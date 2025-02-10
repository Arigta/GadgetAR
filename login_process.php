<?php
require 'koneksi.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Check if the user exists
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Store user data in session
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama'] = $user['nama']; 
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header('Location: admin/adminDashboard.php');
            exit;
        } elseif ($user['role'] === 'user') {
            header('Location: user/dashboard.php');
            exit;
        }
    } else {
        echo "<script>alert('Username atau password salah!'); window.location.href = 'login.php';</script>";
    }
} else {
    echo "<script>alert('Metode tidak valid.'); window.location.href = 'login.php';</script>";
}
?>
