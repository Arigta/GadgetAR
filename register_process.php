<?php
// Aktifkan debugging untuk menemukan kesalahan
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = htmlspecialchars(trim($_POST['nama']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $role = 'user'; // Role default sebagai user

    // Periksa apakah username sudah ada di database
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "<script>alert('Username sudah digunakan. Silakan gunakan username lain.'); window.location.href = 'register.php';</script>";
        exit;
    }

    // Insert data user baru ke database
    $stmt = $conn->prepare("INSERT INTO user (nama, username, password, role) VALUES (:nama, :username, :password, :role)");
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);

    try {
        $stmt->execute();
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href = 'login.php';</script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Terjadi kesalahan saat registrasi: " . $e->getMessage() . "'); window.location.href = 'register.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Metode tidak valid.'); window.location.href = 'register.php';</script>";
    exit;
}
?>
