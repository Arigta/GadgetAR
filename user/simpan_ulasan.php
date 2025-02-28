<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$id_barang = $_POST['id_barang'] ?? null;
$rate = $_POST['rate'] ?? null;
$komentar = trim($_POST['komentar'] ?? '');
$edit = $_POST['edit'] ?? '0';

if (!$id_barang || !$rate || empty($komentar)) {
    $_SESSION['error'] = "Semua field harus diisi!";
    header("Location: detail_barang.php?id=$id_barang");
    exit();
}

// Cek apakah user pernah membeli produk ini
$stmt = $conn->prepare("SELECT COUNT(*) FROM orderan WHERE id_user = ? AND id_barang = ? AND status = 'ordered'");
$stmt->execute([$id_user, $id_barang]);
$pernah_beli = $stmt->fetchColumn() > 0;

if (!$pernah_beli) {
    $_SESSION['error'] = "Anda belum membeli produk ini!";
    header("Location: detail_barang.php?id=$id_barang");
    exit();
}

if ($edit == '1') {
    // Update ulasan yang sudah ada
    $stmt = $conn->prepare("UPDATE ulasan SET rate = ?, komentar = ?, tgl_ulasan = NOW() WHERE id_user = ? AND id_barang = ?");
    $stmt->execute([$rate, $komentar, $id_user, $id_barang]);

    $_SESSION['success'] = "Ulasan berhasil diperbarui!";
} else {
    // Tambah ulasan baru jika belum ada
    $stmt = $conn->prepare("INSERT INTO ulasan (id_user, id_barang, rate, komentar, tgl_ulasan) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$id_user, $id_barang, $rate, $komentar]);

    $_SESSION['success'] = "Ulasan berhasil ditambahkan!";
}

header("Location: detail_barang.php?id=$id_barang");
exit();
?>
