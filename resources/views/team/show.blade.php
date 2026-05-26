 @include('partials.anti-inspect')
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Detail Post | Velora Finance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <style>
    :root{
      --vl-bg:#100313;
      --vl-bg2:#19071d;
      --vl-panel:#24102b;
      --vl-panel2:#2b0f34;
      --vl-card:rgba(43,15,52,.82);
      --vl-card-strong:rgba(30,8,38,.94);
      --vl-text:#fff8ff;
      --vl-soft:#f9e7ff;
      --vl-muted:#c5a8cf;
      --vl-muted2:#9675a1;
      --vl-border:rgba(255,255,255,.12);
      --vl-purple:#8b3dff;
      --vl-violet:#d85cff;
      --vl-lilac:#f0c7ff;
      --vl-gold:#ffb23f;
      --vl-gold2:#ffd36d;
      --vl-red:#ff4f6d;
      --vl-shadow:0 30px 80px rgba(0,0,0,.42);
      --vl-shadow-soft:0 16px 34px rgba(0,0,0,.24);
      --radius:26px;
      --radius-sm:18px;
    }

    *{ box-sizing:border-box; }

    html,body{
      min-height:100%;
    }

    body{
      margin:0;
      color:var(--vl-text);
      font-family:Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
      background:
        radial-gradient(900px 520px at 10% -4%, rgba(255,178,63,.18), transparent 58%),
        radial-gradient(760px 480px at 90% 4%, rgba(216,92,255,.22), transparent 62%),
        radial-gradient(660px 380px at 50% 105%, rgba(139,61,255,.16), transparent 64%),
        linear-gradient(180deg, #18071d 0%, #100313 50%, #07020a 100%);
      min-height:100vh;
      overflow-x:hidden;
      -webkit-tap-highlight-color:transparent;
    }

    body::before{
      content:"";
      position:fixed;
      inset:0;
      pointer-events:none;
      background:
        linear-gradient(rgba(255,255,255,.024) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.017) 1px, transparent 1px);
      background-size:38px 38px;
      mask-image:linear-gradient(180deg, rgba(0,0,0,.66), transparent 76%);
      -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.66), transparent 76%);
      opacity:.45;
      z-index:0;
    }

    body::after{
      content:"";
      position:fixed;
      inset:0;
      pointer-events:none;
      background:
        radial-gradient(circle at 50% 0%, rgba(255,255,255,.045), transparent 28%),
        radial-gradient(circle at 0% 48%, rgba(255,178,63,.065), transparent 28%),
        radial-gradient(circle at 100% 42%, rgba(216,92,255,.08), transparent 30%);
      z-index:0;
    }

    a{
      color:inherit;
      text-decoration:none;
    }

    button,
    textarea{
      font-family:inherit;
    }

    .wrap{
      min-height:100vh;
      display:flex;
      align-items:flex-start;
      justify-content:center;
      padding:18px 10px 28px;
      position:relative;
      z-index:1;
    }

    .card{
      width:100%;
      max-width:430px;
      background:
        radial-gradient(360px 210px at 96% 0%, rgba(216,92,255,.20), transparent 62%),
        radial-gradient(320px 190px at 0% 100%, rgba(255,178,63,.10), transparent 64%),
        linear-gradient(180deg, rgba(43,15,52,.84), rgba(16,3,19,.94));
      border:1px solid rgba(255,255,255,.12);
      border-radius:30px;
      box-shadow:
        0 26px 70px rgba(0,0,0,.44),
        0 0 0 1px rgba(216,92,255,.06) inset,
        0 0 42px rgba(216,92,255,.10);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      position:relative;
      overflow:hidden;
    }

    .card::before{
      content:"";
      position:absolute;
      inset:0;
      background:
        linear-gradient(145deg, rgba(255,255,255,.10) 0%, rgba(255,255,255,.035) 27%, transparent 28%),
        radial-gradient(190px 130px at 84% 12%, rgba(255,178,63,.13), transparent 70%);
      pointer-events:none;
    }

    .card-inner{
      position:relative;
      padding:15px;
    }

    .header{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      padding:3px 2px 14px;
      margin-bottom:14px;
      border-bottom:1px solid rgba(255,255,255,.08);
    }

    .brand{
      display:flex;
      align-items:center;
      gap:11px;
      min-width:0;
    }

    .logoBox{
      width:50px;
      height:50px;
      border-radius:17px;
      background:
        radial-gradient(circle at 28% 8%, rgba(255,255,255,.95), rgba(255,245,218,.72) 34%, rgba(244,207,255,.78) 78%),
        linear-gradient(135deg, rgba(255,178,63,.75), rgba(216,92,255,.72));
      border:1px solid rgba(255,211,109,.30);
      box-shadow:
        0 14px 30px rgba(0,0,0,.34),
        0 0 0 1px rgba(255,255,255,.10) inset,
        0 0 28px rgba(216,92,255,.18),
        0 0 22px rgba(255,178,63,.08);
      display:flex;
      align-items:center;
      justify-content:center;
      flex:0 0 auto;
      overflow:hidden;
    }

    .logoBox img{
      width:44px;
      height:44px;
      object-fit:contain;
      display:block;
    }

    .titleBlock{
      min-width:0;
      display:flex;
      flex-direction:column;
      gap:4px;
    }

    .title{
      margin:0;
      font-size:20px;
      font-weight:950;
      letter-spacing:-.045em;
      line-height:1.08;
      color:#ffffff;
      text-shadow:0 12px 26px rgba(0,0,0,.28);
    }

    .subtitle{
      margin:0;
      font-size:11px;
      color:rgba(255,211,109,.82);
      line-height:1.35;
      font-weight:800;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width:235px;
    }

    .headerActions{
      display:flex;
      gap:10px;
      align-items:center;
      flex:0 0 auto;
    }

    .btnGhost{
      width:42px;
      height:42px;
      display:inline-grid;
      place-items:center;
      border-radius:999px;
      border:1px solid rgba(255,255,255,.115);
      background:
        radial-gradient(circle at 32% 18%, rgba(255,255,255,.18), transparent 34%),
        linear-gradient(180deg, rgba(53,18,71,.96), rgba(23,6,28,.96));
      box-shadow:
        0 13px 28px rgba(0,0,0,.34),
        0 0 0 1px rgba(216,92,255,.08) inset;
      color:#ffffff;
      transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
      cursor:pointer;
      user-select:none;
      -webkit-tap-highlight-color: transparent;
      white-space:nowrap;
    }

    .btnGhost:hover{
      transform:translateY(-1px);
      border-color:rgba(255,211,109,.24);
      box-shadow:
        0 16px 34px rgba(0,0,0,.38),
        0 0 28px rgba(216,92,255,.13);
    }

    .btnGhost svg{
      width:20px;
      height:20px;
    }

    .btnGhost span{
      display:none;
    }

    .detailHero{
      position:relative;
      overflow:hidden;
      border-radius:28px;
      margin-bottom:14px;
      padding:17px;
      background:
        radial-gradient(320px 180px at 95% 0%, rgba(216,92,255,.32), transparent 62%),
        radial-gradient(260px 160px at 4% 0%, rgba(255,178,63,.22), transparent 65%),
        linear-gradient(135deg, rgba(53,18,71,.96), rgba(30,8,38,.98) 54%, rgba(16,3,19,.98));
      border:1px solid rgba(255,255,255,.13);
      box-shadow:
        0 18px 42px rgba(0,0,0,.34),
        0 0 0 1px rgba(255,211,109,.08) inset,
        inset 0 1px 0 rgba(255,255,255,.13);
    }

    .detailHero::before{
      content:"";
      position:absolute;
      inset:0;
      pointer-events:none;
      background:
        linear-gradient(145deg, rgba(255,255,255,.11) 0%, rgba(255,255,255,.035) 26%, transparent 27%),
        radial-gradient(150px 110px at 86% 18%, rgba(255,178,63,.18), transparent 70%);
    }

    .detailHero > *{
      position:relative;
      z-index:1;
    }

    .detailKicker{
      display:inline-flex;
      align-items:center;
      min-height:26px;
      padding:0 10px;
      border-radius:999px;
      color:#210812;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.48), transparent 34%),
        linear-gradient(135deg, var(--vl-gold), var(--vl-violet));
      font-size:10px;
      font-weight:950;
      letter-spacing:.08em;
      text-transform:uppercase;
      box-shadow:0 12px 24px rgba(216,92,255,.16);
    }

    .detailHero h2{
      margin:12px 0 0;
      color:#ffffff;
      font-size:22px;
      line-height:1.07;
      letter-spacing:-.055em;
      font-weight:950;
      text-shadow:0 12px 26px rgba(0,0,0,.28);
    }

    .detailHero p{
      margin:8px 0 0;
      color:rgba(249,231,255,.68);
      font-size:12px;
      font-weight:650;
      line-height:1.45;
    }

    .block{
      position:relative;
      overflow:hidden;
      border-radius:26px;
      border:1px solid rgba(255,255,255,.10);
      background:
        radial-gradient(260px 160px at 90% 0%, rgba(216,92,255,.14), transparent 58%),
        radial-gradient(220px 140px at 0% 100%, rgba(255,178,63,.08), transparent 62%),
        linear-gradient(180deg, rgba(43,15,52,.88), rgba(18,5,23,.94));
      box-shadow:
        0 16px 34px rgba(0,0,0,.26),
        0 0 0 1px rgba(255,255,255,.028) inset;
      padding:14px;
      margin-bottom:13px;
    }

    .block::before{
      content:"";
      position:absolute;
      inset:0;
      pointer-events:none;
      background:
        linear-gradient(135deg, rgba(255,255,255,.06), transparent 30%),
        radial-gradient(circle at 12% 0%, rgba(255,178,63,.08), transparent 42%);
      opacity:.78;
    }

    .block > *{
      position:relative;
      z-index:1;
    }

    .muted{
      color:rgba(249,231,255,.58);
      font-size:11px;
      font-weight:750;
      line-height:1.5;
    }

    .alert{
      border-radius:20px;
      border:1px solid rgba(255,79,109,.26);
      background:
        radial-gradient(220px 140px at 100% 0%, rgba(255,130,150,.18), transparent 60%),
        linear-gradient(180deg, rgba(88,24,35,.88), rgba(57,14,22,.92));
      color:#ffe8ed;
      padding:13px;
      margin-bottom:12px;
      box-shadow:0 14px 28px rgba(0,0,0,.22);
      font-weight:800;
      font-size:13px;
    }

    .alertSuccess{
      border-color:rgba(216,92,255,.34);
      background:
        radial-gradient(240px 140px at 100% 0%, rgba(216,92,255,.20), transparent 60%),
        linear-gradient(180deg, rgba(53,18,71,.88), rgba(24,7,30,.92));
      color:#fff8ff;
    }

    .alertTitle{
      font-weight:950;
      margin-bottom:6px;
      color:#ffffff;
    }

    .btnDanger{
      min-height:36px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:8px;
      padding:0 11px;
      border-radius:14px;
      border:1px solid rgba(255,79,109,.22);
      background:rgba(255,79,109,.08);
      color:#ffd7df;
      font-weight:950;
      font-size:12px;
      cursor:pointer;
      box-shadow:0 10px 20px rgba(0,0,0,.20);
      transition:transform .15s ease, background .15s ease;
    }

    .btnDanger:hover{
      transform:translateY(-1px);
      background:rgba(255,79,109,.13);
    }

    .btnDanger svg{
      width:17px;
      height:17px;
    }

    .btnPrimary{
      width:100%;
      min-height:46px;
      border:0;
      border-radius:999px;
      padding:0 16px;
      font-size:13px;
      font-weight:950;
      color:#210812;
      cursor:pointer;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.48), transparent 34%),
        linear-gradient(135deg, var(--vl-gold) 0%, var(--vl-violet) 100%);
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,.34),
        0 14px 30px rgba(216,92,255,.24),
        0 0 34px rgba(255,178,63,.10);
      transition:transform .15s ease, filter .15s ease;
      position:relative;
      overflow:hidden;
      -webkit-tap-highlight-color:transparent;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:10px;
    }

    .btnPrimary:hover{
      transform:translateY(-1px);
      filter:brightness(1.04);
    }

    .btnPrimary svg{
      width:18px;
      height:18px;
    }

    .input{
      width:100%;
      border-radius:20px;
      border:1px dashed rgba(216,92,255,.38);
      background:rgba(7,2,10,.38);
      padding:14px;
      font-size:13px;
      font-weight:600;
      line-height:1.55;
      color:var(--vl-text);
      outline:none;
      transition:box-shadow .15s ease, border-color .15s ease, background .15s ease;
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,.04),
        0 10px 22px rgba(0,0,0,.18);
    }

    .input::placeholder{
      color:rgba(249,231,255,.48);
    }

    .input:focus{
      border-color:rgba(255,211,109,.56);
      background:rgba(7,2,10,.50);
      box-shadow:
        0 0 0 4px rgba(216,92,255,.08),
        inset 0 1px 0 rgba(255,255,255,.05),
        0 12px 28px rgba(0,0,0,.22);
    }

    textarea.input{
      resize:vertical;
      min-height:108px;
    }

    .link{
      color:var(--vl-gold2);
      font-weight:950;
      text-decoration:none;
    }

    .link:hover{
      text-decoration:underline;
    }

    .postTop{
      display:flex;
      justify-content:space-between;
      align-items:flex-start;
      gap:12px;
    }

    .author{
      display:flex;
      align-items:center;
      gap:10px;
      min-width:0;
    }

    .avatar{
      width:42px;
      height:42px;
      border-radius:999px;
      flex:0 0 auto;
      display:grid;
      place-items:center;
      color:#210812;
      font-size:13px;
      font-weight:950;
      letter-spacing:-.03em;
      background:
        radial-gradient(circle at 30% 15%, rgba(255,255,255,.58), transparent 34%),
        linear-gradient(135deg, var(--vl-gold) 0%, var(--vl-violet) 58%, var(--vl-purple) 100%);
      box-shadow:
        0 12px 24px rgba(216,92,255,.20),
        0 9px 20px rgba(255,178,63,.12),
        0 0 0 1px rgba(255,255,255,.18) inset,
        0 0 24px rgba(216,92,255,.16);
    }

    .name{
      font-weight:950;
      font-size:14px;
      color:#ffffff;
      letter-spacing:-.02em;
      line-height:1.15;
    }

    .content{
      margin-top:13px;
      white-space:pre-wrap;
      color:rgba(255,248,255,.90);
      font-weight:650;
      font-size:13px;
      line-height:1.62;
    }

    .mediaWrap{
      margin-top:12px;
    }

    .mediaImg{
      width:100%;
      aspect-ratio:3 / 4;
      max-height:540px;
      object-fit:cover;
      object-position:center;
      border-radius:21px;
      border:1px solid rgba(255,211,109,.18);
      background:rgba(216,92,255,.08);
      box-shadow:
        0 14px 26px rgba(0,0,0,.20),
        0 0 0 1px rgba(216,92,255,.06) inset;
      display:block;
    }

    .fileLine{
      margin-top:10px;
      min-height:50px;
      display:flex;
      gap:10px;
      align-items:center;
      flex-wrap:nowrap;
      color:rgba(249,231,255,.86);
      font-weight:850;
      font-size:12px;
      padding:10px 12px;
      border-radius:17px;
      background:rgba(255,255,255,.055);
      border:1px solid rgba(255,255,255,.09);
      overflow:hidden;
    }

    .fileLine span{
      flex:0 0 auto;
    }

    .commentHeader{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:10px;
    }

    .sectionLabel{
      margin:0;
      font-weight:950;
      color:#ffffff;
      font-size:14px;
      letter-spacing:-.02em;
    }

    .sectionPill{
      display:inline-flex;
      align-items:center;
      min-height:26px;
      padding:0 10px;
      border-radius:999px;
      color:#210812;
      background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.48), transparent 34%),
        linear-gradient(135deg, var(--vl-gold), var(--vl-violet));
      font-size:10px;
      font-weight:950;
      white-space:nowrap;
    }

    .commentsList{
      display:grid;
      grid-template-columns:1fr;
      gap:10px;
    }

    .commentBlock{
      margin-bottom:0;
      border-radius:22px;
      background:
        radial-gradient(180px 100px at 100% 0%, rgba(216,92,255,.10), transparent 60%),
        linear-gradient(180deg, rgba(255,255,255,.065), rgba(255,255,255,.035));
    }

    .pager{
      margin-top:12px;
      padding:10px 12px;
      border-radius:18px;
      border:1px solid rgba(255,255,255,.08);
      background:rgba(255,255,255,.045);
      overflow:auto;
      box-shadow:0 10px 22px rgba(0,0,0,.18);
      color:#ffffff;
    }

    .pager a{
      color:var(--vl-gold2);
      font-weight:950;
      text-decoration:none;
    }

    .pager a:hover{
      text-decoration:underline;
    }

    .pager span{
      color:rgba(249,231,255,.70);
    }

    @media (max-width:420px){
      .wrap{
        padding:12px 8px 22px;
      }

      .card{
        border-radius:28px;
      }

      .card-inner{
        padding:13px;
      }

      .header{
        gap:10px;
      }

      .logoBox{
        width:44px;
        height:44px;
        border-radius:15px;
      }

      .logoBox img{
        width:38px;
        height:38px;
      }

      .title{
        font-size:18px;
      }

      .subtitle{
        max-width:180px;
        font-size:10.5px;
      }

      .detailHero{
        border-radius:25px;
        padding:15px;
      }

      .detailHero h2{
        font-size:20px;
      }

      .block{
        border-radius:23px;
        padding:12px;
      }

      .btnDanger span{
        display:none;
      }

      .btnDanger{
        width:36px;
        padding:0;
      }

      .postTop{
        gap:9px;
      }

      .avatar{
        width:38px;
        height:38px;
        font-size:12px;
      }

      .mediaImg{
        border-radius:18px;
      }
    }
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
              <h1 class="title">Detail Forum</h1>
              <p class="subtitle">Velora Community Discussion</p>
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


        <section class="detailHero" aria-label="Detail diskusi Velora">
          <span class="detailKicker">Velora Forum</span>
          <h2>Detail diskusi komunitas.</h2>
          <p>Baca postingan, lihat lampiran, dan lanjutkan percakapan dengan komentar yang rapi dan profesional.</p>
        </section>

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
            <div class="author">
              <div class="avatar" aria-hidden="true">
                {{ mb_strtoupper(mb_substr($post->user->name ?? 'U', 0, 1)) }}
              </div>
              <div style="min-width:0">
                <div class="name">{{ $post->user->name }}</div>
                <div class="muted">{{ $post->created_at->format('Y-m-d H:i') }}</div>
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

          @foreach($post->media as $m)
            @if($m->type === 'image')
              <div class="mediaWrap">
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
          <div class="commentHeader">
            <h2 class="sectionLabel">Tulis Komentar</h2>
            <span class="sectionPill">Diskusi</span>
          </div>
          <form method="POST" action="{{ route('team.comments.store', $post) }}">
            @csrf
            <textarea class="input" name="content" rows="3" placeholder="Tulis komentar kamu di forum Velora..." required></textarea>
            <div class="mediaWrap">
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
        <section class="block" aria-label="Daftar Komentar">
          <div class="commentHeader">
            <h2 class="sectionLabel">Komentar</h2>
            <span class="sectionPill">{{ method_exists($comments, 'total') ? $comments->total() : $comments->count() }}</span>
          </div>

          <div class="commentsList">
        @foreach($comments as $c)
          <section class="block commentBlock" aria-label="Komentar {{ $c->id }}">
            <div class="postTop">
              <div class="author">
                <div class="avatar" aria-hidden="true">
                  {{ mb_strtoupper(mb_substr($c->user->name ?? 'U', 0, 1)) }}
                </div>
                <div style="min-width:0">
                  <div class="name">{{ $c->user->name }}</div>
                  <div class="muted">{{ $c->created_at->format('Y-m-d H:i') }}</div>
                </div>
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
          </div>
        </section>

        <div class="pager">
          {{ $comments->links() }}
        </div>

      </div>
    </main>
  </div>
</body>
</html>
