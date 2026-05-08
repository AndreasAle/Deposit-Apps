
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Masuk Akun | Rubik Company</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <meta name="robots" content="noindex, nofollow">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --green:#18c79b;
      --green-2:#42dfb2;
      --green-3:#0b8f70;
      --green-dark:#0d7f67;

      --dark:#031816;
      --dark-2:#05231f;
      --dark-3:#010f0d;

      --gold:#d6aa35;
      --gold-2:#f5d879;

      --white:#ffffff;
      --text:#10322c;
      --muted:#6c8b82;
      --line:#e2eee9;
      --danger:#ef4444;

      --shadow-page:0 28px 70px rgba(0,0,0,.30);
      --shadow-card:0 18px 38px rgba(6,34,29,.16);
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
      min-height:100vh;
      font-family:'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      color:var(--text);
      display:block;
      padding:0;
      overflow-x:hidden;
      position:relative;
      background:
        radial-gradient(760px 420px at 10% -6%, rgba(0,223,130,.16), transparent 58%),
        radial-gradient(640px 360px at 100% 0%, rgba(13,127,103,.18), transparent 62%),
        linear-gradient(180deg, #f7fffb 0%, #e9fbf2 48%, #d8f3e5 100%);
      -webkit-tap-highlight-color:transparent;
    }

    a{
      color:inherit;
    }

    button,
    input{
      font-family:inherit;
    }
.page{
  width:100%;
  max-width:none;
  min-height:100vh;
  position:relative;
  z-index:1;
  animation:pageEnter .45s ease both;
  display:block;
}

.shell{
  position:relative;
  overflow:hidden;
  width:100%;
  max-width:none;
  min-height:100vh;
  background:#ffffff;
  box-shadow:none;
}

