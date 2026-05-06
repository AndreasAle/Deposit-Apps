@php
    $authUser = auth()->user();
    $tab = $tab ?? request('tab', 'overview');

    function adminMoney($value) {
        return 'Rp ' . number_format((float) $value, 0, ',', '.');
    }

    function adminInitial($name) {
        return strtoupper(substr($name ?: 'A', 0, 1));
    }

    function maskPhone($phone) {
        $phone = (string) $phone;
        if (strlen($phone) <= 6) return $phone ?: '-';
        return substr($phone, 0, 4) . '****' . substr($phone, -3);
    }

    function sourceBadgeClass($source) {
        return strtolower((string) $source) === 'deposit' ? 'deposit' : 'buy';
    }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />

    <title>Admin | Referral</title>

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
            max-width: 780px;
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

        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }

        .stat-card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            padding: 17px;
            box-shadow: var(--shadow-soft);
            min-height: 126px;
        }

        .stat-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }

        .stat-icon {
            width: 43px;
            height: 43px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            font-size: 18px;
            background: var(--blue-soft);
            color: var(--blue);
        }

        .stat-card.green .stat-icon {
            background: var(--green-soft);
            color: var(--green);
        }

        .stat-card.yellow .stat-icon {
            background: var(--yellow-soft);
            color: var(--yellow);
        }

        .stat-card.purple .stat-icon {
            background: var(--purple-soft);
            color: var(--purple);
        }

        .stat-label {
            color: var(--muted);
            font-size: 12.5px;
            font-weight: 750;
        }

        .stat-value {
            margin-top: 13px;
            font-size: 24px;
            line-height: 1;
            font-weight: 950;
            letter-spacing: -.045em;
        }

        .stat-note {
            margin-top: 10px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
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

        .tabs {
            display: flex;
            gap: 4px;
            border-bottom: 1px solid var(--line);
            padding: 0 18px;
            margin-top: 16px;
            overflow: auto;
        }

        .tab {
            padding: 14px 14px;
            border-bottom: 2px solid transparent;
            font-size: 13px;
            font-weight: 850;
            color: var(--muted);
            white-space: nowrap;
        }

        .tab.active {
            color: var(--blue);
            border-bottom-color: var(--blue);
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1.45fr;
            gap: 18px;
            align-items: start;
        }

        .soft-card {
            border: 1px solid var(--line);
            border-radius: 22px;
            padding: 16px;
            background: #fff;
            box-shadow: var(--shadow-soft);
        }

        .soft-card b {
            display: block;
            font-size: 14px;
        }

        .soft-card p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 12.5px;
            line-height: 1.5;
        }

        .breakdown-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 0;
            border-top: 1px solid var(--line);
            font-size: 13px;
        }

        .breakdown-row:first-of-type {
            border-top: 0;
        }

        .table-wrap {
            overflow: auto;
            padding: 16px 18px 18px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 900px;
        }

        thead th {
            background: #f8faff;
            color: var(--muted);
            font-size: 11.5px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .03em;
            text-align: left;
            padding: 13px 14px;
            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
        }

        thead th:first-child {
            border-left: 1px solid var(--line);
            border-radius: 15px 0 0 15px;
        }

        thead th:last-child {
            border-right: 1px solid var(--line);
            border-radius: 0 15px 15px 0;
        }

        tbody td {
            padding: 15px 14px;
            border-bottom: 1px solid var(--line);
            font-size: 13px;
            vertical-align: middle;
            white-space: nowrap;
        }

        tbody tr:hover td {
            background: #fbfdff;
        }

        .money {
            font-weight: 950;
            letter-spacing: -.02em;
        }

        .muted {
            color: var(--muted);
        }

        .identity {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .identity-avatar {
            width: 36px;
            height: 36px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: var(--blue-soft);
            color: var(--blue);
            font-size: 12px;
            font-weight: 950;
            flex: 0 0 auto;
        }

        .identity b {
            display: block;
            font-size: 13px;
            line-height: 1.15;
        }

        .identity span {
            display: block;
            margin-top: 3px;
            font-size: 12px;
            color: var(--muted);
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

        .badge.deposit {
            color: #027a48;
            background: var(--green-soft);
            border-color: rgba(18, 183, 106, .16);
        }

        .badge.buy {
            color: #6941c6;
            background: var(--purple-soft);
            border-color: rgba(122, 90, 248, .16);
        }

        .action-link,
        .btn {
            height: 36px;
            padding: 0 12px;
            border-radius: 13px;
            background: var(--blue-soft);
            color: var(--blue);
            border: 1px solid rgba(49, 87, 248, .14);
            font-weight: 900;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: .18s ease;
            cursor: pointer;
        }

        .action-link:hover,
        .btn:hover {
            transform: translateY(-1px);
            background: var(--blue);
            color: #fff;
        }

        .filters {
            display: grid;
            grid-template-columns: 170px repeat(4, minmax(140px, 1fr)) auto auto;
            gap: 10px;
            padding: 16px 18px 0;
        }

        .filters input,
        .filters select {
            width: 100%;
            height: 42px;
            border-radius: 14px;
            border: 1px solid var(--line);
            background: #f9fbff;
            outline: none;
            padding: 0 12px;
            color: var(--text);
            font-size: 13px;
        }

        .filters input:focus,
        .filters select:focus {
            border-color: rgba(49, 87, 248, .35);
            box-shadow: 0 0 0 4px rgba(49, 87, 248, .08);
            background: #fff;
        }

        .pagination-wrap {
            padding: 0 18px 18px;
        }

        .empty {
            padding: 34px 18px;
            text-align: center;
            color: var(--muted);
            font-size: 13px;
        }

        .detail-section {
            margin-top: 18px;
            border-top: 1px solid var(--line);
            padding-top: 18px;
        }

        .detail-head {
            margin-bottom: 14px;
        }

        .detail-head h3 {
            margin: 0;
            font-size: 18px;
            letter-spacing: -.03em;
        }

        .detail-head p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
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
            .stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .content-grid { grid-template-columns: 1fr; }
            .filters { grid-template-columns: repeat(2, minmax(0, 1fr)); }
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
            .stats { grid-template-columns: 1fr; }

            .top-actions .icon-btn { display: none; }

            .logout-btn {
                width: 42px;
                padding: 0;
                font-size: 0;
            }

            .logout-btn span { font-size: 17px; }

            .filters { grid-template-columns: 1fr; }

            .table-wrap {
                overflow: visible;
            }

            table { min-width: 0; }

            .table-wrap table,
            .table-wrap thead,
            .table-wrap tbody,
            .table-wrap th,
            .table-wrap td,
            .table-wrap tr {
                display: block;
            }

            .table-wrap thead { display: none; }

            .table-wrap tbody tr {
                border: 1px solid var(--line);
                border-radius: 18px;
                margin-bottom: 12px;
                overflow: hidden;
                box-shadow: var(--shadow-soft);
            }

            .table-wrap tbody td {
                display: flex;
                justify-content: space-between;
                gap: 14px;
                padding: 11px 12px;
                border-bottom: 1px solid var(--line);
                background: #fff;
                white-space: normal;
            }

            .table-wrap tbody td:last-child { border-bottom: 0; }

            .table-wrap tbody td::before {
                content: attr(data-label);
                color: var(--muted);
                font-weight: 900;
                font-size: 11px;
                flex: 0 0 86px;
            }

            .identity {
                justify-content: flex-end;
                text-align: right;
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

                <a class="nav-item active" href="{{ route('admin.referral') }}" data-label="referral komisi invite users">
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
                    <h1>Referral</h1>
                    <p>Kelola performa referral, user yang direferensikan, dan audit komisi.</p>
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

        <section class="hero">
            <div class="hero-content">
                <div class="hero-kicker">
                    <span>🎁</span>
                    Referral Management
                </div>

                <h2>Audit referral lebih rapi, mulai dari performa sampai komisi.</h2>

                <p>
                    Pantau total user, user yang berhasil direferensikan, sumber komisi deposit/buy,
                    top referrer, dan detail transaksi komisi dari satu halaman admin.
                </p>
            </div>
        </section>

        <section class="stats">
            <div class="stat-card">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Total Users</div>
                        <div class="stat-value">{{ number_format($totalUsers ?? 0) }}</div>
                    </div>
                    <div class="stat-icon">👥</div>
                </div>
                <div class="stat-note">Role user di sistem</div>
            </div>

            <div class="stat-card green">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Referred Users</div>
                        <div class="stat-value">{{ number_format($totalReferredUsers ?? 0) }}</div>
                    </div>
                    <div class="stat-icon">🔗</div>
                </div>
                <div class="stat-note">User punya referrer</div>
            </div>

            <div class="stat-card yellow">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Total Komisi</div>
                        <div class="stat-value">{{ adminMoney($totalCommission ?? 0) }}</div>
                    </div>
                    <div class="stat-icon">💰</div>
                </div>
                <div class="stat-note">Lifetime referral commission</div>
            </div>

            <div class="stat-card purple">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Komisi 7 Hari</div>
                        <div class="stat-value">{{ adminMoney($commission7d ?? 0) }}</div>
                    </div>
                    <div class="stat-icon">📈</div>
                </div>
                <div class="stat-note">Hari ini {{ adminMoney($commissionToday ?? 0) }}</div>
            </div>
        </section>

        <section class="panel">
            <div class="panel-head">
                <div class="panel-title">
                    <b>Referral Console</b>
                    <span>Overview, users, dan commission history.</span>
                </div>
            </div>

            <div class="tabs">
                <a class="tab {{ $tab === 'overview' ? 'active' : '' }}" href="{{ route('admin.referral', ['tab' => 'overview']) }}">
                    Overview
                </a>

                <a class="tab {{ $tab === 'users' ? 'active' : '' }}" href="{{ route('admin.referral', ['tab' => 'users']) }}">
                    Users
                </a>

                <a class="tab {{ $tab === 'commissions' ? 'active' : '' }}" href="{{ route('admin.referral', ['tab' => 'commissions']) }}">
                    Commissions
                </a>
            </div>

            @if($tab === 'overview')
                <div class="panel-inner">
                    <div class="content-grid">
                        <div class="soft-card">
                            <b>Breakdown Sumber Komisi</b>
                            <p>Ringkasan sumber komisi dari deposit dan pembelian produk.</p>

                            <div style="height:12px"></div>

                            <div class="breakdown-row">
                                <span>Deposit</span>
                                <strong>{{ adminMoney($breakdown['deposit'] ?? 0) }}</strong>
                            </div>

                            <div class="breakdown-row">
                                <span>Buy</span>
                                <strong>{{ adminMoney($breakdown['buy'] ?? 0) }}</strong>
                            </div>

                            <div class="breakdown-row">
                                <span>Hari ini</span>
                                <strong>{{ adminMoney($commissionToday ?? 0) }}</strong>
                            </div>
                        </div>

                        <div class="soft-card">
                            <b>Catatan Referral</b>
                            <p>
                                Sistem referral saat ini menampilkan komisi berdasarkan source type.
                                Gunakan tab Commissions untuk audit detail transaksi dan tab Users untuk melihat performa per referrer.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="panel-head">
                    <div class="panel-title">
                        <b>Top Referrers</b>
                        <span>10 terbesar berdasarkan total komisi.</span>
                    </div>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Referrer</th>
                            <th>Phone</th>
                            <th>Total Trx</th>
                            <th>Total Komisi</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($topReferrers as $i => $r)
                            <tr>
                                <td data-label="#">
                                    {{ $i + 1 }}
                                </td>

                                <td data-label="Referrer">
                                    <div class="identity">
                                        <div class="identity-avatar">{{ adminInitial($r->referrer_name ?? 'U') }}</div>
                                        <div>
                                            <b>{{ $r->referrer_name ?? '-' }}</b>
                                            <span>ID: #{{ $r->referrer_id }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td data-label="Phone">{{ maskPhone($r->referrer_phone ?? '-') }}</td>

                                <td data-label="Total Trx">{{ number_format($r->total_trx ?? 0) }}</td>

                                <td data-label="Total Komisi">
                                    <span class="money">{{ adminMoney($r->total_commission ?? 0) }}</span>
                                </td>

                                <td data-label="Aksi">
                                    <a class="action-link" href="{{ route('admin.referral', ['tab' => 'users', 'referrer_id' => $r->referrer_id]) }}#detail">
                                        Detail →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty">Belum ada data komisi.</div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

            @if($tab === 'users')
                <div class="panel-head">
                    <div class="panel-title">
                        <b>Referrers List</b>
                        <span>Daftar referrer dan jumlah user yang mereka ajak.</span>
                    </div>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>User</th>
                            <th>Phone</th>
                            <th>Kode</th>
                            <th>Jumlah Referral</th>
                            <th>Total Earned</th>
                            <th>Join</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($referrers as $u)
                            <tr>
                                <td data-label="User">
                                    <div class="identity">
                                        <div class="identity-avatar">{{ adminInitial($u->name ?? 'U') }}</div>
                                        <div>
                                            <b>{{ $u->name ?? '-' }}</b>
                                            <span>ID: #{{ $u->id }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td data-label="Phone">{{ maskPhone($u->phone ?? '-') }}</td>

                                <td data-label="Kode">
                                    <span class="badge buy">{{ $u->referral_code ?? '-' }}</span>
                                </td>

                                <td data-label="Jumlah Referral">{{ number_format($u->referrals_count ?? 0) }}</td>

                                <td data-label="Total Earned">
                                    <span class="money">{{ adminMoney($u->referral_earned_total ?? 0) }}</span>
                                </td>

                                <td data-label="Join">
                                    {{ \Carbon\Carbon::parse($u->created_at)->format('d M Y') }}
                                </td>

                                <td data-label="Aksi">
                                    <a class="action-link" href="{{ route('admin.referral', ['tab' => 'users', 'referrer_id' => $u->id]) }}#detail">
                                        Detail →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty">Belum ada data referrer.</div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrap">
                    {{ $referrers->links() }}
                </div>

                @if($referrerDetail)
                    <div id="detail" class="panel-inner detail-section">
                        <div class="detail-head">
                            <h3>Detail Referrer: {{ $referrerDetail->name }}</h3>
                            <p>
                                Phone: {{ maskPhone($referrerDetail->phone) }} ·
                                Kode: <b>{{ $referrerDetail->referral_code ?? '-' }}</b> ·
                                Earned: <b>{{ adminMoney($referrerDetail->referral_earned_total ?? 0) }}</b>
                            </p>
                        </div>

                        <div class="content-grid">
                            <div class="soft-card">
                                <b>Referred Users</b>
                                <p>Max 100 user terbaru yang daftar memakai kode ini.</p>

                                <div class="table-wrap" style="padding:14px 0 0">
                                    <table style="min-width:520px">
                                        <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Phone</th>
                                            <th>Daftar</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @forelse($referredUsers as $ru)
                                            <tr>
                                                <td data-label="Nama">{{ $ru->name }}</td>
                                                <td data-label="Phone">{{ maskPhone($ru->phone) }}</td>
                                                <td data-label="Daftar">{{ \Carbon\Carbon::parse($ru->created_at)->format('d M Y H:i') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">
                                                    <div class="empty">Belum ada user yang daftar pakai kode ini.</div>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="soft-card">
                                <b>Komisi Terbaru</b>
                                <p>Max 50 komisi terbaru dari referrer ini.</p>

                                <div class="table-wrap" style="padding:14px 0 0">
                                    <table style="min-width:720px">
                                        <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Sumber</th>
                                            <th>Referred</th>
                                            <th>Base</th>
                                            <th>Rate</th>
                                            <th>Komisi</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @forelse($referrerCommissions as $c)
                                            <tr>
                                                <td data-label="Tanggal">{{ \Carbon\Carbon::parse($c->created_at)->format('d M Y H:i') }}</td>
                                                <td data-label="Sumber">
                                                    <span class="badge {{ sourceBadgeClass($c->source_type) }}">{{ strtoupper($c->source_type) }}</span>
                                                </td>
                                                <td data-label="Referred">{{ $c->referred_name ?? '-' }} ({{ maskPhone($c->referred_phone ?? '-') }})</td>
                                                <td data-label="Base">{{ adminMoney($c->base_amount) }}</td>
                                                <td data-label="Rate">{{ (float) $c->rate * 100 }}%</td>
                                                <td data-label="Komisi"><span class="money">{{ adminMoney($c->commission_amount) }}</span></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">
                                                    <div class="empty">Belum ada komisi masuk.</div>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div style="margin-top:12px">
                                    <a class="action-link" href="{{ route('admin.referral', ['tab' => 'commissions', 'referrer_id' => $referrerDetail->id]) }}">
                                        Buka di tab Commissions →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            @if($tab === 'commissions')
                <div class="panel-head">
                    <div class="panel-title">
                        <b>Commissions History</b>
                        <span>Audit transaksi komisi referral dengan filter.</span>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.referral') }}">
                    <input type="hidden" name="tab" value="commissions">

                    <div class="filters">
                        <select name="source_type">
                            <option value="">All source</option>
                            <option value="deposit" {{ request('source_type') === 'deposit' ? 'selected' : '' }}>deposit</option>
                            <option value="buy" {{ request('source_type') === 'buy' ? 'selected' : '' }}>buy</option>
                        </select>

                        <input type="number" name="referrer_id" placeholder="referrer_id" value="{{ request('referrer_id') }}">
                        <input type="number" name="referred_user_id" placeholder="referred_user_id" value="{{ request('referred_user_id') }}">
                        <input type="date" name="date_from" value="{{ request('date_from') }}">
                        <input type="date" name="date_to" value="{{ request('date_to') }}">

                        <button class="btn" type="submit">Filter</button>
                        <a class="btn" href="{{ route('admin.referral', ['tab' => 'commissions']) }}">Reset</a>
                    </div>
                </form>

                <div class="table-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Referrer</th>
                            <th>Referred</th>
                            <th>Sumber</th>
                            <th>Source ID</th>
                            <th>Base</th>
                            <th>Rate</th>
                            <th>Komisi</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($commissions as $c)
                            <tr>
                                <td data-label="Tanggal">{{ \Carbon\Carbon::parse($c->created_at)->format('d M Y H:i') }}</td>

                                <td data-label="Referrer">
                                    {{ $c->referrer_name ?? '-' }} ({{ maskPhone($c->referrer_phone ?? '-') }})
                                </td>

                                <td data-label="Referred">
                                    {{ $c->referred_name ?? '-' }} ({{ maskPhone($c->referred_phone ?? '-') }})
                                </td>

                                <td data-label="Sumber">
                                    <span class="badge {{ sourceBadgeClass($c->source_type) }}">{{ strtoupper($c->source_type) }}</span>
                                </td>

                                <td data-label="Source ID">#{{ $c->source_id }}</td>

                                <td data-label="Base">{{ adminMoney($c->base_amount) }}</td>

                                <td data-label="Rate">{{ (float) $c->rate * 100 }}%</td>

                                <td data-label="Komisi">
                                    <span class="money">{{ adminMoney($c->commission_amount) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty">Belum ada data komisi.</div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrap">
                    {{ $commissions->links() }}
                </div>
            @endif
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