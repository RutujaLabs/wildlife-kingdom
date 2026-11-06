<?php
// ================= SEO SETTINGS (edit here) =================
$pageTitle       = 'Tickets';
$seoTitle        = 'Wildlife Kingdom Tickets | Entry Prices & Visit Passes';
$seoDescription  = 'Buy tickets for Wildlife Kingdom. Compare entry prices and visit passes for adults, children and families, and plan your wildlife day out.';
$seoKeywords     = 'wildlife kingdom tickets, zoo tickets, entry prices, visit passes, family tickets, book zoo tickets, wildlife park admission';
$seoPublisher    = 'Wildlife Kingdom';
$seoRobots       = 'index, follow, max-image-preview:large';

// Canonical URL (current page, without query string)
$seoScheme    = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$seoCanonical = $seoScheme . '://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?');

// X-Robots-Tag HTTP header
header('X-Robots-Tag: ' . $seoRobots);

// Output buffer: injects/fixes SEO tags in <head> without touching header.php
ob_start(function ($html) use ($seoTitle, $seoDescription, $seoKeywords, $seoPublisher, $seoRobots, $seoCanonical) {
    if (stripos($html, '</head>') === false) { return $html; }
    $e = function ($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); };

    // remove existing tags so nothing is duplicated
    $html = preg_replace('#<title>.*?</title>#is', '', $html, 1);
    $html = preg_replace('#<meta\s+name=["\'](description|keywords|robots|publisher)["\'][^>]*>\s*#i', '', $html);
    $html = preg_replace('#<link\s+rel=["\']canonical["\'][^>]*>\s*#i', '', $html);
    $html = preg_replace('#<link\s+rel=["\']publisher["\'][^>]*>\s*#i', '', $html);

    $tags  = "<title>" . $e($seoTitle) . "</title>\n";
    $tags .= '<meta name="description" content="' . $e($seoDescription) . "\">\n";
    $tags .= '<link rel="canonical" href="' . $e($seoCanonical) . "\">\n";
    $tags .= '<meta name="robots" content="' . $e($seoRobots) . "\">\n";
    $tags .= '<meta name="keywords" content="' . $e($seoKeywords) . "\">\n";
    $tags .= '<meta name="publisher" content="' . $e($seoPublisher) . "\">\n";
    $tags .= '<meta property="og:title" content="' . $e($seoTitle) . "\">\n";
    $tags .= '<meta property="og:description" content="' . $e($seoDescription) . "\">\n";
    $tags .= '<meta property="og:url" content="' . $e($seoCanonical) . "\">\n";
    $tags .= "<meta property=\"og:type\" content=\"website\">\n";

    return preg_replace('#</head>#i', $tags . '</head>', $html, 1);
});
// ============================================================

require '../includes/header.php';
require '../config/db.php';

$tickets = [];
$result = $conn->query("SELECT * FROM tickets WHERE status = 'active' ORDER BY price ASC");
if ($result) { while ($row = $result->fetch_assoc()) { $tickets[] = $row; } }


/* ---- Admin se upload ki hui ticket image ka URL nikaalo ----
   Admin panel (Laravel) DB me path aise save karta hai:  storage/tickets/abc.jpg  (ya uploads/tickets/abc.jpg)
   Yahan project ke saare folders me us file ko dhundha jata hai (Laravel folder ka naam kuch bhi ho, auto-detect hota hai).
   Agar kahin nahi mili to default image dikhegi. */

/* Agar Laravel admin alag server par chalta hai (jaise php artisan serve), to uska URL yahan likho.
   Example:  $tkLaravelUrl = 'http://localhost:8000/';      Same folder/XAMPP me ho to khali chhod do. */
$tkLaravelUrl = '';

