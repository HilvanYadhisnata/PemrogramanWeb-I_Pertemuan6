<?php
/** @var string $title */
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Data Pelanggan') ?> | PHP PDO CRUD</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="topbar">
    <div class="container">
        <a href="index.php" class="brand">PHP &amp; PDO CRUD</a>
        <span class="subtitle">Data Pelanggan</span>
    </div>
</header>
<main class="container">
<?php if ($flash): ?>
    <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
<?php endif; ?>
