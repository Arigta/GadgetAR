<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$id_barang = $_POST['id_barang'] ?? null;

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
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metode Pembayaran</title>
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

        .hidden {
            display: none;
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

    <!-- Metode Pembayaran -->
    <div class="container mt-5">
        <div class="card p-4">
            <h3>Checkout Barang</h3>
            <hr>

            <!-- Informasi Barang -->
            <div class="row">
                <div class="col-md-4">
                    <img src="../asset/img/<?= htmlspecialchars($barang['gambar_barang']) ?>" class="img-fluid rounded">
                </div>
                <div class="col-md-8">
                    <h4><?= htmlspecialchars($barang['nama_barang']) ?></h4>
                    <h5>Harga: Rp <?= number_format($barang['harga_barang'], 0, ',', '.') ?></h5>
                    <p><?= nl2br(htmlspecialchars($barang['deskripsi'])) ?></p>
                    <p><strong>Stok:</strong> <?= htmlspecialchars($barang['stok']) ?></p>
                </div>
            </div>

            <hr>

            <!-- Form Checkout -->
            <form action="proses_checkout.php" method="POST">
                <input type="hidden" name="id_barang" value="<?= $id_barang ?>">
                <input type="hidden" id="harga_barang" value="<?= $barang['harga_barang'] ?>">

                <!-- Input Jumlah Beli -->
                <div class="mb-3">
                    <label for="qty" class="form-label">Jumlah Beli</label>
                    <input type="number" id="qty" name="qty" class="form-control" min="1" max="<?= $barang['stok'] ?>" required oninput="hitungTotal()">
                </div>

                <!-- Input Alamat -->
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat Pengiriman</label>
                    <textarea id="alamat" name="alamat" class="form-control" rows="3" required></textarea>
                </div>

                <!-- Pilih Metode Pembayaran -->
                <div class="mb-3">
                    <label class="form-label">Metode Pembayaran</label>
                    <select id="metode_pembayaran" name="metode_pembayaran" class="form-select" required onchange="tampilkanEwallet()">
                        <option value="COD">COD (Bayar di Tempat)</option>
                        <option value="e-wallet">E-Wallet</option>
                    </select>
                </div>

                <!-- Pilih E-Wallet -->
                <div id="pilihan_ewallet" class="mb-3 hidden">
                    <label class="form-label">Pilih E-Wallet</label>
                    <select name="e_wallet" class="form-select">
                        <option value="Gopay">Gopay</option>
                        <option value="OVO">OVO</option>
                        <option value="DANA">DANA</option>
                        <option value="ShopeePay">ShopeePay</option>
                    </select>
                </div>

                <!-- Ringkasan Transaksi -->
                <div class="card p-3">
                    <h4>Ringkasan Transaksi</h4>
                    <p>Subtotal: Rp <span id="subtotal">0</span></p>
                    <p>Ongkos Kirim: Rp <span id="ongkos_kirim">0</span></p>
                    <h5>Total Tagihan: Rp <span id="total_tagihan">0</span></h5>
                </div>

                <button type="submit" class="btn btn-order w-100 mt-3">Konfirmasi Pembayaran</button>
            </form>

        </div>
    </div>

    <script>
        function tampilkanEwallet() {
            var metode = document.getElementById("metode_pembayaran").value;
            var pilihanEwallet = document.getElementById("pilihan_ewallet");
            if (metode === "e-wallet") {
                pilihanEwallet.classList.remove("hidden");
            } else {
                pilihanEwallet.classList.add("hidden");
            }
        }

        function hitungTotal() {
            var harga = parseInt(document.getElementById("harga_barang").value);
            var qty = parseInt(document.getElementById("qty").value) || 1;
            var subtotal = harga * qty;

            // Hitung Ongkos Kirim berdasarkan harga satuan barang
            var ongkos_kirim = 0;
            if (harga < 100000) {
                ongkos_kirim = harga * 0.15;
            } else if (harga >= 100000 && harga <= 500000) {
                ongkos_kirim = harga * 0.07;
            } else if (harga > 500000 && harga <= 1000000) {
                ongkos_kirim = harga * 0.03;
            } else if (harga > 1000000 && harga <= 10000000) {
                ongkos_kirim = harga * 0.02;
            } else {
                ongkos_kirim = harga * 0.003;
            }

            var total_tagihan = subtotal + ongkos_kirim;

            document.getElementById("subtotal").innerText = subtotal.toLocaleString();
            document.getElementById("ongkos_kirim").innerText = ongkos_kirim.toLocaleString();
            document.getElementById("total_tagihan").innerText = total_tagihan.toLocaleString();
        }
    </script>
</body>

</html>