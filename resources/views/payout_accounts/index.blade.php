@include('partials.anti-inspect')
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Kelola Rekening | Capital Wave</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --blue:#0A57A3; --blue-lite:#2f7fd4; --blue-soft:#eef4fb;
      --navy:#0b2740; --navy-2:#0d3357; --navy-3:#0a2036;
      --gold:#c99433; --gold-lite:#e8c874; --gold-deep:#a9772a; --gold-soft:#faf3e2;
      --gold-metal:linear-gradient(135deg,#a9772a 0%,#e8c874 46%,#c99433 100%);
      --green:#1c9d67; --green-soft:#e6f5ee; --red:#dc5757; --red-soft:#fdeaea;
      --card:#ffffff; --tint:#f5f8fc; --line:#e9edf4; --line-2:#dfe5ee;
      --ink:#152a3f; --ink-soft:#46586c; --muted:#8493a6; --muted-2:#aab6c4;
      --sh-sm:0 1px 2px rgba(11,39,64,.05);
      --sh:0 2px 6px rgba(11,39,64,.04), 0 12px 30px rgba(11,39,64,.07);
      --sh-lg:0 4px 10px rgba(11,39,64,.05), 0 22px 50px rgba(11,39,64,.12);
      --sh-navy:0 10px 24px rgba(9,30,52,.28), 0 24px 60px rgba(9,30,52,.30);
      --r:24px;
    }
    *{ box-sizing:border-box; }
    html,body{ min-height:100%; }
    body{
      margin:0; color:var(--ink);
      font-family:'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
      background:
        radial-gradient(900px 500px at 50% -220px, rgba(10,87,163,.10), transparent 60%),
        radial-gradient(600px 400px at 100% 8%, rgba(201,148,51,.06), transparent 55%),
        linear-gradient(180deg,#f2f5f9 0%,#eef1f6 40%,#eaeef4 100%);
      background-attachment:fixed; overflow-x:hidden; -webkit-font-smoothing:antialiased; letter-spacing:-.01em;
    }
    a{ color:inherit; text-decoration:none; } button,input,select{ font-family:inherit; }
    h1,h2,h3,p{ margin:0; }
    .bank-page{ width:100%; min-height:100vh; display:flex; justify-content:center; }
    .bank-phone{ width:100%; max-width:428px; min-height:100vh; padding:18px 16px 40px; }

    /* header */
    .bank-header{ display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:16px; }
    .bank-brand{ display:flex; align-items:center; gap:11px; min-width:0; }
    .bank-logo{ width:42px; height:42px; flex:0 0 auto; border:1px solid var(--line); background:var(--card); color:var(--navy); border-radius:13px; display:grid; place-items:center; box-shadow:var(--sh-sm); }
    .bank-logo svg{ width:20px; height:20px; }
    .bank-title-wrap h1{ font-size:17px; font-weight:700; letter-spacing:-.02em; color:var(--navy); }
    .bank-title-wrap p{ margin-top:3px; font-size:10px; font-weight:600; letter-spacing:.14em; text-transform:uppercase; color:var(--muted); }
    .bank-header-actions{ display:flex; gap:8px; }
    .bank-header-btn{ width:42px; height:42px; border:1px solid var(--line); background:var(--card); color:var(--ink-soft); border-radius:13px; display:grid; place-items:center; box-shadow:var(--sh-sm); }
    .bank-header-btn svg{ width:19px; height:19px; }

    /* hero */
    .bank-hero{ position:relative; overflow:hidden; border-radius:26px; padding:20px; color:#fff;
      background:
        radial-gradient(420px 240px at 90% -20%, rgba(232,200,116,.18), transparent 62%),
        radial-gradient(360px 220px at 5% 120%, rgba(47,127,212,.22), transparent 60%),
        linear-gradient(150deg,#0f3255 0%,#0b2740 52%,#0a2036 100%);
      box-shadow:var(--sh-navy); margin-bottom:8px; }
    .bank-hero::before{ content:""; position:absolute; inset:0; border-radius:inherit; padding:1px; pointer-events:none;
      background:linear-gradient(150deg, rgba(232,200,116,.6), rgba(232,200,116,0) 34%, rgba(255,255,255,.14) 100%);
      -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; }
    .bank-hero-inner{ position:relative; z-index:1; display:flex; align-items:center; gap:14px; }
    .bank-hero-inner > div:first-child{ flex:1; min-width:0; }
    .bank-hero-label{ display:inline-flex; align-items:center; gap:8px; color:rgba(255,255,255,.62); font-size:9.5px; font-weight:600; letter-spacing:.18em; text-transform:uppercase; }
    .bank-hero-label::before{ content:""; width:6px; height:6px; border-radius:999px; background:var(--gold-lite); box-shadow:0 0 10px rgba(232,200,116,.8); }
    .bank-hero-title{ margin-top:12px; font-size:22px; font-weight:700; letter-spacing:-.03em; }
    .bank-hero-sub{ margin-top:8px; font-size:11.5px; font-weight:500; color:rgba(255,255,255,.55); line-height:1.5; max-width:250px; }
    .bank-hero-pill{ width:64px; height:64px; flex:0 0 auto; border-radius:20px; display:grid; place-items:center; color:var(--navy); background:var(--gold-metal); box-shadow:0 10px 24px rgba(201,148,51,.34); }
    .bank-hero-pill svg{ width:30px; height:30px; }

    /* list */
    .bank-loader{ margin-top:16px; padding:40px 20px; border-radius:var(--r); background:var(--card); border:1px solid var(--line); text-align:center; color:var(--muted); font-size:12.5px; font-weight:600; }
    .bank-list{ margin-top:14px; display:flex; flex-direction:column; gap:13px; }
    .bank-card{ position:relative; overflow:hidden; border-radius:20px; background:var(--card); border:1px solid var(--line); box-shadow:var(--sh); padding:16px; }
    .bank-card::before{ content:""; position:absolute; top:0; left:0; right:0; height:3px; background:var(--gold-metal); }
    .bank-card-top{ display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:14px; }
    .bank-card-brand{ display:flex; align-items:center; gap:12px; min-width:0; }
    .bank-provider-icon{ width:46px; height:46px; flex:0 0 auto; border-radius:13px; display:grid; place-items:center; overflow:hidden; color:var(--navy); font-size:13px; font-weight:700; background:var(--blue-soft); }
    .bank-provider-icon img{ width:100%; height:100%; object-fit:contain; padding:7px; background:#fff; }
    .bank-provider-label{ font-size:9px; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:var(--muted); }
    .bank-provider-name{ margin-top:3px; font-size:15px; font-weight:700; color:var(--navy); letter-spacing:-.02em; }
    .bank-default{ flex:0 0 auto; display:inline-flex; align-items:center; gap:4px; padding:5px 11px; border-radius:999px; background:var(--gold-soft); color:var(--gold-deep); font-size:10px; font-weight:700; }
    .bank-card-body{ display:grid; grid-template-columns:1fr 1fr; gap:12px; padding:13px 0; border-top:1px solid var(--line); border-bottom:1px solid var(--line); }
    .bank-info-label{ font-size:9.5px; font-weight:500; letter-spacing:.04em; text-transform:uppercase; color:var(--muted); }
    .bank-info-value{ margin-top:5px; font-size:13px; font-weight:700; color:var(--navy); letter-spacing:.03em; }
    .bank-card-actions{ margin-top:14px; display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    .bank-action{ min-height:44px; border:0; border-radius:12px; font-size:12.5px; font-weight:700; cursor:pointer; transition:.15s ease; }
    .bank-action.is-edit{ color:var(--blue); background:var(--blue-soft); }
    .bank-action.is-edit:hover{ background:#e2edf9; }
    .bank-action.is-delete{ color:var(--red); background:var(--red-soft); }
    .bank-action.is-delete:hover{ background:#fbe0e0; }

    .bank-primary{ width:100%; min-height:50px; border:0; border-radius:14px; cursor:pointer; color:#fff; background:linear-gradient(135deg,#123457,#0b2740); box-shadow:0 10px 22px rgba(11,39,64,.24), inset 0 1px 0 rgba(255,255,255,.08); font-size:13.5px; font-weight:700; transition:.15s ease; }
    .bank-primary:hover{ transform:translateY(-1px); }
    .bank-primary:disabled{ opacity:.6; cursor:default; transform:none; }
    .bank-floating-add{ margin-top:2px; position:relative; overflow:hidden; }
    .bank-floating-add::after{ content:""; position:absolute; inset:0; border-radius:inherit; padding:1px; background:linear-gradient(135deg, rgba(232,200,116,.7), transparent 55%); -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; opacity:.6; }
    .bank-secondary{ width:100%; min-height:48px; border:1px solid var(--line-2); border-radius:14px; cursor:pointer; color:var(--ink-soft); background:var(--card); font-size:13px; font-weight:600; }

    /* empty */
    .bank-empty{ margin-top:14px; padding:34px 22px; border-radius:var(--r); background:var(--card); border:1px dashed var(--line-2); text-align:center; }
    .bank-empty-illustration{ width:66px; height:66px; margin:0 auto 16px; border-radius:20px; display:grid; place-items:center; color:var(--blue); background:var(--blue-soft); }
    .bank-empty-illustration svg{ width:32px; height:32px; }
    .bank-empty-title{ font-size:16px; font-weight:700; color:var(--navy); }
    .bank-empty-text{ margin:8px 0 18px; font-size:12.5px; font-weight:500; color:var(--muted); line-height:1.55; }

    /* overlay sheet */
    .bank-overlay{ position:fixed; inset:0; z-index:1000; display:none; align-items:flex-end; justify-content:center; background:rgba(11,39,64,.55); backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px); }
    .bank-overlay.show{ display:flex; }
    .bank-sheet{ width:100%; max-width:460px; max-height:92vh; overflow:auto; border-radius:26px 26px 0 0; background:var(--card); box-shadow:0 -20px 60px rgba(11,39,64,.3); padding:20px 18px calc(18px + env(safe-area-inset-bottom)); animation:bankSheet .3s cubic-bezier(.22,.8,.22,1) both; }
    @keyframes bankSheet{ from{ transform:translateY(30px); opacity:0; } to{ transform:translateY(0); opacity:1; } }
    .bank-sheet-head{ display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:16px; }
    .bank-sheet-title{ font-size:17px; font-weight:700; color:var(--navy); letter-spacing:-.02em; }
    .bank-close{ width:38px; height:38px; border:1px solid var(--line); background:var(--tint); color:var(--ink-soft); border-radius:12px; display:grid; place-items:center; cursor:pointer; }
    .bank-close svg{ width:18px; height:18px; }
    .bank-form-group{ margin-bottom:14px; }
    .bank-label{ display:block; margin-bottom:8px; font-size:11.5px; font-weight:600; color:var(--ink-soft); }
    .bank-input,.bank-select{ width:100%; height:50px; padding:0 14px; border-radius:14px; border:1px solid var(--line-2); background:var(--tint); color:var(--ink); font-size:14px; font-weight:600; outline:none; transition:.15s ease; }
    .bank-input:focus,.bank-select:focus{ border-color:var(--blue); box-shadow:0 0 0 3px rgba(10,87,163,.1); background:var(--card); }
    .bank-input::placeholder{ color:var(--muted-2); font-weight:500; }
    .bank-select{ appearance:none; -webkit-appearance:none; padding-right:42px; cursor:pointer;
      background-image:url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%238493a6' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='m6 9 6 6 6-6'/%3e%3c/svg%3e");
      background-repeat:no-repeat; background-position:right 14px center; background-size:18px; }
    .bank-check{ display:flex; align-items:center; gap:10px; margin:4px 0 4px; cursor:pointer; }
    .bank-check input{ width:20px; height:20px; accent-color:var(--blue); }
    .bank-check-text{ font-size:13px; font-weight:600; color:var(--ink-soft); }
    .bank-sheet-actions{ margin-top:18px; display:grid; gap:10px; }

    /* toast */
    .bank-toast{ position:fixed; left:50%; bottom:28px; transform:translateX(-50%) translateY(20px); z-index:2000; opacity:0; pointer-events:none;
      display:flex; align-items:center; gap:8px; padding:13px 18px; border-radius:14px; background:var(--navy); color:#fff; box-shadow:var(--sh-lg);
      font-size:12.5px; font-weight:600; transition:.28s cubic-bezier(.22,.8,.22,1); }
    .bank-toast.show{ opacity:1; transform:translateX(-50%) translateY(0); }
    .bank-toast.is-error{ background:linear-gradient(135deg,#dc5757,#b83f3f); }

    @media (prefers-reduced-motion:reduce){ *,*::before,*::after{ animation:none !important; transition:none !important; } }
  </style>
</head>

<body>
  <main class="bank-page">
    <div class="bank-phone">

      <header class="bank-header">
        <div class="bank-brand">
          <a href="{{ url('/akun') }}" class="bank-logo" aria-label="Kembali">
            <svg viewBox="0 0 24 24" fill="none"><path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
          <div class="bank-title-wrap">
            <h1>Kelola Rekening</h1>
            <p>Akun Penarikan</p>
          </div>
        </div>
        <div class="bank-header-actions">
          <a href="{{ url('/dashboard') }}" class="bank-header-btn" aria-label="Dashboard">
            <svg viewBox="0 0 24 24" fill="none"><path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
          </a>
        </div>
      </header>

      <section class="bank-hero">
        <div class="bank-hero-inner">
          <div>
            <p class="bank-hero-label">Akun Penerima Penarikan</p>
            <h2 class="bank-hero-title">Kelola Rekening</h2>
            <p class="bank-hero-sub">Tambahkan e-wallet atau rekening bank untuk menerima pencairan saldo Capital Wave.</p>
          </div>
          <div class="bank-hero-pill">
            <svg viewBox="0 0 24 24" fill="none"><path d="M4 10 12 4l8 6" stroke="currentColor" stroke-width="1.9" stroke-linejoin="round"/><path d="M5 10v9h14v-9" stroke="currentColor" stroke-width="1.9" stroke-linejoin="round"/><path d="M9 19v-5h6v5" stroke="currentColor" stroke-width="1.9" stroke-linejoin="round"/></svg>
          </div>
        </div>
      </section>

      <section id="bankListWrap">
        <div class="bank-loader">Mengambil data rekening...</div>
      </section>
    </div>
  </main>

  {{-- SHEET --}}
  <div class="bank-overlay" id="bankOverlay" role="dialog" aria-modal="true" aria-labelledby="bankSheetTitle">
    <section class="bank-sheet">
      <div class="bank-sheet-head">
        <h2 class="bank-sheet-title" id="bankSheetTitle">Data Akun Penarikan</h2>
        <button class="bank-close" type="button" id="closeSheet" aria-label="Tutup">
          <svg viewBox="0 0 24 24" fill="none"><path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </button>
      </div>

      <form class="bank-form" id="form" novalidate>
        <input type="hidden" id="id" />
        <input type="hidden" id="type" value="EWALLET" />

        <div class="bank-form-group">
          <label class="bank-label" for="provider">Pilih Metode Penarikan</label>
          <select id="provider" class="bank-select" required>
            <option value="DANA">DANA</option>
            <option value="OVO">OVO</option>
            <option value="BCA">BCA</option>
            <option value="BRI">BRI</option>
            <option value="BNI">BNI</option>
            <option value="MANDIRI">MANDIRI</option>
            <option value="PERMATA">PERMATA</option>
            <option value="CIMB">CIMB</option>
            <option value="DANAMON">DANAMON</option>
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
