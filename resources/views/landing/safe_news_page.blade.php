<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .hero { background: linear-gradient(135deg, #0d6efd, #0a58ca); color: white; padding: 60px 0; }
    </style>
</head>
<body>
    <nav class="navbar navbar-light bg-white shadow-sm">
        <div class="container"><a class="navbar-brand fw-bold" href="#">Portal Edukasi Digital</a></div>
    </nav>

    <header class="hero text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Waspada Penipuan Digital</h1>
            <p class="lead">Mari tingkatkan literasi keamanan siber untuk melindungi data pribadi Anda.</p>
        </div>
    </header>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <article class="p-4 bg-white rounded shadow-sm">
                    <h2 class="mb-4">{{ $title }}</h2>
                    <p class="text-muted">Dipublikasikan pada: {{ $date }}</p>
                    <hr>
                    <p>{{ $content }}</p>
                    <p>Beberapa tips menjaga keamanan akun Anda:</p>
                    <ul>
                        <li>Gunakan otentikasi dua faktor (2FA).</li>
                        <li>Jangan berikan kode OTP kepada siapapun.</li>
                        <li>Ganti kata sandi secara berkala.</li>
                    </ul>
                </article>
            </div>
        </div>
    </main>
    <footer class="text-center py-4 text-muted">© 2026 Portal Edukasi Masyarakat Indonesia</footer>
</body>
</html>