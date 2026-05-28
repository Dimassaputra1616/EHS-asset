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

    <!-- PWA Bottom Navigation Bar (Mobile Only) -->
    <nav class="pwa-bottom-nav d-lg-none" id="pwaBottomNav">
        <a href="{{ route('dashboard') }}" class="pwa-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2{{ request()->routeIs('dashboard') ? '-fill' : '' }}"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('assets.index') }}" class="pwa-nav-item {{ request()->routeIs('assets.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam{{ request()->routeIs('assets.*') ? '-fill' : '' }}"></i>
            <span>Assets</span>
        </a>
        
        <!-- Empty placeholder to leave space for the floating standalone Scan FAB -->
        <div style="flex: 1;"></div>
        
        <a href="{{ route('consumables.index') }}" class="pwa-nav-item {{ request()->routeIs('consumables.*') ? 'active' : '' }}">
            <i class="bi bi-basket{{ request()->routeIs('consumables.*') ? '-fill' : '' }}"></i>
            <span>Stock</span>
        </a>
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

    <!-- PWA More Menu Overlay (Mobile Only) -->
    <div class="pwa-more-overlay d-lg-none" id="pwaMoreOverlay"></div>
    <div class="pwa-more-menu d-lg-none" id="pwaMoreMenu">
        <div class="pwa-more-handle"></div>
        <div class="pwa-more-header">
            <span class="fw-bold">More Options</span>
            <button class="btn-close btn-close-sm" id="pwaMoreClose"></button>
        </div>
        <div class="pwa-more-grid">
            <a href="{{ route('categories.index') }}" class="pwa-more-item">
                <div class="pwa-more-icon" style="background: linear-gradient(135deg, #C0392B, #E74C3C);">
                    <i class="bi bi-tags-fill"></i>
                </div>
                <span>Categories</span>
            </a>
            <a href="{{ route('locations.index') }}" class="pwa-more-item">
                <div class="pwa-more-icon" style="background: linear-gradient(135deg, #3498DB, #2980B9);">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <span>Locations</span>
            </a>
            <a href="{{ route('suppliers.index') }}" class="pwa-more-item">
                <div class="pwa-more-icon" style="background: linear-gradient(135deg, #E67E22, #D35400);">
                    <i class="bi bi-truck"></i>
                </div>
                <span>Suppliers</span>
            </a>
            <a href="{{ route('consumables.transactions.in') }}" class="pwa-more-item">
                <div class="pwa-more-icon" style="background: linear-gradient(135deg, #27AE60, #229954);">
                    <i class="bi bi-box-arrow-in-down"></i>
                </div>
                <span>Stock In</span>
            </a>
            <a href="{{ route('consumables.transactions.out') }}" class="pwa-more-item">
                <div class="pwa-more-icon" style="background: linear-gradient(135deg, #E74C3C, #C0392B);">
                    <i class="bi bi-box-arrow-up"></i>
                </div>
                <span>Stock Out</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="pwa-more-item">
                <div class="pwa-more-icon" style="background: linear-gradient(135deg, #8E44AD, #7D3C98);">
                    <i class="bi bi-person-fill"></i>
                </div>
                <span>Profile</span>
            </a>
            @role('admin')
            <a href="{{ route('admin.users.index') }}" class="pwa-more-item">
                <div class="pwa-more-icon" style="background: linear-gradient(135deg, #2C3E50, #34495E);">
                    <i class="bi bi-people-fill"></i>
                </div>
                <span>Users</span>
            </a>
            <a href="{{ route('admin.configs.index') }}" class="pwa-more-item">
                <div class="pwa-more-icon" style="background: linear-gradient(135deg, #7F8C8D, #95A5A6);">
                    <i class="bi bi-gear-fill"></i>
                </div>
                <span>Config</span>
            </a>
            <a href="{{ route('admin.logs.index') }}" class="pwa-more-item">
                <div class="pwa-more-icon" style="background: linear-gradient(135deg, #1ABC9C, #16A085);">
                    <i class="bi bi-clock-history"></i>
                </div>
                <span>Logs</span>
            </a>
            @endrole
        </div>
        <!-- PWA Drawer Sign Out Button (Opsi 1) -->
        <div class="pwa-more-footer p-3 border-top bg-light">
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2 py-2.5" style="border-radius: 12px; font-weight: 700; font-size: 0.9rem; transition: all 0.2s ease;">
                    <i class="bi bi-box-arrow-right fs-5"></i>
                    <span>Sign Out Account</span>
                </button>
            </form>
        </div>
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
            document.addEventListener("DOMContentLoaded", function() {
                const toggleBtn = document.getElementById("sidebarToggle");
                const wrapper = document.getElementById("sidebar-wrapper");
                const overlay = document.getElementById("sidebarOverlay");
                
                if(toggleBtn && wrapper && overlay) {
                    toggleBtn.addEventListener("click", function(e) {
                        e.preventDefault();
                        wrapper.classList.toggle("toggled");
                        overlay.classList.toggle("active");
                        document.body.classList.toggle("sidebar-open");
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
                                return { width: Math.floor(w * 0.75), height: 150 };
                            },
                            aspectRatio: 1.0
                        };

                        globalScanner.start(
                            { facingMode: 'environment' },
                            config,
                            (decodedText) => {
                                try {
                                    let audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-84.wav');
                                    audio.volume = 0.5;
                                    audio.play().catch(() => {});
                                } catch (e) {}

                                // Close modal and redirect to assets with scanned code
                                const bsModal = bootstrap.Modal.getInstance(globalScannerModal);
                                bsModal.hide();
                                window.location.href = '{{ route("assets.index") }}?search=' + encodeURIComponent(decodedText);
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

                // Auto-dismiss alert-modern toasts after 5 seconds
                const flashAlerts = document.querySelectorAll('.alert-modern');
                flashAlerts.forEach(function(alert) {
                    setTimeout(function() {
                        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                        if (bsAlert) {
                            bsAlert.close();
                        } else {
                            alert.style.transition = 'opacity 0.5s ease';
                            alert.style.opacity = '0';
                            setTimeout(() => alert.remove(), 500);
                        }
                    }, 5000);
                });
            });
        </script>
 
        @yield('scripts')
        @stack('scripts')
    </body>
</html>
