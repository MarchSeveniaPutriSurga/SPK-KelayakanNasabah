@extends('layouts.app')

@section('content')
<div class="card card-soft p-4">
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <div class="icon-circle me-3">
            <i class="fa-solid fa-user-plus"></i>
        </div>
        <div>
            <h4 class="mb-1 fw-bold">Tambah Nasabah Baru</h4>
            <p class="text-muted mb-0 small">Masukkan informasi nasabah untuk sistem penilaian</p>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="alert alert-info d-flex align-items-center mb-4">
        <i class="fa-solid fa-info-circle fs-4 me-3"></i>
        <div>
            <strong>Informasi:</strong> Field yang bertanda <span class="text-danger">*</span> wajib diisi.
            <br>
            <small>Email dan nomor telepon bersifat opsional namun disarankan untuk dilengkapi.</small>
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
                    <i class="fa-solid fa-lightbulb me-1"></i>Masukkan nama lengkap sesuai identitas
                </div>
            </div>

            <!-- Identifier -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-id-card me-2"></i>Email
                </label>
                <input type="text" 
                       name="identifier" 
                       class="form-control form-control-lg" 
                       placeholder="Contoh: adinda@gmail.com"
                       id="identifierInput">
                <div class="form-text">
                    <i class="fa-solid fa-lightbulb me-1"></i>Email nasabah (opsional)
                </div>
            </div>

            <!-- Nomor Telepon -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-phone me-2"></i>Nomor Telepon
                </label>
                <input type="tel" 
                       name="phone" 
                       class="form-control form-control-lg" 
                       placeholder="Contoh: 081234567890"
                       id="phoneInput">
                <div class="form-text">
                    <i class="fa-solid fa-lightbulb me-1"></i>Nomor telepon aktif untuk dihubungi (opsional)
                </div>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="mt-4">
            <label class="form-label fw-semibold">
                <i class="fa-solid fa-eye me-2"></i>Preview Data Nasabah
            </label>
            <div class="preview-card">
                <div class="preview-avatar" id="previewAvatar">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="preview-content">
                    <h5 class="mb-1" id="previewName">Nama Nasabah</h5>
                    <div class="preview-details">
                        <span class="preview-item" id="previewIdentifier">
                            <i class="fa-solid fa-id-card me-1"></i>-
                        </span>
                        <span class="preview-item" id="previewPhone">
                            <i class="fa-solid fa-phone me-1"></i>-
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-save me-2"></i>Simpan Nasabah
            </button>
            <button type="reset" class="btn btn-outline-secondary btn-lg">
                <i class="fa-solid fa-rotate-left me-2"></i>Reset Form
            </button>
            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary btn-lg">
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

.preview-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
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
    
    .preview-avatar {
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
const nameInput = document.getElementById('nameInput');
const identifierInput = document.getElementById('identifierInput');
const phoneInput = document.getElementById('phoneInput');

// Preview elements
const previewName = document.getElementById('previewName');
const previewIdentifier = document.getElementById('previewIdentifier');
const previewPhone = document.getElementById('previewPhone');
const previewAvatar = document.getElementById('previewAvatar');

// Update preview name
nameInput.addEventListener('input', function() {
    const name = this.value.trim();
    if (name) {
        previewName.textContent = name;
        previewAvatar.textContent = name.charAt(0).toUpperCase();
    } else {
        previewName.textContent = 'Nama Nasabah';
        previewAvatar.innerHTML = '<i class="fa-solid fa-user"></i>';
    }
});

// Update preview identifier
identifierInput.addEventListener('input', function() {
    const identifier = this.value.trim();
    previewIdentifier.innerHTML = identifier 
        ? `<i class="fa-solid fa-id-card me-1"></i>${identifier}` 
        : '<i class="fa-solid fa-id-card me-1"></i>-';
});

// Update preview phone
phoneInput.addEventListener('input', function() {
    let phone = this.value.trim();
    
    // Format phone number (optional)
    phone = phone.replace(/\D/g, '');
    
    previewPhone.innerHTML = phone 
        ? `<i class="fa-solid fa-phone me-1"></i>${phone}` 
        : '<i class="fa-solid fa-phone me-1"></i>-';
});

// Phone number validation (only allow numbers)
phoneInput.addEventListener('keypress', function(e) {
    if (!/[0-9]/.test(e.key) && e.key !== 'Backspace') {
        e.preventDefault();
    }
});

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