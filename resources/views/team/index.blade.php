@php
  $user = auth()->user();

  function rbInitials($name){
    $name = trim((string) $name);
    if($name === '') return 'U';

    $parts = preg_split('/\s+/', $name);
    $first = mb_substr($parts[0] ?? 'U', 0, 1);
    $second = mb_substr($parts[1] ?? '', 0, 1);

    return mb_strtoupper($first.$second);
  }

  function rbTimeLabel($date){
    if(!$date) return '-';
    return $date->diffForHumans();
  }
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Forum | Rubik Company</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --rb-bg:#030F0F;
      --rb-bg2:#061817;
      --rb-text:#f7fffb;
      --rb-muted:#9bb9ad;
      --rb-muted2:#6f9084;
      --rb-neon:#00DF82;
      --rb-neon2:#58ffad;
      --rb-primary:#03624C;
      --rb-red:#ff4f6d;
      --rb-shadow-soft:0 16px 34px rgba(0,0,0,.24);
    }

    *{
      box-sizing:border-box;
    }

    html{
      scroll-behavior:smooth;
    }

    html,
    body{
      min-height:100%;
    }

    body{
      margin:0;
      font-family:Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      color:var(--rb-text);
      background:
        radial-gradient(760px 420px at 14% -2%, rgba(0,223,130,.20), transparent 58%),
        radial-gradient(620px 360px at 88% 12%, rgba(3,98,76,.42), transparent 62%),
        radial-gradient(560px 320px at 50% 100%, rgba(0,223,130,.10), transparent 62%),
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
        linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.018) 1px, transparent 1px);
      background-size:38px 38px;
      mask-image:linear-gradient(180deg, rgba(0,0,0,.65), transparent 76%);
      -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.65), transparent 76%);
      opacity:.45;
      z-index:0;
    }

    a{
      color:inherit;
      text-decoration:none;
    }

    button,
    input,
    textarea{
      font-family:inherit;
    }

    body.rb-compose-lock{
      overflow:hidden;
    }

    .rb-page{
      width:100%;
      min-height:100vh;
      display:flex;
      justify-content:center;
      padding:14px 10px 0;
      position:relative;
      z-index:1;
    }

    .rb-phone{
      width:100%;
      max-width:430px;
      min-height:100vh;
      position:relative;
      padding:8px 4px 0;
    }

    /* =========================
       HEADER
    ========================= */
    .rb-topbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
      padding:0 2px;
    }

    .rb-brand{
      display:flex;
      align-items:center;
      gap:10px;
      min-width:0;
    }

    .rb-logo-card{
      width:48px;
      height:48px;
      border-radius:15px;
      background:
        radial-gradient(circle at 30% 18%, rgba(255,255,255,.90), transparent 34%),
        linear-gradient(180deg, rgba(235,255,247,.94), rgba(202,255,232,.90));
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

    .rb-logo-card img{
      width:40px;
      height:40px;
      object-fit:contain;
      display:block;
    }

    .rb-title-wrap{
      min-width:0;
    }

    .rb-title-wrap h1{
      margin:0;
      font-size:22px;
      line-height:1;
      font-weight:950;
      letter-spacing:-.04em;
      color:#ffffff;
      text-transform:uppercase;
      white-space:nowrap;
      text-shadow:0 10px 28px rgba(0,0,0,.28);
    }

    .rb-title-wrap p{
      margin:5px 0 0;
      font-size:11px;
      font-weight:750;
      color:rgba(214,255,240,.70);
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width:220px;
    }

    .rb-header-actions{
      display:flex;
      align-items:center;
      gap:9px;
      flex:0 0 auto;
    }

    .rb-header-btn{
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
      cursor:pointer;
      transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    }

    .rb-header-btn:hover{
      transform:translateY(-1px);
      border-color:rgba(0,223,130,.28);
      box-shadow:
        0 16px 34px rgba(0,0,0,.38),
        0 0 30px rgba(0,223,130,.10);
    }

    .rb-header-btn svg{
      width:20px;
      height:20px;
      display:block;
    }

    .rb-notif-dot{
      position:absolute;
      right:9px;
      top:8px;
      width:8px;
      height:8px;
      border-radius:999px;
      background:var(--rb-neon);
      border:2px solid #061714;
      box-shadow:
        0 0 0 2px rgba(0,223,130,.15),
        0 0 18px rgba(0,223,130,.55);
    }

    /* =========================
       AVATAR
    ========================= */
    .rb-avatar{
      width:42px;
      height:42px;
      border-radius:999px;
      flex:0 0 auto;
      display:grid;
      place-items:center;
      color:#03110c;
      font-size:13px;
      font-weight:950;
      letter-spacing:-.03em;
      background:
        radial-gradient(circle at 30% 15%, rgba(255,255,255,.55), transparent 34%),
        linear-gradient(135deg, #00DF82 0%, #8cff2f 100%);
      box-shadow:
        0 12px 24px rgba(0,223,130,.20),
        0 0 0 1px rgba(255,255,255,.18) inset,
        0 0 24px rgba(0,223,130,.20);
      overflow:hidden;
    }

    .rb-avatar img{
      width:100%;
      height:100%;
      object-fit:cover;
      display:block;
    }

    /* =========================
       COMPOSER TRIGGER
    ========================= */
    .rb-composer-trigger{
      margin-bottom:14px;
    }

    .rb-composer-open{
      width:100%;
      min-height:72px;
      border:1.5px solid rgba(0,255,130,.72);
      border-radius:24px;
      padding:12px;
      cursor:pointer;

      display:grid;
      grid-template-columns:auto minmax(0, 1fr) auto;
      align-items:center;
      gap:12px;

      color:var(--rb-text);
      background:
        radial-gradient(280px 160px at 100% 0%, rgba(0,255,130,.16), transparent 60%),
        linear-gradient(180deg, rgba(9,37,31,.90), rgba(5,21,18,.92));

      box-shadow:
        0 18px 44px rgba(0,0,0,.34),
        inset 0 1px 0 rgba(255,255,255,.08),
        0 0 0 1px rgba(0,255,130,.22),
        0 0 28px rgba(0,255,130,.18);

      position:relative;
      overflow:hidden;
      text-align:left;
      transition:transform .18s ease, border-color .18s ease, box-shadow .18s ease;
    }

    .rb-composer-open::before{
      content:"";
      position:absolute;
      inset:0;
      background:
        linear-gradient(152deg, rgba(255,255,255,.08) 0%, rgba(255,255,255,.025) 28%, transparent 29%);
      pointer-events:none;
    }

    .rb-composer-open:hover{
      transform:translateY(-1px);
      border-color:rgba(0,255,130,.95);
      box-shadow:
        0 22px 50px rgba(0,0,0,.40),
        0 0 0 1px rgba(0,255,130,.35),
        0 0 36px rgba(0,255,130,.24);
    }

    .rb-composer-open > *{
      position:relative;
      z-index:1;
    }

    .rb-composer-placeholder{
      min-width:0;
      height:42px;
      border-radius:999px;
      border:1px dashed rgba(0,255,130,.65);
      background:rgba(3,15,15,.34);
      display:flex;
      align-items:center;
      padding:0 14px;
      color:rgba(214,255,240,.62);
      font-size:10px;
      font-weight:500;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      box-shadow:
        inset 0 0 0 1px rgba(0,255,130,.08),
        0 0 18px rgba(0,255,130,.10);
    }

    .rb-composer-plus{
      width:42px;
      height:42px;
      border-radius:999px;
      display:grid;
      place-items:center;
      color:#03110c;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.48), transparent 34%),
        linear-gradient(135deg, #00DF82 0%, #6dff98 100%);
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,.34),
        0 14px 30px rgba(0,223,130,.22),
        0 0 34px rgba(0,223,130,.12);
    }

    .rb-composer-plus svg{
      width:20px;
      height:20px;
    }

    /* =========================
       COMPOSER POPUP
    ========================= */
    .rb-compose-overlay{
      position:fixed;
      inset:0;
      z-index:9999;
      display:none;
      align-items:flex-end;
      justify-content:center;
      padding:18px 10px calc(18px + env(safe-area-inset-bottom));
    }

    .rb-compose-overlay.show{
      display:flex;
    }

    .rb-compose-backdrop{
      position:absolute;
      inset:0;
      background:rgba(1,7,7,.68);
      backdrop-filter:blur(14px);
      -webkit-backdrop-filter:blur(14px);
      animation:rbFadeIn .18s ease forwards;
    }

    .rb-compose-modal{
      position:relative;
      z-index:2;
      width:100%;
      max-width:430px;
      border-radius:28px;
      overflow:hidden;
      background:
        radial-gradient(320px 180px at 100% 0%, rgba(0,223,130,.16), transparent 62%),
        radial-gradient(260px 180px at 0% 100%, rgba(3,98,76,.34), transparent 64%),
        linear-gradient(180deg, rgba(9,37,31,.98), rgba(3,15,15,.98));
      border:1px solid rgba(0,255,130,.22);
      box-shadow:
        0 34px 90px rgba(0,0,0,.56),
        0 0 0 1px rgba(0,255,130,.10) inset,
        0 0 46px rgba(0,255,130,.10);
      padding:15px;
      animation:rbComposeUp .22s ease forwards;
    }

    .rb-compose-head{
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
      padding-bottom:13px;
      margin-bottom:13px;
      border-bottom:1px solid rgba(255,255,255,.075);
    }

    .rb-compose-head h2{
      margin:0;
      color:#ffffff;
      font-size:17px;
      line-height:1.15;
      font-weight:850;
      letter-spacing:-.03em;
    }

    .rb-compose-head p{
      margin:5px 0 0;
      color:rgba(214,255,240,.62);
      font-size:10px;
      font-weight:600;
      line-height:1.4;
    }

    .rb-compose-close{
      width:40px;
      height:40px;
      border-radius:15px;
      border:1px solid rgba(255,255,255,.10);
      background:rgba(255,255,255,.055);
      color:#ffffff;
      display:grid;
      place-items:center;
      cursor:pointer;
      flex:0 0 auto;
    }

    .rb-compose-close svg{
      width:20px;
      height:20px;
    }

    .rb-compose-user{
      display:flex;
      align-items:center;
      gap:10px;
      margin-bottom:12px;
    }

    .rb-compose-user h3{
      margin:0;
      color:#ffffff;
      font-size:14px;
      font-weight:950;
      line-height:1.15;
    }

    .rb-compose-user p{
      margin:4px 0 0;
      color:rgba(214,255,240,.56);
      font-size:11px;
      font-weight:800;
    }

    .rb-compose-textarea{
      width:100%;
      min-height:154px;
      resize:none;
      outline:none;
      border:1px dashed rgba(0,223,130,.40);
      border-radius:22px;
      background:rgba(3,15,15,.46);
      color:#f7fffb;
      padding:15px;
      font-size:12px;
      font-weight:600;
      line-height:1.55;
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,.04),
        0 10px 22px rgba(0,0,0,.18);
      transition:border-color .18s ease, box-shadow .18s ease, background .18s ease;
    }

    .rb-compose-textarea::placeholder{
      color:rgba(214,255,240,.50);
    }

    .rb-compose-textarea:focus{
      border-color:rgba(0,255,130,.65);
      background:rgba(3,15,15,.58);
      box-shadow:
        0 0 0 4px rgba(0,223,130,.08),
        inset 0 1px 0 rgba(255,255,255,.05),
        0 12px 28px rgba(0,0,0,.22);
    }

    .rb-compose-tools{
      margin-top:10px;
    }

    .rb-compose-upload{
      min-height:46px;
      border-radius:18px;
      border:1px solid rgba(255,255,255,.09);
      background:rgba(255,255,255,.045);
      display:flex;
      align-items:center;
      gap:9px;
      padding:10px 12px;
      color:rgba(214,255,240,.78);
      font-size:12px;
      font-weight:900;
      overflow:hidden;
      cursor:pointer;
    }

    .rb-compose-upload svg{
      width:18px;
      height:18px;
      color:var(--rb-neon);
      flex:0 0 auto;
    }

    .rb-compose-upload span{
      min-width:0;
      overflow:hidden;
      white-space:nowrap;
      text-overflow:ellipsis;
    }

    .rb-compose-upload input{
      position:absolute;
      opacity:0;
      pointer-events:none;
      width:1px;
      height:1px;
    }

    .rb-compose-actions{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:10px;
      margin-top:13px;
    }

    .rb-compose-cancel,
    .rb-compose-submit{
      min-height:46px;
      border-radius:999px;
      border:0;
      cursor:pointer;
      font-size:13px;
      font-weight:950;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:8px;
    }

    .rb-compose-cancel{
      color:rgba(214,255,240,.76);
      background:rgba(255,255,255,.06);
      border:1px solid rgba(255,255,255,.08);
    }

    .rb-compose-submit{
      color:#03110c;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.48), transparent 34%),
        linear-gradient(135deg, #00DF82 0%, #6dff98 100%);
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,.34),
        0 14px 30px rgba(0,223,130,.24),
        0 0 34px rgba(0,223,130,.12);
    }

    .rb-compose-submit svg{
      width:18px;
      height:18px;
    }

    /* =========================
       TOAST POPUP MESSAGE
    ========================= */
    .rb-toast-wrap{
      position:fixed;
      top:18px;
      left:50%;
      transform:translateX(-50%);
      width:min(calc(100% - 24px), 420px);
      z-index:10020;
      pointer-events:none;
    }

    .rb-toast{
      position:relative;
      pointer-events:auto;
      display:flex;
      align-items:flex-start;
      gap:12px;
      padding:15px 44px 15px 14px;
      border-radius:22px;
      overflow:hidden;
      animation:rbToastIn .26s ease forwards;
      border:1.5px solid rgba(255,255,255,.12);
      box-shadow:
        0 24px 60px rgba(0,0,0,.38),
        0 0 0 1px rgba(255,255,255,.05) inset;
      backdrop-filter:blur(18px);
      -webkit-backdrop-filter:blur(18px);
    }

    .rb-toast.is-success{
      background:
        radial-gradient(260px 160px at 100% 0%, rgba(120,255,180,.28), transparent 60%),
        linear-gradient(180deg, rgba(16,92,67,.96), rgba(8,62,46,.98));
      border-color:rgba(0,255,130,.42);
      box-shadow:
        0 24px 60px rgba(0,0,0,.38),
        0 0 0 1px rgba(0,255,130,.10) inset,
        0 0 34px rgba(0,255,130,.14);
    }

    .rb-toast.is-error{
      background:
        radial-gradient(220px 140px at 100% 0%, rgba(255,130,150,.18), transparent 60%),
        linear-gradient(180deg, rgba(88,24,35,.97), rgba(57,14,22,.98));
      border-color:rgba(255,95,120,.34);
      box-shadow:
        0 24px 60px rgba(0,0,0,.40),
        0 0 0 1px rgba(255,95,120,.08) inset,
        0 0 30px rgba(255,95,120,.12);
    }

    .rb-toast-icon{
      width:44px;
      height:44px;
      border-radius:999px;
      flex:0 0 auto;
      display:grid;
      place-items:center;
    }

    .rb-toast.is-success .rb-toast-icon{
      color:#032015;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.52), transparent 34%),
        linear-gradient(135deg, #00ff82 0%, #8dff54 100%);
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,.36),
        0 10px 24px rgba(0,255,130,.24);
    }

    .rb-toast.is-error .rb-toast-icon{
      color:#ffffff;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.16), transparent 34%),
        linear-gradient(135deg, #ff5d79 0%, #ff8b65 100%);
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,.20),
        0 10px 24px rgba(255,95,120,.20);
    }

    .rb-toast-icon svg{
      width:22px;
      height:22px;
    }

    .rb-toast-body{
      min-width:0;
      flex:1 1 auto;
    }

    .rb-toast-body h4{
      margin:0 0 4px;
      font-size:15px;
      font-weight:950;
      color:#ffffff;
      line-height:1.15;
    }

    .rb-toast-body p{
      margin:0;
      color:rgba(240,255,248,.88);
      font-size:12.5px;
      font-weight:800;
      line-height:1.5;
    }

    .rb-toast-errors{
      color:rgba(255,240,244,.90);
      font-size:12px;
      font-weight:800;
      line-height:1.5;
    }

    .rb-toast-close{
      position:absolute;
      top:10px;
      right:10px;
      width:30px;
      height:30px;
      border-radius:10px;
      border:1px solid rgba(255,255,255,.10);
      background:rgba(255,255,255,.06);
      color:#ffffff;
      display:grid;
      place-items:center;
      cursor:pointer;
    }

    .rb-toast-close svg{
      width:16px;
      height:16px;
    }

    .rb-toast-hide{
      animation:rbToastOut .22s ease forwards;
    }

    /* =========================
       FEED
    ========================= */
    .rb-feed{
      display:grid;
      grid-template-columns:1fr;
      gap:12px;
    }

    .rb-feed-card{
      position:relative;
      overflow:hidden;
      border-radius:24px;
      background:
        radial-gradient(260px 160px at 90% 0%, rgba(120,255,180,.34), transparent 58%),
        radial-gradient(220px 140px at 0% 100%, rgba(0,255,130,.18), transparent 62%),
        linear-gradient(180deg, rgba(18,78,60,.96), rgba(10,56,44,.96));
      border:1.5px solid rgba(0,255,130,.42);
      box-shadow:
        0 16px 34px rgba(0,0,0,.22),
        0 0 0 1px rgba(0,255,130,.10) inset,
        0 0 32px rgba(0,255,130,.12);
      padding:13px;
      transition:transform .18s ease, border-color .18s ease, box-shadow .18s ease;
    }

    .rb-feed-card:hover{
      transform:translateY(-1px);
      border-color:rgba(0,255,130,.72);
      box-shadow:
        0 22px 42px rgba(0,0,0,.26),
        0 0 0 1px rgba(0,255,130,.14) inset,
        0 0 36px rgba(0,255,130,.18);
    }

    .rb-post-head{
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
    }

    .rb-post-user{
      display:flex;
      align-items:center;
      gap:10px;
      min-width:0;
    }

    .rb-post-name{
      margin:0;
      color:#ffffff;
      font-size:14px;
      font-weight:950;
      letter-spacing:-.02em;
      line-height:1.15;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width:230px;
    }

    .rb-post-time{
      margin:4px 0 0;
      color:rgba(214,255,240,.64);
      font-size:11px;
      font-weight:750;
    }

    .rb-delete-btn{
      width:36px;
      height:36px;
      border-radius:13px;
      border:1px solid rgba(255,79,109,.20);
      background:rgba(255,79,109,.08);
      color:#ffd7df;
      display:grid;
      place-items:center;
      cursor:pointer;
      box-shadow:0 10px 20px rgba(0,0,0,.20);
      transition:transform .18s ease, background .18s ease;
    }

    .rb-delete-btn:hover{
      transform:translateY(-1px);
      background:rgba(255,79,109,.13);
    }

    .rb-delete-btn svg{
      width:17px;
      height:17px;
    }

    .rb-post-content{
      margin:12px 0 0;
      color:rgba(247,255,251,.92);
      font-size:13px;
      font-weight:750;
      line-height:1.58;
      white-space:pre-wrap;
    }

    .rb-media-grid{
      display:grid;
      gap:8px;
      margin-top:12px;
    }

    .rb-media-grid.is-one{
      grid-template-columns:1fr;
    }

    .rb-media-grid.is-two,
    .rb-media-grid.is-many{
      grid-template-columns:repeat(2, 1fr);
    }




    .rb-file-list{
      display:grid;
      grid-template-columns:1fr;
      gap:8px;
      margin-top:12px;
    }

    .rb-file-card{
      min-height:48px;
      display:flex;
      align-items:center;
      gap:10px;
      border-radius:16px;
      padding:10px 12px;
      background:rgba(255,255,255,.065);
      border:1px solid rgba(255,255,255,.10);
      color:rgba(214,255,240,.86);
      font-size:12px;
      font-weight:850;
      overflow:hidden;
    }

    .rb-file-card svg{
      width:18px;
      height:18px;
      color:var(--rb-neon);
      flex:0 0 auto;
    }

    .rb-file-card span{
      min-width:0;
      overflow:hidden;
      white-space:nowrap;
      text-overflow:ellipsis;
    }

    .rb-post-actions{
      display:grid;
      grid-template-columns:repeat(4, 1fr);
      gap:6px;
      margin-top:13px;
      padding-top:11px;
      border-top:1px solid rgba(255,255,255,.10);
    }

    .rb-action{
      min-height:34px;
      border-radius:999px;
      display:flex;
      align-items:center;
      justify-content:center;
      gap:6px;
      color:rgba(214,255,240,.78);
      background:rgba(255,255,255,.06);
      border:1px solid rgba(255,255,255,.07);
      font-size:11px;
      font-weight:900;
      transition:transform .18s ease, color .18s ease, background .18s ease, border-color .18s ease;
    }

    .rb-action:hover{
      transform:translateY(-1px);
      color:#03110c;
      border-color:rgba(0,255,130,.28);
      background:rgba(0,255,130,.18);
    }

    .rb-action svg{
      width:16px;
      height:16px;
    }

    .rb-action.is-comment{
      color:#03110c;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.42), transparent 34%),
        linear-gradient(135deg, #00DF82 0%, #59ff9c 100%);
      border:0;
      box-shadow:0 12px 24px rgba(0,223,130,.16);
    }

    .rb-empty{
      padding:22px 16px;
      border-radius:24px;
      background:
        radial-gradient(220px 120px at 90% 0%, rgba(0,223,130,.12), transparent 62%),
        rgba(9,37,31,.76);
      border:1px dashed rgba(0,223,130,.22);
      color:rgba(214,255,240,.72);
      text-align:center;
      font-size:12.5px;
      font-weight:800;
      line-height:1.5;
    }

    .rb-empty b{
      color:#ffffff;
    }

    .rb-pager{
      margin-top:12px;
      padding:10px 12px;
      border-radius:18px;
      border:1px solid rgba(255,255,255,.08);
      background:rgba(255,255,255,.045);
      overflow:auto;
      color:#ffffff;
      box-shadow:0 10px 22px rgba(0,0,0,.18);
    }

    .rb-pager a{
      color:var(--rb-neon);
      font-weight:950;
    }

    .rb-pager span{
      color:rgba(214,255,240,.72);
    }

    .rb-bottom-spacer-local{
      height:94px;
    }

    /* =========================
       ANIMATION
    ========================= */
    @keyframes rbFadeIn{
      from{ opacity:0; }
      to{ opacity:1; }
    }

    @keyframes rbComposeUp{
      from{
        opacity:0;
        transform:translateY(26px) scale(.96);
      }
      to{
        opacity:1;
        transform:translateY(0) scale(1);
      }
    }

    @keyframes rbToastIn{
      from{
        opacity:0;
        transform:translateY(-14px) scale(.96);
      }
      to{
        opacity:1;
        transform:translateY(0) scale(1);
      }
    }

    @keyframes rbToastOut{
      from{
        opacity:1;
        transform:translateY(0) scale(1);
      }
      to{
        opacity:0;
        transform:translateY(-10px) scale(.96);
      }
    }

    /* =========================
       RESPONSIVE
    ========================= */
    @media (min-width:768px){
      .rb-compose-overlay{
        align-items:center;
      }
    }

    @media (max-width:370px){
      .rb-page{
        padding-left:8px;
        padding-right:8px;
      }

      .rb-title-wrap h1{
        font-size:20px;
      }

      .rb-title-wrap p{
        max-width:190px;
        font-size:10.5px;
      }

      .rb-header-btn{
        width:39px;
        height:39px;
      }

      .rb-avatar{
        width:38px;
        height:38px;
        font-size:12px;
      }

      .rb-composer-open{
        min-height:66px;
        border-radius:22px;
        padding:10px;
        gap:9px;
      }

      .rb-composer-placeholder{
        height:40px;
        font-size:8.5px;
        padding:0 12px;
      }

      .rb-composer-plus{
        width:40px;
        height:40px;
      }

      .rb-compose-modal{
        border-radius:24px;
        padding:13px;
      }

      .rb-compose-textarea{
        min-height:140px;
        border-radius:20px;
      }

      .rb-toast-wrap{
        top:14px;
        width:min(calc(100% - 18px), 420px);
      }

      .rb-toast{
        border-radius:18px;
        padding:13px 40px 13px 12px;
      }

      .rb-toast-icon{
        width:40px;
        height:40px;
      }

      .rb-toast-body h4{
        font-size:14px;
      }

      .rb-toast-body p,
      .rb-toast-errors{
        font-size:11.5px;
      }

      .rb-feed-card{
        border-radius:22px;
        padding:12px;
      }



      .rb-post-actions{
        grid-template-columns:repeat(2, 1fr);
      }
    }
/* =========================
   FORUM MEDIA IMAGE - PORTRAIT MODE
========================= */
.rb-media-grid{
  display:grid;
  gap:8px;
  margin-top:12px;
}

/* Kalau cuma 1 gambar, tampil portrait besar */
.rb-media-grid.is-one{
  grid-template-columns:1fr;
}

/* Kalau 2 atau banyak gambar, tetap grid tapi item tetap portrait */
.rb-media-grid.is-two,
.rb-media-grid.is-many{
  grid-template-columns:repeat(2, 1fr);
}

.rb-media-img{
  width:100%;
  aspect-ratio:3 / 4;
  height:auto;
  border-radius:18px;
  object-fit:cover;
  object-position:center center;
  display:block;
  border:1px solid rgba(140,255,190,.24);
  background:rgba(170,255,210,.10);
  box-shadow:
    0 14px 26px rgba(0,0,0,.18),
    0 0 0 1px rgba(0,255,130,.08) inset;
}

/* 1 gambar dibuat portrait lebih premium */
.rb-media-grid.is-one .rb-media-img{
  width:100%;
  aspect-ratio:3 / 4;
  height:auto;
  max-height:520px;
  object-fit:cover;
}

/* 2 gambar / banyak gambar tetap portrait kecil */
.rb-media-grid.is-two .rb-media-img,
.rb-media-grid.is-many .rb-media-img{
  aspect-ratio:3 / 4;
  height:auto;
  object-fit:cover;
}

@media (max-width:370px){
  .rb-media-img,
  .rb-media-grid.is-one .rb-media-img,
  .rb-media-grid.is-two .rb-media-img,
  .rb-media-grid.is-many .rb-media-img{
    aspect-ratio:3 / 4;
    height:auto;
    border-radius:16px;
  }
}
   /* =========================
   INLINE COMMENT PANEL
========================= */
.rb-comments-panel{
  display:none;
  margin-top:13px;
  padding-top:13px;
  border-top:1px solid rgba(230,255,244,.16);
}

.rb-comments-panel.show{
  display:block;
  animation:rbCommentOpen .18s ease forwards;
}

.rb-comments-title{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:10px;
  margin-bottom:10px;
}

.rb-comments-title h3{
  margin:0;
  color:#ffffff;
  font-size:13px;
  font-weight:750;
  letter-spacing:-.02em;
}

.rb-comments-title span{
  color:rgba(230,255,244,.78);
  font-size:11px;
  font-weight:650;
}

.rb-comment-form{
  display:grid;
  grid-template-columns:minmax(0, 1fr) auto;
  gap:8px;
  align-items:end;
  margin-bottom:12px;
}

.rb-comment-input{
  width:100%;
  min-height:42px;
  max-height:110px;
  resize:vertical;
  outline:none;
  border:1px dashed rgba(170,255,215,.48);
  border-radius:17px;
  background:
    radial-gradient(160px 80px at 100% 0%, rgba(255,255,255,.18), transparent 60%),
    linear-gradient(180deg, rgba(240,255,248,.16), rgba(185,255,218,.10));
  color:#ffffff;
  padding:11px 12px;
  font-size:12px;
  font-weight:500;
  line-height:1.45;
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,.10),
    0 8px 18px rgba(0,0,0,.12),
    0 0 18px rgba(0,255,130,.05);
  backdrop-filter:blur(8px);
  -webkit-backdrop-filter:blur(8px);
}

.rb-comment-input::placeholder{
  color:rgba(235,255,247,.64);
}

.rb-comment-input:focus{
  border-color:rgba(0,255,130,.74);
  background:
    radial-gradient(180px 90px at 100% 0%, rgba(255,255,255,.24), transparent 60%),
    linear-gradient(180deg, rgba(245,255,250,.22), rgba(180,255,215,.14));
  box-shadow:
    0 0 0 4px rgba(0,255,130,.10),
    inset 0 1px 0 rgba(255,255,255,.12),
    0 0 22px rgba(0,255,130,.10);
}

.rb-comment-submit{
  width:42px;
  height:42px;
  border:0;
  border-radius:999px;
  display:grid;
  place-items:center;
  cursor:pointer;
  color:#03110c;
  background:
    radial-gradient(circle at 30% 0%, rgba(255,255,255,.52), transparent 34%),
    linear-gradient(135deg, #00DF82 0%, #7dff9b 100%);
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,.34),
    0 12px 24px rgba(0,223,130,.24),
    0 0 22px rgba(0,255,130,.10);
}

.rb-comment-submit svg{
  width:18px;
  height:18px;
}

.rb-comment-list{
  display:grid;
  grid-template-columns:1fr;
  gap:8px;
}

.rb-comment-item{
  display:flex;
  align-items:flex-start;
  gap:9px;
  padding:10px;
  border-radius:18px;
  background:
    radial-gradient(180px 100px at 100% 0%, rgba(255,255,255,.30), transparent 60%),
    radial-gradient(160px 100px at 0% 100%, rgba(120,255,185,.22), transparent 65%),
    linear-gradient(180deg, rgba(245,255,250,.22), rgba(180,255,215,.15));
  border:1px solid rgba(190,255,225,.34);
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,.14),
    0 10px 20px rgba(0,0,0,.10),
    0 0 22px rgba(0,255,130,.07);
  backdrop-filter:blur(10px);
  -webkit-backdrop-filter:blur(10px);
}

