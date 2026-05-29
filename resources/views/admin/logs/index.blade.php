@extends('layouts.app')

@section('title', 'Activity Logs')

@section('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
<style>
    .dt-user-agent {
        max-width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
    }
    .btn-clear-logs {
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.88rem;
        transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .btn-clear-logs:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(220, 53, 69, 0.25);
    }
    .custom-option-check {
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        transition: all 0.2s ease;
    }
    .custom-option-check:hover {
        border-color: rgba(0, 0, 0, 0.15) !important;
        background-color: rgba(0, 0, 0, 0.01);
    }
    .custom-option-check.active-option {
        border-color: #dc3545 !important;
        background-color: rgba(220, 53, 69, 0.03) !important;
    }
    .custom-option-check.active-option-blue {
        border-color: #0d6efd !important;
        background-color: rgba(13, 110, 253, 0.03) !important;
    }
</style>
@endsection

@section('content')
<div class="card border-0 shadow-sm mt-3 animate-fade-in" style="border-radius: 16px;">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
        <div class="d-flex align-items-center gap-2">
            <div class="p-2 bg-hse-red bg-opacity-10 text-hse-red rounded-3">
                <i class="bi bi-clock-history fs-5"></i>
            </div>
            <div>
                <h5 class="card-title mb-0 fw-bold">System Activity Logs</h5>
                <small class="text-muted">Track user logins, logouts, asset changes, and configuration updates</small>
            </div>
        </div>
        <div>
            <button type="button" class="btn btn-outline-danger btn-clear-logs d-flex align-items-center gap-1 px-3 py-2 shadow-sm" id="btn-clear-all-logs">
                <i class="bi bi-trash3 fs-6"></i> Hapus / Bersihkan Log
            </button>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100 dt-responsive nowrap" id="logs-table">
                <thead>
                    <tr>
                        <th width="4%">No</th>
                        <th width="14%">Timestamp</th>
                        <th width="12%">User</th>
                        <th width="10%">Activity</th>
                        <th width="28%">Description</th>
                        <th width="10%">IP Address</th>
                        <th width="14%">User Agent</th>
                        <th width="8%" class="text-center">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal Hapus Log Premium -->
<div class="modal fade" id="clearLogsModal" tabindex="-1" aria-labelledby="clearLogsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #1c1515 0%, #291818 100%);">
                <h6 class="modal-title fw-bold text-white mb-0" id="clearLogsModalLabel">
                    <i class="bi bi-trash3-fill text-danger me-2"></i> Bersihkan Log Aktivitas
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="clear-logs-form">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark mb-2">Pilih Mode Pembersihan:</label>
                        <div class="d-flex flex-column gap-2">
                            <!-- Opsi Semua Log -->
                            <div class="form-check custom-option-check active-option p-3 position-relative" style="cursor: pointer;">
                                <input class="form-check-input ms-0 me-2" type="radio" name="clear_mode" id="mode-all" value="all" checked style="cursor: pointer;">
                                <label class="form-check-label fw-bold text-dark d-block" for="mode-all" style="cursor: pointer; padding-left: 20px;">
                                    <i class="bi bi-trash3 me-1 text-danger"></i> Hapus Semua Log (Seluruh Riwayat)
                                </label>
                                <div class="text-muted small mt-1" style="padding-left: 20px;">Mengosongkan seluruh data log aktivitas sistem secara permanen.</div>
                            </div>
                            
                            <!-- Opsi Rentang Tanggal -->
                            <div class="form-check custom-option-check p-3 position-relative" style="cursor: pointer;">
                                <input class="form-check-input ms-0 me-2" type="radio" name="clear_mode" id="mode-range" value="range" style="cursor: pointer;">
                                <label class="form-check-label fw-bold text-dark d-block" for="mode-range" style="cursor: pointer; padding-left: 20px;">
                                    <i class="bi bi-calendar-range me-1 text-primary"></i> Hapus Berdasarkan Rentang Tanggal
                                </label>
                                <div class="text-muted small mt-1" style="padding-left: 20px;">Hanya menghapus data log aktivitas dalam rentang tanggal tertentu.</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Input Rentang Tanggal (Hidden by default) -->
                    <div id="date-range-container" class="mb-2" style="display: none; border-top: 1px dashed rgba(0,0,0,0.08); padding-top: 15px;">
                        <div class="row g-2">
                            <div class="col-6">
                                <label for="start_date" class="form-label small fw-bold text-secondary mb-1">Tanggal Mulai</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar-event text-muted"></i></span>
                                    <input type="date" class="form-control border-start-0 font-monospace" id="start_date" name="start_date" style="border-radius: 0 8px 8px 0; font-size: 0.9rem;">
                                </div>
                            </div>
                            <div class="col-6">
                                <label for="end_date" class="form-label small fw-bold text-secondary mb-1">Tanggal Selesai</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar-event text-muted"></i></span>
                                    <input type="date" class="form-control border-start-0 font-monospace" id="end_date" name="end_date" style="border-radius: 0 8px 8px 0; font-size: 0.9rem;">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 p-3 bg-light d-flex gap-2 justify-content-end">
                <button type="button" class="btn btn-outline-secondary fw-semibold px-3 py-2" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                <button type="button" class="btn btn-danger fw-bold px-4 py-2" id="btn-submit-cleanup" style="border-radius: 10px; background-color: #dc3545; border-color: #dc3545;">
                    <i class="bi bi-shield-check me-1"></i> Jalankan Pembersihan
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(function () {
        var table = $('#logs-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('admin.logs.index') }}",
            order: [[1, 'desc']], // Sort by Timestamp (index 1) descending by default
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search logs...",
                paginate: {
                    previous: "<i class='bi bi-chevron-left'></i>",
                    next: "<i class='bi bi-chevron-right'></i>"
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'formatted_date', name: 'created_at'},
                {data: 'user_name', name: 'user.name'},
                {data: 'activity_badge', name: 'activity'},
                {data: 'description', name: 'description', render: function(data) {
                    return '<span class="text-dark fw-medium">' + (data ? data : '-') + '</span>';
                }},
                {data: 'ip_address', name: 'ip_address', render: function(data) {
                    return '<span class="font-monospace text-muted">' + (data ? data : '-') + '</span>';
                }},
                {data: 'user_agent', name: 'user_agent', render: function(data) {
                    if (!data) return '-';
                    return '<span class="dt-user-agent text-muted" title="' + data + '">' + data + '</span>';
                }},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
            ]
        });

        // Toggle Clear Mode selection styling and date pickers
        $('input[name="clear_mode"]').on('change', function() {
            var mode = $(this).val();
            $('.custom-option-check').removeClass('active-option active-option-blue');
            
            if (mode === 'range') {
                $('#date-range-container').slideDown(250);
                $(this).closest('.custom-option-check').addClass('active-option-blue');
            } else {
                $('#date-range-container').slideUp(200);
                $(this).closest('.custom-option-check').addClass('active-option');
            }
        });

        // Trigger opening of cleanup modal instead of instant clear all
        $('#btn-clear-all-logs').on('click', function(e) {
            e.preventDefault();
            // Reset fields
            $('#mode-all').prop('checked', true).trigger('change');
            $('#start_date').val('');
            $('#end_date').val('');
            
            var modal = new bootstrap.Modal(document.getElementById('clearLogsModal'));
            modal.show();
        });

        // Handle Cleanup Form Submission
        $('#btn-submit-cleanup').on('click', function(e) {
            e.preventDefault();
            var mode = $('input[name="clear_mode"]:checked').val();
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();
            
            var requestData = {};
            var confirmTitle = 'Kosongkan Semua Log?';
            var confirmText = 'Tindakan ini akan menghapus seluruh data log aktivitas sistem secara permanen!';
            
            if (mode === 'range') {
                if (!startDate || !endDate) {
                    Swal.fire({
                        title: 'Input Tidak Lengkap!',
                        text: 'Silakan isi tanggal mulai dan tanggal selesai terlebih dahulu.',
                        icon: 'warning',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }
                
                // Validate dates
                if (new Date(startDate) > new Date(endDate)) {
                    Swal.fire({
                        title: 'Tanggal Tidak Valid!',
                        text: 'Tanggal Mulai tidak boleh lebih besar dari Tanggal Selesai.',
                        icon: 'warning',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }
                
                requestData.start_date = startDate;
                requestData.end_date = endDate;
                confirmTitle = 'Bersihkan Log Periode Terpilih?';
                confirmText = 'Seluruh log aktivitas dari tanggal ' + startDate + ' sampai ' + endDate + ' akan dihapus secara permanen!';
            }
            
            // SweetAlert2 Confirmation Dialog
            Swal.fire({
                title: confirmTitle,
                text: confirmText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-shield-check me-1"></i> Ya, Bersihkan!',
                cancelButtonText: 'Batal',
                background: '#ffffff',
                customClass: {
                    popup: 'rounded-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Close the Bootstrap modal first
                    bootstrap.Modal.getInstance(document.getElementById('clearLogsModal')).hide();
                    
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang membersihkan data log...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: "{{ route('admin.logs.clear') }}",
                        type: 'DELETE',
                        data: requestData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                    customClass: {
                                        popup: 'rounded-4'
                                    }
                                });
                                table.ajax.reload();
                            } else {
                                Swal.fire({
                                    title: 'Info',
                                    text: response.message,
                                    icon: 'info',
                                    confirmButtonColor: '#3085d6'
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan sistem saat mencoba membersihkan log.',
                                icon: 'error',
                                confirmButtonColor: '#3085d6'
                            });
                        }
                    });
                }
            });
        });

        // Hapus Single Log (Delete Single Log)
        $('#logs-table').on('click', '.btn-delete-log', function(e) {
            e.preventDefault();
            var logId = $(this).data('id');
            
            Swal.fire({
                title: 'Hapus Log Terpilih?',
                text: "Baris log aktivitas ini akan dihapus secara permanen dari sistem!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#ffffff',
                customClass: {
                    popup: 'rounded-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/admin/logs/" + logId,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Terhapus!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                    customClass: {
                                        popup: 'rounded-4'
                                    }
                                });
                                table.ajax.reload();
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Gagal menghapus baris log terpilih.',
                                icon: 'error',
                                confirmButtonColor: '#3085d6'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
