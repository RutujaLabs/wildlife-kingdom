<?php
$pageTitle = 'Habitats';
$pageCss = 'pages.css';
require '../includes/header.php';
require '../config/db.php';

/* ---- Fetch habitats (published only; falls back if columns are missing) ---- */
$habitats = [];
$queries = [
  "SELECT * FROM habitats WHERE status = 'published' ORDER BY display_order ASC, name ASC",
  "SELECT * FROM habitats ORDER BY name ASC",
];
foreach ($queries as $sql) {
  try {
    $result = $conn->query($sql);
    if ($result) { while ($row = $result->fetch_assoc()) { $habitats[] = $row; } break; }
  } catch (Throwable $e) { /* try next query */ }
}

/* Returns the image URL only if the file really exists, otherwise '' (shows a placeholder) */
function hb_img($file) {
  $file = basename((string)$file);
  if ($file === '') return '';
  return is_file(__DIR__ . '/../uploads/habitats/' . $file) ? '../uploads/habitats/' . rawurlencode($file) : '';
}
function hb_e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap');

.hb-page {
  --sw-white:#FFFDF8; --sw-orange:#E8A15B; --sw-forest:#173B2A; --sw-dark:#0B2F22; --sw-gold:#C89B3C; --sw-text:#2B241D;
  font-family:'Lato','Segoe UI',sans-serif; color:var(--sw-text);
}
.hb-page, .hb-page * { box-sizing:border-box; }
.hb-wrap { width:100%; max-width:1400px; margin-inline:auto; padding-inline:clamp(14px,2.2vw,32px); }
.hb-page h1, .hb-page h2, .hb-page h3 { font-family:'Playfair Display',Georgia,serif; margin:0; }

