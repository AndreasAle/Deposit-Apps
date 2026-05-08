<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Masuk | Crowdnik</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --green:#18c79b;
      --green-2:#42dfb2;
      --green-3:#0b8f70;
      --green-dark:#0d7f67;

      --dark:#031816;
      --dark-2:#05231f;
      --dark-3:#010f0d;

      --white:#ffffff;
      --text:#10322c;
      --muted:#6c8b82;
      --line:#e2eee9;
      --input:#edf4ff;

      --danger:#ef4444;

      --shadow-page:0 28px 70px rgba(0,0,0,.30);
      --shadow-card:0 18px 38px rgba(6,34,29,.16);
    }

    *{
      box-sizing:border-box;
    }

    html,
    body{
      min-height:100%;
    }

body{
  margin:0;
  min-height:100vh;
  font-family:'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  color:var(--text);

  display:block;

  padding:0;
  overflow-x:hidden;
  position:relative;

  background:#ffffff;
}

body::before,
body::after{
  content:none !important;
}

body::before{
  content:none !important;
}
body::after{
  content:none !important;
}

    a{
      color:inherit;
    }

    button,
    input{
      font-family:inherit;
    }
.page{
  width:100%;
  max-width:none;
  min-height:100vh;
  position:relative;
  z-index:1;
  animation:pageEnter .45s ease both;
}

.shell{
  position:relative;
  overflow:hidden;
  width:100%;
  max-width:none;
  min-height:100vh;

  border-radius:0;
  background:#ffffff;
  border:none;
  box-shadow:none;
}

.shell::before{
  content:none !important;
}

