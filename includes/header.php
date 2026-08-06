<!-- ini layout guest -->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($judul) ? $judul : 'Zakat Maal' ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar">
    <a class="brand" href="index.php">Zakat<span>Maal</span></a>
    <div class="nav-links">
        <a href="index.php">Beranda</a>
        <a href="info.php">Info Zakat</a>
        <a href="kalkulator.php">Kalkulator</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="admin/index.php">Dashboard</a>
            <a class="btn-logout" href="logout.php">Keluar</a>
        <?php else: ?>
            <a class="btn-logout" href="login.php">Login Admin</a>
        <?php endif; ?>
    </div>
</nav>
