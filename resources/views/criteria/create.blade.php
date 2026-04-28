@extends('layouts.app')

@section('content')
<div class="card card-soft p-4">
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <div class="icon-circle me-3">
            <i class="fa-solid fa-plus"></i>
        </div>
        <div>
            <h4 class="mb-1 fw-bold">Tambah Kriteria Baru</h4>
            <p class="text-muted mb-0 small">Definisikan kriteria penilaian untuk metode SAW</p>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="alert alert-info d-flex align-items-center mb-4">
        <i class="fa-solid fa-info-circle fs-4 me-3"></i>
        <div>
            <strong>Informasi:</strong> Total bobot semua kriteria harus sama dengan <strong>1.00</strong> (100%).
            <br>
            <small>Gunakan kode seperti C1, C2, C3, dll. Pilih jenis Benefit (semakin tinggi semakin baik) atau Cost (semakin rendah semakin baik).</small>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('criteria.store') }}" method="post" id="criteriaForm">
        @csrf
        
        <div class="row g-4">
            <!-- Kode Kriteria -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-code me-2"></i>Kode Kriteria
                    <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="code" 
                       class="form-control form-control-lg" 
                       placeholder="Contoh: C1, C2, C3"
                       id="codeInput"
                       maxlength="10"
                       style="text-transform: uppercase;"
                       required>
                <div class="form-text">
                    <i class="fa-solid fa-lightbulb me-1"></i>Kode unik untuk identifikasi kriteria (maks 10 karakter)
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
                    <option value="benefit">Benefit (Semakin tinggi semakin baik)</option>
                    <option value="cost">Cost (Semakin rendah semakin baik)</option>
                </select>
                <div class="form-text" id="typeHelp">
                    <i class="fa-solid fa-circle-question me-1"></i>Pilih jenis berdasarkan karakteristik kriteria
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
                    <i class="fa-solid fa-question"></i>
                </div>
                <div class="preview-content">
                    <h5 class="mb-1" id="previewName">Nama Kriteria</h5>
                    <div class="preview-details">
                        <span class="preview-item" id="previewType">
                            <i class="fa-solid fa-circle-question me-1"></i>Jenis: -
                        </span>
                        <span class="preview-item" id="previewWeight">
                            <i class="fa-solid fa-weight-hanging me-1"></i>Bobot: -
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Box -->
        <div class="mt-4">
            <div class="help-box">
                <h6 class="mb-2"><i class="fa-solid fa-circle-info me-2"></i>Panduan Pemilihan Jenis:</h6>
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
                <i class="fa-solid fa-save me-2"></i>Simpan Kriteria
            </button>
            <button type="reset" class="btn btn-outline-secondary btn-lg">
                <i class="fa-solid fa-rotate-left me-2"></i>Reset Form
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
    background: linear-gradient(135deg, var(--primary), var(--accent));
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
    border: 2px solid var(--primary);
    border-radius: 16px;
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    transition: all 0.3s ease;
}

.preview-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
}

.preview-code {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary), var(--accent));
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
    color: var(--primary);
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
}
</style>

@push('scripts')
<script>
// Form inputs
const codeInput = document.getElementById('codeInput');
const nameInput = document.getElementById('nameInput');
const typeSelect = document.getElementById('typeSelect');
const weightInput = document.getElementById('weightInput');

// Preview elements
const previewCode = document.getElementById('previewCode');
const previewName = document.getElementById('previewName');
const previewType = document.getElementById('previewType');
const previewWeight = document.getElementById('previewWeight');
const typeHelp = document.getElementById('typeHelp');

// Auto uppercase code
codeInput.addEventListener('input', function() {
    this.value = this.value.toUpperCase();
    const code = this.value.trim();
    previewCode.textContent = code || '?';
});

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
    } else {
        previewType.innerHTML = '<i class="fa-solid fa-circle-question me-1"></i>Jenis: -';
        typeHelp.innerHTML = '<i class="fa-solid fa-circle-question me-1"></i>Pilih jenis berdasarkan karakteristik kriteria';
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

// Form validation
document.getElementById('criteriaForm').addEventListener('submit', function(e) {
    const code = codeInput.value.trim();
    const name = nameInput.value.trim();
    const type = typeSelect.value;
    const weight = parseFloat(weightInput.value);
    
    if (!code || !name || !type) {
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
    
    // Confirm before submit
    if (!confirm(`Simpan kriteria "${code} - ${name}"?`)) {
        e.preventDefault();
        return false;
    }
});

// Reset form handler
document.querySelector('button[type="reset"]').addEventListener('click', function(e) {
    if (!confirm('Reset semua input? Data yang dimasukkan akan hilang.')) {
        e.preventDefault();
        return false;
    }
    
    // Reset preview
    setTimeout(() => {
        previewCode.innerHTML = '<i class="fa-solid fa-question"></i>';
        previewName.textContent = 'Nama Kriteria';
        previewType.innerHTML = '<i class="fa-solid fa-circle-question me-1"></i>Jenis: -';
        previewWeight.innerHTML = '<i class="fa-solid fa-weight-hanging me-1"></i>Bobot: -';
        typeHelp.innerHTML = '<i class="fa-solid fa-circle-question me-1"></i>Pilih jenis berdasarkan karakteristik kriteria';
    }, 10);
});
</script>
@endpush

@endsection