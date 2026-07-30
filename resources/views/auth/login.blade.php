<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Masuk Akun | Capital Wave</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --blue:#0A57A3; --blue-lite:#2f7fd4; --blue-soft:#eef4fb;
      --navy:#0b2740; --navy-2:#0d3357; --navy-3:#07182a;
      --gold:#c99433; --gold-lite:#e8c874; --gold-deep:#a9772a; --gold-soft:#faf3e2;
      --gold-metal:linear-gradient(135deg,#a9772a 0%,#e8c874 46%,#c99433 100%);
      --green:#1fb97a; --green-soft:#e6f5ee; --red:#dc5757; --red-soft:#fdeaea;
      --card:#ffffff; --tint:#f5f8fc; --line:#e9edf4; --line-2:#dfe5ee;
      --ink:#152a3f; --ink-soft:#46586c; --muted:#8493a6; --muted-2:#aab6c4;
      --sh:0 2px 6px rgba(11,39,64,.04), 0 12px 30px rgba(11,39,64,.07);
      --sh-lg:0 -10px 40px rgba(11,39,64,.10);
    }
    *{ box-sizing:border-box; }
    html,body{ min-height:100%; }
    body{
      margin:0; color:var(--ink);
      font-family:'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
      background:var(--navy-3); overflow-x:hidden; -webkit-font-smoothing:antialiased; letter-spacing:-.01em;
    }
    a{ color:inherit; text-decoration:none; } button,input{ font-family:inherit; }
    h1,h2,h3,p{ margin:0; }
    .lg-wrap{ width:100%; min-height:100vh; display:flex; justify-content:center; }
    .lg-phone{ width:100%; max-width:428px; min-height:100vh; position:relative; display:flex; flex-direction:column; }

    /* ===== HERO ===== */
    .lg-hero{ position:relative; overflow:hidden; padding:0 0 70px; color:#fff;
      background:
        radial-gradient(500px 300px at 80% 6%, rgba(232,200,116,.16), transparent 60%),
        radial-gradient(500px 320px at 0% 30%, rgba(47,127,212,.24), transparent 58%),
        linear-gradient(160deg,#0f3255 0%,#0b2740 55%,#07182a 100%); }
    .lg-hero::before{ content:""; position:absolute; inset:0; pointer-events:none; opacity:.5;
      background-image:linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
      background-size:30px 30px; -webkit-mask-image:radial-gradient(120% 90% at 80% 0%, #000, transparent 72%); mask-image:radial-gradient(120% 90% at 80% 0%, #000, transparent 72%); }

    /* TICKER */
    .lg-ticker{ position:relative; z-index:2; height:38px; display:flex; align-items:center; overflow:hidden; border-bottom:1px solid rgba(255,255,255,.08); background:rgba(0,0,0,.15); }
    .lg-ticker-track{ display:inline-flex; align-items:center; gap:22px; white-space:nowrap; padding-left:16px; animation:lgTicker 22s linear infinite; }
    .lg-tk{ display:inline-flex; align-items:center; gap:6px; font-size:11px; font-weight:700; color:rgba(255,255,255,.72); }
    .lg-tk b{ color:#fff; }
    .lg-tk .up{ color:#4fe0a0; } .lg-tk .down{ color:#ff8b8b; }
    .lg-tk svg{ width:11px; height:11px; }
    @keyframes lgTicker{ from{ transform:translateX(0); } to{ transform:translateX(-50%); } }

    .lg-hero-body{ position:relative; z-index:2; padding:20px 20px 0; }
    .lg-hero-top{ display:flex; align-items:center; justify-content:space-between; gap:12px; }
    .lg-brand{ display:flex; align-items:center; gap:11px; }
    .lg-mark{ width:44px; height:44px; border-radius:50%; position:relative; display:grid; place-items:center; background:linear-gradient(160deg,#ffffff 0%,#eef4fb 100%); box-shadow:0 8px 20px rgba(0,0,0,.3), inset 0 1px 0 rgba(255,255,255,.14); }
    .lg-mark::after{ content:""; position:absolute; inset:0; border-radius:50%; padding:1.4px; background:var(--gold-metal); -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; }
    .lg-mark svg{ width:26px; height:26px; position:relative; z-index:1; }
    .lg-brand-tx span{ display:block; font-size:8.5px; font-weight:600; letter-spacing:.24em; text-transform:uppercase; color:rgba(255,255,255,.5); }
    .lg-brand-tx b{ display:block; margin-top:4px; font-size:16px; font-weight:800; letter-spacing:.01em; }
    .lg-brand-tx b i{ font-style:normal; background:var(--gold-metal); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }
    .lg-secure{ display:inline-flex; align-items:center; gap:6px; padding:7px 12px; border-radius:999px; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.16); font-size:10px; font-weight:700; }
    .lg-secure::before{ content:""; width:6px; height:6px; border-radius:999px; background:var(--green); box-shadow:0 0 8px var(--green); }

    .lg-hero-main{ margin-top:22px; display:flex; align-items:center; gap:12px; }
    .lg-hero-copy{ flex:1; min-width:0; }
    .lg-hero-copy h1{ font-size:26px; font-weight:800; letter-spacing:-.035em; line-height:1.12; }
    .lg-hero-copy h1 i{ font-style:normal; background:var(--gold-metal); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }
    .lg-hero-copy p{ margin-top:8px; font-size:11.5px; font-weight:500; color:rgba(255,255,255,.58); line-height:1.5; max-width:240px; }
    .lg-mascot{ width:78px; height:78px; flex:0 0 auto; animation:lgFloat 4s ease-in-out infinite; }

    .lg-chips{ margin-top:18px; display:grid; grid-template-columns:repeat(3,1fr); gap:9px; }
    .lg-chip{ padding:11px 8px; border-radius:14px; text-align:center; background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.12);
      opacity:0; transform:translateY(10px); animation:lgUp .5s cubic-bezier(.22,.8,.22,1) forwards; }
    .lg-chip:nth-child(1){ animation-delay:.15s; } .lg-chip:nth-child(2){ animation-delay:.25s; } .lg-chip:nth-child(3){ animation-delay:.35s; }
    .lg-chip svg{ width:18px; height:18px; color:var(--gold-lite); }
    .lg-chip b{ display:block; margin-top:6px; font-size:10.5px; font-weight:700; }
    .lg-chip span{ display:block; margin-top:2px; font-size:8.5px; font-weight:500; color:rgba(255,255,255,.5); }

    /* ===== SHEET ===== */
    .lg-sheet{ position:relative; z-index:3; margin-top:-46px; background:var(--card); border-radius:30px 30px 0 0; box-shadow:var(--sh-lg);
      padding:8px 20px 30px; flex:1; animation:lgSheet .5s cubic-bezier(.22,.8,.22,1) both; }
    .lg-grip{ width:44px; height:5px; border-radius:999px; background:var(--line-2); margin:8px auto 16px; }

    .lg-tabs{ display:flex; gap:6px; padding:5px; border-radius:15px; background:var(--tint); border:1px solid var(--line); margin-bottom:20px; }
    .lg-tab{ flex:1; height:42px; border:0; cursor:pointer; border-radius:11px; background:transparent; color:var(--ink-soft); font-size:13px; font-weight:700; display:flex; align-items:center; justify-content:center; gap:7px; transition:.16s ease; }
    .lg-tab svg{ width:16px; height:16px; }
    .lg-tab.active{ color:#fff; background:linear-gradient(135deg,#123457,#0b2740); box-shadow:0 8px 16px rgba(11,39,64,.2); }

    .lg-title{ font-size:20px; font-weight:800; color:var(--navy); letter-spacing:-.03em; }
    .lg-subtitle{ margin-top:6px; font-size:12px; font-weight:500; color:var(--muted); }

    .lg-errors{ margin:14px 0 0; padding:12px 14px; border-radius:14px; background:var(--red-soft); border:1px solid #f7d4d4; color:#a33; font-size:12px; font-weight:600; }

    .lg-field{ margin-top:16px; }
    .lg-label{ display:flex; align-items:center; gap:6px; margin-bottom:8px; font-size:11.5px; font-weight:700; color:var(--ink-soft); }
    .lg-label svg{ width:14px; height:14px; color:var(--gold-deep); }
    .lg-input-box{ display:flex; align-items:center; gap:0; border-radius:14px; border:1.5px solid var(--line-2); background:var(--tint); overflow:hidden; transition:.15s ease; }
    .lg-input-box:focus-within{ border-color:var(--blue); box-shadow:0 0 0 3px rgba(10,87,163,.1); background:var(--card); }
    .lg-prefix{ padding:0 12px; height:52px; display:flex; align-items:center; font-size:14px; font-weight:700; color:var(--navy); border-right:1px solid var(--line-2); background:rgba(11,39,64,.03); }
    .lg-input{ flex:1; min-width:0; height:52px; padding:0 14px; border:0; outline:none; background:transparent; color:var(--navy); font-size:14px; font-weight:600; }
    .lg-input::placeholder{ color:var(--muted-2); font-weight:500; }
    .lg-eye{ width:44px; height:52px; flex:0 0 auto; border:0; background:transparent; color:var(--muted); cursor:pointer; display:grid; place-items:center; }
    .lg-eye svg{ width:20px; height:20px; }

    .lg-row{ margin-top:14px; display:flex; align-items:center; justify-content:space-between; gap:10px; }
    .lg-check{ display:flex; align-items:center; gap:8px; cursor:pointer; }
    .lg-check input{ width:18px; height:18px; accent-color:var(--blue); }
    .lg-check span{ font-size:12px; font-weight:600; color:var(--ink-soft); }
    .lg-forgot{ font-size:12px; font-weight:700; color:var(--blue); background:none; border:0; cursor:pointer; }

    .lg-submit{ position:relative; overflow:hidden; margin-top:20px; width:100%; min-height:54px; border:0; border-radius:16px; cursor:pointer; color:#fff;
      background:linear-gradient(135deg,#123457,#0b2740); box-shadow:0 12px 26px rgba(11,39,64,.3), inset 0 1px 0 rgba(255,255,255,.08);
      display:flex; align-items:center; justify-content:center; gap:9px; font-size:15px; font-weight:700; letter-spacing:-.01em; transition:.16s ease; }
    .lg-submit::after{ content:""; position:absolute; top:0; left:-70%; width:45%; height:100%; background:linear-gradient(100deg, transparent, rgba(232,200,116,.4), transparent); transform:skewX(-18deg); animation:lgSheen 3.5s ease-in-out infinite; }
    .lg-submit:hover{ transform:translateY(-1px); }
    .lg-submit svg{ width:18px; height:18px; }

    .lg-alt{ margin-top:18px; text-align:center; font-size:12.5px; font-weight:500; color:var(--muted); }
    .lg-alt a{ font-weight:800; background:var(--gold-metal); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }

    .lg-trust{ margin-top:22px; padding-top:18px; border-top:1px solid var(--line); display:flex; align-items:center; justify-content:center; gap:16px; }
    .lg-trust img{ height:30px; width:auto; object-fit:contain; opacity:.85; }
    .lg-trust span{ font-size:10px; font-weight:600; color:var(--muted); }
    .lg-copyright{ margin-top:16px; text-align:center; font-size:9.5px; font-weight:500; color:var(--muted-2); }

    @keyframes lgFloat{ 0%,100%{ transform:translateY(0) rotate(0); } 50%{ transform:translateY(-8px) rotate(3deg); } }
    @keyframes lgUp{ to{ opacity:1; transform:translateY(0); } }
    @keyframes lgSheet{ from{ transform:translateY(40px); opacity:0; } to{ transform:translateY(0); opacity:1; } }
    @keyframes lgSheen{ 0%,55%{ left:-70%; } 100%{ left:130%; } }
    @media (prefers-reduced-motion:reduce){ *,*::before,*::after{ animation:none !important; transition:none !important; } .lg-chip{ opacity:1; transform:none; } }
  </style>
</head>

<body>
  <div class="lg-wrap">
    <div class="lg-phone">

      <section class="lg-hero">
        <div class="lg-ticker" aria-hidden="true">
          <div class="lg-ticker-track">
            @php $tk = [['BBRI','+0,42',1],['TLKM','+0,86',1],['ASII','+2,13',1],['BBCA','-0,31',0],['BMRI','+1,04',1],['GOTO','-0,90',0],['UNVR','+0,58',1],['ANTM','+1,77',1]]; @endphp
            @for($r=0;$r<2;$r++)
              @foreach($tk as $t)
                <span class="lg-tk"><b>{{ $t[0] }}</b><span class="{{ $t[2] ? 'up' : 'down' }}">
                  <svg viewBox="0 0 24 24" fill="none">@if($t[2])<path d="M6 15l6-6 6 6" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>@else<path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>@endif</svg>
                  {{ $t[1] }}%</span></span>
              @endforeach
            @endfor
          </div>
        </div>

        <div class="lg-hero-body">
          <div class="lg-hero-top">
            <div class="lg-brand">
              <span class="lg-mark">
                <img src="{{ asset('logo.png') }}" alt="Capital Wave" style="width:82%;height:82%;object-fit:contain;position:relative;z-index:1;">
              </span>
              <div class="lg-brand-tx"><span>Official Portal</span><b>Capital Wave</b></div>
            </div>
            <span class="lg-secure">SECURE</span>
          </div>

          <div class="lg-chips">
            <div class="lg-chip"><svg viewBox="0 0 24 24" fill="none"><path d="M4 15l5-5 4 4 7-8" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg><b>Pertumbuhan</b><span>Real-time</span></div>
            <div class="lg-chip"><svg viewBox="0 0 24 24" fill="none"><path d="M12 3 20 7v5c0 4.4-3.2 7.2-8 8.5C7.2 19.2 4 16.4 4 12V7l8-4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg><b>Akun Aman</b><span>Terenkripsi</span></div>
            <div class="lg-chip"><svg viewBox="0 0 24 24" fill="none"><path d="M12 3 20 7.4v9.2L12 21l-8-4.4V7.4L12 3Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg><b>Aset Digital</b><span>Terpantau</span></div>
          </div>
        </div>
      </section>

      <section class="lg-sheet">
        <div class="lg-grip"></div>

        <div class="lg-tabs">
          <button type="button" class="lg-tab active">
            <svg viewBox="0 0 24 24" fill="none"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10 17l5-5-5-5M15 12H3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Masuk
          </button>
          <a href="{{ route('register.form') }}" class="lg-tab">
            <svg viewBox="0 0 24 24" fill="none"><circle cx="9" cy="8" r="3.4" stroke="currentColor" stroke-width="2"/><path d="M3.5 20a5.5 5.5 0 0 1 11 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M17 8h4M19 6v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Daftar
          </a>
        </div>

        <h2 class="lg-title">Masuk ke Akun</h2>
        <p class="lg-subtitle">Gunakan nomor WhatsApp terdaftar untuk mengelola portofolio kamu.</p>

        @if($errors->any())
          <div class="lg-errors">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
          @csrf

          <div class="lg-field">
            <label class="lg-label" for="phone"><svg viewBox="0 0 24 24" fill="none"><path d="M5 4h4l2 5-3 2a12 12 0 0 0 5 5l2-3 5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg> Nomor WhatsApp</label>
            <div class="lg-input-box">
              <span class="lg-prefix">+62</span>
              <input class="lg-input" type="tel" inputmode="numeric" name="phone" id="phone" placeholder="08123456789" value="{{ old('phone') }}" autocomplete="username" required>
            </div>
          </div>

          <div class="lg-field">
            <label class="lg-label" for="password"><svg viewBox="0 0 24 24" fill="none"><rect x="4" y="10" width="16" height="11" rx="2.5" stroke="currentColor" stroke-width="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3" stroke="currentColor" stroke-width="2"/></svg> Kata Sandi</label>
            <div class="lg-input-box">
              <input class="lg-input" type="password" name="password" id="password" placeholder="Masukkan kata sandi" autocomplete="current-password" required>
              <button type="button" class="lg-eye" id="togglePw" aria-label="Lihat sandi">
                <svg viewBox="0 0 24 24" fill="none"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg>
              </button>
            </div>
          </div>

          <div class="lg-row">
            <label class="lg-check"><input type="checkbox" name="remember"><span>Ingat akun</span></label>
            <a href="https://t.me/goveloracs" target="_blank" rel="noopener" class="lg-forgot">Lupa sandi?</a>
          </div>

          <button type="submit" class="lg-submit">
            <svg viewBox="0 0 24 24" fill="none"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10 17l5-5-5-5M15 12H3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Masuk Sekarang
          </button>
        </form>

        <p class="lg-alt">Belum punya akun? <a href="{{ route('register.form') }}">Daftar sekarang</a></p>

        <div class="lg-trust">
          <span>Berlisensi &amp; diawasi</span>
          <img src="{{ asset('assets/logos/ojk.png') }}" alt="OJK" onerror="this.style.display='none'">
          <img src="{{ asset('assets/logos/bappebti.png') }}" alt="Bappebti" onerror="this.style.display='none'">
        </div>
        <p class="lg-copyright">© {{ date('Y') }} Capital Wave. Tumbuh bersama, melalui akses resmi.</p>
      </section>
    </div>
  </div>

  <script>
    (function(){
      const pw = document.getElementById('password');
      const btn = document.getElementById('togglePw');
      if(pw && btn){
        btn.addEventListener('click', function(){
          const show = pw.type === 'password';
          pw.type = show ? 'text' : 'password';
          btn.innerHTML = show
            ? '<svg viewBox="0 0 24 24" fill="none"><path d="M3 3l18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10.6 6.1A9.7 9.7 0 0 1 12 6c6 0 10 6 10 6a17 17 0 0 1-3 3.3M6.4 6.5A17 17 0 0 0 2 12s4 6 10 6a9.6 9.6 0 0 0 4-.9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>'
            : '<svg viewBox="0 0 24 24" fill="none"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg>';
        });
      }
    })();
  </script>
</body>
</html>