.rb-comment-item .rb-avatar{
  width:32px;
  height:32px;
  font-size:10px;
  box-shadow:
    0 8px 18px rgba(0,223,130,.20),
    0 0 0 1px rgba(255,255,255,.20) inset;
}

.rb-comment-body{
  min-width:0;
  flex:1 1 auto;
}

.rb-comment-head{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:8px;
  margin-bottom:4px;
}

.rb-comment-name{
  margin:0;
  color:#ffffff;
  font-size:12px;
  font-weight:800;
  line-height:1.1;
  white-space:nowrap;
  overflow:hidden;
  text-overflow:ellipsis;
  text-shadow:0 1px 8px rgba(0,0,0,.18);
}

.rb-comment-time{
  display:block;
  margin-top:3px;
  color:rgba(235,255,247,.74);
  font-size:10px;
  font-weight:600;
}

.rb-comment-text{
  margin:0;
  color:rgba(255,255,255,.96);
  font-size:12px;
  font-weight:650;
  line-height:1.5;
  white-space:pre-wrap;
  text-shadow:0 1px 8px rgba(0,0,0,.12);
}

.rb-comment-delete{
  width:28px;
  height:28px;
  border-radius:10px;
  border:1px solid rgba(255,255,255,.18);
  background:rgba(255,255,255,.12);
  color:#ffd7df;
  display:grid;
  place-items:center;
  cursor:pointer;
  flex:0 0 auto;
  transition:
    transform .18s ease,
    background .18s ease,
    border-color .18s ease;
}

