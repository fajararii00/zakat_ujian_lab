<?php
$judul = 'Transaksi Zakat';
$halaman = 'transaksi';
require_once __DIR__ . '/../includes/header_admin.php';

$q = trim($_GET['q'] ?? '');
$perHalaman = 10;
$halaman = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($halaman - 1) * $perHalaman;

if ($q !== '') {
    $like = '%' . $q . '%';
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) c FROM transaksi_zakat t JOIN muzakki m ON m.id = t.muzakki_id WHERE m.nama LIKE ?");
    mysqli_stmt_bind_param($stmt, 's', $like);
    mysqli_stmt_execute($stmt);
    $totalTransaksi = (int) mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['c'];

    $stmt = mysqli_prepare($conn, "SELECT t.*, m.nama FROM transaksi_zakat t JOIN muzakki m ON m.id = t.muzakki_id WHERE m.nama LIKE ? ORDER BY t.tanggal_pembayaran DESC, t.id DESC LIMIT ? OFFSET ?");
    mysqli_stmt_bind_param($stmt, 'sii', $like, $perHalaman, $offset);
    mysqli_stmt_execute($stmt);
    $data = mysqli_stmt_get_result($stmt);
} else {
    $totalTransaksi = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM transaksi_zakat"))['c'];
    $data = mysqli_query($conn, "SELECT t.*, m.nama FROM transaksi_zakat t JOIN muzakki m ON m.id = t.muzakki_id ORDER BY t.tanggal_pembayaran DESC, t.id DESC LIMIT $perHalaman OFFSET $offset");
}

$totalHalaman = max(1, (int) ceil($totalTransaksi / $perHalaman));
?>
<div class="container">
    <h1 class="halaman">Transaksi Zakat</h1>

    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['pesan']); unset($_SESSION['pesan']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="bar-aksi">
        <form method="GET" action="transaksi.php" class="filters">
            <input type="text" name="q" placeholder="Cari nama muzakki..." value="<?= htmlspecialchars($q) ?>">
            <button class="btn btn-kecil" type="submit">Cari</button>
            <?php if ($q !== ''): ?><a class="btn btn-kecil btn-merah" href="transaksi.php">Reset</a><?php endif; ?>
        </form>
        <a class="btn" href="transaksi_tambah.php">+ Transaksi Baru</a>
    </div>

    <div class="table-wrap">
        <table>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Muzakki</th>
                <th>Jumlah Zakat</th>
                <th>Metode</th>
                <th>Aksi</th>
            </tr>
            <?php if (mysqli_num_rows($data) > 0): $i = $offset + 1; while ($r = mysqli_fetch_assoc($data)): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($r['tanggal_pembayaran']) ?></td>
                <td><?= htmlspecialchars($r['nama']) ?></td>
                <td><?= rupiah($r['jumlah_zakat']) ?></td>
                <td><?= $r['metode'] ?></td>
                <td>
                    <a class="btn btn-kecil btn-biru" href="transaksi_edit.php?id=<?= $r['id'] ?>">Edit</a>
                    <a class="btn btn-kecil btn-merah" href="transaksi_hapus.php?id=<?= $r['id'] ?>" onclick="return confirm('Hapus transaksi ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="6" style="text-align:center;color:var(--abu)">Tidak ada transaksi.</td></tr>
            <?php endif; ?>
        </table>
    </div>

    <?= paginasi('transaksi.php', $_GET, $halaman, $totalHalaman) ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>