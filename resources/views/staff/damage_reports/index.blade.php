@extends('layouts.app')

@section('title', 'Lapor Kerusakan Alat')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 mt-3">
    <div>
        <h4 class="mb-0 fw-bold text-dark"><i class="bi bi-shield-fill-exclamation me-2 text-danger"></i> Laporan Kerusakan & Temuan</h4>
        <small class="text-muted">Laporkan kerusakan alat pelindung diri (APD) atau kerusakan fasilitas keselamatan kerja di lapangan</small>
    </div>
    <div>
        <a href="{{ route('staff.damage_reports.create') }}" class="btn btn-danger fw-bold shadow-sm d-flex align-items-center gap-1 px-3 py-2" style="border-radius: 10px; background-color: #dc3545; border-color: #dc3545;">
            <i class="bi bi-megaphone-fill"></i> Laporkan Temuan
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
                confirmButtonColor: '#dc3545',
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
                        <th width="15%">Tanggal Lapor</th>
                        <th width="12%">Foto Alat</th>
                        <th width="18%">Nama Barang</th>
                        <th width="12%">Urgency</th>
                        <th width="12%">Status</th>
                        <th width="26%">Deskripsi Kerusakan / Tanggapan Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $index => $rep)
                        <tr>
                            <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                            <td class="font-monospace text-muted">{{ $rep->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                @if($rep->photo)
                                    <a href="{{ Storage::url($rep->photo) }}" target="_blank">
                                        <img src="{{ Storage::url($rep->photo) }}" alt="Foto" class="rounded-3 border border-secondary border-opacity-15 shadow-sm" style="width: 58px; height: 58px; object-fit: cover;">
                                    </a>
                                @else
                                    <div class="rounded-3 bg-light border border-secondary border-opacity-10 d-flex align-items-center justify-content-center text-muted" style="width: 58px; height: 58px;">
                                        <i class="bi bi-image" style="font-size: 1.1rem;"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="fw-bold text-dark">
                                {{ $rep->item_name }}
                            </td>
                            <td>{!! $rep->urgency_badge !!}</td>
                            <td>{!! $rep->status_badge !!}</td>
                            <td>
                                <div class="text-dark fw-medium small mb-1" style="max-width: 320px;">
                                    <span class="fw-bold">Temuan:</span> {{ $rep->description }}
                                </div>
                                @if($rep->admin_notes)
                                    <div class="bg-light p-2 rounded-3 border-start border-danger border-3 mt-1.5 small" style="max-width: 320px;">
                                        <span class="fw-bold text-danger">Tanggapan Admin:</span> {{ $rep->admin_notes }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted mb-2">
                                    <i class="bi bi-clipboard-x fs-1 d-block opacity-40"></i>
                                </div>
                                <h6 class="fw-bold text-secondary">Belum ada laporan kerusakan</h6>
                                <p class="text-muted small mb-0">Klik tombol "Laporkan Temuan" untuk membuat laporan jika ada APD atau alat keselamatan yang rusak.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
