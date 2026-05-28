@extends('layouts.app')

@section('title', 'Manage Fixed Assets')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.3; transform: scale(0.8); }
    }
    .animate-pulse {
        animation: pulse 1.2s infinite;
        display: inline-block;
    }
    #reader video {
        object-fit: cover !important;
        border-radius: 12px !important;
        width: 100% !important;
        height: auto !important;
    }
    #reader {
        border: none !important;
    }
    /* Hide some html5-qrcode controls we don't want */
    #reader__dashboard {
        padding: 10px !important;
        background: #f8f9fa !important;
        border-top: 1px solid #dee2e6 !important;
    }
    #reader__dashboard_section_csr button {
        background-color: var(--hse-red, #C0392B) !important;
        color: white !important;
        border: none !important;
        padding: 6px 12px !important;
        border-radius: 6px !important;
        font-weight: 600 !important;
        font-size: 0.85rem !important;
    }

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
<div class="card border-0 shadow-sm mt-3 animate-fade-in">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-0 fw-bold"><i class="bi bi-box-seam me-2 text-hse-red"></i> Fixed Assets</h5>
            <small class="text-muted">Manage all fixed assets (hardware, furniture, vehicles)</small>
        </div>
        <div class="d-flex gap-2">
            <a href="#" id="btn-export-excel" class="btn btn-outline-success fw-bold shadow-sm">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
            <button type="button" class="btn btn-outline-danger fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#scannerModal">
                <i class="bi bi-qr-code-scan me-1"></i> Scan Barcode
            </button>
            <a href="{{ route('assets.create') }}" class="btn btn-hse-red fw-bold shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Asset
            </a>
        </div>
    </div>
    
    <!-- Scanner Modal -->
    <div class="modal fade" id="scannerModal" tabindex="-1" aria-labelledby="scannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="scannerModalLabel">
                        <i class="bi bi-camera me-2 text-danger"></i> Scan Asset Barcode
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div id="reader" style="width: 100%; border-radius: 12px; overflow: hidden; border: none; background: #fafafa;"></div>
                    <div id="scanner-status" class="text-center mt-3 text-muted small">
                        Allow camera access to scan barcodes or QR codes.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Asset Modal -->
    <div class="modal fade" id="viewAssetModal" tabindex="-1" aria-labelledby="viewAssetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="viewAssetModalLabel">
                        <i class="bi bi-info-circle me-2 text-danger"></i> Asset Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-7 border-end">
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0">
                                    <tr>
                                        <td width="35%" class="fw-bold text-muted small">Asset Name</td>
                                        <td id="view-name" class="fw-semibold">-</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted small">Category</td>
                                        <td id="view-category">-</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted small">Location</td>
                                        <td id="view-location">-</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted small">Assigned To (Holder)</td>
                                        <td id="view-assigned-to" class="fw-semibold text-dark">-</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted small">Supplier</td>
                                        <td id="view-supplier">-</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted small">Condition</td>
                                        <td id="view-condition">-</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted small">Status</td>
                                        <td id="view-status">-</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted small">Purchase Date</td>
                                        <td id="view-purchase-date">-</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted small">Purchase Cost</td>
                                        <td id="view-purchase-cost" class="fw-semibold text-danger">-</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted small">Description</td>
                                        <td id="view-description" class="text-muted small">-</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-5 d-flex flex-column align-items-center justify-content-center text-center ps-md-4">
                            <div class="p-3 bg-white border rounded-3 mb-3 shadow-sm w-100" style="min-height: 180px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                                <svg id="barcode-display" style="max-width: 100%; height: auto;"></svg>
                                <div id="barcode-code" class="font-monospace fw-bold text-dark mt-2" style="letter-spacing: 2px;"></div>
                            </div>
                            <button type="button" class="btn btn-outline-danger fw-bold w-100" id="btn-print-barcode">
                                <i class="bi bi-printer me-1"></i> Print Barcode Label
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div>
            <table class="table table-hover align-middle w-100 dt-responsive nowrap" id="assets-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Asset Code</th>
                        <th>Asset Name</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Holder / Pemegang</th>
                        <th>Status</th>
                        <th width="15%">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<!-- SweetAlert2 & JsBarcode -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

