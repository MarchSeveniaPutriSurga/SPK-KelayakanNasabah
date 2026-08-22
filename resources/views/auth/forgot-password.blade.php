<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lupa Password - SPK Kelayakan Nasabah</title>

    <link rel="icon"
          type="image/png"
          href="{{ asset('images/logo-bumkalma.png') }}">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="{{ asset('css/auth2.css') }}" rel="stylesheet">
</head>

<body>

    {{-- Background --}}
    <div class="bg-photo"></div>
    <div class="bg-overlay"></div>

    {{-- Dekorasi --}}
    <div class="deco-line"></div>
    <div class="deco-line deco-line-right"></div>

    {{-- Tahun --}}
    <div class="company-year">{{ date('Y') }}</div>

    {{-- Card --}}
    <div class="login-container">

        <div class="login-card">

            <div class="login-header">

                <div class="logo-wrap">
                    <img src="{{ asset('images/logo-bumkalma.png') }}"
                         alt="Logo BUMKalma">
                </div>

                <h2>Lupa Password?</h2>

                <p>
                    Masukkan email yang terdaftar untuk menerima
                    link reset password
                </p>

                <div class="header-rule"></div>

            </div>

            <form class="login-form"
                  method="POST"
                  action="{{ route('password.email') }}">

                @csrf

                <div class="form-group">

                    <div class="input-group neu-input">

                        <input type="email"
                               id="email"
                               name="email"
                               required
                               autocomplete="email"
                               placeholder=" "
                               value="{{ old('email') }}">

                        <label for="email">
                            Alamat Email
                        </label>

                        <div class="input-icon">
                            <i class="fa-solid fa-envelope"></i>
                        </div>

                    </div>

                    @error('email')
                        <span class="error-message"
                              style="display: block;">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                <button type="submit" class="neu-button">
                    Kirim Link Reset
                </button>

            </form>

            @if (session('success'))
                <p class="server-success">
                    {{ session('success') }}
                </p>
            @endif

            <div class="forgot-password" style="text-align: center; margin-top: 18px;">
                <a href="{{ route('login') }}">
                    <i class="fa-solid fa-arrow-left me-1"></i>
                    Kembali ke Login
                </a>
            </div>

        </div>

    </div>

</body>

</html>