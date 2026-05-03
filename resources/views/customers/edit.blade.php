@extends('layouts.app')

@section('content')
<div class="card card-soft p-4">
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
        <button type="submit" form="editForm" class="btn btn-primary">
            <i class="fa-solid fa-save me-1"></i> Update
        </button>

        <button type="reset" form="editForm" class="btn btn-outline-secondary">
            <i class="fa-solid fa-rotate-left me-1"></i> Reset
        </button>

        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

</div>

    <!-- Info Alert -->
    <div class="alert alert-warning d-flex align-items-center mb-4">
        <i class="fa-solid fa-triangle-exclamation fs-4 me-3"></i>
        <div>
            <strong>Perhatian:</strong> Pastikan data yang Anda ubah sudah benar sebelum menyimpan.
            <br>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('customers.update', $customer->id) }}" method="post" id="editForm">
        @csrf 
        @method('PUT')
        
        <div class="row g-4">
            <!-- Nama Nasabah -->
            <div class="col-md-12">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-user me-2"></i>Nama Lengkap Nasabah
                    <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       value="{{ $customer->name }}" 
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
                     <i class="fa-solid fa-house"></i>Alamat
                </label>
                <input type="text" 
                       name="identifier" 
                       value="{{ $customer->identifier }}" 
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
                       value="{{ $customer->phone }}" 
                       class="form-control form-control-lg" 
                       placeholder="Contoh: Petani"
                       id="phoneInput">
                <div class="form-text">
                    Usaha atau pekerjaan nasabah (opsional)
                </div>
            </div>
        </div>

        <!-- Change History (Optional) -->
        <div class="mt-4">
            <div class="info-box">
                <i class="fa-solid fa-clock-rotate-left me-2"></i>
                <div>
                    <strong>Informasi Nasabah:</strong>
                    <br>
                    <small class="text-muted">
                        Nasabah ini terdaftar sejak {{ $customer->created_at ? $customer->created_at->format('d F Y') : '-' }}
                    </small>
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
    background: linear-gradient(135deg, #f59e0b, #ef4444);
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
const identifierInput = document.getElementById('identifierInput');
const phoneInput = document.getElementById('phoneInput');

// Store original values
const originalName = nameInput.value;
const originalIdentifier = identifierInput.value;
const originalPhone = phoneInput.value;


// Check for changes
function hasChanges() {
    return nameInput.value !== originalName || 
           identifierInput.value !== originalIdentifier || 
           phoneInput.value !== originalPhone;
}

// Form validation
document.getElementById('editForm').addEventListener('submit', function(e) {
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
    
    if (!hasChanges()) {
        e.preventDefault();
        alert('Tidak ada perubahan data yang dilakukan.');
        return false;
    }
    
    // Confirm before submit
    if (!confirm(`Update data nasabah "${name}"?\n\nPerubahan akan disimpan ke database.`)) {
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
        identifierInput.value = originalIdentifier;
        phoneInput.value = originalPhone;
        
        // Update preview
        previewName.textContent = originalName;
        previewAvatar.textContent = originalName.charAt(0).toUpperCase();
        previewIdentifier.innerHTML = originalIdentifier 
            ? `<i class="fa-solid fa-id-card me-1"></i>${originalIdentifier}` 
            : '<i class="fa-solid fa-id-card me-1"></i>-';
        previewPhone.innerHTML = originalPhone 
            ? `<i class="fa-solid fa-phone me-1"></i>${originalPhone}` 
            : '<i class="fa-solid fa-phone me-1"></i>-';
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