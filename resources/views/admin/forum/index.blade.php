@php $user = auth()->user(); @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Admin Forum | TumbuhMaju</title>
    <style>
        body{margin:0;font-family:Inter,system-ui;background:#f5f7fb;color:#0f172a}
        .wrap{max-width:1100px;margin:0 auto;padding:22px}
        .card{background:#fff;border:1px solid #e7ebf3;border-radius:16px;box-shadow:0 10px 22px rgba(15,23,42,.06)}
        .top{display:flex;gap:12px;align-items:center;justify-content:space-between;margin-bottom:14px}
        .title{font-size:18px;font-weight:900;margin:0}
        .muted{color:#64748b;font-size:13px}
        .toolbar{display:flex;gap:10px;align-items:center}
        .inp{padding:10px 12px;border-radius:12px;border:1px solid #e7ebf3;min-width:280px}
        .btn{padding:10px 12px;border-radius:12px;border:1px solid #e7ebf3;background:#fff;cursor:pointer;font-weight:800}
        .btn-primary{background:#2563eb;color:#fff;border-color:#2563eb}
        table{width:100%;border-collapse:separate;border-spacing:0}
        th,td{padding:12px 14px;border-bottom:1px solid #e7ebf3;font-size:13px;vertical-align:top}
        th{font-size:12px;color:#64748b;text-align:left;background:#fbfcff}
        tr:hover td{background:#fafcff}
        .pill{display:inline-flex;gap:8px;align-items:center;padding:6px 10px;border-radius:999px;border:1px solid #e7ebf3;background:#fff;font-weight:800;font-size:12px;color:#475569}
        .pill-blue{border-color:rgba(37,99,235,.25);color:#2563eb;background:rgba(37,99,235,.06)}
        .pill-green{border-color:rgba(34,197,94,.25);color:#16a34a;background:rgba(34,197,94,.06)}
        .excerpt{color:#334155;max-width:520px}
        .link{color:#2563eb;font-weight:900;text-decoration:none}
        .pager{padding:14px}
        .headpad{padding:16px 16px 0}
        @media(max-width:760px){
            .toolbar{flex-wrap:wrap}
            .inp{min-width:0;flex:1}
            .excerpt{max-width:unset}
            table{display:block;overflow:auto}
        }
    </style>
</head>
<body>
<div class="wrap">

    <div class="top">
        <div>
            <h1 class="title">Admin Forum</h1>
            <div class="muted">Lihat semua postingan & komentar user (global).</div>
        </div>
        <div class="toolbar">
            <form method="GET" action="{{ route('admin.forum.index') }}" style="display:flex;gap:10px;align-items:center">
                <input class="inp" type="text" name="q" value="{{ $q }}" placeholder="Cari nama user / isi post..." />
                <button class="btn btn-primary" type="submit">Cari</button>
                <a class="btn" href="{{ route('admin.forum.index') }}">Reset</a>
            </form>
            <a class="btn" href="/admin/dashboard">← Admin</a>
        </div>
    </div>

    <div class="card">
        <div class="headpad">
            <span class="pill pill-blue">Total tampil: {{ $posts->total() }}</span>
        </div>

        <div style="overflow:auto">
            <table>
                <thead>
                    <tr>
                        <th style="width:70px">ID</th>
                        <th style="width:180px">User</th>
                        <th>Konten</th>
                        <th style="width:150px">Stats</th>
                        <th style="width:160px">Waktu</th>
                        <th style="width:110px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($posts as $post)
                    <tr>
                        <td>#{{ $post->id }}</td>
                        <td>
                            <b>{{ $post->user->name ?? '-' }}</b><br>
                            <span class="muted">user_id: {{ $post->user_id }}</span>
                        </td>
                        <td class="excerpt">
                            {{ \Illuminate\Support\Str::limit(strip_tags((string)$post->content), 140) }}
                        </td>
                        <td>
                            <span class="pill pill-green">💬 {{ $post->comments_count }}</span>
                            <span class="pill pill-blue">🖼️ {{ $post->media_count }}</span>
                        </td>
                        <td>
                            {{ optional($post->created_at)->format('d M Y H:i') }}
                        </td>
                        <td>
                            <a class="link" href="{{ route('admin.forum.show', $post->id) }}">Detail →</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="muted" style="padding:18px">Belum ada postingan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="pager">
            {{ $posts->links() }}
        </div>
    </div>

</div>
</body>
</html>
