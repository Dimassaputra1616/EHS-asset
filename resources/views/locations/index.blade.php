@extends('layouts.app')

@section('title', 'Locations')

@section('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <div class="d-flex align-items-center gap-2">
            <div class="p-2 bg-hse-red bg-opacity-10 text-hse-red rounded">
                <i class="bi bi-geo-alt fs-5"></i>
            </div>
            <div>
                <h5 class="card-title mb-0 fw-bold">Master Locations</h5>
                <small class="text-muted">Manage building locations, floors, and rooms</small>
            </div>
        </div>
        <a href="{{ route('locations.create') }}" class="btn btn-hse-red fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Location
        </a>
    </div>
    <div class="card-body">
        <div>
            <table class="table table-hover align-middle w-100 dt-responsive nowrap" id="locations-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Building</th>
                        <th width="10%">Floor</th>
                        <th width="20%">Room Name</th>
                        <th width="35%">Description</th>
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
        $('#locations-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('locations.index') }}",
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search locations...",
                paginate: {
                    previous: "<i class='bi bi-chevron-left'></i>",
                    next: "<i class='bi bi-chevron-right'></i>"
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'building', name: 'building', render: function(data) {
                    return data ? '<span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1"><i class="bi bi-building me-1"></i> ' + data + '</span>' : '-';
                }},
                {data: 'floor', name: 'floor', render: function(data) {
                    return data ? '<span class="badge bg-dark bg-opacity-10 text-dark border px-2 py-1">Lv. ' + data + '</span>' : '-';
                }},
                {data: 'name', name: 'name', render: function(data) {
                    return '<div class="fw-bold text-dark"><i class="bi bi-geo text-danger bg-danger bg-opacity-10 p-1 rounded me-1"></i> ' + data + '</div>';
                }},
                {data: 'description', name: 'description', render: function(data) {
                    return data ? '<span class="text-muted">' + data + '</span>' : '<span class="text-muted fst-italic">No description</span>';
                }},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end'},
            ]
        });
        
        // Setup delete action handling
        $('#locations-table').on('click', '.btn-delete', function(e) {
            e.preventDefault();
            if(confirm('Are you sure you want to delete this location?')) {
                $(this).closest('form').submit();
            }
        });
    });
</script>
@endsection
