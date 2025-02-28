<?php
session_start();
include '../koneksi.php';

// Periksa apakah user sudah login dan memiliki role 'user'
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_order = $_GET['id'];
    $user_id = $_SESSION['id_user'];

    try {
        // Cek apakah orderan milik user yang sedang login dan statusnya masih PENDING
        $stmt = $conn->prepare("
            SELECT * 
            FROM orderan 
            WHERE id_order = :id_order 
            AND id_user = :id_user 
            AND status = 'PENDING'
        ");
        $stmt->execute([
            ':id_order' => $id_order,
            ':id_user' => $user_id
        ]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            // Hapus orderan
            $stmt = $conn->prepare("DELETE FROM orderan WHERE id_oder = :id_order");
            $stmt->execute([':id_order' => $id_order]);


            // Redirect dengan pesan sukses
            header("Location: orderan.php?message=Order berhasil dibatalkan");
        } else {
            // Redirect dengan pesan error jika order tidak ditemukan atau sudah diproses
            header("Location: orderan.php?error=Order tidak ditemukan atau sudah diproses");
        }
    } catch (PDOException $e) {
        // Handle error
        header("Location: orderan.php?error=Terjadi kesalahan saat membatalkan pesanan");
    }
} else {
    // Redirect jika id order tidak ada di URL
    header("Location: orderan.php");
    exit();
}
?>
