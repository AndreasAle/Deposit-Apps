{{-- Crowdnik Premium White — Investasi Saya (NO @extends) --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Investasi Saya</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <style>
    /* =========================
       CROWDNIK PREMIUM WHITE TOKENS (WAJIB)
    ========================== */
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

    /* =========================
       SINGLE-CARD CENTER LAYOUT
    ========================== */
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
    .card-inner{
      position:relative;
      padding:22px;
    }

    /* =========================
       HEADER
    ========================== */
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
    .titleBlock{ min-width:0; display:flex; flex-direction:column; gap:2px; }
    .title{
      margin:0;
      font-size:18px;
      font-weight:1000;
      letter-spacing:-0.02em;
      line-height:1.2;
      color: var(--text);
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
      font-weight:900;
      font-size:12px;
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
      cursor:pointer;
      user-select:none;
      -webkit-tap-highlight-color: transparent;
      white-space:nowrap;
    }
    .btnGhost:hover{
      transform: translateY(-1px);
      background: rgba(255,255,255,.92);
      box-shadow: 0 16px 30px rgba(15,23,42,.10);
    }
    .btnGhost svg{ width:18px; height:18px; }

    @media (max-width: 860px){
      .header{ flex-direction:column; align-items:stretch; }
      .headerActions{ width:100%; justify-content:flex-end; }
      .subtitle{ white-space:normal; }
    }
    @media (max-width: 420px){
      .card-inner{ padding:18px; }
      .btnGhost{ width:100%; justify-content:center; }
      .headerActions{ width:100%; }
    }

    /* =========================
       LIST + INVESTMENT CARD
    ========================== */
    .inv-list{
      display:flex;
      flex-direction:column;
      gap:14px;
    }

    .inv-card{
      border-radius: var(--radius);
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.72);
      box-shadow: var(--shadow-soft);
      padding:16px;
      position:relative;
      overflow:hidden;
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease, border-color .15s ease;
    }
    .inv-card:hover{
      transform: translateY(-1px);
      background: rgba(255,255,255,.86);
      box-shadow: 0 22px 48px rgba(15,23,42,.12);
      border-color: rgba(6,182,212,.22);
    }

    .inv-header{
      display:flex;
      justify-content:space-between;
      align-items:flex-start;
      gap:12px;
      padding-bottom:12px;
      margin-bottom:12px;
      border-bottom: 1px solid rgba(15,23,42,.08);
    }
    .prod-name{
      font-size:15px;
      font-weight:1000;
      color: var(--text);
      letter-spacing:-0.01em;
      margin-bottom:4px;
    }
    .prod-dur{
      font-size:12px;
      color: var(--muted);
      font-weight:900;
      display:flex;
      align-items:center;
      gap:8px;
    }
    .prod-dur svg{ opacity:.8; }

    /* badge / chip */
    .status-badge{
      font-size:11px;
      font-weight:1000;
      letter-spacing:.08em;
      text-transform:uppercase;
      padding:7px 10px;
      border-radius: 999px; /* chip/badge 999px */
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.86);
      color: var(--muted);
      white-space:nowrap;
    }
    .st-active{
      border-color: rgba(6,182,212,.22);
      background: rgba(6,182,212,.10);
      color:#075985;
      box-shadow: 0 0 0 4px rgba(6,182,212,.10);
    }
    .st-finished{
      border-color: rgba(100,116,139,.18);
      background: rgba(100,116,139,.08);
      color: var(--muted);
    }

    /* stats grid */
    .stats-grid{
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap:14px;
      margin-bottom: 12px;
    }
    @media (max-width: 520px){
      .stats-grid{ grid-template-columns: 1fr; }
    }
    .stat-box{ display:flex; flex-direction:column; gap:4px; }
    .stat-label{
      font-size:11px;
      color: var(--muted);
      font-weight:1000;
      letter-spacing:.10em;
      text-transform:uppercase;
    }
    .stat-val{
      font-size:15px;
      font-weight:1000;
      color: var(--text);
      letter-spacing:-0.01em;
    }
    .val-profit{
      color:#075985;
      background: rgba(6,182,212,.10);
      border: 1px solid rgba(6,182,212,.18);
      border-radius: 12px;
      padding: 8px 10px;
      width: fit-content;
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
    }
    .val-daily{
      font-size:13px;
      font-weight:1000;
      color:#0b4a6f;
      background: rgba(109,40,217,.08);
      border: 1px solid rgba(109,40,217,.14);
      border-radius: 12px;
      padding: 8px 10px;
      width: fit-content;
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
    }

    .inlineProfit{
      display:flex;
      align-items:center;
      gap:10px;
      flex-wrap:wrap;
      margin-bottom: 12px;
    }
    .inlineProfit .stat-label{ margin:0; }

    /* footer dates */
    .inv-footer{
      border-radius: 18px;
      border: 1px solid rgba(15,23,42,.08);
      background: rgba(255,255,255,.66);
      padding: 12px 14px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
    }
    .date-group{
      display:flex;
      flex-direction:column;
      gap:3px;
      min-width:0;
    }
    .date-label{
      font-size:10px;
      color: var(--muted);
      font-weight:1000;
      letter-spacing:.10em;
      text-transform:uppercase;
    }
    .date-val{
      font-size:12px;
      font-weight:1000;
      color: var(--text);
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
    }
    .arrow-icon{
      color: var(--muted);
      opacity:.8;
      flex:0 0 auto;
    }

    /* empty */
