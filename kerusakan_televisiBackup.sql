-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2025 at 04:54 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kerusakan_televisi`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `ID_ADMIN` int(11) NOT NULL,
  `USERNAME` varchar(10) DEFAULT NULL,
  `PASSWORD` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ID_ADMIN`, `USERNAME`, `PASSWORD`) VALUES
(11001, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `gejala_kerusakan`
--

CREATE TABLE `gejala_kerusakan` (
  `ID_GEJALA` int(11) NOT NULL,
  `NAMA_GEJALA` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gejala_kerusakan`
--

INSERT INTO `gejala_kerusakan` (`ID_GEJALA`, `NAMA_GEJALA`) VALUES
(1, 'Layar pada Televisi Gelap'),
(2, 'Suara Tidak Ada'),
(3, 'Televisi Mati Total'),
(4, 'Remote Televisi Tidak Berfungsi'),
(5, 'Cahaya pada Layar Televisi Berkedip'),
(6, 'Gambar Bergaris'),
(7, 'Gambar Bergetar'),
(8, 'Televisi Tidak Bisa Mendapatkan Siaran'),
(9, 'Mati Standby'),
(10, 'Lampu Indikator Televisi Berkedip'),
(11, 'Lampu Indikator Tidak Menyala'),
(12, 'Sekring Terputus / Terbakar'),
(13, 'Televisi Mendadak Mati Setelah Dinyalakan'),
(14, 'Layar Putih Polos'),
(15, 'Layar terlihat Berkedip / Redup / Gelap'),
(16, 'Cahaya pada Layar Tidak Merata Di Seluruh Layar'),
(17, 'OSD (On Screen Display) Tidak Tampil'),
(18, 'Gambar Tidak Tampil'),
(19, 'Layar Mengalami Kerusakan Fisik Seperti Retak atau Pecah'),
(20, 'Gambar Muncul Setengah pada Layar'),
(21, 'Layar Pelangi atau Garis Berwarna atau ada Warna yang Hilang'),
(22, 'Gambar Blur, Berbayang, Klise atau Low Contras');

-- --------------------------------------------------------

--
-- Table structure for table `kerusakan`
--

CREATE TABLE `kerusakan` (
  `ID_KERUSAKAN` int(11) NOT NULL,
  `NAMA_KERUSAKAN` text DEFAULT NULL,
  `SARAN_PERBAIKAN` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kerusakan`
--

INSERT INTO `kerusakan` (`ID_KERUSAKAN`, `NAMA_KERUSAKAN`, `SARAN_PERBAIKAN`) VALUES
(1, 'Power Supply', 'Mengganti Power Supply'),
(2, 'Inverter', 'Mengganti Inverter'),
(3, 'Mainboard', 'Mengganti Mainboard'),
(4, 'T-Con', 'Mengganti T-Con'),
(5, 'Panel LED / LCD', 'Mengganti Panel LED / LCD'),
(6, 'Speaker', 'Mengganti Speaker'),
(7, 'Backlight', 'Mengganti Backlight');

-- --------------------------------------------------------

--
-- Table structure for table `pengetahuan_kerusakan`
--

CREATE TABLE `pengetahuan_kerusakan` (
  `ID_PENGETAHUAN` varchar(3) NOT NULL,
  `ID_GEJALA` int(11) DEFAULT NULL,
  `ID_KERUSAKAN` int(11) DEFAULT NULL,
  `MB` float DEFAULT NULL,
  `MD` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengetahuan_kerusakan`
--

INSERT INTO `pengetahuan_kerusakan` (`ID_PENGETAHUAN`, `ID_GEJALA`, `ID_KERUSAKAN`, `MB`, `MD`) VALUES
('p01', 1, 3, 0.5, 0.2),
('p02', 1, 1, 0.5, 0.2),
('p03', 1, 7, 1, 0.4),
('p04', 2, 6, 1, 0),
('p05', 2, 3, 0.5, 0.2),
('p06', 3, 1, 1, 0),
('p07', 3, 3, 0.6, 0.3),
('p08', 4, 3, 1, 0),
('p09', 5, 3, 0.7, 0.4),
('p10', 5, 5, 1, 0),
('p11', 6, 5, 1, 0),
('p12', 6, 4, 0.5, 0.2),
('p13', 7, 4, 1, 0.2),
('p14', 7, 5, 0.8, 0.4),
('p15', 8, 3, 1, 0.2),
('p16', 9, 1, 0.8, 0.2),
('p17', 9, 3, 0.5, 0.2),
('p18', 10, 3, 1, 0),
('p19', 10, 1, 0.5, 0.8),
('p20', 11, 1, 1, 0),
('p21', 11, 3, 0.5, 0),
('p22', 12, 3, 0.8, 0.2),
('p23', 12, 1, 1, 0),
('p24', 13, 1, 1, 0.2),
('p25', 14, 3, 0.8, 0.2),
('p26', 14, 4, 0.4, 0),
('p27', 15, 7, 1, 0),
('p28', 16, 7, 1, 0.2),
('p29', 17, 4, 1, 0.4),
('p30', 20, 5, 1, 0),
('p31', 21, 5, 0.8, 0.4),
('p32', 22, 4, 0.8, 0.2),
('p33', 23, 4, 0.8, 0.4),
('p34', 18, 5, 1, 0.2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID_ADMIN`);

--
-- Indexes for table `gejala_kerusakan`
--
ALTER TABLE `gejala_kerusakan`
  ADD PRIMARY KEY (`ID_GEJALA`);

--
-- Indexes for table `kerusakan`
--
ALTER TABLE `kerusakan`
  ADD PRIMARY KEY (`ID_KERUSAKAN`);

--
-- Indexes for table `pengetahuan_kerusakan`
--
ALTER TABLE `pengetahuan_kerusakan`
  ADD PRIMARY KEY (`ID_PENGETAHUAN`),
  ADD KEY `FK_RELATIONSHIP_1` (`ID_GEJALA`),
  ADD KEY `FK_RELATIONSHIP_2` (`ID_KERUSAKAN`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kerusakan`
--
ALTER TABLE `kerusakan`
  MODIFY `ID_KERUSAKAN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pengetahuan_kerusakan`
--
ALTER TABLE `pengetahuan_kerusakan`
  ADD CONSTRAINT `FK_RELATIONSHIP_1` FOREIGN KEY (`ID_GEJALA`) REFERENCES `gejala_kerusakan` (`ID_GEJALA`),
  ADD CONSTRAINT `FK_RELATIONSHIP_2` FOREIGN KEY (`ID_KERUSAKAN`) REFERENCES `kerusakan` (`ID_KERUSAKAN`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
