@extends('layouts.app')

@section('content')
<div class="card card-soft p-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Ubah Password</h4>
            <p class="text-muted mb-0 small">Perbarui password akun Anda</p>
        </div>
    </div>

    <div class="row g-4">

        {{-- Form Ubah Password --}}
        <div class="col-lg-8">
            <div class="border rounded-3 p-4 h-100">

                <div class="d-flex align-items-center mb-4">
                    <div class="stat-icon bg-primary-subtle me-3">
                        <i class="fa-solid fa-lock text-primary"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Ubah Password</h5>
                        <p class="text-muted mb-0 small">
                            Masukkan password saat ini dan password baru Anda
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('PUT')

                    {{-- Password Saat Ini --}}
                    <div class="mb-3">
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

                    {{-- Password Baru --}}
                    <div class="mb-3">
                        <label for="new_password" class="form-label fw-semibold">
                            <i class="fa-solid fa-lock me-1 text-muted"></i>Password Baru
                        </label>

                        <input type="password"
                               class="form-control @error('new_password') is-invalid @enderror"
                               id="new_password"
                               name="new_password"
                               required>

                        @error('new_password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="mb-4">
                        <label for="new_password_confirmation" class="form-label fw-semibold">
                            <i class="fa-solid fa-lock me-1 text-muted"></i>Konfirmasi Password Baru
                        </label>

                        <input type="password"
                               class="form-control"
                               id="new_password_confirmation"
                               name="new_password_confirmation"
                               required>
                    </div>

                   <hr class="my-4">

                    <div class="mb-3 text-end">
                        <a href="{{ route('profile.password.forgot') }}"
                        class="text-decoration-none small">
                            Lupa password saat ini?
                        </a>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk me-2"></i>
                            Simpan Perubahan
                        </button>

                        <a href="{{ route('profile.index') }}"
                        class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>

        {{-- Tips Keamanan --}}
        <div class="col-lg-4">
            <div class="security-tips h-100">
                <div class="d-flex align-items-center mb-3">
                    <div class="stat-icon bg-warning-subtle me-2" style="width:40px;height:40px;font-size:1rem;">
                        <i class="fa-solid fa-shield-halved text-warning"></i>
                    </div>
                    <h6 class="fw-bold mb-0">Tips Keamanan</h6>
                </div>
                <ul class="security-tips-list">
                    <li>Gunakan minimal 8 karakter</li>
                    <li>Kombinasikan huruf besar, kecil, dan angka</li>
                    <li>Hindari menggunakan data pribadi (nama, tanggal lahir)</li>
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

.security-tips {
    background: linear-gradient(180deg, #fffbeb 0%, #fef3e2 100%);
    border: 1px solid #fde8c8;
    border-radius: 12px;
    padding: 1.5rem;
}

.security-tips-list {
    padding-left: 1.1rem;
    margin-bottom: 0;
}

.security-tips-list li {
    font-size: 0.875rem;
    color: #92400e;
    margin-bottom: 0.6rem;
    line-height: 1.4;
}

.security-tips-list li:last-child {
    margin-bottom: 0;
}
</style>
@endsection