@extends('layouts.app')

@section('title', 'Categories')

@section('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
<style>
    .view-category-items {
        transition: color 0.15s ease-in-out;
    }
    a.view-category-items:hover {
        color: #C0392B !important;
        text-decoration: underline !important;
    }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <div class="d-flex align-items-center gap-2">
            <div class="p-2 bg-hse-red bg-opacity-10 text-hse-red rounded">
                <i class="bi bi-tags fs-5"></i>
            </div>
            <div>
                <h5 class="card-title mb-0 fw-bold">Master Categories</h5>
                <small class="text-muted">Manage all asset and consumable classifications</small>
            </div>
        </div>
        <a href="{{ route('categories.create') }}" class="btn btn-hse-red fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Category
        </a>
    </div>
    <div class="card-body">
        <div>
            <table class="table table-hover align-middle w-100 dt-responsive nowrap" id="categories-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Category Name</th>
                        <th width="15%">Type</th>
                        <th width="15%">Total Items</th>
                        <th width="25%">Description</th>
                        <th width="15%" class="text-end">Actions</th>
                    </tr>
                </thead>
            </table>
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

<script>
    $(function () {
        $('#categories-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('categories.index') }}",
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search categories...",
                paginate: {
                    previous: "<i class='bi bi-chevron-left'></i>",
                    next: "<i class='bi bi-chevron-right'></i>"
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name', render: function(data, type, row) {
                    return '<a href="javascript:void(0)" class="fw-bold text-dark text-decoration-none view-category-items" data-id="' + row.id + '">' + data + '</a>';
                }},
                {data: 'type', name: 'type', render: function(data) {
                    if(data === 'asset') {
                        return '<span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1"><i class="bi bi-box-seam me-1"></i> ASSET</span>';
                    } else {
                        return '<span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1"><i class="bi bi-basket me-1"></i> CONSUMABLE</span>';
                    }
                }},
                {data: 'items_count', name: 'items_count', orderable: false, searchable: false, render: function(data, type, row) {
                    let badgeClass = row.type === 'asset' ? 'bg-primary' : 'bg-info';
                    return '<button type="button" class="btn btn-sm btn-light border position-relative view-category-items shadow-sm" data-id="' + row.id + '" style="border-radius: 20px; padding: 2px 12px; font-size: 0.85rem;">' +
                           '<span class="badge ' + badgeClass + ' me-1 rounded-circle">' + data + '</span> View Items' +
                           '</button>';
                }},
                {data: 'description', name: 'description', render: function(data) {
                    return data ? '<span class="text-muted">' + data + '</span>' : '<span class="text-muted fst-italic">No description</span>';
                }},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end'},
            ]
        });
        
        // Setup delete action handling
        $('#categories-table').on('click', '.btn-delete', function(e) {
            e.preventDefault();
            if(confirm('Are you sure you want to delete this category?')) {
                $(this).closest('form').submit();
            }
        });

        function format(data) {
            let category = data.category;
            let items = data.items;
            
            if (items.length === 0) {
                return `<div class="p-3 bg-light rounded-3 text-center border-start border-3 border-danger m-2">
                    <p class="text-muted mb-0"><i class="bi bi-inbox me-2"></i>No items found in this category.</p>
                </div>`;
            }
            
            let tableHeader = '';
            let tableRows = '';
            
            if (category.type === 'asset') {
                tableHeader = `
                    <thead>
                        <tr class="table-light">
                            <th width="10%">No</th>
                            <th width="20%">Asset Code</th>
                            <th width="25%">Asset Name</th>
                            <th width="20%">Location</th>
                            <th width="15%">Holder</th>
                            <th width="10%">Status</th>
                        </tr>
                    </thead>
                `;
                
                items.forEach((item, index) => {
                    let badgeClass = 'bg-secondary';
                    if(item.status === 'In Use') badgeClass = 'bg-success';
                    if(item.status === 'In Stock') badgeClass = 'bg-primary';
                    if(item.status === 'Maintenance') badgeClass = 'bg-warning text-dark';
                    if(item.status === 'Retired') badgeClass = 'bg-danger';
                    
                    tableRows += `
                        <tr>
                            <td>${index + 1}</td>
                            <td><span class="fw-bold text-dark font-monospace">${item.code}</span></td>
                            <td class="fw-semibold">${item.name}</td>
                            <td>${item.location ? item.location.name : '-'}</td>
                            <td>${item.assigned_to ? '<span class="fw-medium text-dark"><i class="bi bi-person me-1"></i>' + item.assigned_to + '</span>' : '<span class="text-muted fst-italic">-</span>'}</td>
                            <td><span class="badge ${badgeClass}">${item.status}</span></td>
                        </tr>
                    `;
                });
            } else {
                // Consumable
                tableHeader = `
                    <thead>
                        <tr class="table-light">
                            <th width="10%">No</th>
                            <th width="45%">Item Name</th>
                            <th width="25%">Supplier</th>
                            <th width="20%">Current Stock</th>
                        </tr>
                    </thead>
                `;
                
                items.forEach((item, index) => {
                    let stockClass = 'text-dark';
                    if (item.stock <= item.min_stock) {
                        stockClass = 'text-danger fw-bold';
                    }
                    
                    tableRows += `
                        <tr>
                            <td>${index + 1}</td>
                            <td class="fw-semibold">${item.name}</td>
                            <td>${item.supplier ? item.supplier.name : '-'}</td>
                            <td>
                                <span class="${stockClass}">${item.stock}</span> 
                                <small class="text-muted">${item.unit || 'pcs'}</small>
                                ${item.stock <= item.min_stock ? '<span class="badge bg-danger ms-1" style="font-size: 0.7rem;">Low Stock</span>' : ''}
                            </td>
                        </tr>
                    `;
                });
            }
            
            let manageUrl = category.type === 'asset' 
                ? `/assets?category_id=${category.id}` 
                : `/consumables?category_id=${category.id}`;
            let btnLabel = category.type === 'asset' ? 'Manage Assets' : 'Manage Consumables';
            let btnClass = category.type === 'asset' ? 'btn-outline-primary' : 'btn-outline-info';
            let icon = category.type === 'asset' ? 'bi-box-seam' : 'bi-basket';

            return `
                <div class="p-3 bg-light rounded shadow-sm border-start border-3 border-danger m-2">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0 text-danger">
                            <i class="bi bi-list-task me-1"></i> Items in Category: "${category.name}"
                        </h6>
                        <a href="${manageUrl}" class="btn btn-sm ${btnClass} fw-bold" style="padding: 4px 12px; font-size: 0.8rem; border-radius: 6px;">
                            <i class="bi ${icon} me-1"></i> ${btnLabel} Page <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="table-responsive bg-white rounded shadow-sm border">
                        <table class="table table-sm table-hover align-middle mb-0 py-2">
                            ${tableHeader}
                            <tbody>
                                ${tableRows}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        }

        // View Items child row toggle handler
        $('#categories-table tbody').on('click', '.view-category-items', function (e) {
            e.preventDefault();
            let tr = $(this).closest('tr');
            let table = $('#categories-table').DataTable();
            let row = table.row(tr);
            let id = $(this).data('id');
            let btn = tr.find('button.view-category-items');
            
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                
                if (btn.length) {
                    let badgeEl = btn.find('.badge');
                    let count = badgeEl.text();
                    let badgeClass = badgeEl.hasClass('bg-primary') ? 'bg-primary' : 'bg-info';
                    btn.html('<span class="badge ' + badgeClass + ' me-1 rounded-circle">' + count + '</span> View Items');
                    btn.removeClass('btn-secondary text-white').addClass('btn-light text-dark');
                }
            } else {
                // Open this row
                row.child(`
                    <div class="p-3 bg-light rounded-3 border-start border-3 border-danger m-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="spinner-border spinner-border-sm text-danger" role="status" aria-hidden="true"></span>
                            <span class="text-muted">Loading items...</span>
                        </div>
                    </div>
                `).show();
                tr.addClass('shown');
                
                if (btn.length) {
                    let badgeEl = btn.find('.badge');
                    let count = badgeEl.text();
                    let badgeClass = badgeEl.hasClass('bg-primary') ? 'bg-primary' : 'bg-info';
                    btn.html('<span class="badge ' + badgeClass + ' me-1 rounded-circle">' + count + '</span> Hide Items');
                    btn.addClass('btn-secondary text-white').removeClass('btn-light text-dark');
                }
                
                // Fetch the actual data
                fetch('/categories/' + id + '/items')
                    .then(response => response.json())
                    .then(data => {
                        row.child(format(data)).show();
                    })
                    .catch(err => {
                        console.error('Error fetching details:', err);
                        row.child(`
                            <div class="p-3 bg-light rounded-3 border-start border-3 border-danger m-2 text-danger">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> Failed to load items.
                            </div>
                        `).show();
                    });
            }
        });
    });
</script>
@endsection
