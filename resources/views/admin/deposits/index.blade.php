@php
    $authUser = auth()->user();

    function adminInitial($name) {
        return strtoupper(substr($name ?: 'A', 0, 1));
    }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<title>Admin | Deposits</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
:root {
    --bg:#f7f9fd;
    --card:#ffffff;
    --text:#101828;
    --muted:#667085;
    --muted-2:#98a2b3;
    --line:#e7ebf3;
    --blue:#3157f8;
    --blue-soft:#eef3ff;
    --green:#12b76a;
    --green-soft:#eafaf2;
    --yellow:#f79009;
    --yellow-soft:#fff6e6;
    --red:#f04438;
    --red-soft:#fff0ee;
    --purple:#7a5af8;
    --purple-soft:#f3f0ff;
    --slate-soft:#f2f4f7;
    --shadow:0 18px 40px rgba(16,24,40,.06);
    --shadow-soft:0 10px 24px rgba(16,24,40,.045);
    --sidebar:260px;
    --topbar:74px;
    --radius-xl:28px;
}

*{box-sizing:border-box}
html,body{min-height:100%}
body{
    margin:0;
    font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Arial,sans-serif;
    color:var(--text);
    background:
        radial-gradient(900px 420px at 55% -10%, rgba(49,87,248,.10), transparent 62%),
        radial-gradient(760px 380px at 96% 8%, rgba(18,183,106,.08), transparent 58%),
        var(--bg);
    overflow-x:hidden;
}
a{color:inherit;text-decoration:none}
button,input,select{font-family:inherit}

.admin-shell{
    min-height:100vh;
    display:grid;
    grid-template-columns:var(--sidebar) 1fr;
}

.sidebar{
    position:sticky;
    top:0;
    height:100vh;
    background:rgba(255,255,255,.86);
    backdrop-filter:blur(18px);
    border-right:1px solid var(--line);
    padding:18px 14px;
    display:flex;
    flex-direction:column;
    z-index:60;
}

.brand{
    display:flex;
    align-items:center;
    gap:12px;
    padding:10px 10px 18px;
    border-bottom:1px solid var(--line);
}

