{{-- Velora Premium — Portofolio Saya (NO @extends) --}}
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


  $chartKeys = [];
  $chartMonths = [];

  for ($i = 5; $i >= 0; $i--) {
    $month = now()->copy()->startOfMonth()->subMonths($i);
    $chartKeys[] = $month->format('Y-m');
    $chartMonths[] = $month->translatedFormat('M');
  }

  $monthlyModalMap = [];
  foreach ($chartKeys as $key) {
    $monthlyModalMap[$key] = 0;
  }

  foreach (($investments ?? []) as $inv) {
    if (!empty($inv->start_date)) {
      $monthKey = \Carbon\Carbon::parse($inv->start_date)->format('Y-m');
      if (array_key_exists($monthKey, $monthlyModalMap)) {
        $monthlyModalMap[$monthKey] += (int) ($inv->price ?? 0);
      }
    }
  }

  $monthlyModalData = array_values($monthlyModalMap);

  $monthlyModalMax = 1;
  foreach ($monthlyModalData as $value) {
    if ($value > $monthlyModalMax) {
      $monthlyModalMax = $value;
    }
  }

  $returnRate = $totalModal > 0 ? round(($totalProfit / $totalModal) * 100, 1) : 0;
  $ringPercent = $returnRate > 0
    ? max(12, min(100, (int) round($returnRate)))
    : ($totalActive > 0 ? 24 : 0);

  $latestMonthValue = count($monthlyModalData)
    ? $monthlyModalData[count($monthlyModalData) - 1]
    : 0;
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Portofolio Saya | Velora Finance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,600;1,700&display=swap" rel="stylesheet">

  <style>
    :root{
      --iv-bg:#f6f2f8;
      --iv-bg2:#efe8f7;
      --iv-paper:#ffffff;
      --iv-paper2:#fbf8ff;
      --iv-card:#ffffff;
      --iv-card2:#fbf8ff;
      --iv-text:#2b0b16;
      --iv-soft:#45162a;
      --iv-muted:#7b6370;
      --iv-muted2:#a894a0;
      --iv-border:rgba(43,11,22,.085);
      --iv-border2:rgba(43,11,22,.14);

      --iv-maroon:#3a0712;
      --iv-maroon2:#55112a;
      --iv-purple:#8f57ff;
      --iv-violet:#d96bff;
      --iv-lilac:#f1d6ff;
      --iv-gold:#f5af2a;
      --iv-gold2:#ffd46d;
      --iv-rose:#ff5c93;
      --iv-green:#20b873;
      --iv-red:#e24a64;

      --iv-gradient-main:linear-gradient(135deg,#f5af2a 0%,#ffd46d 26%,#d96bff 58%,#8f57ff 100%);
      --iv-gradient-gold:linear-gradient(135deg,#ffe08a 0%,#f5af2a 100%);
      --iv-gradient-purple:linear-gradient(135deg,#d96bff 0%,#8f57ff 100%);
      --iv-gradient-hero:linear-gradient(145deg,#8f57ff 0%,#9455ff 40%,#d96bff 72%,#f5af2a 100%);

      --iv-shadow:0 26px 68px rgba(88,43,145,.16);
      --iv-shadow-soft:0 14px 36px rgba(43,11,22,.075);
      --iv-radius:30px;
      --iv-radius-sm:21px;
    }

    *{ box-sizing:border-box; }
    html, body{ min-height:100%; }

    body{
      margin:0;
      font-family:"Plus Jakarta Sans", Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      color:var(--iv-text);
      background:
        radial-gradient(680px 360px at 50% -150px, rgba(245,175,42,.23), transparent 64%),
        radial-gradient(520px 340px at 100% 4%, rgba(217,107,255,.18), transparent 62%),
        radial-gradient(520px 330px at -12% 34%, rgba(143,87,255,.13), transparent 58%),
        linear-gradient(180deg,#ffffff 0%,#f7f2fa 44%,#eee8f6 100%);
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

    body::after{
      content:"";
      position:fixed;
      inset:0;
      pointer-events:none;
      background:
        radial-gradient(circle at 9% 18%, rgba(245,175,42,.10), transparent 28%),
        radial-gradient(circle at 92% 26%, rgba(217,107,255,.11), transparent 30%),
        radial-gradient(circle at 50% 100%, rgba(143,87,255,.075), transparent 34%);
      z-index:0;
    }

    a{ color:inherit; text-decoration:none; }
    button{ font-family:inherit; }

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
      padding:8px 4px 104px;
    }

    .iv-topbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:16px;
      padding:0 2px;
    }

    .iv-brand{
      display:flex;
      align-items:center;
      gap:12px;
      min-width:0;
    }

.iv-logo-card{
  width:52px;
  height:52px;
  border-radius:19px;
  background:#ffffff;
  border:1px solid rgba(43,11,22,.06);
  box-shadow:
    0 14px 30px rgba(43,11,22,.07),
    inset 0 1px 0 rgba(255,255,255,.95);
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

    .iv-title{ min-width:0; }

    .iv-title span{
      display:block;
      margin-bottom:6px;
      color:rgba(58,7,18,.58);
      font-size:10px;
      line-height:1;
      font-weight:800;
      letter-spacing:.18em;
      text-transform:uppercase;
      font-style:italic;
    }

    .iv-title h1{
      margin:0;
      font-size:23px;
      line-height:1;
      font-weight:800;
      letter-spacing:-.052em;
      color:var(--iv-maroon);
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
      border:1px solid rgba(43,11,22,.08);
      background:rgba(255,255,255,.84);
      color:#5b2841;
      display:grid;
      place-items:center;
      box-shadow:0 12px 26px rgba(43,11,22,.065), inset 0 1px 0 rgba(255,255,255,.92);
      position:relative;
      transition:.18s ease;
      backdrop-filter:blur(18px);
      -webkit-backdrop-filter:blur(18px);
    }

    .iv-header-btn:hover{
      transform:translateY(-1px);
      color:var(--iv-purple);
      box-shadow:0 16px 34px rgba(88,43,145,.13);
    }

    .iv-header-btn svg{ width:20px; height:20px; }

    .iv-hero{
      position:relative;
      overflow:hidden;
      border-radius:34px;
      background:
        radial-gradient(360px 220px at 92% -12%, rgba(255,212,109,.48), transparent 58%),
        radial-gradient(300px 200px at 2% 8%, rgba(217,107,255,.34), transparent 62%),
        var(--iv-gradient-hero);
      border:1px solid rgba(255,255,255,.44);
      color:#fff;
      box-shadow:
        0 28px 62px rgba(143,87,255,.22),
        0 18px 42px rgba(245,175,42,.10),
        inset 0 1px 0 rgba(255,255,255,.22);
    }

    .iv-hero::before{
      content:"";
      position:absolute;
      inset:0;
      background:
        linear-gradient(135deg, rgba(255,255,255,.22), transparent 34%),
        radial-gradient(circle at 82% 26%, rgba(255,255,255,.16), transparent 28%),
        linear-gradient(180deg, transparent 0%, rgba(43,11,22,.08) 100%);
      pointer-events:none;
    }

    .iv-hero::after{
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

    .iv-hero-inner{
      position:relative;
      z-index:1;
      padding:18px;
    }

    .iv-hero-head{
      display:grid;
      grid-template-columns:minmax(0,1fr) auto;
      gap:14px;
      align-items:start;
    }

    .iv-hero-label{
      margin:0 0 8px;
      color:rgba(255,255,255,.74);
      font-size:12px;
      line-height:1.1;
      font-weight:700;
    }

    .iv-hero-balance{
      margin:0;
      color:#ffffff;
      font-size:31px;
      line-height:1.02;
      letter-spacing:-.075em;
      font-weight:800;
      text-shadow:0 12px 24px rgba(43,11,22,.22);
    }

    .iv-hero-profit{
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

    .iv-hero-profit span{
      color:rgba(44,18,0,.68);
      font-weight:700;
    }

    .iv-status-pill{
      flex:0 0 auto;
      min-width:82px;
      height:42px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:6px;
      color:#2b0b16;
      background:
        radial-gradient(circle at 24% 0%, rgba(255,255,255,.72), transparent 40%),
        linear-gradient(135deg,#ffd46d 0%,#d96bff 56%,#8f57ff 100%);
      border:1px solid rgba(255,255,255,.40);
      box-shadow:0 12px 24px rgba(143,87,255,.18), inset 0 1px 0 rgba(255,255,255,.40);
      font-size:12px;
      font-weight:800;
    }

    .iv-status-pill svg{ width:15px; height:15px; color:#2b0b16; }

    .iv-portfolio-strip{
      margin-top:14px;
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:9px;
    }

    .iv-strip-card{
      min-height:64px;
      border-radius:20px;
      padding:11px 12px;
      background:rgba(255,255,255,.14);
      border:1px solid rgba(255,255,255,.17);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.10);
      backdrop-filter:blur(10px);
      -webkit-backdrop-filter:blur(10px);
    }

    .iv-strip-card span{
      display:block;
      margin-bottom:7px;
      color:rgba(255,255,255,.66);
      font-size:10px;
      font-weight:700;
    }

    .iv-strip-card strong{
      display:block;
      color:#fff;
      font-size:13px;
      line-height:1.15;
      letter-spacing:-.02em;
      font-weight:800;
    }

    .iv-quick-actions{
      margin-top:11px;
      display:grid;
      grid-template-columns:1fr 1fr 1fr;
      gap:8px;
    }

    .iv-quick-action{
      min-height:54px;
      border-radius:18px;
      border:1px solid rgba(255,255,255,.13);
      background:rgba(255,255,255,.10);
      color:rgba(255,255,255,.86);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.08);
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      gap:5px;
      font-size:10.5px;
      line-height:1;
      font-weight:800;
      transition:.18s ease;
      text-align:center;
    }

    .iv-quick-action:hover{
      transform:translateY(-1px);
      background:rgba(255,255,255,.14);
    }

    .iv-quick-action svg{ width:18px; height:18px; }
    .iv-quick-action.is-market svg{ color:#ffd46d; }
    .iv-quick-action.is-deposit svg{ color:#ffffff; }
    .iv-quick-action.is-withdraw svg{ color:#ffe08a; }

    .iv-summary{
      margin-top:14px;
      display:grid;
      grid-template-columns:repeat(3,1fr);
      gap:10px;
    }

    .iv-summary-card{
      min-height:82px;
      position:relative;
      overflow:hidden;
      border-radius:22px;
      padding:12px;
      background:linear-gradient(180deg,rgba(255,255,255,.96),rgba(255,255,255,.86));
      border:1px solid rgba(43,11,22,.075);
      box-shadow:0 14px 32px rgba(43,11,22,.07), inset 0 1px 0 rgba(255,255,255,.95);
    }

    .iv-summary-card::before{
      content:"";
      position:absolute;
      top:10px;
      right:10px;
      width:10px;
      height:10px;
      border-radius:999px;
      background:linear-gradient(135deg,var(--sum-accent),var(--sum-accent2));
      box-shadow:0 0 0 6px var(--sum-glow);
    }

    .iv-summary-card::after{
      content:"";
      position:absolute;
      left:12px;
      right:12px;
      bottom:0;
      height:3px;
      border-radius:999px 999px 0 0;
      background:linear-gradient(90deg,var(--sum-accent),transparent 80%);
      opacity:.75;
    }

    .iv-summary-card:nth-child(1){ --sum-accent:#f5af2a; --sum-accent2:#ffd46d; --sum-glow:rgba(245,175,42,.14); }
    .iv-summary-card:nth-child(2){ --sum-accent:#d96bff; --sum-accent2:#8f57ff; --sum-glow:rgba(143,87,255,.12); }
    .iv-summary-card:nth-child(3){ --sum-accent:#8f57ff; --sum-accent2:#f5af2a; --sum-glow:rgba(180,92,255,.10); }

    .iv-summary-label{
      margin:0;
      padding-right:18px;
      color:var(--iv-muted);
      font-size:9.6px;
      line-height:1.22;
      font-weight:700;
    }

    .iv-summary-value{
      margin:13px 0 0;
      color:var(--iv-maroon);
      font-size:12.4px;
      line-height:1.14;
      letter-spacing:-.032em;
      font-weight:800;
    }

    .iv-summary-value span{ color:var(--sum-accent); }

    .iv-section{ margin-top:22px; }

    .iv-section-head{
      display:flex;
      align-items:flex-end;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
      padding:0 2px;
    }

    .iv-section-title h2{
      margin:0;
      color:var(--iv-maroon);
      font-size:18px;
      line-height:1.12;
      letter-spacing:-.042em;
      font-weight:800;
    }

    .iv-section-title p{
      margin:6px 0 0;
      color:var(--iv-muted);
      font-size:11px;
      font-weight:600;
      line-height:1.35;
    }

    .iv-see-all{
      display:inline-flex;
      align-items:center;
      min-height:35px;
      padding:0 13px;
      border-radius:999px;
      color:#2c1200;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.56), transparent 34%),
        var(--iv-gradient-gold);
      font-size:11px;
      font-weight:800;
      white-space:nowrap;
      box-shadow:0 12px 24px rgba(245,175,42,.16);
    }

    .iv-list{
      display:flex;
      flex-direction:column;
      gap:12px;
    }

    .iv-card{
      position:relative;
      overflow:hidden;
      border-radius:30px;
      background:
        radial-gradient(250px 140px at 100% -10%, var(--card-glow), transparent 64%),
        linear-gradient(180deg,rgba(255,255,255,.98),rgba(255,255,255,.90));
      border:1px solid rgba(43,11,22,.075);
      box-shadow:0 14px 34px rgba(43,11,22,.07), inset 0 1px 0 rgba(255,255,255,.94);
      transition:.18s ease;
    }

    .iv-card::before{
      content:"";
      position:absolute;
      inset:0;
      pointer-events:none;
      background:
        linear-gradient(135deg, rgba(255,255,255,.82), transparent 30%),
        radial-gradient(circle at 12% 0%, var(--card-glow-soft), transparent 44%);
      opacity:.86;
    }

    .iv-card:hover{
      transform:translateY(-1px);
      border-color:rgba(143,87,255,.16);
      box-shadow:0 18px 38px rgba(43,11,22,.095), 0 0 0 4px rgba(143,87,255,.04);
    }

    .iv-card:nth-child(4n+1){ --card-accent:#d96bff; --card-accent2:#8f57ff; --card-glow:rgba(180,92,255,.13); --card-glow-soft:rgba(180,92,255,.08); }
    .iv-card:nth-child(4n+2){ --card-accent:#ffd46d; --card-accent2:#f5af2a; --card-glow:rgba(245,175,42,.16); --card-glow-soft:rgba(245,175,42,.10); }
    .iv-card:nth-child(4n+3){ --card-accent:#f5af2a; --card-accent2:#d96bff; --card-glow:rgba(245,175,42,.14); --card-glow-soft:rgba(217,107,255,.08); }
    .iv-card:nth-child(4n+4){ --card-accent:#d96bff; --card-accent2:#ff5c93; --card-glow:rgba(217,107,255,.13); --card-glow-soft:rgba(217,107,255,.08); }

    .iv-card-top{
      position:relative;
      z-index:1;
      display:grid;
      grid-template-columns:50px minmax(0,1fr) auto;
      gap:11px;
      align-items:center;
      padding:14px 13px 11px;
      border-bottom:1px solid rgba(43,11,22,.075);
    }

    .iv-product-icon{
      width:48px;
      height:48px;
      border-radius:19px;
      display:grid;
      place-items:center;
      color:#fff;
      background:
        radial-gradient(circle at 28% 18%, rgba(255,255,255,.40), transparent 34%),
        linear-gradient(135deg,var(--card-accent),var(--card-accent2));
      box-shadow:0 12px 24px var(--card-glow), inset 0 1px 0 rgba(255,255,255,.25);
      overflow:hidden;
      font-size:16px;
      font-weight:800;
    }

    .iv-product-info{ min-width:0; }

    .iv-product-name{
      margin:0;
      color:var(--iv-maroon);
      font-size:14px;
      line-height:1.18;
      letter-spacing:-.026em;
      font-weight:800;
      white-space:normal;
      overflow:hidden;
      display:-webkit-box;
      -webkit-line-clamp:2;
      -webkit-box-orient:vertical;
    }

    .iv-product-meta{
      margin:5px 0 0;
      color:var(--iv-muted);
      font-size:10.5px;
      font-weight:600;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
    }

    .iv-badge{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      min-height:30px;
      padding:0 10px;
      border-radius:999px;
      font-size:10px;
      font-weight:800;
      letter-spacing:.04em;
      text-transform:uppercase;
      white-space:nowrap;
      border:1px solid rgba(43,11,22,.075);
      background:#fbf8ff;
      color:var(--iv-muted);
    }

    .iv-badge.is-active{
      color:#2c1200;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.62), transparent 34%),
        linear-gradient(135deg,var(--iv-gold2),var(--iv-violet));
      border-color:rgba(255,255,255,.40);
      box-shadow:0 12px 24px rgba(143,87,255,.16);
    }

    .iv-card-body{
      position:relative;
      z-index:1;
      padding:12px 13px 13px;
    }

    .iv-stats{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:9px;
      margin-bottom:10px;
    }

    .iv-stat{
      min-height:66px;
      border-radius:18px;
      background:rgba(251,248,255,.84);
      border:1px solid rgba(43,11,22,.065);
      padding:10px;
      box-shadow:inset 0 1px 0 rgba(255,255,255,.88);
    }

    .iv-stat-label{
      margin:0 0 7px;
      color:var(--iv-muted);
      font-size:9.6px;
      line-height:1.1;
      font-weight:650;
    }

    .iv-stat-value{
      margin:0;
      color:var(--iv-maroon);
      font-size:12.5px;
      line-height:1.15;
      letter-spacing:-.015em;
      font-weight:800;
    }

    .iv-stat-value.is-accent{ color:var(--card-accent2); }

    .iv-progress-wrap{
      margin-bottom:10px;
      border-radius:18px;
      padding:10px;
      background:rgba(251,248,255,.84);
      border:1px solid rgba(43,11,22,.065);
    }

    .iv-progress-head{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:10px;
      margin-bottom:8px;
    }

    .iv-progress-head span{
      color:var(--iv-muted);
      font-size:10px;
      font-weight:700;
    }

    .iv-progress-head strong{
      color:var(--card-accent2);
      font-size:10.5px;
      font-weight:800;
      white-space:nowrap;
    }

    .iv-progress-bar{
      height:8px;
      border-radius:999px;
      background:rgba(43,11,22,.08);
      overflow:hidden;
    }

    .iv-progress-fill{
      height:100%;
      width:var(--progress,45%);
      border-radius:999px;
      background:linear-gradient(90deg,var(--card-accent),var(--card-accent2));
      box-shadow:0 0 16px var(--card-glow-soft);
    }

    .iv-daily-box{
      margin-bottom:10px;
      border-radius:18px;
      padding:11px 12px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      background:
        radial-gradient(circle at 88% 0%, var(--card-glow), transparent 54%),
        rgba(251,248,255,.84);
      border:1px solid rgba(43,11,22,.065);
    }

    .iv-daily-box span{
      color:var(--iv-muted);
      font-size:10.5px;
      font-weight:650;
    }

    .iv-daily-box strong{
      color:var(--card-accent2);
      font-size:15px;
      letter-spacing:-.02em;
      font-weight:800;
      white-space:nowrap;
    }

    .iv-date-row{
      border-radius:19px;
      border:1px solid rgba(43,11,22,.065);
      background:rgba(251,248,255,.84);
      display:grid;
      grid-template-columns:1fr 32px 1fr;
      align-items:center;
      gap:8px;
      padding:11px 12px;
    }

    .iv-date-group{ min-width:0; }
    .iv-date-group:last-child{ text-align:right; }

    .iv-date-label{
      display:block;
      margin-bottom:5px;
      color:var(--iv-muted2);
      font-size:9.5px;
      line-height:1;
      font-weight:700;
      text-transform:uppercase;
      letter-spacing:.08em;
    }

    .iv-date-value{
      display:block;
      color:var(--iv-maroon);
      font-size:11.4px;
      line-height:1.15;
      font-weight:750;
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
      background:#fff;
      color:var(--card-accent2);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.88), 0 8px 16px rgba(43,11,22,.06);
    }

    .iv-date-arrow svg{ width:15px; height:15px; }

    .iv-empty{
      min-height:360px;
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      gap:14px;
      text-align:center;
      padding:26px 16px;
      border-radius:30px;
      background:
        radial-gradient(260px 140px at 50% 0%, rgba(217,107,255,.16), transparent 62%),
        radial-gradient(260px 140px at 90% 100%, rgba(245,175,42,.12), transparent 64%),
        linear-gradient(180deg,rgba(255,255,255,.96),rgba(255,255,255,.88));
      border:1px dashed rgba(43,11,22,.16);
      box-shadow:var(--iv-shadow-soft);
    }

    .iv-empty-icon{
      width:88px;
      height:88px;
      border-radius:30px;
      display:grid;
      place-items:center;
      color:#2c1200;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.65), transparent 34%),
        var(--iv-gradient-main);
      box-shadow:0 18px 38px rgba(143,87,255,.20), inset 0 1px 0 rgba(255,255,255,.32);
    }

    .iv-empty-icon svg{ width:42px; height:42px; }

    .iv-empty-title{
      margin:0;
      color:var(--iv-maroon);
      font-size:18px;
      line-height:1.2;
      letter-spacing:-.035em;
      font-weight:800;
    }

    .iv-empty-desc{
      margin:0;
      max-width:330px;
      color:var(--iv-muted);
      font-size:12.5px;
      line-height:1.55;
      font-weight:600;
    }

    .iv-empty-btn{
      width:100%;
      min-height:46px;
      border-radius:999px;
      display:flex;
      align-items:center;
      justify-content:center;
      gap:8px;
      color:#2c1200;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.50), transparent 34%),
        var(--iv-gradient-gold);
      font-size:12.5px;
      font-weight:800;
      box-shadow:0 14px 30px rgba(245,175,42,.20), inset 0 1px 0 rgba(255,255,255,.32);
    }

    .iv-empty-btn svg{ width:17px; height:17px; }

    .rb-bottom-spacer{ height:94px; }

    .rb-bottom-nav{
      background:rgba(255,255,255,.92) !important;
      border:1px solid rgba(43,11,22,.08) !important;
      box-shadow:0 -18px 40px rgba(43,11,22,.10), inset 0 1px 0 rgba(255,255,255,.84) !important;
      backdrop-filter:blur(22px) !important;
      -webkit-backdrop-filter:blur(22px) !important;
    }

    .rb-bottom-nav__item{ color:#aa8f9f !important; }
    .rb-bottom-nav__item:hover{ color:#5b2841 !important; }
    .rb-bottom-nav__item.is-active{ color:#3a0712 !important; text-shadow:none !important; }
    .rb-bottom-nav__item.is-active .rb-bottom-nav__icon{ filter:drop-shadow(0 8px 12px rgba(143,87,255,.18)); }

    .fade-up{
      opacity:0;
      transform:translateY(10px);
      animation:fadeUp .55s cubic-bezier(0.2,0.8,0.2,1) forwards;
    }

    @keyframes fadeUp{
      to{ opacity:1; transform:translateY(0); }
    }



    /* =========================
       PORTFOLIO ANALYTICS — REF STYLE
    ========================== */
    .iv-analytics{
      margin-top:16px;
      display:grid;
      gap:14px;
    }

    .iv-analytics-card{
      position:relative;
      overflow:hidden;
      border-radius:34px;
      padding:18px 18px 20px;
      background:
        radial-gradient(260px 160px at 85% 0%, rgba(217,107,255,.12), transparent 64%),
        radial-gradient(220px 140px at 0% 100%, rgba(245,175,42,.10), transparent 62%),
        linear-gradient(180deg, rgba(255,255,255,.98), rgba(255,255,255,.93));
      border:1px solid rgba(43,11,22,.08);
      box-shadow:0 18px 44px rgba(43,11,22,.08), inset 0 1px 0 rgba(255,255,255,.86);
    }

    .iv-analytics-card::before{
      content:"";
      position:absolute;
      inset:0;
      pointer-events:none;
      background:linear-gradient(145deg,rgba(255,255,255,.62),transparent 42%);
    }

    .iv-analytics-card > *{ position:relative; z-index:1; }

    .iv-analytics-head{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
    }

    .iv-analytics-back,
    .iv-analytics-menu{
      width:34px;
      height:34px;
      border-radius:12px;
      display:grid;
      place-items:center;
      color:var(--iv-muted);
      background:rgba(143,87,255,.055);
      border:1px solid rgba(43,11,22,.055);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.78);
    }

    .iv-analytics-back svg,
    .iv-analytics-menu svg{ width:18px; height:18px; }

    .iv-analytics-head h2{
      margin:0;
      color:var(--iv-maroon);
      font-size:20px;
      line-height:1.1;
      letter-spacing:-.03em;
      font-weight:800;
      text-align:center;
    }

    .iv-ring-stage{
      --iv-ring-length: 389.56;
      --ring-pct: 0;
      position:relative;
      display:flex;
      align-items:center;
      justify-content:center;
      min-height:260px;
      padding:8px 0 0;
    }

    .iv-ring-svg{
      width:220px;
      height:220px;
      transform:rotate(-90deg);
      overflow:visible;
      filter:drop-shadow(0 12px 22px rgba(143,87,255,.14));
    }

    .iv-ring-track{
      fill:none;
      stroke:rgba(143,87,255,.12);
      stroke-width:14;
      stroke-linecap:round;
    }

    .iv-ring-progress{
      fill:none;
      stroke:url(#ivRingGradient);
      stroke-width:14;
      stroke-linecap:round;
      stroke-dasharray:var(--iv-ring-length);
      stroke-dashoffset:var(--iv-ring-length);
      animation:ivRingIn 1.55s cubic-bezier(.2,.8,.2,1) forwards;
    }

    .iv-ring-center{
  position:absolute;
  inset:0;
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  text-align:center;
  pointer-events:none;
  padding:12px 46px 0;
}

.iv-ring-center span{
  display:block;
  color:var(--iv-muted);
  font-size:12px;
  line-height:1.1;
  font-weight:700;
  margin-bottom:7px;
  white-space:nowrap;
}

.iv-ring-center strong{
  display:block;
  width:100%;
  max-width:124px;
  color:var(--iv-maroon);
  font-size:clamp(18px, 4.4vw, 24px);
  line-height:1.08;
  letter-spacing:-.04em;
  font-weight:900;
  text-align:center;
  white-space:nowrap;
  overflow:hidden;
  text-overflow:ellipsis;
}

.iv-ring-center small{
  display:block;
  width:100%;
  max-width:132px;
  margin-top:8px;
  color:var(--iv-purple);
  font-size:11px;
  line-height:1.1;
  font-weight:800;
  white-space:nowrap;
  overflow:hidden;
  text-overflow:ellipsis;
}

    .iv-ring-float{
      position:absolute;
      top:24px;
      left:50%;
      transform:translateX(-50%);
      min-height:34px;
      padding:0 12px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:7px;
      color:#fff;
      background:linear-gradient(135deg, #d96bff 0%, #8f57ff 100%);
      box-shadow:0 12px 26px rgba(143,87,255,.24);
      border:3px solid #fff;
      font-size:11px;
      font-weight:850;
      white-space:nowrap;
      animation:ivFloat 2.6s ease-in-out infinite;
    }

    .iv-ring-float::before{
      content:"";
      width:7px;
      height:7px;
      border-radius:999px;
      background:#fff;
      opacity:.95;
    }

    .iv-perf-card{
      position:relative;
      overflow:hidden;
      border-radius:30px;
      padding:18px 18px 16px;
      background:
        radial-gradient(300px 180px at 82% -10%, rgba(255,255,255,.16), transparent 55%),
        radial-gradient(220px 150px at -8% 100%, rgba(245,175,42,.20), transparent 54%),
        linear-gradient(180deg, #a56fff 0%, #8f57ff 45%, #b14bff 100%);
      color:#fff;
      box-shadow:0 22px 44px rgba(143,87,255,.23);
    }

    .iv-perf-card::before{
      content:"";
      position:absolute;
      inset:0;
      pointer-events:none;
      background:
        linear-gradient(rgba(255,255,255,.055) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.055) 1px, transparent 1px);
      background-size:35px 35px;
      opacity:.55;
    }

    .iv-perf-card > *{ position:relative; z-index:1; }

    .iv-perf-head{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
    }

    .iv-perf-head h3{
      margin:0;
      font-size:20px;
      line-height:1.1;
      letter-spacing:-.03em;
      font-weight:800;
      color:#fff;
    }

    .iv-perf-pill{
      min-height:32px;
      padding:0 12px;
      border-radius:12px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      color:#5c2aa6;
      background:rgba(255,255,255,.92);
      box-shadow:0 8px 16px rgba(65,20,120,.16);
      font-size:11px;
      font-weight:850;
      white-space:nowrap;
    }

    .iv-bars{
      height:185px;
      display:grid;
      grid-template-columns:repeat(6,1fr);
      gap:12px;
      align-items:end;
      padding:8px 4px 0;
    }

    .iv-bar-col{
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:flex-end;
      gap:8px;
      min-width:0;
    }

    .iv-bar-track{
      width:100%;
      max-width:26px;
      height:136px;
      border-radius:999px;
      display:flex;
      align-items:flex-end;
      justify-content:center;
      background:linear-gradient(180deg, rgba(255,255,255,.14), rgba(255,255,255,.04));
      position:relative;
      overflow:hidden;
    }

    .iv-bar-fill{
      width:100%;
      height:var(--bar-h, 35%);
      border-radius:999px;
      background:linear-gradient(180deg, rgba(255,255,255,.98) 0%, rgba(255,255,255,.26) 100%);
      box-shadow:
        0 10px 22px rgba(255,255,255,.18),
        inset 0 1px 0 rgba(255,255,255,.88);
      transform-origin:bottom;
      transform:scaleY(0);
      animation:ivBarGrow .85s cubic-bezier(.22,.84,.2,1) forwards, ivBarPulse 2.4s ease-in-out infinite;
      animation-delay:var(--delay, 0s), calc(var(--delay, 0s) + .95s);
    }

    .iv-bar-col span{
      color:rgba(255,255,255,.82);
      font-size:10.5px;
      font-weight:700;
    }

    .iv-insight-card{
      margin-top:16px;
      border-radius:20px;
      padding:12px;
      display:flex;
      align-items:center;
      gap:12px;
      background:rgba(255,255,255,.96);
      color:#352143;
      box-shadow:0 10px 18px rgba(65,20,120,.14);
    }

    .iv-insight-icon{
      width:44px;
      height:44px;
      border-radius:999px;
      display:grid;
      place-items:center;
      background:linear-gradient(135deg, #ffd46d 0%, #d96bff 100%);
      box-shadow:0 10px 22px rgba(143,87,255,.22);
      color:#fff;
      font-size:19px;
      flex:0 0 auto;
    }

    .iv-insight-text strong{
      display:block;
      color:var(--iv-maroon);
      font-size:17px;
      line-height:1.1;
      font-weight:800;
      margin-bottom:4px;
    }

    .iv-insight-text span{
      display:block;
      color:var(--iv-muted);
      font-size:12px;
      line-height:1.45;
      font-weight:650;
    }

    .iv-insight-text b{ color:var(--iv-purple); }

    .iv-portfolio-actions{
      margin-top:0;
      padding:4px 0 0;
    }

    .iv-portfolio-actions .iv-quick-action{
      color:#fff;
      border-color:rgba(255,255,255,.24);
      background:linear-gradient(135deg,#d96bff 0%, #8f57ff 52%, #6d35e8 100%);
      box-shadow:0 16px 30px rgba(143,87,255,.22), inset 0 1px 0 rgba(255,255,255,.22);
    }

    .iv-portfolio-actions .iv-quick-action svg{ color:#fff; }

    @keyframes ivRingIn{
      from{ stroke-dashoffset:var(--iv-ring-length); }
      to{ stroke-dashoffset:calc(var(--iv-ring-length) - (var(--iv-ring-length) * var(--ring-pct) / 100)); }
    }

    @keyframes ivBarGrow{
      from{ transform:scaleY(0); opacity:.35; }
      to{ transform:scaleY(1); opacity:1; }
    }

    @keyframes ivBarPulse{
      0%,100%{ filter:brightness(1); }
      50%{ filter:brightness(1.18); }
    }

    @keyframes ivFloat{
      0%,100%{ transform:translateX(-50%) translateY(0); }
      50%{ transform:translateX(-50%) translateY(-4px); }
    }
    @media (max-width:370px){
      .iv-page{ padding-left:8px; padding-right:8px; }
      .iv-logo-card{ width:45px; height:45px; border-radius:16px; }
      .iv-logo-card img{ width:39px; height:39px; }
      .iv-title h1{ font-size:21px; }
      .iv-header-btn{ width:39px; height:39px; }
      .iv-hero-inner{ padding:16px; }
      .iv-hero-balance{ font-size:25px; }
      .iv-status-pill{ min-width:74px; height:38px; font-size:11px; }
      .iv-portfolio-strip{ grid-template-columns:1fr; gap:8px; }
      .iv-quick-actions{ gap:7px; }
      .iv-quick-action{ min-height:50px; font-size:10px; }
      .iv-summary{ gap:8px; }
      .iv-summary-card{ min-height:76px; padding:10px; border-radius:18px; }
      .iv-summary-label{ font-size:9.2px; }
      .iv-summary-value{ margin-top:10px; font-size:11.4px; }
      .iv-card{ border-radius:24px; }
      .iv-card-top{ grid-template-columns:44px minmax(0,1fr) auto; gap:9px; padding:12px 10px 10px; }
      .iv-product-icon{ width:42px; height:42px; border-radius:16px; font-size:14px; }
      .iv-product-name{ font-size:12.6px; }
      .iv-product-meta{ font-size:9.8px; }
      .iv-badge{ min-height:27px; padding:0 8px; font-size:9px; }
      .iv-card-body{ padding:11px 10px 11px; }
      .iv-stats{ gap:7px; }
      .iv-stat{ min-height:62px; border-radius:16px; padding:9px; }
      .iv-stat-value{ font-size:11.2px; }
      .iv-date-row{ grid-template-columns:1fr 28px 1fr; padding:10px; }
      .iv-date-arrow{ width:28px; height:28px; }
      .iv-date-value{ font-size:10.4px; }
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
            <img src="{{ asset('logo.png') }}" alt="Velora Finance">
          </div>

          <div class="iv-title">
            <span>Velora Portfolio</span>
            <h1>Portofolio</h1>
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

      {{-- PORTFOLIO ANALYTICS --}}
      <section class="iv-analytics fade-up">
        <article class="iv-analytics-card">
          <div class="iv-analytics-head">
            <a href="javascript:history.length > 1 ? history.back() : location.href='/'" class="iv-analytics-back" aria-label="Kembali">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M15 18 9 12l6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </a>

            <h2>Statistic</h2>

            <div class="iv-analytics-menu" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M12 5h.01M12 12h.01M12 19h.01" stroke="currentColor" stroke-width="2.8" stroke-linecap="round"/>
              </svg>
            </div>
          </div>

          <div class="iv-ring-stage" style="--ring-pct: {{ $ringPercent }};">
            <svg class="iv-ring-svg" viewBox="0 0 180 180" aria-hidden="true">
              <defs>
                <linearGradient id="ivRingGradient" x1="0" y1="0" x2="1" y2="1">
                  <stop offset="0%" stop-color="#e8dcff"/>
                  <stop offset="45%" stop-color="#d96bff"/>
                  <stop offset="100%" stop-color="#8f57ff"/>
                </linearGradient>
              </defs>

              <circle class="iv-ring-track" cx="90" cy="90" r="62"></circle>
              <circle class="iv-ring-progress" cx="90" cy="90" r="62"></circle>
            </svg>

            <div class="iv-ring-float">{{ $totalActive }} Plan Aktif</div>

<div class="iv-ring-center">
  <span>Total modal</span>
  <strong title="Rp {{ number_format($totalModal, 0, ',', '.') }}">
    Rp {{ number_format($totalModal, 0, ',', '.') }}
  </strong>
  <small>Return {{ number_format($returnRate, 1, ',', '.') }}%</small>
</div>


          </div>
        </article>

        <article class="iv-perf-card">
          <div class="iv-perf-head">
            <h3>Performa</h3>
            <div class="iv-perf-pill">
              Rp {{ number_format($latestMonthValue, 0, ',', '.') }}
            </div>
          </div>

          <div class="iv-bars">
            @foreach($chartMonths as $index => $monthLabel)
              @php
                $barPercent = $monthlyModalMax > 0
                  ? max(18, round((($monthlyModalData[$index] ?? 0) / $monthlyModalMax) * 100))
                  : 18;
              @endphp

              <div class="iv-bar-col" style="--bar-h: {{ $barPercent }}%; --delay: {{ $index * 0.08 }}s;">
                <div class="iv-bar-track">
                  <div class="iv-bar-fill"></div>
                </div>
                <span>{{ $monthLabel }}</span>
              </div>
            @endforeach
          </div>

          <div class="iv-insight-card">
            <div class="iv-insight-icon">👍</div>
            <div class="iv-insight-text">
              <strong>Good job!</strong>
              <span>
                Total profit portofolio saat ini
                <b>Rp {{ number_format($totalProfit, 0, ',', '.') }}</b>
                dengan profit harian aktif
                <b>Rp {{ number_format($totalDailyProfit, 0, ',', '.') }}</b>.
              </span>
            </div>
          </div>
        </article>

        <div class="iv-quick-actions iv-portfolio-actions">
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
      </section>

      {{-- LIST --}}
      <section class="iv-section">
       

        <div class="iv-list">
          @forelse($investments as $inv)
            <article class="iv-card fade-up" style="animation-delay: {{ $loop->index * 0.08 }}s;">
              <div class="iv-card-top">
                <div class="iv-product-icon">
                  {{ strtoupper(substr(optional($inv->product)->name ?? 'I', 0, 1)) }}
                </div>

                <div class="iv-product-info">
                  <h3 class="iv-product-name">{{ optional($inv->product)->name ?? '-' }}</h3>
                  <p class="iv-product-meta">Durasi {{ $inv->duration_days }} Hari • Velora Asset</p>
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

                @php
                  $startTs = \Carbon\Carbon::parse($inv->start_date)->startOfDay();
                  $endTs = \Carbon\Carbon::parse($inv->end_date)->startOfDay();
                  $todayTs = now()->startOfDay();
                  $totalDaysProgress = max(1, $startTs->diffInDays($endTs));
                  $elapsedDaysProgress = max(0, min($totalDaysProgress, $startTs->diffInDays($todayTs, false)));
                  $progressPercent = $inv->status === 'active'
                    ? min(100, max(0, round(($elapsedDaysProgress / $totalDaysProgress) * 100)))
                    : 100;
                @endphp

                <div class="iv-progress-wrap">
                  <div class="iv-progress-head">
                    <span>Progress Periode</span>
                    <strong>{{ $progressPercent }}%</strong>
                  </div>
                  <div class="iv-progress-bar">
                    <div class="iv-progress-fill" style="--progress: {{ $progressPercent }}%;"></div>
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