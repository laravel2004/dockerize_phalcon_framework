-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Feb 2025 pada 09.28
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `erp_rmi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `activity_setting_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `time_of_work` int(11) DEFAULT NULL,
  `cost` decimal(12,2) DEFAULT NULL,
  `total_cost` decimal(12,2) DEFAULT NULL,
  `total_worker` int(11) DEFAULT NULL,
  `plot_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `activity_log`
--

INSERT INTO `activity_log` (`id`, `activity_setting_id`, `description`, `time_of_work`, `cost`, `total_cost`, `total_worker`, `plot_id`, `image`, `created_at`, `updated_at`, `deleted_at`, `start_date`, `end_date`) VALUES
(25, 4, 'jsdnsj', 1, '10000.00', '10000.00', 1, 2, '[\"uploads\\\\activity_logs\\\\1738306957_channels4_profile.jpg\",\"uploads\\\\activity_logs\\\\1738306957_download (1).jpeg\"]', '2025-01-31 07:02:37', '2025-01-31 07:02:37', NULL, '2025-01-30', '2025-01-30'),
(26, 4, 'kjsdnwj', 1, '200000.00', '200000.00', 1, 4, '[\"uploads\\\\activity_logs\\\\1738307613_channels4_profile.jpg\",\"uploads\\\\activity_logs\\\\1738307613_download (1).jpeg\",\"uploads\\\\activity_logs\\\\1738307613_download.jpeg\"]', '2025-01-31 07:13:33', '2025-01-31 07:21:40', NULL, '2025-01-31', '2025-01-31'),
(27, 3, 'mfsfns', 2, '50000.00', '100000.00', 1, 4, '[\"uploads\\\\activity_logs\\\\1738309584_channels4_profile - Copy.jpg\"]', '2025-01-31 07:46:24', '2025-01-31 07:46:24', NULL, '2025-01-31', '2025-02-01'),
(30, 4, 'test', 3, '45000.00', '135000.00', 1, 4, '[\"uploads\\\\activity_logs\\\\1738310926_channels4_profile - Copy.jpg\",\"uploads\\\\activity_logs\\\\1738310926_channels4_profile.jpg\"]', '2025-01-31 08:08:46', '2025-01-31 08:08:46', NULL, '2025-01-28', '2025-01-31'),
(31, 4, 'test', 5, '45000.00', '225000.00', 1, 4, '[\"uploads\\\\activity_logs\\\\1738311036_screencapture-127-0-0-1-5500-index-html-2025-01-31-01_51_54 - Copy.png\"]', '2025-01-31 08:10:36', '2025-01-31 08:10:36', NULL, '2025-01-28', '2025-02-01'),
(32, 3, 'test', 2, '45000.00', '90000.00', 1, 4, '[\"uploads\\\\activity_logs\\\\1738311196_channels4_profile - Copy.jpg\"]', '2025-01-31 08:13:17', '2025-01-31 08:13:17', NULL, '2025-01-31', '2025-01-31'),
(33, 3, 'Tractor Tebu untuk clearing', 3, '200000.00', '600000.00', 1, 5, '[\"uploads\\\\activity_logs\\\\1738330091_download.png\"]', '2025-01-31 13:28:11', '2025-01-31 13:28:11', NULL, '2025-01-31', '2025-01-31'),
(34, 4, 'test', 5, '82500.00', '1650000.00', 4, 5, '[\"uploads\\\\activity_logs\\\\1738330200_download.png\"]', '2025-01-31 13:30:00', '2025-01-31 13:30:00', NULL, '2025-01-27', '2025-01-31'),
(35, 2, 'test', 3, '82500.00', '495000.00', 2, 5, '[\"uploads\\\\activity_logs\\\\1738330253_channels4_profile - Copy.jpg\",\"uploads\\\\activity_logs\\\\1738330253_download.png\"]', '2025-01-31 13:30:53', '2025-01-31 13:30:53', NULL, '2025-01-27', '2025-01-29'),
(36, 1, 'test', NULL, '100000.00', '100000.00', 1, 5, '[\"uploads\\\\activity_logs\\\\1738330367_channels4_profile - Copy.jpg\",\"uploads\\\\activity_logs\\\\1738330367_channels4_profile.jpg\",\"uploads\\\\activity_logs\\\\1738330367_download (1).jpeg\",\"uploads\\\\activity_logs\\\\1738330367_download.png\"]', '2025-01-31 13:32:47', '2025-01-31 13:32:47', NULL, '2025-01-29', '2025-01-30'),
(37, 1, '2232', NULL, '1200000.00', '1200000.00', 1, 4, '[\"uploads\\\\activity_logs\\\\1738330968_channels4_profile - Copy.jpg\",\"uploads\\\\activity_logs\\\\1738330968_download.png\"]', '2025-01-31 13:42:48', '2025-01-31 13:42:48', NULL, '2025-01-31', '2025-01-31'),
(38, 3, 'sndjsnfjs', 5, '30000.00', '0.00', 0, 7, '[\"uploads\\/activity_logs\\/1738568396_21 jan.jpg\"]', '2025-02-03 07:39:56', '2025-02-03 07:39:56', NULL, '2025-02-03', '2025-02-03'),
(39, 2, 'nksjhfsj', 2, '82500.00', '0.00', 0, 7, '[\"uploads\\/activity_logs\\/1738568437_21 jan.jpg\"]', '2025-02-03 07:40:37', '2025-02-03 07:40:37', NULL, '2025-02-03', '2025-02-04'),
(40, 2, 'test', 4, '82500.00', '660000.00', 2, 4, '[\"uploads\\/activity_logs\\/1738651189_21 jan.jpg\"]', '2025-02-04 06:39:49', '2025-02-04 06:39:49', NULL, '2025-02-01', '2025-02-04'),
(41, 3, 'test', 4, '20000.00', '160000.00', 2, 4, '[\"uploads\\/activity_logs\\/1738651261_21 jan.jpg\"]', '2025-02-04 06:41:01', '2025-02-04 06:41:01', NULL, '2025-02-04', '2025-02-04'),
(42, 2, 'test', 2, '82000.00', '164000.00', 1, 4, '[\"uploads\\/activity_logs\\/1738655419_download.jpeg\"]', '2025-02-04 07:50:19', '2025-02-04 07:50:19', NULL, '2025-02-04', '2025-02-05'),
(43, 3, 'test', 4, '20000.00', '160000.00', 2, 4, '[\"uploads\\/activity_logs\\/1738655509_docker.png\"]', '2025-02-04 07:51:49', '2025-02-04 07:51:49', NULL, '2025-02-04', '2025-02-04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_setting`
--

