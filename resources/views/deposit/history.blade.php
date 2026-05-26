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
      return substr($method ?: 'VL', 0, 4);
  }
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Riwayat Deposit | Velora Finance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,500;1,700&display=swap" rel="stylesheet">

  <style>
    :root{
      --vl-bg:#f6f2f8;
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
      --vl-violet:#b45cff;
      --vl-pink:#d96bff;
      --vl-green:#20b873;
      --vl-red:#e24a64;
      --vl-amber:#f5af2a;
      --vl-gradient:linear-gradient(135deg,#f5af2a 0%,#ffd46d 25%,#d96bff 58%,#8f57ff 100%);
      --vl-hero:linear-gradient(145deg,#8f57ff 0%,#9f55ff 42%,#d96bff 72%,#f5af2a 100%);
      --vl-shadow:0 26px 68px rgba(88,43,145,.16);
      --vl-shadow-soft:0 14px 36px rgba(43,11,22,.075);
    }

    *{ box-sizing:border-box; }

    html,
    body{ min-height:100%; }

    body{
      margin:0;
      font-family:"Plus Jakarta Sans", Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      color:var(--vl-text);
      background:
        radial-gradient(680px 360px at 50% -150px, rgba(245,175,42,.22), transparent 64%),
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
      mask-image:linear-gradient(180deg, rgba(0,0,0,.42), transparent 76%);
      -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.42), transparent 76%);
      opacity:.52;
      z-index:0;
    }

    a{ color:inherit; text-decoration:none; }
    button,
    select{ font-family:inherit; }

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

    .dh-brand{
      display:flex;
      align-items:center;
      gap:10px;
      min-width:0;
    }

    .dh-back,
    .dh-header-icon{
      width:42px;
      height:42px;
      border-radius:999px;
      border:1px solid rgba(43,11,22,.075);
      background:rgba(255,255,255,.88);
      color:#5b2841;
      display:grid;
      place-items:center;
      box-shadow:0 12px 26px rgba(43,11,22,.065), inset 0 1px 0 rgba(255,255,255,.92);
      cursor:pointer;
      flex:0 0 auto;
      backdrop-filter:blur(18px);
      -webkit-backdrop-filter:blur(18px);
      transition:.18s ease;
    }

    .dh-back:hover,
    .dh-header-icon:hover{
      transform:translateY(-1px);
      color:var(--vl-purple);
    }

    .dh-back svg,
    .dh-header-icon svg{
      width:20px;
      height:20px;
    }

    .dh-logo-mini{
      width:42px;
      height:42px;
      border-radius:16px;
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

    .dh-logo-mini img{
      width:36px;
      height:36px;
      object-fit:contain;
      display:block;
    }

    .dh-title{
      min-width:0;
    }

    .dh-title h1{
      margin:0;
      color:var(--vl-maroon);
      font-size:22px;
      line-height:1;
      font-weight:800;
      letter-spacing:-.052em;
      white-space:nowrap;
    }

    .dh-title p{
      margin:6px 0 0;
      color:var(--vl-soft);
      font-size:11px;
      font-weight:650;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width:230px;
    }

    .dh-hero{
      position:relative;
      overflow:hidden;
      border-radius:30px;
      background:
        radial-gradient(360px 220px at 92% -12%, rgba(255,212,109,.48), transparent 58%),
        radial-gradient(300px 200px at 2% 8%, rgba(217,107,255,.34), transparent 62%),
        var(--vl-hero);
      color:#fff;
      border:1px solid rgba(255,255,255,.44);
      box-shadow:0 28px 62px rgba(143,87,255,.22), 0 18px 42px rgba(245,175,42,.10), inset 0 1px 0 rgba(255,255,255,.22);
      padding:18px;
      animation:dhFadeUp .42s ease both;
    }

    .dh-hero::before{
      content:"";
      position:absolute;
      inset:0;
      pointer-events:none;
      background:
        linear-gradient(135deg, rgba(255,255,255,.22), transparent 34%),
        radial-gradient(circle at 82% 26%, rgba(255,255,255,.16), transparent 28%),
        linear-gradient(180deg, transparent 0%, rgba(43,11,22,.08) 100%);
    }

    .dh-hero::after{
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
      color:rgba(255,255,255,.74);
      font-size:12px;
      font-weight:700;
    }

    .dh-hero-total{
      margin:0;
      color:#ffffff;
      font-size:30px;
      line-height:1.02;
      letter-spacing:-.075em;
      font-weight:800;
      text-shadow:0 12px 24px rgba(43,11,22,.22);
    }

    .dh-hero-sub{
      margin:12px 0 0;
      display:inline-flex;
      align-items:center;
      gap:7px;
      color:#2c1200;
      font-size:11.5px;
      font-weight:800;
      padding:8px 12px;
      border-radius:999px;
      background:linear-gradient(135deg,#ffe08a 0%,#f5af2a 100%);
      border:1px solid rgba(255,255,255,.30);
      box-shadow:0 12px 22px rgba(245,175,42,.24), inset 0 1px 0 rgba(255,255,255,.38);
    }

    .dh-hero-badge{
      min-width:82px;
      height:42px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      color:#2b0b16;
      background:
        radial-gradient(circle at 24% 0%, rgba(255,255,255,.72), transparent 40%),
        linear-gradient(135deg,#ffd46d 0%,#d96bff 56%,#8f57ff 100%);
      border:1px solid rgba(255,255,255,.40);
      box-shadow:0 12px 24px rgba(143,87,255,.18), inset 0 1px 0 rgba(255,255,255,.40);
      font-size:12px;
      font-weight:800;
      white-space:nowrap;
    }

    .dh-filter-card{
      margin-top:14px;
      position:relative;
      z-index:2;
      animation:dhFadeUp .42s ease both;
    }

    .dh-filter-grid{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:9px;
    }

    .dh-select{
      width:100%;
      height:44px;
      border-radius:999px;
      border:1px solid rgba(43,11,22,.075);
      outline:0;
      background:rgba(255,255,255,.92);
      color:var(--vl-maroon);
      padding:0 38px 0 14px;
      font-size:11.5px;
      font-weight:800;
      appearance:none;
      background-image:url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='rgba(43,11,22,.70)' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='m6 9 6 6 6-6'/%3e%3c/svg%3e");
      background-repeat:no-repeat;
      background-position:right 14px center;
      background-size:16px;
      box-shadow:0 10px 22px rgba(43,11,22,.055), inset 0 1px 0 rgba(255,255,255,.9);
    }

    .dh-select option{
      background:#ffffff;
      color:var(--vl-maroon);
    }

    .dh-list{
      margin-top:12px;
      display:flex;
      flex-direction:column;
      gap:11px;
    }

    .dh-card{
      position:relative;
      overflow:hidden;
      border-radius:26px;
      background:
        radial-gradient(230px 130px at 96% -6%, var(--card-glow), transparent 64%),
        linear-gradient(180deg,rgba(255,255,255,.98),rgba(255,255,255,.91));
      border:1px solid rgba(43,11,22,.075);
      box-shadow:0 14px 34px rgba(43,11,22,.07), inset 0 1px 0 rgba(255,255,255,.94);
      animation:dhFadeUp .42s ease both;
      transition:.18s ease;
    }

    .dh-card::before{
      content:"";
      position:absolute;
      inset:0;
      pointer-events:none;
      background:
        linear-gradient(135deg, rgba(255,255,255,.82), transparent 30%),
        radial-gradient(circle at 12% 0%, var(--card-soft), transparent 44%);
      opacity:.86;
    }

    .dh-card:hover{
      transform:translateY(-1px);
      border-color:rgba(143,87,255,.16);
      box-shadow:0 18px 38px rgba(43,11,22,.095), 0 0 0 4px rgba(143,87,255,.04);
    }

    .dh-card[data-status="success"]{
      --card-glow:rgba(32,184,115,.13);
      --card-soft:rgba(32,184,115,.08);
      --accent:#20b873;
    }

    .dh-card[data-status="pending"]{
      --card-glow:rgba(245,175,42,.15);
      --card-soft:rgba(245,175,42,.08);
      --accent:#f5af2a;
    }

    .dh-card[data-status="failed"]{
      --card-glow:rgba(226,74,100,.13);
      --card-soft:rgba(226,74,100,.08);
      --accent:#e24a64;
    }

    .dh-card-top{
      position:relative;
      z-index:1;
      padding:13px;
      display:grid;
      grid-template-columns:minmax(0,1fr) auto;
      align-items:start;
      gap:12px;
    }

    .dh-bank{
      display:flex;
      align-items:center;
      gap:11px;
      min-width:0;
    }

    .dh-bank-logo{
      width:50px;
      height:50px;
      border-radius:18px;
      display:grid;
      place-items:center;
      color:#210812;
      background:#ffffff;
      border:1px solid rgba(43,11,22,.075);
      box-shadow:0 12px 24px rgba(43,11,22,.08), inset 0 1px 0 rgba(255,255,255,.90);
      overflow:hidden;
      flex:0 0 auto;
      font-size:10px;
      font-weight:900;
    }

    .dh-bank-logo img{
      width:40px;
      height:34px;
      object-fit:contain;
      display:block;
    }

    .dh-bank-logo-fallback{
      display:none;
    }

    .dh-bank-name{
      color:var(--vl-maroon);
      font-size:14px;
      line-height:1.15;
      font-weight:800;
      letter-spacing:-.025em;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width:178px;
    }

    .dh-bank-number{
      margin-top:5px;
      color:var(--vl-soft);
      font-size:10.7px;
      font-weight:700;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width:190px;
    }

    .dh-status{
      min-height:29px;
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

    .dh-status::before{
      content:"";
      width:6px;
      height:6px;
      border-radius:999px;
      background:currentColor;
    }

    .dh-status.is-success{
      color:#13945c;
      background:#e8fff4;
      border-color:rgba(32,184,115,.16);
    }

    .dh-status.is-pending{
      color:#9a6700;
      background:#fff7df;
      border-color:rgba(245,175,42,.20);
    }

    .dh-status.is-failed{
      color:#d7495c;
      background:#fff1f3;
      border-color:rgba(226,74,100,.18);
    }

    .dh-card-body{
      position:relative;
      z-index:1;
      margin:0 13px 13px;
      border-radius:20px;
      background:rgba(251,248,255,.78);
      border:1px solid rgba(43,11,22,.06);
      padding:11px 12px;
      display:grid;
      gap:10px;
    }

    .dh-row{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      color:var(--vl-soft);
      font-size:11.2px;
      font-weight:650;
    }

    .dh-row strong{
      color:var(--vl-maroon);
      font-size:12px;
      font-weight:800;
      white-space:nowrap;
      text-align:right;
    }

    .dh-row.is-total{
      padding-top:10px;
      border-top:1px solid rgba(43,11,22,.065);
    }

    .dh-row.is-total span{
      color:var(--vl-maroon);
      font-weight:800;
    }

    .dh-row.is-total strong{
      color:var(--accent);
      font-size:15px;
      letter-spacing:-.035em;
    }

    .dh-date{
      position:relative;
      z-index:1;
      padding:0 13px 13px;
      color:var(--vl-soft);
      font-size:10.7px;
      font-weight:700;
      display:flex;
      align-items:center;
      gap:7px;
    }

    .dh-date svg{
      width:14px;
      height:14px;
      opacity:.78;
    }

    .dh-invoice-btn{
      margin-left:auto;
      min-height:31px;
      padding:0 11px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      color:#2c1200;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.55), transparent 34%),
        linear-gradient(135deg,#ffd46d 0%,#d96bff 60%,#8f57ff 100%);
      box-shadow:0 12px 24px rgba(143,87,255,.15);
      font-size:10.5px;
      font-weight:800;
      white-space:nowrap;
    }

    .dh-empty{
      min-height:180px;
      border-radius:24px;
      border:1px dashed rgba(43,11,22,.18);
      background:rgba(255,255,255,.92);
      color:var(--vl-soft);
      display:flex;
      align-items:center;
      justify-content:center;
      text-align:center;
      padding:18px;
      font-size:12.5px;
      font-weight:750;
      box-shadow:var(--vl-shadow-soft);
    }

    .dh-bottom-actions{
      position:fixed;
      left:50%;
      bottom:0;
      transform:translateX(-50%);
      z-index:50;
      width:min(100%,430px);
      padding:12px 14px calc(14px + env(safe-area-inset-bottom));
      background:linear-gradient(180deg, rgba(246,242,248,0), rgba(246,242,248,.92) 26%, rgba(246,242,248,.98));
      pointer-events:none;
    }

    .dh-main-btn{
      width:100%;
      min-height:50px;
      border:0;
      border-radius:999px;
      color:#2c1200;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.55), transparent 34%),
        var(--vl-gradient);
      box-shadow:0 18px 38px rgba(143,87,255,.20), 0 0 0 1px rgba(255,255,255,.42) inset;
      font-size:13.5px;
      font-weight:800;
      cursor:pointer;
      pointer-events:auto;
      display:flex;
      align-items:center;
      justify-content:center;
      gap:8px;
    }

    @keyframes dhFadeUp{
      from{ opacity:0; transform:translateY(10px); }
      to{ opacity:1; transform:translateY(0); }
    }

    @media(min-width:768px){
      .dh-page{ padding:22px 0; }
      .dh-phone{
        min-height:calc(100vh - 44px);
        border-radius:30px;
        overflow:hidden;
      }
      .dh-bottom-actions{
        bottom:22px;
        border-radius:0 0 30px 30px;
      }
    }

    @media(max-width:370px){
      .dh-page{
        padding-left:8px;
        padding-right:8px;
      }

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
            <p>Transaksi pengisian saldo Velora</p>
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
