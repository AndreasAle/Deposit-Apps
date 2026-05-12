 @include('partials.anti-inspect')
@php
    $user = auth()->user();

    $portfolioBalance = (int) ($portfolioBalance ?? 0);
    $todayProfit = (int) ($todayProfit ?? 0);

    $totalProduk = 0;
    $produkTermurah = null;
    $estimasiProfit = 0;

    foreach(($categories ?? []) as $cat){
        foreach(($cat->products ?? []) as $product){
            $totalProduk++;
            $estimasiProfit += (int) ($product->daily_profit ?? 0);

            if($produkTermurah === null || (int) $product->price < $produkTermurah){
                $produkTermurah = (int) $product->price;
            }
        }
    }

    $produkTermurah = $produkTermurah ?? 0;
@endphp

@if(!$user)
    <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Pasar | Rubik Company</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root{
            --mk-bg:#030F0F;
            --mk-bg2:#061817;
            --mk-surface:#071f1b;
            --mk-surface2:#0b2723;
            --mk-card:#081a18;
            --mk-card2:#10171f;
            --mk-text:#f7fffb;
            --mk-soft:#dffcf1;
            --mk-muted:#9bb9ad;
            --mk-muted2:#6f9084;
            --mk-border:rgba(255,255,255,.09);

            --mk-green:#00DF82;
            --mk-emerald:#13c58f;
            --mk-cyan:#34d5ff;
            --mk-blue:#5a8cff;
            --mk-violet:#a78bfa;
            --mk-amber:#f6c453;
            --mk-orange:#fb923c;
            --mk-rose:#fb7185;

            --mk-shadow:0 28px 70px rgba(0,0,0,.46);
            --mk-shadow-soft:0 16px 34px rgba(0,0,0,.24);
            --mk-radius:24px;
            --mk-radius-sm:18px;
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
            font-family:Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color:var(--mk-text);
            background:
                radial-gradient(760px 420px at 14% -2%, rgba(0,223,130,.18), transparent 58%),
                radial-gradient(620px 360px at 90% 10%, rgba(90,140,255,.18), transparent 62%),
                radial-gradient(520px 300px at 55% 100%, rgba(246,196,83,.10), transparent 62%),
                linear-gradient(180deg, #071f1a 0%, #030f0f 48%, #020807 100%);
            overflow-x:hidden;
            -webkit-tap-highlight-color:transparent;
        }

        body::before{
            content:"";
            position:fixed;
            inset:0;
            pointer-events:none;
            background:
                linear-gradient(rgba(255,255,255,.022) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.016) 1px, transparent 1px);
            background-size:38px 38px;
            mask-image:linear-gradient(180deg, rgba(0,0,0,.65), transparent 76%);
            -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,.65), transparent 76%);
            opacity:.46;
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

        .mk-page{
            width:100%;
            min-height:100vh;
            display:flex;
            justify-content:center;
            padding:14px 10px 0;
            position:relative;
            z-index:1;
        }

        .mk-phone{
            width:100%;
            max-width:430px;
            min-height:100vh;
            position:relative;
            padding:8px 4px 96px;
        }

        /* =========================
           HEADER
        ========================= */
        .mk-topbar{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            margin-bottom:14px;
            padding:0 2px;
        }

        .mk-brand{
            display:flex;
            align-items:center;
            gap:10px;
            min-width:0;
        }

        .mk-logo-card{
            width:48px;
            height:48px;
            border-radius:14px;
            background:
                radial-gradient(circle at 30% 10%, rgba(255,255,255,.98), rgba(224,255,242,.90));
            border:1px solid rgba(0,223,130,.24);
            box-shadow:
                0 12px 28px rgba(0,0,0,.30),
                0 0 0 1px rgba(255,255,255,.08) inset,
                0 0 28px rgba(0,223,130,.12);
            display:grid;
            place-items:center;
            flex:0 0 auto;
            overflow:hidden;
        }

        .mk-logo-card img{
            width:42px;
            height:42px;
            object-fit:contain;
            display:block;
        }

        .mk-title{
            min-width:0;
        }

        .mk-title span{
            display:block;
            margin-bottom:4px;
            color:rgba(214,255,240,.58);
            font-size:11px;
            line-height:1;
            font-weight:600;
            letter-spacing:.02em;
        }

        .mk-title h1{
            margin:0;
            font-size:23px;
            line-height:1;
            font-weight:850;
            letter-spacing:-.045em;
            color:#ffffff;
            white-space:nowrap;
        }

        .mk-header-actions{
            display:flex;
            align-items:center;
            gap:9px;
            flex:0 0 auto;
        }

        .mk-header-btn{
            width:42px;
            height:42px;
            border-radius:999px;
            border:1px solid rgba(255,255,255,.10);
            background:
                radial-gradient(circle at 32% 18%, rgba(255,255,255,.18), transparent 34%),
                linear-gradient(180deg, rgba(10,42,35,.96), rgba(4,18,16,.96));
            color:#ffffff;
            display:grid;
            place-items:center;
            box-shadow:
                0 13px 28px rgba(0,0,0,.34),
                0 0 0 1px rgba(0,223,130,.06) inset;
            position:relative;
        }

        .mk-header-btn svg{
            width:20px;
            height:20px;
        }

        .mk-notif-dot{
            position:absolute;
            right:9px;
            top:8px;
            width:8px;
            height:8px;
            border-radius:999px;
            background:var(--mk-rose);
            border:2px solid #061714;
            box-shadow:0 0 16px rgba(251,113,133,.55);
        }

        /* =========================
           HERO MARKET
        ========================= */
.mk-hero{
    position:relative;
    overflow:hidden;
    border-radius:24px;
    background:
        radial-gradient(320px 180px at 95% 4%, rgba(90,140,255,.24), transparent 62%),
        radial-gradient(260px 170px at 8% 0%, rgba(0,223,130,.28), transparent 62%),
        radial-gradient(240px 150px at 90% 110%, rgba(246,196,83,.20), transparent 68%),
        linear-gradient(135deg, rgba(236,255,248,.96), rgba(199,255,232,.92) 48%, rgba(185,236,255,.88));
    border:1px solid rgba(255,255,255,.55);
    box-shadow:
        0 20px 44px rgba(0,0,0,.22),
        0 0 0 1px rgba(0,223,130,.14) inset,
        inset 0 1px 0 rgba(255,255,255,.72);
    padding:16px;
}

