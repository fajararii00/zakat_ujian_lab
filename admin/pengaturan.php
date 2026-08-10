<?php
$judul = 'Pengaturan';
$halaman = 'pengaturan';
require_once __DIR__ . '/../includes/header_admin.php';

$error = '';
$sukses = '';

if (isset($_SESSION['pesan'])) {
    $sukses = $_SESSION['pesan'];
    unset($_SESSION['pesan']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $harga = str_replace(['.', ' '], '', trim($_POST['harga_emas'] ?? ''));
    if (!is_numeric($harga) || (float) $harga <= 0) {
        $error = 'Harga emas harus berupa angka lebih dari 0!';
    } else {
        if (simpanHargaEmas($harga)) {
            $_SESSION['pesan'] = 'Harga emas per gram berhasil diperbarui.';
            header("Location: pengaturan.php");
            exit;
        }
        $error = 'Gagal menyimpan: ' . mysqli_error($conn);
    }
}

$hargaSaatIni = hargaEmasPerGram();
?>
<div class="container">
    
    <h1 class="halaman">Pengaturan</h1>

    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($sukses): ?><div class="alert alert-success"><?= htmlspecialchars($sukses) ?></div><?php endif; ?>

    <div class="card" style="max-width:600px">
        <h2>Harga Emas per Gram</h2>
        <p style="color:var(--abu)">Nilai ini dipakai untuk menghitung nisab dan zakat di kalkulator publik.</p>
        <form method="POST" action="pengaturan.php">
            <div class="form-group">
                <label>Harga Emas per Gram (Rp)</label>
                <input type="text" name="harga_emas" value="<?= number_format($hargaSaatIni, 0, ',', '.') ?>" placeholder="Contoh: 1.300.000" oninput="formatAngka(this)">
                <p style="color:var(--abu);font-size:.85rem;margin-top:.4rem">Nisab (85 gr): <?= rupiah($hargaSaatIni * NISAB_GRAM) ?></p>
            </div>
            <a class="btn btn-kuning" href="index.php">Kembali</a>
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
