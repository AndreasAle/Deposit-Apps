@php
  $user = auth()->user();
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard | TumbuhMaju</title>
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
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
      background:
        radial-gradient(1100px 600px at 12% 8%, rgba(59,130,246,.18), transparent 60%),
        radial-gradient(900px 520px at 90% 18%, rgba(14,165,233,.14), transparent 55%),
        radial-gradient(900px 520px at 50% 105%, rgba(124,58,237,.10), transparent 60%),
        linear-gradient(180deg, #ffffff 0%, #f5f7ff 55%, #eef2ff 100%);
      min-height:100vh;
      overflow-x:hidden;
      -webkit-tap-highlight-color: transparent;
    }

    /* Single-card center */
    .page{
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      padding: 22px 14px;
    }
    .shell{
      width:100%;
      max-width: 900px;
    }

    .card{
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      overflow:hidden;
      position:relative;
    }
    .card::before{
      content:"";
      position:absolute;
      inset:-1px;
      background: linear-gradient(135deg, rgba(109,40,217,.10), rgba(6,182,212,.10));
      opacity:.65;
      pointer-events:none;
      mask: linear-gradient(#000, #000) content-box, linear-gradient(#000, #000);
      -webkit-mask: linear-gradient(#000, #000) content-box, linear-gradient(#000, #000);
      padding:1px;
      border-radius: var(--radius);
    }

    .inner{
      position:relative;
      padding: 16px;
    }
    @media (min-width: 768px){
      .inner{ padding: 22px; }
    }

    /* Header (logo besar) */
    .header{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap: 12px;
      padding: 4px 2px 14px;
    }
    .brand{
      display:flex;
      align-items:center;
      gap: 12px;
      min-width:0;
    }
    .logoBox{
      width:68px; height:68px;
      border-radius: 20px;
      background: rgba(255,255,255,.88);
      border: 1px solid rgba(6,182,212,.18);
      box-shadow: var(--shadow-soft);
      display:flex;
      align-items:center;
      justify-content:center;
      flex: 0 0 auto;
    }
    .logoBox img{
      width:46px; height:46px;
      object-fit:contain;
      display:block;
      filter: drop-shadow(0 10px 18px rgba(15,23,42,.12));
    }
    .hello{ min-width:0; }
    .hello h1{
      margin:0;
      font-size: 16px;
      font-weight: 950;
      letter-spacing: -0.02em;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width: 58vw;
    }
    .hello p{
      margin: 6px 0 0;
      font-size: 12.5px;
      color: var(--muted);
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width: 58vw;
    }
    @media (min-width: 768px){
      .hello h1{ font-size: 18px; max-width: 560px; }
      .hello p{ font-size: 13px; max-width: 560px; }
    }

    .vip{
      display:inline-flex;
      align-items:center;
      gap: 8px;
      padding: 8px 12px;
      border-radius: 999px;
      background: rgba(255,255,255,.86);
      border: 1px solid var(--border);
      box-shadow: var(--shadow-soft);
      flex: 0 0 auto;
    }
    .vipDot{
      width:10px; height:10px; border-radius:999px;
      background: linear-gradient(135deg, var(--primary1), var(--primary2));
      box-shadow: 0 10px 18px rgba(6,182,212,.16);
    }
    .vipText{
      font-size: 11.5px;
      font-weight: 950;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: var(--text);
    }

    /* Error state */
    .errors{
      margin: 0 0 14px;
      background: rgba(255,255,255,.92);
      border: 1px solid rgba(239,68,68,.20);
      border-radius: var(--radius-sm);
      box-shadow: var(--shadow-soft);
      padding: 12px 12px;
    }
    .errorsTitle{
      display:flex;
      align-items:center;
      gap:10px;
      font-weight: 950;
      font-size: 13px;
      color:#991b1b;
      margin: 0 0 6px;
    }
    .errorsTitle .ico{
      width:28px; height:28px;
      border-radius: 999px;
      display:flex;
      align-items:center;
      justify-content:center;
      background: rgba(239,68,68,.10);
      border: 1px solid rgba(239,68,68,.20);
      flex: 0 0 auto;
    }
    .errors ul{
      margin: 0;
      padding-left: 18px;
      color:#7f1d1d;
      font-size: 12.5px;
      line-height: 1.45;
    }

    /* Hero balance (stacked) */
    .hero{
      background: var(--card-strong);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow-soft);
      padding: 16px;
      position:relative;
      overflow:hidden;
    }
    .hero::before{
      content:"";
      position:absolute;
      inset:-2px;
      background:
        radial-gradient(900px 380px at 10% 0%, rgba(6,182,212,.14), transparent 60%),
        radial-gradient(900px 380px at 90% 0%, rgba(109,40,217,.12), transparent 60%);
      opacity:.9;
      pointer-events:none;
    }
    .heroInner{ position:relative; }

    .heroTop{
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap: 12px;
    }
    .kicker{
      font-size: 12.5px;
      font-weight: 800;
      color: var(--muted);
      letter-spacing: .02em;
      margin: 0;
    }
    .saldo{
      margin: 10px 0 0;
      font-size: 32px;
      font-weight: 950;
      letter-spacing: -0.03em;
      line-height: 1.05;
      color: var(--text);
    }
    @media (min-width: 768px){
      .saldo{ font-size: 38px; }
    }

    .chips{
      display:flex;
      flex-wrap:wrap;
      gap: 8px;
      margin-top: 12px;
    }
    .chip{
      display:inline-flex;
      align-items:center;
      gap: 8px;
      padding: 8px 12px;
      border-radius: 999px;
      background: rgba(255,255,255,.88);
      border: 1px solid rgba(15,23,42,.08);
      color: var(--text);
      font-size: 12px;
      font-weight: 900;
      box-shadow: 0 12px 20px rgba(15,23,42,.06);
      white-space:nowrap;
    }
    .chipDot{
      width:8px; height:8px; border-radius:999px;
      background: rgba(16,185,129,.85);
      box-shadow: 0 10px 16px rgba(16,185,129,.16);
    }
    .chipMuted{
      color: var(--muted);
      font-weight: 900;
    }
    .chipIcon{
      width:14px; height:14px; display:block;
    }

    /* Quick actions */
    .quick{
      margin-top: 12px;
      display:grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 10px;
    }
    @media (max-width: 380px){
      .quick{ gap: 8px; }
    }
    .qa{
      text-decoration:none;
      color: var(--text);
      background: rgba(255,255,255,.82);
      border: 1px solid rgba(15,23,42,.10);
      border-radius: 18px;
      box-shadow: 0 12px 20px rgba(15,23,42,.06);
      padding: 12px 10px;
      display:flex;
      flex-direction:column;
      align-items:center;
      gap: 8px;
      transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
      user-select:none;
    }
    .qa:hover{
      transform: translateY(-1px);
      border-color: rgba(6,182,212,.28);
      box-shadow: 0 16px 28px rgba(15,23,42,.10);
    }
    .qa:focus{
      outline:none;
      box-shadow: 0 0 0 4px rgba(6,182,212,.14), 0 16px 28px rgba(15,23,42,.10);
      border-color: rgba(6,182,212,.40);
    }
    .qaIco{
      width:46px; height:46px;
      border-radius: 16px;
      display:flex;
      align-items:center;
      justify-content:center;
      background: linear-gradient(135deg, rgba(109,40,217,.14), rgba(6,182,212,.14));
      border: 1px solid rgba(15,23,42,.08);
    }
    .qaIco svg{ width:22px; height:22px; stroke: var(--text); opacity:.92; }
    .qaLbl{
      font-size: 11px;
      font-weight: 950;
      letter-spacing: .02em;
      color: var(--text);
    }

    /* Section Products */
    .section{
      margin-top: 14px;
      background: var(--card-strong);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow-soft);
      padding: 14px;
      position:relative;
      overflow:hidden;
    }
    .section::before{
      content:"";
      position:absolute;
      inset:-2px;
      background:
        radial-gradient(900px 360px at 10% 0%, rgba(59,130,246,.10), transparent 60%),
        radial-gradient(900px 360px at 90% 0%, rgba(124,58,237,.10), transparent 60%);
      opacity:.9;
      pointer-events:none;
    }
    .sectionInner{ position:relative; }

    .sectionTitle{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap: 10px;
      margin-bottom: 10px;
    }
    .sectionTitle h2{
      margin:0;
      font-size: 14px;
      font-weight: 950;
      letter-spacing: -0.01em;
      color: var(--text);
    }
    @media (min-width: 768px){
      .sectionTitle h2{ font-size: 15px; }
    }
    .hint{
      font-size: 12px;
      color: var(--muted);
      font-weight: 800;
      white-space:nowrap;
    }

    /* Tabs */
    .tabsWrap{
      overflow:auto;
      -webkit-overflow-scrolling: touch;
      padding-bottom: 8px;
      margin-bottom: 6px;
    }
    .tabs{
      display:flex;
      gap: 10px;
      min-width:max-content;
    }
    .tab{
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.70);
      color: var(--muted);
      padding: 10px 14px;
      border-radius: 999px;
      font-size: 12.5px;
      font-weight: 950;
      cursor:pointer;
      transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease, background .18s ease, color .18s ease;
      user-select:none;
      white-space:nowrap;
    }
    .tab:hover{ transform: translateY(-1px); }
    .tab:focus{
      outline:none;
      box-shadow: 0 0 0 4px rgba(6,182,212,.14);
      border-color: rgba(6,182,212,.40);
    }
    .tab.active{
      color:#081022;
      border-color: rgba(6,182,212,.40);
      background: linear-gradient(135deg, rgba(109,40,217,.18), rgba(6,182,212,.18));
      box-shadow: 0 16px 30px rgba(15,23,42,.10);
    }

    /* Product cards */
    .list{
      display:grid;
      gap: 12px;
      margin-top: 8px;
    }
    .prod{
      background: rgba(255,255,255,.84);
      border: 1px solid rgba(15,23,42,.10);
      border-radius: var(--radius);
      box-shadow: 0 14px 28px rgba(15,23,42,.08);
      overflow:hidden;
      transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    }
    .prod:hover{
      transform: translateY(-1px);
      border-color: rgba(6,182,212,.26);
      box-shadow: 0 18px 34px rgba(15,23,42,.12);
    }
    .prodHead{
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap: 10px;
      padding: 14px 14px 10px;
    }
    .prodName{
      margin:0;
      font-size: 14.5px;
      font-weight: 950;
      color: var(--text);
      line-height: 1.25;
    }
    .pill{
      border-radius: 999px;
      padding: 7px 10px;
      font-size: 11px;
      font-weight: 950;
      letter-spacing: .04em;
      color:#081022;
      background: linear-gradient(135deg, rgba(109,40,217,.14), rgba(6,182,212,.14));
      border: 1px solid rgba(6,182,212,.22);
      white-space:nowrap;
      flex: 0 0 auto;
    }

    .prodBody{
      padding: 0 14px 12px;
    }
    .stats{
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      padding: 10px;
      border-radius: 16px;
      background: rgba(255,255,255,.74);
      border: 1px solid rgba(15,23,42,.08);
    }
    .sLabel{
      font-size: 11px;
      color: var(--muted);
      font-weight: 800;
      margin-bottom: 4px;
    }
    .sVal{
      font-size: 13px;
      font-weight: 950;
      color: var(--text);
      letter-spacing: -0.01em;
    }
    .sValStrong{
      background: linear-gradient(135deg, var(--primary1), var(--primary2));
      -webkit-background-clip:text;
      background-clip:text;
      -webkit-text-fill-color: transparent;
    }

    .prodAction{
      padding: 12px 14px 14px;
      border-top: 1px solid rgba(15,23,42,.08);
      background: rgba(255,255,255,.62);
    }

    /* Buttons */
    .btn{
      width:100%;
      border: 0;
      border-radius: 16px;
      padding: 12px 14px;
      font-weight: 950;
      cursor:pointer;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap: 10px;
      transition: transform .18s ease, box-shadow .18s ease, filter .18s ease, border-color .18s ease;
      user-select:none;
      text-decoration:none;
    }
    .btn svg{ width:18px; height:18px; stroke: currentColor; }
    .btnPrimary{
      background: linear-gradient(135deg, var(--primary1), var(--primary2));
      color: #081022;
      box-shadow: 0 18px 34px rgba(6,182,212,.18);
    }
    .btnPrimary:hover{ transform: translateY(-1px); filter: brightness(1.02); }
    .btnPrimary:focus{
      outline:none;
      box-shadow: 0 0 0 4px rgba(6,182,212,.14), 0 18px 34px rgba(6,182,212,.18);
    }
    .btnGhost{
      background: rgba(255,255,255,.75);
      border: 1px solid rgba(15,23,42,.12);
      color: var(--muted);
      cursor:pointer;
    }
    .btnWarn{
      background: rgba(255,255,255,.78);
      border: 1px solid rgba(239,68,68,.22);
      color: #b91c1c;
      cursor:pointer;
    }

    .empty{
      background: rgba(255,255,255,.82);
      border: 1px dashed rgba(15,23,42,.16);
      border-radius: 16px;
      padding: 18px 14px;
      color: var(--muted);
      font-size: 13px;
      font-weight: 800;
      text-align:center;
      margin-top: 8px;
    }

    /* Bottom nav */
    .nav{
      margin-top: 14px;
      background: rgba(255,255,255,.80);
      border: 1px solid rgba(15,23,42,.10);
      border-radius: 18px;
      box-shadow: 0 16px 28px rgba(15,23,42,.08);
      padding: 10px;
      display:grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 8px;
    }
    .nav a, .nav button{
      appearance:none;
      border: 1px solid transparent;
      background: transparent;
      border-radius: 16px;
      padding: 10px 8px;
      font: inherit;
      color: var(--muted);
      text-decoration:none;
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      gap: 6px;
      cursor:pointer;
      transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease, background .18s ease, color .18s ease;
    }
    .nav svg{ width:20px; height:20px; stroke: currentColor; }
    .nav a:hover, .nav button:hover{ transform: translateY(-1px); }
    .nav a:focus, .nav button:focus{
      outline:none;
      box-shadow: 0 0 0 4px rgba(6,182,212,.14);
      border-color: rgba(6,182,212,.40);
    }
    .nav .active{
      color:#081022;
      background: linear-gradient(135deg, rgba(109,40,217,.14), rgba(6,182,212,.14));
      border-color: rgba(6,182,212,.22);
    }
    .nav span{
      font-size: 11px;
      font-weight: 950;
      letter-spacing: .02em;
    }

    .fadeUp{
      animation: fadeUp .55s cubic-bezier(.16,1,.3,1) both;
    }
    @keyframes fadeUp{
      from{ opacity:0; transform: translateY(10px); }
      to{ opacity:1; transform: translateY(0); }
    }

    /* =========================
       TELEGRAM POPUP (FIRST VISIT)
    ========================== */
    .tgOverlay{
      position: fixed;
      inset: 0;
      z-index: 9999;
      background: rgba(15,23,42,.35);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      display:none;
      align-items:center;
      justify-content:center;
      padding: 18px 14px;
    }
    .tgOverlay.show{ display:flex; }

    .tgModal{
      width: 100%;
      max-width: 520px;
      background: rgba(255,255,255,.92);
      border: 1px solid rgba(15,23,42,.10);
      border-radius: 18px;
      box-shadow: 0 30px 80px rgba(15,23,42,.22);
      overflow:hidden;
      position:relative;
    }
    .tgTop{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap: 12px;
      padding: 14px 16px;
      border-bottom: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.88);
    }
    .tgTitle{
      display:flex;
      align-items:center;
      gap: 10px;
      font-weight: 950;
      color: var(--text);
      margin:0;
      font-size: 16px;
      letter-spacing: -0.01em;
    }
    .tgIcon{
      width: 34px; height: 34px;
      border-radius: 999px;
      display:flex;
      align-items:center;
      justify-content:center;
      background: linear-gradient(135deg, rgba(109,40,217,.16), rgba(6,182,212,.16));
      border: 1px solid rgba(6,182,212,.22);
      flex: 0 0 auto;
    }
    .tgClose{
      appearance:none;
      border: 0;
      width: 38px; height: 38px;
      border-radius: 12px;
      background: rgba(255,255,255,.80);
      border: 1px solid rgba(15,23,42,.10);
      cursor:pointer;
      display:flex;
      align-items:center;
      justify-content:center;
      transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    }
    .tgClose:hover{
      transform: translateY(-1px);
      border-color: rgba(6,182,212,.28);
      box-shadow: 0 16px 28px rgba(15,23,42,.10);
    }
    .tgClose:focus{
      outline:none;
      box-shadow: 0 0 0 4px rgba(6,182,212,.14);
      border-color: rgba(6,182,212,.40);
    }

    .tgBody{
      padding: 16px 16px 14px;
    }
    .tgDesc{
      margin: 0;
      color: var(--muted);
      font-size: 13.5px;
      line-height: 1.55;
      font-weight: 800;
      text-align:center;
    }

    .tgActions{
      padding: 14px 16px 16px;
      display:flex;
      gap: 10px;
    }
    .tgBtn{
      flex: 1 1 auto;
      border: 0;
      border-radius: 16px;
      padding: 12px 14px;
      font-weight: 950;
      cursor:pointer;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap: 10px;
      transition: transform .18s ease, box-shadow .18s ease, filter .18s ease, border-color .18s ease;
      user-select:none;
      text-decoration:none;
    }
    .tgBtnPrimary{
      background: linear-gradient(135deg, var(--primary1), var(--primary2));
      color: #081022;
      box-shadow: 0 18px 34px rgba(6,182,212,.18);
    }
    .tgBtnPrimary:hover{ transform: translateY(-1px); filter: brightness(1.02); }
    .tgBtnPrimary:focus{
      outline:none;
      box-shadow: 0 0 0 4px rgba(6,182,212,.14), 0 18px 34px rgba(6,182,212,.18);
    }
    .tgBtnGhost{
      background: rgba(255,255,255,.78);
      border: 1px solid rgba(15,23,42,.10);
      color: var(--muted);
    }
    .tgBtnGhost:hover{
      transform: translateY(-1px);
      border-color: rgba(6,182,212,.24);
      box-shadow: 0 16px 28px rgba(15,23,42,.10);
    }
    .tgBtnGhost:focus{
      outline:none;
      box-shadow: 0 0 0 4px rgba(6,182,212,.14);
      border-color: rgba(6,182,212,.40);
    }

    /* =========================
   SALDO KURANG POPUP
========================= */
.smOverlay{
  position: fixed;
  inset: 0;
  z-index: 9999;
  background: rgba(15,23,42,.35);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  display:none;
  align-items:center;
  justify-content:center;
  padding: 18px 14px;
}
.smOverlay.show{ display:flex; }

