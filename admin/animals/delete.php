<?php
require '../../config/db.php';
require '../../includes/functions.php';
animal_ensure_table($conn);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	exit('Method not allowed.');
}

try {
	animal_verify_csrf();
	$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
	if (!$id) {
		throw new InvalidArgumentException('Invalid animal.');
	}
	$stmt = $conn->prepare('SELECT image FROM animals WHERE id = ?');
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$animal = $stmt->get_result()->fetch_assoc();
	if (!$animal) {
		throw new RuntimeException('Animal not found.');
	}
	$stmt = $conn->prepare('DELETE FROM animals WHERE id = ?');
	$stmt->bind_param('i', $id);
	if (!$stmt->execute()) {
		throw new RuntimeException('Could not delete this animal.');
	}
	animal_remove_image($animal['image']);
	header('Location: list.php?deleted=1');
	exit;
} catch (Throwable $error) {
	http_response_code(400);
	exit(htmlspecialchars($error->getMessage()));
}
