-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 11, 2024 at 04:44 PM
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
-- Database: `presensi2`
--

-- --------------------------------------------------------

--
-- Table structure for table `absen`
--

CREATE TABLE `absen` (
  `id_absen` int(11) NOT NULL,
  `nik` varchar(16) NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id_jadwal`, `nama_hari`, `waktu_masuk`, `waktu_pulang`, `status`) VALUES
(1, 'Senin', '08:00:00', '16:00:00', 'Aktif'),
(2, 'Selasa', '08:00:00', '16:00:00', 'Aktif'),
(3, 'Rabu', '08:00:00', '16:00:00', 'Aktif'),
(4, 'Kamis', '08:00:00', '16:00:00', 'Aktif'),
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penempatan`
--

INSERT INTO `penempatan` (`penempatan_id`, `penempatan_nama`, `alamat`, `latitude`, `longitude`, `link`) VALUES
(1, 'Gayamsari', 'JL. Brigjen. Slamet Riyadi, No. 3,  Gayamsari, Kota Semarang, Jawa Tengah 50512, Indonesia', '-6.998499963090052', '110.45005596164846', 'https://maps.app.goo.gl/SS6BjJFLcZtpYWDG9'),
(2, 'Pedurungan', 'Jl. Brigjen Sudiarto 50246 Semarang Jawa Tengah', '-7.009210', '110.464752', 'https://maps.app.goo.gl/1azmZ2Xi3dy6XK8LA'),
(3, 'Genuk', 'Jl. Dong Biru No. 12 Kelurahan Genuksari Kecamatan Genuk Kota Semarang Jawa Tengah 50117', '-6.96543801276143', '110.477028271164', 'https://maps.app.goo.gl/5cRBKYvyFwyZ8H2w5'),
(4, 'Semarang Selatan', 'Jl. Durian I No.14, Lamper Kidul, Semarang Sel., Kota Semarang, Jawa Tengah 50249', '-7.008171989273294', '110.43978080988522', 'https://maps.app.goo.gl/uYYsBn2t4hncoR1V9'),
(5, 'Semarang Barat', 'Jl. Ronggolawe Baru. Gisikdrono Semarang Kota Semarang, Jawa Tengah 50149', '-6.982333', '110.389250', 'https://maps.app.goo.gl/K3Jdo4R4tKpmmZdj6'),
(6, 'Semarang Utara', 'Jl. Taman Brotojoyo No.2, Panggung Kidul, Semarang Utara, Kota Semarang, Jawa Tengah 50178', '-6.965528', '110.407139', 'https://maps.app.goo.gl/C8ZWSMEVGdjdQf347'),
(7, 'Semarang Timur', 'Jl. Krakatau VIII, Karangtempel, Semarang Tim., Kota Semarang, Jawa Tengah 50232', '-6.989428579569249', '110.43745804119327', 'https://maps.app.goo.gl/wTQqtKyQHsCFWcqx8'),
(8, 'Gajah Mungkur', 'Jl. S. Parman Semarang', '-7.004281480640262', '110.40892112870911', 'https://maps.app.goo.gl/aVr3u7BMYLWwNt8L9'),
(9, 'Candisari', 'Jl. Kesatrian No.18, Jatingaleh, Kec. Candisari, Kota Semarang, Jawa Tengah 50254', '-7.026685975772222', '110.42790653607993', 'https://maps.app.goo.gl/53TQbDe17a1hF6SZ7'),
(10, 'Banyumanik', 'Jalan Ngesrep Timur V Semarang', '-7.052722', '110.428639', 'https://maps.app.goo.gl/8ybsHe9vwXwHgJoAA'),
(11, 'Gunung Pati', 'JL. MR Wuyanto No.33, Gunungpati, Gunung Pati, Sumurrejo, Semarang, Kota Semarang, Jawa Tengah 50226', '-7.10367377253785', '110.38687282676845', 'https://maps.app.goo.gl/QFyo5FykYr6GzmPq6'),
(12, 'Ngaliyan', 'JL. Prof Hamka No.233 Semarang', '-6.9973550', '110.3474640', 'https://maps.app.goo.gl/r5GKfNptGwAD2EzcA'),
(13, 'Mijen', 'Jalan Raya Semarang – Boja W895+9QM Tambangan, Kota Semarang, Jawa Tengah', '-7.0815278', '110.3094167', 'https://maps.app.goo.gl/4cuiVHUAcRto9KJf8'),
(14, 'Tugu', 'Jl. Walisongo No.KM 10, Tugurejo, Kec. Tugu, Kota Semarang, Jawa Tengah 50182', '-6.9857778', '110.3450556', 'https://maps.app.goo.gl/3tQ2UW2yKHbbVuFX6'),
(15, 'Tembalang', 'Jalan Imam Suparto ‎Semarang WCRW+5V5, Bulusan, Kec. Tembalang, Kota Semarang, Jawa Tengah 50277', '-7.0596111', '110.4470833', 'https://maps.app.goo.gl/tb3tsETrEYJrE1mY9'),
(16, 'Semarang Tengah', 'Jalan Taman Seteran Barat No.1, Miroto, Semarang Tengah, Kota Semarang, Jawa Tengah 50134, Indonesia', '-7.0527222', '110.4286389', 'https://maps.app.goo.gl/GdUoR6TmvPrCC1rQ9'),
(18, 'tes', 'tes', '-6.990603035421752', '110.44500235944727', 'https://maps.app.goo.gl/NR6gC3UhzLa7RV9X6');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(27, 18, 30, 500, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `penempatan_id` int(11) NOT NULL,
  `foto_profil` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `nik`, `nama`, `password`, `penempatan_id`, `foto_profil`) VALUES
(58, '3374041406030001', 'Rahmanda Afridiansyah', '25d55ad283aa400af464c76d713c07ad', 1, '3374041406030001_66d359cfad333.png');

-- --------------------------------------------------------

--
-- Table structure for table `status_absen`
--

CREATE TABLE `status_absen` (
  `id_status` int(11) NOT NULL,
  `nama_status` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  ADD KEY `nik` (`nik`) USING BTREE;

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
  ADD UNIQUE KEY `nik` (`nik`) USING BTREE,
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
  MODIFY `id_absen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=374;

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
  MODIFY `penempatan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id_pengaturan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

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
