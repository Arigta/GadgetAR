<?php
session_start();
include '../koneksi.php';

// Periksa apakah user sudah login dan role-nya admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Ambil ID Order dan Status
$id_order = isset($_GET['id']) ? intval($_GET['id']) : 0;
$status = isset($_GET['status']) ? $_GET['status'] : '';

if ($id_order > 0 && in_array($status, ['ORDERED', 'REJECTED'])) {
    // Mulai transaksi
    $conn->beginTransaction();

    try {
        // Ambil data pesanan untuk mendapatkan ID Barang dan Quantity
        $stmt = $conn->prepare("SELECT id_barang, qty FROM orderan WHERE id_oder = :id_order");
        $stmt->execute([':id_order' => $id_order]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            $id_barang = $order['id_barang'];
            $qty = $order['qty'];

            if ($status === 'ORDERED') {
                // Kurangi stok barang
                $stmt = $conn->prepare("UPDATE barang SET stok = stok - :qty WHERE id_barang = :id_barang AND stok >= :qty");
                $stmt->execute([':qty' => $qty, ':id_barang' => $id_barang]);

                // Pastikan stok cukup
                if ($stmt->rowCount() === 0) {
                    throw new Exception("Stok barang tidak mencukupi.");
                }
            }

            // Update status pesanan
            $stmt = $conn->prepare("UPDATE orderan SET status = :status WHERE id_oder = :id_order");
            $stmt->execute([':status' => $status, ':id_order' => $id_order]);

            // Commit transaksi
            $conn->commit();

            $_SESSION['success'] = "Status order berhasil diperbarui menjadi $status.";
        } else {
            throw new Exception("Order tidak ditemukan.");
        }
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        $conn->rollBack();
        $_SESSION['error'] = "Gagal memperbarui status order: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "Gagal memperbarui status order.";
}

header("Location: manageOrders.php");
exit();
