@php
    $lockedReferralCode = session('referral_code');
    $referralInputValue = old('referral_code', $lockedReferralCode);
    $isReferralLocked = !empty($lockedReferralCode);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Daftar Akun | Capital Wave</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <meta name="robots" content="noindex, nofollow, noarchive">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

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
    body{ margin:0; color:var(--ink); font-family:'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; background:var(--navy-3); overflow-x:hidden; -webkit-font-smoothing:antialiased; letter-spacing:-.01em; }
    a{ color:inherit; text-decoration:none; } button,input{ font-family:inherit; }
    h1,h2,h3,p{ margin:0; }
    .vl-wrap{ width:100%; min-height:100vh; display:flex; justify-content:center; }
    .vl-phone{ width:100%; max-width:428px; min-height:100vh; position:relative; display:flex; flex-direction:column; }

    /* HERO */
    .vl-hero{ position:relative; overflow:hidden; padding:20px 20px 66px; color:#fff;
      background:
        radial-gradient(500px 300px at 82% 4%, rgba(232,200,116,.16), transparent 60%),
        radial-gradient(500px 320px at 0% 28%, rgba(47,127,212,.24), transparent 58%),
        linear-gradient(160deg,#0f3255 0%,#0b2740 55%,#07182a 100%); }
    .vl-hero::before{ content:""; position:absolute; inset:0; pointer-events:none; opacity:.5;
      background-image:linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
      background-size:30px 30px; -webkit-mask-image:radial-gradient(120% 90% at 80% 0%, #000, transparent 72%); mask-image:radial-gradient(120% 90% at 80% 0%, #000, transparent 72%); }
    .vl-hero > *{ position:relative; z-index:1; }
    .vl-hero-top{ display:flex; align-items:center; justify-content:space-between; gap:12px; }
    .vl-brand{ display:flex; align-items:center; gap:11px; }
    .vl-mark{ width:44px; height:44px; border-radius:50%; position:relative; display:grid; place-items:center; background:linear-gradient(160deg,#ffffff 0%,#eef4fb 100%); box-shadow:0 8px 20px rgba(0,0,0,.3), inset 0 1px 0 rgba(255,255,255,.14); }
    .vl-mark::after{ content:""; position:absolute; inset:0; border-radius:50%; padding:1.4px; background:var(--gold-metal); -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; }
    .vl-mark svg{ width:26px; height:26px; position:relative; z-index:1; }
    .vl-brand-tx span{ display:block; font-size:8.5px; font-weight:600; letter-spacing:.24em; text-transform:uppercase; color:rgba(255,255,255,.5); }
    .vl-brand-tx b{ display:block; margin-top:4px; font-size:16px; font-weight:800; letter-spacing:.01em; }
    .vl-brand-tx b i{ font-style:normal; background:var(--gold-metal); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }
    .vl-secure{ display:inline-flex; align-items:center; gap:6px; padding:7px 12px; border-radius:999px; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.16); font-size:10px; font-weight:700; }
    .vl-secure::before{ content:""; width:6px; height:6px; border-radius:999px; background:var(--green); box-shadow:0 0 8px var(--green); }
    .vl-hero-main{ margin-top:20px; display:flex; align-items:center; gap:12px; }
    .vl-hero-copy{ flex:1; min-width:0; }
    .vl-hero-copy h1{ font-size:24px; font-weight:800; letter-spacing:-.035em; line-height:1.14; }
    .vl-hero-copy h1 i{ font-style:normal; background:var(--gold-metal); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }
    .vl-hero-copy p{ margin-top:8px; font-size:11.5px; font-weight:500; color:rgba(255,255,255,.58); line-height:1.5; max-width:230px; }
    .vl-mascot{ width:72px; height:72px; flex:0 0 auto; animation:vlFloat 4s ease-in-out infinite; }
    .vl-stats{ margin-top:18px; display:grid; grid-template-columns:repeat(3,1fr); gap:9px; }
    .vl-stat{ padding:11px 8px; border-radius:14px; text-align:center; background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.12); opacity:0; transform:translateY(10px); animation:vlUp .5s cubic-bezier(.22,.8,.22,1) forwards; }
    .vl-stat:nth-child(1){ animation-delay:.12s; } .vl-stat:nth-child(2){ animation-delay:.22s; } .vl-stat:nth-child(3){ animation-delay:.32s; }
    .vl-stat-icon{ display:grid; place-items:center; color:var(--gold-lite); }
    .vl-stat-icon svg{ width:18px; height:18px; }
    .vl-stat strong{ display:block; margin-top:6px; font-size:10.5px; font-weight:700; }
    .vl-stat span{ display:block; margin-top:2px; font-size:8.5px; font-weight:500; color:rgba(255,255,255,.5); }

    /* SHEET */
    .vl-sheet{ position:relative; z-index:3; margin-top:-42px; background:var(--card); border-radius:30px 30px 0 0; box-shadow:var(--sh-lg); padding:8px 20px 28px; flex:1; animation:vlSheet .5s cubic-bezier(.22,.8,.22,1) both; }
    .vl-grip{ width:44px; height:5px; border-radius:999px; background:var(--line-2); margin:8px auto 16px; }

    .vl-tabs{ display:flex; gap:6px; padding:5px; border-radius:15px; background:var(--tint); border:1px solid var(--line); margin-bottom:18px; }
    .vl-tab{ flex:1; height:42px; border:0; cursor:pointer; border-radius:11px; background:transparent; color:var(--ink-soft); font-size:13px; font-weight:700; display:flex; align-items:center; justify-content:center; gap:7px; transition:.16s ease; }
    .vl-tab svg{ width:16px; height:16px; }
    .vl-tab.active{ color:#fff; background:linear-gradient(135deg,#123457,#0b2740); box-shadow:0 8px 16px rgba(11,39,64,.2); }

    .vl-panel{ }
    .vl-panel-head{ margin-bottom:4px; }
    .vl-panel-eyebrow{ display:flex; align-items:center; gap:9px; }
    .vl-panel-bar{ width:4px; height:18px; border-radius:999px; background:var(--gold-metal); }
    .vl-panel-label{ font-size:9.5px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--gold-deep); }
    .vl-panel-title{ margin-top:8px; font-size:20px; font-weight:800; color:var(--navy); letter-spacing:-.03em; }
    .vl-panel-sub{ margin-top:6px; font-size:12px; font-weight:500; color:var(--muted); }

    /* skeleton */
    .vl-skeleton-spinner{ margin-top:16px; font-size:12px; font-weight:600; color:var(--muted); }
    .vl-skeleton-bar{ height:48px; border-radius:14px; margin-top:12px; background:linear-gradient(90deg,#eef1f6 25%,#f6f8fb 37%,#eef1f6 63%); background-size:400% 100%; animation:vlShimmer 1.3s ease infinite; }
    .vl-skeleton-bar.tall{ height:120px; }
    @keyframes vlShimmer{ 0%{ background-position:100% 0; } 100%{ background-position:0 0; } }

    /* ERROR */
    .vl-error{ margin-top:14px; padding:12px 14px; border-radius:14px; background:var(--red-soft); border:1px solid #f7d4d4; color:#a33; font-size:12px; font-weight:600; }
    .vl-error ul{ margin:0; padding-left:18px; } .vl-error li{ margin-top:2px; }

    /* FORM */
    .vl-field{ margin-top:16px; }
    .vl-label{ display:flex; align-items:center; gap:6px; margin-bottom:8px; font-size:11.5px; font-weight:700; color:var(--ink-soft); }
    .vl-label svg{ width:14px; height:14px; color:var(--gold-deep); }
    .vl-input-wrap{ position:relative; display:flex; align-items:center; border-radius:14px; border:1.5px solid var(--line-2); background:var(--tint); overflow:hidden; transition:.15s ease; }
    .vl-input-wrap:focus-within{ border-color:var(--blue); box-shadow:0 0 0 3px rgba(10,87,163,.1); background:var(--card); }
    .vl-input-prefix{ position:absolute; left:0; top:0; bottom:0; width:46px; display:grid; place-items:center; color:var(--muted); pointer-events:none; }
    .vl-input-prefix svg{ width:19px; height:19px; }
    .vl-input{ width:100%; height:52px; padding:0 14px; border:0; outline:none; background:transparent; color:var(--navy); font-size:14px; font-weight:600; }
    .vl-input::placeholder{ color:var(--muted-2); font-weight:500; }
    .vl-input-icon{ padding-left:46px; }
    .vl-input-locked{ color:var(--muted); background:rgba(11,39,64,.03); }
    .vl-phone-wrap{ }
    .vl-phone-prefix{ padding:0 12px; height:52px; display:flex; align-items:center; font-size:14px; font-weight:700; color:var(--navy); border-right:1px solid var(--line-2); background:rgba(11,39,64,.03); }
    .vl-toggle-pass{ position:absolute; right:6px; top:50%; transform:translateY(-50%); width:40px; height:40px; border:0; background:transparent; color:var(--muted); cursor:pointer; display:grid; place-items:center; }
    .vl-toggle-pass svg{ width:20px; height:20px; }
    .vl-hint{ margin-top:8px; font-size:10.5px; font-weight:500; color:var(--muted); }

    /* SECURITY / PUZZLE */
    .vl-security{ margin-top:20px; padding:16px; border-radius:18px; background:var(--tint); border:1px solid var(--line); }
    .vl-security-heading{ display:flex; align-items:center; gap:8px; font-size:12.5px; font-weight:700; color:var(--navy); }
    .vl-security-dot{ width:22px; height:22px; border-radius:8px; display:grid; place-items:center; color:#0b2740; background:var(--gold-metal); font-size:12px; }
    .vl-puzzle{ margin-top:14px; border-radius:16px; background:var(--card); border:1px solid var(--line); padding:14px; }
    .vl-puzzle-head{ display:flex; align-items:flex-start; justify-content:space-between; gap:10px; }
    .vl-puzzle-head h3{ font-size:12.5px; font-weight:700; color:var(--navy); letter-spacing:-.01em; }
    .vl-puzzle-head p{ margin-top:3px; font-size:10px; font-weight:500; color:var(--muted); }
    .vl-puzzle-badge{ flex:0 0 auto; padding:5px 10px; border-radius:8px; font-size:9.5px; font-weight:800; letter-spacing:.06em; color:var(--gold-deep); background:var(--gold-soft); }
    .vl-puzzle-badge.is-ok{ color:var(--green); background:var(--green-soft); }
    .vl-puzzle-stage{ position:relative; margin-top:12px; height:72px; border-radius:13px; overflow:hidden;
      background:linear-gradient(135deg,#0f3255,#0b2740); }
    .vl-puzzle-chip{ position:absolute; z-index:1; padding:4px 9px; border-radius:8px; font-size:9.5px; font-weight:700; color:#fff; background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.16); }
    .vl-puzzle-chip-l{ top:14px; left:14px; }
    .vl-puzzle-chip-r{ top:14px; right:14px; }
    .vl-puzzle-piece{ position:absolute; z-index:3; top:50%; left:14px; margin-top:-18px; width:36px; height:36px; border-radius:10px; background:var(--gold-metal); box-shadow:0 6px 14px rgba(201,148,51,.4); }
    .vl-puzzle-slot{ position:absolute; z-index:2; top:50%; right:14px; margin-top:-18px; width:36px; height:36px; border-radius:10px; border:2px dashed rgba(255,255,255,.35); background:rgba(255,255,255,.05); }
    .vl-puzzle-slider{ position:relative; margin-top:14px; height:52px; border-radius:13px; background:var(--tint); border:1px solid var(--line-2); overflow:hidden; }
    .vl-puzzle-slider-text{ position:absolute; inset:0; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:600; color:var(--muted); pointer-events:none; }
    .vl-puzzle-handle{ position:absolute; left:4px; top:4px; width:48px; height:44px; border:0; border-radius:11px; cursor:grab; color:#fff; background:linear-gradient(135deg,#123457,#0b2740); box-shadow:0 6px 14px rgba(11,39,64,.3); font-size:18px; font-weight:800; z-index:2; touch-action:none; }
    .vl-puzzle-handle:active{ cursor:grabbing; }
    .vl-puzzle-note{ margin-top:12px; font-size:10px; font-weight:500; color:var(--muted); }
    .vl-puzzle-reset{ margin-top:8px; border:0; background:transparent; color:var(--blue); font-size:11px; font-weight:700; cursor:pointer; }

    .vl-confirm{ margin-top:14px; display:flex; align-items:flex-start; gap:11px; cursor:pointer; }
    .vl-confirm input[type="checkbox"]{ appearance:none; -webkit-appearance:none; flex:0 0 auto; width:22px; height:22px; border-radius:7px; border:2px solid var(--line-2); background:var(--card); cursor:pointer; position:relative; transition:.15s ease; }
    .vl-confirm input[type="checkbox"]:checked{ border-color:var(--blue); background:var(--blue); }
    .vl-confirm input[type="checkbox"]:checked::before{ content:"✓"; position:absolute; inset:0; display:grid; place-items:center; color:#fff; font-size:13px; font-weight:800; }
    .vl-confirm span{ font-size:11px; font-weight:500; color:var(--muted); line-height:1.5; }
    .vl-confirm span strong{ display:block; font-size:12px; font-weight:700; color:var(--navy); margin-bottom:2px; }

    .vl-btn-submit{ position:relative; overflow:hidden; margin-top:20px; width:100%; min-height:54px; border:0; border-radius:16px; cursor:pointer; color:#fff;
      background:linear-gradient(135deg,#123457,#0b2740); box-shadow:0 12px 26px rgba(11,39,64,.3), inset 0 1px 0 rgba(255,255,255,.08);
      display:flex; align-items:center; justify-content:center; gap:9px; font-size:15px; font-weight:700; letter-spacing:-.01em; transition:.16s ease; }
    .vl-btn-submit svg{ width:18px; height:18px; }
    .vl-btn-submit:not(:disabled)::after{ content:""; position:absolute; top:0; left:-70%; width:45%; height:100%; background:linear-gradient(100deg, transparent, rgba(232,200,116,.4), transparent); transform:skewX(-18deg); animation:vlSheen 3.5s ease-in-out infinite; }
    .vl-btn-submit:disabled{ opacity:.45; cursor:not-allowed; box-shadow:none; }
    .vl-btn-submit:not(:disabled):hover{ transform:translateY(-1px); }

    /* TRUST + FOOTER */
    .vl-trust-tiles{ margin-top:20px; display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }
    .vl-trust-tile{ padding:14px 8px; border-radius:16px; text-align:center; background:var(--card); border:1px solid var(--line); box-shadow:var(--sh); }
    .vl-trust-tile-icon{ display:grid; place-items:center; color:var(--blue); }
    .vl-trust-tile-icon svg{ width:20px; height:20px; }
    .vl-trust-tile strong{ display:block; margin-top:8px; font-size:11px; font-weight:700; color:var(--navy); }
    .vl-trust-tile span{ display:block; margin-top:2px; font-size:9px; font-weight:500; color:var(--muted); }
    .vl-footer{ margin-top:20px; text-align:center; font-size:12.5px; font-weight:500; color:var(--muted); }
    .vl-footer a{ font-weight:800; background:var(--gold-metal); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }
    .vl-copyright{ margin-top:12px; text-align:center; font-size:9.5px; font-weight:500; color:var(--muted-2); }

    @keyframes vlFloat{ 0%,100%{ transform:translateY(0) rotate(0); } 50%{ transform:translateY(-8px) rotate(3deg); } }
    @keyframes vlUp{ to{ opacity:1; transform:translateY(0); } }
    @keyframes vlSheet{ from{ transform:translateY(40px); opacity:0; } to{ transform:translateY(0); opacity:1; } }
    @keyframes vlSheen{ 0%,55%{ left:-70%; } 100%{ left:130%; } }
    @media (prefers-reduced-motion:reduce){ *,*::before,*::after{ animation:none !important; transition:none !important; } .vl-stat{ opacity:1; transform:none; } }
  </style>
</head>
<body>
  <div class="vl-wrap"><div class="vl-phone">

    <section class="vl-hero">
      <div class="vl-hero-top">
        <div class="vl-brand">
          <span class="vl-mark"><img src="{{ asset('logo.png') }}" alt="Capital Wave" style="width:82%;height:82%;object-fit:contain;position:relative;z-index:1;"></span>
          <div class="vl-brand-tx"><span>Official Portal</span><b>Capital Wave</b></div>
        </div>
        <span class="vl-secure">SECURE</span>
      </div>

      <div class="vl-hero-main">
        <div class="vl-hero-copy">
          <h1>Buat akun & mulai <i>berinvestasi</i></h1>
          <p>Daftar cepat, aman, dan terverifikasi. Portofolio pertamamu menunggu.</p>
        </div>
        <svg class="vl-mascot" viewBox="0 0 64 64" fill="none" aria-hidden="true">
          <rect x="29" y="6" width="6" height="7" rx="3" fill="#c99433"/><circle cx="32" cy="5" r="2.6" fill="#e8c874"/>
          <rect x="12" y="14" width="40" height="32" rx="14" fill="#0b2740" stroke="#1a4a75" stroke-width="1.5"/>
          <rect x="17" y="20" width="30" height="18" rx="9" fill="#0A57A3"/>
          <circle cx="26" cy="29" r="3.4" fill="#e8c874"/><circle cx="38" cy="29" r="3.4" fill="#e8c874"/>
          <rect x="27" y="42" width="10" height="12" rx="5" fill="#123a5c"/>
          <rect x="5" y="24" width="7" height="16" rx="3.5" fill="#123a5c"/><rect x="52" y="24" width="7" height="16" rx="3.5" fill="#123a5c"/>
        </svg>
      </div>

      <div class="vl-stats">
        <div class="vl-stat"><div class="vl-stat-icon"><svg viewBox="0 0 24 24" fill="none"><path d="M4 15l5-5 4 4 7-8" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg></div><strong>Pertumbuhan</strong><span>Berkelanjutan</span></div>
        <div class="vl-stat"><div class="vl-stat-icon"><svg viewBox="0 0 24 24" fill="none"><path d="M12 3l7 3v5c0 4.9-3.1 8.6-7 10C8.1 19.6 5 15.9 5 11V6l7-3z" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/></svg></div><strong>Akun Aman</strong><span>Terenkripsi</span></div>
        <div class="vl-stat"><div class="vl-stat-icon"><svg viewBox="0 0 24 24" fill="none"><path d="M12 2.5 20 7v10l-8 4.5L4 17V7l8-4.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg></div><strong>Aset Digital</strong><span>Terpantau</span></div>
      </div>
    </section>

    <div class="vl-sheet">
      <div class="vl-grip"></div>

      <nav class="vl-tabs" aria-label="Navigasi Daftar / Masuk">
        <a href="{{ route('register.form') }}" class="vl-tab active">
          <svg viewBox="0 0 24 24" fill="none"><circle cx="9" cy="8" r="3.4" stroke="currentColor" stroke-width="2"/><path d="M3.5 20a5.5 5.5 0 0 1 11 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M17 8h4M19 6v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          Daftar
        </a>
        <a href="{{ route('login') }}" class="vl-tab">
          <svg viewBox="0 0 24 24" fill="none"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10 17l5-5-5-5M15 12H3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          Masuk
        </a>
      </nav>

      <div class="vl-panel" id="registerPanelContainer">
        <div class="vl-panel-head">
          <div class="vl-panel-eyebrow"><div class="vl-panel-bar"></div><span class="vl-panel-label">Registrasi</span></div>
          <h2 class="vl-panel-title">Pendaftaran Akun</h2>
          <p class="vl-panel-sub">Lengkapi data untuk membuat akun resmi kamu.</p>
        </div>

        <div id="formSkeleton">
          <div class="vl-skeleton-spinner">Mempersiapkan formulir...</div>
          <div class="vl-skeleton-bar"></div>
          <div class="vl-skeleton-bar"></div>
          <div class="vl-skeleton-bar"></div>
          <div class="vl-skeleton-bar tall"></div>
        </div>
      </div>

      <div class="vl-trust-tiles">
        <div class="vl-trust-tile"><div class="vl-trust-tile-icon"><svg viewBox="0 0 24 24" fill="none"><path d="M12 3l7 3v5c0 4.9-3.1 8.6-7 10C8.1 19.6 5 15.9 5 11V6l7-3z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M8.5 11.5l2.2 2.2 4.8-5.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></div><strong>Data Aman</strong><span>Privasi terjaga</span></div>
        <div class="vl-trust-tile"><div class="vl-trust-tile-icon"><svg viewBox="0 0 24 24" fill="none"><path d="M4 17l6-6 4 4 6-8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 7h6v6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></div><strong>Pertumbuhan</strong><span>Real-time</span></div>
        <div class="vl-trust-tile"><div class="vl-trust-tile-icon"><svg viewBox="0 0 24 24" fill="none"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="2"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></div><strong>Komunitas</strong><span>Terpercaya</span></div>
      </div>

      <p class="vl-footer">Sudah punya akun? <a href="{{ route('login') }}">Masuk sekarang</a></p>
      <p class="vl-copyright">&copy; {{ date('Y') }} Capital Wave. Tumbuh bersama, melalui akses resmi.</p>
    </div>

  </div></div>

<script>
  const _bd = {
    lockedReferralCode: @json($lockedReferralCode),
    referralInputValue: @json($referralInputValue),
    isReferralLocked:   @json($isReferralLocked),
    oldName:  @json(old('name')),
    oldPhone: @json(old('phone')),
    errors:   @json($errors->any() ? $errors->all() : [])
  };

  (function(){
    function esc(v){
      if(v == null) return '';
      return String(v)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;').replace(/'/g,'&#39;');
    }

    function buildErrorHtml(){
      if(!_bd.errors || !_bd.errors.length) return '';
      const items = _bd.errors.map(e => `<li>${esc(e)}</li>`).join('');
      return `<div class="vl-error"><ul>${items}</ul></div>`;
    }

    function injectForm(){
      const skeleton = document.getElementById('formSkeleton');
      if(skeleton) skeleton.remove();

      const panel = document.getElementById('registerPanelContainer');
      if(!panel) return;

      const refLocked  = _bd.isReferralLocked;
      const refValue   = esc(_bd.referralInputValue || '');
      const nameValue  = esc(_bd.oldName || '');
      const phoneValue = esc(_bd.oldPhone || '');

      const fragment = document.createRange().createContextualFragment(`
        ${buildErrorHtml()}

        <form method="POST" action="{{ route('register.store') }}" autocomplete="off" novalidate id="registerForm">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">

          <div class="vl-field">
            <label class="vl-label" for="name">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M20 21a8 8 0 0 0-16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="2"/>
              </svg>
              Username
            </label>
            <div class="vl-input-wrap">
              <span class="vl-input-prefix">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M20 21a8 8 0 0 0-16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  <path d="M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="2"/>
                </svg>
              </span>
              <input class="vl-input vl-input-icon" id="name" type="text" name="name"
                value="${nameValue}" placeholder="Masukkan nama panggilan" required>
            </div>
          </div>

          <div class="vl-field">
            <label class="vl-label" for="phone">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.86 19.86 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.91.32 1.8.59 2.65a2 2 0 0 1-.45 2.11L8 9.73a16 16 0 0 0 6.27 6.27l1.25-1.25a2 2 0 0 1 2.11-.45c.85.27 1.74.47 2.65.59A2 2 0 0 1 22 16.92Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              Nomor WhatsApp
            </label>
            <div class="vl-input-wrap vl-phone-wrap">
              <span class="vl-phone-prefix">+62</span>
              <input class="vl-input" id="phone" type="tel" name="phone"
                value="${phoneValue}" placeholder="08123456789"
                inputmode="numeric" pattern="08[0-9]{8,12}" required>
            </div>
          </div>

          <div class="vl-field">
            <label class="vl-label" for="referral_code">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M20 12v10H4V12" stroke="currentColor" stroke-width="2"/>
                <path d="M22 7H2v5h20V7Z" stroke="currentColor" stroke-width="2"/>
                <path d="M12 22V7" stroke="currentColor" stroke-width="2"/>
              </svg>
              Kode Undangan
            </label>
            <div class="vl-input-wrap">
              <span class="vl-input-prefix">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M20 12v10H4V12" stroke="currentColor" stroke-width="2"/>
                  <path d="M22 7H2v5h20V7Z" stroke="currentColor" stroke-width="2"/>
                  <path d="M12 22V7" stroke="currentColor" stroke-width="2"/>
                </svg>
              </span>
              <input class="vl-input vl-input-icon ${refLocked ? 'vl-input-locked' : ''}"
                id="referral_code" type="text" name="referral_code"
                value="${refValue}" placeholder="Masukkan kode undangan"
                autocomplete="off" ${refLocked ? 'readonly' : ''}
                data-locked-val="${refLocked ? refValue : ''}">
            </div>
          </div>

          <div class="vl-field">
            <label class="vl-label" for="password">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="currentColor" stroke-width="2.1" stroke-linecap="round"/>
                <path d="M6 11h12a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              Kata Sandi
            </label>
            <div class="vl-input-wrap">
              <input class="vl-input" id="password" type="password" name="password"
                placeholder="Buat kata sandi" style="padding-right:50px" required>
              <button class="vl-toggle-pass" type="button" id="togglePassBtn" aria-label="Tampilkan kata sandi">
                <svg id="eyeIcon" viewBox="0 0 24 24" fill="none">
                  <path d="M1.5 12s4-7.5 10.5-7.5S22.5 12 22.5 12 18.5 19.5 12 19.5 1.5 12 1.5 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2"/>
                </svg>
              </button>
            </div>
            <p class="vl-hint">Gunakan kombinasi huruf dan angka untuk keamanan optimal.</p>
          </div>

          <input type="text" name="website" tabindex="-1" autocomplete="off" style="display:none">
          <input type="hidden" name="human_interaction" value="1">
          <input type="hidden" name="puzzle_verified" id="puzzleVerified" value="0">

          <div class="vl-security">
            <div class="vl-security-heading">
              <div class="vl-security-dot">✦</div>
              Verifikasi Keamanan
            </div>

            <div class="vl-puzzle" id="puzzleCard">
              <div class="vl-puzzle-head">
                <div>
                  <h3>Geser untuk melengkapi puzzle</h3>
                  <p>Geser ke kanan sampai potongan masuk ke slot.</p>
                </div>
                <div class="vl-puzzle-badge" id="puzzleBadge">PERLU</div>
              </div>

              <div class="vl-puzzle-stage" id="puzzleStage">
                <div class="vl-puzzle-chip vl-puzzle-chip-l">Akun</div>
                <div class="vl-puzzle-chip vl-puzzle-chip-r">Aman</div>
                <div class="vl-puzzle-piece" id="puzzlePiece"></div>
                <div class="vl-puzzle-slot" id="puzzleSlot"></div>
              </div>

              <div class="vl-puzzle-slider" id="puzzleSlider">
                <button type="button" class="vl-puzzle-handle" id="puzzleHandle" aria-label="Geser verifikasi">»</button>
                <div class="vl-puzzle-slider-text" id="puzzleSliderText">Geser untuk menyelesaikan verifikasi</div>
              </div>

              <p class="vl-puzzle-note">✦ Verifikasi cepat untuk menjaga keamanan pendaftaran.</p>
              <button type="button" class="vl-puzzle-reset" id="puzzleResetBtn">↺ Ulangi</button>
            </div>

            <label class="vl-confirm" for="security_confirm">
              <input id="security_confirm" type="checkbox" name="security_confirm" value="1">
              <span>
                <strong>Konfirmasi keamanan akun</strong>
                Saya memahami kata sandi bersifat pribadi dan keamanan akun adalah tanggung jawab saya.
              </span>
            </label>
          </div>

          <button class="vl-btn-submit" type="submit" id="registerSubmit" disabled>
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="2"/>
              <path d="M19 8v6M22 11h-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>Daftar Sekarang</span>
          </button>
        </form>
      `);

      panel.appendChild(fragment);
      initHandlers();
    }

    function initHandlers(){
      const toggleBtn = document.getElementById('togglePassBtn');
      const pwd = document.getElementById('password');
      const eye = document.getElementById('eyeIcon');
      if(toggleBtn && pwd && eye){
        toggleBtn.addEventListener('click', () => {
          const show = pwd.type === 'password';
          pwd.type = show ? 'text' : 'password';
          eye.innerHTML = show
            ? '<path d="M3 3l18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10.58 10.58A2 2 0 0 0 12 14a2 2 0 0 0 1.42-.58" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M9.88 5.09A9.77 9.77 0 0 1 12 4.86C18.5 4.86 22.5 12 22.5 12a17.56 17.56 0 0 1-3.09 4.08" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.61 6.61C3.32 8.78 1.5 12 1.5 12s4 7.14 10.5 7.14a9.9 9.9 0 0 0 4.1-.88" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'
            : '<path d="M1.5 12s4-7.5 10.5-7.5S22.5 12 22.5 12 18.5 19.5 12 19.5 1.5 12 1.5 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2"/>';
        });
      }

      const refInput = document.getElementById('referral_code');
      if(refInput && _bd.isReferralLocked){
        const locked = refInput.dataset.lockedVal || '';
        refInput.value = locked;
        refInput.readOnly = true;
        refInput.addEventListener('input', () => { refInput.value = locked; });
        refInput.addEventListener('paste', e => { e.preventDefault(); refInput.value = locked; });
        refInput.addEventListener('keydown', e => {
          const ok = ['Tab','Shift','ArrowLeft','ArrowRight','ArrowUp','ArrowDown'];
          if(!ok.includes(e.key)) e.preventDefault();
        });
      }

      const phoneEl = document.getElementById('phone');
      if(phoneEl){
        phoneEl.addEventListener('input', function(){
          this.value = this.value.replace(/[^0-9]/g,'').slice(0,14);
        });
      }

      const handle    = document.getElementById('puzzleHandle');
      const slider    = document.getElementById('puzzleSlider');
      const piece     = document.getElementById('puzzlePiece');
      const slot      = document.getElementById('puzzleSlot');
      const badge     = document.getElementById('puzzleBadge');
      const sliderTxt = document.getElementById('puzzleSliderText');
      const verField  = document.getElementById('puzzleVerified');
      const resetBtn  = document.getElementById('puzzleResetBtn');
      const confirmCk = document.getElementById('security_confirm');
      const submitBtn = document.getElementById('registerSubmit');
      const form      = document.getElementById('registerForm');

      if(!handle || !slider || !piece) return;

      let dragging = false, startX = 0, curX = 0, verified = false;

      function maxX(){ return Math.max(0, slider.clientWidth - handle.clientWidth); }

      function setX(x){
        const mx = maxX();
        curX = Math.max(0, Math.min(x, mx));
        handle.style.transform = `translateX(${curX}px)`;
        const pieceTarget = slot ? Math.max(0, slot.offsetLeft - 18 + 4) : 220;
        piece.style.transform = `translateX(${mx > 0 ? (curX / mx) * pieceTarget : 0}px)`;
      }

      function updateBtn(){
        const ok = verified && confirmCk && confirmCk.checked;
        if(submitBtn) submitBtn.disabled = !ok;
      }

      function markVerified(){
        verified = true;
        if(verField) verField.value = '1';
        setX(maxX());
        if(badge){ badge.textContent = 'AMAN'; badge.classList.add('is-ok'); }
        if(sliderTxt) sliderTxt.textContent = '✓ Verifikasi berhasil';
        if(handle){ handle.textContent = '✓'; handle.style.cursor = 'default'; }
        updateBtn();
      }

      function resetPuzzle(){
        verified = false;
        if(verField) verField.value = '0';
        if(badge){ badge.textContent = 'PERLU'; badge.classList.remove('is-ok'); }
        if(sliderTxt) sliderTxt.textContent = 'Geser untuk menyelesaikan verifikasi';
        if(handle){ handle.textContent = '»'; handle.style.cursor = 'grab'; }
        setX(0);
        updateBtn();
      }

      function pX(e){ return e.touches?.[0]?.clientX ?? e.clientX; }

      handle.addEventListener('pointerdown', e => {
        if(verified) return;
        dragging = true; startX = pX(e) - curX;
        if(handle.setPointerCapture) handle.setPointerCapture(e.pointerId);
        e.preventDefault();
      });

      window.addEventListener('pointermove', e => {
        if(!dragging || verified) return;
        setX(pX(e) - startX);
        if(maxX() > 0 && curX >= maxX() * 0.92){ dragging = false; markVerified(); }
        e.preventDefault();
      });

      window.addEventListener('pointerup', () => {
        if(!dragging || verified) return;
        dragging = false;
        if(maxX() > 0 && curX >= maxX() * 0.82){ markVerified(); return; }
        setX(0);
      });

      handle.addEventListener('touchstart', e => {
        if(verified) return;
        dragging = true; startX = pX(e) - curX; e.preventDefault();
      }, { passive:false });

      window.addEventListener('touchmove', e => {
        if(!dragging || verified) return;
        setX(pX(e) - startX);
        if(maxX() > 0 && curX >= maxX() * 0.92){ dragging = false; markVerified(); }
        e.preventDefault();
      }, { passive:false });

      window.addEventListener('touchend', () => {
        if(!dragging || verified) return;
        dragging = false;
        if(maxX() > 0 && curX >= maxX() * 0.82){ markVerified(); return; }
        setX(0);
      });

      if(resetBtn) resetBtn.addEventListener('click', resetPuzzle);
      if(confirmCk) confirmCk.addEventListener('change', updateBtn);
      window.addEventListener('resize', () => { verified ? setX(maxX()) : setX(0); });

      if(form){
        form.addEventListener('submit', e => {
          if(!verified){ e.preventDefault(); alert('Selesaikan verifikasi puzzle terlebih dahulu.'); return; }
          if(!confirmCk?.checked){ e.preventDefault(); alert('Centang konfirmasi keamanan akun terlebih dahulu.'); }
        });
      }

      resetPuzzle();
    }

    setTimeout(injectForm, 2000);
  })();
</script>
</body>
</html>
