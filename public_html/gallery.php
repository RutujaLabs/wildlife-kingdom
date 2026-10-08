<?php
$pageTitle = 'Gallery';
$pageCss = 'pages.css';
require '../includes/header.php';
require '../config/db.php';

$gallery = [];
$result = $conn->query("SELECT * FROM gallery ORDER BY created_at DESC");
if ($result) { while ($row = $result->fetch_assoc()) { $gallery[] = $row; } }

/* ==================================================================
   IMAGE SLOTS  --  yahan se images add karo (DB ke bina bhi chalega)
   ------------------------------------------------------------------
   1) Image file ko  uploads/gallery/  folder me daalo
   2) Neeche ek line copy karke file ka naam likho:
        ['image' => 'lion-1.jpg', 'title' => 'Lion', 'species' => 'African Lion', 'category' => 'big-cats'],
   3) Ek animal ki kitni bhi photos add kar sakte ho (lion-1.jpg, lion-2.jpg ...)
   category: big-cats | giants | birds | herbivores | primates | reptiles | habitats
   NOTE: jis file ka image folder me nahi milega wo apne aap hide rahega.
================================================================== */
$slots = [
  // ---- BIG CATS ----
  ['image' => 'lion.jpg',      'title' => 'Lion',      'species' => 'African Lion',      'category' => 'big-cats'],
  ['image' => 'tiger.jpg',     'title' => 'Tiger',     'species' => 'Bengal Tiger',      'category' => 'big-cats'],
  ['image' => 'leopard.jpg',   'title' => 'Leopard',   'species' => 'Indian Leopard',    'category' => 'big-cats'],
  ['image' => 'cheetah.jpg',   'title' => 'Cheetah',   'species' => 'Cheetah',           'category' => 'big-cats'],
  // ---- GIANTS ----
  ['image' => 'elephant.jpg',  'title' => 'Asian Elephant', 'species' => 'Elephant',     'category' => 'giants'],
  ['image' => 'giraffe.jpg',   'title' => 'Giraffe',   'species' => 'Giraffe',           'category' => 'giants'],
  ['image' => 'rhino.jpg',     'title' => 'Rhinoceros','species' => 'Rhinoceros',        'category' => 'giants'],
  ['image' => 'hippo.jpg',     'title' => 'Hippopotamus','species' => 'Hippopotamus',    'category' => 'giants'],
  // ---- BIRDS ----
  ['image' => 'peacock.jpg',   'title' => 'Peacock',   'species' => 'Indian Peafowl',    'category' => 'birds'],
  ['image' => 'parrot.jpg',    'title' => 'Parrot',    'species' => 'Parrot',            'category' => 'birds'],
  ['image' => 'flamingo.jpg',  'title' => 'Flamingo',  'species' => 'Flamingo',          'category' => 'birds'],
  ['image' => 'eagle.jpg',     'title' => 'Eagle',     'species' => 'Eagle',             'category' => 'birds'],
  ['image' => 'macaw.jpg',     'title' => 'Blue-and-Yellow Macaw', 'species' => 'Macaw', 'category' => 'birds'],
  ['image' => 'pelican.jpg',   'title' => 'Pelican',   'species' => 'Pelican',           'category' => 'birds'],
  ['image' => 'owl.jpg',       'title' => 'Owl',       'species' => 'Owl',               'category' => 'birds'],
  // ---- HERBIVORES ----
  ['image' => 'deer.jpg',      'title' => 'Deer',      'species' => 'Spotted Deer',      'category' => 'herbivores'],
  ['image' => 'zebra.jpg',     'title' => 'Zebra',     'species' => 'Plains Zebra',      'category' => 'herbivores'],
  ['image' => 'gaur.jpg',      'title' => 'Gaur',      'species' => 'Indian Gaur',       'category' => 'herbivores'],
  ['image' => 'antelope.jpg',  'title' => 'Antelope',  'species' => 'Blackbuck Antelope','category' => 'herbivores'],
  // ---- PRIMATES ----
  ['image' => 'monkey.jpg',    'title' => 'Monkey',    'species' => 'Macaque Monkey',    'category' => 'primates'],
  ['image' => 'chimpanzee.jpg','title' => 'Chimpanzee','species' => 'Chimpanzee',        'category' => 'primates'],
  ['image' => 'gorilla.jpg',   'title' => 'Gorilla',   'species' => 'Gorilla',           'category' => 'primates'],
  // ---- REPTILES ----
  ['image' => 'crocodile.jpg', 'title' => 'Crocodile', 'species' => 'Crocodile',         'category' => 'reptiles'],
  ['image' => 'python.jpg',    'title' => 'Python',    'species' => 'Python',            'category' => 'reptiles'],
  ['image' => 'turtle.jpg',    'title' => 'Turtle',    'species' => 'Turtle',            'category' => 'reptiles'],
  ['image' => 'iguana.jpg',    'title' => 'Iguana',    'species' => 'Iguana',            'category' => 'reptiles'],
  // ---- HABITATS ----
  ['image' => 'habitat-1.jpg', 'title' => 'Savanna Grassland', 'species' => 'Savanna',   'category' => 'habitats'],
  ['image' => 'habitat-2.jpg', 'title' => 'Wetland Lake',      'species' => 'Wetland',   'category' => 'habitats'],
  // ---- yahan aur lines add karo ----
];
$slotRows = [];
foreach ($slots as $sl) {
  if (is_file(__DIR__ . '/../uploads/gallery/' . $sl['image'])) { $slotRows[] = $sl; }
}
$gallery = array_merge($slotRows, $gallery);


