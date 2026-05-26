 @include('partials.anti-inspect')
@php
  $user = auth()->user();

  $paymentMethods = [
    [
      'code' => 'QRIS',
      'api_code' => 'QRIS',
      'name' => 'QRIS',
      'type' => 'Direct',
      'desc' => 'Bayar cepat melalui kode QRIS',
      'badge' => 'QR',
      'logo' => asset('assets/payment-methods/qris.png'),
      'min' => 10000,
      'max' => 1000000,
      'fee' => '5%',
    ],
    [
      'code' => 'BRI',
      'api_code' => 'BRI',
      'name' => 'BRI Virtual Account',
      'type' => 'Direct',
      'desc' => 'Transfer melalui Bank BRI',
      'badge' => 'BR',
      'logo' => asset('assets/payment-methods/bri.png'),
      'min' => 15000,
      'max' => 50000000,
      'fee' => '3.5% + Rp 6.500',
    ],
    [
      'code' => 'DANA',
      'api_code' => 'CASHIER',
      'name' => 'DANA',
      'type' => 'QRIS Combo',
      'desc' => 'Bayar melalui DANA',
      'badge' => 'DA',
      'logo' => asset('assets/payment-methods/dana.png'),
      'min' => 10000,
      'max' => 1000000,
      'fee' => '5%',
    ],
    [
      'code' => 'GOPAY',
      'api_code' => 'CASHIER',
      'name' => 'GoPay',
      'type' => 'QRIS Combo',
      'desc' => 'Bayar melalui GoPay',
      'badge' => 'GP',
      'logo' => asset('assets/payment-methods/gopay.png'),
      'min' => 10000,
      'max' => 1000000,
      'fee' => '5%',
    ],
    [
      'code' => 'OVO',
      'api_code' => 'CASHIER',
      'name' => 'OVO',
      'type' => 'QRIS Combo',
      'desc' => 'Bayar melalui OVO',
      'badge' => 'OV',
      'logo' => asset('assets/payment-methods/ovo.png'),
      'min' => 10000,
      'max' => 1000000,
      'fee' => '5%',
    ],
    [
      'code' => 'DOKU',
      'api_code' => 'CASHIER',
      'name' => 'DOKU',
      'type' => 'QRIS Combo',
      'desc' => 'Bayar melalui DOKU',
      'badge' => 'DK',
      'logo' => asset('assets/payment-methods/doku.png'),
      'min' => 10000,
      'max' => 1000000,
      'fee' => '5%',
    ],
    [
      'code' => 'LINKAJA',
      'api_code' => 'CASHIER',
      'name' => 'LinkAja',
      'type' => 'QRIS Combo',
      'desc' => 'Bayar melalui LinkAja',
      'badge' => 'LA',
      'logo' => asset('assets/payment-methods/linkaja.png'),
      'min' => 10000,
      'max' => 1000000,
      'fee' => '5%',
    ],
    [
      'code' => 'SHOPEEPAY',
      'api_code' => 'CASHIER',
      'name' => 'ShopeePay',
      'type' => 'QRIS Combo',
      'desc' => 'Bayar melalui ShopeePay',
      'badge' => 'SP',
      'logo' => asset('assets/payment-methods/shopeepay.png'),
      'min' => 10000,
      'max' => 1000000,
      'fee' => '5%',
    ],
    [
      'code' => 'BCA',
      'api_code' => 'CASHIER',
      'name' => 'BCA',
      'type' => 'QRIS Combo',
      'desc' => 'Bayar melalui BCA',
      'badge' => 'BC',
      'logo' => asset('assets/payment-methods/bca.png'),
      'min' => 10000,
      'max' => 1000000,
      'fee' => '5%',
    ],
    [
      'code' => 'MANDIRI',
      'api_code' => 'CASHIER',
      'name' => 'Mandiri',
      'type' => 'QRIS Combo',
      'desc' => 'Bayar melalui Mandiri',
      'badge' => 'MD',
      'logo' => asset('assets/payment-methods/mandiri.png'),
      'min' => 10000,
      'max' => 1000000,
      'fee' => '5%',
    ],
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
  <title>Deposit Saldo | Velora Finance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>

    :root{
      --rb-bg:#f7f2fa;
      --rb-bg2:#efe8f7;
      --rb-panel:#ffffff;
      --rb-panel2:#fbf8ff;
      --rb-card:#ffffff;
      --rb-text:#2b0b16;
      --rb-dark:#2b0b16;
      --rb-muted:#7b6370;
      --rb-muted-dark:#a894a0;
      --rb-violet:#d96bff;
      --rb-purple:#8f57ff;
      --rb-gold:#f5af2a;
      --rb-gold2:#ffd46d;
      --rb-lilac:#f3d6ff;
      --rb-rose:#ff5c93;
      --rb-neon:#d96bff;
      --rb-neon2:#ffd46d;
      --rb-border:rgba(43,11,22,.085);
      --rb-shadow:0 26px 68px rgba(88,43,145,.16);
      --rb-soft:0 14px 36px rgba(43,11,22,.075);
      --rb-gradient-main:linear-gradient(135deg,#f5af2a 0%,#ffd46d 26%,#d96bff 58%,#8f57ff 100%);
      --rb-gradient-gold:linear-gradient(135deg,#ffe08a 0%,#f5af2a 100%);
      --rb-gradient-purple:linear-gradient(135deg,#d96bff 0%,#8f57ff 100%);
      --rb-radius-xl:34px;
      --rb-radius-lg:28px;
      --rb-radius-md:22px;
      --rb-radius-sm:18px;
    }

    *{ box-sizing:border-box; }

    html,
    body{ min-height:100%; }

    body{
      margin:0;
      font-family:Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      color:var(--rb-text);
      background:
        radial-gradient(680px 360px at 50% -150px, rgba(245,175,42,.23), transparent 64%),
        radial-gradient(520px 340px at 100% 4%, rgba(217,107,255,.18), transparent 62%),
        radial-gradient(520px 330px at -12% 34%, rgba(143,87,255,.13), transparent 58%),
        linear-gradient(180deg,#ffffff 0%,#f7f2fa 44%,#eee8f6 100%);
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
      mask-image:linear-gradient(180deg, rgba(0,0,0,.46), transparent 76%);
      -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.46), transparent 76%);
      opacity:.55;
      z-index:0;
    }

    body::after{
      content:"";
      position:fixed;
      inset:0;
      pointer-events:none;
      background:
        radial-gradient(circle at 9% 18%, rgba(245,175,42,.095), transparent 28%),
        radial-gradient(circle at 92% 26%, rgba(217,107,255,.105), transparent 30%),
        radial-gradient(circle at 50% 100%, rgba(143,87,255,.07), transparent 34%);
      z-index:0;
    }

    a{ color:inherit; text-decoration:none; }

    button,
    input{ font-family:inherit; }

    .dp-page{
      width:100%;
      min-height:100vh;
      display:flex;
      justify-content:center;
      position:relative;
      z-index:1;
      padding:14px 10px 0;
    }

    .dp-phone{
      width:100%;
      max-width:430px;
      min-height:100vh;
      position:relative;
      padding:8px 4px 118px;
    }

    /* HEADER */
    .dp-header{
      min-height:48px;
      display:grid;
      grid-template-columns:44px 1fr 44px;
      align-items:center;
      gap:8px;
      margin-bottom:16px;
      padding:0 2px;
    }

    .dp-back{
      width:42px;
      height:42px;
      border-radius:999px;
      border:1px solid rgba(43,11,22,.08);
      background:rgba(255,255,255,.88);
      color:#5b2841;
      display:grid;
      place-items:center;
      box-shadow:
        0 12px 26px rgba(43,11,22,.065),
        inset 0 1px 0 rgba(255,255,255,.92);
      backdrop-filter:blur(18px);
      -webkit-backdrop-filter:blur(18px);
      transition:.18s ease;
    }

    .dp-back:hover{
      transform:translateY(-1px);
      color:var(--rb-purple);
      border-color:rgba(143,87,255,.16);
      box-shadow:0 16px 32px rgba(43,11,22,.09);
    }

    .dp-back svg{
      width:20px;
      height:20px;
    }

    .dp-title{
      margin:0;
      color:#3a0712;
      font-size:23px;
      line-height:1;
      font-weight:950;
      letter-spacing:-.055em;
      text-align:center;
    }

    /* HERO */
    .dp-hero{
      position:relative;
      overflow:hidden;
      border-radius:34px;
      margin-bottom:14px;
      background:
        radial-gradient(360px 220px at 92% -12%, rgba(255,212,109,.48), transparent 58%),
        radial-gradient(300px 200px at 2% 8%, rgba(217,107,255,.34), transparent 62%),
        linear-gradient(145deg,#8f57ff 0%,#9455ff 40%,#d96bff 72%,#f5af2a 100%);
      border:1px solid rgba(255,255,255,.44);
      box-shadow:
        0 28px 62px rgba(143,87,255,.22),
        0 18px 42px rgba(245,175,42,.10),
        inset 0 1px 0 rgba(255,255,255,.22);
      padding:18px;
      color:#fff;
    }

    .dp-hero::before{
      content:"";
      position:absolute;
      inset:0;
      background:
        linear-gradient(135deg, rgba(255,255,255,.22), transparent 34%),
        radial-gradient(circle at 82% 26%, rgba(255,255,255,.16), transparent 28%),
        linear-gradient(180deg, transparent 0%, rgba(43,11,22,.08) 100%);
      pointer-events:none;
    }

    .dp-hero::after{
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

    .dp-hero > *{
      position:relative;
      z-index:1;
    }

    .dp-hero-kicker{
      display:inline-flex;
      align-items:center;
      min-height:28px;
      padding:0 11px;
      border-radius:999px;
      color:#2b0b16;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.66), transparent 36%),
        linear-gradient(135deg,#ffe08a,#f5af2a);
      font-size:10px;
      font-weight:950;
      letter-spacing:.09em;
      text-transform:uppercase;
      box-shadow:0 12px 24px rgba(245,175,42,.22), inset 0 1px 0 rgba(255,255,255,.35);
    }

    .dp-hero-title{
      margin:13px 0 0;
      color:#ffffff;
      font-size:25px;
      line-height:1.04;
      letter-spacing:-.065em;
      font-weight:950;
      text-shadow:0 12px 26px rgba(43,11,22,.24);
      max-width:320px;
    }

    .dp-hero-sub{
      margin:9px 0 0;
      color:rgba(255,255,255,.72);
      font-size:12px;
      font-weight:650;
      line-height:1.48;
      max-width:340px;
    }

    .dp-hero-row{
      margin-top:15px;
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:9px;
    }

    .dp-hero-mini{
      min-height:62px;
      border-radius:20px;
      padding:11px 12px;
      background:rgba(255,255,255,.12);
      border:1px solid rgba(255,255,255,.16);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.10);
      backdrop-filter:blur(10px);
      -webkit-backdrop-filter:blur(10px);
    }

    .dp-hero-mini span{
      display:block;
      margin-bottom:7px;
      color:rgba(255,255,255,.62);
      font-size:10px;
      font-weight:750;
    }

    .dp-hero-mini strong{
      display:block;
      color:#ffffff;
      font-size:12.7px;
      line-height:1.15;
      letter-spacing:-.02em;
      font-weight:950;
    }

    /* ALERT */
    .dp-alert{
      margin:0 0 14px;
      padding:13px;
      border-radius:22px;
      background:
        radial-gradient(220px 140px at 100% 0%, rgba(255,92,147,.14), transparent 60%),
        #fff4f7;
      border:1px solid rgba(255,92,147,.20);
      color:#8f263e;
      font-size:12px;
      font-weight:750;
      line-height:1.5;
      box-shadow:0 14px 30px rgba(43,11,22,.07);
    }

    .dp-alert strong{
      display:block;
      margin-bottom:5px;
      color:#5b1024;
      font-size:12.5px;
      font-weight:950;
    }

    .dp-alert ul{
      margin:0;
      padding-left:18px;
    }

    /* FIELDSET CARD */
    .dp-fieldset{
      position:relative;
      margin-bottom:14px;
      border:1px solid rgba(43,11,22,.075);
      border-radius:28px;
      background:
        radial-gradient(260px 140px at 90% 0%, rgba(217,107,255,.12), transparent 64%),
        linear-gradient(180deg,rgba(255,255,255,.97),rgba(255,255,255,.90));
      box-shadow:
        0 14px 34px rgba(43,11,22,.07),
        inset 0 1px 0 rgba(255,255,255,.94);
      padding:16px 13px 13px;
      overflow:hidden;
    }

    .dp-fieldset::before{
      content:"";
      position:absolute;
      inset:0;
      pointer-events:none;
      background:
        linear-gradient(135deg, rgba(255,255,255,.82), transparent 30%),
        radial-gradient(circle at 12% 0%, rgba(245,175,42,.08), transparent 42%);
    }

    .dp-fieldset > *{
      position:relative;
      z-index:1;
    }

    .dp-fieldset-label{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:10px;
      margin:0 0 12px;
      color:#3a0712;
      font-size:14px;
      font-weight:950;
      line-height:1.15;
      letter-spacing:-.025em;
    }

    .dp-fieldset-label small{
      color:#8f57ff;
      font-size:10.5px;
      font-weight:850;
      white-space:nowrap;
    }

    /* PAYMENT METHOD GRID */
    .dp-method-grid{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:10px;
    }

    .dp-method-card{
      position:relative;
      width:100%;
      min-height:108px;
      border:1px solid rgba(43,11,22,.075);
      border-radius:22px;
      padding:12px;
      background:
        radial-gradient(180px 100px at 88% 0%, rgba(217,107,255,.11), transparent 62%),
        rgba(255,255,255,.78);
      color:#3a0712;
      text-align:left;
      cursor:pointer;
      overflow:hidden;
      box-shadow:
        0 10px 24px rgba(43,11,22,.055),
        inset 0 1px 0 rgba(255,255,255,.86);
      transition:transform .16s ease, border-color .16s ease, background .16s ease, box-shadow .16s ease;
    }

    .dp-method-card::before{
      content:"";
      position:absolute;
      inset:0;
      pointer-events:none;
      opacity:.78;
      background:
        linear-gradient(135deg, rgba(255,255,255,.78), transparent 34%),
        radial-gradient(circle at 12% 0%, rgba(245,175,42,.08), transparent 42%);
    }

    .dp-method-card:hover{
      transform:translateY(-1px);
      border-color:rgba(143,87,255,.16);
      box-shadow:
        0 16px 32px rgba(43,11,22,.085),
        0 0 0 4px rgba(143,87,255,.04);
    }

    .dp-method-card.is-selected{
      border-color:rgba(143,87,255,.34);
      background:
        radial-gradient(200px 110px at 85% 0%, rgba(245,175,42,.13), transparent 64%),
        radial-gradient(200px 110px at 0% 100%, rgba(217,107,255,.15), transparent 64%),
        #ffffff;
      box-shadow:
        0 16px 34px rgba(143,87,255,.15),
        0 0 0 1px rgba(217,107,255,.12) inset;
    }

    .dp-method-card > *{
      position:relative;
      z-index:1;
    }

    .dp-method-logo{
      width:54px;
      height:42px;
      border-radius:15px;
      display:flex;
      align-items:center;
      justify-content:center;
      background:#ffffff;
      border:1px solid rgba(43,11,22,.07);
      color:#210812;
      overflow:hidden;
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,.82),
        0 10px 20px rgba(43,11,22,.08);
      margin-bottom:11px;
    }

    .dp-method-logo-img,
    .dp-method-option-logo-img{
      display:block;
      width:44px;
      height:34px;
      object-fit:contain;
    }

    .dp-method-logo-fallback{
      display:none;
      color:#210812;
      font-size:12px;
      font-weight:950;
    }

    .dp-method-title{
      margin:0;
      color:#3a0712;
      font-size:12.5px;
      line-height:1.18;
      font-weight:950;
      letter-spacing:-.02em;
      display:-webkit-box;
      -webkit-line-clamp:2;
      -webkit-box-orient:vertical;
      overflow:hidden;
    }

    .dp-method-sub{
      margin:5px 0 0;
      color:#7b6370;
      font-size:10.2px;
      font-weight:650;
      line-height:1.25;
      display:-webkit-box;
      -webkit-line-clamp:2;
      -webkit-box-orient:vertical;
      overflow:hidden;
    }

    .dp-method-check{
      position:absolute;
      top:10px;
      right:10px;
      z-index:2;
      width:24px;
      height:24px;
      border-radius:999px;
      display:grid;
      place-items:center;
      color:#2b0b16;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.55), transparent 34%),
        var(--rb-gradient-main);
      box-shadow:0 10px 20px rgba(143,87,255,.20);
      opacity:0;
      transform:scale(.82);
      transition:.16s ease;
    }

    .dp-method-card.is-selected .dp-method-check{
      opacity:1;
      transform:scale(1);
    }

    .dp-method-fee{
      display:inline-flex;
      margin-top:9px;
      min-height:22px;
      align-items:center;
      padding:0 8px;
      border-radius:999px;
      color:#2b0b16;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.50), transparent 34%),
        linear-gradient(135deg,#ffe08a,#f5af2a);
      font-size:9.5px;
      font-weight:950;
      box-shadow:0 8px 16px rgba(245,175,42,.16);
    }

    /* AMOUNT */
    .dp-amount-box{
      min-height:88px;
      display:flex;
      align-items:center;
      gap:8px;
      border-radius:23px;
      background:#fbf8ff;
      border:1px solid rgba(43,11,22,.075);
      padding:8px 10px 8px 13px;
      box-shadow:inset 0 1px 0 rgba(255,255,255,.86);
    }

    .dp-rp{
      color:#8f57ff;
      font-size:30px;
      line-height:1;
      font-weight:750;
      letter-spacing:-.055em;
      flex:0 0 auto;
    }

    .dp-amount-input{
      width:100%;
      min-width:0;
      border:0;
      outline:0;
      background:transparent;
      color:#3a0712;
      font-size:30px;
      line-height:1;
      font-weight:850;
      letter-spacing:-.055em;
      padding:0;
    }

    .dp-amount-input::placeholder{
      color:rgba(43,11,22,.30);
    }

    .dp-clear{
      width:34px;
      height:34px;
      border-radius:999px;
      border:1px solid rgba(43,11,22,.08);
      background:#ffffff;
      color:#8a7280;
      display:grid;
      place-items:center;
      cursor:pointer;
      flex:0 0 auto;
      box-shadow:0 8px 18px rgba(43,11,22,.05);
    }

    .dp-clear:hover{
      color:#3a0712;
      border-color:rgba(143,87,255,.16);
    }

    .dp-clear svg{
      width:18px;
      height:18px;
    }

    /* PRESET */
    .dp-presets{
      display:grid;
      grid-template-columns:1fr 1fr 1fr;
      gap:8px;
      margin:8px 0 16px;
    }

    .dp-preset{
      border:1px solid rgba(43,11,22,.075);
      background:
        radial-gradient(circle at 80% 0%, rgba(217,107,255,.09), transparent 40%),
        rgba(255,255,255,.88);
      color:#3a0712;
      min-height:41px;
      padding:0 9px;
      border-radius:17px;
      font-size:11.5px;
      font-weight:900;
      cursor:pointer;
      box-shadow:
        0 10px 22px rgba(43,11,22,.055),
        inset 0 1px 0 rgba(255,255,255,.90);
      transition:transform .16s ease, background .16s ease, border-color .16s ease, box-shadow .16s ease;
    }

    .dp-preset:hover{
      transform:translateY(-1px);
      border-color:rgba(143,87,255,.18);
      background:#ffffff;
    }

    .dp-preset.is-active{
      color:#2b0b16;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.52), transparent 34%),
        var(--rb-gradient-main);
      border-color:rgba(255,255,255,.22);
      box-shadow:0 12px 26px rgba(143,87,255,.18);
    }

    /* LIMIT */
    .dp-limit{
      display:flex;
      justify-content:space-between;
      gap:12px;
      margin:0 0 12px;
      color:#7b6370;
      font-size:10.3px;
      font-weight:700;
      line-height:1.35;
    }

    .dp-limit span:last-child{
      text-align:right;
    }

    .dp-error-text{
      min-height:20px;
      text-align:center;
      color:#e24a64;
      font-size:11.5px;
      font-weight:850;
      margin-bottom:10px;
    }

    .dp-error-text:empty{
      display:none;
    }

    /* FIXED ACTION */
    .dp-bottom{
      position:fixed;
      left:50%;
      bottom:0;
      transform:translateX(-50%);
      z-index:50;
      width:min(100%, 430px);
      padding:12px 14px calc(14px + env(safe-area-inset-bottom));
      background:
        linear-gradient(180deg, rgba(247,242,250,0), rgba(247,242,250,.90) 26%, rgba(247,242,250,.98));
      pointer-events:none;
    }

    .dp-submit{
      width:100%;
      min-height:52px;
      border:0;
      border-radius:999px;
      color:#2b0b16;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.58), transparent 34%),
        var(--rb-gradient-main);
      box-shadow:
        0 18px 38px rgba(143,87,255,.20),
        0 0 0 1px rgba(255,255,255,.34) inset;
      font-size:14px;
      font-weight:950;
      cursor:pointer;
      pointer-events:auto;
      transition:transform .16s ease, filter .16s ease, opacity .16s ease;
    }

    .dp-submit:hover{
      transform:translateY(-1px);
      filter:brightness(1.03);
    }

    .dp-submit:active{
      transform:scale(.99);
    }

    .dp-submit[disabled]{
      opacity:.55;
      cursor:not-allowed;
      filter:saturate(.62);
    }

    @media (min-width:768px){
      .dp-page{
        padding:22px 0;
      }

      .dp-phone{
        min-height:calc(100vh - 44px);
        border-radius:30px;
        overflow:hidden;
        padding-left:14px;
        padding-right:14px;
      }

      .dp-bottom{
        bottom:22px;
        border-radius:0 0 30px 30px;
      }
    }

    @media (max-width:370px){
      .dp-page{
        padding-left:8px;
        padding-right:8px;
      }

      .dp-phone{
        padding-left:2px;
        padding-right:2px;
      }

      .dp-title{
        font-size:21px;
      }

      .dp-hero{
        border-radius:30px;
        padding:16px;
      }

      .dp-hero-title{
        font-size:22px;
      }

      .dp-hero-row{
        grid-template-columns:1fr;
      }

      .dp-method-grid{
        gap:8px;
      }

      .dp-method-card{
        min-height:104px;
        border-radius:19px;
        padding:10px;
      }

      .dp-method-logo{
        width:48px;
        height:38px;
        border-radius:13px;
      }

      .dp-method-logo-img{
        width:39px;
        height:30px;
      }

      .dp-method-title{
        font-size:11.6px;
      }

      .dp-method-sub{
        font-size:9.6px;
      }

      .dp-rp,
      .dp-amount-input{
        font-size:28px;
      }

      .dp-presets{
        grid-template-columns:1fr 1fr;
      }

      .dp-limit{
        font-size:10px;
      }
    }

  </style>
</head>

<body>
  <main class="dp-page">
    <div class="dp-phone">

      <header class="dp-header">
        <a href="/dashboard" class="dp-back" aria-label="Kembali">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M15 18 9 12l6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>

        <h1 class="dp-title">Deposit</h1>

        <span aria-hidden="true"></span>
      </header>


      <section class="dp-hero" aria-label="Deposit Velora">
        <span class="dp-hero-kicker">Velora Payment</span>
        <h2 class="dp-hero-title">Top up saldo lebih cepat.</h2>
        <p class="dp-hero-sub">Pilih metode pembayaran langsung dari daftar, masukkan nominal, lalu lanjutkan pembayaran dengan aman.</p>

        <div class="dp-hero-row">
          <div class="dp-hero-mini">
            <span>Minimum Deposit</span>
            <strong>Rp 50.000</strong>
          </div>

          <div class="dp-hero-mini">
            <span>Maksimum Deposit</span>
            <strong>Rp 10.000.000</strong>
          </div>
        </div>
      </section>

      @if ($errors->any())
        <div class="dp-alert" role="alert">
          <strong>Periksa input Anda</strong>
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif


  <form method="POST" action="{{ route('deposit.store') }}" id="depositForm" novalidate>
      @csrf

    <input type="hidden" name="method" id="paymentMethod" value="{{ $selectedMethod['api_code'] }}">
<input type="hidden" name="selected_channel" id="selectedChannel" value="{{ $selectedMethod['code'] }}">

        <section class="dp-fieldset">
          <div class="dp-fieldset-label">
            <span>Metode Pembayaran</span>
            <small>Klik untuk pilih</small>
          </div>

          <div class="dp-method-grid" aria-label="Pilihan metode pembayaran">
            @foreach($paymentMethods as $method)
              <button
                type="button"
                class="dp-method-card {{ $selectedMethod['code'] === $method['code'] ? 'is-selected' : '' }}"
                data-code="{{ $method['code'] }}"
                data-api-code="{{ $method['api_code'] }}"
                data-name="{{ $method['name'] }}"
                data-desc="{{ $method['desc'] }}"
                data-badge="{{ $method['badge'] }}"
                data-logo="{{ $method['logo'] }}"
                aria-label="Pilih {{ $method['name'] }}"
              >
                <span class="dp-method-check" aria-hidden="true">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                    <path d="m5 12 4 4L19 6" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </span>

                <span class="dp-method-logo">
                  <img
                    src="{{ $method['logo'] }}"
                    alt="{{ $method['name'] }}"
                    class="dp-method-logo-img"
                    loading="lazy"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                  >
                  <span class="dp-method-logo-fallback">{{ $method['badge'] }}</span>
                </span>

                <span class="dp-method-title">{{ $method['name'] }}</span>
                <span class="dp-method-sub">{{ $method['desc'] }}</span>
                <span class="dp-method-fee">{{ $method['fee'] }}</span>
              </button>
            @endforeach
          </div>
        </section>

        <section class="dp-fieldset">
          <div class="dp-fieldset-label"><span>Masukkan Jumlah</span><small>Nominal Deposit</small></div>

          <div class="dp-amount-box">
            <span class="dp-rp">Rp</span>

            <input
              type="text"
              inputmode="numeric"
              id="amountDisplay"
              class="dp-amount-input @error('amount') is-invalid @enderror"
              placeholder="0"
              autocomplete="off"
              value="{{ old('amount') ? number_format((int) old('amount'), 0, ',', '.') : '' }}"
              aria-label="Masukkan jumlah deposit"
            >

            <button type="button" class="dp-clear" id="clearAmount" aria-label="Hapus nominal">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M18 6 6 18" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"/>
                <path d="M6 6 18 18" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"/>
              </svg>
            </button>
          </div>

          <input
            type="hidden"
            name="amount"
            id="amount"
            value="{{ old('amount') }}"
          >
        </section>

        <div class="dp-presets" aria-label="Pilihan nominal cepat">
          <button type="button" class="dp-preset" data-amount="50000">Rp 50.000</button>
          <button type="button" class="dp-preset" data-amount="100000">Rp 100.000</button>
          <button type="button" class="dp-preset" data-amount="500000">Rp 500.000</button>
          <button type="button" class="dp-preset" data-amount="1000000">Rp 1.000.000</button>
          <button type="button" class="dp-preset" data-amount="3000000">Rp 3.000.000</button>
          <button type="button" class="dp-preset" data-amount="5000000">Rp 5.000.000</button>
        </div>

        <div class="dp-limit">
          <span>Minimal deposit: Rp 50.000</span>
          <span>Maksimal deposit: Rp 10.000.000</span>
        </div>

        <div class="dp-error-text" id="amountError">
          @error('amount') {{ $message }} @enderror
        </div>
      </form>

     
    </div>
  </main>


  <div class="dp-bottom">
    <button class="dp-submit" type="submit" form="depositForm" id="submitBtn">
      Lanjutkan Pembayaran
    </button>
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