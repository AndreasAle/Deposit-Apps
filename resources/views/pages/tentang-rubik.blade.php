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
  <title>Tentang Velora | Velora Finance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,600;1,700&display=swap" rel="stylesheet">

  <style>
    :root{
      --vl-bg:#f7f2fa;
      --vl-paper:#ffffff;
      --vl-paper2:#fbf8ff;
      --vl-text:#2b0b16;
      --vl-maroon:#3a0712;
      --vl-soft:#7b6370;
      --vl-muted:#a894a0;
      --vl-border:rgba(43,11,22,.085);
      --vl-gold:#f5af2a;
      --vl-gold2:#ffd46d;
      --vl-purple:#8f57ff;
      --vl-violet:#b45cff;
      --vl-pink:#d96bff;
      --vl-green:#20b873;
      --vl-blue:#3978ff;
      --vl-red:#e24a64;
      --vl-gradient:linear-gradient(135deg,#f5af2a 0%,#ffd46d 26%,#d96bff 58%,#8f57ff 100%);
      --vl-gradient-soft:linear-gradient(145deg,#8f57ff 0%,#9f55ff 38%,#d96bff 72%,#f5af2a 100%);
      --vl-shadow:0 22px 54px rgba(88,43,145,.15);
      --vl-shadow-soft:0 13px 30px rgba(43,11,22,.075);
    }

    *{box-sizing:border-box}
    html,body{min-height:100%}

    body{
      margin:0;
      font-family:"Plus Jakarta Sans", Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      color:var(--vl-text);
      background:
        radial-gradient(680px 360px at 50% -150px, rgba(245,175,42,.23), transparent 64%),
        radial-gradient(520px 340px at 100% 4%, rgba(217,107,255,.18), transparent 62%),
        radial-gradient(520px 330px at -12% 34%, rgba(143,87,255,.13), transparent 58%),
        linear-gradient(180deg,#fff 0%,#f7f2fa 44%,#eee8f6 100%);
      overflow-x:hidden;
      -webkit-tap-highlight-color:transparent;
    }

    body::before{
      content:"";
      position:fixed;
      inset:0;
      pointer-events:none;
      background:
        linear-gradient(rgba(43,11,22,.026) 1px, transparent 1px),
        linear-gradient(90deg, rgba(43,11,22,.018) 1px, transparent 1px);
      background-size:32px 32px;
      mask-image:linear-gradient(180deg, rgba(0,0,0,.40), transparent 76%);
      -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.40), transparent 76%);
      opacity:.55;
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
      border-radius:16px;
      border:1px solid rgba(43,11,22,.08);
      background:rgba(255,255,255,.88);
      color:#5b2841;
      display:grid;
      place-items:center;
      box-shadow:0 12px 26px rgba(43,11,22,.065), inset 0 1px 0 rgba(255,255,255,.92);
      backdrop-filter:blur(18px);
      flex:0 0 auto;
      transition:.18s ease;
    }

    .ab-back:hover{transform:translateY(-1px);color:var(--vl-purple)}
    .ab-back svg{width:20px;height:20px}

    .ab-title{min-width:0}
    .ab-title span{
      display:block;
      margin-bottom:5px;
      color:rgba(58,7,18,.58);
      font-size:10px;
      line-height:1;
      font-weight:800;
      letter-spacing:.15em;
      text-transform:uppercase;
    }
    .ab-title h1{
      margin:0;
      color:var(--vl-maroon);
      font-size:22px;
      line-height:1;
      font-weight:800;
      letter-spacing:-.052em;
      white-space:nowrap;
    }

    /* Hero */
    .ab-hero{
      position:relative;
      overflow:hidden;
      border-radius:30px;
      padding:20px;
      min-height:286px;
      background:
        radial-gradient(360px 220px at 92% -12%, rgba(255,212,109,.45), transparent 58%),
        radial-gradient(300px 200px at 2% 8%, rgba(217,107,255,.34), transparent 62%),
        linear-gradient(145deg,#8f57ff 0%,#9455ff 40%,#d96bff 72%,#f5af2a 100%);
      border:1px solid rgba(255,255,255,.44);
      box-shadow:0 28px 62px rgba(143,87,255,.20), 0 18px 42px rgba(245,175,42,.10), inset 0 1px 0 rgba(255,255,255,.22);
      margin-bottom:13px;
      animation:abFadeUp .48s ease both;
      color:#fff;
    }

    .ab-hero::before{
      content:"";
      position:absolute;
      inset:0;
      background:linear-gradient(135deg, rgba(255,255,255,.22), transparent 34%), radial-gradient(circle at 82% 26%, rgba(255,255,255,.16), transparent 28%);
      pointer-events:none;
    }

    .ab-orb{
      position:absolute;
      border-radius:999px;
      pointer-events:none;
    }

    .ab-orb.one{
      width:96px;height:96px;right:-26px;top:36px;
      background:rgba(255,212,109,.18);
      box-shadow:0 0 42px rgba(255,212,109,.22);
      animation:abOrbFloat1 5.4s ease-in-out infinite;
    }

    .ab-orb.two{
      width:58px;height:58px;left:22px;bottom:64px;
      background:rgba(255,255,255,.12);
      box-shadow:0 0 34px rgba(255,255,255,.16);
      animation:abOrbFloat2 4.8s ease-in-out infinite;
    }

    .ab-orb.three{
      width:28px;height:28px;right:92px;bottom:26px;
      background:rgba(245,175,42,.28);
      box-shadow:0 0 26px rgba(245,175,42,.24);
      animation:abOrbFloat3 3.8s ease-in-out infinite;
    }

    .ab-hero-content{position:relative;z-index:2}

    .ab-hero-badge{
      width:max-content;
      min-height:32px;
      padding:0 12px;
      border-radius:999px;
      display:inline-flex;
      align-items:center;
      gap:7px;
      color:#fff;
      background:rgba(255,255,255,.16);
      border:1px solid rgba(255,255,255,.28);
      font-size:11px;
      font-weight:900;
      margin-bottom:13px;
      box-shadow:inset 0 1px 0 rgba(255,255,255,.18);
    }

    .ab-hero-badge::before{
      content:"";
      width:7px;height:7px;
      border-radius:999px;
      background:var(--vl-gold2);
      box-shadow:0 0 0 4px rgba(255,212,109,.18), 0 0 16px rgba(255,212,109,.60);
      animation:abPulseDot 1.8s ease-in-out infinite;
    }

    .ab-hero h2{
      margin:0;
      max-width:350px;
      color:#fff;
      font-size:30px;
      line-height:1.04;
      letter-spacing:-.065em;
      font-weight:900;
      text-shadow:0 12px 28px rgba(43,11,22,.18);
    }

    .ab-hero h2 span{
      color:var(--vl-gold2);
      text-shadow:0 0 22px rgba(255,212,109,.30);
    }

    .ab-hero p{
      margin:11px 0 0;
      max-width:340px;
      color:rgba(255,255,255,.78);
      font-size:13px;
      line-height:1.55;
      font-weight:600;
    }

    /* Chart Visual */
    .ab-hero-visual{
      position:relative;
      z-index:2;
      margin-top:16px;
      border-radius:20px;
      overflow:hidden;
      background:rgba(255,255,255,.12);
      border:1px solid rgba(255,255,255,.22);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.18), 0 16px 30px rgba(43,11,22,.12);
      padding:13px;
    }

    .ab-chart-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:8px}
    .ab-chart-head strong{color:#fff;font-size:12px;font-weight:900}
    .ab-chart-head span{color:var(--vl-gold2);font-size:11px;font-weight:900}

    .ab-chart{width:100%;height:88px;display:block;overflow:visible}
    .ab-chart-grid{stroke:rgba(255,255,255,.14);stroke-width:1}
    .ab-chart-area{fill:url(#abChartGradient);opacity:.56}

    .ab-chart-line{
      fill:none;
      stroke:#ffd46d;
      stroke-width:4;
      stroke-linecap:round;
      stroke-linejoin:round;
      filter:drop-shadow(0 0 10px rgba(255,212,109,.40));
      stroke-dasharray:360;
      stroke-dashoffset:360;
      animation:abDrawLine 1.25s ease forwards .25s;
    }

    .ab-chart-dot{
      fill:#ffd46d;
      filter:drop-shadow(0 0 8px rgba(255,212,109,.70));
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
      min-height:92px;
      border-radius:22px;
      padding:12px 10px;
      position:relative;
      overflow:hidden;
      background:radial-gradient(circle at 82% 0%, var(--metric-glow), transparent 48%), linear-gradient(180deg,rgba(255,255,255,.95),rgba(255,255,255,.85));
      border:1px solid rgba(43,11,22,.075);
      box-shadow:var(--vl-shadow-soft), inset 0 1px 0 rgba(255,255,255,.9);
    }

    .ab-metric:nth-child(1){--metric-accent:var(--vl-purple);--metric-glow:rgba(143,87,255,.14)}
    .ab-metric:nth-child(2){--metric-accent:var(--vl-pink);--metric-glow:rgba(217,107,255,.14)}
    .ab-metric:nth-child(3){--metric-accent:var(--vl-gold);--metric-glow:rgba(245,175,42,.14)}

    .ab-metric span{display:block;color:var(--vl-soft);font-size:10px;line-height:1.2;font-weight:750}
    .ab-metric strong{display:block;margin-top:8px;color:var(--vl-maroon);font-size:19px;line-height:1;font-weight:900;letter-spacing:-.04em}
    .ab-metric em{display:block;margin-top:7px;color:var(--metric-accent);font-size:10px;line-height:1.2;font-weight:900;font-style:normal}

    /* Cards */
    .ab-card{
      position:relative;
      overflow:hidden;
      border-radius:24px;
      background:radial-gradient(220px 120px at 96% 0%, rgba(217,107,255,.10), transparent 64%), linear-gradient(180deg,rgba(255,255,255,.98),rgba(255,255,255,.90));
      border:1px solid rgba(43,11,22,.075);
      box-shadow:var(--vl-shadow-soft), inset 0 1px 0 rgba(255,255,255,.94);
      padding:18px;
      margin-bottom:13px;
      animation:abFadeUp .48s ease both;
    }

    .ab-card::before{
      content:"";
      position:absolute;
      inset:0;
      background:linear-gradient(135deg,rgba(255,255,255,.76),transparent 30%);
      pointer-events:none;
    }

    .ab-card > *{position:relative;z-index:1}

    .ab-card-kicker{
      display:inline-flex;
      min-height:27px;
      align-items:center;
      padding:0 11px;
      border-radius:999px;
      color:var(--vl-purple);
      background:rgba(143,87,255,.08);
      border:1px solid rgba(143,87,255,.16);
      font-size:10px;
      font-weight:900;
      margin-bottom:10px;
    }

    .ab-card h3{
      margin:0 0 9px;
      color:var(--vl-maroon);
      font-size:17px;
      line-height:1.2;
      font-weight:900;
      letter-spacing:-.035em;
    }

    .ab-card p{
      margin:0;
      color:var(--vl-soft);
      font-size:13px;
      line-height:1.62;
      font-weight:560;
    }

    .ab-card p + p{margin-top:10px}
    .ab-highlight{color:var(--vl-maroon);font-weight:850}

    /* Values Grid */
    .ab-values{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:9px;
      margin-bottom:13px;
    }

    .ab-value{
      min-height:132px;
      border-radius:22px;
      padding:14px;
      position:relative;
      overflow:hidden;
      background:radial-gradient(circle at 80% 0%, var(--value-glow), transparent 44%), linear-gradient(180deg,rgba(255,255,255,.97),rgba(255,255,255,.88));
      border:1px solid rgba(43,11,22,.075);
      box-shadow:var(--vl-shadow-soft), inset 0 1px 0 rgba(255,255,255,.9);
      animation:abFadeUp .48s ease both;
    }

    .ab-value:nth-child(1){--value-accent:var(--vl-purple);--value-glow:rgba(143,87,255,.12)}
    .ab-value:nth-child(2){--value-accent:var(--vl-pink);--value-glow:rgba(217,107,255,.12)}
    .ab-value:nth-child(3){--value-accent:var(--vl-gold);--value-glow:rgba(245,175,42,.12)}
    .ab-value:nth-child(4){--value-accent:var(--vl-blue);--value-glow:rgba(57,120,255,.12)}

    .ab-value-icon{
      width:40px;height:40px;
      border-radius:14px;
      display:grid;place-items:center;
      color:var(--value-accent);
      background:rgba(43,11,22,.05);
      border:1px solid rgba(43,11,22,.07);
      margin-bottom:12px;
      box-shadow:0 8px 16px rgba(43,11,22,.06);
    }

    .ab-value-icon svg{width:20px;height:20px}
    .ab-value strong{display:block;color:var(--vl-maroon);font-size:13px;line-height:1.15;font-weight:900;margin-bottom:7px;letter-spacing:-.02em}
    .ab-value span{display:block;color:var(--vl-soft);font-size:11px;line-height:1.48;font-weight:560}

    /* Timeline */
    .ab-timeline{display:grid;gap:10px;margin-top:13px}

    .ab-step{
      position:relative;
      display:grid;
      grid-template-columns:42px 1fr;
      gap:11px;
      padding:13px;
      border-radius:18px;
      background:rgba(143,87,255,.04);
      border:1px solid rgba(143,87,255,.10);
      overflow:hidden;
    }

    .ab-step::before{
      content:"";
      position:absolute;
      left:33px;top:54px;bottom:-16px;
      width:1px;
      background:linear-gradient(180deg, rgba(143,87,255,.35), transparent);
    }

    .ab-step:last-child::before{display:none}

    .ab-step-no{
      width:42px;height:42px;
      border-radius:14px;
      display:grid;place-items:center;
      color:#2c1200;
      background:var(--vl-gradient);
      box-shadow:0 14px 24px rgba(143,87,255,.20), inset 0 1px 0 rgba(255,255,255,.30);
      font-size:13px;font-weight:900;
      position:relative;z-index:1;
    }

    .ab-step-text strong{display:block;color:var(--vl-maroon);font-size:13px;font-weight:900;margin-bottom:5px}
    .ab-step-text span{display:block;color:var(--vl-soft);font-size:11.5px;line-height:1.48;font-weight:560}

    /* Trust */
    .ab-trust{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:9px;
      margin-bottom:13px;
    }

    .ab-trust-item{
      border-radius:20px;
      padding:14px;
      background:radial-gradient(circle at 100% 0%, rgba(143,87,255,.10), transparent 45%), linear-gradient(180deg,rgba(255,255,255,.96),rgba(255,255,255,.88));
      border:1px solid rgba(43,11,22,.075);
      box-shadow:var(--vl-shadow-soft);
    }

    .ab-trust-item span{display:block;color:var(--vl-soft);font-size:10.5px;font-weight:750;margin-bottom:7px}
    .ab-trust-item strong{display:block;color:var(--vl-maroon);font-size:12.5px;line-height:1.25;font-weight:900;letter-spacing:-.02em}

    /* CTA */
    .ab-cta{display:grid;grid-template-columns:1fr;gap:9px;margin-top:13px}

    .ab-btn{
      min-height:50px;
      border-radius:999px;
      display:flex;align-items:center;justify-content:center;
      gap:8px;
      font-size:13.5px;font-weight:900;
      color:#2c1200;
      background:var(--vl-gradient);
      box-shadow:0 18px 38px rgba(143,87,255,.22), inset 0 1px 0 rgba(255,255,255,.34);
      transition:.18s ease;
    }

    .ab-btn:hover{transform:translateY(-1px);filter:brightness(1.04)}
    .ab-btn svg{width:18px;height:18px}

    .ab-btn.is-muted{
      color:var(--vl-soft);
      background:rgba(255,255,255,.82);
      border:1px solid rgba(43,11,22,.10);
      box-shadow:0 8px 18px rgba(43,11,22,.06);
    }

    @keyframes abFadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}
    @keyframes abFadeDown{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}
    @keyframes abPulseDot{0%,100%{transform:scale(.92);opacity:.72}50%{transform:scale(1.18);opacity:1}}
    @keyframes abOrbFloat1{0%,100%{transform:translate3d(0,0,0) scale(1)}50%{transform:translate3d(-10px,10px,0) scale(1.08)}}
    @keyframes abOrbFloat2{0%,100%{transform:translate3d(0,0,0) scale(1)}50%{transform:translate3d(8px,-9px,0) scale(1.06)}}
    @keyframes abOrbFloat3{0%,100%{transform:translate3d(0,0,0) rotate(0deg)}50%{transform:translate3d(-7px,-10px,0) rotate(20deg)}}
    @keyframes abDrawLine{to{stroke-dashoffset:0}}
    @keyframes abDotPulse{0%,100%{transform:scale(.92);opacity:.72}50%{transform:scale(1.22);opacity:1}}

    @media(max-width:370px){
      .ab-title h1{font-size:20px}
      .ab-hero h2{font-size:26px}
      .ab-metrics{gap:7px}
      .ab-metric{min-height:86px;padding:11px 8px}
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
          <h1>Tentang Velora</h1>
        </div>
      </header>

      <section class="ab-hero">
        <span class="ab-orb one"></span>
        <span class="ab-orb two"></span>
        <span class="ab-orb three"></span>

        <div class="ab-hero-content">
          <div class="ab-hero-badge">Velora Finance</div>

          <h2>
            Ekosistem investasi digital yang <span>modern</span>, ringkas, dan mudah dipantau.
          </h2>

          <p>
            Velora membantu pengguna mengelola saldo, investasi, riwayat transaksi, dan penarikan dana melalui satu dashboard yang rapi.
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
                <stop offset="0%" stop-color="#ffd46d" stop-opacity=".55"/>
                <stop offset="100%" stop-color="#ffd46d" stop-opacity="0"/>
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
          <em>Deposit & WD</em>
        </div>
      </section>

      <section class="ab-card">
        <div class="ab-card-kicker">Tentang Kami</div>
        <h3>Velora dibuat untuk pengalaman investasi yang lebih simpel.</h3>
        <p>
          <span class="ab-highlight">Velora Finance</span> adalah platform layanan digital yang dirancang untuk membantu pengguna menjalankan aktivitas investasi dengan alur yang lebih praktis, visual yang jelas, dan informasi yang mudah dipahami.
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
          <span>Pengguna dapat menghubungi kanal resmi Velora untuk bantuan dan informasi layanan.</span>
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
          <span>Velora terus menyiapkan fitur baru untuk meningkatkan kenyamanan pengguna.</span>
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
              <span>Pengguna menambahkan saldo utama untuk memulai aktivitas di platform Velora.</span>
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
          <strong>Status deposit & withdraw lebih jelas</strong>
        </div>
      </section>

      <section class="ab-card">
        <div class="ab-card-kicker">Komitmen Kami</div>
        <h3>Membangun layanan digital yang rapi, informatif, dan nyaman.</h3>
        <p>
          Velora berkomitmen menghadirkan sistem yang mudah digunakan, tampilan yang modern, serta informasi yang tersusun jelas agar pengguna dapat mengambil keputusan dengan lebih percaya diri.
        </p>

        <div class="ab-cta">
          <a href="https://t.me/rubikcompanycs" target="_blank" rel="noopener" class="ab-btn">
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
