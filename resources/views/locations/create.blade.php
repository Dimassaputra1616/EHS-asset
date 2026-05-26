@extends('layouts.app')

@section('title', 'Create Location')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-hse-red text-white p-2 rounded-3 shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-geo-alt fs-5"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Add New Location</h5>
                        <p class="text-muted small mb-0">Register a new building, floor, or room for asset placement</p>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4 pt-2">
                <form action="{{ route('locations.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="building" class="form-label fw-bold">Building Name</label>
                            <input type="text" name="building" id="building" class="form-control form-control-lg fs-6 @error('building') is-invalid @enderror" value="{{ old('building') }}" placeholder="e.g. Main HQ, Block A">
                            @error('building')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="floor" class="form-label fw-bold">Floor / Level</label>
                            <input type="text" name="floor" id="floor" class="form-control form-control-lg fs-6 @error('floor') is-invalid @enderror" value="{{ old('floor') }}" placeholder="e.g. 1, 2, Basement">
                            @error('floor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="name" class="form-label fw-bold">Room / Exact Location Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg fs-6 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Server Room A, Meeting Room 101" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label fw-bold">Description</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Additional details to find this location (optional)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4 border-light">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('locations.index') }}" class="btn btn-light fw-bold px-4">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                        <button type="submit" class="btn btn-hse-red fw-bold px-5">
                            <i class="bi bi-check2-circle me-1"></i> Save Location
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
