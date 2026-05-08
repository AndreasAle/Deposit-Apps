 @include('partials.anti-inspect')
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Penarikan Saldo | Rubik Company</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --wd-bg:#030F0F;
      --wd-panel:#071f1b;
      --wd-panel2:#0a2a23;
      --wd-text:#f7fffb;
      --wd-muted:#89a99c;
      --wd-soft:#d6fff0;
      --wd-neon:#00DF82;
      --wd-neon2:#79ff99;
      --wd-border:rgba(255,255,255,.10);
      --wd-red:#ff5b75;
      --wd-yellow:#f6c453;
      --wd-blue:#34d5ff;
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
      font-family:Inter, system-ui, -apple-system, "Segoe UI", sans-serif;
      color:var(--wd-text);
      background:
        radial-gradient(760px 420px at 14% -2%, rgba(0,223,130,.18), transparent 58%),
        radial-gradient(620px 360px at 90% 10%, rgba(90,140,255,.14), transparent 62%),
        radial-gradient(520px 300px at 55% 100%, rgba(246,196,83,.08), transparent 62%),
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
        linear-gradient(rgba(255,255,255,.022) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.016) 1px, transparent 1px);
      background-size:38px 38px;
      mask-image:linear-gradient(180deg, rgba(0,0,0,.65), transparent 76%);
      -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.65), transparent 76%);
      opacity:.46;
      z-index:0;
    }

    a{
      color:inherit;
      text-decoration:none;
    }

    button,
    input,
    select{
      font-family:inherit;
    }

    button{
      -webkit-tap-highlight-color:transparent;
    }

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

    /* =========================
       HEADER
    ========================= */
    .wd-header{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
      padding:0 2px;
    }

    .wd-brand{
      display:flex;
      align-items:center;
      gap:10px;
      min-width:0;
    }

    .wd-logo-card{
      width:48px;
      height:48px;
      border-radius:14px;
      background:
        radial-gradient(circle at 30% 10%, rgba(255,255,255,.98), rgba(224,255,242,.90));
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

    .wd-logo-card img{
      width:42px;
      height:42px;
      object-fit:contain;
      display:block;
    }

    .wd-title-wrap{
      min-width:0;
    }

    .wd-title-wrap span{
      display:block;
      margin-bottom:4px;
      color:rgba(214,255,240,.58);
      font-size:11px;
      line-height:1;
      font-weight:600;
      letter-spacing:.02em;
    }

    .wd-title-wrap h1{
      margin:0;
      font-size:23px;
      line-height:1;
      font-weight:850;
      letter-spacing:-.045em;
      color:#fff;
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
      border:1px solid rgba(255,255,255,.10);
      background:
        radial-gradient(circle at 32% 18%, rgba(255,255,255,.18), transparent 34%),
        linear-gradient(180deg, rgba(10,42,35,.96), rgba(4,18,16,.96));
      color:#fff;
      display:grid;
      place-items:center;
      box-shadow:
        0 13px 28px rgba(0,0,0,.34),
        0 0 0 1px rgba(0,223,130,.06) inset;
      transition:.18s ease;
    }

    .wd-header-btn:hover{
      transform:translateY(-1px);
      border-color:rgba(0,223,130,.24);
    }

    .wd-header-btn svg{
      width:20px;
      height:20px;
    }

    /* =========================
       HERO
    ========================= */
    .wd-hero{
      position:relative;
      overflow:hidden;
      border-radius:24px;
      background:
        radial-gradient(320px 180px at 95% 4%, rgba(90,140,255,.20), transparent 62%),
        radial-gradient(260px 170px at 8% 0%, rgba(0,223,130,.26), transparent 62%),
        radial-gradient(240px 150px at 90% 110%, rgba(246,196,83,.16), transparent 68%),
        linear-gradient(135deg, rgba(236,255,248,.96), rgba(199,255,232,.92) 48%, rgba(185,236,255,.88));
      border:1px solid rgba(255,255,255,.55);
      box-shadow:
        0 20px 44px rgba(0,0,0,.22),
        0 0 0 1px rgba(0,223,130,.14) inset,
        inset 0 1px 0 rgba(255,255,255,.72);
      padding:16px;
      margin-bottom:14px;
      animation:wdFadeUp .42s ease both;
    }

    .wd-hero::before{
      content:"";
      position:absolute;
      inset:0;
      background:
        linear-gradient(145deg, rgba(255,255,255,.48) 0%, rgba(255,255,255,.18) 27%, transparent 28%),
        linear-gradient(180deg, rgba(255,255,255,.22), rgba(255,255,255,0));
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
      color:rgba(3,24,20,.62);
      font-size:12px;
      font-weight:650;
      line-height:1.1;
    }

    .wd-hero-title{
      margin:0;
      color:#031713;
      font-size:28px;
      line-height:1.04;
      letter-spacing:-.055em;
      font-weight:900;
    }

    .wd-hero-sub{
      margin-top:10px;
      display:flex;
      align-items:center;
      gap:6px;
      color:#037e5d;
      font-size:12px;
      font-weight:760;
    }

    .wd-hero-sub span{
      color:rgba(3,24,20,.56);
      font-weight:550;
    }

    .wd-hero-pill{
      flex:0 0 auto;
      min-width:78px;
      height:38px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:6px;
      color:#05221b;
      background:rgba(255,255,255,.45);
      border:1px solid rgba(3,24,20,.10);
      box-shadow:
        0 10px 22px rgba(3,24,20,.10),
        inset 0 1px 0 rgba(255,255,255,.55);
      font-size:12px;
      font-weight:850;
      white-space:nowrap;
    }

    .wd-hero-pill svg{
      width:15px;
      height:15px;
      color:#047857;
    }

    /* =========================
       FORM CARD
    ========================= */
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
  margin-bottom:16px;
  border:1px solid rgba(255,255,255,.08);
  border-radius:22px;
  background:transparent;
  padding:18px 16px 16px;
}
  .wd-fieldset-label{
  position:absolute;
  left:16px;
  top:-9px;
  padding:0 10px;
  background:#031312;
  color:rgba(214,255,240,.78);
  font-size:12px;
  font-weight:800;
  line-height:18px;
  border-radius:999px;
}

    /* =========================
       BANK SELECTED
    ========================= */
    .wd-loader{
      color:rgba(214,255,240,.58);
      font-size:12px;
      font-weight:750;
      text-align:center;
      padding:14px;
    }

  .wd-bank-card{
  border:1px solid rgba(255,255,255,.07);
  border-radius:18px;
  overflow:hidden;
  background:transparent;
}
    .wd-bank-top{
      padding:14px;
      display:flex;
      gap:12px;
      align-items:flex-start;
    }

