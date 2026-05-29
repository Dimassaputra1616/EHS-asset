@extends('layouts.app')

@section('title', 'Config Master')

@push('css')
<style>
    /* Styling Tabs Sidebar */
    .config-sidebar {
        background-color: #f8fafc !important;
        border-right: 1px solid #e2e8f0 !important;
    }
    .config-nav-link {
        border-radius: 12px !important;
        padding: 0.85rem 1.25rem !important;
        font-weight: 600 !important;
        color: #475569 !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        border: 1px solid transparent !important;
        margin-bottom: 0.5rem;
        background: transparent;
        display: flex;
        align-items: center;
        width: 100%;
    }
    .config-nav-link:hover {
        background: #f1f5f9 !important;
        color: #1e293b !important;
    }
    .config-nav-link.active {
        background: linear-gradient(135deg, var(--hse-red, #C0392B) 0%, #E74C3C 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(192, 57, 43, 0.2) !important;
    }
    
    /* Config Panel Container */
    .config-panel-card {
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.02) !important;
        border-radius: 20px !important;
        overflow: hidden;
    }

    /* Card Form Group */
    .config-form-card {
        border: 1.5px solid #f1f5f9 !important;
        background: #ffffff !important;
        padding: 1.5rem !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01), 0 2px 4px -1px rgba(0, 0, 0, 0.005) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .config-form-card:hover {
        transform: translateY(-2px);
        border-color: rgba(192, 57, 43, 0.18) !important;
        box-shadow: 0 12px 24px -10px rgba(192, 57, 43, 0.08) !important;
    }

    .config-form-card label {
        font-size: 0.95rem;
        letter-spacing: 0.3px;
        color: #1e293b;
    }

    /* Code Badge Dynamic */
    .config-code-badge {
        font-family: 'Fira Code', monospace;
        font-size: 0.72rem !important;
        font-weight: 600;
        background: #f1f5f9 !important;
        color: #64748b !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;
        padding: 4px 10px !important;
        transition: all 0.2s ease;
    }
    .config-form-card:hover .config-code-badge {
        background: rgba(192, 57, 43, 0.06) !important;
        color: var(--hse-red, #C0392B) !important;
        border-color: rgba(192, 57, 43, 0.12) !important;
    }

    /* Input controls dynamic styling */
    .config-form-card .form-control, .config-form-card .form-select {
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 10px !important;
        background: #f8fafc !important;
        padding: 0.65rem 1rem !important;
        font-weight: 500;
        color: #1e293b !important;
        transition: all 0.2s ease-in-out !important;
    }
    .config-form-card .form-control:focus, .config-form-card .form-select:focus {
        border-color: var(--hse-red, #C0392B) !important;
        box-shadow: 0 0 0 4px rgba(192, 57, 43, 0.1) !important;
        background: #ffffff !important;
    }

    /* Premium Logo uploader box */
    .logo-uploader-frame {
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        padding: 1.5rem;
        background: #f8fafc;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    .logo-uploader-frame:hover {
        border-color: var(--hse-red, #C0392B);
        background: rgba(192, 57, 43, 0.02);
    }
    
    .form-control-color {
        width: 65px !important;
        height: 42px !important;
        padding: 4px !important;
        cursor: pointer;
    }


    /* Style choice cards for login page */
    .login-style-card {
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: #ffffff;
        position: relative;
    }
    .login-style-card:hover {
        transform: translateY(-4px);
        border-color: #cbd5e1;
        box-shadow: 0 10px 20px rgba(0,0,0,0.04);
    }
    .login-style-card.active {
        border-color: var(--hse-red, #C0392B);
        box-shadow: 0 12px 25px rgba(192, 57, 43, 0.12);
    }
    .style-selected-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        background: var(--hse-red, #C0392B);
        color: white;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        box-shadow: 0 2px 8px rgba(192, 57, 43, 0.3);
        opacity: 0;
        transform: scale(0.6);
        transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        z-index: 10;
    }
    .login-style-card.active .style-selected-badge {
        opacity: 1;
        transform: scale(1);
    }
    .mockup-preview-screen {
        height: 100px;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem;
        overflow: hidden;
        border-radius: 14px 14px 0 0;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm mb-4 config-panel-card">
            <div class="card-header bg-white py-4 border-bottom-0 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-hse-red text-white p-2 rounded-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: var(--hse-red-gradient);">
                        <i class="bi bi-sliders fs-4"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fw-bold" style="font-size: 1.2rem; color: #1e293b;">Application Settings</h5>
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
                        <div class="col-md-3 config-sidebar">
                            <div class="p-4">
                                <div class="nav flex-row flex-md-column nav-pills flex-nowrap overflow-auto pb-2 pb-md-0" id="v-pills-tab" role="tablist" aria-orientation="vertical" style="white-space: nowrap;">
                                    @foreach($configs as $group => $items)
                                        <button class="config-nav-link {{ $loop->first ? 'active' : '' }} text-capitalize mb-2" 
                                                id="v-pills-{{ Str::slug($group) }}-tab" 
                                                data-bs-toggle="pill" 
                                                data-bs-target="#v-pills-{{ Str::slug($group) }}" 
                                                type="button" role="tab" 
                                                aria-controls="v-pills-{{ Str::slug($group) }}" 
                                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                            @if($group == 'general') 
                                                <i class="bi bi-gear-wide-connected me-2.5 fs-5"></i>
                                            @elseif($group == 'appearance') 
                                                <i class="bi bi-palette me-2.5 fs-5"></i>
                                            @else 
                                                <i class="bi bi-circle me-2.5 fs-5"></i> 
                                            @endif
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
                                        <h5 class="text-uppercase fw-bold text-dark mb-4 pb-2 border-bottom d-flex align-items-center" style="font-size: 1rem; letter-spacing: 0.5px;">
                                            <span style="border-bottom: 2px solid var(--hse-red); padding-bottom: 8px;">{{ $group }} Settings</span>
                                        </h5>
                                        
                                        <div class="row g-4">
                                            @foreach($items as $config)
                                            @php
                                                $colClass = 'col-12';
                                                if (in_array($config->key, ['app_name', 'company_name', 'asset_code_prefix', 'consumable_code_prefix', 'currency_symbol', 'low_stock_threshold'])) {
                                                    $colClass = 'col-md-6';
                                                }
                                            @endphp
                                            <div class="{{ $colClass }}">
                                                <div class="form-group config-form-card h-100 d-flex flex-column justify-content-between">
                                                    <div>
                                                        <div class="d-flex justify-content-between align-items-center mb-2.5">
                                                            <label for="{{ $config->key }}" class="form-label fw-bold text-dark mb-0">{{ $config->label }}</label>
                                                            <div class="text-muted fw-mono small config-code-badge">
                                                                <i class="bi bi-braces text-secondary me-1"></i> config('{{ $config->key }}')
                                                            </div>
                                                        </div>
                                                        
                                                        @if($config->key == 'primary_color' || Str::contains($config->key, 'color'))
                                                            <div class="d-flex align-items-center gap-3 mt-2">
                                                                <div class="input-group" style="max-width: 250px;">
                                                                    <span class="input-group-text p-1 bg-white" style="border-top-left-radius: 10px; border-bottom-left-radius: 10px; border-right: 0;">
                                                                        <input type="color" id="picker-{{ $config->key }}" class="border-0" value="{{ $config->value }}" style="width: 44px; height: 32px; padding: 0; cursor: pointer; border-radius: 6px;" oninput="syncColorPicker('{{ $config->key }}', this.value)">
                                                                    </span>
                                                                    <input type="text" name="{{ $config->key }}" id="input-{{ $config->key }}" class="form-control font-monospace text-uppercase" value="{{ $config->value }}" style="height: 44px; border-top-right-radius: 10px; border-bottom-right-radius: 10px;" placeholder="#C0392B" maxlength="7" oninput="syncColorInput('{{ $config->key }}', this.value)">
                                                                </div>
                                                                <div class="text-muted small fw-semibold">Pilih warna utama atau masukkan kode HEX untuk mengubah tema visual aplikasi secara instan.</div>
                                                            </div>
                                                        @elseif($config->key == 'app_logo' || Str::contains($config->key, 'logo'))
                                                            <div class="mt-2 d-flex flex-column gap-3">
                                                                <!-- Modern Image Preview Frame -->
                                                                <div class="d-flex align-items-center gap-3 logo-uploader-frame">
                                                                    <div class="border rounded-3 p-2 bg-white d-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px; overflow: hidden; flex-shrink: 0;">
                                                                        @if($config->value)
                                                                            <img id="logo-preview-img" src="{{ Storage::url($config->value) }}" alt="App Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                                        @else
                                                                            <div class="text-center text-muted d-flex flex-column align-items-center justify-content-center w-100 h-100" id="logo-placeholder">
                                                                                <i class="bi bi-shield-check text-hse-red fs-2"></i>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <div class="small fw-bold text-dark">Current Brand Logo</div>
                                                                        <div class="small text-muted mb-2">A beautiful preview of the system logo.</div>
                                                                        <input type="file" name="{{ $config->key }}" id="{{ $config->key }}" class="form-control form-control-lg fs-6" accept="image/*" onchange="previewAppLogo(this)" style="max-width: 320px;">
                                                                    </div>
                                                                </div>
                                                                <div class="small text-muted">Upload a crisp branding asset (PNG, JPG, SVG, or WEBP, max 2MB).</div>
                                                            </div>
                                                        @elseif($config->key == 'login_bg')
                                                            <div class="mt-2 d-flex flex-column gap-3">
                                                                <!-- Modern Landscape Image Preview Frame -->
                                                                <div class="d-flex align-items-center gap-3 logo-uploader-frame">
                                                                    <div class="border rounded-3 p-1 bg-white d-flex align-items-center justify-content-center shadow-sm" style="width: 140px; height: 80px; overflow: hidden; flex-shrink: 0;">
                                                                        @php
                                                                            $bgValue = $config->value ?: 'images/auth/welcome-bg.png';
                                                                            $bgUrl = str_contains($bgValue, 'images/auth') ? asset($bgValue) : asset('storage/' . $bgValue);
                                                                        @endphp
                                                                        <img id="login-bg-preview-img" src="{{ $bgUrl }}" alt="Login Background" style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;">
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <div class="small fw-bold text-dark">Current Login Background</div>
                                                                        <div class="small text-muted mb-2">A landscape image preview for the left side branding panel on login screen.</div>
                                                                        <input type="file" name="{{ $config->key }}" id="{{ $config->key }}" class="form-control form-control-lg fs-6" accept="image/*" onchange="previewLoginBg(this)" style="max-width: 320px;">
                                                                    </div>
                                                                </div>
                                                                <div class="small text-muted">Upload a premium landscape banner (PNG, JPG, or WEBP, max 3MB). Recommended size: 1920x1080.</div>
                                                            </div>
                                                        @elseif($config->key == 'login_style')
                                                            <div class="row g-3 mt-2">
                                                                <!-- Style 1: Split -->
                                                                <div class="col-md-6">
                                                                    <div class="login-style-card h-100 p-3 {{ $config->value == 'split' ? 'active' : '' }}" onclick="selectLoginStyle('split', this)">
                                                                        <div class="style-selected-badge"><i class="bi bi-check-lg"></i></div>
                                                                        <div class="mockup-preview-screen">
                                                                            <div style="display:flex; width:100%; height:100%; border-radius:6px; overflow:hidden;">
                                                                                <div style="width:45%; background:linear-gradient(135deg, #1c1515, #291818); display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px;">
                                                                                    <div style="width:6px; height:6px; border-radius:50%; background:#fff;"></div>
                                                                                    <div style="width:60%; height:2px; background:#fff; opacity:0.3; border-radius:2px;"></div>
                                                                                    <div style="width:45%; height:2px; background:#fff; opacity:0.3; border-radius:2px;"></div>
                                                                                </div>
                                                                                <div style="width:55%; background:#fff; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:5px;">
                                                                                    <div style="width:6px; height:6px; border-radius:50%; background:var(--hse-red);"></div>
                                                                                    <div style="width:70%; height:4px; background:#e2e8f0; border-radius:2px;"></div>
                                                                                    <div style="width:70%; height:4px; background:#e2e8f0; border-radius:2px;"></div>
                                                                                    <div style="width:70%; height:5px; background:var(--hse-red); border-radius:2px;"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="pt-3 text-center">
                                                                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.85rem;">Split Screen</h6>
                                                                            <p class="text-muted mb-0" style="font-size: 0.72rem;">Sisi kiri gambar branding, sisi kanan form login (Default).</p>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Style 2: Centered Card -->
                                                                <div class="col-md-6">
                                                                    <div class="login-style-card h-100 p-3 {{ $config->value == 'centered-card' ? 'active' : '' }}" onclick="selectLoginStyle('centered-card', this)">
                                                                        <div class="style-selected-badge"><i class="bi bi-check-lg"></i></div>
                                                                        <div class="mockup-preview-screen">
                                                                            <div style="width:100%; height:100%; background:linear-gradient(135deg, #2d1b69, #1a1a3e); border-radius:6px; display:flex; align-items:center; justify-content:center; position:relative;">
                                                                                <!-- Floating mini icons with tiny colored icons -->
                                                                                <div style="position:absolute; top:10%; left:8%; width:16px; height:16px; background:rgba(255,255,255,0.12); border-radius:4px; border:1px solid rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 10px rgba(0,0,0,0.15);"><i class="bi bi-hdd-rack" style="font-size: 8px; background: linear-gradient(135deg, #00f2fe, #4facfe); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i></div>
                                                                                <div style="position:absolute; top:28%; left:4%; width:16px; height:16px; background:rgba(255,255,255,0.12); border-radius:4px; border:1px solid rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 10px rgba(0,0,0,0.15);"><i class="bi bi-key" style="font-size: 8px; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i></div>
                                                                                <div style="position:absolute; top:48%; left:9%; width:16px; height:16px; background:rgba(255,255,255,0.12); border-radius:4px; border:1px solid rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 10px rgba(0,0,0,0.15);"><i class="bi bi-tools" style="font-size: 8px; background: linear-gradient(135deg, #11998e, #38ef7d); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i></div>
                                                                                <div style="position:absolute; top:66%; left:5%; width:16px; height:16px; background:rgba(255,255,255,0.12); border-radius:4px; border:1px solid rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 10px rgba(0,0,0,0.15);"><i class="bi bi-database" style="font-size: 8px; background: linear-gradient(135deg, #f093fb, #f5576c); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i></div>
                                                                                <div style="position:absolute; top:84%; left:11%; width:16px; height:16px; background:rgba(255,255,255,0.12); border-radius:4px; border:1px solid rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 10px rgba(0,0,0,0.15);"><i class="bi bi-laptop" style="font-size: 8px; background: linear-gradient(135deg, #e0c3fc, #8ec5fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i></div>
                                                                                
                                                                                <div style="position:absolute; top:12%; right:8%; width:16px; height:16px; background:rgba(255,255,255,0.12); border-radius:4px; border:1px solid rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 10px rgba(0,0,0,0.15);"><i class="bi bi-qr-code-scan" style="font-size: 8px; background: linear-gradient(135deg, #cfd9df, #e2ebf0); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i></div>
                                                                                <div style="position:absolute; top:30%; right:4%; width:16px; height:16px; background:rgba(255,255,255,0.12); border-radius:4px; border:1px solid rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 10px rgba(0,0,0,0.15);"><i class="bi bi-geo-alt" style="font-size: 8px; background: linear-gradient(135deg, #f6d365, #fda085); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i></div>
                                                                                <div style="position:absolute; top:50%; right:10%; width:16px; height:16px; background:rgba(255,255,255,0.12); border-radius:4px; border:1px solid rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 10px rgba(0,0,0,0.15);"><i class="bi bi-cpu" style="font-size: 8px; background: linear-gradient(135deg, #ff0844, #ffb199); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i></div>
                                                                                <div style="position:absolute; top:68%; right:6%; width:16px; height:16px; background:rgba(255,255,255,0.12); border-radius:4px; border:1px solid rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 10px rgba(0,0,0,0.15);"><i class="bi bi-arrow-repeat" style="font-size: 8px; background: linear-gradient(135deg, #8fd3f4, #84fab0); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i></div>
                                                                                <div style="position:absolute; top:86%; right:11%; width:16px; height:16px; background:rgba(255,255,255,0.12); border-radius:4px; border:1px solid rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 10px rgba(0,0,0,0.15);"><i class="bi bi-shield-check" style="font-size: 8px; background: linear-gradient(135deg, #2193b0, #6dd5ed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i></div>
                                                                                <!-- Center glass card -->
                                                                                <div style="width:55%; height:75%; background:rgba(255,255,255,0.1); backdrop-filter:blur(4px); border:1px solid rgba(255,255,255,0.18); border-radius:8px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; padding:4px;">
                                                                                    <div style="width:5px; height:5px; border-radius:50%; background:#a78bfa;"></div>
                                                                                    <div style="width:65%; height:3px; background:rgba(255,255,255,0.2); border-radius:2px;"></div>
                                                                                    <div style="width:65%; height:3px; background:rgba(255,255,255,0.2); border-radius:2px;"></div>
                                                                                    <div style="width:65%; height:4px; background:linear-gradient(90deg, #6366f1, #a855f7); border-radius:2px;"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="pt-3 text-center">
                                                                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.85rem;">Centered Card</h6>
                                                                            <p class="text-muted mb-0" style="font-size: 0.72rem;">Glassmorphism card di tengah dengan background blur dan floating icons.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="login_style" id="login_style_input" value="{{ $config->value }}">
                                                        @elseif($config->key == 'sidebar_theme')
                                                             <select name="{{ $config->key }}" id="{{ $config->key }}" class="form-select form-select-lg mt-1 fs-6" style="max-width: 280px;">
                                                                 <option value="Dark" {{ $config->value == 'Dark' ? 'selected' : '' }}>Midnight Dark 🖤</option>
                                                                 <option value="Light" {{ $config->value == 'Light' ? 'selected' : '' }}>Clean Light 🤍</option>
                                                                 <option value="Gradient" {{ $config->value == 'Gradient' ? 'selected' : '' }}>HSE Crimson Gradient ❤️</option>
                                                                 <option value="Forest" {{ $config->value == 'Forest' ? 'selected' : '' }}>Forest Emerald 💚</option>
                                                                 <option value="Ocean" {{ $config->value == 'Ocean' ? 'selected' : '' }}>Deep Ocean Blue 💙</option>
                                                                 <option value="Orange" {{ $config->value == 'Orange' ? 'selected' : '' }}>Sunset Orange 🧡</option>
                                                                 <option value="Purple" {{ $config->value == 'Purple' ? 'selected' : '' }}>Royal Purple 💜</option>
                                                                 <option value="Charcoal" {{ $config->value == 'Charcoal' ? 'selected' : '' }}>Midnight Charcoal 🩶</option>
                                                                 <option value="Neon" {{ $config->value == 'Neon' ? 'selected' : '' }}>Cyberpunk Neon 🩷</option>
                                                                 <option value="Gold" {{ $config->value == 'Gold' ? 'selected' : '' }}>Gold Premium 💛</option>
                                                                 <option value="Sakura" {{ $config->value == 'Sakura' ? 'selected' : '' }}>Sakura Rose 🌸</option>
                                                             </select>
                                                             <div class="small text-muted mt-1.5">Pilih tema warna sidebar navigasi utama untuk menyesuaikan nuansa dasbor.</div>
                                                        @elseif($config->key == 'glassmorphism_effects' || $config->key == 'show_sidebar_logo')
                                                             <input type="hidden" name="{{ $config->key }}" value="0">
                                                             <div class="form-check form-switch mt-2">
                                                                 <input type="checkbox" name="{{ $config->key }}" id="{{ $config->key }}" class="form-check-input" value="1" {{ $config->value == '1' ? 'checked' : '' }} style="width: 50px; height: 26px; cursor: pointer;">
                                                                 <label class="form-check-label ms-2 small fw-semibold text-muted" for="{{ $config->key }}">
                                                                     {{ $config->key == 'glassmorphism_effects' ? 'Aktifkan efek blur kaca buram (glassmorphism) pada notifikasi global.' : 'Tampilkan logo instansi di bagian kepala sidebar navigasi.' }}
                                                                 </label>
                                                             </div>
                                                        @elseif($config->key == 'app_description' || Str::contains($config->key, 'text'))
                                                            <textarea name="{{ $config->key }}" id="{{ $config->key }}" class="form-control form-control-lg mt-1 fs-6" rows="3">{{ $config->value }}</textarea>
                                                            @if($config->key == 'copyright_text')
                                                                <div class="small text-muted mt-1.5">Teks hak cipta / footer yang muncul pada halaman login / splash screen.</div>
                                                            @endif
                                                        @elseif($config->key == 'low_stock_threshold')
                                                             <input type="number" name="{{ $config->key }}" id="{{ $config->key }}" class="form-control form-control-lg mt-1 fs-6" value="{{ $config->value }}" min="1" max="1000" style="max-width: 150px;">
                                                             <div class="small text-muted mt-1.5">Batas minimum stok barang habis pakai (consumables) sebelum peringatan "LOW STOCK" diaktifkan secara otomatis.</div>
                                                        @elseif($config->key == 'currency_symbol')
                                                             <input type="text" name="{{ $config->key }}" id="{{ $config->key }}" class="form-control form-control-lg mt-1 fs-6" value="{{ $config->value }}" placeholder="Rp" style="max-width: 120px;">
                                                             <div class="small text-muted mt-1.5">Simbol mata uang yang akan digunakan pada seluruh rincian biaya pengadaan aset.</div>
                                                        @elseif($config->key == 'asset_code_prefix' || $config->key == 'consumable_code_prefix')
                                                             <input type="text" name="{{ $config->key }}" id="{{ $config->key }}" class="form-control form-control-lg mt-1 fs-6 font-monospace" value="{{ $config->value }}" placeholder="AST" style="max-width: 180px; text-transform: uppercase;">
                                                             <div class="small text-muted mt-1.5">Prefiks awalan untuk penomoran kode otomatis (contoh: {{ $config->key == 'asset_code_prefix' ? 'AST' : 'CSM' }}-KATEGORI-001).</div>
                                                        @else
                                                            <input type="text" name="{{ $config->key }}" id="{{ $config->key }}" class="form-control form-control-lg mt-1 fs-6" value="{{ $config->value }}">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <div class="mt-5 pt-4 border-top d-flex justify-content-end">
                                    <button type="submit" class="btn btn-hse-red px-5 py-2.5 fw-bold shadow-sm" style="border-radius: 12px; font-size: 0.95rem; letter-spacing: 0.5px;">
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
    document.addEventListener('DOMContentLoaded', function() {
        // Theme live preview listener
        const themeSelect = document.getElementById('sidebar_theme');
        if (themeSelect) {
            themeSelect.addEventListener('change', function() {
                const selectedTheme = this.value;
                const sidebar = document.getElementById('sidebar-wrapper');
                if (sidebar) {
                    // Remove all existing theme classes
                    sidebar.className = sidebar.className.split(' ').filter(c => !c.startsWith('sidebar-theme-')).join(' ');
                    // Add the newly selected theme class
                    sidebar.classList.add('sidebar-theme-' + selectedTheme);
                }
            });
        }
        
        // Logo visibility live preview listener
        const logoToggle = document.getElementById('show_sidebar_logo');
        if (logoToggle) {
            logoToggle.addEventListener('change', function() {
                const sidebarLogo = document.querySelector('#sidebar-wrapper .sidebar-logo');
                if (sidebarLogo) {
                    if (this.checked) {
                        sidebarLogo.style.setProperty('display', 'flex', 'important');
                    } else {
                        sidebarLogo.style.setProperty('display', 'none', 'important');
                    }
                }
            });
        }
    });

    function selectLoginStyle(style, cardElement) {
        const input = document.getElementById('login_style_input');
        if (input) input.value = style;
        document.querySelectorAll('.login-style-card').forEach(c => c.classList.remove('active'));
        if (cardElement) cardElement.classList.add('active');
    }


    function updatePrimaryColorPreview(val) {
        if (val.length === 7 && val.startsWith('#')) {
            document.documentElement.style.setProperty('--hse-red', val, 'important');
            document.documentElement.style.setProperty('--hse-red-light', `color-mix(in srgb, ${val} 80%, white)`, 'important');
            document.documentElement.style.setProperty('--hse-red-dark', `color-mix(in srgb, ${val} 80%, black)`, 'important');
            document.documentElement.style.setProperty('--hse-red-gradient', `linear-gradient(135deg, ${val} 0%, color-mix(in srgb, ${val} 80%, white) 100%)`, 'important');
            document.documentElement.style.setProperty('--hse-red-glow', `0 10px 25px color-mix(in srgb, ${val} 25%, transparent)`, 'important');
        }
    }

    function syncColorPicker(key, val) {
        document.getElementById('input-' + key).value = val.toUpperCase();
        if (key === 'primary_color') {
            updatePrimaryColorPreview(val);
        }
    }
    
    function syncColorInput(key, val) {
        if (val.length === 7 && val.startsWith('#')) {
            document.getElementById('picker-' + key).value = val;
            if (key === 'primary_color') {
                updatePrimaryColorPreview(val);
            }
        }
    }

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

    function previewLoginBg(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const imgEl = document.getElementById('login-bg-preview-img');
                if (imgEl) {
                    imgEl.src = e.target.result;
                }
            };
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
