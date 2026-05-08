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
  <title>Dashboard | Rubik Company</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --rb-bg:#030F0F;
      --rb-bg2:#061817;
      --rb-surface:#071f1b;
      --rb-surface2:#092a23;
      --rb-card:#071915;
      --rb-card-soft:rgba(7,31,27,.76);
      --rb-text:#f7fffb;
      --rb-text-dark:#071211;
      --rb-muted:#9bb9ad;
      --rb-muted2:#6f9084;
      --rb-border:rgba(255,255,255,.09);

      --rb-primary:#03624C;
      --rb-dark:#030F0F;
      --rb-neon:#00DF82;
      --rb-neon2:#58ffad;
      --rb-green-soft:#0b8f69;
      --rb-red:#ff4f6d;
      --rb-yellow:#f6c453;
      --rb-blue:#1fd3a4;

      --rb-shadow:0 28px 70px rgba(0,0,0,.46);
      --rb-shadow-soft:0 16px 34px rgba(0,0,0,.24);
      --rb-glow:0 0 0 1px rgba(0,223,130,.14), 0 18px 50px rgba(0,223,130,.10);
      --rb-radius:24px;
      --rb-radius-sm:18px;
    }

    *{ box-sizing:border-box; }

    html{
      scroll-behavior:smooth;
    }

    html,body{
      min-height:100%;
    }

    body{
      margin:0;
      font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
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
    }

    button,
    input{
      font-family:inherit;
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
       TOP HEADER - PREMIUM DARK
    ========================= */
    .rb-topbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:16px;
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
      border-radius:13px;
      background:
        linear-gradient(180deg, rgba(255,255,255,.94), rgba(224,255,242,.90));
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
      width:42px;
      height:42px;
      object-fit:contain;
      display:block;
    }

    .rb-welcome{
      min-width:0;
      display:flex;
      align-items:center;
    }

    .rb-welcome h1{
      margin:0;
      font-size:24px;
      line-height:1;
      font-weight:950;
      letter-spacing:-.04em;
      color:#ffffff;
      text-transform:uppercase;
      white-space:nowrap;
      text-shadow:0 10px 28px rgba(0,0,0,.28);
    }

    .rb-welcome p{
      display:none;
    }

    .rb-header-actions{
      display:flex;
      align-items:center;
      gap:10px;
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
      text-decoration:none;
      box-shadow:
        0 13px 28px rgba(0,0,0,.34),
        0 0 0 1px rgba(0,223,130,.06) inset;
      position:relative;
      cursor:pointer;
      -webkit-tap-highlight-color:transparent;
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

    .rb-header-btn.is-profile{
      overflow:hidden;
      background:
        radial-gradient(circle at 50% 28%, rgba(255,255,255,.18), transparent 30%),
        linear-gradient(180deg, #0d3028, #061714);
      border:1px solid rgba(0,223,130,.18);
    }

    .rb-header-avatar{
      width:42px;
      height:42px;
      border-radius:999px;
      display:grid;
      place-items:center;
      box-shadow:inset 0 1px 0 rgba(255,255,255,.12);
      color:#c9fff0;
    }

    .rb-header-avatar svg{
      width:31px;
      height:31px;
      color:#c9fff0;
      opacity:.92;
      filter:drop-shadow(0 3px 8px rgba(0,0,0,.25));
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

    @media (max-width:370px){
      .rb-logo-card{
        width:44px;
        height:44px;
        border-radius:12px;
      }

      .rb-logo-card img{
        width:38px;
        height:38px;
      }

      .rb-welcome h1{
        font-size:22px;
      }

      .rb-header-btn{
        width:39px;
        height:39px;
      }

      .rb-header-avatar{
        width:39px;
        height:39px;
      }
    }

    .rb-errors{
      margin:0 0 14px;
      padding:13px;
      border-radius:18px;
      background:rgba(80,10,22,.70);
      border:1px solid rgba(255,79,109,.26);
      color:#ffd7df;
      box-shadow:var(--rb-shadow-soft);
      font-size:12.5px;
      font-weight:700;
      line-height:1.45;
    }

    .rb-errors-title{
      margin:0 0 6px;
      font-size:13px;
      font-weight:900;
    }

    .rb-errors ul{
      margin:0;
      padding-left:18px;
    }

    /* =========================
       HERO BALANCE - PREMIUM GREEN BLACK
    ========================= */
    .rb-hero{
      position:relative;
      overflow:hidden;
      border-radius:22px;
      background:#030F0F;
      border:1px solid rgba(255,255,255,.09);
      box-shadow:
        0 18px 44px rgba(0,0,0,.42),
        inset 0 1px 0 rgba(255,255,255,.08),
        0 0 0 1px rgba(0,223,130,.08);
      padding:0;
    }

    .rb-hero::before{
      content:"";
      position:absolute;
      inset:0;
      background:
        linear-gradient(152deg, rgba(255,255,255,.12) 0%, rgba(255,255,255,.06) 28%, transparent 29%),
        radial-gradient(300px 180px at 92% 105%, rgba(0,223,130,.22), transparent 62%),
        radial-gradient(240px 170px at 18% 0%, rgba(3,98,76,.46), transparent 70%),
        linear-gradient(180deg, #10251f 0%, #041311 55%, #020908 100%);
      pointer-events:none;
    }

    .rb-hero::after{
      content:"";
      position:absolute;
      right:-60px;
      bottom:-80px;
      width:220px;
      height:220px;
      border-radius:50%;
      background:rgba(0,223,130,.14);
      filter:blur(24px);
      pointer-events:none;
    }

    .rb-hero-inner{
      position:relative;
      z-index:1;
      padding:17px 17px 15px;
    }

    .rb-balance-grid{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:0;
    }

    .rb-balance-box{
      min-width:0;
      padding:0 0 18px;
    }

    .rb-balance-box:nth-child(2){
      padding-left:10px;
    }

    .rb-balance-label{
      margin:0 0 5px;
      font-size:12px;
      line-height:1.15;
      font-weight:650;
      color:rgba(236,255,248,.72);
    }

    .rb-balance{
      margin:0;
      font-size:20px;
      line-height:1.05;
      letter-spacing:-.035em;
      color:#ffffff;
      font-weight:950;
      text-shadow:0 8px 20px rgba(0,0,0,.28);
    }

    .rb-balance-bottom{
      display:grid;
      grid-template-columns:1fr 1fr;
      align-items:end;
      gap:0;
    }

    .rb-balance-small{
      min-width:0;
    }

    .rb-balance-small .rb-balance-label{
      margin-bottom:5px;
      font-size:11.5px;
      color:rgba(236,255,248,.80);
    }

    .rb-balance-small .rb-balance{
      font-size:18px;
    }

    .rb-account-status{
      justify-self:end;
      display:inline-flex;
      align-items:center;
      gap:6px;
      padding:6px 11px;
      border-radius:999px;
      background:
        radial-gradient(circle at 20% 15%, rgba(255,255,255,.26), transparent 36%),
        linear-gradient(135deg, #03624C, #00A86B);
      color:#ffffff;
      font-size:12px;
      font-weight:950;
      letter-spacing:.05em;
      text-transform:uppercase;
      box-shadow:
        0 10px 22px rgba(0,223,130,.19),
        inset 0 1px 0 rgba(255,255,255,.18);
    }

    .rb-account-status::before{
      content:"";
      width:6px;
      height:6px;
      border-radius:999px;
      background:#ccfff0;
      box-shadow:
        0 0 0 3px rgba(204,255,240,.14),
        0 0 14px rgba(204,255,240,.52);
    }

    /* =========================
       HERO ACTION BUTTONS
    ========================= */
    .rb-main-actions{
      margin-top:10px;
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:10px;
    }

    .rb-main-action{
      min-height:48px;
      border-radius:999px;
      text-decoration:none;
      display:flex;
      align-items:center;
      justify-content:center;
      gap:9px;
      font-size:15px;
      font-weight:900;
      letter-spacing:-.01em;
      transition:transform .18s ease, box-shadow .18s ease, filter .18s ease;
      -webkit-tap-highlight-color:transparent;
    }

    .rb-main-action svg{
      width:19px;
      height:19px;
    }

    .rb-main-action:hover{
      transform:translateY(-1px);
    }

    .rb-main-action.is-deposit{
      color:#ffffff;
      background:
        linear-gradient(180deg, rgba(255,255,255,.13), rgba(255,255,255,0) 45%),
        linear-gradient(135deg, #182522, #030F0F);
      border:1px solid rgba(255,255,255,.14);
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,.14),
        0 12px 26px rgba(0,0,0,.34);
    }

    .rb-main-action.is-withdraw{
      color:#02110c;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.48), transparent 34%),
        linear-gradient(135deg, #00DF82 0%, #6dff98 100%);
      border:1px solid rgba(255,255,255,.28);
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,.36),
        0 14px 30px rgba(0,223,130,.28),
        0 0 34px rgba(0,223,130,.16);
    }

    .rb-main-action.is-withdraw:hover{
      filter:brightness(1.04);
    }

    @media (max-width:370px){
      .rb-hero-inner{
        padding:15px 15px 14px;
      }

      .rb-balance-label{
        font-size:11px;
      }

      .rb-balance{
        font-size:18px;
      }

      .rb-balance-small .rb-balance{
        font-size:16px;
      }

      .rb-account-status{
        font-size:11px;
        padding:5px 9px;
      }

      .rb-main-action{
        min-height:46px;
        font-size:14px;
      }
    }
/* =========================
   DASHBOARD PROMO SLIDER
   Full width + gambar tidak kepotong
========================= */
.rb-dashboard-slider{
  margin-top:0 !important;
  margin-bottom:14px;
width:calc(100% + 28px);
margin-left:-14px;
margin-right:-14px;
  position:relative;
}

.rb-dashboard-slider-viewport{
  width:100%;
  overflow:hidden;
  border-radius:0;
  position:relative;
  aspect-ratio:9 / 4;
  background:#123b2b;
  border:1px solid rgba(0,223,130,.22);
}

.rb-dashboard-slider-track{
  display:flex;
  width:100%;
  height:100%;
  will-change:transform;
  transition:transform .48s cubic-bezier(.22,.8,.22,1);
}

.rb-dashboard-slide{
  flex:0 0 100%;
  width:100%;
  height:100%;
  display:block;
  text-decoration:none;
  -webkit-tap-highlight-color:transparent;
}

.rb-dashboard-slide-img{
  display:block;
  width:100%;
  height:100%;
  object-fit:cover;
  object-position:center center;
  border-radius:0;
  background:#123b2b;
}

.rb-dashboard-slider-viewport::before{
  content:none !important;
}

.rb-dashboard-dots{
  position:absolute;
  left:50%;
  bottom:7px;
  transform:translateX(-50%);
  z-index:5;

  display:flex;
  align-items:center;
  justify-content:center;
  gap:5px;

  padding:4px 7px;
  border-radius:999px;
  background:rgba(3,15,15,.42);
  border:1px solid rgba(255,255,255,.10);
  backdrop-filter:blur(8px);
  -webkit-backdrop-filter:blur(8px);
}

.rb-dashboard-dot{
  width:6px;
  height:6px;
  border:0;
  border-radius:999px;
  padding:0;
  cursor:pointer;
  background:rgba(255,255,255,.42);
  transition:
    width .22s ease,
    background .22s ease,
    transform .22s ease;
}

.rb-dashboard-dot.active{
  width:16px;
  background:#00DF82;
  box-shadow:0 0 12px rgba(0,223,130,.45);
}

.rb-dashboard-slider:hover .rb-dashboard-slider-viewport{
  border-color:rgba(0,223,130,.34);
  box-shadow:
    0 20px 40px rgba(0,0,0,.30),
    0 0 0 1px rgba(255,255,255,.06) inset,
    0 0 34px rgba(0,223,130,.14);
}

@media (max-width:430px){
  .rb-dashboard-slider{
    margin-top:0 !important;
    margin-bottom:13px;
  }

  .rb-dashboard-slider-viewport,
  .rb-dashboard-slide-img{
  }
}

@media (max-width:370px){
  .rb-dashboard-slider{
    margin-top:0 !important;
    margin-bottom:12px;
  }

  .rb-dashboard-slider-viewport,
  .rb-dashboard-slide-img{
  }

  .rb-dashboard-dots{
    bottom:6px;
  }
}

.rb-dashboard-slider-viewport{
  touch-action: pan-y;
}

.rb-dashboard-slider-track{
  cursor: grab;
}

.rb-dashboard-slider-track.is-dragging{
  cursor: grabbing;
  transition: none !important;
}
/* =========================
   LICENSE TRUST STRIP - PREMIUM MINT GLASS
========================= */
.rb-license{
  margin-top:14px;
  margin-bottom:14px;

  min-height:58px;
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:12px;

  padding:8px 14px;
  border-radius:18px;

  background:
    radial-gradient(circle at 12% 0%, rgba(255,255,255,.95), transparent 34%),
    linear-gradient(135deg, rgba(239,255,249,.94), rgba(205,255,232,.86));

  border:1px solid rgba(0,223,130,.20);

  box-shadow:
    0 16px 34px rgba(0,0,0,.20),
    0 0 0 1px rgba(255,255,255,.50) inset,
    0 0 30px rgba(0,223,130,.10);

  backdrop-filter:blur(14px);
  -webkit-backdrop-filter:blur(14px);
}

.rb-license-left{
  flex:0 0 auto;
  color:#041b16;
  font-size:12.5px;
  line-height:1;
  font-weight:900;
  white-space:nowrap;
  letter-spacing:-.02em;
  text-shadow:none;
}

.rb-license-right{
  flex:1 1 auto;
  min-width:0;
  display:flex;
  align-items:center;
  justify-content:flex-end;
  gap:10px;
  overflow:visible;
}

.rb-license-logo{
  display:block;
  width:auto !important;
  object-fit:contain;
  object-position:center;
  flex:0 0 auto;
  max-width:none !important;

  filter:
    drop-shadow(0 3px 5px rgba(3,15,15,.10))
    saturate(1.08)
    contrast(1.02);
}

/* Atur besar logo dari sini */
.rb-license-logo-ojk{
  height:46px !important;
}

.rb-license-logo-bappebti{
  height:38px !important;
}

@media (max-width:430px){
  .rb-license{
    margin-top:14px;
    margin-bottom:14px;
    min-height:56px;
    padding:8px 12px;
    gap:10px;
    border-radius:17px;
  }

  .rb-license-left{
    font-size:12px;
  }

  .rb-license-right{
    gap:8px;
  }

  .rb-license-logo-ojk{
    height:44px !important;
  }

  .rb-license-logo-bappebti{
    height:36px !important;
  }
}

@media (max-width:370px){
  .rb-license{
    min-height:52px;
    padding:7px 10px;
    gap:8px;
    border-radius:16px;
  }

  .rb-license-left{
    font-size:11.3px;
  }

  .rb-license-right{
    gap:7px;
  }

  .rb-license-logo-ojk{
    height:39px !important;
  }

  .rb-license-logo-bappebti{
    height:32px !important;
  }
}
/* =========================
   INVITE BONUS BANNER
   posisi: di bawah rb-license
========================= */
.rb-invite-banner-section{
  margin:0 0 16px;
  position:relative;
}

.rb-invite-slider{
  position:relative;
  width:100%;
}

.rb-invite-slider-viewport{
  overflow:hidden;
  width:100%;
}

.rb-invite-slider-track{
  display:flex;
  width:100%;
  transition:transform .45s cubic-bezier(.22,.8,.22,1);
  will-change:transform;
}

.rb-invite-slide{
  flex:0 0 100%;
  width:100%;
}

.rb-invite-card{
  position:relative;
  overflow:hidden;
  min-height:148px;
  border-radius:24px;
  background:
    radial-gradient(circle at 12% 0%, rgba(255,255,255,.95), transparent 34%),
    linear-gradient(135deg, rgba(239,255,249,.94), rgba(205,255,232,.86));
  border:1px solid rgba(0,223,130,.20);
  box-shadow:
    0 16px 34px rgba(0,0,0,.20),
    0 0 0 1px rgba(255,255,255,.50) inset,
    0 0 30px rgba(0,223,130,.10);
  display:grid;
  grid-template-columns:minmax(0, 1fr) 154px;
  gap:4px;
  padding:18px 12px 16px 22px;
  text-decoration:none;
  backdrop-filter:blur(14px);
  -webkit-backdrop-filter:blur(14px);
}

.rb-invite-card::before{
  content:"";
  position:absolute;
  left:22px;
  top:10px;
  width:22px;
  height:22px;
  background:#00DF82;
  clip-path:polygon(50% 0%, 61% 38%, 100% 50%, 61% 62%, 50% 100%, 39% 62%, 0% 50%, 39% 38%);
  opacity:.95;
  z-index:3;
  filter:drop-shadow(0 0 12px rgba(0,223,130,.42));
}

.rb-invite-content{
  position:relative;
  z-index:4;
  display:flex;
  flex-direction:column;
  justify-content:center;
  min-width:0;
  padding-left:0;
}

.rb-invite-title{
  margin:0;
  color:#041b16;
  font-size:15px;
  line-height:1.12;
  font-weight:950;
  letter-spacing:-.045em;
  max-width:180px;
  text-shadow:none;
}
.rb-invite-title .highlight{
  color:#00C97A;
  white-space:nowrap;
  text-shadow:none;
}
.rb-invite-btn{
  margin-top:14px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  min-width:150px;
  height:42px;
  padding:0 24px;
  border-radius:999px;
  background:
    radial-gradient(circle at 30% 0%, rgba(255,255,255,.42), transparent 34%),
    linear-gradient(135deg, #00DF82 0%, #03624C 100%);
  color:#02110c;
  border:1px solid rgba(255,255,255,.24);
  font-size:15px;
  line-height:1;
  font-weight:950;
  box-shadow:
    0 14px 28px rgba(0,223,130,.22),
    inset 0 1px 0 rgba(255,255,255,.30);
  position:relative;
}

.rb-invite-btn::after{
  content:"";
  position:absolute;
  right:12px;
  top:50%;
  width:17px;
  height:17px;
  transform:translateY(-50%);
  background:#ccfff0;
  clip-path:polygon(50% 0%, 61% 38%, 100% 50%, 61% 62%, 50% 100%, 39% 62%, 0% 50%, 39% 38%);
  box-shadow:0 0 14px rgba(204,255,240,.42);
}

.rb-invite-visual{
  position:absolute;
  right:-6px;
  bottom:-10px;
  width:210px;
  height:162px;
  z-index:2;
  display:flex;
  align-items:flex-end;
  justify-content:flex-end;
  pointer-events:none;
}

.rb-invite-visual::before{
  content:"";
  position:absolute;
  right:48px;
  bottom:-54px;
  width:172px;
  height:172px;
  border-radius:50%;
 background:rgba(0,223,130,.24);
  z-index:0;
}

.rb-invite-visual::after{
  content:"";
  position:absolute;
  right:-38px;
  bottom:-64px;
  width:172px;
  height:116px;
  border-radius:52% 48% 0 0 / 70% 62% 0 0;
  background:rgba(3,98,76,.70);
  z-index:0;
}

.rb-invite-hero{
  position:absolute;
  right:-6px;
  bottom:4px;
  z-index:3;
  width:180px;
  height:142px;
  max-width:none;
  max-height:none;
  object-fit:contain;
  object-position:right bottom;
  display:block;
  transform:scale(1.34);
  transform-origin:right bottom;
}

.rb-invite-deco{
  position:absolute;
  pointer-events:none;
  z-index:3;
}

.rb-invite-deco.is-star{
  display:none;
}

.rb-invite-deco.is-coin{
  position:absolute;
  width:24px;
  height:24px;
  border-radius:999px;
background:radial-gradient(circle at 35% 35%, #ccfff0 0 28%, #00DF82 29% 100%);
border:2px solid rgba(0,223,130,.45);
  box-shadow:0 6px 10px rgba(0,0,0,.10);
}

.rb-invite-deco.coin-1{
  right:12px;
  bottom:20px;
}

.rb-invite-deco.coin-2{
  right:72px;
  bottom:5px;
  width:20px;
  height:20px;
}

/* panah disembunyikan total kalau masih ada sisa button lama */
.rb-invite-nav{
  display:none !important;
}

.rb-invite-dots{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:6px;
  margin-top:10px;
}

.rb-invite-dot{
  width:7px;
  height:7px;
  border-radius:999px;
  background:rgba(255,255,255,.28);
  border:0;
  padding:0;
  cursor:pointer;
  transition:all .2s ease;
}

.rb-invite-dot.active{
  width:18px;
  background:#00DF82;
  box-shadow:0 0 12px rgba(0,223,130,.38);
}

@media (max-width:430px){
  .rb-invite-card{
    min-height:140px;
    border-radius:22px;
    padding:17px 10px 15px 22px;
    grid-template-columns:minmax(0, 1fr) 142px;
  }

  .rb-invite-title{
    font-size:14px;
    max-width:165px;
  }

  .rb-invite-btn{
    min-width:144px;
    height:40px;
    font-size:14.5px;
    margin-top:13px;
  }

  .rb-invite-visual{
    right:-10px;
    bottom:-10px;
    width:205px;
    height:156px;
  }
  .rb-invite-visual::before{
    width:164px;
    height:164px;
    right:42px;
    bottom:-54px;
  }

  .rb-invite-visual::after{
    width:166px;
    height:112px;
    right:-42px;
    bottom:-62px;
  }

   .rb-invite-hero{
    width:250px;
    height:136px;
    right:-90px;
    bottom:-15px;
    transform:scale(1.38);
  }

  .rb-invite-deco.coin-1{
    right:10px;
    bottom:18px;
    width:23px;
    height:23px;
  }

  .rb-invite-deco.coin-2{
    right:67px;
    bottom:4px;
    width:19px;
    height:19px;
  }
}

@media (max-width:370px){
  .rb-invite-card{
    min-height:132px;
    padding:15px 8px 13px 20px;
    grid-template-columns:minmax(0, 1fr) 128px;
    border-radius:21px;
  }

  .rb-invite-title{
    font-size:13.2px;
    max-width:150px;
  }

  .rb-invite-btn{
    min-width:132px;
    height:37px;
    font-size:13.5px;
    margin-top:11px;
  }

  .rb-invite-visual{
    width:166px;
    height:132px;
  }

  .rb-invite-hero{
    width:136px;
    height:108px;
    transform:scale(1.22);
  }

  .rb-invite-visual::before{
    width:148px;
    height:148px;
    right:38px;
    bottom:-50px;
  }

  .rb-invite-visual::after{
    width:148px;
    height:102px;
    right:-36px;
    bottom:-56px;
  }
}


    .rb-section{
      margin-top:16px;
    }

    .rb-section-head{
      display:flex;
      align-items:flex-end;
      justify-content:space-between;
      gap:12px;
      margin-bottom:12px;
    }

    .rb-section-title{
      min-width:0;
    }

    .rb-section-title h2{
      margin:0;
      font-size:15px;
      line-height:1.15;
      letter-spacing:-.025em;
      color:#ffffff;
      font-weight:700;
      text-shadow:0 10px 24px rgba(0,0,0,.28);
    }

    .rb-section-title p{
      margin:5px 0 0;
      font-size:9.5px;
      color:rgba(214,255,240,.70);
      font-weight:400;
    }

    .rb-see-all{
      display:inline-flex;
      align-items:center;
      gap:5px;
      color:var(--rb-neon);
      font-size:11.5px;
      font-weight:950;
      text-decoration:none;
      white-space:nowrap;
      text-shadow:0 0 18px rgba(0,223,130,.18);
    }

 /* =========================
   CATEGORY CARDS - REFERENCE STYLE
   House / Travel / New Cars look
========================= */
.rb-category-slider-wrap{
  width:100%;
  overflow:hidden;
  margin:10px 0 16px;
  position:relative;
}

.rb-category-slider-wrap::before,
.rb-category-slider-wrap::after{
  content:"";
  position:absolute;
  top:0;
  bottom:0;
  width:18px;
  z-index:2;
  pointer-events:none;
}

.rb-category-slider-wrap::before{
  left:0;
  background:linear-gradient(90deg, rgba(3,15,15,1), rgba(3,15,15,0));
}

.rb-category-slider-wrap::after{
  right:0;
  background:linear-gradient(270deg, rgba(3,15,15,1), rgba(3,15,15,0));
}

.rb-category-slider{
  display:flex;
  gap:9px;
  overflow:visible;
  scroll-behavior:auto !important;
  -webkit-overflow-scrolling:auto;
  padding:2px 4px 7px;
  scrollbar-width:none;
  will-change:transform;
  transform:translate3d(0,0,0);
  touch-action:pan-y;
  cursor:grab;
}

.rb-category-slider.is-dragging{
  cursor:grabbing;
  transition:none !important;
}

.rb-category-slider::-webkit-scrollbar{
  display:none;
}

.rb-category-card{
  appearance:none;
  border:0;
  flex:0 0 104px;
  height:110px;
  border-radius:16px;
  padding:0;
  overflow:hidden;
  cursor:pointer;
  position:relative;
  text-align:left;
  color:#ffffff;
  background:
    radial-gradient(circle at 82% 8%, rgba(255,255,255,.055), transparent 32%),
    linear-gradient(180deg, rgba(30,35,39,.96) 0%, rgba(22,27,30,.98) 100%);
  box-shadow:
    0 12px 24px rgba(0,0,0,.30),
    inset 0 1px 0 rgba(255,255,255,.055),
    inset 0 -1px 0 rgba(0,0,0,.28);
  transition:
    transform .18s ease,
    box-shadow .18s ease,
    background .18s ease,
    border-color .18s ease;
  -webkit-tap-highlight-color:transparent;
}

.rb-category-card:hover{
  transform:translateY(-2px);
  box-shadow:
    0 16px 30px rgba(0,0,0,.38),
    inset 0 1px 0 rgba(255,255,255,.07);
}

.rb-category-card.active{
  background:
    radial-gradient(circle at 75% 8%, var(--cat-accent-soft), transparent 36%),
    linear-gradient(180deg, rgba(31,39,39,.98) 0%, rgba(21,29,29,.98) 100%);
  box-shadow:
    0 16px 32px rgba(0,0,0,.40),
    0 0 0 1px var(--cat-accent-glow) inset,
    0 0 24px var(--cat-accent-glow),
    inset 0 1px 0 rgba(255,255,255,.08);
}

/* Semua / Reguler = Biru */
.rb-category-card.is-regular{
  --cat-accent:#34d5ff;
  --cat-accent2:#5a8cff;
  --cat-accent-soft:rgba(52,213,255,.14);
  --cat-accent-glow:rgba(52,213,255,.22);
}

/* Saham Rubik / Harian = Oren */
.rb-category-card.is-daily{
  --cat-accent:#fb923c;
  --cat-accent2:#f97316;
  --cat-accent-soft:rgba(251,146,60,.14);
  --cat-accent-glow:rgba(251,146,60,.22);
}

/* Rubik Pro = Kuning */
.rb-category-card.is-premium{
  --cat-accent:#f6c453;
  --cat-accent2:#facc15;
  --cat-accent-soft:rgba(246,196,83,.14);
  --cat-accent-glow:rgba(246,196,83,.22);
}
.rb-category-inner{
  position:relative;
  z-index:2;
  width:100%;
  height:100%;
  padding:13px 12px 11px;
  display:flex;
  flex-direction:column;
  justify-content:space-between;
}

.rb-category-visual{
  width:30px;
  height:30px;
  position:relative;
  display:grid;
  place-items:center;
}

.rb-category-visual::before{
  content:"";
  position:absolute;
  inset:0;
  border-radius:10px;
  background:
    radial-gradient(circle at 28% 18%, rgba(255,255,255,.18), transparent 36%),
    linear-gradient(135deg, var(--cat-accent-soft), rgba(255,255,255,.035));
  box-shadow:
    0 8px 18px rgba(0,0,0,.20),
    0 0 18px var(--cat-accent-glow),
    inset 0 1px 0 rgba(255,255,255,.08);
}

.rb-category-visual::after{
  content:"";
  position:absolute;
  right:-8px;
  top:-7px;
  width:21px;
  height:21px;
  border-radius:999px;
  background:linear-gradient(135deg, var(--cat-accent), var(--cat-accent2));
  opacity:.22;
  filter:blur(.2px);
}
.rb-category-icon{
  position:relative;
  width:22px;
  height:22px;
  display:grid;
  place-items:center;
  color:var(--cat-accent);
  z-index:3;
  filter:drop-shadow(0 0 10px color-mix(in srgb, var(--cat-accent) 45%, transparent));
}

.rb-category-icon svg{
  width:19px;
  height:19px;
}

.rb-category-text{
  min-width:0;
  display:block;
}

.rb-category-kicker{
  display:none;
}

.rb-category-name{
  display:block;
  margin:0 0 4px;
  font-size:13px;
  font-weight:850;
  letter-spacing:-.025em;
  line-height:1.12;
  color:#f8fffc;
  text-shadow:0 6px 16px rgba(0,0,0,.24);
}

.rb-category-value{
  display:block;
  font-size:10.5px;
  font-weight:800;
  letter-spacing:-.02em;
  color:rgba(229,238,235,.70);
  line-height:1.1;
}

.rb-category-value strong{
  color:#ffffff;
  font-weight:900;
}

.rb-category-value span{
  color:rgba(229,238,235,.42);
  font-weight:800;
}

.rb-category-card.active .rb-category-name{
  color:#ffffff;
}

/* =========================
   INVITE BANNER ANIMATION
   gambar terasa hidup / premium
========================= */

/* gambar utama gerak halus */
.rb-invite-hero{
  animation: rbInviteHeroFloat 4.2s ease-in-out infinite;
  will-change: transform, filter;
  filter:
    drop-shadow(0 14px 18px rgba(3,15,15,.14))
    saturate(1.04)
    contrast(1.03);
}

/* shape belakang ikut breathing */
.rb-invite-visual::before{
  animation: rbInviteBubblePulse 5.4s ease-in-out infinite;
}

.rb-invite-visual::after{
  animation: rbInviteBasePulse 6s ease-in-out infinite;
}

/* coin gerak beda-beda */
.rb-invite-deco.coin-1{
  animation: rbInviteCoinFloat1 3.6s ease-in-out infinite;
}

.rb-invite-deco.coin-2{
  animation: rbInviteCoinFloat2 4.1s ease-in-out infinite;
}

/* sparkle tombol */
.rb-invite-btn::after{
  animation: rbInviteSpark 2.4s ease-in-out infinite;
}

/* sparkle kiri atas card */
.rb-invite-card::before{
  animation: rbInviteSparkTop 2.8s ease-in-out infinite;
}

/* hover: terasa lebih responsif */
.rb-invite-card:hover .rb-invite-hero{
  filter:
    drop-shadow(0 18px 24px rgba(3,15,15,.18))
    saturate(1.08)
    contrast(1.05);
}

.rb-invite-card:hover .rb-invite-btn{
  transform:translateY(-1px);
  box-shadow:
    0 14px 26px rgba(0,223,130,.24),
    inset 0 1px 0 rgba(255,255,255,.42);
}

/* KEYFRAMES */
@keyframes rbInviteHeroFloat{
  0%, 100%{
    transform:scale(1.38) translate3d(0, 0, 0) rotate(0deg);
  }
  25%{
    transform:scale(1.38) translate3d(0, -4px, 0) rotate(-.45deg);
  }
  50%{
    transform:scale(1.38) translate3d(2px, -7px, 0) rotate(.35deg);
  }
  75%{
    transform:scale(1.38) translate3d(-1px, -3px, 0) rotate(-.25deg);
  }
}

@keyframes rbInviteBubblePulse{
  0%, 100%{
    transform:scale(1);
    opacity:.86;
  }
  50%{
    transform:scale(1.045);
    opacity:1;
  }
}

@keyframes rbInviteBasePulse{
  0%, 100%{
    transform:translate3d(0, 0, 0) scale(1);
    opacity:.82;
  }
  50%{
    transform:translate3d(-4px, 2px, 0) scale(1.035);
    opacity:.96;
  }
}

@keyframes rbInviteCoinFloat1{
  0%, 100%{
    transform:translate3d(0, 0, 0) rotate(0deg);
  }
  50%{
    transform:translate3d(-4px, -8px, 0) rotate(10deg);
  }
}

@keyframes rbInviteCoinFloat2{
  0%, 100%{
    transform:translate3d(0, 0, 0) rotate(0deg);
  }
  50%{
    transform:translate3d(5px, -6px, 0) rotate(-12deg);
  }
}

@keyframes rbInviteSpark{
  0%, 100%{
    transform:translateY(-50%) scale(.9) rotate(0deg);
    opacity:.72;
  }
  50%{
    transform:translateY(-50%) scale(1.18) rotate(18deg);
    opacity:1;
  }
}

@keyframes rbInviteSparkTop{
  0%, 100%{
    transform:scale(.9) rotate(0deg);
    opacity:.65;
  }
  50%{
    transform:scale(1.16) rotate(18deg);
    opacity:1;
  }
}

/* aksesibilitas: kalau user disable motion, animasi mati */
@media (prefers-reduced-motion: reduce){
  .rb-invite-hero,
  .rb-invite-visual::before,
  .rb-invite-visual::after,
  .rb-invite-deco.coin-1,
  .rb-invite-deco.coin-2,
  .rb-invite-btn::after,
  .rb-invite-card::before{
    animation:none !important;
  }
}

.rb-category-card.active .rb-category-value strong{
  color:var(--cat-accent);
}

.rb-category-card.is-clone{
  pointer-events:auto;
}

@media (max-width:370px){
  .rb-category-slider{
    gap:8px;
  }

  .rb-category-card{
    flex-basis:98px;
    height:104px;
    border-radius:15px;
  }

  .rb-category-inner{
    padding:12px 10px 10px;
  }

  .rb-category-name{
    font-size:12.5px;
  }

  .rb-category-value{
    font-size:10px;
  }
}
    /* =========================
       PRODUCT WATCHLIST STYLE - DARK PREMIUM
    ========================= */
    .rb-products{
      display:grid;
      grid-template-columns:1fr;
      gap:8px;
    }

    .rb-product-card{
      position:relative;
      overflow:hidden;
      border-radius:18px;
      background:
        radial-gradient(180px 100px at 88% 10%, rgba(0,223,130,.10), transparent 62%),
        linear-gradient(180deg, rgba(9,37,31,.94), rgba(5,21,18,.94));
      border:1px solid rgba(255,255,255,.08);
      box-shadow:
        0 16px 30px rgba(0,0,0,.24),
        0 0 0 1px rgba(0,223,130,.04) inset;
      transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    }

    .rb-product-card:hover{
      transform:translateY(-1px);
      border-color:rgba(0,223,130,.22);
      box-shadow:
        0 18px 34px rgba(0,0,0,.32),
        0 0 28px rgba(0,223,130,.08);
    }

    .rb-product-row{
      width:100%;
      min-height:72px;
      padding:12px 12px;
      display:grid;
      grid-template-columns: minmax(0, 1fr) 104px;
      gap:10px;
      align-items:center;
    }

    .rb-product-info{
      min-width:0;
    }

    .rb-product-line{
      display:flex;
      align-items:center;
      gap:7px;
      min-width:0;
    }

    .rb-product-code{
      margin:0;
      color:#ffffff;
      font-size:14px;
      line-height:1.1;
      font-weight:950;
      letter-spacing:-.02em;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width:112px;
      text-shadow:0 8px 18px rgba(0,0,0,.22);
    }

    .rb-product-risk{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      height:22px;
      padding:0 8px;
      border-radius:999px;
      font-size:9.5px;
      line-height:1;
      font-weight:850;
      white-space:nowrap;
      border:1px solid transparent;
    }

    .rb-product-risk.is-aggressive{
      color:#00DF82;
      background:rgba(0,223,130,.10);
      border-color:rgba(0,223,130,.24);
    }

    .rb-product-risk.is-balanced{
      color:#ffd67d;
      background:rgba(246,196,83,.10);
      border-color:rgba(246,196,83,.28);
    }

    .rb-product-risk.is-conservative{
      color:#aaffdc;
      background:rgba(3,98,76,.26);
      border-color:rgba(0,223,130,.18);
    }

    .rb-product-meta{
      margin:7px 0 0;
      color:rgba(214,255,240,.68);
      font-size:12px;
      font-weight:700;
    }

    .rb-product-chart{
      width:104px;
      height:46px;
      justify-self:end;
      position:relative;
      border-radius:12px;
      overflow:hidden;
    }

    .rb-product-chart svg{
      width:100%;
      height:100%;
      display:block;
      overflow:visible;
    }

    .rb-chart-area{
      opacity:.23;
      transition:opacity .25s ease;
    }

    .rb-chart-line{
      fill:none;
      stroke:#00DF82;
      stroke-width:2.6;
      stroke-linecap:round;
      stroke-linejoin:round;
      filter:drop-shadow(0 4px 7px rgba(0,223,130,.18));
      will-change:d;
    }

    .rb-chart-dot{
      fill:#00DF82;
      opacity:.95;
      transform-origin:center;
      will-change:transform, opacity, cx, cy;
      animation:rbDotPulse 2.15s ease-in-out infinite;
    }

    .rb-product-card:nth-child(2n) .rb-chart-line,
    .rb-product-card:nth-child(2n) .rb-chart-dot{
      stroke:#58ffad;
      fill:#58ffad;
    }

    .rb-product-card:nth-child(3n) .rb-chart-line,
    .rb-product-card:nth-child(3n) .rb-chart-dot{
      stroke:#0ecb81;
      fill:#0ecb81;
    }

    @keyframes rbDotPulse{
      0%,100%{
        opacity:.55;
        transform:scale(.92);
      }
      50%{
        opacity:1;
        transform:scale(1.18);
      }
    }

    .rb-product-details{
      display:grid;
      grid-template-columns:repeat(2,1fr);
      gap:0;
      border-top:1px solid rgba(255,255,255,.07);
      background:rgba(3,15,15,.42);
    }

    .rb-product-stat{
      padding:10px 12px;
    }

    .rb-product-stat + .rb-product-stat{
      border-left:1px solid rgba(255,255,255,.06);
    }

    .rb-product-stat-label{
      margin:0 0 4px;
      font-size:10.5px;
      color:rgba(214,255,240,.62);
      font-weight:850;
    }

    .rb-product-stat-value{
      margin:0;
      font-size:12.5px;
      color:#ffffff;
      font-weight:950;
      letter-spacing:-.015em;
    }

    .rb-product-stat-value.is-blue{
      color:var(--rb-neon);
      text-shadow:0 0 16px rgba(0,223,130,.14);
    }

    .rb-product-action{
      padding:10px 12px 12px;
      background:rgba(3,15,15,.18);
    }

    .rb-btn{
      width:100%;
      min-height:42px;
      border:0;
      border-radius:14px;
      padding:11px 14px;
      font-size:12.5px;
      font-weight:950;
      cursor:pointer;
      text-decoration:none;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:8px;
      transition:transform .18s ease, box-shadow .18s ease, filter .18s ease;
    }

    .rb-btn svg{
      width:16px;
      height:16px;
    }

    .rb-btn-primary{
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.42), transparent 34%),
        linear-gradient(135deg, #00DF82 0%, #03624C 100%);
      color:#02110c;
      box-shadow:
        0 14px 26px rgba(0,223,130,.20),
        inset 0 1px 0 rgba(255,255,255,.26);
    }

    .rb-btn-primary:hover{
      transform:translateY(-1px);
      filter:brightness(1.04);
    }

    .rb-btn-muted{
      background:rgba(255,255,255,.06);
      color:rgba(214,255,240,.58);
      border:1px solid rgba(255,255,255,.08);
      cursor:not-allowed;
    }

    @media (max-width:370px){
      .rb-product-row{
        grid-template-columns:minmax(0, 1fr) 92px;
        gap:8px;
        padding:11px 10px;
      }

      .rb-product-chart{
        width:92px;
        height:42px;
      }

      .rb-product-code{
        max-width:92px;
        font-size:13px;
      }

      .rb-product-risk{
        height:20px;
        padding:0 7px;
        font-size:9px;
      }
    }

    .rb-empty{
      padding:18px 14px;
      border-radius:20px;
      background:rgba(9,37,31,.76);
      border:1px dashed rgba(0,223,130,.22);
      color:rgba(214,255,240,.72);
      text-align:center;
      font-size:12.5px;
      font-weight:800;
    }

    /* =========================
       MODAL / OVERLAY
    ========================= */
    .rb-overlay{
      position:fixed;
      inset:0;
      z-index:999;
      display:none;
      align-items:center;
      justify-content:center;
      padding:18px 14px;
      background:rgba(0,0,0,.62);
      backdrop-filter:blur(12px);
      -webkit-backdrop-filter:blur(12px);
    }

    .rb-overlay.show{
      display:flex;
    }

    .rb-category-card.is-clone{
      pointer-events:auto;
    }

    .rb-modal{
      width:100%;
      max-width:420px;
      border-radius:24px;
      background:
        radial-gradient(280px 160px at 100% 0%, rgba(0,223,130,.15), transparent 60%),
        linear-gradient(180deg, rgba(8,34,29,.98), rgba(3,15,15,.98));
      border:1px solid rgba(255,255,255,.10);
      box-shadow:
        0 30px 80px rgba(0,0,0,.48),
        0 0 0 1px rgba(0,223,130,.08) inset;
      overflow:hidden;
    }

    .rb-modal-head{
      padding:15px 16px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      border-bottom:1px solid rgba(255,255,255,.08);
    }

    .rb-modal-title{
      margin:0;
      display:flex;
      align-items:center;
      gap:10px;
      font-size:15px;
      font-weight:950;
      letter-spacing:-.02em;
      color:#ffffff;
    }

    .rb-modal-icon{
      width:34px;
      height:34px;
      border-radius:14px;
      display:grid;
      place-items:center;
      background:linear-gradient(135deg, rgba(0,223,130,.18), rgba(3,98,76,.36));
      color:#dfffee;
      border:1px solid rgba(0,223,130,.18);
    }

    .rb-modal-close{
      width:38px;
      height:38px;
      border-radius:13px;
      border:1px solid rgba(255,255,255,.10);
      background:rgba(255,255,255,.05);
      display:grid;
      place-items:center;
      cursor:pointer;
      color:#ffffff;
    }

    .rb-modal-body{
      padding:16px;
    }

    .rb-modal-body p{
      margin:0;
      color:rgba(214,255,240,.70);
      font-size:13.5px;
      font-weight:700;
      line-height:1.55;
      text-align:center;
    }

    .rb-modal-body b{
      color:#ffffff;
    }

    .rb-modal-actions{
      padding:0 16px 16px;
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:10px;
    }

    .rb-modal-btn{
      min-height:44px;
      border-radius:15px;
      border:1px solid rgba(255,255,255,.09);
      background:rgba(255,255,255,.06);
      color:rgba(214,255,240,.72);
      font-size:12.5px;
      font-weight:950;
      cursor:pointer;
      text-decoration:none;
      display:flex;
      align-items:center;
      justify-content:center;
      text-align:center;
    }

    .rb-modal-btn.is-primary{
      border:0;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.42), transparent 34%),
        linear-gradient(135deg, #00DF82, #03624C);
      color:#03110c;
      box-shadow:0 14px 26px rgba(0,223,130,.22);
    }

    @media (min-width:768px){
      .rb-page{
        padding-top:22px;
      }

      .rb-phone{
        max-width:430px;
      }
    }

   /* =========================
   PROMO IMAGE POPUP - BIG VISUAL FIX
========================= */
.rb-promo-overlay{
  position:fixed;
  inset:0;
  z-index:9999;

  display:none;
  align-items:center;
  justify-content:center;

  padding:24px 10px 96px;

  background:rgba(2,8,8,.58);
  backdrop-filter:blur(8px);
  -webkit-backdrop-filter:blur(8px);

  opacity:0;
  transition:opacity .22s ease;

  overflow:hidden;
}

.rb-promo-overlay.show{
  display:flex;
  opacity:1;
}

.rb-promo-modal{
  position:relative;

  /* Modal jangan terlalu kecil */
  width:min(calc(100vw - 18px), 430px);

  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;

  transform:translateY(16px) scale(.96);
  opacity:0;

  animation:rbPromoIn .26s ease forwards;

  /* penting: gambar boleh keluar sedikit dari modal */
  overflow:visible;
}

.rb-promo-image{
  width:100%;
  height:auto;
  display:block;

  border-radius:0;
  object-fit:contain;

  /*
    INI KUNCINYA BRO:
    kalau PNG punya canvas kosong, scale ini membesarkan visualnya.
  */
  transform:scale(1.42);
  transform-origin:center center;

  filter:
    drop-shadow(0 26px 50px rgba(0,0,0,.50))
    drop-shadow(0 0 34px rgba(0,223,130,.20));
}

.rb-promo-close{
  position:absolute;
  left:50%;
  bottom:-92px;
  transform:translateX(-50%);

  width:48px;
  height:48px;
  border-radius:999px;
  border:1px solid rgba(15,23,42,.10);

  display:grid;
  place-items:center;

  background:rgba(255,255,255,.96);
  color:#374151;

  box-shadow:
    0 14px 32px rgba(0,0,0,.30),
    0 0 0 1px rgba(255,255,255,.38) inset;

  cursor:pointer;
  z-index:2;
  -webkit-tap-highlight-color:transparent;

  transition:
    transform .18s ease,
    box-shadow .18s ease,
    background .18s ease;
}

.rb-promo-close:hover{
  transform:translateX(-50%) translateY(-1px);
  background:#ffffff;
  box-shadow:
    0 18px 36px rgba(0,0,0,.34),
    0 0 0 1px rgba(255,255,255,.45) inset;
}

.rb-promo-close svg{
  width:24px;
  height:24px;
}

@keyframes rbPromoIn{
  to{
    transform:translateY(0) scale(1);
    opacity:1;
  }
}

@media (max-width:430px){
  .rb-promo-overlay{
    padding-left:8px;
    padding-right:8px;
    padding-bottom:98px;
  }

  .rb-promo-modal{
    width:min(calc(100vw - 12px), 420px);
  }

  .rb-promo-image{
    transform:scale(1.48);
  }

  .rb-promo-close{
    bottom:-94px;
  }
}

@media (max-width:370px){
  .rb-promo-overlay{
    padding-left:6px;
    padding-right:6px;
    padding-bottom:92px;
  }

  .rb-promo-modal{
    width:min(calc(100vw - 10px), 390px);
  }

  .rb-promo-image{
    transform:scale(1.44);
  }

  .rb-promo-close{
    width:44px;
    height:44px;
    bottom:-88px;
  }
}

.rb-category-slider{
  scroll-behavior:auto !important;
  will-change:scroll-position;
}

.rb-category-slider.is-auto-running{
  cursor:grab;
}

.rb-category-slider.is-user-dragging{
  cursor:grabbing;
  scroll-behavior:auto !important;
}

.rb-invite-title{
  margin:0;
  color:#041b16;
  font-size:15px;
  line-height:1.08;
  font-weight:950;
  letter-spacing:-.045em;
  max-width:180px;
  text-shadow:none;
}

.rb-invite-title span{
  display:block;
}

.rb-invite-title .highlight{
  color:#00C97A;
  white-space:nowrap;
  text-shadow:none;
}
  </style>
</head>

<body>
  {{-- POPUP TELEGRAM --}}
  <div class="rb-overlay" id="tgOverlay" role="dialog" aria-modal="true" aria-labelledby="tgTitle">
    <div class="rb-modal">
      <div class="rb-modal-head">
        <h3 class="rb-modal-title" id="tgTitle">
          <span class="rb-modal-icon" aria-hidden="true">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
              <path d="M22 2 11 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M22 2 15 22 11 13 2 9 22 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </span>
          Grup Resmi Rubik
        </h3>

        <button class="rb-modal-close" type="button" id="tgCloseBtn" aria-label="Tutup">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <path d="M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            <path d="M6 6 18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
      </div>

      <div class="rb-modal-body">
        <p>
          Bergabunglah dengan grup resmi untuk mendapatkan informasi investasi terbaru,
          update sistem, dan komunikasi bersama member lainnya.
        </p>
      </div>

      <div class="rb-modal-actions">
        <button class="rb-modal-btn" type="button" id="tgLaterBtn">Nanti saja</button>
        <a class="rb-modal-btn is-primary" id="tgJoinBtn" href="https://t.me/rubikcompany" target="_blank" rel="noopener">
          Bergabung
        </a>
      </div>
    </div>
  </div>

  {{-- POPUP SALDO KURANG --}}
  <div class="rb-overlay" id="saldoOverlay" role="dialog" aria-modal="true" aria-labelledby="saldoTitle">
    <div class="rb-modal">
      <div class="rb-modal-head">
        <h3 class="rb-modal-title" id="saldoTitle">
          <span class="rb-modal-icon" aria-hidden="true">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
              <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
              <path d="M12 8v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M12 16h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
            </svg>
          </span>
          Saldo Kurang
        </h3>

        <button class="rb-modal-close" type="button" id="saldoCloseBtn" aria-label="Tutup">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <path d="M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            <path d="M6 6 18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
      </div>

      <div class="rb-modal-body">
        <p>
          Produk <b id="smProduct">-</b> membutuhkan saldo <b id="smPrice">Rp 0</b>.
          Saldo kamu saat ini <b id="smSaldo">Rp 0</b>.
          Kekurangan <b id="smShort">Rp 0</b>.
        </p>
      </div>

      <div class="rb-modal-actions">
        <button class="rb-modal-btn" type="button" id="saldoOkBtn">Oke</button>
        <a class="rb-modal-btn is-primary" href="/deposit">Deposit Sekarang</a>
      </div>
    </div>
  </div>

</div>
  <main class="rb-page">
    <div class="rb-phone">

      {{-- HEADER --}}
      <header class="rb-topbar">
        <div class="rb-brand">
          <div class="rb-logo-card">
            <img src="{{ asset('logo.png') }}" alt="Rubik Company">
          </div>

          <div class="rb-welcome">
            <h1>RUBIK</h1>
            <p>Rubik Company</p>
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

          <a href="{{ url('/akun') }}" class="rb-header-btn is-profile" aria-label="Profil">
            <span class="rb-header-avatar">
              <svg viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="8" r="4" fill="currentColor"/>
                <path d="M4 21a8 8 0 0 1 16 0" fill="currentColor"/>
              </svg>
            </span>
          </a>
        </div>
      </header>

      {{-- ERROR --}}
      @if($errors->any())
        <div class="rb-errors" role="alert">
          <p class="rb-errors-title">Terjadi kesalahan</p>
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

@php
  $saldoUtama = (int) ($saldoUtama ?? data_get($user, 'saldo', 0));
  $saldoPenarikan = (int) ($saldoPenarikan ?? data_get($user, 'saldo_penarikan', 0));
  $saldoHold = (int) ($saldoHold ?? data_get($user, 'saldo_hold', 0));
  $saldoPenarikanTotal = (int) ($saldoPenarikanTotal ?? data_get($user, 'saldo_penarikan_total', 0));

  $totalInvestasi = (int) ($totalInvestasi ?? 0);
  $activePlanCount = (int) ($activePlanCount ?? 0);
  $totalDailyProfit = (int) ($totalDailyProfit ?? 0);
  $totalProfit = (int) ($totalProfit ?? 0);

  $statusAkun = data_get($user, 'status') ?: 'Aktif';
@endphp

      
@php
  $dashboardBanners = [
    [
      'image' => 'assets/banners/bonus-uang-tunai.png',
      'url' => url('/referral'),
      'alt' => 'Bonus Undang Teman Berinvestasi Hingga Rp100 Jt',
    ],

    [
      'image' => 'assets/banners/banner-2.png',
      'url' => url('/deposit'),
      'alt' => 'Promo deposit Rubik',
    ],
  ];
@endphp



      {{-- HERO SALDO --}}
      <section class="rb-hero">
        <div class="rb-hero-inner">
          <div class="rb-balance-grid">
            <div class="rb-balance-box">
              <p class="rb-balance-label">Jumlah Saldo Utama</p>
              <div class="rb-balance">Rp {{ number_format($saldoUtama,0,',','.') }}</div>
            </div>

            <div class="rb-balance-box">
              <p class="rb-balance-label">Jumlah Saldo Penarikan</p>
              <div class="rb-balance">Rp {{ number_format($saldoPenarikan,0,',','.') }}</div>
            </div>
          </div>

          <div class="rb-balance-bottom">
            <div class="rb-balance-small">
              <p class="rb-balance-label">Total Investasi</p>
              <div class="rb-balance">Rp {{ number_format($totalInvestasi,0,',','.') }}</div>
            </div>

            <div class="rb-account-status">
              {{ strtoupper($statusAkun) }}
            </div>
          </div>
        </div>
      </section>

{{-- ACTION UTAMA --}}
<div class="rb-main-actions">
  <a href="/deposit" class="rb-main-action is-deposit">
    <svg viewBox="0 0 24 24" fill="none">
      <path d="M7 17 17 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
      <path d="M9 7h8v8" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    Deposito
  </a>

  <a href="{{ url('/ui/withdrawals') }}" class="rb-main-action is-withdraw">
    <svg viewBox="0 0 24 24" fill="none">
      <path d="M17 7 7 17" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
      <path d="M15 17H7V9" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    Menarik
  </a>
</div>

{{-- LISENSI --}}
<div class="rb-license">
  <div class="rb-license-left">
    Berlisensi oleh :
  </div>

  <div class="rb-license-right">
    <img
      src="{{ asset('assets/logos/ojk.png') }}"
      alt="OJK"
      class="rb-license-logo rb-license-logo-ojk"
    >

    <img
      src="{{ asset('assets/logos/bappebti.png') }}"
      alt="BAPPEBTI"
      class="rb-license-logo rb-license-logo-bappebti"
    >
  </div>
</div>


@php
  $inviteBanners = [
    [
      'title_top' => 'Bonus Undang Teman',
      'title_bottom' => 'Berinvestasi Hingga',
      'highlight' => 'Rp100 Jt!',
      'button' => 'Undang',
      'url' => url('/referral'),
      'image' => asset('assets/banners/invite-bonus-hero.png'),
      'alt' => 'Bonus Undang Teman Berinvestasi Hingga Rp100 Jt',
    ],
  ];
@endphp

@if(count($inviteBanners))
  <section class="rb-invite-banner-section" aria-label="Banner bonus undangan">
    <div class="rb-invite-slider" id="inviteBannerSlider">

@if(count($inviteBanners) > 1)
  <button type="button" class="rb-invite-nav prev" data-invite-prev aria-label="Banner sebelumnya">
    <svg viewBox="0 0 24 24" fill="none">
      <path d="m15 18-6-6 6-6" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </button>
@endif
      <div class="rb-invite-slider-viewport">
        <div class="rb-invite-slider-track">
          @foreach($inviteBanners as $banner)
            <div class="rb-invite-slide">
              <a href="{{ $banner['url'] }}" class="rb-invite-card">
                <div class="rb-invite-content">
<h3 class="rb-invite-title">
  <span>{{ $banner['title_top'] }}</span>
  <span>{{ $banner['title_bottom'] }}</span>
  <span class="highlight">{{ $banner['highlight'] }}</span>
</h3>

                  <span class="rb-invite-btn">{{ $banner['button'] }}</span>
                </div>

                <div class="rb-invite-visual">
                  <img
                    src="{{ $banner['image'] }}"
                    alt="{{ $banner['alt'] }}"
                    class="rb-invite-hero"
                    loading="lazy"
                  >
                </div>

                <span class="rb-invite-deco is-star"></span>
                <span class="rb-invite-deco is-coin coin-1"></span>
                <span class="rb-invite-deco is-coin coin-2"></span>
              </a>
            </div>
          @endforeach
        </div>
      </div>

@if(count($inviteBanners) > 1)
  <button type="button" class="rb-invite-nav next" data-invite-next aria-label="Banner berikutnya">
    <svg viewBox="0 0 24 24" fill="none">
      <path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </button>
@endif
    </div>

    @if(count($inviteBanners) > 1)
      <div class="rb-invite-dots" id="inviteBannerDots">
        @foreach($inviteBanners as $index => $banner)
          <button
            type="button"
            class="rb-invite-dot {{ $index === 0 ? 'active' : '' }}"
            data-invite-dot="{{ $index }}"
            aria-label="Slide {{ $index + 1 }}"
          ></button>
        @endforeach
      </div>
    @endif
  </section>
@endif


      {{-- PRODUK --}}
      <section class="rb-section" id="produk">
<div class="rb-section-head">
  <div class="rb-section-title">
    <h2>Kembangkan Uang Anda</h2>
    <p>Portofolio diperbarui setiap 24 jam</p>
  </div>

  <a href="#produk" class="rb-see-all">
    Jelajahi Semua
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none">
      <path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </a>
</div>

        <div class="rb-category-slider-wrap" aria-label="Kategori Produk">
          <div class="rb-category-slider" id="categorySlider">
            @foreach($categories as $i => $cat)
@php
  $catName = strtolower(trim($cat->name));

  if(
    str_contains($catName, 'saham') ||
    str_contains($catName, 'harian')
  ) {
    // Saham Rubik = oren
    $catClass = 'is-daily';
    $catKicker = 'Portofolio Harian';

  } elseif(
    str_contains($catName, 'pro') ||
    str_contains($catName, 'premium')
  ) {
    // Rubik Pro = oren juga
    $catClass = 'is-premium';
    $catKicker = 'Portofolio Premium';

  } else {
    // Semua = biru
    $catClass = 'is-regular';
    $catKicker = 'Portofolio Dasar';
  }
@endphp
          @php
  $categoryTotalProduct = $cat->products->count();
  $categoryTarget = max($categoryTotalProduct * 10, 50);
@endphp

<button
  type="button"
  class="rb-category-card {{ $catClass }} {{ $i===0 ? 'active' : '' }}"
  data-tab="cat-{{ $cat->id }}"
>
  <span class="rb-category-inner">
<span class="rb-category-visual" aria-hidden="true">
<span class="rb-category-icon">
  {{-- Semua kategori pakai icon box/package, warna tetap ikut class kategori --}}
  <svg viewBox="0 0 24 24" fill="none">
    <path d="M12 3 20 7 12 11 4 7 12 3Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
    <path d="M20 7v10l-8 4-8-4V7" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
    <path d="M12 11v10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
  </svg>
</span>
</span>

    <span class="rb-category-text">
      <strong class="rb-category-name">{{ $cat->name }}</strong>

      <span class="rb-category-value">
        <strong>{{ $categoryTotalProduct }}</strong>
        <span>/{{ $categoryTarget }}K</span>
      </span>
    </span>
  </span>
</button>
            @endforeach
          </div>
        </div>

        @foreach($categories as $i => $cat)
          <div class="rb-pane" id="cat-{{ $cat->id }}" style="{{ $i !== 0 ? 'display:none' : '' }}">
            <div class="rb-products">
              @forelse($cat->products as $product)
                @php
                  $invActive   = $activeInvestments[$product->id] ?? null;
                  $vipKurang   = $user->vip_level < $product->min_vip_level;
                  $saldoKurang = $user->saldo < $product->price;
                @endphp

                @php
                  $riskIndex = ($loop->index + $cat->id) % 3;

                  if($riskIndex === 0){
                    $riskLabel = 'Aggressive';
                    $riskClass = 'is-aggressive';
                  } elseif($riskIndex === 1){
                    $riskLabel = 'Balanced';
                    $riskClass = 'is-balanced';
                  } else {
                    $riskLabel = 'Conservative';
                    $riskClass = 'is-conservative';
                  }

                  $seedValue = (int) (
                    $product->price
                    + $product->daily_profit
                    + $product->total_profit
                    + $product->duration_days
                  );

                  $chartSeed = max($seedValue, 1);
                @endphp

                <article class="rb-product-card">
                  <div class="rb-product-row">
                    <div class="rb-product-info">
                      <div class="rb-product-line">
                        <h3 class="rb-product-code">{{ $product->name }}</h3>
                        <span class="rb-product-risk {{ $riskClass }}">{{ $riskLabel }}</span>
                      </div>

                      <p class="rb-product-meta">{{ $product->duration_days }} hari</p>
                    </div>

                    <div
                      class="rb-product-chart js-product-chart"
                      data-seed="{{ $chartSeed }}"
                      data-price="{{ (int) $product->price }}"
                      data-profit="{{ (int) $product->daily_profit }}"
                      data-total="{{ (int) $product->total_profit }}"
                    >
                      <svg viewBox="0 0 120 52" preserveAspectRatio="none" aria-hidden="true">
                        <defs>
                          <linearGradient id="chartFill-{{ $cat->id }}-{{ $product->id }}" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="#00DF82" stop-opacity=".26"/>
                            <stop offset="100%" stop-color="#00DF82" stop-opacity="0"/>
                          </linearGradient>
                        </defs>

                        <path
                          class="rb-chart-area"
                          d=""
                          fill="url(#chartFill-{{ $cat->id }}-{{ $product->id }})"
                        ></path>

                        <path
                          class="rb-chart-line"
                          d=""
                        ></path>

                        <circle
                          class="rb-chart-dot"
                          cx="0"
                          cy="0"
                          r="3"
                        ></circle>
                      </svg>
                    </div>
                  </div>

                  <div class="rb-product-details">
                    <div class="rb-product-stat">
                      <p class="rb-product-stat-label">Profit Harian</p>
                      <p class="rb-product-stat-value">Rp {{ number_format($product->daily_profit,0,',','.') }}</p>
                    </div>

                    <div class="rb-product-stat">
                      <p class="rb-product-stat-label">Total Profit</p>
                      <p class="rb-product-stat-value is-blue">Rp {{ number_format($product->total_profit,0,',','.') }}</p>
                    </div>
                  </div>

                  <div class="rb-product-action">
                    @if($invActive)
                      <a href="{{ route('investasi.index') }}" class="rb-btn rb-btn-muted">
                        Sedang Aktif
                      </a>
                    @elseif($vipKurang)
                      <button class="rb-btn rb-btn-muted" type="button" disabled>
                        VIP {{ $product->min_vip_level }} Required
                      </button>
                    @elseif($saldoKurang)
<a href="{{ route('market.index') }}" class="rb-btn rb-btn-primary">
  <svg viewBox="0 0 24 24" fill="none">
    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
    <path d="M3 6h18" stroke="currentColor" stroke-width="2"/>
    <path d="M16 10a4 4 0 0 1-8 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
  </svg>
  Investasi Sekarang
</a>
                    @else
<a href="{{ route('market.index') }}" class="rb-btn rb-btn-primary">
  <svg viewBox="0 0 24 24" fill="none">
    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
    <path d="M3 6h18" stroke="currentColor" stroke-width="2"/>
    <path d="M16 10a4 4 0 0 1-8 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
  </svg>
  Investasi Sekarang
</a>
                    @endif
                  </div>
                </article>
              @empty
                <div class="rb-empty">Belum ada produk tersedia di kategori ini.</div>
              @endforelse
            </div>
          </div>
        @endforeach
      </section>

      <div class="rb-bottom-spacer"></div>
    </div>
  </main>

  @include('partials.bottom-nav')
   @include('partials.anti-inspect')



  <script>
    function formatRupiah(value){
      const number = Number(value || 0);
      return 'Rp ' + number.toLocaleString('id-ID');
    }

    function lockScroll(){
      document.documentElement.style.overflow = 'hidden';
      document.body.style.overflow = 'hidden';
    }

    function unlockScroll(){
      document.documentElement.style.overflow = '';
      document.body.style.overflow = '';
    }

// Category slider - transform marquee infinite, tidak berhenti
  (function(){
    const slider = document.getElementById('categorySlider');
    const panes = Array.from(document.querySelectorAll('.rb-pane'));

    if(!slider) return;

    const originals = Array.from(slider.querySelectorAll('.rb-category-card'));
    if(!originals.length) return;

    let setWidth = 0;
    let offset = 0;
    let rafId = null;
    let lastTime = 0;

    let isDragging = false;
    let startX = 0;
    let startOffset = 0;
    let moved = false;

    const speed = 12; // px per detik, makin kecil makin pelan

    function cloneOriginalSet(){
      const frag = document.createDocumentFragment();

      originals.forEach(function(card){
        const clone = card.cloneNode(true);
        clone.classList.add('is-clone');
        frag.appendChild(clone);
      });

      slider.appendChild(frag);
    }

    function measureSetWidth(){
      const first = originals[0];
      const last = originals[originals.length - 1];

      if(!first || !last) return 0;

      const firstLeft = first.offsetLeft;
      const lastRight = last.offsetLeft + last.offsetWidth;

      setWidth = lastRight - firstLeft + getGap();
      return setWidth;
    }

    function getGap(){
      const style = window.getComputedStyle(slider);
      return parseFloat(style.columnGap || style.gap || 0) || 0;
    }

    function normalizeOffset(){
      if(!setWidth) return;

      while(offset <= -setWidth){
        offset += setWidth;
      }

      while(offset > 0){
        offset -= setWidth;
      }
    }

    function applyTransform(){
      slider.style.transform = 'translate3d(' + offset + 'px, 0, 0)';
    }

    function setActive(id){
      Array.from(slider.querySelectorAll('.rb-category-card')).forEach(function(card){
        card.classList.toggle('active', card.dataset.tab === id);
      });

      panes.forEach(function(pane){
        pane.style.display = pane.id === id ? '' : 'none';
      });
    }

    function bindClick(){
      Array.from(slider.querySelectorAll('.rb-category-card')).forEach(function(card){
        card.addEventListener('click', function(e){
          if(moved){
            e.preventDefault();
            return;
          }

          const id = card.dataset.tab;
          if(id) setActive(id);
        });
      });
    }

    function tick(time){
      if(!lastTime) lastTime = time;

      const delta = time - lastTime;
      lastTime = time;

      if(!isDragging && setWidth > 0){
        offset -= (speed * delta) / 1000;
        normalizeOffset();
        applyTransform();
      }

      rafId = requestAnimationFrame(tick);
    }

    function start(){
      stop();
      lastTime = 0;
      rafId = requestAnimationFrame(tick);
    }

    function stop(){
      if(rafId){
        cancelAnimationFrame(rafId);
        rafId = null;
      }
    }

    function onPointerDown(e){
      if(e.pointerType === 'mouse' && e.button !== 0) return;

      isDragging = true;
      moved = false;
      startX = e.clientX;
      startOffset = offset;

      slider.classList.add('is-dragging');

      if(slider.setPointerCapture){
        slider.setPointerCapture(e.pointerId);
      }
    }

    function onPointerMove(e){
      if(!isDragging) return;

      const dx = e.clientX - startX;

      if(Math.abs(dx) > 4){
        moved = true;
      }

      offset = startOffset + dx;
      normalizeOffset();
      applyTransform();
    }

    function onPointerUp(e){
      if(!isDragging) return;

      isDragging = false;
      slider.classList.remove('is-dragging');

      if(slider.releasePointerCapture){
        try{
          slider.releasePointerCapture(e.pointerId);
        }catch(err){}
      }

      setTimeout(function(){
        moved = false;
      }, 80);
    }

    // clone 2 set supaya loop transform selalu punya isi panjang
    cloneOriginalSet();
    cloneOriginalSet();

    requestAnimationFrame(function(){
      measureSetWidth();
      normalizeOffset();
      applyTransform();
      bindClick();
      start();
    });

    slider.addEventListener('pointerdown', onPointerDown);
    slider.addEventListener('pointermove', onPointerMove);
    slider.addEventListener('pointerup', onPointerUp);
    slider.addEventListener('pointercancel', onPointerUp);
    slider.addEventListener('pointerleave', onPointerUp);

    window.addEventListener('resize', function(){
      measureSetWidth();
      normalizeOffset();
      applyTransform();
    });

    document.addEventListener('visibilitychange', function(){
      if(document.hidden){
        stop();
      }else{
        start();
      }
    });
  })();


    // Popup Telegram muncul sekali
    (function(){
      const KEY = 'rubik_tg_popup_v1';
      const overlay = document.getElementById('tgOverlay');
      const closeBtn = document.getElementById('tgCloseBtn');
      const laterBtn = document.getElementById('tgLaterBtn');
      const joinBtn = document.getElementById('tgJoinBtn');

      if(!overlay) return;

      function open(){
        overlay.classList.add('show');
        lockScroll();
      }

      function close(persist = true){
        overlay.classList.remove('show');
        unlockScroll();

        if(persist){
          try{ localStorage.setItem(KEY, '1'); }catch(e){}
        }
      }

      overlay.addEventListener('click', function(e){
        if(e.target === overlay) close(true);
      });

      closeBtn?.addEventListener('click', () => close(true));
      laterBtn?.addEventListener('click', () => close(true));

      joinBtn?.addEventListener('click', function(){
        try{ localStorage.setItem(KEY, '1'); }catch(e){}
      });

      document.addEventListener('keydown', function(e){
        if(e.key === 'Escape' && overlay.classList.contains('show')){
          close(true);
        }
      });

      let seen = false;
      try{ seen = localStorage.getItem(KEY) === '1'; }catch(e){}

      if(!seen){
        setTimeout(open, 550);
      }
    })();

    // Popup saldo kurang
    (function(){
      const overlay = document.getElementById('saldoOverlay');
      const closeBtn = document.getElementById('saldoCloseBtn');
      const okBtn = document.getElementById('saldoOkBtn');

      const productEl = document.getElementById('smProduct');
      const priceEl = document.getElementById('smPrice');
      const saldoEl = document.getElementById('smSaldo');
      const shortEl = document.getElementById('smShort');

      if(!overlay) return;

      function open(data){
        const price = Number(data.price || 0);
        const saldo = Number(data.saldo || 0);
        const shortage = Math.max(price - saldo, 0);

        productEl.textContent = data.product || '-';
        priceEl.textContent = formatRupiah(price);
        saldoEl.textContent = formatRupiah(saldo);
        shortEl.textContent = formatRupiah(shortage);

        overlay.classList.add('show');
        lockScroll();
      }

      function close(){
        overlay.classList.remove('show');
        unlockScroll();
      }

      document.querySelectorAll('.js-saldo-kurang').forEach(button => {
        button.addEventListener('click', function(){
          open({
            product: this.dataset.product,
            price: this.dataset.price,
            saldo: this.dataset.saldo
          });
        });
      });

      overlay.addEventListener('click', function(e){
        if(e.target === overlay) close();
      });

      closeBtn?.addEventListener('click', close);
      okBtn?.addEventListener('click', close);

      document.addEventListener('keydown', function(e){
        if(e.key === 'Escape' && overlay.classList.contains('show')){
          close();
        }
      });
    })();

    // Product sparkline chart - smooth streaming realtime visual
    (function(){
      const charts = Array.from(document.querySelectorAll('.js-product-chart'));
      if(!charts.length) return;

      const WIDTH = 120;
      const HEIGHT = 52;
      const MIN_Y = 8;
      const MAX_Y = 42;

      function seededRandom(seed){
        let value = seed % 2147483647;
        if(value <= 0) value += 2147483646;

        return function(){
          value = (value * 16807) % 2147483647;
          return (value - 1) / 2147483646;
        };
      }

      function clamp(value, min, max){
        return Math.max(min, Math.min(max, value));
      }

      function catmullRomToBezier(points){
        if(!points.length) return '';
        if(points.length === 1) return `M ${points[0].x} ${points[0].y}`;

        let d = `M ${points[0].x.toFixed(2)} ${points[0].y.toFixed(2)}`;

        for(let i = 0; i < points.length - 1; i++){
          const p0 = points[i - 1] || points[i];
          const p1 = points[i];
          const p2 = points[i + 1];
          const p3 = points[i + 2] || p2;

          const cp1x = p1.x + (p2.x - p0.x) / 6;
          const cp1y = p1.y + (p2.y - p0.y) / 6;
          const cp2x = p2.x - (p3.x - p1.x) / 6;
          const cp2y = p2.y - (p3.y - p1.y) / 6;

          d += ` C ${cp1x.toFixed(2)} ${cp1y.toFixed(2)}, ${cp2x.toFixed(2)} ${cp2y.toFixed(2)}, ${p2.x.toFixed(2)} ${p2.y.toFixed(2)}`;
        }

        return d;
      }

      function buildAreaPath(points){
        if(!points.length) return '';

        const linePath = catmullRomToBezier(points);
        const first = points[0];
        const last = points[points.length - 1];

        return `${linePath} L ${last.x.toFixed(2)} ${HEIGHT} L ${first.x.toFixed(2)} ${HEIGHT} Z`;
      }

      function getLastVisiblePoint(points){
        for(let i = points.length - 1; i >= 0; i--){
          if(points[i].x <= WIDTH){
            return points[i];
          }
        }
        return points[points.length - 1];
      }

      function createState(chart, index){
        const seed = Number(chart.dataset.seed || 1) + ((index + 1) * 97);
        const price = Number(chart.dataset.price || 0);
        const profit = Number(chart.dataset.profit || 0);
        const total = Number(chart.dataset.total || 0);

        const rnd = seededRandom(seed);
        const strength = clamp((profit + total) / Math.max(price, 1), 0.08, 0.85);

        const spacing = 12 + rnd() * 3.5;
        const scrollSpeed = 0.16 + rnd() * 0.20;

        const waveAmp1 = 7.5 + rnd() * 4.5 + strength * 5.5;
        const waveAmp2 = 3.5 + rnd() * 3.2;
        const volatility = 0.65 + rnd() * 0.75 + strength * 0.55;
        const driftBias = (rnd() - 0.5) * 0.14;
        const phase1 = rnd() * Math.PI * 2;
        const phase2 = rnd() * Math.PI * 2;
        const pulseDelay = -(rnd() * 2.2);

        const line = chart.querySelector('.rb-chart-line');
        const area = chart.querySelector('.rb-chart-area');
        const dot = chart.querySelector('.rb-chart-dot');

        if(dot){
          dot.style.animationDelay = `${pulseDelay}s`;
        }

        const state = {
          chart,
          line,
          area,
          dot,
          rnd,
          spacing,
          scrollSpeed,
          waveAmp1,
          waveAmp2,
          volatility,
          driftBias,
          phase1,
          phase2,
          time: rnd() * 50,
          points: [],
          yBase: 24 + (rnd() - 0.5) * 8,
          lastTimeBucket: 0
        };

        let x = -spacing * 3;
        let y = clamp(state.yBase, MIN_Y, MAX_Y);

        while(x <= WIDTH + spacing * 3){
          y = nextY(state, y, true);
          state.points.push({ x, y });
          x += spacing;
        }

        return state;
      }

      function nextY(state, prevY, initial = false){
        state.time += initial ? 0.55 : 0.42;

        const wave1 = Math.sin(state.time * 0.82 + state.phase1) * state.waveAmp1;
        const wave2 = Math.sin(state.time * 0.37 + state.phase2) * state.waveAmp2;
        const noise = (state.rnd() - 0.5) * (state.volatility * 8.5);

        let next = prevY + (wave1 * 0.22) + (wave2 * 0.26) + noise + state.driftBias;
        next = (prevY * 0.50) + (next * 0.50);

        return clamp(next, MIN_Y, MAX_Y);
      }

      const states = charts.map(createState);
      let lastTs = performance.now();

      function render(ts){
        const delta = Math.min(34, ts - lastTs);
        lastTs = ts;

        states.forEach((state) => {
          if(!state.line || !state.area || !state.dot) return;

          const move = state.scrollSpeed * (delta / 16.6667);

          state.points.forEach((point) => {
            point.x -= move;
          });

          while(state.points.length && state.points[0].x < -state.spacing * 2){
            state.points.shift();
          }

          while(state.points[state.points.length - 1].x < WIDTH + state.spacing * 2){
            const lastPoint = state.points[state.points.length - 1];
            const newX = lastPoint.x + state.spacing;
            const newY = nextY(state, lastPoint.y, false);

            state.points.push({
              x: newX,
              y: newY
            });
          }

          const linePath = catmullRomToBezier(state.points);
          const areaPath = buildAreaPath(state.points);
          const lastVisible = getLastVisiblePoint(state.points);

          state.line.setAttribute('d', linePath);
          state.area.setAttribute('d', areaPath);
          state.dot.setAttribute('cx', lastVisible.x.toFixed(2));
          state.dot.setAttribute('cy', lastVisible.y.toFixed(2));
        });

        requestAnimationFrame(render);
      }

      requestAnimationFrame(render);
    })();

    // Promo image popup
(function(){
  const KEY = 'rubik_promo_popup_v1';
  const overlay = document.getElementById('promoOverlay');
  const closeBtn = document.getElementById('promoCloseBtn');

  if(!overlay || !closeBtn) return;

  function openPromo(){
    overlay.classList.add('show');

    if(typeof lockScroll === 'function'){
      lockScroll();
    }else{
      document.documentElement.style.overflow = 'hidden';
      document.body.style.overflow = 'hidden';
    }
  }

  function closePromo(){
    overlay.classList.remove('show');

    if(typeof unlockScroll === 'function'){
      unlockScroll();
    }else{
      document.documentElement.style.overflow = '';
      document.body.style.overflow = '';
    }

    try{
      localStorage.setItem(KEY, '1');
    }catch(e){}
  }

  closeBtn.addEventListener('click', closePromo);

  overlay.addEventListener('click', function(e){
    if(e.target === overlay){
      closePromo();
    }
  });

  document.addEventListener('keydown', function(e){
    if(e.key === 'Escape' && overlay.classList.contains('show')){
      closePromo();
    }
  });

  let seen = false;

  try{
    seen = localStorage.getItem(KEY) === '1';
  }catch(e){}

  if(!seen){
    setTimeout(openPromo, 700);
  }
})();
  </script>

<script>
  (function(){
    const slider = document.getElementById('dashboardPromoSlider');
    if(!slider) return;

    const viewport = slider.querySelector('.rb-dashboard-slider-viewport');
    const track = slider.querySelector('.rb-dashboard-slider-track');
    const originalSlides = Array.from(slider.querySelectorAll('.rb-dashboard-slide'));
    const dots = Array.from(slider.querySelectorAll('.rb-dashboard-dot'));

    if(!viewport || !track || originalSlides.length <= 1) return;

    const firstClone = originalSlides[0].cloneNode(true);
    const lastClone = originalSlides[originalSlides.length - 1].cloneNode(true);

    firstClone.classList.add('is-clone');
    lastClone.classList.add('is-clone');

    track.insertBefore(lastClone, originalSlides[0]);
    track.appendChild(firstClone);

    let slides = Array.from(track.querySelectorAll('.rb-dashboard-slide'));
    let currentIndex = 1;
    let realIndex = 0;

    let timer = null;
    let isDragging = false;
    let isAnimating = false;
    let startX = 0;
    let currentX = 0;
    let moveX = 0;
    let slideWidth = viewport.clientWidth;

    const delay = 3200;
    const threshold = 45;

    function setTrackTransition(active){
      track.style.transition = active
        ? 'transform .48s cubic-bezier(.22,.8,.22,1)'
        : 'none';
    }

    function setTranslate(px){
      track.style.transform = 'translateX(' + px + 'px)';
    }

    function goToIndex(index, animated = true){
      slideWidth = viewport.clientWidth;
      currentIndex = index;

      setTrackTransition(animated);
      setTranslate(-currentIndex * slideWidth);

      realIndex = getRealIndex();
      updateDots();
    }

    function getRealIndex(){
      if(currentIndex === 0) {
        return originalSlides.length - 1;
      }

      if(currentIndex === slides.length - 1) {
        return 0;
      }

      return currentIndex - 1;
    }

    function updateDots(){
      dots.forEach(function(dot, i){
        dot.classList.toggle('active', i === realIndex);
      });
    }

    function nextSlide(){
      if(isAnimating) return;
      isAnimating = true;
      goToIndex(currentIndex + 1, true);
    }

    function prevSlide(){
      if(isAnimating) return;
      isAnimating = true;
      goToIndex(currentIndex - 1, true);
    }

    function startAuto(){
      stopAuto();

      timer = setInterval(function(){
        nextSlide();
      }, delay);
    }

    function stopAuto(){
      if(timer){
        clearInterval(timer);
        timer = null;
      }
    }

    track.addEventListener('transitionend', function(){
      isAnimating = false;

      if(currentIndex === slides.length - 1){
        currentIndex = 1;
        goToIndex(currentIndex, false);
      }

      if(currentIndex === 0){
        currentIndex = slides.length - 2;
        goToIndex(currentIndex, false);
      }
    });

    dots.forEach(function(dot, i){
      dot.addEventListener('click', function(e){
        e.preventDefault();
        e.stopPropagation();

        stopAuto();
        isAnimating = true;
        goToIndex(i + 1, true);
        startAuto();
      });
    });

    function onDragStart(e){
      if(e.pointerType === 'mouse' && e.button !== 0) return;

      stopAuto();
      isDragging = true;
      isAnimating = false;

      startX = e.clientX;
      currentX = startX;
      moveX = 0;
      slideWidth = viewport.clientWidth;

      track.classList.add('is-dragging');
      setTrackTransition(false);

      if(track.setPointerCapture){
        track.setPointerCapture(e.pointerId);
      }
    }

    function onDragMove(e){
      if(!isDragging) return;

      currentX = e.clientX;
      moveX = currentX - startX;

      setTranslate((-currentIndex * slideWidth) + moveX);
    }

    function onDragEnd(e){
      if(!isDragging) return;

      isDragging = false;
      track.classList.remove('is-dragging');

      if(track.releasePointerCapture){
        try {
          track.releasePointerCapture(e.pointerId);
        } catch(err) {}
      }

      if(moveX <= -threshold){
        isAnimating = true;
        goToIndex(currentIndex + 1, true);
      }else if(moveX >= threshold){
        isAnimating = true;
        goToIndex(currentIndex - 1, true);
      }else{
        goToIndex(currentIndex, true);
      }

      startAuto();
    }

    track.addEventListener('pointerdown', onDragStart);
    track.addEventListener('pointermove', onDragMove);
    track.addEventListener('pointerup', onDragEnd);
    track.addEventListener('pointercancel', onDragEnd);
    track.addEventListener('pointerleave', function(e){
      if(isDragging) onDragEnd(e);
    });

    window.addEventListener('resize', function(){
      slideWidth = viewport.clientWidth;
      goToIndex(currentIndex, false);
    });

    slider.addEventListener('mouseenter', stopAuto);
    slider.addEventListener('mouseleave', startAuto);

    goToIndex(1, false);
    startAuto();
  })();
</script>


<script>
(function(){
  const root = document.getElementById('inviteBannerSlider');
  if(!root) return;

  const track = root.querySelector('.rb-invite-slider-track');
  const slides = Array.from(root.querySelectorAll('.rb-invite-slide'));
  const prevBtn = root.querySelector('[data-invite-prev]');
  const nextBtn = root.querySelector('[data-invite-next]');
  const dots = Array.from(document.querySelectorAll('[data-invite-dot]'));

  if(!track || !slides.length) return;

  let current = 0;
  let autoPlay = null;
  const total = slides.length;

  function updateSlider(){
    track.style.transform = 'translate3d(-' + (current * 100) + '%, 0, 0)';

    dots.forEach(function(dot, index){
      dot.classList.toggle('active', index === current);
    });
  }

  function goTo(index){
    if(index < 0){
      current = total - 1;
    }else if(index >= total){
      current = 0;
    }else{
      current = index;
    }
    updateSlider();
  }

  function next(){
    goTo(current + 1);
  }

  function prev(){
    goTo(current - 1);
  }

  function startAuto(){
    if(total <= 1) return;
    stopAuto();
    autoPlay = setInterval(next, 4500);
  }

  function stopAuto(){
    if(autoPlay){
      clearInterval(autoPlay);
      autoPlay = null;
    }
  }

  if(prevBtn){
    prevBtn.addEventListener('click', function(){
      prev();
      startAuto();
    });
  }

  if(nextBtn){
    nextBtn.addEventListener('click', function(){
      next();
      startAuto();
    });
  }

  dots.forEach(function(dot){
    dot.addEventListener('click', function(){
      const index = Number(dot.getAttribute('data-invite-dot') || 0);
      goTo(index);
      startAuto();
    });
  });

  root.addEventListener('mouseenter', stopAuto);
  root.addEventListener('mouseleave', startAuto);
  root.addEventListener('touchstart', stopAuto, { passive:true });
  root.addEventListener('touchend', startAuto, { passive:true });

  updateSlider();
  startAuto();
})();
</script>
</body>
</html>