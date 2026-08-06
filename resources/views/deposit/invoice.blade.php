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

    $payUrl = $displayPayUrl ?? $deposit->pay_url;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Deposit | Capital Wave</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

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
            z-index:0;
            background:
                linear-gradient(rgba(11,39,64,.022) 1px, transparent 1px),
                linear-gradient(90deg, rgba(11,39,64,.016) 1px, transparent 1px);
            background-size:32px 32px;
            mask-image:linear-gradient(180deg, rgba(0,0,0,.36), transparent 76%);
            -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.36), transparent 76%);
            opacity:.7;
        }

        a{ color:inherit; text-decoration:none; }
        button{ font-family:inherit; }

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
            border-radius:14px;
            display:grid;
            place-items:center;
            color:var(--navy);
            background:var(--card);
            border:1px solid var(--line);
            box-shadow:var(--sh-sm);
            transition:.18s ease;
        }

        .pay-back:hover{ transform:translateY(-1px); color:var(--blue); }

        .pay-title{
            margin:0;
            color:var(--navy);
            font-size:20px;
            line-height:1;
            font-weight:800;
            letter-spacing:-.04em;
            text-align:center;
        }

        .pay-alert{
            margin:0 0 14px;
            padding:12px 14px;
            border-radius:16px;
            color:#0d6b45;
            background:var(--green-soft);
            border:1px solid rgba(31,185,122,.28);
            box-shadow:var(--sh-sm);
            font-size:12px;
            line-height:1.5;
            font-weight:600;
        }

        /* HERO */
        .pay-hero{
            position:relative;
            overflow:hidden;
            border-radius:26px;
            padding:18px;
            color:#fff;
            background:
                radial-gradient(420px 240px at 90% -20%, rgba(232,200,116,.20), transparent 62%),
                radial-gradient(360px 220px at 5% 120%, rgba(47,127,212,.24), transparent 60%),
                linear-gradient(150deg,#0f3255 0%,#0b2740 52%,#07182a 100%);
            box-shadow:var(--sh-navy);
        }

        .pay-hero::after{
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

        .pay-hero > *{ position:relative; z-index:1; }

        .pay-hero-top{
            display:grid;
            grid-template-columns:minmax(0,1fr) auto;
            gap:14px;
            align-items:start;
        }

        .pay-kicker{
            display:inline-flex;
            align-items:center;
            gap:6px;
            min-height:26px;
            padding:0 11px;
            border-radius:999px;
            color:var(--gold-lite);
            background:rgba(255,255,255,.08);
            border:1px solid rgba(232,200,116,.32);
            font-size:9.5px;
            font-weight:700;
            letter-spacing:.12em;
            text-transform:uppercase;
        }

        .pay-label{
            margin:13px 0 7px;
            color:rgba(255,255,255,.62);
            font-size:11px;
            font-weight:500;
            letter-spacing:.02em;
        }

        .pay-amount{
            margin:0;
            color:#fff;
            font-size:31px;
            line-height:1.02;
            letter-spacing:-.045em;
            font-weight:700;
            text-shadow:0 12px 28px rgba(3,12,22,.28);
        }

        .pay-status{
            min-height:34px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:0 13px;
            border-radius:999px;
            font-size:10px;
            font-weight:700;
            letter-spacing:.04em;
            text-transform:uppercase;
            white-space:nowrap;
        }

        .pay-status.waiting{ color:var(--navy); background:linear-gradient(135deg,#f0d798,#e8c874); }
        .pay-status.paid{ color:#062e1c; background:linear-gradient(135deg,#8fe6bf,#1fb97a); }
        .pay-status.failed{ color:#fff; background:linear-gradient(135deg,#ec8080,#dc5757); }

        .pay-info-grid{
            margin-top:15px;
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:9px;
        }

        .pay-info{
            min-height:58px;
            border-radius:15px;
            padding:11px 12px;
            background:rgba(255,255,255,.07);
            border:1px solid rgba(255,255,255,.13);
        }

        .pay-info span{
            display:block;
            margin-bottom:6px;
            color:rgba(255,255,255,.58);
            font-size:9.5px;
            font-weight:500;
            text-transform:uppercase;
            letter-spacing:.05em;
        }

        .pay-info strong{
            display:block;
            color:#fff;
            font-size:12.5px;
            line-height:1.2;
            font-weight:700;
            letter-spacing:-.02em;
        }

        /* PANEL */
        .pay-panel{
            margin-top:14px;
            border-radius:22px;
            padding:16px;
            background:var(--card);
            border:1px solid var(--line);
            box-shadow:var(--sh);
            position:relative;
            overflow:hidden;
        }

        .pay-panel::before{
            content:"";
            position:absolute;
            top:0; left:0; right:0; height:3px;
            background:var(--gold-metal);
        }

        .pay-panel-head{
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap:12px;
            margin-bottom:14px;
        }

        .pay-panel-title{
            margin:0;
            color:var(--navy);
            font-size:16px;
            line-height:1.2;
            font-weight:800;
            letter-spacing:-.03em;
        }

        .pay-panel-note{
            margin:6px 0 0;
            color:var(--ink-soft);
            font-size:11.5px;
            line-height:1.5;
            font-weight:500;
        }

        .pay-pill{
            flex:0 0 auto;
            min-height:28px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:0 11px;
            border-radius:999px;
            color:var(--blue);
            background:var(--blue-soft);
            border:1px solid rgba(10,87,163,.16);
            font-size:10px;
            font-weight:700;
            white-space:nowrap;
        }

        /* QR CARD */
        .qr-card{
            width:100%;
            margin:0 auto;
            border-radius:18px;
            overflow:hidden;
            background:#fff;
            border:1px solid var(--line);
            box-shadow:var(--sh-sm);
        }

        .qr-brand{
            min-height:48px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
            padding:12px 14px;
            color:#fff;
            background:linear-gradient(135deg,#123457,#0b2740);
        }

        .qr-brand strong{
            font-size:12px;
            line-height:1;
            font-weight:800;
            letter-spacing:.14em;
        }

        .qr-brand span{
            font-size:9px;
            color:var(--gold-lite);
            font-weight:700;
            text-transform:uppercase;
            letter-spacing:.08em;
        }

        .qr-wrap{
            min-height:300px;
            padding:16px;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#fff;
        }

        .qr-image{
            width:100%;
            max-width:280px;
            aspect-ratio:1/1;
            object-fit:contain;
            display:block;
        }

        .qr-empty{
            width:100%;
            min-height:180px;
            border-radius:16px;
            background:var(--tint);
            color:var(--ink-soft);
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            gap:10px;
            text-align:center;
            padding:20px;
            font-size:12.5px;
            font-weight:600;
            line-height:1.5;
        }

        .qr-empty svg{ width:38px; height:38px; color:var(--gold-deep); }

        .qr-footer{
            padding:12px 14px 14px;
            color:var(--muted);
            text-align:center;
            font-size:10.5px;
            line-height:1.45;
            font-weight:500;
            border-top:1px solid var(--line);
            background:#fff;
        }

        /* ACTIONS */
        .pay-actions{
            margin-top:12px;
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:10px;
        }

        .pay-actions.stack{ grid-template-columns:1fr; }

        .pay-btn{
            min-height:48px;
            border:0;
            border-radius:14px;
            display:flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            text-align:center;
            padding:0 14px;
            font-size:12.5px;
            font-weight:700;
            cursor:pointer;
            transition:.18s ease;
        }

        .pay-btn svg{ width:17px; height:17px; }
        .pay-btn:hover{ transform:translateY(-1px); }

        .pay-btn-primary{
            color:#fff;
            background:linear-gradient(135deg,#123457,#0b2740);
            box-shadow:0 12px 26px rgba(11,39,64,.24);
        }

        .pay-btn-gold{
            color:var(--navy);
            background:var(--gold-metal);
            box-shadow:0 12px 24px rgba(201,148,51,.26);
        }

        .pay-btn-secondary{
            color:var(--ink-soft);
            background:var(--card);
            border:1px solid var(--line);
            box-shadow:var(--sh-sm);
        }

        /* DATA LIST */
        .data-list{ display:grid; gap:8px; }

        .data-row{
            display:grid;
            grid-template-columns:minmax(0, .78fr) minmax(0, 1fr);
            gap:10px;
            align-items:start;
            padding:11px 12px;
            border-radius:13px;
            background:var(--tint);
            border:1px solid var(--line);
        }

        .data-key{ color:var(--muted); font-size:11px; font-weight:600; }
        .data-value{ color:var(--navy); text-align:right; font-size:12px; font-weight:700; word-break:break-word; }

        .copy-btn{
            border:0;
            min-height:28px;
            border-radius:999px;
            padding:0 11px;
            color:var(--navy);
            background:var(--gold-soft);
            border:1px solid rgba(201,148,51,.3);
            font-size:10px;
            font-weight:700;
            cursor:pointer;
            margin-top:7px;
        }

        /* SUCCESS */
        .success-box{
            margin-top:14px;
            min-height:340px;
            border-radius:22px;
            padding:26px 20px;
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            text-align:center;
            background:var(--card);
            border:1px solid var(--line);
            box-shadow:var(--sh);
            position:relative;
            overflow:hidden;
        }

        .success-box::before{
            content:"";
            position:absolute;
            top:0; left:0; right:0; height:3px;
            background:linear-gradient(90deg,#1fb97a,#3fd39a);
        }

        .success-icon{
            width:70px;
            height:70px;
            margin-bottom:16px;
            border-radius:22px;
            display:grid;
            place-items:center;
            color:#fff;
            background:linear-gradient(135deg,#1fb97a,#159e66);
            box-shadow:0 16px 32px rgba(31,185,122,.26);
        }

        .success-title{
            margin:0 0 8px;
            color:var(--navy);
            font-size:20px;
            line-height:1.15;
            font-weight:800;
            letter-spacing:-.04em;
        }

        .success-text{
            margin:0 0 18px;
            color:var(--ink-soft);
            font-size:12.5px;
            line-height:1.55;
            font-weight:500;
            max-width:310px;
        }

        @media (min-width:768px){
            .pay-page{ padding:22px 0; }
            .pay-phone{ min-height:calc(100vh - 44px); }
        }

        @media (max-width:390px){
            .pay-amount{ font-size:28px; }
            .pay-info-grid{ grid-template-columns:1fr; }
            .pay-actions{ grid-template-columns:1fr; }
            .qr-wrap{ min-height:280px; padding:14px; }
            .qr-image{ max-width:250px; }
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
                        <span class="pay-kicker">Capital Wave Invoice</span>
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
                    @if(!empty($qrImageSrc))
                        {{-- QRIS lokal tersedia --}}
                        <div class="pay-panel-head">
                            <div>
                                <h2 class="pay-panel-title">Scan QR Pembayaran</h2>
                                <p class="pay-panel-note">Scan QR berikut pakai e-wallet/m-banking. Status diperbarui otomatis.</p>
                            </div>
                            <span class="pay-pill">{{ $displayMethod }}</span>
                        </div>

                        <div class="qr-card">
                            <div class="qr-brand">
                                <strong>CAPITAL WAVE</strong>
                                <span>Secure Payment</span>
                            </div>
                            <div class="qr-wrap">
                                <img id="paymentQrImage" src="{{ $qrImageSrc }}" alt="QR Pembayaran {{ $displayMethod }}" class="qr-image">
                            </div>
                            <div class="qr-footer">Gunakan aplikasi yang mendukung QRIS (DANA, OVO, GoPay, ShopeePay, m-banking).</div>
                        </div>

                        <div class="pay-actions">
                            <a href="{{ route('deposit.invoice', $deposit->id) }}" class="pay-btn pay-btn-primary">
                                <svg viewBox="0 0 24 24" fill="none"><path d="M21 12a9 9 0 1 1-2.6-6.4M21 4v5h-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                Refresh Status
                            </a>
                            <button type="button" class="pay-btn pay-btn-secondary" onclick="downloadQrImage()">
                                <svg viewBox="0 0 24 24" fill="none"><path d="M12 3v12m0 0 4-4m-4 4-4-4M5 21h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                Download QR
                            </button>
                        </div>

                    @elseif(!empty($payUrl))
                        {{-- Tidak ada QR lokal, tapi ada checkout URL (mis. sandbox / DANA) --}}
                        <div class="pay-panel-head">
                            <div>
                                <h2 class="pay-panel-title">Lanjutkan Pembayaran</h2>
                                <p class="pay-panel-note">Buka halaman pembayaran untuk menyelesaikan transaksi via {{ $displayMethod }}.</p>
                            </div>
                            <span class="pay-pill">{{ $displayMethod }}</span>
                        </div>

                        <div class="qr-card">
                            <div class="qr-brand">
                                <strong>CAPITAL WAVE</strong>
                                <span>Secure Payment</span>
                            </div>
                            <div class="qr-wrap">
                                <div class="qr-empty">
                                    <svg viewBox="0 0 24 24" fill="none"><path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h3v3h-3zM20 14v6h-3M17 20h3" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                                    Pembayaran diselesaikan di halaman pembayaran resmi.
                                </div>
                            </div>
                            <div class="qr-footer">Kamu akan diarahkan ke halaman pembayaran yang aman.</div>
                        </div>

                        <div class="pay-actions">
                            <a href="{{ $payUrl }}" target="_blank" rel="noopener" class="pay-btn pay-btn-gold">
                                <svg viewBox="0 0 24 24" fill="none"><path d="M14 3h7v7M21 3l-9 9M10 5H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                Buka Halaman Pembayaran
                            </a>
                            <a href="{{ route('deposit.invoice', $deposit->id) }}" class="pay-btn pay-btn-secondary">
                                <svg viewBox="0 0 24 24" fill="none"><path d="M21 12a9 9 0 1 1-2.6-6.4M21 4v5h-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                Refresh Status
                            </a>
                        </div>

                    @elseif(is_array($payData))
                        {{-- Data pembayaran manual (VA dsb) --}}
                        <div class="pay-panel-head">
                            <div>
                                <h2 class="pay-panel-title">Data Pembayaran</h2>
                                <p class="pay-panel-note">Selesaikan pembayaran sesuai data berikut. Pastikan nominal sama dengan total bayar.</p>
                            </div>
                            <span class="pay-pill">{{ $displayMethod }}</span>
                        </div>

                        <div class="data-list">
                            @foreach($payData as $key => $value)
                                @if(is_scalar($value))
                                    <div class="data-row">
                                        <span class="data-key">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                                        <span class="data-value">
                                            {{ $value }}
                                            @if(in_array($key, ['realMoney', 'matchingId', 'custAccNo', 'payeeBankCard', 'transCode']))
                                                <br>
                                                <button type="button" class="copy-btn" onclick="copyText('{{ $value }}')">Salin</button>
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <div class="pay-actions">
                            <a href="{{ route('deposit.invoice', $deposit->id) }}" class="pay-btn pay-btn-primary">Refresh Status</a>
                            <a href="{{ route('deposit.index') }}" class="pay-btn pay-btn-secondary">Kembali</a>
                        </div>

                    @else
                        {{-- Fallback --}}
                        <div class="pay-panel-head">
                            <div>
                                <h2 class="pay-panel-title">Pembayaran Belum Tersedia</h2>
                                <p class="pay-panel-note">Data pembayaran belum tersedia. Silakan refresh halaman atau hubungi admin.</p>
                            </div>
                            <span class="pay-pill">{{ $displayMethod }}</span>
                        </div>

                        <div class="pay-actions">
                            <a href="{{ route('deposit.invoice', $deposit->id) }}" class="pay-btn pay-btn-primary">Refresh Status</a>
                            <a href="{{ route('deposit.index') }}" class="pay-btn pay-btn-secondary">Kembali</a>
                        </div>
                    @endif
                </section>
            @else
                <section class="success-box">
                    <div class="success-icon" aria-hidden="true">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                            <path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>

                    <h2 class="success-title">Deposit Berhasil</h2>
                    <p class="success-text">Pembayaran telah diverifikasi dan saldo sudah masuk otomatis ke akun Capital Wave Anda.</p>

                    <a href="{{ route('deposit.index') }}" class="pay-btn pay-btn-primary" style="padding:0 26px;">
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
            if(!img || !img.src){ alert('QR belum tersedia'); return; }
            const link = document.createElement('a');
            link.href = img.src;
            link.download = 'QR-Deposit-{{ $displayMethod }}-{{ $deposit->order_id }}.png';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        @if($deposit->status !== 'PAID')
            setTimeout(function(){ window.location.reload(); }, 20000);
        @endif
    </script>
</body>
</html>
