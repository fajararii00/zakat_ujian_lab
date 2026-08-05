<?php
$judul = 'Laporan Zakat';
$halaman = 'laporan';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

$dari = $_GET['dari'] ?? '';
$sampai = $_GET['sampai'] ?? '';
$metode = $_GET['metode'] ?? '';

$where = [];
$params = [];
$types = '';
if ($dari !== '') { $where[] = "t.tanggal_pembayaran >= ?"; $params[] = $dari; $types .= 's'; }
if ($sampai !== '') { $where[] = "t.tanggal_pembayaran <= ?"; $params[] = $sampai; $types .= 's'; }
if ($metode !== '') { $where[] = "t.metode = ?"; $params[] = $metode; $types .= 's'; }

$sql = "SELECT t.*, m.nama FROM transaksi_zakat t JOIN muzakki m ON m.id = t.muzakki_id";
if ($where) { $sql .= " WHERE " . implode(" AND ", $where); }
$sql .= " ORDER BY t.tanggal_pembayaran DESC, t.id DESC";

if ($params) {
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $data = mysqli_stmt_get_result($stmt);
} else {
    $data = mysqli_query($conn, $sql);
}

$total = 0;
$rows = [];
while ($r = mysqli_fetch_assoc($data)) {
    $rows[] = $r;
    $total += (float) $r['jumlah_zakat'];
}

if (isset($_GET['csv'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="laporan_zakat.csv"');
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF");
    fputcsv($out, ['No', 'Tanggal', 'Muzakki', 'Jumlah Zakat', 'Metode']);
    $i = 1;
    foreach ($rows as $r) {
        fputcsv($out, [$i++, $r['tanggal_pembayaran'], $r['nama'], $r['jumlah_zakat'], $r['metode']]);
    }
    fputcsv($out, ['', '', 'TOTAL', number_format($total, 2), '']);
    fclose($out);
    exit;
}

require_once __DIR__ . '/../includes/header_admin.php';
?>
<div class="container">
    <h1 class="halaman">Laporan Zakat</h1>

    <div class="bar-aksi">
        <form method="GET" action="laporan.php" class="filters">
            <input type="date" name="dari" value="<?= htmlspecialchars($dari) ?>" title="Dari tanggal">
            <span>s/d</span>
            <input type="date" name="sampai" value="<?= htmlspecialchars($sampai) ?>" title="Sampai tanggal">
            <select name="metode">
                <option value="">Semua Metode</option>
                <option value="Tunai" <?= $metode == 'Tunai' ? 'selected' : '' ?>>Tunai</option>
                <option value="Transfer" <?= $metode == 'Transfer' ? 'selected' : '' ?>>Transfer</option>
                <option value="Lainnya" <?= $metode == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
            </select>
            <button class="btn btn-kecil" type="submit">Filter</button>
            <?php if ($dari || $sampai || $metode): ?>
                <a class="btn btn-kecil btn-merah" href="laporan.php">Reset</a>
            <?php endif; ?>
        </form>
        <div>
            <button class="btn btn-kecil btn-kuning" onclick="window.print()">Cetak</button>
            <a class="btn btn-kecil btn-ekor" href="laporan.php?<?= http_build_query(['dari' => $dari, 'sampai' => $sampai, 'metode' => $metode, 'csv' => 1]) ?>">Export CSV</a>
        </div>
    </div>

    <div class="card" style="padding:16px 24px">
        <div class="table-wrap">
            <table>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Muzakki</th>
                    <th>Jumlah Zakat</th>
                    <th>Metode</th>
                </tr>
                <?php if (count($rows) > 0): $i = 1; foreach ($rows as $r): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($r['tanggal_pembayaran']) ?></td>
                    <td><?= htmlspecialchars($r['nama']) ?></td>
                    <td><?= rupiah($r['jumlah_zakat']) ?></td>
                    <td><?= $r['metode'] ?></td>
                </tr>
                <?php endforeach; ?>
                <tr style="background:var(--hijau-muda);font-weight:700">
                    <td colspan="3">TOTAL</td>
                    <td><?= rupiah($total) ?></td>
                    <td><?= count($rows) ?> transaksi</td>
                </tr>
                <?php else: ?>
                <tr><td colspan="5" style="text-align:center;color:var(--abu)">Tidak ada data pada periode terpilih.</td></tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>