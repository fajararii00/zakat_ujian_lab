<?php
$judul = 'Kalkulator Zakat';
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$uangTunai = 0;
$tabungan = 0;
$emasGram = 0;
$hasil = null;
$nisab = null;
$totalHarta = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uangTunai = (float) str_replace(['.', ' '], '', $_POST['uang_tunai'] ?? 0);
    $tabungan  = (float) str_replace(['.', ' '], '', $_POST['tabungan'] ?? 0);
    $emasGram  = (float) str_replace([',', ' '], '.', $_POST['emas_gram'] ?? 0);

    $totalHarta = $uangTunai + $tabungan + (hargaEmasPerGram() * $emasGram);
    $nisab = hargaEmasPerGram() * NISAB_GRAM;
    $hasil = hitungZakat($totalHarta);
}

require_once __DIR__ . '/includes/header.php';
?>
<div class="container">
    <h1 class="halaman">Kalkulator Zakat Maal</h1>

    <div class="grid-2">
        <div class="card">
            <h2>Masukkan Harta Anda</h2>
            <form method="POST" action="kalkulator.php">
                <div class="form-group">
                    <label>Uang Tunai / Kas</label>
                    <input type="text" name="uang_tunai" value="<?= $uangTunai > 0 ? number_format($uangTunai, 0, ',', '.') : '' ?>" placeholder="Contoh: 50.000.000" oninput="formatAngka(this)">
                </div>
                <div class="form-group">
                    <label>Tabungan / Deposito</label>
                    <input type="text" name="tabungan" value="<?= $tabungan > 0 ? number_format($tabungan, 0, ',', '.') : '' ?>" placeholder="Contoh: 100.000.000" oninput="formatAngka(this)">
                </div>
                <div class="form-group">
                    <label>Emas (gram) &mdash; harga per gram <?= rupiah(hargaEmasPerGram()) ?></label>
                    <input type="number" step="0.01" name="emas_gram" value="<?= $emasGram > 0 ? $emasGram : '' ?>" placeholder="Contoh: 100">
                </div>
                <button type="submit" class="btn">Hitung Zakat</button>
            </form>
        </div>

        <div>
            <div class="card">
                <h2>Rincian Perhitungan</h2>
                <?php if ($hasil === null): ?>
                    <p style="color:var(--abu)">Isi formulir lalu klik <strong>Hitung Zakat</strong> untuk melihat hasil perhitungan.</p>
                <?php else: ?>
                    <table>
                        <tr>
                            <td>Uang Tunai</td>
                            <td align="right"><?= rupiah($uangTunai) ?></td>
                        </tr>
                        <tr>
                            <td>Tabungan</td>
                            <td align="right"><?= rupiah($tabungan) ?></td>
                        </tr>
                        <tr>
                            <td>Emas (<?= $emasGram ?> gr &times; <?= rupiah(hargaEmasPerGram()) ?>)</td>
                            <td align="right"><?= rupiah(hargaEmasPerGram() * $emasGram) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Total Harta</strong></td>
                            <td align="right"><strong><?= rupiah($totalHarta) ?></strong></td>
                        </tr>
                        <tr>
                            <td>Nisab (<?= NISAB_GRAM ?> gr emas)</td>
                            <td align="right"><?= rupiah($nisab) ?></td>
                        </tr>
                    </table>

                    <div class="result-box">
                        <?php if ($hasil > 0): ?>
                            <div class="label">Zakat yang wajib Anda bayar (<?= KADAR_ZAKAT * 100 ?>%)</div>
                            <div class="total"><?= rupiah($hasil) ?></div>
                        <?php else: ?>
                            <div class="total" style="color:var(--emas)">Tidak Wajib Zakat</div>
                            <p>Total harta Anda belum mencapai nisab (<?= rupiah($nisab) ?>).</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function formatAngka(input) {
    var v = input.value.replace(/\D/g, '');
    input.value = v ? Number(v).toLocaleString('id-ID').replace(/,/g, '.') : '';
}
</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>