CREATE TABLE `activity_setting` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('Borongan','Harian','Jam','Vendor') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `activity_setting`
--

INSERT INTO `activity_setting` (`id`, `name`, `description`, `type`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Clearing', 'Sulam tebu dan coklakk', 'Borongan', '2025-01-24 03:16:34', '2025-01-24 03:57:29', NULL),
(2, 'Sulam Tebu', 'Sulam Tebu', 'Harian', '2025-01-24 03:38:46', '2025-01-27 06:42:03', NULL),
(3, 'Tractor', 'sndfsjn', 'Jam', '2025-01-27 06:45:08', '2025-01-27 06:45:08', NULL),
(4, 'Tebang Tebu', 'Test', 'Harian', '2025-01-30 02:09:54', '2025-01-30 02:09:54', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `conversion_uom`
--

CREATE TABLE `conversion_uom` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `master_uom_start` int(11) NOT NULL,
  `master_uom_end` int(11) NOT NULL,
  `conversion` decimal(18,2) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `conversion_uom`
--

INSERT INTO `conversion_uom` (`id`, `master_uom_start`, `master_uom_end`, `conversion`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 9, 7, '40.00', 'Satuan Kilo Gram untuk Pupuk', '2025-01-31 00:44:11', '2025-01-31 00:44:11', NULL),
(2, 9, 7, '50.00', 'test', NULL, NULL, '2025-01-30 18:10:12'),
(3, 9, 7, '50.00', 'Sak to KG [PUPUK]', NULL, NULL, NULL),
(4, 9, 7, '50.00', 'Kg to Sak [PUPUK]', NULL, NULL, '2025-01-31 06:24:01'),
(5, 11, 8, '30.00', 'Galon to Liter [Obat Rumput]', NULL, NULL, NULL),
(6, 11, 8, '15.00', 'Liter to Galon [Obat Rumput]', NULL, NULL, NULL),
(7, 9, 7, '50.00', 'SAK TO KILO', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `material`
--

CREATE TABLE `material` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `stock` decimal(10,2) UNSIGNED NOT NULL,
  `uom` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `material`
--

INSERT INTO `material` (`id`, `name`, `stock`, `uom`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'Pupuk NPK', '1096.06', 'Sak', '2025-01-24 06:56:42', '2025-02-04 07:46:08', NULL),
(3, 'Solimate', '110.00', '9', '2025-01-24 07:06:58', '2025-01-24 07:07:02', '2025-01-24 00:07:02'),
(4, 'Solimate', '151.00', 'Sak', '2025-01-27 04:27:58', '2025-01-31 08:28:16', NULL),
(5, 'Pupuk Urea', '0.00', 'Sak', '2025-01-30 02:06:52', '2025-01-31 01:38:00', NULL),
(6, 'Obat Roundup ', '19.33', 'Galon', '2025-01-31 13:25:14', '2025-02-04 06:30:40', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `plot`
--

CREATE TABLE `plot` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `project_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `plot`
--

INSERT INTO `plot` (`id`, `code`, `project_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '123122', 3, '2025-01-24 01:53:12', '2025-01-24 01:53:12', NULL),
(2, '411', 3, '2025-01-24 02:28:10', '2025-01-24 02:28:10', NULL),
(3, '12111', 3, '2025-01-24 02:30:59', '2025-01-24 02:31:13', '2025-01-23 19:31:13'),
(4, 'E567BZ', 3, '2025-01-27 04:58:27', '2025-01-27 04:58:27', NULL),
(5, '5746', 4, '2025-01-30 02:07:49', '2025-01-30 02:07:49', NULL),
(6, 'T435', 4, '2025-02-03 03:39:59', '2025-02-03 03:39:59', NULL),
(7, 'QE2325', 4, '2025-02-03 03:41:04', '2025-02-03 03:41:04', NULL),
(8, '123QW', 4, '2025-02-03 03:41:04', '2025-02-03 03:41:04', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `project`
--

CREATE TABLE `project` (
  `id` int(11) NOT NULL,
  `project` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `project`
--

INSERT INTO `project` (`id`, `project`, `code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'project', 'project', '2025-01-24 07:49:54', '2025-01-24 08:27:37', '2025-01-24 01:27:37'),
(2, 'test12', 'test12', '2025-01-24 07:53:03', '2025-01-24 08:04:35', '2025-01-24 01:04:35'),
(3, 'Bibit 1', 'B1B17', '2025-01-24 08:30:37', '2025-01-24 08:30:37', NULL),
(4, 'Bibit 3', 'BIBIT3', '2025-01-30 09:07:34', '2025-01-30 09:07:34', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `supporting_material`
--

CREATE TABLE `supporting_material` (
  `id` int(11) UNSIGNED NOT NULL,
  `item_needed` varchar(255) NOT NULL,
  `conversion_of_uom_item` decimal(10,2) NOT NULL,
  `uom` varchar(50) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `plot_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `supporting_material`
--

INSERT INTO `supporting_material` (`id`, `item_needed`, `conversion_of_uom_item`, `uom`, `image`, `plot_id`, `material_id`, `created_at`, `updated_at`, `deleted_at`, `date`) VALUES
(27, '12', '40.00', 'Kilogram', '[\"uploads\\\\activity_logs\\\\1738308634_channels4_profile.jpg\"]', 5, 2, '2025-01-31 07:30:34', '2025-01-31 07:30:46', '2025-01-31 00:30:46', NULL),
(28, '250', '40.00', 'Kilogram', '[\"uploads\\\\activity_logs\\\\1738308663_channels4_profile.jpg\",\"uploads\\\\activity_logs\\\\1738308663_download (1).jpeg\",\"uploads\\\\activity_logs\\\\1738308663_download.jpeg\"]', 4, 4, '2025-01-31 07:31:03', '2025-01-31 08:28:16', '2025-01-31 01:28:16', NULL),
(29, '120', '50.00', 'Kilogram', '[\"uploads\\\\activity_logs\\\\1738311877_channels4_profile - Copy.jpg\"]', 1, 2, '2025-01-31 08:24:38', '2025-02-03 07:16:01', '2025-02-03 00:16:01', NULL),
(30, '250', '50.00', 'Kilogram', '[\"uploads\\\\activity_logs\\\\1738312082_screencapture-127-0-0-1-5500-index-html-2025-01-31-01_51_54 - Copy.png\",\"uploads\\\\activity_logs\\\\1738312082_channels4_profile - Copy.jpg\",\"uploads\\\\activity_logs\\\\1738312082_channels4_profile.jpg\"]', 4, 2, '2025-01-31 08:28:02', '2025-01-31 08:28:02', NULL, '2025-01-31'),
(31, '20', '30.00', 'Liter', '[\"uploads\\\\activity_logs\\\\1738329968_download.jpeg\"]', 5, 6, '2025-01-31 13:26:08', '2025-01-31 13:26:08', NULL, '2025-01-31'),
(32, '15', '15.00', 'Liter', '[\"uploads\\/activity_logs\\/1738568338_21 jan.jpg\"]', 8, 6, '2025-02-03 07:38:58', '2025-02-04 06:30:40', '2025-02-03 23:30:40', '2025-02-03'),
(33, '250', '40.00', 'Kilogram', '[\"uploads\\/activity_logs\\/1738650617_21 jan.jpg\"]', 1, 2, '2025-02-04 06:30:17', '2025-02-04 06:31:25', '2025-02-03 23:31:25', '2025-02-05'),
(34, '122', '40.00', 'Kilogram', '[\"uploads\\/activity_logs\\/1738650668_21 jan.jpg\"]', 1, 2, '2025-02-04 06:31:08', '2025-02-04 06:31:19', '2025-02-03 23:31:19', '2025-02-05'),
(35, '80', '50.00', 'Kilogram', '[\"uploads\\/activity_logs\\/1738655168_nginx.png\"]', 4, 2, '2025-02-04 07:46:08', '2025-02-04 07:46:08', NULL, '2025-02-03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `uom_setting`
--

CREATE TABLE `uom_setting` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `uom_setting`
--

INSERT INTO `uom_setting` (`id`, `name`, `create_at`, `update_at`, `delete_at`) VALUES
(2, 'sbdhs', '2025-01-23 15:42:45', '2025-01-23 16:18:38', '2025-01-23 09:18:38'),
(3, 'nsdjs', '2025-01-23 15:43:27', '2025-02-03 03:09:54', '2025-02-02 20:09:54'),
(5, 'test', '2025-01-23 16:56:15', '2025-01-23 16:56:15', NULL),
(6, 'test1', '2025-01-23 16:56:26', '2025-01-23 16:56:26', NULL),
(7, 'Kilogram', '2025-01-23 16:56:54', '2025-01-23 16:56:54', NULL),
(8, 'Liter', '2025-01-23 16:57:01', '2025-01-23 16:57:01', NULL),
(9, 'Sak', '2025-01-23 16:57:10', '2025-01-23 16:57:10', NULL),
(10, 'Karton', '2025-01-23 16:57:19', '2025-01-23 16:57:19', NULL),
(11, 'Galon', '2025-01-23 16:57:32', '2025-01-23 16:57:32', NULL),
(12, 'Kodi', '2025-01-23 16:58:17', '2025-01-23 16:58:17', NULL),
(13, 'Rim', '2025-01-23 16:58:24', '2025-01-23 16:58:24', NULL),
(14, 'Bal', '2025-01-23 16:58:33', '2025-01-23 16:58:33', NULL),
(15, 'dankda', '2025-01-24 00:39:55', '2025-01-24 00:39:55', NULL),
(16, 'test', '2025-01-24 00:40:40', '2025-01-24 00:40:40', NULL),
(17, 'dns', '2025-01-24 00:42:20', '2025-01-24 00:42:20', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `worker`
--

CREATE TABLE `worker` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `activity_log_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `worker`
--

INSERT INTO `worker` (`id`, `name`, `activity_log_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(35, 'test', 25, '2025-01-31 07:02:37', '2025-01-31 07:02:37', NULL),
(36, 'test', 26, '2025-01-31 07:13:33', '2025-01-31 07:13:33', NULL),
(37, 'test', 27, '2025-01-31 07:46:24', '2025-01-31 07:46:24', NULL),
(39, 'test', 30, '2025-01-31 08:08:46', '2025-01-31 08:08:46', NULL),
(40, 'sdns', 31, '2025-01-31 08:10:36', '2025-01-31 08:10:36', NULL),
(41, 'name', 32, '2025-01-31 08:13:17', '2025-01-31 08:13:17', NULL),
(42, 'Supriadi', 33, '2025-01-31 13:28:11', '2025-01-31 13:28:11', NULL),
(43, 'Suparto', 34, '2025-01-31 13:30:00', '2025-01-31 13:30:00', NULL),
(44, 'Mesiam', 34, '2025-01-31 13:30:00', '2025-01-31 13:30:00', NULL),
(45, 'Kul Kul', 34, '2025-01-31 13:30:00', '2025-01-31 13:30:00', NULL),
(46, 'Marjan', 34, '2025-01-31 13:30:00', '2025-01-31 13:30:00', NULL),
(47, 'Mukdi', 35, '2025-01-31 13:30:53', '2025-01-31 13:30:53', NULL),
(48, 'Mala', 35, '2025-01-31 13:30:53', '2025-01-31 13:30:53', NULL),
(49, 'Mursid', 36, '2025-01-31 13:32:47', '2025-01-31 13:32:47', NULL),
(50, 'Suliadi', 37, '2025-01-31 13:42:48', '2025-01-31 13:42:48', NULL),
(51, 'Mulia', 40, '2025-02-04 06:39:49', '2025-02-04 06:39:49', NULL),
(52, 'Abadi', 40, '2025-02-04 06:39:49', '2025-02-04 06:39:49', NULL),
(53, 'Suliadi', 41, '2025-02-04 06:41:01', '2025-02-04 06:41:01', NULL),
(54, 'Ari', 41, '2025-02-04 06:41:01', '2025-02-04 06:41:01', NULL),
(55, 'SUli', 42, '2025-02-04 07:50:19', '2025-02-04 07:50:19', NULL),
(56, 'fgsca', 43, '2025-02-04 07:51:49', '2025-02-04 07:51:49', NULL),
(57, 'njsbdjs', 43, '2025-02-04 07:51:49', '2025-02-04 07:51:49', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_setting_id` (`activity_setting_id`),
  ADD KEY `plot_id` (`plot_id`);

--
-- Indeks untuk tabel `activity_setting`
--
ALTER TABLE `activity_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `conversion_uom`
--
ALTER TABLE `conversion_uom`
  ADD PRIMARY KEY (`id`),
  ADD KEY `master_uom_start` (`master_uom_start`),
  ADD KEY `master_uom_end` (`master_uom_end`);

--
-- Indeks untuk tabel `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `plot`
--
ALTER TABLE `plot`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indeks untuk tabel `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `supporting_material`
--
ALTER TABLE `supporting_material`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_plot` (`plot_id`),
  ADD KEY `fk_material` (`material_id`);

--
-- Indeks untuk tabel `uom_setting`
--
ALTER TABLE `uom_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `worker`
--
ALTER TABLE `worker`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_log_id` (`activity_log_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT untuk tabel `activity_setting`
--
ALTER TABLE `activity_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `conversion_uom`
--
ALTER TABLE `conversion_uom`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `material`
--
ALTER TABLE `material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `plot`
--
ALTER TABLE `plot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `supporting_material`
--
ALTER TABLE `supporting_material`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `uom_setting`
--
ALTER TABLE `uom_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `worker`
--
ALTER TABLE `worker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`activity_setting_id`) REFERENCES `activity_setting` (`id`),
  ADD CONSTRAINT `activity_log_ibfk_2` FOREIGN KEY (`plot_id`) REFERENCES `plot` (`id`);

--
-- Ketidakleluasaan untuk tabel `conversion_uom`
--
ALTER TABLE `conversion_uom`
  ADD CONSTRAINT `conversion_uom_ibfk_1` FOREIGN KEY (`master_uom_start`) REFERENCES `uom_setting` (`id`),
  ADD CONSTRAINT `conversion_uom_ibfk_2` FOREIGN KEY (`master_uom_end`) REFERENCES `uom_setting` (`id`);

--
-- Ketidakleluasaan untuk tabel `plot`
--
ALTER TABLE `plot`
  ADD CONSTRAINT `plot_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `supporting_material`
--
ALTER TABLE `supporting_material`
  ADD CONSTRAINT `fk_material` FOREIGN KEY (`material_id`) REFERENCES `material` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_plot` FOREIGN KEY (`plot_id`) REFERENCES `plot` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `worker`
--
ALTER TABLE `worker`
  ADD CONSTRAINT `worker_ibfk_1` FOREIGN KEY (`activity_log_id`) REFERENCES `activity_log` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
