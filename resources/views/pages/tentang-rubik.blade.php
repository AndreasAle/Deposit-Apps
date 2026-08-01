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
  <title>Tentang Capital Wave | Capital Wave</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --blue:#0A57A3; --blue-lite:#2f7fd4; --blue-soft:#eaf2fb;
      --navy:#0b2740; --navy-3:#07182a;
      --gold:#c99433; --gold-lite:#e8c874; --gold-deep:#a9772a;
      --gold-soft:#f7efdd;
      --gold-metal:linear-gradient(135deg,#a9772a 0%,#e8c874 46%,#c99433 100%);
      --green:#1fb97a; --green-soft:#e7f7f0;
      --card:#ffffff; --tint:#f5f8fc; --line:#e9edf4; --line-2:#dfe5ee;
      --ink:#152a3f; --ink-soft:#46586c; --muted:#8493a6;
      --sh-sm:0 6px 16px rgba(11,39,64,.06);
      --sh:0 14px 34px rgba(11,39,64,.09);
      --sh-navy:0 22px 48px rgba(11,39,64,.28);
    }

    *{box-sizing:border-box}
    html,body{min-height:100%}

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
      background:
        linear-gradient(rgba(11,39,64,.022) 1px, transparent 1px),
        linear-gradient(90deg, rgba(11,39,64,.016) 1px, transparent 1px);
      background-size:32px 32px;
      mask-image:linear-gradient(180deg, rgba(0,0,0,.38), transparent 76%);
      -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.38), transparent 76%);
      opacity:.7;
      z-index:0;
    }

    a{color:inherit;text-decoration:none}
    button{font-family:inherit}

    .ab-page{
      width:100%;
      min-height:100vh;
      display:flex;
      justify-content:center;
      padding:14px 10px 0;
      position:relative;
      z-index:1;
    }

    .ab-phone{
      width:100%;
      max-width:430px;
      min-height:100vh;
      position:relative;
      padding:8px 4px 108px;
    }

    /* Header */
    .ab-header{
      display:flex;
      align-items:center;
      gap:12px;
      margin-bottom:14px;
      padding:0 2px;
      animation:abFadeDown .42s ease both;
    }

    .ab-back{
      width:42px;
      height:42px;
      border-radius:14px;
      border:1px solid var(--line);
      background:var(--card);
      color:var(--navy);
      display:grid;
      place-items:center;
      box-shadow:var(--sh-sm);
      flex:0 0 auto;
      transition:.18s ease;
    }

    .ab-back:hover{transform:translateY(-1px);color:var(--blue)}
    .ab-back svg{width:20px;height:20px}

    .ab-title{min-width:0}
    .ab-title span{
      display:block;
      margin-bottom:5px;
      color:var(--gold-deep);
      font-size:9.5px;
      line-height:1;
      font-weight:700;
      letter-spacing:.17em;
      text-transform:uppercase;
    }
    .ab-title h1{
      margin:0;
      color:var(--navy);
      font-size:22px;
      line-height:1;
      font-weight:800;
      letter-spacing:-.04em;
      white-space:nowrap;
    }

    /* Hero */
    .ab-hero{
      position:relative;
      overflow:hidden;
      border-radius:28px;
      padding:20px;
      min-height:280px;
      background:
        radial-gradient(360px 220px at 92% -12%, rgba(232,200,116,.30), transparent 58%),
        radial-gradient(300px 200px at 2% 8%, rgba(47,127,212,.34), transparent 62%),
        linear-gradient(150deg,#0f3255 0%,#0b2740 54%,#07182a 100%);
      box-shadow:var(--sh-navy);
      margin-bottom:13px;
      animation:abFadeUp .48s ease both;
      color:#fff;
    }

    .ab-hero::after{
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

    .ab-orb{
      position:absolute;
      border-radius:999px;
      pointer-events:none;
    }

    .ab-orb.one{
      width:96px;height:96px;right:-26px;top:36px;
      background:rgba(232,200,116,.16);
      box-shadow:0 0 42px rgba(232,200,116,.18);
      animation:abOrbFloat1 5.4s ease-in-out infinite;
    }

    .ab-orb.two{
      width:58px;height:58px;left:22px;bottom:64px;
      background:rgba(47,127,212,.16);
      box-shadow:0 0 34px rgba(47,127,212,.18);
      animation:abOrbFloat2 4.8s ease-in-out infinite;
    }

    .ab-orb.three{
      width:28px;height:28px;right:92px;bottom:26px;
      background:rgba(232,200,116,.26);
      box-shadow:0 0 26px rgba(232,200,116,.24);
      animation:abOrbFloat3 3.8s ease-in-out infinite;
    }

    .ab-hero-content{position:relative;z-index:2}

    .ab-hero-badge{
      width:max-content;
      min-height:30px;
      padding:0 12px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      gap:7px;
      color:var(--gold-lite);
      background:rgba(255,255,255,.08);
      border:1px solid rgba(232,200,116,.32);
      font-size:10.5px;
      font-weight:700;
      letter-spacing:.04em;
      margin-bottom:13px;
    }

    .ab-hero-badge::before{
      content:"";
      width:7px;height:7px;
      border-radius:999px;
      background:var(--gold-lite);
      box-shadow:0 0 0 4px rgba(232,200,116,.14), 0 0 16px rgba(232,200,116,.60);
      animation:abPulseDot 1.8s ease-in-out infinite;
    }

    .ab-hero h2{
      margin:0;
      max-width:350px;
      color:#fff;
      font-size:29px;
      line-height:1.08;
      letter-spacing:-.055em;
      font-weight:800;
      text-shadow:0 12px 28px rgba(3,12,22,.28);
    }

    .ab-hero h2 span{
      color:var(--gold-lite);
      text-shadow:0 0 22px rgba(232,200,116,.28);
    }

    .ab-hero p{
      margin:11px 0 0;
      max-width:340px;
      color:rgba(255,255,255,.72);
      font-size:12.5px;
      line-height:1.58;
      font-weight:500;
    }

    /* Chart Visual */
    .ab-hero-visual{
      position:relative;
      z-index:2;
      margin-top:16px;
      border-radius:18px;
      overflow:hidden;
      background:rgba(255,255,255,.06);
      border:1px solid rgba(255,255,255,.14);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.12);
      padding:13px;
    }

    .ab-chart-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:8px}
    .ab-chart-head strong{color:#fff;font-size:12px;font-weight:700}
    .ab-chart-head span{color:var(--gold-lite);font-size:10.5px;font-weight:700;letter-spacing:.02em}

    .ab-chart{width:100%;height:88px;display:block;overflow:visible}
    .ab-chart-grid{stroke:rgba(255,255,255,.12);stroke-width:1}
    .ab-chart-area{fill:url(#abChartGradient);opacity:.6}

    .ab-chart-line{
      fill:none;
      stroke:#e8c874;
      stroke-width:3.5;
      stroke-linecap:round;
      stroke-linejoin:round;
      filter:drop-shadow(0 0 10px rgba(232,200,116,.40));
      stroke-dasharray:360;
      stroke-dashoffset:360;
      animation:abDrawLine 1.25s ease forwards .25s;
    }

    .ab-chart-dot{
      fill:#e8c874;
      filter:drop-shadow(0 0 8px rgba(232,200,116,.70));
      animation:abDotPulse 1.9s ease-in-out infinite;
    }

    /* Metrics */
    .ab-metrics{
      display:grid;
      grid-template-columns:repeat(3, 1fr);
      gap:9px;
      margin-bottom:13px;
      animation:abFadeUp .48s ease both .05s;
    }

    .ab-metric{
      min-height:90px;
      border-radius:20px;
      padding:12px 10px;
      position:relative;
      overflow:hidden;
      background:radial-gradient(circle at 82% 0%, var(--metric-glow), transparent 48%), var(--card);
      border:1px solid var(--line);
      box-shadow:var(--sh-sm);
    }

    .ab-metric::before{ content:""; position:absolute; top:0; left:0; right:0; height:2.5px; background:var(--metric-bar); }
    .ab-metric:nth-child(1){--metric-accent:var(--blue);--metric-glow:rgba(47,127,212,.10);--metric-bar:linear-gradient(90deg,#0A57A3,#2f7fd4)}
    .ab-metric:nth-child(2){--metric-accent:var(--gold-deep);--metric-glow:rgba(232,200,116,.14);--metric-bar:var(--gold-metal)}
    .ab-metric:nth-child(3){--metric-accent:var(--green);--metric-glow:rgba(31,185,122,.12);--metric-bar:linear-gradient(90deg,#1fb97a,#3fd39a)}

    .ab-metric span{display:block;color:var(--muted);font-size:10px;line-height:1.2;font-weight:600}
    .ab-metric strong{display:block;margin-top:8px;color:var(--navy);font-size:19px;line-height:1;font-weight:800;letter-spacing:-.04em}
    .ab-metric em{display:block;margin-top:7px;color:var(--metric-accent);font-size:10px;line-height:1.2;font-weight:700;font-style:normal}

    /* Cards */
    .ab-card{
      position:relative;
      overflow:hidden;
      border-radius:22px;
      background:radial-gradient(220px 120px at 96% 0%, rgba(47,127,212,.07), transparent 64%), var(--card);
      border:1px solid var(--line);
      box-shadow:var(--sh-sm);
      padding:18px;
      margin-bottom:13px;
      animation:abFadeUp .48s ease both;
    }

    .ab-card-kicker{
      display:inline-flex;
      min-height:26px;
      align-items:center;
      padding:0 11px;
      border-radius:999px;
      color:var(--blue);
      background:var(--blue-soft);
      border:1px solid rgba(10,87,163,.14);
      font-size:10px;
      font-weight:700;
      letter-spacing:.04em;
      margin-bottom:10px;
    }

    .ab-card h3{
      margin:0 0 9px;
      color:var(--navy);
      font-size:17px;
      line-height:1.25;
      font-weight:800;
      letter-spacing:-.03em;
    }

    .ab-card p{
      margin:0;
      color:var(--ink-soft);
      font-size:12.5px;
      line-height:1.62;
      font-weight:500;
    }

    .ab-card p + p{margin-top:10px}
    .ab-highlight{color:var(--navy);font-weight:700}

    /* Values Grid */
    .ab-values{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:9px;
      margin-bottom:13px;
    }

    .ab-value{
      min-height:130px;
      border-radius:20px;
      padding:14px;
      position:relative;
      overflow:hidden;
      background:radial-gradient(circle at 80% 0%, var(--value-glow), transparent 44%), var(--card);
      border:1px solid var(--line);
      box-shadow:var(--sh-sm);
      animation:abFadeUp .48s ease both;
    }

    .ab-value:nth-child(1){--value-accent:var(--blue);--value-glow:rgba(47,127,212,.10)}
    .ab-value:nth-child(2){--value-accent:var(--gold-deep);--value-glow:rgba(232,200,116,.12)}
    .ab-value:nth-child(3){--value-accent:var(--green);--value-glow:rgba(31,185,122,.10)}
    .ab-value:nth-child(4){--value-accent:var(--blue-lite);--value-glow:rgba(47,127,212,.10)}

    .ab-value-icon{
      width:40px;height:40px;
      border-radius:12px;
      display:grid;place-items:center;
      color:var(--value-accent);
      background:var(--tint);
      border:1px solid var(--line);
      margin-bottom:12px;
    }

    .ab-value-icon svg{width:20px;height:20px}
    .ab-value strong{display:block;color:var(--navy);font-size:13px;line-height:1.15;font-weight:800;margin-bottom:7px;letter-spacing:-.02em}
    .ab-value span{display:block;color:var(--ink-soft);font-size:11px;line-height:1.48;font-weight:500}

    /* Timeline */
    .ab-timeline{display:grid;gap:10px;margin-top:13px}

    .ab-step{
      position:relative;
      display:grid;
      grid-template-columns:42px 1fr;
      gap:11px;
      padding:13px;
      border-radius:16px;
      background:var(--tint);
      border:1px solid var(--line);
      overflow:hidden;
    }

    .ab-step::before{
      content:"";
      position:absolute;
      left:33px;top:54px;bottom:-16px;
      width:1px;
      background:linear-gradient(180deg, rgba(10,87,163,.30), transparent);
    }

    .ab-step:last-child::before{display:none}

    .ab-step-no{
      width:42px;height:42px;
      border-radius:13px;
      display:grid;place-items:center;
      color:#fff;
      background:linear-gradient(135deg,#123457,#0b2740);
      box-shadow:0 12px 22px rgba(11,39,64,.22);
      font-size:13px;font-weight:800;
      position:relative;z-index:1;
    }
    .ab-step-no::after{
      content:"";position:absolute;inset:0;border-radius:inherit;padding:1px;
      background:var(--gold-metal);
      -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
      -webkit-mask-composite:xor; mask-composite:exclude;
    }

    .ab-step-text strong{display:block;color:var(--navy);font-size:13px;font-weight:800;margin-bottom:5px}
    .ab-step-text span{display:block;color:var(--ink-soft);font-size:11.5px;line-height:1.48;font-weight:500}

    /* Trust */
    .ab-trust{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:9px;
      margin-bottom:13px;
    }

    .ab-trust-item{
      border-radius:18px;
      padding:14px;
      background:var(--card);
      border:1px solid var(--line);
      box-shadow:var(--sh-sm);
    }

    .ab-trust-item span{display:block;color:var(--muted);font-size:10px;font-weight:600;margin-bottom:7px;text-transform:uppercase;letter-spacing:.05em}
    .ab-trust-item strong{display:block;color:var(--navy);font-size:12.5px;line-height:1.25;font-weight:800;letter-spacing:-.02em}

    /* CTA */
    .ab-cta{display:grid;grid-template-columns:1fr;gap:9px;margin-top:13px}

    .ab-btn{
      min-height:50px;
      border-radius:14px;
      display:flex;align-items:center;justify-content:center;
      gap:8px;
      font-size:13.5px;font-weight:700;
      color:#fff;
      background:linear-gradient(135deg,#123457,#0b2740);
      box-shadow:0 14px 30px rgba(11,39,64,.24);
      transition:.18s ease;
    }

    .ab-btn:hover{transform:translateY(-1px);filter:brightness(1.06)}
    .ab-btn svg{width:18px;height:18px}

    .ab-btn.is-muted{
      color:var(--ink-soft);
      background:var(--card);
      border:1px solid var(--line);
      box-shadow:var(--sh-sm);
    }

    @keyframes abFadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}
    @keyframes abFadeDown{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}
    @keyframes abPulseDot{0%,100%{transform:scale(.92);opacity:.72}50%{transform:scale(1.18);opacity:1}}
    @keyframes abOrbFloat1{0%,100%{transform:translate3d(0,0,0) scale(1)}50%{transform:translate3d(-10px,10px,0) scale(1.08)}}
    @keyframes abOrbFloat2{0%,100%{transform:translate3d(0,0,0) scale(1)}50%{transform:translate3d(8px,-9px,0) scale(1.06)}}
    @keyframes abOrbFloat3{0%,100%{transform:translate3d(0,0,0) rotate(0deg)}50%{transform:translate3d(-7px,-10px,0) rotate(20deg)}}
    @keyframes abDrawLine{to{stroke-dashoffset:0}}
    @keyframes abDotPulse{0%,100%{transform:scale(.92);opacity:.72}50%{transform:scale(1.22);opacity:1}}

    @media(prefers-reduced-motion:reduce){
      *{animation:none !important}
      .ab-chart-line{stroke-dashoffset:0}
    }

    @media(max-width:370px){
      .ab-title h1{font-size:20px}
      .ab-hero h2{font-size:25px}
      .ab-metrics{gap:7px}
      .ab-metric{min-height:84px;padding:11px 8px}
      .ab-metric strong{font-size:17px}
      .ab-values{grid-template-columns:1fr}
      .ab-trust{grid-template-columns:1fr}
    }
  </style>
</head>

<body>
  <main class="ab-page">
    <div class="ab-phone">

      <header class="ab-header">
        <a href="{{ url('/akun') }}" class="ab-back" aria-label="Kembali ke akun">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M15 18 9 12l6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>
        <div class="ab-title">
          <span>Informasi aplikasi dan layanan</span>
          <h1>Tentang Capital Wave</h1>
        </div>
      </header>

      <section class="ab-hero">
        <span class="ab-orb one"></span>
        <span class="ab-orb two"></span>
        <span class="ab-orb three"></span>

        <div class="ab-hero-content">
          <div class="ab-hero-badge">Capital Wave</div>

          <h2>
            Ekosistem investasi digital yang <span>modern</span>, ringkas, dan mudah dipantau.
          </h2>

          <p>
            Capital Wave membantu pengguna mengelola saldo, investasi, riwayat transaksi, dan penarikan dana melalui satu dashboard yang rapi.
          </p>
        </div>

        <div class="ab-hero-visual">
          <div class="ab-chart-head">
            <strong>Perkembangan Platform</strong>
            <span>Live Growth</span>
          </div>

          <svg class="ab-chart" viewBox="0 0 360 100" preserveAspectRatio="none">
            <defs>
              <linearGradient id="abChartGradient" x1="0" x2="0" y1="0" y2="1">
                <stop offset="0%" stop-color="#e8c874" stop-opacity=".5"/>
                <stop offset="100%" stop-color="#e8c874" stop-opacity="0"/>
              </linearGradient>
            </defs>
            <path class="ab-chart-grid" d="M0 20H360M0 50H360M0 80H360"/>
            <path class="ab-chart-area" d="M0 76 C38 72 50 46 82 54 C118 64 126 30 162 35 C198 40 205 18 238 25 C286 34 300 12 360 16 L360 100 L0 100 Z"/>
            <path class="ab-chart-line" d="M0 76 C38 72 50 46 82 54 C118 64 126 30 162 35 C198 40 205 18 238 25 C286 34 300 12 360 16"/>
            <circle class="ab-chart-dot" cx="360" cy="16" r="5"/>
          </svg>
        </div>
      </section>

      <section class="ab-metrics">
        <div class="ab-metric">
          <span>Fokus</span>
          <strong>1 App</strong>
          <em>Semua aktivitas</em>
        </div>
        <div class="ab-metric">
          <span>Layanan</span>
          <strong>24/7</strong>
          <em>Monitoring sistem</em>
        </div>
        <div class="ab-metric">
          <span>Flow</span>
          <strong>Auto</strong>
          <em>Deposit &amp; WD</em>
        </div>
      </section>

      <section class="ab-card">
        <div class="ab-card-kicker">Tentang Kami</div>
        <h3>Capital Wave dibuat untuk pengalaman investasi yang lebih simpel.</h3>
        <p>
          <span class="ab-highlight">Capital Wave</span> adalah platform layanan digital yang dirancang untuk membantu pengguna menjalankan aktivitas investasi dengan alur yang lebih praktis, visual yang jelas, dan informasi yang mudah dipahami.
        </p>
        <p>
          Melalui satu akun, pengguna dapat melakukan deposit, memilih produk, memantau saldo, melihat riwayat transaksi, hingga mengajukan penarikan dana ke rekening atau e-wallet.
        </p>
      </section>

      <section class="ab-values">
        <div class="ab-value">
          <div class="ab-value-icon">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
            </svg>
          </div>
          <strong>Keamanan Akun</strong>
          <span>Data akun dan aktivitas pengguna disusun agar lebih mudah diawasi dan dipantau.</span>
        </div>

        <div class="ab-value">
          <div class="ab-value-icon">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M4 19V5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              <path d="M8 17V9" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              <path d="M12 17V7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              <path d="M16 17v-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              <path d="M20 17V4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
            </svg>
          </div>
          <strong>Transparansi</strong>
          <span>Saldo, investasi, deposit, dan withdraw ditampilkan dengan status yang jelas.</span>
        </div>

        <div class="ab-value">
          <div class="ab-value-icon">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M21 15a4 4 0 0 1-4 4H7l-4 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
          </div>
          <strong>Dukungan CS</strong>
          <span>Pengguna dapat menghubungi kanal resmi Capital Wave untuk bantuan dan informasi layanan.</span>
        </div>

        <div class="ab-value">
          <div class="ab-value-icon">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 3v18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              <path d="M5 10l7-7 7 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M5 21h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
            </svg>
          </div>
          <strong>Terus Berkembang</strong>
          <span>Capital Wave terus menyiapkan fitur baru untuk meningkatkan kenyamanan pengguna.</span>
        </div>
      </section>

      <section class="ab-card">
        <div class="ab-card-kicker">Alur Layanan</div>
        <h3>Semua proses dibuat ringkas dari awal sampai akhir.</h3>

        <div class="ab-timeline">
          <div class="ab-step">
            <div class="ab-step-no">01</div>
            <div class="ab-step-text">
              <strong>Deposit Saldo</strong>
              <span>Pengguna menambahkan saldo utama untuk memulai aktivitas di platform Capital Wave.</span>
            </div>
          </div>
          <div class="ab-step">
            <div class="ab-step-no">02</div>
            <div class="ab-step-text">
              <strong>Pilih Produk</strong>
              <span>Produk investasi ditampilkan dalam dashboard dengan informasi harga dan potensi profit.</span>
            </div>
          </div>
          <div class="ab-step">
            <div class="ab-step-no">03</div>
            <div class="ab-step-text">
              <strong>Pantau Aktivitas</strong>
              <span>Saldo, riwayat deposit, investasi aktif, dan penarikan dapat dipantau melalui menu akun.</span>
            </div>
          </div>
          <div class="ab-step">
            <div class="ab-step-no">04</div>
            <div class="ab-step-text">
              <strong>Withdraw Dana</strong>
              <span>Pengguna dapat mengajukan penarikan ke rekening atau e-wallet yang sudah ditambahkan.</span>
            </div>
          </div>
        </div>
      </section>

      <section class="ab-trust">
        <div class="ab-trust-item">
          <span>Dashboard</span>
          <strong>Mobile-first dan mudah digunakan</strong>
        </div>
        <div class="ab-trust-item">
          <span>Transaksi</span>
          <strong>Status deposit &amp; withdraw lebih jelas</strong>
        </div>
      </section>

      <section class="ab-card">
        <div class="ab-card-kicker">Komitmen Kami</div>
        <h3>Membangun layanan digital yang rapi, informatif, dan nyaman.</h3>
        <p>
          Capital Wave berkomitmen menghadirkan sistem yang mudah digunakan, tampilan yang modern, serta informasi yang tersusun jelas agar pengguna dapat mengambil keputusan dengan lebih percaya diri.
        </p>

        <div class="ab-cta">
          <a href="https://t.me/Capitalwavecs" target="_blank" rel="noopener" class="ab-btn">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M22 2 11 13" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M22 2 15 22 11 13 2 9 22 2" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Hubungi Layanan CS
          </a>
          <a href="{{ url('/akun') }}" class="ab-btn is-muted">
            Kembali ke Akun
          </a>
        </div>
      </section>

    </div>
  </main>

  @include('partials.bottom-nav')
</body>
</html>
