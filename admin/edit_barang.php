<?php
session_start();
include '../koneksi.php';

// Periksa apakah user sudah login dan role-nya admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Ambil data barang berdasarkan ID
if (isset($_GET['id'])) {
    $id_barang = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM barang WHERE id_barang = :id_barang");
    $stmt->execute([':id_barang' => $id_barang]);
    $barang = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$barang) {
        die("Barang tidak ditemukan.");
    }
} else {
    header("Location: manageBarang.php");
    exit();
}

// Proses update barang
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_barang = $_POST['nama_barang'];
    $harga_barang = $_POST['harga_barang'];
    $stok = $_POST['stok'];
    $gambar_barang_lama = $_POST['gambar_barang_lama'];

    // Jika ada gambar baru, upload dan ganti gambar lama
    if (!empty($_FILES['gambar_barang']['name'])) {
        $gambar_barang = $_FILES['gambar_barang']['name'];
        $tmp_name = $_FILES['gambar_barang']['tmp_name'];
        move_uploaded_file($tmp_name, "../asset/img/" . $gambar_barang);
    } else {
        $gambar_barang = $gambar_barang_lama;
    }

    // Update data barang
    $stmt = $conn->prepare("UPDATE barang SET nama_barang = :nama_barang, harga_barang = :harga_barang, stok = :stok, gambar_barang = :gambar_barang WHERE id_barang = :id_barang");
    $stmt->execute([
        ':nama_barang' => $nama_barang,
        ':harga_barang' => $harga_barang,
        ':stok' => $stok,
        ':gambar_barang' => $gambar_barang,
        ':id_barang' => $id_barang
    ]);

    header("Location: manageBarang.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>
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
        <div class="container-fluid">
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
        <h1 class="text-center">Edit Barang</h1>
        <div class="card bg-dark p-4">
            <form action="edit_barang.php?id=<?= $barang['id_barang'] ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nama_barang" class="form-label" style="color:white">Nama Barang</label>
                    <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="<?= htmlspecialchars($barang['nama_barang']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="harga_barang" class="form-label" style="color:white">Harga Barang</label>
                    <input type="number" class="form-control" id="harga_barang" name="harga_barang" value="<?= htmlspecialchars($barang['harga_barang']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="stok" class="form-label" style="color:white">Stok Barang</label>
                    <input type="number" class="form-control" id="stok" name="stok" value="<?= htmlspecialchars($barang['stok']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="gambar_barang" class="form-label" style="color:white">Gambar Barang</label>
                    <input type="file" class="form-control" id="gambar_barang" name="gambar_barang">
                    <input type="hidden" name="gambar_barang_lama" value="<?= htmlspecialchars($barang['gambar_barang']) ?>">
                    <small>Gambar saat ini: <img src="../asset/img/<?= htmlspecialchars($barang['gambar_barang']) ?>" alt="Gambar Barang" width="50"></small>
                </div>
                <button type="submit" class="btn btn-primary">Update Barang</button>
                <a href="manageBarang.php" class="btn btn-secondary">Batal</a>
            </form>
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