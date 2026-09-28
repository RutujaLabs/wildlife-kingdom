<?php
$pageTitle = 'Events';
$pageCss = 'pages.css';
require '../includes/header.php';
require '../config/db.php';

$events = [];
$result = $conn->query("SELECT * FROM events ORDER BY event_date ASC");
if ($result) { while ($row = $result->fetch_assoc()) { $events[] = $row; } }
?>

<section class="page-hero">
  <div class="container">
    <h1>Events</h1>
    <p>Talks, tours and encounters happening across the kingdom.</p>
  </div>
</section>

<section class="section section-dark">
  <div class="container">
    <div class="event-list" data-reveal>
      <?php if (count($events) > 0): ?>
        <?php foreach ($events as $event):
          $day = date('d', strtotime($event['event_date']));
          $month = date('M', strtotime($event['event_date']));
        ?>
          <div class="event-row">
            <div class="event-date"><strong><?php echo $day; ?></strong><span><?php echo $month; ?></span></div>
            <div class="event-info">
              <h3><?php echo htmlspecialchars($event['title']); ?></h3>
              <p><?php echo htmlspecialchars($event['description']); ?></p>
              <p><?php echo htmlspecialchars($event['location']); ?> &middot; <?php echo date('g:i A', strtotime($event['event_time'])); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No events scheduled right now.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require '../includes/footer.php'; ?>