-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2026 at 02:32 PM
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
-- Database: `koperasi`
--

-- --------------------------------------------------------

--
-- Table structure for table `anggotas`
--

CREATE TABLE `anggotas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `no_anggota` varchar(255) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `foto_ktp` varchar(255) DEFAULT NULL,
  `alamat` text NOT NULL,
  `no_hp` varchar(255) NOT NULL,
  `tgl_gabung` date NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `anggotas`
--

INSERT INTO `anggotas` (`id`, `user_id`, `no_anggota`, `nik`, `foto_ktp`, `alamat`, `no_hp`, `tgl_gabung`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'ANG-202606-0001', '3201234567890001', 'ktp/jf99gtQ8kdCHiD1f8YgkyLSXQ8ZYVfnBLmZbrFDc.jpg', 'Jl. Koperasi No. 1', '081234567890', '2026-06-20', 'aktif', '2026-06-20 08:47:45', '2026-06-20 09:27:30');

-- --------------------------------------------------------

--
-- Table structure for table `angsurans`
--

CREATE TABLE `angsurans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pinjaman_id` bigint(20) UNSIGNED NOT NULL,
  `ke_berapa` int(11) NOT NULL,
  `jumlah_bayar` decimal(15,2) NOT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `keterangan_tolak` text DEFAULT NULL,
  `denda` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tgl_jatuh_tempo` date NOT NULL,
  `tgl_bayar` date DEFAULT NULL,
  `status` enum('belum_bayar','menunggu_verifikasi','lunas','telat','ditolak') NOT NULL DEFAULT 'belum_bayar',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_simpanans`
--

CREATE TABLE `jenis_simpanans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_simpanans`
--

INSERT INTO `jenis_simpanans` (`id`, `nama`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'Simpanan Pokok', 'Wajib dibayar sekali saat mendaftar', '2026-06-20 08:47:45', '2026-06-20 08:47:45'),
(2, 'Simpanan Wajib', 'Wajib dibayar setiap bulan', '2026-06-20 08:47:45', '2026-06-20 08:47:45'),
(3, 'Simpanan Sukarela', 'Bebas dan bisa ditarik kapan saja', '2026-06-20 08:47:45', '2026-06-20 08:47:45');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_19_162442_create_anggotas_table', 1),
(5, '2026_06_19_162443_create_jenis_simpanans_table', 1),
(6, '2026_06_19_162443_create_pinjamen_table', 1),
(7, '2026_06_19_162443_create_simpanans_table', 1),
(8, '2026_06_19_162444_create_angsurans_table', 1),
(9, '2026_06_19_162445_create_shus_table', 1),
(10, '2026_06_20_091419_add_bukti_pembayaran_to_angsurans_table', 1),
(11, '2026_06_20_123438_create_transaksi_kas_table', 1),
(12, '2026_06_20_144048_add_status_and_bukti_to_simpanans_table', 1),
(13, '2026_06_20_145257_tambah_kolom_status_bukti_v2', 1),
(14, '2026_06_20_171401_create_pengaturan_shus_table', 2),
(15, '2026_06_20_171423_add_details_to_shus_table', 2),
(16, '2026_06_20_193104_create-pengaturan-bunga-pinjamans-table', 3),
(17, '2026_06_20_220440_add_keterangan_tolak_to_angsurans_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan_bunga_pinjamans`
--

CREATE TABLE `pengaturan_bunga_pinjamans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenor` int(11) NOT NULL COMMENT 'Lama angsuran dalam bulan: 3, 6, 12, 24',
  `bunga_persen` decimal(5,2) NOT NULL COMMENT 'Bunga flat per bulan dalam persen, contoh 1.50 = 1.5%',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengaturan_bunga_pinjamans`
--

INSERT INTO `pengaturan_bunga_pinjamans` (`id`, `tenor`, `bunga_persen`, `created_at`, `updated_at`) VALUES
(1, 3, 1.50, '2026-06-20 12:33:04', '2026-06-20 12:33:04'),
(2, 6, 1.75, '2026-06-20 12:33:04', '2026-06-20 12:33:04'),
(3, 12, 2.00, '2026-06-20 12:33:04', '2026-06-20 12:33:04'),
(4, 24, 2.25, '2026-06-20 12:33:04', '2026-06-20 12:33:04');

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan_shus`
--

CREATE TABLE `pengaturan_shus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `persen_anggota` decimal(5,2) NOT NULL DEFAULT 40.00,
  `persen_cadangan` decimal(5,2) NOT NULL DEFAULT 25.00,
  `persen_pengurus` decimal(5,2) NOT NULL DEFAULT 10.00,
  `persen_karyawan` decimal(5,2) NOT NULL DEFAULT 5.00,
  `persen_pendidikan` decimal(5,2) NOT NULL DEFAULT 5.00,
  `persen_sosial` decimal(5,2) NOT NULL DEFAULT 15.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengaturan_shus`
--

INSERT INTO `pengaturan_shus` (`id`, `persen_anggota`, `persen_cadangan`, `persen_pengurus`, `persen_karyawan`, `persen_pendidikan`, `persen_sosial`, `created_at`, `updated_at`) VALUES
(1, 40.00, 25.00, 10.00, 5.00, 5.00, 15.00, '2026-06-20 10:22:05', '2026-06-20 10:22:33');

-- --------------------------------------------------------

--
-- Table structure for table `pinjamans`
--

