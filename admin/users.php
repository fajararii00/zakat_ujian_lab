<?php
$judul = 'Manajemen Admin';
$halaman = 'users';
require_once __DIR__ . '/../includes/header_admin.php';

$perHalaman = 10;
$halaman = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($halaman - 1) * $perHalaman;

$totalUsers = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM users"))['c'];
$totalHalaman = max(1, (int) ceil($totalUsers / $perHalaman));

$data = mysqli_query($conn, "SELECT id, nama, username, created_at FROM users ORDER BY id LIMIT $perHalaman OFFSET $offset");
?>
<div class="container">
    <h1 class="halaman">Manajemen Admin</h1>

    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['pesan']); unset($_SESSION['pesan']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="bar-aksi">
        <span style="color:var(--abu);font-size:.85rem">Kelola akun yang dapat masuk ke panel admin.</span>
        <a class="btn" href="users_tambah.php">+ Tambah Admin</a>
    </div>

    <div class="table-wrap">
        <table>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Dibuat Pada</th>
                <th>Aksi</th>
            </tr>
            <?php $i = $offset + 1; while ($r = mysqli_fetch_assoc($data)): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($r['nama']) ?></td>
                <td><?= htmlspecialchars($r['username']) ?></td>
                <td><?= htmlspecialchars($r['created_at']) ?></td>
                <td>
                    <a class="btn btn-kecil btn-biru" href="users_edit.php?id=<?= $r['id'] ?>">Edit</a>
                    <?php if ($r['id'] != $_SESSION['user_id']): ?>
                        <a class="btn btn-kecil btn-merah" href="users_hapus.php?id=<?= $r['id'] ?>" onclick="return confirm('Hapus akun ini?')">Hapus</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <?= paginasi('users.php', $_GET, $halaman, $totalHalaman) ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>