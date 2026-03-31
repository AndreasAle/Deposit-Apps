<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Rincian Saldo</title>
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

    .wrap{
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:24px 16px;
    }

    .card{
      width:100%;
      max-width: 980px;
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
    }
    .card-inner{ position:relative; padding:22px; }

    .header{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:16px;
      padding:6px 2px 14px;
      border-bottom: 1px solid rgba(15,23,42,.08);
      margin-bottom: 16px;
    }
    .brand{ display:flex; align-items:center; gap:14px; min-width:0; }
    .logoBox{
      width:68px;height:68px;border-radius:18px;
      background: rgba(255,255,255,.86);
      border: 1px solid rgba(15,23,42,.10);
      box-shadow: var(--shadow-soft);
      display:flex;align-items:center;justify-content:center;
      flex:0 0 auto;
    }
    .logoBox img{ width:46px;height:46px;object-fit:contain;display:block; }
    .titleBlock{ min-width:0; display:flex; flex-direction:column; gap:2px; }
    .title{
      margin:0;font-size:18px;font-weight:1000;
      letter-spacing:-0.02em;line-height:1.2;color:var(--text);
    }
    .subtitle{ margin:0;font-size:13px;color:var(--muted);line-height:1.35; }

    .btnGhost{
      display:inline-flex;align-items:center;gap:10px;
      padding:10px 12px;border-radius:999px;
      border:1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.78);
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
      color: var(--text);
      text-decoration:none;
      font-weight:900;font-size:12px;
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
      white-space:nowrap;
    }
    .btnGhost:hover{ transform:translateY(-1px); background: rgba(255,255,255,.92); box-shadow:0 16px 30px rgba(15,23,42,.10); }
    .btnGhost svg{ width:18px;height:18px; }

    @media (max-width: 860px){
      .header{ flex-direction:column; align-items:stretch; }
      .headerActions{ width:100%; justify-content:flex-end; display:flex; gap:10px; }
    }
    @media (max-width: 420px){
      .card-inner{ padding:18px; }
      .btnGhost{ width:100%; justify-content:center; }
      .headerActions{ width:100%; }
    }

    .toolbar{
      display:flex;
      gap:10px;
      flex-wrap:wrap;
      align-items:center;
      justify-content:space-between;
      margin-bottom: 12px;
    }

    .chips{ display:flex; gap:10px; flex-wrap:wrap; }
    .chip{
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:8px 12px;
      border-radius: 999px;
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.78);
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
      color: var(--text);
      text-decoration:none;
      font-size:12px;
      font-weight:1000;
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
    }
    .chip:hover{ transform: translateY(-1px); background: rgba(255,255,255,.92); box-shadow: 0 16px 34px rgba(15,23,42,.10); }
    .chipActive{
      border-color: rgba(6,182,212,.22);
      background: rgba(6,182,212,.10);
      color:#075985;
    }

    .list{
      display:flex;
      flex-direction:column;
      gap:12px;
    }

    .rowItem{
      border-radius: 18px;
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.72);
      box-shadow: var(--shadow-soft);
      overflow:hidden;
      display:flex;
      min-height: 62px;
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
    }
    .rowItem:hover{
      transform: translateY(-1px);
      background: rgba(255,255,255,.86);
      box-shadow: 0 22px 48px rgba(15,23,42,.12);
    }

    .bar{
      width:4px;
      flex:0 0 auto;
    }
    .barDeposit{ background: rgba(6,182,212,.85); }
    .barInvest{ background: rgba(239,68,68,.85); }

    .rowBody{
      flex:1;
      padding:12px 14px;
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
    }

    .leftCol{ min-width:0; }
    .mainText{
      font-weight:1000;
      color: var(--text);
      font-size:14px;
      line-height:1.25;
    }
    .subText{
      margin-top:4px;
      font-size:12px;
      font-weight:800;
      color: var(--muted);
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
    }

    .rightCol{
      text-align:right;
      flex:0 0 auto;
      display:flex;
      flex-direction:column;
      align-items:flex-end;
      gap:6px;
    }
    .amount{
      font-size:14px;
      font-weight:1000;
      letter-spacing:-0.01em;
      white-space:nowrap;
    }
    .amtPlus{ color:#075985; }
    .amtMinus{ color:#b91c1c; }

    .badge{
      font-size:11px;
      font-weight:1000;
      letter-spacing:.08em;
      text-transform:uppercase;
      padding:6px 10px;
      border-radius: 999px;
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.86);
      color: var(--muted);
      white-space:nowrap;
    }

    .pager{
      margin-top: 14px;
      padding: 10px 12px;
      border-radius: 18px;
      border: 1px solid rgba(15,23,42,.08);
      background: rgba(255,255,255,.62);
      overflow:auto;
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
    }
    .pager a{ color:#0891b2; font-weight:1000; text-decoration:none; }
    .pager a:hover{ text-decoration:underline; }
/* Empty state (lebih panjang + premium) */
.empty{
  border-radius: var(--radius);
  border: 1px dashed rgba(15,23,42,.18);
  background:
    radial-gradient(520px 220px at 50% 0%, rgba(109,40,217,.10), transparent 60%),
    radial-gradient(520px 220px at 50% 110%, rgba(6,182,212,.10), transparent 60%),
    rgba(255,255,255,.66);
  box-shadow: 0 10px 22px rgba(15,23,42,.06);
  padding: 26px 16px;

  min-height: 320px; /* bikin lebih panjang */
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  gap:14px;
  text-align:center;
}

.emptyIco{
  width: 92px;
  height: 92px;
  border-radius: 22px;
  background: rgba(255,255,255,.86);
  border: 1px solid rgba(15,23,42,.10);
  box-shadow: var(--shadow-soft);
  display:flex;
  align-items:center;
  justify-content:center;
}

.emptyTitle{
  margin:0;
  font-size:16px;
  font-weight:1000;
  letter-spacing:-0.02em;
  color: var(--text);
}

.emptyDesc{
  margin:0;
  max-width: 520px;
  font-size:13px;
  font-weight:800;
  color: var(--muted);
  line-height:1.55;
}

.emptyGrid{
  width:100%;
  max-width: 560px;
  display:grid;
  grid-template-columns: repeat(3, 1fr);
  gap:10px;
  margin-top: 6px;
}

@media (max-width: 560px){
  .empty{ min-height: 380px; }
  .emptyGrid{ grid-template-columns: 1fr; }
}

.emptyPill{
  border-radius: 18px;
  border: 1px solid rgba(15,23,42,.10);
  background: rgba(255,255,255,.72);
  box-shadow: 0 10px 22px rgba(15,23,42,.06);
  padding: 12px 12px;
  text-align:left;
}

.emptyPill .k{
  font-size:10px;
  color: var(--muted);
  font-weight:1000;
  letter-spacing:.10em;
  text-transform:uppercase;
  margin-bottom:4px;
}
.emptyPill .v{
  font-size:12px;
  font-weight:1000;
  color: var(--text);
  line-height:1.25;
}

  </style>
</head>

<body>
  <div class="wrap">
    <main class="card" role="main" aria-label="Rincian Saldo">
      <div class="card-inner">

        <header class="header">
          <div class="brand">
            <div class="logoBox" aria-hidden="true">
              <img src="/logo.png" alt="Logo" />
            </div>
            <div class="titleBlock">
              <h1 class="title">Rincian Saldo</h1>
              <p class="subtitle">Riwayat uang masuk (deposit) dan uang keluar (beli investasi).</p>
            </div>
          </div>

          <div class="headerActions" style="display:flex; gap:10px; align-items:center;">
            <a class="btnGhost" href="/dashboard" aria-label="Kembali ke Dashboard">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                   stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M15 18l-6-6 6-6"></path>
              </svg>
              <span>Kembali</span>
            </a>
          </div>
        </header>

        <div class="toolbar">
          <div class="chips">
            <a class="chip {{ $type === 'all' ? 'chipActive' : '' }}" href="{{ route('saldo.rincian', ['type' => 'all']) }}">Semua</a>
            <a class="chip {{ $type === 'deposit' ? 'chipActive' : '' }}" href="{{ route('saldo.rincian', ['type' => 'deposit']) }}">Deposit</a>
            <a class="chip {{ $type === 'investment' ? 'chipActive' : '' }}" href="{{ route('saldo.rincian', ['type' => 'investment']) }}">Investasi</a>
          </div>
        </div>

        <section class="list" aria-label="List Aktivitas">
          @forelse($activities as $a)
            @php
              $isDeposit = $a->activity_type === 'deposit';
              $title = $isDeposit ? 'Isi ulang' : 'Membeli investasi';
              $date = \Carbon\Carbon::parse($a->happened_at)->format('Y-m-d H:i:s');

              // Sub info
              $sub = $isDeposit
                ? ($a->method ? "Metode: {$a->method} • Ref: {$a->ref}" : "Ref: {$a->ref}")
                : (($a->product_name ? "Produk: {$a->product_name}" : "Produk investasi") . " • ID: {$a->ref}");

              $badge = $isDeposit ? ($a->status ?? 'PAID') : ($a->status ?? 'ACTIVE');
            @endphp

            <div class="rowItem">
              <div class="bar {{ $isDeposit ? 'barDeposit' : 'barInvest' }}"></div>

              <div class="rowBody">
                <div class="leftCol">
                  <div class="mainText">{{ $title }}</div>
                  <div class="subText">{{ $date }} • {{ $sub }}</div>
                </div>

                <div class="rightCol">
                  <div class="amount {{ $isDeposit ? 'amtPlus' : 'amtMinus' }}">
                    {{ $isDeposit ? '+' : '-' }} Rp {{ number_format((int)$a->amount, 0, ',', '.') }}
                  </div>
                  <div class="badge">{{ strtoupper($badge) }}</div>
                </div>
              </div>
            </div>
            @empty
            <div class="empty">
                <div class="emptyIco" aria-hidden="true">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.6">
                    <path d="M12 2v20"></path>
                    <path d="M17 7H7a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2Z"></path>
                    <path d="M16 12h.01"></path>
                </svg>
                </div>

                <h2 class="emptyTitle">Belum ada aktivitas saldo</h2>
                <p class="emptyDesc">
                Aktivitas akan muncul otomatis saat kamu melakukan <b>deposit</b> atau <b>membeli investasi</b>.
                Gunakan filter di atas untuk melihat detail per kategori.
                </p>

                <div class="emptyGrid" aria-label="Info aktivitas">
                <div class="emptyPill">
                    <div class="k">Deposit</div>
                    <div class="v">Uang masuk tercatat sebagai “Isi ulang”.</div>
                </div>
                <div class="emptyPill">
                    <div class="k">Investasi</div>
                    <div class="v">Uang keluar tercatat sebagai “Membeli investasi”.</div>
                </div>
                <div class="emptyPill">
                    <div class="k">Status</div>
                    <div class="v">PAID / ACTIVE akan tampil di badge kanan.</div>
                </div>
                </div>

                <div style="display:flex; gap:10px; flex-wrap:wrap; justify-content:center; margin-top:4px;">
                <a class="btnGhost" href="/dashboard" style="text-decoration:none;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 12h18"></path>
                    <path d="M12 3v18"></path>
                    </svg>
                    <span>Isi saldo / Mulai transaksi</span>
                </a>

                <a class="btnGhost" href="/dashboard" style="text-decoration:none;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M15 18l-6-6 6-6"></path>
                    </svg>
                    <span>Kembali</span>
                </a>
                </div>
            </div>
            @endforelse

        </section>

        <div class="pager">
          {{ $activities->links() }}
        </div>

      </div>
    </main>
  </div>
</body>
</html>
