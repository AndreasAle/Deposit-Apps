{{-- Velora Premium Light — Referral (NO @extends) --}}
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

  $qrImage = 'https://quickchart.io/qr'
      . '?text=' . urlencode($referralLink)
      . '&size=220'
      . '&margin=1'
      . '&dark=3A0712'
      . '&light=FFF7FD';
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Referral | Velora Finance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --rf-bg:#f7f2fb;
      --rf-bg2:#fff8fc;
      --rf-paper:#ffffff;
      --rf-paper2:#fffafd;
      --rf-text:#2b0b16;
      --rf-maroon:#3a0712;
      --rf-soft:#6f5363;
      --rf-muted:#9c8190;
      --rf-border:rgba(43,11,22,.09);
      --rf-border2:rgba(43,11,22,.14);
      --rf-purple:#8f57ff;
      --rf-violet:#d96bff;
      --rf-gold:#f5af2a;
      --rf-gold2:#ffd46d;
      --rf-rose:#ff6fa8;
      --rf-green:#20b873;
      --rf-gradient:linear-gradient(135deg,#f5af2a 0%,#ffd46d 25%,#d96bff 60%,#8f57ff 100%);
      --rf-gradient-soft:linear-gradient(135deg,#fff6d8 0%,#fff 32%,#fce6ff 64%,#f1e9ff 100%);
      --rf-shadow:0 24px 60px rgba(88,43,145,.14);
      --rf-shadow-soft:0 14px 34px rgba(43,11,22,.075);
    }

    *{box-sizing:border-box;}
    html,body{min-height:100%;}
    body{
      margin:0;
      font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
      color:var(--rf-text);
      background:
        radial-gradient(680px 360px at 50% -150px,rgba(245,175,42,.23),transparent 64%),
        radial-gradient(520px 340px at 100% 4%,rgba(217,107,255,.18),transparent 62%),
        radial-gradient(520px 330px at -12% 34%,rgba(143,87,255,.13),transparent 58%),
        linear-gradient(180deg,#fff 0%,#f8f3fa 45%,#f0e9f7 100%);
      overflow-x:hidden;
      -webkit-tap-highlight-color:transparent;
    }
    body::before{
      content:"";
      position:fixed;
      inset:0;
      pointer-events:none;
      background:
        linear-gradient(rgba(43,11,22,.024) 1px,transparent 1px),
        linear-gradient(90deg,rgba(43,11,22,.016) 1px,transparent 1px);
      background-size:32px 32px;
      opacity:.46;
      mask-image:linear-gradient(180deg,rgba(0,0,0,.44),transparent 76%);
      -webkit-mask-image:linear-gradient(180deg,rgba(0,0,0,.44),transparent 76%);
      z-index:0;
    }
    body::after{
      content:"";
      position:fixed;
      inset:0;
      pointer-events:none;
      background:
        radial-gradient(circle at 9% 18%,rgba(245,175,42,.10),transparent 28%),
        radial-gradient(circle at 92% 26%,rgba(217,107,255,.11),transparent 30%),
        radial-gradient(circle at 50% 100%,rgba(143,87,255,.075),transparent 34%);
      z-index:0;
    }
    a{color:inherit;text-decoration:none;}
    button,input{font-family:inherit;}

    .rf-page{width:100%;min-height:100vh;display:flex;justify-content:center;padding:14px 10px 0;position:relative;z-index:1;}
    .rf-phone{width:100%;max-width:430px;min-height:100vh;position:relative;padding:8px 4px 104px;}

    .rf-header{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:15px;padding:0 2px;}
    .rf-brand{display:flex;align-items:center;gap:11px;min-width:0;}
    .rf-logo-card{
      width:50px;height:50px;border-radius:18px;display:grid;place-items:center;flex:0 0 auto;overflow:hidden;
      background:radial-gradient(circle at 28% 8%,rgba(255,255,255,.98),rgba(255,226,155,.76) 35%,rgba(225,188,255,.76) 92%),var(--rf-gradient);
      border:1px solid rgba(255,255,255,.72);
      box-shadow:0 16px 34px rgba(88,43,145,.13),0 8px 22px rgba(245,175,42,.10),inset 0 1px 0 rgba(255,255,255,.75);
    }
    .rf-logo-card img{width:44px;height:44px;object-fit:contain;display:block;}
    .rf-title{min-width:0;}
    .rf-title span{display:block;margin-bottom:5px;color:rgba(58,7,18,.58);font-size:10px;line-height:1;font-weight:900;letter-spacing:.18em;text-transform:uppercase;}
    .rf-title h1{margin:0;color:var(--rf-maroon);font-size:23px;line-height:1;font-weight:950;letter-spacing:-.055em;white-space:nowrap;}
    .rf-header-actions{display:flex;align-items:center;gap:9px;flex:0 0 auto;}
    .rf-header-btn{
      width:42px;height:42px;border-radius:999px;border:1px solid var(--rf-border);background:rgba(255,255,255,.88);color:#5b2841;display:grid;place-items:center;
      box-shadow:0 12px 26px rgba(43,11,22,.065),inset 0 1px 0 rgba(255,255,255,.92);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);transition:.18s ease;
    }
    .rf-header-btn:hover{transform:translateY(-1px);color:var(--rf-purple);}
    .rf-header-btn svg{width:20px;height:20px;}

    .rf-invite-card{
      position:relative;overflow:hidden;border-radius:34px;padding:17px;
      background:
        radial-gradient(360px 220px at 92% -12%,rgba(255,212,109,.48),transparent 58%),
        radial-gradient(300px 200px at 2% 8%,rgba(217,107,255,.34),transparent 62%),
        linear-gradient(145deg,#8f57ff 0%,#9455ff 40%,#d96bff 72%,#f5af2a 100%);
      color:#fff;border:1px solid rgba(255,255,255,.44);
      box-shadow:0 28px 62px rgba(143,87,255,.22),0 18px 42px rgba(245,175,42,.10),inset 0 1px 0 rgba(255,255,255,.22);
      animation:rfFadeUp .42s ease both;
    }
    .rf-invite-card::before{content:"";position:absolute;inset:0;pointer-events:none;background:linear-gradient(135deg,rgba(255,255,255,.22),transparent 34%),radial-gradient(circle at 82% 26%,rgba(255,255,255,.16),transparent 28%),linear-gradient(180deg,transparent,rgba(43,11,22,.08));}
    .rf-invite-card::after{content:"";position:absolute;right:-68px;bottom:-86px;width:240px;height:240px;border-radius:50%;background:linear-gradient(135deg,rgba(255,212,109,.46),rgba(217,107,255,.25));filter:blur(18px);pointer-events:none;}
    .rf-invite-card>*{position:relative;z-index:1;}
    .rf-invite-head{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:12px;align-items:start;}
    .rf-kicker{display:inline-flex;align-items:center;min-height:26px;padding:0 10px;border-radius:999px;color:#2c1200;background:linear-gradient(135deg,#ffe08a,#f5af2a);font-size:10px;font-weight:950;letter-spacing:.08em;text-transform:uppercase;box-shadow:0 12px 22px rgba(245,175,42,.20);}
    .rf-main-title{margin:12px 0 0;color:#fff;font-size:23px;line-height:1.06;letter-spacing:-.06em;font-weight:950;text-shadow:0 12px 28px rgba(43,11,22,.24);}
    .rf-main-sub{margin:8px 0 0;color:rgba(255,255,255,.76);font-size:12px;line-height:1.45;font-weight:650;max-width:280px;}
    .rf-rate-pill{min-height:40px;display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:0 12px;border-radius:999px;color:#2c1200;background:linear-gradient(135deg,#ffd46d,#d96bff 70%,#8f57ff);border:1px solid rgba(255,255,255,.34);box-shadow:0 12px 24px rgba(143,87,255,.18),inset 0 1px 0 rgba(255,255,255,.34);font-size:12px;font-weight:950;white-space:nowrap;}
    .rf-rate-pill svg{width:15px;height:15px;}

    .rf-invite-main{margin-top:14px;display:grid;grid-template-columns:118px minmax(0,1fr);gap:10px;align-items:stretch;}
    .rf-qr-stage{border-radius:24px;padding:9px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.16);box-shadow:inset 0 1px 0 rgba(255,255,255,.12);display:flex;align-items:center;justify-content:center;}
    .rf-qr-inner{width:100px;height:100px;border-radius:19px;padding:7px;background:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 12px 24px rgba(43,11,22,.18),0 0 0 1px rgba(43,11,22,.05) inset;}
    .rf-qr-inner img{width:100%;height:100%;object-fit:contain;display:block;}
    .rf-code-panel{min-width:0;border-radius:24px;padding:11px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.15);box-shadow:inset 0 1px 0 rgba(255,255,255,.10);display:grid;gap:9px;}
    .rf-code-label{margin:0 0 6px;color:rgba(255,255,255,.68);font-size:9.5px;line-height:1;font-weight:850;text-transform:uppercase;letter-spacing:.08em;}
    .rf-code-value,.rf-link-value{width:100%;height:39px;border:1px solid rgba(255,255,255,.12);outline:none;border-radius:14px;padding:0 11px;color:#fff;background:rgba(43,11,22,.18);box-shadow:inset 0 1px 0 rgba(255,255,255,.05);}
    .rf-code-value{font-size:13.5px;font-weight:950;letter-spacing:.08em;}
    .rf-link-value{font-size:10.5px;font-weight:750;}
    .rf-invite-actions{margin-top:12px;display:grid;grid-template-columns:1fr 1fr;gap:9px;}
    .rf-btn{min-height:44px;border:0;border-radius:999px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;font-size:12.3px;font-weight:950;transition:.18s ease;}
    .rf-btn:hover{transform:translateY(-1px);filter:brightness(1.04);}
    .rf-btn svg{width:16px;height:16px;}
    .rf-btn-primary{color:#2c1200;background:linear-gradient(135deg,#ffe08a,#f5af2a);box-shadow:0 14px 28px rgba(245,175,42,.24),inset 0 1px 0 rgba(255,255,255,.35);}
    .rf-btn-secondary{color:#fff;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.16);}

    .rf-stats{margin-top:13px;display:grid;grid-template-columns:repeat(3,1fr);gap:9px;}
    .rf-stat{position:relative;overflow:hidden;min-height:78px;border-radius:21px;padding:11px 10px;background:linear-gradient(180deg,rgba(255,255,255,.97),rgba(255,255,255,.88));border:1px solid var(--rf-border);box-shadow:var(--rf-shadow-soft),inset 0 1px 0 rgba(255,255,255,.92);animation:rfFadeUp .42s ease both;}
    .rf-stat::before{content:"";position:absolute;top:10px;right:10px;width:9px;height:9px;border-radius:999px;background:linear-gradient(135deg,var(--stat-a),var(--stat-b));box-shadow:0 0 0 6px var(--stat-glow);}
    .rf-stat:nth-child(1){--stat-a:#f5af2a;--stat-b:#ffd46d;--stat-glow:rgba(245,175,42,.14);}
    .rf-stat:nth-child(2){--stat-a:#d96bff;--stat-b:#8f57ff;--stat-glow:rgba(143,87,255,.12);}
    .rf-stat:nth-child(3){--stat-a:#8f57ff;--stat-b:#f5af2a;--stat-glow:rgba(180,92,255,.10);}
    .rf-stat-label{margin:0;padding-right:14px;color:var(--rf-soft);font-size:9.6px;line-height:1.22;font-weight:750;}
    .rf-stat-value{margin:12px 0 0;color:var(--rf-maroon);font-size:12.2px;line-height:1.14;letter-spacing:-.025em;font-weight:950;}
    .rf-stat-value span{color:var(--rf-purple);}

    .rf-section{margin-top:20px;}
    .rf-section-head{display:flex;align-items:flex-end;justify-content:space-between;gap:12px;margin-bottom:12px;padding:0 2px;}
    .rf-section-title{min-width:0;}
    .rf-section-title h2{margin:0;color:var(--rf-maroon);font-size:17px;line-height:1.15;letter-spacing:-.035em;font-weight:950;}
    .rf-section-title p{margin:5px 0 0;color:var(--rf-soft);font-size:11px;font-weight:650;}
    .rf-section-hint{display:inline-flex;align-items:center;min-height:30px;padding:0 11px;border-radius:999px;color:#2c1200;background:linear-gradient(135deg,#ffe08a,#f5af2a);font-size:10.5px;font-weight:950;white-space:nowrap;box-shadow:0 12px 24px rgba(245,175,42,.16);}
    .rf-card-list{display:flex;flex-direction:column;gap:10px;}
    .rf-user-card,.rf-commission-card{position:relative;overflow:hidden;border-radius:24px;background:radial-gradient(210px 120px at 88% 8%,var(--card-glow),transparent 64%),linear-gradient(180deg,rgba(255,255,255,.98),rgba(255,255,255,.90));border:1px solid var(--rf-border);box-shadow:var(--rf-shadow-soft),inset 0 1px 0 rgba(255,255,255,.92);padding:12px;transition:.18s ease;animation:rfFadeUp .42s ease both;}
    .rf-user-card:hover,.rf-commission-card:hover{transform:translateY(-1px);border-color:rgba(143,87,255,.17);box-shadow:0 18px 36px rgba(43,11,22,.10),0 0 0 4px rgba(143,87,255,.045);}
    .rf-user-card:nth-child(4n+1),.rf-commission-card:nth-child(4n+1){--card-accent:#d96bff;--card-accent2:#8f57ff;--card-glow:rgba(217,107,255,.11);}
    .rf-user-card:nth-child(4n+2),.rf-commission-card:nth-child(4n+2){--card-accent:#f5af2a;--card-accent2:#ffd46d;--card-glow:rgba(245,175,42,.13);}
    .rf-user-card:nth-child(4n+3),.rf-commission-card:nth-child(4n+3){--card-accent:#ffd46d;--card-accent2:#d96bff;--card-glow:rgba(143,87,255,.10);}
    .rf-user-card:nth-child(4n+4),.rf-commission-card:nth-child(4n+4){--card-accent:#8f57ff;--card-accent2:#ff6fa8;--card-glow:rgba(143,87,255,.11);}
    .rf-row-head{display:flex;align-items:center;justify-content:space-between;gap:12px;}
    .rf-user-left{display:flex;align-items:center;gap:11px;min-width:0;}
    .rf-avatar{width:43px;height:43px;border-radius:16px;display:grid;place-items:center;color:#fff;background:linear-gradient(135deg,var(--card-accent),var(--card-accent2));box-shadow:0 12px 24px rgba(143,87,255,.14),inset 0 1px 0 rgba(255,255,255,.22);flex:0 0 auto;font-size:14px;font-weight:950;}
    .rf-user-meta{min-width:0;}
    .rf-user-name{display:block;color:var(--rf-maroon);font-size:13.4px;line-height:1.15;font-weight:950;letter-spacing:-.015em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:210px;}
    .rf-user-sub{display:block;margin-top:5px;color:var(--rf-soft);font-size:10.8px;font-weight:650;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:210px;}
    .rf-small-badge{min-height:28px;display:inline-flex;align-items:center;justify-content:center;padding:0 10px;border-radius:999px;color:#2c1200;background:linear-gradient(135deg,#ffe08a,#f5af2a);font-size:10px;font-weight:950;white-space:nowrap;}
    .rf-commission-grid{margin-top:10px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;}
    .rf-mini-info{min-height:55px;border-radius:16px;padding:9px;background:#fff8fc;border:1px solid rgba(43,11,22,.07);box-shadow:inset 0 1px 0 rgba(255,255,255,.86);}
    .rf-mini-info span{display:block;color:var(--rf-soft);font-size:9.4px;line-height:1.1;font-weight:750;margin-bottom:6px;}
    .rf-mini-info strong{display:block;color:var(--rf-maroon);font-size:11px;line-height:1.15;font-weight:950;word-break:break-word;}
    .rf-mini-info strong.is-accent{color:var(--rf-purple);}
    .rf-empty{padding:17px 14px;border-radius:22px;background:#fff;border:1px dashed rgba(43,11,22,.18);color:var(--rf-soft);text-align:center;font-size:12.5px;font-weight:750;line-height:1.5;box-shadow:var(--rf-shadow-soft);}
    .rf-pager{margin-top:12px;border-radius:18px;border:1px solid var(--rf-border);background:rgba(255,255,255,.92);padding:10px 12px;overflow:auto;color:var(--rf-maroon);box-shadow:var(--rf-shadow-soft);}
    .rf-pager *{color:var(--rf-soft);font-size:12px;}
    .rf-pager a{color:var(--rf-purple)!important;font-weight:950;text-decoration:none;}
    .rf-toast{position:fixed;left:50%;bottom:92px;z-index:9999;transform:translateX(-50%) translateY(12px);opacity:0;pointer-events:none;min-height:44px;padding:0 15px;border-radius:999px;display:flex;align-items:center;justify-content:center;gap:8px;color:#2c1200;background:linear-gradient(135deg,#ffe08a,#f5af2a);box-shadow:0 18px 42px rgba(43,11,22,.16),0 0 28px rgba(245,175,42,.18);font-size:12px;font-weight:950;transition:.22s ease;white-space:nowrap;}
    .rf-toast.show{opacity:1;transform:translateX(-50%) translateY(0);}
    .rf-toast svg{width:16px;height:16px;}

    .rb-bottom-spacer{height:94px!important;}
    .rb-bottom-nav{background:rgba(255,255,255,.92)!important;border:1px solid rgba(43,11,22,.08)!important;box-shadow:0 -18px 40px rgba(43,11,22,.10),inset 0 1px 0 rgba(255,255,255,.84)!important;backdrop-filter:blur(22px)!important;-webkit-backdrop-filter:blur(22px)!important;}
    .rb-bottom-nav__item{color:#aa8f9f!important;}
    .rb-bottom-nav__item:hover{color:#5b2841!important;}
    .rb-bottom-nav__item.is-active{color:#3a0712!important;text-shadow:none!important;}
    .rb-bottom-nav__item.is-active .rb-bottom-nav__icon{filter:drop-shadow(0 8px 12px rgba(143,87,255,.18));}

    @keyframes rfFadeUp{from{opacity:0;transform:translateY(10px);}to{opacity:1;transform:translateY(0);}}
    @media(max-width:430px){.rf-invite-main{grid-template-columns:112px minmax(0,1fr);}.rf-qr-inner{width:94px;height:94px;}.rf-stat-value{font-size:11.8px;}}
    @media(max-width:370px){.rf-page{padding-left:8px;padding-right:8px;}.rf-logo-card{width:44px;height:44px;border-radius:15px;}.rf-logo-card img{width:38px;height:38px;}.rf-title h1{font-size:21px;}.rf-invite-card{padding:15px;border-radius:28px;}.rf-invite-head{grid-template-columns:1fr;}.rf-main-title{font-size:21px;}.rf-invite-main{grid-template-columns:1fr;}.rf-qr-stage{min-height:132px;}.rf-invite-actions{grid-template-columns:1fr;}.rf-stats{gap:7px;}.rf-stat{min-height:74px;border-radius:19px;padding:10px 7px;}.rf-stat-label{font-size:9px;}.rf-stat-value{font-size:10.8px;}.rf-user-name,.rf-user-sub{max-width:168px;}.rf-commission-grid{grid-template-columns:1fr;}}
    @media(prefers-reduced-motion:reduce){*,*::before,*::after{animation:none!important;transition:none!important;}}
  </style>
</head>

<body>
  <main class="rf-page">
    <div class="rf-phone">

      {{-- HEADER --}}
      <header class="rf-header">
        <div class="rf-brand">
          <div class="rf-logo-card">
            <img src="{{ asset('logo.png') }}" alt="Velora Finance">
          </div>

          <div class="rf-title">
            <span>Velora Reward</span>
            <h1>Referral</h1>
          </div>
        </div>

        <div class="rf-header-actions">
          <a href="{{ url('/dashboard') }}" class="rf-header-btn" aria-label="Kembali ke Dashboard">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M15 18 9 12l6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </a>

          <a href="{{ url('/akun') }}" class="rf-header-btn" aria-label="Akun">
            <svg viewBox="0 0 24 24" fill="none">
              <circle cx="12" cy="8" r="4" fill="currentColor"/>
              <path d="M4 21a8 8 0 0 1 16 0" fill="currentColor"/>
            </svg>
          </a>
        </div>
      </header>

      {{-- INVITE CARD --}}
      <section class="rf-invite-card">
        <div class="rf-invite-head">
          <div>
            <span class="rf-kicker">Invite & Earn</span>
            <h2 class="rf-main-title">Bangun jaringan komisi Velora.</h2>
            <p class="rf-main-sub">
              Bagikan kode referral kamu dan pantau komisi dari aktivitas referral secara langsung.
            </p>
          </div>

          <div class="rf-rate-pill">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
            </svg>
            33%
          </div>
        </div>

        <div class="rf-invite-main">
          <div class="rf-qr-stage">
            <div class="rf-qr-inner">
              <img src="{{ $qrImage }}" alt="QR Referral Velora">
            </div>
          </div>

          <div class="rf-code-panel">
            <div>
              <p class="rf-code-label">Kode Referral</p>
              <input
                id="referralCodeField"
                value="{{ $referralCode }}"
                class="rf-code-value"
                readonly
                aria-label="Kode Referral"
              >
            </div>

            <div>
              <p class="rf-code-label">Tautan Referral</p>
              <input
                id="referralLinkField"
                value="{{ $referralLink }}"
                class="rf-link-value"
                readonly
                aria-label="Tautan Referral"
              >
            </div>
          </div>
        </div>

        <div class="rf-invite-actions">
          <button type="button" class="rf-btn rf-btn-primary" onclick="shareReferral()">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M4 12v7a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              <path d="M12 16V3" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
              <path d="m7 8 5-5 5 5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Bagikan Link
          </button>

          <button type="button" class="rf-btn rf-btn-secondary" onclick="copyById('referralLinkField', 'Tautan referral berhasil dicopy!')">
            <svg viewBox="0 0 24 24" fill="none">
              <rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2"/>
              <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
            </svg>
            Copy Link
          </button>
        </div>
      </section>

      {{-- STATS --}}
      <section class="rf-stats" aria-label="Statistik Referral">
        <div class="rf-stat">
          <p class="rf-stat-label">Jumlah Referral</p>
          <div class="rf-stat-value"><span>{{ $totalReferral }}</span> User</div>
        </div>

        <div class="rf-stat">
          <p class="rf-stat-label">Total Komisi</p>
          <div class="rf-stat-value">Rp {{ number_format($totalCommission, 0, ',', '.') }}</div>
        </div>

        <div class="rf-stat">
          <p class="rf-stat-label">Saldo Komisi</p>
          <div class="rf-stat-value">Rp {{ number_format($saldoKomisi, 0, ',', '.') }}</div>
        </div>
      </section>

      {{-- USER REFERRAL --}}
      <section class="rf-section">
        <div class="rf-section-head">
          <div class="rf-section-title">
            <h2>User Referral</h2>
            <p>Daftar user yang memakai kode kamu</p>
          </div>

          <div class="rf-section-hint">
            Total {{ $totalReferral }}
          </div>
        </div>

        <div class="rf-card-list">
          @forelse ($refUsers as $ru)
            <article class="rf-user-card">
              <div class="rf-row-head">
                <div class="rf-user-left">
                  <div class="rf-avatar" aria-hidden="true">
                    {{ strtoupper(substr($ru->name ?? 'U', 0, 1)) }}
                  </div>

                  <div class="rf-user-meta">
                    <strong class="rf-user-name">{{ $ru->name }}</strong>
                    <span class="rf-user-sub">{{ $ru->phone ?? '-' }}</span>
                  </div>
                </div>

                <span class="rf-small-badge">
                  {{ optional($ru->created_at)->format('d M') ?? '-' }}
                </span>
              </div>
            </article>
          @empty
            <div class="rf-empty">
              Belum ada user yang daftar memakai kode kamu.
            </div>
          @endforelse

          @if(is_object($refUsers) && method_exists($refUsers, 'links'))
            <div class="rf-pager">
              {{ $refUsers->links() }}
            </div>
          @endif
        </div>
      </section>

      {{-- RIWAYAT KOMISI --}}
      <section class="rf-section">
        <div class="rf-section-head">
          <div class="rf-section-title">
            <h2>Riwayat Komisi</h2>
            <p>Komisi terbaru dari pembelian produk referral</p>
          </div>

          <div class="rf-section-hint">
            Terbaru
          </div>
        </div>

        <div class="rf-card-list">
          @forelse ($commissions as $c)
            <article class="rf-commission-card">
              <div class="rf-row-head">
                <div class="rf-user-left">
                  <div class="rf-avatar" aria-hidden="true">
                    %
                  </div>

                  <div class="rf-user-meta">
                    <strong class="rf-user-name">
                      @if($c->source_type === 'deposit')
                        Deposit
                      @else
                        Beli Produk
                      @endif
                    </strong>
                    <span class="rf-user-sub">{{ optional($c->created_at)->format('d-m-Y H:i') ?? '-' }}</span>
                  </div>
                </div>

                <span class="rf-small-badge">{{ (float) $c->rate * 100 }}%</span>
              </div>

              <div class="rf-commission-grid">
                <div class="rf-mini-info">
                  <span>Dasar</span>
                  <strong>Rp {{ number_format($c->base_amount, 0, ',', '.') }}</strong>
                </div>

                <div class="rf-mini-info">
                  <span>Rate</span>
                  <strong>{{ (float) $c->rate * 100 }}%</strong>
                </div>

                <div class="rf-mini-info">
                  <span>Komisi</span>
                  <strong class="is-accent">Rp {{ number_format($c->commission_amount, 0, ',', '.') }}</strong>
                </div>
              </div>
            </article>
          @empty
            <div class="rf-empty">
              Belum ada komisi masuk.
            </div>
          @endforelse

          @if(is_object($commissions) && method_exists($commissions, 'links'))
            <div class="rf-pager">
              {{ $commissions->links() }}
            </div>
          @endif
        </div>
      </section>

      <div class="rb-bottom-spacer"></div>
    </div>
  </main>

  <div id="rfToast" class="rf-toast" role="status" aria-live="polite">
    <svg viewBox="0 0 24 24" fill="none">
      <path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    <span>Tautan referral berhasil dicopy!</span>
  </div>

  @include('partials.bottom-nav')

  <script>
    function showToast(message) {
      const toast = document.getElementById('rfToast');
      if (!toast) return;

      const text = toast.querySelector('span');
      if (text) text.textContent = message;

      toast.classList.add('show');

      clearTimeout(window.__rfToastTimer);
      window.__rfToastTimer = setTimeout(function () {
        toast.classList.remove('show');
      }, 1800);
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

      if (!link) {
        showToast('Tautan referral belum tersedia.');
        return;
      }

      const shareData = {
        title: 'Referral Velora',
        text: `Gabung pakai kode referral saya: ${code}`,
        url: link
      };

      try {
        if (navigator.share) {
          await navigator.share(shareData);
        } else {
          await copyText(link, 'Tautan referral berhasil dicopy!');
        }
      } catch (error) {
        if (error && error.name !== 'AbortError') {
          showToast('Gagal membagikan tautan.');
        }
      }
    }
  </script>
</body>
</html>
