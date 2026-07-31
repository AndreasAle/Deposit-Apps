@php
  $user = auth()->user();
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

@php
  $vipLevel        = $vipLevel        ?? (int)($user->vip_level ?? 0);
  $nextVipLevel    = $nextVipLevel    ?? null;
  $nextVipTarget   = $nextVipTarget   ?? 0;
  $vipProgress     = $vipProgress     ?? 0;
  $totalInvestasi  = $totalInvestasi  ?? 0;
  $activePlanCount = $activePlanCount ?? 0;
  $saldoUtama      = $saldoUtama      ?? (int)($user->saldo ?? 0);
  $saldoPenarikan  = $saldoPenarikan  ?? (int)($user->saldo_penarikan ?? 0);

  $accountId = str_pad((string)($user->id ?? 0), 12, '0', STR_PAD_LEFT);
  $vipButuh  = max(0, (int)$nextVipTarget - (int)$totalInvestasi);
  $tierName  = $vipLevel > 0 ? 'VIP Member' : 'Classic Member';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard | Capital Wave</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      /* === PALET === */
      --blue:#0A57A3;
      --blue-ink:#0b4a8c;
      --blue-lite:#2f7fd4;
      --blue-soft:#eef4fb;
      --navy:#0b2740;
      --navy-2:#0d3357;
      --navy-3:#0a2036;

      --gold:#c99433;
      --gold-lite:#e8c874;
      --gold-deep:#a9772a;
      --gold-soft:#faf3e2;
      --gold-metal:linear-gradient(135deg,#a9772a 0%,#e8c874 46%,#c99433 100%);

      --green:#1c9d67;
      --green-soft:#e6f5ee;
      --chart:#16a86a;
      --red:#dc5757;

      /* neutrals */
      --bg:#eef1f6;
      --card:#ffffff;
      --tint:#f5f8fc;
      --line:#e9edf4;
      --line-2:#dfe5ee;

      --ink:#152a3f;
      --ink-soft:#46586c;
      --muted:#8493a6;
      --muted-2:#aab6c4;

      /* shadows — layered, premium */
      --sh-sm:0 1px 2px rgba(11,39,64,.05);
      --sh:0 2px 6px rgba(11,39,64,.04), 0 12px 30px rgba(11,39,64,.07);
      --sh-lg:0 4px 10px rgba(11,39,64,.05), 0 22px 50px rgba(11,39,64,.12);
      --sh-navy:0 10px 24px rgba(9,30,52,.28), 0 24px 60px rgba(9,30,52,.30);

      --r:24px;
      --r-sm:16px;
      --r-xs:13px;
    }

    *{ box-sizing:border-box; }
    html,body{ min-height:100%; }

    body{
      margin:0;
      color:var(--ink);
      font-family:'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background:
        radial-gradient(900px 500px at 50% -220px, rgba(10,87,163,.10), transparent 60%),
        radial-gradient(600px 400px at 100% 8%, rgba(201,148,51,.06), transparent 55%),
        linear-gradient(180deg, #f2f5f9 0%, #eef1f6 40%, #eaeef4 100%);
      background-attachment:fixed;
      overflow-x:hidden;
      -webkit-font-smoothing:antialiased;
      -webkit-tap-highlight-color:transparent;
      letter-spacing:-.01em;
    }

    a{ color:inherit; text-decoration:none; }
    button,input{ font-family:inherit; }
    h1,h2,h3,p{ margin:0; }

    .vl-page{ width:100%; min-height:100vh; display:flex; justify-content:center; }
    .vl-phone{ width:100%; max-width:428px; min-height:100vh; position:relative; padding:18px 16px 112px; }

    .vl-gold-text{ background:var(--gold-metal); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }

    /* ============ HEADER (Capital Wave) ============ */
    .cw-header{ display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:20px; }
    .cw-brand{ display:flex; align-items:center; gap:11px; min-width:0; }
    .cw-mark{
      width:42px; height:42px; border-radius:50%; flex:0 0 auto; position:relative; display:grid; place-items:center;
      background:linear-gradient(160deg,#ffffff 0%,#eef4fb 100%);
      box-shadow:0 8px 20px rgba(11,39,64,.26), inset 0 1px 0 rgba(255,255,255,.14);
    }
    .cw-mark::after{ content:""; position:absolute; inset:0; border-radius:50%; padding:1.4px; background:var(--gold-metal); -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; opacity:.85; }
    .cw-mark svg{ width:26px; height:26px; position:relative; z-index:1; }
    .cw-word{ display:flex; flex-direction:column; min-width:0; line-height:1; }
    .cw-word .name{ font-size:18px; font-weight:800; letter-spacing:.01em; color:var(--navy); white-space:nowrap; }
    .cw-word .name b{ font-weight:800; }
    .cw-word .tag{ margin-top:5px; font-size:8.5px; font-weight:600; letter-spacing:.24em; text-transform:uppercase; color:var(--muted); white-space:nowrap; }

    .cw-tools{ display:flex; align-items:center; gap:2px; padding:4px; border-radius:999px; background:var(--card); border:1px solid var(--line); box-shadow:var(--sh-sm); flex:0 0 auto; }
    .cw-tool{ width:38px; height:38px; border-radius:999px; display:grid; place-items:center; color:var(--ink-soft); position:relative; transition:.16s ease; }
    .cw-tool:hover{ color:var(--blue); background:var(--blue-soft); }
    .cw-tool svg{ width:19px; height:19px; }
    .cw-tool .vl-dot{ position:absolute; right:8px; top:8px; width:7px; height:7px; border-radius:999px; background:var(--gold); border:2px solid var(--card); }
    .cw-tool-div{ width:1px; height:20px; background:var(--line-2); }

    /* ============ PROFILE ============ */
    .vl-profile{
      position:relative; overflow:hidden; border-radius:var(--r); padding:15px 17px; margin-bottom:15px;
      background:var(--card); border:1px solid var(--line); box-shadow:var(--sh);
      display:flex; align-items:center; gap:13px;
    }
    .vl-avatar{ position:relative; width:48px; height:48px; flex:0 0 auto; border-radius:15px; padding:2px; background:var(--gold-metal); }
    .vl-avatar-inner{ width:100%; height:100%; border-radius:13px; display:grid; place-items:center; color:var(--blue); background:var(--blue-soft); }
    .vl-avatar-inner svg{ width:24px; height:24px; }
    .vl-profile-info{ min-width:0; flex:1; }
    .vl-profile-info h2{ font-size:16px; font-weight:700; letter-spacing:-.02em; color:var(--navy); }
    .vl-tier{ margin-top:6px; display:inline-flex; align-items:center; gap:6px; padding:3px 9px 3px 8px; border-radius:999px; background:var(--gold-soft); border:1px solid rgba(201,148,51,.22); }
    .vl-tier svg{ width:11px; height:11px; color:var(--gold-deep); }
    .vl-tier b{ font-size:10px; font-weight:600; letter-spacing:.04em; color:var(--gold-deep); }
    .vl-profile-id{ margin-left:8px; font-size:10.5px; font-weight:500; color:var(--muted); }
    .vl-profile-id b{ color:var(--ink-soft); font-weight:600; }
    .vl-mascot{ flex:0 0 auto; width:48px; height:48px; }

    /* ============ PORTFOLIO HERO (premium navy) ============ */
    .vl-hero{
      position:relative; overflow:hidden; border-radius:26px; padding:20px; color:#fff;
      background:
        radial-gradient(420px 240px at 88% -20%, rgba(232,200,116,.16), transparent 62%),
        radial-gradient(360px 220px at 8% 120%, rgba(47,127,212,.22), transparent 60%),
        linear-gradient(150deg,#0f3255 0%, #0b2740 52%, #0a2036 100%);
      box-shadow:var(--sh-navy);
    }
    /* hairline gold border */
    .vl-hero::before{
      content:""; position:absolute; inset:0; border-radius:inherit; padding:1px; pointer-events:none;
      background:linear-gradient(150deg, rgba(232,200,116,.6), rgba(232,200,116,0) 34%, rgba(255,255,255,.14) 100%);
      -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude;
    }
    /* faint grid texture */
    .vl-hero::after{
      content:""; position:absolute; inset:0; pointer-events:none; opacity:.5;
      background-image:linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
      background-size:26px 26px;
      -webkit-mask-image:radial-gradient(120% 90% at 80% 0%, #000, transparent 70%);
      mask-image:radial-gradient(120% 90% at 80% 0%, #000, transparent 70%);
    }
    .vl-hero > *{ position:relative; z-index:1; }
    .vl-hero-top{ display:flex; align-items:center; justify-content:space-between; gap:12px; }
    .vl-eyebrow{ display:inline-flex; align-items:center; gap:8px; color:rgba(255,255,255,.62); font-size:9.5px; font-weight:600; letter-spacing:.2em; text-transform:uppercase; }
    .vl-eyebrow::before{ content:""; width:6px; height:6px; border-radius:999px; background:var(--gold-lite); box-shadow:0 0 10px rgba(232,200,116,.8); }
    .vl-live{ display:inline-flex; align-items:center; gap:6px; padding:5px 11px; border-radius:999px; color:#0b2740; background:var(--gold-metal); font-size:10px; font-weight:700; letter-spacing:.02em; box-shadow:0 6px 16px rgba(201,148,51,.34); }
    .vl-live svg{ width:12px; height:12px; }

    .vl-balance{ margin-top:16px; color:#fff; font-size:36px; font-weight:700; letter-spacing:-.035em; line-height:1; text-shadow:0 8px 24px rgba(0,0,0,.28); }
    .vl-balance .rp{ font-size:17px; font-weight:600; color:rgba(255,255,255,.55); margin-right:4px; vertical-align:2px; }
    .vl-hero-hint{ margin-top:9px; display:inline-flex; align-items:center; gap:7px; color:rgba(255,255,255,.5); font-size:11px; font-weight:500; }
    .vl-hero-hint svg{ width:13px; height:13px; color:var(--green); }
    .vl-hero-hint b{ color:#7fe3b4; font-weight:600; }

    .vl-divider{ margin:17px 0; height:1px; background:linear-gradient(90deg, transparent, rgba(232,200,116,.32) 22%, rgba(255,255,255,.1) 78%, transparent); }

    .vl-sub{ display:grid; grid-template-columns:1fr 1fr; gap:22px; }
    .vl-sub-box{ position:relative; }
    .vl-sub-box + .vl-sub-box::before{ content:""; position:absolute; left:-11px; top:2px; bottom:2px; width:1px; background:linear-gradient(180deg, transparent, rgba(255,255,255,.12), transparent); }
    .vl-sub-box span{ display:flex; align-items:center; gap:6px; color:rgba(255,255,255,.55); font-size:10.5px; font-weight:500; }
    .vl-sub-box span svg{ width:13px; height:13px; color:var(--gold-lite); opacity:.9; }
    .vl-sub-box strong{ display:block; margin-top:7px; color:#fff; font-size:16px; font-weight:600; letter-spacing:-.02em; }
    .vl-sub-box strong .rp{ font-size:11px; color:rgba(255,255,255,.5); font-weight:500; margin-right:2px; }

    .vl-hero-actions{ margin-top:18px; display:grid; grid-template-columns:1.15fr 1fr; gap:11px; }
    .vl-btn{ min-height:50px; border-radius:15px; display:flex; align-items:center; justify-content:center; gap:8px; font-size:13.5px; font-weight:700; letter-spacing:-.01em; transition:.16s ease; }
    .vl-btn svg{ width:17px; height:17px; }
    .vl-btn.primary{ position:relative; color:#3d2b06; background:var(--gold-metal); box-shadow:0 10px 24px rgba(201,148,51,.4), inset 0 1px 0 rgba(255,255,255,.4); overflow:hidden; }
    .vl-btn.primary::after{ content:""; position:absolute; top:0; left:-60%; width:40%; height:100%; background:linear-gradient(100deg, transparent, rgba(255,255,255,.55), transparent); transform:skewX(-18deg); animation:vlSheen 4.5s ease-in-out infinite; }
    .vl-btn.primary:hover{ transform:translateY(-1px); }
    .vl-btn.ghost{ color:#fff; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.2); }
    .vl-btn.ghost:hover{ background:rgba(255,255,255,.14); }

    /* ============ VIP (membership) ============ */
    .vl-vip{
      margin-top:15px; position:relative; overflow:hidden; border-radius:var(--r); padding:17px 18px;
      background:linear-gradient(135deg,#ffffff 0%, #fdf8ee 100%); border:1px solid var(--line); box-shadow:var(--sh);
    }
    .vl-vip::before{ content:""; position:absolute; left:0; top:0; bottom:0; width:4px; background:var(--gold-metal); }
    .vl-vip::after{ content:""; position:absolute; right:-40px; top:-50px; width:150px; height:150px; border-radius:50%; background:radial-gradient(circle, rgba(201,148,51,.12), transparent 70%); }
    .vl-vip > *{ position:relative; z-index:1; }
    .vl-vip-top{ display:flex; align-items:center; gap:13px; }
    .vl-vip-badge{ position:relative; width:46px; height:46px; flex:0 0 auto; border-radius:14px; padding:1.5px; background:var(--gold-metal); box-shadow:0 8px 18px rgba(201,148,51,.3); }
    .vl-vip-badge span{ width:100%; height:100%; border-radius:12px; display:grid; place-items:center; color:var(--gold-deep); background:#fffdf8; }
    .vl-vip-badge svg{ width:21px; height:21px; }
    .vl-vip-info{ flex:1; min-width:0; }
    .vl-vip-info .k{ display:block; color:var(--gold-deep); font-size:9px; font-weight:700; letter-spacing:.16em; text-transform:uppercase; }
    .vl-vip-info h3{ margin-top:5px; font-size:17px; font-weight:700; letter-spacing:-.02em; color:var(--navy); }
    .vl-vip-info p{ margin-top:3px; font-size:11.5px; font-weight:500; color:var(--muted); }
    .vl-vip-info p b{ color:var(--ink-soft); font-weight:600; }
    .vl-vip-chevron{ color:var(--muted-2); }
    .vl-vip-chevron svg{ width:19px; height:19px; }

    .vl-vip-progress{ margin-top:16px; }
    .vl-vip-progress-head{ display:flex; align-items:center; justify-content:space-between; margin-bottom:9px; font-size:11.5px; font-weight:500; }
    .vl-vip-progress-head .lbl{ color:var(--ink-soft); }
    .vl-vip-progress-head .lbl b{ color:var(--navy); font-weight:700; }
    .vl-vip-progress-head .pct{ font-weight:700; }
    .vl-track{ height:8px; border-radius:999px; background:#eee6d4; overflow:hidden; box-shadow:inset 0 1px 2px rgba(11,39,64,.08); }
    .vl-track-fill{ height:100%; border-radius:999px; background:var(--gold-metal); box-shadow:0 1px 4px rgba(201,148,51,.5); transition:width .9s cubic-bezier(.22,.8,.22,1); }
    .vl-vip-foot{ margin-top:12px; display:flex; align-items:center; justify-content:space-between; gap:10px; font-size:11.5px; font-weight:500; color:var(--muted); }
    .vl-vip-foot b{ color:var(--ink); font-weight:700; }
    .vl-vip-foot .chip{ display:inline-flex; align-items:center; gap:6px; padding:5px 10px; border-radius:999px; background:var(--blue-soft); color:var(--blue); font-weight:600; }
    .vl-vip-foot .chip::before{ content:""; width:5px; height:5px; border-radius:999px; background:var(--blue); }

    /* ============ QUICK MENU ============ */
    .vl-quick{ margin-top:22px; display:grid; grid-template-columns:repeat(5,1fr); gap:4px; padding:16px 8px 14px; background:var(--card); border:1px solid var(--line); border-radius:22px; box-shadow:var(--sh); }
    .vl-quick-item{ display:flex; flex-direction:column; align-items:center; gap:9px; padding:4px 2px; background:none; border:0; cursor:pointer; opacity:0; transform:translateY(10px); animation:vlQuickIn .55s cubic-bezier(.22,.8,.22,1) forwards; }
    .vl-quick-item:nth-child(1){ animation-delay:.04s; }
    .vl-quick-item:nth-child(2){ animation-delay:.10s; }
    .vl-quick-item:nth-child(3){ animation-delay:.16s; }
    .vl-quick-item:nth-child(4){ animation-delay:.22s; }
    .vl-quick-item:nth-child(5){ animation-delay:.28s; }
    .vl-quick-icon{
      width:50px; height:50px; border-radius:16px; display:grid; place-items:center; position:relative;
      color:var(--blue); background:var(--blue-soft);
      transition:transform .22s cubic-bezier(.22,.8,.22,1), box-shadow .2s ease, color .2s ease, background .2s ease;
    }
    .vl-quick-icon::after{ content:""; position:absolute; inset:0; border-radius:inherit; padding:1.4px; background:var(--gold-metal); -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; opacity:0; transition:.2s ease; }
    .vl-quick-item:hover .vl-quick-icon{ transform:translateY(-4px); color:var(--gold-deep); background:var(--gold-soft); box-shadow:0 12px 22px rgba(201,148,51,.26); }
    .vl-quick-item:hover .vl-quick-icon::after{ opacity:1; }
    .vl-quick-item:active .vl-quick-icon{ transform:translateY(-1px) scale(.95); }
    .vl-quick-icon svg{ width:23px; height:23px; position:relative; z-index:1; }
    .vl-quick-icon .badge{ position:absolute; right:7px; top:7px; width:8px; height:8px; border-radius:999px; background:var(--gold); border:2px solid var(--card); z-index:2; box-shadow:0 0 0 3px rgba(201,148,51,.14); }
    .vl-quick-item span{ font-size:10.5px; font-weight:600; color:var(--ink-soft); }
    @keyframes vlQuickIn{ to{ opacity:1; transform:translateY(0); } }

    /* ============ SECTION ============ */
    .vl-section{ margin-top:26px; }
    .vl-section-head{ display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:15px; }
    .vl-section-title{ display:flex; align-items:center; gap:11px; }
    .vl-section-title .bar{ width:4px; height:30px; border-radius:999px; background:var(--gold-metal); }
    .vl-section-title h2{ color:var(--navy); font-size:18px; font-weight:700; letter-spacing:-.025em; }
    .vl-section-title p{ margin-top:3px; color:var(--muted); font-size:11.5px; font-weight:500; }
    .vl-see-all{ display:inline-flex; align-items:center; gap:5px; padding:8px 14px; border-radius:999px; color:var(--blue); background:var(--card); border:1px solid var(--line); box-shadow:var(--sh-sm); font-size:11.5px; font-weight:600; white-space:nowrap; }
    .vl-see-all svg{ width:13px; height:13px; }

    /* ============ CATEGORY TABS ============ */
    .vl-cats{ display:flex; gap:8px; overflow-x:auto; overflow-y:hidden; padding:8px 4px 22px; margin:0 -2px -10px; scrollbar-width:none; }
    .vl-cats::-webkit-scrollbar{ display:none; }
    .vl-cat{
      flex:0 0 auto; display:inline-flex; align-items:center; gap:7px; height:40px; padding:0 16px; border:0; cursor:pointer;
      border-radius:999px; color:var(--ink-soft); background:var(--card); border:1px solid var(--line); box-shadow:var(--sh-sm);
      font-size:12.5px; font-weight:500; transition:transform .16s ease, color .16s ease, background .16s ease, box-shadow .16s ease; white-space:nowrap;
      opacity:0; animation:vlQuickIn .5s cubic-bezier(.22,.8,.22,1) forwards;
    }
    .vl-cat:nth-child(1){ animation-delay:.22s; }
    .vl-cat:nth-child(2){ animation-delay:.28s; }
    .vl-cat:nth-child(3){ animation-delay:.34s; }
    .vl-cat:nth-child(4){ animation-delay:.40s; }
    .vl-cat:active{ transform:scale(.97); }
    .vl-cat svg{ width:15px; height:15px; opacity:.85; }
    .vl-cat .count{ font-size:11px; color:var(--muted); font-weight:600; }
    .vl-cat.active{ color:#fff; background:linear-gradient(135deg,#123457,#0b2740); border-color:transparent; font-weight:600; box-shadow:0 8px 16px rgba(11,39,64,.2); }
    .vl-cat.active svg{ opacity:1; color:var(--gold-lite); }
    .vl-cat.active .count{ color:rgba(255,255,255,.6); }

    /* ============ PRODUCT CARDS (slider) ============ */
    .vl-products{
      display:flex; flex-direction:row; gap:13px;
      overflow-x:auto; overflow-y:hidden; scroll-snap-type:x mandatory; -webkit-overflow-scrolling:touch;
      margin:-2px 0 -8px; padding:8px 0 20px; scrollbar-width:none;
    }
    .vl-products::-webkit-scrollbar{ display:none; }
    .vl-product-pane{ display:none; }
    .vl-product-pane.active{ display:block; }
    .vl-asset-card{ flex:0 0 88%; scroll-snap-align:start; position:relative; overflow:hidden; border-radius:var(--r); background:var(--card); border:1px solid var(--line); box-shadow:var(--sh); transition:.18s ease; }
    .vl-asset-card::before{ content:""; position:absolute; top:0; left:0; right:0; height:3px; background:var(--gold-metal); opacity:0; transition:.18s ease; }
    .vl-asset-card:hover{ box-shadow:var(--sh-lg); transform:translateY(-1px); }
    .vl-asset-card:hover::before{ opacity:1; }

    .vl-asset-top{ display:flex; align-items:center; gap:12px; padding:17px 17px 12px; }
    .vl-token{ position:relative; width:44px; height:44px; flex:0 0 auto; border-radius:13px; padding:1.5px; background:var(--gold-metal); }
    .vl-token span{ width:100%; height:100%; border-radius:11px; display:grid; place-items:center; color:var(--navy); background:#fff; font-size:13px; font-weight:700; letter-spacing:-.02em; }
    .vl-asset-headline{ min-width:0; flex:1; }
    .vl-asset-headline h3{ color:var(--navy); font-size:15.5px; font-weight:700; letter-spacing:-.02em; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .vl-asset-headline p{ margin-top:4px; display:flex; align-items:center; gap:5px; color:var(--muted); font-size:11px; font-weight:500; }
    .vl-asset-headline p svg{ width:12px; height:12px; color:var(--green); }
    .vl-profit-badge{ flex:0 0 auto; display:inline-flex; align-items:center; gap:3px; padding:5px 10px; border-radius:10px; color:var(--green); background:var(--green-soft); font-size:11.5px; font-weight:700; }

    .vl-spark-wrap{ padding:0 16px; }
    .vl-spark{ width:100%; height:70px; display:block; overflow:visible; }
    .vl-spark .spark-grid{ stroke:var(--line); stroke-width:1; stroke-dasharray:2 4; }
    .vl-spark .spark-area{ fill:url(#vlSparkFill); }
    .vl-spark .spark-line{ fill:none; stroke:var(--chart); stroke-width:2; stroke-linecap:round; stroke-linejoin:round; filter:drop-shadow(0 4px 7px rgba(22,168,106,.20)); stroke-dasharray:520; stroke-dashoffset:520; animation:vlDraw 1.2s ease forwards; }
    .vl-spark .spark-glow{ display:none; }
    .vl-spark .spark-dot{ fill:var(--chart); stroke:#fff; stroke-width:1.6; filter:drop-shadow(0 0 6px rgba(22,168,106,.75)); animation:vlPulse 2s ease-in-out infinite; transform-origin:center; }

    .vl-indicators{ display:grid; grid-template-columns:repeat(3,1fr); gap:6px; padding:15px 16px 8px; }
    .vl-ind{ text-align:center; }
    .vl-ind span{ display:block; margin-bottom:8px; color:var(--muted); font-size:9px; font-weight:600; letter-spacing:.07em; text-transform:uppercase; }
    .vl-ind-dots{ display:flex; justify-content:center; gap:3px; }
    .vl-ind-dots i{ width:14px; height:4px; border-radius:999px; background:var(--line-2); }
    .vl-ind.risk i.on{ background:var(--gold); }
    .vl-ind.profit i.on{ background:var(--green); }
    .vl-ind.eff i.on{ background:var(--blue); }

    .vl-asset-detail{ display:grid; grid-template-columns:repeat(3,1fr); border-top:1px solid var(--line); margin-top:14px; background:var(--tint); }
    .vl-detail{ padding:14px; }
    .vl-detail + .vl-detail{ border-left:1px solid var(--line); }
    .vl-detail span{ display:block; margin-bottom:6px; color:var(--muted); font-size:9.5px; font-weight:500; letter-spacing:.05em; text-transform:uppercase; }
    .vl-detail strong{ color:var(--navy); font-size:13px; font-weight:700; letter-spacing:-.01em; }
    .vl-detail strong.gold{ color:var(--gold-deep); }
    .vl-detail strong.blue{ color:var(--blue); }

    .vl-asset-action{ padding:14px 16px 16px; }
    .vl-buy{ position:relative; overflow:hidden; width:100%; min-height:48px; border:0; border-radius:14px; display:flex; align-items:center; justify-content:center; gap:8px; color:#fff; background:linear-gradient(135deg,#123457,#0b2740); box-shadow:0 10px 22px rgba(11,39,64,.24), inset 0 1px 0 rgba(255,255,255,.08); font-size:13.5px; font-weight:700; letter-spacing:-.01em; cursor:pointer; transition:.16s ease; }
    .vl-buy::after{ content:""; position:absolute; inset:0; border-radius:inherit; padding:1px; background:linear-gradient(135deg, rgba(232,200,116,.7), transparent 55%); -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; opacity:.6; }
    .vl-buy:hover{ transform:translateY(-1px); }
    .vl-buy svg{ width:16px; height:16px; position:relative; z-index:1; }
    /* blink / kilau untuk CTA Deposit */
    .vl-buy.is-blink{ animation:vlBuyPulse 2.4s ease-in-out infinite; }
    .vl-buy.is-blink::before{ content:""; position:absolute; top:0; left:-75%; width:50%; height:100%; z-index:2; pointer-events:none;
      background:linear-gradient(100deg, transparent, rgba(255,255,255,.48), transparent); transform:skewX(-18deg);
      animation:vlBuyShine 2.4s ease-in-out infinite; }
    @keyframes vlBuyShine{ 0%{ left:-75%; } 50%,100%{ left:135%; } }
    @keyframes vlBuyPulse{
      0%,100%{ box-shadow:0 10px 22px rgba(11,39,64,.24), inset 0 1px 0 rgba(255,255,255,.08); }
      50%{ box-shadow:0 12px 26px rgba(11,39,64,.3), 0 0 0 3px rgba(232,200,116,.22), inset 0 1px 0 rgba(255,255,255,.08); }
    }
    .vl-buy.muted{ color:var(--muted); background:var(--tint); border:1px solid var(--line); box-shadow:none; cursor:default; }
    .vl-buy.muted::after{ display:none; }
    .vl-empty{ flex:1 0 100%; padding:24px 16px; border-radius:var(--r); background:var(--card); border:1px dashed var(--line-2); color:var(--muted); text-align:center; font-size:12.5px; font-weight:500; }

    /* ============ CS POPUP ============ */
    .vl-cs-popup{ position:fixed; inset:0; z-index:100000; display:none; align-items:center; justify-content:center; padding:20px 16px; background:rgba(11,39,64,.55); backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px); }
    .vl-cs-popup.show{ display:flex; }
    .vl-cs-card{ width:100%; max-width:340px; overflow:hidden; border-radius:22px; background:var(--card); box-shadow:var(--sh-lg); animation:vlIn .3s cubic-bezier(.22,.8,.22,1) both; }
    .vl-cs-inner{ padding:16px 16px 15px; }
    .vl-cs-top{ display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:12px; }
    .vl-cs-brand{ display:flex; align-items:center; gap:10px; min-width:0; }
    .vl-cs-logo{ width:40px; height:40px; border-radius:12px; display:grid; place-items:center; overflow:hidden; flex:0 0 auto; background:linear-gradient(160deg,#ffffff,#eef4fb); border:1px solid var(--line); }
    .vl-cs-logo img{ width:26px; height:26px; object-fit:contain; display:block; }
    .vl-cs-brand span{ display:block; margin-bottom:3px; font-size:8.5px; font-weight:600; letter-spacing:.16em; color:var(--gold-deep); text-transform:uppercase; }
    .vl-cs-brand strong{ display:block; color:var(--navy); font-size:15px; font-weight:700; letter-spacing:-.02em; }
    .vl-cs-close{ width:34px; height:34px; border:0; border-radius:11px; color:var(--ink-soft); background:var(--tint); display:grid; place-items:center; cursor:pointer; }
    .vl-cs-badge{ display:inline-flex; align-items:center; gap:6px; padding:5px 10px; border-radius:999px; color:var(--gold-deep); background:var(--gold-soft); border:1px solid rgba(201,148,51,.22); font-size:9px; font-weight:600; letter-spacing:.06em; text-transform:uppercase; }
    .vl-cs-title{ margin-top:10px; color:var(--navy); font-size:17px; font-weight:700; line-height:1.15; letter-spacing:-.03em; }
    .vl-cs-title span{ color:var(--blue); }
    .vl-cs-desc{ margin-top:6px; max-width:290px; color:var(--ink-soft); font-size:11px; line-height:1.5; font-weight:500; }
    .vl-cs-info{ margin-top:12px; display:grid; gap:6px; }
    .vl-cs-info-item{ display:flex; align-items:center; gap:10px; padding:9px 10px; border-radius:12px; background:var(--tint); border:1px solid var(--line); }
    .vl-cs-info-icon{ width:31px; height:31px; border-radius:10px; display:grid; place-items:center; color:var(--blue); background:var(--blue-soft); flex:0 0 auto; }
    .vl-cs-info-icon svg{ width:16px; height:16px; }
    .vl-cs-info-text span{ display:block; margin-bottom:2px; color:var(--muted); font-size:9px; font-weight:500; text-transform:uppercase; letter-spacing:.06em; }
    .vl-cs-info-text strong{ display:block; color:var(--navy); font-size:12px; font-weight:600; }
    .vl-cs-actions{ margin-top:12px; display:grid; grid-template-columns:1fr 1fr; gap:8px; }
    .vl-cs-btn{ min-height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; gap:7px; font-size:11.5px; font-weight:600; transition:.16s ease; }
    .vl-cs-btn svg{ width:16px; height:16px; }
    .vl-cs-btn.cs{ color:var(--navy); background:var(--gold-soft); border:1px solid rgba(201,148,51,.28); }
    .vl-cs-btn.channel{ color:#fff; background:linear-gradient(135deg,#123457,#0b2740); }
    .vl-cs-btn.wa{ grid-column:1 / -1; color:#fff; background:linear-gradient(135deg,#25d366,#1aa251); box-shadow:0 10px 22px rgba(37,211,102,.3); }
    .vl-cs-info-icon.wa{ color:#1aa251; background:#e7f9ef; }
    .vl-cs-foot{ margin-top:11px; display:flex; align-items:center; justify-content:center; gap:7px; color:var(--muted); font-size:10px; font-weight:500; }
    .vl-cs-foot svg{ width:13px; height:13px; color:var(--gold); }

    /* ============ MODAL ============ */
    .vl-modal-overlay{ position:fixed; inset:0; z-index:9999; display:none; align-items:center; justify-content:center; padding:18px 16px; background:rgba(11,39,64,.55); backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px); }
    .vl-modal-overlay.show{ display:flex; }
    .vl-modal{ width:100%; max-width:404px; border-radius:22px; background:var(--card); box-shadow:var(--sh-lg); overflow:hidden; animation:vlIn .24s ease both; }
    .vl-modal-head{ padding:15px 16px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid var(--line); }
    .vl-modal-title{ display:flex; align-items:center; gap:10px; color:var(--navy); font-size:14.5px; font-weight:700; letter-spacing:-.02em; }
    .vl-modal-icon{ width:34px; height:34px; border-radius:11px; display:grid; place-items:center; color:var(--gold-deep); background:var(--gold-soft); }
    .vl-modal-close{ width:36px; height:36px; border-radius:11px; border:1px solid var(--line); background:var(--tint); color:var(--ink-soft); display:grid; place-items:center; cursor:pointer; }
    .vl-modal-body{ padding:18px; color:var(--ink-soft); text-align:center; font-size:13px; line-height:1.55; font-weight:500; }
    .vl-modal-body b{ color:var(--navy); font-weight:600; }
    .vl-modal-actions{ padding:0 18px 18px; display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    .vl-modal-btn{ min-height:44px; border-radius:13px; border:1px solid var(--line-2); background:var(--card); color:var(--ink-soft); font-size:12.5px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; }
    .vl-modal-btn.primary{ border:0; color:#fff; background:linear-gradient(135deg,#123457,#0b2740); }

    /* ============ ANIMATIONS ============ */
    @keyframes vlDraw{ to{ stroke-dashoffset:0; } }
    @keyframes vlPulse{ 0%,100%{ opacity:.6; } 50%{ opacity:1; } }
    @keyframes vlIn{ from{ opacity:0; transform:translateY(14px) scale(.98); } to{ opacity:1; transform:translateY(0) scale(1); } }
    @keyframes vlSheen{ 0%,60%{ left:-60%; } 100%{ left:130%; } }

    @media (max-width:360px){
      .vl-phone{ padding:16px 12px 112px; }
      .vl-balance{ font-size:31px; }
      .vl-quick-icon{ width:48px; height:48px; border-radius:16px; }
      .vl-asset-detail{ grid-template-columns:1fr; }
      .vl-detail + .vl-detail{ border-left:0; border-top:1px solid var(--line); }
    }
    @media (prefers-reduced-motion:reduce){
      *,*::before,*::after{ animation:none !important; transition:none !important; }
      .vl-quick-item,.vl-cat{ opacity:1 !important; transform:none !important; }
    }
  </style>
</head>

<body>
  {{-- shared gradient defs untuk chart --}}
  <svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <defs>
      <linearGradient id="vlSparkFill" x1="0" y1="0" x2="0" y2="1">
        <stop offset="0%" stop-color="#16a86a" stop-opacity=".26"/>
        <stop offset="55%" stop-color="#16a86a" stop-opacity=".08"/>
        <stop offset="100%" stop-color="#16a86a" stop-opacity="0"/>
      </linearGradient>
    </defs>
  </svg>

  <main class="vl-page">
    <div class="vl-phone">

      {{-- HEADER --}}
      <header class="cw-header">
        <a href="{{ url('/dashboard') }}" class="cw-brand" aria-label="Capital Wave">
          <span class="cw-mark">
            <img src="{{ asset('logo.png') }}" alt="Capital Wave" style="width:82%;height:82%;object-fit:contain;position:relative;z-index:1;">
          </span>
          <span class="cw-word">
            <span class="name">Capital&nbsp;<b class="vl-gold-text">Wave</b></span>
            <span class="tag">Ride the Wave</span>
          </span>
        </a>

        <div class="cw-tools">
          <a href="{{ url('/saldo/rincian') }}" class="cw-tool" aria-label="Notifikasi">
            <svg viewBox="0 0 24 24" fill="none"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
            <span class="vl-dot"></span>
          </a>
          <span class="cw-tool-div"></span>
          <a href="{{ url('/akun') }}" class="cw-tool" aria-label="Akun">
            <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="3.6" stroke="currentColor" stroke-width="1.7"/><path d="M5 20a7 7 0 0 1 14 0" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
          </a>
        </div>
      </header>

      @if ($errors->any())
        <div style="margin:0 0 14px; padding:13px 15px; border-radius:16px; background:#fdecec; border:1px solid #f7d4d4; color:#a33; font-size:12.5px; font-weight:500; line-height:1.5;">
          <strong style="display:block; margin-bottom:5px; color:#0b2740; font-weight:700;">Terjadi kesalahan</strong>
          <ul style="margin:0; padding-left:18px;">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
          </ul>
        </div>
      @endif

      {{-- PROFILE --}}
      <section class="vl-profile">
        <div class="vl-avatar">
          <div class="vl-avatar-inner">
            <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="3.6" stroke="currentColor" stroke-width="1.8"/><path d="M5 20a7 7 0 0 1 14 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
          </div>
        </div>
        <div class="vl-profile-info">
          <h2>{{ $user->name ?: 'Investor Capital Wave' }}</h2>
          <div style="display:flex;align-items:center;flex-wrap:wrap;">
            <span class="vl-tier">
              <svg viewBox="0 0 24 24" fill="currentColor"><path d="m12 2 2.9 6 6.1.9-4.5 4.3 1.1 6L12 16.9 6.4 19.2l1.1-6L3 8.9 9.1 8 12 2Z"/></svg>
              <b>{{ $tierName }}</b>
            </span>
            <span class="vl-profile-id">ID <b>{{ $accountId }}</b></span>
          </div>
        </div>
        <svg class="vl-mascot" viewBox="0 0 64 64" fill="none" aria-hidden="true">
          <rect x="29" y="7" width="6" height="7" rx="3" fill="#c99433"/><circle cx="32" cy="6" r="2.4" fill="#e8c874"/>
          <rect x="13" y="14" width="38" height="30" rx="13" fill="#0b2740"/>
          <rect x="18" y="20" width="28" height="17" rx="8.5" fill="#0A57A3"/>
          <circle cx="27" cy="28.5" r="3" fill="#e8c874"/><circle cx="37" cy="28.5" r="3" fill="#e8c874"/>
          <rect x="28" y="40" width="8" height="10" rx="4" fill="#123a5c"/>
          <rect x="7" y="24" width="6" height="14" rx="3" fill="#123a5c"/><rect x="51" y="24" width="6" height="14" rx="3" fill="#123a5c"/>
        </svg>
      </section>

      {{-- PORTFOLIO --}}
      <section class="vl-hero">
        <div class="vl-hero-top">
          <span class="vl-eyebrow">Total Portofolio</span>
          <span class="vl-live">
            <svg viewBox="0 0 24 24" fill="none"><path d="M4 15l5-5 4 4 7-8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Live
          </span>
        </div>

        <h2 class="vl-balance"><span class="rp">Rp</span>{{ number_format($saldoUtama, 0, ',', '.') }}</h2>
        <div class="vl-hero-hint">
          <svg viewBox="0 0 24 24" fill="none"><path d="M4 15l5-5 4 4 7-8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          Portofolio kamu terpantau real-time
        </div>

        <div class="vl-divider"></div>

        <div class="vl-sub">
          <div class="vl-sub-box">
            <span><svg viewBox="0 0 24 24" fill="none"><rect x="3" y="6" width="18" height="13" rx="2.5" stroke="currentColor" stroke-width="1.8"/><path d="M3 10h18" stroke="currentColor" stroke-width="1.8"/></svg> Saldo Akun</span>
            <strong><span class="rp">Rp</span>{{ number_format($saldoUtama, 0, ',', '.') }}</strong>
          </div>
          <div class="vl-sub-box">
            <span><svg viewBox="0 0 24 24" fill="none"><path d="M4 10 12 4l8 6" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M5 10v9h14v-9" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg> Saldo Bank</span>
            <strong><span class="rp">Rp</span>{{ number_format($saldoPenarikan, 0, ',', '.') }}</strong>
          </div>
        </div>

        <div class="vl-hero-actions">
          <a href="{{ url('/deposit') }}" class="vl-btn primary">
            <svg viewBox="0 0 24 24" fill="none"><path d="M12 4v11" stroke="currentColor" stroke-width="2.1" stroke-linecap="round"/><path d="M7 11l5 5 5-5" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 20h14" stroke="currentColor" stroke-width="2.1" stroke-linecap="round"/></svg>
            Deposit
          </a>
          <a href="{{ url('/ui/withdrawals') }}" class="vl-btn ghost">
            <svg viewBox="0 0 24 24" fill="none"><path d="M12 20V9" stroke="currentColor" stroke-width="2.1" stroke-linecap="round"/><path d="M7 13l5-5 5 5" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 4h14" stroke="currentColor" stroke-width="2.1" stroke-linecap="round"/></svg>
            Withdraw
          </a>
        </div>
      </section>

      {{-- VIP --}}
      <a href="{{ url('/akun') }}" style="display:block;">
        <section class="vl-vip">
          <div class="vl-vip-top">
            <div class="vl-vip-badge"><span><svg viewBox="0 0 24 24" fill="currentColor"><path d="m12 2 2.9 6 6.1.9-4.5 4.3 1.1 6L12 16.9 6.4 19.2l1.1-6L3 8.9 9.1 8 12 2Z"/></svg></span></div>
            <div class="vl-vip-info">
              <span class="k">Tingkat VIP</span>
              <h3>VIP {{ $vipLevel }}</h3>
              <p>Total investasi <b>Rp {{ number_format((int)$totalInvestasi, 0, ',', '.') }}</b></p>
            </div>
            <span class="vl-vip-chevron"><svg viewBox="0 0 24 24" fill="none"><path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
          </div>

          @if($nextVipLevel)
            <div class="vl-vip-progress">
              <div class="vl-vip-progress-head">
                <span class="lbl">Menuju <b>VIP {{ $nextVipLevel }}</b></span>
                <span class="pct vl-gold-text">{{ $vipProgress }}%</span>
              </div>
              <div class="vl-track"><div class="vl-track-fill" style="width: {{ $vipProgress }}%"></div></div>
              <div class="vl-vip-foot">
                <span>Butuh <b>Rp {{ number_format($vipButuh, 0, ',', '.') }}</b> lagi</span>
                <span class="chip">{{ $activePlanCount }} paket aktif</span>
              </div>
            </div>
          @else
            <div class="vl-vip-progress">
              <div class="vl-vip-foot">
                <span>Level VIP tertinggi tercapai</span>
                <span class="chip">{{ $activePlanCount }} paket aktif</span>
              </div>
            </div>
          @endif
        </section>
      </a>

      {{-- QUICK MENU --}}
      <nav class="vl-quick">
        <a href="{{ route('investasi.index') }}" class="vl-quick-item">
          <span class="vl-quick-icon"><svg viewBox="0 0 24 24" fill="none"><path d="M9 11.5l2.2 2.2L15 9.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><rect x="4" y="4" width="16" height="16" rx="4.5" stroke="currentColor" stroke-width="1.8"/></svg></span>
          <span>Tugas</span>
        </a>
        <a href="{{ route('referral.index') }}" class="vl-quick-item">
          <span class="vl-quick-icon"><svg viewBox="0 0 24 24" fill="none"><circle cx="9" cy="8" r="3" stroke="currentColor" stroke-width="1.8"/><path d="M4 19a5 5 0 0 1 10 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M17 8h4M19 6v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg><i class="badge"></i></span>
          <span>Undangan</span>
        </a>
        <a href="{{ route('referral.index') }}" class="vl-quick-item">
          <span class="vl-quick-icon"><svg viewBox="0 0 24 24" fill="none"><rect x="4" y="11" width="16" height="9" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M3 8h18v3H3z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M12 8v12" stroke="currentColor" stroke-width="1.8"/><path d="M12 8C10 8 8.5 7 8.5 5.8 8.5 4.8 9.3 4 10.3 4c1.3 0 1.7 1.6 1.7 4Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg></span>
          <span>Bonus</span>
        </a>
        <a href="{{ route('team.index') }}" class="vl-quick-item">
          <span class="vl-quick-icon"><svg viewBox="0 0 24 24" fill="none"><path d="m12 4 2.2 4.5 5 .7-3.6 3.5.8 4.9L12 15.8 7.6 17.6l.8-4.9L4.8 9.2l5-.7L12 4Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg></span>
          <span>Testimoni</span>
        </a>
        <button type="button" class="vl-quick-item" id="vlQuickCs">
          <span class="vl-quick-icon"><svg viewBox="0 0 24 24" fill="none"><path d="M5 13a7 7 0 0 1 14 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><rect x="3.5" y="13" width="4" height="6.5" rx="2" stroke="currentColor" stroke-width="1.8"/><rect x="16.5" y="13" width="4" height="6.5" rx="2" stroke="currentColor" stroke-width="1.8"/></svg></span>
          <span>Layanan</span>
        </button>
      </nav>

      {{-- PRODUCTS --}}
      <section class="vl-section">
        <div class="vl-section-head">
          <div class="vl-section-title">
            <span class="bar"></span>
            <div>
              <h2>Produk Investasi AI</h2>
              <p>Pasar Investasi Velora</p>
            </div>
          </div>
          <a href="{{ route('market.index') }}" class="vl-see-all">
            Lihat Semua
            <svg viewBox="0 0 24 24" fill="none"><path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
        </div>

        <div class="vl-cats" id="vlCategoryTrack">
          @foreach(($categories ?? []) as $i => $cat)
            @php
              $catName = strtolower($cat->name ?? '');
              if(str_contains($catName, 'saham')) { $catIcon = 'chart'; }
              elseif(str_contains($catName, 'pro')) { $catIcon = 'diamond'; }
              else { $catIcon = 'cube'; }
              $catProducts = $cat->products ?? collect();
            @endphp
            <button type="button" class="vl-cat {{ $i === 0 ? 'active' : '' }}" data-category-target="vl-cat-{{ $cat->id }}">
              @if($catIcon === 'chart')
                <svg viewBox="0 0 24 24" fill="none"><path d="M4 19V5M8 17v-7M12 17V7M16 17v-4M20 17V4" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/></svg>
              @elseif($catIcon === 'diamond')
                <svg viewBox="0 0 24 24" fill="none"><path d="m12 21 8-10-4-7H8l-4 7 8 10Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
              @else
                <svg viewBox="0 0 24 24" fill="none"><path d="M12 3 20 7.4v9.2L12 21l-8-4.4V7.4L12 3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
              @endif
              {{ str_ireplace('Rubik', 'Velora', $cat->name) }}
              <span class="count">{{ count($catProducts) }}</span>
            </button>
          @endforeach
        </div>

        @forelse(($categories ?? []) as $i => $cat)
          <div class="vl-product-pane {{ $i === 0 ? 'active' : '' }}" id="vl-cat-{{ $cat->id }}">
            <div class="vl-products">
              @forelse(($cat->products ?? []) as $product)
                @php
                  $catName = strtolower($cat->name ?? '');
                  $productName = str_ireplace('Rubik', 'Velora', $product->name ?? 'Velora Asset');

                  if(str_contains($catName, 'saham')) { $tokenSymbol = 'SV'; }
                  elseif(str_contains($catName, 'pro')) { $tokenSymbol = 'VP'; }
                  else { $tokenSymbol = 'VA'; }

                  $activeInvestments = $activeInvestments ?? [];
                  $invActive = $activeInvestments[$product->id] ?? null;

                  $isOneTimeProduct = in_array((int) $cat->id, [2, 3], true);
                  $shouldLockBuyButton = $isOneTimeProduct && $invActive;

                  $vipKurang = (int) ($user->vip_level ?? 0) < (int) ($product->min_vip_level ?? 0);
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

                <article class="vl-asset-card">
                  <div class="vl-asset-top">
                    <div class="vl-token"><span>{{ $tokenSymbol }}</span></div>
                    <div class="vl-asset-headline">
                      <h3>{{ $productName }}</h3>
                      <p>
                        <svg viewBox="0 0 24 24" fill="none"><path d="M4 15l5-5 4 4 7-8" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Performa AI · 30 hari
                      </p>
                    </div>
                    <span class="vl-profit-badge">+{{ $profitPercent }}%</span>
                  </div>

                  <div class="vl-spark-wrap">
                    <svg class="vl-spark js-spark-chart" viewBox="0 0 300 70" preserveAspectRatio="none" aria-hidden="true"
                      data-seed="{{ max($seed, 1) }}" data-price="{{ (int)($product->price ?? 0) }}"
                      data-profit="{{ (int)($product->daily_profit ?? 0) }}" data-total="{{ (int)($product->total_profit ?? 0) }}">
                      <path class="spark-grid" d="M0 18 H300 M0 35 H300 M0 52 H300"></path>
                      <path class="spark-area" d=""></path>
                      <path class="spark-glow" d=""></path>
                      <path class="spark-line" d=""></path>
                      <circle class="spark-dot" cx="0" cy="0" r="3.2"></circle>
                    </svg>
                  </div>

                  <div class="vl-indicators">
                    <div class="vl-ind risk"><span>Resiko</span><div class="vl-ind-dots">@for($d=1;$d<=4;$d++)<i class="{{ $d <= $riskDots ? 'on' : '' }}"></i>@endfor</div></div>
                    <div class="vl-ind profit"><span>Profit</span><div class="vl-ind-dots">@for($d=1;$d<=4;$d++)<i class="{{ $d <= $profitDots ? 'on' : '' }}"></i>@endfor</div></div>
                    <div class="vl-ind eff"><span>Efisiensi</span><div class="vl-ind-dots">@for($d=1;$d<=4;$d++)<i class="{{ $d <= $effDots ? 'on' : '' }}"></i>@endfor</div></div>
                  </div>

                  <div class="vl-asset-detail">
                    <div class="vl-detail"><span>Investasi</span><strong>Rp {{ number_format((int)($product->price ?? 0), 0, ',', '.') }}</strong></div>
                    <div class="vl-detail"><span>Potensi</span><strong class="gold">Rp {{ number_format((int)($product->total_profit ?? 0), 0, ',', '.') }}</strong></div>
                    <div class="vl-detail"><span>Durasi</span><strong class="blue">{{ (int)($product->duration_days ?? 0) }} Hari</strong></div>
                  </div>

                  <div class="vl-asset-action">
                    @if($shouldLockBuyButton)
                      <a href="{{ route('investasi.index') }}" class="vl-buy muted">Sedang Aktif</a>
                    @else
                      <form method="POST" action="{{ url('/product/buy/'.$product->id) }}" class="js-invest-form" style="margin:0;"
                        data-product-name="{{ $productName }}"
                        data-product-price="Rp {{ number_format((int)($product->price ?? 0), 0, ',', '.') }}"
                        data-product-profit="Rp {{ number_format((int)($product->daily_profit ?? 0), 0, ',', '.') }}"
                        data-product-duration="{{ $product->duration_days }} Hari"
                        data-product-vip="{{ (int)($product->min_vip_level ?? 0) }}"
                        data-user-vip="{{ (int)($user->vip_level ?? 0) }}"
                        data-product-raw-price="{{ (int)($product->price ?? 0) }}"
                        data-user-saldo="{{ (int)($user->saldo ?? 0) }}"
                        data-vip-kurang="{{ $vipKurang ? '1' : '0' }}"
                        data-saldo-kurang="{{ $saldoKurang ? '1' : '0' }}">
                        @csrf
                        <button type="submit" class="vl-buy {{ ($vipKurang || $saldoKurang) ? 'is-blink' : '' }}">
                          <svg viewBox="0 0 24 24" fill="none"><path d="M13 2 4 14h7l-1 8 9-12h-7l1-8Z" fill="currentColor"/></svg>
                          {{ ($vipKurang || $saldoKurang) ? 'Deposit Sekarang' : 'Aktifkan' }}
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

      <div class="rb-bottom-spacer"></div>
      @include('partials.bottom-nav')
    </div>
  </main>

  {{-- CS POPUP --}}
  <div class="vl-cs-popup" id="vlCsPopup" aria-hidden="true">
    <div class="vl-cs-card">
      <div class="vl-cs-inner">
        <div class="vl-cs-top">
          <div class="vl-cs-brand">
            <div class="vl-cs-logo"><img src="{{ asset('logo.png') }}" alt="Velora"></div>
            <div><span>Official Support</span><strong>Capital Wave</strong></div>
          </div>
          <button type="button" class="vl-cs-close" id="vlCsPopupClose" aria-label="Tutup">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          </button>
        </div>
        <div class="vl-cs-badge">Resmi Hadir 2026</div>
        <h2 class="vl-cs-title">Akses CS &amp; Channel <span>Resmi Capital Wave</span></h2>
        <p class="vl-cs-desc">Gunakan kontak resmi Capital Wave untuk bantuan akun, informasi terbaru, dan update layanan agar transaksi tetap aman dan terpantau.</p>

        <div class="vl-cs-info">
          <div class="vl-cs-info-item">
            <div class="vl-cs-info-icon"><svg viewBox="0 0 24 24" fill="none"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg></div>
            <div class="vl-cs-info-text"><span>Customer Service</span><strong>@goveloracs</strong></div>
          </div>
          <div class="vl-cs-info-item">
            <div class="vl-cs-info-icon"><svg viewBox="0 0 24 24" fill="none"><path d="M21.5 4.5 2.8 11.7c-1.1.4-1.1 1.9.1 2.2l4.7 1.4 1.8 5.1c.4 1.1 1.8 1.3 2.4.3l2.5-3.8 4.8 3.5c.9.7 2.2.2 2.4-.9l2.5-13.4c.2-1.1-.9-2-2-1.6Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg></div>
            <div class="vl-cs-info-text"><span>Official Channel</span><strong>t.me/velorafinance</strong></div>
          </div>
          <div class="vl-cs-info-item">
            <div class="vl-cs-info-icon wa"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2Zm5.3 14.1c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .2-3.3-.7-2.8-1.1-4.5-4-4.7-4.2-.1-.2-1-1.4-1-2.6 0-1.3.6-1.9.9-2.1.2-.3.5-.3.7-.3h.5c.2 0 .4 0 .6.5l.8 1.9c.1.2.1.4 0 .5l-.4.6c-.1.2-.3.3-.1.6.1.3.7 1.1 1.4 1.8.9.8 1.7 1 2 1.2.2.1.4.1.5-.1l.7-.8c.2-.2.3-.2.6-.1l1.8.9c.3.1.4.2.5.3 0 .2 0 .8-.1 1.5Z"/></svg></div>
            <div class="vl-cs-info-text"><span>WhatsApp CS</span><strong>+62 8xx-xxxx-xxxx</strong></div>
          </div>
        </div>

        <div class="vl-cs-actions">
          {{-- WA_LINK: ganti 62XXXXXXXXXX dengan nomor WhatsApp valid (format 62..., tanpa 0/+) --}}
          <a href="https://wa.me/62XXXXXXXXXX?text=Halo%20Capital%20Wave%2C%20saya%20butuh%20bantuan" target="_blank" rel="noopener noreferrer" class="vl-cs-btn wa">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2Zm5.3 14.1c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .2-3.3-.7-2.8-1.1-4.5-4-4.7-4.2-.1-.2-1-1.4-1-2.6 0-1.3.6-1.9.9-2.1.2-.3.5-.3.7-.3h.5c.2 0 .4 0 .6.5l.8 1.9c.1.2.1.4 0 .5l-.4.6c-.1.2-.3.3-.1.6.1.3.7 1.1 1.4 1.8.9.8 1.7 1 2 1.2.2.1.4.1.5-.1l.7-.8c.2-.2.3-.2.6-.1l1.8.9c.3.1.4.2.5.3 0 .2 0 .8-.1 1.5Z"/></svg>
            Channel WhatsApp
          </a>
          <a href="https://t.me/goveloracs" target="_blank" rel="noopener noreferrer" class="vl-cs-btn cs">
            <svg viewBox="0 0 24 24" fill="none"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
            Hubungi CS
          </a>
          <a href="https://t.me/velorafinance" target="_blank" rel="noopener noreferrer" class="vl-cs-btn channel">
            <svg viewBox="0 0 24 24" fill="none"><path d="M21.5 4.5 2.8 11.7c-1.1.4-1.1 1.9.1 2.2l4.7 1.4 1.8 5.1c.4 1.1 1.8 1.3 2.4.3l2.5-3.8 4.8 3.5c.9.7 2.2.2 2.4-.9l2.5-13.4c.2-1.1-.9-2-2-1.6Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
            Join Channel
          </a>
        </div>

        <div class="vl-cs-foot">
          <svg viewBox="0 0 24 24" fill="none"><path d="M12 2.5 20 7v5.5c0 4.8-3.2 7.7-8 9-4.8-1.3-8-4.2-8-9V7l8-4.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="m8.5 12 2.2 2.2 4.8-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          Pastikan hanya menggunakan kontak resmi Capital Wave
        </div>
      </div>
    </div>
  </div>

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
    function buildSmoothPath(points){
      if(!points.length) return '';
      let d = `M ${points[0].x} ${points[0].y}`;
      for(let i = 1; i < points.length; i++){
        const prev = points[i - 1], curr = points[i], midX = (prev.x + curr.x) / 2;
        d += ` C ${midX} ${prev.y}, ${midX} ${curr.y}, ${curr.x} ${curr.y}`;
      }
      return d;
    }
    function buildLineChart(svg, options){
      const width = options.width, height = options.height, count = options.count || 9, seed = options.seed || 1;
      const minY = options.minY || 8, maxY = options.maxY || (height - 8), bottomY = height, rand = seededRandom(seed);
      const points = [];
      for(let i = 0; i < count; i++){
        const x = Math.round((width / (count - 1)) * i);
        const upwardTrend = (maxY - 6) - (i * ((maxY - minY) / (count + 1)));
        const wave = Math.sin((i + seed) * .9) * (options.wave || 8);
        const noise = (rand() * (options.noise || 14)) - ((options.noise || 14) / 2);
        const y = Math.max(minY, Math.min(maxY, upwardTrend + wave + noise));
        points.push({ x, y: Math.round(y) });
      }
      const path = buildSmoothPath(points), first = points[0], last = points[points.length - 1];
      const line = svg.querySelector('.spark-line'), area = svg.querySelector('.spark-area'), dot = svg.querySelector('.spark-dot');
      if(line) line.setAttribute('d', path);
      if(area) area.setAttribute('d', `${path} L ${last.x} ${bottomY} L ${first.x} ${bottomY} Z`);
      if(dot){ dot.setAttribute('cx', last.x); dot.setAttribute('cy', last.y); }
    }

    (function(){
      const charts = Array.from(document.querySelectorAll('.js-spark-chart'));
      charts.forEach((chart, index) => {
        const seed = Number(chart.dataset.seed || index + 1) + Number(chart.dataset.price || 0)
          + Number(chart.dataset.profit || 0) + Number(chart.dataset.total || 0);
        buildLineChart(chart, { width:300, height:70, count:26, seed, minY:10, maxY:60, wave:2.6, noise:15 });
      });
    })();

    (function(){
      const tabs = Array.from(document.querySelectorAll('.vl-cat'));
      const panes = Array.from(document.querySelectorAll('.vl-product-pane'));
      tabs.forEach(tab => tab.addEventListener('click', function(){
        const target = this.dataset.categoryTarget;
        tabs.forEach(t => t.classList.remove('active'));
        panes.forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        const pane = document.getElementById(target);
        if(pane) pane.classList.add('active');
      }));
    })();

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
            const name = this.dataset.productName || 'Produk Velora';
            const price = this.dataset.productPrice || '-';
            const productVip = this.dataset.productVip || '0', userVip = this.dataset.userVip || '0';
            if(vipKurang){
              openModal('VIP Belum Cukup', `Produk <b>${name}</b> membutuhkan minimal <b>VIP ${productVip}</b>.<br>Status kamu saat ini <b>VIP ${userVip}</b>.`, '{{ url('/akun') }}', 'Lihat Akun');
              return;
            }
            if(saldoKurang){
              openModal('Saldo Belum Cukup', `Saldo kamu belum cukup untuk membeli <b>${name}</b>.<br>Harga produk: <b>${price}</b>.`, '{{ url('/deposit') }}', 'Deposit');
            }
          }
        });
      });
    })();

    (function(){
      const popup = document.getElementById('vlCsPopup');
      const close = document.getElementById('vlCsPopupClose');
      const quickCs = document.getElementById('vlQuickCs');
      if(!popup || !close) return;
      function showPopup(){ popup.classList.add('show'); popup.setAttribute('aria-hidden', 'false'); }
      function hidePopup(){ popup.classList.remove('show'); popup.setAttribute('aria-hidden', 'true'); }
      window.addEventListener('load', () => setTimeout(showPopup, 500));
      close.addEventListener('click', hidePopup);
      quickCs?.addEventListener('click', showPopup);
      popup.addEventListener('click', e => { if(e.target === popup) hidePopup(); });
      document.addEventListener('keydown', e => { if(e.key === 'Escape') hidePopup(); });
    })();
  </script>
</body>
</html>
