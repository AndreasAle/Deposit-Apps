@include('partials.anti-inspect')
@php
  $user = auth()->user();
  $saldoPenarikan = (int) data_get($user, 'saldo_penarikan', 0);
  $saldoHold = (int) data_get($user, 'saldo_hold', 0);
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Penarikan Saldo | Capital Wave</title>
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
      --green:#1c9d67; --green-soft:#e6f5ee; --red:#dc5757; --red-soft:#fdeaea; --amber:#c98a1e; --amber-soft:#fdf3e0;
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
    a{ color:inherit; text-decoration:none; } button,input{ font-family:inherit; }
    h1,h2,h3,p{ margin:0; }
    .wd-page{ width:100%; min-height:100vh; display:flex; justify-content:center; }
    .wd-phone{ width:100%; max-width:428px; min-height:100vh; padding:18px 16px 100px; }

    .wd-header{ display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:16px; }
    .wd-head-l{ display:flex; align-items:center; gap:11px; min-width:0; }
    .wd-back{ width:42px; height:42px; flex:0 0 auto; border:1px solid var(--line); background:var(--card); color:var(--navy); border-radius:13px; display:grid; place-items:center; box-shadow:var(--sh-sm); }
    .wd-back svg{ width:20px; height:20px; }
    .wd-head-l .t .name{ display:block; font-size:17px; font-weight:700; letter-spacing:-.02em; color:var(--navy); }
    .wd-head-l .t .tag{ display:block; margin-top:3px; font-size:10px; font-weight:600; letter-spacing:.14em; text-transform:uppercase; color:var(--muted); }
    .wd-header-btn{ width:42px; height:42px; flex:0 0 auto; border:1px solid var(--line); background:var(--card); color:var(--ink-soft); border-radius:13px; display:grid; place-items:center; box-shadow:var(--sh-sm); }
    .wd-header-btn svg{ width:19px; height:19px; }

    .wd-hero{ position:relative; overflow:hidden; border-radius:20px; padding:14px 16px; color:#fff;
      background:
        radial-gradient(420px 240px at 90% -20%, rgba(232,200,116,.18), transparent 62%),
        radial-gradient(360px 220px at 5% 120%, rgba(47,127,212,.22), transparent 60%),
        linear-gradient(150deg,#0f3255 0%,#0b2740 52%,#0a2036 100%);
      box-shadow:var(--sh-navy); }
    .wd-hero::before{ content:""; position:absolute; inset:0; border-radius:inherit; padding:1px; pointer-events:none;
      background:linear-gradient(150deg, rgba(232,200,116,.6), rgba(232,200,116,0) 34%, rgba(255,255,255,.14) 100%);
      -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; }
    .wd-hero > *{ position:relative; z-index:1; }
    .wd-eyebrow{ display:inline-flex; align-items:center; gap:8px; color:rgba(255,255,255,.62); font-size:9.5px; font-weight:600; letter-spacing:.18em; text-transform:uppercase; }
    .wd-eyebrow::before{ content:""; width:6px; height:6px; border-radius:999px; background:var(--gold-lite); box-shadow:0 0 10px rgba(232,200,116,.8); }
    .wd-hero-big{ margin-top:8px; font-size:25px; font-weight:700; letter-spacing:-.035em; line-height:1; }
    .wd-hero-boxes{ margin-top:11px; display:grid; grid-template-columns:1fr 1fr; gap:8px; }
    .wd-hbox{ border-radius:13px; padding:9px 11px; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.14); }
    .wd-hbox span{ display:flex; align-items:center; gap:6px; font-size:10px; font-weight:500; color:rgba(255,255,255,.6); }
    .wd-hbox span svg{ width:13px; height:13px; color:var(--gold-lite); }
    .wd-hbox strong{ display:block; margin-top:5px; font-size:13px; font-weight:700; letter-spacing:-.02em; }

    .wd-sec-label{ margin:18px 0 11px; font-size:13.5px; font-weight:700; color:var(--navy); letter-spacing:-.02em; }

    /* selected bank (JS rendered) */
    .wd-bank-card{ border-radius:18px; background:var(--card); border:1px solid var(--line); box-shadow:var(--sh); padding:16px; position:relative; overflow:hidden; }
    .wd-bank-card::before{ content:""; position:absolute; top:0; left:0; right:0; height:3px; background:var(--gold-metal); }
    .wd-bank-top{ display:flex; align-items:center; gap:12px; }
    .wd-bank-logo{ width:46px; height:46px; flex:0 0 auto; border-radius:13px; display:grid; place-items:center; overflow:hidden; background:var(--blue-soft); position:relative; }
    .wd-bank-logo-img{ width:100%; height:100%; object-fit:contain; padding:7px; background:#fff; }
    .wd-bank-logo-fallback{ display:none; font-size:13px; font-weight:700; color:var(--navy); }
    .wd-bank-logo-img + .wd-bank-logo-fallback{ position:absolute; inset:0; align-items:center; justify-content:center; }
    .wd-bank-label{ font-size:9px; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:var(--muted); }
    .wd-bank-name{ margin-top:3px; font-size:15px; font-weight:700; color:var(--navy); letter-spacing:-.02em; }
    .wd-bank-bottom{ margin-top:14px; display:grid; grid-template-columns:1fr 1fr; gap:12px; padding:13px 0; border-top:1px solid var(--line); border-bottom:1px solid var(--line); }
    .wd-bank-info span{ font-size:9.5px; font-weight:500; letter-spacing:.04em; text-transform:uppercase; color:var(--muted); }
    .wd-bank-info strong{ display:block; margin-top:5px; font-size:13px; font-weight:700; color:var(--navy); letter-spacing:.03em; }
    .wd-bank-change{ margin-top:12px; text-align:center; }
    .wd-bank-change a{ font-size:12px; font-weight:700; color:var(--blue); }
    .wd-bank-empty{ border-radius:18px; background:var(--card); border:1px dashed var(--line-2); padding:22px; text-align:center; }
    .wd-bank-empty-title{ font-size:14px; font-weight:700; color:var(--navy); }
    .wd-bank-empty-desc{ margin:8px 0 14px; font-size:12px; font-weight:500; color:var(--muted); line-height:1.5; }
    .wd-add-bank-link{ display:inline-flex; align-items:center; justify-content:center; min-height:46px; padding:0 20px; border-radius:13px; color:#fff; background:linear-gradient(135deg,#123457,#0b2740); box-shadow:0 10px 22px rgba(11,39,64,.24); font-size:13px; font-weight:700; }

    /* amount */
    .wd-amount-box{ display:flex; align-items:center; gap:10px; padding:16px 18px; border-radius:18px; background:var(--card); border:1.5px solid var(--line); box-shadow:var(--sh-sm); }
    .wd-amount-box:focus-within{ border-color:var(--blue); box-shadow:0 0 0 3px rgba(10,87,163,.1); }
    .wd-rp{ font-size:20px; font-weight:700; color:var(--muted); }
    .wd-amount-input{ flex:1; min-width:0; border:0; outline:none; background:transparent; color:var(--navy); font-size:30px; font-weight:700; letter-spacing:-.03em; }
    .wd-amount-input::placeholder{ color:var(--muted-2); }
    .wd-clear{ width:34px; height:34px; flex:0 0 auto; border:0; border-radius:10px; background:var(--tint); color:var(--muted); font-size:18px; cursor:pointer; display:grid; place-items:center; }
    .wd-presets{ margin-top:12px; display:grid; grid-template-columns:repeat(3,1fr); gap:9px; }
    .wd-preset{ min-height:44px; border:1px solid var(--line); border-radius:13px; background:var(--card); color:var(--ink-soft); font-size:12px; font-weight:700; cursor:pointer; box-shadow:var(--sh-sm); transition:.15s ease; }
    .wd-preset.is-active{ color:#fff; background:linear-gradient(135deg,#123457,#0b2740); border-color:transparent; box-shadow:0 8px 16px rgba(11,39,64,.2); }

    .wd-received{ margin-top:14px; display:flex; align-items:center; justify-content:space-between; gap:12px; padding:15px 16px; border-radius:16px; background:linear-gradient(135deg, var(--gold-soft), #fff); border:1px solid rgba(201,148,51,.2); }
    .wd-received-label{ display:block; font-size:10.5px; font-weight:600; color:var(--gold-deep); }
    .wd-received-amount{ display:block; margin-top:4px; font-size:19px; font-weight:700; color:var(--navy); letter-spacing:-.02em; }
    .wd-tax{ font-size:10px; font-weight:600; color:var(--muted); text-align:right; max-width:120px; line-height:1.4; }
    .wd-error{ margin-top:10px; font-size:11.5px; font-weight:600; color:var(--red); min-height:16px; }

    /* history */
    .wd-history{ margin-top:14px; display:flex; flex-direction:column; gap:9px; }
    .wd-empty{ padding:22px; border-radius:16px; background:var(--card); border:1px dashed var(--line-2); text-align:center; color:var(--muted); font-size:12.5px; font-weight:500; }
    .wd-history-item{ display:flex; align-items:center; justify-content:space-between; gap:10px; padding:14px; border-radius:16px; background:var(--card); border:1px solid var(--line); box-shadow:var(--sh-sm); }
    .wd-history-amount{ font-size:15px; font-weight:700; color:var(--navy); letter-spacing:-.02em; }
    .wd-history-date{ margin-top:4px; font-size:10.5px; font-weight:500; color:var(--muted); }
    .wd-status{ display:inline-block; padding:5px 10px; border-radius:9px; font-size:10px; font-weight:700; }
    .wd-status.is-paid{ color:var(--green); background:var(--green-soft); }
    .wd-status.is-processing{ color:var(--blue); background:var(--blue-soft); }
    .wd-status.is-rejected{ color:var(--red); background:var(--red-soft); }
    .wd-status.is-pending{ color:var(--amber); background:var(--amber-soft); }
    .wd-cancel{ display:block; margin-top:8px; margin-left:auto; padding:6px 12px; border:1px solid var(--red-soft); background:var(--red-soft); color:var(--red); border-radius:9px; font-size:11px; font-weight:700; cursor:pointer; }

    .wd-bottom{ position:fixed; left:50%; bottom:0; transform:translateX(-50%); width:min(100%,460px); padding:14px 16px calc(14px + env(safe-area-inset-bottom));
      background:linear-gradient(180deg, rgba(238,241,246,0), #eef1f6 40%); }
    .wd-submit{ position:relative; overflow:hidden; width:100%; min-height:54px; border:0; border-radius:16px; cursor:pointer; color:#fff;
      background:linear-gradient(135deg,#123457,#0b2740); box-shadow:0 12px 26px rgba(11,39,64,.3), inset 0 1px 0 rgba(255,255,255,.08); font-size:14.5px; font-weight:700; letter-spacing:-.01em; transition:.16s ease; }
    .wd-submit::after{ content:""; position:absolute; inset:0; border-radius:inherit; padding:1px; background:linear-gradient(135deg, rgba(232,200,116,.7), transparent 55%); -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; opacity:.6; }
    .wd-submit[disabled]{ opacity:.5; cursor:default; box-shadow:none; }

    .wd-toast{ position:fixed; left:50%; bottom:90px; transform:translateX(-50%) translateY(20px); z-index:2000; opacity:0; pointer-events:none;
      padding:13px 18px; border-radius:14px; background:var(--navy); color:#fff; box-shadow:var(--sh-lg); font-size:12.5px; font-weight:600; transition:.28s cubic-bezier(.22,.8,.22,1); }
    .wd-toast.show{ opacity:1; transform:translateX(-50%) translateY(0); }
    .wd-toast.is-error{ background:linear-gradient(135deg,#dc5757,#b83f3f); }
    @media (prefers-reduced-motion:reduce){ *,*::before,*::after{ animation:none !important; transition:none !important; } }
  </style>
</head>

<body>
  <main class="wd-page">
    <div class="wd-phone">

      <header class="wd-header">
        <div class="wd-head-l">
          <a href="{{ url('/dashboard') }}" class="wd-back" aria-label="Kembali"><svg viewBox="0 0 24 24" fill="none"><path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
          <div class="t"><span class="name">Penarikan</span><span class="tag">Cairkan Saldo</span></div>
        </div>
        <a href="{{ url('/ui/payout-accounts') }}" class="wd-header-btn" aria-label="Kelola Rekening"><svg viewBox="0 0 24 24" fill="none"><path d="M4 10 12 4l8 6" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M5 10v9h14v-9" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg></a>
      </header>

      <section class="wd-hero">
        <span class="wd-eyebrow">Saldo Siap Ditarik</span>
        <div class="wd-hero-big">Rp {{ number_format($saldoPenarikan, 0, ',', '.') }}</div>
        <div class="wd-hero-boxes">
          <div class="wd-hbox"><span><svg viewBox="0 0 24 24" fill="none"><path d="M12 8v4l3 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/></svg> Dalam Proses</span><strong>Rp {{ number_format($saldoHold, 0, ',', '.') }}</strong></div>
          <div class="wd-hbox"><span><svg viewBox="0 0 24 24" fill="none"><path d="M12 3 20 7v5c0 4.4-3.2 7.2-8 8.5C7.2 19.2 4 16.4 4 12V7l8-4Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg> Biaya Admin</span><strong>5%</strong></div>
        </div>
      </section>

      <p class="wd-sec-label">Rekening Tujuan</p>
      <div id="selectedBank"></div>

      <form id="wdForm" novalidate>
        <input type="hidden" id="payout" value="">
        <input type="hidden" id="amount" value="">

        <p class="wd-sec-label">Jumlah Penarikan</p>
        <div class="wd-amount-box">
          <span class="wd-rp">Rp</span>
          <input inputmode="numeric" autocomplete="off" id="amountDisplay" class="wd-amount-input" placeholder="0">
          <button type="button" class="wd-clear" id="clearAmount" aria-label="Hapus nominal">×</button>
        </div>

        <div class="wd-presets">
          <button type="button" class="wd-preset" data-amount="50000">Rp 50.000</button>
          <button type="button" class="wd-preset" data-amount="100000">Rp 100.000</button>
          <button type="button" class="wd-preset" data-amount="500000">Rp 500.000</button>
          <button type="button" class="wd-preset" data-amount="1000000">Rp 1.000.000</button>
          <button type="button" class="wd-preset" data-amount="5000000">Rp 5.000.000</button>
          <button type="button" class="wd-preset" data-amount="10000000">Rp 10.000.000</button>
        </div>

        <div class="wd-received">
          <div>
            <span class="wd-received-label">Kamu akan menerima</span>
            <span class="wd-received-amount" id="receivedAmount">Rp 0</span>
          </div>
          <span class="wd-tax" id="gatewayFeeText">Biaya mengikuti response gateway pembayaran</span>
        </div>

        <div class="wd-error" id="amountError"></div>
      </form>

      <p class="wd-sec-label">Riwayat Penarikan</p>
      <div class="wd-history" id="history"></div>
    </div>
  </main>

  <div class="wd-bottom">
    <button class="wd-submit" type="submit" form="wdForm" id="btnSubmitWd">Ajukan Penarikan</button>
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
      : 'Biaya mengikuti response gateway pembayaran';
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
