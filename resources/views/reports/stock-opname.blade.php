<x-app-layout>
    <x-slot name="title">Stock Opname</x-slot>

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Stock Opname</h3>
                    <p class="text-subtitle text-muted">Adjust stock based on physical count</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('stock-report.index') }}">Stock Report</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Stock Opname</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Submit Stock Opname</h5>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('stock-opname.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="product_id" class="form-label">Product <span class="text-danger">*</span></label>
                                    <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                                        <option value="">-- Select Product --</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                    data-current-stock="{{ optional($product->productStock)->current_stock ?? 0 }}"
                                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Current Stock (System)</label>
                                    <input type="text" id="current_stock_display" class="form-control" readonly value="0">
                                </div>

                                <div class="mb-3">
                                    <label for="counted_stock" class="form-label">Counted Stock (Physical) <span class="text-danger">*</span></label>
                                    <input type="number" name="counted_stock" id="counted_stock"
                                           class="form-control @error('counted_stock') is-invalid @enderror"
                                           min="0" value="{{ old('counted_stock', 0) }}" required>
                                    @error('counted_stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Difference</label>
                                    <input type="text" id="difference_display" class="form-control" readonly value="0">
                                    <small class="text-muted">Positive = stock will increase, Negative = stock will decrease</small>
                                </div>

                                <div class="mb-3">
                                    <label for="note" class="form-label">Note</label>
                                    <textarea name="note" id="note" rows="2" class="form-control @error('note') is-invalid @enderror">{{ old('note') }}</textarea>
                                    @error('note')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('stock-report.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left"></i> Back
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Submit Adjustment
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Instructions</h5>
                        </div>
                        <div class="card-body">
                            <ol>
                                <li class="mb-2">Select the product you want to adjust</li>
                                <li class="mb-2">The system will show the current stock recorded</li>
                                <li class="mb-2">Enter the actual physical count from your warehouse</li>
                                <li class="mb-2">The difference will be calculated automatically</li>
                                <li class="mb-2">Add a note if needed (optional)</li>
                                <li class="mb-2">Click "Submit Adjustment" to save</li>
                            </ol>
                            <div class="alert alert-info mb-0">
                                <i class="bi bi-info-circle"></i>
                                Stock opname will create a transaction record for audit purposes.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('styles')
    <link rel="stylesheet" href="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.css') }}">
    @endpush

    @push('scripts')
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            function updateDifference() {
                const currentStock = parseInt($('#current_stock_display').val()) || 0;
                const countedStock = parseInt($('#counted_stock').val()) || 0;
                const diff = countedStock - currentStock;

                let displayText = diff.toString();
                if (diff > 0) displayText = '+' + diff;

                $('#difference_display').val(displayText);

                // Color coding
                if (diff > 0) {
                    $('#difference_display').removeClass('text-danger').addClass('text-success');
                } else if (diff < 0) {
                    $('#difference_display').removeClass('text-success').addClass('text-danger');
                } else {
                    $('#difference_display').removeClass('text-success text-danger');
                }
            }

            $('#product_id').on('change', function() {
                const selected = $(this).find(':selected');
                const currentStock = selected.data('current-stock') || 0;
                $('#current_stock_display').val(currentStock);
                updateDifference();
            });

            $('#counted_stock').on('input', function() {
                updateDifference();
            });

            // Initialize on page load
            $('#product_id').trigger('change');

            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
            @endif
        });
    </script>
    @endpush
</x-app-layout>
