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
</style>
@endsection

@section('content')
<div class="card border-0 shadow-sm mt-3 animate-fade-in">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <div class="p-2 bg-hse-red bg-opacity-10 text-hse-red rounded">
                <i class="bi bi-clock-history fs-5"></i>
            </div>
            <div>
                <h5 class="card-title mb-0 fw-bold">System Activity Logs</h5>
                <small class="text-muted">Track user logins, logouts, asset changes, and configuration updates</small>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100 dt-responsive nowrap" id="logs-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Timestamp</th>
                        <th width="15%">User</th>
                        <th width="15%">Activity</th>
                        <th width="30%">Description</th>
                        <th width="10%">IP Address</th>
                        <th width="10%">User Agent</th>
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
        $('#logs-table').DataTable({
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
                }}
            ]
        });
    });
</script>
@endsection
