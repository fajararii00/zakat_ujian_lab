<?php
$judul = 'Hapus Muzakki';
$halaman = 'muzakki';
require_once __DIR__ . '/../includes/header_admin.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = mysqli_prepare($conn, "DELETE FROM muzakki WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
if (mysqli_stmt_execute($stmt)) {
    $_SESSION['pesan'] = 'Muzakki berhasil dihapus.';
} else {
    $_SESSION['error'] = 'Gagal menghapus muzakki.';
}
header("Location: muzakki.php");
exit;
?>