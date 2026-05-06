@php
    $authUser = auth()->user();

    function adminMoney($value) {
        return 'Rp ' . number_format((float) $value, 0, ',', '.');
    }

    function adminInitial($name) {
        return strtoupper(substr($name ?: 'U', 0, 1));
    }

$totalUsers = count($users);
$totalAdmin = collect($users)->where('role', 'admin')->count();
$totalRegular = collect($users)->where('role', 'user')->count();

$totalSaldo = collect($users)->sum('saldo');
$totalSaldoPenarikan = collect($users)->sum('saldo_penarikan');
$totalSaldoHold = collect($users)->sum('saldo_hold');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />

    <title>Admin | Users</title>

    <style>
        :root {
            --bg: #f7f9fd;
            --bg-soft: #eef4ff;
            --card: #ffffff;
            --card-soft: #f9fbff;
            --text: #101828;
            --muted: #667085;
            --muted-2: #98a2b3;
            --line: #e7ebf3;

            --blue: #3157f8;
            --blue-2: #2348db;
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
            --radius-md: 16px;

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

        /* PAGE */
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

.stats {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
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

        .panel-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .btn {
            height: 40px;
            border-radius: 14px;
            border: 1px solid var(--line);
            background: #fff;
            padding: 0 14px;
            font-weight: 850;
            font-size: 12.5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: var(--shadow-soft);
            transition: .18s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: var(--blue);
            color: #fff;
            border-color: rgba(49, 87, 248, .18);
            box-shadow: 0 14px 24px rgba(49, 87, 248, .20);
        }

        .filters {
            display: grid;
            grid-template-columns: 1fr 220px 220px;
            gap: 12px;
            padding: 16px 18px 0;
        }

        .field {
            position: relative;
        }

        .field span {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted-2);
            font-size: 14px;
            pointer-events: none;
        }

        .field input,
        .field select {
            width: 100%;
            height: 44px;
            border-radius: 15px;
            border: 1px solid var(--line);
            background: #f9fbff;
            outline: none;
            padding: 0 14px;
            font-size: 13px;
            color: var(--text);
        }

        .field input {
            padding-left: 40px;
        }

        .field input:focus,
        .field select:focus {
            border-color: rgba(49, 87, 248, .35);
            box-shadow: 0 0 0 4px rgba(49, 87, 248, .08);
            background: #fff;
        }

        .meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 14px 18px 0;
            color: var(--muted);
            font-size: 12.5px;
        }

        .chips {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .chip {
            min-height: 30px;
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0 10px;
            background: var(--blue-soft);
            color: var(--blue);
            border: 1px solid rgba(49, 87, 248, .12);
            font-weight: 900;
            font-size: 12px;
        }

        .table-wrap {
            overflow: auto;
            padding: 16px 18px 18px;
        }

table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    min-width: 1120px;
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
        }

        tbody tr:hover td {
            background: #fbfdff;
        }

        .identity {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .identity-avatar {
            width: 38px;
            height: 38px;
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

        .money {
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

        .action-link {
            height: 34px;
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
        }

        .action-link:hover {
            transform: translateY(-1px);
            background: var(--blue);
            color: #fff;
        }

        .empty {
            padding: 34px 18px;
            text-align: center;
            color: var(--muted);
            font-size: 13px;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            padding: 0 18px 18px;
            color: var(--muted);
            font-size: 12.5px;
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
            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
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

            .hero {
                padding: 20px;
            }

            .hero h2 {
                font-size: 24px;
            }

            .hero p {
                font-size: 13px;
            }

            .filters {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 620px) {
            .stats {
                grid-template-columns: 1fr;
            }

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

            .panel-head {
                flex-direction: column;
                align-items: stretch;
            }

            .panel-actions {
                justify-content: flex-start;
            }

            table {
                min-width: 0;
            }

            .table-wrap {
                overflow: visible;
            }

            .table-wrap table,
            .table-wrap thead,
            .table-wrap tbody,
            .table-wrap th,
            .table-wrap td,
            .table-wrap tr {
                display: block;
            }

            .table-wrap thead {
                display: none;
            }

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
            }

            .table-wrap tbody td:last-child {
                border-bottom: 0;
            }

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

            .meta-row {
                align-items: flex-start;
                flex-direction: column;
            }

            .footer {
                align-items: flex-start;
                flex-direction: column;
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
                    <h1>Users</h1>
                    <p>Kelola seluruh pengguna, saldo, role, dan level VIP dari satu halaman.</p>
                </div>
            </div>

            <div class="top-center">
                <div class="global-search">
                    <span>⌕</span>
                    <input type="text" placeholder="Search user / phone / role..." oninput="syncMainSearch(this.value)">
                </div>
            </div>

            <div class="top-actions">
                <div class="status-pill">
                    <span class="live-dot"></span>
                    <span>System Online</span>
                </div>

                <a href="/admin/deposits" class="icon-btn" title="Deposits">💳</a>
                <a href="{{ route('admin.withdraw.page') }}" class="icon-btn" title="Withdraw">↗</a>

                <form action="/logout" method="POST" style="margin:0">
                    @csrf
                    <button class="logout-btn" type="submit">
                        <span>⎋</span>
                        Logout
                    </button>
                </form>
            </div>
        </header>

        {{-- HERO --}}
        <section class="hero">
            <div class="hero-content">
                <div class="hero-kicker">
                    <span>👥</span>
                    User Management
                </div>

                <h2>Panel user yang lebih rapi untuk kontrol saldo, VIP, dan akses.</h2>

                <p>
                    Gunakan pencarian cepat, filter role, dan filter VIP untuk menemukan user.
                    Klik detail untuk override VIP atau melakukan koreksi saldo manual.
                </p>
            </div>
        </section>

        {{-- STATS --}}
        <section class="stats">
            <div class="stat-card">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Total Users</div>
                        <div class="stat-value">{{ number_format($totalUsers) }}</div>
                    </div>
                    <div class="stat-icon">👥</div>
                </div>
                <div class="stat-note">Seluruh akun yang terdaftar</div>
            </div>

            <div class="stat-card green">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Regular Users</div>
                        <div class="stat-value">{{ number_format($totalRegular) }}</div>
                    </div>
                    <div class="stat-icon">🧑</div>
                </div>
                <div class="stat-note">Role user aktif di sistem</div>
            </div>

            <div class="stat-card yellow">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Admin Users</div>
                        <div class="stat-value">{{ number_format($totalAdmin) }}</div>
                    </div>
                    <div class="stat-icon">🛡️</div>
                </div>
                <div class="stat-note">Akun dengan akses admin</div>
            </div>

<div class="stat-card purple">
    <div class="stat-top">
        <div>
            <div class="stat-label">Total Saldo Utama</div>
            <div class="stat-value">{{ adminMoney($totalSaldo) }}</div>
        </div>
        <div class="stat-icon">💰</div>
    </div>
    <div class="stat-note">Saldo utama untuk deposit dan pembelian produk</div>
</div>

<div class="stat-card green">
    <div class="stat-top">
        <div>
            <div class="stat-label">Total Saldo Tarik</div>
            <div class="stat-value">{{ adminMoney($totalSaldoPenarikan) }}</div>
        </div>
        <div class="stat-icon">↘</div>
    </div>
    <div class="stat-note">Akumulasi saldo yang tersedia untuk withdraw</div>
</div>
        </section>

        {{-- USERS TABLE --}}
        <section class="panel">
            <div class="panel-head">
                <div class="panel-title">
                    <b>Users List</b>
                    <span>Daftar seluruh pengguna sistem. Gunakan filter untuk mempercepat manajemen.</span>
                </div>

                <div class="panel-actions">
                    <a href="/admin" class="btn">← Dashboard</a>
                    <button type="button" class="btn btn-primary" onclick="window.location.reload()">⟳ Refresh</button>
                </div>
            </div>

            <div class="filters">
                <div class="field">
                    <span>⌕</span>
                    <input id="q" type="text" placeholder="Cari nama / phone / role..." oninput="filterUsers()" />
                </div>

                <div class="field">
                    <select id="role" onchange="filterUsers()">
                        <option value="">Semua Role</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>

                <div class="field">
                    <select id="vip" onchange="filterUsers()">
                        <option value="">Semua VIP</option>
                        <option value="0">VIP 0</option>
                        <option value="1">VIP 1</option>
                        <option value="2">VIP 2</option>
                        <option value="3">VIP 3</option>
                        <option value="4">VIP 4</option>
                        <option value="5">VIP 5</option>
                        <option value="6">VIP 6</option>
                        <option value="7">VIP 7</option>
                        <option value="8">VIP 8</option>
                        <option value="9">VIP 9</option>
                        <option value="10">VIP 10</option>
                    </select>
                </div>
            </div>

            <div class="meta-row">
                <div class="chips">
                    <span class="chip" id="countChip">Total: {{ $totalUsers }}</span>
                    <span class="chip">Admin: {{ $totalAdmin }}</span>
                    <span class="chip">User: {{ $totalRegular }}</span>
                </div>

                <div id="shownInfo">Menampilkan: {{ $totalUsers }} user</div>
            </div>

            <div class="table-wrap">
                <table id="usersTable">
                    <thead>
<tr>
    <th>Nama</th>
    <th>Phone</th>
    <th>Saldo Utama</th>
    <th>Saldo Tarik</th>
    <th>Saldo Hold</th>
    <th>VIP</th>
    <th>Role</th>
    <th style="width:140px">Action</th>
</tr>
                    </thead>

                    <tbody id="usersBody">
                    @forelse($users as $u)
                        <tr
                            data-name="{{ strtolower($u->name ?? '') }}"
                            data-phone="{{ strtolower($u->phone ?? '') }}"
                            data-role="{{ strtolower($u->role ?? '') }}"
                            data-vip="{{ (int)($u->vip_level ?? 0) }}"
                        >
                            <td data-label="Nama">
                                <div class="identity">
                                    <div class="identity-avatar">
                                        {{ adminInitial($u->name ?? 'U') }}
                                    </div>
                                    <div>
                                        <b>{{ $u->name ?? '-' }}</b>
                                        <span>ID: #{{ $u->id }}</span>
                                    </div>
                                </div>
                            </td>

                            <td data-label="Phone">
                                {{ $u->phone ?: '-' }}
                            </td>

               <td data-label="Saldo Utama">
    <span class="money">{{ adminMoney($u->saldo ?? 0) }}</span>
</td>

<td data-label="Saldo Tarik">
    <span class="money" style="color:#12b76a">
        {{ adminMoney($u->saldo_penarikan ?? 0) }}
    </span>
</td>

<td data-label="Saldo Hold">
    <span class="money" style="color:#f79009">
        {{ adminMoney($u->saldo_hold ?? 0) }}
    </span>
</td>

<td data-label="VIP">
    <span class="badge vip">VIP {{ (int)($u->vip_level ?? 0) }}</span>
</td>

                            <td data-label="Role">
                                <span class="badge {{ $u->role === 'admin' ? 'admin' : 'user' }}">
                                    {{ strtoupper($u->role ?? 'USER') }}
                                </span>
                            </td>

                            <td data-label="Action">
                                <a href="{{ url('/admin/users/'.$u->id) }}" class="action-link">Detail →</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                       <td colspan="8">
    <div class="empty">Belum ada data user.</div>
</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="footer">
             <div>Tips: klik <b>Detail</b> untuk override VIP, saldo utama, dan saldo penarikan user.</div>
                <div>Last updated: {{ now()->format('d M Y H:i') }}</div>
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

    function syncMainSearch(value) {
        const q = document.getElementById('q');
        if (q) {
            q.value = value;
            filterUsers();
        }
    }

    function filterUsers() {
        const q = (document.getElementById('q').value || '').toLowerCase().trim();
        const role = (document.getElementById('role').value || '').toLowerCase().trim();
        const vip = (document.getElementById('vip').value || '').trim();

        const rows = document.querySelectorAll('#usersBody tr[data-name]');
        let shown = 0;

        rows.forEach((tr) => {
            const name = tr.getAttribute('data-name') || '';
            const phone = tr.getAttribute('data-phone') || '';
            const r = tr.getAttribute('data-role') || '';
            const v = tr.getAttribute('data-vip') || '';

            const matchQ = !q || name.includes(q) || phone.includes(q) || r.includes(q);
            const matchRole = !role || r === role;
            const matchVip = !vip || v === vip;

            const ok = matchQ && matchRole && matchVip;

            tr.style.display = ok ? '' : 'none';

            if (ok) shown++;
        });

        const shownInfo = document.getElementById('shownInfo');
        const countChip = document.getElementById('countChip');

        if (shownInfo) shownInfo.textContent = `Menampilkan: ${shown} user`;
        if (countChip) countChip.textContent = `Total: ${shown}`;
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