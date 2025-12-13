<x-app-layout>
    <x-slot name="title">Edit Supplier</x-slot>

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Supplier</h3>
                    <p class="text-subtitle text-muted">Update supplier information</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Suppliers</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Supplier Information</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Supplier Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $supplier->name) }}"
                                           placeholder="Enter supplier name"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('address') is-invalid @enderror"
                                              id="address"
                                              name="address"
                                              rows="4"
                                              placeholder="Enter supplier address"
                                              required>{{ old('address', $supplier->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save"></i> Update Supplier
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Supplier Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">Created At:</small>
                                <p class="mb-0">{{ $supplier->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Last Updated:</small>
                                <p class="mb-0">{{ $supplier->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                            <hr>
                            <div class="alert alert-light-info mb-0">
                                <i class="bi bi-info-circle"></i>
                                <strong>Note:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>All fields marked with <span class="text-danger">*</span> are required</li>
                                    <li>Changes will be saved immediately</li>
                                </ul>
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
    <script src="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
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
    </script>
    @endpush
</x-app-layout>
