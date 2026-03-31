{{-- Crowdnik Premium White — Referral Page (single-card center, NO @extends) --}}
@php
  // asumsi controller sudah mengirim:
  // $user, $refUsers (Collection), $totalCommission (int), $commissions (Paginator)
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Referral</title>
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

    /* single-card center */
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

    /* header */
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

    .headerActions{ display:flex; gap:10px; align-items:center; flex:0 0 auto; }
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

    /* layout blocks */
    .grid{
      display:grid;
      grid-template-columns: 1fr;
      gap:16px;
      align-items:start;
    }
    .stats{
      display:grid;
      grid-template-columns: repeat(3, 1fr);
      gap:12px;
    }
    @media (max-width: 860px){
      .header{ flex-direction:column; align-items:stretch; }
      .headerActions{ width:100%; justify-content:flex-end; }
      .subtitle{ white-space:normal; }
      .stats{ grid-template-columns: 1fr; }
    }
    @media (max-width: 420px){
      .card-inner{ padding:18px; }
      .btnGhost{ width:100%; justify-content:center; }
      .headerActions{ width:100%; }
    }

    .block{
      border-radius: var(--radius);
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.72);
      box-shadow: var(--shadow-soft);
      padding:16px;
      overflow:hidden;
    }
    .blockTitle{
      margin:0 0 10px 0;
      font-size:12px;
      letter-spacing:.12em;
      text-transform:uppercase;
      color: var(--muted);
      font-weight:1000;
    }

    /* referral code */
    .row{
      display:flex;
      gap:12px;
      align-items:center;
      flex-wrap:wrap;
    }
    .field{
      position:relative;
      flex: 1 1 280px;
      min-width: 220px;
    }
    .input{
      width:100%;
      border-radius: var(--radius-sm);
      border: 1px solid rgba(15,23,42,.12);
      background: rgba(255,255,255,.70);
      padding: 14px 14px;
      font-size: 14px;
      color: var(--text);
      outline:none;
      transition: box-shadow .15s ease, border-color .15s ease, background .15s ease;
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
      font-weight: 900;
      letter-spacing:.10em;
    }
    .input:focus{
      border-color: rgba(6,182,212,.40);
      box-shadow: 0 0 0 4px rgba(6,182,212,.14), 0 14px 30px rgba(15,23,42,.08);
      background: rgba(255,255,255,.86);
    }

    .btnPrimary{
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
      min-width: 120px;
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

    .muted{ color: var(--muted); font-size:12px; line-height:1.5; font-weight:700; }
    .stat{
      border-radius: var(--radius);
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.72);
      box-shadow: var(--shadow-soft);
      padding:16px;
    }
    .statLabel{ font-size:12px; color: var(--muted); font-weight:900; }
    .statValue{
      margin-top:6px;
      font-size:22px;
      font-weight:1000;
      letter-spacing:-0.02em;
      color: var(--text);
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
      font-weight:900;
      width: fit-content;
    }

    /* tables */
    .tableWrap{ overflow-x:auto; border-radius: 18px; border: 1px solid rgba(15,23,42,.08); background: rgba(255,255,255,.66); }
    table{ width:100%; border-collapse: collapse; min-width: 620px; }
    thead th{
      text-align:left;
      padding:12px 12px;
      font-size:12px;
      color: var(--muted);
      font-weight:1000;
      letter-spacing:.08em;
      text-transform:uppercase;
      border-bottom: 1px solid rgba(15,23,42,.08);
      background: rgba(255,255,255,.62);
    }
    tbody td{
      padding:12px 12px;
      font-size:13px;
      color: var(--text);
      border-bottom: 1px solid rgba(15,23,42,.06);
      background: rgba(255,255,255,.40);
      vertical-align:top;
      font-weight:700;
    }
    tbody tr:hover td{ background: rgba(255,255,255,.70); }
    .tdStrong{ font-weight:1000; }
    .empty{
      padding: 14px;
      text-align:center;
      color: var(--muted);
      font-weight:800;
      background: rgba(255,255,255,.66);
      border-radius: 18px;
      border: 1px dashed rgba(15,23,42,.18);
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
    }

    /* pagination container (Laravel default) */
    .pager{
      margin-top:14px;
      border-radius: 18px;
      border: 1px solid rgba(15,23,42,.08);
      background: rgba(255,255,255,.62);
      padding: 10px 12px;
      overflow:auto;
    }
    .pager *{ color: var(--text); }
    .pager a{ color: #0891b2; font-weight:900; text-decoration:none; }
    .pager a:hover{ text-decoration:underline; }

    /* toast */
    .toast{
      position: fixed;
      right: 16px;
      bottom: 16px;
      z-index: 9999;
      padding: 12px 14px;
      border-radius: 999px;
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.92);
      box-shadow: var(--shadow-soft);
      color: var(--text);
      font-weight: 900;
      font-size: 12px;
      opacity: 0;
      transform: translateY(10px);
      pointer-events:none;
      transition: opacity .18s ease, transform .18s ease;
    }
    .toast.show{ opacity:1; transform: translateY(0); }
  </style>
</head>

