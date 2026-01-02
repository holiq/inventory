<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function __construct(protected StockService $stockService) {}

    public function index()
    {
        return view('sales.index');
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('sales.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
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

            // Create sale
            $sale = Sale::create([
                'customer_id' => $validated['customer_id'],
                'description' => $validated['description'],
                'qty' => $totalQty,
                'total_price' => $totalPrice,
            ]);

            // Create sale items and update stock
            foreach ($validated['items'] as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'total_price' => $item['qty'] * $item['price'],
                ]);

                // Update stock
                $this->stockService->stockOut($item['product_id'], $item['qty'], $item['price'], 'sale');
            }

            DB::commit();

            return redirect()->route('sales.index')
                ->with('success', 'Sale created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors(['error' => 'Failed to create sale: '.$e->getMessage()]);
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'items.product']);

        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $sale->load('items');
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('sales.edit', compact('sale', 'customers', 'products'));
    }

    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
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

            // Update sale
            $sale->update([
                'customer_id' => $validated['customer_id'],
                'description' => $validated['description'],
                'qty' => $totalQty,
                'total_price' => $totalPrice,
            ]);

            // Reverse old stock
            foreach ($sale->items as $oldItem) {
                $this->stockService->reverseStockOut($oldItem->product_id, $oldItem->qty);
            }

            // Delete old items and create new ones
            $sale->items()->delete();

            foreach ($validated['items'] as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'total_price' => $item['qty'] * $item['price'],
                ]);

                // Update stock using service
                $this->stockService->stockOut($item['product_id'], $item['qty'], $item['price'], 'sale');
            }

            DB::commit();

            return redirect()->route('sales.index')
                ->with('success', 'Sale updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors(['error' => 'Failed to update sale: '.$e->getMessage()]);
        }
    }

    public function destroy(Sale $sale)
    {
        DB::beginTransaction();
        try {
            // Reverse stock before deleting
            foreach ($sale->items as $item) {
                $this->stockService->reverseStockOut($item->product_id, $item->qty);
            }

            $sale->items()->delete();
            $sale->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale deleted successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete sale: '.$e->getMessage(),
            ], 500);
        }
    }
}
