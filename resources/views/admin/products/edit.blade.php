@php
    $authUser = auth()->user();

    function adminMoney($value) {
        return 'Rp ' . number_format((float) $value, 0, ',', '.');
    }

    function adminInitial($name) {
        return strtoupper(substr($name ?: 'A', 0, 1));
    }

    $previewTotalProfit = (float)($product->daily_profit ?? 0) * (int)($product->duration_days ?? 0);
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />

    <title>Admin | Edit Produk</title>

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

        * { box-sizing: border-box; }

        html, body { min-height: 100%; }

        body {
            margin: 0;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif;
            color: var(--text);
            background:
                radial-gradient(900px 420px at 55% -10%, rgba(49, 87, 248, .10), transparent 62%),
                radial-gradient(760px 380px at 96% 8%, rgba(18, 183, 106, .08), transparent 58%),
                var(--bg);
        }

        a { color: inherit; text-decoration: none; }
        button, input, select { font-family: inherit; }

        .admin-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: var(--sidebar) 1fr;
        }

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

        .hero {
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

        .hero::after {
            content: "";
            position: absolute;
            width: 230px;
            height: 230px;
            border-radius: 999px;
            right: -70px;
            bottom: -100px;
            background: rgba(255, 255, 255, .11);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 760px;
        }

        .hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            height: 32px;
            padding: 0 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .18);
            font-size: 12px;
            font-weight: 800;
        }

        .hero h2 {
            margin: 16px 0 8px;
            font-size: 30px;
            line-height: 1.05;
            letter-spacing: -.055em;
        }

        .hero p {
            margin: 0;
            color: rgba(255, 255, 255, .76);
            font-size: 14px;
            line-height: 1.55;
        }

        .hero-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 18px;
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

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .form-section {
            grid-column: 1 / -1;
            margin-top: 2px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 950;
            letter-spacing: .11em;
            text-transform: uppercase;
        }

        .form-section::after {
            content: "";
            height: 1px;
            background: var(--line);
            flex: 1;
        }

        .field {
            border: 1px solid var(--line);
            border-radius: 20px;
            padding: 14px;
            background: #fff;
            box-shadow: var(--shadow-soft);
        }

        label {
            display: block;
            font-size: 12.5px;
            font-weight: 900;
            color: var(--text);
            margin-bottom: 8px;
        }

        input,
        select {
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

        input:focus,
        select:focus {
            border-color: rgba(49, 87, 248, .35);
            box-shadow: 0 0 0 4px rgba(49, 87, 248, .08);
            background: #fff;
        }

        .hint {
            margin-top: 8px;
            font-size: 12px;
            color: var(--muted);
            line-height: 1.5;
        }

        .submit-wrap {
            grid-column: 1 / -1;
        }

        .submit {
            width: 100%;
            height: 46px;
            border: 0;
            border-radius: 16px;
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

        .summary-grid {
            display: grid;
            gap: 12px;
        }

        .summary-card {
            border: 1px solid var(--line);
            border-radius: 20px;
            padding: 14px;
            background: #fff;
            box-shadow: var(--shadow-soft);
        }

        .summary-card span {
            display: block;
            color: var(--muted);
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 7px;
        }

        .summary-card b {
            display: block;
            font-size: 16px;
            letter-spacing: -.03em;
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

        .badge.success {
            color: #027a48;
            background: var(--green-soft);
            border-color: rgba(18, 183, 106, .16);
        }

        .badge.danger {
            color: #b42318;
            background: var(--red-soft);
            border-color: rgba(240, 68, 56, .18);
        }

        .badge.vip {
            color: var(--blue);
            background: var(--blue-soft);
            border-color: rgba(49, 87, 248, .14);
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

        body.sidebar-open { overflow: hidden; }
        body.sidebar-open .overlay { display: block; }

        @media (max-width: 1180px) {
            .content-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 860px) {
            .admin-shell { grid-template-columns: 1fr; }

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

            body.sidebar-open .sidebar { transform: translateX(0); }

            .hamburger {
                display: inline-grid;
                place-items: center;
                flex: 0 0 auto;
            }

            .main { padding: 12px 14px 24px; }

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

            .page-title h1 { font-size: 19px; }
            .page-title p { font-size: 12px; }
            .status-pill { display: none; }

            .hero { padding: 20px; }
            .hero h2 { font-size: 24px; }
            .hero p { font-size: 13px; }
        }

        @media (max-width: 620px) {
            .top-actions .icon-btn { display: none; }

            .logout-btn {
                width: 42px;
                padding: 0;
                font-size: 0;
            }

            .logout-btn span { font-size: 17px; }

            .hero-actions { flex-direction: column; }
            .hero-link { width: 100%; }

            .form-grid { grid-template-columns: 1fr; }

            .panel-head {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>

<body>
<div class="admin-shell">
    <div class="overlay" onclick="toggleSidebar(false)"></div>

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

                <a class="nav-item" href="/admin/users" data-label="users kelola user vip saldo">
                    <div class="nav-icon">👥</div>
                    <div class="nav-text">
                        <b>Users</b>
                        <span>Kelola user & VIP</span>
                    </div>
                </a>

                <a class="nav-item active" href="/admin/products" data-label="products produk tier investasi">
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

    <main class="main">
        <header class="topbar">
            <div class="top-left">
                <button class="hamburger" type="button" onclick="toggleSidebar(true)">☰</button>

                <div class="page-title">
                    <h1>Edit Produk</h1>
                    <p>Update data produk, harga, profit, durasi, dan minimal VIP.</p>
                </div>
            </div>

            <div class="top-actions">
                <div class="status-pill">
                    <span class="live-dot"></span>
                    <span>System Online</span>
                </div>

                <a href="/admin/products" class="icon-btn" title="Products">📦</a>
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

        <section class="hero">
            <div class="hero-content">
                <div class="hero-kicker">
                    <span>✎</span>
                    Edit Product #{{ $product->id }}
                </div>

                <h2>{{ $product->name }}</h2>

                <p>
                    Produk ini berada di kategori {{ optional($product->category)->name ?? '-' }},
                    harga {{ adminMoney($product->price ?? 0) }},
                    dan minimal VIP {{ (int)($product->min_vip_level ?? 0) }}.
                </p>

                <div class="hero-actions">
                    <a href="/admin/products" class="hero-link secondary">← Back Products</a>
                    <a href="/admin" class="hero-link">Dashboard →</a>
                </div>
            </div>
        </section>

        <section class="content-grid">
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-title">
                        <b>Form Edit Produk</b>
                        <span>Ubah detail produk investasi dengan hati-hati.</span>
                    </div>
                </div>

                <div class="panel-inner">
                    <form method="POST" action="/admin/products/{{ $product->id }}/update" class="form-grid">
                        @csrf

                        <div class="form-section">Informasi Produk</div>

                        <div class="field">
                            <label>Kategori</label>
                            <select name="category_id" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="hint">Pastikan kategori benar agar produk tampil di grup yang tepat.</div>
                        </div>

                        <div class="field">
                            <label>Nama Produk</label>
                            <input name="name" value="{{ $product->name }}" required>
                            <div class="hint">Gunakan nama yang konsisten dan mudah dipahami user.</div>
                        </div>

                        <div class="form-section">Pricing & Benefit</div>

                        <div class="field">
                            <label>Harga Produk</label>
                            <input name="price" type="number" inputmode="numeric" value="{{ $product->price }}" required>
                            <div class="hint">Nominal harga pembelian produk.</div>
                        </div>

                        <div class="field">
                            <label>Untung Harian</label>
                            <input name="daily_profit" type="number" inputmode="numeric" value="{{ $product->daily_profit }}" required>
                            <div class="hint">Keuntungan yang dihitung per hari.</div>
                        </div>

                        <div class="form-section">Kebijakan Produk</div>

                        <div class="field">
                            <label>Durasi (Hari)</label>
                            <input name="duration_days" type="number" inputmode="numeric" value="{{ $product->duration_days }}" required>
                            <div class="hint">Berapa hari produk aktif sebelum selesai.</div>
                        </div>

                        <div class="field">
                            <label>VIP Minimal</label>
                            <input name="min_vip_level" type="number" inputmode="numeric" value="{{ $product->min_vip_level }}" required>
                            <div class="hint">User minimal VIP level untuk dapat membeli produk ini.</div>
                        </div>

                        <div class="submit-wrap">
                            <button class="submit" type="submit">💾 Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

            <aside class="side-stack">
                <div class="panel">
                    <div class="panel-head">
                        <div class="panel-title">
                            <b>Ringkasan Produk</b>
                            <span>Preview data produk saat ini.</span>
                        </div>
                    </div>

                    <div class="panel-inner">
                        <div class="summary-grid">
                            <div class="summary-card">
                                <span>Status</span>
                                @if($product->is_active)
                                    <b><span class="badge success">Aktif</span></b>
                                @else
                                    <b><span class="badge danger">Nonaktif</span></b>
                                @endif
                            </div>

                            <div class="summary-card">
                                <span>Kategori</span>
                                <b>{{ optional($product->category)->name ?? '-' }}</b>
                            </div>

                            <div class="summary-card">
                                <span>Harga Produk</span>
                                <b>{{ adminMoney($product->price ?? 0) }}</b>
                            </div>

                            <div class="summary-card">
                                <span>Total Profit</span>
                                <b>{{ adminMoney($previewTotalProfit) }}</b>
                            </div>

                            <div class="summary-card">
                                <span>VIP Minimal</span>
                                <b><span class="badge vip">VIP {{ (int)($product->min_vip_level ?? 0) }}</span></b>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-head">
                        <div class="panel-title">
                            <b>Shortcuts</b>
                            <span>Akses cepat menu admin.</span>
                        </div>
                    </div>

                    <div class="panel-inner">
                        <div class="quick-list">
                            <a class="quick-card" href="/admin/products">
                                <div class="quick-left">
                                    <div class="quick-icon">📦</div>
                                    <div>
                                        <b>All Products</b>
                                        <span>Kembali ke daftar produk.</span>
                                    </div>
                                </div>
                                <div class="quick-arrow">→</div>
                            </a>

                            <a class="quick-card" href="/admin/products/create">
                                <div class="quick-left">
                                    <div class="quick-icon">＋</div>
                                    <div>
                                        <b>Tambah Produk</b>
                                        <span>Buat produk investasi baru.</span>
                                    </div>
                                </div>
                                <div class="quick-arrow">→</div>
                            </a>

                            <a class="quick-card" href="/admin/users">
                                <div class="quick-left">
                                    <div class="quick-icon">👥</div>
                                    <div>
                                        <b>Users</b>
                                        <span>Kelola user dan VIP.</span>
                                    </div>
                                </div>
                                <div class="quick-arrow">→</div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="warning-box">
                    Setelah perubahan disimpan, controller akan menghitung ulang <b>total_profit</b>
                    dari untung harian × durasi sesuai logic yang bro punya.
                </div>
            </aside>
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