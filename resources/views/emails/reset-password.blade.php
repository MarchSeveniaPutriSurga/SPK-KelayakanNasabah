<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f4f5f2; font-family: Arial, sans-serif;">

    <div style="max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; overflow: hidden;">

        {{-- Header / Logo --}}
        <div style="text-align: center; padding: 30px 20px 20px;">

            <img
                src="{{ asset('images/logo-bumkalma.png') }}"
                alt="Logo BUMKalma"
                style="width: 90px; height: 90px; object-fit: contain;"
            >

            <h2 style="margin: 15px 0 5px; color: #8ec7d6;">
                SPK Penentuan Kelayakan Nasabah
            </h2>

            <p style="margin: 0; color: #777;">
                BUMKalma Mitra Lestari
            </p>

        </div>

        {{-- Isi Email --}}
        <div style="padding: 25px 35px 35px; color: #333;">

            <h3 style="margin-top: 0; color: #333;">
                Reset Password
            </h3>

            <p>
                Halo, {{ $user->name }}!
            </p>

            <p>
                Kami menerima permintaan untuk mengatur ulang password
                akun Anda pada Sistem Pendukung Keputusan Penentuan
                Kelayakan Nasabah.
            </p>

            <p>
                Silakan klik tombol di bawah ini untuk membuat password baru:
            </p>

            {{-- Tombol Reset Password --}}
            <div style="text-align: center; margin: 30px 0;">

                <a href="{{ $url }}"
                   style="
                       display: inline-block;
                       padding: 13px 28px;
                       background-color: #8ec7d6;
                       color: #ffffff;
                       text-decoration: none;
                       border-radius: 6px;
                       font-weight: bold;
                   ">
                    Reset Password
                </a>

            </div>

            {{-- Informasi Expired --}}
            <p style="font-size: 14px; color: #666;">
                Link reset password ini akan kedaluwarsa dalam
                <strong>60 menit</strong>.
            </p>

            {{-- Link Alternatif --}}
            <p style="font-size: 14px; color: #666;">
                Jika tombol <strong>Reset Password</strong> di atas tidak
                dapat digunakan, silakan salin dan buka link berikut
                pada browser:
            </p>

            <p style="font-size: 13px; word-break: break-all;">
                <a href="{{ $url }}"
                   style="color: #00aeff;">
                    {{ $url }}
                </a>
            </p>

            {{-- Peringatan --}}
            <p style="font-size: 14px; color: #666; margin-top: 25px;">
                Jika Anda tidak merasa melakukan permintaan reset password,
                Anda dapat mengabaikan email ini.
            </p>

            <p style="margin-top: 30px;">
                Salam,<br>
                <strong>Tim SPK BUMKalma</strong>
            </p>

        </div>

        {{-- Footer --}}
        <div style="
            background-color: #f4f5f2;
            text-align: center;
            padding: 18px;
            color: #777;
            font-size: 12px;
        ">
            SPK Penentuan Kelayakan Nasabah<br>
            BUMKalma Mitra Lestari
        </div>

    </div>

</body>

</html>