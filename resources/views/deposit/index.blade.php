{{-- Crowdnik Premium White — Deposit Page (single-card center) --}}
{{-- Source adapted from pasted.txt :contentReference[oaicite:0]{index=0} --}}

@php
  $user = auth()->user();
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Deposit | TumbuhMaju</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <style>
    :root{
      --text:#0f172a;
      --muted:#64748b;
      --border: rgba(15,23,42,.10);
      --card: rgba(255,255,255,.78);
      --card-strong: rgba(255,255,255,.92);
      --shadow: 0 30px 80px rgba(15,23,42,.16);
      --shadow-soft: 0 16px 34px rgba(15,23,42,.10);
      --primary1:#6d28d9;
      --primary2:#06b6d4;
      --radius:22px;
      --radius-sm:16px;
    }

    *{ box-sizing:border-box; }
    html,body{ height:100%; }
    body{
      margin:0;
      color:var(--text);
      font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      background:
        radial-gradient(1100px 600px at 12% 8%, rgba(59,130,246,.18), transparent 60%),
        radial-gradient(900px 520px at 90% 18%, rgba(14,165,233,.14), transparent 55%),
        radial-gradient(900px 520px at 50% 105%, rgba(124,58,237,.10), transparent 60%),
        linear-gradient(180deg, #ffffff 0%, #f5f7ff 55%, #eef2ff 100%);
      min-height:100vh;
      overflow-x:hidden;
    }

    /* ---------- Layout ---------- */
    .wrap{
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:24px 16px;
    }
    .card{
      width:100%;
      max-width: 720px;
      background: linear-gradient(180deg, var(--card) 0%, var(--card-strong) 100%);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      position:relative;
      overflow:hidden;
    }
    .card::before{
      content:"";
      position:absolute;
      inset:-2px;
      background:
        radial-gradient(900px 260px at 10% 0%, rgba(109,40,217,.10), transparent 60%),
        radial-gradient(800px 240px at 90% 10%, rgba(6,182,212,.10), transparent 55%);
      pointer-events:none;
      filter: blur(0px);
    }
    .card-inner{
      position:relative;
      padding:22px;
    }

    /* ---------- Header ---------- */
    .header{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:16px;
      padding:6px 2px 14px;
      border-bottom: 1px solid rgba(15,23,42,.08);
      margin-bottom: 16px;
    }
    .brand{
      display:flex;
      align-items:center;
      gap:14px;
      min-width:0;
    }
    .logoBox{
      width:68px;
      height:68px;
      border-radius: 18px;
      background: rgba(255,255,255,.86);
      border: 1px solid rgba(15,23,42,.10);
      box-shadow: var(--shadow-soft);
      display:flex;
      align-items:center;
      justify-content:center;
      flex: 0 0 auto;
    }
    .logoBox img{
      width:46px;
      height:46px;
      object-fit:contain;
      display:block;
    }
    .titleBlock{
      min-width:0;
      display:flex;
      flex-direction:column;
      gap:2px;
    }
    .title{
      margin:0;
      font-size:18px;
      font-weight:900;
      letter-spacing:-0.02em;
      color: var(--text);
      line-height:1.2;
    }
    .subtitle{
      margin:0;
      font-size:13px;
      color: var(--muted);
      line-height:1.35;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
    }
    .headerActions{
      display:flex;
      gap:10px;
      align-items:center;
      flex:0 0 auto;
    }
    .btnGhost{
      display:inline-flex;
      align-items:center;
      gap:10px;
      padding:10px 12px;
      border-radius: 999px;
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.78);
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
      color: var(--text);
      text-decoration:none;
      font-weight:800;
      font-size:12px;
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
      cursor:pointer;
      user-select:none;
      -webkit-tap-highlight-color: transparent;
    }
    .btnGhost:hover{ transform: translateY(-1px); background: rgba(255,255,255,.92); box-shadow: 0 16px 30px rgba(15,23,42,.10); }
    .btnGhost svg{ width:18px; height:18px; }

    /* ---------- Grid sections ---------- */
    .grid{
      display:grid;
      grid-template-columns: 1.15fr .85fr;
      gap:16px;
      align-items:start;
    }

    /* responsive */
    @media (max-width: 860px){
      .card{ max-width: 760px; }
      .grid{ grid-template-columns: 1fr; }
      .header{ flex-direction: column; align-items: stretch; }
      .headerActions{ width:100%; justify-content:flex-end; }
      .subtitle{ white-space:normal; }
    }
    @media (max-width: 420px){
      .card-inner{ padding:18px; }
      .logoBox{ width:68px; height:68px; border-radius:18px; }
      .title{ font-size:17px; }
      .btnGhost{ width:100%; justify-content:center; }
      .headerActions{ width:100%; }
    }

    /* ---------- Blocks ---------- */
    .block{
      border-radius: var(--radius);
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.72);
      box-shadow: var(--shadow-soft);
      padding:16px;
    }
    .blockTitle{
      margin:0 0 10px 0;
      font-size:12px;
      letter-spacing:.12em;
      text-transform:uppercase;
      color: var(--muted);
      font-weight:900;
    }

    /* ---------- Balance ---------- */
    .balance{
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
      padding:16px;
      border-radius: var(--radius);
      border: 1px solid rgba(15,23,42,.10);
      background:
        radial-gradient(900px 220px at 12% 0%, rgba(109,40,217,.10), transparent 55%),
        radial-gradient(760px 220px at 88% 10%, rgba(6,182,212,.10), transparent 55%),
        rgba(255,255,255,.78);
      box-shadow: var(--shadow-soft);
    }
    .balanceLeft{
      display:flex;
      flex-direction:column;
      gap:6px;
      min-width:0;
    }
    .balanceLabel{
      font-size:12px;
      color: var(--muted);
      font-weight:800;
      letter-spacing:.02em;
    }
    .balanceValue{
      font-size:22px;
      font-weight:1000;
      letter-spacing:-0.02em;
      line-height:1.1;
      color: var(--text);
      margin:0;
      word-break:break-word;
    }
    .chip{
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:7px 10px;
      border-radius: 999px;
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.86);
      color: var(--muted);
      font-size:12px;
      font-weight:800;
      width: fit-content;
    }
    .chip svg{ width:16px; height:16px; opacity:.9; }

    /* ---------- Alerts / Errors ---------- */
    .alert{
      border-radius: var(--radius-sm);
      border: 1px solid rgba(239,68,68,.22);
      background: rgba(239,68,68,.06);
      color: #7f1d1d;
      padding:12px 12px;
      margin-bottom: 12px;
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
    }
    .alertTitle{
      font-weight:1000;
      font-size:13px;
      margin:0 0 6px 0;
      color:#7f1d1d;
    }
    .alert ul{ margin:0; padding-left: 18px; }
    .alert li{ font-size:12px; color:#7f1d1d; line-height:1.5; }

    /* ---------- Form ---------- */
    .formRow{ display:grid; gap:10px; margin-top: 10px; }
    .label{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:10px;
      font-size:12px;
      color: var(--muted);
      font-weight:900;
      letter-spacing:.02em;
    }
    .hint{ font-size:11px; color: var(--muted); font-weight:800; opacity:.9; }

    .field{
      position:relative;
    }
    .prefix{
      position:absolute;
      left:14px;
      top:50%;
      transform: translateY(-50%);
      font-weight:1000;
      color: var(--muted);
      font-size:13px;
      pointer-events:none;
    }
    .input{
      width:100%;
      border-radius: var(--radius-sm);
      border: 1px solid rgba(15,23,42,.12);
      background: rgba(255,255,255,.70);
      padding: 14px 14px 14px 44px;
      font-size: 14px;
      color: var(--text);
      outline:none;
      transition: box-shadow .15s ease, border-color .15s ease, transform .15s ease, background .15s ease;
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
    }
    .input::placeholder{ color: rgba(100,116,139,.85); }
    .input:focus{
      border-color: rgba(6,182,212,.40);
      box-shadow: 0 0 0 4px rgba(6,182,212,.14), 0 14px 30px rgba(15,23,42,.08);
      background: rgba(255,255,255,.86);
    }
    .input.is-invalid{
      border-color: rgba(239,68,68,.38);
      box-shadow: 0 0 0 4px rgba(239,68,68,.10), 0 14px 30px rgba(15,23,42,.08);
    }
    .errorText{
      margin-top:6px;
      font-size:12px;
      color:#b91c1c;
      font-weight:800;
    }

    /* ---------- Method card ---------- */
    .methodCard{
      border-radius: var(--radius-sm);
      border: 1px solid rgba(15,23,42,.12);
      background: rgba(255,255,255,.72);
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
      padding: 12px 12px;
      display:flex;
      gap:12px;
      align-items:flex-start;
      cursor:pointer;
      user-select:none;
      transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease, background .15s ease;
    }
    .methodCard:hover{ transform: translateY(-1px); background: rgba(255,255,255,.86); box-shadow: 0 16px 34px rgba(15,23,42,.10); }
    .methodCard input{ display:none; }
    .radio{
      width:18px;
      height:18px;
      border-radius:999px;
      border: 2px solid rgba(100,116,139,.55);
      margin-top:2px;
      position:relative;
      flex: 0 0 auto;
      background: rgba(255,255,255,.65);
    }
    .methodCard input:checked + .radio{
      border-color: rgba(6,182,212,.70);
      box-shadow: 0 0 0 4px rgba(6,182,212,.12);
    }
    .methodCard input:checked + .radio::after{
      content:"";
      position:absolute;
      inset:3px;
      border-radius:999px;
      background: linear-gradient(135deg, var(--primary1), var(--primary2));
    }
    .methodText{ min-width:0; }
    .methodText strong{
      display:block;
      font-size:13px;
      color: var(--text);
      font-weight:1000;
      margin-bottom:2px;
    }
    .methodText span{
      display:block;
      font-size:12px;
      color: var(--muted);
      line-height:1.45;
    }

    /* ---------- Primary button ---------- */
    .btnPrimary{
      width:100%;
      border:0;
      border-radius: var(--radius-sm);
      padding: 14px 14px;
      font-size: 14px;
      font-weight:1000;
      color: #081022;
      cursor:pointer;
      background: linear-gradient(135deg, var(--primary1) 0%, var(--primary2) 100%);
      box-shadow: 0 18px 42px rgba(109,40,217,.20), 0 14px 34px rgba(6,182,212,.12);
      transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
      position:relative;
      overflow:hidden;
      -webkit-tap-highlight-color: transparent;
    }
    .btnPrimary:hover{ transform: translateY(-1px); filter: saturate(1.06); box-shadow: 0 24px 60px rgba(109,40,217,.22), 0 18px 44px rgba(6,182,212,.14); }
    .btnPrimary:active{ transform: translateY(0px) scale(.99); }
    .btnPrimary::after{
      content:"";
      position:absolute;
      top:0;
      left:-120%;
      width: 60%;
      height:100%;
      background: linear-gradient(to right, transparent, rgba(255,255,255,.32), transparent);
      transform: skewX(-18deg);
      animation: shimmer 3.2s infinite;
      pointer-events:none;
    }
    @keyframes shimmer{
      0%{ left:-120%; }
      18%{ left: 220%; }
      100%{ left: 220%; }
    }

    /* ---------- Notes ---------- */
    .note{
      border-radius: var(--radius);
      border: 1px dashed rgba(15,23,42,.18);
      background: rgba(255,255,255,.68);
      padding: 14px 14px;
      color: var(--muted);
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
    }
    .note strong{
      display:block;
      color: var(--text);
      font-weight:1000;
      margin-bottom:8px;
      font-size:13px;
    }
    .note ol{ margin:0; padding-left: 18px; }
    .note li{ margin: 6px 0; font-size:12px; line-height:1.55; }

    /* ---------- History ---------- */
    .historyList{ display:flex; flex-direction:column; gap:10px; }
    .historyItem{
      display:flex;
      justify-content:space-between;
      align-items:flex-start;
      gap:12px;
      padding: 12px 12px;
      border-radius: 18px;
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.72);
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
    }
    .historyItem:hover{ transform: translateY(-1px); background: rgba(255,255,255,.86); box-shadow: 0 16px 34px rgba(15,23,42,.10); }

    .hLeft{ min-width:0; }
    .hId{
      font-size:11px;
      color: var(--muted);
      font-weight:900;
      letter-spacing:.06em;
      text-transform:uppercase;
    }
    .hAmt{
      margin-top:4px;
      font-size:14px;
      font-weight:1000;
      color: var(--text);
      letter-spacing:-0.01em;
    }
    .hDate{
      margin-top:4px;
      font-size:12px;
      color: var(--muted);
      line-height:1.4;
    }

    .hRight{
      flex:0 0 auto;
      display:flex;
      flex-direction:column;
      align-items:flex-end;
      gap:8px;
      text-align:right;
    }
    .badge{
      padding: 7px 10px;
      border-radius: 999px; /* chip/badge */
      font-size:11px;
      font-weight:1000;
      letter-spacing:.06em;
      text-transform:uppercase;
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.82);
      color: var(--muted);
    }
    .badgePaid{
      border-color: rgba(6,182,212,.22);
      background: rgba(6,182,212,.10);
      color: #075985;
    }
    .badgeWait{
      border-color: rgba(109,40,217,.22);
      background: rgba(109,40,217,.08);
      color: #4c1d95;
    }

    .btnMini{
      border-radius: 999px;
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.78);
      color: var(--text);
      font-weight:1000;
      font-size:12px;
      padding: 8px 10px;
      cursor:pointer;
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
    }
    .btnMini:hover{ transform: translateY(-1px); background: rgba(255,255,255,.92); box-shadow: 0 16px 34px rgba(15,23,42,.10); }
    .btnMini:active{ transform: translateY(0px) scale(.99); }

    .empty{
      border-radius: var(--radius);
      border: 1px dashed rgba(15,23,42,.18);
      background: rgba(255,255,255,.66);
      color: var(--muted);
      padding: 14px;
      font-size:13px;
      text-align:center;
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
    }

    /* small separators */
    .spacer{ height:12px; }

    /* a11y focus */
    a:focus, button:focus, input:focus{ outline:none; }
  </style>
