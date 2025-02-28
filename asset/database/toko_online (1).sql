-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 28 Feb 2025 pada 20.36
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
  `deskripsi` text NOT NULL,
  `harga_barang` decimal(10,0) NOT NULL,
  `stok` int NOT NULL,
  `gambar_barang` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `deskripsi`, `harga_barang`, `stok`, `gambar_barang`) VALUES
(1, 'Asus Legion i9', 'ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ', 80000000, 47, 'uq5hlswfqcqg1t1ljymv820qfszl7y169149.jpg'),
(2, 'Lenovo LOQ 16IRH8', 'ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ak jg mw ', 23000000, 61, 'izeyebel86t3indfczvo76fruum7hk728628.jpg'),
(4, 'Headphone Gaming Beexcellent.', 'Headphone bagus unutk keperluan gaming dan sehari hari', 235000, 54, 'd2e25b6e-ad71-4ec2-8b98-9611d12231c3.jpg'),
(5, 'Mouse Gamen GM310', 'Kondisi: Baru\r\nMin. Pemesanan: 1 Buah\r\nEtalase: Mouse and Keyboard\r\nSpecification :\r\n- Model : GM310\r\n- DPI : 800 - 1200 (Default) - 1600 - 2400 - 4800 - 7200\r\n- Buttons Lifespan : 5 million clicks\r\n- Size : 131 x 76 x 40mm\r\n- Weight : 103g ± 5g\r\n- Working Current : <100mA\r\n\r\nButton Function :\r\n- Light On/ Off : Long Press DPI for 3 Seconds\r\n- Light Switch : Forward/ Back + DPI\r\n\r\nFeatures :\r\n- Adjustable DPI level 800/1200/1600/2400/4800/7200\r\n- Comfortable Grip, Reduce Long-Term Use Fatigue\r\n- Professional Gaming Sensor, Give Better Experience\r\n- Multi Modes RGB Light', 124000, 63, 'Gamen-GM310-.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orderan`
--

CREATE TABLE `orderan` (
  `id_order` int NOT NULL,
  `id_user` int NOT NULL,
  `id_barang` int NOT NULL,
  `qty` int NOT NULL,
  `sub_total` decimal(10,0) NOT NULL,
  `ongkos_kirim` decimal(10,0) NOT NULL,
  `total_harga` decimal(10,0) NOT NULL,
  `metode_bayar` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `alamat` text NOT NULL,
  `status` enum('PENDING','ORDERED','REJECTED') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tanggal` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `orderan`
--

INSERT INTO `orderan` (`id_order`, `id_user`, `id_barang`, `qty`, `sub_total`, `ongkos_kirim`, `total_harga`, `metode_bayar`, `alamat`, `status`, `tanggal`) VALUES
(8, 2, 4, 1, 0, 0, 235000, '', '', 'ORDERED', '2025-02-10 11:39:23'),
(9, 2, 2, 1, 0, 0, 23000000, '', '', 'REJECTED', '2025-02-10 11:39:23'),
(12, 2, 1, 2, 0, 0, 160000000, '', '', 'REJECTED', '2025-02-10 11:39:23'),
(14, 2, 2, 5, 0, 0, 115000000, '', '', 'ORDERED', '2025-02-10 11:39:23'),
(15, 2, 4, 3, 0, 0, 705000, '', '', 'ORDERED', '2025-02-10 11:39:23'),
(16, 2, 1, 1, 0, 0, 80000000, '', '', 'REJECTED', '2025-02-10 11:39:23'),
(17, 4, 1, 4, 0, 0, 320000000, '', '', 'ORDERED', '2025-02-10 13:55:05'),
(21, 2, 4, 3, 705000, 16450, 721450, 'Gopay', 'alamat', 'PENDING', '2025-02-28 19:48:50'),
(22, 2, 2, 3, 69000000, 69000, 69069000, 'COD', 'rumah saya', 'PENDING', '2025-02-28 19:50:31'),
(23, 2, 4, 1, 235000, 16450, 251450, 'COD', 'Jl. Komp. Bumi Asri, Cinta Damai, Kec. Medan Helvetia, Kota Medan, Sumatera Utara 20123', 'PENDING', '2025-02-28 20:14:38'),
(24, 2, 4, 1, 235000, 16450, 251450, 'COD', 'Jl. Komp. Bumi Asri, Cinta Damai, Kec. Medan Helvetia, Kota Medan, Sumatera Utara 20123', 'PENDING', '2025-02-28 20:16:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ulasan`
--

CREATE TABLE `ulasan` (
  `id_ulasan` int NOT NULL,
  `id_user` int NOT NULL,
  `id_barang` int NOT NULL,
  `rate` int NOT NULL,
  `komentar` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tgl_ulasan` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `ulasan`
--

INSERT INTO `ulasan` (`id_ulasan`, `id_user`, `id_barang`, `rate`, `komentar`, `tgl_ulasan`) VALUES
(3, 4, 4, 3, 'lumayan', '2025-02-28 22:59:57'),
(9, 2, 4, 1, 'good👍🤪', '2025-03-01 01:28:36'),
(10, 2, 2, 5, 'aku nak 100', '2025-03-01 01:33:27');

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
(2, 'Akbar2', 'user', '$2y$10$iJtWZEXWpOi50WWmmFv5L.pAjJZAnwbjQj04r.X5lf7YnAXclHM/6', 'user'),
(4, 'ARigata', 'arigata', '$2y$10$N1vle9fDZzh4NUjH6SxHveiAs1vI70X7jZERPSS3jdKWRre01XFN.', 'admin');

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
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indeks untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`id_ulasan`),
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
  MODIFY `id_order` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `id_ulasan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  ADD CONSTRAINT `ulasan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `ulasan_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
