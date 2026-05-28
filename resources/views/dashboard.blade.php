@extends('layouts.app')

@section('title', 'Dashboard Overview')

@section('content')
<!-- Welcome Banner -->
<div class="welcome-banner mb-4">
    <div class="welcome-text position-relative" style="z-index: 2;">
        <h2>Hello, {{ Auth::user()->name }} 👋</h2>
        <p>Here's what's happening with your HSE assets today: {{ now()->format('l, j F Y') }}</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Stat Cards -->
    <div class="col-xxl-3 col-xl-6 col-md-6">
        <a href="/assets" class="text-decoration-none d-block">
            <div class="stat-card" style="background: linear-gradient(135deg, #E74C3C 0%, #C0392B 100%); cursor: pointer;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ \App\Models\Asset::count() }}</div>
                        <div class="stat-label mt-2">Total Fixed Assets</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-xxl-3 col-xl-6 col-md-6">
        <a href="/consumables" class="text-decoration-none d-block">
            <div class="stat-card" style="background: linear-gradient(135deg, #C0392B 0%, #922B21 100%); cursor: pointer;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ \App\Models\Consumable::count() }}</div>
                        <div class="stat-label mt-2">Consumable Items</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-basket"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xxl-3 col-xl-6 col-md-6">
        <a href="/consumables?low_stock=1" class="text-decoration-none d-block">
            <div class="stat-card" style="background: linear-gradient(135deg, #922B21 0%, #641E16 100%); cursor: pointer;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ \App\Models\Consumable::where('stock', '<=', (int) config('app.low_stock_threshold', 10))->count() }}</div>
                        <div class="stat-label mt-2">Low Stock Alerts</div>
                    </div>
                    <div class="stat-icon text-white">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xxl-3 col-xl-6 col-md-6">
        @role('admin')
        <a href="/admin/users" class="text-decoration-none d-block">
        @else
        <div class="d-block">
        @endrole
            <div class="stat-card" style="background: linear-gradient(135deg, #A93226 0%, #7B241C 100%); cursor: {{ Auth::user()->hasRole('admin') ? 'pointer' : 'default' }};">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ \App\Models\User::count() }}</div>
                        <div class="stat-label mt-2">Active Users</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        @role('admin')
        </a>
        @else
        </div>
        @endrole
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-lightning-charge-fill text-warning"></i>
            <span class="fw-bold text-dark">Quick Actions</span>
        </div>
    </div>
    <div class="col-md-4">
        <a href="{{ route('assets.create') }}" class="text-decoration-none d-block">
            <div class="card border-0 shadow-sm h-100 quick-action-card">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: linear-gradient(135deg, #E74C3C, #C0392B);">
                        <i class="bi bi-plus-lg text-white fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark">Add New Asset</div>
                        <small class="text-muted">Register fixed asset</small>
                    </div>
                    <i class="bi bi-chevron-right text-muted ms-auto"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('consumables.transactions.create', ['type' => 'in']) }}" class="text-decoration-none d-block">
            <div class="card border-0 shadow-sm h-100 quick-action-card">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: linear-gradient(135deg, #27ae60, #2ecc71);">
                        <i class="bi bi-box-arrow-in-down text-white fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark">Stock In</div>
                        <small class="text-muted">Record incoming goods</small>
                    </div>
                    <i class="bi bi-chevron-right text-muted ms-auto"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('consumables.transactions.create', ['type' => 'out']) }}" class="text-decoration-none d-block">
            <div class="card border-0 shadow-sm h-100 quick-action-card">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: linear-gradient(135deg, #e67e22, #d35400);">
                        <i class="bi bi-box-arrow-up text-white fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark">Stock Out</div>
                        <small class="text-muted">Record outgoing goods</small>
                    </div>
                    <i class="bi bi-chevron-right text-muted ms-auto"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Transactions -->
    <div class="col-xxl-8 col-xl-12">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-arrow-left-right text-hse-red"></i>
                    <span class="fw-bold">Transaksi Terakhir</span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('consumables.transactions.in') }}" class="btn btn-sm btn-outline-success border-0">
                        <i class="bi bi-box-arrow-in-down me-1"></i>Masuk
                    </a>
                    <a href="{{ route('consumables.transactions.out') }}" class="btn btn-sm btn-outline-danger border-0">
                        <i class="bi bi-box-arrow-up me-1"></i>Keluar
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 fw-semibold text-muted small text-uppercase" style="letter-spacing: 0.5px;">Type</th>
                                <th class="fw-semibold text-muted small text-uppercase" style="letter-spacing: 0.5px;">Item</th>
                                <th class="fw-semibold text-muted small text-uppercase" style="letter-spacing: 0.5px;">Qty</th>
                                <th class="fw-semibold text-muted small text-uppercase" style="letter-spacing: 0.5px;">Operator</th>
                                <th class="pe-4 fw-semibold text-muted small text-uppercase" style="letter-spacing: 0.5px;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\ConsumableTransaction::with(['consumable', 'user'])->latest()->take(7)->get() as $trx)
                            <tr>
                                <td class="ps-4">
                                    @if($trx->type === 'in')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                            <i class="bi bi-arrow-down-circle me-1"></i>Masuk
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">
                                            <i class="bi bi-arrow-up-circle me-1"></i>Keluar
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-2 d-flex align-items-center justify-content-center {{ $trx->type === 'in' ? 'bg-success' : 'bg-danger' }} bg-opacity-10" style="width: 32px; height: 32px;">
                                            <i class="bi bi-basket {{ $trx->type === 'in' ? 'text-success' : 'text-danger' }}" style="font-size: 14px;"></i>
                                        </div>
                                        <span class="fw-medium text-dark">{{ $trx->consumable ? $trx->consumable->name : '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold {{ $trx->type === 'in' ? 'text-success' : 'text-danger' }}">
                                        {{ $trx->type === 'in' ? '+' : '-' }}{{ $trx->quantity }}
                                    </span>
                                    <span class="text-muted small">{{ $trx->consumable ? $trx->consumable->unit : '' }}</span>
                                </td>
                                <td class="text-muted small">{{ $trx->user ? $trx->user->name : 'System' }}</td>
                                <td class="pe-4 text-muted small">{{ $trx->date->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div class="text-center py-5">
                                        <div class="mb-3">
                                            <i class="bi bi-arrow-left-right text-muted" style="font-size: 2.5rem; opacity: 0.3;"></i>
                                        </div>
                                        <h6 class="text-muted mb-1">Belum ada transaksi</h6>
                                        <p class="small text-muted mb-3">Mulai catat barang masuk atau keluar untuk melihat riwayat di sini.</p>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('consumables.transactions.create', ['type' => 'in']) }}" class="btn btn-sm btn-success">
                                                <i class="bi bi-plus-lg me-1"></i>Stock In
                                            </a>
                                            <a href="{{ route('consumables.transactions.create', ['type' => 'out']) }}" class="btn btn-sm btn-danger">
                                                <i class="bi bi-plus-lg me-1"></i>Stock Out
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Low Stock Items -->
    <div class="col-xxl-4 col-xl-12">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                    <span class="fw-bold">Low Stock Items</span>
                </div>
                <a href="/consumables?low_stock=1" class="btn btn-sm btn-outline-danger border-0">View All</a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @php $lowThreshold = (int) config('app.low_stock_threshold', 10); @endphp
                    @forelse(\App\Models\Consumable::where('stock', '<=', $lowThreshold)->latest()->take(5)->get() as $item)
                    <li class="list-group-item px-4 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-2 d-flex align-items-center justify-content-center bg-danger bg-opacity-10" style="width: 36px; height: 36px;">
                                    <i class="bi bi-exclamation-lg text-danger"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $item->name }}</div>
                                    <small class="text-muted">Min: {{ $lowThreshold }} {{ $item->unit }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-danger fs-5">{{ $item->stock }}</div>
                                <small class="text-muted">{{ $item->unit }} left</small>
                            </div>
                        </div>
                        @php $pct = $lowThreshold > 0 ? min(($item->stock / $lowThreshold) * 100, 100) : 0; @endphp
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar {{ $pct <= 30 ? 'bg-danger' : ($pct <= 70 ? 'bg-warning' : 'bg-success') }}" style="width: {{ $pct }}%"></div>
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item border-0">
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                                    <i class="bi bi-check-circle text-success fs-3"></i>
                                </div>
                            </div>
                            <h6 class="text-dark mb-1">All stocks healthy!</h6>
                            <p class="small text-muted mb-0">No items are at or below {{ $lowThreshold }} units.</p>
                        </div>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
