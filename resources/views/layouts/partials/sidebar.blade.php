<div id="sidebar-wrapper">
    <div class="sidebar-heading">
        <div class="sidebar-logo d-flex align-items-center justify-content-center" style="overflow: hidden;">
            @if(config('app.app_logo'))
                <img src="{{ Storage::url(config('app.app_logo')) }}" alt="Logo" style="max-width: 100%; max-height: 100%; object-fit: contain; border-radius: 4px;">
            @else
                <i class="bi bi-shield-check text-white"></i>
            @endif
        </div>
        <div class="sidebar-brand-text">
            {{ config('app.app_name', 'HSE ASSET') }}
        </div>
    </div>
    
    <div class="list-group">
        <a class="list-group-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="bi bi-grid-1x2"></i> <span>Dashboard</span>
        </a>
        
        <div class="sidebar-section-header">Assets & Items</div>
        
        <a class="list-group-item {{ request()->routeIs('assets.*') ? 'active' : '' }}" href="{{ route('assets.index') }}">
            <i class="bi bi-box-seam"></i> <span>Fixed Assets</span>
        </a>
        
        <a class="list-group-item d-flex justify-content-between align-items-center {{ request()->routeIs('consumables.*') ? 'active' : '' }}" 
           href="#consumablesSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('consumables.*') ? 'true' : 'false' }}">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-basket"></i> <span>Consumables</span>
            </div>
            <i class="bi bi-chevron-down small collapse-arrow"></i>
        </a>
        <div class="collapse {{ request()->routeIs('consumables.*') ? 'show' : '' }}" id="consumablesSubmenu">
            <div class="sidebar-submenu">
                <a class="list-group-item border-0 py-2 {{ request()->routeIs('consumables.index') ? 'active' : '' }}" href="{{ route('consumables.index') }}">
                    <i class="bi bi-circle-fill" style="font-size: 6px;"></i> Stock Consumables
                </a>
                <a class="list-group-item border-0 py-2 {{ request()->routeIs('consumables.transactions.in') ? 'active' : '' }}" href="{{ route('consumables.transactions.in') }}">
                    <i class="bi bi-box-arrow-in-down text-success"></i> Barang Masuk
                </a>
                <a class="list-group-item border-0 py-2 {{ request()->routeIs('consumables.transactions.out') ? 'active' : '' }}" href="{{ route('consumables.transactions.out') }}">
                    <i class="bi bi-box-arrow-up text-danger"></i> Barang Keluar
                </a>
            </div>
        </div>

        <div class="sidebar-section-header">Master Data</div>
        
        <a class="list-group-item {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
            <i class="bi bi-tags"></i> <span>Categories</span>
        </a>
        <a class="list-group-item {{ request()->routeIs('locations.*') ? 'active' : '' }}" href="{{ route('locations.index') }}">
            <i class="bi bi-geo-alt"></i> <span>Locations</span>
        </a>
        <a class="list-group-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
            <i class="bi bi-truck"></i> <span>Suppliers</span>
        </a>

        @role('admin')
        <div class="sidebar-section-header">Administration</div>
        
        <a class="list-group-item {{ request()->routeIs('admin.configs.*') ? 'active' : '' }}" href="{{ route('admin.configs.index') }}">
            <i class="bi bi-gear"></i> <span>Config Master</span>
        </a>
        <a class="list-group-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
            <i class="bi bi-people"></i> <span>Users</span>
        </a>
        <a class="list-group-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
            <i class="bi bi-shield-lock"></i> <span>Roles</span>
        </a>
        <a class="list-group-item {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}" href="{{ route('admin.logs.index') }}">
            <i class="bi bi-clock-history"></i> <span>Activity Logs</span>
        </a>
        @endrole
    </div>
    
    <div class="sidebar-user-info mt-auto text-center" style="border-top: 1px solid rgba(255,255,255,0.06); padding: 1.25rem 1rem 1.75rem 1rem;">
        <div style="font-size: 0.85rem; color: #E74C3C; letter-spacing: 1.5px; font-weight: 800; text-transform: uppercase; text-shadow: 1px 1px 0px #922B21, 2px 2px 0px #7B241C, 3px 3px 4px rgba(0,0,0,0.7); display: inline-block; margin-bottom: 2px;">
            HSE Asset Management
        </div>
        <div style="font-size: 0.65rem; color: #ffffff; margin-top: 4px; font-weight: 700; letter-spacing: 2px; opacity: 0.65; text-shadow: 1px 1px 2px rgba(0,0,0,0.9);">
            VERSION 1.0.0
        </div>
    </div>
</div>
