@extends('layouts.app')

@section('title', 'Add Role')

@push('css')
<style>
    .pwa-permission-check {
        border-color: #e2e8f0 !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .pwa-permission-check:hover {
        border-color: var(--hse-red, #C0392B) !important;
        background-color: rgba(192, 57, 43, 0.01) !important;
        transform: translateY(-1.5px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.03) !important;
    }
    .pwa-permission-check:has(.form-check-input:checked) {
        border-color: var(--hse-red, #C0392B) !important;
        background-color: rgba(192, 57, 43, 0.03) !important;
    }
</style>
@endpush

@section('content')
@php
    // Mapping permission key to [Indonesian Label, Description/Hint, Icon]
    $permissionMap = [
        // Fixed Assets Group
        'assets.view' => ['Lihat Aset Tetap', 'Melihat daftar dan rincian data aset tetap (Fixed Assets)', 'bi-eye-fill'],
        'assets.create' => ['Tambah Aset Tetap', 'Menambah data aset tetap baru ke dalam sistem', 'bi-plus-circle-fill'],
        'assets.edit' => ['Ubah Aset Tetap', 'Mengedit rincian data aset tetap yang sudah ada', 'bi-pencil-square'],
        'assets.delete' => ['Hapus Aset Tetap', 'Menghapus data aset tetap dari sistem', 'bi-trash-fill'],
        
        // Consumables Group
        'consumables.view' => ['Lihat Bahan Habis Pakai', 'Melihat daftar stok bahan habis pakai (Consumables)', 'bi-eye-fill'],
        'consumables.create' => ['Tambah Bahan Habis Pakai', 'Menambah item bahan habis pakai baru', 'bi-plus-circle-fill'],
        'consumables.edit' => ['Ubah Bahan Habis Pakai', 'Mengubah rincian item bahan habis pakai', 'bi-pencil-square'],
        'consumables.delete' => ['Hapus Bahan Habis Pakai', 'Menghapus item bahan habis pakai', 'bi-trash-fill'],
        
        // Requests Group
        'requests.view' => ['Lihat Permintaan Pinjam', 'Melihat riwayat dan status permintaan peminjaman staf', 'bi-file-earmark-text-fill'],
        'requests.create' => ['Buat Permintaan Pinjam', 'Mengajukan permintaan peminjaman aset baru', 'bi-file-earmark-plus-fill'],
        'requests.manage' => ['Kelola Permintaan Pinjam', 'Persetujuan (Approve/Reject) permintaan pinjam staf', 'bi-patch-check-fill'],
        
        // Damage Reports Group
        'damage_reports.view' => ['Lihat Laporan Kerusakan', 'Melihat daftar laporan kerusakan aset', 'bi-exclamation-triangle-fill'],
        'damage_reports.create' => ['Buat Laporan Kerusakan', 'Melaporkan kerusakan pada aset yang dipinjam', 'bi-clipboard-pulse'],
        'damage_reports.manage' => ['Kelola Laporan Kerusakan', 'Memproses dan memperbarui status laporan kerusakan', 'bi-wrench-adjustable-circle-fill'],
        
        // System Settings Group
        'master.manage' => ['Kelola Data Master', 'Kelola data pendukung (Kategori, Lokasi, & Supplier)', 'bi-database-fill-gear'],
        'users.manage' => ['Kelola Pengguna', 'Menambah, mengedit, dan menghapus akun pengguna (staf/admin)', 'bi-people-fill'],
        'roles.manage' => ['Kelola Peran & Hak Akses', 'Mengatur peran (Roles) dan hak akses (Permissions) sistem', 'bi-shield-lock-fill'],
        'config.manage' => ['Kelola Konfigurasi', 'Mengubah logo, warna tema, background login, dan versi aplikasi', 'bi-sliders'],
        'logs.view' => ['Lihat Log Aktivitas', 'Melihat riwayat aktivitas dan log sistem dari admin/staff', 'bi-clock-history'],
    ];

    // Define grouping structure
    $groupedPermissions = [
        'Aset Tetap (Fixed Assets)' => ['assets.view', 'assets.create', 'assets.edit', 'assets.delete'],
        'Bahan Habis Pakai (Consumables)' => ['consumables.view', 'consumables.create', 'consumables.edit', 'consumables.delete'],
        'Alur Permintaan Pinjam' => ['requests.view', 'requests.create', 'requests.manage'],
        'Laporan Kerusakan Aset' => ['damage_reports.view', 'damage_reports.create', 'damage_reports.manage'],
        'Pengaturan Sistem & Admin' => ['master.manage', 'users.manage', 'roles.manage', 'config.manage', 'logs.view']
    ];
@endphp

<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-11">
        <div class="card border-0 shadow-sm mt-3 animate-fade-in" style="border-radius: 20px; overflow: hidden;">
            <div class="card-header bg-white py-4 border-bottom-0 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="text-white p-2 rounded-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: var(--hse-red-gradient);">
                        <i class="bi bi-shield-lock fs-4"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fw-bold" style="font-size: 1.2rem; color: #1e293b;">Add New Role</h5>
                        <p class="text-muted small mb-0">Create a new organizational role</p>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4 pt-2">
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label fw-bold text-dark">Role Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-lg fs-6 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. manager, staff" required style="border-radius: 12px; background: #f8fafc; border: 1.5px solid #e2e8f0;">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-dark mb-1">Permissions <span class="text-danger">*</span></label>
                            <p class="text-muted small mb-3">Tentukan hak akses spesifik yang diberikan untuk peran ini.</p>
                            
                            @if(count($permissions) > 0)
                                <div class="row g-4">
                                    @foreach($groupedPermissions as $groupTitle => $keys)
                                        @php
                                            $groupPerms = $permissions->filter(function($p) use ($keys) {
                                                return in_array($p->name, $keys);
                                            });
                                        @endphp
                                        
                                        @if($groupPerms->count() > 0)
                                            <div class="col-12">
                                                <div class="p-3 border-0 bg-light bg-opacity-40" style="border-radius: 16px; background-color: #f8fafc;">
                                                    <h6 class="fw-bold mb-3 text-dark d-flex align-items-center" style="font-size: 0.9rem; letter-spacing: 0.3px;">
                                                        <span class="badge bg-danger bg-opacity-10 text-danger me-2.5 px-2.5 py-2" style="border-radius: 8px;"><i class="bi bi-folder2-open fs-6"></i></span>
                                                        {{ $groupTitle }}
                                                    </h6>
                                                    
                                                    <div class="row g-3">
                                                        @foreach($groupPerms as $permission)
                                                            @php
                                                                $map = $permissionMap[$permission->name] ?? [$permission->name, 'Hak akses sistem', 'bi-circle'];
                                                            @endphp
                                                            <div class="col-md-6 col-lg-4">
                                                                <div class="form-check pwa-permission-check border bg-white p-3 rounded-3 h-100 d-flex align-items-start gap-2.5" style="cursor: pointer; border-radius: 12px !important;">
                                                                    <input class="form-check-input mt-1" type="checkbox" name="permission[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}" style="cursor: pointer; width: 1.15rem; height: 1.15rem;">
                                                                    <label class="form-check-label w-100 ms-1" for="perm_{{ $permission->id }}" style="cursor: pointer;">
                                                                        <div class="d-flex align-items-center gap-1.5 fw-bold text-dark" style="font-size: 0.88rem;">
                                                                            <i class="bi {{ $map[2] }} text-secondary"></i>
                                                                            {{ $map[0] }}
                                                                        </div>
                                                                        <div class="text-muted small mt-1.5" style="font-size: 0.72rem; line-height: 1.35;">
                                                                            {{ $map[1] }}
                                                                        </div>
                                                                        <div class="mt-2.5 font-monospace text-secondary" style="font-size: 0.65rem; opacity: 0.7;">
                                                                            key: {{ $permission->name }}
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-light border mt-2">
                                    <i class="bi bi-info-circle me-2 text-primary"></i> No permissions are available in the system. Just assigning by name.
                                </div>
                            @endif
                            @error('permission') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <hr class="my-4 border-light">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-light fw-bold px-4" style="border-radius: 12px; padding: 0.65rem 1.5rem;">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                        <button type="submit" class="btn btn-hse-red fw-bold px-5" style="border-radius: 12px; padding: 0.65rem 2rem;">
                            <i class="bi bi-check2-circle me-1"></i> Save Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
