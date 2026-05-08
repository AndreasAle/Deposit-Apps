
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Undangan Resmi | Rubik Company</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

  {{-- Halaman undangan referral tidak perlu di-index --}}
  <meta name="robots" content="noindex, nofollow">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --green:#18c79b;
      --green-2:#42dfb2;
      --green-3:#0b8f70;
      --green-dark:#0d7f67;

      --dark:#031816;
      --dark-2:#05231f;
      --dark-3:#010f0d;

      --gold:#d6aa35;
      --gold-2:#f5d879;

      --white:#ffffff;
      --text:#10322c;
      --muted:#6c8b82;
      --line:#e2eee9;
      --danger:#ef4444;
    }

    *{
      box-sizing:border-box;
    }

    html,
    body{
      min-height:100%;
    }

    body{
      margin:0;
      min-height:100vh;
      font-family:'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      color:var(--text);
      background:
        radial-gradient(760px 420px at 10% -6%, rgba(0,223,130,.16), transparent 58%),
        radial-gradient(640px 360px at 100% 0%, rgba(13,127,103,.18), transparent 62%),
        linear-gradient(180deg, #f7fffb 0%, #e9fbf2 48%, #d8f3e5 100%);
      overflow-x:hidden;
      -webkit-tap-highlight-color:transparent;
    }

    a{
      color:inherit;
    }

    .page{
      width:100%;
      min-height:100vh;
      display:flex;
      justify-content:center;
      animation:pageEnter .45s ease both;
    }

    .shell{
      position:relative;
      overflow:hidden;
      width:100%;
      max-width:none;
      min-height:100vh;
      background:#ffffff;
      box-shadow:none;
    }

    .hero{
      position:relative;
      width:100%;
      padding:18px 22px 42px;
      overflow:hidden;
      background:
        linear-gradient(152deg, rgba(255,255,255,.10) 0%, rgba(255,255,255,.04) 28%, transparent 29%),
        radial-gradient(260px 160px at 92% 105%, rgba(0,223,130,.22), transparent 62%),
        radial-gradient(230px 160px at 16% 0%, rgba(3,98,76,.58), transparent 72%),
        linear-gradient(180deg, #10251f 0%, #041311 58%, #020908 100%);
    }

    .hero::before{
      content:"";
      position:absolute;
      left:-48px;
      top:-34px;
      width:190px;
      height:170px;
      border-radius:44% 56% 54% 46% / 42% 44% 56% 58%;
      background:
        radial-gradient(circle at 30% 18%, rgba(255,255,255,.12), transparent 34%),
        linear-gradient(145deg, rgba(0,223,130,.22), rgba(3,98,76,.48));
      box-shadow:
        0 0 50px rgba(0,223,130,.08),
        inset 0 1px 0 rgba(255,255,255,.08);
      animation:blobFloat 6s ease-in-out infinite;
    }

    .hero::after{
      content:"";
      position:absolute;
      right:-38px;
      top:-38px;
      width:168px;
      height:138px;
      border-radius:34px 0 0 64px;
      background:
        radial-gradient(circle at 26% 26%, rgba(255,255,255,.18), transparent 34%),
        linear-gradient(145deg, rgba(214,255,240,.16), rgba(0,223,130,.08));
      border:1px solid rgba(255,255,255,.08);
      backdrop-filter:blur(4px);
      -webkit-backdrop-filter:blur(4px);
      animation:blobFloat2 7s ease-in-out infinite;
    }

    .topbar{
      position:relative;
      z-index:2;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
    }

    .brandMini{
      display:flex;
      align-items:center;
      gap:10px;
      min-width:0;
    }

    .brandMiniLogo{
      width:44px;
      height:44px;
      border-radius:16px;
      background:#ffffff;
      display:grid;
      place-items:center;
      overflow:hidden;
      box-shadow:
        0 12px 28px rgba(0,0,0,.22),
        0 0 0 1px rgba(255,255,255,.70) inset;
      flex:0 0 auto;
    }

    .brandMiniLogo img{
      width:34px;
      height:34px;
      object-fit:contain;
      display:block;
    }

    .brandMiniText span{
      display:block;
      color:rgba(223,252,241,.68);
      font-size:11px;
      font-weight:700;
      line-height:1;
      margin-bottom:4px;
    }

    .brandMiniText strong{
      display:block;
      color:#ffffff;
      font-size:15px;
      font-weight:900;
      line-height:1;
      white-space:nowrap;
    }

    .topPill{
      min-height:34px;
      padding:0 13px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      gap:7px;
      color:#fff5d6;
      background:rgba(255,255,255,.08);
      border:1px solid rgba(255,255,255,.13);
      font-size:11px;
      font-weight:900;
      backdrop-filter:blur(10px);
      -webkit-backdrop-filter:blur(10px);
      white-space:nowrap;
    }

    .topPill::before{
      content:"";
      width:8px;
      height:8px;
      border-radius:999px;
      background:linear-gradient(135deg, var(--gold-2), var(--gold));
      box-shadow:0 0 14px rgba(245,216,121,.35);
    }

    .heroMain{
      position:relative;
      z-index:2;
      margin-top:28px;
      text-align:center;
    }

    .heroLogoCard{
      width:126px;
      min-height:126px;
      margin:0 auto 13px;
      border-radius:28px;
      padding:13px;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.85), transparent 38%),
        linear-gradient(180deg, rgba(255,255,255,.96), rgba(238,255,248,.92));
      border:1px dashed rgba(13,127,103,.20);
      box-shadow:
        0 24px 46px rgba(0,0,0,.28),
        0 0 0 8px rgba(255,255,255,.04),
        inset 0 1px 0 rgba(255,255,255,.85);
      display:grid;
      place-items:center;
      position:relative;
    }

    .heroLogoCard::after{
      content:"";
      position:absolute;
      inset:10px;
      border-radius:22px;
      border:1px solid rgba(13,127,103,.10);
      pointer-events:none;
    }

    .heroLogoCard img{
      width:88px;
      height:88px;
      object-fit:contain;
      display:block;
      position:relative;
      z-index:1;
    }

    .heroBadge{
      width:max-content;
      max-width:100%;
      margin:0 auto 10px;
      min-height:30px;
      padding:0 13px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:7px;
      color:#6f5000;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.62), transparent 38%),
        linear-gradient(180deg, #fff1bd, #d9ae36);
      border:1px solid rgba(139,101,0,.12);
      box-shadow:
        0 10px 18px rgba(139,101,0,.12),
        inset 0 1px 0 rgba(255,255,255,.52);
      font-size:11px;
      font-weight:900;
    }

    .heroTitle{
      margin:0;
      font-family:'Playfair Display', serif;
      color:#ffffff;
      font-size:30px;
      line-height:1.05;
      font-weight:800;
      letter-spacing:-.035em;
      text-shadow:0 10px 24px rgba(0,0,0,.22);
    }

    .heroTitle span{
      color:#f0cc62;
    }

    .heroSub{
      width:min(340px, 100%);
      margin:10px auto 0;
      color:rgba(223,252,241,.74);
      font-size:12px;
      line-height:1.6;
      font-weight:650;
    }

    .heroStats{
      position:relative;
      z-index:2;
      margin:16px auto -22px;
      display:grid;
      grid-template-columns:repeat(3, 1fr);
      gap:8px;
    }

    .heroStat{
      min-height:56px;
      border-radius:16px;
      padding:10px 8px;
      background:rgba(255,255,255,.95);
      border:1px solid rgba(13,127,103,.10);
      box-shadow:
        0 16px 28px rgba(0,0,0,.15),
        inset 0 1px 0 rgba(255,255,255,.82);
      text-align:center;
    }

    .heroStatIcon{
      width:18px;
      height:18px;
      margin:0 auto 5px;
      color:#d3a12a;
    }

    .heroStat strong{
      display:block;
      color:#14382f;
      font-size:10.5px;
      font-weight:900;
      line-height:1.15;
    }

    .heroStat span{
      display:block;
      margin-top:2px;
      color:#819890;
      font-size:9px;
      font-weight:700;
      line-height:1.15;
    }

    .content{
      position:relative;
      z-index:5;
      width:100%;
      margin:0;
      padding:36px 24px 24px;
      background:
        radial-gradient(220px 140px at 100% 100%, rgba(98,221,177,.22), transparent 60%),
        radial-gradient(180px 120px at 78% 18%, rgba(255,255,255,.16), transparent 58%),
        linear-gradient(135deg, #e8fbf2 0%, #d7f5e7 52%, #c8efd9 100%);
      box-shadow:0 -12px 32px rgba(6,34,29,.10);
      min-height:calc(100vh - 146px);
      overflow:hidden;
    }

    .content::before{
      content:"";
      position:absolute;
      inset:0;
      pointer-events:none;
      background:
        radial-gradient(180px 120px at 20% 0%, rgba(255,255,255,.22), transparent 60%),
        radial-gradient(260px 180px at 100% 100%, rgba(24,199,155,.10), transparent 66%);
    }

    .content > *{
      position:relative;
      z-index:1;
    }

    .invitePanel{
      padding:18px 16px 16px;
      border-radius:24px;
      background:rgba(255,255,255,.82);
      border:1px solid rgba(13,127,103,.12);
      box-shadow:
        0 18px 36px rgba(6,34,29,.08),
        inset 0 1px 0 rgba(255,255,255,.75);
    }

    .panelDecor{
      width:74px;
      height:4px;
      border-radius:999px;
      margin:0 auto 12px;
      background:linear-gradient(90deg, #0d5c46, #00c97a, #d6aa35);
    }

    .eyebrow{
      width:max-content;
      max-width:100%;
      margin:0 auto 10px;
      min-height:30px;
      padding:0 13px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:7px;
      color:#0d7f67;
      background:rgba(255,255,255,.72);
      border:1px solid rgba(13,127,103,.13);
      font-size:11px;
      font-weight:900;
      text-transform:uppercase;
      letter-spacing:.04em;
    }

    .title{
      text-align:center;
      margin:0;
      font-family:'Playfair Display', serif;
      font-size:27px;
      line-height:1.08;
      font-weight:800;
      color:#173d35;
      letter-spacing:-.04em;
    }

    .subtitle{
      width:min(350px, 100%);
      text-align:center;
      margin:9px auto 16px;
      color:#6c8b82;
      font-size:12.5px;
      line-height:1.6;
      font-weight:650;
    }

    .notice{
      border-radius:20px;
      padding:14px;
      background:
        radial-gradient(circle at 100% 0%, rgba(255,255,255,.70), transparent 44%),
        linear-gradient(135deg, rgba(248,255,252,.92), rgba(226,248,238,.82));
      border:1px solid rgba(13,127,103,.14);
      box-shadow:
        0 12px 22px rgba(6,34,29,.045),
        inset 0 1px 0 rgba(255,255,255,.70);
      color:#31544c;
      font-size:12.5px;
      line-height:1.6;
      font-weight:650;
      margin:14px 0;
    }

    .notice strong{
      color:#173d35;
      font-weight:900;
    }

    .infoList{
      display:grid;
      gap:10px;
      margin:14px 0 16px;
    }

    .infoItem{
      display:grid;
      grid-template-columns:40px 1fr;
      gap:12px;
      align-items:flex-start;
      padding:13px;
      border-radius:18px;
      background:
        linear-gradient(180deg, rgba(255,255,255,.92), rgba(244,255,250,.84));
      border:1px solid rgba(13,127,103,.10);
      box-shadow:
        0 10px 20px rgba(6,34,29,.045),
        inset 0 1px 0 rgba(255,255,255,.70);
    }

    .infoIcon{
      width:40px;
      height:40px;
      border-radius:14px;
      display:grid;
      place-items:center;
      color:#f6d97a;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.16), transparent 34%),
        linear-gradient(135deg, #031816 0%, #0a2f27 45%, #0d7f67 100%);
      box-shadow:
        0 10px 18px rgba(6,34,29,.12),
        inset 0 1px 0 rgba(255,255,255,.14);
    }

    .infoIcon svg{
      width:20px;
      height:20px;
    }

    .infoText strong{
      display:block;
      color:#173d35;
      font-size:13.5px;
      font-weight:900;
      line-height:1.25;
      margin-bottom:4px;
    }

    .infoText span{
      display:block;
      color:#6c8b82;
      font-size:11.8px;
      font-weight:650;
      line-height:1.5;
    }

    .safeBox{
      display:flex;
      align-items:flex-start;
      gap:12px;
      padding:14px;
      border-radius:20px;
      background:
        radial-gradient(180px 100px at 100% 0%, rgba(255,255,255,.10), transparent 60%),
        linear-gradient(135deg, #031816 0%, #0b4d3d 38%, #0d7f67 72%, #1fe08e 100%);
      color:#fff;
      box-shadow:0 14px 28px rgba(6,34,29,.16);
      margin-bottom:16px;
    }

    .safeIcon{
      width:38px;
      height:38px;
      flex:0 0 38px;
      border-radius:12px;
      display:grid;
      place-items:center;
      color:#f6d97a;
      background:rgba(255,255,255,.10);
      border:1px solid rgba(255,255,255,.14);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.12);
    }

    .safeIcon svg{
      width:21px;
      height:21px;
    }

    .safeText strong{
      display:block;
      font-size:13px;
      font-weight:900;
      line-height:1.25;
      margin-bottom:3px;
    }

    .safeText span{
      display:block;
      font-size:11.5px;
      line-height:1.45;
      font-weight:700;
      color:rgba(240,255,248,.86);
    }

    .actions{
      display:grid;
      gap:10px;
    }

    .btn{
      min-height:52px;
      border:none;
      border-radius:15px;
      cursor:pointer;
      font-size:14px;
      font-weight:900;
      letter-spacing:.01em;
      text-decoration:none;
      display:flex;
      align-items:center;
      justify-content:center;
      gap:8px;
      position:relative;
      overflow:hidden;
      transition:.2s ease;
    }

    .btn svg{
      width:17px;
      height:17px;
    }

    .btnPrimary{
      color:#ffffff;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.16), transparent 34%),
        linear-gradient(135deg, #031816 0%, #0a2f27 35%, #0d5c46 68%, #00c97a 100%);
      box-shadow:
        0 14px 28px rgba(0,0,0,.18),
        0 10px 26px rgba(0,223,130,.18),
        inset 0 1px 0 rgba(255,255,255,.12);
    }

    .btnPrimary::after{
      content:"";
      position:absolute;
      top:0;
      left:-120%;
      width:55%;
      height:100%;
      background:linear-gradient(to right, transparent, rgba(255,255,255,.22), transparent);
      transform:skewX(-18deg);
      animation:btnShine 3.2s infinite;
      pointer-events:none;
    }

    .btnGhost{
      color:#0d7f67;
      background:
        linear-gradient(135deg, rgba(248,255,252,.92), rgba(226,248,238,.82));
      border:1px solid rgba(13,127,103,.14);
      box-shadow:
        0 10px 22px rgba(6,34,29,.045),
        inset 0 1px 0 rgba(255,255,255,.70);
    }

    .btn:hover{
      transform:translateY(-1px);
      filter:brightness(1.03);
    }

    .small{
      margin:14px 0 0;
      text-align:center;
      color:#6c8b82;
      font-size:11.5px;
      line-height:1.55;
      font-weight:650;
    }

    .copyright{
      margin:16px 0 0;
      text-align:center;
      color:#6c8b82;
      font-size:10.5px;
      font-weight:700;
      line-height:1.5;
    }

    @keyframes pageEnter{
      from{ opacity:0; transform:translateY(12px); }
      to{ opacity:1; transform:translateY(0); }
    }

    @keyframes blobFloat{
      0%,100%{ transform:translate3d(0,0,0) rotate(0deg); }
      50%{ transform:translate3d(-8px,8px,0) rotate(5deg); }
    }

    @keyframes blobFloat2{
      0%,100%{ transform:translate3d(0,0,0); }
      50%{ transform:translate3d(7px,-7px,0); }
    }

    @keyframes btnShine{
      0%{ left:-120%; }
      20%{ left:180%; }
      100%{ left:180%; }
    }

    @media (max-width:380px){
      .hero{
        padding-left:18px;
        padding-right:18px;
      }

      .content{
        padding-left:18px;
        padding-right:18px;
      }

      .heroTitle{
        font-size:28px;
      }

      .heroStats{
        gap:6px;
      }

      .invitePanel{
        padding-left:14px;
        padding-right:14px;
      }
    }

    @media (prefers-reduced-motion: reduce){
      *,
      *::before,
      *::after{
        animation:none !important;
        transition:none !important;
      }
    }
  </style>
</head>
<body>
  <main class="page">
    <section class="shell" role="region" aria-label="Undangan Resmi Rubik Company">

      <header class="hero">
        <div class="topbar">
          <div class="brandMini">
            <div class="brandMiniLogo">
              <img src="{{ asset('logo.png') }}" alt="Rubik Company">
            </div>

            <div class="brandMiniText">
              <span>Platform Akun</span>
              <strong>Rubik Company</strong>
            </div>
          </div>

          <div class="topPill">AKB</div>
        </div>

        <div class="heroMain">
          <div class="heroLogoCard">
            <img src="{{ asset('logo.png') }}" alt="Rubik Company">
          </div>

          <div class="heroBadge">
            ✦ Undangan resmi Rubik
          </div>

          <h1 class="heroTitle">
            Akses Akun <span>Resmi</span>
          </h1>

          <p class="heroSub">
            Kamu menerima undangan untuk membuat akun Rubik melalui halaman resmi yang aman dan transparan.
          </p>
        </div>

        <div class="heroStats">
          <div class="heroStat">
            <svg class="heroStatIcon" viewBox="0 0 24 24" fill="none">
              <path d="M4 19V9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M10 19V5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M16 19v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M22 19H2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <strong>Dashboard</strong>
            <span>Akun</span>
          </div>

          <div class="heroStat">
            <svg class="heroStatIcon" viewBox="0 0 24 24" fill="none">
              <path d="M4 17l6-6 4 4 6-8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M14 7h6v6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <strong>Referral</strong>
            <span>Transparan</span>
          </div>

          <div class="heroStat">
            <svg class="heroStatIcon" viewBox="0 0 24 24" fill="none">
              <path d="M12 3l7 3v5c0 4.9-3.1 8.6-7 10-3.9-1.4-7-5.1-7-10V6l7-3z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              <path d="M8.5 11.5l2.2 2.2 4.8-5.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <strong>Aman</strong>
            <span>Privasi</span>
          </div>
        </div>
      </header>

      <div class="content">
        <section class="invitePanel">
          <div class="panelDecor"></div>

          <div class="eyebrow">
            Undangan akun
          </div>

          <h1 class="title">Undangan resmi Rubik</h1>

          <p class="subtitle">
            Kode undangan hanya digunakan untuk menghubungkan akun kamu dengan pengundang di sistem referral.
          </p>

          <div class="notice">
            <strong>Perhatian keamanan:</strong> Rubik tidak pernah meminta password melalui chat, telepon, atau pihak lain.
            Buat akun hanya melalui halaman resmi dan pastikan data yang kamu isi benar.
          </div>

          <div class="infoList">
            <div class="infoItem">
              <div class="infoIcon">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M4 19V9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  <path d="M10 19V5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  <path d="M16 19v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  <path d="M22 19H2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
              </div>
              <div class="infoText">
                <strong>Akses akun</strong>
                <span>Kelola dashboard, saldo, riwayat transaksi, dan aktivitas akun kamu.</span>
              </div>
            </div>

            <div class="infoItem">
              <div class="infoIcon">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  <path d="M6 11h12a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="2"/>
                </svg>
              </div>
              <div class="infoText">
                <strong>Referral transparan</strong>
                <span>Kode referral hanya menjadi penanda pengundang. Tidak mengubah keamanan akun kamu.</span>
              </div>
            </div>

            <div class="infoItem">
              <div class="infoIcon">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M12 3l7 3v5c0 4.9-3.1 8.6-7 10-3.9-1.4-7-5.1-7-10V6l7-3z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                  <path d="M8.5 11.5l2.2 2.2 4.8-5.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <div class="infoText">
                <strong>Keamanan pengguna</strong>
                <span>Jangan bagikan password atau kode akses akun kepada siapa pun.</span>
              </div>
            </div>
          </div>

          <div class="safeBox">
            <div class="safeIcon">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M12 3l7 3v5c0 4.9-3.1 8.6-7 10-3.9-1.4-7-5.1-7-10V6l7-3z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                <path d="M8.5 11.5l2.2 2.2 4.8-5.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>

            <div class="safeText">
              <strong>Akses resmi perusahaan</strong>
              <span>Pastikan pendaftaran hanya melalui halaman resmi Rubik dan jangan membagikan password ke pihak mana pun.</span>
            </div>
          </div>

          <div class="actions">
            <a class="btn btnPrimary" href="{{ route('register.form') }}">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="2"/>
                <path d="M19 8v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M22 11h-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
              Lanjut ke Pendaftaran
            </a>

            <a class="btn btnGhost" href="{{ route('home') }}">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M3 11l9-8 9 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M5 10v10h14V10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              Lihat Informasi Platform
            </a>
          </div>

          <div class="small">
            Dengan melanjutkan, kamu akan diarahkan ke halaman pendaftaran akun Rubik.
          </div>
        </section>

        <div class="copyright">
          © {{ date('Y') }} Rubik Company. Tumbuh bersama, melalui akses resmi.
        </div>
      </div>
    </section>
  </main>
</body>
</html>