<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Rincian Saldo | Rubik Company</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --sd-bg:#030F0F;
      --sd-bg2:#061817;
      --sd-card:#081a18;
      --sd-text:#f7fffb;
      --sd-soft:#dffcf1;
      --sd-muted:#9bb9ad;
      --sd-muted2:#6f9084;
      --sd-border:rgba(255,255,255,.09);

      --sd-green:#00DF82;
      --sd-green2:#72ffab;
      --sd-blue:#34d5ff;
      --sd-amber:#f6c453;
      --sd-orange:#fb923c;
      --sd-red:#fb7185;

      --sd-shadow:0 28px 70px rgba(0,0,0,.46);
      --sd-shadow-soft:0 16px 34px rgba(0,0,0,.24);
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
      color:var(--sd-text);
      background:
        radial-gradient(760px 420px at 14% -2%, rgba(0,223,130,.18), transparent 58%),
        radial-gradient(620px 360px at 90% 10%, rgba(90,140,255,.14), transparent 62%),
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
      mask-image:linear-gradient(180deg, rgba(0,0,0,.65), transparent 76%);
      -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.65), transparent 76%);
      opacity:.46;
      z-index:0;
    }

    a{
      color:inherit;
      text-decoration:none;
    }

    button{
      font-family:inherit;
    }

    .sd-page{
      width:100%;
      min-height:100vh;
      display:flex;
      justify-content:center;
      padding:14px 10px 0;
      position:relative;
      z-index:1;
    }

    .sd-phone{
      width:100%;
      max-width:430px;
      min-height:100vh;
      position:relative;
      padding:8px 4px 96px;
    }

    /* =========================
       HEADER
    ========================= */
    .sd-topbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
      padding:0 2px;
    }

    .sd-brand{
      display:flex;
      align-items:center;
      gap:10px;
      min-width:0;
    }

    .sd-logo-card{
      width:48px;
      height:48px;
      border-radius:14px;
      background:
        radial-gradient(circle at 30% 10%, rgba(255,255,255,.98), rgba(224,255,242,.90));
      border:1px solid rgba(0,223,130,.24);
      box-shadow:
        0 12px 28px rgba(0,0,0,.30),
        0 0 0 1px rgba(255,255,255,.08) inset,
        0 0 28px rgba(0,223,130,.12);
      display:grid;
      place-items:center;
      flex:0 0 auto;
      overflow:hidden;
    }

    .sd-logo-card img{
      width:42px;
      height:42px;
      object-fit:contain;
      display:block;
    }

    .sd-title{
      min-width:0;
    }

    .sd-title span{
      display:block;
      margin-bottom:4px;
      color:rgba(214,255,240,.58);
      font-size:11px;
      line-height:1;
      font-weight:600;
      letter-spacing:.02em;
    }

    .sd-title h1{
      margin:0;
      font-size:23px;
      line-height:1;
      font-weight:850;
      letter-spacing:-.045em;
      color:#ffffff;
      white-space:nowrap;
    }

    .sd-header-actions{
      display:flex;
      align-items:center;
      gap:9px;
      flex:0 0 auto;
    }

    .sd-header-btn{
      width:42px;
      height:42px;
      border-radius:999px;
      border:1px solid rgba(255,255,255,.10);
      background:
        radial-gradient(circle at 32% 18%, rgba(255,255,255,.18), transparent 34%),
        linear-gradient(180deg, rgba(10,42,35,.96), rgba(4,18,16,.96));
      color:#ffffff;
      display:grid;
      place-items:center;
      box-shadow:
        0 13px 28px rgba(0,0,0,.34),
        0 0 0 1px rgba(0,223,130,.06) inset;
      position:relative;
      transition:.18s ease;
    }

    .sd-header-btn:hover{
      transform:translateY(-1px);
      border-color:rgba(0,223,130,.24);
    }

    .sd-header-btn svg{
      width:20px;
      height:20px;
    }

    /* =========================
       HERO SUMMARY
    ========================= */
    .sd-hero{
      position:relative;
      overflow:hidden;
      border-radius:24px;
      background:
        radial-gradient(320px 180px at 95% 4%, rgba(90,140,255,.20), transparent 62%),
        radial-gradient(260px 170px at 8% 0%, rgba(0,223,130,.26), transparent 62%),
        radial-gradient(240px 150px at 90% 110%, rgba(246,196,83,.16), transparent 68%),
        linear-gradient(135deg, rgba(236,255,248,.96), rgba(199,255,232,.92) 48%, rgba(185,236,255,.88));
      border:1px solid rgba(255,255,255,.55);
      box-shadow:
        0 20px 44px rgba(0,0,0,.22),
        0 0 0 1px rgba(0,223,130,.14) inset,
        inset 0 1px 0 rgba(255,255,255,.72);
      padding:16px;
      animation:sdFadeUp .42s ease both;
    }

    .sd-hero::before{
      content:"";
      position:absolute;
      inset:0;
      background:
        linear-gradient(145deg, rgba(255,255,255,.48) 0%, rgba(255,255,255,.18) 27%, transparent 28%),
        linear-gradient(180deg, rgba(255,255,255,.22), rgba(255,255,255,0));
      pointer-events:none;
    }

    .sd-hero-inner{
      position:relative;
      z-index:1;
    }

    .sd-hero-head{
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
    }

    .sd-hero-label{
      margin:0 0 8px;
      color:rgba(3,24,20,.62);
      font-size:12px;
      font-weight:650;
      line-height:1.1;
    }

    .sd-hero-title{
      margin:0;
      color:#031713;
      font-size:28px;
      line-height:1.04;
      letter-spacing:-.055em;
      font-weight:900;
    }

    .sd-hero-sub{
      margin-top:10px;
      display:flex;
      align-items:center;
      gap:6px;
      color:#037e5d;
      font-size:12px;
      font-weight:760;
    }

    .sd-hero-sub span{
      color:rgba(3,24,20,.56);
      font-weight:550;
    }

    .sd-hero-pill{
      flex:0 0 auto;
      min-width:78px;
      height:38px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:6px;
      color:#05221b;
      background:rgba(255,255,255,.45);
      border:1px solid rgba(3,24,20,.10);
      box-shadow:
        0 10px 22px rgba(3,24,20,.10),
        inset 0 1px 0 rgba(255,255,255,.55);
      font-size:12px;
      font-weight:850;
      white-space:nowrap;
    }

    .sd-hero-pill svg{
      width:15px;
      height:15px;
      color:#047857;
    }

    .sd-hero-actions{
      margin-top:18px;
      display:grid;
      grid-template-columns:repeat(3,1fr);
      gap:8px;
    }

    .sd-hero-action{
      min-height:58px;
      border-radius:18px;
      border:1px solid rgba(3,24,20,.08);
      background:rgba(255,255,255,.38);
      color:#05221b;
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      gap:6px;
      font-size:10.5px;
      line-height:1;
      font-weight:750;
      box-shadow:
        0 10px 22px rgba(3,24,20,.08),
        inset 0 1px 0 rgba(255,255,255,.45);
      transition:.18s ease;
    }

    .sd-hero-action:hover{
      transform:translateY(-1px);
      background:rgba(255,255,255,.54);
    }

    .sd-hero-action svg{
      width:19px;
      height:19px;
    }

    .sd-hero-action.is-deposit svg{
      color:#037e5d;
    }

    .sd-hero-action.is-invest svg{
      color:#f97316;
    }

    .sd-hero-action.is-all svg{
      color:#2563eb;
    }

    /* =========================
       FILTER CHIPS
    ========================= */
    .sd-section{
      margin-top:18px;
    }

    .sd-section-head{
      display:flex;
      align-items:flex-end;
      justify-content:space-between;
      gap:12px;
      margin-bottom:12px;
      padding:0 2px;
    }

    .sd-section-title{
      min-width:0;
    }

    .sd-section-title h2{
      margin:0;
      color:#ffffff;
      font-size:17px;
      line-height:1.15;
      letter-spacing:-.03em;
      font-weight:760;
    }

    .sd-section-title p{
      margin:5px 0 0;
      color:rgba(214,255,240,.56);
      font-size:11px;
      font-weight:450;
    }

    .sd-filter{
      width:100%;
      overflow:hidden;
      margin-bottom:12px;
    }

    .sd-chips{
      display:flex;
      gap:7px;
      overflow-x:auto;
      overflow-y:hidden;
      padding:1px 2px 3px;
      scrollbar-width:none;
      -webkit-overflow-scrolling:touch;
    }

    .sd-chips::-webkit-scrollbar{
      display:none;
    }

    .sd-chip{
      flex:0 0 auto;
      min-width:82px;
      height:34px;
      padding:0 12px;
      border-radius:999px;
      border:1px solid rgba(255,255,255,.08);
      background:rgba(255,255,255,.04);
      color:rgba(236,255,248,.58);
      font-size:11px;
      font-weight:650;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:6px;
      box-shadow:0 8px 18px rgba(0,0,0,.12);
      transition:.18s ease;
      white-space:nowrap;
    }

    .sd-chip:hover{
      transform:translateY(-1px);
    }

    .sd-chip.is-active{
      color:#05100d;
      border-color:rgba(255,255,255,.34);
      background:
        radial-gradient(circle at 20% 0%, rgba(255,255,255,.65), transparent 38%),
        linear-gradient(135deg, #00DF82, #70ffb1);
      box-shadow:
        0 14px 28px rgba(0,223,130,.18),
        inset 0 1px 0 rgba(255,255,255,.30);
    }

    /* =========================
       ACTIVITY LIST
    ========================= */
    .sd-list{
      display:flex;
      flex-direction:column;
      gap:9px;
    }

    .sd-row{
      position:relative;
      overflow:hidden;
      border-radius:21px;
      background:
        radial-gradient(170px 94px at 88% 8%, var(--row-glow), transparent 64%),
        linear-gradient(180deg, rgba(13,35,34,.94), rgba(6,20,19,.96));
      border:1px solid rgba(255,255,255,.085);
      box-shadow:
        0 16px 32px rgba(0,0,0,.25),
        0 0 0 1px rgba(255,255,255,.025) inset;
      transition:.18s ease;
      animation:sdFadeUp .42s ease both;
    }

    .sd-row:hover{
      transform:translateY(-1px);
      border-color:rgba(255,255,255,.13);
      box-shadow:
        0 18px 36px rgba(0,0,0,.32),
        0 0 28px var(--row-glow-soft);
    }

    .sd-row.is-deposit{
      --row-accent:#00DF82;
      --row-accent2:#58ffad;
      --row-glow:rgba(0,223,130,.12);
      --row-glow-soft:rgba(0,223,130,.08);
    }

    .sd-row.is-investment{
      --row-accent:#fb923c;
      --row-accent2:#f97316;
      --row-glow:rgba(251,146,60,.13);
      --row-glow-soft:rgba(251,146,60,.08);
    }

    .sd-row-inner{
      min-height:78px;
      display:grid;
      grid-template-columns:44px minmax(0,1fr) auto;
      align-items:center;
      gap:10px;
      padding:12px 10px;
    }

    .sd-icon{
      width:42px;
      height:42px;
      border-radius:16px;
      display:grid;
      place-items:center;
      color:var(--row-accent);
      background:
        radial-gradient(circle at 30% 15%, var(--row-glow-soft), transparent 34%),
        linear-gradient(180deg, rgba(16,34,31,.98), rgba(7,18,16,.98));
      border:1px solid color-mix(in srgb, var(--row-accent) 28%, transparent);
      box-shadow:
        0 12px 24px rgba(0,0,0,.28),
        0 0 24px var(--row-glow-soft),
        inset 0 1px 0 rgba(255,255,255,.10);
      flex:0 0 auto;
    }

    .sd-icon svg{
      width:22px;
      height:22px;
    }

    .sd-row-info{
      min-width:0;
    }

    .sd-row-title{
      margin:0;
      color:#ffffff;
      font-size:13px;
      line-height:1.18;
      letter-spacing:-.02em;
      font-weight:800;
      white-space:normal;
      overflow:hidden;
      display:-webkit-box;
      -webkit-line-clamp:2;
      -webkit-box-orient:vertical;
    }

    .sd-row-meta{
      margin:5px 0 0;
      color:rgba(214,255,240,.52);
      font-size:10.3px;
      font-weight:500;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width:190px;
    }

    .sd-row-right{
      text-align:right;
      min-width:86px;
      display:flex;
      flex-direction:column;
      align-items:flex-end;
      gap:6px;
    }

    .sd-amount{
      color:var(--row-accent);
      font-size:12px;
      line-height:1.1;
      letter-spacing:-.025em;
      font-weight:850;
      white-space:nowrap;
      text-shadow:0 0 16px var(--row-glow-soft);
    }

    .sd-badge{
      min-height:23px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      padding:0 8px;
      border-radius:999px;
      color:#06110e;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.42), transparent 34%),
        linear-gradient(135deg, var(--row-accent), var(--row-accent2));
      font-size:9.5px;
      line-height:1;
      font-weight:850;
      white-space:nowrap;
      box-shadow:
        0 12px 22px var(--row-glow-soft),
        inset 0 1px 0 rgba(255,255,255,.25);
    }

    /* =========================
       EMPTY
    ========================= */
    .sd-empty{
      min-height:360px;
      border-radius:24px;
      padding:22px 14px;
      background:
        radial-gradient(220px 120px at 90% 0%, rgba(0,223,130,.12), transparent 62%),
        radial-gradient(220px 120px at 0% 100%, rgba(52,213,255,.10), transparent 62%),
        rgba(9,37,31,.76);
      border:1px dashed rgba(0,223,130,.22);
      box-shadow:
        0 16px 32px rgba(0,0,0,.25),
        0 0 0 1px rgba(255,255,255,.025) inset;
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      gap:13px;
      text-align:center;
      animation:sdFadeUp .42s ease both;
    }

    .sd-empty-icon{
      width:76px;
      height:76px;
      border-radius:24px;
      display:grid;
      place-items:center;
      color:#06110e;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.52), transparent 34%),
        linear-gradient(135deg, #00DF82, #72ffab);
      box-shadow:
        0 14px 28px rgba(0,223,130,.20),
        inset 0 1px 0 rgba(255,255,255,.32);
    }

    .sd-empty-icon svg{
      width:35px;
      height:35px;
    }

    .sd-empty h3{
      margin:0;
      color:#ffffff;
      font-size:17px;
      line-height:1.15;
      letter-spacing:-.03em;
      font-weight:850;
    }

    .sd-empty p{
      margin:0;
      max-width:320px;
      color:rgba(214,255,240,.62);
      font-size:12px;
      line-height:1.55;
      font-weight:600;
    }

    .sd-empty-grid{
      width:100%;
      display:grid;
      grid-template-columns:repeat(3,1fr);
      gap:8px;
      margin-top:4px;
    }

    .sd-empty-pill{
      min-height:74px;
      border-radius:18px;
      padding:10px 8px;
      text-align:left;
      background:rgba(255,255,255,.045);
      border:1px solid rgba(255,255,255,.075);
      box-shadow:0 10px 18px rgba(0,0,0,.16);
    }

    .sd-empty-pill .k{
      color:rgba(214,255,240,.46);
      font-size:9.5px;
      line-height:1.1;
      font-weight:800;
      text-transform:uppercase;
      letter-spacing:.08em;
      margin-bottom:6px;
    }

    .sd-empty-pill .v{
      color:#ffffff;
      font-size:10.5px;
      line-height:1.25;
      font-weight:700;
    }

    .sd-empty-actions{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:9px;
      width:100%;
      margin-top:4px;
    }

    .sd-btn{
      min-height:42px;
      border-radius:15px;
      border:1px solid rgba(255,255,255,.08);
      background:rgba(255,255,255,.055);
      color:#ffffff;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:7px;
      font-size:12px;
      font-weight:850;
      box-shadow:
        0 10px 18px rgba(0,0,0,.16),
        inset 0 1px 0 rgba(255,255,255,.05);
      transition:.18s ease;
    }

    .sd-btn:hover{
      transform:translateY(-1px);
    }

    .sd-btn.is-primary{
      color:#06110e;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.50), transparent 34%),
        linear-gradient(135deg, #00DF82, #72ffab);
      box-shadow:
        0 12px 24px rgba(0,223,130,.16),
        inset 0 1px 0 rgba(255,255,255,.30);
    }

    .sd-btn svg{
      width:16px;
      height:16px;
    }

    /* =========================
       PAGER
    ========================= */
    .sd-pager{
      margin-top:12px;
      border-radius:18px;
      border:1px solid rgba(255,255,255,.075);
      background:rgba(2,10,10,.26);
      padding:10px 12px;
      overflow:auto;
      color:#ffffff;
      box-shadow:0 10px 22px rgba(0,0,0,.16);
    }

    .sd-pager *{
      color:rgba(214,255,240,.78);
      font-size:12px;
    }

    .sd-pager a{
      color:#8fffd3 !important;
      font-weight:850;
      text-decoration:none;
    }

    .sd-pager nav{
      display:block;
      width:100%;
    }

    .rb-bottom-spacer{
      height:86px !important;
    }

    .rb-bottom-nav{
      background:
        radial-gradient(240px 110px at 50% 0%, rgba(0,223,130,.10), transparent 65%),
        linear-gradient(180deg, rgba(8,34,29,.96), rgba(3,15,15,.98)) !important;
      border:1px solid rgba(255,255,255,.10) !important;
      border-bottom:0 !important;
      box-shadow:
        0 -18px 42px rgba(0,0,0,.38),
        0 0 0 1px rgba(0,223,130,.06) inset !important;
      backdrop-filter:blur(20px) !important;
      -webkit-backdrop-filter:blur(20px) !important;
    }

    .rb-bottom-nav__item{
      color:rgba(214,255,240,.48) !important;
    }

    .rb-bottom-nav__item:hover{
      color:rgba(214,255,240,.78) !important;
    }

    .rb-bottom-nav__item.is-active{
      color:#00DF82 !important;
      text-shadow:0 0 18px rgba(0,223,130,.25);
    }

    .rb-bottom-nav__item.is-active .rb-bottom-nav__icon{
      filter:drop-shadow(0 0 12px rgba(0,223,130,.30));
    }

    @keyframes sdFadeUp{
      from{
        opacity:0;
        transform:translateY(10px);
      }
      to{
        opacity:1;
        transform:translateY(0);
      }
    }

    @media (max-width:370px){
      .sd-logo-card{
        width:44px;
        height:44px;
      }

      .sd-logo-card img{
        width:38px;
        height:38px;
      }

      .sd-title h1{
        font-size:21px;
      }

      .sd-hero{
        padding:15px;
      }

      .sd-hero-title{
        font-size:25px;
      }

      .sd-hero-pill{
        min-width:70px;
        height:36px;
        font-size:11px;
      }

      .sd-row-inner{
        grid-template-columns:40px minmax(0,1fr);
        align-items:start;
      }

      .sd-row-right{
        grid-column:2 / 3;
        flex-direction:row;
        justify-content:space-between;
        align-items:center;
        width:100%;
        text-align:left;
        min-width:0;
      }

      .sd-row-meta{
        max-width:240px;
      }

      .sd-empty{
        min-height:400px;
      }

      .sd-empty-grid{
        grid-template-columns:1fr;
      }

      .sd-empty-actions{
        grid-template-columns:1fr;
      }
    }
  </style>
</head>

<body>
  <main class="sd-page">
    <div class="sd-phone">

      {{-- HEADER --}}
      <header class="sd-topbar">
        <div class="sd-brand">
          <div class="sd-logo-card">
            <img src="{{ asset('logo.png') }}" alt="Rubik Company">
          </div>

          <div class="sd-title">
            <span>Aktivitas saldo</span>
            <h1>Rincian Saldo</h1>
          </div>
        </div>

        <div class="sd-header-actions">
          <a href="{{ url('/dashboard') }}" class="sd-header-btn" aria-label="Kembali ke Dashboard">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M15 18 9 12l6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </a>

          <a href="{{ url('/akun') }}" class="sd-header-btn" aria-label="Akun">
            <svg viewBox="0 0 24 24" fill="none">
              <circle cx="12" cy="8" r="4" fill="currentColor"/>
              <path d="M4 21a8 8 0 0 1 16 0" fill="currentColor"/>
            </svg>
          </a>
        </div>
      </header>

      {{-- HERO --}}
      <section class="sd-hero">
        <div class="sd-hero-inner">
          <div class="sd-hero-head">
            <div>
              <p class="sd-hero-label">Riwayat Transaksi Saldo</p>
              <h2 class="sd-hero-title">Aktivitas</h2>

              <div class="sd-hero-sub">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                  <path d="M4 15l5-5 4 4 7-8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M15 6h5v5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Deposit
                <span>Investasi</span>
              </div>
            </div>

            <div class="sd-hero-pill">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
              </svg>
              Aman
            </div>
          </div>

          <div class="sd-hero-actions">
            <a href="{{ route('saldo.rincian', ['type' => 'all']) }}" class="sd-hero-action is-all">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M4 6h16" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                <path d="M4 12h16" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                <path d="M4 18h16" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              </svg>
              Semua
            </a>

            <a href="{{ route('saldo.rincian', ['type' => 'deposit']) }}" class="sd-hero-action is-deposit">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M12 5v14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                <path d="M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              </svg>
              Deposit
            </a>

            <a href="{{ route('saldo.rincian', ['type' => 'investment']) }}" class="sd-hero-action is-invest">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                <path d="M3 6h18" stroke="currentColor" stroke-width="2"/>
                <path d="M16 10a4 4 0 0 1-8 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
              Investasi
            </a>
          </div>
        </div>
      </section>

      {{-- LIST --}}
      <section class="sd-section" aria-label="List Aktivitas">
        <div class="sd-section-head">
          <div class="sd-section-title">
            <h2>Daftar Aktivitas</h2>
            <p>Uang masuk dan keluar tercatat otomatis</p>
          </div>
        </div>

        <div class="sd-filter">
          <div class="sd-chips">
            <a class="sd-chip {{ $type === 'all' ? 'is-active' : '' }}" href="{{ route('saldo.rincian', ['type' => 'all']) }}">Semua</a>
            <a class="sd-chip {{ $type === 'deposit' ? 'is-active' : '' }}" href="{{ route('saldo.rincian', ['type' => 'deposit']) }}">Deposit</a>
            <a class="sd-chip {{ $type === 'investment' ? 'is-active' : '' }}" href="{{ route('saldo.rincian', ['type' => 'investment']) }}">Investasi</a>
          </div>
        </div>

        <div class="sd-list">
          @forelse($activities as $a)
            @php
              $isDeposit = $a->activity_type === 'deposit';
              $title = $isDeposit ? 'Isi ulang saldo' : 'Membeli investasi';
              $date = \Carbon\Carbon::parse($a->happened_at)->format('d M Y • H:i');

              $sub = $isDeposit
                ? ($a->method ? "Metode: {$a->method} • Ref: {$a->ref}" : "Ref: {$a->ref}")
                : (($a->product_name ? "Produk: {$a->product_name}" : "Produk investasi") . " • ID: {$a->ref}");

              $badge = $isDeposit ? ($a->status ?? 'PAID') : ($a->status ?? 'ACTIVE');
            @endphp

            <article class="sd-row {{ $isDeposit ? 'is-deposit' : 'is-investment' }}">
              <div class="sd-row-inner">
                <div class="sd-icon" aria-hidden="true">
                  @if($isDeposit)
                    <svg viewBox="0 0 24 24" fill="none">
                      <path d="M12 5v14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                      <path d="M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                    </svg>
                  @else
                    <svg viewBox="0 0 24 24" fill="none">
                      <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                      <path d="M3 6h18" stroke="currentColor" stroke-width="2"/>
                      <path d="M16 10a4 4 0 0 1-8 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                  @endif
                </div>

                <div class="sd-row-info">
                  <h3 class="sd-row-title">{{ $title }}</h3>
                  <p class="sd-row-meta">{{ $date }} • {{ $sub }}</p>
                </div>

                <div class="sd-row-right">
                  <div class="sd-amount">
                    {{ $isDeposit ? '+' : '-' }} Rp {{ number_format((int)$a->amount, 0, ',', '.') }}
                  </div>

                  <div class="sd-badge">{{ strtoupper($badge) }}</div>
                </div>
              </div>
            </article>
          @empty
            <div class="sd-empty">
              <div class="sd-empty-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M12 2v20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  <path d="M17 7H7a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                  <path d="M16 12h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                </svg>
              </div>

              <h3>Belum ada aktivitas saldo</h3>

              <p>
                Aktivitas akan muncul otomatis saat kamu melakukan deposit atau membeli produk investasi.
              </p>

              <div class="sd-empty-grid">
                <div class="sd-empty-pill">
                  <div class="k">Deposit</div>
                  <div class="v">Uang masuk tercatat sebagai isi ulang.</div>
                </div>

                <div class="sd-empty-pill">
                  <div class="k">Investasi</div>
                  <div class="v">Uang keluar tercatat sebagai pembelian.</div>
                </div>

                <div class="sd-empty-pill">
                  <div class="k">Status</div>
                  <div class="v">PAID / ACTIVE tampil otomatis.</div>
                </div>
              </div>

              <div class="sd-empty-actions">
                <a class="sd-btn is-primary" href="{{ url('/deposit') }}">
                  <svg viewBox="0 0 24 24" fill="none">
                    <path d="M12 5v14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                    <path d="M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                  </svg>
                  Isi Saldo
                </a>

                <a class="sd-btn" href="{{ url('/dashboard') }}">
                  <svg viewBox="0 0 24 24" fill="none">
                    <path d="M15 18 9 12l6-6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  Kembali
                </a>
              </div>
            </div>
          @endforelse
        </div>

        @if(method_exists($activities, 'links'))
          <div class="sd-pager">
            {{ $activities->links() }}
          </div>
        @endif
      </section>

      <div class="rb-bottom-spacer"></div>
    </div>
  </main>

  @include('partials.bottom-nav')
</body>
</html>