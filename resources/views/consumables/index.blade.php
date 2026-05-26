@extends('layouts.app')

@section('title', 'Manage Consumables')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush

@section('content')
<div class="card border-0 shadow-sm mt-3 animate-fade-in">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-0 fw-bold"><i class="bi bi-basket me-2 text-hse-red"></i> Consumables</h5>
            <small class="text-muted">Manage expendable items and stationery stocks</small>
        </div>
        <div class="d-flex gap-2">
            <a href="#" id="btn-export-excel" class="btn btn-outline-success fw-bold shadow-sm">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
            <a href="{{ route('consumables.create') }}" class="btn btn-hse-red fw-bold shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Consumable
            </a>
        </div>
    </div>
    <div class="card-body">
        <div>
            <table class="table table-hover align-middle w-100 dt-responsive nowrap" id="consumables-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Current Stock</th>
                        <th>Min Stock</th>
                        <th>Status</th>
                        <th width="15%">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- View Consumable Details Modal -->
<div class="modal fade" id="viewConsumableModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
            <div class="modal-header bg-light border-bottom-0 py-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                <div class="d-flex align-items-center gap-2">
                    <div class="form-header-icon-box" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; border-radius: 8px; background: rgba(192, 57, 43, 0.1); color: var(--hse-red, #C0392B);">
                        <i class="bi bi-basket fs-5"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0">Consumable Details</h6>
                        <small class="text-muted">Item information and inventory levels</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-7 border-end">
                        <div class="mb-4">
                            <h4 class="fw-bold text-dark mb-1" id="view-name">Loading...</h4>
                            <span class="badge bg-secondary px-2.5 py-1.5" id="view-category">Category</span>
                        </div>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3 text-center">
                                    <span class="text-muted d-block small mb-1">Current Stock</span>
                                    <h4 class="fw-bold text-dark mb-0" id="view-stock">0</h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3 text-center">
                                    <span class="text-muted d-block small mb-1">Minimum Stock</span>
                                    <h4 class="fw-bold text-dark mb-0" id="view-min-stock">0</h4>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-borderless align-middle mb-0">
                                <tr class="border-bottom">
                                    <td width="40%" class="text-muted small py-2">Stock Status</td>
                                    <td id="view-status" class="py-2">-</td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="text-muted small py-2">Supplier / Vendor</td>
                                    <td id="view-supplier" class="fw-semibold text-dark small py-2">-</td>
                                </tr>
                                <tr>
                                    <td class="text-muted small py-2">Notes / Description</td>
                                    <td id="view-description" class="text-dark small py-2">-</td>
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
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary w-100 fw-bold py-2.5" style="border-radius: 10px;" data-bs-dismiss="modal">Close</button>
            </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

<script>
    $(function () {
        let categoryId = new URLSearchParams(window.location.search).get('category_id');
        let exportUrl = "{{ route('consumables.export') }}";
        if (categoryId) {
            exportUrl += '?category_id=' + categoryId;
        }
        $('#btn-export-excel').attr('href', exportUrl);

        $('#consumables-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('consumables.index') }}",
                data: function(d) {
                    d.low_stock = new URLSearchParams(window.location.search).get('low_stock') || "{{ request('low_stock') }}";
                    d.category_id = new URLSearchParams(window.location.search).get('category_id');
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'code', name: 'code', render: function(data) { return '<span class="fw-bold text-dark font-monospace">' + data + '</span>'; }},
                {data: 'name', name: 'name', render: function(data) { return '<span class="fw-medium text-dark">' + data + '</span>'; }},
                {data: 'category_name', name: 'category_name'},
                {data: 'stock', name: 'stock', render: function(data, type, row) { 
                    return '<span class="fw-bold fs-5">' + data + '</span> <span class="small text-muted">' + row.unit + '</span>'; 
                }},
                {data: 'min_stock', name: 'min_stock', render: function(data, type, row) {
                    return data + ' ' + row.unit;
                }},
                {data: 'stock_status', name: 'stock_status', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            language: {
                search: "",
                searchPlaceholder: "Search consumables..."
            },
            drawCallback: function() {
                $('.dataTables_paginate > .pagination').addClass('pagination-sm');
            }
        });

        // VIEW CONSUMABLE DETAILS MODAL EVENT
        let currentConsumable = null;
        $(document).on('click', '.btn-view', function() {
            let id = $(this).data('id');
            $('#view-name').text('Loading...');
            $('#view-category').text('-');
            $('#view-stock').text('-');
            $('#view-min-stock').text('-');
            $('#view-status').html('-');
            $('#view-supplier').text('-');
            $('#view-description').text('-');
            $('#barcode-code').text('');
            $('#barcode-display').html('');
            
            $('#viewConsumableModal').modal('show');
            
            fetch('/consumables/' + id)
                .then(response => response.json())
                .then(data => {
                    currentConsumable = data;
                    $('#view-name').text(data.name);
                    $('#view-category').text(data.category ? data.category.name : '-');
                    $('#view-stock').text(data.stock + ' ' + data.unit);
                    $('#view-min-stock').text(data.min_stock + ' ' + data.unit);
                    
                    let statusHtml = '';
                    if (data.stock <= data.min_stock) {
                        statusHtml = '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1"><i class="bi bi-exclamation-triangle me-1"></i> LOW STOCK</span>';
                    } else {
                        statusHtml = '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="bi bi-check-circle me-1"></i> IN STOCK</span>';
                    }
                    $('#view-status').html(statusHtml);
                    $('#view-supplier').text(data.supplier ? data.supplier.name : '-');
                    $('#view-description').text(data.description ? data.description : '-');

                    // Generate Barcode SVG
                    $('#barcode-code').text(data.code);
                    JsBarcode("#barcode-display", data.code, {
                        format: "CODE128",
                        width: 2,
                        height: 50,
                        displayValue: false,
                        margin: 10
                    });
                })
                .catch(err => {
                    console.error(err);
                    $('#viewConsumableModal').modal('hide');
                    Swal.fire('Error', 'Failed to load details.', 'error');
                });
        });

        // PRINT BARCODE LABEL
        $('#btn-print-barcode').on('click', function() {
            if (!currentConsumable) return;
            
            let code = currentConsumable.code;
            let name = currentConsumable.name;
            let categoryName = currentConsumable.category ? currentConsumable.category.name : 'GENERAL';
            let locationName = 'GUDANG UTAMA';
            
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
            printWindow.document.write('      <div class="brand-title">HSE SYSTEM</div>');
            printWindow.document.write('      <h3 class="asset-name">' + name + '</h3>');
            printWindow.document.write('      <div class="asset-meta">' + categoryName + ' • ' + locationName + '</div>');
            printWindow.document.write('    </div>');
            printWindow.document.write('    <div class="label-footer">');
            printWindow.document.write('      <div class="asset-code-badge">' + code + '</div>');
            printWindow.document.write('      <div class="footer-text">PROPERTY OF HSE</div>');
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
                title: 'Delete Consumable?',
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
                        'action': '/consumables/' + id,
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
