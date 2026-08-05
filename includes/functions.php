<?php
// fungsi & konstanta umum (dipakai publik dan admin)
define('NISAB_GRAM', 85);
define('KADAR_ZAKAT', 0.025);

function hargaEmasPerGram() {
    if (isset($_SESSION['harga_emas']) && is_numeric($_SESSION['harga_emas'])) {
        return (float) $_SESSION['harga_emas'];
    }
    return 1300000;
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