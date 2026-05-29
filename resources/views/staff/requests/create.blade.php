@extends('layouts.app')

@section('title', 'Buat Pengajuan Alat')

@section('styles')
<style>
    .form-option-card {
        border: 2px solid rgba(0, 0, 0, 0.05);
        border-radius: 14px;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .form-option-card:hover {
        border-color: rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }
    .form-option-card.active-red {
        border-color: #dc3545 !important;
        background-color: rgba(220, 53, 69, 0.03);
    }
    .form-option-card.active-green {
        border-color: #198754 !important;
        background-color: rgba(25, 135, 84, 0.03);
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center mt-4 mb-5">
    <div class="col-12 col-md-8">
        <div class="card border-0 shadow-sm rounded-4 animate-fade-in">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                <a href="{{ route('staff.requests.index') }}" class="btn btn-sm btn-outline-secondary border-0 rounded-circle p-2">
                    <i class="bi bi-arrow-left fs-5 d-block"></i>
                </a>
                <div>
                    <h5 class="card-title mb-0 fw-bold">Buat Pengajuan Alat keselamatan</h5>
                    <small class="text-muted">Isi formulir untuk mengajukan APD atau Alat Ketinggian/Gas Detector</small>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('staff.requests.store') }}" method="POST" id="request-form">
                    @csrf
                    
                    <!-- Pilihan Tipe -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark mb-2">Pilih Kategori Pengajuan:</label>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="form-option-card p-3 text-center active-red" id="option-fixed-asset">
                                    <input class="form-check-input d-none" type="radio" name="request_type" id="type-fixed" value="fixed_asset" checked>
                                    <i class="bi bi-box-seam fs-2 text-danger d-block mb-1"></i>
                                    <span class="fw-bold text-dark d-block">Fixed Asset</span>
                                    <small class="text-muted d-block" style="font-size: 0.72rem;">Peralatan keselamatan pinjam pakai (Kembali)</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-option-card p-3 text-center" id="option-consumable">
                                    <input class="form-check-input d-none" type="radio" name="request_type" id="type-consumable" value="consumable">
                                    <i class="bi bi-basket fs-2 text-success d-block mb-1"></i>
                                    <span class="fw-bold text-dark d-block">Consumables</span>
                                    <small class="text-muted d-block" style="font-size: 0.72rem;">Barang habis pakai / APD (Milik pribadi)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown Fixed Asset -->
                    <div class="mb-3" id="asset-selector-group">
                        <label for="asset_id" class="form-label fw-semibold text-secondary">Pilih Alat Keselamatan (Fixed Asset)</label>
                        <select class="form-select border-secondary border-opacity-15 p-2.5 rounded-3" id="asset_id" name="asset_id" style="font-size: 0.92rem;">
                            <option value="">-- Pilih Alat --</option>
                            @foreach($assets as $ast)
                                <option value="{{ $ast->id }}">{{ $ast->name }} - {{ $ast->code }}</option>
                            @endforeach
                        </select>
                        <div class="form-text small">Hanya menampilkan alat keselamatan yang saat ini tersedia (*tidak sedang dipinjam*).</div>
                    </div>

                    <!-- Dropdown Consumables -->
                    <div class="mb-3" id="consumable-selector-group" style="display: none;">
                        <label for="consumable_id" class="form-label fw-semibold text-secondary">Pilih Barang APD (Consumable)</label>
                        <select class="form-select border-secondary border-opacity-15 p-2.5 rounded-3" id="consumable_id" name="consumable_id" style="font-size: 0.92rem;">
                            <option value="">-- Pilih APD --</option>
                            @foreach($consumables as $cns)
                                <option value="{{ $cns->id }}">{{ $cns->name }} - Stok: {{ $cns->stock }}</option>
                            @endforeach
                        </select>
                        <div class="form-text small">Stok barang akan otomatis dipotong saat pengajuan disetujui.</div>
                    </div>

                    <!-- Jumlah Qty -->
                    <div class="mb-3">
                        <label for="qty" class="form-label fw-semibold text-secondary">Jumlah (Quantity)</label>
                        <input type="number" class="form-control border-secondary border-opacity-15 p-2.5 rounded-3 fw-bold" id="qty" name="qty" value="1" min="1" required style="font-size: 0.92rem;">
                    </div>

                    <!-- Tujuan Penggunaan -->
                    <div class="mb-4">
                        <label for="purpose" class="form-label fw-semibold text-secondary">Tujuan Penggunaan (Purpose)</label>
                        <textarea class="form-control border-secondary border-opacity-15 p-2.5 rounded-3" id="purpose" name="purpose" rows="3" placeholder="Contoh: Pekerjaan maintenance listrik di ketinggian tangki minyak" required style="font-size: 0.92rem;"></textarea>
                    </div>

                    <!-- Button Kirim -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary fw-bold p-3" style="border-radius: 12px;">
                            <i class="bi bi-send-fill me-1"></i> Kirim Pengajuan Alat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
    $(function() {
        // Toggle Active Card Selections
        $('#option-fixed-asset').on('click', function() {
            $('#type-fixed').prop('checked', true);
            $(this).addClass('active-red');
            $('#option-consumable').removeClass('active-green');
            
            $('#asset-selector-group').slideDown(250);
            $('#consumable-selector-group').slideUp(200);
            
            // Set required attributes
            $('#asset_id').prop('required', true);
            $('#consumable_id').prop('required', false).val('');
        });

        $('#option-consumable').on('click', function() {
            $('#type-consumable').prop('checked', true);
            $(this).addClass('active-green');
            $('#option-fixed-asset').removeClass('active-red');
            
            $('#consumable-selector-group').slideDown(250);
            $('#asset-selector-group').slideUp(200);
            
            // Set required attributes
            $('#consumable_id').prop('required', true);
            $('#asset_id').prop('required', false).val('');
        });

        // Initialize state
        $('#asset_id').prop('required', true);
    });
</script>
@endsection
