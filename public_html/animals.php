<?php
$pageTitle = 'Animals';
$pageCss = 'animals.css';
require '../config/db.php';

/* ---------- helpers ---------- */
function an_e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function an_f($row, $keys, $default = '') {
    foreach ((array)$keys as $k) {
        if (isset($row[$k]) && trim((string)$row[$k]) !== '') return trim((string)$row[$k]);
    }
    return $default;
}
function an_cut($s, $n) {
    if (function_exists('mb_strimwidth')) return mb_strimwidth($s, 0, $n, '…', 'UTF-8');
    return strlen($s) > $n ? substr($s, 0, $n - 1) . '…' : $s;
}
function an_icon($name) {
    $p = [
        'paw'    => '<circle cx="6.5" cy="11" r="1.8"/><circle cx="10.5" cy="6.5" r="1.8"/><circle cx="15.5" cy="6.5" r="1.8"/><circle cx="19" cy="11" r="1.8"/><path d="M12.8 12c-3 0-6 3-5.5 6 .4 2 2.4 2.4 5.5 2.4s5.1-.4 5.5-2.4c.5-3-2.5-6-5.5-6z"/>',
        'mammal' => '<path d="M4 15c0-4 3-7 7-7h5c2.5 0 4 1.5 4 3.5V15"/><path d="M4 15v4M8 15v4M16 15v4M20 15v4"/><path d="M4 15h16"/><path d="M19 11.5c1.5 0 2 1 2 2.5"/>',
        'bird'   => '<path d="M3 8c4 0 6 2 8 5 2-3 5-5 10-5-1 5-4 9-10 9-3 0-5-1-8-9z"/><path d="M11 18v3"/>',
        'reptile'=> '<circle cx="7" cy="17" r="3"/><circle cx="17" cy="7" r="3"/><path d="M10 17h3a4 4 0 0 0 4-4v-3"/>',
        'frog'   => '<circle cx="8" cy="8" r="2.5"/><circle cx="16" cy="8" r="2.5"/><path d="M4 14c0-3 3-5 8-5s8 2 8 5-3 6-8 6-8-3-8-6z"/><path d="M9 15c1.7 1 4.3 1 6 0"/>',
        'insect' => '<path d="M12 7v13"/><path d="M12 10C8 3 3 5 4 10c.5 3 5 3 8 0zM12 10c4-7 9-5 8 0-.5 3-5 3-8 0z"/><path d="M12 14c-3 1-6 4-6 6M12 14c3 1 6 4 6 6"/>',
        'search' => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/>',
        'arrow'  => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'refresh'=> '<path d="M20 11a8 8 0 0 0-14-4M4 4v4h4"/><path d="M4 13a8 8 0 0 0 14 4M20 20v-4h-4"/>',
    ];
    return '<svg viewBox="0 0 24 24" aria-hidden="true">' . ($p[$name] ?? '') . '</svg>';
}

$animals = [];
$result = $conn->query("SELECT * FROM animals WHERE status = 'published' ORDER BY name ASC");
if ($result) { while ($row = $result->fetch_assoc()) { $animals[] = $row; } }

require '../includes/header.php';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Lato:wght@400;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap');

/* =====================================================
   Our Animals — Safari theme (classes prefixed "an-")
   ===================================================== */
.an-page {
  --sw-white:  #FFFDF8;
  --sw-orange: #E8A15B;
  --sw-forest: #173B2A;
  --sw-dark:   #0B2F22;
  --sw-gold:   #C89B3C;
  --sw-text:   #2B241D;
  background: var(--sw-white);
  color: var(--sw-text);
  font-family: 'Lato', 'Segoe UI', sans-serif;
  overflow: hidden;
}
.an-page, .an-page * { box-sizing: border-box; }
.an-page h1, .an-page h2, .an-page h3, .an-page p { margin: 0; }
.an-page svg { display: block; }
.an-wrap { max-width: 1240px; margin: 0 auto; padding: 0 clamp(16px, 2.5vw, 36px); position: relative; }
.an-script { font-family: 'Dancing Script', 'Playfair Display', cursive; font-weight: 700; color: var(--sw-orange); }

/* ---------- banner (plain image) ---------- */
.an-banner { position: relative; width: 100%; height: clamp(220px, 32vw, 520px); overflow: hidden; background: #0B2F22; }
.an-banner img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center; }
.an-leaf { position: absolute; pointer-events: none; }
.an-leaf svg { width: 100%; height: 100%; }

