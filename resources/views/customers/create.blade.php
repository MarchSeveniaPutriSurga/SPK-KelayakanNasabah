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
                    form="customerForm"
                    class="btn btn-primary px-3 py-2 fw-semibold">

                <i class="fa-solid fa-save me-1"></i>
                Simpan

            </button>

            <button type="reset"
                    form="customerForm"
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
    <div class="alert alert-info d-flex align-items-center mb-4">
        <i class="fa-solid fa-circle-info fs-4 me-3"></i>

        <div>
            <strong>Informasi:</strong>
            Field bertanda <span class="text-danger">*</span> wajib diisi.
            <br>

            <small>
                NIK harus unik dan terdiri dari 16 digit angka.
            </small>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('customers.store') }}"
          method="POST"
          id="customerForm">

        @csrf

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
                       class="form-control form-control-lg @error('nik') is-invalid @enderror"
                       placeholder="Contoh: 340412xxxxxxxx"
                       maxlength="16"
                       value="{{ old('nik', $customer->nik ?? '') }}"
                       id="nikInput"
                       oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                       required>

                @error('nik')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

                <div class="form-text">
                    Masukkan 16 digit NIK nasabah
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
                       class="form-control form-control-lg @error('name') is-invalid @enderror"
                       placeholder="Contoh: Budi Santoso"
                       value="{{ old('name', $customer->name ?? '') }}"
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
                       class="form-control form-control-lg"
                       placeholder="Contoh: Panggang, Giriwungu"
                       value="{{ old('identifier', $customer->identifier ?? '') }}"
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
                       class="form-control form-control-lg"
                       placeholder="Contoh: Petani"
                       value="{{ old('phone', $customer->phone ?? '') }}"
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
    border-color: var(--primary);
    box-shadow: 0 0 0 0.25rem rgba(99,102,241,.15);
}

.form-label {
    color: #495057;
}

.form-text {
    font-size: 0.85rem;
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
}

</style>

@push('scripts')

<script>

// Form inputs
const nikInput = document.getElementById('nikInput');
const nameInput = document.getElementById('nameInput');

// Validation
document.getElementById('customerForm')
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

    if (!confirm(`Simpan data nasabah "${name}"?`)) {
        e.preventDefault();
        return false;
    }

});

// Reset
document.querySelector('button[type="reset"]')
.addEventListener('click', function(e) {

    if (!confirm('Reset semua input?')) {
        e.preventDefault();
    }

});

</script>

@endpush

@endsection