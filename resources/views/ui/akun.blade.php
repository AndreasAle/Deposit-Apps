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

  $maskedId = $user ? str_pad((string) $user->id, 8, '0', STR_PAD_LEFT) : '00000000';
  $maskedIdView = '•••• ' . substr($maskedId, -4);
  $interestRate = data_get($user, 'interest_rate', '3.5');

  $nameParts = explode(' ', trim($user->name ?? 'User'));
  $initials  = mb_strtoupper(mb_substr($nameParts[0], 0, 1) . mb_substr($nameParts[1] ?? '', 0, 1));
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Profil | Velora Finance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --gold:#ffb52e;
      --gold2:#ffd45c;
      --purple:#7d3cff;
      --purple2:#c957ff;
      --violet:#d85cff;
      --maroon:#4a1218;
      --maroon2:#2b070c;
      --green:#18c97a;
      --danger:#ef4444;
      --ink:#26101a;
      --muted:#8d7a86;
      --line:rgba(38,16,26,.08);
      --shadow:0 20px 50px rgba(38,16,26,.10);
      --shadow-soft:0 10px 28px rgba(38,16,26,.065);
    }

    *{ box-sizing:border-box; }
    html,body{ min-height:100%; }

    body{
      margin:0;
      font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
      color:var(--ink);
      background:
        radial-gradient(600px 320px at 80% -100px, rgba(201,87,255,.12), transparent 64%),
        radial-gradient(400px 260px at 0% 8%, rgba(255,181,46,.11), transparent 58%),
        linear-gradient(180deg,#fdfcfe 0%,#f7f4fc 50%,#ede9f5 100%);
      overflow-x:hidden;
      -webkit-tap-highlight-color:transparent;
    }

    a{ color:inherit; text-decoration:none; }
    button{ font-family:inherit; }

    .vl-page{
      width:100%;
      min-height:100vh;
      display:flex;
      justify-content:center;
    }

    .vl-phone{
      width:100%;
      max-width:430px;
      min-height:100vh;
      padding:0 0 110px;
      position:relative;
    }

    /* ── HERO CARD ── */
    .vl-hero{
      position:relative;
      overflow:hidden;
      padding:52px 22px 28px;
      background:
        radial-gradient(380px 220px at 95% 0%, rgba(255,255,255,.22), transparent 58%),
        radial-gradient(280px 180px at 0% 100%, rgba(255,212,93,.22), transparent 54%),
        linear-gradient(145deg,#ffb52e 0%,#f07aff 42%,#7d3cff 100%);
      color:#fff;
    }

    .vl-hero::after{
      content:"";
      position:absolute;
      inset:0;
      pointer-events:none;
      background:linear-gradient(180deg, rgba(255,255,255,.18) 0%, transparent 40%);
    }

    .vl-hero > *{ position:relative; z-index:1; }

    /* back + settings row */
    .vl-hero-topbar{
      position:absolute;
      top:14px;
      left:14px;
      right:14px;
      z-index:2;
      display:flex;
      align-items:center;
      justify-content:space-between;
    }

    .vl-ghost-btn{
      width:38px;
      height:38px;
      border:0;
      border-radius:999px;
      background:rgba(255,255,255,.22);
      border:1px solid rgba(255,255,255,.32);
      backdrop-filter:blur(10px);
      -webkit-backdrop-filter:blur(10px);
      color:#fff;
      display:grid;
      place-items:center;
      cursor:pointer;
      transition:.18s ease;
    }

    .vl-ghost-btn:hover{ background:rgba(255,255,255,.32); }
    .vl-ghost-btn svg{ width:19px; height:19px; }

    /* avatar */
    .vl-profile-center{
      display:flex;
      flex-direction:column;
      align-items:center;
      gap:10px;
      text-align:center;
    }

    .vl-avatar-ring{
      width:78px;
      height:78px;
      border-radius:999px;
      padding:3px;
      background:linear-gradient(135deg, rgba(255,255,255,.90), rgba(255,212,93,.80), rgba(255,255,255,.60));
      box-shadow:0 14px 34px rgba(0,0,0,.18), 0 0 0 1px rgba(255,255,255,.28);
    }

    .vl-avatar-inner{
      width:100%;
      height:100%;
      border-radius:999px;
      background:
        radial-gradient(circle at 30% 22%, rgba(255,255,255,.72), transparent 40%),
        linear-gradient(135deg,#ffb52e,#d85cff,#7d3cff);
      display:grid;
      place-items:center;
      font-size:24px;
      font-weight:950;
      color:#fff;
      letter-spacing:-.03em;
      text-shadow:0 4px 12px rgba(0,0,0,.18);
    }

    .vl-profile-name{
      margin:0;
      font-size:20px;
      font-weight:950;
      line-height:1.1;
      letter-spacing:-.04em;
      color:#fff;
      text-shadow:0 6px 18px rgba(0,0,0,.14);
    }

    .vl-profile-id{
      display:inline-flex;
      align-items:center;
      gap:6px;
      min-height:26px;
      padding:0 12px;
      border-radius:999px;
      background:rgba(255,255,255,.18);
      border:1px solid rgba(255,255,255,.26);
      backdrop-filter:blur(8px);
      -webkit-backdrop-filter:blur(8px);
      color:rgba(255,255,255,.92);
      font-size:11px;
      font-weight:800;
      letter-spacing:.12em;
      cursor:pointer;
      transition:.18s ease;
    }

    .vl-profile-id:hover{ background:rgba(255,255,255,.28); }
    .vl-profile-id svg{ width:12px; height:12px; opacity:.8; }

    /* vip badge */
    .vl-vip-badge{
      display:inline-flex;
      align-items:center;
      gap:5px;
      min-height:24px;
      padding:0 10px;
      border-radius:999px;
      background:linear-gradient(135deg,#fffbe6,#ffe793);
      border:1px solid rgba(255,181,46,.40);
      color:#7a4a00;
      font-size:9.5px;
      font-weight:950;
      letter-spacing:.06em;
      text-transform:uppercase;
    }

    /* ── BALANCE FLOAT CARD ── */
    .vl-balance-card{
      margin:0 14px;
      margin-top:-28px;
      position:relative;
      z-index:10;
      border-radius:26px;
      background:
        radial-gradient(300px 140px at 96% 0%, rgba(201,87,255,.08), transparent 58%),
        rgba(255,255,255,.96);
      border:1px solid rgba(255,255,255,.88);
      box-shadow:0 22px 56px rgba(38,16,26,.12), inset 0 1px 0 rgba(255,255,255,.95);
      padding:20px;
      backdrop-filter:blur(18px);
      -webkit-backdrop-filter:blur(18px);
    }

    .vl-bal-label{
      display:flex;
      align-items:center;
      justify-content:space-between;
      margin-bottom:6px;
    }

    .vl-bal-label span{
      color:var(--muted);
      font-size:11.5px;
      font-weight:750;
    }

    .vl-bal-eye{
      width:30px;
      height:30px;
      border:0;
      border-radius:999px;
      background:rgba(125,60,255,.08);
      color:var(--purple);
      display:grid;
      place-items:center;
      cursor:pointer;
    }

    .vl-bal-eye svg{ width:16px; height:16px; }

    .vl-bal-amount{
      margin:0 0 16px;
      font-size:34px;
      font-weight:950;
      letter-spacing:-.045em;
      color:var(--ink);
      line-height:1;
    }

    .vl-bal-amount.hidden{
      letter-spacing:.1em;
      color:#c8bece;
    }

    .vl-bal-split{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:10px;
      padding-top:14px;
      border-top:1px solid var(--line);
    }

    .vl-bal-item span{
      display:block;
      color:var(--muted);
      font-size:10px;
      font-weight:750;
      margin-bottom:5px;
    }

    .vl-bal-item strong{
      display:block;
      color:var(--ink);
      font-size:12.5px;
      font-weight:950;
      letter-spacing:-.02em;
    }

    .vl-bal-divider{
      width:1px;
      background:var(--line);
      margin:0 -5px;
    }

    /* ── ACTION BUTTONS ── */
    .vl-actions{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:10px;
      margin:14px 14px 0;
    }

    .vl-btn{
      min-height:52px;
      border:0;
      border-radius:18px;
      display:flex;
      align-items:center;
      justify-content:center;
      gap:9px;
      font-size:13px;
      font-weight:900;
      letter-spacing:-.01em;
      cursor:pointer;
      transition:.18s ease;
      text-decoration:none;
    }

    .vl-btn:hover{ transform:translateY(-2px); filter:brightness(1.04); }
    .vl-btn svg{ width:17px; height:17px; }

    .vl-btn-gold{
      color:#4a2200;
      background:linear-gradient(135deg,#ffb52e,#ffd45c);
      box-shadow:0 14px 28px rgba(255,181,46,.36), inset 0 1px 0 rgba(255,255,255,.55);
    }

    .vl-btn-purple{
      color:#fff;
      background:linear-gradient(135deg,#7d3cff,#c957ff);
      box-shadow:0 14px 28px rgba(125,60,255,.36), inset 0 1px 0 rgba(255,255,255,.18);
    }

    /* ── STATS ROW ── */
    .vl-stats{
      display:grid;
      grid-template-columns:repeat(3,1fr);
      gap:10px;
      margin:14px 14px 0;
    }

    .vl-stat{
      border-radius:20px;
      background:rgba(255,255,255,.88);
      border:1px solid rgba(255,255,255,.80);
      box-shadow:var(--shadow-soft);
      padding:14px 12px;
      text-align:center;
    }

    .vl-stat-icon{
      width:36px;
      height:36px;
      border-radius:14px;
      display:grid;
      place-items:center;
      margin:0 auto 8px;
    }

    .vl-stat-icon svg{ width:18px; height:18px; }
    .vl-stat-icon.gold{ background:rgba(255,181,46,.14); color:#b87200; }
    .vl-stat-icon.purple{ background:rgba(125,60,255,.12); color:var(--purple); }
    .vl-stat-icon.green{ background:rgba(24,201,122,.12); color:#0d9155; }

    .vl-stat strong{
      display:block;
      color:var(--ink);
      font-size:13px;
      font-weight:950;
      line-height:1.15;
      letter-spacing:-.02em;
    }

    .vl-stat span{
      display:block;
      color:var(--muted);
      font-size:9.5px;
      font-weight:750;
      margin-top:3px;
    }

    /* ── BOOST BANNER ── */
    .vl-boost{
      margin:14px 14px 0;
      border-radius:22px;
      padding:14px 16px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      background:
        radial-gradient(200px 80px at 90% 50%, rgba(201,87,255,.30), transparent 62%),
        linear-gradient(135deg,#fff8e0,#fff,#f8eaff);
      border:1px solid rgba(201,87,255,.14);
      box-shadow:var(--shadow-soft);
    }

    .vl-boost-left{ display:flex; align-items:center; gap:11px; min-width:0; }

    .vl-boost-emoji{
      width:42px;
      height:42px;
      border-radius:16px;
      display:grid;
      place-items:center;
      flex:0 0 auto;
      background:linear-gradient(135deg,#ffd45c,#ffb52e);
      box-shadow:0 10px 22px rgba(255,181,46,.28);
      font-size:20px;
    }

    .vl-boost-copy h3{
      margin:0;
      color:var(--ink);
      font-size:13px;
      font-weight:900;
      line-height:1.2;
      letter-spacing:-.025em;
    }

    .vl-boost-copy p{
      margin:4px 0 0;
      color:var(--muted);
      font-size:10.5px;
      font-weight:700;
      line-height:1.3;
    }

    .vl-boost-pill{
      flex:0 0 auto;
      min-height:28px;
      padding:0 12px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      color:#fff;
      background:linear-gradient(135deg,#7d3cff,#c957ff);
      font-size:9.5px;
      font-weight:950;
      white-space:nowrap;
      box-shadow:0 10px 22px rgba(125,60,255,.26);
    }

    /* ── SECTION HEADING ── */
    .vl-section-label{
      margin:22px 14px 10px;
      font-size:13px;
      font-weight:900;
      letter-spacing:-.02em;
      color:#9a8a96;
      text-transform:uppercase;
      letter-spacing:.08em;
      font-size:10.5px;
    }

    /* ── MENU CARD ── */
    .vl-card{
      margin:0 14px;
      border-radius:24px;
      background:rgba(255,255,255,.94);
      border:1px solid rgba(255,255,255,.84);
      box-shadow:var(--shadow-soft);
      overflow:hidden;
    }

    .vl-card + .vl-card{ margin-top:10px; }

    .vl-menu-row{
      min-height:60px;
      padding:0 18px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:14px;
      border-bottom:1px solid var(--line);
      transition:.15s ease;
      text-decoration:none;
      color:inherit;
      cursor:pointer;
    }

    .vl-menu-row:last-child{ border-bottom:0; }
    .vl-menu-row:hover{ background:rgba(125,60,255,.035); }

    .vl-menu-left{
      display:flex;
      align-items:center;
      gap:13px;
      min-width:0;
    }

    .vl-menu-icon{
      width:38px;
      height:38px;
      border-radius:15px;
      display:grid;
      place-items:center;
      flex:0 0 auto;
    }

    .vl-menu-icon svg{ width:18px; height:18px; }

    .vl-menu-icon.purple{ background:rgba(125,60,255,.10); color:var(--purple); }
    .vl-menu-icon.gold{ background:rgba(255,181,46,.14); color:#b07000; }
    .vl-menu-icon.green{ background:rgba(24,201,122,.12); color:#0d9155; }
    .vl-menu-icon.blue{ background:rgba(56,132,255,.12); color:#2060dd; }
    .vl-menu-icon.pink{ background:rgba(232,74,100,.10); color:#c02040; }
    .vl-menu-icon.teal{ background:rgba(6,182,212,.10); color:#0682a0; }

    .vl-menu-copy{ min-width:0; }

    .vl-menu-title{
      font-size:14px;
      font-weight:800;
      color:var(--ink);
      line-height:1.1;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
    }

    .vl-menu-sub{
      margin-top:3px;
      color:var(--muted);
      font-size:10.5px;
      font-weight:700;
    }

    .vl-menu-badge{
      min-height:20px;
      padding:0 8px;
      border-radius:999px;
      background:rgba(125,60,255,.10);
      color:var(--purple);
      font-size:9.5px;
      font-weight:950;
      display:inline-flex;
      align-items:center;
    }

    .vl-menu-arrow{
      color:#d0c6ce;
      flex:0 0 auto;
      display:grid;
      place-items:center;
    }

    .vl-menu-arrow svg{ width:17px; height:17px; }

    /* ── LOGOUT ── */
    .vl-logout-wrap{
      margin:10px 14px 0;
      border-radius:22px;
      overflow:hidden;
      background:rgba(255,255,255,.90);
      border:1px solid rgba(255,255,255,.80);
      box-shadow:var(--shadow-soft);
      padding:10px;
    }

    .vl-logout-btn{
      width:100%;
      min-height:50px;
      border:0;
      border-radius:16px;
      display:flex;
      align-items:center;
      justify-content:center;
      gap:9px;
      color:#d12e4c;
      background:rgba(239,68,68,.08);
      border:1px solid rgba(239,68,68,.14);
      cursor:pointer;
      font-size:13.5px;
      font-weight:900;
      letter-spacing:-.01em;
      transition:.18s ease;
    }

    .vl-logout-btn:hover{ background:rgba(239,68,68,.14); transform:translateY(-1px); }
    .vl-logout-btn svg{ width:18px; height:18px; }

    /* ── APP VERSION ── */
    .vl-version{
      text-align:center;
      margin:18px 0 4px;
      color:var(--muted);
      font-size:10.5px;
      font-weight:700;
      opacity:.7;
    }

    /* ── TOAST ── */
    .vl-toast{
      position:fixed;
      left:50%;
      bottom:100px;
      transform:translateX(-50%) translateY(10px);
      z-index:10000;
      min-height:40px;
      padding:0 18px;
      border-radius:999px;
      display:flex;
      align-items:center;
      gap:8px;
      color:#fff;
      background:rgba(38,16,26,.88);
      backdrop-filter:blur(14px);
      -webkit-backdrop-filter:blur(14px);
      font-size:12px;
      font-weight:850;
      opacity:0;
      pointer-events:none;
      transition:.22s ease;
      box-shadow:0 18px 40px rgba(38,16,26,.20);
      white-space:nowrap;
    }

    .vl-toast.show{
      opacity:1;
      transform:translateX(-50%) translateY(0);
    }

    /* ── COMING SOON MODAL ── */
    .ac-overlay{
      position:fixed;
      inset:0;
      z-index:9999;
      display:none;
      align-items:center;
      justify-content:center;
      padding:20px;
      background:rgba(38,16,26,.40);
      backdrop-filter:blur(16px);
      -webkit-backdrop-filter:blur(16px);
    }

    .ac-overlay.show{ display:flex; }

    .ac-modal{
      width:100%;
      max-width:340px;
      border-radius:28px;
      background:
        radial-gradient(260px 130px at 96% 0%, rgba(201,87,255,.12), transparent 62%),
        rgba(255,255,255,.98);
      border:1px solid rgba(255,255,255,.72);
      box-shadow:0 32px 80px rgba(38,16,26,.22);
      padding:22px;
      text-align:center;
      animation:acIn .24s ease both;
      position:relative;
    }

    .ac-close{
      position:absolute;
      top:12px;
      right:12px;
      width:34px;
      height:34px;
      border-radius:13px;
      border:1px solid var(--line);
      background:#fbf8ff;
      color:var(--ink);
      display:grid;
      place-items:center;
      cursor:pointer;
    }

    .ac-close svg{ width:17px; height:17px; }

    .ac-icon{
      width:60px;
      height:60px;
      border-radius:22px;
      margin:0 auto 16px;
      display:grid;
      place-items:center;
      background:linear-gradient(135deg,#ffb52e,#ffd45c 28%,#c957ff 68%,#7d3cff);
      box-shadow:0 18px 36px rgba(125,60,255,.22);
      color:#fff;
    }

    .ac-icon svg{ width:28px; height:28px; }

    .ac-title{
      margin:0;
      font-size:20px;
      font-weight:950;
      letter-spacing:-.045em;
      color:var(--ink);
    }

    .ac-desc{
      margin:9px 0 20px;
      color:var(--muted);
      font-size:13px;
      line-height:1.5;
      font-weight:650;
    }

    .ac-ok{
      width:100%;
      min-height:46px;
      border:0;
      border-radius:999px;
      background:linear-gradient(135deg,#7d3cff,#c957ff);
      color:#fff;
      font-size:13px;
      font-weight:950;
      cursor:pointer;
      box-shadow:0 14px 28px rgba(125,60,255,.26);
    }

    @keyframes acIn{
      from{ opacity:0; transform:translateY(14px) scale(.96); }
      to{ opacity:1; transform:translateY(0) scale(1); }
    }

    /* ── BOTTOM NAV COMPAT ── */
    .rb-bottom-spacer{ height:94px; }
    .rb-bottom-nav{ background:rgba(255,255,255,.92)!important; border:1px solid rgba(38,16,26,.08)!important; box-shadow:0 -18px 40px rgba(38,16,26,.10), inset 0 1px 0 rgba(255,255,255,.84)!important; backdrop-filter:blur(22px)!important; -webkit-backdrop-filter:blur(22px)!important; }
    .rb-bottom-nav__item{ color:#aa8f9f!important; }
    .rb-bottom-nav__item:hover{ color:#2d1620!important; }
    .rb-bottom-nav__item.is-active{ color:#111!important; text-shadow:none!important; }
    .rb-bottom-nav__item.is-active .rb-bottom-nav__icon{ filter:drop-shadow(0 8px 12px rgba(125,60,255,.20)); }

    @media (max-width:370px){
      .vl-bal-amount{ font-size:28px; }
      .vl-stats{ grid-template-columns:repeat(3,1fr); gap:8px; }
      .vl-stat{ padding:12px 8px; }
      .vl-stat strong{ font-size:11px; }
      .vl-boost{ padding:12px 14px; }
    }

    @media (prefers-reduced-motion:reduce){
      *,*::before,*::after{ animation:none!important; transition:none!important; }
    }
  </style>
</head>

<body>
  <main class="vl-page">
    <div class="vl-phone">

      {{-- ── HERO ── --}}
      <div class="vl-hero">
        <div class="vl-hero-topbar">
          <button
            type="button"
            class="vl-ghost-btn"
            onclick="history.length > 1 ? history.back() : location.href='/'"
            aria-label="Kembali"
          >
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M15 18 9 12l6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>

          <a href="{{ route('saldo.rincian') }}" class="vl-ghost-btn" aria-label="Rincian saldo">
            <svg viewBox="0 0 24 24" fill="none">
              <circle cx="12" cy="12" r="1" fill="currentColor"/>
              <circle cx="19" cy="12" r="1" fill="currentColor"/>
              <circle cx="5" cy="12" r="1" fill="currentColor"/>
            </svg>
          </a>
        </div>

        <div class="vl-profile-center">
          <div class="vl-avatar-ring">
            <div class="vl-avatar-inner">{{ $initials }}</div>
          </div>

          <h1 class="vl-profile-name">{{ $user->name ?? 'Velora Member' }}</h1>

          <button
            type="button"
            class="vl-profile-id"
            id="copyIdBtn"
            data-real-id="{{ $user->id }}"
            id-text="{{ $maskedIdView }}"
            aria-label="Salin ID member"
          >
            <svg viewBox="0 0 24 24" fill="none">
              <rect x="9" y="9" width="10" height="10" rx="2" stroke="currentColor" stroke-width="2"/>
              <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span id="userIdDisplay">{{ $maskedIdView }}</span>
          </button>

          <div class="vl-vip-badge">
            ⭐ VIP {{ $user->vip_level ?? 0 }} Member
          </div>
        </div>
      </div>

      {{-- ── BALANCE CARD ── --}}
      <div class="vl-balance-card">
        <div class="vl-bal-label">
          <span>Total Aset</span>
          <button type="button" class="vl-bal-eye" id="toggleAmount" aria-label="Tampilkan saldo">
            <svg id="eyeIcon" viewBox="0 0 24 24" fill="none">
              <path d="M3 12s3.4-6 9-6 9 6 9 6-3.4 6-9 6-9-6-9-6Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
            </svg>
          </button>
        </div>

        <h2
          class="vl-bal-amount hidden"
          id="amountText"
          data-amount="Rp {{ number_format($totalAset, 0, ',', '.') }}"
        >••••••••</h2>

        <div class="vl-bal-split">
          <div class="vl-bal-item">
            <span>Saldo Utama</span>
            <strong>Rp {{ number_format($saldoUtama, 0, ',', '.') }}</strong>
          </div>
          <div class="vl-bal-divider"></div>
          <div class="vl-bal-item" style="padding-left:14px;">
            <span>Saldo Penarikan</span>
            <strong>Rp {{ number_format($saldoPenarikan, 0, ',', '.') }}</strong>
          </div>
        </div>
      </div>

      {{-- ── ACTION BUTTONS ── --}}
      <div class="vl-actions">
        <a href="/deposit" class="vl-btn vl-btn-gold">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M12 5v14" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"/>
            <path d="M5 12h14" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"/>
          </svg>
          Deposit
        </a>

        <a href="/ui/withdrawals" class="vl-btn vl-btn-purple">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M12 4v13" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"/>
            <path d="M7 12l5 5 5-5" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Withdraw
        </a>
      </div>

      {{-- ── STATS ── --}}
      <div class="vl-stats">
        <div class="vl-stat">
          <div class="vl-stat-icon gold">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </div>
          <strong>{{ $interestRate }}%</strong>
          <span>Bunga p.a.</span>
        </div>

        <div class="vl-stat">
          <div class="vl-stat-icon purple">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M8 7V6a3 3 0 0 1 3-3h2a3 3 0 0 1 3 3v1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M4 8h16a1.5 1.5 0 0 1 1.5 1.5v8A2.5 2.5 0 0 1 19 20H5a2.5 2.5 0 0 1-2.5-2.5v-8A1.5 1.5 0 0 1 4 8Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
          </div>
          <strong>VIP {{ $user->vip_level ?? 0 }}</strong>
          <span>Level</span>
        </div>

        <div class="vl-stat">
          <div class="vl-stat-icon green">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <strong>Aktif</strong>
          <span>Status</span>
        </div>
      </div>

      {{-- ── BOOST BANNER ── --}}
      <a href="{{ route('investasi.index') }}" class="vl-boost">
        <div class="vl-boost-left">
          <div class="vl-boost-emoji">💰</div>
          <div class="vl-boost-copy">
            <h3>Boost bunga kamu</h3>
            <p>Investasi Velora untuk yield lebih tinggi</p>
          </div>
        </div>
        <div class="vl-boost-pill">Up to 15%</div>
      </a>

      {{-- ── MENU AKUN ── --}}
      <div class="vl-section-label">Akun Saya</div>

      <div class="vl-card">
        <a href="{{ route('saldo.rincian') }}" class="vl-menu-row">
          <div class="vl-menu-left">
            <div class="vl-menu-icon purple">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M20 21a8 8 0 0 0-16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"/>
              </svg>
            </div>
            <div class="vl-menu-copy">
              <div class="vl-menu-title">Detail Akun</div>
              <div class="vl-menu-sub">Lihat info lengkap profil</div>
            </div>
          </div>
          <div class="vl-menu-arrow">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </a>

        <a href="{{ route('investasi.index') }}" class="vl-menu-row">
          <div class="vl-menu-left">
            <div class="vl-menu-icon gold">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              </svg>
            </div>
            <div class="vl-menu-copy">
              <div class="vl-menu-title">Portofolio</div>
              <div class="vl-menu-sub">Investasi aktif kamu</div>
            </div>
          </div>
          <div class="vl-menu-arrow">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </a>

        <a href="{{ route('referral.index') }}" class="vl-menu-row">
          <div class="vl-menu-left">
            <div class="vl-menu-icon green">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </div>
            <div class="vl-menu-copy">
              <div class="vl-menu-title">Referral</div>
              <div class="vl-menu-sub">Ajak teman, raih komisi</div>
            </div>
          </div>
          <div class="vl-menu-arrow">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </a>
      </div>

      {{-- ── MENU TRANSAKSI ── --}}
      <div class="vl-section-label">Transaksi</div>

      <div class="vl-card">
        <a href="/deposit/history" class="vl-menu-row">
          <div class="vl-menu-left">
            <div class="vl-menu-icon blue">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M12 5v14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                <path d="M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              </svg>
            </div>
            <div class="vl-menu-copy">
              <div class="vl-menu-title">Riwayat Deposit</div>
              <div class="vl-menu-sub">Semua transaksi masuk</div>
            </div>
          </div>
          <div class="vl-menu-arrow">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </a>

        <a href="/withdraw/history" class="vl-menu-row">
          <div class="vl-menu-left">
            <div class="vl-menu-icon purple">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M12 4v13" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                <path d="M7 12l5 5 5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <div class="vl-menu-copy">
              <div class="vl-menu-title">Riwayat Penarikan</div>
              <div class="vl-menu-sub">Semua transaksi keluar</div>
            </div>
          </div>
          <div class="vl-menu-arrow">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </a>

        <a href="/ui/payout-accounts" class="vl-menu-row">
          <div class="vl-menu-left">
            <div class="vl-menu-icon teal">
              <svg viewBox="0 0 24 24" fill="none">
                <rect x="2.5" y="6" width="19" height="12" rx="2.2" stroke="currentColor" stroke-width="2"/>
                <path d="M2.5 10h19" stroke="currentColor" stroke-width="2"/>
              </svg>
            </div>
            <div class="vl-menu-copy">
              <div class="vl-menu-title">Rekening Penarikan</div>
              <div class="vl-menu-sub">Kelola rekening bank</div>
            </div>
          </div>
          <div class="vl-menu-arrow">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </a>
      </div>

      {{-- ── MENU LAINNYA ── --}}
      <div class="vl-section-label">Lainnya</div>

      <div class="vl-card">
        <a href="https://t.me/velorafinance" target="_blank" rel="noopener noreferrer" class="vl-menu-row">
          <div class="vl-menu-left">
            <div class="vl-menu-icon green">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M21 15a4 4 0 0 1-4 4H7l-4 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                <path d="M8 10h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M8 14h5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </div>
            <div class="vl-menu-copy">
              <div class="vl-menu-title">Layanan CS</div>
              <div class="vl-menu-sub">Chat support via Telegram</div>
            </div>
          </div>
          <div class="vl-menu-arrow">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </a>

        <a href="/tentang" class="vl-menu-row">
          <div class="vl-menu-left">
            <div class="vl-menu-icon blue">
              <svg viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                <path d="M12 16h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                <path d="M12 10a2 2 0 0 1 2 2c0 1-1 1.5-2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </div>
            <div class="vl-menu-copy">
              <div class="vl-menu-title">Tentang Velora</div>
              <div class="vl-menu-sub">Versi 1.0 · Kebijakan privasi</div>
            </div>
          </div>
          <div class="vl-menu-arrow">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </a>

        <a href="javascript:void(0)" class="vl-menu-row" id="comingSoonBtn">
          <div class="vl-menu-left">
            <div class="vl-menu-icon pink">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M12 3v12" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                <path d="M7 10l5 5 5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M5 21h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              </svg>
            </div>
            <div class="vl-menu-copy">
              <div class="vl-menu-title">Unduh Aplikasi</div>
              <div class="vl-menu-sub">Segera hadir di App Store & Play</div>
            </div>
          </div>
          <div class="vl-menu-badge">Soon</div>
        </a>
      </div>

      {{-- ── LOGOUT ── --}}
      <div class="vl-logout-wrap">
        <form action="/logout" method="POST" style="margin:0;">
          @csrf
          <button class="vl-logout-btn" type="submit">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M16 17l5-5-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Keluar dari Akun
          </button>
        </form>
      </div>

      <div class="vl-version">Velora Finance v1.0 · &copy; 2025</div>

      <div class="rb-bottom-spacer"></div>
    </div>
  </main>

  <div class="vl-toast" id="copyToast">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
      <path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    ID member tersalin
  </div>

  <div class="ac-overlay" id="comingSoonOverlay" role="dialog" aria-modal="true">
    <div class="ac-modal">
      <button type="button" class="ac-close" id="comingSoonClose" aria-label="Tutup">
        <svg viewBox="0 0 24 24" fill="none">
          <path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
        </svg>
      </button>

      <div class="ac-icon">
        <svg viewBox="0 0 24 24" fill="none">
          <path d="M12 3v12" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
          <path d="M7 10l5 5 5-5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M5 21h14" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
        </svg>
      </div>

      <h3 class="ac-title">Fitur Mendatang</h3>
      <p class="ac-desc">Aplikasi Velora versi mobile sedang disiapkan dan akan segera tersedia di App Store & Google Play.</p>
      <button type="button" class="ac-ok" id="comingSoonOk">Oke, Mengerti</button>
    </div>
  </div>

  @include('partials.bottom-nav')

  <script>
    (function(){
      const copyBtn   = document.getElementById('copyIdBtn');
      const toast     = document.getElementById('copyToast');
      const amountBtn = document.getElementById('toggleAmount');
      const amountEl  = document.getElementById('amountText');
      const eyeIcon   = document.getElementById('eyeIcon');
      let shown = false;

      function showToast(msg){
        if(!toast) return;
        toast.querySelector('span') && (toast.lastChild.textContent = ' ' + msg);
        toast.classList.add('show');
        clearTimeout(window.__vlToast);
        window.__vlToast = setTimeout(() => toast.classList.remove('show'), 1400);
      }

      async function copyText(val){
        try{ await navigator.clipboard.writeText(val); }
        catch(e){
          const t = document.createElement('textarea');
          t.value = val;
          t.style.cssText = 'position:fixed;opacity:0;';
          document.body.appendChild(t);
          t.select();
          document.execCommand('copy');
          t.remove();
        }
      }

      if(copyBtn){
        copyBtn.addEventListener('click', async function(){
          await copyText(this.dataset.realId || this.textContent.trim());
          showToast('ID member tersalin');
        });
      }

      if(amountBtn && amountEl){
        amountBtn.addEventListener('click', function(){
          shown = !shown;
          if(shown){
            amountEl.textContent = amountEl.dataset.amount;
            amountEl.classList.remove('hidden');
            eyeIcon.innerHTML = `
              <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M1 1l22 22" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>`;
          } else {
            amountEl.textContent = '••••••••';
            amountEl.classList.add('hidden');
            eyeIcon.innerHTML = `
              <path d="M3 12s3.4-6 9-6 9 6 9 6-3.4 6-9 6-9-6-9-6Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>`;
          }
        });
      }

      const btn     = document.getElementById('comingSoonBtn');
      const overlay = document.getElementById('comingSoonOverlay');
      const closeBtn= document.getElementById('comingSoonClose');
      const okBtn   = document.getElementById('comingSoonOk');

      function openPopup(e){ if(e) e.preventDefault(); overlay.classList.add('show'); document.body.style.overflow='hidden'; }
      function closePopup(){ overlay.classList.remove('show'); document.body.style.overflow=''; }

      if(btn) btn.addEventListener('click', openPopup);
      if(closeBtn) closeBtn.addEventListener('click', closePopup);
      if(okBtn) okBtn.addEventListener('click', closePopup);
      if(overlay) overlay.addEventListener('click', e => e.target === overlay && closePopup());
      document.addEventListener('keydown', e => e.key === 'Escape' && overlay.classList.contains('show') && closePopup());
    })();
  </script>
</body>
</html>
