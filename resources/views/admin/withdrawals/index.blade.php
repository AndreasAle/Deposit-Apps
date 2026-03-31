<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Admin • Withdrawals</title>
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
      flex:0 0 auto;
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
      white-space:nowrap;
    }
    .btnGhost:hover{ transform:translateY(-1px); background: rgba(255,255,255,.92); box-shadow:0 16px 30px rgba(15,23,42,.10); }
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

    /* toolbar */
    .toolbar{
      display:flex;
      justify-content:space-between;
      gap:10px;
      flex-wrap:wrap;
      align-items:center;
      margin-bottom: 12px;
    }

    .select{
      width: 240px;
      max-width:100%;
      border-radius: var(--radius-sm);
      border: 1px solid rgba(15,23,42,.12);
      background: rgba(255,255,255,.72);
      padding: 12px 12px;
      font-weight:1000;
      color: var(--text);
      outline:none;
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
      transition: box-shadow .15s ease, border-color .15s ease, background .15s ease;
      appearance:none;
    }
    .select:focus{
      border-color: rgba(6,182,212,.40);
      box-shadow: 0 0 0 4px rgba(6,182,212,.14), 0 14px 30px rgba(15,23,42,.08);
      background: rgba(255,255,255,.86);
    }

    .btnPrimary{
      border:0;
      border-radius: var(--radius-sm);
      padding: 12px 14px;
      font-size: 13px;
      font-weight:1000;
      color: #081022;
      cursor:pointer;
      background: linear-gradient(135deg, var(--primary1) 0%, var(--primary2) 100%);
      box-shadow: 0 18px 42px rgba(109,40,217,.20), 0 14px 34px rgba(6,182,212,.12);
      transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
      display:inline-flex; align-items:center; justify-content:center; gap:10px;
      -webkit-tap-highlight-color: transparent;
      white-space:nowrap;
    }
    .btnPrimary:hover{
      transform: translateY(-1px);
      filter: saturate(1.06);
      box-shadow: 0 24px 60px rgba(109,40,217,.22), 0 18px 44px rgba(6,182,212,.14);
    }

    .panel{
      border-radius: var(--radius);
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.72);
      box-shadow: var(--shadow-soft);
      padding:16px;
    }

    /* rows */
    .rows{ display:flex; flex-direction:column; gap:12px; }
    .rowCard{
      border-radius: 18px;
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.72);
      box-shadow: 0 12px 26px rgba(15,23,42,.08);
      overflow:hidden;
      display:flex;
      min-height: 74px;
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
    }
    .rowCard:hover{
      transform: translateY(-1px);
      background: rgba(255,255,255,.86);
      box-shadow: 0 22px 48px rgba(15,23,42,.12);
    }
    .bar{ width:4px; flex:0 0 auto; }
    .barPending{ background: rgba(234,179,8,.85); }
    .barApproved{ background: rgba(59,130,246,.85); }
    .barPaid{ background: rgba(16,185,129,.85); }
    .barRejected{ background: rgba(239,68,68,.85); }
    .barCancelled{ background: rgba(100,116,139,.85); }

    .rowBody{
      flex:1;
      padding:12px 14px;
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
    }
    .left{ min-width:0; }
    .right{
      text-align:right;
      flex:0 0 auto;
      display:flex;
      flex-direction:column;
      align-items:flex-end;
      gap:8px;
    }

    .line1{
      display:flex;
      align-items:center;
      gap:10px;
      flex-wrap:wrap;
    }

    .amount{
      font-weight:1000;
      font-size:14px;
      letter-spacing:-0.01em;
      white-space:nowrap;
      color: var(--text);
    }

    .badge{
      font-size:11px;
      font-weight:1000;
      letter-spacing:.08em;
      text-transform:uppercase;
      padding:6px 10px;
      border-radius: 999px;
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.86);
      color: var(--muted);
      white-space:nowrap;
    }

    .meta{
      margin-top:4px;
      font-size:12px;
      font-weight:800;
      color: var(--muted);
      line-height:1.5;
    }
    .meta b{ color: var(--text); }

    .rejectReason{
      margin-top:8px;
      font-size:12px;
      font-weight:900;
      color:#b91c1c;
      background: rgba(239,68,68,.08);
      border: 1px solid rgba(239,68,68,.18);
      border-radius: 14px;
      padding: 10px 12px;
    }

    .btnMini{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:8px;
      padding:10px 12px;
      border-radius: 14px;
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.80);
      color: var(--text);
      font-weight:1000;
      font-size:12px;
      cursor:pointer;
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
      white-space:nowrap;
      text-decoration:none;
    }
    .btnMini:hover{ transform: translateY(-1px); background: rgba(255,255,255,.92); box-shadow: 0 16px 34px rgba(15,23,42,.10); }

    .btnApprove{
      border-color: rgba(59,130,246,.18);
      background: rgba(59,130,246,.10);
      color:#075985;
    }
    .btnReject{
      border-color: rgba(239,68,68,.18);
      background: rgba(239,68,68,.08);
      color:#b91c1c;
    }
    .btnPaid{
      border-color: rgba(16,185,129,.18);
      background: rgba(16,185,129,.10);
      color:#065f46;
    }
    .btnProof{
      border-color: rgba(15,23,42,.12);
      background: rgba(15,23,42,.86);
      color:#fff;
    }

    /* modal */
    .modalOverlay{
      position:fixed;
      inset:0;
      display:none;
      align-items:center;
      justify-content:center;
      background: rgba(15,23,42,.35);
      padding: 18px;
      z-index: 50;
    }
    .modalOverlay.show{ display:flex; }

    .modal{
      width:100%;
      max-width: 560px;
      border-radius: var(--radius);
      border: 1px solid rgba(15,23,42,.10);
      background: linear-gradient(180deg, var(--card) 0%, var(--card-strong) 100%);
      box-shadow: var(--shadow);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      overflow:hidden;
      position:relative;
    }
    .modal::before{
      content:"";
      position:absolute;
      inset:-2px;
      background:
        radial-gradient(520px 220px at 10% 0%, rgba(109,40,217,.10), transparent 60%),
        radial-gradient(520px 220px at 90% 10%, rgba(6,182,212,.10), transparent 55%);
      pointer-events:none;
    }
    .modalInner{ position:relative; padding:16px; }
    .modalHead{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:10px;
      padding-bottom: 10px;
      border-bottom: 1px solid rgba(15,23,42,.08);
      margin-bottom: 12px;
    }
    .modalTitle{
      font-weight:1000;
      color: var(--text);
      letter-spacing:-0.01em;
    }
    .closeBtn{
      width:36px;height:36px;
      border-radius: 12px;
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.78);
      cursor:pointer;
      display:flex;align-items:center;justify-content:center;
      font-weight:1000;
      color: var(--text);
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
    }
    .closeBtn:hover{ transform: translateY(-1px); background: rgba(255,255,255,.92); box-shadow: 0 16px 34px rgba(15,23,42,.10); }

    .textarea, .fileInput{
      width:100%;
      border-radius: var(--radius-sm);
      border: 1px solid rgba(15,23,42,.12);
      background: rgba(255,255,255,.70);
      padding: 12px 12px;
      font-weight:900;
      color: var(--text);
      outline:none;
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
      transition: box-shadow .15s ease, border-color .15s ease, background .15s ease;
    }
    .textarea:focus, .fileInput:focus{
      border-color: rgba(6,182,212,.40);
      box-shadow: 0 0 0 4px rgba(6,182,212,.14), 0 14px 30px rgba(15,23,42,.08);
      background: rgba(255,255,255,.86);
    }

    .modalActions{
      display:flex;
      gap:10px;
      justify-content:flex-end;
      flex-wrap:wrap;
      margin-top: 12px;
    }

    .btnNeutral{
      border-radius: var(--radius-sm);
      padding: 12px 14px;
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.78);
      font-weight:1000;
      cursor:pointer;
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
    }
    .btnNeutral:hover{ transform: translateY(-1px); background: rgba(255,255,255,.92); box-shadow: 0 16px 34px rgba(15,23,42,.10); }

    .btnDanger{
      border: 0;
      border-radius: var(--radius-sm);
      padding: 12px 14px;
      font-weight:1000;
      color: #fff;
      background: linear-gradient(135deg, rgba(239,68,68,.95) 0%, rgba(244,63,94,.88) 100%);
      cursor:pointer;
      box-shadow: 0 18px 42px rgba(239,68,68,.18);
      transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
    }
    .btnDanger:hover{ transform: translateY(-1px); filter:saturate(1.06); box-shadow: 0 24px 60px rgba(239,68,68,.22); }

    .hint{
      font-size:12px;
      font-weight:800;
      color: var(--muted);
      line-height:1.5;
    }

    .empty{
      border-radius: var(--radius);
      border: 1px dashed rgba(15,23,42,.18);
      background: rgba(255,255,255,.66);
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
      padding: 18px;
      text-align:center;
      color: var(--muted);
      font-weight:900;
    }
  </style>
