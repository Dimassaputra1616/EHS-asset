@extends('layouts.app')

@section('title', 'Add Role')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <div class="card border-0 shadow-sm mt-3 animate-fade-in">
            <div class="card-header bg-white py-3 border-bottom-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="text-white p-2 rounded-3 shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: var(--hse-red-gradient);">
                        <i class="bi bi-shield-lock fs-5"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Add New Role</h5>
                        <p class="text-muted small mb-0">Create a new organizational role</p>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4 pt-2">
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label fw-bold">Role Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-lg fs-6 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. manager, staff" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Permissions <span class="text-danger">*</span></label>
                            @if(count($permissions) > 0)
                                <div class="d-flex flex-wrap gap-3 mt-2">
                                    @foreach($permissions as $permission)
                                        <div class="form-check border p-3 rounded" style="flex: 1; min-width: 200px; border-color: var(--border-color) !important;">
                                            <input class="form-check-input" type="checkbox" name="permission[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}">
                                            <label class="form-check-label fw-medium ms-2" for="perm_{{ $permission->id }}">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-light border mt-2">
                                    <i class="bi bi-info-circle me-2 text-primary"></i> No permissions are available in the system. Just assigning by name.
                                </div>
                            @endif
                            @error('permission') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <hr class="my-4 border-light">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-light fw-bold px-4">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                        <button type="submit" class="btn btn-hse-red fw-bold px-5">
                            <i class="bi bi-check2-circle me-1"></i> Save Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
