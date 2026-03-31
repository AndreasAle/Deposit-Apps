<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin | Detail User</title>

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
      --radius-sm:14px;
      --danger:#ef4444;
    }

    *{ box-sizing:border-box; }
    body{
      margin:0;
      font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      color:var(--text);
      background: radial-gradient(1200px 600px at 10% 0%, #eaf0ff 0%, transparent 60%),
                  radial-gradient(900px 500px at 90% 10%, #e9fbff 0%, transparent 55%),
                  var(--bg);
      padding: 28px 16px;
    }

    a{ text-decoration:none; color:inherit; }

    .wrap{
      max-width: 980px;
      margin: 0 auto;
      display:grid;
      grid-template-columns: 1.15fr .85fr;
      gap: 18px;
      align-items:start;
    }

    .card{
      background:var(--card);
      border:1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding:18px;
    }

    .head{
      display:flex;
      justify-content:space-between;
      align-items:flex-start;
      gap:12px;
      margin-bottom:12px;
    }
    .title{
      margin:0;
      font-size:18px;
      letter-spacing:-0.02em;
    }
    .sub{
      margin-top:6px;
      color:var(--muted);
      font-size:13px;
      line-height:1.45;
    }

    .btn{
      border:1px solid var(--border);
      background:#fff;
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
      background: rgba(37,99,235,.06);
      color: var(--primary);
    }

    .alert{
      background: #ecfeff;
      border: 1px solid #67e8f9;
      padding: 10px 12px;
      border-radius: 14px;
      margin: 10px 0 14px;
      color: #155e75;
      font-size: 13px;
      box-shadow: var(--shadow-soft);
    }

    .info{
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap:10px;
      margin-top:10px;
    }
    .kv{
      border:1px solid var(--border);
      border-radius: 16px;
      padding:12px;
      background:#fff;
      box-shadow: var(--shadow-soft);
    }
    .kv .k{
      font-size:12px;
      color:var(--muted);
      margin-bottom:6px;
    }
    .kv .v{
      font-weight:900;
      font-size:13.5px;
      letter-spacing:-0.01em;
      display:flex;
      align-items:center;
      gap:8px;
      flex-wrap:wrap;
    }

    .badge{
      padding:6px 10px;
      border-radius:999px;
      font-size:12px;
      font-weight:900;
      display:inline-flex;
      align-items:center;
      gap:8px;
      border:1px solid;
    }
    .vip{ background:#dbeafe; color:#2563eb; border-color: rgba(37,99,235,.25); }
    .admin{ background:#fee2e2; color:#b91c1c; border-color: rgba(239,68,68,.25); }
    .user{ background:#dcfce7; color:#166534; border-color: rgba(34,197,94,.25); }

    .money{
      font-weight:1000;
    }

    .section-title{
      margin: 12px 0 10px;
      font-size: 13px;
      color: var(--muted);
      font-weight: 900;
      letter-spacing:.02em;
      text-transform: uppercase;
    }

    form{
      border:1px solid var(--border);
      border-radius: 18px;
      padding: 14px;
      background:#fff;
      box-shadow: var(--shadow-soft);
    }
    label{
      display:block;
      font-size:12.5px;
      font-weight:900;
      margin-bottom:6px;
      color: var(--text);
    }
    select,input{
      width:100%;
      padding: 12px 12px;
      border-radius: 14px;
      border:1px solid var(--border);
      outline:none;
      font-size: 13px;
      background:#fff;
    }
    .hint{
      font-size:12px;
      color: var(--muted);
      margin-top: 8px;
      line-height:1.45;
    }
    .submit{
      margin-top: 12px;
      width:100%;
      padding: 12px 12px;
      border-radius: 14px;
      border:1px solid rgba(37,99,235,.25);
      background: rgba(37,99,235,.08);
      color: var(--primary);
      font-weight:1000;
      cursor:pointer;
      transition:.18s ease;
      box-shadow: var(--shadow-soft);
    }
    .submit:hover{ transform: translateY(-1px); }

    .side{
      display:flex;
      flex-direction:column;
      gap: 18px;
    }
    .mini{
      border:1px solid var(--border);
      border-radius: 18px;
      padding: 14px;
      background:#fff;
      box-shadow: var(--shadow-soft);
    }
    .mini b{ font-size:13px; }
    .mini p{
      margin:6px 0 0;
      color: var(--muted);
      font-size: 12px;
      line-height:1.5;
    }
    .link{
      display:inline-flex;
      align-items:center;
      gap:8px;
      margin-top:10px;
      color: var(--primary);
      font-weight: 1000;
      font-size: 12.5px;
    }

    @media (max-width: 980px){
      .wrap{ grid-template-columns: 1fr; }
      .info{ grid-template-columns: 1fr; }
    }
  </style>
</head>

<body>
  <div class="wrap">

    <section class="card">
      <div class="head">
        <div>
          <h1 class="title">Detail User</h1>
          <div class="sub">Manajemen user & override manual (VIP dan saldo).</div>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
          <a href="/admin/users" class="btn">← Back</a>
          <a href="/admin" class="btn btn-primary">Dashboard</a>
        </div>
      </div>

      @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
      @endif

      {{-- INFO USER --}}
      <div class="info">
        <div class="kv">
          <div class="k">Nama</div>
          <div class="v">{{ $user->name }}</div>
        </div>

        <div class="kv">
          <div class="k">Phone</div>
          <div class="v">{{ $user->phone }}</div>
        </div>

        <div class="kv">
          <div class="k">Role</div>
          <div class="v">
            <span class="badge {{ $user->role === 'admin' ? 'admin' : 'user' }}">
              {{ strtoupper($user->role) }}
            </span>
          </div>
        </div>

        <div class="kv">
          <div class="k">Saldo</div>
          <div class="v money">Rp {{ number_format($user->saldo, 0, ',', '.') }}</div>
        </div>

        <div class="kv">
          <div class="k">VIP Saat Ini</div>
          <div class="v">
            <span class="badge vip">VIP {{ (int)$user->vip_level }}</span>
          </div>
        </div>

        <div class="kv">
          <div class="k">User ID</div>
          <div class="v">#{{ $user->id }}</div>
        </div>
      </div>

      <div class="section-title">Override VIP</div>

      <form method="POST" action="/admin/users/{{ $user->id }}/vip">
        @csrf
        <label>VIP Level</label>
        <select name="vip_level">
          @for($i = 0; $i <= 10; $i++)
            <option value="{{ $i }}" {{ (int)$user->vip_level === $i ? 'selected' : '' }}>
              VIP {{ $i }}
            </option>
          @endfor
        </select>

        <button class="submit" type="submit">Update VIP</button>
      </form>

      <div class="section-title">Update Saldo Manual</div>

      <form method="POST" action="/admin/users/{{ $user->id }}/saldo">
        @csrf
        <label>Nominal (+ / -)</label>
        <input
          type="number"
          name="amount"
          placeholder="Masukkan nominal (contoh: 100000 / -50000)"
          required
        />
        <div class="hint">
          Contoh: <b>100000</b> (tambah) / <b>-50000</b> (kurangi).
          Sistem akan mengubah saldo sesuai nilai input.
        </div>

        <button class="submit" type="submit">Update Saldo</button>
      </form>
    </section>

    <aside class="side">
      <div class="mini">
        <b>Catatan Operasional</b>
        <p>
          Gunakan update saldo manual hanya untuk koreksi. Untuk audit,
          pastikan aksi ini juga dicatat ke logs (recommended).
        </p>
      </div>

      <div class="mini">
        <b>Shortcut</b>
        <p>Kelola menu lain tanpa kembali jauh.</p>
        <a class="link" href="/admin/deposits">Open Deposits →</a><br />
        <a class="link" href="{{ route('admin.withdraw.page') }}">Open Withdraw →</a><br />
        <a class="link" href="/admin/logs">Open Logs →</a>
      </div>
    </aside>

  </div>
</body>
</html>
