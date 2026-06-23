@extends('layouts.app')

@section('title', 'Pinjam Alat & APD')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 mt-3">
    <div>
        <h4 class="mb-0 fw-bold text-dark"><i class="bi bi-patch-question me-2 text-primary"></i> Pengajuan Alat & APD</h4>
        <small class="text-muted">Ajukan peminjaman alat keselamatan (Fixed Assets) atau permintaan APD baru (Consumables)</small>
    </div>
    <div>
        <a href="{{ route('staff.requests.create') }}" class="btn btn-primary fw-bold shadow-sm d-flex align-items-center gap-1 px-3 py-2" style="border-radius: 10px;">
            <i class="bi bi-plus-lg"></i> Buat Pengajuan
        </a>
    </div>
</div>

@if(session('success'))
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#0d6efd',
                customClass: { popup: 'rounded-4' }
            });
        });
    </script>
@endif

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tanggal Pengajuan</th>
                        <th width="15%">Jenis Barang</th>
                        <th width="20%">Nama Barang</th>
                        <th width="10%">Jumlah</th>
                        <th width="15%">Status</th>
                        <th width="20%">Catatan Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $index => $req)
                        <tr>
                            <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                            <td class="font-monospace text-muted">{{ $req->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                @if($req->request_type === 'fixed_asset')
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-10 px-2.5 py-1.5 fw-semibold rounded-pill">
                                        <i class="bi bi-box-seam me-1"></i> Fixed Asset
                                    </span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10 px-2.5 py-1.5 fw-semibold rounded-pill">
                                        <i class="bi bi-basket me-1"></i> Consumables
                                    </span>
                                @endif
                            </td>
                            <td class="fw-bold text-dark">
                                @if($req->request_type === 'fixed_asset')
                                    {{ $req->asset ? $req->asset->name : 'Asset dihapus' }}
                                    <div class="small text-muted font-monospace mt-0.5">{{ $req->asset ? $req->asset->code : '-' }}</div>
                                @else
                                    {{ $req->consumable ? $req->consumable->name : 'Consumable dihapus' }}
                                    <div class="small text-muted font-monospace mt-0.5">{{ $req->consumable ? $req->consumable->code : '-' }}</div>
                                @endif
                            </td>
                            <td><span class="fw-bold text-primary">{{ $req->qty }}</span> Pcs</td>
                            <td>{!! $req->status_badge !!}</td>
                            <td>
                                <span class="text-secondary small">{{ $req->admin_notes ?: '-' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted mb-2">
                                    <i class="bi bi-inbox fs-1 d-block opacity-40"></i>
                                </div>
                                <h6 class="fw-bold text-secondary">Belum ada pengajuan alat</h6>
                                <p class="text-muted small mb-0">Klik tombol "Buat Pengajuan" di atas untuk mengajukan APD atau Alat Keselamatan baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
