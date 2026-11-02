<?php
$pageTitle = 'Tickets';
$pageCss = 'pages.css';
require '../includes/header.php';
require '../config/db.php';

$tickets = [];
$result = $conn->query("SELECT * FROM tickets ORDER BY price ASC");
if ($result) { while ($row = $result->fetch_assoc()) { $tickets[] = $row; } }

/* Order: Adult, Child, Family Pass (baaki tickets price ke hisaab se baad mein) */
$order = ['adult' => 1, 'child' => 2, 'family pass' => 3];
usort($tickets, function ($a, $b) use ($order) {
  $oa = $order[strtolower(trim($a['ticket_type']))] ?? 99;
  $ob = $order[strtolower(trim($b['ticket_type']))] ?? 99;
  return $oa === $ob ? $a['price'] <=> $b['price'] : $oa <=> $ob;
});

/* ---- Card images (apni image files yahan set karo) ---- */
$ticketImages = [
  '../assets/images/tickets-lion.jpg',
  '../assets/images/tickets-elephant.jpg',
  '../assets/images/tickets-giraffe.jpg',
];

/* ---- Card icons (SVG) : person, child, family ---- */
$ticketIcons = [
  '<svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7z"/></svg>',
  '<svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="3"/><path d="M5 9l7 2 7-2v2.2l-5 1.6V15l2.5 6h-2.4L12 16.5 9.9 21H7.5L10 15v-2.2L5 11.2z"/></svg>',
  '<svg viewBox="0 0 24 24" fill="currentColor"><circle cx="7" cy="7" r="2.6"/><circle cx="17" cy="7" r="2.6"/><circle cx="12" cy="11" r="2.2"/><path d="M2 19c0-3.3 2.2-5.5 5-5.5s5 2.2 5 5.5zM12 19c0-3.3 2.2-5.5 5-5.5s5 2.2 5 5.5z"/></svg>',
];

