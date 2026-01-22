<x-app-layout>
    <x-slot name="title">Stock Report</x-slot>

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Stock Report</h3>
                    <p class="text-subtitle text-muted">View current stock levels for all products</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Stock Report</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Current Stock Levels</h5>
                        <a href="{{ route('stock-opname.index') }}" class="btn btn-primary">
                            <i class="bi bi-clipboard-check"></i> Stock Opname
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive pt-2">
                        <table class="table table-striped" id="stockTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Product Name</th>
                                    <th class="text-end">Qty In</th>
                                    <th class="text-end">Qty Out</th>
                                    <th class="text-end">Current Stock</th>
                                    <th>Last Updated</th>
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
            $('#stockTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('stock-report.datatable') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'product_name', name: 'name' },
                    { data: 'qty_in', name: 'qty_in', className: 'text-end' },
                    { data: 'qty_out', name: 'qty_out', className: 'text-end' },
                    { data: 'current_stock', name: 'current_stock', className: 'text-end' },
                    { data: 'updated_at', name: 'updated_at' }
                ],
                pageLength: 10,
                order: [[1, 'asc']],
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