function tkAdminImage($img, &$debug = null) {
  global $tkLaravelUrl;
  $debug = [];
  $img = trim((string) $img);
  if ($img === '') return '';
  if (preg_match('#^(https?:)?//#i', $img) || preg_match('#^data:image/#i', $img)) return $img;

  $raw = ltrim(str_replace('\\', '/', $img), '/');
  if (strpos($raw, '..') !== false) return '';
  if (!preg_match('#\.(jpe?g|png|webp|gif)$#i', $raw)) return '';

  $stripped = preg_replace('#^(app/)?(public/|storage/)+#', '', $raw);   // storage/tickets/x.jpg -> tickets/x.jpg
  $names = array_values(array_unique([$raw, $stripped, basename($raw)]));

  $roots = ['../laravel/public/', '../laravel/public/storage/', '../laravel/storage/app/public/'];
  foreach (glob(__DIR__ . '/../*', GLOB_ONLYDIR) ?: [] as $d) {            // koi bhi Laravel folder (admin, backend, ...)
    $n = basename($d);
    $roots[] = "../$n/public/";
    $roots[] = "../$n/public/storage/";
    $roots[] = "../$n/storage/app/public/";
  }
  $roots = array_merge($roots, [
    '../public/', '../public/storage/', '../storage/app/public/',
    '../assets/images/tickets/', '../assets/images/static/',
    '../uploads/tickets/', '../uploads/',
  ]);

  foreach (array_unique($roots) as $f) {
    foreach ($names as $name) {
      $disk = __DIR__ . '/' . $f . $name;
      $debug[] = $f . $name;
      if (is_file($disk)) return $f . $name . '?v=' . filemtime($disk);
    }
  }

  // File is server par nahi mili: agar Laravel ka URL diya hai to wahi se image lo
  if ($tkLaravelUrl !== '') return rtrim($tkLaravelUrl, '/') . '/' . $raw;
  return '';
}

/* Order: Adult, Child, Family Pass (baaki tickets price ke hisaab se baad mein) */
$order = ['adult' => 1, 'child' => 2, 'family pass' => 3];
usort($tickets, function ($a, $b) use ($order) {
  $oa = $order[strtolower(trim($a['ticket_type']))] ?? 99;
  $ob = $order[strtolower(trim($b['ticket_type']))] ?? 99;
  return $oa === $ob ? $a['price'] <=> $b['price'] : $oa <=> $ob;
});

/* ---- Banner image (automatic) ----
   Bas image is folder me rakh do:  <project>/assets/images/static/
   Naam yahi hona chahiye:  tickets-banner.webp  (ya .jpg / .jpeg / .png)
   File milte hi banner me dikhne lagti hai, code badalne ki zaroorat nahi.

   $bannerFixedHeight = 0   -> image POORI dikhti hai (na crop, na khali jagah), height image ke hisaab se apne aap.
   $bannerFixedHeight = 500 -> banner 500px fix, image poori dikhti hai (contain) aur side me same image ka blur fill (koi khali jagah nahi). */
$bannerFixedHeight = 500;
/* $bannerFit: 'cover' = full width, side me ZERO space, upar-neeche thoda crop (recommended)
              'fill'  = poori image bina crop, par stretch hoti hai (distort)
   $bannerFocus: cover me kaunsa hissa dikhe, jaise 'center center' / 'center top' / 'center 30%' */
$bannerFit = 'cover';
$bannerFocus = 'center center';
$bannerSrc = ''; $bannerW = 0; $bannerH = 0;
foreach (['tickets-banner.webp', 'tickets-banner.jpg', 'tickets-banner.jpeg', 'tickets-banner.png'] as $bf) {
  $bp = __DIR__ . '/../assets/images/static/' . $bf;
  if (is_file($bp)) {
    $bannerSrc = '../assets/images/static/' . $bf . '?v=' . filemtime($bp);
    $sz = @getimagesize($bp);
    if ($sz && $sz[0] > 0 && $sz[1] > 0) { $bannerW = (int)$sz[0]; $bannerH = (int)$sz[1]; }
    break;
  }
}

/* ---- Card images (apni image files yahan set karo) ---- */
$ticketImages = [
  '../assets/images/static/adult.png',
  '../assets/images/static/child.png',
  '../assets/images/static/familypass.png',
];

