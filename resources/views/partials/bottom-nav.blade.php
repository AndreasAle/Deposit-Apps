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
  .rb-bottom-spacer{
    height: 86px;
  }

  .rb-bottom-nav{
    position: fixed;
    left: 50%;
    bottom: 12px;
    z-index: 90;
    transform: translateX(-50%);

    width: min(calc(100% - 28px), 390px);
    min-height: 70px;

    padding: 9px 10px calc(9px + env(safe-area-inset-bottom));

    display: grid;
    grid-template-columns: repeat(5, 1fr);
    align-items: center;
    gap: 5px;

    background:
      radial-gradient(circle at 50% 0%, rgba(0,223,130,.12), transparent 58%),
      linear-gradient(180deg, rgba(14,21,20,.92), rgba(8,12,12,.96));

    border: 1px solid rgba(255,255,255,.10);
    border-radius: 999px;

    box-shadow:
      0 20px 45px rgba(0,0,0,.38),
      0 0 0 1px rgba(255,255,255,.04) inset,
      0 0 34px rgba(0,223,130,.08);

    backdrop-filter: blur(22px);
    -webkit-backdrop-filter: blur(22px);
  }

  .rb-bottom-nav::before{
    content:"";
    position:absolute;
    inset:0;
    border-radius:inherit;
    pointer-events:none;
    background:
      linear-gradient(180deg, rgba(255,255,255,.08), transparent 45%);
    opacity:.75;
  }

  .rb-bottom-nav__item{
    position: relative;
    min-height: 52px;
    border-radius: 999px;
    text-decoration: none;
    color: rgba(220,241,234,.58);

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;

    font-size: 10px;
    font-weight: 800;
    line-height: 1;

    transition:
      transform .18s ease,
      color .18s ease,
      background .18s ease,
      opacity .18s ease;

    -webkit-tap-highlight-color: transparent;
  }

  .rb-bottom-nav__item:hover{
    transform: translateY(-1px);
    color: rgba(235,255,248,.82);
  }

  .rb-bottom-nav__icon{
    width: 36px;
    height: 36px;
    border-radius: 999px;

    display: grid;
    place-items: center;

    color: rgba(220,241,234,.58);
    background: transparent;

    transition:
      transform .18s ease,
      color .18s ease,
      background .18s ease,
      box-shadow .18s ease;
  }

  .rb-bottom-nav__icon svg{
    width: 20px;
    height: 20px;
    display: block;
  }

  .rb-bottom-nav__label{
    display: block;
    font-size: 9.5px;
    font-weight: 850;
    color: currentColor;
    opacity: .82;
    white-space: nowrap;
  }

  .rb-bottom-nav__item.is-active{
    color: #00DF82;
  }

  .rb-bottom-nav__item.is-active .rb-bottom-nav__icon{
    color: #071211;
    background:
      radial-gradient(circle at 30% 18%, rgba(255,255,255,.55), transparent 34%),
      linear-gradient(135deg, #00DF82 0%, #8cff2f 100%);

    box-shadow:
      0 12px 24px rgba(0,223,130,.28),
      0 0 0 1px rgba(255,255,255,.18) inset,
      0 0 26px rgba(0,223,130,.28);

    transform: translateY(-7px);
  }

  .rb-bottom-nav__item.is-active .rb-bottom-nav__label{
    margin-top: -5px;
    color: #00DF82;
    opacity: 1;
    text-shadow: 0 0 14px rgba(0,223,130,.22);
  }

  @media (max-width: 370px){
    .rb-bottom-nav{
      bottom: 10px;
      width: min(calc(100% - 22px), 390px);
      min-height: 66px;
      padding: 8px 8px calc(8px + env(safe-area-inset-bottom));
      gap: 4px;
    }

    .rb-bottom-nav__item{
      min-height: 50px;
    }

    .rb-bottom-nav__icon{
      width: 34px;
      height: 34px;
    }

    .rb-bottom-nav__icon svg{
      width: 19px;
      height: 19px;
    }

    .rb-bottom-nav__label{
      font-size: 9px;
    }
  }

  @media (max-width: 340px){
    .rb-bottom-nav{
      width: min(calc(100% - 16px), 390px);
      padding-left: 6px;
      padding-right: 6px;
      gap: 3px;
    }

    .rb-bottom-nav__label{
      font-size: 8.5px;
    }

    .rb-bottom-nav__icon{
      width: 32px;
      height: 32px;
    }

    .rb-bottom-nav__icon svg{
      width: 18px;
      height: 18px;
    }
  }
</style>

<nav class="rb-bottom-nav" aria-label="Navigasi utama">
  <a href="{{ $homeUrl }}" class="rb-bottom-nav__item {{ $activeHome ? 'is-active' : '' }}">
    <span class="rb-bottom-nav__icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none">
        <path d="M3 10.5 12 3l9 7.5v9A1.5 1.5 0 0 1 19.5 21h-15A1.5 1.5 0 0 1 3 19.5v-9Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
        <path d="M9 21v-7h6v7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </span>
    <span class="rb-bottom-nav__label">Rumah</span>
  </a>

  <a href="{{ $marketUrl }}" class="rb-bottom-nav__item {{ $activeMarket ? 'is-active' : '' }}">
    <span class="rb-bottom-nav__icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none">
        <path d="M7 17 17 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <path d="M9 7h8v8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M5 21h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </span>
    <span class="rb-bottom-nav__label">Pasar</span>
  </a>

  <a href="{{ $forumUrl }}" class="rb-bottom-nav__item {{ $activeForum ? 'is-active' : '' }}">
    <span class="rb-bottom-nav__icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none">
        <path d="M7.5 18.5H7a4 4 0 0 1-4-4V8a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v6.5a4 4 0 0 1-4 4h-4.2L8.6 21.2a.7.7 0 0 1-1.1-.58v-2.12Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
        <path d="M8 9h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <path d="M8 13h5.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </span>
    <span class="rb-bottom-nav__label">Forum</span>
  </a>

  <a href="{{ $portfolioUrl }}" class="rb-bottom-nav__item {{ $activePortfolio ? 'is-active' : '' }}">
    <span class="rb-bottom-nav__icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none">
        <path d="M8 7V6a3 3 0 0 1 3-3h2a3 3 0 0 1 3 3v1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <path d="M4 8h16a1.5 1.5 0 0 1 1.5 1.5v8A2.5 2.5 0 0 1 19 20H5a2.5 2.5 0 0 1-2.5-2.5v-8A1.5 1.5 0 0 1 4 8Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
        <path d="M9 12h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </span>
    <span class="rb-bottom-nav__label">Portofolio</span>
  </a>

  <a href="{{ $profileUrl }}" class="rb-bottom-nav__item {{ $activeProfile ? 'is-active' : '' }}">
    <span class="rb-bottom-nav__icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none">
        <path d="M20 21a8 8 0 0 0-16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"/>
      </svg>
    </span>
    <span class="rb-bottom-nav__label">Profil</span>
  </a>
</nav>