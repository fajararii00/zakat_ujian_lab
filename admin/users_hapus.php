<?php
$judul = 'Hapus Admin';
$halaman = 'users';
require_once __DIR__ . '/../includes/header_admin.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id == $_SESSION['user_id']) {
    $_SESSION['error'] = 'Tidak dapat menghapus akun yang sedang digunakan.';
} else {
    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['pesan'] = 'Akun admin berhasil dihapus.';
    } else {
        $_SESSION['error'] = 'Gagal menghapus akun.';
    }
}
header("Location: users.php");
exit;
?>