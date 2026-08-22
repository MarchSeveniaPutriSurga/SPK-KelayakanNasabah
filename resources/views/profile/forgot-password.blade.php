@extends('layouts.app')

@section('content')

<div class="card card-soft p-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Lupa Password</h4>
            <p class="text-muted mb-0 small">
                Kirim link untuk mengatur ulang password akun Anda
            </p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="border rounded-3 p-4">

                {{-- Icon & Judul --}}
                <div class="text-center mb-4">

                    <div class="forgot-icon mx-auto mb-3">
                        <i class="fa-solid fa-key"></i>
                    </div>

                    <h5 class="fw-bold mb-2">
                        Lupa Password Saat Ini?
                    </h5>

                    <p class="text-muted small mb-0">
                        Jangan khawatir.
                        Kami akan mengirimkan link untuk mengatur ulang password anda pada email akun yang sudah terdaftar.
                    </p>

                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('profile.password.email') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold">
                            <i class="fa-solid fa-envelope me-1 text-muted"></i>
                            Email Akun
                        </label>

                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            value="{{ auth()->user()->email }}"
                            readonly
                        >

                        <small class="text-muted">
                            Link reset password akan dikirim ke email ini.
                        </small>
                    </div>

                    {{-- Button --}}
                    <div class="d-flex gap-2">

                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-paper-plane me-2"></i>
                            Kirim Link Reset
                        </button>

                        <a href="{{ route('profile.password.edit') }}"
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
    .forgot-icon {
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