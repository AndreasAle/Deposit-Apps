@php
  $user = auth()->user();
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Akun Saya | TumbuhMaju</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

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
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
      background:
        radial-gradient(1100px 600px at 12% 8%, rgba(59,130,246,.18), transparent 60%),
        radial-gradient(900px 520px at 90% 18%, rgba(14,165,233,.14), transparent 55%),
        radial-gradient(900px 520px at 50% 105%, rgba(124,58,237,.10), transparent 60%),
        linear-gradient(180deg, #ffffff 0%, #f5f7ff 55%, #eef2ff 100%);
      min-height:100vh;
      overflow-x:hidden;
      -webkit-tap-highlight-color: transparent;
    }

    /* ====== Mobile-first vertical shell (tetap tegak di desktop) ====== */
    .page{
      min-height:100vh;
      display:flex;
      justify-content:center;
      padding: 18px 12px;
      padding-bottom: calc(18px + 92px); /* space for sticky nav */
    }
    .shell{
      width:100%;
      max-width: 540px; /* KUNCI: biar tegak, ga melebar */
    }

    .card{
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      overflow:hidden;
      position:relative;
    }
    .card::before{
      content:"";
      position:absolute;
      inset:-1px;
      background: linear-gradient(135deg, rgba(109,40,217,.10), rgba(6,182,212,.10));
      opacity:.60;
      pointer-events:none;
      mask: linear-gradient(#000, #000) content-box, linear-gradient(#000, #000);
      -webkit-mask: linear-gradient(#000, #000) content-box, linear-gradient(#000, #000);
      padding:1px;
      border-radius: var(--radius);
    }

    .inner{
      position:relative;
      padding: 16px;
    }

    /* ===== Header ===== */
    .header{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap: 12px;
      padding: 6px 2px 14px;
    }
    .brand{
      display:flex;
      align-items:center;
      gap: 12px;
      min-width:0;
    }
    .logoBox{
      width:68px; height:68px;
      border-radius: 20px;
      background: rgba(255,255,255,.88);
      border: 1px solid rgba(6,182,212,.18);
      box-shadow: var(--shadow-soft);
      display:flex;
      align-items:center;
      justify-content:center;
      flex: 0 0 auto;
    }
    .logoBox img{
      width:46px; height:46px;
      object-fit:contain;
      display:block;
      filter: drop-shadow(0 10px 18px rgba(15,23,42,.12));
    }
    .who{ min-width:0; }
    .who .name{
      margin:0;
      font-size: 16px;
      font-weight: 950;
      letter-spacing: -0.02em;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width: 250px;
    }
    .metaRow{
      margin-top: 7px;
      display:flex;
      align-items:center;
      gap: 10px;
      flex-wrap:wrap;
    }
    .pill{
      display:inline-flex;
      align-items:center;
      gap: 8px;
      padding: 8px 10px;
      border-radius: 999px;
      background: rgba(255,255,255,.86);
      border: 1px solid rgba(15,23,42,.10);
      box-shadow: 0 10px 18px rgba(15,23,42,.06);
      font-size: 12px;
      font-weight: 900;
      color: var(--muted);
    }
    .copyBtn{
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.92);
      color: var(--muted);
      border-radius: 999px;
      padding: 8px 12px;
      font-weight: 950;
      font-size: 12px;
      cursor:pointer;
      transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
      user-select:none;
    }
    .copyBtn:hover{
      transform: translateY(-1px);
      border-color: rgba(6,182,212,.28);
      box-shadow: 0 16px 28px rgba(15,23,42,.10);
    }
    .copyBtn:focus{
      outline:none;
      box-shadow: 0 0 0 4px rgba(6,182,212,.14);
      border-color: rgba(6,182,212,.40);
    }

    .vipChip{
      display:inline-flex;
      align-items:center;
      gap: 8px;
      padding: 10px 12px;
      border-radius: 999px;
      background: rgba(255,255,255,.86);
      border: 1px solid rgba(15,23,42,.10);
      box-shadow: var(--shadow-soft);
      white-space:nowrap;
      flex: 0 0 auto;
      font-size: 12px;
      font-weight: 950;
      color: var(--text);
    }
    .vipDot{
      width:10px; height:10px; border-radius:999px;
      background: linear-gradient(135deg, var(--primary1), var(--primary2));
      box-shadow: 0 10px 18px rgba(6,182,212,.16);
    }

    /* ===== Balance (modern, compact) ===== */
    .balance{
      background: rgba(255,255,255,.90);
      border: 1px solid rgba(15,23,42,.10);
      border-radius: var(--radius);
      box-shadow: var(--shadow-soft);
      overflow:hidden;
      position:relative;
    }
    .balance::before{
      content:"";
      position:absolute;
      inset:-2px;
      background:
        radial-gradient(900px 420px at 10% 0%, rgba(6,182,212,.14), transparent 60%),
        radial-gradient(900px 420px at 90% 0%, rgba(109,40,217,.12), transparent 60%);
      opacity:.9;
      pointer-events:none;
    }
    .balanceInner{ position:relative; padding: 16px; text-align:center; }
    .kicker{
      margin:0;
      color: var(--muted);
      font-size: 12.5px;
      font-weight: 900;
      letter-spacing: .01em;
    }
    .amount{
      margin: 10px 0 0;
      font-size: 32px;
      font-weight: 950;
      letter-spacing: -0.03em;
      line-height: 1.05;
      color: var(--text);
    }

    .actions{
      margin-top: 14px;
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
    }

    .btn{
      width:100%;
      border: 0;
      border-radius: var(--radius-sm);
      padding: 12px 14px;
      font-weight: 950;
      cursor:pointer;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap: 10px;
      transition: transform .18s ease, box-shadow .18s ease, filter .18s ease, border-color .18s ease;
      user-select:none;
      text-decoration:none;
      min-height: 46px;
      white-space:nowrap;
    }
    .btn svg{ width:18px; height:18px; stroke: currentColor; }
    .btnPrimary{
      background: linear-gradient(135deg, var(--primary1), var(--primary2));
      color: #081022;
      box-shadow: 0 18px 34px rgba(6,182,212,.18);
    }
    .btnPrimary:hover{ transform: translateY(-1px); filter: brightness(1.02); }
    .btnPrimary:focus{
      outline:none;
      box-shadow: 0 0 0 4px rgba(6,182,212,.14), 0 18px 34px rgba(6,182,212,.18);
    }
    .btnGhost{
      background: rgba(255,255,255,.92);
      border: 1px solid rgba(15,23,42,.12);
      color: var(--text);
      box-shadow: 0 12px 20px rgba(15,23,42,.06);
    }
    .btnGhost:hover{
      transform: translateY(-1px);
      border-color: rgba(6,182,212,.28);
      box-shadow: 0 16px 28px rgba(15,23,42,.10);
    }
    .btnGhost:focus{
      outline:none;
      box-shadow: 0 0 0 4px rgba(6,182,212,.14);
      border-color: rgba(6,182,212,.40);
    }

    /* ===== Menu panel ===== */
    .panel{
      margin-top: 14px;
      background: rgba(255,255,255,.88);
      border: 1px solid rgba(15,23,42,.10);
      border-radius: var(--radius);
      box-shadow: var(--shadow-soft);
      padding: 14px;
    }
    .panelHead{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap: 10px;
      margin-bottom: 12px;
    }
    .panelHead h2{
      margin:0;
      font-size: 13.5px;
      font-weight: 950;
      letter-spacing: -0.01em;
      color: var(--text);
    }
    .panelHead p{
      margin:0;
      font-size: 12px;
      font-weight: 800;
      color: var(--muted);
      white-space:nowrap;
    }

    /* KUNCI: grid selalu 3 kolom biar lurus tegak */
    .grid{
      display:grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
    }

    .tile{
      text-decoration:none;
      color: var(--text);
      background: rgba(255,255,255,.94);
      border: 1px solid rgba(15,23,42,.10);
      border-radius: 18px;
      box-shadow: 0 10px 18px rgba(15,23,42,.06);
      padding: 12px 10px;
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      gap: 8px;
      min-height: 92px;
      transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    }
    .tile:hover{
      transform: translateY(-1px);
      border-color: rgba(6,182,212,.28);
      box-shadow: 0 16px 28px rgba(15,23,42,.10);
    }
    .tile:focus{
      outline:none;
      box-shadow: 0 0 0 4px rgba(6,182,212,.14);
      border-color: rgba(6,182,212,.40);
    }
    .tileIco{
      width:44px; height:44px;
      border-radius: 16px;
      display:flex;
      align-items:center;
      justify-content:center;
      background: linear-gradient(135deg, rgba(109,40,217,.12), rgba(6,182,212,.12));
      border: 1px solid rgba(15,23,42,.08);
    }
    .tileIco svg{ width:22px; height:22px; stroke: var(--text); opacity:.92; }
    .tileLbl{
      font-size: 11px;
      font-weight: 950;
      letter-spacing: .02em;
      color: var(--text);
      text-align:center;
      line-height: 1.2;
    }

    /* ===== Logout ===== */
    .logout{
      margin-top: 12px;
      background: rgba(255,255,255,.88);
      border: 1px solid rgba(239,68,68,.18);
      border-radius: 18px;
      box-shadow: 0 16px 28px rgba(15,23,42,.08);
      padding: 10px;
    }
    .btnDanger{
      width:100%;
      border-radius: var(--radius-sm);
      padding: 12px 14px;
      border: 1px solid rgba(239,68,68,.22);
      background: rgba(255,255,255,.94);
      color: #b91c1c;
      font-weight: 950;
      cursor:pointer;
      display:flex;
      align-items:center;
      justify-content:center;
      gap: 10px;
      transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
      min-height: 46px;
    }
    .btnDanger:hover{
      transform: translateY(-1px);
      border-color: rgba(239,68,68,.30);
      box-shadow: 0 16px 28px rgba(15,23,42,.10);
    }
    .btnDanger:focus{
      outline:none;
      box-shadow: 0 0 0 4px rgba(239,68,68,.12);
      border-color: rgba(239,68,68,.35);
    }

    /* ===== Sticky bottom nav (always inside viewport, modern) ===== */
    .bottomNav{
      position: fixed;
      left: 50%;
      transform: translateX(-50%);
      bottom: 14px;
      width: min(540px, calc(100% - 24px));
      z-index: 50;
    }
    .nav{
      background: rgba(255,255,255,.90);
      border: 1px solid rgba(15,23,42,.10);
      border-radius: 18px;
      box-shadow: 0 16px 28px rgba(15,23,42,.10);
      padding: 10px;
      display:grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 8px;
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
    }
    .nav a{
      border: 1px solid transparent;
      background: transparent;
      border-radius: 16px;
      padding: 10px 8px;
      color: var(--muted);
      text-decoration:none;
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      gap: 6px;
      transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease, background .18s ease, color .18s ease;
      user-select:none;
      min-height: 54px;
    }
    .nav svg{ width:20px; height:20px; stroke: currentColor; }
    .nav a:hover{ transform: translateY(-1px); }
    .nav a:focus{
      outline:none;
      box-shadow: 0 0 0 4px rgba(6,182,212,.14);
      border-color: rgba(6,182,212,.40);
    }
    .nav .active{
      color:#081022;
      background: linear-gradient(135deg, rgba(109,40,217,.14), rgba(6,182,212,.14));
      border-color: rgba(6,182,212,.22);
    }
    .nav span{
      font-size: 11px;
      font-weight: 950;
      letter-spacing: .02em;
    }

    .fadeUp{ animation: fadeUp .55s cubic-bezier(.16,1,.3,1) both; }
    @keyframes fadeUp{ from{opacity:0; transform: translateY(10px);} to{opacity:1; transform: translateY(0);} }
  </style>
</head>

<body>
  <div class="page">
    <div class="shell">
      <div class="card fadeUp">
        <div class="inner">

          {{-- HEADER --}}
          <div class="header">
            <div class="brand">
              <div class="logoBox" aria-hidden="true">
                <img src="/logo.png" alt="Logo" />
              </div>
              <div class="who">
                <p class="name">{{ $user->name }}</p>
                <div class="metaRow">
                  <span class="pill">ID: <span id="userIdText">{{ $user->id }}</span></span>
                  <button type="button" class="copyBtn" id="copyIdBtn">Salin</button>
                </div>
              </div>
            </div>

            <div class="vipChip" title="VIP Level">
              <span class="vipDot" aria-hidden="true"></span>
              VIP {{ $user->vip_level }}
            </div>
          </div>

          {{-- SALDO --}}
          <div class="balance fadeUp">
            <div class="balanceInner">
              <p class="kicker">Total Saldo Aset</p>
              <div class="amount">Rp {{ number_format($user->saldo,0,',','.') }}</div>

              <div class="actions">
                <a href="/deposit" class="btn btnPrimary">
                  <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14"></path>
                    <path d="M5 12h14"></path>
                  </svg>
                  Deposit
                </a>

                <a href="/ui/withdrawals" class="btn btnGhost">
                  <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4"></path>
                    <path d="M4 6v12c0 1.1.9 2 2 2h14v-4"></path>
                    <path d="M18 12a2 2 0 0 0-2 2c0 1.1.9 2 2 2h4v-4h-4z"></path>
                  </svg>
                  Penarikan
                </a>
              </div>
            </div>
          </div>

          {{-- MENU --}}
          <div class="panel fadeUp">
            <div class="panelHead">
              <h2>Menu Akun</h2>
              <p>Kelola aktivitas Anda</p>
            </div>

            <div class="grid">
              <a href="{{ route('investasi.index') }}" class="tile">
                <div class="tileIco" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3v18h18"></path>
                    <path d="M7 14l3-3 3 3 5-6"></path>
                  </svg>
                </div>
                <div class="tileLbl">Investasi Saya</div>
              </a>

              <a href="/ui/payout-accounts" class="tile">
                <div class="tileIco" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="6" width="20" height="12" rx="2"></rect>
                    <path d="M2 10h20"></path>
                  </svg>
                </div>
                <div class="tileLbl">Rekening Bank</div>
              </a>

                <a href="{{ route('saldo.rincian') }}" class="tile">
                <div class="tileIco" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2Z"></path>
                    <path d="M16 12h.01"></path>
                    </svg>
                </div>
                <div class="tileLbl">Rincian Saldo</div>
                </a>
              <a href="/deposit/history" class="tile">
                <div class="tileIco" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 8v4l3 3"></path>
                    <circle cx="12" cy="12" r="10"></circle>
                  </svg>
                </div>
                <div class="tileLbl">Riwayat Deposit</div>
              </a>

              <a href="/withdraw/history" class="tile">
                <div class="tileIco" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4"></path>
                    <path d="M4 6v12c0 1.1.9 2 2 2h14v-4"></path>
                    <path d="M18 12a2 2 0 0 0-2 2c0 1.1.9 2 2 2h4v-4h-4z"></path>
                  </svg>
                </div>
                <div class="tileLbl">Riwayat Tarik</div>
              </a>

              <a href="https://t.me/crowdnikchannel" target="_blank" rel="noopener" class="tile">
                <div class="tileIco" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 2 11 13"></path>
                    <path d="M22 2 15 22 11 13 2 9 22 2"></path>
                  </svg>
                </div>
                <div class="tileLbl">Grup Resmi</div>
              </a>

              <a href="/cs" class="tile">
                <div class="tileIco" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a4 4 0 0 1-4 4H7l-4 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"></path>
                    <path d="M8 10h8"></path>
                    <path d="M8 14h5"></path>
                  </svg>
                </div>
                <div class="tileLbl">Layanan CS</div>
              </a>

              <a href="/tentang" class="tile">
                <div class="tileIco" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M12 16h.01"></path>
                    <path d="M12 10a2 2 0 0 1 2 2c0 1-1 1.5-2 2"></path>
                  </svg>
                </div>
                <div class="tileLbl">Tentang</div>
              </a>

              <a href="/download" class="tile">
                <div class="tileIco" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3v12"></path>
                    <path d="M7 10l5 5 5-5"></path>
                    <path d="M5 21h14"></path>
                  </svg>
                </div>
                <div class="tileLbl">Unduh</div>
              </a>
            </div>
          </div>

          {{-- LOGOUT --}}
          <div class="logout fadeUp">
            <form action="/logout" method="POST" style="margin:0;">
              @csrf
              <button class="btnDanger" type="submit">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#b91c1c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                  <path d="M16 17l5-5-5-5"></path>
                  <path d="M21 12H9"></path>
                </svg>
                Keluar dari Akun
              </button>
            </form>
          </div>

        </div>
      </div>
    </div>
  </div>

  {{-- STICKY NAV --}}
  <div class="bottomNav">
    <div class="nav" aria-label="Navigation">
      <a href="/">
        <svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
          <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
          <path d="M9 22V12h6v10"></path>
        </svg>
        <span>Home</span>
      </a>

      <a href="{{ route('investasi.index') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
          <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
          <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
        </svg>
        <span>Invest</span>
      </a>

      <a href="{{ route('team.index') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
          <circle cx="9" cy="7" r="4"></circle>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
          <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
        <span>Team</span>
      </a>

      <a href="{{ url('/akun') }}" class="active">
        <svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
          <circle cx="12" cy="7" r="4"></circle>
        </svg>
        <span>Akun</span>
      </a>
    </div>
  </div>

  <script>
    // Copy ID
    (function(){
      const btn = document.getElementById('copyIdBtn');
      const txt = document.getElementById('userIdText');
      if(!btn || !txt) return;

      btn.addEventListener('click', async () => {
        const val = (txt.textContent || '').trim();
        try{
          await navigator.clipboard.writeText(val);
          const prev = btn.textContent;
          btn.textContent = 'Tersalin';
          setTimeout(() => (btn.textContent = prev), 1200);
        }catch{
          const ta = document.createElement('textarea');
          ta.value = val;
          document.body.appendChild(ta);
          ta.select();
          document.execCommand('copy');
          ta.remove();
          const prev = btn.textContent;
          btn.textContent = 'Tersalin';
          setTimeout(() => (btn.textContent = prev), 1200);
        }
      });
    })();
  </script>
</body>
</html>
