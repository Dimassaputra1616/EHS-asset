@extends('layouts.app')

@section('title', 'Config Master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-hse-red text-white p-2 rounded-3 shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-sliders fs-5"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Application Settings</h5>
                        <p class="text-muted small mb-0">Configure global parameters and branding for the HSE system</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <form action="{{ route('admin.configs.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-0">
                        <!-- Navigation Tabs side -->
                        <div class="col-md-3 bg-light border-end">
                            <div class="p-3">
                                <div class="nav flex-row flex-md-column nav-pills flex-nowrap overflow-auto pb-2 pb-md-0" id="v-pills-tab" role="tablist" aria-orientation="vertical" style="white-space: nowrap;">
                                    @foreach($configs as $group => $items)
                                        <button class="nav-link {{ $loop->first ? 'active' : '' }} text-start text-capitalize mb-2" 
                                                id="v-pills-{{ Str::slug($group) }}-tab" 
                                                data-bs-toggle="pill" 
                                                data-bs-target="#v-pills-{{ Str::slug($group) }}" 
                                                type="button" role="tab" 
                                                aria-controls="v-pills-{{ Str::slug($group) }}" 
                                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                            @if($group == 'general') <i class="bi bi-gear-wide-connected me-2"></i>
                                            @elseif($group == 'branding') <i class="bi bi-palette me-2"></i>
                                            @else <i class="bi bi-circle me-2"></i> @endif
                                            {{ $group }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <!-- Content side -->
                        <div class="col-md-9">
                            <div class="p-4 p-lg-5">
                                <div class="tab-content" id="v-pills-tabContent">
                                    @foreach($configs as $group => $items)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="v-pills-{{ Str::slug($group) }}" role="tabpanel" aria-labelledby="v-pills-{{ Str::slug($group) }}-tab">
                                        <h5 class="text-uppercase fw-bold text-dark mb-4 pb-2 border-bottom">{{ $group }} Settings</h5>
                                        
                                        <div class="row g-4">
                                            @foreach($items as $config)
                                            <div class="col-12">
                                                <div class="form-group bg-white p-3 rounded-3 border">
                                                    <label for="{{ $config->key }}" class="form-label fw-bold text-dark">{{ $config->label }}</label>
                                                                                                        @if($config->key == 'primary_color' || Str::contains($config->key, 'color'))
                                                        <div class="d-flex align-items-center gap-3 mt-1">
                                                            <input type="color" name="{{ $config->key }}" id="{{ $config->key }}" class="form-control form-control-color" value="{{ $config->value }}" title="Choose your color" style="width: 60px; height: 40px;">
                                                            <div class="text-muted small">Select the primary brand color for the application.</div>
                                                        </div>
                                                    @elseif($config->key == 'app_logo' || Str::contains($config->key, 'logo'))
                                                        <div class="mt-2 d-flex flex-column gap-3">
                                                            <!-- Modern Image Preview Frame -->
                                                            <div class="d-flex align-items-center gap-3">
                                                                <div class="border rounded-3 p-2 bg-light d-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px; overflow: hidden; background-color: #f8f9fa;">
                                                                    @if($config->value)
                                                                        <img id="logo-preview-img" src="{{ Storage::url($config->value) }}" alt="App Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                                    @else
                                                                        <div class="text-center text-muted d-flex flex-column align-items-center justify-content-center w-100 h-100" id="logo-placeholder">
                                                                            <i class="bi bi-shield-check text-hse-red fs-2"></i>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div>
                                                                    <div class="small fw-bold text-dark">Current Brand Logo</div>
                                                                    <div class="small text-muted">A beautiful preview of the system logo.</div>
                                                                </div>
                                                            </div>
                                                            <!-- Sleek File Input -->
                                                            <input type="file" name="{{ $config->key }}" id="{{ $config->key }}" class="form-control form-control-lg mt-1 fs-6" accept="image/*" onchange="previewAppLogo(this)">
                                                            <div class="small text-muted">Upload a crisp branding asset (PNG, JPG, SVG, or WEBP, max 2MB).</div>
                                                        </div>
                                                    @elseif($config->key == 'app_description' || Str::contains($config->key, 'text'))
                                                        <textarea name="{{ $config->key }}" id="{{ $config->key }}" class="form-control form-control-lg mt-1 fs-6" rows="3">{{ $config->value }}</textarea>
                                                    @else
                                                        <input type="text" name="{{ $config->key }}" id="{{ $config->key }}" class="form-control form-control-lg mt-1 fs-6" value="{{ $config->value }}">
                                                    @endif
                                                    
                                                    <div class="mt-2 text-muted fw-mono small bg-light p-1 rounded d-inline-block px-2">
                                                        <i class="bi bi-braces text-secondary me-1"></i> config('{{ $config->key }}')
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <div class="mt-5 pt-3 border-top d-flex justify-content-end">
                                    <button type="submit" class="btn btn-hse-red px-5 py-2 fw-bold shadow-sm">
                                        <i class="bi bi-save me-2"></i> Save All Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewAppLogo(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                let imgEl = document.getElementById('logo-preview-img');
                const placeholderEl = document.getElementById('logo-placeholder');
                
                if (!imgEl) {
                    // Create image preview element dynamically if it doesn't exist
                    const container = placeholderEl.parentElement;
                    placeholderEl.remove();
                    
                    imgEl = document.createElement('img');
                    imgEl.id = 'logo-preview-img';
                    imgEl.alt = 'App Logo';
                    imgEl.style.maxWidth = '100%';
                    imgEl.style.maxHeight = '100%';
                    imgEl.style.objectFit = 'contain';
                    container.appendChild(imgEl);
                }
                
                imgEl.src = e.target.result;
            };
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
