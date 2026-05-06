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
  <title>Deposit Saldo | Rubik Company</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --rb-bg:#030F0F;
      --rb-bg2:#061817;
      --rb-panel:#071f1b;
      --rb-panel2:#0a2a23;
      --rb-card:#ffffff;
      --rb-text:#f7fffb;
      --rb-dark:#071211;
      --rb-muted:#89a99c;
      --rb-muted-dark:#64748b;
      --rb-neon:#00DF82;
      --rb-neon2:#58ffad;
      --rb-border:rgba(255,255,255,.10);
      --rb-shadow:0 24px 70px rgba(0,0,0,.42);
      --rb-soft:0 14px 32px rgba(0,0,0,.22);
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
      font-family:Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      color:var(--rb-text);
      background:
        radial-gradient(620px 360px at 20% -5%, rgba(0,223,130,.20), transparent 62%),
        radial-gradient(520px 340px at 95% 12%, rgba(3,98,76,.36), transparent 65%),
        linear-gradient(180deg, #061b17 0%, #030F0F 58%, #020807 100%);
      overflow-x:hidden;
      -webkit-tap-highlight-color:transparent;
    }

    body::before{
      content:"";
      position:fixed;
      inset:0;
      pointer-events:none;
      background:
        linear-gradient(rgba(255,255,255,.018) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.014) 1px, transparent 1px);
      background-size:38px 38px;
      opacity:.34;
      mask-image:linear-gradient(180deg, rgba(0,0,0,.8), transparent 76%);
      -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.8), transparent 76%);
    }

    a{
      color:inherit;
      text-decoration:none;
    }

    button,
    input{
      font-family:inherit;
    }

    .dp-page{
      width:100%;
      min-height:100vh;
      display:flex;
      justify-content:center;
      position:relative;
      z-index:1;
      padding:0;
    }

    .dp-phone{
      width:100%;
      max-width:430px;
      min-height:100vh;
      position:relative;
      background:
        radial-gradient(360px 260px at 86% 7%, rgba(0,223,130,.14), transparent 68%),
        linear-gradient(180deg, rgba(7,31,27,.98), rgba(3,15,15,.98));
      box-shadow:0 0 0 1px rgba(255,255,255,.05);
      padding:14px 14px 104px;
    }

    /* HEADER */
    .dp-header{
      min-height:42px;
      display:grid;
      grid-template-columns:44px 1fr 44px;
      align-items:center;
      gap:8px;
      margin-bottom:22px;
    }

    .dp-back{
      width:38px;
      height:38px;
      border-radius:14px;
      border:1px solid rgba(255,255,255,.10);
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.16), transparent 36%),
        linear-gradient(180deg, rgba(12,44,37,.92), rgba(5,20,17,.96));
      color:#ffffff;
      display:grid;
      place-items:center;
      box-shadow:0 10px 22px rgba(0,0,0,.24);
    }

    .dp-back svg{
      width:20px;
      height:20px;
    }

    .dp-title{
      margin:0;
      color:#ffffff;
      font-size:22px;
      line-height:1;
      font-weight:950;
      letter-spacing:-.04em;
      text-align:center;
      text-shadow:0 10px 24px rgba(0,0,0,.32);
    }

    /* ALERT */
    .dp-alert{
      margin:0 0 14px;
      padding:12px 13px;
      border-radius:16px;
      background:rgba(80,10,22,.58);
      border:1px solid rgba(255,79,109,.28);
      color:#ffd8df;
      font-size:12px;
      font-weight:700;
      line-height:1.5;
      box-shadow:0 14px 30px rgba(0,0,0,.20);
    }

    .dp-alert strong{
      display:block;
      margin-bottom:5px;
      color:#ffffff;
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
      margin-bottom:16px;
      border:1px solid rgba(255,255,255,.10);
      border-radius:18px;
      background:
        radial-gradient(260px 140px at 90% 0%, rgba(0,223,130,.09), transparent 64%),
        linear-gradient(180deg, rgba(9,37,31,.86), rgba(5,20,17,.92));
      box-shadow:
        0 16px 36px rgba(0,0,0,.24),
        inset 0 1px 0 rgba(255,255,255,.06);
      padding:17px 14px 13px;
    }

    .dp-fieldset-label{
      position:absolute;
      left:18px;
      top:-9px;
      padding:0 8px;
      background:#071f1b;
      color:rgba(214,255,240,.75);
      font-size:12px;
      font-weight:700;
      line-height:18px;
      border-radius:999px;
    }

    /* METHOD */
    .dp-method{
      width:100%;
      min-height:74px;
      display:grid;
      grid-template-columns:58px minmax(0,1fr) 28px;
      align-items:center;
      gap:10px;
      border-radius:14px;
      border:1px solid rgba(255,255,255,.10);
      background:rgba(255,255,255,.045);
      padding:12px;
    }

    .dp-qris-logo{
      width:54px;
      height:40px;
      border-radius:12px;
      display:grid;
      place-items:center;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.28), transparent 34%),
        linear-gradient(135deg, rgba(255,255,255,.13), rgba(255,255,255,.05));
      color:#ffffff;
      font-size:14px;
      font-weight:950;
      letter-spacing:-.08em;
      box-shadow:inset 0 1px 0 rgba(255,255,255,.08);
    }

    .dp-method-text{
      min-width:0;
    }

    .dp-method-title{
      margin:0;
      color:#ffffff;
      font-size:14px;
      line-height:1.15;
      font-weight:900;
      letter-spacing:-.02em;
    }

    .dp-method-sub{
      margin:4px 0 0;
      color:rgba(214,255,240,.58);
      font-size:11.5px;
      font-weight:600;
      line-height:1.25;
    }

    .dp-chevron{
      color:rgba(214,255,240,.72);
      display:grid;
      place-items:center;
    }

    .dp-chevron svg{
      width:20px;
      height:20px;
    }

    /* AMOUNT */
    .dp-amount-box{
      min-height:92px;
      display:flex;
      align-items:center;
      gap:8px;
      padding:8px 2px 2px;
    }

    .dp-rp{
      color:rgba(255,255,255,.78);
      font-size:30px;
      line-height:1;
      font-weight:500;
      letter-spacing:-.055em;
      flex:0 0 auto;
    }

    .dp-amount-input{
      width:100%;
      min-width:0;
      border:0;
      outline:0;
      background:transparent;
      color:#ffffff;
      font-size:30px;
      line-height:1;
      font-weight:650;
      letter-spacing:-.05em;
      padding:0;
    }

    .dp-amount-input::placeholder{
      color:rgba(255,255,255,.42);
    }

    .dp-clear{
      width:34px;
      height:34px;
      border-radius:999px;
      border:1px solid rgba(255,255,255,.10);
      background:rgba(255,255,255,.06);
      color:rgba(255,255,255,.82);
      display:grid;
      place-items:center;
      cursor:pointer;
      flex:0 0 auto;
    }

    .dp-clear svg{
      width:18px;
      height:18px;
    }

    /* PRESET */
    .dp-presets{
      display:flex;
      flex-wrap:wrap;
      gap:8px;
      margin:8px 0 22px;
    }

    .dp-preset{
      border:1px solid rgba(255,255,255,.10);
      background:
        linear-gradient(180deg, rgba(255,255,255,.08), rgba(255,255,255,.035));
      color:#ffffff;
      min-height:36px;
      padding:0 15px;
      border-radius:999px;
      font-size:13px;
      font-weight:850;
      cursor:pointer;
      box-shadow:0 10px 20px rgba(0,0,0,.14);
      transition:transform .16s ease, background .16s ease, border-color .16s ease, box-shadow .16s ease;
    }

    .dp-preset:hover{
      transform:translateY(-1px);
      border-color:rgba(0,223,130,.24);
      background:rgba(0,223,130,.10);
    }

    .dp-preset.is-active{
      color:#06110d;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.42), transparent 34%),
        linear-gradient(135deg, #00DF82, #79ff99);
      border-color:rgba(255,255,255,.22);
      box-shadow:0 12px 26px rgba(0,223,130,.20);
    }

    /* LIMIT */
    .dp-limit{
      display:flex;
      justify-content:space-between;
      gap:12px;
      margin:0 0 16px;
      color:rgba(214,255,240,.78);
      font-size:8.2px;
      font-weight:450;
      line-height:1.35;
    }

    .dp-limit span:last-child{
      text-align:right;
    }

    .dp-error-text{
      min-height:20px;
      text-align:center;
      color:#ff5b75;
      font-size:11.5px;
      font-weight:700;
      margin-bottom:10px;
    }

    .dp-error-text:empty{
      display:none;
    }

    /* BALANCE SMALL */
    .dp-balance{
      margin-bottom:16px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      border-radius:18px;
      border:1px solid rgba(255,255,255,.08);
      background:
        radial-gradient(180px 90px at 96% 0%, rgba(0,223,130,.10), transparent 64%),
        rgba(255,255,255,.04);
      padding:13px 14px;
    }

    .dp-balance-label{
      color:rgba(214,255,240,.62);
      font-size:11.5px;
      font-weight:700;
    }

    .dp-balance-value{
      color:#ffffff;
      font-size:15px;
      font-weight:950;
      letter-spacing:-.025em;
      white-space:nowrap;
    }

    /* HISTORY */
    .dp-history{
      margin-top:20px;
      padding-top:4px;
    }

    .dp-history-head{
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:12px;
      margin-bottom:10px;
    }

    .dp-history-title{
      margin:0;
      color:#ffffff;
      font-size:15px;
      font-weight:900;
      letter-spacing:-.025em;
    }

    .dp-history-sub{
      color:rgba(214,255,240,.55);
      font-size:11px;
      font-weight:700;
    }

    .dp-history-list{
      display:flex;
      flex-direction:column;
      gap:8px;
    }

    .dp-history-item{
      border-radius:16px;
      border:1px solid rgba(255,255,255,.08);
      background:rgba(255,255,255,.045);
      padding:12px;
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
      box-shadow:0 10px 24px rgba(0,0,0,.12);
    }

    .dp-history-id{
      color:rgba(214,255,240,.55);
      font-size:10px;
      font-weight:900;
      letter-spacing:.06em;
      text-transform:uppercase;
    }

    .dp-history-amount{
      margin-top:4px;
      color:#ffffff;
      font-size:13px;
      font-weight:950;
    }

    .dp-history-date{
      margin-top:4px;
      color:rgba(214,255,240,.52);
      font-size:10.5px;
      font-weight:600;
      line-height:1.35;
    }

    .dp-status{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      min-height:24px;
      padding:0 9px;
      border-radius:999px;
      font-size:10px;
      font-weight:950;
      text-transform:uppercase;
      white-space:nowrap;
    }

    .dp-status.is-paid{
      color:#06110d;
      background:linear-gradient(135deg, #00DF82, #8cff2f);
    }

    .dp-status.is-wait{
      color:#fff0c7;
      background:rgba(246,196,83,.12);
      border:1px solid rgba(246,196,83,.22);
    }

    .dp-pay-again{
      margin-top:8px;
      width:100%;
      border:0;
      border-radius:999px;
      min-height:30px;
      padding:0 12px;
      color:#06110d;
      background:linear-gradient(135deg, #00DF82, #72ff9a);
      font-size:11px;
      font-weight:950;
      cursor:pointer;
    }

    .dp-empty{
      border-radius:16px;
      border:1px dashed rgba(255,255,255,.14);
      background:rgba(255,255,255,.035);
      padding:14px;
      color:rgba(214,255,240,.58);
      text-align:center;
      font-size:12px;
      font-weight:700;
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
        linear-gradient(180deg, rgba(3,15,15,0), rgba(3,15,15,.92) 26%, rgba(3,15,15,.98));
      pointer-events:none;
    }

    .dp-submit{
      width:100%;
      min-height:50px;
      border:0;
      border-radius:999px;
      color:#06110d;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.55), transparent 34%),
        linear-gradient(135deg, #00DF82 0%, #79ff99 100%);
      box-shadow:
        0 18px 38px rgba(0,223,130,.24),
        0 0 0 1px rgba(255,255,255,.22) inset;
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
      filter:saturate(.6);
    }

    @media (min-width:768px){
      .dp-page{
        padding:22px 0;
      }

      .dp-phone{
        min-height:calc(100vh - 44px);
        border-radius:26px;
        overflow:hidden;
      }

      .dp-bottom{
        bottom:22px;
        border-radius:0 0 26px 26px;
      }
    }

    @media (max-width:370px){
      .dp-phone{
        padding-left:12px;
        padding-right:12px;
      }

      .dp-title{
        font-size:20px;
      }

      .dp-rp,
      .dp-amount-input{
        font-size:28px;
      }

      .dp-preset{
        font-size:12px;
        padding:0 12px;
      }

      .dp-limit{
        font-size:10.5px;
      }
    }

    .dp-status.is-failed{
  color:#fff;
  background:rgba(255,65,96,.22);
  border:1px solid rgba(255,65,96,.30);
}

.dp-method{
  cursor:pointer;
  transition:transform .16s ease, border-color .16s ease, background .16s ease;
}

.dp-method:hover{
  transform:translateY(-1px);
  border-color:rgba(0,223,130,.24);
  background:rgba(255,255,255,.06);
}

.dp-method-logo{
  width:60px;
  height:50px;
  min-width:60px;
  border-radius:16px;
  display:flex;
  align-items:center;
  justify-content:center;
  background:#ffffff;
  border:1px solid rgba(255,255,255,.22);
  color:#06110d;
  overflow:hidden;
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,.65),
    0 10px 22px rgba(0,0,0,.16);
}

.dp-method-sheet{
  position:fixed;
  inset:0;
  z-index:80;
  display:none;
  align-items:flex-end;
  justify-content:center;
  background:rgba(0,0,0,.58);
  backdrop-filter:blur(10px);
  -webkit-backdrop-filter:blur(10px);
  padding:16px;
}

.dp-method-sheet.is-open{
  display:flex;
}

.dp-method-panel{
  width:100%;
  max-width:430px;
  max-height:82vh;
  overflow:hidden;
  border-radius:28px;
  background:
    radial-gradient(360px 220px at 90% 0%, rgba(0,223,130,.16), transparent 65%),
    linear-gradient(180deg, rgba(9,37,31,.98), rgba(3,15,15,.98));
  border:1px solid rgba(255,255,255,.10);
  box-shadow:0 28px 80px rgba(0,0,0,.55);
}

.dp-method-panel-head{
  display:flex;
  align-items:flex-start;
  justify-content:space-between;
  gap:14px;
  padding:18px 18px 12px;
  border-bottom:1px solid rgba(255,255,255,.08);
}

.dp-method-panel-title{
  margin:0;
  color:#fff;
  font-size:18px;
  font-weight:950;
  letter-spacing:-.035em;
}

.dp-method-panel-sub{
  margin:5px 0 0;
  color:rgba(214,255,240,.58);
  font-size:12px;
  line-height:1.45;
  font-weight:650;
}

.dp-method-close{
  width:36px;
  height:36px;
  border:1px solid rgba(255,255,255,.10);
  border-radius:14px;
  background:rgba(255,255,255,.06);
  color:#fff;
  cursor:pointer;
  display:grid;
  place-items:center;
  flex:0 0 auto;
}

.dp-method-list{
  padding:14px;
  overflow:auto;
  max-height:calc(82vh - 90px);
  display:flex;
  flex-direction:column;
  gap:10px;
}

.dp-method-option{
  width:100%;
  border:1px solid rgba(255,255,255,.09);
  background:rgba(255,255,255,.045);
  border-radius:18px;
  padding:12px;
  display:grid;
  grid-template-columns:58px minmax(0,1fr) 24px;
  align-items:center;
  gap:12px;
  color:#fff;
  text-align:left;
  cursor:pointer;
  transition:transform .16s ease, background .16s ease, border-color .16s ease;
}
.dp-method-option:hover{
  transform:translateY(-1px);
  border-color:rgba(0,223,130,.24);
  background:rgba(0,223,130,.08);
}

.dp-method-option.is-selected{
  border-color:rgba(0,223,130,.50);
  background:rgba(0,223,130,.12);
  box-shadow:0 14px 30px rgba(0,223,130,.10);
}

.dp-method-option-logo{
  width:58px;
  height:50px;
  min-width:58px;
  border-radius:16px;
  display:flex;
  align-items:center;
  justify-content:center;
  color:#06110d;
  background:#ffffff;
  border:1px solid rgba(255,255,255,.22);
  font-size:12px;
  font-weight:950;
  overflow:hidden;
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,.65),
    0 10px 22px rgba(0,0,0,.14);
}

.dp-method-option-title{
  margin:0;
  font-size:14px;
  font-weight:950;
  letter-spacing:-.02em;
}

.dp-method-option-sub{
  margin:4px 0 0;
  color:rgba(214,255,240,.58);
  font-size:11.5px;
  line-height:1.35;
  font-weight:650;
}

.dp-method-check{
  width:22px;
  height:22px;
  border-radius:999px;
  display:grid;
  place-items:center;
  color:#06110d;
  background:linear-gradient(135deg,#00DF82,#79ff99);
  opacity:0;
  transform:scale(.8);
  transition:.16s ease;
}

.dp-method-option.is-selected .dp-method-check{
  opacity:1;
  transform:scale(1);
}

.dp-method-logo{
  overflow:hidden;
}

.dp-method-logo-img,
.dp-method-option-logo-img{
  display:block;
  width:46px;
  height:40px;
  object-fit:contain;
}

.dp-method-logo-fallback,
.dp-method-option-logo-fallback{
  display:none;
  color:#06110d;
  font-size:12px;
  font-weight:950;
}

.dp-method-option-logo{
  overflow:hidden;
}
.dp-method-option-logo-img{
  max-width:48px;
  max-height:40px;
}

.dp-method-logo-img[src=""],
.dp-method-option-logo-img[src=""]{
  display:none;
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

        <h1 class="dp-title">Deposit Saldo</h1>

        <span aria-hidden="true"></span>
      </header>

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
          <span class="dp-fieldset-label">Metode Pembayaran</span>

<button type="button" class="dp-method" id="openMethodSheet">
<div class="dp-method-logo" id="selectedMethodLogoWrap">
  <img
    src="{{ $selectedMethod['logo'] }}"
    alt="{{ $selectedMethod['name'] }}"
    class="dp-method-logo-img"
    id="selectedMethodLogo"
  >
  <span class="dp-method-logo-fallback" id="selectedMethodBadge">{{ $selectedMethod['badge'] }}</span>
</div>

  <div class="dp-method-text">
    <p class="dp-method-title" id="selectedMethodTitle">
      {{ $selectedMethod['name'] }}
    </p>
    <p class="dp-method-sub" id="selectedMethodSub">
      {{ $selectedMethod['desc'] }}
    </p>
  </div>

  <div class="dp-chevron" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none">
      <path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </div>
</button>
        </section>

        <section class="dp-fieldset">
          <span class="dp-fieldset-label">Masukkan Jumlah</span>

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

<div class="dp-method-sheet" id="methodSheet" aria-hidden="true">
  <div class="dp-method-panel">
    <div class="dp-method-panel-head">
      <div>
        <h2 class="dp-method-panel-title">Pilih Metode Pembayaran</h2>
        <p class="dp-method-panel-sub">
          Pilih metode deposit yang tersedia melalui gateway JayaPay.
        </p>
      </div>

      <button type="button" class="dp-method-close" id="closeMethodSheet" aria-label="Tutup">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
          <path d="M18 6 6 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
          <path d="M6 6 18 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
        </svg>
      </button>
    </div>

    <div class="dp-method-list">
@foreach($paymentMethods as $method)
  <button
    type="button"
    class="dp-method-option {{ $selectedMethod['code'] === $method['code'] ? 'is-selected' : '' }}"
    data-code="{{ $method['code'] }}"
    data-api-code="{{ $method['api_code'] }}"
    data-name="{{ $method['name'] }}"
    data-desc="{{ $method['desc'] }}"
   data-badge="{{ $method['badge'] }}"
data-logo="{{ $method['logo'] }}"
  >
  <div class="dp-method-option-logo">
  <img
    src="{{ $method['logo'] }}"
    alt="{{ $method['name'] }}"
    class="dp-method-option-logo-img"
    loading="lazy"
  >
  <span class="dp-method-option-logo-fallback">{{ $method['badge'] }}</span>
</div>

    <div>
      <p class="dp-method-option-title">{{ $method['name'] }}</p>
      <p class="dp-method-option-sub">{{ $method['type'] }} • {{ $method['desc'] }}</p>
    </div>

    <div class="dp-method-check">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
        <path d="m5 12 4 4L19 6" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>
  </button>
@endforeach
    </div>
  </div>
</div>

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
const methodSheet = document.getElementById('methodSheet');
const openMethodSheet = document.getElementById('openMethodSheet');
const closeMethodSheet = document.getElementById('closeMethodSheet');
const selectedMethodBadge = document.getElementById('selectedMethodBadge');
const selectedMethodTitle = document.getElementById('selectedMethodTitle');
const selectedMethodSub = document.getElementById('selectedMethodSub');
const methodOptions = Array.from(document.querySelectorAll('.dp-method-option'));

function showMethodSheet(){
  if(!methodSheet) return;
  methodSheet.classList.add('is-open');
  methodSheet.setAttribute('aria-hidden', 'false');
}

function hideMethodSheet(){
  if(!methodSheet) return;
  methodSheet.classList.remove('is-open');
  methodSheet.setAttribute('aria-hidden', 'true');
}

openMethodSheet?.addEventListener('click', showMethodSheet);
closeMethodSheet?.addEventListener('click', hideMethodSheet);

methodSheet?.addEventListener('click', function(e){
  if(e.target === methodSheet){
    hideMethodSheet();
  }
});

methodOptions.forEach(option => {
  option.addEventListener('click', function(){
    const code = this.dataset.code || 'QRIS';
    const apiCode = this.dataset.apiCode || code;
    const name = this.dataset.name || code;
    const desc = this.dataset.desc || '';
    const badge = this.dataset.badge || code.substring(0, 2);
const logo = this.dataset.logo || '';

    if(methodInput) methodInput.value = apiCode;
    if(selectedChannelInput) selectedChannelInput.value = code;

   const selectedMethodLogo = document.getElementById('selectedMethodLogo');

if(selectedMethodLogo){
  selectedMethodLogo.src = logo;
  selectedMethodLogo.alt = name;
}

if(selectedMethodBadge) selectedMethodBadge.textContent = badge;
    if(selectedMethodTitle) selectedMethodTitle.textContent = name;
    if(selectedMethodSub) selectedMethodSub.textContent = desc;

    methodOptions.forEach(btn => btn.classList.remove('is-selected'));
    this.classList.add('is-selected');

    hideMethodSheet();
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