.mk-hero::before{
    content:"";
    position:absolute;
    inset:0;
    background:
        linear-gradient(145deg, rgba(255,255,255,.48) 0%, rgba(255,255,255,.18) 27%, transparent 28%),
        linear-gradient(180deg, rgba(255,255,255,.22), rgba(255,255,255,0));
    pointer-events:none;
}

.mk-hero-label{
    color:rgba(3,24,20,.62) !important;
}

.mk-hero-balance{
    color:#031713 !important;
    text-shadow:none !important;
}

.mk-hero-profit{
    color:#037e5d !important;
}

.mk-hero-profit span{
    color:rgba(3,24,20,.56) !important;
}

.mk-market-score{
    color:#05221b !important;
    background:rgba(255,255,255,.45) !important;
    border-color:rgba(3,24,20,.10) !important;
    box-shadow:
        0 10px 22px rgba(3,24,20,.10),
        inset 0 1px 0 rgba(255,255,255,.55) !important;
}

.mk-market-score svg{
    color:#047857 !important;
}

.mk-hero-action{
    color:#05221b !important;
    background:rgba(255,255,255,.38) !important;
    border-color:rgba(3,24,20,.08) !important;
    box-shadow:
        0 10px 22px rgba(3,24,20,.08),
        inset 0 1px 0 rgba(255,255,255,.45);
}

.mk-hero-action:hover{
    background:rgba(255,255,255,.54) !important;
}

        .mk-hero-inner{
            position:relative;
            z-index:1;
        }

        .mk-hero-head{
            display:flex;
            justify-content:space-between;
            gap:14px;
            align-items:flex-start;
        }

        .mk-hero-label{
            margin:0 0 8px;
            color:rgba(236,255,248,.70);
            font-size:12px;
            font-weight:600;
            line-height:1.1;
        }

        .mk-hero-balance{
            margin:0;
            color:#ffffff;
            font-size:27px;
            line-height:1.05;
            letter-spacing:-.055em;
            font-weight:850;
            text-shadow:0 10px 24px rgba(0,0,0,.28);
        }

        .mk-hero-profit{
            margin-top:10px;
            display:flex;
            align-items:center;
            gap:6px;
            color:#88ffd0;
            font-size:12px;
            font-weight:750;
        }

        .mk-hero-profit span{
            color:rgba(236,255,248,.54);
            font-weight:500;
        }

        .mk-market-score{
            flex:0 0 auto;
            min-width:78px;
            height:38px;
            border-radius:999px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:6px;
            color:#ffffff;
            background:rgba(255,255,255,.10);
            border:1px solid rgba(255,255,255,.11);
            box-shadow:inset 0 1px 0 rgba(255,255,255,.10);
            font-size:12px;
            font-weight:800;
        }

        .mk-market-score svg{
            width:15px;
            height:15px;
            color:#8fffd3;
        }

.mk-hero-actions{
    margin-top:18px;
    border-radius:0;
    background:transparent;
    border:0;
    padding:0;
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:8px;
}

        .mk-hero-action{
            min-height:58px;
            border-radius:18px;
            border:1px solid rgba(255,255,255,.055);
            background:rgba(255,255,255,.035);
            color:#ffffff;
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            gap:6px;
            font-size:10.5px;
            line-height:1;
            font-weight:650;
            transition:.18s ease;
        }

        .mk-hero-action:hover{
            transform:translateY(-1px);
            background:rgba(255,255,255,.07);
        }

        .mk-hero-action svg{
            width:19px;
            height:19px;
        }

        .mk-hero-action.is-deposit svg{
            color:var(--mk-green);
        }

        .mk-hero-action.is-withdraw svg{
            color:var(--mk-cyan);
        }

        .mk-hero-action.is-analytic svg{
            color:var(--mk-amber);
        }

        /* =========================
           INSIGHT CARDS
        ========================= */
        .mk-insights{
            margin-top:12px;
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:9px;
        }

        .mk-insight{
            min-height:78px;
            border-radius:18px;
            padding:11px 10px;
            background:
                radial-gradient(circle at 80% 0%, var(--insight-glow), transparent 44%),
                linear-gradient(180deg, rgba(18,34,35,.94), rgba(8,21,21,.96));
            border:1px solid rgba(255,255,255,.085);
            box-shadow:
                0 14px 28px rgba(0,0,0,.24),
                inset 0 1px 0 rgba(255,255,255,.055);
        }

        .mk-insight:nth-child(1){
            --insight-glow:rgba(0,223,130,.20);
            --insight-accent:#00DF82;
        }

        .mk-insight:nth-child(2){
            --insight-glow:rgba(90,140,255,.22);
            --insight-accent:#5a8cff;
        }

        .mk-insight:nth-child(3){
            --insight-glow:rgba(246,196,83,.20);
            --insight-accent:#f6c453;
        }

        .mk-insight-label{
            margin:0;
            color:rgba(214,255,240,.52);
            font-size:10px;
            line-height:1.2;
            font-weight:550;
        }

        .mk-insight-value{
            margin:8px 0 0;
            color:#ffffff;
            font-size:13px;
            line-height:1.15;
            letter-spacing:-.02em;
            font-weight:800;
        }

        .mk-insight-value span{
            color:var(--insight-accent);
        }

        /* =========================
           SECTION / TABS
        ========================= */
        .mk-section{
            margin-top:18px;
        }

        .mk-section-head{
            display:flex;
            align-items:flex-end;
            justify-content:space-between;
            gap:12px;
            margin-bottom:12px;
            padding:0 2px;
        }

        .mk-section-title{
            min-width:0;
        }

        .mk-section-title h2{
            margin:0;
            color:#ffffff;
            font-size:17px;
            line-height:1.15;
            letter-spacing:-.03em;
            font-weight:760;
        }

        .mk-section-title p{
            margin:5px 0 0;
            color:rgba(214,255,240,.56);
            font-size:11px;
            font-weight:450;
        }

        .mk-see-all{
            display:inline-flex;
            align-items:center;
            gap:5px;
            color:#8fffd3;
            font-size:11.5px;
            font-weight:750;
            white-space:nowrap;
        }

        .mk-see-all svg{
            width:13px;
            height:13px;
        }

        .mk-tabs-wrap{
            width:100%;
            overflow:hidden;
            position:relative;
            margin-bottom:12px;
        }

