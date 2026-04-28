@extends('layouts.app')

@section('content')
<div class="card card-soft p-4">
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <div class="icon-circle edit me-3">
            <i class="fa-solid fa-pen-to-square"></i>
        </div>
        <div>
            <h4 class="mb-1 fw-bold">Edit Periode</h4>
            <p class="text-muted mb-0 small">Ubah informasi periode penilaian</p>
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

    <!-- Current Period Info -->
    <div class="current-period-card mb-4">
        <div class="period-badge">
            <i class="fa-solid fa-calendar-check"></i>
        </div>
        <div class="period-info">
            <small class="text-muted d-block mb-1">Periode Saat Ini:</small>
            <h5 class="mb-1">{{ $period->label }}</h5>
            <div class="d-flex gap-2 align-items-center flex-wrap">
                @if($period->is_active)
                    <span class="badge bg-success">
                        <i class="fa-solid fa-check-circle me-1"></i>Aktif
                    </span>
                @else
                    <span class="badge bg-secondary">
                        <i class="fa-solid fa-circle me-1"></i>Tidak Aktif
                    </span>
                @endif
                @php
                    $usageCount = \App\Models\Evaluation::where('period_id', $period->id)->count();
                @endphp
                @if($usageCount > 0)
                    <span class="badge bg-info">
                        <i class="fa-solid fa-database me-1"></i>{{ $usageCount }} Penilaian
                    </span>
                @endif
            </div>
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

            <!-- Status Aktif -->
            <div class="col-12">
                <div class="form-check form-switch-lg">
                    <input class="form-check-input" 
                           type="checkbox" 
                           name="is_active" 
                           id="activeSwitch" 
                           value="1"
                           {{ $period->is_active ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="activeSwitch">
                        <i class="fa-solid fa-toggle-on me-2"></i>Jadikan Periode Aktif
                    </label>
                    <div class="form-text">
                        <i class="fa-solid fa-info-circle me-1"></i>Hanya satu periode yang dapat aktif dalam satu waktu. Mengaktifkan periode ini akan menonaktifkan periode lainnya.
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Label -->
        <div class="mt-4">
            <label class="form-label fw-semibold">
                <i class="fa-solid fa-eye me-2"></i>Preview Label Periode Baru
            </label>
            <div class="preview-card edit">
                <div class="preview-icon edit">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div class="preview-content">
                    <small class="text-muted d-block mb-1">Label yang akan diubah menjadi:</small>
                    <h5 class="mb-0" id="previewLabel">{{ $period->label }}</h5>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="mt-4">
            <div class="info-box">
                <i class="fa-solid fa-clock-rotate-left me-2"></i>
                <div>
                    <strong>Informasi Periode:</strong>
                    <br>
                    <small class="text-muted">
                        Periode ini dibuat pada {{ $period->created_at ? $period->created_at->format('d F Y, H:i') : '-' }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-warning btn-lg">
                <i class="fa-solid fa-save me-2"></i>Update Periode
            </button>
            <button type="reset" class="btn btn-outline-secondary btn-lg">
                <i class="fa-solid fa-rotate-left me-2"></i>Reset
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

.period-badge {
    width: 64px;
    height: 64px;
    border-radius: 12px;
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(217, 119, 6, 0.3));
    color: #f59e0b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    flex-shrink: 0;
}

.period-info {
    flex: 1;
}

.period-info h5 {
    color: #d97706;
    font-weight: 700;
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

/* Form Switch Large */
.form-check-input {
    width: 3rem;
    height: 1.5rem;
    cursor: pointer;
}

.form-check-input:checked {
    background-color: #f59e0b;
    border-color: #f59e0b;
}

.form-check-input:focus {
    border-color: #f59e0b;
    box-shadow: 0 0 0 0.25rem rgba(245, 158, 11, 0.15);
}

.form-check-label {
    cursor: pointer;
    margin-left: 0.5rem;
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

.preview-card.edit {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.05), rgba(217, 119, 6, 0.1));
    border-color: #f59e0b;
}

.preview-card:hover {
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(99, 102, 241, 0.15));
    transform: translateY(-2px);
}

.preview-card.edit:hover {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.15));
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

.preview-icon.edit {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.preview-content {
    flex: 1;
}

.preview-content h5 {
    color: var(--primary);
    font-weight: 700;
}

.preview-card.edit .preview-content h5 {
    color: #d97706;
}

/* Info Box */
.info-box {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.05), rgba(217, 119, 6, 0.05));
    border-left: 4px solid #f59e0b;
    border-radius: 8px;
}

.info-box i {
    font-size: 1.25rem;
    color: #f59e0b;
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
    
    .period-badge,
    .preview-icon {
        width: 52px;
        height: 52px;
        font-size: 1.5rem;
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
// Store original values
const originalMonth = document.getElementById('monthSelect').value;
const originalYear = document.getElementById('yearInput').value;
const originalActive = document.getElementById('activeSwitch').checked;

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

// Check for changes
function hasChanges() {
    return monthSelect.value !== originalMonth || 
           yearInput.value !== originalYear || 
           document.getElementById('activeSwitch').checked !== originalActive;
}

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
    
    if (!hasChanges()) {
        e.preventDefault();
        alert('Tidak ada perubahan data yang dilakukan.');
        return false;
    }
    
    // Confirm before submit
    const monthName = monthNames[parseInt(month)];
    if (!confirm(`Update periode menjadi ${monthName} ${year}?\n\nPerubahan akan disimpan ke database.`)) {
        e.preventDefault();
        return false;
    }
});

// Reset form handler
document.querySelector('button[type="reset"]').addEventListener('click', function(e) {
    if (!confirm('Reset form ke data awal?')) {
        e.preventDefault();
        return false;
    }
    
    // Restore original values
    setTimeout(() => {
        monthSelect.value = originalMonth;
        yearInput.value = originalYear;
        document.getElementById('activeSwitch').checked = originalActive;
        updatePreview();
    }, 10);
});

// Warn before leaving with unsaved changes
window.addEventListener('beforeunload', function(e) {
    if (hasChanges()) {
        e.preventDefault();
        e.returnValue = '';
    }
});

// Initialize preview on page load
updatePreview();
</script>
@endpush

@endsection