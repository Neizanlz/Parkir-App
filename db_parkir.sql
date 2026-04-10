-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3308
-- Generation Time: Apr 08, 2026 at 09:53 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_parkir`
--

-- --------------------------------------------------------

--
-- Table structure for table `area_parkir`
--

CREATE TABLE `area_parkir` (
  `id_area` int NOT NULL,
  `nama_area` varchar(50) NOT NULL,
  `kapasitas` int NOT NULL,
  `terisi` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `area_parkir`
--

INSERT INTO `area_parkir` (`id_area`, `nama_area`, `kapasitas`, `terisi`) VALUES
(1, 'Area Basement BTM', 50, 1),
(3, 'vvip', 10, 0),
(7, 'VVIP B', 66, 0);

-- --------------------------------------------------------

--
-- Table structure for table `kendaraan`
--

CREATE TABLE `kendaraan` (
  `id_kendaraan` int NOT NULL,
  `plat_nomor` varchar(15) NOT NULL,
  `jenis_kendaraan` varchar(20) NOT NULL,
  `warna` varchar(20) DEFAULT NULL,
  `pemilik` varchar(100) DEFAULT NULL,
  `id_user` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kendaraan`
--

INSERT INTO `kendaraan` (`id_kendaraan`, `plat_nomor`, `jenis_kendaraan`, `warna`, `pemilik`, `id_user`) VALUES
(25, 'F 5555 FA', 'motor', 'Biru', 'Nesya', 6),
(26, 'B 4567 SS', 'mobil', 'Biru', 'Nesya', 6),
(28, 'F 123 BA', 'lainnya', 'Biru', 'Nesya', 6);

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `aktivitas` varchar(100) DEFAULT NULL,
  `waktu_aktivitas` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log`, `id_user`, `aktivitas`, `waktu_aktivitas`) VALUES
(181, 6, 'Login ke sistem sebagai admin', '2026-04-08 15:41:42'),
(182, 6, 'Menambah tarif motor sebesar 2000', '2026-04-08 15:46:24'),
(183, 6, 'Menambah tarif mobil sebesar 10000', '2026-04-08 15:46:33'),
(184, 6, 'Menambah kendaraan F 5555 FA', '2026-04-08 15:47:23'),
(185, 6, 'Mengubah user owner', '2026-04-08 15:47:36'),
(186, 6, 'Mengubah user owner', '2026-04-08 15:47:43'),
(187, 6, 'Mengubah user owner', '2026-04-08 15:47:44'),
(188, 6, 'Mengubah user owner', '2026-04-08 15:47:44'),
(189, 6, 'Mengubah user owner', '2026-04-08 15:47:46'),
(190, 6, 'Mengubah user owner', '2026-04-08 15:47:47'),
(191, 6, 'Mengubah user owner', '2026-04-08 15:47:51'),
(192, 6, 'Mengubah user owner', '2026-04-08 15:47:54'),
(193, 6, 'Mengubah user owner', '2026-04-08 15:47:54'),
(194, 6, 'Mengubah user owner', '2026-04-08 15:47:58'),
(195, 6, 'Mengubah user owner', '2026-04-08 15:48:47'),
(196, 6, 'Mengubah user owner', '2026-04-08 15:50:00'),
(197, 6, 'Menambah user nes', '2026-04-08 15:51:01'),
(198, 6, 'Menambah kendaraan B 4567 SS', '2026-04-08 15:51:21'),
(199, 6, 'Menambah kendaraan B 4567 SS', '2026-04-08 15:52:41'),
(200, 6, 'Menghapus kendaraan ID 27', '2026-04-08 15:52:47'),
(201, 6, 'Menambah kendaraan F 123 BA', '2026-04-08 15:53:10'),
(202, 6, 'Menambah tarif lainnya sebesar 15000', '2026-04-08 15:53:26'),
(203, 6, 'Menghapus tarif ID 7', '2026-04-08 15:54:20'),
(204, 6, 'Menambah tarif lainnya sebesar 15000', '2026-04-08 15:54:26'),
(205, 7, 'Login ke sistem sebagai petugas', '2026-04-08 15:57:00'),
(206, 6, 'Login ke sistem sebagai admin', '2026-04-08 15:59:06'),
(207, 7, 'Login ke sistem sebagai petugas', '2026-04-08 16:01:31'),
(208, 6, 'Login ke sistem sebagai admin', '2026-04-08 16:12:40'),
(209, 7, 'Login ke sistem sebagai petugas', '2026-04-08 16:14:31'),
(210, 6, 'Login ke sistem sebagai admin', '2026-04-08 16:15:12'),
(211, 7, 'Login ke sistem sebagai petugas', '2026-04-08 16:19:42'),
(212, 9, 'Login ke sistem sebagai owner', '2026-04-08 16:41:16'),
(213, 9, 'Membuka dashboard owner', '2026-04-08 16:41:16'),
(214, 9, 'Melihat rekap transaksi', '2026-04-08 16:41:23'),
(215, 9, 'Melihat rekap transaksi', '2026-04-08 16:45:40'),
(216, 9, 'Melihat rekap transaksi', '2026-04-08 16:45:43'),
(217, 9, 'Melihat rekap transaksi', '2026-04-08 16:45:47'),
(218, 9, 'Melihat rekap transaksi', '2026-04-08 16:47:17'),
(219, 9, 'Melihat rekap transaksi', '2026-04-08 16:48:29'),
(220, 9, 'Melihat rekap transaksi', '2026-04-08 16:48:39'),
(221, 9, 'Membuka dashboard owner', '2026-04-08 16:48:44'),
(222, 9, 'Melihat rekap transaksi', '2026-04-08 16:48:47'),
(223, 9, 'Membuka dashboard owner', '2026-04-08 16:48:50');

-- --------------------------------------------------------

--
-- Table structure for table `tarif`
--

CREATE TABLE `tarif` (
  `id_tarif` int NOT NULL,
  `jenis_kendaraan` enum('motor','mobil','lainnya') NOT NULL,
  `tarif_per_jam` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tarif`
