<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin | Users</title>

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
      --chip:#eef2ff;
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
      max-width:1200px;
      margin:34px auto;
      padding:22px;
    }

    .card{
      background:var(--card);
      border:1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding:18px;
    }

    .top{
      display:flex;
      justify-content:space-between;
      align-items:flex-start;
      gap:14px;
      margin-bottom:14px;
    }
    .title{
      margin:0;
      font-size:20px;
      letter-spacing:-0.02em;
    }
    .sub{
      margin-top:6px;
      color:var(--muted);
      font-size:13px;
      line-height:1.4;
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
      font-weight:800;
      display:inline-flex;
      gap:8px;
      align-items:center;
    }
    .btn:hover{ transform: translateY(-1px); }
    .btn-primary{
      border-color: rgba(37,99,235,.25);
      color: var(--primary);
      background: rgba(37,99,235,.06);
    }

    .filters{
      display:flex;
      gap:10px;
      flex-wrap:wrap;
      margin: 14px 0 14px;
    }
    .input{
      position:relative;
      flex: 1 1 320px;
      min-width: 220px;
    }
    .input input{
      width:100%;
      padding:12px 12px 12px 40px;
      border-radius:14px;
      border:1px solid var(--border);
      outline:none;
      background:#fff;
      box-shadow: var(--shadow-soft);
      font-size:13px;
    }
    .input .ic{
      position:absolute;
      left:12px;
      top:50%;
      transform:translateY(-50%);
      color: var(--muted);
      font-size:14px;
    }

    .select{
      flex: 0 0 220px;
      min-width: 200px;
    }
    .select select{
      width:100%;
      padding:12px 12px;
      border-radius:14px;
      border:1px solid var(--border);
      outline:none;
      background:#fff;
      box-shadow: var(--shadow-soft);
      font-size:13px;
      color: var(--text);
    }

    .meta-row{
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:10px;
      margin-bottom: 10px;
      color: var(--muted);
      font-size:12.5px;
    }
    .chips{
      display:flex;
      gap:8px;
      flex-wrap:wrap;
    }
    .chip{
      padding:7px 10px;
      border-radius:999px;
      background: var(--chip);
      border:1px solid rgba(37,99,235,.12);
      color: var(--primary);
      font-weight:800;
      font-size:12px;
    }

    .table-wrap{
      overflow:auto;
      border:1px solid var(--border);
      border-radius: 16px;
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
      position:sticky;
      top:0;
      z-index:2;
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

    .name{
      display:flex;
      flex-direction:column;
      gap:4px;
    }
    .name b{ font-size:13px; }
    .name span{ font-size:12px; color:var(--muted); }

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

    .money{ font-weight:900; letter-spacing:-0.01em; }

    .row-actions{
      display:flex;
      gap:8px;
      align-items:center;
    }
    .btn-small{
      padding:8px 10px;
      border-radius:12px;
      border:1px solid var(--border);
      background:#fff;
      font-weight:900;
      font-size:12px;
      cursor:pointer;
      transition:.18s ease;
    }
    .btn-small:hover{ transform: translateY(-1px); }
    .btn-detail{
      border-color: rgba(37,99,235,.25);
      background: rgba(37,99,235,.06);
      color: var(--primary);
    }

    .footer{
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:12px;
      flex-wrap:wrap;
      margin-top:14px;
      color: var(--muted);
      font-size:12.5px;
    }
    .pager{
      display:flex;
      gap:8px;
      align-items:center;
    }
    .page{
      width:34px;height:34px;
      border-radius:12px;
      border:1px solid var(--border);
      display:grid;
      place-items:center;
      background:#fff;
      box-shadow: var(--shadow-soft);
      cursor:pointer;
      font-weight:900;
      color: var(--text);
    }
    .page.active{
      background: rgba(37,99,235,.10);
      border-color: rgba(37,99,235,.25);
      color: var(--primary);
    }

    @media (max-width: 720px){
      .wrap{ padding:16px; }
      .top{ flex-direction:column; align-items:flex-start; }
      .actions{ justify-content:flex-start; }
      .select{ flex:1 1 220px; }
    }
  </style>
</head>

<body>
<div class="wrap">
  <div class="card">

    <div class="top">
      <div>
        <h1 class="title">Users</h1>
        <div class="sub">Daftar seluruh pengguna sistem. Gunakan pencarian dan filter untuk mempercepat manajemen.</div>
      </div>

      <div class="actions">
        <a href="/admin" class="btn">← Back</a>
        <button type="button" class="btn btn-primary" onclick="window.location.reload()">
          ⟳ Refresh
        </button>
      </div>
    </div>

    {{-- FILTERS (client-side) --}}
    <div class="filters">
      <div class="input">
        <span class="ic">🔎</span>
        <input id="q" type="text" placeholder="Cari nama / phone / role..." oninput="filterUsers()" />
      </div>

      <div class="select">
        <select id="role" onchange="filterUsers()">
          <option value="">Semua Role</option>
          <option value="admin">Admin</option>
          <option value="user">User</option>
        </select>
      </div>

      <div class="select">
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
        <span class="chip" id="countChip">Total: {{ count($users) }}</span>
      </div>
      <div>Tips: klik “Detail” untuk override VIP & saldo.</div>
    </div>

    <div class="table-wrap">
      <table id="usersTable">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Phone</th>
            <th>Saldo</th>
            <th>VIP</th>
            <th>Role</th>
            <th style="width:160px">Action</th>
          </tr>
        </thead>

        <tbody id="usersBody">
          @foreach($users as $u)
            <tr
              data-name="{{ strtolower($u->name ?? '') }}"
              data-phone="{{ strtolower($u->phone ?? '') }}"
              data-role="{{ strtolower($u->role ?? '') }}"
              data-vip="{{ (int)($u->vip_level ?? 0) }}"
            >
              <td>
                <div class="name">
                  <b>{{ $u->name }}</b>
                  <span>ID: {{ $u->id }}</span>
                </div>
              </td>

              <td>{{ $u->phone }}</td>

              <td class="money">Rp {{ number_format($u->saldo,0,',','.') }}</td>

              <td>
                <span class="badge vip">VIP {{ (int)$u->vip_level }}</span>
              </td>

              <td>
                <span class="badge {{ $u->role === 'admin' ? 'admin' : 'user' }}">
                  {{ strtoupper($u->role) }}
                </span>
              </td>

              <td>
                <div class="row-actions">
                  <a href="{{ url('/admin/users/'.$u->id) }}" class="btn-small btn-detail">Detail</a>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>

      </table>
    </div>

    <div class="footer">
      <div id="shownInfo">Menampilkan: {{ count($users) }} user</div>

      {{-- Pagination UI placeholder (kalau pakai Laravel paginate, ganti dengan $users->links()) --}}
      <div class="pager" aria-label="pagination">
        <div class="page active">1</div>
        <div class="page">2</div>
        <div class="page">3</div>
        <div class="page">›</div>
      </div>
    </div>

  </div>
</div>

<script>
  function filterUsers(){
    const q = (document.getElementById('q').value || '').toLowerCase().trim();
    const role = (document.getElementById('role').value || '').toLowerCase().trim();
    const vip = (document.getElementById('vip').value || '').trim();

    const rows = document.querySelectorAll('#usersBody tr');
    let shown = 0;

    rows.forEach(tr => {
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

    document.getElementById('shownInfo').textContent = `Menampilkan: ${shown} user`;
    document.getElementById('countChip').textContent = `Total: ${shown}`;
  }
</script>
</body>
</html>
