<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Masuk | Crowdnik</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <style>
    :root{
      --text:#0f172a;
      --muted:#64748b;
      --border: rgba(15,23,42,.10);

      --card: rgba(255,255,255,.78);
      --card-strong: rgba(255,255,255,.92);

      --shadow: 0 30px 80px rgba(15,23,42,.16);
      --shadow-soft: 0 16px 34px rgba(15,23,42,.10);

      --primary1:#6d28d9; /* purple */
      --primary2:#06b6d4; /* cyan */
      --danger:#ef4444;

      --radius: 22px;
      --radius-sm: 16px;
    }

    *{ box-sizing:border-box; }
    html,body{ height:100%; }
    body{
      margin:0;
      min-height:100vh;
      font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      color: var(--text);

      /* WHITE PREMIUM BACKGROUND */
      background:
        radial-gradient(1100px 600px at 12% 8%, rgba(59,130,246,.18), transparent 60%),
        radial-gradient(900px 520px at 90% 18%, rgba(14,165,233,.14), transparent 55%),
        radial-gradient(900px 520px at 50% 105%, rgba(124,58,237,.10), transparent 60%),
        linear-gradient(180deg, #ffffff 0%, #f5f7ff 55%, #eef2ff 100%);

      display:flex;
      align-items:center;
      justify-content:center;
      padding: 22px;
      overflow:hidden;
    }

    /* subtle highlight */
    body::before{
      content:"";
      position:fixed;
      inset:0;
      pointer-events:none;
      background:
        radial-gradient(900px 520px at 50% 10%, rgba(255,255,255,.75), transparent 60%);
      opacity: .55;
    }

    .wrap{
      width: 100%;
      max-width: 560px;
      position:relative;
      z-index:1;
    }

    .card{
      border-radius: var(--radius);
      background: linear-gradient(180deg, var(--card-strong) 0%, var(--card) 100%);
      border: 1px solid var(--border);
      box-shadow: var(--shadow);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      overflow:hidden;
      position:relative;
    }

    /* soft gradient wash inside card */
    .card::after{
      content:"";
      position:absolute;
      inset:-2px;
      pointer-events:none;
      background:
        radial-gradient(520px 320px at 18% 18%, rgba(109,40,217,.16), transparent 62%),
        radial-gradient(520px 320px at 88% 22%, rgba(6,182,212,.14), transparent 62%),
        radial-gradient(520px 380px at 50% 115%, rgba(59,130,246,.10), transparent 65%);
      opacity: 1;
    }

    .inner{
      position:relative;
      z-index:1;
      padding: 30px 28px 26px;
    }

    /* HEADER */
    .head{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap: 14px;
      margin-bottom: 16px;
    }

    .brand{
      display:flex;
      align-items:center;
      gap: 12px;
      min-width: 0;
    }

    .logoBox{
      width: 68px;               /* LOGO LEBIH BESAR */
      height: 68px;
      border-radius: 22px;
      background: rgba(255,255,255,.75);
      border: 1px solid rgba(15,23,42,.10);
      box-shadow: 0 18px 36px rgba(15,23,42,.12);
      display:grid;
      place-items:center;
      overflow:hidden;
      flex: 0 0 68px;
    }

    .logoBox img{
      width: 46px;               /* LOGO LEBIH BESAR */
      height: 46px;
      object-fit: contain;
      display:block;
    }

    .brandText{
      display:flex;
      flex-direction:column;
      line-height:1.1;
      min-width: 0;
    }

    .brandText b{
      font-size: 15px;
      letter-spacing: .2px;
    }

    .brandText span{
      margin-top:4px;
      font-size: 12.5px;
      color: var(--muted);
      white-space: nowrap;
      overflow:hidden;
      text-overflow: ellipsis;
      max-width: 240px;
    }

    .secure{
      display:flex;
      align-items:center;
      gap: 8px;
      padding: 9px 11px;
      border-radius: 999px;
      background: rgba(255,255,255,.72);
      border: 1px solid rgba(15,23,42,.10);
      color: #334155;
      font-size: 12.5px;
      white-space:nowrap;
    }

    .secureDot{
      width: 9px;
      height: 9px;
      border-radius: 999px;
      background: #22c55e;
      box-shadow: 0 0 0 4px rgba(34,197,94,.14);
    }

    .title{
      margin: 6px 0 6px;
      font-size: 26px;
      letter-spacing: -0.03em;
      line-height:1.15;
    }

    .subtitle{
      margin: 0 0 18px;
      color: var(--muted);
      font-size: 14px;
      line-height: 1.6;
    }

    /* ERROR */
    .error{
      background: rgba(239,68,68,.10);
      border: 1px solid rgba(239,68,68,.22);
      color: #991b1b;
      padding: 11px 12px;
      border-radius: 14px;
      font-size: 13px;
      margin-bottom: 14px;
      line-height: 1.45;
    }

    /* FORM */
    .field{ margin-bottom: 12px; }
    label{
      display:block;
      font-size: 12.5px;
      color: #334155;
      margin-bottom: 7px;
      font-weight: 700;
    }

    .control{
      display:flex;
      align-items:center;
      gap: 10px;
      padding: 12px 12px;
      border-radius: 16px;
      background: rgba(255,255,255,.70);
      border: 1px solid rgba(15,23,42,.10);
      transition: .18s ease;
    }
    .control:focus-within{
      border-color: rgba(6,182,212,.40);
      box-shadow: 0 0 0 4px rgba(6,182,212,.14);
      transform: translateY(-1px);
      background: rgba(255,255,255,.92);
    }

    .prefix{
      display:flex;
      align-items:center;
      gap: 8px;
      color: #475569;
      font-weight: 800;
      font-size: 13px;
      white-space: nowrap;
      padding-right: 10px;
      border-right: 1px solid rgba(15,23,42,.08);
    }

    input{
      width:100%;
      border:none;
      outline:none;
      background: transparent;
      color: var(--text);
      font-size: 14.5px;
      padding: 2px 4px;
    }
    input::placeholder{ color: rgba(15,23,42,.35); }

    .suffixBtn{
      border:none;
      background: rgba(255,255,255,.85);
      color: #0f172a;
      padding: 10px 10px;
      border-radius: 14px;
      cursor:pointer;
      display:grid;
      place-items:center;
      border: 1px solid rgba(15,23,42,.10);
      transition:.18s ease;
    }
    .suffixBtn:hover{
      transform: translateY(-1px);
      box-shadow: var(--shadow-soft);
    }

    .row{
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap: 12px;
      margin: 10px 0 14px;
      flex-wrap:wrap;
    }

    .hint{
      font-size: 12.5px;
      color: var(--muted);
    }

    .linkInline a{
      color: #1d4ed8;
      font-weight: 900;
      text-decoration:none;
      border-bottom: 1px dashed rgba(29,78,216,.35);
    }
    .linkInline a:hover{ border-bottom-color: rgba(29,78,216,.75); }

    .btn{
      width:100%;
      border:none;
      cursor:pointer;
      padding: 13px 14px;
      border-radius: 16px;
      font-weight: 900;
      font-size: 15px;
      letter-spacing: .2px;
      color: #000000;
      background: linear-gradient(135deg, var(--primary1) 0%, var(--primary2) 100%);
      box-shadow: 0 18px 42px rgba(109,40,217,.18);
      transition: .18s ease;
    }
    .btn:hover{ transform: translateY(-1px); opacity: .95; }
    .btn:active{ transform: translateY(0px); opacity: .92; }

    .divider{
      display:flex;
      align-items:center;
      gap: 12px;
      margin: 16px 0 14px;
      color: rgba(100,116,139,.9);
      font-size: 12px;
    }
    .divider::before,
    .divider::after{
      content:"";
      height: 1px;
      background: rgba(15,23,42,.10);
      flex:1;
    }

    .footerLink{
      text-align:center;
      font-size: 13.5px;
      color: var(--muted);
      padding-bottom: 4px;
    }
    .footerLink a{
      color: #1d4ed8;
      font-weight: 900;
      text-decoration:none;
      border-bottom: 1px dashed rgba(29,78,216,.35);
    }
    .footerLink a:hover{ border-bottom-color: rgba(29,78,216,.75); }

    @media (max-width: 420px){
      .inner{ padding: 26px 18px 20px; }
      .logoBox{ width: 62px; height: 62px; border-radius: 20px; }
      .logoBox img{ width: 42px; height: 42px; }
      .title{ font-size: 24px; }
    }

    @media (min-width: 900px){
      .wrap{ max-width: 620px; }
      .inner{ padding: 34px 34px 28px; }
      .title{ font-size: 28px; }
      input{ font-size: 15.5px; }
      .btn{ padding: 14px 16px; font-size: 15.5px; }
    }
  </style>
</head>

<body>
  <div class="wrap">
    <section class="card" role="region" aria-label="Login Crowdnik">
      <div class="inner">

        <div class="head">
          <div class="brand">
            <div class="logoBox">
              <img src="/logo.png" alt="Crowdnik">
            </div>
            <div class="brandText">
              <b>Crowdnik</b>
              <span>Secure access portal</span>
            </div>
          </div>

          <div class="secure" title="Koneksi aman">
            <span class="secureDot"></span>
            <span>Secure</span>
          </div>
        </div>

        <div class="title">Masuk</div>
        <div class="subtitle">Gunakan nomor telepon dan kata sandi Anda untuk melanjutkan.</div>

        {{-- ERROR LOGIN --}}
        @if ($errors->any())
          <div class="error">
            {{ $errors->first() }}
          </div>
        @endif

        <form method="POST" action="/login" autocomplete="off" novalidate>
          @csrf

          <div class="field">
            <label for="phone">Nomor Telepon</label>
            <div class="control">
              <div class="prefix" aria-hidden="true">
                <!-- phone icon -->
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                  <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07A19.5 19.5 0 0 1 3.15 9.81 19.86 19.86 0 0 1 .08 1.18A2 2 0 0 1 2.06 -1h3a2 2 0 0 1 2 1.72c.12.86.3 1.7.54 2.5a2 2 0 0 1-.45 2.11L6.09 6.91a16 16 0 0 0 7 7l1.58-1.06a2 2 0 0 1 2.11-.45c.8.24 1.64.42 2.5.54A2 2 0 0 1 22 16.92Z"
                        stroke="rgba(71,85,105,.95)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>+62</span>
              </div>

              <input
                id="phone"
                type="tel"
                name="phone"
                value="{{ old('phone') }}"
                placeholder="08xxxxxxxxxx"
                inputmode="numeric"
                pattern="08[0-9]{8,12}"
                required
              />
            </div>
          </div>

          <div class="field">
            <label for="password">Kata Sandi</label>
            <div class="control">
              <div class="prefix" aria-hidden="true">
                <!-- lock icon -->
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                  <path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="rgba(71,85,105,.95)" stroke-width="2" stroke-linecap="round"/>
                  <path d="M6 11h12a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Z"
                        stroke="rgba(71,85,105,.95)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>

              <input
                id="password"
                type="password"
                name="password"
                placeholder="Masukkan kata sandi"
                required
              />

              <button class="suffixBtn" type="button" onclick="togglePassword()" aria-label="Tampilkan/Sembunyikan password">
                <!-- eye icon -->
                <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12Z" stroke="rgba(15,23,42,.75)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="rgba(15,23,42,.75)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </button>
            </div>
          </div>

          <div class="row">
            <div class="hint">Pastikan nomor aktif & kata sandi benar.</div>
            <div class="hint linkInline"><a href="/register">Buat akun</a></div>
          </div>

          <button class="btn" type="submit">Masuk</button>
        </form>

        <div class="divider">Crowdnik</div>

        <div class="footerLink">
          Belum punya akun? <a href="/register">Daftar sekarang</a>
        </div>

      </div>
    </section>
  </div>

  <script>
    function togglePassword(){
      const input = document.getElementById('password');
      if(!input) return;
      input.type = input.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>
