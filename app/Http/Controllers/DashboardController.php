<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductTransaction;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Count statistics
        $totalProducts = Product::count();
        $totalSuppliers = Supplier::count();
        $totalCustomers = Customer::count();

        // Low stock items (current_stock < 10)
        $lowStockCount = ProductStock::where('current_stock', '<', 10)->count();

        // Products with stock info
        $lowStockProducts = Product::join('product_stocks', 'products.id', '=', 'product_stocks.product_id')
            ->where('product_stocks.current_stock', '<', 10)
            ->select('products.name', 'product_stocks.current_stock')
            ->orderBy('product_stocks.current_stock', 'asc')
            ->limit(5)
            ->get();

        // Recent transactions (last 10)
        $recentTransactions = collect();

        // Get recent purchases
        $recentPurchases = Purchase::with('supplier')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($purchase) {
                return [
                    'type' => 'Purchase',
                    'type_class' => 'success',
                    'description' => $purchase->description,
                    'reference' => $purchase->supplier->name ?? '-',
                    'qty' => $purchase->qty,
                    'total_price' => $purchase->total_price,
                    'date' => $purchase->created_at,
                ];
            });

        // Get recent sales
        $recentSales = Sale::with('customer')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($sale) {
                return [
                    'type' => 'Sale',
                    'type_class' => 'primary',
                    'description' => $sale->description,
                    'reference' => $sale->customer->name ?? '-',
                    'qty' => $sale->qty,
                    'total_price' => $sale->total_price,
                    'date' => $sale->created_at,
                ];
            });

        // Merge and sort by date
        $recentTransactions = $recentPurchases
            ->merge($recentSales)
            ->sortByDesc('date')
            ->take(10);

        // Stock summary
        $totalStock = ProductStock::sum('current_stock');
        $totalStockValue = ProductStock::join('products', 'product_stocks.product_id', '=', 'products.id')
            ->sum(DB::raw('product_stocks.current_stock * products.price'));

        // Monthly statistics
        $currentMonth = now()->format('Y-m');
        $monthlyPurchases = Purchase::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])->sum('total_price');
        $monthlySales = Sale::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])->sum('total_price');

        return view('dashboard', compact(
            'totalProducts',
            'totalSuppliers',
            'totalCustomers',
            'lowStockCount',
            'lowStockProducts',
            'recentTransactions',
            'totalStock',
            'totalStockValue',
            'monthlyPurchases',
            'monthlySales'
        ));
    }
}
