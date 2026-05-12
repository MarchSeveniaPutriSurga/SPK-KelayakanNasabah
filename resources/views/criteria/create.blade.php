@extends('layouts.app')

@section('content')
<div class="card card-soft p-4">
       <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
    
        <!-- Kiri: icon + title -->
        <div class="d-flex align-items-center">
            <div class="icon-circle me-3">
                <i class="fa-solid fa-user-pen"></i>
            </div>
            <div>
            <h4 class="mb-1 fw-bold">Tambah Kriteria Baru</h4>
            <p class="text-muted mb-0 small">Definisikan kriteria penilaian untuk metode SMART</p>
            </div>
        </div>

        <!-- Kanan: semua tombol -->
        <div class="d-flex gap-2">
            <button type="submit" form="criteriaForm" class="btn btn-primary">
                <i class="fa-solid fa-save me-1"></i> Simpan
            </button>

            <button type="reset" form="criteriaForm" class="btn btn-outline-secondary">
                <i class="fa-solid fa-rotate-left me-1"></i> Reset
            </button>

            <a href="{{ route('criteria.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
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

// Auto uppercase code
codeInput.addEventListener('input', function() {
    this.value = this.value.toUpperCase();
    const code = this.value.trim();
    previewCode.textContent = code || '?';
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