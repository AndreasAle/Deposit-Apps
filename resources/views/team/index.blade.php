@include('partials.anti-inspect')
@php
  $user = auth()->user();

  if(!function_exists('rbInitials')){
    function rbInitials($name){
      $name = trim((string) $name);
      if($name === '') return 'U';
      $parts = preg_split('/\s+/', $name);
      $first = mb_substr($parts[0] ?? 'U', 0, 1);
      $second = mb_substr($parts[1] ?? '', 0, 1);
      return mb_strtoupper($first.$second);
    }
  }
  if(!function_exists('rbTimeLabel')){
    function rbTimeLabel($date){
      if(!$date) return '-';
      return $date->diffForHumans();
    }
  }

  if(!function_exists('rbMaskId')){
    function rbMaskId($id){
      $s = str_pad((string) ($id ?? 0), 10, '0', STR_PAD_LEFT);
      return 'ID '.mb_substr($s, 0, 1).str_repeat('*', max(strlen($s) - 2, 4)).mb_substr($s, -1);
    }
  }

  $totalPosts = method_exists($posts, 'total') ? $posts->total() : $posts->count();

  $stackUsers = (method_exists($posts, 'items') ? collect($posts->items()) : collect($posts))->take(3);
  $stackMore  = max(0, (int) $totalPosts - $stackUsers->count());
@endphp