.mk-tabs{
    display:flex;
    gap:7px;
    overflow-x:auto;
    overflow-y:hidden;
    padding:1px 2px 3px;
    scrollbar-width:none;
    -webkit-overflow-scrolling:touch;
}

        .mk-tabs::-webkit-scrollbar{
            display:none;
        }

.mk-tab{
    flex:0 0 auto;
    min-width:82px;
    height:34px;
    padding:0 12px;
    border-radius:999px;
    border:1px solid rgba(255,255,255,.08);
    background:rgba(255,255,255,.04);
    color:rgba(236,255,248,.58);
    font-size:11px;
    font-weight:650;
    cursor:pointer;
    box-shadow:0 8px 18px rgba(0,0,0,.12);
    transition:.18s ease;
}

        .mk-tab:hover{
            transform:translateY(-1px);
        }

.mk-tab.active{
    color:#05100d;
    border-color:rgba(255,255,255,.34);
    background:
        radial-gradient(circle at 20% 0%, rgba(255,255,255,.65), transparent 38%),
        linear-gradient(135deg, var(--tab-accent), var(--tab-accent2));
    box-shadow:
        0 14px 28px var(--tab-shadow),
        inset 0 1px 0 rgba(255,255,255,.30);
}

.mk-tab.is-all{
    --tab-accent:#34d5ff;
    --tab-accent2:#5a8cff;
    --tab-shadow:rgba(52,213,255,.18);
}

.mk-tab.is-saham{
    --tab-accent:#fb923c;
    --tab-accent2:#f97316;
    --tab-shadow:rgba(251,146,60,.20);
}

.mk-tab.is-pro{
    --tab-accent:#f6c453;
    --tab-accent2:#facc15;
    --tab-shadow:rgba(246,196,83,.20);
}
        .mk-pane{
            display:none;
        }

        .mk-pane.active{
            display:block;
        }

        /* =========================
           MARKET LIST
        ========================= */
        .mk-assets{
            display:flex;
            flex-direction:column;
            gap:9px;
        }

        .mk-asset{
            position:relative;
            overflow:hidden;
            border-radius:21px;
            background:
                radial-gradient(170px 94px at 88% 8%, var(--asset-glow), transparent 64%),
                linear-gradient(180deg, rgba(13,35,34,.94), rgba(6,20,19,.96));
            border:1px solid rgba(255,255,255,.085);
            box-shadow:
                0 16px 32px rgba(0,0,0,.25),
                0 0 0 1px rgba(255,255,255,.025) inset;
            transition:.18s ease;
        }

        .mk-asset:hover{
            transform:translateY(-1px);
            border-color:rgba(255,255,255,.13);
            box-shadow:
                0 18px 36px rgba(0,0,0,.32),
                0 0 28px var(--asset-glow-soft);
        }

.mk-asset-row{
    width:100%;
    min-height:78px;
    display:grid;
    grid-template-columns:44px minmax(0,1.25fr) 76px minmax(72px,auto);
    align-items:center;
    gap:8px;
    padding:12px 10px;
    cursor:pointer;
}

 /* Semua = Biru */
.mk-asset.is-all,
.mk-asset.is-regular{
    --asset-accent:#34d5ff;
    --asset-accent2:#5a8cff;
    --asset-glow:rgba(52,213,255,.13);
    --asset-glow-soft:rgba(52,213,255,.12);

    --asset-icon-glow:rgba(52,213,255,.28);
    --asset-icon-bg1:#10223a;
    --asset-icon-bg2:#07152a;
    --asset-icon-border:rgba(52,213,255,.28);
}

/* Saham Rubik = Oren */
.mk-asset.is-saham,
.mk-asset.is-daily{
    --asset-accent:#fb923c;
    --asset-accent2:#f97316;
    --asset-glow:rgba(251,146,60,.14);
    --asset-glow-soft:rgba(251,146,60,.12);

    --asset-icon-glow:rgba(251,146,60,.28);
    --asset-icon-bg1:#3a2110;
    --asset-icon-bg2:#241207;
    --asset-icon-border:rgba(251,146,60,.28);
}

/* Rubik Pro = Kuning */
.mk-asset.is-pro,
.mk-asset.is-premium{
    --asset-accent:#f6c453;
    --asset-accent2:#facc15;
    --asset-glow:rgba(246,196,83,.15);
    --asset-glow-soft:rgba(246,196,83,.13);

    --asset-icon-glow:rgba(246,196,83,.30);
    --asset-icon-bg1:#3a2d10;
    --asset-icon-bg2:#241b07;
    --asset-icon-border:rgba(246,196,83,.30);
}