.rb-comment-delete:hover{
  transform:translateY(-1px);
  background:rgba(255,90,120,.18);
  border-color:rgba(255,90,120,.32);
}

.rb-comment-delete svg{
  width:14px;
  height:14px;
}

.rb-comment-empty{
  padding:11px 12px;
  border-radius:16px;
  background:
    radial-gradient(150px 80px at 100% 0%, rgba(255,255,255,.16), transparent 60%),
    linear-gradient(180deg, rgba(240,255,248,.13), rgba(170,255,215,.09));
  border:1px dashed rgba(190,255,225,.30);
  color:rgba(235,255,247,.72);
  font-size:11.5px;
  font-weight:700;
  text-align:center;
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,.08),
    0 8px 18px rgba(0,0,0,.08);
}

.rb-feed-card.is-comment-open{
  border-color:rgba(0,255,130,.80);
  box-shadow:
    0 22px 42px rgba(0,0,0,.26),
    0 0 0 1px rgba(0,255,130,.18) inset,
    0 0 42px rgba(0,255,130,.20);
}

@keyframes rbCommentOpen{
  from{
    opacity:0;
    transform:translateY(-6px);
  }
  to{
    opacity:1;
    transform:translateY(0);
  }
}

@media (max-width:370px){
  .rb-comment-form{
    grid-template-columns:1fr 40px;
  }

  .rb-comment-submit{
    width:40px;
    height:40px;
  }

  .rb-comment-input{
    font-size:11.5px;
  }
}

