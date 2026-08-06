<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>404 — Halaman Tidak Ditemukan | Capital Wave</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="robots" content="noindex, nofollow">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root{
      --blue:#0A57A3; --navy:#0b2740; --gold-lite:#e8c874; --gold-deep:#a9772a;
      --gold-metal:linear-gradient(135deg,#a9772a 0%,#e8c874 46%,#c99433 100%);
    }
    *{box-sizing:border-box}
    html,body{min-height:100%}
    body{ margin:0; color:#fff;
      font-family:'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
      background:
        radial-gradient(600px 360px at 80% 0%, rgba(232,200,116,.14), transparent 60%),
        radial-gradient(600px 380px at 0% 30%, rgba(47,127,212,.22), transparent 58%),
        linear-gradient(160deg,#0f3255 0%,#0b2740 55%,#07182a 100%);
      min-height:100vh; display:flex; align-items:center; justify-content:center; padding:24px;
      -webkit-font-smoothing:antialiased; letter-spacing:-.01em; text-align:center; }
    .e-card{ width:100%; max-width:420px; animation:eUp .5s cubic-bezier(.22,.8,.22,1) both; }
    .e-mark{ width:64px; height:64px; margin:0 auto; border-radius:50%; position:relative; display:grid; place-items:center;
      background:linear-gradient(160deg,#ffffff,#eef4fb); box-shadow:0 10px 26px rgba(0,0,0,.3); }
    .e-mark::after{ content:""; position:absolute; inset:0; border-radius:50%; padding:1.6px; background:var(--gold-metal);
      -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; }
    .e-mark img{ width:80%; height:80%; object-fit:contain; position:relative; z-index:1; }
    .e-code{ margin-top:22px; font-size:72px; font-weight:800; line-height:1; letter-spacing:-.05em;
      background:var(--gold-metal); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }
    .e-card h1{ margin-top:10px; font-size:22px; font-weight:800; letter-spacing:-.035em; }
    .e-card p{ margin-top:10px; font-size:13px; font-weight:500; color:rgba(255,255,255,.62); line-height:1.6; }
    .e-btn{ margin-top:22px; display:inline-flex; align-items:center; justify-content:center; gap:8px;
      min-height:50px; padding:0 26px; border-radius:14px; color:#fff; text-decoration:none;
      background:linear-gradient(135deg,#123457,#0b2740); box-shadow:0 12px 26px rgba(0,0,0,.3);
      font-size:14px; font-weight:700; transition:.16s ease; }
    .e-btn:hover{ transform:translateY(-1px); filter:brightness(1.06); }
    .e-btn svg{ width:18px; height:18px; }
    .e-foot{ margin-top:20px; font-size:10.5px; font-weight:500; color:rgba(255,255,255,.4); }
    @keyframes eUp{ from{ opacity:0; transform:translateY(16px); } to{ opacity:1; transform:translateY(0); } }
  </style>
</head>
<body>
  <div class="e-card">
    <span class="e-mark"><img src="{{ asset('logo.png') }}" alt="Capital Wave"></span>
    <div class="e-code">404</div>
    <h1>Halaman tidak ditemukan</h1>
    <p>Maaf, halaman yang kamu tuju tidak tersedia atau sudah dipindahkan. Kembali ke dashboard untuk melanjutkan.</p>
    <a href="{{ url('/dashboard') }}" class="e-btn">
      <svg viewBox="0 0 24 24" fill="none"><path d="M3 11.5 12 4l9 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 10v10h14V10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      Kembali ke Dashboard
    </a>
    <p class="e-foot">© {{ date('Y') }} Capital Wave</p>
  </div>
</body>
</html>
