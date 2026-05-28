@extends('layouts.app')

@section('title', 'Lakukan Stock Opname')

@section('content')
<div class="row justify-content-center animate-fade-in">
    <div class="col-md-9 col-lg-8">
        <div class="card border-0 shadow-sm mt-3" style="border-radius: 16px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                <a href="{{ route('stock-opnames.index') }}" class="btn btn-action me-3" title="Kembali">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h5 class="card-title mb-0 fw-bold"><i class="bi bi-clipboard2-check me-2 text-hse-red"></i> Form Stock Opname</h5>
                    <small class="text-muted">Pemeriksaan fisik langsung stok barang habis pakai (consumables)</small>
                </div>
            </div>
            
            <div class="card-body p-4">
                <form action="{{ route('stock-opnames.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <!-- Step 1: Select Consumable -->
                        <div class="col-12">
                            <label for="consumable_id" class="form-label small fw-bold text-muted">PILIH BARANG CONSUMABLE</label>
                            <select name="consumable_id" id="consumable_id" class="form-select form-select-lg @error('consumable_id') is-invalid @enderror" style="border-radius: 10px; height: 50px;" required>
                                <option value="" selected disabled>Pilih barang...</option>
                                @foreach($consumables as $item)
                                    <option value="{{ $item->id }}" data-stock="{{ $item->stock }}" data-unit="{{ $item->unit }}" {{ old('consumable_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }} ({{ $item->code }}) — Stok Sistem: {{ $item->stock }} {{ $item->unit }}
                                    </option>
                                @endforeach
                            </select>
                            @error('consumable_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Step 2: Real-time Live Calculation Panel (Snappy UI) -->
                        <div class="col-12 d-none" id="calc-panel">
                            <div class="border rounded-4 p-4" style="background: rgba(15, 23, 42, 0.02); border-color: rgba(15, 23, 42, 0.08) !important;">
                                <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-calculator me-2 text-hse-red"></i> Kalkulasi Selisih Audit</h6>
                                <div class="row g-3">
                                    <!-- Card A: Current Stock in Database -->
                                    <div class="col-6 col-md-4">
                                        <div class="bg-white border rounded-3 p-3 text-center shadow-sm">
                                            <span class="text-muted small fw-semibold d-block mb-1">Stok Sistem</span>
                                            <h4 class="fw-bold mb-0 text-dark" id="calc-system-stock">0</h4>
                                        </div>
                                    </div>
                                    <!-- Card B: Physical Quantity Entered -->
                                    <div class="col-6 col-md-4">
                                        <div class="bg-white border rounded-3 p-3 text-center shadow-sm">
                                            <span class="text-muted small fw-semibold d-block mb-1">Stok Fisik</span>
                                            <h4 class="fw-bold mb-0 text-dark" id="calc-physical-stock">0</h4>
                                        </div>
                                    </div>
                                    <!-- Card C: Difference -->
                                    <div class="col-12 col-md-4">
                                        <div id="calc-diff-card" class="bg-white border rounded-3 p-3 text-center shadow-sm">
                                            <span class="text-muted small fw-semibold d-block mb-1">Selisih Persediaan</span>
                                            <h4 class="fw-bold mb-0" id="calc-difference">0</h4>
                                        </div>
                                    </div>
                                    <!-- Helper status info -->
                                    <div class="col-12">
                                        <div id="calc-status-alert" class="alert d-flex align-items-center mb-0 border-0 p-3" style="border-radius: 10px;">
                                            <i class="bi me-2 fs-5" id="calc-status-icon"></i>
                                            <span class="small fw-semibold" id="calc-status-text">Stok siap diperiksa.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Enter Physical Stock -->
                        <div class="col-md-6">
                            <label for="physical_stock" class="form-label small fw-bold text-muted">JUMLAH STOK FISIK DI LAPANGAN</label>
                            <div class="input-group">
                                <input type="number" name="physical_stock" id="physical_stock" class="form-control form-control-lg @error('physical_stock') is-invalid @enderror" style="border-top-left-radius: 10px; border-bottom-left-radius: 10px; height: 50px;" min="0" value="{{ old('physical_stock') }}" required disabled placeholder="Pilih barang terlebih dahulu">
                                <span class="input-group-text bg-light text-muted small fw-bold" id="item-unit" style="border-top-right-radius: 10px; border-bottom-right-radius: 10px;">pcs</span>
                            </div>
                            @error('physical_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text small">Masukkan jumlah fisik barang yang dihitung nyata saat pemeriksaan.</div>
                        </div>

                        <!-- Step 4: Audit Date -->
                        <div class="col-md-6">
                            <label for="opname_date" class="form-label small fw-bold text-muted">TANGGAL AUDIT</label>
                            <input type="date" name="opname_date" id="opname_date" class="form-control form-control-lg @error('opname_date') is-invalid @enderror" style="border-radius: 10px; height: 50px;" value="{{ old('opname_date', date('Y-m-d')) }}" required>
                            @error('opname_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Step 5: Remarks / Notes -->
                        <div class="col-12">
                            <label for="notes" class="form-label small fw-bold text-muted">CATATAN PEMERIKSAAN (REMARKS)</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" style="border-radius: 10px; min-height: 100px;" placeholder="Tulis catatan jika ada selisih, misalnya: '3 box basah / rusak di rak C' atau 'Kehilangan kunci gudang'.">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('stock-opnames.index') }}" class="btn btn-light px-4 py-2.5 fw-bold" style="border-radius: 10px;">Batal</a>
                        <button type="submit" class="btn btn-hse-red px-5 py-2.5 fw-bold shadow-sm" style="border-radius: 10px;">
                            <i class="bi bi-cloud-arrow-up me-1"></i> Simpan Hasil Opname
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        var consumableSelect = $('#consumable_id');
        var physicalInput = $('#physical_stock');
        var unitLabel = $('#item-unit');
        
        var calcPanel = $('#calc-panel');
        var calcSystem = $('#calc-system-stock');
        var calcPhysical = $('#calc-physical-stock');
        var calcDifference = $('#calc-difference');
        var calcDiffCard = $('#calc-diff-card');
        
        var calcAlert = $('#calc-status-alert');
        var calcIcon = $('#calc-status-icon');
        var calcText = $('#calc-status-text');

        function calculateDiff() {
            var selectedOpt = consumableSelect.find(':selected');
            if (!selectedOpt.val()) return;

            var sysStock = parseInt(selectedOpt.data('stock'));
            var unitName = selectedOpt.data('unit');
            var physStock = physicalInput.val() === "" ? 0 : parseInt(physicalInput.val());
            var diff = physStock - sysStock;
            
            calcSystem.text(sysStock + ' ' + unitName);
            calcPhysical.text(physStock + ' ' + unitName);
            
            var diffText = diff > 0 ? '+' + diff : diff;
            calcDifference.text(diffText + ' ' + unitName);

            // Dynamic Styling and Status Messages
            if (diff === 0) {
                // MATCH (Stok Sesuai)
                calcDiffCard.removeClass('border-danger text-danger border-success text-success').addClass('border-success text-success');
                calcAlert.removeClass('alert-danger alert-success alert-warning').addClass('alert-success bg-success bg-opacity-10 text-success');
                calcIcon.removeClass().addClass('bi bi-check-circle-fill fs-5 me-2');
                calcText.text('Luar biasa! Jumlah stok fisik sama persis dengan sistem di database.');
            } else if (diff < 0) {
                // DEFICIT (Selisih Kurang)
                calcDiffCard.removeClass('border-danger text-danger border-success text-success').addClass('border-danger text-danger');
                calcAlert.removeClass('alert-danger alert-success alert-warning').addClass('alert-danger bg-danger bg-opacity-10 text-danger');
                calcIcon.removeClass().addClass('bi bi-exclamation-octagon-fill fs-5 me-2');
                calcText.text('Stok Defisit! Ditemukan kekurangan sebanyak ' + Math.abs(diff) + ' ' + unitName + '. Stok di database akan disesuaikan berkurang saat disimpan.');
            } else {
                // SURPLUS (Selisih Lebih)
                calcDiffCard.removeClass('border-danger text-danger border-success text-success').addClass('border-success text-success');
                calcAlert.removeClass('alert-danger alert-success alert-warning').addClass('alert-warning bg-warning bg-opacity-10 text-warning-emphasis');
                calcIcon.removeClass().addClass('bi bi-info-circle-fill fs-5 me-2');
                calcText.text('Stok Surplus! Ditemukan kelebihan sebanyak ' + diff + ' ' + unitName + '. Stok di database akan disesuaikan bertambah saat disimpan.');
            }
        }

        // On Consumable Change
        consumableSelect.on('change', function() {
            var selectedOpt = $(this).find(':selected');
            if (selectedOpt.val()) {
                var unit = selectedOpt.data('unit');
                unitLabel.text(unit);
                physicalInput.prop('disabled', false).attr('placeholder', 'Masukkan jumlah ' + unit);
                
                // Show calculations panel
                calcPanel.removeClass('d-none');
                
                // Reset physical field if empty
                if (physicalInput.val() === "") {
                    physicalInput.val(0);
                }
                
                calculateDiff();
            } else {
                physicalInput.prop('disabled', true).val('').attr('placeholder', 'Pilih barang terlebih dahulu');
                calcPanel.addClass('d-none');
            }
        });

        // On Physical Quantity Input
        physicalInput.on('input changeKeyup', function() {
            calculateDiff();
        });
    });
</script>
@endpush