/* ---------- featured heading + search ---------- */
.an-sec { padding: clamp(10px, 2vw, 22px) 0 clamp(36px, 4vw, 56px); }
.an-top { display: flex; align-items: flex-end; justify-content: space-between; gap: 24px; flex-wrap: wrap; position: relative; }
.an-kicker {
  display: flex; align-items: center; gap: 12px; margin-bottom: 8px;
  font-size: .68rem; font-weight: 700; letter-spacing: .24em; text-transform: uppercase; color: var(--sw-forest);
}
.an-kicker::before, .an-kicker::after { content: ""; width: 34px; height: 2px; background: var(--sw-gold); }
.an-kicker::after { background: linear-gradient(90deg, var(--sw-gold), rgba(200,155,60,0)); }
.an-top h2 { font-family: 'Playfair Display', Georgia, serif; font-size: clamp(1.8rem, 3vw, 2.4rem); font-weight: 700; line-height: 1.1; color: var(--sw-forest); }
.an-top h2 .an-script { font-size: 1.15em; margin-left: 6px; }
.an-sub { margin-top: 10px; font-size: .88rem; color: rgba(43,36,29,.72); }
.an-search {
  position: relative; z-index: 1; display: flex; align-items: center; gap: 10px;
  width: min(100%, 320px); padding: 0 18px; height: 42px;
  background: var(--sw-white); border: 1px solid rgba(200,155,60,.45); border-radius: 999px;
  box-shadow: 0 6px 16px rgba(23,59,42,.08);
}
.an-search:focus-within { border-color: var(--sw-gold); box-shadow: 0 0 0 3px rgba(232,161,91,.3); }
.an-search svg { width: 18px; height: 18px; flex: 0 0 auto; fill: none; stroke: var(--sw-forest); stroke-width: 2; stroke-linecap: round; }
.an-search input { flex: 1 1 auto; min-width: 0; border: 0; outline: 0; background: transparent; font: inherit; font-size: .84rem; color: var(--sw-text); }
.an-top .an-leaf { right: -22px; top: -26px; width: 58px; height: 88px; opacity: .8; }

