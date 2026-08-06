<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Verifikasi Undangan | Capital Wave</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <meta name="robots" content="noindex, nofollow">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

  <style>
    :root{
      --blue:#0A57A3; --blue-lite:#2f7fd4; --navy:#0b2740; --navy-3:#07182a;
      --gold:#c99433; --gold-lite:#e8c874; --gold-deep:#a9772a;
      --gold-metal:linear-gradient(135deg,#a9772a 0%,#e8c874 46%,#c99433 100%);
      --green:#1fb97a; --red:#dc5757; --red-soft:#fdeaea;
      --card:#ffffff; --tint:#f5f8fc; --line:#e9edf4; --line-2:#dfe5ee;
      --ink:#152a3f; --ink-soft:#46586c; --muted:#8493a6;
    }
    *{ box-sizing:border-box; }
    html,body{ min-height:100%; }
    body{ margin:0; color:#fff;
      font-family:'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
      background:
        radial-gradient(600px 360px at 80% 0%, rgba(232,200,116,.14), transparent 60%),
        radial-gradient(600px 380px at 0% 30%, rgba(47,127,212,.22), transparent 58%),
        linear-gradient(160deg,#0f3255 0%,#0b2740 55%,#07182a 100%);
      min-height:100vh; display:flex; align-items:center; justify-content:center; padding:20px;
      -webkit-font-smoothing:antialiased; letter-spacing:-.01em; }
    .rg-card{ width:100%; max-width:400px; text-align:center; animation:rgUp .5s cubic-bezier(.22,.8,.22,1) both; }
    .rg-mark{ width:58px; height:58px; margin:0 auto; border-radius:50%; position:relative; display:grid; place-items:center;
      background:linear-gradient(160deg,#ffffff,#eef4fb); box-shadow:0 10px 26px rgba(0,0,0,.3); }
    .rg-mark::after{ content:""; position:absolute; inset:0; border-radius:50%; padding:1.6px; background:var(--gold-metal);
      -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; }
    .rg-mark img{ width:82%; height:82%; object-fit:contain; position:relative; z-index:1; }
    .rg-eyebrow{ margin-top:18px; display:inline-flex; align-items:center; gap:7px; color:rgba(255,255,255,.6); font-size:9.5px; font-weight:600; letter-spacing:.2em; text-transform:uppercase; }
    .rg-eyebrow::before{ content:""; width:6px; height:6px; border-radius:999px; background:var(--gold-lite); box-shadow:0 0 8px var(--gold-lite); }
    .rg-card h1{ margin-top:12px; font-size:23px; font-weight:800; letter-spacing:-.035em; }
    .rg-card p{ margin-top:9px; font-size:12.5px; font-weight:500; color:rgba(255,255,255,.6); line-height:1.55; }
    .rg-code{ margin-top:14px; display:inline-flex; align-items:center; gap:8px; padding:8px 14px; border-radius:12px; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.16); }
    .rg-code span{ font-size:9.5px; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:rgba(255,255,255,.5); }
    .rg-code b{ font-size:14px; font-weight:800; letter-spacing:.04em; color:var(--gold-lite); }
    .rg-box{ margin-top:22px; background:var(--card); border-radius:22px; padding:22px; box-shadow:0 24px 60px rgba(0,0,0,.35); }
    .rg-box-title{ display:flex; align-items:center; justify-content:center; gap:8px; color:var(--navy); font-size:13.5px; font-weight:700; letter-spacing:-.02em; }
    .rg-box-title svg{ width:17px; height:17px; color:var(--gold-deep); }
    .rg-box-sub{ margin-top:6px; font-size:11px; font-weight:500; color:var(--muted); }
    .rg-widget{ margin-top:16px; display:flex; justify-content:center; min-height:70px; }
    .rg-err{ margin-top:12px; padding:10px 12px; border-radius:12px; background:var(--red-soft); border:1px solid #f7d4d4; color:#a33; font-size:12px; font-weight:600; }
    .rg-btn{ margin-top:16px; width:100%; min-height:50px; border:0; border-radius:14px; cursor:pointer; color:#fff;
      background:linear-gradient(135deg,#123457,#0b2740); box-shadow:0 10px 22px rgba(11,39,64,.3); font-size:14px; font-weight:700; transition:.16s ease; }
    .rg-btn:disabled{ opacity:.45; cursor:not-allowed; }
    .rg-btn:not(:disabled):hover{ transform:translateY(-1px); }
    .rg-loading{ display:inline-flex; align-items:center; gap:8px; margin-top:14px; font-size:11px; font-weight:600; color:var(--muted); }
    .rg-spin{ width:15px; height:15px; border:2px solid var(--line-2); border-top-color:var(--blue); border-radius:50%; animation:rgSpin .7s linear infinite; }
    .rg-foot{ margin-top:20px; font-size:10px; font-weight:500; color:rgba(255,255,255,.4); }
    @keyframes rgUp{ from{ opacity:0; transform:translateY(16px); } to{ opacity:1; transform:translateY(0); } }
    @keyframes rgSpin{ to{ transform:rotate(360deg); } }
  </style>
</head>

<body>
  <div class="rg-card">
    <span class="rg-mark"><img src="{{ asset('logo.png') }}" alt="Capital Wave"></span>
    <span class="rg-eyebrow">Undangan Capital Wave</span>
    <h1>Verifikasi Keamanan</h1>
    <p>Sebentar ya — verifikasi cepat untuk memastikan kamu bukan robot, lalu lanjut ke pendaftaran.</p>

    <div class="rg-code">
      <span>Kode Undangan</span>
      <b>{{ $code }}</b>
    </div>

    <div class="rg-box">
      <div class="rg-box-title">
        <svg viewBox="0 0 24 24" fill="none"><path d="M12 3 20 7v5c0 4.4-3.2 7.2-8 8.5C7.2 19.2 4 16.4 4 12V7l8-4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="m8.5 12 2.2 2.2 4.8-4.8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Verifikasi Manusia
      </div>
      <div class="rg-box-sub">Centang kotak di bawah untuk melanjutkan.</div>

      @if(session('error'))
        <div class="rg-err">{{ session('error') }}</div>
      @endif

      <form method="POST" action="{{ route('referral.verify') }}" id="rgForm">
        @csrf
        <input type="hidden" name="code" value="{{ $code }}">

        <div class="rg-widget">
          <div class="cf-turnstile"
               data-sitekey="{{ $siteKey }}"
               data-callback="rgOnSuccess"
               data-error-callback="rgOnError"
               data-theme="light"></div>
        </div>

        <button type="submit" class="rg-btn" id="rgBtn" disabled>Lanjutkan ke Pendaftaran</button>
        <div class="rg-loading" id="rgLoading" style="display:none"><span class="rg-spin"></span> Memverifikasi...</div>
      </form>
    </div>

    <p class="rg-foot">Dilindungi Cloudflare · © {{ date('Y') }} Capital Wave</p>
  </div>

  <script>
    function rgOnSuccess(token){
      var btn = document.getElementById('rgBtn');
      if(btn) btn.disabled = false;
      // auto-lanjut biar mulus
      document.getElementById('rgLoading').style.display = 'inline-flex';
      setTimeout(function(){ document.getElementById('rgForm').submit(); }, 300);
    }
    function rgOnError(){
      var btn = document.getElementById('rgBtn');
      if(btn) btn.disabled = true;
    }
  </script>
</body>
</html>
