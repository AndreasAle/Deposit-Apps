@php
    $user = auth()->user();
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk | Admin TumbuhMaju</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        :root{
            --bg:#f5f7fb;
            --card:#ffffff;
            --text:#0f172a;
            --muted:#64748b;
            --border:#e7ebf3;
            --primary:#2563eb;
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
            max-width: 980px;
            margin: 34px auto;
            padding: 22px;
        }

        .card{
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 18px;
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

        .divider{
            height:1px;
            background: var(--border);
            margin: 12px 0 16px;
        }

        .section-title{
            font-size:12px;
            font-weight:900;
            letter-spacing:.12em;
            text-transform:uppercase;
            color: var(--muted);
            margin: 0 0 10px;
        }

        form{
            display:grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .field{
            border:1px solid var(--border);
            border-radius: 16px;
            padding: 12px;
            background:#fff;
            box-shadow: var(--shadow-soft);
        }

        label{
            display:block;
            font-size:12.5px;
            font-weight:900;
            color: var(--text);
            margin-bottom: 8px;
        }

        input, select{
            width:100%;
            padding: 12px 12px;
            border-radius: 14px;
            border: 1px solid var(--border);
            font-size: 13px;
            background:#fff;
            outline:none;
            transition: .15s ease;
        }
        input:focus, select:focus{
            border-color: rgba(37,99,235,.45);
            box-shadow: 0 0 0 3px rgba(37,99,235,.12);
        }

        .hint{
            margin-top:8px;
            font-size:12px;
            color: var(--muted);
            line-height:1.45;
        }

        .full{ grid-column: span 2; }

        .submit{
            width:100%;
            padding: 12px 14px;
            border-radius: 14px;
            border:1px solid rgba(37,99,235,.25);
            background: linear-gradient(135deg, rgba(37,99,235,.10), rgba(56,189,248,.10));
            color: var(--primary);
            font-weight:1000;
            cursor:pointer;
            transition:.18s ease;
            box-shadow: var(--shadow-soft);
        }
        .submit:hover{ transform: translateY(-1px); }

        /* RESPONSIVE */
        @media (max-width: 860px){
            .wrap{ padding: 16px; }
            .card{ padding: 14px; border-radius: 18px; }

            .topbar{
                flex-direction:column;
                align-items:flex-start;
                gap:10px;
            }
            .actions{
                width:100%;
                justify-content:flex-start;
            }

            form{
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .full{ grid-column: span 1; }

            .title{ font-size: 18px; }
            .muted{ font-size: 12.5px; }
        }
    </style>
</head>

<body>
<div class="wrap">
    <div class="card">

        <div class="topbar">
            <div>
                <h1 class="title">Edit Produk</h1>
                <div class="muted">
                    Ubah detail produk investasi. Pastikan harga, profit, durasi, dan VIP minimal sesuai aturan.
                </div>
            </div>

            <div class="actions">
                <a href="/admin/products" class="btn">← Kembali</a>
                <a href="/admin" class="btn btn-primary">Dashboard</a>
            </div>
        </div>

        <div class="divider"></div>

        <div class="section-title">Informasi Produk</div>

        <form method="POST" action="/admin/products/{{ $product->id }}/update">
            @csrf

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
                <div class="hint">Gunakan nama yang konsisten (contoh: Reguler 1, Harian 2, Premium A).</div>
            </div>

            <div class="section-title full" style="margin-top:2px;">Pricing & Benefit</div>

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

            <div class="section-title full" style="margin-top:2px;">Kebijakan</div>

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

            <div class="full">
                <button class="submit" type="submit">💾 Simpan Perubahan</button>
            </div>
        </form>

    </div>
</div>
</body>
</html>
