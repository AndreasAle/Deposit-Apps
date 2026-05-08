 @include('partials.anti-inspect')
@php
  $user = auth()->user();

  $saldoUtama = (int) data_get($user, 'saldo', 0);

  $saldoPenarikan = (int) (
    data_get($user, 'saldo_penarikan')
    ?? data_get($user, 'withdraw_balance')
    ?? data_get($user, 'saldo_withdraw')
    ?? 0
  );

  $totalAset = $saldoUtama + $saldoPenarikan;
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Akun Saya | Rubik Company</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --ac-bg:#030F0F;
      --ac-bg2:#061817;
      --ac-surface:#071f1b;
      --ac-card:#081a18;
      --ac-text:#f7fffb;
      --ac-soft:#dffcf1;
      --ac-muted:#9bb9ad;
      --ac-muted2:#6f9084;
      --ac-border:rgba(255,255,255,.09);

      --ac-green:#00DF82;
      --ac-emerald:#13c58f;
      --ac-cyan:#34d5ff;
      --ac-blue:#5a8cff;
      --ac-violet:#a78bfa;
      --ac-amber:#f6c453;
      --ac-orange:#fb923c;
      --ac-rose:#fb7185;
      --ac-red:#ef4444;

      --ac-shadow:0 28px 70px rgba(0,0,0,.46);
      --ac-shadow-soft:0 16px 34px rgba(0,0,0,.24);
      --ac-radius:24px;
      --ac-radius-sm:18px;
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
      color:var(--ac-text);
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

    .ac-page{
      width:100%;
      min-height:100vh;
      display:flex;
      justify-content:center;
      padding:14px 10px 0;
      position:relative;
      z-index:1;
    }

    .ac-phone{
      width:100%;
      max-width:430px;
      min-height:100vh;
      position:relative;
      padding:8px 4px 96px;
    }

    /* =========================
       HEADER
    ========================= */
    .ac-header{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
      padding:0 2px;
    }

    .ac-greeting{
      min-width:0;
    }

    .ac-greeting span{
      display:block;
      margin-bottom:5px;
      color:rgba(214,255,240,.58);
      font-size:11px;
      line-height:1;
      font-weight:600;
      letter-spacing:.02em;
    }

    .ac-greeting h1{
      margin:0;
      font-size:23px;
      line-height:1.1;
      font-weight:850;
      letter-spacing:-.045em;
      color:#ffffff;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width:270px;
    }

    .ac-header-actions{
      display:flex;
      align-items:center;
      gap:9px;
      flex:0 0 auto;
    }

    .ac-header-btn{
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

    .ac-header-btn svg{
      width:20px;
      height:20px;
    }

    .ac-avatar{
      width:42px;
      height:42px;
      border-radius:999px;
      display:grid;
      place-items:center;
      color:#06110e;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.60), transparent 34%),
        linear-gradient(135deg, #00DF82, #72ffab);
      box-shadow:
        0 12px 24px rgba(0,223,130,.20),
        inset 0 1px 0 rgba(255,255,255,.30);
      font-size:15px;
      font-weight:900;
    }

    /* =========================
       WALLET CARD
    ========================= */
    .ac-wallet{
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
    }

    .ac-wallet::before{
      content:"";
      position:absolute;
      inset:0;
      background:
        linear-gradient(145deg, rgba(255,255,255,.48) 0%, rgba(255,255,255,.18) 27%, transparent 28%),
        linear-gradient(180deg, rgba(255,255,255,.22), rgba(255,255,255,0));
      pointer-events:none;
    }

    .ac-wallet-inner{
      position:relative;
      z-index:1;
    }

    .ac-wallet-top{
      display:flex;
      justify-content:space-between;
      align-items:flex-start;
      gap:12px;
    }

    .ac-wallet-label{
      margin:0 0 8px;
      color:rgba(3,24,20,.62);
      font-size:12px;
      font-weight:650;
      line-height:1.1;
    }

    .ac-wallet-amount{
      margin:0;
      color:#031713;
      font-size:31px;
      line-height:1.05;
      letter-spacing:-.055em;
      font-weight:880;
      text-shadow:none;
    }

    .ac-wallet-sub{
      margin-top:10px;
      display:flex;
      align-items:center;
      gap:6px;
      color:#037e5d;
      font-size:12px;
      font-weight:760;
    }

    .ac-wallet-sub span{
      color:rgba(3,24,20,.56);
      font-weight:550;
    }

    .ac-vip-pill{
      flex:0 0 auto;
      min-width:74px;
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

    .ac-vip-pill::before{
      content:"";
      width:8px;
      height:8px;
      border-radius:999px;
      background:#047857;
      box-shadow:0 0 0 4px rgba(4,120,87,.12);
    }

    .ac-wallet-actions{
      margin-top:18px;
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:8px;
    }

    .ac-wallet-btn{
      min-height:46px;
      border:0;
      border-radius:17px;
      display:flex;
      align-items:center;
      justify-content:center;
      gap:8px;
      cursor:pointer;
      color:#05221b;
      background:rgba(255,255,255,.38);
      border:1px solid rgba(3,24,20,.08);
      box-shadow:
        0 10px 22px rgba(3,24,20,.08),
        inset 0 1px 0 rgba(255,255,255,.45);
      font-size:12px;
      font-weight:820;
      transition:.18s ease;
    }

    .ac-wallet-btn:hover{
      transform:translateY(-1px);
      background:rgba(255,255,255,.54);
    }

    .ac-wallet-btn svg{
      width:18px;
      height:18px;
    }

    .ac-wallet-btn.is-deposit svg{
      color:#047857;
    }

    .ac-wallet-btn.is-withdraw svg{
      color:#2563eb;
    }

    /* =========================
       QUICK ACTIONS
    ========================= */
    .ac-quick{
      margin-top:12px;
      display:grid;
      grid-template-columns:repeat(4,1fr);
      gap:9px;
    }

    .ac-quick-item{
      min-height:84px;
      border-radius:20px;
      padding:11px 7px;
      background:
        radial-gradient(circle at 80% 0%, var(--quick-glow), transparent 44%),
        linear-gradient(180deg, rgba(18,34,35,.94), rgba(8,21,21,.96));
      border:1px solid rgba(255,255,255,.085);
      box-shadow:
        0 14px 28px rgba(0,0,0,.24),
        inset 0 1px 0 rgba(255,255,255,.055);
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      gap:8px;
      text-align:center;
      transition:.18s ease;
    }

    .ac-quick-item:hover{
      transform:translateY(-1px);
    }

    .ac-quick-item:nth-child(1){
      --quick-glow:rgba(0,223,130,.20);
      --quick-accent:#00DF82;
    }

    .ac-quick-item:nth-child(2){
      --quick-glow:rgba(52,213,255,.20);
      --quick-accent:#34d5ff;
    }

    .ac-quick-item:nth-child(3){
      --quick-glow:rgba(246,196,83,.20);
      --quick-accent:#f6c453;
    }

    .ac-quick-item:nth-child(4){
      --quick-glow:rgba(167,139,250,.20);
      --quick-accent:#a78bfa;
    }

    .ac-quick-icon{
      width:38px;
      height:38px;
      border-radius:15px;
      display:grid;
      place-items:center;
      color:var(--quick-accent);
      background:rgba(255,255,255,.055);
      border:1px solid rgba(255,255,255,.075);
    }

    .ac-quick-icon svg{
      width:19px;
      height:19px;
    }

    .ac-quick-label{
      color:rgba(244,255,251,.88);
      font-size:10.3px;
      line-height:1.15;
      font-weight:760;
    }

    /* =========================
       ACCOUNT ID CARD
    ========================= */
    .ac-id-card{
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
      justify-content:space-between;
      gap:12px;
    }

    .ac-id-left{
      min-width:0;
      display:flex;
      align-items:center;
      gap:11px;
    }

    .ac-id-icon{
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

    .ac-id-icon svg{
      width:20px;
      height:20px;
    }

    .ac-id-meta{
      min-width:0;
    }

    .ac-id-meta p{
      margin:0;
      color:rgba(214,255,240,.50);
      font-size:10.5px;
      font-weight:560;
    }

    .ac-id-meta strong{
      display:block;
      margin-top:5px;
      color:#ffffff;
      font-size:13px;
      font-weight:800;
      letter-spacing:-.01em;
    }

    .ac-copy-btn{
      min-height:36px;
      border:0;
      border-radius:999px;
      padding:0 13px;
      color:#06110e;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.50), transparent 34%),
        linear-gradient(135deg, #00DF82, #72ffab);
      font-size:11px;
      font-weight:850;
      cursor:pointer;
      box-shadow:
        0 12px 24px rgba(0,223,130,.16),
        inset 0 1px 0 rgba(255,255,255,.30);
      white-space:nowrap;
    }

    /* =========================
       SECTION / LIST
    ========================= */
    .ac-section{
      margin-top:18px;
    }

    .ac-section-head{
      display:flex;
      align-items:flex-end;
      justify-content:space-between;
      gap:12px;
      margin-bottom:12px;
      padding:0 2px;
    }

    .ac-section-title h2{
      margin:0;
      color:#ffffff;
      font-size:17px;
      line-height:1.15;
      letter-spacing:-.03em;
      font-weight:760;
    }

    .ac-section-title p{
      margin:5px 0 0;
      color:rgba(214,255,240,.56);
      font-size:11px;
      font-weight:450;
    }

    .ac-section-hint{
      color:#8fffd3;
      font-size:11.5px;
      font-weight:750;
      white-space:nowrap;
    }

    .ac-menu-list{
      display:flex;
      flex-direction:column;
      gap:9px;
    }

    .ac-menu-item{
      position:relative;
      overflow:hidden;
      border-radius:20px;
      background:
        radial-gradient(180px 100px at 88% 8%, var(--menu-glow), transparent 64%),
        linear-gradient(180deg, rgba(13,35,34,.94), rgba(6,20,19,.96));
      border:1px solid rgba(255,255,255,.085);
      box-shadow:
        0 16px 32px rgba(0,0,0,.22),
        0 0 0 1px rgba(255,255,255,.025) inset;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      padding:12px;
      transition:.18s ease;
    }

    .ac-menu-item:hover{
      transform:translateY(-1px);
      border-color:rgba(255,255,255,.13);
    }

    .ac-menu-item:nth-child(4n+1){
      --menu-accent:#00DF82;
      --menu-accent2:#58ffad;
      --menu-glow:rgba(0,223,130,.12);
    }

    .ac-menu-item:nth-child(4n+2){
      --menu-accent:#34d5ff;
      --menu-accent2:#5a8cff;
      --menu-glow:rgba(52,213,255,.12);
    }

    .ac-menu-item:nth-child(4n+3){
      --menu-accent:#f6c453;
      --menu-accent2:#fb923c;
      --menu-glow:rgba(246,196,83,.12);
    }

    .ac-menu-item:nth-child(4n+4){
      --menu-accent:#a78bfa;
      --menu-accent2:#fb7185;
      --menu-glow:rgba(167,139,250,.12);
    }

    .ac-menu-left{
      display:flex;
      align-items:center;
      gap:11px;
      min-width:0;
    }

    .ac-menu-icon{
      width:42px;
      height:42px;
      border-radius:16px;
      display:grid;
      place-items:center;
      color:#06110e;
      background:
        radial-gradient(circle at 28% 18%, rgba(255,255,255,.70), transparent 34%),
        linear-gradient(135deg, var(--menu-accent), var(--menu-accent2));
      box-shadow:
        0 12px 24px rgba(0,0,0,.23),
        inset 0 1px 0 rgba(255,255,255,.28);
      flex:0 0 auto;
    }

    .ac-menu-icon svg{
      width:20px;
      height:20px;
    }

    .ac-menu-text{
      min-width:0;
    }

    .ac-menu-text strong{
      display:block;
      color:#ffffff;
      font-size:13.2px;
      line-height:1.15;
      font-weight:760;
      letter-spacing:-.015em;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
    }

    .ac-menu-text span{
      display:block;
      margin-top:5px;
      color:rgba(214,255,240,.50);
      font-size:10.8px;
      font-weight:500;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
    }

    .ac-menu-arrow{
      color:rgba(214,255,240,.48);
      flex:0 0 auto;
    }

    .ac-menu-arrow svg{
      width:18px;
      height:18px;
    }

    /* =========================
       LOGOUT
    ========================= */
    .ac-logout{
      margin-top:12px;
      border-radius:20px;
      background:
        radial-gradient(180px 100px at 88% 8%, rgba(239,68,68,.13), transparent 64%),
        linear-gradient(180deg, rgba(42,18,22,.92), rgba(18,8,10,.95));
      border:1px solid rgba(239,68,68,.22);
      box-shadow:0 16px 32px rgba(0,0,0,.25);
      padding:10px;
    }

    .ac-logout-btn{
      width:100%;
      min-height:46px;
      border:0;
      border-radius:17px;
      display:flex;
      align-items:center;
      justify-content:center;
      gap:9px;
      color:#ffdce2;
      background:rgba(255,255,255,.055);
      border:1px solid rgba(239,68,68,.16);
      font-size:12.5px;
      font-weight:850;
      cursor:pointer;
    }

    .ac-logout-btn svg{
      width:18px;
      height:18px;
    }

    /* =========================
       BOTTOM NAV SPACER ONLY
    ========================= */
    .rb-bottom-spacer{
      height:86px;
    }

    @media (max-width:370px){
      .ac-phone{
        padding-left:2px;
        padding-right:2px;
      }

      .ac-greeting h1{
        font-size:21px;
        max-width:230px;
      }

      .ac-wallet{
        padding:15px;
      }

      .ac-wallet-amount{
        font-size:28px;
      }

      .ac-vip-pill{
        min-width:68px;
        height:36px;
        font-size:11px;
      }

      .ac-quick{
        gap:7px;
      }

      .ac-quick-item{
        min-height:78px;
        border-radius:18px;
      }

      .ac-quick-icon{
        width:35px;
        height:35px;
        border-radius:14px;
      }

      .ac-quick-label{
        font-size:9.7px;
      }

      .ac-menu-item{
        padding:11px;
      }

      .ac-menu-icon{
        width:39px;
        height:39px;
        border-radius:15px;
      }
    }

    /* =========================
   COMING SOON POPUP - RUBIK THEME
========================= */
.ac-coming-overlay{
  position:fixed;
  inset:0;
  z-index:9999;
  display:none;
  align-items:center;
  justify-content:center;
  padding:18px;
  background:rgba(2,8,8,.68);
  backdrop-filter:blur(12px);
  -webkit-backdrop-filter:blur(12px);
}

.ac-coming-overlay.show{
  display:flex;
}

.ac-coming-modal{
  width:100%;
  max-width:360px;
  position:relative;
  overflow:hidden;
  border-radius:26px;
  background:
    radial-gradient(260px 150px at 100% 0%, rgba(0,223,130,.18), transparent 62%),
    radial-gradient(220px 130px at 0% 100%, rgba(90,140,255,.15), transparent 60%),
    linear-gradient(180deg, rgba(13,35,34,.98), rgba(3,15,15,.98));
  border:1px solid rgba(255,255,255,.11);
  box-shadow:
    0 30px 80px rgba(0,0,0,.52),
    0 0 0 1px rgba(0,223,130,.08) inset,
    0 0 42px rgba(0,223,130,.10);
  padding:20px;
  animation:acComingIn .24s ease both;
}

.ac-coming-modal::before{
  content:"";
  position:absolute;
  inset:0;
  background:
    linear-gradient(145deg, rgba(255,255,255,.10), transparent 34%),
    radial-gradient(circle at 20% 0%, rgba(255,255,255,.08), transparent 34%);
  pointer-events:none;
}

.ac-coming-icon{
  position:relative;
  z-index:1;
  width:58px;
  height:58px;
  margin:0 auto 14px;
  border-radius:21px;
  display:grid;
  place-items:center;
  color:#06110e;
  background:
    radial-gradient(circle at 30% 0%, rgba(255,255,255,.62), transparent 34%),
    linear-gradient(135deg, #00DF82, #72ffab);
  box-shadow:
    0 18px 34px rgba(0,223,130,.22),
    inset 0 1px 0 rgba(255,255,255,.32);
}

.ac-coming-icon svg{
  width:28px;
  height:28px;
}

.ac-coming-title{
  position:relative;
  z-index:1;
  margin:0;
  color:#ffffff;
  font-size:20px;
  line-height:1.15;
  font-weight:900;
  text-align:center;
  letter-spacing:-.04em;
}

.ac-coming-text{
  position:relative;
  z-index:1;
  margin:9px auto 18px;
  max-width:280px;
  color:rgba(214,255,240,.68);
  font-size:13px;
  line-height:1.5;
  font-weight:650;
  text-align:center;
}

.ac-coming-actions{
  position:relative;
  z-index:1;
  display:grid;
  grid-template-columns:1fr;
  gap:10px;
}

.ac-coming-btn{
  width:100%;
  min-height:46px;
  border:0;
  border-radius:999px;
  cursor:pointer;
  display:flex;
  align-items:center;
  justify-content:center;
  color:#06110e;
  background:
    radial-gradient(circle at 30% 0%, rgba(255,255,255,.55), transparent 34%),
    linear-gradient(135deg, #00DF82, #72ffab);
  box-shadow:
    0 16px 32px rgba(0,223,130,.20),
    inset 0 1px 0 rgba(255,255,255,.28);
  font-size:13px;
  font-weight:900;
}

.ac-coming-close{
  position:absolute;
  top:12px;
  right:12px;
  z-index:2;
  width:34px;
  height:34px;
  border-radius:13px;
  border:1px solid rgba(255,255,255,.10);
  background:rgba(255,255,255,.06);
  color:#ffffff;
  display:grid;
  place-items:center;
  cursor:pointer;
}

.ac-coming-close svg{
  width:18px;
  height:18px;
}

@keyframes acComingIn{
  from{
    opacity:0;
    transform:translateY(12px) scale(.96);
  }
  to{
    opacity:1;
    transform:translateY(0) scale(1);
  }
}
  </style>
</head>

<body>
  <main class="ac-page">
    <div class="ac-phone">

      {{-- HEADER --}}
      <header class="ac-header">
        <div class="ac-greeting">
          <span>Hello, {{ $user->name ?? 'Investor' }}!</span>
          <h1>Akun Rubik ✨</h1>
        </div>

        <div class="ac-header-actions">
          <a href="{{ url('/saldo/rincian') }}" class="ac-header-btn" aria-label="Rincian Saldo">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M4 7h16v10H4V7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              <path d="M8 11h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
              <path d="M16 13h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
            </svg>
          </a>

          <div class="ac-avatar" aria-hidden="true">
            {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
          </div>
        </div>
      </header>

      {{-- WALLET --}}
      <section class="ac-wallet">
        <div class="ac-wallet-inner">
          <div class="ac-wallet-top">
            <div>
              <p class="ac-wallet-label">Your Wallet Balance</p>
              <h2 class="ac-wallet-amount">Rp {{ number_format($totalAset, 0, ',', '.') }}</h2>

              <div class="ac-wallet-sub">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                  <path d="M4 15l5-5 4 4 7-8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M15 6h5v5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Rp {{ number_format($saldoUtama, 0, ',', '.') }}
                <span>Saldo utama</span>
              </div>
            </div>

            <div class="ac-vip-pill">
              VIP {{ $user->vip_level ?? 0 }}
            </div>
          </div>

          <div class="ac-wallet-actions">
            <a href="/deposit" class="ac-wallet-btn is-deposit">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M12 5v14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                <path d="M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              </svg>
              Deposit
            </a>

            <a href="/ui/withdrawals" class="ac-wallet-btn is-withdraw">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M12 4v13" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                <path d="M7 12l5 5 5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              Withdraw
            </a>
          </div>
        </div>
      </section>

      {{-- QUICK ACTION --}}
      <section class="ac-quick">
        <a href="{{ route('investasi.index') }}" class="ac-quick-item">
          <div class="ac-quick-icon">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M4 19V5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              <path d="M8 17V9" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              <path d="M12 17V7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              <path d="M16 17v-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              <path d="M20 17V4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
            </svg>
          </div>
          <div class="ac-quick-label">Investasi</div>
        </a>

        <a href="/ui/payout-accounts" class="ac-quick-item">
          <div class="ac-quick-icon">
            <svg viewBox="0 0 24 24" fill="none">
              <rect x="2.5" y="6" width="19" height="12" rx="2.2" stroke="currentColor" stroke-width="2"/>
              <path d="M2.5 10h19" stroke="currentColor" stroke-width="2"/>
            </svg>
          </div>
          <div class="ac-quick-label">Rekening</div>
        </a>

        <a href="{{ route('saldo.rincian') }}" class="ac-quick-item">
          <div class="ac-quick-icon">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M4 7h16v10H4V7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              <path d="M8 11h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
              <path d="M16 13h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
            </svg>
          </div>
          <div class="ac-quick-label">Saldo</div>
        </a>

        <a href="https://t.me/rubikcompany" target="_blank" rel="noopener" class="ac-quick-item">
          <div class="ac-quick-icon">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M22 2 11 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M22 2 15 22 11 13 2 9 22 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <div class="ac-quick-label">Grup</div>
        </a>
      </section>

      {{-- ID AKUN --}}
      <section class="ac-id-card">
        <div class="ac-id-left">
          <div class="ac-id-icon">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M20 21a8 8 0 0 0-16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"/>
            </svg>
          </div>

          <div class="ac-id-meta">
            <p>ID Member</p>
            <strong id="userIdText">{{ $user->id }}</strong>
          </div>
        </div>

        <button type="button" class="ac-copy-btn" id="copyIdBtn">Salin</button>
      </section>

      {{-- MENU --}}
      <section class="ac-section">
        <div class="ac-section-head">
          <div class="ac-section-title">
            <h2>Menu Akun</h2>
            <p>Kelola aktivitas dan informasi akun Anda.</p>
          </div>

          <div class="ac-section-hint">Rubik</div>
        </div>

        <div class="ac-menu-list">
          <a href="/deposit/history" class="ac-menu-item">
            <div class="ac-menu-left">
              <div class="ac-menu-icon">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M12 8v4l3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                </svg>
              </div>
              <div class="ac-menu-text">
                <strong>Riwayat Deposit</strong>
                <span>Lihat transaksi pengisian saldo</span>
              </div>
            </div>
            <div class="ac-menu-arrow">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
          </a>

          <a href="/withdraw/history" class="ac-menu-item">
            <div class="ac-menu-left">
              <div class="ac-menu-icon">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M12 4v13" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                  <path d="M7 12l5 5 5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <div class="ac-menu-text">
                <strong>Riwayat Penarikan</strong>
                <span>Status dan catatan withdraw</span>
              </div>
            </div>
            <div class="ac-menu-arrow">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
          </a>

          <a href="https://t.me/rubikcompany" target="_blank" rel="noopener" class="ac-menu-item">
            <div class="ac-menu-left">
              <div class="ac-menu-icon">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M21 15a4 4 0 0 1-4 4H7l-4 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                  <path d="M8 10h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  <path d="M8 14h5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
              </div>
              <div class="ac-menu-text">
                <strong>Layanan CS</strong>
                <span>Bantuan dan informasi layanan</span>
              </div>
            </div>
            <div class="ac-menu-arrow">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
          </a>

          <a href="/tentang" class="ac-menu-item">
            <div class="ac-menu-left">
              <div class="ac-menu-icon">
                <svg viewBox="0 0 24 24" fill="none">
                  <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                  <path d="M12 16h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                  <path d="M12 10a2 2 0 0 1 2 2c0 1-1 1.5-2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
              </div>
              <div class="ac-menu-text">
                <strong>Tentang Rubik</strong>
                <span>Informasi aplikasi dan layanan</span>
              </div>
            </div>
            <div class="ac-menu-arrow">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
          </a>

          <a href="javascript:void(0)" class="ac-menu-item" id="comingSoonBtn">
            <div class="ac-menu-left">
              <div class="ac-menu-icon">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M12 3v12" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                  <path d="M7 10l5 5 5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M5 21h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                </svg>
              </div>
              <div class="ac-menu-text">
                <strong>Unduh Aplikasi</strong>
                <span>Download versi aplikasi Rubik</span>
              </div>
            </div>
            <div class="ac-menu-arrow">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
          </a>
        </div>
      </section>

      {{-- LOGOUT --}}
      <section class="ac-logout">
        <form action="/logout" method="POST" style="margin:0;">
          @csrf
          <button class="ac-logout-btn" type="submit">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M16 17l5-5-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Keluar dari Akun
          </button>
        </form>
      </section>

      <div class="rb-bottom-spacer"></div>
    </div>
  </main>
<div class="ac-coming-overlay" id="comingSoonOverlay" role="dialog" aria-modal="true">
  <div class="ac-coming-modal">
    <button type="button" class="ac-coming-close" id="comingSoonClose" aria-label="Tutup">
      <svg viewBox="0 0 24 24" fill="none">
        <path d="M18 6 6 18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
        <path d="M6 6 18 18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
      </svg>
    </button>

    <div class="ac-coming-icon">
      <svg viewBox="0 0 24 24" fill="none">
        <path d="M12 3v12" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
        <path d="M7 10l5 5 5-5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M5 21h14" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
      </svg>
    </div>

    <h3 class="ac-coming-title">Fitur Mendatang</h3>

    <p class="ac-coming-text">
      Fitur unduh aplikasi Rubik sedang kami siapkan dan akan segera tersedia.
    </p>

    <div class="ac-coming-actions">
      <button type="button" class="ac-coming-btn" id="comingSoonOk">
        Oke, Mengerti
      </button>
    </div>
  </div>
</div>
  @include('partials.bottom-nav')

  <script>
    // Copy ID member
    (function(){
      const btn = document.getElementById('copyIdBtn');
      const txt = document.getElementById('userIdText');

      if(!btn || !txt) return;

      btn.addEventListener('click', async function(){
        const val = (txt.textContent || '').trim();
        const original = btn.textContent;

        try{
          await navigator.clipboard.writeText(val);
          btn.textContent = 'Tersalin';
          setTimeout(() => {
            btn.textContent = original;
          }, 1200);
        }catch(e){
          const ta = document.createElement('textarea');
          ta.value = val;
          document.body.appendChild(ta);
          ta.select();
          document.execCommand('copy');
          ta.remove();

          btn.textContent = 'Tersalin';
          setTimeout(() => {
            btn.textContent = original;
          }, 1200);
        }
      });
    })();
  </script>
<script>
  (function(){
    const btn = document.getElementById('comingSoonBtn');
    const overlay = document.getElementById('comingSoonOverlay');
    const closeBtn = document.getElementById('comingSoonClose');
    const okBtn = document.getElementById('comingSoonOk');

    if(!btn || !overlay) return;

    function openPopup(e){
      if(e) e.preventDefault();
      overlay.classList.add('show');
      document.body.style.overflow = 'hidden';
    }

    function closePopup(){
      overlay.classList.remove('show');
      document.body.style.overflow = '';
    }

    btn.addEventListener('click', openPopup);

    if(closeBtn) closeBtn.addEventListener('click', closePopup);
    if(okBtn) okBtn.addEventListener('click', closePopup);

    overlay.addEventListener('click', function(e){
      if(e.target === overlay){
        closePopup();
      }
    });

    document.addEventListener('keydown', function(e){
      if(e.key === 'Escape' && overlay.classList.contains('show')){
        closePopup();
      }
    });
  })();
</script>
</body>
</html>