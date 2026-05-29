@extends('layouts.app')

@section('title', 'Manage Damage Reports')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 mt-3">
    <div>
        <h4 class="mb-0 fw-bold text-dark"><i class="bi bi-shield-exclamation me-2 text-hse-red"></i> Laporan Temuan & Kerusakan Alat</h4>
        <small class="text-muted">Pantau laporan kerusakan APD dan fasilitas keselamatan kerja lapangan dari staff</small>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="reports-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Pelapor</th>
                        <th width="12%">Foto Alat</th>
                        <th width="15%">Nama Alat</th>
                        <th width="10%">Urgensi</th>
                        <th width="12%">Status</th>
                        <th width="20%">Deskripsi Temuan / Catatan Perbaikan</th>
                        <th width="11%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $index => $rep)
                        <tr id="rep-row-{{ $rep->id }}">
                            <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $rep->user->name }}</div>
                                <div class="small text-muted font-monospace" style="font-size: 0.75rem;">{{ $rep->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td>
                                @if($rep->photo)
                                    <a href="{{ Storage::url($rep->photo) }}" target="_blank">
                                        <img src="{{ Storage::url($rep->photo) }}" alt="Foto Alat" class="rounded-3 border border-secondary border-opacity-15 shadow-sm" style="width: 58px; height: 58px; object-fit: cover;">
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
                                <div class="small text-dark mb-1" style="max-width: 250px;"><span class="fw-bold">Temuan:</span> {{ $rep->description }}</div>
                                @if($rep->admin_notes)
                                    <div class="bg-light p-2 rounded-3 border-start border-danger border-3 mt-1.5 small" style="max-width: 250px;">
                                        <span class="fw-bold text-danger">Tanggapan EHS:</span> {{ $rep->admin_notes }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-danger fw-bold px-3 py-1.5 rounded-3 d-inline-flex align-items-center gap-1" 
                                        onclick="openUpdateModal({{ $rep->id }}, '{{ $rep->status }}', '{{ addslashes($rep->admin_notes) }}')">
                                    <i class="bi bi-shield-fill-check"></i> Respon
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted mb-2">
                                    <i class="bi bi-shield-check fs-1 d-block opacity-40"></i>
                                </div>
                                <h6 class="fw-bold text-secondary">Belum ada laporan kerusakan</h6>
                                <p class="text-muted small mb-0">Semua laporan temuan keselamatan dari staff lapangan akan muncul di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Update Status -->
<div class="modal fade" id="updateReportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold" id="modal-title"><i class="bi bi-shield-fill-exclamation text-danger me-2"></i> Tindak Lanjuti Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="update-report-form" onsubmit="submitReportUpdate(event)">
                @csrf
                <input type="hidden" id="modal-rep-id">
                
                <div class="modal-body p-4">
                    <!-- Status Options -->
                    <div class="mb-3">
                        <label for="modal-status-select" class="form-label fw-bold text-dark mb-2">Tentukan Status Laporan:</label>
                        <select class="form-select border-secondary border-opacity-15 p-2.5 rounded-3" id="modal-status-select" required>
                            <option value="pending">Pending (Menunggu Tindakan)</option>
                            <option value="investigating">Investigating (Sedang Diperiksa EHS)</option>
                            <option value="resolved">Resolved (Sudah Diperbaiki / Diganti)</option>
                            <option value="closed">Closed (Selesai & Ditutup)</option>
                        </select>
                    </div>

                    <!-- Catatan Perbaikan -->
                    <div class="mb-0">
                        <label for="modal-notes" class="form-label fw-bold text-dark mb-2">Catatan Tindakan EHS / Perbaikan:</label>
                        <textarea class="form-control border-secondary border-opacity-15 p-3 rounded-3" id="modal-notes" rows="3" placeholder="Tulis instruksi perbaikan atau tanggapan untuk pelapor..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer border-top py-3 bg-light bg-opacity-50">
                    <button type="button" class="btn btn-secondary fw-bold px-3 py-2 rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-bold px-3 py-2 rounded-3" style="background-color: #dc3545; border-color: #dc3545;">Kirim Respon</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
    var reportModal;

    $(function() {
        reportModal = new bootstrap.Modal(document.getElementById('updateReportModal'));
    });

    function openUpdateModal(id, currentStatus, notes) {
        $('#modal-rep-id').val(id);
        $('#modal-status-select').val(currentStatus);
        $('#modal-notes').val(notes);
        
        reportModal.show();
    }

    function submitReportUpdate(e) {
        e.preventDefault();
        
        var id = $('#modal-rep-id').val();
        var status = $('#modal-status-select').val();
        var notes = $('#modal-notes').val();
        
        $.ajax({
            url: "/admin/damage-reports/" + id + "/status",
            type: "PUT",
            data: {
                _token: "{{ csrf_token() }}",
                status: status,
                admin_notes: notes
            },
            success: function(response) {
                if(response.success) {
                    reportModal.hide();
                    
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
                    text: 'Gagal memperbarui status laporan.',
                    icon: 'error',
                    confirmButtonColor: '#dc3545',
                    customClass: { popup: 'rounded-4' }
                });
            }
        });
    }
</script>
@endsection