.hero{
  position:relative;
  width:100%;
  padding:18px 22px 54px;
  overflow:hidden;
  background:
    linear-gradient(152deg, rgba(255,255,255,.10) 0%, rgba(255,255,255,.04) 28%, transparent 29%),
    radial-gradient(260px 160px at 92% 105%, rgba(0,223,130,.22), transparent 62%),
    radial-gradient(230px 160px at 16% 0%, rgba(3,98,76,.58), transparent 72%),
    linear-gradient(180deg, #10251f 0%, #041311 58%, #020908 100%);
}

    .hero::before{
      content:"";
      position:absolute;
      left:-48px;
      top:-34px;
      width:190px;
      height:170px;
      border-radius:44% 56% 54% 46% / 42% 44% 56% 58%;
      background:
        radial-gradient(circle at 30% 18%, rgba(255,255,255,.12), transparent 34%),
        linear-gradient(145deg, rgba(0,223,130,.22), rgba(3,98,76,.48));
      box-shadow:
        0 0 50px rgba(0,223,130,.08),
        inset 0 1px 0 rgba(255,255,255,.08);
      animation:blobFloat 6s ease-in-out infinite;
    }

    .hero::after{
      content:"";
      position:absolute;
      right:-38px;
      top:-38px;
      width:168px;
      height:138px;
      border-radius:34px 0 0 64px;
      background:
        radial-gradient(circle at 26% 26%, rgba(255,255,255,.18), transparent 34%),
        linear-gradient(145deg, rgba(214,255,240,.16), rgba(0,223,130,.08));
      border:1px solid rgba(255,255,255,.08);
      backdrop-filter:blur(4px);
      -webkit-backdrop-filter:blur(4px);
      animation:blobFloat2 7s ease-in-out infinite;
    }

    .topbar{
      position:relative;
      z-index:2;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
    }

    .brandMini{
      display:flex;
      align-items:center;
      gap:10px;
      min-width:0;
    }

    .brandMiniLogo{
      width:44px;
      height:44px;
      border-radius:16px;
      background:#ffffff;
      display:grid;
      place-items:center;
      overflow:hidden;
      box-shadow:
        0 12px 28px rgba(0,0,0,.22),
        0 0 0 1px rgba(255,255,255,.70) inset;
      flex:0 0 auto;
    }

    .brandMiniLogo img{
      width:34px;
      height:34px;
      object-fit:contain;
      display:block;
    }

    .brandMiniText{
      min-width:0;
    }

    .brandMiniText span{
      display:block;
      color:rgba(223,252,241,.68);
      font-size:11px;
      font-weight:700;
      line-height:1;
      margin-bottom:4px;
    }

    .brandMiniText strong{
      display:block;
      color:#ffffff;
      font-size:15px;
      font-weight:900;
      line-height:1;
      white-space:nowrap;
    }

    .topPill{
      min-height:34px;
      padding:0 13px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      gap:7px;
      color:#fff5d6;
      background:rgba(255,255,255,.08);
      border:1px solid rgba(255,255,255,.13);
      font-size:11px;
      font-weight:900;
      backdrop-filter:blur(10px);
      -webkit-backdrop-filter:blur(10px);
      white-space:nowrap;
    }

    .topPill::before{
      content:"";
      width:8px;
      height:8px;
      border-radius:999px;
      background:linear-gradient(135deg, var(--gold-2), var(--gold));
      box-shadow:0 0 14px rgba(245,216,121,.35);
    }

    .heroMain{
      position:relative;
      z-index:2;
      margin-top:26px;
      text-align:center;
    }

    .heroLogoCard{
      width:138px;
      min-height:138px;
      margin:0 auto 14px;
      border-radius:28px;
      padding:13px;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.85), transparent 38%),
        linear-gradient(180deg, rgba(255,255,255,.96), rgba(238,255,248,.92));
      border:1px dashed rgba(13,127,103,.20);
      box-shadow:
        0 24px 46px rgba(0,0,0,.28),
        0 0 0 8px rgba(255,255,255,.04),
        inset 0 1px 0 rgba(255,255,255,.85);
      display:grid;
      place-items:center;
      position:relative;
    }

    .heroLogoCard::after{
      content:"";
      position:absolute;
      inset:10px;
      border-radius:22px;
      border:1px solid rgba(13,127,103,.10);
      pointer-events:none;
    }

    .heroLogoCard img{
      width:96px;
      height:96px;
      object-fit:contain;
      display:block;
      position:relative;
      z-index:1;
    }

    .heroBadge{
      width:max-content;
      max-width:100%;
      margin:0 auto 10px;
      min-height:30px;
      padding:0 13px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:7px;
      color:#6f5000;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.62), transparent 38%),
        linear-gradient(180deg, #fff1bd, #d9ae36);
      border:1px solid rgba(139,101,0,.12);
      box-shadow:
        0 10px 18px rgba(139,101,0,.12),
        inset 0 1px 0 rgba(255,255,255,.52);
      font-size:11px;
      font-weight:900;
    }

    .heroTitle{
      margin:0;
      font-family:'Playfair Display', serif;
      color:#ffffff;
      font-size:32px;
      line-height:1.05;
      font-weight:800;
      letter-spacing:-.035em;
      text-shadow:0 10px 24px rgba(0,0,0,.22);
    }

    .heroTitle span{
      color:#f0cc62;
    }

    .heroSub{
      width:min(340px, 100%);
      margin:10px auto 0;
      color:rgba(223,252,241,.74);
      font-size:12px;
      line-height:1.6;
      font-weight:650;
    }

.heroStats{
  position:relative;
  z-index:2;
  margin:18px auto -26px;
  display:grid;
  grid-template-columns:repeat(3, 1fr);
  gap:8px;
}

    .heroStat{
      min-height:56px;
      border-radius:16px;
      padding:10px 8px;
      background:rgba(255,255,255,.95);
      border:1px solid rgba(13,127,103,.10);
      box-shadow:
        0 16px 28px rgba(0,0,0,.15),
        inset 0 1px 0 rgba(255,255,255,.82);
      text-align:center;
    }

    .heroStatIcon{
      width:18px;
      height:18px;
      margin:0 auto 5px;
      color:#d3a12a;
    }

    .heroStat strong{
      display:block;
      color:#14382f;
      font-size:10.5px;
      font-weight:900;
      line-height:1.15;
    }

    .heroStat span{
      display:block;
      margin-top:2px;
      color:#819890;
      font-size:9px;
      font-weight:700;
      line-height:1.15;
    }

.card{
  position:relative;
  z-index:5;
  width:100%;
  margin:0;
  padding:44px 22px 22px;
  background:
    radial-gradient(220px 140px at 100% 100%, rgba(98,221,177,.22), transparent 60%),
    radial-gradient(180px 120px at 78% 18%, rgba(255,255,255,.16), transparent 58%),
    linear-gradient(135deg, #e8fbf2 0%, #d7f5e7 52%, #c8efd9 100%);
  border-radius:0;
  box-shadow:0 -12px 32px rgba(6,34,29,.10);
  overflow:hidden;
}

    .card::before{
      content:"";
      position:absolute;
      inset:0;
      pointer-events:none;
      background:
        radial-gradient(180px 120px at 20% 0%, rgba(255,255,255,.22), transparent 60%),
        radial-gradient(260px 180px at 100% 100%, rgba(24,199,155,.10), transparent 66%);
      opacity:1;
    }

    .card > *{
      position:relative;
      z-index:1;
    }

    .switchTabs{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:0;
      padding:5px;
      border-radius:18px;
      background:rgba(255,255,255,.82);
      box-shadow:
        0 16px 30px rgba(6,34,29,.09),
        inset 0 1px 0 rgba(255,255,255,.70);
      border:1px solid rgba(13,127,103,.10);
      margin-bottom:14px;
    }

    .switchTab{
      min-height:46px;
      border-radius:14px;
      display:flex;
      align-items:center;
      justify-content:center;
      gap:8px;
      text-decoration:none;
      font-size:12px;
      font-weight:900;
      color:#6c8b82;
      transition:.18s ease;
    }

    .switchTab svg{
      width:16px;
      height:16px;
    }

    .switchTab.active{
      color:#ffffff;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.16), transparent 34%),
        linear-gradient(135deg, #031816 0%, #0a2f27 35%, #0d5c46 68%, #00c97a 100%);
      box-shadow:
        0 12px 24px rgba(6,34,29,.14),
        inset 0 1px 0 rgba(255,255,255,.14);
    }

    .loginPanel{
      padding:18px 16px 16px;
      border-radius:24px;
      background:rgba(255,255,255,.82);
      border:1px solid rgba(13,127,103,.12);
      box-shadow:
        0 18px 36px rgba(6,34,29,.08),
        inset 0 1px 0 rgba(255,255,255,.75);
      margin-bottom:14px;
    }

    .panelDecor{
      width:74px;
      height:4px;
      border-radius:999px;
      margin:0 auto 12px;
      background:linear-gradient(90deg, #0d5c46, #00c97a, #d6aa35);
    }

    .title{
      text-align:center;
      margin:0;
      font-family:'Playfair Display', serif;
      font-size:27px;
      line-height:1.08;
      font-weight:800;
      color:#173d35;
      letter-spacing:-.04em;
    }

    .subtitle{
      text-align:center;
      margin:9px auto 18px;
      color:#6c8b82;
      font-size:12.5px;
      line-height:1.55;
      font-weight:600;
      width:min(330px, 100%);
    }

    .error{
      margin-bottom:14px;
      padding:12px 13px;
      border-radius:16px;
      background:rgba(239,68,68,.08);
      border:1px solid rgba(239,68,68,.18);
      color:#b42318;
      font-size:12.5px;
      line-height:1.45;
      font-weight:650;
    }

    .field{
      margin-bottom:13px;
    }

    .label{
      display:flex;
      align-items:center;
      gap:6px;
      margin:0 0 7px;
      font-size:11.5px;
      line-height:1.2;
      color:#668078;
      font-weight:800;
    }

    .label svg{
      width:14px;
      height:14px;
      color:#d3a12a;
    }

    .inputWrap{
      position:relative;
    }

    .input{
      width:100%;
      height:52px;
      border-radius:16px;
      border:1px solid rgba(13,127,103,.14);
      background:
        radial-gradient(circle at 90% 0%, rgba(255,255,255,.72), transparent 42%),
        linear-gradient(135deg, rgba(238,255,248,.92), rgba(216,246,233,.82));
      outline:none;
      padding:0 14px;
      font-size:13.5px;
      color:#10322c;
      font-weight:750;
      transition:
        border-color .18s ease,
        box-shadow .18s ease,
        background .18s ease,
        transform .18s ease;
      box-shadow:
        0 10px 22px rgba(6,34,29,.045),
        inset 0 1px 0 rgba(255,255,255,.62);
      backdrop-filter:blur(8px);
      -webkit-backdrop-filter:blur(8px);
    }

    .input.input-phone{
      padding-left:76px;
    }

    .input.input-password{
      padding-right:48px;
      padding-left:46px;
    }

    .input:focus{
      border-color:rgba(0,201,122,.36);
      background:
        radial-gradient(circle at 90% 0%, rgba(255,255,255,.82), transparent 42%),
        linear-gradient(135deg, rgba(248,255,252,.98), rgba(214,250,234,.92));
      box-shadow:
        0 0 0 4px rgba(0,201,122,.10),
        0 12px 24px rgba(6,34,29,.065),
        inset 0 1px 0 rgba(255,255,255,.74);
      transform:translateY(-1px);
    }

    .input::placeholder{
      color:#8fa79f;
      font-weight:550;
    }

    .prefix62{
      position:absolute;
      top:50%;
      left:14px;
      transform:translateY(-50%);
      z-index:3;
      min-width:48px;
      height:30px;
      padding:0 9px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      border-radius:999px;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.78), transparent 40%),
        linear-gradient(135deg, rgba(232,255,246,.96), rgba(198,239,222,.84));
      border:1px solid rgba(13,127,103,.12);
      color:#0d7f67;
      font-size:12.5px;
      line-height:1;
      font-weight:900;
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,.68),
        0 6px 12px rgba(6,34,29,.045);
      pointer-events:none;
    }

    .prefixIcon{
      position:absolute;
      top:50%;
      left:13px;
      transform:translateY(-50%);
      z-index:3;
      width:28px;
      height:28px;
      border-radius:999px;
      display:grid;
      place-items:center;
      color:#0d7f67;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.78), transparent 40%),
        linear-gradient(135deg, rgba(232,255,246,.96), rgba(198,239,222,.84));
      border:1px solid rgba(13,127,103,.12);
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,.68),
        0 6px 12px rgba(6,34,29,.045);
      pointer-events:none;
    }

    .prefixIcon svg{
      width:15px;
      height:15px;
    }

    .togglePass{
      position:absolute;
      top:50%;
      right:9px;
      transform:translateY(-50%);
      width:36px;
      height:36px;
      border:none;
      border-radius:12px;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.72), transparent 40%),
        linear-gradient(135deg, rgba(232,255,246,.92), rgba(198,239,222,.78));
      cursor:pointer;
      display:grid;
      place-items:center;
      color:#0d7f67;
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,.62),
        0 6px 14px rgba(6,34,29,.05);
      transition:.18s ease;
    }

    .togglePass:hover{
      color:#03624C;
      box-shadow:
        0 8px 16px rgba(6,34,29,.075),
        0 0 0 3px rgba(0,201,122,.08),
        inset 0 1px 0 rgba(255,255,255,.74);
    }

    .togglePass svg{
      width:18px;
      height:18px;
      display:block;
    }

    .helperRow{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:10px;
      margin:8px 0 16px;
      flex-wrap:wrap;
    }

    .rememberCheck{
      display:inline-flex;
      align-items:center;
      gap:7px;
      color:#6c8b82;
      font-size:11.5px;
      font-weight:700;
      cursor:pointer;
    }

    .rememberCheck input{
      appearance:none;
      -webkit-appearance:none;
      width:15px;
      height:15px;
      border-radius:4px;
      border:1px solid rgba(13,127,103,.22);
      background:#ffffff;
      display:grid;
      place-items:center;
    }

    .rememberCheck input:checked{
      border-color:#0d7f67;
      background:linear-gradient(135deg, #0d5c46, #00c97a);
    }

    .rememberCheck input:checked::before{
      content:"✓";
      color:#fff;
      font-size:10px;
      font-weight:900;
      line-height:1;
    }

    .helperLink{
      font-size:11.5px;
      line-height:1.4;
      color:var(--green-dark);
      font-weight:900;
      text-decoration:none;
    }

    .helperLink:hover{
      text-decoration:underline;
    }

    .btn{
      width:100%;
      height:52px;
      border:none;
      border-radius:15px;
      cursor:pointer;
      color:#ffffff;
      font-size:14px;
      font-weight:900;
      letter-spacing:.01em;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.16), transparent 34%),
        linear-gradient(135deg, #031816 0%, #0a2f27 35%, #0d5c46 68%, #00c97a 100%);
      box-shadow:
        0 14px 28px rgba(0,0,0,.18),
        0 10px 26px rgba(0,223,130,.18),
        inset 0 1px 0 rgba(255,255,255,.12);
      transition:.2s ease;
      position:relative;
      overflow:hidden;
      display:flex;
      align-items:center;
      justify-content:center;
      gap:8px;
    }

    .btn::before{
      content:"";
      position:absolute;
      inset:0;
      background:linear-gradient(180deg, rgba(255,255,255,.08), transparent 40%);
      pointer-events:none;
    }

    .btn::after{
      content:"";
      position:absolute;
      top:0;
      left:-120%;
      width:55%;
      height:100%;
      background:linear-gradient(to right, transparent, rgba(255,255,255,.22), transparent);
      transform:skewX(-18deg);
      animation:btnShine 3.2s infinite;
      pointer-events:none;
    }

    .btn span,
    .btn svg{
      position:relative;
      z-index:1;
    }

    .btn svg{
      width:17px;
      height:17px;
    }

    .btn:hover{
      transform:translateY(-1px);
      filter:brightness(1.03);
      box-shadow:
        0 18px 34px rgba(0,0,0,.22),
        0 14px 34px rgba(0,223,130,.22),
        inset 0 1px 0 rgba(255,255,255,.14);
    }

    .trustTiles{
      display:grid;
      grid-template-columns:repeat(3, 1fr);
      gap:8px;
      margin-top:14px;
    }

    .trustTile{
      min-height:58px;
      border-radius:15px;
      background:
        linear-gradient(180deg, rgba(255,255,255,.92), rgba(244,255,250,.84));
      border:1px solid rgba(13,127,103,.10);
      box-shadow:
        0 10px 20px rgba(6,34,29,.045),
        inset 0 1px 0 rgba(255,255,255,.70);
      display:grid;
      place-items:center;
      text-align:center;
      padding:8px 6px;
    }

    .trustTile svg{
      width:17px;
      height:17px;
      color:#d3a12a;
      margin-bottom:4px;
    }

    .trustTile strong{
      display:block;
      color:#173d35;
      font-size:10.5px;
      line-height:1.2;
      font-weight:900;
    }

    .footer{
      margin-top:14px;
      text-align:center;
      font-size:12.5px;
      line-height:1.5;
      color:var(--muted);
      font-weight:600;
    }

    .footer a{
      color:var(--green-dark);
      font-weight:900;
      text-decoration:none;
    }

    .footer a:hover{
      text-decoration:underline;
    }

    /* =========================
       LEGALITAS SECTION
    ========================= */
    .legality-section{
      margin-top:14px;
    }

    .legality-shell{
      position:relative;
      border-radius:24px;
      padding:14px 12px 12px;
      background:
        radial-gradient(220px 140px at 100% 0%, rgba(0,201,122,.10), transparent 60%),
        linear-gradient(180deg, rgba(255,255,255,.96), rgba(239,252,245,.90));
      border:1px solid rgba(13,127,103,.10);
      box-shadow:
        0 18px 36px rgba(6,34,29,.06),
        inset 0 1px 0 rgba(255,255,255,.78);
    }

    .legality-title{
      margin:0 0 12px;
      text-align:center;
      font-size:15px;
      line-height:1.2;
      font-weight:900;
      letter-spacing:.02em;
      color:#173d35;
      text-transform:uppercase;
    }

    .legality-grid{
      display:grid;
      grid-template-columns:repeat(2, minmax(0, 1fr));
      gap:10px;
    }

    .legality-card{
      position:relative;
      min-width:0;
      border-radius:20px;
      padding:10px 10px 14px;
      background:linear-gradient(180deg, rgba(255,255,255,.96), rgba(245,255,250,.92));
      border:1px solid rgba(206,176,83,.42);
      box-shadow:
        0 10px 22px rgba(6,34,29,.05),
        inset 0 1px 0 rgba(255,255,255,.86);
      overflow:hidden;
    }

    .legality-card-top{
      position:absolute;
      top:0;
      left:50%;
      width:44%;
      height:18px;
      transform:translateX(-50%);
      background:linear-gradient(180deg, rgba(235,224,188,.85), rgba(232,248,241,0));
      border-bottom-left-radius:14px;
      border-bottom-right-radius:14px;
    }

    .legality-logo-box{
      height:78px;
      border-radius:16px;
      background:linear-gradient(180deg, rgba(247,250,248,.98), rgba(241,246,244,.94));
      border:1px solid rgba(179,196,189,.55);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.82);
      display:flex;
      align-items:center;
      justify-content:center;
      padding:10px 12px;
    }

    .legality-logo{
      display:block;
      max-width:100%;
      max-height:42px;
      object-fit:contain;
    }

    .legality-logo-ojk{
      max-height:46px;
    }

    .legality-logo-bappebti{
      max-height:46px;
    }

    .legality-name{
      margin-top:10px;
      text-align:center;
      font-size:11px;
      line-height:1.3;
      font-weight:900;
      color:#173d35;
    }

    .legality-badge{
      width:max-content;
      max-width:100%;
      margin:10px auto 0;
      min-height:30px;
      padding:0 10px;
      border-radius:999px;
      display:flex;
      align-items:center;
      justify-content:center;
      text-align:center;
      font-size:10px;
      line-height:1.2;
      font-weight:900;
      color:#8b6500;
      background:linear-gradient(180deg, #f7e6ab, #d8aa2d);
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,.45),
        0 6px 14px rgba(177,130,0,.14);
    }

    .legality-footer{
      margin-top:12px;
      border-radius:18px;
      padding:12px;
      display:flex;
      align-items:center;
      gap:10px;
      background:
        radial-gradient(180px 100px at 100% 0%, rgba(255,255,255,.10), transparent 60%),
        linear-gradient(135deg, #031816 0%, #0b4d3d 38%, #0d7f67 72%, #1fe08e 100%);
      color:#fff;
      box-shadow:0 14px 28px rgba(6,34,29,.16);
    }

    .legality-footer-icon{
      width:36px;
      height:36px;
      flex:0 0 36px;
      border-radius:10px;
      display:grid;
      place-items:center;
      color:#f6d97a;
      background:rgba(255,255,255,.10);
      border:1px solid rgba(255,255,255,.14);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.12);
    }

    .legality-footer-icon svg{
      width:20px;
      height:20px;
    }

    .legality-footer-content{
      display:grid;
      gap:3px;
      min-width:0;
    }

    .legality-footer-content strong{
      display:block;
      font-size:13px;
      line-height:1.25;
      font-weight:900;
      color:#ffffff;
    }

    .legality-footer-content span{
      display:block;
      font-size:11px;
      line-height:1.4;
      font-weight:700;
      color:rgba(240,255,248,.86);
    }

    .copyright{
      margin:16px 0 0;
      text-align:center;
      color:#6c8b82;
      font-size:10.5px;
      font-weight:700;
      line-height:1.5;
    }

    @keyframes pageEnter{
      from{ opacity:0; transform:translateY(12px); }
      to{ opacity:1; transform:translateY(0); }
    }

    @keyframes blobFloat{
      0%,100%{ transform:translate3d(0,0,0) rotate(0deg); }
      50%{ transform:translate3d(-8px,8px,0) rotate(5deg); }
    }

    @keyframes blobFloat2{
      0%,100%{ transform:translate3d(0,0,0); }
      50%{ transform:translate3d(7px,-7px,0); }
    }

    @keyframes btnShine{
      0%{ left:-120%; }
      20%{ left:180%; }
      100%{ left:180%; }
    }

