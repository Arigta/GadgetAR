<?php
session_start();
include '../koneksi.php';

// Periksa apakah user sudah login dan role-nya admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Ambil ID user yang akan dihapus
$id_user = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_user > 0) {
    // Hapus data user dari database
    $stmt = $conn->prepare("DELETE FROM user WHERE id_user = :id_user");
    $stmt->execute([':id_user' => $id_user]);

    // Beri pesan sukses
    $_SESSION['success'] = "User dengan ID $id_user berhasil dihapus.";
} else {
    // Beri pesan error
    $_SESSION['error'] = "Gagal menghapus user. ID tidak valid.";
}

// Redirect kembali ke halaman anggota
header("Location: anggota.php");
exit();
