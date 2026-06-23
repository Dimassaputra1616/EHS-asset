@extends('layouts.app')

@section('title', 'Manage EHS Requests')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 mt-3">
    <div>
        <h4 class="mb-0 fw-bold text-dark"><i class="bi bi-card-checklist me-2 text-hse-red"></i> Kelola Pengajuan Staff</h4>
        <small class="text-muted">Setujui, tolak, atau catat pengembalian alat keselamatan (Fixed Assets) dan APD (Consumables)</small>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="requests-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Karyawan</th>
                        <th width="12%">Jenis</th>
                        <th width="18%">Nama Barang</th>
                        <th width="8%">Qty</th>
                        <th width="15%">Tujuan Penggunaan</th>
                        <th width="12%">Status</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $index => $req)
                        <tr id="req-row-{{ $req->id }}">
                            <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $req->user->name }}</div>
                                <div class="small text-muted">{{ $req->user->email }}</div>
                            </td>
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
                            <td>
                                <span class="small text-secondary">{{ $req->purpose }}</span>
                            </td>
                            <td id="status-badge-{{ $req->id }}">{!! $req->status_badge !!}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-outline-dark fw-bold px-3 py-1.5 rounded-3 d-inline-flex align-items-center gap-1" 
                                            onclick="openUpdateModal({{ $req->id }}, '{{ $req->status }}', '{{ addslashes($req->admin_notes) }}')">
                                        <i class="bi bi-pencil-square"></i> Proses
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger fw-bold px-3 py-1.5 rounded-3 d-inline-flex align-items-center gap-1" 
                                            onclick="confirmDelete({{ $req->id }})">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted mb-2">
                                    <i class="bi bi-inbox fs-1 d-block opacity-40"></i>
                                </div>
                                <h6 class="fw-bold text-secondary">Belum ada pengajuan masuk</h6>
                                <p class="text-muted small mb-0">Semua pengajuan alat dari staff akan muncul secara realtime di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Update Status -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold" id="modal-title"><i class="bi bi-arrow-right-circle text-primary me-2"></i> Proses Pengajuan Alat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="update-status-form" onsubmit="submitStatusUpdate(event)">
                @csrf
                <input type="hidden" id="modal-req-id">
                
                <div class="modal-body p-4">
                    <!-- Status Options -->
                    <div class="mb-3">
                        <label for="modal-status-select" class="form-label fw-bold text-dark mb-2">Tentukan Status Pengajuan:</label>
                        <select class="form-select border-secondary border-opacity-15 p-2.5 rounded-3" id="modal-status-select" required>
                            <option value="pending">Pending (Menunggu)</option>
                            <option value="approved">Approve (Disetujui / Diserahkan)</option>
                            <option value="rejected">Reject (Ditolak)</option>
                            <option value="returned">Returned (Sudah Dikembalikan - Khusus Fixed Asset)</option>
                        </select>
                    </div>

                    <!-- Catatan Admin -->
                    <div class="mb-0">
                        <label for="modal-notes" class="form-label fw-bold text-dark mb-2">Catatan Admin / Keterangan Penolakan:</label>
                        <textarea class="form-control border-secondary border-opacity-15 p-3 rounded-3" id="modal-notes" rows="3" placeholder="Tulis catatan atau alasan di sini..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer border-top py-3 bg-light bg-opacity-50">
                    <button type="button" class="btn btn-secondary fw-bold px-3 py-2 rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-3 py-2 rounded-3">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var updateModal;

    $(function() {
        updateModal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
    });

    function openUpdateModal(id, currentStatus, notes) {
        $('#modal-req-id').val(id);
        $('#modal-status-select').val(currentStatus);
        $('#modal-notes').val(notes);
        
        updateModal.show();
    }

    function submitStatusUpdate(e) {
        e.preventDefault();
        
        var id = $('#modal-req-id').val();
        var status = $('#modal-status-select').val();
        var notes = $('#modal-notes').val();
        
        $.ajax({
            url: "/admin/requests/" + id + "/status",
            type: "PUT",
            data: {
                _token: "{{ csrf_token() }}",
                status: status,
                admin_notes: notes
            },
            success: function(response) {
                if(response.success) {
                    updateModal.hide();
                    
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        customClass: { popup: 'rounded-4' }
                    });
                    
                    // Reload table dynamically or reload page
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Gagal memperbarui status pengajuan.',
                    icon: 'error',
                    confirmButtonColor: '#dc3545',
                    customClass: { popup: 'rounded-4' }
                });
            }
        });
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Pengajuan ini akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-4' }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/admin/requests/" + id,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if(response.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false,
                                customClass: { popup: 'rounded-4' }
                            });
                            
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal menghapus pengajuan.',
                            icon: 'error',
                            confirmButtonColor: '#dc3545',
                            customClass: { popup: 'rounded-4' }
                        });
                    }
                });
            }
        });
    }
</script>
@endsection
