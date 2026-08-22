<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reset Password - SPK Kelayakan Nasabah</title>

    <link rel="icon"
          type="image/png"
          href="{{ asset('images/logo-bumkalma.png') }}">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="{{ asset('css/auth2.css') }}" rel="stylesheet">
</head>

<body>

    <div class="bg-photo"></div>
    <div class="bg-overlay"></div>

    <div class="deco-line"></div>
    <div class="deco-line deco-line-right"></div>

    <div class="company-year">{{ date('Y') }}</div>

    <div class="login-container">

        <div class="login-card">

            <div class="login-header">

                <div class="logo-wrap">
                    <img src="{{ asset('images/logo-bumkalma.png') }}"
                         alt="Logo BUMKalma">
                </div>

                <h2>Reset Password</h2>

                <p>
                    Masukkan password baru untuk akun Anda
                </p>

                <div class="header-rule"></div>

            </div>

            <form class="login-form"
                  method="POST"
                  action="{{ route('password.update') }}">

                @csrf

                <input type="hidden"
                       name="token"
                       value="{{ $token }}">

                <div class="form-group">

                    <div class="input-group neu-input">

                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ $email }}"
                               required
                               autocomplete="email"
                               placeholder=" "
                               readonly>

                        <label for="email">
                            Alamat Email
                        </label>

                        <div class="input-icon">
                            <i class="fa-solid fa-envelope"></i>
                        </div>

                    </div>

                    @error('email')
                        <span class="error-message" style="display: block;">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                <div class="form-group">

                    <div class="input-group neu-input password-group">

                        <input type="password"
                               id="password"
                               name="password"
                               required
                               autocomplete="new-password"
                               placeholder=" ">

                        <label for="password">
                            Password Baru
                        </label>

                        <div class="input-icon">
                            <i class="fa-solid fa-lock"></i>
                        </div>

                    </div>

                    @error('password')
                        <span class="error-message" style="display: block;">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                <div class="form-group">

                    <div class="input-group neu-input password-group">

                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               required
                               autocomplete="new-password"
                               placeholder=" ">

                        <label for="password_confirmation">
                            Konfirmasi Password Baru
                        </label>

                        <div class="input-icon">
                            <i class="fa-solid fa-lock"></i>
                        </div>

                    </div>

                </div>

                <button type="submit" class="neu-button">
                    Reset Password
                </button>

            </form>

            @if ($errors->any())
                <p class="server-error">
                    {{ $errors->first() }}
                </p>
            @endif

        </div>

    </div>

</body>

</html>