--

INSERT INTO `tarif` (`id_tarif`, `jenis_kendaraan`, `tarif_per_jam`) VALUES
(5, 'motor', 2000),
(6, 'mobil', 10000),
(8, 'lainnya', 15000);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_parkir` int NOT NULL,
  `id_kendaraan` int DEFAULT NULL,
  `waktu_masuk` datetime NOT NULL,
  `waktu_keluar` datetime DEFAULT NULL,
  `id_tarif` int DEFAULT NULL,
  `durasi_jam` int DEFAULT NULL,
  `biaya_total` decimal(10,0) DEFAULT NULL,
  `status` enum('masuk','keluar') DEFAULT 'masuk',
  `id_user` int DEFAULT NULL,
  `id_area` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_parkir`, `id_kendaraan`, `waktu_masuk`, `waktu_keluar`, `id_tarif`, `durasi_jam`, `biaya_total`, `status`, `id_user`, `id_area`) VALUES
(24, 26, '2026-04-08 16:01:43', '2026-04-08 16:08:08', 6, 1, 10000, 'keluar', 7, 7),
(25, 28, '2026-04-08 16:14:47', '2026-04-08 16:24:40', 8, 1, 15000, 'keluar', 7, 1),
(26, 25, '2026-04-08 16:22:53', NULL, 5, NULL, NULL, 'masuk', 7, 3);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('admin','petugas','owner') NOT NULL,
  `status_aktif` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama_lengkap`, `username`, `password`, `role`, `status_aktif`) VALUES
(6, 'admin', 'admin', 'admin123', 'admin', 1),
(7, 'petugas', 'petugas', 'petugas123', 'petugas', 1),
(9, 'owner123', 'owner', 'owner123', 'owner', 1),
(11, 'nesa', 'nes', 'nesa123', 'owner', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `area_parkir`
--
ALTER TABLE `area_parkir`
  ADD PRIMARY KEY (`id_area`);

--
-- Indexes for table `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD PRIMARY KEY (`id_kendaraan`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tarif`
--
ALTER TABLE `tarif`
  ADD PRIMARY KEY (`id_tarif`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_parkir`),
  ADD KEY `id_kendaraan` (`id_kendaraan`),
  ADD KEY `id_tarif` (`id_tarif`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_area` (`id_area`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `area_parkir`
--
ALTER TABLE `area_parkir`
  MODIFY `id_area` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kendaraan`
--
ALTER TABLE `kendaraan`
  MODIFY `id_kendaraan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=224;

--
-- AUTO_INCREMENT for table `tarif`
--
ALTER TABLE `tarif`
  MODIFY `id_tarif` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_parkir` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_kendaraan`) REFERENCES `kendaraan` (`id_kendaraan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_tarif`) REFERENCES `tarif` (`id_tarif`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_4` FOREIGN KEY (`id_area`) REFERENCES `area_parkir` (`id_area`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
