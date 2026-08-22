@extends('layouts.app')

@section('content')
<div class="card card-soft p-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Ubah Email</h4>
            <p class="text-muted mb-0 small">Perbarui alamat email akun Anda</p>
        </div>
    </div>

    <div class="row g-4">

        {{-- Form Ubah Email --}}
        <div class="col-lg-8">
            <div class="border rounded-3 p-4 h-100">

                <div class="d-flex align-items-center mb-4">
                    <div class="stat-icon bg-primary-subtle me-3">
                        <i class="fa-solid fa-envelope text-primary"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Ubah Email</h5>
                        <p class="text-muted mb-0 small">
                            Masukkan email baru dan password saat ini
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.email.update') }}">
                    @csrf
                    @method('PUT')

                    {{-- Email Baru --}}
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">
                            <i class="fa-solid fa-at me-1 text-muted"></i>Email Baru
                        </label>

                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               required>

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Password Saat Ini --}}
                    <div class="mb-4">
                        <label for="current_password" class="form-label fw-semibold">
                            <i class="fa-solid fa-key me-1 text-muted"></i>Password Saat Ini
                        </label>

                        <input type="password"
                               class="form-control @error('current_password') is-invalid @enderror"
                               id="current_password"
                               name="current_password"
                               required>

                        @error('current_password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Perubahan
                        </button>

                        <a href="{{ route('profile.index') }}"
                           class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>

        {{-- Informasi --}}
        <div class="col-lg-4">
            <div class="info-tips h-100">
                <div class="d-flex align-items-center mb-3">
                    <div class="stat-icon bg-info-subtle me-2" style="width:40px;height:40px;font-size:1rem;">
                        <i class="fa-solid fa-circle-info text-info"></i>
                    </div>
                    <h6 class="fw-bold mb-0">Perlu Diperhatikan</h6>
                </div>
                <ul class="info-tips-list">
                    <li>Pastikan email baru masih aktif dan bisa diakses</li>
                    <li>Email digunakan untuk login ke sistem</li>
                    <li>Password saat ini diperlukan untuk verifikasi keamanan</li>
                </ul>
            </div>
        </div>

    </div>
</div>

<style>
.stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.info-tips {
    background: linear-gradient(180deg, #eff8ff 0%, #e6f3fb 100%);
    border: 1px solid #cfe8f7;
    border-radius: 12px;
    padding: 1.5rem;
}

.info-tips-list {
    padding-left: 1.1rem;
    margin-bottom: 0;
}

.info-tips-list li {
    font-size: 0.875rem;
    color: #0c5a7a;
    margin-bottom: 0.6rem;
    line-height: 1.4;
}

.info-tips-list li:last-child {
    margin-bottom: 0;
}
</style>
@endsection