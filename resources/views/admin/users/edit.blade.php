@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <div class="card border-0 shadow-sm mt-3 animate-fade-in">
            <div class="card-header bg-white py-3 border-bottom-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3 shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Edit User</h5>
                        <p class="text-muted small mb-0">Update account for {{ $user->name }}</p>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4 pt-2">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-lg fs-6 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control form-control-lg fs-6 @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 mt-4">
                            <div class="alert alert-light border d-flex align-items-center">
                                <i class="bi bi-info-circle me-3 text-primary fs-4"></i>
                                <div>Leave the password fields blank if you do not want to change the user's password.</div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <label class="form-label fw-bold">New Password</label>
                            <input type="password" name="password" class="form-control form-control-lg fs-6 @error('password') is-invalid @enderror">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mt-2">
                            <label class="form-label fw-bold">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control form-control-lg fs-6">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Assign Role <span class="text-danger">*</span></label>
                            <div class="d-flex flex-wrap gap-3 mt-2">
                                @foreach($roles as $role)
                                    <div class="form-check border p-3 rounded" style="flex: 1; min-width: 200px; border-color: var(--border-color) !important;">
                                        <input class="form-check-input" type="radio" name="roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}" 
                                            {{ in_array($role->name, $userRole) ? 'checked' : '' }} required>
                                        <label class="form-check-label fw-medium ms-2" for="role_{{ $role->id }}">
                                            {{ ucfirst($role->name) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('roles') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <hr class="my-4 border-light">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light fw-bold px-4">
                            <i class="bi bi-arrow-left me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary fw-bold px-5">
                            <i class="bi bi-check2-circle me-1"></i> Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
