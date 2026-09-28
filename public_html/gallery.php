<?php
$pageTitle = 'Gallery';
$pageCss = 'pages.css';
require '../includes/header.php';
require '../config/db.php';

$gallery = [];
$result = $conn->query("SELECT * FROM gallery ORDER BY created_at DESC");
if ($result) { while ($row = $result->fetch_assoc()) { $gallery[] = $row; } }
?>

<section class="page-hero">
  <div class="container">
    <h1>Gallery</h1>
    <p>Moments from the wild, captured by our visitors and keepers.</p>
  </div>
</section>

<section class="section section-sand">
  <div class="container">
    <div class="grid-4" data-reveal>
      <?php if (count($gallery) > 0): ?>
        <?php foreach ($gallery as $item): ?>
          <a href="../uploads/gallery/<?php echo htmlspecialchars($item['image']); ?>" target="_blank"
             style="border-radius:14px; overflow:hidden; aspect-ratio:1/1; display:block;">
            <img src="../uploads/gallery/<?php echo htmlspecialchars($item['image']); ?>"
                 alt="<?php echo htmlspecialchars($item['title']); ?>"
                 style="width:100%; height:100%; object-fit:cover;"
                 onerror="this.parentElement.style.display='none'">
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Gallery coming soon.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require '../includes/footer.php'; ?>