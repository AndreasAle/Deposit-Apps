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
  :root{
    /* Velora logo derived palette */
    --vl-white:#ffffff;
    --vl-page:#f7f3ff;
    --vl-ink:#3b0f16;
    --vl-maroon:#4a1218;
    --vl-maroon2:#2b070c;
    --vl-gold:#ffb33a;
    --vl-gold2:#ffd45c;
    --vl-gold-soft:#fff5d8;
    --vl-purple:#7d42ff;
    --vl-purple2:#b94dff;
    --vl-violet:#d85cff;
    --vl-lilac:#f4e7ff;
    --vl-muted:#988993;
    --vl-border:rgba(74,18,24,.10);
    --vl-shadow:0 18px 45px rgba(58,15,24,.16);
    --vl-shadow-strong:0 24px 58px rgba(58,15,24,.22);
  }

  .rb-bottom-spacer{
    height: 104px;
  }

  .rb-bottom-nav{
    position: fixed;
    left: 50%;
    bottom: 14px;
    z-index: 90;
    transform: translateX(-50%);

    width: min(calc(100% - 28px), 404px);
    min-height: 76px;
    padding: 9px 10px calc(9px + env(safe-area-inset-bottom));

    display: grid;
    grid-template-columns: repeat(5, 1fr);
    align-items: center;
    gap: 4px;

    background:
      radial-gradient(circle at 13% 0%, rgba(255,179,58,.26), transparent 36%),
      radial-gradient(circle at 50% -16%, rgba(216,92,255,.24), transparent 46%),
      radial-gradient(circle at 91% 4%, rgba(125,66,255,.18), transparent 42%),
      linear-gradient(180deg, rgba(255,255,255,.94), rgba(255,251,255,.86));

    border: 1px solid rgba(255,255,255,.76);
    border-radius: 999px;

    box-shadow:
      var(--vl-shadow-strong),
      0 -10px 30px rgba(255,255,255,.68) inset,
      0 0 0 1px rgba(74,18,24,.06) inset,
      0 0 34px rgba(216,92,255,.10),
      0 0 36px rgba(255,179,58,.08);

    backdrop-filter: blur(26px) saturate(1.2);
    -webkit-backdrop-filter: blur(26px) saturate(1.2);

    overflow: visible;
    isolation: isolate;
  }

  .rb-bottom-nav::before{
    content:"";
    position:absolute;
    inset:0;
    z-index:0;
    border-radius:inherit;
    pointer-events:none;
    background:
      linear-gradient(180deg, rgba(255,255,255,.94), transparent 48%),
      linear-gradient(90deg, transparent, rgba(255,255,255,.44), transparent);
    opacity:.74;
  }

  .rb-bottom-nav::after{
    content:"";
    position:absolute;
    left:9%;
    right:9%;
    top:0;
    z-index:1;
    height:1px;
    border-radius:999px;
    pointer-events:none;
    background:linear-gradient(90deg, transparent, rgba(255,179,58,.82), rgba(216,92,255,.70), rgba(125,66,255,.55), transparent);
  }

  .rb-bottom-nav__item{
    position: relative;
    z-index: 2;
    min-height: 56px;
    border-radius: 999px;
    text-decoration: none;
    color: rgba(74,18,24,.48);

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;

    font-size: 10px;
    font-weight: 850;
    line-height: 1;

    transition:
      transform .22s cubic-bezier(.22,.8,.22,1),
      color .22s ease,
      background .22s ease,
      opacity .22s ease,
      filter .22s ease;

    -webkit-tap-highlight-color: transparent;
  }

  .rb-bottom-nav__item:hover{
    transform: translateY(-2px);
    color: var(--vl-maroon);
  }

  .rb-bottom-nav__item:hover .rb-bottom-nav__icon{
    background:rgba(255,245,216,.72);
    color:var(--vl-purple);
  }

  .rb-bottom-nav__icon{
    width: 38px;
    height: 38px;
    border-radius: 999px;

    display: grid;
    place-items: center;

    color: rgba(74,18,24,.48);
    background: rgba(255,255,255,.18);
    border:1px solid transparent;

    transition:
      transform .22s cubic-bezier(.22,.8,.22,1),
      color .22s ease,
      background .22s ease,
      box-shadow .22s ease,
      border-color .22s ease;
  }

  .rb-bottom-nav__icon svg{
    width: 20px;
    height: 20px;
    display: block;
    stroke-width: 2.15;
  }

  .rb-bottom-nav__label{
    display: block;
    font-size: 9.4px;
    font-weight: 900;
    letter-spacing:-.015em;
    color: currentColor;
    opacity: .82;
    white-space: nowrap;
  }

  .rb-bottom-nav__item.is-active{
    color: var(--vl-maroon);
    transform: translateY(-2px);
  }

  .rb-bottom-nav__item.is-active::before{
    content:"";
    position:absolute;
    left:50%;
    top:50%;
    width:62px;
    height:62px;
    transform:translate(-50%,-54%);
    border-radius:999px;
    z-index:-1;
    pointer-events:none;
    background:
      radial-gradient(circle at 32% 18%, rgba(255,255,255,.92), transparent 34%),
      linear-gradient(135deg, rgba(255,179,58,.22), rgba(216,92,255,.16));
    filter:blur(2px);
    opacity:.95;
  }

  .rb-bottom-nav__item.is-active .rb-bottom-nav__icon{
    color:#ffffff;
    border-color:rgba(255,255,255,.48);
    background:
      radial-gradient(circle at 30% 16%, rgba(255,255,255,.78), transparent 31%),
      linear-gradient(135deg, var(--vl-gold) 0%, var(--vl-gold2) 22%, var(--vl-violet) 58%, var(--vl-purple) 100%);

    box-shadow:
      0 16px 30px rgba(216,92,255,.26),
      0 12px 24px rgba(255,179,58,.20),
      0 0 0 1px rgba(255,255,255,.22) inset,
      0 0 24px rgba(125,66,255,.24);

    transform: translateY(-10px) scale(1.08);
  }

  .rb-bottom-nav__item.is-active .rb-bottom-nav__label{
    margin-top:-7px;
    min-height:20px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:0 8px;
    border-radius:999px;
    color:var(--vl-maroon);
    opacity:1;
    background:rgba(255,255,255,.74);
    border:1px solid rgba(74,18,24,.06);
    box-shadow:
      0 8px 18px rgba(74,18,24,.08),
      inset 0 1px 0 rgba(255,255,255,.86);
  }

  .rb-bottom-nav__item.is-active::after{
    content:"";
    position:absolute;
    top:5px;
    left:50%;
    width:34px;
    height:34px;
    transform:translateX(-50%);
    border-radius:999px;
    background:linear-gradient(135deg, rgba(255,179,58,.32), rgba(216,92,255,.28));
    filter:blur(16px);
    z-index:-2;
    pointer-events:none;
  }

  @media (max-width: 370px){
    .rb-bottom-spacer{
      height: 96px;
    }

    .rb-bottom-nav{
      bottom: 10px;
      width: min(calc(100% - 20px), 404px);
      min-height: 70px;
      padding: 8px 8px calc(8px + env(safe-area-inset-bottom));
      gap: 3px;
    }

    .rb-bottom-nav__item{
      min-height: 52px;
    }

    .rb-bottom-nav__icon{
      width: 35px;
      height: 35px;
    }

    .rb-bottom-nav__icon svg{
      width: 19px;
      height: 19px;
    }

    .rb-bottom-nav__label{
      font-size: 8.8px;
    }

    .rb-bottom-nav__item.is-active .rb-bottom-nav__label{
      padding:0 6px;
    }
  }

  @media (max-width: 340px){
    .rb-bottom-nav{
      width: min(calc(100% - 14px), 404px);
      padding-left: 5px;
      padding-right: 5px;
      gap: 2px;
    }

    .rb-bottom-nav__label{
      font-size: 8.2px;
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

  @media (prefers-reduced-motion: reduce){
    .rb-bottom-nav,
    .rb-bottom-nav::before,
    .rb-bottom-nav::after,
    .rb-bottom-nav__item,
    .rb-bottom-nav__item::before,
    .rb-bottom-nav__item::after,
    .rb-bottom-nav__icon,
    .rb-bottom-nav__label{
      transition:none !important;
      animation:none !important;
    }
  }
</style>

<nav class="rb-bottom-nav" aria-label="Navigasi utama Velora">
  <a href="{{ $homeUrl }}" class="rb-bottom-nav__item {{ $activeHome ? 'is-active' : '' }}">
    <span class="rb-bottom-nav__icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none">
        <path d="M3 10.5 12 3l9 7.5v9A1.5 1.5 0 0 1 19.5 21h-15A1.5 1.5 0 0 1 3 19.5v-9Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
        <path d="M9 21v-7h6v7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </span>
    <span class="rb-bottom-nav__label">Beranda</span>
  </a>

  <a href="{{ $marketUrl }}" class="rb-bottom-nav__item {{ $activeMarket ? 'is-active' : '' }}">
    <span class="rb-bottom-nav__icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none">
        <rect x="4" y="8" width="3" height="8" rx="1" stroke="currentColor" stroke-width="2"/>
        <line x1="5.5" y1="5" x2="5.5" y2="8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <line x1="5.5" y1="16" x2="5.5" y2="19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <rect x="10.5" y="5" width="3" height="7" rx="1" stroke="currentColor" stroke-width="2"/>
        <line x1="12" y1="3" x2="12" y2="5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <line x1="12" y1="12" x2="12" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <rect x="17" y="9" width="3" height="7" rx="1" stroke="currentColor" stroke-width="2"/>
        <line x1="18.5" y1="6" x2="18.5" y2="9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <line x1="18.5" y1="16" x2="18.5" y2="20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
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
    <span class="rb-bottom-nav__label">Akun</span>
  </a>
</nav>
