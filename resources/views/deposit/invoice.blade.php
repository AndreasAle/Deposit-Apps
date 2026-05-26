@include('partials.anti-inspect')
@php
    $qrImageSrc = $qrImageSrc ?? null;

    $payData = null;

    if (!empty($deposit->pay_data)) {
        $payData = json_decode($deposit->pay_data, true);
    }

    $statusText = match($deposit->status) {
        'PAID' => 'Berhasil',
        'FAILED' => 'Gagal',
        default => 'Menunggu',
    };

    $statusClass = match($deposit->status) {
        'PAID' => 'paid',
        'FAILED' => 'failed',
        default => 'waiting',
    };

    $payAmount = (float) ($deposit->real_amount ?: $deposit->amount);

    $displayMethod = $deposit->selected_channel ?: $deposit->method ?: 'QRIS';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Deposit | Velora Finance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">

    <style>
        :root{
            --pay-bg:#f7f2fa;
            --pay-bg-2:#eee8f6;
            --pay-card:#ffffff;
            --pay-card-soft:#fbf8ff;

            --pay-text:#2b0b16;
            --pay-heading:#3a0712;
            --pay-muted:#7b6370;
            --pay-soft:#a894a0;

            --pay-gold:#f5af2a;
            --pay-gold-2:#ffd46d;
            --pay-purple:#8f57ff;
            --pay-pink:#d96bff;
            --pay-green:#21b874;
            --pay-red:#e24a64;

            --pay-border:rgba(43,11,22,.085);
            --pay-border-strong:rgba(43,11,22,.14);

            --pay-gradient:linear-gradient(135deg,#f5af2a 0%,#ffd46d 26%,#d96bff 58%,#8f57ff 100%);
            --pay-gradient-soft:linear-gradient(145deg,#8f57ff 0%,#9f55ff 38%,#d96bff 72%,#f5af2a 100%);

            --pay-shadow:0 24px 60px rgba(88,43,145,.14);
            --pay-shadow-soft:0 14px 34px rgba(43,11,22,.075);
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
            font-family:"Plus Jakarta Sans", Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color:var(--pay-text);
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
            z-index:0;
            background:
                linear-gradient(rgba(43,11,22,.026) 1px, transparent 1px),
                linear-gradient(90deg, rgba(43,11,22,.018) 1px, transparent 1px);
            background-size:32px 32px;
            mask-image:linear-gradient(180deg, rgba(0,0,0,.46), transparent 76%);
            -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.46), transparent 76%);
            opacity:.55;
        }

        a{
            color:inherit;
            text-decoration:none;
        }

        button{
            font-family:inherit;
        }

        .pay-page{
            width:100%;
            min-height:100vh;
            display:flex;
            justify-content:center;
            padding:14px 10px 28px;
            position:relative;
            z-index:1;
        }

        .pay-phone{
            width:100%;
            max-width:430px;
            min-height:100vh;
            padding:8px 4px 20px;
        }

        .pay-header{
            display:grid;
            grid-template-columns:44px 1fr 44px;
            gap:8px;
            align-items:center;
            margin-bottom:16px;
            padding:0 2px;
        }

        .pay-back{
            width:42px;
            height:42px;
            border-radius:999px;
            display:grid;
            place-items:center;
            color:#5b2841;
            background:rgba(255,255,255,.88);
            border:1px solid var(--pay-border);
            box-shadow:0 12px 26px rgba(43,11,22,.065), inset 0 1px 0 rgba(255,255,255,.92);
            backdrop-filter:blur(18px);
            -webkit-backdrop-filter:blur(18px);
            transition:.18s ease;
        }

        .pay-back:hover{
            transform:translateY(-1px);
            color:var(--pay-purple);
        }

        .pay-title{
            margin:0;
            color:var(--pay-heading);
            font-size:22px;
            line-height:1;
            font-weight:800;
            letter-spacing:-.055em;
            text-align:center;
        }

        .pay-alert{
            margin:0 0 14px;
            padding:13px 14px;
            border-radius:22px;
            color:#5b2841;
            background:rgba(255,255,255,.92);
            border:1px solid rgba(245,175,42,.22);
            box-shadow:var(--pay-shadow-soft);
            font-size:12px;
            line-height:1.5;
            font-weight:700;
        }

        .pay-hero{
            position:relative;
            overflow:hidden;
            border-radius:34px;
            padding:18px;
            color:#fff;
            background:
                radial-gradient(360px 220px at 92% -12%, rgba(255,212,109,.48), transparent 58%),
                radial-gradient(300px 200px at 2% 8%, rgba(217,107,255,.34), transparent 62%),
                var(--pay-gradient-soft);
            border:1px solid rgba(255,255,255,.44);
            box-shadow:0 28px 62px rgba(143,87,255,.22), 0 18px 42px rgba(245,175,42,.10), inset 0 1px 0 rgba(255,255,255,.22);
        }

        .pay-hero::before{
            content:"";
            position:absolute;
            inset:0;
            pointer-events:none;
            background:
                linear-gradient(135deg, rgba(255,255,255,.22), transparent 34%),
                radial-gradient(circle at 82% 26%, rgba(255,255,255,.16), transparent 28%),
                linear-gradient(180deg, transparent 0%, rgba(43,11,22,.08) 100%);
        }

        .pay-hero::after{
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

        .pay-hero > *{
            position:relative;
            z-index:1;
        }

        .pay-hero-top{
            display:grid;
            grid-template-columns:minmax(0,1fr) auto;
            gap:14px;
            align-items:start;
        }

        .pay-kicker{
            display:inline-flex;
            align-items:center;
            min-height:27px;
            padding:0 11px;
            border-radius:999px;
            color:#2c1200;
            background:linear-gradient(135deg,#ffe08a,#f5af2a);
            border:1px solid rgba(255,255,255,.32);
            box-shadow:0 12px 22px rgba(245,175,42,.20), inset 0 1px 0 rgba(255,255,255,.38);
            font-size:10px;
            font-weight:800;
            letter-spacing:.09em;
            text-transform:uppercase;
        }

        .pay-label{
            margin:13px 0 8px;
            color:rgba(255,255,255,.75);
            font-size:12px;
            font-weight:700;
        }

        .pay-amount{
            margin:0;
            color:#fff;
            font-size:32px;
            line-height:1.02;
            letter-spacing:-.075em;
            font-weight:800;
            text-shadow:0 12px 28px rgba(43,11,22,.24);
        }

        .pay-status{
            min-height:38px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:0 12px;
            border-radius:999px;
            font-size:10.5px;
            font-weight:800;
            text-transform:uppercase;
            white-space:nowrap;
            border:1px solid rgba(255,255,255,.32);
            box-shadow:0 12px 22px rgba(143,87,255,.14), inset 0 1px 0 rgba(255,255,255,.32);
        }

        .pay-status.waiting{
            color:#2c1200;
            background:linear-gradient(135deg,#ffe08a,#f5af2a);
        }

        .pay-status.paid{
            color:#062e1c;
            background:linear-gradient(135deg,#dfffee,#21b874);
        }

        .pay-status.failed{
            color:#fff;
            background:linear-gradient(135deg,#ff7890,#e24a64);
        }

        .pay-info-grid{
            margin-top:15px;
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:9px;
        }

        .pay-info{
            min-height:60px;
            border-radius:20px;
            padding:11px 12px;
            background:rgba(255,255,255,.11);
            border:1px solid rgba(255,255,255,.14);
            box-shadow:inset 0 1px 0 rgba(255,255,255,.10);
        }

        .pay-info span{
            display:block;
            margin-bottom:7px;
            color:rgba(255,255,255,.62);
            font-size:10px;
            font-weight:700;
        }

        .pay-info strong{
            display:block;
            color:#fff;
            font-size:12.4px;
            line-height:1.2;
            font-weight:800;
            letter-spacing:-.02em;
        }

        .pay-panel{
            margin-top:14px;
            border-radius:30px;
            padding:14px;
            background:
                radial-gradient(260px 140px at 100% 0%, rgba(217,107,255,.12), transparent 62%),
                radial-gradient(240px 130px at 0% 100%, rgba(245,175,42,.10), transparent 60%),
                rgba(255,255,255,.94);
            border:1px solid rgba(43,11,22,.075);
            box-shadow:var(--pay-shadow-soft), inset 0 1px 0 rgba(255,255,255,.94);
            position:relative;
            overflow:hidden;
        }

        .pay-panel::before{
            content:"";
            position:absolute;
            inset:0;
            pointer-events:none;
            background:
                linear-gradient(135deg, rgba(255,255,255,.80), transparent 30%),
                radial-gradient(circle at 12% 0%, rgba(245,175,42,.08), transparent 44%);
        }

        .pay-panel > *{
            position:relative;
            z-index:1;
        }

        .pay-panel-head{
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap:12px;
            margin-bottom:12px;
        }

        .pay-panel-title{
            margin:0;
            color:var(--pay-heading);
            font-size:17px;
            line-height:1.15;
            font-weight:800;
            letter-spacing:-.04em;
        }

        .pay-panel-note{
            margin:6px 0 0;
            color:var(--pay-muted);
            font-size:12px;
            line-height:1.48;
            font-weight:600;
        }

        .pay-pill{
            flex:0 0 auto;
            min-height:30px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:0 11px;
            border-radius:999px;
            color:#2c1200;
            background:var(--pay-gradient);
            font-size:10.5px;
            font-weight:800;
            white-space:nowrap;
            box-shadow:0 12px 24px rgba(143,87,255,.14);
        }

        .qr-card{
            width:100%;
            margin:0 auto;
            border-radius:28px;
            overflow:hidden;
            background:#fff;
            border:1px solid rgba(43,11,22,.08);
            box-shadow:0 18px 40px rgba(43,11,22,.12);
        }

        .qr-brand{
            min-height:52px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
            padding:12px 14px;
            color:#2b0b16;
            background:
                radial-gradient(circle at 30% 0%, rgba(255,255,255,.75), transparent 34%),
                linear-gradient(135deg,#fff3bd,#f3d5ff);
            border-bottom:1px solid rgba(43,11,22,.07);
        }

        .qr-brand strong{
            font-size:13px;
            line-height:1;
            font-weight:800;
            letter-spacing:.16em;
        }

        .qr-brand span{
            font-size:10px;
            color:rgba(43,11,22,.58);
            font-weight:800;
            text-transform:uppercase;
            letter-spacing:.08em;
        }

        .qr-wrap{
            min-height:315px;
            padding:16px;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#fff;
        }

        .qr-image{
            width:100%;
            max-width:292px;
            aspect-ratio:1/1;
            object-fit:contain;
            display:block;
        }

        .qr-empty{
            width:100%;
            min-height:260px;
            border-radius:22px;
            background:#f8fafc;
            color:#334155;
            display:flex;
            align-items:center;
            justify-content:center;
            text-align:center;
            padding:18px;
            font-size:13px;
            font-weight:700;
            line-height:1.5;
        }

        .qr-footer{
            padding:12px 14px 14px;
            color:rgba(43,11,22,.70);
            text-align:center;
            font-size:11px;
            line-height:1.45;
            font-weight:700;
            border-top:1px solid rgba(43,11,22,.07);
            background:#fff;
        }

        .pay-actions{
            margin-top:12px;
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:10px;
        }

        .pay-btn{
            min-height:48px;
            border:0;
            border-radius:999px;
            display:flex;
            align-items:center;
            justify-content:center;
            text-align:center;
            padding:0 14px;
            font-size:12.5px;
            font-weight:800;
            cursor:pointer;
            transition:.18s ease;
        }

        .pay-btn:hover{
            transform:translateY(-1px);
        }

        .pay-btn-primary{
            color:#2c1200;
            background:linear-gradient(135deg,#ffe08a,#f5af2a);
            box-shadow:0 14px 28px rgba(245,175,42,.18);
        }

        .pay-btn-secondary{
            color:var(--pay-heading);
            background:#fbf8ff;
            border:1px solid var(--pay-border);
            box-shadow:inset 0 1px 0 rgba(255,255,255,.86);
        }

        .data-list{
            display:grid;
            gap:8px;
        }

        .data-row{
            display:grid;
            grid-template-columns:minmax(0, .78fr) minmax(0, 1fr);
            gap:10px;
            align-items:start;
            padding:11px 12px;
            border-radius:17px;
            background:#fbf8ff;
            border:1px solid rgba(43,11,22,.07);
        }

        .data-key{
            color:var(--pay-muted);
            font-size:11px;
            font-weight:700;
        }

        .data-value{
            color:var(--pay-heading);
            text-align:right;
            font-size:12px;
            font-weight:800;
            word-break:break-word;
        }

        .copy-btn{
            border:0;
            min-height:30px;
            border-radius:999px;
            padding:0 11px;
            color:#2c1200;
            background:linear-gradient(135deg,#ffe08a,#f5af2a);
            font-size:10.5px;
            font-weight:800;
            cursor:pointer;
            margin-top:7px;
        }

        .success-box{
            margin-top:14px;
            min-height:350px;
            border-radius:30px;
            padding:24px 18px;
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            text-align:center;
            background:
                radial-gradient(260px 140px at 50% 0%, rgba(217,107,255,.14), transparent 62%),
                radial-gradient(260px 140px at 90% 100%, rgba(245,175,42,.12), transparent 64%),
                rgba(255,255,255,.94);
            border:1px solid rgba(43,11,22,.075);
            box-shadow:var(--pay-shadow-soft);
        }

        .success-icon{
            width:72px;
            height:72px;
            margin-bottom:14px;
            border-radius:25px;
            display:grid;
            place-items:center;
            color:#2c1200;
            background:var(--pay-gradient);
            box-shadow:0 18px 36px rgba(143,87,255,.18), inset 0 1px 0 rgba(255,255,255,.32);
        }

        .success-title{
            margin:0 0 8px;
            color:var(--pay-heading);
            font-size:20px;
            line-height:1.15;
            font-weight:800;
            letter-spacing:-.045em;
        }

        .success-text{
            margin:0 0 18px;
            color:var(--pay-muted);
            font-size:12.6px;
            line-height:1.55;
            font-weight:600;
            max-width:310px;
        }

        @media (min-width:768px){
            .pay-page{
                padding:22px 0;
            }

            .pay-phone{
                min-height:calc(100vh - 44px);
            }
        }

        @media (max-width:390px){
            .pay-amount{
                font-size:29px;
            }

            .pay-info-grid{
                grid-template-columns:1fr;
            }

            .pay-actions{
                grid-template-columns:1fr;
            }

            .qr-wrap{
                min-height:292px;
                padding:14px;
            }

            .qr-image{
                max-width:260px;
            }
        }

        @media (max-width:360px){
            .pay-title{
                font-size:20px;
            }

            .pay-hero,
            .pay-panel,
            .success-box{
                border-radius:26px;
                padding:15px;
            }

            .qr-card{
                border-radius:24px;
            }
        }
    </style>
</head>

<body>
    <main class="pay-page">
        <div class="pay-phone">
            <header class="pay-header">
                <a href="{{ route('deposit.index') }}" class="pay-back" aria-label="Kembali">
                    <svg width="21" height="21" viewBox="0 0 24 24" fill="none">
                        <path d="M15 18 9 12l6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>

                <h1 class="pay-title">Pembayaran</h1>
                <span></span>
            </header>

            @if(session('success'))
                <div class="pay-alert">{{ session('success') }}</div>
            @endif

            <section class="pay-hero">
                <div class="pay-hero-top">
                    <div>
                        <span class="pay-kicker">Velora Invoice</span>
                        <p class="pay-label">Total Bayar</p>
                        <h2 class="pay-amount">Rp {{ number_format($payAmount, 0, ',', '.') }}</h2>
                    </div>

                    <div class="pay-status {{ $statusClass }}">{{ $statusText }}</div>
                </div>

                <div class="pay-info-grid">
                    <div class="pay-info">
                        <span>Metode</span>
                        <strong>{{ $displayMethod }}</strong>
                    </div>

                    <div class="pay-info">
                        <span>Batas Bayar</span>
                        <strong>{{ $deposit->expired_at ? $deposit->expired_at->format('d M Y H:i') : '-' }}</strong>
                    </div>
                </div>
            </section>

            @if($deposit->status !== 'PAID')
                <section class="pay-panel">
                    @if(!empty($deposit->pay_url))
                        <div class="pay-panel-head">
                            <div>
                                <h2 class="pay-panel-title">Scan QR Pembayaran</h2>
                                <p class="pay-panel-note">
                                    Scan QR berikut, lalu tunggu status pembayaran diperbarui otomatis.
                                </p>
                            </div>

                            <span class="pay-pill">JayaPay</span>
                        </div>

                        <div class="qr-card">
                            <div class="qr-brand">
                                <strong>VELORA</strong>
                                <span>Secure Payment</span>
                            </div>

                            <div class="qr-wrap">
                                @if(!empty($qrImageSrc))
                                    <img
                                        id="paymentQrImage"
                                        src="{{ $qrImageSrc }}"
                                        alt="QR Pembayaran {{ $displayMethod }}"
                                        class="qr-image"
                                    >
                                @else
                                    <div class="qr-empty">
                                        QR pembayaran belum berhasil dimuat.
                                        <br>
                                        Silakan refresh halaman atau buka halaman asli.
                                    </div>
                                @endif
                            </div>

                            <div class="qr-footer">
                                Gunakan e-wallet atau mobile banking yang mendukung QRIS.
                            </div>
                        </div>

                        <div class="pay-actions">
                            <a href="{{ route('deposit.invoice', $deposit->id) }}" class="pay-btn pay-btn-primary">
                                Refresh Status
                            </a>

                            @if(!empty($qrImageSrc))
                                <button
                                    type="button"
                                    class="pay-btn pay-btn-secondary"
                                    onclick="downloadQrImage()"
                                >
                                    Download QR
                                </button>
                            @else
                                <a href="{{ $displayPayUrl ?? $deposit->pay_url }}" target="_blank" rel="noopener" class="pay-btn pay-btn-secondary">
                                    Buka Halaman Asli
                                </a>
                            @endif
                        </div>
                    @elseif(is_array($payData))
                        <div class="pay-panel-head">
                            <div>
                                <h2 class="pay-panel-title">Data Pembayaran</h2>
                                <p class="pay-panel-note">
                                    Selesaikan pembayaran sesuai data berikut. Pastikan nominal sama dengan total bayar.
                                </p>
                            </div>

                            <span class="pay-pill">{{ $displayMethod }}</span>
                        </div>

                        <div class="data-list">
                            @foreach($payData as $key => $value)
                                <div class="data-row">
                                    <span class="data-key">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                                    <span class="data-value">
                                        {{ $value }}

                                        @if(in_array($key, ['realMoney', 'matchingId', 'custAccNo', 'payeeBankCard', 'transCode']))
                                            <br>
                                            <button type="button" class="copy-btn" onclick="copyText('{{ $value }}')">
                                                Salin
                                            </button>
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <div class="pay-actions">
                            <a href="{{ route('deposit.invoice', $deposit->id) }}" class="pay-btn pay-btn-primary">
                                Refresh Status
                            </a>
                            <a href="{{ route('deposit.index') }}" class="pay-btn pay-btn-secondary">
                                Kembali
                            </a>
                        </div>
                    @else
                        <div class="pay-panel-head">
                            <div>
                                <h2 class="pay-panel-title">Pembayaran Belum Tersedia</h2>
                                <p class="pay-panel-note">
                                    Data pembayaran belum tersedia. Silakan refresh halaman atau hubungi admin.
                                </p>
                            </div>

                            <span class="pay-pill">{{ $displayMethod }}</span>
                        </div>

                        <div class="pay-actions">
                            <a href="{{ route('deposit.invoice', $deposit->id) }}" class="pay-btn pay-btn-primary">
                                Refresh Status
                            </a>
                            <a href="{{ route('deposit.index') }}" class="pay-btn pay-btn-secondary">
                                Kembali
                            </a>
                        </div>
                    @endif
                </section>
            @else
                <section class="success-box">
                    <div class="success-icon" aria-hidden="true">
                        <svg width="34" height="34" viewBox="0 0 24 24" fill="none">
                            <path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>

                    <h2 class="success-title">Deposit Berhasil</h2>
                    <p class="success-text">
                        Pembayaran telah diverifikasi dan saldo sudah masuk otomatis ke akun Velora Anda.
                    </p>

                    <a href="{{ route('deposit.index') }}" class="pay-btn pay-btn-primary">
                        Kembali ke Deposit
                    </a>
                </section>
            @endif
        </div>
    </main>

    <script>
        function copyText(text){
            navigator.clipboard.writeText(text).then(function(){
                alert('Berhasil disalin');
            }).catch(function(){
                const input = document.createElement('input');
                input.value = text;
                document.body.appendChild(input);
                input.select();
                document.execCommand('copy');
                document.body.removeChild(input);
                alert('Berhasil disalin');
            });
        }

        function downloadQrImage(){
            const img = document.getElementById('paymentQrImage');

            if(!img || !img.src){
                alert('QR belum tersedia');
                return;
            }

            const link = document.createElement('a');
            link.href = img.src;
            link.download = 'QR-Deposit-{{ $displayMethod }}-{{ $deposit->order_id }}.png';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        @if($deposit->status !== 'PAID')
            setTimeout(function(){
                window.location.reload();
            }, 20000);
        @endif
    </script>
</body>
</html>
