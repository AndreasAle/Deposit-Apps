<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'TumbuhMaju')</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

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
      --font-sans: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
      --font-mono: "JetBrains Mono", ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    }

    *{ box-sizing:border-box; }
    html,body{ height:100%; }
    body{
      margin:0;
      color:var(--text);
      font-family: var(--font-sans);
      min-height:100vh;
      overflow-x:hidden;
      -webkit-tap-highlight-color: transparent;

      /* WAJIB: background theme */
      background:
        radial-gradient(1100px 600px at 12% 8%, rgba(59,130,246,.18), transparent 60%),
        radial-gradient(900px 520px at 90% 18%, rgba(14,165,233,.14), transparent 55%),
        radial-gradient(900px 520px at 50% 105%, rgba(124,58,237,.10), transparent 60%),
        linear-gradient(180deg, #ffffff 0%, #f5f7ff 55%, #eef2ff 100%);
    }

    /* ===== Glass Navigation (white) ===== */
    .glass-nav{
      position: sticky;
      top:0;
      z-index: 50;

      background: rgba(255,255,255,.70);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);

      border-bottom: 1px solid var(--border);
      box-shadow: 0 14px 34px rgba(15,23,42,.08);
    }

    .nav-container{
      max-width: 1024px;
      margin: 0 auto;
      padding: 14px 14px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap: 12px;
    }

    .brand{
      display:flex;
      align-items:center;
      gap: 10px;
      min-width:0;
    }

    .brandMark{
      width:40px; height:40px;
      border-radius: 14px;
      background: rgba(255,255,255,.86);
      border: 1px solid rgba(6,182,212,.18);
      box-shadow: var(--shadow-soft);
      display:flex;
      align-items:center;
      justify-content:center;
      flex: 0 0 auto;
    }
    .brandMark img{
      width:24px; height:24px;
      object-fit:contain;
      display:block;
      filter: drop-shadow(0 8px 16px rgba(15,23,42,.10));
    }

    .brandText{
      display:flex;
      flex-direction:column;
      min-width:0;
    }
    .brandTitle{
      font-weight: 950;
      font-size: 14px;
      letter-spacing: -0.02em;
      line-height: 1.1;
      margin:0;
      background: linear-gradient(135deg, var(--primary1), var(--primary2));
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width: 40vw;
    }
    .brandSub{
      margin: 4px 0 0;
      font-size: 12px;
      color: var(--muted);
      font-weight: 700;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width: 40vw;
    }

    .nav-actions{
      display:flex;
      align-items:center;
      gap: 8px;
      flex: 0 0 auto;
    }

    .nav-link{
      display:inline-flex;
      align-items:center;
      gap: 8px;

      padding: 9px 12px;
      border-radius: 999px;

      text-decoration:none;
      color: var(--muted);
      font-size: 12.5px;
      font-weight: 900;

      background: rgba(255,255,255,.78);
      border: 1px solid rgba(15,23,42,.10);
      box-shadow: 0 12px 20px rgba(15,23,42,.06);

      transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease, color .18s ease;
      user-select:none;
      white-space:nowrap;
    }
    .nav-link:hover{
      transform: translateY(-1px);
      color: #081022;
      border-color: rgba(6,182,212,.28);
      box-shadow: 0 16px 28px rgba(15,23,42,.10);
    }
    .nav-link:focus{
      outline:none;
      box-shadow: 0 0 0 4px rgba(6,182,212,.14), 0 16px 28px rgba(15,23,42,.10);
      border-color: rgba(6,182,212,.40);
    }

    .logout-btn{
      appearance:none;
      border: 0;
      padding: 9px 12px;
      border-radius: 999px;

      font-size: 12.5px;
      font-weight: 950;
      letter-spacing: .01em;

      background: rgba(255,255,255,.78);
      border: 1px solid rgba(239,68,68,.22);
      color: #b91c1c;

      box-shadow: 0 12px 20px rgba(15,23,42,.06);
      cursor:pointer;

      transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease, filter .18s ease;
      user-select:none;
      white-space:nowrap;
    }
    .logout-btn:hover{
      transform: translateY(-1px);
      border-color: rgba(239,68,68,.30);
      box-shadow: 0 16px 28px rgba(15,23,42,.10);
      filter: brightness(1.01);
    }
    .logout-btn:focus{
      outline:none;
      box-shadow: 0 0 0 4px rgba(239,68,68,.12), 0 16px 28px rgba(15,23,42,.10);
    }

    @media (max-width: 520px){
      .brandSub{ display:none; }
      .nav-link span.txt{ display:none; }
    }

    /* ===== Main container ===== */
    main{
      max-width: 1024px;
      margin: 0 auto;
      padding: 16px 14px 28px;
      position:relative;
      z-index: 10;
    }

    /* ===== Toast (premium white) ===== */
    #toast{
      position: fixed;
      right: 16px;
      bottom: 16px;
      z-index: 9999;
      display:none;
      max-width: min(420px, calc(100vw - 32px));
    }

    #toast.show{ display:block; }

    #toastBox{
      background: rgba(255,255,255,.86);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);

      border: 1px solid rgba(15,23,42,.10);
      box-shadow: 0 24px 60px rgba(15,23,42,.16);

      border-radius: 16px;
      padding: 12px 14px;

      display:flex;
      align-items:flex-start;
      gap: 10px;

      font-size: 13px;
      font-weight: 800;
      color: var(--text);

      animation: toastIn .28s ease-out both;
    }

    @keyframes toastIn{
      from{ transform: translateY(14px); opacity: 0; }
      to{ transform: translateY(0); opacity: 1; }
    }

    .toast-success{
      border-left: 4px solid rgba(16,185,129,.90) !important;
    }
    .toast-error{
      border-left: 4px solid rgba(239,68,68,.90) !important;
    }

    /* Helper for pages that still use monospace numbers */
    .mono{ font-family: var(--font-mono); }
  </style>
