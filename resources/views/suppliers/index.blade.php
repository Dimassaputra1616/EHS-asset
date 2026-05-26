@extends('layouts.app')

@section('title', 'Manage Suppliers')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush

@section('content')
<div class="card border-0 shadow-sm mt-3 animate-fade-in">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-0 fw-bold"><i class="bi bi-truck me-2 text-hse-red"></i> Suppliers</h5>
            <small class="text-muted">Manage vendors and partner details</small>
        </div>
        <a href="{{ route('suppliers.create') }}" class="btn btn-hse-red fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Supplier
        </a>
    </div>
    <div class="card-body">
        <div>
            <table class="table table-hover align-middle w-100 dt-responsive nowrap" id="suppliers-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Supplier Name</th>
                        <th>Contact Person</th>
                        <th>Phone</th>
                        <th>Email</th>
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
        $('#suppliers-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('suppliers.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name', render: function(data) { return '<span class="fw-bold text-primary">' + data + '</span>'; }},
                {data: 'contact_person', name: 'contact_person'},
                {data: 'phone', name: 'phone'},
                {data: 'email', name: 'email', render: function(data) { return data ? '<a href="mailto:'+data+'">'+data+'</a>' : '-'; }},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            language: {
                search: "",
                searchPlaceholder: "Search suppliers..."
            },
            drawCallback: function() {
                $('.dataTables_paginate > .pagination').addClass('pagination-sm');
            }
        });
    });
</script>
@endpush
