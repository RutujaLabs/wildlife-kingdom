<?php
require '../../config/db.php';
require '../../includes/functions.php';
animal_ensure_table($conn);

$adminPageTitle = 'Animals';
$result = $conn->query('SELECT a.id, a.name, a.scientific_name, h.name AS habitat, a.conservation_status, a.image FROM animals a LEFT JOIN habitats h ON h.id = a.habitat_id ORDER BY a.name ASC');
$animals = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
require '../includes/admin-header.php';
?>
<?php if (isset($_GET['saved'])): ?><div class="admin-alert">Animal saved.</div><?php endif; ?>
<?php if (isset($_GET['deleted'])): ?><div class="admin-alert">Animal deleted.</div><?php endif; ?>
<section class="admin-panel">
	<div class="admin-panel-heading"><h2>Animal records</h2><span><?php echo count($animals); ?> total</span></div>
	<?php if ($animals): ?>
		<div class="admin-table-wrap"><table class="admin-table">
			<thead><tr><th>Animal</th><th>Scientific name</th><th>Habitat</th><th>Status</th><th>Actions</th></tr></thead>
			<tbody>
			<?php foreach ($animals as $animal): ?>
				<tr>
					<td><div class="admin-animal-cell">
						<?php if ($animal['image'] !== ''): ?><img src="../../uploads/animals/<?php echo rawurlencode($animal['image']); ?>" alt=""><?php endif; ?>
						<strong><?php echo htmlspecialchars($animal['name']); ?></strong>
					</div></td>
					<td><?php echo htmlspecialchars($animal['scientific_name']); ?></td>
					<td><?php echo htmlspecialchars($animal['habitat']); ?></td>
					<td><?php echo htmlspecialchars($animal['conservation_status']); ?></td>
					<td class="admin-actions"><a href="edit.php?id=<?php echo (int)$animal['id']; ?>">Edit</a>
						<form action="delete.php" method="post" data-confirm="Delete this animal? This cannot be undone.">
							<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(animal_csrf_token()); ?>">
							<input type="hidden" name="id" value="<?php echo (int)$animal['id']; ?>">
							<button class="admin-link-danger" type="submit">Delete</button>
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table></div>
	<?php else: ?>
		<div class="admin-empty"><h3>No animals yet</h3><p>Add an animal to populate the public Animals pages.</p><a class="admin-button" href="add.php">Add the first animal</a></div>
	<?php endif; ?>
</section>
<?php require '../includes/admin-footer.php'; ?>
