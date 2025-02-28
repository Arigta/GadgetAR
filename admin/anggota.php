<?php
session_start();
include '../koneksi.php';

// Periksa apakah user sudah login dan role-nya admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Logika pencarian anggota
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT u.id_user, u.nama, u.username, u.role, 
                            (SELECT COUNT(o.id_order) FROM orderan o WHERE o.id_user = u.id_user) AS jumlah_produk 
                            FROM user u 
                            WHERE u.nama LIKE :search OR u.username LIKE :search 
                            ORDER BY u.id_user ASC");
    $stmt->execute([':search' => '%' . $search . '%']);
} else {
    $stmt = $conn->prepare("SELECT u.id_user, u.nama, u.username, u.role, 
                            (SELECT COUNT(o.id_order) FROM orderan o WHERE o.id_user = u.id_user) AS jumlah_produk 
                            FROM user u 
                            ORDER BY u.id_user ASC");
    $stmt->execute();
}

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</head>
<style>
    body {
        background: #121212;
        color: #ffffff;
    }

    .navbar {
        background: linear-gradient(45deg, #ff0000, #800080, #0000ff);
    }

    .navbar-brand,
    .nav-link {
        color: #fff !important;
    }

    .active {
        font-weight: bold;
        color: #fff !important;
    }

    .form-control,
    .btn {
        border-radius: 10px;
    }

    .table {
        color: #fff;
    }

    footer {
        position: relative;
        bottom: 0;
        width: 100%;
        background: #000000;
    }

    footer p {
        margin: 0;
        font-size: 14px;
    }

    footer a {
        text-decoration: none;
        margin-top: 5px;
    }
</style>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="adminDashboard.php">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="manageBarang.php">Manage Barang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="anggota.php">Anggota</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manageOrders.php">Manage Orders</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white" href="../logout.php" onclick="return confirm('Apakah Anda yakin ingin logout?')">Logout</a>
                    </li>
                </ul>
                </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->

    <div class="container mt-5">
        <div class="card bg-dark p-4">
            <h2 class="mb-4" style="color:white">Daftar Anggota</h2>

            <!-- Form Pencarian -->
            <form method="GET" action="anggota.php" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari anggota berdasarkan nama atau username..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </form>

            <!-- Tabel Anggota -->
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Jumlah Produk Dibeli</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr">
                                    <td><?= htmlspecialchars($user['id_user']) ?></td>
                                    <td><?= htmlspecialchars($user['nama']) ?></td>
                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                    <td><?= htmlspecialchars($user['jumlah_produk']) ?></td>
                                    < <td>
                                        <!-- Tambahkan gaya untuk teks Role -->
                                        <span class="badge <?= $user['role'] === 'admin' ? 'bg-danger' : 'bg-primary' ?>">
                                            <?= htmlspecialchars($user['role']) ?>
                                        </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm editBtn" data-bs-toggle="modal" data-bs-target="#editModal<?= $user['id_user'] ?>">Edit</button>

                                            <a href="hapusAnggota.php?id=<?= $user['id_user'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus anggota ini?');">Hapus</a>
                                        </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-warning">Anggota tidak ditemukan.</td>
                                    </tr>
                                <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Modal Edit -->
    <?php foreach ($users as $user): ?>
        <!-- Modal Edit -->
        <div class="modal fade" id="editModal<?= $user['id_user'] ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-white">
                    <form action="editAnggota.php" method="POST">
                        <div class="modal-header border-secondary">
                            <h5 class="modal-title">Edit Anggota</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id_user" value="<?= $user['id_user'] ?>">

                            <div class="mb-3">
                                <label for="nama<?= $user['id_user'] ?>" class="form-label">Nama</label>
                                <input type="text" class="form-control bg-dark text-white" id="nama<?= $user['id_user'] ?>" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="username<?= $user['id_user'] ?>" class="form-label">Username</label>
                                <input type="text" class="form-control bg-dark text-white" id="username<?= $user['id_user'] ?>" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="role<?= $user['id_user'] ?>" class="form-label">Role</label>
                                <select class="form-select bg-dark text-white" id="role<?= $user['id_user'] ?>" name="role" required>
                                    <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer border-secondary">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <footer class="bg-dark text-center text-white py-3 mt-5">
        <div class="container">
            <p>Copyright © GadgetAR Online Shop</p>
            <a href="https://github.com/Arigta?tab=repositories" target="_blank">
                <img src="https://cdn-icons-png.flaticon.com/512/25/25231.png" alt="GitHub Logo" width="30" height="30" style="filter: invert(1);">
            </a>
        </div>
    </footer>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>