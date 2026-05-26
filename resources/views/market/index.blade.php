@include('partials.anti-inspect')

@php
    $user = auth()->user();

    $portfolioBalance = (int) ($portfolioBalance ?? 0);
    $todayProfit = (int) ($todayProfit ?? 0);

    $totalProduk = 0;
    $produkTermurah = null;
    $estimasiProfit = 0;
    $marketProducts = collect();

    foreach(($categories ?? []) as $cat){
        foreach(($cat->products ?? []) as $product){
            $totalProduk++;
            $estimasiProfit += (int) ($product->daily_profit ?? 0);
            $marketProducts->push([
                'category' => $cat,
                'product' => $product,
            ]);

            if($produkTermurah === null || (int) $product->price < $produkTermurah){
                $produkTermurah = (int) $product->price;
            }
        }
    }

    $produkTermurah = $produkTermurah ?? 0;

    $yieldPercent = $portfolioBalance > 0
        ? round(($todayProfit / max($portfolioBalance, 1)) * 100, 2)
        : 0;

    /*
    |--------------------------------------------------------------------------
    | Dynamic Product Icons
    |--------------------------------------------------------------------------
    | Icon akan dipilih otomatis berdasarkan category id + product id + nama produk.
    | Jadi setiap produk terlihat berbeda, tapi tetap konsisten setiap reload.
    */
    $vrIconPool = [
        'cube' => "<svg viewBox='0 0 24 24' fill='none'>
            <path d='M12 2.7 20 7.1v9.8l-8 4.4-8-4.4V7.1l8-4.4Z' stroke='currentColor' stroke-width='2' stroke-linejoin='round'/>
            <path d='M4.5 7.4 12 11.7l7.5-4.3' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
            <path d='M12 11.7v8.4' stroke='currentColor' stroke-width='2' stroke-linecap='round'/>
        </svg>",

        'chart' => "<svg viewBox='0 0 24 24' fill='none'>
            <path d='M4 19V5' stroke='currentColor' stroke-width='2.2' stroke-linecap='round'/>
            <path d='M9 19v-6' stroke='currentColor' stroke-width='2.2' stroke-linecap='round'/>
            <path d='M14 19V9' stroke='currentColor' stroke-width='2.2' stroke-linecap='round'/>
            <path d='M19 19V3' stroke='currentColor' stroke-width='2.2' stroke-linecap='round'/>
        </svg>",

        'wallet' => "<svg viewBox='0 0 24 24' fill='none'>
            <path d='M3 7.5A2.5 2.5 0 0 1 5.5 5H18a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5.5A2.5 2.5 0 0 1 3 16.5v-9Z' stroke='currentColor' stroke-width='2' stroke-linejoin='round'/>
            <path d='M15 13h5' stroke='currentColor' stroke-width='2' stroke-linecap='round'/>
            <circle cx='15.5' cy='13' r='1' fill='currentColor'/>
        </svg>",

        'shield' => "<svg viewBox='0 0 24 24' fill='none'>
            <path d='M12 3l7 3v5c0 5-3.5 8.5-7 10-3.5-1.5-7-5-7-10V6l7-3Z' stroke='currentColor' stroke-width='2' stroke-linejoin='round'/>
            <path d='m9.2 12 1.8 1.8 3.8-4.1' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
        </svg>",

        'diamond' => "<svg viewBox='0 0 24 24' fill='none'>
            <path d='M7 3h10l4 5-9 13L3 8l4-5Z' stroke='currentColor' stroke-width='2' stroke-linejoin='round'/>
            <path d='M7 3l5 18 5-18' stroke='currentColor' stroke-width='2' stroke-linejoin='round'/>
            <path d='M3 8h18' stroke='currentColor' stroke-width='2' stroke-linecap='round'/>
        </svg>",

        'star' => "<svg viewBox='0 0 24 24' fill='none'>
            <path d='m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.1L12 17.2 6.4 20l1.1-6.1L3 9.6l6.2-.9L12 3Z' stroke='currentColor' stroke-width='2' stroke-linejoin='round'/>
        </svg>",

        'rocket' => "<svg viewBox='0 0 24 24' fill='none'>
            <path d='M14 4c3.5 0 6 2.5 6 6 0 4-3 7-8 10-3-5-4-8-4-11 0-3.5 2.5-5 6-5Z' stroke='currentColor' stroke-width='2' stroke-linejoin='round'/>
            <path d='M10 14 5 19' stroke='currentColor' stroke-width='2' stroke-linecap='round'/>
            <path d='M14.5 9.5h.01' stroke='currentColor' stroke-width='3' stroke-linecap='round'/>
        </svg>",

        'bank' => "<svg viewBox='0 0 24 24' fill='none'>
            <path d='M3 9 12 4l9 5' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
            <path d='M5 10v8' stroke='currentColor' stroke-width='2' stroke-linecap='round'/>
            <path d='M10 10v8' stroke='currentColor' stroke-width='2' stroke-linecap='round'/>
            <path d='M14 10v8' stroke='currentColor' stroke-width='2' stroke-linecap='round'/>
            <path d='M19 10v8' stroke='currentColor' stroke-width='2' stroke-linecap='round'/>
            <path d='M3 20h18' stroke='currentColor' stroke-width='2' stroke-linecap='round'/>
        </svg>",

        'bolt' => "<svg viewBox='0 0 24 24' fill='none'>
            <path d='M13 2 5 13h5l-1 9 8-11h-5l1-9Z' stroke='currentColor' stroke-width='2' stroke-linejoin='round'/>
        </svg>",

        'target' => "<svg viewBox='0 0 24 24' fill='none'>
            <circle cx='12' cy='12' r='8' stroke='currentColor' stroke-width='2'/>
            <circle cx='12' cy='12' r='4' stroke='currentColor' stroke-width='2'/>
            <circle cx='12' cy='12' r='1.4' fill='currentColor'/>
        </svg>",

        'layers' => "<svg viewBox='0 0 24 24' fill='none'>
            <path d='M12 3 3 8l9 5 9-5-9-5Z' stroke='currentColor' stroke-width='2' stroke-linejoin='round'/>
            <path d='m3 12 9 5 9-5' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
            <path d='m3 16 9 5 9-5' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
        </svg>",

        'spark' => "<svg viewBox='0 0 24 24' fill='none'>
            <path d='M13 2 9.8 9.8 2 13l7.8 3.2L13 24l3.2-7.8L24 13l-7.8-3.2L13 2Z' stroke='currentColor' stroke-width='2' stroke-linejoin='round'/>
        </svg>",
    ];
@endphp

