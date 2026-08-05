<?php
$judul = 'Tambah Muzakki';
$halaman = 'muzakki';
require_once __DIR__ . '/../includes/header_admin.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? 'Laki-laki';
    $alamat = trim($_POST['alamat'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');
    $jenis_harta = trim($_POST['jenis_harta'] ?? 'Campuran');
    $total_harta = (float) str_replace(['.', ' '], '', $_POST['total_harta'] ?? 0);

    if ($nama === '' || $total_harta < 0) {
        $error = 'Nama wajib diisi dan total harta tidak boleh negatif!';
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO muzakki (nama, jenis_kelamin, alamat, no_hp, jenis_harta, total_harta) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'sssssd', $nama, $jenis_kelamin, $alamat, $no_hp, $jenis_harta, $total_harta);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan'] = 'Data muzakki berhasil ditambahkan.';
            header("Location: muzakki.php");
            exit;
        }
        $error = 'Gagal menyimpan data: ' . mysqli_error($conn);
    }
}
?>
<div class="container">
    <h1 class="halaman">Tambah Muzakki</h1>

    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="card" style="max-width:600px">
        <form method="POST" action="muzakki_tambah.php">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" required>
            </div>
            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin">
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>No. HP</label>
                <input type="text" name="no_hp" placeholder="08xxxxxxxxxx">
            </div>
            <div class="form-group">
                <label>Jenis Harta</label>
                <input type="text" name="jenis_harta" value="Campuran">
            </div>
            <div class="form-group">
                <label>Total Harta (Rp)</label>
                <input type="text" name="total_harta" placeholder="Contoh: 150000000" oninput="formatAngka(this)">
            </div>
            <a class="btn btn-kuning" href="muzakki.php">Kembali</a>
            <button type="submit" class="btn">Simpan</button>
        </form>
    </div>
</div>
<script>
function formatAngka(input) {
    var v = input.value.replace(/\D/g, '');
    input.value = v ? Number(v).toLocaleString('id-ID').replace(/,/g, '.') : '';
}
</script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>