.smModal{
  width: 100%;
  max-width: 520px;
  background: rgba(255,255,255,.92);
  border: 1px solid rgba(15,23,42,.10);
  border-radius: 18px;
  box-shadow: 0 30px 80px rgba(15,23,42,.22);
  overflow:hidden;
}

.smTop{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap: 12px;
  padding: 14px 16px;
  border-bottom: 1px solid rgba(15,23,42,.10);
  background: rgba(255,255,255,.88);
}

.smTitle{
  display:flex;
  align-items:center;
  gap: 10px;
  font-weight: 950;
  color: var(--text);
  margin:0;
  font-size: 16px;
  letter-spacing: -0.01em;
}

.smIcon{
  width: 34px; height: 34px;
  border-radius: 999px;
  display:flex;
  align-items:center;
  justify-content:center;
  background: rgba(239,68,68,.10);
  border: 1px solid rgba(239,68,68,.20);
  flex: 0 0 auto;
}

.smClose{
  appearance:none;
  border: 0;
  width: 38px; height: 38px;
  border-radius: 12px;
  background: rgba(255,255,255,.80);
  border: 1px solid rgba(15,23,42,.10);
  cursor:pointer;
  display:flex;
  align-items:center;
  justify-content:center;
  transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
}
.smClose:hover{
  transform: translateY(-1px);
  border-color: rgba(6,182,212,.28);
  box-shadow: 0 16px 28px rgba(15,23,42,.10);
}
.smClose:focus{
  outline:none;
  box-shadow: 0 0 0 4px rgba(6,182,212,.14);
  border-color: rgba(6,182,212,.40);
}

