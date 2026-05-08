@php
    $authUser = auth()->user();

    function adminInitial($name) {
        return strtoupper(substr($name ?: 'A', 0, 1));
    }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<title>Admin | Withdrawals</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">

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
            --slate-soft: #f2f4f7;

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
            overflow-x: hidden;
        }

        a { color: inherit; text-decoration: none; }
        button, input, select, textarea { font-family: inherit; }

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

        /* HERO */
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

        /* PANEL */
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

        .toolbar {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .select {
            width: 220px;
            height: 42px;
            border-radius: 15px;
            border: 1px solid var(--line);
            background: #f9fbff;
            outline: none;
            padding: 0 12px;
            color: var(--text);
            font-size: 13px;
            font-weight: 800;
            transition: .18s ease;
        }

        .select:focus {
            border-color: rgba(49, 87, 248, .35);
            box-shadow: 0 0 0 4px rgba(49, 87, 248, .08);
            background: #fff;
        }

        .btn,
        .btn-mini {
            border-radius: 13px;
            border: 1px solid rgba(49, 87, 248, .14);
            background: var(--blue-soft);
            color: var(--blue);
            font-weight: 900;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: .18s ease;
            text-decoration: none;
        }

        .btn {
            height: 42px;
            padding: 0 14px;
        }

        .btn-mini {
            min-height: 34px;
            padding: 0 12px;
        }

        .btn:hover,
        .btn-mini:hover {
            transform: translateY(-1px);
            background: var(--blue);
            color: #fff;
        }

        .btn-primary {
            background: var(--blue);
            color: #fff;
            border-color: rgba(49, 87, 248, .18);
            box-shadow: 0 14px 24px rgba(49, 87, 248, .20);
        }

        .btn-primary:hover {
            background: #2348db;
        }

        .btn-approve {
            background: var(--blue-soft);
            color: var(--blue);
            border-color: rgba(49, 87, 248, .14);
        }

        .btn-reject {
            background: var(--red-soft);
            color: #b42318;
            border-color: rgba(240, 68, 56, .18);
        }

        .btn-paid {
            background: var(--green-soft);
            color: #027a48;
            border-color: rgba(18, 183, 106, .16);
        }

        .btn-proof {
            background: #101828;
            color: #fff;
            border-color: rgba(16, 24, 40, .18);
        }

        .btn-reject:hover {
            background: var(--red);
            color: #fff;
        }

        .btn-paid:hover {
            background: var(--green);
            color: #fff;
        }

        .btn-proof:hover {
            background: #344054;
            color: #fff;
        }

        .panel-inner {
            padding: 18px;
        }

        .hint {
            color: var(--muted);
            font-size: 12.5px;
            line-height: 1.5;
            font-weight: 700;
        }

        .rows {
            display: grid;
            gap: 12px;
        }

        .row-card {
            display: grid;
            grid-template-columns: 5px 1fr;
            border: 1px solid var(--line);
            border-radius: 22px;
            background: #fff;
            box-shadow: var(--shadow-soft);
            overflow: hidden;
            transition: .18s ease;
        }

        .row-card:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        .bar-pending { background: var(--yellow); }
        .bar-approved { background: var(--blue); }
        .bar-paid { background: var(--green); }
        .bar-rejected { background: var(--red); }
        .bar-cancelled { background: var(--muted-2); }

        .row-body {
            padding: 15px;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 14px;
            align-items: start;
        }

        .amount-line {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .amount {
            font-weight: 950;
            font-size: 16px;
            letter-spacing: -.02em;
        }

        .status-badge {
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

        .status-pending {
            color: #b54708;
            background: var(--yellow-soft);
            border-color: rgba(247, 144, 9, .18);
        }

        .status-approved {
            color: var(--blue);
            background: var(--blue-soft);
            border-color: rgba(49, 87, 248, .14);
        }

        .status-paid {
            color: #027a48;
            background: var(--green-soft);
            border-color: rgba(18, 183, 106, .16);
        }

        .status-rejected {
            color: #b42318;
            background: var(--red-soft);
            border-color: rgba(240, 68, 56, .18);
        }

        .status-cancelled {
            color: #475467;
            background: var(--slate-soft);
            border-color: #eaecf0;
        }

        .meta {
            margin-top: 8px;
            color: var(--muted);
            font-size: 12.5px;
            line-height: 1.6;
            font-weight: 700;
        }

        .meta b {
            color: var(--text);
        }

        .reject-reason {
            margin-top: 10px;
            color: #b42318;
            background: var(--red-soft);
            border: 1px solid rgba(240, 68, 56, .18);
            border-radius: 16px;
            padding: 10px 12px;
            font-size: 12.5px;
            line-height: 1.5;
            font-weight: 800;
        }

        .row-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
            text-align: right;
        }

        .actions-list {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .empty {
            padding: 34px 18px;
            text-align: center;
            color: var(--muted);
            font-size: 13px;
            border: 1px dashed var(--line);
            border-radius: 20px;
            background: #f9fbff;
        }

        .loading {
            padding: 34px 18px;
            text-align: center;
            color: var(--muted);
            font-size: 13px;
            border: 1px dashed var(--line);
            border-radius: 20px;
            background: #f9fbff;
        }

        /* MODAL */
        .modal-overlay {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(16, 24, 40, .52);
            backdrop-filter: blur(5px);
            padding: 18px;
            z-index: 100;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal {
            width: 100%;
            max-width: 560px;
            border-radius: 26px;
            border: 1px solid var(--line);
            background: #fff;
            box-shadow: 0 30px 80px rgba(16, 24, 40, .22);
            overflow: hidden;
        }

        .modal-head {
            padding: 16px 18px;
            border-bottom: 1px solid var(--line);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .modal-title {
            font-weight: 950;
            letter-spacing: -.02em;
        }

        .modal-body {
            padding: 18px;
        }

        .modal-close {
            width: 38px;
            height: 38px;
            border-radius: 14px;
            border: 1px solid var(--line);
            background: #fff;
            cursor: pointer;
            font-weight: 950;
            box-shadow: var(--shadow-soft);
        }

        .textarea,
        .file-input {
            width: 100%;
            border-radius: 16px;
            border: 1px solid var(--line);
            background: #f9fbff;
            outline: none;
            padding: 12px 14px;
            color: var(--text);
            font-size: 13px;
            font-weight: 700;
            transition: .18s ease;
        }

        .textarea:focus,
        .file-input:focus {
            border-color: rgba(49, 87, 248, .35);
            box-shadow: 0 0 0 4px rgba(49, 87, 248, .08);
            background: #fff;
        }

        .modal-actions {
            margin-top: 14px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-neutral {
            height: 42px;
            padding: 0 14px;
            border-radius: 14px;
            border: 1px solid var(--line);
            background: #fff;
            color: var(--text);
            font-weight: 900;
            cursor: pointer;
            box-shadow: var(--shadow-soft);
        }

        .btn-danger {
            height: 42px;
            padding: 0 14px;
            border-radius: 14px;
            border: 0;
            background: var(--red);
            color: #fff;
            font-weight: 950;
            cursor: pointer;
            box-shadow: 0 14px 24px rgba(240, 68, 56, .20);
        }

        .modal-btn-primary {
            height: 42px;
            padding: 0 14px;
            border-radius: 14px;
            border: 0;
            background: var(--blue);
            color: #fff;
            font-weight: 950;
            cursor: pointer;
            box-shadow: 0 14px 24px rgba(49, 87, 248, .20);
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
            .panel-head {
                flex-direction: column;
            }

            .toolbar {
                width: 100%;
                justify-content: flex-start;
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

            .hero-actions {
                flex-direction: column;
            }

            .hero-link {
                width: 100%;
            }

            .toolbar,
            .select,
            .btn {
                width: 100%;
            }

            .row-body {
                grid-template-columns: 1fr;
            }

            .row-right {
                align-items: flex-start;
                text-align: left;
            }

            .actions-list {
                justify-content: flex-start;
                width: 100%;
            }

            .btn-mini {
                flex: 1;
            }

            .modal-actions {
                flex-direction: column;
            }

            .btn-neutral,
            .btn-danger,
            .modal-btn-primary {
                width: 100%;
            }
        }

        .gateway-box {
    margin-top: 12px;
    border: 1px solid rgba(49, 87, 248, .12);
    background:
        radial-gradient(220px 120px at 100% 0%, rgba(49, 87, 248, .08), transparent 60%),
        linear-gradient(180deg, #ffffff 0%, #f8faff 100%);
    border-radius: 18px;
    padding: 12px;
}

.gateway-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 10px;
}

.gateway-title {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #101828;
    font-size: 12.5px;
    font-weight: 950;
    letter-spacing: -.01em;
}

.gateway-title::before {
    content: "";
    width: 8px;
    height: 8px;
    border-radius: 99px;
    background: var(--blue);
    box-shadow: 0 0 0 4px rgba(49, 87, 248, .10);
}

.gateway-pill {
    display: inline-flex;
    align-items: center;
    min-height: 24px;
    padding: 0 9px;
    border-radius: 999px;
    color: var(--blue);
    background: var(--blue-soft);
    border: 1px solid rgba(49, 87, 248, .14);
    font-size: 10.5px;
    font-weight: 950;
    white-space: nowrap;
}

.gateway-pill.is-success {
    color: #027a48;
    background: var(--green-soft);
    border-color: rgba(18, 183, 106, .16);
}

.gateway-pill.is-failed {
    color: #b42318;
    background: var(--red-soft);
    border-color: rgba(240, 68, 56, .18);
}

.gateway-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 8px;
}

.gateway-item {
    min-width: 0;
    border: 1px solid var(--line);
    background: #fff;
    border-radius: 14px;
    padding: 9px 10px;
}

.gateway-item span {
    display: block;
    color: var(--muted);
    font-size: 10.5px;
    font-weight: 800;
    margin-bottom: 5px;
}

.gateway-item b {
    display: block;
    color: var(--text);
    font-size: 12px;
    line-height: 1.35;
    font-weight: 900;
    word-break: break-word;
}

.gateway-item.is-wide {
    grid-column: 1 / -1;
}

.gateway-message {
    margin-top: 8px;
    border-radius: 14px;
    padding: 9px 10px;
    background: var(--yellow-soft);
    border: 1px solid rgba(247, 144, 9, .18);
    color: #b54708;
    font-size: 11.5px;
    line-height: 1.45;
    font-weight: 800;
}

.gateway-message.is-error {
    background: var(--red-soft);
    border-color: rgba(240, 68, 56, .18);
    color: #b42318;
}

@media (max-width: 620px) {
    .gateway-grid {
        grid-template-columns: 1fr;
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

                <a class="nav-item active" href="{{ route('admin.withdraw.page') }}" data-label="withdraw penarikan saldo">
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
                    <h1>Withdrawals</h1>
                    <p>Kelola request WD: approve, reject, dan set paid dengan bukti transfer.</p>
                </div>
            </div>

            <div class="top-actions">
                <div class="status-pill">
                    <span class="live-dot"></span>
                    <span>System Online</span>
                </div>

                <a href="/admin/deposits" class="icon-btn" title="Deposits">💳</a>
                <a href="/admin/users" class="icon-btn" title="Users">👥</a>

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
                    <span>↗</span>
                    Withdrawal Management
                </div>

                <h2>Proses penarikan saldo user dengan panel admin yang lebih rapi.</h2>

                <p>
                    Gunakan filter status untuk mengecek request harian. Admin dapat approve,
                    reject dengan alasan, dan menandai withdraw sebagai paid beserta bukti transfer.
                </p>

                <div class="hero-actions">
                    <a href="/admin" class="hero-link secondary">← Dashboard</a>
                    <a href="/admin/users" class="hero-link">Open Users →</a>
                </div>
            </div>
        </section>

        {{-- WITHDRAW PANEL --}}
        <section class="panel">
            <div class="panel-head">
                <div class="panel-title">
                    <b>Withdrawal Requests</b>
                    <span>Data diambil dari endpoint JSON `/admin/withdrawals`.</span>
                </div>

                <div class="toolbar">
                    <select id="status" class="select" aria-label="Filter status">
                        <option value="">All Status</option>
                        <option value="PENDING">PENDING</option>
                        <option value="APPROVED">APPROVED</option>
                        <option value="PAID">PAID</option>
                        <option value="REJECTED">REJECTED</option>
                        <option value="CANCELLED">CANCELLED</option>
                    </select>

                    <button id="btnLoad" class="btn btn-primary" type="button">⟳ Load</button>
                </div>
            </div>

            <div class="panel-inner">
                <div class="hint" style="margin-bottom:14px">
                    Tips: gunakan filter status untuk mempercepat pengecekan request withdraw yang masih pending.
                </div>

                <div id="rows" class="rows">
                    <div class="loading">Loading data withdraw...</div>
                </div>
            </div>
        </section>
    </main>
</div>

{{-- Modal Reject --}}
<div id="rejectModal" class="modal-overlay" aria-hidden="true">
    <div class="modal" role="dialog" aria-modal="true" aria-label="Reject Withdraw">
        <div class="modal-head">
            <div>
                <div class="modal-title">Reject Withdraw</div>
                <div class="hint">Masukkan alasan penolakan agar user jelas dan bisa follow up.</div>
            </div>

            <button class="modal-close" type="button" onclick="closeReject()">×</button>
        </div>

        <div class="modal-body">
            <textarea id="rejectReason" class="textarea" rows="4" placeholder="Alasan reject..." style="resize:none"></textarea>

            <div class="modal-actions">
                <button class="btn-neutral" type="button" onclick="closeReject()">Batal</button>
                <button id="btnRejectSubmit" class="btn-danger" type="button">Reject</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Paid --}}
<div id="paidModal" class="modal-overlay" aria-hidden="true">
    <div class="modal" role="dialog" aria-modal="true" aria-label="Mark as Paid">
        <div class="modal-head">
            <div>
                <div class="modal-title">Mark as Paid</div>
                <div class="hint">Upload bukti transfer opsional, tapi disarankan untuk audit.</div>
            </div>

            <button class="modal-close" type="button" onclick="closePaid()">×</button>
        </div>

        <div class="modal-body">
            <input id="paidProof" type="file" class="file-input" />

            <div class="modal-actions">
                <button class="btn-neutral" type="button" onclick="closePaid()">Batal</button>
                <button id="btnPaidSubmit" class="modal-btn-primary" type="button">Set Paid</button>
            </div>
        </div>
    </div>
</div>

<script>

        function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

async function api(url, options = {}) {
    const isFormData = options.body instanceof FormData;

    const headers = {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken(),
        ...(isFormData ? {} : { 'Content-Type': 'application/json' }),
        ...(options.headers || {})
    };

    const res = await fetch(url, {
        credentials: 'same-origin',
        ...options,
        headers
    });

    let data = null;

    try {
        data = await res.json();
    } catch (e) {
        data = {};
    }

    if (!res.ok) {
        throw new Error(data?.message || data?.error || 'Request gagal.');
    }

    return data;
}

function toast(message, type = 'success') {
    alert(message);
}
    const rowsEl = document.getElementById('rows');
    const statusSel = document.getElementById('status');

    let rejectId = null;
    let paidId = null;

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

    function rupiah(n) {
        try {
            return new Intl.NumberFormat('id-ID').format(n || 0);
        } catch {
            return String(n || 0);
        }
    }

    function escapeHtml(str) {
        return String(str ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function parseGatewayResponse(value) {
    if (!value) return null;

    if (typeof value === 'object') {
        return value;
    }

    try {
        return JSON.parse(value);
    } catch (e) {
        return null;
    }
}

function firstFilled(...values) {
    for (const value of values) {
        if (value !== null && value !== undefined && String(value).trim() !== '') {
            return value;
        }
    }

    return '-';
}

function gatewayPillClass(code, status, failedReason) {
    const raw = [
        code,
        status,
        failedReason
    ].map(v => String(v || '').toUpperCase()).join(' ');

    if (raw.includes('SUCCESS') || raw.includes('PAID') || raw.includes('APPLY')) {
        return 'is-success';
    }

    if (
        raw.includes('FAILED') ||
        raw.includes('FAIL') ||
        raw.includes('ERROR') ||
        raw.includes('INSUFFICIENT') ||
        raw.includes('REJECT')
    ) {
        return 'is-failed';
    }

    return '';
}

function gatewayInfoHtml(r) {
    const g = parseGatewayResponse(r.gateway_response);

    const orderNum = firstFilled(
        r.order_id,
        r.order_num,
        g?.orderNum
    );

    const platOrderNum = firstFilled(
        r.plat_order_num,
        g?.platOrderNum
    );

    const gatewayCode = firstFilled(
        r.gateway_status,
        g?.platRespCode
    );

    const gatewayMessage = firstFilled(
        r.gateway_message,
        g?.platRespMessage,
        g?.statusMsg
    );

    const bankCode = firstFilled(
        r.bank_code,
        g?.bankCode
    );

    const accountNo = firstFilled(
        r.account_no,
        g?.number
    );

    const accountName = firstFilled(
        r.account_name,
        g?.name
    );

    const gatewayMoney = Number(firstFilled(
        g?.money,
        r.net_amount,
        r.amount,
        0
    ));

    const gatewayFee = Number(firstFilled(
        g?.fee,
        r.fee,
        0
    ));

    const failedReason = firstFilled(
        r.failed_reason,
        r.gateway_message
    );

    const processedAt = firstFilled(
        r.processed_at,
        '-'
    );

    const callbackUrl = firstFilled(
        r.gateway_callback,
        '-'
    );

    const hasGatewayData =
        r.gateway_response ||
        r.gateway_status ||
        r.gateway_message ||
        r.plat_order_num ||
        r.order_id ||
        r.failed_reason ||
        r.processed_at;

    if (!hasGatewayData) {
        return '';
    }

    const pillClass = gatewayPillClass(gatewayCode, r.status, r.failed_reason);

    const failedHtml = r.failed_reason
        ? `<div class="gateway-message is-error"><b>Failed Reason:</b> ${escapeHtml(r.failed_reason)}</div>`
        : '';

    const messageHtml = gatewayMessage !== '-'
        ? `<div class="gateway-message"><b>Gateway Message:</b> ${escapeHtml(gatewayMessage)}</div>`
        : '';

    return `
        <div class="gateway-box">
            <div class="gateway-head">
                <div class="gateway-title">Gateway Response</div>
                <div class="gateway-pill ${pillClass}">
                    ${escapeHtml(gatewayCode)}
                </div>
            </div>

            <div class="gateway-grid">
                <div class="gateway-item">
                    <span>Order WD</span>
                    <b>${escapeHtml(orderNum)}</b>
                </div>

                <div class="gateway-item">
                    <span>Plat Order</span>
                    <b>${escapeHtml(platOrderNum)}</b>
                </div>

                <div class="gateway-item">
                    <span>Bank Code</span>
                    <b>${escapeHtml(bankCode)}</b>
                </div>

                <div class="gateway-item">
                    <span>Tujuan</span>
                    <b>${escapeHtml(accountNo)}</b>
                </div>

                <div class="gateway-item">
                    <span>Nama Tujuan</span>
                    <b>${escapeHtml(accountName)}</b>
                </div>

                <div class="gateway-item">
                    <span>Nominal Gateway</span>
                    <b>Rp ${rupiah(gatewayMoney)}</b>
                </div>

                <div class="gateway-item">
                    <span>Fee Gateway</span>
                    <b>Rp ${rupiah(gatewayFee)}</b>
                </div>

                <div class="gateway-item">
                    <span>Processed At</span>
                    <b>${escapeHtml(processedAt)}</b>
                </div>

                ${callbackUrl !== '-' ? `
                    <div class="gateway-item is-wide">
                        <span>Callback URL</span>
                        <b>${escapeHtml(callbackUrl)}</b>
                    </div>
                ` : ''}
            </div>

            ${messageHtml}
            ${failedHtml}
        </div>
    `;
}

    function statusKey(status) {
        return String(status || '').toUpperCase();
    }

function statusBarClass(status) {
    const s = statusKey(status);

    if (s === 'PENDING') return 'bar-pending';
    if (s === 'PROCESSING') return 'bar-approved';
    if (s === 'APPROVED') return 'bar-approved';
    if (s === 'PAID') return 'bar-paid';
    if (s === 'FAILED') return 'bar-rejected';
    if (s === 'REJECTED') return 'bar-rejected';
    if (s === 'CANCELLED') return 'bar-cancelled';

    return 'bar-pending';
}
function statusBadge(status) {
    const s = statusKey(status);
    let cls = 'status-pending';

    if (s === 'PROCESSING') cls = 'status-approved';
    if (s === 'APPROVED') cls = 'status-approved';
    if (s === 'PAID') cls = 'status-paid';
    if (s === 'FAILED') cls = 'status-rejected';
    if (s === 'REJECTED') cls = 'status-rejected';
    if (s === 'CANCELLED') cls = 'status-cancelled';

    return `<span class="status-badge ${cls}">${escapeHtml(s || '-')}</span>`;
}

    async function loadAdmin() {
        rowsEl.innerHTML = `<div class="loading">Loading data withdraw...</div>`;

        const status = statusSel.value;
        const url = status ? `/admin/withdrawals?status=${encodeURIComponent(status)}` : '/admin/withdrawals';

        const res = await api(url);
        const rows = res?.data || [];

        if (!rows.length) {
            rowsEl.innerHTML = `<div class="empty">Tidak ada data withdraw.</div>`;
            return;
        }

        rowsEl.innerHTML = rows.map((r) => {
            const acct = r.payout_account || r.payoutAccount;
            const user = r.user;

            const created = r.created_at ? new Date(r.created_at).toLocaleString('id-ID') : '-';
            const userLine = user ? `${user.name ?? ''} (#${user.id})` : `#${r.user_id}`;
            const payoutLine = acct
                ? `${acct.type ?? '-'} • ${acct.provider ?? '-'} • ${acct.account_name ?? '-'} • ${acct.account_number ?? '-'}`
                : '-';

            const reasonHtml = r.reject_reason
                ? `<div class="reject-reason">Reject: ${escapeHtml(String(r.reject_reason))}</div>`
                : '';

                const gatewayHtml = gatewayInfoHtml(r);

            const actions = (() => {
                const s = statusKey(r.status);
                let html = '';

                if (s === 'PENDING') {
                    html += `
                        <button class="btn-mini btn-approve" type="button" onclick="approve(${r.id})">Approve</button>
                        <button class="btn-mini btn-reject" type="button" onclick="openReject(${r.id})">Reject</button>
                        <button class="btn-mini btn-paid" type="button" onclick="openPaid(${r.id})">Paid</button>
                    `;
                } else if (s === 'APPROVED') {
                    html += `
                        <button class="btn-mini btn-reject" type="button" onclick="openReject(${r.id})">Reject</button>
                        <button class="btn-mini btn-paid" type="button" onclick="openPaid(${r.id})">Paid</button>
                    `;
                } else if (s === 'PAID' && r.proof_url) {
                    html += `
                        <a class="btn-mini btn-proof" href="${escapeHtml(r.proof_url)}" target="_blank" rel="noopener">Bukti</a>
                    `;
                }

                return html ? `<div class="actions-list">${html}</div>` : `<div class="hint">Tidak ada aksi</div>`;
            })();

            return `
                <div class="row-card">
                    <div class="${statusBarClass(r.status)}"></div>

                    <div class="row-body">
                        <div>
                            <div class="amount-line">
                                <div class="amount">Rp ${rupiah(r.amount)}</div>
                                ${statusBadge(r.status)}
                            </div>

                            <div class="meta">
                                <div><b>User:</b> ${escapeHtml(userLine)}</div>
                                <div><b>Payout:</b> ${escapeHtml(payoutLine)}</div>
                            </div>

                           ${gatewayHtml}
${reasonHtml}
                        </div>

                        <div class="row-right">
                            <div class="meta" style="margin-top:0">${escapeHtml(created)}</div>
                            ${actions}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    window.approve = async function(id) {
        if (!confirm('Approve withdraw ini?')) return;

        try {
            await api(`/admin/withdrawals/${id}/approve`, { method: 'POST' });
            toast('Withdraw approved');
            await loadAdmin();
        } catch (e) {
            toast(e.message, 'err');
        }
    }

    window.openReject = function(id) {
        rejectId = id;
        document.getElementById('rejectReason').value = '';
        const modal = document.getElementById('rejectModal');
        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
    }

    window.closeReject = function() {
        rejectId = null;
        const modal = document.getElementById('rejectModal');
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
    }

    document.getElementById('btnRejectSubmit').addEventListener('click', async () => {
        const reason = document.getElementById('rejectReason').value.trim();

        if (!reason) {
            return toast('Alasan reject wajib diisi', 'err');
        }

        try {
            await api(`/admin/withdrawals/${rejectId}/reject`, {
                method: 'POST',
                body: JSON.stringify({ reason })
            });

            toast('Withdraw rejected');
            closeReject();
            await loadAdmin();
        } catch (e) {
            toast(e.message, 'err');
        }
    });

    window.openPaid = function(id) {
        paidId = id;
        document.getElementById('paidProof').value = '';
        const modal = document.getElementById('paidModal');
        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
    }

    window.closePaid = function() {
        paidId = null;
        const modal = document.getElementById('paidModal');
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
    }

    document.getElementById('btnPaidSubmit').addEventListener('click', async () => {
        try {
            const formData = new FormData();
            const file = document.getElementById('paidProof').files?.[0];

            if (file) {
                formData.append('proof', file);
            }

            await api(`/admin/withdrawals/${paidId}/paid`, {
                method: 'POST',
                body: formData
            });

            toast('Withdraw marked as paid');
            closePaid();
            await loadAdmin();
        } catch (e) {
            toast(e.message, 'err');
        }
    });

    document.getElementById('rejectModal').addEventListener('click', (e) => {
        if (e.target.id === 'rejectModal') closeReject();
    });

    document.getElementById('paidModal').addEventListener('click', (e) => {
        if (e.target.id === 'paidModal') closePaid();
    });

    document.getElementById('btnLoad').addEventListener('click', () => {
        loadAdmin().catch((e) => toast(e.message, 'err'));
    });

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
            if (e.key === 'Escape') {
                toggleSidebar(false);
                closeReject();
                closePaid();
            }
        });
    })();

    loadAdmin().catch((e) => toast(e.message, 'err'));
</script>
</body>
</html>