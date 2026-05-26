@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="row">
    <!-- Left Column: Profile Card -->
    <div class="col-xl-4 col-lg-5 mb-4 mb-lg-0">
        <div class="card profile-card border-0 shadow-sm h-100">
            <div class="profile-header">
                @if(Auth::user()->photo)
                    <img src="{{ Storage::url(Auth::user()->photo) }}" alt="{{ Auth::user()->name }}" class="profile-avatar mb-3" style="object-fit: cover;">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=C0392B&color=fff&size=150" alt="{{ Auth::user()->name }}" class="profile-avatar mb-3">
                @endif
                <h4 class="fw-bold mb-1">{{ Auth::user()->name }}</h4>
                <p class="mb-2 opacity-75">{{ Auth::user()->email }}</p>
                <span class="badge bg-white text-dark px-3 py-2 rounded-pill shadow-sm">
                    {{ Auth::user()->roles->pluck('name')->first() ?? 'Staff Member' }}
                </span>
            </div>
            <div class="card-body p-4">
                <div class="mb-4">
                    <h6 class="fw-bold text-uppercase text-muted opacity-75 mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Account Details</h6>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-light p-2 rounded text-muted me-3"><i class="bi bi-envelope"></i></div>
                        <div>
                            <div class="small text-muted mb-0">Email Address</div>
                            <div class="fw-bold">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-light p-2 rounded text-muted me-3"><i class="bi bi-shield-check"></i></div>
                        <div>
                            <div class="small text-muted mb-0">Account Role</div>
                            <div class="fw-bold text-capitalize">{{ Auth::user()->roles->pluck('name')->first() ?? 'Staff' }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="bg-light p-2 rounded text-muted me-3"><i class="bi bi-clock-history"></i></div>
                        <div>
                            <div class="small text-muted mb-0">Member Since</div>
                            <div class="fw-bold">{{ Auth::user()->created_at->format('M Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Settings -->
    <div class="col-xl-8 col-lg-7">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-person-lines-fill me-2 text-hse-red"></i> Profile Information</h5>
            </div>
            <div class="card-body p-4 pt-2">
                <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                    @csrf
                </form>

                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('patch')

                    <div class="row g-4">
                        <div class="col-12">
                            <label for="photo" class="form-label fw-bold">Profile Photo</label>
                            <input type="file" name="photo" id="photo" class="form-control form-control-lg fs-6 @error('photo') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg">
                            <div class="form-text">Recommended size: 300x300 pixels. Max file size: 2MB.</div>
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="name" class="form-label fw-bold">Full Name</label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg fs-6 @error('name') is-invalid @enderror" value="{{ old('name', Auth::user()->name) }}" required autocomplete="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg fs-6 @error('email') is-invalid @enderror" value="{{ old('email', Auth::user()->email) }}" required autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            @if (Auth::user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! Auth::user()->hasVerifiedEmail())
                                <div class="mt-2 text-warning small">
                                    <i class="bi bi-exclamation-triangle"></i> Your email address is unverified.
                                    <button form="send-verification" class="btn btn-link p-0 m-0 align-baseline text-decoration-none">Click here to re-send the verification email.</button>
                                </div>
                                @if (session('status') === 'verification-link-sent')
                                    <div class="mt-2 text-success small">
                                        <i class="bi bi-check-circle"></i> A new verification link has been sent to your email address.
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <button type="submit" class="btn btn-hse-red px-4 fw-bold">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-shield-lock me-2 text-hse-red"></i> Update Password</h5>
            </div>
            <div class="card-body p-4 pt-2">
                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="row g-4">
                        <div class="col-12">
                            <label for="update_password_current_password" class="form-label fw-bold">Current Password</label>
                            <input type="password" name="current_password" id="update_password_current_password" class="form-control form-control-lg fs-6 @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password">
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="update_password_password" class="form-label fw-bold">New Password</label>
                            <input type="password" name="password" id="update_password_password" class="form-control form-control-lg fs-6 @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="update_password_password_confirmation" class="form-label fw-bold">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="update_password_password_confirmation" class="form-control form-control-lg fs-6 @error('password_confirmation', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                            @error('password_confirmation', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <button type="submit" class="btn btn-hse-red px-4 fw-bold">Update Password</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm border-danger">
            <div class="card-header bg-danger bg-opacity-10 py-3 border-bottom-0">
                <h5 class="card-title mb-0 fw-bold text-danger"><i class="bi bi-exclamation-octagon me-2"></i> Danger Zone</h5>
            </div>
            <div class="card-body p-4">
                <p class="text-muted mb-4">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
                
                <button type="button" class="btn btn-outline-danger fw-bold" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
                    Delete Account
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title fw-bold" id="confirmUserDeletionModalLabel">Delete Account</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="mb-4">Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Password</label>
                        <input type="password" name="password" id="password" class="form-control form-control-lg fs-6 @error('password', 'userDeletion') is-invalid @enderror" placeholder="Enter your password">
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger fw-bold px-4">Delete Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
