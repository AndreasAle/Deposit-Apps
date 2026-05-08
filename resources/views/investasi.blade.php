{{-- Rubik Premium — Investasi Saya (NO @extends) --}}
 @include('partials.anti-inspect')
@php
  $user = auth()->user();

  $totalActive = 0;
  $totalFinished = 0;
  $totalModal = 0;
  $totalProfit = 0;
  $totalDailyProfit = 0;

  foreach(($investments ?? []) as $inv){
    if(($inv->status ?? '') === 'active'){
      $totalActive++;
    }else{
      $totalFinished++;
    }

    $totalModal += (int) ($inv->price ?? 0);
    $totalProfit += (int) ($inv->total_profit ?? 0);
    $totalDailyProfit += (int) ($inv->daily_profit ?? 0);
  }
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Investasi Saya | Rubik Company</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --iv-bg:#030F0F;
      --iv-bg2:#061817;
      --iv-surface:#071f1b;
      --iv-surface2:#0b2723;
      --iv-card:#081a18;
      --iv-text:#f7fffb;
      --iv-soft:#dffcf1;
      --iv-muted:#9bb9ad;
      --iv-muted2:#6f9084;
      --iv-border:rgba(255,255,255,.09);

      --iv-green:#00DF82;
      --iv-emerald:#13c58f;
      --iv-cyan:#34d5ff;
      --iv-blue:#5a8cff;
      --iv-violet:#a78bfa;
      --iv-amber:#f6c453;
      --iv-orange:#fb923c;
      --iv-rose:#fb7185;

      --iv-shadow:0 28px 70px rgba(0,0,0,.46);
      --iv-shadow-soft:0 16px 34px rgba(0,0,0,.24);
      --iv-radius:24px;
      --iv-radius-sm:18px;
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
      color:var(--iv-text);
      background:
        radial-gradient(760px 420px at 14% -2%, rgba(0,223,130,.18), transparent 58%),
        radial-gradient(620px 360px at 90% 10%, rgba(90,140,255,.18), transparent 62%),
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

    .iv-page{
      width:100%;
      min-height:100vh;
      display:flex;
      justify-content:center;
      padding:14px 10px 0;
      position:relative;
      z-index:1;
    }

    .iv-phone{
      width:100%;
      max-width:430px;
      min-height:100vh;
      position:relative;
      padding:8px 4px 96px;
    }

    /* =========================
       HEADER
    ========================= */
    .iv-topbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
      padding:0 2px;
    }

    .iv-brand{
      display:flex;
      align-items:center;
      gap:10px;
      min-width:0;
    }

    .iv-logo-card{
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

    .iv-logo-card img{
      width:42px;
      height:42px;
      object-fit:contain;
      display:block;
    }

    .iv-title{
      min-width:0;
    }

    .iv-title span{
      display:block;
      margin-bottom:4px;
      color:rgba(214,255,240,.58);
      font-size:11px;
      line-height:1;
      font-weight:600;
      letter-spacing:.02em;
    }

    .iv-title h1{
      margin:0;
      font-size:23px;
      line-height:1;
      font-weight:850;
      letter-spacing:-.045em;
      color:#ffffff;
      white-space:nowrap;
    }

    .iv-header-actions{
      display:flex;
      align-items:center;
      gap:9px;
      flex:0 0 auto;
    }

    .iv-header-btn{
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
    }

    .iv-header-btn svg{
      width:20px;
      height:20px;
    }

    /* =========================
       HERO BRIGHT GLASS
    ========================= */
    .iv-hero{
      position:relative;
      overflow:hidden;
      border-radius:24px;
      background:
        radial-gradient(320px 180px at 95% 4%, rgba(90,140,255,.24), transparent 62%),
        radial-gradient(260px 170px at 8% 0%, rgba(0,223,130,.28), transparent 62%),
        radial-gradient(240px 150px at 90% 110%, rgba(246,196,83,.20), transparent 68%),
        linear-gradient(135deg, rgba(236,255,248,.96), rgba(199,255,232,.92) 48%, rgba(185,236,255,.88));
      border:1px solid rgba(255,255,255,.55);
      box-shadow:
        0 20px 44px rgba(0,0,0,.22),
        0 0 0 1px rgba(0,223,130,.14) inset,
        inset 0 1px 0 rgba(255,255,255,.72);
      padding:16px;
    }

    .iv-hero::before{
      content:"";
      position:absolute;
      inset:0;
      background:
        linear-gradient(145deg, rgba(255,255,255,.48) 0%, rgba(255,255,255,.18) 27%, transparent 28%),
        linear-gradient(180deg, rgba(255,255,255,.22), rgba(255,255,255,0));
      pointer-events:none;
    }

    .iv-hero-inner{
      position:relative;
      z-index:1;
    }

    .iv-hero-head{
      display:flex;
      justify-content:space-between;
      gap:14px;
      align-items:flex-start;
    }

    .iv-hero-label{
      margin:0 0 8px;
      color:rgba(3,24,20,.62);
      font-size:12px;
      font-weight:650;
      line-height:1.1;
    }

    .iv-hero-balance{
      margin:0;
      color:#031713;
      font-size:26px;
      line-height:1.05;
      letter-spacing:-.055em;
      font-weight:850;
      text-shadow:none;
    }

    .iv-hero-profit{
      margin-top:10px;
      display:flex;
      align-items:center;
      gap:6px;
      color:#037e5d;
      font-size:12px;
      font-weight:760;
    }

    .iv-hero-profit span{
      color:rgba(3,24,20,.56);
      font-weight:550;
    }

    .iv-status-pill{
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
      font-weight:820;
    }

    .iv-status-pill svg{
      width:15px;
      height:15px;
      color:#047857;
    }

    .iv-quick-actions{
      margin-top:18px;
      display:grid;
      grid-template-columns:repeat(3,1fr);
      gap:8px;
    }

    .iv-quick-action{
      min-height:58px;
      border-radius:18px;
      border:1px solid rgba(3,24,20,.08);
      background:rgba(255,255,255,.38);
      color:#05221b;
      box-shadow:
        0 10px 22px rgba(3,24,20,.08),
        inset 0 1px 0 rgba(255,255,255,.45);
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      gap:6px;
      font-size:10.5px;
      line-height:1;
      font-weight:700;
      transition:.18s ease;
    }

    .iv-quick-action:hover{
      transform:translateY(-1px);
      background:rgba(255,255,255,.54);
    }

    .iv-quick-action svg{
      width:19px;
      height:19px;
    }

    .iv-quick-action.is-market svg{
      color:#047857;
    }

    .iv-quick-action.is-deposit svg{
      color:#2563eb;
    }

    .iv-quick-action.is-withdraw svg{
      color:#d97706;
    }

    /* =========================
       SUMMARY
    ========================= */
    .iv-summary{
      margin-top:12px;
      display:grid;
      grid-template-columns:repeat(3,1fr);
      gap:9px;
    }

    .iv-summary-card{
      min-height:76px;
      border-radius:18px;
      padding:11px 10px;
      background:
        radial-gradient(circle at 80% 0%, var(--sum-glow), transparent 44%),
        linear-gradient(180deg, rgba(18,34,35,.94), rgba(8,21,21,.96));
      border:1px solid rgba(255,255,255,.085);
      box-shadow:
        0 14px 28px rgba(0,0,0,.24),
        inset 0 1px 0 rgba(255,255,255,.055);
    }

    .iv-summary-card:nth-child(1){
      --sum-glow:rgba(0,223,130,.20);
      --sum-accent:#00DF82;
    }

    .iv-summary-card:nth-child(2){
      --sum-glow:rgba(90,140,255,.22);
      --sum-accent:#5a8cff;
    }

    .iv-summary-card:nth-child(3){
      --sum-glow:rgba(246,196,83,.20);
      --sum-accent:#f6c453;
    }

    .iv-summary-label{
      margin:0;
      color:rgba(214,255,240,.52);
      font-size:10px;
      line-height:1.2;
      font-weight:550;
    }

    .iv-summary-value{
      margin:8px 0 0;
      color:#ffffff;
      font-size:13px;
      line-height:1.15;
      letter-spacing:-.02em;
      font-weight:800;
    }

    .iv-summary-value span{
      color:var(--sum-accent);
    }

    /* =========================
       SECTION
    ========================= */
    .iv-section{
      margin-top:18px;
    }

    .iv-section-head{
      display:flex;
      align-items:flex-end;
      justify-content:space-between;
      gap:12px;
      margin-bottom:12px;
      padding:0 2px;
    }

    .iv-section-title h2{
      margin:0;
      color:#ffffff;
      font-size:17px;
      line-height:1.15;
      letter-spacing:-.03em;
      font-weight:760;
    }

    .iv-section-title p{
      margin:5px 0 0;
      color:rgba(214,255,240,.56);
      font-size:11px;
      font-weight:450;
    }

    .iv-see-all{
      color:#8fffd3;
      font-size:11.5px;
      font-weight:750;
      white-space:nowrap;
    }

    /* =========================
       INVESTMENT CARDS
    ========================= */
    .iv-list{
      display:flex;
      flex-direction:column;
      gap:10px;
    }

    .iv-card{
      position:relative;
      overflow:hidden;
      border-radius:21px;
      background:
        radial-gradient(180px 100px at 88% 8%, var(--card-glow), transparent 64%),
        linear-gradient(180deg, rgba(13,35,34,.94), rgba(6,20,19,.96));
      border:1px solid rgba(255,255,255,.085);
      box-shadow:
        0 16px 32px rgba(0,0,0,.25),
        0 0 0 1px rgba(255,255,255,.025) inset;
      transition:.18s ease;
    }

    .iv-card:hover{
      transform:translateY(-1px);
      border-color:rgba(255,255,255,.13);
      box-shadow:
        0 18px 36px rgba(0,0,0,.32),
        0 0 28px var(--card-glow-soft);
    }

    .iv-card:nth-child(4n+1){
      --card-accent:#00DF82;
      --card-accent2:#58ffad;
      --card-glow:rgba(0,223,130,.13);
      --card-glow-soft:rgba(0,223,130,.08);
    }

    .iv-card:nth-child(4n+2){
      --card-accent:#34d5ff;
      --card-accent2:#5a8cff;
      --card-glow:rgba(52,213,255,.13);
      --card-glow-soft:rgba(52,213,255,.08);
    }

    .iv-card:nth-child(4n+3){
      --card-accent:#f6c453;
      --card-accent2:#fb923c;
      --card-glow:rgba(246,196,83,.13);
      --card-glow-soft:rgba(246,196,83,.08);
    }

    .iv-card:nth-child(4n+4){
      --card-accent:#a78bfa;
      --card-accent2:#fb7185;
      --card-glow:rgba(167,139,250,.13);
      --card-glow-soft:rgba(167,139,250,.08);
    }

    .iv-card-top{
      display:grid;
      grid-template-columns:46px minmax(0,1fr) auto;
      gap:11px;
      align-items:center;
      padding:13px 13px 11px;
      border-bottom:1px solid rgba(255,255,255,.07);
    }

    .iv-product-icon{
      width:44px;
      height:44px;
      border-radius:17px;
      display:grid;
      place-items:center;
      color:#06110e;
      background:
        radial-gradient(circle at 28% 18%, rgba(255,255,255,.70), transparent 34%),
        linear-gradient(135deg, var(--card-accent), var(--card-accent2));
      box-shadow:
        0 12px 24px rgba(0,0,0,.26),
        inset 0 1px 0 rgba(255,255,255,.28);
      overflow:hidden;
      font-size:15px;
      font-weight:900;
    }

    .iv-product-info{
      min-width:0;
    }

    .iv-product-name{
      margin:0;
      color:#ffffff;
      font-size:14px;
      line-height:1.16;
      letter-spacing:-.02em;
      font-weight:780;
      white-space:normal;
      overflow:hidden;
      display:-webkit-box;
      -webkit-line-clamp:2;
      -webkit-box-orient:vertical;
    }

    .iv-product-meta{
      margin:5px 0 0;
      color:rgba(214,255,240,.52);
      font-size:10.8px;
      font-weight:500;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
    }

    .iv-badge{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      height:28px;
      padding:0 10px;
      border-radius:999px;
      font-size:10px;
      font-weight:850;
      letter-spacing:.04em;
      text-transform:uppercase;
      white-space:nowrap;
      border:1px solid rgba(255,255,255,.10);
      background:rgba(255,255,255,.055);
      color:rgba(214,255,240,.62);
    }

    .iv-badge.is-active{
      color:#06110e;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.55), transparent 34%),
        linear-gradient(135deg, #00DF82, #72ffab);
      border-color:rgba(255,255,255,.32);
      box-shadow:0 12px 24px rgba(0,223,130,.18);
    }

    .iv-card-body{
      padding:12px 13px 13px;
    }

    .iv-stats{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:9px;
      margin-bottom:9px;
    }

    .iv-stat{
      min-height:64px;
      border-radius:16px;
      background:rgba(255,255,255,.045);
      border:1px solid rgba(255,255,255,.07);
      padding:10px;
    }

    .iv-stat-label{
      margin:0 0 6px;
      color:rgba(214,255,240,.48);
      font-size:9.6px;
      line-height:1.1;
      font-weight:550;
    }

    .iv-stat-value{
      margin:0;
      color:#ffffff;
      font-size:12.4px;
      line-height:1.15;
      letter-spacing:-.01em;
      font-weight:780;
    }

    .iv-stat-value.is-accent{
      color:var(--card-accent);
    }

    .iv-daily-box{
      margin-bottom:10px;
      border-radius:16px;
      padding:10px 11px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      background:
        radial-gradient(circle at 88% 0%, var(--card-glow), transparent 54%),
        rgba(255,255,255,.045);
      border:1px solid rgba(255,255,255,.07);
    }

    .iv-daily-box span{
      color:rgba(214,255,240,.52);
      font-size:10.5px;
      font-weight:560;
    }

    .iv-daily-box strong{
      color:var(--card-accent);
      font-size:15px;
      letter-spacing:-.02em;
      font-weight:850;
      white-space:nowrap;
    }

    .iv-date-row{
      border-radius:17px;
      border:1px solid rgba(255,255,255,.07);
      background:rgba(2,10,10,.22);
      display:grid;
      grid-template-columns:1fr 32px 1fr;
      align-items:center;
      gap:8px;
      padding:11px 12px;
    }

    .iv-date-group{
      min-width:0;
    }

    .iv-date-group:last-child{
      text-align:right;
    }

    .iv-date-label{
      display:block;
      margin-bottom:4px;
      color:rgba(214,255,240,.44);
      font-size:9.5px;
      line-height:1;
      font-weight:620;
      text-transform:uppercase;
      letter-spacing:.08em;
    }

    .iv-date-value{
      display:block;
      color:#ffffff;
      font-size:11.3px;
      line-height:1.15;
      font-weight:720;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
    }

    .iv-date-arrow{
      width:32px;
      height:32px;
      border-radius:999px;
      display:grid;
      place-items:center;
      background:rgba(255,255,255,.06);
      color:var(--card-accent);
    }

    .iv-date-arrow svg{
      width:15px;
      height:15px;
    }

    /* =========================
       EMPTY STATE
    ========================= */
    .iv-empty{
      min-height:360px;
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      gap:14px;
      text-align:center;
      padding:26px 16px;
      border-radius:24px;
      background:
        radial-gradient(260px 140px at 50% 0%, rgba(0,223,130,.14), transparent 62%),
        radial-gradient(260px 140px at 90% 100%, rgba(90,140,255,.13), transparent 64%),
        linear-gradient(180deg, rgba(13,35,34,.88), rgba(6,20,19,.94));
      border:1px dashed rgba(255,255,255,.16);
      box-shadow:var(--iv-shadow-soft);
    }

    .iv-empty-icon{
      width:88px;
      height:88px;
      border-radius:28px;
      display:grid;
      place-items:center;
      color:#06110e;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.65), transparent 34%),
        linear-gradient(135deg, #00DF82, #72ffab);
      box-shadow:
        0 18px 38px rgba(0,223,130,.18),
        inset 0 1px 0 rgba(255,255,255,.32);
    }

    .iv-empty-icon svg{
      width:42px;
      height:42px;
    }

    .iv-empty-title{
      margin:0;
      color:#ffffff;
      font-size:18px;
      line-height:1.2;
      letter-spacing:-.03em;
      font-weight:820;
    }

    .iv-empty-desc{
      margin:0;
      max-width:330px;
      color:rgba(214,255,240,.58);
      font-size:12.5px;
      line-height:1.55;
      font-weight:470;
    }

    .iv-empty-grid{
      width:100%;
      display:grid;
      grid-template-columns:repeat(3,1fr);
      gap:8px;
      margin-top:2px;
    }

    .iv-empty-pill{
      border-radius:17px;
      padding:10px 8px;
      text-align:left;
      background:rgba(255,255,255,.045);
      border:1px solid rgba(255,255,255,.075);
    }

    .iv-empty-pill .k{
      margin-bottom:5px;
      color:rgba(214,255,240,.42);
      font-size:9px;
      font-weight:650;
      letter-spacing:.08em;
      text-transform:uppercase;
    }

    .iv-empty-pill .v{
      color:#ffffff;
      font-size:10.6px;
      line-height:1.28;
      font-weight:650;
    }

    .iv-empty-btn{
      width:100%;
      min-height:46px;
      border-radius:17px;
      display:flex;
      align-items:center;
      justify-content:center;
      gap:8px;
      color:#06110e;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.50), transparent 34%),
        linear-gradient(135deg, #00DF82, #72ffab);
      font-size:12.5px;
      font-weight:860;
      box-shadow:
        0 14px 30px rgba(0,223,130,.20),
        inset 0 1px 0 rgba(255,255,255,.32);
    }

    .iv-empty-btn svg{
      width:17px;
      height:17px;
    }

    /* =========================
       ANIMATION
    ========================= */
    .fade-up{
      opacity:0;
      transform:translateY(10px);
      animation:fadeUp .55s cubic-bezier(0.2,0.8,0.2,1) forwards;
    }

    @keyframes fadeUp{
      to{
        opacity:1;
        transform:translateY(0);
      }
    }

    
  </style>
</head>

<body>
  <main class="iv-page">
    <div class="iv-phone">

      {{-- HEADER --}}
      <header class="iv-topbar">
        <div class="iv-brand">
          <div class="iv-logo-card">
            <img src="{{ asset('logo.png') }}" alt="Rubik Company">
          </div>

          <div class="iv-title">
            <span>Portfolio investasi</span>
            <h1>Investasi Saya</h1>
          </div>
        </div>

        <div class="iv-header-actions">
          <a href="{{ url('/saldo/rincian') }}" class="iv-header-btn" aria-label="Saldo">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M4 7h16v10H4V7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              <path d="M8 11h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
              <path d="M16 13h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
            </svg>
          </a>

          <a href="{{ url('/akun') }}" class="iv-header-btn" aria-label="Profil">
            <svg viewBox="0 0 24 24" fill="none">
              <circle cx="12" cy="8" r="4" fill="currentColor"/>
              <path d="M4 21a8 8 0 0 1 16 0" fill="currentColor"/>
            </svg>
          </a>
        </div>
      </header>

      {{-- HERO --}}
      <section class="iv-hero">
        <div class="iv-hero-inner">
          <div class="iv-hero-head">
            <div>
              <p class="iv-hero-label">Total Modal Investasi</p>
              <h2 class="iv-hero-balance">Rp {{ number_format($totalModal, 0, ',', '.') }}</h2>

              <div class="iv-hero-profit">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                  <path d="M4 15l5-5 4 4 7-8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M15 6h5v5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                +Rp {{ number_format($totalDailyProfit, 0, ',', '.') }}
                <span>Profit harian aktif</span>
              </div>
            </div>

            <div class="iv-status-pill">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M4 15l5-5 4 4 7-8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              Aktif
            </div>
          </div>

          <div class="iv-quick-actions">
            <a href="{{ route('market.index') }}" class="iv-quick-action is-market">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M7 17 17 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                <path d="M9 7h8v8" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              Pasar
            </a>

            <a href="{{ url('/deposit') }}" class="iv-quick-action is-deposit">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M12 5v14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                <path d="M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              </svg>
              Deposit
            </a>

            <a href="{{ url('/ui/withdrawals') }}" class="iv-quick-action is-withdraw">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M12 4v13" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                <path d="M7 12l5 5 5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              Withdraw
            </a>
          </div>
        </div>
      </section>

      {{-- SUMMARY --}}
      <section class="iv-summary">
        <div class="iv-summary-card">
          <p class="iv-summary-label">Aktif</p>
          <p class="iv-summary-value"><span>{{ $totalActive }}</span> Plan</p>
        </div>

        <div class="iv-summary-card">
          <p class="iv-summary-label">Selesai</p>
          <p class="iv-summary-value">{{ $totalFinished }} Plan</p>
        </div>

        <div class="iv-summary-card">
          <p class="iv-summary-label">Total Profit</p>
          <p class="iv-summary-value"><span>Rp {{ number_format($totalProfit, 0, ',', '.') }}</span></p>
        </div>
      </section>

      {{-- LIST --}}
      <section class="iv-section">
        <div class="iv-section-head">
          <div class="iv-section-title">
            <h2>Riwayat Investasi</h2>
            <p>Semua plan aktif dan selesai ditampilkan di sini.</p>
          </div>

          <a href="{{ route('market.index') }}" class="iv-see-all">Tambah Plan</a>
        </div>

        <div class="iv-list">
          @forelse($investments as $inv)
            <article class="iv-card fade-up" style="animation-delay: {{ $loop->index * 0.08 }}s;">
              <div class="iv-card-top">
                <div class="iv-product-icon">
                  {{ strtoupper(substr(optional($inv->product)->name ?? 'I', 0, 1)) }}
                </div>

                <div class="iv-product-info">
                  <h3 class="iv-product-name">{{ optional($inv->product)->name ?? '-' }}</h3>
                  <p class="iv-product-meta">Durasi {{ $inv->duration_days }} Hari • Portfolio Asset</p>
                </div>

                @if($inv->status === 'active')
                  <span class="iv-badge is-active">Aktif</span>
                @else
                  <span class="iv-badge">{{ strtoupper($inv->status ?? 'SELESAI') }}</span>
                @endif
              </div>

              <div class="iv-card-body">
                <div class="iv-stats">
                  <div class="iv-stat">
                    <p class="iv-stat-label">Modal Investasi</p>
                    <p class="iv-stat-value">Rp {{ number_format((int)$inv->price, 0, ',', '.') }}</p>
                  </div>

                  <div class="iv-stat">
                    <p class="iv-stat-label">Total Profit</p>
                    <p class="iv-stat-value is-accent">+Rp {{ number_format((int)$inv->total_profit, 0, ',', '.') }}</p>
                  </div>
                </div>

                <div class="iv-daily-box">
                  <span>Profit Harian</span>
                  <strong>Rp {{ number_format((int)$inv->daily_profit, 0, ',', '.') }}</strong>
                </div>

                <div class="iv-date-row">
                  <div class="iv-date-group">
                    <span class="iv-date-label">Mulai</span>
                    <span class="iv-date-value">
                      {{ \Carbon\Carbon::parse($inv->start_date)->translatedFormat('d M Y') }}
                    </span>
                  </div>

                  <div class="iv-date-arrow" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                      <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>

                  <div class="iv-date-group">
                    <span class="iv-date-label">Selesai</span>
                    <span class="iv-date-value">
                      {{ \Carbon\Carbon::parse($inv->end_date)->translatedFormat('d M Y') }}
                    </span>
                  </div>
                </div>
              </div>
            </article>
          @empty
            <div class="iv-empty fade-up">
              <div class="iv-empty-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                  <rect x="3" y="7" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.8"/>
                  <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" stroke="currentColor" stroke-width="1.8"/>
                  <path d="M7 11h10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                  <path d="M7 15h7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
              </div>

              <h2 class="iv-empty-title">Belum ada investasi aktif</h2>
              <p class="iv-empty-desc">
                Portofolio kamu masih kosong. Pilih produk investasi dari halaman pasar untuk mulai melihat profit harian.
              </p>


              <a class="iv-empty-btn" href="{{ route('market.index') }}">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M12 5v14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                  <path d="M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                </svg>
                Mulai Investasi Sekarang
              </a>
            </div>
          @endforelse
        </div>
      </section>

      <div class="rb-bottom-spacer"></div>
    </div>
  </main>

  @include('partials.bottom-nav')
</body>
</html>