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
  <title>Dashboard | Velora Finance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --bg:#fffaf3;
      --paper:#ffffff;
      --paper2:#fff7ea;
      --panel:#ffffff;
      --panel2:#fff3df;
      --text:#3b1116;
      --soft:#5b2a30;
      --muted:#8b6b70;
      --muted2:#b89aa0;
      --border:rgba(75,16,21,.09);
      --border2:rgba(75,16,21,.14);

      --brand:#7d3cff;
      --brand2:#c957ff;
      --purple:#8e46ff;
      --violet:#d35cff;
      --gold:#ffb52e;
      --green:#22c982;
      --rose:#ef4444;
      --maroon:#4b1015;

      --shadow:0 22px 55px rgba(75,16,21,.10);
      --shadow-soft:0 12px 28px rgba(75,16,21,.075);
      --radius:28px;
      --radius-sm:20px;
    }

    *{ box-sizing:border-box; }
    html,body{ min-height:100%; }

    body{
      margin:0;
      color:var(--text);
      font-family:Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background:
        radial-gradient(780px 420px at 50% -120px, rgba(201,87,255,.10), transparent 64%),
        linear-gradient(180deg, #fffdf9 0%, #fff8ee 45%, #f7eefc 100%);
      overflow-x:hidden;
      -webkit-tap-highlight-color:transparent;
    }

    body::before{
      content:"";
      position:fixed;
      inset:0;
      pointer-events:none;
      background:
        linear-gradient(rgba(75,16,21,.018) 1px, transparent 1px),
        linear-gradient(90deg, rgba(75,16,21,.012) 1px, transparent 1px);
      background-size:34px 34px;
      opacity:.55;
      mask-image:linear-gradient(180deg, rgba(0,0,0,.45), transparent 80%);
      -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.45), transparent 80%);
      z-index:0;
    }

    body::after{
      content:"";
      position:fixed;
      inset:0;
      pointer-events:none;
      background:
        radial-gradient(circle at 6% 18%, rgba(255,181,46,.12), transparent 30%),
        radial-gradient(circle at 92% 28%, rgba(201,87,255,.11), transparent 30%),
        radial-gradient(circle at 50% 100%, rgba(125,60,255,.07), transparent 34%);
      z-index:0;
    }

    a{ color:inherit; text-decoration:none; }
    button,input{ font-family:inherit; }

    .vl-page{
      width:100%;
      min-height:100vh;
      position:relative;
      z-index:1;
      display:flex;
      justify-content:center;
      padding:14px 10px 0;
    }

    .vl-phone{
      width:100%;
      max-width:430px;
      min-height:100vh;
      position:relative;
      padding:8px 4px 104px;
    }

    /* HEADER */
    .vl-topbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
      padding:2px 2px 0;
    }

    .vl-brand{ display:flex; align-items:center; gap:11px; min-width:0; }
    .vl-logo{
      width:50px;
      height:50px;
      border-radius:18px;
      display:grid;
      place-items:center;
      overflow:hidden;
      background:linear-gradient(135deg, rgba(255,181,46,.12), rgba(201,87,255,.12), rgba(125,60,255,.10)), #fff;
      border:1px solid rgba(75,16,21,.09);
      box-shadow:0 12px 26px rgba(75,16,21,.075), 0 0 0 5px rgba(255,181,46,.08), inset 0 1px 0 rgba(255,255,255,.9);
      flex:0 0 auto;
    }

    .vl-logo img{ width:44px; height:44px; object-fit:contain; display:block; }
    .vl-brand-copy{ min-width:0; }
    .vl-brand-copy span{
      display:block;
      margin-bottom:5px;
      color:#9a6e72;
      font-size:11px;
      line-height:1;
      font-weight:850;
      letter-spacing:.16em;
      text-transform:uppercase;
    }

    .vl-brand-copy h1{
      margin:0;
      font-size:23px;
      line-height:1;
      font-weight:950;
      letter-spacing:-.055em;
      color:#3b1116;
      white-space:nowrap;
    }

    .vl-actions{ display:flex; align-items:center; gap:9px; flex:0 0 auto; }
    .vl-icon-btn{
      width:42px;
      height:42px;
      border-radius:999px;
      border:1px solid rgba(75,16,21,.09);
      background:#fff;
      color:#5b2a30;
      display:grid;
      place-items:center;
      box-shadow:0 12px 26px rgba(75,16,21,.075);
      position:relative;
      transition:.18s ease;
    }

    .vl-icon-btn:hover{ transform:translateY(-1px); color:var(--brand); box-shadow:0 16px 32px rgba(75,16,21,.11); }
    .vl-icon-btn svg{ width:20px; height:20px; }
    .vl-dot{
      position:absolute;
      right:9px;
      top:8px;
      width:8px;
      height:8px;
      border-radius:999px;
      background:#ef4444;
      border:2px solid #fff;
      box-shadow:0 0 0 3px rgba(239,68,68,.12);
    }

    /* HERO */
    .vl-hero{
      position:relative;
      overflow:visible;
      border-radius:30px;
      background:
        radial-gradient(360px 210px at 95% 0%, rgba(255,255,255,.18), transparent 60%),
        linear-gradient(135deg, #ffb52e 0%, #c957ff 46%, #7d3cff 100%);
      border:1px solid rgba(255,255,255,.55);
      box-shadow:0 24px 52px rgba(201,87,255,.24), inset 0 1px 0 rgba(255,255,255,.22);
      padding:18px;
      color:#fff;
    }

    .vl-hero::before{
      content:"";
      position:absolute;
      inset:0;
      pointer-events:none;
      border-radius:inherit;
      background:
        linear-gradient(135deg, rgba(255,255,255,.20), transparent 34%),
        radial-gradient(circle at 88% 22%, rgba(255,255,255,.18), transparent 28%);
    }

    .vl-hero::after{
      content:"";
      position:absolute;
      right:20px;
      top:20px;
      width:34px;
      height:18px;
      border-radius:999px;
      border:2px solid rgba(255,255,255,.65);
      border-left-color:transparent;
      border-right-color:transparent;
      opacity:.8;
      pointer-events:none;
    }

    .vl-hero > *{ position:relative; z-index:1; }
    .vl-hero-head{ display:grid; grid-template-columns:minmax(0,1fr) auto; gap:14px; align-items:start; }

    .vl-kicker{
      display:inline-flex;
      align-items:center;
      gap:7px;
      min-height:28px;
      padding:0 11px;
      border-radius:999px;
      color:#fff9ef;
      background:rgba(255,255,255,.14);
      border:1px solid rgba(255,255,255,.18);
      backdrop-filter:blur(10px);
      -webkit-backdrop-filter:blur(10px);
      font-size:10px;
      font-weight:950;
      letter-spacing:.08em;
      text-transform:uppercase;
    }

    .vl-kicker::before{ content:""; width:7px; height:7px; border-radius:999px; background:#fff3ba; box-shadow:0 0 0 4px rgba(255,243,186,.20); }
    .vl-portfolio-label{ margin:14px 0 8px; color:rgba(255,255,255,.70); font-size:12px; line-height:1.1; font-weight:650; }
    .vl-balance{ margin:0; color:#fff; font-size:34px; line-height:1.02; letter-spacing:-.075em; font-weight:950; text-shadow:0 10px 26px rgba(0,0,0,.22); }

    .vl-market-pill{
      min-width:82px;
      min-height:38px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:6px;
      padding:0 12px;
      border-radius:999px;
      color:#fff;
      background:rgba(255,255,255,.15);
      border:1px solid rgba(255,255,255,.22);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.20);
      font-size:12px;
      font-weight:950;
      white-space:nowrap;
      backdrop-filter:blur(10px);
      -webkit-backdrop-filter:blur(10px);
    }

    .vl-market-pill svg{ width:15px; height:15px; }

    .vl-chart-panel{
      margin-top:17px;
      min-height:128px;
      border-radius:24px;
      padding:13px;
      background:rgba(255,255,255,.12);
      border:1px solid rgba(255,255,255,.16);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.12);
      overflow:hidden;
    }

    .vl-chart-top{ display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:8px; }
    .vl-chart-top span{ color:rgba(255,255,255,.64); font-size:10.5px; font-weight:750; }
    .vl-chart-top strong{ color:#fff3ba; font-size:11px; font-weight:950; }
    .vl-main-chart{ width:100%; height:82px; display:block; overflow:visible; }
    .vl-main-chart .chart-grid{ stroke:rgba(255,255,255,.12); stroke-width:1; }
    .vl-main-chart .chart-area{ fill:url(#mainChartFill); opacity:.72; }
    .vl-main-chart .chart-line{ fill:none; stroke:url(#mainChartStroke); stroke-width:3.2; stroke-linecap:round; stroke-linejoin:round; filter:drop-shadow(0 5px 10px rgba(0,0,0,.12)); stroke-dasharray:420; stroke-dashoffset:420; animation:vlLineDraw 1.25s cubic-bezier(.22,.8,.22,1) forwards; }
    .vl-main-chart .chart-glow{ fill:none; stroke:rgba(255,255,255,.20); stroke-width:8; stroke-linecap:round; stroke-linejoin:round; filter:blur(1px); opacity:.7; }
    .vl-live-dot{ fill:#fff; filter:drop-shadow(0 0 10px rgba(255,255,255,.65)); animation:vlDotPulse 1.7s ease-in-out infinite; transform-origin:center; }

    .vl-metrics{ margin-top:11px; display:grid; grid-template-columns:1fr; gap:9px; }
    .vl-metric{ min-height:62px; border-radius:20px; padding:11px 12px; background:rgba(255,255,255,.13); border:1px solid rgba(255,255,255,.16); box-shadow:inset 0 1px 0 rgba(255,255,255,.10); }
    .vl-metric span{ display:block; margin-bottom:7px; color:rgba(255,255,255,.62); font-size:10px; font-weight:750; }
    .vl-metric strong{ display:block; color:#fff; font-size:13px; line-height:1.15; letter-spacing:-.02em; font-weight:950; }
    .vl-metric strong.is-up{ color:#fff3ba; }

    .vl-hero-actions{ margin-top:11px; display:grid; grid-template-columns:1fr 1fr; gap:9px; }
    .vl-main-btn{ min-height:50px; border-radius:18px; display:flex; align-items:center; justify-content:center; gap:9px; font-size:12.5px; line-height:1; font-weight:950; transition:.18s ease; }
    .vl-main-btn:hover{ transform:translateY(-1px); filter:brightness(1.02); }
    .vl-main-btn svg{ width:18px; height:18px; }
    .vl-main-btn.deposit{ color:#4a1218; background:linear-gradient(135deg,#ffb52e,#ffd45c); border:1px solid rgba(255,255,255,.55); box-shadow:0 14px 28px rgba(255,181,46,.38), inset 0 1px 0 rgba(255,255,255,.55); }
    .vl-main-btn.withdraw{ color:#fff; background:linear-gradient(135deg,#7d3cff,#c957ff); border:1px solid rgba(255,255,255,.22); box-shadow:0 14px 28px rgba(125,60,255,.38), inset 0 1px 0 rgba(255,255,255,.18); }

    /* PROMO */
    .vl-promo{ margin-top:14px; position:relative; overflow:hidden; border-radius:26px; background:#fff; border:1px solid var(--border); box-shadow:var(--shadow-soft); }
    .vl-promo-viewport{ overflow:hidden; width:100%; aspect-ratio:9/4; border-radius:26px; }
    .vl-promo-track{ height:100%; display:flex; transition:transform .52s cubic-bezier(.22,.8,.22,1); will-change:transform; cursor:grab; }
    .vl-promo-track.is-dragging{ transition:none !important; cursor:grabbing; }
    .vl-promo-slide{ flex:0 0 100%; width:100%; height:100%; display:block; }
    .vl-promo-img{ width:100%; height:100%; object-fit:cover; display:block; background:#fff2df; }
    .vl-promo-dots{ position:absolute; left:50%; bottom:9px; transform:translateX(-50%); z-index:5; display:flex; gap:6px; padding:5px 8px; border-radius:999px; background:rgba(255,255,255,.82); border:1px solid rgba(75,16,21,.09); backdrop-filter:blur(10px); -webkit-backdrop-filter:blur(10px); box-shadow:0 8px 18px rgba(75,16,21,.12); }
    .vl-promo-dot{ width:7px; height:7px; border:0; padding:0; border-radius:999px; background:rgba(148,104,112,.38); transition:.22s ease; cursor:pointer; }
    .vl-promo-dot.active{ width:19px; background:linear-gradient(90deg,var(--gold),var(--violet),var(--brand)); box-shadow:0 0 13px rgba(201,87,255,.25); }

    /* TRUST */
    .vl-trust{ margin-top:14px; display:grid; grid-template-columns:minmax(0,1fr) auto; gap:12px; align-items:center; min-height:64px; padding:11px 13px; border-radius:24px; background:#fff; border:1px solid var(--border); box-shadow:var(--shadow-soft); }
    .vl-trust-title{ color:#3b1116; font-size:13px; line-height:1.2; font-weight:950; letter-spacing:-.025em; }
    .vl-trust-sub{ margin-top:4px; color:#9a6e72; font-size:10px; font-weight:800; }
    .vl-trust-logos{ display:flex; align-items:center; gap:8px; flex:0 0 auto; }
    .vl-trust-logos img{ width:auto; object-fit:contain; filter:drop-shadow(0 3px 5px rgba(75,16,21,.075)) saturate(1.02); }
    .vl-trust-logos .ojk{ height:42px; }
    .vl-trust-logos .bappebti{ height:34px; }

    /* INVITE */
    .vl-invite{ margin-top:14px; position:relative; overflow:hidden; min-height:150px; border-radius:28px; padding:18px; background:linear-gradient(135deg,#ffffff 0%,#fff2df 100%); border:1px solid var(--border); box-shadow:var(--shadow-soft); color:#3b1116; }
    .vl-invite::after{ content:""; position:absolute; right:-44px; bottom:-60px; width:188px; height:188px; border-radius:50%; background:rgba(201,87,255,.10); }
    .vl-invite-content{ position:relative; z-index:2; max-width:225px; }
    .vl-invite h2{ margin:0; font-size:22px; line-height:1.04; letter-spacing:-.06em; font-weight:950; color:#3b1116; }
    .vl-invite h2 span{ color:var(--brand); }
    .vl-invite p{ margin:8px 0 0; color:#9a6e72; font-size:11.5px; line-height:1.4; font-weight:750; }
    .vl-invite-btn{ margin-top:13px; display:inline-flex; align-items:center; justify-content:center; gap:8px; min-height:42px; padding:0 16px; border-radius:999px; color:#fff; background:linear-gradient(135deg,var(--gold),var(--violet),var(--brand)); border:1px solid rgba(255,255,255,.24); box-shadow:0 14px 28px rgba(201,87,255,.20); font-size:12px; font-weight:950; }
     .vl-invite-coin{ position:absolute; z-index:2; right:22px; top:24px; width:74px; height:74px; border-radius:26px; display:grid; place-items:center; color:#fff; background:linear-gradient(160deg,#ffffff 0%,#f8f4ff 60%,#fff5e8 100%); border:1px solid rgba(201,87,255,.14); box-shadow:0 16px 30px rgba(201,87,255,.14), 0 4px 12px rgba(75,16,21,.07), inset 0 1px 0 rgba(255,255,255,.95); animation:vlFloat 4s ease-in-out infinite; }
    .vl-invite-coin svg{ width:36px; height:36px; }

    /* SECTION */
    .vl-section{ margin-top:20px; }
    .vl-section-head{ display:flex; align-items:flex-end; justify-content:space-between; gap:12px; margin-bottom:13px; padding:0 2px; }
    .vl-section-title h2{ margin:0; color:#3b1116; font-size:18px; line-height:1.15; letter-spacing:-.035em; font-weight:900; }
    .vl-section-title p{ margin:5px 0 0; color:#8b6b70; font-size:11px; font-weight:550; }
    .vl-see-all{ display:inline-flex; align-items:center; gap:5px; color:var(--brand); font-size:11.5px; font-weight:850; white-space:nowrap; }
    .vl-see-all svg{ width:13px; height:13px; }

    /* CATEGORY */
    .vl-categories{ overflow:hidden; margin:0 -2px; position:relative; }
    .vl-category-track{ display:flex; gap:10px; overflow:auto; padding:2px 2px 8px; scrollbar-width:none; -webkit-overflow-scrolling:touch; }
    .vl-category-track::-webkit-scrollbar{ display:none; }
    .vl-cat{ flex:0 0 116px; min-height:124px; border:0; border-radius:24px; padding:12px; color:#3b1116; background:#fff; border:1px solid var(--border); box-shadow:var(--shadow-soft); text-align:left; cursor:pointer; transition:.18s ease; }
    .vl-cat:hover,.vl-cat.active{ transform:translateY(-2px); border-color:rgba(201,87,255,.24); box-shadow:0 18px 34px rgba(75,16,21,.11), 0 0 0 4px rgba(255,181,46,.10); }
    .vl-cat.regular{ --cat-accent:#7d3cff; --cat-accent2:#c957ff; --cat-soft:#f6efff; }
    .vl-cat.daily{ --cat-accent:#ffb52e; --cat-accent2:#ffcf57; --cat-soft:#fff5df; }
    .vl-cat.premium{ --cat-accent:#c957ff; --cat-accent2:#7d3cff; --cat-soft:#f7efff; }
    .vl-cat-icon{ width:42px; height:42px; border-radius:17px; display:grid; place-items:center; color:var(--cat-accent); background:var(--cat-soft); box-shadow:inset 0 0 0 1px rgba(17,24,39,.04); margin-bottom:15px; }
    .vl-cat-icon svg{ width:21px; height:21px; }
    .vl-cat strong{ display:block; color:#3b1116; font-size:13px; line-height:1.16; font-weight:900; letter-spacing:-.02em; }
    .vl-cat span{ display:block; margin-top:6px; color:#8b6b70; font-size:10.5px; line-height:1.2; font-weight:650; }

    /* ASSET LIST */
    .vl-products{ display:flex; flex-direction:column; gap:11px; }
    .vl-product-pane{ display:none; }
    .vl-product-pane.active{ display:block; }
    .vl-asset-card{ position:relative; overflow:hidden; border-radius:25px; background:#fff; border:1px solid var(--border); box-shadow:var(--shadow-soft); transition:.18s ease; }
    .vl-asset-card + .vl-asset-card{ margin-top:11px; }
    .vl-asset-card::before{ content:""; position:absolute; inset:0; pointer-events:none; background:radial-gradient(220px 120px at 92% 2%, var(--asset-soft), transparent 68%); opacity:.95; }
    .vl-asset-card:hover{ transform:translateY(-1px); border-color:rgba(201,87,255,.20); box-shadow:0 18px 36px rgba(75,16,21,.11); }
    .vl-asset-card.regular{ --asset-accent:#7d3cff; --asset-accent2:#c957ff; --asset-soft:rgba(201,87,255,.11); --asset-bg:#f6efff; }
    .vl-asset-card.daily{ --asset-accent:#ffb52e; --asset-accent2:#ffcf57; --asset-soft:rgba(255,181,46,.16); --asset-bg:#fff5df; }
    .vl-asset-card.premium{ --asset-accent:#c957ff; --asset-accent2:#7d3cff; --asset-soft:rgba(201,87,255,.13); --asset-bg:#f7efff; }

    .vl-asset-row{ position:relative; z-index:1; min-height:104px; display:grid; grid-template-columns:52px minmax(0,1fr) 118px; grid-template-areas:"icon info chart" "icon meta chart"; gap:6px 11px; align-items:center; padding:14px 12px; }
    .vl-token{ grid-area:icon; width:50px; height:50px; border-radius:19px; display:grid; place-items:center; color:var(--asset-accent); background:var(--asset-bg); box-shadow:inset 0 0 0 1px rgba(17,24,39,.04); font-size:15px; font-weight:950; }
    .vl-asset-info{ grid-area:info; min-width:0; align-self:end; }
    .vl-asset-info h3{ margin:0; color:#3b1116; font-size:14px; line-height:1.17; letter-spacing:-.025em; font-weight:950; white-space:normal; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; }
    .vl-asset-info p{ margin:5px 0 0; color:#8b6b70; font-size:10.5px; font-weight:650; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .vl-asset-meta{ grid-area:meta; display:flex; align-items:center; gap:7px; min-width:0; align-self:start; }
    .vl-price{ color:#3b1116; font-size:12px; font-weight:950; white-space:nowrap; letter-spacing:-.02em; }
    .vl-profit-badge{ min-height:22px; display:inline-flex; align-items:center; padding:0 8px; border-radius:999px; color:var(--asset-accent); background:var(--asset-bg); font-size:10px; line-height:1; font-weight:950; white-space:nowrap; }
    .vl-spark{ grid-area:chart; width:100%; height:58px; align-self:center; overflow:visible; }
    .vl-spark .spark-grid{ stroke:rgba(75,16,21,.10); stroke-width:1; }
    .vl-spark .spark-area{ fill:var(--asset-accent); opacity:.12; }
    .vl-spark .spark-line{ fill:none; stroke:var(--asset-accent); stroke-width:2.8; stroke-linecap:round; stroke-linejoin:round; filter:drop-shadow(0 5px 9px rgba(75,16,21,.06)); stroke-dasharray:260; stroke-dashoffset:260; animation:vlLineDraw .95s cubic-bezier(.22,.8,.22,1) forwards; }
    .vl-spark .spark-glow{ fill:none; stroke:var(--asset-accent); stroke-width:7; opacity:.10; stroke-linecap:round; stroke-linejoin:round; filter:blur(1px); }
    .vl-spark .spark-dot{ fill:var(--asset-accent); filter:drop-shadow(0 0 10px var(--asset-soft)); animation:vlDotPulse 1.9s ease-in-out infinite; transform-origin:center; }

    .vl-asset-detail{ position:relative; z-index:1; display:grid; grid-template-columns:repeat(3,1fr); border-top:1px solid var(--border); background:#fffaf3; }
    .vl-detail{ padding:11px 10px; }
    .vl-detail + .vl-detail{ border-left:1px solid var(--border); }
    .vl-detail span{ display:block; margin-bottom:5px; color:#9a6e72; font-size:9.5px; line-height:1.1; font-weight:650; }
    .vl-detail strong{ color:#3b1116; font-size:11px; line-height:1.15; font-weight:850; letter-spacing:-.01em; }
    .vl-detail strong.accent{ color:var(--asset-accent); }
    .vl-asset-action{ position:relative; z-index:1; padding:0 12px 12px; background:#fffaf3; }
    .vl-buy{ width:100%; min-height:45px; border:0; border-radius:16px; display:flex; align-items:center; justify-content:center; gap:8px; color:#fff; background:linear-gradient(135deg,var(--asset-accent),var(--asset-accent2)); box-shadow:0 14px 26px var(--asset-soft); font-size:12.5px; font-weight:950; cursor:pointer; transition:.18s ease; }
    .vl-buy:hover{ transform:translateY(-1px); filter:brightness(1.03); }
    .vl-buy.muted{ color:#8b6b70; background:#fff0db; border:1px solid rgba(17,24,39,.06); box-shadow:none; cursor:not-allowed; }
    .vl-empty{ padding:18px 14px; border-radius:22px; background:#fff; border:1px dashed rgba(75,16,21,.22); color:#8b6b70; text-align:center; font-size:12.5px; font-weight:750; }
    .vl-bottom-spacer{ height:96px; }


    /* CS / CHANNEL WELCOME POPUP */
.vl-cs-popup{
  position:fixed;
  inset:0;
  z-index:100000;
  display:none;
  align-items:center;
  justify-content:center;
  padding:20px 14px;
  background:
    radial-gradient(circle at 20% 10%, rgba(255,181,46,.18), transparent 34%),
    radial-gradient(circle at 90% 22%, rgba(125,60,255,.22), transparent 38%),
    rgba(38, 10, 45, .58);
  backdrop-filter:blur(18px);
  -webkit-backdrop-filter:blur(18px);
}

.vl-cs-popup.show{
  display:flex;
}

.vl-cs-card{
  width:100%;
  max-width:420px;
  position:relative;
  overflow:hidden;
  border-radius:34px;
  background:
    radial-gradient(280px 180px at 100% 0%, rgba(201,87,255,.22), transparent 64%),
    radial-gradient(260px 170px at 0% 100%, rgba(255,181,46,.18), transparent 62%),
    linear-gradient(180deg, rgba(255,255,255,.98), rgba(255,248,239,.96));
  border:1px solid rgba(255,255,255,.76);
  box-shadow:
    0 34px 90px rgba(41, 10, 55, .34),
    0 0 0 1px rgba(125,60,255,.08),
    inset 0 1px 0 rgba(255,255,255,.92);
  animation:vlCsIn .32s cubic-bezier(.22,.8,.22,1) both;
}

.vl-cs-card::before{
  content:"";
  position:absolute;
  left:-90px;
  top:-80px;
  width:190px;
  height:190px;
  border-radius:999px;
  background:linear-gradient(135deg, rgba(255,181,46,.28), rgba(201,87,255,.20));
  filter:blur(8px);
}

.vl-cs-card::after{
  content:"";
  position:absolute;
  right:-70px;
  bottom:-90px;
  width:210px;
  height:210px;
  border-radius:999px;
  background:linear-gradient(135deg, rgba(125,60,255,.18), rgba(255,181,46,.16));
  filter:blur(10px);
}

.vl-cs-inner{
  position:relative;
  z-index:2;
  padding:22px;
}

.vl-cs-top{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:12px;
  margin-bottom:18px;
}

.vl-cs-brand{
  display:flex;
  align-items:center;
  gap:12px;
  min-width:0;
}

.vl-cs-logo{
  width:52px;
  height:52px;
  border-radius:19px;
  display:grid;
  place-items:center;
  background:
    linear-gradient(135deg, rgba(255,181,46,.18), rgba(201,87,255,.16), rgba(125,60,255,.13)),
    #fff;
  border:1px solid rgba(75,16,21,.08);
  box-shadow:0 16px 32px rgba(75,16,21,.10);
  overflow:hidden;
  flex:0 0 auto;
}

.vl-cs-logo img{
  width:44px;
  height:44px;
  object-fit:contain;
  display:block;
}

.vl-cs-brand span{
  display:block;
  margin-bottom:5px;
  font-size:10px;
  font-weight:950;
  letter-spacing:.16em;
  color:#9b6b72;
  text-transform:uppercase;
}

.vl-cs-brand strong{
  display:block;
  color:#3b1116;
  font-size:19px;
  line-height:1;
  font-weight:950;
  letter-spacing:-.045em;
}

.vl-cs-close{
  width:40px;
  height:40px;
  border:0;
  border-radius:15px;
  color:#5b2a30;
  background:rgba(255,255,255,.72);
  box-shadow:0 10px 24px rgba(75,16,21,.08);
  display:grid;
  place-items:center;
  cursor:pointer;
}

.vl-cs-badge{
  display:inline-flex;
  align-items:center;
  gap:8px;
  min-height:32px;
  padding:0 12px;
  border-radius:999px;
  color:#fff;
  background:linear-gradient(135deg, var(--gold), var(--violet), var(--brand));
  box-shadow:0 14px 28px rgba(125,60,255,.22);
  font-size:10.5px;
  font-weight:950;
  letter-spacing:.08em;
  text-transform:uppercase;
}

.vl-cs-badge::before{
  content:"";
  width:7px;
  height:7px;
  border-radius:999px;
  background:#fff3ba;
  box-shadow:0 0 0 5px rgba(255,255,255,.18);
}

.vl-cs-title{
  margin:14px 0 0;
  color:#321047;
  font-size:27px;
  line-height:1.04;
  font-weight:950;
  letter-spacing:-.07em;
}

.vl-cs-title span{
  color:var(--brand);
}

.vl-cs-desc{
  margin:10px 0 0;
  max-width:330px;
  color:#8b6b70;
  font-size:12.5px;
  line-height:1.55;
  font-weight:720;
}

.vl-cs-info{
  margin-top:18px;
  display:grid;
  gap:10px;
}

.vl-cs-info-item{
  min-height:58px;
  display:flex;
  align-items:center;
  gap:12px;
  padding:12px;
  border-radius:22px;
  background:rgba(255,255,255,.74);
  border:1px solid rgba(75,16,21,.08);
  box-shadow:0 12px 28px rgba(75,16,21,.065);
}

.vl-cs-info-icon{
  width:38px;
  height:38px;
  border-radius:15px;
  display:grid;
  place-items:center;
  color:#fff;
  background:linear-gradient(135deg, var(--gold), var(--violet), var(--brand));
  flex:0 0 auto;
}

.vl-cs-info-icon svg{
  width:19px;
  height:19px;
}

.vl-cs-info-text{
  min-width:0;
}

.vl-cs-info-text span{
  display:block;
  margin-bottom:4px;
  color:#9a6e72;
  font-size:10px;
  font-weight:850;
  text-transform:uppercase;
  letter-spacing:.08em;
}

.vl-cs-info-text strong{
  display:block;
  color:#3b1116;
  font-size:13px;
  line-height:1.15;
  font-weight:950;
  overflow:hidden;
  text-overflow:ellipsis;
  white-space:nowrap;
}

.vl-cs-actions{
  margin-top:18px;
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:10px;
}

.vl-cs-btn{
  min-height:50px;
  border-radius:18px;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:9px;
  font-size:12.5px;
  font-weight:950;
  transition:.18s ease;
}

.vl-cs-btn:hover{
  transform:translateY(-1px);
  filter:brightness(1.02);
}

.vl-cs-btn svg{
  width:18px;
  height:18px;
}

.vl-cs-btn.cs{
  color:#4a1218;
  background:linear-gradient(135deg,#ffb52e,#ffd45c);
  box-shadow:0 16px 28px rgba(255,181,46,.28);
}

.vl-cs-btn.channel{
  color:#fff;
  background:linear-gradient(135deg,#7d3cff,#c957ff);
  box-shadow:0 16px 28px rgba(125,60,255,.30);
}

.vl-cs-foot{
  margin-top:14px;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  color:#9a6e72;
  font-size:10.5px;
  font-weight:800;
}

.vl-cs-foot svg{
  width:14px;
  height:14px;
  color:var(--gold);
}

@keyframes vlCsIn{
  from{
    opacity:0;
    transform:translateY(18px) scale(.94);
  }
  to{
    opacity:1;
    transform:translateY(0) scale(1);
  }
}

@media(max-width:370px){
  .vl-cs-inner{
    padding:18px;
  }

  .vl-cs-title{
    font-size:23px;
  }

  .vl-cs-actions{
    grid-template-columns:1fr;
  }
}

    /* MODAL */
    .vl-modal-overlay{ position:fixed; inset:0; z-index:9999; display:none; align-items:center; justify-content:center; padding:18px 14px; background:rgba(75,16,21,.35); backdrop-filter:blur(14px); -webkit-backdrop-filter:blur(14px); }
    .vl-modal-overlay.show{ display:flex; }
    .vl-modal{ width:100%; max-width:420px; border-radius:28px; background:#fff; border:1px solid rgba(255,255,255,.72); box-shadow:0 30px 80px rgba(75,16,21,.22); overflow:hidden; animation:vlModalIn .24s ease both; }
    .vl-modal-head{ padding:15px 16px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid var(--border); }
    .vl-modal-title{ display:flex; align-items:center; gap:10px; color:#3b1116; font-size:15px; font-weight:950; letter-spacing:-.02em; }
    .vl-modal-icon{ width:36px; height:36px; border-radius:15px; display:grid; place-items:center; color:#fff; background:linear-gradient(135deg,var(--gold),var(--violet),var(--brand)); }
    .vl-modal-close{ width:38px; height:38px; border-radius:14px; border:1px solid var(--border); background:#fff8ef; color:#5b2a30; display:grid; place-items:center; cursor:pointer; }
    .vl-modal-body{ padding:17px; color:#9a6e72; text-align:center; font-size:13px; line-height:1.55; font-weight:700; }
    .vl-modal-body b{ color:#3b1116; }
    .vl-modal-actions{ padding:0 17px 17px; display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    .vl-modal-btn{ min-height:44px; border-radius:16px; border:1px solid var(--border); background:#fff8ef; color:#9a6e72; font-size:12.5px; font-weight:950; cursor:pointer; display:flex; align-items:center; justify-content:center; }
    .vl-modal-btn.primary{ border:0; color:#fff; background:linear-gradient(135deg,var(--gold),var(--violet),var(--brand)); box-shadow:0 14px 26px rgba(201,87,255,.20); }

    /* PROMO POPUP */
    .vl-promo-popup{ position:fixed; inset:0; z-index:99999; display:none; align-items:center; justify-content:center; padding:24px 10px 96px; background:rgba(75,16,21,.38); backdrop-filter:blur(10px); -webkit-backdrop-filter:blur(10px); }
    .vl-promo-popup.show{ display:flex; }
    .vl-promo-popup-modal{ width:min(calc(100vw - 18px),430px); position:relative; display:flex; align-items:center; justify-content:center; animation:vlPromoIn .26s ease forwards; }
    .vl-promo-popup-img{ width:100%; height:auto; display:block; object-fit:contain; transform:scale(1.36); filter:drop-shadow(0 26px 50px rgba(75,16,21,.24)); }
    .vl-promo-popup-close{ position:absolute; left:50%; bottom:-92px; transform:translateX(-50%); width:48px; height:48px; border-radius:999px; border:1px solid rgba(75,16,21,.09); display:grid; place-items:center; background:rgba(255,255,255,.96); color:#5b2a30; box-shadow:0 14px 32px rgba(75,16,21,.18); cursor:pointer; }

    /* BOTTOM NAV COMPAT */
    .rb-bottom-spacer{ height:94px !important; }
    .rb-bottom-nav{ background:rgba(255,255,255,.94) !important; border:1px solid rgba(75,16,21,.09) !important; box-shadow:0 -18px 42px rgba(75,16,21,.10), inset 0 1px 0 rgba(255,255,255,.75) !important; backdrop-filter:blur(22px) !important; -webkit-backdrop-filter:blur(22px) !important; }
    .rb-bottom-nav__item{ color:#b78d92 !important; }
    .rb-bottom-nav__item:hover{ color:#5b2a30 !important; }
    .rb-bottom-nav__item.is-active{ color:#3b1116 !important; text-shadow:none; }
    .rb-bottom-nav__item.is-active .rb-bottom-nav__icon{ filter:drop-shadow(0 8px 12px rgba(201,87,255,.20)); }

    /* ANIMATIONS */
    @keyframes vlLineDraw{ to{ stroke-dashoffset:0; } }
    @keyframes vlDotPulse{ 0%,100%{ transform:scale(.86); opacity:.72; } 50%{ transform:scale(1.2); opacity:1; } }
    @keyframes vlFloat{ 0%,100%{ transform:translate3d(0,0,0) rotate(0deg); } 50%{ transform:translate3d(0,-8px,0) rotate(4deg); } }
    @keyframes vlModalIn{ from{ opacity:0; transform:translateY(16px) scale(.96); } to{ opacity:1; transform:translateY(0) scale(1); } }
    @keyframes vlPromoIn{ from{ opacity:0; transform:translateY(16px) scale(.96); } to{ opacity:1; transform:translateY(0) scale(1); } }

    @media (max-width:370px){
      .vl-page{ padding-left:8px; padding-right:8px; }
      .vl-logo{ width:44px; height:44px; border-radius:15px; }
      .vl-logo img{ width:38px; height:38px; }
      .vl-brand-copy h1{ font-size:21px; }
      .vl-icon-btn{ width:39px; height:39px; }
      .vl-hero{ border-radius:28px; padding:16px; }
      .vl-balance{ font-size:28px; }
      .vl-chart-panel{ min-height:116px; padding:12px; }
      .vl-main-chart{ height:74px; }
      .vl-metrics{ grid-template-columns:1fr; }
      .vl-asset-row{ grid-template-columns:46px minmax(0,1fr) 106px; min-height:98px; gap:6px 9px; padding:12px 10px; }
      .vl-token{ width:44px; height:44px; border-radius:17px; }
      .vl-spark{ height:52px; }
      .vl-asset-info h3{ font-size:12.7px; }
      .vl-price{ font-size:11px; }
      .vl-asset-detail{ grid-template-columns:1fr; }
      .vl-detail + .vl-detail{ border-left:0; border-top:1px solid var(--border); }
      .vl-trust{ grid-template-columns:1fr; }
      .vl-trust-logos{ justify-content:flex-start; }
      .vl-invite-coin{ width:60px; height:60px; border-radius:22px; right:16px; top:24px; opacity:.95; }
      .vl-invite-content{ max-width:205px; }
    }

    @media (prefers-reduced-motion:reduce){ *,*::before,*::after{ animation:none !important; transition:none !important; } }

  </style>
</head>

<body>
  <main class="vl-page">
    <div class="vl-phone">

      {{-- HEADER --}}
      <header class="vl-topbar">
        <div class="vl-brand">
          <div class="vl-logo">
            <img src="{{ asset('logo.png') }}" alt="Velora Finance">
          </div>

          <div class="vl-brand-copy">
            <span>Velora Finance</span>
            <h1>Dashboard</h1>
          </div>
        </div>

        <div class="vl-actions">
          <a href="{{ url('/saldo/rincian') }}" class="vl-icon-btn" aria-label="Notifikasi">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9Z" fill="currentColor"/>
              <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span class="vl-dot"></span>
          </a>

          <a href="{{ url('/akun') }}" class="vl-icon-btn" aria-label="Akun">
            <svg viewBox="0 0 24 24" fill="none">
              <circle cx="12" cy="8" r="4" fill="currentColor"/>
              <path d="M4 21a8 8 0 0 1 16 0" fill="currentColor"/>
            </svg>
          </a>
        </div>
      </header>

      @if ($errors->any())
        <div style="margin:0 0 14px; padding:13px; border-radius:20px; background:rgba(255,79,109,.12); border:1px solid rgba(255,79,109,.26); color:#7f1d1d; font-size:12.5px; font-weight:750; line-height:1.45;">
          <strong style="display:block; margin-bottom:6px; color:#3b1116;">Terjadi kesalahan</strong>
          <ul style="margin:0; padding-left:18px;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- CRYPTO HERO --}}
      <section class="vl-hero">
        <div class="vl-hero-head">
          <div>
            <span class="vl-kicker">Live Portfolio</span>
            <p class="vl-portfolio-label">Total Aset</p>
            <h2 class="vl-balance">Rp {{ number_format((int)($user->saldo ?? 0), 0, ',', '.') }}</h2>
          </div>

          <div class="vl-market-pill">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M4 15l5-5 4 4 7-8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Live
          </div>
        </div>


        <div class="vl-metrics">
          <div class="vl-metric">
            <span>Saldo Penarikan</span>
            <strong>Rp {{ number_format((int)($user->saldo_penarikan ?? 0), 0, ',', '.') }}</strong>
          </div>
        </div>

        <div class="vl-hero-actions">
          <a href="{{ url('/deposit') }}" class="vl-main-btn deposit">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 5v14" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"/>
              <path d="M5 12h14" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"/>
            </svg>
            Deposit
          </a>

          <a href="{{ url('/ui/withdrawals') }}" class="vl-main-btn withdraw">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 4v13" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"/>
              <path d="M7 12l5 5 5-5" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Withdraw
          </a>
        </div>
      </section>

   

      {{-- TRUST --}}
      <section class="vl-trust">
        <div>
          <div class="vl-trust-title">Secure Digital Finance</div>
          <div class="vl-trust-sub">Sistem terenkripsi dan transaksi terpantau</div>
        </div>

        <div class="vl-trust-logos">
          <img src="{{ asset('ojk.png') }}" class="ojk" alt="OJK" onerror="this.style.display='none'">
          <img src="{{ asset('bappebti.png') }}" class="bappebti" alt="Bappebti" onerror="this.style.display='none'">
        </div>
      </section>

      {{-- INVITE --}}
      <section class="vl-invite">
        <div class="vl-invite-content">
          <h2>Earn more with <span>Velora Reward</span></h2>
          <p>Bagikan referral dan pantau komisi dari jaringan kamu secara langsung.</p>
          <a href="{{ route('referral.index') }}" class="vl-invite-btn">
            Buka Referral
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
              <path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </a>
        </div>

   <div class="vl-invite-coin" aria-hidden="true">
          <img src="{{ asset('logo.png') }}" alt="Velora" style="width:52px;height:52px;object-fit:contain;display:block;">
        </div>
      </section>

      {{-- CATEGORY --}}
      <section class="vl-section">
        <div class="vl-section-head">
          <div class="vl-section-title">
            <h2>Market Categories</h2>
            <p>Pilih kategori aset Velora</p>
          </div>

          <a href="{{ route('market.index') }}" class="vl-see-all">
            Lihat Semua
            <svg viewBox="0 0 24 24" fill="none">
              <path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </a>
        </div>

        <div class="vl-categories">
          <div class="vl-category-track" id="vlCategoryTrack">
            @foreach(($categories ?? []) as $i => $cat)
              @php
                $catName = strtolower($cat->name ?? '');

                if(str_contains($catName, 'saham')) {
                  $catClass = 'daily';
                  $catIcon = 'chart';
                } elseif(str_contains($catName, 'pro')) {
                  $catClass = 'premium';
                  $catIcon = 'diamond';
                } else {
                  $catClass = 'regular';
                  $catIcon = 'cube';
                }

                $catProducts = $cat->products ?? collect();
              @endphp

              <button
                type="button"
                class="vl-cat {{ $catClass }} {{ $i === 0 ? 'active' : '' }}"
                data-category-target="vl-cat-{{ $cat->id }}"
              >
                <span class="vl-cat-icon">
                  @if($catIcon === 'chart')
                    <svg viewBox="0 0 24 24" fill="none">
                      <path d="M4 19V5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                      <path d="M8 17v-7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                      <path d="M12 17V7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                      <path d="M16 17v-4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                      <path d="M20 17V4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                    </svg>
                  @elseif($catIcon === 'diamond')
                    <svg viewBox="0 0 24 24" fill="none">
                      <path d="m12 21 8-10-4-7H8l-4 7 8 10Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                      <path d="M4 11h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                      <path d="m9 4 3 17 3-17" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    </svg>
                  @else
                    <svg viewBox="0 0 24 24" fill="none">
                      <path d="M12 2.7 20 7.1v9.8l-8 4.4-8-4.4V7.1l8-4.4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                      <path d="M4.5 7.4 12 11.7l7.5-4.3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M12 11.7v8.4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                  @endif
                </span>

                <strong>{{ str_ireplace('Rubik', 'Velora', $cat->name) }}</strong>
                <span>{{ count($catProducts) }} aset tersedia</span>
              </button>
            @endforeach
          </div>
        </div>
      </section>

      {{-- ASSETS --}}
      <section class="vl-section">
        <div class="vl-section-head">
          <div class="vl-section-title">
            <h2>Trending Assets</h2>
            <p>Grafik line chart real visual seperti aplikasi crypto</p>
          </div>

          <a href="{{ route('market.index') }}" class="vl-see-all">Market</a>
        </div>

        @forelse(($categories ?? []) as $i => $cat)
          <div class="vl-product-pane {{ $i === 0 ? 'active' : '' }}" id="vl-cat-{{ $cat->id }}">
            <div class="vl-products">
              @forelse(($cat->products ?? []) as $product)
                @php
                  $catName = strtolower($cat->name ?? '');
                  $productName = str_ireplace('Rubik', 'Velora', $product->name ?? 'Velora Asset');

                  if(str_contains($catName, 'saham')) {
                    $assetClass = 'daily';
                    $assetType = 'Saham Velora';
                    $tokenSymbol = 'SV';
                  } elseif(str_contains($catName, 'pro')) {
                    $assetClass = 'premium';
                    $assetType = 'Velora Pro';
                    $tokenSymbol = 'VP';
                  } else {
                    $assetClass = 'regular';
                    $assetType = 'Velora Asset';
                    $tokenSymbol = 'VA';
                  }

                  $activeInvestments = $activeInvestments ?? [];
                  $invActive = $activeInvestments[$product->id] ?? null;

                  $isBasicProduct = (int) $cat->id === 1;
                  $isOneTimeProduct = in_array((int) $cat->id, [2, 3], true);
                  $shouldLockBuyButton = $isOneTimeProduct && $invActive;

                  $vipKurang = (int) ($user->vip_level ?? 0) < (int) ($product->min_vip_level ?? 0);
                  $saldoKurang = (int) ($user->saldo ?? 0) < (int) ($product->price ?? 0);

                  $profitPercent = (int) ($product->price ?? 0) > 0
                    ? round(((int) ($product->daily_profit ?? 0) / (int) ($product->price ?? 1)) * 100, 1)
                    : 0;

                  $seed = (int) (
                    (int) ($product->id ?? 0)
                    + (int) ($product->price ?? 0)
                    + (int) ($product->daily_profit ?? 0)
                    + (int) ($product->total_profit ?? 0)
                    + (int) ($product->duration_days ?? 0)
                  );
                @endphp

                <article class="vl-asset-card {{ $assetClass }}">
                  <div class="vl-asset-row">
                    <div class="vl-token">{{ $tokenSymbol }}</div>

                    <div class="vl-asset-info">
                      <h3>{{ $productName }}</h3>
                    </div>

                    <div class="vl-asset-meta">
                      <span class="vl-price">Rp {{ number_format((int)($product->price ?? 0), 0, ',', '.') }}</span>
                      <span class="vl-profit-badge">+{{ $profitPercent }}%</span>
                    </div>

                    <svg
                      class="vl-spark js-spark-chart"
                      viewBox="0 0 128 62"
                      preserveAspectRatio="none"
                      aria-hidden="true"
                      data-seed="{{ max($seed, 1) }}"
                      data-price="{{ (int)($product->price ?? 0) }}"
                      data-profit="{{ (int)($product->daily_profit ?? 0) }}"
                      data-total="{{ (int)($product->total_profit ?? 0) }}"
                    >
                      <path class="spark-grid" d="M0 16 H128 M0 31 H128 M0 46 H128"></path>
                      <path class="spark-area" d=""></path>
                      <path class="spark-glow" d=""></path>
                      <path class="spark-line" d=""></path>
                      <circle class="spark-dot" cx="0" cy="0" r="3.2"></circle>
                    </svg>
                  </div>

                  <div class="vl-asset-detail">
                    <div class="vl-detail">
                      <span>Profit Harian</span>
                      <strong class="accent">Rp {{ number_format((int)($product->daily_profit ?? 0), 0, ',', '.') }}</strong>
                    </div>

                    <div class="vl-detail">
                      <span>Total Profit</span>
                      <strong>Rp {{ number_format((int)($product->total_profit ?? 0), 0, ',', '.') }}</strong>
                    </div>
                  </div>

                  <div class="vl-asset-action">
                    @if($shouldLockBuyButton)
                      <a href="{{ route('investasi.index') }}" class="vl-buy muted">Sedang Aktif</a>
                    @else
                      <form
                        method="POST"
                        action="{{ url('/product/buy/'.$product->id) }}"
                        class="js-invest-form"
                        style="margin:0;"
                        data-product-name="{{ $productName }}"
                        data-product-price="Rp {{ number_format((int)($product->price ?? 0), 0, ',', '.') }}"
                        data-product-profit="Rp {{ number_format((int)($product->daily_profit ?? 0), 0, ',', '.') }}"
                        data-product-duration="{{ $product->duration_days }} Hari"
                        data-product-vip="{{ (int)($product->min_vip_level ?? 0) }}"
                        data-user-vip="{{ (int)($user->vip_level ?? 0) }}"
                        data-product-raw-price="{{ (int)($product->price ?? 0) }}"
                        data-user-saldo="{{ (int)($user->saldo ?? 0) }}"
                        data-vip-kurang="{{ $vipKurang ? '1' : '0' }}"
                        data-saldo-kurang="{{ $saldoKurang ? '1' : '0' }}"
                      >
                        @csrf
                        <button type="submit" class="vl-buy">
                          {{ ($vipKurang || $saldoKurang) ? 'Deposit Sekarang' : 'Investasikan Sekarang' }}
                        </button>
                      </form>
                    @endif
                  </div>
                </article>
              @empty
                <div class="vl-empty">Belum ada produk tersedia di kategori ini.</div>
              @endforelse
            </div>
          </div>
        @empty
          <div class="vl-empty">Belum ada kategori produk tersedia.</div>
        @endforelse
      </section>

      <div class="vl-bottom-spacer"></div>
      @include('partials.bottom-nav')
    </div>
  </main>
    {{-- CS & CHANNEL POPUP --}}
  <div class="vl-cs-popup" id="vlCsPopup" aria-hidden="true">
    <div class="vl-cs-card">
      <div class="vl-cs-inner">
        <div class="vl-cs-top">
          <div class="vl-cs-brand">
            <div class="vl-cs-logo">
              <img src="{{ asset('logo.png') }}" alt="Velora Finance">
            </div>

            <div>
              <span>Official Support</span>
              <strong>Velora Finance</strong>
            </div>
          </div>

          <button type="button" class="vl-cs-close" id="vlCsPopupClose" aria-label="Tutup">
            <svg width="19" height="19" viewBox="0 0 24 24" fill="none">
              <path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
            </svg>
          </button>
        </div>

        <div class="vl-cs-badge">Resmi Hadir 2026</div>

        <h2 class="vl-cs-title">
          Akses CS & Channel <span>Resmi Velora</span>
        </h2>

        <p class="vl-cs-desc">
          Gunakan kontak resmi Velora untuk bantuan akun, informasi terbaru, dan update layanan agar transaksi tetap aman dan terpantau.
        </p>

        <div class="vl-cs-info">
          <div class="vl-cs-info-item">
            <div class="vl-cs-info-icon">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              </svg>
            </div>

            <div class="vl-cs-info-text">
              <span>Customer Service</span>
              <strong>@goveloracs</strong>
            </div>
          </div>

          <div class="vl-cs-info-item">
            <div class="vl-cs-info-icon">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M21.5 4.5 2.8 11.7c-1.1.4-1.1 1.9.1 2.2l4.7 1.4 1.8 5.1c.4 1.1 1.8 1.3 2.4.3l2.5-3.8 4.8 3.5c.9.7 2.2.2 2.4-.9l2.5-13.4c.2-1.1-.9-2-2-1.6Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              </svg>
            </div>

            <div class="vl-cs-info-text">
              <span>Official Channel</span>
              <strong>t.me/velorafinance</strong>
            </div>
          </div>
        </div>

        <div class="vl-cs-actions">
          <a href="https://t.me/goveloracs" target="_blank" rel="noopener noreferrer" class="vl-cs-btn cs">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
            Hubungi CS
          </a>

          <a href="https://t.me/velorafinance" target="_blank" rel="noopener noreferrer" class="vl-cs-btn channel">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M21.5 4.5 2.8 11.7c-1.1.4-1.1 1.9.1 2.2l4.7 1.4 1.8 5.1c.4 1.1 1.8 1.3 2.4.3l2.5-3.8 4.8 3.5c.9.7 2.2.2 2.4-.9l2.5-13.4c.2-1.1-.9-2-2-1.6Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
            Join Channel
          </a>
        </div>

        <div class="vl-cs-foot">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M12 2.5 20 7v5.5c0 4.8-3.2 7.7-8 9-4.8-1.3-8-4.2-8-9V7l8-4.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            <path d="m8.5 12 2.2 2.2 4.8-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Pastikan hanya menggunakan kontak resmi Velora
        </div>
      </div>
    </div>
  </div>
  {{-- MODAL ALERT --}}
  <div class="vl-modal-overlay" id="vlModal" aria-hidden="true">
    <div class="vl-modal">
      <div class="vl-modal-head">
        <div class="vl-modal-title">
          <span class="vl-modal-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
              <path d="M12 2.5 20 7v10l-8 4.5L4 17V7l8-4.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
          </span>
          <span id="vlModalTitle">Informasi</span>
        </div>

        <button type="button" class="vl-modal-close" id="vlModalClose" aria-label="Tutup">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
          </svg>
        </button>
      </div>

      <div class="vl-modal-body" id="vlModalBody"></div>

      <div class="vl-modal-actions">
        <button type="button" class="vl-modal-btn" id="vlModalCancel">Nanti</button>
        <a href="{{ url('/deposit') }}" class="vl-modal-btn primary" id="vlModalAction">Deposit</a>
      </div>
    </div>
  </div>

  {{-- PROMO POPUP --}}
  <div class="vl-promo-popup" id="vlPromoPopup" aria-hidden="true">
    <div class="vl-promo-popup-modal">
      <img src="" alt="Promo Velora" class="vl-promo-popup-img" id="vlPromoPopupImg">
      <button type="button" class="vl-promo-popup-close" id="vlPromoPopupClose" aria-label="Tutup">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
          <path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
        </svg>
      </button>
    </div>
  </div>

  <script>
    // Shared utilities
    function seededRandom(seed){
      let value = seed % 2147483647;
      if(value <= 0) value += 2147483646;

      return function(){
        value = value * 16807 % 2147483647;
        return (value - 1) / 2147483646;
      };
    }

    function buildSmoothPath(points){
      if(!points.length) return '';

      let d = `M ${points[0].x} ${points[0].y}`;

      for(let i = 1; i < points.length; i++){
        const prev = points[i - 1];
        const curr = points[i];
        const midX = (prev.x + curr.x) / 2;
        d += ` C ${midX} ${prev.y}, ${midX} ${curr.y}, ${curr.x} ${curr.y}`;
      }

      return d;
    }

    function buildLineChart(svg, options){
      const width = options.width;
      const height = options.height;
      const count = options.count || 9;
      const seed = options.seed || 1;
      const minY = options.minY || 8;
      const maxY = options.maxY || (height - 8);
      const bottomY = height;
      const rand = seededRandom(seed);

      const points = [];

      for(let i = 0; i < count; i++){
        const x = Math.round((width / (count - 1)) * i);
        const upwardTrend = (maxY - 6) - (i * ((maxY - minY) / (count + 1)));
        const wave = Math.sin((i + seed) * .9) * (options.wave || 8);
        const noise = (rand() * (options.noise || 14)) - ((options.noise || 14) / 2);
        const y = Math.max(minY, Math.min(maxY, upwardTrend + wave + noise));

        points.push({ x, y: Math.round(y) });
      }

      const path = buildSmoothPath(points);
      const first = points[0];
      const last = points[points.length - 1];

      const line = svg.querySelector('.chart-line, .spark-line');
      const glow = svg.querySelector('.chart-glow, .spark-glow');
      const area = svg.querySelector('.chart-area, .spark-area');
      const dot = svg.querySelector('.vl-live-dot, .spark-dot');

      if(line) line.setAttribute('d', path);
      if(glow) glow.setAttribute('d', path);
      if(area) area.setAttribute('d', `${path} L ${last.x} ${bottomY} L ${first.x} ${bottomY} Z`);
      if(dot){
        dot.setAttribute('cx', last.x);
        dot.setAttribute('cy', last.y);
      }
    }

    // Main hero line chart
    (function(){
      const chart = document.querySelector('.js-main-line-chart');
      if(!chart) return;

      buildLineChart(chart, {
        width:330,
        height:92,
        count:10,
        seed:Number(chart.dataset.seed || 10),
        minY:12,
        maxY:78,
        wave:9,
        noise:15
      });
    })();

    // Asset line charts
    (function(){
      const charts = Array.from(document.querySelectorAll('.js-spark-chart'));

      charts.forEach((chart, index) => {
        const seed = Number(chart.dataset.seed || index + 1)
          + Number(chart.dataset.price || 0)
          + Number(chart.dataset.profit || 0)
          + Number(chart.dataset.total || 0);

        buildLineChart(chart, {
          width:128,
          height:62,
          count:8,
          seed,
          minY:9,
          maxY:52,
          wave:6.5,
          noise:11
        });
      });
    })();

    // Category tabs
    (function(){
      const tabs = Array.from(document.querySelectorAll('.vl-cat'));
      const panes = Array.from(document.querySelectorAll('.vl-product-pane'));

      tabs.forEach(tab => {
        tab.addEventListener('click', function(){
          const target = this.dataset.categoryTarget;

          tabs.forEach(t => t.classList.remove('active'));
          panes.forEach(p => p.classList.remove('active'));

          this.classList.add('active');

          const pane = document.getElementById(target);
          if(pane) pane.classList.add('active');
        });
      });
    })();

    // Promo slider
    (function(){
      const slider = document.querySelector('.js-promo-slider');
      if(!slider) return;

      const track = slider.querySelector('.vl-promo-track');
      const slides = Array.from(slider.querySelectorAll('.vl-promo-slide'));
      const dots = Array.from(slider.querySelectorAll('.vl-promo-dot'));

      if(!track || !slides.length) return;

      let index = 0;
      let timer = null;
      let startX = 0;
      let currentX = 0;
      let dragging = false;

      function goTo(next){
        index = (next + slides.length) % slides.length;
        track.style.transform = `translateX(-${index * 100}%)`;

        dots.forEach((dot, i) => {
          dot.classList.toggle('active', i === index);
        });
      }

      function startAuto(){
        stopAuto();
        timer = setInterval(() => goTo(index + 1), 4200);
      }

      function stopAuto(){
        if(timer) clearInterval(timer);
      }

      dots.forEach((dot, i) => {
        dot.addEventListener('click', function(){
          goTo(i);
          startAuto();
        });
      });

      track.addEventListener('pointerdown', function(e){
        dragging = true;
        startX = e.clientX;
        currentX = startX;
        track.classList.add('is-dragging');
        stopAuto();
      });

      window.addEventListener('pointermove', function(e){
        if(!dragging) return;
        currentX = e.clientX;
      });

      window.addEventListener('pointerup', function(){
        if(!dragging) return;

        const diff = currentX - startX;
        dragging = false;
        track.classList.remove('is-dragging');

        if(Math.abs(diff) > 42){
          goTo(diff < 0 ? index + 1 : index - 1);
        }

        startAuto();
      });

      startAuto();
    })();

    // Promo popup
    (function(){
      const popup = document.getElementById('vlPromoPopup');
      const image = document.getElementById('vlPromoPopupImg');
      const close = document.getElementById('vlPromoPopupClose');

      if(!popup || !image || !close) return;

      document.querySelectorAll('.vl-promo-img').forEach(img => {
        img.addEventListener('click', function(e){
          const src = this.dataset.popupSrc || this.src;
          if(!src) return;

          e.preventDefault();
          image.src = src;
          popup.classList.add('show');
          popup.setAttribute('aria-hidden', 'false');
        });
      });

      function hide(){
        popup.classList.remove('show');
        popup.setAttribute('aria-hidden', 'true');
        image.src = '';
      }

      close.addEventListener('click', hide);

      popup.addEventListener('click', function(e){
        if(e.target === popup) hide();
      });
    })();

    // Buy modal / validation
    (function(){
      const modal = document.getElementById('vlModal');
      const modalTitle = document.getElementById('vlModalTitle');
      const modalBody = document.getElementById('vlModalBody');
      const modalClose = document.getElementById('vlModalClose');
      const modalCancel = document.getElementById('vlModalCancel');
      const modalAction = document.getElementById('vlModalAction');

      function openModal(title, body, actionUrl, actionText){
        if(!modal) return;

        modalTitle.textContent = title;
        modalBody.innerHTML = body;

        if(actionUrl){
          modalAction.href = actionUrl;
          modalAction.textContent = actionText || 'Lanjutkan';
          modalAction.style.display = 'flex';
        } else {
          modalAction.style.display = 'none';
        }

        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
      }

      function closeModal(){
        if(!modal) return;
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
      }

      modalClose?.addEventListener('click', closeModal);
      modalCancel?.addEventListener('click', closeModal);
      modal?.addEventListener('click', function(e){
        if(e.target === modal) closeModal();
      });

      document.querySelectorAll('.js-invest-form').forEach(form => {
        form.addEventListener('submit', function(e){
          const vipKurang = this.dataset.vipKurang === '1';
          const saldoKurang = this.dataset.saldoKurang === '1';

          if(vipKurang || saldoKurang){
            e.preventDefault();

            const name = this.dataset.productName || 'Produk Velora';
            const price = this.dataset.productPrice || '-';
            const productVip = this.dataset.productVip || '0';
            const userVip = this.dataset.userVip || '0';

            if(vipKurang){
              openModal(
                'VIP Belum Cukup',
                `Produk <b>${name}</b> membutuhkan minimal <b>VIP ${productVip}</b>.<br>Status kamu saat ini <b>VIP ${userVip}</b>.`,
                '{{ url('/akun') }}',
                'Lihat Akun'
              );
              return;
            }

            if(saldoKurang){
              openModal(
                'Saldo Belum Cukup',
                `Saldo kamu belum cukup untuk membeli <b>${name}</b>.<br>Harga produk: <b>${price}</b>.`,
                '{{ url('/deposit') }}',
                'Deposit'
              );
            }
          }
        });
      });
    })();

        // CS & Channel popup - selalu muncul saat masuk dashboard
    (function(){
      const popup = document.getElementById('vlCsPopup');
      const close = document.getElementById('vlCsPopupClose');

      if(!popup || !close) return;

      function showPopup(){
        popup.classList.add('show');
        popup.setAttribute('aria-hidden', 'false');
      }

      function hidePopup(){
        popup.classList.remove('show');
        popup.setAttribute('aria-hidden', 'true');
      }

      window.addEventListener('load', function(){
        setTimeout(showPopup, 450);
      });

      close.addEventListener('click', hidePopup);

      popup.addEventListener('click', function(e){
        if(e.target === popup) hidePopup();
      });

      document.addEventListener('keydown', function(e){
        if(e.key === 'Escape') hidePopup();
      });
    })();
  </script>
</body>
</html>
