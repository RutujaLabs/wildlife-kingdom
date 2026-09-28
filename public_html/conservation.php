<?php
$pageTitle = 'Conservation';
$pageCss = 'pages.css';
require '../includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <h1>Conservation</h1>
    <p>Every visit funds real work protecting species and habitats around the world.</p>
  </div>
</section>

<section class="section section-light">
  <div class="container conservation-grid" data-reveal>
    <div class="conservation-media">
      <img src="../assets/images/static/conservation-field.jpg" alt="Conservation field work" onerror="this.style.display='none'">
    </div>
    <div class="conservation-text">
      <h2>Why conservation matters</h2>
      <p>Nearly a third of the species in our care are threatened in the wild. Our work does not stop at the gates of the kingdom.</p>
      <div class="conservation-list">
        <div><span class="dot"></span><p>Breeding programmes for endangered species</p></div>
        <div><span class="dot"></span><p>Anti-poaching support for field partners</p></div>
        <div><span class="dot"></span><p>Habitat restoration across three continents</p></div>
      </div>
    </div>
  </div>
</section>

<section class="section section-dark">
  <div class="container">
    <div class="section-head" data-reveal>
      <h2>Where your ticket goes</h2>
      <p>A transparent look at how conservation funding is used each year.</p>
    </div>
    <div class="grid-3" data-reveal>
      <div class="info-card">
        <div class="icon">🐘</div>
        <h3>Species protection</h3>
        <p>Direct funding for breeding and rewilding programmes.</p>
      </div>
      <div class="info-card">
        <div class="icon">🌍</div>
        <h3>Field partnerships</h3>
        <p>Support for rangers and researchers in the wild.</p>
      </div>
      <div class="info-card">
        <div class="icon">🎓</div>
        <h3>Education</h3>
        <p>Free conservation programmes for local schools.</p>
      </div>
    </div>
  </div>
</section>

<?php require '../includes/footer.php'; ?>