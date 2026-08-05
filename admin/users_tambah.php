<?php
$judul = 'Tambah Admin';
$halaman = 'users';
require_once __DIR__ . '/../includes/header_admin.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($nama === '' || $username === '' || $password === '') {
        $error = 'Semua kolom wajib diisi!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } elseif ($password !== $password2) {
        $error = 'Konfirmasi password tidak sama.';
    } else {
        $cek = mysqli_query($conn, "SELECT id FROM users WHERE username = '" . mysqli_real_escape_string($conn, $username) . "'");
        if (mysqli_num_rows($cek) > 0) {
            $error = 'Username sudah digunakan.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "INSERT INTO users (nama, username, password) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'sss', $nama, $username, $hash);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['pesan'] = 'Akun admin berhasil ditambahkan.';
                header("Location: users.php");
                exit;
            }
            $error = 'Gagal menyimpan: ' . mysqli_error($conn);
        }
    }
}
?>
<div class="container">
    <h1 class="halaman">Tambah Admin</h1>

    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="card" style="max-width:600px">
        <form method="POST" action="users_tambah.php">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required minlength="6">
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password2" required minlength="6">
            </div>
            <a class="btn btn-kuning" href="users.php">Kembali</a>
            <button type="submit" class="btn">Simpan</button>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>