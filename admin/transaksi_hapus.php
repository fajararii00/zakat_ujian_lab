<?php
$judul = 'Hapus Transaksi';
$halaman = 'transaksi';
require_once __DIR__ . '/../includes/header_admin.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = mysqli_prepare($conn, "DELETE FROM transaksi_zakat WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
if (mysqli_stmt_execute($stmt)) {
    $_SESSION['pesan'] = 'Transaksi berhasil dihapus.';
} else {
    $_SESSION['error'] = 'Gagal menghapus transaksi.';
}
header("Location: transaksi.php");
exit;
?>