<?php
$pageTitle = 'Home';
require '../includes/header.php';
require '../config/db.php';

// ---- Fetch dynamic content from the database ----

$habitats = [];
$result = $conn->query("SELECT * FROM habitats LIMIT 3");
if ($result) { while ($row = $result->fetch_assoc()) { $habitats[] = $row; } }

$animals = [];
$result = $conn->query("SELECT a.id, a.name, a.species, a.image, a.conservation_status FROM animals a WHERE a.is_featured = 1 ORDER BY a.name ASC LIMIT 4");
if ($result) { while ($row = $result->fetch_assoc()) { $animals[] = $row; } }

$events = [];
$result = $conn->query("SELECT * FROM events ORDER BY event_date ASC LIMIT 2");
if ($result) { while ($row = $result->fetch_assoc()) { $events[] = $row; } }
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap');

/* =====================================================
   Wildlife Kingdom — Premium Safari Theme (visual only)
   Load AFTER your existing stylesheet, e.g. in header.php:
   <link rel="stylesheet" href="../assets/css/safari-theme.css">
   ===================================================== */

:root {
  --sw-white:  #FFFDF8;   /* Soft White      */
  --sw-orange: #E8A15B;   /* Safari Orange   */
  --sw-forest: #173B2A;   /* Headings/buttons */
  --sw-dark:   #0B2F22;   /* Footer          */
  --sw-gold:   #C89B3C;   /* Accents/icons   */
  --sw-text:   #2B241D;   /* Body text       */

  /* Re-point common existing variables (used by inline styles in your pages) */
  --deep-jungle: #173B2A;
  --warm-ivory:  #FFFDF8;
}

/* ---------- Section backgrounds: alternate white / orange ----------
   Section 1, 3, 5... (odd) => Soft White
   Section 2, 4, 6... (even) => Safari Orange */
section.section:nth-of-type(odd),
section.final-cta:nth-of-type(odd) { background: var(--sw-white)  !important; }
section.section:nth-of-type(even),
section.final-cta:nth-of-type(even)  { background: var(--sw-orange) !important; }

/* Keep old modifier classes from fighting the pattern */
.section-light, .section-dark, .section-sand, .section-deepest { background-image: none; }

/* ---------- Typography ---------- */
section.section, .final-cta { color: var(--sw-text); }
section.section p,
section.section span,
section.section li,
.final-cta p { color: var(--sw-text); }

section.section h1, section.section h2, section.section h3,
.final-cta h2 {
  color: var(--sw-forest);
  font-weight: 700;
  letter-spacing: .01em;
}

/* Elegant gold rule under section headings */
.section-head h2::after {
  content: "";
  display: block;
  width: 56px; height: 3px;
  margin: 14px auto 0;
  background: var(--sw-gold);
  border-radius: 2px;
}
.welcome-text h2::after,
.conservation-text h2::after,
.visit-info h2::after {
  content: "";
  display: block;
  width: 56px; height: 3px;
  margin-top: 14px;
  background: var(--sw-gold);
  border-radius: 2px;
}

/* ---------- Buttons ---------- */
.btn-gold,
.btn.btn-gold {
  background: var(--sw-forest);
  color: var(--sw-white);
  border: 2px solid var(--sw-forest);
  transition: background .25s, color .25s, border-color .25s, transform .25s;
}
.btn-gold:hover {
  background: var(--sw-gold);
  border-color: var(--sw-gold);
  color: var(--sw-forest);
  transform: translateY(-2px);
}
.btn-outline,
.btn-outline-dark {
  background: transparent;
  color: var(--sw-forest);
  border: 2px solid var(--sw-forest);
  transition: background .25s, color .25s, transform .25s;
}
.btn-outline:hover,
.btn-outline-dark:hover {
  background: var(--sw-forest);
  color: var(--sw-white);
  transform: translateY(-2px);
}
/* Hero sits on imagery: keep its outline button light */
.hero .btn-outline { color: var(--sw-white); border-color: var(--sw-white); }
.hero .btn-outline:hover { background: var(--sw-white); color: var(--sw-forest); }
.hero .btn-gold { background: var(--sw-gold); border-color: var(--sw-gold); color: var(--sw-forest); }
.hero .btn-gold:hover { background: var(--sw-white); border-color: var(--sw-white); }
.hero h1 span { color: var(--sw-gold); }

/* ---------- Cards (white, gold-edged, soft shadow) ---------- */
.explore-card, .animal-card, .event-row, .habitat-card {
  background: var(--sw-white);
  border: 1px solid rgba(200, 155, 60, .45);
  border-radius: 14px;
  box-shadow: 0 8px 24px rgba(23, 59, 42, .12);
  transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
}
.explore-card:hover, .animal-card:hover, .event-row:hover, .habitat-card:hover {
  transform: translateY(-4px);
  border-color: var(--sw-gold);
  box-shadow: 0 14px 32px rgba(23, 59, 42, .2);
}
.habitat-card { overflow: hidden; }
.habitat-overlay { background: linear-gradient(to top, rgba(11, 47, 34, .9), transparent); }
.habitat-overlay h3, .habitat-overlay span { color: var(--sw-white); }

.explore-card h3, .animal-card h3, .event-info h3 { color: var(--sw-forest); }
.explore-card p,  .animal-card p,  .event-info p  { color: var(--sw-text); }

/* ---------- Gold accents ---------- */
.explore-icon { color: var(--sw-gold); }
.animal-card .status {
  color: var(--sw-forest);
  background: rgba(200, 155, 60, .22);
  border: 1px solid var(--sw-gold);
  border-radius: 999px;
  padding: 2px 10px;
}
.event-date { color: var(--sw-forest); }
.event-date strong { color: var(--sw-gold); }
.conservation-list .dot { background: var(--sw-gold); }
.welcome-stats strong { color: var(--sw-gold); }
.welcome-stats span   { color: var(--sw-text); }

/* On orange sections gold text is low-contrast, so use forest green there */
section.section:nth-of-type(even) .welcome-stats strong,
section.section:nth-of-type(even) .conservation-list .dot { color: var(--sw-forest); }
section.section:nth-of-type(even) .conservation-list .dot { background: var(--sw-forest); }

/* ---------- Images ---------- */
.welcome-media img, .conservation-media img, .gallery-grid img {
  border-radius: 14px;
  box-shadow: 0 10px 28px rgba(23, 59, 42, .18);
}

/* ---------- Visit hours + map placeholder ---------- */
.visit-hours > div { border-bottom: 1px solid rgba(23, 59, 42, .25); }
.visit-map {
  background: rgba(255, 253, 248, .6);
  border: 1px dashed var(--sw-forest);
  color: var(--sw-forest);
  border-radius: 14px;
}

/* ---------- Footer ---------- */
footer, .site-footer, .footer { background: var(--sw-dark) !important; }
footer a:hover, .site-footer a:hover, .footer a:hover { color: var(--sw-gold); }

/* =====================================================
   SECTION 1 — Home hero ("wk-hero"), compact version
   ===================================================== */
.wk-hero {
  position: relative; overflow: hidden;
  padding: clamp(32px, 4.5vw, 56px) 0;
  font-family: 'Lato', 'Segoe UI', sans-serif;
}
.wk-hero, .wk-hero * { box-sizing: border-box; }
.wk-hero-grid {
  position: relative; z-index: 1;
  display: grid; grid-template-columns: minmax(0, 1fr) clamp(320px, 27vw, 460px);
  gap: clamp(24px, 3vw, 44px);
  align-items: stretch;             /* image column = same height as the text */
}
/* full-width container with small side spacing */
.wk-hero .wk-hero-grid.container {
  max-width: 100%;
  padding-left: clamp(16px, 2.5vw, 36px);
  padding-right: clamp(16px, 2.5vw, 36px);
}

