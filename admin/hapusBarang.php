<?php
session_start();
include '../koneksi.php';

// Periksa apakah user sudah login dan role-nya admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Periksa apakah ID barang telah diberikan
if (isset($_GET['id'])) {
    $id_barang = $_GET['id'];

    // Ambil data gambar barang sebelum menghapus
    $stmt = $conn->prepare("SELECT gambar_barang FROM barang WHERE id_barang = :id_barang");
    $stmt->execute([':id_barang' => $id_barang]);
    $barang = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($barang) {
        // Hapus file gambar dari folder
        $gambar_barang = $barang['gambar_barang'];
        $file_path = "../asset/img/" . $gambar_barang;

        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Hapus data barang dari database
        $stmt = $conn->prepare("DELETE FROM barang WHERE id_barang = :id_barang");
        $stmt->execute([':id_barang' => $id_barang]);

        // Redirect kembali ke halaman manageBarang.php
        header("Location: manageBarang.php?message=Barang berhasil dihapus");
        exit();
    } else {
        header("Location: manageBarang.php?error=Barang tidak ditemukan");
        exit();
    }
} else {
    header("Location: manageBarang.php?error=ID barang tidak valid");
    exit();
}
?>