/* Optional promo */
.mk-asset.is-special{
    --asset-accent:#a78bfa;
    --asset-accent2:#fb7185;
    --asset-glow:rgba(167,139,250,.13);
    --asset-glow-soft:rgba(167,139,250,.08);

    --asset-icon-glow:rgba(167,139,250,.28);
    --asset-icon-bg1:#21183a;
    --asset-icon-bg2:#120b25;
    --asset-icon-border:rgba(167,139,250,.28);
}
.mk-asset-info h3{
    margin:0;
    color:#ffffff;
    font-size:13px;
    line-height:1.18;
    letter-spacing:-.02em;
    font-weight:760;

    white-space:normal;
    overflow:hidden;
    text-overflow:clip;

    display:-webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
}
.mk-asset-info p{
    margin:4px 0 0;
    color:rgba(214,255,240,.52);
    font-size:10.3px;
    font-weight:500;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.mk-chart{
    width:76px;
    height:40px;
    justify-self:end;
    overflow:hidden;
    opacity:.98;
}
        .mk-chart svg{
            width:100%;
            height:100%;
            display:block;
            overflow:visible;
        }

        .mk-chart-line{
            fill:none;
            stroke:var(--asset-accent);
            stroke-width:2.6;
            stroke-linecap:round;
            stroke-linejoin:round;
            filter:drop-shadow(0 4px 8px var(--asset-glow-soft));
        }

        .mk-chart-area{
            fill:var(--asset-accent);
            opacity:.17;
        }

        .mk-price{
            text-align:right;
            min-width:0;
        }

        .mk-price strong{
            display:block;
            color:#ffffff;
            font-size:12px;
            line-height:1.1;
            letter-spacing:-.025em;
            font-weight:820;
            white-space:nowrap;
        }

        .mk-price span{
            display:block;
            margin-top:5px;
            color:var(--asset-accent);
            font-size:10.2px;
            line-height:1;
            font-weight:750;
            white-space:nowrap;
        }

        .mk-price strong,
.mk-price span{
    transition:
        color .22s ease,
        transform .22s ease,
        opacity .22s ease;
}

.mk-price strong.is-up,
.mk-price span.is-up{
    color:#00DF82 !important;
    transform:translateY(-1px);
}

.mk-price strong.is-down,
.mk-price span.is-down{
    color:#fb7185 !important;
    transform:translateY(1px);
}

.mk-price strong.is-neutral,
.mk-price span.is-neutral{
    color:#ffffff !important;
}

        .mk-asset-detail{
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:0;
            border-top:1px solid rgba(255,255,255,.07);
            background:rgba(2,10,10,.22);
            max-height:0;
            overflow:hidden;
            transition:max-height .24s ease;
        }

        .mk-asset.is-open .mk-asset-detail{
            max-height:86px;
        }

        .mk-detail-item{
            padding:10px 10px;
        }

        .mk-detail-item + .mk-detail-item{
            border-left:1px solid rgba(255,255,255,.055);
        }

        .mk-detail-label{
            margin:0 0 4px;
            color:rgba(214,255,240,.48);
            font-size:9.5px;
            line-height:1.1;
            font-weight:550;
        }

        .mk-detail-value{
            margin:0;
            color:#ffffff;
            font-size:11.2px;
            line-height:1.15;
            font-weight:760;
            letter-spacing:-.01em;
        }

        .mk-detail-value.is-accent{
            color:var(--asset-accent);
        }

        .mk-asset-action{
            max-height:0;
            overflow:hidden;
            padding:0 12px;
            background:rgba(2,10,10,.20);
            transition:max-height .24s ease, padding .24s ease;
        }

        .mk-asset.is-open .mk-asset-action{
            max-height:72px;
            padding:0 12px 12px;
        }

        .mk-buy-btn{
            width:100%;
            min-height:43px;
            border:0;
            border-radius:16px;
            padding:0 14px;
            display:flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            cursor:pointer;
            color:#06110e;
            background:
                radial-gradient(circle at 30% 0%, rgba(255,255,255,.50), transparent 34%),
                linear-gradient(135deg, var(--asset-accent), var(--asset-accent2));
            font-size:12px;
            font-weight:850;
            box-shadow:
                0 14px 26px var(--asset-glow-soft),
                inset 0 1px 0 rgba(255,255,255,.30);
            transition:.18s ease;
        }

        .mk-buy-btn:hover{
            transform:translateY(-1px);
            filter:brightness(1.04);
        }

        .mk-buy-btn svg{
            width:16px;
            height:16px;
        }

        .mk-buy-btn.is-muted{
            color:rgba(214,255,240,.54);
            background:rgba(255,255,255,.06);
            border:1px solid rgba(255,255,255,.08);
            box-shadow:none;
            cursor:not-allowed;
        }

        .mk-empty{
            padding:18px 14px;
            border-radius:20px;
            background:rgba(9,37,31,.76);
            border:1px dashed rgba(255,255,255,.14);
            color:rgba(214,255,240,.72);
            text-align:center;
            font-size:12.5px;
            font-weight:650;
        }

        /* =========================
           FLOAT CENTER
        ========================= */
        .mk-floating-market{
            position:fixed;
            left:50%;
            bottom:24px;
            transform:translateX(-50%);
            width:58px;
            height:58px;
            border-radius:999px;
            background:
                radial-gradient(circle at 30% 0%, rgba(255,255,255,.20), transparent 34%),
                linear-gradient(180deg, #14211f, #07100f);
            border:1px solid rgba(255,255,255,.12);
            color:#ffffff;
            display:grid;
            place-items:center;
            box-shadow:
                0 18px 42px rgba(0,0,0,.42),
                0 0 28px rgba(0,223,130,.12),
                inset 0 1px 0 rgba(255,255,255,.12);
            z-index:20;
            pointer-events:none;
        }

        .mk-floating-market svg{
            width:25px;
            height:25px;
            color:#f4fffb;
        }

        /* =========================
           BOTTOM NAV OVERRIDE
        ========================= */
        .rb-bottom-spacer{
            height:86px !important;
        }

        .rb-bottom-nav{
            background:
                radial-gradient(240px 110px at 50% 0%, rgba(0,223,130,.10), transparent 65%),
                linear-gradient(180deg, rgba(8,34,29,.96), rgba(3,15,15,.98)) !important;
            border:1px solid rgba(255,255,255,.10) !important;
            border-bottom:0 !important;
            box-shadow:
                0 -18px 42px rgba(0,0,0,.38),
                0 0 0 1px rgba(0,223,130,.06) inset !important;
            backdrop-filter:blur(20px) !important;
            -webkit-backdrop-filter:blur(20px) !important;
        }

        .rb-bottom-nav__item{
            color:rgba(214,255,240,.48) !important;
        }

        .rb-bottom-nav__item:hover{
            color:rgba(214,255,240,.78) !important;
        }

        .rb-bottom-nav__item.is-active{
            color:#00DF82 !important;
            text-shadow:0 0 18px rgba(0,223,130,.25);
        }

        .rb-bottom-nav__item.is-active .rb-bottom-nav__icon{
            filter:drop-shadow(0 0 12px rgba(0,223,130,.30));
        }

        @media (max-width:370px){
            .mk-logo-card{
                width:44px;
                height:44px;
            }

            .mk-logo-card img{
                width:38px;
                height:38px;
            }

            .mk-title h1{
                font-size:21px;
            }

            .mk-hero{
                padding:15px;
            }

            .mk-hero-balance{
                font-size:24px;
            }

            .mk-market-score{
                min-width:70px;
                height:36px;
                font-size:11px;
            }

            .mk-insights{
                gap:7px;
            }

            .mk-insight{
                padding:10px 8px;
            }

            .mk-insight-value{
                font-size:12px;
            }

    .mk-asset-row{
        grid-template-columns:39px minmax(0,1.35fr) 62px minmax(60px,auto);
        gap:6px;
        padding:11px 9px;
    }


    .mk-coin svg{
    width:23px;
    height:23px;
}

    .mk-chart{
        width:62px;
        height:36px;
    }

    .mk-asset-info h3{
        font-size:12.2px;
        line-height:1.16;
    }

    .mk-asset-info p{
        font-size:9.8px;
    }

    .mk-price strong{
        font-size:10.8px;
    }

    .mk-price span{
        font-size:9.2px;
    }
        }


        .mk-coin{
    width:42px;
    height:42px;
    border-radius:16px;
    display:grid;
    place-items:center;
    color:var(--asset-accent);
    background:
        radial-gradient(circle at 30% 15%, var(--asset-icon-glow), transparent 34%),
        linear-gradient(180deg, var(--asset-icon-bg1) 0%, var(--asset-icon-bg2) 100%);
    border:1px solid var(--asset-icon-border);
    box-shadow:
        0 12px 24px rgba(0,0,0,.28),
        0 0 24px var(--asset-glow-soft),
        inset 0 1px 0 rgba(255,255,255,.10);
    overflow:hidden;
    flex:0 0 auto;
}

.mk-coin img{
    display:none !important;
}

.mk-coin svg{
    width:25px;
    height:25px;
    color:var(--asset-accent);
    filter:
        drop-shadow(0 0 8px var(--asset-glow-soft))
        drop-shadow(0 4px 8px rgba(0,0,0,.22));
}

/* =========================
   SWEETALERT INVEST CONFIRM
========================= */
.mk-swal-popup{
    width:min(calc(100vw - 28px), 390px) !important;
    border-radius:24px !important;
    padding:18px 16px 16px !important;
    background:
        radial-gradient(260px 140px at 90% 0%, rgba(0,223,130,.18), transparent 62%),
        linear-gradient(180deg, rgba(8,34,29,.98), rgba(3,15,15,.98)) !important;
    border:1px solid rgba(255,255,255,.10) !important;
    box-shadow:
        0 28px 70px rgba(0,0,0,.48),
        0 0 0 1px rgba(0,223,130,.08) inset !important;
}

.mk-swal-title{
    margin:4px 0 10px !important;
    padding:0 !important;
    color:#ffffff !important;
    font-size:18px !important;
    line-height:1.2 !important;
    font-weight:900 !important;
    letter-spacing:-.035em !important;
}

.mk-swal-html{
    margin:0 !important;
    padding:0 !important;
}

.mk-swal-invest{
    text-align:center;
}

.mk-swal-icon{
    width:54px;
    height:54px;
    margin:0 auto 12px;
    border-radius:18px;
    display:grid;
    place-items:center;
    color:#00DF82;
    background:
        radial-gradient(circle at 30% 15%, rgba(0,223,130,.30), transparent 36%),
        linear-gradient(180deg, #102a24, #071713);
    border:1px solid rgba(0,223,130,.24);
    box-shadow:
        0 14px 28px rgba(0,0,0,.28),
        0 0 24px rgba(0,223,130,.16),
        inset 0 1px 0 rgba(255,255,255,.10);
}

.mk-swal-icon svg{
    width:29px;
    height:29px;
}

.mk-swal-question{
    margin:0 0 13px;
    color:rgba(214,255,240,.76);
    font-size:13px;
    line-height:1.45;
    font-weight:650;
}

.mk-swal-box{
    display:grid;
    gap:8px;
    padding:10px;
    border-radius:18px;
    background:rgba(2,10,10,.34);
    border:1px solid rgba(255,255,255,.08);
}

.mk-swal-box div{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    padding:8px 9px;
    border-radius:13px;
    background:rgba(255,255,255,.04);
}

.mk-swal-box span{
    color:rgba(214,255,240,.54);
    font-size:11px;
    font-weight:650;
    white-space:nowrap;
}

.mk-swal-box strong{
    color:#ffffff;
    font-size:12px;
    font-weight:850;
    text-align:right;
}

.mk-swal-confirm,
.mk-swal-cancel{
    height:44px;
    border:0;
    border-radius:15px;
    padding:0 16px;
    font-size:12.5px;
    font-weight:900;
    cursor:pointer;
    transition:.18s ease;
}

.mk-swal-confirm{
    color:#03110c;
    background:
        radial-gradient(circle at 30% 0%, rgba(255,255,255,.48), transparent 34%),
        linear-gradient(135deg, #00DF82, #5a8cff);
    box-shadow:
        0 14px 28px rgba(0,223,130,.20),
        inset 0 1px 0 rgba(255,255,255,.30);
}

.mk-swal-cancel{
    color:rgba(214,255,240,.72);
    background:rgba(255,255,255,.07);
    border:1px solid rgba(255,255,255,.09);
}

.mk-swal-confirm:hover,
.mk-swal-cancel:hover{
    transform:translateY(-1px);
    filter:brightness(1.04);
}

.mk-buy-btn:disabled{
    opacity:.82;
    cursor:wait;
}

.mk-buy-spinner{
    width:15px;
    height:15px;
    border-radius:999px;
    border:2px solid rgba(3,17,12,.25);
    border-top-color:#03110c;
    display:inline-block;
    animation:mkBuySpin .72s linear infinite;
}

@keyframes mkBuySpin{
    to{
        transform:rotate(360deg);
    }
}

.mk-swal-warning{
    text-align:center;
}

.mk-swal-warning-title{
    margin:0 0 8px;
    color:#ffffff;
    font-size:15px;
    font-weight:950;
    letter-spacing:-.03em;
}

.mk-swal-warning-desc{
    margin:0 0 12px;
    color:rgba(214,255,240,.72);
    font-size:12.5px;
    line-height:1.5;
    font-weight:650;
}

.mk-swal-warning-box{
    display:grid;
    gap:8px;
    padding:10px;
    border-radius:18px;
    background:rgba(2,10,10,.34);
    border:1px solid rgba(255,255,255,.08);
}

.mk-swal-warning-box div{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    padding:8px 9px;
    border-radius:13px;
    background:rgba(255,255,255,.04);
}

.mk-swal-warning-box span{
    color:rgba(214,255,240,.54);
    font-size:11px;
    font-weight:650;
}

.mk-swal-warning-box strong{
    color:#ffffff;
    font-size:12px;
    font-weight:850;
    text-align:right;
}
    </style>
</head>

<body>
<main class="mk-page">
    <div class="mk-phone">

        {{-- HEADER --}}
        <header class="mk-topbar">
            <div class="mk-brand">
                <div class="mk-logo-card">
                    <img src="{{ asset('logo.png') }}" alt="Rubik Company">
                </div>

                <div class="mk-title">
                    <span>Market investasi</span>
                    <h1>Pasar Rubik</h1>
                </div>
            </div>

            <div class="mk-header-actions">
                <a href="{{ url('/saldo/rincian') }}" class="mk-header-btn" aria-label="Notifikasi">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9Z" fill="currentColor"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span class="mk-notif-dot"></span>
                </a>

                <a href="{{ url('/akun') }}" class="mk-header-btn" aria-label="Profil">
                    <svg viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="8" r="4" fill="currentColor"/>
                        <path d="M4 21a8 8 0 0 1 16 0" fill="currentColor"/>
                    </svg>
                </a>
            </div>
        </header>

        {{-- HERO --}}
        <section class="mk-hero">
            <div class="mk-hero-inner">
                <div class="mk-hero-head">
                    <div>
                        <p class="mk-hero-label">Portfolio Balance</p>
                        <h2 class="mk-hero-balance">Rp {{ number_format($portfolioBalance, 0, ',', '.') }}</h2>

                        <div class="mk-hero-profit">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                                <path d="M4 15l5-5 4 4 7-8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M15 6h5v5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            +Rp {{ number_format($todayProfit, 0, ',', '.') }}
                            <span>Profit aktif</span>
                        </div>
                    </div>

                    <div class="mk-market-score">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M4 15l5-5 4 4 7-8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        1.52%
                    </div>
                </div>

                <div class="mk-hero-actions">
                    <a href="{{ url('/deposit') }}" class="mk-hero-action is-deposit">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M12 5v14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                            <path d="M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                        </svg>
                        Deposit
                    </a>

                    <a href="{{ url('/ui/withdrawals') }}" class="mk-hero-action is-withdraw">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M12 4v13" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                            <path d="M7 12l5 5 5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Withdraw
                    </a>

                    <a href="{{ route('investasi.index') }}" class="mk-hero-action is-analytic">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M4 19V5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                            <path d="M8 17V9" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                            <path d="M12 17V7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                            <path d="M16 17v-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                            <path d="M20 17V4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                        </svg>
                        Portfolio
                    </a>
                </div>
            </div>
        </section>

  

        {{-- MARKET LIST --}}
        <section class="mk-section">
            <div class="mk-section-head">
                <div class="mk-section-title">
                    <h2>Daftar Aset Investasi</h2>
                    <p>Pilih plan berdasarkan kategori</p>
                </div>

                <a href="#market-list" class="mk-see-all">
                    Lihat Semua
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>

            <div class="mk-tabs-wrap" id="market-list">
                <div class="mk-tabs" aria-label="Kategori Produk">
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
        class="mk-tab {{ $tabClass }} {{ $i === 0 ? 'active' : '' }}"
        data-tab="market-cat-{{ $cat->id }}"
    >
        {{ $cat->name }}
    </button>
@endforeach
                </div>
            </div>

            @foreach($categories as $i => $cat)
                <div class="mk-pane {{ $i === 0 ? 'active' : '' }}" id="market-cat-{{ $cat->id }}">
                    <div class="mk-assets">
                        @forelse($cat->products as $product)
         @php
    $catName = strtolower($cat->name ?? '');
    $productName = strtolower($product->name ?? '');

    if(str_contains($catName, 'semua')) {
        $catClass = 'is-all';
        $assetLabel = 'All Asset';
        $assetSymbol = 'A';
    } elseif(str_contains($catName, 'saham')) {
        $catClass = 'is-saham';
        $assetLabel = 'Saham Rubik';
        $assetSymbol = 'S';
    } elseif(str_contains($catName, 'pro')) {
        $catClass = 'is-pro';
        $assetLabel = 'Rubik Pro';
        $assetSymbol = 'P';
    } elseif(str_contains($catName, 'promo') || str_contains($productName, 'promo')) {
        $catClass = 'is-special';
        $assetLabel = 'Promo Asset';
        $assetSymbol = 'S';
    } else {
        $catClass = 'is-all';
        $assetLabel = 'All Asset';
        $assetSymbol = 'A';
    }

    $invActive = $activeInvestments[$product->id] ?? null;

    $isBasicProduct = (int) $cat->id === 1;
    $isOneTimeProduct = in_array((int) $cat->id, [2, 3], true);

    $shouldLockBuyButton = $isOneTimeProduct && $invActive;

    $vipKurang   = (int) $user->vip_level < (int) $product->min_vip_level;
    $saldoKurang = (int) $user->saldo < (int) $product->price;

    $profitPercent = (int) $product->price > 0
        ? round(((int) $product->daily_profit / (int) $product->price) * 100, 1)
        : 0;

    $seed = (int) (
        (int) $product->price
        + (int) $product->daily_profit
        + (int) $product->total_profit
        + (int) $product->duration_days
    );
@endphp
                            <article
                                class="mk-asset {{ $catClass }} js-market-asset"
                                data-seed="{{ max($seed, 1) }}"
                            >
                                <div class="mk-asset-row">
<div class="mk-coin" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none">
        <path d="M12 2.7 20 7.1v9.8l-8 4.4-8-4.4V7.1l8-4.4Z"
              stroke="currentColor"
              stroke-width="2"
              stroke-linejoin="round"/>
        <path d="M4.5 7.4 12 11.7l7.5-4.3"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"/>
        <path d="M12 11.7v8.4"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"/>
        <path d="M8.2 5.2 16 9.6"
              stroke="currentColor"
              stroke-width="1.7"
              stroke-linecap="round"
              opacity=".75"/>
    </svg>
</div>

                                    <div class="mk-asset-info">
                                        <h3>{{ $product->name }}</h3>
                                        <p>{{ $assetLabel }} • {{ $product->duration_days }} hari</p>
                                    </div>

                                    <div class="mk-chart">
                                        <svg viewBox="0 0 110 44" preserveAspectRatio="none" aria-hidden="true">
                                            <path class="mk-chart-area" d=""></path>
                                            <path class="mk-chart-line" d=""></path>
                                        </svg>
                                    </div>
<div class="mk-price">
    <strong
        class="js-live-price"
        data-base-price="{{ (int) $product->price }}"
    >
        Rp {{ number_format($product->price, 0, ',', '.') }}
    </strong>

    <span
        class="js-live-percent"
        data-base-percent="{{ $profitPercent }}"
    >
        +{{ $profitPercent }}%
    </span>
</div>
                                </div>

                                <div class="mk-asset-detail">
                                    <div class="mk-detail-item">
                                        <p class="mk-detail-label">Profit Harian</p>
                                        <p class="mk-detail-value is-accent">Rp {{ number_format($product->daily_profit, 0, ',', '.') }}</p>
                                    </div>

                                    <div class="mk-detail-item">
                                        <p class="mk-detail-label">Total Profit</p>
                                        <p class="mk-detail-value">Rp {{ number_format($product->total_profit, 0, ',', '.') }}</p>
                                    </div>

                                    <div class="mk-detail-item">
                                        <p class="mk-detail-label">Durasi</p>
                                        <p class="mk-detail-value">{{ $product->duration_days }} Hari</p>
                                    </div>
                                </div>

                                <div class="mk-asset-action">
@if($shouldLockBuyButton)
    <a href="{{ route('investasi.index') }}" class="mk-buy-btn">
        Sedang Aktif
    </a>
@else
    <form
        method="POST"
        action="{{ url('/product/buy/'.$product->id) }}"
        class="js-invest-form"
        style="margin:0;"
        data-product-name="{{ $product->name }}"
        data-product-price="Rp {{ number_format($product->price, 0, ',', '.') }}"
        data-product-profit="Rp {{ number_format($product->daily_profit, 0, ',', '.') }}"
        data-product-duration="{{ $product->duration_days }} Hari"
        data-product-vip="{{ (int) $product->min_vip_level }}"
        data-user-vip="{{ (int) ($user->vip_level ?? 0) }}"
        data-product-raw-price="{{ (int) $product->price }}"
        data-user-saldo="{{ (int) ($user->saldo ?? 0) }}"
        data-vip-kurang="{{ $vipKurang ? '1' : '0' }}"
        data-saldo-kurang="{{ $saldoKurang ? '1' : '0' }}"
    >
        @csrf

        <button class="mk-buy-btn" type="submit">
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
                            <div class="mk-empty">
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

<div class="mk-floating-market" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none">
        <path d="M7 7h10l-3-3" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M17 17H7l3 3" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M7 7l10 10" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
    </svg>
</div>

@include('partials.bottom-nav')

<script>
    // Tabs kategori pasar
    (function(){
        const tabs = Array.from(document.querySelectorAll('.mk-tab'));
        const panes = Array.from(document.querySelectorAll('.mk-pane'));

        if(!tabs.length) return;

        tabs.forEach(tab => {
            tab.addEventListener('click', function(){
                const id = this.dataset.tab;

                tabs.forEach(item => {
                    item.classList.toggle('active', item === this);
                });

                panes.forEach(pane => {
                    pane.classList.toggle('active', pane.id === id);
                });
            });
        });
    })();

    // Expand produk saat diklik
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

    // Mini chart premium
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

            const line = asset.querySelector('.mk-chart-line');
            const area = asset.querySelector('.mk-chart-area');

            if(!line || !area) return;

            const points = [];
            const count = 7;

            for(let i = 0; i < count; i++){
                const x = Math.round((110 / (count - 1)) * i);
                const trend = 30 - (i * 1.55);
                const wave = Math.sin((i + index) * 1.14) * 4.5;
                const noise = (rand() * 13) - 6.5;
                const y = Math.max(8, Math.min(36, trend + wave + noise));

                points.push({
                    x: x,
                    y: Math.round(y)
                });
            }

            const path = buildPath(points);
            const first = points[0];
            const last = points[points.length - 1];

            line.setAttribute('d', path);
            area.setAttribute('d', `${path} L ${last.x} 44 L ${first.x} 44 Z`);
        });
    })();
