@extends('layouts.app')

@section('title', 'Edit Asset')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-12">
        <div class="card border-0 mt-3 animate-fade-in" style="border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);">
            <div class="card-header bg-white py-4 border-bottom-0" style="border-radius: 20px 20px 0 0;">
                <div class="d-flex align-items-center gap-3">
                    <div class="form-header-icon-box">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fw-bold" style="color: #1e293b; font-size: 1.25rem;">Edit Fixed Asset</h5>
                        <p class="text-muted small mb-0">Update details for <strong class="text-dark">{{ $asset->name }}</strong></p>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4 pt-0">
                <form action="{{ route('assets.update', $asset->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4">
                        <!-- SECTION 1: IDENTIFICATION -->
                        <div class="col-12 mb-1">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2.5 py-1.5 fw-bold" style="font-size: 0.7rem;">01</span>
                                <span class="form-section-title">Asset Identification</span>
                                <div class="flex-grow-1 border-bottom border-light ms-2"></div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Asset Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control font-monospace @error('code') is-invalid @enderror" value="{{ old('code', $asset->code) }}" required>
                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Asset Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $asset->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- SECTION 2: CLASSIFICATION & SOURCE -->
                        <div class="col-12 mt-5 mb-1">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2.5 py-1.5 fw-bold" style="font-size: 0.7rem;">02</span>
                                <span class="form-section-title">Classification & Logistics</span>
                                <div class="flex-grow-1 border-bottom border-light ms-2"></div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $asset->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Location <span class="text-danger">*</span></label>
                            <select name="location_id" class="form-select @error('location_id') is-invalid @enderror" required>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ old('location_id', $asset->location_id) == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                @endforeach
                            </select>
                            @error('location_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Supplier / Vendor <span class="text-danger">*</span></label>
                            <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $asset->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- SECTION 3: ACQUISITION & STATUS -->
                        <div class="col-12 mt-5 mb-1">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2.5 py-1.5 fw-bold" style="font-size: 0.7rem;">03</span>
                                <span class="form-section-title">Acquisition & Status</span>
                                <div class="flex-grow-1 border-bottom border-light ms-2"></div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Condition <span class="text-danger">*</span></label>
                            <select name="condition" class="form-select @error('condition') is-invalid @enderror" required>
                                <option value="Good" {{ old('condition', $asset->condition) == 'Good' ? 'selected' : '' }}>Good</option>
                                <option value="Fair" {{ old('condition', $asset->condition) == 'Fair' ? 'selected' : '' }}>Fair</option>
                                <option value="Poor" {{ old('condition', $asset->condition) == 'Poor' ? 'selected' : '' }}>Poor</option>
                            </select>
                            @error('condition') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="In Stock" {{ old('status', $asset->status) == 'In Stock' ? 'selected' : '' }}>In Stock</option>
                                <option value="In Use" {{ old('status', $asset->status) == 'In Use' ? 'selected' : '' }}>In Use</option>
                                <option value="Maintenance" {{ old('status', $asset->status) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="Retired" {{ old('status', $asset->status) == 'Retired' ? 'selected' : '' }}>Retired</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Purchase Date</label>
                            <input type="date" name="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" value="{{ old('purchase_date', $asset->purchase_date) }}">
                            @error('purchase_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Purchase Cost <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="purchase_cost" class="form-control @error('purchase_cost') is-invalid @enderror" value="{{ old('purchase_cost', ceil($asset->purchase_cost)) }}" min="0" required>
                            </div>
                            @error('purchase_cost') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- SECTION 4: ASSIGNMENT & NOTES -->
                        <div class="col-12 mt-5 mb-1">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2.5 py-1.5 fw-bold" style="font-size: 0.7rem;">04</span>
                                <span class="form-section-title">Assignment & Notes</span>
                                <div class="flex-grow-1 border-bottom border-light ms-2"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Assigned To (Holder / Pemegang / Penanggung Jawab)</label>
                            <input type="text" name="assigned_to" class="form-control @error('assigned_to') is-invalid @enderror" value="{{ old('assigned_to', $asset->assigned_to) }}" placeholder="e.g. John Doe (Pos Security) atau Kosongkan jika di Gudang">
                            @error('assigned_to') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description / Notes</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Add any descriptive details or observations...">{{ old('description', $asset->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <hr class="my-4 border-light" style="opacity: 0.08;">

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary fw-bold px-4 py-2.5" style="border-radius: 10px; border: 1.5px solid #cbd5e1; color: #475569; transition: all 0.2s ease;">
                            <i class="bi bi-arrow-left me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-hse-red shadow-sm px-5 py-2.5" style="border-radius: 10px;">
                            <i class="bi bi-check2-circle me-1"></i> Update Asset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