<script>
    $(function () {
        let categoryId = new URLSearchParams(window.location.search).get('category_id');
        let exportUrl = "{{ route('assets.export') }}";
        if (categoryId) {
            exportUrl += '?category_id=' + categoryId;
        }
        $('#btn-export-excel').attr('href', exportUrl);

        $('#assets-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('assets.index') }}",
                data: function(d) {
                    d.category_id = new URLSearchParams(window.location.search).get('category_id');
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'code', name: 'code', render: function(data) { return '<span class="fw-bold text-dark font-monospace">' + data + '</span>'; }},
                {data: 'name', name: 'name', render: function(data) { return '<span class="fw-medium">' + data + '</span>'; }},
                {data: 'category_name', name: 'category_name'},
                {data: 'location_name', name: 'location_name'},
                {data: 'assigned_to', name: 'assigned_to', render: function(data) {
                    return data ? '<span class="fw-semibold text-dark"><i class="bi bi-person me-1"></i>' + data + '</span>' : '<span class="text-muted fst-italic">No holder</span>';
                }},
                {data: 'status', name: 'status', render: function(data) {
                    let badgeClass = 'bg-secondary';
                    if(data === 'In Use') badgeClass = 'bg-success';
                    if(data === 'In Stock') badgeClass = 'bg-primary';
                    if(data === 'Maintenance') badgeClass = 'bg-warning text-dark';
                    if(data === 'Retired') badgeClass = 'bg-danger';
                    return '<span class="badge ' + badgeClass + ' custom-badge"><i class="bi bi-circle-fill me-1" style="font-size: 0.5em;"></i>' + data + '</span>';
                }},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            language: {
                search: "",
                searchPlaceholder: "Search assets..."
            },
            drawCallback: function() {
                $('.dataTables_paginate > .pagination').addClass('pagination-sm');
            }
        });
    });
</script>

