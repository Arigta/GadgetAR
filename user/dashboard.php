<?php
session_start();
include '../koneksi.php';

// Periksa apakah user sudah login dan role-nya user
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

// Proses pencarian barang
$search = $_GET['search'] ?? '';
$query = "SELECT * FROM barang";
if ($search) {
    $query .= " WHERE nama_barang LIKE :search";
}
$stmt = $conn->prepare($query);
if ($search) {
    $stmt->bindValue(':search', "%$search%");
}
$stmt->execute();
$barang = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?= htmlspecialchars($_SESSION['nama']); ?> Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

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
            background-color: #1f1f1f;
            border: none;
            color: #fff;
            padding: 20px
        }

        .card img {
            max-height: 250px;
            /* Membatasi tinggi gambar */
            object-fit: cover;
            /* Memastikan gambar tetap proporsional */
            border-radius: 5px;
            /* Opsional: sudut gambar melengkung */
            width: 100%;
            /* Memastikan gambar tetap lebar penuh sesuai container */
        }

        .btn-order {
            background-color: #ff4757;
            border: none;
        }

        .btn-order:hover {
            background-color: #ff6b81;
        }

        .jumbotron {
            background: linear-gradient(45deg, #0000ff, #ff0000, #800080);
            color: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
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
            <a class="navbar-brand" href="dashboard.php">GadgetAR</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orderan.php">Orderan Saya</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="jumbotron text-center">
            <h1 class="animate__animated animate__fadeInDown">Selamat Datang, <?= htmlspecialchars($_SESSION['nama']); ?> di GadgetAR!</h1>
            <p class="animate__animated animate__fadeInDown">Temukan berbagai perangkat canggih dengan harga terbaik. Belanja mudah, cepat, dan aman hanya di sini!</p>
        </div>
        <div class="mt-4">
            <form action="dashboard.php" method="GET" class="d-flex mb-3">
                <input type="text" name="search" class="form-control" placeholder="Cari Barang..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary ms-2">Cari</button>
            </form>
        </div>
        <hr>
        <h2 class="text-center mb-4">Daftar Produk</h2>

        <div class="row">
            <?php foreach ($barang as $b) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card animate__animated animate__zoomIn">
                        <img src="../asset/img/<?= $b['gambar_barang']; ?>" class="card-img-top" alt="<?= $b['nama_barang']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $b['nama_barang']; ?></h5>
                            <p class="card-text">Harga: Rp <?= number_format($b['harga_barang'], 0, ',', '.'); ?></p>
                            <p class="card-text">Stok: <?= $b['stok']; ?></p>
                            <button class="btn btn-order w-100" data-id="<?= $b['id_barang']; ?>" data-nama="<?= $b['nama_barang']; ?>">Order</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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
    <script>
        document.querySelectorAll('.btn-order').forEach(button => {
            button.addEventListener('click', function() {
                const idBarang = this.getAttribute('data-id');
                const namaBarang = this.getAttribute('data-nama');
                const qty = prompt(`Masukkan jumlah untuk memesan ${namaBarang}:`, 1);

                if (qty && !isNaN(qty) && qty > 0) {
                    fetch('order_process.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                id_barang: idBarang,
                                qty: qty
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Pesanan dalam status "Pending" berhasil dibuat!');
                                window.location.href = 'orderan.php';
                            } else {
                                alert(data.message || 'Terjadi kesalahan!');
                            }
                        })
                        .catch(() => {
                            alert('Terjadi kesalahan saat memproses pesanan.');
                        });
                }
            });
        });
    </script>
</body>

</html>