.rb-comment-divider{
  position:relative;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:10px;
  margin:10px 0 12px;
}

.rb-comment-divider::before,
.rb-comment-divider::after{
  content:"";
  flex:1 1 auto;
  height:1px;
  background:linear-gradient(
    90deg,
    rgba(190,255,225,0),
    rgba(190,255,225,.42),
    rgba(190,255,225,0)
  );
}

.rb-comment-divider span{
  flex:0 0 auto;
  padding:0 10px;
  font-size:10.5px;
  font-weight:700;
  letter-spacing:.02em;
  color:rgba(235,255,247,.72);
  text-transform:uppercase;
  white-space:nowrap;
}
  </style>
</head>

<body>
  {{-- POPUP COMPOSER --}}
  <div class="rb-compose-overlay" id="composeOverlay" aria-hidden="true">
    <div class="rb-compose-backdrop" id="closeComposerBackdrop"></div>

    <section class="rb-compose-modal" role="dialog" aria-modal="true" aria-labelledby="composeTitle">
      <div class="rb-compose-head">
        <div>
          <h2 id="composeTitle">Buat Forum</h2>
          <p>Bagikan update atau bukti untuk member Rubik.</p>
        </div>

        <button class="rb-compose-close" type="button" id="closeComposerBtn" aria-label="Tutup popup">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M18 6 6 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
            <path d="M6 6 18 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
          </svg>
        </button>
      </div>

      <form method="POST" action="{{ route('team.posts.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="rb-compose-user">
          <div class="rb-avatar" aria-hidden="true">
            {{ rbInitials($user->name ?? 'User') }}
          </div>

          <div>
            <h3>{{ $user->name ?? 'User' }}</h3>
            <p>Posting ke Forum Rubik</p>
          </div>
        </div>

        <textarea
          class="rb-compose-textarea"
          name="content"
          rows="6"
          placeholder="Berikan postingan..."
        >{{ old('content') }}</textarea>

        <div class="rb-compose-tools">
          <label class="rb-compose-upload">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M17 8 12 3 7 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M12 3v12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>

            <span id="uploadText">Gambar / PDF / File</span>
            <input id="mediaInput" type="file" name="media[]" multiple>
          </label>
        </div>

        <div class="rb-compose-actions">
          <button class="rb-compose-cancel" type="button" id="cancelComposerBtn">
            Batal
          </button>

          <button class="rb-compose-submit" type="submit">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M22 2 11 13" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M22 2 15 22 11 13 2 9 22 2Z" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Posting
          </button>
        </div>
      </form>
    </section>
  </div>

  {{-- POPUP MESSAGE --}}
  @if(session('success') || session('error') || $errors->any())
    <div class="rb-toast-wrap" id="rbToastWrap">
      <div
        class="rb-toast {{ session('success') ? 'is-success' : '' }} {{ session('error') || $errors->any() ? 'is-error' : '' }}"
        id="rbToast"
        role="alert"
        aria-live="assertive"
      >
        <button type="button" class="rb-toast-close" id="rbToastClose" aria-label="Tutup notifikasi">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M18 6 6 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
            <path d="M6 6 18 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
          </svg>
        </button>

        <div class="rb-toast-icon">
          @if(session('success'))
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          @else
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 8v5" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"/>
              <circle cx="12" cy="16.5" r="1" fill="currentColor"/>
              <path d="M10.3 3.84 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.7 3.84a2 2 0 0 0-3.4 0Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
          @endif
        </div>

        <div class="rb-toast-body">
          @if(session('success'))
            <h4>Sukses</h4>
            <p>{{ session('success') }}</p>
          @elseif(session('error'))
            <h4>Gagal</h4>
            <p>{{ session('error') }}</p>
          @elseif($errors->any())
            <h4>Terjadi kesalahan</h4>
            <div class="rb-toast-errors">
              @foreach($errors->all() as $error)
                <div>• {{ $error }}</div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>
  @endif

  <main class="rb-page">
    <div class="rb-phone">

      {{-- HEADER --}}
      <header class="rb-topbar">
        <div class="rb-brand">
          <div class="rb-logo-card">
            <img src="{{ asset('logo.png') }}" alt="Rubik Company">
          </div>

          <div class="rb-title-wrap">
            <h1>FORUM</h1>
            <p>Ruang diskusi member Rubik</p>
          </div>
        </div>

        <div class="rb-header-actions">
          <a href="{{ url('/saldo/rincian') }}" class="rb-header-btn" aria-label="Notifikasi">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9Z" fill="currentColor"/>
              <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span class="rb-notif-dot"></span>
          </a>

          <a href="{{ url('/akun') }}" class="rb-header-btn" aria-label="Profil">
            <svg viewBox="0 0 24 24" fill="none">
              <circle cx="12" cy="8" r="4" fill="currentColor"/>
              <path d="M4 21a8 8 0 0 1 16 0" fill="currentColor"/>
            </svg>
          </a>
        </div>
      </header>

      {{-- COMPOSER TRIGGER --}}
      <section class="rb-composer-trigger" aria-label="Buat postingan forum">
        <button class="rb-composer-open" type="button" id="openComposerBtn">
          <span class="rb-avatar" aria-hidden="true">
            {{ rbInitials($user->name ?? 'User') }}
          </span>

          <span class="rb-composer-placeholder">
            Apa yang kamu pikirkan hari ini?
          </span>

          <span class="rb-composer-plus" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 5v14" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
              <path d="M5 12h14" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
            </svg>
          </span>
        </button>
      </section>

      {{-- FEED --}}
      <section class="rb-feed" aria-label="Feed Forum">
        @forelse($posts as $post)
          @php
            $postUser = $post->user;
            $imgs = $post->media ? $post->media->where('type', 'image') : collect();
            $files = $post->media ? $post->media->where('type', 'file') : collect();

            $imgCount = $imgs->count();
            $imgClass = $imgCount === 1 ? 'is-one' : ($imgCount === 2 ? 'is-two' : 'is-many');

            $commentsCount = $post->comments_count ?? ($post->comments ? $post->comments->count() : 0);
          @endphp

          <article class="rb-feed-card" aria-label="Postingan {{ $post->id }}">
            <div class="rb-post-head">
              <div class="rb-post-user">
                <div class="rb-avatar" aria-hidden="true">
                  {{ rbInitials($postUser->name ?? 'User') }}
                </div>

                <div style="min-width:0">
                  <h2 class="rb-post-name">{{ $postUser->name ?? 'User' }}</h2>
                  <p class="rb-post-time">
                    {{ rbTimeLabel($post->created_at) }}
                    @if($post->created_at)
                      • {{ $post->created_at->format('H:i') }}
                    @endif
                  </p>
                </div>
              </div>

              @can('delete', $post)
                <form method="POST" action="{{ route('team.posts.destroy', $post) }}">
                  @csrf
                  @method('DELETE')

                  <button
                    class="rb-delete-btn"
                    type="submit"
                    onclick="return confirm('Hapus postingan ini?')"
                    aria-label="Hapus postingan"
                  >
                    <svg viewBox="0 0 24 24" fill="none">
                      <path d="M3 6h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                      <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                      <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                      <path d="M10 11v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                      <path d="M14 11v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                  </button>
                </form>
              @endcan
            </div>

            @if($post->content)
              <div class="rb-post-content">{{ $post->content }}</div>
            @endif

            @if($imgs->count())
              <div class="rb-media-grid {{ $imgClass }}" aria-label="Media gambar">
                @foreach($imgs as $m)
                  <a href="{{ asset('storage/'.$m->path) }}" target="_blank" rel="noopener">
                    <img
                      class="rb-media-img"
                      src="{{ asset('storage/'.$m->path) }}"
                      alt="{{ $m->original_name ?? 'Forum media' }}"
                    >
                  </a>
                @endforeach
              </div>
            @endif

            @if($files->count())
              <div class="rb-file-list" aria-label="File postingan">
                @foreach($files as $m)
                  <a class="rb-file-card" href="{{ asset('storage/'.$m->path) }}" target="_blank" rel="noopener">
                    <svg viewBox="0 0 24 24" fill="none">
                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                      <path d="M14 2v6h6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    </svg>
                    <span>{{ $m->original_name ?? 'Download file' }}</span>
                  </a>
                @endforeach
              </div>
            @endif

          <div class="rb-post-actions">
            <button class="rb-action" type="button" aria-label="Like">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                <path d="M7 11l4-8a3 3 0 0 1 3 3v5h5a2 2 0 0 1 2 2l-1 6a3 3 0 0 1-3 3H7V11Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              </svg>
              Suka
            </button>

            <button
              class="rb-action is-comment"
              type="button"
              data-comment-toggle="{{ $post->id }}"
              aria-expanded="false"
            >
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              </svg>
              {{ $commentsCount }}
            </button>

            <button class="rb-action" type="button">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M4 12v7a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M16 6l-4-4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 2v14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
              Bagikan
            </button>

            <button class="rb-action" type="button">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              </svg>
              Simpan
            </button>
          </div>

          @php
  $postComments = $post->comments ?? collect();