</head>

<body>

  <nav class="glass-nav">
    <div class="nav-container">
      <div class="brand">
        <div class="brandMark" aria-hidden="true">
          <img src="/logo.png" alt="Logo" />
        </div>
        <div class="brandText">
          <p class="brandTitle">TumbuhMaju</p>
          <p class="brandSub">Crowdnik Premium White</p>
        </div>
      </div>

      <div class="nav-actions">
        <a href="/ui/payout-accounts" class="nav-link">
          <span aria-hidden="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0f172a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:.75">
              <rect x="3" y="4" width="18" height="16" rx="2"></rect>
              <path d="M3 10h18"></path>
            </svg>
          </span>
          <span class="txt">Akun Bank</span>
        </a>

        <a href="/ui/withdrawals" class="nav-link">
          <span aria-hidden="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0f172a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:.75">
              <path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4"></path>
              <path d="M4 6v12c0 1.1.9 2 2 2h14v-4"></path>
              <path d="M18 12a2 2 0 0 0-2 2c0 1.1.9 2 2 2h4v-4h-4z"></path>
            </svg>
          </span>
          <span class="txt">Withdraw</span>
        </a>

        <form method="POST" action="/logout" style="margin:0;">
          @csrf
          <button class="logout-btn" type="submit">
            Logout
          </button>
        </form>
      </div>
    </div>
  </nav>

  <main>
    @yield('content')
  </main>

  <div id="toast" aria-live="polite" aria-atomic="true">
    <div id="toastBox"></div>
  </div>

  <script>
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Premium White Toast
    function toast(msg, type='ok') {
      const el = document.getElementById('toast');
      const box = document.getElementById('toastBox');

      const isErr = type === 'err';
      box.className = (isErr ? 'toast-error' : 'toast-success');

      const icon = isErr
        ? `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
             <circle cx="12" cy="12" r="10"></circle>
             <path d="M12 8v5"></path>
             <path d="M12 16h.01"></path>
           </svg>`
        : `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
             <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
             <path d="M22 4 12 14.01 9 11.01"></path>
           </svg>`;

      // keep safe text render
      const safe = String(msg ?? '').replace(/[&<>"']/g, (c) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
      }[c]));

      box.innerHTML = `${icon}<div style="line-height:1.35"><div style="font-weight:950;color:var(--text)">${isErr ? 'Gagal' : 'Berhasil'}</div><div style="margin-top:2px;color:var(--muted);font-weight:800">${safe}</div></div>`;

      el.classList.add('show');

      clearTimeout(window.__TOAST_T__);
      window.__TOAST_T__ = setTimeout(() => el.classList.remove('show'), 3000);
    }

    async function api(url, opts = {}) {
      const res = await fetch(url, {
        headers: {
          'Accept': 'application/json',
          ...(opts.body instanceof FormData ? {} : {'Content-Type': 'application/json'}),
          'X-CSRF-TOKEN': csrf,
        },
        credentials: 'same-origin',
        ...opts,
      });

      if (!res.ok) {
        let data = null;
        try { data = await res.json(); } catch {}
        const msg =
          data?.message ||
          (data?.errors ? Object.values(data.errors).flat().join(', ') : null) ||
          `Request gagal (${res.status})`;
        throw new Error(msg);
      }

      const text = await res.text();
      return text ? JSON.parse(text) : null;
    }

    // Badge (Premium White)
    function badge(status) {
      const map = {
        PENDING:   "background:rgba(245,158,11,.12); color:#b45309; border:1px solid rgba(245,158,11,.25);",
        APPROVED:  "background:rgba(59,130,246,.12); color:#1d4ed8; border:1px solid rgba(59,130,246,.22);",
        PAID:      "background:rgba(16,185,129,.12); color:#047857; border:1px solid rgba(16,185,129,.22); box-shadow:0 10px 18px rgba(16,185,129,.10);",
        REJECTED:  "background:rgba(239,68,68,.10); color:#b91c1c; border:1px solid rgba(239,68,68,.22);",
        CANCELLED: "background:rgba(100,116,139,.12); color:#475569; border:1px solid rgba(100,116,139,.20);",
      };

      const style = map[status] || "background:rgba(100,116,139,.10); color:#475569; border:1px solid rgba(100,116,139,.20);";
      const safe = String(status ?? '').replace(/[&<>"']/g, (c) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
      }[c]));

      return `<span style="padding:6px 10px; border-radius:999px; font-size:10px; font-weight:950; letter-spacing:.08em; text-transform:uppercase; ${style}">${safe}</span>`;
    }
  </script>

  @stack('scripts')
</body>
</html>