.smBody{ padding: 16px 16px 14px; }
.smDesc{
  margin:0;
  color: var(--muted);
  font-size: 13.5px;
  line-height: 1.55;
  font-weight: 800;
  text-align:center;
}
.smDesc b{ color: var(--text); }

.smActions{
  padding: 14px 16px 16px;
  display:flex;
  gap: 10px;
}

  </style>
</head>

<body>
  {{-- TELEGRAM POPUP --}}
  <div class="tgOverlay" id="tgOverlay" role="dialog" aria-modal="true" aria-labelledby="tgTitle">
    <div class="tgModal">
      <div class="tgTop">
        <h3 class="tgTitle" id="tgTitle">
          <span class="tgIcon" aria-hidden="true">
            <!-- Telegram icon -->
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0f172a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:.9">
              <path d="M22 2 11 13"></path>
              <path d="M22 2 15 22 11 13 2 9 22 2"></path>
            </svg>
          </span>
          Grup resmi
        </h3>
        <button class="tgClose" type="button" id="tgCloseBtn" aria-label="Tutup">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0f172a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:.85">
            <path d="M18 6 6 18"></path>
            <path d="M6 6 18 18"></path>
          </svg>
        </button>
      </div>

      <div class="tgBody">
        <p class="tgDesc">
          Bergabunglah dengan satu-satunya grup resmi untuk mendapatkan informasi investasi terkini
          atau berkomunikasi dengan anggota lainnya.
        </p>
      </div>

      <div class="tgActions">
        <a class="tgBtn tgBtnPrimary" id="tgJoinBtn" href="https://t.me/crowdnikchannel" target="_blank" rel="noopener">
          Klik untuk bergabung
        </a>
        <button class="tgBtn tgBtnGhost" type="button" id="tgLaterBtn">
          Nanti saja
        </button>
      </div>
    </div>
  </div>

  {{-- SALDO KURANG POPUP --}}
