@php
    $user = auth()->user();

    $currentPath = request()->path();

    function adminMoney($value) {
        return 'Rp ' . number_format((float) $value, 0, ',', '.');
    }

    function adminInitial($name) {
        return strtoupper(substr($name ?: 'A', 0, 1));
    }

    function adminStatusClass($status) {
        $status = strtoupper((string) $status);

        return match ($status) {
            'PAID', 'SUCCESS', 'APPROVED', 'OK' => 'is-success',
            'UNPAID', 'PENDING', 'PROCESS', 'WAITING' => 'is-warning',
            'REJECTED', 'FAILED', 'CANCELLED', 'CANCELED' => 'is-danger',
            default => 'is-muted',
        };
    }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />

    <title>Admin Panel | RUBIK</title>

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
            --radius-sm: 12px;

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
            background:
                linear-gradient(135deg, #3157f8 0%, #5a7cff 52%, #12b76a 100%);
            color: #fff;
            font-weight: 950;
            letter-spacing: -.04em;
            box-shadow: 0 14px 28px rgba(49, 87, 248, .24);
        }

        .brand-name {
            min-width: 0;
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

        .nav-text {
            min-width: 0;
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

        .page-title {
            min-width: 0;
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

        /* DASHBOARD HERO */
        .hero {
            display: grid;
            grid-template-columns: 1.45fr .75fr;
            gap: 18px;
            margin-bottom: 18px;
        }

        .hero-card {
            position: relative;
            overflow: hidden;
            border-radius: var(--radius-xl);
            background:
                radial-gradient(420px 240px at 88% 8%, rgba(255, 255, 255, .18), transparent 55%),
                linear-gradient(135deg, #3157f8 0%, #233fd1 58%, #172554 100%);
            color: #fff;
            padding: 24px;
            box-shadow: 0 24px 46px rgba(49, 87, 248, .22);
            min-height: 188px;
        }

        .hero-card::after {
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
            max-width: 620px;
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

        .mini-summary {
            border-radius: var(--radius-xl);
            background: #fff;
            border: 1px solid var(--line);
            padding: 18px;
            box-shadow: var(--shadow);
        }

        .mini-summary-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .mini-summary-head b {
            font-size: 14px;
        }

        .mini-summary-head span {
            color: var(--muted);
            font-size: 12px;
        }

        .summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 12px 0;
            border-top: 1px solid var(--line);
        }

        .summary-row:first-of-type {
            border-top: 0;
        }

        .summary-left {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .summary-icon {
            width: 38px;
            height: 38px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: var(--blue-soft);
            color: var(--blue);
        }

        .summary-left b {
            display: block;
            font-size: 13px;
        }

        .summary-left span {
            display: block;
            margin-top: 3px;
            font-size: 12px;
            color: var(--muted);
        }

        .summary-value {
            font-size: 14px;
            font-weight: 900;
            white-space: nowrap;
        }

        /* STATS */
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
            min-height: 132px;
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
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
        }

        .up {
            color: var(--green);
        }

        /* CONTENT GRID */
        .content-grid {
            display: grid;
            grid-template-columns: 1.15fr .85fr;
            gap: 18px;
        }

        .panel {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .panel-inner {
            padding: 18px;
        }

        .panel-head {
            display: flex;
            align-items: center;
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

        .panel-action {
            height: 38px;
            padding: 0 13px;
            border-radius: 13px;
            background: var(--blue);
            color: #fff;
            font-weight: 850;
            font-size: 12.5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 14px 24px rgba(49, 87, 248, .20);
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

        .table-tools {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 18px 0;
        }

        .table-search {
            position: relative;
            flex: 1;
            max-width: 380px;
        }

        .table-search span {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted-2);
        }

        .table-search input {
            width: 100%;
            height: 42px;
            border-radius: 15px;
            border: 1px solid var(--line);
            background: #f9fbff;
            outline: none;
            padding: 0 14px 0 40px;
            font-size: 13px;
        }

        .filter-btn {
            height: 42px;
            border-radius: 15px;
            border: 1px solid var(--line);
            background: #fff;
            color: var(--text);
            padding: 0 14px;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
        }

        .table-wrap {
            overflow: auto;
            padding: 16px 18px 18px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 780px;
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
            width: 34px;
            height: 34px;
            border-radius: 13px;
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

        .status {
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

        .status.is-success {
            color: #027a48;
            background: var(--green-soft);
            border-color: rgba(18, 183, 106, .16);
        }

        .status.is-warning {
            color: #b54708;
            background: var(--yellow-soft);
            border-color: rgba(247, 144, 9, .18);
        }

        .status.is-danger {
            color: #b42318;
            background: var(--red-soft);
            border-color: rgba(240, 68, 56, .18);
        }

        .status.is-muted {
            color: #475467;
            background: #f2f4f7;
            border-color: #eaecf0;
        }

        .money {
            font-weight: 950;
            letter-spacing: -.02em;
        }

        .muted {
            color: var(--muted);
        }

        .empty {
            padding: 34px 18px;
            text-align: center;
            color: var(--muted);
            font-size: 13px;
        }

        /* QUICK ACTIONS RIGHT */
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

        .notes {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.6;
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

        /* RESPONSIVE */
        @media (max-width: 1180px) {
            .hero,
            .content-grid {
                grid-template-columns: 1fr;
            }

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

            .hero-card {
                min-height: auto;
                padding: 20px;
            }

            .hero h2 {
                font-size: 24px;
            }

            .hero p {
                font-size: 13px;
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

            .hero-actions {
                flex-direction: column;
            }

            .hero-link {
                width: 100%;
            }

            .table-tools {
                flex-direction: column;
                align-items: stretch;
            }

            .table-search {
                max-width: none;
            }

            .filter-btn {
                width: 100%;
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
                flex: 0 0 90px;
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

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-logo">RB</div>
            <div class="brand-name">
                <strong>RUBIK</strong>
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
                <div class="avatar">{{ adminInitial($user->name ?? 'Admin') }}</div>
                <div>
                    <b>{{ $user->name ?? 'Admin' }}</b>
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
                    <h1>Rubik Dashboard</h1>
                    <p>Selamat datang kembali, <b>{{ $user->name ?? 'Admin' }}</b>. Pantau transaksi, user, dan operasional dari satu panel.</p>
                </div>
            </div>

            <div class="top-center">
                <div class="global-search">
                    <span>⌕</span>
                    <input type="text" placeholder="Search for anything here..." oninput="filterDashboardTables(this.value)">
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
            <div class="hero-card">
                <div class="hero-content">
                    <div class="hero-kicker">
                        <span>✦</span>
                        Admin Workspace
                    </div>

                    <h2>Kelola RUBIK lebih cepat, rapi, dan profesional.</h2>

                    <p>
                        Dashboard ini disiapkan untuk monitoring deposit, withdraw, user, produk,
                        referral, dan aktivitas operasional dengan tampilan yang lebih clean seperti sistem admin modern.
                    </p>

                    <div class="hero-actions">
                        <a href="/admin/deposits" class="hero-link">Open Deposits →</a>
                        <a href="{{ route('admin.withdraw.page') }}" class="hero-link secondary">Review Withdraw →</a>
                    </div>
                </div>
            </div>

            <div class="mini-summary">
                <div class="mini-summary-head">
                    <b>Operational Snapshot</b>
                    <span>{{ now()->format('d M Y') }}</span>
                </div>

                <div class="summary-row">
                    <div class="summary-left">
                        <div class="summary-icon">👥</div>
                        <div>
                            <b>New Users</b>
                            <span>This month</span>
                        </div>
                    </div>
                    <div class="summary-value">{{ number_format($stats['new_users_month'] ?? 0) }}</div>
                </div>

                <div class="summary-row">
                    <div class="summary-left">
                        <div class="summary-icon">💳</div>
                        <div>
                            <b>Unpaid Deposit</b>
                            <span>Need review</span>
                        </div>
                    </div>
                    <div class="summary-value">{{ number_format($stats['deposit_unpaid'] ?? 0) }}</div>
                </div>

                <div class="summary-row">
                    <div class="summary-left">
                        <div class="summary-icon">↗</div>
                        <div>
                            <b>Withdraw Queue</b>
                            <span>Pending request</span>
                        </div>
                    </div>
                    <div class="summary-value">{{ number_format($stats['withdraw_pending'] ?? 0) }}</div>
                </div>
            </div>
        </section>

        {{-- STATS --}}
        <section class="stats">
            <div class="stat-card">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Total Users</div>
                        <div class="stat-value">{{ number_format($stats['total_users'] ?? 0) }}</div>
                    </div>
                    <div class="stat-icon">👥</div>
                </div>
                <div class="stat-note">
                    <span class="up">↗</span>
                    {{ number_format($stats['new_users_month'] ?? 0) }} user baru bulan ini
                </div>
            </div>

            <div class="stat-card green">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Paid Deposits</div>
                        <div class="stat-value">{{ adminMoney($stats['deposit_paid'] ?? 0) }}</div>
                    </div>
                    <div class="stat-icon">💳</div>
                </div>
                <div class="stat-note">
                    <span class="up">●</span>
                    {{ adminMoney($stats['deposit_today'] ?? 0) }} deposit hari ini
                </div>
            </div>

            <div class="stat-card yellow">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Products</div>
                        <div class="stat-value">{{ number_format($stats['active_products'] ?? 0) }}</div>
                    </div>
                    <div class="stat-icon">📦</div>
                </div>
                <div class="stat-note">
                    <span>●</span>
                    Dari total {{ number_format($stats['total_products'] ?? 0) }} produk
                </div>
            </div>

            <div class="stat-card purple">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Withdraw Queue</div>
                        <div class="stat-value">{{ number_format($stats['withdraw_pending'] ?? 0) }}</div>
                    </div>
                    <div class="stat-icon">↗</div>
                </div>
                <div class="stat-note">
                    <span>●</span>
                    {{ number_format($stats['withdraw_paid'] ?? 0) }} sudah dibayar
                </div>
            </div>
        </section>

        {{-- CONTENT --}}
        <section class="content-grid">
            {{-- LEFT TABLE --}}
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-title">
                        <b>Latest Deposits</b>
                        <span>Riwayat deposit terbaru dari seluruh user.</span>
                    </div>

                    <a href="/admin/deposits" class="panel-action">View All</a>
                </div>

                <div class="tabs">
                    <a class="tab active" href="/admin/deposits">All Deposits</a>
                    <a class="tab" href="/admin/deposits?status=UNPAID">Unpaid</a>
                    <a class="tab" href="/admin/deposits?status=PAID">Paid</a>
                </div>

                <div class="table-tools">
                    <div class="table-search">
                        <span>⌕</span>
                        <input type="text" placeholder="Search order / user..." data-table-search="depositTable">
                    </div>

                    <button class="filter-btn" type="button">Export</button>
                </div>

                <div class="table-wrap">
                    <table id="depositTable">
                        <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse(($latestDeposits ?? collect()) as $deposit)
                            <tr>
                                <td data-label="Order ID">
                                    <b>{{ $deposit->order_id }}</b>
                                </td>

                                <td data-label="User">
                                    <div class="identity">
                                        <div class="identity-avatar">
                                            {{ adminInitial($deposit->user->name ?? 'U') }}
                                        </div>
                                        <div>
                                            <b>{{ $deposit->user->name ?? 'Unknown User' }}</b>
                                            <span>{{ $deposit->user->phone ?? 'No phone' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td data-label="Amount">
                                    <span class="money">{{ adminMoney($deposit->amount) }}</span>
                                </td>

                                <td data-label="Method">
                                    <span class="muted">{{ $deposit->method ?? '-' }}</span>
                                </td>

                                <td data-label="Status">
                                    <span class="status {{ adminStatusClass($deposit->status) }}">
                                        {{ $deposit->status }}
                                    </span>
                                </td>

                                <td data-label="Date">
                                    <span class="muted">{{ optional($deposit->created_at)->format('d M Y H:i') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty">Belum ada data deposit.</div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- RIGHT --}}
            <div style="display:grid; gap:18px;">
                <div class="panel">
                    <div class="panel-head">
                        <div class="panel-title">
                            <b>Quick Actions</b>
                            <span>Akses cepat menu penting admin.</span>
                        </div>
                    </div>

                    <div class="panel-inner">
                        <div class="quick-list">
                            <a class="quick-card" href="/admin/users">
                                <div class="quick-left">
                                    <div class="quick-icon">👥</div>
                                    <div>
                                        <b>Manage Users</b>
                                        <span>Update saldo, VIP, dan detail user.</span>
                                    </div>
                                </div>
                                <div class="quick-arrow">→</div>
                            </a>

                            <a class="quick-card" href="/admin/deposits">
                                <div class="quick-left">
                                    <div class="quick-icon">💳</div>
                                    <div>
                                        <b>Review Deposits</b>
                                        <span>Cek pembayaran manual dan status deposit.</span>
                                    </div>
                                </div>
                                <div class="quick-arrow">→</div>
                            </a>

                            <a class="quick-card" href="{{ route('admin.withdraw.page') }}">
                                <div class="quick-left">
                                    <div class="quick-icon">↗</div>
                                    <div>
                                        <b>Withdraw Queue</b>
                                        <span>Proses permintaan penarikan user.</span>
                                    </div>
                                </div>
                                <div class="quick-arrow">→</div>
                            </a>

                            <a class="quick-card" href="/admin/products">
                                <div class="quick-left">
                                    <div class="quick-icon">📦</div>
                                    <div>
                                        <b>Manage Products</b>
                                        <span>Atur produk, harga, profit, dan tier.</span>
                                    </div>
                                </div>
                                <div class="quick-arrow">→</div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-head">
                        <div class="panel-title">
                            <b>Latest Withdrawals</b>
                            <span>Permintaan penarikan terbaru.</span>
                        </div>

                        <a href="{{ route('admin.withdraw.page') }}" class="panel-action">Open</a>
                    </div>

                    <div class="table-wrap">
                        <table id="withdrawTable">
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse(($latestWithdrawals ?? collect()) as $withdraw)
                                <tr>
                                    <td data-label="User">
                                        <div class="identity">
                                            <div class="identity-avatar">
                                                {{ adminInitial($withdraw->user->name ?? 'U') }}
                                            </div>
                                            <div>
                                                <b>{{ $withdraw->user->name ?? 'Unknown User' }}</b>
                                                <span>{{ optional($withdraw->created_at)->format('d M Y H:i') }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td data-label="Amount">
                                        <span class="money">{{ adminMoney($withdraw->amount ?? 0) }}</span>
                                    </td>

                                    <td data-label="Status">
                                        <span class="status {{ adminStatusClass($withdraw->status) }}">
                                            {{ $withdraw->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        <div class="empty">Belum ada data withdraw.</div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
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

    function filterTable(tableId, query) {
        const table = document.getElementById(tableId);
        if (!table) return;

        query = (query || '').toLowerCase().trim();

        table.querySelectorAll('tbody tr').forEach((tr) => {
            const text = tr.textContent.toLowerCase();
            tr.style.display = !query || text.includes(query) ? '' : 'none';
        });
    }

    function filterDashboardTables(q) {
        filterTable('depositTable', q);
        filterTable('withdrawTable', q);
    }

    document.querySelectorAll('[data-table-search]').forEach((input) => {
        input.addEventListener('input', function () {
            filterTable(this.getAttribute('data-table-search'), this.value);
        });
    });

    (function setActiveNav() {
        const path = window.location.pathname.replace(/\/$/, '');

        document.querySelectorAll('#navMenu .nav-item').forEach((item) => {
            const href = (item.getAttribute('href') || '').replace(/\/$/, '');

            if (!href) return;

            const active =
                (href === '/admin' && path === '/admin') ||
                (href !== '/admin' && path.startsWith(href));

            item.classList.toggle('active', active);
        });
    })();

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