<!-- HTML5 QR Code Scanner Library -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    $(function() {
        // Curated list of barcode formats for absolute multi-device compatibility (including CODE128)
        let supportedFormats = [];
        if (typeof Html5QrcodeSupportedFormats !== 'undefined') {
            supportedFormats = [
                Html5QrcodeSupportedFormats.QR_CODE,
                Html5QrcodeSupportedFormats.CODE_128,
                Html5QrcodeSupportedFormats.CODE_39,
                Html5QrcodeSupportedFormats.EAN_13,
                Html5QrcodeSupportedFormats.EAN_8,
                Html5QrcodeSupportedFormats.UPC_A,
                Html5QrcodeSupportedFormats.UPC_E
            ];
        } else {
            supportedFormats = [0, 4, 3, 5, 6, 9, 10]; // Fallback to integer representation
        }

        // Helper to load and display asset details modal dynamically
        function showAssetDetails(id) {
            // Show loading placeholders
            $('#view-name').text('Loading...');
            $('#view-category').text('-');
            $('#view-location').text('-');
            $('#view-assigned-to').text('-');
            $('#view-supplier').text('-');
            $('#view-condition').text('-');
            $('#view-status').text('-');
            $('#view-purchase-date').text('-');
            $('#view-purchase-cost').text('-');
            $('#view-description').text('-');
            $('#barcode-code').text('');
            $('#barcode-display').html('');

            $('#viewAssetModal').modal('show');

            fetch('/assets/' + id)
                .then(response => response.json())
                .then(asset => {
                    currentAsset = asset;
                    $('#view-name').text(asset.name);
                    $('#view-category').text(asset.category ? asset.category.name : '-');
                    $('#view-location').text(asset.location ? asset.location.name : '-');
                    $('#view-supplier').text(asset.supplier ? asset.supplier.name : '-');
                    
                    // Condition badge
                    let condClass = 'bg-secondary';
                    if(asset.condition === 'Good') condClass = 'bg-success';
                    if(asset.condition === 'Fair') condClass = 'bg-warning text-dark';
                    if(asset.condition === 'Poor') condClass = 'bg-danger';
                    $('#view-condition').html('<span class="badge ' + condClass + '">' + asset.condition + '</span>');

                    // Status badge
                    let statClass = 'bg-secondary';
                    if(asset.status === 'In Use') statClass = 'bg-success';
                    if(asset.status === 'In Stock') statClass = 'bg-primary';
                    if(asset.status === 'Maintenance') statClass = 'bg-warning text-dark';
                    if(asset.status === 'Retired') statClass = 'bg-danger';
                    $('#view-status').html('<span class="badge ' + statClass + '">' + asset.status + '</span>');

                    // Purchase date & cost
                    $('#view-purchase-date').text(asset.purchase_date ? asset.purchase_date : '-');
                    
                    let costFormatted = '{{ config("app.currency_symbol", "Rp") }} ' + parseFloat(asset.purchase_cost).toLocaleString('id-ID');
                    $('#view-purchase-cost').text(costFormatted);
                    $('#view-assigned-to').text(asset.assigned_to ? asset.assigned_to : '-');
                    $('#view-description').text(asset.description ? asset.description : '-');

                    // Generate Barcode SVG
                    $('#barcode-code').text(asset.code);
                    JsBarcode("#barcode-display", asset.code, {
                        format: "CODE128",
                        width: 2,
                        height: 50,
                        displayValue: false,
                        margin: 10
                    });
                })
                .catch(err => {
                    console.error('Error fetching asset details:', err);
                    $('#viewAssetModal').modal('hide');
                    Swal.fire('Error', 'Failed to load asset details.', 'error');
                });
        }

        // Scanner Logic
        $('#scannerModal').on('shown.bs.modal', function () {
            $('#scanner-status').html('<span class="spinner-border spinner-border-sm text-secondary me-2"></span>Starting camera...');
            lastResult = '';
            
            html5QrcodeScanner = new Html5Qrcode("reader");
            
            const config = { 
                fps: 30, // 3x higher frame rate for split-second captures
                qrbox: function(width, height) {
                    return {
                        width: Math.floor(width * 0.85), // Widen scan frame for horizontal 1D barcodes
                        height: Math.floor(height * 0.6)
                    };
                },
                aspectRatio: 1.333333,
                formatsToSupport: supportedFormats, // Explicitly tell scanner to listen to CODE128 barcodes!
                experimentalFeatures: {
                    useBarCodeDetectorIfSupported: true // Activate browser native hardware accelerated scanning engine
                }
            };
            
            html5QrcodeScanner.start(
                { 
                    facingMode: "environment",
                    width: { min: 640, ideal: 1280 }, // Request high-resolution frame for sharp line scanning
                    height: { min: 480, ideal: 720 }
                },
                config,
                (decodedText, decodedResult) => {
                    if (decodedText === lastResult) return;
                    lastResult = decodedText;
                    
                    try {
                        let audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-84.wav');
                        audio.volume = 0.5;
                        audio.play().catch(e => {});
                    } catch (e) {
                        console.error('Audio cue error:', e);
                    }

                    // Hide scanner immediately
                    $('#scannerModal').modal('hide');

                    // Filter table list
                    let table = $('#assets-table').DataTable();
                    table.search(decodedText).draw();
                    
                    $('.dataTables_filter input').addClass('border-danger').css('box-shadow', '0 0 0 4px rgba(192, 57, 43, 0.2)');
                    setTimeout(() => {
                        $('.dataTables_filter input').removeClass('border-danger').css('box-shadow', 'none');
                    }, 1500);

                    // Client-side quick row lookup for instant detail presentation!
                    let assetId = null;
                    table.rows().every(function() {
                        let rowData = this.data();
                        if (rowData.code === decodedText || rowData.name === decodedText) {
                            assetId = rowData.id;
                            return false; // Break loop
                        }
                    });

                    if (assetId) {
                        showAssetDetails(assetId);
                    } else {
                        // Fallback: search backend for matching code if not on current datatable page
                        fetch('/api/search?q=' + encodeURIComponent(decodedText))
                            .then(res => res.json())
                            .then(data => {
                                // Redraw table, wait, then trigger view
                                setTimeout(() => {
                                    let firstRow = table.row(0).data();
                                    if (firstRow) {
                                        showAssetDetails(firstRow.id);
                                    }
                                }, 500);
                            });
                    }
                },
                (errorMessage) => {}
            ).then(() => {
                $('#scanner-status').html('<span class="text-success fw-bold"><i class="bi bi-circle-fill text-success animate-pulse me-1"></i> Scanner Active. Point at an asset barcode.</span>');
            }).catch(err => {
                let errorMsg = err;
                if (window.location.protocol !== 'https:' && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
                    errorMsg = "Kamera diblokir oleh browser karena menggunakan HTTP biasa. Silakan gunakan link HTTPS aman terowongan agar kamera aktif: <a href='" + window.location.href.replace('http://', 'https://') + "' class='text-decoration-underline text-danger fw-bold' target='_blank'>Buka via HTTPS</a>";
                }
                $('#scanner-status').html('<span class="text-danger fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i> Akses Kamera Gagal:<br><span class="small fw-normal text-muted d-block mt-1">' + errorMsg + '</span></span>');
            });
        });

        $('#scannerModal').on('hidden.bs.modal', function () {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    html5QrcodeScanner = null;
                    $('#reader').html('');
                }).catch(err => {
                    console.error('Error stopping scanner:', err);
                });
            }
        });

        // VIEW ASSET EVENT BINDING
        $(document).on('click', '.btn-view', function() {
            let id = $(this).data('id');
            showAssetDetails(id);
        });

        // PRINT BARCODE LABEL
        $('#btn-print-barcode').on('click', function() {
            if (!currentAsset) return;
            
            let code = currentAsset.code;
            let name = currentAsset.name;
            let categoryName = currentAsset.category ? currentAsset.category.name : 'GENERAL';
            let locationName = currentAsset.location ? currentAsset.location.name : 'WAREHOUSE';
            
            let printWindow = window.open('', '_blank', 'width=680,height=380');
            printWindow.document.write('<html><head><title>Print Barcode - ' + code + '</title>');
            printWindow.document.write('<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&family=Fira+Code:wght@700&display=swap" rel="stylesheet">');
            printWindow.document.write('<style>');
            printWindow.document.write('body { font-family: "Plus Jakarta Sans", sans-serif; margin: 0; padding: 25px; display: flex; justify-content: center; align-items: center; background-color: #0f172a; }');
            printWindow.document.write('.label-card { width: 480px; height: 220px; background: #0f172a; border-radius: 16px; overflow: hidden; border: 2px solid #334155; display: flex; padding: 16px; box-sizing: border-box; color: #f8fafc; box-shadow: 0 10px 30px rgba(0,0,0,0.25); position: relative; }');
            printWindow.document.write('.label-left { width: 160px; background: #1e293b; border-radius: 12px; border: 1px solid #334155; display: flex; align-items: center; justify-content: center; padding: 10px; position: relative; box-sizing: border-box; }');
            printWindow.document.write('.tech-corner { position: absolute; width: 10px; height: 10px; border-color: #ef4444; border-style: solid; }');
            printWindow.document.write('.tc-tl { top: 6px; left: 6px; border-width: 2px 0 0 2px; }');
            printWindow.document.write('.tc-tr { top: 6px; right: 6px; border-width: 2px 2px 0 0; }');
            printWindow.document.write('.tc-bl { bottom: 6px; left: 6px; border-width: 0 0 2px 2px; }');
            printWindow.document.write('.tc-br { bottom: 6px; right: 6px; border-width: 0 2px 2px 0; }');
            printWindow.document.write('.barcode-container { background: #ffffff; padding: 6px; border-radius: 8px; display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; box-sizing: border-box; }');
            printWindow.document.write('.barcode-container svg { width: 100% !important; height: auto !important; }');
            printWindow.document.write('.label-right { flex: 1; padding-left: 20px; display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box; }');
            printWindow.document.write('.brand-title { font-size: 9px; font-weight: 800; color: #ef4444; letter-spacing: 2px; text-transform: uppercase; margin: 0 0 4px 0; }');
            printWindow.document.write('.asset-name { font-size: 18px; font-weight: 800; color: #ffffff; margin: 0 0 4px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }');
            printWindow.document.write('.asset-meta { font-size: 10px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }');
            printWindow.document.write('.label-footer { display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #334155; padding-top: 10px; margin-top: 10px; }');
            printWindow.document.write('.asset-code-badge { background: #ef4444; color: #ffffff; font-family: "Fira Code", monospace; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 9999px; letter-spacing: 1px; }');
            printWindow.document.write('.footer-text { font-size: 8px; font-weight: 700; color: #64748b; letter-spacing: 1px; text-transform: uppercase; }');
            printWindow.document.write('@media print {');
            printWindow.document.write('  body { background-color: white; padding: 0; }');
            printWindow.document.write('  .label-card { width: 480px; height: 220px; border: 2px solid #000; background: #ffffff; color: #000000; box-shadow: none; -webkit-print-color-adjust: exact; print-color-adjust: exact; }');
            printWindow.document.write('  .label-left { background: #f8fafc; border: 1px solid #cbd5e1; }');
            printWindow.document.write('  .asset-name { color: #000000; }');
            printWindow.document.write('  .asset-meta { color: #475569; }');
            printWindow.document.write('  .label-footer { border-top: 1px solid #cbd5e1; }');
            printWindow.document.write('  .asset-code-badge { background: #000000; color: #ffffff; }');
            printWindow.document.write('  .footer-text { color: #475569; }');
            printWindow.document.write('}');
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<div class="label-card">');
            printWindow.document.write('  <div class="label-left">');
            printWindow.document.write('    <div class="tech-corner tc-tl"></div>');
            printWindow.document.write('    <div class="tech-corner tc-tr"></div>');
            printWindow.document.write('    <div class="tech-corner tc-bl"></div>');
            printWindow.document.write('    <div class="tech-corner tc-br"></div>');
            printWindow.document.write('    <div class="barcode-container">');
            printWindow.document.write('      <svg id="barcode-print"></svg>');
            printWindow.document.write('    </div>');
            printWindow.document.write('  </div>');
            printWindow.document.write('  <div class="label-right">');
            printWindow.document.write('    <div>');
            printWindow.document.write('      <div class="brand-title">{{ config("app.name", "HSE SYSTEM") }}</div>');
            printWindow.document.write('      <h3 class="asset-name">' + name + '</h3>');
            printWindow.document.write('      <div class="asset-meta">' + categoryName + ' • ' + locationName + '</div>');
            printWindow.document.write('    </div>');
            printWindow.document.write('    <div class="label-footer">');
            printWindow.document.write('      <div class="asset-code-badge">' + code + '</div>');
            printWindow.document.write('      <div class="footer-text">PROPERTY OF {{ strtoupper(config("app.name", "HSE")) }}</div>');
            printWindow.document.write('    </div>');
            printWindow.document.write('  </div>');
            printWindow.document.write('</div>');
            printWindow.document.write('<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"><\/script>');
            printWindow.document.write('<script>');
            printWindow.document.write('window.onload = function() {');
            printWindow.document.write('  JsBarcode("#barcode-print", "' + code + '", { format: "CODE128", width: 2, height: 75, displayValue: false, margin: 0 });');
            printWindow.document.write('  setTimeout(function() { window.print(); window.close(); }, 500);');
            printWindow.document.write('};');
            printWindow.document.write('<\/script>');
            printWindow.document.write('</body></html>');
            printWindow.document.close();
        });

        // SWEETALERT DELETE CONFIRMATION
        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            
            Swal.fire({
                title: 'Delete Asset?',
                text: 'Are you sure you want to delete "' + name + '"? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C0392B',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('<form>', {
                        'action': '/assets/' + id,
                        'method': 'POST'
                    });
                    
                    form.append($('<input>', {
                        'name': '_token',
                        'value': $('meta[name="csrf-token"]').attr('content'),
                        'type': 'hidden'
                    }));
                    
                    form.append($('<input>', {
                        'name': '_method',
                        'value': 'DELETE',
                        'type': 'hidden'
                    }));
                    
                    $('body').append(form);
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
