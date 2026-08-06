@include('partials.anti-inspect')
@php
  $user = auth()->user();

  $deposits = $deposits ?? collect();

  $totalEntry = $deposits->count();

  $totalBerhasil = $deposits->filter(function($deposit){
      $status = strtolower((string) ($deposit->status ?? ''));
      return in_array($status, ['berhasil', 'success', 'sukses', 'paid', 'completed']);
  })->sum('amount');

  $totalMenunggu = $deposits->filter(function($deposit){
      $status = strtolower((string) ($deposit->status ?? ''));
      return !in_array($status, ['berhasil', 'success', 'sukses', 'paid', 'completed', 'failed', 'fail', 'gagal', 'cancelled', 'canceled', 'expired', 'rejected']);
  })->count();

  $paymentLogos = [
      'QRIS'      => asset('assets/payment-methods/qris.png'),
      'BRI'       => asset('assets/payment-methods/bri.png'),
      'DANA'      => asset('assets/payment-methods/dana.png'),
      'GOPAY'     => asset('assets/payment-methods/gopay.png'),
      'GO PAY'    => asset('assets/payment-methods/gopay.png'),
      'OVO'       => asset('assets/payment-methods/ovo.png'),
      'DOKU'      => asset('assets/payment-methods/doku.png'),
      'LINKAJA'   => asset('assets/payment-methods/linkaja.png'),
      'LINK AJA'  => asset('assets/payment-methods/linkaja.png'),
      'SHOPEEPAY' => asset('assets/payment-methods/shopeepay.png'),
      'SHOPEE PAY'=> asset('assets/payment-methods/shopeepay.png'),
      'BCA'       => asset('assets/payment-methods/bca.png'),
      'MANDIRI'   => asset('assets/payment-methods/mandiri.png'),
      'CASHIER'   => asset('assets/payment-methods/qris.png'),
  ];

  function vlDepositLogo($method, $paymentLogos){
      $raw = strtoupper(trim((string) $method));

      foreach($paymentLogos as $key => $logo){
          if(str_contains($raw, strtoupper($key))){
              return $logo;
          }
      }

      return asset('logo.png');
  }

  function vlDepositInitial($method){
      $method = strtoupper(trim((string) $method));
      $method = preg_replace('/[^A-Z0-9]/', '', $method);
      return substr($method ?: 'CW', 0, 4);
  }
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Riwayat Deposit | Capital Wave</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --blue:#0A57A3; --blue-lite:#2f7fd4; --blue-soft:#eaf2fb;
      --navy:#0b2740; --navy-3:#07182a;
      --gold:#c99433; --gold-lite:#e8c874; --gold-deep:#a9772a; --gold-soft:#f7efdd;
      --gold-metal:linear-gradient(135deg,#a9772a 0%,#e8c874 46%,#c99433 100%);
      --green:#1fb97a; --green-soft:#e7f7f0; --red:#dc5757; --red-soft:#fdeaea;
      --card:#ffffff; --tint:#f5f8fc; --line:#e9edf4; --line-2:#dfe5ee;
      --ink:#152a3f; --ink-soft:#46586c; --muted:#8493a6;
      --sh-sm:0 6px 16px rgba(11,39,64,.06);
      --sh:0 14px 34px rgba(11,39,64,.09);
      --sh-navy:0 22px 48px rgba(11,39,64,.28);
    }

    *{ box-sizing:border-box; }
    html,body{ min-height:100%; }

    body{
      margin:0;
      font-family:"Plus Jakarta Sans", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      color:var(--ink);
      background:
        radial-gradient(640px 340px at 50% -150px, rgba(47,127,212,.16), transparent 64%),
        radial-gradient(480px 320px at 100% 6%, rgba(232,200,116,.14), transparent 62%),
        linear-gradient(180deg,#ffffff 0%,#f4f8fc 46%,#eef3f9 100%);
      overflow-x:hidden;
      -webkit-tap-highlight-color:transparent;
      -webkit-font-smoothing:antialiased;
      letter-spacing:-.01em;
    }

    body::before{
      content:"";
      position:fixed;
      inset:0;
      pointer-events:none;
      background:
        linear-gradient(rgba(11,39,64,.022) 1px, transparent 1px),
        linear-gradient(90deg, rgba(11,39,64,.016) 1px, transparent 1px);
      background-size:32px 32px;
      mask-image:linear-gradient(180deg, rgba(0,0,0,.36), transparent 76%);
      -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.36), transparent 76%);
      opacity:.7;
      z-index:0;
    }

    a{ color:inherit; text-decoration:none; }
    button, select{ font-family:inherit; }

    .dh-page{
      width:100%;
      min-height:100vh;
      display:flex;
      justify-content:center;
      padding:14px 10px 0;
      position:relative;
      z-index:1;
    }

    .dh-phone{
      width:100%;
      max-width:430px;
      min-height:100vh;
      position:relative;
      padding:8px 4px 104px;
    }

    .dh-topbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
      padding:0 2px;
    }

    .dh-brand{ display:flex; align-items:center; gap:10px; min-width:0; }

    .dh-back,
    .dh-header-icon{
      width:42px;
      height:42px;
      border-radius:14px;
      border:1px solid var(--line);
      background:var(--card);
      color:var(--navy);
      display:grid;
      place-items:center;
      box-shadow:var(--sh-sm);
      cursor:pointer;
      flex:0 0 auto;
      transition:.18s ease;
    }

    .dh-back:hover,
    .dh-header-icon:hover{ transform:translateY(-1px); color:var(--blue); }
    .dh-back svg,
    .dh-header-icon svg{ width:20px; height:20px; }

    .dh-title{ min-width:0; }
    .dh-title h1{
      margin:0;
      color:var(--navy);
      font-size:22px;
      line-height:1;
      font-weight:800;
      letter-spacing:-.04em;
      white-space:nowrap;
    }
    .dh-title p{
      margin:6px 0 0;
      color:var(--ink-soft);
      font-size:11px;
      font-weight:500;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width:230px;
    }

    /* HERO */
    .dh-hero{
      position:relative;
      overflow:hidden;
      border-radius:26px;
      color:#fff;
      background:
        radial-gradient(420px 240px at 90% -20%, rgba(232,200,116,.20), transparent 62%),
        radial-gradient(360px 220px at 5% 120%, rgba(47,127,212,.24), transparent 60%),
        linear-gradient(150deg,#0f3255 0%,#0b2740 52%,#07182a 100%);
      box-shadow:var(--sh-navy);
      padding:18px;
      animation:dhFadeUp .42s ease both;
    }

    .dh-hero::after{
      content:"";
      position:absolute;
      inset:0;
      border-radius:inherit;
      padding:1px;
      pointer-events:none;
      background:linear-gradient(150deg, rgba(232,200,116,.6), rgba(232,200,116,0) 34%, rgba(255,255,255,.14) 100%);
      -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
      -webkit-mask-composite:xor; mask-composite:exclude;
    }

    .dh-hero-inner{
      position:relative;
      z-index:1;
      display:grid;
      grid-template-columns:minmax(0,1fr) auto;
      gap:14px;
      align-items:flex-start;
    }

    .dh-hero-label{
      margin:0 0 8px;
      color:rgba(255,255,255,.62);
      font-size:11px;
      font-weight:500;
      letter-spacing:.02em;
    }

    .dh-hero-total{
      margin:0;
      color:#fff;
      font-size:29px;
      line-height:1.02;
      letter-spacing:-.045em;
      font-weight:700;
      text-shadow:0 12px 24px rgba(3,12,22,.28);
    }

    .dh-hero-sub{
      margin:12px 0 0;
      display:inline-flex;
      align-items:center;
      gap:7px;
      color:var(--navy);
      font-size:11px;
      font-weight:700;
      padding:7px 12px;
      border-radius:999px;
      background:var(--gold-metal);
      box-shadow:0 10px 20px rgba(201,148,51,.22);
    }

    .dh-hero-badge{
      min-width:82px;
      height:38px;
      padding:0 14px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      color:var(--gold-lite);
      background:rgba(255,255,255,.08);
      border:1px solid rgba(232,200,116,.32);
      font-size:11px;
      font-weight:700;
      white-space:nowrap;
    }

    /* FILTER */
    .dh-filter-card{
      margin-top:14px;
      position:relative;
      z-index:2;
      animation:dhFadeUp .42s ease both;
    }

    .dh-filter-grid{ display:grid; grid-template-columns:1fr 1fr; gap:9px; }

    .dh-select{
      width:100%;
      height:44px;
      border-radius:13px;
      border:1px solid var(--line);
      outline:0;
      background:var(--card);
      color:var(--navy);
      padding:0 38px 0 14px;
      font-size:11.5px;
      font-weight:600;
      appearance:none;
      background-image:url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='rgba(11,39,64,.55)' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='m6 9 6 6 6-6'/%3e%3c/svg%3e");
      background-repeat:no-repeat;
      background-position:right 14px center;
      background-size:16px;
      box-shadow:var(--sh-sm);
    }

    .dh-select option{ background:#fff; color:var(--navy); }

    /* LIST */
    .dh-list{ margin-top:12px; display:flex; flex-direction:column; gap:11px; }

    .dh-card{
      position:relative;
      overflow:hidden;
      border-radius:20px;
      background:radial-gradient(230px 130px at 96% -6%, var(--card-glow), transparent 64%), var(--card);
      border:1px solid var(--line);
      box-shadow:var(--sh-sm);
      animation:dhFadeUp .42s ease both;
      transition:.18s ease;
    }

    .dh-card::before{
      content:"";
      position:absolute;
      top:0; left:0; right:0; height:3px;
      background:var(--accent-bar);
    }

    .dh-card:hover{
      transform:translateY(-1px);
      border-color:var(--line-2);
      box-shadow:var(--sh);
    }

    .dh-card[data-status="success"]{ --card-glow:rgba(31,185,122,.10); --accent:#1fb97a; --accent-bar:linear-gradient(90deg,#1fb97a,#3fd39a); }
    .dh-card[data-status="pending"]{ --card-glow:rgba(232,200,116,.14); --accent:#a9772a; --accent-bar:var(--gold-metal); }
    .dh-card[data-status="failed"]{  --card-glow:rgba(220,87,87,.10);  --accent:#dc5757; --accent-bar:linear-gradient(90deg,#dc5757,#ec8080); }

    .dh-card-top{
      position:relative;
      z-index:1;
      padding:13px;
      display:grid;
      grid-template-columns:minmax(0,1fr) auto;
      align-items:start;
      gap:12px;
    }

    .dh-bank{ display:flex; align-items:center; gap:11px; min-width:0; }

    .dh-bank-logo{
      width:48px;
      height:48px;
      border-radius:13px;
      display:grid;
      place-items:center;
      color:var(--navy);
      background:var(--blue-soft);
      border:1px solid var(--line);
      overflow:hidden;
      flex:0 0 auto;
      font-size:10px;
      font-weight:800;
    }

    .dh-bank-logo img{ width:100%; height:100%; object-fit:contain; padding:8px; background:#fff; display:block; }
    .dh-bank-logo-fallback{ display:none; }

    .dh-bank-name{
      color:var(--navy);
      font-size:14px;
      line-height:1.15;
      font-weight:700;
      letter-spacing:-.02em;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width:178px;
    }

    .dh-bank-number{
      margin-top:5px;
      color:var(--muted);
      font-size:10.5px;
      font-weight:500;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width:190px;
    }

    .dh-status{
      min-height:28px;
      padding:0 11px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:6px;
      font-size:10.5px;
      font-weight:700;
      white-space:nowrap;
      flex:0 0 auto;
      border:1px solid transparent;
    }

    .dh-status::before{ content:""; width:6px; height:6px; border-radius:999px; background:currentColor; }
    .dh-status.is-success{ color:#0d8a54; background:var(--green-soft); border-color:rgba(31,185,122,.20); }
    .dh-status.is-pending{ color:var(--gold-deep); background:var(--gold-soft); border-color:rgba(201,148,51,.24); }
    .dh-status.is-failed{ color:#b53a3a; background:var(--red-soft); border-color:rgba(220,87,87,.20); }

    .dh-card-body{
      position:relative;
      z-index:1;
      margin:0 13px 13px;
      border-radius:14px;
      background:var(--tint);
      border:1px solid var(--line);
      padding:11px 12px;
      display:grid;
      gap:10px;
    }

    .dh-row{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      color:var(--ink-soft);
      font-size:11px;
      font-weight:500;
    }

    .dh-row strong{
      color:var(--navy);
      font-size:12px;
      font-weight:700;
      white-space:nowrap;
      text-align:right;
    }

    .dh-row.is-total{ padding-top:10px; border-top:1px solid var(--line); }
    .dh-row.is-total span{ color:var(--navy); font-weight:700; }
    .dh-row.is-total strong{ color:var(--accent); font-size:15px; letter-spacing:-.03em; }

    .dh-date{
      position:relative;
      z-index:1;
      padding:0 13px 13px;
      color:var(--muted);
      font-size:10.5px;
      font-weight:500;
      display:flex;
      align-items:center;
      gap:7px;
    }

    .dh-date svg{ width:14px; height:14px; opacity:.78; }

    .dh-invoice-btn{
      margin-left:auto;
      min-height:30px;
      padding:0 13px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      color:#fff;
      background:linear-gradient(135deg,#123457,#0b2740);
      box-shadow:0 8px 18px rgba(11,39,64,.2);
      font-size:10.5px;
      font-weight:700;
      white-space:nowrap;
    }

    .dh-empty{
      min-height:180px;
      border-radius:18px;
      border:1px dashed var(--line-2);
      background:var(--card);
      color:var(--ink-soft);
      display:flex;
      align-items:center;
      justify-content:center;
      text-align:center;
      padding:18px;
      font-size:12.5px;
      font-weight:600;
      box-shadow:var(--sh-sm);
    }

    .dh-bottom-actions{
      position:fixed;
      left:50%;
      bottom:0;
      transform:translateX(-50%);
      z-index:50;
      width:min(100%,430px);
      padding:12px 14px calc(14px + env(safe-area-inset-bottom));
      background:linear-gradient(180deg, rgba(238,243,249,0), rgba(238,243,249,.92) 26%, rgba(238,243,249,.98));
      pointer-events:none;
    }

    .dh-main-btn{
      width:100%;
      min-height:50px;
      border:0;
      border-radius:14px;
      color:#fff;
      background:linear-gradient(135deg,#123457,#0b2740);
      box-shadow:0 14px 30px rgba(11,39,64,.24);
      font-size:13.5px;
      font-weight:700;
      cursor:pointer;
      pointer-events:auto;
      display:flex;
      align-items:center;
      justify-content:center;
      gap:8px;
    }

    @keyframes dhFadeUp{ from{ opacity:0; transform:translateY(10px); } to{ opacity:1; transform:translateY(0); } }

    @media(min-width:768px){
      .dh-page{ padding:22px 0; }
      .dh-phone{ min-height:calc(100vh - 44px); border-radius:26px; overflow:hidden; }
      .dh-bottom-actions{ bottom:22px; border-radius:0 0 26px 26px; }
    }

    @media(max-width:370px){
      .dh-page{ padding-left:8px; padding-right:8px; }
      .dh-title h1{ font-size:20px; }
      .dh-title p{ max-width:190px; }
      .dh-filter-grid{ grid-template-columns:1fr; }
      .dh-hero-total{ font-size:26px; }
      .dh-hero-inner{ grid-template-columns:1fr; }
      .dh-hero-badge{ width:max-content; }
      .dh-bank-name{ max-width:130px; }
      .dh-bank-number{ max-width:140px; }
      .dh-card-top{ grid-template-columns:1fr; }
      .dh-status{ width:max-content; }
    }
  </style>
</head>

<body>
  <main class="dh-page">
    <div class="dh-phone">

      <header class="dh-topbar">
        <div class="dh-brand">
          <button type="button" class="dh-back" onclick="goBack()" aria-label="Kembali">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M15 18 9 12l6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>

          <div class="dh-title">
            <h1>Riwayat Deposit</h1>
            <p>Transaksi pengisian saldo Capital Wave</p>
          </div>
        </div>

        <a href="{{ url('/deposit') }}" class="dh-header-icon" aria-label="Deposit Baru">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M12 5v14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
            <path d="M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
          </svg>
        </a>
      </header>

      <section class="dh-hero">
        <div class="dh-hero-inner">
          <div>
            <p class="dh-hero-label">Total Deposit Berhasil</p>
            <h2 class="dh-hero-total">Rp {{ number_format($totalBerhasil, 0, ',', '.') }}</h2>
            <p class="dh-hero-sub">{{ $totalEntry }} transaksi tersimpan</p>
          </div>

          <div class="dh-hero-badge">
            {{ $totalMenunggu }} Pending
          </div>
        </div>
      </section>

      <section class="dh-filter-card">
        <div class="dh-filter-grid">
          <select id="monthFilter" class="dh-select" aria-label="Filter bulan">
            <option value="">Semua bulan</option>
          </select>

          <select id="statusFilter" class="dh-select" aria-label="Filter status">
            <option value="">Semua status</option>
            <option value="success">Berhasil</option>
            <option value="pending">Menunggu</option>
            <option value="failed">Gagal</option>
          </select>
        </div>
      </section>

      <section id="depositHistoryList" class="dh-list">
        @forelse($deposits as $deposit)
          @php
            $rawStatus = strtolower((string) data_get($deposit, 'status', 'pending'));

            if(in_array($rawStatus, ['berhasil', 'success', 'sukses', 'paid', 'completed'])) {
                $statusKey = 'success';
                $statusText = 'Berhasil';
            } elseif(in_array($rawStatus, ['failed', 'fail', 'gagal', 'cancelled', 'canceled', 'expired', 'rejected'])) {
                $statusKey = 'failed';
                $statusText = 'Gagal';
            } else {
                $statusKey = 'pending';
                $statusText = 'Menunggu';
            }

            $orderId = data_get($deposit, 'order_id')
              ?? data_get($deposit, 'merchant_order_id')
              ?? data_get($deposit, 'reference')
              ?? ('DEP-'.$deposit->id);

            $method = data_get($deposit, 'selected_channel')
              ?? data_get($deposit, 'method')
              ?? data_get($deposit, 'payment_method')
              ?? 'QRIS / E-Wallet';

            $amount = (int) data_get($deposit, 'amount', 0);
            $realAmount = (int) (data_get($deposit, 'real_amount') ?: $amount);
            $feeAmount = max(0, $realAmount - $amount);

            $dateValue = optional($deposit->created_at)->format('Y-m-d');
            $monthValue = optional($deposit->created_at)->format('Y-m');
            $logoSrc = vlDepositLogo($method, $paymentLogos);
            $fallbackText = vlDepositInitial($method);
          @endphp

          <article
            class="dh-card js-deposit-row"
            data-status="{{ $statusKey }}"
            data-month="{{ $monthValue }}"
            style="animation-delay: {{ $loop->index * 0.045 }}s;"
          >
            <div class="dh-card-top">
              <div class="dh-bank">
                <div class="dh-bank-logo">
                  <img
                    src="{{ $logoSrc }}"
                    alt="{{ $method }}"
                    loading="lazy"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                  >
                  <span class="dh-bank-logo-fallback">{{ $fallbackText }}</span>
                </div>

                <div>
                  <div class="dh-bank-name">{{ $method }}</div>
                  <div class="dh-bank-number">#{{ $orderId }}</div>
                </div>
              </div>

              <div class="dh-status is-{{ $statusKey }}">
                {{ $statusText }}
              </div>
            </div>

            <div class="dh-card-body">
              <div class="dh-row">
                <span>Nominal</span>
                <strong>Rp {{ number_format($amount, 0, ',', '.') }}</strong>
              </div>

              <div class="dh-row">
                <span>Biaya</span>
                <strong>Rp {{ number_format($feeAmount, 0, ',', '.') }}</strong>
              </div>

              <div class="dh-row is-total">
                <span>Total Bayar</span>
                <strong>Rp {{ number_format($realAmount, 0, ',', '.') }}</strong>
              </div>
            </div>

            <div class="dh-date">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M7 2v3M17 2v3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <rect x="3" y="5" width="18" height="16" rx="4" stroke="currentColor" stroke-width="2"/>
                <path d="M3 9h18" stroke="currentColor" stroke-width="2"/>
              </svg>

              {{ optional($deposit->created_at)->format('d M Y, H:i') ?? '-' }}

              @if($statusKey === 'pending')
                <a href="{{ route('deposit.invoice', $deposit->id) }}" class="dh-invoice-btn">
                  Lanjutkan
                </a>
              @else
                <a href="{{ route('deposit.invoice', $deposit->id) }}" class="dh-invoice-btn">
                  Detail
                </a>
              @endif
            </div>
          </article>
        @empty
          <div class="dh-empty">
            Belum ada riwayat deposit.
          </div>
        @endforelse
      </section>

    </div>
  </main>

  <div class="dh-bottom-actions">
    <a href="{{ url('/deposit') }}" class="dh-main-btn">
      Deposit Baru
      <span>↗</span>
    </a>
  </div>

  <script>
    function goBack(){
      if(window.history.length > 1){
        window.history.back();
        return;
      }

      window.location.href = '/akun';
    }

    (function(){
      const rows = Array.from(document.querySelectorAll('.js-deposit-row'));
      const monthFilter = document.getElementById('monthFilter');
      const statusFilter = document.getElementById('statusFilter');
      const list = document.getElementById('depositHistoryList');

      if(!rows.length || !monthFilter || !statusFilter) return;

      const monthNames = {
        '01':'Januari',
        '02':'Februari',
        '03':'Maret',
        '04':'April',
        '05':'Mei',
        '06':'Juni',
        '07':'Juli',
        '08':'Agustus',
        '09':'September',
        '10':'Oktober',
        '11':'November',
        '12':'Desember'
      };

      const months = [...new Set(rows.map(row => row.dataset.month).filter(Boolean))];

      months.sort().reverse().forEach(month => {
        const [year, monthNum] = month.split('-');
        const option = document.createElement('option');
        option.value = month;
        option.textContent = `${monthNames[monthNum] || monthNum} ${year}`;
        monthFilter.appendChild(option);
      });

      function applyFilter(){
        const selectedMonth = monthFilter.value;
        const selectedStatus = statusFilter.value;

        let visibleCount = 0;

        rows.forEach(row => {
          const matchMonth = !selectedMonth || row.dataset.month === selectedMonth;
          const matchStatus = !selectedStatus || row.dataset.status === selectedStatus;
          const show = matchMonth && matchStatus;

          row.style.display = show ? '' : 'none';

          if(show) visibleCount++;
        });

        let emptyFiltered = document.getElementById('depositEmptyFiltered');

        if(!visibleCount){
          if(!emptyFiltered){
            emptyFiltered = document.createElement('div');
            emptyFiltered.id = 'depositEmptyFiltered';
            emptyFiltered.className = 'dh-empty';
            emptyFiltered.textContent = 'Tidak ada riwayat deposit sesuai filter.';
            list.appendChild(emptyFiltered);
          }
        }else if(emptyFiltered){
          emptyFiltered.remove();
        }
      }

      monthFilter.addEventListener('change', applyFilter);
      statusFilter.addEventListener('change', applyFilter);
    })();
  </script>
</body>
</html>
