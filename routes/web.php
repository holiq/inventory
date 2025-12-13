<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerDatatableController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierDatatableController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDatatableController;
use App\Http\Controllers\DashboardController;
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
});
