<?php
$judul = 'Edit Transaksi';
$halaman = 'transaksi';
require_once __DIR__ . '/../includes/header_admin.php';

$id = (int) ($_GET['id'] ?? 0);
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM transaksi_zakat WHERE id = $id"));
if (!$data) {
    header("Location: transaksi.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $muzakki_id = (int) ($_POST['muzakki_id'] ?? 0);
    $jumlah_zakat = (float) str_replace(['.', ' '], '', $_POST['jumlah_zakat'] ?? 0);
    $metode = $_POST['metode'] ?? 'Tunai';
    $tanggal = $_POST['tanggal_pembayaran'] ?? date('Y-m-d');

    if ($muzakki_id <= 0 || $jumlah_zakat <= 0) {
        $error = 'Pilih muzakki dan pastikan jumlah zakat lebih dari 0!';
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE transaksi_zakat SET muzakki_id=?, jumlah_zakat=?, metode=?, tanggal_pembayaran=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'idssi', $muzakki_id, $jumlah_zakat, $metode, $tanggal, $id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan'] = 'Transaksi berhasil diperbarui.';
            header("Location: transaksi.php");
            exit;
        }
        $error = 'Gagal memperbarui transaksi: ' . mysqli_error($conn);
    }
}

$muzakki = mysqli_query($conn, "SELECT id, nama, total_harta FROM muzakki ORDER BY nama");
?>
<div class="container">
    <h1 class="halaman">Edit Transaksi Zakat</h1>

    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="card" style="max-width:600px">
        <form method="POST" action="transaksi_edit.php?id=<?= $id ?>">
            <div class="form-group">
                <label>Muzakki</label>
                <select name="muzakki_id" required>
                    <?php while ($m = mysqli_fetch_assoc($muzakki)): ?>
                        <option value="<?= $m['id'] ?>" <?= $m['id'] == $data['muzakki_id'] ? 'selected' : '' ?>><?= htmlspecialchars($m['nama']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Jumlah Zakat (Rp)</label>
                <input type="text" name="jumlah_zakat" value="<?= number_format((float)$data['jumlah_zakat'], 2, ',', '.') ?>" oninput="formatAngka(this)">
            </div>
            <div class="form-group">
                <label>Metode Pembayaran</label>
                <select name="metode">
                    <option value="Tunai" <?= $data['metode'] == 'Tunai' ? 'selected' : '' ?>>Tunai</option>
                    <option value="Transfer" <?= $data['metode'] == 'Transfer' ? 'selected' : '' ?>>Transfer</option>
                    <option value="Lainnya" <?= $data['metode'] == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal Pembayaran</label>
                <input type="date" name="tanggal_pembayaran" value="<?= htmlspecialchars($data['tanggal_pembayaran']) ?>" required>
            </div>
            <a class="btn btn-kuning" href="transaksi.php">Kembali</a>
            <button type="submit" class="btn">Simpan Perubahan</button>
        </form>
    </div>
</div>
<script>
function formatAngka(input) {
    var v = input.value.replace(/[^\d,]/g, '');
    input.value = v;
}
</script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>