<div class="smOverlay" id="saldoOverlay" role="dialog" aria-modal="true" aria-labelledby="saldoTitle">
  <div class="smModal">
    <div class="smTop">
      <h3 class="smTitle" id="saldoTitle">
        <span class="smIcon" aria-hidden="true">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0f172a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:.9">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M12 8v4"></path>
            <path d="M12 16h.01"></path>
          </svg>
        </span>
        Saldo Kurang
      </h3>

      <button class="smClose" type="button" id="saldoCloseBtn" aria-label="Tutup">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0f172a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:.85">
          <path d="M18 6 6 18"></path>
          <path d="M6 6 18 18"></path>
        </svg>
      </button>
    </div>

    <div class="smBody">
      <p class="smDesc" id="saldoDesc">
        Produk <b id="smProduct">-</b> butuh saldo <b id="smPrice">Rp 0</b>.
        Saldo kamu sekarang <b id="smSaldo">Rp 0</b>.
        Kekurangannya <b id="smShort">Rp 0</b>.
      </p>
    </div>

    <div class="smActions">
      <a class="tgBtn tgBtnPrimary" href="/deposit">
        Deposit Sekarang
      </a>
      <button class="tgBtn tgBtnGhost" type="button" id="saldoOkBtn">
        Oke
      </button>
    </div>
  </div>