@endphp

<div class="rb-comments-panel" id="commentsPanel-{{ $post->id }}" aria-label="Komentar postingan {{ $post->id }}">
  <div class="rb-comments-title">
    <h3>Komentar</h3>
    <span>{{ $commentsCount }} komentar</span>
  </div>

  <form class="rb-comment-form" method="POST" action="{{ route('team.comments.store', $post) }}">
  @csrf

  <textarea
    class="rb-comment-input"
    name="content"
    rows="2"
    placeholder="Tulis komentar..."
    required
  ></textarea>

  <button class="rb-comment-submit" type="submit" aria-label="Kirim komentar">
    <svg viewBox="0 0 24 24" fill="none">
      <path d="M22 2 11 13" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M22 2 15 22 11 13 2 9 22 2Z" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </button>
</form>

<div class="rb-comment-divider">
  <span>Daftar komentar</span>
</div>

<div class="rb-comment-list">
    @forelse($postComments as $comment)
      <div class="rb-comment-item">
        <div class="rb-avatar" aria-hidden="true">
          {{ rbInitials($comment->user->name ?? 'User') }}
        </div>

        <div class="rb-comment-body">
          <div class="rb-comment-head">
            <div style="min-width:0">
              <p class="rb-comment-name">{{ $comment->user->name ?? 'User' }}</p>
              <span class="rb-comment-time">
                {{ rbTimeLabel($comment->created_at) }}
                @if($comment->created_at)
                  • {{ $comment->created_at->format('H:i') }}
                @endif
              </span>
            </div>

            @can('delete', $comment)
              <form method="POST" action="{{ route('team.comments.destroy', $comment) }}">
                @csrf
                @method('DELETE')

                <button
                  class="rb-comment-delete"
                  type="submit"
                  onclick="return confirm('Hapus komentar ini?')"
                  aria-label="Hapus komentar"
                >
                  <svg viewBox="0 0 24 24" fill="none">
                    <path d="M3 6h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                  </svg>
                </button>
              </form>
            @endcan
          </div>

          <p class="rb-comment-text">{{ $comment->content }}</p>
        </div>
      </div>
    @empty
      <div class="rb-comment-empty">
        Belum ada komentar. Jadilah yang pertama berkomentar.
      </div>
    @endforelse
  </div>
