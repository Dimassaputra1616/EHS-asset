@extends('layouts.app')

@section('title', 'Manage Roles')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush

@section('content')
<div class="card border-0 shadow-sm mt-3 animate-fade-in">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-0 fw-bold"><i class="bi bi-shield-lock me-2 text-hse-red"></i> Roles</h5>
            <small class="text-muted">Manage user roles and permissions</small>
        </div>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-hse-red fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Role
        </a>
    </div>
    <div class="card-body">
        <div>
            <table class="table table-hover align-middle w-100 dt-responsive nowrap" id="roles-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
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
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name', render: function(data) {
                    if(data == 'admin') return '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 py-2 px-3 fw-bold"><i class="bi bi-shield-lock me-1"></i>Administrator</span>';
                    return '<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 py-2 px-3 fw-bold">' + data.charAt(0).toUpperCase() + data.slice(1) + '</span>';
                }},
                {data: 'action', name: 'action', orderable: false, searchable: false},
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
