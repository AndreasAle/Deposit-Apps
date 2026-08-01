@include('partials.anti-inspect')

@php
    $user = auth()->user();

    $portfolioBalance = (int) ($portfolioBalance ?? 0);
    $todayProfit = (int) ($todayProfit ?? 0);

    $totalProduk = 0;
    $produkTermurah = null;
    $estimasiProfit = 0;

    foreach(($categories ?? []) as $cat){
        foreach(($cat->products ?? []) as $product){
            $totalProduk++;
            $estimasiProfit += (int) ($product->daily_profit ?? 0);
            if($produkTermurah === null || (int) $product->price < $produkTermurah){
                $produkTermurah = (int) $product->price;
            }
        }
    }

    $produkTermurah = $produkTermurah ?? 0;

    $yieldPercent = $portfolioBalance > 0
        ? round(($todayProfit / max($portfolioBalance, 1)) * 100, 2)
        : 0;
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Pasar Investasi | Capital Wave</title>
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
      --green:#1c9d67; --green-soft:#e6f5ee; --chart:#16a86a; --red:#dc5757;
      --bg:#eef1f6; --card:#ffffff; --tint:#f5f8fc; --line:#e9edf4; --line-2:#dfe5ee;
      --ink:#152a3f; --ink-soft:#46586c; --muted:#8493a6; --muted-2:#aab6c4;
      --sh-sm:0 1px 2px rgba(11,39,64,.05);
      --sh:0 2px 6px rgba(11,39,64,.04), 0 12px 30px rgba(11,39,64,.07);
      --sh-lg:0 4px 10px rgba(11,39,64,.05), 0 22px 50px rgba(11,39,64,.12);
      --sh-navy:0 10px 24px rgba(9,30,52,.28), 0 24px 60px rgba(9,30,52,.30);
      --r:24px; --r-sm:16px;
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
    button,input{ font-family:inherit; }
    h1,h2,h3,p{ margin:0; }
    .cwm-page{ width:100%; min-height:100vh; display:flex; justify-content:center; }
    .cwm-phone{ width:100%; max-width:428px; min-height:100vh; position:relative; padding:18px 16px 112px; }
    .cw-gold-text{ background:var(--gold-metal); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }

    /* ===== HEADER ===== */
    .cw-header{ display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:18px; }
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
    .cw-tool .dot{ position:absolute; right:8px; top:8px; width:7px; height:7px; border-radius:999px; background:var(--gold); border:2px solid var(--card); }
    .cw-tool-div{ width:1px; height:20px; background:var(--line-2); }

    /* ===== HERO ===== */
    .cwm-hero{ position:relative; overflow:hidden; border-radius:26px; padding:20px; color:#fff;
      background:
        radial-gradient(420px 240px at 88% -20%, rgba(232,200,116,.16), transparent 62%),
        radial-gradient(360px 220px at 8% 120%, rgba(47,127,212,.22), transparent 60%),
        linear-gradient(150deg,#0f3255 0%,#0b2740 52%,#0a2036 100%);
      box-shadow:var(--sh-navy); }
    .cwm-hero::before{ content:""; position:absolute; inset:0; border-radius:inherit; padding:1px; pointer-events:none;
      background:linear-gradient(150deg, rgba(232,200,116,.6), rgba(232,200,116,0) 34%, rgba(255,255,255,.14) 100%);
      -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; }
    .cwm-hero > *{ position:relative; z-index:1; }
    .cwm-hero-top{ display:flex; align-items:flex-start; justify-content:space-between; gap:12px; }
    .cwm-eyebrow{ display:inline-flex; align-items:center; gap:8px; color:rgba(255,255,255,.62); font-size:9.5px; font-weight:600; letter-spacing:.2em; text-transform:uppercase; }
    .cwm-eyebrow::before{ content:""; width:6px; height:6px; border-radius:999px; background:var(--gold-lite); box-shadow:0 0 10px rgba(232,200,116,.8); }
    .cwm-yield{ display:inline-flex; align-items:center; gap:5px; padding:6px 11px; border-radius:999px; color:#0b2740; background:var(--gold-metal); font-size:11px; font-weight:800; box-shadow:0 6px 16px rgba(201,148,51,.34); }
    .cwm-yield svg{ width:13px; height:13px; }
    .cwm-hero-balance{ margin-top:14px; color:#fff; font-size:34px; font-weight:700; letter-spacing:-.035em; line-height:1; text-shadow:0 8px 24px rgba(0,0,0,.28); }
    .cwm-hero-balance .rp{ font-size:16px; font-weight:600; color:rgba(255,255,255,.55); margin-right:4px; vertical-align:2px; }
    .cwm-profit{ margin-top:13px; display:inline-flex; align-items:center; gap:7px; padding:7px 12px; border-radius:12px; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.16); font-size:12px; font-weight:700; color:#fff; }
    .cwm-profit svg{ width:14px; height:14px; color:var(--gold-lite); }
    .cwm-profit em{ font-style:normal; color:rgba(255,255,255,.55); font-weight:500; margin-left:2px; }

    /* ===== STAT TILES ===== */
    .cwm-stats{ margin-top:13px; display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }
    .cwm-stat{ position:relative; overflow:hidden; border-radius:18px; padding:13px 12px; background:var(--card); border:1px solid var(--line); box-shadow:var(--sh-sm); }
    .cwm-stat::before{ content:""; position:absolute; left:0; top:0; bottom:0; width:3px; background:var(--gold-metal); opacity:.85; }
    .cwm-stat p{ line-height:1.2; }
    .cwm-stat-label{ color:var(--muted); font-size:9.5px; font-weight:600; letter-spacing:.04em; text-transform:uppercase; }
    .cwm-stat-value{ margin-top:8px; color:var(--navy); font-size:14px; font-weight:700; letter-spacing:-.02em; }
    .cwm-stat-value small{ color:var(--muted); font-size:10px; font-weight:600; }
    .cwm-stat.blue::before{ background:var(--blue); }

    /* ===== SECTION / TABS ===== */
    .cwm-section-head{ margin-top:26px; margin-bottom:14px; display:flex; align-items:center; gap:11px; }
    .cwm-section-head .bar{ width:4px; height:30px; border-radius:999px; background:var(--gold-metal); }
    .cwm-section-head h2{ color:var(--navy); font-size:18px; font-weight:700; letter-spacing:-.025em; }
    .cwm-section-head p{ margin-top:3px; color:var(--muted); font-size:11.5px; font-weight:500; }
    .cwm-tabs{ display:flex; gap:8px; overflow-x:auto; overflow-y:hidden; padding:8px 4px 24px; margin:0 -2px -12px; scrollbar-width:none; }
    .cwm-tabs::-webkit-scrollbar{ display:none; }
    .cwm-tab{ flex:0 0 auto; display:inline-flex; align-items:center; gap:7px; height:40px; padding:0 16px; border:0; cursor:pointer;
      border-radius:999px; color:var(--ink-soft); background:var(--card); border:1px solid var(--line); box-shadow:var(--sh-sm);
      font-size:12.5px; font-weight:500; transition:.16s ease; white-space:nowrap; }
    .cwm-tab.active{ color:#fff; background:linear-gradient(135deg,#123457,#0b2740); border-color:transparent; font-weight:600; box-shadow:0 8px 16px rgba(11,39,64,.2); }

    /* ===== PRODUCT CARD ===== */
    .cwm-pane{ display:none; }
    .cwm-pane.active{ display:block; }
    .cwm-list{ display:flex; flex-direction:column; gap:14px; }
    .cwm-card{ position:relative; overflow:hidden; border-radius:var(--r); background:var(--card); border:1px solid var(--line); box-shadow:var(--sh); transition:.18s ease; }
    .cwm-card::before{ content:""; position:absolute; top:0; left:0; right:0; height:3px; background:var(--gold-metal); opacity:0; transition:.18s ease; }
    .cwm-card:hover{ box-shadow:var(--sh-lg); }
    .cwm-card:hover::before{ opacity:1; }

    .cwm-card-top{ display:flex; align-items:center; gap:12px; padding:16px 16px 12px; }
    .cwm-token{ position:relative; width:46px; height:46px; flex:0 0 auto; border-radius:14px; padding:1.5px; background:var(--gold-metal); }
    .cwm-token span{ width:100%; height:100%; border-radius:12px; display:grid; place-items:center; color:#fff;
      background:linear-gradient(135deg, var(--blue), var(--blue-lite)); }
    .cwm-token svg{ width:22px; height:22px; }
    .cwm-headline{ min-width:0; flex:1; }
    .cwm-headline h3{ color:var(--navy); font-size:15.5px; font-weight:700; letter-spacing:-.02em; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .cwm-headline p{ margin-top:4px; color:var(--muted); font-size:11px; font-weight:500; }
    .cwm-headline p b{ color:var(--gold-deep); font-weight:600; }
    .cwm-price{ text-align:right; flex:0 0 auto; }
    .cwm-price strong{ display:block; color:var(--navy); font-size:15px; font-weight:700; letter-spacing:-.02em; }
    .cwm-badge{ margin-top:5px; display:inline-flex; align-items:center; gap:2px; padding:3px 8px; border-radius:8px; color:var(--green); background:var(--green-soft); font-size:11px; font-weight:700; }

    /* candlestick preview */
    .cwm-chart-box{ margin:0 14px; padding:12px; border-radius:16px; background:var(--tint); border:1px solid var(--line); }
    .cwm-chart-head{ display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:8px; }
    .cwm-chart-lbl{ display:flex; align-items:center; gap:6px; color:var(--muted); font-size:10px; font-weight:600; letter-spacing:.08em; text-transform:uppercase; }
    .cwm-chart-lbl::before{ content:""; width:5px; height:5px; border-radius:999px; background:var(--chart); box-shadow:0 0 7px rgba(22,168,106,.7); }
    .cwm-chart-tf{ display:flex; gap:2px; padding:2px; border-radius:9px; background:var(--card); border:1px solid var(--line); }
    .cwm-chart-tf button{ min-width:26px; height:22px; padding:0 6px; border:0; border-radius:7px; background:transparent; color:var(--muted); font-family:inherit; font-size:9.5px; font-weight:700; cursor:pointer; }
    .cwm-chart-tf button.on{ color:var(--navy); background:var(--tint); }

    .cw-chart{ width:100%; height:auto; display:block; overflow:visible; }
    .cw-chart .cw-grid{ stroke:var(--line-2); stroke-width:1; stroke-dasharray:2 4; }
    .cw-chart .cw-axis{ fill:var(--muted-2); font-family:'Plus Jakarta Sans',sans-serif; font-size:8px; font-weight:600; }
    .cw-chart .cw-wick{ stroke-width:1.2; }
    .cw-chart .cw-up{ fill:var(--chart); stroke:var(--chart); }
    .cw-chart .cw-down{ fill:var(--red); stroke:var(--red); }
    .cw-chart .cw-vol-up{ fill:var(--chart); opacity:.32; }
    .cw-chart .cw-vol-down{ fill:var(--red); opacity:.3; }
    .cw-chart .cw-ma1{ fill:none; stroke:var(--gold); stroke-width:1.8; stroke-linecap:round; stroke-linejoin:round; }
    .cw-chart .cw-ma2{ fill:none; stroke:var(--blue-lite); stroke-width:1.6; stroke-linecap:round; stroke-linejoin:round; opacity:.9; }
    .cw-chart .cw-hi-line{ stroke:var(--gold); stroke-width:1; stroke-dasharray:4 3; opacity:.8; }
    .cw-chart .cw-hi-tag{ fill:var(--gold-deep); font-family:'Plus Jakarta Sans',sans-serif; font-size:8.5px; font-weight:700; }
    .cw-chart .cw-cur-g{ transition:transform .85s cubic-bezier(.22,.8,.22,1); }
    .cw-chart .cw-cur-line{ stroke:var(--navy); stroke-width:1; stroke-dasharray:3 3; opacity:.5; }
    .cw-chart .cw-cur-tag-bg{ fill:var(--navy); }
    .cw-chart .cw-cur-tag-tx{ fill:#fff; font-family:'Plus Jakarta Sans',sans-serif; font-size:8.5px; font-weight:700; }
    .cw-chart .cw-cur-dot{ fill:var(--chart); stroke:#fff; stroke-width:1.4; animation:cwPulse 1.8s ease-in-out infinite; }
    .cw-candle{ transform-box:fill-box; transform-origin:center bottom; animation:cwCandleIn .5s cubic-bezier(.22,.8,.22,1) both; }
    @keyframes cwCandleIn{ from{ opacity:0; transform:scaleY(.2); } to{ opacity:1; transform:scaleY(1); } }
    @keyframes cwPulse{ 0%,100%{ opacity:.6; } 50%{ opacity:1; } }

    /* indicators */
    .cwm-ind{ display:grid; grid-template-columns:repeat(3,1fr); gap:6px; padding:14px 16px 6px; }
    .cwm-ind-col{ text-align:center; }
    .cwm-ind-col span{ display:block; margin-bottom:7px; color:var(--muted); font-size:9px; font-weight:600; letter-spacing:.07em; text-transform:uppercase; }
    .cwm-dots{ display:flex; justify-content:center; gap:3px; }
    .cwm-dots i{ width:14px; height:4px; border-radius:999px; background:var(--line-2); }
    .cwm-ind-col.risk i.on{ background:var(--gold); }
    .cwm-ind-col.profit i.on{ background:var(--green); }
    .cwm-ind-col.eff i.on{ background:var(--blue); }

    /* details 2x2 */
    .cwm-detail{ display:grid; grid-template-columns:1fr 1fr; border-top:1px solid var(--line); margin-top:14px; background:var(--tint); }
    .cwm-detail-cell{ padding:13px 16px; }
    .cwm-detail-cell:nth-child(odd){ border-right:1px solid var(--line); }
    .cwm-detail-cell:nth-child(n+3){ border-top:1px solid var(--line); }
    .cwm-detail-cell span{ display:block; margin-bottom:6px; color:var(--muted); font-size:9.5px; font-weight:500; letter-spacing:.04em; text-transform:uppercase; }
    .cwm-detail-cell strong{ color:var(--navy); font-size:13px; font-weight:700; letter-spacing:-.01em; }
    .cwm-detail-cell strong.gold{ color:var(--gold-deep); }
    .cwm-detail-cell strong.blue{ color:var(--blue); }

    .cwm-action{ padding:14px 16px 16px; }
    .cwm-buy{ position:relative; overflow:hidden; width:100%; min-height:48px; border:0; border-radius:14px; display:flex; align-items:center; justify-content:center; gap:8px; color:#fff; background:linear-gradient(135deg,#123457,#0b2740); box-shadow:0 10px 22px rgba(11,39,64,.24), inset 0 1px 0 rgba(255,255,255,.08); font-size:13.5px; font-weight:700; letter-spacing:-.01em; cursor:pointer; transition:.16s ease; }
    .cwm-buy::after{ content:""; position:absolute; inset:0; border-radius:inherit; padding:1px; background:linear-gradient(135deg, rgba(232,200,116,.7), transparent 55%); -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; opacity:.6; }
    .cwm-buy:hover{ transform:translateY(-1px); }
    .cwm-buy svg{ width:16px; height:16px; position:relative; z-index:1; }
    .cwm-buy.muted{ color:var(--muted); background:var(--tint); border:1px solid var(--line); box-shadow:none; cursor:default; }
    .cwm-buy.muted::after{ display:none; }
    .cwm-empty{ padding:24px 16px; border-radius:var(--r); background:var(--card); border:1px dashed var(--line-2); color:var(--muted); text-align:center; font-size:12.5px; font-weight:500; }

    /* modal */
    .vl-modal-overlay{ position:fixed; inset:0; z-index:9999; display:none; align-items:center; justify-content:center; padding:18px 16px; background:rgba(11,39,64,.55); backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px); }
    .vl-modal-overlay.show{ display:flex; }
    .vl-modal{ width:100%; max-width:404px; border-radius:22px; background:var(--card); box-shadow:var(--sh-lg); overflow:hidden; animation:vlIn .24s ease both; }
    .vl-modal-head{ padding:15px 16px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid var(--line); }
    .vl-modal-title{ display:flex; align-items:center; gap:10px; color:var(--navy); font-size:14.5px; font-weight:700; }
    .vl-modal-icon{ width:34px; height:34px; border-radius:11px; display:grid; place-items:center; color:var(--gold-deep); background:var(--gold-soft); }
    .vl-modal-close{ width:36px; height:36px; border-radius:11px; border:1px solid var(--line); background:var(--tint); color:var(--ink-soft); display:grid; place-items:center; cursor:pointer; }
    .vl-modal-body{ padding:18px; color:var(--ink-soft); text-align:center; font-size:13px; line-height:1.55; font-weight:500; }
    .vl-modal-body b{ color:var(--navy); font-weight:600; }
    .vl-modal-actions{ padding:0 18px 18px; display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    .vl-modal-btn{ min-height:44px; border-radius:13px; border:1px solid var(--line-2); background:var(--card); color:var(--ink-soft); font-size:12.5px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; }
    .vl-modal-btn.primary{ border:0; color:#fff; background:linear-gradient(135deg,#123457,#0b2740); }
    @keyframes vlIn{ from{ opacity:0; transform:translateY(14px) scale(.98); } to{ opacity:1; transform:translateY(0) scale(1); } }

    /* bottom nav compat */
    .rb-bottom-spacer{ height:94px; }

    @media (max-width:360px){
      .cwm-phone{ padding:16px 12px 112px; }
      .cwm-hero-balance{ font-size:30px; }
      .cwm-stats{ gap:8px; }
      .cwm-stat{ padding:11px 10px; }
      .cwm-detail{ grid-template-columns:1fr; }
      .cwm-detail-cell:nth-child(odd){ border-right:0; }
      .cwm-detail-cell:nth-child(n+2){ border-top:1px solid var(--line); }
    }
    @media (prefers-reduced-motion:reduce){ *,*::before,*::after{ animation:none !important; transition:none !important; } }
  </style>
</head>

<body>
  <main class="cwm-page">
    <div class="cwm-phone">

      {{-- HEADER --}}
      <header class="cw-header">
        <a href="{{ url('/dashboard') }}" class="cw-brand" aria-label="Capital Wave">
          <span class="cw-mark">
            <img src="{{ asset('logo.png') }}" alt="Capital Wave" style="width:82%;height:82%;object-fit:contain;position:relative;z-index:1;">
          </span>
          <span class="cw-word">
            <span class="name">Pasar Investasi</span>
            <span class="tag">Capital Wave</span>
          </span>
        </a>
        <div class="cw-tools">
          <a href="{{ url('/saldo/rincian') }}" class="cw-tool" aria-label="Notifikasi">
            <svg viewBox="0 0 24 24" fill="none"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
            <span class="dot"></span>
          </a>
          <span class="cw-tool-div"></span>
          <a href="{{ url('/akun') }}" class="cw-tool" aria-label="Akun">
            <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="3.6" stroke="currentColor" stroke-width="1.7"/><path d="M5 20a7 7 0 0 1 14 0" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
          </a>
        </div>
      </header>

      {{-- HERO --}}
      <section class="cwm-hero">
        <div class="cwm-hero-top">
          <span class="cwm-eyebrow">Nilai Portofolio</span>
          <span class="cwm-yield">
            <svg viewBox="0 0 24 24" fill="none"><path d="M4 15l5-5 4 4 7-8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ number_format($yieldPercent, 2, ',', '.') }}%
          </span>
        </div>
        <h2 class="cwm-hero-balance"><span class="rp">Rp</span>{{ number_format($portfolioBalance, 0, ',', '.') }}</h2>
        <div class="cwm-profit">
          <svg viewBox="0 0 24 24" fill="none"><path d="M4 15l5-5 4 4 7-8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M15 6h5v5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          +Rp {{ number_format($todayProfit, 0, ',', '.') }} <em>profit aktif hari ini</em>
        </div>
      </section>

      {{-- STAT TILES --}}
      <section class="cwm-stats">
        <div class="cwm-stat">
          <p class="cwm-stat-label">Produk Aktif</p>
          <p class="cwm-stat-value">{{ number_format($totalProduk, 0, ',', '.') }} <small>Plan</small></p>
        </div>
        <div class="cwm-stat blue">
          <p class="cwm-stat-label">Mulai Dari</p>
          <p class="cwm-stat-value">Rp {{ number_format($produkTermurah, 0, ',', '.') }}</p>
        </div>
        <div class="cwm-stat">
          <p class="cwm-stat-label">Estimasi Harian</p>
          <p class="cwm-stat-value">Rp {{ number_format($estimasiProfit, 0, ',', '.') }}</p>
        </div>
      </section>

      {{-- SECTION --}}
      <div class="cwm-section-head">
        <span class="bar"></span>
        <div>
          <h2>Katalog Investasi</h2>
          <p>Pilih produk & aktifkan paket AI</p>
        </div>
      </div>

      {{-- TABS --}}
      <div class="cwm-tabs" id="marketTabs">
        @foreach($categories as $i => $cat)
          <button type="button" class="cwm-tab {{ $i === 0 ? 'active' : '' }}" data-tab="market-cat-{{ $cat->id }}">
            {{ str_ireplace('Rubik', 'Capital Wave', $cat->name) }}
          </button>
        @endforeach
      </div>

      {{-- PANES --}}
      @forelse($categories as $i => $cat)
        <div class="cwm-pane {{ $i === 0 ? 'active' : '' }}" id="market-cat-{{ $cat->id }}">
          <div class="cwm-list">
            @forelse($cat->products as $product)
              @php
                $catName = strtolower($cat->name ?? '');
                $productName = str_ireplace('Rubik', 'Capital Wave', $product->name ?? 'Capital Asset');

                if(str_contains($catName, 'saham')) { $assetLabel = 'Saham Capital Wave'; $tokenIcon = 'chart'; }
                elseif(str_contains($catName, 'pro')) { $assetLabel = 'Capital Wave Pro'; $tokenIcon = 'diamond'; }
                else { $assetLabel = 'All Asset'; $tokenIcon = 'shield'; }

                $invActive = $activeInvestments[$product->id] ?? null;
                $isOneTimeProduct = in_array((int) $cat->id, [2, 3], true);
                $shouldLockBuyButton = $isOneTimeProduct && $invActive;

                $vipKurang   = (int) ($user->vip_level ?? 0) < (int) ($product->min_vip_level ?? 0);
                $saldoKurang = (int) ($user->saldo ?? 0) < (int) ($product->price ?? 0);

                $profitPercent = (int) ($product->price ?? 0) > 0
                    ? round(((int) ($product->daily_profit ?? 0) / (int) ($product->price ?? 1)) * 100, 1)
                    : 0;

                $seed = (int) (
                    (int) ($product->id ?? 0) + (int) ($product->price ?? 0)
                    + (int) ($product->daily_profit ?? 0) + (int) ($product->total_profit ?? 0)
                    + (int) ($product->duration_days ?? 0)
                );

                $riskDots   = max(1, min(4, ($seed % 3) + 1));
                $profitDots = max(1, min(4, (int) round($profitPercent / 2) + 1));
                $effDots    = max(1, min(4, (($seed >> 2) % 4) + 1));
              @endphp

              <article class="cwm-card">
                <div class="cwm-card-top">
                  <div class="cwm-token"><span>
                    @if($tokenIcon === 'chart')
                      <svg viewBox="0 0 24 24" fill="none"><path d="M4 19V5M8 17v-7M12 17V7M16 17v-4M20 17V4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    @elseif($tokenIcon === 'diamond')
                      <svg viewBox="0 0 24 24" fill="none"><path d="m12 21 8-10-4-7H8l-4 7 8 10Z" stroke="currentColor" stroke-width="1.9" stroke-linejoin="round"/></svg>
                    @else
                      <svg viewBox="0 0 24 24" fill="none"><path d="M12 3 20 7v5c0 4.4-3.2 7.2-8 8.5C7.2 19.2 4 16.4 4 12V7l8-4Z" stroke="currentColor" stroke-width="1.9" stroke-linejoin="round"/></svg>
                    @endif
                  </span></div>
                  <div class="cwm-headline">
                    <h3>{{ $productName }}</h3>
                    <p>{{ $assetLabel }} · <b>Capital Wave</b></p>
                  </div>
                  <div class="cwm-price">
                    <strong>Rp {{ number_format((int)($product->price ?? 0), 0, ',', '.') }}</strong>
                    <span class="cwm-badge">+{{ $profitPercent }}%</span>
                  </div>
                </div>

                {{-- CANDLESTICK PREVIEW --}}
                <div class="cwm-chart-box">
                  <div class="cwm-chart-head">
                    <span class="cwm-chart-lbl">Pergerakan Harga</span>
                    <div class="cwm-chart-tf">
                      <button type="button" class="on">1D</button>
                      <button type="button">1W</button>
                      <button type="button">1M</button>
                    </div>
                  </div>
                  <svg class="cw-chart js-prodchart" viewBox="0 0 340 176"
                    data-price="{{ (int)($product->price ?? 0) }}"
                    data-seed="{{ max($seed, 1) }}"></svg>
                </div>

                {{-- INDICATORS --}}
                <div class="cwm-ind">
                  <div class="cwm-ind-col risk"><span>Resiko</span><div class="cwm-dots">@for($d=1;$d<=4;$d++)<i class="{{ $d <= $riskDots ? 'on' : '' }}"></i>@endfor</div></div>
                  <div class="cwm-ind-col profit"><span>Profit</span><div class="cwm-dots">@for($d=1;$d<=4;$d++)<i class="{{ $d <= $profitDots ? 'on' : '' }}"></i>@endfor</div></div>
                  <div class="cwm-ind-col eff"><span>Efisiensi</span><div class="cwm-dots">@for($d=1;$d<=4;$d++)<i class="{{ $d <= $effDots ? 'on' : '' }}"></i>@endfor</div></div>
                </div>

                {{-- DETAILS 2x2 --}}
                <div class="cwm-detail">
                  <div class="cwm-detail-cell"><span>Modal Aktivasi</span><strong>Rp {{ number_format((int)($product->price ?? 0), 0, ',', '.') }}</strong></div>
                  <div class="cwm-detail-cell"><span>Dividen Harian</span><strong class="blue">Rp {{ number_format((int)($product->daily_profit ?? 0), 0, ',', '.') }}</strong></div>
                  <div class="cwm-detail-cell"><span>Durasi</span><strong>{{ (int)($product->duration_days ?? 0) }} Hari</strong></div>
                  <div class="cwm-detail-cell"><span>Estimasi Hasil</span><strong class="gold">Rp {{ number_format((int)($product->total_profit ?? 0), 0, ',', '.') }}</strong></div>
                </div>

                {{-- ACTION --}}
                <div class="cwm-action">
                  @if($shouldLockBuyButton)
                    <a href="{{ route('investasi.index') }}" class="cwm-buy muted">Sedang Aktif</a>
                  @else
                    <form method="POST" action="{{ url('/product/buy/'.$product->id) }}" class="js-invest-form" style="margin:0;"
                      data-product-name="{{ $productName }}"
                      data-product-price="Rp {{ number_format((int)($product->price ?? 0), 0, ',', '.') }}"
                      data-product-vip="{{ (int)($product->min_vip_level ?? 0) }}"
                      data-user-vip="{{ (int)($user->vip_level ?? 0) }}"
                      data-vip-kurang="{{ $vipKurang ? '1' : '0' }}"
                      data-saldo-kurang="{{ $saldoKurang ? '1' : '0' }}">
                      @csrf
                      <button type="submit" class="cwm-buy">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M13 2 4 14h7l-1 8 9-12h-7l1-8Z" fill="currentColor"/></svg>
                        {{ ($vipKurang || $saldoKurang) ? 'Deposit Sekarang' : 'Aktifkan Paket' }}
                      </button>
                    </form>
                  @endif
                </div>
              </article>
            @empty
              <div class="cwm-empty">Belum ada produk di kategori ini.</div>
            @endforelse
          </div>
        </div>
      @empty
        <div class="cwm-empty">Belum ada kategori produk tersedia.</div>
      @endforelse

      <div class="rb-bottom-spacer"></div>
      @include('partials.bottom-nav')
    </div>
  </main>

  {{-- MODAL --}}
  <div class="vl-modal-overlay" id="vlModal" aria-hidden="true">
    <div class="vl-modal">
      <div class="vl-modal-head">
        <div class="vl-modal-title">
          <span class="vl-modal-icon"><svg width="17" height="17" viewBox="0 0 24 24" fill="none"><path d="M12 8v5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="12" cy="16.5" r="1.1" fill="currentColor"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/></svg></span>
          <span id="vlModalTitle">Informasi</span>
        </div>
        <button type="button" class="vl-modal-close" id="vlModalClose" aria-label="Tutup">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none"><path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </button>
      </div>
      <div class="vl-modal-body" id="vlModalBody"></div>
      <div class="vl-modal-actions">
        <button type="button" class="vl-modal-btn" id="vlModalCancel">Nanti</button>
        <a href="{{ url('/deposit') }}" class="vl-modal-btn primary" id="vlModalAction">Deposit</a>
      </div>
    </div>
  </div>

  <script>
    function seededRandom(seed){
      let value = seed % 2147483647;
      if(value <= 0) value += 2147483646;
      return function(){ value = value * 16807 % 2147483647; return (value - 1) / 2147483646; };
    }

    // ===== Candlestick per produk (styling + animasi) =====
    (function(){
      const fmt0 = v => Math.round(v).toLocaleString('id-ID');
      const charts = Array.from(document.querySelectorAll('.js-prodchart'));
      const reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

      charts.forEach((svg, idx) => {
        const PRICE = parseFloat(svg.dataset.price) || 100000;
        const SEEDV = parseInt(svg.dataset.seed, 10) || (idx + 1);

        const START = PRICE * (1 - (0.06 + (SEEDV % 5) * 0.012));   // run-up 6-11%
        const VMIN = START * 0.985, VMAX = PRICE * 1.03;
        const PLOT_T = 10, PLOT_B = 120, X0 = 10, X1 = 296, N = 30;
        const step = (X1 - X0) / (N - 1), cw = step * 0.55;
        const sy = v => PLOT_T + (VMAX - v) / (VMAX - VMIN) * (PLOT_B - PLOT_T);
        const rnd = seededRandom(SEEDV);
        const span = VMAX - VMIN, prg = PRICE - START, trend = prg / (N - 1), vol = prg * 0.16 + PRICE * 0.006;

        const candles = [];
        let price = START;
        for(let i = 0; i < N; i++){
          const open = price;
          let close = open + ((rnd() - 0.5) * vol) + trend;
          if(i === N - 1) close = PRICE;
          close = Math.max(VMIN + span * 0.02, Math.min(VMAX - span * 0.02, close));
          const hi = Math.min(VMAX - span * 0.01, Math.max(open, close) + rnd() * vol * 0.35);
          const lo = Math.max(VMIN + span * 0.01, Math.min(open, close) - rnd() * vol * 0.35);
          candles.push({ open, close, hi, lo, x: X0 + step * i });
          price = close;
        }

        const P = [];
        for(let i = 0; i < 5; i++){
          const v = VMAX - (VMAX - VMIN) * (i / 4), y = sy(v);
          P.push(`<line class="cw-grid" x1="8" y1="${y.toFixed(1)}" x2="298" y2="${y.toFixed(1)}"/>`);
          P.push(`<text class="cw-axis" x="303" y="${(y + 3).toFixed(1)}">${fmt0(v)}</text>`);
        }
        const volBase = 168;
        candles.forEach(c => {
          const up = c.close >= c.open;
          const h = Math.min(28, 5 + Math.abs(c.close - c.open) / prg * 80 + rnd() * 8);
          P.push(`<rect class="${up ? 'cw-vol-up' : 'cw-vol-down'}" x="${(c.x - cw/2).toFixed(1)}" y="${(volBase - h).toFixed(1)}" width="${cw.toFixed(1)}" height="${h.toFixed(1)}" rx="1"/>`);
        });
        candles.forEach((c, i) => {
          const up = c.close >= c.open, cls = up ? 'cw-up' : 'cw-down';
          const yO = sy(c.open), yC = sy(c.close), yH = sy(c.hi), yL = sy(c.lo);
          const top = Math.min(yO, yC), bh = Math.max(1.4, Math.abs(yC - yO));
          P.push(`<g class="cw-candle" style="animation-delay:${i * 15}ms"><line class="cw-wick ${cls}" x1="${c.x.toFixed(1)}" y1="${yH.toFixed(1)}" x2="${c.x.toFixed(1)}" y2="${yL.toFixed(1)}"/><rect class="${cls}" x="${(c.x - cw/2).toFixed(1)}" y="${top.toFixed(1)}" width="${cw.toFixed(1)}" height="${bh.toFixed(1)}" rx="1.1"/></g>`);
        });
        const ma = p => { const pts = []; for(let i = 0; i < N; i++){ let s = 0, c = 0; for(let j = Math.max(0, i - p + 1); j <= i; j++){ s += candles[j].close; c++; } pts.push([candles[i].x, sy(s / c)]); } return pts; };
        const smooth = pts => { let d = `M ${pts[0][0].toFixed(1)} ${pts[0][1].toFixed(1)}`; for(let i = 1; i < pts.length; i++){ const p = pts[i-1], q = pts[i], mx = (p[0] + q[0]) / 2; d += ` C ${mx.toFixed(1)} ${p[1].toFixed(1)}, ${mx.toFixed(1)} ${q[1].toFixed(1)}, ${q[0].toFixed(1)} ${q[1].toFixed(1)}`; } return d; };
        P.push(`<path class="cw-ma2" d="${smooth(ma(10))}"/>`);
        P.push(`<path class="cw-ma1" d="${smooth(ma(5))}"/>`);

        const hi = Math.max(...candles.map(c => c.hi)), hiY = sy(hi);
        P.push(`<line class="cw-hi-line" x1="8" y1="${hiY.toFixed(1)}" x2="298" y2="${hiY.toFixed(1)}"/>`);
        P.push(`<text class="cw-hi-tag" x="10" y="${(hiY - 4).toFixed(1)}">H: ${fmt0(hi)}</text>`);

        ['25 Jun','5 Jul','15 Jul','27 Jul'].forEach((d, i, a) => {
          const x = X0 + (X1 - X0) * (i / (a.length - 1));
          const anchor = i === 0 ? 'start' : (i === a.length - 1 ? 'end' : 'middle');
          P.push(`<text class="cw-axis" x="${x.toFixed(1)}" y="176" text-anchor="${anchor}">${d}</text>`);
        });

        const baseY = sy(PRICE);
        P.push(`<g class="cw-cur-g"><line class="cw-cur-line" x1="8" y1="${baseY.toFixed(1)}" x2="296" y2="${baseY.toFixed(1)}"/><circle class="cw-cur-dot" cx="296" cy="${baseY.toFixed(1)}" r="3"/><g transform="translate(300 ${baseY.toFixed(1)})"><rect class="cw-cur-tag-bg" x="0" y="-7" width="46" height="14" rx="3"/><text class="cw-cur-tag-tx" x="23" y="3" text-anchor="middle">${fmt0(PRICE)}</text></g></g>`);

        svg.innerHTML = P.join('');

        if(reduce) return;

        // animasi harga (styling)
        const curG = svg.querySelector('.cw-cur-g');
        const tagTx = svg.querySelector('.cw-cur-tag-tx');
        const band = PRICE * 0.004;
        const tick = () => {
          const target = PRICE - band + rnd() * band * 2;
          curG.style.transform = `translateY(${(sy(target) - baseY).toFixed(2)}px)`;
          if(tagTx) tagTx.textContent = fmt0(target);
        };
        setTimeout(tick, 700 + idx * 180);
        setInterval(tick, 2600 + (idx % 5) * 130);
      });
    })();

    // ===== Tabs =====
    (function(){
      const tabs = Array.from(document.querySelectorAll('.cwm-tab'));
      const panes = Array.from(document.querySelectorAll('.cwm-pane'));
      tabs.forEach(tab => tab.addEventListener('click', function(){
        const target = this.dataset.tab;
        tabs.forEach(t => t.classList.remove('active'));
        panes.forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        const pane = document.getElementById(target);
        if(pane) pane.classList.add('active');
      }));
    })();

    // ===== Buy validation modal =====
    (function(){
      const modal = document.getElementById('vlModal');
      const modalTitle = document.getElementById('vlModalTitle');
      const modalBody = document.getElementById('vlModalBody');
      const modalClose = document.getElementById('vlModalClose');
      const modalCancel = document.getElementById('vlModalCancel');
      const modalAction = document.getElementById('vlModalAction');
      function openModal(title, body, actionUrl, actionText){
        if(!modal) return;
        modalTitle.textContent = title; modalBody.innerHTML = body;
        if(actionUrl){ modalAction.href = actionUrl; modalAction.textContent = actionText || 'Lanjutkan'; modalAction.style.display = 'flex'; }
        else { modalAction.style.display = 'none'; }
        modal.classList.add('show'); modal.setAttribute('aria-hidden', 'false');
      }
      function closeModal(){ if(!modal) return; modal.classList.remove('show'); modal.setAttribute('aria-hidden', 'true'); }
      modalClose?.addEventListener('click', closeModal);
      modalCancel?.addEventListener('click', closeModal);
      modal?.addEventListener('click', e => { if(e.target === modal) closeModal(); });
      document.querySelectorAll('.js-invest-form').forEach(form => {
        form.addEventListener('submit', function(e){
          const vipKurang = this.dataset.vipKurang === '1', saldoKurang = this.dataset.saldoKurang === '1';
          if(vipKurang || saldoKurang){
            e.preventDefault();
            const name = this.dataset.productName || 'Produk';
            const price = this.dataset.productPrice || '-';
            const productVip = this.dataset.productVip || '0', userVip = this.dataset.userVip || '0';
            if(vipKurang){
              openModal('VIP Belum Cukup', `Produk <b>${name}</b> membutuhkan minimal <b>VIP ${productVip}</b>.<br>Status kamu saat ini <b>VIP ${userVip}</b>.`, '{{ url('/akun') }}', 'Lihat Akun');
              return;
            }
            if(saldoKurang){
              openModal('Saldo Belum Cukup', `Saldo kamu belum cukup untuk mengaktifkan <b>${name}</b>.<br>Modal aktivasi: <b>${price}</b>.`, '{{ url('/deposit') }}', 'Deposit');
            }
          }
        });
      });
    })();
  </script>
</body>
</html>
