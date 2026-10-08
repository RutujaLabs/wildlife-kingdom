<?php
/**
 * Shared header + navigation
 * Included by every page in public_html/
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | Wildlife Kingdom' : 'Wildlife Kingdom'; ?></title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/home.css">
<link rel="stylesheet" href="../assets/css/header-navbar.css">
<?php
if (isset($pageCss)) {
    $pageCssFiles = is_array($pageCss) ? $pageCss : [$pageCss];
    foreach ($pageCssFiles as $cssFile) {
        echo '<link rel="stylesheet" href="../assets/css/' . htmlspecialchars($cssFile) . '">' . "\n";
    }
}
?>

<style>
/* Navbar hamesha green rahe (banner ke upar bhi, scroll se pehle bhi) */
:root { --wk-nav-green: #0B2F22; } /* apni theme ka green yahan daal do */
.site-header,
.site-header.site-header--transparent,
.site-header.is-scrolled {
  background: var(--wk-nav-green) !important;
  background-color: var(--wk-nav-green) !important;
  backdrop-filter: none !important;
  -webkit-backdrop-filter: none !important;
}
@media (max-width: 900px) {
  .main-nav.is-open { background: var(--wk-nav-green) !important; }
}
.site-header .header-cta,
.site-header .header-cta.btn-gold {
  background: #E8A15B !important;
  background-color: #E8A15B !important;
  border-color: #E8A15B !important;
  color: #000 !important;
}
.site-header .header-cta:hover { color: #000 !important; }

/* Logo bada, upar-niche gap ke saath, navbar ki height same */
.site-header .logo img {
  height: 84px !important;
  width: auto !important;
  max-height: none !important;
  margin: -10px 0 !important;
  display: block;
}
@media (max-width: 900px) {
  .site-header .logo img { height: 56px !important; margin: -3px 0 !important; }
}

/* ===== Mobile hamburger menu fix ===== */
.site-header { z-index: 9999; }
.nav-toggle { cursor: pointer; }

@media (min-width: 901px) {
  .nav-toggle { display: none !important; }
}

@media (max-width: 900px) {
  .site-header .header-inner { position: relative; }

  /* Hamburger hamesha dikhe */
  .nav-toggle {
    display: flex !important;
    flex-direction: column;
    justify-content: center;
    gap: 5px;
    width: 42px;
    height: 42px;
    padding: 8px;
    background: transparent;
    border: 0;
    margin-left: auto;
    position: relative;
    z-index: 10001;
  }
  .nav-toggle span {
    display: block;
    width: 100%;
    height: 3px;
    background: #fff;
    border-radius: 2px;
    transition: transform .25s ease, opacity .25s ease;
  }
  .nav-toggle.is-open span:nth-child(1) { transform: translateY(8px) rotate(45deg); }
  .nav-toggle.is-open span:nth-child(2) { opacity: 0; }
  .nav-toggle.is-open span:nth-child(3) { transform: translateY(-8px) rotate(-45deg); }

  /* Menu band rahe by default */
  .main-nav {
    display: none !important;
    position: fixed !important;
    top: var(--wk-header-h, 72px) !important;
    left: 0 !important;
    right: 0 !important;
    bottom: auto !important;
    max-height: calc(100vh - var(--wk-header-h, 72px));
    overflow-y: auto;
    background: var(--wk-nav-green) !important;
    padding: 10px 0 20px;
    z-index: 10000;
    box-shadow: 0 12px 24px rgba(0,0,0,.25);
    transform: none !important;
    opacity: 1 !important;
    visibility: visible !important;
  }
  /* Click pe khule */
  .main-nav.is-open { display: block !important; }

  .main-nav ul {
    display: flex !important;
    flex-direction: column;
    gap: 0;
    margin: 0;
    padding: 0;
    list-style: none;
  }
  .main-nav li { width: 100%; }
  .main-nav a {
    display: block;
    padding: 14px 24px;
    color: #fff !important;
    text-decoration: none;
    font-size: 17px;
    border-bottom: 1px solid rgba(255,255,255,.08);
    pointer-events: auto;
  }
  .main-nav a:hover { background: rgba(255,255,255,.08); }

  /* Chhoti screen pe Book Tickets button thoda chhota */
  .site-header .header-cta { padding: 8px 12px; font-size: 13px; margin-right: 8px; }
}
</style>
</head>
<body>

<header class="site-header">
  <div class="header-inner">
    <a href="index.php" class="logo">
      <img src="../assets/images/logo/wildlife-kingdom-header.png" alt="Wildlife Kingdom" onerror="this.style.display='none'">
     
    </a>

    <nav class="main-nav" id="mainNav">
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About Us</a></li>
        <li><a href="animals.php">Animals</a></li>
        <li><a href="habitats.php">Habitats</a></li>
        <li><a href="events.php">Events</a></li>
        <li><a href="gallery.php">Gallery</a></li>
        <li><a href="contact.php">Contact Us</a></li>
      </ul>
    </nav>

    <a href="tickets.php" class="btn btn-gold header-cta">Book Tickets</a>

    <button class="nav-toggle" id="navToggle" aria-label="Toggle menu" aria-expanded="false" aria-controls="mainNav">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>

<script>
(function () {
  var toggle = document.getElementById('navToggle');
  var nav = document.getElementById('mainNav');
  var header = document.querySelector('.site-header');
  if (!toggle || !nav) return;
  toggle.dataset.wkBound = 'true';

  function setHeaderHeight() {
    if (header) {
      document.documentElement.style.setProperty('--wk-header-h', header.offsetHeight + 'px');
    }
  }

  function closeMenu() {
    nav.classList.remove('is-open');
    toggle.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('wk-menu-open');
  }

  function openMenu() {
    setHeaderHeight();
    nav.classList.add('is-open');
    toggle.classList.add('is-open');
    toggle.setAttribute('aria-expanded', 'true');
    document.body.classList.add('wk-menu-open');
  }

  // Capture phase + stopImmediatePropagation: dusri JS file se double-toggle nahi hoga
  toggle.addEventListener('click', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    if (nav.classList.contains('is-open')) { closeMenu(); } else { openMenu(); }
  }, true);

  // Link click pe menu band, page normally khulega
  nav.querySelectorAll('a').forEach(function (link) {
    link.addEventListener('click', closeMenu);
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeMenu();
  });

  window.addEventListener('resize', function () {
    setHeaderHeight();
    if (window.innerWidth > 900) closeMenu();
  });

  setHeaderHeight();
  window.addEventListener('load', setHeaderHeight);

  if (header) {
    var onScroll = function () {
      header.classList.toggle('is-scrolled', window.scrollY > 12);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }
})();
</script>