/* empty (lebih panjang + premium) */
.empty-state{
  text-align:center;
  padding: 26px 16px;
  border-radius: var(--radius);
  border: 1px dashed rgba(15,23,42,.18);
  background:
    radial-gradient(520px 220px at 50% 0%, rgba(109,40,217,.10), transparent 60%),
    radial-gradient(520px 220px at 50% 110%, rgba(6,182,212,.10), transparent 60%),
    rgba(255,255,255,.66);
  box-shadow: 0 10px 22px rgba(15,23,42,.06);
  min-height: 360px;          /* INI yang bikin lebih panjang */
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  gap:14px;
}

.empty-ico{
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

.empty-title{
  font-weight:1000;
  color: var(--text);
  margin:0;
  letter-spacing:-0.02em;
  font-size: 16px;
}

.empty-desc{
  color: var(--muted);
  font-size:13px;
  font-weight:800;
  margin:0;
  line-height:1.55;
  max-width: 520px;
}

.empty-grid{
  width:100%;
  max-width: 560px;
  display:grid;
  grid-template-columns: repeat(3, 1fr);
  gap:10px;
  margin-top: 6px;
}

@media (max-width: 560px){
  .empty-state{ min-height: 420px; } /* mobile lebih lega */
  .empty-grid{ grid-template-columns: 1fr; }
}

.empty-pill{
  border-radius: 18px;
  border: 1px solid rgba(15,23,42,.10);
  background: rgba(255,255,255,.72);
  box-shadow: 0 10px 22px rgba(15,23,42,.06);
  padding: 12px 12px;
  text-align:left;
}

.empty-pill .k{
  font-size:10px;
  color: var(--muted);
  font-weight:1000;
  letter-spacing:.10em;
  text-transform:uppercase;
  margin-bottom:4px;
}
.empty-pill .v{
  font-size:12px;
  font-weight:1000;
  color: var(--text);
  line-height:1.25;
}


    .btnPrimary{
      border:0;
      border-radius: var(--radius-sm);
      padding: 14px 16px;
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
      text-decoration:none;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:10px;
    }
    .btnPrimary:hover{
      transform: translateY(-1px);
      filter: saturate(1.06);
      box-shadow: 0 24px 60px rgba(109,40,217,.22), 0 18px 44px rgba(6,182,212,.14);
    }
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

    /* subtle entrance */
    .fade-up{ opacity:0; transform: translateY(10px); animation: fadeUp .55s cubic-bezier(0.2,0.8,0.2,1) forwards; }
    @keyframes fadeUp{ to { opacity:1; transform: translateY(0); } }
  </style>
</head>

<body>
  <div class="wrap">
    <main class="card" role="main" aria-label="Investasi Saya">
      <div class="card-inner">

        <header class="header">
          <div class="brand">
            <div class="logoBox" aria-hidden="true">
              <img src="/logo.png" alt="Logo" />
            </div>
            <div class="titleBlock">
              <h1 class="title">Portofolio Investasi</h1>
              <p class="subtitle">Ringkasan investasi aktif & selesai, rapi dan premium.</p>
            </div>
          </div>

          <div class="headerActions">
            <a class="btnGhost" href="{{ url('/dashboard') }}" aria-label="Kembali ke Dashboard">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                   stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M15 18l-6-6 6-6"></path>
              </svg>
              <span>Kembali</span>
            </a>
          </div>
        </header>

        <section class="inv-list" aria-label="Daftar Investasi">

          @forelse($investments as $inv)
            <article class="inv-card fade-up" style="animation-delay: {{ $loop->index * 0.08 }}s;">
              <div class="inv-header">
                <div>
                  <div class="prod-name">{{ optional($inv->product)->name ?? '-' }}</div>
                  <div class="prod-dur">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                      <circle cx="12" cy="12" r="10"></circle>
                      <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    Durasi {{ $inv->duration_days }} Hari
                  </div>
                </div>

                @if($inv->status === 'active')
                  <span class="status-badge st-active">Aktif</span>
                @else
                  <span class="status-badge st-finished">{{ strtoupper($inv->status ?? 'SELESAI') }}</span>
                @endif
              </div>

              <div class="stats-grid">
                <div class="stat-box">
                  <span class="stat-label">Modal Investasi</span>
                  <span class="stat-val">Rp {{ number_format((int)$inv->price, 0, ',', '.') }}</span>
                </div>

                <div class="stat-box" style="align-items:flex-end; text-align:right;">
                  <span class="stat-label">Total Profit</span>
                  <span class="stat-val val-profit">+Rp {{ number_format((int)$inv->total_profit, 0, ',', '.') }}</span>
                </div>
              </div>

              <div class="inlineProfit">
                <span class="stat-label">Profit Harian</span>
                <span class="stat-val val-daily">Rp {{ number_format((int)$inv->daily_profit, 0, ',', '.') }}</span>
              </div>

              <div class="inv-footer">
                <div class="date-group">
                  <span class="date-label">Mulai</span>
                  <span class="date-val">
                    {{ \Carbon\Carbon::parse($inv->start_date)->translatedFormat('d M Y') }}
                  </span>
                </div>

                <div class="arrow-icon" aria-hidden="true">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"></path>
                  </svg>
                </div>

                <div class="date-group" style="text-align:right;">
                  <span class="date-label">Selesai</span>
                  <span class="date-val">
                    {{ \Carbon\Carbon::parse($inv->end_date)->translatedFormat('d M Y') }}
                  </span>
                </div>
              </div>
            </article>

@empty
  <div class="empty-state fade-up">
    <div class="empty-ico" aria-hidden="true">
      <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.6">
        <rect x="3" y="7" width="18" height="14" rx="2"></rect>
        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
        <path d="M7 11h10"></path>
        <path d="M7 15h7"></path>
      </svg>
    </div>

    <h2 class="empty-title">Belum ada investasi aktif</h2>
    <p class="empty-desc">
      Portofolio kamu masih kosong. Mulai dari nominal kecil untuk melihat simulasi profit harian dan periode investasi.
    </p>

    <div class="empty-grid" aria-label="Info cepat">
      <div class="empty-pill">
        <div class="k">Modal fleksibel</div>
        <div class="v">Pilih produk sesuai budget</div>
      </div>
      <div class="empty-pill">
        <div class="k">Profit harian</div>
        <div class="v">Terlihat di portofolio setiap hari</div>
      </div>
      <div class="empty-pill">
        <div class="k">Durasi jelas</div>
        <div class="v">Mulai & selesai tercatat otomatis</div>
      </div>
    </div>

    <a class="btnPrimary" href="{{ url('/dashboard') }}">
      <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <path d="M12 5v14"></path>
        <path d="M5 12h14"></path>
      </svg>
      <span>Mulai Investasi Sekarang</span>
    </a>
  </div>
@endempty


        </section>

      </div>
    </main>
  </div>
</body>
</html>
