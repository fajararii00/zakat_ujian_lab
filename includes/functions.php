<?php
// fungsi & konstanta umum (dipakai publik dan admin)
define('NISAB_GRAM', 85);
define('KADAR_ZAKAT', 0.025);
define('HARGA_EMAS_DEFAULT', 1300000);

function hargaEmasPerGram() {
    global $conn;
    if (!isset($conn)) {
        return HARGA_EMAS_DEFAULT;
    }
    $hasil = mysqli_query($conn, "SELECT nilai FROM settings WHERE kunci = 'harga_emas_per_gram' LIMIT 1");
    if ($hasil && $row = mysqli_fetch_assoc($hasil)) {
        if (is_numeric($row['nilai'])) {
            return (float) $row['nilai'];
        }
    }
    return HARGA_EMAS_DEFAULT;
}

function simpanHargaEmas($nilai) {
    global $conn;
    $nilai = (float) $nilai;
    $stmt = mysqli_prepare($conn, "INSERT INTO settings (kunci, nilai) VALUES ('harga_emas_per_gram', ?)
                                   ON DUPLICATE KEY UPDATE nilai = VALUES(nilai)");
    mysqli_stmt_bind_param($stmt, 'd', $nilai);
    return mysqli_stmt_execute($stmt);
}

function hitungZakat($totalHarta) {
    $totalHarta = (float) $totalHarta;
    $nisab = hargaEmasPerGram() * NISAB_GRAM;
    if ($totalHarta >= $nisab) {
        return round($totalHarta * KADAR_ZAKAT, 2);
    }
    return 0;
}

function rupiah($angka) {
    return 'Rp ' . number_format((float) $angka, 2, ',', '.');
}
?>