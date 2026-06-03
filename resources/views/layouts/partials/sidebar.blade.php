@php
    $theme = config('app.sidebar_theme', 'Dark');
@endphp

<style>
    /* Light Theme Override */
    #sidebar-wrapper.sidebar-theme-Light {
        background: #ffffff !important;
        box-shadow: 4px 0 20px rgba(0,0,0,0.05) !important;
        border-right: 1px solid #e2e8f0 !important;
    }
    #sidebar-wrapper.sidebar-theme-Light .sidebar-heading {
        background: #ffffff !important;
        border-bottom: 1px solid #e2e8f0 !important;
        color: #1e293b !important;
    }
    #sidebar-wrapper.sidebar-theme-Light .sidebar-heading .sidebar-brand-text {
        color: #1e293b !important;
    }
    #sidebar-wrapper.sidebar-theme-Light .sidebar-section-header {
        color: #94a3b8 !important;
    }
    #sidebar-wrapper.sidebar-theme-Light .list-group-item {
        color: #475569 !important;
    }
    #sidebar-wrapper.sidebar-theme-Light .list-group-item:hover {
        background-color: #f1f5f9 !important;
        color: #1e293b !important;
    }
    #sidebar-wrapper.sidebar-theme-Light .list-group-item.active {
        background: var(--hse-red-gradient) !important;
        color: #ffffff !important;
    }
    #sidebar-wrapper.sidebar-theme-Light .sidebar-submenu {
        background-color: #f8fafc !important;
    }
    #sidebar-wrapper.sidebar-theme-Light .sidebar-submenu .list-group-item {
        color: #64748b !important;
    }
    #sidebar-wrapper.sidebar-theme-Light .sidebar-submenu .list-group-item:hover {
        color: #1e293b !important;
    }
    #sidebar-wrapper.sidebar-theme-Light .sidebar-user-info {
        background: #f8fafc !important;
        border-top: 1px solid #e2e8f0 !important;
    }
    #sidebar-wrapper.sidebar-theme-Light .sidebar-user-info div {
        color: #1e293b !important;
        text-shadow: none !important;
    }
    #sidebar-wrapper.sidebar-theme-Light .sidebar-user-info div:first-child {
        color: var(--hse-red) !important;
    }

    /* Gradient Theme Override */
    #sidebar-wrapper.sidebar-theme-Gradient {
        background: linear-gradient(180deg, #922B21 0%, #641E16 100%) !important;
    }
    #sidebar-wrapper.sidebar-theme-Gradient .sidebar-heading {
        background: rgba(0, 0, 0, 0.15) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    #sidebar-wrapper.sidebar-theme-Gradient .sidebar-section-header {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    #sidebar-wrapper.sidebar-theme-Gradient .list-group-item {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    #sidebar-wrapper.sidebar-theme-Gradient .list-group-item:hover {
        background-color: rgba(255, 255, 255, 0.15) !important;
        color: #ffffff !important;
    }
    #sidebar-wrapper.sidebar-theme-Gradient .list-group-item.active {
        background: #ffffff !important;
        color: #922B21 !important;
    }
    #sidebar-wrapper.sidebar-theme-Gradient .sidebar-submenu {
        background-color: rgba(0, 0, 0, 0.1) !important;
    }
    #sidebar-wrapper.sidebar-theme-Gradient .sidebar-submenu .list-group-item {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    #sidebar-wrapper.sidebar-theme-Gradient .sidebar-submenu .list-group-item:hover {
        color: #ffffff !important;
    }
    #sidebar-wrapper.sidebar-theme-Gradient .sidebar-user-info {
        border-top: 1px solid rgba(255,255,255,0.15) !important;
    }
    #sidebar-wrapper.sidebar-theme-Gradient .sidebar-user-info div {
        color: #ffffff !important;
        text-shadow: none !important;
    }

    /* Forest Theme Override */
    #sidebar-wrapper.sidebar-theme-Forest {
        background: linear-gradient(180deg, #0f2e1b 0%, #153e25 100%) !important;
    }
    #sidebar-wrapper.sidebar-theme-Forest .sidebar-heading {
        background: rgba(0, 0, 0, 0.15) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
    }
    #sidebar-wrapper.sidebar-theme-Forest .sidebar-section-header {
        color: rgba(255, 255, 255, 0.45) !important;
    }
    #sidebar-wrapper.sidebar-theme-Forest .list-group-item {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    #sidebar-wrapper.sidebar-theme-Forest .list-group-item:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: #ffffff !important;
    }
    #sidebar-wrapper.sidebar-theme-Forest .list-group-item.active {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2) !important;
    }
    #sidebar-wrapper.sidebar-theme-Forest .sidebar-submenu {
        background-color: rgba(0, 0, 0, 0.15) !important;
    }
    #sidebar-wrapper.sidebar-theme-Forest .sidebar-submenu .list-group-item {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    #sidebar-wrapper.sidebar-theme-Forest .sidebar-submenu .list-group-item:hover {
        color: #ffffff !important;
    }
    #sidebar-wrapper.sidebar-theme-Forest .sidebar-user-info {
        border-top: 1px solid rgba(255,255,255,0.08) !important;
    }
    #sidebar-wrapper.sidebar-theme-Forest .sidebar-user-info div {
        color: #ffffff !important;
        text-shadow: none !important;
    }

    /* Ocean Theme Override */
    #sidebar-wrapper.sidebar-theme-Ocean {
        background: linear-gradient(180deg, #0b1329 0%, #111b36 100%) !important;
    }
    #sidebar-wrapper.sidebar-theme-Ocean .sidebar-heading {
        background: rgba(0, 0, 0, 0.15) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
    }
    #sidebar-wrapper.sidebar-theme-Ocean .sidebar-section-header {
        color: rgba(255, 255, 255, 0.45) !important;
    }
    #sidebar-wrapper.sidebar-theme-Ocean .list-group-item {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    #sidebar-wrapper.sidebar-theme-Ocean .list-group-item:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: #ffffff !important;
    }
    #sidebar-wrapper.sidebar-theme-Ocean .list-group-item.active {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2) !important;
    }
    #sidebar-wrapper.sidebar-theme-Ocean .sidebar-submenu {
        background-color: rgba(0, 0, 0, 0.15) !important;
    }
    #sidebar-wrapper.sidebar-theme-Ocean .sidebar-submenu .list-group-item {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    #sidebar-wrapper.sidebar-theme-Ocean .sidebar-submenu .list-group-item:hover {
        color: #ffffff !important;
    }
    #sidebar-wrapper.sidebar-theme-Ocean .sidebar-user-info {
        border-top: 1px solid rgba(255,255,255,0.08) !important;
    }
    #sidebar-wrapper.sidebar-theme-Ocean .sidebar-user-info div {
        color: #ffffff !important;
        text-shadow: none !important;
    }

    /* Orange Theme Override */
    #sidebar-wrapper.sidebar-theme-Orange {
        background: linear-gradient(180deg, #2d1305 0%, #3a1a08 100%) !important;
    }
    #sidebar-wrapper.sidebar-theme-Orange .sidebar-heading {
        background: rgba(0, 0, 0, 0.15) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
    }
    #sidebar-wrapper.sidebar-theme-Orange .sidebar-section-header {
        color: rgba(255, 255, 255, 0.45) !important;
    }
    #sidebar-wrapper.sidebar-theme-Orange .list-group-item {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    #sidebar-wrapper.sidebar-theme-Orange .list-group-item:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: #ffffff !important;
    }
    #sidebar-wrapper.sidebar-theme-Orange .list-group-item.active {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.2) !important;
    }
    #sidebar-wrapper.sidebar-theme-Orange .sidebar-submenu {
        background-color: rgba(0, 0, 0, 0.15) !important;
    }
    #sidebar-wrapper.sidebar-theme-Orange .sidebar-submenu .list-group-item {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    #sidebar-wrapper.sidebar-theme-Orange .sidebar-submenu .list-group-item:hover {
        color: #ffffff !important;
    }
    #sidebar-wrapper.sidebar-theme-Orange .sidebar-user-info {
        border-top: 1px solid rgba(255,255,255,0.08) !important;
    }
    #sidebar-wrapper.sidebar-theme-Orange .sidebar-user-info div {
        color: #ffffff !important;
        text-shadow: none !important;
    }

    /* Purple Theme Override */
    #sidebar-wrapper.sidebar-theme-Purple {
        background: linear-gradient(180deg, #1c0e35 0%, #261347 100%) !important;
    }
    #sidebar-wrapper.sidebar-theme-Purple .sidebar-heading {
        background: rgba(0, 0, 0, 0.15) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
    }
    #sidebar-wrapper.sidebar-theme-Purple .sidebar-section-header {
        color: rgba(255, 255, 255, 0.45) !important;
    }
    #sidebar-wrapper.sidebar-theme-Purple .list-group-item {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    #sidebar-wrapper.sidebar-theme-Purple .list-group-item:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: #ffffff !important;
    }
    #sidebar-wrapper.sidebar-theme-Purple .list-group-item.active {
        background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.2) !important;
    }
    #sidebar-wrapper.sidebar-theme-Purple .sidebar-submenu {
        background-color: rgba(0, 0, 0, 0.15) !important;
    }
    #sidebar-wrapper.sidebar-theme-Purple .sidebar-submenu .list-group-item {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    #sidebar-wrapper.sidebar-theme-Purple .sidebar-submenu .list-group-item:hover {
        color: #ffffff !important;
    }
    #sidebar-wrapper.sidebar-theme-Purple .sidebar-user-info {
        border-top: 1px solid rgba(255,255,255,0.08) !important;
    }
    #sidebar-wrapper.sidebar-theme-Purple .sidebar-user-info div {
        color: #ffffff !important;
        text-shadow: none !important;
    }

    /* Charcoal Theme Override */
    #sidebar-wrapper.sidebar-theme-Charcoal {
        background: linear-gradient(180deg, #1f2937 0%, #111827 100%) !important;
    }
    #sidebar-wrapper.sidebar-theme-Charcoal .sidebar-heading {
        background: rgba(0, 0, 0, 0.15) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
    }
    #sidebar-wrapper.sidebar-theme-Charcoal .sidebar-section-header {
        color: rgba(255, 255, 255, 0.45) !important;
    }
    #sidebar-wrapper.sidebar-theme-Charcoal .list-group-item {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    #sidebar-wrapper.sidebar-theme-Charcoal .list-group-item:hover {
        background-color: rgba(255, 255, 255, 0.08) !important;
        color: #ffffff !important;
    }
    #sidebar-wrapper.sidebar-theme-Charcoal .list-group-item.active {
        background: linear-gradient(135deg, #4b5563 0%, #374151 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(75, 85, 99, 0.2) !important;
    }
    #sidebar-wrapper.sidebar-theme-Charcoal .sidebar-submenu {
        background-color: rgba(0, 0, 0, 0.15) !important;
    }
    #sidebar-wrapper.sidebar-theme-Charcoal .sidebar-submenu .list-group-item {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    #sidebar-wrapper.sidebar-theme-Charcoal .sidebar-submenu .list-group-item:hover {
        color: #ffffff !important;
    }
    #sidebar-wrapper.sidebar-theme-Charcoal .sidebar-user-info {
        border-top: 1px solid rgba(255,255,255,0.08) !important;
    }
    #sidebar-wrapper.sidebar-theme-Charcoal .sidebar-user-info div {
        color: #ffffff !important;
        text-shadow: none !important;
    }

    /* Neon Theme Override */
    #sidebar-wrapper.sidebar-theme-Neon {
        background: linear-gradient(180deg, #09090b 0%, #18181b 100%) !important;
        border-right: 1px solid rgba(236, 72, 153, 0.15) !important;
    }
    #sidebar-wrapper.sidebar-theme-Neon .sidebar-heading {
        background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    #sidebar-wrapper.sidebar-theme-Neon .sidebar-section-header {
        color: rgba(236, 72, 153, 0.6) !important;
    }
    #sidebar-wrapper.sidebar-theme-Neon .list-group-item {
        color: rgba(255, 255, 255, 0.65) !important;
    }
    #sidebar-wrapper.sidebar-theme-Neon .list-group-item:hover {
        background-color: rgba(236, 72, 153, 0.08) !important;
        color: #ec4899 !important;
    }
    #sidebar-wrapper.sidebar-theme-Neon .list-group-item.active {
        background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(236, 72, 153, 0.4) !important;
    }
    #sidebar-wrapper.sidebar-theme-Neon .sidebar-submenu {
        background-color: rgba(0, 0, 0, 0.3) !important;
    }
    #sidebar-wrapper.sidebar-theme-Neon .sidebar-submenu .list-group-item {
        color: rgba(255, 255, 255, 0.5) !important;
    }
    #sidebar-wrapper.sidebar-theme-Neon .sidebar-submenu .list-group-item:hover {
        color: #ec4899 !important;
    }
    #sidebar-wrapper.sidebar-theme-Neon .sidebar-user-info {
        border-top: 1px solid rgba(236, 72, 153, 0.2) !important;
    }
    #sidebar-wrapper.sidebar-theme-Neon .sidebar-user-info div {
        color: #ffffff !important;
        text-shadow: 0 0 10px rgba(236, 72, 153, 0.5) !important;
    }

    /* Gold Theme Override */
    #sidebar-wrapper.sidebar-theme-Gold {
        background: linear-gradient(180deg, #1c1917 0%, #292524 100%) !important;
    }
    #sidebar-wrapper.sidebar-theme-Gold .sidebar-heading {
        background: rgba(0, 0, 0, 0.2) !important;
        border-bottom: 1px solid rgba(250, 204, 21, 0.15) !important;
    }
    #sidebar-wrapper.sidebar-theme-Gold .sidebar-section-header {
        color: rgba(250, 204, 21, 0.5) !important;
    }
    #sidebar-wrapper.sidebar-theme-Gold .list-group-item {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    #sidebar-wrapper.sidebar-theme-Gold .list-group-item:hover {
        background-color: rgba(250, 204, 21, 0.08) !important;
        color: #facc15 !important;
    }
    #sidebar-wrapper.sidebar-theme-Gold .list-group-item.active {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(217, 119, 6, 0.2) !important;
    }
    #sidebar-wrapper.sidebar-theme-Gold .sidebar-submenu {
        background-color: rgba(0, 0, 0, 0.2) !important;
    }
    #sidebar-wrapper.sidebar-theme-Gold .sidebar-submenu .list-group-item {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    #sidebar-wrapper.sidebar-theme-Gold .sidebar-submenu .list-group-item:hover {
        color: #facc15 !important;
    }
    #sidebar-wrapper.sidebar-theme-Gold .sidebar-user-info {
        border-top: 1px solid rgba(250,204,21,0.15) !important;
    }
    #sidebar-wrapper.sidebar-theme-Gold .sidebar-user-info div {
        color: #ffffff !important;
        text-shadow: none !important;
    }

    /* Sakura Theme Override */
    #sidebar-wrapper.sidebar-theme-Sakura {
        background: linear-gradient(180deg, #2e151b 0%, #3e1b23 100%) !important;
    }
    #sidebar-wrapper.sidebar-theme-Sakura .sidebar-heading {
        background: rgba(0, 0, 0, 0.15) !important;
        border-bottom: 1px solid rgba(244, 63, 94, 0.15) !important;
    }
    #sidebar-wrapper.sidebar-theme-Sakura .sidebar-section-header {
        color: rgba(244, 63, 94, 0.5) !important;
    }
    #sidebar-wrapper.sidebar-theme-Sakura .list-group-item {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    #sidebar-wrapper.sidebar-theme-Sakura .list-group-item:hover {
        background-color: rgba(244, 63, 94, 0.08) !important;
        color: #fda4af !important;
    }
    #sidebar-wrapper.sidebar-theme-Sakura .list-group-item.active {
        background: linear-gradient(135deg, #fda4af 0%, #f43f5e 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(244, 63, 94, 0.2) !important;
    }
    #sidebar-wrapper.sidebar-theme-Sakura .sidebar-submenu {
        background-color: rgba(0, 0, 0, 0.2) !important;
    }
    #sidebar-wrapper.sidebar-theme-Sakura .sidebar-submenu .list-group-item {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    #sidebar-wrapper.sidebar-theme-Sakura .sidebar-submenu .list-group-item:hover {
        color: #fda4af !important;
    }
    #sidebar-wrapper.sidebar-theme-Sakura .sidebar-user-info {
        border-top: 1px solid rgba(244,63,94,0.15) !important;
    }
    #sidebar-wrapper.sidebar-theme-Sakura .sidebar-user-info div {
        color: #ffffff !important;
        text-shadow: none !important;
    }
    
    /* Active menu icon overrides to ensure perfect contrast and premium readability */
    .list-group-item.active i {
        color: #ffffff !important;
        text-shadow: 0 2px 4px rgba(0,0,0,0.15) !important;
    }
