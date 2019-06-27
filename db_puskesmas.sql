-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 17 Jun 2019 pada 04.26
-- Versi server: 10.1.32-MariaDB
-- Versi PHP: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_puskesmas`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_berita`
--

CREATE TABLE `tb_berita` (
  `id_berita` int(10) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(50) NOT NULL,
  `cover` text,
  `content` text NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_berita_komen`
--

CREATE TABLE `tb_berita_komen` (
  `id_komen` int(10) UNSIGNED NOT NULL,
  `id_berita` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_bpjs`
--

CREATE TABLE `tb_bpjs` (
  `id_bpjs` int(10) UNSIGNED NOT NULL,
  `name_bpjs` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_bpjs`
--

INSERT INTO `tb_bpjs` (`id_bpjs`, `name_bpjs`) VALUES
(1, 'Tidak Ada'),
(2, 'BPJS PBI'),
(3, 'BPJS NON PBI'),
(4, 'UMUM'),
(5, 'LAINNYA');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_bpjs_pasien`
--

CREATE TABLE `tb_bpjs_pasien` (
  `id_bpjs_pasien` int(10) UNSIGNED NOT NULL,
  `id_bpjs` int(10) UNSIGNED NOT NULL,
  `id_pasien` varchar(50) NOT NULL,
  `no_bpjs` varchar(50) NOT NULL DEFAULT '-'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_darah`
--

CREATE TABLE `tb_darah` (
  `id_darah` int(10) UNSIGNED NOT NULL,
  `name_darah` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_darah`
--

INSERT INTO `tb_darah` (`id_darah`, `name_darah`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'AB'),
(4, 'O');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_dokter`
--

CREATE TABLE `tb_dokter` (
  `id_dokter` varchar(50) NOT NULL,
  `nip` varchar(18) NOT NULL,
  `code_spesialis` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `sex` enum('P','W') NOT NULL,
  `address` text NOT NULL,
  `telp` varchar(15) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_jadwal`
--

CREATE TABLE `tb_jadwal` (
  `id_jadwal` int(10) UNSIGNED NOT NULL,
  `id_dokter` varchar(50) NOT NULL,
  `id_poli` int(10) UNSIGNED NOT NULL,
  `jadwal` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_menu`
--

CREATE TABLE `tb_menu` (
  `id_menu` int(10) UNSIGNED NOT NULL,
  `name_menu` varchar(50) NOT NULL,
  `used` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_menu`
--

INSERT INTO `tb_menu` (`id_menu`, `name_menu`, `used`) VALUES
(1, 'Visi Misi', '0'),
(2, 'Struktur Organisasi', '0'),
(3, 'Tentang', '0'),
(4, 'Jenis Pelayanan', '0'),
(5, 'Fasilitas Pelayanan', '0'),
(6, 'Kontak', '0');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_obat`
--

CREATE TABLE `tb_obat` (
  `code_obat` varchar(50) NOT NULL,
  `id_unit` int(10) UNSIGNED NOT NULL,
  `name_obat` varchar(50) NOT NULL,
  `stock` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `price_buy` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `price_sale` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pasien`
--

CREATE TABLE `tb_pasien` (
  `id_pasien` varchar(50) NOT NULL,
  `kk` varchar(16) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `name` varchar(50) NOT NULL,
  `place_born` varchar(50) DEFAULT NULL,
  `date_born` varchar(11) NOT NULL DEFAULT '01/01/1970',
  `sex` enum('P','W') NOT NULL,
  `id_darah` int(10) UNSIGNED NOT NULL,
  `religion` varchar(50) NOT NULL DEFAULT '-',
  `worker` varchar(50) NOT NULL DEFAULT '-',
  `address` text NOT NULL,
  `telp` varchar(15) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_permissions`
--

CREATE TABLE `tb_permissions` (
  `id_permission` int(10) UNSIGNED NOT NULL,
  `name_permission` varchar(50) NOT NULL,
  `display_permission` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_permissions`
--

INSERT INTO `tb_permissions` (`id_permission`, `name_permission`, `display_permission`) VALUES
(1, 'create_dokter', 'Create Dokter'),
(2, 'read_dokter', 'Read Dokter'),
(3, 'update_dokter', 'Update Dokter'),
(4, 'delete_dokter', 'Delete Dokter'),
(5, 'create_pasien', 'Create Pasien'),
(6, 'read_pasien', 'Read Pasien'),
(7, 'update_pasien', 'Update Pasien'),
(8, 'delete_pasien', 'Delete Pasien'),
(9, 'create_staff', 'Create Staff'),
(10, 'read_staff', 'Read Staff'),
(11, 'update_staff', 'Update Staff'),
(12, 'delete_staff', 'Delete Staff'),
(13, 'create_obat', 'Create Obat'),
(14, 'read_obat', 'Read Obat'),
(15, 'update_obat', 'Update Obat'),
(16, 'delete_obat', 'Delete Obat'),
(17, 'create_satuan', 'Create Satuan'),
(18, 'read_satuan', 'Read Satuan'),
(19, 'update_satuan', 'Update Satuan'),
(20, 'delete_satuan', 'Delete Satuan'),
(21, 'create_berita', 'Create Berita'),
(22, 'read_berita', 'Read Berita'),
(23, 'update_berita', 'Update Berita'),
(24, 'delete_berita', 'Delete Berita'),
(25, 'create_poli', 'Create Poli'),
(26, 'read_poli', 'Read Poli'),
(27, 'update_poli', 'Update Poli'),
(28, 'delete_poli', 'Delete Poli'),
(29, 'create_darah', 'Create Darah'),
(30, 'read_darah', 'Read Darah'),
(31, 'update_darah', 'Update Darah'),
(32, 'delete_darah', 'Delete Darah'),
(33, 'create_spesialis', 'Create Spesialis'),
(34, 'read_spesialis', 'Read Spesialis'),
(35, 'update_spesialis', 'Update Spesialis'),
(36, 'delete_spesialis', 'Delete Spesialis'),
(37, 'create_jadwal', 'Create Jadwal'),
(38, 'read_jadwal', 'Read Jadwal'),
(39, 'update_jadwal', 'Update Jadwal'),
(40, 'delete_jadwal', 'Delete Jadwal'),
(41, 'create_resep', 'Create Resep'),
(42, 'read_resep', 'Read Resep'),
(43, 'update_resep', 'Update Resep'),
(44, 'delete_resep', 'Delete Resep'),
(45, 'create_rawat_jalan', 'Create Rawat Jalan'),
(46, 'read_rawat_jalan', 'Read Rawat Jalan'),
(47, 'update_rawat_jalan', 'Update Rawat Jalan'),
(48, 'delete_rawat_jalan', 'Delete Rawat Jalan'),
(49, 'create_rekam_medis', 'Create Rekam Medis'),
(50, 'read_rekam_medis', 'Read Rekam Medis'),
(51, 'update_rekam_medis', 'Update Rekam Medis'),
(52, 'delete_rekam_medis', 'Delete Rekam Medis'),
(53, 'create_pembayaran', 'Create Pembayaran'),
(54, 'read_pembayaran', 'Read Pembayaran'),
(55, 'update_pembayaran', 'Update Pembayaran'),
(56, 'delete_pembayaran', 'Delete Pembayaran'),
(57, 'create_sistem', 'Create Sistem'),
(58, 'read_sistem', 'Read Sistem'),
(59, 'update_sistem', 'Update Sistem'),
(60, 'delete_sistem', 'Delete Sistem'),
(61, 'menu_user', 'Menu User'),
(62, 'submenu_dokter', 'Submenu Dokter'),
(63, 'submenu_pasien', 'Submenu Pasien'),
(64, 'submenu_staff', 'Submenu Staff'),
(65, 'menu_obat', 'Menu Obat'),
(66, 'submenu_obat', 'Submenu Obat'),
(67, 'submenu_satuan', 'Submenu Satuan'),
(68, 'menu_puskesmas', 'Menu Puskesmas'),
(69, 'submenu_berita', 'Submenu Berita'),
(70, 'submenu_poli', 'Submenu Poli'),
(71, 'submenu_darah', 'Submenu Darah'),
(72, 'submenu_spesialis', 'Submenu Spesialis'),
(73, 'submenu_jadwal', 'Submenu Jadwal'),
(74, 'submenu_resep', 'Submenu Resep'),
(75, 'submenu_rekam_medis', 'Submenu Rekam Medis'),
(76, 'submenu_rawat_jalan', 'Submenu Rawat Jalan'),
(77, 'submenu_pembayaran', 'Submenu Pembayaran'),
(78, 'submenu_balai_pengobatan', 'Submenu Balai Pengobatan'),
(79, 'menu_konfigurasi', 'Menu Konfigurasi'),
(80, 'submenu_beranda', 'Submenu beranda');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_poli`
--

CREATE TABLE `tb_poli` (
  `id_poli` int(10) UNSIGNED NOT NULL,
  `name_poli` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_rawat_jalan`
--

CREATE TABLE `tb_rawat_jalan` (
  `id_rawat_jalan` int(10) UNSIGNED NOT NULL,
  `id_pasien` varchar(50) NOT NULL,
  `id_dokter` varchar(50) NOT NULL,
  `code_resep` varchar(50) NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_rekam_medis`
--

CREATE TABLE `tb_rekam_medis` (
  `id_rekam_medis` int(10) UNSIGNED NOT NULL,
  `id_pasien` varchar(50) NOT NULL,
  `id_dokter` varchar(50) NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_resep`
--

CREATE TABLE `tb_resep` (
  `code_resep` varchar(50) NOT NULL,
  `id_pasien` varchar(50) NOT NULL,
  `id_dokter` varchar(50) NOT NULL,
  `total` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_resep_detail`
--

CREATE TABLE `tb_resep_detail` (
  `id_resep_detail` int(10) UNSIGNED NOT NULL,
  `code_resep` varchar(50) NOT NULL,
  `code_obat` varchar(50) NOT NULL,
  `amount` int(3) NOT NULL DEFAULT '0',
  `subtotal` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `anjuran` varchar(50) NOT NULL DEFAULT '-'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_rules`
--

CREATE TABLE `tb_rules` (
  `id_rule` int(10) UNSIGNED NOT NULL,
  `name_rule` varchar(50) NOT NULL,
  `display_rule` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_rules`
--

INSERT INTO `tb_rules` (`id_rule`, `name_rule`, `display_rule`) VALUES
(1, 'admin', 'Administrator'),
(2, 'tata_usaha', 'Tata Usaha'),
(3, 'pimpinan', 'Pimpinan'),
(4, 'bendahara', 'Bendahara'),
(5, 'apotik', 'Apotik'),
(6, 'dokter', 'Dokter'),
(7, 'pasien', 'Pasien');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_rule_permission`
--

CREATE TABLE `tb_rule_permission` (
  `id_rule` int(10) UNSIGNED NOT NULL,
  `id_permission` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_rule_permission`
--

INSERT INTO `tb_rule_permission` (`id_rule`, `id_permission`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 21),
(1, 22),
(1, 23),
(1, 24),
(1, 25),
(1, 26),
(1, 27),
(1, 28),
(1, 29),
(1, 30),
(1, 31),
(1, 32),
(1, 33),
(1, 34),
(1, 35),
(1, 36),
(1, 37),
(1, 38),
(1, 39),
(1, 40),
(1, 41),
(1, 42),
(1, 43),
(1, 44),
(1, 45),
(1, 46),
(1, 47),
(1, 48),
(1, 49),
(1, 50),
(1, 51),
(1, 52),
(1, 53),
(1, 54),
(1, 55),
(1, 56),
(1, 57),
(1, 58),
(1, 59),
(1, 60),
(1, 61),
(1, 62),
(1, 63),
(1, 64),
(1, 65),
(1, 66),
(1, 67),
(1, 68),
(1, 69),
(1, 70),
(1, 71),
(1, 72),
(1, 73),
(1, 74),
(1, 75),
(1, 76),
(1, 77),
(1, 78),
(1, 79),
(1, 80),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 5),
(2, 6),
(2, 7),
(2, 8),
(2, 9),
(2, 10),
(2, 11),
(2, 12),
(2, 13),
(2, 14),
(2, 15),
(2, 16),
(2, 17),
(2, 18),
(2, 19),
(2, 20),
(2, 21),
(2, 22),
(2, 23),
(2, 24),
(2, 25),
(2, 26),
(2, 27),
(2, 28),
(2, 29),
(2, 30),
(2, 31),
(2, 32),
(2, 33),
(2, 34),
(2, 35),
(2, 36),
(2, 37),
(2, 38),
(2, 39),
(2, 40),
(2, 41),
(2, 42),
(2, 43),
(2, 44),
(2, 45),
(2, 46),
(2, 47),
(2, 48),
(2, 49),
(2, 50),
(2, 51),
(2, 52),
(2, 53),
(2, 54),
(2, 55),
(2, 56),
(2, 57),
(2, 58),
(2, 59),
(2, 60),
(2, 61),
(2, 62),
(2, 63),
(2, 64),
(2, 65),
(2, 66),
(2, 67),
(2, 68),
(2, 69),
(2, 70),
(2, 71),
(2, 72),
(2, 73),
(2, 74),
(2, 75),
(2, 76),
(2, 77),
(2, 78),
(2, 79),
(2, 80),
(3, 14),
(3, 46),
(3, 65),
(3, 66),
(3, 68),
(3, 76),
(5, 13),
(5, 14),
(5, 15),
(5, 16),
(5, 65),
(5, 66),
(5, 68),
(5, 74),
(6, 6),
(6, 41),
(6, 42),
(6, 43),
(6, 44),
(6, 45),
(6, 46),
(6, 47),
(6, 48),
(6, 49),
(6, 50),
(6, 51),
(6, 52);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_rule_user`
--

CREATE TABLE `tb_rule_user` (
  `id_rule` int(10) UNSIGNED NOT NULL,
  `id_user` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_rule_user`
--

INSERT INTO `tb_rule_user` (`id_rule`, `id_user`) VALUES
(1, '635cf52a-f2e4-4a9b-985d-0b684a9eec0f'),
(3, 'c9cf5d42-7b7e-45e3-9ee9-6e5d3457dc31'),
(5, 'aecec937-71e8-4447-b323-c8e0c2bef889');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_spesialis`
--

CREATE TABLE `tb_spesialis` (
  `code_spesialis` varchar(50) NOT NULL,
  `name_spesialis` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_staff`
--

CREATE TABLE `tb_staff` (
  `id_staff` varchar(50) NOT NULL,
  `nip` varchar(18) NOT NULL,
  `name` varchar(50) NOT NULL,
  `sex` enum('P','W') NOT NULL,
  `address` text NOT NULL,
  `telp` varchar(15) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_staff`
--

INSERT INTO `tb_staff` (`id_staff`, `nip`, `name`, `sex`, `address`, `telp`, `created_at`, `updated_at`) VALUES
('a74faaba-34fd-4042-9944-b31389fb03b7', '123456789012345679', 'Pimpinan Puskesmas', 'P', 'Palembang Sumsel', '081234567890', '2019-06-08 07:46:21', '2019-06-08 07:46:21'),
('d50be420-cb2a-4819-b0b9-27f397ac74f9', '123456789012345677', 'Apotik', 'W', 'Palembang sumsel', '081234567890', '2019-06-08 07:49:37', '2019-06-08 07:49:37'),
('d84b2bb5-5330-4f50-aab7-8930a60ed6a3', '123456789012345678', 'Superadmin', 'P', 'Palembang Sumsel', '081234567890', '2019-05-31 17:00:00', '2019-06-16 03:37:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_unit`
--

CREATE TABLE `tb_unit` (
  `id_unit` int(10) UNSIGNED NOT NULL,
  `name_unit` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_unit`
--

INSERT INTO `tb_unit` (`id_unit`, `name_unit`) VALUES
(1, 'Unit'),
(2, 'Set');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_users`
--

CREATE TABLE `tb_users` (
  `id_user` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_users`
--

INSERT INTO `tb_users` (`id_user`, `username`, `password`, `status`, `created_at`, `updated_at`) VALUES
('635cf52a-f2e4-4a9b-985d-0b684a9eec0f', 'superadmin', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', '1', '2019-05-31 17:00:00', '2019-06-17 02:25:20'),
('aecec937-71e8-4447-b323-c8e0c2bef889', 'apotik_1', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', '1', '2019-06-08 07:49:37', '2019-06-08 07:49:37'),
('c9cf5d42-7b7e-45e3-9ee9-6e5d3457dc31', 'pimpinan_puskesmas', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', '1', '2019-06-08 07:46:21', '2019-06-08 07:46:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_users_dokter`
--

CREATE TABLE `tb_users_dokter` (
  `id_user` varchar(50) NOT NULL,
  `id_dokter` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_users_pasien`
--

CREATE TABLE `tb_users_pasien` (
  `id_user` varchar(50) NOT NULL,
  `id_pasien` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_users_staff`
--

CREATE TABLE `tb_users_staff` (
  `id_user` varchar(50) NOT NULL,
  `id_staff` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_users_staff`
--

INSERT INTO `tb_users_staff` (`id_user`, `id_staff`) VALUES
('635cf52a-f2e4-4a9b-985d-0b684a9eec0f', 'd84b2bb5-5330-4f50-aab7-8930a60ed6a3'),
('aecec937-71e8-4447-b323-c8e0c2bef889', 'd50be420-cb2a-4819-b0b9-27f397ac74f9'),
('c9cf5d42-7b7e-45e3-9ee9-6e5d3457dc31', 'a74faaba-34fd-4042-9944-b31389fb03b7');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_view_menu`
--

CREATE TABLE `tb_view_menu` (
  `id_view_menu` int(10) UNSIGNED NOT NULL,
  `id_menu` int(10) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_berita`
--
ALTER TABLE `tb_berita`
  ADD PRIMARY KEY (`id_berita`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indeks untuk tabel `tb_berita_komen`
--
ALTER TABLE `tb_berita_komen`
  ADD PRIMARY KEY (`id_komen`),
  ADD KEY `id_berita` (`id_berita`);

--
-- Indeks untuk tabel `tb_bpjs`
--
ALTER TABLE `tb_bpjs`
  ADD PRIMARY KEY (`id_bpjs`);

--
-- Indeks untuk tabel `tb_bpjs_pasien`
--
ALTER TABLE `tb_bpjs_pasien`
  ADD PRIMARY KEY (`id_bpjs_pasien`),
  ADD KEY `id_bpjs` (`id_bpjs`),
  ADD KEY `id_pasein` (`id_pasien`);

--
-- Indeks untuk tabel `tb_darah`
--
ALTER TABLE `tb_darah`
  ADD PRIMARY KEY (`id_darah`);

--
-- Indeks untuk tabel `tb_dokter`
--
ALTER TABLE `tb_dokter`
  ADD PRIMARY KEY (`id_dokter`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD KEY `code_spesialis` (`code_spesialis`);

--
-- Indeks untuk tabel `tb_jadwal`
--
ALTER TABLE `tb_jadwal`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_dokter` (`id_dokter`),
  ADD KEY `id_poli` (`id_poli`);

--
-- Indeks untuk tabel `tb_menu`
--
ALTER TABLE `tb_menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indeks untuk tabel `tb_obat`
--
ALTER TABLE `tb_obat`
  ADD PRIMARY KEY (`code_obat`),
  ADD KEY `id_unit` (`id_unit`);

--
-- Indeks untuk tabel `tb_pasien`
--
ALTER TABLE `tb_pasien`
  ADD PRIMARY KEY (`id_pasien`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD KEY `id_darah` (`id_darah`);

--
-- Indeks untuk tabel `tb_permissions`
--
ALTER TABLE `tb_permissions`
  ADD PRIMARY KEY (`id_permission`);

--
-- Indeks untuk tabel `tb_poli`
--
ALTER TABLE `tb_poli`
  ADD PRIMARY KEY (`id_poli`);

--
-- Indeks untuk tabel `tb_rawat_jalan`
--
ALTER TABLE `tb_rawat_jalan`
  ADD PRIMARY KEY (`id_rawat_jalan`),
  ADD KEY `id_pasien` (`id_pasien`),
  ADD KEY `id_dokter` (`id_dokter`),
  ADD KEY `code_resep` (`code_resep`);

--
-- Indeks untuk tabel `tb_rekam_medis`
--
ALTER TABLE `tb_rekam_medis`
  ADD PRIMARY KEY (`id_rekam_medis`),
  ADD KEY `id_pasien` (`id_pasien`),
  ADD KEY `id_dokter` (`id_dokter`);

--
-- Indeks untuk tabel `tb_resep`
--
ALTER TABLE `tb_resep`
  ADD PRIMARY KEY (`code_resep`),
  ADD KEY `id_pasien` (`id_pasien`),
  ADD KEY `id_dokter` (`id_dokter`);

--
-- Indeks untuk tabel `tb_resep_detail`
--
ALTER TABLE `tb_resep_detail`
  ADD PRIMARY KEY (`id_resep_detail`),
  ADD KEY `code_resep` (`code_resep`),
  ADD KEY `code_obat` (`code_obat`);

--
-- Indeks untuk tabel `tb_rules`
--
ALTER TABLE `tb_rules`
  ADD PRIMARY KEY (`id_rule`);

--
-- Indeks untuk tabel `tb_rule_permission`
--
ALTER TABLE `tb_rule_permission`
  ADD PRIMARY KEY (`id_rule`,`id_permission`),
  ADD KEY `id_permission` (`id_permission`);

--
-- Indeks untuk tabel `tb_rule_user`
--
ALTER TABLE `tb_rule_user`
  ADD PRIMARY KEY (`id_rule`,`id_user`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `tb_spesialis`
--
ALTER TABLE `tb_spesialis`
  ADD PRIMARY KEY (`code_spesialis`);

--
-- Indeks untuk tabel `tb_staff`
--
ALTER TABLE `tb_staff`
  ADD PRIMARY KEY (`id_staff`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- Indeks untuk tabel `tb_unit`
--
ALTER TABLE `tb_unit`
  ADD PRIMARY KEY (`id_unit`);

--
-- Indeks untuk tabel `tb_users`
--
ALTER TABLE `tb_users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `tb_users_dokter`
--
ALTER TABLE `tb_users_dokter`
  ADD PRIMARY KEY (`id_user`,`id_dokter`),
  ADD KEY `id_dokter` (`id_dokter`);

--
-- Indeks untuk tabel `tb_users_pasien`
--
ALTER TABLE `tb_users_pasien`
  ADD PRIMARY KEY (`id_user`,`id_pasien`),
  ADD KEY `id_pasien` (`id_pasien`);

--
-- Indeks untuk tabel `tb_users_staff`
--
ALTER TABLE `tb_users_staff`
  ADD PRIMARY KEY (`id_user`,`id_staff`),
  ADD KEY `id_staff` (`id_staff`);

--
-- Indeks untuk tabel `tb_view_menu`
--
ALTER TABLE `tb_view_menu`
  ADD PRIMARY KEY (`id_view_menu`),
  ADD KEY `id_menu` (`id_menu`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_berita`
--
ALTER TABLE `tb_berita`
  MODIFY `id_berita` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_berita_komen`
--
ALTER TABLE `tb_berita_komen`
  MODIFY `id_komen` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_bpjs`
--
ALTER TABLE `tb_bpjs`
  MODIFY `id_bpjs` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tb_bpjs_pasien`
--
ALTER TABLE `tb_bpjs_pasien`
  MODIFY `id_bpjs_pasien` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_darah`
--
ALTER TABLE `tb_darah`
  MODIFY `id_darah` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tb_jadwal`
--
ALTER TABLE `tb_jadwal`
  MODIFY `id_jadwal` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_menu`
--
ALTER TABLE `tb_menu`
  MODIFY `id_menu` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `tb_permissions`
--
ALTER TABLE `tb_permissions`
  MODIFY `id_permission` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT untuk tabel `tb_poli`
--
ALTER TABLE `tb_poli`
  MODIFY `id_poli` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_rawat_jalan`
--
ALTER TABLE `tb_rawat_jalan`
  MODIFY `id_rawat_jalan` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_rekam_medis`
--
ALTER TABLE `tb_rekam_medis`
  MODIFY `id_rekam_medis` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_resep_detail`
--
ALTER TABLE `tb_resep_detail`
  MODIFY `id_resep_detail` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_rules`
--
ALTER TABLE `tb_rules`
  MODIFY `id_rule` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `tb_unit`
--
ALTER TABLE `tb_unit`
  MODIFY `id_unit` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tb_view_menu`
--
ALTER TABLE `tb_view_menu`
  MODIFY `id_view_menu` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tb_berita_komen`
--
ALTER TABLE `tb_berita_komen`
  ADD CONSTRAINT `tb_berita_komen_ibfk_1` FOREIGN KEY (`id_berita`) REFERENCES `tb_berita` (`id_berita`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_bpjs_pasien`
--
ALTER TABLE `tb_bpjs_pasien`
  ADD CONSTRAINT `tb_bpjs_pasien_ibfk_1` FOREIGN KEY (`id_bpjs`) REFERENCES `tb_bpjs` (`id_bpjs`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_bpjs_pasien_ibfk_2` FOREIGN KEY (`id_pasien`) REFERENCES `tb_pasien` (`id_pasien`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_dokter`
--
ALTER TABLE `tb_dokter`
  ADD CONSTRAINT `tb_dokter_ibfk_1` FOREIGN KEY (`code_spesialis`) REFERENCES `tb_spesialis` (`code_spesialis`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_jadwal`
--
ALTER TABLE `tb_jadwal`
  ADD CONSTRAINT `tb_jadwal_ibfk_1` FOREIGN KEY (`id_dokter`) REFERENCES `tb_dokter` (`id_dokter`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_jadwal_ibfk_2` FOREIGN KEY (`id_poli`) REFERENCES `tb_poli` (`id_poli`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_obat`
--
ALTER TABLE `tb_obat`
  ADD CONSTRAINT `tb_obat_ibfk_1` FOREIGN KEY (`id_unit`) REFERENCES `tb_unit` (`id_unit`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_pasien`
--
ALTER TABLE `tb_pasien`
  ADD CONSTRAINT `tb_pasien_ibfk_1` FOREIGN KEY (`id_darah`) REFERENCES `tb_darah` (`id_darah`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_rawat_jalan`
--
ALTER TABLE `tb_rawat_jalan`
  ADD CONSTRAINT `tb_rawat_jalan_ibfk_1` FOREIGN KEY (`id_pasien`) REFERENCES `tb_pasien` (`id_pasien`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_rawat_jalan_ibfk_2` FOREIGN KEY (`id_dokter`) REFERENCES `tb_dokter` (`id_dokter`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_rawat_jalan_ibfk_3` FOREIGN KEY (`code_resep`) REFERENCES `tb_resep` (`code_resep`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_rekam_medis`
--
ALTER TABLE `tb_rekam_medis`
  ADD CONSTRAINT `tb_rekam_medis_ibfk_1` FOREIGN KEY (`id_pasien`) REFERENCES `tb_pasien` (`id_pasien`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_rekam_medis_ibfk_2` FOREIGN KEY (`id_dokter`) REFERENCES `tb_dokter` (`id_dokter`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_resep`
--
ALTER TABLE `tb_resep`
  ADD CONSTRAINT `tb_resep_ibfk_1` FOREIGN KEY (`id_pasien`) REFERENCES `tb_pasien` (`id_pasien`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_resep_ibfk_2` FOREIGN KEY (`id_dokter`) REFERENCES `tb_dokter` (`id_dokter`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_resep_detail`
--
ALTER TABLE `tb_resep_detail`
  ADD CONSTRAINT `tb_resep_detail_ibfk_1` FOREIGN KEY (`code_resep`) REFERENCES `tb_resep` (`code_resep`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_resep_detail_ibfk_2` FOREIGN KEY (`code_obat`) REFERENCES `tb_obat` (`code_obat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_rule_permission`
--
ALTER TABLE `tb_rule_permission`
  ADD CONSTRAINT `tb_rule_permission_ibfk_1` FOREIGN KEY (`id_rule`) REFERENCES `tb_rules` (`id_rule`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_rule_permission_ibfk_2` FOREIGN KEY (`id_permission`) REFERENCES `tb_permissions` (`id_permission`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_rule_user`
--
ALTER TABLE `tb_rule_user`
  ADD CONSTRAINT `tb_rule_user_ibfk_1` FOREIGN KEY (`id_rule`) REFERENCES `tb_rules` (`id_rule`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_rule_user_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `tb_users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_users_dokter`
--
ALTER TABLE `tb_users_dokter`
  ADD CONSTRAINT `tb_users_dokter_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_users_dokter_ibfk_2` FOREIGN KEY (`id_dokter`) REFERENCES `tb_dokter` (`id_dokter`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_users_pasien`
--
ALTER TABLE `tb_users_pasien`
  ADD CONSTRAINT `tb_users_pasien_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_users_pasien_ibfk_2` FOREIGN KEY (`id_pasien`) REFERENCES `tb_pasien` (`id_pasien`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_users_staff`
--
ALTER TABLE `tb_users_staff`
  ADD CONSTRAINT `tb_users_staff_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_users_staff_ibfk_2` FOREIGN KEY (`id_staff`) REFERENCES `tb_staff` (`id_staff`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_view_menu`
--
ALTER TABLE `tb_view_menu`
  ADD CONSTRAINT `tb_view_menu_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `tb_menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
