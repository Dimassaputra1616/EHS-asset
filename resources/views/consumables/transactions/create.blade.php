@extends('layouts.app')

@section('title', $type === 'in' ? 'Record Stock In' : 'Record Stock Out')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <div class="card border-0 shadow-sm mt-3 animate-fade-in">
            <div class="card-header bg-white py-3 border-bottom-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="text-white p-2 rounded-3 shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: {{ $type === 'in' ? 'linear-gradient(135deg, #2ecc71, #27ae60)' : 'linear-gradient(135deg, #e74c3c, #c0392b)' }};">
                        <i class="bi {{ $type === 'in' ? 'bi-box-arrow-in-down' : 'bi-box-arrow-up' }} fs-5"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fw-bold">{{ $type === 'in' ? 'Record Stock In (Barang Masuk)' : 'Record Stock Out (Barang Keluar)' }}</h5>
                        <p class="text-muted small mb-0">
                            {{ $type === 'in' ? 'Record stock additions to replenish supplies' : 'Record stock deductions or usage of consumable items' }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4 pt-2">
                <form action="{{ route('consumables.transactions.store') }}" method="POST">
                    @csrf
                    
                    <input type="hidden" name="type" value="{{ $type }}">
                    
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label fw-bold">Consumable Item <span class="text-danger">*</span></label>
                            <select name="consumable_id" class="form-select form-select-lg fs-6 @error('consumable_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Select Consumable Item</option>
                                @foreach($consumables as $consumable)
                                    <option value="{{ $consumable->id }}" {{ old('consumable_id') == $consumable->id ? 'selected' : '' }}>
                                        {{ $consumable->code }} - {{ $consumable->name }} (Sisa Stok: {{ $consumable->stock }} {{ $consumable->unit }})
                                    </option>
                                @endforeach
                            </select>
                            @error('consumable_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control form-control-lg fs-6 @error('quantity') is-invalid @enderror" value="{{ old('quantity', 1) }}" min="1" required>
                            @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Transaction Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control form-control-lg fs-6 @error('date') is-invalid @enderror" value="{{ old('date', date('Y-m-d')) }}" required>
                            @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Notes / Description</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Explain reason for transaction (e.g. Monthly replenishment, Taken for HQ Department)...">{{ old('notes') }}</textarea>
                            @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <hr class="my-4 border-light">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route($type === 'in' ? 'consumables.transactions.in' : 'consumables.transactions.out') }}" class="btn btn-light fw-bold px-4">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                        <button type="submit" class="btn {{ $type === 'in' ? 'btn-success' : 'btn-danger' }} fw-bold px-5">
                            <i class="bi bi-check2-circle me-1"></i> Save Transaction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