</div>


          </article>
        @empty
          <div class="rb-empty">
            <b>Belum ada postingan.</b><br>
            Jadilah yang pertama membagikan update atau diskusi di forum Rubik.
          </div>
        @endforelse
      </section>

      @if(method_exists($posts, 'links'))
        <div class="rb-pager">
          {{ $posts->links() }}
        </div>
      @endif

      <div class="rb-bottom-spacer-local"></div>
    </div>
  </main>

  @include('partials.bottom-nav')

  <script>
    (function(){
      const overlay = document.getElementById('composeOverlay');
      const openBtn = document.getElementById('openComposerBtn');
      const closeBtn = document.getElementById('closeComposerBtn');
      const cancelBtn = document.getElementById('cancelComposerBtn');
      const backdrop = document.getElementById('closeComposerBackdrop');
      const textarea = overlay ? overlay.querySelector('.rb-compose-textarea') : null;

      const input = document.getElementById('mediaInput');
      const label = document.getElementById('uploadText');

      const toast = document.getElementById('rbToast');
      const toastWrap = document.getElementById('rbToastWrap');
      const toastClose = document.getElementById('rbToastClose');

      function openComposer(){
        if(!overlay) return;

        overlay.classList.add('show');
        overlay.setAttribute('aria-hidden', 'false');
        document.body.classList.add('rb-compose-lock');

        setTimeout(function(){
          if(textarea) textarea.focus();
        }, 120);
      }

      function closeComposer(){
        if(!overlay) return;

        overlay.classList.remove('show');
        overlay.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('rb-compose-lock');
      }

      function updateUploadLabel(){
        if(!input || !label) return;

        const total = input.files ? input.files.length : 0;

        if(total <= 0){
          label.textContent = 'Gambar / PDF / File';
          return;
        }

        if(total === 1){
          label.textContent = input.files[0].name;
          return;
        }

        label.textContent = total + ' file dipilih';
      }

      function closeToast(){
        if(!toast || !toastWrap) return;

        toast.classList.add('rb-toast-hide');

        setTimeout(function(){
          toastWrap.remove();
        }, 220);
      }

      if(openBtn) openBtn.addEventListener('click', openComposer);
      if(closeBtn) closeBtn.addEventListener('click', closeComposer);
      if(cancelBtn) cancelBtn.addEventListener('click', closeComposer);
      if(backdrop) backdrop.addEventListener('click', closeComposer);

      if(input) input.addEventListener('change', updateUploadLabel);

      if(toastClose) toastClose.addEventListener('click', closeToast);
      if(toast && toastWrap) setTimeout(closeToast, 2800);

      document.addEventListener('keydown', function(e){
        if(e.key === 'Escape'){
          closeComposer();
          closeToast();
        }
      });

      @if($errors->any() && old('content'))
        openComposer();
      @endif
    })();

    const commentButtons = document.querySelectorAll('[data-comment-toggle]');