/* ---- Card icons (SVG) : person, child, family ---- */
$ticketIcons = [
  '<svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7z"/></svg>',
  '<svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="3"/><path d="M5 9l7 2 7-2v2.2l-5 1.6V15l2.5 6h-2.4L12 16.5 9.9 21H7.5L10 15v-2.2L5 11.2z"/></svg>',
  '<svg viewBox="0 0 24 24" fill="currentColor"><circle cx="7" cy="7" r="2.6"/><circle cx="17" cy="7" r="2.6"/><circle cx="12" cy="11" r="2.2"/><path d="M2 19c0-3.3 2.2-5.5 5-5.5s5 2.2 5 5.5zM12 19c0-3.3 2.2-5.5 5-5.5s5 2.2 5 5.5z"/></svg>',
];

$leaf = '<svg class="tk-leaf" viewBox="0 0 80 70" fill="none" stroke="#6f9a5a" stroke-width="1.4" stroke-linecap="round"><path d="M70 66C60 40 45 22 18 8c4 24 18 44 52 58z" fill="#cfe3bd" fill-opacity=".7"/><path d="M70 66C52 44 36 28 18 8"/><path d="M50 66C46 52 36 40 26 32" fill="#b9d3a2" fill-opacity=".6"/></svg>';
?>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap">
<style>
  :root {
    --tk-green: #173B2A;
    --tk-green-dark: #0B2F22;
    --tk-orange: #E8A15B;
    --tk-orange-dark: #D98E45;
    --tk-cream: #FFFDF8; /* Soft White - index.php section 1 */
    --tk-text: #2B241D;
  }

  /* ---------- BANNER (sirf image ke liye, koi text/line nahi) ---------- */
  /* Banner: image poori dikhti hai (crop nahi, side/upar-neeche koi khali jagah nahi).
     Width screen jitni, height image ke ratio se apne aap set hoti hai. */
  .tk-banner {
    position: relative; overflow: hidden; line-height: 0; margin: 0; padding: 0; width: 100%;
    background: linear-gradient(100deg, #173B2A 0%, #0B2F22 100%);
  }
  .tk-banner:not(.has-photo) { height: 500px; }
  .tk-banner-photo { display: block; width: 100%; height: auto; margin: 0; }
  /* FIXED 500px: image container ko edge-to-edge bharti hai, left/right koi space nahi */
  .tk-banner.is-fixed { height: 500px; }
  .tk-banner.is-fixed .tk-banner-photo { width: 100%; height: 100%; }
  @media (max-width: 768px) {
    .tk-banner.is-fixed { height: auto; }
    .tk-banner.is-fixed .tk-banner-photo { height: auto; }
  }
  .tk-sr { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); white-space: nowrap; }

  /* ---------- TICKETS SECTION ---------- */
  .tk-section { background: #FFFDF8; padding: 40px 0; }
  .tk-wrap { width: calc(100% - 64px); max-width: 1560px; margin: 0 auto; }

  .tk-grid {
    display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 20px; align-items: stretch;
  }
  /* ===== COMPACT CARD: chhota square image (poori dikhti hai) + text barabar aligned ===== */
  .tk-card {
    --tk-img: 160px;                       /* image box ka size (square) */
    position: relative; display: flex; flex-direction: row; gap: 16px; align-items: stretch;
    padding: 12px; border-radius: 18px; overflow: hidden;
    background: #FFFFFF;
    border: 1px solid rgba(23, 59, 42, .10);
    box-shadow: 0 6px 20px rgba(23, 59, 42, .10);
    transition: transform .2s, box-shadow .2s;
  }
  .tk-card:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(23, 59, 42, .16); }

  /* Image box: square. Image 'contain' hoti hai, yaani kabhi cut nahi hoti.
     Agar image square nahi hai to khali jagah par usi image ka soft blur bhar jaata hai. */
  .tk-card-img {
    position: relative; flex: 0 0 var(--tk-img); width: var(--tk-img); height: var(--tk-img);
    align-self: center; border-radius: 14px; overflow: hidden; background: #E3ECE6;
    box-shadow: 0 6px 14px rgba(11, 47, 34, .18);
  }
  .tk-card-img::before {
    content: ""; position: absolute; inset: -20px; z-index: 0;
    background: var(--tkbg) center / cover no-repeat; filter: blur(16px) saturate(1.1); transform: scale(1.1);
  }
  .tk-card-img img { position: absolute; inset: 0; z-index: 1; width: 100%; height: 100%; object-fit: contain; object-position: center; display: block; }

  .tk-card-body {
    position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column; justify-content: center;
    min-width: 0; padding: 2px 4px 2px 0; font-family: 'Lato', 'Segoe UI', sans-serif;
  }
  .tk-title { display: flex; align-items: center; gap: 10px; }
  .tk-icon {
    width: 34px; height: 34px; border-radius: 50%; flex: 0 0 34px;
    background: var(--tk-green); color: #fff; display: grid; place-items: center;
  }
  .tk-icon svg { width: 18px; height: 18px; }
  .tk-title h3 {
    font-family: 'Playfair Display', Georgia, 'Times New Roman', serif; font-weight: 700;
    font-size: 20px; line-height: 1.15; color: var(--tk-green-dark); margin: 0; overflow-wrap: anywhere;
  }
  .tk-price {
    font-family: 'Playfair Display', Georgia, 'Times New Roman', serif; font-weight: 700;
    font-size: 26px; line-height: 1.1; color: var(--tk-green-dark); margin: 10px 0 4px;
  }
  .tk-desc { font-size: 14px; color: var(--tk-text); margin: 0 0 8px; line-height: 1.4; flex: 1 1 auto; overflow-wrap: anywhere; }
  .tk-line { width: 30px; height: 2px; background: var(--tk-orange); margin: 0 0 10px; }

  .tk-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    align-self: flex-start; white-space: nowrap; padding: 9px 20px; position: relative; z-index: 3; border-radius: 40px;
    background: var(--tk-orange); color: #173B2A;
    border: 0; font-family: 'Lato', 'Segoe UI', sans-serif; font-weight: 700; font-size: 15px; text-decoration: none; cursor: pointer;
    box-shadow: 0 4px 10px rgba(232, 161, 91, .40); transition: transform .2s, box-shadow .2s;
  }
  .tk-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(232, 161, 91, .50); background: var(--tk-orange-dark); }
  .tk-btn:focus-visible { outline: 3px solid var(--tk-green); outline-offset: 3px; }
  .tk-btn svg { width: 16px; height: 16px; }

  .tk-leaf { position: absolute; right: 0; bottom: 0; width: 40px; height: 36px; opacity: .85; z-index: 1; pointer-events: none; }

  /* ---------- FEATURE STRIP ---------- */
  .tk-strip {
    margin-top: 24px; display: grid; grid-template-columns: repeat(4, minmax(0, 1fr));
    background: #EAF1EC; border-radius: 22px; padding: 14px 10px;
    font-family: 'Lato', 'Segoe UI', sans-serif; color: var(--tk-green-dark);
  }
  .tk-strip-item {
    display: flex; align-items: center; justify-content: center; gap: 14px;
    padding: 6px 14px; min-height: 56px; font-size: 16px; font-weight: 400; line-height: 1.3;
    border-right: 1px solid rgba(23, 59, 42, .25);
  }
  .tk-strip-item:last-child { border-right: 0; }
  .tk-strip-item svg { width: 36px; height: 36px; flex: 0 0 36px; color: var(--tk-green); }

  .tk-booking-dialog {
    width: min(760px, calc(100% - 28px)); max-width: none; max-height: min(92vh, 900px);
    padding: 0; border: 1px solid rgba(23, 59, 42, .18); border-radius: 22px;
    background: var(--tk-cream); color: var(--tk-text); box-shadow: 0 24px 80px rgba(11, 47, 34, .35);
  }
  .tk-booking-dialog::backdrop { background: rgba(11, 31, 23, .72); backdrop-filter: blur(3px); }
  .wk-modal-content { padding: clamp(20px, 5vw, 36px); }
  .wk-modal-heading { display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; margin-bottom: 24px; }
  .wk-modal-heading h2 { margin: 4px 0 0; color: var(--tk-green-dark); font: 700 clamp(25px, 4vw, 34px)/1.15 'Playfair Display', Georgia, serif; }
  .wk-modal-eyebrow { color: #9a5a20; font-size: 12px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; }
  .wk-modal-close { flex: 0 0 42px; width: 42px; height: 42px; border: 1px solid #e6d9c4; border-radius: 50%; background: #fff; color: var(--tk-green); font-size: 28px; line-height: 1; cursor: pointer; }
  .wk-modal-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
  .wk-modal-field { display: flex; flex-direction: column; gap: 7px; margin-bottom: 16px; }
  .wk-modal-field-wide { grid-column: 1 / -1; }
  .wk-modal-field label { color: var(--tk-green-dark); font-weight: 700; }
  .wk-modal-field input { width: 100%; min-height: 46px; padding: 10px 12px; border: 1px solid #d8cdbb; border-radius: 10px; background: #fff; color: var(--tk-text); font: inherit; }
  .wk-modal-field input:focus { outline: 3px solid rgba(232, 161, 91, .35); border-color: var(--tk-orange-dark); }
  .wk-modal-total, .wk-payment-summary { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin: 2px 0 18px; padding: 16px 18px; border: 1px solid #e9dcc7; border-radius: 12px; background: #f5efe4; color: var(--tk-green-dark); }
  .wk-modal-total strong, .wk-payment-summary strong { color: #8a4d16; font-size: 1.1rem; }
  .wk-modal-submit { display: inline-flex; min-height: 48px; align-items: center; justify-content: center; width: 100%; padding: 12px 18px; border: 0; border-radius: 30px; background: var(--tk-orange); color: var(--tk-green-dark); font: 700 16px 'Lato', sans-serif; text-align: center; text-decoration: none; cursor: pointer; }
  .wk-modal-submit:hover { background: var(--tk-orange-dark); }
  .wk-modal-submit:disabled { cursor: wait; opacity: .7; }
  .wk-modal-errors, .wk-modal-notice { margin: 14px 0; padding: 13px 15px; border: 1px solid #e9c78e; border-radius: 10px; background: #fff6e8; color: #69420b; }
  .wk-modal-errors { border-color: #e5b3a5; background: #fff1ed; color: #7d2c1c; }
  .wk-modal-errors ul { margin: 0; padding-left: 20px; }
  .wk-payment-layout { display: grid; grid-template-columns: minmax(180px, 230px) 1fr; align-items: center; gap: 22px; margin-bottom: 20px; }
  .wk-payment-qr { display: flex; min-height: 210px; flex-direction: column; align-items: center; justify-content: center; gap: 8px; padding: 14px; border: 2px dashed #c9b58e; border-radius: 14px; background: #fff; color: #6c6254; text-align: center; }
  .wk-payment-qr img { display: block; width: 100%; max-width: 190px; height: auto; }
  .wk-payment-qr-mark { display: grid; width: 64px; height: 64px; place-items: center; border: 3px solid var(--tk-green); border-radius: 12px; color: var(--tk-green); font-size: 20px; font-weight: 800; }
  .wk-payment-instructions { color: #493f32; line-height: 1.6; }
  .wk-payment-instructions p:first-child { color: var(--tk-green-dark); font-weight: 700; }
  .wk-confirmation-details { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px 18px; margin: 16px 0; }
  .wk-confirmation-details div { padding: 10px 0; border-bottom: 1px solid #e9dcc7; }
  .wk-confirmation-details dt { color: #746b5f; font-size: .86rem; }
  .wk-confirmation-details dd { margin: 4px 0 0; color: var(--tk-green-dark); font-weight: 700; overflow-wrap: anywhere; }
  .wk-confirmation-qr { display: flex; flex-direction: column; align-items: center; gap: 8px; margin: 18px 0; color: #5c6967; text-align: center; font-size: .9rem; }
  .wk-confirmation-qr img { width: 160px; height: 160px; }

  /* ---------- RESPONSIVE ---------- */
  /* Laptop (3 cards narrow): image upar, text neeche, taaki sab clean dikhe */
  @media (min-width: 1101px) and (max-width: 1499px) {
    .tk-wrap { width: calc(100% - 48px); }
    .tk-card { --tk-img: 128px; gap: 14px; }
    .tk-title h3 { font-size: 18px; }
    .tk-price { font-size: 23px; }
    .tk-strip-item { font-size: 15px; gap: 10px; }
    .tk-strip-item svg { width: 32px; height: 32px; flex-basis: 32px; }
  }
  @media (max-width: 1100px) {
    .tk-grid { grid-template-columns: 1fr; max-width: 640px; margin-inline: auto; }
    .tk-strip { grid-template-columns: repeat(2, 1fr); row-gap: 10px; }
    .tk-strip-item:nth-child(2) { border-right: 0; }
  }
  @media (max-width: 560px) {
    .tk-banner:not(.has-photo) { height: 300px; }
    .tk-wrap { width: calc(100% - 32px); }
    .tk-card { --tk-img: 108px; gap: 12px; padding: 10px; }
    .tk-title h3 { font-size: 17px; }
    .tk-icon { width: 30px; height: 30px; flex-basis: 30px; }
    .tk-price { font-size: 22px; margin-top: 8px; }
    .tk-desc { font-size: 13px; }
    .tk-btn { padding: 8px 16px; font-size: 14px; }
    .tk-strip { grid-template-columns: 1fr; }
    .tk-strip-item { border-right: 0; justify-content: flex-start; }
    .wk-modal-grid, .wk-confirmation-details { grid-template-columns: 1fr; }
    .wk-modal-field-wide { grid-column: auto; }
    .wk-payment-layout { grid-template-columns: 1fr; }
    .wk-payment-qr { min-height: 190px; }
    .wk-modal-total, .wk-payment-summary { align-items: flex-start; flex-direction: column; }
  }
</style>

<!-- ================= BANNER (abhi sirf khali, image baad me) ================= -->
<section class="tk-banner<?php echo $bannerSrc ? ' has-photo' : ''; ?><?php echo ($bannerSrc && $bannerFixedHeight) ? ' is-fixed' : ''; ?>">
  <?php if ($bannerSrc): ?>
    <img class="tk-banner-photo" src="<?php echo htmlspecialchars($bannerSrc, ENT_QUOTES, 'UTF-8'); ?>" alt="Wildlife Kingdom tickets"<?php if ($bannerFixedHeight) echo ' style="object-fit:' . ($bannerFit === 'fill' ? 'fill' : 'cover') . ';object-position:' . htmlspecialchars($bannerFocus, ENT_QUOTES, 'UTF-8') . '"'; ?><?php if ($bannerW && $bannerH) echo ' width="' . $bannerW . '" height="' . $bannerH . '"'; ?> fetchpriority="high">
  <?php endif; ?>
  <h1 class="tk-sr">Wildlife Kingdom Tickets</h1>
</section>

<!-- ================= TICKETS ================= -->
<section class="tk-section">
  <div class="tk-wrap">

    <div class="tk-grid">
      <?php if (count($tickets) > 0): ?>
        <?php foreach ($tickets as $i => $ticket): ?>
          <article class="tk-card">
            <?php $cardDefault = $ticketImages[$i % count($ticketImages)]; $cardImg = tkAdminImage($ticket['image'] ?? '', $tkTried) ?: $cardDefault; $isDefaultImg = ($cardImg === $cardDefault); ?>
            <div class="tk-card-img<?php echo $isDefaultImg ? ' is-default' : ''; ?>" style="--tkbg:url('<?php echo htmlspecialchars($cardImg, ENT_QUOTES, 'UTF-8'); ?>')">
              <?php if (isset($_GET['imgdebug'])) echo '<!-- imgdebug: db="' . htmlspecialchars((string) ($ticket['image'] ?? ''), ENT_QUOTES) . '" using="' . htmlspecialchars($cardImg, ENT_QUOTES) . '" tried=' . htmlspecialchars(implode(' | ', array_slice($tkTried, 0, 12)), ENT_QUOTES) . ' -->'; ?>
              <img src="<?php echo htmlspecialchars($cardImg, ENT_QUOTES, 'UTF-8'); ?>"
                   alt="" loading="lazy"
                   onerror="if(!this.dataset.f){this.dataset.f=1;this.src='<?php echo htmlspecialchars($cardDefault, ENT_QUOTES, 'UTF-8'); ?>';}else{this.style.display='none';}">
            </div>

            <div class="tk-card-body">
              <div class="tk-title">
                <span class="tk-icon"><?php echo $ticketIcons[$i % count($ticketIcons)]; ?></span>
                <h3><?php echo htmlspecialchars($ticket['ticket_type']); ?></h3>
              </div>

              <div class="tk-price">₹<?php echo number_format($ticket['price'], 2); ?></div>
              <p class="tk-desc"><?php echo htmlspecialchars($ticket['description']); ?></p>
              <div class="tk-line"></div>

              <button type="button" class="tk-btn" data-book-ticket="<?php echo (int) $ticket['id']; ?>">
                Book Now
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
              </button>
            </div>

            <?php echo $leaf; ?>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Ticket pricing coming soon.</p>
      <?php endif; ?>
    </div>

    <!-- ================= FEATURE STRIP ================= -->
    <div class="tk-strip">
      <div class="tk-strip-item">
        <svg viewBox="0 0 24 24" fill="currentColor"><ellipse cx="12" cy="16" rx="5" ry="4.2"/><ellipse cx="5" cy="11" rx="2" ry="2.8" transform="rotate(-20 5 11)"/><ellipse cx="9.5" cy="6" rx="2" ry="2.8"/><ellipse cx="14.5" cy="6" rx="2" ry="2.8"/><ellipse cx="19" cy="11" rx="2" ry="2.8" transform="rotate(20 19 11)"/></svg>
        <span>Explore Amazing<br>Wildlife</span>
      </div>
      <div class="tk-strip-item">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c3 2.5 5 5 5 7.5 0 1-.4 1.8-1 2.5 2 .8 3.5 2.6 3.5 4.7 0 2.4-2 4.3-4.5 4.3H13v1.5h-2V21H8C5.5 21 3.5 19.100 3.500 16.700c0-2.100 1.500-3.900 3.500-4.700-.6-.7-1-1.500-1-2.500C6 7 9 4.500 12 2z"/></svg>
        <span>Discover Beautiful<br>Habitats</span>
      </div>
      <div class="tk-strip-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" stroke-linecap="round"><path d="M12 2.500l8 3v6c0 5-3.400 8.700-8 10-4.600-1.300-8-5-8-10v-6z" fill="currentColor" fill-opacity=".12"/><path d="M8 12l3 3 5-6"/></svg>
        <span>Support Conservation</span>
      </div>
      <div class="tk-strip-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"><path d="M12 21C5 15.500 2.500 12 2.500 8.500 2.500 5.900 4.500 4 7 4c2 0 3.800 1.100 5 3 1.200-1.900 3-3 5-3 2.500 0 4.500 1.900 4.500 4.500C21.500 12 19 15.500 12 21z"/></svg>
        <span>Create Lasting Memories</span>
      </div>
    </div>

  </div>
</section>

<dialog class="tk-booking-dialog" id="tkBookingDialog" aria-label="Ticket booking">
  <div id="tkBookingDialogContent" aria-live="polite">
    <div class="wk-modal-content" role="status">Loading booking form…</div>
  </div>
</dialog>
<script>
  (function () {
    var dialog = document.getElementById('tkBookingDialog');
    var content = document.getElementById('tkBookingDialogContent');
    var laravelBase = new URL('../laravel/public/', window.location.href).href.replace(/\/$/, '');
    var requestHeaders = {
      'Accept': 'text/html',
      'X-Requested-With': 'XMLHttpRequest'
    };

    function showError(message) {
      content.innerHTML = '<div class="wk-modal-content"><div class="wk-modal-heading"><h2>Booking unavailable</h2><button class="wk-modal-close" type="button" data-modal-close aria-label="Close booking dialog">&times;</button></div><p class="wk-modal-errors" role="alert"></p></div>';
      content.querySelector('[role="alert"]').textContent = message;
    }

    function showValidationErrors(form, errors) {
      var box = form.querySelector('[data-form-errors]');
      var list = document.createElement('ul');
      Object.keys(errors || {}).forEach(function (field) {
        errors[field].forEach(function (message) {
          var item = document.createElement('li');
          item.textContent = message;
          list.appendChild(item);
        });
      });
      box.replaceChildren(list);
      box.hidden = false;
    }

    function loadFragment(url) {
      content.innerHTML = '<div class="wk-modal-content" role="status">Loading…</div>';
      return fetch(url, { headers: requestHeaders, credentials: 'same-origin' })
        .then(function (response) {
          if (!response.ok) throw new Error('The booking service could not load this step.');
          return response.text();
        })
        .then(function (html) { content.innerHTML = html; })
        .catch(function (error) { showError(error.message); });
    }

    function openBooking(ticketId) {
      if (!dialog.open) dialog.showModal();
      loadFragment(laravelBase + '/tickets/' + encodeURIComponent(ticketId) + '/booking-modal');
    }

    document.addEventListener('click', function (event) {
      var bookButton = event.target.closest('[data-book-ticket]');
      if (bookButton) {
        openBooking(bookButton.getAttribute('data-book-ticket'));
        return;
      }

      if (event.target.closest('[data-modal-close]')) {
        dialog.close();
        return;
      }

      var fragmentButton = event.target.closest('[data-load-fragment]');
      if (fragmentButton) loadFragment(fragmentButton.getAttribute('data-load-fragment'));
    });

    document.addEventListener('input', function (event) {
      if (event.target.matches('#wk_ticket_quantity')) {
        var quantity = Math.min(20, Math.max(1, Number(event.target.value || 1)));
        var unit = Number(content.querySelector('[data-unit-price]').textContent);
        content.querySelector('[data-total-amount]').textContent = (unit * quantity).toFixed(2);
      }
    });

    document.addEventListener('submit', function (event) {
      var form = event.target.closest('[data-booking-form], [data-payment-form]');
      if (!form) return;
      event.preventDefault();
      var submit = form.querySelector('button[type="submit"]');
      submit.disabled = true;
      fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        credentials: 'same-origin',
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      }).then(function (response) {
        return response.json().then(function (data) {
          if (!response.ok) {
            showValidationErrors(form, data.errors || { booking: ['Please check the information and try again.'] });
            submit.disabled = false;
            return null;
          }
          return data;
        });
      }).then(function (data) {
        if (!data) return;
        return loadFragment(data.paymentUrl || data.confirmationUrl);
      }).catch(function () {
        showValidationErrors(form, { booking: ['We could not submit your booking. Please check your connection and try again.'] });
        submit.disabled = false;
      });
    });

    dialog.addEventListener('click', function (event) {
      if (event.target === dialog) dialog.close();
    });

    var selectedTicket = new URLSearchParams(window.location.search).get('book');
    if (selectedTicket && /^\d+$/.test(selectedTicket)) openBooking(selectedTicket);
  })();
</script>

<?php require '../includes/footer.php'; ?>