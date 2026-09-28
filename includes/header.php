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
</head>
<body>

<header class="site-header<?php echo (isset($transparentHeader) && $transparentHeader === false) ? '' : ' site-header--transparent'; ?>">
  <div class="header-inner">
    <a href="index.php" class="logo">
      <img src="../assets/images/logo/wildlife-kingdom-header.png" alt="Wildlife Kingdom" onerror="this.style.display='none'">
     
    </a>

    <nav class="main-nav" id="mainNav">
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="animals.php">Animals</a></li>
        <li><a href="habitats.php">Habitats</a></li>
        <li><a href="events.php">Events</a></li>
        <li><a href="gallery.php">Gallery</a></li>
        <li><a href="conservation.php">Conservation</a></li>
        <li><a href="visit-us.php">Visit Us</a></li>
        <li><a href="contact.php">Contact</a></li>
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
  if (!toggle || !nav || toggle.dataset.wkBound) return;
  toggle.dataset.wkBound = 'true';

  function closeMenu() {
    nav.classList.remove('is-open');
    toggle.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('wk-menu-open');
  }

  toggle.addEventListener('click', function () {
    var open = nav.classList.toggle('is-open');
    toggle.classList.toggle('is-open', open);
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    document.body.classList.toggle('wk-menu-open', open);
  });

  nav.querySelectorAll('a').forEach(function (link) {
    link.addEventListener('click', closeMenu);
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeMenu();
  });

  window.addEventListener('resize', function () {
    if (window.innerWidth > 900) closeMenu();
  });

  if (header) {
    var onScroll = function () {
      header.classList.toggle('is-scrolled', window.scrollY > 12);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }
})();
</script>