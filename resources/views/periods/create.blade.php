@extends('layouts.app')

@section('content')
<div class="card card-soft p-4">
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <div class="icon-circle me-3">
            <i class="fa-solid fa-plus"></i>
        </div>
        <div>
            <h4 class="mb-1 fw-bold">Tambah Periode Baru</h4>
            <p class="text-muted mb-0 small">Buat periode penilaian bulanan untuk nasabah</p>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="alert alert-info d-flex align-items-center mb-4">
        <i class="fa-solid fa-lightbulb fs-4 me-3"></i>
        <div>
            <strong>Tips:</strong> Label periode akan otomatis dibuat berdasarkan bulan dan tahun yang Anda pilih.
            <br>
            <small>Contoh: Periode Januari 2024, Periode Februari 2024, dll.</small>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('periods.store') }}" method="post" id="periodForm">
        @csrf
        
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
                        <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
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
                       value="{{ date('Y') }}" 
                       min="2020" 
                       max="2099"
                       required>
                <div class="form-text">
                    <i class="fa-solid fa-info-circle me-1"></i>Masukkan tahun (2020 - 2099)
                </div>
            </div>
        </div>

        <!-- Preview Label -->
        <div class="mt-4">
            <label class="form-label fw-semibold">
                <i class="fa-solid fa-eye me-2"></i>Preview Label Periode
            </label>
            <div class="preview-card">
                <div class="preview-icon">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div class="preview-content">
                    <small class="text-muted d-block mb-1">Label yang akan dibuat:</small>
                    <h5 class="mb-0" id="previewLabel">Periode {{ DateTime::createFromFormat('!m', date('n'))->format('F') }} {{ date('Y') }}</h5>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-save me-2"></i>Simpan Periode
            </button>
            <a href="{{ route('periods.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="fa-solid fa-arrow-left me-2"></i>Kembali
            </a>
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
    border-color: var(--primary);
    box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.15);
}

.form-label {
    margin-bottom: 0.5rem;
    color: #495057;
}

.form-text {
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

/* Preview Card */
.preview-card {
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(99, 102, 241, 0.1));
    border: 2px dashed var(--primary);
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
}

.preview-card:hover {
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(99, 102, 241, 0.15));
    transform: translateY(-2px);
}

.preview-icon {
    width: 64px;
    height: 64px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    flex-shrink: 0;
}

.preview-content {
    flex: 1;
}

.preview-content h5 {
    color: var(--primary);
    font-weight: 700;
}

/* Buttons */
.btn-lg {
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: 10px;
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
    
    .preview-icon {
        width: 52px;
        height: 52px;
        font-size: 1.5rem;
    }
    
    .preview-card {
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
// Update preview label when month or year changes
const monthSelect = document.getElementById('monthSelect');
const yearInput = document.getElementById('yearInput');
const previewLabel = document.getElementById('previewLabel');

const monthNames = [
    '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];

function updatePreview() {
    const month = monthSelect.value;
    const year = yearInput.value;
    
    if (month && year) {
        const monthName = monthNames[parseInt(month)];
        previewLabel.textContent = `Periode ${monthName} ${year}`;
    } else {
        previewLabel.textContent = 'Periode - (Pilih bulan dan tahun)';
    }
}

monthSelect.addEventListener('change', updatePreview);
yearInput.addEventListener('input', updatePreview);

// Form validation
document.getElementById('periodForm').addEventListener('submit', function(e) {
    const month = monthSelect.value;
    const year = yearInput.value;
    
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
    
    // Confirm before submit
    const monthName = monthNames[parseInt(month)];
    if (!confirm(`Buat periode untuk ${monthName} ${year}?`)) {
        e.preventDefault();
        return false;
    }
});

// Initialize preview on page load
updatePreview();
</script>
@endpush

@endsection