<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
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

// Cek apakah user pernah membeli produk ini (status ordered)
$stmt = $conn->prepare("SELECT COUNT(*) FROM orderan WHERE id_user = ? AND id_barang = ? AND status = 'ordered'");
$stmt->execute([$id_user, $id_barang]);
$pernah_beli = $stmt->fetchColumn() > 0;

// Cek apakah user sudah mengulas produk ini
$stmt = $conn->prepare("SELECT * FROM ulasan WHERE id_user = ? AND id_barang = ?");
$stmt->execute([$id_user, $id_barang]);
$ulasan_saya = $stmt->fetch(PDO::FETCH_ASSOC);

// Ambil semua ulasan produk
$stmt = $conn->prepare("SELECT u.nama, ul.rate, ul.komentar, ul.tgl_ulasan FROM ulasan ul 
                        JOIN user u ON ul.id_user = u.id_user 
                        WHERE ul.id_barang = ? ORDER BY ul.tgl_ulasan DESC");
$stmt->execute([$id_barang]);
$ulasan = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Hitung jumlah pesanan yang memiliki status "ordered"
$stmt = $conn->prepare("SELECT COUNT(*) FROM orderan WHERE id_barang = ? AND status = 'ordered'");
$stmt->execute([$id_barang]);
$total_terjual = $stmt->fetchColumn() ?? 0;

// Hitung rata-rata rating dari tabel ulasan
$stmt = $conn->prepare("SELECT AVG(rate) as rata_rating FROM ulasan WHERE id_barang = ?");
$stmt->execute([$id_barang]);
$rata_rating = $stmt->fetchColumn();
$rata_rating = $rata_rating !== null ? round($rata_rating, 1) : 0.0;

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
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

        .btn-order {
            background: linear-gradient(45deg, #ff0000, #ff7300);
            border: none;
        }

        .btn-order:hover {
            background: linear-gradient(45deg, #ff7300, #ff0000);
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
            <a class="navbar-brand" href="dashboard.php">GadgetAR</a>
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

                    <!-- Form Order -->
                    <form id="orderForm" action="metode_pembayaran.php" method="POST" onsubmit="return cekStok()">
                        <input type="hidden" name="id_barang" value="<?= $id_barang ?>">
                        <button type="submit" id="orderBtn" class="btn btn-order w-100">Order</button>
                    </form>


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

        <!-- Tambah/Edit Ulasan -->
        <?php if ($pernah_beli) : ?>
            <div class="mt-4">
                <h4><?= $ulasan_saya ? 'Edit Ulasan Anda' : 'Tambah Ulasan' ?></h4>
                <form action="simpan_ulasan.php" method="POST">
                    <input type="hidden" name="id_barang" value="<?= $id_barang ?>">
                    <input type="hidden" name="edit" value="<?= $ulasan_saya ? '1' : '0' ?>">

                    <label>Rating:</label>
                    <select name="rate" class="form-select" required>
                        <option value="5" <?= ($ulasan_saya['rate'] ?? '') == 5 ? 'selected' : '' ?>>⭐⭐⭐⭐⭐ - Sangat Baik</option>
                        <option value="4" <?= ($ulasan_saya['rate'] ?? '') == 4 ? 'selected' : '' ?>>⭐⭐⭐⭐ - Baik</option>
                        <option value="3" <?= ($ulasan_saya['rate'] ?? '') == 3 ? 'selected' : '' ?>>⭐⭐⭐ - Cukup</option>
                        <option value="2" <?= ($ulasan_saya['rate'] ?? '') == 2 ? 'selected' : '' ?>>⭐⭐ - Kurang</option>
                        <option value="1" <?= ($ulasan_saya['rate'] ?? '') == 1 ? 'selected' : '' ?>>⭐ - Buruk</option>
                    </select>

                    <label>Komentar:</label>
                    <textarea name="komentar" class="form-control" rows="3" required><?= htmlspecialchars($ulasan_saya['komentar'] ?? '') ?></textarea>

                    <button type="submit" class="btn btn-primary mt-2"><?= $ulasan_saya ? 'Perbarui Ulasan' : 'Kirim Ulasan' ?></button>
                </form>
            </div>
        <?php else : ?>
            <p class="mt-4 text-white">Anda harus membeli produk ini sebelum memberikan ulasan.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function cekStok() {
        var stok = <?= (int) $barang['stok'] ?>;
        if (stok <= 0) {
            alert("Stok habis! Tidak bisa melakukan pemesanan.");
            return false; // Mencegah form dikirim
        }
        return true; // Lanjutkan submit form jika stok masih ada
    }
</script>
</body>

</html>