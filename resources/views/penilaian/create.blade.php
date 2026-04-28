@extends('layouts.app')

@section('content')
<div class="card card-soft p-4">
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <div class="icon-circle me-3">
            <i class="fa-solid fa-file-pen"></i>
        </div>
        <div>
            <h4 class="mb-1 fw-bold">Form Penilaian Nasabah</h4>
            <p class="text-muted mb-0 small">Input nilai kriteria untuk setiap nasabah berdasarkan periode</p>
        </div>
    </div>

    <!-- Filter Periode -->
    <form method="get" action="{{ route('penilaian.create') }}" class="mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-calendar-days me-2"></i>Pilih Periode Penilaian
                </label>
                <select name="period_id" class="form-select form-select-lg" onchange="this.form.submit()">
                    <option value="">-- Pilih Periode --</option>
                    @foreach($periods as $p)
                        <option value="{{ $p->id }}" {{ $selected == $p->id ? 'selected':'' }}>
                            {{ $p->label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa-solid fa-filter me-2"></i>Tampilkan Form
                </button>
            </div>
        </div>
    </form>

    @if(!$selected)
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="empty-state">
                <i class="fa-solid fa-clipboard-list text-muted mb-3" style="font-size: 4rem; opacity: 0.3;"></i>
                <h5 class="text-muted">Belum ada periode yang dipilih</h5>
                <p class="text-muted">Silakan pilih periode terlebih dahulu untuk mulai melakukan penilaian nasabah</p>
            </div>
        </div>
    @endif

    @if($selected)
        <!-- Info Periode -->
        <div class="alert alert-info d-flex align-items-center mb-4">
            <i class="fa-solid fa-info-circle fs-4 me-3"></i>
            <div>
                <strong>Periode:</strong> {{ $periods->firstWhere('id', $selected)->label ?? '-' }}
                <br>
                <small>Centang nasabah yang ingin dinilai, lalu isi nilai untuk setiap kriteria</small>
            </div>
        </div>

        <!-- Info Kriteria -->
        <div class="criteria-info mb-4">
            <h6 class="mb-3"><i class="fa-solid fa-list-check me-2"></i>Kriteria Penilaian:</h6>
            <div class="row g-2">
                @foreach($criteria as $c)
                    <div class="col-md-4 col-lg-3">
                        <div class="criteria-badge">
                            <span class="badge bg-primary">{{ $c->code }}</span>
                            <small class="ms-2">{{ $c->name }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <form method="post" action="{{ route('penilaian.store') }}">
            @csrf
            <input type="hidden" name="period_id" value="{{ $selected }}">

            <input type="hidden" name="period_id" value="{{ $selected }}">

            <!-- Quota Lolos -->
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-check-to-slot me-2"></i>Jumlah Nasabah Layak Lanjut
                </label>
                <input type="number"
                    name="quota_lolos"
                    class="form-control"
                    min="1"
                    placeholder="Contoh: 10"
                    value="{{ $periods->firstWhere('id', $selected)->quota_lolos ?? '' }}">
                <small class="text-muted">
                    Nasabah dengan peringkat 1 sampai nilai ini akan berstatus <b>Layak Lanjut</b>
                </small>
            </div>

            <!-- Tabel Penilaian -->
            <div class="table-responsive">
                <table class="table table-hover align-middle penilaian-table">
                    <thead class="table-light">
                        <tr>
                            <th width="60" class="text-center">
                                <input type="checkbox" id="checkAll" class="form-check-input" title="Pilih Semua">
                            </th>
                            <th width="200">
                                <i class="fa-solid fa-user me-1"></i>Nama Nasabah
                            </th>
                            @foreach($criteria as $c)
                                <th class="text-center" width="150">
                                    <div class="fw-bold text-primary">{{ $c->code }}</div>
                                    <small class="text-muted d-block">{{ $c->name }}</small>
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($customers as $cus)
                        @php
                            // Cek apakah customer ini sudah punya evaluasi di periode ini
                            $hasEvaluation = isset($existingData[$cus->id]) && !empty($existingData[$cus->id]);
                        @endphp
                        
                        <tr class="customer-row {{ $hasEvaluation ? 'selected' : '' }}">
                            <td class="text-center">
                                <input type="checkbox" 
                                       name="checked[]" 
                                       value="{{ $cus->id }}" 
                                       class="form-check-input select-row"
                                       {{ $hasEvaluation ? 'checked' : '' }}>
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2">
                                        {{ strtoupper(substr($cus->name, 0, 1)) }}
                                    </div>
                                    <strong>{{ $cus->name }}</strong>
                                </div>
                            </td>

                            @foreach($criteria as $c)
                            @php
                                // Ambil nilai yang sudah ada (kalau ada)
                                $existingValue = $existingData[$cus->id][$c->id] ?? null;
                                // Format dengan pemisah ribuan
                                $displayValue = $existingValue ? number_format($existingValue, 0, ',', '.') : '';
                            @endphp
                            
                            <td>
                                <input type="text"
                                       name="values[{{ $cus->id }}][{{ $c->id }}]"
                                       class="form-control real-input text-center"
                                       placeholder="0"
                                       value="{{ $displayValue }}"
                                       {{ $hasEvaluation ? '' : 'disabled' }}
                                       required>
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <span class="text-muted" id="selectedCount">0 nasabah dipilih</span>
                </div>
                <div class="d-flex gap-2">
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                        <i class="fa-solid fa-save me-2"></i> Simpan Penilaian
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>

@push('scripts')
<script>
// Update selected count
function updateSelectedCount() {
    const checkedCount = document.querySelectorAll('.select-row:checked').length;
    document.getElementById('selectedCount').textContent = `${checkedCount} nasabah dipilih`;
    document.getElementById('submitBtn').disabled = checkedCount === 0;
}

// Format angka dengan pemisah ribuan
document.querySelectorAll('.real-input').forEach(el => {
    el.addEventListener('input', function() {
        let v = this.value.replace(/\D/g,'');
        this.value = v.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
});

// Enable input hanya jika checkbox dicentang
document.querySelectorAll('.select-row').forEach(cb => {
    cb.addEventListener('change', function() {
        let tr = this.closest('tr');
        
        // Toggle class selected
        tr.classList.toggle('selected', this.checked);
        
        // Enable/disable inputs
        tr.querySelectorAll('input[type=text]').forEach(inp => {
            inp.disabled = !this.checked;
            if (!this.checked) {
                inp.value = "";
            } else {
                inp.focus();
            }
        });
        
        updateSelectedCount();
    });
});

// Check All functionality
document.getElementById('checkAll').addEventListener('change', function() {
    const isChecked = this.checked;
    
    document.querySelectorAll('.select-row').forEach(cb => {
        cb.checked = isChecked;
        cb.dispatchEvent(new Event('change'));
    });
});

// Initialize count on page load
document.addEventListener('DOMContentLoaded', function() {
    updateSelectedCount();
});

// Reset button functionality
document.querySelector('button[type="reset"]').addEventListener('click', function(e) {
    e.preventDefault();
    
    if (confirm('Apakah Anda yakin ingin mereset semua pilihan dan input?')) {
        document.querySelectorAll('.select-row').forEach(cb => {
            cb.checked = false;
            cb.dispatchEvent(new Event('change'));
        });
        document.getElementById('checkAll').checked = false;
    }
});

// Form validation before submit
document.querySelector('form[action="{{ route('penilaian.store') }}"]').addEventListener('submit', function(e) {
    const checkedCount = document.querySelectorAll('.select-row:checked').length;
    
    if (checkedCount === 0) {
        e.preventDefault();
        alert('Silakan pilih minimal 1 nasabah untuk dinilai');
        return false;
    }
    
    // Check if all required inputs are filled
    let hasEmptyInput = false;
    document.querySelectorAll('.select-row:checked').forEach(cb => {
        const tr = cb.closest('tr');
        tr.querySelectorAll('input[type=text]').forEach(inp => {
            if (!inp.value.trim()) {
                hasEmptyInput = true;
            }
        });
    });
    
    if (hasEmptyInput) {
        e.preventDefault();
        alert('Harap isi semua nilai kriteria untuk nasabah yang dipilih');
        return false;
    }
    
    // Confirm before submit
    if (!confirm(`Anda akan menyimpan penilaian untuk ${checkedCount} nasabah. Lanjutkan?`)) {
        e.preventDefault();
        return false;
    }
});
</script>
@endpush

@endsection