<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Rekening Bank | Rubik Company</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --bg:#030F0F;
      --panel:#071f1b;
      --panel2:#0a2a23;
      --text:#f7fffb;
      --muted:#89a99c;
      --soft:#d6fff0;
      --neon:#00DF82;
      --neon2:#79ff99;
      --border:rgba(255,255,255,.10);
      --red:#ff5b75;
    }

    *{box-sizing:border-box}

    html,
    body{
      min-height:100%;
    }

    body{
      margin:0;
      font-family:Inter,system-ui,-apple-system,"Segoe UI",sans-serif;
      color:var(--text);
      background:
        radial-gradient(760px 420px at 14% -2%,rgba(0,223,130,.18),transparent 58%),
        radial-gradient(620px 360px at 90% 10%,rgba(90,140,255,.14),transparent 62%),
        radial-gradient(520px 300px at 55% 100%,rgba(246,196,83,.08),transparent 62%),
        linear-gradient(180deg,#071f1a 0%,#030f0f 48%,#020807 100%);
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
      margin-bottom:14px;
      padding:0 2px;
    }

    .bank-brand{
      display:flex;
      align-items:center;
      gap:10px;
      min-width:0;
    }

    .bank-logo{
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

    .bank-logo img{
      width:42px;
      height:42px;
      object-fit:contain;
      display:block;
    }

    .bank-title-wrap{
      min-width:0;
    }

    .bank-title-wrap span{
      display:block;
      margin-bottom:4px;
      color:rgba(214,255,240,.58);
      font-size:11px;
      line-height:1;
      font-weight:600;
      letter-spacing:.02em;
    }

    .bank-title-wrap h1{
      margin:0;
      font-size:23px;
      line-height:1;
      font-weight:850;
      letter-spacing:-.045em;
      color:#fff;
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

    .bank-header-btn:hover{
      transform:translateY(-1px);
      border-color:rgba(0,223,130,.24);
    }

    .bank-header-btn svg{
      width:20px;
      height:20px;
    }

    .bank-hero{
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
      animation:bankFadeUp .42s ease both;
    }

    .bank-hero::before{
      content:"";
      position:absolute;
      inset:0;
      background:
        linear-gradient(145deg, rgba(255,255,255,.48) 0%, rgba(255,255,255,.18) 27%, transparent 28%),
        linear-gradient(180deg, rgba(255,255,255,.22), rgba(255,255,255,0));
      pointer-events:none;
    }

    .bank-hero-inner{
      position:relative;
      z-index:1;
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
    }

    .bank-hero-label{
      margin:0 0 8px;
      color:rgba(3,24,20,.62);
      font-size:12px;
      font-weight:650;
      line-height:1.1;
    }

    .bank-hero-title{
      margin:0;
      color:#031713;
      font-size:28px;
      line-height:1.04;
      letter-spacing:-.055em;
      font-weight:900;
    }

    .bank-hero-sub{
      margin-top:10px;
      color:rgba(3,24,20,.58);
      font-size:12px;
      line-height:1.45;
      font-weight:650;
      max-width:245px;
    }

    .bank-hero-pill{
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

    .bank-hero-pill svg{
      width:15px;
      height:15px;
      color:#047857;
    }

    .bank-loader{
      color:rgba(214,255,240,.62);
      text-align:center;
      font-size:12px;
      font-weight:700;
      padding:30px 12px;
      border-radius:20px;
      border:1px dashed rgba(255,255,255,.12);
      background:rgba(255,255,255,.035);
    }

    .bank-empty{
      min-height:430px;
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      text-align:center;
      padding:30px 14px;
      border-radius:24px;
      background:
        radial-gradient(220px 120px at 90% 0%, rgba(0,223,130,.12), transparent 62%),
        radial-gradient(220px 120px at 0% 100%, rgba(52,213,255,.10), transparent 62%),
        rgba(9,37,31,.76);
      border:1px dashed rgba(0,223,130,.22);
      box-shadow:
        0 16px 32px rgba(0,0,0,.25),
        0 0 0 1px rgba(255,255,255,.025) inset;
      animation:bankFadeUp .42s ease both;
    }

    .bank-empty-illustration{
      width:96px;
      height:96px;
      border-radius:28px;
      margin-bottom:18px;
      display:grid;
      place-items:center;
      color:#06110d;
      background:
        radial-gradient(circle at 30% 0%,rgba(255,255,255,.52),transparent 34%),
        linear-gradient(135deg,#00DF82,#79ff99);
      box-shadow:
        0 18px 38px rgba(0,223,130,.20),
        inset 0 1px 0 rgba(255,255,255,.30);
    }

    .bank-empty-illustration svg{
      width:48px;
      height:48px;
    }

    .bank-empty-title{
      margin:0;
      font-size:21px;
      font-weight:950;
      letter-spacing:-.04em;
      color:#fff;
    }

    .bank-empty-text{
      max-width:310px;
      margin:10px auto 18px;
      color:rgba(214,255,240,.64);
      font-size:12.5px;
      font-weight:650;
      line-height:1.55;
    }

    .bank-primary{
      width:100%;
      min-height:50px;
      border:0;
      border-radius:999px;
      color:#06110d;
      background:
        radial-gradient(circle at 30% 0%,rgba(255,255,255,.55),transparent 34%),
        linear-gradient(135deg,#00DF82 0%,#79ff99 100%);
      box-shadow:
        0 18px 38px rgba(0,223,130,.24),
        0 0 0 1px rgba(255,255,255,.22) inset;
      font-size:14px;
      font-weight:950;
      cursor:pointer;
    }

    .bank-primary:disabled{
      opacity:.65;
      cursor:not-allowed;
    }

    .bank-list{
      display:flex;
      flex-direction:column;
      gap:10px;
      margin-top:12px;
      animation:bankFadeUp .42s ease both;
    }

    .bank-card{
      border:1px solid var(--border);
      border-radius:20px;
      background:
        radial-gradient(220px 100px at 92% 0%,rgba(0,223,130,.10),transparent 64%),
        linear-gradient(180deg,rgba(9,37,31,.86),rgba(5,20,17,.94));
      box-shadow:
        0 16px 36px rgba(0,0,0,.24),
        inset 0 1px 0 rgba(255,255,255,.06);
      overflow:hidden;
    }

    .bank-card-top{
      padding:16px;
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
    }

    .bank-card-brand{
      display:flex;
      align-items:center;
      gap:10px;
      min-width:0;
    }

    .bank-provider-icon{
      width:38px;
      height:38px;
      border-radius:999px;
      display:grid;
      place-items:center;
      color:#071211;
      background:linear-gradient(135deg,#00DF82,#79ff99);
      box-shadow:0 12px 24px rgba(0,223,130,.20);
      font-weight:950;
      font-size:11px;
      flex:0 0 auto;
    }

    .bank-provider-label{
      color:rgba(214,255,240,.58);
      font-size:10px;
      font-weight:850;
      text-transform:uppercase;
      letter-spacing:.08em;
    }

    .bank-provider-name{
      margin-top:3px;
      color:#fff;
      font-size:16px;
      font-weight:950;
      letter-spacing:-.03em;
    }

    .bank-default{
      min-height:24px;
      padding:5px 9px;
      border-radius:999px;
      background:rgba(0,223,130,.12);
      border:1px solid rgba(0,223,130,.24);
      color:#00DF82;
      font-size:10px;
      font-weight:950;
      white-space:nowrap;
    }

    .bank-card-body{
      display:grid;
      grid-template-columns:1fr 1fr;
      border-top:1px solid rgba(255,255,255,.08);
      background:rgba(255,255,255,.025);
    }

    .bank-info{
      padding:13px 16px;
      min-width:0;
    }

    .bank-info + .bank-info{
      border-left:1px solid rgba(255,255,255,.08);
    }

    .bank-info-label{
      display:block;
      color:rgba(214,255,240,.55);
      font-size:10.5px;
      font-weight:750;
      margin-bottom:5px;
    }

    .bank-info-value{
      color:#fff;
      font-size:14px;
      font-weight:900;
      letter-spacing:-.02em;
      overflow:hidden;
      text-overflow:ellipsis;
      white-space:nowrap;
    }

    .bank-card-actions{
      padding:12px 16px 14px;
      display:flex;
      justify-content:flex-end;
      gap:8px;
      border-top:1px solid rgba(255,255,255,.07);
    }

    .bank-action{
      border:1px solid var(--border);
      min-height:34px;
      padding:0 12px;
      border-radius:999px;
      background:rgba(255,255,255,.06);
      color:rgba(214,255,240,.82);
      font-size:11px;
      font-weight:900;
      cursor:pointer;
    }

    .bank-action.is-edit{
      color:#071211;
      background:linear-gradient(135deg,#00DF82,#72ff9a);
    }

    .bank-action.is-delete{
      color:#ffd7df;
      background:rgba(255,79,109,.10);
      border-color:rgba(255,79,109,.22);
    }

    .bank-floating-add{
      margin-top:14px;
    }

    .bank-overlay{
      position:fixed;
      inset:0;
      z-index:999;
      display:none;
      align-items:flex-end;
      justify-content:center;
      background:rgba(0,0,0,.58);
      backdrop-filter:blur(8px);
      -webkit-backdrop-filter:blur(8px);
    }

    .bank-overlay.show{
      display:flex;
    }

    .bank-sheet{
      width:min(100%,430px);
      max-height:88vh;
      overflow:auto;
      border-radius:28px 28px 0 0;
      background:
        radial-gradient(360px 180px at 86% 0%,rgba(0,223,130,.12),transparent 64%),
        linear-gradient(180deg,rgba(8,34,29,.98),rgba(3,15,15,.98));
      border:1px solid var(--border);
      border-bottom:0;
      box-shadow:0 -24px 70px rgba(0,0,0,.44);
      padding:22px 14px 16px;
      animation:bankSheetUp .22s ease both;
    }

    .bank-sheet-head{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:22px;
    }

    .bank-sheet-title{
      margin:0;
      color:#fff;
      font-size:20px;
      font-weight:950;
      letter-spacing:-.04em;
    }

    .bank-close{
      width:38px;
      height:38px;
      border-radius:14px;
      border:1px solid var(--border);
      background:rgba(255,255,255,.06);
      color:#fff;
      display:grid;
      place-items:center;
      cursor:pointer;
    }

    .bank-close svg{
      width:19px;
    }

    .bank-form{
      display:grid;
      gap:13px;
    }

    .bank-form-group{
      display:grid;
      gap:7px;
    }

    .bank-label{
      color:rgba(214,255,240,.56);
      font-size:12px;
      font-weight:850;
    }

    .bank-input,
    .bank-select{
      width:100%;
      min-height:54px;
      border-radius:14px;
      border:1px solid var(--border);
      outline:0;
      background:rgba(255,255,255,.065);
      color:#fff;
      padding:0 15px;
      font-size:14px;
      font-weight:750;
    }

    .bank-select{
      appearance:none;
      background-image:url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='rgba(214,255,240,.75)' stroke-width='2.3' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='m6 9 6 6 6-6'/%3e%3c/svg%3e");
      background-repeat:no-repeat;
      background-position:right 14px center;
      background-size:18px;
      padding-right:44px;
    }

    .bank-select option{
      background:#061714;
      color:#fff;
    }

    .bank-input:focus,
    .bank-select:focus{
      border-color:rgba(0,223,130,.42);
      box-shadow:0 0 0 4px rgba(0,223,130,.10);
    }

    .bank-check{
      display:flex;
      align-items:center;
      gap:10px;
      padding:12px 13px;
      border-radius:15px;
      background:rgba(255,255,255,.045);
      border:1px solid rgba(255,255,255,.08);
      cursor:pointer;
    }

    .bank-check input{
      width:17px;
      height:17px;
      accent-color:#00DF82;
    }

    .bank-check-text{
      color:#fff;
      font-size:13px;
      font-weight:800;
    }

    .bank-secondary{
      width:100%;
      min-height:48px;
      border-radius:999px;
      border:1px solid rgba(255,255,255,.16);
      background:rgba(255,255,255,.04);
      color:#fff;
      font-size:14px;
      font-weight:900;
      cursor:pointer;
    }

    .bank-sheet-actions{
      display:grid;
      gap:10px;
      margin-top:4px;
    }

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
      gap:8px;
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

    .bank-toast.is-error{
      color:#fff;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.18), transparent 34%),
        linear-gradient(135deg, #ff5b75, #fb923c);
    }

    .bank-toast.show{
      opacity:1;
      transform:translateX(-50%) translateY(0);
    }

    @keyframes bankFadeUp{
      from{opacity:0;transform:translateY(10px)}
      to{opacity:1;transform:translateY(0)}
    }

    @keyframes bankSheetUp{
      from{opacity:0;transform:translateY(24px)}
      to{opacity:1;transform:translateY(0)}
    }

    @media(min-width:768px){
      .bank-page{
        padding:22px 0;
      }

      .bank-phone{
        min-height:calc(100vh - 44px);
        border-radius:26px;
        overflow:hidden;
      }

      .bank-overlay{
        align-items:center;
      }

      .bank-sheet{
        border-radius:28px;
        border-bottom:1px solid var(--border);
      }
    }

    @media(max-width:370px){
      .bank-title-wrap h1{
        font-size:21px;
      }

      .bank-hero-title{
        font-size:25px;
      }

      .bank-card-body{
        grid-template-columns:1fr;
      }

      .bank-info + .bank-info{
        border-left:0;
        border-top:1px solid rgba(255,255,255,.08);
      }
    }
  </style>
</head>

<body>
  <main class="bank-page">
    <div class="bank-phone">

      <header class="bank-header">
        <div class="bank-brand">
          <div class="bank-logo">
            <img src="{{ asset('logo.png') }}" alt="Rubik Company">
          </div>

          <div class="bank-title-wrap">
            <span>Penarikan saldo</span>
            <h1>Rekening Bank</h1>
          </div>
        </div>

        <div class="bank-header-actions">
          <a href="{{ url('/withdrawals') }}" class="bank-header-btn" aria-label="Kembali ke Penarikan">
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
            <p class="bank-hero-sub">
              Tambahkan rekening bank atau e-wallet untuk menerima penarikan saldo.
            </p>
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
        <h2 class="bank-sheet-title" id="bankSheetTitle">Data Akun Bank</h2>

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
          <label class="bank-label" for="provider">Pilih Bank / E-Wallet</label>
          <select id="provider" class="bank-select" required>
            <option value="OVO">OVO</option>
            <option value="DANA">DANA</option>
            <option value="GOPAY">GOPAY</option>
            <option value="SHOPEEPAY">SHOPEEPAY</option>
            <option value="BCA">BCA</option>
            <option value="BRI">BRI</option>
            <option value="BNI">BNI</option>
            <option value="MANDIRI">MANDIRI</option>
            <option value="BSI">BSI</option>
            <option value="CIMB">CIMB</option>
            <option value="PERMATA">PERMATA</option>
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
          <button class="bank-primary" type="submit" id="submitBankBtn">Simpan Akun Bank</button>
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
        const message =
          data?.message ||
          data?.error ||
          'Terjadi kesalahan saat memproses data.';

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
      return String(p || 'RB').trim().slice(0,3).toUpperCase();
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
        submitBtn.textContent = 'Perbarui Akun Bank';
      }else{
        resetForm(false);
        submitBtn.textContent = 'Simpan Akun Bank';
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
      submitBtn.textContent = 'Simpan Akun Bank';

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

          <p class="bank-empty-text">
            Tautkan rekening bank atau e-wallet untuk melakukan penarikan saldo dengan cepat.
          </p>

          <button class="bank-primary" type="button" onclick="openSheet()">
            + Tambahkan Akun Bank
          </button>
        </section>
      `;
    }

    function renderRows(rows){
      elListWrap.innerHTML = `
        <section class="bank-list">
          ${rows.map(function(r){
            return `
              <article class="bank-card">
                <div class="bank-card-top">
                  <div class="bank-card-brand">
                    <div class="bank-provider-icon">${escapeHtml(providerInitial(r.provider))}</div>

                    <div>
                      <div class="bank-provider-label">${escapeHtml(r.type || 'EWALLET')}</div>
                      <div class="bank-provider-name">${escapeHtml(r.provider || '-')}</div>
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
            + Tambahkan Akun Bank
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