<?php
$pageCss = 'animals.css';
require '../config/db.php';
require '../includes/functions.php';
animal_ensure_table($conn);

/* =====================================================
   helpers (all prefixed ad_)
   ===================================================== */
function ad_e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function ad_f($row, $keys, $default = '') {
    foreach ((array)$keys as $k) {
        if (isset($row[$k]) && !is_array($row[$k]) && trim((string)$row[$k]) !== '') return trim((string)$row[$k]);
    }
    return $default;
}
/* file name / path stored in the DB  ->  web path */
function ad_path($f) {
    $f = trim((string)$f);
    if ($f === '') return '';
    if (preg_match('#^(https?:)?//#i', $f) || $f[0] === '/' || strpos($f, '../') === 0) return $f;
    if (strpos($f, 'uploads/') === 0) return '../' . $f;
    if (strpos($f, '/') !== false) return '../' . $f;
    return '../uploads/animals/' . rawurlencode($f);
}
/* plain text from the rich-text editor HTML */
function ad_plain($html) {
    $html = preg_replace('/<\/(p|li|div|h\d)>|<br\s*\/?>/i', ' ', (string)$html);
    return trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($html), ENT_QUOTES, 'UTF-8')));
}
function ad_cut($s, $n) {
    if (function_exists('mb_strimwidth')) return mb_strimwidth($s, 0, $n, '…', 'UTF-8');
    return strlen($s) > $n ? substr($s, 0, $n - 1) . '…' : $s;
}
/* safe version of the rich-text editor HTML (bold, italic, lists, links only) */
function ad_rich($html) {
    $html = (string)$html;
    if ($html === strip_tags($html)) return nl2br(htmlspecialchars($html, ENT_QUOTES, 'UTF-8'));
    $html = strip_tags($html, '<p><br><strong><b><em><i><u><ul><ol><li><a><h3><h4>');
    return preg_replace_callback('/<(\/?)([a-z0-9]+)([^>]*)>/i', function ($m) {
        $tag = strtolower($m[2]);
        if ($m[1] === '/') return '</' . $tag . '>';
        if ($tag === 'a') {
            if (preg_match('/href\s*=\s*("|\')(.*?)\1/i', $m[3], $h) && preg_match('/^(https?:|mailto:|\/|#|[a-z0-9._-]+\.php)/i', trim($h[2]))) {
                return '<a href="' . htmlspecialchars(trim($h[2]), ENT_QUOTES, 'UTF-8') . '" rel="noopener">';
            }
            return '<a>';
        }
        return '<' . $tag . '>';
    }, $html);
}
function ad_label($key) { return ucwords(trim(str_replace(['_', '-'], ' ', $key))); }
/* JSON array or comma / new-line separated list  ->  array */
function ad_list($v) {
    $v = trim((string)$v);
    if ($v === '') return [];
    if ($v[0] === '[' || $v[0] === '{') {
        $d = json_decode($v, true);
        if (is_array($d)) return array_values($d);
    }
    return array_values(array_filter(array_map('trim', preg_split('/[,\n|]+/', $v)), 'strlen'));
}
function ad_tables($conn) {
    $t = [];
    try { $r = $conn->query('SHOW TABLES'); if ($r) { while ($row = $r->fetch_row()) { $t[] = $row[0]; } } } catch (Throwable $e) {}
    return $t;
}
function ad_cols($conn, $table) {
    $c = [];
    try { $r = $conn->query('SHOW COLUMNS FROM `' . str_replace('`', '', $table) . '`'); if ($r) { while ($row = $r->fetch_assoc()) { $c[] = $row['Field']; } } } catch (Throwable $e) {}
    return $c;
}
function ad_rows($conn, $sql, $id) {
    $out = [];
    try {
        $st = $conn->prepare($sql);
        if ($st) {
            $st->bind_param('i', $id);
            $st->execute();
            $rs = $st->get_result();
            if ($rs) { while ($r = $rs->fetch_assoc()) { $out[] = $r; } }
            $st->close();
        }
    } catch (Throwable $e) {}
    return $out;
}
function ad_icon_for($key) {
    $k = strtolower($key);
    if (preg_match('/life|age/', $k)) return 'clock';
    if (preg_match('/weight|mass/', $k)) return 'scale';
    if (preg_match('/length|height|size|tall/', $k)) return 'ruler';
    if (preg_match('/speed/', $k)) return 'speed';
    if (preg_match('/diet|food|prey/', $k)) return 'diet';
    if (preg_match('/habitat|range|region|terrain/', $k)) return 'mountain';
    if (preg_match('/group|social|herd|pride/', $k)) return 'group';
    if (preg_match('/status|population|trend/', $k)) return 'shield';
    if (preg_match('/species|scientific/', $k)) return 'leaf';
    return 'paw';
}
function ad_icon($name) {
    $p = [
        'paw'      => '<circle cx="6.5" cy="11" r="1.8"/><circle cx="10.5" cy="6.5" r="1.8"/><circle cx="15.5" cy="6.5" r="1.8"/><circle cx="19" cy="11" r="1.8"/><path d="M12.8 12c-3 0-6 3-5.5 6 .4 2 2.4 2.4 5.5 2.4s5.1-.4 5.5-2.4c.5-3-2.5-6-5.5-6z"/>',
        'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'scale'    => '<path d="M12 4v16M7 20h10M5 8l7-2 7 2"/><path d="M5 8l-2.5 6a3 3 0 0 0 5 0z"/><path d="M19 8l-2.5 6a3 3 0 0 0 5 0z"/>',
        'ruler'    => '<path d="M3 16L16 3l5 5L8 21z"/><path d="M7 12l2 2M10 9l2 2M13 6l2 2"/>',
        'speed'    => '<path d="M4 18a9 9 0 1 1 16 0"/><path d="M12 14l4-5"/><circle cx="12" cy="14" r="1"/>',
        'leaf'     => '<path d="M5 19c0-8.5 5-13.5 14-14 0 9-5 14-13.5 14"/><path d="M5 19c2-4.5 5-7.5 9-10"/>',
        'shield'   => '<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M9 12l2 2 4-4"/>',
        'mountain' => '<path d="M2.5 19.5l6.5-11 4 6.5 2.5-3.5 6 8z"/>',
        'diet'     => '<path d="M7 3v8M5 3v5a2 2 0 0 0 4 0V3M7 11v10M17 3c-2 2-3 5-3 8h3v10"/>',
        'group'    => '<circle cx="9" cy="8" r="3"/><path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6"/><path d="M16.5 5.5a2.6 2.6 0 0 1 0 5M17 14c2.5.4 4 2.4 4 5"/>',
        'home'     => '<path d="M3 11l9-7 9 7"/><path d="M5 10v10h14V10"/><path d="M10 20v-6h4v6"/>',
        'moon'     => '<path d="M20 14.5A8 8 0 0 1 9.5 4 8 8 0 1 0 20 14.5z"/>',
        'target'   => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1"/>',
        'arrow'    => '<path d="M7 17L17 7M8 7h9v9"/>',
        'bulb'     => '<path d="M9 18h6M10 21h4"/><path d="M12 3a6 6 0 0 0-3.5 10.9c.6.5 1 1.2 1 2.1h5c0-.9.4-1.6 1-2.1A6 6 0 0 0 12 3z"/>',
        'help'     => '<circle cx="12" cy="12" r="9"/><path d="M9.5 9.5a2.5 2.5 0 1 1 3.5 2.3c-.7.4-1 1-1 1.7M12 17v.1"/>',
        'image'    => '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="1.6"/><path d="M21 16l-5-5-9 9"/>',
    ];
    return '<svg viewBox="0 0 24 24" aria-hidden="true">' . ($p[$name] ?? $p['paw']) . '</svg>';
}

