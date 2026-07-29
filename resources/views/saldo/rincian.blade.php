@include('partials.anti-inspect')
@php
  $type = $type ?? request('type', 'all');
  $activities = $activities ?? collect();

  $totalActivity = method_exists($activities, 'total') ? $activities->total() : $activities->count();

  $items = collect($activities instanceof \Illuminate\Pagination\AbstractPaginator ? $activities->items() : $activities);
  $totalDeposit    = $items->filter(fn($a) => ($a->activity_type ?? '') === 'deposit')->sum(fn($a) => (int) ($a->amount ?? 0));
  $totalInvestment = $items->filter(fn($a) => ($a->activity_type ?? '') !== 'deposit')->sum(fn($a) => (int) ($a->amount ?? 0));
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Rincian Saldo | Capital Wave</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --blue:#0A57A3; --blue-lite:#2f7fd4; --blue-soft:#eef4fb;
      --navy:#0b2740; --navy-2:#0d3357; --navy-3:#0a2036;
      --gold:#c99433; --gold-lite:#e8c874; --gold-deep:#a9772a; --gold-soft:#faf3e2;
      --gold-metal:linear-gradient(135deg,#a9772a 0%,#e8c874 46%,#c99433 100%);
      --green:#1c9d67; --green-soft:#e6f5ee; --red:#dc5757; --red-soft:#fdeaea;
      --card:#ffffff; --tint:#f5f8fc; --line:#e9edf4; --line-2:#dfe5ee;
      --ink:#152a3f; --ink-soft:#46586c; --muted:#8493a6; --muted-2:#aab6c4;
      --sh-sm:0 1px 2px rgba(11,39,64,.05);
      --sh:0 2px 6px rgba(11,39,64,.04), 0 12px 30px rgba(11,39,64,.07);
      --sh-navy:0 10px 24px rgba(9,30,52,.28), 0 24px 60px rgba(9,30,52,.30);
      --r:24px;
    }
    *{ box-sizing:border-box; }
    html,body{ min-height:100%; }
    body{
      margin:0; color:var(--ink);
      font-family:'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
      background:
        radial-gradient(900px 500px at 50% -220px, rgba(10,87,163,.10), transparent 60%),
        radial-gradient(600px 400px at 100% 8%, rgba(201,148,51,.06), transparent 55%),
        linear-gradient(180deg,#f2f5f9 0%,#eef1f6 40%,#eaeef4 100%);
      background-attachment:fixed; overflow-x:hidden; -webkit-font-smoothing:antialiased; letter-spacing:-.01em;
    }
    a{ color:inherit; text-decoration:none; } button{ font-family:inherit; }
    h1,h2,h3,p{ margin:0; }
    .sd-page{ width:100%; min-height:100vh; display:flex; justify-content:center; }
    .sd-phone{ width:100%; max-width:428px; min-height:100vh; padding:18px 16px 40px; }

    /* header */
    .sd-head{ display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:16px; }
    .sd-head-l{ display:flex; align-items:center; gap:11px; min-width:0; }
    .sd-back{ width:42px; height:42px; flex:0 0 auto; border:1px solid var(--line); background:var(--card); color:var(--navy); border-radius:13px; display:grid; place-items:center; box-shadow:var(--sh-sm); }
    .sd-back svg{ width:20px; height:20px; }
    .sd-head-l .t .name{ display:block; font-size:17px; font-weight:700; letter-spacing:-.02em; color:var(--navy); }
    .sd-head-l .t .tag{ display:block; margin-top:3px; font-size:10px; font-weight:600; letter-spacing:.14em; text-transform:uppercase; color:var(--muted); }
    .sd-head-btn{ width:42px; height:42px; flex:0 0 auto; border:1px solid var(--line); background:var(--card); color:var(--ink-soft); border-radius:13px; display:grid; place-items:center; box-shadow:var(--sh-sm); }
    .sd-head-btn svg{ width:19px; height:19px; }

    /* hero */
    .sd-hero{ position:relative; overflow:hidden; border-radius:26px; padding:20px; color:#fff;
      background:
        radial-gradient(420px 240px at 90% -20%, rgba(232,200,116,.18), transparent 62%),
        radial-gradient(360px 220px at 5% 120%, rgba(47,127,212,.22), transparent 60%),
        linear-gradient(150deg,#0f3255 0%,#0b2740 52%,#0a2036 100%);
      box-shadow:var(--sh-navy); }
    .sd-hero::before{ content:""; position:absolute; inset:0; border-radius:inherit; padding:1px; pointer-events:none;
      background:linear-gradient(150deg, rgba(232,200,116,.6), rgba(232,200,116,0) 34%, rgba(255,255,255,.14) 100%);
      -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; }
    .sd-hero > *{ position:relative; z-index:1; }
    .sd-eyebrow{ display:inline-flex; align-items:center; gap:8px; color:rgba(255,255,255,.62); font-size:9.5px; font-weight:600; letter-spacing:.18em; text-transform:uppercase; }
    .sd-eyebrow::before{ content:""; width:6px; height:6px; border-radius:999px; background:var(--gold-lite); box-shadow:0 0 10px rgba(232,200,116,.8); }
    .sd-hero-count{ margin-top:12px; display:flex; align-items:baseline; gap:8px; }
    .sd-hero-count b{ font-size:32px; font-weight:700; letter-spacing:-.03em; }
    .sd-hero-count span{ font-size:12px; font-weight:500; color:rgba(255,255,255,.55); }
    .sd-hero-boxes{ margin-top:16px; display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    .sd-hbox{ border-radius:16px; padding:12px 13px; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.14); }
    .sd-hbox span{ display:flex; align-items:center; gap:6px; font-size:10.5px; font-weight:500; color:rgba(255,255,255,.6); }
    .sd-hbox span svg{ width:13px; height:13px; }
    .sd-hbox.in span svg{ color:#7fe3b4; } .sd-hbox.out span svg{ color:var(--gold-lite); }
    .sd-hbox strong{ display:block; margin-top:7px; font-size:14px; font-weight:700; letter-spacing:-.02em; }

    /* chips */
    .sd-chips{ margin-top:20px; display:flex; gap:8px; padding:6px; border-radius:16px; background:var(--card); border:1px solid var(--line); box-shadow:var(--sh-sm); }
    .sd-chip{ flex:1; height:38px; display:flex; align-items:center; justify-content:center; border-radius:11px; color:var(--ink-soft); font-size:12.5px; font-weight:600; transition:.16s ease; }
    .sd-chip.is-active{ color:#fff; background:linear-gradient(135deg,#123457,#0b2740); box-shadow:0 8px 16px rgba(11,39,64,.2); }

    /* list */
    .sd-list{ margin-top:14px; display:flex; flex-direction:column; gap:10px; }
    .sd-row{ border-radius:18px; background:var(--card); border:1px solid var(--line); box-shadow:var(--sh-sm); padding:13px 14px; opacity:0; transform:translateY(10px); animation:sdUp .45s cubic-bezier(.22,.8,.22,1) forwards; }
    .sd-row-inner{ display:flex; align-items:center; gap:12px; }
    .sd-icon{ width:44px; height:44px; flex:0 0 auto; border-radius:13px; display:grid; place-items:center; }
    .sd-row.is-deposit .sd-icon{ color:var(--green); background:var(--green-soft); }
    .sd-row.is-investment .sd-icon{ color:var(--blue); background:var(--blue-soft); }
    .sd-icon svg{ width:21px; height:21px; }
    .sd-row-info{ min-width:0; flex:1; }
    .sd-row-title{ font-size:14px; font-weight:700; color:var(--navy); letter-spacing:-.01em; }
    .sd-row-meta{ margin-top:4px; font-size:10.5px; font-weight:500; color:var(--muted); overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .sd-row-right{ text-align:right; flex:0 0 auto; }
    .sd-amount{ font-size:14px; font-weight:700; letter-spacing:-.02em; white-space:nowrap; }
    .sd-row.is-deposit .sd-amount{ color:var(--green); }
    .sd-row.is-investment .sd-amount{ color:var(--navy); }
    .sd-badge{ margin-top:5px; display:inline-block; padding:2px 8px; border-radius:7px; font-size:9px; font-weight:700; letter-spacing:.04em; color:var(--muted); background:var(--tint); }
    .sd-row.is-deposit .sd-badge{ color:var(--green); background:var(--green-soft); }

    /* empty */
    .sd-empty{ margin-top:14px; padding:34px 22px; border-radius:var(--r); background:var(--card); border:1px dashed var(--line-2); text-align:center; }
    .sd-empty-icon{ width:64px; height:64px; margin:0 auto; border-radius:20px; display:grid; place-items:center; color:var(--blue); background:var(--blue-soft); }
    .sd-empty-icon svg{ width:30px; height:30px; }
    .sd-empty h3{ margin-top:16px; font-size:16px; font-weight:700; color:var(--navy); }
    .sd-empty p{ margin-top:8px; font-size:12.5px; font-weight:500; color:var(--muted); line-height:1.55; }
    .sd-empty-actions{ margin-top:18px; display:flex; gap:10px; justify-content:center; }
    .sd-btn{ display:inline-flex; align-items:center; gap:7px; height:46px; padding:0 18px; border-radius:13px; border:1px solid var(--line-2); background:var(--card); color:var(--ink-soft); font-size:13px; font-weight:600; }
    .sd-btn.is-primary{ border:0; color:#fff; background:linear-gradient(135deg,#123457,#0b2740); box-shadow:0 10px 22px rgba(11,39,64,.24); }
    .sd-btn svg{ width:16px; height:16px; }

    .sd-pager{ margin-top:16px; display:flex; justify-content:center; }
    .sd-pager a,.sd-pager span{ font-size:12px; font-weight:600; }

    @keyframes sdUp{ to{ opacity:1; transform:translateY(0); } }
    @media (prefers-reduced-motion:reduce){ *,*::before,*::after{ animation:none !important; transition:none !important; } .sd-row{ opacity:1; transform:none; } }
  </style>
</head>

<body>
  <main class="sd-page">
    <div class="sd-phone">

      <header class="sd-head">
        <div class="sd-head-l">
          <a href="{{ url('/dashboard') }}" class="sd-back" aria-label="Kembali">
            <svg viewBox="0 0 24 24" fill="none"><path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
          <div class="t"><span class="name">Rincian Saldo</span><span class="tag">Riwayat transaksi</span></div>
        </div>
        <a href="{{ url('/deposit') }}" class="sd-head-btn" aria-label="Isi Saldo">
          <svg viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </a>
      </header>

      <section class="sd-hero">
        <span class="sd-eyebrow">Ringkasan Aktivitas</span>
        <div class="sd-hero-count"><b>{{ number_format($totalActivity, 0, ',', '.') }}</b><span>total transaksi</span></div>
        <div class="sd-hero-boxes">
          <div class="sd-hbox in">
            <span><svg viewBox="0 0 24 24" fill="none"><path d="M12 19V5M5 12l7-7 7 7" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg> Uang Masuk</span>
            <strong>Rp {{ number_format((int)$totalDeposit, 0, ',', '.') }}</strong>
          </div>
          <div class="sd-hbox out">
            <span><svg viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12l7 7 7-7" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg> Uang Keluar</span>
            <strong>Rp {{ number_format((int)$totalInvestment, 0, ',', '.') }}</strong>
          </div>
        </div>
      </section>

      <div class="sd-chips">
        <a class="sd-chip {{ $type === 'all' ? 'is-active' : '' }}" href="{{ route('saldo.rincian', ['type' => 'all']) }}">Semua</a>
        <a class="sd-chip {{ $type === 'deposit' ? 'is-active' : '' }}" href="{{ route('saldo.rincian', ['type' => 'deposit']) }}">Deposit</a>
        <a class="sd-chip {{ $type === 'investment' ? 'is-active' : '' }}" href="{{ route('saldo.rincian', ['type' => 'investment']) }}">Investasi</a>
      </div>

      <div class="sd-list">
        @forelse($activities as $a)
          @php
            $isDeposit = $a->activity_type === 'deposit';
            $title = $isDeposit ? 'Isi Ulang Saldo' : 'Pembelian Investasi';
            $date = \Carbon\Carbon::parse($a->happened_at)->format('d M Y • H:i');
            $sub = $isDeposit
              ? ($a->method ? "Metode {$a->method} · Ref {$a->ref}" : "Ref {$a->ref}")
              : (($a->product_name ? "Produk {$a->product_name}" : "Produk investasi") . " · ID {$a->ref}");
            $badge = $isDeposit ? ($a->status ?? 'PAID') : ($a->status ?? 'ACTIVE');
          @endphp

          <article class="sd-row {{ $isDeposit ? 'is-deposit' : 'is-investment' }}" style="animation-delay: {{ $loop->index * 0.04 }}s;">
            <div class="sd-row-inner">
              <div class="sd-icon" aria-hidden="true">
                @if($isDeposit)
                  <svg viewBox="0 0 24 24" fill="none"><path d="M12 19V5M5 12l7-7 7 7" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/></svg>
                @else
                  <svg viewBox="0 0 24 24" fill="none"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M3 6h18M16 10a4 4 0 0 1-8 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                @endif
              </div>
              <div class="sd-row-info">
                <h3 class="sd-row-title">{{ $title }}</h3>
                <p class="sd-row-meta">{{ $date }} · {{ $sub }}</p>
              </div>
              <div class="sd-row-right">
                <div class="sd-amount">{{ $isDeposit ? '+' : '−' }} Rp {{ number_format((int)$a->amount, 0, ',', '.') }}</div>
                <div class="sd-badge">{{ strtoupper($badge) }}</div>
              </div>
            </div>
          </article>
        @empty
          <div class="sd-empty">
            <div class="sd-empty-icon"><svg viewBox="0 0 24 24" fill="none"><path d="M12 2v20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M17 7H7a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg></div>
            <h3>Belum ada aktivitas saldo</h3>
            <p>Aktivitas muncul otomatis saat kamu deposit atau membeli produk investasi.</p>
            <div class="sd-empty-actions">
              <a class="sd-btn is-primary" href="{{ url('/deposit') }}"><svg viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg> Isi Saldo</a>
              <a class="sd-btn" href="{{ url('/dashboard') }}"><svg viewBox="0 0 24 24" fill="none"><path d="M15 18 9 12l6-6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg> Kembali</a>
            </div>
          </div>
        @endforelse
      </div>

      @if(method_exists($activities, 'links'))
        <div class="sd-pager">{{ $activities->links() }}</div>
      @endif
    </div>
  </main>
</body>
</html>
