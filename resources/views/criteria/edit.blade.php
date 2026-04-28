@extends('layouts.app')

@section('content')
<div class="card card-soft p-4">
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <div class="icon-circle me-3">
            <i class="fa-solid fa-pen-to-square"></i>
        </div>
        <div>
            <h4 class="mb-1 fw-bold">Edit Kriteria</h4>
            <p class="text-muted mb-0 small">Perbarui informasi kriteria penilaian</p>
        </div>
    </div>

    <!-- Warning Alert -->
    <div class="alert alert-warning d-flex align-items-center mb-4">
        <i class="fa-solid fa-triangle-exclamation fs-4 me-3"></i>
        <div>
            <strong>Perhatian:</strong> Kode kriteria tidak dapat diubah untuk menjaga konsistensi data.
            <br>
            <small>Perubahan bobot akan mempengaruhi hasil perhitungan penilaian yang sudah ada.</small>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('criteria.update', $criterion->id) }}" method="post" id="editForm">
        @csrf 
        @method('PUT')
        
        <div class="row g-4">
            <!-- Kode Kriteria (Disabled) -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-code me-2"></i>Kode Kriteria
                    <span class="badge bg-secondary ms-2">Tidak dapat diubah</span>
                </label>
                <input type="text" 
                       value="{{ $criterion->code }}" 
                       class="form-control form-control-lg" 
                       disabled>
                <div class="form-text">
                    <i class="fa-solid fa-lock me-1"></i>Kode kriteria bersifat tetap dan tidak dapat dimodifikasi
                </div>
            </div>

            <!-- Nama Kriteria -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-tag me-2"></i>Nama Kriteria
                    <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       value="{{ $criterion->name }}" 
                       class="form-control form-control-lg" 
                       placeholder="Contoh: Pendapatan"
                       id="nameInput"
                       required>
                <div class="form-text">
                    <i class="fa-solid fa-lightbulb me-1"></i>Nama lengkap kriteria penilaian
                </div>
            </div>

            <!-- Jenis Kriteria -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-arrow-up-arrow-down me-2"></i>Jenis Kriteria
                    <span class="text-danger">*</span>
                </label>
                <select name="type" class="form-select form-select-lg" id="typeSelect" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="benefit" {{ $criterion->type == 'benefit' ? 'selected' : '' }}>
                        Benefit (Semakin tinggi semakin baik)
                    </option>
                    <option value="cost" {{ $criterion->type == 'cost' ? 'selected' : '' }}>
                        Cost (Semakin rendah semakin baik)
                    </option>
                </select>
                <div class="form-text" id="typeHelp">
                    @if($criterion->type == 'benefit')
                        <i class="fa-solid fa-check-circle me-1 text-success"></i>Nilai tinggi = Lebih baik
                    @else
                        <i class="fa-solid fa-check-circle me-1 text-danger"></i>Nilai rendah = Lebih baik
                    @endif
                </div>
            </div>

            <!-- Bobot -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-weight-hanging me-2"></i>Bobot Kriteria
                    <span class="text-danger">*</span>
                </label>
                <input type="number" 
                       name="weight" 
                       value="{{ $criterion->weight }}" 
                       class="form-control form-control-lg" 
                       placeholder="Contoh: 0.25"
                       id="weightInput"
                       step="0.01"
                       min="0"
                       max="1"
                       required>
                <div class="form-text">
                    <i class="fa-solid fa-lightbulb me-1"></i>Nilai antara 0 - 1 (contoh: 0.25 = 25%)
                </div>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="mt-4">
            <label class="form-label fw-semibold">
                <i class="fa-solid fa-eye me-2"></i>Preview Kriteria
            </label>
            <div class="preview-card">
                <div class="preview-code" id="previewCode">
                    {{ $criterion->code }}
                </div>
                <div class="preview-content">
                    <h5 class="mb-1" id="previewName">{{ $criterion->name }}</h5>
                    <div class="preview-details">
                        <span class="preview-item" id="previewType">
                            @if($criterion->type == 'benefit')
                                <i class="fa-solid fa-arrow-trend-up me-1 text-success"></i>Jenis: Benefit
                            @else
                                <i class="fa-solid fa-arrow-trend-down me-1 text-danger"></i>Jenis: Cost
                            @endif
                        </span>
                        <span class="preview-item" id="previewWeight">
                            <i class="fa-solid fa-weight-hanging me-1"></i>Bobot: {{ $criterion->weight }} ({{ number_format($criterion->weight * 100, 1) }}%)
                        </span>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="fa-solid fa-hashtag me-1"></i>ID: {{ $criterion->id }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="mt-4">
            <div class="info-box">
                <i class="fa-solid fa-chart-line me-2"></i>
                <div>
                    <strong>Informasi Kriteria:</strong>
                    <br>
                    <small class="text-muted">
                        Kriteria ini dibuat pada {{ $criterion->created_at ? $criterion->created_at->format('d F Y, H:i') : '-' }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Help Box -->
        <div class="mt-4">
            <div class="help-box">
                <h6 class="mb-2"><i class="fa-solid fa-circle-info me-2"></i>Panduan Jenis Kriteria:</h6>
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="help-item benefit">
                            <i class="fa-solid fa-arrow-trend-up me-2"></i>
                            <div>
                                <strong>Benefit</strong>
                                <small class="d-block">Contoh: Pendapatan, Aset, Nilai Kredit, Loyalitas</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="help-item cost">
                            <i class="fa-solid fa-arrow-trend-down me-2"></i>
                            <div>
                                <strong>Cost</strong>
                                <small class="d-block">Contoh: Hutang, Resiko, Tunggakan, Biaya</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-save me-2"></i>Update Kriteria
            </button>
            <button type="reset" class="btn btn-outline-secondary btn-lg">
                <i class="fa-solid fa-rotate-left me-2"></i>Reset
            </button>
            <a href="{{ route('criteria.index') }}" class="btn btn-outline-secondary btn-lg">
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
    background: linear-gradient(135deg, #f59e0b, #ef4444);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

/* Form Controls */
.form-control-lg,
.form-select-lg {
    border-radius: 12px;
    padding: 0.875rem 1rem;
    font-size: 1rem;
    border: 2px solid #e9ecef;
    transition: all 0.2s ease;
}

.form-control-lg:focus,
.form-select-lg:focus {
    border-color: #f59e0b;
    box-shadow: 0 0 0 0.25rem rgba(245, 158, 11, 0.15);
}

.form-control-lg:disabled {
    background-color: #f8f9fa;
    cursor: not-allowed;
    opacity: 0.7;
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
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.05), rgba(245, 158, 11, 0.1));
    border: 2px solid #f59e0b;
    border-radius: 16px;
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    transition: all 0.3s ease;
}

.preview-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
}

