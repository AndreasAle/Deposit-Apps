{{-- Rubik Premium — Investasi Saya (NO @extends) --}}
 @include('partials.anti-inspect')
@php
  $user = $user ?? auth()->user();

  $refUsers = $refUsers ?? collect();
  $commissions = $commissions ?? collect();

  $totalCommission = (int) ($totalCommission ?? 0);
  $totalReferral = method_exists($refUsers, 'count') ? $refUsers->count() : 0;
  $saldoKomisi = (int) data_get($user, 'referral_earned_total', 0);

  $referralCode = data_get($user, 'referral_code', '-');

  $referralLink = $referralCode && $referralCode !== '-'
      ? url('/r/' . urlencode($referralCode))
      : route('home');

  $qrImage = 'https://quickchart.io/qr'
      . '?text=' . urlencode($referralLink)
      . '&size=220'
      . '&margin=1'
      . '&dark=0B2E2A'
      . '&light=E9FFF5';
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Referral | Rubik Company</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --rf-bg:#030F0F;
      --rf-bg2:#061817;
      --rf-card:#081a18;
      --rf-text:#f7fffb;
      --rf-soft:#dffcf1;
      --rf-muted:#9bb9ad;
      --rf-muted2:#6f9084;
      --rf-border:rgba(255,255,255,.09);

      --rf-green:#00DF82;
      --rf-cyan:#34d5ff;
      --rf-blue:#5a8cff;
      --rf-amber:#f6c453;
      --rf-rose:#fb7185;

      --rf-shadow:0 28px 70px rgba(0,0,0,.46);
      --rf-shadow-soft:0 16px 34px rgba(0,0,0,.24);
      --rf-radius:24px;
      --rf-radius-sm:18px;
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
      color:var(--rf-text);
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

    button,
    input{
      font-family:inherit;
    }

    .rf-page{
      width:100%;
      min-height:100vh;
      display:flex;
      justify-content:center;
      padding:14px 10px 0;
      position:relative;
      z-index:1;
    }

    .rf-phone{
      width:100%;
      max-width:430px;
      min-height:100vh;
      position:relative;
      padding:8px 4px 96px;
    }

    /* =========================
       HEADER
    ========================= */
    .rf-header{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
      padding:0 2px;
    }

    .rf-brand{
      display:flex;
      align-items:center;
      gap:10px;
      min-width:0;
    }

    .rf-logo-card{
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

    .rf-logo-card img{
      width:42px;
      height:42px;
      object-fit:contain;
      display:block;
    }

    .rf-title{
      min-width:0;
    }

    .rf-title span{
      display:block;
      margin-bottom:4px;
      color:rgba(214,255,240,.58);
      font-size:11px;
      line-height:1;
      font-weight:600;
      letter-spacing:.02em;
    }

    .rf-title h1{
      margin:0;
      font-size:23px;
      line-height:1;
      font-weight:850;
      letter-spacing:-.045em;
      color:#ffffff;
      white-space:nowrap;
    }

    .rf-header-actions{
      display:flex;
      align-items:center;
      gap:9px;
      flex:0 0 auto;
    }

    .rf-header-btn{
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

    .rf-header-btn:hover{
      transform:translateY(-1px);
      border-color:rgba(0,223,130,.22);
    }

    .rf-header-btn svg{
      width:20px;
      height:20px;
    }

    /* =========================
       HERO
    ========================= */
    .rf-hero{
      position:relative;
      overflow:hidden;
      border-radius:26px;
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
      animation:rfFadeUp .42s ease both;
    }

    .rf-hero::before{
      content:"";
      position:absolute;
      inset:0;
      background:
        linear-gradient(145deg, rgba(255,255,255,.48) 0%, rgba(255,255,255,.18) 27%, transparent 28%),
        linear-gradient(180deg, rgba(255,255,255,.22), rgba(255,255,255,0));
      pointer-events:none;
    }

    .rf-hero::after{
      content:"";
      position:absolute;
      right:-58px;
      bottom:-64px;
      width:184px;
      height:184px;
      border-radius:50%;
      background:rgba(0,223,130,.18);
      filter:blur(4px);
      pointer-events:none;
      animation:rfPulse 5.6s ease-in-out infinite;
    }

    .rf-hero-inner{
      position:relative;
      z-index:1;
    }

    .rf-hero-top{
      display:flex;
      justify-content:space-between;
      align-items:flex-start;
      gap:12px;
    }

    .rf-hero-label{
      margin:0 0 8px;
      color:rgba(3,24,20,.62);
      font-size:12px;
      font-weight:650;
      line-height:1.1;
    }

    .rf-hero-title{
      margin:0;
      color:#031713;
      font-size:28px;
      line-height:1.04;
      letter-spacing:-.055em;
      font-weight:900;
      text-shadow:none;
    }

    .rf-hero-sub{
      margin-top:10px;
      display:flex;
      align-items:center;
      gap:6px;
      color:#037e5d;
      font-size:12px;
      font-weight:760;
    }

    .rf-hero-sub span{
      color:rgba(3,24,20,.56);
      font-weight:550;
    }

    .rf-rate-pill{
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

    .rf-rate-pill svg{
      width:15px;
      height:15px;
      color:#047857;
    }

    .rf-code-box{
      margin-top:18px;
      border-radius:20px;
      padding:12px;
      background:rgba(255,255,255,.38);
      border:1px solid rgba(3,24,20,.08);
      box-shadow:
        0 10px 22px rgba(3,24,20,.08),
        inset 0 1px 0 rgba(255,255,255,.45);
    }

    .rf-code-label{
      margin:0 0 8px;
      color:rgba(3,24,20,.58);
      font-size:10.5px;
      line-height:1;
      font-weight:760;
      text-transform:uppercase;
      letter-spacing:.08em;
    }

    .rf-code-row{
      display:grid;
      grid-template-columns:minmax(0, 1fr) auto;
      gap:8px;
      align-items:center;
    }

    .rf-code-input{
      width:100%;
      min-height:44px;
      border:1px solid rgba(3,24,20,.10);
      outline:none;
      border-radius:16px;
      padding:0 13px;
      color:#031713;
      background:rgba(255,255,255,.58);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.45);
      font-size:14px;
      font-weight:900;
      letter-spacing:.10em;
    }

    .rf-copy-btn{
      min-height:44px;
      border:0;
      border-radius:16px;
      padding:0 15px;
      color:#06110e;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.50), transparent 34%),
        linear-gradient(135deg, #00DF82, #72ffab);
      font-size:12px;
      font-weight:900;
      cursor:pointer;
      box-shadow:
        0 12px 24px rgba(0,223,130,.16),
        inset 0 1px 0 rgba(255,255,255,.30);
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:7px;
      transition:.18s ease;
      position:relative;
      overflow:hidden;
    }

    .rf-copy-btn:hover{
      transform:translateY(-1px);
      filter:brightness(1.03);
    }

    .rf-copy-btn::after{
      content:"";
      position:absolute;
      top:0;
      left:-120%;
      width:60%;
      height:100%;
      background:linear-gradient(to right, transparent, rgba(255,255,255,.36), transparent);
      transform:skewX(-18deg);
      animation:rfShimmer 3.2s infinite;
      pointer-events:none;
    }

    .rf-copy-btn svg{
      width:16px;
      height:16px;
      position:relative;
      z-index:1;
    }

    .rf-copy-btn span{
      position:relative;
      z-index:1;
    }

    /* =========================
       SHARE CARD
    ========================= */
    .rf-share-card{
      margin-top:12px;
      position:relative;
      overflow:hidden;
      border-radius:24px;
      background:
        radial-gradient(220px 120px at 92% 8%, rgba(0,223,130,.10), transparent 64%),
        linear-gradient(180deg, rgba(13,35,34,.94), rgba(6,20,19,.96));
      border:1px solid rgba(255,255,255,.085);
      box-shadow:
        0 16px 32px rgba(0,0,0,.25),
        0 0 0 1px rgba(255,255,255,.025) inset;
      padding:14px;
      animation:rfFadeUp .42s ease both;
    }

    .rf-share-head{
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
    }

    .rf-share-title{
      min-width:0;
    }

    .rf-share-title h3{
      margin:0;
      color:#ffffff;
      font-size:17px;
      line-height:1.15;
      letter-spacing:-.03em;
      font-weight:850;
    }

    .rf-share-title p{
      margin:6px 0 0;
      color:rgba(214,255,240,.58);
      font-size:11px;
      line-height:1.45;
      font-weight:500;
    }

    .rf-share-chip{
      flex:0 0 auto;
      min-height:30px;
      padding:0 11px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:6px;
      color:#06110e;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.50), transparent 34%),
        linear-gradient(135deg, #00DF82, #72ffab);
      font-size:10px;
      font-weight:850;
      box-shadow:
        0 10px 20px rgba(0,223,130,.16),
        inset 0 1px 0 rgba(255,255,255,.30);
      white-space:nowrap;
    }

    .rf-share-chip svg{
      width:13px;
      height:13px;
    }

    .rf-share-stack{
      display:flex;
      flex-direction:column;
      gap:10px;
    }

    .rf-share-row{
      display:grid;
      grid-template-columns:96px minmax(0,1fr) 42px;
      gap:10px;
      align-items:center;
    }

    .rf-share-label{
      color:#ffffff;
      font-size:12px;
      font-weight:800;
      line-height:1.2;
    }

    .rf-share-input{
      width:100%;
      min-width:0;
      height:42px;
      border-radius:14px;
      border:1px solid rgba(255,255,255,.08);
      background:
        linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.03));
      color:#dffcf1;
      padding:0 12px;
      outline:none;
      font-size:12px;
      font-weight:750;
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,.05),
        0 10px 18px rgba(0,0,0,.16);
    }

    .rf-share-input.is-link{
      font-size:11px;
      color:rgba(223,252,241,.86);
    }

    .rf-icon-copy{
      width:42px;
      height:42px;
      border:0;
      border-radius:14px;
      cursor:pointer;
      color:#ccfff0;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.16), transparent 34%),
        linear-gradient(180deg, rgba(9,42,35,.96), rgba(4,18,16,.96));
      border:1px solid rgba(255,255,255,.10);
      box-shadow:
        0 10px 18px rgba(0,0,0,.22),
        inset 0 1px 0 rgba(255,255,255,.08);
      display:grid;
      place-items:center;
      transition:.18s ease;
    }

    .rf-icon-copy:hover{
      transform:translateY(-1px);
      border-color:rgba(0,223,130,.24);
      box-shadow:
        0 14px 24px rgba(0,0,0,.28),
        0 0 20px rgba(0,223,130,.08);
    }

    .rf-icon-copy svg{
      width:17px;
      height:17px;
    }

    .rf-share-actions{
      display:flex;
      gap:9px;
      flex-wrap:wrap;
      margin-top:4px;
    }

    .rf-share-btn{
      min-height:40px;
      padding:0 14px;
      border-radius:14px;
      border:1px solid rgba(255,255,255,.08);
      background:
        linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.03));
      color:#ffffff;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:7px;
      cursor:pointer;
      font-size:12px;
      font-weight:800;
      transition:.18s ease;
      box-shadow:
        0 10px 18px rgba(0,0,0,.16),
        inset 0 1px 0 rgba(255,255,255,.05);
    }

    .rf-share-btn:hover{
      transform:translateY(-1px);
    }

    .rf-share-btn svg{
      width:16px;
      height:16px;
    }

    .rf-share-btn.is-primary{
      color:#06110e;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.50), transparent 34%),
        linear-gradient(135deg, #00DF82, #72ffab);
      box-shadow:
        0 12px 24px rgba(0,223,130,.16),
        inset 0 1px 0 rgba(255,255,255,.30);
    }

    .rf-share-qr-wrap{
      margin-top:14px;
      display:flex;
      justify-content:center;
    }

    .rf-qr-box{
      position:relative;
      width:124px;
      height:124px;
      border-radius:22px;
      padding:10px;
      background:
        radial-gradient(circle at 20% 0%, rgba(255,255,255,.18), transparent 36%),
        linear-gradient(180deg, rgba(8,39,33,.96), rgba(4,18,16,.98));
      border:1px solid rgba(0,223,130,.18);
      box-shadow:
        0 14px 26px rgba(0,0,0,.28),
        0 0 28px rgba(0,223,130,.08),
        inset 0 1px 0 rgba(255,255,255,.08);
      display:flex;
      align-items:center;
      justify-content:center;
      overflow:hidden;
    }

    .rf-qr-box::before{
      content:"";
      position:absolute;
      inset:-20%;
      background:radial-gradient(circle, rgba(0,223,130,.12), transparent 55%);
      animation:rfQrGlow 4.2s ease-in-out infinite;
      pointer-events:none;
    }

    .rf-qr-inner{
      position:relative;
      z-index:1;
      width:100%;
      height:100%;
      border-radius:16px;
      padding:8px;
      background:rgba(233,255,245,.92);
      display:flex;
      align-items:center;
      justify-content:center;
    }

    .rf-qr-inner img{
      width:100%;
      height:100%;
      object-fit:contain;
      display:block;
    }

    /* =========================
       STATS
    ========================= */
    .rf-stats{
      margin-top:12px;
      display:grid;
      grid-template-columns:repeat(3,1fr);
      gap:9px;
    }

    .rf-stat{
      min-height:86px;
      border-radius:20px;
      padding:12px 10px;
      background:
        radial-gradient(circle at 80% 0%, var(--stat-glow), transparent 44%),
        linear-gradient(180deg, rgba(18,34,35,.94), rgba(8,21,21,.96));
      border:1px solid rgba(255,255,255,.085);
      box-shadow:
        0 14px 28px rgba(0,0,0,.24),
        inset 0 1px 0 rgba(255,255,255,.055);
      overflow:hidden;
      position:relative;
      animation:rfFadeUp .42s ease both;
    }

    .rf-stat:nth-child(1){
      --stat-glow:rgba(0,223,130,.20);
      --stat-accent:#00DF82;
    }

    .rf-stat:nth-child(2){
      --stat-glow:rgba(52,213,255,.20);
      --stat-accent:#34d5ff;
    }

    .rf-stat:nth-child(3){
      --stat-glow:rgba(246,196,83,.20);
      --stat-accent:#f6c453;
    }

    .rf-stat-label{
      margin:0;
      color:rgba(214,255,240,.52);
      font-size:10px;
      line-height:1.2;
      font-weight:650;
    }

    .rf-stat-value{
      margin:9px 0 0;
      color:#ffffff;
      font-size:15px;
      line-height:1.13;
      letter-spacing:-.025em;
      font-weight:900;
    }

    .rf-stat-value span{
      color:var(--stat-accent);
    }

    /* =========================
       INFO CARD
    ========================= */
    .rf-info-card{
      margin-top:12px;
      border-radius:21px;
      background:
        radial-gradient(180px 100px at 88% 8%, rgba(52,213,255,.11), transparent 64%),
        linear-gradient(180deg, rgba(13,35,34,.94), rgba(6,20,19,.96));
      border:1px solid rgba(255,255,255,.085);
      box-shadow:
        0 16px 32px rgba(0,0,0,.25),
        0 0 0 1px rgba(255,255,255,.025) inset;
      padding:13px;
      display:flex;
      align-items:center;
      gap:11px;
      animation:rfFadeUp .42s ease both;
    }

    .rf-info-icon{
      width:42px;
      height:42px;
      border-radius:16px;
      display:grid;
      place-items:center;
      color:#06110e;
      background:
        radial-gradient(circle at 28% 18%, rgba(255,255,255,.70), transparent 34%),
        linear-gradient(135deg, #34d5ff, #5a8cff);
      box-shadow:
        0 12px 24px rgba(0,0,0,.26),
        inset 0 1px 0 rgba(255,255,255,.28);
      flex:0 0 auto;
    }

    .rf-info-icon svg{
      width:20px;
      height:20px;
    }

    .rf-info-text{
      min-width:0;
    }

    .rf-info-text strong{
      display:block;
      color:#ffffff;
      font-size:13px;
      font-weight:850;
      letter-spacing:-.01em;
    }

    .rf-info-text p{
      margin:5px 0 0;
      color:rgba(214,255,240,.54);
      font-size:11px;
      line-height:1.45;
      font-weight:550;
    }

    /* =========================
       SECTION
    ========================= */
    .rf-section{
      margin-top:18px;
    }

    .rf-section-head{
      display:flex;
      align-items:flex-end;
      justify-content:space-between;
      gap:12px;
      margin-bottom:12px;
      padding:0 2px;
    }

    .rf-section-title{
      min-width:0;
    }

    .rf-section-title h2{
      margin:0;
      color:#ffffff;
      font-size:17px;
      line-height:1.15;
      letter-spacing:-.03em;
      font-weight:760;
    }

    .rf-section-title p{
      margin:5px 0 0;
      color:rgba(214,255,240,.56);
      font-size:11px;
      font-weight:450;
    }

    .rf-section-hint{
      color:#8fffd3;
      font-size:11.5px;
      font-weight:750;
      white-space:nowrap;
    }

    /* =========================
       TABLE
    ========================= */
    .rf-table-card{
      position:relative;
      overflow:hidden;
      border-radius:21px;
      background:
        radial-gradient(170px 94px at 88% 8%, rgba(0,223,130,.12), transparent 64%),
        linear-gradient(180deg, rgba(13,35,34,.94), rgba(6,20,19,.96));
      border:1px solid rgba(255,255,255,.085);
      box-shadow:
        0 16px 32px rgba(0,0,0,.25),
        0 0 0 1px rgba(255,255,255,.025) inset;
      padding:12px;
      animation:rfFadeUp .42s ease both;
    }

    .rf-table-wrap{
      width:100%;
      overflow-x:auto;
      border-radius:17px;
      border:1px solid rgba(255,255,255,.07);
      background:rgba(2,10,10,.22);
    }

    .rf-table-wrap::-webkit-scrollbar{
      height:6px;
    }

    .rf-table-wrap::-webkit-scrollbar-thumb{
      background:rgba(0,223,130,.38);
      border-radius:999px;
    }

    .rf-table{
      width:100%;
      min-width:560px;
      border-collapse:collapse;
    }

    .rf-table.is-wide{
      min-width:760px;
    }

    .rf-table thead th{
      text-align:left;
      padding:12px 12px;
      color:rgba(214,255,240,.54);
      font-size:10px;
      line-height:1.1;
      font-weight:800;
      letter-spacing:.08em;
      text-transform:uppercase;
      border-bottom:1px solid rgba(255,255,255,.07);
      background:rgba(255,255,255,.025);
      white-space:nowrap;
    }

    .rf-table tbody td{
      padding:12px 12px;
      color:rgba(247,255,251,.88);
      font-size:12px;
      line-height:1.35;
      font-weight:600;
      border-bottom:1px solid rgba(255,255,255,.055);
      vertical-align:top;
    }

    .rf-table tbody tr:last-child td{
      border-bottom:0;
    }

    .rf-table tbody tr:hover td{
      background:rgba(255,255,255,.025);
    }

    .rf-td-strong{
      color:#ffffff !important;
      font-weight:850 !important;
    }

    .rf-td-green{
      color:#00DF82 !important;
      font-weight:900 !important;
      text-shadow:0 0 16px rgba(0,223,130,.10);
    }

    .rf-badge{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      min-height:24px;
      padding:0 9px;
      border-radius:999px;
      color:#06110e;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.42), transparent 34%),
        linear-gradient(135deg, #00DF82, #72ffab);
      font-size:10px;
      font-weight:850;
      white-space:nowrap;
    }

    .rf-empty{
      padding:18px 14px;
      border-radius:18px;
      background:rgba(9,37,31,.76);
      border:1px dashed rgba(255,255,255,.14);
      color:rgba(214,255,240,.72);
      text-align:center;
      font-size:12.5px;
      font-weight:650;
    }

    .rf-pager{
      margin-top:12px;
      border-radius:18px;
      border:1px solid rgba(255,255,255,.075);
      background:rgba(2,10,10,.26);
      padding:10px 12px;
      overflow:auto;
      color:#ffffff;
    }

    .rf-pager *{
      color:rgba(214,255,240,.78);
      font-size:12px;
    }

    .rf-pager a{
      color:#8fffd3 !important;
      font-weight:850;
      text-decoration:none;
    }

    .rf-pager nav{
      display:block;
      width:100%;
    }

    /* =========================
       TOAST
    ========================= */
    .rf-toast{
      position:fixed;
      left:50%;
      bottom:92px;
      z-index:9999;
      transform:translateX(-50%) translateY(12px);
      opacity:0;
      pointer-events:none;
      min-height:44px;
      padding:0 15px;
      border-radius:999px;
      display:flex;
      align-items:center;
      justify-content:center;
      gap:8px;
      color:#06110e;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.62), transparent 34%),
        linear-gradient(135deg, #00DF82, #72ffab);
      box-shadow:
        0 18px 42px rgba(0,0,0,.32),
        0 0 28px rgba(0,223,130,.18);
      font-size:12px;
      font-weight:850;
      transition:.22s ease;
      white-space:nowrap;
    }

    .rf-toast.show{
      opacity:1;
      transform:translateX(-50%) translateY(0);
    }

    .rf-toast svg{
      width:16px;
      height:16px;
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

    @keyframes rfShimmer{
      0%{ left:-120%; }
      18%{ left:220%; }
      100%{ left:220%; }
    }

    @keyframes rfPulse{
      0%,100%{
        transform:scale(1);
        opacity:.65;
      }
      50%{
        transform:scale(1.08);
        opacity:.95;
      }
    }

    @keyframes rfQrGlow{
      0%,100%{
        transform:scale(1);
        opacity:.72;
      }
      50%{
        transform:scale(1.08);
        opacity:1;
      }
    }

    @keyframes rfFadeUp{
      from{
        opacity:0;
        transform:translateY(10px);
      }
      to{
        opacity:1;
        transform:translateY(0);
      }
    }

    @media (max-width:430px){
      .rf-stats{
        grid-template-columns:repeat(3,1fr);
        gap:8px;
      }

      .rf-stat{
        min-height:84px;
        padding:11px 8px;
      }

      .rf-stat-value{
        font-size:13px;
      }

      .rf-code-row{
        grid-template-columns:1fr;
      }

      .rf-copy-btn{
        width:100%;
      }

      .rf-share-row{
        grid-template-columns:92px minmax(0,1fr) 42px;
        gap:8px;
      }

      .rf-share-title h3{
        font-size:16px;
      }

      .rf-share-chip{
        font-size:9.5px;
        padding:0 9px;
      }
    }

    @media (max-width:370px){
      .rf-logo-card{
        width:44px;
        height:44px;
      }

      .rf-logo-card img{
        width:38px;
        height:38px;
      }

      .rf-title h1{
        font-size:21px;
      }

      .rf-hero{
        padding:15px;
      }

      .rf-hero-title{
        font-size:25px;
      }

      .rf-rate-pill{
        min-width:70px;
        height:36px;
        font-size:11px;
      }

      .rf-share-head{
        flex-direction:column;
        align-items:flex-start;
      }

      .rf-share-row{
        grid-template-columns:1fr;
      }

      .rf-share-label{
        margin-bottom:2px;
      }

      .rf-icon-copy{
        width:100%;
        height:40px;
        border-radius:12px;
      }

      .rf-share-actions{
        flex-direction:column;
      }

      .rf-share-btn{
        width:100%;
      }

      .rf-stats{
        gap:7px;
      }

      .rf-stat{
        min-height:78px;
        border-radius:18px;
        padding:10px 7px;
      }

      .rf-stat-label{
        font-size:9.5px;
      }

      .rf-stat-value{
        font-size:12px;
      }

      .rf-info-card,
      .rf-table-card{
        border-radius:20px;
      }

      .rf-section-title h2{
        font-size:16px;
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

    /* =========================
   HERO REFERRAL CONTENT
   QR + kode + tautan di dalam hero
========================= */

.rf-hero-qr-wrap{
  margin-top:18px;
  display:flex;
  align-items:center;
  justify-content:center;
}

.rf-hero-qr-box{
  position:relative;
  width:126px;
  height:126px;
  border-radius:24px;
  padding:10px;
  background:
    radial-gradient(circle at 20% 0%, rgba(255,255,255,.20), transparent 36%),
    linear-gradient(180deg, rgba(8,39,33,.88), rgba(4,18,16,.92));
  border:1px solid rgba(3,24,20,.14);
  box-shadow:
    0 16px 30px rgba(3,24,20,.18),
    0 0 28px rgba(0,223,130,.12),
    inset 0 1px 0 rgba(255,255,255,.18);
  display:flex;
  align-items:center;
  justify-content:center;
  overflow:hidden;
}

.rf-hero-qr-box::before{
  content:"";
  position:absolute;
  inset:-24%;
  background:radial-gradient(circle, rgba(0,223,130,.16), transparent 56%);
  animation:rfQrGlow 4.2s ease-in-out infinite;
  pointer-events:none;
}

.rf-hero-qr-inner{
  position:relative;
  z-index:1;
  width:100%;
  height:100%;
  border-radius:18px;
  padding:8px;
  background:rgba(233,255,245,.92);
  display:flex;
  align-items:center;
  justify-content:center;
}

.rf-hero-qr-inner img{
  width:100%;
  height:100%;
  object-fit:contain;
  display:block;
}

.rf-code-box + .rf-code-box{
  margin-top:10px;
}

.rf-code-input.is-link{
  letter-spacing:0 !important;
  font-size:12px !important;
  font-weight:800 !important;
}

.rf-hero-actions{
  margin-top:12px;
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:9px;
}

.rf-hero-action{
  min-height:42px;
  border-radius:15px;
  border:1px solid rgba(3,24,20,.08);
  background:rgba(255,255,255,.34);
  color:#05221b;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  cursor:pointer;
  font-size:12px;
  font-weight:850;
  box-shadow:
    0 10px 22px rgba(3,24,20,.08),
    inset 0 1px 0 rgba(255,255,255,.45);
  transition:.18s ease;
}

.rf-hero-action:hover{
  transform:translateY(-1px);
  background:rgba(255,255,255,.48);
}

.rf-hero-action svg{
  width:16px;
  height:16px;
}

.rf-hero-action.is-primary{
  color:#06110e;
  background:
    radial-gradient(circle at 30% 0%, rgba(255,255,255,.50), transparent 34%),
    linear-gradient(135deg, #00DF82, #72ffab);
  box-shadow:
    0 12px 24px rgba(0,223,130,.16),
    inset 0 1px 0 rgba(255,255,255,.30);
}

@keyframes rfQrGlow{
  0%,100%{
    transform:scale(1);
    opacity:.72;
  }
  50%{
    transform:scale(1.08);
    opacity:1;
  }
}

@media (max-width:370px){
  .rf-hero-qr-box{
    width:116px;
    height:116px;
    border-radius:22px;
  }

  .rf-hero-actions{
    grid-template-columns:1fr;
  }

  .rf-hero-action{
    width:100%;
  }
}
  </style>
</head>

<body>
  <main class="rf-page">
    <div class="rf-phone">

      {{-- HEADER --}}
      <header class="rf-header">
        <div class="rf-brand">
          <div class="rf-logo-card">
            <img src="{{ asset('logo.png') }}" alt="Rubik Company">
          </div>

          <div class="rf-title">
            <span>Program komisi</span>
            <h1>Referral</h1>
          </div>
        </div>

        <div class="rf-header-actions">
          <a href="{{ url('/dashboard') }}" class="rf-header-btn" aria-label="Kembali ke Dashboard">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M15 18 9 12l6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </a>

          <a href="{{ url('/akun') }}" class="rf-header-btn" aria-label="Akun">
            <svg viewBox="0 0 24 24" fill="none">
              <circle cx="12" cy="8" r="4" fill="currentColor"/>
              <path d="M4 21a8 8 0 0 1 16 0" fill="currentColor"/>
            </svg>
          </a>
        </div>
      </header>

      {{-- HERO --}}
{{-- HERO --}}
<section class="rf-hero">
  <div class="rf-hero-inner">
    <div class="rf-hero-top">
      <div>
        <p class="rf-hero-label">Total Komisi Referral</p>
        <h2 class="rf-hero-title">Rp {{ number_format($totalCommission, 0, ',', '.') }}</h2>

        <div class="rf-hero-sub">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
            <path d="M4 15l5-5 4 4 7-8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M15 6h5v5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        Produk 33%
        <span>Referral aktif</span>
        </div>
      </div>

      <div class="rf-rate-pill">
        <svg viewBox="0 0 24 24" fill="none">
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
        </svg>
        Aktif
      </div>
    </div>

    {{-- QR REFERRAL --}}
    <div class="rf-hero-qr-wrap">
      <div class="rf-hero-qr-box">
        <div class="rf-hero-qr-inner">
          <img src="{{ $qrImage }}" alt="QR Referral">
        </div>
      </div>
    </div>

    {{-- KODE REFERRAL --}}
    <div class="rf-code-box">
      <p class="rf-code-label">Kode Referral Kamu</p>

      <div class="rf-code-row">
        <input
          id="referralCodeField"
          value="{{ $referralCode }}"
          class="rf-code-input"
          readonly
          aria-label="Kode Referral"
        >

        <button
          type="button"
          class="rf-copy-btn"
          onclick="copyById('referralCodeField', 'Kode referral berhasil dicopy!')"
          aria-label="Copy kode referral"
        >
          <svg viewBox="0 0 24 24" fill="none">
            <rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2"/>
            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
          <span>Copy</span>
        </button>
      </div>
    </div>

    {{-- TAUTAN REFERRAL --}}
    <div class="rf-code-box">
      <p class="rf-code-label">Tautan Referral</p>

      <div class="rf-code-row">
        <input
          id="referralLinkField"
          value="{{ $referralLink }}"
          class="rf-code-input is-link"
          readonly
          aria-label="Tautan Referral"
        >

        <button
          type="button"
          class="rf-copy-btn"
          onclick="copyById('referralLinkField', 'Tautan referral berhasil dicopy!')"
          aria-label="Copy tautan referral"
        >
          <svg viewBox="0 0 24 24" fill="none">
            <rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2"/>
            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
          <span>Copy</span>
        </button>
      </div>
    </div>

    {{-- ACTION --}}
    <div class="rf-hero-actions">
      <button type="button" class="rf-hero-action is-primary" onclick="shareReferral()">
        <svg viewBox="0 0 24 24" fill="none">
          <path d="M4 12v7a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
          <path d="M12 16V3" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
          <path d="m7 8 5-5 5 5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Bagikan Link
      </button>

      <button type="button" class="rf-hero-action" onclick="copyById('referralLinkField', 'Tautan referral berhasil dicopy!')">
        <svg viewBox="0 0 24 24" fill="none">
          <rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2"/>
          <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
        </svg>
        Copy Link
      </button>
    </div>
  </div>
</section>

      {{-- STATS --}}
      <section class="rf-stats" aria-label="Statistik Referral">
        <div class="rf-stat">
          <p class="rf-stat-label">Jumlah Referral</p>
          <div class="rf-stat-value"><span>{{ $totalReferral }}</span> User</div>
        </div>

        <div class="rf-stat">
          <p class="rf-stat-label">Total Komisi</p>
          <div class="rf-stat-value">Rp {{ number_format($totalCommission, 0, ',', '.') }}</div>
        </div>

        <div class="rf-stat">
          <p class="rf-stat-label">Saldo Komisi</p>
          <div class="rf-stat-value">Rp {{ number_format($saldoKomisi, 0, ',', '.') }}</div>
        </div>
      </section>

      {{-- INFO --}}
      <section class="rf-info-card">
        <div class="rf-info-icon">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
            <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2.2"/>
            <path d="M22 21v-2a4 4 0 0 0-3-3.87" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
          </svg>
        </div>

        <div class="rf-info-text">
          <strong>Bagikan kode referral ke teman kamu</strong>
          <p>Komisi akan tercatat otomatis saat referral melakukan deposit atau membeli produk investasi.</p>
        </div>
      </section>

      {{-- USER REFERRAL --}}
      <section class="rf-section">
        <div class="rf-section-head">
          <div class="rf-section-title">
            <h2>User Referral</h2>
            <p>Daftar user yang memakai kode kamu</p>
          </div>

          <div class="rf-section-hint">
            Total {{ $totalReferral }}
          </div>
        </div>

        <div class="rf-table-card">
          <div class="rf-table-wrap">
            <table class="rf-table">
              <thead>
                <tr>
                  <th>Nama</th>
                  <th>Phone</th>
                  <th>Tanggal Daftar</th>
                </tr>
              </thead>

              <tbody>
                @forelse ($refUsers as $ru)
                  <tr>
                    <td class="rf-td-strong">{{ $ru->name }}</td>
                    <td>{{ $ru->phone ?? '-' }}</td>
                    <td>{{ optional($ru->created_at)->format('d-m-Y H:i') ?? '-' }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3">
                      <div class="rf-empty">
                        Belum ada user yang daftar memakai kode kamu.
                      </div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </section>

      {{-- RIWAYAT KOMISI --}}
      <section class="rf-section">
        <div class="rf-section-head">
          <div class="rf-section-title">
            <h2>Riwayat Komisi</h2>
            <p>Komisi terbaru dari deposit dan pembelian produk</p>
          </div>

          <div class="rf-section-hint">
            Terbaru
          </div>
        </div>

        <div class="rf-table-card">
          <div class="rf-table-wrap">
            <table class="rf-table is-wide">
              <thead>
                <tr>
                  <th>Tanggal</th>
                  <th>Sumber</th>
                  <th>Dasar</th>
                  <th>Rate</th>
                  <th>Komisi</th>
                </tr>
              </thead>

              <tbody>
                @forelse ($commissions as $c)
                  <tr>
                    <td>{{ optional($c->created_at)->format('d-m-Y H:i') ?? '-' }}</td>

                    <td>
                      @if($c->source_type === 'deposit')
                        <span class="rf-badge">Deposit</span>
                      @else
                        <span class="rf-badge">Beli Produk</span>
                      @endif
                    </td>

                    <td>Rp {{ number_format($c->base_amount, 0, ',', '.') }}</td>
                    <td>{{ (float) $c->rate * 100 }}%</td>
                    <td class="rf-td-green">Rp {{ number_format($c->commission_amount, 0, ',', '.') }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5">
                      <div class="rf-empty">
                        Belum ada komisi masuk.
                      </div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          @if(is_object($commissions) && method_exists($commissions, 'links'))
            <div class="rf-pager">
              {{ $commissions->links() }}
            </div>
          @endif
        </div>
      </section>

      <div class="rb-bottom-spacer"></div>
    </div>
  </main>

  <div id="rfToast" class="rf-toast" role="status" aria-live="polite">
    <svg viewBox="0 0 24 24" fill="none">
      <path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    <span>Kode referral berhasil dicopy!</span>
  </div>

  @include('partials.bottom-nav')

  <script>
    function showToast(message) {
      const toast = document.getElementById('rfToast');
      if (!toast) return;

      const text = toast.querySelector('span');
      if (text) text.textContent = message;

      toast.classList.add('show');

      clearTimeout(window.__rfToastTimer);
      window.__rfToastTimer = setTimeout(function () {
        toast.classList.remove('show');
      }, 1800);
    }

    async function copyText(value, message = 'Berhasil dicopy!') {
      try {
        if (navigator.clipboard && window.isSecureContext) {
          await navigator.clipboard.writeText(value);
        } else {
          const temp = document.createElement('input');
          temp.value = value;
          document.body.appendChild(temp);
          temp.select();
          temp.setSelectionRange(0, 99999);
          document.execCommand('copy');
          document.body.removeChild(temp);
        }

        showToast(message);
      } catch (error) {
        showToast('Gagal copy, coba manual.');
      }
    }

    function copyById(id, message) {
      const el = document.getElementById(id);
      if (!el) return;

      copyText(el.value || '', message);
    }

    async function shareReferral() {
      const link = document.getElementById('referralLinkField')?.value || '';
      const code = document.getElementById('referralCodeField')?.value || '';

      if (!link) {
        showToast('Tautan referral belum tersedia.');
        return;
      }

      const shareData = {
        title: 'Referral Rubik',
        text: `Gabung pakai kode referral saya: ${code}`,
        url: link
      };

      try {
        if (navigator.share) {
          await navigator.share(shareData);
        } else {
          await copyText(link, 'Tautan referral berhasil dicopy!');
        }
      } catch (error) {
        if (error && error.name !== 'AbortError') {
          showToast('Gagal membagikan tautan.');
        }
      }
    }
  </script>
</body>
</html>