/* ---------- chips ---------- */
.an-chips { display: flex; flex-wrap: wrap; gap: 12px; margin: 26px 0 28px; }
.an-chip {
  display: inline-flex; align-items: center; gap: 9px; padding: 0 22px; height: 42px; cursor: pointer;
  font: inherit; font-size: .84rem; font-weight: 700; color: var(--sw-forest);
  background: var(--sw-white); border: 1px solid rgba(200,155,60,.35); border-radius: 999px;
  box-shadow: 0 4px 12px rgba(23,59,42,.08);
  transition: background .25s, color .25s, transform .25s, border-color .25s;
}
.an-chip svg { width: 18px; height: 18px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
.an-chip:hover { transform: translateY(-2px); border-color: var(--sw-gold); }
.an-chip.is-active { background: var(--sw-forest); border-color: var(--sw-forest); color: var(--sw-white); }
.an-chip:focus-visible, .an-more:focus-visible, .an-card a:focus-visible { outline: 3px solid var(--sw-orange); outline-offset: 3px; }

/* ---------- cards ---------- */
.an-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: clamp(18px, 2.4vw, 30px); }
.an-card {
  position: relative; display: flex; flex-direction: column; overflow: hidden;
  background: var(--sw-white); border: 1px solid rgba(200,155,60,.28); border-radius: 14px;
  box-shadow: 0 8px 24px rgba(23,59,42,.12);
  transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
}
.an-card[hidden] { display: none; }
.an-card:hover { transform: translateY(-4px); border-color: var(--sw-gold); box-shadow: 0 14px 32px rgba(23,59,42,.2); }
.an-card-img { position: relative; aspect-ratio: 16 / 10.5; background: linear-gradient(160deg, #2E5B44, #173B2A); overflow: hidden; }
.an-card-img img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; transition: transform .5s ease; }
.an-card:hover .an-card-img img { transform: scale(1.05); }
.an-tag {
  position: absolute; z-index: 2; left: 18px; bottom: -14px; display: inline-flex; align-items: center; gap: 6px;
  padding: 6px 14px; font-size: .72rem; font-weight: 700; color: var(--sw-forest);
  background: var(--sw-white); border-radius: 999px; box-shadow: 0 4px 12px rgba(23,59,42,.18);
}
.an-tag svg { width: 13px; height: 13px; fill: var(--sw-forest); stroke: none; }
.an-card-body { display: flex; flex-direction: column; flex: 1 1 auto; padding: 26px 20px 18px; }
.an-card h3 { font-family: 'Playfair Display', Georgia, serif; font-size: 1.12rem; font-weight: 700; color: var(--sw-forest); }
.an-card p { margin: 8px 0 16px; font-size: .82rem; line-height: 1.6; color: rgba(43,36,29,.72); }
.an-card-foot { display: flex; align-items: center; justify-content: space-between; margin-top: auto; }
.an-card-foot a { display: inline-flex; align-items: center; gap: 10px; font-size: .84rem; font-weight: 700; color: var(--sw-forest); text-decoration: none; }
.an-card-foot a::after { content: ""; position: absolute; inset: 0; z-index: 1; }   /* whole card is clickable */
.an-card-foot svg { width: 15px; height: 15px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
.an-go { width: 28px; height: 28px; border-radius: 50%; display: grid; place-items: center; background: #F0D9A8; color: var(--sw-forest); transition: background .25s, color .25s; }
.an-go svg { width: 14px; height: 14px; }
.an-card:hover .an-go { background: var(--sw-forest); color: var(--sw-orange); }

.an-empty { grid-column: 1 / -1; padding: 40px 16px; text-align: center; font-size: .95rem; color: rgba(43,36,29,.72); }
.an-empty[hidden] { display: none; }

/* ---------- load more ---------- */
.an-more-wrap { display: flex; align-items: center; justify-content: center; gap: 16px; margin-top: 44px; }
.an-more-wrap[hidden] { display: none; }
.an-more-wrap::before, .an-more-wrap::after { content: ""; flex: 0 1 140px; height: 1px; background: linear-gradient(90deg, rgba(157,176,138,0), #9DB08A); }
.an-more-wrap::after { transform: scaleX(-1); }
.an-more {
  display: inline-flex; align-items: center; gap: 10px; padding: 0 26px; height: 42px; cursor: pointer;
  font: inherit; font-size: .84rem; font-weight: 700; color: var(--sw-forest);
  background: #EEF1E3; border: 1px solid rgba(23,59,42,.18); border-radius: 999px;
  transition: background .25s, color .25s, transform .25s;
}
.an-more svg { width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
.an-more:hover { background: var(--sw-forest); color: var(--sw-white); transform: translateY(-2px); }

/* ---------- responsive ---------- */
@media (max-width: 900px) {
  .an-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  .an-top .an-leaf { display: none; }
}
@media (max-width: 600px) {
  .an-grid { grid-template-columns: minmax(0, 1fr); }
  .an-search { width: 100%; }
  .an-chips { gap: 8px; }
  .an-chip { padding: 0 16px; }
  .an-more-wrap::before, .an-more-wrap::after { display: none; }
}
@media (prefers-reduced-motion: reduce) {
  .an-page *, .an-page *::before, .an-page *::after { transition: none !important; }
}
</style>

<svg width="0" height="0" style="position:absolute" aria-hidden="true">
  <symbol id="anSprig" viewBox="0 0 80 120">
    <path d="M40 118 C40 80 42 40 40 4" stroke="#9DB08A" stroke-width="2" fill="none" stroke-linecap="round"/>
    <g fill="#B7C7A3">
      <path d="M40 100 C20 96 8 82 6 66 C24 68 38 80 40 100z"/>
      <path d="M40 84 C60 80 72 66 74 50 C56 52 42 64 40 84z"/>
      <path d="M40 62 C22 58 12 46 10 32 C26 34 38 46 40 62z"/>
      <path d="M40 44 C58 40 68 28 70 14 C54 16 42 28 40 44z"/>
      <path d="M40 24 C32 20 28 12 28 4 C36 6 40 14 40 24z"/>
    </g>
  </symbol>
</svg>

<main class="an-page">

  <!-- ============ BANNER (image only) ============ -->
  <div class="an-banner">
    <img src="../assets/images/static/animals-banner.webp" alt="Wildlife Kingdom elephant orphanage nursery" onerror="this.style.display='none'">
  </div>

  <!-- ============ FEATURED ANIMALS ============ -->
  <section class="an-sec" id="animals">
    <div class="an-wrap">

      <div class="an-top">
        <div>
          <span class="an-kicker">Wildlife Kingdom</span>
          <h2>Featured <span class="an-script">Animals</span></h2>
          <p class="an-sub">Meet some of the most fascinating creatures that call Wildlife Kingdom home.</p>
        </div>
        <label class="an-search">
          <?= an_icon('search') ?>
          <input type="search" id="anSearch" placeholder="Search animals..." aria-label="Search animals" autocomplete="off">
        </label>
        <span class="an-leaf" aria-hidden="true"><svg><use href="#anSprig"/></svg></span>
      </div>

      <div class="an-chips" role="group" aria-label="Filter by animal type">
        <button type="button" class="an-chip is-active" data-filter="all"><?= an_icon('paw') ?>All Animals</button>
        <button type="button" class="an-chip" data-filter="mammal"><?= an_icon('mammal') ?>Mammals</button>
        <button type="button" class="an-chip" data-filter="bird"><?= an_icon('bird') ?>Birds</button>
        <button type="button" class="an-chip" data-filter="reptile"><?= an_icon('reptile') ?>Reptiles</button>
        <button type="button" class="an-chip" data-filter="amphibian"><?= an_icon('frog') ?>Amphibians</button>
        <button type="button" class="an-chip" data-filter="insect"><?= an_icon('insect') ?>Insects</button>
      </div>

      <div class="an-grid" id="anGrid">
        <?php if (count($animals) > 0): ?>
          <?php foreach ($animals as $animal):
              $cat    = an_f($animal, ['category', 'class', 'animal_type'], 'Wildlife');
              $catKey = strtolower(rtrim($cat, 'sS'));
              $text   = an_f($animal, ['short_description', 'summary', 'description']);
              $search = strtolower($animal['name'] . ' ' . an_f($animal, 'scientific_name') . ' ' . $cat);
          ?>
            <article class="an-card" data-cat="<?= an_e($catKey) ?>" data-name="<?= an_e($search) ?>">
              <div class="an-card-img">
                <?php if (!empty($animal['image'])): ?>
                  <img src="../uploads/animals/<?= an_e(rawurlencode($animal['image'])) ?>"
                       alt="<?= an_e($animal['name']) ?>" loading="lazy" onerror="this.style.display='none'">
                <?php endif; ?>
                <span class="an-tag"><?= an_icon('paw') ?><?= an_e($cat) ?></span>
              </div>
              <div class="an-card-body">
                <h3><?= an_e($animal['name']) ?></h3>
                <?php if ($text !== ''): ?><p><?= an_e(an_cut($text, 100)) ?></p><?php endif; ?>
                <div class="an-card-foot">
                  <a href="animal-details.php?id=<?= (int)$animal['id'] ?>">View Details <?= an_icon('arrow') ?></a>
                  <span class="an-go" aria-hidden="true"><?= an_icon('arrow') ?></span>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
          <p class="an-empty" id="anEmpty" hidden>No animals match your search. Try another name or choose All Animals.</p>
        <?php else: ?>
          <p class="an-empty">No animals added yet. Add some from the admin panel soon!</p>
        <?php endif; ?>
      </div>

      <?php if (count($animals) > 0): ?>
        <div class="an-more-wrap" id="anMoreWrap">
          <button type="button" class="an-more" id="anMore"><?= an_icon('refresh') ?>Load More Animals</button>
        </div>
      <?php endif; ?>

    </div>
  </section>

</main>

<script>
(function () {
  var cards = [].slice.call(document.querySelectorAll('.an-card'));
  if (!cards.length) return;
  var chips = [].slice.call(document.querySelectorAll('.an-chip'));
  var input = document.getElementById('anSearch');
  var empty = document.getElementById('anEmpty');
  var moreWrap = document.getElementById('anMoreWrap');
  var moreBtn = document.getElementById('anMore');
  var STEP = 6, shown = STEP, cat = 'all', q = '';

  function render() {
    var matches = cards.filter(function (c) {
      return (cat === 'all' || c.getAttribute('data-cat') === cat) &&
             (q === '' || c.getAttribute('data-name').indexOf(q) > -1);
    });
    cards.forEach(function (c) { c.hidden = true; });
    matches.forEach(function (c, i) { c.hidden = i >= shown; });
    empty.hidden = matches.length > 0;
    moreWrap.hidden = matches.length <= shown;
  }

  chips.forEach(function (chip) {
    chip.addEventListener('click', function () {
      chips.forEach(function (c) { c.classList.remove('is-active'); });
      chip.classList.add('is-active');
      cat = chip.getAttribute('data-filter');
      shown = STEP;
      render();
    });
  });
  input.addEventListener('input', function () {
    q = input.value.trim().toLowerCase();
    shown = STEP;
    render();
  });
  moreBtn.addEventListener('click', function () { shown += STEP; render(); });

  render();
})();
</script>

<?php require '../includes/footer.php'; ?>