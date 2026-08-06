@include('partials.anti-inspect')
@php
  $user = auth()->user();

  $totalActive = 0; $totalFinished = 0;
  $totalModal = 0; $totalProfit = 0; $totalDailyProfit = 0;

  foreach(($investments ?? []) as $inv){
    if(($inv->status ?? '') === 'active'){ $totalActive++; } else { $totalFinished++; }
    $totalModal += (int) ($inv->price ?? 0);
    $totalProfit += (int) ($inv->total_profit ?? 0);
    $totalDailyProfit += (int) ($inv->daily_profit ?? 0);
  }

  // alokasi modal 6 bulan terakhir
  $chartKeys = []; $chartMonths = [];
  for ($i = 5; $i >= 0; $i--) {
    $m = now()->copy()->startOfMonth()->subMonths($i);
    $chartKeys[] = $m->format('Y-m');
    $chartMonths[] = $m->translatedFormat('M');
  }
  $monthlyModalMap = array_fill_keys($chartKeys, 0);
  foreach (($investments ?? []) as $inv) {
    if (!empty($inv->start_date)) {
      $k = \Carbon\Carbon::parse($inv->start_date)->format('Y-m');
      if (array_key_exists($k, $monthlyModalMap)) $monthlyModalMap[$k] += (int) ($inv->price ?? 0);
    }
  }
  $monthlyModalData = array_values($monthlyModalMap);
  $monthlyModalMax = max(1, ...$monthlyModalData);

  $returnRate  = $totalModal > 0 ? round(($totalProfit / $totalModal) * 100, 1) : 0;
  $ringPercent = $returnRate > 0 ? max(8, min(100, (int) round($returnRate))) : ($totalActive > 0 ? 20 : 0);
  $ringCirc    = 213.63; // 2*pi*34
  $ringOffset  = $ringCirc * (1 - $ringPercent / 100);

  $totalPlan = (int) $totalActive + (int) $totalFinished;
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Portofolio Investasi | Capital Wave</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --blue:#0A57A3; --blue-ink:#0b4a8c; --blue-lite:#2f7fd4; --blue-soft:#eef4fb;
      --navy:#0b2740; --navy-2:#0d3357; --navy-3:#0a2036;
      --gold:#c99433; --gold-lite:#e8c874; --gold-deep:#a9772a; --gold-soft:#faf3e2;
      --gold-metal:linear-gradient(135deg,#a9772a 0%,#e8c874 46%,#c99433 100%);
      --green:#1c9d67; --green-soft:#e6f5ee; --chart:#16a86a; --red:#dc5757; --red-soft:#fdeaea;
      --bg:#eef1f6; --card:#ffffff; --tint:#f5f8fc; --line:#e9edf4; --line-2:#dfe5ee;
      --ink:#152a3f; --ink-soft:#46586c; --muted:#8493a6; --muted-2:#aab6c4;
      --sh-sm:0 1px 2px rgba(11,39,64,.05);
      --sh:0 2px 6px rgba(11,39,64,.04), 0 12px 30px rgba(11,39,64,.07);
      --sh-lg:0 4px 10px rgba(11,39,64,.05), 0 22px 50px rgba(11,39,64,.12);
      --sh-navy:0 10px 24px rgba(9,30,52,.28), 0 24px 60px rgba(9,30,52,.30);
      --r:24px;
    }
    *{ box-sizing:border-box; }
    html,body{ min-height:100%; }
    body{
      margin:0; color:var(--ink);
      font-family:'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background:
        radial-gradient(900px 500px at 50% -220px, rgba(10,87,163,.10), transparent 60%),
        radial-gradient(600px 400px at 100% 8%, rgba(201,148,51,.06), transparent 55%),
        linear-gradient(180deg,#f2f5f9 0%,#eef1f6 40%,#eaeef4 100%);
      background-attachment:fixed; overflow-x:hidden;
      -webkit-font-smoothing:antialiased; -webkit-tap-highlight-color:transparent; letter-spacing:-.01em;
    }
    a{ color:inherit; text-decoration:none; }
    button{ font-family:inherit; }
    h1,h2,h3,p{ margin:0; }
    .iv-page{ width:100%; min-height:100vh; display:flex; justify-content:center; }
    .iv-phone{ width:100%; max-width:428px; min-height:100vh; position:relative; padding:18px 16px 112px; }

    /* ===== HEADER ===== */
    .cw-header{ display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:16px; }
    .cw-brand{ display:flex; align-items:center; gap:11px; min-width:0; }
    .cw-mark{ width:42px; height:42px; border-radius:50%; flex:0 0 auto; position:relative; display:grid; place-items:center;
      background:linear-gradient(160deg,#ffffff 0%,#eef4fb 100%); box-shadow:0 8px 20px rgba(11,39,64,.26), inset 0 1px 0 rgba(255,255,255,.14); }
    .cw-mark::after{ content:""; position:absolute; inset:0; border-radius:50%; padding:1.4px; background:var(--gold-metal); -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; opacity:.85; }
    .cw-mark svg{ width:26px; height:26px; position:relative; z-index:1; }
    .cw-word{ display:flex; flex-direction:column; min-width:0; line-height:1; }
    .cw-word .name{ font-size:18px; font-weight:800; letter-spacing:.01em; color:var(--navy); white-space:nowrap; }
    .cw-word .tag{ margin-top:5px; font-size:8.5px; font-weight:600; letter-spacing:.24em; text-transform:uppercase; color:var(--muted); white-space:nowrap; }
    .cw-tools{ display:flex; align-items:center; gap:2px; padding:4px; border-radius:999px; background:var(--card); border:1px solid var(--line); box-shadow:var(--sh-sm); flex:0 0 auto; }
    .cw-tool{ width:38px; height:38px; border-radius:999px; display:grid; place-items:center; color:var(--ink-soft); position:relative; transition:.16s ease; }
    .cw-tool:hover{ color:var(--blue); background:var(--blue-soft); }
    .cw-tool svg{ width:19px; height:19px; }
    .cw-tool-div{ width:1px; height:20px; background:var(--line-2); }

    /* ===== HERO (ring) ===== */
    .iv-hero{ position:relative; overflow:hidden; border-radius:26px; padding:20px; color:#fff;
      background:
        radial-gradient(420px 240px at 90% -20%, rgba(232,200,116,.2), transparent 62%),
        radial-gradient(360px 220px at 5% 120%, rgba(47,127,212,.22), transparent 60%),
        linear-gradient(150deg,#0f3255 0%,#0b2740 52%,#0a2036 100%);
      box-shadow:var(--sh-navy); }
    .iv-hero::before{ content:""; position:absolute; inset:0; border-radius:inherit; padding:1px; pointer-events:none;
      background:linear-gradient(150deg, rgba(232,200,116,.6), rgba(232,200,116,0) 34%, rgba(255,255,255,.14) 100%);
      -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; }
    .iv-hero > *{ position:relative; z-index:1; }
    .iv-hero-main{ display:flex; align-items:center; gap:16px; }
    .iv-hero-tx{ flex:1; min-width:0; }
    .iv-eyebrow{ display:inline-flex; align-items:center; gap:8px; color:rgba(255,255,255,.62); font-size:9.5px; font-weight:600; letter-spacing:.18em; text-transform:uppercase; }
    .iv-eyebrow::before{ content:""; width:6px; height:6px; border-radius:999px; background:var(--gold-lite); box-shadow:0 0 10px rgba(232,200,116,.8); }
    .iv-hero-label{ margin-top:12px; color:rgba(255,255,255,.6); font-size:11px; font-weight:500; }
    .iv-hero-profit{ margin-top:5px; font-size:29px; font-weight:700; letter-spacing:-.035em; line-height:1; }
    .iv-hero-profit .gold{ background:var(--gold-metal); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }
    .iv-hero-sub{ margin-top:8px; font-size:11px; font-weight:500; color:rgba(255,255,255,.5); }
    .iv-hero-sub b{ color:rgba(255,255,255,.8); font-weight:600; }

    .iv-ring-wrap{ position:relative; width:96px; height:96px; flex:0 0 auto; display:grid; place-items:center; }
    .iv-ring{ width:96px; height:96px; transform:rotate(-90deg); }
    .iv-ring-track{ fill:none; stroke:rgba(255,255,255,.12); stroke-width:8; }
    .iv-ring-prog{ fill:none; stroke:url(#ivRing); stroke-width:8; stroke-linecap:round; transition:stroke-dashoffset 1.1s cubic-bezier(.22,.8,.22,1); }
    .iv-ring-center{ position:absolute; inset:0; display:grid; place-content:center; text-align:center; }
    .iv-ring-center b{ display:block; font-size:20px; font-weight:700; letter-spacing:-.03em; color:#fff; }
    .iv-ring-center span{ display:block; margin-top:2px; font-size:8.5px; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:rgba(255,255,255,.55); }

    .iv-hero-divider{ margin:16px 0; height:1px; background:linear-gradient(90deg, transparent, rgba(232,200,116,.3) 22%, rgba(255,255,255,.1) 78%, transparent); }
    .iv-hero-stats{ display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
    .iv-hs{ position:relative; }
    .iv-hs + .iv-hs::before{ content:""; position:absolute; left:-6px; top:2px; bottom:2px; width:1px; background:linear-gradient(180deg, transparent, rgba(255,255,255,.12), transparent); }
    .iv-hs span{ display:flex; align-items:center; gap:5px; color:rgba(255,255,255,.55); font-size:10px; font-weight:500; }
    .iv-hs span svg{ width:12px; height:12px; color:var(--gold-lite); }
    .iv-hs strong{ display:block; margin-top:6px; font-size:14px; font-weight:700; letter-spacing:-.02em; color:#fff; }

    /* ===== MONTHLY BAR CARD ===== */
    .iv-chart-card{ margin-top:14px; border-radius:22px; padding:16px; background:var(--card); border:1px solid var(--line); box-shadow:var(--sh); }
    .iv-chart-head{ display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:14px; }
    .iv-chart-head .t b{ display:block; font-size:14px; font-weight:700; color:var(--navy); letter-spacing:-.02em; }
    .iv-chart-head .t span{ display:block; margin-top:3px; font-size:10.5px; font-weight:500; color:var(--muted); }
    .iv-chart-tag{ display:inline-flex; align-items:center; gap:5px; padding:6px 11px; border-radius:999px; background:var(--gold-soft); color:var(--gold-deep); font-size:11px; font-weight:700; }
    .iv-bars{ display:grid; grid-template-columns:repeat(6,1fr); gap:8px; align-items:end; height:110px; }
    .iv-bar-col{ display:flex; flex-direction:column; align-items:center; gap:8px; height:100%; justify-content:flex-end; }
    .iv-bar{ width:100%; max-width:26px; border-radius:8px 8px 4px 4px; background:linear-gradient(180deg, var(--blue-lite), var(--blue)); box-shadow:inset 0 1px 0 rgba(255,255,255,.2);
      height:0; transition:height 1s cubic-bezier(.22,.8,.22,1); position:relative; }
    .iv-bar.is-top{ background:var(--gold-metal); }
    .iv-bar-col span{ font-size:9.5px; font-weight:600; color:var(--muted); }

    /* ===== FILTER TABS ===== */
    .iv-filter{ margin-top:22px; display:flex; gap:8px; padding:6px; border-radius:16px; background:var(--card); border:1px solid var(--line); box-shadow:var(--sh-sm); }
    .iv-tab{ flex:1; height:38px; border:0; cursor:pointer; border-radius:11px; background:transparent; color:var(--ink-soft); font-size:12.5px; font-weight:600; transition:.16s ease; display:flex; align-items:center; justify-content:center; gap:6px; }
    .iv-tab .cnt{ display:inline-flex; align-items:center; justify-content:center; min-width:18px; height:18px; padding:0 5px; border-radius:999px; background:var(--tint); color:var(--muted); font-size:10px; font-weight:700; }
    .iv-tab.active{ color:#fff; background:linear-gradient(135deg,#123457,#0b2740); box-shadow:0 8px 16px rgba(11,39,64,.2); }
    .iv-tab.active .cnt{ background:rgba(255,255,255,.18); color:#fff; }

    /* ===== INVESTMENT CARDS ===== */
    .iv-list{ margin-top:14px; display:flex; flex-direction:column; gap:13px; }
    .iv-card{ position:relative; overflow:hidden; border-radius:var(--r); background:var(--card); border:1px solid var(--line); box-shadow:var(--sh); opacity:0; transform:translateY(12px); animation:ivUp .5s cubic-bezier(.22,.8,.22,1) forwards; }
    .iv-card::before{ content:""; position:absolute; top:0; left:0; right:0; height:3px; background:var(--gold-metal); }
    .iv-card.is-finished::before{ background:var(--line-2); }
    .iv-card-top{ display:flex; align-items:center; gap:12px; padding:16px 16px 12px; }
    .iv-product-icon{ position:relative; width:46px; height:46px; flex:0 0 auto; border-radius:14px; padding:1.5px; background:var(--gold-metal); display:grid; }
    .iv-card.is-finished .iv-product-icon{ background:var(--line-2); }
    .iv-product-icon::after{ content:attr(data-i); grid-area:1/1; border-radius:12px; display:grid; place-items:center; color:#fff; font-size:16px; font-weight:700; background:linear-gradient(135deg, var(--navy), var(--blue)); }
    .iv-product-info{ min-width:0; flex:1; }
    .iv-product-name{ font-size:15px; font-weight:700; letter-spacing:-.02em; color:var(--navy); overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .iv-product-meta{ margin-top:4px; font-size:11px; font-weight:500; color:var(--muted); }
    .iv-badge{ flex:0 0 auto; display:inline-flex; align-items:center; gap:5px; padding:5px 10px; border-radius:9px; font-size:10.5px; font-weight:700; color:var(--muted); background:var(--tint); }
    .iv-badge.is-active{ color:var(--green); background:var(--green-soft); }
    .iv-badge.is-active::before{ content:""; width:6px; height:6px; border-radius:999px; background:var(--green); box-shadow:0 0 0 3px rgba(28,157,103,.16); animation:ivPulse 1.8s ease-in-out infinite; }

    .iv-stats{ display:grid; grid-template-columns:1fr 1fr 1fr; border-top:1px solid var(--line); background:var(--tint); }
    .iv-stat{ padding:12px 14px; }
    .iv-stat + .iv-stat{ border-left:1px solid var(--line); }
    .iv-stat-label{ font-size:9.5px; font-weight:500; letter-spacing:.04em; text-transform:uppercase; color:var(--muted); }
    .iv-stat-value{ margin-top:6px; font-size:12.5px; font-weight:700; color:var(--navy); letter-spacing:-.01em; }
    .iv-stat-value.is-accent{ color:var(--gold-deep); }
    .iv-stat-value.is-green{ color:var(--green); }

    .iv-card-foot{ padding:13px 16px 16px; }
    .iv-progress-head{ display:flex; align-items:center; justify-content:space-between; margin-bottom:8px; font-size:11px; font-weight:600; }
    .iv-progress-head span{ color:var(--ink-soft); }
    .iv-progress-head strong{ color:var(--blue); }
    .iv-progress-bar{ height:8px; border-radius:999px; background:var(--line-2); overflow:hidden; }
    .iv-progress-fill{ height:100%; width:0; border-radius:999px; background:linear-gradient(90deg, var(--blue), var(--gold)); transition:width 1s cubic-bezier(.22,.8,.22,1); }
    .iv-card.is-finished .iv-progress-fill{ background:var(--muted-2); }
    .iv-dates{ margin-top:12px; display:flex; align-items:center; justify-content:space-between; gap:10px; padding:10px 12px; border-radius:12px; background:var(--tint); border:1px solid var(--line); }
    .iv-date{ text-align:center; }
    .iv-date span{ display:block; font-size:9px; font-weight:600; letter-spacing:.06em; text-transform:uppercase; color:var(--muted); }
    .iv-date b{ display:block; margin-top:4px; font-size:11.5px; font-weight:700; color:var(--navy); }
    .iv-date-arrow{ color:var(--gold-deep); }
    .iv-date-arrow svg{ width:18px; height:18px; }

    /* ===== EMPTY ===== */
    .iv-empty{ margin-top:14px; padding:34px 22px; border-radius:var(--r); background:var(--card); border:1px dashed var(--line-2); text-align:center; }
    .iv-empty-icon{ width:64px; height:64px; margin:0 auto; border-radius:20px; display:grid; place-items:center; color:var(--blue); background:var(--blue-soft); }
    .iv-empty-icon svg{ width:30px; height:30px; }
    .iv-empty-title{ margin-top:16px; font-size:16px; font-weight:700; color:var(--navy); letter-spacing:-.02em; }
    .iv-empty-desc{ margin-top:8px; font-size:12.5px; font-weight:500; color:var(--muted); line-height:1.55; }
    .iv-empty-btn{ margin-top:18px; display:inline-flex; align-items:center; gap:8px; height:48px; padding:0 20px; border-radius:14px; color:#fff; background:linear-gradient(135deg,#123457,#0b2740); box-shadow:0 10px 22px rgba(11,39,64,.24); font-size:13.5px; font-weight:700; }
    .iv-empty-btn svg{ width:17px; height:17px; }
    .iv-hidden{ display:none !important; }

    .rb-bottom-spacer{ height:94px; }

    @keyframes ivUp{ to{ opacity:1; transform:translateY(0); } }
    @keyframes ivPulse{ 0%,100%{ box-shadow:0 0 0 3px rgba(28,157,103,.16); } 50%{ box-shadow:0 0 0 5px rgba(28,157,103,.05); } }

    @media (max-width:360px){
      .iv-phone{ padding:16px 12px 112px; }
      .iv-hero-profit{ font-size:25px; }
      .iv-ring-wrap,.iv-ring{ width:84px; height:84px; }
      .iv-stats{ grid-template-columns:1fr 1fr; }
      .iv-stat:nth-child(3){ grid-column:1 / -1; border-left:0; border-top:1px solid var(--line); }
    }
    @media (prefers-reduced-motion:reduce){
      *,*::before,*::after{ animation:none !important; transition:none !important; }
      .iv-card{ opacity:1; transform:none; }
    }
  </style>
</head>

<body>
  <svg width="0" height="0" style="position:absolute" aria-hidden="true"><defs>
    <linearGradient id="ivRing" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#e8c874"/><stop offset="100%" stop-color="#c99433"/>
    </linearGradient>
  </defs></svg>

  <main class="iv-page">
    <div class="iv-phone">

      {{-- HEADER --}}
      <header class="cw-header">
        <a href="{{ url('/dashboard') }}" class="cw-brand" aria-label="Capital Wave">
          <span class="cw-mark">
            <img src="{{ asset('logo.png') }}" alt="Capital Wave" style="width:82%;height:82%;object-fit:contain;position:relative;z-index:1;">
          </span>
          <span class="cw-word">
            <span class="name">Portofolio</span>
            <span class="tag">Investasi Capital Wave</span>
          </span>
        </a>
        <div class="cw-tools">
          <a href="{{ url('/saldo/rincian') }}" class="cw-tool" aria-label="Saldo">
            <svg viewBox="0 0 24 24" fill="none"><rect x="3" y="6" width="18" height="13" rx="2.5" stroke="currentColor" stroke-width="1.7"/><path d="M3 10h18" stroke="currentColor" stroke-width="1.7"/></svg>
          </a>
          <span class="cw-tool-div"></span>
          <a href="{{ url('/akun') }}" class="cw-tool" aria-label="Akun">
            <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="3.6" stroke="currentColor" stroke-width="1.7"/><path d="M5 20a7 7 0 0 1 14 0" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
          </a>
        </div>
      </header>

      {{-- HERO --}}
      <section class="iv-hero">
        <div class="iv-hero-main">
          <div class="iv-hero-tx">
            <span class="iv-eyebrow">Total Profit</span>
            <p class="iv-hero-label">Keuntungan terkumpul</p>
            <h2 class="iv-hero-profit"><span class="gold">+Rp {{ number_format($totalProfit, 0, ',', '.') }}</span></h2>
            <p class="iv-hero-sub">dari modal <b>Rp {{ number_format($totalModal, 0, ',', '.') }}</b></p>
          </div>
          <div class="iv-ring-wrap">
            <svg class="iv-ring" viewBox="0 0 96 96">
              <circle class="iv-ring-track" cx="48" cy="48" r="34"/>
              <circle class="iv-ring-prog" cx="48" cy="48" r="34" style="stroke-dasharray:{{ $ringCirc }}; stroke-dashoffset:{{ $ringCirc }};" data-offset="{{ $ringOffset }}"/>
            </svg>
            <div class="iv-ring-center">
              <b>{{ $returnRate }}%</b>
              <span>Return</span>
            </div>
          </div>
        </div>

        <div class="iv-hero-divider"></div>

        <div class="iv-hero-stats">
          <div class="iv-hs">
            <span><svg viewBox="0 0 24 24" fill="none"><path d="M12 3v18M8 7l4-4 4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Profit Harian</span>
            <strong>Rp {{ number_format($totalDailyProfit, 0, ',', '.') }}</strong>
          </div>
          <div class="iv-hs">
            <span><svg viewBox="0 0 24 24" fill="none"><rect x="4" y="5" width="16" height="14" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M4 9h16" stroke="currentColor" stroke-width="1.8"/></svg> Modal</span>
            <strong>Rp {{ number_format($totalModal, 0, ',', '.') }}</strong>
          </div>
          <div class="iv-hs">
            <span><svg viewBox="0 0 24 24" fill="none"><path d="M4 19V5M8 17v-6M12 17V8M16 17v-3M20 17V6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Paket Aktif</span>
            <strong>{{ $totalActive }} Plan</strong>
          </div>
        </div>
      </section>

      {{-- MONTHLY ALLOCATION BARS --}}
      <section class="iv-chart-card">
        <div class="iv-chart-head">
          <div class="t">
            <b>Alokasi Modal</b>
            <span>6 bulan terakhir</span>
          </div>
          <span class="iv-chart-tag">
            <svg viewBox="0 0 24 24" fill="none" style="width:12px;height:12px"><path d="M4 15l5-5 4 4 7-8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ $totalPlan }} paket
          </span>
        </div>
        <div class="iv-bars">
          @foreach($chartMonths as $i => $mLabel)
            @php $val = $monthlyModalData[$i] ?? 0; $h = (int) round(($val / $monthlyModalMax) * 100); $isTop = $val > 0 && $val >= $monthlyModalMax; @endphp
            <div class="iv-bar-col">
              <div class="iv-bar {{ $isTop ? 'is-top' : '' }}" data-h="{{ max($val > 0 ? 8 : 3, $h) }}" title="Rp {{ number_format($val, 0, ',', '.') }}"></div>
              <span>{{ $mLabel }}</span>
            </div>
          @endforeach
        </div>
      </section>

      {{-- FILTER --}}
      <div class="iv-filter" id="ivFilter">
        <button type="button" class="iv-tab active" data-filter="all">Semua <span class="cnt">{{ $totalPlan }}</span></button>
        <button type="button" class="iv-tab" data-filter="active">Aktif <span class="cnt">{{ $totalActive }}</span></button>
        <button type="button" class="iv-tab" data-filter="finished">Selesai <span class="cnt">{{ $totalFinished }}</span></button>
      </div>

      {{-- LIST --}}
      <section class="iv-list" id="ivList">
        @forelse($investments as $inv)
          @php
            $isActive = ($inv->status ?? '') === 'active';
            $pName = optional($inv->product)->name ?? 'Investasi';
            $init  = mb_strtoupper(mb_substr($pName, 0, 1));

            $prog = 100;
            if ($isActive && !empty($inv->start_date) && !empty($inv->end_date)) {
              $s = \Carbon\Carbon::parse($inv->start_date)->startOfDay();
              $e = \Carbon\Carbon::parse($inv->end_date)->startOfDay();
              $today = now()->startOfDay();
              $totalD = max(1, $s->diffInDays($e));
              $elapsed = max(0, min($totalD, $s->diffInDays($today, false)));
              $prog = min(100, max(0, (int) round(($elapsed / $totalD) * 100)));
            }

            $startLabel = !empty($inv->start_date) ? \Carbon\Carbon::parse($inv->start_date)->translatedFormat('d M Y') : '-';
            $endLabel   = !empty($inv->end_date) ? \Carbon\Carbon::parse($inv->end_date)->translatedFormat('d M Y') : '-';
          @endphp

          <article class="iv-card {{ $isActive ? 'is-active-card' : 'is-finished' }}" data-status="{{ $isActive ? 'active' : 'finished' }}" style="animation-delay: {{ $loop->index * 0.07 }}s;">
            <div class="iv-card-top">
              <div class="iv-product-icon" data-i="{{ $init }}"></div>
              <div class="iv-product-info">
                <h3 class="iv-product-name">{{ str_ireplace(['Rubik', 'Velora'], 'Capital Wave', $pName) }}</h3>
                <p class="iv-product-meta">Durasi {{ (int)($inv->duration_days ?? 0) }} Hari · Capital Wave</p>
              </div>
              @if($isActive)
                <span class="iv-badge is-active">Aktif</span>
              @else
                <span class="iv-badge">{{ strtoupper($inv->status ?? 'Selesai') }}</span>
              @endif
            </div>

            <div class="iv-stats">
              <div class="iv-stat">
                <p class="iv-stat-label">Modal</p>
                <p class="iv-stat-value">Rp {{ number_format((int)($inv->price ?? 0), 0, ',', '.') }}</p>
              </div>
              <div class="iv-stat">
                <p class="iv-stat-label">Total Profit</p>
                <p class="iv-stat-value is-green">+Rp {{ number_format((int)($inv->total_profit ?? 0), 0, ',', '.') }}</p>
              </div>
              <div class="iv-stat">
                <p class="iv-stat-label">Profit / Hari</p>
                <p class="iv-stat-value is-accent">Rp {{ number_format((int)($inv->daily_profit ?? 0), 0, ',', '.') }}</p>
              </div>
            </div>

            <div class="iv-card-foot">
              <div class="iv-progress-head">
                <span>Progress Periode</span>
                <strong>{{ $prog }}%</strong>
              </div>
              <div class="iv-progress-bar"><div class="iv-progress-fill" data-w="{{ $prog }}"></div></div>

              <div class="iv-dates">
                <div class="iv-date"><span>Mulai</span><b>{{ $startLabel }}</b></div>
                <div class="iv-date-arrow"><svg viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
                <div class="iv-date"><span>Selesai</span><b>{{ $endLabel }}</b></div>
              </div>
            </div>
          </article>
        @empty
          <div class="iv-empty">
            <div class="iv-empty-icon"><svg viewBox="0 0 24 24" fill="none"><rect x="3" y="7" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" stroke="currentColor" stroke-width="1.8"/><path d="M7 11h10M7 15h7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></div>
            <h2 class="iv-empty-title">Belum ada investasi</h2>
            <p class="iv-empty-desc">Portofolio kamu masih kosong. Pilih produk dari halaman pasar untuk mulai melihat profit harian.</p>
            <a class="iv-empty-btn" href="{{ route('market.index') }}">
              <svg viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>
              Mulai Investasi
            </a>
          </div>
        @endforelse

        <div class="iv-empty iv-hidden" id="ivFilterEmpty">
          <div class="iv-empty-icon"><svg viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"/><path d="m20 20-3.5-3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></div>
          <h2 class="iv-empty-title">Tidak ada data</h2>
          <p class="iv-empty-desc">Belum ada investasi pada kategori ini.</p>
        </div>
      </section>

      <div class="rb-bottom-spacer"></div>
      @include('partials.bottom-nav')
    </div>
  </main>

  <script>
    // animate ring, bars, progress on load
    window.addEventListener('load', function(){
      setTimeout(function(){
        const ring = document.querySelector('.iv-ring-prog');
        if(ring) ring.style.strokeDashoffset = ring.dataset.offset;
        document.querySelectorAll('.iv-bar').forEach(b => { b.style.height = (b.dataset.h || 0) + '%'; });
        document.querySelectorAll('.iv-progress-fill').forEach(f => { f.style.width = (f.dataset.w || 0) + '%'; });
      }, 120);
    });

    // filter tabs
    (function(){
      const tabs = Array.from(document.querySelectorAll('.iv-tab'));
      const cards = Array.from(document.querySelectorAll('.iv-card'));
      const emptyEl = document.getElementById('ivFilterEmpty');
      tabs.forEach(tab => tab.addEventListener('click', function(){
        const f = this.dataset.filter;
        tabs.forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        let shown = 0;
        cards.forEach(c => {
          const ok = f === 'all' || c.dataset.status === f;
          c.classList.toggle('iv-hidden', !ok);
          if(ok) shown++;
        });
        if(emptyEl) emptyEl.classList.toggle('iv-hidden', shown > 0 || cards.length === 0);
      }));
    })();
  </script>
</body>
</html>
