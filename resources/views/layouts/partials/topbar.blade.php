<div class="topbar">
    <div class="d-flex align-items-center">
        <!-- Desktop Sidebar Toggle Button -->
        <button class="sidebar-toggle-btn me-2 d-none d-md-flex" id="sidebarToggle">
            <i class="bi bi-list fs-4"></i>
        </button>
        
        <!-- Mobile Left Icon / Back Button -->
        @if(request()->routeIs('dashboard') || request()->routeIs('assets.index') || request()->routeIs('consumables.index'))
            <!-- App Logo on Main Tab Pages (Mobile Only) -->
            <div class="d-flex d-md-none align-items-center justify-content-center me-2" style="width: 38px; height: 38px; border-radius: 10px; background: linear-gradient(135deg, rgba(192, 57, 43, 0.08) 0%, rgba(231, 76, 60, 0.04) 100%); border: 1px solid rgba(192, 57, 43, 0.12); color: var(--hse-red);">
                @if(config('app.app_logo'))
                    <img src="{{ Storage::url(config('app.app_logo')) }}" alt="Logo" style="max-width: 24px; max-height: 24px; object-fit: contain; border-radius: 4px;">
                @else
                    <i class="bi bi-shield-check fs-5"></i>
                @endif
            </div>
        @else
            <!-- Back Button on Sub-Pages / Details / Master Data (Mobile Only) -->
            <a href="javascript:history.back()" class="topbar-icon-btn d-flex d-md-none me-2 align-items-center justify-content-center" style="width: 38px; height: 38px; border-radius: 10px; background: rgba(0,0,0,0.04); color: #6c757d; border: none; text-decoration: none;">
                <i class="bi bi-chevron-left fs-5"></i>
            </a>
        @endif
        
        <!-- Animated Breadcrumb Area (Desktop) -->
        <nav aria-label="breadcrumb" class="d-none d-md-block">
            <ol class="breadcrumb page-breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i> {{ config('app.name', 'HSE System') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">@yield('title', 'Dashboard')</li>
            </ol>
        </nav>

        <!-- Title Area (Mobile - Aligned Left) -->
        <div class="d-block d-md-none">
            <span class="fw-bold text-dark pwa-topbar-title">@yield('title', 'Dashboard')</span>
        </div>
    </div>
    
    <div class="topbar-actions">
        <!-- Notification Bell Dropdown -->
        <div class="dropdown">
            <button class="topbar-icon-btn" data-bs-toggle="dropdown" aria-expanded="false" id="notificationBell">
                <i class="bi bi-bell"></i>
                <span class="notification-dot" id="notificationDot" style="display: none;"></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end p-0 notification-dropdown border-0" aria-labelledby="notificationBell" style="width: 320px;">
                <div class="p-3 d-flex justify-content-between align-items-center bg-light" style="border-bottom: 1px solid rgba(0, 0, 0, 0.05); border-top-left-radius: 15px; border-top-right-radius: 15px;">
                    <span class="fw-bold text-dark"><i class="bi bi-bell me-2 text-hse-red"></i>System Alerts</span>
                    <span class="badge bg-danger rounded-pill px-2.5 py-1.5" id="notificationCountBadge" style="font-size: 0.7rem; font-weight: 700;">0 alerts</span>
                </div>
                <div id="notificationList" style="max-height: 280px; overflow-y: auto;">
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-check-circle text-success fs-4 d-block mb-2"></i>
                        <span class="small">No active alerts</span>
                    </div>
                </div>
                <div class="p-2 border-top text-center bg-light" style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                    <a href="/consumables?low_stock=1" class="text-decoration-none small fw-bold text-hse-red">View Low Stock Items</a>
                </div>
            </div>
        </div>
        
        <!-- Search Button (Opens Modal) -->
        <button class="topbar-icon-btn d-flex" data-bs-toggle="modal" data-bs-target="#globalSearchModal" id="btnTopbarSearch">
            <i class="bi bi-search"></i>
        </button>

        <!-- User Dropdown Modern -->
        <div class="dropdown ms-2 d-none d-md-block">
            <div class="topbar-user-btn" data-bs-toggle="dropdown" aria-expanded="false">
                @if(Auth::user()->photo)
                    <img src="{{ Storage::url(Auth::user()->photo) }}" alt="User" style="object-fit: cover;">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=C0392B&color=fff" alt="User">
                @endif
                <div class="user-info d-none d-sm-block text-start px-1">
                    <div class="name">{{ Auth::user()->name }}</div>
                    <div class="role text-truncate" style="max-width: 80px;">{{ Auth::user()->roles->pluck('name')->first() ?? 'Staff' }}</div>
                </div>
                <i class="bi bi-chevron-down text-muted small ms-1 me-1"></i>
            </div>
            
            <ul class="dropdown-menu dropdown-menu-end modern-dropdown">
                <li class="px-3 py-2 border-bottom mb-1 d-sm-none">
                    <div class="fw-bold">{{ Auth::user()->name }}</div>
                    <div class="small text-muted">{{ Auth::user()->email }}</div>
                </li>
                <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i> My Profile</a></li>
                <li><a class="dropdown-item py-2" href="#"><i class="bi bi-gear me-2"></i> Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
