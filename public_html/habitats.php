<?php
$pageTitle = 'Habitats';
$pageCss = 'pages.css';
require '../includes/header.php';
require '../config/db.php';

$habitats = [];
$result = $conn->query("SELECT * FROM habitats ORDER BY name ASC");
if ($result) { while ($row = $result->fetch_assoc()) { $habitats[] = $row; } }
?>

<section class="page-hero">
  <div class="container">
    <h1>Our Habitats</h1>
    <p>Every zone recreates a real ecosystem, climate and terrain included, so animals live close to home.</p>
  </div>
</section>

<section class="section section-sand">
  <div class="container">
    <div class="habitat-grid" data-reveal>
      <?php if (count($habitats) > 0): ?>
        <?php foreach ($habitats as $habitat): ?>
          <a href="#habitat-<?php echo $habitat['id']; ?>" class="habitat-card">
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
        <p>No habitats added yet.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php $i = 0; foreach ($habitats as $habitat): $i++; ?>
<section id="habitat-<?php echo $habitat['id']; ?>" class="section <?php echo ($i % 2 === 1) ? 'section-light' : 'section-dark'; ?>">
  <div class="container welcome-grid" data-reveal>
    <div class="welcome-media">
      <img src="../uploads/habitats/<?php echo htmlspecialchars($habitat['image']); ?>"
           alt="<?php echo htmlspecialchars($habitat['name']); ?>"
           onerror="this.style.display='none'">
    </div>
    <div class="welcome-text">
      <h2><?php echo htmlspecialchars($habitat['name']); ?></h2>
      <p><?php echo nl2br(htmlspecialchars($habitat['description'])); ?></p>
    </div>
  </div>
</section>
<?php endforeach; ?>

<?php require '../includes/footer.php'; ?>