</style>

<div id="sidebar-wrapper" class="sidebar-theme-{{ $theme }}">
    <div class="sidebar-heading">
        <div class="sidebar-logo d-flex align-items-center justify-content-center" style="overflow: hidden; {{ config('app.show_sidebar_logo', '1') == '0' ? 'display: none !important;' : '' }}">
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
            <i class="bi bi-grid-1x2" style="color: #38bdf8;"></i> <span>Dashboard</span>
        </a>
        
        @role('admin')
        <div class="sidebar-section-header">Assets & Items</div>
        
        <a class="list-group-item {{ request()->routeIs('assets.*') ? 'active' : '' }}" href="{{ route('assets.index') }}">
            <i class="bi bi-box-seam" style="color: #f87171;"></i> <span>Fixed Assets</span>
        </a>
        
        <a class="list-group-item d-flex justify-content-between align-items-center {{ request()->routeIs('consumables.*') ? 'active' : '' }}" 
           href="#consumablesSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('consumables.*') ? 'true' : 'false' }}">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-basket" style="color: #4ade80;"></i> <span>Consumables</span>
            </div>
            <i class="bi bi-chevron-down small collapse-arrow"></i>
        </a>
        <div class="collapse {{ request()->routeIs('consumables.*') ? 'show' : '' }}" id="consumablesSubmenu">
            <div class="sidebar-submenu">
                <a class="list-group-item border-0 py-2 {{ request()->routeIs('consumables.index') ? 'active' : '' }}" href="{{ route('consumables.index') }}">
                    <i class="bi bi-circle-fill" style="font-size: 6px; color: #4ade80;"></i> Stock Consumables
                </a>
                <a class="list-group-item border-0 py-2 {{ request()->routeIs('consumables.transactions.in') ? 'active' : '' }}" href="{{ route('consumables.transactions.in') }}">
                    <i class="bi bi-box-arrow-in-down text-success"></i> Barang Masuk
                </a>
                <a class="list-group-item border-0 py-2 {{ request()->routeIs('consumables.transactions.out') ? 'active' : '' }}" href="{{ route('consumables.transactions.out') }}">
                    <i class="bi bi-box-arrow-up text-danger"></i> Barang Keluar
                </a>
            </div>
        </div>

        <a class="list-group-item {{ request()->routeIs('stock-opnames.*') ? 'active' : '' }}" href="{{ route('stock-opnames.index') }}">
            <i class="bi bi-clipboard-data" style="color: #fbbf24;"></i> <span>Stock Opname</span>
        </a>

        <div class="sidebar-section-header">Master Data</div>
        
        <a class="list-group-item {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
            <i class="bi bi-tags" style="color: #818cf8;"></i> <span>Categories</span>
        </a>
        <a class="list-group-item {{ request()->routeIs('locations.*') ? 'active' : '' }}" href="{{ route('locations.index') }}">
            <i class="bi bi-geo-alt" style="color: #fb7185;"></i> <span>Locations</span>
        </a>
        <a class="list-group-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
            <i class="bi bi-truck" style="color: #2dd4bf;"></i> <span>Suppliers</span>
        </a>

        <div class="sidebar-section-header">EHS Management</div>
        <a class="list-group-item {{ request()->routeIs('admin.requests.*') ? 'active' : '' }}" href="{{ route('admin.requests.index') }}">
            <i class="bi bi-card-checklist" style="color: #fda4af;"></i> <span>Manage Requests</span>
        </a>
        <a class="list-group-item {{ request()->routeIs('admin.damage_reports.*') ? 'active' : '' }}" href="{{ route('admin.damage_reports.index') }}">
            <i class="bi bi-shield-exclamation" style="color: #fb7185;"></i> <span>Manage Damage Reports</span>
        </a>

        <div class="sidebar-section-header">Administration</div>
        
        <a class="list-group-item {{ request()->routeIs('admin.configs.*') ? 'active' : '' }}" href="{{ route('admin.configs.index') }}">
            <i class="bi bi-gear" style="color: #a78bfa;"></i> <span>Config Master</span>
        </a>
        <a class="list-group-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
            <i class="bi bi-people" style="color: #60a5fa;"></i> <span>Users</span>
        </a>
        <a class="list-group-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
            <i class="bi bi-shield-lock" style="color: #fca5a5;"></i> <span>Roles</span>
        </a>
        <a class="list-group-item {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}" href="{{ route('admin.logs.index') }}">
            <i class="bi bi-clock-history" style="color: #94a3b8;"></i> <span>Activity Logs</span>
        </a>
        @else
        <div class="sidebar-section-header">EHS Request Portals</div>
        
        <a class="list-group-item {{ request()->routeIs('staff.requests.*') ? 'active' : '' }}" href="{{ route('staff.requests.index') }}">
            <i class="bi bi-patch-question" style="color: #38bdf8;"></i> <span>Pinjam Alat & APD</span>
        </a>
        
        <a class="list-group-item {{ request()->routeIs('staff.damage_reports.*') ? 'active' : '' }}" href="{{ route('staff.damage_reports.index') }}">
            <i class="bi bi-shield-fill-exclamation" style="color: #f87171;"></i> <span>Lapor Kerusakan Alat</span>
        </a>
        @endrole
    </div>
    
    <div class="sidebar-user-info mt-auto text-center" style="border-top: 1px solid rgba(255,255,255,0.06); padding: 1.25rem 1rem 1.75rem 1rem;">
        <div style="font-size: 0.85rem; color: #E74C3C; letter-spacing: 1.5px; font-weight: 800; text-transform: uppercase; text-shadow: 1px 1px 0px #922B21, 2px 2px 0px #7B241C, 3px 3px 4px rgba(0,0,0,0.7); display: inline-block; margin-bottom: 2px;">
            {{ config('app.name', 'HSE Asset Management') }}
        </div>
        <div style="font-size: 0.65rem; color: #ffffff; margin-top: 4px; font-weight: 700; letter-spacing: 2px; opacity: 0.65; text-shadow: 1px 1px 2px rgba(0,0,0,0.9);">
            VERSION {{ config('app.app_version', '1.0.0') }}
        </div>
    </div>
</div>
