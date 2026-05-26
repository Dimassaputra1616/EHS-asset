@extends('layouts.app')

@section('title', 'Add Supplier')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <div class="card border-0 shadow-sm mt-3 animate-fade-in">
            <div class="card-header bg-white py-3 border-bottom-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="text-white p-2 rounded-3 shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: var(--hse-red-gradient);">
                        <i class="bi bi-truck fs-5"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Add New Supplier</h5>
                        <p class="text-muted small mb-0">Register a new vendor or partner</p>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4 pt-2">
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <div class="col-12">
                            <label for="name" class="form-label fw-bold">Supplier / Vendor Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg fs-6 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. PT Maju Jaya" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="contact_person" class="form-label fw-bold">Contact Person</label>
                            <input type="text" name="contact_person" id="contact_person" class="form-control form-control-lg fs-6 @error('contact_person') is-invalid @enderror" value="{{ old('contact_person') }}" placeholder="e.g. Budi Santoso">
                            @error('contact_person')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-bold">Phone Number</label>
                            <input type="text" name="phone" id="phone" class="form-control form-control-lg fs-6 @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="e.g. 08123456789">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-12">
                            <label for="email" class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg fs-6 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="e.g. contact@supplier.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label fw-bold">Address</label>
                            <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3" placeholder="Full address of the supplier">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4 border-light">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-light fw-bold px-4">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                        <button type="submit" class="btn btn-hse-red fw-bold px-5">
                            <i class="bi bi-check2-circle me-1"></i> Save Supplier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