.preview-code {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    background: linear-gradient(135deg, #f59e0b, #ef4444);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    font-weight: bold;
    flex-shrink: 0;
}

.preview-content {
    flex: 1;
}

.preview-content h5 {
    color: #f59e0b;
    font-weight: 700;
}

.preview-details {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 0.5rem;
}

.preview-item {
    font-size: 0.875rem;
    color: #6c757d;
    display: flex;
    align-items: center;
}

/* Info Box */
.info-box {
    background: #f8f9fa;
    border-left: 4px solid #6c757d;
    border-radius: 8px;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-box i {
    font-size: 1.25rem;
    color: #6c757d;
}

/* Help Box */
.help-box {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #e9ecef;
}

.help-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    border-radius: 8px;
    background: white;
}

.help-item.benefit {
    border-left: 4px solid #198754;
}

.help-item.cost {
    border-left: 4px solid #dc3545;
}

.help-item i {
    font-size: 1.5rem;
}

.help-item.benefit i {
    color: #198754;
}

.help-item.cost i {
    color: #dc3545;
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
    
    .preview-card {
        flex-direction: column;
        text-align: center;
        padding: 1.5rem;
    }
    
    .preview-code {
        width: 64px;
        height: 64px;
        font-size: 1.5rem;
    }
    
    .preview-details {
        justify-content: center;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 .btn {
        width: 100%;
    }
    
    .info-box {
        flex-direction: column;
        text-align: center;
    }
}
</style>

@push('scripts')
<script>
// Form inputs
const nameInput = document.getElementById('nameInput');
const typeSelect = document.getElementById('typeSelect');
const weightInput = document.getElementById('weightInput');

// Preview elements
const previewName = document.getElementById('previewName');
const previewType = document.getElementById('previewType');
const previewWeight = document.getElementById('previewWeight');
const typeHelp = document.getElementById('typeHelp');

// Store original values
const originalName = nameInput.value;
const originalType = typeSelect.value;
const originalWeight = weightInput.value;

// Update preview name
nameInput.addEventListener('input', function() {
    const name = this.value.trim();
    previewName.textContent = name || 'Nama Kriteria';
});

// Update preview type
typeSelect.addEventListener('change', function() {
    const type = this.value;
    if (type === 'benefit') {
        previewType.innerHTML = '<i class="fa-solid fa-arrow-trend-up me-1 text-success"></i>Jenis: Benefit';
        typeHelp.innerHTML = '<i class="fa-solid fa-check-circle me-1 text-success"></i>Nilai tinggi = Lebih baik';
    } else if (type === 'cost') {
        previewType.innerHTML = '<i class="fa-solid fa-arrow-trend-down me-1 text-danger"></i>Jenis: Cost';
        typeHelp.innerHTML = '<i class="fa-solid fa-check-circle me-1 text-danger"></i>Nilai rendah = Lebih baik';
    }
});

// Update preview weight
weightInput.addEventListener('input', function() {
    const weight = parseFloat(this.value);
    if (!isNaN(weight)) {
        const percentage = (weight * 100).toFixed(1);
        previewWeight.innerHTML = `<i class="fa-solid fa-weight-hanging me-1"></i>Bobot: ${weight} (${percentage}%)`;
    } else {
        previewWeight.innerHTML = '<i class="fa-solid fa-weight-hanging me-1"></i>Bobot: -';
    }
});

// Check for changes
function hasChanges() {
    return nameInput.value !== originalName || 
           typeSelect.value !== originalType || 
           weightInput.value !== originalWeight;
}

// Form validation
document.getElementById('editForm').addEventListener('submit', function(e) {
    const name = nameInput.value.trim();
    const type = typeSelect.value;
    const weight = parseFloat(weightInput.value);
    
    if (!name || !type) {
        e.preventDefault();
        alert('Harap lengkapi semua field yang wajib diisi!');
        return false;
    }
    
    if (isNaN(weight) || weight <= 0 || weight > 1) {
        e.preventDefault();
        alert('Bobot harus antara 0 dan 1!\nContoh: 0.25 untuk 25%');
        weightInput.focus();
        return false;
    }
    
    if (!hasChanges()) {
        e.preventDefault();
        alert('Tidak ada perubahan data yang dilakukan.');
        return false;
    }
    
    // Confirm before submit
    if (!confirm(`Update kriteria "${name}"?\n\nPerubahan akan disimpan ke database.`)) {
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
        nameInput.value = originalName;
        typeSelect.value = originalType;
        weightInput.value = originalWeight;
        
        // Update preview
        previewName.textContent = originalName;
        
        if (originalType === 'benefit') {
            previewType.innerHTML = '<i class="fa-solid fa-arrow-trend-up me-1 text-success"></i>Jenis: Benefit';
            typeHelp.innerHTML = '<i class="fa-solid fa-check-circle me-1 text-success"></i>Nilai tinggi = Lebih baik';
        } else {
            previewType.innerHTML = '<i class="fa-solid fa-arrow-trend-down me-1 text-danger"></i>Jenis: Cost';
            typeHelp.innerHTML = '<i class="fa-solid fa-check-circle me-1 text-danger"></i>Nilai rendah = Lebih baik';
        }
        
        const weight = parseFloat(originalWeight);
        const percentage = (weight * 100).toFixed(1);
        previewWeight.innerHTML = `<i class="fa-solid fa-weight-hanging me-1"></i>Bobot: ${weight} (${percentage}%)`;
    }, 10);
});

// Warn before leaving with unsaved changes
window.addEventListener('beforeunload', function(e) {
    if (hasChanges()) {
        e.preventDefault();
        e.returnValue = '';
    }
});
</script>
@endpush

@endsection