@extends('layouts.app')

@section('title', 'Barang Masuk (Stock In)')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush

@section('content')
<div class="card border-0 shadow-sm mt-3 animate-fade-in">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-0 fw-bold"><i class="bi bi-box-arrow-in-down me-2 text-success"></i> Barang Masuk (Stock In)</h5>
            <small class="text-muted">History of replenished consumable stocks</small>
        </div>
        <a href="{{ route('consumables.transactions.create', ['type' => 'in']) }}" class="btn btn-success fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Record Stock In
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100 dt-responsive nowrap" id="transactions-in-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Masuk</th>
                        <th>Operator</th>
                        <th>Catatan</th>
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
        $('#transactions-in-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('consumables.transactions.in') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'formatted_date', name: 'date'},
                {data: 'consumable_name', name: 'consumable.name', render: function(data) {
                    return '<span class="fw-medium text-dark">' + data + '</span>';
                }},
                {data: 'formatted_quantity', name: 'quantity'},
                {data: 'user_name', name: 'user.name'},
                {data: 'notes', name: 'notes', render: function(data) {
                    return data ? data : '<span class="text-muted fst-italic">-</span>';
                }}
            ],
            language: {
                search: "",
                searchPlaceholder: "Search records..."
            },
            drawCallback: function() {
                $('.dataTables_paginate > .pagination').addClass('pagination-sm');
            }
        });
    });
</script>
@endpush
