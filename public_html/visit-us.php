<?php
$pageTitle = 'Visit Us';
$pageCss = 'pages.css';
require '../includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <h1>Plan Your Visit</h1>
    <p>Everything you need to know before you come see us.</p>
  </div>
</section>

<section class="section section-deepest">
  <div class="container visit-grid" data-reveal>
    <div class="visit-info">
      <h2>Opening hours</h2>
      <div class="visit-hours">
        <div><span>Monday – Friday</span><span>9:00 AM – 6:00 PM</span></div>
        <div><span>Saturday – Sunday</span><span>8:00 AM – 7:00 PM</span></div>
        <div><span>Public Holidays</span><span>8:00 AM – 7:00 PM</span></div>
      </div>
      <a href="tickets.php" class="btn btn-gold">Book Tickets</a>
    </div>
    <div class="visit-map">Map preview coming soon</div>
  </div>
</section>

<section class="section section-light">
  <div class="container">
    <div class="section-head" data-reveal>
      <h2>Good to know</h2>
      <p>A few essentials to make your visit smooth.</p>
    </div>
    <div class="grid-3" data-reveal>
      <div class="info-card">
        <div class="icon">🅿️</div>
        <h3>Parking</h3>
        <p>Free parking is available at the main entrance, arrive early on weekends.</p>
      </div>
      <div class="info-card">
        <div class="icon">🍽️</div>
        <h3>Food &amp; drink</h3>
        <p>Cafes and picnic areas are located throughout the grounds.</p>
      </div>
      <div class="info-card">
        <div class="icon">♿</div>
        <h3>Accessibility</h3>
        <p>Wheelchair access and rentals are available near the entrance.</p>
      </div>
    </div>
  </div>
</section>

<?php require '../includes/footer.php'; ?>