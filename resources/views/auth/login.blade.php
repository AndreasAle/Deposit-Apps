<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Masuk — Velora Finance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <meta name="robots" content="noindex, nofollow, noarchive">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --bg:#fffaf3;
      --paper:#ffffff;
      --text:#3b1116;
      --soft:#5b2a30;
      --muted:#8b6b70;
      --muted2:#b89aa0;
      --border:rgba(75,16,21,.09);
      --border2:rgba(75,16,21,.14);

      --brand:#7d3cff;
      --brand2:#c957ff;
      --violet:#d35cff;
      --gold:#ffb52e;
      --rose:#ef4444;

      --shadow:0 22px 55px rgba(75,16,21,.10);
      --shadow-soft:0 12px 28px rgba(75,16,21,.075);
    }

    *{ box-sizing:border-box; margin:0; padding:0; }
    html,body{ min-height:100%; }

    body{
      font-family:Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      color:var(--text);
      background:
        radial-gradient(780px 420px at 50% -120px, rgba(201,87,255,.10), transparent 64%),
        linear-gradient(180deg, #fffdf9 0%, #fff8ee 45%, #f7eefc 100%);
      overflow-x:hidden;
      -webkit-tap-highlight-color:transparent;
    }

    body::before{
      content:"";
      position:fixed;
      inset:0;
      pointer-events:none;
      background:
        linear-gradient(rgba(75,16,21,.018) 1px, transparent 1px),
        linear-gradient(90deg, rgba(75,16,21,.012) 1px, transparent 1px);
      background-size:34px 34px;
      opacity:.55;
      mask-image:linear-gradient(180deg, rgba(0,0,0,.45), transparent 80%);
      -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.45), transparent 80%);
      z-index:0;
    }

    body::after{
      content:"";
      position:fixed;
      inset:0;
      pointer-events:none;
      background:
        radial-gradient(circle at 6% 18%, rgba(255,181,46,.12), transparent 30%),
        radial-gradient(circle at 92% 28%, rgba(201,87,255,.11), transparent 30%),
        radial-gradient(circle at 50% 100%, rgba(125,60,255,.07), transparent 34%);
      z-index:0;
    }

    a{ color:inherit; text-decoration:none; }
    button,input{ font-family:inherit; }

    /* PAGE */
    .vl-page{
      width:100%;
      min-height:100vh;
      position:relative;
      z-index:1;
      display:flex;
      justify-content:center;
      padding:14px 10px 40px;
    }

    .vl-phone{
      width:100%;
      max-width:430px;
      position:relative;
      padding:8px 4px;
    }

    /* HEADER */
    .vl-topbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:20px;
      padding:2px 2px 0;
    }

    .vl-brand{ display:flex; align-items:center; gap:11px; min-width:0; }

    .vl-logo{
      width:50px;
      height:50px;
      border-radius:18px;
      display:grid;
      place-items:center;
      overflow:hidden;
      background:linear-gradient(135deg, rgba(255,181,46,.12), rgba(201,87,255,.12), rgba(125,60,255,.10)), #fff;
      border:1px solid rgba(75,16,21,.09);
      box-shadow:0 12px 26px rgba(75,16,21,.075), 0 0 0 5px rgba(255,181,46,.08), inset 0 1px 0 rgba(255,255,255,.9);
      flex:0 0 auto;
    }

    .vl-logo img{ width:44px; height:44px; object-fit:contain; display:block; }

    .vl-brand-copy{ min-width:0; }
    .vl-brand-copy span{
      display:block;
      margin-bottom:5px;
      color:#9a6e72;
      font-size:11px;
      line-height:1;
      font-weight:850;
      letter-spacing:.16em;
      text-transform:uppercase;
    }
    .vl-brand-copy h1{
      margin:0;
      font-size:23px;
      line-height:1;
      font-weight:950;
      letter-spacing:-.055em;
      color:#3b1116;
      white-space:nowrap;
    }

    .vl-kicker-pill{
      display:inline-flex;
      align-items:center;
      gap:7px;
      min-height:30px;
      padding:0 13px;
      border-radius:999px;
      background:#fff;
      border:1px solid var(--border);
      box-shadow:var(--shadow-soft);
      color:var(--brand);
      font-size:10px;
      font-weight:850;
      letter-spacing:.08em;
      text-transform:uppercase;
      flex:0 0 auto;
    }
    .vl-kicker-pill::before{
      content:"";
      width:7px;
      height:7px;
      border-radius:999px;
      background:var(--gold);
      box-shadow:0 0 0 3px rgba(255,181,46,.20);
    }

    /* HERO BANNER */
    .vl-hero-banner{
      position:relative;
      overflow:hidden;
      border-radius:30px;
      background:
        radial-gradient(360px 210px at 95% 0%, rgba(255,255,255,.18), transparent 60%),
        linear-gradient(135deg, #ffb52e 0%, #c957ff 46%, #7d3cff 100%);
      border:1px solid rgba(255,255,255,.55);
      box-shadow:0 24px 52px rgba(201,87,255,.24), inset 0 1px 0 rgba(255,255,255,.22);
      padding:22px 20px;
      color:#fff;
      margin-bottom:16px;
      text-align:center;
    }

    .vl-hero-banner::before{
      content:"";
      position:absolute;
      inset:0;
      pointer-events:none;
      border-radius:inherit;
      background:
        linear-gradient(135deg, rgba(255,255,255,.20), transparent 34%),
        radial-gradient(circle at 88% 22%, rgba(255,255,255,.18), transparent 28%);
    }

    .vl-hero-banner > *{ position:relative; z-index:1; }

    .vl-hero-logo-wrap{
      width:80px;
      height:80px;
      margin:0 auto 14px;
      border-radius:26px;
      background:rgba(255,255,255,.96);
      border:1px solid rgba(255,255,255,.92);
      box-shadow:0 0 0 6px rgba(255,255,255,.15), 0 18px 40px rgba(0,0,0,.18), inset 0 1px 0 rgba(255,255,255,.90);
      display:grid;
      place-items:center;
      position:relative;
      animation:vlFloat 4s ease-in-out infinite;
    }

    .vl-hero-logo-wrap img{ width:58px; height:58px; object-fit:contain; display:block; }

    .vl-hero-tagline{
      display:inline-flex;
      align-items:center;
      gap:6px;
      min-height:26px;
      padding:0 12px;
      border-radius:999px;
      background:rgba(255,255,255,.15);
      border:1px solid rgba(255,255,255,.22);
      color:#fff9ef;
      font-size:10px;
      font-weight:850;
      letter-spacing:.06em;
      text-transform:uppercase;
      margin-bottom:10px;
    }

    .vl-hero-title{
      font-size:28px;
      font-weight:950;
      line-height:1.05;
      letter-spacing:-.055em;
      color:#fff;
      text-shadow:0 8px 20px rgba(0,0,0,.18);
      margin-bottom:6px;
    }

    .vl-hero-sub{
      color:rgba(255,255,255,.68);
      font-size:12px;
      font-weight:650;
      line-height:1.5;
    }

    .vl-hero-stats{
      display:grid;
      grid-template-columns:repeat(3,1fr);
      gap:8px;
      margin-top:16px;
    }

    .vl-stat{
      padding:10px 8px;
      border-radius:16px;
      background:rgba(255,255,255,.13);
      border:1px solid rgba(255,255,255,.18);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.10);
      text-align:center;
    }

    .vl-stat-icon{
      width:28px;
      height:28px;
      border-radius:10px;
      background:rgba(255,255,255,.18);
      display:grid;
      place-items:center;
      margin:0 auto 6px;
    }

    .vl-stat-icon svg{ width:14px; height:14px; color:#fff; }
    .vl-stat strong{ display:block; color:#fff; font-size:10px; font-weight:850; line-height:1.2; }
    .vl-stat span{ display:block; margin-top:2px; color:rgba(255,255,255,.60); font-size:9px; font-weight:650; }

    /* TABS */
    .vl-tabs{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:5px;
      padding:5px;
      border-radius:22px;
      background:#fff;
      border:1px solid var(--border);
      box-shadow:var(--shadow-soft);
      margin-bottom:14px;
    }

    .vl-tab{
      min-height:44px;
      border-radius:17px;
      display:flex;
      align-items:center;
      justify-content:center;
      gap:7px;
      font-size:12.5px;
      font-weight:850;
      color:var(--muted);
      transition:.18s ease;
    }

    .vl-tab svg{ width:15px; height:15px; }

    .vl-tab.active{
      color:#fff;
      background:linear-gradient(135deg, var(--gold), var(--violet), var(--brand));
      box-shadow:0 8px 20px rgba(201,87,255,.22);
    }

    /* PANEL */
    .vl-panel{
      background:#fff;
      border-radius:26px;
      padding:20px 18px 18px;
      border:1px solid var(--border);
      box-shadow:var(--shadow-soft);
      margin-bottom:14px;
    }

    .vl-panel-head{ margin-bottom:18px; }

    .vl-panel-eyebrow{
      display:flex;
      align-items:center;
      gap:8px;
      margin-bottom:7px;
    }

    .vl-panel-bar{
      width:24px;
      height:4px;
      border-radius:999px;
      background:linear-gradient(90deg, var(--gold), var(--violet), var(--brand));
    }

    .vl-panel-label{
      font-size:10.5px;
      font-weight:850;
      color:var(--brand);
      letter-spacing:.08em;
      text-transform:uppercase;
    }

    .vl-panel-title{
      font-size:21px;
      font-weight:950;
      letter-spacing:-.04em;
      color:var(--text);
      line-height:1.1;
    }

    .vl-panel-sub{
      margin-top:4px;
      font-size:12px;
      color:var(--muted);
      font-weight:650;
      line-height:1.5;
    }

    /* ERROR */
    .vl-error{
      margin-bottom:14px;
      padding:12px 14px;
      border-radius:16px;
      background:rgba(239,68,68,.06);
      border:1px solid rgba(239,68,68,.18);
      color:#B91C1C;
      font-size:12px;
      font-weight:700;
      line-height:1.5;
      display:flex;
      gap:9px;
      align-items:flex-start;
    }

    .vl-error svg{ width:15px; height:15px; flex-shrink:0; margin-top:1px; }

    /* FIELDS */
    .vl-field{ margin-bottom:12px; }

    .vl-label{
      display:flex;
      align-items:center;
      gap:5px;
      margin-bottom:6px;
      font-size:11.5px;
      font-weight:750;
      color:var(--muted);
    }

    .vl-label svg{ width:13px; height:13px; color:var(--brand); }

    .vl-input-wrap{ position:relative; }

    .vl-input{
      width:100%;
      height:50px;
      border-radius:16px;
      border:1.5px solid var(--border);
      background:#fffaf3;
      outline:none;
      padding:0 14px;
      font-size:13.5px;
      font-weight:500;
      color:var(--text);
      transition:border-color .18s, box-shadow .18s, transform .18s;
    }

    .vl-input:focus{
      border-color:rgba(125,60,255,.36);
      background:#fff;
      box-shadow:0 0 0 4px rgba(125,60,255,.08);
      transform:translateY(-1px);
    }

    .vl-input::placeholder{ color:var(--muted2); font-weight:400; }

    .vl-phone-wrap .vl-input{ padding-left:54px; }

    .vl-phone-prefix{
      position:absolute;
      left:0; top:0; bottom:0;
      width:48px;
      display:flex;
      align-items:center;
      justify-content:center;
      color:var(--muted);
      font-size:13px;
      font-weight:750;
      border-right:1.5px solid var(--border);
      pointer-events:none;
    }

    .vl-toggle-pass{
      position:absolute;
      top:50%; right:8px;
      transform:translateY(-50%);
      width:36px; height:36px;
      border:none;
      border-radius:12px;
      background:transparent;
      cursor:pointer;
      display:grid;
      place-items:center;
      color:var(--muted2);
      transition:color .18s, background .18s;
    }

    .vl-toggle-pass:hover{ color:var(--brand); background:rgba(125,60,255,.08); }
    .vl-toggle-pass svg{ width:17px; height:17px; }

    /* HELPER ROW */
    .vl-helper{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:10px;
      margin:10px 0 16px;
    }

    .vl-remember{
      display:inline-flex;
      align-items:center;
      gap:8px;
      font-size:12px;
      font-weight:650;
      color:var(--muted);
      cursor:pointer;
    }

    .vl-remember input[type="checkbox"]{
      appearance:none;
      -webkit-appearance:none;
      width:17px; height:17px;
      border-radius:6px;
      border:1.5px solid var(--border2);
      background:#fffaf3;
      display:grid;
      place-items:center;
      cursor:pointer;
      transition:border-color .15s, background .15s;
    }

    .vl-remember input[type="checkbox"]:checked{
      border-color:var(--brand);
      background:linear-gradient(135deg, var(--gold), var(--violet), var(--brand));
    }

    .vl-remember input[type="checkbox"]:checked::before{
      content:'';
      width:5px; height:8px;
      border-right:2px solid #fff;
      border-bottom:2px solid #fff;
      transform:rotate(45deg) translate(-1px,-1px);
    }

    .vl-forgot{
      font-size:12px;
      font-weight:850;
      color:var(--brand);
    }

    .vl-forgot:hover{ text-decoration:underline; }

    /* SUBMIT */
    .vl-btn-submit{
      width:100%;
      min-height:52px;
      border:none;
      border-radius:18px;
      cursor:pointer;
      background:linear-gradient(135deg, var(--gold) 0%, var(--violet) 50%, var(--brand) 100%);
      color:#fff;
      font-size:14px;
      font-weight:950;
      letter-spacing:.01em;
      box-shadow:0 14px 30px rgba(201,87,255,.28), inset 0 1px 0 rgba(255,255,255,.18);
      display:flex;
      align-items:center;
      justify-content:center;
      gap:9px;
      position:relative;
      overflow:hidden;
      transition:transform .18s, box-shadow .18s, filter .18s;
    }

    .vl-btn-submit::after{
      content:'';
      position:absolute;
      top:0; left:-100%;
      width:50%; height:100%;
      background:linear-gradient(to right, transparent, rgba(255,255,255,.22), transparent);
      transform:skewX(-18deg);
      animation:vlShine 3s infinite;
    }

    .vl-btn-submit svg{ width:17px; height:17px; position:relative; z-index:1; }
    .vl-btn-submit span{ position:relative; z-index:1; }

    .vl-btn-submit:hover{
      transform:translateY(-2px);
      box-shadow:0 18px 40px rgba(201,87,255,.36), inset 0 1px 0 rgba(255,255,255,.20);
    }

    .vl-btn-submit:active{ transform:translateY(0); filter:brightness(.97); }

    /* TRUST TILES */
    .vl-trust-tiles{
      display:grid;
      grid-template-columns:repeat(3,1fr);
      gap:9px;
      margin-bottom:14px;
    }

    .vl-trust-tile{
      padding:12px 8px;
      border-radius:20px;
      background:#fff;
      border:1px solid var(--border);
      box-shadow:var(--shadow-soft);
      text-align:center;
    }

    .vl-trust-tile-icon{
      width:32px; height:32px;
      border-radius:12px;
      background:linear-gradient(135deg, rgba(255,181,46,.12), rgba(201,87,255,.12));
      border:1px solid rgba(201,87,255,.12);
      display:grid;
      place-items:center;
      margin:0 auto 7px;
      color:var(--brand);
    }

    .vl-trust-tile-icon svg{ width:15px; height:15px; }
    .vl-trust-tile strong{ display:block; color:var(--text); font-size:10px; font-weight:850; line-height:1.2; }
    .vl-trust-tile span{ display:block; margin-top:2px; color:var(--muted); font-size:9px; font-weight:650; }

    /* FOOTER */
    .vl-footer{
      text-align:center;
      font-size:12.5px;
      color:var(--muted);
      font-weight:600;
      line-height:1.6;
    }

    .vl-footer a{ color:var(--brand); font-weight:850; }
    .vl-footer a:hover{ text-decoration:underline; }

    .vl-copyright{
      text-align:center;
      font-size:10.5px;
      color:var(--muted2);
      font-weight:500;
      margin-top:10px;
    }

    /* SKELETON */
    .vl-skeleton{
      padding:18px;
      border-radius:16px;
      background:#fff8ef;
      color:var(--muted);
      font-size:12.5px;
      font-weight:650;
      text-align:center;
      animation:vlSkeletonPulse 1.4s ease-in-out infinite;
    }

    .vl-skeleton-bars{ margin-top:12px; display:flex; flex-direction:column; gap:10px; }

    .vl-skeleton-bar{
      height:50px;
      border-radius:16px;
      background:rgba(75,16,21,.06);
      animation:vlSkeletonPulse 1.4s ease-in-out infinite;
    }

    .vl-skeleton-bar:nth-child(2){ animation-delay:.15s; }
    .vl-skeleton-bar:nth-child(3){ height:52px; animation-delay:.3s; }

    /* ANIMATIONS */
    @keyframes vlFloat{ 0%,100%{ transform:translate3d(0,0,0); } 50%{ transform:translate3d(0,-6px,0); } }
    @keyframes vlShine{ 0%{ left:-100%; } 20%{ left:160%; } 100%{ left:160%; } }
    @keyframes vlSkeletonPulse{ 0%,100%{ opacity:.7; } 50%{ opacity:1; } }
    @keyframes vlFadeUp{ from{ opacity:0; transform:translateY(14px); } to{ opacity:1; transform:translateY(0); } }

    .vl-page{ animation:vlFadeUp .45s ease both; }

    @media (max-width:370px){
      .vl-page{ padding-left:8px; padding-right:8px; }
      .vl-logo{ width:44px; height:44px; border-radius:15px; }
      .vl-logo img{ width:38px; height:38px; }
      .vl-brand-copy h1{ font-size:21px; }
      .vl-hero-title{ font-size:24px; }
      .vl-hero-logo-wrap{ width:68px; height:68px; }
      .vl-hero-logo-wrap img{ width:50px; height:50px; }
    }

    @media (prefers-reduced-motion:reduce){ *,*::before,*::after{ animation:none !important; transition:none !important; } }
  </style>
</head>
<body>

<main class="vl-page">
  <div class="vl-phone">

    {{-- HEADER --}}
    <header class="vl-topbar">
      <div class="vl-brand">
        <div class="vl-logo">
          <img src="{{ asset('logo.png') }}" alt="Velora Finance">
        </div>
        <div class="vl-brand-copy">
          <span>Velora Finance</span>
          <h1>Masuk Akun</h1>
        </div>
      </div>

      <div class="vl-kicker-pill">Secure</div>
    </header>

    {{-- HERO BANNER --}}
    <section class="vl-hero-banner">
      <div class="vl-hero-logo-wrap">
        <img src="{{ asset('logo.png') }}" alt="Velora Finance">
      </div>

      <div class="vl-hero-tagline">✦ Portal Resmi Velora</div>
      <h2 class="vl-hero-title">Selamat Datang</h2>
      <p class="vl-hero-sub">Masuk ke akun Velora Finance Anda dengan aman</p>

      <div class="vl-hero-stats">
        <div class="vl-stat">
          <div class="vl-stat-icon">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M4 17l6-6 4 4 6-8" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <strong>Pertumbuhan</strong>
          <span>Berkelanjutan</span>
        </div>
        <div class="vl-stat">
          <div class="vl-stat-icon">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 3l7 3v5c0 4.9-3.1 8.6-7 10C8.1 19.6 5 15.9 5 11V6l7-3z" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
            </svg>
          </div>
          <strong>Akun Aman</strong>
          <span>Terenkripsi</span>
        </div>
        <div class="vl-stat">
          <div class="vl-stat-icon">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 2.5 20 7v10l-8 4.5L4 17V7l8-4.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
          </div>
          <strong>Aset Digital</strong>
          <span>Terpantau</span>
        </div>
      </div>
    </section>

    {{-- TABS --}}
    <nav class="vl-tabs" aria-label="Navigasi Masuk / Daftar">
      <a href="{{ route('register.form') }}" class="vl-tab">
        <svg viewBox="0 0 24 24" fill="none">
          <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          <path d="M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="2"/>
          <path d="M19 8v6M22 11h-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        Daftar
      </a>
      <a href="{{ url('/login') }}" class="vl-tab active">
        <svg viewBox="0 0 24 24" fill="none">
          <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          <path d="M10 17l5-5-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M15 12H3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        Masuk
      </a>
    </nav>

    {{-- FORM PANEL --}}
    <div class="vl-panel">
      <div class="vl-panel-head">
        <div class="vl-panel-eyebrow">
          <div class="vl-panel-bar"></div>
          <span class="vl-panel-label">Login</span>
        </div>
        <h2 class="vl-panel-title">Masuk Akun</h2>
        <p class="vl-panel-sub">Gunakan nomor WhatsApp terdaftar Anda</p>
      </div>

      <div id="dynamicFormContainer">
        <div class="vl-skeleton">
          <div>Memuat portal aman...</div>
          <div class="vl-skeleton-bars">
            <div class="vl-skeleton-bar"></div>
            <div class="vl-skeleton-bar"></div>
            <div class="vl-skeleton-bar"></div>
          </div>
        </div>
      </div>
    </div>

    {{-- TRUST TILES --}}
    <div class="vl-trust-tiles">
      <div class="vl-trust-tile">
        <div class="vl-trust-tile-icon">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M12 3l7 3v5c0 4.9-3.1 8.6-7 10C8.1 19.6 5 15.9 5 11V6l7-3z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            <path d="M8.5 11.5l2.2 2.2 4.8-5.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <strong>Akses Aman</strong>
        <span>Privasi terjaga</span>
      </div>
      <div class="vl-trust-tile">
        <div class="vl-trust-tile-icon">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M4 17l6-6 4 4 6-8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M14 7h6v6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <strong>Pertumbuhan</strong>
        <span>Real-time</span>
      </div>
      <div class="vl-trust-tile">
        <div class="vl-trust-tile-icon">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M12 2.5 20 7v10l-8 4.5L4 17V7l8-4.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            <path d="M4.5 7.3 12 11.6l7.5-4.3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M12 11.6v8.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </div>
        <strong>Aset Digital</strong>
        <span>Terverifikasi</span>
      </div>
    </div>

    <p class="vl-footer">
      Belum punya akun? <a href="{{ route('register.form') }}">Daftar melalui halaman resmi</a>
    </p>
    <p class="vl-copyright">© {{ date('Y') }} Velora Finance. Tumbuh bersama, melalui akses resmi.</p>

  </div>
</main>

<script>
(function(){
  const container = document.getElementById('dynamicFormContainer');
  if(!container) return;

  let injected = false;
  const csrfToken = '{{ csrf_token() }}';
  const oldPhone = @json(old('phone'));
  const oldRemember = @json(old('remember'));

  function esc(str){
    if(!str) return '';
    return String(str)
      .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
      .replace(/"/g,'&quot;').replace(/'/g,'&#39;');
  }

  function injectForm(){
    if(injected) return;
    injected = true;

    const phoneVal = esc(oldPhone || '');
    const checkedAttr = oldRemember ? 'checked' : '';

    @if ($errors->any())
      const errorsHtml = `
        <div class="vl-error">
          <svg viewBox="0 0 24 24" fill="none">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
            <path d="M12 8v4M12 16h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
          <div>
            @foreach ($errors->all() as $error)
              {{ $error }}<br>
            @endforeach
          </div>
        </div>
      `;
    @else
      const errorsHtml = '';
    @endif

    container.innerHTML = `
      ${errorsHtml}
      <form method="POST" action="{{ route('login.store') }}" autocomplete="off" novalidate>
        <input type="hidden" name="_token" value="${csrfToken}">

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
              value="${phoneVal}" placeholder="08123456789"
              inputmode="numeric" pattern="08[0-9]{8,12}" required />
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
              placeholder="Masukkan kata sandi" style="padding-right:50px" required />
            <button class="vl-toggle-pass" type="button" id="togglePassBtn" aria-label="Tampilkan kata sandi">
              <svg id="eyeIcon" viewBox="0 0 24 24" fill="none">
                <path d="M1.5 12s4-7.5 10.5-7.5S22.5 12 22.5 12 18.5 19.5 12 19.5 1.5 12 1.5 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>
          </div>
        </div>

        <input type="text" name="website" tabindex="-1" autocomplete="off" style="display:none">

        <div class="vl-helper">
          <label class="vl-remember">
            <input type="checkbox" name="remember" value="1" ${checkedAttr}>
            Ingat akun
          </label>
          <a class="vl-forgot" href="{{ url('/login') }}">Lupa sandi?</a>
        </div>

        <button class="vl-btn-submit" type="submit">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
            <path d="M10 17l5-5-5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M15 12H3" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
          </svg>
          <span>Masuk Sekarang</span>
        </button>
      </form>
    `;

    const btn = document.getElementById('togglePassBtn');
    const pwd = document.getElementById('password');
    const eye = document.getElementById('eyeIcon');
    if(btn && pwd && eye){
      btn.addEventListener('click', () => {
        const show = pwd.type === 'password';
        pwd.type = show ? 'text' : 'password';
        eye.innerHTML = show
          ? '<path d="M3 3l18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10.58 10.58A2 2 0 0 0 12 14a2 2 0 0 0 1.42-.58" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M9.88 5.09A9.77 9.77 0 0 1 12 4.86C18.5 4.86 22.5 12 22.5 12a17.56 17.56 0 0 1-3.09 4.08" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.61 6.61C3.32 8.78 1.5 12 1.5 12s4 7.14 10.5 7.14a9.9 9.9 0 0 0 4.1-.88" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'
          : '<path d="M1.5 12s4-7.5 10.5-7.5S22.5 12 22.5 12 18.5 19.5 12 19.5 1.5 12 1.5 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
      });
    }

    const phoneInput = document.getElementById('phone');
    if(phoneInput){
      phoneInput.addEventListener('input', function(){
        this.value = this.value.replace(/[^0-9]/g,'').slice(0,14);
      });
    }

    const form = container.querySelector('form');
    if(form && phoneInput && pwd){
      form.addEventListener('submit', e => {
        const p = phoneInput.value.trim();
        if(!/^08[0-9]{8,12}$/.test(p)){
          e.preventDefault();
          alert('Nomor WhatsApp harus diawali 08 dan berisi 10–14 digit.');
          phoneInput.focus();
          return;
        }
        if(pwd.value.trim().length < 6){
          e.preventDefault();
          alert('Kata sandi minimal 6 karakter.');
          pwd.focus();
        }
      });
    }
  }

  setTimeout(injectForm, 2000);
})();
</script>
</body>
</html>
