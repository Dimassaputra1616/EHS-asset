@extends('layouts.app')

@section('title', 'Laporkan Kerusakan')

@section('styles')
<style>
    .type-tab {
        cursor: pointer;
        padding: 12px;
        text-align: center;
        border: 2px solid rgba(0,0,0,0.05);
        border-radius: 12px;
        font-weight: 700;
        transition: all 0.2s ease;
    }
    .type-tab:hover {
        border-color: rgba(0,0,0,0.15);
    }
    .type-tab.active-tab {
        border-color: #dc3545 !important;
        background-color: rgba(220, 53, 69, 0.03);
        color: #dc3545 !important;
    }
    
    .urgency-badge-opt {
        cursor: pointer;
        padding: 12px;
        text-align: center;
        border: 2px solid rgba(0,0,0,0.05);
        border-radius: 12px;
        font-weight: 700;
        transition: all 0.2s ease;
    }
    .urgency-badge-opt:hover {
        border-color: rgba(0,0,0,0.15);
    }
    .urgency-badge-opt.active-low {
        border-color: #0dcaf0 !important;
        background-color: rgba(13, 202, 240, 0.04);
        color: #0dcaf0 !important;
    }
    .urgency-badge-opt.active-medium {
        border-color: #ffc107 !important;
        background-color: rgba(255, 193, 7, 0.04);
        color: #ffc107 !important;
    }
    .urgency-badge-opt.active-high {
        border-color: #dc3545 !important;
        background-color: rgba(220, 53, 69, 0.04);
        color: #dc3545 !important;
    }
    
    .photo-preview-box {
        border: 2px dashed rgba(0, 0, 0, 0.15);
        border-radius: 14px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 180px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: all 0.2s ease;
    }
    .photo-preview-box:hover {
        border-color: #dc3545;
        background: rgba(220, 53, 69, 0.01);
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center mt-4 mb-5">
    <div class="col-12 col-md-8">
        <div class="card border-0 shadow-sm rounded-4 animate-fade-in">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                <a href="{{ route('staff.damage_reports.index') }}" class="btn btn-sm btn-outline-secondary border-0 rounded-circle p-2">
                    <i class="bi bi-arrow-left fs-5 d-block"></i>
                </a>
                <div>
                    <h5 class="card-title mb-0 fw-bold">Buat Laporan Kerusakan Alat</h5>
                    <small class="text-muted">Ajukan laporan kerusakan APD, gas detector, APAR, atau temuan lapangan lainnya</small>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('staff.damage_reports.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Klasifikasi Barang -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark mb-2">Klasifikasi Alat:</label>
                        <div class="row g-2">
                            <div class="col-4">
                                <div class="type-tab active-tab" id="tab-fixed">
                                    <input class="d-none" type="radio" name="item_type" id="item-fixed" value="fixed_asset" checked>
                                    <i class="bi bi-box-seam d-block mb-1 fs-5"></i> Fixed Asset
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="type-tab" id="tab-consumable">
                                    <input class="d-none" type="radio" name="item_type" id="item-consumable" value="consumable">
                                    <i class="bi bi-basket d-block mb-1 fs-5"></i> APD
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="type-tab" id="tab-other">
                                    <input class="d-none" type="radio" name="item_type" id="item-other" value="other">
                                    <i class="bi bi-gear-wide-connected d-block mb-1 fs-5"></i> Umum
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dropdowns / Text Input based on classification -->
                    <div class="mb-3" id="select-fixed-group">
                        <label for="asset_id" class="form-label fw-semibold text-secondary">Pilih Alat keselamatan (Fixed Asset)</label>
                        <select class="form-select border-secondary border-opacity-15 p-2.5 rounded-3" id="asset_id" name="asset_id">
                            <option value="">-- Pilih Alat --</option>
                            @foreach($assets as $ast)
                                <option value="{{ $ast->id }}">{{ $ast->name }} - {{ $ast->code }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3" id="select-consumable-group" style="display: none;">
                        <label for="consumable_id" class="form-label fw-semibold text-secondary">Pilih Barang APD (Consumables)</label>
                        <select class="form-select border-secondary border-opacity-15 p-2.5 rounded-3" id="consumable_id" name="consumable_id">
                            <option value="">-- Pilih APD --</option>
                            @foreach($consumables as $cns)
                                <option value="{{ $cns->id }}">{{ $cns->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3" id="input-other-group" style="display: none;">
                        <label for="item_name" class="form-label fw-semibold text-secondary">Nama Alat / Temuan Lapangan</label>
                        <input type="text" class="form-control border-secondary border-opacity-15 p-2.5 rounded-3" id="item_name" name="item_name" placeholder="Contoh: APAR Selasar Gedung A, Pintu Emergency Timur">
                    </div>

                    <!-- Urgency Level -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark mb-2">Tingkat Urgensi Kerusakan:</label>
                        <div class="row g-2">
                            <div class="col-4">
                                <div class="urgency-badge-opt" id="urg-low">
                                    <input class="d-none" type="radio" name="urgency" id="urg-val-low" value="low">
                                    <i class="bi bi-info-circle-fill me-1"></i> Rendah
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="urgency-badge-opt active-medium" id="urg-medium">
                                    <input class="d-none" type="radio" name="urgency" id="urg-val-med" value="medium" checked>
                                    <i class="bi bi-exclamation-circle-fill me-1"></i> Sedang
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="urgency-badge-opt" id="urg-high">
                                    <input class="d-none" type="radio" name="urgency" id="urg-val-high" value="high">
                                    <i class="bi bi-shield-fill-x me-1"></i> Tinggi
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Foto Temuan -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark mb-2">Unggah Foto Kerusakan (Optional):</label>
                        <input type="file" class="d-none" id="photo" name="photo" accept="image/*">
                        <div class="photo-preview-box text-center p-3" id="photo-dropzone">
                            <div id="preview-placeholder">
                                <i class="bi bi-cloud-upload fs-1 text-muted d-block mb-2"></i>
                                <span class="fw-bold text-dark d-block">Pilih Foto atau Ambil Kamera</span>
                                <small class="text-muted">Mendukung format JPG, PNG, WEBP (Maksimal 2MB)</small>
                            </div>
                            <img id="photo-preview-element" src="#" alt="Pratinjau Foto" style="display: none; max-width: 100%; max-height: 100%; object-fit: contain; position: absolute; top:0; left:0; right:0; bottom:0; padding: 10px;">
                        </div>
                    </div>

                    <!-- Deskripsi Kerusakan -->
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold text-dark mb-2">Deskripsi Kerusakan / Temuan Lapangan:</label>
                        <textarea class="form-control border-secondary border-opacity-15 p-3 rounded-3" id="description" name="description" rows="4" placeholder="Jelaskan secara detail kondisi kerusakan alat (Contoh: Kunci pengait harness retak dan tidak bisa mengunci rapat saat ditarik beban)" required></textarea>
                    </div>

                    <!-- Button Submit -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger fw-bold p-3" style="border-radius: 12px; background-color: #dc3545; border-color: #dc3545;">
                            <i class="bi bi-megaphone-fill me-1"></i> Kirim Laporan Kerusakan
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
        // Toggle Classification Tabs
        $('#tab-fixed').on('click', function() {
            $('#item-fixed').prop('checked', true);
            $(this).addClass('active-tab');
            $('#tab-consumable, #tab-other').removeClass('active-tab');
            
            $('#select-fixed-group').slideDown(250);
            $('#select-consumable-group, #input-other-group').slideUp(200);
            
            $('#asset_id').prop('required', true);
            $('#consumable_id, #item_name').prop('required', false).val('');
        });

        $('#tab-consumable').on('click', function() {
            $('#item-consumable').prop('checked', true);
            $(this).addClass('active-tab');
            $('#tab-fixed, #tab-other').removeClass('active-tab');
            
            $('#select-consumable-group').slideDown(250);
            $('#select-fixed-group, #input-other-group').slideUp(200);
            
            $('#consumable_id').prop('required', true);
            $('#asset_id, #item_name').prop('required', false).val('');
        });

        $('#tab-other').on('click', function() {
            $('#item-other').prop('checked', true);
            $(this).addClass('active-tab');
            $('#tab-fixed, #tab-consumable').removeClass('active-tab');
            
            $('#input-other-group').slideDown(250);
            $('#select-fixed-group, #select-consumable-group').slideUp(200);
            
            $('#item_name').prop('required', true);
            $('#asset_id, #consumable_id').prop('required', false).val('');
        });

        // Toggle Urgency Badges
        $('#urg-low').on('click', function() {
            $('#urg-val-low').prop('checked', true);
            $(this).addClass('active-low');
            $('#urg-medium, #urg-high').removeClass('active-medium active-high');
        });

        $('#urg-medium').on('click', function() {
            $('#urg-val-med').prop('checked', true);
            $(this).addClass('active-medium');
            $('#urg-low, #urg-high').removeClass('active-low active-high');
        });

        $('#urg-high').on('click', function() {
            $('#urg-val-high').prop('checked', true);
            $(this).addClass('active-high');
            $('#urg-low, #urg-medium').removeClass('active-low active-medium');
        });

        // Trigger file input click on dropzone click
        $('#photo-dropzone').on('click', function() {
            $('#photo').click();
        });

        // Handle file select and preview image
        $('#photo').on('change', function(e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    $('#photo-preview-element').attr('src', event.target.result).fadeIn(200);
                    $('#preview-placeholder').fadeOut(100);
                }
                reader.readAsDataURL(file);
            }
        });

        // Initialize state
        $('#asset_id').prop('required', true);
    });
</script>
@endsection
