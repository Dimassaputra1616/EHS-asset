@extends('layouts.app')

@section('title', 'Manage Roles')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
    /* Premium Table styling overrides */
    #roles-table {
        border-collapse: separate !important;
        border-spacing: 0 10px !important;
        background: transparent !important;
        margin-top: 8px !important;
    }
    #roles-table thead th {
        border: none !important;
        background: #f8fafc !important;
        color: #64748b !important;
        font-weight: 800 !important;
        text-transform: uppercase !important;
        font-size: 0.72rem !important;
        letter-spacing: 1px !important;
        padding: 14px 20px !important;
    }
    #roles-table tbody tr {
        background: #ffffff !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01), 0 2px 4px -1px rgba(0, 0, 0, 0.006) !important;
        border-radius: 14px !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    #roles-table tbody tr:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.06) !important;
        background: #fdfdfd !important;
    }
    #roles-table tbody td {
        border: none !important;
        padding: 16px 20px !important;
        border-top: 1.5px solid #f1f5f9 !important;
        border-bottom: 1.5px solid #f1f5f9 !important;
        color: #334155;
    }
    #roles-table tbody td:first-child {
        border-left: 1.5px solid #f1f5f9 !important;
        border-top-left-radius: 14px !important;
        border-bottom-left-radius: 14px !important;
        font-weight: 600;
        color: #64748b;
    }
    #roles-table tbody td:last-child {
        border-right: 1.5px solid #f1f5f9 !important;
        border-top-right-radius: 14px !important;
        border-bottom-right-radius: 14px !important;
    }
    
    /* Modern add button */
    .btn-add-role {
        border-radius: 12px !important;
        padding: 0.6rem 1.25rem !important;
        font-size: 0.9rem !important;
        letter-spacing: 0.3px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .btn-add-role:hover {
        transform: translateY(-1.5px);
        box-shadow: 0 8px 20px -6px rgba(192, 57, 43, 0.35) !important;
    }
</style>
@endpush

@section('content')
<div class="card border-0 shadow-sm mt-3 animate-fade-in" style="border-radius: 20px; overflow: hidden;">
    <div class="card-header bg-white py-4 border-0 px-4 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-hse-red text-white p-2 rounded-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: var(--hse-red-gradient);">
                <i class="bi bi-shield-lock fs-4"></i>
            </div>
            <div>
                <h5 class="card-title mb-0 fw-bold" style="font-size: 1.2rem; color: #1e293b;">Roles</h5>
                <p class="text-muted small mb-0">Manage user roles and permissions</p>
            </div>
        </div>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-hse-red btn-add-role fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Role
        </a>
    </div>
    <div class="card-body px-4 pb-4 pt-1">
        <div class="table-responsive" style="overflow: visible;">
            <table class="table align-middle w-100 dt-responsive" id="roles-table">
                <thead>
                    <tr>
                        <th width="8%">No</th>
                        <th>Role Name</th>
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

<script>
    $(function () {
        $('#roles-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.roles.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center'},
                {data: 'name', name: 'name', render: function(data) {
                    const role = data.toLowerCase().trim();
                    let badgeClass = '';
                    let icon = '';
                    let displayName = data.charAt(0).toUpperCase() + data.slice(1);
                    
                    if (role === 'admin' || role === 'administrator') {
                        badgeClass = 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20';
                        icon = '<i class="bi bi-shield-lock-fill me-1.5"></i>';
                        displayName = 'Administrator';
                    } else if (role === 'super admin' || role === 'superadmin') {
                        badgeClass = 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20';
                        icon = '<i class="bi bi-crown-fill me-1.5"></i>';
                        displayName = 'Super Admin';
                    } else if (role === 'staff') {
                        badgeClass = 'bg-info bg-opacity-10 text-info border border-info border-opacity-20';
                        icon = '<i class="bi bi-person-badge-fill me-1.5"></i>';
                        displayName = 'Staff';
                    } else if (role === 'karyawan' || role === 'employee') {
                        badgeClass = 'bg-success bg-opacity-10 text-success border border-success border-opacity-20';
                        icon = '<i class="bi bi-people-fill me-1.5"></i>';
                        displayName = 'Karyawan';
                    } else {
                        badgeClass = 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-20';
                        icon = '<i class="bi bi-tag-fill me-1.5"></i>';
                    }
                    
                    return `<span class="badge ${badgeClass} py-2 px-3 fw-bold rounded-pill" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase; box-shadow: 0 2px 4px rgba(0,0,0,0.015);">${icon}${displayName}</span>`;
                }},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
            ],
            language: {
                search: "",
                searchPlaceholder: "Search roles..."
            },
            drawCallback: function() {
                $('.dataTables_paginate > .pagination').addClass('pagination-sm');
            }
        });
    });
</script>
@endpush
