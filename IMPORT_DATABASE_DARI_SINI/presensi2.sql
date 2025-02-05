-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 05, 2025 at 03:32 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `presensi2`
--

-- --------------------------------------------------------

--
-- Table structure for table `absen`
--

CREATE TABLE `absen` (
  `id_absen` int(11) NOT NULL,
  `nim` varchar(250) NOT NULL,
  `id_status` int(11) DEFAULT NULL,
  `id_jadwal` int(11) DEFAULT NULL,
  `tanggal_absen` date DEFAULT NULL,
  `jam_masuk` time DEFAULT NULL,
  `tgl_keluar` date DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL,
  `keterangan` varchar(55) DEFAULT NULL,
  `logbook` varchar(255) DEFAULT NULL,
  `foto_absen` varchar(255) DEFAULT NULL,
  `latlong` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `absen`
--

INSERT INTO `absen` (`id_absen`, `nim`, `id_status`, `id_jadwal`, `tanggal_absen`, `jam_masuk`, `tgl_keluar`, `jam_keluar`, `keterangan`, `logbook`, `foto_absen`, `latlong`) VALUES
(376, 'A11.2021.13881', 1, 3, '2025-01-22', '21:06:05', '2025-01-22', '21:06:19', 'Jarak 7006 meter dari kantor, TERLAMBAT 13 jam 7 menit', 'Sudah selesai', 'A11.2021.13881_2025-01-22.png', '-7.0298617, 110.4969732'),
(377, 'A11.2021.13881', 1, 1, '2025-02-03', '21:55:29', '2025-02-03', '21:55:37', 'Jarak 6166 meter dari kantor, TERLAMBAT 13 jam 56 menit', NULL, 'A11.2021.13881_2025-02-03.png', '-6.9730304, 110.4019456'),
(378, 'A11.2021.13881', 1, 2, '2025-02-04', '10:18:08', '2025-02-04', '10:18:39', 'Jarak 4157 meter dari kantor, TERLAMBAT 2 jam 19 menit', 'posyandu', 'A11.2021.13881_2025-02-04.png', '-6.9926912, 110.4216064');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` int(11) NOT NULL,
  `nama_hari` varchar(11) NOT NULL,
  `waktu_masuk` time NOT NULL,
  `waktu_pulang` time NOT NULL,
  `status` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id_jadwal`, `nama_hari`, `waktu_masuk`, `waktu_pulang`, `status`) VALUES
(1, 'Senin', '07:00:00', '14:00:00', 'Aktif'),
(2, 'Selasa', '07:00:00', '14:00:00', 'Aktif'),
(3, 'Rabu', '07:00:00', '14:00:00', 'Aktif'),
(4, 'Kamis', '07:00:00', '14:00:00', 'Aktif'),
(5, 'Jumat', '07:30:00', '14:00:00', 'Aktif'),
(6, 'Sabtu', '00:00:00', '00:00:00', 'Aktif'),
(7, 'Minggu', '00:00:00', '00:00:00', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `penempatan`
--

CREATE TABLE `penempatan` (
  `penempatan_id` int(11) NOT NULL,
  `penempatan_nama` varchar(50) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `latitude` varchar(30) NOT NULL,
  `longitude` varchar(30) NOT NULL,
  `link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `penempatan`
--

INSERT INTO `penempatan` (`penempatan_id`, `penempatan_nama`, `alamat`, `latitude`, `longitude`, `link`) VALUES
(18, 'Udinus', 'Jl. Imam Bonjol No.207, Pendrikan Kidul, Kec. Semarang Tengah, Kota Semarang, Jawa Tengah 50131', '-6.9826794', '110.4090606', 'https://maps.app.goo.gl/d3PnXf6dD6ywFTQb8'),
(20, 'Puskesmas Tlogosari Kulon', 'Jl. Taman Satrio Manah No.2, Tlogosari Kulon, Kec. Pedurungan, Kota Semarang, Jawa Tengah 50196', '-6.980688108362258', '110.45728062412445', 'https://maps.app.goo.gl/DFZRPcRNuWYXdztQ9');

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id_pengaturan` int(11) NOT NULL,
  `penempatan_id` int(11) NOT NULL,
  `batas_telat` int(11) NOT NULL,
  `jarak` int(11) NOT NULL,
  `fitur_foto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pengaturan`
--

INSERT INTO `pengaturan` (`id_pengaturan`, `penempatan_id`, `batas_telat`, `jarak`, `fitur_foto`) VALUES
(1, 1, 30, 500, 1),
(11, 2, 30, 2, 1),
(12, 3, 30, 2, 1),
(13, 12, 30, 2, 1),
(14, 4, 30, 2, 1),
(15, 5, 30, 2, 1),
(16, 6, 30, 2, 1),
(17, 7, 30, 2, 1),
(18, 8, 30, 2, 1),
(19, 9, 30, 2, 1),
(20, 10, 30, 2, 1),
(21, 11, 30, 2, 1),
(22, 13, 30, 500, 1),
(23, 14, 30, 2, 1),
(24, 15, 30, 2, 1),
(25, 16, 30, 2, 1),
(26, 17, 30, 2, 1),
(27, 18, 30, 500, 1),
(28, 20, 30, 20000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `nim` varchar(250) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `universitas` varchar(250) NOT NULL,
  `password` varchar(255) NOT NULL,
  `penempatan_id` int(11) NOT NULL,
  `foto_profil` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `nim`, `nama`, `universitas`, `password`, `penempatan_id`, `foto_profil`) VALUES
(66, 'A11.2021.13831', 'Rama', 'Udinus', '25d55ad283aa400af464c76d713c07ad', 20, NULL),
(67, 'A11.2021.13881', 'Rizal', 'Universitas Dian Nuswantoro', '25d55ad283aa400af464c76d713c07ad', 20, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `status_absen`
--

CREATE TABLE `status_absen` (
  `id_status` int(11) NOT NULL,
  `nama_status` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `status_absen`
--

INSERT INTO `status_absen` (`id_status`, `nama_status`) VALUES
(1, 'Hadir'),
(2, 'Izin'),
(3, 'Sakit'),
(4, 'Cuti');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absen`
--
ALTER TABLE `absen`
  ADD PRIMARY KEY (`id_absen`),
  ADD KEY `id_jadwal` (`id_jadwal`),
  ADD KEY `id_status` (`id_status`),
  ADD KEY `nik` (`nim`) USING BTREE;

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_jadwal` (`id_jadwal`);

--
-- Indexes for table `penempatan`
--
ALTER TABLE `penempatan`
  ADD PRIMARY KEY (`penempatan_id`);

--
-- Indexes for table `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id_pengaturan`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nim`) USING BTREE,
  ADD KEY `penempatan_id` (`penempatan_id`);

--
-- Indexes for table `status_absen`
--
ALTER TABLE `status_absen`
  ADD PRIMARY KEY (`id_status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absen`
--
ALTER TABLE `absen`
  MODIFY `id_absen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=379;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `penempatan`
--
ALTER TABLE `penempatan`
  MODIFY `penempatan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id_pengaturan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `status_absen`
--
ALTER TABLE `status_absen`
  MODIFY `id_status` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absen`
--
ALTER TABLE `absen`
  ADD CONSTRAINT `absen_ibfk_1` FOREIGN KEY (`id_status`) REFERENCES `status_absen` (`id_status`),
  ADD CONSTRAINT `absen_ibfk_3` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal` (`id_jadwal`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
