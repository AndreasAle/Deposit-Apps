@include('partials.anti-inspect')
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Riwayat Penarikan | Velora Finance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,600;1,700&display=swap" rel="stylesheet">

  <style>
    :root{
      --vl-bg:#f7f2fa;
      --vl-bg2:#efe8f7;
      --vl-paper:#ffffff;
      --vl-paper2:#fbf8ff;
      --vl-text:#2b0b16;
      --vl-maroon:#3a0712;
      --vl-soft:#7b6370;
      --vl-muted:#a894a0;
      --vl-border:rgba(43,11,22,.085);
      --vl-gold:#f5af2a;
      --vl-gold2:#ffd46d;
      --vl-purple:#8f57ff;
      --vl-violet:#b45cff;
      --vl-pink:#d96bff;
      --vl-green:#20b873;
      --vl-blue:#3978ff;
      --vl-red:#e24a64;
      --vl-amber:#f59e0b;
      --vl-gradient:linear-gradient(135deg,#f5af2a 0%,#ffd46d 26%,#d96bff 58%,#8f57ff 100%);
      --vl-gradient-soft:linear-gradient(145deg,#8f57ff 0%,#9f55ff 38%,#d96bff 72%,#f5af2a 100%);
      --vl-shadow:0 22px 54px rgba(88,43,145,.15);
      --vl-shadow-soft:0 13px 30px rgba(43,11,22,.075);
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
      mask-image:linear-gradient(180deg, rgba(0,0,0,.40), transparent 76%);
      -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.40), transparent 76%);
      opacity:.55;
      z-index:0;
    }

    a{color:inherit;text-decoration:none}
    button,input,select{font-family:inherit}

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

    /* Topbar */
    .wh-topbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:15px;
      padding:0 2px;
    }

    .wh-brand{display:flex;align-items:center;gap:11px;min-width:0}

    .wh-back,
    .wh-header-icon{
      width:42px;
      height:42px;
      border:1px solid rgba(43,11,22,.08);
      background:rgba(255,255,255,.88);
      color:#5b2841;
      display:grid;
      place-items:center;
      box-shadow:0 12px 26px rgba(43,11,22,.065), inset 0 1px 0 rgba(255,255,255,.92);
      backdrop-filter:blur(18px);
      -webkit-backdrop-filter:blur(18px);
      cursor:pointer;
      transition:.18s ease;
    }

    .wh-back{border-radius:16px}
    .wh-header-icon{border-radius:999px;flex:0 0 auto}
    .wh-back:hover,.wh-header-icon:hover{transform:translateY(-1px);color:var(--vl-purple)}
    .wh-back svg,.wh-header-icon svg{width:20px;height:20px}

    .wh-title{min-width:0}
    .wh-title span{
      display:block;
      margin-bottom:5px;
      color:rgba(58,7,18,.58);
      font-size:10px;
      line-height:1;
      font-weight:800;
      letter-spacing:.15em;
      text-transform:uppercase;
    }
    .wh-title h1{
      margin:0;
      color:var(--vl-maroon);
      font-size:22px;
      line-height:1;
      font-weight:800;
      letter-spacing:-.052em;
      white-space:nowrap;
    }

    /* Stats Hero Card */
    .wh-hero{
      position:relative;
      overflow:hidden;
      border-radius:30px;
      background:
        radial-gradient(360px 220px at 92% -12%, rgba(255,212,109,.45), transparent 58%),
        radial-gradient(300px 200px at 2% 8%, rgba(217,107,255,.34), transparent 62%),
        linear-gradient(145deg,#8f57ff 0%,#9455ff 40%,#d96bff 72%,#f5af2a 100%);
      color:#fff;
      border:1px solid rgba(255,255,255,.44);
      box-shadow:0 28px 62px rgba(143,87,255,.20), 0 18px 42px rgba(245,175,42,.10), inset 0 1px 0 rgba(255,255,255,.22);
      padding:18px 18px 20px;
      margin-bottom:13px;
      animation:whFadeUp .42s ease both;
    }

    .wh-hero::before{
      content:"";
      position:absolute;
      inset:0;
      pointer-events:none;
      background:linear-gradient(135deg, rgba(255,255,255,.22), transparent 34%), radial-gradient(circle at 82% 26%, rgba(255,255,255,.16), transparent 28%);
    }

    .wh-hero > *{position:relative;z-index:1}

    .wh-hero-label{
      margin:0 0 6px;
      color:rgba(255,255,255,.74);
      font-size:11.5px;
      font-weight:700;
    }

    .wh-hero-grid{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:10px;
      align-items:stretch;
    }

    .wh-hero-stat{
      background:rgba(255,255,255,.14);
      border:1px solid rgba(255,255,255,.20);
      border-radius:18px;
      padding:13px 14px;
      backdrop-filter:blur(8px);
    }

    .wh-hero-stat-label{
      color:rgba(255,255,255,.72);
      font-size:10.5px;
      font-weight:700;
      margin-bottom:6px;
    }

    .wh-hero-stat-value{
      color:#fff;
      font-size:20px;
      font-weight:900;
      letter-spacing:-.05em;
      line-height:1.1;
      text-shadow:0 8px 18px rgba(43,11,22,.18);
    }

    /* Tab Filters */
    .wh-tabs{
      display:flex;
      gap:8px;
      margin-bottom:14px;
      overflow-x:auto;
      scrollbar-width:none;
      padding-bottom:2px;
    }
    .wh-tabs::-webkit-scrollbar{display:none}

    .wh-tab{
      min-height:38px;
      padding:0 16px;
      border-radius:999px;
      border:1px solid rgba(43,11,22,.10);
      background:rgba(255,255,255,.82);
      color:var(--vl-soft);
      font-size:12px;
      font-weight:800;
      cursor:pointer;
      white-space:nowrap;
      transition:.18s ease;
      box-shadow:0 8px 18px rgba(43,11,22,.05), inset 0 1px 0 rgba(255,255,255,.9);
      display:flex;
      align-items:center;
      gap:6px;
    }

    .wh-tab.is-active{
      background:var(--vl-gradient);
      color:#2c1200;
      border-color:transparent;
      box-shadow:0 12px 26px rgba(143,87,255,.22), inset 0 1px 0 rgba(255,255,255,.34);
    }

    .wh-tab-count{
      background:rgba(43,11,22,.10);
      color:inherit;
      font-size:10px;
      font-weight:900;
      min-width:18px;
      height:18px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      padding:0 5px;
    }

    .wh-tab.is-active .wh-tab-count{
      background:rgba(44,18,0,.14);
    }

    /* Date Group */
    .wh-date-group{margin-bottom:6px}

    .wh-date-label{
      font-size:11px;
      font-weight:800;
      color:var(--vl-soft);
      letter-spacing:.04em;
      text-transform:uppercase;
      padding:10px 2px 7px;
    }

    /* Transaction Card */
    .wh-list{display:flex;flex-direction:column;gap:10px}

    .wh-card{
      position:relative;
      overflow:hidden;
      border-radius:22px;
      background:radial-gradient(220px 120px at 96% 0%, rgba(217,107,255,.11), transparent 64%), linear-gradient(180deg,rgba(255,255,255,.98),rgba(255,255,255,.90));
      border:1px solid rgba(43,11,22,.075);
      box-shadow:var(--vl-shadow-soft), inset 0 1px 0 rgba(255,255,255,.94);
      animation:whFadeUp .42s ease both;
    }

    .wh-card::before{
      content:"";
      position:absolute;
      inset:0;
      pointer-events:none;
      background:linear-gradient(135deg,rgba(255,255,255,.76),transparent 30%), radial-gradient(circle at 12% 0%, rgba(245,175,42,.08), transparent 42%);
    }

    .wh-card > *{position:relative;z-index:1}

    .wh-card-main{
      padding:13px 14px;
      display:flex;
      align-items:center;
      gap:12px;
    }

    .wh-bank-logo{
      width:48px;
      height:48px;
      min-width:48px;
      border-radius:16px;
      display:flex;
      align-items:center;
      justify-content:center;
      background:#fff;
      border:1px solid rgba(43,11,22,.07);
      overflow:hidden;
      box-shadow:inset 0 1px 0 rgba(255,255,255,.70), 0 8px 18px rgba(43,11,22,.07);
    }

    .wh-bank-logo-img{display:block;width:36px;height:36px;object-fit:contain}
    .wh-bank-logo-fallback{display:none;width:100%;height:100%;align-items:center;justify-content:center;color:#2b0b16;font-size:11px;font-weight:900;line-height:1}

    .wh-card-info{flex:1;min-width:0}
    .wh-card-provider{color:var(--vl-maroon);font-size:14px;font-weight:800;letter-spacing:-.02em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .wh-card-number{margin-top:3px;color:var(--vl-soft);font-size:11px;font-weight:700}

    .wh-card-right{display:flex;flex-direction:column;align-items:flex-end;gap:5px;flex:0 0 auto}
    .wh-card-amount{color:var(--vl-maroon);font-size:15px;font-weight:900;letter-spacing:-.04em;white-space:nowrap}

    .wh-status{
      min-height:24px;
      padding:0 9px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:5px;
      font-size:10px;
      font-weight:800;
      white-space:nowrap;
      border:1px solid transparent;
    }

    .wh-status::before{content:"";width:5px;height:5px;border-radius:999px;background:currentColor}
    .wh-status.is-paid{color:#15935d;background:#e9fff4;border-color:rgba(32,184,115,.18)}
    .wh-status.is-pending{color:#b87300;background:#fff6dc;border-color:rgba(245,158,11,.18)}
    .wh-status.is-approved{color:#3978ff;background:#eef4ff;border-color:rgba(57,120,255,.16)}
    .wh-status.is-rejected,.wh-status.is-cancelled{color:#d9435c;background:#fff0f3;border-color:rgba(226,74,100,.16)}

    .wh-card-footer{
      border-top:1px solid rgba(43,11,22,.06);
      padding:9px 14px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      background:rgba(251,248,255,.60);
    }

    .wh-card-footer-left{
      display:flex;
      align-items:center;
      gap:6px;
      color:var(--vl-soft);
      font-size:10.5px;
      font-weight:700;
    }

    .wh-card-footer-left svg{width:13px;height:13px;opacity:.7}

    .wh-card-fee{
      color:var(--vl-red);
      font-size:10.5px;
      font-weight:800;
    }

    .wh-proof{
      min-height:24px;
      padding:0 9px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      color:#2c1200;
      background:var(--vl-gradient);
      font-size:10px;
      font-weight:900;
      box-shadow:0 8px 16px rgba(143,87,255,.14);
    }

    .wh-loading,.wh-empty{
      min-height:180px;
      border-radius:22px;
      border:1px dashed rgba(43,11,22,.16);
      background:rgba(255,255,255,.78);
      color:var(--vl-soft);
      display:flex;
      align-items:center;
      justify-content:center;
      text-align:center;
      padding:18px;
      font-size:12.5px;
      font-weight:800;
      box-shadow:var(--vl-shadow-soft);
    }

    /* Bottom CTA */
    .wh-bottom-actions{
      position:fixed;
      left:50%;
      bottom:0;
      transform:translateX(-50%);
      z-index:50;
      width:min(100%,430px);
      padding:12px 14px calc(14px + env(safe-area-inset-bottom));
      background:linear-gradient(180deg, rgba(247,242,250,0), rgba(247,242,250,.88) 26%, rgba(247,242,250,.98));
      pointer-events:none;
    }

    .wh-main-btn{
      width:100%;
      min-height:50px;
      border:0;
      border-radius:999px;
      color:#2c1200;
      background:var(--vl-gradient);
      box-shadow:0 18px 38px rgba(143,87,255,.22), inset 0 1px 0 rgba(255,255,255,.34);
      font-size:13px;
      font-weight:900;
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
      color:#2c1200;
      background:var(--vl-gradient);
      box-shadow:0 18px 42px rgba(43,11,22,.18);
      font-size:12px;
      font-weight:850;
      transition:.22s ease;
      white-space:nowrap;
    }

    .wh-toast.is-error{color:#fff;background:linear-gradient(135deg,#e24a64,#d96bff)}
    .wh-toast.show{opacity:1;transform:translateX(-50%) translateY(0)}

    @keyframes whFadeUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}

    @media(min-width:768px){
      .wh-page{padding:22px 0}
      .wh-phone{min-height:calc(100vh - 44px);border-radius:26px;overflow:hidden}
      .wh-bottom-actions{bottom:22px;border-radius:0 0 26px 26px}
    }

    @media(max-width:370px){
      .wh-title h1{font-size:20px}
      .wh-hero-stat-value{font-size:17px}
      .wh-card-amount{font-size:13px}
    }
  </style>
</head>

<body>
  <main class="wh-page">
    <div class="wh-phone">

      <header class="wh-topbar">
        <div class="wh-brand">
          <button type="button" class="wh-back" onclick="goBack()" aria-label="Kembali">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M15 18 9 12l6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
          <div class="wh-title">
            <span>Velora Withdraw</span>
            <h1>Riwayat Penarikan</h1>
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

      <!-- Stats Hero -->
      <section class="wh-hero">
        <div class="wh-hero-grid">
          <div class="wh-hero-stat">
            <div class="wh-hero-stat-label">Total Penarikan Sukses</div>
            <div class="wh-hero-stat-value" id="totalSuccess">Rp 0</div>
          </div>
          <div class="wh-hero-stat">
            <div class="wh-hero-stat-label">7 Hari Terakhir</div>
            <div class="wh-hero-stat-value" id="last7Days">Rp 0</div>
          </div>
        </div>
      </section>

      <!-- Tab Filters -->
      <div class="wh-tabs" id="tabContainer">
        <button class="wh-tab is-active" data-tab="all" onclick="switchTab('all')">
          Semua <span class="wh-tab-count" id="countAll">0</span>
        </button>
        <button class="wh-tab" data-tab="PAID" onclick="switchTab('PAID')">
          Berhasil <span class="wh-tab-count" id="countPaid">0</span>
        </button>
        <button class="wh-tab" data-tab="PROCESSING" onclick="switchTab('PROCESSING')">
          Diproses <span class="wh-tab-count" id="countProcessing">0</span>
        </button>
        <button class="wh-tab" data-tab="PENDING" onclick="switchTab('PENDING')">
          Menunggu <span class="wh-tab-count" id="countPending">0</span>
        </button>
        <button class="wh-tab" data-tab="FAILED" onclick="switchTab('FAILED')">
          Gagal <span class="wh-tab-count" id="countFailed">0</span>
        </button>
      </div>

      <!-- List -->
      <div id="withdrawHistoryList" class="wh-list">
        <div class="wh-loading">Mengambil riwayat penarikan...</div>
      </div>

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
    const toastEl = document.getElementById('whToast');
    const toastText = document.getElementById('whToastText');

    let allRows = [];
    let activeTab = 'all';

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
      const res = await fetch(url, { credentials:'same-origin', ...options, headers });
      let data = null;
      try { data = await res.json(); } catch { data = {}; }
      if(!res.ok) throw new Error(data?.message || data?.error || 'Request gagal.');
      return data;
    }

    function toast(message, type = 'success'){
      if(!toastEl || !toastText) return;
      toastText.textContent = message;
      toastEl.classList.toggle('is-error', type === 'err');
      toastEl.classList.add('show');
      clearTimeout(window.__whToastTimer);
      window.__whToastTimer = setTimeout(function(){ toastEl.classList.remove('show'); }, 1800);
    }

    function rupiah(n){
      try { return 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(n || 0)); }
      catch { return 'Rp ' + String(n || 0); }
    }

    function escapeHtml(str){
      return String(str ?? '')
        .replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;')
        .replaceAll('"','&quot;').replaceAll("'","&#039;");
    }

    function providerInitial(p){ return String(p || 'VL').trim().slice(0,3).toUpperCase(); }

    function providerLogo(provider){
      const key = String(provider || '').trim().toUpperCase();
      const logos = {
        BCA:'/assets/payment-methods/bca.png', BRI:'/assets/payment-methods/bri.png',
        BNI:'/assets/payment-methods/bni.png', MANDIRI:'/assets/payment-methods/mandiri.png',
        DANA:'/assets/payment-methods/dana.png', GOPAY:'/assets/payment-methods/gopay.png',
        OVO:'/assets/payment-methods/ovo.png', DOKU:'/assets/payment-methods/doku.png',
        LINKAJA:'/assets/payment-methods/linkaja.png', SHOPEEPAY:'/assets/payment-methods/shopeepay.png',
        QRIS:'/assets/payment-methods/qris.png'
      };
      return logos[key] || '';
    }

    function providerDisplayName(provider){
      const key = String(provider || '').trim().toUpperCase();
      const names = {
        BCA:'BCA', BRI:'BRI', BNI:'BNI', MANDIRI:'Mandiri',
        DANA:'DANA', GOPAY:'GoPay', OVO:'OVO', DOKU:'DOKU',
        LINKAJA:'LinkAja', SHOPEEPAY:'ShopeePay', QRIS:'QRIS'
      };
      return names[key] || provider || 'Rekening';
    }

    function maskNumber(n){
      const raw = String(n || '');
      if(raw.length <= 6) return raw;
      return raw.slice(0,4) + '*'.repeat(Math.max(raw.length - 8, 4)) + raw.slice(-4);
    }

    function statusText(status){
      const s = String(status || '').toUpperCase();
      if(s === 'PAID') return 'Sukses';
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
      if(s === 'PROCESSING' || s === 'APPROVED') return 'is-approved';
      if(s === 'FAILED' || s === 'REJECTED') return 'is-rejected';
      if(s === 'CANCELLED') return 'is-cancelled';
      return 'is-pending';
    }

    function dateObj(row){ return row.created_at ? new Date(row.created_at) : null; }

    function dateGroupKey(row){
      const d = dateObj(row);
      if(!d || isNaN(d.getTime())) return '';
      return d.toISOString().slice(0,10);
    }

    function dateGroupLabel(key){
      if(!key) return '';
      const d = new Date(key + 'T00:00:00');
      return d.toLocaleDateString('id-ID', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
    }

    function formatTime(row){
      const d = dateObj(row);
      if(!d || isNaN(d.getTime())) return '-';
      const date = d.toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' });
      const time = d.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' });
      return date + ' | ' + time + ' WIB';
    }

    function normalizeRows(payload){
      if(Array.isArray(payload?.data)) return payload.data;
      if(Array.isArray(payload)) return payload;
      return [];
    }

    function getAccount(row){ return row.payout_account || row.payoutAccount || null; }

    function getFee(row){
      try {
        const gw = typeof row.gateway_response === 'string' ? JSON.parse(row.gateway_response) : (row.gateway_response || {});
        const gwFee = Number(gw.fee || 0);
        if(gwFee > 0) return gwFee;
      } catch{}
      return Number(row.fee || 0);
    }

    function getAmount(row){ return Number(row.amount || 0); }
    function getNet(row){ return Math.max(getAmount(row) - getFee(row), 0); }

    function switchTab(tab){
      activeTab = tab;
      document.querySelectorAll('.wh-tab').forEach(function(btn){
        btn.classList.toggle('is-active', btn.dataset.tab === tab);
      });
      render();
    }

    function filteredRows(){
      if(activeTab === 'all') return allRows;
      return allRows.filter(function(row){
        return String(row.status || '').toUpperCase() === activeTab;
      });
    }

    function updateCounts(){
      const statuses = ['PAID','PROCESSING','PENDING','FAILED'];
      const ids = { PAID:'countPaid', PROCESSING:'countProcessing', PENDING:'countPending', FAILED:'countFailed' };
      document.getElementById('countAll').textContent = allRows.length;
      statuses.forEach(function(s){
        const el = document.getElementById(ids[s]);
        if(el) el.textContent = allRows.filter(function(r){ return String(r.status||'').toUpperCase() === s; }).length;
      });
    }

    function updateStats(){
      const paidRows = allRows.filter(function(r){ return String(r.status||'').toUpperCase() === 'PAID'; });
      const totalSuccessAmount = paidRows.reduce(function(sum, r){ return sum + getNet(r); }, 0);
      const cutoff = new Date();
      cutoff.setDate(cutoff.getDate() - 7);
      const last7Amount = paidRows.filter(function(r){
        const d = dateObj(r);
        return d && d >= cutoff;
      }).reduce(function(sum, r){ return sum + getNet(r); }, 0);

      document.getElementById('totalSuccess').textContent = rupiah(totalSuccessAmount);
      document.getElementById('last7Days').textContent = rupiah(last7Amount);
    }

    function render(){
      const rows = filteredRows();

      if(!rows.length){
        listEl.innerHTML = '<div class="wh-empty">Tidak ada riwayat untuk filter ini.</div>';
        return;
      }

      // Group by date
      const groups = {};
      const groupOrder = [];
      rows.forEach(function(row){
        const key = dateGroupKey(row);
        if(!groups[key]){ groups[key] = []; groupOrder.push(key); }
        groups[key].push(row);
      });

      listEl.innerHTML = groupOrder.map(function(dateKey){
        const groupRows = groups[dateKey];
        const label = dateGroupLabel(dateKey);

        const cards = groupRows.map(function(row){
          const account = getAccount(row);
          const provider = account?.provider || row.bank_code || row.method || 'Rekening';
          const providerName = providerDisplayName(provider);
          const logo = providerLogo(provider);
          const number = account?.account_number || row.account_no || '-';
          const amount = getAmount(row);
          const fee = getFee(row);

          const proof = row.proof_url
            ? `<a class="wh-proof" href="${escapeHtml(row.proof_url)}" target="_blank" rel="noopener">Bukti</a>`
            : '';

          return `
            <article class="wh-card">
              <div class="wh-card-main">
                <div class="wh-bank-logo">
                  ${logo
                    ? `<img src="${escapeHtml(logo)}" alt="${escapeHtml(providerName)}" class="wh-bank-logo-img" loading="lazy" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">`
                    : ''}
                  <span class="wh-bank-logo-fallback">${escapeHtml(providerInitial(providerName))}</span>
                </div>

                <div class="wh-card-info">
                  <div class="wh-card-provider">${escapeHtml(providerName)}</div>
                  <div class="wh-card-number">${escapeHtml(maskNumber(number))}</div>
                </div>

                <div class="wh-card-right">
                  <div class="wh-card-amount">${rupiah(amount)}</div>
                  <div class="wh-status ${statusClass(row.status)}">${escapeHtml(statusText(row.status))}</div>
                </div>
              </div>

              <div class="wh-card-footer">
                <div class="wh-card-footer-left">
                  <svg viewBox="0 0 24 24" fill="none">
                    <rect x="3" y="4" width="18" height="18" rx="3" stroke="currentColor" stroke-width="2"/>
                    <path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                  <span>${escapeHtml(formatTime(row))}</span>
                </div>
                <div style="display:flex;align-items:center;gap:7px">
                  ${fee > 0 ? `<span class="wh-card-fee">Fee: -${rupiah(fee)}</span>` : ''}
                  ${proof}
                </div>
              </div>
            </article>
          `;
        }).join('');

        return `
          <div class="wh-date-group">
            <div class="wh-date-label">${escapeHtml(label)}</div>
            ${cards}
          </div>
        `;
      }).join('');
    }

    async function loadHistory(){
      listEl.innerHTML = '<div class="wh-loading">Mengambil riwayat penarikan...</div>';
      const payload = await api('/withdrawals');
      allRows = normalizeRows(payload);
      updateStats();
      updateCounts();
      render();
    }

    loadHistory().catch(function(error){
      toast(error.message, 'err');
      listEl.innerHTML = '<div class="wh-empty">Gagal mengambil riwayat penarikan.</div>';
    });

    function goBack(){ window.location.href = '/ui/withdrawals'; }
  </script>
</body>
</html>
