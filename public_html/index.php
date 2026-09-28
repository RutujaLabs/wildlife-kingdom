<?php
$pageTitle = 'Home';
require '../includes/header.php';
require '../config/db.php';

// ---- Fetch dynamic content from the database ----

$habitats = [];
$result = $conn->query("SELECT * FROM habitats LIMIT 3");
if ($result) { while ($row = $result->fetch_assoc()) { $habitats[] = $row; } }

$animals = [];
$result = $conn->query("SELECT * FROM animals WHERE is_featured = 1 LIMIT 3");
if ($result) { while ($row = $result->fetch_assoc()) { $animals[] = $row; } }

$events = [];
$result = $conn->query("SELECT * FROM events ORDER BY event_date ASC LIMIT 2");
if ($result) { while ($row = $result->fetch_assoc()) { $events[] = $row; } }

$gallery = [];
$result = $conn->query("SELECT * FROM gallery LIMIT 4");
if ($result) { while ($row = $result->fetch_assoc()) { $gallery[] = $row; } }
?>

<!-- ============ HERO ============ -->
<section class="hero">
  <div class="hero-inner hero-fade">
    <h1>Where the wild is <span>royally</span> alive</h1>
    <p>Step into a world of untamed beauty. Wildlife Kingdom brings you face to face with the planet's most extraordinary creatures, in habitats built to let them thrive.</p>
    <div class="hero-actions">
      <a href="tickets.php" class="btn btn-gold">Book Tickets</a>
      <a href="animals.php" class="btn btn-outline">Explore Animals</a>
    </div>
  </div>
</section>

<!-- ============ WELCOME / ABOUT ============ -->
<section class="section section-light">
  <div class="container welcome-grid" data-reveal>
    <div class="welcome-media">
      <img src="../assets/images/static/welcome-elephant.jpg" alt="Elephant family at Wildlife Kingdom"
           onerror="this.style.display='none'">
    </div>
    <div class="welcome-text">
      <h2>A sanctuary built for wonder</h2>
      <p>For three decades, Wildlife Kingdom has stood as a home for over 200 species, where every enclosure is designed around the animal's natural world rather than ours.</p>
      <p>We exist to bring people closer to wildlife responsibly, funding conservation work across the globe with every visit.</p>
      <div class="welcome-stats">
        <div><strong>200+</strong><span>Species cared for</span></div>
        <div><strong>30</strong><span>Years of conservation</span></div>
        <div><strong>12</strong><span>Natural habitats</span></div>
      </div>
    </div>
  </div>
</section>

<!-- ============ EXPLORE WILDLIFE ============ -->
<section class="section section-dark">
  <div class="container">
    <div class="section-head" data-reveal>
      <h2>Explore the wild, by kind</h2>
      <p>Every corner of the kingdom holds a different world. Choose where your curiosity takes you first.</p>
    </div>
    <div class="explore-grid" data-reveal>
      <div class="explore-card">
        <div class="explore-icon">🦁</div>
        <h3>Mammals</h3>
        <p>From the tallest giraffe to the smallest meerkat.</p>
      </div>
      <div class="explore-card">
        <div class="explore-icon">🦜</div>
        <h3>Birds</h3>
        <p>Soaring residents of every colour and call.</p>
      </div>
      <div class="explore-card">
        <div class="explore-icon">🐍</div>
        <h3>Reptiles</h3>
        <p>Ancient survivors of a changing world.</p>
      </div>
      <div class="explore-card">
        <div class="explore-icon">🐠</div>
        <h3>Marine Life</h3>
        <p>An ocean of life beneath the surface.</p>
      </div>
    </div>
  </div>
</section>

<!-- ============ HABITATS (from database) ============ -->
<section class="section section-sand">
  <div class="container">
    <div class="section-head" data-reveal>
      <h2>Habitats, recreated faithfully</h2>
      <p>Each zone mirrors a real ecosystem, climate and all, so animals live as close to home as possible.</p>
    </div>
    <div class="habitat-grid" data-reveal>
      <?php if (count($habitats) > 0): ?>
        <?php foreach ($habitats as $habitat): ?>
          <a href="habitats.php" class="habitat-card">
            <img src="../uploads/habitats/<?php echo htmlspecialchars($habitat['image']); ?>"
                 alt="<?php echo htmlspecialchars($habitat['name']); ?>"
                 onerror="this.style.display='none'">
            <div class="habitat-overlay">
              <h3><?php echo htmlspecialchars($habitat['name']); ?></h3>
              <span><?php echo htmlspecialchars($habitat['climate']); ?></span>
            </div>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Habitats coming soon.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ============ FEATURED ANIMALS (from database) ============ -->
