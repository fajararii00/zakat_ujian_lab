<?php
$judul = 'Tambah Transaksi';
$halaman = 'transaksi';
require_once __DIR__ . '/../includes/header_admin.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $muzakki_id = (int) ($_POST['muzakki_id'] ?? 0);
    $jumlah_zakat = (float) str_replace(['.', ' '], '', $_POST['jumlah_zakat'] ?? 0);
    $metode = $_POST['metode'] ?? 'Tunai';
    $tanggal = $_POST['tanggal_pembayaran'] ?? date('Y-m-d');

    if ($muzakki_id <= 0 || $jumlah_zakat <= 0) {
        $error = 'Pilih muzakki dan pastikan jumlah zakat lebih dari 0!';
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO transaksi_zakat (muzakki_id, jumlah_zakat, metode, tanggal_pembayaran) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'idss', $muzakki_id, $jumlah_zakat, $metode, $tanggal);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan'] = 'Transaksi zakat berhasil dicatat.';
            header("Location: transaksi.php");
            exit;
        }
        $error = 'Gagal menyimpan transaksi: ' . mysqli_error($conn);
    }
}

$muzakki = mysqli_query($conn, "SELECT id, nama, total_harta FROM muzakki ORDER BY nama");
$muzakkiList = [];
while ($m = mysqli_fetch_assoc($muzakki)) {
    $muzakkiList[] = ['id' => $m['id'], 'nama' => $m['nama'], 'harta' => (float)$m['total_harta']];
}
?>
<div class="container">
    <h1 class="halaman">Tambah Transaksi Zakat</h1>

    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="card" style="max-width:600px">
        <form method="POST" action="transaksi_tambah.php">
            <div class="form-group">
                <label>Muzakki</label>
                <select name="muzakki_id" id="muzakki_id" required>
                    <option value="">-- Pilih Muzakki --</option>
                    <?php foreach ($muzakkiList as $m): ?>
                        <option value="<?= $m['id'] ?>" data-harta="<?= $m['harta'] ?>"><?= htmlspecialchars($m['nama']) ?></option>
                    <?php endforeach; ?>
                </select>
                <small style="color:var(--abu)">Total harta muzakki akan otomatis dihitung menjadi jumlah zakat.</small>
            </div>
            <div class="form-group">
                <label>Jumlah Zakat (Rp) <span style="color:var(--abu)">(hitung otomatis)</span></label>
                <input type="text" name="jumlah_zakat" id="jumlah_zakat" placeholder="Kosongkan untuk hitung otomatis" oninput="formatAngka(this)">
                <small style="color:var(--abu)">Nisab: <?= rupiah(hargaEmasPerGram() * NISAB_GRAM) ?> &middot; Kadar: <?= KADAR_ZAKAT * 100 ?>%</small>
            </div>
            <div class="form-group">
                <label>Metode Pembayaran</label>
                <select name="metode">
                    <option value="Tunai">Tunai</option>
                    <option value="Transfer">Transfer</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal Pembayaran</label>
                <input type="date" name="tanggal_pembayaran" value="<?= date('Y-m-d') ?>" required>
            </div>
            <a class="btn btn-kuning" href="transaksi.php">Kembali</a>
            <button type="submit" class="btn">Simpan</button>
        </form>
    </div>
</div>
<script>
var kadarZakat = <?= KADAR_ZAKAT ?>;
var nisab = <?= hargaEmasPerGram() * NISAB_GRAM ?>;

function hitungOtomatis() {
    var sel = document.getElementById('muzakki_id');
    var opt = sel.options[sel.selectedIndex];
    var field = document.getElementById('jumlah_zakat');
    if (!opt || !opt.dataset.harta) return;
    var harta = parseFloat(opt.dataset.harta);
    var zakat = harta >= nisab ? Math.round(harta * kadarZakat * 100) / 100 : 0;
    field.value = zakat > 0 ? zakat.toLocaleString('id-ID').replace(/,/g, '.') : '';
    field.dataset.manual = '0';
}

document.getElementById('muzakki_id').addEventListener('change', hitungOtomatis);
document.getElementById('jumlah_zakat').addEventListener('input', function () {
    this.dataset.manual = '1';
});

function formatAngka(input) {
    if (input.dataset.manual === '1') {
        var v = input.value.replace(/\D/g, '');
        input.value = v ? Number(v).toLocaleString('id-ID').replace(/,/g, '.') : '';
    }
}
</script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>