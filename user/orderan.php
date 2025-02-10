<?php
session_start();
include '../koneksi.php';

// Periksa apakah user sudah login dan role-nya user
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['id_user'];

// Query untuk mengambil data orderan berdasarkan id_user
$search = isset($_GET['search']) ? $_GET['search'] : '';
$stmt = $conn->prepare("
    SELECT 
        o.id_oder, 
        u.nama AS nama_user, 
        b.nama_barang, 
        o.qty, 
        o.total_harga, 
        o.status, 
        o.tanggal 
    FROM orderan o 
    JOIN user u ON o.id_user = u.id_user 
    JOIN barang b ON o.id_barang = b.id_barang 
    WHERE o.id_user = :id_user AND b.nama_barang LIKE :search
    ORDER BY o.tanggal DESC
");
$stmt->execute([
    ':id_user' => $user_id,
    ':search' => "%$search%"
]);
$orderan = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orderan Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../asset/style.css">
    <style>
        body {
            background-color: #121212;
            color: #fff;
        }

        .navbar {
            background: linear-gradient(45deg, #ff0000, #800080, #0000ff);
        }

        .navbar-brand {
            color: #fff;
            font-weight: bold;
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

<body class="text-white">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">GadgetAR</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="orderan.php">Orderan Saya</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <!-- Konten -->
    <div class="container mt-5">
        <div class="card bg-dark p-4 text-white">

            <h2 class="mb-4">Orderan Saya</h2>

            <!-- Form Search -->
            <form method="GET" action="orderan.php" class="mb-3">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </form>

            <!-- Tabel Orderan -->
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>ID Order</th>
                            <th>Nama Pengguna</th>
                            <th>Nama Produk</th>
                            <th>Qty</th>
                            <th>Total Harga</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($orderan) > 0): ?>
                            <?php foreach ($orderan as $row): ?>
                                <tr>
                                    <td><?= $row['id_oder'] ?></td>
                                    <td><?= $row['nama_user'] ?></td>
                                    <td><?= $row['nama_barang'] ?></td>
                                    <td><?= $row['qty'] ?></td>
                                    <td>Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                    <td><?= $row['tanggal'] ?></td>
                                    <td>
                                        <span class="badge <?= $row['status'] === 'ORDERED' ? 'bg-success' : ($row['status'] === 'REJECTED' ? 'bg-danger' : 'bg-warning') ?>">
                                            <?= htmlspecialchars($row['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($row['status'] === 'PENDING'): ?>
                                            <a href="cancel_order.php?id=<?= $row['id_oder'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">Cancel</a>
                                        <?php else: ?>
                                            <span class="text-muted">Tidak ada aksi</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada orderan ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>