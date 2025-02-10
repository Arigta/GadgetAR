-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 10 Feb 2025 pada 05.02
-- Versi server: 8.0.30
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toko_online`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id_barang` int NOT NULL,
  `nama_barang` varchar(50) NOT NULL,
  `harga_barang` double NOT NULL,
  `stok` int NOT NULL,
  `gambar_barang` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `harga_barang`, `stok`, `gambar_barang`) VALUES
(1, 'Asus Legion i9', 80000000, 65, 'uq5hlswfqcqg1t1ljymv820qfszl7y169149.jpg'),
(2, 'Lenovo LOQ 16IRH8', 23000000, 64, 'izeyebel86t3indfczvo76fruum7hk728628.jpg'),
(4, 'Headphone Gaming Beexcellent.', 235000, 60, 'd2e25b6e-ad71-4ec2-8b98-9611d12231c3.jpg'),
(5, 'Mouse Gamen GM310', 124000, 63, 'Gamen-GM310-.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orderan`
--

CREATE TABLE `orderan` (
  `id_oder` int NOT NULL,
  `id_user` int NOT NULL,
  `id_barang` int NOT NULL,
  `qty` int NOT NULL,
  `total_harga` double NOT NULL,
  `status` enum('PENDING','ORDERED','REJECTED') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tanggal` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `orderan`
--

INSERT INTO `orderan` (`id_oder`, `id_user`, `id_barang`, `qty`, `total_harga`, `status`, `tanggal`) VALUES
(8, 2, 4, 1, 235000, 'ORDERED', '2025-02-10 11:39:23'),
(9, 2, 2, 1, 23000000, 'REJECTED', '2025-02-10 11:39:23'),
(12, 2, 1, 2, 160000000, 'REJECTED', '2025-02-10 11:39:23'),
(13, 2, 5, 1, 124000, 'PENDING', '2025-02-10 11:39:23'),
(14, 2, 2, 5, 115000000, 'ORDERED', '2025-02-10 11:39:23'),
(15, 2, 4, 3, 705000, 'ORDERED', '2025-02-10 11:39:23'),
(16, 2, 1, 1, 80000000, 'PENDING', '2025-02-10 11:39:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `nama` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `nama`, `username`, `password`, `role`) VALUES
(1, 'Akbar', 'bikur', '$2y$10$hhrPexIPVfTitS5PAjMvyeCbKVIPoVDhD9qYTQaeSFOknCzHTAgmq', 'admin'),
(2, 'Akbar2', 'user', '$2y$10$iJtWZEXWpOi50WWmmFv5L.pAjJZAnwbjQj04r.X5lf7YnAXclHM/6', 'user');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indeks untuk tabel `orderan`
--
ALTER TABLE `orderan`
  ADD PRIMARY KEY (`id_oder`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `orderan`
--
ALTER TABLE `orderan`
  MODIFY `id_oder` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