@if(!$user)
    <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Pasar Investasi | Velora Finance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,500;1,700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root{
    --vr-bg:#f6f2f8;
    --vr-bg-2:#efe8f7;
    --vr-paper:#ffffff;
    --vr-paper-2:#fbf8ff;

    --vr-text:#2b0b16;
    --vr-text-2:#45162a;
    --vr-soft:#7b6370;
    --vr-muted:#a894a0;

    --vr-border:rgba(43,11,22,.085);
    --vr-border-2:rgba(43,11,22,.14);

    --vr-maroon:#3a0712;
    --vr-maroon-2:#55112a;

    --vr-gold:#f5af2a;
    --vr-gold-2:#f0b43b;
    --vr-gold-3:#ffd46d;

    --vr-purple:#8f57ff;
    --vr-purple-2:#b45cff;
    --vr-pink:#d96bff;

    --vr-green:#20b873;
    --vr-red:#e24a64;

    --vr-gradient-main:linear-gradient(135deg,#f5af2a 0%,#ffd46d 26%,#d96bff 58%,#8f57ff 100%);
    --vr-gradient-gold:linear-gradient(135deg,#ffe08a 0%,#f5af2a 100%);
    --vr-gradient-purple:linear-gradient(135deg,#d96bff 0%,#8f57ff 100%);
    --vr-gradient-dark:linear-gradient(145deg,#8f57ff 0%,#9f55ff 38%,#d96bff 72%,#f5af2a 100%);

    --vr-shadow:0 26px 68px rgba(88,43,145,.16);
    --vr-shadow-soft:0 14px 36px rgba(43,11,22,.075);

    --vr-radius-xl:34px;
    --vr-radius-lg:28px;
    --vr-radius-md:22px;
    --vr-radius-sm:18px;
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
    font-family:"Plus Jakarta Sans", Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    color:var(--vr-text);
    background:
        radial-gradient(680px 360px at 50% -150px, rgba(245,175,42,.23), transparent 64%),
        radial-gradient(520px 340px at 100% 4%, rgba(217,107,255,.18), transparent 62%),
        radial-gradient(520px 330px at -12% 34%, rgba(143,87,255,.13), transparent 58%),
        linear-gradient(180deg,#fff 0%,#f7f2fa 44%,#eee8f6 100%);
    overflow-x:hidden;
    -webkit-tap-highlight-color:transparent;
}

body::before{
    content:"";
    position:fixed;
    inset:0;
    pointer-events:none;
    background:
        linear-gradient(rgba(43,11,22,.026) 1px, transparent 1px),
        linear-gradient(90deg, rgba(43,11,22,.018) 1px, transparent 1px);
    background-size:32px 32px;
    mask-image:linear-gradient(180deg, rgba(0,0,0,.46), transparent 76%);
    -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.46), transparent 76%);
    opacity:.55;
    z-index:0;
}

a{
    color:inherit;
    text-decoration:none;
}

button,
input{
    font-family:inherit;
}

.vr-page{
    width:100%;
    min-height:100vh;
    display:flex;
    justify-content:center;
    padding:14px 10px 0;
    position:relative;
    z-index:1;
}

.vr-phone{
    width:100%;
    max-width:430px;
    min-height:100vh;
    position:relative;
    padding:8px 4px 110px;
}

/* HEADER */
.vr-topbar{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    margin-bottom:16px;
    padding:0 2px;
}

.vr-brand{
    display:flex;
    align-items:center;
    gap:12px;
    min-width:0;
}

.vr-logo{
    width:52px;
    height:52px;
    border-radius:19px;
    display:grid;
    place-items:center;
    overflow:hidden;
    background:#ffffff;
    border:1px solid rgba(43,11,22,.065);
    box-shadow:
        0 16px 34px rgba(88,43,145,.13),
        0 8px 22px rgba(245,175,42,.10),
        inset 0 1px 0 rgba(255,255,255,.72);
}

.vr-logo img{
    width:46px;
    height:46px;
    object-fit:contain;
    display:block;
}

.vr-brand-copy{
    min-width:0;
}

.vr-brand-copy span{
    display:block;
    margin-bottom:6px;
    font-size:10px;
    line-height:1;
    letter-spacing:.18em;
    text-transform:uppercase;
    font-weight:800;
    font-style:italic;
    color:rgba(58,7,18,.58);
}

.vr-brand-copy h1{
    margin:0;
    color:var(--vr-maroon);
    font-size:23px;
    line-height:1;
    font-weight:800;
    letter-spacing:-.052em;
    white-space:nowrap;
}

.vr-top-actions{
    display:flex;
    align-items:center;
    gap:9px;
    flex:0 0 auto;
}

.vr-icon-btn{
    width:42px;
    height:42px;
    border-radius:999px;
    display:grid;
    place-items:center;
    color:#5b2841;
    background:rgba(255,255,255,.84);
    border:1px solid rgba(43,11,22,.08);
    box-shadow:
        0 12px 26px rgba(43,11,22,.065),
        inset 0 1px 0 rgba(255,255,255,.92);
    backdrop-filter:blur(18px);
    -webkit-backdrop-filter:blur(18px);
    position:relative;
    transition:.18s ease;
}

.vr-icon-btn:hover{
    transform:translateY(-1px);
    color:var(--vr-purple);
}

.vr-icon-btn svg{
    width:20px;
    height:20px;
}

.vr-dot{
    position:absolute;
    right:9px;
    top:8px;
    width:8px;
    height:8px;
    border-radius:999px;
    background:var(--vr-gradient-main);
    border:2px solid #fff;
    box-shadow:0 0 0 4px rgba(143,87,255,.12);
}

/* HERO */
.vr-hero{
    position:relative;
    overflow:hidden;
    border-radius:34px;
    background:
        radial-gradient(360px 220px at 92% -12%, rgba(255,212,109,.48), transparent 58%),
        radial-gradient(300px 200px at 2% 8%, rgba(217,107,255,.34), transparent 62%),
        linear-gradient(145deg,#8f57ff 0%,#9455ff 40%,#d96bff 72%,#f5af2a 100%);
    color:#fff;
    border:1px solid rgba(255,255,255,.44);
    box-shadow:
        0 28px 62px rgba(143,87,255,.22),
        0 18px 42px rgba(245,175,42,.10),
        inset 0 1px 0 rgba(255,255,255,.22);
}

.vr-hero::before{
    content:"";
    position:absolute;
    inset:0;
    pointer-events:none;
    background:
        linear-gradient(135deg, rgba(255,255,255,.22), transparent 34%),
        radial-gradient(circle at 82% 26%, rgba(255,255,255,.16), transparent 28%),
        linear-gradient(180deg, transparent 0%, rgba(43,11,22,.08) 100%);
}

.vr-hero::after{
    content:"";
    position:absolute;
    right:-68px;
    bottom:-86px;
    width:240px;
    height:240px;
    border-radius:50%;
    background:linear-gradient(135deg, rgba(255,212,109,.46), rgba(217,107,255,.25));
    filter:blur(18px);
    pointer-events:none;
}

.vr-hero-inner{
    position:relative;
    z-index:1;
    padding:18px;
}

.vr-hero-head{
    display:grid;
    grid-template-columns:minmax(0,1fr) auto;
    gap:14px;
    align-items:start;
}

.vr-hero-label{
    margin:0 0 8px;
    color:rgba(255,255,255,.74);
    font-size:12px;
    font-weight:700;
}

.vr-hero-balance{
    margin:0;
    font-size:31px;
    line-height:1.02;
    font-weight:800;
    letter-spacing:-.075em;
    text-shadow:0 12px 24px rgba(43,11,22,.22);
}

.vr-hero-profit{
    margin-top:12px;
    display:inline-flex;
    align-items:center;
    gap:7px;
    color:#2c1200;
    font-size:11.5px;
    font-weight:800;
    padding:8px 12px;
    border-radius:999px;
    background:linear-gradient(135deg,#ffe08a 0%,#f5af2a 100%);
    border:1px solid rgba(255,255,255,.30);
    box-shadow:
        0 12px 22px rgba(245,175,42,.24),
        inset 0 1px 0 rgba(255,255,255,.38);
}

.vr-hero-profit span{
    color:rgba(44,18,0,.68);
    font-weight:700;
}

.vr-hero-profit svg{
    width:15px;
    height:15px;
}

.vr-score{
    min-width:88px;
    height:42px;
    border-radius:999px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:6px;
    color:#2b0b16;
    background:
        radial-gradient(circle at 24% 0%, rgba(255,255,255,.72), transparent 40%),
        linear-gradient(135deg,#ffd46d 0%,#d96bff 56%,#8f57ff 100%);
    border:1px solid rgba(255,255,255,.40);
    box-shadow:
        0 12px 24px rgba(143,87,255,.18),
        inset 0 1px 0 rgba(255,255,255,.40);
    font-size:12px;
    font-weight:800;
}

.vr-score svg{
    width:15px;
    height:15px;
}

/* HERO UNUSED ACTION/CHART SAFE STYLE */
.vr-chart-card{
    margin-top:16px;
    min-height:118px;
    border-radius:24px;
    padding:12px;
    background:rgba(255,255,255,.10);
    border:1px solid rgba(255,255,255,.14);
    box-shadow:inset 0 1px 0 rgba(255,255,255,.10);
    overflow:hidden;
}

.vr-chart-top{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:8px;
    margin-bottom:8px;
}

.vr-chart-top span{
    color:rgba(255,255,255,.60);
    font-size:10px;
    font-weight:700;
}

.vr-chart-top strong{
    color:#ffd46d;
    font-size:11px;
    font-weight:800;
}

.vr-chart-card svg{
    width:100%;
    height:78px;
    display:block;
    overflow:visible;
}

.vr-chart-grid{
    stroke:rgba(255,255,255,.10);
    stroke-width:1;
}

.vr-chart-area{
    fill:url(#vrHeroArea);
    opacity:.86;
}

.vr-chart-line{
    fill:none;
    stroke:url(#vrHeroStroke);
    stroke-width:3;
    stroke-linecap:round;
    stroke-linejoin:round;
    filter:drop-shadow(0 6px 10px rgba(180,92,255,.22));
}

.vr-hero-actions{
    margin-top:12px;
    display:grid;
    grid-template-columns:repeat(3, 1fr);
    gap:9px;
}

.vr-hero-action{
    min-height:58px;
    border-radius:20px;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    gap:6px;
    padding:8px 7px;
    font-size:10.5px;
    font-weight:800;
    color:#fff;
    background:rgba(255,255,255,.08);
    border:1px solid rgba(255,255,255,.12);
    box-shadow:inset 0 1px 0 rgba(255,255,255,.08);
    transition:.18s ease;
    text-align:center;
}

.vr-hero-action:hover{
    transform:translateY(-1px);
}

.vr-hero-action svg{
    width:20px;
    height:20px;
}

.vr-hero-action.is-deposit{
    color:#2c1200;
    background:var(--vr-gradient-gold);
    box-shadow:0 14px 26px rgba(245,175,42,.22), inset 0 1px 0 rgba(255,255,255,.32);
}

.vr-hero-action.is-withdraw{
    background:var(--vr-gradient-purple);
    box-shadow:0 14px 26px rgba(143,87,255,.24), inset 0 1px 0 rgba(255,255,255,.18);
}

.vr-hero-action.is-portfolio svg{
    color:#ffd46d;
}

/* SUMMARY */
.vr-insights{
    margin-top:14px;
    display:grid;
    grid-template-columns:repeat(3, minmax(0,1fr));
    gap:10px;
}

.vr-insight{
    min-height:82px;
    position:relative;
    overflow:hidden;
    border-radius:22px;
    padding:12px;
    background:
        linear-gradient(180deg,rgba(255,255,255,.96),rgba(255,255,255,.86));
    border:1px solid rgba(43,11,22,.075);
    box-shadow:
        0 14px 32px rgba(43,11,22,.07),
        inset 0 1px 0 rgba(255,255,255,.95);
}

.vr-insight::before{
    content:"";
    position:absolute;
    top:10px;
    right:10px;
    width:10px;
    height:10px;
    border-radius:999px;
    background:linear-gradient(135deg,var(--stat-a),var(--stat-b));
    box-shadow:0 0 0 6px var(--stat-glow);
}

.vr-insight::after{
    content:"";
    position:absolute;
    left:12px;
    right:12px;
    bottom:0;
    height:3px;
    border-radius:999px 999px 0 0;
    background:linear-gradient(90deg,var(--stat-a),transparent 80%);
    opacity:.75;
}

.vr-insight:nth-child(1){
    --stat-a:#f5af2a;
    --stat-b:#ffd46d;
    --stat-glow:rgba(245,175,42,.14);
}

.vr-insight:nth-child(2){
    --stat-a:#d96bff;
    --stat-b:#8f57ff;
    --stat-glow:rgba(143,87,255,.12);
}

.vr-insight:nth-child(3){
    --stat-a:#8f57ff;
    --stat-b:#f5af2a;
    --stat-glow:rgba(180,92,255,.10);
}

.vr-insight-label{
    margin:0;
    padding-right:18px;
    color:var(--vr-soft);
    font-size:9.6px;
    line-height:1.22;
    font-weight:700;
}

.vr-insight-value{
    margin:13px 0 0;
    color:var(--vr-maroon);
    font-size:12.4px;
    line-height:1.14;
    font-weight:800;
    letter-spacing:-.032em;
}

.vr-insight-value span{
    color:var(--vr-purple);
}

/* STRIP */
.vr-market-strip{
    margin-top:14px;
    position:relative;
    overflow:hidden;
    border-radius:28px;
    padding:13px;
    background:
        radial-gradient(160px 90px at 100% 0%,rgba(217,107,255,.12),transparent 62%),
        rgba(255,255,255,.93);
    border:1px solid rgba(43,11,22,.075);
    box-shadow:
        0 14px 34px rgba(43,11,22,.075),
        inset 0 1px 0 rgba(255,255,255,.94);
    display:grid;
    grid-template-columns:44px minmax(0,1fr) auto;
    gap:12px;
    align-items:center;
}

.vr-market-strip::after{
    content:"";
    position:absolute;
    right:-42px;
    bottom:-66px;
    width:150px;
    height:150px;
    border-radius:50%;
    background:rgba(245,175,42,.13);
}

.vr-strip-icon{
    width:44px;
    height:44px;
    border-radius:18px;
    display:grid;
    place-items:center;
    color:#2c1200;
    background:var(--vr-gradient-main);
    box-shadow:
        0 12px 22px rgba(143,87,255,.16),
        inset 0 1px 0 rgba(255,255,255,.25);
    position:relative;
    z-index:1;
}

.vr-strip-icon svg{
    width:22px;
    height:22px;
}

.vr-strip-copy{
    min-width:0;
    position:relative;
    z-index:1;
}

.vr-strip-copy strong{
    display:block;
    color:var(--vr-maroon);
    font-size:13px;
    font-weight:800;
    letter-spacing:-.02em;
}

.vr-strip-copy span{
    display:block;
    margin-top:4px;
    color:var(--vr-soft);
    font-size:10.5px;
    font-weight:600;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.vr-strip-bars{
    position:relative;
    z-index:1;
    height:30px;
    display:flex;
    gap:4px;
    align-items:end;
}

.vr-strip-bars i{
    display:block;
    width:6px;
    border-radius:999px;
    background:var(--vr-gradient-main);
}

.vr-strip-bars i:nth-child(1){ height:12px; }
.vr-strip-bars i:nth-child(2){ height:21px; }
.vr-strip-bars i:nth-child(3){ height:17px; }
.vr-strip-bars i:nth-child(4){ height:28px; }


/* PREMIUM CHART + WATCHLIST */
.vr-chart-line{
    stroke-dasharray:520;
    stroke-dashoffset:520;
    animation:vrLineDraw 1.55s cubic-bezier(.2,.8,.2,1) forwards;
}

.vr-chart-area{
    opacity:0;
    animation:vrAreaIn .9s ease .55s forwards;
}

.vr-chart-dot{
    fill:#ffd46d;
    stroke:#fff;
    stroke-width:3;
    filter:drop-shadow(0 8px 12px rgba(245,175,42,.28));
    animation:vrDotPulse 2.4s ease-in-out infinite;
}

.vr-watch-section{
    margin-top:20px;
}

.vr-watch-head{
    display:flex;
    align-items:flex-end;
    justify-content:space-between;
    gap:12px;
    margin-bottom:12px;
    padding:0 2px;
}

.vr-watch-title h2{
    margin:0;
    color:var(--vr-maroon);
    font-size:18px;
    line-height:1.12;
    font-weight:800;
    letter-spacing:-.042em;
}

.vr-watch-title p{
    margin:5px 0 0;
    color:var(--vr-soft);
    font-size:11px;
    line-height:1.35;
    font-weight:600;
}

.vr-watch-pill{
    min-height:31px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:0 12px;
    border-radius:999px;
    color:#fff;
    background:var(--vr-gradient-purple);
    box-shadow:0 12px 24px rgba(143,87,255,.18);
    font-size:10.5px;
    font-weight:800;
    white-space:nowrap;
}

.vr-watch-list{
    display:grid;
    gap:10px;
}

.vr-watch-item{
    min-height:76px;
    display:grid;
    grid-template-columns:46px minmax(0,1fr) 112px;
    gap:11px;
    align-items:center;
    padding:12px;
    border-radius:24px;
    background:
        radial-gradient(220px 120px at 100% 0%, rgba(217,107,255,.10), transparent 64%),
        linear-gradient(180deg,rgba(255,255,255,.98),rgba(255,255,255,.90));
    border:1px solid rgba(43,11,22,.075);
    box-shadow:var(--vr-shadow-soft), inset 0 1px 0 rgba(255,255,255,.94);
}

.vr-watch-icon{
    width:46px;
    height:46px;
    border-radius:18px;
    display:grid;
    place-items:center;
    color:#fff;
    background:var(--vr-gradient-purple);
    box-shadow:0 12px 22px rgba(143,87,255,.16), inset 0 1px 0 rgba(255,255,255,.24);
    font-size:14px;
    font-weight:900;
}

.vr-watch-info{
    min-width:0;
}

.vr-watch-info strong{
    display:block;
    color:var(--vr-maroon);
    font-size:13.5px;
    line-height:1.12;
    letter-spacing:-.025em;
    font-weight:800;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.vr-watch-info span{
    display:block;
    margin-top:5px;
    color:var(--vr-soft);
    font-size:10.5px;
    font-weight:600;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.vr-watch-side{
    min-width:0;
    text-align:right;
}

.vr-watch-mini{
    width:100%;
    height:34px;
    display:block;
    overflow:visible;
    margin-bottom:5px;
}

.vr-watch-line{
    fill:none;
    stroke:url(#vrWatchStroke);
    stroke-width:2.6;
    stroke-linecap:round;
    stroke-linejoin:round;
    stroke-dasharray:190;
    stroke-dashoffset:190;
    animation:vrMiniDraw 1.35s cubic-bezier(.2,.8,.2,1) forwards;
}

.vr-watch-price{
    display:block;
    color:var(--vr-maroon);
    font-size:12.2px;
    line-height:1;
    font-weight:800;
    letter-spacing:-.02em;
}

.vr-watch-profit{
    display:block;
    margin-top:5px;
    color:#159864;
    font-size:10px;
    line-height:1;
    font-weight:800;
}

.vr-movers{
    margin-top:14px;
    display:grid;
    grid-template-columns:repeat(3, minmax(0,1fr));
    gap:10px;
}

.vr-mover-card{
    min-height:118px;
    border-radius:22px;
    padding:12px;
    background:linear-gradient(180deg,rgba(255,255,255,.98),rgba(255,255,255,.90));
    border:1px solid rgba(43,11,22,.075);
    box-shadow:0 14px 32px rgba(43,11,22,.07), inset 0 1px 0 rgba(255,255,255,.95);
    overflow:hidden;
    position:relative;
}

.vr-mover-card::before{
    content:"";
    position:absolute;
    right:-34px;
    top:-42px;
    width:96px;
    height:96px;
    border-radius:50%;
    background:rgba(143,87,255,.10);
}

.vr-mover-icon{
    width:36px;
    height:36px;
    border-radius:15px;
    display:grid;
    place-items:center;
    color:#fff;
    background:var(--vr-gradient-main);
    box-shadow:0 10px 20px rgba(143,87,255,.14);
    font-size:13px;
    font-weight:900;
    position:relative;
    z-index:1;
}

.vr-mover-name{
    margin:14px 0 0;
    color:var(--vr-soft);
    font-size:11px;
    font-weight:700;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
    position:relative;
    z-index:1;
}

.vr-mover-value{
    margin:5px 0 0;
    color:var(--vr-maroon);
    font-size:12px;
    line-height:1.1;
    font-weight:800;
    position:relative;
    z-index:1;
}

.vr-mover-change{
    margin:6px 0 0;
    color:#159864;
    font-size:10px;
    line-height:1;
    font-weight:800;
    position:relative;
    z-index:1;
}

@keyframes vrLineDraw{
    to{ stroke-dashoffset:0; }
}

@keyframes vrAreaIn{
    to{ opacity:.86; }
}

@keyframes vrMiniDraw{
    to{ stroke-dashoffset:0; }
}

@keyframes vrDotPulse{
    0%,100%{ transform:scale(1); opacity:.92; }
    50%{ transform:scale(1.18); opacity:1; }
}

/* SECTION */
.vr-section{
    margin-top:22px;
}

.vr-section-head{
    display:flex;
    align-items:flex-end;
    justify-content:space-between;
    gap:12px;
    margin-bottom:14px;
    padding:0 2px;
}

.vr-section-title h2{
    margin:0;
    color:var(--vr-maroon);
    font-size:18px;
    line-height:1.12;
    font-weight:800;
    letter-spacing:-.042em;
}

.vr-section-title p{
    margin:6px 0 0;
    color:var(--vr-soft);
    font-size:11px;
    font-weight:600;
    line-height:1.35;
}

.vr-see-all{
    display:inline-flex;
    align-items:center;
    gap:5px;
    color:var(--vr-purple);
    font-size:11.5px;
    font-weight:800;
    white-space:nowrap;
}

.vr-see-all svg{
    width:13px;
    height:13px;
}

/* TABS */
.vr-tabs-wrap{
    width:100%;
    overflow:hidden;
    margin-bottom:14px;
}

.vr-tabs{
    display:flex;
    gap:8px;
    overflow-x:auto;
    overflow-y:hidden;
    padding:2px 2px 7px;
    scrollbar-width:none;
    -webkit-overflow-scrolling:touch;
}

.vr-tabs::-webkit-scrollbar{
    display:none;
}

.vr-tab{
    flex:0 0 auto;
    min-width:92px;
    min-height:43px;
    padding:0 16px;
    border-radius:999px;
    border:1px solid rgba(43,11,22,.075);
    background:rgba(255,255,255,.92);
    color:var(--vr-soft);
    font-size:11px;
    font-weight:800;
    cursor:pointer;
    box-shadow:
        0 10px 22px rgba(43,11,22,.055),
        inset 0 1px 0 rgba(255,255,255,.9);
    transition:.18s ease;
}

.vr-tab:hover{
    transform:translateY(-1px);
    color:var(--vr-maroon);
}

.vr-tab.active{
    color:#2c1200;
    border-color:rgba(255,255,255,.72);
    background:
        radial-gradient(circle at 22% 0%, rgba(255,255,255,.78), transparent 40%),
        linear-gradient(135deg,var(--tab-a),var(--tab-b));
    box-shadow:
        0 16px 30px var(--tab-shadow),
        inset 0 1px 0 rgba(255,255,255,.38);
}

.vr-tab.is-all{
    --tab-a:#ffd46d;
    --tab-b:#d96bff;
    --tab-shadow:rgba(180,92,255,.17);
}

.vr-tab.is-saham{
    --tab-a:#ffe08a;
    --tab-b:#f5af2a;
    --tab-shadow:rgba(245,175,42,.18);
}

.vr-tab.is-pro{
    --tab-a:#d96bff;
    --tab-b:#8f57ff;
    --tab-shadow:rgba(143,87,255,.18);
}

.vr-pane{
    display:none;
}

.vr-pane.active{
    display:block;
}

/* PRODUCT CARDS */
.vr-assets{
    display:flex;
    flex-direction:column;
    gap:12px;
}

.vr-asset{
    position:relative;
    overflow:hidden;
    border-radius:30px;
    background:
        radial-gradient(250px 140px at 100% -10%, var(--asset-glow), transparent 64%),
        linear-gradient(180deg,rgba(255,255,255,.98),rgba(255,255,255,.90));
    border:1px solid rgba(43,11,22,.075);
    box-shadow:
        0 14px 34px rgba(43,11,22,.07),
        inset 0 1px 0 rgba(255,255,255,.94);
    transition:.18s ease;
}

.vr-asset::before{
    content:"";
    position:absolute;
    inset:0;
    pointer-events:none;
    background:
        linear-gradient(135deg, rgba(255,255,255,.82), transparent 30%),
        radial-gradient(circle at 12% 0%, var(--asset-glow-soft), transparent 44%);
    opacity:.86;
}

.vr-asset:hover{
    transform:translateY(-1px);
    border-color:rgba(143,87,255,.16);
    box-shadow:
        0 18px 38px rgba(43,11,22,.095),
        0 0 0 4px rgba(143,87,255,.04);
}

.vr-asset.is-all,
.vr-asset.is-regular{
    --asset-a:#d96bff;
    --asset-b:#8f57ff;
    --asset-glow:rgba(180,92,255,.13);
    --asset-glow-soft:rgba(180,92,255,.08);
}

.vr-asset.is-saham,
.vr-asset.is-daily{
    --asset-a:#ffd46d;
    --asset-b:#f5af2a;
    --asset-glow:rgba(245,175,42,.16);
    --asset-glow-soft:rgba(245,175,42,.10);
}

.vr-asset.is-pro,
.vr-asset.is-premium{
    --asset-a:#d96bff;
    --asset-b:#8f57ff;
    --asset-glow:rgba(143,87,255,.14);
    --asset-glow-soft:rgba(143,87,255,.08);
}

.vr-asset.is-special{
    --asset-a:#f5af2a;
    --asset-b:#d96bff;
    --asset-glow:rgba(245,175,42,.14);
    --asset-glow-soft:rgba(217,107,255,.08);
}

.vr-asset-row{
    position:relative;
    z-index:1;
    width:100%;
    min-height:108px;
    display:grid;
    grid-template-columns:52px minmax(0,1fr) 98px;
    grid-template-areas:
        "icon info price"
        "icon chart price";
    gap:7px 11px;
    align-items:center;
    padding:14px 12px;
    cursor:pointer;
}

.vr-coin{
    grid-area:icon;
    width:50px;
    height:50px;
    border-radius:20px;
    display:grid;
    place-items:center;
    color:#fff;
    background:linear-gradient(135deg,var(--asset-a),var(--asset-b));
    border:1px solid rgba(255,255,255,.62);
    box-shadow:
        0 12px 24px var(--asset-glow),
        inset 0 1px 0 rgba(255,255,255,.25);
}

.vr-coin{
    position:relative;
}

.vr-coin::after{
    content:"";
    position:absolute;
    inset:7px;
    border-radius:15px;
    border:1px solid rgba(255,255,255,.16);
    pointer-events:none;
}

.vr-coin svg{
    width:26px;
    height:26px;
    display:block;
    filter:drop-shadow(0 4px 8px rgba(44,18,0,.18));
    position:relative;
    z-index:1;
}

.vr-asset-info{
    grid-area:info;
    min-width:0;
}

.vr-asset-info h3{
    margin:0;
    color:var(--vr-maroon);
    font-size:14px;
    line-height:1.18;
    font-weight:800;
    letter-spacing:-.026em;
    display:-webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
    overflow:hidden;
}

.vr-asset-info p{
    margin:5px 0 0;
    color:var(--vr-soft);
    font-size:10.5px;
    font-weight:600;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.vr-chart{
    grid-area:chart;
    width:100%;
    max-width:136px;
    height:34px;
    align-self:end;
    overflow:hidden;
}

.vr-chart svg{
    width:100%;
    height:100%;
    display:block;
    overflow:visible;
}

.vr-chart-line-sm{
    fill:none;
    stroke:var(--asset-a);
    stroke-width:2.7;
    stroke-linecap:round;
    stroke-linejoin:round;
    filter:drop-shadow(0 4px 8px var(--asset-glow));
}

.vr-chart-area-sm{
    fill:var(--asset-a);
    opacity:.14;
}

.vr-price{
    grid-area:price;
    text-align:right;
    min-width:92px;
    align-self:center;
    padding:10px 9px;
    border-radius:20px;
}

.vr-price strong{
    display:block;
    color:var(--vr-maroon);
    font-size:12.2px;
    line-height:1.08;
    font-weight:800;
    letter-spacing:-.03em;
    white-space:nowrap;
    transition:.22s ease;
}

.vr-price span{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-height:22px;
    margin-top:8px;
    padding:0 8px;
    border-radius:999px;
    color:#fff;
    background:linear-gradient(135deg,var(--asset-a),var(--asset-b));
    box-shadow:0 8px 18px var(--asset-glow);
    font-size:10px;
    line-height:1;
    font-weight:800;
    white-space:nowrap;
    transition:.22s ease;
}

.vr-price strong.is-up{
    color:var(--vr-green);
    transform:translateY(-1px);
}

.vr-price strong.is-down{
    color:var(--vr-red);
    transform:translateY(1px);
}

.vr-price span.is-up{
    background:#e8fff4 !important;
    color:#17915f !important;
    transform:translateY(-1px);
}

.vr-price span.is-down{
    background:#fff1f3 !important;
    color:#d7495c !important;
    transform:translateY(1px);
}

.vr-asset-detail{
    position:relative;
    z-index:1;
    display:grid;
    grid-template-columns:repeat(2,1fr);
    border-top:1px solid rgba(43,11,22,.075);
    background:rgba(251,248,255,.84);
    max-height:0;
    overflow:hidden;
    transition:max-height .25s ease;
}

.vr-asset.is-open .vr-asset-detail{
    max-height:88px;
}

.vr-detail-item{
    padding:12px 10px;
}

.vr-detail-item + .vr-detail-item{
    border-left:1px solid rgba(43,11,22,.065);
}

.vr-detail-label{
    margin:0 0 6px;
    color:var(--vr-soft);
    font-size:9.4px;
    line-height:1.1;
    font-weight:600;
}

.vr-detail-value{
    margin:0;
    color:var(--vr-maroon);
    font-size:11px;
    line-height:1.16;
    font-weight:800;
    letter-spacing:-.01em;
}

.vr-detail-value.is-accent{
    color:var(--asset-b);
}

.vr-asset-action{
    position:relative;
    z-index:1;
    max-height:0;
    overflow:hidden;
    padding:0 12px;
    background:rgba(251,248,255,.84);
    transition:max-height .25s ease, padding .25s ease;
}

.vr-asset.is-open .vr-asset-action{
    max-height:80px;
    padding:0 12px 12px;
}

.vr-buy-btn{
    width:100%;
    min-height:46px;
    border:0;
    border-radius:18px;
    padding:0 14px;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    cursor:pointer;
    color:#fff;
    background:linear-gradient(135deg,var(--asset-a),var(--asset-b));
    font-size:12.4px;
    font-weight:800;
    box-shadow:0 14px 28px var(--asset-glow);
    transition:.18s ease;
}

.vr-buy-btn:hover{
    transform:translateY(-1px);
    filter:brightness(1.04);
}

.vr-buy-btn svg{
    width:16px;
    height:16px;
}

.vr-buy-btn:disabled{
    opacity:.82;
    cursor:wait;
}

.vr-empty{
    padding:18px 14px;
    border-radius:24px;
    background:#fff;
    border:1px dashed rgba(43,11,22,.18);
    color:var(--vr-soft);
    text-align:center;
    font-size:12.5px;
    font-weight:700;
}


/* TRADING DETAIL CHART - muncul saat produk diklik */
.vr-trade-chart{
    position:relative;
    z-index:1;
    max-height:0;
    overflow:hidden;
    padding:0 12px;
    background:
        radial-gradient(220px 110px at 96% 0%, var(--asset-glow), transparent 62%),
        rgba(251,248,255,.84);
    border-top:1px solid rgba(43,11,22,.075);
    transition:max-height .28s ease, padding .28s ease;
}

.vr-asset.is-open .vr-trade-chart{
    max-height:252px;
    padding:12px 12px 13px;
}

.vr-trade-shell{
    position:relative;
    overflow:hidden;
    border-radius:24px;
    background:
        radial-gradient(240px 140px at 92% 0%, rgba(143,87,255,.10), transparent 62%),
        linear-gradient(180deg,rgba(255,255,255,.98),rgba(255,255,255,.90));
    border:1px solid rgba(43,11,22,.07);
    box-shadow:inset 0 1px 0 rgba(255,255,255,.92), 0 14px 30px rgba(43,11,22,.055);
    padding:12px;
}

.vr-trade-head{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:10px;
    margin-bottom:8px;
}

.vr-trade-title span{
    display:block;
    color:var(--vr-soft);
    font-size:10px;
    line-height:1.1;
    font-weight:700;
}

.vr-trade-title strong{
    display:block;
    margin-top:5px;
    color:var(--vr-maroon);
    font-size:18px;
    line-height:1;
    font-weight:900;
    letter-spacing:-.04em;
}

.vr-trade-change{
    min-height:26px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:0 10px;
    border-radius:999px;
    color:#14905c;
    background:#eafff5;
    font-size:10.5px;
    font-weight:850;
    white-space:nowrap;
}

.vr-trade-tabs{
    display:flex;
    align-items:center;
    gap:7px;
    margin:8px 0 8px;
}

.vr-trade-tab{
    height:27px;
    min-width:38px;
    border:0;
    border-radius:999px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    color:#9b8b98;
    background:#f4eef9;
    font-size:10px;
    font-weight:850;
}

.vr-trade-tab.is-active{
    color:#fff;
    background:linear-gradient(135deg,var(--asset-a),var(--asset-b));
    box-shadow:0 10px 20px var(--asset-glow);
}

.vr-trade-svg-wrap{
    position:relative;
    height:128px;
    overflow:hidden;
}

.vr-trade-svg{
    width:100%;
    height:128px;
    display:block;
    overflow:visible;
}

.vr-trade-grid{
    stroke:rgba(43,11,22,.065);
    stroke-width:1;
}

.vr-trade-area{
    fill:var(--asset-a);
    opacity:0;
}

.vr-asset.is-open .vr-trade-area{
    animation:vrTradeAreaIn .55s ease .22s forwards;
}

.vr-trade-line{
    fill:none;
    stroke:var(--asset-b);
    stroke-width:3.2;
    stroke-linecap:round;
    stroke-linejoin:round;
    stroke-dasharray:640;
    stroke-dashoffset:640;
    filter:drop-shadow(0 7px 12px var(--asset-glow));
}

.vr-asset.is-open .vr-trade-line{
    animation:vrTradeLineDraw 1.1s cubic-bezier(.2,.8,.2,1) forwards;
}

.vr-trade-dot{
    fill:#fff;
    stroke:var(--asset-b);
    stroke-width:3;
    filter:drop-shadow(0 8px 14px var(--asset-glow));
    opacity:0;
}

.vr-asset.is-open .vr-trade-dot{
    animation:vrTradeDot .7s ease .9s forwards, vrTradePulse 2.2s ease-in-out 1.3s infinite;
}

.vr-trade-axis{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    margin-top:6px;
    color:var(--vr-muted);
    font-size:9.5px;
    font-weight:750;
}

@keyframes vrTradeLineDraw{
    to{ stroke-dashoffset:0; }
}

@keyframes vrTradeAreaIn{
    to{ opacity:.18; }
}

@keyframes vrTradeDot{
    to{ opacity:1; }
}

@keyframes vrTradePulse{
    0%,100%{ transform:scale(1); }
    50%{ transform:scale(1.16); }
}

/* FLOAT */
.vr-floating{
    position:fixed;
    left:50%;
    bottom:24px;
    transform:translateX(-50%);
    width:58px;
    height:58px;
    border-radius:999px;
    background:var(--vr-gradient-main);
    border:1px solid rgba(255,255,255,.58);
    color:#2c1200;
    display:grid;
    place-items:center;
    box-shadow:
        0 18px 42px rgba(88,43,145,.20),
        inset 0 1px 0 rgba(255,255,255,.34);
    z-index:20;
    pointer-events:none;
}

.vr-floating svg{
    width:25px;
    height:25px;
}

/* BOTTOM NAV */
.rb-bottom-spacer{
    height:94px !important;
}

.rb-bottom-nav{
    background:rgba(255,255,255,.92) !important;
    border:1px solid rgba(43,11,22,.08) !important;
    box-shadow:
        0 -18px 40px rgba(43,11,22,.10),
        inset 0 1px 0 rgba(255,255,255,.84) !important;
    backdrop-filter:blur(22px) !important;
    -webkit-backdrop-filter:blur(22px) !important;
}

.rb-bottom-nav__item{
    color:#aa8f9f !important;
}

.rb-bottom-nav__item:hover{
    color:#5b2841 !important;
}

.rb-bottom-nav__item.is-active{
    color:#3a0712 !important;
    text-shadow:none !important;
}

.rb-bottom-nav__item.is-active .rb-bottom-nav__icon{
    filter:drop-shadow(0 8px 12px rgba(143,87,255,.18));
}

/* SWEET ALERT */
.vr-swal-popup{
    width:min(calc(100vw - 28px),390px) !important;
    border-radius:28px !important;
    padding:18px 16px 16px !important;
    background:#ffffff !important;
    border:1px solid rgba(43,11,22,.09) !important;
    box-shadow:
        0 28px 70px rgba(43,11,22,.20),
        inset 0 1px 0 rgba(255,255,255,.9) !important;
}

.vr-swal-title{
    margin:4px 0 10px !important;
    padding:0 !important;
    color:var(--vr-maroon) !important;
    font-size:18px !important;
    line-height:1.18 !important;
    font-weight:800 !important;
    letter-spacing:-.04em !important;
}

.vr-swal-html{
    margin:0 !important;
    padding:0 !important;
}

.vr-swal-wrap{
    text-align:center;
}

.vr-swal-icon{
    width:56px;
    height:56px;
    margin:0 auto 12px;
    border-radius:21px;
    display:grid;
    place-items:center;
    color:#2c1200;
    background:var(--vr-gradient-main);
    border:1px solid rgba(255,255,255,.58);
    box-shadow:
        0 14px 28px rgba(143,87,255,.16),
        inset 0 1px 0 rgba(255,255,255,.24);
}

.vr-swal-icon svg{
    width:30px;
    height:30px;
}

.vr-swal-desc{
    margin:0 0 13px;
    color:var(--vr-soft);
    font-size:13px;
    line-height:1.46;
    font-weight:600;
}

.vr-swal-box{
    display:grid;
    gap:8px;
    padding:10px;
    border-radius:20px;
    background:#fbf8ff;
    border:1px solid rgba(43,11,22,.07);
}

.vr-swal-box div{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    padding:8px 9px;
    border-radius:14px;
    background:#ffffff;
    border:1px solid rgba(43,11,22,.05);
}

.vr-swal-box span{
    color:var(--vr-soft);
    font-size:11px;
    font-weight:600;
    white-space:nowrap;
}

.vr-swal-box strong{
    color:var(--vr-maroon);
    font-size:12px;
    font-weight:800;
    text-align:right;
}

.vr-swal-confirm,
.vr-swal-cancel{
    height:44px;
    border:0;
    border-radius:16px;
    padding:0 16px;
    font-size:12.5px;
    font-weight:800;
    cursor:pointer;
    transition:.18s ease;
}

.vr-swal-confirm{
    color:#2c1200;
    background:var(--vr-gradient-gold);
    box-shadow:0 14px 26px rgba(245,175,42,.20);
}

.vr-swal-cancel{
    color:var(--vr-soft);
    background:#fbf8ff;
    border:1px solid rgba(43,11,22,.08);
}

.vr-swal-confirm:hover,
.vr-swal-cancel:hover{
    transform:translateY(-1px);
}

.vr-buy-spinner{
    width:15px;
    height:15px;
    border-radius:999px;
    border:2px solid rgba(255,255,255,.35);
    border-top-color:#fff;
    display:inline-block;
    animation:vrBuySpin .72s linear infinite;
}

@keyframes vrBuySpin{
    to{
        transform:rotate(360deg);
    }
}

/* MOBILE */
@media (max-width:370px){
    .vr-page{
        padding-left:8px;
        padding-right:8px;
    }

    .vr-logo{
        width:45px;
        height:45px;
        border-radius:16px;
    }

    .vr-logo img{
        width:39px;
        height:39px;
    }

    .vr-brand-copy h1{
        font-size:21px;
    }

    .vr-icon-btn{
        width:39px;
        height:39px;
    }

    .vr-hero-inner{
        padding:16px;
    }

    .vr-hero-balance{
        font-size:26px;
    }

    .vr-score{
        min-width:76px;
        height:38px;
        font-size:11px;
    }

    .vr-hero-actions{
        gap:8px;
    }

    .vr-hero-action{
        min-height:54px;
        border-radius:18px;
        font-size:10px;
    }

    .vr-insights{
        gap:8px;
    }

    .vr-insight{
        min-height:76px;
        padding:10px;
        border-radius:18px;
    }

    .vr-insight-label{
        font-size:9.2px;
    }

    .vr-insight-value{
        margin-top:10px;
        font-size:11.4px;
    }

    .vr-asset-row{
        grid-template-columns:46px minmax(0,1fr) 86px;
        gap:6px 8px;
        padding:12px 10px;
        min-height:96px;
    }

    .vr-coin{
        width:44px;
        height:44px;
        border-radius:17px;
    }

    .vr-coin svg{
        width:24px;
        height:24px;
    }

    .vr-asset-info h3{
        font-size:12.4px;
    }

    .vr-asset-info p{
        font-size:9.7px;
    }

    .vr-chart{
        max-width:116px;
        height:31px;
    }

    .vr-price{
        min-width:78px;
        padding:8px 7px;
        border-radius:16px;
    }

    .vr-price strong{
        font-size:10.8px;
    }

    .vr-price span{
        font-size:9px;
        min-height:20px;
        padding:0 7px;
    }

    .vr-watch-item{
        grid-template-columns:42px minmax(0,1fr) 96px;
        padding:11px;
        border-radius:21px;
    }

    .vr-watch-icon{
        width:42px;
        height:42px;
        border-radius:16px;
    }

    .vr-watch-info strong{
        font-size:12.4px;
    }

    .vr-watch-side{
        max-width:96px;
    }

    .vr-movers{
        gap:8px;
    }

    .vr-mover-card{
        min-height:110px;
        padding:10px;
        border-radius:18px;
    }

    .vr-mover-value{
        font-size:10.5px;
    }
}

@media (prefers-reduced-motion:reduce){
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
<main class="vr-page">
    <div class="vr-phone">

        {{-- HEADER --}}
        <header class="vr-topbar">
            <div class="vr-brand">
                <div class="vr-logo">
                    <img src="{{ asset('logo.png') }}" alt="Velora Finance">
                </div>

                <div class="vr-brand-copy">
                    <span>Velora Finance</span>
                    <h1>Pasar Investasi</h1>
                </div>
            </div>

            <div class="vr-top-actions">
                <a href="{{ url('/saldo/rincian') }}" class="vr-icon-btn" aria-label="Notifikasi">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9Z" fill="currentColor"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span class="vr-dot"></span>
                </a>

                <a href="{{ url('/akun') }}" class="vr-icon-btn" aria-label="Profil">
                    <svg viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="8" r="4" fill="currentColor"/>
                        <path d="M4 21a8 8 0 0 1 16 0" fill="currentColor"/>
                    </svg>
                </a>
            </div>
        </header>

        {{-- HERO --}}
        <section class="vr-hero">
            <div class="vr-hero-inner">
                <div class="vr-hero-head">
                    <div>
                        <p class="vr-hero-label">Nilai Portofolio</p>
                        <h2 class="vr-hero-balance">Rp {{ number_format($portfolioBalance, 0, ',', '.') }}</h2>

                        <div class="vr-hero-profit">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M4 15l5-5 4 4 7-8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M15 6h5v5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            +Rp {{ number_format($todayProfit, 0, ',', '.') }}
                            <span>profit aktif</span>
                        </div>
                    </div>

                    <div class="vr-score">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M4 15l5-5 4 4 7-8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        {{ number_format($yieldPercent, 2, ',', '.') }}%
                    </div>
                </div>

                
    

            </div>
        </section>

        {{-- SUMMARY --}}
        <section class="vr-insights" aria-label="Ringkasan pasar">
            <div class="vr-insight">
                <p class="vr-insight-label">Produk Aktif</p>
                <p class="vr-insight-value"><span>{{ number_format($totalProduk, 0, ',', '.') }}</span> Plan</p>
            </div>

            <div class="vr-insight">
                <p class="vr-insight-label">Mulai Dari</p>
                <p class="vr-insight-value">Rp {{ number_format($produkTermurah, 0, ',', '.') }}</p>
            </div>

            <div class="vr-insight">
                <p class="vr-insight-label">Estimasi Harian</p>
                <p class="vr-insight-value"><span>Rp {{ number_format($estimasiProfit, 0, ',', '.') }}</span></p>
            </div>
        </section>

        {{-- PREMIUM WATCHLIST + CHARTS --}}


        {{-- MARKET LIST --}}
        <section class="vr-section">
            <div class="vr-section-head">

                <a href="#market-list" class="vr-see-all">
                    Lihat Semua
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>

            <div class="vr-tabs-wrap" id="market-list">
                <div class="vr-tabs" aria-label="Kategori Produk">
                    @foreach($categories as $i => $cat)
                        @php
                            $tabName = strtolower($cat->name ?? '');

                            if(str_contains($tabName, 'semua')) {
                                $tabClass = 'is-all';
                            } elseif(str_contains($tabName, 'saham')) {
                                $tabClass = 'is-saham';
                            } elseif(str_contains($tabName, 'pro')) {
                                $tabClass = 'is-pro';
                            } else {
                                $tabClass = 'is-all';
                            }
                        @endphp

                        <button
                            type="button"
                            class="vr-tab {{ $tabClass }} {{ $i === 0 ? 'active' : '' }}"
                            data-tab="market-cat-{{ $cat->id }}"
                        >
                            {{ str_ireplace('Rubik', 'Velora', $cat->name) }}
                        </button>
                    @endforeach
                </div>
            </div>

            @foreach($categories as $i => $cat)
                <div class="vr-pane {{ $i === 0 ? 'active' : '' }}" id="market-cat-{{ $cat->id }}">
                    <div class="vr-assets">
                        @forelse($cat->products as $product)
                            @php
                                $catName = strtolower($cat->name ?? '');
                                $productName = strtolower($product->name ?? '');

                                if(str_contains($catName, 'semua')) {
                                    $catClass = 'is-all';
                                    $assetLabel = 'All Asset';
                                } elseif(str_contains($catName, 'saham')) {
                                    $catClass = 'is-saham';
                                    $assetLabel = 'Saham Velora';
                                } elseif(str_contains($catName, 'pro')) {
                                    $catClass = 'is-pro';
                                    $assetLabel = 'Velora Pro';
                                } elseif(str_contains($catName, 'promo') || str_contains($productName, 'promo')) {
                                    $catClass = 'is-special';
                                    $assetLabel = 'Promo Asset';
                                } else {
                                    $catClass = 'is-all';
                                    $assetLabel = 'All Asset';
                                }

                                $invActive = $activeInvestments[$product->id] ?? null;
                                $isOneTimeProduct = in_array((int) $cat->id, [2, 3], true);
                                $shouldLockBuyButton = $isOneTimeProduct && $invActive;

                                $vipKurang   = (int) $user->vip_level < (int) $product->min_vip_level;
                                $saldoKurang = (int) $user->saldo < (int) $product->price;

                                $profitPercent = (int) $product->price > 0
                                    ? round(((int) $product->daily_profit / (int) $product->price) * 100, 1)
                                    : 0;

                                $seed = (int) (
                                    (int) $product->price +
                                    (int) $product->daily_profit +
                                    (int) $product->total_profit +
                                    (int) $product->duration_days
                                );

                                $iconKeys = array_keys($vrIconPool);
                                $iconSeed = abs(crc32(
                                    (string) ($cat->id ?? 0) . '-' .
                                    (string) ($product->id ?? 0) . '-' .
                                    (string) ($product->name ?? '')
                                ));
                                $assetIcon = $vrIconPool[$iconKeys[$iconSeed % count($iconKeys)]];
                            @endphp

                            <article class="vr-asset {{ $catClass }} js-market-asset" data-seed="{{ max($seed, 1) }}" data-price="{{ (int) $product->price }}" data-profit="{{ (int) $product->daily_profit }}" data-percent="{{ $profitPercent }}">
                                <div class="vr-asset-row">
                                    <div class="vr-coin" aria-hidden="true">
                                        {!! $assetIcon !!}
                                    </div>

                                    <div class="vr-asset-info">
                                        <h3>{{ str_ireplace('Rubik', 'Velora', $product->name) }}</h3>
                                        <p>{{ $assetLabel }} • Velora Asset</p>
                                    </div>

                                    <div class="vr-chart">
                                        <svg viewBox="0 0 110 44" preserveAspectRatio="none" aria-hidden="true">
                                            <path class="vr-chart-area-sm" d=""></path>
                                            <path class="vr-chart-line-sm" d=""></path>
                                        </svg>
                                    </div>

                                    <div class="vr-price">
                                        <strong class="js-live-price" data-base-price="{{ (int) $product->price }}">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </strong>

                                        <span class="js-live-percent" data-base-percent="{{ $profitPercent }}">
                                            +{{ $profitPercent }}%
                                        </span>
                                    </div>
                                </div>


                                <div class="vr-trade-chart" aria-label="Grafik trading produk">
                                    <div class="vr-trade-shell">
                                        <div class="vr-trade-head">
                                            <div class="vr-trade-title">
                                                <span>Price movement</span>
                                                <strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                                            </div>

                                            <div class="vr-trade-change">
                                                +{{ $profitPercent }}%
                                            </div>
                                        </div>

                                        <div class="vr-trade-tabs" aria-hidden="true">
                                            <span class="vr-trade-tab">1D</span>
                                            <span class="vr-trade-tab is-active">1W</span>
                                            <span class="vr-trade-tab">1M</span>
                                            <span class="vr-trade-tab">1Y</span>
                                        </div>

                                        <div class="vr-trade-svg-wrap">
                                            <svg class="vr-trade-svg" viewBox="0 0 320 132" preserveAspectRatio="none" aria-hidden="true">
                                                <path class="vr-trade-grid" d="M0 22H320 M0 54H320 M0 86H320 M0 118H320"></path>
                                                <path class="vr-trade-area js-trade-area" d=""></path>
                                                <path class="vr-trade-line js-trade-line" d=""></path>
                                                <circle class="vr-trade-dot js-trade-dot" cx="0" cy="0" r="5"></circle>
                                            </svg>
                                        </div>

                                        <div class="vr-trade-axis">
                                            <span>Mon</span>
                                            <span>Tue</span>
                                            <span>Wed</span>
                                            <span>Thu</span>
                                            <span>Fri</span>
                                            <span>Sat</span>
                                            <span>Sun</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="vr-asset-detail">
                                    <div class="vr-detail-item">
                                        <p class="vr-detail-label">Profit Harian</p>
                                        <p class="vr-detail-value is-accent">Rp {{ number_format($product->daily_profit, 0, ',', '.') }}</p>
                                    </div>

                                    <div class="vr-detail-item">
                                        <p class="vr-detail-label">Total Profit</p>
                                        <p class="vr-detail-value">Rp {{ number_format($product->total_profit, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <div class="vr-asset-action">
                                    @if($shouldLockBuyButton)
                                        <a href="{{ route('investasi.index') }}" class="vr-buy-btn">
                                            Sedang Aktif
                                        </a>
                                    @else
                                        <form
                                            method="POST"
                                            action="{{ url('/product/buy/'.$product->id) }}"
                                            class="js-invest-form"
                                            style="margin:0;"
                                            data-product-name="{{ str_ireplace('Rubik', 'Velora', $product->name) }}"
                                            data-product-price="Rp {{ number_format($product->price, 0, ',', '.') }}"
                                            data-product-profit="Rp {{ number_format($product->daily_profit, 0, ',', '.') }}"
                                            data-product-vip="{{ (int) $product->min_vip_level }}"
                                            data-user-vip="{{ (int) ($user->vip_level ?? 0) }}"
                                            data-product-raw-price="{{ (int) $product->price }}"
                                            data-user-saldo="{{ (int) ($user->saldo ?? 0) }}"
                                            data-vip-kurang="{{ $vipKurang ? '1' : '0' }}"
                                            data-saldo-kurang="{{ $saldoKurang ? '1' : '0' }}"
                                        >
                                            @csrf

                                            <button class="vr-buy-btn" type="submit">
                                                <svg viewBox="0 0 24 24" fill="none">
                                                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                                    <path d="M3 6h18" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M16 10a4 4 0 0 1-8 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>

                                                @if($vipKurang || $saldoKurang)
                                                    Deposit Sekarang
                                                @else
                                                    Investasikan Sekarang
                                                @endif
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </article>
                        @empty
                            <div class="vr-empty">
                                Belum ada produk tersedia di kategori ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </section>

        <div class="rb-bottom-spacer"></div>
    </div>
</main>

<div class="vr-floating" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none">
        <path d="M7 7h10l-3-3" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M17 17H7l3 3" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M7 7l10 10" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
    </svg>
</div>

@include('partials.bottom-nav')

<script>
    // Tabs
    (function(){
        const tabs = Array.from(document.querySelectorAll('.vr-tab'));
        const panes = Array.from(document.querySelectorAll('.vr-pane'));

        if(!tabs.length) return;

        tabs.forEach(tab => {
            tab.addEventListener('click', function(){
                const id = this.dataset.tab;

                tabs.forEach(item => item.classList.toggle('active', item === this));
                panes.forEach(pane => pane.classList.toggle('active', pane.id === id));
            });
        });
    })();

    // Expand card
    (function(){
        const assets = Array.from(document.querySelectorAll('.js-market-asset'));

        assets.forEach(asset => {
            asset.addEventListener('click', function(e){
                if(e.target.closest('a, button, form')) return;

                assets.forEach(item => {
                    if(item !== this) item.classList.remove('is-open');
                });

                this.classList.toggle('is-open');
            });
        });
    })();

    // Mini chart generator
    (function(){
        const assets = Array.from(document.querySelectorAll('.js-market-asset'));

        function seededRandom(seed){
            let value = seed % 2147483647;
            if(value <= 0) value += 2147483646;

            return function(){
                value = value * 16807 % 2147483647;
                return (value - 1) / 2147483646;
            };
        }

        function buildPath(points){
            if(!points.length) return '';

            let d = `M ${points[0].x} ${points[0].y}`;

            for(let i = 1; i < points.length; i++){
                const prev = points[i - 1];
                const curr = points[i];
                const midX = (prev.x + curr.x) / 2;
                d += ` C ${midX} ${prev.y}, ${midX} ${curr.y}, ${curr.x} ${curr.y}`;
            }

            return d;
        }

        assets.forEach((asset, index) => {
            const seed = Number(asset.dataset.seed || index + 1);
            const rand = seededRandom(seed);

            const line = asset.querySelector('.vr-chart-line-sm');
            const area = asset.querySelector('.vr-chart-area-sm');

            if(!line || !area) return;

            const points = [];
            const count = 7;

            for(let i = 0; i < count; i++){
                const x = Math.round((110 / (count - 1)) * i);
                const trend = 30 - (i * 1.5);
                const wave = Math.sin((i + index) * 1.14) * 4.5;
                const noise = (rand() * 12) - 6;
                const y = Math.max(8, Math.min(36, trend + wave + noise));

                points.push({ x, y: Math.round(y) });
            }

            const path = buildPath(points);
            const first = points[0];
            const last = points[points.length - 1];

            line.setAttribute('d', path);
            area.setAttribute('d', `${path} L ${last.x} 44 L ${first.x} 44 Z`);
        });
    })();


    // Detail trading chart muncul saat produk diklik
    (function(){
        const assets = Array.from(document.querySelectorAll('.js-market-asset'));
        if(!assets.length) return;

        function seededRandom(seed){
            let value = seed % 2147483647;
            if(value <= 0) value += 2147483646;

            return function(){
                value = value * 16807 % 2147483647;
                return (value - 1) / 2147483646;
            };
        }

        function buildPath(points){
            if(!points.length) return '';

            let d = `M ${points[0].x} ${points[0].y}`;

            for(let i = 1; i < points.length; i++){
                const prev = points[i - 1];
                const curr = points[i];
                const midX = (prev.x + curr.x) / 2;
                d += ` C ${midX} ${prev.y}, ${midX} ${curr.y}, ${curr.x} ${curr.y}`;
            }

            return d;
        }

        function buildTradeChart(asset, force){
            const line = asset.querySelector('.js-trade-line');
            const area = asset.querySelector('.js-trade-area');
            const dot = asset.querySelector('.js-trade-dot');

            if(!line || !area || !dot) return;

            if(asset.dataset.tradeReady === '1' && !force) return;

            const seed = Number(asset.dataset.seed || 1);
            const price = Math.max(1, Number(asset.dataset.price || 1));
            const profit = Math.max(0, Number(asset.dataset.profit || 0));
            const percent = Math.max(0.1, Number(asset.dataset.percent || 1));
            const rand = seededRandom(seed + Math.round(price / 10) + Math.round(profit));

            const points = [];
            const count = 12;
            const volatility = Math.min(24, Math.max(8, percent * 3.8));

            for(let i = 0; i < count; i++){
                const x = Math.round((320 / (count - 1)) * i);
                const trend = 94 - (i * (4.3 + Math.min(2.8, percent / 2)));
                const wave = Math.sin((i + 1) * 1.18 + seed) * volatility;
                const noise = (rand() * volatility) - (volatility / 2);
                const breakout = i > 8 ? (i - 8) * (2.2 + percent / 3) : 0;
                const y = Math.max(16, Math.min(116, trend + wave + noise - breakout));

                points.push({ x, y: Math.round(y) });
            }

            const last = points[points.length - 1];
            last.y = Math.max(14, last.y - 18);

            const first = points[0];
            const lastPoint = points[points.length - 1];
            const path = buildPath(points);

            line.setAttribute('d', path);
            area.setAttribute('d', `${path} L ${lastPoint.x} 132 L ${first.x} 132 Z`);
            dot.setAttribute('cx', lastPoint.x);
            dot.setAttribute('cy', lastPoint.y);

            asset.dataset.tradeReady = '1';
        }

        // Build semua chart dari awal supaya tidak ada yang kosong.
        assets.forEach(asset => buildTradeChart(asset, true));

        // Saat card dibuka, generate ulang supaya animasinya tetap fresh.
        document.addEventListener('click', function(e){
            const asset = e.target.closest('.js-market-asset');
            if(!asset || e.target.closest('a, button, form')) return;

            setTimeout(function(){
                buildTradeChart(asset, true);

                const line = asset.querySelector('.js-trade-line');
                const area = asset.querySelector('.js-trade-area');
                const dot = asset.querySelector('.js-trade-dot');

                [line, area, dot].forEach(function(el){
                    if(!el) return;
                    el.style.animation = 'none';
                    el.offsetHeight;
                    el.style.animation = '';
                });
            }, 35);
        });
    })();


    // Live animation only
    (function(){
        const priceEls = Array.from(document.querySelectorAll('.js-live-price'));
        const percentEls = Array.from(document.querySelectorAll('.js-live-percent'));

        if(!priceEls.length) return;

        function formatRupiah(value){
            const number = Math.max(0, Math.round(Number(value || 0)));
            return 'Rp ' + number.toLocaleString('id-ID');
        }

        function randomBetween(min, max){
            return Math.random() * (max - min) + min;
        }

        function clearState(el){
            el.classList.remove('is-up', 'is-down', 'is-neutral');
        }

        function setState(el, state){
            clearState(el);
            el.classList.add(state);
        }

        function fluctuatePrice(el){
            const basePrice = Number(el.dataset.basePrice || 0);
            if(!basePrice) return;

            const lastPrice = Number(el.dataset.lastPrice || basePrice);

            const minMove = Math.max(300, basePrice * 0.006);
            const maxMove = Math.max(900, basePrice * 0.022);

            const direction = Math.random() > 0.48 ? 1 : -1;
            const movement = randomBetween(minMove, maxMove) * direction;

            let nextPrice = basePrice + movement;

            const minPrice = basePrice * 0.968;
            const maxPrice = basePrice * 1.032;

            nextPrice = Math.max(minPrice, Math.min(maxPrice, nextPrice));
            nextPrice = Math.round(nextPrice / 100) * 100;

            el.textContent = formatRupiah(nextPrice);
            el.dataset.lastPrice = nextPrice;

            if(nextPrice > lastPrice){
                setState(el, 'is-up');
            }else if(nextPrice < lastPrice){
                setState(el, 'is-down');
            }

            setTimeout(() => clearState(el), 520);
        }

        function fluctuatePercent(el){
            const basePercent = Number(el.dataset.basePercent || 0);
            const direction = Math.random() > 0.45 ? 1 : -1;
            const movement = randomBetween(0.1, 0.7) * direction;

            let nextPercent = basePercent + movement;
            const minPercent = Math.max(0.1, basePercent - 1.2);
            const maxPercent = basePercent + 1.4;

            nextPercent = Math.max(minPercent, Math.min(maxPercent, nextPercent));
            nextPercent = nextPercent.toFixed(1);

            el.textContent = '+' + nextPercent + '%';

            clearState(el);
            el.classList.add(direction > 0 ? 'is-up' : 'is-down');

            setTimeout(() => clearState(el), 520);
        }

        function tick(){
            priceEls.forEach((el, index) => {
                setTimeout(() => fluctuatePrice(el), index * 120);
            });

            percentEls.forEach((el, index) => {
                setTimeout(() => fluctuatePercent(el), index * 120);
            });
        }

        setTimeout(tick, 600);
        setInterval(tick, 2400);
    })();

    // Invest popup
    (function(){
        const forms = Array.from(document.querySelectorAll('.js-invest-form'));
        if(!forms.length) return;

        function rupiah(value){
            const n = Math.max(0, Math.round(Number(value || 0)));
            return 'Rp ' + n.toLocaleString('id-ID');
        }

        function setLoadingButton(form){
            const btn = form.querySelector('button[type="submit"]');
            if(!btn) return;

            btn.disabled = true;
            btn.innerHTML = `<span class="vr-buy-spinner"></span> Memproses`;
        }

        function showDepositRequiredPopup(form){
            const productName = form.dataset.productName || 'Produk Investasi';
            const productPrice = form.dataset.productPrice || 'Rp 0';
            const productVip = Number(form.dataset.productVip || 0);
            const userVip = Number(form.dataset.userVip || 0);
            const rawPrice = Number(form.dataset.productRawPrice || 0);
            const userSaldo = Number(form.dataset.userSaldo || 0);
            const vipKurang = form.dataset.vipKurang === '1';
            const saldoKurang = form.dataset.saldoKurang === '1';

            const kurangSaldo = Math.max(rawPrice - userSaldo, 0);

            let desc = 'Kamu perlu memenuhi syarat produk ini terlebih dahulu sebelum bisa investasi.';
            if(vipKurang && saldoKurang){
                desc = `Kamu membutuhkan minimal VIP ${productVip} dan saldo yang cukup untuk membeli produk ini.`;
            }else if(vipKurang){
                desc = `Kamu harus mencapai minimal VIP ${productVip} untuk membuka produk ini.`;
            }else if(saldoKurang){
                desc = `Saldo utama kamu belum cukup. Tambahkan saldo deposit untuk membeli produk ini.`;
            }

            Swal.fire({
                title: 'Syarat Belum Terpenuhi',
                html: `
                    <div class="vr-swal-wrap">
                        <div class="vr-swal-icon">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M12 9v4" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                                <path d="M12 17h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                                <path d="M10.3 3.7 2.8 17a2 2 0 0 0 1.7 3h15a2 2 0 0 0 1.7-3L13.7 3.7a2 2 0 0 0-3.4 0Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <p class="vr-swal-desc">${desc}</p>

                        <div class="vr-swal-box">
                            <div>
                                <span>Produk</span>
                                <strong>${productName}</strong>
                            </div>
                            <div>
                                <span>Harga Produk</span>
                                <strong>${productPrice}</strong>
                            </div>
                            <div>
                                <span>VIP Kamu</span>
                                <strong>VIP ${userVip}</strong>
                            </div>
                            <div>
                                <span>Minimal VIP</span>
                                <strong>VIP ${productVip}</strong>
                            </div>
                            <div>
                                <span>Saldo Kamu</span>
                                <strong>${rupiah(userSaldo)}</strong>
                            </div>
                            ${
                                saldoKurang
                                ? `<div>
                                    <span>Kekurangan Saldo</span>
                                    <strong>${rupiah(kurangSaldo)}</strong>
                                   </div>`
                                : ''
                            }
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Deposit Sekarang',
                cancelButtonText: 'Nanti Dulu',
                reverseButtons: true,
                background: '#ffffff',
                color: '#3a0707',
                customClass: {
                    popup: 'vr-swal-popup',
                    title: 'vr-swal-title',
                    htmlContainer: 'vr-swal-html',
                    confirmButton: 'vr-swal-confirm',
                    cancelButton: 'vr-swal-cancel'
                },
                buttonsStyling: false
            }).then((result) => {
                if(result.isConfirmed){
                    window.location.href = "{{ url('/deposit') }}";
                }
            });
        }

        function showInvestConfirm(form){
            const productName = form.dataset.productName || 'Produk Investasi';
            const productPrice = form.dataset.productPrice || 'Rp 0';
            const productProfit = form.dataset.productProfit || 'Rp 0';

            Swal.fire({
                title: 'Konfirmasi Investasi',
                html: `
                    <div class="vr-swal-wrap">
                        <div class="vr-swal-icon">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M12 2.7 20 7.1v9.8l-8 4.4-8-4.4V7.1l8-4.4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                <path d="M4.5 7.4 12 11.7l7.5-4.3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 11.7v8.4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>

                        <p class="vr-swal-desc">Pastikan detail investasi sudah sesuai sebelum melanjutkan pembelian produk.</p>

                        <div class="vr-swal-box">
                            <div>
                                <span>Produk</span>
                                <strong>${productName}</strong>
                            </div>
                            <div>
                                <span>Harga</span>
                                <strong>${productPrice}</strong>
                            </div>
                            <div>
                                <span>Profit Harian</span>
                                <strong>${productProfit}</strong>
                            </div>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Investasikan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                background: '#ffffff',
                color: '#3a0707',
                customClass: {
                    popup: 'vr-swal-popup',
                    title: 'vr-swal-title',
                    htmlContainer: 'vr-swal-html',
                    confirmButton: 'vr-swal-confirm',
                    cancelButton: 'vr-swal-cancel'
                },
                buttonsStyling: false
            }).then((result) => {
                if(result.isConfirmed){
                    setLoadingButton(form);
                    form.submit();
                }
            });
        }

        forms.forEach(form => {
            form.addEventListener('submit', function(e){
                e.preventDefault();

                const vipKurang = this.dataset.vipKurang === '1';
                const saldoKurang = this.dataset.saldoKurang === '1';

                if(vipKurang || saldoKurang){
                    showDepositRequiredPopup(this);
                    return;
                }

                showInvestConfirm(this);
            });
        });
    })();
</script>
</body>
</html>