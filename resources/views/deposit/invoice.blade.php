@php
    $qrImageSrc = $qrImageSrc ?? null;

    $payData = null;

    if (!empty($deposit->pay_data)) {
        $payData = json_decode($deposit->pay_data, true);
    }

    $statusText = match($deposit->status) {
        'PAID' => 'Berhasil',
        'FAILED' => 'Gagal',
        default => 'Menunggu Pembayaran',
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
    <title>Invoice Deposit | Rubik Company</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        :root{
            --bg:#030f0f;
            --bg-2:#061b17;
            --panel:#081f1b;
            --panel-2:#0b2c25;
            --line:rgba(255,255,255,.08);
            --line-2:rgba(255,255,255,.12);
            --text:#f5fffb;
            --muted:rgba(214,255,240,.68);
            --muted-2:rgba(214,255,240,.52);
            --green:#00df82;
            --green-2:#79ff99;
            --warn:#f6c453;
            --warn-soft:rgba(246,196,83,.14);
            --danger:#ff5371;
            --danger-soft:rgba(255,83,113,.18);
            --shadow:0 24px 60px rgba(0,0,0,.30);
        }

        *{
            box-sizing:border-box;
        }

        html,
        body{
            margin:0;
            min-height:100%;
            font-family:Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(520px 320px at 15% 0%, rgba(0,223,130,.16), transparent 60%),
                radial-gradient(420px 220px at 100% 10%, rgba(0,223,130,.10), transparent 60%),
                linear-gradient(180deg,#061b17,#030f0f 60%,#020807);
            color:var(--text);
        }

        a{
            text-decoration:none;
            color:inherit;
        }

        button{
            font-family:inherit;
        }

        .page{
            width:100%;
            min-height:100vh;
            display:flex;
            justify-content:center;
        }

        .phone{
            width:100%;
            max-width:430px;
            min-height:100vh;
            padding:16px 14px 30px;
            background:
                radial-gradient(260px 180px at 85% 6%, rgba(0,223,130,.10), transparent 65%),
                linear-gradient(180deg, rgba(7,31,27,.98), rgba(3,15,15,.98));
            box-shadow:0 0 0 1px rgba(255,255,255,.03);
        }

        .header{
            display:grid;
            grid-template-columns:44px 1fr 44px;
            align-items:center;
            gap:8px;
            margin-bottom:16px;
        }

        .back{
            width:38px;
            height:38px;
            border-radius:14px;
            display:grid;
            place-items:center;
            background:rgba(255,255,255,.06);
            border:1px solid rgba(255,255,255,.10);
            box-shadow:0 10px 24px rgba(0,0,0,.16);
        }

        .title{
            text-align:center;
            font-size:22px;
            font-weight:950;
            letter-spacing:-.04em;
        }

        .alert{
            margin-bottom:14px;
            padding:13px 14px;
            border-radius:18px;
            color:#fff0c7;
            background:linear-gradient(135deg, rgba(246,196,83,.12), rgba(246,196,83,.06));
            border:1px solid rgba(246,196,83,.20);
            font-size:12px;
            line-height:1.5;
            font-weight:800;
        }

        .trust-strip{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
            padding:12px 14px;
            border-radius:18px;
            margin-bottom:14px;
            background:
                radial-gradient(160px 100px at 0% 0%, rgba(0,223,130,.10), transparent 70%),
                linear-gradient(180deg, rgba(8,34,29,.96), rgba(6,24,21,.96));
            border:1px solid var(--line);
            box-shadow:var(--shadow);
        }

        .trust-left{
            min-width:0;
        }

        .trust-kicker{
            font-size:10.5px;
            font-weight:900;
            letter-spacing:.12em;
            text-transform:uppercase;
            color:rgba(214,255,240,.50);
        }

        .trust-title{
            margin-top:3px;
            font-size:13.5px;
            font-weight:900;
            color:#fff;
            line-height:1.3;
        }

        .trust-badge{
            flex:0 0 auto;
            min-height:28px;
            padding:0 12px;
            border-radius:999px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            color:#06110d;
            background:linear-gradient(135deg,var(--green),var(--green-2));
            font-size:11px;
            font-weight:950;
            box-shadow:0 12px 28px rgba(0,223,130,.18);
        }

        .card{
            border-radius:24px;
            border:1px solid var(--line);
            background:
                radial-gradient(280px 160px at 90% 0%,rgba(0,223,130,.12),transparent 62%),
                linear-gradient(180deg,rgba(9,37,31,.94),rgba(5,20,17,.96));
            box-shadow:var(--shadow);
            overflow:hidden;
        }

        .card-top{
            padding:22px 18px 18px;
            border-bottom:1px solid rgba(255,255,255,.06);
        }

        .label{
            color:var(--muted);
            font-size:12px;
            font-weight:800;
            margin-bottom:8px;
        }

        .amount{
            font-size:33px;
            line-height:1;
            font-weight:950;
            letter-spacing:-.055em;
            margin-bottom:14px;
        }

        .status{
            display:inline-flex;
            min-height:31px;
            align-items:center;
            justify-content:center;
            padding:0 12px;
            border-radius:999px;
            font-size:11px;
            font-weight:950;
            text-transform:uppercase;
            letter-spacing:.02em;
        }

        .status.waiting{
            color:#fff0c7;
            background:var(--warn-soft);
            border:1px solid rgba(246,196,83,.22);
        }

        .status.paid{
            color:#06110d;
            background:linear-gradient(135deg,var(--green),#8cff2f);
        }

        .status.failed{
            color:#fff;
            background:var(--danger-soft);
            border:1px solid rgba(255,83,113,.24);
        }

        .rows{
            padding:8px 18px 18px;
        }

        .row{
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap:14px;
            padding:13px 0;
            border-bottom:1px solid rgba(255,255,255,.06);
        }

        .row:last-child{
            border-bottom:0;
        }

        .row span:first-child{
            color:var(--muted);
            font-size:12px;
            font-weight:700;
        }

        .row span:last-child{
            color:#fff;
            text-align:right;
            font-size:12.5px;
            font-weight:900;
            max-width:220px;
            word-break:break-word;
        }

        .pay-box{
            margin-top:16px;
            border-radius:24px;
            padding:16px;
            background:
                radial-gradient(260px 160px at 80% 0%, rgba(0,223,130,.10), transparent 62%),
                rgba(255,255,255,.04);
            border:1px solid var(--line);
            box-shadow:var(--shadow);
        }

        .pay-header{
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap:12px;
            margin-bottom:12px;
        }

        .pay-title{
            margin:0;
            font-size:17px;
            font-weight:950;
            letter-spacing:-.03em;
        }

        .pay-sub{
            margin:6px 0 0;
            color:var(--muted);
            font-size:12.5px;
            line-height:1.55;
            font-weight:650;
        }

        .official-pill{
            flex:0 0 auto;
            min-height:28px;
            padding:0 11px;
            border-radius:999px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            background:rgba(255,255,255,.06);
            border:1px solid rgba(255,255,255,.10);
            color:#fff;
            font-size:10.5px;
            font-weight:900;
        }

        .pay-meta{
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:8px;
            margin-bottom:14px;
        }

        .pay-meta-item{
            border-radius:16px;
            padding:11px 10px;
            background:rgba(255,255,255,.045);
            border:1px solid rgba(255,255,255,.07);
            text-align:center;
        }

        .pay-meta-label{
            font-size:10px;
            font-weight:800;
            color:rgba(214,255,240,.50);
            text-transform:uppercase;
            letter-spacing:.08em;
            margin-bottom:5px;
        }

        .pay-meta-value{
            font-size:12px;
            font-weight:900;
            color:#fff;
            line-height:1.35;
        }

        .qris-shell{
            border-radius:24px;
            padding:14px;
            background:
                linear-gradient(180deg, rgba(255,255,255,.05), rgba(255,255,255,.025));
            border:1px solid rgba(255,255,255,.08);
            box-shadow:inset 0 1px 0 rgba(255,255,255,.04);
        }

        .qris-top-note{
            text-align:center;
            color:#ffdd9f;
            font-size:11px;
            font-weight:800;
            line-height:1.45;
            margin-bottom:12px;
        }

        .qris-card{
            width:100%;
            max-width:330px;
            margin:0 auto;
            border-radius:26px;
            background:#ffffff;
            overflow:hidden;
            box-shadow:0 18px 40px rgba(0,0,0,.22);
            border:1px solid rgba(0,0,0,.06);
        }

        .qris-image-wrap{
            width:100%;
            min-height:320px;
            padding:18px;
            background:#ffffff;
            display:flex;
            align-items:center;
            justify-content:center;
        }

        .qris-image{
            display:block;
            width:100%;
            max-width:288px;
            aspect-ratio:1 / 1;
            object-fit:contain;
            image-rendering:auto;
        }

        .qris-fallback-box{
            width:100%;
            min-height:260px;
            border-radius:20px;
            background:#f8fafc;
            color:#0f172a;
            display:flex;
            align-items:center;
            justify-content:center;
            text-align:center;
            padding:18px;
            font-size:13px;
            font-weight:800;
            line-height:1.5;
        }

        .secure-note{
            margin-top:14px;
            padding:13px 14px;
            border-radius:18px;
            background:rgba(0,223,130,.08);
            border:1px solid rgba(0,223,130,.14);
            color:#d9fff0;
            font-size:12px;
            line-height:1.6;
            font-weight:700;
        }

        .secure-note strong{
            color:#fff;
        }

        .action-grid{
            margin-top:14px;
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:10px;
        }

        .btn{
            min-height:46px;
            border:0;
            border-radius:999px;
            display:flex;
            align-items:center;
            justify-content:center;
            text-align:center;
            padding:0 14px;
            font-size:12.5px;
            font-weight:950;
        }

        .btn-primary{
            color:#06110d;
            background:linear-gradient(135deg,var(--green),var(--green-2));
            box-shadow:0 18px 38px rgba(0,223,130,.20);
        }

        .btn-secondary{
            color:#fff;
            background:rgba(255,255,255,.06);
            border:1px solid rgba(255,255,255,.10);
        }

        .steps{
            margin-top:14px;
            border-radius:20px;
            padding:14px;
            background:rgba(255,255,255,.035);
            border:1px solid rgba(255,255,255,.06);
        }

        .steps-title{
            margin:0 0 10px;
            font-size:13px;
            font-weight:900;
            color:#fff;
        }

        .steps ol{
            margin:0;
            padding-left:18px;
            color:var(--muted);
        }

        .steps li{
            margin:0 0 8px;
            font-size:12px;
            line-height:1.55;
            font-weight:650;
        }

        .steps li:last-child{
            margin-bottom:0;
        }

        .success-box{
            margin-top:16px;
            border-radius:24px;
            padding:18px 16px;
            background:
                radial-gradient(220px 130px at 90% 0%, rgba(0,223,130,.12), transparent 65%),
                rgba(0,223,130,.08);
            border:1px solid rgba(0,223,130,.18);
            box-shadow:var(--shadow);
        }

        .success-title{
            margin:0 0 6px;
            font-size:17px;
            font-weight:950;
        }

        .success-text{
            margin:0 0 14px;
            color:var(--muted);
            font-size:12.5px;
            line-height:1.55;
            font-weight:650;
        }

        .copy-btn{
            border:0;
            border-radius:999px;
            padding:8px 12px;
            color:#06110d;
            background:linear-gradient(135deg,#00DF82,#79ff99);
            font-size:11px;
            font-weight:950;
            cursor:pointer;
            margin-top:7px;
        }

        @media (max-width:390px){
            .amount{
                font-size:30px;
            }

            .pay-meta{
                grid-template-columns:1fr;
            }

            .action-grid{
                grid-template-columns:1fr;
            }

            .qris-card{
                max-width:315px;
            }

            .qris-image-wrap{
                min-height:305px;
                padding:16px;
            }

            .qris-image{
                max-width:275px;
            }
        }

        @media (max-width:360px){
            .qris-card{
                max-width:295px;
            }

            .qris-image-wrap{
                min-height:285px;
                padding:14px;
            }

            .qris-image{
                max-width:255px;
            }
        }
    </style>
</head>

<body>
    <main class="page">
        <div class="phone">
            <header class="header">
                <a href="{{ route('deposit.index') }}" class="back" aria-label="Kembali">
                    <svg width="21" height="21" viewBox="0 0 24 24" fill="none">
                        <path d="M15 18 9 12l6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>

                <div class="title">Invoice Deposit</div>
                <span></span>
            </header>

            @if(session('success'))
                <div class="alert">{{ session('success') }}</div>
            @endif



            <section class="card">
                <div class="card-top">
                    <div class="label">Total Deposit</div>
                    <div class="amount">Rp {{ number_format($payAmount, 0, ',', '.') }}</div>
                    <div class="status {{ $statusClass }}">{{ $statusText }}</div>
                </div>

                <div class="rows">
                    <div class="row">
                        <span>Order ID</span>
                        <span>{{ $deposit->order_id }}</span>
                    </div>

                    <div class="row">
                        <span>Platform Order</span>
                        <span>{{ $deposit->plat_order_num ?: '-' }}</span>
                    </div>

                    <div class="row">
                        <span>Metode</span>
                        <span>{{ $displayMethod }}</span>
                    </div>

                    <div class="row">
                        <span>Nominal Awal</span>
                        <span>Rp {{ number_format((float) $deposit->amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="row">
                        <span>Biaya</span>
                        <span>Rp {{ number_format((float) $deposit->pay_fee, 0, ',', '.') }}</span>
                    </div>

                    <div class="row">
                        <span>Expired</span>
                        <span>{{ $deposit->expired_at ? $deposit->expired_at->format('d M Y H:i') : '-' }}</span>
                    </div>
                </div>
            </section>

            @if($deposit->status !== 'PAID')
                <section class="pay-box">

                    <div class="pay-meta">
                        <div class="pay-meta-item">
                            <div class="pay-meta-label">Gateway</div>
                            <div class="pay-meta-value">JayaPay</div>
                        </div>

                        <div class="pay-meta-item">
                            <div class="pay-meta-label">Metode</div>
                            <div class="pay-meta-value">{{ $displayMethod }}</div>
                        </div>

                        <div class="pay-meta-item">
                            <div class="pay-meta-label">Total Bayar</div>
                            <div class="pay-meta-value">Rp {{ number_format($payAmount, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    @if(!empty($deposit->pay_url))
                        <div class="qris-shell">
                            <div class="qris-top-note">
                                Layanan pembayaran 24 jam • pastikan nominal dan status pembayaran sesuai
                            </div>

                            <div class="qris-card">
                                <div class="qris-image-wrap">
                                    @if(!empty($qrImageSrc))
                                    <img
                                        id="paymentQrImage"
                                        src="{{ $qrImageSrc }}"
                                        alt="QR Pembayaran {{ $displayMethod }}"
                                        class="qris-image"
                                    >
                                    @else
                                        <div class="qris-fallback-box">
                                            QR pembayaran belum berhasil dimuat.
                                            <br>
                                            Silakan refresh halaman atau buka halaman asli.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>


                        <div class="action-grid">
                            <a href="{{ route('deposit.invoice', $deposit->id) }}" class="btn btn-primary">
                                Refresh Status
                            </a>

                        @if(!empty($qrImageSrc))
                            <button
                                type="button"
                                class="btn btn-secondary"
                                onclick="downloadQrImage()"
                            >
                                Download QR
                            </button>
                        @else
                            <a href="{{ $displayPayUrl ?? $deposit->pay_url }}" target="_blank" rel="noopener" class="btn btn-secondary">
                                Buka Halaman Asli
                            </a>
                        @endif
                        </div>

                        <div class="steps">
                            <h3 class="steps-title">Langkah pembayaran</h3>
                            <ol>
                                <li>Buka aplikasi e-wallet atau mobile banking yang mendukung QRIS.</li>
                                <li>Scan kode QR pada invoice ini.</li>
                                <li>Pastikan nominal pembayaran sesuai.</li>
                                <li>Selesaikan pembayaran, lalu kembali ke halaman ini untuk cek status.</li>
                            </ol>
                        </div>
                    @elseif(is_array($payData))
                        <p class="pay-sub">
                            Silakan lakukan pembayaran sesuai detail di bawah. Pastikan nominal dibayar sesuai instruksi.
                        </p>

                        <div class="steps">
                            @foreach($payData as $key => $value)
                                <div class="row">
                                    <span>{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                                    <span>
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
                    @else
                        <div class="secure-note">
                            Data pembayaran belum tersedia. Silakan hubungi admin untuk bantuan lebih lanjut.
                        </div>
                    @endif
                </section>
            @else
                <section class="success-box">
                    <h2 class="success-title">Deposit Berhasil</h2>
                    <p class="success-text">
                        Pembayaran telah berhasil diverifikasi dan saldo sudah masuk otomatis ke akun Anda.
                    </p>

                    <a href="{{ route('deposit.index') }}" class="btn btn-primary">
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

        @if($deposit->status !== 'PAID')
            setTimeout(function(){
                window.location.reload();
            }, 20000);
        @endif
    </script>

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