<?php
$judul = 'Data Muzakki';
$halaman = 'muzakki';
require_once __DIR__ . '/../includes/header_admin.php';

$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $like = '%' . $q . '%';
    $stmt = mysqli_prepare($conn, "SELECT * FROM muzakki WHERE nama LIKE ? OR no_hp LIKE ? OR alamat LIKE ? ORDER BY nama");
    mysqli_stmt_bind_param($stmt, 'sss', $like, $like, $like);
    mysqli_stmt_execute($stmt);
    $data = mysqli_stmt_get_result($stmt);
    $totalMuzakki = mysqli_num_rows($data);
} else {
    $data = mysqli_query($conn, "SELECT * FROM muzakki ORDER BY nama");
    $totalMuzakki = mysqli_num_rows($data);
}
?>
<div class="container">
    <h1 class="halaman">Data Muzakki</h1>

    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['pesan']); unset($_SESSION['pesan']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="bar-aksi">
        <form method="GET" action="muzakki.php" class="filters">
            <input type="text" name="q" placeholder="Cari nama / no HP / alamat..." value="<?= htmlspecialchars($q) ?>">
            <button class="btn btn-kecil" type="submit">Cari</button>
            <?php if ($q !== ''): ?><a class="btn btn-kecil btn-merah" href="muzakki.php">Reset</a><?php endif; ?>
        </form>
        <a class="btn" href="muzakki_tambah.php">+ Tambah Muzakki</a>
    </div>

    <div class="table-wrap">
        <table>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Jenis Harta</th>
                <th>Total Harta</th>
                <th>No. HP</th>
                <th>Aksi</th>
            </tr>
            <?php if ($totalMuzakki > 0): $i = 1; while ($r = mysqli_fetch_assoc($data)): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($r['nama']) ?></td>
                <td><?= $r['jenis_kelamin'] ?></td>
                <td><?= htmlspecialchars($r['jenis_harta']) ?></td>
                <td><?= rupiah($r['total_harta']) ?></td>
                <td><?= htmlspecialchars($r['no_hp']) ?></td>
                <td>
                    <a class="btn btn-kecil btn-biru" href="muzakki_edit.php?id=<?= $r['id'] ?>">Edit</a>
                    <a class="btn btn-kecil btn-merah" href="muzakki_hapus.php?id=<?= $r['id'] ?>" onclick="return confirm('Hapus muzakki ini? Semua transaksinya ikut terhapus.')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="7" style="text-align:center;color:var(--abu)">Tidak ada data muzakki.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>