/* =====================================================
   load the animal (same query as before)
   ===================================================== */
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$animal = null;

$stmt = $conn->prepare("SELECT a.*, h.name AS habitat FROM animals a LEFT JOIN habitats h ON h.id = a.habitat_id WHERE a.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    $animal = $res->fetch_assoc();
}

$pageTitle = $animal ? $animal['name'] : 'Animal Not Found';

if ($animal) {
    $name      = $animal['name'];
    $status    = ad_f($animal, 'conservation_status');
    $category  = ad_f($animal, 'category');
    $catLabel  = $category !== '' ? (substr($category, -2) === 'ss' ? $category : preg_replace('/s$/i', '', $category)) : 'Wildlife';
    $descHtml  = ad_f($animal, 'description');
    $shortDesc = ad_f($animal, 'short_description');
    $heroText  = $shortDesc !== '' ? $shortDesc : ad_cut(ad_plain($descHtml), 210);
    $funFact   = ad_f($animal, 'fun_fact');
    $mainImg   = ad_path(ad_f($animal, 'image'));

    /* ---------- gallery / FAQs / related: child tables first, then JSON columns ---------- */
    $gallery = []; $faqs = []; $relatedIds = [];
    foreach (ad_tables($conn) as $t) {
        if (strcasecmp($t, 'animals') === 0 || stripos($t, 'animal') !== 0) continue;
        $cols = ad_cols($conn, $t);
        if (!in_array('animal_id', $cols, true)) continue;
        $order = '';
        foreach (['sort_order', 'display_order', 'position', 'id'] as $oc) {
            if (in_array($oc, $cols, true)) { $order = ' ORDER BY `' . $oc . '` ASC'; break; }
        }
        $rows = ad_rows($conn, 'SELECT * FROM `' . str_replace('`', '', $t) . '` WHERE animal_id = ?' . $order, $id);
        if (in_array('question', $cols, true) && in_array('answer', $cols, true)) {
            foreach ($rows as $r) { if (trim((string)$r['question']) !== '') $faqs[] = [$r['question'], (string)$r['answer']]; }
        } elseif (array_intersect(['related_animal_id', 'related_id'], $cols)) {
            foreach ($rows as $r) { $rid = (int)($r['related_animal_id'] ?? $r['related_id'] ?? 0); if ($rid > 0) $relatedIds[] = $rid; }
        } else {
            $imgCol = null;
            foreach (['image', 'image_path', 'image_url', 'file_name', 'filename', 'file', 'path', 'photo', 'url'] as $c) {
                if (in_array($c, $cols, true)) { $imgCol = $c; break; }
            }
            if ($imgCol) {
                foreach ($rows as $r) {
                    if (trim((string)$r[$imgCol]) !== '') $gallery[] = ['src' => ad_path($r[$imgCol]), 'alt' => ad_f($r, ['alt_text', 'alt', 'caption', 'title'], $name)];
                }
            }
        }
    }
    if (!$gallery) {
        foreach (['gallery', 'gallery_images', 'images'] as $k) {
            if (!empty($animal[$k])) {
                foreach (ad_list($animal[$k]) as $it) {
                    $f = is_array($it) ? ad_f($it, ['image', 'file', 'path', 'url', 'src', 'filename']) : $it;
                    if ($f !== '') $gallery[] = ['src' => ad_path($f), 'alt' => is_array($it) ? ad_f($it, ['alt', 'alt_text'], $name) : $name];
                }
                break;
            }
        }
    }
    if (!$faqs) {
        foreach (['faqs', 'faq'] as $k) {
            if (!empty($animal[$k])) {
                foreach (ad_list($animal[$k]) as $it) {
                    if (is_array($it)) {
                        $q = ad_f($it, ['question', 'q', 'title']); $a = ad_f($it, ['answer', 'a', 'text']);
                        if ($q !== '') $faqs[] = [$q, $a];
                    }
                }
                break;
            }
        }
    }
    if (!$relatedIds) {
        foreach (['related_animals', 'related', 'related_ids'] as $k) {
            if (!empty($animal[$k])) { foreach (ad_list($animal[$k]) as $it) { $rid = (int)(is_array($it) ? ($it['id'] ?? 0) : $it); if ($rid > 0) $relatedIds[] = $rid; } break; }
        }
    }
    $seen = []; $g2 = [];
    foreach ($gallery as $g) { if (!isset($seen[$g['src']])) { $seen[$g['src']] = 1; $g2[] = $g; } }
    $gallery = $g2;

    /* ---------- every other filled-in admin field ---------- */
    $skip = '/^(id|slug|status|featured|display_order|sort_order|views?|views_count|name|description|short_description|scientific_name|no_?index|no_?follow|robots|species|diet|lifespan|fun_fact|conservation_status|image|category|habitat|is_.*|show_.*|.*_id|.*_at|seo_.*|meta_.*|og_.*|canonical.*|.*image.*|.*gallery.*|alt|alt_text|.*_alt|.*credit.*|.*faq.*|related.*)$/i';
    $classif = []; $range = []; $extraFacts = []; $behItems = []; $blocks = [];
    $tagline = '';
    foreach ($animal as $k => $v) {
        if (preg_match($skip, $k) || $v === null || is_array($v)) continue;
        $v = trim((string)$v);
        if ($v === '' || $v[0] === '[' || $v[0] === '{') continue;
        if (preg_match('/^(tagline|nickname|subtitle)$/i', $k)) { $tagline = $v; continue; }
        $isLong = (strlen(ad_plain($v)) > 110 || stripos($v, '<p') !== false || strpos($v, "\n") !== false);
        if (!$isLong && preg_match('/^(social.*|activity.*|hunting.*|feeding.*)$/i', $k)) { $behItems[] = [ad_label($k), preg_match('/social|group/i', $k) ? 'home' : (preg_match('/activity/i', $k) ? 'moon' : 'target'), $v]; continue; }
        if (!$isLong && preg_match('/^(kingdom|phylum|class|order|family|genus|subfamily|tribe|suborder)$/i', $k)) { $classif[] = [ad_label($k), $v]; continue; }
        if (!$isLong && preg_match('/(range|region|continent|countr|geograph|location|distribution|climate|terrain|native)/i', $k)) { $range[] = [ad_label($k), $v]; continue; }
        if ($isLong) { $blocks[] = [$k, ad_label($k), $v]; continue; }
        $extraFacts[] = [ad_label($k), $v, $k];
    }

    /* long text blocks: habitat/behaviour + conservation get their own sections, the rest go to "More about" */
    $habText = ''; $consText = ''; $moreBlocks = [];
    foreach ($blocks as $b) {
        if ($habText === '' && preg_match('/habitat|behav|range/i', $b[0])) { $habText = $b[2]; continue; }
        if ($consText === '' && preg_match('/conserv|threat|protect/i', $b[0])) { $consText = $b[2]; continue; }
        $moreBlocks[] = $b;
    }
    if ($consText === '' && $status !== '') {
        $consText = $name . ' is currently classified as ' . $status . '. Conservation efforts focus on protecting natural habitats, reducing conflict, and preserving wildlife for future generations.';
    }

    /* ---------- hero stats (first 3 available) ---------- */
    $heroStats = [];
    foreach (['lifespan', 'weight', 'length', 'body_length', 'height', 'shoulder_height', 'size', 'top_speed', 'speed'] as $hk) {
        if (count($heroStats) >= 3) break;
        $v = ad_f($animal, $hk);
        if ($v === '') continue;
        if (preg_match('/^(.*?)\s*\((.*)\)\s*$/u', $v, $m)) { $main = trim($m[1]); $note = '(' . trim($m[2]) . ')'; } else { $main = $v; $note = ''; }
        $heroStats[] = [ad_label($hk), ad_icon_for($hk), $main, $note];
    }

    /* ---------- quick facts ---------- */
    $facts = [];
    foreach ([['Species', 'species', 'leaf'], ['Habitat', 'habitat', 'mountain'], ['Diet', 'diet', 'diet'], ['Lifespan', 'lifespan', 'clock']] as $f) {
        $v = ad_f($animal, $f[1]);
        if ($v !== '') $facts[] = [$f[0], $f[2], $v, ''];
    }
    foreach ($extraFacts as $ef) { $facts[] = [$ef[0], ad_icon_for($ef[2]), $ef[1], '']; }
    if ($status !== '') $facts[] = ['Conservation Status', 'shield', $status, 'pill'];

    /* ---------- related animals ---------- */
    $related = [];
    $relatedIds = array_values(array_unique(array_filter(array_map('intval', $relatedIds), function ($x) use ($id) { return $x > 0 && $x !== $id; })));
    if ($relatedIds) {
        $idList = implode(',', $relatedIds);
        try {
            $rs = $conn->query("SELECT id, name, image FROM animals WHERE status = 'published' AND id IN ($idList) ORDER BY FIELD(id, $idList)");
            if ($rs) { while ($r = $rs->fetch_assoc()) { $related[] = $r; } }
        } catch (Throwable $e) {}
    }
    $related = array_slice($related, 0, 4);
    if (count($related) < 4) {
        $skipIds = array_merge([$id], array_map(function ($r) { return (int)$r['id']; }, $related));
        $skipSql = implode(',', array_map('intval', $skipIds));
        $need = 4 - count($related);
        try {
            if ($category !== '') {
                $st = $conn->prepare("SELECT id, name, image FROM animals WHERE status = 'published' AND category = ? AND id NOT IN ($skipSql) ORDER BY name ASC LIMIT $need");
                if ($st) { $st->bind_param('s', $category); $st->execute(); $rs = $st->get_result(); if ($rs) { while ($r = $rs->fetch_assoc()) { $related[] = $r; } } $st->close(); }
            }
            if (count($related) < 4) {
                $skipSql = implode(',', array_map('intval', array_merge($skipIds, array_map(function ($r) { return (int)$r['id']; }, $related))));
                $need = 4 - count($related);
                $rs = $conn->query("SELECT id, name, image FROM animals WHERE status = 'published' AND id NOT IN ($skipSql) ORDER BY name ASC LIMIT $need");
                if ($rs) { while ($r = $rs->fetch_assoc()) { $related[] = $r; } }
            }
        } catch (Throwable $e) {}
    }

    $related = array_slice($related, 0, 4);
    $habImg  = isset($gallery[1]) ? $gallery[1]['src'] : $mainImg;
    $consImg = isset($gallery[2]) ? $gallery[2]['src'] : $mainImg;
    $showHab = ($habText !== '' || count($behItems) > 0);
}