CREATE TABLE `pinjamans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `anggota_id` bigint(20) UNSIGNED NOT NULL,
  `disetujui_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `jumlah_pinjaman` decimal(15,2) NOT NULL,
  `tenor` int(11) NOT NULL,
  `bunga` decimal(5,2) NOT NULL,
  `status` enum('menunggu','disetujui','ditolak','lunas') NOT NULL DEFAULT 'menunggu',
  `tgl_pengajuan` date NOT NULL,
  `tgl_cair` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shus`
--

CREATE TABLE `shus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `anggota_id` bigint(20) UNSIGNED NOT NULL,
  `periode` year(4) NOT NULL,
  `jasa_modal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `jasa_usaha` decimal(15,2) NOT NULL DEFAULT 0.00,
  `jumlah` decimal(15,2) NOT NULL,
  `tgl_dihitung` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `simpanans`
--

CREATE TABLE `simpanans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `anggota_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_simpanan_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_transaksi` enum('setor','tarik') NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `bukti_transaksi` varchar(255) DEFAULT NULL,
  `status` enum('pending','disetujui','ditolak') NOT NULL DEFAULT 'disetujui',
  `tanggal` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_kas`
--

CREATE TABLE `transaksi_kas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jenis` enum('masuk','keluar') NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `dieksekusi_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','bendahara','anggota') NOT NULL DEFAULT 'anggota',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@koperasi.com', '$2y$12$dh9hlsvT21YaOj0x0GBj3.7bsI/6OedgNe37/lJs03GBymIORLPiG', 'admin', '2026-06-20 08:47:45', '2026-06-20 08:47:45'),
(2, 'Ibu Bendahara', 'bendahara@koperasi.com', '$2y$12$ULpXxa.U3R.2IRVP1fjEcuVsJH8DcB7qfmxMKcv.JR8Iklmd2oEJm', 'bendahara', '2026-06-20 08:47:45', '2026-06-20 08:47:45'),
(3, 'Mutiara', 'Mutiara@gmail.com', '$2y$12$eg53AqS.zv0LG691UiJDRe8.X6R98zsUsqG5LmLC9tRfmWN58T3HG', 'anggota', '2026-06-20 08:47:45', '2026-06-20 09:14:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anggotas`
--
ALTER TABLE `anggotas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `anggotas_no_anggota_unique` (`no_anggota`),
  ADD UNIQUE KEY `anggotas_nik_unique` (`nik`),
  ADD KEY `anggotas_user_id_foreign` (`user_id`);

--
-- Indexes for table `angsurans`
--
ALTER TABLE `angsurans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `angsurans_pinjaman_id_foreign` (`pinjaman_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jenis_simpanans`
--
ALTER TABLE `jenis_simpanans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pengaturan_bunga_pinjamans`
--
ALTER TABLE `pengaturan_bunga_pinjamans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pengaturan_bunga_pinjamans_tenor_unique` (`tenor`);

--
-- Indexes for table `pengaturan_shus`
--
ALTER TABLE `pengaturan_shus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinjamans`
--
ALTER TABLE `pinjamans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pinjamans_anggota_id_foreign` (`anggota_id`),
  ADD KEY `pinjamans_disetujui_oleh_foreign` (`disetujui_oleh`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `shus`
--
ALTER TABLE `shus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shus_anggota_id_foreign` (`anggota_id`);

--
-- Indexes for table `simpanans`
--
ALTER TABLE `simpanans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `simpanans_anggota_id_foreign` (`anggota_id`),
  ADD KEY `simpanans_jenis_simpanan_id_foreign` (`jenis_simpanan_id`);

--
-- Indexes for table `transaksi_kas`
--
ALTER TABLE `transaksi_kas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_kas_dieksekusi_oleh_foreign` (`dieksekusi_oleh`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anggotas`
--
ALTER TABLE `anggotas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `angsurans`
--
ALTER TABLE `angsurans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_simpanans`
--
ALTER TABLE `jenis_simpanans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pengaturan_bunga_pinjamans`
--
ALTER TABLE `pengaturan_bunga_pinjamans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pengaturan_shus`
--
ALTER TABLE `pengaturan_shus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pinjamans`
--
ALTER TABLE `pinjamans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shus`
--
ALTER TABLE `shus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `simpanans`
--
ALTER TABLE `simpanans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi_kas`
--
ALTER TABLE `transaksi_kas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anggotas`
--
ALTER TABLE `anggotas`
  ADD CONSTRAINT `anggotas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `angsurans`
--
ALTER TABLE `angsurans`
  ADD CONSTRAINT `angsurans_pinjaman_id_foreign` FOREIGN KEY (`pinjaman_id`) REFERENCES `pinjamans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pinjamans`
--
ALTER TABLE `pinjamans`
  ADD CONSTRAINT `pinjamans_anggota_id_foreign` FOREIGN KEY (`anggota_id`) REFERENCES `anggotas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pinjamans_disetujui_oleh_foreign` FOREIGN KEY (`disetujui_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `shus`
--
ALTER TABLE `shus`
  ADD CONSTRAINT `shus_anggota_id_foreign` FOREIGN KEY (`anggota_id`) REFERENCES `anggotas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `simpanans`
--
ALTER TABLE `simpanans`
  ADD CONSTRAINT `simpanans_anggota_id_foreign` FOREIGN KEY (`anggota_id`) REFERENCES `anggotas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `simpanans_jenis_simpanan_id_foreign` FOREIGN KEY (`jenis_simpanan_id`) REFERENCES `jenis_simpanans` (`id`);

--
-- Constraints for table `transaksi_kas`
--
ALTER TABLE `transaksi_kas`
  ADD CONSTRAINT `transaksi_kas_dieksekusi_oleh_foreign` FOREIGN KEY (`dieksekusi_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
