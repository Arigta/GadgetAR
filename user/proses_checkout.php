<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$id_barang = $_POST['id_barang'];
$qty = $_POST['qty'];
$alamat = trim($_POST['alamat']);
$metode_bayar = $_POST['metode_bayar'];
$e_wallet = $_POST['e_wallet'] ?? null; // Jika user memilih e-wallet
$tanggal = date("Y-m-d H:i:s");

// Ambil data barang
$stmt = $conn->prepare("SELECT harga_barang, stok FROM barang WHERE id_barang = ?");
$stmt->execute([$id_barang]);
$barang = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$barang) {
    echo "<script>alert('Barang tidak ditemukan!'); window.location.href='detail_barang.php?id=$id_barang';</script>";
    exit();
}

// Cek stok barang
if ($qty > $barang['stok']) {
    echo "<script>alert('Stok tidak mencukupi!'); window.location.href='detail_barang.php?id=$id_barang';</script>";
    exit();
}

// Hitung harga
$harga_satuan = $barang['harga_barang'];
$sub_total = $harga_satuan * $qty;

// Hitung ongkos kirim berdasarkan harga satuan barang
if ($harga_satuan < 100000) {
    $ongkos_kirim = $harga_satuan * 0.15;
} elseif ($harga_satuan >= 100000 && $harga_satuan <= 500000) {
    $ongkos_kirim = $harga_satuan * 0.07;
} elseif ($harga_satuan > 500000 && $harga_satuan <= 1000000) {
    $ongkos_kirim = $harga_satuan * 0.03;
} elseif ($harga_satuan > 1000000 && $harga_satuan <= 10000000) {
    $ongkos_kirim = $harga_satuan * 0.02;
} else {
    $ongkos_kirim = $harga_satuan * 0.003;
}

$total_harga = $sub_total + $ongkos_kirim;

$metode_bayar = $_POST['metode_pembayaran']; // Nama input harus sesuai dengan yang di form
$e_wallet = isset($_POST['e_wallet']) ? $_POST['e_wallet'] : null;

// Jika metode bayar e-wallet dan user memilih e-wallet yang valid, gunakan e-wallet
$metode_final = ($metode_bayar === 'e-wallet' && !empty($e_wallet)) ? $e_wallet : 'COD';


// Insert ke tabel orderan
$stmt = $conn->prepare("INSERT INTO orderan (id_user, id_barang, qty, sub_total, ongkos_kirim, total_harga, metode_bayar, alamat, status, tanggal) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'PENDING', ?)");
$insert = $stmt->execute([$id_user, $id_barang, $qty, $sub_total, $ongkos_kirim, $total_harga, $metode_final, $alamat, $tanggal]);

if ($insert) {
    // Kurangi stok barang
    $stmt = $conn->prepare("UPDATE barang SET stok = stok - ? WHERE id_barang = ?");
    $stmt->execute([$qty, $id_barang]);

    // Redirect ke halaman orderan.php
    header("Location: orderan.php");
    exit();
} else {
    echo "<script>alert('Gagal melakukan pemesanan!'); window.location.href='metode_pembayaran.php?id=$id_barang';</script>";
}
?>
