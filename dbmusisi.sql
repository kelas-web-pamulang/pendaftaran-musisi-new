-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost: 3306
-- Generation Time: Jun 06, 2024 at 06:47 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbmusisi`
--

-- --------------------------------------------------------

--
-- Table structure for table `pendaftar`
--

CREATE TABLE `pendaftar` (
  `id_pendaftar` int(11) NOT NULL,
  `nama_musisi` varchar(255) NOT NULL,
  `nim_musisi` varchar(15) NOT NULL,
  `email_musisi` varchar(255) NOT NULL,
  `alamat_musisi` varchar(255) NOT NULL,
  `hp_musisi` varchar(15) NOT NULL,
  `id_genre` int(11) NOT NULL,
  `pengalaman` varchar(25) NOT NULL,
  `usia` varchar(25) NOT NULL,
  `id_pilihan_instrumen` int(11) NOT NULL,
  `tanggal_tambah_data` datetime DEFAULT NULL,
  `tanggal_perbarui_data` datetime DEFAULT NULL,
  `tanggal_hapus_data` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pilihan_instrumen`
--

CREATE TABLE `pilihan_instrumen` (
  `id_pilihan_instrumen` int(11) NOT NULL,
  `nama_instrumen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_genre_musik`
--

CREATE TABLE `table_genre_musik` (
  `id_genre` int(11) NOT NULL,
  `nama_genre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pendaftar`
--
ALTER TABLE `pendaftar`
  ADD PRIMARY KEY (`id_pendaftar`),
  ADD KEY `id_genre` (`id_genre`),
  ADD KEY `id_pilihan_instrumen` (`id_pilihan_instrumen`);

--
-- Indexes for table `pilihan_instrumen`
--
ALTER TABLE `pilihan_instrumen`
  ADD PRIMARY KEY (`id_pilihan_instrumen`);

--
-- Indexes for table `table_genre_musik`
--
ALTER TABLE `table_genre_musik`
  ADD PRIMARY KEY (`id_genre`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pendaftar`
--
ALTER TABLE `pendaftar`
  MODIFY `id_pendaftar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pilihan_instrumen`
--
ALTER TABLE `pilihan_instrumen`
  MODIFY `id_pilihan_instrumen` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `table_genre_musik`
--
ALTER TABLE `table_genre_musik`
  MODIFY `id_genre` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
