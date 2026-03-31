<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Team Forum</title>
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

    /* single-card center */
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

    /* header */
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

    /* blocks */
    .block{
      border-radius: var(--radius);
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.72);
      box-shadow: var(--shadow-soft);
      padding:16px;
      margin-bottom: 14px;
    }
    .blockTitle{
      margin:0 0 10px 0;
      font-size:12px;
      letter-spacing:.12em;
      text-transform:uppercase;
      color: var(--muted);
      font-weight:1000;
    }
    .muted{ color: var(--muted); font-size:12px; font-weight:800; line-height:1.5; }

    /* alerts */
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
    .alertList div{ margin:4px 0; font-size:12px; }

    /* form */
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

    .row{
      display:flex; gap:10px; flex-wrap:wrap; align-items:center;
      margin-top:10px;
    }

    /* file input: keep native, but nicer container */
    .fileWrap{
      display:flex; flex-direction:column; gap:6px;
      padding:10px 12px;
      border-radius: 18px;
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.72);
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
      flex: 1 1 260px;
      min-width: 240px;
    }
    .fileWrap label{
      font-size:12px; color: var(--muted); font-weight:1000; letter-spacing:.08em; text-transform:uppercase;
    }
    .fileWrap input[type="file"]{
      width:100%;
      font-size:13px;
      color: var(--text);
    }

    /* buttons */
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
    .btnPrimary:active{ transform: translateY(0px) scale(.99); }
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
    .btnDanger:active{ transform: translateY(0px) scale(.99); }

    /* post card */
    .postHead{ display:flex; justify-content:space-between; align-items:flex-start; gap:12px; }
    .name{ font-weight:1000; font-size:14px; color: var(--text); }
    .meta{ margin-top:4px; }
    .badge{
      display:inline-flex;align-items:center;gap:8px;
      padding:7px 10px;
      border-radius:999px;
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.86);
      color: var(--muted);
      font-size:12px;
      font-weight:900;
      white-space:nowrap;
    }

    .content{ margin-top:10px; white-space:pre-wrap; color: var(--text); font-weight:700; line-height:1.55; }

    .mediaGrid{
      display:grid;
      grid-template-columns: repeat(3, 1fr);
      gap:8px;
      margin-top:10px;
    }
    @media (max-width: 560px){
      .mediaGrid{ grid-template-columns: repeat(2, 1fr); }
    }
    .mediaGrid img{
      width:100%; height:120px; object-fit:cover;
      border-radius: 14px;
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.70);
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
    }

    .fileLine{
      margin-top:10px;
      display:flex;
      gap:8px;
      align-items:center;
      flex-wrap:wrap;
      color: var(--muted);
      font-weight:900;
      font-size:12px;
    }
    .link{
      color:#0891b2;
      font-weight:1000;
      text-decoration:none;
    }
    .link:hover{ text-decoration: underline; }

    .footerLink{ margin-top:12px; }

    /* pagination wrapper */
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
    <main class="card" role="main" aria-label="Team Forum">
      <div class="card-inner">

        <header class="header">
          <div class="brand">
            <div class="logoBox" aria-hidden="true">
              <img src="/logo.png" alt="Logo" />
            </div>
            <div class="titleBlock">
              <h1 class="title">Team Forum</h1>
              <p class="subtitle">Diskusi internal tim: teks, gambar, dan file PDF.</p>
            </div>
          </div>

          <div class="headerActions">
            <a class="btnGhost" href="/dashboard" aria-label="Kembali ke Dashboard">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                   stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M15 18l-6-6 6-6"></path>
              </svg>
              <span>Kembali</span>
            </a>
          </div>
        </header>

        {{-- Alerts --}}
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

        @if($errors->any())
          <div class="alert">
            <div class="alertTitle">Validasi</div>
            <div class="alertList">
              @foreach($errors->all() as $e)
                <div>— {{ $e }}</div>
              @endforeach
            </div>
          </div>
        @endif

        {{-- Composer --}}
        <section class="block" aria-label="Buat Postingan">
          <p class="blockTitle">Buat Postingan</p>
          <div class="muted">Boleh teks saja, boleh upload gambar/pdf.</div>

          <form method="POST" action="{{ route('team.posts.store') }}" enctype="multipart/form-data" style="margin-top:12px">
            @csrf

            <textarea class="input" name="content" rows="3" placeholder="Tulis sesuatu..."></textarea>

            <div class="row">
              <div class="fileWrap">
                <label>Upload media</label>
                <input type="file" name="media[]" multiple />
                <div class="muted">Max 5MB/file • jpg, png, webp, pdf</div>
              </div>

              <button class="btnPrimary" type="submit" aria-label="Posting">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M22 2L11 13"></path>
                  <path d="M22 2l-7 20-4-9-9-4 20-7z"></path>
                </svg>
                <span>Posting</span>
              </button>
            </div>
          </form>
        </section>

        {{-- Feed --}}
        @foreach($posts as $post)
          <article class="block" aria-label="Post {{ $post->id }}">
            <div class="postHead">
              <div>
                <div class="name">{{ $post->user->name }}</div>
                <div class="meta muted">
                  {{ $post->created_at->format('Y-m-d H:i') }}
                  &nbsp;•&nbsp;
                  <span class="badge">{{ $post->comments_count }} komentar</span>
                </div>
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

            @php $imgs = $post->media->where('type','image'); @endphp
            @if($imgs->count())
              <div class="mediaGrid" aria-label="Gambar Post">
                @foreach($imgs as $m)
                  <img src="{{ asset('storage/'.$m->path) }}" alt="media">
                @endforeach
              </div>
            @endif

            @php $files = $post->media->where('type','file'); @endphp
            @if($files->count())
              <div style="margin-top:10px">
                @foreach($files as $m)
                  <div class="fileLine">
                    <span aria-hidden="true">📎</span>
                    <a class="link" href="{{ asset('storage/'.$m->path) }}" target="_blank" rel="noopener">
                      {{ $m->original_name ?? 'File' }}
                    </a>
                  </div>
                @endforeach
              </div>
            @endif

            <div class="footerLink">
              <a class="link" href="{{ route('team.show', $post) }}">Lihat &amp; Komentar →</a>
            </div>
          </article>
        @endforeach

        <div class="pager">
          {{ $posts->links() }}
        </div>

      </div>
    </main>
  </div>
</body>
</html>
