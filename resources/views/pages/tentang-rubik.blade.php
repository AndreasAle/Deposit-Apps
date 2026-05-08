@php
  $user = auth()->user();
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Tentang Rubik | Rubik Company</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --ab-bg:#030F0F;
      --ab-bg2:#061817;
      --ab-card:#071f1b;
      --ab-card2:#092a23;
      --ab-text:#f7fffb;
      --ab-soft:#dffcf1;
      --ab-muted:#9bb9ad;
      --ab-muted2:#6f9084;
      --ab-border:rgba(255,255,255,.09);
      --ab-green:#00DF82;
      --ab-green2:#72ffab;
      --ab-blue:#34d5ff;
      --ab-amber:#f6c453;
      --ab-violet:#a78bfa;
      --ab-red:#fb7185;
      --ab-shadow:0 28px 70px rgba(0,0,0,.46);
      --ab-shadow-soft:0 16px 34px rgba(0,0,0,.24);
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
      font-family:Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      color:var(--ab-text);
      background:
        radial-gradient(760px 420px at 14% -2%, rgba(0,223,130,.20), transparent 58%),
        radial-gradient(620px 360px at 90% 10%, rgba(90,140,255,.16), transparent 62%),
        radial-gradient(520px 300px at 55% 100%, rgba(246,196,83,.10), transparent 62%),
        linear-gradient(180deg, #071f1a 0%, #030f0f 48%, #020807 100%);
      overflow-x:hidden;
      -webkit-tap-highlight-color:transparent;
    }

    body::before{
      content:"";
      position:fixed;
      inset:0;
      pointer-events:none;
      background:
        linear-gradient(rgba(255,255,255,.022) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.016) 1px, transparent 1px);
      background-size:38px 38px;
      mask-image:linear-gradient(180deg, rgba(0,0,0,.70), transparent 76%);
      -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.70), transparent 76%);
      opacity:.48;
      z-index:0;
    }

    a{
      color:inherit;
      text-decoration:none;
    }

    button{
      font-family:inherit;
    }

    .ab-page{
      width:100%;
      min-height:100vh;
      display:flex;
      justify-content:center;
      padding:14px 10px 0;
      position:relative;
      z-index:1;
    }

    .ab-phone{
      width:100%;
      max-width:430px;
      min-height:100vh;
      position:relative;
      padding:8px 4px 108px;
    }

    .ab-header{
      display:flex;
      align-items:center;
      gap:12px;
      margin-bottom:14px;
      padding:0 2px;
      animation:abFadeDown .42s ease both;
    }

    .ab-back{
      width:42px;
      height:42px;
      border-radius:15px;
      border:1px solid rgba(255,255,255,.10);
      background:
        radial-gradient(circle at 32% 18%, rgba(255,255,255,.18), transparent 34%),
        linear-gradient(180deg, rgba(10,42,35,.96), rgba(4,18,16,.96));
      color:#fff;
      display:grid;
      place-items:center;
      box-shadow:
        0 13px 28px rgba(0,0,0,.34),
        0 0 0 1px rgba(0,223,130,.06) inset;
      flex:0 0 auto;
      transition:.18s ease;
    }

    .ab-back:hover{
      transform:translateY(-1px);
      border-color:rgba(0,223,130,.26);
    }

    .ab-back svg{
      width:20px;
      height:20px;
    }

    .ab-title{
      min-width:0;
    }

    .ab-title span{
      display:block;
      margin-bottom:5px;
      color:rgba(214,255,240,.58);
      font-size:11px;
      line-height:1;
      font-weight:650;
    }

    .ab-title h1{
      margin:0;
      color:#fff;
      font-size:23px;
      line-height:1;
      font-weight:900;
      letter-spacing:-.045em;
      white-space:nowrap;
    }

    /* HERO */
    .ab-hero{
      position:relative;
      overflow:hidden;
      border-radius:30px;
      padding:18px;
      min-height:286px;
      background:
        radial-gradient(280px 170px at 16% 0%, rgba(0,223,130,.34), transparent 64%),
        radial-gradient(260px 170px at 110% 10%, rgba(52,213,255,.24), transparent 60%),
        linear-gradient(180deg, rgba(12,45,38,.98), rgba(3,15,15,.98));
      border:1px solid rgba(255,255,255,.10);
      box-shadow:
        0 24px 54px rgba(0,0,0,.42),
        0 0 0 1px rgba(0,223,130,.10) inset,
        0 0 40px rgba(0,223,130,.08);
      margin-bottom:13px;
      animation:abFadeUp .48s ease both;
    }

    .ab-hero::before{
      content:"";
      position:absolute;
      inset:0;
      background:
        linear-gradient(145deg, rgba(255,255,255,.12), transparent 35%),
        radial-gradient(circle at 50% 110%, rgba(0,223,130,.18), transparent 58%);
      pointer-events:none;
    }

    .ab-orb{
      position:absolute;
      border-radius:999px;
      pointer-events:none;
      filter:blur(.2px);
      opacity:.9;
    }

    .ab-orb.one{
      width:96px;
      height:96px;
      right:-26px;
      top:36px;
      background:rgba(0,223,130,.15);
      box-shadow:0 0 42px rgba(0,223,130,.18);
      animation:abOrbFloat1 5.4s ease-in-out infinite;
    }

    .ab-orb.two{
      width:58px;
      height:58px;
      left:22px;
      bottom:64px;
      background:rgba(52,213,255,.14);
      box-shadow:0 0 34px rgba(52,213,255,.18);
      animation:abOrbFloat2 4.8s ease-in-out infinite;
    }

    .ab-orb.three{
      width:28px;
      height:28px;
      right:92px;
      bottom:26px;
      background:rgba(246,196,83,.22);
      box-shadow:0 0 26px rgba(246,196,83,.20);
      animation:abOrbFloat3 3.8s ease-in-out infinite;
    }

    .ab-hero-content{
      position:relative;
      z-index:2;
    }

    .ab-hero-badge{
      width:max-content;
      min-height:32px;
      padding:0 12px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      gap:7px;
      color:#cffff0;
      background:rgba(255,255,255,.07);
      border:1px solid rgba(255,255,255,.10);
      font-size:11px;
      font-weight:900;
      margin-bottom:13px;
      box-shadow:inset 0 1px 0 rgba(255,255,255,.08);
    }

    .ab-hero-badge::before{
      content:"";
      width:7px;
      height:7px;
      border-radius:999px;
      background:var(--ab-green);
      box-shadow:
        0 0 0 4px rgba(0,223,130,.12),
        0 0 16px rgba(0,223,130,.60);
      animation:abPulseDot 1.8s ease-in-out infinite;
    }

    .ab-hero h2{
      margin:0;
      max-width:350px;
      color:#ffffff;
      font-size:31px;
      line-height:1.02;
      letter-spacing:-.065em;
      font-weight:950;
      text-shadow:0 12px 28px rgba(0,0,0,.26);
    }

    .ab-hero h2 span{
      color:var(--ab-green);
      text-shadow:0 0 22px rgba(0,223,130,.22);
    }

    .ab-hero p{
      margin:11px 0 0;
      max-width:340px;
      color:rgba(214,255,240,.68);
      font-size:13px;
      line-height:1.55;
      font-weight:620;
    }

    .ab-hero-visual{
      position:relative;
      z-index:2;
      margin-top:16px;
      border-radius:22px;
      overflow:hidden;
      background:
        radial-gradient(180px 90px at 92% 10%, rgba(0,223,130,.18), transparent 64%),
        linear-gradient(180deg, rgba(255,255,255,.075), rgba(255,255,255,.035));
      border:1px solid rgba(255,255,255,.09);
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,.08),
        0 16px 30px rgba(0,0,0,.20);
      padding:13px;
    }

    .ab-chart-head{
      display:flex;
      align-items:center;
      justify-content:space-between;
      margin-bottom:8px;
    }

    .ab-chart-head strong{
      color:#ffffff;
      font-size:12px;
      font-weight:900;
    }

    .ab-chart-head span{
      color:var(--ab-green);
      font-size:11px;
      font-weight:900;
    }

    .ab-chart{
      width:100%;
      height:88px;
      display:block;
      overflow:visible;
    }

    .ab-chart-grid{
      stroke:rgba(255,255,255,.08);
      stroke-width:1;
    }

    .ab-chart-area{
      fill:url(#abChartGradient);
      opacity:.56;
    }

    .ab-chart-line{
      fill:none;
      stroke:#00DF82;
      stroke-width:4;
      stroke-linecap:round;
      stroke-linejoin:round;
      filter:drop-shadow(0 0 10px rgba(0,223,130,.30));
      stroke-dasharray:360;
      stroke-dashoffset:360;
      animation:abDrawLine 1.25s ease forwards .25s;
    }

    .ab-chart-dot{
      fill:#00DF82;
      filter:drop-shadow(0 0 8px rgba(0,223,130,.60));
      animation:abDotPulse 1.9s ease-in-out infinite;
    }

    /* METRICS */
    .ab-metrics{
      display:grid;
      grid-template-columns:repeat(3, 1fr);
      gap:9px;
      margin-bottom:13px;
      animation:abFadeUp .48s ease both .05s;
    }

    .ab-metric{
      min-height:92px;
      border-radius:22px;
      padding:12px 10px;
      position:relative;
      overflow:hidden;
      background:
        radial-gradient(circle at 82% 0%, var(--metric-glow), transparent 48%),
        linear-gradient(180deg, rgba(13,35,34,.94), rgba(6,20,19,.96));
      border:1px solid rgba(255,255,255,.085);
      box-shadow:
        0 16px 32px rgba(0,0,0,.22),
        inset 0 1px 0 rgba(255,255,255,.055);
    }

    .ab-metric:nth-child(1){
      --metric-accent:#00DF82;
      --metric-glow:rgba(0,223,130,.22);
    }

    .ab-metric:nth-child(2){
      --metric-accent:#34d5ff;
      --metric-glow:rgba(52,213,255,.20);
    }

    .ab-metric:nth-child(3){
      --metric-accent:#f6c453;
      --metric-glow:rgba(246,196,83,.20);
    }

    .ab-metric span{
      display:block;
      color:rgba(214,255,240,.55);
      font-size:10px;
      line-height:1.2;
      font-weight:750;
    }

    .ab-metric strong{
      display:block;
      margin-top:8px;
      color:#ffffff;
      font-size:19px;
      line-height:1;
      font-weight:950;
      letter-spacing:-.04em;
    }

    .ab-metric em{
      display:block;
      margin-top:7px;
      color:var(--metric-accent);
      font-size:10px;
      line-height:1.2;
      font-weight:900;
      font-style:normal;
    }

    .ab-card{
      position:relative;
      overflow:hidden;
      border-radius:24px;
      background:
        radial-gradient(190px 110px at 88% 8%, rgba(0,223,130,.10), transparent 64%),
        linear-gradient(180deg, rgba(13,35,34,.94), rgba(6,20,19,.96));
      border:1px solid rgba(255,255,255,.085);
      box-shadow:
        0 16px 32px rgba(0,0,0,.25),
        0 0 0 1px rgba(255,255,255,.025) inset;
      padding:16px;
      margin-bottom:11px;
      animation:abFadeUp .48s ease both;
    }

    .ab-card::before{
      content:"";
      position:absolute;
      inset:0;
      background:linear-gradient(145deg, rgba(255,255,255,.045), transparent 32%);
      pointer-events:none;
    }

    .ab-card > *{
      position:relative;
      z-index:1;
    }

    .ab-card-kicker{
      display:inline-flex;
      min-height:27px;
      align-items:center;
      padding:0 10px;
      border-radius:999px;
      color:#8fffd3;
      background:rgba(0,223,130,.08);
      border:1px solid rgba(0,223,130,.14);
      font-size:10px;
      font-weight:900;
      margin-bottom:10px;
    }

    .ab-card h3{
      margin:0 0 9px;
      color:#fff;
      font-size:18px;
      line-height:1.18;
      font-weight:900;
      letter-spacing:-.04em;
    }

    .ab-card p{
      margin:0;
      color:rgba(214,255,240,.66);
      font-size:13px;
      line-height:1.62;
      font-weight:560;
    }

    .ab-card p + p{
      margin-top:10px;
    }

    .ab-highlight{
      color:#ffffff;
      font-weight:850;
    }

    /* VALUE GRID */
    .ab-values{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:9px;
      margin:0 0 11px;
    }

    .ab-value{
      min-height:132px;
      border-radius:22px;
      padding:14px;
      position:relative;
      overflow:hidden;
      background:
        radial-gradient(circle at 80% 0%, var(--value-glow), transparent 44%),
        linear-gradient(180deg, rgba(18,34,35,.94), rgba(8,21,21,.96));
      border:1px solid rgba(255,255,255,.085);
      box-shadow:
        0 14px 28px rgba(0,0,0,.22),
        inset 0 1px 0 rgba(255,255,255,.055);
      animation:abFadeUp .48s ease both;
    }

    .ab-value:nth-child(1){
      --value-accent:#00DF82;
      --value-glow:rgba(0,223,130,.20);
    }

    .ab-value:nth-child(2){
      --value-accent:#34d5ff;
      --value-glow:rgba(52,213,255,.20);
    }

    .ab-value:nth-child(3){
      --value-accent:#f6c453;
      --value-glow:rgba(246,196,83,.20);
    }

    .ab-value:nth-child(4){
      --value-accent:#a78bfa;
      --value-glow:rgba(167,139,250,.20);
    }

    .ab-value-icon{
      width:40px;
      height:40px;
      border-radius:16px;
      display:grid;
      place-items:center;
      color:var(--value-accent);
      background:rgba(255,255,255,.055);
      border:1px solid rgba(255,255,255,.075);
      margin-bottom:12px;
      box-shadow:0 10px 20px rgba(0,0,0,.16);
    }

    .ab-value-icon svg{
      width:20px;
      height:20px;
    }

    .ab-value strong{
      display:block;
      color:#fff;
      font-size:13.5px;
      line-height:1.15;
      font-weight:900;
      margin-bottom:7px;
      letter-spacing:-.02em;
    }

    .ab-value span{
      display:block;
      color:rgba(214,255,240,.56);
      font-size:11px;
      line-height:1.48;
      font-weight:560;
    }

    /* TIMELINE */
    .ab-timeline{
      display:grid;
      gap:10px;
      margin-top:13px;
    }

    .ab-step{
      position:relative;
      display:grid;
      grid-template-columns:42px 1fr;
      gap:11px;
      padding:13px;
      border-radius:20px;
      background:rgba(255,255,255,.04);
      border:1px solid rgba(255,255,255,.07);
      overflow:hidden;
    }

    .ab-step::before{
      content:"";
      position:absolute;
      left:33px;
      top:54px;
      bottom:-16px;
      width:1px;
      background:linear-gradient(180deg, rgba(0,223,130,.45), transparent);
    }

    .ab-step:last-child::before{
      display:none;
    }

    .ab-step-no{
      width:42px;
      height:42px;
      border-radius:16px;
      display:grid;
      place-items:center;
      color:#06110e;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.55), transparent 34%),
        linear-gradient(135deg, #00DF82, #72ffab);
      box-shadow:
        0 14px 24px rgba(0,223,130,.16),
        inset 0 1px 0 rgba(255,255,255,.28);
      font-size:13px;
      font-weight:950;
      position:relative;
      z-index:1;
    }

    .ab-step-text strong{
      display:block;
      color:#fff;
      font-size:13px;
      font-weight:900;
      margin-bottom:5px;
    }

    .ab-step-text span{
      display:block;
      color:rgba(214,255,240,.58);
      font-size:11.7px;
      line-height:1.48;
      font-weight:560;
    }

    /* TRUST STRIP */
    .ab-trust{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:9px;
      margin-bottom:11px;
    }

    .ab-trust-item{
      border-radius:22px;
      padding:14px;
      background:
        radial-gradient(circle at 100% 0%, rgba(0,223,130,.13), transparent 45%),
        linear-gradient(180deg, rgba(13,35,34,.94), rgba(6,20,19,.96));
      border:1px solid rgba(255,255,255,.085);
      box-shadow:0 14px 28px rgba(0,0,0,.20);
    }

    .ab-trust-item span{
      display:block;
      color:rgba(214,255,240,.54);
      font-size:10.5px;
      font-weight:750;
      margin-bottom:7px;
    }

    .ab-trust-item strong{
      display:block;
      color:#fff;
      font-size:13px;
      line-height:1.25;
      font-weight:900;
      letter-spacing:-.02em;
    }

    .ab-cta{
      display:grid;
      grid-template-columns:1fr;
      gap:9px;
      margin-top:13px;
    }

    .ab-btn{
      min-height:50px;
      border-radius:999px;
      display:flex;
      align-items:center;
      justify-content:center;
      gap:8px;
      text-decoration:none;
      font-size:13.5px;
      font-weight:950;
      color:#06110e;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.55), transparent 34%),
        linear-gradient(135deg, #00DF82, #72ffab);
      box-shadow:
        0 16px 32px rgba(0,223,130,.22),
        inset 0 1px 0 rgba(255,255,255,.30);
      transition:.18s ease;
    }

    .ab-btn:hover{
      transform:translateY(-1px);
      filter:brightness(1.04);
    }

    .ab-btn svg{
      width:18px;
      height:18px;
    }

    .ab-btn.is-muted{
      color:rgba(214,255,240,.78);
      background:rgba(255,255,255,.06);
      border:1px solid rgba(255,255,255,.09);
      box-shadow:none;
    }

    @keyframes abFadeUp{
      from{
        opacity:0;
        transform:translateY(14px);
      }
      to{
        opacity:1;
        transform:translateY(0);
      }
    }

    @keyframes abFadeDown{
      from{
        opacity:0;
        transform:translateY(-8px);
      }
      to{
        opacity:1;
        transform:translateY(0);
      }
    }

    @keyframes abPulseDot{
      0%,100%{
        transform:scale(.92);
        opacity:.72;
      }
      50%{
        transform:scale(1.18);
        opacity:1;
      }
    }

    @keyframes abOrbFloat1{
      0%,100%{ transform:translate3d(0,0,0) scale(1); }
      50%{ transform:translate3d(-10px,10px,0) scale(1.08); }
    }

    @keyframes abOrbFloat2{
      0%,100%{ transform:translate3d(0,0,0) scale(1); }
      50%{ transform:translate3d(8px,-9px,0) scale(1.06); }
    }

    @keyframes abOrbFloat3{
      0%,100%{ transform:translate3d(0,0,0) rotate(0deg); }
      50%{ transform:translate3d(-7px,-10px,0) rotate(20deg); }
    }

    @keyframes abDrawLine{
      to{
        stroke-dashoffset:0;
      }
    }

    @keyframes abDotPulse{
      0%,100%{
        transform:scale(.92);
        opacity:.72;
      }
      50%{
        transform:scale(1.22);
        opacity:1;
      }
    }

    @media(max-width:370px){
      .ab-title h1{
        font-size:21px;
      }

      .ab-hero{
        border-radius:26px;
        padding:16px;
      }

      .ab-hero h2{
        font-size:27px;
      }

      .ab-metrics{
        gap:7px;
      }

      .ab-metric{
        min-height:86px;
        padding:11px 8px;
      }

      .ab-metric strong{
        font-size:17px;
      }

      .ab-values{
        grid-template-columns:1fr;
      }

      .ab-trust{
        grid-template-columns:1fr;
      }
    }

    @media (prefers-reduced-motion: reduce){
      *,
      *::before,
      *::after{
        animation:none !important;
        transition:none !important;
        scroll-behavior:auto !important;
      }
    }
  </style>
</head>

<body>
  <main class="ab-page">
    <div class="ab-phone">

      <header class="ab-header">
        <a href="{{ url('/akun') }}" class="ab-back" aria-label="Kembali ke akun">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M15 18 9 12l6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>

        <div class="ab-title">
          <span>Informasi aplikasi dan layanan</span>
          <h1>Tentang Rubik</h1>
        </div>
      </header>

      <section class="ab-hero">
        <span class="ab-orb one"></span>
        <span class="ab-orb two"></span>
        <span class="ab-orb three"></span>

        <div class="ab-hero-content">
          <div class="ab-hero-badge">Rubik Company</div>

          <h2>
            Ekosistem investasi digital yang <span>modern</span>, ringkas, dan mudah dipantau.
          </h2>

          <p>
            Rubik membantu pengguna mengelola saldo, investasi, riwayat transaksi, dan penarikan dana melalui satu dashboard yang rapi.
          </p>
        </div>

        <div class="ab-hero-visual">
          <div class="ab-chart-head">
            <strong>Perkembangan Platform</strong>
            <span>Live Growth</span>
          </div>

          <svg class="ab-chart" viewBox="0 0 360 100" preserveAspectRatio="none">
            <defs>
              <linearGradient id="abChartGradient" x1="0" x2="0" y1="0" y2="1">
                <stop offset="0%" stop-color="#00DF82" stop-opacity=".55"/>
                <stop offset="100%" stop-color="#00DF82" stop-opacity="0"/>
              </linearGradient>
            </defs>

            <path class="ab-chart-grid" d="M0 20H360M0 50H360M0 80H360"/>
            <path class="ab-chart-area" d="M0 76 C38 72 50 46 82 54 C118 64 126 30 162 35 C198 40 205 18 238 25 C286 34 300 12 360 16 L360 100 L0 100 Z"/>
            <path class="ab-chart-line" d="M0 76 C38 72 50 46 82 54 C118 64 126 30 162 35 C198 40 205 18 238 25 C286 34 300 12 360 16"/>
            <circle class="ab-chart-dot" cx="360" cy="16" r="5"/>
          </svg>
        </div>
      </section>

      <section class="ab-metrics">
        <div class="ab-metric">
          <span>Fokus</span>
          <strong>1 App</strong>
          <em>Semua aktivitas</em>
        </div>

        <div class="ab-metric">
          <span>Layanan</span>
          <strong>24/7</strong>
          <em>Monitoring sistem</em>
        </div>

        <div class="ab-metric">
          <span>Flow</span>
          <strong>Auto</strong>
          <em>Deposit & WD</em>
        </div>
      </section>

      <section class="ab-card">
        <div class="ab-card-kicker">Tentang Kami</div>
        <h3>Rubik dibuat untuk pengalaman investasi yang lebih simpel.</h3>

        <p>
          <span class="ab-highlight">Rubik Company</span> adalah platform layanan digital yang dirancang untuk membantu pengguna menjalankan aktivitas investasi dengan alur yang lebih praktis, visual yang jelas, dan informasi yang mudah dipahami.
        </p>

        <p>
          Melalui satu akun, pengguna dapat melakukan deposit, memilih produk, memantau saldo, melihat riwayat transaksi, hingga mengajukan penarikan dana ke rekening atau e-wallet.
        </p>
      </section>

      <section class="ab-values">
        <div class="ab-value">
          <div class="ab-value-icon">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
            </svg>
          </div>
          <strong>Keamanan Akun</strong>
          <span>Data akun dan aktivitas pengguna disusun agar lebih mudah diawasi dan dipantau.</span>
        </div>

        <div class="ab-value">
          <div class="ab-value-icon">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M4 19V5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              <path d="M8 17V9" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              <path d="M12 17V7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              <path d="M16 17v-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              <path d="M20 17V4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
            </svg>
          </div>
          <strong>Transparansi</strong>
          <span>Saldo, investasi, deposit, dan withdraw ditampilkan dengan status yang jelas.</span>
        </div>

        <div class="ab-value">
          <div class="ab-value-icon">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M21 15a4 4 0 0 1-4 4H7l-4 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
          </div>
          <strong>Dukungan CS</strong>
          <span>Pengguna dapat menghubungi kanal resmi Rubik untuk bantuan dan informasi layanan.</span>
        </div>

        <div class="ab-value">
          <div class="ab-value-icon">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 3v18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              <path d="M5 10l7-7 7 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M5 21h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
            </svg>
          </div>
          <strong>Terus Berkembang</strong>
          <span>Rubik terus menyiapkan fitur baru untuk meningkatkan kenyamanan pengguna.</span>
        </div>
      </section>

      <section class="ab-card">
        <div class="ab-card-kicker">Alur Layanan</div>
        <h3>Semua proses dibuat ringkas dari awal sampai akhir.</h3>

        <div class="ab-timeline">
          <div class="ab-step">
            <div class="ab-step-no">01</div>
            <div class="ab-step-text">
              <strong>Deposit Saldo</strong>
              <span>Pengguna menambahkan saldo utama untuk memulai aktivitas di platform Rubik.</span>
            </div>
          </div>

          <div class="ab-step">
            <div class="ab-step-no">02</div>
            <div class="ab-step-text">
              <strong>Pilih Produk</strong>
              <span>Produk investasi ditampilkan dalam dashboard dengan informasi harga dan potensi profit.</span>
            </div>
          </div>

          <div class="ab-step">
            <div class="ab-step-no">03</div>
            <div class="ab-step-text">
              <strong>Pantau Aktivitas</strong>
              <span>Saldo, riwayat deposit, investasi aktif, dan penarikan dapat dipantau melalui menu akun.</span>
            </div>
          </div>

          <div class="ab-step">
            <div class="ab-step-no">04</div>
            <div class="ab-step-text">
              <strong>Withdraw Dana</strong>
              <span>Pengguna dapat mengajukan penarikan ke rekening atau e-wallet yang sudah ditambahkan.</span>
            </div>
          </div>
        </div>
      </section>

      <section class="ab-trust">
        <div class="ab-trust-item">
          <span>Dashboard</span>
          <strong>Mobile-first dan mudah digunakan</strong>
        </div>

        <div class="ab-trust-item">
          <span>Transaksi</span>
          <strong>Status deposit & withdraw lebih jelas</strong>
        </div>
      </section>

      <section class="ab-card">
        <div class="ab-card-kicker">Komitmen Kami</div>
        <h3>Membangun layanan digital yang rapi, informatif, dan nyaman.</h3>

        <p>
          Rubik berkomitmen menghadirkan sistem yang mudah digunakan, tampilan yang modern, serta informasi yang tersusun jelas agar pengguna dapat mengambil keputusan dengan lebih percaya diri.
        </p>

        <div class="ab-cta">
          <a href="https://t.me/rubikcompany" target="_blank" rel="noopener" class="ab-btn">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M22 2 11 13" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M22 2 15 22 11 13 2 9 22 2" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Hubungi Layanan CS
          </a>

          <a href="{{ url('/akun') }}" class="ab-btn is-muted">
            Kembali ke Akun
          </a>
        </div>
      </section>

    </div>
  </main>

  @include('partials.bottom-nav')
</body>
</html>