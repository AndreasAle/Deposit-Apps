@include('partials.anti-inspect')
@php
  $user = $user ?? auth()->user();

  $refUsers = $refUsers ?? collect();
  $commissions = $commissions ?? collect();

  $totalCommission = (int) ($totalCommission ?? 0);
  $totalReferral = (int) ($refCount ?? 0);
  $saldoKomisi = (int) data_get($user, 'referral_earned_total', 0);

  $referralCode = data_get($user, 'referral_code', '-');

  $referralLink = $referralCode && $referralCode !== '-'
      ? url('/r/' . urlencode($referralCode))
      : route('home');

  // QR code dari link referral (biar gampang disebar tanpa harus followers banyak)
  $referralQr = null;
  try {
      $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
          ->size(260)->margin(1)->errorCorrection('M')->generate($referralLink);
      $referralQr = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);
  } catch (\Throwable $e) {
      $referralQr = null;
  }

  // Struktur komisi 3 tingkat (display)
  $levels = [
    ['n' => 1, 'pct' => 32, 'label' => 'Referral langsung', 'tag' => 'Direct', 'anggota' => $totalReferral, 'komisi' => $totalCommission, 'investasi' => 0],
    ['n' => 2, 'pct' => 2,  'label' => 'Jaringan kedua',    'tag' => 'Tim',    'anggota' => 0, 'komisi' => 0, 'investasi' => 0],
    ['n' => 3, 'pct' => 1,  'label' => 'Jaringan ketiga',   'tag' => 'Tim',    'anggota' => 0, 'komisi' => 0, 'investasi' => 0],
  ];
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Program Referral | Capital Wave</title>
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
      --silver:linear-gradient(135deg,#9aa6b4,#e4e9ef 46%,#aab4c2);
      --bronze:linear-gradient(135deg,#a5673a,#e0a878 46%,#b0754a);
      --green:#1c9d67; --green-soft:#e6f5ee; --red:#dc5757; --red-soft:#fdeaea;
      --card:#ffffff; --tint:#f5f8fc; --line:#e9edf4; --line-2:#dfe5ee;
      --ink:#152a3f; --ink-soft:#46586c; --muted:#8493a6; --muted-2:#aab6c4;
      --sh-sm:0 1px 2px rgba(11,39,64,.05);
      --sh:0 2px 6px rgba(11,39,64,.04), 0 12px 30px rgba(11,39,64,.07);
      --sh-lg:0 4px 10px rgba(11,39,64,.05), 0 22px 50px rgba(11,39,64,.12);
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
    a{ color:inherit; text-decoration:none; } button,input{ font-family:inherit; }
    h1,h2,h3,p{ margin:0; }
    .rf-page{ width:100%; min-height:100vh; display:flex; justify-content:center; }
    .rf-phone{ width:100%; max-width:428px; min-height:100vh; padding:18px 16px 112px; }

    .rf-head{ display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:16px; }
    .rf-head-l{ display:flex; align-items:center; gap:11px; min-width:0; }
    .rf-back{ width:42px; height:42px; flex:0 0 auto; border:1px solid var(--line); background:var(--card); color:var(--navy); border-radius:13px; display:grid; place-items:center; box-shadow:var(--sh-sm); }
    .rf-back svg{ width:20px; height:20px; }
    .rf-head-l .t .name{ display:block; font-size:17px; font-weight:700; letter-spacing:-.02em; color:var(--navy); }
    .rf-head-l .t .tag{ display:block; margin-top:3px; font-size:10px; font-weight:600; letter-spacing:.14em; text-transform:uppercase; color:var(--muted); }
    .rf-header-btn{ width:42px; height:42px; flex:0 0 auto; border:1px solid var(--line); background:var(--card); color:var(--ink-soft); border-radius:13px; display:grid; place-items:center; box-shadow:var(--sh-sm); }
    .rf-header-btn svg{ width:19px; height:19px; }

    /* HERO */
    .rf-invite-card{ position:relative; overflow:hidden; border-radius:26px; padding:20px; color:#fff;
      background:
        radial-gradient(420px 240px at 92% -20%, rgba(232,200,116,.24), transparent 62%),
        radial-gradient(360px 220px at 4% 120%, rgba(47,127,212,.22), transparent 60%),
        linear-gradient(150deg,#0f3255 0%,#0b2740 52%,#0a2036 100%);
      box-shadow:var(--sh-navy); }
    .rf-invite-card::before{ content:""; position:absolute; inset:0; border-radius:inherit; padding:1px; pointer-events:none;
      background:linear-gradient(150deg, rgba(232,200,116,.6), rgba(232,200,116,0) 34%, rgba(255,255,255,.14) 100%);
      -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; }
    .rf-invite-card > *{ position:relative; z-index:1; }
    .rf-invite-head{ display:flex; align-items:flex-start; justify-content:space-between; gap:12px; }
    .rf-kicker{ display:inline-flex; align-items:center; gap:8px; color:rgba(255,255,255,.62); font-size:9.5px; font-weight:600; letter-spacing:.18em; text-transform:uppercase; }
    .rf-kicker::before{ content:""; width:6px; height:6px; border-radius:999px; background:var(--gold-lite); box-shadow:0 0 10px rgba(232,200,116,.8); }
    .rf-main-title{ margin-top:12px; font-size:23px; font-weight:700; letter-spacing:-.035em; }
    .rf-main-sub{ margin-top:8px; font-size:11.5px; font-weight:500; color:rgba(255,255,255,.55); line-height:1.5; max-width:250px; }
    .rf-main-sub b{ color:var(--gold-lite); font-weight:700; }
    .rf-rate-pill{ display:inline-flex; align-items:center; gap:5px; padding:8px 12px; border-radius:14px; color:#0b2740; background:var(--gold-metal); font-size:15px; font-weight:800; box-shadow:0 8px 18px rgba(201,148,51,.34); }
    .rf-rate-pill svg{ width:15px; height:15px; }

    .rf-code-panel{ margin-top:16px; display:grid; gap:10px; }
    .rf-code-row{ display:flex; align-items:center; gap:10px; padding:11px 13px; border-radius:14px; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.14); }
    .rf-code-row .lbl{ font-size:9px; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:rgba(255,255,255,.5); flex:0 0 auto; }
    .rf-code-row input{ flex:1; min-width:0; border:0; outline:none; background:transparent; color:#fff; font-size:13px; font-weight:700; letter-spacing:.02em; }
    .rf-code-row input.link{ font-weight:500; color:rgba(255,255,255,.8); font-size:11.5px; }
    .rf-copy-mini{ width:34px; height:34px; flex:0 0 auto; border:0; border-radius:10px; cursor:pointer; display:grid; place-items:center; color:#0b2740; background:var(--gold-metal); }
    .rf-copy-mini svg{ width:16px; height:16px; }

    .rf-invite-actions{ margin-top:12px; display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    .rf-btn{ min-height:48px; border:0; border-radius:14px; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; font-size:13px; font-weight:700; transition:.15s ease; }
    .rf-btn svg{ width:17px; height:17px; }
    .rf-btn-primary{ color:#0b2740; background:var(--gold-metal); box-shadow:0 10px 22px rgba(201,148,51,.34); }
    .rf-btn-secondary{ color:#fff; background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.2); }

    /* SOCIAL */
    .rf-social{ margin-top:14px; border-radius:20px; padding:16px; background:var(--card); border:1px solid var(--line); box-shadow:var(--sh); }
    .rf-social h3{ font-size:13.5px; font-weight:700; color:var(--navy); letter-spacing:-.02em; }
    .rf-social p{ margin-top:3px; font-size:10.5px; font-weight:500; color:var(--muted); }
    .rf-social-row{ margin-top:14px; display:flex; gap:10px; justify-content:space-between; }
    .rf-soc{ flex:1; aspect-ratio:1; max-width:52px; border:0; border-radius:15px; cursor:pointer; display:grid; place-items:center; color:#fff; transition:.15s ease; }
    .rf-soc:hover{ transform:translateY(-2px); }
    .rf-soc svg{ width:22px; height:22px; }
    .rf-soc.wa{ background:linear-gradient(135deg,#25d366,#1aa251); }
    .rf-soc.tg{ background:linear-gradient(135deg,#37aee2,#1e96c8); }
    .rf-soc.fb{ background:linear-gradient(135deg,#4267b2,#2f4b83); }
    .rf-soc.x{ background:linear-gradient(135deg,#2b2b2b,#000); }
    .rf-soc.cp{ background:var(--gold-metal); color:#0b2740; }

    /* STATS */
    .rf-stats{ margin-top:14px; display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }
    .rf-stat{ position:relative; overflow:hidden; border-radius:18px; padding:14px 12px; background:var(--card); border:1px solid var(--line); box-shadow:var(--sh-sm); }
    .rf-stat::before{ content:""; position:absolute; left:0; top:0; bottom:0; width:3px; background:var(--gold-metal); }
    .rf-stat-label{ font-size:9.5px; font-weight:600; letter-spacing:.04em; text-transform:uppercase; color:var(--muted); }
    .rf-stat-value{ margin-top:8px; font-size:15px; font-weight:700; color:var(--navy); letter-spacing:-.02em; }
    .rf-stat-value span{ color:var(--blue); }

    /* LEVELS */
    .rf-sec-head{ margin:24px 0 14px; display:flex; align-items:center; gap:11px; }
    .rf-sec-head .bar{ width:4px; height:30px; border-radius:999px; background:var(--gold-metal); }
    .rf-sec-head h2{ color:var(--navy); font-size:18px; font-weight:700; letter-spacing:-.025em; }
    .rf-sec-head p{ margin-top:3px; color:var(--muted); font-size:11.5px; font-weight:500; }

    .rf-levels{ display:flex; flex-direction:column; gap:13px; }
    .rf-level{ position:relative; overflow:hidden; border-radius:20px; background:var(--card); border:1px solid var(--line); box-shadow:var(--sh); padding:16px; opacity:0; transform:translateY(12px); animation:rfUp .5s cubic-bezier(.22,.8,.22,1) forwards; }
    .rf-level::before{ content:""; position:absolute; top:0; left:0; right:0; height:3px; }
    .rf-level.l1::before{ background:var(--gold-metal); }
    .rf-level.l2::before{ background:var(--silver); }
    .rf-level.l3::before{ background:var(--bronze); }
    .rf-level-top{ display:flex; align-items:center; gap:13px; }
    .rf-medal{ width:46px; height:46px; flex:0 0 auto; border-radius:999px; display:grid; place-items:center; color:#3d2b06; font-size:18px; font-weight:800; box-shadow:0 8px 18px rgba(11,39,64,.14); }
    .rf-level.l1 .rf-medal{ background:var(--gold-metal); }
    .rf-level.l2 .rf-medal{ background:var(--silver); color:#3a4756; }
    .rf-level.l3 .rf-medal{ background:var(--bronze); color:#fff; }
    .rf-level-info{ flex:1; min-width:0; }
    .rf-level-info h3{ display:flex; align-items:center; gap:8px; font-size:15px; font-weight:700; color:var(--navy); letter-spacing:-.02em; }
    .rf-level-badge{ padding:3px 8px; border-radius:7px; font-size:9px; font-weight:700; letter-spacing:.04em; text-transform:uppercase; color:var(--blue); background:var(--blue-soft); }
    .rf-level-info p{ margin-top:4px; font-size:11px; font-weight:500; color:var(--muted); }
    .rf-level-pct{ text-align:right; flex:0 0 auto; }
    .rf-level-pct b{ display:block; font-size:22px; font-weight:800; letter-spacing:-.03em; background:var(--gold-metal); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }
    .rf-level-pct span{ display:block; font-size:8.5px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--muted); }

    .rf-level-grid{ margin-top:14px; display:grid; grid-template-columns:repeat(3,1fr); border-top:1px solid var(--line); background:var(--tint); border-radius:12px; overflow:hidden; }
    .rf-lg{ padding:12px 10px; text-align:center; }
    .rf-lg + .rf-lg{ border-left:1px solid var(--line); }
    .rf-lg span{ display:block; font-size:8.5px; font-weight:600; letter-spacing:.05em; text-transform:uppercase; color:var(--muted); }
    .rf-lg strong{ display:block; margin-top:5px; font-size:12px; font-weight:700; color:var(--navy); letter-spacing:-.01em; }
    .rf-lg strong.gold{ color:var(--gold-deep); }
    .rf-level-link{ margin-top:12px; display:flex; align-items:center; justify-content:center; gap:5px; height:40px; border-radius:11px; background:var(--tint); color:var(--blue); font-size:11.5px; font-weight:700; }
    .rf-level-link svg{ width:14px; height:14px; }

    /* SECTIONS (user list / commission) */
    .rf-section{ margin-top:24px; }
    .rf-section-head{ display:flex; align-items:flex-end; justify-content:space-between; gap:12px; margin-bottom:13px; }
    .rf-section-title h2{ color:var(--navy); font-size:16px; font-weight:700; letter-spacing:-.02em; }
    .rf-section-title p{ margin-top:3px; color:var(--muted); font-size:11px; font-weight:500; }
    .rf-section-hint{ font-size:10.5px; font-weight:700; color:var(--blue); background:var(--blue-soft); padding:6px 11px; border-radius:999px; white-space:nowrap; }
    .rf-card-list{ display:flex; flex-direction:column; gap:10px; }
    .rf-user-card,.rf-commission-card{ border-radius:16px; background:var(--card); border:1px solid var(--line); box-shadow:var(--sh-sm); padding:13px 14px; }
    .rf-row-head{ display:flex; align-items:center; justify-content:space-between; gap:10px; }
    .rf-user-left{ display:flex; align-items:center; gap:11px; min-width:0; }
    .rf-avatar{ width:42px; height:42px; flex:0 0 auto; border-radius:13px; display:grid; place-items:center; color:#fff; font-size:15px; font-weight:700; background:linear-gradient(135deg,var(--navy),var(--blue)); }
    .rf-user-meta{ min-width:0; }
    .rf-user-name{ font-size:13.5px; font-weight:700; color:var(--navy); }
    .rf-user-sub{ display:block; margin-top:3px; font-size:10.5px; font-weight:500; color:var(--muted); }
    .rf-small-badge{ font-size:10px; font-weight:700; color:var(--ink-soft); background:var(--tint); padding:5px 9px; border-radius:8px; white-space:nowrap; }
    .rf-commission-grid{ margin-top:12px; display:grid; grid-template-columns:repeat(3,1fr); border-top:1px solid var(--line); padding-top:11px; }
    .rf-mini-info{ text-align:center; }
    .rf-mini-info + .rf-mini-info{ border-left:1px solid var(--line); }
    .rf-mini-info span{ font-size:9px; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:var(--muted); }
    .rf-mini-info strong{ display:block; margin-top:5px; font-size:11.5px; font-weight:700; color:var(--navy); }
    .rf-mini-info strong.is-accent{ color:var(--gold-deep); }
    .rf-empty{ padding:22px; border-radius:16px; background:var(--card); border:1px dashed var(--line-2); text-align:center; color:var(--muted); font-size:12.5px; font-weight:500; }
    .rf-pager{ margin-top:12px; display:flex; justify-content:center; font-size:12px; }

    .rb-bottom-spacer{ height:94px; }

    .rf-toast{ position:fixed; left:50%; bottom:110px; transform:translateX(-50%) translateY(20px); z-index:2000; opacity:0; pointer-events:none;
      display:flex; align-items:center; gap:8px; padding:12px 16px; border-radius:14px; background:var(--navy); color:#fff; box-shadow:var(--sh-lg); font-size:12.5px; font-weight:600; transition:.28s cubic-bezier(.22,.8,.22,1); }
    .rf-toast.show{ opacity:1; transform:translateX(-50%) translateY(0); }
    .rf-toast svg{ width:16px; height:16px; color:var(--gold-lite); }

    @keyframes rfUp{ to{ opacity:1; transform:translateY(0); } }
    @media (prefers-reduced-motion:reduce){ *,*::before,*::after{ animation:none !important; transition:none !important; } .rf-level{ opacity:1; transform:none; } }
  </style>
</head>

<body>
  <main class="rf-page">
    <div class="rf-phone">

      <header class="rf-head">
        <div class="rf-head-l">
          <a href="{{ url('/akun') }}" class="rf-back" aria-label="Kembali"><svg viewBox="0 0 24 24" fill="none"><path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
          <div class="t"><span class="name">Program Referral</span><span class="tag">Ajak & raih komisi</span></div>
        </div>
        <a href="{{ url('/akun') }}" class="rf-header-btn" aria-label="Akun"><svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="3.6" stroke="currentColor" stroke-width="1.7"/><path d="M5 20a7 7 0 0 1 14 0" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg></a>
      </header>

      {{-- HERO --}}
      <section class="rf-invite-card">
        <div class="rf-invite-head">
          <div>
            <span class="rf-kicker">Program Referral</span>
            <h2 class="rf-main-title">Ajak Teman, Raih Komisi</h2>
            <p class="rf-main-sub">Undang lewat link, raih komisi otomatis dari <b>3 tingkat</b> jaringanmu.</p>
          </div>
          <div class="rf-rate-pill">
            <svg viewBox="0 0 24 24" fill="none"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/></svg>
            32%
          </div>
        </div>

        <div class="rf-code-panel">
          <div class="rf-code-row">
            <span class="lbl">Kode</span>
            <input id="referralCodeField" value="{{ $referralCode }}" class="code" readonly aria-label="Kode Referral">
            <button type="button" class="rf-copy-mini" onclick="copyById('referralCodeField','Kode referral dicopy!')" aria-label="Copy kode">
              <svg viewBox="0 0 24 24" fill="none"><rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>
            </button>
          </div>
          <div class="rf-code-row">
            <span class="lbl">Link</span>
            <input id="referralLinkField" value="{{ $referralLink }}" class="link" readonly aria-label="Tautan Referral">
            <button type="button" class="rf-copy-mini" onclick="copyById('referralLinkField','Tautan referral dicopy!')" aria-label="Copy link">
              <svg viewBox="0 0 24 24" fill="none"><rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>
            </button>
          </div>
        </div>

        <div class="rf-invite-actions">
          <button type="button" class="rf-btn rf-btn-primary" onclick="shareReferral()">
            <svg viewBox="0 0 24 24" fill="none"><path d="M4 12v7a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/><path d="M12 16V3" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/><path d="m7 8 5-5 5 5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Bagikan Link
          </button>
          <button type="button" class="rf-btn rf-btn-secondary" onclick="copyById('referralLinkField','Tautan referral berhasil dicopy!')">
            <svg viewBox="0 0 24 24" fill="none"><rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>
            Copy Link
          </button>
        </div>
      </section>

      {{-- QR CODE --}}
      @if($referralQr)
      <section style="margin-top:14px; background:var(--card,#fff); border:1px solid var(--line,#e9edf4); border-radius:20px; box-shadow:0 6px 16px rgba(11,39,64,.06); padding:18px; text-align:center;">
        <h3 style="margin:0; color:var(--navy,#0b2740); font-size:15px; font-weight:800; letter-spacing:-.02em;">Scan QR Referral</h3>
        <p style="margin:6px 0 14px; color:var(--ink-soft,#46586c); font-size:11.5px; font-weight:500; line-height:1.5;">Suruh calon anggota scan QR ini — langsung ke halaman daftar pakai kodemu. Cocok disebar di story/status.</p>
        <div style="display:inline-block; padding:14px 14px 12px; background:#fff; border:1px solid var(--line,#e9edf4); border-radius:16px; position:relative; overflow:hidden;">
          <span style="position:absolute; top:0; left:0; right:0; height:3px; background:linear-gradient(135deg,#a9772a,#e8c874,#c99433);"></span>
          <img src="{{ $referralQr }}" alt="QR Referral {{ $referralCode }}" id="referralQrImg" style="width:200px; height:200px; display:block;">
        </div>
        <div style="margin-top:12px; font-size:13px; font-weight:800; letter-spacing:.08em; color:var(--navy,#0b2740);">{{ $referralCode }}</div>
        <button type="button" onclick="downloadQr()" style="margin-top:12px; min-height:44px; padding:0 22px; border:1px solid var(--line,#e9edf4); border-radius:12px; background:var(--card,#fff); color:var(--navy,#0b2740); font-size:12.5px; font-weight:700; cursor:pointer;">⬇ Download QR</button>
      </section>
      <script>
        function downloadQr(){
          var img = document.getElementById('referralQrImg');
          if(!img || !img.src) return;
          var a = document.createElement('a');
          a.href = img.src;
          a.download = 'QR-Referral-{{ $referralCode }}.svg';
          document.body.appendChild(a); a.click(); document.body.removeChild(a);
        }
      </script>
      @endif

      {{-- SOCIAL --}}
      <section class="rf-social">
        <h3>Bagikan ke Sosial Media</h3>
        <p>Sekali klik, link & QR-mu langsung tersebar.</p>
        <div class="rf-social-row">
          <button type="button" class="rf-soc wa" onclick="shareTo('wa')" aria-label="WhatsApp"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15l-1.3 4.7 4.8-1.3A10 10 0 1 0 12 2Zm5.3 14.1c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .2-3.3-.7-2.8-1.1-4.5-4-4.7-4.2-.1-.2-1-1.4-1-2.6 0-1.3.6-1.9.9-2.1.2-.3.5-.3.7-.3h.5c.2 0 .4 0 .6.5l.8 1.9c.1.2.1.4 0 .5l-.4.6c-.1.2-.3.3-.1.6.1.3.7 1.1 1.4 1.8.9.8 1.7 1 2 1.2.2.1.4.1.5-.1l.7-.8c.2-.2.3-.2.6-.1l1.8.9c.3.1.4.2.5.3 0 .2 0 .8-.1 1.5Z"/></svg></button>
          <button type="button" class="rf-soc tg" onclick="shareTo('tg')" aria-label="Telegram"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M21.9 4.3 2.9 11.6c-1 .4-1 1.8.1 2.1l4.8 1.5 1.8 5.7c.2.6 1 .8 1.4.3l2.6-2.6 4.7 3.5c.6.4 1.5.1 1.6-.7l3-14.6c.2-.9-.7-1.6-1.6-1.3Z"/></svg></button>
          <button type="button" class="rf-soc fb" onclick="shareTo('fb')" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M14 9h3l.5-3H14V4.5c0-.8.3-1.5 1.5-1.5H17V.2C16.7.1 15.7 0 14.6 0 12.2 0 10.5 1.5 10.5 4.2V6H8v3h2.5v9H14V9Z"/></svg></button>
          <button type="button" class="rf-soc x" onclick="shareTo('x')" aria-label="X"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 3h3l-6.6 7.5L21.7 21h-6.1l-4.3-5.6L6.3 21H3.3l7.1-8L2.6 3h6.2l3.9 5.2L17.5 3Zm-1.1 16h1.7L7.7 4.8H5.9L16.4 19Z"/></svg></button>
          <button type="button" class="rf-soc cp" onclick="copyById('referralLinkField','Tautan referral dicopy!')" aria-label="Copy"><svg viewBox="0 0 24 24" fill="none"><rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2.2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg></button>
        </div>
      </section>

      {{-- STATS --}}
      <section class="rf-stats">
        <div class="rf-stat"><p class="rf-stat-label">Total Anggota</p><div class="rf-stat-value"><span>{{ $totalReferral }}</span></div></div>
        <div class="rf-stat"><p class="rf-stat-label">Total Komisi</p><div class="rf-stat-value">Rp {{ number_format($totalCommission, 0, ',', '.') }}</div></div>
        <div class="rf-stat"><p class="rf-stat-label">Saldo Komisi</p><div class="rf-stat-value">Rp {{ number_format($saldoKomisi, 0, ',', '.') }}</div></div>
      </section>

      {{-- LEVELS --}}
      <div class="rf-sec-head">
        <span class="bar"></span>
        <div><h2>Tingkat Referral</h2><p>Komisi otomatis dari 3 tingkat jaringan</p></div>
      </div>

      <div class="rf-levels">
        @foreach($levels as $i => $lv)
          <article class="rf-level l{{ $lv['n'] }}" style="animation-delay: {{ $i * 0.08 }}s;">
            <div class="rf-level-top">
              <div class="rf-medal">{{ $lv['n'] }}</div>
              <div class="rf-level-info">
                <h3>Level {{ $lv['n'] }} <span class="rf-level-badge">{{ $lv['tag'] }}</span></h3>
                <p>{{ $lv['label'] }}</p>
              </div>
              <div class="rf-level-pct">
                <b>{{ $lv['pct'] }}%</b>
                <span>Komisi</span>
              </div>
            </div>
            <div class="rf-level-grid">
              <div class="rf-lg"><span>Anggota</span><strong>{{ $lv['anggota'] }}</strong></div>
              <div class="rf-lg"><span>Komisi</span><strong class="gold">Rp {{ number_format($lv['komisi'], 0, ',', '.') }}</strong></div>
              <div class="rf-lg"><span>Investasi</span><strong>Rp {{ number_format($lv['investasi'], 0, ',', '.') }}</strong></div>
            </div>
            <a href="#userReferral" class="rf-level-link">
              Lihat daftar referral
              <svg viewBox="0 0 24 24" fill="none"><path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
          </article>
        @endforeach
      </div>

      {{-- USER REFERRAL --}}
      <section class="rf-section" id="userReferral">
        <div class="rf-section-head">
          <div class="rf-section-title"><h2>Daftar Anggota</h2><p>User yang memakai kode kamu</p></div>
          <div class="rf-section-hint">Total {{ $totalReferral }}</div>
        </div>
        <div class="rf-card-list">
          @forelse ($refUsers as $ru)
            <article class="rf-user-card">
              <div class="rf-row-head">
                <div class="rf-user-left">
                  <div class="rf-avatar">{{ strtoupper(substr($ru->name ?? 'U', 0, 1)) }}</div>
                  <div class="rf-user-meta">
                    <strong class="rf-user-name">{{ $ru->name }}</strong>
                    <span class="rf-user-sub">{{ $ru->phone ?? '-' }}</span>
                  </div>
                </div>
                <span class="rf-small-badge">{{ optional($ru->created_at)->format('d M') ?? '-' }}</span>
              </div>
            </article>
          @empty
            <div class="rf-empty">Belum ada user yang daftar memakai kode kamu.</div>
          @endforelse
          @if(is_object($refUsers) && method_exists($refUsers, 'links'))
            <div class="rf-pager">{{ $refUsers->links() }}</div>
          @endif
        </div>
      </section>

      {{-- RIWAYAT KOMISI --}}
      <section class="rf-section">
        <div class="rf-section-head">
          <div class="rf-section-title"><h2>Riwayat Komisi</h2><p>Komisi terbaru dari jaringanmu</p></div>
          <div class="rf-section-hint">Terbaru</div>
        </div>
        <div class="rf-card-list">
          @forelse ($commissions as $c)
            @php
              $lvlNum = (int) ($c->level ?? 1);
              $lvlLabel = $lvlNum === 1 ? 'Referral langsung' : ($lvlNum === 2 ? 'Jaringan kedua' : 'Jaringan ketiga');
              $lvlHex = $lvlNum === 1 ? '#a9772a' : ($lvlNum === 2 ? '#5b6b7d' : '#9c6b3b');
            @endphp
            <article class="rf-commission-card">
              <div class="rf-row-head">
                <div class="rf-user-left">
                  <div class="rf-avatar" style="background:var(--gold-metal);color:#3d2b06;">%</div>
                  <div class="rf-user-meta">
                    <strong class="rf-user-name">{{ $c->source_type === 'deposit' ? 'Deposit' : 'Beli Produk' }}</strong>
                    <span class="rf-user-sub">{{ optional($c->created_at)->format('d-m-Y H:i') ?? '-' }} · {{ $lvlLabel }}</span>
                  </div>
                </div>
                <span class="rf-small-badge" style="background:{{ $lvlHex }};color:#fff;">Level {{ $lvlNum }}</span>
              </div>
              <div class="rf-commission-grid">
                <div class="rf-mini-info"><span>Dasar</span><strong>Rp {{ number_format($c->base_amount, 0, ',', '.') }}</strong></div>
                <div class="rf-mini-info"><span>Rate (L{{ $lvlNum }})</span><strong>{{ rtrim(rtrim(number_format((float) $c->rate * 100, 2, '.', ''), '0'), '.') }}%</strong></div>
                <div class="rf-mini-info"><span>Komisi</span><strong class="is-accent">Rp {{ number_format($c->commission_amount, 0, ',', '.') }}</strong></div>
              </div>
              <div style="margin-top:10px; padding:9px 11px; border-radius:10px; background:var(--tint); border:1px solid var(--line); font-size:10.5px; font-weight:500; color:var(--ink-soft); line-height:1.5;">
                @if($lvlNum === 1)
                  Kamu <b>referral langsung</b> pembeli → dapat <b>32%</b> dari harga produk.
                @elseif($lvlNum === 2)
                  Pembeli ada di <b>jaringan tingkat 2</b>-mu (downline dari downline) → dapat <b>2%</b>.
                @else
                  Pembeli ada di <b>jaringan tingkat 3</b>-mu → dapat <b>1%</b>.
                @endif
              </div>
            </article>
          @empty
            <div class="rf-empty">Belum ada komisi masuk.</div>
          @endforelse
          @if(is_object($commissions) && method_exists($commissions, 'links'))
            <div class="rf-pager">{{ $commissions->links() }}</div>
          @endif
        </div>
      </section>

      <div class="rb-bottom-spacer"></div>
      @include('partials.bottom-nav')
    </div>
  </main>

  <div id="rfToast" class="rf-toast" role="status" aria-live="polite">
    <svg viewBox="0 0 24 24" fill="none"><path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
    <span>Tautan referral berhasil dicopy!</span>
  </div>

  <script>
    function showToast(message) {
      const toast = document.getElementById('rfToast');
      if (!toast) return;
      const text = toast.querySelector('span');
      if (text) text.textContent = message;
      toast.classList.add('show');
      clearTimeout(window.__rfToastTimer);
      window.__rfToastTimer = setTimeout(function () { toast.classList.remove('show'); }, 1800);
    }

    async function copyText(value, message = 'Berhasil dicopy!') {
      try {
        if (navigator.clipboard && window.isSecureContext) {
          await navigator.clipboard.writeText(value);
        } else {
          const temp = document.createElement('input');
          temp.value = value;
          document.body.appendChild(temp);
          temp.select();
          temp.setSelectionRange(0, 99999);
          document.execCommand('copy');
          document.body.removeChild(temp);
        }
        showToast(message);
      } catch (error) {
        showToast('Gagal copy, coba manual.');
      }
    }

    function copyById(id, message) {
      const el = document.getElementById(id);
      if (!el) return;
      copyText(el.value || '', message);
    }

    async function shareReferral() {
      const link = document.getElementById('referralLinkField')?.value || '';
      const code = document.getElementById('referralCodeField')?.value || '';
      if (!link) { showToast('Tautan referral belum tersedia.'); return; }
      const shareData = { title: 'Referral Capital Wave', text: `Gabung pakai kode referral saya: ${code}`, url: link };
      try {
        if (navigator.share) { await navigator.share(shareData); }
        else { await copyText(link, 'Tautan referral berhasil dicopy!'); }
      } catch (error) {
        if (error && error.name !== 'AbortError') { showToast('Gagal membagikan tautan.'); }
      }
    }

    function shareTo(platform){
      const link = document.getElementById('referralLinkField')?.value || '';
      const code = document.getElementById('referralCodeField')?.value || '';
      if(!link){ showToast('Tautan referral belum tersedia.'); return; }
      const text = `Gabung pakai kode referral saya: ${code}`;
      const u = encodeURIComponent(link), t = encodeURIComponent(text + ' ' + link);
      let url = '';
      if(platform === 'wa') url = 'https://wa.me/?text=' + t;
      else if(platform === 'tg') url = 'https://t.me/share/url?url=' + u + '&text=' + encodeURIComponent(text);
      else if(platform === 'fb') url = 'https://www.facebook.com/sharer/sharer.php?u=' + u;
      else if(platform === 'x') url = 'https://twitter.com/intent/tweet?text=' + encodeURIComponent(text) + '&url=' + u;
      if(url) window.open(url, '_blank', 'noopener,noreferrer');
    }
  </script>
</body>
</html>
