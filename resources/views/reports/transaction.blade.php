<x-app-layout>
    <x-slot name="title">Transaction Report</x-slot>

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Transaction Report</h3>
                    <p class="text-subtitle text-muted">View all product transaction history</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Transaction Report</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="filter_type" class="form-label">Transaction Type</label>
                            <select id="filter_type" class="form-select">
                                <option value="">All Types</option>
                                <option value="purchase">Purchase</option>
                                <option value="sale">Sale</option>
                                <option value="stock_opname">Stock Opname</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filter_product" class="form-label">Product</label>
                            <select id="filter_product" class="form-select">
                                <option value="">All Products</option>
                                @foreach (\App\Models\Product::orderBy('name')->get() as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="filter_date_from" class="form-label">Date From</label>
                            <input type="date" id="filter_date_from" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label for="filter_date_to" class="form-label">Date To</label>
                            <input type="date" id="filter_date_to" class="form-control">
                        </div>
                        <div class="col-md-2 d-flex align-items-end gap-2">
                            <button type="button" id="btn_filter" class="btn btn-primary">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            <button type="button" id="btn_reset" class="btn btn-secondary">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Transaction History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive pt-2">
                        <table class="table table-striped" id="transactionTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Type</th>
                                    <th>User</th>
                                    <th class="text-end">Qty In</th>
                                    <th class="text-end">Qty Out</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Total</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('styles')
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/table-datatable-jquery.css') }}">
    @endpush

    @push('scripts')
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            const table = $('#transactionTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('transaction-report.datatable') }}',
                    data: function(d) {
                        d.type = $('#filter_type').val();
                        d.product_id = $('#filter_product').val();
                        d.date_from = $('#filter_date_from').val();
                        d.date_to = $('#filter_date_to').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'created_at_formatted', name: 'created_at' },
                    { data: 'product_name', name: 'product.name' },
                    { data: 'type_badge', name: 'type' },
                    { data: 'user_name', name: 'user.name' },
                    { data: 'qty_in', name: 'qty_in', className: 'text-end' },
                    { data: 'qty_out', name: 'qty_out', className: 'text-end' },
                    { data: 'price_formatted', name: 'price', className: 'text-end' },
                    { data: 'total_price_formatted', name: 'total_price', className: 'text-end' },
                    { data: 'note', name: 'note' }
                ],
                pageLength: 25,
                order: [[1, 'desc']],
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                }
            });

            $('#btn_filter').on('click', function() {
                table.ajax.reload();
            });

            $('#btn_reset').on('click', function() {
                $('#filter_type').val('');
                $('#filter_product').val('');
                $('#filter_date_from').val('');
                $('#filter_date_to').val('');
                table.ajax.reload();
            });
        });
    </script>
    @endpush
</x-app-layout>
