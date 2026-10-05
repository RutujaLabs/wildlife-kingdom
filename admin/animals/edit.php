<?php
require '../../config/db.php';
require '../../includes/functions.php';
animal_ensure_table($conn);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$stmt = $conn->prepare('SELECT * FROM animals WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$animal = $stmt->get_result()->fetch_assoc();
if (!$animal) {
	http_response_code(404);
	exit('Animal not found.');
}

$errors = [];
$values = $animal;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	try {
		animal_verify_csrf();
		foreach (['name' => 100, 'scientific_name' => 150, 'description' => 10000, 'conservation_status' => 40, 'species' => 100, 'diet' => 100, 'lifespan' => 50, 'fun_fact' => 10000] as $key => $limit) {
			$values[$key] = animal_post_value($key, $limit);
		}
		if ($values['name'] === '') {
			throw new InvalidArgumentException('Animal name is required.');
		}
		$values['habitat_id'] = animal_validate_habitat($conn);
		$allowedStatuses = ['Least Concern', 'Near Threatened', 'Vulnerable', 'Endangered', 'Critically Endangered'];
		if (!in_array($values['conservation_status'], $allowedStatuses, true)) {
			throw new InvalidArgumentException('Choose a valid conservation status.');
		}

		$oldImage = $animal['image'];
		$image = animal_upload_image($_FILES['image'] ?? [], $oldImage);
		$featured = isset($_POST['is_featured']) ? 1 : 0;
		$stmt = $conn->prepare('UPDATE animals SET habitat_id = ?, name = ?, scientific_name = ?, description = ?, image = ?, conservation_status = ?, species = ?, diet = ?, lifespan = ?, fun_fact = ?, is_featured = ? WHERE id = ?');
		$stmt->bind_param('isssssssssii', $values['habitat_id'], $values['name'], $values['scientific_name'], $values['description'], $image, $values['conservation_status'], $values['species'], $values['diet'], $values['lifespan'], $values['fun_fact'], $featured, $id);
		if (!$stmt->execute()) {
			if ($image !== $oldImage) { animal_remove_image($image); }
			throw new RuntimeException('Could not update this animal.');
		}
		if ($image !== $oldImage) { animal_remove_image($oldImage); }
		header('Location: list.php?saved=1');
		exit;
	} catch (Throwable $error) {
		$errors[] = $error->getMessage();
	}
}

$adminPageTitle = 'Edit Animal';
$habitats = animal_habitat_options($conn);
require '../includes/admin-header.php';
require __DIR__ . '/form.php';
require '../includes/admin-footer.php';