/* ------------------------------------------------------------------
   Categories. The table only needs: title, image, created_at.
   Optional columns:  category  (big-cats | giants | birds | herbivores | primates | reptiles | habitats)
                      species   (e.g. "African Lion" -> overlay shows "Lion · Big Cats")
   If they are missing, category/species are worked out from the title.
------------------------------------------------------------------- */
$catLabels = [
  'big-cats'   => 'Big Cats',
  'giants'     => 'Giants',
  'birds'      => 'Birds',
  'herbivores' => 'Herbivores',
  'primates'   => 'Primates',
  'reptiles'   => 'Reptiles',
  'habitats'   => 'Habitats',
];
$catEmoji = [
  'big-cats' => '🦁', 'giants' => '🐘', 'birds' => '🦜', 'herbivores' => '🦌',
  'primates' => '🐒', 'reptiles' => '🐊', 'habitats' => '🌿',
];
$mammalCats = ['big-cats', 'giants', 'herbivores', 'primates'];
$keywords = [   // order matters (first match wins)
  'reptiles'   => ['crocodile','alligator','python','snake','cobra','turtle','tortoise','iguana','lizard','gecko','chameleon'],
  'big-cats'   => ['lion','tiger','leopard','cheetah','panther','jaguar','cougar','puma'],
  'giants'     => ['elephant','giraffe','rhinoceros','rhino','hippopotamus','hippo'],
  'primates'   => ['monkey','chimpanzee','gorilla','orangutan','lemur','gibbon','baboon','macaque','langur'],
  'herbivores' => ['deer','zebra','gaur','antelope','bison','buffalo','kudu'],
  'birds'      => ['peacock','parrot','flamingo','eagle','macaw','pelican','owl','toucan','hornbill','penguin','stork','heron','vulture','cockatoo','swan'],
  'habitats'   => ['habitat','savanna','savannah','forest','jungle','wetland','pond','lake','river','grassland','aviary','landscape'],
];
$speciesFix = ['rhino' => 'Rhinoceros', 'hippo' => 'Hippopotamus'];

function gal_slug($s) { return trim(preg_replace('/[^a-z0-9]+/', '-', strtolower((string)$s)), '-'); }
function gal_infer($text, $keywords) {
  foreach ($keywords as $slug => $list) {
    foreach ($list as $k) {
      if (preg_match('/\b' . preg_quote($k, '/') . '(?:s|es)?\b/i', $text)) return [$slug, $k];
    }
  }
  return ['', ''];
}

$items = [];
$counts = ['all' => 0, 'mammals' => 0, 'birds' => 0, 'reptiles' => 0, 'big-cats' => 0, 'habitats' => 0];
foreach ($gallery as $row) {
  $title   = trim($row['title'] ?? '');
  $speciesDb = trim($row['species'] ?? '');
  $catDb   = gal_slug($row['category'] ?? '');
  list($inferCat, $inferKw) = gal_infer($title . ' ' . $speciesDb, $keywords);
  $cat = isset($catLabels[$catDb]) ? $catDb : $inferCat;
  $species = $speciesDb;
  if ($species === '' && $inferKw !== '') $species = $speciesFix[$inferKw] ?? ucfirst($inferKw);
  $label = $cat !== '' ? $catLabels[$cat] : '';
  $sub = trim($species . ($species !== '' && $label !== '' ? ' · ' : '') . $label);

  $groups = [];
  if (in_array($cat, $mammalCats, true)) $groups[] = 'mammals';
  if (in_array($cat, ['birds','reptiles','big-cats','habitats'], true)) $groups[] = $cat;

  $counts['all']++;
  foreach ($groups as $g) { $counts[$g]++; }

  $items[] = [
    'src'    => '../uploads/gallery/' . $row['image'],
    'title'  => $title !== '' ? $title : ($species !== '' ? $species : 'Wildlife'),
    'sub'    => $sub,
    'cat'    => $cat,
    'groups' => implode(' ', $groups),
    'emoji'  => $cat !== '' ? $catEmoji[$cat] : '📷',
  ];
}
$total = count($items);

