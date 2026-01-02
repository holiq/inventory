<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function __construct(protected StockService $stockService) {}

    public function index()
    {
        return view('purchases.index');
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'description' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Calculate totals
            $totalQty = 0;
            $totalPrice = 0;

            foreach ($validated['items'] as $item) {
                $totalQty += $item['qty'];
                $totalPrice += $item['qty'] * $item['price'];
            }

            // Create purchase
            $purchase = Purchase::create([
                'supplier_id' => $validated['supplier_id'],
                'description' => $validated['description'],
                'qty' => $totalQty,
                'total_price' => $totalPrice,
            ]);

            // Create purchase items and update stock
            foreach ($validated['items'] as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'total_price' => $item['qty'] * $item['price'],
                ]);

                // Update stock
                $this->stockService->stockIn($item['product_id'], $item['qty'], $item['price'], 'purchase');
            }

            DB::commit();

            return redirect()->route('purchases.index')
                ->with('success', 'Purchase created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors(['error' => 'Failed to create purchase: '.$e->getMessage()]);
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'items.product']);

        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        $purchase->load('items');
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('purchases.edit', compact('purchase', 'suppliers', 'products'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'description' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Calculate totals
            $totalQty = 0;
            $totalPrice = 0;

            foreach ($validated['items'] as $item) {
                $totalQty += $item['qty'];
                $totalPrice += $item['qty'] * $item['price'];
            }

            // Update purchase
            $purchase->update([
                'supplier_id' => $validated['supplier_id'],
                'description' => $validated['description'],
                'qty' => $totalQty,
                'total_price' => $totalPrice,
            ]);

            // Reverse old stock
            foreach ($purchase->items as $oldItem) {
                $this->stockService->reverseStockIn($oldItem->product_id, $oldItem->qty);
            }

            // Delete old items and create new ones
            $purchase->items()->delete();

            foreach ($validated['items'] as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'total_price' => $item['qty'] * $item['price'],
                ]);

                // Update stock using service
                $this->stockService->stockIn($item['product_id'], $item['qty'], $item['price'], 'purchase');
            }

            DB::commit();

            return redirect()->route('purchases.index')
                ->with('success', 'Purchase updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors(['error' => 'Failed to update purchase: '.$e->getMessage()]);
        }
    }

    public function destroy(Purchase $purchase)
    {
        DB::beginTransaction();
        try {
            // Reverse stock before deleting
            foreach ($purchase->items as $item) {
                $this->stockService->reverseStockIn($item->product_id, $item->qty);
            }

            $purchase->items()->delete();
            $purchase->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase deleted successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete purchase: '.$e->getMessage(),
            ], 500);
        }
    }
}
