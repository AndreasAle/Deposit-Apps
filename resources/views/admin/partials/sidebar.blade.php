@php
    $user = auth()->user();
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Admin Products | TumbuhMaju</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <style>
        :root{
            --bg:#f5f7fb;
            --card:#ffffff;
            --text:#0f172a;
            --muted:#64748b;
            --border:#e7ebf3;
            --primary:#2563eb;
            --primary-2:#1d4ed8;
            --danger:#ef4444;
            --shadow: 0 18px 45px rgba(15,23,42,.08);
            --shadow-soft: 0 10px 22px rgba(15,23,42,.06);
            --radius:18px;
            --radius-sm:14px;

            /* sidebar (dashboard) */
            --sidebar:#0b1220;
            --sidebar-2:#0f1a30;
            --sidebar-text:#dbe7ff;
            --sidebar-muted:#9db3dd;
            --chip:#eef2ff;
        }

        *{ box-sizing:border-box; }
        html,body{ height:100%; }
        body{
            margin:0;
            font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            color:var(--text);
            background: radial-gradient(1200px 600px at 10% 0%, #eaf0ff 0%, transparent 60%),
                        radial-gradient(900px 500px at 90% 10%, #e9fbff 0%, transparent 55%),
                        var(--bg);
        }
        a{ text-decoration:none; color:inherit; }
        button{ font-family:inherit; }

        /* ===== LAYOUT (dashboard) ===== */
        .app{
            min-height:100vh;
            display:grid;
            grid-template-columns: 280px 1fr;
        }

        /* ===== SIDEBAR (dashboard) ===== */
        .sidebar{
            position:sticky;
            top:0;
            height:100vh;
            padding:22px 16px;
            background: linear-gradient(180deg, var(--sidebar) 0%, var(--sidebar-2) 100%);
            border-right: 1px solid rgba(255,255,255,.06);
            color:var(--sidebar-text);
        }
        .brand{
            display:flex;
            align-items:center;
            gap:12px;
            padding:10px 12px;
            border-radius:16px;
        }
        .logo{
            width:42px;height:42px;
            border-radius:14px;
            background: linear-gradient(135deg, #60a5fa 0%, #2563eb 45%, #22c55e 100%);
            box-shadow: 0 14px 30px rgba(37,99,235,.25);
            display:grid;
            place-items:center;
            color:#fff;
            font-weight:800;
            letter-spacing:.5px;
        }
        .brand h2{
            margin:0;
            font-size:15px;
            line-height:1.1;
        }
        .brand p{
            margin:4px 0 0;
            font-size:12px;
            color:var(--sidebar-muted);
        }

        .side-search{
            margin:14px 12px 8px;
            position:relative;
        }
        .side-search input{
            width:100%;
            padding:12px 12px 12px 40px;
            border-radius:14px;
            border:1px solid rgba(255,255,255,.10);
            background: rgba(255,255,255,.06);
            color:var(--sidebar-text);
            outline:none;
        }
        .side-search input::placeholder{ color:rgba(219,231,255,.65); }
        .side-search .icon{
            position:absolute;
            left:12px;
            top:50%;
            transform:translateY(-50%);
            opacity:.8;
            font-size:14px;
        }

        .nav{
            margin-top:14px;
            padding:0 6px;
        }
        .nav-section{
            margin:14px 8px 8px;
            font-size:11px;
            letter-spacing:.12em;
            color:rgba(219,231,255,.55);
            text-transform:uppercase;
        }
        .nav a{
            display:flex;
            align-items:center;
            gap:10px;
            padding:12px 12px;
            border-radius:14px;
            margin:6px 6px;
            color:var(--sidebar-text);
            border:1px solid transparent;
            transition:.18s ease;
        }
        .nav a:hover{
            background: rgba(255,255,255,.07);
            border-color: rgba(255,255,255,.08);
            transform: translateY(-1px);
        }
        .nav a.active{
            background: rgba(37,99,235,.22);
            border-color: rgba(96,165,250,.40);
            box-shadow: 0 12px 24px rgba(37,99,235,.14);
        }
        .nav .bullet{
            width:34px;height:34px;
            border-radius:12px;
            display:grid;
            place-items:center;
            background: rgba(255,255,255,.06);
        }
        .nav .meta{
            display:flex;
            flex-direction:column;
            line-height:1.15;
        }
        .nav .meta b{ font-size:13px; font-weight:700; }
        .nav .meta span{ font-size:11.5px; color:rgba(219,231,255,.65); margin-top:3px; }

        .sidebar-footer{
            position:absolute;
            left:16px;
            right:16px;
            bottom:18px;
            background: rgba(255,255,255,.06);
            border:1px solid rgba(255,255,255,.08);
            border-radius:16px;
            padding:12px;
        }
        .who{
            display:flex;
            gap:10px;
            align-items:center;
        }
        .avatar{
            width:38px;height:38px;
            border-radius:14px;
            background: rgba(255,255,255,.12);
            display:grid;
            place-items:center;
            font-weight:800;
        }
        .who .name{
            display:flex;
            flex-direction:column;
        }
        .who .name b{ font-size:13px; }
        .who .name span{ font-size:11.5px; color:rgba(219,231,255,.70); margin-top:3px; }

        /* ===== MAIN ===== */
        .main{ padding:26px; }

        .topbar{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:16px;
            margin-bottom:18px;
        }
        .topbar-left{
            display:flex;
            flex-direction:column;
            gap:4px;
        }
        .page-title{
            margin:0;
            font-size:22px;
            letter-spacing:-0.02em;
        }
        .subtitle{
            color:var(--muted);
            font-size:13px;
        }

        .topbar-right{
            display:flex;
            align-items:center;
            gap:10px;
            flex-wrap:wrap;
            justify-content:flex-end;
        }

        .pill{
            display:flex;
            align-items:center;
            gap:8px;
            padding:10px 12px;
            background: var(--card);
            border:1px solid var(--border);
            border-radius:999px;
            box-shadow: var(--shadow-soft);
            font-size:13px;
            color:var(--muted);
        }
        .dot{
            width:9px;height:9px;border-radius:99px;
            background:#22c55e;
            box-shadow: 0 0 0 4px rgba(34,197,94,.14);
        }

        /* ===== BUTTONS (re-use) ===== */
        .btn{
            border:1px solid var(--border);
            background: var(--card);
            color: var(--text);
            padding:10px 12px;
            border-radius:12px;
            cursor:pointer;
            box-shadow: var(--shadow-soft);
            transition:.18s ease;
            font-size:13px;
            font-weight:900;
            display:inline-flex;
            gap:8px;
            align-items:center;
            white-space:nowrap;
        }
        .btn:hover{ transform: translateY(-1px); }
        .btn-primary{
            border-color: rgba(37,99,235,.25);
            background: rgba(37,99,235,.08);
            color: var(--primary);
        }
        .btn-gray{
            border-color: rgba(148,163,184,.35);
            background: rgba(148,163,184,.12);
            color: #334155;
        }
        .btn-danger{
            border-color: rgba(239,68,68,.35);
            color: var(--danger);
        }

        /* ===== PAGE CARD (produk kamu) ===== */
        .card{
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding:18px;
        }

        .table-wrap{
            overflow:auto;
            border:1px solid var(--border);
            border-radius:16px;
            background:#fff;
        }
        table{
            width:100%;
            border-collapse:separate;
            border-spacing:0;
            min-width: 900px;
            font-size:13px;
        }
        thead th{
            text-align:left;
            font-size:12px;
            color: var(--muted);
            padding:12px 14px;
            border-bottom:1px solid var(--border);
            background: #fbfcff;
            white-space:nowrap;
        }
        tbody td{
            padding:12px 14px;
            border-bottom:1px solid var(--border);
            vertical-align:middle;
            white-space:nowrap;
        }
        tbody tr:hover td{ background:#fafcff; }

        .badge{
            padding:6px 10px;
            border-radius:999px;
            font-size:12px;
            font-weight:900;
            border:1px solid;
            display:inline-flex;
            align-items:center;
            gap:8px;
        }
        .green{ background:#dcfce7; color:#166534; border-color: rgba(34,197,94,.25); }
        .red{ background:#fee2e2; color:#991b1b; border-color: rgba(239,68,68,.25); }

        .actions-col{
            display:flex;
            gap:8px;
            align-items:center;
            flex-wrap:wrap;
        }
        .danger-link{
            background:none;
            border:none;
            color: var(--danger);
            cursor:pointer;
            font-weight:900;
            padding: 8px 8px;
        }

        /* ===== MOBILE OFFCANVAS SIDEBAR (dashboard) ===== */
        .hamburger{
            display:none;
            border:1px solid var(--border);
            background: var(--card);
            color: var(--text);
            padding:10px 12px;
            border-radius:12px;
            cursor:pointer;
            box-shadow: var(--shadow-soft);
            font-size:16px;
            font-weight:900;
            line-height:1;
        }
        .overlay{
            display:none;
            position:fixed;
            inset:0;
            background: rgba(2, 6, 23, .55);
            backdrop-filter: blur(2px);
            z-index: 40;
            opacity:0;
            transition: opacity .18s ease;
        }
        body.sidebar-open{ overflow:hidden; }

        /* Responsive */
        @media (max-width: 860px){
            .app{ grid-template-columns: 1fr; }
            .sidebar{
                position:fixed;
                top:0;
                left:0;
                height:100vh;
                width: 86vw;
                max-width: 320px;
                z-index:50;
                transform: translateX(-105%);
                transition: transform .22s ease;
                border-right: 1px solid rgba(255,255,255,.06);
            }
            body.sidebar-open .sidebar{ transform: translateX(0); }
            body.sidebar-open .overlay{ display:block; opacity:1; }

            .sidebar-footer{
                position:absolute;
                left:16px;
                right:16px;
                bottom:18px;
            }

            .main{ padding:14px; }

            .topbar{
                position:sticky;
                top:0;
                z-index:20;
                background: rgba(245,247,251,.85);
                backdrop-filter: blur(6px);
                border-radius: 16px;
                padding: 12px;
                border: 1px solid var(--border);
                margin-bottom:14px;
            }

            .hamburger{
                display:inline-flex;
                align-items:center;
                justify-content:center;
                margin-right:10px;
            }

            /* table jadi card list (produk kamu) */
            .table-wrap{
                border:none;
                background: transparent;
                overflow: visible;
            }
            table{
                min-width: 0;
                width: 100%;
                border-spacing: 0 10px;
                background: transparent;
            }
            thead{ display:none; }
            tbody tr{
                display:block;
                background:#fff;
                border:1px solid var(--border);
                border-radius:16px;
                box-shadow: var(--shadow-soft);
                overflow:hidden;
            }
            tbody td{
                display:flex;
                justify-content:space-between;
                gap:14px;
                padding:10px 12px;
                border-bottom:1px solid var(--border);
                white-space:normal;
                font-size:12.5px;
            }
            tbody td:last-child{ border-bottom:none; }

            /* urutan kolom: Nama | Kategori | Harga | VIP Min | Status | Aksi */
            tbody td:nth-child(1)::before{ content:"Nama"; }
            tbody td:nth-child(2)::before{ content:"Kategori"; }
            tbody td:nth-child(3)::before{ content:"Harga"; }
            tbody td:nth-child(4)::before{ content:"VIP Min"; }
            tbody td:nth-child(5)::before{ content:"Status"; }
            tbody td:nth-child(6)::before{ content:"Aksi"; }

            tbody td::before{
                color: var(--muted);
                font-weight: 900;
                font-size: 11px;
                flex: 0 0 84px;
            }
            .actions-col{ justify-content:flex-end; }
        }
    </style>
</head>

<body>
<div class="app">
    <div class="overlay" onclick="toggleSidebar(false)"></div>

    {{-- SIDEBAR (SAMA PERSIS KAYAK DASHBOARD) --}}
    <aside class="sidebar">
        <div class="brand">
            <div class="logo">TM</div>
            <div>
                <h2>TumbuhMaju</h2>
                <p>Admin Console</p>
            </div>
        </div>

        <div class="side-search">
            <span class="icon">🔎</span>
            <input type="text" placeholder="Search menu…" oninput="filterMenu(this.value)" />
        </div>

        <nav class="nav" id="navMenu">
            <div class="nav-section">Management</div>

            <a href="/admin/users" data-label="users kelola user vip">
                <div class="bullet">👥</div>
                <div class="meta">
                    <b>Users</b>
                    <span>Kelola user & VIP</span>
                </div>
            </a>

            <a href="/admin/vip" data-label="vip settings aturan level">
                <div class="bullet">⭐</div>
                <div class="meta">
                    <b>VIP Settings</b>
                    <span>Aturan level VIP</span>
                </div>
            </a>

            <a href="/admin/products" data-label="products produk tier">
                <div class="bullet">📦</div>
                <div class="meta">
                    <b>Products</b>
                    <span>Produk & tier</span>
                </div>
            </a>

            <a href="/admin/deposits" data-label="deposits riwayat isi saldo">
                <div class="bullet">💰</div>
                <div class="meta">
                    <b>Deposits</b>
                    <span>Riwayat isi saldo</span>
                </div>
            </a>

            <a href="{{ route('admin.forum.index') }}" data-label="forum posts comments">
                <div class="bullet">💬</div>
                <div class="meta">
                    <b>Forum</b>
                    <span>Posting & komentar user</span>
                </div>
            </a>

            <a href="{{ route('admin.withdraw.page') }}" data-label="withdraw penarikan permintaan">
                <div class="bullet">⬆️</div>
                <div class="meta">
                    <b>Withdraw</b>
                    <span>Permintaan penarikan</span>
                </div>
            </a>

            <a href="{{ route('admin.referral') }}" data-label="referral komisi invite">
                <div class="bullet">🎁</div>
                <div class="meta">
                    <b>Referral</b>
                    <span>Overview, users, komisi</span>
                </div>
            </a>

            <a href="/admin/logs" data-label="logs aktivitas sistem">
                <div class="bullet">📜</div>
                <div class="meta">
                    <b>Logs</b>
                    <span>Aktivitas sistem</span>
                </div>
            </a>

            <div class="nav-section">System</div>

            <a href="/" data-label="home kembali ke website">
                <div class="bullet">🏠</div>
                <div class="meta">
                    <b>Back to Site</b>
                    <span>Kembali ke website</span>
                </div>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="who">
                <div class="avatar">{{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}</div>
                <div class="name">
                    <b>{{ $user->name }}</b>
                    <span>Administrator</span>
                </div>
            </div>
        </div>
    </aside>

    {{-- MAIN --}}
    <main class="main">
        <div class="topbar">
            <button class="hamburger" type="button" aria-label="Open menu" onclick="toggleSidebar(true)">☰</button>

            <div class="topbar-left">
                <h1 class="page-title">Produk</h1>
                <div class="subtitle">Kelola produk investasi (Reguler / Harian / Premium).</div>
            </div>

            <div class="topbar-right">
                <div class="pill" title="System status">
                    <span class="dot"></span>
                    <span>System Online</span>
                </div>

                <a href="/admin" class="btn">← Kembali</a>
                <a href="/admin/products/create" class="btn btn-primary">+ Tambah Produk</a>

                <form action="/logout" method="POST" style="margin:0">
                    @csrf
                    <button class="btn btn-danger" type="submit">Logout</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>VIP Min</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($products as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td>{{ $p->category->name }}</td>
                            <td>Rp {{ number_format($p->price,0,',','.') }}</td>
                            <td>VIP {{ $p->min_vip_level }}</td>
                            <td>
                                @if($p->is_active)
                                    <span class="badge green">Aktif</span>
                                @else
                                    <span class="badge red">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions-col">
                                    <a href="/admin/products/{{ $p->id }}/edit" class="btn btn-gray">Edit</a>

                                    <form method="POST" action="/admin/products/{{ $p->id }}/toggle" style="display:inline">
                                        @csrf
                                        <button class="danger-link" type="submit">
                                            {{ $p->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
    // Active nav berdasarkan URL (sama seperti dashboard)
    (function setActiveNav(){
        const path = window.location.pathname;
        const links = document.querySelectorAll('#navMenu a');
        links.forEach(a => {
            const href = a.getAttribute('href');
            if (!href) return;
            const isActive = (href !== '/' && path.startsWith(href)) || (href === path);
            if (isActive) a.classList.add('active');
        });
    })();

    // Search menu sidebar
    function filterMenu(q){
        q = (q || '').toLowerCase().trim();
        const links = document.querySelectorAll('#navMenu a');
        links.forEach(a => {
            const label = (a.getAttribute('data-label') || a.textContent || '').toLowerCase();
            a.style.display = !q || label.includes(q) ? 'flex' : 'none';
        });
    }

    // Drawer mobile
    function toggleSidebar(open){
        const shouldOpen = open === true;
        document.body.classList.toggle('sidebar-open', shouldOpen);
    }

    (function bindSidebarClose(){
        const nav = document.getElementById('navMenu');
        if (!nav) return;

        nav.addEventListener('click', (e) => {
            const a = e.target.closest('a');
            if (!a) return;
            if (window.matchMedia('(max-width: 860px)').matches){
                toggleSidebar(false);
            }
        });

        window.addEventListener('resize', () => {
            if (!window.matchMedia('(max-width: 860px)').matches){
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
