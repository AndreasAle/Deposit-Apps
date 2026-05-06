@php
    $authUser = auth()->user();

    function adminMoney($value) {
        return 'Rp ' . number_format((float) $value, 0, ',', '.');
    }

    function adminInitial($name) {
        return strtoupper(substr($name ?: 'U', 0, 1));
    }

    $roleClass = ($user->role ?? 'user') === 'admin' ? 'admin' : 'user';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />

    <title>Admin | Detail User</title>

    <style>
        :root {
            --bg: #f7f9fd;
            --card: #ffffff;
            --text: #101828;
            --muted: #667085;
            --muted-2: #98a2b3;
            --line: #e7ebf3;

            --blue: #3157f8;
            --blue-soft: #eef3ff;
            --green: #12b76a;
            --green-soft: #eafaf2;
            --yellow: #f79009;
            --yellow-soft: #fff6e6;
            --red: #f04438;
            --red-soft: #fff0ee;
            --purple: #7a5af8;
            --purple-soft: #f3f0ff;

            --shadow: 0 18px 40px rgba(16, 24, 40, .06);
            --shadow-soft: 0 10px 24px rgba(16, 24, 40, .045);

            --radius-xl: 28px;
            --radius-lg: 22px;
            --sidebar: 260px;
            --topbar: 74px;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            margin: 0;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif;
            color: var(--text);
            background:
                radial-gradient(900px 420px at 55% -10%, rgba(49, 87, 248, .10), transparent 62%),
                radial-gradient(760px 380px at 96% 8%, rgba(18, 183, 106, .08), transparent 58%),
                var(--bg);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input,
        select {
            font-family: inherit;
        }

        .admin-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: var(--sidebar) 1fr;
        }

        /* SIDEBAR */
        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            background: rgba(255, 255, 255, .86);
            backdrop-filter: blur(18px);
            border-right: 1px solid var(--line);
            padding: 18px 14px;
            display: flex;
            flex-direction: column;
            z-index: 60;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 10px 18px;
            border-bottom: 1px solid var(--line);
        }

        .brand-logo {
            width: 42px;
            height: 42px;
            border-radius: 15px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #3157f8 0%, #5a7cff 52%, #12b76a 100%);
            color: #fff;
            font-weight: 950;
            letter-spacing: -.04em;
            box-shadow: 0 14px 28px rgba(49, 87, 248, .24);
        }

        .brand-name strong {
            display: block;
            font-size: 15px;
            line-height: 1.1;
            letter-spacing: -.02em;
        }

        .brand-name span {
            display: block;
            margin-top: 4px;
            font-size: 12px;
            color: var(--muted);
        }

        .sidebar-search {
            margin: 16px 4px 12px;
            position: relative;
        }

        .sidebar-search span {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted-2);
            font-size: 14px;
        }

        .sidebar-search input {
            width: 100%;
            height: 44px;
            border-radius: 15px;
            border: 1px solid var(--line);
            background: #f7f9fd;
            outline: none;
            padding: 0 14px 0 40px;
            font-size: 13px;
            color: var(--text);
            transition: .18s ease;
        }

        .sidebar-search input:focus {
            border-color: rgba(49, 87, 248, .35);
            box-shadow: 0 0 0 4px rgba(49, 87, 248, .08);
            background: #fff;
        }

        .nav-scroll {
            overflow: auto;
            padding-right: 2px;
            flex: 1;
        }

        .nav-label {
            margin: 18px 10px 8px;
            color: var(--muted-2);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .11em;
            text-transform: uppercase;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 10px;
            margin: 5px 2px;
            border-radius: 16px;
            border: 1px solid transparent;
            color: #475467;
            transition: .18s ease;
        }

        .nav-item:hover {
            background: var(--blue-soft);
            color: var(--blue);
            transform: translateX(2px);
        }

        .nav-item.active {
            color: var(--blue);
            background: var(--blue-soft);
            border-color: rgba(49, 87, 248, .12);
            box-shadow: inset 3px 0 0 var(--blue);
        }

        .nav-icon {
            width: 36px;
            height: 36px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: #f2f5fb;
            flex: 0 0 auto;
            font-size: 16px;
        }

        .nav-item.active .nav-icon {
            background: #fff;
            box-shadow: var(--shadow-soft);
        }

        .nav-text b {
            display: block;
            font-size: 13px;
            line-height: 1.1;
            color: inherit;
        }

        .nav-text span {
            display: block;
            margin-top: 4px;
            font-size: 11.5px;
            color: var(--muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user {
            margin-top: 14px;
            border: 1px solid var(--line);
            border-radius: 20px;
            padding: 12px;
            background: linear-gradient(180deg, #fff 0%, #f9fbff 100%);
            box-shadow: var(--shadow-soft);
        }

        .user-mini {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 42px;
            height: 42px;
            border-radius: 15px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #101828, #344054);
            color: #fff;
            font-weight: 900;
            flex: 0 0 auto;
        }

        .user-mini b {
            display: block;
            font-size: 13px;
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-mini span {
            display: block;
            margin-top: 4px;
            font-size: 11.5px;
            color: var(--muted);
        }

        /* MAIN */
        .main {
            min-width: 0;
            padding: 18px 24px 30px;
        }

        .topbar {
            height: var(--topbar);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 18px;
        }

        .top-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .hamburger {
            display: none;
            width: 42px;
            height: 42px;
            border: 1px solid var(--line);
            background: #fff;
            border-radius: 14px;
            cursor: pointer;
            font-weight: 950;
            color: var(--text);
            box-shadow: var(--shadow-soft);
        }

        .page-title h1 {
            margin: 0;
            font-size: 24px;
            line-height: 1.1;
            letter-spacing: -.04em;
        }

        .page-title p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 13px;
        }

        .top-center {
            flex: 1;
            max-width: 430px;
        }

        .global-search {
            position: relative;
        }

        .global-search span {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted-2);
        }

        .global-search input {
            width: 100%;
            height: 46px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, .82);
            outline: none;
            padding: 0 18px 0 44px;
            box-shadow: var(--shadow-soft);
            font-size: 13px;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .icon-btn,
        .logout-btn {
            height: 42px;
            border-radius: 14px;
            border: 1px solid var(--line);
            background: #fff;
            box-shadow: var(--shadow-soft);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: .18s ease;
            color: var(--text);
        }

        .icon-btn {
            width: 42px;
            font-size: 17px;
        }

        .logout-btn {
            padding: 0 14px;
            gap: 8px;
            color: var(--red);
            font-weight: 800;
            font-size: 13px;
            border-color: rgba(240, 68, 56, .18);
        }

        .icon-btn:hover,
        .logout-btn:hover {
            transform: translateY(-1px);
        }

        .status-pill {
            height: 42px;
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 0 14px;
            border-radius: 999px;
            background: #fff;
            border: 1px solid var(--line);
            box-shadow: var(--shadow-soft);
            color: var(--muted);
            font-size: 13px;
            font-weight: 700;
        }

        .live-dot {
            width: 9px;
            height: 9px;
            border-radius: 99px;
            background: var(--green);
            box-shadow: 0 0 0 5px rgba(18, 183, 106, .13);
        }

        /* DETAIL HERO */
        .profile-hero {
            position: relative;
            overflow: hidden;
            border-radius: var(--radius-xl);
            background:
                radial-gradient(420px 240px at 88% 8%, rgba(255, 255, 255, .18), transparent 55%),
                linear-gradient(135deg, #3157f8 0%, #233fd1 58%, #172554 100%);
            color: #fff;
            padding: 24px;
            box-shadow: 0 24px 46px rgba(49, 87, 248, .22);
            margin-bottom: 18px;
        }

        .profile-hero::after {
            content: "";
            position: absolute;
            width: 230px;
            height: 230px;
            border-radius: 999px;
            right: -70px;
            bottom: -100px;
            background: rgba(255, 255, 255, .11);
        }

        .profile-hero-inner {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
        }

        .profile-main {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
        }

        .profile-avatar {
            width: 74px;
            height: 74px;
            border-radius: 26px;
            background: rgba(255, 255, 255, .16);
            border: 1px solid rgba(255, 255, 255, .20);
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 26px;
            font-weight: 950;
            box-shadow: 0 18px 28px rgba(0, 0, 0, .12);
            flex: 0 0 auto;
        }

        .profile-info {
            min-width: 0;
        }

        .profile-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            height: 30px;
            padding: 0 11px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .18);
            font-size: 12px;
            font-weight: 800;
        }

        .profile-info h2 {
            margin: 12px 0 6px;
            font-size: 30px;
            line-height: 1.05;
            letter-spacing: -.055em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 640px;
        }

        .profile-info p {
            margin: 0;
            color: rgba(255, 255, 255, .76);
            font-size: 14px;
            line-height: 1.55;
        }

        .profile-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .hero-link {
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 14px;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 850;
            background: #fff;
            color: var(--blue);
            box-shadow: 0 14px 24px rgba(0, 0, 0, .10);
        }

        .hero-link.secondary {
            background: rgba(255, 255, 255, .13);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .18);
            box-shadow: none;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1.15fr .85fr;
            gap: 18px;
            align-items: start;
        }

        .panel {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .panel-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            padding: 18px 18px 0;
        }

        .panel-title b {
            display: block;
            font-size: 15px;
            letter-spacing: -.02em;
        }

        .panel-title span {
            display: block;
            margin-top: 4px;
            font-size: 12.5px;
            color: var(--muted);
        }

        .panel-inner {
            padding: 18px;
        }

        .alert {
            margin-bottom: 14px;
            border: 1px solid rgba(18, 183, 106, .20);
            background: var(--green-soft);
            color: #027a48;
            border-radius: 16px;
            padding: 12px 14px;
            font-size: 13px;
            font-weight: 750;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .info-card {
            border: 1px solid var(--line);
            border-radius: 20px;
            background: #fff;
            box-shadow: var(--shadow-soft);
            padding: 14px;
            min-height: 86px;
        }

        .info-label {
            color: var(--muted);
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .info-value {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            font-size: 14px;
            font-weight: 950;
            letter-spacing: -.02em;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 950;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .badge.vip {
            color: #3157f8;
            background: var(--blue-soft);
            border-color: rgba(49, 87, 248, .14);
        }

        .badge.admin {
            color: #b42318;
            background: var(--red-soft);
            border-color: rgba(240, 68, 56, .18);
        }

        .badge.user {
            color: #027a48;
            background: var(--green-soft);
            border-color: rgba(18, 183, 106, .16);
        }

        .badge.id {
            color: #6941c6;
            background: var(--purple-soft);
            border-color: rgba(122, 90, 248, .16);
        }

        .form-grid {
            display: grid;
            gap: 14px;
        }

        .form-card {
            border: 1px solid var(--line);
            border-radius: 24px;
            background: #fff;
            box-shadow: var(--shadow-soft);
            padding: 16px;
        }

        .form-card-head {
            margin-bottom: 14px;
        }

        .form-card-head b {
            display: block;
            font-size: 14px;
            letter-spacing: -.02em;
        }

        .form-card-head span {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 12.5px;
            line-height: 1.45;
        }

        label {
            display: block;
            margin-bottom: 7px;
            color: var(--text);
            font-size: 12.5px;
            font-weight: 900;
        }

        select,
        input {
            width: 100%;
            height: 46px;
            border-radius: 15px;
            border: 1px solid var(--line);
            background: #f9fbff;
            outline: none;
            padding: 0 14px;
            color: var(--text);
            font-size: 13px;
            transition: .18s ease;
        }

        select:focus,
        input:focus {
            border-color: rgba(49, 87, 248, .35);
            box-shadow: 0 0 0 4px rgba(49, 87, 248, .08);
            background: #fff;
        }

        .hint {
            margin-top: 8px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.5;
        }

        .submit {
            width: 100%;
            height: 44px;
            margin-top: 12px;
            border: 0;
            border-radius: 15px;
            background: var(--blue);
            color: #fff;
            font-weight: 950;
            font-size: 13px;
            cursor: pointer;
            box-shadow: 0 14px 24px rgba(49, 87, 248, .20);
            transition: .18s ease;
        }

        .submit:hover {
            transform: translateY(-1px);
            background: #2348db;
        }

        .side-stack {
            display: grid;
            gap: 18px;
        }

        .quick-list {
            display: grid;
            gap: 12px;
        }

        .quick-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            border: 1px solid var(--line);
            border-radius: 20px;
            padding: 14px;
            background: #fff;
            transition: .18s ease;
        }

        .quick-card:hover {
            border-color: rgba(49, 87, 248, .18);
            box-shadow: var(--shadow-soft);
            transform: translateY(-1px);
        }

        .quick-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .quick-icon {
            width: 42px;
            height: 42px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            background: var(--blue-soft);
            color: var(--blue);
            flex: 0 0 auto;
        }

        .quick-card:nth-child(2) .quick-icon {
            background: var(--green-soft);
            color: var(--green);
        }

        .quick-card:nth-child(3) .quick-icon {
            background: var(--yellow-soft);
            color: var(--yellow);
        }

        .quick-card:nth-child(4) .quick-icon {
            background: var(--purple-soft);
            color: var(--purple);
        }

        .quick-left b {
            display: block;
            font-size: 13px;
        }

        .quick-left span {
            display: block;
            margin-top: 4px;
            font-size: 12px;
            color: var(--muted);
            line-height: 1.35;
        }

        .quick-arrow {
            color: var(--muted-2);
            font-weight: 950;
        }

        .note-box {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.6;
        }

        .warning-box {
            border: 1px solid rgba(247, 144, 9, .20);
            background: var(--yellow-soft);
            color: #b54708;
            border-radius: 18px;
            padding: 14px;
            font-size: 12.5px;
            line-height: 1.55;
            font-weight: 700;
        }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(16, 24, 40, .48);
            backdrop-filter: blur(4px);
            z-index: 50;
        }

        body.sidebar-open {
            overflow: hidden;
        }

        body.sidebar-open .overlay {
            display: block;
        }

        @media (max-width: 1180px) {
            .content-grid {
                grid-template-columns: 1fr;
            }

            .top-center {
                display: none;
            }
        }

        @media (max-width: 860px) {
            .admin-shell {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                width: 86vw;
                max-width: 320px;
                transform: translateX(-105%);
                transition: .22s ease;
                box-shadow: 24px 0 50px rgba(16, 24, 40, .18);
            }

            body.sidebar-open .sidebar {
                transform: translateX(0);
            }

            .hamburger {
                display: inline-grid;
                place-items: center;
                flex: 0 0 auto;
            }

            .main {
                padding: 12px 14px 24px;
            }

            .topbar {
                position: sticky;
                top: 0;
                z-index: 40;
                height: auto;
                min-height: 66px;
                padding: 10px;
                border-radius: 20px;
                background: rgba(247, 249, 253, .86);
                backdrop-filter: blur(14px);
                border: 1px solid var(--line);
            }

            .page-title h1 {
                font-size: 19px;
            }

            .page-title p {
                font-size: 12px;
            }

            .status-pill {
                display: none;
            }

            .profile-hero {
                padding: 20px;
            }

            .profile-hero-inner {
                align-items: flex-start;
                flex-direction: column;
            }

            .profile-info h2 {
                font-size: 24px;
                white-space: normal;
            }

            .profile-actions {
                justify-content: flex-start;
                width: 100%;
            }

            .hero-link {
                flex: 1;
            }
        }

        @media (max-width: 620px) {
            .top-actions .icon-btn {
                display: none;
            }

            .logout-btn {
                width: 42px;
                padding: 0;
                font-size: 0;
            }

            .logout-btn span {
                font-size: 17px;
            }

            .profile-main {
                align-items: flex-start;
            }

            .profile-avatar {
                width: 62px;
                height: 62px;
                border-radius: 22px;
                font-size: 22px;
            }

            .profile-info h2 {
                font-size: 21px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .panel-head {
                flex-direction: column;
            }

            .hero-link {
                width: 100%;
            }
        }
    </style>
</head>

<body>
<div class="admin-shell">
    <div class="overlay" onclick="toggleSidebar(false)"></div>

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-logo">CW</div>
            <div class="brand-name">
                <strong>Crowdink</strong>
                <span>Admin Console</span>
            </div>
        </div>

        <div class="sidebar-search">
            <span>⌕</span>
            <input type="text" placeholder="Search menu..." oninput="filterMenu(this.value)">
        </div>

        <div class="nav-scroll">
            <nav id="navMenu">
                <div class="nav-label">Main Menu</div>

                <a class="nav-item" href="/admin" data-label="dashboard overview admin panel">
                    <div class="nav-icon">🏠</div>
                    <div class="nav-text">
                        <b>Dashboard</b>
                        <span>Overview sistem</span>
                    </div>
                </a>

                <a class="nav-item active" href="/admin/users" data-label="users kelola user vip saldo">
                    <div class="nav-icon">👥</div>
                    <div class="nav-text">
                        <b>Users</b>
                        <span>Kelola user & VIP</span>
                    </div>
                </a>

                <a class="nav-item" href="/admin/products" data-label="products produk tier investasi">
                    <div class="nav-icon">📦</div>
                    <div class="nav-text">
                        <b>Products</b>
                        <span>Produk & tier</span>
                    </div>
                </a>

                <a class="nav-item" href="/admin/deposits" data-label="deposits deposit saldo pembayaran">
                    <div class="nav-icon">💳</div>
                    <div class="nav-text">
                        <b>Deposits</b>
                        <span>Riwayat isi saldo</span>
                    </div>
                </a>

                <a class="nav-item" href="{{ route('admin.withdraw.page') }}" data-label="withdraw penarikan saldo">
                    <div class="nav-icon">↗</div>
                    <div class="nav-text">
                        <b>Withdraw</b>
                        <span>Permintaan penarikan</span>
                    </div>
                </a>

                <a class="nav-item" href="{{ route('admin.referral') }}" data-label="referral komisi invite users">
                    <div class="nav-icon">🎁</div>
                    <div class="nav-text">
                        <b>Referral</b>
                        <span>Users & komisi</span>
                    </div>
                </a>

                <a class="nav-item" href="{{ route('admin.forum.index') }}" data-label="forum post komentar team">
                    <div class="nav-icon">💬</div>
                    <div class="nav-text">
                        <b>Forum</b>
                        <span>Posting & komentar</span>
                    </div>
                </a>

                <div class="nav-label">System</div>

                <a class="nav-item" href="/admin/vip" data-label="vip settings level rule">
                    <div class="nav-icon">⭐</div>
                    <div class="nav-text">
                        <b>VIP Settings</b>
                        <span>Aturan level VIP</span>
                    </div>
                </a>

                <a class="nav-item" href="/admin/logs" data-label="logs aktivitas sistem">
                    <div class="nav-icon">📜</div>
                    <div class="nav-text">
                        <b>Logs</b>
                        <span>Aktivitas sistem</span>
                    </div>
                </a>

                <a class="nav-item" href="/" data-label="website back site home">
                    <div class="nav-icon">🌐</div>
                    <div class="nav-text">
                        <b>Back to Site</b>
                        <span>Kembali ke website</span>
                    </div>
                </a>
            </nav>
        </div>

        <div class="sidebar-user">
            <div class="user-mini">
                <div class="avatar">{{ adminInitial($authUser->name ?? 'Admin') }}</div>
                <div>
                    <b>{{ $authUser->name ?? 'Admin' }}</b>
                    <span>Super Administrator</span>
                </div>
            </div>
        </div>
    </aside>

    {{-- MAIN --}}
    <main class="main">
        <header class="topbar">
            <div class="top-left">
                <button class="hamburger" type="button" onclick="toggleSidebar(true)">☰</button>

                <div class="page-title">
                    <h1>Detail User</h1>
                    <p>Kelola profil, saldo, dan level VIP user secara manual.</p>
                </div>
            </div>

            <div class="top-center">
                <div class="global-search">
                    <span>⌕</span>
                    <input type="text" value="{{ $user->name ?? '' }}" readonly>
                </div>
            </div>

            <div class="top-actions">
                <div class="status-pill">
                    <span class="live-dot"></span>
                    <span>System Online</span>
                </div>

                <a href="/admin/users" class="icon-btn" title="Users">👥</a>
                <a href="/admin/deposits" class="icon-btn" title="Deposits">💳</a>

                <form action="/logout" method="POST" style="margin:0">
                    @csrf
                    <button class="logout-btn" type="submit">
                        <span>⎋</span>
                        Logout
                    </button>
                </form>
            </div>
        </header>

        {{-- PROFILE HERO --}}
        <section class="profile-hero">
            <div class="profile-hero-inner">
                <div class="profile-main">
                    <div class="profile-avatar">{{ adminInitial($user->name ?? 'U') }}</div>

                    <div class="profile-info">
                        <div class="profile-kicker">
                            <span>👤</span>
                            User Profile #{{ $user->id }}
                        </div>

                        <h2>{{ $user->name ?? 'Unknown User' }}</h2>

                    <p>
    {{ $user->phone ?: 'No phone' }} ·
    Saldo utama {{ adminMoney($user->saldo ?? 0) }} ·
    Saldo tarik {{ adminMoney($user->saldo_penarikan ?? 0) }} ·
    VIP {{ (int)($user->vip_level ?? 0) }}
</p>
                    </div>
                </div>

                <div class="profile-actions">
                    <a href="/admin/users" class="hero-link secondary">← Back Users</a>
                    <a href="/admin" class="hero-link">Dashboard →</a>
                </div>
            </div>
        </section>

        <section class="content-grid">
            {{-- LEFT --}}
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-title">
                        <b>Informasi User</b>
                        <span>Ringkasan data utama user dan status akun.</span>
                    </div>
                </div>

                <div class="panel-inner">
                    @if(session('success'))
                        <div class="alert">{{ session('success') }}</div>
                    @endif

                    <div class="info-grid">
                        <div class="info-card">
                            <div class="info-label">Nama</div>
                            <div class="info-value">{{ $user->name ?? '-' }}</div>
                        </div>

                        <div class="info-card">
                            <div class="info-label">Phone</div>
                            <div class="info-value">{{ $user->phone ?: '-' }}</div>
                        </div>

                        <div class="info-card">
                            <div class="info-label">Role</div>
                            <div class="info-value">
                                <span class="badge {{ $roleClass }}">
                                    {{ strtoupper($user->role ?? 'USER') }}
                                </span>
                            </div>
                        </div>

                      <div class="info-card">
    <div class="info-label">Saldo Utama</div>
    <div class="info-value">{{ adminMoney($user->saldo ?? 0) }}</div>
</div>

<div class="info-card">
    <div class="info-label">Saldo Penarikan</div>
    <div class="info-value" style="color:#12b76a">
        {{ adminMoney($user->saldo_penarikan ?? 0) }}
    </div>
</div>

<div class="info-card">
    <div class="info-label">Saldo Hold / Diproses</div>
    <div class="info-value" style="color:#f79009">
        {{ adminMoney($user->saldo_hold ?? 0) }}
    </div>
</div>

                        <div class="info-card">
                            <div class="info-label">VIP Saat Ini</div>
                            <div class="info-value">
                                <span class="badge vip">VIP {{ (int)($user->vip_level ?? 0) }}</span>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-label">User ID</div>
                            <div class="info-value">
                                <span class="badge id">#{{ $user->id }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="side-stack">
                <div class="panel">
                    <div class="panel-head">
                   <div class="panel-title">
    <b>Update Manual</b>
    <span>Override VIP, koreksi saldo utama, dan saldo penarikan user.</span>
</div>
                    </div>

                    <div class="panel-inner">
                        <div class="form-grid">
                            <form class="form-card" method="POST" action="/admin/users/{{ $user->id }}/vip">
                                @csrf

                                <div class="form-card-head">
                                    <b>Override VIP</b>
                                    <span>Pilih level VIP terbaru untuk user ini.</span>
                                </div>

                                <label>VIP Level</label>
                                <select name="vip_level">
                                    @for($i = 0; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ (int)($user->vip_level ?? 0) === $i ? 'selected' : '' }}>
                                            VIP {{ $i }}
                                        </option>
                                    @endfor
                                </select>

                                <button class="submit" type="submit">Update VIP</button>
                            </form>

                            <form class="form-card" method="POST" action="/admin/users/{{ $user->id }}/saldo">
                                @csrf

                                <div class="form-card-head">
                                    <b>Update Saldo Manual</b>
                                    <span>Gunakan untuk koreksi saldo plus atau minus.</span>
                                </div>

                                <label>Nominal (+ / -)</label>
                                <input
                                    type="number"
                                    name="amount"
                                    placeholder="Contoh: 100000 / -50000"
                                    required
                                />

                                <div class="hint">
                                    Contoh: <b>100000</b> untuk tambah saldo, atau <b>-50000</b> untuk mengurangi saldo.
                                </div>

                                <button class="submit" type="submit">Update Saldo</button>
                            </form>

                            <form class="form-card" method="POST" action="/admin/users/{{ $user->id }}/saldo-penarikan">
    @csrf

    <div class="form-card-head">
        <b>Update Saldo Penarikan</b>
        <span>
            Gunakan untuk menambah atau mengurangi saldo yang bisa ditarik user.
            Saldo penarikan sekarang: {{ adminMoney($user->saldo_penarikan ?? 0) }}.
        </span>
    </div>

    <label>Nominal Saldo Penarikan (+ / -)</label>
    <input
        type="number"
        name="amount"
        placeholder="Contoh: 50000 / -50000"
        required
    />

    <div class="hint">
        Contoh: <b>50000</b> untuk menambah saldo tarik, atau <b>-50000</b> untuk mengurangi saldo tarik.
        Saldo hold saat ini: <b>{{ adminMoney($user->saldo_hold ?? 0) }}</b>.
    </div>

    <button class="submit" type="submit" style="background:#12b76a; box-shadow:0 14px 24px rgba(18,183,106,.20);">
        Update Saldo Penarikan
    </button>
</form>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-head">
                        <div class="panel-title">
                            <b>Shortcuts</b>
                            <span>Akses cepat menu admin lain.</span>
                        </div>
                    </div>

                    <div class="panel-inner">
                        <div class="quick-list">
                            <a class="quick-card" href="/admin/users">
                                <div class="quick-left">
                                    <div class="quick-icon">👥</div>
                                    <div>
                                        <b>All Users</b>
                                        <span>Kembali ke daftar user.</span>
                                    </div>
                                </div>
                                <div class="quick-arrow">→</div>
                            </a>

                            <a class="quick-card" href="/admin/deposits">
                                <div class="quick-left">
                                    <div class="quick-icon">💳</div>
                                    <div>
                                        <b>Deposits</b>
                                        <span>Audit riwayat isi saldo.</span>
                                    </div>
                                </div>
                                <div class="quick-arrow">→</div>
                            </a>

                            <a class="quick-card" href="{{ route('admin.withdraw.page') }}">
                                <div class="quick-left">
                                    <div class="quick-icon">↗</div>
                                    <div>
                                        <b>Withdraw</b>
                                        <span>Proses permintaan penarikan.</span>
                                    </div>
                                </div>
                                <div class="quick-arrow">→</div>
                            </a>

                            <a class="quick-card" href="/admin/logs">
                                <div class="quick-left">
                                    <div class="quick-icon">📜</div>
                                    <div>
                                        <b>System Logs</b>
                                        <span>Lihat aktivitas sistem.</span>
                                    </div>
                                </div>
                                <div class="quick-arrow">→</div>
                            </a>
                        </div>
                    </div>
                </div>

            <div class="warning-box">
    Gunakan update saldo manual hanya untuk koreksi. Saldo utama dipakai untuk deposit dan pembelian produk,
    sedangkan saldo penarikan dipakai untuk withdraw. Pastikan setiap perubahan saldo tercatat di logs.
</div>
            </div>
        </section>
    </main>
</div>

<script>
    function toggleSidebar(open) {
        document.body.classList.toggle('sidebar-open', open === true);
    }

    function filterMenu(q) {
        q = (q || '').toLowerCase().trim();

        document.querySelectorAll('#navMenu .nav-item').forEach((item) => {
            const label = (item.getAttribute('data-label') || item.textContent || '').toLowerCase();
            item.style.display = !q || label.includes(q) ? 'flex' : 'none';
        });
    }

    (function mobileEvents() {
        document.querySelectorAll('#navMenu a').forEach((link) => {
            link.addEventListener('click', () => {
                if (window.matchMedia('(max-width: 860px)').matches) {
                    toggleSidebar(false);
                }
            });
        });

        window.addEventListener('resize', () => {
            if (!window.matchMedia('(max-width: 860px)').matches) {
                toggleSidebar(false);
            }
        });

        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') toggleSidebar(false);
        });
    })();
</script>
</body>
</html>