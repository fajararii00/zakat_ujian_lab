<?php
$judul = 'Edit Admin';
$halaman = 'users';
require_once __DIR__ . '/../includes/header_admin.php';

$id = (int) ($_GET['id'] ?? 0);
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $id"));
if (!$data) {
    header("Location: users.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($nama === '' || $username === '') {
        $error = 'Nama dan username wajib diisi!';
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE users SET nama=?, username=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'ssi', $nama, $username, $id);
        $ok = mysqli_stmt_execute($stmt);

        if ($password !== '') {
            if (strlen($password) < 6) {
                $error = 'Password baru minimal 6 karakter.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt2 = mysqli_prepare($conn, "UPDATE users SET password=? WHERE id=?");
                mysqli_stmt_bind_param($stmt2, 'si', $hash, $id);
                $ok = mysqli_stmt_execute($stmt2);
            }
        }

        if ($ok) {
            $_SESSION['pesan'] = 'Akun berhasil diperbarui.';
            header("Location: users.php");
            exit;
        }
        if ($error === '') {
            $error = 'Gagal memperbarui: ' . mysqli_error($conn);
        }
    }
}
?>
<div class="container">
    <h1 class="halaman">Edit Admin</h1>

    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="card" style="max-width:600px">
        <form method="POST" action="users_edit.php?id=<?= $id ?>">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($data['username']) ?>" required>
            </div>
            <div class="form-group">
                <label>Password Baru <span style="color:var(--abu)">(kosongkan jika tidak diganti)</span></label>
                <input type="password" name="password" minlength="6">
            </div>
            <a class="btn btn-kuning" href="users.php">Kembali</a>
            <button type="submit" class="btn">Simpan Perubahan</button>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>