</head>

<body>
  <div class="wrap">
    <main class="card" role="main" aria-label="Deposit Saldo">
      <div class="card-inner">

        <header class="header">
          <div class="brand">
            <div class="logoBox" aria-hidden="true">
              <img src="/logo.png" alt="Logo" />
            </div>
            <div class="titleBlock">
              <h1 class="title">Deposit Saldo</h1>
              <p class="subtitle">Isi ulang saldo cepat via Transfer Bank / QRIS dengan tampilan premium.</p>
            </div>
          </div>

          <div class="headerActions">
            <a class="btnGhost" href="/dashboard" aria-label="Kembali ke Dashboard">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M15 18l-6-6 6-6"></path>
              </svg>
              <span>Kembali</span>
            </a>
          </div>
        </header>

        <section class="grid" aria-label="Konten Deposit">

          {{-- LEFT: Form & Info --}}
          <div style="display:flex; flex-direction:column; gap:16px;">

            <div class="balance" aria-label="Saldo Saat Ini">
              <div class="balanceLeft">
                <div class="balanceLabel">Saldo Anda Saat Ini</div>
                <p class="balanceValue">Rp {{ number_format($user->saldo, 0, ',', '.') }}</p>
                <div class="chip" title="Aturan deposit">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                  </svg>
                  <span>Min 10rb • Max 20jt</span>
                </div>
              </div>
            </div>

            <div class="block" aria-label="Form Isi Ulang">
              <p class="blockTitle">Isi Ulang</p>

              {{-- Global error state --}}
              @if ($errors->any())
                <div class="alert" role="alert" aria-live="polite">
                  <p class="alertTitle">Periksa input Anda</p>
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              <form method="POST" action="/deposit" novalidate>
                @csrf

                <div class="formRow">
                  <div class="label">
                    <span>Nominal</span>
                    <span class="hint">Masukkan jumlah deposit</span>
                  </div>

                  <div class="field">
                    <span class="prefix">Rp</span>
                    <input
                      type="number"
                      name="amount"
                      class="input @error('amount') is-invalid @enderror"
                      placeholder="0"
                      min="10000"
                      required
                      inputmode="numeric"
                      value="{{ old('amount') }}"
                      aria-invalid="@error('amount') true @else false @enderror"
                      aria-describedby="@error('amount') amount-error @enderror"
                    />
                    @error('amount')
                      <div class="errorText" id="amount-error">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="label" style="margin-top:6px;">
                    <span>Metode Pembayaran</span>
                    <span class="hint">Pilih satu metode</span>
                  </div>

                  <label class="methodCard" for="m1">
                    <input id="m1" type="radio" name="method" value="TRANSFER_QRIS" checked />
                    <div class="radio" aria-hidden="true"></div>
                    <div class="methodText">
                      <strong>Transfer Bank &amp; QRIS</strong>
                      <span>Diproses otomatis. Gunakan rekening/QR yang muncul di halaman bayar.</span>
                    </div>
                  </label>

                  <button class="btnPrimary" type="submit">
                    Lanjutkan Pembayaran
                  </button>
                </div>
              </form>
            </div>

            <div class="note" aria-label="Informasi Penting">
              <strong>Informasi Penting</strong>
              <ol>
                <li>Deposit diproses otomatis 24 Jam Nonstop.</li>
                <li>Hindari BI-FAST di jam sibuk untuk mencegah delay.</li>
                <li>Hanya transfer ke rekening yang muncul di halaman bayar.</li>
              </ol>
            </div>

          </div>

          {{-- RIGHT: History --}}
          <aside class="block" aria-label="Riwayat Transaksi">
            <p class="blockTitle">Riwayat Transaksi</p>

            <div class="historyList">
              @forelse($deposits as $d)
                <div class="historyItem">
                  <div class="hLeft">
                    <div class="hId">#{{ $d->order_id }}</div>
                    <div class="hAmt">Rp {{ number_format($d->amount, 0, ',', '.') }}</div>
                    <div class="hDate">{{ $d->created_at ?? 'Baru saja' }}</div>
                  </div>

                  <div class="hRight">
                    @if($d->status === 'PAID')
                      <span class="badge badgePaid">Berhasil</span>
                    @else
                      <span class="badge badgeWait">Menunggu</span>
                      <form method="POST" action="/deposit/callback/{{ $d->order_id }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="btnMini">Bayar</button>
                      </form>
                    @endif
                  </div>
                </div>
              @empty
                <div class="empty">Belum ada riwayat deposit.</div>
              @endforelse
            </div>

          </aside>

        </section>

      </div>
    </main>
  </div>
</body>
</html>