</script>

<script>
    // Animasi harga market - visual only, tidak mengubah database
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

            /*
                Range fluktuasi:
                - harga kecil: minimal gerak Rp 300 - Rp 900
                - harga besar: sekitar 0.6% - 2.4%
            */
            const minMove = Math.max(300, basePrice * 0.006);
            const maxMove = Math.max(900, basePrice * 0.024);

            const direction = Math.random() > 0.48 ? 1 : -1;
            const movement = randomBetween(minMove, maxMove) * direction;

            let nextPrice = basePrice + movement;

            // Batas aman supaya tidak terlalu liar: maksimal ±3.2% dari harga asli
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
            }else{
                setState(el, 'is-neutral');
            }

            setTimeout(() => {
                clearState(el);
            }, 520);
        }

        function fluctuatePercent(el){
            const basePercent = Number(el.dataset.basePercent || 0);
            if(!basePercent && basePercent !== 0) return;

            const direction = Math.random() > 0.45 ? 1 : -1;
            const movement = randomBetween(0.1, 0.8) * direction;

            let nextPercent = basePercent + movement;

            // Batas aman persentase supaya tetap masuk akal
            const minPercent = Math.max(0.1, basePercent - 1.2);
            const maxPercent = basePercent + 1.4;

            nextPercent = Math.max(minPercent, Math.min(maxPercent, nextPercent));
            nextPercent = nextPercent.toFixed(1);

            el.textContent = '+' + nextPercent + '%';

            clearState(el);
            el.classList.add(direction > 0 ? 'is-up' : 'is-down');

            setTimeout(() => {
                clearState(el);
            }, 520);
        }

        function tick(){
            priceEls.forEach((el, index) => {
                setTimeout(() => {
                    fluctuatePrice(el);
                }, index * 120);
            });

            percentEls.forEach((el, index) => {
                setTimeout(() => {
                    fluctuatePercent(el);
                }, index * 120);
            });
        }

        // Jalankan pertama setelah halaman siap
        setTimeout(tick, 600);

        // Ulang setiap 2.4 detik
        setInterval(tick, 2400);
    })();
