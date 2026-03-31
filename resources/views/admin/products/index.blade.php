@php
    $user = auth()->user();
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Products | TumbuhMaju</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        :root{
            --bg:#f5f7fb;
            --card:#ffffff;
            --text:#0f172a;
            --muted:#64748b;
            --border:#e7ebf3;
            --primary:#2563eb;
            --danger:#ef4444;
            --shadow: 0 18px 45px rgba(15,23,42,.08);
            --shadow-soft: 0 10px 22px rgba(15,23,42,.06);
            --radius:18px;
        }

        *{ box-sizing:border-box; }
        body{
            margin:0;
            font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            color:var(--text);
            background: radial-gradient(1200px 600px at 10% 0%, #eaf0ff 0%, transparent 60%),
                        radial-gradient(900px 500px at 90% 10%, #e9fbff 0%, transparent 55%),
                        var(--bg);
        }
        a{ text-decoration:none; color:inherit; }

        .wrap{
            max-width:1100px;
            margin:34px auto;
            padding:22px;
        }

        .card{
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding:18px;
        }

        .topbar{
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            gap:12px;
            margin-bottom: 14px;
        }

        .title{
            margin:0;
            font-size: 20px;
            letter-spacing: -0.02em;
            line-height:1.2;
        }
        .muted{
            margin-top:6px;
            color:var(--muted);
            font-size:13px;
            line-height:1.45;
        }

        .actions{
            display:flex;
            align-items:center;
            gap:10px;
            flex-wrap:wrap;
            justify-content:flex-end;
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

        /* RESPONSIVE: Mobile => table jadi card list */
        @media (max-width: 860px){
            .wrap{ padding: 16px; }
            .card{ padding: 14px; }

            .topbar{
                flex-direction:column;
                align-items:flex-start;
                gap:10px;
            }
            .actions{
                width:100%;
                justify-content:flex-start;
            }

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

            .actions-col{
                justify-content:flex-end;
            }
        }
    </style>
</head>

<body>
<div class="wrap">
    <div class="card">


        <div class="topbar">
            <div>
                <h1 class="title">Produk</h1>
                <div class="muted">
                    Kelola produk investasi (Reguler / Harian / Premium).
                </div>
            </div>

            <div class="actions">
                <a href="/admin" class="btn">← Kembali</a>
                <a href="/admin/products/create" class="btn btn-primary">+ Tambah Produk</a>
            </div>
        </div>

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
</div>
</body>
</html>
