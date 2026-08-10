<?php
$judul = 'Dashboard';
$halaman = 'dashboard';
require_once __DIR__ . '/../includes/header_admin.php';

$jmlMuzakki = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM muzakki"))['c'];
$jmlTransaksi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM transaksi_zakat"))['c'];
$totalZakat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(jumlah_zakat),0) s FROM transaksi_zakat"))['s'];
$jmlUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM users"))['c'];

$transaksiTerbaru = mysqli_query($conn, "SELECT t.*, m.nama FROM transaksi_zakat t JOIN muzakki m ON m.id = t.muzakki_id ORDER BY t.tanggal_pembayaran DESC, t.id DESC LIMIT 5");
?>
<div class="container">
    <h1 class="brand">Zakat <span style="color: #FFD700;">Maal</span></h1>
    <h1 class="halaman">Dashboard</h1>

    <div class="grid-3">
        <div class="stat-card">
            <div class="angka"><?= $jmlMuzakki ?></div>
            <div class="label">Muzakki Terdaftar</div>
        </div>
        <div class="stat-card">
            <div class="angka"><?= $jmlTransaksi ?></div>
            <div class="label">Total Transaksi</div>
        </div>
        <div class="stat-card">
            <div class="angka"><?= rupiah($totalZakat) ?></div>
            <div class="label">Zakat Terkumpul</div>
        </div>
    </div>

    <div class="card">
        <div class="bar-aksi">
            <h2 style="margin-bottom:0">Transaksi Terbaru</h2>
            <a class="btn btn-kuning" href="transaksi_tambah.php">+ Transaksi Baru</a>
        </div>
        <div class="table-wrap">
            <table>
                <tr>
                    <th>No</th>
                    <th>Muzakki</th>
                    <th>Jumlah Zakat</th>
                    <th>Metode</th>
                    <th>Tanggal</th>
                </tr>
                <?php if (mysqli_num_rows($transaksiTerbaru) > 0): $i = 1; while ($row = mysqli_fetch_assoc($transaksiTerbaru)): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= rupiah($row['jumlah_zakat']) ?></td>
                    <td><?= $row['metode'] ?></td>
                    <td><?= htmlspecialchars($row['tanggal_pembayaran']) ?></td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="5" style="text-align:center;color:var(--abu)">Belum ada transaksi.</td></tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <p style="color:var(--abu);font-size:.85rem">Jumlah admin: <?= $jmlUsers ?></p>

    <div class="card">
        <div class="bar-aksi">
            <div>
                <h2 style="margin-bottom:0">Harga Emas per Gram</h2>
                <p style="margin:.25rem 0 0;color:var(--abu)">Nisab (85 gr): <?= rupiah(hargaEmasPerGram() * NISAB_GRAM) ?></p>
            </div>
            <a class="btn btn-kuning" href="pengaturan.php">Ubah Harga</a>
        </div>
        <p style="font-size:1.4rem;margin:1rem 0 0"><?= rupiah(hargaEmasPerGram()) ?></p>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>