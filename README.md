Toko GadgetAR Online Shop

Proyek Toko GadgetAR Online Shop adalah aplikasi web berbasis PHP yang memungkinkan pengguna untuk membeli gadget secara online. Proyek ini mencakup fitur untuk mengelola barang, pengguna, dan pesanan, serta fitur admin untuk pengelolaan data toko.

📋 Fitur
1. Fitur User (Pelanggan):
Registrasi & Login: Pelanggan dapat membuat akun dan masuk ke sistem.
Daftar Barang: Menampilkan daftar gadget yang tersedia untuk dibeli.
Pesan Barang: Pelanggan dapat memesan barang yang diinginkan.
Cek Status Pesanan: Melihat status pesanan (ORDERED, REJECTED, COMPLETED).

2. Fitur Admin:
Kelola Barang: Tambah, edit, hapus barang di katalog.
Kelola User: Tambah, edit, hapus pengguna.
Kelola Pesanan: Perbarui status pesanan (ORDERED, REJECTED, COMPLETED).
Pengurangan Stok Otomatis: Stok barang akan otomatis berkurang ketika pesanan berhasil dibuat.

🛠️ Teknologi yang Digunakan
Backend: PHP 8.x (Native, tanpa framework)
Database: MySQL
Frontend: HTML, CSS, Bootstrap 5
Server: Laragon (localhost)

⚙️ Cara Menjalankan Proyek
1. Persyaratan
PHP >= 7.4
MySQL >= 5.7
Web server (seperti Laragon, XAMPP, atau WAMP)
2. Langkah-langkah
Clone Repository


git clone https://github.com/Arigta/Toko_GadgetAR.git
Import Database

Masuk ke phpMyAdmin atau gunakan terminal MySQL.
Buat database baru bernama toko_online.
Import file toko_online.sql yang ada di folder database.
Konfigurasi Koneksi Database

Edit file koneksi.php di root proyek.
Sesuaikan konfigurasi berikut:

$host = 'localhost';
$dbname = 'toko_online';
$username = 'root';
$password = '';
Jalankan Server

Jika menggunakan Laragon, letakkan proyek di folder www.
Akses aplikasi di browser: http://localhost/Toko_GadgetAR.
Login Sebagai Admin

Gunakan akun berikut untuk login sebagai admin:

Username: bikur

Password: 123


👤 Kontributor
Nama: Sulaiman AR
GitHub: https://github.com/Arigta
Lokasi: Medan, Indonesia