</div>


  <div class="page">
    <div class="shell">
      <div class="card fadeUp">
        <div class="inner">

          {{-- Header --}}
          <div class="header">
            <div class="brand">
              <div class="logoBox" aria-hidden="true">
                <img src="/logo.png" alt="Logo" />
              </div>
              <div class="hello">
                <h1>Hi, {{ $user->name }}</h1>
                <p>Selamat Datang Kembali</p>
              </div>
            </div>

            <div class="vip" title="VIP Level">
              <span class="vipDot" aria-hidden="true"></span>
              <span class="vipText">{{ $user->vip_level }}</span>
            </div>
          </div>

          {{-- Laravel errors --}}
          @if($errors->any())
            <div class="errors fadeUp" role="alert">
              <div class="errorsTitle">
                <span class="ico" aria-hidden="true">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#b91c1c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M12 8v5"></path>
                    <path d="M12 16h.01"></path>
                  </svg>
                </span>
                Terjadi kesalahan saat memproses permintaan
              </div>
              <ul>
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          {{-- HERO --}}
          <div class="hero fadeUp">
            <div class="heroInner">
              <div class="heroTop">
                <div>
                  <p class="kicker">Total Saldo Aktif</p>
                  <div class="saldo">Rp {{ number_format($user->saldo,0,',','.') }}</div>
                </div>
              </div>

              <div class="chips" aria-label="Status">
                <span class="chip"><span class="chipDot" aria-hidden="true"></span>Realtime</span>
                <span class="chip chipMuted">
                  <svg class="chipIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                  </svg>
                  Aman
                </span>
              </div>

              <div class="quick" aria-label="Quick Actions">
                <a href="/deposit" class="qa">
                  <div class="qaIco" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M12 5v14"></path>
                      <path d="M5 12h14"></path>
                    </svg>
                  </div>
                  <div class="qaLbl">Deposit</div>
                </a>

                <a href="{{ url('/ui/withdrawals') }}" class="qa">
                  <div class="qaIco" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4"></path>
                      <path d="M4 6v12c0 1.1.9 2 2 2h14v-4"></path>
                      <path d="M18 12a2 2 0 0 0-2 2c0 1.1.9 2 2 2h4v-4h-4z"></path>
                    </svg>
                  </div>
                  <div class="qaLbl">Withdraw</div>
                </a>

                <a href="https://t.me/crowdnikchannel" target="_blank" rel="noopener" class="qa">
                <div class="qaIco" aria-hidden="true">
                    <!-- Telegram icon -->
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 2 11 13"></path>
                    <path d="M22 2 15 22 11 13 2 9 22 2"></path>
                    </svg>
                </div>
                <div class="qaLbl">Telegram</div>
                </a>


                <a href="/referral" class="qa">
                  <div class="qaIco" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                      <circle cx="8.5" cy="7" r="4"></circle>
                      <path d="M20 8v6"></path>
                      <path d="M23 11h-6"></path>
                    </svg>
                  </div>
                  <div class="qaLbl">Referral</div>
                </a>
              </div>
            </div>
          </div>

          {{-- PRODUK --}}
          <div class="section fadeUp">
            <div class="sectionInner">
              <div class="sectionTitle">
                <h2>Produk & Investasi</h2>
                <div class="hint">Pilih kategori lalu investasi</div>
              </div>

              <div class="tabsWrap" aria-label="Kategori Produk">
                <div class="tabs">
                  @foreach($categories as $i => $cat)
                    <button type="button" class="tab {{ $i===0?'active':'' }}" data-tab="cat-{{ $cat->id }}">
                      {{ $cat->name }}
                    </button>
                  @endforeach
                </div>
              </div>

              @foreach($categories as $i => $cat)
                <div id="cat-{{ $cat->id }}" style="{{ $i!==0?'display:none':'' }}">
                  <div class="list">
                    @forelse($cat->products as $product)
                      @php
                        $invActive   = $activeInvestments[$product->id] ?? null;
                        $vipKurang   = $user->vip_level < $product->min_vip_level;
                        $saldoKurang = $user->saldo < $product->price;
                      @endphp

                      <div class="prod">
                        <div class="prodHead">
                          <h3 class="prodName">{{ $product->name }}</h3>
                          <span class="pill">{{ $product->duration_days }} Hari</span>
                        </div>

                        <div class="prodBody">
                          <div class="stats">
                            <div>
                              <div class="sLabel">Profit Harian</div>
                              <div class="sVal">Rp {{ number_format($product->daily_profit,0,',','.') }}</div>
                            </div>
                            <div>
                              <div class="sLabel">Total Profit</div>
                              <div class="sVal sValStrong">Rp {{ number_format($product->total_profit,0,',','.') }}</div>
                            </div>
                          </div>
                        </div>

                        <div class="prodAction">
                          @if($invActive)
                            <a href="{{ route('investasi.index') }}" class="btn btnGhost">
                              <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 6v6l4 2"></path>
                              </svg>
                              Sedang Aktif (Lihat Investasi)
                            </a>
                          @elseif($vipKurang)
                            <button class="btn btnGhost" type="button" disabled style="opacity:.9; cursor:not-allowed;">
                              <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                              </svg>
                              VIP {{ $product->min_vip_level }} Required
                            </button>
                          @elseif($saldoKurang)
                            <button
                              class="btn btnPrimary js-saldo-kurang"
                              type="button"
                              data-price="{{ (int) $product->price }}"
                              data-saldo="{{ (int) $user->saldo }}"
                              data-product="{{ $product->name }}"
                            >
                              <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <path d="M3 6h18"></path>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                              </svg>
                              Investasi Sekarang
                            </button>
                          @else
                            <form method="POST" action="{{ url('/product/buy/'.$product->id) }}">
                              @csrf
                              <button class="btn btnPrimary" type="submit">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                  <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                  <path d="M3 6h18"></path>
                                  <path d="M16 10a4 4 0 0 1-8 0"></path>
                                </svg>
                                Investasi Sekarang
                              </button>
                            </form>
                          @endif
                        </div>
                      </div>
                    @empty
                      <div class="empty">Belum ada produk tersedia di kategori ini.</div>
                    @endforelse
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          {{-- NAV --}}
          <div class="nav fadeUp" aria-label="Navigation">
            <a href="#" class="active">
              <svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <path d="M9 22V12h6v10"></path>
              </svg>
              <span>Home</span>
            </a>

            <a href="{{ route('investasi.index') }}">
              <svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
              </svg>
              <span>Invest</span>
            </a>

            <a href="{{ route('team.index') }}">
              <svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
              </svg>
              <span>Team</span>
            </a>

            <a href="{{ url('/akun') }}">
              <svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
              <span>Akun</span>
            </a>
          </div>

        </div>
      </div>
    </div>
  </div>

  <script>
    // Tabs
    (function () {
      const tabs = Array.from(document.querySelectorAll('.tab'));
      const panes = Array.from(document.querySelectorAll('[id^="cat-"]'));
      function show(id){
        panes.forEach(p => p.style.display = (p.id === id ? 'block' : 'none'));
        tabs.forEach(t => t.classList.toggle('active', t.dataset.tab === id));
      }
      tabs.forEach(t => t.addEventListener('click', () => show(t.dataset.tab)));
    })();

    // Telegram popup (show once)
    (function () {
      const KEY = 'crowdnik_tg_popup_v1';
      const overlay = document.getElementById('tgOverlay');
      const closeBtn = document.getElementById('tgCloseBtn');
      const laterBtn = document.getElementById('tgLaterBtn');
      const joinBtn = document.getElementById('tgJoinBtn');

      if (!overlay) return;

      function open(){
        overlay.classList.add('show');
        document.documentElement.style.overflow = 'hidden';
        document.body.style.overflow = 'hidden';
      }

      function close(persist = true){
        overlay.classList.remove('show');
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
        if (persist) {
          try { localStorage.setItem(KEY, '1'); } catch {}
        }
      }

      // close via overlay click (outside modal)
      overlay.addEventListener('click', (e) => {
        if (e.target === overlay) close(true);
      });

      // close via X / later
      closeBtn.addEventListener('click', () => close(true));
      laterBtn.addEventListener('click', () => close(true));

      // join: mark as seen, then go telegram
      joinBtn.addEventListener('click', () => {
        try { localStorage.setItem(KEY, '1'); } catch {}
      });

      // Esc key
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && overlay.classList.contains('show')) close(true);
      });

      // show on first visit
      let seen = false;
      try { seen = localStorage.getItem(KEY) === '1'; } catch {}
      if (!seen) {
        // small delay biar halaman kebuka dulu
        setTimeout(open, 450);
      }
    })();
  </script>
</body>
</html>
