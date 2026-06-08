@extends('layouts.app')

@section('title', 'Add Asset')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-12">
        <div class="card border-0 shadow-sm mt-3 animate-fade-in">
            <div class="card-header bg-white py-3 border-bottom-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="text-white p-2 rounded-3 shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: var(--hse-red-gradient);">
                        <i class="bi bi-box-seam fs-5"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Add New Fixed Asset</h5>
                        <p class="text-muted small mb-0">Record a highly valuable item tracking</p>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4 pt-2">
                <form action="{{ route('assets.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Asset Code <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" id="asset-code-input" name="code" class="form-control form-control-lg fs-6 font-monospace @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="Pilih kategori terlebih dahulu..." required>
                                <span class="input-group-text bg-light d-none" id="code-loading-spinner">
                                    <span class="spinner-border spinner-border-sm text-secondary" role="status"></span>
                                </span>
                            </div>
                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-bold">Asset Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-lg fs-6 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. MacBook Pro M3 16-inch" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select form-select-lg fs-6 @error('category_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Location <span class="text-danger">*</span></label>
                            <select name="location_id" class="form-select form-select-lg fs-6 @error('location_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Select Location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                @endforeach
                            </select>
                            @error('location_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Supplier / Vendor <span class="text-danger">*</span></label>
                            <select name="supplier_id" class="form-select form-select-lg fs-6 @error('supplier_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-bold">Condition <span class="text-danger">*</span></label>
                            <select name="condition" class="form-select form-select-lg fs-6 @error('condition') is-invalid @enderror" required>
                                <option value="Good" {{ old('condition') == 'Good' ? 'selected' : '' }}>Good</option>
                                <option value="Fair" {{ old('condition') == 'Fair' ? 'selected' : '' }}>Fair</option>
                                <option value="Poor" {{ old('condition') == 'Poor' ? 'selected' : '' }}>Poor</option>
                            </select>
                            @error('condition') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-lg fs-6 @error('status') is-invalid @enderror" required>
                                <option value="In Stock" {{ old('status') == 'In Stock' ? 'selected' : '' }}>In Stock</option>
                                <option value="In Use" {{ old('status') == 'In Use' ? 'selected' : '' }}>In Use</option>
                                <option value="Maintenance" {{ old('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="Retired" {{ old('status') == 'Retired' ? 'selected' : '' }}>Retired</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-bold">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="qty" class="form-control form-control-lg fs-6 @error('qty') is-invalid @enderror" value="{{ old('qty', 1) }}" min="1" required>
                            @error('qty') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Purchase Date</label>
                            <input type="date" name="purchase_date" class="form-control form-control-lg fs-6 @error('purchase_date') is-invalid @enderror" value="{{ old('purchase_date') }}">
                            @error('purchase_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Purchase Cost <span class="text-danger">*</span></label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light">{{ config('app.currency_symbol', 'Rp') }}</span>
                                <input type="number" name="purchase_cost" class="form-control fs-6 @error('purchase_cost') is-invalid @enderror" value="{{ old('purchase_cost', 0) }}" min="0" required>
                            </div>
                            @error('purchase_cost') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Assigned To (Holder / Pemegang / Penanggung Jawab)</label>
                            <input type="text" name="assigned_to" class="form-control form-control-lg fs-6 @error('assigned_to') is-invalid @enderror" value="{{ old('assigned_to') }}" placeholder="e.g. John Doe (Pos Security) atau Kosongkan jika di Gudang">
                            @error('assigned_to') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Description / Notes</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Additional specifications or notes...">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <hr class="my-4 border-light">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('assets.index') }}" class="btn btn-light fw-bold px-4">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                        <button type="submit" class="btn btn-hse-red fw-bold px-5">
                            <i class="bi bi-check2-circle me-1"></i> Save Asset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const categorySelect = document.querySelector('select[name="category_id"]');
        const codeInput = document.getElementById('asset-code-input');
        const spinner = document.getElementById('code-loading-spinner');

        function fetchAssetCode(categoryId) {
            if (!categoryId) return;
            
            spinner.classList.remove('d-none');
            codeInput.readOnly = true;
            
            fetch(`/assets/generate-code/${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.code) {
                        codeInput.value = data.code;
                    }
                    spinner.classList.add('d-none');
                    codeInput.readOnly = false;
                })
                .catch(error => {
                    console.error('Error generating asset code:', error);
                    spinner.classList.add('d-none');
                    codeInput.readOnly = false;
                });
        }

        // When category is changed
        categorySelect.addEventListener('change', function() {
            fetchAssetCode(this.value);
        });

        // Trigger on load if there's already a category selected
        if (categorySelect.value) {
            fetchAssetCode(categorySelect.value);
        }
    });
</script>
@endpush
@endsection
