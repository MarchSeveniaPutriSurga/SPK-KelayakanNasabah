@extends('layouts.app')

@section('content')

<div class="card card-soft p-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <!-- Kiri -->
        <div class="d-flex align-items-center">

            <div class="icon-circle me-3">
                <i class="fa-solid fa-user-pen"></i>
            </div>

            <div>
                <h4 class="mb-1 fw-bold">
                    Edit Data Nasabah
                </h4>

                <p class="text-muted mb-0 small">
                    Perbarui informasi nasabah yang sudah ada
                </p>
            </div>

        </div>

        <!-- Kanan -->
        <div class="d-flex gap-2 flex-wrap">

            <button type="submit"
                    form="editForm"
                    class="btn btn-primary px-3 py-2 fw-semibold">

                <i class="fa-solid fa-save me-1"></i>
                Update

            </button>

            <button type="reset"
                    form="editForm"
                    class="btn btn-outline-secondary px-3 py-2 fw-semibold">

                <i class="fa-solid fa-rotate-left me-1"></i>
                Reset

            </button>

            <a href="{{ route('customers.index') }}"
               class="btn btn-outline-secondary px-3 py-2 fw-semibold">

                <i class="fa-solid fa-arrow-left me-1"></i>
                Kembali

            </a>

        </div>

    </div>

    <!-- Alert -->
    <div class="alert alert-warning d-flex align-items-center mb-4">

        <i class="fa-solid fa-triangle-exclamation fs-4 me-3"></i>

        <div>
            <strong>Perhatian:</strong>
            Pastikan data yang diubah sudah benar sebelum disimpan.
            <br>

            <small>
                NIK harus unik dan terdiri dari 16 digit angka.
            </small>
        </div>

    </div>

    <!-- Form -->
    <form action="{{ route('customers.update', $customer->id) }}"
          method="POST"
          id="editForm">

        @csrf
        @method('PUT')

        <div class="row g-4">

            <!-- NIK -->
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-id-card me-2"></i>
                    NIK
                    <span class="text-danger">*</span>
                </label>

                <input type="text"
                       name="nik"
                       value="{{ old('nik', $customer->nik) }}"
                       class="form-control form-control-lg @error('nik') is-invalid @enderror"
                       placeholder="Masukkan 16 digit NIK"
                       maxlength="16"
                       id="nikInput"
                       oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                       required>

                @error('nik')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

                <div class="form-text">
                    NIK harus unik dan tidak boleh sama
                </div>

            </div>

            <!-- Nama -->
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-user me-2"></i>
                    Nama Lengkap Nasabah
                    <span class="text-danger">*</span>
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name', $customer->name) }}"
                       class="form-control form-control-lg @error('name') is-invalid @enderror"
                       placeholder="Contoh: Budi Santoso"
                       id="nameInput"
                       required>

                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

                <div class="form-text">
                    Masukkan nama lengkap sesuai identitas
                </div>

            </div>

            <!-- Alamat -->
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-house me-2"></i>
                    Alamat
                </label>

                <input type="text"
                       name="identifier"
                       value="{{ old('identifier', $customer->identifier) }}"
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
                    <i class="fa-solid fa-briefcase me-2"></i>
                    Usaha
                </label>

                <input type="text"
                       name="phone"
                       value="{{ old('phone', $customer->phone) }}"
                       class="form-control form-control-lg"
                       placeholder="Contoh: Petani"
                       id="phoneInput">

                <div class="form-text">
                    Usaha atau pekerjaan nasabah (opsional)
                </div>

            </div>

        </div>

        <!-- Info -->
        <div class="mt-4">

            <div class="info-box">

                <i class="fa-solid fa-clock-rotate-left"></i>

                <div>
                    <strong>Informasi Nasabah</strong>
                    <br>

                    <small class="text-muted">
                        Terdaftar sejak
                        {{ $customer->created_at ? $customer->created_at->format('d F Y') : '-' }}
                    </small>

                </div>

            </div>

        </div>

    </form>

</div>

<style>

/* Icon */
.icon-circle {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f59e0b, #ef4444);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
}

/* Input */
.form-control-lg {
    border-radius: 12px;
    padding: 0.85rem 1rem;
    border: 2px solid #e9ecef;
    transition: 0.2s ease;
}

.form-control-lg:focus {
    border-color: #f59e0b;
    box-shadow: 0 0 0 0.25rem rgba(245,158,11,.15);
}

/* Info Box */
.info-box {
    background: #f8f9fa;
    border-left: 4px solid #6c757d;
    border-radius: 10px;
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.info-box i {
    font-size: 1.2rem;
    color: #6c757d;
}

/* Alert */
.alert {
    border-radius: 14px;
    border: none;
}

/* Button */
.btn {
    border-radius: 10px;
}

/* Responsive */
@media (max-width: 768px) {

    .icon-circle {
        width: 48px;
        height: 48px;
        font-size: 1.2rem;
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

// Inputs
const nikInput = document.getElementById('nikInput');
const nameInput = document.getElementById('nameInput');
const identifierInput = document.getElementById('identifierInput');
const phoneInput = document.getElementById('phoneInput');

// Original values
const originalNik = nikInput.value;
const originalName = nameInput.value;
const originalIdentifier = identifierInput.value;
const originalPhone = phoneInput.value;

// Check changes
function hasChanges() {

    return nikInput.value !== originalNik ||
           nameInput.value !== originalName ||
           identifierInput.value !== originalIdentifier ||
           phoneInput.value !== originalPhone;

}

// Validation
document.getElementById('editForm')
.addEventListener('submit', function(e) {

    const nik = nikInput.value.trim();
    const name = nameInput.value.trim();

    if (!nik) {
        e.preventDefault();
        alert('NIK wajib diisi!');
        nikInput.focus();
        return false;
    }

    if (nik.length !== 16) {
        e.preventDefault();
        alert('NIK harus 16 digit!');
        nikInput.focus();
        return false;
    }

    if (!name) {
        e.preventDefault();
        alert('Nama nasabah wajib diisi!');
        nameInput.focus();
        return false;
    }

    if (name.length < 3) {
        e.preventDefault();
        alert('Nama minimal 3 karakter!');
        nameInput.focus();
        return false;
    }

    if (!hasChanges()) {
        e.preventDefault();
        alert('Tidak ada perubahan data.');
        return false;
    }

    if (!confirm(`Update data nasabah "${name}"?`)) {
        e.preventDefault();
        return false;
    }

});

// Reset
document.querySelector('button[type="reset"]')
.addEventListener('click', function(e) {

    if (!confirm('Reset form ke data awal?')) {
        e.preventDefault();
    }

});

// Warning leave page
window.addEventListener('beforeunload', function(e) {

    if (hasChanges()) {
        e.preventDefault();
        e.returnValue = '';
    }

});

</script>

@endpush

@endsection