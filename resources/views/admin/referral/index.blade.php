@php
    $user = auth()->user();
    $tab = $tab ?? request('tab', 'overview');

    function rupiah($n){
        return 'Rp ' . number_format((float)$n, 0, ',', '.');
    }
    function maskPhone($p){
        $p = (string)$p;
        if (strlen($p) <= 6) return $p;
        return substr($p,0,4) . '****' . substr($p,-3);
    }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Admin Referral | TumbuhMaju</title>

    {{-- Pakai CSS admin kamu (copy dari dashboard admin kalau belum ada layout) --}}
    <style>
        :root{
            --bg:#f5f7fb; --card:#ffffff; --text:#0f172a; --muted:#64748b; --border:#e7ebf3;
            --primary:#2563eb; --primary-2:#1d4ed8; --shadow: 0 18px 45px rgba(15,23,42,.08);
            --shadow-soft: 0 10px 22px rgba(15,23,42,.06); --radius:18px; --radius-sm:14px;
            --sidebar:#0b1220; --sidebar-2:#0f1a30; --sidebar-text:#dbe7ff; --sidebar-muted:#9db3dd;
            --chip:#eef2ff;
        }
        *{box-sizing:border-box;}
        html,body{height:100%;}
        body{
            margin:0;
            font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            color:var(--text);
            background: radial-gradient(1200px 600px at 10% 0%, #eaf0ff 0%, transparent 60%),
                        radial-gradient(900px 500px at 90% 10%, #e9fbff 0%, transparent 55%),
                        var(--bg);
        }
        a{color:inherit;text-decoration:none;}
        .app{min-height:100vh;display:grid;grid-template-columns:280px 1fr;}
        .sidebar{
            position:sticky;top:0;height:100vh;padding:22px 16px;
            background: linear-gradient(180deg, var(--sidebar) 0%, var(--sidebar-2) 100%);
            border-right: 1px solid rgba(255,255,255,.06); color:var(--sidebar-text);
        }
        .brand{display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:16px;}
        .logo{width:42px;height:42px;border-radius:14px;background: linear-gradient(135deg,#60a5fa 0%,#2563eb 45%,#22c55e 100%);
              box-shadow: 0 14px 30px rgba(37,99,235,.25);display:grid;place-items:center;color:#fff;font-weight:800;letter-spacing:.5px;}
        .brand h2{margin:0;font-size:15px;line-height:1.1;}
        .brand p{margin:4px 0 0;font-size:12px;color:var(--sidebar-muted);}
        .nav{margin-top:14px;padding:0 6px;}
        .nav-section{margin:14px 8px 8px;font-size:11px;letter-spacing:.12em;color:rgba(219,231,255,.55);text-transform:uppercase;}
        .nav a{display:flex;align-items:center;gap:10px;padding:12px 12px;border-radius:14px;margin:6px 6px;color:var(--sidebar-text);border:1px solid transparent;transition:.18s ease;}
        .nav a:hover{background: rgba(255,255,255,.07);border-color: rgba(255,255,255,.08);transform: translateY(-1px);}
        .nav a.active{background: rgba(37,99,235,.22);border-color: rgba(96,165,250,.40);box-shadow: 0 12px 24px rgba(37,99,235,.14);}
        .nav .bullet{width:34px;height:34px;border-radius:12px;display:grid;place-items:center;background: rgba(255,255,255,.06);}
        .nav .meta{display:flex;flex-direction:column;line-height:1.15;}
        .nav .meta b{font-size:13px;font-weight:700;}
        .nav .meta span{font-size:11.5px;color:rgba(219,231,255,.65);margin-top:3px;}
        .sidebar-footer{position:absolute;left:16px;right:16px;bottom:18px;background: rgba(255,255,255,.06);
            border:1px solid rgba(255,255,255,.08);border-radius:16px;padding:12px;}
        .who{display:flex;gap:10px;align-items:center;}
        .avatar{width:38px;height:38px;border-radius:14px;background: rgba(255,255,255,.12);display:grid;place-items:center;font-weight:800;}

        .main{padding:26px;}
        .topbar{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:18px;}
        .title{margin:0;font-size:22px;letter-spacing:-0.02em;}
        .subtitle{color:var(--muted);font-size:13px;}
        .pill{display:flex;align-items:center;gap:8px;padding:10px 12px;background: var(--card);border:1px solid var(--border);border-radius:999px;box-shadow: var(--shadow-soft);font-size:13px;color:var(--muted);}
        .dot{width:9px;height:9px;border-radius:99px;background:#22c55e;box-shadow: 0 0 0 4px rgba(34,197,94,.14);}
        .btn{border:1px solid var(--border);background: var(--card);color: var(--text);padding:10px 12px;border-radius:12px;cursor:pointer;box-shadow: var(--shadow-soft);transition:.18s ease;font-size:13px;font-weight:700;}
        .btn-danger{border-color: rgba(239,68,68,.35);color:#ef4444;}
        .btn:hover{transform: translateY(-1px);}

        .panel{background: var(--card);border:1px solid var(--border);border-radius: var(--radius);box-shadow: var(--shadow);padding:18px;}

        .tabs{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:14px;}
        .tab{
            padding:10px 12px;border-radius:999px;border:1px solid var(--border);background:#fff;
            box-shadow: var(--shadow-soft);font-weight:900;font-size:13px;color:var(--muted);
        }
        .tab.active{background: rgba(37,99,235,.10);border-color: rgba(37,99,235,.22);color: var(--primary);}

        .cards{display:grid;grid-template-columns: repeat(4, 1fr);gap:12px;margin-top:12px;}
        .stat{background: linear-gradient(180deg,#fff 0%,#fbfcff 100%);border:1px solid var(--border);border-radius: 16px;padding:14px;box-shadow: var(--shadow-soft);}
        .stat .k{display:flex;justify-content:space-between;font-size:12px;color:var(--muted);}
        .stat .v{font-size:20px;font-weight:900;margin-top:8px;}

        .table-wrap{overflow:auto;border:1px solid var(--border);border-radius: 16px;margin-top:12px;}
        table{width:100%;border-collapse:separate;border-spacing:0;min-width: 900px;background:#fff;}
        thead th{text-align:left;font-size:12px;color: var(--muted);padding:12px 14px;border-bottom:1px solid var(--border);background:#fbfcff;}
        tbody td{padding:12px 14px;border-bottom:1px solid var(--border);font-size:13px;}
        tbody tr:hover td{background:#fafcff;}

        .filters{display:flex;gap:10px;flex-wrap:wrap;margin-top:10px;}
        .filters input, .filters select{
            padding:10px 12px;border:1px solid var(--border);border-radius:12px;background:#fff;outline:none;
        }
        .muted{color:var(--muted);font-size:12px;}
        .hr{height:1px;background:var(--border);margin:14px 0;}
    </style>
</head>

<body>
<div class="app">
    <aside class="sidebar">
        <div class="brand">
            <div class="logo">TM</div>
            <div>
                <h2>TumbuhMaju</h2>
                <p>Admin Console</p>
            </div>
        </div>

        <nav class="nav" id="navMenu">
            <div class="nav-section">Management</div>

            <a href="/admin/users">
                <div class="bullet">👥</div><div class="meta"><b>Users</b><span>Kelola user & VIP</span></div>
            </a>
            <a href="/admin/vip">
                <div class="bullet">⭐</div><div class="meta"><b>VIP Settings</b><span>Aturan level VIP</span></div>
            </a>
            <a href="/admin/products">
                <div class="bullet">📦</div><div class="meta"><b>Products</b><span>Produk & tier</span></div>
            </a>
            <a href="/admin/deposits">
                <div class="bullet">💰</div><div class="meta"><b>Deposits</b><span>Riwayat isi saldo</span></div>
            </a>
            <a href="{{ route('admin.withdraw.page') }}">
                <div class="bullet">⬆️</div><div class="meta"><b>Withdraw</b><span>Permintaan penarikan</span></div>
            </a>

            <a href="{{ route('admin.referral') }}" class="active">
                <div class="bullet">🎁</div><div class="meta"><b>Referral</b><span>Overview, users, komisi</span></div>
            </a>

            <a href="/admin/logs">
                <div class="bullet">📜</div><div class="meta"><b>Logs</b><span>Aktivitas sistem</span></div>
            </a>

            <div class="nav-section">System</div>
            <a href="/"><div class="bullet">🏠</div><div class="meta"><b>Back to Site</b><span>Kembali ke website</span></div></a>
        </nav>

        <div class="sidebar-footer">
            <div class="who">
                <div class="avatar">{{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}</div>
                <div>
                    <b style="font-size:13px">{{ $user->name }}</b><br>
                    <span style="font-size:11.5px;color:rgba(219,231,255,.70)">Administrator</span>
                </div>
            </div>
        </div>
    </aside>

    <main class="main">
        <div class="topbar">
            <div>
                <h1 class="title">Referral</h1>
                <div class="subtitle">Kelola & audit sistem referral (deposit 5%, buy 3%)</div>
            </div>

            <div style="display:flex;gap:10px;align-items:center;">
                <div class="pill"><span class="dot"></span><span>System Online</span></div>
                <form action="/logout" method="POST" style="margin:0">
                    @csrf
                    <button class="btn btn-danger" type="submit">Logout</button>
                </form>
            </div>
        </div>

        <section class="panel">
            <div class="tabs">
                <a class="tab {{ $tab==='overview' ? 'active' : '' }}" href="{{ route('admin.referral', ['tab'=>'overview']) }}">Overview</a>
                <a class="tab {{ $tab==='users' ? 'active' : '' }}" href="{{ route('admin.referral', ['tab'=>'users']) }}">Users</a>
                <a class="tab {{ $tab==='commissions' ? 'active' : '' }}" href="{{ route('admin.referral', ['tab'=>'commissions']) }}">Commissions</a>
            </div>

            {{-- TAB: OVERVIEW --}}
            @if($tab==='overview')
                <div class="muted">Ringkasan referral dan performa komisi.</div>

                <div class="cards">
                    <div class="stat">
                        <div class="k"><span>Total Users</span><span class="muted">role=user</span></div>
                        <div class="v">{{ $totalUsers }}</div>
                    </div>
                    <div class="stat">
                        <div class="k"><span>User Ter-refer</span><span class="muted">punya referrer</span></div>
                        <div class="v">{{ $totalReferredUsers }}</div>
                    </div>
                    <div class="stat">
                        <div class="k"><span>Total Komisi</span><span class="muted">lifetime</span></div>
                        <div class="v">{{ rupiah($totalCommission) }}</div>
                    </div>
                    <div class="stat">
                        <div class="k"><span>Komisi 7 Hari</span><span class="muted">rolling</span></div>
                        <div class="v">{{ rupiah($commission7d) }}</div>
                    </div>
                </div>

                <div class="hr"></div>

                <div style="display:flex;gap:18px;flex-wrap:wrap;">
                    <div style="flex:1;min-width:320px;">
                        <b>Breakdown Sumber Komisi</b>
                        <div class="muted">deposit vs buy</div>
                        <div style="margin-top:10px;">
                            <div>Deposit: <b>{{ rupiah($breakdown['deposit'] ?? 0) }}</b></div>
                            <div>Buy: <b>{{ rupiah($breakdown['buy'] ?? 0) }}</b></div>
                            <div class="muted" style="margin-top:6px;">Hari ini: <b>{{ rupiah($commissionToday) }}</b></div>
                        </div>
                    </div>

                    <div style="flex:2;min-width:420px;">
                        <b>Top Referrers</b>
                        <div class="muted">10 terbesar berdasarkan total komisi</div>

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
                                        <td>{{ $i+1 }}</td>
                                        <td>{{ $r->referrer_name }}</td>
                                        <td>{{ maskPhone($r->referrer_phone) }}</td>
                                        <td>{{ $r->total_trx }}</td>
                                        <td><b>{{ rupiah($r->total_commission) }}</b></td>
                                        <td>
                                            <a class="btn" href="{{ route('admin.referral', ['tab'=>'users','referrer_id'=>$r->referrer_id]) }}">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="muted">Belum ada data komisi.</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            {{-- TAB: USERS --}}
            @if($tab==='users')
                <div class="muted">Daftar referrer dan user yang mereka ajak.</div>

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
                        @foreach($referrers as $u)
                            <tr>
                                <td><b>{{ $u->name }}</b></td>
                                <td>{{ maskPhone($u->phone) }}</td>
                                <td>{{ $u->referral_code ?? '-' }}</td>
                                <td>{{ $u->referrals_count }}</td>
                                <td>{{ rupiah($u->referral_earned_total ?? 0) }}</td>
                                <td>{{ \Carbon\Carbon::parse($u->created_at)->format('d M Y') }}</td>
                                <td>
                                    <a class="btn" href="{{ route('admin.referral', ['tab'=>'users', 'referrer_id'=>$u->id]) }}#detail">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="margin-top:12px;">
                    {{ $referrers->links() }}
                </div>

                @if($referrerDetail)
                    <div id="detail" class="hr"></div>
                    <h3 style="margin:0 0 6px;">Detail Referrer: {{ $referrerDetail->name }}</h3>
                    <div class="muted">
                        Phone: {{ maskPhone($referrerDetail->phone) }} |
                        Kode: <b>{{ $referrerDetail->referral_code ?? '-' }}</b> |
                        Earned: <b>{{ rupiah($referrerDetail->referral_earned_total ?? 0) }}</b>
                    </div>

                    <div style="display:flex;gap:16px;flex-wrap:wrap;margin-top:12px;">
                        <div style="flex:1;min-width:380px;">
                            <b>Referred Users (max 100)</b>
                            <div class="table-wrap">
                                <table>
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
                                            <td>{{ $ru->name }}</td>
                                            <td>{{ maskPhone($ru->phone) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($ru->created_at)->format('d M Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="muted">Belum ada user yang daftar pakai kode ini.</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div style="flex:1;min-width:480px;">
                            <b>Komisi Terbaru (max 50)</b>
                            <div class="table-wrap">
                                <table>
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
                                            <td>{{ \Carbon\Carbon::parse($c->created_at)->format('d M Y H:i') }}</td>
                                            <td>{{ strtoupper($c->source_type) }}</td>
                                            <td>{{ $c->referred_name ?? '-' }} ({{ maskPhone($c->referred_phone ?? '-') }})</td>
                                            <td>{{ rupiah($c->base_amount) }}</td>
                                            <td>{{ (float)$c->rate * 100 }}%</td>
                                            <td><b>{{ rupiah($c->commission_amount) }}</b></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="muted">Belum ada komisi masuk.</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div style="margin-top:10px;">
                                <a class="btn" href="{{ route('admin.referral', ['tab'=>'commissions','referrer_id'=>$referrerDetail->id]) }}">Buka di tab Commissions →</a>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            {{-- TAB: COMMISSIONS --}}
            @if($tab==='commissions')
                <div class="muted">Audit transaksi komisi referral (filterable).</div>

                <form method="GET" action="{{ route('admin.referral') }}">
                    <input type="hidden" name="tab" value="commissions">
                    <div class="filters">
                        <select name="source_type">
                            <option value="">All source</option>
                            <option value="deposit" {{ request('source_type')==='deposit' ? 'selected' : '' }}>deposit</option>
                            <option value="buy" {{ request('source_type')==='buy' ? 'selected' : '' }}>buy</option>
                        </select>

                        <input type="number" name="referrer_id" placeholder="referrer_id" value="{{ request('referrer_id') }}">
                        <input type="number" name="referred_user_id" placeholder="referred_user_id" value="{{ request('referred_user_id') }}">
                        <input type="date" name="date_from" value="{{ request('date_from') }}">
                        <input type="date" name="date_to" value="{{ request('date_to') }}">

                        <button class="btn" type="submit">Filter</button>
                        <a class="btn" href="{{ route('admin.referral', ['tab'=>'commissions']) }}">Reset</a>
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
                                <td>{{ \Carbon\Carbon::parse($c->created_at)->format('d M Y H:i') }}</td>
                                <td>{{ $c->referrer_name }} ({{ maskPhone($c->referrer_phone) }})</td>
                                <td>{{ $c->referred_name }} ({{ maskPhone($c->referred_phone) }})</td>
                                <td><b>{{ strtoupper($c->source_type) }}</b></td>
                                <td>#{{ $c->source_id }}</td>
                                <td>{{ rupiah($c->base_amount) }}</td>
                                <td>{{ (float)$c->rate * 100 }}%</td>
                                <td><b>{{ rupiah($c->commission_amount) }}</b></td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="muted">Belum ada data komisi.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top:12px;">
                    {{ $commissions->links() }}
                </div>
            @endif
        </section>
    </main>
</div>
</body>
</html>