/* ================= BANNER ================= */
.hb-sr { position:absolute; width:1px; height:1px; overflow:hidden; clip:rect(0 0 0 0); white-space:nowrap; }
.hb-banner { position:relative; overflow:hidden; color:var(--sw-white); background:linear-gradient(135deg,#0B2F22 0%,#173B2A 55%,#2E5B44 100%); height:500px; }
@media (max-width:560px) { .hb-banner { height:300px; } }
.hb-banner-photo { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; }
.hb-btn { display:inline-flex; align-items:center; gap:8px; padding:10px 24px; border-radius:999px; font-weight:700; font-size:.9rem; text-decoration:none; border:2px solid transparent; transition:background .25s,color .25s,border-color .25s,transform .25s; }
.hb-btn:hover { transform:translateY(-2px); }
.hb-btn-gold { background:var(--sw-gold); color:var(--sw-forest); border-color:var(--sw-gold); }
.hb-btn-gold:hover { background:var(--sw-white); border-color:var(--sw-white); }
.hb-btn-dark { background:var(--sw-forest); color:var(--sw-white); border-color:var(--sw-forest); }
.hb-btn-dark:hover { background:var(--sw-gold); border-color:var(--sw-gold); color:var(--sw-forest); }
.hb-btn-line { color:var(--sw-white); border-color:var(--sw-white); }
.hb-btn-line:hover { background:var(--sw-white); color:var(--sw-forest); }

/* ================= ZONES (cards) ================= */
.hb-zones { background:var(--sw-white); padding:clamp(28px,3.5vw,44px) 0; }
.hb-head { text-align:center; margin-bottom:22px; }
.hb-head h2 { font-size:clamp(1.5rem,2.6vw,2rem); color:var(--sw-forest); }
.hb-head h2::after { content:""; display:block; width:56px; height:3px; margin:10px auto 0; background:var(--sw-gold); border-radius:2px; }
.hb-head p { max-width:none; width:100%; margin:10px 0 0; line-height:1.6; }
.hb-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:20px; }
.hb-card { position:relative; display:block; aspect-ratio:16/10; border-radius:14px; overflow:hidden; text-decoration:none; background:linear-gradient(160deg,#2E5B44,#0B2F22); border:1px solid rgba(200,155,60,.55); box-shadow:0 8px 22px rgba(23,59,42,.16); transition:transform .3s,box-shadow .3s,border-color .3s; }
.hb-card:hover { transform:translateY(-4px); border-color:var(--sw-gold); box-shadow:0 16px 34px rgba(23,59,42,.26); }
.hb-card img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; transition:transform .6s; }
.hb-card:hover img { transform:scale(1.06); }
.hb-card-ph { position:absolute; inset:0; display:flex; align-items:center; justify-content:center; font-size:2.6rem; opacity:.5; }
.hb-card-info { position:absolute; left:0; right:0; bottom:0; padding:38px 18px 14px; background:linear-gradient(to top, rgba(11,47,34,.94), transparent); color:var(--sw-white); }
.hb-card-info h3 { font-size:1.2rem; color:var(--sw-white); }
.hb-card-info span { display:block; margin-top:3px; font-size:.84rem; color:var(--sw-orange); }
.hb-card-go { position:absolute; top:12px; right:12px; width:34px; height:34px; border-radius:50%; background:rgba(255,253,248,.92); color:var(--sw-forest); display:flex; align-items:center; justify-content:center; font-weight:700; transition:background .25s,transform .25s; }
.hb-card:hover .hb-card-go { background:var(--sw-gold); transform:translateY(3px); }

/* ================= DETAIL SECTIONS: white / green / white ================= */
.hb-detail { padding:clamp(26px,3.2vw,40px) 0; scroll-margin-top:70px; }
.hb-detail.is-light { background:var(--sw-white); color:var(--sw-text); }
.hb-detail.is-green { background:var(--sw-forest); color:var(--sw-white); }
.hb-row { display:grid; grid-template-columns:minmax(0,5fr) minmax(0,6fr); gap:clamp(20px,3.5vw,48px); align-items:center; }

.hb-media { margin:0; }
.hb-frame { position:relative; aspect-ratio:16/10; border-radius:80px 16px 80px 16px; overflow:hidden; background:linear-gradient(160deg,#FBE9CC,#F3C48C); box-shadow:0 16px 36px rgba(11,47,34,.28); }
.hb-frame::after { content:""; position:absolute; inset:9px; z-index:2; pointer-events:none; border:1.5px solid rgba(200,155,60,.9); border-radius:72px 9px 72px 9px; }
.hb-frame img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; }
.hb-frame-ph { position:absolute; inset:0; display:flex; flex-direction:column; gap:4px; align-items:center; justify-content:center; color:var(--sw-forest); font-weight:700; }
.hb-frame-ph b { font-size:2.6rem; font-weight:400; }
.hb-media figcaption { margin-top:8px; font-size:.8rem; line-height:1.4; text-align:center; opacity:.85; }

.hb-text h2 { font-size:clamp(1.6rem,2.8vw,2.2rem); line-height:1.15; }
.hb-light-h, .is-light .hb-text h2 { color:var(--sw-forest); }
.is-green .hb-text h2 { color:var(--sw-white); }
.hb-text h2::after { content:""; display:block; width:56px; height:3px; margin-top:10px; background:var(--sw-gold); border-radius:2px; }
.hb-tag { margin:10px 0 0; font-style:italic; font-size:1.02rem; }
.is-light .hb-tag { color:var(--sw-forest); }
.is-green .hb-tag { color:var(--sw-orange); }
.hb-desc { margin:10px 0 0; line-height:1.7; font-size:.98rem; max-width:68ch; }
.is-green .hb-desc { color:rgba(255,253,248,.92); }
.hb-facts { display:flex; flex-wrap:wrap; gap:10px; margin:16px 0 0; padding:0; list-style:none; }
.hb-facts li { padding:8px 14px; border-radius:10px; background:var(--sw-white); border:1px solid rgba(200,155,60,.7); min-width:130px; }
.hb-facts small { display:block; font-size:.72rem; color:var(--sw-forest); opacity:.75; }
.hb-facts strong { display:block; margin-top:1px; font-size:.92rem; color:var(--sw-forest); }
.hb-text .hb-btn { margin-top:18px; }
.is-green .hb-btn-dark { background:var(--sw-gold); border-color:var(--sw-gold); color:var(--sw-forest); }
.is-green .hb-btn-dark:hover { background:var(--sw-white); border-color:var(--sw-white); }

/* ================= EMPTY + CTA ================= */
.hb-empty { grid-column:1/-1; text-align:center; padding:36px 20px; border:2px dashed rgba(200,155,60,.6); border-radius:14px; background:#fff; }

@media (max-width:980px) { .hb-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
@media (max-width:820px) {
  .hb-row { grid-template-columns:1fr; }
  .hb-frame { border-radius:56px 12px 56px 12px; }
  .hb-frame::after { border-radius:48px 7px 48px 7px; }
}
@media (max-width:560px) { .hb-grid { grid-template-columns:1fr; } }
@media (prefers-reduced-motion:reduce) { .hb-page * { transition:none !important; } }
</style>

<div class="hb-page">

  <!-- ============ BANNER ============ -->
  <section class="hb-banner">
    <!-- BANNER IMAGE: jab image ready ho, neeche wali line uncomment karke file ka naam badal do (1920 x 500 px) -->
    <!-- <img class="hb-banner-photo" src="../assets/images/static/YOUR-BANNER-IMAGE.webp" alt="Wildlife Kingdom habitats"> -->
    <h1 class="hb-sr">Our Habitats</h1>
  </section>

  <!-- ============ HABITAT CARDS ============ -->
  <section class="hb-zones" id="zones">
    <div class="hb-wrap">
      <div class="hb-head">
        <h2>Choose Your Habitat</h2>
        <p>Pick a zone to learn about its climate, landscape and the wildlife that calls it home.</p>
      </div>
      <div class="hb-grid">
        <?php if (count($habitats) > 0): ?>
          <?php foreach ($habitats as $habitat): $img = hb_img($habitat['image'] ?? ''); ?>
            <a href="#habitat-<?php echo (int)$habitat['id']; ?>" class="hb-card">
              <?php if ($img): ?>
                <img src="<?php echo hb_e($img); ?>" alt="<?php echo hb_e(($habitat['image_alt_text'] ?? '') ?: $habitat['name']); ?>" loading="lazy">
              <?php else: ?>
                <div class="hb-card-ph" aria-hidden="true">🌿</div>
              <?php endif; ?>
              <span class="hb-card-go" aria-hidden="true">↓</span>
              <div class="hb-card-info">
                <h3><?php echo hb_e($habitat['name']); ?></h3>
                <?php $sub = ($habitat['climate'] ?? '') ?: ($habitat['short_description'] ?? ''); if ($sub): ?>
                  <span><?php echo hb_e($sub); ?></span>
                <?php endif; ?>
              </div>
            </a>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="hb-empty">
            <h3 style="color:var(--sw-forest);">No habitats yet</h3>
            <p>New habitats are being prepared. Please check back soon.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- ============ HABITAT DETAILS ============ -->
  <?php $i = 0; foreach ($habitats as $habitat): $i++;
    $img     = hb_img($habitat['image'] ?? '');
    $climate = trim((string)($habitat['climate'] ?? ''));
    $loc     = trim((string)($habitat['location'] ?? ''));
    $range   = trim((string)($habitat['geographical_range'] ?? ''));
    $short   = trim((string)($habitat['short_description'] ?? ''));
    $caption = trim((string)($habitat['image_caption'] ?? ''));
    $credit  = trim((string)($habitat['image_credit'] ?? ''));
  ?>
  <section id="habitat-<?php echo (int)$habitat['id']; ?>" class="hb-detail <?php echo ($i % 2 === 1) ? 'is-green' : 'is-light'; ?>">
    <div class="hb-wrap hb-row">
      <figure class="hb-media">
        <div class="hb-frame">
          <?php if ($img): ?>
            <img src="<?php echo hb_e($img); ?>" alt="<?php echo hb_e(($habitat['image_alt_text'] ?? '') ?: $habitat['name']); ?>" loading="lazy">
          <?php else: ?>
            <div class="hb-frame-ph" aria-hidden="true"><b>🌿</b><span>Photo coming soon</span></div>
          <?php endif; ?>
        </div>
        <?php if ($caption || $credit): ?>
          <figcaption><?php echo hb_e($caption); ?><?php if ($caption && $credit) echo ' — '; ?><?php if ($credit) echo 'Photo: ' . hb_e($credit); ?></figcaption>
        <?php endif; ?>
      </figure>

      <div class="hb-text">
        <h2><?php echo hb_e($habitat['name']); ?></h2>
        <?php if ($short): ?><p class="hb-tag"><?php echo hb_e($short); ?></p><?php endif; ?>
        <p class="hb-desc"><?php echo nl2br(hb_e($habitat['description'] ?? '')); ?></p>

        <?php if ($climate || $loc || $range): ?>
          <ul class="hb-facts">
            <?php if ($climate): ?><li><small>Climate</small><strong><?php echo hb_e($climate); ?></strong></li><?php endif; ?>
            <?php if ($loc): ?><li><small>Location</small><strong><?php echo hb_e($loc); ?></strong></li><?php endif; ?>
            <?php if ($range): ?><li style="flex:1 1 100%;"><small>Geographical range</small><strong><?php echo hb_e($range); ?></strong></li><?php endif; ?>
          </ul>
        <?php endif; ?>

        <a href="animals.php" class="hb-btn hb-btn-dark">See the animals</a>
      </div>
    </div>
  </section>
  <?php endforeach; ?>

</div>

<?php require '../includes/footer.php'; ?>