@include('partials.anti-inspect')
@php
  $type = $type ?? request('type', 'all');
  $activities = $activities ?? collect();

  $totalActivity = method_exists($activities, 'total') ? $activities->total() : $activities->count();

  $totalDeposit = collect($activities instanceof \Illuminate\Pagination\AbstractPaginator ? $activities->items() : $activities)
    ->filter(fn($a) => ($a->activity_type ?? '') === 'deposit')
    ->sum(fn($a) => (int) ($a->amount ?? 0));

  $totalInvestment = collect($activities instanceof \Illuminate\Pagination\AbstractPaginator ? $activities->items() : $activities)
    ->filter(fn($a) => ($a->activity_type ?? '') !== 'deposit')
    ->sum(fn($a) => (int) ($a->amount ?? 0));
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Rincian Saldo | Velora Finance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">

  <style>
    :root{
      --vl-bg:#f7f2fa;
      --vl-bg2:#efe8f7;
      --vl-paper:#ffffff;
      --vl-paper2:#fbf8ff;
      --vl-text:#2b0b16;
      --vl-maroon:#3a0712;
      --vl-soft:#7b6370;
      --vl-muted:#a894a0;
      --vl-border:rgba(43,11,22,.085);
      --vl-border2:rgba(43,11,22,.14);
      --vl-gold:#f5af2a;
      --vl-gold2:#ffd46d;
      --vl-purple:#8f57ff;
      --vl-violet:#b45cff;
      --vl-pink:#d96bff;
      --vl-red:#e24a64;
      --vl-green:#20b873;
      --vl-gradient:linear-gradient(135deg,#f5af2a 0%,#ffd46d 24%,#d96bff 58%,#8f57ff 100%);
      --vl-gradient-soft:linear-gradient(145deg,#8f57ff 0%,#9f55ff 40%,#d96bff 72%,#f5af2a 100%);
      --vl-shadow:0 24px 58px rgba(88,43,145,.16);
      --vl-shadow-soft:0 14px 34px rgba(43,11,22,.075);
    }

    *{box-sizing:border-box}
    html,body{min-height:100%}

    body{
      margin:0;
      font-family:"Plus Jakarta Sans",Inter,system-ui,-apple-system,"Segoe UI",sans-serif;
      color:var(--vl-text);
      background:
        radial-gradient(680px 360px at 50% -150px, rgba(245,175,42,.23), transparent 64%),
        radial-gradient(520px 340px at 100% 4%, rgba(217,107,255,.18), transparent 62%),
        radial-gradient(520px 330px at -12% 34%, rgba(143,87,255,.13), transparent 58%),
        linear-gradient(180deg,#fff 0%,#f7f2fa 44%,#eee8f6 100%);
      overflow-x:hidden;
      -webkit-tap-highlight-color:transparent;
    }

    body::before{
      content:"";
      position:fixed;
      inset:0;
      pointer-events:none;
      background:
        linear-gradient(rgba(43,11,22,.026) 1px, transparent 1px),
        linear-gradient(90deg, rgba(43,11,22,.018) 1px, transparent 1px);
      background-size:32px 32px;
      mask-image:linear-gradient(180deg, rgba(0,0,0,.46), transparent 76%);
      -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.46), transparent 76%);
      opacity:.55;
      z-index:0;
    }

    a{color:inherit;text-decoration:none}
    button{font-family:inherit}

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
      padding:8px 4px 106px;
    }

    .sd-topbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:16px;
      padding:0 2px;
    }

    .sd-brand{
      display:flex;
      align-items:center;
      gap:12px;
      min-width:0;
    }

    .sd-logo-card{
      width:52px;
      height:52px;
      border-radius:19px;
      display:grid;
      place-items:center;
      overflow:hidden;
      background:
        radial-gradient(circle at 28% 8%, rgba(255,255,255,.98), rgba(255,226,155,.78) 34%, rgba(225,188,255,.76) 92%),
        var(--vl-gradient);
      border:1px solid rgba(255,255,255,.68);
      box-shadow:0 16px 34px rgba(88,43,145,.13), 0 8px 22px rgba(245,175,42,.10), inset 0 1px 0 rgba(255,255,255,.72);
      flex:0 0 auto;
    }

    .sd-logo-card img{
      width:46px;
      height:46px;
      object-fit:contain;
      display:block;
    }

    .sd-title{min-width:0}

    .sd-title span{
      display:block;
      margin-bottom:6px;
      color:rgba(58,7,18,.58);
      font-size:10px;
      line-height:1;
      font-weight:800;
      letter-spacing:.18em;
      text-transform:uppercase;
    }

    .sd-title h1{
      margin:0;
      color:var(--vl-maroon);
      font-size:23px;
      line-height:1;
      font-weight:800;
      letter-spacing:-.052em;
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
      border:1px solid rgba(43,11,22,.08);
      background:rgba(255,255,255,.84);
      color:#5b2841;
      display:grid;
      place-items:center;
      box-shadow:0 12px 26px rgba(43,11,22,.065), inset 0 1px 0 rgba(255,255,255,.92);
      backdrop-filter:blur(18px);
      -webkit-backdrop-filter:blur(18px);
      transition:.18s ease;
    }

    .sd-header-btn:hover{
      transform:translateY(-1px);
      color:var(--vl-purple);
    }

    .sd-header-btn svg{width:20px;height:20px}

    .sd-hero{
      position:relative;
      overflow:hidden;
      border-radius:34px;
      background:
        radial-gradient(360px 220px at 92% -12%, rgba(255,212,109,.48), transparent 58%),
        radial-gradient(300px 200px at 2% 8%, rgba(217,107,255,.34), transparent 62%),
        linear-gradient(145deg,#8f57ff 0%,#9455ff 40%,#d96bff 72%,#f5af2a 100%);
      color:#fff;
      border:1px solid rgba(255,255,255,.44);
      box-shadow:0 28px 62px rgba(143,87,255,.22), 0 18px 42px rgba(245,175,42,.10), inset 0 1px 0 rgba(255,255,255,.22);
    }

    .sd-hero::before{
      content:"";
      position:absolute;
      inset:0;
      pointer-events:none;
      background:linear-gradient(135deg, rgba(255,255,255,.22), transparent 34%), radial-gradient(circle at 82% 26%, rgba(255,255,255,.16), transparent 28%), linear-gradient(180deg, transparent 0%, rgba(43,11,22,.08) 100%);
    }

    .sd-hero::after{
      content:"";
      position:absolute;
      right:-68px;
      bottom:-86px;
      width:240px;
      height:240px;
      border-radius:50%;
      background:linear-gradient(135deg, rgba(255,212,109,.46), rgba(217,107,255,.25));
      filter:blur(18px);
      pointer-events:none;
    }

    .sd-hero-inner{
      position:relative;
      z-index:1;
      padding:18px;
    }

    .sd-hero-head{
      display:grid;
      grid-template-columns:minmax(0,1fr) auto;
      gap:14px;
      align-items:start;
    }

    .sd-hero-label{
      margin:0 0 8px;
      color:rgba(255,255,255,.74);
      font-size:12px;
      font-weight:700;
    }

    .sd-hero-title{
      margin:0;
      color:#fff;
      font-size:31px;
      line-height:1.02;
      letter-spacing:-.075em;
      font-weight:800;
      text-shadow:0 12px 24px rgba(43,11,22,.22);
    }

    .sd-hero-sub{
      margin-top:12px;
      display:inline-flex;
      align-items:center;
      gap:7px;
      color:#2c1200;
      font-size:11.5px;
      font-weight:800;
      padding:8px 12px;
      border-radius:999px;
      background:linear-gradient(135deg,#ffe08a 0%,#f5af2a 100%);
      border:1px solid rgba(255,255,255,.30);
      box-shadow:0 12px 22px rgba(245,175,42,.24), inset 0 1px 0 rgba(255,255,255,.38);
    }

    .sd-hero-sub span{
      color:rgba(44,18,0,.68);
      font-weight:700;
    }

    .sd-hero-pill{
      min-width:82px;
      height:40px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:6px;
      color:#2b0b16;
      background:radial-gradient(circle at 24% 0%, rgba(255,255,255,.72), transparent 40%), linear-gradient(135deg,#ffd46d 0%,#d96bff 56%,#8f57ff 100%);
      border:1px solid rgba(255,255,255,.40);
      box-shadow:0 12px 24px rgba(143,87,255,.18), inset 0 1px 0 rgba(255,255,255,.40);
      font-size:12px;
      font-weight:800;
      white-space:nowrap;
    }

    .sd-hero-pill svg{width:15px;height:15px}

    .sd-hero-actions{
      margin-top:16px;
      display:grid;
      grid-template-columns:repeat(3,1fr);
      gap:9px;
    }

    .sd-hero-action{
      min-height:56px;
      border-radius:20px;
      border:1px solid rgba(255,255,255,.13);
      background:rgba(255,255,255,.10);
      color:#fff;
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      gap:6px;
      font-size:10.5px;
      font-weight:800;
      box-shadow:inset 0 1px 0 rgba(255,255,255,.09);
      transition:.18s ease;
    }

    .sd-hero-action:hover{transform:translateY(-1px)}
    .sd-hero-action svg{width:19px;height:19px;color:#ffd46d}

    .sd-summary{
      margin-top:14px;
      display:grid;
      grid-template-columns:repeat(3,minmax(0,1fr));
      gap:9px;
    }

    .sd-summary-card{
      min-height:78px;
      border-radius:22px;
      padding:12px;
      background:linear-gradient(180deg,rgba(255,255,255,.96),rgba(255,255,255,.86));
      border:1px solid rgba(43,11,22,.075);
      box-shadow:var(--vl-shadow-soft), inset 0 1px 0 rgba(255,255,255,.95);
      position:relative;
      overflow:hidden;
    }

    .sd-summary-card::after{
      content:"";
      position:absolute;
      left:12px;
      right:12px;
      bottom:0;
      height:3px;
      border-radius:999px 999px 0 0;
      background:linear-gradient(90deg,var(--sum-a),transparent 80%);
    }

    .sd-summary-card:nth-child(1){--sum-a:#f5af2a}
    .sd-summary-card:nth-child(2){--sum-a:#d96bff}
    .sd-summary-card:nth-child(3){--sum-a:#8f57ff}

    .sd-summary-card span{
      display:block;
      color:var(--vl-soft);
      font-size:9.6px;
      font-weight:700;
      line-height:1.2;
    }

    .sd-summary-card strong{
      display:block;
      margin-top:10px;
      color:var(--vl-maroon);
      font-size:12px;
      line-height:1.15;
      font-weight:800;
      letter-spacing:-.03em;
    }

    .sd-summary-card strong.is-plus{color:var(--vl-green)}
    .sd-summary-card strong.is-minus{color:var(--vl-red)}

    .sd-section{margin-top:22px}

    .sd-section-head{
      display:flex;
      align-items:flex-end;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
      padding:0 2px;
    }

    .sd-section-title h2{
      margin:0;
      color:var(--vl-maroon);
      font-size:18px;
      line-height:1.12;
      font-weight:800;
      letter-spacing:-.042em;
    }

    .sd-section-title p{
      margin:6px 0 0;
      color:var(--vl-soft);
      font-size:11px;
      font-weight:600;
    }

    .sd-filter{width:100%;overflow:hidden;margin-bottom:14px}
    .sd-chips{display:flex;gap:8px;overflow-x:auto;overflow-y:hidden;padding:2px 2px 7px;scrollbar-width:none;-webkit-overflow-scrolling:touch}
    .sd-chips::-webkit-scrollbar{display:none}

    .sd-chip{
      flex:0 0 auto;
      min-width:86px;
      min-height:40px;
      padding:0 15px;
      border-radius:999px;
      border:1px solid rgba(43,11,22,.075);
      background:rgba(255,255,255,.92);
      color:var(--vl-soft);
      font-size:11px;
      font-weight:800;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      box-shadow:0 10px 22px rgba(43,11,22,.055), inset 0 1px 0 rgba(255,255,255,.9);
      transition:.18s ease;
      white-space:nowrap;
    }

    .sd-chip:hover{transform:translateY(-1px);color:var(--vl-maroon)}

    .sd-chip.is-active{
      color:#2c1200;
      border-color:rgba(255,255,255,.72);
      background:radial-gradient(circle at 22% 0%, rgba(255,255,255,.78), transparent 40%), linear-gradient(135deg,#ffd46d,#d96bff);
      box-shadow:0 16px 30px rgba(180,92,255,.17), inset 0 1px 0 rgba(255,255,255,.38);
    }

    .sd-list{display:flex;flex-direction:column;gap:10px}

    .sd-row{
      position:relative;
      overflow:hidden;
      border-radius:27px;
      background:radial-gradient(210px 120px at 100% 0%, var(--row-glow), transparent 64%), linear-gradient(180deg,rgba(255,255,255,.98),rgba(255,255,255,.90));
      border:1px solid rgba(43,11,22,.075);
      box-shadow:var(--vl-shadow-soft), inset 0 1px 0 rgba(255,255,255,.94);
      transition:.18s ease;
      animation:sdFadeUp .42s ease both;
    }

    .sd-row::before{
      content:"";
      position:absolute;
      inset:0;
      pointer-events:none;
      background:linear-gradient(135deg, rgba(255,255,255,.82), transparent 30%), radial-gradient(circle at 12% 0%, var(--row-glow-soft), transparent 44%);
      opacity:.86;
    }

    .sd-row:hover{
      transform:translateY(-1px);
      border-color:rgba(143,87,255,.16);
      box-shadow:0 18px 38px rgba(43,11,22,.095), 0 0 0 4px rgba(143,87,255,.04);
    }

    .sd-row.is-deposit{
      --row-a:#f5af2a;
      --row-b:#ffd46d;
      --row-glow:rgba(245,175,42,.16);
      --row-glow-soft:rgba(245,175,42,.10);
      --row-amount:#20b873;
    }

    .sd-row.is-investment{
      --row-a:#d96bff;
      --row-b:#8f57ff;
      --row-glow:rgba(143,87,255,.14);
      --row-glow-soft:rgba(143,87,255,.08);
      --row-amount:#e24a64;
    }

    .sd-row-inner{
      position:relative;
      z-index:1;
      min-height:82px;
      display:grid;
      grid-template-columns:46px minmax(0,1fr) auto;
      align-items:center;
      gap:11px;
      padding:13px 12px;
    }

    .sd-icon{
      width:44px;
      height:44px;
      border-radius:17px;
      display:grid;
      place-items:center;
      color:#fff;
      background:linear-gradient(135deg,var(--row-a),var(--row-b));
      border:1px solid rgba(255,255,255,.62);
      box-shadow:0 12px 24px var(--row-glow), inset 0 1px 0 rgba(255,255,255,.25);
    }

    .sd-icon svg{width:22px;height:22px}
    .sd-row-info{min-width:0}

    .sd-row-title{
      margin:0;
      color:var(--vl-maroon);
      font-size:13.4px;
      line-height:1.18;
      font-weight:800;
      letter-spacing:-.026em;
      display:-webkit-box;
      -webkit-line-clamp:2;
      -webkit-box-orient:vertical;
      overflow:hidden;
    }

    .sd-row-meta{
      margin:5px 0 0;
      color:var(--vl-soft);
      font-size:10.4px;
      font-weight:600;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width:205px;
    }

    .sd-row-right{
      text-align:right;
      min-width:92px;
      display:flex;
      flex-direction:column;
      align-items:flex-end;
      gap:7px;
    }

    .sd-amount{
      color:var(--row-amount);
      font-size:11.8px;
      line-height:1.1;
      letter-spacing:-.025em;
      font-weight:800;
      white-space:nowrap;
    }

    .sd-badge{
      min-height:22px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      padding:0 8px;
      border-radius:999px;
      color:#fff;
      background:linear-gradient(135deg,var(--row-a),var(--row-b));
      font-size:9.4px;
      line-height:1;
      font-weight:800;
      white-space:nowrap;
      box-shadow:0 8px 18px var(--row-glow);
    }

    .sd-empty{
      min-height:350px;
      border-radius:28px;
      padding:24px 16px;
      background:radial-gradient(260px 140px at 50% 0%, rgba(217,107,255,.14), transparent 62%), radial-gradient(260px 140px at 90% 100%, rgba(245,175,42,.12), transparent 64%), rgba(255,255,255,.92);
      border:1px dashed rgba(43,11,22,.15);
      box-shadow:var(--vl-shadow-soft);
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      gap:13px;
      text-align:center;
      animation:sdFadeUp .42s ease both;
    }

    .sd-empty-icon{
      width:78px;
      height:78px;
      border-radius:27px;
      display:grid;
      place-items:center;
      color:#2c1200;
      background:radial-gradient(circle at 30% 0%, rgba(255,255,255,.62), transparent 34%), var(--vl-gradient);
      box-shadow:0 18px 34px rgba(143,87,255,.18), inset 0 1px 0 rgba(255,255,255,.32);
    }

    .sd-empty-icon svg{width:36px;height:36px}

    .sd-empty h3{
      margin:0;
      color:var(--vl-maroon);
      font-size:18px;
      line-height:1.15;
      letter-spacing:-.035em;
      font-weight:800;
    }

    .sd-empty p{
      margin:0;
      max-width:320px;
      color:var(--vl-soft);
      font-size:12px;
      line-height:1.55;
      font-weight:600;
    }

    .sd-empty-actions{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:9px;
      width:100%;
      margin-top:4px;
    }

    .sd-btn{
      min-height:44px;
      border-radius:999px;
      border:1px solid rgba(43,11,22,.08);
      background:#fff;
      color:var(--vl-soft);
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:7px;
      font-size:12px;
      font-weight:800;
      box-shadow:0 10px 20px rgba(43,11,22,.06), inset 0 1px 0 rgba(255,255,255,.9);
      transition:.18s ease;
    }

    .sd-btn:hover{transform:translateY(-1px)}

    .sd-btn.is-primary{
      color:#2c1200;
      background:linear-gradient(135deg,#ffe08a 0%,#f5af2a 100%);
      box-shadow:0 14px 26px rgba(245,175,42,.20);
    }

    .sd-btn svg{width:16px;height:16px}

    .sd-pager{
      margin-top:12px;
      border-radius:20px;
      border:1px solid rgba(43,11,22,.075);
      background:rgba(255,255,255,.92);
      padding:10px 12px;
      overflow:auto;
      color:var(--vl-maroon);
      box-shadow:var(--vl-shadow-soft);
    }

    .sd-pager *{font-size:12px}
    .sd-pager a{color:var(--vl-purple)!important;font-weight:800;text-decoration:none}

    .rb-bottom-spacer{height:94px!important}

    .rb-bottom-nav{
      background:rgba(255,255,255,.92)!important;
      border:1px solid rgba(43,11,22,.08)!important;
      box-shadow:0 -18px 40px rgba(43,11,22,.10), inset 0 1px 0 rgba(255,255,255,.84)!important;
      backdrop-filter:blur(22px)!important;
      -webkit-backdrop-filter:blur(22px)!important;
    }

    .rb-bottom-nav__item{color:#aa8f9f!important}
    .rb-bottom-nav__item:hover{color:#5b2841!important}
    .rb-bottom-nav__item.is-active{color:#3a0712!important;text-shadow:none!important}
    .rb-bottom-nav__item.is-active .rb-bottom-nav__icon{filter:drop-shadow(0 8px 12px rgba(143,87,255,.18))}

    @keyframes sdFadeUp{
      from{opacity:0;transform:translateY(10px)}
      to{opacity:1;transform:translateY(0)}
    }

    @media(max-width:370px){
      .sd-logo-card{width:45px;height:45px;border-radius:16px}
      .sd-logo-card img{width:39px;height:39px}
      .sd-title h1{font-size:21px}
      .sd-hero-inner{padding:16px}
      .sd-hero-title{font-size:26px}
      .sd-hero-pill{min-width:74px;height:37px;font-size:11px}
      .sd-summary{gap:7px}
      .sd-summary-card{min-height:74px;padding:10px}
      .sd-row-inner{grid-template-columns:42px minmax(0,1fr);align-items:start;gap:9px;padding:12px 10px}
      .sd-icon{width:40px;height:40px;border-radius:15px}
      .sd-row-right{grid-column:2/3;flex-direction:row;justify-content:space-between;align-items:center;width:100%;text-align:left;min-width:0}
      .sd-row-meta{max-width:240px}
      .sd-empty-actions{grid-template-columns:1fr}
    }
  </style>
</head>

<body>
  <main class="sd-page">
    <div class="sd-phone">

      <header class="sd-topbar">
        <div class="sd-brand">
          <div class="sd-logo-card">
            <img src="{{ asset('logo.png') }}" alt="Velora Finance">
          </div>

          <div class="sd-title">
            <span>Velora Balance</span>
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

      <section class="sd-hero">
        <div class="sd-hero-inner">
          <div class="sd-hero-head">
            <div>
              <p class="sd-hero-label">Aktivitas Transaksi</p>
              <h2 class="sd-hero-title">Riwayat Saldo</h2>

              <div class="sd-hero-sub">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                  <path d="M4 15l5-5 4 4 7-8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M15 6h5v5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                {{ number_format($totalActivity, 0, ',', '.') }} Aktivitas
                <span>tercatat</span>
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
            <a href="{{ route('saldo.rincian', ['type' => 'all']) }}" class="sd-hero-action">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              </svg>
              Semua
            </a>

            <a href="{{ route('saldo.rincian', ['type' => 'deposit']) }}" class="sd-hero-action">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M12 5v14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                <path d="M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              </svg>
              Deposit
            </a>

            <a href="{{ route('saldo.rincian', ['type' => 'investment']) }}" class="sd-hero-action">
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

      <section class="sd-summary">
        <div class="sd-summary-card">
          <span>Total Aktivitas</span>
          <strong>{{ number_format($totalActivity, 0, ',', '.') }} Data</strong>
        </div>

        <div class="sd-summary-card">
          <span>Deposit</span>
          <strong class="is-plus">+Rp {{ number_format($totalDeposit, 0, ',', '.') }}</strong>
        </div>

        <div class="sd-summary-card">
          <span>Investasi</span>
          <strong class="is-minus">-Rp {{ number_format($totalInvestment, 0, ',', '.') }}</strong>
        </div>
      </section>

      <section class="sd-section" aria-label="List Aktivitas">
        <div class="sd-section-head">
          <div class="sd-section-title">
            <h2>Daftar Aktivitas</h2>
            <p>Deposit dan pembelian investasi tercatat otomatis</p>
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
              $title = $isDeposit ? 'Isi ulang saldo' : 'Pembelian investasi';
              $date = \Carbon\Carbon::parse($a->happened_at)->format('d M Y • H:i');

              $sub = $isDeposit
                ? ($a->method ? "Metode: {$a->method} • Ref: {$a->ref}" : "Ref: {$a->ref}")
                : (($a->product_name ? "Produk: {$a->product_name}" : "Produk investasi") . " • ID: {$a->ref}");

              $badge = $isDeposit ? ($a->status ?? 'PAID') : ($a->status ?? 'ACTIVE');
            @endphp

            <article class="sd-row {{ $isDeposit ? 'is-deposit' : 'is-investment' }}" style="animation-delay: {{ $loop->index * 0.045 }}s;">
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
              <p>Aktivitas akan muncul otomatis saat kamu melakukan deposit atau membeli produk investasi.</p>

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
