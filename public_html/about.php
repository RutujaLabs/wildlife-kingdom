<?php
$pageTitle = 'About Us';
$pageCss = 'pages.css';
require '../includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <h1>Our Story</h1>
    <p>Three decades of care, conservation and connection between people and the wild.</p>
  </div>
</section>

<section class="section section-light">
  <div class="container welcome-grid" data-reveal>
    <div class="welcome-media">
      <img src="../assets/images/static/about-story.jpg" alt="Wildlife Kingdom founding" onerror="this.style.display='none'">
    </div>
    <div class="welcome-text">
      <h2>Built on a simple belief</h2>
      <p>Wildlife Kingdom opened its gates in 1996 with a single promise: every animal here would live in a space built around its needs, not ours.</p>
      <p>What began as a small rescue sanctuary has grown into a home for over 200 species across 12 recreated habitats, without ever losing that founding promise.</p>
    </div>
  </div>
</section>

<section class="section section-dark">
  <div class="container">
    <div class="section-head" data-reveal>
      <h2>What guides us</h2>
      <p>Three principles sit behind every decision we make.</p>
    </div>
    <div class="grid-3" data-reveal>
      <div class="info-card">
        <div class="icon">🌿</div>
        <h3>Welfare first</h3>
        <p>Every habitat is designed around the animal's natural behaviour, not visitor convenience.</p>
      </div>
      <div class="info-card">
        <div class="icon">🔬</div>
        <h3>Real conservation</h3>
        <p>We fund breeding and field programmes for species at genuine risk of extinction.</p>
      </div>
      <div class="info-card">
        <div class="icon">📚</div>
        <h3>Honest education</h3>
        <p>We teach the truth about the wild, including the uncomfortable parts.</p>
      </div>
    </div>
  </div>
</section>

<section class="section section-sand">
  <div class="container welcome-grid" data-reveal>
    <div class="welcome-text">
      <h2>Where we stand today</h2>
      <p>Today, Wildlife Kingdom is home to keepers, vets, researchers and educators working together across every habitat.</p>
      <div class="welcome-stats">
        <div><strong>200+</strong><span>Species cared for</span></div>
        <div><strong>30</strong><span>Years of conservation</span></div>
        <div><strong>50+</strong><span>Field partners worldwide</span></div>
      </div>
    </div>
    <div class="welcome-media">
      <img src="../assets/images/static/about-today.jpg" alt="Wildlife Kingdom keepers at work" onerror="this.style.display='none'">
    </div>
  </div>
</section>

<?php require '../includes/footer.php'; ?>