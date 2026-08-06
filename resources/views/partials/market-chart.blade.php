{{--
  ============================================================================
  RINGKASAN MARKET GLOBAL — candlestick + MA + volume + harga animasi (styling)
  ============================================================================
  Partial reusable & self-contained. Rencana dipakai di halaman MARKET.

  Cara pakai:
    @include('partials.market-chart')

  Sumber angka:
    - Kalau controller mengirim $marketIndex/$marketChangePct/$marketCount/$marketSeed,
      partial pakai itu.
    - Kalau tidak, tapi ada $categories (punya ->products), indeks dihitung otomatis:
        index  = rata-rata (total_profit / price) * 1000   (avg "multiple" potensi)
        change = rata-rata (daily_profit / price) * 100     (avg profit harian %)
        seed   = sum(price)+sum(total_profit)+sum(daily_profit)+count*7
    - Kalau dua-duanya tidak ada, pakai nilai contoh.

  Warna memakai CSS var design system (--navy/--gold/--chart/...) dengan fallback,
  jadi tetap aman kalau di-include di halaman yang belum punya token itu.
--}}
@php
  if (!isset($marketIndex) && isset($categories)) {
      $__all = collect($categories)->flatMap(fn ($c) => $c->products ?? collect());
      $__mult = $__all->map(fn ($p) => (int)($p->price ?? 0) > 0 ? ((int)($p->total_profit ?? 0)) / (int)$p->price : 0)->filter(fn ($m) => $m > 0);
      $__daily = $__all->map(fn ($p) => (int)($p->price ?? 0) > 0 ? ((int)($p->daily_profit ?? 0)) / (int)$p->price * 100 : 0)->filter(fn ($m) => $m > 0);
      $marketIndex     = $__mult->isNotEmpty() ? round($__mult->avg() * 1000, 2) : 1994.00;
      $marketChangePct = $__daily->isNotEmpty() ? round($__daily->avg(), 2) : 1.24;
      $marketCount     = (int) $__all->count();
      $marketSeed      = (int) ((int)$__all->sum('price') + (int)$__all->sum('total_profit') + (int)$__all->sum('daily_profit') + $marketCount * 7) ?: 20260727;
  }

  $marketIndex     = $marketIndex     ?? 1994.00;
  $marketChangePct = $marketChangePct ?? 1.24;
  $marketCount     = $marketCount     ?? 0;
  $marketSeed      = $marketSeed      ?? 20260727;
  $marketUp        = $marketChangePct >= 0;
@endphp

