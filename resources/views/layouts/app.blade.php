<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HSE Asset Management') }} - @yield('title', 'Dashboard')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

        <!-- PWA Meta Tags & Manifest -->
        <meta name="theme-color" content="#C0392B">
        <link rel="apple-touch-icon" href="{{ asset('icon-192x192.png') }}">
        <link rel="manifest" href="{{ asset('manifest.json') }}">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            :root {
                --hse-red: {{ config('app.primary_color', '#C0392B') }} !important;
                --hse-red-light: color-mix(in srgb, var(--hse-red) 80%, white) !important;
                --hse-red-dark: color-mix(in srgb, var(--hse-red) 80%, black) !important;
                --hse-red-gradient: linear-gradient(135deg, var(--hse-red) 0%, var(--hse-red-light) 100%) !important;
                --hse-red-glow: 0 10px 25px color-mix(in srgb, var(--hse-red) 25%, transparent) !important;
            }
            @yield('styles')
        </style>
        @stack('css')

        @if(config('app.glassmorphism_effects', '1') == '0')
        <style>
            .alert-modern {
                backdrop-filter: none !important;
                -webkit-backdrop-filter: none !important;
            }
            .alert-modern.alert-success {
                background: #f0fdf4 !important;
            }
            .alert-modern.alert-danger {
                background: #fef2f2 !important;
            }
        </style>
        @endif
    </head>
    <body>
        <div id="wrapper">
            <!-- Sidebar -->
            @include('layouts.partials.sidebar')
            
            <!-- Mobile Sidebar Overlay -->
            <div class="sidebar-overlay" id="sidebarOverlay"></div>
            
            <!-- Page Content -->
            <div id="page-content-wrapper">
                <!-- Topbar -->
                @include('layouts.partials.topbar')
                
                <!-- Main Content -->
                <main class="main-content">
                    
                    <!-- Page Header for Layouts that don't have custom ones -->
                    @hasSection('title')
                    <div class="page-header d-md-none">
                        <div class="page-title-group">
                            <h4>@yield('title')</h4>
                        </div>
                    </div>
                    @endif
 
                    <!-- Flash Messages (Modern Toasts) -->
                    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1055;">
                        @if (session('success'))
                            <div class="alert alert-modern alert-success alert-dismissible fade show mb-0" role="alert">
                                <div class="alert-icon"><i class="bi bi-check-circle-fill"></i></div>
                                <div>{{ session('success') }}</div>
                                <button type="button" class="btn-close ms-auto mt-1" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        @if (session('error'))
                            <div class="alert alert-modern alert-danger alert-dismissible fade show mb-0" role="alert">
                                <div class="alert-icon"><i class="bi bi-exclamation-octagon-fill"></i></div>
                                <div>{{ session('error') }}</div>
                                <button type="button" class="btn-close ms-auto mt-1" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    </div>
 
                    <!-- Main Dynamic Content -->
                    @yield('content')
                    
                </main>
            </div>
        </div>

    <nav class="pwa-bottom-nav d-lg-none" id="pwaBottomNav">
        <a href="{{ route('dashboard') }}" class="pwa-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2{{ request()->routeIs('dashboard') ? '-fill' : '' }}"></i>
            <span>Home</span>
        </a>
        @can('assets.view')
        <a href="{{ route('assets.index') }}" class="pwa-nav-item {{ request()->routeIs('assets.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam{{ request()->routeIs('assets.*') ? '-fill' : '' }}"></i>
            <span>Assets</span>
        </a>
        @else
        <a href="{{ route('staff.requests.index') }}" class="pwa-nav-item {{ request()->routeIs('staff.requests.*') ? 'active' : '' }}">
            <i class="bi bi-patch-question{{ request()->routeIs('staff.requests.*') ? '-fill' : '' }}"></i>
            <span>Pinjam</span>
        </a>
        @endcan
        
        <!-- Empty placeholder to leave space for the floating standalone Scan FAB -->
        <div style="flex: 1;"></div>
        
        @can('consumables.view')
        <a href="{{ route('consumables.index') }}" class="pwa-nav-item {{ request()->routeIs('consumables.*') ? 'active' : '' }}">
            <i class="bi bi-basket{{ request()->routeIs('consumables.*') ? '-fill' : '' }}"></i>
            <span>Stock</span>
        </a>
        @else
        <a href="{{ route('staff.damage_reports.index') }}" class="pwa-nav-item {{ request()->routeIs('staff.damage_reports.*') ? 'active' : '' }}">
            <i class="bi bi-shield-fill-exclamation{{ request()->routeIs('staff.damage_reports.*') ? '-fill' : '' }}"></i>
            <span>Lapor</span>
        </a>
        @endcan
        <button class="pwa-nav-item" id="pwaMoreBtn" type="button">
            <i class="bi bi-three-dots"></i>
            <span>More</span>
        </button>
    </nav>

    <!-- Standalone Floating Scan FAB (Mobile Only) -->
    <button class="pwa-scan-fab-fixed d-lg-none" id="pwaScanBtn" type="button">
        <div class="pwa-scan-circle-fixed">
            <i class="bi bi-qr-code-scan"></i>
        </div>
        <span>Scan</span>
    </button>

    <!-- Global Barcode Scanner Modal (PWA) -->
    <div class="modal fade" id="globalScannerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #1c1515 0%, #291818 100%);">
                    <h6 class="modal-title fw-bold text-white mb-0">
                        <i class="bi bi-qr-code-scan me-2"></i> Scan Asset Barcode
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div id="globalReader" style="width: 100%; border-radius: 12px; overflow: hidden; background: #111;"></div>
                    <div id="globalScannerStatus" class="text-center mt-3 text-muted small">
                        <i class="bi bi-camera me-1"></i> Allow camera access to scan barcodes
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Scan Action Center Modal -->
    <div class="modal fade" id="scanActionCenterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: #0f172a; color: #f8fafc;">
                <div class="modal-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border-bottom: 1px solid rgba(255,255,255,0.06) !important;">
                    <h6 class="modal-title fw-bold text-white mb-0 d-flex align-items-center">
                        <span class="p-1.5 bg-danger bg-opacity-10 rounded-3 me-2 border border-danger border-opacity-20">
                            <i class="bi bi-qr-code-scan text-danger"></i>
                        </span>
                        Scan Action Center
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Item Display Card (Glassmorphic) -->
                    <div class="p-3 mb-4 rounded-4 position-relative overflow-hidden" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); box-shadow: inset 0 1px 1px rgba(255,255,255,0.1);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-15 rounded-3 border border-danger border-opacity-10" style="width: 56px; height: 56px; flex-shrink: 0;" id="sac-item-icon-box">
                                <i class="bi bi-box-seam text-danger fs-3" id="sac-item-icon"></i>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <span class="badge bg-secondary bg-opacity-25 text-light mb-1 text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;" id="sac-item-type">Fixed Asset</span>
                                <h5 class="fw-bold text-white mb-1 text-truncate" id="sac-item-name">-</h5>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="font-monospace text-danger small fw-semibold" id="sac-item-code">-</span>
                                    <span class="text-white-50" id="sac-item-divider">•</span>
                                    <span class="small text-white-50" id="sac-item-meta">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-secondary border-opacity-20">
                            <div class="d-flex gap-2">
                                <span id="sac-item-status-badge"></span>
                                <span id="sac-item-condition-badge"></span>
                            </div>
                            <div class="small fw-semibold text-white-50" id="sac-item-holder">
                                <i class="bi bi-person me-1"></i>Holder: <span class="text-white" id="sac-item-holder-name">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions Grid -->
                    <div class="row g-3">
                        <!-- View Details -->
                        <div class="col-6">
                            <button type="button" class="btn border-0 text-start w-100 p-3 h-100 d-flex flex-column justify-content-between rounded-4 transition-all" id="sac-btn-view" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08) !important; transition: all 0.2s ease;">
                                <div class="p-2 bg-primary bg-opacity-15 rounded-3 mb-3 border border-primary border-opacity-10" style="width: fit-content;">
                                    <i class="bi bi-eye-fill text-primary fs-5 d-block"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white small">Lihat Detail</div>
                                    <small class="text-white-50" style="font-size: 0.72rem; line-height: 1.2; display: block; margin-top: 4px;">Rincian spesifikasi, biaya, dan vendor.</small>
                                </div>
                            </button>
                        </div>
                        
                        <!-- Request Borrow / Claim -->
                        <div class="col-6">
                            <a href="#" class="btn border-0 text-start w-100 p-3 h-100 d-flex flex-column justify-content-between rounded-4 transition-all" id="sac-btn-request" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08) !important; transition: all 0.2s ease;">
                                <div class="p-2 bg-success bg-opacity-15 rounded-3 mb-3 border border-success border-opacity-10" style="width: fit-content;">
                                    <i class="bi bi-hand-index-thumb-fill text-success fs-5 d-block"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white small" id="sac-btn-request-title">Ajukan Pinjam</div>
                                    <small class="text-white-50" style="font-size: 0.72rem; line-height: 1.2; display: block; margin-top: 4px;" id="sac-btn-request-desc">Buat form request pinjam/ambil APD.</small>
                                </div>
                            </a>
                        </div>

                        <!-- Report Damage -->
                        <div class="col-6">
                            <a href="#" class="btn border-0 text-start w-100 p-3 h-100 d-flex flex-column justify-content-between rounded-4 transition-all" id="sac-btn-damage" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08) !important; transition: all 0.2s ease;">
                                <div class="p-2 bg-warning bg-opacity-15 rounded-3 mb-3 border border-warning border-opacity-10" style="width: fit-content;">
                                    <i class="bi bi-exclamation-triangle-fill text-warning fs-5 d-block"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white small">Laporkan Rusak</div>
                                    <small class="text-white-50" style="font-size: 0.72rem; line-height: 1.2; display: block; margin-top: 4px;">Kirim laporan kendala / temuan alat rusak.</small>
                                </div>
                            </a>
                        </div>

                        <!-- Admin Only: Quick Status Update -->
                        @can('assets.edit')
                        <div class="col-6">
                            <button type="button" class="btn border-0 text-start w-100 p-3 h-100 d-flex flex-column justify-content-between rounded-4 transition-all" id="sac-btn-quick-update" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08) !important; transition: all 0.2s ease;" data-bs-toggle="collapse" data-bs-target="#sac-admin-panel">
                                <div class="p-2 bg-danger bg-opacity-15 rounded-3 mb-3 border border-danger border-opacity-10" style="width: fit-content;">
                                    <i class="bi bi-sliders text-danger fs-5 d-block"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white small">Ubah Status</div>
                                    <small class="text-white-50" style="font-size: 0.72rem; line-height: 1.2; display: block; margin-top: 4px;">Edit status/pemegang secara cepat.</small>
                                </div>
                            </button>
                        </div>
                        @else
                        <div class="col-6">
                            <div class="p-3 h-100 d-flex flex-column justify-content-center align-items-center rounded-4 border border-secondary border-opacity-10 text-center" style="background: rgba(255,255,255,0.01);">
                                <i class="bi bi-shield-lock text-white-50 fs-3 mb-2 opacity-50"></i>
                                <div class="fw-bold text-white-50 small" style="font-size: 0.75rem;">HSE Guard Portal</div>
                            </div>
                        </div>
                        @endcan
                    </div>
 
                    <!-- Admin Quick Update Collapse Panel -->
                    @can('assets.edit')
                    <div class="collapse mt-3" id="sac-admin-panel">
                        <div class="p-3 rounded-4 border border-secondary border-opacity-20" style="background: rgba(255,255,255,0.02);">
                            <h6 class="fw-bold text-white mb-3 small d-flex align-items-center gap-1">
                                <i class="bi bi-sliders text-danger"></i> Quick Update Panel
                            </h6>
                            <form id="sac-quick-update-form" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="quick_update" value="1">
                                <div class="mb-2">
                                    <label class="form-label text-white-50 fw-semibold" style="font-size: 0.72rem;">Status Alat</label>
                                    <select class="form-select bg-dark text-white border-secondary border-opacity-20 p-2 rounded-3" name="status" id="sac-input-status" style="font-size: 0.85rem;">
                                        <option value="In Stock">In Stock</option>
                                        <option value="In Use">In Use</option>
                                        <option value="Maintenance">Maintenance</option>
                                        <option value="Retired">Retired</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="sac-holder-input-group">
                                    <label class="form-label text-white-50 fw-semibold" style="font-size: 0.72rem;">Nama Pemegang (Holder)</label>
                                    <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 p-2 rounded-3" name="assigned_to" id="sac-input-holder" placeholder="Kosongkan jika di Gudang" style="font-size: 0.85rem;">
                                </div>
                                <button type="submit" class="btn btn-danger w-100 fw-bold py-2 btn-sm rounded-3">
                                    Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- PWA More Menu Overlay (Mobile Only) -->
    <div class="pwa-more-overlay d-lg-none" id="pwaMoreOverlay"></div>
    <div class="pwa-more-menu d-lg-none" id="pwaMoreMenu">
        <div class="pwa-more-handle"></div>
        <div class="pwa-more-header">
            <span class="fw-bold">More Options</span>
            <button class="btn-close btn-close-sm" id="pwaMoreClose"></button>
        </div>

        @php
            $moreItems = [];
            
            if (auth()->user()->can('master.manage')) {
                $moreItems[] = [
                    'url' => route('categories.index'),
                    'icon' => 'bi-tags-fill',
                    'bg' => 'linear-gradient(135deg, #C0392B, #E74C3C)',
                    'label' => 'Categories'
                ];
                $moreItems[] = [
                    'url' => route('locations.index'),
                    'icon' => 'bi-geo-alt-fill',
                    'bg' => 'linear-gradient(135deg, #3498DB, #2980B9)',
                    'label' => 'Locations'
                ];
                $moreItems[] = [
                    'url' => route('suppliers.index'),
                    'icon' => 'bi-truck',
                    'bg' => 'linear-gradient(135deg, #E67E22, #D35400)',
                    'label' => 'Suppliers'
                ];
            }
            if (auth()->user()->can('consumables.view')) {
                $moreItems[] = [
                    'url' => route('consumables.transactions.in'),
                    'icon' => 'bi-box-arrow-in-down',
                    'bg' => 'linear-gradient(135deg, #27AE60, #229954)',
                    'label' => 'Stock In'
                ];
                $moreItems[] = [
                    'url' => route('consumables.transactions.out'),
                    'icon' => 'bi-box-arrow-up',
                    'bg' => 'linear-gradient(135deg, #E74C3C, #C0392B)',
                    'label' => 'Stock Out'
                ];
            }
            if (auth()->user()->can('master.manage')) {
                $moreItems[] = [
                    'url' => route('stock-opnames.index'),
                    'icon' => 'bi-clipboard-data',
                    'bg' => 'linear-gradient(135deg, #F1C40F, #F39C12)',
                    'label' => 'Stock Opname'
                ];
            }
            $moreItems[] = [
                'url' => route('profile.edit'),
                'icon' => 'bi-person-fill',
                'bg' => 'linear-gradient(135deg, #8E44AD, #7D3C98)',
                'label' => 'Profile'
            ];
            if (auth()->user()->can('users.manage')) {
                $moreItems[] = [
                    'url' => route('admin.users.index'),
                    'icon' => 'bi-people-fill',
                    'bg' => 'linear-gradient(135deg, #2C3E50, #34495E)',
                    'label' => 'Users'
                ];
            }
            if (auth()->user()->can('config.manage')) {
                $moreItems[] = [
                    'url' => route('admin.configs.index'),
                    'icon' => 'bi-gear-fill',
                    'bg' => 'linear-gradient(135deg, #7F8C8D, #95A5A6)',
                    'label' => 'Config'
                ];
            }
            if (auth()->user()->hasRole('admin')) {
                $moreItems[] = [
                    'url' => route('admin.logs.index'),
                    'icon' => 'bi-clock-history',
                    'bg' => 'linear-gradient(135deg, #1ABC9C, #16A085)',
                    'label' => 'Logs'
                ];
            }
            if (auth()->user()->can('requests.view')) {
                $moreItems[] = [
                    'url' => route('staff.requests.index'),
                    'icon' => 'bi-patch-question-fill',
                    'bg' => 'linear-gradient(135deg, #3498DB, #2980B9)',
                    'label' => 'History Pinjam'
                ];
            }
            if (auth()->user()->can('damage_reports.view')) {
                $moreItems[] = [
                    'url' => route('staff.damage_reports.index'),
                    'icon' => 'bi-shield-fill-exclamation',
                    'bg' => 'linear-gradient(135deg, #E74C3C, #C0392B)',
                    'label' => 'Laporan Rusak'
                ];
            }
            
            $chunks = array_chunk($moreItems, 8);
        @endphp

        <div class="pwa-more-carousel" id="pwaMoreCarousel">
            @foreach($chunks as $pageIndex => $chunk)
                <div class="pwa-more-slide">
                    <div class="pwa-more-grid">
                        @foreach($chunk as $item)
                            <a href="{{ $item['url'] }}" class="pwa-more-item">
                                <div class="pwa-more-icon" style="background: {{ $item['bg'] }};">
                                    <i class="bi {{ $item['icon'] }}"></i>
                                </div>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        @if(count($chunks) > 1)
            <div class="pwa-more-dots">
                @foreach($chunks as $pageIndex => $chunk)
                    <span class="pwa-more-dot {{ $pageIndex === 0 ? 'active' : '' }}" data-slide="{{ $pageIndex }}"></span>
                @endforeach
            </div>
        @endif
    </div>
 
    <!-- Global Search Modal (Command Palette Style) -->
    <div class="modal fade" id="globalSearchModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="search-bar-wrapper">
                    <i class="bi bi-search search-icon-left"></i>
                    <input type="text" id="globalSearchInput" class="search-input-field" placeholder="Search assets, consumables, categories..." autocomplete="off">
                    <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div id="globalSearchResults">
                        <div class="search-state-container">
                            <i class="bi bi-search search-state-icon"></i>
                            <div class="search-state-title">Search {{ config('app.name', 'HSE Asset Management') }}</div>
                            <div class="search-state-desc">Find codes, names, serial numbers, units, or locations in real-time</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <small class="text-muted">
                        Press <span class="kbd-badge">Ctrl</span> + <span class="kbd-badge">K</span> to trigger search
                    </small>
                    <small class="text-muted">
                        <i class="bi bi-lightning-charge-fill text-warning me-1"></i>Instant Search
                    </small>
                </div>
            </div>
        </div>
    </div>

        <!-- Bootstrap 5 JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- HTML5 QR Code Scanner Library -->
        <script src="https://unpkg.com/html5-qrcode"></script>
        
        <!-- Sidebar Toggle Script -->
        <script>
            // Unified Barcode Scanner Scan Action Center handler
            window.handleScannedBarcode = function(decodedText) {
                // 1. Play success audio cue
                try {
                    let audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-84.wav');
                    audio.volume = 0.5;
                    audio.play().catch(() => {});
                } catch (e) {}

                // 2. Close active scanner modals
                ['globalScannerModal', 'scannerModal'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        const modal = bootstrap.Modal.getInstance(el);
                        if (modal) modal.hide();
                    }
                });

                // 3. Fetch details
                fetch(`/api/assets/by-code/${encodeURIComponent(decodedText)}`)
                    .then(res => {
                        if (!res.ok) throw new Error('Not found');
                        return res.json();
                    })
                    .then(data => {
                        if (data.found) {
                            const item = data.data;
                            const type = data.type;

                            // Populate Scan Action Center Modal
                            document.getElementById('sac-item-name').innerText = item.name;
                            document.getElementById('sac-item-code').innerText = item.code;
                            document.getElementById('sac-item-type').innerText = type === 'fixed_asset' ? 'Fixed Asset' : 'Consumable (APD)';

                            // Icon changes
                            const iconBox = document.getElementById('sac-item-icon-box');
                            const icon = document.getElementById('sac-item-icon');
                            if (type === 'fixed_asset') {
                                iconBox.className = 'd-flex align-items-center justify-content-center bg-danger bg-opacity-15 rounded-3 border border-danger border-opacity-10';
                                icon.className = 'bi bi-box-seam text-danger fs-3';
                            } else {
                                iconBox.className = 'd-flex align-items-center justify-content-center bg-success bg-opacity-15 rounded-3 border border-success border-opacity-10';
                                icon.className = 'bi bi-basket text-success fs-3';
                            }

                            // Meta location / stock
                            if (type === 'fixed_asset') {
                                document.getElementById('sac-item-meta').innerText = `Lokasi: ${item.location ? item.location.name : '-'}`;
                                document.getElementById('sac-item-holder').style.display = 'block';
                                document.getElementById('sac-item-holder-name').innerText = item.assigned_to ? item.assigned_to : '-';
                            } else {
                                document.getElementById('sac-item-meta').innerText = `Stok: ${item.stock} ${item.unit || 'pcs'}`;
                                document.getElementById('sac-item-holder').style.display = 'none';
                            }

                            // Badges for status & condition
                            const statusBadge = document.getElementById('sac-item-status-badge');
                            const condBadge = document.getElementById('sac-item-condition-badge');
                            
                            statusBadge.innerHTML = '';
                            condBadge.innerHTML = '';

                            if (type === 'fixed_asset') {
                                let statusClass = 'bg-secondary';
                                if (item.status === 'In Stock') statusClass = 'bg-success bg-opacity-25 text-success border border-success border-opacity-20';
                                if (item.status === 'In Use') statusClass = 'bg-primary bg-opacity-25 text-primary border border-primary border-opacity-20';
                                if (item.status === 'Maintenance') statusClass = 'bg-warning bg-opacity-25 text-warning border border-warning border-opacity-20';
                                if (item.status === 'Retired') statusClass = 'bg-danger bg-opacity-25 text-danger border border-danger border-opacity-20';
                                
                                statusBadge.innerHTML = `<span class="badge ${statusClass} text-uppercase fw-bold" style="font-size:0.68rem">${item.status}</span>`;

                                let condClass = 'bg-secondary';
                                if (item.condition === 'Good') condClass = 'bg-success text-white';
                                if (item.condition === 'Damaged') condClass = 'bg-warning text-dark';
                                if (item.condition === 'Broken') condClass = 'bg-danger text-white';
                                condBadge.innerHTML = `<span class="badge ${condClass} text-uppercase fw-bold" style="font-size:0.68rem">${item.condition}</span>`;
                            }

                            // Button View logic
                            const viewBtn = document.getElementById('sac-btn-view');
                            if (type === 'fixed_asset') {
                                viewBtn.style.display = 'flex';
                                viewBtn.onclick = function() {
                                    const sacModal = bootstrap.Modal.getInstance(document.getElementById('scanActionCenterModal'));
                                    if (sacModal) sacModal.hide();
                                    
                                    // If we are on assets page, open detail modal directly!
                                    if (typeof showAssetDetails === 'function') {
                                        showAssetDetails(item.id);
                                    } else {
                                        // Redirect to assets page with direct parameter to open it
                                        window.location.href = `/assets?view_id=${item.id}`;
                                    }
                                };
                            } else {
                                viewBtn.style.display = 'none';
                            }

                            // Prefilled request link
                            const reqBtn = document.getElementById('sac-btn-request');
                            const reqTitle = document.getElementById('sac-btn-request-title');
                            const reqDesc = document.getElementById('sac-btn-request-desc');
                            
                            if (type === 'fixed_asset') {
                                reqTitle.innerText = 'Ajukan Pinjam';
                                reqDesc.innerText = 'Buat form request pinjam/mutasi alat.';
                                reqBtn.href = `/staff/requests/create?request_type=fixed_asset&asset_id=${item.id}`;
                            } else {
                                reqTitle.innerText = 'Ambil APD';
                                reqDesc.innerText = 'Buat form klaim barang APD habis pakai.';
                                reqBtn.href = `/staff/requests/create?request_type=consumable&consumable_id=${item.id}`;
                            }

                            // Prefilled damage link
                            const dmgBtn = document.getElementById('sac-btn-damage');
                            if (type === 'fixed_asset') {
                                dmgBtn.href = `/staff/damage-reports/create?item_type=fixed_asset&asset_id=${item.id}`;
                            } else {
                                dmgBtn.href = `/staff/damage-reports/create?item_type=consumable&consumable_id=${item.id}`;
                            }

                            // Quick Admin update form
                            const adminPanel = document.getElementById('sac-admin-panel');
                            if (adminPanel) {
                                const form = document.getElementById('sac-quick-update-form');
                                if (form) {
                                    form.action = `/assets/${item.id}`;
                                    document.getElementById('sac-input-status').value = item.status || 'In Stock';
                                    document.getElementById('sac-input-holder').value = item.assigned_to || '';
                                    
                                    // Hide holder input if status is In Stock or Maintenance
                                    const holderGroup = document.getElementById('sac-holder-input-group');
                                    const statusSelect = document.getElementById('sac-input-status');
                                    
                                    function toggleHolderInput() {
                                        if (statusSelect.value === 'In Stock' || statusSelect.value === 'Maintenance' || statusSelect.value === 'Retired') {
                                            holderGroup.style.opacity = '0.5';
                                            document.getElementById('sac-input-holder').disabled = true;
                                            document.getElementById('sac-input-holder').value = '';
                                        } else {
                                            holderGroup.style.opacity = '1';
                                            document.getElementById('sac-input-holder').disabled = false;
                                        }
                                    }
                                    statusSelect.onchange = toggleHolderInput;
                                    toggleHolderInput();
                                }
                            }

                            // Show Scan Action Center Modal
                            const sacModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('scanActionCenterModal'));
                            sacModal.show();
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Aset atau APD tidak ditemukan dengan barcode: ' + decodedText);
                    });
            };

            document.addEventListener("DOMContentLoaded", function() {
                const toggleBtn = document.getElementById("sidebarToggle");
                const wrapper = document.getElementById("sidebar-wrapper");
                const overlay = document.getElementById("sidebarOverlay");
                
                if(toggleBtn && wrapper && overlay) {
                    // Check localStorage to persist the collapsed sidebar state across page loads
                    const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
                    if (isCollapsed && window.innerWidth >= 992) {
                        wrapper.classList.add("toggled");
                    }

                    toggleBtn.addEventListener("click", function(e) {
                        e.preventDefault();
                        wrapper.classList.toggle("toggled");
                        if (window.innerWidth < 992) {
                            overlay.classList.toggle("active");
                            document.body.classList.toggle("sidebar-open");
                        } else {
                            // Persist the state in localStorage for desktop screen sizes
                            localStorage.setItem('sidebar-collapsed', wrapper.classList.contains('toggled'));
                        }
                    });
                    
                    overlay.addEventListener("click", function() {
                        wrapper.classList.remove("toggled");
                        overlay.classList.remove("active");
                        document.body.classList.remove("sidebar-open");
                    });

                    // Prevent touch dragging on mobile sidebar overlay from scrolling the page
                    overlay.addEventListener('touchmove', function(e) {
                        e.preventDefault();
                    }, { passive: false });
                }

                // --- PWA Bottom Nav "More" Menu Toggle ---
                const moreBtn = document.getElementById('pwaMoreBtn');
                const moreMenu = document.getElementById('pwaMoreMenu');
                const moreOverlay = document.getElementById('pwaMoreOverlay');
                const moreClose = document.getElementById('pwaMoreClose');

                function openMoreMenu() {
                    moreMenu && moreMenu.classList.add('open');
                    moreOverlay && moreOverlay.classList.add('active');
                    document.body.classList.add('pwa-menu-open');
                }
                function closeMoreMenu() {
                    moreMenu && moreMenu.classList.remove('open');
                    moreOverlay && moreOverlay.classList.remove('active');
                    document.body.classList.remove('pwa-menu-open');
                }

                moreBtn && moreBtn.addEventListener('click', openMoreMenu);
                moreOverlay && moreOverlay.addEventListener('click', closeMoreMenu);
                moreClose && moreClose.addEventListener('click', closeMoreMenu);

                // Sync PWA More Menu Slider dots on scroll and dot clicks
                const pwaCarousel = document.getElementById('pwaMoreCarousel');
                const pwaDots = document.querySelectorAll('.pwa-more-dot');
                if (pwaCarousel && pwaDots.length) {
                    // Scroll Listener to update active dot
                    pwaCarousel.addEventListener('scroll', function() {
                        const width = pwaCarousel.offsetWidth;
                        if (width > 0) {
                            const page = Math.round(pwaCarousel.scrollLeft / width);
                            pwaDots.forEach((dot, idx) => {
                                if (idx === page) {
                                    dot.classList.add('active');
                                } else {
                                    dot.classList.remove('active');
                                }
                            });
                        }
                    });

                    // Dot Clicks to scroll to slides
                    pwaDots.forEach(dot => {
                        dot.addEventListener('click', function() {
                            const slideIndex = parseInt(this.getAttribute('data-slide'));
                            const width = pwaCarousel.offsetWidth;
                            pwaCarousel.scrollTo({
                                left: slideIndex * width,
                                behavior: 'smooth'
                             });
                         });
                     });
                 }

                // Prevent touch dragging on PWA overlays & menu container from causing rubber-band scroll
                if (moreOverlay) {
                    moreOverlay.addEventListener('touchmove', function(e) {
                        e.preventDefault();
                    }, { passive: false });
                }

                // --- PWA Global Barcode Scanner ---
                const scanBtn = document.getElementById('pwaScanBtn');
                let globalScanner = null;

                if (scanBtn) {
                    scanBtn.addEventListener('click', function() {
                        const modalEl = document.getElementById('globalScannerModal');
                        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                        modal.show();
                    });
                }

                const globalScannerModal = document.getElementById('globalScannerModal');
                if (globalScannerModal) {
                    globalScannerModal.addEventListener('shown.bs.modal', function() {
                        const statusEl = document.getElementById('globalScannerStatus');
                        statusEl.innerHTML = '<span class="spinner-border spinner-border-sm text-secondary me-2"></span>Starting camera...';

                        globalScanner = new Html5Qrcode('globalReader');
                        const config = {
                            fps: 10,
                            qrbox: function(w, h) {
                                let size = Math.floor(Math.min(w, h) * 0.7);
                                return { width: size, height: size };
                            },
                            aspectRatio: 1.0
                        };

                        globalScanner.start(
                            { facingMode: 'environment' },
                            config,
                            (decodedText) => {
                                handleScannedBarcode(decodedText);
                            },
                            () => {}
                        ).then(() => {
                            statusEl.innerHTML = '<span class="text-success fw-bold"><i class="bi bi-circle-fill text-success me-1" style="font-size:8px"></i> Scanner Active — Point at a barcode</span>';
                        }).catch(err => {
                            statusEl.innerHTML = '<span class="text-danger fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i> Camera Error: ' + err + '</span>';
                        });
                    });

                    globalScannerModal.addEventListener('hidden.bs.modal', function() {
                        if (globalScanner) {
                            globalScanner.stop().then(() => {
                                globalScanner = null;
                                document.getElementById('globalReader').innerHTML = '';
                            }).catch(() => {});
                        }
                    });
                }

                // --- PWA Real-time Notifications ---
                function loadNotifications() {
                    fetch("{{ route('api.notifications') }}")
                        .then(response => response.json())
                        .then(data => {
                            const dot = document.getElementById('notificationDot');
                            const badge = document.getElementById('notificationCountBadge');
                            const list = document.getElementById('notificationList');
                            
                            if (data.count > 0) {
                                dot.style.display = 'block';
                                badge.innerText = data.count + ' alerts';
                                badge.className = 'badge bg-danger rounded-pill';
                                
                                let html = '';
                                data.notifications.forEach(item => {
                                    let iconColor = item.type === 'warning' ? 'text-warning bg-warning bg-opacity-10' : 'text-danger bg-danger bg-opacity-10';
                                    let icon = item.type === 'warning' ? 'bi-exclamation-triangle' : 'bi-exclamation-octagon';
                                    
                                    html += `
                                        <a href="${item.url}" class="notification-item">
                                            <div class="notification-item-icon ${iconColor}">
                                                <i class="bi ${icon}"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-dark small">${item.title}</div>
                                                <div class="text-muted small mt-1" style="font-size: 0.75rem;">${item.message}</div>
                                                <div class="text-xs text-secondary mt-1" style="font-size: 0.65rem; font-weight: 500;">${item.time}</div>
                                            </div>
                                        </a>
                                    `;
                                });
                                list.innerHTML = html;
                            } else {
                                dot.style.display = 'none';
                                badge.innerText = '0 alerts';
                                badge.className = 'badge bg-success rounded-pill';
                                list.innerHTML = `
                                    <div class="p-4 text-center text-muted">
                                        <i class="bi bi-check-circle text-success fs-4 d-block mb-2"></i>
                                        <span class="small">No active alerts</span>
                                    </div>
                                `;
                            }
                        })
                        .catch(err => console.error('Failed to load notifications', err));
                }
                
                loadNotifications();
                // Refresh notifications every 60 seconds
                setInterval(loadNotifications, 60000);

                // --- Instant Search Input Logic ---
                const searchInput = document.getElementById('globalSearchInput');
                const searchResults = document.getElementById('globalSearchResults');
                let searchTimeout = null;

                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        const q = this.value.trim();
                        clearTimeout(searchTimeout);
                        
                        if (q.length < 2) {
                            searchResults.innerHTML = `
                                <div class="search-state-container">
                                    <i class="bi bi-search search-state-icon"></i>
                                    <div class="search-state-title">Search {{ config('app.name', 'HSE Asset Management') }}</div>
                                    <div class="search-state-desc">Find codes, names, serial numbers, units, or locations in real-time</div>
                                </div>
                            `;
                            return;
                        }

                        searchResults.innerHTML = `
                            <div class="search-state-container">
                                <div class="search-loading-pulse"></div>
                                <div class="search-state-title">Searching for "${q}"</div>
                                <div class="search-state-desc">Scanning database assets and consumables...</div>
                            </div>
                        `;

                        searchTimeout = setTimeout(() => {
                            fetch(`/api/search?q=${encodeURIComponent(q)}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.results && data.results.length > 0) {
                                        let html = '<div class="search-results-list">';
                                        data.results.forEach(item => {
                                            html += `
                                                <a href="${item.url}" class="search-result-item">
                                                    <div class="result-icon-box">
                                                        <i class="bi ${item.icon}"></i>
                                                    </div>
                                                    <div class="result-info-box">
                                                        <div class="result-title">${item.title}</div>
                                                        <div class="result-subtitle">${item.subtitle}</div>
                                                    </div>
                                                    <span class="result-type-badge">${item.type}</span>
                                                </a>
                                            `;
                                        });
                                        html += '</div>';
                                        searchResults.innerHTML = html;
                                    } else {
                                        searchResults.innerHTML = `
                                            <div class="search-state-container">
                                                <i class="bi bi-emoji-frown search-state-icon" style="animation: none;"></i>
                                                <div class="search-state-title">No results found</div>
                                                <div class="search-state-desc">We couldn't find any assets, consumables, or categories matching "${q}"</div>
                                            </div>
                                        `;
                                    }
                                })
                                .catch(err => {
                                    console.error(err);
                                    searchResults.innerHTML = `
                                        <div class="search-state-container">
                                            <i class="bi bi-exclamation-triangle search-state-icon text-danger" style="animation: none;"></i>
                                            <div class="search-state-title text-danger">Search Error</div>
                                            <div class="search-state-desc">Something went wrong. Please check your network connection and try again.</div>
                                        </div>
                                    `;
                                });
                        }, 300);
                    });
                }

                // Keyboard Shortcut Ctrl+K
                document.addEventListener('keydown', function(e) {
                    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                        e.preventDefault();
                        const modalEl = document.getElementById('globalSearchModal');
                        if (modalEl) {
                            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                            modal.show();
                            setTimeout(() => {
                                searchInput.focus();
                            }, 500);
                        }
                    }
                });

                // Quick status update AJAX handler for Scan Action Center
                const quickUpdateForm = document.getElementById('sac-quick-update-form');
                if (quickUpdateForm) {
                    quickUpdateForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const actionUrl = this.action;
                        const formData = new FormData(this);
                        
                        fetch(actionUrl, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                // Hide modal
                                const sacModal = bootstrap.Modal.getInstance(document.getElementById('scanActionCenterModal'));
                                if (sacModal) sacModal.hide();
                                
                                // Show success message
                                alert(data.message);
                                
                                // If we are on assets page, reload the datatable in place!
                                if (typeof $ !== 'undefined' && $.fn.DataTable && $('#assets-table').length) {
                                    $('#assets-table').DataTable().ajax.reload(null, false);
                                } else {
                                    // Reload page
                                    window.location.reload();
                                }
                            } else {
                                alert('Gagal memperbarui status aset.');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Terjadi kesalahan koneksi saat memperbarui status.');
                        });
                    });
                }
            });
        </script>
 
        @yield('scripts')
        @stack('scripts')

        <!-- Global Premium Page Loader -->
        <div id="global-page-loader" class="global-loader-overlay">
            <div class="global-loader-card">
                <div class="global-loader-spinner-wrapper">
                    <div class="global-loader-spinner-outer"></div>
                    <div class="global-loader-spinner-inner"></div>
                </div>
                <div class="global-loader-text">Loading Data</div>
            </div>
        </div>

        <script>
            // Global DataTable Processing Event Listener
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof $ !== 'undefined') {
                    $(document).on('processing.dt', function(e, settings, processing) {
                        const loader = document.getElementById('global-page-loader');
                        if (loader) {
                            if (processing) {
                                loader.classList.add('active');
                            } else {
                                loader.classList.remove('active');
                            }
                        }
                    });
                }

                // Auto-dismiss and manual exit transitions for Toast Alerts
                const toasts = document.querySelectorAll('.toast-container .alert-modern');
                toasts.forEach(function(toast) {
                    function dismissToast() {
                        toast.classList.add('dismissing');
                        // Wait for slide-out animation (300ms) then remove from DOM
                        setTimeout(function() {
                            toast.remove();
                        }, 350);
                    }

                    // 1. Auto-dismiss after 3.5 seconds
                    const autoHideTimer = setTimeout(dismissToast, 3500);

                    // 2. Manual click close button handler
                    const closeBtn = toast.querySelector('.btn-close');
                    if (closeBtn) {
                        closeBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            clearTimeout(autoHideTimer); // Cancel auto-dismiss if clicked manually
                            dismissToast();
                        });
                    }
                });
            });
        </script>
    </body>
</html>