/* ---- copy ---- */
.wk-eyebrow {
  display: inline-flex; align-items: center; gap: 12px; margin-bottom: 14px;
  font-size: .72rem; font-weight: 700; line-height: 1;
  letter-spacing: .24em; text-transform: uppercase; color: var(--sw-forest);
}
.wk-eyebrow::before { content: ""; width: 36px; height: 2px; background: var(--sw-gold); }
.wk-hero h1 {
  margin: 0 0 14px;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.3rem, 2.2vw, 1.9rem);
  width: 100%;
  font-weight: 700; line-height: 1.12; letter-spacing: -.005em;
  color: var(--sw-forest);
}
.wk-hero h1 .wk-h1-black { color: #000; }
.wk-hero h1 .wk-h1-accent { color: #E8A15B; font-style: italic; }
.wk-hero h1::after {             /* rule that runs the full width of the heading */
  content: ""; display: block; width: 100%; height: 2px; margin-top: 14px;
  background: linear-gradient(90deg, var(--sw-gold), rgba(200,155,60,0));
}
.wk-hero h1 em {
  font-style: normal; white-space: nowrap;
}
.wk-lead {
  max-width: none; margin: 0 0 22px;
  font-family: 'Lato', 'Segoe UI', sans-serif;
  font-size: .98rem; line-height: 1.7; color: var(--sw-text);
  text-align: justify; hyphens: manual;
}
.wk-more { margin: 0 0 22px; }
.wk-more p {
  margin: 0 0 10px; font-size: .95rem; line-height: 1.7; color: var(--sw-text);
  text-align: justify; hyphens: manual;
}
.wk-more p:last-child { margin-bottom: 0; }
.wk-actions { display: flex; flex-wrap: wrap; gap: 12px; }
.wk-hero .wk-actions .btn { padding: 11px 26px; font-size: .9rem; }

/* ---- image container: "leaf" shape, stretches to the text height ---- */
.wk-visual { position: relative; height: 100%; min-height: 340px; }
.wk-frame {
  position: absolute; inset: 0;
  border-radius: 110px 18px 110px 18px;
  overflow: hidden;
  background: linear-gradient(160deg, #FBE9CC 0%, #F3C48C 100%);
  box-shadow: 0 22px 46px rgba(23, 59, 42, .26);
}
.wk-frame::after {                 /* thin gold "mat" line inside the picture */
  content: ""; position: absolute; inset: 10px; z-index: 2; pointer-events: none;
  border: 1.5px solid rgba(200, 155, 60, .85);
  border-radius: 100px 10px 100px 10px;
}
.wk-frame img {
  position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;
}
.wk-frame-empty {
  position: absolute; inset: 0; display: flex; flex-direction: column;
  align-items: center; justify-content: center; gap: 6px;
  color: var(--sw-forest); text-align: center;
}
.wk-frame-empty svg { width: 46px; height: 46px; opacity: .55; }
.wk-frame-empty span { font-weight: 700; letter-spacing: .06em; }
.wk-frame-empty small { opacity: .75; }

/* glass stats bar inside the picture */
.wk-stats {
  position: absolute; z-index: 3; left: 26px; bottom: 26px;
  display: flex; align-items: center;
  padding: 8px 6px; border-radius: 999px;
  background: rgba(255, 253, 248, .9); backdrop-filter: blur(6px);
  border: 1px solid var(--sw-gold);
  box-shadow: 0 8px 18px rgba(11, 47, 34, .22);
}
.wk-stats > div { display: flex; align-items: baseline; gap: 6px; padding: 0 16px; }
.wk-stats > div + div { border-left: 1px solid rgba(23, 59, 42, .25); }
.wk-stats strong { font-family: 'Playfair Display', Georgia, serif; font-size: 1.1rem; color: var(--sw-forest); }
.wk-stats span   { font-size: .76rem; letter-spacing: .05em; color: var(--sw-text); }

/* entrance animation */
.wk-copy > * { opacity: 0; animation: wkRise .7s ease forwards; }
.wk-copy > *:nth-child(2) { animation-delay: .1s; }
.wk-copy > *:nth-child(3) { animation-delay: .2s; }
.wk-copy > *:nth-child(4) { animation-delay: .3s; }
.wk-copy > *:nth-child(5) { animation-delay: .4s; }
.wk-visual { opacity: 0; animation: wkRise .9s ease .15s forwards; }
@keyframes wkRise { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: none; } }
@keyframes wkSpin { to { transform: rotate(360deg); } }
@media (prefers-reduced-motion: reduce) {
  .wk-copy > *, .wk-visual { animation: none; opacity: 1; }
}

/* responsive */
@media (max-width: 900px) {
  .wk-hero-grid { grid-template-columns: minmax(0, 1fr); }
  .wk-visual { height: auto; min-height: 0; aspect-ratio: 4 / 3; }
  .wk-frame { border-radius: 70px 14px 70px 14px; }
  .wk-frame::after { border-radius: 62px 8px 62px 8px; }
}
@media (max-width: 480px) {
  .wk-eyebrow { letter-spacing: .16em; font-size: .68rem; gap: 10px; }
  .wk-eyebrow::before { width: 26px; }
  .wk-actions .btn { flex: 1 1 100%; text-align: center; }
  .wk-hero h1 em { white-space: normal; }
  .wk-visual { aspect-ratio: 1 / 1; }
  .wk-stats { left: 18px; bottom: 18px; }
  .wk-stats > div { padding: 0 12px; }
}

/* =====================================================
   TOP BANNER ("wk-banner") — image only. It is a <div>, so it
   does not shift the white/orange section alternation below it.
   ===================================================== */
.wk-banner {
  position: relative; overflow: hidden;
  width: 100%;
  height: clamp(300px, 52vw, 700px);
  background: #0B2F22;
}
.wk-banner-art, .wk-banner-photo {
  position: absolute; inset: 0; width: 100%; height: 100%;
}
.wk-banner-photo { object-fit: cover; object-position: center; }
/* =====================================================
   SECTION 2 — Explore Wildlife ("wk-explore")
   bg #173B2A, compact, small side spacing (same as section 1)
   ===================================================== */
section.section.wk-explore.wk-explore {
  background: #173B2A !important;
  position: relative; overflow: hidden;
  padding: clamp(32px, 4.5vw, 56px) 0;
  font-family: 'Lato', 'Segoe UI', sans-serif;
}
.wk-explore, .wk-explore * { box-sizing: border-box; }
.wk-explore .wk-ex-grid.container {
  position: relative; z-index: 1;
  max-width: 100%;
  padding-left: clamp(16px, 2.5vw, 36px);
  padding-right: clamp(16px, 2.5vw, 36px);
  display: grid; grid-template-columns: clamp(320px, 34vw, 500px) minmax(0, 1fr);
  gap: clamp(24px, 3vw, 44px);
  align-items: center;
}

/* ---- animal tiles (left) ---- */
.wk-ex-tiles { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.wk-ex-tile {
  display: flex; flex-direction: column; justify-content: flex-end; gap: 4px;
  position: relative; overflow: hidden;
  min-height: 150px; padding: 16px 18px; text-decoration: none;
  background: linear-gradient(160deg, rgba(255,253,248,.10), rgba(255,253,248,.03));
  border: 1px solid rgba(200,155,60,.6);
  border-radius: 70px 14px 70px 14px;
  transition: transform .3s ease, background .3s ease, border-color .3s ease;
}
.wk-ex-tile:nth-child(even) { border-radius: 14px 70px 14px 70px; margin-top: 22px; }
.wk-ex-tile:nth-child(odd)  { margin-bottom: 22px; }
.wk-ex-tile:hover { transform: translateY(-4px); border-color: #E8A15B; background: rgba(232,161,91,.16); }
.wk-explore .wk-ex-tile .wk-ex-emoji { position: relative; z-index: 0; font-size: 2.1rem; line-height: 1; margin-bottom: auto; }
.wk-ex-img {                       /* photo fills the whole tile */
  position: absolute; inset: 0; z-index: 1;
  width: 100%; height: 100%; object-fit: cover; object-position: center;
  transition: transform .5s ease;
}
.wk-ex-tile::after {               /* dark green fade so the label stays readable */
  content: ""; position: absolute; inset: 0; z-index: 2; pointer-events: none;
  background: linear-gradient(to top, rgba(11,47,34,.88) 0%, rgba(11,47,34,.35) 45%, rgba(11,47,34,0) 75%);
}
.wk-ex-tile:hover .wk-ex-img { transform: scale(1.06); }
.wk-explore .wk-ex-tile strong { position: relative; z-index: 3; font-family: 'Playfair Display', Georgia, serif; font-size: 1.05rem; color: #FFFDF8; }
.wk-explore .wk-ex-tile .wk-ex-sub { position: relative; z-index: 3; font-size: .78rem; letter-spacing: .04em; color: rgba(255,253,248,.85); }

/* ---- copy (right) ---- */
.wk-explore .wk-eyebrow { color: #E8A15B; }
.wk-explore h2 {
  margin: 0 0 14px; width: 100%;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.3rem, 2.2vw, 1.9rem);
  font-weight: 700; line-height: 1.12; letter-spacing: -.005em;
  color: #FFFDF8 !important;
}
.wk-explore h2 .wk-h1-accent { color: #E8A15B; font-style: italic; }
.wk-explore h2::after {
  content: ""; display: block; width: 100%; height: 2px; margin-top: 14px;
  background: linear-gradient(90deg, #E8A15B, rgba(232,161,91,0));
}
.wk-explore .wk-ex-copy p {
  margin: 0 0 10px; font-size: .95rem; line-height: 1.7;
  color: rgba(255,253,248,.88); text-align: justify; hyphens: manual;
}
.wk-explore .wk-ex-copy p:last-of-type { margin-bottom: 22px; }
.wk-explore .wk-ex-btn {
  display: inline-block; padding: 11px 26px; font-size: .9rem; font-weight: 700;
  background: #E8A15B; color: #173B2A; border: 2px solid #E8A15B; border-radius: 999px;
  text-decoration: none;
  transition: background .25s, color .25s, transform .25s;
}
.wk-explore .wk-ex-btn:hover { background: #FFFDF8; border-color: #FFFDF8; color: #173B2A; transform: translateY(-2px); }

@media (max-width: 900px) {
  .wk-explore .wk-ex-grid.container { grid-template-columns: minmax(0, 1fr); }
  .wk-ex-copy { order: -1; }
}
@media (max-width: 480px) {
  .wk-ex-tile { min-height: 120px; padding: 12px 14px; border-radius: 50px 12px 50px 12px; }
  .wk-ex-tile:nth-child(even) { border-radius: 12px 50px 12px 50px; }
  .wk-explore .wk-ex-btn { display: block; text-align: center; }
}
/* =====================================================
   SECTION 1 button -> Safari Orange (same as sections 2 & 3)
   ===================================================== */
.wk-hero .wk-actions .btn-gold {
  background: #E8A15B; border-color: #E8A15B; color: #173B2A; font-weight: 700;
}
.wk-hero .wk-actions .btn-gold:hover {
  background: #173B2A; border-color: #173B2A; color: #FFFDF8;
}

/* =====================================================
   SECTION 3 — Explore Diverse Wildlife Habitats ("wk-habitats")
   bg soft white (like section 1), image on the left with a zig-zag
   edge on the side that faces the text
   ===================================================== */
section.section.wk-habitats.wk-habitats {
  background: #FFFDF8 !important;
  position: relative; overflow: hidden;
  padding: clamp(32px, 4.5vw, 56px) 0;
  font-family: 'Lato', 'Segoe UI', sans-serif;
}
.wk-habitats, .wk-habitats * { box-sizing: border-box; }
.wk-habitats .wk-hb-grid.container {
  position: relative; z-index: 1;
  max-width: 100%;
  padding-left: clamp(16px, 2.5vw, 36px);
  padding-right: clamp(16px, 2.5vw, 36px);
  display: grid; grid-template-columns: clamp(320px, 36vw, 540px) minmax(0, 1fr);
  gap: clamp(20px, 2.5vw, 36px);
  align-items: stretch;             /* image height = text height */
}

/* ---- image with zig-zag edge (right side, facing the text) ---- */
.wk-hb-visual { position: relative; min-height: 340px; }
.wk-hb-art {                     /* wrapper carries the soft paper shadow */
  position: absolute; inset: 0;
  filter: drop-shadow(3px 5px 7px rgba(23,59,42,.28));
}
.wk-hb-paper {                   /* torn-paper fibre edge peeking out behind the photo */
  position: absolute; inset: 0;
  background: linear-gradient(90deg, #F6EEDD 0%, #EADFC8 100%);
  clip-path: polygon(0 0, calc(100% - 37.1px) 0.00%, calc(100% - 36.8px) 0.59%, calc(100% - 39.6px) 1.18%, calc(100% - 41.3px) 1.76%, calc(100% - 42.5px) 2.35%, calc(100% - 42.8px) 2.94%, calc(100% - 43.4px) 3.53%, calc(100% - 44.4px) 4.12%, calc(100% - 44.7px) 4.71%, calc(100% - 45.2px) 5.29%, calc(100% - 47.4px) 5.88%, calc(100% - 49.3px) 6.47%, calc(100% - 49.9px) 7.06%, calc(100% - 50.2px) 7.65%, calc(100% - 51.7px) 8.24%, calc(100% - 53.5px) 8.82%, calc(100% - 54.4px) 9.41%, calc(100% - 55.4px) 10.00%, calc(100% - 56.1px) 10.59%, calc(100% - 58.4px) 11.18%, calc(100% - 57.8px) 11.76%, calc(100% - 58.5px) 12.35%, calc(100% - 58.6px) 12.94%, calc(100% - 57.2px) 13.53%, calc(100% - 54.9px) 14.12%, calc(100% - 54.3px) 14.71%, calc(100% - 53.4px) 15.29%, calc(100% - 54.9px) 15.88%, calc(100% - 54.8px) 16.47%, calc(100% - 55.1px) 17.06%, calc(100% - 54.8px) 17.65%, calc(100% - 54.7px) 18.24%, calc(100% - 53.5px) 18.82%, calc(100% - 53.0px) 19.41%, calc(100% - 53.1px) 20.00%, calc(100% - 52.4px) 20.59%, calc(100% - 50.0px) 21.18%, calc(100% - 49.2px) 21.76%, calc(100% - 50.3px) 22.35%, calc(100% - 51.0px) 22.94%, calc(100% - 53.2px) 23.53%, calc(100% - 54.7px) 24.12%, calc(100% - 55.1px) 24.71%, calc(100% - 54.3px) 25.29%, calc(100% - 52.4px) 25.88%, calc(100% - 50.0px) 26.47%, calc(100% - 48.1px) 27.06%, calc(100% - 49.1px) 27.65%, calc(100% - 48.4px) 28.24%, calc(100% - 46.4px) 28.82%, calc(100% - 45.8px) 29.41%, calc(100% - 45.4px) 30.00%, calc(100% - 44.2px) 30.59%, calc(100% - 45.1px) 31.18%, calc(100% - 46.9px) 31.76%, calc(100% - 47.9px) 32.35%, calc(100% - 48.1px) 32.94%, calc(100% - 47.4px) 33.53%, calc(100% - 47.0px) 34.12%, calc(100% - 49.3px) 34.71%, calc(100% - 49.3px) 35.29%, calc(100% - 48.7px) 35.88%, calc(100% - 47.6px) 36.47%, calc(100% - 48.3px) 37.06%, calc(100% - 49.1px) 37.65%, calc(100% - 49.2px) 38.24%, calc(100% - 48.5px) 38.82%, calc(100% - 48.0px) 39.41%, calc(100% - 45.6px) 40.00%, calc(100% - 41.0px) 40.59%, calc(100% - 37.8px) 41.18%, calc(100% - 36.8px) 41.76%, calc(100% - 37.0px) 42.35%, calc(100% - 36.7px) 42.94%, calc(100% - 37.0px) 43.53%, calc(100% - 37.8px) 44.12%, calc(100% - 40.2px) 44.71%, calc(100% - 40.8px) 45.29%, calc(100% - 43.1px) 45.88%, calc(100% - 44.4px) 46.47%, calc(100% - 44.9px) 47.06%, calc(100% - 44.3px) 47.65%, calc(100% - 45.4px) 48.24%, calc(100% - 45.1px) 48.82%, calc(100% - 45.2px) 49.41%, calc(100% - 45.4px) 50.00%, calc(100% - 45.7px) 50.59%, calc(100% - 47.0px) 51.18%, calc(100% - 47.2px) 51.76%, calc(100% - 45.8px) 52.35%, calc(100% - 44.7px) 52.94%, calc(100% - 42.9px) 53.53%, calc(100% - 41.8px) 54.12%, calc(100% - 37.5px) 54.71%, calc(100% - 36.2px) 55.29%, calc(100% - 34.9px) 55.88%, calc(100% - 34.1px) 56.47%, calc(100% - 33.9px) 57.06%, calc(100% - 33.5px) 57.65%, calc(100% - 31.6px) 58.24%, calc(100% - 29.3px) 58.82%, calc(100% - 26.5px) 59.41%, calc(100% - 24.1px) 60.00%, calc(100% - 21.7px) 60.59%, calc(100% - 22.3px) 61.18%, calc(100% - 21.8px) 61.76%, calc(100% - 21.8px) 62.35%, calc(100% - 23.7px) 62.94%, calc(100% - 25.3px) 63.53%, calc(100% - 23.9px) 64.12%, calc(100% - 22.1px) 64.71%, calc(100% - 20.2px) 65.29%, calc(100% - 17.7px) 65.88%, calc(100% - 15.6px) 66.47%, calc(100% - 12.4px) 67.06%, calc(100% - 9.4px) 67.65%, calc(100% - 10.3px) 68.24%, calc(100% - 10.2px) 68.82%, calc(100% - 10.2px) 69.41%, calc(100% - 10.0px) 70.00%, calc(100% - 10.4px) 70.59%, calc(100% - 11.2px) 71.18%, calc(100% - 11.0px) 71.76%, calc(100% - 11.0px) 72.35%, calc(100% - 13.0px) 72.94%, calc(100% - 14.2px) 73.53%, calc(100% - 14.2px) 74.12%, calc(100% - 13.6px) 74.71%, calc(100% - 13.4px) 75.29%, calc(100% - 13.6px) 75.88%, calc(100% - 13.0px) 76.47%, calc(100% - 15.3px) 77.06%, calc(100% - 16.8px) 77.65%, calc(100% - 21.3px) 78.24%, calc(100% - 22.1px) 78.82%, calc(100% - 23.7px) 79.41%, calc(100% - 24.1px) 80.00%, calc(100% - 24.3px) 80.59%, calc(100% - 23.8px) 81.18%, calc(100% - 24.4px) 81.76%, calc(100% - 24.3px) 82.35%, calc(100% - 25.3px) 82.94%, calc(100% - 26.4px) 83.53%, calc(100% - 27.7px) 84.12%, calc(100% - 29.6px) 84.71%, calc(100% - 32.6px) 85.29%, calc(100% - 34.8px) 85.88%, calc(100% - 37.6px) 86.47%, calc(100% - 39.5px) 87.06%, calc(100% - 40.4px) 87.65%, calc(100% - 38.8px) 88.24%, calc(100% - 38.8px) 88.82%, calc(100% - 38.6px) 89.41%, calc(100% - 38.0px) 90.00%, calc(100% - 38.6px) 90.59%, calc(100% - 39.6px) 91.18%, calc(100% - 39.8px) 91.76%, calc(100% - 38.5px) 92.35%, calc(100% - 37.8px) 92.94%, calc(100% - 36.9px) 93.53%, calc(100% - 36.5px) 94.12%, calc(100% - 37.0px) 94.71%, calc(100% - 35.6px) 95.29%, calc(100% - 35.1px) 95.88%, calc(100% - 35.7px) 96.47%, calc(100% - 38.2px) 97.06%, calc(100% - 39.4px) 97.65%, calc(100% - 41.3px) 98.24%, calc(100% - 41.7px) 98.82%, calc(100% - 42.9px) 99.41%, calc(100% - 43.5px) 100.00%, 0 100%);
}
.wk-hb-frame {
  position: absolute; inset: 0; overflow: hidden;
  background: linear-gradient(160deg, #2E5B44 0%, #173B2A 100%);
  clip-path: polygon(0 0, calc(100% - 40.2px) 0.00%, calc(100% - 41.2px) 0.59%, calc(100% - 42.8px) 1.18%, calc(100% - 44.7px) 1.76%, calc(100% - 45.9px) 2.35%, calc(100% - 46.5px) 2.94%, calc(100% - 46.9px) 3.53%, calc(100% - 47.4px) 4.12%, calc(100% - 48.3px) 4.71%, calc(100% - 49.2px) 5.29%, calc(100% - 50.9px) 5.88%, calc(100% - 52.3px) 6.47%, calc(100% - 54.3px) 7.06%, calc(100% - 55.0px) 7.65%, calc(100% - 56.8px) 8.24%, calc(100% - 58.0px) 8.82%, calc(100% - 59.6px) 9.41%, calc(100% - 60.4px) 10.00%, calc(100% - 61.2px) 10.59%, calc(100% - 62.2px) 11.18%, calc(100% - 63.1px) 11.76%, calc(100% - 63.2px) 12.35%, calc(100% - 62.9px) 12.94%, calc(100% - 61.2px) 13.53%, calc(100% - 59.7px) 14.12%, calc(100% - 58.7px) 14.71%, calc(100% - 58.6px) 15.29%, calc(100% - 60.1px) 15.88%, calc(100% - 60.2px) 16.47%, calc(100% - 60.4px) 17.06%, calc(100% - 58.9px) 17.65%, calc(100% - 58.1px) 18.24%, calc(100% - 57.2px) 18.82%, calc(100% - 56.6px) 19.41%, calc(100% - 56.6px) 20.00%, calc(100% - 55.8px) 20.59%, calc(100% - 55.7px) 21.18%, calc(100% - 54.5px) 21.76%, calc(100% - 55.4px) 22.35%, calc(100% - 56.7px) 22.94%, calc(100% - 59.1px) 23.53%, calc(100% - 60.5px) 24.12%, calc(100% - 60.8px) 24.71%, calc(100% - 60.1px) 25.29%, calc(100% - 57.8px) 25.88%, calc(100% - 56.0px) 26.47%, calc(100% - 54.8px) 27.06%, calc(100% - 54.1px) 27.65%, calc(100% - 53.7px) 28.24%, calc(100% - 52.8px) 28.82%, calc(100% - 51.9px) 29.41%, calc(100% - 50.8px) 30.00%, calc(100% - 50.6px) 30.59%, calc(100% - 51.5px) 31.18%, calc(100% - 53.3px) 31.76%, calc(100% - 54.2px) 32.35%, calc(100% - 54.5px) 32.94%, calc(100% - 54.1px) 33.53%, calc(100% - 54.8px) 34.12%, calc(100% - 55.9px) 34.71%, calc(100% - 56.1px) 35.29%, calc(100% - 55.4px) 35.88%, calc(100% - 54.7px) 36.47%, calc(100% - 55.1px) 37.06%, calc(100% - 55.4px) 37.65%, calc(100% - 56.2px) 38.24%, calc(100% - 56.1px) 38.82%, calc(100% - 55.6px) 39.41%, calc(100% - 52.9px) 40.00%, calc(100% - 49.7px) 40.59%, calc(100% - 46.7px) 41.18%, calc(100% - 45.5px) 41.76%, calc(100% - 44.8px) 42.35%, calc(100% - 44.9px) 42.94%, calc(100% - 44.8px) 43.53%, calc(100% - 45.8px) 44.12%, calc(100% - 46.9px) 44.71%, calc(100% - 49.0px) 45.29%, calc(100% - 50.8px) 45.88%, calc(100% - 51.8px) 46.47%, calc(100% - 52.0px) 47.06%, calc(100% - 52.2px) 47.65%, calc(100% - 53.0px) 48.24%, calc(100% - 53.6px) 48.82%, calc(100% - 53.9px) 49.41%, calc(100% - 54.7px) 50.00%, calc(100% - 55.3px) 50.59%, calc(100% - 55.7px) 51.18%, calc(100% - 55.3px) 51.76%, calc(100% - 53.9px) 52.35%, calc(100% - 52.4px) 52.94%, calc(100% - 50.3px) 53.53%, calc(100% - 49.1px) 54.12%, calc(100% - 47.2px) 54.71%, calc(100% - 45.4px) 55.29%, calc(100% - 43.9px) 55.88%, calc(100% - 43.5px) 56.47%, calc(100% - 43.1px) 57.06%, calc(100% - 42.2px) 57.65%, calc(100% - 39.9px) 58.24%, calc(100% - 37.6px) 58.82%, calc(100% - 34.5px) 59.41%, calc(100% - 32.5px) 60.00%, calc(100% - 30.4px) 60.59%, calc(100% - 29.0px) 61.18%, calc(100% - 28.6px) 61.76%, calc(100% - 29.7px) 62.35%, calc(100% - 31.3px) 62.94%, calc(100% - 32.3px) 63.53%, calc(100% - 31.7px) 64.12%, calc(100% - 29.6px) 64.71%, calc(100% - 27.3px) 65.29%, calc(100% - 24.3px) 65.88%, calc(100% - 22.0px) 66.47%, calc(100% - 18.7px) 67.06%, calc(100% - 16.5px) 67.65%, calc(100% - 16.0px) 68.24%, calc(100% - 16.0px) 68.82%, calc(100% - 16.0px) 69.41%, calc(100% - 16.0px) 70.00%, calc(100% - 16.0px) 70.59%, calc(100% - 16.0px) 71.18%, calc(100% - 16.0px) 71.76%, calc(100% - 16.2px) 72.35%, calc(100% - 17.8px) 72.94%, calc(100% - 18.6px) 73.53%, calc(100% - 19.9px) 74.12%, calc(100% - 19.5px) 74.71%, calc(100% - 19.4px) 75.29%, calc(100% - 18.9px) 75.88%, calc(100% - 18.7px) 76.47%, calc(100% - 19.6px) 77.06%, calc(100% - 21.2px) 77.65%, calc(100% - 24.7px) 78.24%, calc(100% - 27.3px) 78.82%, calc(100% - 28.7px) 79.41%, calc(100% - 28.8px) 80.00%, calc(100% - 28.7px) 80.59%, calc(100% - 28.7px) 81.18%, calc(100% - 28.7px) 81.76%, calc(100% - 29.1px) 82.35%, calc(100% - 30.3px) 82.94%, calc(100% - 31.9px) 83.53%, calc(100% - 33.5px) 84.12%, calc(100% - 34.6px) 84.71%, calc(100% - 37.0px) 85.29%, calc(100% - 39.4px) 85.88%, calc(100% - 41.6px) 86.47%, calc(100% - 43.1px) 87.06%, calc(100% - 43.6px) 87.65%, calc(100% - 43.9px) 88.24%, calc(100% - 43.1px) 88.82%, calc(100% - 42.4px) 89.41%, calc(100% - 42.0px) 90.00%, calc(100% - 42.4px) 90.59%, calc(100% - 42.9px) 91.18%, calc(100% - 42.8px) 91.76%, calc(100% - 41.7px) 92.35%, calc(100% - 40.9px) 92.94%, calc(100% - 40.6px) 93.53%, calc(100% - 40.7px) 94.12%, calc(100% - 40.0px) 94.71%, calc(100% - 38.6px) 95.29%, calc(100% - 38.4px) 95.88%, calc(100% - 38.7px) 96.47%, calc(100% - 41.2px) 97.06%, calc(100% - 42.5px) 97.65%, calc(100% - 44.3px) 98.24%, calc(100% - 44.7px) 98.82%, calc(100% - 45.9px) 99.41%, calc(100% - 46.5px) 100.00%, 0 100%);   /* irregular, hand-torn edge */
}
.wk-hb-frame img {
  position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;
}
.wk-hb-empty {
  position: absolute; inset: 0; display: flex; flex-direction: column;
  align-items: center; justify-content: center; gap: 6px; text-align: center;
  color: #FFFDF8;
}
.wk-hb-empty b { font-size: 2.4rem; font-weight: 400; line-height: 1; }
.wk-habitats .wk-hb-empty span { font-weight: 700; letter-spacing: .06em; color: #FFFDF8; }
.wk-hb-empty small { opacity: .75; }
.wk-habitats .wk-hb-tags {
  position: absolute; z-index: 3; left: 18px; bottom: 18px;
  display: flex; flex-wrap: wrap; gap: 8px; max-width: calc(100% - 60px);
}
.wk-habitats .wk-hb-tags span {
  padding: 6px 14px; border-radius: 999px; font-size: .78rem; font-weight: 700;
  letter-spacing: .04em; color: #173B2A;
  background: rgba(255,253,248,.92); border: 1px solid #C89B3C;
  box-shadow: 0 6px 14px rgba(11,47,34,.22);
}

/* ---- copy ---- */
.wk-habitats .wk-eyebrow { color: #173B2A; }
.wk-habitats h2 {
  margin: 0 0 14px; width: 100%;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.3rem, 2.2vw, 1.9rem);
  font-weight: 700; line-height: 1.12; letter-spacing: -.005em;
}
.wk-habitats h2 .wk-hb-black { color: #000 !important; }
.wk-habitats h2 .wk-hb-accent { color: #E8A15B !important; font-style: italic; }
.wk-habitats h2::after {
  content: ""; display: block; width: 100%; height: 2px; margin-top: 14px;
  background: linear-gradient(90deg, #C89B3C, rgba(200,155,60,0));
}
.wk-habitats .wk-hb-copy p {
  margin: 0 0 10px; font-size: .95rem; line-height: 1.7;
  color: #2B241D; text-align: justify; hyphens: manual;
}
.wk-habitats .wk-hb-copy p:last-of-type { margin-bottom: 22px; }
.wk-habitats .wk-hb-btn {
  display: inline-block; padding: 11px 26px; font-size: .9rem; font-weight: 700;
  background: #E8A15B; color: #173B2A; border: 2px solid #E8A15B; border-radius: 999px;
  text-decoration: none;
  transition: background .25s, color .25s, border-color .25s, transform .25s;
}
.wk-habitats .wk-hb-btn:hover { background: #173B2A; border-color: #173B2A; color: #FFFDF8; transform: translateY(-2px); }

@media (max-width: 900px) {
  .wk-habitats .wk-hb-grid.container { grid-template-columns: minmax(0, 1fr); }
  .wk-hb-visual { min-height: 0; aspect-ratio: 16 / 10; }
  .wk-hb-paper { clip-path: polygon(0 0, 100% 0, 100.00% calc(100% - 26.1px), 99.41% calc(100% - 25.8px), 98.82% calc(100% - 25.0px), 98.24% calc(100% - 24.8px), 97.65% calc(100% - 23.6px), 97.06% calc(100% - 22.9px), 96.47% calc(100% - 21.4px), 95.88% calc(100% - 21.0px), 95.29% calc(100% - 21.4px), 94.71% calc(100% - 22.2px), 94.12% calc(100% - 21.9px), 93.53% calc(100% - 22.2px), 92.94% calc(100% - 22.7px), 92.35% calc(100% - 23.1px), 91.76% calc(100% - 23.9px), 91.18% calc(100% - 23.8px), 90.59% calc(100% - 23.2px), 90.00% calc(100% - 22.8px), 89.41% calc(100% - 23.2px), 88.82% calc(100% - 23.3px), 88.24% calc(100% - 23.3px), 87.65% calc(100% - 24.2px), 87.06% calc(100% - 23.7px), 86.47% calc(100% - 22.5px), 85.88% calc(100% - 20.9px), 85.29% calc(100% - 19.6px), 84.71% calc(100% - 17.8px), 84.12% calc(100% - 16.6px), 83.53% calc(100% - 15.9px), 82.94% calc(100% - 15.2px), 82.35% calc(100% - 14.6px), 81.76% calc(100% - 14.6px), 81.18% calc(100% - 14.3px), 80.59% calc(100% - 14.6px), 80.00% calc(100% - 14.4px), 79.41% calc(100% - 14.2px), 78.82% calc(100% - 13.3px), 78.24% calc(100% - 12.8px), 77.65% calc(100% - 10.1px), 77.06% calc(100% - 9.2px), 76.47% calc(100% - 7.8px), 75.88% calc(100% - 8.2px), 75.29% calc(100% - 8.1px), 74.71% calc(100% - 8.2px), 74.12% calc(100% - 8.5px), 73.53% calc(100% - 8.5px), 72.94% calc(100% - 7.8px), 72.35% calc(100% - 6.6px), 71.76% calc(100% - 6.6px), 71.18% calc(100% - 6.7px), 70.59% calc(100% - 6.3px), 70.00% calc(100% - 6.0px), 69.41% calc(100% - 6.1px), 68.82% calc(100% - 6.1px), 68.24% calc(100% - 6.2px), 67.65% calc(100% - 5.6px), 67.06% calc(100% - 7.4px), 66.47% calc(100% - 9.4px), 65.88% calc(100% - 10.6px), 65.29% calc(100% - 12.1px), 64.71% calc(100% - 13.2px), 64.12% calc(100% - 14.3px), 63.53% calc(100% - 15.2px), 62.94% calc(100% - 14.2px), 62.35% calc(100% - 13.1px), 61.76% calc(100% - 13.1px), 61.18% calc(100% - 13.4px), 60.59% calc(100% - 13.0px), 60.00% calc(100% - 14.5px), 59.41% calc(100% - 15.9px), 58.82% calc(100% - 17.6px), 58.24% calc(100% - 19.0px), 57.65% calc(100% - 20.1px), 57.06% calc(100% - 20.4px), 56.47% calc(100% - 20.4px), 55.88% calc(100% - 21.0px), 55.29% calc(100% - 21.7px), 54.71% calc(100% - 22.5px), 54.12% calc(100% - 25.1px), 53.53% calc(100% - 25.8px), 52.94% calc(100% - 26.8px), 52.35% calc(100% - 27.5px), 51.76% calc(100% - 28.3px), 51.18% calc(100% - 28.2px), 50.59% calc(100% - 27.4px), 50.00% calc(100% - 27.2px), 49.41% calc(100% - 27.1px), 48.82% calc(100% - 27.1px), 48.24% calc(100% - 27.2px), 47.65% calc(100% - 26.6px), 47.06% calc(100% - 27.0px), 46.47% calc(100% - 26.7px), 45.88% calc(100% - 25.8px), 45.29% calc(100% - 24.5px), 44.71% calc(100% - 24.1px), 44.12% calc(100% - 22.7px), 43.53% calc(100% - 22.2px), 42.94% calc(100% - 22.0px), 42.35% calc(100% - 22.2px), 41.76% calc(100% - 22.1px), 41.18% calc(100% - 22.7px), 40.59% calc(100% - 24.6px), 40.00% calc(100% - 27.4px), 39.41% calc(100% - 28.8px), 38.82% calc(100% - 29.1px), 38.24% calc(100% - 29.5px), 37.65% calc(100% - 29.5px), 37.06% calc(100% - 29.0px), 36.47% calc(100% - 28.6px), 35.88% calc(100% - 29.2px), 35.29% calc(100% - 29.6px), 34.71% calc(100% - 29.6px), 34.12% calc(100% - 28.2px), 33.53% calc(100% - 28.4px), 32.94% calc(100% - 28.8px), 32.35% calc(100% - 28.7px), 31.76% calc(100% - 28.1px), 31.18% calc(100% - 27.1px), 30.59% calc(100% - 26.5px), 30.00% calc(100% - 27.2px), 29.41% calc(100% - 27.5px), 28.82% calc(100% - 27.8px), 28.24% calc(100% - 29.0px), 27.65% calc(100% - 29.4px), 27.06% calc(100% - 28.9px), 26.47% calc(100% - 30.0px), 25.88% calc(100% - 31.4px), 25.29% calc(100% - 32.6px), 24.71% calc(100% - 33.1px), 24.12% calc(100% - 32.8px), 23.53% calc(100% - 31.9px), 22.94% calc(100% - 30.6px), 22.35% calc(100% - 30.2px), 21.76% calc(100% - 29.5px), 21.18% calc(100% - 30.0px), 20.59% calc(100% - 31.5px), 20.00% calc(100% - 31.9px), 19.41% calc(100% - 31.8px), 18.82% calc(100% - 32.1px), 18.24% calc(100% - 32.8px), 17.65% calc(100% - 32.9px), 17.06% calc(100% - 33.1px), 16.47% calc(100% - 32.9px), 15.88% calc(100% - 32.9px), 15.29% calc(100% - 32.1px), 14.71% calc(100% - 32.6px), 14.12% calc(100% - 32.9px), 13.53% calc(100% - 34.3px), 12.94% calc(100% - 35.1px), 12.35% calc(100% - 35.1px), 11.76% calc(100% - 34.7px), 11.18% calc(100% - 35.0px), 10.59% calc(100% - 33.6px), 10.00% calc(100% - 33.3px), 9.41% calc(100% - 32.7px), 8.82% calc(100% - 32.1px), 8.24% calc(100% - 31.0px), 7.65% calc(100% - 30.1px), 7.06% calc(100% - 30.0px), 6.47% calc(100% - 29.6px), 5.88% calc(100% - 28.4px), 5.29% calc(100% - 27.1px), 4.71% calc(100% - 26.8px), 4.12% calc(100% - 26.6px), 3.53% calc(100% - 26.1px), 2.94% calc(100% - 25.7px), 2.35% calc(100% - 25.5px), 1.76% calc(100% - 24.8px), 1.18% calc(100% - 23.8px), 0.59% calc(100% - 22.1px), 0.00% calc(100% - 22.2px)); }
  .wk-hb-frame { clip-path: polygon(0 0, 100% 0, 100.00% calc(100% - 27.9px), 99.41% calc(100% - 27.6px), 98.82% calc(100% - 26.8px), 98.24% calc(100% - 26.6px), 97.65% calc(100% - 25.5px), 97.06% calc(100% - 24.7px), 96.47% calc(100% - 23.2px), 95.88% calc(100% - 23.0px), 95.29% calc(100% - 23.2px), 94.71% calc(100% - 24.0px), 94.12% calc(100% - 24.4px), 93.53% calc(100% - 24.4px), 92.94% calc(100% - 24.5px), 92.35% calc(100% - 25.0px), 91.76% calc(100% - 25.7px), 91.18% calc(100% - 25.7px), 90.59% calc(100% - 25.4px), 90.00% calc(100% - 25.2px), 89.41% calc(100% - 25.4px), 88.82% calc(100% - 25.8px), 88.24% calc(100% - 26.4px), 87.65% calc(100% - 26.2px), 87.06% calc(100% - 25.9px), 86.47% calc(100% - 25.0px), 85.88% calc(100% - 23.6px), 85.29% calc(100% - 22.2px), 84.71% calc(100% - 20.8px), 84.12% calc(100% - 20.1px), 83.53% calc(100% - 19.1px), 82.94% calc(100% - 18.2px), 82.35% calc(100% - 17.5px), 81.76% calc(100% - 17.2px), 81.18% calc(100% - 17.2px), 80.59% calc(100% - 17.2px), 80.00% calc(100% - 17.3px), 79.41% calc(100% - 17.2px), 78.82% calc(100% - 16.4px), 78.24% calc(100% - 14.8px), 77.65% calc(100% - 12.7px), 77.06% calc(100% - 11.7px), 76.47% calc(100% - 11.2px), 75.88% calc(100% - 11.3px), 75.29% calc(100% - 11.6px), 74.71% calc(100% - 11.7px), 74.12% calc(100% - 11.9px), 73.53% calc(100% - 11.1px), 72.94% calc(100% - 10.7px), 72.35% calc(100% - 9.7px), 71.76% calc(100% - 9.6px), 71.18% calc(100% - 9.6px), 70.59% calc(100% - 9.6px), 70.00% calc(100% - 9.6px), 69.41% calc(100% - 9.6px), 68.82% calc(100% - 9.6px), 68.24% calc(100% - 9.6px), 67.65% calc(100% - 9.9px), 67.06% calc(100% - 11.2px), 66.47% calc(100% - 13.2px), 65.88% calc(100% - 14.6px), 65.29% calc(100% - 16.4px), 64.71% calc(100% - 17.7px), 64.12% calc(100% - 19.0px), 63.53% calc(100% - 19.4px), 62.94% calc(100% - 18.8px), 62.35% calc(100% - 17.8px), 61.76% calc(100% - 17.2px), 61.18% calc(100% - 17.4px), 60.59% calc(100% - 18.3px), 60.00% calc(100% - 19.5px), 59.41% calc(100% - 20.7px), 58.82% calc(100% - 22.5px), 58.24% calc(100% - 23.9px), 57.65% calc(100% - 25.3px), 57.06% calc(100% - 25.9px), 56.47% calc(100% - 26.1px), 55.88% calc(100% - 26.4px), 55.29% calc(100% - 27.2px), 54.71% calc(100% - 28.3px), 54.12% calc(100% - 29.5px), 53.53% calc(100% - 30.2px), 52.94% calc(100% - 31.5px), 52.35% calc(100% - 32.3px), 51.76% calc(100% - 33.2px), 51.18% calc(100% - 33.4px), 50.59% calc(100% - 33.2px), 50.00% calc(100% - 32.8px), 49.41% calc(100% - 32.3px), 48.82% calc(100% - 32.2px), 48.24% calc(100% - 31.8px), 47.65% calc(100% - 31.3px), 47.06% calc(100% - 31.2px), 46.47% calc(100% - 31.1px), 45.88% calc(100% - 30.5px), 45.29% calc(100% - 29.4px), 44.71% calc(100% - 28.1px), 44.12% calc(100% - 27.5px), 43.53% calc(100% - 26.9px), 42.94% calc(100% - 26.9px), 42.35% calc(100% - 26.9px), 41.76% calc(100% - 27.3px), 41.18% calc(100% - 28.0px), 40.59% calc(100% - 29.8px), 40.00% calc(100% - 31.8px), 39.41% calc(100% - 33.3px), 38.82% calc(100% - 33.7px), 38.24% calc(100% - 33.7px), 37.65% calc(100% - 33.2px), 37.06% calc(100% - 33.0px), 36.47% calc(100% - 32.8px), 35.88% calc(100% - 33.3px), 35.29% calc(100% - 33.6px), 34.71% calc(100% - 33.5px), 34.12% calc(100% - 32.9px), 33.53% calc(100% - 32.4px), 32.94% calc(100% - 32.7px), 32.35% calc(100% - 32.5px), 31.76% calc(100% - 32.0px), 31.18% calc(100% - 30.9px), 30.59% calc(100% - 30.4px), 30.00% calc(100% - 30.5px), 29.41% calc(100% - 31.1px), 28.82% calc(100% - 31.7px), 28.24% calc(100% - 32.2px), 27.65% calc(100% - 32.5px), 27.06% calc(100% - 32.9px), 26.47% calc(100% - 33.6px), 25.88% calc(100% - 34.7px), 25.29% calc(100% - 36.0px), 24.71% calc(100% - 36.5px), 24.12% calc(100% - 36.3px), 23.53% calc(100% - 35.4px), 22.94% calc(100% - 34.0px), 22.35% calc(100% - 33.3px), 21.76% calc(100% - 32.7px), 21.18% calc(100% - 33.4px), 20.59% calc(100% - 33.5px), 20.00% calc(100% - 33.9px), 19.41% calc(100% - 34.0px), 18.82% calc(100% - 34.3px), 18.24% calc(100% - 34.9px), 17.65% calc(100% - 35.4px), 17.06% calc(100% - 36.2px), 16.47% calc(100% - 36.1px), 15.88% calc(100% - 36.1px), 15.29% calc(100% - 35.2px), 14.71% calc(100% - 35.2px), 14.12% calc(100% - 35.8px), 13.53% calc(100% - 36.7px), 12.94% calc(100% - 37.7px), 12.35% calc(100% - 37.9px), 11.76% calc(100% - 37.8px), 11.18% calc(100% - 37.3px), 10.59% calc(100% - 36.7px), 10.00% calc(100% - 36.2px), 9.41% calc(100% - 35.8px), 8.82% calc(100% - 34.8px), 8.24% calc(100% - 34.1px), 7.65% calc(100% - 33.0px), 7.06% calc(100% - 32.6px), 6.47% calc(100% - 31.4px), 5.88% calc(100% - 30.6px), 5.29% calc(100% - 29.5px), 4.71% calc(100% - 29.0px), 4.12% calc(100% - 28.4px), 3.53% calc(100% - 28.1px), 2.94% calc(100% - 27.9px), 2.35% calc(100% - 27.5px), 1.76% calc(100% - 26.8px), 1.18% calc(100% - 25.7px), 0.59% calc(100% - 24.7px), 0.00% calc(100% - 24.1px)); }   /* torn edge moves to the bottom */
  .wk-habitats .wk-hb-tags { bottom: 32px; }
}
@media (max-width: 480px) {
  .wk-habitats .wk-hb-btn { display: block; text-align: center; }
}

/* =====================================================
   SECTION 4 — Wildlife Experiences ("wk-xp")
   bg #173B2A (same green as section 2), compact, small side spacing.
   It is a <div> (not <section>) so the white/orange alternation of the
   sections below stays exactly as it was.
   Layout: text on the left, arch photo + round photo + badge on the right
   ===================================================== */
.section.wk-xp.wk-xp {
  background: #173B2A !important;
  position: relative; overflow: hidden;
  padding: clamp(32px, 4.5vw, 56px) 0;
  font-family: 'Lato', 'Segoe UI', sans-serif;
}
.wk-xp, .wk-xp * { box-sizing: border-box; }
.wk-xp .wk-xp-grid.container {
  position: relative; z-index: 1;
  max-width: 100%;
  padding-left: clamp(16px, 2.5vw, 36px);
  padding-right: clamp(16px, 2.5vw, 36px);
  display: grid; grid-template-columns: minmax(0, 1fr) clamp(300px, 34vw, 440px);
  gap: clamp(24px, 3vw, 44px);
  align-items: center;
}

/* ---- copy (left) ---- */
.wk-xp .wk-eyebrow { color: #E8A15B; }
.wk-xp h2 {
  margin: 0 0 14px; width: 100%;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.3rem, 2.2vw, 1.9rem);
  font-weight: 700; line-height: 1.12; letter-spacing: -.005em;
  color: #FFFDF8 !important;
}
.wk-xp h2 .wk-xp-accent { color: #E8A15B; font-style: italic; }
.wk-xp h2::after {
  content: ""; display: block; width: 100%; height: 2px; margin-top: 14px;
  background: linear-gradient(90deg, #E8A15B, rgba(232,161,91,0));
}
.wk-xp .wk-xp-copy p {
  margin: 0 0 10px; font-size: .95rem; line-height: 1.7;
  color: rgba(255,253,248,.88); text-align: justify; hyphens: manual;
}
.wk-xp .wk-xp-copy p.wk-xp-note {          /* 2nd & 3rd paragraphs get a gold rule */
  padding-left: 14px; border-left: 3px solid #C89B3C;
}
.wk-xp .wk-xp-btn {
  display: inline-block; padding: 11px 26px; font-size: .9rem; font-weight: 700;
  background: #E8A15B; color: #173B2A; border: 2px solid #E8A15B; border-radius: 999px;
  text-decoration: none;
  transition: background .25s, color .25s, border-color .25s, transform .25s;
}
.wk-xp .wk-xp-btn:hover { background: #FFFDF8; border-color: #FFFDF8; color: #173B2A; transform: translateY(-2px); }

/* ---- copy: entrance animation + small polish ---- */
.wk-xp .wk-xp-btn { position: relative; overflow: hidden; }
.wk-xp .wk-xp-btn::after {         /* light sweep on hover */
  content: ""; position: absolute; top: 0; bottom: 0; left: -60%; width: 40%;
  background: linear-gradient(105deg, transparent, rgba(255,255,255,.55), transparent);
  transform: skewX(-20deg); transition: left .6s;
}
.wk-xp .wk-xp-btn:hover::after { left: 130%; }
.wk-xp-cta { display: block; }

.wk-xp.wk-js [data-xp] {
  opacity: 0; transform: translateY(18px);
  transition: opacity .7s ease, transform .7s cubic-bezier(.2,.7,.2,1);
  transition-delay: calc(var(--i, 0) * 90ms);
}
.wk-xp.wk-js.is-in [data-xp] { opacity: 1; transform: none; }
.wk-xp.wk-js h2::after { transform: scaleX(0); transform-origin: left; transition: transform 1.1s .35s cubic-bezier(.2,.7,.2,1); }
.wk-xp.wk-js.is-in h2::after { transform: scaleX(1); }

/* ---- visual (right): leaf-cut frame + offset gold frame + fireflies ---- */
.wk-xp-visual { position: relative; padding: 0 24px 34px 30px; }
.wk-xp-visual > * { translate: calc(var(--px, 0) * var(--d, 6) * 1px) calc(var(--py, 0) * var(--d, 6) * 1px); }

.wk-xp-visual::before {            /* offset gold frame (up-right) */
  content: ""; position: absolute; z-index: 0;
  top: -14px; left: 46px; right: 10px; bottom: 48px;
  border: 1.5px solid #C89B3C; border-radius: 150px 10px 150px 10px;
  translate: calc(var(--px, 0) * -10px) calc(var(--py, 0) * -10px);
  transition: top .5s, right .5s, left .5s, bottom .5s;
}
.wk-xp-visual:hover::before { top: -20px; right: 4px; left: 52px; bottom: 54px; }

.wk-xp-main {
  --d: 8;
  position: relative; z-index: 2; width: 100%; aspect-ratio: 10 / 11; overflow: hidden;
  border-radius: 150px 10px 150px 10px;         /* leaf cut */
  background: linear-gradient(160deg, #2E5B44 0%, #0B2F22 100%);
  box-shadow: 0 22px 40px rgba(0,0,0,.4);
}
.wk-xp-main img, .wk-xp-sub img {
  position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center;
}
.wk-xp-main img { animation: wkXpKen 18s ease-in-out infinite alternate; transform-origin: 60% 40%; }
@keyframes wkXpKen { from { transform: scale(1.02); } to { transform: scale(1.12) translate(-1.5%, -1%); } }
.wk-xp-mat {                       /* thin inner "mat" line */
  position: absolute; inset: 12px; z-index: 3; pointer-events: none;
  border: 1px solid rgba(255,253,248,.4); border-radius: 140px 4px 140px 4px;
}
.wk-xp-main::after {               /* slow light sweep */
  content: ""; position: absolute; inset: 0; z-index: 4; pointer-events: none;
  background: linear-gradient(105deg, transparent 38%, rgba(255,253,248,.2) 50%, transparent 62%);
  transform: translateX(-130%); animation: wkXpShine 8s 2.5s ease-in-out infinite;
}
@keyframes wkXpShine { 0% { transform: translateX(-130%); } 40%, 100% { transform: translateX(130%); } }

.wk-xp-empty {
  position: absolute; inset: 0; display: flex; flex-direction: column;
  align-items: center; justify-content: center; gap: 6px; text-align: center; color: #FFFDF8;
}
.wk-xp-empty b { font-size: 2.2rem; font-weight: 400; line-height: 1; }
.wk-xp .wk-xp-empty span { font-weight: 700; letter-spacing: .06em; color: #FFFDF8; }
.wk-xp-empty small { opacity: .75; }

.wk-xp-sub {                       /* second image, opposite leaf cut, overlaps bottom-left */
  --d: 14;
  position: absolute; z-index: 4; left: 0; bottom: 0;
  width: 38%; aspect-ratio: 1 / 1; overflow: hidden;
  border-radius: 10px 58px 10px 58px;
  background: linear-gradient(160deg, #2E5B44 0%, #0B2F22 100%);
  border: 5px solid #173B2A;
  box-shadow: 0 0 0 2px #C89B3C, 0 16px 28px rgba(0,0,0,.45);
  animation: wkXpBob 6s ease-in-out infinite;
}
.wk-xp-sub img { transition: transform .8s cubic-bezier(.2,.7,.2,1); }
.wk-xp-sub:hover img { transform: scale(1.12); }
.wk-xp-sub .wk-xp-empty b { font-size: 1.6rem; }
.wk-xp-sub .wk-xp-empty small { font-size: .62rem; }
@keyframes wkXpBob { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-7px); } }

.wk-xp-tag {                       /* glass label */
  --d: 18;
  position: absolute; z-index: 5; right: 0; bottom: 15%;
  display: flex; align-items: center; gap: 10px;
  padding: 9px 16px 9px 10px; border-radius: 999px;
  background: rgba(11,47,34,.72); -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px);
  border: 1px solid rgba(232,161,91,.75); color: #FFFDF8;
  box-shadow: 0 12px 24px rgba(0,0,0,.35);
  animation: wkXpFloat 4.5s ease-in-out infinite;
}
.wk-xp-tag i {
  font-style: normal; width: 30px; height: 30px; border-radius: 50%; display: grid; place-items: center;
  background: #E8A15B; font-size: .95rem; animation: wkXpPulse 2s ease-out infinite;
}
.wk-xp-tag b { display: block; font-size: .8rem; letter-spacing: .04em; line-height: 1.2; }
.wk-xp-tag small { display: block; font-size: .62rem; letter-spacing: .18em; text-transform: uppercase; color: #E8A15B; }
@keyframes wkXpFloat { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
@keyframes wkXpPulse { 0% { box-shadow: 0 0 0 0 rgba(232,161,91,.65); } 100% { box-shadow: 0 0 0 12px rgba(232,161,91,0); } }

.wk-xp-vert {                      /* vertical caption, top-left */
  --d: 4;
  position: absolute; z-index: 1; left: 2px; top: 2%; display: flex; flex-direction: column; align-items: center; gap: 10px;
  writing-mode: vertical-rl; transform: rotate(180deg);
  font-size: .62rem; font-weight: 700; letter-spacing: .38em; color: #E8A15B; white-space: nowrap;
}
.wk-xp-vert::after { content: ""; width: 1px; height: 60px; background: linear-gradient(#E8A15B, transparent); }

.wk-xp-flies { position: absolute; inset: 0; z-index: 6; pointer-events: none; }
.wk-xp-flies span {
  position: absolute; width: 5px; height: 5px; border-radius: 50%;
  background: #F6D9A8; box-shadow: 0 0 10px 3px rgba(232,161,91,.75);
  opacity: 0; animation: wkXpFly 9s ease-in-out infinite;
}
.wk-xp-flies span:nth-child(1) { left: 22%; bottom: 30%; animation-delay: 0s; }
.wk-xp-flies span:nth-child(2) { left: 48%; bottom: 20%; animation-delay: 2s;  animation-duration: 11s; }
.wk-xp-flies span:nth-child(3) { left: 72%; bottom: 40%; animation-delay: 4s;  animation-duration: 10s; }
.wk-xp-flies span:nth-child(4) { left: 86%; bottom: 18%; animation-delay: 6s; }
.wk-xp-flies span:nth-child(5) { left: 36%; bottom: 55%; animation-delay: 1s;  animation-duration: 12s; }
@keyframes wkXpFly {
  0%   { opacity: 0; transform: translate(0, 0); }
  20%  { opacity: 1; }
  50%  { transform: translate(14px, -70px); }
  80%  { opacity: .8; }
  100% { opacity: 0; transform: translate(-10px, -150px); }
}

/* entrance for the visual */
.wk-xp.wk-js .wk-xp-main, .wk-xp.wk-js .wk-xp-sub,
.wk-xp.wk-js .wk-xp-tag,  .wk-xp.wk-js .wk-xp-vert { opacity: 0; }
.wk-xp.wk-js.is-in .wk-xp-main { opacity: 1; animation: wkXpRise 1.2s .1s cubic-bezier(.2,.7,.2,1) backwards; }
.wk-xp.wk-js.is-in .wk-xp-sub  { opacity: 1; animation: wkXpPop .8s .9s cubic-bezier(.3,1.3,.5,1) backwards, wkXpBob 6s 1.7s ease-in-out infinite; }
.wk-xp.wk-js.is-in .wk-xp-tag  { opacity: 1; animation: wkXpPop .8s 1.2s cubic-bezier(.3,1.3,.5,1) backwards, wkXpFloat 4.5s 2s ease-in-out infinite; }
.wk-xp.wk-js.is-in .wk-xp-vert { opacity: 1; transition: opacity 1s 1.4s; }
@keyframes wkXpRise { from { clip-path: inset(100% 0 0 0); } to { clip-path: inset(-40px -40px -40px -40px); } }
@keyframes wkXpPop  { from { opacity: 0; transform: scale(.5); } to { opacity: 1; transform: scale(1); } }

@media (prefers-reduced-motion: reduce) {
  .wk-xp *, .wk-xp *::before, .wk-xp *::after { animation: none !important; transition: none !important; }
  .wk-xp.wk-js [data-xp], .wk-xp.wk-js .wk-xp-main, .wk-xp.wk-js .wk-xp-sub,
  .wk-xp.wk-js .wk-xp-tag, .wk-xp.wk-js .wk-xp-vert { opacity: 1; transform: none; }
  .wk-xp.wk-js h2::after { transform: none; }
  .wk-xp-flies { display: none; }
}

@media (max-width: 900px) {
  .wk-xp .wk-xp-grid.container { grid-template-columns: minmax(0, 1fr); }
  .wk-xp-visual { max-width: 400px; width: 100%; margin: 0 auto; }
}
@media (max-width: 480px) {
  .wk-xp .wk-xp-btn { display: block; text-align: center; }
  .wk-xp-visual { padding: 0 14px 28px 24px; }
  .wk-xp-visual::before { left: 38px; }
  .wk-xp-vert { display: none; }
}

/* =====================================================
   SECTION 5 — Wildlife Conservation & Events ("wk-cv")
   bg = same Soft White as Section 1 (hero). Compact, small side spacing.
   It is a <div> (not <section>) so the white/orange alternation is untouched.
   Layout: header, then an endlessly auto-scrolling carousel of leaf-cut cards
   with round arrows fixed on the left / right, vertically centered.
   ===================================================== */
.section.wk-cv.wk-cv {
  background: #FFFDF8 !important;
  position: relative; overflow: hidden;
  padding: clamp(32px, 4.5vw, 56px) 0;
  font-family: 'Lato', 'Segoe UI', sans-serif;
}
.wk-cv, .wk-cv * { box-sizing: border-box; }
.wk-cv .wk-cv-wrap.container {
  position: relative; z-index: 1; max-width: 100%;
  padding-left: clamp(16px, 2.5vw, 36px);
  padding-right: clamp(16px, 2.5vw, 36px);
}

/* ---- header ---- */
.wk-cv-head { margin-bottom: 14px; text-align: center; }
.wk-cv .wk-eyebrow::after { content: ""; width: 36px; height: 2px; background: #C89B3C; }   /* mirrors the left line */
.wk-cv h2 {
  margin: 0 0 14px; width: 100%;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.3rem, 2.2vw, 1.9rem); font-weight: 700; line-height: 1.12; letter-spacing: -.005em;
  color: #173B2A;
}
.wk-cv h2 .wk-cv-accent { color: #E8A15B; font-style: italic; }
.wk-cv h2::after {
  content: ""; display: block; width: 100%; height: 2px; margin-top: 14px;
  background: linear-gradient(90deg, rgba(200,155,60,0), #C89B3C 50%, rgba(200,155,60,0));
}
.wk-cv .wk-cv-intro {
  width: 100%; max-width: none; margin: 0; font-size: .95rem; line-height: 1.7; color: #2B241D; text-align: center;
}

/* ---- carousel stage: view (clips) + arrows on left / right ---- */
.wk-cv-stage { position: relative; }
.wk-cv-view {
  --gap: 18px; --vis: 4;
  overflow: hidden; padding: 10px 0 28px; margin: 0 0 -6px;
  touch-action: pan-y; cursor: grab;
  -webkit-mask-image: linear-gradient(90deg, transparent 0, #000 18px, #000 calc(100% - 18px), transparent 100%);
          mask-image: linear-gradient(90deg, transparent 0, #000 18px, #000 calc(100% - 18px), transparent 100%);
}
.wk-cv-view.is-drag { cursor: grabbing; user-select: none; -webkit-user-select: none; }
.wk-cv-track { display: flex; gap: var(--gap); width: 100%; will-change: transform; }
@media (max-width: 1399px) { .wk-cv-view { --vis: 3; } }
@media (max-width: 999px)  { .wk-cv-view { --vis: 2; } }
@media (max-width: 639px)  { .wk-cv-view { --vis: 1; --gap: 14px; } }

.wk-cv-arrow {
  position: absolute; z-index: 5; top: calc(50% - 9px); transform: translateY(-50%);
  width: 48px; height: 48px; border-radius: 50%; padding: 0; cursor: pointer;
  display: grid; place-items: center;
  background: #FFFDF8; color: #173B2A; border: 2px solid #C89B3C;
  box-shadow: 0 10px 22px rgba(23,59,42,.28);
  transition: background .25s, color .25s, border-color .25s, box-shadow .25s, scale .25s;
}
.wk-cv-arrow.is-prev { left: -8px; }
.wk-cv-arrow.is-next { right: -8px; }
.wk-cv-arrow svg { width: 18px; height: 18px; fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round; stroke-linejoin: round; }
.wk-cv-arrow:hover { background: #173B2A; color: #E8A15B; border-color: #E8A15B; scale: 1.08; box-shadow: 0 14px 26px rgba(23,59,42,.4); }
.wk-cv-arrow:active { scale: .94; }
.wk-cv-arrow:focus-visible { outline: 3px solid #E8A15B; outline-offset: 3px; }
@media (max-width: 639px) { .wk-cv-arrow { width: 40px; height: 40px; } .wk-cv-arrow.is-prev { left: -6px; } .wk-cv-arrow.is-next { right: -6px; } }

.wk-cv-card {
  --mx: 50%; --my: 0%;
  position: relative; flex: 0 0 calc((100% - (var(--vis) - 1) * var(--gap)) / var(--vis));
  display: flex; flex-direction: column;
  padding: 26px 24px 24px 28px;
  border-radius: 56px 10px 56px 10px;                 /* leaf cut, same language as the hero */
  background: linear-gradient(160deg, #1F4A36 0%, #173B2A 55%, #0F3024 100%);
  border: 1px solid rgba(200,155,60,.5);
  box-shadow: 0 12px 26px rgba(23,59,42,.22);
  overflow: hidden; color: #FFFDF8;
  transition: transform .35s cubic-bezier(.2,.7,.2,1), box-shadow .35s, border-color .35s;
}
.wk-cv-card::before {              /* glow that follows the pointer */
  content: ""; position: absolute; inset: 0; pointer-events: none; opacity: 0; transition: opacity .35s;
  background: radial-gradient(260px circle at var(--mx) var(--my), rgba(232,161,91,.22), transparent 65%);
}
.wk-cv-card::after {               /* gold line that draws along the bottom */
  content: ""; position: absolute; left: 0; right: 0; bottom: 0; height: 3px;
  background: linear-gradient(90deg, #E8A15B, #C89B3C);
  transform: scaleX(0); transform-origin: left; transition: transform .5s cubic-bezier(.2,.7,.2,1);
}
.wk-cv-card:hover { transform: translateY(-6px); border-color: #E8A15B; box-shadow: 0 20px 38px rgba(23,59,42,.34); }
.wk-cv-card:hover::before { opacity: 1; }
.wk-cv-card:hover::after { transform: scaleX(1); }

.wk-cv-top { position: relative; display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
.wk-cv-ico {
  width: 46px; height: 46px; border-radius: 50%; display: grid; place-items: center;
  background: rgba(232,161,91,.14); border: 1px solid rgba(232,161,91,.7); color: #E8A15B;
  transition: background .3s, color .3s, transform .5s;
}
.wk-cv-ico svg { width: 22px; height: 22px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
.wk-cv-card:hover .wk-cv-ico { background: #E8A15B; color: #173B2A; transform: rotate(-8deg) scale(1.06); }
.wk-cv-num {
  font-family: 'Playfair Display', Georgia, serif; font-size: 2.3rem; font-weight: 700; line-height: 1;
  color: transparent; -webkit-text-stroke: 1px rgba(232,161,91,.6);
}
.wk-cv-tag {
  position: relative; margin: 0 0 6px; font-size: .68rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #E8A15B;
}
.wk-cv-card h3 {
  position: relative; margin: 0 0 10px;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: 1.2rem; font-weight: 700; line-height: 1.2; color: #FFFDF8;
}
.wk-cv-card p.wk-cv-txt {
  position: relative; margin: 0 0 18px; font-size: .9rem; line-height: 1.65;
  color: rgba(255,253,248,.85); text-align: justify; hyphens: manual;
}
.wk-cv-card .wk-cv-link {
  position: relative; align-self: flex-start; margin-top: auto;
  display: inline-flex; align-items: center; gap: 8px; padding: 9px 20px;
  font-size: .82rem; font-weight: 700; text-decoration: none;
  background: #E8A15B; color: #173B2A; border: 2px solid #E8A15B; border-radius: 999px;
  transition: background .25s, border-color .25s, gap .25s;
}
.wk-cv-card .wk-cv-link:hover, .wk-cv-card .wk-cv-link:focus-visible { background: #FFFDF8; border-color: #FFFDF8; gap: 13px; }

/* ---- loop progress bar ---- */
.wk-cv-bar { height: 3px; border-radius: 3px; background: rgba(23,59,42,.14); overflow: hidden; }
.wk-cv-bar i { display: block; height: 100%; width: 100%; transform: scaleX(0); transform-origin: left; background: linear-gradient(90deg, #C89B3C, #E8A15B); }

/* ---- entrance ---- */
.wk-cv.wk-js [data-cv] { opacity: 0; transform: translateY(18px); transition: opacity .7s ease, transform .7s cubic-bezier(.2,.7,.2,1); transition-delay: calc(var(--i, 0) * 90ms); }
.wk-cv.wk-js.is-in [data-cv] { opacity: 1; transform: none; }
.wk-cv.wk-js h2::after { transform: scaleX(0); transform-origin: center; transition: transform 1.1s .35s cubic-bezier(.2,.7,.2,1); }
.wk-cv.wk-js.is-in h2::after { transform: scaleX(1); }
.wk-cv.wk-js .wk-cv-card { opacity: 0; }
.wk-cv.wk-js.is-in .wk-cv-card { opacity: 1; animation: wkCvIn .8s calc(var(--i, 0) * 110ms + .3s) cubic-bezier(.2,.7,.2,1) backwards; }
@keyframes wkCvIn { from { opacity: 0; transform: translateY(30px) scale(.96); } to { opacity: 1; transform: none; } }

@media (prefers-reduced-motion: reduce) {
  .wk-cv *, .wk-cv *::before, .wk-cv *::after { animation: none !important; transition: none !important; }
  .wk-cv.wk-js [data-cv], .wk-cv.wk-js .wk-cv-card { opacity: 1; transform: none; }
  .wk-cv.wk-js h2::after { transform: none; }
}

/* =====================================================
   SECTION 6 — FAQ ("wk-fq")
   bg #173B2A (same green as section 2), compact, small side spacing.
   It is a <div> (not <section>) so the white/orange alternation is untouched.
   Left: a "visitor pass" ticket (photo + perforated stub + rotating stamp)
   Right: heading, intro and an editorial accordion
   ===================================================== */
.section.wk-fq.wk-fq {
  background: #173B2A !important;
  position: relative; overflow: hidden;
  padding: clamp(32px, 4.5vw, 56px) 0;
  font-family: 'Lato', 'Segoe UI', sans-serif;
}
.wk-fq, .wk-fq * { box-sizing: border-box; }
.wk-fq .wk-fq-wrap.container {
  position: relative; z-index: 1; max-width: 100%;
  padding-left: clamp(16px, 2.5vw, 36px);
  padding-right: clamp(16px, 2.5vw, 36px);
}
.wk-fq-head { text-align: center; margin-bottom: 22px; }
.wk-fq .wk-eyebrow::after { content: ""; width: 36px; height: 2px; background: #C89B3C; }   /* mirrors the left line */
.wk-fq-grid {
  display: grid; grid-template-columns: clamp(300px, 31vw, 410px) minmax(0, 1fr);
  gap: clamp(28px, 3.4vw, 52px);
  align-items: stretch;
}

/* ---- ticket (left) ---- */
.wk-fq-visual { position: relative; align-self: stretch; min-height: 0; --stub: 140px; }   /* height = FAQ list height, so both end on the same line */
.wk-fq-shadow {                  /* thin gold outline + soft shadow that follow the notched shape */
  position: absolute; inset: 0;
  filter: drop-shadow(1.5px 0 0 #C89B3C) drop-shadow(-1.5px 0 0 #C89B3C)
          drop-shadow(0 1.5px 0 #C89B3C) drop-shadow(0 -1.5px 0 #C89B3C)
          drop-shadow(0 22px 26px rgba(0,0,0,.4));
}
.wk-fq-ticket {
  position: absolute; inset: 0; overflow: hidden; border-radius: 24px;
  background: #0B2F22;
  -webkit-mask:
    radial-gradient(circle 15px at 0 calc(100% - var(--stub)), transparent 98%, #000) left  / 51% 100% no-repeat,
    radial-gradient(circle 15px at 100% calc(100% - var(--stub)), transparent 98%, #000) right / 51% 100% no-repeat;
          mask:
    radial-gradient(circle 15px at 0 calc(100% - var(--stub)), transparent 98%, #000) left  / 51% 100% no-repeat,
    radial-gradient(circle 15px at 100% calc(100% - var(--stub)), transparent 98%, #000) right / 51% 100% no-repeat;
}
.wk-fq-photo {
  position: absolute; left: 0; right: 0; top: 0; bottom: var(--stub); overflow: hidden;
  background: linear-gradient(160deg, #2E5B44 0%, #0B2F22 100%);
}
.wk-fq-photo img {
  position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center;
  animation: wkFqKen 18s ease-in-out infinite alternate; transform-origin: 50% 45%;
}
@keyframes wkFqKen { from { transform: scale(1.02); } to { transform: scale(1.12) translate(1.5%, -1%); } }
.wk-fq-photo::before {           /* soft fade into the stub */
  content: ""; position: absolute; left: 0; right: 0; bottom: 0; height: 30%; z-index: 2; pointer-events: none;
  background: linear-gradient(to top, rgba(11,47,34,.75), transparent);
}
.wk-fq-photo::after {            /* slow light sweep */
  content: ""; position: absolute; inset: 0; z-index: 3; pointer-events: none;
  background: linear-gradient(105deg, transparent 38%, rgba(255,253,248,.2) 50%, transparent 62%);
  transform: translateX(-130%); animation: wkFqShine 8s 2.5s ease-in-out infinite;
}
@keyframes wkFqShine { 0% { transform: translateX(-130%); } 40%, 100% { transform: translateX(130%); } }
.wk-fq-empty {
  position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center;
  gap: 6px; text-align: center; color: #FFFDF8;
}
.wk-fq-empty b { font-size: 2.4rem; font-weight: 400; line-height: 1; }
.wk-fq .wk-fq-empty span { font-weight: 700; letter-spacing: .06em; color: #FFFDF8; }
.wk-fq-empty small { opacity: .75; }
.wk-fq-label {
  position: absolute; z-index: 4; left: 18px; top: 18px;
  display: flex; align-items: center; gap: 8px; padding: 7px 14px; border-radius: 999px;
  background: rgba(11,47,34,.7); -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px);
  border: 1px solid rgba(232,161,91,.7); color: #FFFDF8;
  font-size: .66rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase;
}
.wk-fq-label::before { content: ""; width: 7px; height: 7px; border-radius: 50%; background: #E8A15B; animation: wkFqPulse 1.8s ease-out infinite; }
@keyframes wkFqPulse { 0% { box-shadow: 0 0 0 0 rgba(232,161,91,.7); } 100% { box-shadow: 0 0 0 9px rgba(232,161,91,0); } }

.wk-fq-stub {
  position: absolute; left: 0; right: 0; bottom: 0; height: var(--stub);
  padding: 18px 24px 16px; display: flex; flex-direction: column; justify-content: center; align-items: flex-start; gap: 3px;
  background: linear-gradient(180deg, #123427 0%, #0B2F22 100%); color: #FFFDF8;
}
.wk-fq-stub::before {            /* perforation line */
  content: ""; position: absolute; left: 24px; right: 24px; top: 0; height: 2px;
  background: repeating-linear-gradient(90deg, #E8A15B 0 7px, transparent 7px 14px);
}
.wk-fq-stub small { font-size: .64rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #E8A15B; }
.wk-fq-stub b { margin-bottom: 9px; font-size: .88rem; line-height: 1.35; font-weight: 700; }
.wk-fq-stub a {
  display: inline-flex; align-items: center; gap: 8px; padding: 7px 18px;
  font-size: .8rem; font-weight: 700; text-decoration: none;
  background: #E8A15B; color: #173B2A; border: 2px solid #E8A15B; border-radius: 999px;
  transition: background .25s, border-color .25s, gap .25s;
}
.wk-fq-stub a:hover { background: #FFFDF8; border-color: #FFFDF8; gap: 13px; }

.wk-fq-stamp {                   /* rotating postmark on the ticket corner */
  position: absolute; z-index: 6; top: -18px; right: -14px; width: 92px; height: 92px; border-radius: 50%;
  background: #173B2A; border: 1.5px solid #C89B3C; box-shadow: 0 10px 22px rgba(0,0,0,.4);
  display: grid; place-items: center;
}
.wk-fq-stamp svg { position: absolute; inset: 0; width: 100%; height: 100%; animation: wkFqSpin 20s linear infinite; }
.wk-fq-stamp text { font: 700 8.6px 'Lato', sans-serif; fill: #E8A15B; }
.wk-fq-stamp i { font-style: normal; font-size: 1.35rem; line-height: 1; }
@keyframes wkFqSpin { to { transform: rotate(360deg); } }

/* ---- copy + accordion (right) ---- */
.wk-fq .wk-eyebrow { color: #E8A15B; }
.wk-fq h2 {
  margin: 0 0 14px; width: 100%;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.3rem, 2.2vw, 1.9rem); font-weight: 700; line-height: 1.12; letter-spacing: -.005em;
  color: #FFFDF8 !important;
}
.wk-fq h2 .wk-fq-accent { color: #E8A15B; font-style: italic; }
.wk-fq h2::after {
  content: ""; display: block; width: 100%; height: 2px; margin-top: 14px;
  background: linear-gradient(90deg, rgba(232,161,91,0), #E8A15B 50%, rgba(232,161,91,0));
}
.wk-fq .wk-fq-intro { width: 100%; max-width: none; margin: 0; font-size: .95rem; line-height: 1.7; color: rgba(255,253,248,.88); text-align: center; }

.wk-fq-list { border-top: 1px solid rgba(200,155,60,.35); }
.wk-fq-item { position: relative; border-bottom: 1px solid rgba(200,155,60,.35); }
.wk-fq-item::before {            /* soft sweep behind the row */
  content: ""; position: absolute; inset: 0; pointer-events: none; opacity: 0; transition: opacity .3s;
  background: linear-gradient(90deg, rgba(232,161,91,.13), transparent 75%);
}
.wk-fq-item::after {             /* orange line that draws under an open row */
  content: ""; position: absolute; left: 0; right: 0; bottom: -1px; height: 2px;
  background: linear-gradient(90deg, #E8A15B, rgba(232,161,91,0));
  transform: scaleX(0); transform-origin: left; transition: transform .6s cubic-bezier(.2,.7,.2,1);
}
.wk-fq-item:hover::before, .wk-fq-item.is-open::before { opacity: 1; }
.wk-fq-item.is-open::after { transform: scaleX(1); }
.wk-fq-q { margin: 0; font-size: inherit; position: relative; }
.wk-fq-q button {
  width: 100%; display: flex; align-items: center; gap: 14px; padding: 11px 6px;
  background: none; border: 0; cursor: pointer; text-align: left; color: #FFFDF8; font: inherit;
}
.wk-fq-q button:focus-visible { outline: 2px solid #E8A15B; outline-offset: -3px; }
.wk-fq-n {
  flex: 0 0 auto; width: 40px; font-family: 'Playfair Display', Georgia, serif; font-size: 1.45rem; font-weight: 700; line-height: 1;
  color: transparent; -webkit-text-stroke: 1px rgba(232,161,91,.75);
  transition: color .3s, -webkit-text-stroke-color .3s, transform .4s;
}
.wk-fq-item.is-open .wk-fq-n, .wk-fq-item:hover .wk-fq-n { color: #E8A15B; }
.wk-fq-item.is-open .wk-fq-n { transform: translateX(4px); }
.wk-fq-t { flex: 1 1 auto; font-size: .98rem; font-weight: 700; line-height: 1.35; transition: color .3s; }
.wk-fq-item.is-open .wk-fq-t { color: #E8A15B; }
.wk-fq-pm {
  flex: 0 0 auto; position: relative; width: 28px; height: 28px; border-radius: 50%;
  border: 1px solid rgba(232,161,91,.7); transition: background .3s, transform .4s;
}
.wk-fq-pm::before, .wk-fq-pm::after {
  content: ""; position: absolute; left: 50%; top: 50%; background: #E8A15B; border-radius: 2px;
  transition: transform .35s cubic-bezier(.2,.7,.2,1), background .3s;
}
.wk-fq-pm::before { width: 12px; height: 2px; margin: -1px 0 0 -6px; }
.wk-fq-pm::after  { width: 2px; height: 12px; margin: -6px 0 0 -1px; }
.wk-fq-item.is-open .wk-fq-pm { background: #E8A15B; transform: rotate(180deg); }
.wk-fq-item.is-open .wk-fq-pm::before { background: #173B2A; }
.wk-fq-item.is-open .wk-fq-pm::after  { background: #173B2A; transform: rotate(90deg) scaleX(0); }

.wk-fq-a { display: grid; grid-template-rows: 1fr; transition: grid-template-rows .45s cubic-bezier(.2,.7,.2,1); }
.wk-fq-a > div { overflow: hidden; min-height: 0; }
.wk-fq-a p {
  position: relative; margin: 0; padding: 0 46px 14px 60px; font-size: .9rem; line-height: 1.65;
  color: rgba(255,253,248,.86); text-align: justify; hyphens: manual;
}
.wk-fq-a p a { color: #E8A15B; font-weight: 700; text-decoration: underline; text-underline-offset: 3px; }
.wk-fq-a p a:hover { color: #FFFDF8; }
.wk-fq.wk-js .wk-fq-item:not(.is-open) .wk-fq-a { grid-template-rows: 0fr; }
.wk-fq.wk-js .wk-fq-item:not(.is-open) .wk-fq-a p { visibility: hidden; transition: visibility 0s .45s; }

/* ---- entrance ---- */
.wk-fq.wk-js [data-fq] { opacity: 0; transform: translateY(18px); transition: opacity .7s ease, transform .7s cubic-bezier(.2,.7,.2,1); transition-delay: calc(var(--i, 0) * 90ms); }
.wk-fq.wk-js.is-in [data-fq] { opacity: 1; transform: none; }
.wk-fq.wk-js h2::after { transform: scaleX(0); transform-origin: center; transition: transform 1.1s .35s cubic-bezier(.2,.7,.2,1); }
.wk-fq.wk-js.is-in h2::after { transform: scaleX(1); }
.wk-fq.wk-js .wk-fq-visual { opacity: 0; }
.wk-fq.wk-js.is-in .wk-fq-visual { opacity: 1; animation: wkFqSlide 1.1s .1s cubic-bezier(.2,.7,.2,1) backwards; }
@keyframes wkFqSlide { from { opacity: 0; transform: translateX(-46px) rotate(-3deg); } to { opacity: 1; transform: none; } }
.wk-fq.wk-js .wk-fq-stamp { opacity: 0; }
.wk-fq.wk-js.is-in .wk-fq-stamp { opacity: 1; animation: wkFqPop .8s 1.1s cubic-bezier(.3,1.4,.5,1) backwards; }
@keyframes wkFqPop { from { opacity: 0; transform: scale(.3) rotate(-90deg); } to { opacity: 1; transform: none; } }
.wk-fq.wk-js .wk-fq-item { opacity: 0; }
.wk-fq.wk-js.is-in .wk-fq-item { opacity: 1; animation: wkFqRow .7s calc(var(--i, 0) * 80ms + .45s) cubic-bezier(.2,.7,.2,1) backwards; }
@keyframes wkFqRow { from { opacity: 0; transform: translateX(34px); } to { opacity: 1; transform: none; } }

@media (prefers-reduced-motion: reduce) {
  .wk-fq *, .wk-fq *::before, .wk-fq *::after { animation: none !important; transition: none !important; }
  .wk-fq.wk-js [data-fq], .wk-fq.wk-js .wk-fq-visual, .wk-fq.wk-js .wk-fq-item, .wk-fq.wk-js .wk-fq-stamp { opacity: 1; transform: none; }
  .wk-fq.wk-js h2::after { transform: none; }
}
@media (max-width: 900px) {
  .wk-fq-grid { grid-template-columns: minmax(0, 1fr); }
  .wk-fq-visual { min-height: 0; height: auto; aspect-ratio: 4 / 5; max-width: 420px; width: 100%; margin: 0 auto; }
}
@media (max-width: 480px) {
  .wk-fq-q button { gap: 10px; padding: 11px 2px; }
  .wk-fq-n { width: 32px; font-size: 1.2rem; }
  .wk-fq-a p { padding: 0 8px 14px 44px; }
  .wk-fq-stamp { width: 76px; height: 76px; right: -6px; }
}
</style>

<!-- ============ TOP BANNER (image only) ============ -->
<div class="wk-banner">
  <!-- Fallback illustration: shown only until the banner image exists -->
  <svg class="wk-banner-art" viewBox="0 0 1440 600" preserveAspectRatio="xMidYMax slice" role="img" aria-label="Savanna at sunset with acacia trees, an elephant and a giraffe">
    <defs>
      <linearGradient id="wkBnSky" x1="0" y1="0" x2="0" y2="1">
        <stop offset="0" stop-color="#0B2F22"/>
        <stop offset=".55" stop-color="#173B2A"/>
        <stop offset="1" stop-color="#7A5A2A"/>
      </linearGradient>
      <radialGradient id="wkBnGlow" cx="1130" cy="400" r="520" gradientUnits="userSpaceOnUse">
        <stop offset="0" stop-color="#E8A15B" stop-opacity=".75"/>
        <stop offset=".5" stop-color="#E8A15B" stop-opacity=".22"/>
        <stop offset="1" stop-color="#E8A15B" stop-opacity="0"/>
      </radialGradient>
      <g id="wkAcacia">
        <path d="M172 410 Q166 340 184 285" stroke="currentColor" stroke-width="8" fill="none" stroke-linecap="round"/>
        <path d="M184 288 Q150 274 118 280 M184 288 Q220 270 252 274" stroke="currentColor" stroke-width="5" fill="none" stroke-linecap="round"/>
        <ellipse cx="184" cy="272" rx="88" ry="15" fill="currentColor"/>
        <ellipse cx="128" cy="280" rx="42" ry="10" fill="currentColor"/>
        <ellipse cx="240" cy="278" rx="34" ry="9" fill="currentColor"/>
      </g>
      <g id="wkGiraffe" fill="currentColor">
        <path d="M283 340 L306 340 L302 218 L286 221 Z"/>
        <ellipse cx="265" cy="348" rx="50" ry="25"/>
        <ellipse cx="300" cy="211" rx="18" ry="9" transform="rotate(-14 300 211)"/>
        <rect x="232" y="356" width="8" height="54" rx="3"/>
        <rect x="246" y="358" width="8" height="52" rx="3"/>
        <rect x="282" y="358" width="8" height="52" rx="3"/>
        <rect x="296" y="356" width="8" height="54" rx="3"/>
        <path d="M217 338 Q206 358 211 380" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round"/>
        <path d="M292 199 l-2 -11 M299 197 l1 -11" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
      </g>
      <g id="wkElephant" fill="currentColor">
        <ellipse cx="112" cy="362" rx="54" ry="33"/>
        <circle cx="66" cy="350" r="27"/>
        <ellipse cx="78" cy="354" rx="15" ry="24"/>
        <rect x="76" y="376" width="15" height="34" rx="5"/>
        <rect x="97" y="380" width="15" height="30" rx="5"/>
        <rect x="126" y="380" width="15" height="30" rx="5"/>
        <rect x="145" y="376" width="15" height="34" rx="5"/>
        <path d="M46 358 Q30 388 43 408" stroke="currentColor" stroke-width="10" fill="none" stroke-linecap="round"/>
        <path d="M165 352 Q176 362 170 378" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round"/>
      </g>
    </defs>
    <rect width="1440" height="600" fill="url(#wkBnSky)"/>
    <rect width="1440" height="600" fill="url(#wkBnGlow)"/>
    <circle cx="1130" cy="390" r="118" fill="#FFFDF8" opacity=".10"/>
    <circle cx="1130" cy="390" r="84" fill="#C89B3C"/>
    <g fill="none" stroke="#FFFDF8" stroke-opacity=".55" stroke-width="2.5" stroke-linecap="round">
      <path d="M820 170 q10 -11 20 0 q10 -11 20 0"/>
      <path d="M900 130 q8 -9 16 0 q8 -9 16 0"/>
      <path d="M1290 190 q8 -9 16 0 q8 -9 16 0"/>
    </g>
    <!-- far, mid hills -->
    <path d="M0 470 Q180 400 380 450 T760 440 T1120 455 T1440 430 L1440 600 L0 600Z" fill="#2E5B44" opacity=".75"/>
    <path d="M0 505 Q220 455 460 495 T900 490 T1440 480 L1440 600 L0 600Z" fill="#1F4A35"/>
    <!-- silhouettes -->
    <use href="#wkAcacia" style="color:#0B2F22" transform="translate(-60 -135) scale(1.5)"/>
    <use href="#wkElephant" style="color:#0B2F22" transform="translate(520 40) scale(1.05)"/>
    <use href="#wkGiraffe" style="color:#0B2F22" transform="translate(760 -58) scale(1.32)"/>
    <use href="#wkAcacia" style="color:#0B2F22" transform="translate(1180 96) scale(.9)"/>
    <!-- foreground -->
    <path d="M0 540 Q240 505 520 530 T1040 525 T1440 515 L1440 600 L0 600Z" fill="#0B2F22"/>
  </svg>
  <!-- Real photo: save your image as assets/images/static/panoramic-zoo-scene-with-wild-animals.webp -->
  <img class="wk-banner-photo" src="../assets/images/static/panoramic-zoo-scene-with-wild-animals.webp" alt="Wildlife Kingdom zoo" onerror="this.style.display='none'">
</div>

<!-- ============ HERO / SECTION 1 ============ -->
<section class="section wk-hero">
  <div class="container wk-hero-grid">

    <div class="wk-copy">
      <span class="wk-eyebrow">Welcome to Wildlife Kingdom</span>
      <h1><span class="wk-h1-black">Discover the <em>Wild.</em></span> <span class="wk-h1-accent">Experience Nature.</span></h1>
      <p class="wk-lead">Explore the fascinating world of wildlife at Wildlife Kingdom, where nature, adventure, and conservation come together. Discover diverse animals, explore natural habitats, and learn about the importance of protecting wildlife for future generations.</p>
      <div class="wk-more">
        <p>Welcome to Wildlife Kingdom, a place where the beauty of nature and the fascinating world of wildlife come together. Explore an inspiring environment where visitors can discover amazing animals, learn about their natural habitats, and experience the wonder of the wild.</p>
        <p>From majestic lions and graceful giraffes to mighty elephants and fascinating birds, Wildlife Kingdom brings you closer to the incredible diversity of wildlife. Our carefully designed habitats and informative experiences are created to help visitors understand animals, appreciate nature, and develop a deeper connection with the natural world.</p>
      </div>
      <div class="wk-actions">
        <a href="animals.php" class="btn btn-gold">Explore Wildlife</a>
        <a href="visit-us.php" class="btn btn-outline">Plan Your Visit</a>
      </div>
    </div>

    <div class="wk-visual">
      <!-- IMAGE CONTAINER: height follows the text on its left. Use a 1000 x 800 px image. -->
      <div class="wk-frame">
        <div class="wk-frame-empty" aria-hidden="true">
          <svg viewBox="0 0 64 64" fill="currentColor"><ellipse cx="32" cy="42" rx="14" ry="11"/><ellipse cx="14" cy="28" rx="6" ry="8"/><ellipse cx="26" cy="17" rx="6" ry="8"/><ellipse cx="38" cy="17" rx="6" ry="8"/><ellipse cx="50" cy="28" rx="6" ry="8"/></svg>
          <span>Your image here</span>
          <small>1000 × 800 px</small>
        </div>
        <img src="../assets/images/static/magnificent-male-lion-standing-on-rocky-outcrop.webp" alt="Male lion standing on a rocky outcrop at Wildlife Kingdom" onerror="this.style.display='none'">
        <div class="wk-stats">
          <div><strong>200+</strong><span>Species</span></div>
          <div><strong>12</strong><span>Habitats</span></div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- ============ SECTION 2 — EXPLORE WILDLIFE ============ -->
<section class="section wk-explore">
  <div class="container wk-ex-grid">

    <div class="wk-ex-tiles">
      <?php
      $animalCardFallbacks = [
          ['image' => '../assets/images/static/majestic-african-male-lion-portrait-savannah.webp', 'alt' => 'Male African lion portrait in the savannah', 'emoji' => '🦁', 'name' => 'Big Cats', 'subtitle' => 'Lions & tigers'],
          ['image' => '../assets/images/static/mother-and-baby-elephants-portrait.webp', 'alt' => 'Mother and baby elephants', 'emoji' => '🐘', 'name' => 'Elephants', 'subtitle' => 'Gentle giants'],
          ['image' => '../assets/images/static/beautiful-giraffe-neck-pattern-acacia-tree-sunset.webp', 'alt' => 'Giraffe neck pattern near an acacia tree at sunset', 'emoji' => '🦒', 'name' => 'Giraffes', 'subtitle' => 'Graceful heights'],
          ['image' => '../assets/images/static/colorful-macaw-parrot-feathers-close-up.webp', 'alt' => 'Colourful macaw parrot close-up', 'emoji' => '🦜', 'name' => 'Birds', 'subtitle' => 'Colour & song'],
      ];
      for ($i = 0; $i < 4; $i++):
          $animal = $animals[$i] ?? null;
          $fallback = $animalCardFallbacks[$i];
          $cardImage = $animal && !empty($animal['image'])
              ? '../uploads/animals/' . rawurlencode($animal['image'])
              : $fallback['image'];
          $cardAlt = $animal ? $animal['name'] : $fallback['alt'];
          $cardName = $animal ? $animal['name'] : $fallback['name'];
          $cardSubtitle = $animal
              ? ($animal['species'] ?: $animal['conservation_status'])
              : $fallback['subtitle'];
      ?>
        <a href="<?php echo $animal ? 'animal-details.php?id=' . (int)$animal['id'] : 'animals.php'; ?>" class="wk-ex-tile">
          <span class="wk-ex-emoji"><?php echo $fallback['emoji']; ?></span>
          <img class="wk-ex-img" src="<?php echo htmlspecialchars($cardImage, ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($cardAlt, ENT_QUOTES); ?>" loading="lazy" onerror="this.onerror=null;this.src='<?php echo htmlspecialchars($fallback['image'], ENT_QUOTES); ?>'">
          <strong><?php echo htmlspecialchars($cardName); ?></strong>
          <span class="wk-ex-sub"><?php echo htmlspecialchars($cardSubtitle); ?></span>
        </a>
      <?php endfor; ?>
    </div>

    <div class="wk-ex-copy">
      <span class="wk-eyebrow">Explore Wildlife</span>
      <h2>Explore the Fascinating <span class="wk-h1-accent">World of Wildlife</span></h2>
      <p>Step into the wild and discover the incredible diversity of animals at Wildlife Kingdom. From majestic big cats and powerful elephants to graceful giraffes, colourful birds, and fascinating wildlife species, our animal experiences offer visitors an opportunity to learn more about the creatures that share our planet.</p>
      <p>Explore fascinating animal stories, discover unique behaviours, and learn how different species adapt to their environments. Our wildlife-focused experiences are designed to make every visit informative, engaging, and memorable for visitors of all ages.</p>
      <p>At Wildlife Kingdom, we believe that understanding wildlife is an important step towards protecting it. As you explore our animal habitats and discover the natural world, you can gain a deeper appreciation for biodiversity, wildlife conservation, and the importance of protecting animals and their habitats for future generations.</p>
      <a href="animals.php" class="wk-ex-btn">Explore Our Animals →</a>
    </div>

  </div>
</section>

<!-- ============ SECTION 3 — EXPLORE DIVERSE WILDLIFE HABITATS ============ -->
<section class="section wk-habitats">
  <div class="container wk-hb-grid">

    <div class="wk-hb-visual">
      <!-- IMAGE: use a 1200 x 1000 px image (6:5). Right edge is torn-paper style. -->
      <div class="wk-hb-art">
      <div class="wk-hb-paper"></div>
      <div class="wk-hb-frame">
        <div class="wk-hb-empty" aria-hidden="true"><b>🌿</b><span>Your image here</span><small>1200 × 1000 px</small></div>
        <img src="../assets/images/static/giraffes-zebras-golden-light-natural-grassland.webp" alt="Giraffes and zebras in golden light on a natural grassland at Wildlife Kingdom" loading="lazy" onerror="this.style.display='none'">
      </div>
      </div>
    </div>

    <div class="wk-hb-copy">
      <span class="wk-eyebrow">Explore Our Habitats</span>
      <h2><span class="wk-hb-black">Explore Diverse</span> <span class="wk-hb-accent">Wildlife Habitats</span></h2>
      <p>Discover the natural environments that make wildlife truly fascinating at Wildlife Kingdom. From lush forests and open grasslands to peaceful wetlands and carefully designed animal habitats, explore the different environments where wildlife can thrive and adapt.</p>
      <p>Every habitat offers a unique opportunity to understand how animals interact with their surroundings. Learn about the natural behaviours, food sources, shelter, and environmental conditions that help different wildlife species survive in their habitats.</p>
      <p>At Wildlife Kingdom, our habitat experiences are designed to encourage curiosity and appreciation for the natural world. Whether you are exploring with family, friends, or as a nature enthusiast, discovering these diverse habitats can help you understand why protecting natural ecosystems is essential for wildlife and future generations.</p>
      <a href="habitats.php" class="wk-hb-btn">Explore Habitats →</a>
    </div>

  </div>
</section>

<!-- ============ SECTION 4 — WILDLIFE EXPERIENCES ============ -->
<div class="section wk-xp" role="region" aria-label="Wildlife Experiences">
  <div class="container wk-xp-grid">

    <div class="wk-xp-copy">
      <div data-xp style="--i:0"><span class="wk-eyebrow">Wildlife Experiences</span></div>
      <h2 data-xp style="--i:1">Unforgettable <span class="wk-xp-accent">Wildlife Experiences</span> for Everyone</h2>
      <p data-xp style="--i:2">Make your visit to Wildlife Kingdom more than just a day out. Discover engaging wildlife experiences designed to bring you closer to nature while helping you learn more about the incredible animals and ecosystems around us. Whether you are visiting with family, friends, or simply exploring your passion for wildlife, there is something exciting to discover.</p>
      <p class="wk-xp-note" data-xp style="--i:3">From observing fascinating animal behaviour and exploring natural-looking habitats to enjoying educational activities and memorable moments in nature, every experience is designed to make your visit enjoyable and meaningful. Take your time to explore, learn interesting facts about wildlife, and create lasting memories surrounded by the beauty of the natural world.</p>
      <p class="wk-xp-note" data-xp style="--i:4">Wildlife experiences can also inspire a deeper understanding of wildlife conservation and environmental protection. At Wildlife Kingdom, we aim to encourage curiosity, respect for animals, and greater awareness of the importance of protecting wildlife and their natural habitats for future generations.</p>
      <div class="wk-xp-cta" data-xp style="--i:5"><a href="experiences.php" class="wk-xp-btn">Discover Wildlife Experiences →</a></div>
    </div>

    <div class="wk-xp-visual">
      <div class="wk-xp-vert" aria-hidden="true">WILDLIFE KINGDOM</div>

      <!-- MAIN IMAGE (leaf-cut frame): use a 1000 x 1100 px image (10:11, portrait). -->
      <div class="wk-xp-main">
        <div class="wk-xp-empty" aria-hidden="true"><b>🐾</b><span>Your image here</span><small>1000 × 1100 px</small></div>
        <img src="../assets/images/static/zoo-safari-park-giraffe-habitat.webp" alt="Giraffe in its safari park habitat at Wildlife Kingdom" loading="lazy" onerror="this.style.display='none'">
        <div class="wk-xp-mat" aria-hidden="true"></div>
      </div>

      <!-- SECOND IMAGE (overlapping): use a 600 x 600 px image (1:1, square). -->
      <div class="wk-xp-sub">
        <div class="wk-xp-empty" aria-hidden="true"><b>🦒</b><small>600 × 600 px</small></div>
        <img src="../assets/images/static/powerful-silverback-gorilla-portrait.webp" alt="Portrait of a powerful silverback gorilla at Wildlife Kingdom" loading="lazy" onerror="this.style.display='none'">
      </div>

      <div class="wk-xp-tag"><i>🐾</i><span><small>Explore</small><b>Wildlife Experiences</b></span></div>

      <div class="wk-xp-flies" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span></div>
    </div>

  </div>
</div>
<script>
(function () {
  var sec = document.querySelector('.wk-xp');
  if (!sec) return;
  sec.classList.add('wk-js');
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* reveal when the section scrolls into view */
  if ('IntersectionObserver' in window && !reduce) {
    var io = new IntersectionObserver(function (e) {
      if (e[0].isIntersecting) { sec.classList.add('is-in'); io.disconnect(); }
    }, { threshold: 0.25 });
    io.observe(sec);
  } else { sec.classList.add('is-in'); }

  /* gentle mouse parallax on desktop */
  var vis = sec.querySelector('.wk-xp-visual');
  if (!vis || reduce || !window.matchMedia('(hover: hover)').matches) return;
  var raf = 0;
  sec.addEventListener('pointermove', function (ev) {
    if (raf) return;
    raf = requestAnimationFrame(function () {
      var r = sec.getBoundingClientRect();
      vis.style.setProperty('--px', ((ev.clientX - r.left) / r.width - 0.5).toFixed(3));
      vis.style.setProperty('--py', ((ev.clientY - r.top) / r.height - 0.5).toFixed(3));
      raf = 0;
    });
  });
  sec.addEventListener('pointerleave', function () {
    vis.style.setProperty('--px', 0); vis.style.setProperty('--py', 0);
  });
})();
</script>

<!-- ============ SECTION 5 — WILDLIFE CONSERVATION &amp; EVENTS ============ -->
<div class="section wk-cv" role="region" aria-label="Wildlife Conservation and Events">
  <div class="container wk-cv-wrap">

    <div class="wk-cv-head">
      <div data-cv style="--i:0"><span class="wk-eyebrow">Conservation &amp; Events</span></div>
      <h2 data-cv style="--i:1">Discover, <span class="wk-cv-accent">Learn &amp; Protect</span> Wildlife</h2>
      <p class="wk-cv-intro" data-cv style="--i:2">Explore the latest wildlife initiatives, educational activities, conservation programs, and special events at Wildlife Kingdom. Discover something new with every visit and stay connected with the world of wildlife.</p>
    </div>

    <div class="wk-cv-stage" data-cv style="--i:3">
      <button type="button" class="wk-cv-arrow is-prev" aria-label="Previous"><svg viewBox="0 0 24 24"><path d="M15 5l-7 7 7 7"/></svg></button>
      <button type="button" class="wk-cv-arrow is-next" aria-label="Next"><svg viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg></button>

      <div class="wk-cv-view" tabindex="0" role="group" aria-roledescription="carousel" aria-label="Wildlife conservation and events">
        <div class="wk-cv-track">
          <article class="wk-cv-card" style="--i:0">
            <div class="wk-cv-top"><span class="wk-cv-ico"><svg viewBox="0 0 24 24"><path d="M5 19c0-8.5 5-13.5 14-14 0 9-5 14-13.5 14"/><path d="M5 19c2-4.5 5-7.5 9-10"/></svg></span><span class="wk-cv-num" aria-hidden="true">01</span></div>
            <p class="wk-cv-tag">Wildlife Conservation</p>
            <h3>Protecting Wildlife for the Future</h3>
            <p class="wk-cv-txt">Learn how wildlife conservation helps protect animals, preserve biodiversity, and safeguard natural habitats for future generations.</p>
            <a href="conservation.php" class="wk-cv-link">Learn More <span aria-hidden="true">→</span></a>
          </article>
          <article class="wk-cv-card" style="--i:1">
            <div class="wk-cv-top"><span class="wk-cv-ico"><svg viewBox="0 0 24 24"><path d="M4 5a2 2 0 0 1 2-2h13v15H6a2 2 0 0 0-2 2z"/><path d="M4 20a2 2 0 0 0 2 1h13v-3"/></svg></span><span class="wk-cv-num" aria-hidden="true">02</span></div>
            <p class="wk-cv-tag">Wildlife Education</p>
            <h3>Learn About the Natural World</h3>
            <p class="wk-cv-txt">Discover fascinating facts about animals, their behaviour, habitats, and the important role they play in maintaining healthy ecosystems.</p>
            <a href="education.php" class="wk-cv-link">Discover More <span aria-hidden="true">→</span></a>
          </article>
          <article class="wk-cv-card" style="--i:2">
            <div class="wk-cv-top"><span class="wk-cv-ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3c3.2 3.2 3.2 14.8 0 18"/><path d="M12 3c-3.2 3.2-3.2 14.8 0 18"/></svg></span><span class="wk-cv-num" aria-hidden="true">03</span></div>
            <p class="wk-cv-tag">Nature &amp; Awareness</p>
            <h3>Building a Connection with Nature</h3>
            <p class="wk-cv-txt">Explore how understanding nature and wildlife can inspire greater awareness, responsible choices, and a stronger commitment to protecting our environment.</p>
            <a href="awareness.php" class="wk-cv-link">Explore More <span aria-hidden="true">→</span></a>
          </article>
          <article class="wk-cv-card" style="--i:3">
            <div class="wk-cv-top"><span class="wk-cv-ico"><svg viewBox="0 0 24 24"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M4 10h16M9 3v4M15 3v4"/></svg></span><span class="wk-cv-num" aria-hidden="true">04</span></div>
            <p class="wk-cv-tag">Upcoming Events</p>
            <h3>Experience Wildlife Events</h3>
            <p class="wk-cv-txt">Take part in upcoming wildlife programs, educational activities, family experiences, and special events designed to make every visit more engaging.</p>
            <a href="events.php" class="wk-cv-link">View Events <span aria-hidden="true">→</span></a>
          </article>
          <article class="wk-cv-card" style="--i:4">
            <div class="wk-cv-top"><span class="wk-cv-ico"><svg viewBox="0 0 24 24"><path d="M12 20s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.6-7 10-7 10z"/></svg></span><span class="wk-cv-num" aria-hidden="true">05</span></div>
            <p class="wk-cv-tag">Family Experiences</p>
            <h3>Create Meaningful Wildlife Memories</h3>
            <p class="wk-cv-txt">Bring your family and discover engaging activities that combine fun, learning, and memorable experiences with the natural world.</p>
            <a href="visit-us.php" class="wk-cv-link">Plan Your Visit <span aria-hidden="true">→</span></a>
          </article>
          <article class="wk-cv-card" style="--i:5">
            <div class="wk-cv-top"><span class="wk-cv-ico"><svg viewBox="0 0 24 24"><circle cx="6.5" cy="11" r="1.8"/><circle cx="10.5" cy="6.5" r="1.8"/><circle cx="15.5" cy="6.5" r="1.8"/><circle cx="19" cy="11" r="1.8"/><path d="M12.8 12c-3 0-6 3-5.5 6 .4 2 2.4 2.4 5.5 2.4s5.1-.4 5.5-2.4c.5-3-2.5-6-5.5-6z"/></svg></span><span class="wk-cv-num" aria-hidden="true">06</span></div>
            <p class="wk-cv-tag">Animal Conservation</p>
            <h3>Supporting the Future of Wildlife</h3>
            <p class="wk-cv-txt">Learn about the importance of protecting endangered animals, preserving biodiversity, and creating safer environments where wildlife can thrive.</p>
            <a href="conservation.php" class="wk-cv-link">Learn More <span aria-hidden="true">→</span></a>
          </article>
          <article class="wk-cv-card" style="--i:6">
            <div class="wk-cv-top"><span class="wk-cv-ico"><svg viewBox="0 0 24 24"><path d="M2.5 19.5l6.5-11 4 6.5 2.5-3.5 6 8z"/><path d="M9 8.5l1.7 2.7"/></svg></span><span class="wk-cv-num" aria-hidden="true">07</span></div>
            <p class="wk-cv-tag">Habitat Protection</p>
            <h3>Preserving Natural Habitats</h3>
            <p class="wk-cv-txt">Discover why forests, grasslands, wetlands, and other natural ecosystems are essential for wildlife and how habitat protection supports a healthy planet.</p>
            <a href="habitats.php" class="wk-cv-link">Explore Habitats <span aria-hidden="true">→</span></a>
          </article>
          <article class="wk-cv-card" style="--i:7">
            <div class="wk-cv-top"><span class="wk-cv-ico"><svg viewBox="0 0 24 24"><path d="M12 21v-9"/><path d="M12 13c0-4-3-6.5-7.5-6.5 0 4 3 6.5 7.5 6.5z"/><path d="M12 15.5c0-3.2 2.4-5.5 7-5.5 0 3.2-2.4 5.5-7 5.5z"/></svg></span><span class="wk-cv-num" aria-hidden="true">08</span></div>
            <p class="wk-cv-tag">Wildlife Awareness</p>
            <h3>Inspiring the Next Generation</h3>
            <p class="wk-cv-txt">Help create a stronger connection between people and nature through wildlife education, engaging experiences, and awareness about responsible conservation.</p>
            <a href="education.php" class="wk-cv-link">Discover More <span aria-hidden="true">→</span></a>
          </article>
        </div>
      </div>
    </div>
    <div class="wk-cv-bar" aria-hidden="true"><i></i></div>

  </div>
</div>

<script>
(function () {
  var sec = document.querySelector('.wk-cv');
  if (!sec) return;
  sec.classList.add('wk-js');
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var view  = sec.querySelector('.wk-cv-view');
  var track = sec.querySelector('.wk-cv-track');
  var fill  = sec.querySelector('.wk-cv-bar i');

  /* clone the set once so the loop is seamless */
  var originals = [].slice.call(track.children), N = originals.length;
  originals.forEach(function (c, k) {
    var cl = c.cloneNode(true);
    cl.setAttribute('aria-hidden', 'true');
    cl.style.setProperty('--i', k);
    [].forEach.call(cl.querySelectorAll('a'), function (a) { a.tabIndex = -1; });
    track.appendChild(cl);
  });
  var cards = track.children;

  var SPEED = 75;                      /* px per second (auto scroll) */
  var pos = 0, L = 0, stepPx = 0, vel = 0, last = 0, raf = 0;
  var hover = false, focus = false, inView = false, dragging = false, tween = null, moved = 0;

  function measure() {
    var ratio = L ? pos / L : 0;
    var gap = parseFloat(getComputedStyle(track).columnGap) || 0;
    stepPx = cards[0].offsetWidth + gap;
    L = stepPx * N;
    pos = ratio * L;
    draw();
  }
  function mod(v) { return L ? ((v % L) + L) % L : 0; }
  function draw() {
    var p = mod(pos);
    track.style.transform = 'translate3d(' + (-p) + 'px,0,0)';
    fill.style.transform = 'scaleX(' + (L ? p / L : 0) + ')';
  }
  function tick(t) {
    raf = requestAnimationFrame(tick);
    var dt = Math.min(0.05, (t - last) / 1000 || 0); last = t;
    if (tween) {
      var k = Math.min(1, (t - tween.t0) / tween.d), e = 1 - Math.pow(1 - k, 3);
      pos = tween.from + (tween.to - tween.from) * e;
      if (k >= 1) { tween = null; pos = mod(pos); }
    } else if (!dragging) {
      var target = (reduce || hover || focus) ? 0 : SPEED;
      vel += (target - vel) * Math.min(1, dt * 4);
      pos = mod(pos + vel * dt);
    }
    draw();
  }
  function run()  { if (!raf) { last = performance.now(); raf = requestAnimationFrame(tick); } }
  function halt() { if (raf) { cancelAnimationFrame(raf); raf = 0; } }

  /* arrows: slide exactly one card, then auto scroll carries on */
  function go(dir) {
    var from = pos, base = tween ? tween.to : pos;
    tween = { from: from, to: base + dir * stepPx, t0: performance.now(), d: reduce ? 1 : 650 };
    vel = 0;
    run();
  }
  sec.querySelector('.is-prev').addEventListener('click', function () { go(-1); });
  sec.querySelector('.is-next').addEventListener('click', function () { go(1); });
  view.addEventListener('keydown', function (e) {
    if (e.key === 'ArrowRight') { go(1); e.preventDefault(); }
    if (e.key === 'ArrowLeft')  { go(-1); e.preventDefault(); }
  });

  /* pause while the pointer is over the cards */
  view.addEventListener('pointerenter', function (e) { if (e.pointerType === 'mouse') hover = true; });
  view.addEventListener('pointerleave', function (e) { if (e.pointerType === 'mouse') hover = false; });
  view.addEventListener('focusin',  function () { focus = true; });
  view.addEventListener('focusout', function () { focus = false; });

  /* glow follows the pointer */
  [].forEach.call(cards, function (c) {
    c.addEventListener('pointermove', function (e) {
      var r = c.getBoundingClientRect();
      c.style.setProperty('--mx', (e.clientX - r.left) + 'px');
      c.style.setProperty('--my', (e.clientY - r.top) + 'px');
    });
  });

  /* drag / swipe */
  var startX = 0, startPos = 0, down = false;
  view.addEventListener('dragstart', function (e) { e.preventDefault(); });
  view.addEventListener('pointerdown', function (e) {
    if (e.button !== 0) return;
    down = true; moved = 0; startX = e.clientX; startPos = pos; tween = null; vel = 0;
  });
  window.addEventListener('pointermove', function (e) {
    if (!down) return;
    var dx = e.clientX - startX; moved = Math.max(moved, Math.abs(dx));
    if (moved > 5) { dragging = true; view.classList.add('is-drag'); pos = startPos - dx; draw(); }
  });
  function release() { down = false; dragging = false; view.classList.remove('is-drag'); pos = mod(pos); }
  window.addEventListener('pointerup', release);
  window.addEventListener('pointercancel', release);
  view.addEventListener('click', function (e) { if (moved > 5) { e.preventDefault(); e.stopPropagation(); moved = 0; } }, true);

  /* layout + visibility */
  measure();
  window.addEventListener('load', measure);
  if ('ResizeObserver' in window) new ResizeObserver(measure).observe(view); else window.addEventListener('resize', measure);

  if ('IntersectionObserver' in window) {
    var shown = false;
    new IntersectionObserver(function (e) {
      inView = e[0].isIntersecting;
      if (inView) { if (!shown && !reduce) { shown = true; sec.classList.add('is-in'); } else if (reduce) sec.classList.add('is-in'); run(); }
      else halt();
    }, { threshold: 0.2 }).observe(sec);
  } else { sec.classList.add('is-in'); run(); }
})();
</script>

<!-- ============ SECTION 6 — FAQ ============ -->
<div class="section wk-fq" role="region" aria-label="Frequently Asked Questions">
  <div class="container wk-fq-wrap">

    <div class="wk-fq-head">
      <div data-fq style="--i:0"><span class="wk-eyebrow">Frequently Asked Questions</span></div>
      <h2 data-fq style="--i:1">Frequently Asked <span class="wk-fq-accent">Questions</span></h2>
      <p class="wk-fq-intro" data-fq style="--i:2">Planning your visit to Wildlife Kingdom? Find answers to some of the most common questions about visiting, wildlife experiences, habitats, events, and tickets. If you need more information, our team is always here to help you make the most of your wildlife experience.</p>
    </div>

    <div class="wk-fq-grid">

    <div class="wk-fq-visual">
      <div class="wk-fq-shadow">
        <div class="wk-fq-ticket">
          <!-- IMAGE (top of the ticket, portrait): use a 1000 x 900 px image. -->
          <div class="wk-fq-photo">
            <div class="wk-fq-empty" aria-hidden="true"><b>🐾</b><span>Your image here</span><small>1000 × 900 px</small></div>
            <img src="../assets/images/static/animals.webp" alt="Wild animals together in the savanna at Wildlife Kingdom" loading="lazy" onerror="this.style.display='none'">
            <span class="wk-fq-label">Visitor Guide</span>
          </div>
          <div class="wk-fq-stub">
            <small>Need more help?</small>
            <b>Our team is always here to help you.</b>
            <a href="contact.php">Contact Us <span aria-hidden="true">→</span></a>
          </div>
        </div>
      </div>
      <div class="wk-fq-stamp" aria-hidden="true">
        <svg viewBox="0 0 100 100">
          <defs><path id="wkFqCirc" d="M50,50 m-38,0 a38,38 0 1,1 76,0 a38,38 0 1,1 -76,0"/></defs>
          <text><textPath href="#wkFqCirc" textLength="236" lengthAdjust="spacing">WILDLIFE KINGDOM • VISITOR GUIDE • </textPath></text>
        </svg>
        <i>🐾</i>
      </div>
    </div>

    <div class="wk-fq-copy">
      <div class="wk-fq-list">
        <div class="wk-fq-item is-open" style="--i:0">
          <h3 class="wk-fq-q"><button type="button" id="wkfqb1" aria-expanded="true" aria-controls="wkfqa1"><span class="wk-fq-n">01</span><span class="wk-fq-t">What can I explore at Wildlife Kingdom?</span><span class="wk-fq-pm" aria-hidden="true"></span></button></h3>
          <div class="wk-fq-a" id="wkfqa1" role="region" aria-labelledby="wkfqb1"><div><p>Wildlife Kingdom offers visitors the opportunity to explore diverse wildlife, fascinating animal habitats, nature-focused experiences, educational activities, and special events. Explore our website to discover the animals, habitats, and experiences available during your visit.</p></div></div>
        </div>
        <div class="wk-fq-item" style="--i:1">
          <h3 class="wk-fq-q"><button type="button" id="wkfqb2" aria-expanded="false" aria-controls="wkfqa2"><span class="wk-fq-n">02</span><span class="wk-fq-t">What animals can I see at Wildlife Kingdom?</span><span class="wk-fq-pm" aria-hidden="true"></span></button></h3>
          <div class="wk-fq-a" id="wkfqa2" role="region" aria-labelledby="wkfqb2"><div><p>You can discover a variety of fascinating wildlife species at Wildlife Kingdom. Visit our <a href="animals.php">Animals section</a> to explore featured species, learn interesting facts, and discover more about their habitats and natural behaviours.</p></div></div>
        </div>
        <div class="wk-fq-item" style="--i:2">
          <h3 class="wk-fq-q"><button type="button" id="wkfqb3" aria-expanded="false" aria-controls="wkfqa3"><span class="wk-fq-n">03</span><span class="wk-fq-t">Can I visit Wildlife Kingdom with my family?</span><span class="wk-fq-pm" aria-hidden="true"></span></button></h3>
          <div class="wk-fq-a" id="wkfqa3" role="region" aria-labelledby="wkfqb3"><div><p>Yes. Wildlife Kingdom is designed to provide engaging and educational experiences for visitors of different ages. Families can explore wildlife, discover natural habitats, participate in activities, and create memorable experiences together.</p></div></div>
        </div>
        <div class="wk-fq-item" style="--i:3">
          <h3 class="wk-fq-q"><button type="button" id="wkfqb4" aria-expanded="false" aria-controls="wkfqa4"><span class="wk-fq-n">04</span><span class="wk-fq-t">How can I book tickets for Wildlife Kingdom?</span><span class="wk-fq-pm" aria-hidden="true"></span></button></h3>
          <div class="wk-fq-a" id="wkfqa4" role="region" aria-labelledby="wkfqb4"><div><p>You can visit our <a href="tickets.php">Tickets page</a> to find available ticket information and booking options. Check the latest details before planning your visit.</p></div></div>
        </div>
        <div class="wk-fq-item" style="--i:4">
          <h3 class="wk-fq-q"><button type="button" id="wkfqb5" aria-expanded="false" aria-controls="wkfqa5"><span class="wk-fq-n">05</span><span class="wk-fq-t">What wildlife experiences are available?</span><span class="wk-fq-pm" aria-hidden="true"></span></button></h3>
          <div class="wk-fq-a" id="wkfqa5" role="region" aria-labelledby="wkfqb5"><div><p>Wildlife Kingdom offers opportunities to explore animals and habitats, learn about wildlife, enjoy educational activities, and participate in special programs and events. Available experiences may vary depending on the schedule.</p></div></div>
        </div>
        <div class="wk-fq-item" style="--i:5">
          <h3 class="wk-fq-q"><button type="button" id="wkfqb6" aria-expanded="false" aria-controls="wkfqa6"><span class="wk-fq-n">06</span><span class="wk-fq-t">Does Wildlife Kingdom have wildlife conservation programs?</span><span class="wk-fq-pm" aria-hidden="true"></span></button></h3>
          <div class="wk-fq-a" id="wkfqa6" role="region" aria-labelledby="wkfqb6"><div><p>Wildlife conservation and environmental awareness are important parts of Wildlife Kingdom. Visitors can learn about protecting animals, preserving natural habitats, maintaining biodiversity, and understanding the importance of responsible conservation.</p></div></div>
        </div>
        <div class="wk-fq-item" style="--i:6">
          <h3 class="wk-fq-q"><button type="button" id="wkfqb7" aria-expanded="false" aria-controls="wkfqa7"><span class="wk-fq-n">07</span><span class="wk-fq-t">Where can I find information about upcoming events?</span><span class="wk-fq-pm" aria-hidden="true"></span></button></h3>
          <div class="wk-fq-a" id="wkfqa7" role="region" aria-labelledby="wkfqb7"><div><p>You can visit the <a href="events.php">Events section</a> of our website to discover upcoming wildlife programs, educational activities, special events, and other experiences planned at Wildlife Kingdom.</p></div></div>
        </div>
        <div class="wk-fq-item" style="--i:7">
          <h3 class="wk-fq-q"><button type="button" id="wkfqb8" aria-expanded="false" aria-controls="wkfqa8"><span class="wk-fq-n">08</span><span class="wk-fq-t">How can I plan my visit?</span><span class="wk-fq-pm" aria-hidden="true"></span></button></h3>
          <div class="wk-fq-a" id="wkfqa8" role="region" aria-labelledby="wkfqb8"><div><p>Visit our <a href="visit-us.php">Visit Us page</a> for important visitor information, including location and other details that can help you prepare for your Wildlife Kingdom experience.</p></div></div>
        </div>
      </div>
    </div>

    </div>

  </div>
</div>
<script>
(function () {
  var sec = document.querySelector('.wk-fq');
  if (!sec) return;
  sec.classList.add('wk-js');
  var items = [].slice.call(sec.querySelectorAll('.wk-fq-item'));

  function setOpen(item, open) {
    item.classList.toggle('is-open', open);
    item.querySelector('button').setAttribute('aria-expanded', open ? 'true' : 'false');
  }
  items.forEach(function (item) {
    item.querySelector('button').addEventListener('click', function () {
      var willOpen = !item.classList.contains('is-open');
      items.forEach(function (o) { setOpen(o, false); });     /* one open at a time */
      if (willOpen) setOpen(item, true);
    });
  });

  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (e) {
      if (e[0].isIntersecting) { sec.classList.add('is-in'); io.disconnect(); }
    }, { threshold: 0.15 });
    io.observe(sec);
  } else { sec.classList.add('is-in'); }
})();
</script>

<?php require '../includes/footer.php'; ?>