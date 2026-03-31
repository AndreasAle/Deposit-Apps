@extends('layouts.app')
@section('title', 'Withdraw')

@section('content')

<style>
  /* =========================
     CROWDNIK PREMIUM WHITE TOKENS (WAJIB)
  ========================== */
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

  /* =========================
     SINGLE-CARD CENTER LAYOUT
  ========================== */
  .wrap{
    min-height: calc(100vh - 20px);
    display:flex;
    align-items:center;
    justify-content:center;
    padding:24px 16px;
  }

  .card{
    width:100%;
    max-width: 760px;
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
  .card-inner{
    position:relative;
    padding:22px;
  }

  /* =========================
     HEADER
  ========================== */
  .header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:16px;
    padding:6px 2px 14px;
    border-bottom: 1px solid rgba(15,23,42,.08);
    margin-bottom: 16px;
  }
  .brand{
    display:flex;
    align-items:center;
    gap:14px;
    min-width:0;
  }
  .logoBox{
    width:68px;
    height:68px;
    border-radius: 18px;
    background: rgba(255,255,255,.86);
    border: 1px solid rgba(15,23,42,.10);
    box-shadow: var(--shadow-soft);
    display:flex;
    align-items:center;
    justify-content:center;
    flex: 0 0 auto;
  }
  .logoBox img{
    width:46px;
    height:46px;
    object-fit:contain;
    display:block;
  }
  .titleBlock{ min-width:0; display:flex; flex-direction:column; gap:2px; }
  .title{
    margin:0;
    font-size:18px;
    font-weight:1000;
    letter-spacing:-0.02em;
    line-height:1.2;
    color: var(--text);
  }
  .subtitle{
    margin:0;
    font-size:13px;
    color: var(--muted);
    line-height:1.35;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
  }

  .headerActions{
    display:flex;
    gap:10px;
    align-items:center;
    flex:0 0 auto;
  }
  .btnGhost{
    display:inline-flex;
    align-items:center;
    gap:10px;
    padding:10px 12px;
    border-radius: 999px;
    border: 1px solid rgba(15,23,42,.10);
    background: rgba(255,255,255,.78);
    box-shadow: 0 10px 22px rgba(15,23,42,.06);
    color: var(--text);
    text-decoration:none;
    font-weight:900;
    font-size:12px;
    transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
    cursor:pointer;
    user-select:none;
    -webkit-tap-highlight-color: transparent;
    white-space:nowrap;
  }
  .btnGhost:hover{
    transform: translateY(-1px);
    background: rgba(255,255,255,.92);
    box-shadow: 0 16px 30px rgba(15,23,42,.10);
  }
  .btnGhost svg{ width:18px; height:18px; }

  /* =========================
     GRID (FORM + HISTORY)
  ========================== */
  .grid{
    display:grid;
    grid-template-columns: 1.05fr .95fr;
    gap:16px;
    align-items:start;
  }

  @media (max-width: 860px){
    .grid{ grid-template-columns: 1fr; }
    .header{ flex-direction:column; align-items:stretch; }
    .headerActions{ width:100%; justify-content:flex-end; }
    .subtitle{ white-space:normal; }
  }
  @media (max-width: 420px){
    .card-inner{ padding:18px; }
    .btnGhost{ width:100%; justify-content:center; }
    .headerActions{ width:100%; }
  }

  /* =========================
     BLOCKS
  ========================== */
  .block{
    border-radius: var(--radius);
    border: 1px solid rgba(15,23,42,.10);
    background: rgba(255,255,255,.72);
    box-shadow: var(--shadow-soft);
    padding:16px;
  }
  .blockTitle{
    margin:0 0 10px 0;
    font-size:12px;
    letter-spacing:.12em;
    text-transform:uppercase;
    color: var(--muted);
    font-weight:1000;
  }

  /* =========================
     ALERT / ERRORS
  ========================== */
  .alert{
    border-radius: var(--radius-sm);
    border: 1px solid rgba(239,68,68,.22);
    background: rgba(239,68,68,.06);
    color: #7f1d1d;
    padding:12px;
    margin-bottom: 12px;
    box-shadow: 0 10px 22px rgba(15,23,42,.06);
  }
  .alertTitle{
    font-weight:1000;
    font-size:13px;
    margin:0 0 6px 0;
    color:#7f1d1d;
  }
  .alert ul{ margin:0; padding-left: 18px; }
  .alert li{ font-size:12px; color:#7f1d1d; line-height:1.5; }

  /* =========================
     FORM INPUTS
  ========================== */
  .formRow{ display:grid; gap:12px; }
  .formGroup{ display:grid; gap:8px; }

  .labelRow{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    font-size:12px;
    color: var(--muted);
    font-weight:900;
    letter-spacing:.02em;
  }
  .hint{
    font-size:11px;
    color: var(--muted);
    font-weight:800;
    opacity:.9;
  }

  .field{ position:relative; }
  .prefix{
    position:absolute;
    left:14px;
    top:50%;
    transform: translateY(-50%);
    font-weight:1000;
    color: var(--muted);
    font-size:13px;
    pointer-events:none;
  }

  .input{
    width:100%;
    border-radius: var(--radius-sm);
    border: 1px solid rgba(15,23,42,.12);
    background: rgba(255,255,255,.70);
    padding: 14px 14px 14px 44px;
    font-size: 14px;
    color: var(--text);
    outline:none;
    transition: box-shadow .15s ease, border-color .15s ease, background .15s ease, transform .15s ease;
    box-shadow: 0 10px 22px rgba(15,23,42,.06);
    appearance: none;
  }
  .input::placeholder{ color: rgba(100,116,139,.85); }
  .input:focus{
    border-color: rgba(6,182,212,.40);
    box-shadow: 0 0 0 4px rgba(6,182,212,.14), 0 14px 30px rgba(15,23,42,.08);
    background: rgba(255,255,255,.86);
  }
  .input.is-invalid{
    border-color: rgba(239,68,68,.38);
    box-shadow: 0 0 0 4px rgba(239,68,68,.10), 0 14px 30px rgba(15,23,42,.08);
  }
  .errorText{
    margin-top:6px;
    font-size:12px;
    color:#b91c1c;
    font-weight:800;
  }

  /* select arrow */
  select.input{
    padding-right: 44px;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat:no-repeat;
    background-position: right 14px center;
    background-size: 18px 18px;
  }
  select.input option{ background:#ffffff; color: var(--text); }

  /* helper link */
  .helperLink{
    color: #0891b2;
    text-decoration:none;
    font-weight:900;
  }
  .helperLink:hover{ text-decoration: underline; }

  /* =========================
     PRIMARY BUTTON
  ========================== */
  .btnPrimary{
    width:100%;
    border:0;
    border-radius: var(--radius-sm);
    padding: 14px 14px;
    font-size: 14px;
    font-weight:1000;
    color: #081022;
    cursor:pointer;
    background: linear-gradient(135deg, var(--primary1) 0%, var(--primary2) 100%);
    box-shadow: 0 18px 42px rgba(109,40,217,.20), 0 14px 34px rgba(6,182,212,.12);
    transition: transform .15s ease, box-shadow .15s ease, filter .15s ease, opacity .15s ease;
    position:relative;
    overflow:hidden;
    -webkit-tap-highlight-color: transparent;
  }
  .btnPrimary:hover{
    transform: translateY(-1px);
    filter: saturate(1.06);
    box-shadow: 0 24px 60px rgba(109,40,217,.22), 0 18px 44px rgba(6,182,212,.14);
  }
  .btnPrimary:active{ transform: translateY(0px) scale(.99); }
  .btnPrimary[disabled]{ opacity:.7; cursor:not-allowed; }

  .btnPrimary::after{
    content:"";
    position:absolute;
    top:0;
    left:-120%;
    width: 60%;
    height:100%;
    background: linear-gradient(to right, transparent, rgba(255,255,255,.32), transparent);
    transform: skewX(-18deg);
    animation: shimmer 3.2s infinite;
    pointer-events:none;
  }
  @keyframes shimmer{
    0%{ left:-120%; }
    18%{ left: 220%; }
    100%{ left: 220%; }
  }

  /* =========================
     HISTORY
  ========================== */
  .historyHeader{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:10px;
    margin-bottom: 12px;
  }

  .btnMini{
    border-radius: 999px;
    border: 1px solid rgba(15,23,42,.10);
    background: rgba(255,255,255,.78);
    color: var(--text);
    font-weight:1000;
    font-size:12px;
    padding: 8px 10px;
    cursor:pointer;
    transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
    box-shadow: 0 10px 22px rgba(15,23,42,.06);
    white-space:nowrap;
  }
  .btnMini:hover{
    transform: translateY(-1px);
    background: rgba(255,255,255,.92);
    box-shadow: 0 16px 34px rgba(15,23,42,.10);
  }
  .btnMini:active{ transform: translateY(0px) scale(.99); }

  .historyList{ display:flex; flex-direction:column; gap:10px; min-height:100px; }
  .historyItem{
    border-radius: 18px;
    border: 1px solid rgba(15,23,42,.10);
    background: rgba(255,255,255,.72);
    box-shadow: 0 10px 22px rgba(15,23,42,.06);
    padding: 14px;
    display:flex;
    flex-direction:column;
    gap:10px;
    transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
  }
  .historyItem:hover{
    transform: translateY(-1px);
    background: rgba(255,255,255,.86);
    box-shadow: 0 16px 34px rgba(15,23,42,.10);
  }

  .hTop{
    display:flex;
    justify-content:space-between;
    gap:12px;
    align-items:flex-start;
  }
  .hAmount{
    font-size:15px;
    font-weight:1000;
    letter-spacing:-0.01em;
    color: var(--text);
  }
  .hTime{
    margin-top:4px;
    font-size:12px;
    color: var(--muted);
    line-height:1.4;
  }
  .hMeta{
    display:flex;
    gap:8px;
    align-items:center;
    color: var(--muted);
    font-size:12px;
  }
  .hMeta svg{ width:14px; height:14px; opacity:.9; }

  .divider{
    height:1px;
    background: rgba(15,23,42,.08);
  }

  .badge{
    padding: 7px 10px;
    border-radius: 999px; /* chip/badge */
    font-size:11px;
    font-weight:1000;
    letter-spacing:.06em;
    text-transform:uppercase;
    border: 1px solid rgba(15,23,42,.10);
    background: rgba(255,255,255,.82);
    color: var(--muted);
    white-space:nowrap;
  }
  .badgePending{
    border-color: rgba(245,158,11,.22);
    background: rgba(245,158,11,.10);
    color: #92400e;
  }
  .badgePaid{
    border-color: rgba(6,182,212,.22);
    background: rgba(6,182,212,.10);
    color: #075985;
  }
  .badgeRejected{
    border-color: rgba(239,68,68,.22);
    background: rgba(239,68,68,.08);
    color: #7f1d1d;
  }

  .rejectBox{
    border-radius: 14px;
    border: 1px solid rgba(239,68,68,.18);
    background: rgba(239,68,68,.06);
    padding:10px;
    color:#7f1d1d;
    font-size:12px;
    line-height:1.45;
    font-weight:700;
  }

  .empty{
    border-radius: var(--radius);
    border: 1px dashed rgba(15,23,42,.18);
    background: rgba(255,255,255,.66);
    color: var(--muted);
    padding: 14px;
    font-size:13px;
    text-align:center;
    box-shadow: 0 10px 22px rgba(15,23,42,.06);
  }

  /* small motion (subtle) */
  .fadeUp{
    opacity:0;
    transform: translateY(10px);
    animation: fadeUp .55s cubic-bezier(0.2,0.8,0.2,1) forwards;
  }
  .delay1{ animation-delay: .08s; }
  .delay2{ animation-delay: .16s; }
  @keyframes fadeUp{ to { opacity:1; transform: translateY(0); } }
</style>

<div class="wrap">
  <main class="card" role="main" aria-label="Withdraw">
    <div class="card-inner">

      <header class="header">
        <div class="brand">
          <div class="logoBox" aria-hidden="true">
            <img src="/logo.png" alt="Logo" />
          </div>
          <div class="titleBlock">
            <h1 class="title">Request Withdraw</h1>
            <p class="subtitle">Ajukan penarikan saldo ke akun pencairan Anda, cepat dan rapi.</p>
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

      <section class="grid" aria-label="Konten Withdraw">

        {{-- LEFT: FORM --}}
        <div class="block fadeUp">
          <p class="blockTitle">Form Withdraw</p>

          {{-- Laravel error state (jika kamu submit via form server-side di masa depan) --}}
          @if ($errors->any())
            <div class="alert" role="alert" aria-live="polite">
              <p class="alertTitle">Periksa input Anda</p>
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          {{-- Saat ini form kamu submit via JS (AJAX) --}}
          <form id="wdForm" novalidate>
            <div class="formRow">

              <div class="formGroup">
                <div class="labelRow">
                  <span>Nominal Penarikan</span>
                  <span class="hint">Minimal Rp 50.000</span>
                </div>

                <div class="field">
                  <span class="prefix">Rp</span>
                  <input
                    id="amount"
                    type="number"
                    min="50000"
                    class="input"
                    placeholder="0"
                    inputmode="numeric"
                    aria-label="Nominal Withdraw"
                  />
                </div>

                <div class="hint" style="font-weight:800; opacity:.85;">
                  Pastikan nominal sesuai saldo tersedia.
                </div>
              </div>

              <div class="formGroup">
                <div class="labelRow">
                  <span>Tujuan Pencairan</span>
                  <span class="hint">Pilih akun</span>
                </div>

                <select id="payout" class="input" style="padding-left:14px;" aria-label="Akun Pencairan">
                  <option value="">Loading...</option>
                </select>

                <div class="hint" style="font-weight:800; opacity:.85;">
                  Belum punya akun? <a class="helperLink" href="/ui/payout-accounts">Tambah Akun</a>
                </div>
              </div>

              <button class="btnPrimary" type="submit" id="btnSubmitWd">
                Kirim Permintaan
              </button>

            </div>
          </form>
        </div>

        {{-- RIGHT: HISTORY --}}
        <aside class="block fadeUp delay1" aria-label="Riwayat Withdraw">
          <div class="historyHeader">
            <div>
              <p class="blockTitle" style="margin:0;">Riwayat Penarikan</p>
            </div>
            <button id="btnReload" class="btnMini" type="button" aria-label="Reload Riwayat">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                   stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;">
                <path d="M23 4v6h-6"></path>
                <path d="M1 20v-6h6"></path>
                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10"></path>
                <path d="M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
              </svg>
              <span style="margin-left:6px;">Reload</span>
            </button>
          </div>

          <div id="history" class="historyList">
            <div class="empty">Loading...</div>
          </div>
        </aside>

      </section>

    </div>
  </main>
</div>

@endsection

@push('scripts')
<script>
  const wdForm = document.getElementById('wdForm');
  const payoutSel = document.getElementById('payout');
  const historyEl = document.getElementById('history');
  const btnReload = document.getElementById('btnReload');
  const btnSubmit = document.getElementById('btnSubmitWd');

  function rupiah(n) {
    try { return new Intl.NumberFormat('id-ID').format(n); } catch { return String(n); }
  }

  function escapeHtml(str) {
    return String(str ?? '')
      .replaceAll('&','&amp;')
      .replaceAll('<','&lt;')
      .replaceAll('>','&gt;')
      .replaceAll('"','&quot;')
      .replaceAll("'","&#039;");
  }

  function badge(status) {
    const s = String(status || '').toUpperCase();
    if (s === 'PENDING') return `<span class="badge badgePending">PENDING</span>`;
    if (s === 'PAID') return `<span class="badge badgePaid">PAID</span>`;
    if (s === 'REJECTED') return `<span class="badge badgeRejected">REJECTED</span>`;
    return `<span class="badge">${escapeHtml(s || 'UNKNOWN')}</span>`;
  }

  async function loadPayoutAccounts() {
    const res = await api('/payout-accounts');
    const rows = res?.data || [];

    if (!rows.length) {
      payoutSel.innerHTML = `<option value="">-- Belum ada akun pencairan --</option>`;
      return;
    }

    const sorted = [...rows].sort((a,b) => (b.is_default ? 1 : 0) - (a.is_default ? 1 : 0));
    payoutSel.innerHTML = sorted.map(r => {
      const label = `${r.type} • ${r.provider} • ${r.account_number} (${r.account_name})`;
      return `<option value="${Number(r.id)}">${escapeHtml(label)}</option>`;
    }).join('');
  }

  async function loadWithdrawals() {
    historyEl.innerHTML = `<div class="empty">Mengambil data...</div>`;

    const res = await api('/withdrawals');
    const rows = res?.data || [];

    if (!rows.length) {
      historyEl.innerHTML = `<div class="empty">Belum ada riwayat penarikan.</div>`;
      return;
    }

    historyEl.innerHTML = rows.map(r => {
      const created = r.created_at ? new Date(r.created_at).toLocaleString('id-ID') : '-';
      const payoutInfo = r.payout_account
        ? `${r.payout_account.provider} - ${r.payout_account.account_number}`
        : 'Akun dihapus';

      const rejectReason = (String(r.status || '').toUpperCase() === 'REJECTED' && r.reject_reason)
        ? `<div class="rejectBox"><strong>Ditolak:</strong> ${escapeHtml(r.reject_reason)}</div>`
        : '';

      const proof = (String(r.status || '').toUpperCase() === 'PAID' && r.proof_url)
        ? `<div style="text-align:right; font-size:12px;">
             <a class="helperLink" href="${escapeHtml(r.proof_url)}" target="_blank" rel="noopener">Lihat Bukti Transfer →</a>
           </div>`
        : '';

      const cancel = (String(r.status || '').toUpperCase() === 'PENDING')
        ? `<div style="text-align:right;">
             <button class="btnMini" type="button" onclick="cancelWd(${Number(r.id)})"
               style="border-color: rgba(239,68,68,.22); background: rgba(239,68,68,.06); color:#7f1d1d;">
               Batalkan
             </button>
           </div>`
        : '';

      return `
        <div class="historyItem">
          <div class="hTop">
            <div>
              <div class="hAmount">Rp ${rupiah(r.amount)}</div>
              <div class="hTime">${escapeHtml(created)}</div>
            </div>
            ${badge(r.status)}
          </div>

          <div class="divider"></div>

          <div class="hMeta">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <rect x="2" y="5" width="20" height="14" rx="2"></rect>
              <line x1="2" y1="10" x2="22" y2="10"></line>
            </svg>
            <span>${escapeHtml(payoutInfo)}</span>
          </div>

          ${cancel}
          ${rejectReason}
          ${proof}
        </div>
      `;
    }).join('');
  }

  window.cancelWd = async function(id) {
    if (!confirm('Batalkan request withdraw ini?')) return;
    try {
      await api(`/withdrawals/${id}/cancel`, { method: 'POST' });
      toast('Withdraw dibatalkan');
      await loadWithdrawals();
    } catch (e) {
      toast(e.message, 'err');
    }
  }

  wdForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const amount = Number(document.getElementById('amount').value);
    const payout = payoutSel.value;

    if (!payout) return toast('Pilih akun pencairan terlebih dahulu', 'err');
    if (!amount || amount < 50000) return toast('Minimal withdraw Rp 50.000', 'err');

    const oldText = btnSubmit.innerHTML;
    btnSubmit.innerHTML = 'Memproses...';
    btnSubmit.disabled = true;

    try {
      await api('/withdrawals', {
        method: 'POST',
        body: JSON.stringify({ amount, user_payout_account_id: Number(payout) }),
      });
      toast('Request withdraw berhasil dibuat');
      document.getElementById('amount').value = '';
      await loadWithdrawals();
    } catch (e) {
      toast(e.message, 'err');
    } finally {
      btnSubmit.innerHTML = oldText;
      btnSubmit.disabled = false;
    }
  });

  btnReload.addEventListener('click', async () => {
    try { await loadWithdrawals(); } catch (e) { toast(e.message, 'err'); }
  });

  // initial load
  Promise.all([loadPayoutAccounts(), loadWithdrawals()]).catch(e => console.error(e));
</script>
@endpush
