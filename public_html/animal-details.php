<?php
$pageCss = ['pages.css', 'animals.css'];
require '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$animal = null;

$stmt = $conn->prepare("SELECT * FROM animals WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    $animal = $res->fetch_assoc();
}

$pageTitle = $animal ? $animal['name'] : 'Animal Not Found';
require '../includes/header.php';
?>

<?php if ($animal): ?>

  <section class="page-hero">
    <div class="container">
      <h1><?php echo htmlspecialchars($animal['name']); ?></h1>
      <div class="breadcrumb"><a href="animals.php">Animals</a> / <?php echo htmlspecialchars($animal['name']); ?></div>
    </div>
  </section>

  <section class="section section-light">
    <div class="container detail-grid" data-reveal>
      <div class="detail-media">
        <img src="../uploads/animals/<?php echo htmlspecialchars($animal['image']); ?>"
             alt="<?php echo htmlspecialchars($animal['name']); ?>"
             onerror="this.style.display='none'">
      </div>

      <div class="detail-heading">
        <span class="detail-status"><?php echo htmlspecialchars($animal['conservation_status']); ?></span>
        <h1><?php echo htmlspecialchars($animal['name']); ?></h1>
        <p class="sci"><?php echo htmlspecialchars($animal['scientific_name']); ?></p>
        <p style="margin-top:20px; opacity:.85;"><?php echo nl2br(htmlspecialchars($animal['description'])); ?></p>

        <div class="detail-facts">
          <div><span>Species</span><strong><?php echo htmlspecialchars($animal['species']); ?></strong></div>
          <div><span>Diet</span><strong><?php echo htmlspecialchars($animal['diet']); ?></strong></div>
          <div><span>Lifespan</span><strong><?php echo htmlspecialchars($animal['lifespan']); ?></strong></div>
          <div><span>Status</span><strong><?php echo htmlspecialchars($animal['conservation_status']); ?></strong></div>
        </div>

        <?php if (!empty($animal['fun_fact'])): ?>
          <div class="detail-funfact">🐾 <strong>Did you know?</strong> <?php echo htmlspecialchars($animal['fun_fact']); ?></div>
        <?php endif; ?>

        <a href="animals.php" class="btn btn-outline-dark" style="margin-top:28px;">&larr; Back to All Animals</a>
      </div>
    </div>
  </section>

<?php else: ?>

  <section class="section section-light" style="padding-top:180px; text-align:center;">
    <div class="container">
      <h2>Animal not found</h2>
      <p style="margin-top:12px;">This animal may have been removed, or the link is incorrect.</p>
      <a href="animals.php" class="btn btn-gold" style="margin-top:24px;">Browse All Animals</a>
    </div>
  </section>

<?php endif; ?>

<?php require '../includes/footer.php'; ?>