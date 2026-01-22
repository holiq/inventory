<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="page-heading">
        <h3>Dashboard Statistics</h3>
        <p class="text-muted">Overview of your inventory system</p>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-9">
                {{-- Statistics Cards --}}
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon purple mb-2">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Products</h6>
                                        <h6 class="font-extrabold mb-0">{{ number_format($totalProducts) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon blue mb-2">
                                            <i class="iconly-boldProfile"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Suppliers</h6>
                                        <h6 class="font-extrabold mb-0">{{ number_format($totalSuppliers) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon green mb-2">
                                            <i class="iconly-boldAdd-User"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Customers</h6>
                                        <h6 class="font-extrabold mb-0">{{ number_format($totalCustomers) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon red mb-2">
                                            <i class="iconly-boldBookmark"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Low Stock Items</h6>
                                        <h6 class="font-extrabold mb-0">{{ number_format($lowStockCount) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Monthly Summary --}}
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="stats-icon bg-success mb-0">
                                            <i class="bi bi-cart-plus-fill"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h6 class="text-muted font-semibold mb-1">Monthly Purchases</h6>
                                        <h4 class="font-extrabold mb-0">Rp {{ number_format($monthlyPurchases, 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="stats-icon bg-primary mb-0">
                                            <i class="bi bi-bag-check-fill"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h6 class="text-muted font-semibold mb-1">Monthly Sales</h6>
                                        <h4 class="font-extrabold mb-0">Rp {{ number_format($monthlySales, 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stock Summary --}}
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="stats-icon bg-info mb-0">
                                            <i class="bi bi-archive-fill"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h6 class="text-muted font-semibold mb-1">Total Stock Quantity</h6>
                                        <h4 class="font-extrabold mb-0">{{ number_format($totalStock) }} items</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="stats-icon bg-warning mb-0">
                                            <i class="bi bi-currency-dollar"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h6 class="text-muted font-semibold mb-1">Total Stock Value</h6>
                                        <h4 class="font-extrabold mb-0">Rp {{ number_format($totalStockValue, 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Low Stock Alert --}}
                @if($lowStockProducts->count() > 0)
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-danger">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Low Stock Alert
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th class="text-end">Current Stock</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($lowStockProducts as $product)
                                            <tr>
                                                <td>{{ $product->name }}</td>
                                                <td class="text-end">
                                                    <span class="badge bg-danger">{{ $product->current_stock }}</span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('purchases.create') }}" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-cart-plus"></i> Restock
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Recent Transactions --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Recent Transactions</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Description</th>
                                                <th>Reference</th>
                                                <th class="text-end">Qty</th>
                                                <th class="text-end">Total Price</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentTransactions as $transaction)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-{{ $transaction['type_class'] }}">
                                                        {{ $transaction['type'] }}
                                                    </span>
                                                </td>
                                                <td>{{ $transaction['description'] }}</td>
                                                <td>{{ $transaction['reference'] }}</td>
                                                <td class="text-end">{{ number_format($transaction['qty']) }}</td>
                                                <td class="text-end">{{ $transaction['total_price'] }}</td>
                                                <td>{{ $transaction['date']->format('d M Y H:i') }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">No transactions yet</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if($recentTransactions->count() > 0)
                                <div class="text-center mt-3">
                                    <a href="{{ route('transaction-report.index') }}" class="btn btn-primary">
                                        View All Transactions
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-12 col-lg-3">
                {{-- User Profile --}}
                <div class="card">
                    <div class="card-header">
                        <h4>User Profile</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-xl">
                                <img src="{{ asset('assets/compiled/jpg/1.jpg') }}" alt="Avatar">
                            </div>
                            <div class="ms-3 name">
                                <h5 class="font-bold">{{ auth()->user()->name }}</h5>
                                <h6 class="text-muted mb-0">{{ auth()->user()->email }}</h6>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="card">
                    <div class="card-header">
                        <h4>Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('purchases.create') }}" class="btn btn-success">
                                <i class="bi bi-cart-plus"></i> New Purchase
                            </a>
                            <a href="{{ route('sales.create') }}" class="btn btn-primary">
                                <i class="bi bi-bag-check"></i> New Sale
                            </a>
                            <a href="{{ route('stock-opname.index') }}" class="btn btn-warning">
                                <i class="bi bi-clipboard-check"></i> Stock Opname
                            </a>
                            <a href="{{ route('stock-report.index') }}" class="btn btn-info">
                                <i class="bi bi-archive"></i> Stock Report
                            </a>
                        </div>
                    </div>
                </div>

                {{-- System Info --}}
                <div class="card">
                    <div class="card-header">
                        <h4>System Info</h4>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Laravel</span>
                                <span class="badge bg-primary">{{ app()->version() }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>PHP</span>
                                <span class="badge bg-success">{{ PHP_VERSION }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Today</span>
                                <span class="badge bg-info">{{ now()->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
