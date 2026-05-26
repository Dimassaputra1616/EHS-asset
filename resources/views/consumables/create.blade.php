@extends('layouts.app')

@section('title', 'Add Consumable')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <div class="card border-0 shadow-sm mt-3 animate-fade-in">
            <div class="card-header bg-white py-3 border-bottom-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="text-white p-2 rounded-3 shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: var(--hse-red-gradient);">
                        <i class="bi bi-basket fs-5"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Add New Consumable</h5>
                        <p class="text-muted small mb-0">Record items that are used up over time (e.g. Printer Ink, Paper)</p>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4 pt-2">
                <form action="{{ route('consumables.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Item Code <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" id="consumable-code-input" name="code" class="form-control form-control-lg fs-6 font-monospace @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="Pilih kategori terlebih dahulu..." required>
                                <span class="input-group-text bg-light d-none" id="code-loading-spinner">
                                    <span class="spinner-border spinner-border-sm text-secondary" role="status"></span>
                                </span>
                            </div>
                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-bold">Item Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-lg fs-6 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. A4 Copy Paper" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select form-select-lg fs-6 @error('category_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Supplier</label>
                            <select name="supplier_id" class="form-select form-select-lg fs-6 @error('supplier_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Unit Type <span class="text-danger">*</span></label>
                            <input type="text" name="unit" class="form-control form-control-lg fs-6 @error('unit') is-invalid @enderror" value="{{ old('unit') }}" placeholder="e.g. Reams, Boxes" required>
                            @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Starting Stock <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control form-control-lg fs-6 @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" min="0" required>
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Minimum Stock Alert <span class="text-danger">*</span></label>
                            <input type="number" name="min_stock" class="form-control form-control-lg fs-6 @error('min_stock') is-invalid @enderror" value="{{ old('min_stock', 5) }}" min="0" required>
                            @error('min_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Additional details...">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <hr class="my-4 border-light">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('consumables.index') }}" class="btn btn-light fw-bold px-4">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                        <button type="submit" class="btn btn-hse-red fw-bold px-5">
                            <i class="bi bi-check2-circle me-1"></i> Save Consumable
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
        const codeInput = document.getElementById('consumable-code-input');
        const spinner = document.getElementById('code-loading-spinner');

        function fetchConsumableCode(categoryId) {
            if (!categoryId) return;
            
            spinner.classList.remove('d-none');
            codeInput.readOnly = true;
            
            fetch(`/consumables/generate-code/${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.code) {
                        codeInput.value = data.code;
                    }
                    spinner.classList.add('d-none');
                    codeInput.readOnly = false;
                })
                .catch(error => {
                    console.error('Error generating consumable code:', error);
                    spinner.classList.add('d-none');
                    codeInput.readOnly = false;
                });
        }

        // When category is changed
        categorySelect.addEventListener('change', function() {
            fetchConsumableCode(this.value);
        });

        // Trigger on load if there's already a category selected
        if (categorySelect.value) {
            fetchConsumableCode(categorySelect.value);
        }
    });
</script>
@endpush
@endsection