function closeAllCommentPanels(exceptId){
  document.querySelectorAll('.rb-comments-panel.show').forEach(function(panel){
    if(panel.id !== 'commentsPanel-' + exceptId){
      panel.classList.remove('show');

      const card = panel.closest('.rb-feed-card');
      if(card) card.classList.remove('is-comment-open');
    }
  });

  document.querySelectorAll('[data-comment-toggle]').forEach(function(btn){
    if(btn.getAttribute('data-comment-toggle') !== String(exceptId)){
      btn.setAttribute('aria-expanded', 'false');
    }
  });
}

commentButtons.forEach(function(btn){
  btn.addEventListener('click', function(e){
    e.preventDefault();
    e.stopPropagation();

    const id = btn.getAttribute('data-comment-toggle');
    const panel = document.getElementById('commentsPanel-' + id);
    const card = btn.closest('.rb-feed-card');

    if(!panel) return;

    const willOpen = !panel.classList.contains('show');

    closeAllCommentPanels(id);

    panel.classList.toggle('show', willOpen);
    btn.setAttribute('aria-expanded', willOpen ? 'true' : 'false');

    if(card){
      card.classList.toggle('is-comment-open', willOpen);
    }

    if(willOpen){
      setTimeout(function(){
        panel.scrollIntoView({
          behavior:'smooth',
          block:'nearest'
        });
      }, 80);
    }
  });
});

/* Klik area postingan juga buka komentar, kecuali klik tombol/form/link/media */
document.querySelectorAll('.rb-feed-card').forEach(function(card){
  card.addEventListener('click', function(e){
    if(
      e.target.closest('button') ||
      e.target.closest('a') ||
      e.target.closest('form') ||
      e.target.closest('textarea') ||
      e.target.closest('input')
    ){
      return;
    }

    const btn = card.querySelector('[data-comment-toggle]');
    if(btn) btn.click();
  });
});
  </script>
</body>
</html>