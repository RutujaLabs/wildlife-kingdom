<?php
$pageTitle = 'Events';
$pageCss = 'pages.css';
$metaDescription = 'Join guided safari walks, conservation talks and family wildlife encounters at Wildlife Kingdom. See upcoming events and plan your visit.';
require '../includes/header.php';
require '../config/db.php';

// ---- Fetch events (only published ones if a status column exists) ----
$events = [];
$result = $conn->query("SELECT * FROM events ORDER BY event_date ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        if (isset($row['status']) && strtolower((string)$row['status']) !== 'published') continue;
        $events[] = $row;
    }
}

// ---- Helpers ----
if (!function_exists('ev_image_url')) {
    function ev_image_url($row) {
        $img = trim((string)($row['image'] ?? $row['image_path'] ?? ''));
        if ($img === '') return '';
        if (preg_match('#^https?://#i', $img)) return $img;
        $img = ltrim($img, '/');
        if (strpos($img, 'uploads/') === 0) {
            return '../' . implode('/', array_map('rawurlencode', explode('/', $img)));
        }
        return '../uploads/events/' . rawurlencode(basename($img));
    }
}

$icoCal   = '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M4 10h16M9 3v4M15 3v4"/></svg>';
$icoClock = '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>';
$icoPin   = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7-5.2-7-11a7 7 0 0 1 14 0c0 5.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>';

$emojis = ['🦁', '🐘', '🦒', '🌿', '🦓', '🦜'];

// ---- Structured data (Event schema) for SEO ----
$schemaItems = [];
foreach ($events as $e) {
    $ts = strtotime($e['event_date'] . ' ' . ($e['event_time'] ?? '00:00:00'));
    if (!$ts) continue;
    $schemaItems[] = [
        '@type'               => 'Event',
        'name'                => $e['title'],
        'startDate'           => date('c', $ts),
        'eventStatus'         => 'https://schema.org/EventScheduled',
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'description'         => trim(preg_replace('/\s+/', ' ', strip_tags((string)($e['description'] ?? '')))),
        'location'            => ['@type' => 'Place', 'name' => (string)($e['location'] ?? 'Wildlife Kingdom')],
        'organizer'           => ['@type' => 'Organization', 'name' => 'Wildlife Kingdom'],
    ];
}
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap');

/* =====================================================
   Events page — Wildlife Kingdom safari theme
   (same palette, fonts and small side spacing as the home page)
   ===================================================== */
.ev, .ev * { box-sizing: border-box; }
.ev {
  --ev-white:  #FFFDF8;
  --ev-orange: #E8A15B;
  --ev-forest: #173B2A;
  --ev-dark:   #0B2F22;
  --ev-gold:   #E8A15B;   /* now Safari Orange (same as --ev-orange) */
  --ev-text:   #2B241D;
  font-family: 'Lato', 'Segoe UI', sans-serif;
  color: var(--ev-text);
}
.ev .ev-container {
  width: 100%; max-width: 100%; margin: 0 auto;
  padding-left: clamp(16px, 2.5vw, 36px);
  padding-right: clamp(16px, 2.5vw, 36px);
}
.ev-eyebrow {
  display: inline-flex; align-items: center; gap: 12px; margin-bottom: 14px;
  font-size: .72rem; font-weight: 700; line-height: 1;
  letter-spacing: .24em; text-transform: uppercase; color: var(--ev-forest);
}
.ev-eyebrow::before { content: ""; width: 36px; height: 2px; background: var(--ev-gold); }
.ev .ev-accent { color: var(--ev-orange); font-style: italic; }

