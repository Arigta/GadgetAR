<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id_barang = $_GET['id'] ?? null;

if (!$id_barang) {
    header("Location: dashboard.php");
    exit();
}

// Ambil data barang
$stmt = $conn->prepare("SELECT * FROM barang WHERE id_barang = ?");
$stmt->execute([$id_barang]);
$barang = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$barang) {
    echo "<p>Barang tidak ditemukan.</p>";
    exit();
}

// Hitung jumlah pesanan yang memiliki status "ordered"
$stmt = $conn->prepare("SELECT COUNT(*) FROM orderan WHERE id_barang = ? AND status = 'ordered'");
$stmt->execute([$id_barang]);
$total_terjual = $stmt->fetchColumn() ?? 0;

// Hitung rata-rata rating dari tabel ulasan
$stmt = $conn->prepare("SELECT AVG(rate) as rata_rating FROM ulasan WHERE id_barang = ?");
$stmt->execute([$id_barang]);
$rata_rating = $stmt->fetchColumn();
$rata_rating = $rata_rating !== null ? round($rata_rating, 1) : 0.0;

// Ambil semua ulasan produk
$stmt = $conn->prepare("SELECT u.nama, ul.rate, ul.komentar, ul.tgl_ulasan FROM ulasan ul 
                        JOIN user u ON ul.id_user = u.id_user 
                        WHERE ul.id_barang = ? ORDER BY ul.tgl_ulasan DESC");
$stmt->execute([$id_barang]);
$ulasan = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk (Admin)</title>
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

        .card {
            background-color: #1e1e1e;
            border: none;
            color: #fff;
        }

        .btn-kelola {
            background: linear-gradient(45deg, #007bff, #00c3ff);
            border: none;
        }

        .btn-kelola:hover {
            background: linear-gradient(45deg, #00c3ff, #007bff);
        }

        .rating {
            color: gold;
        }

        .review-box {
            background: #222;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">GadgetAR (Admin)</a>
        </div>
    </nav>

    <!-- Detail Produk -->
    <div class="container mt-5">

        <div class="card p-4">
            <div class="row">
                <div class="col-md-5">
                    <img src="../asset/img/<?= htmlspecialchars($barang['gambar_barang']) ?>" class="img-fluid rounded">
                </div>
                <div class="col-md-7">
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <h2><?= htmlspecialchars($barang['nama_barang']) ?></h2>
                        <h4 class="text-end">Rp <?= number_format($barang['harga_barang'], 0, ',', '.') ?></h4>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="badge bg-success">Terjual: <?= $total_terjual ?></span>
                        <span class="badge bg-danger text-white">⭐ <?= number_format($rata_rating, 1) ?></span>
                    </div>
                    <br>

                    <p><?= nl2br(htmlspecialchars($barang['deskripsi'])) ?></p>
                    <p><strong>Stok:</strong> <?= htmlspecialchars($barang['stok']) ?></p>

                    <!-- Tombol Kelola -->
                    <a href="manageBarang.php?id=<?= $id_barang ?>" class="btn btn-kelola w-100">Kelola Produk</a>
                </div>
            </div>
        </div>
        <br>

        <!-- Ulasan Produk -->
        <h3>Ulasan Produk</h3>
        <?php if (count($ulasan) > 0) : ?>
            <?php foreach ($ulasan as $ul) : ?>
                <div class="review-box p-3 mb-2">
                    <strong><?= htmlspecialchars($ul['nama']) ?></strong> - <span class="text-warning"><?= str_repeat("⭐", $ul['rate']) ?></span>

                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <p><?= htmlspecialchars($ul['komentar']) ?></p>
                        <small class="text-white text-end"><?= date("d M Y H:i", strtotime($ul['tgl_ulasan'])) ?></small>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Belum ada ulasan.</p>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
