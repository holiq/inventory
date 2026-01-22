<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOpnameController extends Controller
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index()
    {
        $products = Product::with('productStock')->orderBy('name')->get();

        return view('reports.stock-opname', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'counted_stock' => 'required|integer|min:0',
            'note' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $productId = (int) $validated['product_id'];
            $counted = (int) $validated['counted_stock'];

            $current = $this->stockService->getCurrentStock($productId);
            $diff = $counted - $current;
            $note = $validated['note'] ?? null;

            if ($diff > 0) {
                $this->stockService->stockIn($productId, $diff, 0, 'stock_opname', $note);
            } elseif ($diff < 0) {
                $this->stockService->stockOut($productId, abs($diff), 0, 'stock_opname', $note);
            }

            DB::commit();

            $message = $diff === 0
                ? 'No adjustment needed. Stock already matches.'
                : 'Stock adjusted successfully!';

            return redirect()->route('stock-opname.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Failed to submit stock opname: '.$e->getMessage()])
                ->withInput();
        }
    }
}
