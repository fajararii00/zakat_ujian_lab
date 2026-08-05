<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
$namaAdmin = $_SESSION['nama'];
$judul = isset($judul) ? $judul : 'Dashboard';
$halaman = isset($halaman) ? $halaman : 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $judul ?> &mdash; ZakatMaal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<nav class="navbar">
    <a class="brand" href="index.php">Zakat<span>Maal</span></a>
    <div class="nav-links">
        <a href="index.php" class="<?= $halaman == 'dashboard' ? 'active' : '' ?>">Dashboard</a>
        <a href="muzakki.php" class="<?= $halaman == 'muzakki' ? 'active' : '' ?>">Muzakki</a>
        <a href="transaksi.php" class="<?= $halaman == 'transaksi' ? 'active' : '' ?>">Transaksi</a>
        <a href="laporan.php" class="<?= $halaman == 'laporan' ? 'active' : '' ?>">Laporan</a>
        <a href="users.php" class="<?= $halaman == 'users' ? 'active' : '' ?>">Users</a>
        <a class="btn-logout" href="../logout.php">Keluar (<?= htmlspecialchars($namaAdmin) ?>)</a>
    </div>
</nav>
