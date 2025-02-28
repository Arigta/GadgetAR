<?php
session_start();
include '../koneksi.php';

// Periksa apakah user sudah login dan role-nya admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Filter berdasarkan status (jika ada)
$status = isset($_GET['status']) ? $_GET['status'] : '';

// Query untuk mengambil data orderan
if (!empty($status)) {
    $stmt = $conn->prepare("
        SELECT 
            o.id_order, 
            o.id_user, 
            o.id_barang, 
            u.nama AS nama_user, 
            b.nama_barang AS nama_produk, 
            o.qty, 
             o.metode_bayar,
            o.total_harga,
            o.alamat, 
            o.status,
            o.tanggal
        FROM orderan o
        JOIN user u ON o.id_user = u.id_user
        JOIN barang b ON o.id_barang = b.id_barang
        WHERE o.status = :status
        ORDER BY o.id_order ASC
    ");
    $stmt->execute([':status' => $status]);
} else {
    $stmt = $conn->prepare("
        SELECT 
            o.id_order, 
            o.id_user, 
            o.id_barang, 
            u.nama AS nama_user, 
            b.nama_barang AS nama_produk, 
            o.qty, 
            o.metode_bayar,
            o.total_harga, 
            o.alamat,
            o.status,
            o.tanggal
        FROM orderan o
        JOIN user u ON o.id_user = u.id_user
        JOIN barang b ON o.id_barang = b.id_barang
        ORDER BY o.id_order ASC
    ");
    $stmt->execute();
}

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <nav class="navbar navbar-expand-lg navbar-dark">
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
                        <a class="nav-link" href="anggota.php">Anggota</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="manageOrders.php">Manage Orders</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white" href="../logout.php" onclick="return confirm('Apakah Anda yakin ingin logout?')">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mt-5">
        <div class="card bg-dark p-4">
            <h2 class="mb-4 text-white">Manage Orders</h2>

            <!-- Filter Status -->
            <form method="GET" action="manageOrders.php" class="mb-4">
                <div class="input-group">
                    <select name="status" class="form-select bg-dark text-white">
                        <option value="" <?= empty($status) ? 'selected' : '' ?>>Semua Status</option>
                        <option value="PENDING" <?= $status === 'PENDING' ? 'selected' : '' ?>>PENDING</option>
                        <option value="ORDERED" <?= $status === 'ORDERED' ? 'selected' : '' ?>>ORDERED</option>
                        <option value="REJECTED" <?= $status === 'REJECTED' ? 'selected' : '' ?>>REJECTED</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>

            <!-- Tabel Orders -->
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Order</th>
                            <th>ID User</th>
                            <th>Nama User</th>
                            <th>Nama Produk</th>
                            <th>Qty</th>
                            <th>Metode Bayar</th>
                            <th>Total Harga</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orders)): ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= htmlspecialchars($order['id_order']) ?></td>
                                    <td><?= htmlspecialchars($order['id_user']) ?></td>
                                    <td><?= htmlspecialchars($order['nama_user']) ?></td>
                                    <td><?= htmlspecialchars($order['nama_produk']) ?></td>
                                    <td><?= htmlspecialchars($order['qty']) ?></td>
                                    <td><?= htmlspecialchars($order['metode_bayar']) ?></td> 
                                    <td><?= htmlspecialchars($order['alamat']) ?></td>
                                    
                                    <td><?= htmlspecialchars($order['total_harga']) ?></td>
                                    <td><?= htmlspecialchars($order['tanggal']) ?></td>
                                    <td>
                                        <span class="badge <?= $order['status'] === 'ORDERED' ? 'bg-success' : ($order['status'] === 'REJECTED' ? 'bg-danger' : 'bg-warning') ?>">
                                            <?= htmlspecialchars($order['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($order['status'] === 'PENDING'): ?>
                                            <a href="updateOrder.php?id=<?= $order['id_order'] ?>&status=ORDERED" class="btn btn-success btn-sm">ORDERED</a>
                                            <a href="updateOrder.php?id=<?= $order['id_order'] ?>&status=REJECTED" class="btn btn-danger btn-sm">REJECTED</a>
                                        <?php else: ?>
                                            <span class="text-muted">No Actions</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-warning">Order tidak ditemukan.</td>
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>