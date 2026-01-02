<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerDatatableController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDatatableController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseDatatableController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleDatatableController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierDatatableController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Customer Management
    Route::get('customers/datatable', CustomerDatatableController::class)->name('customers.datatable');
    Route::resource('customers', CustomerController::class);
    // Supplier Management
    Route::get('suppliers/datatable', SupplierDatatableController::class)->name('suppliers.datatable');
    Route::resource('suppliers', SupplierController::class);
    // Product Management
    Route::get('products/datatable', ProductDatatableController::class)->name('products.datatable');
    Route::resource('products', ProductController::class);

    // Purchase Management
    Route::get('purchases/datatable', PurchaseDatatableController::class)->name('purchases.datatable');
    Route::resource('purchases', PurchaseController::class);

    // Sale Management
    Route::get('sales/datatable', SaleDatatableController::class)->name('sales.datatable');
    Route::resource('sales', SaleController::class);
});
