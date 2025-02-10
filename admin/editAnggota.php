<?php
session_start();
include '../koneksi.php';

// Periksa apakah user sudah login dan role-nya admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_POST['id_user'];
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $role = $_POST['role'];

    // Validasi input
    if (empty($nama) || empty($username) || empty($role)) {
        $_SESSION['error'] = "Semua field harus diisi!";
        header("Location: anggota.php");
        exit();
    }

    try {
        // Perbarui data anggota di database
        $stmt = $conn->prepare("UPDATE user SET nama = :nama, username = :username, role = :role WHERE id_user = :id_user");
        $stmt->execute([
            ':nama' => $nama,
            ':username' => $username,
            ':role' => $role,
            ':id_user' => $id_user
        ]);

        $_SESSION['success'] = "Data anggota berhasil diperbarui.";
        header("Location: anggota.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Gagal memperbarui data anggota: " . $e->getMessage();
        header("Location: anggota.php");
        exit();
    }
} else {
    header("Location: anggota.php");
    exit();
}
?>
