<x-app-layout>
    <x-slot name="title">Edit Purchase</x-slot>

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Purchase</h3>
                    <p class="text-subtitle text-muted">Update purchase transaction</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">Purchases</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <form action="{{ route('purchases.update', $purchase->id) }}" method="POST" id="purchaseForm">
                @csrf
                @method('PUT')

                {{-- dipakai untuk menampung id item yang dihapus saat edit --}}
                <div id="deletedItemsContainer"></div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Purchase Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                                            <select class="form-select @error('supplier_id') is-invalid @enderror"
                                                    id="supplier_id"
                                                    name="supplier_id"
                                                    required>
                                                <option value="">Select Supplier</option>
                                                @foreach($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        {{ old('supplier_id', $purchase->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('supplier_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('description') is-invalid @enderror"
                                                   id="description"
                                                   name="description"
                                                   value="{{ old('description', $purchase->description) }}"
                                                   placeholder="Enter purchase description"
                                                   required>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="card-title mb-0">Purchase Items</h4>
                                    <button type="button" class="btn btn-sm btn-success" id="addItemBtn">
                                        <i class="bi bi-plus-circle"></i> Add Item
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="itemsTable">
                                        <thead>
                                            <tr>
                                                <th width="40%">Product</th>
                                                <th width="15%">Qty</th>
                                                <th width="20%">Price</th>
                                                <th width="20%">Subtotal</th>
                                                <th width="5%">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody id="itemsTableBody">
                                            @if(old('items'))
                                                @foreach(old('items') as $i => $item)
                                                    @php
                                                        $qty = (float) ($item['qty'] ?? 0);
                                                        $price = (float) ($item['price'] ?? 0);
                                                        $subtotal = $qty * $price;
                                                        $itemId = $item['id'] ?? null;
                                                    @endphp

                                                    <tr class="item-row" data-item-id="{{ $itemId }}">
                                                        <td>
                                                            {{-- kalau old items menyimpan id, tetap kirim supaya update lebih gampang --}}
                                                            @if($itemId)
                                                                <input type="hidden" name="items[{{ $i }}][id]" value="{{ $itemId }}">
                                                            @endif

                                                            <select class="form-select form-select-sm product-select" name="items[{{ $i }}][product_id]" required>
                                                                <option value="">Select Product</option>
                                                                @foreach($products as $product)
                                                                    <option value="{{ $product->id }}"
                                                                        data-price="{{ $product->price }}"
                                                                        {{ (string)($item['product_id'] ?? '') === (string)$product->id ? 'selected' : '' }}>
                                                                        {{ $product->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control form-control-sm qty-input" name="items[{{ $i }}][qty]" min="1" value="{{ $item['qty'] ?? 1 }}" required>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm form-amount price-input" name="items[{{ $i }}][price]" min="0" step="0.01" value="{{ $item['price'] ?? 0 }}" required>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm subtotal-display" readonly value="Rp {{ number_format($subtotal, 0, ',', '.') }}">
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-sm btn-danger remove-item-btn">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                @forelse($purchase->items as $i => $item)
                                                    @php
                                                        $subtotal = ((float) $item->qty) * ((float) unformatRupiah($item->price))
                                                    @endphp

                                                    <tr class="item-row" data-item-id="{{ $item->id }}">
                                                        <td>
                                                            <input type="hidden" name="items[{{ $i }}][id]" value="{{ $item->id }}">

                                                            <select class="form-select form-select-sm product-select" name="items[{{ $i }}][product_id]" required>
                                                                <option value="">Select Product</option>
                                                                @foreach($products as $product)
                                                                    <option value="{{ $product->id }}"
                                                                        data-price="{{ unformatRupiah($item->price) }}"
                                                                        {{ (string)$item->product_id === (string)$product->id ? 'selected' : '' }}>
                                                                        {{ $product->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control form-control-sm qty-input" name="items[{{ $i }}][qty]" min="1" value="{{ $item->qty }}" required>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm form-amount price-input" name="items[{{ $i }}][price]" min="0" step="0.01" value="{{ $item->price }}" required>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm subtotal-display" readonly value="{{ formatRupiah($subtotal) }}">
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-sm btn-danger remove-item-btn">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    {{-- kalau purchase belum punya item, kasih 1 baris default --}}
                                                    <tr class="item-row">
                                                        <td>
                                                            <select class="form-select form-select-sm product-select" name="items[0][product_id]" required>
                                                                <option value="">Select Product</option>
                                                                @foreach($products as $product)
                                                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                                        {{ $product->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control form-control-sm qty-input" name="items[0][qty]" min="1" value="1" required>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm form-amount price-input" name="items[0][price]" required>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm subtotal-display" readonly value="Rp 0">
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-sm btn-danger remove-item-btn" disabled>
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            @endif
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <td colspan="2" class="text-end"><strong>Total:</strong></td>
                                                <td><strong id="totalQty">0</strong></td>
                                                <td><strong id="totalPrice">Rp 0</strong></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left"></i> Back
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Update Purchase
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>

    @push('styles')
    <link rel="stylesheet" href="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.css') }}">
    @endpush

    @push('scripts')
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        // itemIndex mulai dari jumlah row existing (old items atau items dari DB)
        let itemIndex = {{ max(1, count(old('items', [])) ?: ($purchase->items->count() ?? 1)) }};

        $(document).ready(function() {
            // Add new item row
            $('#addItemBtn').click(function() {
                const newRow = `
                    <tr class="item-row">
                        <td>
                            <select class="form-select form-select-sm product-select" name="items[${itemIndex}][product_id]" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm qty-input" name="items[${itemIndex}][qty]" min="1" value="1" required>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm form-amount price-input" name="items[${itemIndex}][price]" required>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm subtotal-display" readonly value="Rp 0">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger remove-item-btn">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                $('#itemsTableBody').append(newRow);
                itemIndex++;
                updateRemoveButtons();
                calculateTotal();
            });

            // Remove item row (kalau item existing, simpan id untuk dihapus di backend)
            $(document).on('click', '.remove-item-btn', function() {
                const row = $(this).closest('tr');
                const itemId = row.data('item-id');

                if (itemId) {
                    $('#deletedItemsContainer').append(
                        `<input type="hidden" name="deleted_item_ids[]" value="${itemId}">`
                    );
                }

                row.remove();
                updateRemoveButtons();
                calculateTotal();
            });

            // Auto-fill price when product selected
            $(document).on('change', '.product-select', function() {
                const price = $(this).find(':selected').data('price') || 0;
                $(this).closest('tr').find('.price-input').val(price);
                calculateRowSubtotal($(this).closest('tr'));
            });

            // Calculate subtotal on qty or price change
            $(document).on('input', '.qty-input, .price-input', function() {
                calculateRowSubtotal($(this).closest('tr'));
            });

            function calculateRowSubtotal(row) {
                const qty = parseFloat(row.find('.qty-input').val()) || 0;
                const price = parseFloat(unformatCurrency(row.find('.price-input').val())) || 0;
                const subtotal = qty * price;
                row.find('.subtotal-display').val(formatCurrency(subtotal));
                calculateTotal();
            }

            function calculateTotal() {
                let totalQty = 0;
                let totalPrice = 0;

                $('.item-row').each(function() {
                    const qty = parseFloat($(this).find('.qty-input').val()) || 0;
                    const price = parseFloat(unformatCurrency($(this).find('.price-input').val())) || 0;
                    totalQty += qty;
                    totalPrice += (qty * price);
                });

                $('#totalQty').text(totalQty);
                $('#totalPrice').text(formatCurrency(totalPrice));
            }

            function updateRemoveButtons() {
                const rowCount = $('.item-row').length;
                $('.remove-item-btn').prop('disabled', rowCount === 1);
            }

            // init hitung total saat halaman edit dibuka
            updateRemoveButtons();
            calculateTotal();

            @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `
                    <ul class="text-start">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
            });
            @endif
        });
    </script>
    @endpush
</x-app-layout>
