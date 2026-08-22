@extends('layouts.app')

@section('content')
<div class="card card-soft p-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Profil Admin</h4>
            <p class="text-muted mb-0 small">Kelola informasi akun Anda</p>
        </div>
    </div>

    <div class="row g-4">

        {{-- Ringkasan Profil --}}
        <div class="col-lg-4">
            <div class="profile-summary text-center h-100">
                <div class="profile-avatar mx-auto mb-3">
                    <i class="fa-solid fa-user"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ Auth::user()->name }}</h5>
                <p class="text-muted small mb-3">{{ Auth::user()->email }}</p>
                <span class="badge bg-primary-subtle text-primary px-3 py-2">
                    <i class="fa-solid fa-shield-halved me-1"></i>Administrator
                </span>
            </div>
        </div>

        {{-- Form Informasi Akun --}}
        <div class="col-lg-8">
            <div class="border rounded-3 p-4 h-100">

                <div class="d-flex align-items-center mb-4">
                    <div class="stat-icon bg-primary-subtle me-3">
                        <i class="fa-solid fa-address-card text-primary"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Informasi Akun</h5>
                        <p class="text-muted mb-0 small">
                            Informasi akun yang sedang digunakan
                        </p>
                    </div>
                </div>

                {{-- Nama --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <i class="fa-solid fa-id-badge me-1 text-muted"></i>Nama
                    </label>
                    <input type="text"
                           class="form-control"
                           value="{{ Auth::user()->name }}"
                           readonly>
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        <i class="fa-solid fa-envelope me-1 text-muted"></i>Email
                    </label>
                    <input type="email"
                           class="form-control"
                           value="{{ Auth::user()->email }}"
                           readonly>
                </div>

                <hr class="my-4">

                {{-- Tombol Pengaturan --}}
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('profile.email.edit') }}"
                       class="btn btn-primary">
                        <i class="fa-solid fa-envelope me-2"></i>Ubah Email
                    </a>

                    <a href="{{ route('profile.password.edit') }}"
                       class="btn btn-outline-primary">
                        <i class="fa-solid fa-lock me-2"></i>Ubah Password
                    </a>
                </div>

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

.profile-summary {
    background: linear-gradient(180deg, #f8fafc 0%, #eef2f7 100%);
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 2rem 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.profile-avatar {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: #e7edff;
    color: #3b6fe0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.25rem;
}
</style>
@endsection