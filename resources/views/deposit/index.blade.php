@include('partials.anti-inspect')
@php
  $user = auth()->user();

  $paymentMethods = [
    [
      'code' => 'QRIS',
      'api_code' => 'QRIS',
      'name' => 'QRIS (Semua E-Wallet)',
      'type' => 'Direct',
      'desc' => 'Scan pakai DANA, OVO, GoPay, ShopeePay, atau m-banking',
      'badge' => 'QR',
      'logo' => asset('assets/payment-methods/qris.png'),
      'min' => 10000,
      'max' => 1000000,
      'fee' => '5%',
    ],
  ];

  /*
    Saluran pembayaran (config/deposit.php). Dikirim controller lewat $channels.
    Kalau cuma satu saluran yang aktif, pemilihnya disembunyikan dan saluran
    itu tetap ikut terkirim sebagai input hidden.
  */
  $depositChannels = collect($channels ?? []);
  $depositNotice = \App\Services\DepositChannels::notice();

  $selectedChannelKey = old('payment_channel')
      ?: \App\Services\DepositChannels::resolve(null);

  if (!$depositChannels->has($selectedChannelKey)) {
    $selectedChannelKey = $depositChannels->keys()->first();
  }

  $channelIcons = [
    'bankpay' => 'M13 2 4.5 13.5H11l-1 8.5L19.5 10H13l0-8Z',
    'qris_statis' => 'M12 2.5 4.5 5.5v6c0 4.6 3.2 8.9 7.5 10 4.3-1.1 7.5-5.4 7.5-10v-6L12 2.5Z',
  ];

  $selectedMethodCode = old('selected_channel', old('method', 'QRIS'));
  $selectedMethod = collect($paymentMethods)->firstWhere('code', $selectedMethodCode)
      ?? collect($paymentMethods)->firstWhere('api_code', $selectedMethodCode)
      ?? $paymentMethods[0];
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Deposit Saldo | Capital Wave</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
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
    .dp-page{ width:100%; min-height:100vh; display:flex; justify-content:center; }
    .dp-phone{ width:100%; max-width:428px; min-height:100vh; padding:18px 16px 100px; }

    .dp-head{ display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:16px; }
    .dp-head-l{ display:flex; align-items:center; gap:11px; min-width:0; }
    .dp-back{ width:42px; height:42px; flex:0 0 auto; border:1px solid var(--line); background:var(--card); color:var(--navy); border-radius:13px; display:grid; place-items:center; box-shadow:var(--sh-sm); }
    .dp-back svg{ width:20px; height:20px; }
    .dp-head-l .t .name{ display:block; font-size:17px; font-weight:700; letter-spacing:-.02em; color:var(--navy); }
    .dp-head-l .t .tag{ display:block; margin-top:3px; font-size:10px; font-weight:600; letter-spacing:.14em; text-transform:uppercase; color:var(--muted); }
    .dp-head-btn{ width:42px; height:42px; flex:0 0 auto; border:1px solid var(--line); background:var(--card); color:var(--ink-soft); border-radius:13px; display:grid; place-items:center; box-shadow:var(--sh-sm); }
    .dp-head-btn svg{ width:19px; height:19px; }

    .dp-hero{ position:relative; overflow:hidden; border-radius:26px; padding:20px; color:#fff;
      background:
        radial-gradient(420px 240px at 90% -20%, rgba(232,200,116,.18), transparent 62%),
        radial-gradient(360px 220px at 5% 120%, rgba(47,127,212,.22), transparent 60%),
        linear-gradient(150deg,#0f3255 0%,#0b2740 52%,#0a2036 100%);
      box-shadow:var(--sh-navy); }
    .dp-hero::before{ content:""; position:absolute; inset:0; border-radius:inherit; padding:1px; pointer-events:none;
      background:linear-gradient(150deg, rgba(232,200,116,.6), rgba(232,200,116,0) 34%, rgba(255,255,255,.14) 100%);
      -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; }
    .dp-hero > *{ position:relative; z-index:1; }
    .dp-eyebrow{ display:inline-flex; align-items:center; gap:8px; color:rgba(255,255,255,.62); font-size:9.5px; font-weight:600; letter-spacing:.18em; text-transform:uppercase; }
    .dp-eyebrow::before{ content:""; width:6px; height:6px; border-radius:999px; background:var(--gold-lite); box-shadow:0 0 10px rgba(232,200,116,.8); }
    .dp-hero h2{ margin-top:12px; font-size:22px; font-weight:700; letter-spacing:-.03em; }
    .dp-hero p{ margin-top:8px; font-size:11.5px; font-weight:500; color:rgba(255,255,255,.55); line-height:1.5; max-width:280px; }
    .dp-hero-minis{ margin-top:16px; display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    .dp-hero-mini{ border-radius:16px; padding:12px 13px; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.14); }
    .dp-hero-mini span{ display:block; font-size:10px; font-weight:500; color:rgba(255,255,255,.55); }
    .dp-hero-mini strong{ display:block; margin-top:6px; font-size:13px; font-weight:700; letter-spacing:-.02em; }

    .dp-fieldset{ margin-top:18px; }
    .dp-fieldset-label{ display:flex; align-items:baseline; justify-content:space-between; gap:10px; margin-bottom:11px; }
    .dp-fieldset-label span{ font-size:13.5px; font-weight:700; color:var(--navy); letter-spacing:-.02em; }
    .dp-fieldset-label small{ font-size:10.5px; font-weight:500; color:var(--muted); }

    .dp-method-card{ width:100%; text-align:left; display:flex; align-items:center; gap:13px; padding:15px; border-radius:18px; cursor:pointer;
      background:var(--card); border:1.5px solid var(--line); box-shadow:var(--sh-sm); transition:.16s ease; position:relative; }
    .dp-method-card.is-selected{ border-color:var(--blue); box-shadow:0 8px 20px rgba(10,87,163,.12); }
    .dp-method-logo{ width:52px; height:52px; flex:0 0 auto; border-radius:14px; display:grid; place-items:center; overflow:hidden; background:var(--tint); border:1px solid var(--line); }
    .dp-method-logo img{ width:100%; height:100%; object-fit:contain; padding:8px; }
    .dp-method-tx{ flex:1; min-width:0; }
    .dp-method-tx b{ display:block; font-size:14px; font-weight:700; color:var(--navy); letter-spacing:-.01em; }
    .dp-method-tx small{ display:block; margin-top:4px; font-size:10.5px; font-weight:500; color:var(--muted); line-height:1.4; }
    .dp-method-check{ width:24px; height:24px; flex:0 0 auto; border-radius:999px; border:2px solid var(--line-2); display:grid; place-items:center; color:transparent; transition:.16s ease; }
    .dp-method-card.is-selected .dp-method-check{ border-color:var(--blue); background:var(--blue); color:#fff; }
    .dp-method-check svg{ width:13px; height:13px; }

    /* Pemilih saluran pembayaran: dua kartu berdampingan */
    .dp-channel-grid{ display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:11px; }
    .dp-channel-card{ position:relative; width:100%; text-align:left; display:block; padding:15px; border-radius:18px; cursor:pointer;
      background:var(--card, #fff); border:1.6px solid var(--line); transition:.16s ease; }
    .dp-channel-card.is-selected{ border-color:var(--blue); box-shadow:0 8px 20px rgba(10,87,163,.12); }
    .dp-channel-icon{ width:42px; height:42px; border-radius:13px; display:grid; place-items:center; background:var(--tint); color:var(--blue); transition:.16s ease; }
    .dp-channel-card.is-selected .dp-channel-icon{ background:var(--blue); color:#fff; }
    .dp-channel-icon svg{ width:21px; height:21px; }
    .dp-channel-card b{ display:block; margin-top:13px; font-size:13.5px; font-weight:700; color:var(--navy); letter-spacing:-.01em; }
    .dp-channel-card small{ display:block; margin-top:5px; font-size:10.5px; font-weight:500; color:var(--muted); line-height:1.45; }
    .dp-channel-check{ position:absolute; top:14px; right:14px; width:21px; height:21px; border-radius:999px;
      border:2px solid var(--line-2); display:grid; place-items:center; color:transparent; transition:.16s ease; }
    .dp-channel-card.is-selected .dp-channel-check{ border-color:var(--blue); background:var(--blue); color:#fff; }
    .dp-channel-check svg{ width:11px; height:11px; }

    .dp-amount-box{ display:flex; align-items:center; gap:10px; padding:16px 18px; border-radius:18px; background:var(--card); border:1.5px solid var(--line); box-shadow:var(--sh-sm); }
    .dp-amount-box:focus-within{ border-color:var(--blue); box-shadow:0 0 0 3px rgba(10,87,163,.1); }
    .dp-rp{ font-size:20px; font-weight:700; color:var(--muted); }
    .dp-amount-input{ flex:1; min-width:0; border:0; outline:none; background:transparent; color:var(--navy); font-size:30px; font-weight:700; letter-spacing:-.03em; }
    .dp-amount-input::placeholder{ color:var(--muted-2); }
    .dp-clear{ width:34px; height:34px; flex:0 0 auto; border:0; border-radius:10px; background:var(--tint); color:var(--muted); font-size:18px; cursor:pointer; display:grid; place-items:center; }

    .dp-presets{ margin-top:12px; display:grid; grid-template-columns:repeat(3,1fr); gap:9px; }
    .dp-preset{ min-height:44px; border:1px solid var(--line); border-radius:13px; background:var(--card); color:var(--ink-soft); font-size:12px; font-weight:700; cursor:pointer; box-shadow:var(--sh-sm); transition:.15s ease; }
    .dp-preset:hover{ border-color:var(--line-2); }
    .dp-preset.is-active{ color:#fff; background:linear-gradient(135deg,#123457,#0b2740); border-color:transparent; box-shadow:0 8px 16px rgba(11,39,64,.2); }

    .dp-limit{ margin-top:12px; display:flex; align-items:center; justify-content:space-between; gap:10px; padding:11px 13px; border-radius:13px; background:var(--gold-soft); border:1px solid rgba(201,148,51,.18); }
    .dp-limit span{ font-size:10.5px; font-weight:600; color:var(--gold-deep); }
    .dp-error-text{ margin-top:10px; font-size:11.5px; font-weight:600; color:var(--red); min-height:16px; }

    .dp-bottom{ position:fixed; left:50%; bottom:0; transform:translateX(-50%); width:min(100%,460px); padding:14px 16px calc(14px + env(safe-area-inset-bottom));
      background:linear-gradient(180deg, rgba(238,241,246,0), #eef1f6 40%); }
    .dp-submit{ position:relative; overflow:hidden; width:100%; min-height:54px; border:0; border-radius:16px; cursor:pointer; color:#fff;
      background:linear-gradient(135deg,#123457,#0b2740); box-shadow:0 12px 26px rgba(11,39,64,.3), inset 0 1px 0 rgba(255,255,255,.08); font-size:14.5px; font-weight:700; letter-spacing:-.01em; transition:.16s ease; }
    .dp-submit::after{ content:""; position:absolute; inset:0; border-radius:inherit; padding:1px; background:linear-gradient(135deg, rgba(232,200,116,.7), transparent 55%); -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; opacity:.6; }
    .dp-submit:disabled{ opacity:.5; cursor:default; box-shadow:none; }

    /* Pengumuman gangguan saluran pembayaran */
    .dp-notice{ display:flex; gap:11px; align-items:flex-start; margin-bottom:14px; padding:13px 15px; border-radius:16px;
      background:#fff8e6; border:1px solid #f2dfae; color:#7a5a12; font-size:12.5px; font-weight:500; line-height:1.5; }
    .dp-notice svg{ width:17px; height:17px; flex:0 0 auto; margin-top:1px; color:#c69214; }
    .dp-notice b{ display:block; margin-bottom:2px; color:var(--navy); font-weight:700; }

    /* Label kecil di kartu saluran yang sedang bermasalah */
    .dp-channel-flag{ display:inline-block; margin-top:7px; padding:3px 8px; border-radius:999px;
      background:#fdeaea; border:1px solid #f5cfcf; color:#a33; font-size:9.5px; font-weight:700; letter-spacing:.02em; }
    .dp-channel-card.is-degraded{ opacity:.72; }

    .dp-errors{ margin-bottom:14px; padding:13px 15px; border-radius:16px; background:var(--red-soft); border:1px solid #f7d4d4; color:#a33; font-size:12.5px; font-weight:500; }
    .dp-errors b{ color:var(--navy); font-weight:700; }
    @media (prefers-reduced-motion:reduce){ *,*::before,*::after{ animation:none !important; transition:none !important; } }
  </style>
</head>

<body>
  <main class="dp-page">
    <div class="dp-phone">

      <header class="dp-head">
        <div class="dp-head-l">
          <a href="{{ url('/dashboard') }}" class="dp-back" aria-label="Kembali"><svg viewBox="0 0 24 24" fill="none"><path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
          <div class="t"><span class="name">Deposit</span><span class="tag">Isi Saldo</span></div>
        </div>
        <a href="{{ route('deposit.history') }}" class="dp-head-btn" aria-label="Riwayat"><svg viewBox="0 0 24 24" fill="none"><path d="M12 8v4l3 2" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/></svg></a>
      </header>

      <section class="dp-hero">
        <span class="dp-eyebrow">Top Up Saldo</span>
        <h2>Isi saldo untuk mulai berinvestasi</h2>
        <p>Pilih metode pembayaran, masukkan nominal, lalu lanjutkan pembayaran dengan aman.</p>
        <div class="dp-hero-minis">
          <div class="dp-hero-mini"><span>Minimal Deposit</span><strong>Rp 50.000</strong></div>
          <div class="dp-hero-mini"><span>Maksimal Deposit</span><strong>Rp 10.000.000</strong></div>
        </div>
      </section>

      @if ($errors->any())
        <div class="dp-errors" style="margin-top:14px;">
          <b>Terjadi kesalahan</b>
          <ul style="margin:6px 0 0; padding-left:18px;">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
          </ul>
        </div>
      @endif

      @if($depositNotice)
        <div class="dp-notice" role="status">
          <svg viewBox="0 0 24 24" fill="none"><path d="M12 9v4.5M12 17h.01M10.3 3.9 2.5 17.4A2 2 0 0 0 4.2 20.5h15.6a2 2 0 0 0 1.7-3.1L13.7 3.9a2 2 0 0 0-3.4 0Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <span><b>Pemberitahuan</b>{{ $depositNotice }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('deposit.store') }}" id="depositForm" novalidate>
        @csrf
        <input type="hidden" name="method" id="paymentMethod" value="{{ $selectedMethod['api_code'] }}">
        <input type="hidden" name="selected_channel" id="selectedChannel" value="{{ $selectedMethod['code'] }}">
        <input type="hidden" name="payment_channel" id="paymentChannel" value="{{ $selectedChannelKey }}">

        @if($depositChannels->count() > 1)
          <div class="dp-fieldset">
            <div class="dp-fieldset-label"><span>Pilih Saluran Pembayaran</span><small>Pilih saluran</small></div>
            <div class="dp-channel-grid">
              @foreach($depositChannels as $code => $channel)
                <button type="button"
                  class="dp-channel-card {{ $selectedChannelKey === $code ? 'is-selected' : '' }} {{ !empty($channel['degraded']) ? 'is-degraded' : '' }}"
                  data-channel="{{ $code }}">
                  <span class="dp-channel-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="{{ $channelIcons[$code] ?? $channelIcons['bankpay'] }}"/></svg>
                  </span>
                  <b>{{ $channel['name'] }}</b>
                  <small>{{ !empty($channel['degraded']) ? 'Sedang bermasalah, gunakan saluran lain' : $channel['desc'] }}</small>
                  @if(!empty($channel['degraded']))
                    <span class="dp-channel-flag">GANGGUAN</span>
                  @endif
                  <span class="dp-channel-check"><svg viewBox="0 0 24 24" fill="none"><path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                </button>
              @endforeach
            </div>
          </div>
        @endif

        <div class="dp-fieldset">
          <div class="dp-fieldset-label"><span>Metode Pembayaran</span><small>Pilih channel</small></div>
          @foreach($paymentMethods as $method)
            <button type="button"
              class="dp-method-card {{ $selectedMethod['code'] === $method['code'] ? 'is-selected' : '' }}"
              data-code="{{ $method['code'] }}"
              data-api-code="{{ $method['api_code'] }}"
              data-name="{{ $method['name'] }}">
              <span class="dp-method-logo"><img src="{{ $method['logo'] }}" alt="{{ $method['name'] }}" onerror="this.style.display='none'"></span>
              <span class="dp-method-tx"><b>{{ $method['name'] }}</b><small>{{ $method['desc'] }}</small></span>
              <span class="dp-method-check"><svg viewBox="0 0 24 24" fill="none"><path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
            </button>
          @endforeach
        </div>

        <div class="dp-fieldset">
          <div class="dp-fieldset-label"><span>Masukkan Jumlah</span><small>Nominal Deposit</small></div>
          <div class="dp-amount-box">
            <span class="dp-rp">Rp</span>
            <input id="amountDisplay" class="dp-amount-input @error('amount') is-invalid @enderror" inputmode="numeric" autocomplete="off" placeholder="0" value="{{ old('amount') ? number_format((int) old('amount'), 0, ',', '.') : '' }}">
            <button type="button" class="dp-clear" id="clearAmount" aria-label="Hapus nominal">×</button>
          </div>
          <input type="hidden" name="amount" id="amount" value="{{ old('amount') }}">

          <div class="dp-presets" aria-label="Pilihan nominal cepat">
            <button type="button" class="dp-preset" data-amount="50000">Rp 50.000</button>
            <button type="button" class="dp-preset" data-amount="100000">Rp 100.000</button>
            <button type="button" class="dp-preset" data-amount="500000">Rp 500.000</button>
            <button type="button" class="dp-preset" data-amount="1000000">Rp 1.000.000</button>
            <button type="button" class="dp-preset" data-amount="3000000">Rp 3.000.000</button>
            <button type="button" class="dp-preset" data-amount="5000000">Rp 5.000.000</button>
          </div>

          <div class="dp-limit">
            <span>Min: Rp 50.000</span>
            <span>Max: Rp 10.000.000</span>
          </div>
          <div class="dp-error-text" id="amountError">@error('amount') {{ $message }} @enderror</div>
        </div>
      </form>
    </div>
  </main>

  <div class="dp-bottom">
    <button class="dp-submit" type="submit" form="depositForm" id="submitBtn">Lanjutkan Pembayaran</button>
  </div>

  <script>
    (function(){
      const MIN = 50000;
      const MAX = 10000000;

      const form = document.getElementById('depositForm');
      const amountHidden = document.getElementById('amount');
      const amountDisplay = document.getElementById('amountDisplay');
      const clearBtn = document.getElementById('clearAmount');
      const errorEl = document.getElementById('amountError');
      const submitBtn = document.getElementById('submitBtn');
      const presetButtons = Array.from(document.querySelectorAll('.dp-preset'));

      const channelInput = document.getElementById('paymentChannel');
      const channelOptions = Array.from(document.querySelectorAll('.dp-channel-card'));

      channelOptions.forEach(option => {
        option.addEventListener('click', function(){
          if(channelInput) channelInput.value = this.dataset.channel || '';

          channelOptions.forEach(btn => btn.classList.remove('is-selected'));
          this.classList.add('is-selected');
        });
      });

      const methodInput = document.getElementById('paymentMethod');
      const selectedChannelInput = document.getElementById('selectedChannel');
      const methodOptions = Array.from(document.querySelectorAll('.dp-method-card'));

      methodOptions.forEach(option => {
        option.addEventListener('click', function(){
          const code = this.dataset.code || 'QRIS';
          const apiCode = this.dataset.apiCode || code;

          if(methodInput) methodInput.value = apiCode;
          if(selectedChannelInput) selectedChannelInput.value = code;

          methodOptions.forEach(btn => btn.classList.remove('is-selected'));
          this.classList.add('is-selected');
        });
      });
      if(!form || !amountHidden || !amountDisplay) return;

      function onlyNumber(value){
        return String(value || '').replace(/[^\d]/g, '');
      }

      function formatNumber(value){
        const number = Number(value || 0);
        if(!number) return '';
        return number.toLocaleString('id-ID');
      }

      function setAmount(value){
        const clean = onlyNumber(value);
        const number = Number(clean || 0);

        amountHidden.value = number ? String(number) : '';
        amountDisplay.value = number ? formatNumber(number) : '';

        presetButtons.forEach(btn => {
          btn.classList.toggle('is-active', Number(btn.dataset.amount) === number);
        });

        validate(false);
      }

      function validate(showMessage = true){
        const number = Number(amountHidden.value || 0);

        let message = '';

        if(!number){
          message = 'Masukkan jumlah deposit';
        }else if(number < MIN){
          message = 'Minimal deposit Rp 50.000';
        }else if(number > MAX){
          message = 'Maksimal deposit Rp 10.000.000';
        }

        if(errorEl){
          errorEl.textContent = showMessage ? message : '';
        }

        if(submitBtn){
          submitBtn.disabled = Boolean(message);
        }

        return !message;
      }

      amountDisplay.addEventListener('input', function(){
        setAmount(this.value);
      });

      amountDisplay.addEventListener('blur', function(){
        validate(true);
      });

      clearBtn?.addEventListener('click', function(){
        setAmount('');
        amountDisplay.focus();
        validate(true);
      });

      presetButtons.forEach(btn => {
        btn.addEventListener('click', function(){
          setAmount(this.dataset.amount);
          validate(false);
        });
      });

      form.addEventListener('submit', function(e){
        if(!validate(true)){
          e.preventDefault();
        }
      });

      if(amountHidden.value){
        setAmount(amountHidden.value);
      }else if(amountDisplay.value){
        setAmount(amountDisplay.value);
      }else{
        validate(false);
      }
    })();
  </script>
</body>
</html>