<section class="section section-deepest">
  <div class="container">
    <div class="section-head" data-reveal>
      <h2>Meet a few of our residents</h2>
      <p>A small introduction to the many personalities that call the kingdom home.</p>
    </div>
    <div class="animal-grid" data-reveal>
      <?php if (count($animals) > 0): ?>
        <?php foreach ($animals as $animal): ?>
          <div class="animal-card">
            <img src="../uploads/animals/<?php echo htmlspecialchars($animal['image']); ?>"
                 alt="<?php echo htmlspecialchars($animal['name']); ?>"
                 onerror="this.style.display='none'">
            <div class="animal-card-body">
              <span class="status"><?php echo htmlspecialchars($animal['conservation_status']); ?></span>
              <h3><?php echo htmlspecialchars($animal['name']); ?></h3>
              <p><?php echo htmlspecialchars($animal['scientific_name']); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Featured animals coming soon.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ============ CONSERVATION ============ -->
<section class="section section-light">
  <div class="container conservation-grid" data-reveal>
    <div class="conservation-media">
      <img src="../assets/images/static/conservation.jpg" alt="Conservation work at Wildlife Kingdom"
           onerror="this.style.display='none'">
    </div>
    <div class="conservation-text">
      <h2>Protecting what we love</h2>
      <p>Every ticket funds real conservation work, from anti-poaching patrols to habitat restoration across three continents.</p>
      <div class="conservation-list">
        <div><span class="dot"></span><p>Breeding programmes for endangered species</p></div>
        <div><span class="dot"></span><p>Field research partnerships worldwide</p></div>
        <div><span class="dot"></span><p>Community education across local schools</p></div>
      </div>
      <a href="conservation.php" class="btn btn-outline-dark" style="margin-top:28px;">Our Conservation Work</a>
    </div>
  </div>
</section>

<!-- ============ UPCOMING EVENTS (from database) ============ -->
<section class="section section-dark">
  <div class="container">
    <div class="section-head" data-reveal>
      <h2>Upcoming at the kingdom</h2>
      <p>Talks, tours and encounters happening soon.</p>
    </div>
    <div class="event-list" data-reveal>
      <?php if (count($events) > 0): ?>
        <?php foreach ($events as $event):
          $day   = date('d', strtotime($event['event_date']));
          $month = date('M', strtotime($event['event_date']));
        ?>
          <div class="event-row">
            <div class="event-date"><strong><?php echo $day; ?></strong><span><?php echo $month; ?></span></div>
            <div class="event-info">
              <h3><?php echo htmlspecialchars($event['title']); ?></h3>
              <p><?php echo htmlspecialchars($event['location']); ?> &middot; <?php echo date('g:i A', strtotime($event['event_time'])); ?></p>
            </div>
            <a href="events.php" class="btn btn-outline">Details</a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No upcoming events right now, check back soon.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ============ GALLERY (from database) ============ -->
<section class="section section-sand">
  <div class="container">
    <div class="section-head" data-reveal>
      <h2>Moments from the wild</h2>
      <p>A glimpse through the eyes of our visitors and keepers.</p>
    </div>
    <div class="gallery-grid" data-reveal>
      <?php if (count($gallery) > 0): ?>
        <?php foreach ($gallery as $item): ?>
          <a href="gallery.php">
            <img src="../uploads/gallery/<?php echo htmlspecialchars($item['image']); ?>"
                 alt="<?php echo htmlspecialchars($item['title']); ?>"
                 onerror="this.style.display='none'">
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Gallery coming soon.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ============ VISIT US ============ -->
<section class="section section-deepest">
  <div class="container visit-grid" data-reveal>
    <div class="visit-info">
      <h2>Plan your visit</h2>
      <p>Open every day of the year, rain or shine.</p>
      <div class="visit-hours">
        <div><span>Monday – Friday</span><span>9:00 AM – 6:00 PM</span></div>
        <div><span>Saturday – Sunday</span><span>8:00 AM – 7:00 PM</span></div>
        <div><span>Public Holidays</span><span>8:00 AM – 7:00 PM</span></div>
      </div>
      <a href="visit-us.php" class="btn btn-gold">Plan Your Visit</a>
    </div>
    <div class="visit-map">Map preview coming soon</div>
  </div>
</section>

<!-- ============ FINAL CTA ============ -->
<section class="final-cta">
  <div class="container">
    <h2>Your wild adventure starts here</h2>
    <p>Book your tickets today and step into a world where every trail leads to a new encounter.</p>
    <a href="tickets.php" class="btn" style="background:var(--deep-jungle); color:var(--warm-ivory);">Book Tickets Now</a>
  </div>
</section>

<?php require '../includes/footer.php'; ?>