require '../includes/header.php';
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap');

/* =====================================================
   Animal details — same Safari theme as the home page
   (all classes are prefixed "ad-" so nothing else is touched)
   ===================================================== */
.ad-page {
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
.ad-page, .ad-page * { box-sizing: border-box; }
:where(.ad-page) :is(h1, h2, h3, p) { margin: 0; }
.ad-page svg { display: block; }
.ad-wrap { max-width: 1400px; margin: 0 auto; padding: 0 clamp(16px, 2.2vw, 40px); position: relative; }
.ad-sec { padding: clamp(16px, 2.2vw, 28px) 0; }

/* ---------- buttons ---------- */
.ad-btn {
  display: inline-flex; align-items: center; gap: 8px; padding: 11px 26px;
  font-size: .9rem; font-weight: 700; text-decoration: none; cursor: pointer;
  background: var(--sw-orange); color: var(--sw-forest); border: 2px solid var(--sw-orange); border-radius: 999px;
  transition: background .25s, color .25s, border-color .25s, transform .25s;
}
.ad-btn:hover { background: var(--sw-forest); border-color: var(--sw-forest); color: var(--sw-white); transform: translateY(-2px); }
.ad-btn.is-outline { background: var(--sw-white); }
.ad-btn.is-outline:hover { background: var(--sw-orange); border-color: var(--sw-orange); color: var(--sw-forest); }

/* ---------- section headings ---------- */
.ad-h2 {
  display: flex; align-items: center; gap: 10px; margin-bottom: 14px;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.15rem, 1.7vw, 1.4rem); font-weight: 700; line-height: 1.15; color: var(--sw-forest);
}
.ad-h2 > svg { width: 24px; height: 24px; flex: 0 0 auto; fill: none; stroke: var(--sw-gold); stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
.ad-h2::after { content: ""; width: 44px; height: 2px; background: linear-gradient(90deg, var(--sw-gold), rgba(200,155,60,0)); }
.ad-sub { margin: -6px 0 0 34px; font-size: .82rem; color: var(--sw-text); opacity: .8; }
.ad-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; margin-bottom: 16px; }
.ad-head .ad-h2 { margin-bottom: 10px; }
.ad-link { font-size: .8rem; font-weight: 700; color: var(--sw-forest); text-decoration: none; white-space: nowrap; padding-top: 6px; }
.ad-link:hover { color: var(--sw-gold); }

/* =====================================================
   HERO
   ===================================================== */
