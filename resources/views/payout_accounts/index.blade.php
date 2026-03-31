@extends('layouts.app')
@section('title', 'Akun Pencairan')

@section('content')

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

  /* single card center */
  .wrap{
    min-height: calc(100vh - 20px);
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

  .headerActions{ display:flex; gap:10px; align-items:center; flex:0 0 auto; }
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

  /* grid */
  .grid{
    display:grid;
    grid-template-columns: 1.15fr .85fr;
    gap:16px;
    align-items:start;
  }
  @media (max-width: 980px){
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

  /* blocks */
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

  /* errors */
  .alert{
    border-radius: var(--radius-sm);
    border: 1px solid rgba(239,68,68,.22);
    background: rgba(239,68,68,.06);
    color: #7f1d1d;
    padding:12px;
    margin-bottom: 12px;
    box-shadow: 0 10px 22px rgba(15,23,42,.06);
  }
  .alertTitle{ font-weight:1000; font-size:13px; margin:0 0 6px 0; color:#7f1d1d; }
  .alert ul{ margin:0; padding-left:18px; }
  .alert li{ font-size:12px; line-height:1.5; }

  /* inputs */
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
  .hint{ font-size:11px; color: var(--muted); font-weight:800; opacity:.9; }

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
    appearance:none;
  }
  .input::placeholder{ color: rgba(100,116,139,.85); }
  .input:focus{
    border-color: rgba(6,182,212,.40);
    box-shadow: 0 0 0 4px rgba(6,182,212,.14), 0 14px 30px rgba(15,23,42,.08);
    background: rgba(255,255,255,.86);
  }

  select.input{
    padding-right:44px;
    background-image:url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat:no-repeat;
    background-position:right 14px center;
    background-size:18px 18px;
  }
  select.input option{ background:#ffffff; color: var(--text); }

  /* checkbox */
  .checkRow{
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px 12px;
    border-radius: 14px;
    border: 1px solid rgba(15,23,42,.10);
    background: rgba(255,255,255,.72);
    box-shadow: 0 10px 22px rgba(15,23,42,.06);
    cursor:pointer;
    user-select:none;
  }
  .checkRow input{ width:16px; height:16px; accent-color:#06b6d4; }
  .checkText{ font-size:13px; color: var(--text); font-weight:800; }
  .checkSub{ font-size:12px; color: var(--muted); font-weight:700; }

  /* buttons */
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
    top:0; left:-120%;
    width:60%; height:100%;
    background: linear-gradient(to right, transparent, rgba(255,255,255,.32), transparent);
    transform: skewX(-18deg);
    animation: shimmer 3.2s infinite;
    pointer-events:none;
  }
  @keyframes shimmer{
    0%{ left:-120%; }
    18%{ left:220%; }
    100%{ left:220%; }
  }

  .btnSecondary{
    width:100%;
    border-radius: var(--radius-sm);
    padding: 14px 14px;
    font-size: 14px;
    font-weight:1000;
    cursor:pointer;
    border: 1px solid rgba(15,23,42,.12);
    background: rgba(255,255,255,.78);
    color: var(--text);
    box-shadow: 0 10px 22px rgba(15,23,42,.06);
    transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
  }
  .btnSecondary:hover{
    transform: translateY(-1px);
    background: rgba(255,255,255,.92);
    box-shadow: 0 16px 34px rgba(15,23,42,.10);
  }

  .btnTiny{
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
  .btnTiny:hover{
    transform: translateY(-1px);
    background: rgba(255,255,255,.92);
    box-shadow: 0 16px 34px rgba(15,23,42,.10);
  }

  .btnEdit{
    border-color: rgba(6,182,212,.22);
    background: rgba(6,182,212,.10);
    color:#075985;
  }
  .btnDel{
    border-color: rgba(239,68,68,.22);
    background: rgba(239,68,68,.06);
    color:#7f1d1d;
  }

  /* list cards */
  .list{ display:flex; flex-direction:column; gap:10px; }
  .accountCard{
    border-radius: 18px;
    border: 1px solid rgba(15,23,42,.10);
    background: rgba(255,255,255,.72);
    box-shadow: 0 10px 22px rgba(15,23,42,.06);
    padding: 14px;
    transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
  }
  .accountCard:hover{
    transform: translateY(-1px);
    background: rgba(255,255,255,.86);
    box-shadow: 0 16px 34px rgba(15,23,42,.10);
  }
  .acTop{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:12px;
    margin-bottom: 10px;
  }
  .acType{
    font-size:10px;
    font-weight:1000;
    color: var(--muted);
    text-transform:uppercase;
    letter-spacing:.12em;
  }
  .acProvider{
    margin-top:3px;
    font-size:15px;
    font-weight:1000;
    color: var(--text);
    letter-spacing:-0.01em;
    display:flex;
    align-items:center;
    gap:8px;
    flex-wrap:wrap;
  }
  .acDetails{
    font-size:13px;
    color: var(--muted);
    font-weight:900;
    letter-spacing:.02em;
  }
  .acName{
    margin-top:4px;
    font-size:12px;
    color: var(--muted);
    font-weight:800;
  }
  .badgeDefault{
    padding: 6px 10px;
    border-radius: 999px;
    font-size:11px;
    font-weight:1000;
    letter-spacing:.06em;
    text-transform:uppercase;
    border: 1px solid rgba(6,182,212,.22);
    background: rgba(6,182,212,.10);
    color:#075985;
    white-space:nowrap;
  }
  .acActions{
    display:flex;
    gap:8px;
    justify-content:flex-end;
    padding-top: 10px;
    border-top: 1px solid rgba(15,23,42,.08);
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

  /* subtle enter animation */
  .fadeUp{ opacity:0; transform: translateY(10px); animation: fadeUp .55s cubic-bezier(0.2,0.8,0.2,1) forwards; }
  .delay1{ animation-delay:.08s; }
  .delay2{ animation-delay:.16s; }
  @keyframes fadeUp{ to { opacity:1; transform: translateY(0); } }
</style>

<div class="wrap">
  <main class="card" role="main" aria-label="Akun Pencairan">
    <div class="card-inner">

      <header class="header">
        <div class="brand">
          <div class="logoBox" aria-hidden="true">
            <img src="/logo.png" alt="Logo" />
          </div>
          <div class="titleBlock">
            <h1 class="title">Akun Pencairan</h1>
            <p class="subtitle">Kelola akun bank / e-wallet untuk tujuan withdraw.</p>
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

      <section class="grid">

        {{-- LEFT: LIST --}}
        <section class="block fadeUp" aria-label="Daftar Akun">
          <p class="blockTitle">Daftar Akun Tersimpan</p>
          <div id="list" class="list">
            <div class="empty">Loading data...</div>
          </div>
        </section>

        {{-- RIGHT: FORM --}}
        <aside class="block fadeUp delay1" aria-label="Form Akun">
          <p id="formTitle" class="blockTitle" style="margin-bottom:10px;">Tambah Akun</p>

          {{-- Laravel error state (kalau suatu hari dipakai submit server-side) --}}
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

          <form id="form" novalidate>
            <input type="hidden" id="id" />

            <div class="formRow">
              <div class="formGroup">
                <div class="labelRow">
                  <span>Tipe Akun</span>
                  <span class="hint">Bank / E-Wallet</span>
                </div>
                <select id="type" class="input" aria-label="Tipe Akun">
                  <option value="EWALLET">E-Wallet (Dana/OVO/dll)</option>
                  <option value="BANK">Rekening Bank</option>
                </select>
              </div>

              <div class="formGroup">
                <div class="labelRow">
                  <span>Nama Provider / Bank</span>
                  <span class="hint">Contoh: BCA / DANA</span>
                </div>
                <input id="provider" class="input" placeholder="Contoh: BCA, DANA, GOPAY" required />
              </div>

              <div class="formGroup">
                <div class="labelRow">
                  <span>Nama Pemilik Akun</span>
                  <span class="hint">Sesuai aplikasi</span>
                </div>
                <input id="account_name" class="input" placeholder="Sesuai buku tabungan/aplikasi" required />
              </div>

              <div class="formGroup">
                <div class="labelRow">
                  <span>Nomor Rekening / HP</span>
                  <span class="hint">08xx / No. rekening</span>
                </div>
                <input id="account_number" type="number" class="input" placeholder="08xx atau nomor rekening" required />
              </div>

              <label class="checkRow" for="is_default">
                <input id="is_default" type="checkbox" />
                <div style="display:flex; flex-direction:column; gap:2px;">
                  <div class="checkText">Jadikan akun utama (Default)</div>
                  <div class="checkSub">Akan dipilih otomatis saat withdraw.</div>
                </div>
              </label>

              <div style="display:flex; gap:10px; margin-top:8px;">
                <button class="btnPrimary" type="submit" style="flex:1;">Simpan</button>
                <button id="btnReset" class="btnSecondary" type="button" style="width:140px;">Reset</button>
              </div>

              <button id="btnNew" class="btnTiny btnEdit" type="button" style="width:100%;">
                + Tambah Baru
              </button>
            </div>
          </form>
        </aside>

      </section>

    </div>
  </main>
</div>

@endsection

@push('scripts')
<script>
  const elList = document.getElementById('list');
  const form = document.getElementById('form');
  const $ = (id) => document.getElementById(id);

  function escapeHtml(str) {
    return String(str ?? '')
      .replaceAll('&','&amp;')
      .replaceAll('<','&lt;')
      .replaceAll('>','&gt;')
      .replaceAll('"','&quot;')
      .replaceAll("'","&#039;");
  }

  function resetForm() {
    $('id').value = '';
    $('type').value = 'EWALLET';
    $('provider').value = '';
    $('account_name').value = '';
    $('account_number').value = '';
    $('is_default').checked = false;
    $('formTitle').textContent = 'Tambah Akun';

    // smooth scroll ke form di mobile
    if (window.innerWidth < 980) {
      $('formTitle').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }

  async function load() {
    elList.innerHTML = `<div class="empty">Mengambil data...</div>`;

    const res = await api('/payout-accounts');
    const rows = res?.data || [];

    if (!rows.length) {
      elList.innerHTML = `<div class="empty">Belum ada akun tersimpan.</div>`;
      return;
    }

    elList.innerHTML = rows.map(r => {
      const icon = (r.type === 'BANK')
        ? `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="opacity:.7">
             <path d="M3 21h18M5 21v-7M19 21v-7M4 10a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v3H4v-3zM12 3L2 10h20L12 3z"/>
           </svg>`
        : `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="opacity:.7">
             <rect x="2" y="5" width="20" height="14" rx="2"></rect>
             <line x1="2" y1="10" x2="22" y2="10"></line>
           </svg>`;

      return `
        <div class="accountCard">
          <div class="acTop">
            <div style="min-width:0;">
              <div class="acType">${escapeHtml(r.type)}</div>
              <div class="acProvider">
                ${icon}
                <span>${escapeHtml(r.provider)}</span>
                ${r.is_default ? `<span class="badgeDefault">DEFAULT</span>` : ``}
              </div>
            </div>
          </div>

          <div class="acDetails">${escapeHtml(r.account_number)}</div>
          <div class="acName">${escapeHtml(r.account_name)}</div>

          <div class="acActions">
            <button class="btnTiny btnEdit" type="button" onclick='edit(${JSON.stringify(r)})'>Edit</button>
            <button class="btnTiny btnDel" type="button" onclick='del(${Number(r.id)})'>Hapus</button>
          </div>
        </div>
      `;
    }).join('');
  }

  window.edit = function (r) {
    $('id').value = r.id;
    $('type').value = r.type;
    $('provider').value = r.provider;
    $('account_name').value = r.account_name;
    $('account_number').value = r.account_number;
    $('is_default').checked = !!r.is_default;
    $('formTitle').textContent = 'Edit Akun';

    if (window.innerWidth < 980) {
      $('formTitle').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }

  window.del = async function (id) {
    if (!confirm('Hapus akun ini?')) return;
    try {
      await api(`/payout-accounts/${id}`, { method: 'DELETE' });
      toast('Akun berhasil dihapus');
      await load();
      resetForm();
    } catch (e) {
      toast(e.message, 'err');
    }
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const id = $('id').value.trim();

    const submitBtn = form.querySelector('button[type="submit"]');
    const oldText = submitBtn.innerHTML;
    submitBtn.innerHTML = 'Menyimpan...';
    submitBtn.disabled = true;

    const payload = {
      type: $('type').value,
      provider: $('provider').value.trim(),
      account_name: $('account_name').value.trim(),
      account_number: $('account_number').value.trim(),
      is_default: $('is_default').checked,
    };

    try {
      if (!id) {
        await api('/payout-accounts', { method: 'POST', body: JSON.stringify(payload) });
        toast('Akun baru ditambahkan');
      } else {
        await api(`/payout-accounts/${id}`, { method: 'PUT', body: JSON.stringify(payload) });
        toast('Data akun diperbarui');
      }
      await load();
      resetForm();
    } catch (e) {
      toast(e.message, 'err');
    } finally {
      submitBtn.innerHTML = oldText;
      submitBtn.disabled = false;
    }
  });

  document.getElementById('btnReset').addEventListener('click', resetForm);
  document.getElementById('btnNew').addEventListener('click', resetForm);

  load().catch(e => toast(e.message, 'err'));
</script>
@endpush
