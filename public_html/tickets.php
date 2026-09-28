<?php
$pageTitle = 'Tickets';
$pageCss = 'pages.css';
require '../includes/header.php';
require '../config/db.php';

$tickets = [];
$result = $conn->query("SELECT * FROM tickets ORDER BY price ASC");
if ($result) { while ($row = $result->fetch_assoc()) { $tickets[] = $row; } }
?>

<section class="page-hero">
  <div class="container">
    <h1>Tickets</h1>
    <p>Simple pricing, every ticket helps fund conservation work.</p>
  </div>
</section>

<section class="section section-light">
  <div class="container">
    <div class="ticket-grid" data-reveal>
      <?php if (count($tickets) > 0): ?>
        <?php $i = 0; foreach ($tickets as $ticket): $i++; ?>
          <div class="ticket-card <?php echo ($i === 2) ? 'featured' : ''; ?>">
            <h3><?php echo htmlspecialchars($ticket['ticket_type']); ?></h3>
            <div class="price">$<?php echo number_format($ticket['price'], 2); ?></div>
            <p class="desc"><?php echo htmlspecialchars($ticket['description']); ?></p>
            <a href="contact.php" class="btn btn-gold" style="width:100%; justify-content:center;">Book Now</a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Ticket pricing coming soon.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require '../includes/footer.php'; ?>