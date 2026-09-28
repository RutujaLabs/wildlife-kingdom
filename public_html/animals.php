<?php
$pageTitle = 'Animals';
$pageCss = ['pages.css', 'animals.css'];
require '../includes/header.php';
require '../config/db.php';

$animals = [];
$result = $conn->query("SELECT * FROM animals ORDER BY name ASC");
if ($result) { while ($row = $result->fetch_assoc()) { $animals[] = $row; } }
?>

<section class="page-hero">
  <div class="container">
    <h1>Our Animals</h1>
    <p>Meet the residents of Wildlife Kingdom, from the smallest insects to the tallest giants.</p>
  </div>
</section>

<section class="section section-light">
  <div class="container">
    <div class="grid-3" data-reveal>
      <?php if (count($animals) > 0): ?>
        <?php foreach ($animals as $animal): ?>
          <div class="animal-card">
            <img src="../uploads/animals/<?php echo htmlspecialchars($animal['image']); ?>"
                 alt="<?php echo htmlspecialchars($animal['name']); ?>"
                 onerror="this.style.display='none'">
            <div class="animal-card-body">
              <span class="status"><?php echo htmlspecialchars($animal['conservation_status']); ?></span>
              <h3><?php echo htmlspecialchars($animal['name']); ?></h3>
              <p class="sci"><?php echo htmlspecialchars($animal['scientific_name']); ?></p>
              <a href="animal-details.php?id=<?php echo (int)$animal['id']; ?>">View Details &rarr;</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No animals added yet. Add some from the admin panel soon!</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require '../includes/footer.php'; ?>