<?php

namespace App\Services;

use App\Models\ProductStock;
use App\Models\ProductTransaction;
use Illuminate\Support\Facades\Auth;

class StockService
{
    /**
     * Process stock in (purchase, stock opname in, return, etc.)
     */
    public function stockIn(int $productId, int $qty, float $price, string $type = 'purchase', ?string $note = null): void
    {
        $this->createTransaction($productId, $qty, 0, $price, $type, $note);
        $this->updateStock($productId, $qty, 0);
    }

    /**
     * Process stock out (sale, stock opname out, etc.)
     */
    public function stockOut(int $productId, int $qty, float $price, string $type = 'sale', ?string $note = null): void
    {
        $this->createTransaction($productId, 0, $qty, $price, $type, $note);
        $this->updateStock($productId, 0, $qty);
    }

    /**
     * Process stock adjustment (for stock opname - can be in or out)
     */
    public function stockAdjustment(int $productId, int $qtyIn, int $qtyOut, float $price, string $type = 'adjustment', ?string $note = null): void
    {
        $this->createTransaction($productId, $qtyIn, $qtyOut, $price, $type, $note);
        $this->updateStock($productId, $qtyIn, $qtyOut);
    }

    /**
     * Create product transaction history
     */
    protected function createTransaction(int $productId, int $qtyIn, int $qtyOut, float $price, string $type, ?string $note = null): ProductTransaction
    {
        $totalQty = $qtyIn > 0 ? $qtyIn : $qtyOut;

        return ProductTransaction::create([
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'qty_in' => $qtyIn,
            'qty_out' => $qtyOut,
            'type' => $type,
            'price' => $price,
            'total_price' => $totalQty * $price,
            'note' => $note,
        ]);
    }

    /**
     * Update or create product stock (only 1 row per product)
     */
    protected function updateStock(int $productId, int $qtyIn, int $qtyOut): ProductStock
    {
        $productStock = ProductStock::firstOrNew(['product_id' => $productId]);

        $productStock->qty_in = ($productStock->qty_in ?? 0) + $qtyIn;
        $productStock->qty_out = ($productStock->qty_out ?? 0) + $qtyOut;
        $productStock->current_stock = $productStock->qty_in - $productStock->qty_out;
        $productStock->save();

        return $productStock;
    }

    /**
     * Get current stock for a product
     */
    public function getCurrentStock(int $productId): int
    {
        $stock = ProductStock::find($productId);

        return $stock ? $stock->current_stock : 0;
    }

    /**
     * Check if stock is available for sale
     */
    public function isStockAvailable(int $productId, int $qty): bool
    {
        return $this->getCurrentStock($productId) >= $qty;
    }

    /**
     * Reverse stock in (for delete/update purchase)
     */
    public function reverseStockIn(int $productId, int $qty): void
    {
        $productStock = ProductStock::find($productId);

        if ($productStock) {
            $productStock->qty_in = max(0, $productStock->qty_in - $qty);
            $productStock->current_stock = $productStock->qty_in - $productStock->qty_out;
            $productStock->save();
        }
    }

    /**
     * Reverse stock out (for delete/update sale)
     */
    public function reverseStockOut(int $productId, int $qty): void
    {
        $productStock = ProductStock::find($productId);

        if ($productStock) {
            $productStock->qty_out = max(0, $productStock->qty_out - $qty);
            $productStock->current_stock = $productStock->qty_in - $productStock->qty_out;
            $productStock->save();
        }
    }
}