.hero{
  position:relative;
  width:100%;
  height:176px;
  overflow:hidden;
  background:
    linear-gradient(152deg, rgba(255,255,255,.10) 0%, rgba(255,255,255,.04) 28%, transparent 29%),
    radial-gradient(260px 160px at 92% 105%, rgba(0,223,130,.22), transparent 62%),
    radial-gradient(230px 160px at 16% 0%, rgba(3,98,76,.58), transparent 72%),
    linear-gradient(180deg, #10251f 0%, #041311 58%, #020908 100%);
}

/* blob besar kiri - hijau gelap dashboard */
.hero::before{
  content:"";
  position:absolute;
  left:-48px;
  top:-34px;
  width:190px;
  height:170px;
  border-radius:44% 56% 54% 46% / 42% 44% 56% 58%;
  background:
    radial-gradient(circle at 30% 18%, rgba(255,255,255,.12), transparent 34%),
    linear-gradient(145deg, rgba(0,223,130,.22), rgba(3,98,76,.48));
  box-shadow:
    0 0 50px rgba(0,223,130,.08),
    inset 0 1px 0 rgba(255,255,255,.08);
  animation:blobFloat 6s ease-in-out infinite;
}

/* blob kanan atas - glass gelap bukan putih terang */
.hero::after{
  content:"";
  position:absolute;
  right:-38px;
  top:-38px;
  width:168px;
  height:138px;
  border-radius:34px 0 0 64px;
  background:
    radial-gradient(circle at 26% 26%, rgba(255,255,255,.18), transparent 34%),
    linear-gradient(145deg, rgba(214,255,240,.16), rgba(0,223,130,.08));
  border:1px solid rgba(255,255,255,.08);
  backdrop-filter:blur(4px);
  -webkit-backdrop-filter:blur(4px);
  animation:blobFloat2 7s ease-in-out infinite;
}

/* shape besar kanan */
.shapeOne{
  width:170px;
  height:130px;
  right:-28px;
  top:26px;
  border-radius:48% 52% 58% 42% / 42% 48% 52% 58%;
  background:
    radial-gradient(circle at 26% 22%, rgba(255,255,255,.10), transparent 34%),
    linear-gradient(135deg, rgba(0,223,130,.16), rgba(3,98,76,.40));
  box-shadow:0 0 40px rgba(0,223,130,.10);
  animation:shapeMoveOne 6.2s ease-in-out infinite;
}

/* bola kecil kanan bawah */
.shapeTwo{
  width:86px;
  height:86px;
  right:56px;
  top:82px;
  border-radius:999px;
  background:
    radial-gradient(circle at 32% 24%, rgba(255,255,255,.50), rgba(204,255,240,.20) 34%, rgba(0,223,130,.12) 100%);
  border:1px solid rgba(255,255,255,.10);
  box-shadow:
    0 18px 30px rgba(0,0,0,.18),
    0 0 30px rgba(0,223,130,.12);
  animation:shapeMoveTwo 5.6s ease-in-out infinite;
}

/* shape atas tengah */
.shapeThree{
  width:126px;
  height:104px;
  left:90px;
  top:-44px;
  border-radius:38% 62% 64% 36% / 40% 44% 56% 60%;
  background:
    linear-gradient(145deg, rgba(214,255,240,.22), rgba(0,223,130,.08));
  border:1px solid rgba(255,255,255,.07);
  animation:shapeMoveThree 7s ease-in-out infinite;
}

    .hero::before{
      content:"";
      position:absolute;
      left:-42px;
      top:-20px;
      width:185px;
      height:162px;
      border-radius:44% 56% 54% 46% / 42% 44% 56% 58%;
      background:rgba(13,127,103,.35);
      animation:blobFloat 6s ease-in-out infinite;
    }

    .hero::after{
      content:"";
      position:absolute;
      right:-30px;
      top:-34px;
      width:154px;
      height:132px;
      border-radius:34px 0 0 64px;
      background:rgba(235,255,248,.72);
      animation:blobFloat2 7s ease-in-out infinite;
    }

    .heroShape{
      position:absolute;
      pointer-events:none;
    }

    .shapeOne{
      width:165px;
      height:126px;
      right:-24px;
      top:25px;
      border-radius:48% 52% 58% 42% / 42% 48% 52% 58%;
      background:rgba(11,143,112,.26);
      animation:shapeMoveOne 6.2s ease-in-out infinite;
    }

    .shapeTwo{
      width:90px;
      height:90px;
      right:52px;
      top:80px;
      border-radius:999px;
      background:
        radial-gradient(circle at 30% 25%, rgba(255,255,255,.85), rgba(203,255,235,.42) 38%, rgba(13,127,103,.18) 100%);
      box-shadow:0 18px 28px rgba(6,34,29,.13);
      animation:shapeMoveTwo 5.6s ease-in-out infinite;
    }

    .shapeThree{
      width:122px;
      height:100px;
      left:88px;
      top:-42px;
      border-radius:38% 62% 64% 36% / 40% 44% 56% 60%;
      background:rgba(234,255,248,.58);
      animation:shapeMoveThree 7s ease-in-out infinite;
    }

.backBubble{
  position:absolute;
  top:20px;
  left:20px;
  z-index:5;

  width:auto;
  height:auto;
  padding:0;

  border-radius:0;
  background:transparent;
  box-shadow:none;
  border:none;

  display:inline-flex;
  align-items:center;
  justify-content:flex-start;
  gap:5px;

  color:#ffffff;
  font-size:12px;
  font-weight:800;
  text-decoration:none;
}
.backBubble span{
  font-size:22px;
  line-height:1;
  font-weight:700;
  margin-top:-2px;
}
.card{
  position:relative;
  z-index:5;
  width:100%;
  margin:-30px 0 0;
  min-height:calc(100vh - 146px);
  background:
    radial-gradient(220px 140px at 100% 100%, rgba(98,221,177,.22), transparent 60%),
    radial-gradient(180px 120px at 78% 18%, rgba(255,255,255,.16), transparent 58%),
    linear-gradient(135deg, #e8fbf2 0%, #d7f5e7 52%, #c8efd9 100%);
  border:none;
  border-radius:28px 28px 0 0;
  padding:24px 24px 24px;
  box-shadow:0 -12px 32px rgba(6,34,29,.10);
  animation:cardFloat .55s ease both;
  overflow:hidden;
}

.card::before{
  content:"";
  position:absolute;
  inset:0;
  pointer-events:none;
  background:
    radial-gradient(180px 120px at 20% 0%, rgba(255,255,255,.22), transparent 60%),
    radial-gradient(260px 180px at 100% 100%, rgba(24,199,155,.10), transparent 66%);
  opacity:1;
}

.card > *{
  position:relative;
  z-index:1;
}

    .brandInside{
      display:flex;
      flex-direction:column;
      align-items:center;
      text-align:center;
      margin:-2px 0 20px;
    }

    .logoBox{
      width:70px;
      height:70px;
      border-radius:24px;
      background:#ffffff;
      border:1px solid rgba(24,199,155,.16);
      box-shadow:
        0 16px 34px rgba(10,47,39,.10),
        0 0 0 1px rgba(255,255,255,.75) inset;
      display:grid;
      place-items:center;
      overflow:hidden;
      margin-bottom:12px;
    }

    .logoBox img{
      width:50px;
      height:50px;
      object-fit:contain;
      display:block;
    }

    .brandTitle{
      margin:0;
      font-size:15px;
      line-height:1.15;
      font-weight:800;
        color:#0d7f67;
      letter-spacing:-.01em;
    }

    .brandSub{
      width:min(275px, 100%);
      margin:7px 0 0;
      font-size:12.5px;
      line-height:1.45;
      color:#66877d;
      font-weight:500;
    }

    .title{
      text-align:center;
      margin:0;
      font-size:25px;
      line-height:1.08;
      font-weight:800;
        color:#173d35;
      letter-spacing:-.04em;
    }

    .subtitle{
      text-align:center;
      margin:9px 0 20px;
        color:#6c8b82;
      font-size:13px;
      line-height:1.5;
      font-weight:500;
    }

    .error{
      margin-bottom:14px;
      padding:11px 12px;
      border-radius:14px;
      background:rgba(239,68,68,.08);
      border:1px solid rgba(239,68,68,.18);
      color:#b42318;
      font-size:12.5px;
      line-height:1.45;
      font-weight:500;
    }

    .field{
      margin-bottom:13px;
    }

    .label{
      display:block;
      margin:0 0 7px;
      font-size:11.5px;
      line-height:1.2;
       color:#668078;
      font-weight:700;
    }

    .inputWrap{
      position:relative;
    }

.input{
  width:100%;
  height:50px;
  border-radius:15px;
  border:1px solid rgba(13,127,103,.14);

  background:
    radial-gradient(circle at 90% 0%, rgba(255,255,255,.72), transparent 42%),
    linear-gradient(135deg, rgba(238,255,248,.92), rgba(216,246,233,.82));

  outline:none;
  padding:0 14px;

  font-size:13.5px;
  color:#10322c;
  font-weight:700;

  transition:
    border-color .18s ease,
    box-shadow .18s ease,
    background .18s ease,
    transform .18s ease;

  box-shadow:
    0 10px 22px rgba(6,34,29,.045),
    inset 0 1px 0 rgba(255,255,255,.62);

  backdrop-filter:blur(8px);
  -webkit-backdrop-filter:blur(8px);
}

.input.input-phone{
  padding-left:74px;
}

    .input.input-password{
      padding-right:48px;
    }

.input:focus{
  border-color:rgba(0,201,122,.36);
  background:
    radial-gradient(circle at 90% 0%, rgba(255,255,255,.82), transparent 42%),
    linear-gradient(135deg, rgba(248,255,252,.98), rgba(214,250,234,.92));

  box-shadow:
    0 0 0 4px rgba(0,201,122,.10),
    0 12px 24px rgba(6,34,29,.065),
    inset 0 1px 0 rgba(255,255,255,.74);

  transform:translateY(-1px);
}

    .input::placeholder{
      color:#b7c6c0;
      font-weight:400;
    }

.prefix62{
  position:absolute;
  top:50%;
  left:14px;
  transform:translateY(-50%);
  z-index:3;

  min-width:46px;
  height:28px;
  padding:0 9px;

  display:inline-flex;
  align-items:center;
  justify-content:center;

  border-radius:999px;
  background:
    radial-gradient(circle at 30% 0%, rgba(255,255,255,.78), transparent 40%),
    linear-gradient(135deg, rgba(232,255,246,.96), rgba(198,239,222,.84));

  border:1px solid rgba(13,127,103,.12);
  color:#0d7f67;

  font-size:12.5px;
  line-height:1;
  font-weight:850;

  box-shadow:
    inset 0 1px 0 rgba(255,255,255,.68),
    0 6px 12px rgba(6,34,29,.045);

  pointer-events:none;
}
    .togglePass{
      position:absolute;
      top:50%;
      right:9px;
      transform:translateY(-50%);
      width:34px;
      height:34px;
      border:none;
      border-radius:10px;
      background:transparent;
      cursor:pointer;
      display:grid;
      place-items:center;
      color:#72a196;
      transition:.18s ease;
    }

    .togglePass:hover{
      background:#f0fbf7;
      color:var(--green-dark);
    }

    .togglePass svg{
      width:18px;
      height:18px;
      display:block;
    }

    .helperRow{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:10px;
      margin:8px 0 16px;
      flex-wrap:wrap;
    }

    .helperText{
      font-size:11.5px;
      line-height:1.4;
      color:var(--muted);
      font-weight:500;
    }

    .helperLink{
      font-size:11.5px;
      line-height:1.4;
      color:var(--green-dark);
      font-weight:800;
      text-decoration:none;
    }

    .helperLink:hover{
      text-decoration:underline;
    }

    .btn{
  width:100%;
  height:52px;
  border:none;
  border-radius:14px;
  cursor:pointer;
  color:#ffffff;
  font-size:14px;
  font-weight:800;
  letter-spacing:.01em;

  background:
    radial-gradient(circle at 30% 0%, rgba(255,255,255,.16), transparent 34%),
    linear-gradient(135deg, #031816 0%, #0a2f27 35%, #0d5c46 68%, #00c97a 100%);

  box-shadow:
    0 14px 28px rgba(0,0,0,.18),
    0 10px 26px rgba(0,223,130,.18),
    inset 0 1px 0 rgba(255,255,255,.12);

  transition:
    transform .2s ease,
    box-shadow .2s ease,
    filter .2s ease,
    background .2s ease;

  position:relative;
  overflow:hidden;
}

.btn::before{
  content:"";
  position:absolute;
  inset:0;
  background:
    linear-gradient(180deg, rgba(255,255,255,.08), transparent 40%);
  pointer-events:none;
}

.btn::after{
  content:"";
  position:absolute;
  top:0;
  left:-120%;
  width:55%;
  height:100%;
  background:linear-gradient(
    to right,
    transparent,
    rgba(255,255,255,.22),
    transparent
  );
  transform:skewX(-18deg);
  animation:btnShine 3.2s infinite;
  pointer-events:none;
}

.btn:hover{
  transform:translateY(-1px);
  filter:brightness(1.03);
  box-shadow:
    0 18px 34px rgba(0,0,0,.22),
    0 14px 34px rgba(0,223,130,.22),
    inset 0 1px 0 rgba(255,255,255,.14);
}

.btn:active{
  transform:translateY(0);
  filter:brightness(.99);
}
    .divider{
      display:flex;
      align-items:center;
      gap:10px;
      margin:20px 0 14px;
      color:#a0b2ac;
      font-size:11.5px;
      font-weight:600;
      text-align:center;
    }

    .divider::before,
    .divider::after{
      content:"";
      flex:1;
      height:1px;
      background:#e5efeb;
    }

    .socials{
      display:grid;
      grid-template-columns:repeat(3, 1fr);
      gap:10px;
      margin-bottom:15px;
    }

    .socialBtn{
      height:42px;
      border-radius:13px;
      border:1px solid #e1efe9;
      background:#ffffff;
      color:#2d5c52;
      display:flex;
      align-items:center;
      justify-content:center;
      gap:7px;
      font-size:12px;
      font-weight:700;
      cursor:pointer;
      transition:.18s ease;
    }

    .socialBtn:hover{
      background:#f6fffb;
      border-color:#cfe9de;
      transform:translateY(-1px);
    }

    .socialBtn svg{
      width:17px;
      height:17px;
      display:block;
    }

    .footer{
      text-align:center;
      font-size:12.5px;
      line-height:1.5;
      color:var(--muted);
      font-weight:500;
    }

    .footer a{
      color:var(--green-dark);
      font-weight:800;
      text-decoration:none;
    }

    .footer a:hover{
      text-decoration:underline;
    }

    .miniNote{
      display:none;
    }

    @keyframes pageEnter{
      from{
        opacity:0;
        transform:translateY(12px);
      }
      to{
        opacity:1;
        transform:translateY(0);
      }
    }

    @keyframes cardFloat{
      from{
        opacity:0;
        transform:translateY(14px);
      }
      to{
        opacity:1;
        transform:translateY(0);
      }
    }

    @keyframes blobFloat{
      0%,100%{
        transform:translate3d(0,0,0) rotate(0deg);
      }
      50%{
        transform:translate3d(-8px,8px,0) rotate(5deg);
      }
    }

    @keyframes blobFloat2{
      0%,100%{
        transform:translate3d(0,0,0);
      }
      50%{
        transform:translate3d(7px,-7px,0);
      }
    }

    @keyframes shapeMoveOne{
      0%,100%{
        transform:translate3d(0,0,0) rotate(0deg);
      }
      50%{
        transform:translate3d(-8px,6px,0) rotate(4deg);
      }
    }

    @keyframes shapeMoveTwo{
      0%,100%{
        transform:translate3d(0,0,0) scale(1);
      }
      50%{
        transform:translate3d(-4px,-8px,0) scale(1.04);
      }
    }

    @keyframes shapeMoveThree{
      0%,100%{
        transform:translate3d(0,0,0) rotate(0deg);
      }
      50%{
        transform:translate3d(7px,5px,0) rotate(-4deg);
      }
    }

@media (max-width:380px){
  body{
    padding:0;
  }

  .page{
    max-width:none;
  }

      .hero{
        height:168px;
      }

      .card{
        padding:22px 20px 22px;
      }

      .title{
        font-size:24px;
      }

      .socials{
        grid-template-columns:1fr;
      }
    }

    @media (prefers-reduced-motion: reduce){
      *,
      *::before,
      *::after{
        animation:none !important;
        transition:none !important;
      }
    }
  </style>
</head>
<body>
  <main class="page">
    <section class="shell" role="region" aria-label="Login Crowdnik">

      <div class="hero">
        <a href="/login" class="backBubble" aria-label="Kembali">
          <span>‹</span>
          Back
        </a>

        <div class="heroShape shapeOne"></div>
        <div class="heroShape shapeTwo"></div>
        <div class="heroShape shapeThree"></div>
      </div>

      <div class="card">
        <div class="brandInside">
          <div class="logoBox">
            <img src="/logo.png" alt="Crowdnik">
          </div>
        </div>

        <h1 class="title">Get Started</h1>
        <p class="subtitle">Masuk untuk melanjutkan ke akun Rubik.</p>

        {{-- ERROR LOGIN --}}
        @if ($errors->any())
          <div class="error">
            {{ $errors->first() }}
          </div>
        @endif

        <form method="POST" action="/login" autocomplete="off" novalidate>
          @csrf

          <div class="field">
            <label class="label" for="phone">Nomor Telepon</label>
            <div class="inputWrap">
              <span class="prefix62">+62</span>
              <input
                class="input input-phone"
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
            <label class="label" for="password">Password</label>
            <div class="inputWrap">
              <input
                class="input input-password"
                id="password"
                type="password"
                name="password"
                placeholder="Masukkan password"
                required
              />

              <button class="togglePass" type="button" onclick="togglePassword()" aria-label="Tampilkan password">
                <svg id="eyeIcon" viewBox="0 0 24 24" fill="none">
                  <path d="M1.5 12s4-7.5 10.5-7.5S22.5 12 22.5 12 18.5 19.5 12 19.5 1.5 12 1.5 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </button>
            </div>
          </div>

          <div class="helperRow">
            <div class="helperText">Pastikan nomor dan password sudah benar.</div>
            <a class="helperLink" href="/register">Belum punya akun?</a>
          </div>

          <button class="btn" type="submit">Login</button>
        </form>


        <div class="footer">
          Belum punya akun? <a href="/register">Register Now</a>
        </div>
      </div>
    </section>
  </main>

  <script>
    function togglePassword(){
      const input = document.getElementById('password');
      const icon = document.getElementById('eyeIcon');

      if(!input) return;

      const isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';

      if(icon){
        icon.innerHTML = isHidden
          ? '<path d="M3 3l18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10.58 10.58A2 2 0 0 0 12 14a2 2 0 0 0 1.42-.58" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M9.88 5.09A9.77 9.77 0 0 1 12 4.86C18.5 4.86 22.5 12 22.5 12a17.56 17.56 0 0 1-3.09 4.08" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.61 6.61C3.32 8.78 1.5 12 1.5 12s4 7.14 10.5 7.14a9.9 9.9 0 0 0 4.1-.88" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'
          : '<path d="M1.5 12s4-7.5 10.5-7.5S22.5 12 22.5 12 18.5 19.5 12 19.5 1.5 12 1.5 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
      }
    }
  </script>
</body>
</html>