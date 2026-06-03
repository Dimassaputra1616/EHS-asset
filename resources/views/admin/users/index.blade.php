@extends('layouts.app')

@section('title', 'Manage Users')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush

@section('content')
<div class="card border-0 shadow-sm mt-3 animate-fade-in">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-0 fw-bold"><i class="bi bi-people me-2 text-hse-red"></i> Users</h5>
            <small class="text-muted">Manage system users and access</small>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-hse-red fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Add User
        </a>
    </div>
    <div class="card-body">
        <div>
            <table class="table table-hover align-middle w-100 dt-responsive nowrap" id="users-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
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
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.users.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name', render: function(data) { return '<span class="fw-bold">' + data + '</span>'; }},
                {data: 'email', name: 'email'},
                {data: 'role', name: 'role', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            language: {
                search: "",
                searchPlaceholder: "Search users..."
            },
            drawCallback: function() {
                $('.dataTables_paginate > .pagination').addClass('pagination-sm');
            }
        });

        // Instant dynamic role changes via AJAX PUT
        $(document).on('change', '.role-select', function() {
            const selectEl = $(this);
            const userId = selectEl.data('user-id');
            const selectedRole = selectEl.val();
            
            // Disable dropdown temporarily to block concurrent requests
            selectEl.prop('disabled', true);
            
            $.ajax({
                url: `/admin/users/${userId}/role`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT',
                    role: selectedRole
                },
                success: function(response) {
                    selectEl.prop('disabled', false);
                    if (response.success) {
                        showRoleToast('success', response.message);
                        
                        // Dynamically update the pill badge colors instantly
                        selectEl.removeClass('role-super-admin role-admin role-staff role-other');
                        if (selectedRole === 'super admin') {
                            selectEl.addClass('role-super-admin');
                        } else if (selectedRole === 'admin') {
                            selectEl.addClass('role-admin');
                        } else if (selectedRole === 'staff') {
                            selectEl.addClass('role-staff');
                        } else {
                            selectEl.addClass('role-other');
                        }
                    } else {
                        showRoleToast('danger', response.message || 'Failed to update role.');
                        $('#users-table').DataTable().ajax.reload(null, false);
                    }
                },
                error: function(xhr) {
                    selectEl.prop('disabled', false);
                    const errMsg = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred while updating the role.';
                    showRoleToast('danger', errMsg);
                    $('#users-table').DataTable().ajax.reload(null, false);
                }
            });
        });

        // Premium Custom Toast Notifications
        function showRoleToast(type, message) {
            const toastId = 'toast-' + Date.now();
            const icon = type === 'success' ? 'bi-check-circle-fill text-success' : 'bi-exclamation-triangle-fill text-danger';
            const borderColor = type === 'success' ? 'rgba(46, 204, 113, 0.4)' : 'rgba(231, 76, 60, 0.4)';
            
            const toastHTML = `
                <div id="${toastId}" class="toast align-items-center text-dark bg-white border shadow-lg show" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; top: 24px; right: 24px; z-index: 10000; border-radius: 14px; min-width: 320px; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); border-color: ${borderColor} !important; border-width: 1.5px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important;">
                    <div class="d-flex p-3 align-items-center">
                        <i class="bi ${icon} fs-5 me-3"></i>
                        <div class="toast-body fw-bold small flex-grow-1 text-secondary" style="font-size: 0.85rem;">${message}</div>
                        <button type="button" class="btn-close ms-2" data-bs-dismiss="toast" aria-label="Close" onclick="document.getElementById('${toastId}').remove()"></button>
                    </div>
                </div>
            `;
            
            $('body').append(toastHTML);
            
            // Auto fade out
            setTimeout(() => {
                const el = document.getElementById(toastId);
                if (el) {
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(-12px)';
                    setTimeout(() => el.remove(), 300);
                }
            }, 3500);
        }
    });
</script>
@endpush