@media (min-width:760px){
  body{
    padding:0;
  }

  .shell{
    border-radius:0;
    min-height:100vh;
  }
}

    @media (max-width:380px){
      .hero{
        padding-left:18px;
        padding-right:18px;
      }

      .card{
        padding-left:18px;
        padding-right:18px;
      }

      .heroTitle{
        font-size:28px;
      }

      .heroStats{
        gap:6px;
      }

      .loginPanel{
        padding-left:14px;
        padding-right:14px;
      }

      .trustTiles{
        gap:6px;
      }
    }

    @media (prefers-reduced-motion: reduce){
      *,
      *::before,
      *::after{
        animation:none !important;
        transition:none !important;
      }
    }
  </style>
</head>
<body>
  <main class="page">
    <section class="shell" role="region" aria-label="Masuk Akun Rubik Company">

      <header class="hero">
        <div class="topbar">
          <div class="brandMini">
            <div class="brandMiniLogo">
              <img src="{{ asset('logo.png') }}" alt="Rubik Company">
            </div>

            <div class="brandMiniText">
              <span>Platform Akun</span>
              <strong>Rubik Company</strong>
            </div>
          </div>

          <div class="topPill">AKB</div>
        </div>

        <div class="heroMain">
          <div class="heroLogoCard">
            <img src="{{ asset('logo.png') }}" alt="Rubik Company">
          </div>

          <div class="heroBadge">
            ✦ Akses resmi Rubik
          </div>

          <h1 class="heroTitle">
            Akses Keuangan yang <span>Bertumbuh</span>
          </h1>

          <p class="heroSub">
            Masuk ke akun untuk memantau aktivitas, melihat riwayat, dan mengelola layanan akun melalui dashboard resmi Rubik.
          </p>
        </div>

        <div class="heroStats">
          <div class="heroStat">
            <svg class="heroStatIcon" viewBox="0 0 24 24" fill="none">
              <path d="M4 19V9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M10 19V5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M16 19v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M22 19H2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <strong>Investasi</strong>
            <span>Terarah</span>
          </div>

          <div class="heroStat">
            <svg class="heroStatIcon" viewBox="0 0 24 24" fill="none">
              <path d="M4 17l6-6 4 4 6-8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M14 7h6v6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <strong>Pertumbuhan</strong>
            <span>Berkelanjutan</span>
          </div>

          <div class="heroStat">
            <svg class="heroStatIcon" viewBox="0 0 24 24" fill="none">
              <path d="M12 3l7 3v5c0 4.9-3.1 8.6-7 10-3.9-1.4-7-5.1-7-10V6l7-3z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              <path d="M8.5 11.5l2.2 2.2 4.8-5.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <strong>Akun Aman</strong>
            <span>Privasi</span>
          </div>
        </div>
      </header>

      <div class="card">

        <div class="switchTabs">
          <a href="{{ url('/undangan') }}" class="switchTab">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="2"/>
              <path d="M19 8v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M22 11h-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Daftar
          </a>

          <a href="{{ url('/login') }}" class="switchTab active">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M10 17l5-5-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M15 12H3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Masuk
          </a>
        </div>

        <section class="loginPanel">
          <div class="panelDecor"></div>

          <h1 class="title">Masuk Akun</h1>
          <p class="subtitle">
            Silakan masuk menggunakan nomor WhatsApp dan kata sandi untuk melanjutkan ke dashboard akun.
          </p>

          {{-- ERROR LOGIN --}}
          @if ($errors->any())
            <div class="error">
              {{ $errors->first() }}
            </div>
          @endif

          <form method="POST" action="{{ route('login.store') }}" autocomplete="off" novalidate>
            @csrf

            <div class="field">
              <label class="label" for="phone">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.86 19.86 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.91.32 1.8.59 2.65a2 2 0 0 1-.45 2.11L8 9.73a16 16 0 0 0 6.27 6.27l1.25-1.25a2 2 0 0 1 2.11-.45c.85.27 1.74.47 2.65.59A2 2 0 0 1 22 16.92Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Nomor WhatsApp
              </label>

              <div class="inputWrap">
                <span class="prefix62">+62</span>

                <input
                  class="input input-phone"
                  id="phone"
                  type="tel"
                  name="phone"
                  value="{{ old('phone') }}"
                  placeholder="08123456789"
                  inputmode="numeric"
                  pattern="08[0-9]{8,12}"
                  required
                />
              </div>
            </div>

            <div class="field">
              <label class="label" for="password">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="currentColor" stroke-width="2.1" stroke-linecap="round"/>
                  <path d="M6 11h12a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Kata Sandi
              </label>

              <div class="inputWrap">
                <span class="prefixIcon" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="none">
                    <path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="currentColor" stroke-width="2.1" stroke-linecap="round"/>
                    <path d="M6 11h12a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </span>

                <input
                  class="input input-password"
                  id="password"
                  type="password"
                  name="password"
                  placeholder="Masukkan kata sandi"
                  required
                />

                <button class="togglePass" type="button" onclick="togglePassword()" aria-label="Tampilkan password">
                  <svg id="eyeIcon" viewBox="0 0 24 24" fill="none">
                    <path d="M1.5 12s4-7.5 10.5-7.5S22.5 12 22.5 12 18.5 19.5 12 19.5 1.5 12 1.5 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </button>
              </div>
            </div>

            <div class="helperRow">
              <label class="rememberCheck">
                <input type="checkbox" name="remember" value="1">
                Ingat akun
              </label>

              <a class="helperLink" href="{{ url('/login') }}">Lupa sandi?</a>
            </div>

            <button class="btn" type="submit">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M10 17l5-5-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M15 12H3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
              <span>Masuk Sekarang</span>
            </button>
          </form>

          <div class="trustTiles">
            <div class="trustTile">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M12 3l2.7 5.5 6.1.9-4.4 4.3 1 6.1L12 16.9 6.6 19.8l1-6.1-4.4-4.3 6.1-.9L12 3Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              </svg>
              <strong>Keuangan</strong>
            </div>

            <div class="trustTile">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M4 17l6-6 4 4 6-8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M14 7h6v6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <strong>Pertumbuhan</strong>
            </div>

            <div class="trustTile">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M12 3l7 3v5c0 4.9-3.1 8.6-7 10-3.9-1.4-7-5.1-7-10V6l7-3z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              </svg>
              <strong>Akses Aman</strong>
            </div>
          </div>
        </section>

        <section class="legality-section">
          <div class="legality-shell">
            <h3 class="legality-title">LEGALITAS PERUSAHAAN</h3>

            <div class="legality-grid">
              <div class="legality-card">
                <div class="legality-card-top"></div>

                <div class="legality-logo-box">
                  <img src="{{ asset('assets/logos/ojk.png') }}" alt="Otoritas Jasa Keuangan" class="legality-logo legality-logo-ojk">
                </div>

                <div class="legality-name">Otoritas Jasa Keuangan</div>
                <div class="legality-badge">TERDAFTAR DI OJK</div>
              </div>

              <div class="legality-card">
                <div class="legality-card-top"></div>

                <div class="legality-logo-box">
                  <img src="{{ asset('assets/logos/bappebti.png') }}" alt="BAPPEBTI" class="legality-logo legality-logo-bappebti">
                </div>

                <div class="legality-name">BAPPEBTI</div>
                <div class="legality-badge">DIAWASI BAPPEBTI</div>
              </div>
            </div>

            <div class="legality-footer">
              <div class="legality-footer-icon">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <path d="M12 3l7 3v5c0 4.9-3.1 8.6-7 10-3.9-1.4-7-5.1-7-10V6l7-3z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                  <path d="M8.5 11.5l2.2 2.2 4.8-5.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>

              <div class="legality-footer-content">
                <strong>Akses resmi perusahaan</strong>
                <span>Pastikan login dan pendaftaran hanya melalui halaman resmi Rubik.</span>
              </div>
            </div>
          </div>
        </section>

        <div class="footer">
          Belum punya akun? <a href="{{ url('/undangan') }}">Daftar melalui halaman resmi</a>
        </div>

        <div class="copyright">
          © {{ date('Y') }} Rubik Company. Tumbuh bersama, melalui akses resmi.
        </div>
      </div>
    </section>
  </main>

  <script>
    function togglePassword(){
      const input = document.getElementById('password');
      const icon = document.getElementById('eyeIcon');

      if(!input) return;

      const isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';

      if(icon){
        icon.innerHTML = isHidden
          ? '<path d="M3 3l18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10.58 10.58A2 2 0 0 0 12 14a2 2 0 0 0 1.42-.58" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M9.88 5.09A9.77 9.77 0 0 1 12 4.86C18.5 4.86 22.5 12 22.5 12a17.56 17.56 0 0 1-3.09 4.08" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.61 6.61C3.32 8.78 1.5 12 1.5 12s4 7.14 10.5 7.14a9.9 9.9 0 0 0 4.1-.88" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'
          : '<path d="M1.5 12s4-7.5 10.5-7.5S22.5 12 22.5 12 18.5 19.5 12 19.5 1.5 12 1.5 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
      }
    }
  </script>
</body>
</html>