$leaf = '<svg class="tk-leaf" viewBox="0 0 80 70" fill="none" stroke="#6f9a5a" stroke-width="1.4" stroke-linecap="round"><path d="M70 66C60 40 45 22 18 8c4 24 18 44 52 58z" fill="#cfe3bd" fill-opacity=".7"/><path d="M70 66C52 44 36 28 18 8"/><path d="M50 66C46 52 36 40 26 32" fill="#b9d3a2" fill-opacity=".6"/></svg>';
?>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap">
<style>
  :root {
    --tk-green: #173B2A;
    --tk-green-dark: #0B2F22;
    --tk-orange: #E8A15B;
    --tk-orange-dark: #D98E45;
    --tk-cream: #FFFDF8; /* Soft White - index.php section 1 */
    --tk-text: #2B241D;
  }

  /* ---------- BANNER (500px, normal) ---------- */
  .tk-banner {
    position: relative; height: 500px; display: flex; align-items: center;
    background: linear-gradient(100deg, #173B2A 0%, #0B2F22 100%);
    /* Image lagani ho to upar wali line ki jagah ye use karo:
       background: url('../assets/images/tickets-hero.jpg') center / cover no-repeat; */
  }
  .tk-banner h1 {
    font-family: 'Playfair Display', Georgia, 'Times New Roman', serif; font-weight: 700;
    font-size: clamp(52px, 7vw, 82px); line-height: 1.05; margin: 6px 0 8px; color: var(--tk-green-dark);
  }
  .tk-banner p {
    font-family: 'Lato', 'Segoe UI', sans-serif; font-size: 18px; font-weight: 400;
    color: #1d2b20; max-width: 560px; margin: 0;
  }
  .tk-banner .container { position: relative; z-index: 2; width: 100%; }
  .tk-eyebrow {
    display: flex; align-items: center; gap: 14px; margin-bottom: 4px;
    font-family: 'Playfair Display', Georgia, 'Times New Roman', serif; font-weight: 600;
    font-size: 15px; letter-spacing: 2px; text-transform: uppercase; color: var(--tk-green);
  }
  .tk-eyebrow::before { content: ""; width: 46px; height: 2px; background: var(--tk-orange); }

  /* ---------- TICKETS SECTION ---------- */
  .tk-section { background: #FFFDF8; padding: 40px 0 40px; }
  .tk-wrap { width: calc(100% - 80px); max-width: 1840px; margin: 0 auto; }

  .tk-grid {
    display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 24px; align-items: stretch;
    margin-top: -4px;
  }
  .tk-card {
    position: relative; display: flex; gap: 24px; align-items: stretch;
    padding: 8px; border-radius: 22px; overflow: hidden;
    background: #FFFFFF;
    border: 1px solid rgba(23, 59, 42, .10);
    box-shadow: 0 8px 26px rgba(23, 59, 42, .12);
    min-height: 280px;
  }
  .tk-card-img { flex: 0 0 38%; border-radius: 18px; overflow: hidden; background: #E3ECE6; }
  .tk-card-img img { width: 100%; height: 100%; object-fit: cover; display: block; }

  .tk-card-body {
    position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column;
    justify-content: flex-start; min-width: 0; padding: 18px 22px 18px 0; font-family: 'Lato', 'Segoe UI', sans-serif;
  }
  .tk-title { display: flex; align-items: center; gap: 12px; }
  .tk-icon {
    width: 48px; height: 48px; border-radius: 50%; flex: 0 0 48px;
    background: var(--tk-green); color: #fff; display: grid; place-items: center;
  }
  .tk-icon svg { width: 26px; height: 26px; }
  .tk-title h3 {
    font-family: 'Playfair Display', Georgia, 'Times New Roman', serif; font-weight: 700;
    font-size: clamp(22px, 1.6vw, 28px); white-space: nowrap; color: var(--tk-green-dark); margin: 0;
  }
  .tk-price {
    font-family: 'Playfair Display', Georgia, 'Times New Roman', serif; font-weight: 700;
    font-size: 36px; color: var(--tk-green-dark); margin: 16px 0 10px;
  }
  .tk-desc { font-size: 16px; color: var(--tk-text); margin: 0 0 8px; line-height: 1.4; min-height: 46px; flex: 1 1 auto; }
  .tk-line { width: 34px; height: 2px; background: var(--tk-orange); margin: 0 0 20px; }

  .tk-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 10px;
    align-self: flex-start; white-space: nowrap; padding: 13px 30px; position: relative; z-index: 3; border-radius: 40px;
    background: var(--tk-orange); color: #173B2A;
    font-weight: 700; font-size: 18px; text-decoration: none;
    box-shadow: 0 5px 12px rgba(232, 161, 91, .40); transition: transform .2s, box-shadow .2s;
  }
  .tk-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(232, 161, 91, .50); background: var(--tk-orange-dark); }
  .tk-btn:focus-visible { outline: 3px solid var(--tk-green); outline-offset: 3px; }
  .tk-btn svg { width: 20px; height: 20px; }

  .tk-leaf { position: absolute; right: 0; bottom: 0; width: 70px; height: 62px; opacity:.9; z-index: 1; pointer-events: none; }

  /* ---------- FEATURE STRIP ---------- */
  .tk-strip {
    margin-top: 24px; display: grid; grid-template-columns: repeat(4, minmax(0, 1fr));
    background: #EAF1EC; border-radius: 22px; padding: 18px 10px;
    font-family: 'Lato', 'Segoe UI', sans-serif; color: var(--tk-green-dark);
  }
  .tk-strip-item {
    display: flex; align-items: center; justify-content: center; gap: 16px;
    padding: 8px 16px; min-height: 64px; font-size: 17px; font-weight: 400; line-height: 1.3;
    border-right: 1px solid rgba(23, 59, 42, .25);
  }
  .tk-strip-item:last-child { border-right: 0; }
  .tk-strip-item svg { width: 42px; height: 42px; flex: 0 0 42px; color: var(--tk-green); }

  /* ---------- RESPONSIVE ---------- */
  @media (max-width: 1400px) {
    .tk-card { gap: 16px; }
    .tk-card-img { flex-basis: 36%; }
    .tk-title { gap: 10px; }
    .tk-icon { width: 42px; height: 42px; flex-basis: 42px; }
    .tk-icon svg { width: 22px; height: 22px; }
    .tk-title h3 { white-space: normal; line-height: 1.15; }
    .tk-price { font-size: 32px; }
    .tk-btn { padding: 12px 24px; font-size: 17px; }
    .tk-strip-item { font-size: 15px; gap: 12px; }
  }
  @media (max-width: 1100px) {
    .tk-grid { grid-template-columns: 1fr; max-width: 640px; margin-inline: auto; }
    .tk-strip { grid-template-columns: repeat(2, 1fr); row-gap: 10px; }
    .tk-strip-item:nth-child(2) { border-right: 0; }
  }
  @media (max-width: 560px) {
    .tk-wrap { width: calc(100% - 32px); }
    .tk-card { flex-direction: column; }
    .tk-card-img { flex: none; height: 200px; }
    .tk-card-body { padding: 8px 16px 20px; }
    .tk-btn { align-self: stretch; }
    .tk-strip { grid-template-columns: 1fr; }
    .tk-strip-item { border-right: 0; justify-content: flex-start; }
  }
</style>

<!-- ================= BANNER ================= -->
<section class="tk-banner"></section>

<!-- ================= TICKETS ================= -->
<section class="tk-section">
  <div class="tk-wrap">

    <div class="tk-grid">
      <?php if (count($tickets) > 0): ?>
        <?php foreach ($tickets as $i => $ticket): ?>
          <article class="tk-card">
            <div class="tk-card-img">
              <img src="<?php echo $ticketImages[$i % count($ticketImages)]; ?>"
                   alt="" loading="lazy" onerror="this.style.display='none'">
            </div>

            <div class="tk-card-body">
              <div class="tk-title">
                <span class="tk-icon"><?php echo $ticketIcons[$i % count($ticketIcons)]; ?></span>
                <h3><?php echo htmlspecialchars($ticket['ticket_type']); ?></h3>
              </div>

              <div class="tk-price">$<?php echo number_format($ticket['price'], 2); ?></div>
              <p class="tk-desc"><?php echo htmlspecialchars($ticket['description']); ?></p>
              <div class="tk-line"></div>

              <a href="contact.php" class="tk-btn">
                Book Now
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
              </a>
            </div>

            <?php echo $leaf; ?>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Ticket pricing coming soon.</p>
      <?php endif; ?>
    </div>

    <!-- ================= FEATURE STRIP ================= -->
    <div class="tk-strip">
      <div class="tk-strip-item">
        <svg viewBox="0 0 24 24" fill="currentColor"><ellipse cx="12" cy="16" rx="5" ry="4.2"/><ellipse cx="5" cy="11" rx="2" ry="2.8" transform="rotate(-20 5 11)"/><ellipse cx="9.5" cy="6" rx="2" ry="2.8"/><ellipse cx="14.5" cy="6" rx="2" ry="2.8"/><ellipse cx="19" cy="11" rx="2" ry="2.8" transform="rotate(20 19 11)"/></svg>
        <span>Explore Amazing<br>Wildlife</span>
      </div>
      <div class="tk-strip-item">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c3 2.5 5 5 5 7.5 0 1-.4 1.8-1 2.5 2 .8 3.5 2.6 3.5 4.7 0 2.4-2 4.3-4.5 4.3H13v1.5h-2V21H8C5.5 21 3.5 19.100 3.500 16.700c0-2.100 1.500-3.900 3.500-4.700-.6-.7-1-1.500-1-2.500C6 7 9 4.500 12 2z"/></svg>
        <span>Discover Beautiful<br>Habitats</span>
      </div>
      <div class="tk-strip-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" stroke-linecap="round"><path d="M12 2.500l8 3v6c0 5-3.400 8.700-8 10-4.600-1.300-8-5-8-10v-6z" fill="currentColor" fill-opacity=".12"/><path d="M8 12l3 3 5-6"/></svg>
        <span>Support Conservation</span>
      </div>
      <div class="tk-strip-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"><path d="M12 21C5 15.500 2.500 12 2.500 8.500 2.500 5.900 4.500 4 7 4c2 0 3.800 1.100 5 3 1.200-1.900 3-3 5-3 2.500 0 4.500 1.900 4.500 4.500C21.500 12 19 15.500 12 21z"/></svg>
        <span>Create Lasting Memories</span>
      </div>
    </div>

  </div>
</section>

<?php require '../includes/footer.php'; ?>