$filters = [
  ['all', 'All'], ['mammals', 'Mammals'], ['birds', 'Birds'],
  ['reptiles', 'Reptiles'], ['big-cats', 'Big Cats'], ['habitats', 'Habitats'],
];
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap');

/* =====================================================
   Gallery page  (same fonts + colours as the About page)
   banner > Section 1: heading + filter bar + mosaic wall
   ===================================================== */

/* ---------- banner (same style as About banner) ---------- */
.gal-banner { position: relative; overflow: hidden; height: 500px; background-color: #0B2F22; }
.gal-banner::before {
  content: ""; position: absolute; inset: 0;
  background: url('../assets/images/static/gallery-banner.webp') center / cover no-repeat;
  animation: abG1Pan 18s ease-in-out infinite alternate;
}
.gal-banner::after { content: ""; position: absolute; left: 0; right: 0; bottom: 0; height: 4px; background: #e8b52a; }
@keyframes abG1Pan { from { transform: scale(1); } to { transform: scale(1.08); } }

/* ---------- section 1 ---------- */
.ab-g1, .ab-g1 * { box-sizing: border-box; }
.ab-g1 {
  position: relative; overflow: hidden;
  background: #FFFDF8; color: #173B2A;
  padding: clamp(14px, 2vw, 26px) 0 clamp(16px, 2.2vw, 30px);
  font-family: 'Lato', 'Segoe UI', sans-serif;
}
.ab-g1::before {
  content: ""; position: absolute; width: 300px; height: 300px; right: -110px; top: -140px;
  border-radius: 50%; border: 2px solid rgba(232, 161, 91, .25); pointer-events: none;
}
.ab-g1-wrap { position: relative; z-index: 1; max-width: 1700px; margin: 0 auto; padding: 0 clamp(6px, 1vw, 12px); }

/* reveal */
.ab-g1.js-anim [data-r] { opacity: 0; transform: translateY(18px); transition: opacity .7s ease, transform .7s ease; transition-delay: calc(var(--d, 0) * 1s); }
.ab-g1.js-anim [data-r].is-in { opacity: 1; transform: none; }

/* ---- head: text left | animated supporting line right ---- */
.ab-g1-head {
  display: grid; grid-template-columns: minmax(0, 1.5fr) minmax(0, .5fr);
  gap: clamp(14px, 3vw, 40px); align-items: center;
  padding: 0 clamp(6px, 1vw, 12px); margin-bottom: clamp(10px, 1.4vw, 16px);
}
.ab-g1-label {
  display: inline-flex; align-items: center; gap: 10px;
  margin: 0 0 8px; padding: 6px 14px;
  border: 1px solid rgba(232, 161, 91, .8); border-radius: 999px; background: rgba(232, 161, 91, .14);
  font-size: .66rem; font-weight: 700; letter-spacing: .22em; text-transform: uppercase; color: #173B2A;
}
.ab-g1-label::before { content: ""; width: 8px; height: 8px; border-radius: 50%; background: #E8A15B; animation: abG1Blink 2s ease-in-out infinite; }
@keyframes abG1Blink { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: .35; transform: scale(.7); } }
.ab-g1-title {
  margin: 0 0 8px;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.6rem, 3vw, 2.6rem); font-weight: 700; line-height: 1.1; letter-spacing: .01em; color: #173B2A;
}
.ab-g1-title em { font-style: italic; font-weight: 600; color: #C97F3F; }
.ab-g1-intro { margin: 0; max-width: 900px; font-size: .95rem; line-height: 1.6; color: #3d4f45; }

.ab-g1-words { margin: 0; padding: 0 0 0 clamp(14px, 2vw, 26px); list-style: none; border-left: 2px dashed rgba(232, 161, 91, .7); }
.ab-g1-words li {
  font-family: 'Playfair Display', Georgia, serif; font-weight: 700; font-style: italic;
  font-size: clamp(1.4rem, 2.4vw, 2.1rem); line-height: 1.12;
  color: transparent; -webkit-text-fill-color: transparent; -webkit-text-stroke: 1.2px #C97F3F;
  background: linear-gradient(0deg, #E8A15B, #C97F3F) no-repeat bottom / 100% 0%;
  -webkit-background-clip: text; background-clip: text;
  display: block; width: fit-content;
  transition: background-size .5s ease, transform .4s ease;
}
.ab-g1-words li.is-on { background-size: 100% 100%; transform: translateX(8px); }

/* ---- filter bar with sliding indicator ---- */
.ab-g1-bar { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: clamp(8px, 1vw, 12px); padding: 0 clamp(6px, 1vw, 12px); }
.ab-g1-filters {
  position: relative; display: inline-flex; gap: 2px; padding: 5px; max-width: 100%;
  background: #fff; border: 1px solid rgba(232, 161, 91, .6); border-radius: 999px;
  box-shadow: 0 6px 18px rgba(23, 59, 42, .08);
  overflow-x: auto; scrollbar-width: none;
}
.ab-g1-filters::-webkit-scrollbar { display: none; }
.ab-g1-ind {
  position: absolute; top: 5px; bottom: 5px; left: 0; width: 0; z-index: 0;
  border-radius: 999px; background: #173B2A;
  transition: left .45s cubic-bezier(.65, 0, .35, 1), width .45s cubic-bezier(.65, 0, .35, 1);
}
.ab-g1-f {
  position: relative; z-index: 1; flex: 0 0 auto;
  display: inline-flex; align-items: center; gap: 8px;
  padding: 9px clamp(12px, 1.6vw, 20px); border: 0; border-radius: 999px; background: none; cursor: pointer;
  font-family: inherit; font-size: .72rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: #173B2A;
  transition: color .3s ease;
}
.ab-g1-f small { font-size: .62rem; letter-spacing: .04em; color: #C97F3F; transition: color .3s ease; }
.ab-g1-f:hover { color: #C97F3F; }
.ab-g1-f.is-active { color: #FFFDF8; }
.ab-g1-f.is-active small { color: #E8A15B; }
.ab-g1-f:focus-visible { outline: 2px solid #E8A15B; outline-offset: 1px; }
.ab-g1-showing { flex: 0 0 auto; font-size: .7rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: #3d4f45; white-space: nowrap; }
.ab-g1-showing b { color: #C97F3F; font-variant-numeric: tabular-nums; }

/* ---- mosaic wall: aligned grid, tiles get 1x1 / tall / big by JS ---- */
.ab-g1-grid {
  display: grid; grid-template-columns: repeat(4, minmax(0, 1fr));
  grid-auto-rows: clamp(140px, 15vw, 235px); grid-auto-flow: dense;
  gap: clamp(6px, .8vw, 10px);
}
.ab-g1-tile {
  position: relative; display: block; overflow: hidden; text-decoration: none; cursor: zoom-in;
  border-radius: 14px; border: 1px solid rgba(232, 161, 91, .4);
  background: linear-gradient(160deg, #1f4d38, #0B2F22);
  opacity: 0; transform: translateY(16px) scale(.97);
  transition: opacity .6s ease, transform .35s ease, border-color .3s ease, box-shadow .3s ease;
}
.ab-g1-tile.in { opacity: 1; transform: none; }
.ab-g1-tile.out { opacity: 0 !important; transform: scale(.92) !important; transition: opacity .22s ease, transform .22s ease; }
.ab-g1-tile.gone { display: none; }
.ab-g1-tile.big  { grid-column: span 2; grid-row: span 2; }
.ab-g1-tile.tall { grid-row: span 2; }
.ab-g1-tile.in:hover { transform: translateY(-3px); border-color: #E8A15B; box-shadow: 0 14px 26px rgba(11, 47, 34, .3); }
.ab-g1-tile img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .8s ease; }
.ab-g1-tile:hover img { transform: scale(1.08); }
.ab-g1-tile::after {                              /* always-on soft fade for the label */
  content: ""; position: absolute; inset: 0; pointer-events: none;
  background: linear-gradient(to top, rgba(11, 47, 34, .78) 0%, rgba(11, 47, 34, 0) 42%);
  transition: opacity .35s ease;
}
.ab-g1-cap {
  position: absolute; left: 12px; right: 12px; bottom: 10px; z-index: 2;
  display: flex; flex-direction: column; gap: 2px; color: #FFFDF8;
  transition: transform .35s ease;
}
.ab-g1-tile:hover .ab-g1-cap { transform: translateY(-3px); }
.ab-g1-cap b { font-family: 'Playfair Display', Georgia, serif; font-size: clamp(.86rem, 1.05vw, 1rem); font-weight: 600; line-height: 1.2; text-shadow: 0 1px 6px rgba(0, 0, 0, .45); }
.ab-g1-cap i { font-style: normal; font-size: .58rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: #E8A15B; }
.ab-g1-tile.big .ab-g1-cap b { font-size: clamp(1.1rem, 1.6vw, 1.4rem); }
.ab-g1-badge {
  position: absolute; top: 9px; left: 9px; z-index: 2;
  width: 30px; height: 30px; border-radius: 50%; background: #E8A15B;
  display: flex; align-items: center; justify-content: center; font-size: .95rem; line-height: 1;
  opacity: 0; transform: scale(.5) rotate(-60deg); transition: opacity .3s ease, transform .4s cubic-bezier(.3, 1.6, .6, 1);
}
.ab-g1-tile:hover .ab-g1-badge, .ab-g1-tile:focus-visible .ab-g1-badge { opacity: 1; transform: none; }
.ab-g1-tile:focus-visible { outline: 3px solid #E8A15B; outline-offset: 2px; }

.ab-g1-none { display: none; text-align: center; padding: 28px 12px; border: 1px dashed rgba(232, 161, 91, .8); border-radius: 16px; font-family: 'Playfair Display', Georgia, serif; font-style: italic; font-size: 1.1rem; }
.ab-g1-more { margin-top: clamp(10px, 1.4vw, 16px); text-align: center; }
.ab-g1-btn {
  display: inline-flex; align-items: center; gap: 10px; padding: 11px 28px;
  border: 2px solid #173B2A; border-radius: 999px; background: #173B2A; color: #FFFDF8; cursor: pointer;
  font-family: inherit; font-size: .74rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase;
  transition: background .3s ease, color .3s ease, transform .3s ease;
}
.ab-g1-btn:hover { background: #E8A15B; border-color: #E8A15B; color: #0B2F22; transform: translateY(-2px); }
.ab-g1-btn small { font-size: .7rem; letter-spacing: .06em; opacity: .85; }
.ab-g1-empty { text-align: center; padding: clamp(24px, 4vw, 48px) 16px; border: 1px dashed rgba(232, 161, 91, .8); border-radius: 18px; font-family: 'Playfair Display', Georgia, serif; font-style: italic; font-size: 1.15rem; }

/* ---------- lightbox ---------- */
.ab-g1-lb {
  position: fixed; inset: 0; z-index: 99999; display: none; flex-direction: column; align-items: center; justify-content: center;
  background: rgba(11, 47, 34, .95); padding: 56px 64px 24px;
}
.ab-g1-lb.is-open { display: flex; animation: abG1Fade .3s ease; }
@keyframes abG1Fade { from { opacity: 0; } to { opacity: 1; } }
@keyframes abG1Pop { from { opacity: 0; transform: scale(.96); } to { opacity: 1; transform: none; } }
.ab-g1-lb figure { margin: 0; text-align: center; max-width: 100%; }
.ab-g1-lb img { max-width: 100%; max-height: 74vh; border-radius: 14px; border: 2px solid #E8A15B; box-shadow: 0 20px 50px rgba(0, 0, 0, .5); animation: abG1Pop .45s ease both; }
.ab-g1-lb figcaption { margin-top: 12px; color: #FFFDF8; font-family: 'Playfair Display', Georgia, serif; font-size: 1.15rem; font-weight: 600; }
.ab-g1-lb figcaption i { display: block; margin-top: 3px; font-family: 'Lato', sans-serif; font-style: normal; font-size: .64rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #E8A15B; }
.ab-g1-lb button {
  position: absolute; width: 44px; height: 44px; border-radius: 50%; border: 2px solid #E8A15B;
  background: rgba(23, 59, 42, .9); color: #E8A15B; cursor: pointer; font-size: 1.4rem; line-height: 1;
  display: flex; align-items: center; justify-content: center; transition: background .25s ease, color .25s ease, transform .25s ease;
}
.ab-g1-lb button:hover { background: #E8A15B; color: #0B2F22; transform: scale(1.08); }
.ab-g1-lb .lb-x { top: 14px; right: 16px; }
.ab-g1-lb .lb-p { left: 14px; top: 50%; margin-top: -22px; }
.ab-g1-lb .lb-n { right: 14px; top: 50%; margin-top: -22px; }
.ab-g1-lb .lb-c { position: absolute; top: 26px; left: 20px; color: #FFFDF8; font-size: .7rem; font-weight: 700; letter-spacing: .2em; }

/* ---------- responsive ---------- */
@media (max-width: 1100px) { .ab-g1-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); grid-auto-rows: clamp(130px, 19vw, 210px); } }
@media (max-width: 800px) {
  .gal-banner { height: 300px; }
  .ab-g1-head { grid-template-columns: 1fr; }
  .ab-g1-words { display: flex; flex-wrap: wrap; gap: 2px 16px; border-left: 0; padding: 0; }
  .ab-g1-bar { flex-direction: column; align-items: stretch; }
  .ab-g1-showing { text-align: right; }
}
@media (max-width: 640px) {
  .ab-g1-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); grid-auto-rows: clamp(120px, 34vw, 170px); }
  .ab-g1-lb { padding: 56px 10px 20px; }
  .ab-g1-lb .lb-p { left: 6px; } .ab-g1-lb .lb-n { right: 6px; }
}
@media (hover: none) { .ab-g1-badge { display: none; } }
@media (prefers-reduced-motion: reduce) {
  .ab-g1 *, .ab-g1 *::before, .ab-g1 *::after, .gal-banner::before { animation: none !important; transition: none !important; }
  .ab-g1-tile { opacity: 1 !important; transform: none !important; }
}
</style>

<!-- BANNER -->
<section class="gal-banner" role="img" aria-label="Wildlife moments captured at Wildlife Kingdom"></section>

<!-- SECTION 1 : A Glimpse Into the Wild -->
<section class="ab-g1" id="abG1">
  <div class="ab-g1-wrap">

    <div class="ab-g1-head" data-r>
      <div>
        <span class="ab-g1-label">Wildlife Kingdom Gallery</span>
        <h1 class="ab-g1-title">A Glimpse Into <em>the Wild</em></h1>
        <p class="ab-g1-intro">Explore a visual collection of wildlife moments from across Wildlife Kingdom. From majestic big cats and gentle giants to colourful birds and fascinating reptiles, every photograph offers a closer look at the incredible diversity of the animal world.</p>
      </div>
      <ul class="ab-g1-words" id="abG1Words" aria-label="Wildlife. Birds. Habitats. Moments.">
        <li>Wildlife.</li><li>Birds.</li><li>Habitats.</li><li>Moments.</li>
      </ul>
    </div>

<?php if ($total === 0): ?>
    <div class="ab-g1-empty">Gallery coming soon.</div>
<?php else: ?>

    <div class="ab-g1-bar" data-r style="--d:.1">
      <div class="ab-g1-filters" id="abG1Filters" role="tablist" aria-label="Filter photos">
        <span class="ab-g1-ind" id="abG1Ind"></span>
        <?php foreach ($filters as $f): if ($f[0] !== 'all' && $counts[$f[0]] === 0) continue; ?>
          <button type="button" class="ab-g1-f<?php echo $f[0] === 'all' ? ' is-active' : ''; ?>" data-f="<?php echo $f[0]; ?>" role="tab">
            <?php echo $f[1]; ?> <small><?php echo $counts[$f[0]]; ?></small>
          </button>
        <?php endforeach; ?>
      </div>
      <div class="ab-g1-showing">Showing <b id="abG1Shown">0</b> of <b id="abG1Of"><?php echo $total; ?></b></div>
    </div>

    <div class="ab-g1-grid" id="abG1Grid">
      <?php foreach ($items as $i => $it): ?>
        <a class="ab-g1-tile" href="<?php echo htmlspecialchars($it['src']); ?>" target="_blank"
           data-i="<?php echo $i; ?>"
           data-title="<?php echo htmlspecialchars($it['title']); ?>"
           data-sub="<?php echo htmlspecialchars($it['sub']); ?>"
           data-groups="<?php echo htmlspecialchars($it['groups']); ?>">
          <img src="<?php echo htmlspecialchars($it['src']); ?>" alt="<?php echo htmlspecialchars($it['title']); ?>" loading="lazy"
               onerror="var a=this.closest('.ab-g1-tile');a.classList.add('broken');a.style.display='none'">
          <span class="ab-g1-badge" aria-hidden="true"><?php echo $it['emoji']; ?></span>
          <span class="ab-g1-cap">
            <b><?php echo htmlspecialchars($it['title']); ?></b>
            <?php if ($it['sub'] !== ''): ?><i><?php echo htmlspecialchars($it['sub']); ?></i><?php endif; ?>
          </span>
        </a>
      <?php endforeach; ?>
    </div>
    <div class="ab-g1-none" id="abG1None">No photos in this category yet.</div>
    <div class="ab-g1-more" id="abG1More" style="display:none">
      <button type="button" class="ab-g1-btn" id="abG1MoreBtn">Load more <small id="abG1Left"></small></button>
    </div>

<?php endif; ?>
  </div>
</section>

<!-- LIGHTBOX -->
<div class="ab-g1-lb" id="abG1Lb" role="dialog" aria-modal="true" aria-label="Photo viewer">
  <span class="lb-c" id="abG1LbC"></span>
  <button type="button" class="lb-x" id="abG1LbX" aria-label="Close">&times;</button>
  <button type="button" class="lb-p" id="abG1LbP" aria-label="Previous">&#8249;</button>
  <button type="button" class="lb-n" id="abG1LbN" aria-label="Next">&#8250;</button>
  <figure>
    <img id="abG1LbImg" src="" alt="">
    <figcaption id="abG1LbCap"></figcaption>
  </figure>
</div>

<script>
(function () {
  var sec = document.getElementById('abG1');
  if (!sec) return;
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---- reveal head / bar ---- */
  sec.classList.add('js-anim');
  var rv = sec.querySelectorAll('[data-r]');
  if (!('IntersectionObserver' in window)) { Array.prototype.forEach.call(rv, function (e) { e.classList.add('is-in'); }); }
  else {
    var io0 = new IntersectionObserver(function (es) {
      es.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('is-in'); io0.unobserve(e.target); } });
    }, { threshold: 0.1 });
    Array.prototype.forEach.call(rv, function (e) { io0.observe(e); });
  }

  /* ---- supporting line: words light up one by one ---- */
  var words = sec.querySelectorAll('#abG1Words li');
  if (words.length) {
    var w = 0;
    words[0].classList.add('is-on');
    if (!reduce) setInterval(function () {
      words[w].classList.remove('is-on'); w = (w + 1) % words.length; words[w].classList.add('is-on');
    }, 1700);
  }

  var grid = document.getElementById('abG1Grid');
  if (!grid) return;
  var tiles = Array.prototype.slice.call(grid.querySelectorAll('.ab-g1-tile'));
  var filters = document.getElementById('abG1Filters');
  var btns = Array.prototype.slice.call(filters.querySelectorAll('.ab-g1-f'));
  var ind = document.getElementById('abG1Ind');
  var shownEl = document.getElementById('abG1Shown'), ofEl = document.getElementById('abG1Of');
  var more = document.getElementById('abG1More'), moreBtn = document.getElementById('abG1MoreBtn'), leftEl = document.getElementById('abG1Left');
  var none = document.getElementById('abG1None');
  var BATCH = 24, limit = BATCH, cur = 'all';

  /* span rhythm (1x1 / tall / big): 12 tiles = 16 cells, so rows stay level */
  var RHYTHM = ['big', '', '', 'tall', '', '', '', 'tall', '', '', '', ''];

  /* tiles fade in as they scroll into view */
  var io = ('IntersectionObserver' in window) ? new IntersectionObserver(function (es) {
    var k = 0;
    es.forEach(function (e) {
      if (!e.isIntersecting) return;
      var t = e.target;
      t.style.transitionDelay = (k++ % 8) * 60 + 'ms';
      t.classList.add('in');
      io.unobserve(t);
      setTimeout(function () { t.style.transitionDelay = ''; }, 900);
    });
  }, { threshold: 0.08, rootMargin: '0px 0px -30px 0px' }) : null;

  function matches(t) { return cur === 'all' || (' ' + t.getAttribute('data-groups') + ' ').indexOf(' ' + cur + ' ') > -1; }
  function usable(t) { return !t.classList.contains('broken'); }

  function moveInd(btn) {
    ind.style.left = btn.offsetLeft + 'px';
    ind.style.width = btn.offsetWidth + 'px';
  }

  function layout(animate) {
    var all = tiles.filter(function (t) { return usable(t) && matches(t); });
    var vis = all.slice(0, limit);
    var idx = 0;
    tiles.forEach(function (t) {
      var show = vis.indexOf(t) > -1;
      t.classList.remove('big', 'tall', 'out');
      if (show) {
        var r = RHYTHM[idx % RHYTHM.length]; if (r) t.classList.add(r);
        idx++;
        t.classList.remove('gone');
        if (animate || !t.classList.contains('in')) { t.classList.remove('in'); if (io) { io.unobserve(t); io.observe(t); } else { t.classList.add('in'); } }
      } else {
        t.classList.add('gone'); t.classList.remove('in');
        if (io) io.unobserve(t);
      }
    });
    shownEl.textContent = vis.length;
    ofEl.textContent = all.length;
    var rest = all.length - vis.length;
    more.style.display = rest > 0 ? '' : 'none';
    leftEl.textContent = '(' + rest + ' more)';
    none.style.display = all.length === 0 ? 'block' : 'none';
  }

  function setFilter(f, btn) {
    if (f === cur) return;
    cur = f; limit = BATCH;
    btns.forEach(function (b) { b.classList.toggle('is-active', b === btn); b.setAttribute('aria-selected', b === btn); });
    moveInd(btn);
    // old tiles shrink away, then the new set pops in
    tiles.forEach(function (t) { if (!t.classList.contains('gone')) t.classList.add('out'); });
    setTimeout(function () { layout(true); }, reduce ? 0 : 230);
  }

  btns.forEach(function (b) { b.addEventListener('click', function () { setFilter(b.getAttribute('data-f'), b); }); });
  moreBtn.addEventListener('click', function () { limit += BATCH; layout(false); });
  window.addEventListener('resize', function () { var a = filters.querySelector('.is-active'); if (a) { ind.style.transition = 'none'; moveInd(a); void ind.offsetWidth; ind.style.transition = ''; } });

  // first paint
  var first = filters.querySelector('.is-active');
  ind.style.transition = 'none'; moveInd(first); void ind.offsetWidth; ind.style.transition = '';
  if (document.fonts && document.fonts.ready) document.fonts.ready.then(function () { var a = filters.querySelector('.is-active'); ind.style.transition = 'none'; moveInd(a); void ind.offsetWidth; ind.style.transition = ''; });
  layout(false);

  /* ---- lightbox (walks through the photos currently shown) ---- */
  var lb = document.getElementById('abG1Lb'), lbImg = document.getElementById('abG1LbImg'),
      lbCap = document.getElementById('abG1LbCap'), lbC = document.getElementById('abG1LbC');
  var list = [], pos = 0;
  function render() {
    var a = list[pos]; if (!a) return;
    var t = a.getAttribute('data-title'), s = a.getAttribute('data-sub');
    lbImg.style.animation = 'none'; void lbImg.offsetWidth; lbImg.style.animation = '';
    lbImg.src = a.getAttribute('href'); lbImg.alt = t || '';
    lbCap.innerHTML = '';
    lbCap.appendChild(document.createTextNode(t || ''));
    if (s) { var i = document.createElement('i'); i.textContent = s; lbCap.appendChild(i); }
    lbC.textContent = (pos + 1) + ' / ' + list.length;
  }
  function openLb(a) {
    list = tiles.filter(function (t) { return usable(t) && !t.classList.contains('gone'); });
    pos = Math.max(0, list.indexOf(a)); render();
    lb.classList.add('is-open'); document.body.style.overflow = 'hidden';
  }
  function closeLb() { lb.classList.remove('is-open'); document.body.style.overflow = ''; lbImg.src = ''; }
  function step(n) { pos = (pos + n + list.length) % list.length; render(); }
  grid.addEventListener('click', function (e) {
    var a = e.target.closest ? e.target.closest('.ab-g1-tile') : null;
    if (a) { e.preventDefault(); openLb(a); }
  });
  document.getElementById('abG1LbX').addEventListener('click', closeLb);
  document.getElementById('abG1LbP').addEventListener('click', function () { step(-1); });
  document.getElementById('abG1LbN').addEventListener('click', function () { step(1); });
  lb.addEventListener('click', function (e) { if (e.target === lb) closeLb(); });
  document.addEventListener('keydown', function (e) {
    if (!lb.classList.contains('is-open')) return;
    if (e.key === 'Escape') closeLb(); else if (e.key === 'ArrowLeft') step(-1); else if (e.key === 'ArrowRight') step(1);
  });
  var sx = null;
  lb.addEventListener('touchstart', function (e) { sx = e.touches[0].clientX; }, { passive: true });
  lb.addEventListener('touchend', function (e) {
    if (sx === null) return; var dx = e.changedTouches[0].clientX - sx;
    if (Math.abs(dx) > 50) step(dx < 0 ? 1 : -1); sx = null;
  });
})();
</script>

<?php require '../includes/footer.php'; ?>