.ad-hero {
  position: relative; display: flex; align-items: center; overflow: hidden;
  min-height: clamp(400px, 34vw, 500px);
  background: linear-gradient(160deg, #2E5B44, #0B2F22);
  color: var(--sw-white);
}
.ad-hero-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: 72% 35%; }
.ad-hero::before {
  content: ""; position: absolute; inset: 0; z-index: 1;
  background: linear-gradient(90deg, rgba(11,47,34,.95) 0%, rgba(11,47,34,.8) 30%, rgba(11,47,34,.25) 60%, rgba(11,47,34,0) 100%);
}
.ad-hero-inner { position: relative; z-index: 2; width: 100%; padding-top: clamp(28px, 4vw, 48px); padding-bottom: clamp(28px, 4vw, 48px); }
.ad-hero-copy { max-width: 500px; }

.ad-crumbs { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-bottom: 18px; font-size: .8rem; color: rgba(255,253,248,.85); }
.ad-crumbs a { color: inherit; text-decoration: none; }
.ad-crumbs a:hover { color: var(--sw-orange); }
.ad-crumbs svg { width: 14px; height: 14px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
.ad-crumbs i { font-style: normal; opacity: .6; }
.ad-crumbs span[aria-current] { color: var(--sw-white); }

.ad-badge {
  display: inline-flex; align-items: center; gap: 6px; padding: 5px 13px; margin-bottom: 14px;
  font-size: .75rem; font-weight: 700; color: var(--sw-forest);
  background: var(--sw-white); border-radius: 999px;
}
.ad-badge svg { width: 14px; height: 14px; fill: none; stroke: var(--sw-forest); stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
.ad-hero h1 {
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(2.3rem, 4.4vw, 3.6rem); font-weight: 700; line-height: 1.05; letter-spacing: -.005em;
  color: var(--sw-white);
}
.ad-tagline {
  margin-top: 6px; font-family: 'Playfair Display', Georgia, serif; font-style: italic; font-weight: 600;
  font-size: clamp(1.2rem, 2vw, 1.6rem); color: var(--sw-orange);
}
.ad-hero-text { margin-top: 14px; max-width: 430px; font-size: .95rem; line-height: 1.7; color: rgba(255,253,248,.92); }
.ad-stats { display: flex; flex-wrap: wrap; gap: 14px 26px; margin-top: 24px; }
.ad-stat { display: flex; align-items: center; gap: 10px; }
.ad-stat-ico {
  width: 40px; height: 40px; border-radius: 50%; display: grid; place-items: center; flex: 0 0 auto;
  border: 1.5px solid rgba(255,253,248,.55); background: rgba(255,253,248,.08); color: #F3C48C;
}
.ad-stat-ico svg { width: 20px; height: 20px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
.ad-stat div b { display: block; font-size: .8rem; line-height: 1.3; }
.ad-stat div span { display: block; font-size: .78rem; line-height: 1.3; color: rgba(255,253,248,.85); }

/* brush-stroke label used on the hero, habitat and conservation pictures */
.ad-brush {
  background: var(--sw-forest);
  clip-path: polygon(1% 18%, 14% 6%, 38% 12%, 62% 3%, 88% 10%, 99% 4%, 100% 48%, 97% 88%, 78% 96%, 52% 90%, 28% 99%, 8% 92%, 0 70%);
  box-shadow: 0 8px 20px rgba(0,0,0,.3);
}
.ad-hero-badge {
  position: absolute; z-index: 3; right: clamp(16px, 4vw, 64px); bottom: 30px;
  width: 210px; padding: 20px 28px 22px; transform: rotate(-4deg);
  font-family: 'Playfair Display', Georgia, serif; font-style: italic; font-weight: 600; font-size: 1.1rem; line-height: 1.25;
  text-align: center; color: var(--sw-white);
}
.ad-hero-badge span { display: block; color: var(--sw-orange); }

/* =====================================================
   ABOUT + QUICK FACTS
   ===================================================== */
.ad-about-grid { display: grid; grid-template-columns: minmax(0, 1.05fr) minmax(340px, .95fr); gap: clamp(20px, 2.6vw, 40px); align-items: start; }
.ad-minis { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-top: 22px; }
.ad-minis .ad-mini { margin: 0; }

.ad-facts {
  position: relative; padding: 16px 22px 8px;
  background: var(--sw-white); border: 1px solid rgba(200,155,60,.4); border-radius: 14px;
  box-shadow: 0 8px 24px rgba(23,59,42,.12);
}
.ad-facts .ad-h2 { margin-bottom: 6px; }
.ad-fact { display: grid; grid-template-columns: 36px minmax(110px, .55fr) minmax(0, 1.45fr); align-items: center; gap: 12px; padding: 9px 0; border-bottom: 1px solid rgba(23,59,42,.12); }
.ad-fact:last-child { border-bottom: 0; }
.ad-fact-ico { width: 34px; height: 34px; border-radius: 50%; display: grid; place-items: center; background: #E4ECD6; color: var(--sw-forest); }
.ad-fact-ico svg { width: 18px; height: 18px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
.ad-fact b { font-size: .85rem; color: var(--sw-forest); }
.ad-fact span { font-size: .85rem; }
.ad-fact span.sci { font-style: italic; }
.ad-pill {
  display: inline-block; padding: 3px 13px; border-radius: 999px; font-size: .74rem; font-weight: 700;
  color: var(--sw-forest); background: rgba(232,161,91,.5); border: 1px solid rgba(232,161,91,.9);
}
.ad-sprig { position: absolute; pointer-events: none; opacity: .75; z-index: 0; }
.ad-sprig svg { width: 100%; height: 100%; }
.ad-sprig.is-a { width: 54px; height: 82px; left: -34px; bottom: -34px; }
.ad-sprig.is-b { width: 48px; height: 72px; right: -18px; bottom: -34px; transform: scaleX(-1) rotate(8deg); }

/* =====================================================
   GALLERY
   ===================================================== */
.ad-gallery { display: grid; grid-template-columns: repeat(var(--n, 5), minmax(0, 1fr)); gap: 14px; }
.ad-gallery a { display: block; aspect-ratio: 4 / 3; border-radius: 10px; overflow: hidden; background: linear-gradient(160deg, #2E5B44, #173B2A); box-shadow: 0 6px 16px rgba(23,59,42,.16); }
.ad-gallery img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s ease; }
.ad-gallery a:hover img { transform: scale(1.06); }

/* =====================================================
   HABITAT & BEHAVIOR
   ===================================================== */
.ad-hab-grid { display: grid; grid-template-columns: minmax(0, .8fr) minmax(0, 1fr); gap: clamp(20px, 3vw, 44px); align-items: center; }
.ad-paper { position: relative; padding: 0 0 10px 6px; }
.ad-paper::before {
  content: ""; position: absolute; inset: -6px 4px 4px 0; border-radius: 18px;
  background: linear-gradient(90deg, #F6EEDD, #EADFC8); transform: rotate(-1.4deg);
  box-shadow: 0 6px 14px rgba(23,59,42,.18);
}
.ad-pic {
  position: relative; overflow: hidden; aspect-ratio: 16 / 9; border-radius: 16px;
  background: linear-gradient(160deg, #2E5B44, #173B2A); box-shadow: 0 10px 26px rgba(23,59,42,.22);
}
.ad-pic img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
.ad-cap {
  position: absolute; z-index: 2; left: 14px; bottom: 16px; padding: 14px 30px 16px 22px;
  font-family: 'Playfair Display', Georgia, serif; font-style: italic; font-weight: 600; font-size: .98rem; line-height: 1.25; color: var(--sw-white);
}
.ad-cap span { display: block; color: var(--sw-orange); }
.ad-hab-copy { position: relative; }
.ad-beh { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 18px; }
.ad-beh-item { display: flex; align-items: flex-start; gap: 10px; }
.ad-beh-ico { width: 42px; height: 42px; border-radius: 50%; display: grid; place-items: center; flex: 0 0 auto; background: var(--sw-forest); color: var(--sw-white); }
.ad-beh-ico svg { width: 20px; height: 20px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
.ad-beh-item b { display: block; font-size: .85rem; color: var(--sw-forest); margin-bottom: 2px; }
.ad-beh-item div span { display: block; font-size: .8rem; line-height: 1.45; }
.ad-hab-copy .ad-sprig { right: -8px; bottom: -24px; width: 52px; height: 78px; opacity: .55; }

/* =====================================================
   CONSERVATION & THREATS
   ===================================================== */
.ad-cons {
  position: relative; display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, .95fr); gap: clamp(20px, 3vw, 40px); align-items: center;
  padding: clamp(20px, 2.4vw, 30px); background: #EEF1E3; border: 1px solid rgba(23,59,42,.1); border-radius: 14px;
}
.ad-cons-copy { display: flex; align-items: flex-start; gap: 14px; }
.ad-cons-ico { width: 40px; height: 40px; border-radius: 50%; display: grid; place-items: center; flex: 0 0 auto; background: #DFE8CE; color: var(--sw-forest); border: 1px solid var(--sw-gold); }
.ad-cons-ico svg { width: 20px; height: 20px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
.ad-cons-copy .ad-h2 { margin-bottom: 8px; }
.ad-cons-copy p strong { color: var(--sw-forest); }
.ad-cons-copy .ad-btn { padding: 9px 22px; font-size: .84rem; }
.ad-cons-pic { position: relative; overflow: hidden; aspect-ratio: 16 / 7; border-radius: 70px 12px 70px 12px; background: linear-gradient(160deg, #2E5B44, #173B2A); box-shadow: 0 10px 24px rgba(23,59,42,.22); }
.ad-cons-pic img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
.ad-cons-pic::after { content: ""; position: absolute; inset: 0; background: linear-gradient(to top, rgba(11,47,34,.7), rgba(11,47,34,0) 55%); }
.ad-cons-quote {
  position: absolute; z-index: 2; right: 22px; bottom: 16px; display: flex; align-items: center; gap: 8px;
  font-family: 'Playfair Display', Georgia, serif; font-style: italic; font-weight: 600; font-size: .98rem; line-height: 1.2; text-align: right; color: var(--sw-white);
}
.ad-cons-quote svg { width: 20px; height: 20px; fill: none; stroke: var(--sw-orange); stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }

/* =====================================================
   RELATED ANIMALS
   ===================================================== */
.ad-related { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
.ad-rel {
  position: relative; display: flex; align-items: center; gap: 12px; padding: 8px 12px 8px 8px; text-decoration: none; color: var(--sw-forest);
  background: var(--sw-white); border: 1px solid rgba(200,155,60,.4); border-radius: 12px; box-shadow: 0 6px 18px rgba(23,59,42,.1);
  transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
}
.ad-rel:hover { transform: translateY(-3px); border-color: var(--sw-gold); box-shadow: 0 12px 26px rgba(23,59,42,.18); }
.ad-rel-img { width: 84px; height: 58px; flex: 0 0 auto; border-radius: 8px; overflow: hidden; background: linear-gradient(160deg, #2E5B44, #173B2A); }
.ad-rel-img img { width: 100%; height: 100%; object-fit: cover; }
.ad-rel b { flex: 1 1 auto; font-size: .85rem; line-height: 1.25; }
.ad-rel-go { width: 26px; height: 26px; border-radius: 50%; display: grid; place-items: center; flex: 0 0 auto; align-self: flex-start; border: 1px solid var(--sw-gold); color: var(--sw-forest); transition: background .25s, color .25s; }
.ad-rel-go svg { width: 13px; height: 13px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
.ad-rel:hover .ad-rel-go { background: var(--sw-forest); color: var(--sw-orange); }

/* =====================================================
   RESPONSIVE
   ===================================================== */
@media (max-width: 1000px) {
  .ad-related { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (max-width: 900px) {
  .ad-about-grid, .ad-hab-grid, .ad-cons { grid-template-columns: minmax(0, 1fr); }
  .ad-gallery { grid-template-columns: repeat(3, minmax(0, 1fr)); }
  .ad-hero::before { background: linear-gradient(180deg, rgba(11,47,34,.55) 0%, rgba(11,47,34,.9) 100%); }
  .ad-hero-badge { display: none; }
  .ad-sprig { display: none; }
  .ad-hab-visual { order: -1; }
}
@media (max-width: 560px) {
  .ad-gallery { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  .ad-beh { grid-template-columns: minmax(0, 1fr); }
  .ad-related { grid-template-columns: minmax(0, 1fr); }
  .ad-fact { grid-template-columns: 36px minmax(0, 1fr); }
  .ad-fact > span:last-child { grid-column: 2; margin-top: -6px; }
  .ad-head { flex-wrap: wrap; }
  .ad-btn { width: 100%; justify-content: center; }
  .ad-cons-pic { aspect-ratio: 16 / 10; }
}
@media (prefers-reduced-motion: reduce) {
  .ad-page *, .ad-page *::before, .ad-page *::after { transition: none !important; animation: none !important; }
}

/* =====================================================
   ADDED — rich text, fun fact, small cards, FAQ, lightbox
   ===================================================== */
.ad-rich { font-size: .92rem; line-height: 1.75; }
.ad-rich p { margin: 0 0 14px; text-align: justify; hyphens: manual; }
.ad-rich ul, .ad-rich ol { margin: 0 0 14px; padding-left: 22px; }
.ad-rich li { margin-bottom: 4px; }
.ad-rich a { color: var(--sw-forest); font-weight: 700; text-decoration: underline; text-underline-offset: 3px; }
.ad-rich a:hover { color: var(--sw-gold); }
.ad-rich > :last-child { margin-bottom: 0; }
.ad-about-copy .ad-rich { margin-bottom: 20px; }
.ad-hab-copy > .ad-rich { margin-bottom: 22px; }
.ad-cons-copy .ad-rich { margin-bottom: 16px; font-size: .86rem; line-height: 1.7; }
.ad-cons-copy .ad-rich p { text-align: left; }

.ad-funfact {
  display: flex; gap: 14px; align-items: flex-start; margin: 0 0 22px; padding: 14px 18px;
  background: rgba(232,161,91,.16); border-left: 4px solid var(--sw-orange); border-radius: 0 12px 12px 0;
}
.ad-funfact-ico { width: 34px; height: 34px; flex: 0 0 auto; border-radius: 50%; display: grid; place-items: center; background: var(--sw-orange); color: var(--sw-forest); }
.ad-funfact-ico svg { width: 18px; height: 18px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
.ad-funfact b { display: block; margin-bottom: 2px; font-family: 'Playfair Display', Georgia, serif; font-size: .98rem; color: var(--sw-forest); }
.ad-funfact span { font-size: .88rem; line-height: 1.6; }

.ad-side { display: grid; gap: 18px; }
.ad-mini { padding: 16px 22px 10px; background: var(--sw-white); border: 1px solid rgba(200,155,60,.4); border-radius: 14px; box-shadow: 0 8px 24px rgba(23,59,42,.1); }
.ad-mini .ad-h2 { margin-bottom: 6px; font-size: 1.05rem; }
.ad-mini-row { display: grid; grid-template-columns: minmax(0, .9fr) minmax(0, 1.1fr); gap: 12px; padding: 9px 0; border-bottom: 1px solid rgba(23,59,42,.12); font-size: .84rem; }
.ad-mini-row:last-child { border-bottom: 0; }
.ad-mini-row b { color: var(--sw-forest); }

.ad-gallery a { position: relative; cursor: zoom-in; }
.ad-gallery .ad-plus { position: absolute; inset: 0; display: grid; place-items: center; background: rgba(11,47,34,.62); color: var(--sw-white); font-family: 'Playfair Display', Georgia, serif; font-size: 1.6rem; font-weight: 700; }
.ad-link-btn { background: none; border: 0; cursor: pointer; font-family: inherit; }

.ad-moregrid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
.ad-more-card { padding: 22px 24px; background: var(--sw-white); border: 1px solid rgba(200,155,60,.4); border-top: 3px solid var(--sw-gold); border-radius: 14px; box-shadow: 0 8px 24px rgba(23,59,42,.1); }
.ad-more-card .ad-h2 { font-size: 1.1rem; margin-bottom: 10px; }

.ad-faq-band { background: var(--sw-forest); color: var(--sw-white); padding: clamp(32px, 4.5vw, 56px) 0; }
.ad-faq-band .ad-h2 { color: var(--sw-white); }
.ad-faq-band .ad-h2 > svg { stroke: var(--sw-orange); }
.ad-faq-band .ad-h2::after { background: linear-gradient(90deg, var(--sw-orange), rgba(232,161,91,0)); }
.ad-faq-list { max-width: 900px; border-top: 1px solid rgba(200,155,60,.35); }
.ad-faq-item { border-bottom: 1px solid rgba(200,155,60,.35); }
.ad-faq-item summary {
  display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 15px 4px; cursor: pointer; list-style: none;
  font-size: .98rem; font-weight: 700; line-height: 1.35;
}
.ad-faq-item summary::-webkit-details-marker { display: none; }
.ad-faq-item summary::after {
  content: "+"; flex: 0 0 auto; width: 28px; height: 28px; border-radius: 50%; display: grid; place-items: center;
  border: 1px solid rgba(232,161,91,.7); color: var(--sw-orange); font-size: 1.1rem; line-height: 1; transition: background .25s, color .25s;
}
.ad-faq-item[open] summary { color: var(--sw-orange); }
.ad-faq-item[open] summary::after { content: "–"; background: var(--sw-orange); color: var(--sw-forest); }
.ad-faq-item summary:focus-visible { outline: 2px solid var(--sw-orange); outline-offset: 2px; }
.ad-faq-item .ad-rich { padding: 0 48px 16px 4px; color: rgba(255,253,248,.88); font-size: .9rem; }
.ad-faq-item .ad-rich a { color: var(--sw-orange); }

.ad-lb { position: fixed; inset: 0; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 24px; background: rgba(11,47,34,.94); }
.ad-lb[hidden] { display: none; }
.ad-lb img { max-width: min(92vw, 1100px); max-height: 80vh; border-radius: 12px; box-shadow: 0 20px 50px rgba(0,0,0,.5); background: #0B2F22; }
.ad-lb button {
  position: absolute; width: 46px; height: 46px; border-radius: 50%; display: grid; place-items: center; cursor: pointer; padding: 0;
  background: var(--sw-white); color: var(--sw-forest); border: 2px solid var(--sw-gold); font-size: 1.3rem; line-height: 1;
}
.ad-lb button:hover { background: var(--sw-orange); }
.ad-lb-close { top: 18px; right: 18px; }
.ad-lb-prev { left: 18px; top: 50%; transform: translateY(-50%); }
.ad-lb-next { right: 18px; top: 50%; transform: translateY(-50%); }
.ad-lb-count { position: absolute; bottom: 20px; left: 0; right: 0; text-align: center; font-size: .85rem; color: rgba(255,253,248,.85); }

@media (max-width: 900px) { .ad-moregrid { grid-template-columns: minmax(0, 1fr); } }
@media (max-width: 560px) { .ad-faq-item .ad-rich { padding-right: 8px; } .ad-lb-prev { left: 8px; } .ad-lb-next { right: 8px; } }

/* ---------- layout polish: no big empty areas ---------- */
.ad-gallery.is-mid a { aspect-ratio: 16 / 10; }
.ad-gallery.is-few a { aspect-ratio: 21 / 9; }

.ad-hab-grid {
  gap: 0; align-items: stretch; overflow: hidden;
  background: #F4F1E4; border: 1px solid rgba(200,155,60,.4); border-radius: 18px;
  box-shadow: 0 8px 24px rgba(23,59,42,.1);
}
.ad-hab-visual { position: relative; min-height: 280px; }
.ad-paper { position: absolute; inset: 0; padding: 0; }
.ad-paper::before { display: none; }
.ad-hab-grid .ad-pic { position: absolute; inset: 0; aspect-ratio: auto; border-radius: 0; box-shadow: none; }
.ad-hab-copy { display: flex; flex-direction: column; justify-content: center; padding: clamp(22px, 3vw, 44px); }
.ad-hab-copy > .ad-rich { margin-bottom: 16px; }
.ad-hab-copy .ad-sprig { right: 14px; bottom: 6px; width: 46px; height: 68px; opacity: .5; }
.ad-chips { display: flex; flex-wrap: wrap; gap: 10px; }
.ad-chip { display: inline-flex; align-items: center; gap: 8px; padding: 7px 14px; border-radius: 999px; background: #E4ECD6; color: var(--sw-forest); font-size: .82rem; }
.ad-chip svg { width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
.ad-chip b { font-weight: 700; }
.ad-chip b::after { content: ":"; }
@media (max-width: 900px) {
  .ad-gallery.is-few a, .ad-gallery.is-mid a { aspect-ratio: 4 / 3; }
  .ad-hab-visual { min-height: 220px; }
}

/* ---------- banner: image only, no text on top ---------- */
.ad-hero::before { display: none; }
.ad-hero { min-height: clamp(300px, 30vw, 460px); }
.ad-sr { position: absolute; width: 1px; height: 1px; margin: -1px; padding: 0; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }
</style>

<!-- decorative leaf sprig (reused) -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
  <symbol id="adSprig" viewBox="0 0 80 120">
    <path d="M40 118 C40 80 42 40 40 4" stroke="#9DB08A" stroke-width="2" fill="none" stroke-linecap="round"/>
    <g fill="#C5D3B0">
      <path d="M40 100 C20 96 8 82 6 66 C24 68 38 80 40 100z"/>
      <path d="M40 84 C60 80 72 66 74 50 C56 52 42 64 40 84z"/>
      <path d="M40 62 C22 58 12 46 10 32 C26 34 38 46 40 62z"/>
      <path d="M40 44 C58 40 68 28 70 14 C54 16 42 28 40 44z"/>
      <path d="M40 24 C32 20 28 12 28 4 C36 6 40 14 40 24z"/>
    </g>
  </symbol>
</svg>

<?php if ($animal): ?>
<main class="ad-page">

  <!-- ============ HERO ============ -->
  <section class="ad-hero">
    <?php if ($mainImg !== ''): ?>
      <img class="ad-hero-img" src="<?= ad_e($mainImg) ?>" alt="<?= ad_e($name) ?>" onerror="this.style.display='none'">
    <?php endif; ?>

    <h1 class="ad-sr"><?= ad_e($name) ?></h1>
  </section>

  <!-- ============ ABOUT + QUICK FACTS ============ -->
  <section class="ad-sec">
    <div class="ad-wrap ad-about-grid">
      <div class="ad-about-copy">
        <h2 class="ad-h2"><?= ad_icon('paw') ?>About <?= ad_e($name) ?></h2>
        <?php if ($descHtml !== ''): ?><div class="ad-rich"><?= ad_rich($descHtml) ?></div><?php endif; ?>

        <?php if ($funFact !== ''): ?>
          <div class="ad-funfact">
            <span class="ad-funfact-ico"><?= ad_icon('bulb') ?></span>
            <div><b>Did you know?</b><span><?= ad_e($funFact) ?></span></div>
          </div>
        <?php endif; ?>

        <a href="animals.php" class="ad-btn">&larr; Back to All Animals</a>

        <?php if (count($classif) > 0 || count($range) > 0): ?>
        <div class="ad-minis">
        <?php if (count($classif) > 0): ?>
          <aside class="ad-mini">
            <h2 class="ad-h2"><?= ad_icon('leaf') ?>Classification</h2>
            <?php foreach ($classif as $c): ?>
              <div class="ad-mini-row"><b><?= ad_e($c[0]) ?></b><span><?= ad_e($c[1]) ?></span></div>
            <?php endforeach; ?>
          </aside>
        <?php endif; ?>

        <?php if (count($range) > 0): ?>
          <aside class="ad-mini">
            <h2 class="ad-h2"><?= ad_icon('mountain') ?>Habitat &amp; Range</h2>
            <?php foreach ($range as $c): ?>
              <div class="ad-mini-row"><b><?= ad_e($c[0]) ?></b><span><?= ad_e($c[1]) ?></span></div>
            <?php endforeach; ?>
          </aside>
        <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>

      <div class="ad-side">
        <?php if (count($facts) > 0): ?>
          <aside class="ad-facts">
            <h2 class="ad-h2"><?= ad_icon('paw') ?>Quick Facts</h2>
            <?php foreach ($facts as $f): ?>
              <div class="ad-fact">
                <span class="ad-fact-ico"><?= ad_icon($f[1]) ?></span>
                <b><?= ad_e($f[0]) ?></b>
                <?php if ($f[3] === 'pill'): ?>
                  <span><span class="ad-pill"><?= ad_e($f[2]) ?></span></span>
                <?php else: ?>
                  <span><?= ad_e($f[2]) ?></span>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
            <span class="ad-sprig is-a" aria-hidden="true"><svg><use href="#adSprig"/></svg></span>
            <span class="ad-sprig is-b" aria-hidden="true"><svg><use href="#adSprig"/></svg></span>
          </aside>
        <?php endif; ?>

      </div>
    </div>
  </section>

  <!-- ============ GALLERY ============ -->
  <?php if (count($gallery) > 0): $shown = array_slice($gallery, 0, 5); $extra = count($gallery) - count($shown); ?>
  <section class="ad-sec" id="gallery">
    <div class="ad-wrap">
      <div class="ad-head">
        <div>
          <h2 class="ad-h2"><?= ad_icon('image') ?>Gallery</h2>
          <p class="ad-sub">A glimpse into the life of the <?= ad_e($name) ?></p>
        </div>
        <button type="button" class="ad-link ad-link-btn" data-lb="0">View All Photos &rarr;</button>
      </div>
      <div class="ad-gallery <?= count($shown) <= 2 ? 'is-few' : (count($shown) <= 4 ? 'is-mid' : '') ?>" style="--n:<?= count($shown) ?>">
        <?php foreach ($shown as $i => $g): ?>
          <a href="<?= ad_e($g['src']) ?>" data-lb="<?= $i ?>" aria-label="Open photo <?= $i + 1 ?>">
            <img src="<?= ad_e($g['src']) ?>" alt="<?= ad_e($g['alt']) ?>" loading="lazy" onerror="this.style.display='none'">
            <?php if ($i === count($shown) - 1 && $extra > 0): ?><span class="ad-plus">+<?= (int)$extra ?></span><?php endif; ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- ============ HABITAT & BEHAVIOR ============ -->
  <?php if ($showHab): ?>
  <section class="ad-sec">
    <div class="ad-wrap">
    <div class="ad-hab-grid">
      <div class="ad-hab-visual">
        <div class="ad-paper">
          <div class="ad-pic">
            <?php if ($habImg !== ''): ?>
              <img src="<?= ad_e($habImg) ?>" alt="<?= ad_e($name) ?> in its habitat" loading="lazy" onerror="this.style.display='none'">
            <?php endif; ?>
            <div class="ad-cap ad-brush">Nature at<span>its finest</span></div>
          </div>
        </div>
      </div>

      <div class="ad-hab-copy">
        <h2 class="ad-h2"><?= ad_icon('paw') ?>Habitat &amp; Behavior</h2>
        <?php if ($habText !== ''): ?><div class="ad-rich"><?= ad_rich($habText) ?></div><?php endif; ?>
        <?php if (count($behItems) > 0): ?>
          <div class="ad-beh">
            <?php foreach ($behItems as $b): ?>
              <div class="ad-beh-item">
                <span class="ad-beh-ico"><?= ad_icon($b[1]) ?></span>
                <div><b><?= ad_e($b[0]) ?></b><span><?= ad_e($b[2]) ?></span></div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <?php $hc = ad_f($animal, 'habitat'); if (count($behItems) === 0 && ($hc !== '' || $catLabel !== '' || $status !== '')): ?>
          <div class="ad-chips">
            <?php if ($hc !== ''): ?><span class="ad-chip"><?= ad_icon('mountain') ?><b>Habitat</b><?= ad_e($hc) ?></span><?php endif; ?>
            <?php if ($catLabel !== ''): ?><span class="ad-chip"><?= ad_icon('paw') ?><b>Class</b><?= ad_e($catLabel) ?></span><?php endif; ?>
            <?php if ($status !== ''): ?><span class="ad-chip"><?= ad_icon('shield') ?><b>Status</b><?= ad_e($status) ?></span><?php endif; ?>
          </div>
        <?php endif; ?>
        <span class="ad-sprig" aria-hidden="true"><svg><use href="#adSprig"/></svg></span>
      </div>
    </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- ============ CONSERVATION & THREATS ============ -->
  <?php if ($consText !== ''): ?>
  <section class="ad-sec">
    <div class="ad-wrap">
      <div class="ad-cons">
        <div class="ad-cons-copy">
          <span class="ad-cons-ico"><?= ad_icon('leaf') ?></span>
          <div>
            <h2 class="ad-h2">Conservation &amp; Threats</h2>
            <div class="ad-rich"><?= ad_rich($consText) ?></div>
            <a href="conservation.php" class="ad-btn is-outline">Learn How You Can Help &rarr;</a>
          </div>
        </div>
        <div class="ad-cons-pic">
          <?php if ($consImg !== ''): ?>
            <img src="<?= ad_e($consImg) ?>" alt="<?= ad_e($name) ?> conservation" loading="lazy" onerror="this.style.display='none'">
          <?php endif; ?>
          <div class="ad-cons-quote"><span>Their future<br>is in our hands.</span><?= ad_icon('paw') ?></div>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- ============ MORE ABOUT (other long admin fields) ============ -->
  <?php if (count($moreBlocks) > 0): ?>
  <section class="ad-sec">
    <div class="ad-wrap">
      <h2 class="ad-h2"><?= ad_icon('paw') ?>More About <?= ad_e($name) ?></h2>
      <div class="ad-moregrid">
        <?php foreach ($moreBlocks as $b): ?>
          <article class="ad-more-card">
            <h3 class="ad-h2"><?= ad_e($b[1]) ?></h3>
            <div class="ad-rich"><?= ad_rich($b[2]) ?></div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- ============ FAQ ============ -->
  <?php if (count($faqs) > 0): ?>
  <section class="ad-faq-band">
    <div class="ad-wrap">
      <h2 class="ad-h2"><?= ad_icon('help') ?>Frequently Asked Questions</h2>
      <div class="ad-faq-list">
        <?php foreach ($faqs as $i => $fq): ?>
          <details class="ad-faq-item"<?= $i === 0 ? ' open' : '' ?>>
            <summary><?= ad_e(ad_plain($fq[0])) ?></summary>
            <div class="ad-rich"><?= ad_rich($fq[1]) ?></div>
          </details>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- ============ RELATED ANIMALS ============ -->
  <?php if (count($related) > 0): ?>
  <section class="ad-sec">
    <div class="ad-wrap">
      <div class="ad-head">
        <div>
          <h2 class="ad-h2"><?= ad_icon('paw') ?>Related Animals</h2>
          <p class="ad-sub">Discover other amazing animals that share the same habitat or are part of the same ecosystem.</p>
        </div>
        <a class="ad-link" href="animals.php">View All &rarr;</a>
      </div>
      <div class="ad-related">
        <?php foreach ($related as $r): ?>
          <a class="ad-rel" href="animal-details.php?id=<?= (int)$r['id'] ?>">
            <span class="ad-rel-img">
              <?php if (!empty($r['image'])): ?>
                <img src="<?= ad_e(ad_path($r['image'])) ?>" alt="<?= ad_e($r['name']) ?>" loading="lazy" onerror="this.style.display='none'">
              <?php endif; ?>
            </span>
            <b><?= ad_e($r['name']) ?></b>
            <span class="ad-rel-go"><?= ad_icon('arrow') ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- photo viewer -->
  <?php if (count($gallery) > 0): ?>
  <div class="ad-lb" id="adLb" role="dialog" aria-modal="true" aria-label="Photo viewer" hidden>
    <button type="button" class="ad-lb-close" aria-label="Close">&times;</button>
    <button type="button" class="ad-lb-prev" aria-label="Previous photo">&lsaquo;</button>
    <img src="" alt="">
    <button type="button" class="ad-lb-next" aria-label="Next photo">&rsaquo;</button>
    <div class="ad-lb-count"></div>
  </div>
  <script>
  (function () {
    var imgs = <?= json_encode(array_column($gallery, 'src'), JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
    var alts = <?= json_encode(array_column($gallery, 'alt'), JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
    var box = document.getElementById('adLb');
    if (!box || !imgs.length) return;
    var im = box.querySelector('img'), count = box.querySelector('.ad-lb-count'), cur = 0;
    function show(n) { cur = (n + imgs.length) % imgs.length; im.src = imgs[cur]; im.alt = alts[cur] || ''; count.textContent = (cur + 1) + ' / ' + imgs.length; }
    function open(n) { show(n); box.hidden = false; document.body.style.overflow = 'hidden'; box.querySelector('.ad-lb-close').focus(); }
    function close() { box.hidden = true; document.body.style.overflow = ''; }
    [].forEach.call(document.querySelectorAll('[data-lb]'), function (el) {
      el.addEventListener('click', function (e) { e.preventDefault(); open(parseInt(el.getAttribute('data-lb'), 10) || 0); });
    });
    box.querySelector('.ad-lb-close').addEventListener('click', close);
    box.querySelector('.ad-lb-prev').addEventListener('click', function () { show(cur - 1); });
    box.querySelector('.ad-lb-next').addEventListener('click', function () { show(cur + 1); });
    box.addEventListener('click', function (e) { if (e.target === box) close(); });
    document.addEventListener('keydown', function (e) {
      if (box.hidden) return;
      if (e.key === 'Escape') close();
      if (e.key === 'ArrowLeft') show(cur - 1);
      if (e.key === 'ArrowRight') show(cur + 1);
    });
  })();
  </script>
  <?php endif; ?>

</main>

<?php else: ?>

  <section class="section section-light" style="padding-top:180px; text-align:center;">
    <div class="container">
      <h2>Animal not found</h2>
      <p style="margin-top:12px;">This animal may have been removed, or the link is incorrect.</p>
      <a href="animals.php" class="btn btn-gold" style="margin-top:24px;">Browse All Animals</a>
    </div>
  </section>

<?php endif; ?>

<?php require '../includes/footer.php'; ?>