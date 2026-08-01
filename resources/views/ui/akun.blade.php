@include('partials.anti-inspect')
@php
  $user = auth()->user();

  $saldoUtama = (int) data_get($user, 'saldo', 0);
  $saldoBank  = (int) (data_get($user, 'saldo_penarikan') ?? data_get($user, 'withdraw_balance') ?? 0);
  $totalPorto = $saldoUtama + $saldoBank;

  $accountId = $user ? str_pad((string) $user->id, 12, '0', STR_PAD_LEFT) : '000000000000';
  $vipLevel  = (int) data_get($user, 'vip_level', 0);
  $initial   = mb_strtoupper(mb_substr(trim($user->name ?? 'U'), 0, 1));
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Akun Saya | Capital Wave</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --blue:#0A57A3; --blue-ink:#0b4a8c; --blue-lite:#2f7fd4; --blue-soft:#eef4fb;
      --navy:#0b2740; --navy-2:#0d3357; --navy-3:#0a2036;
      --gold:#c99433; --gold-lite:#e8c874; --gold-deep:#a9772a; --gold-soft:#faf3e2;
      --gold-metal:linear-gradient(135deg,#a9772a 0%,#e8c874 46%,#c99433 100%);
      --green:#1c9d67; --green-soft:#e6f5ee; --red:#dc5757; --red-soft:#fdeaea;
      --bg:#eef1f6; --card:#ffffff; --tint:#f5f8fc; --line:#e9edf4; --line-2:#dfe5ee;
      --ink:#152a3f; --ink-soft:#46586c; --muted:#8493a6; --muted-2:#aab6c4;
      --sh-sm:0 1px 2px rgba(11,39,64,.05);
      --sh:0 2px 6px rgba(11,39,64,.04), 0 12px 30px rgba(11,39,64,.07);
      --sh-lg:0 4px 10px rgba(11,39,64,.05), 0 22px 50px rgba(11,39,64,.12);
      --sh-navy:0 10px 24px rgba(9,30,52,.28), 0 24px 60px rgba(9,30,52,.30);
      --r:24px; --r-sm:16px;
    }
    *{ box-sizing:border-box; }
    html,body{ min-height:100%; }
    body{
      margin:0; color:var(--ink);
      font-family:'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background:
        radial-gradient(900px 500px at 50% -220px, rgba(10,87,163,.10), transparent 60%),
        radial-gradient(600px 400px at 100% 8%, rgba(201,148,51,.06), transparent 55%),
        linear-gradient(180deg,#f2f5f9 0%,#eef1f6 40%,#eaeef4 100%);
      background-attachment:fixed; overflow-x:hidden;
      -webkit-font-smoothing:antialiased; -webkit-tap-highlight-color:transparent; letter-spacing:-.01em;
    }
    a{ color:inherit; text-decoration:none; }
    button{ font-family:inherit; }
    h1,h2,h3,p{ margin:0; }
    .ak-page{ width:100%; min-height:100vh; display:flex; justify-content:center; }
    .ak-phone{ width:100%; max-width:428px; min-height:100vh; position:relative; padding:20px 16px 112px; }

    /* ===== PROFILE HEAD ===== */
    .ak-head{ display:flex; align-items:center; gap:13px; margin-bottom:16px; }
    .ak-avatar{ width:56px; height:56px; flex:0 0 auto; border-radius:18px; padding:2px; background:var(--gold-metal); display:grid; box-shadow:0 8px 20px rgba(201,148,51,.3); }
    .ak-avatar::after{ content:attr(data-i); grid-area:1/1; border-radius:16px; display:grid; place-items:center; color:#fff; font-size:22px; font-weight:700;
      background:linear-gradient(135deg, var(--navy), var(--blue)); }
    .ak-id{ min-width:0; flex:1; }
    .ak-id h1{ font-size:18px; font-weight:700; letter-spacing:-.02em; color:var(--navy); overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .ak-id-row{ margin-top:7px; display:inline-flex; align-items:center; gap:6px; padding:4px 9px; border-radius:999px; background:var(--card); border:1px solid var(--line); box-shadow:var(--sh-sm); }
    .ak-id-row b{ font-size:9px; font-weight:800; letter-spacing:.08em; color:var(--gold-deep); background:var(--gold-soft); padding:2px 6px; border-radius:6px; }
    .ak-id-row span{ font-size:11px; font-weight:600; color:var(--ink-soft); letter-spacing:.04em; }
    .ak-head-btn{ width:44px; height:44px; flex:0 0 auto; border:1px solid var(--line); background:var(--card); color:var(--ink-soft); border-radius:14px; display:grid; place-items:center; box-shadow:var(--sh-sm); cursor:pointer; transition:.16s ease; }
    .ak-head-btn:hover{ color:var(--gold-deep); border-color:rgba(201,148,51,.3); }
    .ak-head-btn svg{ width:20px; height:20px; }

    /* ===== PORTFOLIO CARD ===== */
    .ak-porto{ position:relative; overflow:hidden; border-radius:26px; padding:20px; color:#fff;
      background:
        radial-gradient(420px 240px at 88% -20%, rgba(232,200,116,.18), transparent 62%),
        radial-gradient(360px 220px at 8% 120%, rgba(47,127,212,.22), transparent 60%),
        linear-gradient(150deg,#0f3255 0%,#0b2740 52%,#0a2036 100%);
      box-shadow:var(--sh-navy); }
    .ak-porto::before{ content:""; position:absolute; inset:0; border-radius:inherit; padding:1px; pointer-events:none;
      background:linear-gradient(150deg, rgba(232,200,116,.6), rgba(232,200,116,0) 34%, rgba(255,255,255,.14) 100%);
      -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; }
    .ak-porto > *{ position:relative; z-index:1; }
    .ak-porto-top{ display:flex; align-items:center; justify-content:space-between; gap:12px; }
    .ak-eyebrow{ display:inline-flex; align-items:center; gap:8px; color:rgba(255,255,255,.62); font-size:9.5px; font-weight:600; letter-spacing:.2em; text-transform:uppercase; }
    .ak-eyebrow::before{ content:""; width:6px; height:6px; border-radius:999px; background:var(--green); box-shadow:0 0 10px rgba(28,157,103,.9); }
    .ak-vip{ display:inline-flex; align-items:center; gap:5px; padding:5px 11px; border-radius:999px; color:#0b2740; background:var(--gold-metal); font-size:10.5px; font-weight:800; box-shadow:0 6px 16px rgba(201,148,51,.34); }
    .ak-vip svg{ width:12px; height:12px; }
    .ak-balance{ margin-top:13px; color:#fff; font-size:33px; font-weight:700; letter-spacing:-.035em; line-height:1; text-shadow:0 8px 24px rgba(0,0,0,.28); }
    .ak-balance .rp{ font-size:16px; font-weight:600; color:rgba(255,255,255,.55); margin-right:4px; vertical-align:2px; }
    .ak-sub{ margin-top:16px; display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    .ak-sub-box{ border-radius:16px; padding:12px 13px; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.14); }
    .ak-sub-box span{ display:flex; align-items:center; gap:6px; color:rgba(255,255,255,.6); font-size:10.5px; font-weight:500; }
    .ak-sub-box span svg{ width:13px; height:13px; color:var(--gold-lite); }
    .ak-sub-box strong{ display:block; margin-top:7px; color:#fff; font-size:15px; font-weight:700; letter-spacing:-.02em; }
    .ak-sub-box strong .rp{ font-size:11px; color:rgba(255,255,255,.5); font-weight:500; margin-right:2px; }

    /* ===== QUICK GRID ===== */
    .ak-grid{ margin-top:14px; display:grid; grid-template-columns:1fr 1fr; gap:11px; }
    .ak-quick{ display:flex; align-items:center; gap:11px; padding:14px 13px; border-radius:18px; background:var(--card); border:1px solid var(--line); box-shadow:var(--sh-sm); transition:.16s ease; }
    .ak-quick:hover{ box-shadow:var(--sh); transform:translateY(-1px); }
    .ak-quick-ic{ width:42px; height:42px; flex:0 0 auto; border-radius:13px; display:grid; place-items:center; color:var(--blue); background:var(--blue-soft); }
    .ak-quick.gold .ak-quick-ic{ color:var(--gold-deep); background:var(--gold-soft); }
    .ak-quick-ic svg{ width:21px; height:21px; }
    .ak-quick-tx{ min-width:0; flex:1; }
    .ak-quick-tx b{ display:block; font-size:13.5px; font-weight:700; color:var(--navy); letter-spacing:-.01em; }
    .ak-quick-tx span{ display:block; margin-top:3px; font-size:10.5px; font-weight:500; color:var(--muted); }
    .ak-quick-ch{ color:var(--muted-2); flex:0 0 auto; }
    .ak-quick-ch svg{ width:16px; height:16px; }

    /* ===== MENU LIST ===== */
    .ak-menu{ margin-top:14px; border-radius:20px; background:var(--card); border:1px solid var(--line); box-shadow:var(--sh); overflow:hidden; }
    .ak-row{ display:flex; align-items:center; gap:13px; padding:15px 16px; cursor:pointer; transition:.14s ease; }
    .ak-row + .ak-row{ border-top:1px solid var(--line); }
    .ak-row:hover{ background:var(--tint); }
    .ak-row-ic{ width:40px; height:40px; flex:0 0 auto; border-radius:12px; display:grid; place-items:center; color:var(--blue); background:var(--blue-soft); }
    .ak-row-ic.gold{ color:var(--gold-deep); background:var(--gold-soft); }
    .ak-row-ic svg{ width:20px; height:20px; }
    .ak-row-tx{ min-width:0; flex:1; }
    .ak-row-tx b{ display:block; font-size:14px; font-weight:700; color:var(--navy); letter-spacing:-.01em; }
    .ak-row-tx span{ display:block; margin-top:3px; font-size:11px; font-weight:500; color:var(--muted); }
    .ak-row-ch{ color:var(--muted-2); flex:0 0 auto; }
    .ak-row-ch svg{ width:18px; height:18px; }

    /* ===== LOGOUT ===== */
    .ak-logout{ margin-top:16px; width:100%; display:flex; align-items:center; justify-content:center; gap:9px; min-height:52px; border:1px solid var(--red-soft); cursor:pointer;
      border-radius:16px; background:rgba(220,87,87,.06); color:var(--red); font-size:14px; font-weight:700; transition:.16s ease; }
    .ak-logout:hover{ background:rgba(220,87,87,.1); }
    .ak-logout svg{ width:18px; height:18px; }
    .ak-version{ margin-top:14px; text-align:center; color:var(--muted-2); font-size:10.5px; font-weight:600; letter-spacing:.04em; }

    /* toast */
    .ak-toast{ position:fixed; left:50%; bottom:120px; transform:translateX(-50%) translateY(20px); z-index:1000; opacity:0; pointer-events:none;
      display:flex; align-items:center; gap:9px; padding:12px 16px; border-radius:14px; background:var(--navy); color:#fff; box-shadow:var(--sh-lg);
      font-size:12.5px; font-weight:600; transition:.28s cubic-bezier(.22,.8,.22,1); }
    .ak-toast.show{ opacity:1; transform:translateX(-50%) translateY(0); }
    .ak-toast svg{ width:16px; height:16px; color:var(--gold-lite); }

    .rb-bottom-spacer{ height:94px; }

    @media (max-width:360px){
      .ak-phone{ padding:18px 12px 112px; }
      .ak-balance{ font-size:29px; }
      .ak-grid{ gap:9px; }
      .ak-quick{ padding:12px 11px; gap:9px; }
      .ak-quick-tx b{ font-size:12.5px; }
    }
    @media (prefers-reduced-motion:reduce){ *,*::before,*::after{ animation:none !important; transition:none !important; } }
  </style>
</head>

<body>
  <main class="ak-page">
    <div class="ak-phone">

      {{-- PROFILE HEAD --}}
      <header class="ak-head">
        <span class="ak-avatar" data-i="{{ $initial }}" aria-hidden="true"></span>
        <div class="ak-id">
          <h1>{{ $user->name ?: 'Capital Wave Member' }}</h1>
          <span class="ak-id-row"><b>ID</b><span>{{ $accountId }}</span></span>
        </div>
        <button type="button" class="ak-head-btn" id="akSoonBtn" aria-label="Tema">
          <svg viewBox="0 0 24 24" fill="none"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
        </button>
      </header>

      {{-- PORTFOLIO --}}
      <section class="ak-porto">
        <div class="ak-porto-top">
          <span class="ak-eyebrow">Total Portofolio</span>
          <span class="ak-vip">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="m12 2 2.9 6 6.1.9-4.5 4.3 1.1 6L12 16.9 6.4 19.2l1.1-6L3 8.9 9.1 8 12 2Z"/></svg>
            VIP {{ $vipLevel }}
          </span>
        </div>
        <h2 class="ak-balance"><span class="rp">Rp</span>{{ number_format($totalPorto, 0, ',', '.') }}</h2>
        <div class="ak-sub">
          <div class="ak-sub-box">
            <span><svg viewBox="0 0 24 24" fill="none"><rect x="3" y="6" width="18" height="13" rx="2.5" stroke="currentColor" stroke-width="1.8"/><path d="M3 10h18" stroke="currentColor" stroke-width="1.8"/></svg> Saldo Utama</span>
            <strong><span class="rp">Rp</span>{{ number_format($saldoUtama, 0, ',', '.') }}</strong>
          </div>
          <div class="ak-sub-box">
            <span><svg viewBox="0 0 24 24" fill="none"><path d="M4 10 12 4l8 6" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M5 10v9h14v-9" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg> Saldo Bank</span>
            <strong><span class="rp">Rp</span>{{ number_format($saldoBank, 0, ',', '.') }}</strong>
          </div>
        </div>
      </section>

      {{-- QUICK GRID --}}
      <section class="ak-grid">
        <a href="{{ route('saldo.rincian') }}" class="ak-quick">
          <span class="ak-quick-ic"><svg viewBox="0 0 24 24" fill="none"><path d="M7 4v13M7 17l-3-3M7 17l3-3M17 20V7M17 7l-3 3M17 7l3 3" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
          <span class="ak-quick-tx"><b>Mutasi</b><span>Semua transaksi</span></span>
          <span class="ak-quick-ch"><svg viewBox="0 0 24 24" fill="none"><path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        </a>
        <a href="{{ url('/deposit') }}" class="ak-quick gold">
          <span class="ak-quick-ic"><svg viewBox="0 0 24 24" fill="none"><path d="M12 4v11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M7 11l5 5 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 20h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></span>
          <span class="ak-quick-tx"><b>Deposit</b><span>Dana masuk</span></span>
          <span class="ak-quick-ch"><svg viewBox="0 0 24 24" fill="none"><path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        </a>
        <a href="{{ url('/ui/withdrawals') }}" class="ak-quick">
          <span class="ak-quick-ic"><svg viewBox="0 0 24 24" fill="none"><path d="M12 20V9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M7 13l5-5 5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 4h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></span>
          <span class="ak-quick-tx"><b>Penarikan</b><span>Dana keluar</span></span>
          <span class="ak-quick-ch"><svg viewBox="0 0 24 24" fill="none"><path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        </a>
        <a href="{{ url('/ui/payout-accounts') }}" class="ak-quick gold">
          <span class="ak-quick-ic"><svg viewBox="0 0 24 24" fill="none"><path d="M4 10 12 4l8 6" stroke="currentColor" stroke-width="1.9" stroke-linejoin="round"/><path d="M5 10v8h14v-8" stroke="currentColor" stroke-width="1.9" stroke-linejoin="round"/><path d="M9 18v-4h6v4" stroke="currentColor" stroke-width="1.9" stroke-linejoin="round"/></svg></span>
          <span class="ak-quick-tx"><b>Bank</b><span>Rekening saya</span></span>
          <span class="ak-quick-ch"><svg viewBox="0 0 24 24" fill="none"><path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        </a>
      </section>

      {{-- MENU LIST --}}
      <section class="ak-menu">
        <a href="{{ route('referral.index') }}" class="ak-row">
          <span class="ak-row-ic"><svg viewBox="0 0 24 24" fill="none"><circle cx="9" cy="8" r="3.2" stroke="currentColor" stroke-width="1.8"/><path d="M3.8 20a5.2 5.2 0 0 1 10.4 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M17 8h4M19 6v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
          <span class="ak-row-tx"><b>Undangan & Bonus</b><span>Ajak teman, dapat komisi</span></span>
          <span class="ak-row-ch"><svg viewBox="0 0 24 24" fill="none"><path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        </a>

        <a href="{{ route('investasi.index') }}" class="ak-row">
          <span class="ak-row-ic gold"><svg viewBox="0 0 24 24" fill="none"><path d="M4 19V5M8 17v-6M12 17V8M16 17v-3M20 17V6" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/></svg></span>
          <span class="ak-row-tx"><b>Portofolio Investasi</b><span>Paket aktif kamu</span></span>
          <span class="ak-row-ch"><svg viewBox="0 0 24 24" fill="none"><path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        </a>

        <button type="button" class="ak-row" id="akEditBtn" style="width:100%; text-align:left; border:0; background:transparent;">
          <span class="ak-row-ic"><svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="3.6" stroke="currentColor" stroke-width="1.8"/><path d="M5 20a7 7 0 0 1 14 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
          <span class="ak-row-tx"><b>Ubah Profil</b><span>Nama & foto profil</span></span>
          <span class="ak-row-ch"><svg viewBox="0 0 24 24" fill="none"><path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        </button>

        <button type="button" class="ak-row" id="akSecurityBtn" style="width:100%; text-align:left; border:0; background:transparent;">
          <span class="ak-row-ic"><svg viewBox="0 0 24 24" fill="none"><path d="M12 3 20 7v5c0 4.4-3.2 7.2-8 8.5C7.2 19.2 4 16.4 4 12V7l8-4Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="m8.5 12 2.2 2.2 4.8-4.8" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
          <span class="ak-row-tx"><b>Keamanan & Kata Sandi</b><span>Lindungi akun kamu</span></span>
          <span class="ak-row-ch"><svg viewBox="0 0 24 24" fill="none"><path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        </button>

        <a href="https://t.me/Capitalwavecs" target="_blank" rel="noopener noreferrer" class="ak-row">
          <span class="ak-row-ic gold"><svg viewBox="0 0 24 24" fill="none"><path d="M5 13a7 7 0 0 1 14 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><rect x="3.5" y="13" width="4" height="6.5" rx="2" stroke="currentColor" stroke-width="1.8"/><rect x="16.5" y="13" width="4" height="6.5" rx="2" stroke="currentColor" stroke-width="1.8"/></svg></span>
          <span class="ak-row-tx"><b>Pusat Bantuan</b><span>Support 24/7</span></span>
          <span class="ak-row-ch"><svg viewBox="0 0 24 24" fill="none"><path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        </a>

        <button type="button" class="ak-row" id="akApkBtn" style="width:100%; text-align:left; border:0; background:transparent;">
          <span class="ak-row-ic"><svg viewBox="0 0 24 24" fill="none"><path d="M12 4v10" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/><path d="M8 11l4 4 4-4" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 19h14" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/></svg></span>
          <span class="ak-row-tx"><b>Unduh Aplikasi (APK)</b><span>Versi terbaru</span></span>
          <span class="ak-row-ch"><svg viewBox="0 0 24 24" fill="none"><path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        </button>
      </section>

      {{-- LOGOUT --}}
      <form action="{{ url('/logout') }}" method="POST" style="margin:0;">
        @csrf
        <button type="submit" class="ak-logout" onclick="return confirm('Keluar dari akun?')">
          <svg viewBox="0 0 24 24" fill="none"><path d="M9 21H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 17l5-5-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          Keluar Akun
        </button>
      </form>

      <p class="ak-version">Capital Wave · v1.0.0</p>

      <div class="rb-bottom-spacer"></div>
      @include('partials.bottom-nav')
    </div>
  </main>

  <div class="ak-toast" id="akToast">
    <svg viewBox="0 0 24 24" fill="none"><path d="M12 8v5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="12" cy="16.5" r="1" fill="currentColor"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/></svg>
    <span id="akToastTx">Segera hadir</span>
  </div>

  <script>
    (function(){
      const toast = document.getElementById('akToast');
      const toastTx = document.getElementById('akToastTx');
      let t;
      function soon(msg){
        if(!toast) return;
        toastTx.textContent = msg || 'Fitur segera hadir';
        toast.classList.add('show');
        clearTimeout(t);
        t = setTimeout(() => toast.classList.remove('show'), 2200);
      }
      ['akSoonBtn','akEditBtn','akSecurityBtn','akApkBtn'].forEach(id => {
        const el = document.getElementById(id);
        if(el) el.addEventListener('click', () => soon('Fitur segera hadir'));
      });
    })();
  </script>
</body>
</html>
