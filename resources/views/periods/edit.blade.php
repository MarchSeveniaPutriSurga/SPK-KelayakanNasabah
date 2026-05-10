@extends('layouts.app')

@section('content')
<div class="card card-soft p-4">
     <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <div class="icon-circle edit me-3">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <div>
                <h4 class="mb-1 fw-bold">Edit Periode</h4>
                <p class="text-muted mb-0 small">Ubah informasi periode penilaian</p>
            </div>
        </div>

        <!-- Button kanan -->
        <div class="d-flex gap-2">
            <button type="submit" form="periodForm" class="btn btn-primary">
                <i class="fa-solid fa-save me-1"></i> Update
            </button>

            <button type="reset" form="periodForm" class="btn btn-outline-secondary">
                <i class="fa-solid fa-rotate-left me-1"></i> Reset
            </button>

            <a href="{{ route('periods.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="alert alert-warning d-flex align-items-center mb-4">
        <i class="fa-solid fa-triangle-exclamation fs-4 me-3"></i>
        <div>
            <strong>Perhatian:</strong> Mengubah periode yang sudah digunakan dalam penilaian dapat mempengaruhi data.
            <br>
            <small>Pastikan Anda yakin sebelum menyimpan perubahan.</small>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('periods.update', $period->id) }}" method="post" id="periodForm">
        @csrf
        @method('PUT')
        
        <div class="row g-4">
            <!-- Bulan -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-calendar me-2"></i>Pilih Bulan
                    <span class="text-danger">*</span>
                </label>
                <select name="month" class="form-select form-select-lg" id="monthSelect" required>
                    <option value="">-- Pilih Bulan --</option>
                    @for($m=1; $m<=12; $m++)
                        @php
                            $currentMonth = \Carbon\Carbon::parse($period->label)->format('n');
                        @endphp
                        <option value="{{ $m }}" {{ $currentMonth == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endfor
                </select>
                <div class="form-text">
                    <i class="fa-solid fa-info-circle me-1"></i>Pilih bulan untuk periode penilaian
                </div>
            </div>

            <!-- Tahun -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-calendar-days me-2"></i>Pilih Tahun
                    <span class="text-danger">*</span>
                </label>
                <input type="number" 
                       name="year" 
                       class="form-control form-control-lg" 
                       id="yearInput"
                       value="{{ \Carbon\Carbon::parse($period->label)->format('Y') }}" 
                       min="2020" 
                       max="2099"
                       required>
                <div class="form-text">
                    <i class="fa-solid fa-info-circle me-1"></i>Masukkan tahun (2020 - 2099)
                </div>
            </div>
        </div>
    </form>
</div>

<style>
/* Icon Circle */
.icon-circle {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

/* Icon Circle - Edit Variant */
.icon-circle.edit {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

/* Current Period Card */
.current-period-card {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.05), rgba(217, 119, 6, 0.05));
    border: 2px solid rgba(245, 158, 11, 0.3);
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

/* Form Controls */
.form-select-lg,
.form-control-lg {
    border-radius: 12px;
    padding: 0.875rem 1rem;
    font-size: 1rem;
    border: 2px solid #e9ecef;
    transition: all 0.2s ease;
}

.form-select-lg:focus,
.form-control-lg:focus {
    border-color: #f59e0b;
    box-shadow: 0 0 0 0.25rem rgba(245, 158, 11, 0.15);
}

.form-label {
    margin-bottom: 0.5rem;
    color: #495057;
}

.form-text {
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

/* Buttons */
.btn-lg {
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: 10px;
}

.btn-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border: none;
    color: white;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #d97706, #b45309);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

/* Badges */
.badge {
    font-weight: 500;
    padding: 0.5rem 0.75rem;
}

/* Alert */
.alert {
    border-radius: 12px;
    border: none;
}

/* Responsive */
@media (max-width: 768px) {
    .icon-circle {
        width: 48px;
        height: 48px;
        font-size: 1.25rem;
    }
    
    .current-period-card,
    .preview-card {
        flex-direction: column;
        text-align: center;
    }
    
    .info-box {
        flex-direction: column;
        text-align: center;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 .btn {
        width: 100%;
    }
}

/* Input Number Arrows */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    opacity: 1;
    height: 40px;
}
</style>

@push('scripts')
<script>
const monthSelect = document.getElementById('monthSelect');
const yearInput   = document.getElementById('yearInput');

// Simpan nilai awal untuk reset
const originalMonth = monthSelect.value;
const originalYear  = yearInput.value;

const monthNames = [
    '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];

// Cek ada perubahan atau tidak
function hasChanges() {
    return monthSelect.value !== originalMonth ||
           yearInput.value !== originalYear;
}

// Validasi sebelum submit
document.getElementById('periodForm').addEventListener('submit', function(e) {
    const month = monthSelect.value;
    const year  = yearInput.value;

    if (!month || !year) {
        e.preventDefault();
        alert('Harap lengkapi bulan dan tahun terlebih dahulu');
        return false;
    }

    if (year < 2020 || year > 2099) {
        e.preventDefault();
        alert('Tahun harus antara 2020 - 2099');
        return false;
    }

    if (!hasChanges()) {
        e.preventDefault();
        alert('Tidak ada perubahan data yang dilakukan.');
        return false;
    }

    const monthName = monthNames[parseInt(month)];
    if (!confirm(`Update periode menjadi ${monthName} ${year}?\n\nPerubahan akan disimpan ke database.`)) {
        e.preventDefault();
        return false;
    }
});

// Reset ke nilai awal
document.querySelector('button[type="reset"]').addEventListener('click', function(e) {
    e.preventDefault();

    if (!confirm('Reset form ke data awal?')) return;

    monthSelect.value = originalMonth;
    yearInput.value   = originalYear;
});

// Warn sebelum keluar kalau ada perubahan
window.addEventListener('beforeunload', function(e) {
    if (hasChanges()) {
        e.preventDefault();
        e.returnValue = '';
    }
});
</script>
@endpush

@endsection