</head>

<body>
  <div class="wrap">
    <main class="card" role="main" aria-label="Admin Withdrawals">
      <div class="card-inner">

        <header class="header">
          <div class="brand">
            <div class="logoBox" aria-hidden="true">
              <img src="/logo.png" alt="Logo" />
            </div>
            <div class="titleBlock">
              <h1 class="title">Admin • Withdrawals</h1>
              <p class="subtitle">Kelola request WD: approve, reject, dan set paid dengan bukti transfer.</p>
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

        <div class="toolbar">
          <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
            <select id="status" class="select" aria-label="Filter status">
              <option value="">All</option>
              <option value="PENDING">PENDING</option>
              <option value="APPROVED">APPROVED</option>
              <option value="PAID">PAID</option>
              <option value="REJECTED">REJECTED</option>
              <option value="CANCELLED">CANCELLED</option>
            </select>

            <button id="btnLoad" class="btnPrimary" type="button">
              <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M21 12a9 9 0 1 1-3-6.7"></path>
                <path d="M21 3v7h-7"></path>
              </svg>
              <span>Load</span>
            </button>
          </div>

          <div class="hint">Tips: gunakan filter untuk memudahkan pengecekan harian.</div>
        </div>

        <section class="panel" aria-label="Daftar Withdrawals">
          <div id="rows" class="rows">Loading...</div>
        </section>

      </div>
    </main>
  </div>

  <!-- Modal Reject -->
  <div id="rejectModal" class="modalOverlay" aria-hidden="true">
    <div class="modal" role="dialog" aria-modal="true" aria-label="Reject Withdraw">
      <div class="modalInner">
        <div class="modalHead">
          <div class="modalTitle">Reject Withdraw</div>
          <button class="closeBtn" type="button" onclick="closeReject()">×</button>
        </div>

        <div class="hint" style="margin-bottom:10px;">Masukkan alasan penolakan agar user jelas dan bisa follow up.</div>
        <textarea id="rejectReason" class="textarea" rows="4" placeholder="Alasan reject..." style="resize:none"></textarea>

        <div class="modalActions">
          <button class="btnNeutral" type="button" onclick="closeReject()">Batal</button>
          <button id="btnRejectSubmit" class="btnDanger" type="button">Reject</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Paid -->
  <div id="paidModal" class="modalOverlay" aria-hidden="true">
    <div class="modal" role="dialog" aria-modal="true" aria-label="Mark as Paid">
      <div class="modalInner">
        <div class="modalHead">
          <div class="modalTitle">Mark as Paid</div>
          <button class="closeBtn" type="button" onclick="closePaid()">×</button>
        </div>

        <div class="hint">Upload bukti transfer (opsional). Disarankan untuk audit.</div>
        <div style="margin-top:10px;">
          <input id="paidProof" type="file" class="fileInput" />
        </div>

        <div class="modalActions">
          <button class="btnNeutral" type="button" onclick="closePaid()">Batal</button>
          <button id="btnPaidSubmit" class="btnPrimary" type="button">Set Paid</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    const rowsEl = document.getElementById('rows');
    const statusSel = document.getElementById('status');

    let rejectId = null;
    let paidId = null;

    function rupiah(n) {
      try { return new Intl.NumberFormat('id-ID').format(n); } catch { return String(n); }
    }

    // status badge & color bar
    function statusBarClass(status) {
      const s = String(status || '').toUpperCase();
      if (s === 'PENDING') return 'barPending';
      if (s === 'APPROVED') return 'barApproved';
      if (s === 'PAID') return 'barPaid';
      if (s === 'REJECTED') return 'barRejected';
      if (s === 'CANCELLED') return 'barCancelled';
      return 'barPending';
    }

    function badge(status) {
      const s = String(status || '').toUpperCase();
      return `<span class="badge">${s || '-'}</span>`;
    }

    // NOTE:
    // - fungsi api() dan toast() diasumsikan sudah ada global di project kamu (seperti yang kamu pakai).
    // - kalau belum, bilang aja, nanti gue buatin helper-nya.
    async function loadAdmin() {
      const status = statusSel.value;
      const url = status ? `/admin/withdrawals?status=${encodeURIComponent(status)}` : '/admin/withdrawals';

      const res = await api(url);
      const rows = res?.data || [];

      if (!rows.length) {
        rowsEl.innerHTML = `<div class="empty">Tidak ada data.</div>`;
        return;
      }

      rowsEl.innerHTML = rows.map(r => {
        const acct = r.payout_account || r.payoutAccount;
        const user = r.user;

        const created = r.created_at ? new Date(r.created_at).toLocaleString('id-ID') : '-';
        const userLine = user ? `${user.name ?? ''} (#${user.id})` : `#${r.user_id}`;
        const payoutLine = acct ? `${acct.type} • ${acct.provider} • ${acct.account_name} • ${acct.account_number}` : '-';
        const reasonHtml = r.reject_reason
          ? `<div class="rejectReason">Reject: ${escapeHtml(String(r.reject_reason))}</div>`
          : '';

        const actions = (() => {
          const s = String(r.status || '').toUpperCase();
          let html = '';

          if (s === 'PENDING') {
            html += `
              <button class="btnMini btnApprove" type="button" onclick="approve(${r.id})">Approve</button>
              <button class="btnMini btnReject" type="button" onclick="openReject(${r.id})">Reject</button>
              <button class="btnMini btnPaid" type="button" onclick="openPaid(${r.id})">Paid</button>
            `;
          } else if (s === 'APPROVED') {
            html += `
              <button class="btnMini btnReject" type="button" onclick="openReject(${r.id})">Reject</button>
              <button class="btnMini btnPaid" type="button" onclick="openPaid(${r.id})">Paid</button>
            `;
          } else if (s === 'PAID' && r.proof_url) {
            html += `
              <a class="btnMini btnProof" href="${r.proof_url}" target="_blank" rel="noopener">Bukti</a>
            `;
          }
          return html ? `<div style="display:flex; gap:10px; flex-wrap:wrap;">${html}</div>` : '';
        })();

        return `
          <div class="rowCard">
            <div class="bar ${statusBarClass(r.status)}"></div>
            <div class="rowBody">
              <div class="left">
                <div class="line1">
                  <div class="amount">Rp ${rupiah(r.amount)} </div>
                  ${badge(r.status)}
                </div>
                <div class="meta">
                  <div><b>User:</b> ${escapeHtml(userLine)}</div>
                  <div><b>Payout:</b> ${escapeHtml(payoutLine)}</div>
                </div>
                ${reasonHtml}
              </div>

              <div class="right">
                <div class="meta" style="margin-top:2px; text-align:right;">${escapeHtml(created)}</div>
                ${actions}
              </div>
            </div>
          </div>
        `;
      }).join('');
    }

    // actions
    window.approve = async function(id) {
      if (!confirm('Approve withdraw ini?')) return;
      try {
        await api(`/admin/withdrawals/${id}/approve`, { method: 'POST' });
        toast('Approved');
        await loadAdmin();
      } catch (e) {
        toast(e.message, 'err');
      }
    }

    // Reject modal
    window.openReject = function(id) {
      rejectId = id;
      document.getElementById('rejectReason').value = '';
      const m = document.getElementById('rejectModal');
      m.classList.add('show');
      m.setAttribute('aria-hidden', 'false');
    }
    window.closeReject = function() {
      rejectId = null;
      const m = document.getElementById('rejectModal');
      m.classList.remove('show');
      m.setAttribute('aria-hidden', 'true');
    }

    document.getElementById('btnRejectSubmit').addEventListener('click', async () => {
      const reason = document.getElementById('rejectReason').value.trim();
      if (!reason) return toast('Alasan reject wajib diisi', 'err');

      try {
        await api(`/admin/withdrawals/${rejectId}/reject`, {
          method: 'POST',
          body: JSON.stringify({ reason })
        });
        toast('Rejected');
        closeReject();
        await loadAdmin();
      } catch (e) {
        toast(e.message, 'err');
      }
    });

    // Paid modal
    window.openPaid = function(id) {
      paidId = id;
      document.getElementById('paidProof').value = '';
      const m = document.getElementById('paidModal');
      m.classList.add('show');
      m.setAttribute('aria-hidden', 'false');
    }
    window.closePaid = function() {
      paidId = null;
      const m = document.getElementById('paidModal');
      m.classList.remove('show');
      m.setAttribute('aria-hidden', 'true');
    }

    document.getElementById('btnPaidSubmit').addEventListener('click', async () => {
      try {
        const fd = new FormData();
        const f = document.getElementById('paidProof').files?.[0];
        if (f) fd.append('proof', f);

        await api(`/admin/withdrawals/${paidId}/paid`, { method: 'POST', body: fd });
        toast('Marked as paid');
        closePaid();
        await loadAdmin();
      } catch (e) {
        toast(e.message, 'err');
      }
    });

    // close modal when click outside
    document.getElementById('rejectModal').addEventListener('click', (e) => {
      if (e.target.id === 'rejectModal') closeReject();
    });
    document.getElementById('paidModal').addEventListener('click', (e) => {
      if (e.target.id === 'paidModal') closePaid();
    });

    document.getElementById('btnLoad').addEventListener('click', () => loadAdmin().catch(e => toast(e.message, 'err')));
    loadAdmin().catch(e => toast(e.message, 'err'));

    function escapeHtml(str) {
      return String(str)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
    }
  </script>
</body>
</html>