/* ---------- BANNER (normal image banner, flat edge) ---------- */
.ev-banner {
  position: relative; overflow: hidden; width: 100%;
  height: 500px;
  display: flex; align-items: center;
  background:
    radial-gradient(900px 320px at 85% 15%, rgba(232,161,91,.35), transparent 60%),
    linear-gradient(120deg, #0B2F22 0%, #173B2A 60%, #2C5A3F 100%);
  border-bottom: 3px solid var(--ev-gold);
}
.ev-banner-img {
  position: absolute; inset: 0; width: 100%; height: 100%;
  object-fit: cover; object-position: center;
}
/* visually hidden (kept for SEO / screen readers) */
.ev-sr { position: absolute; width: 1px; height: 1px; margin: -1px; padding: 0; overflow: hidden; clip: rect(0 0 0 0); white-space: nowrap; border: 0; }

/* ---------- LIST SECTION ---------- */
/* Section 1 = white, Section 2 = green, last section (CTA) = white */
.ev-sec-intro {
  position: relative;
  padding: clamp(34px, 4.5vw, 56px) 0 clamp(26px, 3.4vw, 40px);
  background:
    radial-gradient(rgba(232,161,91,.14) 1px, transparent 1.4px) 0 0 / 22px 22px,
    var(--ev-white);
}
.ev-sec-intro .ev-head { margin-bottom: 0; }
.ev-sec-events {
  position: relative;
  padding: clamp(34px, 4.5vw, 60px) 0 clamp(40px, 5vw, 70px);
  background:
    radial-gradient(rgba(232,161,91,.16) 1px, transparent 1.4px) 0 0 / 22px 22px,
    linear-gradient(160deg, #1F4A36 0%, #173B2A 55%, #0F3024 100%);
}
.ev-head {
  display: flex; flex-direction: column; align-items: center; gap: 18px;
  width: 100%; text-align: center;
  margin-bottom: clamp(22px, 3vw, 34px);
}
.ev-head > div:first-child { width: 100%; }
.ev-head .ev-eyebrow::after { content: ""; width: 36px; height: 2px; background: var(--ev-gold); }
.ev-head h2 {
  margin: 0 0 10px; padding: 0;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.3rem, 2.2vw, 1.9rem); font-weight: 700; line-height: 1.12; letter-spacing: -.005em;
  color: var(--ev-forest);
}
.ev-head h2::after {
  content: ""; display: block; width: 100%; height: 2px; margin-top: 14px;
  background: linear-gradient(90deg, rgba(232,161,91,0), var(--ev-gold) 50%, rgba(232,161,91,0));
}
.ev-head p { margin: 0 auto; width: 100%; max-width: none; font-size: .98rem; line-height: 1.7; color: var(--ev-text); text-align: center; }
.ev-count-wrap { display: flex; justify-content: center; margin-bottom: clamp(20px, 2.6vw, 30px); }
.ev-count {
  display: inline-flex; width: auto; align-items: baseline; gap: 8px; padding: 8px 22px; border-radius: 999px;
  background: rgba(255,253,248,.1); border: 1px solid rgba(232,161,91,.7);
}
.ev-count strong { font-family: 'Playfair Display', Georgia, serif; font-size: 1.2rem; color: var(--ev-orange); }
.ev-count span { font-size: .74rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: #FFFDF8; }

.ev-rows { display: flex; flex-direction: column; gap: clamp(22px, 2.6vw, 34px); }

/* ---------- EVENT ROW CARD ---------- */
.ev-row {
  position: relative;
  display: grid; grid-template-columns: clamp(300px, 36vw, 520px) minmax(0, 1fr);
  gap: clamp(18px, 2.4vw, 34px); align-items: stretch;
  padding: 18px;
  background: #fff;
  border: 1px solid rgba(232,161,91,.5);
  border-radius: 64px 14px 64px 14px;
  box-shadow: 0 10px 28px rgba(23,59,42,.12);
  transition: transform .35s cubic-bezier(.2,.7,.2,1), box-shadow .35s, border-color .35s;
}
.ev-row:hover { transform: translateY(-5px); border-color: var(--ev-gold); box-shadow: 0 20px 40px rgba(23,59,42,.2); }
.ev-row:nth-child(even) { grid-template-columns: minmax(0, 1fr) clamp(300px, 36vw, 520px); border-radius: 14px 64px 14px 64px; }
.ev-row:nth-child(even) .ev-media { order: 2; }

/* image container — same "leaf" language as the home hero */
.ev-media { position: relative; min-height: 320px; }
.ev-frame {
  position: absolute; inset: 0; overflow: hidden;
  border-radius: 48px 12px 48px 12px;
  background: linear-gradient(160deg, #FBE9CC 0%, #F3C48C 100%);
  box-shadow: 0 16px 34px rgba(23,59,42,.22);
}
.ev-row:nth-child(even) .ev-frame { border-radius: 12px 48px 12px 48px; }
.ev-frame::after {
  content: ""; position: absolute; inset: 9px; z-index: 3; pointer-events: none;
  border: 1.5px solid rgba(232,161,91,.85); border-radius: 40px 8px 40px 8px;
}
.ev-row:nth-child(even) .ev-frame::after { border-radius: 8px 40px 8px 40px; }
.ev-frame img {
  position: absolute; inset: 0; z-index: 1; width: 100%; height: 100%; object-fit: cover;
  transition: transform .8s cubic-bezier(.2,.7,.2,1);
}
.ev-row:hover .ev-frame img { transform: scale(1.05); }
.ev-empty {
  position: absolute; inset: 0; z-index: 0; display: flex; flex-direction: column;
  align-items: center; justify-content: center; gap: 6px; text-align: center; color: var(--ev-forest);
}
.ev-empty b { font-size: 3rem; line-height: 1; font-weight: 400; }
.ev-empty span { font-size: .8rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; opacity: .75; }

/* date badge on the image */
.ev-date {
  position: absolute; z-index: 4; top: 22px; left: 22px;
  display: flex; flex-direction: column; align-items: center; min-width: 68px;
  padding: 9px 12px 8px; border-radius: 16px;
  background: rgba(255,253,248,.94); backdrop-filter: blur(6px);
  border: 1px solid var(--ev-gold); box-shadow: 0 8px 18px rgba(11,47,34,.25);
  line-height: 1;
}
.ev-date strong { font-family: 'Playfair Display', Georgia, serif; font-size: 1.9rem; font-weight: 700; color: var(--ev-forest); }
.ev-date span { margin-top: 4px; font-size: .74rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: var(--ev-gold); }
.ev-date small { margin-top: 3px; font-size: .68rem; letter-spacing: .08em; color: var(--ev-text); opacity: .75; }
.ev-row:nth-child(even) .ev-date { left: auto; right: 22px; }

.ev-ribbon {
  position: absolute; z-index: 4; bottom: 22px; left: 22px;
  padding: 7px 16px; border-radius: 999px;
  font-size: .72rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase;
  background: var(--ev-orange); color: var(--ev-forest); box-shadow: 0 6px 14px rgba(11,47,34,.25);
}
.ev-row:nth-child(even) .ev-ribbon { left: auto; right: 22px; }
.ev-credit {
  position: absolute; z-index: 4; right: 22px; bottom: 20px; max-width: 60%;
  padding: 4px 10px; border-radius: 999px; font-size: .68rem; color: #FFFDF8;
  background: rgba(11,47,34,.6); backdrop-filter: blur(4px);
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.ev-row:nth-child(even) .ev-credit { right: auto; left: 22px; }

/* text side */
.ev-body { display: flex; flex-direction: column; justify-content: center; padding: clamp(6px, 1.2vw, 18px) clamp(6px, 1.6vw, 24px); min-width: 0; }
.ev-tag { margin: 0 0 8px; font-size: .68rem; font-weight: 700; letter-spacing: .22em; text-transform: uppercase; color: var(--ev-gold); }
.ev-body h3 {
  margin: 0 0 12px; padding: 0;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.25rem, 1.9vw, 1.6rem); font-weight: 700; line-height: 1.18; color: var(--ev-forest);
}
.ev-body h3::after { content: ""; display: block; width: 56px; height: 3px; margin-top: 12px; background: var(--ev-gold); border-radius: 2px; }

.ev-desc { font-size: .95rem; line-height: 1.7; color: var(--ev-text); position: relative; }
.ev-desc p { margin: 0 0 10px; color: var(--ev-text); text-align: justify; hyphens: manual; }
.ev-desc p:last-child { margin-bottom: 0; }
.ev-js .ev-desc.is-clamped { max-height: calc(1.7em * 4); overflow: hidden; }
.ev-js .ev-desc.is-clamped::after {
  content: ""; position: absolute; left: 0; right: 0; bottom: 0; height: 2.6em; pointer-events: none;
  background: linear-gradient(to bottom, rgba(255,255,255,0), #fff);
}
.ev-more {
  align-self: flex-start; margin: 8px 0 0; padding: 0; background: none; border: 0; cursor: pointer;
  font: inherit; font-size: .85rem; font-weight: 700; color: var(--ev-forest);
  border-bottom: 2px solid var(--ev-gold);
}
.ev-more:hover { color: var(--ev-gold); }

.ev-meta { display: flex; flex-wrap: wrap; gap: 10px; margin: 18px 0 20px; }
.ev-chip {
  display: inline-flex; align-items: center; gap: 9px; padding: 8px 16px 8px 10px; border-radius: 999px;
  background: #FBF6EA; border: 1px solid rgba(232,161,91,.5);
  font-size: .86rem; font-weight: 700; color: var(--ev-forest);
}
.ev-chip i {
  width: 28px; height: 28px; border-radius: 50%; display: grid; place-items: center;
  background: var(--ev-forest); color: var(--ev-orange);
}
.ev-chip svg { width: 15px; height: 15px; fill: none; stroke: currentColor; stroke-width: 1.9; stroke-linecap: round; stroke-linejoin: round; }

.ev-actions { display: flex; flex-wrap: wrap; gap: 12px; }
.ev-btn {
  display: inline-flex; align-items: center; gap: 8px; padding: 10px 24px;
  font-size: .88rem; font-weight: 700; text-decoration: none; border-radius: 999px;
  border: 2px solid var(--ev-forest); transition: background .25s, color .25s, border-color .25s, transform .25s, gap .25s;
}
.ev-btn-solid { background: var(--ev-orange); border-color: var(--ev-orange); color: var(--ev-forest); }
.ev-btn-solid:hover { background: var(--ev-forest); border-color: var(--ev-forest); color: #FFFDF8; transform: translateY(-2px); gap: 12px; }
.ev-btn-line { background: transparent; color: var(--ev-forest); }
.ev-btn-line:hover { background: var(--ev-forest); color: #FFFDF8; transform: translateY(-2px); }

/* empty state */
.ev-none {
  display: flex; flex-direction: column; align-items: center; gap: 10px; text-align: center;
  padding: clamp(34px, 5vw, 60px) 20px; background: #fff;
  border: 1px solid rgba(232,161,91,.5); border-radius: 56px 14px 56px 14px;
  box-shadow: 0 10px 28px rgba(23,59,42,.12);
}
.ev-none b { font-size: 2.6rem; font-weight: 400; line-height: 1; }
.ev-none h3 { margin: 0; font-family: 'Playfair Display', Georgia, serif; font-size: 1.4rem; color: var(--ev-forest); }
.ev-none p { margin: 0 0 8px; color: var(--ev-text); }

/* ---------- LAST SECTION (white): plan your visit ---------- */
.ev-cta {
  padding: clamp(24px, 3vw, 38px) 0 clamp(26px, 3.4vw, 42px);
  background:
    radial-gradient(rgba(232,161,91,.14) 1px, transparent 1.4px) 0 0 / 22px 22px,
    var(--ev-white);
}
.ev-cta .ev-head { gap: 8px; margin-bottom: clamp(14px, 2vw, 22px); }
.ev-cta .ev-head .ev-eyebrow { margin-bottom: 8px; }
.ev-cta .ev-head h2 { margin-bottom: 8px; }
.ev-cta .ev-head h2::after { margin-top: 10px; }
.ev-cta .ev-head p { font-size: .94rem; line-height: 1.6; }
.ev-steps { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: clamp(12px, 1.6vw, 20px); align-items: stretch; }
.ev-step {
  position: relative; overflow: hidden; display: flex; flex-direction: column;
  padding: 20px 20px 20px 24px;
  background: #fff; border: 1px solid rgba(232,161,91,.5);
  border-radius: 40px 10px 40px 10px;
  box-shadow: 0 10px 26px rgba(23,59,42,.12);
  transition: transform .35s cubic-bezier(.2,.7,.2,1), box-shadow .35s, border-color .35s;
}
.ev-step::after {
  content: ""; position: absolute; left: 0; right: 0; bottom: 0; height: 3px;
  background: linear-gradient(90deg, var(--ev-orange), var(--ev-gold));
  transform: scaleX(0); transform-origin: left; transition: transform .5s cubic-bezier(.2,.7,.2,1);
}
.ev-step:hover { transform: translateY(-6px); border-color: var(--ev-gold); box-shadow: 0 20px 40px rgba(23,59,42,.2); }
.ev-step:hover::after { transform: scaleX(1); }
.ev-step-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
.ev-step-ico {
  width: 42px; height: 42px; border-radius: 50%; display: grid; place-items: center;
  background: var(--ev-forest); color: var(--ev-orange); border: 1px solid var(--ev-gold);
  transition: background .3s, color .3s, transform .5s;
}
.ev-step-ico svg { width: 20px; height: 20px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
.ev-step:hover .ev-step-ico { background: var(--ev-orange); color: var(--ev-forest); transform: rotate(-8deg) scale(1.06); }
.ev-step-num {
  font-family: 'Playfair Display', Georgia, serif; font-size: 2rem; font-weight: 700; line-height: 1;
  color: transparent; -webkit-text-stroke: 1px rgba(232,161,91,.8);
}
.ev-step h3 {
  margin: 0 0 8px; padding: 0;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: 1.08rem; font-weight: 700; line-height: 1.2; color: var(--ev-forest);
}
.ev-step h3::after { content: ""; display: block; width: 40px; height: 3px; margin-top: 8px; background: var(--ev-gold); border-radius: 2px; }
.ev-step p { margin: 0; font-size: .88rem; line-height: 1.6; color: var(--ev-text); }
.ev-cta .ev-actions { justify-content: center; margin-top: clamp(16px, 2vw, 24px); }
.ev-cta .ev-btn-solid { background: var(--ev-orange); border-color: var(--ev-orange); color: var(--ev-forest); }
.ev-cta .ev-btn-solid:hover { background: var(--ev-forest); border-color: var(--ev-forest); color: #FFFDF8; transform: translateY(-2px); gap: 12px; }
.ev-cta .ev-btn-line { background: transparent; border-color: var(--ev-forest); color: var(--ev-forest); }
.ev-cta .ev-btn-line:hover { background: var(--ev-forest); border-color: var(--ev-forest); color: #FFFDF8; transform: translateY(-2px); }

/* ---------- entrance animation ---------- */
.ev-js .ev-row, .ev-js .ev-head, .ev-js .ev-step { opacity: 0; transform: translateY(26px); transition: opacity .7s ease, transform .7s cubic-bezier(.2,.7,.2,1); }
.ev-js .ev-step { transition-delay: calc(var(--i, 0) * 110ms); }
.ev-js .ev-row.is-in, .ev-js .ev-head.is-in, .ev-js .ev-step.is-in { opacity: 1; transform: none; }
.ev-js .ev-row.is-in:hover { transform: translateY(-5px); transition: transform .35s cubic-bezier(.2,.7,.2,1), box-shadow .35s, border-color .35s; }
.ev-js .ev-step.is-in:hover { transform: translateY(-6px); transition: transform .35s cubic-bezier(.2,.7,.2,1), box-shadow .35s, border-color .35s; transition-delay: 0s; }
@media (prefers-reduced-motion: reduce) {
  .ev-js .ev-row, .ev-js .ev-head, .ev-js .ev-step { opacity: 1; transform: none; transition: none; }
  .ev-frame img, .ev-row { transition: none; }
}

/* ---------- responsive ---------- */
@media (max-width: 900px) {
  .ev-steps { grid-template-columns: minmax(0, 1fr); }
  .ev-row, .ev-row:nth-child(even) { grid-template-columns: minmax(0, 1fr); padding: 14px; border-radius: 44px 12px 44px 12px; }
  .ev-row:nth-child(even) { border-radius: 12px 44px 12px 44px; }
  .ev-row:nth-child(even) .ev-media { order: 0; }
  .ev-media { min-height: 0; aspect-ratio: 4 / 3; }
  .ev-frame, .ev-row:nth-child(even) .ev-frame { border-radius: 36px 10px 36px 10px; }
  .ev-row:nth-child(even) .ev-frame { border-radius: 10px 36px 10px 36px; }
  .ev-row:nth-child(even) .ev-date { left: auto; right: 18px; }
  .ev-body { padding: 4px 8px 10px; }
}
@media (max-width: 600px) {
  .ev-banner { height: 400px; }
}
@media (max-width: 480px) {
  .ev-eyebrow { letter-spacing: .16em; font-size: .68rem; gap: 10px; }
  .ev-eyebrow::before { width: 26px; }
  .ev-media { aspect-ratio: 1 / 1; }
  .ev-date { top: 16px; left: 16px; min-width: 60px; }
  .ev-ribbon { left: 16px; bottom: 16px; }
  .ev-actions .ev-btn, .ev-cta .ev-btn { flex: 1 1 100%; justify-content: center; }
  .ev-step { border-radius: 34px 10px 34px 10px; padding: 18px 18px 18px 20px; }
  .ev-chip { width: 100%; }
}
</style>

<div class="ev">

  <!-- ============ BANNER ============ -->
  <div class="ev-banner">
    <!-- Save your banner image as assets/images/static/events-banner.webp (1920 x 500 px recommended) -->
    <img class="ev-banner-img" src="../assets/images/static/events-banner.webp" alt="" aria-hidden="true" onerror="this.style.display='none'">
    <h1 class="ev-sr">Wildlife Events at Wildlife Kingdom</h1>
  </div>

  <!-- ============ SECTION 1 (white) + SECTION 2 (green): EVENTS ============ -->
  <section class="ev-sec-intro" aria-label="Events introduction">
    <div class="ev-container">

      <div class="ev-head">
        <div>
          <span class="ev-eyebrow">What's On</span>
          <h2>Wildlife Events &amp; <span class="ev-accent">Experiences</span></h2>
          <p>Guided walks, conservation talks and family encounters. Pick an event, mark the date and plan your visit.</p>
        </div>
      </div>

    </div>
  </section>

  <section class="ev-sec-events" aria-label="Events list">
    <div class="ev-container">

      <?php if (count($events) > 0): ?>
        <div class="ev-count-wrap">
          <div class="ev-count"><strong><?php echo count($events); ?></strong><span><?php echo count($events) === 1 ? 'Event' : 'Events'; ?> Scheduled</span></div>
        </div>
        <div class="ev-rows">
          <?php foreach ($events as $i => $event):
            $title    = (string)($event['title'] ?? '');
            $ts       = strtotime($event['event_date']);
            $day      = $ts ? date('d', $ts) : '';
            $month    = $ts ? date('M', $ts) : '';
            $year     = $ts ? date('Y', $ts) : '';
            $fullDate = $ts ? date('l, j F Y', $ts) : '';
            $timeStr  = !empty($event['event_time']) ? date('g:i A', strtotime($event['event_time'])) : '';
            $location = trim((string)($event['location'] ?? ''));
            $imgUrl   = ev_image_url($event);
            $imgAlt   = trim((string)($event['image_alt_text'] ?? '')) ?: $title;
            $credit   = trim((string)($event['image_credit'] ?? ''));
            $featured = !empty($event['is_featured']);
            $paras    = preg_split('/\R{2,}/', trim((string)($event['description'] ?? ''))) ?: [];

            // "Add to calendar" link (assumes a 2-hour event)
            $calLink = '';
            $startTs = strtotime($event['event_date'] . ' ' . (!empty($event['event_time']) ? $event['event_time'] : '09:00:00'));
            if ($startTs) {
                $calLink = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
                    . '&text='     . rawurlencode($title)
                    . '&dates='    . date('Ymd\THis', $startTs) . '/' . date('Ymd\THis', $startTs + 7200)
                    . '&details='  . rawurlencode(mb_substr(trim(preg_replace('/\s+/', ' ', strip_tags((string)($event['description'] ?? '')))), 0, 300))
                    . '&location=' . rawurlencode($location);
            }
          ?>
          <article class="ev-row" style="--i:<?php echo (int)$i; ?>">

            <div class="ev-media">
              <div class="ev-frame">
                <div class="ev-empty" aria-hidden="true"><b><?php echo $emojis[$i % count($emojis)]; ?></b><span>Image coming soon</span></div>
                <?php if ($imgUrl !== ''): ?>
                  <img src="<?php echo htmlspecialchars($imgUrl, ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($imgAlt, ENT_QUOTES); ?>" loading="lazy" onerror="this.style.display='none'">
                <?php endif; ?>
                <?php if ($day !== ''): ?>
                  <div class="ev-date"><strong><?php echo $day; ?></strong><span><?php echo $month; ?></span><small><?php echo $year; ?></small></div>
                <?php endif; ?>
                <?php if ($featured): ?><span class="ev-ribbon">&#9733; Featured</span><?php endif; ?>
                <?php if ($credit !== '' && !$featured): ?><span class="ev-credit">Photo: <?php echo htmlspecialchars($credit); ?></span><?php endif; ?>
              </div>
            </div>

            <div class="ev-body">
              <p class="ev-tag"><?php echo $featured ? 'Featured Event' : 'Wildlife Event'; ?></p>
              <h3><?php echo htmlspecialchars($title); ?></h3>

              <div class="ev-desc">
                <?php foreach ($paras as $p): if (trim($p) === '') continue; ?>
                  <p><?php echo nl2br(htmlspecialchars(trim($p))); ?></p>
                <?php endforeach; ?>
              </div>

              <div class="ev-meta">
                <?php if ($fullDate !== ''): ?><span class="ev-chip"><i><?php echo $icoCal; ?></i><?php echo $fullDate; ?></span><?php endif; ?>
                <?php if ($timeStr !== ''): ?><span class="ev-chip"><i><?php echo $icoClock; ?></i><?php echo $timeStr; ?></span><?php endif; ?>
                <?php if ($location !== ''): ?><span class="ev-chip"><i><?php echo $icoPin; ?></i><?php echo htmlspecialchars($location); ?></span><?php endif; ?>
              </div>

              <div class="ev-actions">
                <a href="visit-us.php" class="ev-btn ev-btn-solid">Plan Your Visit <span aria-hidden="true">&rarr;</span></a>
                <?php if ($calLink !== ''): ?>
                  <a href="<?php echo htmlspecialchars($calLink, ENT_QUOTES); ?>" class="ev-btn ev-btn-line" target="_blank" rel="noopener">Add to Calendar</a>
                <?php endif; ?>
              </div>
            </div>

          </article>
          <?php endforeach; ?>
        </div>

      <?php else: ?>
        <div class="ev-none">
          <b>🦁</b>
          <h3>No events scheduled right now</h3>
          <p>New wildlife events are added regularly. Check back soon, or plan a visit in the meantime.</p>
          <a href="visit-us.php" class="ev-btn ev-btn-solid">Plan Your Visit <span aria-hidden="true">&rarr;</span></a>
        </div>
      <?php endif; ?>

    </div>
  </section>

  <!-- ============ LAST SECTION (white): PLAN YOUR VISIT ============ -->
  <section class="ev-cta" aria-label="Plan your visit">
    <div class="ev-container">

      <div class="ev-head">
        <div>
          <span class="ev-eyebrow">Plan Your Day</span>
          <h2>Make Your Visit <span class="ev-accent">Unforgettable</span></h2>
          <p>Combine an event with a day exploring our animals and habitats, and create memories the whole family will love.</p>
        </div>
      </div>

      <div class="ev-steps">
        <article class="ev-step" style="--i:0">
          <div class="ev-step-top">
            <span class="ev-step-ico"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M4 10h16M9 3v4M15 3v4"/></svg></span>
            <span class="ev-step-num" aria-hidden="true">01</span>
          </div>
          <h3>Pick an Event</h3>
          <p>Browse our guided walks, conservation talks and family encounters, and choose the one that suits you.</p>
        </article>
        <article class="ev-step" style="--i:1">
          <div class="ev-step-top">
            <span class="ev-step-ico"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7-5.2-7-11a7 7 0 0 1 14 0c0 5.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg></span>
            <span class="ev-step-num" aria-hidden="true">02</span>
          </div>
          <h3>Plan Your Visit</h3>
          <p>Check the date, time and location, then add the event to your calendar so you never miss it.</p>
        </article>
        <article class="ev-step" style="--i:2">
          <div class="ev-step-top">
            <span class="ev-step-ico"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19c0-8.5 5-13.5 14-14 0 9-5 14-13.5 14"/><path d="M5 19c2-4.5 5-7.5 9-10"/></svg></span>
            <span class="ev-step-num" aria-hidden="true">03</span>
          </div>
          <h3>Explore &amp; Enjoy</h3>
          <p>Spend the day meeting our animals and discovering our habitats, and enjoy the experience with your family.</p>
        </article>
      </div>

      <div class="ev-actions">
        <a href="visit-us.php" class="ev-btn ev-btn-solid">Plan Your Visit <span aria-hidden="true">&rarr;</span></a>
        <a href="animals.php" class="ev-btn ev-btn-line">Explore Animals</a>
      </div>

    </div>
  </section>

</div>

<?php if (count($schemaItems) > 0): ?>
<script type="application/ld+json">
<?php echo json_encode(['@context' => 'https://schema.org', '@graph' => $schemaItems], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG); ?>
</script>
<?php endif; ?>

<script>
(function () {
  var root = document.querySelector('.ev');
  if (!root) return;
  root.classList.add('ev-js');
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* clamp long descriptions with a Read more toggle */
  root.querySelectorAll('.ev-desc').forEach(function (d) {
    d.classList.add('is-clamped');
    if (d.scrollHeight <= d.clientHeight + 2) { d.classList.remove('is-clamped'); return; }
    var b = document.createElement('button');
    b.type = 'button'; b.className = 'ev-more'; b.textContent = 'Read more';
    b.addEventListener('click', function () {
      var open = d.classList.toggle('is-clamped');
      b.textContent = open ? 'Read more' : 'Show less';
    });
    d.parentNode.insertBefore(b, d.nextSibling);
  });

  /* reveal on scroll */
  var items = root.querySelectorAll('.ev-row, .ev-head, .ev-step');
  if ('IntersectionObserver' in window && !reduce) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); }
      });
    }, { threshold: 0.12 });
    items.forEach(function (el) { io.observe(el); });
  } else {
    items.forEach(function (el) { el.classList.add('is-in'); });
  }
})();
</script>

<?php require '../includes/footer.php'; ?>