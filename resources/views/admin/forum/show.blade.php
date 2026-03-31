@php $user = auth()->user(); @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Detail Post #{{ $post->id }} | Admin Forum</title>
    <style>
        body{margin:0;font-family:Inter,system-ui;background:#f5f7fb;color:#0f172a}
        .wrap{max-width:1000px;margin:0 auto;padding:22px}
        .card{background:#fff;border:1px solid #e7ebf3;border-radius:16px;box-shadow:0 10px 22px rgba(15,23,42,.06);padding:16px}
        .top{display:flex;gap:12px;align-items:center;justify-content:space-between;margin-bottom:14px}
        .title{font-size:18px;font-weight:900;margin:0}
        .muted{color:#64748b;font-size:13px}
        .btn{padding:10px 12px;border-radius:12px;border:1px solid #e7ebf3;background:#fff;cursor:pointer;font-weight:800;text-decoration:none;color:#0f172a}
        .btn-primary{background:#2563eb;color:#fff;border-color:#2563eb}
        .grid{display:grid;grid-template-columns:1fr;gap:12px}
        .meta{display:flex;gap:12px;flex-wrap:wrap}
        .pill{display:inline-flex;gap:8px;align-items:center;padding:6px 10px;border-radius:999px;border:1px solid #e7ebf3;background:#fff;font-weight:800;font-size:12px;color:#475569}
        .pill-blue{border-color:rgba(37,99,235,.25);color:#2563eb;background:rgba(37,99,235,.06)}
        .pill-green{border-color:rgba(34,197,94,.25);color:#16a34a;background:rgba(34,197,94,.06)}
        .content{white-space:pre-wrap;line-height:1.6;color:#0f172a}
        .media{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
        .img{width:100%;height:140px;object-fit:cover;border-radius:12px;border:1px solid #e7ebf3}
        .file{border:1px solid #e7ebf3;border-radius:12px;padding:10px;font-size:12.5px}
        .comment{border:1px solid #e7ebf3;border-radius:14px;padding:12px;background:#fff}
        .c-head{display:flex;justify-content:space-between;gap:12px;margin-bottom:6px}
        .c-user{font-weight:900}
        .c-time{color:#64748b;font-size:12px}
        .children{margin-top:10px;margin-left:18px;display:flex;flex-direction:column;gap:10px}
        @media(max-width:760px){
            .media{grid-template-columns:1fr 1fr}
        }
        @media(max-width:520px){
            .media{grid-template-columns:1fr}
        }
    </style>
</head>
<body>
<div class="wrap">

    <div class="top">
        <div>
            <h1 class="title">Detail Post #{{ $post->id }}</h1>
            <div class="muted">Dibuat oleh: <b>{{ $post->user->name ?? '-' }}</b></div>
        </div>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <a class="btn" href="{{ route('admin.forum.index') }}">← Semua Post</a>
            <a class="btn" href="/admin/dashboard">Admin</a>
        </div>
    </div>

    <div class="card">
        <div class="meta" style="margin-bottom:10px">
            <span class="pill pill-green">💬 {{ $post->comments->count() }}</span>
            <span class="pill pill-blue">🖼️ {{ $post->media->count() }}</span>
            <span class="pill">🕒 {{ optional($post->created_at)->format('d M Y H:i') }}</span>
            <span class="pill">Status: {{ $post->status }}</span>
        </div>

        <div class="content">{!! nl2br(e((string)$post->content)) !!}</div>

        @if($post->media->count() > 0)
            <div style="height:12px"></div>
            <div class="muted" style="font-weight:900;margin-bottom:8px">Media</div>
            <div class="media">
                @foreach($post->media as $m)
                    @if(str_starts_with((string)$m->mime, 'image/'))
                        <img class="img" src="{{ asset('storage/'.$m->path) }}" alt="media">
                    @else
                        <div class="file">
                            <b>{{ $m->original_name ?? 'File' }}</b><br>
                            <span class="muted">{{ $m->mime }} • {{ $m->size }} bytes</span><br>
                            <a class="btn btn-primary" style="display:inline-block;margin-top:8px" href="{{ asset('storage/'.$m->path) }}" target="_blank">Open</a>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    <div style="height:14px"></div>

    <div class="card">
        <div class="muted" style="font-weight:900;margin-bottom:10px">Komentar</div>

        @forelse($post->rootComments as $comment)
            @include('admin.forum._comment', ['comment' => $comment])
        @empty
            <div class="muted">Belum ada komentar.</div>
        @endforelse
    </div>

</div>
</body>
</html>
