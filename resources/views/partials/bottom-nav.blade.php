@php
  $homeUrl = url('/dashboard');
  $marketUrl = route('market.index');
  $forumUrl = route('team.index');
  $portfolioUrl = route('investasi.index');
  $profileUrl = url('/akun');

  $activeHome = request()->is('dashboard');

  $activeMarket = request()->routeIs('market.index')
    || request()->is('pasar')
    || request()->is('pasar/*');

  $activeForum = request()->routeIs('team.index')
    || request()->routeIs('team.*')
    || request()->is('team')
    || request()->is('team/*');

  $activePortfolio = request()->routeIs('investasi.index')
    || request()->is('investasi')
    || request()->is('investasi/*');

  $activeProfile = request()->is('akun')
    || request()->is('akun/*')
    || request()->is('profile')
    || request()->is('profile/*');
@endphp

<style>
  /* ============ CAPITAL WAVE BOTTOM NAV ============ */
  .rb-bottom-spacer{ height:104px; }

  .rb-bottom-nav{
    position:fixed;
    left:50%;
    bottom:16px;
    z-index:90;
    transform:translateX(-50%);

    width:min(calc(100% - 28px), 412px);
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:5px;
    padding:8px 9px calc(8px + env(safe-area-inset-bottom));

    border-radius:999px;
    background:linear-gradient(180deg, rgba(255,255,255,.97), rgba(246,249,252,.93));
    border:1px solid rgba(11,39,64,.08);
    box-shadow:
      0 14px 34px rgba(11,39,64,.16),
      0 3px 10px rgba(11,39,64,.06),
      inset 0 1px 0 rgba(255,255,255,.85);
    backdrop-filter:blur(22px) saturate(1.1);
    -webkit-backdrop-filter:blur(22px) saturate(1.1);
    isolation:isolate;
  }

  /* hairline emas tipis di atas */
  .rb-bottom-nav::after{
    content:"";
    position:absolute;
    left:24%; right:24%; top:0;
    height:1px;
    border-radius:999px;
    pointer-events:none;
    background:linear-gradient(90deg, transparent, rgba(201,148,51,.6), transparent);
  }

  .rb-bottom-nav__item{
    position:relative;
    flex:0 0 auto;
    min-width:46px;
    height:48px;
    padding:0 13px;
    border-radius:999px;
    text-decoration:none;
    color:#8493a6;

    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;

    transition:
      transform .24s cubic-bezier(.22,.8,.22,1),
      color .2s ease,
      background .24s ease,
      flex-basis .24s ease;
    -webkit-tap-highlight-color:transparent;
  }

  .rb-bottom-nav__item:hover{ color:#0b2740; }

  .rb-bottom-nav__icon{ display:grid; place-items:center; }
  .rb-bottom-nav__icon svg{ width:22px; height:22px; display:block; stroke-width:2; }

  .rb-bottom-nav__label{
    display:none;
    font-family:'Plus Jakarta Sans', system-ui, sans-serif;
    font-size:12.5px;
    font-weight:700;
    letter-spacing:-.01em;
    line-height:1;
    white-space:nowrap;
  }

  /* ===== ACTIVE = pill navy + ikon emas + label ===== */
  .rb-bottom-nav__item.is-active{
    color:#ffffff;
    padding:0 16px;
    background:linear-gradient(135deg, #123457 0%, #0b2740 100%);
    box-shadow:
      0 10px 22px rgba(11,39,64,.32),
      inset 0 1px 0 rgba(255,255,255,.1);
  }

  /* hairline emas di pill aktif */
  .rb-bottom-nav__item.is-active::after{
    content:"";
    position:absolute;
    inset:0;
    border-radius:999px;
    padding:1px;
    pointer-events:none;
    background:linear-gradient(135deg, rgba(232,200,116,.8), rgba(232,200,116,0) 55%);
    -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
    -webkit-mask-composite:xor;
    mask-composite:exclude;
    opacity:.75;
  }

  .rb-bottom-nav__item.is-active .rb-bottom-nav__icon{ color:#e8c874; }
  .rb-bottom-nav__item.is-active .rb-bottom-nav__label{ display:inline-block; }

  @media (max-width:370px){
    .rb-bottom-spacer{ height:96px; }
    .rb-bottom-nav{ bottom:12px; width:min(calc(100% - 18px), 412px); gap:3px; padding:7px 7px calc(7px + env(safe-area-inset-bottom)); }
    .rb-bottom-nav__item{ min-width:42px; height:46px; padding:0 10px; }
    .rb-bottom-nav__item.is-active{ padding:0 13px; }
    .rb-bottom-nav__icon svg{ width:21px; height:21px; }
    .rb-bottom-nav__label{ font-size:12px; }
  }

  @media (max-width:330px){
    .rb-bottom-nav__label{ font-size:11px; }
    .rb-bottom-nav__item{ min-width:38px; padding:0 8px; }
  }

  @media (prefers-reduced-motion:reduce){
    .rb-bottom-nav__item{ transition:none !important; }
  }
</style>

<nav class="rb-bottom-nav" aria-label="Navigasi utama Capital Wave">
  <a href="{{ $homeUrl }}" class="rb-bottom-nav__item {{ $activeHome ? 'is-active' : '' }}" aria-label="Beranda">
    <span class="rb-bottom-nav__icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none">
        <path d="M3 10.5 12 3l9 7.5v9A1.5 1.5 0 0 1 19.5 21h-15A1.5 1.5 0 0 1 3 19.5v-9Z" stroke="currentColor" stroke-linejoin="round"/>
        <path d="M9 21v-7h6v7" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </span>
    <span class="rb-bottom-nav__label">Beranda</span>
  </a>

  <a href="{{ $marketUrl }}" class="rb-bottom-nav__item {{ $activeMarket ? 'is-active' : '' }}" aria-label="Pasar">
    <span class="rb-bottom-nav__icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none">
        <path d="M4 15l5-5 4 4 6-7" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M15 7h4v4" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </span>
    <span class="rb-bottom-nav__label">Pasar</span>
  </a>

  <a href="{{ $forumUrl }}" class="rb-bottom-nav__item {{ $activeForum ? 'is-active' : '' }}" aria-label="Forum">
    <span class="rb-bottom-nav__icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none">
        <path d="M7.5 18.5H7a4 4 0 0 1-4-4V8a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v6.5a4 4 0 0 1-4 4h-4.2L8.6 21.2a.7.7 0 0 1-1.1-.58v-2.12Z" stroke="currentColor" stroke-linejoin="round"/>
        <path d="M8 9h8M8 13h5.5" stroke="currentColor" stroke-linecap="round"/>
      </svg>
    </span>
    <span class="rb-bottom-nav__label">Forum</span>
  </a>

  <a href="{{ $portfolioUrl }}" class="rb-bottom-nav__item {{ $activePortfolio ? 'is-active' : '' }}" aria-label="Portofolio">
    <span class="rb-bottom-nav__icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none">
        <path d="M8 7V6a3 3 0 0 1 3-3h2a3 3 0 0 1 3 3v1" stroke="currentColor" stroke-linecap="round"/>
        <path d="M4 8h16a1.5 1.5 0 0 1 1.5 1.5v8A2.5 2.5 0 0 1 19 20H5a2.5 2.5 0 0 1-2.5-2.5v-8A1.5 1.5 0 0 1 4 8Z" stroke="currentColor" stroke-linejoin="round"/>
        <path d="M9 12h6" stroke="currentColor" stroke-linecap="round"/>
      </svg>
    </span>
    <span class="rb-bottom-nav__label">Portofolio</span>
  </a>

  <a href="{{ $profileUrl }}" class="rb-bottom-nav__item {{ $activeProfile ? 'is-active' : '' }}" aria-label="Akun">
    <span class="rb-bottom-nav__icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none">
        <path d="M20 21a8 8 0 0 0-16 0" stroke="currentColor" stroke-linecap="round"/>
        <circle cx="12" cy="8" r="4" stroke="currentColor"/>
      </svg>
    </span>
    <span class="rb-bottom-nav__label">Akun</span>
  </a>
</nav>
