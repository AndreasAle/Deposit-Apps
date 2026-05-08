 @include('partials.anti-inspect')
@php
  $user = auth()->user();

  $deposits = $deposits ?? collect();

  $totalEntry = $deposits->count();

  $totalBerhasil = $deposits->filter(function($deposit){
      $status = strtolower((string) ($deposit->status ?? ''));
      return in_array($status, ['berhasil', 'success', 'sukses', 'paid', 'completed']);
  })->sum('amount');
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Riwayat Deposit | Rubik Company</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --dh-bg:#030F0F;
      --dh-text:#f7fffb;
      --dh-muted:#9bb9ad;
      --dh-soft:#dffcf1;
      --dh-border:rgba(255,255,255,.10);
      --dh-green:#00DF82;
      --dh-green2:#79ff99;
      --dh-blue:#34d5ff;
      --dh-amber:#f6c453;
      --dh-red:#ff5b75;
      --dh-orange:#fb923c;
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
      color:var(--dh-text);
      background:
        radial-gradient(760px 420px at 14% -2%, rgba(0,223,130,.18), transparent 58%),
        radial-gradient(620px 360px at 90% 10%, rgba(90,140,255,.14), transparent 62%),
        radial-gradient(520px 300px at 55% 100%, rgba(246,196,83,.08), transparent 62%),
        linear-gradient(180deg, #071f1a 0%, #030f0f 48%, #020807 100%);
      overflow-x:hidden;
      -webkit-tap-highlight-color:transparent;
    }

    body::before{
      content:"";
      position:fixed;
      inset:0;
      pointer-events:none;
      background:
        linear-gradient(rgba(255,255,255,.022) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.016) 1px, transparent 1px);
      background-size:38px 38px;
      mask-image:linear-gradient(180deg, rgba(0,0,0,.65), transparent 76%);
      -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.65), transparent 76%);
      opacity:.46;
      z-index:0;
    }

    a{
      color:inherit;
      text-decoration:none;
    }

    button,
    select{
      font-family:inherit;
    }

    .dh-page{
      width:100%;
      min-height:100vh;
      display:flex;
      justify-content:center;
      padding:14px 10px 0;
      position:relative;
      z-index:1;
    }

    .dh-phone{
      width:100%;
      max-width:430px;
      min-height:100vh;
      position:relative;
      padding:8px 4px 104px;
    }

    .dh-topbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
      padding:0 2px;
    }

    .dh-brand{
      display:flex;
      align-items:center;
      gap:10px;
      min-width:0;
    }

    .dh-back{
      width:42px;
      height:42px;
      border-radius:15px;
      border:1px solid rgba(255,255,255,.10);
      background:
        radial-gradient(circle at 32% 18%, rgba(255,255,255,.18), transparent 34%),
        linear-gradient(180deg, rgba(10,42,35,.96), rgba(4,18,16,.96));
      color:#ffffff;
      display:grid;
      place-items:center;
      box-shadow:
        0 13px 28px rgba(0,0,0,.34),
        0 0 0 1px rgba(0,223,130,.06) inset;
      cursor:pointer;
      flex:0 0 auto;
    }

    .dh-back svg{
      width:20px;
      height:20px;
    }

    .dh-title{
      min-width:0;
    }

    .dh-title h1{
      margin:0;
      color:#ffffff;
      font-size:23px;
      line-height:1;
      font-weight:900;
      letter-spacing:-.045em;
    }

    .dh-title p{
      margin:5px 0 0;
      color:rgba(214,255,240,.58);
      font-size:11.5px;
      font-weight:650;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
    }

    .dh-header-icon{
      width:42px;
      height:42px;
      border-radius:999px;
      border:1px solid rgba(255,255,255,.10);
      background:
        radial-gradient(circle at 32% 18%, rgba(255,255,255,.18), transparent 34%),
        linear-gradient(180deg, rgba(10,42,35,.96), rgba(4,18,16,.96));
      color:#ffffff;
      display:grid;
      place-items:center;
      box-shadow:
        0 13px 28px rgba(0,0,0,.34),
        0 0 0 1px rgba(0,223,130,.06) inset;
      flex:0 0 auto;
    }

    .dh-header-icon svg{
      width:20px;
      height:20px;
    }

    .dh-hero{
      position:relative;
      overflow:hidden;
      border-radius:26px;
      background:
        radial-gradient(320px 180px at 95% 4%, rgba(90,140,255,.24), transparent 62%),
        radial-gradient(260px 170px at 8% 0%, rgba(0,223,130,.28), transparent 62%),
        radial-gradient(240px 150px at 90% 110%, rgba(246,196,83,.16), transparent 68%),
        linear-gradient(135deg, rgba(236,255,248,.96), rgba(199,255,232,.92) 48%, rgba(185,236,255,.88));
      border:1px solid rgba(255,255,255,.55);
      box-shadow:
        0 20px 44px rgba(0,0,0,.22),
        0 0 0 1px rgba(0,223,130,.14) inset,
        inset 0 1px 0 rgba(255,255,255,.72);
      padding:16px;
      animation:dhFadeUp .42s ease both;
    }

    .dh-hero::before{
      content:"";
      position:absolute;
      inset:0;
      background:
        linear-gradient(145deg, rgba(255,255,255,.48) 0%, rgba(255,255,255,.18) 27%, transparent 28%),
        linear-gradient(180deg, rgba(255,255,255,.22), rgba(255,255,255,0));
      pointer-events:none;
    }

    .dh-hero-inner{
      position:relative;
      z-index:1;
      display:grid;
      grid-template-columns:52px minmax(0,1fr);
      gap:13px;
      align-items:center;
    }

    .dh-hero-icon{
      width:52px;
      height:52px;
      border-radius:18px;
      display:grid;
      place-items:center;
      color:#06110e;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.55), transparent 34%),
        linear-gradient(135deg, #00DF82 0%, #79ff99 100%);
      box-shadow:
        0 14px 28px rgba(0,223,130,.20),
        inset 0 1px 0 rgba(255,255,255,.30);
    }

    .dh-hero-icon svg{
      width:26px;
      height:26px;
    }

    .dh-hero-label{
      margin:0;
      color:rgba(3,24,20,.62);
      font-size:11px;
      font-weight:850;
      text-transform:uppercase;
      letter-spacing:.08em;
    }

    .dh-hero-total{
      margin:6px 0 0;
      color:#031713;
      font-size:30px;
      line-height:1;
      letter-spacing:-.055em;
      font-weight:950;
    }

    .dh-hero-sub{
      margin:8px 0 0;
      color:rgba(3,24,20,.58);
      font-size:12px;
      line-height:1.35;
      font-weight:650;
    }

    .dh-filter-card{
      margin-top:12px;
      position:relative;
      z-index:2;
      animation:dhFadeUp .42s ease both;
    }

    .dh-filter-grid{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:9px;
    }

    .dh-select{
      width:100%;
      height:44px;
      border-radius:16px;
      border:1px solid rgba(255,255,255,.10);
      outline:0;
      background:
        linear-gradient(180deg, rgba(255,255,255,.07), rgba(255,255,255,.035));
      color:#ffffff;
      padding:0 38px 0 13px;
      font-size:12px;
      font-weight:850;
      appearance:none;
      background-image:url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='rgba(214,255,240,.78)' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='m6 9 6 6 6-6'/%3e%3c/svg%3e");
      background-repeat:no-repeat;
      background-position:right 13px center;
      background-size:16px;
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,.06),
        0 10px 18px rgba(0,0,0,.14);
    }

    .dh-select option{
      background:#061714;
      color:#ffffff;
    }

    .dh-list{
      margin-top:12px;
      display:flex;
      flex-direction:column;
      gap:10px;
    }

    .dh-card{
      position:relative;
      overflow:hidden;
      border-radius:22px;
      background:
        radial-gradient(170px 94px at 88% 8%, rgba(0,223,130,.10), transparent 64%),
        linear-gradient(180deg, rgba(13,35,34,.94), rgba(6,20,19,.96));
      border:1px solid rgba(255,255,255,.085);
      box-shadow:
        0 16px 32px rgba(0,0,0,.25),
        0 0 0 1px rgba(255,255,255,.025) inset;
      animation:dhFadeUp .42s ease both;
    }

    .dh-card-top{
      padding:14px;
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
    }

    .dh-bank{
      display:flex;
      align-items:center;
      gap:11px;
      min-width:0;
    }

    .dh-bank-logo{
      width:44px;
      height:44px;
      border-radius:15px;
      display:grid;
      place-items:center;
      color:#06110e;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.55), transparent 34%),
        linear-gradient(135deg, #00DF82 0%, #79ff99 100%);
      box-shadow:
        0 14px 28px rgba(0,223,130,.18),
        inset 0 1px 0 rgba(255,255,255,.30);
      font-size:10px;
      font-weight:950;
      flex:0 0 auto;
    }

    .dh-bank-name{
      color:#ffffff;
      font-size:14px;
      line-height:1.15;
      font-weight:950;
      letter-spacing:-.025em;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width:170px;
    }

    .dh-bank-number{
      margin-top:5px;
      color:rgba(214,255,240,.54);
      font-size:11px;
      font-weight:750;
      white-space:nowrap;
    }

    .dh-status{
      min-height:28px;
      padding:0 10px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:6px;
      font-size:11px;
      font-weight:900;
      white-space:nowrap;
      flex:0 0 auto;
      border:1px solid transparent;
    }

    .dh-status::before{
      content:"";
      width:6px;
      height:6px;
      border-radius:999px;
      background:currentColor;
    }

    .dh-status.is-success{
      color:#00DF82;
      background:rgba(0,223,130,.10);
      border-color:rgba(0,223,130,.22);
    }

    .dh-status.is-pending{
      color:#f6c453;
      background:rgba(246,196,83,.10);
      border-color:rgba(246,196,83,.22);
    }

    .dh-status.is-failed{
      color:#ff8aa0;
      background:rgba(255,91,117,.10);
      border-color:rgba(255,91,117,.22);
    }

    .dh-card-body{
      border-top:1px solid rgba(255,255,255,.07);
      background:rgba(2,10,10,.16);
      padding:13px 14px;
      display:grid;
      gap:11px;
    }

    .dh-row{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      color:rgba(214,255,240,.68);
      font-size:12px;
      font-weight:700;
    }

    .dh-row strong{
      color:#ffffff;
      font-size:12.5px;
      font-weight:900;
      white-space:nowrap;
      text-align:right;
    }

    .dh-row.is-total{
      padding-top:12px;
      border-top:1px solid rgba(255,255,255,.07);
    }

    .dh-row.is-total span{
      color:#ffffff;
      font-weight:900;
    }

    .dh-row.is-total strong{
      color:#00DF82;
      font-size:16px;
      letter-spacing:-.035em;
      text-shadow:0 0 16px rgba(0,223,130,.10);
    }

    .dh-date{
      border-top:1px solid rgba(255,255,255,.07);
      padding:12px 14px 13px;
      color:rgba(214,255,240,.50);
      font-size:11px;
      font-weight:700;
      display:flex;
      align-items:center;
      gap:7px;
      background:rgba(255,255,255,.018);
    }

    .dh-date svg{
      width:14px;
      height:14px;
      opacity:.8;
    }

    .dh-invoice-btn{
      margin-left:auto;
      min-height:28px;
      padding:0 9px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      color:#06110e;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.50), transparent 34%),
        linear-gradient(135deg, #00DF82, #72ffab);
      font-size:10.5px;
      font-weight:950;
      white-space:nowrap;
    }

    .dh-empty{
      min-height:180px;
      border-radius:22px;
      border:1px dashed rgba(255,255,255,.14);
      background:
        radial-gradient(220px 120px at 90% 0%, rgba(0,223,130,.10), transparent 62%),
        rgba(9,37,31,.70);
      color:rgba(214,255,240,.62);
      display:flex;
      align-items:center;
      justify-content:center;
      text-align:center;
      padding:18px;
      font-size:12.5px;
      font-weight:750;
    }

    .dh-bottom-actions{
      position:fixed;
      left:50%;
      bottom:0;
      transform:translateX(-50%);
      z-index:50;
      width:min(100%,430px);
      padding:12px 14px calc(14px + env(safe-area-inset-bottom));
      background:
        linear-gradient(180deg, rgba(3,15,15,0), rgba(3,15,15,.92) 26%, rgba(3,15,15,.98));
      pointer-events:none;
    }

    .dh-main-btn{
      width:100%;
      min-height:50px;
      border:0;
      border-radius:999px;
      color:#06110d;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.55), transparent 34%),
        linear-gradient(135deg,#00DF82 0%,#79ff99 100%);
      box-shadow:
        0 18px 38px rgba(0,223,130,.24),
        0 0 0 1px rgba(255,255,255,.22) inset;
      font-size:14px;
      font-weight:950;
      cursor:pointer;
      pointer-events:auto;
      display:flex;
      align-items:center;
      justify-content:center;
      gap:8px;
    }

    @keyframes dhFadeUp{
      from{
        opacity:0;
        transform:translateY(10px);
      }
      to{
        opacity:1;
        transform:translateY(0);
      }
    }

    @media(min-width:768px){
      .dh-page{
        padding:22px 0;
      }

      .dh-phone{
        min-height:calc(100vh - 44px);
        border-radius:26px;
        overflow:hidden;
      }

      .dh-bottom-actions{
        bottom:22px;
        border-radius:0 0 26px 26px;
      }
    }

    @media(max-width:370px){
      .dh-title h1{
        font-size:21px;
      }

      .dh-filter-grid{
        grid-template-columns:1fr;
      }

      .dh-bank-name{
        max-width:130px;
      }

      .dh-hero-total{
        font-size:27px;
      }
    }
  </style>
</head>

<body>
  <main class="dh-page">
    <div class="dh-phone">

      <header class="dh-topbar">
        <div class="dh-brand">
          <button type="button" class="dh-back" onclick="goBack()" aria-label="Kembali">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M15 18 9 12l6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>

          <div class="dh-title">
            <h1>Riwayat Deposit</h1>
            <p>Transaksi pengisian saldo akun</p>
          </div>
        </div>

        <a href="{{ url('/deposit') }}" class="dh-header-icon" aria-label="Deposit Baru">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M12 5v14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
            <path d="M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
          </svg>
        </a>
      </header>

      <section class="dh-hero">
        <div class="dh-hero-inner">
          <div class="dh-hero-icon">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M4 7h16v10H4V7Z" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
              <path d="M8 11h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
              <path d="M16 13h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
            </svg>
          </div>

          <div>
            <p class="dh-hero-label">Total Deposit Berhasil</p>
            <h2 class="dh-hero-total">Rp {{ number_format($totalBerhasil, 0, ',', '.') }}</h2>
            <p class="dh-hero-sub">{{ $totalEntry }} transaksi deposit tersimpan di akun kamu.</p>
          </div>
        </div>
      </section>

      <section class="dh-filter-card">
        <div class="dh-filter-grid">
          <select id="monthFilter" class="dh-select" aria-label="Filter bulan">
            <option value="">Semua bulan</option>
          </select>

          <select id="statusFilter" class="dh-select" aria-label="Filter status">
            <option value="">Semua status</option>
            <option value="success">Berhasil</option>
            <option value="pending">Menunggu</option>
            <option value="failed">Gagal</option>
          </select>
        </div>
      </section>

      <section id="depositHistoryList" class="dh-list">
        @forelse($deposits as $deposit)
          @php
            $rawStatus = strtolower((string) data_get($deposit, 'status', 'pending'));

            if(in_array($rawStatus, ['berhasil', 'success', 'sukses', 'paid', 'completed'])) {
                $statusKey = 'success';
                $statusText = 'Berhasil';
            } elseif(in_array($rawStatus, ['failed', 'fail', 'gagal', 'cancelled', 'canceled', 'expired', 'rejected'])) {
                $statusKey = 'failed';
                $statusText = 'Gagal';
            } else {
                $statusKey = 'pending';
                $statusText = 'Menunggu';
            }

            $orderId = data_get($deposit, 'order_id')
              ?? data_get($deposit, 'merchant_order_id')
              ?? data_get($deposit, 'reference')
              ?? ('DEP-'.$deposit->id);

            $method = data_get($deposit, 'method') ?? data_get($deposit, 'payment_method') ?? 'QRIS / E-Wallet';
            $amount = (int) data_get($deposit, 'amount', 0);

            $dateValue = optional($deposit->created_at)->format('Y-m-d');
            $monthValue = optional($deposit->created_at)->format('Y-m');
          @endphp

          <article
            class="dh-card js-deposit-row"
            data-status="{{ $statusKey }}"
            data-month="{{ $monthValue }}"
          >
            <div class="dh-card-top">
              <div class="dh-bank">
                <div class="dh-bank-logo">
                  {{ strtoupper(substr($method, 0, 4)) }}
                </div>

                <div>
                  <div class="dh-bank-name">{{ $method }}</div>
                  <div class="dh-bank-number">#{{ $orderId }}</div>
                </div>
              </div>

              <div class="dh-status is-{{ $statusKey }}">
                {{ $statusText }}
              </div>
            </div>

            <div class="dh-card-body">
              <div class="dh-row">
                <span>Nominal Deposit</span>
                <strong>Rp {{ number_format($amount, 0, ',', '.') }}</strong>
              </div>

              <div class="dh-row">
                <span>Metode</span>
                <strong>{{ $method }}</strong>
              </div>

              <div class="dh-row is-total">
                <span>Total</span>
                <strong>Rp {{ number_format($amount, 0, ',', '.') }}</strong>
              </div>
            </div>

            <div class="dh-date">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M7 2v3M17 2v3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <rect x="3" y="5" width="18" height="16" rx="4" stroke="currentColor" stroke-width="2"/>
                <path d="M3 9h18" stroke="currentColor" stroke-width="2"/>
              </svg>

              {{ optional($deposit->created_at)->format('d M Y, H:i') ?? '-' }}

              @if($statusKey === 'pending')
                <a href="{{ route('deposit.invoice', $deposit->id) }}" class="dh-invoice-btn">
                  Lanjutkan
                </a>
              @else
                <a href="{{ route('deposit.invoice', $deposit->id) }}" class="dh-invoice-btn">
                  Detail
                </a>
              @endif
            </div>
          </article>
        @empty
          <div class="dh-empty">
            Belum ada riwayat deposit.
          </div>
        @endforelse
      </section>

    </div>
  </main>

  <div class="dh-bottom-actions">
    <a href="{{ url('/deposit') }}" class="dh-main-btn">
      Deposit Baru
      <span>↗</span>
    </a>
  </div>

  <script>
    function goBack(){
      if(window.history.length > 1){
        window.history.back();
        return;
      }

      window.location.href = '/akun';
    }

    (function(){
      const rows = Array.from(document.querySelectorAll('.js-deposit-row'));
      const monthFilter = document.getElementById('monthFilter');
      const statusFilter = document.getElementById('statusFilter');
      const list = document.getElementById('depositHistoryList');

      if(!rows.length || !monthFilter || !statusFilter) return;

      const monthNames = {
        '01':'Januari',
        '02':'Februari',
        '03':'Maret',
        '04':'April',
        '05':'Mei',
        '06':'Juni',
        '07':'Juli',
        '08':'Agustus',
        '09':'September',
        '10':'Oktober',
        '11':'November',
        '12':'Desember'
      };

      const months = [...new Set(rows.map(row => row.dataset.month).filter(Boolean))];

      months.sort().reverse().forEach(month => {
        const [year, monthNum] = month.split('-');
        const option = document.createElement('option');
        option.value = month;
        option.textContent = `${monthNames[monthNum] || monthNum} ${year}`;
        monthFilter.appendChild(option);
      });

      function applyFilter(){
        const selectedMonth = monthFilter.value;
        const selectedStatus = statusFilter.value;

        let visibleCount = 0;

        rows.forEach(row => {
          const matchMonth = !selectedMonth || row.dataset.month === selectedMonth;
          const matchStatus = !selectedStatus || row.dataset.status === selectedStatus;
          const show = matchMonth && matchStatus;

          row.style.display = show ? '' : 'none';

          if(show) visibleCount++;
        });

        let emptyFiltered = document.getElementById('depositEmptyFiltered');

        if(!visibleCount){
          if(!emptyFiltered){
            emptyFiltered = document.createElement('div');
            emptyFiltered.id = 'depositEmptyFiltered';
            emptyFiltered.className = 'dh-empty';
            emptyFiltered.textContent = 'Tidak ada riwayat deposit sesuai filter.';
            list.appendChild(emptyFiltered);
          }
        }else if(emptyFiltered){
          emptyFiltered.remove();
        }
      }

      monthFilter.addEventListener('change', applyFilter);
      statusFilter.addEventListener('change', applyFilter);
    })();
  </script>
</body>
</html>