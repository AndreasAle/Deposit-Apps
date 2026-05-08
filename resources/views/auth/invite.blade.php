<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Undangan Rubik</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- sementara lebih aman jangan index halaman referral --}}
    <meta name="robots" content="noindex, nofollow">

    <style>
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: linear-gradient(180deg, #ffffff, #e8fbf2);
            color: #12352e;
            display: grid;
            place-items: center;
            padding: 20px;
        }

        .card {
            width: min(520px, 100%);
            background: #ffffff;
            border-radius: 28px;
            padding: 28px;
            box-shadow: 0 20px 50px rgba(6,34,29,.12);
            border: 1px solid rgba(13,127,103,.12);
        }

        .logo {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            background: #e8fbf2;
            display: grid;
            place-items: center;
            margin-bottom: 18px;
            font-weight: 900;
            color: #0d7f67;
        }

        h1 {
            margin: 0 0 10px;
            font-size: 28px;
            line-height: 1.15;
        }

        p {
            margin: 0 0 16px;
            color: #58746d;
            line-height: 1.65;
            font-size: 14px;
        }

        .notice {
            background: #f4fffa;
            border: 1px solid rgba(13,127,103,.12);
            border-radius: 18px;
            padding: 14px;
            margin: 18px 0;
            color: #31544c;
            font-size: 13px;
            line-height: 1.6;
        }

        .actions {
            display: grid;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            height: 50px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            text-decoration: none;
            font-weight: 800;
        }

        .primary {
            color: #fff;
            background: linear-gradient(135deg, #031816, #0d5c46, #00c97a);
        }

        .ghost {
            color: #0d5c46;
            background: #f4fffa;
            border: 1px solid rgba(13,127,103,.14);
        }

        .small {
            margin-top: 18px;
            font-size: 12px;
            color: #78918a;
        }
    </style>
</head>
<body>
    <main class="card">
        <div class="logo">R</div>

        <h1>Undangan resmi Rubik</h1>

        <p>
            Kamu menerima undangan untuk membuat akun Rubik. Pastikan kamu hanya melanjutkan
            jika link ini berasal dari orang atau admin yang kamu kenal.
        </p>

        <div class="notice">
            Rubik adalah platform membership digital untuk akses dashboard akun, riwayat transaksi,
            saldo akun, dan layanan pengguna. Jangan pernah membagikan password kepada siapa pun.
        </div>

        <div class="actions">
            <a class="btn primary" href="{{ route('register.form', ['ref' => request('ref')]) }}">
                Lanjut Daftar
            </a>

            <a class="btn ghost" href="{{ route('home') }}">
                Lihat Informasi Platform
            </a>
        </div>

        <div class="small">
            Dengan melanjutkan, kamu akan diarahkan ke halaman pendaftaran akun Rubik.
        </div>
    </main>
</body>
</html>