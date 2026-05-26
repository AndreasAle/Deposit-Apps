 @include('partials.anti-inspect')
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Penarikan Saldo | Velora Finance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --wd-bg:#f6f2f8;
      --wd-bg2:#efe8f7;
      --wd-paper:#ffffff;
      --wd-paper2:#fbf8ff;
      --wd-text:#2b0b16;
      --wd-soft:#45162a;
      --wd-muted:#7b6370;
      --wd-muted2:#a894a0;
      --wd-border:rgba(43,11,22,.085);
      --wd-border2:rgba(43,11,22,.14);

      --wd-maroon:#3a0712;
      --wd-gold:#f5af2a;
      --wd-gold2:#ffd46d;
      --wd-purple:#8f57ff;
      --wd-violet:#d96bff;
      --wd-pink:#d96bff;
      --wd-green:#20b873;
      --wd-red:#e24a64;
      --wd-yellow:#f5af2a;
      --wd-blue:#8f57ff;

      --wd-gradient:linear-gradient(135deg,#f5af2a 0%,#ffd46d 25%,#d96bff 58%,#8f57ff 100%);
      --wd-gradient-soft:linear-gradient(145deg,#8f57ff 0%,#9f55ff 40%,#d96bff 72%,#f5af2a 100%);
      --wd-shadow:0 24px 58px rgba(88,43,145,.16);
      --wd-shadow-soft:0 14px 34px rgba(43,11,22,.075);
    }

    *{ box-sizing:border-box; }
    html,body{ min-height:100%; }

    body{
      margin:0;
      font-family:Inter, system-ui, -apple-system, "Segoe UI", sans-serif;
      color:var(--wd-text);
      background:
        radial-gradient(680px 360px at 50% -150px, rgba(245,175,42,.22), transparent 64%),
        radial-gradient(520px 340px at 100% 4%, rgba(217,107,255,.17), transparent 62%),
        radial-gradient(520px 330px at -12% 34%, rgba(143,87,255,.12), transparent 58%),
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
      mask-image:linear-gradient(180deg, rgba(0,0,0,.42), transparent 76%);
      -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.42), transparent 76%);
      opacity:.55;
      z-index:0;
    }

    a{ color:inherit; text-decoration:none; }
    button,input,select{ font-family:inherit; }
    button{ -webkit-tap-highlight-color:transparent; }

    .wd-page{
      width:100%;
      min-height:100vh;
      display:flex;
      justify-content:center;
      padding:14px 10px 0;
      position:relative;
      z-index:1;
    }

    .wd-phone{
      width:100%;
      max-width:430px;
      min-height:100vh;
      position:relative;
      padding:8px 4px 112px;
    }

    .wd-header{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:16px;
      padding:0 2px;
    }

    .wd-brand{
      display:flex;
      align-items:center;
      gap:12px;
      min-width:0;
    }

    .wd-logo-card{
      width:52px;
      height:52px;
      border-radius:19px;
      background:
        radial-gradient(circle at 28% 8%, rgba(255,255,255,.98), rgba(255,226,155,.78) 34%, rgba(225,188,255,.76) 92%),
        var(--wd-gradient);
      border:1px solid rgba(255,255,255,.68);
      box-shadow:
        0 16px 34px rgba(88,43,145,.13),
        0 8px 22px rgba(245,175,42,.10),
        inset 0 1px 0 rgba(255,255,255,.72);
      display:grid;
      place-items:center;
      flex:0 0 auto;
      overflow:hidden;
    }

    .wd-logo-card img{
      width:46px;
      height:46px;
      object-fit:contain;
      display:block;
    }

    .wd-title-wrap{ min-width:0; }

    .wd-title-wrap span{
      display:block;
      margin-bottom:6px;
      color:rgba(58,7,18,.58);
      font-size:10px;
      line-height:1;
      font-weight:800;
      letter-spacing:.18em;
      text-transform:uppercase;
    }

    .wd-title-wrap h1{
      margin:0;
      font-size:23px;
      line-height:1;
      font-weight:950;
      letter-spacing:-.055em;
      color:var(--wd-maroon);
      white-space:nowrap;
    }

    .wd-header-actions{
      display:flex;
      align-items:center;
      gap:9px;
      flex:0 0 auto;
    }

    .wd-header-btn{
      width:42px;
      height:42px;
      border-radius:999px;
      border:1px solid rgba(43,11,22,.08);
      background:rgba(255,255,255,.88);
      color:#5b2841;
      display:grid;
      place-items:center;
      box-shadow:0 12px 26px rgba(43,11,22,.065), inset 0 1px 0 rgba(255,255,255,.92);
      transition:.18s ease;
      backdrop-filter:blur(18px);
      -webkit-backdrop-filter:blur(18px);
    }

    .wd-header-btn:hover{
      transform:translateY(-1px);
      color:var(--wd-purple);
      border-color:rgba(143,87,255,.18);
    }

    .wd-header-btn svg{ width:20px; height:20px; }

    .wd-hero{
      position:relative;
      overflow:hidden;
      border-radius:34px;
      background:
        radial-gradient(360px 220px at 92% -12%, rgba(255,212,109,.48), transparent 58%),
        radial-gradient(300px 200px at 2% 8%, rgba(217,107,255,.34), transparent 62%),
        linear-gradient(145deg,#8f57ff 0%,#9455ff 40%,#d96bff 72%,#f5af2a 100%);
      border:1px solid rgba(255,255,255,.44);
      box-shadow:0 28px 62px rgba(143,87,255,.22), 0 18px 42px rgba(245,175,42,.10), inset 0 1px 0 rgba(255,255,255,.22);
      padding:18px;
      margin-bottom:14px;
      animation:wdFadeUp .42s ease both;
      color:#fff;
    }

    .wd-hero::before{
      content:"";
      position:absolute;
      inset:0;
      background:
        linear-gradient(135deg, rgba(255,255,255,.22), transparent 34%),
        radial-gradient(circle at 82% 26%, rgba(255,255,255,.16), transparent 28%),
        linear-gradient(180deg, transparent 0%, rgba(43,11,22,.08) 100%);
      pointer-events:none;
    }

    .wd-hero::after{
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

    .wd-hero-inner{
      position:relative;
      z-index:1;
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
    }

    .wd-hero-label{
      margin:0 0 8px;
      color:rgba(255,255,255,.74);
      font-size:12px;
      font-weight:700;
      line-height:1.1;
    }

    .wd-hero-title{
      margin:0;
      color:#fff;
      font-size:31px;
      line-height:1.02;
      letter-spacing:-.075em;
      font-weight:950;
      text-shadow:0 12px 28px rgba(43,11,22,.22);
    }

    .wd-hero-sub{
      margin-top:12px;
      display:inline-flex;
      align-items:center;
      gap:7px;
      color:#2c1200;
      font-size:11.5px;
      font-weight:850;
      padding:8px 12px;
      border-radius:999px;
      background:linear-gradient(135deg,#ffe08a 0%,#f5af2a 100%);
      border:1px solid rgba(255,255,255,.30);
      box-shadow:0 12px 22px rgba(245,175,42,.24), inset 0 1px 0 rgba(255,255,255,.38);
    }

    .wd-hero-sub span{
      color:rgba(44,18,0,.68);
      font-weight:700;
    }

    .wd-hero-pill{
      flex:0 0 auto;
      min-width:78px;
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
      font-weight:950;
      white-space:nowrap;
    }

    .wd-hero-pill svg{ width:15px; height:15px; }

    .wd-available-card{
      margin-bottom:14px;
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:10px;
    }

    .wd-available-item{
      min-height:78px;
      border-radius:22px;
      padding:13px;
      background:linear-gradient(180deg,rgba(255,255,255,.96),rgba(255,255,255,.88));
      border:1px solid rgba(43,11,22,.075);
      box-shadow:var(--wd-shadow-soft), inset 0 1px 0 rgba(255,255,255,.95);
      overflow:hidden;
      position:relative;
    }

    .wd-available-item::after{
      content:"";
      position:absolute;
      left:13px;
      right:13px;
      bottom:0;
      height:3px;
      border-radius:999px 999px 0 0;
      background:linear-gradient(90deg,#d96bff,transparent 80%);
    }

    .wd-available-item.is-main::after{ background:linear-gradient(90deg,#f5af2a,#d96bff 80%); }

    .wd-available-item span{
      display:block;
      color:var(--wd-muted);
      font-size:10px;
      font-weight:800;
      margin-bottom:9px;
    }

    .wd-available-item strong{
      display:block;
      color:var(--wd-maroon);
      font-size:14px;
      font-weight:950;
      letter-spacing:-.03em;
    }

    .wd-available-item.is-main strong{ color:var(--wd-purple); }

    .wd-card{
      position:relative;
      overflow:visible;
      border-radius:0;
      background:transparent;
      border:0;
      box-shadow:none;
      padding:0;
      animation:wdFadeUp .42s ease both;
    }

    .wd-fieldset{
      position:relative;
      margin-bottom:14px;
      border:1px solid rgba(43,11,22,.075);
      border-radius:28px;
      background:linear-gradient(180deg,rgba(255,255,255,.96),rgba(255,255,255,.88));
      padding:18px 14px 14px;
      box-shadow:var(--wd-shadow-soft), inset 0 1px 0 rgba(255,255,255,.94);
      overflow:hidden;
    }

    .wd-fieldset::before{
      content:"";
      position:absolute;
      inset:0;
      pointer-events:none;
      background:
        radial-gradient(220px 120px at 100% 0%, rgba(217,107,255,.11), transparent 62%),
        radial-gradient(200px 110px at 0% 100%, rgba(245,175,42,.09), transparent 60%);
    }

    .wd-fieldset > *{ position:relative; z-index:1; }

    .wd-fieldset-label{
      position:static;
      display:inline-flex;
      min-height:28px;
      align-items:center;
      margin:0 0 12px;
      padding:0 11px;
      border-radius:999px;
      background:#fbf8ff;
      color:var(--wd-maroon);
      border:1px solid rgba(43,11,22,.07);
      font-size:11px;
      font-weight:900;
      line-height:1;
      box-shadow:inset 0 1px 0 rgba(255,255,255,.9);
    }

    .wd-loader{
      color:var(--wd-muted);
      font-size:12px;
      font-weight:800;
      text-align:center;
      padding:14px;
    }

    .wd-bank-card{
      border:1px solid rgba(43,11,22,.07);
      border-radius:22px;
      overflow:hidden;
      background:rgba(255,255,255,.68);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.85);
    }

    .wd-bank-top{
      padding:13px;
      display:flex;
      gap:12px;
      align-items:flex-start;
    }

    .wd-bank-logo{
      width:52px;
      height:52px;
      min-width:52px;
      flex:0 0 52px;
      border-radius:18px;
      display:flex;
      align-items:center;
      justify-content:center;
      background:#fff;
      border:1px solid rgba(43,11,22,.08);
      overflow:hidden;
      box-shadow:0 12px 24px rgba(43,11,22,.08), inset 0 1px 0 rgba(255,255,255,.9);
    }

    .wd-bank-logo-img{
      display:block;
      width:42px;
      height:42px;
      object-fit:contain;
    }

    .wd-bank-logo-fallback{
      display:none;
      width:100%;
      height:100%;
      align-items:center;
      justify-content:center;
      color:var(--wd-maroon);
      font-size:11px;
      font-weight:950;
      line-height:1;
    }

    .wd-bank-label{
      color:var(--wd-muted);
      font-size:10px;
      font-weight:850;
      text-transform:uppercase;
      letter-spacing:.08em;
    }

    .wd-bank-name{
      margin-top:4px;
      color:var(--wd-maroon);
      font-size:16px;
      font-weight:950;
      letter-spacing:-.03em;
    }

    .wd-bank-bottom{
      display:grid;
      grid-template-columns:1fr 1fr;
      border-top:1px solid rgba(43,11,22,.07);
      background:#fbf8ff;
    }

    .wd-bank-info{
      padding:12px 13px;
      min-width:0;
    }

    .wd-bank-info + .wd-bank-info{
      border-left:1px solid rgba(43,11,22,.07);
    }

    .wd-bank-info span{
      display:block;
      color:var(--wd-muted);
      font-size:10.5px;
      font-weight:750;
      margin-bottom:5px;
    }

    .wd-bank-info strong{
      display:block;
      color:var(--wd-maroon);
      font-size:14px;
      font-weight:950;
      letter-spacing:-.02em;
      overflow:hidden;
      text-overflow:ellipsis;
      white-space:nowrap;
    }

    .wd-bank-change{
      padding:10px 13px 13px;
      border-top:1px solid rgba(43,11,22,.07);
      background:#fff;
      text-align:center;
    }

    .wd-bank-change a{
      min-height:36px;
      padding:0 13px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      color:#2c1200;
      background:linear-gradient(135deg,#ffe08a,#f5af2a);
      text-decoration:none;
      font-size:11.5px;
      font-weight:950;
      box-shadow:0 12px 24px rgba(245,175,42,.16);
    }

    .wd-bank-empty{
      padding:16px 14px;
      border-radius:20px;
      border:1px dashed rgba(143,87,255,.22);
      background:#fbf8ff;
      color:var(--wd-muted);
      text-align:center;
    }

    .wd-bank-empty-title{
      color:var(--wd-maroon);
      font-size:13px;
      font-weight:950;
      letter-spacing:-.02em;
    }

    .wd-bank-empty-desc{
      margin:7px auto 12px;
      max-width:280px;
      color:var(--wd-muted);
      font-size:11.5px;
      font-weight:650;
      line-height:1.45;
    }

    .wd-add-bank-link{
      width:100%;
      min-height:42px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      color:#2c1200 !important;
      text-decoration:none !important;
      background:linear-gradient(135deg,#ffd46d,#d96bff 70%,#8f57ff);
      box-shadow:0 14px 28px rgba(143,87,255,.18), inset 0 1px 0 rgba(255,255,255,.32);
      font-size:12.5px;
      font-weight:950;
    }

    .wd-amount-box{
      min-height:96px;
      display:flex;
      align-items:center;
      gap:10px;
      padding:10px 12px;
      border-radius:24px;
      background:#fff;
      border:1px solid rgba(43,11,22,.07);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.9);
    }

    .wd-rp{
      color:var(--wd-purple);
      font-size:31px;
      line-height:1;
      font-weight:700;
      letter-spacing:-.055em;
      flex:0 0 auto;
    }

    .wd-amount-input{
      width:100%;
      min-width:0;
      border:0;
      outline:0;
      background:transparent;
      color:var(--wd-maroon);
      font-size:31px;
      line-height:1;
      font-weight:800;
      letter-spacing:-.055em;
      padding:0;
    }

    .wd-amount-input::placeholder{ color:rgba(43,11,22,.28); }

    .wd-clear{
      width:34px;
      height:34px;
      border-radius:999px;
      border:1px solid rgba(43,11,22,.08);
      background:#fbf8ff;
      color:#7b6370;
      display:grid;
      place-items:center;
      cursor:pointer;
      flex:0 0 auto;
    }

    .wd-clear svg{ width:18px; }

    .wd-presets{
      display:grid;
      grid-template-columns:repeat(3,1fr);
      gap:8px;
      margin:8px 0 14px;
    }

    .wd-preset{
      border:1px solid rgba(43,11,22,.075);
      background:rgba(255,255,255,.92);
      color:var(--wd-maroon);
      min-height:40px;
      padding:0 8px;
      border-radius:16px;
      font-size:11.2px;
      font-weight:900;
      cursor:pointer;
      box-shadow:0 10px 22px rgba(43,11,22,.055), inset 0 1px 0 rgba(255,255,255,.9);
      transition:.18s ease;
    }

    .wd-preset:hover{ transform:translateY(-1px); }

    .wd-preset.is-active{
      color:#2c1200;
      background:linear-gradient(135deg,#ffd46d,#d96bff 68%,#8f57ff);
      border-color:rgba(255,255,255,.60);
      box-shadow:0 14px 28px rgba(143,87,255,.18);
    }

    .wd-limit{
      display:grid;
      gap:8px;
      margin:0 0 14px;
      border-radius:22px;
      padding:12px;
      background:rgba(255,255,255,.84);
      border:1px solid rgba(43,11,22,.07);
      box-shadow:var(--wd-shadow-soft);
    }

    .wd-limit-row{
      display:flex;
      justify-content:space-between;
      gap:12px;
      color:var(--wd-muted);
      font-size:11.5px;
      font-weight:700;
    }

    .wd-limit-row strong{
      color:var(--wd-maroon);
      font-weight:950;
      text-align:right;
    }

    .wd-received{
      border-radius:24px;
      background:linear-gradient(180deg,rgba(255,255,255,.96),rgba(255,255,255,.88));
      border:1px solid rgba(43,11,22,.075);
      padding:15px;
      margin-bottom:12px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      box-shadow:var(--wd-shadow-soft), inset 0 1px 0 rgba(255,255,255,.94);
    }

    .wd-received-left{ display:grid; gap:4px; }

    .wd-received-label{
      color:var(--wd-maroon);
      font-size:12px;
      font-weight:900;
    }

    .wd-received-note{
      display:block;
      color:var(--wd-muted);
      font-size:10.5px;
      font-weight:700;
      line-height:1.35;
    }

    .wd-received-right{ text-align:right; }

    .wd-received-amount{
      display:block;
      color:var(--wd-purple);
      font-size:22px;
      font-weight:950;
      letter-spacing:-.04em;
    }

    .wd-tax{
      display:block;
      color:var(--wd-red);
      font-size:11px;
      font-weight:750;
      margin-top:4px;
    }

    .wd-error{
      min-height:20px;
      text-align:center;
      color:var(--wd-red);
      font-size:11.5px;
      font-weight:850;
      margin-bottom:4px;
    }

    .wd-error:empty{ display:none; }

    .wd-history{ margin-top:18px; }

    .wd-history-head{
      display:flex;
      justify-content:space-between;
      align-items:flex-end;
      margin-bottom:10px;
      padding:0 2px;
    }

    .wd-history-title{
      margin:0;
      color:var(--wd-maroon);
      font-size:16px;
      font-weight:900;
      letter-spacing:-.025em;
    }

    .wd-history-sub{
      color:var(--wd-muted);
      font-size:11px;
      font-weight:750;
    }

    .wd-history-list{
      display:flex;
      flex-direction:column;
      gap:8px;
    }

    .wd-history-item{
      border-radius:20px;
      border:1px solid rgba(43,11,22,.075);
      background:#fff;
      padding:12px;
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
      box-shadow:var(--wd-shadow-soft);
    }

    .wd-history-amount{
      color:var(--wd-maroon);
      font-size:13px;
      font-weight:950;
    }

    .wd-history-date{
      margin-top:4px;
      color:var(--wd-muted);
      font-size:10.5px;
      font-weight:600;
      line-height:1.35;
    }

    .wd-status{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      min-height:24px;
      padding:0 9px;
      border-radius:999px;
      font-size:10px;
      font-weight:950;
      text-transform:uppercase;
      white-space:nowrap;
    }

    .wd-status.is-paid{ color:#0f5132; background:#e8fff4; }
    .wd-status.is-pending{ color:#a56d00; background:#fff7dd; border:1px solid rgba(245,175,42,.22); }
    .wd-status.is-rejected{ color:#c52c48; background:#fff1f3; border:1px solid rgba(226,74,100,.18); }
    .wd-status.is-processing{ color:#5d2bd6; background:#f4edff; border:1px solid rgba(143,87,255,.18); }

    .wd-empty{
      border-radius:20px;
      border:1px dashed rgba(43,11,22,.14);
      background:#fff;
      padding:14px;
      color:var(--wd-muted);
      text-align:center;
      font-size:12px;
      font-weight:750;
      box-shadow:var(--wd-shadow-soft);
    }

    .wd-cancel{
      margin-top:8px;
      border:0;
      border-radius:999px;
      min-height:28px;
      padding:0 10px;
      color:#c52c48;
      background:#fff1f3;
      border:1px solid rgba(226,74,100,.18);
      font-size:10.5px;
      font-weight:900;
      cursor:pointer;
    }

    .wd-bottom{
      position:fixed;
      left:50%;
      bottom:0;
      transform:translateX(-50%);
      z-index:50;
      width:min(100%,430px);
      padding:12px 14px calc(14px + env(safe-area-inset-bottom));
      background:linear-gradient(180deg, rgba(247,242,250,0), rgba(247,242,250,.92) 28%, rgba(247,242,250,.98));
      pointer-events:none;
    }

    .wd-submit{
      width:100%;
      min-height:52px;
      border:0;
      border-radius:999px;
      color:#2c1200;
      background:linear-gradient(135deg,#ffd46d,#d96bff 68%,#8f57ff);
      box-shadow:0 18px 38px rgba(143,87,255,.22), inset 0 1px 0 rgba(255,255,255,.32);
      font-size:14px;
      font-weight:950;
      cursor:pointer;
      pointer-events:auto;
    }

    .wd-submit[disabled]{
      opacity:.55;
      cursor:not-allowed;
      filter:saturate(.6);
    }

    .wd-toast{
      position:fixed;
      left:50%;
      bottom:92px;
      z-index:1200;
      transform:translateX(-50%) translateY(12px);
      opacity:0;
      pointer-events:none;
      min-height:44px;
      max-width:calc(100% - 24px);
      padding:0 15px;
      border-radius:999px;
      display:flex;
      align-items:center;
      justify-content:center;
      color:#2c1200;
      background:linear-gradient(135deg,#ffd46d,#d96bff 68%,#8f57ff);
      box-shadow:0 18px 42px rgba(43,11,22,.18);
      font-size:12px;
      font-weight:850;
      transition:.22s ease;
      white-space:nowrap;
    }

    .wd-toast.is-error{
      color:#fff;
      background:linear-gradient(135deg,#e24a64,#f5af2a);
    }

    .wd-toast.show{
      opacity:1;
      transform:translateX(-50%) translateY(0);
    }

    .wd-hero-balance,
    .wd-hero-balance-caption{ display:none; }

    @keyframes wdFadeUp{
      from{ opacity:0; transform:translateY(10px); }
      to{ opacity:1; transform:translateY(0); }
    }

    @media(min-width:768px){
      .wd-page{ padding:22px 0; }
      .wd-phone{ min-height:calc(100vh - 44px); border-radius:30px; overflow:hidden; }
      .wd-bottom{ bottom:22px; border-radius:0 0 30px 30px; }
    }

    @media(max-width:370px){
      .wd-page{ padding-left:8px; padding-right:8px; }
      .wd-phone{ padding-left:2px; padding-right:2px; }
      .wd-logo-card{ width:45px; height:45px; border-radius:16px; }
      .wd-logo-card img{ width:39px; height:39px; }
      .wd-title-wrap h1{ font-size:21px; }
      .wd-hero{ padding:16px; border-radius:30px; }
      .wd-hero-title{ font-size:27px; }
      .wd-hero-pill{ min-width:70px; height:37px; font-size:11px; }
      .wd-available-card{ gap:8px; }
      .wd-available-item{ min-height:74px; padding:11px; border-radius:20px; }
      .wd-available-item strong{ font-size:12.3px; }
      .wd-rp,.wd-amount-input{ font-size:28px; }
      .wd-presets{ grid-template-columns:1fr 1fr; }
      .wd-preset{ font-size:11px; }
      .wd-bank-bottom{ grid-template-columns:1fr; }
      .wd-bank-info + .wd-bank-info{ border-left:0; border-top:1px solid rgba(43,11,22,.07); }
      .wd-received{ align-items:flex-start; flex-direction:column; }
      .wd-received-right{ text-align:left; }
    }
  </style>
</head>

@php
  $user = auth()->user();

  $saldoPenarikan = (int) data_get($user, 'saldo_penarikan', 0);
  $saldoHold = (int) data_get($user, 'saldo_hold', 0);
@endphp


<body>
  <main class="wd-page">
    <div class="wd-phone">

      {{-- HEADER --}}
      <header class="wd-header">
        <div class="wd-brand">
          <div class="wd-logo-card">
            <img src="{{ asset('logo.png') }}" alt="Velora Finance">
          </div>

          <div class="wd-title-wrap">
            <span>Velora Finance</span>
            <h1>Penarikan Saldo</h1>
          </div>
        </div>

        <div class="wd-header-actions">
          <a href="{{ url('/dashboard') }}" class="wd-header-btn" aria-label="Kembali ke Dashboard">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M15 18 9 12l6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </a>

          <a href="{{ url('/ui/payout-accounts') }}" class="wd-header-btn" aria-label="Kelola Rekening">
            <svg viewBox="0 0 24 24" fill="none">
              <rect x="3" y="5" width="18" height="14" rx="3" stroke="currentColor" stroke-width="2.2"/>
              <path d="M3 9h18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              <path d="M7 14h4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
            </svg>
          </a>
        </div>
      </header>

      {{-- HERO --}}
      <section class="wd-hero">
        <div class="wd-hero-inner">
          <div>
<p class="wd-hero-label">Saldo siap ditarik</p>
<h2 class="wd-hero-title">Withdraw</h2>


<div class="wd-hero-sub">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                <path d="M4 15l5-5 4 4 7-8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M15 6h5v5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              Minimal Rp 50.000
             <span>Biaya Gateway</span>
            </div>
          </div>

          <div class="wd-hero-pill">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
            </svg>
            Aman
          </div>
        </div>
      </section>

      <section class="wd-available-card">
  <div class="wd-available-item is-main">
    <span>Bisa Ditarik</span>
    <strong>Rp {{ number_format($saldoPenarikan, 0, ',', '.') }}</strong>
  </div>

  <div class="wd-available-item">
    <span>Sedang Diproses</span>
    <strong>Rp {{ number_format($saldoHold, 0, ',', '.') }}</strong>
  </div>
</section>

      {{-- FORM CARD --}}
      <section class="wd-card">
        <form id="wdForm" novalidate>
          <input type="hidden" id="payout" value="">
          <input type="hidden" id="amount" value="">

          <section class="wd-fieldset">
            <span class="wd-fieldset-label">Akun Bank Terpilih</span>
            <div id="selectedBank">
              <div class="wd-loader">Mengambil akun bank...</div>
            </div>
          </section>

          <section class="wd-fieldset">
            <span class="wd-fieldset-label">Masukkan Jumlah Penarikan</span>

            <div class="wd-amount-box">
              <span class="wd-rp">Rp</span>

              <input
                type="text"
                inputmode="numeric"
                id="amountDisplay"
                class="wd-amount-input"
                placeholder="0"
                autocomplete="off"
                aria-label="Jumlah penarikan"
              >

              <button type="button" class="wd-clear" id="clearAmount" aria-label="Hapus nominal">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M18 6 6 18" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"/>
                  <path d="M6 6 18 18" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"/>
                </svg>
              </button>
            </div>
          </section>

          <div class="wd-presets">
            <button type="button" class="wd-preset" data-amount="50000">Rp 50.000</button>
            <button type="button" class="wd-preset" data-amount="100000">Rp 100.000</button>
            <button type="button" class="wd-preset" data-amount="500000">Rp 500.000</button>
            <button type="button" class="wd-preset" data-amount="1000000">Rp 1.000.000</button>
            <button type="button" class="wd-preset" data-amount="5000000">Rp 5.000.000</button>
            <button type="button" class="wd-preset" data-amount="10000000">Rp 10.000.000</button>
          </div>

          <div class="wd-limit">
            <div class="wd-limit-row">
              <span>Minimal penarikan:</span>
              <strong>Rp 50.000</strong>
            </div>

            <div class="wd-limit-row">
              <span>Maksimal penarikan:</span>
              <strong>Rp 50.000.000</strong>
            </div>
          </div>

<div class="wd-received">
  <div class="wd-received-left">
    <span class="wd-received-label">Jumlah yang Diterima</span>
    <span class="wd-received-note">Estimasi setelah biaya gateway</span>
  </div>

  <div class="wd-received-right">
    <span class="wd-received-amount" id="receivedAmount">Rp 0</span>
    <span class="wd-tax" id="gatewayFeeText">Biaya gateway Rp 0</span>
  </div>
</div>

          <div class="wd-error" id="amountError"></div>
        </form>
      </section>

      {{-- HISTORY --}}
     
    </div>
  </main>

  <div class="wd-bottom">
    <button class="wd-submit" type="submit" form="wdForm" id="btnSubmitWd">
      Lanjutkan Penarikan
    </button>
  </div>

  <div id="wdToast" class="wd-toast" role="status" aria-live="polite">
    <span id="wdToastText">Berhasil</span>
  </div>

  <script>
    const wdForm = document.getElementById('wdForm');
    const payoutHidden = document.getElementById('payout');
    const selectedBank = document.getElementById('selectedBank');
    const historyEl = document.getElementById('history');
    const amountHidden = document.getElementById('amount');
    const amountDisplay = document.getElementById('amountDisplay');
    const clearBtn = document.getElementById('clearAmount');
    const errorEl = document.getElementById('amountError');
    const receivedEl = document.getElementById('receivedAmount');
    const gatewayFeeText = document.getElementById('gatewayFeeText');
    const btnSubmit = document.getElementById('btnSubmitWd');
    const presets = Array.from(document.querySelectorAll('.wd-preset'));
    const toastEl = document.getElementById('wdToast');
    const toastText = document.getElementById('wdToastText');

 const MIN = 50000;
const MAX = 50000000;
const ESTIMATED_GATEWAY_FEE = 0;
const AVAILABLE_WITHDRAW = {{ (int) $saldoPenarikan }};

    function csrfToken(){
      return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    async function api(url, options = {}){
      const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken(),
        ...(options.headers || {})
      };

      const res = await fetch(url, {
        credentials: 'same-origin',
        ...options,
        headers
      });

      let data = null;

      try {
        data = await res.json();
      } catch (error) {
        data = {};
      }

      if(!res.ok){
        const message =
          data?.message ||
          data?.error ||
          'Terjadi kesalahan saat memproses data.';

        throw new Error(message);
      }

      return data;
    }

    function toast(message, type = 'success'){
      if(!toastEl || !toastText) return;

      toastText.textContent = message;
      toastEl.classList.toggle('is-error', type === 'err');
      toastEl.classList.add('show');

      clearTimeout(window.__wdToastTimer);
      window.__wdToastTimer = setTimeout(function(){
        toastEl.classList.remove('show');
      }, 1800);
    }

    function rupiah(n){
      try {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(n || 0));
      } catch {
        return 'Rp ' + String(n || 0);
      }
    }

    function escapeHtml(str){
      return String(str ?? '')
        .replaceAll('&','&amp;')
        .replaceAll('<','&lt;')
        .replaceAll('>','&gt;')
        .replaceAll('"','&quot;')
        .replaceAll("'","&#039;");
    }

    function onlyNumber(v){
      return String(v || '').replace(/[^\d]/g,'');
    }

    function formatNumber(v){
      const n = Number(v || 0);
      return n ? n.toLocaleString('id-ID') : '';
    }

    function maskNumber(n){
      const raw = String(n || '');

      if(raw.length <= 6) return raw;

      return raw.slice(0,3) + '*'.repeat(Math.max(raw.length - 6, 4)) + raw.slice(-3);
    }

    function maskName(name){
      const raw = String(name || '');

      if(raw.length <= 1) return raw;

      return raw[0] + '*'.repeat(Math.min(raw.length - 1, 4));
    }

    function providerInitial(p){
      return String(p || 'RB').trim().slice(0,3).toUpperCase();
    }

    function providerLogo(provider){
  const key = String(provider || '').trim().toUpperCase();

  const logos = {
    BCA: '/assets/payment-methods/bca.png',
    BRI: '/assets/payment-methods/bri.png',
    BNI: '/assets/payment-methods/bni.png',
    MANDIRI: '/assets/payment-methods/mandiri.png',
    DANA: '/assets/payment-methods/dana.png',
    GOPAY: '/assets/payment-methods/gopay.png',
    OVO: '/assets/payment-methods/ovo.png',
    DOKU: '/assets/payment-methods/doku.png',
    LINKAJA: '/assets/payment-methods/linkaja.png',
    SHOPEEPAY: '/assets/payment-methods/shopeepay.png',
    QRIS: '/assets/payment-methods/qris.png'
  };

  return logos[key] || '';
}

function providerDisplayName(provider){
  const key = String(provider || '').trim().toUpperCase();

  const names = {
    BCA: 'BCA',
    BRI: 'BRI',
    BNI: 'BNI',
    MANDIRI: 'Mandiri',
    DANA: 'DANA',
    GOPAY: 'GoPay',
    OVO: 'OVO',
    DOKU: 'DOKU',
    LINKAJA: 'LinkAja',
    SHOPEEPAY: 'ShopeePay',
    QRIS: 'QRIS'
  };

  return names[key] || provider || 'Rekening';
}

function statusBadge(s){
  const status = String(s || '').toUpperCase();

  if(status === 'PAID'){
    return '<span class="wd-status is-paid">Berhasil</span>';
  }

  if(status === 'PROCESSING'){
    return '<span class="wd-status is-processing">Diproses</span>';
  }

  if(status === 'APPROVED'){
    return '<span class="wd-status is-processing">Disetujui</span>';
  }

  if(status === 'FAILED'){
    return '<span class="wd-status is-rejected">Gagal</span>';
  }

  if(status === 'REJECTED'){
    return '<span class="wd-status is-rejected">Ditolak</span>';
  }

  if(status === 'CANCELLED'){
    return '<span class="wd-status is-rejected">Dibatalkan</span>';
  }

  return '<span class="wd-status is-pending">Menunggu</span>';
}
    function renderSelectedBank(row){
      if(!row){
        selectedBank.innerHTML = `
          <div class="wd-bank-empty">
            <div class="wd-bank-empty-title">Belum ada akun bank</div>

            <div class="wd-bank-empty-desc">
              Tambahkan akun bank atau e-wallet terlebih dahulu agar penarikan saldo bisa diproses.
            </div>

            <a class="wd-add-bank-link" href="/ui/payout-accounts">
              + Tambahkan Akun Bank
            </a>
          </div>
        `;

        payoutHidden.value = '';
        return;
      }

      payoutHidden.value = row.id;

const providerName = providerDisplayName(row.provider);
const logo = providerLogo(row.provider);

selectedBank.innerHTML = `
  <div class="wd-bank-card">
    <div class="wd-bank-top">
      <div class="wd-bank-logo">
        ${
          logo
            ? `<img src="${escapeHtml(logo)}" alt="${escapeHtml(providerName)}" class="wd-bank-logo-img" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">`
            : ''
        }
        <span class="wd-bank-logo-fallback">${escapeHtml(providerInitial(providerName))}</span>
      </div>

      <div>
        <div class="wd-bank-label">Nama Bank</div>
        <div class="wd-bank-name">${escapeHtml(providerName)}</div>
      </div>
    </div>

          <div class="wd-bank-bottom">
            <div class="wd-bank-info">
              <span>Nomor Rekening</span>
              <strong>${escapeHtml(maskNumber(row.account_number))}</strong>
            </div>

            <div class="wd-bank-info">
              <span>Nama Akun</span>
              <strong>${escapeHtml(maskName(row.account_name))}</strong>
            </div>
          </div>

          <div class="wd-bank-change">
            <a href="/ui/payout-accounts">Kelola / ganti akun bank</a>
          </div>
        </div>
      `;
    }

    async function loadPayoutAccounts(){
      const res = await api('/payout-accounts');
      const rows = res?.data || [];

      if(!rows.length){
        renderSelectedBank(null);
        return;
      }

      const sorted = [...rows].sort(function(a,b){
        return (b.is_default ? 1 : 0) - (a.is_default ? 1 : 0);
      });

      renderSelectedBank(sorted[0]);
    }

async function loadWithdrawals(){
  if(!historyEl) return;

  historyEl.innerHTML = '<div class="wd-empty">Mengambil data...</div>';

      const res = await api('/withdrawals');
      const rows = res?.data || [];

      if(!rows.length){
        historyEl.innerHTML = '<div class="wd-empty">Belum ada riwayat penarikan.</div>';
        return;
      }

      historyEl.innerHTML = rows.map(function(r){
        const created = r.created_at
          ? new Date(r.created_at).toLocaleString('id-ID')
          : '-';

        const cancel = String(r.status || '').toUpperCase() === 'PENDING'
          ? `<button class="wd-cancel" type="button" onclick="cancelWd(${Number(r.id)})">Batalkan</button>`
          : '';

        return `
          <div class="wd-history-item">
            <div>
              <div class="wd-history-amount">${rupiah(r.amount)}</div>
              <div class="wd-history-date">${escapeHtml(created)}</div>
            </div>

            <div style="text-align:right">
              ${statusBadge(r.status)}
              ${cancel}
            </div>
          </div>
        `;
      }).join('');
    }

    function setAmount(v){
      const n = Number(onlyNumber(v) || 0);

      amountHidden.value = n ? String(n) : '';
      amountDisplay.value = n ? formatNumber(n) : '';

      presets.forEach(function(btn){
        btn.classList.toggle('is-active', Number(btn.dataset.amount) === n);
      });

      updateReceived();
      validate(false);
    }

function updateReceived(){
  const n = Number(amountHidden.value || 0);

  receivedEl.textContent = rupiah(n);

  const taxEl = document.querySelector('.wd-tax');
  if(taxEl){
    taxEl.textContent = n > 0
      ? 'Biaya gateway akan dihitung setelah diproses'
      : 'Biaya gateway mengikuti response JayaPay';
  }
}

function validate(show = true){
  const n = Number(amountHidden.value || 0);
  let msg = '';

  if(!payoutHidden.value){
    msg = 'Tambahkan akun bank terlebih dahulu lewat tombol di atas';
  }else if(AVAILABLE_WITHDRAW < MIN){
    msg = 'Saldo penarikan belum cukup. Minimal saldo siap ditarik Rp 50.000';
  }else if(!n){
    msg = 'Masukkan jumlah penarikan';
  }else if(n < MIN){
    msg = 'Minimal penarikan Rp 50.000';
  }else if(n > MAX){
    msg = 'Maksimal penarikan Rp 50.000.000';
  }else if(n > AVAILABLE_WITHDRAW){
    msg = 'Nominal melebihi saldo siap ditarik. Saldo tersedia ' + rupiah(AVAILABLE_WITHDRAW);
  }

  if(errorEl){
    errorEl.textContent = show ? msg : '';
  }

  btnSubmit.disabled = Boolean(msg);

  return !msg;
}

    amountDisplay.addEventListener('input', function(){
      setAmount(this.value);
    });

    amountDisplay.addEventListener('blur', function(){
      validate(true);
    });

    clearBtn.addEventListener('click', function(){
      setAmount('');
      amountDisplay.focus();
      validate(true);
    });

    presets.forEach(function(btn){
      btn.addEventListener('click', function(){
        setAmount(btn.dataset.amount);
        validate(false);
      });
    });

    wdForm.addEventListener('submit', async function(e){
      e.preventDefault();

      if(!validate(true)) return;

      const old = btnSubmit.textContent;
      btnSubmit.textContent = 'Memproses...';
      btnSubmit.disabled = true;

      try{
        await api('/withdrawals', {
          method:'POST',
          body:JSON.stringify({
            amount:Number(amountHidden.value),
            user_payout_account_id:Number(payoutHidden.value)
          })
        });

   toast('Withdraw dibuat dan sedang diproses gateway');
        setAmount('');
        await loadWithdrawals();
      }catch(error){
        toast(error.message, 'err');
      }finally{
        btnSubmit.textContent = old;
        validate(false);
      }
    });

    window.cancelWd = async function(id){
      if(!confirm('Batalkan request withdraw ini?')) return;

      try{
        await api(`/withdrawals/${id}/cancel`, {
          method:'POST'
        });

        toast('Withdraw dibatalkan');
        await loadWithdrawals();
      }catch(error){
        toast(error.message, 'err');
      }
    };

    Promise
      .all([
        loadPayoutAccounts(),
        loadWithdrawals()
      ])
      .then(function(){
        validate(false);
      })
      .catch(function(error){
        toast(error.message, 'err');
      });
  </script>
</body>
</html>