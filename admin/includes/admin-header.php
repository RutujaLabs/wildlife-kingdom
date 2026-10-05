<?php
$adminPageTitle = $adminPageTitle ?? 'Animal Manager';
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo htmlspecialchars($adminPageTitle); ?> | Wildlife Kingdom Admin</title>
	<link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body class="admin-body">
<header class="admin-topbar">
	<a class="admin-brand" href="list.php">Wildlife Kingdom <span>Admin</span></a>
	<a class="admin-public-link" href="../../public_html/animals.php">View public animals</a>
</header>
<div class="admin-layout">
	<?php require __DIR__ . '/admin-sidebar.php'; ?>
	<main class="admin-main">
		<div class="admin-heading">
			<div><p class="admin-kicker">Content management</p><h1><?php echo htmlspecialchars($adminPageTitle); ?></h1></div>
			<a class="admin-button" href="add.php">+ Add Animal</a>
		</div>