<style>
  /* ============ RINGKASAN MARKET (partial) ============ */
  .cw-market{ position:relative; overflow:hidden; border-radius:24px; padding:16px 16px 12px; background:var(--card,#fff); border:1px solid var(--line,#e9edf4); box-shadow:var(--sh,0 2px 6px rgba(11,39,64,.04),0 12px 30px rgba(11,39,64,.07)); }
  .cw-market-head{ display:flex; align-items:flex-start; justify-content:space-between; gap:12px; }
  .cw-market-eyebrow{ display:flex; align-items:center; gap:7px; color:var(--muted,#8493a6); font-size:10px; font-weight:600; letter-spacing:.12em; text-transform:uppercase; }
  .cw-market-eyebrow::before{ content:""; width:6px; height:6px; border-radius:999px; background:var(--chart,#16a86a); box-shadow:0 0 8px rgba(22,168,106,.7); }
  .cw-market-price{ margin-top:9px; display:flex; align-items:baseline; gap:9px; }
  .cw-market-price #cwPrice{ color:var(--navy,#0b2740); font-size:24px; font-weight:700; letter-spacing:-.03em; font-variant-numeric:tabular-nums; }
  .cw-delta{ display:inline-flex; align-items:center; gap:3px; padding:3px 8px; border-radius:8px; font-size:11px; font-weight:700; }
  .cw-delta.up{ color:var(--chart,#16a86a); background:var(--green-soft,#e6f5ee); }
  .cw-delta.down{ color:var(--red,#dc5757); background:#fdeaea; }
  .cw-delta svg{ width:11px; height:11px; transition:transform .2s ease; }
  .cw-delta.down svg{ transform:scaleY(-1); }
  .cw-market-sub{ margin-top:7px; color:var(--muted,#8493a6); font-size:11px; font-weight:500; }
  .cw-tf{ display:flex; gap:3px; padding:3px; border-radius:11px; background:var(--tint,#f5f8fc); border:1px solid var(--line,#e9edf4); flex:0 0 auto; }
  .cw-tf button{ min-width:30px; height:26px; padding:0 8px; border:0; border-radius:8px; background:transparent; color:var(--muted,#8493a6); font-family:inherit; font-size:10.5px; font-weight:700; cursor:pointer; transition:.15s ease; }
  .cw-tf button.on{ color:var(--navy,#0b2740); background:var(--card,#fff); box-shadow:var(--sh-sm,0 1px 2px rgba(11,39,64,.05)); }
  .cw-chart-wrap{ margin-top:12px; }
  .cw-chart{ width:100%; height:auto; display:block; overflow:visible; }
  .cw-chart .cw-grid{ stroke:var(--line,#e9edf4); stroke-width:1; stroke-dasharray:2 4; }
  .cw-chart .cw-axis{ fill:var(--muted-2,#aab6c4); font-family:'Plus Jakarta Sans',sans-serif; font-size:8px; font-weight:600; }
  .cw-chart .cw-wick{ stroke-width:1.2; }
  .cw-chart .cw-up{ fill:var(--chart,#16a86a); stroke:var(--chart,#16a86a); }
  .cw-chart .cw-down{ fill:var(--red,#dc5757); stroke:var(--red,#dc5757); }
  .cw-chart .cw-vol-up{ fill:var(--chart,#16a86a); opacity:.34; }
  .cw-chart .cw-vol-down{ fill:var(--red,#dc5757); opacity:.32; }
  .cw-chart .cw-ma1{ fill:none; stroke:var(--gold,#c99433); stroke-width:1.8; stroke-linecap:round; stroke-linejoin:round; }
  .cw-chart .cw-ma2{ fill:none; stroke:var(--blue-lite,#2f7fd4); stroke-width:1.6; stroke-linecap:round; stroke-linejoin:round; opacity:.9; }
  .cw-chart .cw-hi-line{ stroke:var(--gold,#c99433); stroke-width:1; stroke-dasharray:4 3; opacity:.8; }
  .cw-chart .cw-hi-tag{ fill:var(--gold-deep,#a9772a); font-family:'Plus Jakarta Sans',sans-serif; font-size:8.5px; font-weight:700; }
  .cw-chart .cw-cur-g{ transition:transform .85s cubic-bezier(.22,.8,.22,1); }
  .cw-chart .cw-cur-line{ stroke:var(--navy,#0b2740); stroke-width:1; stroke-dasharray:3 3; opacity:.55; }
  .cw-chart .cw-cur-tag-bg{ fill:var(--navy,#0b2740); }
  .cw-chart .cw-cur-tag-tx{ fill:#fff; font-family:'Plus Jakarta Sans',sans-serif; font-size:8.5px; font-weight:700; }
  .cw-chart .cw-cur-dot{ fill:var(--chart,#16a86a); stroke:#fff; stroke-width:1.4; animation:cwPulse 1.8s ease-in-out infinite; }
  .cw-candle{ transform-box:fill-box; transform-origin:center bottom; animation:cwCandleIn .5s cubic-bezier(.22,.8,.22,1) both; }
  @keyframes cwCandleIn{ from{ opacity:0; transform:scaleY(.2); } to{ opacity:1; transform:scaleY(1); } }
  @keyframes cwPulse{ 0%,100%{ opacity:.6; } 50%{ opacity:1; } }
  @media (prefers-reduced-motion:reduce){ .cw-candle,.cw-chart .cw-cur-dot,.cw-chart .cw-cur-g{ animation:none !important; transition:none !important; } }
</style>

<section class="cw-market">
  <div class="cw-market-head">
    <div>
      <span class="cw-market-eyebrow">Ringkasan Market</span>
      <div class="cw-market-price">
        <span id="cwPrice">Rp {{ number_format($marketIndex, 2, ',', '.') }}</span>
        <span class="cw-delta {{ $marketUp ? 'up' : 'down' }}" id="cwDelta">
          <svg viewBox="0 0 24 24" fill="none"><path d="M6 15l6-6 6 6" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <span id="cwDeltaTx">{{ ($marketUp ? '+' : '') . number_format($marketChangePct, 2, ',', '.') }}%</span>
        </span>
      </div>
      <div class="cw-market-sub">Indeks gabungan {{ $marketCount }} produk investasi</div>
    </div>
    <div class="cw-tf">
      <button type="button" class="on">1D</button>
      <button type="button">1M</button>
      <button type="button">1Y</button>
    </div>
  </div>
  <div class="cw-chart-wrap">
    <svg class="cw-chart" id="cwChart" viewBox="0 0 340 214"
      data-index="{{ $marketIndex }}"
      data-change="{{ $marketChangePct }}"
      data-seed="{{ $marketSeed }}"></svg>
  </div>
</section>

<script>
  (function(){
    const svg = document.getElementById('cwChart');
    if(!svg) return;

    function seededRandom(seed){
      let value = seed % 2147483647;
      if(value <= 0) value += 2147483646;
      return function(){ value = value * 16807 % 2147483647; return (value - 1) / 2147483646; };
    }

    const fmt2 = v => v.toLocaleString('id-ID', { minimumFractionDigits:2, maximumFractionDigits:2 });
    const fmtY = v => Math.round(v).toLocaleString('id-ID');

    const INDEX  = parseFloat(svg.dataset.index)  || 1994;
    const CHANGE = parseFloat(svg.dataset.change) || 1.24;
    const SEEDV  = parseInt(svg.dataset.seed, 10)  || 20260727;

    const START = INDEX * (1 - Math.min(0.11, Math.max(0.045, Math.abs(CHANGE) / 100 * 5)));
    const VMIN  = START * 0.985, VMAX = INDEX * 1.03;
    const PLOT_T = 10, PLOT_B = 150, X0 = 10, X1 = 296, N = 32;
    const step = (X1 - X0) / (N - 1), cw = step * 0.55;
    const sy = v => PLOT_T + (VMAX - v) / (VMAX - VMIN) * (PLOT_B - PLOT_T);

    const rnd = seededRandom(SEEDV);
    const span = INDEX - START, trend = span / (N - 1), vol = span * 0.14 + INDEX * 0.006;

    const candles = [];
    let price = START;
    for(let i = 0; i < N; i++){
      const open = price;
      let close = open + ((rnd() - 0.5) * vol) + trend;
      if(i === N - 1) close = INDEX;
      close = Math.max(VMIN + span * 0.02, Math.min(VMAX - span * 0.02, close));
      const hi = Math.min(VMAX - 1, Math.max(open, close) + rnd() * vol * 0.35);
      const lo = Math.max(VMIN + 1, Math.min(open, close) - rnd() * vol * 0.35);
      candles.push({ open, close, hi, lo, x: X0 + step * i });
      price = close;
    }

    const parts = [];
    for(let i = 0; i < 6; i++){
      const v = VMAX - (VMAX - VMIN) * (i / 5), y = sy(v);
      parts.push(`<line class="cw-grid" x1="8" y1="${y.toFixed(1)}" x2="298" y2="${y.toFixed(1)}"/>`);
      parts.push(`<text class="cw-axis" x="303" y="${(y + 3).toFixed(1)}">${fmtY(v)}</text>`);
    }
    const volBase = 202;
    candles.forEach(c => {
      const up = c.close >= c.open;
      const h = Math.min(32, 6 + Math.abs(c.close - c.open) / span * 90 + rnd() * 9);
      parts.push(`<rect class="${up ? 'cw-vol-up' : 'cw-vol-down'}" x="${(c.x - cw/2).toFixed(1)}" y="${(volBase - h).toFixed(1)}" width="${cw.toFixed(1)}" height="${h.toFixed(1)}" rx="1"/>`);
    });
    candles.forEach((c, i) => {
      const up = c.close >= c.open, cls = up ? 'cw-up' : 'cw-down';
      const yO = sy(c.open), yC = sy(c.close), yH = sy(c.hi), yL = sy(c.lo);
      const top = Math.min(yO, yC), bh = Math.max(1.6, Math.abs(yC - yO));
      parts.push(`<g class="cw-candle" style="animation-delay:${i * 16}ms">`
        + `<line class="cw-wick ${cls}" x1="${c.x.toFixed(1)}" y1="${yH.toFixed(1)}" x2="${c.x.toFixed(1)}" y2="${yL.toFixed(1)}"/>`
        + `<rect class="${cls}" x="${(c.x - cw/2).toFixed(1)}" y="${top.toFixed(1)}" width="${cw.toFixed(1)}" height="${bh.toFixed(1)}" rx="1.2"/>`
        + `</g>`);
    });
    const ma = p => {
      const pts = [];
      for(let i = 0; i < N; i++){
        let s = 0, c = 0;
        for(let j = Math.max(0, i - p + 1); j <= i; j++){ s += candles[j].close; c++; }
        pts.push([candles[i].x, sy(s / c)]);
      }
      return pts;
    };
    const smooth = pts => {
      let d = `M ${pts[0][0].toFixed(1)} ${pts[0][1].toFixed(1)}`;
      for(let i = 1; i < pts.length; i++){
        const p = pts[i-1], q = pts[i], mx = (p[0] + q[0]) / 2;
        d += ` C ${mx.toFixed(1)} ${p[1].toFixed(1)}, ${mx.toFixed(1)} ${q[1].toFixed(1)}, ${q[0].toFixed(1)} ${q[1].toFixed(1)}`;
      }
      return d;
    };
    parts.push(`<path class="cw-ma2" d="${smooth(ma(12))}"/>`);
    parts.push(`<path class="cw-ma1" d="${smooth(ma(6))}"/>`);

    const hi = Math.max(...candles.map(c => c.hi)), hiY = sy(hi);
    parts.push(`<line class="cw-hi-line" x1="8" y1="${hiY.toFixed(1)}" x2="298" y2="${hiY.toFixed(1)}"/>`);
    parts.push(`<text class="cw-hi-tag" x="10" y="${(hiY - 4).toFixed(1)}">H: ${fmt2(hi)}</text>`);

    ['25 Jun','3 Jul','11 Jul','19 Jul','27 Jul'].forEach((d, i, a) => {
      const x = X0 + (X1 - X0) * (i / (a.length - 1));
      const anchor = i === 0 ? 'start' : (i === a.length - 1 ? 'end' : 'middle');
      parts.push(`<text class="cw-axis" x="${x.toFixed(1)}" y="211" text-anchor="${anchor}">${d}</text>`);
    });

    const baseY = sy(INDEX);
    parts.push(`<g class="cw-cur-g" id="cwCurG">`
      + `<line class="cw-cur-line" x1="8" y1="${baseY.toFixed(1)}" x2="296" y2="${baseY.toFixed(1)}"/>`
      + `<circle class="cw-cur-dot" cx="296" cy="${baseY.toFixed(1)}" r="3.2"/>`
      + `<g transform="translate(300 ${baseY.toFixed(1)})">`
      + `<rect class="cw-cur-tag-bg" x="0" y="-7" width="42" height="14" rx="3"/>`
      + `<text class="cw-cur-tag-tx" id="cwCurTagTx" x="21" y="3" text-anchor="middle">${fmt2(INDEX)}</text>`
      + `</g></g>`);

    svg.innerHTML = parts.join('');

    // animasi harga (styling)
    const priceEl = document.getElementById('cwPrice');
    const deltaEl = document.getElementById('cwDelta');
    const deltaTx = document.getElementById('cwDeltaTx');
    const curG = document.getElementById('cwCurG');
    const tagTx = document.getElementById('cwCurTagTx');
    const BASE = INDEX / (1 + CHANGE / 100);
    const band = INDEX * 0.004;

    if(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    let cur = INDEX;
    const tween = (from, to, dur, cb) => {
      const t0 = performance.now();
      (function f(now){
        const p = Math.min(1, (now - t0) / dur);
        cb(from + (to - from) * (1 - Math.pow(1 - p, 3)));
        if(p < 1) requestAnimationFrame(f);
      })(performance.now());
    };
    const tick = () => {
      const target = INDEX - band + rnd() * band * 2;
      curG.style.transform = `translateY(${(sy(target) - baseY).toFixed(2)}px)`;
      const up = target >= cur;
      deltaEl.classList.toggle('up', (target - BASE) >= 0);
      deltaEl.classList.toggle('down', (target - BASE) < 0);
      priceEl.style.color = up ? 'var(--chart,#16a86a)' : 'var(--red,#dc5757)';
      tween(cur, target, 700, v => {
        priceEl.textContent = 'Rp ' + fmt2(v);
        if(tagTx) tagTx.textContent = fmt2(v);
        deltaTx.textContent = (((v - BASE) / BASE * 100) >= 0 ? '+' : '') + fmt2((v - BASE) / BASE * 100) + '%';
      });
      setTimeout(() => { priceEl.style.color = 'var(--navy,#0b2740)'; }, 850);
      cur = target;
    };
    setTimeout(tick, 900);
    setInterval(tick, 2600);
  })();
</script>
