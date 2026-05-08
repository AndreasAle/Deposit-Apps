
@php
    $referralInputValue = old('referral_code');
    $isReferralLocked = false;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Daftar Akun | Crowdnik</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --green:#18c79b;
      --green-2:#42dfb2;
      --green-3:#0b8f70;
      --green-dark:#0d7f67;

      --dark:#031816;
      --dark-2:#05231f;
      --dark-3:#010f0d;

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
      background:#ffffff;
      -webkit-tap-highlight-color:transparent;
    }

    body::before,
    body::after{
      content:none !important;
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
    }

    .shell{
      position:relative;
      overflow:hidden;
      width:100%;
      max-width:none;
      min-height:100vh;
      border-radius:0;
      background:#ffffff;
      border:none;
      box-shadow:none;
    }

    .shell::before{
      content:none !important;
    }

    .hero{
      position:relative;
      width:100%;
      height:176px;
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

    .heroShape{
      position:absolute;
      pointer-events:none;
    }

    .shapeOne{
      width:170px;
      height:130px;
      right:-28px;
      top:26px;
      border-radius:48% 52% 58% 42% / 42% 48% 52% 58%;
      background:
        radial-gradient(circle at 26% 22%, rgba(255,255,255,.10), transparent 34%),
        linear-gradient(135deg, rgba(0,223,130,.16), rgba(3,98,76,.40));
      box-shadow:0 0 40px rgba(0,223,130,.10);
      animation:shapeMoveOne 6.2s ease-in-out infinite;
    }

    .shapeTwo{
      width:86px;
      height:86px;
      right:56px;
      top:82px;
      border-radius:999px;
      background:
        radial-gradient(circle at 32% 24%, rgba(255,255,255,.50), rgba(204,255,240,.20) 34%, rgba(0,223,130,.12) 100%);
      border:1px solid rgba(255,255,255,.10);
      box-shadow:
        0 18px 30px rgba(0,0,0,.18),
        0 0 30px rgba(0,223,130,.12);
      animation:shapeMoveTwo 5.6s ease-in-out infinite;
    }

    .shapeThree{
      width:126px;
      height:104px;
      left:90px;
      top:-44px;
      border-radius:38% 62% 64% 36% / 40% 44% 56% 60%;
      background:
        linear-gradient(145deg, rgba(214,255,240,.22), rgba(0,223,130,.08));
      border:1px solid rgba(255,255,255,.07);
      animation:shapeMoveThree 7s ease-in-out infinite;
    }

    .backBubble{
      position:absolute;
      top:20px;
      left:20px;
      z-index:5;
      width:auto;
      height:auto;
      padding:0;
      border-radius:0;
      background:transparent;
      box-shadow:none;
      border:none;
      display:inline-flex;
      align-items:center;
      justify-content:flex-start;
      gap:5px;
      color:#ffffff;
      font-size:12px;
      font-weight:800;
      text-decoration:none;
    }

    .backBubble span{
      font-size:22px;
      line-height:1;
      font-weight:700;
      margin-top:-2px;
    }

    .card{
      position:relative;
      z-index:5;
      width:100%;
      margin:-30px 0 0;
      min-height:calc(100vh - 146px);
      background:
        radial-gradient(220px 140px at 100% 100%, rgba(98,221,177,.22), transparent 60%),
        radial-gradient(180px 120px at 78% 18%, rgba(255,255,255,.16), transparent 58%),
        linear-gradient(135deg, #e8fbf2 0%, #d7f5e7 52%, #c8efd9 100%);
      border:none;
      border-radius:28px 28px 0 0;
      padding:22px 24px 24px;
      box-shadow:0 -12px 32px rgba(6,34,29,.10);
      animation:cardFloat .55s ease both;
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

    .brandInside{
      display:flex;
      flex-direction:column;
      align-items:center;
      text-align:center;
      margin:0 0 16px;
    }

    .logoBox{
      width:70px;
      height:70px;
      border-radius:24px;
      background:#ffffff;
      border:1px solid rgba(24,199,155,.16);
      box-shadow:
        0 16px 34px rgba(10,47,39,.10),
        0 0 0 1px rgba(255,255,255,.75) inset;
      display:grid;
      place-items:center;
      overflow:hidden;
      margin-bottom:10px;
    }

    .logoBox img{
      width:50px;
      height:50px;
      object-fit:contain;
      display:block;
    }

    .brandTitle{
      margin:0;
      font-size:15px;
      line-height:1.15;
      font-weight:800;
      color:#0d7f67;
      letter-spacing:-.01em;
    }

    .title{
      text-align:center;
      margin:0;
      font-size:25px;
      line-height:1.08;
      font-weight:800;
      color:#173d35;
      letter-spacing:-.04em;
    }

    .subtitle{
      text-align:center;
      margin:9px 0 18px;
      color:#6c8b82;
      font-size:13px;
      line-height:1.5;
      font-weight:500;
    }

    .error{
      margin-bottom:14px;
      padding:11px 12px;
      border-radius:14px;
      background:rgba(239,68,68,.08);
      border:1px solid rgba(239,68,68,.18);
      color:#b42318;
      font-size:12.5px;
      line-height:1.45;
      font-weight:500;
    }

    .error ul{
      margin:0;
      padding-left:18px;
    }

    .error li{
      margin:4px 0;
    }

    .field{
      margin-bottom:12px;
    }

    .label{
      display:block;
      margin:0 0 7px;
      font-size:11.5px;
      line-height:1.2;
      color:#668078;
      font-weight:700;
    }

    .inputWrap{
      position:relative;
    }

    .input{
      width:100%;
      height:50px;
      border-radius:15px;
      border:1px solid rgba(13,127,103,.14);
      background:
        radial-gradient(circle at 90% 0%, rgba(255,255,255,.72), transparent 42%),
        linear-gradient(135deg, rgba(238,255,248,.92), rgba(216,246,233,.82));
      outline:none;
      padding:0 14px;
      font-size:13.5px;
      color:#10322c;
      font-weight:700;
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

    .input.with-icon{
      padding-left:48px;
    }

    .input.input-phone{
      padding-left:74px;
    }

    .input.input-password{
      padding-right:48px;
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
      font-weight:500;
    }

    .prefixIcon{
      position:absolute;
      top:50%;
      left:14px;
      transform:translateY(-50%);
      z-index:3;
      width:28px;
      height:28px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
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
      display:block;
    }

    .prefix62{
      position:absolute;
      top:50%;
      left:14px;
      transform:translateY(-50%);
      z-index:3;
      min-width:46px;
      height:28px;
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
      font-weight:850;
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,.68),
        0 6px 12px rgba(6,34,29,.045);
      pointer-events:none;
    }

    .togglePass{
      position:absolute;
      top:50%;
      right:9px;
      transform:translateY(-50%);
      width:34px;
      height:34px;
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
      transition:
        transform .18s ease,
        background .18s ease,
        color .18s ease,
        box-shadow .18s ease;
    }

    .togglePass:hover{
      transform:translateY(-50%) translateY(-1px);
      color:#03624C;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.82), transparent 40%),
        linear-gradient(135deg, rgba(241,255,250,.98), rgba(184,236,212,.90));
      box-shadow:
        0 8px 16px rgba(6,34,29,.075),
        0 0 0 3px rgba(0,201,122,.08),
        inset 0 1px 0 rgba(255,255,255,.74);
    }

    .togglePass svg{
      width:18px;
      height:18px;
      display:block;
      stroke-width:2.1;
    }

    .hint{
      margin:7px 0 0;
      font-size:11.5px;
      line-height:1.45;
      color:#6e877f;
      font-weight:500;
    }

    .helperRow{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:10px;
      margin:9px 0 15px;
      flex-wrap:wrap;
    }

    .helperText{
      font-size:11.5px;
      line-height:1.4;
      color:#6e877f;
      font-weight:500;
    }

    .helperLink{
      font-size:11.5px;
      line-height:1.4;
      color:#0d7f67;
      font-weight:800;
      text-decoration:none;
    }

    .helperLink:hover{
      text-decoration:underline;
    }

    .btn{
      width:100%;
      height:52px;
      border:none;
      border-radius:14px;
      cursor:pointer;
      color:#ffffff;
      font-size:14px;
      font-weight:800;
      letter-spacing:.01em;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.16), transparent 34%),
        linear-gradient(135deg, #031816 0%, #0a2f27 35%, #0d5c46 68%, #00c97a 100%);
      box-shadow:
        0 14px 28px rgba(0,0,0,.18),
        0 10px 26px rgba(0,223,130,.18),
        inset 0 1px 0 rgba(255,255,255,.12);
      transition:
        transform .2s ease,
        box-shadow .2s ease,
        filter .2s ease,
        background .2s ease;
      position:relative;
      overflow:hidden;
    }

    .btn::before{
      content:"";
      position:absolute;
      inset:0;
      background:
        linear-gradient(180deg, rgba(255,255,255,.08), transparent 40%);
      pointer-events:none;
    }

    .btn::after{
      content:"";
      position:absolute;
      top:0;
      left:-120%;
      width:55%;
      height:100%;
      background:linear-gradient(
        to right,
        transparent,
        rgba(255,255,255,.22),
        transparent
      );
      transform:skewX(-18deg);
      animation:btnShine 3.2s infinite;
      pointer-events:none;
    }

    .btn:hover{
      transform:translateY(-1px);
      filter:brightness(1.03);
      box-shadow:
        0 18px 34px rgba(0,0,0,.22),
        0 14px 34px rgba(0,223,130,.22),
        inset 0 1px 0 rgba(255,255,255,.14);
    }

    .btn:active{
      transform:translateY(0);
      filter:brightness(.99);
    }

    .footer{
      margin-top:14px;
      text-align:center;
      font-size:12.5px;
      line-height:1.5;
      color:#6c8b82;
      font-weight:500;
    }

    .footer a{
      color:#0d7f67;
      font-weight:800;
      text-decoration:none;
    }

    .footer a:hover{
      text-decoration:underline;
    }

    @keyframes pageEnter{
      from{
        opacity:0;
        transform:translateY(12px);
      }
      to{
        opacity:1;
        transform:translateY(0);
      }
    }

    @keyframes cardFloat{
      from{
        opacity:0;
        transform:translateY(14px);
      }
      to{
        opacity:1;
        transform:translateY(0);
      }
    }

    @keyframes blobFloat{
      0%,100%{
        transform:translate3d(0,0,0) rotate(0deg);
      }
      50%{
        transform:translate3d(-8px,8px,0) rotate(5deg);
      }
    }

    @keyframes blobFloat2{
      0%,100%{
        transform:translate3d(0,0,0);
      }
      50%{
        transform:translate3d(7px,-7px,0);
      }
    }

    @keyframes shapeMoveOne{
      0%,100%{
        transform:translate3d(0,0,0) rotate(0deg);
      }
      50%{
        transform:translate3d(-8px,6px,0) rotate(4deg);
      }
    }

    @keyframes shapeMoveTwo{
      0%,100%{
        transform:translate3d(0,0,0) scale(1);
      }
      50%{
        transform:translate3d(-4px,-8px,0) scale(1.04);
      }
    }

    @keyframes shapeMoveThree{
      0%,100%{
        transform:translate3d(0,0,0) rotate(0deg);
      }
      50%{
        transform:translate3d(7px,5px,0) rotate(-4deg);
      }
    }

    @keyframes btnShine{
      0%{
        left:-120%;
      }
      20%{
        left:180%;
      }
      100%{
        left:180%;
      }
    }

    @media (max-width:380px){
      body{
        padding:0;
      }

      .page{
        max-width:none;
      }

      .hero{
        height:168px;
      }

      .card{
        padding:22px 20px 22px;
      }

      .title{
        font-size:24px;
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

    .input.is-referral-locked{
  color:#0d7f67;
  font-weight:900;
  letter-spacing:.04em;
  cursor:not-allowed;
  background:
    radial-gradient(circle at 90% 0%, rgba(255,255,255,.82), transparent 42%),
    linear-gradient(135deg, rgba(232,255,246,.98), rgba(200,246,226,.92));
  border-color:rgba(13,127,103,.22);
  box-shadow:
    0 0 0 3px rgba(0,201,122,.08),
    0 10px 22px rgba(6,34,29,.045),
    inset 0 1px 0 rgba(255,255,255,.68);
}

.input.is-referral-locked::selection{
  background:rgba(13,127,103,.16);
}
  </style>
</head>
<body>
  <main class="page">
    <section class="shell" role="region" aria-label="Daftar Crowdnik">

      <div class="hero">
        <a href="/login" class="backBubble" aria-label="Kembali ke login">
          <span>‹</span>
          Back
        </a>

        <div class="heroShape shapeOne"></div>
        <div class="heroShape shapeTwo"></div>
        <div class="heroShape shapeThree"></div>
      </div>

      <div class="card">
        <div class="brandInside">
          <div class="logoBox">
            <img src="/logo.png" alt="Crowdnik">
          </div>
        </div>

        <h1 class="title">Buat Akun</h1>
        <p class="subtitle">Daftar untuk mulai memakai akun Rubik.</p>

        {{-- ERROR VALIDATION --}}
        @if ($errors->any())
          <div class="error">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" autocomplete="off" novalidate>
          @csrf

          <div class="field">
            <label class="label" for="name">Nama Panggilan</label>
            <div class="inputWrap">
              <span class="prefixIcon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M20 21a8 8 0 0 0-16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  <path d="M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </span>

<input
  class="input with-icon"
  id="name"
  type="text"
  name="name"
  value="{{ old('name') }}"
  placeholder="Masukkan nama panggilan"
  required
/>
            </div>
          </div>

          <div class="field">
            <label class="label" for="phone">Nomor Telepon</label>
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
            <label class="label" for="referral_code">Kode Referral <span style="font-weight:500;">(Opsional)</span></label>
            <div class="inputWrap">
              <span class="prefixIcon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M20 12v10H4V12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M22 7H2v5h20V7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M12 22V7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  <path d="M12 7H7.5a2.5 2.5 0 1 1 0-5C11 2 12 7 12 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M12 7h4.5a2.5 2.5 0 1 0 0-5C13 2 12 7 12 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </span>

       <input
  class="input with-icon {{ $isReferralLocked ? 'is-referral-locked' : '' }}"
  id="referral_code"
  type="text"
  name="referral_code"
  value="{{ $referralInputValue }}"
  placeholder="Masukkan kode referral"
  autocomplete="off"
  {{ $isReferralLocked ? 'readonly' : '' }}
  data-locked-referral="{{ $isReferralLocked ? $lockedReferralCode : '' }}"
/>
            </div>


          </div>

          <div class="field">
            <label class="label" for="password">Password</label>
            <div class="inputWrap">
              <span class="prefixIcon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="currentColor" stroke-width="2.1" stroke-linecap="round"/>
                  <path d="M6 11h12a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </span>

              <input
                class="input with-icon input-password"
                id="password"
                type="password"
                name="password"
                placeholder="Buat password"
                required
              />

              <button class="togglePass" type="button" onclick="togglePassword()" aria-label="Tampilkan password">
                <svg id="eyeIcon" viewBox="0 0 24 24" fill="none">
                  <path d="M1.5 12s4-7.5 10.5-7.5S22.5 12 22.5 12 18.5 19.5 12 19.5 1.5 12 1.5 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </button>
            </div>

            <div class="hint">
              Gunakan kombinasi huruf dan angka agar lebih aman.
            </div>
          </div>

          {{-- Honeypot anti bot --}}
          <input type="text" name="website" tabindex="-1" autocomplete="off" style="display:none">

          <div class="helperRow">
            <div class="helperText">Dengan daftar, kamu menyetujui ketentuan Rubik.</div>
            <a class="helperLink" href="/login">Masuk</a>
          </div>

          <button class="btn" type="submit">Daftar</button>
        </form>

        <div class="footer">
          Sudah punya akun? <a href="/login">Masuk sekarang</a>
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

  <!-- <script>
  (function lockReferralInput(){
    const input = document.getElementById('referral_code');
    if(!input) return;

    const lockedValue = input.dataset.lockedReferral || '';

    if(!lockedValue) return;

    input.value = lockedValue;
    input.readOnly = true;

    input.addEventListener('input', function(){
      this.value = lockedValue;
    });

    input.addEventListener('paste', function(e){
      e.preventDefault();
      this.value = lockedValue;
    });

    input.addEventListener('keydown', function(e){
      const allowedKeys = ['Tab', 'Shift', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'];
      if(!allowedKeys.includes(e.key)){
        e.preventDefault();
      }
    });
  })();
</script> -->
</body>
</html>