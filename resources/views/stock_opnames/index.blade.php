@extends('layouts.app')

@section('title', 'Stock Opname Audit')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
    /* Premium Springy Modal Animation */
    .modal.fade .modal-dialog {
        transform: scale(0.92) translateY(25px);
        opacity: 0;
        transition: transform 0.45s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.35s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }
    .modal.show .modal-dialog {
        transform: scale(1) translateY(0);
        opacity: 1;
    }
    .modal-content {
        border-radius: 20px !important;
        overflow: hidden;
    }
    
    /* Frosted Glass Backdrop for Modals */
    .modal-backdrop {
        background-color: rgba(15, 23, 42, 0.3) !important;
        backdrop-filter: blur(6px);
        transition: opacity 0.35s ease !important;
    }
    .modal-backdrop.show {
        opacity: 1 !important;
    }
</style>
@endpush

@section('content')
<!-- Premium Dashboard Summary Cards -->
<div class="row g-3 mb-4 animate-fade-in">
    <!-- Card 1: Total Audits -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 position-relative" style="background: #ffffff; min-height: 110px;">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="rounded-3 p-3 d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary" style="width: 54px; height: 54px;">
                    <i class="bi bi-clipboard2-check fs-3"></i>
                </div>
                <div class="ms-3">
                    <span class="text-muted fw-semibold small d-block">Total Audit</span>
                    <h3 class="mb-0 fw-bold mt-1 text-dark">{{ $totalAudits }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Match Audits -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 position-relative" style="background: #ffffff; min-height: 110px;">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="rounded-3 p-3 d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success" style="width: 54px; height: 54px;">
                    <i class="bi bi-check-circle fs-3"></i>
                </div>
                <div class="ms-3">
                    <span class="text-muted fw-semibold small d-block">Sesuai (Match)</span>
                    <h3 class="mb-0 fw-bold mt-1 text-dark">{{ $matchAudits }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Discrepancy Audits -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 position-relative" style="background: #ffffff; min-height: 110px;">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="rounded-3 p-3 d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger" style="width: 54px; height: 54px;">
                    <i class="bi bi-exclamation-triangle fs-3"></i>
                </div>
                <div class="ms-3">
                    <span class="text-muted fw-semibold small d-block">Selisih (Discrepancy)</span>
                    <h3 class="mb-0 fw-bold mt-1 text-dark">{{ $discrepancyAudits }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Net Adjustment -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 position-relative" style="background: #ffffff; min-height: 110px;">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="rounded-3 p-3 d-flex align-items-center justify-content-center {{ $netAdjustment >= 0 ? 'bg-success' : 'bg-danger' }} bg-opacity-10 {{ $netAdjustment >= 0 ? 'text-success' : 'text-danger' }}" style="width: 54px; height: 54px;">
                    <i class="bi {{ $netAdjustment >= 0 ? 'bi-graph-up' : 'bi-graph-down' }} fs-3"></i>
                </div>
                <div class="ms-3">
                    <span class="text-muted fw-semibold small d-block">Penyesuaian Bersih</span>
                    <h3 class="mb-0 fw-bold mt-1 text-dark">{{ $netAdjustment >= 0 ? '+' : '' }}{{ $netAdjustment }} Unit</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm animate-fade-in">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-0 fw-bold"><i class="bi bi-clipboard2-check me-2 text-hse-red"></i> Stock Opname History</h5>
            <small class="text-muted">Daftar pemeriksaan fisik persediaan barang habis pakai (consumables)</small>
        </div>
        <div>
            <a href="{{ route('stock-opnames.create') }}" class="btn btn-hse-red fw-bold shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Lakukan Stock Opname
            </a>
        </div>
    </div>
    
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div>
            <table class="table table-hover align-middle w-100 dt-responsive nowrap" id="opname-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Tanggal Audit</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Stok Sistem</th>
                        <th>Stok Fisik</th>
                        <th>Status Audit</th>
                        <th>Pemeriksa</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- View Opname Details Modal -->
<div class="modal fade" id="viewOpnameModal" tabindex="-1" aria-labelledby="viewOpnameModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom-0 py-3">
                <h5 class="modal-title fw-bold" id="viewOpnameModalLabel">
                    <i class="bi bi-clipboard2-check me-2 text-danger"></i> Rincian Stock Opname
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle p-3 mb-2" style="width: 70px; height: 70px;">
                        <i class="bi bi-clipboard2-check fs-1"></i>
                    </div>
                    <h5 id="modal-item-name" class="fw-bold mb-1">-</h5>
                    <span id="modal-item-code" class="text-muted font-monospace small">-</span>
                </div>
                
                <div class="row g-3">
                    <div class="col-6">
                        <div class="border rounded-3 p-3 text-center bg-light">
                            <span class="text-muted small fw-semibold d-block">Stok Sistem</span>
                            <h4 id="modal-system-stock" class="fw-bold mb-0 text-dark">-</h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded-3 p-3 text-center bg-light">
                            <span class="text-muted small fw-semibold d-block">Stok Fisik</span>
                            <h4 id="modal-physical-stock" class="fw-bold mb-0 text-dark">-</h4>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <div class="border rounded-3 p-3" id="modal-diff-container">
                            <span class="text-muted small fw-semibold d-block">Selisih Pemeriksaan</span>
                            <h3 id="modal-difference" class="fw-bold mb-0">-</h3>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted fw-semibold small">Tanggal Audit</span>
                        <span id="modal-date" class="fw-bold text-dark">-</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted fw-semibold small">Pemeriksa</span>
                        <span id="modal-auditor" class="fw-bold text-dark">-</span>
                    </div>
                    <div>
                        <span class="text-muted fw-semibold small d-block mb-1">Catatan Audit</span>
                        <div id="modal-notes" class="p-3 border rounded-3 text-muted bg-light" style="font-size: 0.9rem; min-height: 60px;">-</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#opname-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('stock-opnames.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'opname_date', name: 'opname_date'},
                {data: 'consumable_code', name: 'consumable.code'},
                {data: 'consumable_name', name: 'consumable.name'},
                {data: 'system_stock', name: 'system_stock', className: 'text-center'},
                {data: 'physical_stock', name: 'physical_stock', className: 'text-center'},
                {data: 'status_badge', name: 'status', className: 'text-center'},
                {data: 'auditor_name', name: 'user.name'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
            ],
            order: [[1, 'desc']],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Cari data audit..."
            }
        });

        // Handle View Details Modal
        $('#opname-table').on('click', '.btn-view', function() {
            var id = $(this).data('id');
            $.get("/stock-opnames/" + id, function(data) {
                $('#modal-item-name').text(data.consumable.name);
                $('#modal-item-code').text(data.consumable.code);
                $('#modal-system-stock').text(data.system_stock + ' ' + data.consumable.unit);
                $('#modal-physical-stock').text(data.physical_stock + ' ' + data.consumable.unit);
                
                var diff = data.difference;
                var diffText = diff > 0 ? '+' + diff : diff;
                $('#modal-difference').text(diffText + ' ' + data.consumable.unit);
                
                var diffContainer = $('#modal-diff-container');
                if (diff === 0) {
                    diffContainer.removeClass('bg-danger bg-opacity-10 border-danger text-danger bg-success bg-opacity-10 border-success text-success').addClass('bg-success bg-opacity-10 border-success text-success');
                } else {
                    diffContainer.removeClass('bg-danger bg-opacity-10 border-danger text-danger bg-success bg-opacity-10 border-success text-success').addClass('bg-danger bg-opacity-10 border-danger text-danger');
                }
                
                // Format date nicely
                var optDate = new Date(data.opname_date);
                var formattedDate = optDate.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
                $('#modal-date').text(formattedDate);
                $('#modal-auditor').text(data.user.name);
                $('#modal-notes').text(data.notes || 'Tidak ada catatan tambahan.');
                
                $('#viewOpnameModal').modal('show');
            });
        });
    });
</script>
@endpush
