@extends('layouts.app')

@section('title', 'Lakukan Stock Opname')

@push('css')
<style>
    /* Snappy Form Styles */
    .opname-container {
        max-width: 850px;
        margin: 0 auto;
    }
    
    .premium-card {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 24px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.03), 0 5px 15px rgba(0, 0, 0, 0.01);
        overflow: hidden;
    }

    .form-header-box {
        background: linear-gradient(135deg, #1e293b, #0f172a);
        color: #ffffff;
        padding: 30px 40px;
        position: relative;
    }

    .form-header-box::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #c0392b, #e74c3c, #f39c12);
    }

    .back-btn-modern {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.15);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        text-decoration: none;
    }

    .back-btn-modern:hover {
        background: #ffffff;
        color: #0f172a;
        transform: translateX(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .form-body-custom {
        padding: 40px;
    }

    /* Premium inputs styling */
    .custom-label {
        font-size: 0.78rem;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        font-weight: 800;
        color: #64748b;
        margin-bottom: 8px;
        display: block;
    }

    .input-premium-select, .input-premium-text {
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 18px;
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
        transition: all 0.25s ease;
        background-color: #f8fafc;
    }

    .input-premium-select:focus, .input-premium-text:focus {
        border-color: #ef4444;
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.12);
        outline: none;
    }

    /* Floating quick helpers */
    .quick-adjust-group {
        display: flex;
        gap: 8px;
        margin-top: 10px;
    }

    .btn-helper {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-helper:hover {
        background: #f1f5f9;
        color: #1e293b;
        border-color: #cbd5e1;
    }

    .btn-helper-active {
        background: rgba(239, 68, 68, 0.08);
        color: #ef4444;
        border-color: rgba(239, 68, 68, 0.2);
    }

    /* Snappy live panels */
    .live-calc-box {
        border-radius: 18px;
        padding: 24px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        margin-top: 10px;
        transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        transform: translateY(0);
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px;
        text-align: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        transition: all 0.25s ease;
    }

    .stat-card-title {
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 4px;
        display: block;
    }

    .stat-card-value {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
    }

    .calc-diff-card {
        border: 2px dashed #cbd5e1;
    }

    /* Alert indicators */
    .status-alert-box {
        border-radius: 12px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 15px;
        font-size: 0.82rem;
        font-weight: 600;
        line-height: 1.4;
    }

    /* Snappy animations */
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .snappy-show {
        animation: slideDown 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
</style>
@endpush

@section('content')
<div class="opname-container animate-fade-in">
    <div class="premium-card mt-3">
        <!-- Header Panel (Deep Cyber Slate) -->
        <div class="form-header-box d-flex align-items-center">
            <a href="{{ route('stock-opnames.index') }}" class="back-btn-modern me-4" title="Kembali">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <div>
                <h4 class="mb-1 fw-bold text-white"><i class="bi bi-clipboard2-check me-2 text-danger"></i> Lakukan Stock Opname</h4>
                <p class="text-white-50 mb-0 small">Audit pemeriksaan fisik langsung untuk menyelaraskan stok barang habis pakai (consumables)</p>
            </div>
        </div>
        
        <!-- Form Body -->
        <div class="form-body-custom">
            <form action="{{ route('stock-opnames.store') }}" method="POST" id="opname-form">
                @csrf
                
                <div class="row g-4">
                    <!-- Step 1: Select Consumable -->
                    <div class="col-12">
                        <label for="consumable_id" class="custom-label">PILIH BARANG CONSUMABLE</label>
                        <select name="consumable_id" id="consumable_id" class="form-select input-premium-select w-100 @error('consumable_id') is-invalid @enderror" style="height: 52px;" required>
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

                    <!-- Step 2: Snappy Live Panel -->
                    <div class="col-12 d-none" id="calc-panel">
                        <div class="live-calc-box snappy-show">
                            <h6 class="fw-bold mb-3 text-dark d-flex align-items-center"><i class="bi bi-calculator me-2 text-danger"></i> Hasil Selisih Audit Real-time</h6>
                            <div class="row g-3">
                                <!-- Card A: System Stock -->
                                <div class="col-6 col-md-4">
                                    <div class="stat-card">
                                        <span class="stat-card-title">Stok Sistem</span>
                                        <div class="stat-card-value text-muted" id="calc-system-stock">0</div>
                                    </div>
                                </div>
                                <!-- Card B: Physical Quantity Entered -->
                                <div class="col-6 col-md-4">
                                    <div class="stat-card" style="border-color: rgba(192, 57, 43, 0.15); background: rgba(192, 57, 43, 0.01);">
                                        <span class="stat-card-title text-danger">Stok Fisik</span>
                                        <div class="stat-card-value text-danger" id="calc-physical-stock">0</div>
                                    </div>
                                </div>
                                <!-- Card C: Difference -->
                                <div class="col-12 col-md-4">
                                    <div id="calc-diff-card" class="stat-card calc-diff-card">
                                        <span class="stat-card-title" id="diff-title-label">Selisih Persediaan</span>
                                        <div class="stat-card-value" id="calc-difference">0</div>
                                    </div>
                                </div>
                                <!-- Alerts Panel -->
                                <div class="col-12">
                                    <div id="calc-status-alert" class="status-alert-box">
                                        <i class="bi fs-5" id="calc-status-icon"></i>
                                        <span id="calc-status-text">Stok siap diperiksa.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Enter Physical Stock -->
                    <div class="col-md-6">
                        <label for="physical_stock" class="custom-label">JUMLAH STOK FISIK DI LAPANGAN</label>
                        <div class="input-group">
                            <!-- Note: input is NOT disabled by default HTML to ensure robust value propagation and compatibility with any browsers! We handle disablement elegantly with JavaScript on load -->
                            <input type="number" name="physical_stock" id="physical_stock" class="form-control input-premium-text @error('physical_stock') is-invalid @enderror" style="border-top-right-radius: 0; border-bottom-right-radius: 0; height: 52px;" min="0" value="{{ old('physical_stock') }}" required placeholder="Pilih barang terlebih dahulu">
                            <span class="input-group-text bg-light text-muted fw-bold px-3" id="item-unit" style="border: 1.5px solid #e2e8f0; border-left: 0; border-top-right-radius: 14px; border-bottom-right-radius: 14px;">pcs</span>
                        </div>
                        @error('physical_stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <!-- Premium Helper Buttons -->
                        <div class="quick-adjust-group" id="helpers-container" style="display: none;">
                            <button type="button" class="btn-helper" id="btn-helper-match"><i class="bi bi-check-all"></i> Samakan (Match)</button>
                            <button type="button" class="btn-helper" id="btn-helper-plus-1">+1</button>
                            <button type="button" class="btn-helper" id="btn-helper-plus-5">+5</button>
                            <button type="button" class="btn-helper" id="btn-helper-minus-1">-1</button>
                            <button type="button" class="btn-helper" id="btn-helper-zero"><i class="bi bi-x-circle"></i> Nol (0)</button>
                        </div>
                    </div>

                    <!-- Step 4: Audit Date -->
                    <div class="col-md-6">
                        <label for="opname_date" class="custom-label">TANGGAL AUDIT</label>
                        <input type="date" name="opname_date" id="opname_date" class="form-control input-premium-text" style="height: 52px;" value="{{ old('opname_date', date('Y-m-d')) }}" required>
                        @error('opname_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Step 5: Remarks / Notes -->
                    <div class="col-12">
                        <label for="notes" class="custom-label">CATATAN PEMERIKSAAN (REMARKS)</label>
                        <textarea name="notes" id="notes" class="form-control input-premium-text" style="min-height: 110px;" placeholder="Tulis catatan jika ada selisih, misalnya: '3 box basah / rusak di rak C' atau 'Kehilangan kunci gudang'.">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4" style="border-color: #e2e8f0;">

                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('stock-opnames.index') }}" class="btn btn-light px-4 py-2.5 fw-bold" style="border-radius: 12px; border: 1.5px solid #e2e8f0; color: #64748b; font-size: 0.9rem;">Batal</a>
                    <button type="submit" class="btn btn-hse-red px-5 py-2.5 fw-bold shadow-sm" style="border-radius: 12px; font-size: 0.9rem;">
                        <i class="bi bi-cloud-arrow-up me-1"></i> Simpan Hasil Opname
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
        var diffTitleLabel = $('#diff-title-label');
        
        var calcAlert = $('#calc-status-alert');
        var calcIcon = $('#calc-status-icon');
        var calcText = $('#calc-status-text');
        var helpersContainer = $('#helpers-container');

        // Main Calculation Engine
        function calculateDiff() {
            var selectedOpt = consumableSelect.find(':selected');
            if (!selectedOpt.val()) return;

            var sysStock = parseInt(selectedOpt.data('stock'));
            var unitName = selectedOpt.data('unit');
            var rawPhysValue = physicalInput.val();
            var physStock = rawPhysValue === "" ? 0 : parseInt(rawPhysValue);
            var diff = physStock - sysStock;
            
            calcSystem.text(sysStock + ' ' + unitName);
            calcPhysical.text(physStock + ' ' + unitName);
            
            var diffText = diff > 0 ? '+' + diff : diff;
            calcDifference.text(diffText + ' ' + unitName);

            // Dynamic Styling and Status Messages
            if (diff === 0) {
                // MATCH (Stok Sesuai)
                diffTitleLabel.text('Selisih Persediaan').css('color', '#94a3b8');
                calcDiffCard.css({
                    'border': '2px dashed #22c55e',
                    'background': 'rgba(34, 197, 94, 0.02)',
                    'color': '#22c55e'
                });
                calcDifference.css('color', '#22c55e');

                calcAlert.removeClass().addClass('status-alert-box').css({
                    'background': 'rgba(34, 197, 94, 0.08)',
                    'color': '#15803d',
                    'border': '1px solid rgba(34, 197, 94, 0.15)'
                });
                calcIcon.removeClass().addClass('bi bi-check-circle-fill fs-5');
                calcText.text('Stok fisik klop! Jumlah fisik di lapangan sesuai persis dengan stok di sistem.');
            } else if (diff < 0) {
                // DEFICIT (Selisih Kurang)
                diffTitleLabel.text('Stok Defisit').css('color', '#ef4444');
                calcDiffCard.css({
                    'border': '2px dashed #ef4444',
                    'background': 'rgba(239, 68, 68, 0.02)',
                    'color': '#ef4444'
                });
                calcDifference.css('color', '#ef4444');

                calcAlert.removeClass().addClass('status-alert-box').css({
                    'background': 'rgba(239, 68, 68, 0.08)',
                    'color': '#b91c1c',
                    'border': '1px solid rgba(239, 68, 68, 0.15)'
                });
                calcIcon.removeClass().addClass('bi bi-exclamation-octagon-fill fs-5');
                calcText.text('Stok Kurang! Terjadi selisih kurang sebanyak ' + Math.abs(diff) + ' ' + unitName + '. Sistem akan menyesuaikan stok berkurang otomatis.');
            } else {
                // SURPLUS (Selisih Lebih)
                diffTitleLabel.text('Stok Surplus').css('color', '#eab308');
                calcDiffCard.css({
                    'border': '2px dashed #eab308',
                    'background': 'rgba(234, 179, 8, 0.02)',
                    'color': '#ca8a04'
                });
                calcDifference.css('color', '#ca8a04');

                calcAlert.removeClass().addClass('status-alert-box').css({
                    'background': 'rgba(234, 179, 8, 0.08)',
                    'color': '#854d0e',
                    'border': '1px solid rgba(234, 179, 8, 0.15)'
                });
                calcIcon.removeClass().addClass('bi bi-info-circle-fill fs-5');
                calcText.text('Stok Berlebih! Terjadi selisih lebih sebanyak ' + diff + ' ' + unitName + '. Sistem akan menyesuaikan stok bertambah otomatis.');
            }
        }

        // Dropdown selection change listener
        consumableSelect.on('change', function() {
            var selectedOpt = $(this).find(':selected');
            if (selectedOpt.val()) {
                var unit = selectedOpt.data('unit');
                unitLabel.text(unit);
                
                // Enable physical input & set friendly placeholder
                physicalInput.prop('disabled', false)
                             .attr('placeholder', 'Masukkan jumlah ' + unit);
                
                // Show calculations panel & quick fill helpers
                calcPanel.removeClass('d-none');
                helpersContainer.fadeIn(250);
                
                // Default value is 0 instead of blank
                if (physicalInput.val() === "") {
                    physicalInput.val(0);
                }
                
                calculateDiff();
            } else {
                // Disable input and hide panels
                physicalInput.prop('disabled', true)
                             .val('')
                             .attr('placeholder', 'Pilih barang terlebih dahulu');
                calcPanel.addClass('d-none');
                helpersContainer.hide();
            }
        });

        // Physical Input Change Listeners
        physicalInput.on('input keyup change propertychange', function() {
            calculateDiff();
        });

        // Quick adjustment helpers click handlers
        $('#btn-helper-match').on('click', function(e) {
            e.preventDefault();
            var selectedOpt = consumableSelect.find(':selected');
            if (selectedOpt.val()) {
                var sysStock = parseInt(selectedOpt.data('stock'));
                physicalInput.val(sysStock).trigger('change');
                
                // Highlight action
                physicalInput.addClass('border-success').css('box-shadow', '0 0 0 4px rgba(34, 197, 94, 0.2)');
                setTimeout(function() {
                    physicalInput.removeClass('border-success').css('box-shadow', '');
                }, 1000);
            }
        });

        $('#btn-helper-plus-1').on('click', function(e) {
            e.preventDefault();
            var currentVal = parseInt(physicalInput.val()) || 0;
            physicalInput.val(currentVal + 1).trigger('change');
        });

        $('#btn-helper-plus-5').on('click', function(e) {
            e.preventDefault();
            var currentVal = parseInt(physicalInput.val()) || 0;
            physicalInput.val(currentVal + 5).trigger('change');
        });

        $('#btn-helper-minus-1').on('click', function(e) {
            e.preventDefault();
            var currentVal = parseInt(physicalInput.val()) || 0;
            if (currentVal > 0) {
                physicalInput.val(currentVal - 1).trigger('change');
            }
        });

        $('#btn-helper-zero').on('click', function(e) {
            e.preventDefault();
            physicalInput.val(0).trigger('change');
        });

        // CRITICAL FIX: Trigger calculation on load for preselected states
        // E.g. when old input is present after validation error or route parameters
        if (consumableSelect.val()) {
            consumableSelect.trigger('change');
        } else {
            // Ensure disabled state on initial blank load
            physicalInput.prop('disabled', true);
        }
    });
</script>
@endpush
