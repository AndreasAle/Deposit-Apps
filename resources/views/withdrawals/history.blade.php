 @include('partials.anti-inspect')
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Riwayat Penarikan | Rubik Company</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --wh-bg:#030F0F;
      --wh-card:#081a18;
      --wh-text:#f7fffb;
      --wh-soft:#dffcf1;
      --wh-muted:#89a99c;
      --wh-border:rgba(255,255,255,.10);
      --wh-green:#00DF82;
      --wh-green2:#79ff99;
      --wh-blue:#34d5ff;
      --wh-amber:#f6c453;
      --wh-red:#ff5b75;
      --wh-purple:#a78bfa;
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
      color:var(--wh-text);
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

    .wh-page{
      width:100%;
      min-height:100vh;
      display:flex;
      justify-content:center;
      padding:14px 10px 0;
      position:relative;
      z-index:1;
    }

    .wh-phone{
      width:100%;
      max-width:430px;
      min-height:100vh;
      position:relative;
      padding:8px 4px 104px;
    }

    /* HEADER */
    .wh-topbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
      padding:0 2px;
    }

    .wh-brand{
      display:flex;
      align-items:center;
      gap:10px;
      min-width:0;
    }

    .wh-back{
      width:42px;
      height:42px;
      border-radius:15px;
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
      flex:0 0 auto;
    }

    .wh-back svg{
      width:20px;
      height:20px;
    }

    .wh-title{
      min-width:0;
    }

    .wh-title h1{
      margin:0;
      color:#ffffff;
      font-size:23px;
      line-height:1;
      font-weight:900;
      letter-spacing:-.045em;
    }

    .wh-title p{
      margin:5px 0 0;
      color:rgba(214,255,240,.58);
      font-size:11.5px;
      font-weight:650;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
    }

    .wh-header-icon{
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
      flex:0 0 auto;
    }

    .wh-header-icon svg{
      width:20px;
      height:20px;
    }

    /* HERO */
    .wh-hero{
      position:relative;
      overflow:hidden;
      border-radius:26px;
      background:
        radial-gradient(320px 180px at 95% 4%, rgba(90,140,255,.24), transparent 62%),
        radial-gradient(260px 170px at 8% 0%, rgba(0,223,130,.28), transparent 62%),
        radial-gradient(240px 150px at 90% 110%, rgba(246,196,83,.16), transparent 68%),
        linear-gradient(135deg, rgba(236,255,248,.96), rgba(199,255,232,.92) 48%, rgba(185,236,255,.88));
      border:1px solid rgba(255,255,255,.55);
      box-shadow:
        0 20px 44px rgba(0,0,0,.22),
        0 0 0 1px rgba(0,223,130,.14) inset,
        inset 0 1px 0 rgba(255,255,255,.72);
      padding:16px;
      animation:whFadeUp .42s ease both;
    }

    .wh-hero::before{
      content:"";
      position:absolute;
      inset:0;
      background:
        linear-gradient(145deg, rgba(255,255,255,.48) 0%, rgba(255,255,255,.18) 27%, transparent 28%),
        linear-gradient(180deg, rgba(255,255,255,.22), rgba(255,255,255,0));
      pointer-events:none;
    }

    .wh-hero-inner{
      position:relative;
      z-index:1;
      display:grid;
      grid-template-columns:52px minmax(0,1fr);
      gap:13px;
      align-items:center;
    }

    .wh-hero-icon{
      width:52px;
      height:52px;
      border-radius:18px;
      display:grid;
      place-items:center;
      color:#06110e;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.55), transparent 34%),
        linear-gradient(135deg, #00DF82 0%, #79ff99 100%);
      box-shadow:
        0 14px 28px rgba(0,223,130,.20),
        inset 0 1px 0 rgba(255,255,255,.30);
    }

    .wh-hero-icon svg{
      width:26px;
      height:26px;
    }

    .wh-hero-label{
      margin:0;
      color:rgba(3,24,20,.62);
      font-size:11px;
      font-weight:850;
      text-transform:uppercase;
      letter-spacing:.08em;
    }

    .wh-hero-total{
      margin:6px 0 0;
      color:#031713;
      font-size:30px;
      line-height:1;
      letter-spacing:-.055em;
      font-weight:950;
    }

    .wh-hero-sub{
      margin:8px 0 0;
      color:rgba(3,24,20,.58);
      font-size:12px;
      line-height:1.35;
      font-weight:650;
    }

    /* FILTER */
.wh-filter-card{
  margin-top:12px;
  position:relative;
  z-index:2;
  border-radius:0;
  background:transparent;
  border:0;
  box-shadow:none;
  padding:0;
  animation:whFadeUp .42s ease both;
}

    .wh-filter-grid{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:9px;
    }

    .wh-select{
      width:100%;
      height:44px;
      border-radius:16px;
      border:1px solid rgba(255,255,255,.10);
      outline:0;
      background:
        linear-gradient(180deg, rgba(255,255,255,.07), rgba(255,255,255,.035));
      color:#ffffff;
      padding:0 38px 0 13px;
      font-size:12px;
      font-weight:850;
      appearance:none;
      background-image:url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='rgba(214,255,240,.78)' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='m6 9 6 6 6-6'/%3e%3c/svg%3e");
      background-repeat:no-repeat;
      background-position:right 13px center;
      background-size:16px;
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,.06),
        0 10px 18px rgba(0,0,0,.14);
    }

    .wh-select option{
      background:#061714;
      color:#fff;
    }

    /* LIST */
    .wh-list{
      margin-top:12px;
      display:flex;
      flex-direction:column;
      gap:10px;
    }

    .wh-card{
      position:relative;
      overflow:hidden;
      border-radius:22px;
      background:
        radial-gradient(170px 94px at 88% 8%, rgba(0,223,130,.10), transparent 64%),
        linear-gradient(180deg, rgba(13,35,34,.94), rgba(6,20,19,.96));
      border:1px solid rgba(255,255,255,.085);
      box-shadow:
        0 16px 32px rgba(0,0,0,.25),
        0 0 0 1px rgba(255,255,255,.025) inset;
      animation:whFadeUp .42s ease both;
    }

    .wh-card-top{
      padding:14px;
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
    }

    .wh-bank{
      display:flex;
      align-items:center;
      gap:11px;
      min-width:0;
    }

  .wh-bank-logo{
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

.wh-bank-logo-img{
  display:block;
  width:40px;
  height:40px;
  object-fit:contain;
}

.wh-bank-logo-fallback{
  display:none;
  color:#06110e;
  font-size:11px;
  font-weight:950;
  line-height:1;
}

    .wh-bank-name{
      color:#ffffff;
      font-size:14px;
      line-height:1.15;
      font-weight:950;
      letter-spacing:-.025em;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width:170px;
    }

    .wh-bank-number{
      margin-top:5px;
      color:rgba(214,255,240,.54);
      font-size:11px;
      font-weight:750;
      white-space:nowrap;
    }

    .wh-status{
      min-height:28px;
      padding:0 10px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:6px;
      font-size:11px;
      font-weight:900;
      white-space:nowrap;
      flex:0 0 auto;
      border:1px solid transparent;
    }

    .wh-status::before{
      content:"";
      width:6px;
      height:6px;
      border-radius:999px;
      background:currentColor;
    }

    .wh-status.is-paid{
      color:#00DF82;
      background:rgba(0,223,130,.10);
      border-color:rgba(0,223,130,.22);
    }

    .wh-status.is-pending{
      color:#f6c453;
      background:rgba(246,196,83,.10);
      border-color:rgba(246,196,83,.22);
    }

    .wh-status.is-approved{
      color:#34d5ff;
      background:rgba(52,213,255,.10);
      border-color:rgba(52,213,255,.22);
    }

    .wh-status.is-rejected,
    .wh-status.is-cancelled{
      color:#ff8aa0;
      background:rgba(255,91,117,.10);
      border-color:rgba(255,91,117,.22);
    }

    .wh-card-body{
      border-top:1px solid rgba(255,255,255,.07);
      background:rgba(2,10,10,.16);
      padding:13px 14px;
      display:grid;
      gap:11px;
    }

    .wh-row{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      color:rgba(214,255,240,.68);
      font-size:12px;
      font-weight:700;
    }

    .wh-row strong{
      color:#ffffff;
      font-size:12.5px;
      font-weight:900;
      white-space:nowrap;
    }

    .wh-row.is-fee strong{
      color:#ff7f95;
    }

    .wh-row.is-net{
      padding-top:12px;
      border-top:1px solid rgba(255,255,255,.07);
    }

    .wh-row.is-net span{
      color:#ffffff;
      font-weight:900;
    }

    .wh-row.is-net strong{
      color:#00DF82;
      font-size:16px;
      letter-spacing:-.035em;
      text-shadow:0 0 16px rgba(0,223,130,.10);
    }

    .wh-date{
      border-top:1px solid rgba(255,255,255,.07);
      padding:12px 14px 13px;
      color:rgba(214,255,240,.50);
      font-size:11px;
      font-weight:700;
      display:flex;
      align-items:center;
      gap:7px;
      background:rgba(255,255,255,.018);
    }

    .wh-date svg{
      width:14px;
      height:14px;
      opacity:.8;
    }

    .wh-proof{
      margin-left:auto;
      min-height:28px;
      padding:0 9px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      color:#06110e;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.50), transparent 34%),
        linear-gradient(135deg, #00DF82, #72ffab);
      font-size:10.5px;
      font-weight:950;
    }

    /* STATES */
    .wh-loading,
    .wh-empty{
      min-height:180px;
      border-radius:22px;
      border:1px dashed rgba(255,255,255,.14);
      background:
        radial-gradient(220px 120px at 90% 0%, rgba(0,223,130,.10), transparent 62%),
        rgba(9,37,31,.70);
      color:rgba(214,255,240,.62);
      display:flex;
      align-items:center;
      justify-content:center;
      text-align:center;
      padding:18px;
      font-size:12.5px;
      font-weight:750;
    }

    .wh-bottom-actions{
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

    .wh-main-btn{
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
      display:flex;
      align-items:center;
      justify-content:center;
      gap:8px;
    }

    .wh-toast{
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

    .wh-toast.is-error{
      color:#fff;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.18), transparent 34%),
        linear-gradient(135deg, #ff5b75, #fb923c);
    }

    .wh-toast.show{
      opacity:1;
      transform:translateX(-50%) translateY(0);
    }

    @keyframes whFadeUp{
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
      .wh-page{
        padding:22px 0;
      }

      .wh-phone{
        min-height:calc(100vh - 44px);
        border-radius:26px;
        overflow:hidden;
      }

      .wh-bottom-actions{
        bottom:22px;
        border-radius:0 0 26px 26px;
      }
    }

    @media(max-width:370px){
      .wh-title h1{
        font-size:21px;
      }

      .wh-filter-grid{
        grid-template-columns:1fr;
      }

      .wh-bank-name{
        max-width:130px;
      }

      .wh-hero-total{
        font-size:27px;
      }
    }

    .wh-back{
  cursor:pointer;
}

.wh-bank-logo-fallback{
  width:100%;
  height:100%;
  align-items:center;
  justify-content:center;
}
  </style>
</head>

<body>
  <main class="wh-page">
    <div class="wh-phone">

      {{-- HEADER --}}
      <header class="wh-topbar">
        <div class="wh-brand">
            <button type="button" class="wh-back" onclick="goBack()" aria-label="Kembali ke halaman sebelumnya">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M15 18 9 12l6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            </button>

          <div class="wh-title">
            <h1>Riwayat Penarikan</h1>
            <p>Withdraw ke rekening & e-wallet</p>
          </div>
        </div>

        <a href="{{ url('/ui/payout-accounts') }}" class="wh-header-icon" aria-label="Rekening Bank">
          <svg viewBox="0 0 24 24" fill="none">
            <rect x="3" y="5" width="18" height="14" rx="3" stroke="currentColor" stroke-width="2.2"/>
            <path d="M3 9h18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
            <path d="M7 14h4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
          </svg>
        </a>
      </header>

      {{-- HERO --}}
      <section class="wh-hero">
        <div class="wh-hero-inner">
          <div class="wh-hero-icon">
            <svg viewBox="0 0 24 24" fill="none">
              <rect x="3" y="6" width="18" height="13" rx="3" stroke="currentColor" stroke-width="2.2"/>
              <path d="M7 10h4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              <path d="M16 14h1" stroke="currentColor" stroke-width="2.8" stroke-linecap="round"/>
              <path d="M12 3v3" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
            </svg>
          </div>

          <div>
            <p class="wh-hero-label">Total Entri</p>
            <h2 class="wh-hero-total" id="totalEntry">0</h2>
            <p class="wh-hero-sub">Semua permintaan penarikan kamu tersimpan di sini.</p>
          </div>
        </div>
      </section>

      {{-- FILTER --}}
      <section class="wh-filter-card">
        <div class="wh-filter-grid">
          <select id="monthFilter" class="wh-select" aria-label="Filter bulan">
            <option value="">Semua bulan</option>
          </select>

          <select id="statusFilter" class="wh-select" aria-label="Filter status">
            <option value="">Semua status</option>
<option value="PENDING">Menunggu</option>
<option value="PROCESSING">Diproses Gateway</option>
<option value="APPROVED">Disetujui</option>
<option value="PAID">Berhasil</option>
<option value="FAILED">Gagal</option>
<option value="REJECTED">Ditolak</option>
<option value="CANCELLED">Dibatalkan</option>
          </select>
        </div>
      </section>

      {{-- LIST --}}
      <section id="withdrawHistoryList" class="wh-list">
        <div class="wh-loading">Mengambil riwayat penarikan...</div>
      </section>

    </div>
  </main>

  <div class="wh-bottom-actions">
    <a href="{{ url('/ui/withdrawals') }}" class="wh-main-btn">
      Buat Penarikan Baru
      <span>↗</span>
    </a>
  </div>

  <div id="whToast" class="wh-toast" role="status" aria-live="polite">
    <span id="whToastText">Berhasil</span>
  </div>

  <script>
    const listEl = document.getElementById('withdrawHistoryList');
    const totalEntryEl = document.getElementById('totalEntry');
    const monthFilter = document.getElementById('monthFilter');
    const statusFilter = document.getElementById('statusFilter');
    const toastEl = document.getElementById('whToast');
    const toastText = document.getElementById('whToastText');

    let allRows = [];

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
        throw new Error(data?.message || data?.error || 'Request gagal.');
      }

      return data;
    }

    function toast(message, type = 'success'){
      if(!toastEl || !toastText) return;

      toastText.textContent = message;
      toastEl.classList.toggle('is-error', type === 'err');
      toastEl.classList.add('show');

      clearTimeout(window.__whToastTimer);
      window.__whToastTimer = setTimeout(function(){
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

    function providerInitial(provider){
      return String(provider || 'RB').trim().slice(0,3).toUpperCase();
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

    function maskNumber(n){
      const raw = String(n || '');

      if(raw.length <= 6) return raw;

      return raw.slice(0, 3) + '*'.repeat(Math.max(raw.length - 6, 4)) + raw.slice(-3);
    }

function statusText(status){
  const s = String(status || '').toUpperCase();

  if(s === 'PAID') return 'Berhasil';
  if(s === 'PENDING') return 'Menunggu';
  if(s === 'PROCESSING') return 'Diproses';
  if(s === 'APPROVED') return 'Disetujui';
  if(s === 'FAILED') return 'Gagal';
  if(s === 'REJECTED') return 'Ditolak';
  if(s === 'CANCELLED') return 'Dibatalkan';

  return s || '-';
}

function statusClass(status){
  const s = String(status || '').toUpperCase();

  if(s === 'PAID') return 'is-paid';
  if(s === 'PENDING') return 'is-pending';
  if(s === 'PROCESSING') return 'is-approved';
  if(s === 'APPROVED') return 'is-approved';
  if(s === 'FAILED') return 'is-rejected';
  if(s === 'REJECTED') return 'is-rejected';
  if(s === 'CANCELLED') return 'is-cancelled';

  return 'is-pending';
}

    function dateObj(row){
      return row.created_at ? new Date(row.created_at) : null;
    }

    function monthKey(row){
      const d = dateObj(row);
      if(!d || isNaN(d.getTime())) return '';

      return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}`;
    }

    function monthLabel(key){
      if(!key) return '';

      const [year, month] = key.split('-').map(Number);
      const d = new Date(year, month - 1, 1);

      return d.toLocaleDateString('id-ID', {
        month:'long',
        year:'numeric'
      });
    }

    function formatDate(row){
      const d = dateObj(row);

      if(!d || isNaN(d.getTime())) return '-';

      return d.toLocaleDateString('id-ID', {
        day:'2-digit',
        month:'short',
        year:'numeric'
      }) + ', ' + d.toLocaleTimeString('id-ID', {
        hour:'2-digit',
        minute:'2-digit'
      });
    }

    function normalizeRows(payload){
      if(Array.isArray(payload?.data)) return payload.data;
      if(Array.isArray(payload)) return payload;

      return [];
    }

    function getAccount(row){
      return row.payout_account || row.payoutAccount || row.payout_account_data || null;
    }

function parseGatewayResponse(row){
  try {
    if(!row.gateway_response) return {};

    return typeof row.gateway_response === 'string'
      ? JSON.parse(row.gateway_response)
      : row.gateway_response;
  } catch (error) {
    return {};
  }
}

function getFee(row){
  const gateway = parseGatewayResponse(row);
  const gatewayFee = Number(gateway.fee || 0);

  if(gatewayFee > 0) return gatewayFee;

  return Number(row.fee || 0);
}

function getAmount(row){
  return Number(row.amount || 0);
}

function getNet(row){
  const amount = getAmount(row);
  const fee = getFee(row);

  return Math.max(amount - fee, 0);
}
    function buildMonthOptions(rows){
      const months = [...new Set(rows.map(monthKey).filter(Boolean))]
        .sort()
        .reverse();

      monthFilter.innerHTML = `
        <option value="">Semua bulan</option>
        ${months.map(function(key){
          return `<option value="${escapeHtml(key)}">${escapeHtml(monthLabel(key))}</option>`;
        }).join('')}
      `;
    }

    function filteredRows(){
      const selectedMonth = monthFilter.value;
      const selectedStatus = statusFilter.value;

      return allRows.filter(function(row){
        const okMonth = !selectedMonth || monthKey(row) === selectedMonth;
        const okStatus = !selectedStatus || String(row.status || '').toUpperCase() === selectedStatus;

        return okMonth && okStatus;
      });
    }

    function render(){
      const rows = filteredRows();

      totalEntryEl.textContent = allRows.length;

      if(!rows.length){
        listEl.innerHTML = `
          <div class="wh-empty">
            Tidak ada riwayat penarikan untuk filter ini.
          </div>
        `;
        return;
      }

      listEl.innerHTML = rows.map(function(row){
        const account = getAccount(row);
   const provider = account?.provider || row.bank_code || row.method || 'Rekening';
const providerName = providerDisplayName(provider);
const logo = providerLogo(provider);
const number = account?.account_number || row.account_no || '-';

        const amount = getAmount(row);
        const fee = getFee(row);
        const net = getNet(row);

        const proof = row.proof_url
          ? `<a class="wh-proof" href="${escapeHtml(row.proof_url)}" target="_blank" rel="noopener">Bukti</a>`
          : '';

        return `
          <article class="wh-card">
            <div class="wh-card-top">
              <div class="wh-bank">
<div class="wh-bank-logo">
  ${
    logo
      ? `<img src="${escapeHtml(logo)}" alt="${escapeHtml(providerName)}" class="wh-bank-logo-img" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">`
      : ''
  }
  <span class="wh-bank-logo-fallback">${escapeHtml(providerInitial(providerName))}</span>
</div>

<div>
  <div class="wh-bank-name">${escapeHtml(providerName)}</div>
  <div class="wh-bank-number">•••• ${escapeHtml(maskNumber(number).slice(-8))}</div>
</div>
              </div>

              <div class="wh-status ${statusClass(row.status)}">
                ${escapeHtml(statusText(row.status))}
              </div>
            </div>

            <div class="wh-card-body">
              <div class="wh-row">
                <span>Jumlah penarikan</span>
                <strong>${rupiah(amount)}</strong>
              </div>

              <div class="wh-row is-fee">
                <span>Biaya</span>
                <strong>-${rupiah(fee)}</strong>
              </div>

              <div class="wh-row is-net">
                <span>Diterima</span>
                <strong>${rupiah(net)}</strong>
              </div>
            </div>

            <div class="wh-date">
              <svg viewBox="0 0 24 24" fill="none">
                <rect x="3" y="4" width="18" height="18" rx="3" stroke="currentColor" stroke-width="2"/>
                <path d="M16 2v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M8 2v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M3 10h18" stroke="currentColor" stroke-width="2"/>
              </svg>

              <span>${escapeHtml(formatDate(row))}</span>
              ${proof}
            </div>
          </article>
        `;
      }).join('');
    }

    async function loadHistory(){
      listEl.innerHTML = `<div class="wh-loading">Mengambil riwayat penarikan...</div>`;

      const payload = await api('/withdrawals');
      allRows = normalizeRows(payload);

      buildMonthOptions(allRows);
      render();
    }

    monthFilter.addEventListener('change', render);
    statusFilter.addEventListener('change', render);

    loadHistory().catch(function(error){
      toast(error.message, 'err');
      listEl.innerHTML = `
        <div class="wh-empty">
          Gagal mengambil riwayat penarikan. Pastikan endpoint /withdrawals sudah aktif.
        </div>
      `;
    });

function goBack(){
  window.location.href = '/ui/withdrawals';
}
  </script>
</body>
</html>