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
                <h4 class="mb-1 fw-bold">Edit Data Nasabah</h4>
                <p class="text-muted mb-0 small">Perbarui informasi nasabah yang sudah ada</p>
            </div>
        </div>

        <!-- Kanan: semua tombol -->
        <div class="d-flex gap-2">
            <button type="submit" form="customerForm" class="btn btn-primary">
                <i class="fa-solid fa-save me-1"></i> Simpan
            </button>

            <button type="reset" form="customerForm" class="btn btn-outline-secondary">
                <i class="fa-solid fa-rotate-left me-1"></i> Reset
            </button>

            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="alert alert-info d-flex align-items-center mb-4">
        <i class="fa-solid fa-info-circle fs-4 me-3"></i>
        <div>
            <strong>Informasi:</strong> Field yang bertanda <span class="text-danger">*</span> wajib diisi.
            <br>
            <small>Alamat dan Usaha bersifat opsional namun disarankan untuk dilengkapi.</small>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('customers.store') }}" method="post" id="customerForm">
        @csrf
        
        <div class="row g-4">
            <!-- Nama Nasabah -->
            <div class="col-md-12">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-user me-2"></i>Nama Lengkap Nasabah
                    <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       class="form-control form-control-lg" 
                       placeholder="Contoh: Budi Santoso"
                       id="nameInput"
                       required>
                <div class="form-text">
                    Masukkan nama lengkap sesuai identitas
                </div>
            </div>

            <!-- Alamat -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-house"></i></i>Alamat
                </label>
                <input type="text" 
                       name="identifier" 
                       class="form-control form-control-lg" 
                       placeholder="Contoh: Panggang, Giriwungu"
                       id="identifierInput">
                <div class="form-text">
                    Alamat nasabah (opsional)
                </div>
            </div>

            <!-- Usaha -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-briefcase"></i>Usaha
                </label>
                <input type="text" 
                       name="phone" 
                       class="form-control form-control-lg" 
                       placeholder="Contoh: Petani"
                       id="phoneInput">
                <div class="form-text">
                    Usaha atau pekerjaan nasabah (opsional)
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
.form-control-lg {
    border-radius: 12px;
    padding: 0.875rem 1rem;
    font-size: 1rem;
    border: 2px solid #e9ecef;
    transition: all 0.2s ease;
}

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
const nameInput = document.getElementById('nameInput');
const identifierInput = document.getElementById('identifierInput');
const phoneInput = document.getElementById('phoneInput');

// Form validation
document.getElementById('customerForm').addEventListener('submit', function(e) {
    const name = nameInput.value.trim();
    
    if (!name) {
        e.preventDefault();
        alert('Nama nasabah wajib diisi!');
        nameInput.focus();
        return false;
    }
    
    if (name.length < 3) {
        e.preventDefault();
        alert('Nama nasabah minimal 3 karakter!');
        nameInput.focus();
        return false;
    }
    
    // Confirm before submit
    if (!confirm(`Simpan data nasabah "${name}"?`)) {
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
        previewName.textContent = 'Nama Nasabah';
        previewAvatar.innerHTML = '<i class="fa-solid fa-user"></i>';
        previewIdentifier.innerHTML = '<i class="fa-solid fa-id-card me-1"></i>-';
        previewPhone.innerHTML = '<i class="fa-solid fa-phone me-1"></i>-';
    }, 10);
});
</script>
@endpush

@endsection