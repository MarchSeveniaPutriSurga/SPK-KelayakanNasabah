@extends('layouts.app')

@section('content')

<div class="card card-soft p-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Reset Password</h4>
            <p class="text-muted mb-0 small">
                Buat password baru untuk akun Anda
            </p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="border rounded-3 p-4">

                {{-- Icon & Judul --}}
                <div class="text-center mb-4">

                    <div class="reset-icon mx-auto mb-3">
                        <i class="fa-solid fa-lock"></i>
                    </div>

                    <h5 class="fw-bold mb-2">
                        Buat Password Baru
                    </h5>

                    <p class="text-muted small mb-0">
                        Silakan masukkan password baru untuk akun Anda.
                    </p>

                </div>

                {{-- Form Reset Password --}}
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <input type="hidden" name="email" value="{{ $email }}">

                    {{-- Password Baru --}}
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">
                            <i class="fa-solid fa-lock me-1 text-muted"></i>
                            Password Baru
                        </label>

                        <input
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            required
                            autocomplete="new-password"
                        >

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <small class="text-muted">
                            Gunakan minimal 8 karakter.
                        </small>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold">
                            <i class="fa-solid fa-lock me-1 text-muted"></i>
                            Konfirmasi Password Baru
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                        >
                    </div>

                    {{-- Informasi --}}
                    <div class="alert alert-info d-flex align-items-start small mb-4">
                        <i class="fa-solid fa-circle-info me-2 mt-1"></i>

                        <div>
                            Pastikan password baru Anda mudah diingat
                            tetapi tetap aman dan tidak digunakan pada
                            akun lain.
                        </div>
                    </div>

                    {{-- Button --}}
                    <div class="d-flex gap-2">

                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-key me-2"></i>
                            Reset Password
                        </button>

                        <a href="{{ route('profile.index') }}"
                           class="btn btn-outline-secondary">
                            Batal
                        </a>

                    </div>

                </form>

            </div>

        </div>
    </div>

</div>

<style>
    .reset-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: #e8f0eb;
        color: #5f7d68;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
</style>

@endsection