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

    .wh-logo{
      width:46px;
      height:46px;
      border-radius:17px;
      display:grid;
      place-items:center;
      overflow:hidden;
      background:
        radial-gradient(circle at 28% 8%, rgba(255,255,255,.98), rgba(255,226,155,.78) 34%, rgba(225,188,255,.76) 92%),
        var(--vl-gradient);
      border:1px solid rgba(255,255,255,.70);
      box-shadow:0 14px 30px rgba(88,43,145,.12), inset 0 1px 0 rgba(255,255,255,.72);
      flex:0 0 auto;
    }

    .wh-logo img{width:41px;height:41px;object-fit:contain;display:block}

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
      padding:18px;
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
    .wh-hero-inner{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:14px;align-items:start}
    .wh-hero-label{margin:0 0 8px;color:rgba(255,255,255,.74);font-size:12px;font-weight:700}
    .wh-hero-total{margin:0;color:#fff;font-size:32px;line-height:1.02;font-weight:800;letter-spacing:-.075em;text-shadow:0 12px 24px rgba(43,11,22,.22)}
    .wh-hero-sub{margin:9px 0 0;color:rgba(255,255,255,.70);font-size:11.5px;line-height:1.45;font-weight:650;max-width:260px}

    .wh-hero-pill{
      min-height:39px;
      border-radius:999px;
      padding:0 12px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:6px;
      color:#2b0b16;
      background:linear-gradient(135deg,#ffd46d 0%,#d96bff 56%,#8f57ff 100%);
      border:1px solid rgba(255,255,255,.40);
      box-shadow:0 12px 24px rgba(143,87,255,.18), inset 0 1px 0 rgba(255,255,255,.40);
      font-size:11px;
      font-weight:800;
      white-space:nowrap;
    }

    .wh-hero-pill svg{width:15px;height:15px}

    .wh-filter-card{margin-top:13px;animation:whFadeUp .42s ease both}
    .wh-filter-grid{display:grid;grid-template-columns:1fr 1fr;gap:9px}

    .wh-select{
      width:100%;
      height:44px;
      border-radius:16px;
      border:1px solid rgba(43,11,22,.08);
      outline:0;
      color:var(--vl-maroon);
      background:rgba(255,255,255,.90);
      padding:0 38px 0 13px;
      font-size:12px;
      font-weight:800;
      appearance:none;
      background-image:url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='rgba(58,7,18,.62)' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='m6 9 6 6 6-6'/%3e%3c/svg%3e");
      background-repeat:no-repeat;
      background-position:right 13px center;
      background-size:16px;
      box-shadow:0 10px 22px rgba(43,11,22,.055), inset 0 1px 0 rgba(255,255,255,.9);
    }

    .wh-select option{background:#fff;color:#2b0b16}

    .wh-list{margin-top:12px;display:flex;flex-direction:column;gap:10px}

    .wh-card{
      position:relative;
      overflow:hidden;
      border-radius:24px;
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
    .wh-card-top{padding:13px;display:flex;align-items:flex-start;justify-content:space-between;gap:12px}
    .wh-bank{display:flex;align-items:center;gap:11px;min-width:0}

    .wh-bank-logo{
      width:52px;
      height:52px;
      min-width:52px;
      border-radius:18px;
      display:flex;
      align-items:center;
      justify-content:center;
      background:#fff;
      border:1px solid rgba(43,11,22,.07);
      overflow:hidden;
      box-shadow:inset 0 1px 0 rgba(255,255,255,.70), 0 12px 24px rgba(43,11,22,.08);
    }

    .wh-bank-logo-img{display:block;width:40px;height:40px;object-fit:contain}
    .wh-bank-logo-fallback{display:none;width:100%;height:100%;align-items:center;justify-content:center;color:#2b0b16;font-size:11px;font-weight:900;line-height:1}
    .wh-bank-name{color:var(--vl-maroon);font-size:14px;line-height:1.15;font-weight:800;letter-spacing:-.02em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:170px}
    .wh-bank-number{margin-top:5px;color:var(--vl-soft);font-size:11px;font-weight:700;white-space:nowrap}

    .wh-status{
      min-height:28px;
      padding:0 10px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:6px;
      font-size:10.5px;
      font-weight:800;
      white-space:nowrap;
      flex:0 0 auto;
      border:1px solid transparent;
    }

    .wh-status::before{content:"";width:6px;height:6px;border-radius:999px;background:currentColor}
    .wh-status.is-paid{color:#15935d;background:#e9fff4;border-color:rgba(32,184,115,.18)}
    .wh-status.is-pending{color:#b87300;background:#fff6dc;border-color:rgba(245,158,11,.18)}
    .wh-status.is-approved{color:#3978ff;background:#eef4ff;border-color:rgba(57,120,255,.16)}
    .wh-status.is-rejected,.wh-status.is-cancelled{color:#d9435c;background:#fff0f3;border-color:rgba(226,74,100,.16)}

    .wh-card-body{
      border-top:1px solid rgba(43,11,22,.07);
      background:rgba(251,248,255,.74);
      padding:13px;
      display:grid;
      gap:10px;
    }

    .wh-row{display:flex;align-items:center;justify-content:space-between;gap:12px;color:var(--vl-soft);font-size:12px;font-weight:700}
    .wh-row strong{color:var(--vl-maroon);font-size:12.5px;font-weight:800;white-space:nowrap;text-align:right}
    .wh-row.is-fee strong{color:var(--vl-red)}
    .wh-row.is-net{padding-top:11px;border-top:1px solid rgba(43,11,22,.07)}
    .wh-row.is-net span{color:var(--vl-maroon);font-weight:800}
    .wh-row.is-net strong{color:var(--vl-purple);font-size:16px;letter-spacing:-.035em;font-weight:900}

    .wh-date{
      border-top:1px solid rgba(43,11,22,.07);
      padding:12px 13px;
      color:var(--vl-soft);
      font-size:11px;
      font-weight:700;
      display:flex;
      align-items:center;
      gap:7px;
      background:rgba(255,255,255,.55);
    }

    .wh-date svg{width:14px;height:14px;opacity:.8}
    .wh-proof{margin-left:auto;min-height:28px;padding:0 10px;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;color:#2c1200;background:var(--vl-gradient);font-size:10.5px;font-weight:900;box-shadow:0 10px 18px rgba(143,87,255,.14)}

    .wh-loading,.wh-empty{
      min-height:180px;
      border-radius:24px;
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
      .wh-logo{width:42px;height:42px;border-radius:15px}
      .wh-logo img{width:37px;height:37px}
      .wh-title h1{font-size:20px}
      .wh-filter-grid{grid-template-columns:1fr}
      .wh-bank-name{max-width:130px}
      .wh-hero-total{font-size:28px}
      .wh-card-top{padding:12px 10px}
      .wh-bank-logo{width:46px;height:46px;min-width:46px;border-radius:16px}
    }
  </style>
</head>

<body>
  <main class="wh-page">
    <div class="wh-phone">
      <header class="wh-topbar">
        <div class="wh-brand">
          <button type="button" class="wh-back" onclick="goBack()" aria-label="Kembali ke halaman sebelumnya">
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

      <section class="wh-hero">
        <div class="wh-hero-inner">
          <div>
            <p class="wh-hero-label">Total Entri</p>
            <h2 class="wh-hero-total" id="totalEntry">0</h2>
            <p class="wh-hero-sub">Semua permintaan penarikan kamu tersimpan rapi dan bisa difilter berdasarkan status atau bulan.</p>
          </div>

          <div class="wh-hero-pill">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
            </svg>
            Aman
          </div>
        </div>
      </section>

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
      return String(provider || 'VL').trim().slice(0,3).toUpperCase();
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
      return d.toLocaleDateString('id-ID', { month:'long', year:'numeric' });
    }

    function formatDate(row){
      const d = dateObj(row);
      if(!d || isNaN(d.getTime())) return '-';
      return d.toLocaleDateString('id-ID', {
        day:'2-digit', month:'short', year:'numeric'
      }) + ', ' + d.toLocaleTimeString('id-ID', {
        hour:'2-digit', minute:'2-digit'
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
