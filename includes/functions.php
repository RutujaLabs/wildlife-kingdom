<?php

function animal_ensure_table(mysqli $conn): void
{
	$sql = "CREATE TABLE IF NOT EXISTS animals (
		id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		slug VARCHAR(120) NOT NULL UNIQUE,
		habitat_id INT NULL,
		name VARCHAR(150) NOT NULL,
		scientific_name VARCHAR(180) NOT NULL DEFAULT '',
		description TEXT NOT NULL,
		habitat VARCHAR(150) NOT NULL DEFAULT '',
		image VARCHAR(255) NOT NULL DEFAULT '',
		conservation_status VARCHAR(100) NOT NULL DEFAULT 'Not assessed',
		species VARCHAR(150) NOT NULL DEFAULT '',
		diet VARCHAR(150) NOT NULL DEFAULT '',
		lifespan VARCHAR(100) NOT NULL DEFAULT '',
		fun_fact TEXT NULL,
		is_featured TINYINT(1) NOT NULL DEFAULT 0,
		created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

	if (!$conn->query($sql)) {
		throw new RuntimeException('Could not prepare the animals table: ' . $conn->error);
	}

	$columns = [
		'slug' => "VARCHAR(120) NOT NULL DEFAULT ''",
		'habitat_id' => 'INT NULL',
		'name' => "VARCHAR(150) NOT NULL DEFAULT ''",
		'scientific_name' => "VARCHAR(180) NOT NULL DEFAULT ''",
		'description' => 'TEXT NULL',
		'habitat' => "VARCHAR(150) NOT NULL DEFAULT ''",
		'image' => "VARCHAR(255) NOT NULL DEFAULT ''",
		'conservation_status' => "VARCHAR(100) NOT NULL DEFAULT 'Not assessed'",
		'species' => "VARCHAR(150) NOT NULL DEFAULT ''",
		'diet' => "VARCHAR(150) NOT NULL DEFAULT ''",
		'lifespan' => "VARCHAR(100) NOT NULL DEFAULT ''",
		'fun_fact' => 'TEXT NULL',
		'is_featured' => 'TINYINT(1) NOT NULL DEFAULT 0',
		'created_at' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
	];
	foreach ($columns as $name => $definition) {
		$result = $conn->query("SHOW COLUMNS FROM animals LIKE '" . $conn->real_escape_string($name) . "'");
		if (!$result) {
			throw new RuntimeException('Could not inspect the animals table: ' . $conn->error);
		}
		if ($result->num_rows === 0 && !$conn->query("ALTER TABLE animals ADD COLUMN `" . $name . "` " . $definition)) {
			throw new RuntimeException('Could not add the animal ' . $name . ' field: ' . $conn->error);
		}
	}
}

function animal_post_value(string $key, int $maxLength = 5000): string
{
	$value = trim((string)($_POST[$key] ?? ''));
	if (strlen($value) > $maxLength) {
		throw new InvalidArgumentException(ucfirst(str_replace('_', ' ', $key)) . ' is too long.');
	}
	return $value;
}

function animal_make_slug(string $name): string
{
	$slug = strtolower(trim((string)preg_replace('/[^a-z0-9]+/i', '-', $name), '-'));
	if ($slug === '') {
		$slug = 'animal';
	}
	return substr($slug, 0, 100) . '-' . bin2hex(random_bytes(4));
}

function animal_habitat_options(mysqli $conn): array
{
	$result = $conn->query('SELECT id, name FROM habitats ORDER BY name ASC');
	if (!$result) {
		throw new RuntimeException('Could not load habitats: ' . $conn->error);
	}
	return $result->fetch_all(MYSQLI_ASSOC);
}

function animal_validate_habitat(mysqli $conn): ?int
{
	$value = trim((string)($_POST['habitat_id'] ?? ''));
	if ($value === '') {
		return null;
	}
	if (!ctype_digit($value) || (int)$value < 1) {
		throw new InvalidArgumentException('Choose a valid habitat.');
	}
	$id = (int)$value;
	$stmt = $conn->prepare('SELECT id FROM habitats WHERE id = ?');
	$stmt->bind_param('i', $id);
	$stmt->execute();
	if (!$stmt->get_result()->fetch_assoc()) {
		throw new InvalidArgumentException('Choose a valid habitat.');
	}
	return $id;
}

function animal_csrf_token(): string
{
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if (empty($_SESSION['animal_csrf_token'])) {
		$_SESSION['animal_csrf_token'] = bin2hex(random_bytes(32));
	}
	return $_SESSION['animal_csrf_token'];
}

function animal_verify_csrf(): void
{
	$submitted = (string)($_POST['csrf_token'] ?? '');
	if ($submitted === '' || !hash_equals(animal_csrf_token(), $submitted)) {
		throw new RuntimeException('Your form session expired. Please try again.');
	}
}

function animal_upload_image(array $file, string $currentImage = ''): string
{
	if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
		return $currentImage;
	}
	if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'] ?? '')) {
		throw new RuntimeException('The image upload did not complete.');
	}
	if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
		throw new RuntimeException('Images must be 5 MB or smaller.');
	}

	$imageInfo = @getimagesize($file['tmp_name']);
	$mime = $imageInfo['mime'] ?? '';
	$extensions = [
		'image/jpeg' => 'jpg',
		'image/png' => 'png',
		'image/gif' => 'gif',
		'image/webp' => 'webp',
	];
	if (!isset($extensions[$mime])) {
		throw new RuntimeException('Choose a valid JPG, PNG, GIF, or WEBP image.');
	}

	$directory = dirname(__DIR__) . '/uploads/animals';
	if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
		throw new RuntimeException('The animal image folder is unavailable.');
	}

	$filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mime];
	if (!move_uploaded_file($file['tmp_name'], $directory . '/' . $filename)) {
		throw new RuntimeException('The image could not be saved.');
	}
	return $filename;
}

function animal_remove_image(string $filename): void
{
	if ($filename === '' || basename($filename) !== $filename) {
		return;
	}
	$path = dirname(__DIR__) . '/uploads/animals/' . $filename;
	if (is_file($path)) {
		unlink($path);
	}
}