</script>

<script>
    // Konfirmasi investasi + popup syarat deposit/VIP
    (function(){
        const forms = Array.from(document.querySelectorAll('.js-invest-form'));

        if(!forms.length) return;

        function rupiah(value){
            const n = Math.max(0, Math.round(Number(value || 0)));
            return 'Rp ' + n.toLocaleString('id-ID');
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

            let reasonTitle = 'Syarat Investasi Belum Terpenuhi';
            let reasonDesc = 'Kamu perlu memenuhi syarat produk ini terlebih dahulu sebelum bisa investasi.';

            if(vipKurang && saldoKurang){
                reasonDesc = `Kamu membutuhkan minimal VIP ${productVip} dan saldo yang cukup untuk membeli produk ini.`;
            }else if(vipKurang){
                reasonDesc = `Kamu harus mencapai minimal VIP ${productVip} untuk membuka produk ini. Lakukan deposit agar level VIP kamu meningkat otomatis.`;
            }else if(saldoKurang){
                reasonDesc = `Saldo utama kamu belum cukup. Tambahkan saldo deposit untuk membeli produk ini.`;
            }

            Swal.fire({
                title: reasonTitle,
                html: `
                    <div class="mk-swal-warning">
                        <div class="mk-swal-icon">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M12 9v4" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                                <path d="M12 17h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                                <path d="M10.3 3.7 2.8 17a2 2 0 0 0 1.7 3h15a2 2 0 0 0 1.7-3L13.7 3.7a2 2 0 0 0-3.4 0Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <p class="mk-swal-warning-title">${productName}</p>
                        <p class="mk-swal-warning-desc">${reasonDesc}</p>

                        <div class="mk-swal-warning-box">
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
                background: '#061817',
                color: '#f7fffb',
                customClass: {
                    popup: 'mk-swal-popup',
                    title: 'mk-swal-title',
                    htmlContainer: 'mk-swal-html',
                    confirmButton: 'mk-swal-confirm',
                    cancelButton: 'mk-swal-cancel'
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
            const productDuration = form.dataset.productDuration || '-';

            Swal.fire({
                title: 'Konfirmasi Investasi',
                html: `
                    <div class="mk-swal-invest">
                        <div class="mk-swal-icon">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M12 2.7 20 7.1v9.8l-8 4.4-8-4.4V7.1l8-4.4Z"
                                      stroke="currentColor"
                                      stroke-width="2"
                                      stroke-linejoin="round"/>
                                <path d="M4.5 7.4 12 11.7l7.5-4.3"
                                      stroke="currentColor"
                                      stroke-width="2"
                                      stroke-linecap="round"
                                      stroke-linejoin="round"/>
                                <path d="M12 11.7v8.4"
                                      stroke="currentColor"
                                      stroke-width="2"
                                      stroke-linecap="round"/>
                            </svg>
                        </div>

                        <p class="mk-swal-question">
                            Yakin ingin membeli produk ini?
                        </p>

                        <div class="mk-swal-box">
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
                            <div>
                                <span>Durasi</span>
                                <strong>${productDuration}</strong>
                            </div>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Ya, Investasikan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                background: '#061817',
                color: '#f7fffb',
                customClass: {
                    popup: 'mk-swal-popup',
                    title: 'mk-swal-title',
                    htmlContainer: 'mk-swal-html',
                    confirmButton: 'mk-swal-confirm',
                    cancelButton: 'mk-swal-cancel'
                },
                buttonsStyling: false,
                allowOutsideClick: true,
                allowEscapeKey: true
            }).then((result) => {
                if(result.isConfirmed){
                    const button = form.querySelector('.mk-buy-btn');

                    if(button){
                        button.disabled = true;
                        button.innerHTML = `
                            <span class="mk-buy-spinner"></span>
                            Memproses Investasi...
                        `;
                    }

                    form.submit();
                }
            });
        }

        forms.forEach(form => {
            form.addEventListener('submit', function(e){
                e.preventDefault();
                e.stopPropagation();

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