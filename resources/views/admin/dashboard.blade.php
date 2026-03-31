@php
    $user = auth()->user();
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    
    <title>Admin Panel | CROWDNIK</title>

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
        a{ color:inherit; text-decoration:none; }
        button{ font-family:inherit; }

        /* LAYOUT */
        .app{
            min-height:100vh;
            display:grid;
            grid-template-columns: 280px 1fr;
        }

        /* SIDEBAR */
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

        /* MAIN */
        .main{
            padding:26px;
        }

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
        .title{
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
            font-weight:700;
        }
        .btn:hover{ transform: translateY(-1px); }
        .btn-danger{
            border-color: rgba(239,68,68,.35);
            color: var(--danger);
        }

        /* GRID CONTENT */
        .grid{
            display:grid;
            grid-template-columns: 1.2fr .8fr;
            gap:18px;
        }

        .panel{
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding:18px;
        }

        .cards{
            display:grid;
            grid-template-columns: repeat(4, 1fr);
            gap:12px;
            margin-bottom:16px;
        }
        .stat{
            background: linear-gradient(180deg, #ffffff 0%, #fbfcff 100%);
            border:1px solid var(--border);
            border-radius: 16px;
            padding:14px;
            box-shadow: var(--shadow-soft);
            min-height:86px;
        }
        .stat .k{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
            font-size:12px;
            color:var(--muted);
        }
        .stat .v{
            font-size:20px;
            font-weight:900;
            margin-top:8px;
            letter-spacing:-0.02em;
        }
        .badge{
            font-size:11px;
            padding:6px 8px;
            border-radius:999px;
            background: var(--chip);
            color: var(--primary);
            border:1px solid rgba(37,99,235,.12);
            font-weight:800;
            white-space:nowrap;
        }

        .section-head{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            margin-bottom:12px;
        }
        .section-head h3{
            margin:0;
            font-size:14px;
        }
        .section-head .hint{
            color:var(--muted);
            font-size:12px;
        }

        /* MENU QUICK ACTIONS */
        .quick{
            display:grid;
            grid-template-columns: repeat(3, 1fr);
            gap:12px;
        }
        .qa{
            border:1px solid var(--border);
            border-radius: 16px;
            padding:14px;
            background: #fff;
            box-shadow: var(--shadow-soft);
            transition:.18s ease;
            display:flex;
            gap:12px;
            align-items:flex-start;
            min-height:86px;
        }
        .qa:hover{
            transform: translateY(-2px);
            border-color: rgba(37,99,235,.25);
            box-shadow: 0 14px 26px rgba(37,99,235,.10);
        }
        .qa .ic{
            width:40px;height:40px;
            border-radius:14px;
            display:grid;
            place-items:center;
            background: rgba(37,99,235,.08);
            color: var(--primary);
            font-size:18px;
            flex: 0 0 auto;
        }
        .qa .t{
            display:flex;
            flex-direction:column;
            gap:5px;
        }
        .qa .t b{ font-size:13px; }
        .qa .t span{ font-size:12px; color:var(--muted); line-height:1.35; }

        /* TABLE */
        .table-wrap{
            overflow:auto;
            border:1px solid var(--border);
            border-radius: 16px;
        }
        table{
            width:100%;
            border-collapse:separate;
            border-spacing:0;
            min-width: 760px;
            background:#fff;
        }
        thead th{
            text-align:left;
            font-size:12px;
            color: var(--muted);
            padding:12px 14px;
            border-bottom:1px solid var(--border);
            background: #fbfcff;
        }
        tbody td{
            padding:12px 14px;
            border-bottom:1px solid var(--border);
            font-size:13px;
        }
        tbody tr:hover td{
            background:#fafcff;
        }
        .status{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:6px 10px;
            border-radius:999px;
            font-size:12px;
            font-weight:800;
            border:1px solid;
        }
        .st-ok{ background: rgba(34,197,94,.10); color:#16a34a; border-color: rgba(34,197,94,.25); }
        .st-warn{ background: rgba(245,158,11,.10); color:#b45309; border-color: rgba(245,158,11,.25); }
        .st-bad{ background: rgba(239,68,68,.10); color:#dc2626; border-color: rgba(239,68,68,.25); }

        .right-panel{
            display:flex;
            flex-direction:column;
            gap:18px;
        }

        .mini-actions{
            display:grid;
            grid-template-columns: 1fr 1fr;
            gap:12px;
        }
        .mini{
            border:1px solid var(--border);
            border-radius: 16px;
            padding:14px;
            background:#fff;
            box-shadow: var(--shadow-soft);
        }
        .mini b{ font-size:13px; }
        .mini p{ margin:6px 0 0; color:var(--muted); font-size:12px; line-height:1.45; }

        .link{
            display:inline-flex;
            align-items:center;
            gap:8px;
            margin-top:10px;
            color:var(--primary);
            font-weight:800;
            font-size:12.5px;
        }

        /* RESPONSIVE */
        @media (max-width: 1100px){
            .cards{ grid-template-columns: repeat(2, 1fr); }
            .grid{ grid-template-columns: 1fr; }
        }
        @media (max-width: 860px){
            .app{ grid-template-columns: 1fr; }
            .sidebar{
                position:relative;
                height:auto;
                border-right:none;
                border-bottom:1px solid rgba(255,255,255,.06);
            }
            .sidebar-footer{ position:relative; left:auto; right:auto; bottom:auto; margin-top:14px; }
            .quick{ grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 520px){
            .main{ padding:16px; }
            .cards{ grid-template-columns: 1fr; }
            .quick{ grid-template-columns: 1fr; }
            .mini-actions{ grid-template-columns: 1fr; }
        }
        /* ===== MOBILE OFFCANVAS SIDEBAR ===== */
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

body.sidebar-open{
  overflow:hidden; /* lock scroll */
}

/* Override aturan lama max-width 860px */
@media (max-width: 860px){
  .app{
    grid-template-columns: 1fr; /* main full */
  }

  /* Topbar lebih rapat */
  .topbar{
    position:sticky;
    top:0;
    z-index:20;
    background: rgba(245,247,251,.85);
    backdrop-filter: blur(6px);
    border-radius: 16px;
    padding: 12px;
    border: 1px solid var(--border);
  }

  .hamburger{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    margin-right:10px;
  }

  /* Sidebar jadi drawer */
  .sidebar{
    position:fixed;          /* penting: bukan relative */
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

  body.sidebar-open .sidebar{
    transform: translateX(0);
  }

  /* overlay aktif */
  body.sidebar-open .overlay{
    display:block;
    opacity:1;
  }

  /* footer sidebar tetap nempel bawah */
  .sidebar-footer{
    position:absolute;
    left:16px;
    right:16px;
    bottom:18px;
    margin-top:0;
  }

  /* quick actions 2 kolom biar nyaman */
  .quick{
    grid-template-columns: repeat(2, 1fr);
  }
}

/* Extra small: quick actions 1 kolom */
@media (max-width: 520px){
  .quick{ grid-template-columns: 1fr; }

  /* pills + logout biar gak kepotong */
  .topbar{
    gap:10px;
  }
  .topbar-right{
    flex-wrap:wrap;
    justify-content:flex-start;
  }
}

/* ====== MOBILE UI POLISH (ADMIN MAIN) ====== */
@media (max-width: 860px){
  /* main padding lebih kecil */
  .main{
    padding: 14px;
  }

  /* topbar lebih compact & enak */
  .topbar{
    padding: 10px 12px;
    border-radius: 16px;
    gap: 10px;
  }
  .topbar-left{
    gap: 2px;
  }
  .title{
    font-size: 18px;
    line-height: 1.15;
  }
  .subtitle{
    font-size: 12px;
  }
  .topbar-right{
    gap: 8px;
  }
  .pill{
    padding: 8px 10px;
    font-size: 12px;
  }
  .btn{
    padding: 9px 10px;
    font-size: 12px;
    border-radius: 12px;
  }

  /* grid konten jadi 1 kolom rapi */
  .grid{
    grid-template-columns: 1fr;
    gap: 12px;
  }
  .panel{
    padding: 14px;
    border-radius: 18px;
  }

  /* STAT CARDS: 1 kolom atau 2 kolom tergantung lebar */
  .cards{
    grid-template-columns: 1fr;
    gap: 10px;
    margin-bottom: 12px;
  }
}

@media (min-width: 520px) and (max-width: 860px){
  /* tablet kecil: stat cards 2 kolom */
  .cards{
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 520px){
  /* stat card lebih pendek */
  .stat{
    padding: 12px;
    min-height: 78px;
    border-radius: 16px;
  }
  .stat .k{
    font-size: 11.5px;
  }
  .stat .v{
    font-size: 18px;
    margin-top: 6px;
  }
  .badge{
    font-size: 10.5px;
    padding: 5px 8px;
  }

  /* Quick actions jadi 1 kolom biar readable */
  .quick{
    grid-template-columns: 1fr !important;
    gap: 10px;
  }
  .qa{
    padding: 12px;
    border-radius: 16px;
    min-height: auto;
  }
  .qa .ic{
    width: 38px;
    height: 38px;
    border-radius: 14px;
    font-size: 17px;
  }
  .qa .t b{
    font-size: 13px;
  }
  .qa .t span{
    font-size: 12px;
  }

  /* Section headings lebih rapat */
  .section-head{
    margin-bottom: 10px;
  }
  .section-head h3{
    font-size: 13.5px;
  }
  .section-head .hint{
    font-size: 11.5px;
  }

  /* TABLE: ubah jadi "mobile cards" (tanpa ubah HTML) */
  .table-wrap{
    border-radius: 16px;
    overflow: visible; /* kita pakai mode cards, bukan scroll horizontal */
    border: none;
    background: transparent;
  }
  table{
    min-width: 0;      /* stop forcing 760px */
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px; /* jarak antar card */
    background: transparent;
  }
  thead{
    display: none; /* header hilang di mobile */
  }
  tbody tr{
    display: block;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 16px;
    box-shadow: var(--shadow-soft);
    overflow: hidden;
  }
  tbody td{
    display: flex;
    justify-content: space-between;
    gap: 14px;
    padding: 10px 12px;
    border-bottom: 1px solid var(--border);
    white-space: normal;
    font-size: 12.5px;
  }
  tbody td:last-child{
    border-bottom: none;
  }

  /* label per kolom berdasarkan urutan table Anda:
     Module | Action | Status | Time  */
  tbody td:nth-child(1)::before{ content:"Module"; }
  tbody td:nth-child(2)::before{ content:"Action"; }
  tbody td:nth-child(3)::before{ content:"Status"; }
  tbody td:nth-child(4)::before{ content:"Time"; }

  tbody td::before{
    color: var(--muted);
    font-weight: 800;
    font-size: 11px;
    flex: 0 0 84px;
  }

  /* status chip lebih kecil */
  .status{
    font-size: 11px;
    padding: 6px 9px;
  }

  /* Right panel mini cards rapihin */
  .mini-actions{
    grid-template-columns: 1fr;
    gap: 10px;
  }
  .mini{
    border-radius: 16px;
    padding: 12px;
  }
  .link{
    font-size: 12px;
  }
}

    </style>
</head>

<body>
<div class="app">
    <div class="overlay" onclick="toggleSidebar(false)"></div>

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="brand">
            <div class="logo">CW</div>
            <div>
                <h2>Crowdink</h2>
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
                <h1 class="title">Admin Panel</h1> 
                
                <div class="subtitle">
                    Selamat datang kembali, <b>{{ $user->name }}</b>. Kelola sistem Anda dari satu tempat.
                </div>
            </div>

            <div class="topbar-right">
                <div class="pill" title="System status">
                    <span class="dot"></span>
                    <span>System Online</span>
                </div>

                <form action="/logout" method="POST" style="margin:0">
                    @csrf
                    <button class="btn btn-danger" type="submit">Logout</button>
                </form>
            </div>
        </div>

        <div class="grid">

            {{-- LEFT --}}
            <section class="panel">
                {{-- STAT CARDS (placeholder; sambungkan ke data Anda nanti) --}}
                <div class="cards">
                    <div class="stat">
                        <div class="k">
                            <span>Total Users</span>
                            <span class="badge">+ this month</span>
                        </div>
                        <div class="v" id="statUsers">—</div>
                    </div>

                    <div class="stat">
                        <div class="k">
                            <span>Products</span>
                            <span class="badge">active</span>
                        </div>
                        <div class="v" id="statProducts">—</div>
                    </div>

                    <div class="stat">
                        <div class="k">
                            <span>Deposits</span>
                            <span class="badge">today</span>
                        </div>
                        <div class="v" id="statDeposits">—</div>
                    </div>

                    <div class="stat">
                        <div class="k">
                            <span>Withdraw Queue</span>
                            <span class="badge">pending</span>
                        </div>
                        <div class="v" id="statWithdraw">—</div>
                    </div>
                </div>

                <div class="section-head">
                    <div>
                        <h3>Quick Actions</h3>
                        <div class="hint">Akses cepat ke menu yang paling sering dipakai</div>
                    </div>
                </div>

                <div class="quick">
                    <a class="qa" href="/admin/users">
                        <div class="ic">👥</div>
                        <div class="t">
                            <b>Kelola Users</b>
                            <span>Update user, VIP, dan kontrol akses.</span>
                        </div>
                    </a>

                    <a class="qa" href="/admin/products">
                        <div class="ic">📦</div>
                        <div class="t">
                            <b>Kelola Products</b>
                            <span>Tambah/ubah produk & tier dengan cepat.</span>
                        </div>
                    </a>

                    <a class="qa" href="/admin/deposits">
                        <div class="ic">💰</div>
                        <div class="t">
                            <b>Riwayat Deposits</b>
                            <span>Audit top up & status pembayaran.</span>
                        </div>
                    </a>

                    <a class="qa" href="{{ route('admin.withdraw.page') }}">
                        <div class="ic">⬆️</div>
                        <div class="t">
                            <b>Withdraw Requests</b>
                            <span>Proses antrian penarikan saldo.</span>
                        </div>
                    </a>

                    <a class="qa" href="/admin/vip">
                        <div class="ic">⭐</div>
                        <div class="t">
                            <b>VIP Settings</b>
                            <span>Atur aturan level & benefit VIP.</span>
                        </div>
                    </a>

                    <a class="qa" href="/admin/logs">
                        <div class="ic">📜</div>
                        <div class="t">
                            <b>System Logs</b>
                            <span>Monitor aktivitas & troubleshooting.</span>
                        </div>
                    </a>
                </div>

                <div style="height:16px"></div>

                <div class="section-head">
                    <div>
                        <h3>Recent Activity</h3>
                        <div class="hint">Contoh tabel (silakan sambungkan ke database/log Anda)</div>
                    </div>
                    <div class="hint">Last 7 entries</div>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th style="width:30%">Module</th>
                            <th>Action</th>
                            <th style="width:18%">Status</th>
                            <th style="width:18%">Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{-- Placeholder rows; ganti dengan loop data Anda --}}
                        <tr>
                            <td>Withdraw</td>
                            <td>New request created</td>
                            <td><span class="status st-warn">Pending</span></td>
                            <td>{{ now()->subMinutes(7)->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td>Deposits</td>
                            <td>Payment confirmed</td>
                            <td><span class="status st-ok">Success</span></td>
                            <td>{{ now()->subMinutes(18)->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td>Products</td>
                            <td>Tier updated</td>
                            <td><span class="status st-ok">Success</span></td>
                            <td>{{ now()->subHours(2)->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td>Users</td>
                            <td>VIP level changed</td>
                            <td><span class="status st-ok">Success</span></td>
                            <td>{{ now()->subHours(5)->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td>Logs</td>
                            <td>System check</td>
                            <td><span class="status st-ok">OK</span></td>
                            <td>{{ now()->subDay()->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td>Deposits</td>
                            <td>Callback received</td>
                            <td><span class="status st-ok">Success</span></td>
                            <td>{{ now()->subDays(2)->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td>Withdraw</td>
                            <td>Request rejected</td>
                            <td><span class="status st-bad">Rejected</span></td>
                            <td>{{ now()->subDays(3)->format('d M Y H:i') }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- RIGHT --}}
            <aside class="right-panel">
                <section class="panel">
                    <div class="section-head">
                        <h3>Shortcuts</h3>
                        <div class="hint">Panel ringkas</div>
                    </div>

                    <div class="mini-actions">
                        <div class="mini">
                            <b>Withdraw</b>
                            <p>Prioritaskan permintaan pending untuk mempercepat SLA.</p>
                            <a class="link" href="{{ route('admin.withdraw.page') }}">Open Withdraw →</a>
                        </div>

                        <div class="mini">
                            <b>Deposits</b>
                            <p>Audit transaksi yang statusnya belum final / mismatch.</p>
                            <a class="link" href="/admin/deposits">Open Deposits →</a>
                        </div>

                        <div class="mini">
                            <b>Products</b>
                            <p>Pastikan tier, pricing, dan stok selalu up-to-date.</p>
                            <a class="link" href="/admin/products">Open Products →</a>
                        </div>

                        <div class="mini">
                            <b>Logs</b>
                            <p>Telusuri error/aktivitas untuk troubleshooting cepat.</p>
                            <a class="link" href="/admin/logs">Open Logs →</a>
                        </div>
                    </div>
                </section>

                <section class="panel">
                    <div class="section-head">
                        <h3>Admin Notes</h3>
                        <div class="hint">Opsional</div>
                    </div>

                    <div style="color:var(--muted); font-size:13px; line-height:1.55">
                        Anda bisa sambungkan statistik di atas ke backend (query counts) dan “Recent Activity”
                        dari tabel logs / transaksi, agar panel ini menjadi benar-benar informatif seperti referensi.
                    </div>
                </section>
            </aside>

        </div>
    </main>
</div>

<script>
    // Set active nav item berdasarkan URL
    (function setActiveNav(){
        const path = window.location.pathname;
        const links = document.querySelectorAll('#navMenu a');
        links.forEach(a => {
            const href = a.getAttribute('href');
            if (!href) return;
            // active jika path sama persis atau path diawali href (untuk nested)
            const isActive = (href !== '/' && path.startsWith(href)) || (href === path);
            if (isActive) a.classList.add('active');
        });
    })();

    // Simple filter menu by search
    function filterMenu(q){
        q = (q || '').toLowerCase().trim();
        const links = document.querySelectorAll('#navMenu a');
        links.forEach(a => {
            const label = (a.getAttribute('data-label') || a.textContent || '').toLowerCase();
            a.style.display = !q || label.includes(q) ? 'flex' : 'none';
        });
    }

    // Placeholder stats (ganti dengan data real dari backend bila perlu)
    // Anda bisa isi dari Blade: window.__STATS__ = {...}
    (function(){
        const stats = window.__STATS__ || {
            users: "—",
            products: "—",
            deposits: "—",
            withdraw: "—"
        };

        document.getElementById('statUsers').textContent = stats.users;
        document.getElementById('statProducts').textContent = stats.products;
        document.getElementById('statDeposits').textContent = stats.deposits;
        document.getElementById('statWithdraw').textContent = stats.withdraw;
    })();
</script>
<script>
  function toggleSidebar(open){
    const shouldOpen = open === true;
    document.body.classList.toggle('sidebar-open', shouldOpen);
  }

  // Close drawer kalau klik link menu (di mobile)
  (function bindSidebarClose(){
    const nav = document.getElementById('navMenu');
    if (!nav) return;

    nav.addEventListener('click', (e) => {
      const a = e.target.closest('a');
      if (!a) return;

      // hanya mobile drawer
      if (window.matchMedia('(max-width: 860px)').matches){
        toggleSidebar(false);
      }
    });

    // close jika resize ke desktop agar state bersih
    window.addEventListener('resize', () => {
      if (!window.matchMedia('(max-width: 860px)').matches){
        toggleSidebar(false);
      }
    });

    // close dengan ESC
    window.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') toggleSidebar(false);
    });
  })();
</script>

</body>
</html>