.wd-bank-logo{
  width:52px;
  height:52px;
  min-width:52px;
  flex:0 0 52px;

  border-radius:17px;
  display:flex;
  align-items:center;
  justify-content:center;

  background:#ffffff;
  border:1px solid rgba(255,255,255,.22);
  overflow:hidden;

  box-shadow:
    inset 0 1px 0 rgba(255,255,255,.70),
    0 12px 24px rgba(0,0,0,.18);
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
  color:#06110e;
  font-size:11px;
  font-weight:950;
  line-height:1;
}
    .wd-bank-label{
      color:rgba(214,255,240,.58);
      font-size:10px;
      font-weight:850;
      text-transform:uppercase;
      letter-spacing:.06em;
    }

    .wd-bank-name{
      margin-top:3px;
      color:#fff;
      font-size:16px;
      font-weight:950;
      letter-spacing:-.03em;
    }

    .wd-bank-bottom{
      display:grid;
      grid-template-columns:1fr 1fr;
      border-top:1px solid rgba(255,255,255,.08);
      background:rgba(255,255,255,.025);
    }

    .wd-bank-info{
      padding:12px 14px;
      min-width:0;
    }

    .wd-bank-info + .wd-bank-info{
      border-left:1px solid rgba(255,255,255,.08);
    }

    .wd-bank-info span{
      display:block;
      color:rgba(214,255,240,.55);
      font-size:10.5px;
      font-weight:750;
      margin-bottom:5px;
    }

    .wd-bank-info strong{
      display:block;
      color:#fff;
      font-size:14px;
      font-weight:900;
      letter-spacing:-.02em;
      overflow:hidden;
      text-overflow:ellipsis;
      white-space:nowrap;
    }

    .wd-bank-change{
      padding:10px 14px 13px;
      border-top:1px solid rgba(255,255,255,.08);
      background:rgba(255,255,255,.025);
      text-align:center;
    }

    .wd-bank-change a{
      min-height:34px;
      padding:0 12px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      color:#00DF82;
      background:rgba(0,223,130,.08);
      border:1px solid rgba(0,223,130,.18);
      text-decoration:none;
      font-size:11.5px;
      font-weight:900;
    }

    .wd-bank-empty{
      padding:16px 14px;
      border-radius:16px;
      border:1px dashed rgba(0,223,130,.24);
      background:
        radial-gradient(180px 90px at 90% 0%, rgba(0,223,130,.10), transparent 64%),
        rgba(255,255,255,.035);
      color:rgba(214,255,240,.68);
      text-align:center;
    }

    .wd-bank-empty-title{
      color:#ffffff;
      font-size:13px;
      font-weight:950;
      letter-spacing:-.02em;
    }

    .wd-bank-empty-desc{
      margin:7px auto 12px;
      max-width:280px;
      color:rgba(214,255,240,.58);
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
      color:#06110d !important;
      text-decoration:none !important;
      background:
        radial-gradient(circle at 30% 0%,rgba(255,255,255,.55),transparent 34%),
        linear-gradient(135deg,#00DF82 0%,#79ff99 100%);
      box-shadow:
        0 14px 28px rgba(0,223,130,.18),
        0 0 0 1px rgba(255,255,255,.22) inset;
      font-size:12.5px;
      font-weight:950;
    }

    /* =========================
       AMOUNT
    ========================= */
.wd-amount-box{
  min-height:102px;
  display:flex;
  align-items:center;
  gap:10px;
  padding:10px 4px 4px;
}

    .wd-rp{
  color:rgba(255,255,255,.82);
  font-size:33px;
  line-height:1;
  font-weight:600;
  letter-spacing:-.055em;
  flex:0 0 auto;
}

.wd-amount-input{
  width:100%;
  min-width:0;
  border:0;
  outline:0;
  background:transparent;
  color:#fff;
  font-size:33px;
  line-height:1;
  font-weight:700;
  letter-spacing:-.05em;
  padding:0;
}

    .wd-amount-input::placeholder{
      color:rgba(255,255,255,.42);
    }

    .wd-clear{
      width:34px;
      height:34px;
      border-radius:999px;
      border:1px solid rgba(255,255,255,.10);
      background:rgba(255,255,255,.06);
      color:rgba(255,255,255,.82);
      display:grid;
      place-items:center;
      cursor:pointer;
      flex:0 0 auto;
    }

    .wd-clear svg{
      width:18px;
    }

    .wd-presets{
      display:flex;
      flex-wrap:wrap;
      gap:8px;
      margin:8px 0 18px;
    }

    .wd-preset{
      border:1px solid rgba(255,255,255,.10);
      background:linear-gradient(180deg,rgba(255,255,255,.08),rgba(255,255,255,.035));
      color:#fff;
      min-height:36px;
      padding:0 13px;
      border-radius:999px;
      font-size:12px;
      font-weight:850;
      cursor:pointer;
      box-shadow:0 10px 20px rgba(0,0,0,.14);
      transition:.18s ease;
    }

    .wd-preset:hover{
      transform:translateY(-1px);
    }

    .wd-preset.is-active{
      color:#06110d;
      background:linear-gradient(135deg,#00DF82,#79ff99);
      border-color:rgba(255,255,255,.22);
      box-shadow:0 12px 26px rgba(0,223,130,.20);
    }

    .wd-limit{
      display:grid;
      gap:9px;
      margin:0 0 18px;
    }

    .wd-limit-row{
      display:flex;
      justify-content:space-between;
      gap:12px;
      color:rgba(214,255,240,.70);
      font-size:12px;
      font-weight:650;
    }

    .wd-limit-row strong{
      color:#fff;
      font-weight:950;
    }

.wd-received{
  border-radius:18px;
  background:transparent;
  border:1px solid rgba(255,255,255,.08);
  padding:16px;
  margin-bottom:14px;
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:12px;
}
    .wd-received-label{
      color:rgba(214,255,240,.72);
      font-size:12px;
      font-weight:750;
    }

    .wd-received-right{
      text-align:right;
    }

    .wd-received-amount{
      display:block;
      color:#00DF82;
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
      font-weight:800;
      margin-bottom:4px;
    }

    .wd-error:empty{
      display:none;
    }

    /* =========================
       HISTORY
    ========================= */
    .wd-history{
      margin-top:18px;
    }

    .wd-history-head{
      display:flex;
      justify-content:space-between;
      align-items:flex-end;
      margin-bottom:10px;
      padding:0 2px;
    }

    .wd-history-title{
      margin:0;
      color:#fff;
      font-size:16px;
      font-weight:850;
      letter-spacing:-.025em;
    }

    .wd-history-sub{
      color:rgba(214,255,240,.55);
      font-size:11px;
      font-weight:750;
    }

    .wd-history-list{
      display:flex;
      flex-direction:column;
      gap:8px;
    }

    .wd-history-item{
      border-radius:18px;
      border:1px solid rgba(255,255,255,.08);
      background:
        radial-gradient(170px 94px at 88% 8%, rgba(0,223,130,.08), transparent 64%),
        linear-gradient(180deg, rgba(13,35,34,.80), rgba(6,20,19,.90));
      padding:12px;
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
      box-shadow:0 12px 22px rgba(0,0,0,.18);
    }

    .wd-history-amount{
      color:#fff;
      font-size:13px;
      font-weight:950;
    }

    .wd-history-date{
      margin-top:4px;
      color:rgba(214,255,240,.52);
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

    .wd-status.is-paid{
      color:#06110d;
      background:linear-gradient(135deg,#00DF82,#8cff2f);
    }

    .wd-status.is-pending{
      color:#fff0c7;
      background:rgba(246,196,83,.12);
      border:1px solid rgba(246,196,83,.22);
    }

    .wd-status.is-rejected{
      color:#ffd7df;
      background:rgba(255,91,117,.10);
      border:1px solid rgba(255,91,117,.22);
    }

    .wd-status.is-processing{
  color:#34d5ff;
  background:rgba(52,213,255,.10);
  border:1px solid rgba(52,213,255,.22);
}

    .wd-empty{
      border-radius:16px;
      border:1px dashed rgba(255,255,255,.14);
      background:rgba(255,255,255,.035);
      padding:14px;
      color:rgba(214,255,240,.58);
      text-align:center;
      font-size:12px;
      font-weight:750;
    }

    .wd-cancel{
      margin-top:8px;
      border:0;
      border-radius:999px;
      min-height:28px;
      padding:0 10px;
      color:#ffd7df;
      background:rgba(255,91,117,.10);
      border:1px solid rgba(255,91,117,.22);
      font-size:10.5px;
      font-weight:900;
      cursor:pointer;
    }

    /* =========================
       BOTTOM ACTION
    ========================= */
    .wd-bottom{
      position:fixed;
      left:50%;
      bottom:0;
      transform:translateX(-50%);
      z-index:50;
      width:min(100%,430px);
      padding:12px 14px calc(14px + env(safe-area-inset-bottom));
      background:
        linear-gradient(180deg, rgba(3,15,15,0), rgba(3,15,15,.92) 26%, rgba(3,15,15,.98));
      pointer-events:none;
    }

    .wd-submit{
      width:100%;
      min-height:50px;
      border:0;
      border-radius:999px;
      color:#06110d;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.55), transparent 34%),
        linear-gradient(135deg,#00DF82 0%,#79ff99 100%);
      box-shadow:
        0 18px 38px rgba(0,223,130,.24),
        0 0 0 1px rgba(255,255,255,.22) inset;
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

    /* =========================
       TOAST
    ========================= */
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
      color:#06110e;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.62), transparent 34%),
        linear-gradient(135deg, #00DF82, #72ffab);
      box-shadow:
        0 18px 42px rgba(0,0,0,.32),
        0 0 28px rgba(0,223,130,.18);
      font-size:12px;
      font-weight:850;
      transition:.22s ease;
      white-space:nowrap;
    }

    .wd-toast.is-error{
      color:#fff;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.18), transparent 34%),
        linear-gradient(135deg, #ff5b75, #fb923c);
    }

    .wd-toast.show{
      opacity:1;
      transform:translateX(-50%) translateY(0);
    }

    @keyframes wdFadeUp{
      from{
        opacity:0;
        transform:translateY(10px);
      }
      to{
        opacity:1;
        transform:translateY(0);
      }
    }

    @media(min-width:768px){
      .wd-page{
        padding:22px 0;
      }

      .wd-phone{
        min-height:calc(100vh - 44px);
        border-radius:26px;
        overflow:hidden;
      }

      .wd-bottom{
        bottom:22px;
        border-radius:0 0 26px 26px;
      }
    }

    @media(max-width:370px){
      .wd-logo-card{
        width:44px;
        height:44px;
      }

      .wd-logo-card img{
        width:38px;
        height:38px;
      }

      .wd-title-wrap h1{
        font-size:21px;
      }

      .wd-hero{
        padding:15px;
      }

      .wd-hero-title{
        font-size:25px;
      }

      .wd-hero-pill{
        min-width:70px;
        height:36px;
        font-size:11px;
      }

      .wd-rp,
      .wd-amount-input{
        font-size:28px;
      }

      .wd-preset{
        font-size:11.5px;
        padding:0 11px;
      }

      .wd-bank-bottom{
        grid-template-columns:1fr;
      }

      .wd-bank-info + .wd-bank-info{
        border-left:0;
        border-top:1px solid rgba(255,255,255,.08);
      }
    }

    .wd-hero-balance{
  margin-top:7px;
  color:#031713;
  font-size:22px;
  line-height:1;
  font-weight:950;
  letter-spacing:-.045em;
}

.wd-hero-balance-caption{
  margin-top:5px;
  color:rgba(3,24,20,.56);
  font-size:11px;
  font-weight:750;
}

.wd-available-card{
  margin-bottom:14px;
  border-radius:20px;
  padding:14px 15px;
  background:
    radial-gradient(220px 110px at 90% 0%, rgba(0,223,130,.12), transparent 65%),
    linear-gradient(180deg, rgba(10,42,35,.92), rgba(4,18,16,.94));
  border:1px solid rgba(255,255,255,.09);
  box-shadow:
    0 14px 26px rgba(0,0,0,.22),
    0 0 0 1px rgba(0,223,130,.06) inset;
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:10px;
}

.wd-available-item span{
  display:block;
  color:rgba(214,255,240,.58);
  font-size:10.5px;
  font-weight:800;
  margin-bottom:5px;
}

.wd-available-item strong{
  display:block;
  color:#fff;
  font-size:15px;
  font-weight:950;
  letter-spacing:-.025em;
}

.wd-available-item.is-main strong{
  color:#00DF82;
}

.wd-received-left{
  display:grid;
  gap:4px;
}

.wd-received-note{
  display:block;
  color:rgba(214,255,240,.46);
  font-size:10.5px;
  font-weight:700;
  line-height:1.35;
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
            <img src="{{ asset('logo.png') }}" alt="Rubik Company">
          </div>

          <div class="wd-title-wrap">
            <span>Penarikan dana</span>
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