.brand-logo{
    width:42px;
    height:42px;
    border-radius:15px;
    display:grid;
    place-items:center;
    background:linear-gradient(135deg,#3157f8 0%,#5a7cff 52%,#12b76a 100%);
    color:#fff;
    font-weight:950;
    letter-spacing:-.04em;
    box-shadow:0 14px 28px rgba(49,87,248,.24);
}

.brand-name strong{display:block;font-size:15px;line-height:1.1}
.brand-name span{display:block;margin-top:4px;font-size:12px;color:var(--muted)}

.sidebar-search{
    margin:16px 4px 12px;
    position:relative;
}

.sidebar-search span{
    position:absolute;
    left:13px;
    top:50%;
    transform:translateY(-50%);
    color:var(--muted-2);
    font-size:14px;
}

.sidebar-search input{
    width:100%;
    height:44px;
    border-radius:15px;
    border:1px solid var(--line);
    background:#f7f9fd;
    outline:none;
    padding:0 14px 0 40px;
    font-size:13px;
    color:var(--text);
}

.nav-scroll{
    overflow:auto;
    padding-right:2px;
    flex:1;
}

.nav-label{
    margin:18px 10px 8px;
    color:var(--muted-2);
    font-size:11px;
    font-weight:800;
    letter-spacing:.11em;
    text-transform:uppercase;
}

.nav-item{
    display:flex;
    align-items:center;
    gap:12px;
    padding:11px 10px;
    margin:5px 2px;
    border-radius:16px;
    border:1px solid transparent;
    color:#475467;
    transition:.18s ease;
}

.nav-item:hover{
    background:var(--blue-soft);
    color:var(--blue);
    transform:translateX(2px);
}

.nav-item.active{
    color:var(--blue);
    background:var(--blue-soft);
    border-color:rgba(49,87,248,.12);
    box-shadow:inset 3px 0 0 var(--blue);
}

.nav-icon{
    width:36px;
    height:36px;
    border-radius:14px;
    display:grid;
    place-items:center;
    background:#f2f5fb;
    flex:0 0 auto;
    font-size:16px;
}

.nav-text b{display:block;font-size:13px;line-height:1.1}
.nav-text span{
    display:block;
    margin-top:4px;
    font-size:11.5px;
    color:var(--muted);
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.sidebar-user{
    margin-top:14px;
    border:1px solid var(--line);
    border-radius:20px;
    padding:12px;
    background:linear-gradient(180deg,#fff 0%,#f9fbff 100%);
    box-shadow:var(--shadow-soft);
}

.user-mini{display:flex;align-items:center;gap:10px}
.avatar{
    width:42px;
    height:42px;
    border-radius:15px;
    display:grid;
    place-items:center;
    background:linear-gradient(135deg,#101828,#344054);
    color:#fff;
    font-weight:900;
    flex:0 0 auto;
}
.user-mini b{display:block;font-size:13px;max-width:150px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.user-mini span{display:block;margin-top:4px;font-size:11.5px;color:var(--muted)}

.main{
    min-width:0;
    padding:18px 24px 30px;
}

.topbar{
    height:var(--topbar);
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:18px;
    margin-bottom:18px;
}

.top-left{display:flex;align-items:center;gap:14px;min-width:0}

.hamburger{
    display:none;
    width:42px;
    height:42px;
    border:1px solid var(--line);
    background:#fff;
    border-radius:14px;
    cursor:pointer;
    font-weight:950;
    color:var(--text);
    box-shadow:var(--shadow-soft);
}

.page-title h1{
    margin:0;
    font-size:24px;
    line-height:1.1;
    letter-spacing:-.04em;
}

.page-title p{
    margin:6px 0 0;
    color:var(--muted);
    font-size:13px;
}

.top-actions{display:flex;align-items:center;gap:10px}

.icon-btn,.logout-btn{
    height:42px;
    border-radius:14px;
    border:1px solid var(--line);
    background:#fff;
    box-shadow:var(--shadow-soft);
    cursor:pointer;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    color:var(--text);
}

.icon-btn{width:42px;font-size:17px}
.logout-btn{
    padding:0 14px;
    gap:8px;
    color:var(--red);
    font-weight:800;
    font-size:13px;
    border-color:rgba(240,68,56,.18);
}

.status-pill{
    height:42px;
    display:inline-flex;
    align-items:center;
    gap:9px;
    padding:0 14px;
    border-radius:999px;
    background:#fff;
    border:1px solid var(--line);
    box-shadow:var(--shadow-soft);
    color:var(--muted);
    font-size:13px;
    font-weight:700;
}
.live-dot{
    width:9px;
    height:9px;
    border-radius:99px;
    background:var(--green);
    box-shadow:0 0 0 5px rgba(18,183,106,.13);
}

.hero{
    position:relative;
    overflow:hidden;
    border-radius:var(--radius-xl);
    background:
        radial-gradient(420px 240px at 88% 8%, rgba(255,255,255,.18), transparent 55%),
        linear-gradient(135deg,#12b76a 0%,#3157f8 58%,#172554 100%);
    color:#fff;
    padding:24px;
    box-shadow:0 24px 46px rgba(49,87,248,.22);
    margin-bottom:18px;
}

.hero::after{
    content:"";
    position:absolute;
    width:230px;
    height:230px;
    border-radius:999px;
    right:-70px;
    bottom:-100px;
    background:rgba(255,255,255,.11);
}

.hero-content{position:relative;z-index:2;max-width:780px}
.hero-kicker{
    display:inline-flex;
    align-items:center;
    gap:8px;
    height:32px;
    padding:0 12px;
    border-radius:999px;
    background:rgba(255,255,255,.14);
    border:1px solid rgba(255,255,255,.18);
    font-size:12px;
    font-weight:800;
}
.hero h2{
    margin:16px 0 8px;
    font-size:30px;
    line-height:1.05;
    letter-spacing:-.055em;
}
.hero p{
    margin:0;
    color:rgba(255,255,255,.76);
    font-size:14px;
    line-height:1.55;
}

.summary-grid{
    display:grid;
    grid-template-columns:repeat(4,minmax(0,1fr));
    gap:12px;
    margin-bottom:18px;
}

.summary-card{
    background:#fff;
    border:1px solid var(--line);
    border-radius:22px;
    padding:15px;
    box-shadow:var(--shadow-soft);
}

.summary-card span{
    display:block;
    color:var(--muted);
    font-size:12px;
    font-weight:800;
    margin-bottom:8px;
}

.summary-card b{
    display:block;
    color:var(--text);
    font-size:20px;
    line-height:1;
    font-weight:950;
    letter-spacing:-.04em;
}

.panel{
    background:#fff;
    border:1px solid var(--line);
    border-radius:var(--radius-xl);
    box-shadow:var(--shadow);
    overflow:hidden;
}

.panel-head{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:14px;
    padding:18px 18px 0;
}

.panel-title b{display:block;font-size:15px;letter-spacing:-.02em}
.panel-title span{display:block;margin-top:4px;font-size:12.5px;color:var(--muted)}

.toolbar{
    display:flex;
    gap:10px;
    align-items:center;
    flex-wrap:wrap;
    justify-content:flex-end;
}

.select,.search{
    height:42px;
    border-radius:15px;
    border:1px solid var(--line);
    background:#f9fbff;
    outline:none;
    padding:0 12px;
    color:var(--text);
    font-size:13px;
    font-weight:800;
}
.select{width:190px}
.search{width:240px}

.btn{
    height:42px;
    padding:0 14px;
    border-radius:13px;
    border:1px solid rgba(49,87,248,.14);
    background:var(--blue);
    color:#fff;
    font-weight:900;
    font-size:12px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    box-shadow:0 14px 24px rgba(49,87,248,.20);
}

.btn-mini{
    min-height:34px;
    padding:0 12px;
    border-radius:13px;
    border:1px solid rgba(49,87,248,.14);
    background:var(--blue-soft);
    color:var(--blue);
    font-weight:900;
    font-size:12px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
}

.btn-paid{
    background:var(--green-soft);
    color:#027a48;
    border-color:rgba(18,183,106,.16);
}

.btn-failed{
    background:var(--red-soft);
    color:#b42318;
    border-color:rgba(240,68,56,.18);
}

.btn-url{
    background:#101828;
    color:#fff;
    border-color:rgba(16,24,40,.18);
}

.panel-inner{padding:18px}
.hint{
    color:var(--muted);
    font-size:12.5px;
    line-height:1.5;
    font-weight:700;
}

.rows{
    display:grid;
    gap:12px;
}

.row-card{
    display:grid;
    grid-template-columns:5px 1fr;
    border:1px solid var(--line);
    border-radius:22px;
    background:#fff;
    box-shadow:var(--shadow-soft);
    overflow:hidden;
}

.row-body{
    padding:15px;
    display:grid;
    grid-template-columns:1fr auto;
    gap:14px;
    align-items:start;
}

.bar-unpaid{background:var(--yellow)}
.bar-paid{background:var(--green)}
.bar-failed{background:var(--red)}
.bar-expired{background:var(--muted-2)}

.amount-line{
    display:flex;
    align-items:center;
    gap:10px;
    flex-wrap:wrap;
}

.amount{
    font-weight:950;
    font-size:16px;
    letter-spacing:-.02em;
}

.status-badge{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-height:28px;
    padding:0 10px;
    border-radius:999px;
    font-size:11.5px;
    font-weight:950;
    border:1px solid transparent;
    white-space:nowrap;
}

.status-unpaid{
    color:#b54708;
    background:var(--yellow-soft);
    border-color:rgba(247,144,9,.18);
}
.status-paid{
    color:#027a48;
    background:var(--green-soft);
    border-color:rgba(18,183,106,.16);
}
.status-failed{
    color:#b42318;
    background:var(--red-soft);
    border-color:rgba(240,68,56,.18);
}
.status-expired{
    color:#475467;
    background:var(--slate-soft);
    border-color:#eaecf0;
}

.meta{
    margin-top:8px;
    color:var(--muted);
    font-size:12.5px;
    line-height:1.6;
    font-weight:700;
}
.meta b{color:var(--text)}

.row-right{
    display:flex;
    flex-direction:column;
    align-items:flex-end;
    gap:10px;
    text-align:right;
}

.actions-list{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
    justify-content:flex-end;
}

.gateway-box{
    margin-top:12px;
    border:1px solid rgba(49,87,248,.12);
    background:
        radial-gradient(220px 120px at 100% 0%, rgba(49,87,248,.08), transparent 60%),
        linear-gradient(180deg,#ffffff 0%,#f8faff 100%);
    border-radius:18px;
    padding:12px;
}

.gateway-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    margin-bottom:10px;
}

.gateway-title{
    display:inline-flex;
    align-items:center;
    gap:7px;
    color:#101828;
    font-size:12.5px;
    font-weight:950;
}

.gateway-title::before{
    content:"";
    width:8px;
    height:8px;
    border-radius:99px;
    background:var(--blue);
    box-shadow:0 0 0 4px rgba(49,87,248,.10);
}

.gateway-pill{
    display:inline-flex;
    align-items:center;
    min-height:24px;
    padding:0 9px;
    border-radius:999px;
    color:var(--blue);
    background:var(--blue-soft);
    border:1px solid rgba(49,87,248,.14);
    font-size:10.5px;
    font-weight:950;
    white-space:nowrap;
}
.gateway-pill.is-success{color:#027a48;background:var(--green-soft);border-color:rgba(18,183,106,.16)}
.gateway-pill.is-failed{color:#b42318;background:var(--red-soft);border-color:rgba(240,68,56,.18)}

.gateway-grid{
    display:grid;
    grid-template-columns:repeat(2,minmax(0,1fr));
    gap:8px;
}

.gateway-item{
    min-width:0;
    border:1px solid var(--line);
    background:#fff;
    border-radius:14px;
    padding:9px 10px;
}

.gateway-item span{
    display:block;
    color:var(--muted);
    font-size:10.5px;
    font-weight:800;
    margin-bottom:5px;
}

.gateway-item b{
    display:block;
    color:var(--text);
    font-size:12px;
    line-height:1.35;
    font-weight:900;
    word-break:break-word;
}

.gateway-item.is-wide{grid-column:1 / -1}

.gateway-message{
    margin-top:8px;
    border-radius:14px;
    padding:9px 10px;
    background:var(--yellow-soft);
    border:1px solid rgba(247,144,9,.18);
    color:#b54708;
    font-size:11.5px;
    line-height:1.45;
    font-weight:800;
}

.empty,.loading{
    padding:34px 18px;
    text-align:center;
    color:var(--muted);
    font-size:13px;
    border:1px dashed var(--line);
    border-radius:20px;
    background:#f9fbff;
}

.toast{
    position:fixed;
    left:50%;
    bottom:24px;
    transform:translateX(-50%) translateY(12px);
    opacity:0;
    pointer-events:none;
    z-index:9999;
    min-height:44px;
    padding:0 16px;
    border-radius:999px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#06110e;
    background:linear-gradient(135deg,#12b76a,#86efac);
    box-shadow:0 18px 42px rgba(16,24,40,.25);
    font-size:12px;
    font-weight:900;
    transition:.22s ease;
}
.toast.show{
    opacity:1;
    transform:translateX(-50%) translateY(0);
}
.toast.err{
    color:#fff;
    background:linear-gradient(135deg,#f04438,#fb7185);
}

.overlay{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(16,24,40,.48);
    backdrop-filter:blur(4px);
    z-index:50;
}
body.sidebar-open{overflow:hidden}
body.sidebar-open .overlay{display:block}

@media(max-width:1180px){
    .panel-head{flex-direction:column}
    .toolbar{width:100%;justify-content:flex-start}
    .summary-grid{grid-template-columns:repeat(2,1fr)}
}

@media(max-width:860px){
    .admin-shell{grid-template-columns:1fr}
    .sidebar{
        position:fixed;
        left:0;
        top:0;
        width:86vw;
        max-width:320px;
        transform:translateX(-105%);
        transition:.22s ease;
        box-shadow:24px 0 50px rgba(16,24,40,.18);
    }
    body.sidebar-open .sidebar{transform:translateX(0)}
    .hamburger{display:inline-grid;place-items:center;flex:0 0 auto}
    .main{padding:12px 14px 24px}
    .topbar{
        position:sticky;
        top:0;
        z-index:40;
        height:auto;
        min-height:66px;
        padding:10px;
        border-radius:20px;
        background:rgba(247,249,253,.86);
        backdrop-filter:blur(14px);
        border:1px solid var(--line);
    }
    .page-title h1{font-size:19px}
    .page-title p{font-size:12px}
    .status-pill{display:none}
    .hero{padding:20px}
    .hero h2{font-size:24px}
}

@media(max-width:620px){
    .top-actions .icon-btn{display:none}
    .logout-btn{width:42px;padding:0;font-size:0}
    .logout-btn span{font-size:17px}
    .toolbar,.select,.search,.btn{width:100%}
    .row-body{grid-template-columns:1fr}
    .row-right{align-items:flex-start;text-align:left}
    .actions-list{justify-content:flex-start;width:100%}
    .btn-mini{flex:1}
    .gateway-grid{grid-template-columns:1fr}
    .summary-grid{grid-template-columns:1fr}
}


.rows{
    display:grid;
    gap:14px;
}

.row-card{
    display:grid;
    grid-template-columns:6px 1fr;
    border:1px solid var(--line);
    border-radius:24px;
    background:#fff;
    box-shadow:var(--shadow-soft);
    overflow:hidden;
    transition:.18s ease;
}

.row-card:hover{
    transform:translateY(-1px);
    box-shadow:0 20px 42px rgba(16,24,40,.08);
}

.row-body{
    padding:16px;
}

.deposit-main{
    display:grid;
    grid-template-columns:1fr auto;
    gap:16px;
    align-items:flex-start;
}

.deposit-left{
    min-width:0;
}

.deposit-top{
    display:flex;
    align-items:center;
    gap:10px;
    flex-wrap:wrap;
    margin-bottom:10px;
}

.amount{
    color:#101828;
    font-weight:950;
    font-size:22px;
    line-height:1;
    letter-spacing:-.045em;
}

.status-badge{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-height:28px;
    padding:0 10px;
    border-radius:999px;
    font-size:11.5px;
    font-weight:950;
    border:1px solid transparent;
    white-space:nowrap;
}

.status-unpaid{
    color:#b54708;
    background:var(--yellow-soft);
    border-color:rgba(247,144,9,.18);
}

.status-paid{
    color:#027a48;
    background:var(--green-soft);
    border-color:rgba(18,183,106,.16);
}

.status-failed{
    color:#b42318;
    background:var(--red-soft);
    border-color:rgba(240,68,56,.18);
}

.status-expired{
    color:#475467;
    background:var(--slate-soft);
    border-color:#eaecf0;
}

.deposit-user{
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:12px;
}

.deposit-avatar{
    width:40px;
    height:40px;
    border-radius:15px;
    display:grid;
    place-items:center;
    background:var(--blue-soft);
    color:var(--blue);
    font-size:13px;
    font-weight:950;
    flex:0 0 auto;
}

.deposit-user-info{
    min-width:0;
}

.deposit-user-info b{
    display:block;
    color:#101828;
    font-size:13.5px;
    line-height:1.15;
    font-weight:950;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.deposit-user-info span{
    display:block;
    margin-top:4px;
    color:var(--muted);
    font-size:12px;
    font-weight:700;
}

.deposit-info-grid{
    display:grid;
    grid-template-columns:repeat(3,minmax(0,1fr));
    gap:8px;
}

.deposit-info{
    min-width:0;
    border:1px solid var(--line);
    background:#f9fbff;
    border-radius:15px;
    padding:9px 10px;
}

.deposit-info span{
    display:block;
    color:var(--muted);
    font-size:10.5px;
    font-weight:850;
    margin-bottom:5px;
}

.deposit-info b{
    display:block;
    color:#101828;
    font-size:12px;
    line-height:1.3;
    font-weight:900;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.deposit-right{
    min-width:210px;
    display:flex;
    flex-direction:column;
    align-items:flex-end;
    gap:10px;
    text-align:right;
}

.deposit-date{
    color:var(--muted);
    font-size:12px;
    font-weight:800;
}

.actions-list{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
    justify-content:flex-end;
}

.btn-mini{
    min-height:34px;
    padding:0 12px;
    border-radius:13px;
    border:1px solid rgba(49,87,248,.14);
    background:var(--blue-soft);
    color:var(--blue);
    font-weight:900;
    font-size:12px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    transition:.18s ease;
}

.btn-mini:hover{
    transform:translateY(-1px);
}

.btn-paid{
    background:var(--green-soft);
    color:#027a48;
    border-color:rgba(18,183,106,.16);
}

.btn-failed{
    background:var(--red-soft);
    color:#b42318;
    border-color:rgba(240,68,56,.18);
}

.btn-url{
    background:#101828;
    color:#fff;
    border-color:rgba(16,24,40,.18);
}

.btn-detail{
    background:#fff;
    color:#344054;
    border-color:var(--line);
}

.gateway-box{
    display:none;
    margin-top:14px;
    border:1px solid rgba(49,87,248,.12);
    background:
        radial-gradient(220px 120px at 100% 0%, rgba(49,87,248,.08), transparent 60%),
        linear-gradient(180deg,#ffffff 0%,#f8faff 100%);
    border-radius:20px;
    padding:13px;
}

.row-card.show-gateway .gateway-box{
    display:block;
}

.gateway-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    margin-bottom:10px;
}

.gateway-title{
    display:inline-flex;
    align-items:center;
    gap:7px;
    color:#101828;
    font-size:12.5px;
    font-weight:950;
}

.gateway-title::before{
    content:"";
    width:8px;
    height:8px;
    border-radius:99px;
    background:var(--blue);
    box-shadow:0 0 0 4px rgba(49,87,248,.10);
}

.gateway-pill{
    display:inline-flex;
    align-items:center;
    min-height:24px;
    padding:0 9px;
    border-radius:999px;
    color:var(--blue);
    background:var(--blue-soft);
    border:1px solid rgba(49,87,248,.14);
    font-size:10.5px;
    font-weight:950;
    white-space:nowrap;
}

.gateway-pill.is-success{
    color:#027a48;
    background:var(--green-soft);
    border-color:rgba(18,183,106,.16);
}

.gateway-pill.is-failed{
    color:#b42318;
    background:var(--red-soft);
    border-color:rgba(240,68,56,.18);
}

.gateway-grid{
    display:grid;
    grid-template-columns:repeat(4,minmax(0,1fr));
    gap:8px;
}

.gateway-item{
    min-width:0;
    border:1px solid var(--line);
    background:#fff;
    border-radius:14px;
    padding:9px 10px;
}

.gateway-item span{
    display:block;
    color:var(--muted);
    font-size:10.5px;
    font-weight:800;
    margin-bottom:5px;
}

.gateway-item b{
    display:block;
    color:var(--text);
    font-size:12px;
    line-height:1.35;
    font-weight:900;
    word-break:break-word;
}

.gateway-item.is-wide{
    grid-column:1 / -1;
}

.gateway-message{
    margin-top:8px;
    border-radius:14px;
    padding:9px 10px;
    background:var(--yellow-soft);
    border:1px solid rgba(247,144,9,.18);
    color:#b54708;
    font-size:11.5px;
    line-height:1.45;
    font-weight:800;
}

@media(max-width:1180px){
    .gateway-grid{
        grid-template-columns:repeat(2,minmax(0,1fr));
    }

    .deposit-info-grid{
        grid-template-columns:repeat(2,minmax(0,1fr));
    }
}

@media(max-width:620px){
    .deposit-main{
        grid-template-columns:1fr;
    }

    .deposit-right{
        min-width:0;
        align-items:flex-start;
        text-align:left;
    }

    .actions-list{
        justify-content:flex-start;
        width:100%;
    }

    .btn-mini{
        flex:1;
    }

    .deposit-info-grid,
    .gateway-grid{
        grid-template-columns:1fr;
    }
}
</style>
</head>

<body>
<div class="admin-shell">
    <div class="overlay" onclick="toggleSidebar(false)"></div>

    <aside class="sidebar">
        <div class="brand">
            <div class="brand-logo">CW</div>
            <div class="brand-name">
                <strong>Crowdink</strong>
                <span>Admin Console</span>
            </div>
        </div>

        <div class="sidebar-search">
            <span>⌕</span>
            <input type="text" placeholder="Search menu..." oninput="filterMenu(this.value)">
        </div>

        <div class="nav-scroll">
            <nav id="navMenu">
                <div class="nav-label">Main Menu</div>

                <a class="nav-item" href="/admin" data-label="dashboard overview admin panel">
                    <div class="nav-icon">🏠</div>
                    <div class="nav-text">
                        <b>Dashboard</b>
                        <span>Overview sistem</span>
                    </div>
                </a>

                <a class="nav-item" href="/admin/users" data-label="users kelola user vip saldo">
                    <div class="nav-icon">👥</div>
                    <div class="nav-text">
                        <b>Users</b>
                        <span>Kelola user & VIP</span>
                    </div>
                </a>

                <a class="nav-item" href="/admin/products" data-label="products produk tier investasi">
                    <div class="nav-icon">📦</div>
                    <div class="nav-text">
                        <b>Products</b>
                        <span>Produk & tier</span>
                    </div>
                </a>

                <a class="nav-item active" href="{{ route('admin.deposits.page') }}" data-label="deposits deposit saldo pembayaran">
                    <div class="nav-icon">💳</div>
                    <div class="nav-text">
                        <b>Deposits</b>
                        <span>Riwayat isi saldo</span>
                    </div>
                </a>

                <a class="nav-item" href="{{ route('admin.withdraw.page') }}" data-label="withdraw penarikan saldo">
                    <div class="nav-icon">↗</div>
                    <div class="nav-text">
                        <b>Withdraw</b>
                        <span>Permintaan penarikan</span>
                    </div>
                </a>

                <a class="nav-item" href="{{ route('admin.referral') }}" data-label="referral komisi invite users">
                    <div class="nav-icon">🎁</div>
                    <div class="nav-text">
                        <b>Referral</b>
                        <span>Users & komisi</span>
                    </div>
                </a>

                <a class="nav-item" href="{{ route('admin.forum.index') }}" data-label="forum post komentar team">
                    <div class="nav-icon">💬</div>
                    <div class="nav-text">
                        <b>Forum</b>
                        <span>Posting & komentar</span>
                    </div>
                </a>

                <div class="nav-label">System</div>

                <a class="nav-item" href="/admin/vip" data-label="vip settings level rule">
                    <div class="nav-icon">⭐</div>
                    <div class="nav-text">
                        <b>VIP Settings</b>
                        <span>Aturan level VIP</span>
                    </div>
                </a>

                <a class="nav-item" href="/admin/logs" data-label="logs aktivitas sistem">
                    <div class="nav-icon">📜</div>
                    <div class="nav-text">
                        <b>Logs</b>
                        <span>Aktivitas sistem</span>
                    </div>
                </a>

                <a class="nav-item" href="/" data-label="website back site home">
                    <div class="nav-icon">🌐</div>
                    <div class="nav-text">
                        <b>Back to Site</b>
                        <span>Kembali ke website</span>
                    </div>
                </a>
            </nav>
        </div>

        <div class="sidebar-user">
            <div class="user-mini">
                <div class="avatar">{{ adminInitial($authUser->name ?? 'Admin') }}</div>
                <div>
                    <b>{{ $authUser->name ?? 'Admin' }}</b>
                    <span>Super Administrator</span>
                </div>
            </div>
        </div>
    </aside>

    <main class="main">
        <header class="topbar">
            <div class="top-left">
                <button class="hamburger" type="button" onclick="toggleSidebar(true)">☰</button>

                <div class="page-title">
                    <h1>Deposits</h1>
                    <p>Audit invoice deposit, status pembayaran, dan response gateway JayaPay.</p>
                </div>
            </div>

            <div class="top-actions">
                <div class="status-pill">
                    <span class="live-dot"></span>
                    <span>System Online</span>
                </div>

                <a href="/admin/users" class="icon-btn" title="Users">👥</a>
                <a href="{{ route('admin.withdraw.page') }}" class="icon-btn" title="Withdraw">↗</a>

                <form action="/logout" method="POST" style="margin:0">
                    @csrf
                    <button class="logout-btn" type="submit">
                        <span>⎋</span>
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <section class="hero">
            <div class="hero-content">
                <div class="hero-kicker">
                    <span>💳</span>
                    Deposit Management
                </div>

                <h2>Panel audit deposit yang menampilkan data pembayaran dan gateway response secara rapi.</h2>

                <p>
                    Admin dapat melihat invoice, order number, channel pembayaran, nominal real, fee gateway,
                    link pembayaran, expired time, hingga response callback JayaPay.
                </p>
            </div>
        </section>

        <section class="summary-grid">
            <div class="summary-card">
                <span>Total Data</span>
                <b id="sumTotal">0</b>
            </div>

            <div class="summary-card">
                <span>Paid</span>
                <b id="sumPaid">0</b>
            </div>

            <div class="summary-card">
                <span>Unpaid</span>
                <b id="sumUnpaid">0</b>
            </div>

            <div class="summary-card">
                <span>Total Paid Amount</span>
                <b id="sumPaidAmount">Rp 0</b>
            </div>
        </section>

        <section class="panel">
            <div class="panel-head">
                <div class="panel-title">
                    <b>Deposit Requests</b>
                   <span>Data diambil dari endpoint JSON `/admin/deposits/data`.</span>
                </div>

                <div class="toolbar">
                    <input id="keyword" class="search" placeholder="Cari order/user/phone..." />

                    <select id="status" class="select" aria-label="Filter status">
                        <option value="">All Status</option>
                        <option value="UNPAID">UNPAID</option>
                        <option value="PAID">PAID</option>
                        <option value="FAILED">FAILED</option>
                    </select>

                    <button id="btnLoad" class="btn" type="button">⟳ Load</button>
                </div>
            </div>

            <div class="panel-inner">
                <div class="hint" style="margin-bottom:14px">
                    Tips: klik tombol invoice untuk melihat halaman pembayaran user. Gateway response dibuat ringkas agar mudah diaudit.
                </div>

                <div id="rows" class="rows">
                    <div class="loading">Loading data deposit...</div>
                </div>
            </div>
        </section>
    </main>
</div>

<div id="toast" class="toast">Berhasil</div>

<script>
function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

async function api(url, options = {}) {
    const headers = {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken(),
        ...(options.headers || {})
    };

    const res = await fetch(url, {
        credentials: 'same-origin',
        ...options,
        headers
    });

    let data = null;

    try {
        data = await res.json();
    } catch (e) {
        data = {};
    }

    if (!res.ok) {
        throw new Error(data?.message || data?.error || 'Request gagal.');
    }

    return data;
}

function toast(message, type = 'success') {
    const el = document.getElementById('toast');
    el.textContent = message;
    el.classList.toggle('err', type === 'err');
    el.classList.add('show');

    clearTimeout(window.__toastTimer);
    window.__toastTimer = setTimeout(() => {
        el.classList.remove('show');
    }, 1800);
}

function toggleSidebar(open) {
    document.body.classList.toggle('sidebar-open', open === true);
}

function filterMenu(q) {
    q = (q || '').toLowerCase().trim();

    document.querySelectorAll('#navMenu .nav-item').forEach((item) => {
        const label = (item.getAttribute('data-label') || item.textContent || '').toLowerCase();
        item.style.display = !q || label.includes(q) ? 'flex' : 'none';
    });
}

function rupiah(n) {
    try {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(n || 0));
    } catch {
        return 'Rp ' + String(n || 0);
    }
}

function escapeHtml(str) {
    return String(str ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function initialName(name) {
    const text = String(name || 'U').trim();
    return escapeHtml(text.charAt(0).toUpperCase() || 'U');
}

function parseJson(value) {
    if (!value) return null;

    if (typeof value === 'object') {
        return value;
    }

    try {
        return JSON.parse(value);
    } catch (e) {
        return null;
    }
}

function firstFilled(...values) {
    for (const value of values) {
        if (value !== null && value !== undefined && String(value).trim() !== '') {
            return value;
        }
    }

    return '-';
}

function statusKey(status) {
    return String(status || '').toUpperCase();
}

function isExpired(row) {
    if (!row.expired_at || statusKey(row.status) === 'PAID') return false;

    const expired = new Date(row.expired_at);
    if (Number.isNaN(expired.getTime())) return false;

    return Date.now() > expired.getTime();
}

function statusBarClass(row) {
    const s = statusKey(row.status);

    if (s === 'PAID') return 'bar-paid';
    if (s === 'FAILED') return 'bar-failed';
    if (isExpired(row)) return 'bar-expired';

    return 'bar-unpaid';
}

function statusBadge(row) {
    const s = statusKey(row.status);

    if (s === 'PAID') {
        return `<span class="status-badge status-paid">PAID</span>`;
    }

    if (s === 'FAILED') {
        return `<span class="status-badge status-failed">FAILED</span>`;
    }

    if (isExpired(row)) {
        return `<span class="status-badge status-expired">EXPIRED</span>`;
    }

    return `<span class="status-badge status-unpaid">${escapeHtml(s || 'UNPAID')}</span>`;
}

function gatewayPillClass(code, status) {
    const raw = [code, status].map(v => String(v || '').toUpperCase()).join(' ');

    if (raw.includes('SUCCESS') || raw.includes('PAID')) return 'is-success';
    if (raw.includes('FAILED') || raw.includes('FAIL') || raw.includes('ERROR')) return 'is-failed';

    return '';
}

function gatewayInfoHtml(r) {
    const g = parseJson(r.gateway_response);
    const response = g?.response || g?.data || g || {};
    const payData = parseJson(r.pay_data) || parseJson(response?.payData) || {};

    const orderNum = firstFilled(
        r.order_id,
        response?.orderNum,
        g?.orderNum
    );

    const platOrderNum = firstFilled(
        r.plat_order_num,
        response?.platOrderNum,
        g?.platOrderNum
    );

    const gatewayCode = firstFilled(
        response?.platRespCode,
        g?.platRespCode,
        g?.status,
        r.status
    );

    const gatewayMessage = firstFilled(
        response?.platRespMessage,
        g?.platRespMessage,
        g?.message,
        g?.statusMsg
    );

    const payUrl = firstFilled(
        r.pay_url,
        response?.url
    );

    const method = firstFilled(
        r.method,
        response?.method
    );

    const selectedChannel = firstFilled(
        r.selected_channel,
        response?.secondMethod,
        method
    );

    const realAmount = Number(firstFilled(
        r.real_amount,
        payData?.realMoney,
        payData?.matchingId,
        r.amount,
        0
    ));

    const payFee = Number(firstFilled(
        r.pay_fee,
        response?.payFee,
        g?.payFee,
        0
    ));

    const expiredAt = firstFilled(
        r.expired_at,
        '-'
    );

    const paidAt = firstFilled(
        r.paid_at,
        '-'
    );

    const hasGatewayData =
        r.gateway_response ||
        r.pay_url ||
        r.plat_order_num ||
        r.pay_data ||
        r.pay_fee ||
        r.real_amount ||
        r.expired_at ||
        r.paid_at;

    if (!hasGatewayData) {
        return '';
    }

    const pillClass = gatewayPillClass(gatewayCode, r.status);

    const messageHtml = gatewayMessage !== '-'
        ? `<div class="gateway-message"><b>Gateway Message:</b> ${escapeHtml(gatewayMessage)}</div>`
        : '';

    return `
        <div class="gateway-box">
            <div class="gateway-head">
                <div class="gateway-title">Gateway Response</div>
                <div class="gateway-pill ${pillClass}">
                    ${escapeHtml(gatewayCode)}
                </div>
            </div>

            <div class="gateway-grid">
                <div class="gateway-item">
                    <span>Order Deposit</span>
                    <b>${escapeHtml(orderNum)}</b>
                </div>

                <div class="gateway-item">
                    <span>Plat Order</span>
                    <b>${escapeHtml(platOrderNum)}</b>
                </div>

                <div class="gateway-item">
                    <span>Method</span>
                    <b>${escapeHtml(method)}</b>
                </div>

                <div class="gateway-item">
                    <span>Channel</span>
                    <b>${escapeHtml(selectedChannel)}</b>
                </div>

                <div class="gateway-item">
                    <span>Nominal Input</span>
                    <b>${rupiah(r.amount)}</b>
                </div>

                <div class="gateway-item">
                    <span>Real Amount</span>
                    <b>${rupiah(realAmount)}</b>
                </div>

                <div class="gateway-item">
                    <span>Fee Gateway</span>
                    <b>${rupiah(payFee)}</b>
                </div>

                <div class="gateway-item">
                    <span>Expired At</span>
                    <b>${escapeHtml(expiredAt)}</b>
                </div>

                ${paidAt !== '-' ? `
                    <div class="gateway-item">
                        <span>Paid At</span>
                        <b>${escapeHtml(paidAt)}</b>
                    </div>
                ` : ''}

                ${payUrl !== '-' ? `
                    <div class="gateway-item is-wide">
                        <span>Pay URL</span>
                        <b>${escapeHtml(payUrl)}</b>
                    </div>
                ` : ''}
            </div>

            ${messageHtml}
        </div>
    `;
}

function invoiceUrl(id) {
    return `/deposit/invoice/${id}`;
}

const rowsEl = document.getElementById('rows');
const statusSel = document.getElementById('status');
const keywordEl = document.getElementById('keyword');

window.toggleGateway = function(id) {
    const card = document.getElementById(`deposit-card-${id}`);
    if (!card) return;

    card.classList.toggle('show-gateway');

    const btn = card.querySelector('[data-gateway-btn]');
    if (btn) {
        btn.textContent = card.classList.contains('show-gateway')
            ? 'Tutup Detail'
            : 'Detail Gateway';
    }
};

async function loadDeposits() {
    rowsEl.innerHTML = `<div class="loading">Loading data deposit...</div>`;

    const params = new URLSearchParams();

    if (statusSel.value) {
        params.set('status', statusSel.value);
    }

    if (keywordEl.value.trim()) {
        params.set('q', keywordEl.value.trim());
    }

const url = params.toString()
    ? `/admin/deposits/data?${params.toString()}`
    : '/admin/deposits/data';

    const res = await api(url);
    const payload = res?.data || {};
    const rows = payload?.data || [];

    const summary = res?.summary || {};

    document.getElementById('sumTotal').textContent = summary.total ?? rows.length;
    document.getElementById('sumPaid').textContent = summary.paid ?? 0;
    document.getElementById('sumUnpaid').textContent = summary.unpaid ?? 0;
    document.getElementById('sumPaidAmount').textContent = rupiah(summary.paid_amount ?? 0);

    if (!rows.length) {
        rowsEl.innerHTML = `<div class="empty">Tidak ada data deposit.</div>`;
        return;
    }

    rowsEl.innerHTML = rows.map((r) => {
        const user = r.user;
        const created = r.created_at ? new Date(r.created_at).toLocaleString('id-ID') : '-';
        const userLine = user ? `${user.name ?? ''} (#${user.id})` : `#${r.user_id}`;
        const phoneLine = user?.phone ? ` • ${user.phone}` : '';

      const actions = (() => {
    const s = statusKey(r.status);
    let html = '';

    html += `<button class="btn-mini btn-detail" type="button" data-gateway-btn onclick="toggleGateway(${r.id})">Detail Gateway</button>`;

    html += `<a class="btn-mini btn-url" href="${invoiceUrl(r.id)}" target="_blank" rel="noopener">Invoice</a>`;

    if (r.pay_url) {
        html += `<a class="btn-mini btn-url" href="${escapeHtml(r.pay_url)}" target="_blank" rel="noopener">Pay URL</a>`;
    }

    if (s !== 'PAID') {
        html += `<button class="btn-mini btn-paid" type="button" onclick="markPaid(${r.id})">Set PAID</button>`;
    }

    if (s !== 'PAID' && s !== 'FAILED') {
        html += `<button class="btn-mini btn-failed" type="button" onclick="markFailed(${r.id})">Set FAILED</button>`;
    }

    return `<div class="actions-list">${html}</div>`;
})();

        const gatewayHtml = gatewayInfoHtml(r);

return `
    <div class="row-card" id="deposit-card-${r.id}">
        <div class="${statusBarClass(r)}"></div>

        <div class="row-body">
            <div class="deposit-main">
                <div class="deposit-left">
                    <div class="deposit-top">
                        <div class="amount">${rupiah(r.amount)}</div>
                        ${statusBadge(r)}
                    </div>

                    <div class="deposit-user">
                        <div class="deposit-avatar">${initialName(user?.name)}</div>
                        <div class="deposit-user-info">
                            <b>${escapeHtml(userLine)}</b>
                            <span>${escapeHtml(user?.phone || 'No phone')}</span>
                        </div>
                    </div>

                    <div class="deposit-info-grid">
                        <div class="deposit-info">
                            <span>Order ID</span>
                            <b>${escapeHtml(r.order_id || '-')}</b>
                        </div>

                        <div class="deposit-info">
                            <span>Method</span>
                            <b>${escapeHtml(r.method || '-')}</b>
                        </div>

                        <div class="deposit-info">
                            <span>Channel</span>
                            <b>${escapeHtml(r.selected_channel || '-')}</b>
                        </div>

                        <div class="deposit-info">
                            <span>Real Amount</span>
                            <b>${rupiah(r.real_amount || r.amount)}</b>
                        </div>

                        <div class="deposit-info">
                            <span>Fee</span>
                            <b>${rupiah(r.pay_fee || 0)}</b>
                        </div>

                        <div class="deposit-info">
                            <span>Expired</span>
                            <b>${escapeHtml(r.expired_at || '-')}</b>
                        </div>
                    </div>
                </div>

                <div class="deposit-right">
                    <div class="deposit-date">${escapeHtml(created)}</div>
                    ${actions}
                </div>
            </div>

            ${gatewayHtml}
        </div>
    </div>
`;
    }).join('');
}

window.markPaid = async function(id) {
    if (!confirm('Tandai deposit ini sebagai PAID dan tambahkan saldo user?')) return;

    try {
        await api(`/admin/deposits/${id}/paid`, { method: 'POST' });
        toast('Deposit berhasil ditandai PAID');
        await loadDeposits();
    } catch (e) {
        toast(e.message, 'err');
    }
};

window.markFailed = async function(id) {
    if (!confirm('Tandai deposit ini sebagai FAILED?')) return;

    try {
        await api(`/admin/deposits/${id}/failed`, { method: 'POST' });
        toast('Deposit berhasil ditandai FAILED');
        await loadDeposits();
    } catch (e) {
        toast(e.message, 'err');
    }
};

document.getElementById('btnLoad').addEventListener('click', () => {
    loadDeposits().catch((e) => toast(e.message, 'err'));
});

statusSel.addEventListener('change', () => {
    loadDeposits().catch((e) => toast(e.message, 'err'));
});

keywordEl.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
        loadDeposits().catch((err) => toast(err.message, 'err'));
    }
});

(function mobileEvents() {
    document.querySelectorAll('#navMenu a').forEach((link) => {
        link.addEventListener('click', () => {
            if (window.matchMedia('(max-width: 860px)').matches) {
                toggleSidebar(false);
            }
        });
    });

    window.addEventListener('resize', () => {
        if (!window.matchMedia('(max-width: 860px)').matches) {
            toggleSidebar(false);
        }
    });

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') toggleSidebar(false);
    });
})();

loadDeposits().catch((e) => toast(e.message, 'err'));
</script>
</body>
</html>