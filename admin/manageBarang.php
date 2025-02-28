<?php
session_start();
include '../koneksi.php';

// Periksa apakah user sudah login dan role-nya admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Proses menambahkan barang
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_barang = $_POST['nama_barang'];
    $harga_barang = $_POST['harga_barang'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];
    $gambar_barang = $_FILES['gambar_barang']['name'];
    $tmp_name = $_FILES['gambar_barang']['tmp_name'];

    // Upload file gambar ke folder img
    if (move_uploaded_file($tmp_name, "../asset/img/" . $gambar_barang)) {
        $stmt = $conn->prepare("INSERT INTO barang (nama_barang, harga_barang, stok, deskripsi, gambar_barang) VALUES (:nama_barang, :harga_barang, :stok, :deskripsi, :gambar_barang)");
        $stmt->execute([
            ':nama_barang' => $nama_barang,
            ':harga_barang' => $harga_barang,
            ':stok' => $stok,
            ':deskripsi' => $deskripsi,
            ':gambar_barang' => $gambar_barang
        ]);
        $message = "Barang berhasil ditambahkan!";
    } else {
        $message = "Gagal mengupload gambar!";
    }
}

// Tambahkan logika pencarian
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM barang WHERE nama_barang LIKE :search ORDER BY id_barang DESC");
    $stmt->execute([':search' => '%' . $search . '%']);
} else {
    $stmt = $conn->prepare("SELECT * FROM barang ORDER BY id_barang DESC");
    $stmt->execute();
}
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .table {
            color: #ffffff;
        }

        .form-control,
        .btn {
            border-radius: 10px;
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
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="adminDashboard.php">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="manageBarang.php">Manage Barang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="anggota.php">Anggota</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manageOrders.php">Manage Orders</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white" href="../logout.php" onclick="return confirm('Apakah Anda yakin ingin logout?')">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="text-center">Kelola Barang</h1>

        <br>

        <!-- Pesan sukses -->
        <?php if (!empty($message)) : ?>
            <div class="alert alert-success">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <!-- Form Tambah Barang -->
        <div class="card bg-dark p-4 mb-4">

            <h3 style="color:white">Tambah Barang</h3>
            <hr style="border: 1px solid white;">


            <form action="manageBarang.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nama_barang" class="form-label" style="color:white">Nama Barang</label>
                    <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                </div>
                <div class="mb-3">
                    <label for="harga_barang" class="form-label" style="color:white">Harga Barang</label>
                    <input type="number" class="form-control" id="harga_barang" name="harga_barang" required>
                </div>
                <div class="mb-3">
                    <label for="stok" class="form-label" style="color:white">Stok Barang</label>
                    <input type="number" class="form-control" id="stok" name="stok" required>
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label" style="color:white">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="gambar_barang" class="form-label" style="color:white">Gambar Barang</label>
                    <input type="file" class="form-control" id="gambar_barang" name="gambar_barang" required>
                </div>
                <button type="submit" class="btn btn-primary">Tambah Barang</button>
            </form>
        </div>

        <!-- Tabel Barang -->
        <div class="card bg-dark p-4">

            <h3 style="color:white">Daftar Barang</h3>

            <hr style="border: 1px solid white;">
            <form method="GET" action="manageBarang.php" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari barang berdasarkan nama..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </form>


            <table class="table table-dark table-hover mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Deskripsi</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row) : ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id_barang']) ?></td>
                            <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                            <td>Rp <?= number_format($row['harga_barang'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($row['stok']) ?></td>
                            <td><?= strlen($row['deskripsi']) > 30 ? htmlspecialchars(substr($row['deskripsi'], 0, 30)) . '...' : htmlspecialchars($row['deskripsi']) ?></td>

                            <td><img src="../asset/img/<?= htmlspecialchars($row['gambar_barang']) ?>" alt="Gambar Barang" width="50"></td>
                            <td>
                                <a href="edit_barang.php?id=<?= $row['id_barang'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="hapusBarang.php?id=<?= $row['id_barang'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
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