<?php
session_start();
include '../koneksi.php';

// Periksa apakah user sudah login
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'user') {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak!']);
    exit();
}

// Ambil data dari request
$data = json_decode(file_get_contents('php://input'), true);

$id_user = $_SESSION['id_user'];
$id_barang = $data['id_barang'];
$qty = (int)$data['qty'];

// Ambil detail barang
$stmt = $conn->prepare("SELECT * FROM barang WHERE id_barang = :id_barang");
$stmt->execute([':id_barang' => $id_barang]);
$barang = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$barang) {
    echo json_encode(['success' => false, 'message' => 'Barang tidak ditemukan!']);
    exit();
}

if ($barang['stok'] < $qty) {
    echo json_encode(['success' => false, 'message' => 'Stok tidak mencukupi!']);
    exit();
}

$total_harga = $barang['harga_barang'] * $qty;

// Tambahkan ke tabel orderan
$stmt = $conn->prepare("INSERT INTO orderan (id_user, id_barang, qty, total_harga, status) VALUES (:id_user, :id_barang, :qty, :total_harga, 'PENDING')");
$stmt->execute([
    ':id_user' => $id_user,
    ':id_barang' => $id_barang,
    ':qty' => $qty,
    ':total_harga' => $total_harga
]);



echo json_encode(['success' => true]);
exit();
?>
