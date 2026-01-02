<x-app-layout>
    <x-slot name="title">Purchase Details</x-slot>

    <div class="page-heading">
        <h3>Purchase Details</h3>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Purchase #{{ $purchase->id }}</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Supplier:</strong> {{ $purchase->supplier->name }}</p>
                        <p><strong>Date:</strong> {{ $purchase->created_at->format('Y-m-d') }}</p>
                        <p><strong>Total Amount:</strong> {{ $purchase->total_price }}</p>

                        <h5 class="mt-4">Items</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchase->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ $item->price }}</td>
                                    <td>{{ $item->total_price }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false,
            });
            @endif
        });
    </script>
    @endpush
</x-app-layout>
