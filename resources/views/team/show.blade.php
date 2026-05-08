 @include('partials.anti-inspect')
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Detail Post</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <style>
    :root{
      --text:#0f172a;
      --muted:#64748b;
      --border: rgba(15,23,42,.10);
      --card: rgba(255,255,255,.78);
      --card-strong: rgba(255,255,255,.92);
      --shadow: 0 30px 80px rgba(15,23,42,.16);
      --shadow-soft: 0 16px 34px rgba(15,23,42,.10);
      --primary1:#6d28d9;
      --primary2:#06b6d4;
      --radius:22px;
      --radius-sm:16px;
    }

    *{ box-sizing:border-box; }
    html,body{ height:100%; }

    body{
      margin:0;
      color:var(--text);
      font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      background:
        radial-gradient(1100px 600px at 12% 8%, rgba(59,130,246,.18), transparent 60%),
        radial-gradient(900px 520px at 90% 18%, rgba(14,165,233,.14), transparent 55%),
        radial-gradient(900px 520px at 50% 105%, rgba(124,58,237,.10), transparent 60%),
        linear-gradient(180deg, #ffffff 0%, #f5f7ff 55%, #eef2ff 100%);
      min-height:100vh;
      overflow-x:hidden;
    }

    .wrap{
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:24px 16px;
    }
    .card{
      width:100%;
      max-width: 980px;
      background: linear-gradient(180deg, var(--card) 0%, var(--card-strong) 100%);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      position:relative;
      overflow:hidden;
    }
    .card::before{
      content:"";
      position:absolute;
      inset:-2px;
      background:
        radial-gradient(900px 260px at 10% 0%, rgba(109,40,217,.10), transparent 60%),
        radial-gradient(800px 240px at 90% 10%, rgba(6,182,212,.10), transparent 55%);
      pointer-events:none;
    }
    .card-inner{ position:relative; padding:22px; }

    .header{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:16px;
      padding:6px 2px 14px;
      border-bottom: 1px solid rgba(15,23,42,.08);
      margin-bottom: 16px;
    }
    .brand{ display:flex; align-items:center; gap:14px; min-width:0; }
    .logoBox{
      width:68px;height:68px;border-radius:18px;
      background: rgba(255,255,255,.86);
      border: 1px solid rgba(15,23,42,.10);
      box-shadow: var(--shadow-soft);
      display:flex;align-items:center;justify-content:center;
      flex: 0 0 auto;
    }
    .logoBox img{ width:46px;height:46px;object-fit:contain;display:block; }
    .titleBlock{ min-width:0; display:flex; flex-direction:column; gap:2px; }
    .title{
      margin:0;font-size:18px;font-weight:1000;
      letter-spacing:-0.02em;line-height:1.2;color:var(--text);
    }
    .subtitle{
      margin:0;font-size:13px;color:var(--muted);line-height:1.35;
      white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
    }
    .headerActions{ display:flex; gap:10px; align-items:center; flex:0 0 auto; }

    .btnGhost{
      display:inline-flex;align-items:center;gap:10px;
      padding:10px 12px;border-radius:999px;
      border:1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.78);
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
      color: var(--text);
      text-decoration:none;
      font-weight:900;font-size:12px;
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
      cursor:pointer;user-select:none;-webkit-tap-highlight-color: transparent;
      white-space:nowrap;
    }
    .btnGhost:hover{
      transform: translateY(-1px);
      background: rgba(255,255,255,.92);
      box-shadow: 0 16px 30px rgba(15,23,42,.10);
    }
    .btnGhost svg{ width:18px;height:18px; }

    @media (max-width: 860px){
      .header{ flex-direction:column; align-items:stretch; }
      .headerActions{ width:100%; justify-content:flex-end; }
      .subtitle{ white-space:normal; }
    }
    @media (max-width: 420px){
      .card-inner{ padding:18px; }
      .btnGhost{ width:100%; justify-content:center; }
      .headerActions{ width:100%; }
    }

    .block{
      border-radius: var(--radius);
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.72);
      box-shadow: var(--shadow-soft);
      padding:16px;
      margin-bottom: 14px;
    }
    .muted{ color: var(--muted); font-size:12px; font-weight:800; line-height:1.5; }

    .alert{
      border-radius: var(--radius-sm);
      border: 1px solid rgba(239,68,68,.22);
      background: rgba(239,68,68,.06);
      color: #7f1d1d;
      padding:12px;
      margin-bottom: 12px;
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
      font-weight:800;
      font-size:13px;
    }
    .alertSuccess{
      border-color: rgba(6,182,212,.22);
      background: rgba(6,182,212,.10);
      color:#075985;
    }
    .alertTitle{ font-weight:1000; margin-bottom:6px; }

    .btnDanger{
      display:inline-flex;align-items:center;justify-content:center;gap:10px;
      padding: 12px 14px;
      border-radius: var(--radius-sm);
      border: 1px solid rgba(239,68,68,.22);
      background: rgba(239,68,68,.06);
      color:#7f1d1d;
      font-weight:1000;
      cursor:pointer;
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
    }
    .btnDanger:hover{
      transform: translateY(-1px);
      background: rgba(239,68,68,.08);
      box-shadow: 0 16px 34px rgba(15,23,42,.10);
    }

    .btnPrimary{
      border:0;
      border-radius: var(--radius-sm);
      padding: 14px 14px;
      font-size: 14px;
      font-weight:1000;
      color: #081022;
      cursor:pointer;
      background: linear-gradient(135deg, var(--primary1) 0%, var(--primary2) 100%);
      box-shadow: 0 18px 42px rgba(109,40,217,.20), 0 14px 34px rgba(6,182,212,.12);
      transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
      position:relative;
      overflow:hidden;
      -webkit-tap-highlight-color: transparent;
      display:inline-flex; align-items:center; justify-content:center; gap:10px;
      min-width: 140px;
    }
    .btnPrimary:hover{
      transform: translateY(-1px);
      filter: saturate(1.06);
      box-shadow: 0 24px 60px rgba(109,40,217,.22), 0 18px 44px rgba(6,182,212,.14);
    }
    .btnPrimary::after{
      content:"";
      position:absolute; top:0; left:-120%;
      width:60%; height:100%;
      background: linear-gradient(to right, transparent, rgba(255,255,255,.32), transparent);
      transform: skewX(-18deg);
      animation: shimmer 3.2s infinite;
      pointer-events:none;
    }
    @keyframes shimmer{ 0%{left:-120%} 18%{left:220%} 100%{left:220%} }

    .input{
      width:100%;
      border-radius: var(--radius-sm);
      border: 1px solid rgba(15,23,42,.12);
      background: rgba(255,255,255,.70);
      padding: 14px 14px;
      font-size: 14px;
      color: var(--text);
      outline:none;
      transition: box-shadow .15s ease, border-color .15s ease, background .15s ease;
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
    }
    .input:focus{
      border-color: rgba(6,182,212,.40);
      box-shadow: 0 0 0 4px rgba(6,182,212,.14), 0 14px 30px rgba(15,23,42,.08);
      background: rgba(255,255,255,.86);
    }
    textarea.input{ resize:none; }

    .link{ color:#0891b2; font-weight:1000; text-decoration:none; }
    .link:hover{ text-decoration: underline; }

    .postTop{
      display:flex; justify-content:space-between; align-items:flex-start; gap:12px;
    }
    .name{ font-weight:1000; font-size:14px; color: var(--text); }
    .content{ margin-top:10px; white-space:pre-wrap; color: var(--text); font-weight:700; line-height:1.55; }

    .mediaImg{
      width:100%;
      max-height:420px;
      object-fit:cover;
      border-radius: 14px;
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.70);
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
    }

    .fileLine{
      margin-top:10px;
      display:flex; gap:8px; align-items:center; flex-wrap:wrap;
      color: var(--muted); font-weight:900; font-size:12px;
    }

    .pager{
      margin-top: 12px;
      padding: 10px 12px;
      border-radius: 18px;
      border: 1px solid rgba(15,23,42,.08);
      background: rgba(255,255,255,.62);
      overflow:auto;
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
    }
    .pager a{ color:#0891b2; font-weight:1000; text-decoration:none; }
    .pager a:hover{ text-decoration:underline; }
  </style>
</head>

<body>
  <div class="wrap">
    <main class="card" role="main" aria-label="Detail Post">
      <div class="card-inner">

        <header class="header">
          <div class="brand">
            <div class="logoBox" aria-hidden="true">
              <img src="/logo.png" alt="Logo" />
            </div>
            <div class="titleBlock">
              <h1 class="title">Detail Post</h1>
              <p class="subtitle">Baca postingan dan diskusikan di komentar.</p>
            </div>
          </div>

          <div class="headerActions">
            <a class="btnGhost" href="{{ route('team.index') }}" aria-label="Kembali ke Forum">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                   stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M15 18l-6-6 6-6"></path>
              </svg>
              <span>Kembali</span>
            </a>
          </div>
        </header>

        @if(session('success'))
          <div class="alert alertSuccess">
            <div class="alertTitle">Sukses</div>
            <div>{{ session('success') }}</div>
          </div>
        @endif

        @if(session('error'))
          <div class="alert">
            <div class="alertTitle">Gagal</div>
            <div>{{ session('error') }}</div>
          </div>
        @endif

        {{-- Post --}}
        <section class="block" aria-label="Post Utama">
          <div class="postTop">
            <div>
              <div class="name">{{ $post->user->name }}</div>
              <div class="muted">{{ $post->created_at->format('Y-m-d H:i') }}</div>
            </div>

            @can('delete', $post)
              <form method="POST" action="{{ route('team.posts.destroy', $post) }}">
                @csrf @method('DELETE')
                <button class="btnDanger" type="submit" onclick="return confirm('Hapus postingan ini?')" aria-label="Hapus Post">
                  <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                       stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                    <path d="M10 11v6"></path>
                    <path d="M14 11v6"></path>
                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                  </svg>
                  <span>Hapus</span>
                </button>
              </form>
            @endcan
          </div>

          @if($post->content)
            <div class="content">{{ $post->content }}</div>
          @endif

          @foreach($post->media as $m)
            @if($m->type === 'image')
              <div style="margin-top:10px">
                <img
                  src="{{ asset('storage/'.$m->path) }}"
                  class="mediaImg"
                  alt="media"
                />
              </div>
            @else
              <div class="fileLine">
                <span aria-hidden="true">📎</span>
                <a class="link" href="{{ asset('storage/'.$m->path) }}" target="_blank" rel="noopener">
                  {{ $m->original_name ?? 'File' }}
                </a>
              </div>
            @endif
          @endforeach
        </section>

        {{-- Composer komentar --}}
        <section class="block" aria-label="Tulis Komentar">
          <div class="muted" style="font-weight:1000; margin-bottom:8px; letter-spacing:.06em; text-transform:uppercase;">
            Tulis Komentar
          </div>
          <form method="POST" action="{{ route('team.comments.store', $post) }}">
            @csrf
            <textarea class="input" name="content" rows="3" placeholder="Komentar..." required></textarea>
            <div style="margin-top:10px">
              <button class="btnPrimary" type="submit" aria-label="Kirim Komentar">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M22 2L11 13"></path>
                  <path d="M22 2l-7 20-4-9-9-4 20-7z"></path>
                </svg>
                <span>Kirim</span>
              </button>
            </div>
          </form>
        </section>

        {{-- Comments --}}
        @foreach($comments as $c)
          <section class="block" aria-label="Komentar {{ $c->id }}">
            <div class="postTop">
              <div>
                <div class="name">{{ $c->user->name }}</div>
                <div class="muted">{{ $c->created_at->format('Y-m-d H:i') }}</div>
              </div>

              @can('delete', $c)
                <form method="POST" action="{{ route('team.comments.destroy', $c) }}">
                  @csrf @method('DELETE')
                  <button class="btnDanger" type="submit" onclick="return confirm('Hapus komentar ini?')" aria-label="Hapus Komentar">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                      <polyline points="3 6 5 6 21 6"></polyline>
                      <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                      <path d="M10 11v6"></path>
                      <path d="M14 11v6"></path>
                      <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                    </svg>
                    <span>Hapus</span>
                  </button>
                </form>
              @endcan
            </div>

            <div class="content">{{ $c->content }}</div>
          </section>
        @endforeach

        <div class="pager">
          {{ $comments->links() }}
        </div>

      </div>
    </main>
  </div>
</body>
</html>
