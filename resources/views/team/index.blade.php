 @include('partials.anti-inspect')
@php
  $user = auth()->user();

  function rbInitials($name){
    $name = trim((string) $name);
    if($name === '') return 'U';

    $parts = preg_split('/\s+/', $name);
    $first = mb_substr($parts[0] ?? 'U', 0, 1);
    $second = mb_substr($parts[1] ?? '', 0, 1);

    return mb_strtoupper($first.$second);
  }

  function rbTimeLabel($date){
    if(!$date) return '-';
    return $date->diffForHumans();
  }
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Forum | Velora Finance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,500;1,700&display=swap" rel="stylesheet">

  
  <style>
    :root{
      --vl-bg:#f7f5fb;
      --vl-paper:#ffffff;
      --vl-paper2:#fbf8ff;
      --vl-text:#26101a;
      --vl-soft:#4b3340;
      --vl-muted:#8d7d89;
      --vl-muted2:#b6aab6;
      --vl-line:rgba(38,16,26,.075);
      --vl-purple:#8f57ff;
      --vl-purple2:#6d35e8;
      --vl-violet:#d96bff;
      --vl-gold:#f5af2a;
      --vl-gold2:#ffd46d;
      --vl-danger:#e84e68;
      --vl-good:#20b873;
      --vl-gradient:linear-gradient(135deg,#ffd46d 0%,#f5af2a 23%,#d96bff 58%,#8f57ff 100%);
      --vl-purple-gradient:linear-gradient(135deg,#d96bff 0%,#8f57ff 52%,#6d35e8 100%);
      --vl-shadow:0 18px 44px rgba(60,26,94,.10);
      --vl-shadow-soft:0 10px 28px rgba(38,16,26,.065);
    }

    *{box-sizing:border-box}
    html,body{min-height:100%;scroll-behavior:smooth}
    body{
      margin:0;
      font-family:"Plus Jakarta Sans", Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      color:var(--vl-text);
      background:
        radial-gradient(520px 300px at 88% -80px, rgba(217,107,255,.16), transparent 62%),
        radial-gradient(390px 240px at 0% 3%, rgba(245,175,42,.12), transparent 60%),
        linear-gradient(180deg,#ffffff 0%,#f7f5fb 42%,#eee9f5 100%);
      overflow-x:hidden;
      -webkit-tap-highlight-color:transparent;
    }
    a{color:inherit;text-decoration:none}
    button,input,textarea{font-family:inherit}
    body.rb-compose-lock{overflow:hidden}

    .rb-page{width:100%;min-height:100vh;display:flex;justify-content:center;padding:10px 10px 0;position:relative;z-index:1}
    .rb-phone{width:100%;max-width:430px;min-height:100vh;position:relative;padding:0 4px 112px}

    /* TOPBAR - simple like reference */
    .rb-topbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:13px;padding:2px 0 0}
    .rb-brand{display:flex;align-items:center;gap:10px;min-width:0}
    .rb-logo-card{width:38px;height:38px;border-radius:999px;background:#fff;border:1px solid rgba(38,16,26,.06);box-shadow:0 10px 22px rgba(38,16,26,.06);display:grid;place-items:center;overflow:hidden;flex:0 0 auto}
    .rb-logo-card img{width:30px;height:30px;object-fit:contain;display:block}
    .rb-title-wrap{min-width:0}
    .rb-title-wrap h1{margin:0;font-size:17px;line-height:1.05;font-weight:900;letter-spacing:-.035em;color:#2b1c25;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:240px}
    .rb-title-wrap p{margin:4px 0 0;color:var(--vl-muted);font-size:11px;line-height:1.1;font-weight:650;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:235px}
    .rb-header-actions{display:flex;align-items:center;gap:8px;flex:0 0 auto}
    .rb-header-btn{width:39px;height:39px;border-radius:999px;border:1px solid rgba(38,16,26,.075);background:rgba(255,255,255,.86);color:#4d3947;display:grid;place-items:center;box-shadow:0 10px 22px rgba(38,16,26,.055);position:relative;cursor:pointer;transition:.18s ease;backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px)}
    .rb-header-btn:hover{transform:translateY(-1px);color:var(--vl-purple)}
    .rb-header-btn svg{width:19px;height:19px;display:block}.rb-notif-dot{position:absolute;right:9px;top:8px;width:7px;height:7px;border-radius:999px;background:var(--vl-purple);border:2px solid #fff}

    /* AVATAR */
    .rb-avatar{width:40px;height:40px;border-radius:999px;display:grid;place-items:center;flex:0 0 auto;color:#fff;font-size:12px;font-weight:900;letter-spacing:-.03em;background:var(--vl-purple-gradient);box-shadow:0 10px 22px rgba(143,87,255,.17);overflow:hidden}
    .rb-avatar img{width:100%;height:100%;object-fit:cover;display:block}

    /* AI ASSISTANT STYLE HERO */
    .rb-forum-hero{position:relative;overflow:hidden;margin-bottom:11px;border-radius:20px;color:#fff;background:
      radial-gradient(260px 120px at 96% 0%, rgba(255,255,255,.30), transparent 54%),
      radial-gradient(220px 130px at 0% 100%, rgba(255,212,109,.20), transparent 52%),
      linear-gradient(135deg,#efe4ff 0%,#d7bcff 28%,#a879ff 70%,#8f57ff 100%);
      border:1px solid rgba(255,255,255,.55);box-shadow:0 18px 42px rgba(143,87,255,.18)}
    .rb-forum-hero::before{content:"✦";position:absolute;right:22px;top:18px;color:rgba(255,255,255,.9);font-size:18px;animation:rbSpark 2.6s ease-in-out infinite}
    .rb-forum-hero::after{content:"✦";position:absolute;right:8px;top:42px;color:rgba(255,255,255,.78);font-size:11px;animation:rbSpark 2.2s ease-in-out infinite .4s}
    .rb-forum-hero-inner{position:relative;z-index:1;display:grid;grid-template-columns:minmax(0,1fr) auto;gap:12px;align-items:center;padding:15px 16px 16px}
    .rb-forum-kicker{display:inline-flex;align-items:center;gap:7px;color:#fff;font-size:11px;font-weight:900;letter-spacing:-.01em;background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.22);min-height:27px;padding:0 10px;border-radius:999px;backdrop-filter:blur(10px)}
    .rb-forum-kicker::before{content:"✦";width:22px;height:22px;border-radius:999px;background:rgba(255,255,255,.22);display:grid;place-items:center;color:#fff;font-size:12px}
    .rb-forum-hero h2{margin:10px 0 0;color:#2f1c41;font-size:19px;line-height:1.14;font-weight:900;letter-spacing:-.045em;max-width:255px}
    .rb-forum-hero p{margin:6px 0 0;color:rgba(47,28,65,.68);font-size:11.2px;line-height:1.48;font-weight:650;max-width:255px}
    .rb-forum-stat{width:70px;height:70px;border-radius:22px;background:rgba(255,255,255,.24);border:1px solid rgba(255,255,255,.32);display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;box-shadow:inset 0 1px 0 rgba(255,255,255,.22)}
    .rb-forum-stat strong{display:block;color:#fff;font-size:19px;line-height:1;font-weight:950;letter-spacing:-.04em;text-shadow:0 8px 18px rgba(82,36,150,.22)}
    .rb-forum-stat span{display:block;margin-top:5px;color:rgba(255,255,255,.82);font-size:8.5px;line-height:1.15;font-weight:800}

    /* TABS */
    .rb-forum-tabs{display:flex;gap:8px;overflow:auto;padding:0 0 10px;margin:2px 0 4px;scrollbar-width:none}.rb-forum-tabs::-webkit-scrollbar{display:none}
    .rb-tab{height:31px;min-width:72px;padding:0 14px;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;font-size:10.8px;font-weight:800;color:#9b8a98;background:#fff;border:1px solid rgba(38,16,26,.075);box-shadow:0 8px 18px rgba(38,16,26,.04);white-space:nowrap}.rb-tab.is-active{color:#7b39ea;border-color:rgba(143,87,255,.28);background:#fff;box-shadow:0 0 0 4px rgba(143,87,255,.055)}

    /* COMPOSER */
    .rb-composer-trigger{margin-bottom:12px}.rb-composer-open{width:100%;min-height:56px;border:0;border-radius:20px;padding:9px 10px;cursor:pointer;display:grid;grid-template-columns:auto minmax(0,1fr) auto;align-items:center;gap:10px;text-align:left;color:var(--vl-text);background:rgba(255,255,255,.92);box-shadow:var(--vl-shadow-soft);border:1px solid rgba(38,16,26,.065);transition:.18s ease}.rb-composer-open:hover{transform:translateY(-1px)}
    .rb-composer-placeholder{min-width:0;color:#7c6e79;font-size:12px;font-weight:750;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.rb-composer-plus{width:39px;height:39px;border-radius:14px;display:grid;place-items:center;color:#fff;background:var(--vl-purple-gradient);box-shadow:0 13px 25px rgba(143,87,255,.18)}.rb-composer-plus svg{width:19px;height:19px}

    /* FEED cards as meeting rooms */
    .rb-feed{display:grid;grid-template-columns:1fr;gap:12px}.rb-feed-card{position:relative;overflow:hidden;border-radius:22px;background:
      radial-gradient(220px 120px at 92% 0%, rgba(217,107,255,.10), transparent 60%),
      linear-gradient(180deg,rgba(255,255,255,.98),rgba(255,255,255,.92));
      border:1px solid rgba(38,16,26,.065);box-shadow:var(--vl-shadow-soft);padding:13px;transition:.18s ease}.rb-feed-card:nth-child(odd){background:radial-gradient(220px 120px at 92% 0%, rgba(143,87,255,.11), transparent 60%),linear-gradient(180deg,#f8f1ff,#fff 70%)}.rb-feed-card:hover{transform:translateY(-1px);box-shadow:0 16px 34px rgba(38,16,26,.085)}
    .rb-post-head{display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:9px}.rb-post-user{display:flex;align-items:center;gap:9px;min-width:0}.rb-post-name{margin:0;color:#2e2230;font-size:12px;font-weight:850;line-height:1.14;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:230px}.rb-post-time{margin:4px 0 0;color:#8d7d89;font-size:10.5px;font-weight:700}.rb-delete-btn{width:34px;height:34px;border-radius:999px;border:0;background:#fff;color:#9a8b99;display:grid;place-items:center;cursor:pointer;box-shadow:0 8px 18px rgba(38,16,26,.06);transition:.18s ease}.rb-delete-btn:hover{color:var(--vl-danger);background:#fff1f4}.rb-delete-btn svg{width:16px;height:16px}
    .rb-post-content{margin:8px 0 0;color:#271c25;font-size:18px;line-height:1.25;font-weight:900;letter-spacing:-.045em;white-space:pre-wrap}.rb-feed-card .rb-post-content:empty{display:none}

    /* MEDIA - portrait layout */
    .rb-media-grid{
      display:grid;
      gap:9px;
      margin-top:12px;
      align-items:start;
    }

    .rb-media-grid.is-one{
      grid-template-columns:1fr;
    }

    .rb-media-grid.is-two,
    .rb-media-grid.is-many{
      grid-template-columns:repeat(2, minmax(0, 1fr));
    }

    .rb-media-grid a{
      display:block;
      min-width:0;
      border-radius:20px;
      overflow:hidden;
    }

    .rb-media-img{
      width:100%;
      aspect-ratio:3 / 4;
      height:auto;
      border-radius:20px;
      object-fit:cover;
      object-position:center center;
      display:block;
      border:1px solid rgba(38,16,26,.08);
      background:#ffffff;
      box-shadow:0 10px 22px rgba(38,16,26,.08);
    }

    .rb-media-grid.is-one{
      display:flex;
      justify-content:flex-start;
    }

    .rb-media-grid.is-one a{
      width:auto;
      max-width:110px;
    }

    .rb-media-grid.is-one .rb-media-img{
      width:110px;
      height:140px;
      aspect-ratio:auto;
      max-height:140px;
      object-fit:cover;
    }

    .rb-media-grid.is-two .rb-media-img,
    .rb-media-grid.is-many .rb-media-img{
      aspect-ratio:3 / 4;
      max-height:none;
    }

    .rb-file-list{display:grid;gap:8px;margin-top:12px}.rb-file-card{min-height:46px;display:flex;align-items:center;gap:9px;border-radius:16px;padding:9px 11px;background:#fff;border:1px solid rgba(38,16,26,.075);color:#4b3340;font-size:11.5px;font-weight:800;overflow:hidden}.rb-file-card svg{width:17px;height:17px;color:var(--vl-purple);flex:0 0 auto}.rb-file-card span{min-width:0;overflow:hidden;white-space:nowrap;text-overflow:ellipsis}

    .rb-post-actions{display:grid;grid-template-columns:repeat(4,1fr);gap:7px;margin-top:12px;padding-top:12px;border-top:1px solid rgba(38,16,26,.07)}.rb-action{min-height:35px;border-radius:999px;border:1px solid rgba(38,16,26,.065);background:#fff;color:#8b7b88;display:flex;align-items:center;justify-content:center;gap:6px;font-size:10.8px;font-weight:850;cursor:pointer;transition:.18s ease}.rb-action svg{width:15px;height:15px}.rb-action:hover{color:var(--vl-purple);transform:translateY(-1px)}.rb-action.is-comment{color:#fff;border:0;background:var(--vl-purple-gradient);box-shadow:0 10px 22px rgba(143,87,255,.16)}

    .rb-empty{padding:22px 15px;border-radius:22px;background:#fff;border:1px dashed rgba(38,16,26,.13);color:var(--vl-muted);text-align:center;font-size:12px;font-weight:750;line-height:1.5;box-shadow:var(--vl-shadow-soft)}.rb-empty b{color:#2e2230}.rb-pager{margin-top:12px;padding:10px 12px;border-radius:18px;border:1px solid var(--vl-line);background:#fff;overflow:auto;color:#2e2230;box-shadow:var(--vl-shadow-soft)}.rb-pager a{color:var(--vl-purple);font-weight:900}.rb-pager span{color:var(--vl-muted)}.rb-bottom-spacer-local{height:96px}

    /* COMMENTS */
    .rb-comments-panel{display:none;margin-top:13px;padding-top:13px;border-top:1px solid rgba(38,16,26,.07)}.rb-comments-panel.show{display:block;animation:rbCommentOpen .18s ease forwards}.rb-comments-title{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:10px}.rb-comments-title h3{margin:0;color:#2e2230;font-size:13px;font-weight:900}.rb-comments-title span{color:var(--vl-muted);font-size:11px;font-weight:750}.rb-comment-form{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:8px;align-items:end;margin-bottom:12px}.rb-comment-input{width:100%;min-height:42px;max-height:110px;resize:vertical;outline:none;border:1px solid rgba(38,16,26,.08);border-radius:17px;background:#fff;color:#2e2230;padding:11px 12px;font-size:12px;font-weight:650;line-height:1.45}.rb-comment-input::placeholder{color:#b6aab6}.rb-comment-input:focus{border-color:rgba(143,87,255,.28);box-shadow:0 0 0 4px rgba(143,87,255,.07)}.rb-comment-submit{width:44px;min-height:44px;border:0;border-radius:16px;color:#fff;display:grid;place-items:center;cursor:pointer;background:var(--vl-purple-gradient);box-shadow:0 12px 24px rgba(143,87,255,.18)}.rb-comment-submit svg{width:18px;height:18px}.rb-comment-divider{display:flex;align-items:center;gap:10px;margin:10px 0;color:#a99aa7;font-size:10px;font-weight:850;text-transform:uppercase;letter-spacing:.10em}.rb-comment-divider::before,.rb-comment-divider::after{content:"";height:1px;background:rgba(38,16,26,.08);flex:1}.rb-comment-list{display:grid;gap:9px}.rb-comment-item{display:grid;grid-template-columns:auto minmax(0,1fr);gap:9px;align-items:start}.rb-comment-body{min-width:0;padding:10px 11px;border-radius:18px;background:#fff;border:1px solid rgba(38,16,26,.065)}.rb-comment-head{display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:6px}.rb-comment-name{margin:0;color:#2e2230;font-size:12px;font-weight:900;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.rb-comment-time{margin:3px 0 0;color:var(--vl-muted);font-size:10px;font-weight:700}.rb-comment-delete{width:30px;height:30px;border-radius:11px;border:1px solid rgba(232,78,104,.14);background:#fff1f4;color:var(--vl-danger);display:grid;place-items:center;cursor:pointer}.rb-comment-delete svg{width:15px;height:15px}.rb-comment-text{margin:0;color:#4b3340;font-size:12px;line-height:1.5;font-weight:650;white-space:pre-wrap}.rb-comment-empty{padding:12px;border-radius:17px;background:#fff;border:1px dashed rgba(38,16,26,.12);color:var(--vl-muted);font-size:12px;text-align:center;font-weight:750}

    /* COMPOSER POPUP */
    .rb-compose-overlay{position:fixed;inset:0;z-index:9999;display:none;align-items:flex-end;justify-content:center;padding:18px 10px calc(18px + env(safe-area-inset-bottom))}.rb-compose-overlay.show{display:flex}.rb-compose-backdrop{position:absolute;inset:0;background:rgba(38,16,26,.34);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);animation:rbFadeIn .18s ease forwards}.rb-compose-modal{position:relative;z-index:2;width:100%;max-width:430px;border-radius:28px;background:rgba(255,255,255,.98);border:1px solid rgba(255,255,255,.72);box-shadow:0 34px 90px rgba(38,16,26,.20);padding:15px;animation:rbComposeUp .22s ease forwards}.rb-compose-head{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;padding-bottom:13px;margin-bottom:13px;border-bottom:1px solid rgba(38,16,26,.075)}.rb-compose-head h2{margin:0;color:#2e2230;font-size:18px;line-height:1.15;font-weight:900;letter-spacing:-.04em}.rb-compose-head p{margin:5px 0 0;color:var(--vl-muted);font-size:10.5px;font-weight:650;line-height:1.4}.rb-compose-close{width:40px;height:40px;border-radius:15px;border:1px solid rgba(38,16,26,.075);background:#fbf8ff;color:#4b3340;display:grid;place-items:center;cursor:pointer;flex:0 0 auto}.rb-compose-close svg{width:20px;height:20px}.rb-compose-user{display:flex;align-items:center;gap:10px;margin-bottom:12px}.rb-compose-user h3{margin:0;color:#2e2230;font-size:14px;font-weight:900;line-height:1.15}.rb-compose-user p{margin:4px 0 0;color:var(--vl-muted);font-size:11px;font-weight:750}.rb-compose-textarea{width:100%;min-height:154px;resize:none;outline:none;border:1px solid rgba(38,16,26,.085);border-radius:23px;background:#fbf8ff;color:#2e2230;padding:15px;font-size:12.5px;font-weight:650;line-height:1.58;transition:.18s ease}.rb-compose-textarea::placeholder{color:#b6aab6}.rb-compose-textarea:focus{border-color:rgba(143,87,255,.28);background:#fff;box-shadow:0 0 0 4px rgba(143,87,255,.07)}.rb-compose-tools{margin-top:10px}.rb-compose-upload{min-height:48px;border-radius:19px;border:1px solid rgba(38,16,26,.085);background:#fbf8ff;display:flex;align-items:center;gap:9px;padding:10px 12px;color:#4b3340;font-size:12px;font-weight:850;overflow:hidden;cursor:pointer}.rb-compose-upload svg{width:18px;height:18px;color:var(--vl-purple);flex:0 0 auto}.rb-compose-upload span{min-width:0;overflow:hidden;white-space:nowrap;text-overflow:ellipsis}.rb-compose-upload input{position:absolute;opacity:0;pointer-events:none;width:1px;height:1px}.rb-compose-actions{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:13px}.rb-compose-cancel,.rb-compose-submit{min-height:46px;border-radius:999px;cursor:pointer;font-size:13px;font-weight:900;display:inline-flex;align-items:center;justify-content:center;gap:8px}.rb-compose-cancel{color:#7b6370;background:#fbf8ff;border:1px solid rgba(38,16,26,.085)}.rb-compose-submit{border:0;color:#fff;background:var(--vl-purple-gradient);box-shadow:0 14px 30px rgba(143,87,255,.20)}.rb-compose-submit svg{width:18px;height:18px}

    /* TOAST */
    .rb-toast-wrap{position:fixed;top:18px;left:50%;transform:translateX(-50%);width:min(calc(100% - 24px),420px);z-index:10020;pointer-events:none}.rb-toast{position:relative;pointer-events:auto;display:flex;align-items:flex-start;gap:12px;padding:15px 44px 15px 14px;border-radius:22px;overflow:hidden;animation:rbToastIn .26s ease forwards;border:1px solid rgba(255,255,255,.60);box-shadow:0 24px 60px rgba(38,16,26,.20);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);background:rgba(255,255,255,.96)}.rb-toast.is-success{border-color:rgba(35,196,131,.22)}.rb-toast.is-error{border-color:rgba(255,95,120,.24)}.rb-toast-icon{width:44px;height:44px;border-radius:999px;flex:0 0 auto;display:grid;place-items:center;color:#fff;background:var(--vl-purple-gradient)}.rb-toast.is-success .rb-toast-icon{background:linear-gradient(135deg,#20b873,#17a76d)}.rb-toast.is-error .rb-toast-icon{background:linear-gradient(135deg,#ff5d79,#ff8b65)}.rb-toast-icon svg{width:22px;height:22px}.rb-toast-body{min-width:0;flex:1}.rb-toast-body h4{margin:0 0 4px;font-size:15px;font-weight:900;color:#2e2230;line-height:1.15}.rb-toast-body p,.rb-toast-errors{margin:0;color:#4b3340;font-size:12.5px;font-weight:700;line-height:1.5}.rb-toast-close{position:absolute;top:10px;right:10px;width:30px;height:30px;border-radius:10px;border:1px solid rgba(38,16,26,.075);background:#fbf8ff;color:#4b3340;display:grid;place-items:center;cursor:pointer}.rb-toast-close svg{width:16px;height:16px}.rb-toast-hide{animation:rbToastOut .22s ease forwards}

    /* BOTTOM NAV */
    .rb-bottom-spacer{height:94px!important}.rb-bottom-nav{background:rgba(255,255,255,.94)!important;border:1px solid rgba(38,16,26,.08)!important;box-shadow:0 -18px 42px rgba(38,16,26,.10),inset 0 1px 0 rgba(255,255,255,.75)!important;backdrop-filter:blur(22px)!important;-webkit-backdrop-filter:blur(22px)!important}.rb-bottom-nav__item{color:#a99aa7!important}.rb-bottom-nav__item:hover{color:#4b3340!important}.rb-bottom-nav__item.is-active{color:#2e2230!important;text-shadow:none!important}.rb-bottom-nav__item.is-active .rb-bottom-nav__icon{background:var(--vl-purple-gradient)!important;color:#fff!important;box-shadow:0 12px 24px rgba(143,87,255,.18)!important}

    @keyframes rbFadeIn{from{opacity:0}to{opacity:1}}@keyframes rbComposeUp{from{opacity:0;transform:translateY(26px) scale(.96)}to{opacity:1;transform:translateY(0) scale(1)}}@keyframes rbToastIn{from{opacity:0;transform:translateY(-14px) scale(.96)}to{opacity:1;transform:translateY(0) scale(1)}}@keyframes rbToastOut{from{opacity:1;transform:translateY(0) scale(1)}to{opacity:0;transform:translateY(-10px) scale(.96)}}@keyframes rbCommentOpen{from{opacity:0;transform:translateY(-4px)}to{opacity:1;transform:translateY(0)}}@keyframes rbSpark{0%,100%{opacity:.45;transform:translateY(0) scale(.95)}50%{opacity:1;transform:translateY(-4px) scale(1.08)}}
    @media (min-width:768px){.rb-compose-overlay{align-items:center}}
    @media (max-width:370px){.rb-page{padding-left:8px;padding-right:8px}.rb-title-wrap h1{font-size:16px}.rb-title-wrap p{font-size:10.5px;max-width:185px}.rb-header-btn{width:37px;height:37px}.rb-forum-hero-inner{grid-template-columns:1fr auto;padding:14px}.rb-forum-hero h2{font-size:17px}.rb-forum-stat{width:62px;height:62px;border-radius:19px}.rb-post-content{font-size:16px}.rb-post-actions{grid-template-columns:repeat(2,1fr)}.rb-compose-modal{border-radius:24px;padding:13px}.rb-compose-textarea{min-height:140px;border-radius:20px}.rb-toast-wrap{top:14px;width:min(calc(100% - 18px),420px)}.rb-toast{border-radius:18px;padding:13px 40px 13px 12px}.rb-toast-icon{width:40px;height:40px}.rb-media-img,.rb-media-grid.is-one .rb-media-img{border-radius:16px}}
    @media (prefers-reduced-motion:reduce){*,*::before,*::after{animation:none!important;transition:none!important}}

  </style>

</head>

<body>
  {{-- POPUP COMPOSER --}}
  <div class="rb-compose-overlay" id="composeOverlay" aria-hidden="true">
    <div class="rb-compose-backdrop" id="closeComposerBackdrop"></div>

    <section class="rb-compose-modal" role="dialog" aria-modal="true" aria-labelledby="composeTitle">
      <div class="rb-compose-head">
        <div>
          <h2 id="composeTitle">Buat Forum</h2>
          <p>Bagikan insight komunitas Velora.</p>
        </div>

        <button class="rb-compose-close" type="button" id="closeComposerBtn" aria-label="Tutup popup">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M18 6 6 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
            <path d="M6 6 18 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
          </svg>
        </button>
      </div>

      <form method="POST" action="{{ route('team.posts.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="rb-compose-user">
          <div class="rb-avatar" aria-hidden="true">
            {{ rbInitials($user->name ?? 'User') }}
          </div>

          <div>
            <h3>{{ $user->name ?? 'User' }}</h3>
            <p>Posting ke Forum Velora</p>
          </div>
        </div>

        <textarea
          class="rb-compose-textarea"
          name="content"
          rows="6"
          placeholder="Tulis update, bukti, atau diskusi komunitas Velora..."
        >{{ old('content') }}</textarea>

        <div class="rb-compose-tools">
          <label class="rb-compose-upload">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M17 8 12 3 7 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M12 3v12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>

            <span id="uploadText">Gambar / PDF / File</span>
            <input id="mediaInput" type="file" name="media[]" multiple>
          </label>
        </div>

        <div class="rb-compose-actions">
          <button class="rb-compose-cancel" type="button" id="cancelComposerBtn">
            Batal
          </button>

          <button class="rb-compose-submit" type="submit">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M22 2 11 13" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M22 2 15 22 11 13 2 9 22 2Z" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Posting
          </button>
        </div>
      </form>
    </section>
  </div>

  {{-- POPUP MESSAGE --}}
  @if(session('success') || session('error') || $errors->any())
    <div class="rb-toast-wrap" id="rbToastWrap">
      <div
        class="rb-toast {{ session('success') ? 'is-success' : '' }} {{ session('error') || $errors->any() ? 'is-error' : '' }}"
        id="rbToast"
        role="alert"
        aria-live="assertive"
      >
        <button type="button" class="rb-toast-close" id="rbToastClose" aria-label="Tutup notifikasi">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M18 6 6 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
            <path d="M6 6 18 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
          </svg>
        </button>

        <div class="rb-toast-icon">
          @if(session('success'))
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          @else
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 8v5" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"/>
              <circle cx="12" cy="16.5" r="1" fill="currentColor"/>
              <path d="M10.3 3.84 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.7 3.84a2 2 0 0 0-3.4 0Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
          @endif
        </div>

        <div class="rb-toast-body">
          @if(session('success'))
            <h4>Sukses</h4>
            <p>{{ session('success') }}</p>
          @elseif(session('error'))
            <h4>Gagal</h4>
            <p>{{ session('error') }}</p>
          @elseif($errors->any())
            <h4>Terjadi kesalahan</h4>
            <div class="rb-toast-errors">
              @foreach($errors->all() as $error)
                <div>• {{ $error }}</div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>
  @endif

  <main class="rb-page">
    <div class="rb-phone">

      {{-- HEADER --}}
      <header class="rb-topbar">
        <div class="rb-brand">

          <div class="rb-title-wrap">
            <h1>Hi, {{ $user->name ?? 'Member' }}</h1>
            <p>Ready to discuss today?</p>
          </div>
        </div>

        <div class="rb-header-actions">
          <a href="{{ url('/saldo/rincian') }}" class="rb-header-btn" aria-label="Notifikasi">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span class="rb-notif-dot"></span>
          </a>
        </div>
      </header>

      {{-- HERO FORUM VELORA --}}
      <section class="rb-forum-hero" aria-label="Ringkasan forum Velora">
        <div class="rb-forum-hero-inner">
          <div>
            <span class="rb-forum-kicker">Forum Assistants</span>
            <h2>Use Velora forum to share updates.</h2>
            <p>Diskusi member, tanya jawab, bukti progres, dan update komunitas Velora dalam satu ruang.</p>
          </div>

          <div class="rb-forum-stat">
            <strong>{{ method_exists($posts, 'total') ? number_format($posts->total(), 0, ',', '.') : number_format($posts->count(), 0, ',', '.') }}</strong>
            <span>Total<br>post</span>
          </div>
        </div>
      </section>

      {{-- FILTER TABS --}}
      <nav class="rb-forum-tabs" aria-label="Filter forum">
        <a href="{{ url()->current() }}" class="rb-tab is-active">On Going</a>
        <a href="{{ url()->current() }}" class="rb-tab">Up Coming</a>
        <a href="{{ url()->current() }}" class="rb-tab">Ended</a>
        <button class="rb-tab" type="button" id="openComposerBtnTab">Create</button>
      </nav>

      {{-- COMPOSER TRIGGER --}}
      <section class="rb-composer-trigger" aria-label="Buat postingan forum">
        <button class="rb-composer-open" type="button" id="openComposerBtn">
          <span class="rb-avatar" aria-hidden="true">
            {{ rbInitials($user->name ?? 'User') }}
          </span>

          <span class="rb-composer-placeholder">
            Start a forum discussion...
          </span>

          <span class="rb-composer-plus" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 5v14" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
              <path d="M5 12h14" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
            </svg>
          </span>
        </button>
      </section>

      {{-- FEED --}}
      <section class="rb-feed" aria-label="Feed Forum">
        @forelse($posts as $post)
          @php
            $postUser = $post->user;
            $imgs = $post->media ? $post->media->where('type', 'image') : collect();
            $files = $post->media ? $post->media->where('type', 'file') : collect();

            $imgCount = $imgs->count();
            $imgClass = $imgCount === 1 ? 'is-one' : ($imgCount === 2 ? 'is-two' : 'is-many');

            $commentsCount = $post->comments_count ?? ($post->comments ? $post->comments->count() : 0);
          @endphp

          <article class="rb-feed-card" aria-label="Postingan {{ $post->id }}">
            <div class="rb-post-head">
              <div class="rb-post-user">
                <div class="rb-avatar" aria-hidden="true">
                  {{ rbInitials($postUser->name ?? 'User') }}
                </div>

                <div style="min-width:0">
                  <h2 class="rb-post-name">{{ $postUser->name ?? 'User' }}</h2>
                  <p class="rb-post-time">
                    {{ rbTimeLabel($post->created_at) }}
                    @if($post->created_at)
                      • {{ $post->created_at->format('H:i') }}
                    @endif
                  </p>
                </div>
              </div>

              @can('delete', $post)
                <form method="POST" action="{{ route('team.posts.destroy', $post) }}">
                  @csrf
                  @method('DELETE')

                  <button
                    class="rb-delete-btn"
                    type="submit"
                    onclick="return confirm('Hapus postingan ini?')"
                    aria-label="Hapus postingan"
                  >
                    <svg viewBox="0 0 24 24" fill="none">
                      <path d="M3 6h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                      <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                      <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                      <path d="M10 11v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                      <path d="M14 11v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                  </button>
                </form>
              @endcan
            </div>

            @if($post->content)
              <div class="rb-post-content">{{ $post->content }}</div>
            @endif

            @if($imgs->count())
              <div class="rb-media-grid {{ $imgClass }}" aria-label="Media gambar">
                @foreach($imgs as $m)
                  <a href="{{ asset('storage/'.$m->path) }}" target="_blank" rel="noopener">
                    <img
                      class="rb-media-img"
                      src="{{ asset('storage/'.$m->path) }}"
                      alt="{{ $m->original_name ?? 'Forum media' }}"
                    >
                  </a>
                @endforeach
              </div>
            @endif

            @if($files->count())
              <div class="rb-file-list" aria-label="File postingan">
                @foreach($files as $m)
                  <a class="rb-file-card" href="{{ asset('storage/'.$m->path) }}" target="_blank" rel="noopener">
                    <svg viewBox="0 0 24 24" fill="none">
                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                      <path d="M14 2v6h6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    </svg>
                    <span>{{ $m->original_name ?? 'Download file' }}</span>
                  </a>
                @endforeach
              </div>
            @endif

          <div class="rb-post-actions">
            <button class="rb-action" type="button" aria-label="Like">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                <path d="M7 11l4-8a3 3 0 0 1 3 3v5h5a2 2 0 0 1 2 2l-1 6a3 3 0 0 1-3 3H7V11Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              </svg>
              Suka
            </button>

            <button
              class="rb-action is-comment"
              type="button"
              data-comment-toggle="{{ $post->id }}"
              aria-expanded="false"
            >
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              </svg>
              {{ $commentsCount }}
            </button>

            <button class="rb-action" type="button">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M4 12v7a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M16 6l-4-4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 2v14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
              Bagikan
            </button>

            <button class="rb-action" type="button">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              </svg>
              Simpan
            </button>
          </div>

          @php
  $postComments = $post->comments ?? collect();
@endphp

<div class="rb-comments-panel" id="commentsPanel-{{ $post->id }}" aria-label="Komentar postingan {{ $post->id }}">
  <div class="rb-comments-title">
    <h3>Komentar</h3>
    <span>{{ $commentsCount }} komentar</span>
  </div>

  <form class="rb-comment-form" method="POST" action="{{ route('team.comments.store', $post) }}">
  @csrf

  <textarea
    class="rb-comment-input"
    name="content"
    rows="2"
    placeholder="Tulis komentar..."
    required
  ></textarea>

  <button class="rb-comment-submit" type="submit" aria-label="Kirim komentar">
    <svg viewBox="0 0 24 24" fill="none">
      <path d="M22 2 11 13" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M22 2 15 22 11 13 2 9 22 2Z" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </button>
</form>

<div class="rb-comment-divider">
  <span>Daftar komentar</span>
</div>

<div class="rb-comment-list">
    @forelse($postComments as $comment)
      <div class="rb-comment-item">
        <div class="rb-avatar" aria-hidden="true">
          {{ rbInitials($comment->user->name ?? 'User') }}
        </div>

        <div class="rb-comment-body">
          <div class="rb-comment-head">
            <div style="min-width:0">
              <p class="rb-comment-name">{{ $comment->user->name ?? 'User' }}</p>
              <span class="rb-comment-time">
                {{ rbTimeLabel($comment->created_at) }}
                @if($comment->created_at)
                  • {{ $comment->created_at->format('H:i') }}
                @endif
              </span>
            </div>

            @can('delete', $comment)
              <form method="POST" action="{{ route('team.comments.destroy', $comment) }}">
                @csrf
                @method('DELETE')

                <button
                  class="rb-comment-delete"
                  type="submit"
                  onclick="return confirm('Hapus komentar ini?')"
                  aria-label="Hapus komentar"
                >
                  <svg viewBox="0 0 24 24" fill="none">
                    <path d="M3 6h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                  </svg>
                </button>
              </form>
            @endcan
          </div>

          <p class="rb-comment-text">{{ $comment->content }}</p>
        </div>
      </div>
    @empty
      <div class="rb-comment-empty">
        Belum ada komentar. Jadilah yang pertama berkomentar.
      </div>
    @endforelse
  </div>
</div>


          </article>
        @empty
          <div class="rb-empty">
            <b>Belum ada postingan.</b><br>
            Jadilah yang pertama membagikan update atau diskusi di forum Velora.
          </div>
        @endforelse
      </section>

      @if(method_exists($posts, 'links'))
        <div class="rb-pager">
          {{ $posts->links() }}
        </div>
      @endif

      <div class="rb-bottom-spacer-local"></div>
    </div>
  </main>

  @include('partials.bottom-nav')

  <script>
    (function(){
      const overlay = document.getElementById('composeOverlay');
      const openBtn = document.getElementById('openComposerBtn');
      const openBtnTab = document.getElementById('openComposerBtnTab');
      const closeBtn = document.getElementById('closeComposerBtn');
      const cancelBtn = document.getElementById('cancelComposerBtn');
      const backdrop = document.getElementById('closeComposerBackdrop');
      const textarea = overlay ? overlay.querySelector('.rb-compose-textarea') : null;

      const input = document.getElementById('mediaInput');
      const label = document.getElementById('uploadText');

      const toast = document.getElementById('rbToast');
      const toastWrap = document.getElementById('rbToastWrap');
      const toastClose = document.getElementById('rbToastClose');

      function openComposer(){
        if(!overlay) return;

        overlay.classList.add('show');
        overlay.setAttribute('aria-hidden', 'false');
        document.body.classList.add('rb-compose-lock');

        setTimeout(function(){
          if(textarea) textarea.focus();
        }, 120);
      }

      function closeComposer(){
        if(!overlay) return;

        overlay.classList.remove('show');
        overlay.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('rb-compose-lock');
      }

      function updateUploadLabel(){
        if(!input || !label) return;

        const total = input.files ? input.files.length : 0;

        if(total <= 0){
          label.textContent = 'Gambar / PDF / File';
          return;
        }

        if(total === 1){
          label.textContent = input.files[0].name;
          return;
        }

        label.textContent = total + ' file dipilih';
      }

      function closeToast(){
        if(!toast || !toastWrap) return;

        toast.classList.add('rb-toast-hide');

        setTimeout(function(){
          toastWrap.remove();
        }, 220);
      }

      if(openBtn) openBtn.addEventListener('click', openComposer);
      if(openBtnTab) openBtnTab.addEventListener('click', openComposer);
      if(closeBtn) closeBtn.addEventListener('click', closeComposer);
      if(cancelBtn) cancelBtn.addEventListener('click', closeComposer);
      if(backdrop) backdrop.addEventListener('click', closeComposer);

      if(input) input.addEventListener('change', updateUploadLabel);

      if(toastClose) toastClose.addEventListener('click', closeToast);
      if(toast && toastWrap) setTimeout(closeToast, 2800);

      document.addEventListener('keydown', function(e){
        if(e.key === 'Escape'){
          closeComposer();
          closeToast();
        }
      });

      @if($errors->any() && old('content'))
        openComposer();
      @endif
    })();

    const commentButtons = document.querySelectorAll('[data-comment-toggle]');

function closeAllCommentPanels(exceptId){
  document.querySelectorAll('.rb-comments-panel.show').forEach(function(panel){
    if(panel.id !== 'commentsPanel-' + exceptId){
      panel.classList.remove('show');

      const card = panel.closest('.rb-feed-card');
      if(card) card.classList.remove('is-comment-open');
    }
  });

  document.querySelectorAll('[data-comment-toggle]').forEach(function(btn){
    if(btn.getAttribute('data-comment-toggle') !== String(exceptId)){
      btn.setAttribute('aria-expanded', 'false');
    }
  });
}

commentButtons.forEach(function(btn){
  btn.addEventListener('click', function(e){
    e.preventDefault();
    e.stopPropagation();

    const id = btn.getAttribute('data-comment-toggle');
    const panel = document.getElementById('commentsPanel-' + id);
    const card = btn.closest('.rb-feed-card');

    if(!panel) return;

    const willOpen = !panel.classList.contains('show');

    closeAllCommentPanels(id);

    panel.classList.toggle('show', willOpen);
    btn.setAttribute('aria-expanded', willOpen ? 'true' : 'false');

    if(card){
      card.classList.toggle('is-comment-open', willOpen);
    }

    if(willOpen){
      setTimeout(function(){
        panel.scrollIntoView({
          behavior:'smooth',
          block:'nearest'
        });
      }, 80);
    }
  });
});

/* Klik area postingan juga buka komentar, kecuali klik tombol/form/link/media */
document.querySelectorAll('.rb-feed-card').forEach(function(card){
  card.addEventListener('click', function(e){
    if(
      e.target.closest('button') ||
      e.target.closest('a') ||
      e.target.closest('form') ||
      e.target.closest('textarea') ||
      e.target.closest('input')
    ){
      return;
    }

    const btn = card.querySelector('[data-comment-toggle]');
    if(btn) btn.click();
  });
});
  </script>
</body>
</html>