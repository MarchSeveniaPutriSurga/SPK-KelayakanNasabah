<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Kelayakan Nasabah</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-bumkalma.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="{{ asset('css/auth2.css') }}" rel="stylesheet">
</head>
<body>

    <!-- Background foto perusahaan (taruh foto di public/images/bg-company.jpg) -->
    <div class="bg-photo"></div>
    <div class="bg-overlay"></div>

    <!-- Dekorasi garis tipis kiri & kanan -->
    <div class="deco-line"></div>
    <div class="deco-line deco-line-right"></div>

    <!-- Tahun pojok kanan bawah -->
    <div class="company-year">{{ date('Y') }}</div>

    <!-- Card Login -->
    <div class="login-container">
        <div class="login-card">

            <div class="login-header">
                <div class="logo-wrap">
                    <img src="{{ asset('images/logo-bumkalma.png') }}" alt="Logo BUMKalma">
                </div>
                <h2>SPK Penentuan Kelayakan Nasabah</h2>
                <p>Masuk untuk melanjutkan ke sistem</p>
                <div class="header-rule"></div>
            </div>

            <form class="login-form" id="loginForm" method="POST" action="{{ route('login.index') }}" novalidate>
                @csrf

                <div class="form-group">
                    <div class="input-group neu-input">
                        <input type="email" id="email" name="email"
                               required autocomplete="email" placeholder=" "
                               value="{{ old('email') }}">
                        <label for="email">Alamat Email</label>
                        <div class="input-icon">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                    </div>
                    <span class="error-message" id="emailError"></span>
                </div>

                <div class="form-group">
                    <div class="input-group neu-input password-group">
                        <input type="password" id="password" name="password"
                               required autocomplete="current-password" placeholder=" ">
                        <label for="password">Password</label>
                        <div class="input-icon">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <button type="button" class="neu-toggle" id="passwordToggle" title="Tampilkan password">
                            <i class="fa-solid fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    <span class="error-message" id="passwordError"></span>
                </div>

                <button type="submit" class="neu-button">
                    Masuk
                </button>
            </form>

            @if ($errors->any())
                <p class="server-error">{{ $errors->first() }}</p>
            @endif

        </div>
    </div>

    <script>
        document.getElementById('passwordToggle').addEventListener('click', function () {
            const pw = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            pw.type = pw.type === 'password' ? 'text' : 'password';
            icon.className = pw.type === 'password' ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash';
        });
    </script>

</body>
</html>