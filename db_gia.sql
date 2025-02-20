-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2025 at 07:08 AM
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
-- Database: `db_gia`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id_admin` bigint(11) UNSIGNED NOT NULL,
  `id_user` bigint(11) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `profile` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`id_admin`, `id_user`, `nama`, `profile`) VALUES
(1, 1, 'IMAM MISMAN TURMUDHI', '67a4f9171de80.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tb_evaluasi`
--

CREATE TABLE `tb_evaluasi` (
  `id_evaluasi` int(11) UNSIGNED NOT NULL,
  `evaluasi_tugas` varchar(255) DEFAULT NULL,
  `evaluasi_kehadiran` varchar(255) DEFAULT NULL,
  `evaluasi_total` varchar(255) DEFAULT NULL,
  `id_kelas_assesment` bigint(11) UNSIGNED NOT NULL,
  `id_murid` bigint(11) UNSIGNED NOT NULL,
  `tanggal` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_evaluasi`
--

INSERT INTO `tb_evaluasi` (`id_evaluasi`, `evaluasi_tugas`, `evaluasi_kehadiran`, `evaluasi_total`, `id_kelas_assesment`, `id_murid`, `tanggal`) VALUES
(13, '65', '100', '75.5', 29, 3, '2025-02-01'),
(14, '44', '11', '13.4', 29, 3, '2025-03-24'),
(21, '90', '75', '85.5', 29, 3, '2025-02-19');

-- --------------------------------------------------------

--
-- Table structure for table `tb_guru`
--

CREATE TABLE `tb_guru` (
  `id_guru` bigint(11) UNSIGNED NOT NULL,
  `id_user` bigint(11) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `profile` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_guru`
--

INSERT INTO `tb_guru` (`id_guru`, `id_user`, `nama`, `profile`) VALUES
(1, 4, 'SRI INTAN. W, S.Pd (Matematika)', '6782afef7aad9.jpg'),
(2, 14, 'WIFDA ANIS, S.Pd (Fisika)', NULL),
(3, 16, 'TRIO MAHENDRO. A, S.Pd (IPA-Matematika)', NULL),
(4, 17, 'KARLINA ANANDA B, S.Pd (Kimia)', NULL),
(5, 18, 'AINU SYAIFA, S.Pd (Biologi)', NULL),
(6, 19, 'SITTI AYU P.S, M.Pd (B.Inggris)', NULL),
(7, 49, 'SRI INTAN. W, S.Pd (Matematika)', NULL),
(8, 50, 'WIFDA ANIS, S.Pd (Fisika)', NULL),
(9, 51, 'TRIO MAHENDRO. A, S.Pd (IPA-Matematika)', NULL),
(10, 52, 'KARLINA ANANDA B, S.Pd (Kimia)', NULL),
(11, 53, 'AINU SYAIFA, S.Pd (Biologi)', NULL),
(12, 54, 'SITTI AYU P.S, M.Pd (B.Inggris)', NULL),
(13, 56, 'SRI handayani', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_jawaban`
--

CREATE TABLE `tb_jawaban` (
  `id_jawaban` bigint(11) UNSIGNED NOT NULL,
  `id_soal` bigint(11) UNSIGNED NOT NULL,
  `id_murid` bigint(11) UNSIGNED NOT NULL,
  `nama_file` varchar(255) DEFAULT NULL,
  `file_jawaban` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_jawaban`
--

INSERT INTO `tb_jawaban` (`id_jawaban`, `id_soal`, `id_murid`, `nama_file`, `file_jawaban`) VALUES
(22, 18, 3, 'semester 7.pdf', '67b1887d5f09f.pdf'),
(23, 22, 3, 'semester 2.pdf', '67b48db89abd8.pdf'),
(24, 22, 5, 'semester 2.pdf', '67b48e582d609.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kelas`
--

CREATE TABLE `tb_kelas` (
  `id_kelas` bigint(11) UNSIGNED NOT NULL,
  `nama_kelas` varchar(255) NOT NULL,
  `nama_ruangan` varchar(255) NOT NULL,
  `duty_start_time` time NOT NULL,
  `duty_end_time` time NOT NULL,
  `duty_start_day` varchar(255) NOT NULL,
  `duty_end_day` varchar(255) NOT NULL,
  `id_guru` bigint(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kelas`
--

INSERT INTO `tb_kelas` (`id_kelas`, `nama_kelas`, `nama_ruangan`, `duty_start_time`, `duty_end_time`, `duty_start_day`, `duty_end_day`, `id_guru`) VALUES
(29, 'Kelas II', 'Ruangan A', '09:00:00', '10:00:00', 'Senin', 'Selasa', 7),
(30, 'Kelas 3', 'Ruangan A', '10:00:00', '11:00:00', 'Rabu', 'Rabu', 13);

-- --------------------------------------------------------

--
-- Table structure for table `tb_kelas_assesment`
--

CREATE TABLE `tb_kelas_assesment` (
  `id_kelas_assesment` bigint(11) UNSIGNED NOT NULL,
  `id_kelas` bigint(11) UNSIGNED NOT NULL,
  `id_murid` bigint(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kelas_assesment`
--

INSERT INTO `tb_kelas_assesment` (`id_kelas_assesment`, `id_kelas`, `id_murid`) VALUES
(64, 29, 3),
(65, 30, 8),
(66, 30, 9),
(67, 29, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tb_materi`
--

CREATE TABLE `tb_materi` (
  `id_materi` bigint(11) UNSIGNED NOT NULL,
  `nama_materi` varchar(255) NOT NULL,
  `file_materi` text DEFAULT NULL,
  `id_kelas_assesment` bigint(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_materi`
--

INSERT INTO `tb_materi` (`id_materi`, `nama_materi`, `file_materi`, `id_kelas_assesment`) VALUES
(11, 'Materi 1', '67b171ca14741.pdf', 29),
(12, 'tes', '67b1869497b13.pdf', 29);

-- --------------------------------------------------------

--
-- Table structure for table `tb_murid`
--

CREATE TABLE `tb_murid` (
  `id_murid` bigint(11) UNSIGNED NOT NULL,
  `id_user` bigint(11) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `profile` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_murid`
--

INSERT INTO `tb_murid` (`id_murid`, `id_user`, `nama`, `profile`) VALUES
(3, 7, 'Hazim Hidayatuz Zikri', '67838ed84a278.png'),
(5, 15, 'Qonita Syahidah Widi', NULL),
(7, 21, 'Alif Al Ihwan Dinata', NULL),
(8, 22, 'Shinta Rahmona Putri', NULL),
(9, 23, 'M.Farhan Yasen', NULL),
(10, 24, 'Hazim Azira', NULL),
(11, 25, 'Farhan Hafizh', NULL),
(12, 26, 'Fathia Ghassani', NULL),
(13, 27, 'Deand Aprilio', NULL),
(14, 28, 'Alifah Khairunisa', NULL),
(15, 29, 'Nadin Prayoza', NULL),
(16, 30, 'Dian Mardiah', NULL),
(17, 31, 'Kenra Tio Alwi', NULL),
(18, 32, 'Irfan Maulana', NULL),
(19, 33, 'Halim Akbar Al Dzikri', NULL),
(20, 34, 'Aisyah Tissy Junieshof', NULL),
(21, 35, 'Veronica Chintya', NULL),
(22, 36, 'Zaschia Chalila Diputri', NULL),
(23, 37, 'Ashya Hafidzah Diyas', NULL),
(24, 38, 'Jihan Luthfiah Widi', NULL),
(25, 39, 'M.Daffa Ramadhan', NULL),
(26, 40, 'Rahmatul Fajri', NULL),
(27, 41, 'Salwa Insan Mawaddah', NULL),
(28, 42, 'Puti Claresta Syahdia', NULL),
(29, 43, 'M.Faiz Dwi Putra Sudarsa', NULL),
(30, 44, 'Aliyah Dzakiyah', NULL),
(31, 45, 'Teguh Esa Maulanna', NULL),
(32, 46, 'Muhammad Zidane', NULL),
(33, 47, 'Fikra Riberta Putra', NULL),
(34, 48, 'Miftahul Khairunnisa', NULL),
(35, 55, 'Fazilla Rahma', NULL),
(36, 57, 'Alif Pagar', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_pimpinan`
--

CREATE TABLE `tb_pimpinan` (
  `id_pimpinan` bigint(11) UNSIGNED NOT NULL,
  `id_user` bigint(11) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `profile` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_pimpinan`
--

INSERT INTO `tb_pimpinan` (`id_pimpinan`, `id_user`, `nama`, `profile`) VALUES
(1, 5, 'Oknira Jalfi, S.Pd', '6782b0063cc4b.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tb_presensi`
--

CREATE TABLE `tb_presensi` (
  `id_presensi` bigint(11) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `status` varchar(255) NOT NULL,
  `id_kelas_assesment` bigint(11) UNSIGNED NOT NULL,
  `id_murid` bigint(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_presensi`
--

INSERT INTO `tb_presensi` (`id_presensi`, `tanggal`, `status`, `id_kelas_assesment`, `id_murid`) VALUES
(14, '2025-02-09', 'Hadir', 29, 3),
(15, '2025-02-10', 'Hadir', 29, 3),
(16, '2025-02-17', 'Tidak Hadir', 29, 3),
(17, '2025-02-19', 'Hadir', 29, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tb_soal`
--

CREATE TABLE `tb_soal` (
  `id_soal` bigint(11) UNSIGNED NOT NULL,
  `nama_soal` varchar(255) DEFAULT NULL,
  `file_soal` text DEFAULT NULL,
  `id_kelas_assesment` bigint(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_soal`
--

INSERT INTO `tb_soal` (`id_soal`, `nama_soal`, `file_soal`, `id_kelas_assesment`) VALUES
(18, 'Soal 1', '67b1715befd63.pdf', 29),
(19, 'Materi 2', '67b17228da327.pdf', 29),
(20, 'tes', '67b1867f73f68.pdf', 29),
(21, 'Materi 3', '67b18a2b0abde.pdf', 29),
(22, 'Soal 7', '67b48d49243d8.pdf', 29);

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` bigint(11) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `username`, `password`, `role`) VALUES
(1, 'admin', '123', 'Admin'),
(5, 'pimpinan', '123', 'Pimpinan'),
(7, 'siswa2', '123', 'Murid'),
(15, 'siswa3', '123', 'Murid'),
(21, 'siswa5', '123', 'Murid'),
(22, 'siswa6', '123', 'Murid'),
(23, 'siswa7', '123', 'Murid'),
(24, 'siswa8', '123', 'Murid'),
(25, 'siswa9', '123', 'Murid'),
(26, 'siswa10', '123', 'Murid'),
(27, 'siswa11', '123', 'Murid'),
(28, 'siswa12', '123', 'Murid'),
(29, 'siswa13', '123', 'Murid'),
(30, 'siswa14', '123', 'Murid'),
(31, 'siswa15', '123', 'Murid'),
(32, 'siswa16', '123', 'Murid'),
(33, 'siswa17', '123', 'Murid'),
(34, 'siswa18', '123', 'Murid'),
(35, 'siswa19', '123', 'Murid'),
(36, 'siswa20', '123', 'Murid'),
(37, 'siswa21', '123', 'Murid'),
(38, 'siswa22', '123', 'Murid'),
(39, 'siswa23', '123', 'Murid'),
(40, 'siswa24', '123', 'Murid'),
(41, 'siswa25', '123', 'Murid'),
(42, 'siswa26', '123', 'Murid'),
(43, 'siswa27', '123', 'Murid'),
(44, 'siswa28', '123', 'Murid'),
(45, 'siswa29', '123', 'Murid'),
(46, 'siswa30', '123', 'Murid'),
(47, 'siswa31', '123', 'Murid'),
(48, 'siswa32', '123', 'Murid'),
(49, 'guru1', '123', 'Guru'),
(50, 'guru2', '123', 'Guru'),
(51, 'guru3', '123', 'Guru'),
(52, 'guru4', '123', 'Guru'),
(54, 'guru6', '123', 'Guru'),
(55, 'siswa1', '123', 'Murid'),
(56, 'guru123', '123', 'Guru'),
(57, 'siswa123', '123', 'Murid');

-- --------------------------------------------------------

--
-- Table structure for table `tb_wali`
--

CREATE TABLE `tb_wali` (
  `id_wali` bigint(11) UNSIGNED NOT NULL,
  `id_murids` bigint(11) UNSIGNED NOT NULL,
  `nama_wali` varchar(255) DEFAULT NULL,
  `alamat_wali` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_wali`
--

INSERT INTO `tb_wali` (`id_wali`, `id_murids`, `nama_wali`, `alamat_wali`) VALUES
(4, 3, 'ccx', 'hghnb');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `tb_evaluasi`
--
ALTER TABLE `tb_evaluasi`
  ADD PRIMARY KEY (`id_evaluasi`);

--
-- Indexes for table `tb_guru`
--
ALTER TABLE `tb_guru`
  ADD PRIMARY KEY (`id_guru`);

--
-- Indexes for table `tb_jawaban`
--
ALTER TABLE `tb_jawaban`
  ADD PRIMARY KEY (`id_jawaban`);

--
-- Indexes for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `tb_kelas_assesment`
--
ALTER TABLE `tb_kelas_assesment`
  ADD PRIMARY KEY (`id_kelas_assesment`);

--
-- Indexes for table `tb_materi`
--
ALTER TABLE `tb_materi`
  ADD PRIMARY KEY (`id_materi`);

--
-- Indexes for table `tb_murid`
--
ALTER TABLE `tb_murid`
  ADD PRIMARY KEY (`id_murid`);

--
-- Indexes for table `tb_pimpinan`
--
ALTER TABLE `tb_pimpinan`
  ADD PRIMARY KEY (`id_pimpinan`);

--
-- Indexes for table `tb_presensi`
--
ALTER TABLE `tb_presensi`
  ADD PRIMARY KEY (`id_presensi`);

--
-- Indexes for table `tb_soal`
--
ALTER TABLE `tb_soal`
  ADD PRIMARY KEY (`id_soal`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `tb_wali`
--
ALTER TABLE `tb_wali`
  ADD PRIMARY KEY (`id_wali`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id_admin` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_evaluasi`
--
ALTER TABLE `tb_evaluasi`
  MODIFY `id_evaluasi` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tb_guru`
--
ALTER TABLE `tb_guru`
  MODIFY `id_guru` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tb_jawaban`
--
ALTER TABLE `tb_jawaban`
  MODIFY `id_jawaban` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  MODIFY `id_kelas` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tb_kelas_assesment`
--
ALTER TABLE `tb_kelas_assesment`
  MODIFY `id_kelas_assesment` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `tb_materi`
--
ALTER TABLE `tb_materi`
  MODIFY `id_materi` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tb_murid`
--
ALTER TABLE `tb_murid`
  MODIFY `id_murid` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tb_pimpinan`
--
ALTER TABLE `tb_pimpinan`
  MODIFY `id_pimpinan` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_presensi`
--
ALTER TABLE `tb_presensi`
  MODIFY `id_presensi` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tb_soal`
--
ALTER TABLE `tb_soal`
  MODIFY `id_soal` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `tb_wali`
--
ALTER TABLE `tb_wali`
  MODIFY `id_wali` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
