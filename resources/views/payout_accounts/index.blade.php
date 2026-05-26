@include('partials.anti-inspect')
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Rekening Penarikan | Velora Finance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --vl-bg:#f7f2fa;
      --vl-bg2:#eee8f6;
      --vl-paper:#ffffff;
      --vl-paper2:#fbf8ff;
      --vl-text:#2b0b16;
      --vl-maroon:#3a0712;
      --vl-soft:#7b6370;
      --vl-muted:#a894a0;
      --vl-border:rgba(43,11,22,.085);
      --vl-border2:rgba(43,11,22,.14);
      --vl-gold:#f5af2a;
      --vl-gold2:#ffd46d;
      --vl-purple:#8f57ff;
      --vl-violet:#d96bff;
      --vl-pink:#d96bff;
      --vl-red:#e24a64;
      --vl-green:#20b873;
      --vl-gradient:linear-gradient(135deg,#f5af2a 0%,#ffd46d 26%,#d96bff 58%,#8f57ff 100%);
      --vl-gradient-soft:linear-gradient(145deg,#8f57ff 0%,#9f55ff 40%,#d96bff 72%,#f5af2a 100%);
      --vl-shadow:0 26px 68px rgba(88,43,145,.16);
      --vl-shadow-soft:0 14px 36px rgba(43,11,22,.075);
    }

    *{box-sizing:border-box}

    html,body{min-height:100%}

    body{
      margin:0;
      font-family:"Plus Jakarta Sans", Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      color:var(--vl-text);
      background:
        radial-gradient(680px 360px at 50% -150px, rgba(245,175,42,.23), transparent 64%),
        radial-gradient(520px 340px at 100% 4%, rgba(217,107,255,.18), transparent 62%),
        radial-gradient(520px 330px at -12% 34%, rgba(143,87,255,.13), transparent 58%),
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
      mask-image:linear-gradient(180deg, rgba(0,0,0,.46), transparent 76%);
      -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.46), transparent 76%);
      opacity:.48;
      z-index:0;
    }

    a{color:inherit;text-decoration:none}
    button,input,select{font-family:inherit}

    .bank-page{
      width:100%;
      min-height:100vh;
      display:flex;
      justify-content:center;
      padding:14px 10px 0;
      position:relative;
      z-index:1;
    }

    .bank-phone{
      width:100%;
      max-width:430px;
      min-height:100vh;
      position:relative;
      padding:8px 4px 96px;
    }

    .bank-header{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:16px;
      padding:0 2px;
    }

    .bank-brand{
      display:flex;
      align-items:center;
      gap:12px;
      min-width:0;
    }

    .bank-logo{
      width:52px;
      height:52px;
      border-radius:19px;
      display:grid;
      place-items:center;
      overflow:hidden;
      background:
        radial-gradient(circle at 28% 8%, rgba(255,255,255,.98), rgba(255,226,155,.78) 34%, rgba(225,188,255,.76) 92%),
        var(--vl-gradient);
      border:1px solid rgba(255,255,255,.68);
      box-shadow:0 16px 34px rgba(88,43,145,.13), 0 8px 22px rgba(245,175,42,.10), inset 0 1px 0 rgba(255,255,255,.72);
      flex:0 0 auto;
    }

    .bank-logo img{
      width:46px;
      height:46px;
      object-fit:contain;
      display:block;
    }

    .bank-title-wrap{min-width:0}

    .bank-title-wrap span{
      display:block;
      margin-bottom:6px;
      font-size:10px;
      line-height:1;
      letter-spacing:.18em;
      text-transform:uppercase;
      font-weight:900;
      color:rgba(58,7,18,.58);
    }

    .bank-title-wrap h1{
      margin:0;
      color:var(--vl-maroon);
      font-size:23px;
      line-height:1;
      font-weight:900;
      letter-spacing:-.052em;
      white-space:nowrap;
    }

    .bank-header-actions{
      display:flex;
      align-items:center;
      gap:9px;
      flex:0 0 auto;
    }

    .bank-header-btn{
      width:42px;
      height:42px;
      border-radius:999px;
      border:1px solid rgba(43,11,22,.08);
      background:rgba(255,255,255,.84);
      color:#5b2841;
      display:grid;
      place-items:center;
      box-shadow:0 12px 26px rgba(43,11,22,.065), inset 0 1px 0 rgba(255,255,255,.92);
      backdrop-filter:blur(18px);
      -webkit-backdrop-filter:blur(18px);
      transition:.18s ease;
    }

    .bank-header-btn:hover{transform:translateY(-1px);color:var(--vl-purple)}
    .bank-header-btn svg{width:20px;height:20px}

    .bank-hero{
      position:relative;
      overflow:hidden;
      border-radius:34px;
      color:#fff;
      background:
        radial-gradient(360px 220px at 92% -12%, rgba(255,212,109,.48), transparent 58%),
        radial-gradient(300px 200px at 2% 8%, rgba(217,107,255,.34), transparent 62%),
        linear-gradient(145deg,#8f57ff 0%,#9455ff 40%,#d96bff 72%,#f5af2a 100%);
      border:1px solid rgba(255,255,255,.44);
      box-shadow:0 28px 62px rgba(143,87,255,.22), 0 18px 42px rgba(245,175,42,.10), inset 0 1px 0 rgba(255,255,255,.22);
      padding:18px;
      margin-bottom:14px;
      animation:bankFadeUp .42s ease both;
    }

    .bank-hero::before{
      content:"";
      position:absolute;
      inset:0;
      pointer-events:none;
      background:linear-gradient(135deg, rgba(255,255,255,.22), transparent 34%), radial-gradient(circle at 82% 26%, rgba(255,255,255,.16), transparent 28%), linear-gradient(180deg, transparent 0%, rgba(43,11,22,.08) 100%);
    }

    .bank-hero::after{
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

    .bank-hero-inner{
      position:relative;
      z-index:1;
      display:grid;
      grid-template-columns:minmax(0,1fr) auto;
      gap:14px;
      align-items:start;
    }

    .bank-hero-label{
      margin:0 0 8px;
      color:rgba(255,255,255,.74);
      font-size:12px;
      font-weight:700;
    }

    .bank-hero-title{
      margin:0;
      color:#fff;
      font-size:28px;
      line-height:1.05;
      letter-spacing:-.06em;
      font-weight:900;
      text-shadow:0 12px 24px rgba(43,11,22,.22);
    }

    .bank-hero-sub{
      margin:9px 0 0;
      max-width:260px;
      color:rgba(255,255,255,.72);
      font-size:11.5px;
      line-height:1.45;
      font-weight:650;
    }

    .bank-hero-pill{
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
      font-weight:900;
      white-space:nowrap;
    }

    .bank-hero-pill svg{width:15px;height:15px;color:#2b0b16}

    .bank-loader{
      color:var(--vl-soft);
      text-align:center;
      font-size:12px;
      font-weight:750;
      padding:26px 12px;
      border-radius:24px;
      border:1px dashed rgba(43,11,22,.16);
      background:rgba(255,255,255,.78);
      box-shadow:var(--vl-shadow-soft);
    }

    .bank-empty{
      min-height:360px;
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      text-align:center;
      padding:28px 16px;
      border-radius:30px;
      background:radial-gradient(260px 140px at 50% 0%, rgba(217,107,255,.14), transparent 62%), radial-gradient(250px 140px at 90% 100%, rgba(245,175,42,.12), transparent 64%), rgba(255,255,255,.92);
      border:1px dashed rgba(43,11,22,.16);
      box-shadow:var(--vl-shadow-soft), inset 0 1px 0 rgba(255,255,255,.92);
      animation:bankFadeUp .42s ease both;
    }

    .bank-empty-illustration{
      width:88px;
      height:88px;
      border-radius:30px;
      margin-bottom:16px;
      display:grid;
      place-items:center;
      color:#2c1200;
      background:radial-gradient(circle at 30% 0%, rgba(255,255,255,.65), transparent 34%), var(--vl-gradient);
      box-shadow:0 18px 38px rgba(143,87,255,.18), inset 0 1px 0 rgba(255,255,255,.32);
    }

    .bank-empty-illustration svg{width:42px;height:42px}

    .bank-empty-title{
      margin:0;
      font-size:18px;
      line-height:1.2;
      font-weight:900;
      letter-spacing:-.035em;
      color:var(--vl-maroon);
    }

    .bank-empty-text{
      max-width:320px;
      margin:10px auto 18px;
      color:var(--vl-soft);
      font-size:12.5px;
      line-height:1.55;
      font-weight:650;
    }

    .bank-primary{
      width:100%;
      min-height:48px;
      border:0;
      border-radius:999px;
      color:#2c1200;
      background:radial-gradient(circle at 30% 0%, rgba(255,255,255,.55), transparent 34%), linear-gradient(135deg,#ffe08a 0%,#f5af2a 42%,#d96bff 100%);
      box-shadow:0 16px 32px rgba(143,87,255,.18), inset 0 1px 0 rgba(255,255,255,.32);
      font-size:13px;
      font-weight:900;
      cursor:pointer;
      transition:.18s ease;
    }

    .bank-primary:hover{transform:translateY(-1px);filter:brightness(1.03)}
    .bank-primary:disabled{opacity:.65;cursor:not-allowed}

    .bank-list{
      display:flex;
      flex-direction:column;
      gap:12px;
      margin-top:12px;
      animation:bankFadeUp .42s ease both;
    }

    .bank-card{
      border:1px solid rgba(43,11,22,.075);
      border-radius:28px;
      background:radial-gradient(250px 140px at 100% -10%, rgba(217,107,255,.12), transparent 64%), linear-gradient(180deg,rgba(255,255,255,.98),rgba(255,255,255,.90));
      box-shadow:0 14px 34px rgba(43,11,22,.07), inset 0 1px 0 rgba(255,255,255,.94);
      overflow:hidden;
      position:relative;
    }

    .bank-card::before{
      content:"";
      position:absolute;
      inset:0;
      pointer-events:none;
      background:linear-gradient(135deg, rgba(255,255,255,.82), transparent 30%), radial-gradient(circle at 12% 0%, rgba(245,175,42,.08), transparent 44%);
    }

    .bank-card > *{position:relative;z-index:1}

    .bank-card-top{
      padding:14px;
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
    }

    .bank-card-brand{
      display:flex;
      align-items:center;
      gap:11px;
      min-width:0;
    }

    .bank-provider-icon{
      width:48px;
      height:48px;
      border-radius:18px;
      display:grid;
      place-items:center;
      background:#fff;
      border:1px solid rgba(43,11,22,.06);
      box-shadow:0 12px 24px rgba(43,11,22,.09), inset 0 1px 0 rgba(255,255,255,.9);
      overflow:hidden;
      flex:0 0 auto;
      color:var(--vl-maroon);
      font-weight:900;
      font-size:11px;
    }

    .bank-provider-icon img{
      width:38px;
      height:38px;
      object-fit:contain;
      display:block;
    }

    .bank-provider-label{
      color:var(--vl-soft);
      font-size:10px;
      font-weight:800;
      text-transform:uppercase;
      letter-spacing:.08em;
    }

    .bank-provider-name{
      margin-top:4px;
      color:var(--vl-maroon);
      font-size:15px;
      line-height:1.15;
      font-weight:900;
      letter-spacing:-.025em;
    }

    .bank-default{
      min-height:26px;
      padding:0 10px;
      border-radius:999px;
      color:#2c1200;
      background:linear-gradient(135deg,#ffe08a,#f5af2a);
      border:1px solid rgba(255,255,255,.72);
      box-shadow:0 10px 20px rgba(245,175,42,.15);
      font-size:10px;
      font-weight:900;
      display:inline-flex;
      align-items:center;
      white-space:nowrap;
    }

    .bank-card-body{
      display:grid;
      grid-template-columns:1fr 1fr;
      border-top:1px solid rgba(43,11,22,.075);
      background:rgba(251,248,255,.72);
    }

    .bank-info{
      padding:12px 14px;
      min-width:0;
    }

    .bank-info + .bank-info{border-left:1px solid rgba(43,11,22,.065)}

    .bank-info-label{
      display:block;
      color:var(--vl-soft);
      font-size:10.5px;
      font-weight:700;
      margin-bottom:5px;
    }

    .bank-info-value{
      color:var(--vl-maroon);
      font-size:13px;
      font-weight:900;
      letter-spacing:-.01em;
      overflow:hidden;
      text-overflow:ellipsis;
      white-space:nowrap;
    }

    .bank-card-actions{
      padding:12px 14px 14px;
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:9px;
      border-top:1px solid rgba(43,11,22,.07);
      background:rgba(251,248,255,.72);
    }

    .bank-action{
      border:1px solid rgba(43,11,22,.08);
      min-height:38px;
      padding:0 12px;
      border-radius:999px;
      background:#fff;
      color:var(--vl-soft);
      font-size:11.5px;
      font-weight:900;
      cursor:pointer;
      transition:.18s ease;
    }

    .bank-action:hover{transform:translateY(-1px)}

    .bank-action.is-edit{
      color:#2c1200;
      border-color:rgba(255,255,255,.72);
      background:linear-gradient(135deg,#ffe08a,#f5af2a);
      box-shadow:0 10px 20px rgba(245,175,42,.14);
    }

    .bank-action.is-delete{
      color:#d7495c;
      background:#fff1f3;
      border-color:rgba(226,74,100,.12);
    }

    .bank-floating-add{margin-top:2px}

    .bank-overlay{
      position:fixed;
      inset:0;
      z-index:999;
      display:none;
      align-items:flex-end;
      justify-content:center;
      background:rgba(43,11,22,.34);
      backdrop-filter:blur(14px);
      -webkit-backdrop-filter:blur(14px);
    }

    .bank-overlay.show{display:flex}

    .bank-sheet{
      width:min(100%,430px);
      max-height:88vh;
      overflow:auto;
      border-radius:30px 30px 0 0;
      background:radial-gradient(260px 140px at 100% 0%, rgba(217,107,255,.14), transparent 62%), radial-gradient(240px 130px at 0% 100%, rgba(245,175,42,.12), transparent 60%), rgba(255,255,255,.98);
      border:1px solid rgba(255,255,255,.72);
      border-bottom:0;
      box-shadow:0 -34px 90px rgba(43,11,22,.22), inset 0 1px 0 rgba(255,255,255,.92);
      padding:16px 14px calc(16px + env(safe-area-inset-bottom));
      animation:bankSheetUp .22s ease both;
    }

    .bank-sheet-head{
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
      padding-bottom:13px;
      border-bottom:1px solid rgba(43,11,22,.08);
    }

    .bank-sheet-title{
      margin:0;
      color:var(--vl-maroon);
      font-size:18px;
      line-height:1.15;
      font-weight:900;
      letter-spacing:-.04em;
    }

    .bank-close{
      width:40px;
      height:40px;
      border-radius:15px;
      border:1px solid rgba(43,11,22,.08);
      background:#fbf8ff;
      color:#5b2841;
      display:grid;
      place-items:center;
      cursor:pointer;
      flex:0 0 auto;
    }

    .bank-close svg{width:20px;height:20px}

    .bank-form{display:grid;gap:12px}
    .bank-form-group{display:grid;gap:7px}

    .bank-label{
      color:var(--vl-soft);
      font-size:11.5px;
      font-weight:800;
    }

    .bank-input,.bank-select{
      width:100%;
      min-height:52px;
      border-radius:18px;
      border:1px solid rgba(43,11,22,.09);
      outline:0;
      background:#fbf8ff;
      color:var(--vl-maroon);
      padding:0 14px;
      font-size:13px;
      font-weight:750;
      box-shadow:inset 0 1px 0 rgba(255,255,255,.86);
    }

    .bank-select{
      appearance:none;
      background-image:url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='rgba(91,40,65,.72)' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='m6 9 6 6 6-6'/%3e%3c/svg%3e");
      background-repeat:no-repeat;
      background-position:right 14px center;
      background-size:17px;
      padding-right:44px;
    }

    .bank-input:focus,.bank-select:focus{
      border-color:rgba(217,107,255,.28);
      background:#fff;
      box-shadow:0 0 0 4px rgba(217,107,255,.07), inset 0 1px 0 rgba(255,255,255,.9);
    }

    .bank-check{
      display:flex;
      align-items:center;
      gap:10px;
      padding:12px 13px;
      border-radius:18px;
      background:#fbf8ff;
      border:1px solid rgba(43,11,22,.08);
      cursor:pointer;
    }

    .bank-check input{width:17px;height:17px;accent-color:#8f57ff}

    .bank-check-text{
      color:var(--vl-maroon);
      font-size:12.5px;
      font-weight:800;
    }

    .bank-secondary{
      width:100%;
      min-height:46px;
      border-radius:999px;
      border:1px solid rgba(43,11,22,.09);
      background:#fbf8ff;
      color:var(--vl-soft);
      font-size:13px;
      font-weight:900;
      cursor:pointer;
    }

    .bank-sheet-actions{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:4px}

    .bank-toast{
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
      background:linear-gradient(135deg,#ffe08a,#f5af2a,#d96bff);
      box-shadow:0 18px 42px rgba(43,11,22,.20);
      font-size:12px;
      font-weight:850;
      transition:.22s ease;
      white-space:nowrap;
    }

    .bank-toast.is-error{
      color:#fff;
      background:linear-gradient(135deg,#e24a64,#f5af2a);
    }

    .bank-toast.show{
      opacity:1;
      transform:translateX(-50%) translateY(0);
    }

    @keyframes bankFadeUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
    @keyframes bankSheetUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}

    @media(min-width:768px){
      .bank-page{padding:22px 0}
      .bank-phone{min-height:calc(100vh - 44px);border-radius:30px;overflow:hidden}
      .bank-overlay{align-items:center}
      .bank-sheet{border-radius:30px;border-bottom:1px solid rgba(255,255,255,.72)}
    }

    @media(max-width:370px){
      .bank-logo{width:45px;height:45px;border-radius:16px}
      .bank-logo img{width:39px;height:39px}
      .bank-title-wrap h1{font-size:21px}
      .bank-hero{border-radius:30px;padding:16px}
      .bank-hero-title{font-size:25px}
      .bank-card-body{grid-template-columns:1fr}
      .bank-info + .bank-info{border-left:0;border-top:1px solid rgba(43,11,22,.065)}
      .bank-sheet-actions{grid-template-columns:1fr}
    }
  </style>
</head>

<body>
  <main class="bank-page">
    <div class="bank-phone">

      <header class="bank-header">
        <div class="bank-brand">
          <div class="bank-logo">
            <img src="{{ asset('logo.png') }}" alt="Velora Finance">
          </div>

          <div class="bank-title-wrap">
            <span>Velora Payout</span>
            <h1>Rekening</h1>
          </div>
        </div>

        <div class="bank-header-actions">
          <a href="{{ url('/dashboard') }}" class="bank-header-btn" aria-label="Kembali ke Penarikan">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M15 18 9 12l6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </a>

          <a href="{{ url('/dashboard') }}" class="bank-header-btn" aria-label="Dashboard">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M3 10.5 12 3l9 7.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M5 10v10h14V10" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
            </svg>
          </a>
        </div>
      </header>

      <section class="bank-hero">
        <div class="bank-hero-inner">
          <div>
            <p class="bank-hero-label">Akun penerima penarikan</p>
            <h2 class="bank-hero-title">Kelola Rekening</h2>
            <p class="bank-hero-sub">Tambahkan e-wallet atau rekening bank untuk menerima pencairan saldo Velora.</p>
          </div>

          <div class="bank-hero-pill">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
            </svg>
            Aman
          </div>
        </div>
      </section>

      <section id="bankListWrap">
        <div class="bank-loader">Mengambil data rekening...</div>
      </section>

    </div>
  </main>

  <div class="bank-overlay" id="bankOverlay" role="dialog" aria-modal="true" aria-labelledby="bankSheetTitle">
    <section class="bank-sheet">
      <div class="bank-sheet-head">
        <h2 class="bank-sheet-title" id="bankSheetTitle">Data Akun Penarikan</h2>

        <button class="bank-close" type="button" id="closeSheet" aria-label="Tutup">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M18 6 6 18" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"/>
            <path d="M6 6 18 18" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"/>
          </svg>
        </button>
      </div>

      <form class="bank-form" id="form" novalidate>
        <input type="hidden" id="id" />
        <input type="hidden" id="type" value="EWALLET" />

        <div class="bank-form-group">
          <label class="bank-label" for="provider">Pilih Metode Penarikan</label>
          <select id="provider" class="bank-select" required>
            <option value="OVO">OVO</option>
            <option value="DANA">DANA</option>
            <option value="GOPAY">GOPAY</option>
            <option value="SHOPEEPAY">SHOPEEPAY</option>
            <option value="BCA">BCA</option>
            <option value="BRI">BRI</option>
            <option value="BNI">BNI</option>
            <option value="MANDIRI">MANDIRI</option>
          </select>
        </div>

        <div class="bank-form-group">
          <label class="bank-label" for="account_name">Nama Akun</label>
          <input id="account_name" class="bank-input" placeholder="Contoh: Andreas" required />
        </div>

        <div class="bank-form-group">
          <label class="bank-label" for="account_number">Nomor Rekening / E-Wallet</label>
          <input id="account_number" type="text" inputmode="numeric" class="bank-input" placeholder="Nomor rekening / nomor e-wallet" required />
        </div>

        <label class="bank-check" for="is_default">
          <input id="is_default" type="checkbox" />
          <span class="bank-check-text">Jadikan akun utama</span>
        </label>

        <div class="bank-sheet-actions">
          <button class="bank-primary" type="submit" id="submitBankBtn">Simpan Akun</button>
          <button class="bank-secondary" type="button" id="btnReset">Batal</button>
        </div>
      </form>
    </section>
  </div>

  <div id="bankToast" class="bank-toast" role="status" aria-live="polite">
    <span id="bankToastText">Berhasil</span>
  </div>

  <script>
    const elListWrap = document.getElementById('bankListWrap');
    const overlay = document.getElementById('bankOverlay');
    const form = document.getElementById('form');
    const submitBtn = document.getElementById('submitBankBtn');
    const closeSheet = document.getElementById('closeSheet');
    const btnReset = document.getElementById('btnReset');
    const toastEl = document.getElementById('bankToast');
    const toastText = document.getElementById('bankToastText');
    const $ = id => document.getElementById(id);

    const bankProviders = ['BCA','BRI','BNI','MANDIRI','BSI','CIMB','PERMATA'];

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
        const message = data?.message || data?.error || 'Terjadi kesalahan saat memproses data.';
        throw new Error(message);
      }

      return data;
    }

    function showToast(message, type = 'success'){
      if(!toastEl || !toastText) return;

      toastText.textContent = message;
      toastEl.classList.toggle('is-error', type === 'err');
      toastEl.classList.add('show');

      clearTimeout(window.__bankToastTimer);
      window.__bankToastTimer = setTimeout(function(){
        toastEl.classList.remove('show');
      }, 1800);
    }

    function escapeHtml(str){
      return String(str ?? '')
        .replaceAll('&','&amp;')
        .replaceAll('<','&lt;')
        .replaceAll('>','&gt;')
        .replaceAll('"','&quot;')
        .replaceAll("'","&#039;");
    }

    function maskNumber(n){
      const raw = String(n || '');
      if(raw.length <= 6) return raw;
      return raw.slice(0,3) + '*'.repeat(Math.max(raw.length - 6, 4)) + raw.slice(-3);
    }

    function providerInitial(p){
      return String(p || 'VL').trim().slice(0,3).toUpperCase();
    }

    function providerDisplayName(provider){
      const key = String(provider || '').trim().toUpperCase();
      const names = {
        BCA:'BCA', BRI:'BRI', BNI:'BNI', MANDIRI:'Mandiri', BSI:'BSI', CIMB:'CIMB', PERMATA:'Permata',
        DANA:'DANA', GOPAY:'GoPay', OVO:'OVO', DOKU:'DOKU', LINKAJA:'LinkAja', SHOPEEPAY:'ShopeePay', QRIS:'QRIS'
      };
      return names[key] || provider || 'Rekening';
    }

    function providerLogo(provider){
      const key = String(provider || '').trim().toUpperCase();
      const logos = {
        BCA:'/assets/payment-methods/bca.png',
        BRI:'/assets/payment-methods/bri.png',
        BNI:'/assets/payment-methods/bni.png',
        MANDIRI:'/assets/payment-methods/mandiri.png',
        DANA:'/assets/payment-methods/dana.png',
        GOPAY:'/assets/payment-methods/gopay.png',
        OVO:'/assets/payment-methods/ovo.png',
        DOKU:'/assets/payment-methods/doku.png',
        LINKAJA:'/assets/payment-methods/linkaja.png',
        SHOPEEPAY:'/assets/payment-methods/shopeepay.png',
        QRIS:'/assets/payment-methods/qris.png'
      };
      return logos[key] || '';
    }

    function providerBadge(provider){
      const providerName = providerDisplayName(provider);
      const logo = providerLogo(provider);

      if(logo){
        return `<img src="${escapeHtml(logo)}" alt="${escapeHtml(providerName)}" loading="lazy" onerror="this.remove(); this.parentElement.textContent='${escapeHtml(providerInitial(providerName))}';">`;
      }

      return escapeHtml(providerInitial(providerName));
    }

    function lock(){
      document.documentElement.style.overflow = 'hidden';
      document.body.style.overflow = 'hidden';
    }

    function unlock(){
      document.documentElement.style.overflow = '';
      document.body.style.overflow = '';
    }

    function openSheet(data = null){
      overlay.classList.add('show');
      lock();

      if(data){
        $('id').value = data.id || '';
        $('type').value = data.type || 'EWALLET';
        $('provider').value = data.provider || 'OVO';
        $('account_name').value = data.account_name || '';
        $('account_number').value = data.account_number || '';
        $('is_default').checked = Boolean(data.is_default);
        submitBtn.textContent = 'Perbarui Akun';
      }else{
        resetForm(false);
        submitBtn.textContent = 'Simpan Akun';
      }

      setTimeout(function(){
        $('provider').focus();
      }, 120);
    }

    function closeBankSheet(){
      overlay.classList.remove('show');
      unlock();
    }

    function resetForm(close = true){
      $('id').value = '';
      $('type').value = 'EWALLET';
      $('provider').value = 'OVO';
      $('account_name').value = '';
      $('account_number').value = '';
      $('is_default').checked = false;
      submitBtn.textContent = 'Simpan Akun';

      if(close) closeBankSheet();
    }

    function renderEmpty(){
      elListWrap.innerHTML = `
        <section class="bank-empty">
          <div class="bank-empty-illustration" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
              <rect x="3" y="5" width="18" height="14" rx="3" stroke="currentColor" stroke-width="2.2"/>
              <path d="M3 9h18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              <path d="M7 14h4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              <path d="M16 14h1" stroke="currentColor" stroke-width="2.8" stroke-linecap="round"/>
            </svg>
          </div>

          <h2 class="bank-empty-title">Belum ada rekening</h2>

          <p class="bank-empty-text">Tambahkan rekening bank atau e-wallet supaya proses withdraw bisa langsung diproses.</p>

          <button class="bank-primary" type="button" onclick="openSheet()">
            + Tambahkan Akun
          </button>
        </section>
      `;
    }

    function renderRows(rows){
      elListWrap.innerHTML = `
        <section class="bank-list">
          ${rows.map(function(r){
            const providerName = providerDisplayName(r.provider);

            return `
              <article class="bank-card">
                <div class="bank-card-top">
                  <div class="bank-card-brand">
                    <div class="bank-provider-icon">${providerBadge(r.provider)}</div>

                    <div>
                      <div class="bank-provider-label">${escapeHtml(r.type || 'EWALLET')}</div>
                      <div class="bank-provider-name">${escapeHtml(providerName)}</div>
                    </div>
                  </div>

                  ${r.is_default ? `<span class="bank-default">Utama</span>` : ``}
                </div>

                <div class="bank-card-body">
                  <div class="bank-info">
                    <span class="bank-info-label">Nomor Rekening</span>
                    <div class="bank-info-value">${escapeHtml(maskNumber(r.account_number))}</div>
                  </div>

                  <div class="bank-info">
                    <span class="bank-info-label">Nama Akun</span>
                    <div class="bank-info-value">${escapeHtml(r.account_name || '-')}</div>
                  </div>
                </div>

                <div class="bank-card-actions">
                  <button class="bank-action is-edit" type="button" onclick='editAccount(${JSON.stringify(r).replaceAll("'", "&#039;")})'>
                    Edit
                  </button>

                  <button class="bank-action is-delete" type="button" onclick="deleteAccount(${Number(r.id)})">
                    Hapus
                  </button>
                </div>
              </article>
            `;
          }).join('')}

          <button class="bank-primary bank-floating-add" type="button" onclick="openSheet()">
            + Tambahkan Akun
          </button>
        </section>
      `;
    }

    async function load(){
      elListWrap.innerHTML = `<div class="bank-loader">Mengambil data rekening...</div>`;

      const res = await api('/payout-accounts');
      const rows = res?.data || [];

      if(!rows.length){
        renderEmpty();
        return;
      }

      const sorted = [...rows].sort(function(a,b){
        return (b.is_default ? 1 : 0) - (a.is_default ? 1 : 0);
      });

      renderRows(sorted);
    }

    window.openSheet = openSheet;

    window.editAccount = function(row){
      openSheet(row);
    };

    window.deleteAccount = async function(id){
      if(!confirm('Hapus akun ini?')) return;

      try{
        await api(`/payout-accounts/${id}`, {
          method:'DELETE'
        });

        showToast('Akun berhasil dihapus');
        await load();
        resetForm(false);
      }catch(error){
        showToast(error.message, 'err');
      }
    };

    closeSheet.addEventListener('click', closeBankSheet);

    overlay.addEventListener('click', function(e){
      if(e.target === overlay) closeBankSheet();
    });

    btnReset.addEventListener('click', function(){
      resetForm(true);
    });

    document.addEventListener('keydown', function(e){
      if(e.key === 'Escape' && overlay.classList.contains('show')){
        closeBankSheet();
      }
    });

    form.addEventListener('submit', async function(e){
      e.preventDefault();

      const id = $('id').value.trim();
      const provider = $('provider').value.trim();
      const type = bankProviders.includes(provider) ? 'BANK' : 'EWALLET';

      const payload = {
        type,
        provider,
        account_name: $('account_name').value.trim(),
        account_number: $('account_number').value.trim(),
        is_default: $('is_default').checked
      };

      if(!payload.provider){
        showToast('Pilih bank/e-wallet terlebih dahulu', 'err');
        return;
      }

      if(!payload.account_name){
        showToast('Nama akun wajib diisi', 'err');
        return;
      }

      if(!payload.account_number){
        showToast('Nomor rekening/e-wallet wajib diisi', 'err');
        return;
      }

      const oldText = submitBtn.textContent;
      submitBtn.textContent = 'Menyimpan...';
      submitBtn.disabled = true;

      try{
        if(!id){
          await api('/payout-accounts', {
            method:'POST',
            body:JSON.stringify(payload)
          });
        }else{
          await api(`/payout-accounts/${id}`, {
            method:'PUT',
            body:JSON.stringify(payload)
          });
        }

        showToast(id ? 'Data akun diperbarui' : 'Akun baru ditambahkan');
        await load();
        resetForm(true);
      }catch(error){
        showToast(error.message, 'err');
      }finally{
        submitBtn.textContent = oldText;
        submitBtn.disabled = false;
      }
    });

    load().catch(function(error){
      showToast(error.message, 'err');
      elListWrap.innerHTML = `
        <div class="bank-loader">
          Gagal mengambil data rekening. Pastikan route API /payout-accounts sudah aktif.
        </div>
      `;
    });
  </script>
</body>
</html>