@if(!$user)
  <script>location.href='/login'</script>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Forum Komunitas | Capital Wave</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --blue:#0A57A3; --blue-ink:#0b4a8c; --blue-lite:#2f7fd4; --blue-soft:#eef4fb;
      --navy:#0b2740; --navy-2:#0d3357; --navy-3:#0a2036;
      --gold:#c99433; --gold-lite:#e8c874; --gold-deep:#a9772a; --gold-soft:#faf3e2;
      --gold-metal:linear-gradient(135deg,#a9772a 0%,#e8c874 46%,#c99433 100%);
      --green:#1c9d67; --green-soft:#e6f5ee; --red:#dc5757; --red-soft:#fdeaea;
      --bg:#eef1f6; --card:#ffffff; --tint:#f5f8fc; --line:#e9edf4; --line-2:#dfe5ee;
      --ink:#152a3f; --ink-soft:#46586c; --muted:#8493a6; --muted-2:#aab6c4;
      --sh-sm:0 1px 2px rgba(11,39,64,.05);
      --sh:0 2px 6px rgba(11,39,64,.04), 0 12px 30px rgba(11,39,64,.07);
      --sh-lg:0 4px 10px rgba(11,39,64,.05), 0 22px 50px rgba(11,39,64,.12);
      --sh-navy:0 10px 24px rgba(9,30,52,.28), 0 24px 60px rgba(9,30,52,.30);
      --r:24px; --r-sm:16px;
    }
    *{ box-sizing:border-box; }
    html,body{ min-height:100%; }
    body{
      margin:0; color:var(--ink);
      font-family:'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background:
        radial-gradient(900px 500px at 50% -220px, rgba(10,87,163,.10), transparent 60%),
        radial-gradient(600px 400px at 100% 8%, rgba(201,148,51,.06), transparent 55%),
        linear-gradient(180deg,#f2f5f9 0%,#eef1f6 40%,#eaeef4 100%);
      background-attachment:fixed; overflow-x:hidden;
      -webkit-font-smoothing:antialiased; -webkit-tap-highlight-color:transparent; letter-spacing:-.01em;
    }
    body.rb-compose-lock{ overflow:hidden; }
    a{ color:inherit; text-decoration:none; }
    button,input,textarea{ font-family:inherit; }
    h1,h2,h3,h4,p{ margin:0; }
    .rb-page{ width:100%; min-height:100vh; display:flex; justify-content:center; }
    .rb-phone{ width:100%; max-width:428px; min-height:100vh; position:relative; padding:18px 16px 112px; }

    /* ===== HEADER ===== */
    .cw-header{ display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:16px; }
    .cw-brand{ display:flex; align-items:center; gap:11px; min-width:0; }
    .cw-mark{ width:42px; height:42px; border-radius:50%; flex:0 0 auto; position:relative; display:grid; place-items:center;
      background:linear-gradient(160deg,#ffffff 0%,#eef4fb 100%); box-shadow:0 8px 20px rgba(11,39,64,.26), inset 0 1px 0 rgba(255,255,255,.14); }
    .cw-mark::after{ content:""; position:absolute; inset:0; border-radius:50%; padding:1.4px; background:var(--gold-metal); -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; opacity:.85; }
    .cw-mark svg{ width:26px; height:26px; position:relative; z-index:1; }
    .cw-word{ display:flex; flex-direction:column; min-width:0; line-height:1; }
    .cw-word .name{ font-size:18px; font-weight:800; letter-spacing:.01em; color:var(--navy); white-space:nowrap; }
    .cw-word .tag{ margin-top:5px; font-size:8.5px; font-weight:600; letter-spacing:.24em; text-transform:uppercase; color:var(--muted); white-space:nowrap; }
    .cw-tools{ display:flex; align-items:center; gap:2px; padding:4px; border-radius:999px; background:var(--card); border:1px solid var(--line); box-shadow:var(--sh-sm); flex:0 0 auto; }
    .cw-tool{ width:38px; height:38px; border-radius:999px; display:grid; place-items:center; color:var(--ink-soft); position:relative; transition:.16s ease; cursor:pointer; }
    .cw-tool:hover{ color:var(--blue); background:var(--blue-soft); }
    .cw-tool svg{ width:19px; height:19px; }
    .cw-tool .dot{ position:absolute; right:8px; top:8px; width:7px; height:7px; border-radius:999px; background:var(--gold); border:2px solid var(--card); }
    .cw-tool-div{ width:1px; height:20px; background:var(--line-2); }

    /* ===== HERO ===== */
    .rb-hero{ position:relative; overflow:hidden; border-radius:26px; padding:20px; color:#fff;
      background:
        radial-gradient(420px 240px at 88% -20%, rgba(232,200,116,.18), transparent 62%),
        radial-gradient(360px 220px at 8% 120%, rgba(47,127,212,.22), transparent 60%),
        linear-gradient(150deg,#0f3255 0%,#0b2740 52%,#0a2036 100%);
      box-shadow:var(--sh-navy); }
    .rb-hero::before{ content:""; position:absolute; inset:0; border-radius:inherit; padding:1px; pointer-events:none;
      background:linear-gradient(150deg, rgba(232,200,116,.6), rgba(232,200,116,0) 34%, rgba(255,255,255,.14) 100%);
      -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; }
    .rb-hero > *{ position:relative; z-index:1; }
    .rb-hero-kicker{ display:inline-flex; align-items:center; gap:8px; color:rgba(255,255,255,.62); font-size:9.5px; font-weight:600; letter-spacing:.2em; text-transform:uppercase; }
    .rb-hero-kicker::before{ content:""; width:6px; height:6px; border-radius:999px; background:var(--gold-lite); box-shadow:0 0 10px rgba(232,200,116,.8); }
    .rb-hero h2{ margin-top:12px; font-size:21px; font-weight:700; letter-spacing:-.03em; line-height:1.2; max-width:250px; }
    .rb-hero p{ margin-top:8px; color:rgba(255,255,255,.6); font-size:12px; font-weight:500; line-height:1.5; max-width:300px; }
    .rb-hero-foot{ margin-top:16px; display:flex; align-items:center; gap:12px; }
    .rb-hero-stat{ display:flex; align-items:center; gap:9px; padding:8px 13px; border-radius:14px; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.14); }
    .rb-hero-stat b{ font-size:18px; font-weight:700; letter-spacing:-.02em; color:#fff; }
    .rb-hero-stat span{ font-size:10px; font-weight:500; color:rgba(255,255,255,.55); line-height:1.15; }
    .rb-hero-cta{ margin-left:auto; display:inline-flex; align-items:center; gap:7px; height:42px; padding:0 16px; border:0; cursor:pointer;
      border-radius:13px; color:#3d2b06; background:var(--gold-metal); box-shadow:0 8px 20px rgba(201,148,51,.36), inset 0 1px 0 rgba(255,255,255,.4);
      font-size:12.5px; font-weight:700; }
    .rb-hero-cta svg{ width:15px; height:15px; }
    .rb-hero h2 .gold{ color:var(--gold-lite); }
    .rb-hero-stack{ display:flex; align-items:center; margin-bottom:14px; }
    .rb-stack-av{ width:32px; height:32px; border-radius:50%; padding:1.5px; background:var(--gold-metal); margin-left:-9px; display:grid; }
    .rb-stack-av:first-child{ margin-left:0; }
    .rb-stack-av::after{ content:attr(data-i); grid-area:1/1; border-radius:50%; display:grid; place-items:center; font-size:10px; font-weight:700; color:#fff; background:linear-gradient(135deg,#1a4a75,#0b2740); box-shadow:0 0 0 2px #0b2740; }
    .rb-stack-more{ margin-left:10px; font-size:12px; font-weight:700; color:var(--gold-lite); }

    /* verified + testimoni bits */
    .rb-verified{ width:15px; height:15px; flex:0 0 auto; }
    .rb-post-id{ margin-top:3px; font-size:11px; font-weight:500; color:var(--muted); letter-spacing:.02em; }
    .rb-verif-row{ padding:2px 16px 12px; display:flex; justify-content:flex-end; }
    .rb-verif-pill{ display:inline-flex; align-items:center; gap:5px; padding:5px 11px; border-radius:999px; background:var(--blue-soft); color:var(--blue); font-size:10.5px; font-weight:700; }
    .rb-verif-pill svg{ width:13px; height:13px; }
    .rb-post-meta{ display:flex; align-items:center; justify-content:space-between; gap:10px; padding:10px 12px; border-top:1px solid var(--line); background:var(--tint); }
    .rb-meta-btn{ display:inline-flex; align-items:center; gap:6px; padding:8px 13px; border:1px solid var(--line); background:var(--card); color:var(--ink-soft); border-radius:11px; font-size:11.5px; font-weight:600; cursor:pointer; transition:.15s ease; }
    .rb-meta-btn:hover{ color:var(--blue); border-color:var(--blue-soft); }
    .rb-meta-btn svg{ width:15px; height:15px; }
    .rb-post-date{ display:flex; align-items:center; gap:5px; color:var(--muted); font-size:11px; font-weight:600; }
    .rb-post-date svg{ width:13px; height:13px; }

    /* bonus CTA */
    .rb-bonus-cta{ margin-top:14px; width:100%; display:flex; align-items:center; justify-content:center; gap:9px; min-height:52px; border:0; cursor:pointer; border-radius:16px; color:#3d2b06; background:var(--gold-metal); box-shadow:0 12px 26px rgba(201,148,51,.4), inset 0 1px 0 rgba(255,255,255,.4); font-size:14px; font-weight:800; letter-spacing:-.01em; position:relative; overflow:hidden; }
    .rb-bonus-cta::after{ content:""; position:absolute; top:0; left:-60%; width:40%; height:100%; background:linear-gradient(100deg, transparent, rgba(255,255,255,.55), transparent); transform:skewX(-18deg); animation:rbSheen 4.5s ease-in-out infinite; }
    .rb-bonus-cta svg{ width:18px; height:18px; }
    .rb-bonus-chip{ display:inline-flex; align-items:center; padding:3px 8px; border-radius:8px; background:rgba(11,39,64,.16); color:#0b2740; font-size:9.5px; font-weight:800; letter-spacing:.06em; }
    @keyframes rbSheen{ 0%,60%{ left:-60%; } 100%{ left:130%; } }

    /* ===== FILTER TABS ===== */
    .rb-filter{ margin:20px -2px -14px; display:flex; gap:8px; overflow-x:auto; overflow-y:hidden; padding:10px 4px 26px; scrollbar-width:none; }
    .rb-filter::-webkit-scrollbar{ display:none; }
    .rb-tab{ flex:0 0 auto; height:38px; padding:0 16px; border:0; cursor:pointer; display:inline-flex; align-items:center; gap:6px;
      border-radius:999px; color:var(--ink-soft); background:var(--card); border:1px solid var(--line); box-shadow:var(--sh-sm);
      font-size:12.5px; font-weight:500; transition:.16s ease; white-space:nowrap; }
    .rb-tab.is-active{ color:#fff; background:linear-gradient(135deg,#123457,#0b2740); border-color:transparent; font-weight:600; box-shadow:0 8px 16px rgba(11,39,64,.2); }
    .rb-tab.rb-tab-create{ color:var(--gold-deep); background:var(--gold-soft); border-color:rgba(201,148,51,.24); font-weight:700; }

    /* ===== COMPOSER TRIGGER ===== */
    .rb-composer-open{ margin-top:14px; width:100%; display:flex; align-items:center; gap:12px; padding:12px 13px; border:1px solid var(--line); cursor:pointer;
      border-radius:18px; background:var(--card); box-shadow:var(--sh-sm); transition:.16s ease; }
    .rb-composer-open:hover{ box-shadow:var(--sh); }
    .rb-composer-placeholder{ flex:1; text-align:left; color:var(--muted); font-size:13px; font-weight:500; }
    .rb-composer-plus{ width:36px; height:36px; flex:0 0 auto; border-radius:12px; display:grid; place-items:center; color:#fff; background:linear-gradient(135deg, var(--blue), var(--blue-lite)); box-shadow:0 6px 14px rgba(10,87,163,.3); }
    .rb-composer-plus svg{ width:18px; height:18px; }

    /* ===== AVATAR ===== */
    .rb-avatar{ width:44px; height:44px; flex:0 0 auto; border-radius:14px; padding:1.5px; background:var(--gold-metal); display:grid; }
    .rb-avatar::after{ content:attr(data-i); grid-area:1/1; border-radius:12px; display:grid; place-items:center; color:#fff; font-size:14px; font-weight:700; letter-spacing:.02em;
      background:linear-gradient(135deg, var(--navy), var(--blue)); }
    .rb-avatar.sm{ width:38px; height:38px; }
    .rb-avatar.sm::after{ font-size:12px; }

    /* ===== FEED ===== */
    .rb-feed{ margin-top:18px; display:flex; flex-direction:column; gap:14px; }
    .rb-feed-card{ position:relative; overflow:hidden; border-radius:var(--r); background:var(--card); border:1px solid var(--line); box-shadow:var(--sh); transition:.18s ease; }
    .rb-feed-card::before{ content:""; position:absolute; top:0; left:0; right:0; height:3px; background:var(--gold-metal); opacity:0; transition:.18s ease; }
    .rb-feed-card.is-comment-open::before{ opacity:1; }
    .rb-feed-card:hover{ box-shadow:var(--sh-lg); }

    .rb-post-head{ display:flex; align-items:center; gap:11px; padding:15px 16px 10px; }
    .rb-post-user{ display:flex; align-items:center; gap:11px; min-width:0; flex:1; }
    .rb-post-name{ display:flex; align-items:center; gap:5px; font-size:14.5px; font-weight:700; letter-spacing:-.02em; color:var(--navy); min-width:0; }
    .rb-post-name .nm{ overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .rb-post-time{ margin-top:3px; display:flex; align-items:center; gap:5px; color:var(--muted); font-size:11px; font-weight:500; }
    .rb-post-badge{ display:inline-flex; align-items:center; gap:4px; padding:2px 7px; border-radius:7px; background:var(--gold-soft); color:var(--gold-deep); font-size:9px; font-weight:700; letter-spacing:.04em; text-transform:uppercase; }
    .rb-delete-btn{ width:36px; height:36px; flex:0 0 auto; border:1px solid var(--line); background:var(--card); color:var(--muted); border-radius:11px; display:grid; place-items:center; cursor:pointer; transition:.15s ease; }
    .rb-delete-btn:hover{ color:var(--red); border-color:var(--red-soft); background:var(--red-soft); }
    .rb-delete-btn svg{ width:17px; height:17px; }

    .rb-post-content{ padding:2px 16px 12px; color:var(--ink); font-size:13.5px; font-weight:500; line-height:1.62; white-space:pre-wrap; word-break:break-word; }

    /* media */
    .rb-media-grid{ padding:0 16px 12px; display:grid; gap:6px; }
    .rb-media-grid.is-one{ display:flex; justify-content:center; }
    .rb-media-grid.is-two{ grid-template-columns:1fr 1fr; }
    .rb-media-grid.is-many{ grid-template-columns:1fr 1fr; }
    .rb-media-grid a{ display:block; overflow:hidden; border-radius:14px; border:1px solid var(--line); background:var(--tint); }
    .rb-media-grid.is-one a{ width:66%; max-width:270px; }
    .rb-media-img{ width:100%; height:100%; object-fit:cover; display:block; aspect-ratio:3/4; transition:.25s ease; }
    .rb-media-grid a:hover .rb-media-img{ transform:scale(1.03); }

    .rb-file-list{ padding:0 16px 12px; display:flex; flex-direction:column; gap:8px; }
    .rb-file-card{ display:flex; align-items:center; gap:10px; padding:11px 12px; border-radius:13px; background:var(--tint); border:1px solid var(--line); color:var(--ink-soft); font-size:12.5px; font-weight:600; transition:.15s ease; }
    .rb-file-card:hover{ border-color:var(--blue); color:var(--blue); }
    .rb-file-card svg{ width:19px; height:19px; color:var(--gold-deep); flex:0 0 auto; }
    .rb-file-card span{ overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }

    /* actions */
    .rb-post-actions{ display:grid; grid-template-columns:repeat(4,1fr); gap:4px; padding:8px 10px; border-top:1px solid var(--line); background:var(--tint); }
    .rb-action{ min-height:40px; border:0; background:transparent; color:var(--ink-soft); border-radius:12px; display:flex; align-items:center; justify-content:center; gap:6px; font-size:12px; font-weight:600; cursor:pointer; transition:.15s ease; }
    .rb-action:hover{ background:var(--card); color:var(--navy); }
    .rb-action svg{ width:17px; height:17px; }
    .rb-action.is-comment:hover{ color:var(--blue); }

    /* comments panel */
    .rb-comments-panel{ display:none; padding:14px 16px 16px; border-top:1px solid var(--line); background:linear-gradient(180deg, var(--tint), var(--card)); }
    .rb-comments-panel.show{ display:block; animation:rbFade .28s ease both; }
    @keyframes rbFade{ from{ opacity:0; transform:translateY(-6px); } to{ opacity:1; transform:translateY(0); } }
    .rb-comments-title{ display:flex; align-items:baseline; justify-content:space-between; gap:10px; margin-bottom:12px; }
    .rb-comments-title h3{ font-size:13.5px; font-weight:700; color:var(--navy); letter-spacing:-.02em; }
    .rb-comments-title span{ font-size:11px; font-weight:600; color:var(--muted); }
    .rb-comment-form{ display:flex; align-items:flex-end; gap:8px; }
    .rb-comment-input{ flex:1; min-height:44px; max-height:120px; padding:11px 13px; border-radius:13px; border:1px solid var(--line-2); background:var(--card); color:var(--ink); font-size:13px; font-weight:500; resize:none; outline:none; transition:.15s ease; }
    .rb-comment-input:focus{ border-color:var(--blue); box-shadow:0 0 0 3px rgba(10,87,163,.1); }
    .rb-comment-submit{ width:44px; height:44px; flex:0 0 auto; border:0; border-radius:13px; cursor:pointer; display:grid; place-items:center; color:#fff; background:linear-gradient(135deg,#123457,#0b2740); box-shadow:0 8px 18px rgba(11,39,64,.24); }
    .rb-comment-submit svg{ width:18px; height:18px; }
    .rb-comment-divider{ margin:16px 0 12px; display:flex; align-items:center; gap:10px; color:var(--muted-2); font-size:10px; font-weight:600; letter-spacing:.06em; text-transform:uppercase; }
    .rb-comment-divider::before,.rb-comment-divider::after{ content:""; flex:1; height:1px; background:var(--line); }
    .rb-comment-list{ display:flex; flex-direction:column; gap:11px; }
    .rb-comment-item{ display:flex; gap:10px; }
    .rb-comment-body{ flex:1; min-width:0; padding:11px 13px; border-radius:14px; background:var(--card); border:1px solid var(--line); }
    .rb-comment-head{ display:flex; align-items:flex-start; justify-content:space-between; gap:8px; }
    .rb-comment-name{ font-size:12.5px; font-weight:700; color:var(--navy); }
    .rb-comment-time{ display:block; margin-top:2px; font-size:10px; font-weight:500; color:var(--muted); }
    .rb-comment-delete{ width:28px; height:28px; flex:0 0 auto; border:0; background:transparent; color:var(--muted-2); border-radius:8px; display:grid; place-items:center; cursor:pointer; }
    .rb-comment-delete:hover{ color:var(--red); background:var(--red-soft); }
    .rb-comment-delete svg{ width:15px; height:15px; }
    .rb-comment-text{ margin-top:6px; font-size:12.5px; font-weight:500; line-height:1.55; color:var(--ink); white-space:pre-wrap; word-break:break-word; }
    .rb-comment-empty{ padding:16px; border-radius:13px; border:1px dashed var(--line-2); color:var(--muted); text-align:center; font-size:12px; font-weight:500; }

    .rb-empty{ margin-top:18px; padding:30px 20px; border-radius:var(--r); background:var(--card); border:1px dashed var(--line-2); color:var(--muted); text-align:center; font-size:13px; font-weight:500; line-height:1.6; }
    .rb-empty b{ color:var(--navy); font-weight:700; }

    .rb-pager{ margin-top:18px; display:flex; justify-content:center; }
    .rb-pager nav{ display:flex; }
    .rb-pager a,.rb-pager span{ font-size:12px; font-weight:600; color:var(--ink-soft); }
    .rb-bottom-spacer-local{ height:20px; }

    /* ===== COMPOSER OVERLAY ===== */
    .rb-compose-overlay{ position:fixed; inset:0; z-index:1000; display:none; align-items:flex-end; justify-content:center; }
    .rb-compose-overlay.show{ display:flex; }
    .rb-compose-backdrop{ position:absolute; inset:0; background:rgba(11,39,64,.55); backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px); }
    .rb-compose-card{ position:relative; z-index:1; width:100%; max-width:460px; max-height:92vh; overflow:auto; border-radius:26px 26px 0 0; background:var(--card); box-shadow:0 -20px 60px rgba(11,39,64,.3); padding:8px 18px calc(18px + env(safe-area-inset-bottom)); animation:rbSheet .3s cubic-bezier(.22,.8,.22,1) both; }
    @keyframes rbSheet{ from{ transform:translateY(30px); opacity:0; } to{ transform:translateY(0); opacity:1; } }
    .rb-compose-grip{ width:44px; height:5px; border-radius:999px; background:var(--line-2); margin:8px auto 14px; }
    .rb-compose-head{ display:flex; align-items:flex-start; justify-content:space-between; gap:12px; margin-bottom:14px; }
    .rb-compose-head h2{ font-size:17px; font-weight:700; color:var(--navy); letter-spacing:-.02em; }
    .rb-compose-head p{ margin-top:4px; font-size:11.5px; font-weight:500; color:var(--muted); }
    .rb-compose-close{ width:38px; height:38px; flex:0 0 auto; border:1px solid var(--line); background:var(--tint); color:var(--ink-soft); border-radius:12px; display:grid; place-items:center; cursor:pointer; }
    .rb-compose-close svg{ width:18px; height:18px; }
    .rb-compose-user{ display:flex; align-items:center; gap:11px; margin-bottom:12px; }
    .rb-compose-user h3{ font-size:13.5px; font-weight:700; color:var(--navy); }
    .rb-compose-user p{ font-size:10.5px; font-weight:500; color:var(--muted); margin-top:2px; }
    .rb-compose-textarea{ width:100%; min-height:130px; padding:14px; border-radius:16px; border:1px solid var(--line-2); background:var(--tint); color:var(--ink); font-size:14px; font-weight:500; line-height:1.55; resize:vertical; outline:none; transition:.15s ease; }
    .rb-compose-textarea:focus{ border-color:var(--blue); box-shadow:0 0 0 3px rgba(10,87,163,.1); background:var(--card); }
    .rb-compose-tools{ margin-top:12px; }
    .rb-compose-upload{ display:flex; align-items:center; gap:9px; padding:12px 14px; border-radius:14px; border:1.5px dashed var(--line-2); background:var(--tint); color:var(--ink-soft); font-size:12.5px; font-weight:600; cursor:pointer; transition:.15s ease; }
    .rb-compose-upload:hover{ border-color:var(--blue); color:var(--blue); }
    .rb-compose-upload svg{ width:19px; height:19px; color:var(--gold-deep); flex:0 0 auto; }
    .rb-compose-upload input{ display:none; }
    .rb-compose-actions{ margin-top:16px; display:grid; grid-template-columns:1fr 1.4fr; gap:10px; }
    .rb-compose-cancel{ min-height:48px; border:1px solid var(--line-2); background:var(--card); color:var(--ink-soft); border-radius:14px; font-size:13px; font-weight:600; cursor:pointer; }
    .rb-compose-submit{ min-height:48px; border:0; border-radius:14px; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; color:#fff; background:linear-gradient(135deg,#123457,#0b2740); box-shadow:0 10px 22px rgba(11,39,64,.26); font-size:13px; font-weight:700; }
    .rb-compose-submit svg{ width:17px; height:17px; }

    /* ===== TOAST ===== */
    .rb-toast-wrap{ position:fixed; left:50%; top:16px; transform:translateX(-50%); z-index:2000; width:calc(100% - 28px); max-width:420px; }
    .rb-toast{ position:relative; display:flex; align-items:flex-start; gap:12px; padding:14px 42px 14px 14px; border-radius:16px; background:var(--card); border:1px solid var(--line); box-shadow:var(--sh-lg); animation:rbToastIn .3s cubic-bezier(.22,.8,.22,1) both; }
    .rb-toast.rb-toast-hide{ animation:rbToastOut .22s ease forwards; }
    @keyframes rbToastIn{ from{ transform:translateY(-16px); opacity:0; } to{ transform:translateY(0); opacity:1; } }
    @keyframes rbToastOut{ to{ transform:translateY(-16px); opacity:0; } }
    .rb-toast-icon{ width:38px; height:38px; flex:0 0 auto; border-radius:12px; display:grid; place-items:center; color:#fff; }
    .rb-toast.is-success .rb-toast-icon{ background:linear-gradient(135deg,#1c9d67,#15855a); }
    .rb-toast.is-error .rb-toast-icon{ background:linear-gradient(135deg,#dc5757,#b83f3f); }
    .rb-toast-icon svg{ width:20px; height:20px; }
    .rb-toast-body h4{ font-size:13px; font-weight:700; color:var(--navy); }
    .rb-toast-body p{ margin-top:3px; font-size:12px; font-weight:500; color:var(--ink-soft); line-height:1.45; }
    .rb-toast-errors{ margin-top:3px; font-size:11.5px; font-weight:500; color:var(--ink-soft); line-height:1.5; }
    .rb-toast-close{ position:absolute; right:10px; top:10px; width:26px; height:26px; border:0; background:transparent; color:var(--muted-2); border-radius:8px; display:grid; place-items:center; cursor:pointer; }
    .rb-toast-close svg{ width:15px; height:15px; }

    .rb-bottom-spacer{ height:94px; }

    @media (max-width:360px){
      .rb-phone{ padding:16px 12px 112px; }
      .rb-hero h2{ font-size:19px; }
      .rb-hero-foot{ flex-wrap:wrap; }
      .rb-hero-cta{ margin-left:0; }
      .rb-post-actions{ grid-template-columns:repeat(4,1fr); }
      .rb-action{ font-size:0; gap:0; }
      .rb-action svg{ width:19px; height:19px; }
    }
    @media (prefers-reduced-motion:reduce){ *,*::before,*::after{ animation:none !important; transition:none !important; } }
  </style>
</head>

<body>

  {{-- COMPOSER OVERLAY --}}
  <div class="rb-compose-overlay" id="composeOverlay" aria-hidden="true">
    <div class="rb-compose-backdrop" id="closeComposerBackdrop"></div>
    <section class="rb-compose-card" role="dialog" aria-modal="true" aria-label="Buat postingan">
      <div class="rb-compose-grip"></div>
      <div class="rb-compose-head">
        <div>
          <h2>Tulis Testimoni</h2>
          <p>Bagikan bukti & progres ke komunitas Capital Wave.</p>
        </div>
        <button class="rb-compose-close" type="button" id="closeComposerBtn" aria-label="Tutup">
          <svg viewBox="0 0 24 24" fill="none"><path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </button>
      </div>

      <form method="POST" action="{{ route('team.posts.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="rb-compose-user">
          <span class="rb-avatar" data-i="{{ rbInitials($user->name ?? 'User') }}" aria-hidden="true"></span>
          <div>
            <h3>{{ $user->name ?? 'User' }}</h3>
            <p>Posting ke Forum Capital Wave</p>
          </div>
        </div>

        <textarea class="rb-compose-textarea" name="content" rows="6" placeholder="Tulis update, bukti progres, atau diskusi komunitas...">{{ old('content') }}</textarea>

        <div class="rb-compose-tools">
          <label class="rb-compose-upload">
            <svg viewBox="0 0 24 24" fill="none"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M17 8 12 3 7 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 3v12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            <span id="uploadText">Gambar / PDF / File</span>
            <input id="mediaInput" type="file" name="media[]" multiple>
          </label>
        </div>

        <div class="rb-compose-actions">
          <button class="rb-compose-cancel" type="button" id="cancelComposerBtn">Batal</button>
          <button class="rb-compose-submit" type="submit">
            <svg viewBox="0 0 24 24" fill="none"><path d="M22 2 11 13" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 2 15 22 11 13 2 9 22 2Z" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Posting
          </button>
        </div>
      </form>
    </section>
  </div>

  {{-- TOAST --}}
  @if(session('success') || session('error') || $errors->any())
    <div class="rb-toast-wrap" id="rbToastWrap">
      <div class="rb-toast {{ session('success') ? 'is-success' : 'is-error' }}" id="rbToast" role="alert" aria-live="assertive">
        <div class="rb-toast-icon">
          @if(session('success'))
            <svg viewBox="0 0 24 24" fill="none"><path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
          @else
            <svg viewBox="0 0 24 24" fill="none"><path d="M12 8v5" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"/><circle cx="12" cy="16.5" r="1" fill="currentColor"/><path d="M10.3 3.84 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.7 3.84a2 2 0 0 0-3.4 0Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
          @endif
        </div>
        <div class="rb-toast-body">
          @if(session('success'))
            <h4>Berhasil</h4><p>{{ session('success') }}</p>
          @elseif(session('error'))
            <h4>Gagal</h4><p>{{ session('error') }}</p>
          @else
            <h4>Terjadi kesalahan</h4>
            <div class="rb-toast-errors">@foreach($errors->all() as $error)<div>• {{ $error }}</div>@endforeach</div>
          @endif
        </div>
        <button type="button" class="rb-toast-close" id="rbToastClose" aria-label="Tutup">
          <svg viewBox="0 0 24 24" fill="none"><path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg>
        </button>
      </div>
    </div>
  @endif

  <main class="rb-page">
    <div class="rb-phone">

      {{-- HEADER --}}
      <header class="cw-header">
        <a href="{{ url('/dashboard') }}" class="cw-brand" aria-label="Capital Wave">
          <span class="cw-mark">
            <img src="{{ asset('logo.png') }}" alt="Capital Wave" style="width:82%;height:82%;object-fit:contain;position:relative;z-index:1;">
          </span>
          <span class="cw-word">
            <span class="name">Forum</span>
            <span class="tag">Komunitas Capital Wave</span>
          </span>
        </a>
        <div class="cw-tools">
          <a href="{{ url('/saldo/rincian') }}" class="cw-tool" aria-label="Notifikasi">
            <svg viewBox="0 0 24 24" fill="none"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
            <span class="dot"></span>
          </a>
          <span class="cw-tool-div"></span>
          <button type="button" class="cw-tool" id="openComposerBtnTab" aria-label="Buat postingan">
            <svg viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          </button>
        </div>
      </header>

      {{-- HERO --}}
      <section class="rb-hero">
        <div class="rb-hero-stack">
          @foreach($stackUsers as $sp)
            <span class="rb-stack-av" data-i="{{ rbInitials($sp->user->name ?? 'U') }}" aria-hidden="true"></span>
          @endforeach
          @if($stackMore > 0)
            <span class="rb-stack-more">+{{ $stackMore }}</span>
          @endif
        </div>

        <span class="rb-hero-kicker">Bukti & Testimoni Terverifikasi</span>
        <h2>Setiap progres member <span class="gold">tercatat & terbukti nyata.</span></h2>
        <p>Bagikan bukti, update, dan testimoni perjalanan investasimu — semua terpantau dan tervalidasi komunitas Capital Wave.</p>
        <div class="rb-hero-foot">
          <div class="rb-hero-stat">
            <b>{{ number_format($totalPosts, 0, ',', '.') }}</b>
            <span>Total<br>Testimoni</span>
          </div>
          <button class="rb-hero-cta" type="button" id="openComposerBtn2">
            <svg viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>
            Tulis Testimoni
          </button>
        </div>
      </section>

      {{-- FILTER TABS --}}
      <nav class="rb-filter" aria-label="Filter testimoni">
        <a href="{{ url()->current() }}" class="rb-tab is-active">Terbaru</a>
        <a href="{{ url()->current() }}" class="rb-tab">Penarikan Tertinggi</a>
        <a href="{{ url()->current() }}" class="rb-tab">Media</a>
      </nav>

      {{-- COMPOSER TRIGGER --}}
      <button class="rb-composer-open" type="button" id="openComposerBtn3">
        <span class="rb-avatar sm" data-i="{{ rbInitials($user->name ?? 'User') }}" aria-hidden="true"></span>
        <span class="rb-composer-placeholder">Bagikan bukti / testimoni kamu...</span>
        <span class="rb-composer-plus" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg>
        </span>
      </button>

      {{-- BONUS CTA --}}
      <button class="rb-bonus-cta" type="button" id="openComposerBtn">
        <svg viewBox="0 0 24 24" fill="none"><path d="M12 3v18M5 8l7-5 7 5M5 16l7 5 7-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Tulis Testimoni
      </button>

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
            $postComments = $post->comments ?? collect();
            $maskId = rbMaskId($postUser->id ?? 0);
          @endphp

          <article class="rb-feed-card" aria-label="Postingan {{ $post->id }}">
            <div class="rb-post-head">
              <div class="rb-post-user">
                <span class="rb-avatar" data-i="{{ rbInitials($postUser->name ?? 'User') }}" aria-hidden="true"></span>
                <div style="min-width:0">
                  <h2 class="rb-post-name">
                    <span class="nm">{{ $postUser->name ?? 'User' }}</span>
                    <svg class="rb-verified" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" fill="#2f7fd4"/><path d="M8 12l2.5 2.5L16 9" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                  </h2>
                  <p class="rb-post-id">{{ $maskId }}</p>
                </div>
              </div>

              @can('delete', $post)
                <form method="POST" action="{{ route('team.posts.destroy', $post) }}">
                  @csrf
                  @method('DELETE')
                  <button class="rb-delete-btn" type="submit" onclick="return confirm('Hapus postingan ini?')" aria-label="Hapus postingan">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M3 6h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
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
                    <img class="rb-media-img" src="{{ asset('storage/'.$m->path) }}" alt="{{ $m->original_name ?? 'Forum media' }}">
                  </a>
                @endforeach
              </div>
            @endif

            @if($files->count())
              <div class="rb-file-list" aria-label="File postingan">
                @foreach($files as $m)
                  <a class="rb-file-card" href="{{ asset('storage/'.$m->path) }}" target="_blank" rel="noopener">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M14 2v6h6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
                    <span>{{ $m->original_name ?? 'Download file' }}</span>
                  </a>
                @endforeach
              </div>
            @endif

            <div class="rb-verif-row">
              <span class="rb-verif-pill">
                <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" fill="currentColor" opacity=".16"/><path d="M8 12l2.5 2.5L16 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Terverifikasi
              </span>
            </div>

            <div class="rb-post-meta">
              <button class="rb-meta-btn" type="button" data-comment-toggle="{{ $post->id }}" aria-expanded="false" aria-label="Komentar">
                <svg viewBox="0 0 24 24" fill="none"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
                {{ $commentsCount }} Komentar
              </button>
              <span class="rb-post-date">
                <svg viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="2"/><path d="M3 9h18M8 3v4M16 3v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                {{ $post->created_at ? $post->created_at->format('d M Y, H:i') : '-' }}
              </span>
            </div>

            <div class="rb-comments-panel" id="commentsPanel-{{ $post->id }}" aria-label="Komentar postingan {{ $post->id }}">
              <div class="rb-comments-title">
                <h3>Komentar</h3>
                <span>{{ $commentsCount }} komentar</span>
              </div>

              <form class="rb-comment-form" method="POST" action="{{ route('team.comments.store', $post) }}">
                @csrf
                <textarea class="rb-comment-input" name="content" rows="2" placeholder="Tulis komentar..." required></textarea>
                <button class="rb-comment-submit" type="submit" aria-label="Kirim komentar">
                  <svg viewBox="0 0 24 24" fill="none"><path d="M22 2 11 13" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 2 15 22 11 13 2 9 22 2Z" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
              </form>

              <div class="rb-comment-divider"><span>Daftar komentar</span></div>

              <div class="rb-comment-list">
                @forelse($postComments as $comment)
                  <div class="rb-comment-item">
                    <span class="rb-avatar sm" data-i="{{ rbInitials($comment->user->name ?? 'User') }}" aria-hidden="true"></span>
                    <div class="rb-comment-body">
                      <div class="rb-comment-head">
                        <div style="min-width:0">
                          <p class="rb-comment-name">{{ $comment->user->name ?? 'User' }}</p>
                          <span class="rb-comment-time">{{ rbTimeLabel($comment->created_at) }}@if($comment->created_at) · {{ $comment->created_at->format('H:i') }}@endif</span>
                        </div>
                        @can('delete', $comment)
                          <form method="POST" action="{{ route('team.comments.destroy', $comment) }}">
                            @csrf
                            @method('DELETE')
                            <button class="rb-comment-delete" type="submit" onclick="return confirm('Hapus komentar ini?')" aria-label="Hapus komentar">
                              <svg viewBox="0 0 24 24" fill="none"><path d="M3 6h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
                            </button>
                          </form>
                        @endcan
                      </div>
                      <p class="rb-comment-text">{{ $comment->content }}</p>
                    </div>
                  </div>
                @empty
                  <div class="rb-comment-empty">Belum ada komentar. Jadilah yang pertama berkomentar.</div>
                @endforelse
              </div>
            </div>
          </article>
        @empty
          <div class="rb-empty">
            <b>Belum ada postingan.</b><br>
            Jadilah yang pertama membagikan update atau diskusi di forum Capital Wave.
          </div>
        @endforelse
      </section>

      @if(method_exists($posts, 'links'))
        <div class="rb-pager">{{ $posts->links() }}</div>
      @endif

      <div class="rb-bottom-spacer-local"></div>
      <div class="rb-bottom-spacer"></div>
      @include('partials.bottom-nav')
    </div>
  </main>

  <script>
    (function(){
      const overlay = document.getElementById('composeOverlay');
      const openBtns = ['openComposerBtn','openComposerBtn2','openComposerBtn3','openComposerBtnTab'].map(id => document.getElementById(id)).filter(Boolean);
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
        setTimeout(function(){ if(textarea) textarea.focus(); }, 120);
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
        if(total <= 0){ label.textContent = 'Gambar / PDF / File'; return; }
        if(total === 1){ label.textContent = input.files[0].name; return; }
        label.textContent = total + ' file dipilih';
      }
      function closeToast(){
        if(!toast || !toastWrap) return;
        toast.classList.add('rb-toast-hide');
        setTimeout(function(){ toastWrap.remove(); }, 220);
      }

      openBtns.forEach(b => b.addEventListener('click', openComposer));
      if(closeBtn) closeBtn.addEventListener('click', closeComposer);
      if(cancelBtn) cancelBtn.addEventListener('click', closeComposer);
      if(backdrop) backdrop.addEventListener('click', closeComposer);
      if(input) input.addEventListener('change', updateUploadLabel);
      if(toastClose) toastClose.addEventListener('click', closeToast);
      if(toast && toastWrap) setTimeout(closeToast, 3200);

      document.addEventListener('keydown', function(e){
        if(e.key === 'Escape'){ closeComposer(); closeToast(); }
      });

      @if($errors->any() && old('content'))
        openComposer();
      @endif
    })();

    // ===== Comment toggle =====
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
        if(card) card.classList.toggle('is-comment-open', willOpen);
        if(willOpen){
          setTimeout(function(){ panel.scrollIntoView({ behavior:'smooth', block:'nearest' }); }, 80);
        }
      });
    });

    // klik area kartu buka komentar (kecuali kontrol interaktif)
    document.querySelectorAll('.rb-feed-card').forEach(function(card){
      card.addEventListener('click', function(e){
        if(e.target.closest('button') || e.target.closest('a') || e.target.closest('form') || e.target.closest('textarea') || e.target.closest('input')) return;
        const btn = card.querySelector('[data-comment-toggle]');
        if(btn) btn.click();
      });
    });

    // auto-buka panel komentar setelah kirim komentar
    @if(session('open_comment_post_id'))
      (function(){
        const p = document.getElementById('commentsPanel-{{ session('open_comment_post_id') }}');
        if(p){ p.classList.add('show'); const c = p.closest('.rb-feed-card'); if(c) c.classList.add('is-comment-open'); setTimeout(function(){ p.scrollIntoView({behavior:'smooth',block:'center'}); }, 200); }
      })();
    @endif
  </script>
</body>
</html>