<body>
  <div class="wrap">
    <main class="card" role="main" aria-label="Referral">
      <div class="card-inner">

        <header class="header">
          <div class="brand">
            <div class="logoBox" aria-hidden="true">
              <img src="/logo.png" alt="Logo" />
            </div>
            <div class="titleBlock">
              <h1 class="title">Referral</h1>
              <p class="subtitle">Komisi: Deposit 5% • Beli Produk 3%</p>
            </div>
          </div>

          <div class="headerActions">
            <a class="btnGhost" href="/dashboard" aria-label="Kembali ke Dashboard">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                   stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M15 18l-6-6 6-6"></path>
              </svg>
              <span>Kembali</span>
            </a>
          </div>
        </header>

        <section class="grid">

          {{-- Kode referral --}}
          <section class="block" aria-label="Kode Referral">
            <p class="blockTitle">Kode Referral</p>

            <div class="row" style="margin-bottom:10px;">
              <div class="field">
                <input
                  id="refCode"
                  value="{{ $user->referral_code }}"
                  class="input"
                  readonly
                  aria-label="Kode Referral"
                />
              </div>

              <button type="button" class="btnPrimary" onclick="copyRef()" aria-label="Copy kode referral">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <rect x="9" y="9" width="13" height="13" rx="2"></rect>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
                <span>Copy</span>
              </button>

              <span class="chip" title="Aturan komisi">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
                Deposit 5% • Produk 3%
              </span>
            </div>

            <div class="muted">
              Bagikan kode ini saat teman kamu daftar. Komisi akan tercatat otomatis berdasarkan transaksi.
            </div>
          </section>

          {{-- Statistik --}}
          <section class="stats" aria-label="Statistik Referral">
            <div class="stat">
              <div class="statLabel">Jumlah referral</div>
              <div class="statValue">{{ $refUsers->count() }}</div>
            </div>

            <div class="stat">
              <div class="statLabel">Total komisi (history)</div>
              <div class="statValue">Rp {{ number_format($totalCommission, 0, ',', '.') }}</div>
            </div>

            <div class="stat">
              <div class="statLabel">Saldo komisi terkumpul</div>
              <div class="statValue">Rp {{ number_format($user->referral_earned_total ?? 0, 0, ',', '.') }}</div>
            </div>
          </section>

          {{-- List refer user --}}
          <section class="block" aria-label="User yang daftar pakai kode kamu">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:10px;">
              <p class="blockTitle" style="margin:0;">User Referral</p>
              <div class="muted" style="font-weight:900;">Total: {{ $refUsers->count() }}</div>
            </div>

            <div class="tableWrap">
              <table>
                <thead>
                  <tr>
                    <th>Nama</th>
                    <th>Phone</th>
                    <th>Tanggal daftar</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($refUsers as $ru)
                    <tr>
                      <td class="tdStrong">{{ $ru->name }}</td>
                      <td>{{ $ru->phone }}</td>
                      <td>{{ $ru->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="3" style="padding:16px;">
                        <div class="empty">Belum ada user yang daftar memakai kode kamu.</div>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </section>

          {{-- Riwayat komisi --}}
          <section class="block" aria-label="Riwayat Komisi">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:10px;">
              <p class="blockTitle" style="margin:0;">Riwayat Komisi</p>
              <div class="muted" style="font-weight:900;">Menampilkan terbaru</div>
            </div>

            <div class="tableWrap">
              <table style="min-width: 760px;">
                <thead>
                  <tr>
                    <th>Tanggal</th>
                    <th>Sumber</th>
                    <th>Dasar</th>
                    <th>Rate</th>
                    <th>Komisi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($commissions as $c)
                    <tr>
                      <td>{{ $c->created_at->format('d-m-Y H:i') }}</td>
                      <td class="tdStrong">{{ $c->source_type === 'deposit' ? 'Deposit' : 'Beli Produk' }}</td>
                      <td>Rp {{ number_format($c->base_amount, 0, ',', '.') }}</td>
                      <td>{{ (float)$c->rate * 100 }}%</td>
                      <td class="tdStrong">Rp {{ number_format($c->commission_amount, 0, ',', '.') }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" style="padding:16px;">
                        <div class="empty">Belum ada komisi masuk.</div>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            <div class="pager">
              {{ $commissions->links() }}
            </div>
          </section>

        </section>

      </div>
    </main>
  </div>

  <div id="toast" class="toast" role="status" aria-live="polite"></div>

  <script>
    function showToast(msg) {
      const t = document.getElementById('toast');
      t.textContent = msg;
      t.classList.add('show');
      clearTimeout(window.__toastTimer);
      window.__toastTimer = setTimeout(() => t.classList.remove('show'), 1800);
    }

    async function copyRef() {
      const el = document.getElementById('refCode');
      const value = el.value || '';

      try {
        if (navigator.clipboard && window.isSecureContext) {
          await navigator.clipboard.writeText(value);
        } else {
          el.focus();
          el.select();
          el.setSelectionRange(0, 99999);
          document.execCommand('copy');
        }
        showToast('Kode referral berhasil dicopy!');
      } catch (e) {
        showToast('Gagal copy, coba manual.');
      }
    }
  </script>
</body>
</html>
