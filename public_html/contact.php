<?php
$pageTitle = 'Contact';
$pageCss = 'pages.css';
require '../config/db.php';

$formMessage = '';
$formStatus = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        $formStatus = 'error';
        $formMessage = 'Please fill in your name, email and message.';
    } else {
        $stmt = $conn->prepare("INSERT INTO enquiries (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);

        if ($stmt->execute()) {
            $formStatus = 'success';
            $formMessage = "Thanks " . $name . ", your message has been received. We'll get back to you soon.";
        } else {
            $formStatus = 'error';
            $formMessage = 'Something went wrong, please try again.';
        }
    }
}

// Re-fill the form only when validation failed (cleared after success)
$old = ($formStatus === 'error')
    ? ['name' => $name ?? '', 'email' => $email ?? '', 'phone' => $phone ?? '', 'subject' => $subject ?? '', 'message' => $message ?? '']
    : [];
function wk_old($old, $key) { return htmlspecialchars($old[$key] ?? '', ENT_QUOTES); }

require '../includes/header.php';
?>

<style>
/* ---------- Contact banner (same style as Gallery / About banner) ---------- */
.con-banner { position: relative; overflow: hidden; height: 500px; background-color: #0B2F22; }
.con-banner::before {
  content: ""; position: absolute; inset: 0;
  background: url('../assets/images/static/contact-banner.webp') center / cover no-repeat;
  animation: conBannerPan 18s ease-in-out infinite alternate;
}
.con-banner::after { content: ""; position: absolute; left: 0; right: 0; bottom: 0; height: 4px; background: #e8b52a; }
@keyframes conBannerPan { from { transform: scale(1); } to { transform: scale(1.08); } }
@media (max-width: 800px) { .con-banner { height: 300px; } }
@media (prefers-reduced-motion: reduce) { .con-banner::before { animation: none !important; } }
</style>

<style>
@import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap');

/* =====================================================
   CONTACT — SECTION 1 ("wk-ct")
   bg #FFFDF8, compact padding, small side spacing (same as Home)
   Fonts: Playfair Display (headings) + Lato (body)
   ===================================================== */
section.wk-ct.wk-ct {
  background: #FFFDF8 !important;
  position: relative; overflow: hidden;
  padding: clamp(32px, 4.5vw, 56px) 0;
  font-family: 'Lato', 'Segoe UI', sans-serif;
}
.wk-ct, .wk-ct * { box-sizing: border-box; }
.wk-ct .wk-ct-grid.container {
  position: relative; z-index: 1;
  max-width: 100%;
  padding-left: clamp(16px, 2.5vw, 36px);
  padding-right: clamp(16px, 2.5vw, 36px);
  display: grid; grid-template-columns: clamp(320px, 40vw, 580px) minmax(0, 1fr);
  gap: clamp(28px, 4vw, 64px);
  align-items: center;
}

/* ---- copy (left) ---- */
.wk-ct .wk-eyebrow {
  display: inline-flex; align-items: center; gap: 12px; margin-bottom: 14px;
  font-size: .72rem; font-weight: 700; line-height: 1;
  letter-spacing: .24em; text-transform: uppercase; color: #173B2A;
}
.wk-ct .wk-eyebrow::before { content: ""; width: 36px; height: 2px; background: #C89B3C; }
.wk-ct h1 {
  margin: 0 0 14px; width: 100%;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.3rem, 2.2vw, 1.9rem);
  font-weight: 700; line-height: 1.12; letter-spacing: -.005em;
  color: #173B2A;
}
.wk-ct h1 .wk-ct-black { color: #000; }
.wk-ct h1 .wk-ct-accent { color: #E8A15B; font-style: italic; }
.wk-ct h1::after {
  content: ""; display: block; width: 100%; height: 2px; margin-top: 14px;
  background: linear-gradient(90deg, #C89B3C, rgba(200,155,60,0));
}
.wk-ct .wk-ct-copy p {
  margin: 0 0 10px; font-size: .95rem; line-height: 1.7;
  color: #2B241D; text-align: justify; hyphens: manual;
}
.wk-ct .wk-ct-copy p.wk-ct-lead { font-size: .98rem; }

/* supporting text: Your questions. Your plans. Our team. */
.wk-ct-trail { display: flex; align-items: center; gap: 12px; margin-top: 18px; }
.wk-ct-stop { flex: 0 0 auto; display: inline-flex; align-items: center; gap: 10px; }
.wk-ct-dot {
  width: 34px; height: 34px; border-radius: 50%; display: grid; place-items: center;
  background: #FFFDF8; border: 2px solid #C89B3C; color: #173B2A;
  font-family: 'Playfair Display', Georgia, serif; font-weight: 700; font-size: .9rem;
  box-shadow: 0 6px 14px rgba(23,59,42,.16); transition: background .3s, color .3s, transform .3s;
}
.wk-ct-stop:last-of-type .wk-ct-dot { background: #173B2A; color: #E8A15B; border-color: #173B2A; }
.wk-ct-stop:hover .wk-ct-dot { background: #E8A15B; color: #173B2A; border-color: #E8A15B; transform: translateY(-3px); }
.wk-ct-stop b { font-family: 'Playfair Display', Georgia, serif; font-size: 1rem; font-weight: 700; color: #173B2A; white-space: nowrap; }
.wk-ct-stop:last-of-type b { color: #E8A15B; font-style: italic; }
.wk-ct-link { flex: 1 1 14px; min-width: 14px; height: 0; border-top: 2px dashed rgba(200,155,60,.8); }
@media (max-width: 560px) {
  .wk-ct-trail { flex-direction: column; align-items: flex-start; gap: 8px; }
  .wk-ct-link { display: none; }
}

/* ---- left: image container (leaf-cut, gold frame, postmark stamp) ---- */
.wk-ct-copy { min-width: 0; }
.wk-ct-visual { order: -1; position: relative; height: clamp(300px, 28vw, 390px); }
.wk-ct-visual::before {          /* offset gold frame (down-left) */
  content: ""; position: absolute; z-index: 0; top: 12px; left: -12px; right: 12px; bottom: -12px;
  border: 1.5px solid #C89B3C; border-radius: 110px 18px 110px 18px; transition: transform .5s;
}
.wk-ct-visual:hover::before { transform: translate(-5px, 5px); }
.wk-ct-visual-img {
  position: absolute; z-index: 1; inset: 0; overflow: hidden; border-radius: 110px 18px 110px 18px;
  background: linear-gradient(160deg, #1F4A36, #0B2F22); box-shadow: 0 22px 46px rgba(23,59,42,.30);
}
.wk-ct-visual-img img { width: 100%; height: 100%; object-fit: cover; object-position: center 60%; display: block; animation: wkCtPan 18s ease-in-out infinite alternate; }
@keyframes wkCtPan { from { transform: scale(1.02); } to { transform: scale(1.12); } }
.wk-ct-visual-img::before {      /* soft bottom shade so chips read well */
  content: ""; position: absolute; inset: 0; z-index: 1; pointer-events: none;
  background: linear-gradient(to top, rgba(11,47,34,.6), rgba(11,47,34,0) 45%);
}
.wk-ct-visual-img::after {       /* thin inner mat line */
  content: ""; position: absolute; inset: 10px; z-index: 2; pointer-events: none;
  border: 1px solid rgba(255,253,248,.42); border-radius: 100px 10px 100px 10px;
}
.wk-ct-chips { position: absolute; z-index: 3; left: 26px; bottom: 24px; display: flex; flex-wrap: wrap; gap: 7px; }
.wk-ct-chips span {
  padding: 6px 13px; border-radius: 999px; font-size: .76rem; font-weight: 700; line-height: 1.3; color: #FFFDF8;
  background: rgba(11,47,34,.72); border: 1px solid rgba(232,161,91,.75); backdrop-filter: blur(4px);
  opacity: 0; animation: wkCtRise .6s ease forwards;
}
.wk-ct-chips span:nth-child(1) { animation-delay: .9s; }
.wk-ct-chips span:nth-child(2) { animation-delay: 1.05s; }
.wk-ct-chips span:nth-child(3) { animation-delay: 1.2s; }
.wk-ct-stamp {                   /* rotating postmark */
  position: absolute; z-index: 3; top: -20px; right: -14px; width: 88px; height: 88px; border-radius: 50%;
  background: #173B2A; border: 1.5px solid #C89B3C; box-shadow: 0 10px 22px rgba(0,0,0,.35);
  display: grid; place-items: center;
}
.wk-ct-stamp svg { position: absolute; inset: 0; width: 100%; height: 100%; animation: wkCtSpin 20s linear infinite; }
.wk-ct-stamp text { font: 700 8.4px 'Lato', sans-serif; fill: #E8A15B; }
.wk-ct-stamp i { font-style: normal; font-size: 1.3rem; line-height: 1; }
@keyframes wkCtSpin { to { transform: rotate(360deg); } }

/* entrance */
.wk-ct-copy > * { opacity: 0; animation: wkCtRise .7s ease forwards; }
.wk-ct-copy > *:nth-child(2) { animation-delay: .1s; }
.wk-ct-copy > *:nth-child(3) { animation-delay: .2s; }
.wk-ct-copy > *:nth-child(4) { animation-delay: .3s; }
.wk-ct-copy > *:nth-child(5) { animation-delay: .4s; }
.wk-ct-visual { opacity: 0; animation: wkCtRise .9s ease .15s forwards; }
@keyframes wkCtRise { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: none; } }
@media (prefers-reduced-motion: reduce) {
  .wk-ct *, .wk-ct *::before { animation: none !important; transition: none !important; }
  .wk-ct-copy > *, .wk-ct-visual, .wk-ct-chips span { opacity: 1; }
}

/* responsive */
@media (max-width: 900px) {
  .wk-ct .wk-ct-grid.container { grid-template-columns: minmax(0, 1fr); }
  .wk-ct-visual { order: 0; height: 300px; max-width: 520px; width: 100%; margin: 6px auto 12px; }
  .wk-ct-visual::before, .wk-ct-visual-img { border-radius: 90px 16px 90px 16px; }
  .wk-ct-visual-img::after { border-radius: 82px 8px 82px 8px; }
}
@media (max-width: 480px) {
  .wk-ct .wk-eyebrow { letter-spacing: .16em; font-size: .68rem; gap: 10px; }
  .wk-ct .wk-eyebrow::before { width: 26px; }
  .wk-ct-visual { height: 240px; }
  .wk-ct-visual::before, .wk-ct-visual-img { border-radius: 64px 14px 64px 14px; }
  .wk-ct-visual-img::after { border-radius: 56px 8px 56px 8px; }
  .wk-ct-chips { left: 16px; bottom: 14px; }
  .wk-ct-chips span { font-size: .7rem; padding: 5px 10px; }
  .wk-ct-stamp { width: 74px; height: 74px; right: -4px; }
}
</style>

<style>
/* =====================================================
   CONTACT — SECTION 2 ("wk-gt") Get in Touch
   bg #173B2A (same green as Home sections 2 / 4 / 6)
   Left: heading, intro + 4 leaf-cut contact tiles
   Right: cream "enquiry paper" form card with offset gold frame
   ===================================================== */
section.wk-gt.wk-gt {
  background: #173B2A !important;
  position: relative; overflow: hidden;
  padding: clamp(24px, 3vw, 40px) 0;
  font-family: 'Lato', 'Segoe UI', sans-serif;
}
.wk-gt, .wk-gt * { box-sizing: border-box; }
.wk-gt .wk-gt-grid.container {
  position: relative; z-index: 1; max-width: 100%;
  padding-left: clamp(16px, 2.5vw, 36px);
  padding-right: clamp(16px, 2.5vw, 36px);
  display: grid; grid-template-columns: minmax(0, 1fr) clamp(340px, 38vw, 540px);
  gap: clamp(24px, 3.4vw, 52px);
  align-items: stretch;
}

/* ---- left: copy ---- */
.wk-gt .wk-eyebrow {
  display: inline-flex; align-items: center; gap: 12px; margin-bottom: 14px;
  font-size: .72rem; font-weight: 700; line-height: 1;
  letter-spacing: .24em; text-transform: uppercase; color: #E8A15B;
}
.wk-gt .wk-eyebrow::before { content: ""; width: 36px; height: 2px; background: #C89B3C; }
.wk-gt h2 {
  margin: 0 0 14px; width: 100%;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.3rem, 2.2vw, 1.9rem);
  font-weight: 700; line-height: 1.12; letter-spacing: -.005em;
  color: #FFFDF8 !important;
}
.wk-gt h2 .wk-gt-accent { color: #E8A15B; font-style: italic; }
.wk-gt h2::after {
  content: ""; display: block; width: 100%; height: 2px; margin-top: 14px;
  background: linear-gradient(90deg, #E8A15B, rgba(232,161,91,0));
}
.wk-gt .wk-gt-intro p {
  margin: 0 0 10px; font-size: .95rem; line-height: 1.7;
  color: rgba(255,253,248,.88); text-align: justify; hyphens: manual;
}

/* ---- left: compact 2x2 "visit guide" cards ---- */
.wk-gt .wk-gt-intro p { font-size: .95rem; line-height: 1.65; margin-bottom: 10px; }
.wk-gt h2 { margin-bottom: 10px; }
.wk-gt h2::after { margin-top: 10px; }
/* ---- left: "We're here to help" contact card (dark green, gold line) ---- */
@keyframes wkGtDot { 0%,100% { box-shadow: 0 0 0 3px rgba(232,161,91,.3); } 50% { box-shadow: 0 0 0 7px rgba(232,161,91,.08); } }
.wk-gt-left { display: flex; flex-direction: column; min-width: 0; }
.wk-gt-help {
  position: relative; overflow: hidden; flex: 1; display: flex; flex-direction: column; margin-top: 20px; padding: 20px 26px 8px;
  background:
    repeating-radial-gradient(circle at 100% 0, rgba(232,161,91,.07) 0 1px, transparent 1px 26px),
    linear-gradient(160deg, #1F4A36, #0B2F22);
  border: 1.5px solid rgba(200,155,60,.75); border-radius: 12px 52px 12px 52px;
  box-shadow: 0 20px 40px rgba(0,0,0,.3);
}
.wk-gt-pill {
  align-self: flex-start; display: inline-flex; align-items: center; gap: 10px; margin-bottom: 6px; padding: 7px 16px 7px 9px;
  font-size: .66rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #FFFDF8;
  border: 1px solid rgba(232,161,91,.75); border-radius: 999px; background: rgba(11,47,34,.55);
}
.wk-gt-pill::before { content: ""; width: 12px; height: 12px; border-radius: 50%; background: #E8A15B; animation: wkGtDot 2s ease-in-out infinite; }
.wk-gt-crows { flex: 1; display: flex; flex-direction: column; justify-content: space-between; }
.wk-gt-crow {
  flex: 1; display: flex; align-items: center; gap: 16px; min-width: 0; padding: 10px 0;
  text-decoration: none; color: inherit; border-bottom: 1px solid rgba(232,161,91,.3); transition: transform .25s;
}
.wk-gt-crow:last-child { border-bottom: 0; }
a.wk-gt-crow:hover { transform: translateX(5px); }
.wk-gt-cico {
  flex: 0 0 auto; width: 46px; height: 46px; border-radius: 50%; display: grid; place-items: center;
  color: #E8A15B; border: 1px solid rgba(232,161,91,.75); background: rgba(11,47,34,.5); transition: background .3s, color .3s, transform .35s;
}
.wk-gt-cico svg { width: 20px; height: 20px; fill: none; stroke: currentColor; stroke-width: 1.7; stroke-linecap: round; stroke-linejoin: round; }
.wk-gt-crow:hover .wk-gt-cico { background: #E8A15B; color: #173B2A; transform: rotate(-8deg) scale(1.06); }
.wk-gt-crow small { display: block; margin-bottom: 3px; font-size: .66rem; font-weight: 700; letter-spacing: .24em; text-transform: uppercase; color: #E8A15B; }
.wk-gt-crow b { display: block; font-size: 1.02rem; font-weight: 700; line-height: 1.3; color: #FFFDF8; overflow-wrap: anywhere; }
@media (max-width: 560px) {
  .wk-gt-help { padding: 16px 16px 6px; border-radius: 10px 38px 10px 38px; }
  .wk-gt-cico { width: 40px; height: 40px; }
  .wk-gt-crow { gap: 12px; }
  .wk-gt-crow b { font-size: .92rem; }
}

/* ---- right: enquiry paper ---- */
.wk-gt-formwrap { position: relative; display: flex; }
.wk-gt-formwrap::before {        /* offset gold frame */
  content: ""; position: absolute; z-index: 0; top: -10px; left: 12px; right: -10px; bottom: 10px;
  border: 1.5px solid #C89B3C; border-radius: 80px 16px 80px 16px; transition: transform .5s;
}
.wk-gt-formwrap:hover::before { transform: translate(4px, -4px); }
.wk-gt-card {
  position: relative; z-index: 1; overflow: hidden; flex: 1; min-width: 0;
  padding: 34px 30px 28px;
  background: #FFFDF8; color: #2B241D;
  border-radius: 80px 16px 80px 16px;
  box-shadow: 0 22px 46px rgba(0,0,0,.35);
}
.wk-gt-tag {
  position: relative; display: inline-flex; align-items: center; gap: 9px; margin: 0 0 10px 22px;
  font-size: .64rem; font-weight: 700; letter-spacing: .22em; text-transform: uppercase; color: #173B2A;
}
.wk-gt-tag::before { content: ""; width: 7px; height: 7px; border-radius: 50%; background: #E8A15B; animation: wkGtPulse 1.8s ease-out infinite; }
@keyframes wkGtPulse { 0% { box-shadow: 0 0 0 0 rgba(232,161,91,.7); } 100% { box-shadow: 0 0 0 9px rgba(232,161,91,0); } }
.wk-gt-card h3.wk-gt-ftitle {
  position: relative; margin: 0 0 6px; padding-left: 22px;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.15rem, 1.8vw, 1.5rem); font-weight: 700; line-height: 1.15; color: #173B2A;
}
.wk-gt-card p.wk-gt-fsub { position: relative; margin: 0 0 18px; padding-left: 22px; font-size: .9rem; line-height: 1.6; color: #2B241D; }
.wk-gt-card p.wk-gt-fsub::after {
  content: ""; display: block; width: 56px; height: 3px; margin-top: 12px; border-radius: 2px; background: #C89B3C;
}

.wk-gt-form { position: relative; display: grid; grid-template-columns: 1fr 1fr; gap: 14px 14px; }
.wk-gt-field { display: flex; flex-direction: column; gap: 6px; min-width: 0; }
.wk-gt-field.is-full { grid-column: 1 / -1; }
.wk-gt-form label {
  margin: 0; font-size: .68rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: #173B2A;
}
.wk-gt-form input, .wk-gt-form select, .wk-gt-form textarea {
  width: 100%; margin: 0; padding: 11px 14px;
  font: inherit; font-size: .92rem; color: #2B241D;
  background: #fff; border: 1px solid rgba(23,59,42,.28); border-radius: 12px;
  outline: none; transition: border-color .25s, box-shadow .25s, background .25s;
}
.wk-gt-form textarea { min-height: 120px; resize: vertical; line-height: 1.6; }
.wk-gt-form ::placeholder { color: rgba(43,36,29,.5); }
.wk-gt-form input:focus, .wk-gt-form select:focus, .wk-gt-form textarea:focus {
  border-color: #E8A15B; box-shadow: 0 0 0 3px rgba(232,161,91,.28); background: #FFFDF8;
}
.wk-gt-form select {
  appearance: none; -webkit-appearance: none; cursor: pointer; padding-right: 38px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23173B2A' stroke-width='2.6' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
  background-repeat: no-repeat; background-position: right 12px center; background-size: 16px;
}
.wk-gt-btn {
  grid-column: 1 / -1; margin-top: 4px; cursor: pointer;
  display: inline-flex; align-items: center; justify-content: center; gap: 10px;
  padding: 13px 26px; font: inherit; font-size: .88rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase;
  background: #E8A15B; color: #173B2A; border: 2px solid #E8A15B; border-radius: 999px;
  position: relative; overflow: hidden;
  transition: background .25s, color .25s, border-color .25s, transform .25s, gap .25s;
}
.wk-gt-btn::after {              /* light sweep */
  content: ""; position: absolute; top: 0; bottom: 0; left: -60%; width: 40%;
  background: linear-gradient(105deg, transparent, rgba(255,255,255,.55), transparent);
  transform: skewX(-20deg); transition: left .6s;
}
.wk-gt-btn:hover { background: #173B2A; border-color: #173B2A; color: #FFFDF8; transform: translateY(-2px); gap: 14px; }
.wk-gt-btn:hover::after { left: 130%; }
.wk-gt-btn:focus-visible { outline: 3px solid #E8A15B; outline-offset: 3px; }

.wk-gt .form-msg {
  position: relative; margin: 0 0 16px; padding: 12px 16px; border-radius: 12px;
  font-size: .9rem; line-height: 1.55; font-weight: 700; border: 1px solid;
}
.wk-gt .form-msg.success { background: rgba(23,59,42,.08); border-color: #173B2A; color: #173B2A; }
.wk-gt .form-msg.error   { background: rgba(176,58,46,.08); border-color: #B03A2E; color: #8F2D23; }

/* compact form (same design, tighter spacing) */
.wk-gt-card { padding: 28px 28px 22px; }
.wk-gt-card p.wk-gt-fsub { margin-bottom: 14px; }
.wk-gt-form { gap: 12px; }
.wk-gt-form input, .wk-gt-form select, .wk-gt-form textarea { padding: 10px 14px; }
.wk-gt-form textarea { min-height: 88px; }
.wk-gt-btn { padding: 12px 26px; }

/* entrance (scroll reveal) */
.wk-gt.wk-js [data-gt] { opacity: 0; transform: translateY(18px); transition: opacity .7s ease, transform .7s cubic-bezier(.2,.7,.2,1); transition-delay: calc(var(--i, 0) * 90ms); }
.wk-gt.wk-js.is-in [data-gt] { opacity: 1; transform: none; }
.wk-gt.wk-js h2::after { transform: scaleX(0); transform-origin: left; transition: transform 1.1s .35s cubic-bezier(.2,.7,.2,1); }
.wk-gt.wk-js.is-in h2::after { transform: scaleX(1); }
.wk-gt.wk-js .wk-gt-formwrap { opacity: 0; }
.wk-gt.wk-js.is-in .wk-gt-formwrap { opacity: 1; animation: wkGtSlide 1s .2s cubic-bezier(.2,.7,.2,1) backwards; }
@keyframes wkGtSlide { from { opacity: 0; transform: translateX(46px); } to { opacity: 1; transform: none; } }
@media (prefers-reduced-motion: reduce) {
  .wk-gt *, .wk-gt *::before, .wk-gt *::after { animation: none !important; transition: none !important; }
  .wk-gt.wk-js [data-gt], .wk-gt.wk-js .wk-gt-formwrap { opacity: 1; transform: none; }
  .wk-gt.wk-js h2::after { transform: none; }
}

/* responsive */
@media (max-width: 900px) {
  .wk-gt .wk-gt-grid.container { grid-template-columns: minmax(0, 1fr); }
  .wk-gt-formwrap { max-width: 560px; width: 100%; margin: 0 auto; }
}
@media (max-width: 480px) {
  .wk-gt .wk-eyebrow { letter-spacing: .16em; font-size: .68rem; gap: 10px; }
  .wk-gt .wk-eyebrow::before { width: 26px; }
  .wk-gt-card { padding: 30px 18px 22px; border-radius: 56px 14px 56px 14px; }
  .wk-gt-formwrap::before { border-radius: 56px 14px 56px 14px; }
  .wk-gt-form { grid-template-columns: 1fr; }
  .wk-gt-tag, .wk-gt-card h3.wk-gt-ftitle, .wk-gt-card p.wk-gt-fsub { padding-left: 4px; margin-left: 0; }
  .wk-gt-tag { margin-left: 4px; }
}
</style>


<style>
/* =====================================================
   CONTACT — SECTION 3 ("wk-vc") Your Voice Matters
   bg #FFFDF8 (same cream as Section 1) — compact
   Left: copy + 3 highlights + closing line | Right: 3-image collage
   ===================================================== */
section.wk-vc.wk-vc {
  background: #FFFDF8 !important; position: relative; overflow: hidden;
  padding: clamp(28px, 3.6vw, 46px) 0;
  font-family: 'Lato', 'Segoe UI', sans-serif;
}
.wk-vc, .wk-vc * { box-sizing: border-box; }
.wk-vc .wk-vc-grid.container {
  position: relative; z-index: 1; max-width: 100%;
  padding-left: clamp(16px, 2.5vw, 36px); padding-right: clamp(16px, 2.5vw, 36px);
  display: grid; grid-template-columns: minmax(0, 1fr) clamp(330px, 38vw, 540px);
  gap: clamp(28px, 4vw, 60px); align-items: center;
}

/* ---- copy ---- */
.wk-vc .wk-eyebrow {
  display: inline-flex; align-items: center; gap: 12px; margin-bottom: 12px;
  font-size: .72rem; font-weight: 700; line-height: 1; letter-spacing: .24em; text-transform: uppercase; color: #173B2A;
}
.wk-vc .wk-eyebrow::before { content: ""; width: 36px; height: 2px; background: #C89B3C; }
.wk-vc h2 {
  margin: 0 0 10px; width: 100%; font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.3rem, 2.2vw, 1.9rem); font-weight: 700; line-height: 1.12; letter-spacing: -.005em; color: #173B2A;
}
.wk-vc h2 .wk-vc-accent { color: #E8A15B; font-style: italic; }
.wk-vc h2::after { content: ""; display: block; width: 100%; height: 2px; margin-top: 10px; background: linear-gradient(90deg, #C89B3C, rgba(200,155,60,0)); }
.wk-vc-copy p.wk-vc-txt { margin: 0 0 8px; font-size: .92rem; line-height: 1.65; color: #2B241D; text-align: justify; hyphens: manual; }

/* ---- 3 highlights ---- */
.wk-vc-hl { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; margin: 14px 0 0; }
.wk-vc-card {
  position: relative; overflow: hidden; display: flex; flex-direction: column; gap: 8px; padding: 14px 14px 13px 16px;
  background: #fff; border: 1px solid rgba(200,155,60,.6); border-radius: 28px 8px 28px 8px;
  box-shadow: 0 8px 20px rgba(23,59,42,.07);
  transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
}
.wk-vc-card:nth-child(2) { border-radius: 8px 28px 8px 28px; }
.wk-vc-card::after { content: ""; position: absolute; left: 0; right: 0; bottom: 0; height: 3px; background: linear-gradient(90deg, #E8A15B, #C89B3C); transform: scaleX(0); transform-origin: left; transition: transform .5s cubic-bezier(.2,.7,.2,1); }
.wk-vc-card:hover { transform: translateY(-4px); border-color: #E8A15B; box-shadow: 0 16px 30px rgba(23,59,42,.14); }
.wk-vc-card:hover::after { transform: scaleX(1); }
.wk-vc-em {
  flex: 0 0 auto; width: 38px; height: 38px; border-radius: 50%; display: grid; place-items: center; font-size: 1.1rem;
  background: #173B2A; border: 2px solid #C89B3C; transition: transform .4s;
}
.wk-vc-card:hover .wk-vc-em { transform: rotate(-10deg) scale(1.12); }
.wk-vc-card h3 { margin: 0 0 3px; font-size: .68rem; font-weight: 700; line-height: 1.35; letter-spacing: .14em; text-transform: uppercase; color: #C47A2C; }
.wk-vc-card p { margin: 0; font-size: .8rem; line-height: 1.5; color: #2B241D; }

/* ---- closing line ---- */
.wk-vc-close {
  margin: 16px 0 0; padding: 6px 0 6px 16px; border-left: 3px solid #C89B3C;
  font-family: 'Playfair Display', Georgia, serif; font-style: italic; font-size: 1.02rem; line-height: 1.5; color: #173B2A;
}
.wk-vc-close b { color: #E8A15B; font-weight: 700; }

/* ---- image collage: taped polaroid photos ---- */
.wk-vc-visual { position: relative; height: clamp(320px, 30vw, 420px); }
.wk-vc-pol {
  --r: 0deg; position: absolute; z-index: 1; margin: 0; padding: 10px 10px 8px;
  background: #fff; border-radius: 6px; border: 1px solid rgba(200,155,60,.4);
  box-shadow: 0 16px 34px rgba(23,59,42,.22), 0 2px 6px rgba(23,59,42,.12);
  transform: rotate(var(--r)); animation: wkVcSway 7s ease-in-out infinite;
  transition: box-shadow .35s;
}
.wk-vc-pol::before {             /* gold tape */
  content: ""; position: absolute; z-index: 2; top: -11px; left: 50%; width: 66px; height: 20px; margin-left: -33px;
  background: rgba(232,161,91,.62); border-left: 1px dashed rgba(255,253,248,.7); border-right: 1px dashed rgba(255,253,248,.7);
  transform: rotate(-4deg);
}
.wk-vc-pol:hover { animation: none; transform: rotate(0deg) scale(1.05); z-index: 6; box-shadow: 0 24px 46px rgba(23,59,42,.32); }
.wk-vc-ph { aspect-ratio: 4 / 3; overflow: hidden; border-radius: 3px; background: linear-gradient(160deg, #1F4A36, #0B2F22); }
.wk-vc-ph img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .6s; }
.wk-vc-pol:hover .wk-vc-ph img { transform: scale(1.08); }
.wk-vc-pol figcaption {
  padding: 8px 4px 2px; text-align: center; font-family: 'Playfair Display', Georgia, serif; font-style: italic;
  font-size: .82rem; line-height: 1.2; color: #173B2A;
}
.wk-vc-p1 { --r: -4deg; top: 0; left: 0; width: 60%; z-index: 2; }
.wk-vc-p2 { --r: 4deg; top: 19%; right: 0; width: 48%; z-index: 3; animation-delay: -2.5s; }
.wk-vc-p3 { --r: -2deg; left: 13%; bottom: 0; width: 46%; z-index: 4; animation-delay: -4.5s; }
.wk-vc-p1 .wk-vc-ph img { object-position: center 50%; }
.wk-vc-p2 .wk-vc-ph img { object-position: 75% 45%; }
.wk-vc-p3 .wk-vc-ph img { object-position: 25% 70%; }
@keyframes wkVcSway { 0%,100% { transform: rotate(var(--r)) translateY(0); } 50% { transform: rotate(var(--r)) translateY(-6px); } }
.wk-vc-stamp {
  position: absolute; z-index: 7; top: -22px; right: -8px; width: 80px; height: 80px; border-radius: 50%;
  background: #173B2A; border: 1.5px solid #C89B3C; box-shadow: 0 10px 22px rgba(0,0,0,.32); display: grid; place-items: center;
}
.wk-vc-stamp svg { position: absolute; inset: 0; width: 100%; height: 100%; animation: wkVcSpin 20s linear infinite; }
.wk-vc-stamp text { font: 700 8.2px 'Lato', sans-serif; fill: #E8A15B; }
.wk-vc-stamp i { font-style: normal; font-size: 1.2rem; line-height: 1; }
@keyframes wkVcSpin { to { transform: rotate(360deg); } }
/* polaroids drop in when the section scrolls into view */
.wk-vc.wk-js .wk-vc-pol { opacity: 0; }
.wk-vc.wk-js.is-in .wk-vc-pol { opacity: 1; animation: wkVcDrop .9s cubic-bezier(.2,.8,.3,1) backwards, wkVcSway 7s ease-in-out 1.2s infinite; }
.wk-vc.wk-js.is-in .wk-vc-p1 { animation-delay: .15s, 1.2s; }
.wk-vc.wk-js.is-in .wk-vc-p2 { animation-delay: .35s, 1.4s; }
.wk-vc.wk-js.is-in .wk-vc-p3 { animation-delay: .55s, 1.6s; }
.wk-vc.wk-js.is-in .wk-vc-pol:hover { animation: none; opacity: 1; transform: rotate(0deg) scale(1.05); }
@keyframes wkVcDrop { from { opacity: 0; transform: translateY(-40px) rotate(calc(var(--r) * 3)) scale(.9); } to { opacity: 1; transform: rotate(var(--r)); } }

/* entrance (scroll reveal) */
.wk-vc.wk-js [data-vc] { opacity: 0; transform: translateY(18px); transition: opacity .7s ease, transform .7s cubic-bezier(.2,.7,.2,1); transition-delay: calc(var(--i, 0) * 90ms); }
.wk-vc.wk-js.is-in [data-vc] { opacity: 1; transform: none; }
.wk-vc.wk-js h2::after { transform: scaleX(0); transform-origin: left; transition: transform 1.1s .35s cubic-bezier(.2,.7,.2,1); }
.wk-vc.wk-js.is-in h2::after { transform: scaleX(1); }
@media (prefers-reduced-motion: reduce) {
  .wk-vc *, .wk-vc *::before, .wk-vc *::after { animation: none !important; transition: none !important; }
  .wk-vc.wk-js [data-vc], .wk-vc.wk-js .wk-vc-pol { opacity: 1; transform: rotate(var(--r)); }
  .wk-vc.wk-js h2::after { transform: none; }
}

/* responsive */
@media (max-width: 1100px) {
  .wk-vc-hl { grid-template-columns: 1fr; gap: 10px; }
  .wk-vc-card { flex-direction: row; align-items: center; gap: 12px; padding: 12px 16px 12px 14px; }
  .wk-vc-card:nth-child(n) { border-radius: 28px 8px 28px 8px; }
  .wk-vc-card:nth-child(2) { border-radius: 8px 28px 8px 28px; }
}
@media (max-width: 900px) {
  .wk-vc .wk-vc-grid.container { grid-template-columns: minmax(0, 1fr); }
  .wk-vc-visual { height: 360px; max-width: 500px; width: 100%; margin: 14px auto 14px; }
}
@media (max-width: 560px) {
  .wk-vc-visual { height: 310px; }
  .wk-vc-pol { padding: 7px 7px 6px; }
  .wk-vc-pol figcaption { font-size: .72rem; padding-top: 6px; }
  .wk-vc-stamp { width: 66px; height: 66px; right: -2px; top: -18px; }
  .wk-vc .wk-eyebrow { letter-spacing: .16em; font-size: .68rem; gap: 10px; }
  .wk-vc .wk-eyebrow::before { width: 26px; }
}
</style>

<!-- BANNER -->
<section class="con-banner" role="img" aria-label="Contact Wildlife Kingdom"></section>

<!-- ============ CONTACT HERO / SECTION 1 ============ -->
<?php
// Section 1 image — replace with your own photo
$wkCtImg = '../assets/images/static/contact-hero.webp';
?>
<section class="section wk-ct">
  <div class="container wk-ct-grid">

    <div class="wk-ct-copy">
      <span class="wk-eyebrow">Contact Wildlife Kingdom</span>
      <h1><span class="wk-ct-black">Let’s Connect</span> <span class="wk-ct-accent">With the Wild</span></h1>
      <p class="wk-ct-lead">Have a question about Wildlife Kingdom, planning a visit, or looking for more information about our animals, habitats, events, and visitor experiences? We’re here to help.</p>
      <p>Whether you’re visiting with family and friends, planning a group visit, interested in upcoming wildlife events, or simply want to know more about Wildlife Kingdom, our team is happy to assist.</p>

      <div class="wk-ct-trail" aria-label="Your questions. Your plans. Our team.">
        <div class="wk-ct-stop"><span class="wk-ct-dot">1</span><b>Your questions.</b></div>
        <span class="wk-ct-link" aria-hidden="true"></span>
        <div class="wk-ct-stop"><span class="wk-ct-dot">2</span><b>Your plans.</b></div>
        <span class="wk-ct-link" aria-hidden="true"></span>
        <div class="wk-ct-stop"><span class="wk-ct-dot">3</span><b>Our team.</b></div>
      </div>
    </div>

    <div class="wk-ct-visual">
      <div class="wk-ct-visual-img">
        <img src="<?php echo htmlspecialchars($wkCtImg, ENT_QUOTES); ?>" alt="Wildlife at Wildlife Kingdom" loading="lazy" onerror="this.style.display='none'">
        <div class="wk-ct-chips" aria-hidden="true"><span>🦁 Animals</span><span>🌿 Habitats</span><span>🎟️ Events</span></div>
      </div>

      <div class="wk-ct-stamp" aria-hidden="true">
        <svg viewBox="0 0 100 100">
          <defs><path id="wkCtCirc" d="M50,50 m-38,0 a38,38 0 1,1 76,0 a38,38 0 1,1 -76,0"/></defs>
          <text><textPath href="#wkCtCirc" textLength="236" lengthAdjust="spacing">WILDLIFE KINGDOM • GET IN TOUCH • </textPath></text>
        </svg>
        <i>🐾</i>
      </div>
    </div>

  </div>
</section>

<!-- ============ CONTACT / SECTION 2 — GET IN TOUCH ============ -->
<section class="section wk-gt" role="region" aria-label="Get in touch">
  <div class="container wk-gt-grid">

    <div class="wk-gt-left">
      <div data-gt style="--i:0"><span class="wk-eyebrow">Before You Reach the Wild</span></div>
      <h2 data-gt style="--i:1">Planning a Visit to <span class="wk-gt-accent">Wildlife Kingdom?</span></h2>
      <div class="wk-gt-intro" data-gt style="--i:2">
        <p>A great wildlife experience begins before you enter the gates. Whether you are visiting with family, friends, children, or a larger group, a little planning can help you make the most of your time at Wildlife Kingdom.</p>
        <p>From choosing the right day for your visit to exploring animals, habitats, events and other experiences, knowing what to expect can make your day more comfortable and enjoyable.</p>
      </div>

      <div class="wk-gt-help" data-gt style="--i:3">
        <span class="wk-gt-pill">We’re here to help</span>
        <div class="wk-gt-crows">
          <div class="wk-gt-crow">
            <span class="wk-gt-cico" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 21s-7-5.2-7-11a7 7 0 0 1 14 0c0 5.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.6"/></svg></span>
            <span><small>Visit us</small><b>123 Rainforest Road, Wildlife County</b></span>
          </div>
          <a class="wk-gt-crow" href="tel:+910000000000">
            <span class="wk-gt-cico" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg></span>
            <span><small>Call us</small><b>+91 00000 00000</b></span>
          </a>
          <a class="wk-gt-crow" href="mailto:hello@wildlifekingdom.com">
            <span class="wk-gt-cico" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg></span>
            <span><small>Email us</small><b>hello@wildlifekingdom.com</b></span>
          </a>
          <div class="wk-gt-crow">
            <span class="wk-gt-cico" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span>
            <span><small>Open daily</small><b>9:00 AM – 6:00 PM</b></span>
          </div>
        </div>
      </div>
    </div>

    <div class="wk-gt-formwrap">
      <div class="wk-gt-card">
        <span class="wk-gt-tag">Enquiry Desk</span>
        <h3 class="wk-gt-ftitle">Send Us a Message</h3>
        <p class="wk-gt-fsub">Tell us what you need help with and our team will get back to you with the information you need.</p>

        <?php if ($formMessage): ?>
          <div class="form-msg <?php echo htmlspecialchars($formStatus); ?>" role="status"><?php echo htmlspecialchars($formMessage); ?></div>
        <?php endif; ?>

        <form class="wk-gt-form" method="POST" action="contact.php">
          <div class="wk-gt-field">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" placeholder="Your name" value="<?php echo wk_old($old, 'name'); ?>" required>
          </div>

          <div class="wk-gt-field">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="you@example.com" value="<?php echo wk_old($old, 'email'); ?>" required>
          </div>

          <div class="wk-gt-field">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" placeholder="+91 XXXXX XXXXX" value="<?php echo wk_old($old, 'phone'); ?>">
          </div>

          <div class="wk-gt-field">
            <label for="subject">Enquiry Type</label>
            <select id="subject" name="subject">
              <?php
              $types = ['General Enquiry', 'Visit Information', 'Ticket Enquiry', 'Group Visit', 'Events & Experiences', 'Feedback', 'Other'];
              $picked = $old['subject'] ?? 'General Enquiry';
              foreach ($types as $t):
              ?>
                <option value="<?php echo htmlspecialchars($t, ENT_QUOTES); ?>"<?php echo $picked === $t ? ' selected' : ''; ?>><?php echo htmlspecialchars($t); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="wk-gt-field is-full">
            <label for="message">Your Message</label>
            <textarea id="message" name="message" placeholder="Tell us more..." required><?php echo wk_old($old, 'message'); ?></textarea>
          </div>

          <button type="submit" class="wk-gt-btn">Send Enquiry <span aria-hidden="true">→</span></button>
        </form>
      </div>
    </div>

  </div>
</section>
<script>
(function () {
  var sec = document.querySelector('.wk-gt');
  if (!sec) return;
  sec.classList.add('wk-js');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (e) {
      if (e[0].isIntersecting) { sec.classList.add('is-in'); io.disconnect(); }
    }, { threshold: 0.12 });
    io.observe(sec);
  } else { sec.classList.add('is-in'); }
})();
</script>

<!-- ============ CONTACT / SECTION 3 — YOUR VOICE MATTERS ============ -->
<?php
// Section 3 images — replace with your own photos (all three: 800 x 600 px, 4:3 landscape)
$wkVcImg1 = '../assets/images/static/contact-feedback-1.webp';
$wkVcImg2 = '../assets/images/static/contact-feedback-2.webp';
$wkVcImg3 = '../assets/images/static/contact-feedback-3.webp';
$wkVcFallback = '../assets/images/static/contact-banner.webp';   // shown if a photo above is missing
$wkVcOnErr = "if(!this.dataset.f){this.dataset.f=1;this.src='" . htmlspecialchars($wkVcFallback, ENT_QUOTES) . "';}else{this.style.display='none';}";
?>
<section class="section wk-vc" role="region" aria-label="Your voice matters">
  <div class="container wk-vc-grid">

    <div class="wk-vc-copy">
      <div data-vc style="--i:0"><span class="wk-eyebrow">We Value Your Voice</span></div>
      <h2 data-vc style="--i:1">Tell Us <span class="wk-vc-accent">What You Think</span></h2>
      <div data-vc style="--i:2">
        <p class="wk-vc-txt">Every visit is different, and every visitor sees Wildlife Kingdom in their own way. Your feedback helps us understand what you enjoyed, what could be improved, and what you would love to experience next.</p>
        <p class="wk-vc-txt">Whether you have a suggestion, a memorable moment to share, or an idea that could make the wildlife experience better, we would love to hear from you.</p>
      </div>

      <div class="wk-vc-hl">
        <div class="wk-vc-card" data-vc style="--i:3">
          <span class="wk-vc-em" aria-hidden="true">💬</span>
          <div><h3>Share Your Experience</h3><p>Tell us what made your visit memorable.</p></div>
        </div>
        <div class="wk-vc-card" data-vc style="--i:4">
          <span class="wk-vc-em" aria-hidden="true">💡</span>
          <div><h3>Share an Idea</h3><p>Have a suggestion for improving the visitor experience? Let us know.</p></div>
        </div>
        <div class="wk-vc-card" data-vc style="--i:5">
          <span class="wk-vc-em" aria-hidden="true">❤️</span>
          <div><h3>Share Your Feedback</h3><p>Your thoughts help us create a better experience for future visitors.</p></div>
        </div>
      </div>

      <p class="wk-vc-close" data-vc style="--i:6"><b>Your experience matters.</b> Your ideas can make the next visit even better.</p>
    </div>

    <div class="wk-vc-visual">
      <figure class="wk-vc-pol wk-vc-p1">
        <div class="wk-vc-ph"><img src="<?php echo htmlspecialchars($wkVcImg1, ENT_QUOTES); ?>" alt="Visitors enjoying Wildlife Kingdom" loading="lazy" onerror="<?php echo $wkVcOnErr; ?>"></div>
        <figcaption>Share your experience</figcaption>
      </figure>
      <figure class="wk-vc-pol wk-vc-p2">
        <div class="wk-vc-ph"><img src="<?php echo htmlspecialchars($wkVcImg2, ENT_QUOTES); ?>" alt="A wildlife moment at Wildlife Kingdom" loading="lazy" onerror="<?php echo $wkVcOnErr; ?>"></div>
        <figcaption>Share an idea</figcaption>
      </figure>
      <figure class="wk-vc-pol wk-vc-p3">
        <div class="wk-vc-ph"><img src="<?php echo htmlspecialchars($wkVcImg3, ENT_QUOTES); ?>" alt="Wildlife at Wildlife Kingdom" loading="lazy" onerror="<?php echo $wkVcOnErr; ?>"></div>
        <figcaption>Share your feedback</figcaption>
      </figure>
      <div class="wk-vc-stamp" aria-hidden="true">
        <svg viewBox="0 0 100 100">
          <defs><path id="wkVcCirc" d="M50,50 m-38,0 a38,38 0 1,1 76,0 a38,38 0 1,1 -76,0"/></defs>
          <text><textPath href="#wkVcCirc" textLength="236" lengthAdjust="spacing">WE VALUE YOUR VOICE • YOUR VOICE MATTERS • </textPath></text>
        </svg>
        <i>💬</i>
      </div>
    </div>

  </div>
</section>
<script>
(function () {
  var sec = document.querySelector('.wk-vc');
  if (!sec) return;
  sec.classList.add('wk-js');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (e) {
      if (e[0].isIntersecting) { sec.classList.add('is-in'); io.disconnect(); }
    }, { threshold: 0.1 });
    io.observe(sec);
  } else { sec.classList.add('is-in'); }
})();
</script>

<style>
/* =====================================================
   CONTACT — SECTION 4 ("wk-mp") Find Us in the Wild
   bg #173B2A (same green as Section 2), no colour glows
   Left: 3-tile image collage | Right: copy + map card + directions
   ===================================================== */
section.wk-mp.wk-mp {
  background: #173B2A !important; position: relative; overflow: hidden;
  padding: clamp(28px, 3.6vw, 46px) 0;
  font-family: 'Lato', 'Segoe UI', sans-serif; color: #FFFDF8;
}
.wk-mp, .wk-mp * { box-sizing: border-box; }
.wk-mp .wk-mp-grid.container {
  max-width: 100%; padding-left: clamp(16px, 2.5vw, 36px); padding-right: clamp(16px, 2.5vw, 36px);
  display: grid; grid-template-columns: clamp(300px, 38vw, 540px) minmax(0, 1fr);
  gap: clamp(28px, 4vw, 60px); align-items: stretch;
}

/* ---- left: image collage (3 tiles) ---- */
.wk-mp-collage { display: grid; grid-template-columns: 1.1fr 1fr; grid-template-rows: 1fr 1fr; gap: 10px; min-height: 340px; }
.wk-mp-tile {
  position: relative; overflow: hidden; margin: 0; border: 1.5px solid rgba(232,161,91,.8);
  background: linear-gradient(160deg, #1F4A36, #0B2F22); box-shadow: 0 16px 32px rgba(0,0,0,.28);
}
.wk-mp-t1 { grid-row: 1 / 3; border-radius: 80px 14px 80px 14px; }
.wk-mp-t2 { border-radius: 14px 56px 14px 14px; }
.wk-mp-t3 { border-radius: 14px 14px 56px 14px; }
.wk-mp-tile img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .7s ease; }
.wk-mp-t1 img { object-position: 40% 50%; }
.wk-mp-t2 img { object-position: 80% 30%; }
.wk-mp-t3 img { object-position: 20% 80%; }
.wk-mp-tile:hover img { transform: scale(1.08); }
.wk-mp-tile::before { content: ""; position: absolute; inset: 0; z-index: 1; pointer-events: none; background: linear-gradient(to top, rgba(11,47,34,.75), rgba(11,47,34,0) 52%); }
.wk-mp-tile figcaption {
  position: absolute; z-index: 2; left: 14px; bottom: 12px; right: 12px; display: flex; align-items: center; gap: 8px;
  font-family: 'Playfair Display', Georgia, serif; font-style: italic; font-size: .92rem; font-weight: 700; line-height: 1.2; color: #FFFDF8;
  transition: transform .35s;
}
.wk-mp-tile figcaption::before { content: ""; flex: 0 0 auto; width: 9px; height: 9px; border-radius: 50%; background: #E8A15B; box-shadow: 0 0 0 3px rgba(232,161,91,.3); }
.wk-mp-t1 figcaption { left: 26px; bottom: 20px; font-size: 1rem; }
.wk-mp-tile:hover figcaption { transform: translateY(-4px); }

/* ---- right: copy ---- */
.wk-mp-copy { min-width: 0; display: flex; flex-direction: column; justify-content: center; }
.wk-mp .wk-eyebrow {
  display: inline-flex; align-items: center; gap: 12px; margin-bottom: 12px; align-self: flex-start;
  font-size: .72rem; font-weight: 700; line-height: 1; letter-spacing: .24em; text-transform: uppercase; color: #FFFDF8;
}
.wk-mp .wk-eyebrow::before { content: ""; width: 36px; height: 2px; background: #E8A15B; }
.wk-mp h2 {
  margin: 0 0 10px; width: 100%; font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.3rem, 2.2vw, 1.9rem); font-weight: 700; line-height: 1.15; letter-spacing: -.005em; color: #FFFDF8;
}
.wk-mp h2 .wk-mp-accent { color: #E8A15B; font-style: italic; }
.wk-mp h2::after { content: ""; display: block; width: 100%; height: 2px; margin-top: 10px; background: linear-gradient(90deg, #C89B3C, rgba(200,155,60,0)); }
.wk-mp-txt { margin: 0 0 14px; font-size: .92rem; line-height: 1.65; color: rgba(255,253,248,.88); text-align: justify; hyphens: manual; }

/* ---- map card ---- */
.wk-mp-card {
  padding: 12px 16px 14px; border: 1.5px solid rgba(200,155,60,.8); border-radius: 12px 38px 12px 38px;
  background: linear-gradient(160deg, rgba(255,253,248,.09), rgba(255,253,248,.03));
}
.wk-mp-pill {
  display: inline-flex; align-items: center; gap: 9px; margin-bottom: 9px; padding: 6px 14px 6px 9px;
  font-size: .64rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #FFFDF8;
  border: 1px solid rgba(232,161,91,.75); border-radius: 999px; background: rgba(11,47,34,.55);
}
.wk-mp-pill::before { content: ""; width: 11px; height: 11px; border-radius: 50%; background: #E8A15B; animation: wkMpDot 2s ease-in-out infinite; }
@keyframes wkMpDot { 0%,100% { box-shadow: 0 0 0 3px rgba(232,161,91,.3); } 50% { box-shadow: 0 0 0 7px rgba(232,161,91,.08); } }
.wk-mp-map { height: clamp(120px, 11vw, 150px); overflow: hidden; border: 1px solid rgba(232,161,91,.6); border-radius: 8px 26px 8px 26px; background: #0B2F22; }
.wk-mp-map iframe { width: 100%; height: 100%; border: 0; display: block; filter: saturate(.9); }
.wk-mp-foot { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px 16px; margin-top: 12px; }
.wk-mp-loc { min-width: 0; flex: 1 1 220px; }
.wk-mp-loc b { display: block; font-family: 'Playfair Display', Georgia, serif; font-size: 1.02rem; font-weight: 700; line-height: 1.2; color: #FFFDF8; }
.wk-mp-loc span { display: block; margin-top: 2px; font-size: .86rem; line-height: 1.4; color: #FFFDF8; }
.wk-mp-loc span i { font-style: normal; display: inline-block; animation: wkMpBob 1.8s ease-in-out infinite; }
@keyframes wkMpBob { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-3px); } }
.wk-mp-loc em { display: block; margin-top: 3px; font-family: 'Playfair Display', Georgia, serif; font-size: .82rem; line-height: 1.4; color: #E8A15B; }
.wk-mp-btn {
  flex: 0 0 auto; display: inline-flex; align-items: center; gap: 10px; padding: 11px 20px; border-radius: 999px; text-decoration: none;
  font-size: .72rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: #173B2A; background: #E8A15B;
  box-shadow: 0 8px 18px rgba(0,0,0,.25); transition: background .3s, transform .3s, box-shadow .3s;
}
.wk-mp-btn span { display: inline-block; font-size: 1rem; line-height: 1; transition: transform .3s; }
.wk-mp-btn:hover { background: #FFFDF8; transform: translateY(-2px); box-shadow: 0 12px 22px rgba(0,0,0,.32); }
.wk-mp-btn:hover span { transform: translateX(5px); }
.wk-mp-btn:focus-visible { outline: 3px solid #FFFDF8; outline-offset: 3px; }

/* entrance (scroll reveal) */
.wk-mp.wk-js [data-mp] { opacity: 0; transform: translateY(16px); transition: opacity .6s ease, transform .6s cubic-bezier(.2,.7,.2,1); transition-delay: calc(var(--i, 0) * 80ms); }
.wk-mp.wk-js.is-in [data-mp] { opacity: 1; transform: none; }
.wk-mp.wk-js h2::after { transform: scaleX(0); transform-origin: left; transition: transform 1.1s .35s cubic-bezier(.2,.7,.2,1); }
.wk-mp.wk-js.is-in h2::after { transform: scaleX(1); }
.wk-mp.wk-js .wk-mp-tile { opacity: 0; transform: scale(.94); }
.wk-mp.wk-js.is-in .wk-mp-tile { opacity: 1; transform: none; transition: opacity .7s ease, transform .8s cubic-bezier(.2,.7,.2,1); }
.wk-mp.wk-js.is-in .wk-mp-t1 { transition-delay: .1s; }
.wk-mp.wk-js.is-in .wk-mp-t2 { transition-delay: .3s; }
.wk-mp.wk-js.is-in .wk-mp-t3 { transition-delay: .5s; }
@media (prefers-reduced-motion: reduce) {
  .wk-mp *, .wk-mp *::before, .wk-mp *::after { animation: none !important; transition: none !important; }
  .wk-mp.wk-js [data-mp], .wk-mp.wk-js .wk-mp-tile { opacity: 1; transform: none; }
  .wk-mp.wk-js h2::after { transform: none; }
}

/* responsive */
@media (max-width: 900px) {
  .wk-mp .wk-mp-grid.container { grid-template-columns: minmax(0, 1fr); }
  .wk-mp-copy { order: -1; }
  .wk-mp-collage { min-height: 0; height: 340px; max-width: 560px; width: 100%; margin: 0 auto; }
}
@media (max-width: 560px) {
  .wk-mp-collage { height: 290px; }
  .wk-mp-t1 { border-radius: 56px 12px 56px 12px; }
  .wk-mp-t2 { border-radius: 12px 40px 12px 12px; }
  .wk-mp-t3 { border-radius: 12px 12px 40px 12px; }
  .wk-mp-tile figcaption { font-size: .8rem; left: 10px; bottom: 9px; }
  .wk-mp-t1 figcaption { left: 18px; bottom: 14px; }
  .wk-mp-btn { width: 100%; justify-content: center; }
  .wk-mp .wk-eyebrow { letter-spacing: .16em; font-size: .68rem; gap: 10px; }
  .wk-mp .wk-eyebrow::before { width: 26px; }
}
</style>

<!-- ============ CONTACT / SECTION 4 — FIND US IN THE WILD ============ -->
<?php
// Section 4 — set your final address here (used for the map, the pin text and the directions button)
$wkAddress = '123 Rainforest Road, Wildlife County';
$wkMapSrc  = 'https://www.google.com/maps?q=' . rawurlencode($wkAddress) . '&output=embed';
$wkDirUrl  = 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode($wkAddress);
// Section 4 images — replace with your own photos (1: 800 x 1200, 2 & 3: 800 x 700)
$wkMpImg1 = '../assets/images/static/contact-find-1.webp';
$wkMpImg2 = '../assets/images/static/contact-find-2.webp';
$wkMpImg3 = '../assets/images/static/contact-find-3.webp';
$wkMpFallback = '../assets/images/static/contact-banner.webp';   // shown if a photo above is missing
$wkMpOnErr = "if(!this.dataset.f){this.dataset.f=1;this.src='" . htmlspecialchars($wkMpFallback, ENT_QUOTES) . "';}else{this.style.display='none';}";
?>
<section class="section wk-mp" role="region" aria-label="Find Wildlife Kingdom">
  <div class="container wk-mp-grid">

    <div class="wk-mp-collage" data-mp style="--i:0">
      <figure class="wk-mp-tile wk-mp-t1">
        <img src="<?php echo htmlspecialchars($wkMpImg1, ENT_QUOTES); ?>" alt="Into the wild at Wildlife Kingdom" loading="lazy" onerror="<?php echo $wkMpOnErr; ?>">
        <figcaption>Into the Wild</figcaption>
      </figure>
      <figure class="wk-mp-tile wk-mp-t2">
        <img src="<?php echo htmlspecialchars($wkMpImg2, ENT_QUOTES); ?>" alt="Meet the wildlife" loading="lazy" onerror="<?php echo $wkMpOnErr; ?>">
        <figcaption>Meet the Wildlife</figcaption>
      </figure>
      <figure class="wk-mp-tile wk-mp-t3">
        <img src="<?php echo htmlspecialchars($wkMpImg3, ENT_QUOTES); ?>" alt="Explore the habitats" loading="lazy" onerror="<?php echo $wkMpOnErr; ?>">
        <figcaption>Explore the Habitats</figcaption>
      </figure>
    </div>

    <div class="wk-mp-copy">
      <div data-mp style="--i:0"><span class="wk-eyebrow">Find Wildlife Kingdom</span></div>
      <h2 data-mp style="--i:1">Your Journey to the Wild <span class="wk-mp-accent">Starts Here</span></h2>
      <p class="wk-mp-txt" data-mp style="--i:2">Ready to explore Wildlife Kingdom? Find us on the map and plan your route before your visit. Whether you’re travelling with family, friends, or a group, knowing your way to the wild makes the journey easier.</p>

      <div class="wk-mp-card" data-mp style="--i:3">
        <span class="wk-mp-pill">Our Location</span>
        <div class="wk-mp-map">
          <iframe src="<?php echo htmlspecialchars($wkMapSrc, ENT_QUOTES); ?>" title="Wildlife Kingdom location map" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
        </div>
        <div class="wk-mp-foot">
          <div class="wk-mp-loc">
            <b>Wildlife Kingdom</b>
            <span><i aria-hidden="true">📍</i> <?php echo htmlspecialchars($wkAddress, ENT_QUOTES); ?></span>
            <em>Plan your route. Find your way. Start exploring.</em>
          </div>
          <a class="wk-mp-btn" href="<?php echo htmlspecialchars($wkDirUrl, ENT_QUOTES); ?>" target="_blank" rel="noopener">Get Directions <span aria-hidden="true">→</span></a>
        </div>
      </div>
    </div>

  </div>
</section>
<script>
(function () {
  var sec = document.querySelector('.wk-mp');
  if (!sec) return;
  sec.classList.add('wk-js');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (e) {
      if (e[0].isIntersecting) { sec.classList.add('is-in'); io.disconnect(); }
    }, { threshold: 0.1 });
    io.observe(sec);
  } else { sec.classList.add('is-in'); }
})();
</script>

<style>
/* =====================================================
   CONTACT — SECTION 5 ("wk-fq") FAQ
   bg #FFFDF8 (same cream as Sections 1 & 3), no colour glows
   Left: arch image | Right: compact accordion (6 FAQs)
   ===================================================== */
section.wk-fq.wk-fq {
  background: #FFFDF8 !important; position: relative; overflow: hidden;
  padding: clamp(28px, 3.6vw, 46px) 0 clamp(38px, 4.6vw, 58px);
  font-family: 'Lato', 'Segoe UI', sans-serif;
}
.wk-fq, .wk-fq * { box-sizing: border-box; }
.wk-fq .wk-fq-wrap.container { max-width: 100%; padding-left: clamp(16px, 2.5vw, 36px); padding-right: clamp(16px, 2.5vw, 36px); }
.wk-fq-grid {
  display: grid; grid-template-columns: clamp(280px, 32vw, 450px) minmax(0, 1fr);
  gap: clamp(28px, 4vw, 64px); align-items: stretch;      /* image box = same top & bottom as the FAQ list */
}

/* ---- left: image box (thin orange border + solid green offset block + "?" badge) ---- */
.wk-fq-visual { position: relative; min-height: 340px; }
.wk-fq-visual::before {          /* solid dark-green block behind, offset down-right */
  content: ""; position: absolute; z-index: 0; top: 18px; left: 18px; right: -18px; bottom: -18px;
  background: #173B2A; border-radius: 24px 50px 24px 16px;
}
.wk-fq-img {
  position: absolute; z-index: 1; inset: 0; overflow: hidden; border-radius: 24px 50px 24px 16px;
  border: 1.5px solid #E8A15B; background: #0B2F22;
}
.wk-fq-img img { width: 100%; height: 100%; object-fit: cover; object-position: center 45%; display: block; transition: transform .8s ease; }
.wk-fq-visual:hover .wk-fq-img img { transform: scale(1.05); }
.wk-fq-badge {                   /* orange "?" badge, top-right */
  position: absolute; z-index: 4; top: 8px; right: -34px; width: 70px; height: 70px; border-radius: 50%; display: grid; place-items: center;
  background: #E8A15B; color: #173B2A; box-shadow: 0 0 0 4px #173B2A, 0 0 0 5.5px #E8A15B;
  font-family: 'Playfair Display', Georgia, serif; font-size: 1.9rem; font-weight: 700; line-height: 1;
}
.wk-fq-badge::before { content: ""; position: absolute; inset: -20px; border-radius: 50%; border: 1px solid rgba(232,161,91,.45); animation: wkFqRing 3s ease-in-out infinite; }
@keyframes wkFqRing { 0%,100% { transform: scale(1); opacity: .9; } 50% { transform: scale(1.12); opacity: .35; } }

/* ---- top: centred full-width heading ---- */
.wk-fq-head { width: 100%; text-align: center; margin-bottom: 22px; }
.wk-fq .wk-eyebrow {
  display: inline-flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 12px;
  font-size: .72rem; font-weight: 700; line-height: 1; letter-spacing: .24em; text-transform: uppercase; color: #173B2A;
}
.wk-fq .wk-eyebrow::before, .wk-fq .wk-eyebrow::after { content: ""; width: 36px; height: 2px; background: #C89B3C; }
.wk-fq h2 {
  margin: 0; width: 100%; text-align: center; font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.5rem, 2.8vw, 2.3rem); font-weight: 700; line-height: 1.12; letter-spacing: -.005em; color: #173B2A;
}
.wk-fq h2 .wk-fq-accent { color: #E8A15B; font-style: italic; }
.wk-fq h2::after { content: ""; display: block; width: 100%; height: 2px; margin-top: 12px; background: linear-gradient(90deg, rgba(200,155,60,0), #C89B3C 50%, rgba(200,155,60,0)); }
.wk-fq-copy { min-width: 0; }
.wk-fq-list { display: grid; gap: 8px; }
.wk-fq-item {
  background: #fff; border: 1px solid rgba(200,155,60,.55); border-radius: 12px 30px 12px 30px; overflow: hidden;
  transition: background .3s, border-color .3s, box-shadow .3s;
}
.wk-fq-item:hover { border-color: #E8A15B; box-shadow: 0 8px 18px rgba(23,59,42,.1); }
.wk-fq-item summary {
  list-style: none; cursor: pointer; display: flex; align-items: center; gap: 12px; padding: 10px 14px 10px 20px;
}
.wk-fq-item summary::-webkit-details-marker { display: none; }
.wk-fq-item summary:focus-visible { outline: 3px solid #E8A15B; outline-offset: -3px; }
.wk-fq-sum { flex: 1; min-width: 0; }
.wk-fq-tag { display: block; margin-bottom: 2px; font-size: .58rem; font-weight: 700; letter-spacing: .22em; text-transform: uppercase; color: #C47A2C; }
.wk-fq-q { display: block; font-family: 'Playfair Display', Georgia, serif; font-size: .95rem; font-weight: 700; line-height: 1.3; color: #173B2A; }
.wk-fq-ico {
  position: relative; flex: 0 0 auto; width: 28px; height: 28px; border-radius: 50%; border: 1.5px solid #C89B3C; background: #FFFDF8;
  transition: transform .35s, background .3s, border-color .3s;
}
.wk-fq-ico::before, .wk-fq-ico::after { content: ""; position: absolute; left: 50%; top: 50%; background: #173B2A; border-radius: 2px; transform: translate(-50%, -50%); transition: background .3s; }
.wk-fq-ico::before { width: 11px; height: 2px; }
.wk-fq-ico::after { width: 2px; height: 11px; }
.wk-fq-a { margin: 0; padding: 0 20px 14px; font-size: .86rem; line-height: 1.6; color: #2B241D; }
.wk-fq-item[open] { background: #173B2A; border-color: #173B2A; box-shadow: 0 12px 24px rgba(23,59,42,.22); }
.wk-fq-item[open] .wk-fq-tag { color: #E8A15B; }
.wk-fq-item[open] .wk-fq-q { color: #FFFDF8; }
.wk-fq-item[open] .wk-fq-ico { transform: rotate(135deg); background: #E8A15B; border-color: #E8A15B; }
.wk-fq-item[open] .wk-fq-a { color: rgba(255,253,248,.9); animation: wkFqIn .4s ease; }
@keyframes wkFqIn { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: none; } }

/* entrance (scroll reveal) */
.wk-fq.wk-js [data-fq] { opacity: 0; transform: translateY(16px); transition: opacity .6s ease, transform .6s cubic-bezier(.2,.7,.2,1); transition-delay: calc(var(--i, 0) * 70ms); }
.wk-fq.wk-js.is-in [data-fq] { opacity: 1; transform: none; }
.wk-fq.wk-js h2::after { transform: scaleX(0); transform-origin: center; transition: transform 1.1s .3s cubic-bezier(.2,.7,.2,1); }
.wk-fq.wk-js.is-in h2::after { transform: scaleX(1); }
.wk-fq.wk-js .wk-fq-visual { opacity: 0; }
.wk-fq.wk-js.is-in .wk-fq-visual { opacity: 1; animation: wkFqSlide 1s .15s cubic-bezier(.2,.7,.2,1) backwards; }
@keyframes wkFqSlide { from { opacity: 0; transform: translateX(-46px); } to { opacity: 1; transform: none; } }
@media (prefers-reduced-motion: reduce) {
  .wk-fq *, .wk-fq *::before, .wk-fq *::after { animation: none !important; transition: none !important; }
  .wk-fq.wk-js [data-fq], .wk-fq.wk-js .wk-fq-visual { opacity: 1; transform: none; }
  .wk-fq.wk-js h2::after { transform: none; }
}

/* responsive */
@media (max-width: 900px) {
  .wk-fq-grid { grid-template-columns: minmax(0, 1fr); }
  .wk-fq-visual { min-height: 0; height: 300px; max-width: 460px; width: calc(100% - 18px); margin: 0 auto 26px 0; }
}
@media (max-width: 560px) {
  .wk-fq-visual { height: 250px; }
  .wk-fq-badge { width: 54px; height: 54px; font-size: 1.5rem; top: 6px; right: -22px; }
  .wk-fq-badge::before { inset: -14px; }
  .wk-fq-img, .wk-fq-visual::before { border-radius: 18px 38px 18px 12px; }
  .wk-fq-item { border-radius: 10px 24px 10px 24px; }
  .wk-fq-item summary { padding: 9px 12px 9px 16px; }
  .wk-fq-q { font-size: .9rem; }
  .wk-fq .wk-eyebrow { letter-spacing: .16em; font-size: .68rem; gap: 10px; }
  .wk-fq .wk-eyebrow::before, .wk-fq .wk-eyebrow::after { width: 22px; }
}
</style>

<!-- ============ CONTACT / SECTION 5 — FAQ ============ -->
<?php
// Section 5 (FAQ) image — replace with your own photo (landscape ~ 1000 x 850 px)
$wkFqImg = '../assets/images/static/contact-faq.webp';
$wkFqFallback = '../assets/images/static/contact-banner.webp';   // shown if the photo above is missing
$wkFqOnErr = "if(!this.dataset.f){this.dataset.f=1;this.src='" . htmlspecialchars($wkFqFallback, ENT_QUOTES) . "';}else{this.style.display='none';}";
?>
<section class="section wk-fq" role="region" aria-label="Frequently asked questions">
  <div class="container wk-fq-wrap">

    <header class="wk-fq-head">
      <div data-fq style="--i:0"><span class="wk-eyebrow">FAQs</span></div>
      <h2 data-fq style="--i:1">Frequently Asked <span class="wk-fq-accent">Questions</span></h2>
    </header>

    <div class="wk-fq-grid">
      <div class="wk-fq-visual">
        <div class="wk-fq-img">
          <img src="<?php echo htmlspecialchars($wkFqImg, ENT_QUOTES); ?>" alt="Wildlife Kingdom visitor information" loading="lazy" onerror="<?php echo $wkFqOnErr; ?>">
        </div>
        <div class="wk-fq-badge" aria-hidden="true">?</div>
      </div>

      <div class="wk-fq-copy">
        <div class="wk-fq-list">
          <details class="wk-fq-item" name="wk-faq" data-fq style="--i:2" open>
            <summary><span class="wk-fq-sum"><span class="wk-fq-tag">FAQ 01</span><span class="wk-fq-q">What can I contact Wildlife Kingdom about?</span></span><span class="wk-fq-ico" aria-hidden="true"></span></summary>
            <p class="wk-fq-a">You can contact us for general enquiries, visit information, ticket-related questions, group visits, upcoming events, feedback, and other wildlife experience-related queries.</p>
          </details>
          <details class="wk-fq-item" name="wk-faq" data-fq style="--i:3">
            <summary><span class="wk-fq-sum"><span class="wk-fq-tag">FAQ 02</span><span class="wk-fq-q">How can I plan my visit to Wildlife Kingdom?</span></span><span class="wk-fq-ico" aria-hidden="true"></span></summary>
            <p class="wk-fq-a">You can explore our website for information about animals, habitats, events and tickets before your visit. If you need additional assistance, you can send us an enquiry.</p>
          </details>
          <details class="wk-fq-item" name="wk-faq" data-fq style="--i:4">
            <summary><span class="wk-fq-sum"><span class="wk-fq-tag">FAQ 03</span><span class="wk-fq-q">Can I make an enquiry for a group visit?</span></span><span class="wk-fq-ico" aria-hidden="true"></span></summary>
            <p class="wk-fq-a">Yes. If you are planning a visit with a school, family group, organisation or other larger group, you can contact us with your requirements.</p>
          </details>
          <details class="wk-fq-item" name="wk-faq" data-fq style="--i:5">
            <summary><span class="wk-fq-sum"><span class="wk-fq-tag">FAQ 04</span><span class="wk-fq-q">How can I ask about upcoming events?</span></span><span class="wk-fq-ico" aria-hidden="true"></span></summary>
            <p class="wk-fq-a">Visit the Events section for information about upcoming wildlife activities and experiences. You can also contact us if you need additional information.</p>
          </details>
          <details class="wk-fq-item" name="wk-faq" data-fq style="--i:6">
            <summary><span class="wk-fq-sum"><span class="wk-fq-tag">FAQ 05</span><span class="wk-fq-q">Can I share feedback about my Wildlife Kingdom experience?</span></span><span class="wk-fq-ico" aria-hidden="true"></span></summary>
            <p class="wk-fq-a">Absolutely. We welcome suggestions, feedback and experiences from our visitors. Your feedback helps us improve and create a better experience.</p>
          </details>
          <details class="wk-fq-item" name="wk-faq" data-fq style="--i:7">
            <summary><span class="wk-fq-sum"><span class="wk-fq-tag">FAQ 06</span><span class="wk-fq-q">I have another question. How can I reach you?</span></span><span class="wk-fq-ico" aria-hidden="true"></span></summary>
            <p class="wk-fq-a">If your question isn’t answered here, simply send us a message through the enquiry form and our team will be happy to assist.</p>
          </details>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
(function () {
  var sec = document.querySelector('.wk-fq');
  if (!sec) return;
  sec.classList.add('wk-js');
  // one answer open at a time (fallback for browsers without <details name>)
  var all = sec.querySelectorAll('details.wk-fq-item');
  all.forEach(function (d) {
    d.addEventListener('toggle', function () {
      if (d.open) all.forEach(function (o) { if (o !== d) o.removeAttribute('open'); });
    });
  });
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (e) {
      if (e[0].isIntersecting) { sec.classList.add('is-in'); io.disconnect(); }
    }, { threshold: 0.1 });
    io.observe(sec);
  } else { sec.classList.add('is-in'); }
})();
</script>

<?php require '../includes/footer.php'; ?>