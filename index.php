<?php
$judul = 'Beranda';
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$jmlMuzakki = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM muzakki"))['c'];
$totalZakat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(jumlah_zakat),0) s FROM transaksi_zakat"))['s'];
require_once __DIR__ . '/includes/header.php';
?>
<div class="hero">
    <h1>Sistem Pengelolaan Zakat Maal</h1>
    <p>Kelola pembayar zakat (muzakki) dan kumpulkan zakat maal dengan mudah, transparan, dan sesuai ketentuan syariat.</p>
</div>

<div class="container">
    <div class="grid-3">
        <div class="stat-card">
            <div class="angka"><?= $jmlMuzakki ?></div>
            <div class="label">Total Muzakki</div>
        </div>
        <div class="stat-card">
            <div class="angka"><?= rupiah($totalZakat) ?></div>
            <div class="label">Total Zakat Terkumpul</div>
        </div>
        <div class="stat-card">
            <div class="angka">85 gr</div>
            <div class="label">Nisab Zakat Maal</div>
        </div>
    </div>

    <div class="grid-3">
        <div class="card">
            <h2>Kalkulator Zakat</h2>
            <p>Hitung zakat maal Anda secara otomatis berdasarkan total harta (uang, tabungan, dan emas).</p>
            <p style="margin-top:12px"><a class="btn" href="kalkulator.php">Hitung Sekarang</a></p>
        </div>
        <div class="card">
            <h2>Info & Panduan</h2>
            <p>Pelajari nisab, kadar zakat (2,5%), syarat wajib zakat, dan jenis harta yang dizakati.</p>
            <p style="margin-top:12px"><a class="btn btn-kuning" href="info.php">Baca Panduan</a></p>
        </div>
        <div class="card">
            <h2>Login Admin</h2>
            <p>Masuk ke panel pengelolaan untuk mengelola muzakki, transaksi, dan mencetak laporan.</p>
            <p style="margin-top:12px"><a class="btn btn-ekor" href="login.php">Masuk Admin</a></p>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>