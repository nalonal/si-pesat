-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 22, 2018 at 08:02 AM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e-inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(3) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile1` varchar(15) NOT NULL,
  `mobile2` varchar(15) NOT NULL,
  `password` char(60) NOT NULL,
  `role` char(5) NOT NULL,
  `created_on` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `last_seen` datetime NOT NULL,
  `last_edited` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `account_status` char(1) NOT NULL DEFAULT '1',
  `deleted` char(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `first_name`, `last_name`, `email`, `mobile1`, `mobile2`, `password`, `role`, `created_on`, `last_login`, `last_seen`, `last_edited`, `account_status`, `deleted`) VALUES
(3, 'Admin', 'Bmn', 'admin@bmn.com', '123456789011', '123456789012', '$2y$10$KA3moRaT11dIZZj/WjXuYeeUOdwVV4zMZ40/pojj5MaNlYJlus0WW', 'Super', '2018-09-14 19:05:51', '2018-10-20 09:47:48', '2018-10-20 09:43:31', '2018-10-20 02:47:48', '1', '0'),
(4, 'Administrator', 'Web', 'web@bmn.com', '081234567890', '081234567980', '$2y$10$ybX0Ltp/bP7iJmIh41ToCeZ10jjb8y5.buoY8rdISsivFV2uDpQEW', 'Super', '2018-10-20 09:34:34', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2018-10-20 02:46:39', '0', '1'),
(5, 'New', 'Admin', 'new@bmn.com', '08112233445566', '08112233445577', '$2y$10$Xhj80BwE/ar3jkFg2rq.JOBPwxir9HfFh/YO/SBmMl7VH5dHg.SAe', 'Super', '2018-10-20 09:42:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2018-10-20 02:42:00', '1', '0'),
(6, 'Admin', 'Baru', 'baru@bmn.com', '08112244335566', '08112244336655', '$2y$10$J1dS9/PiA8/KpLSek0gZLe87dy2QnejN4bFmiMNqRubDeeahItDyC', 'Super', '2018-10-20 09:46:19', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2018-10-20 02:47:27', '0', '1');

-- --------------------------------------------------------

--
-- Table structure for table `eventlog`
--

CREATE TABLE `eventlog` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event` varchar(200) NOT NULL,
  `eventRowIdOrRef` varchar(20) DEFAULT NULL,
  `eventDesc` text,
  `eventTable` varchar(20) DEFAULT NULL,
  `staffInCharge` bigint(20) UNSIGNED NOT NULL,
  `eventTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `eventlog`
--

INSERT INTO `eventlog` (`id`, `event`, `eventRowIdOrRef`, `eventDesc`, `eventTable`, `staffInCharge`, `eventTime`) VALUES
(1, 'Item Update', '60', 'Details of item with code \'11142322\' was updated', 'items', 3, '2018-10-20 02:26:15'),
(2, 'Stock Update (New Stock)', '60', '<p>10 quantities of Buku Absen was added to stock</p>\n            Reason: <p>Penambahan Barang Baru Definitif</p>', 'items', 3, '2018-10-20 02:26:40'),
(3, 'Stock Update (New Stock)', '60', '<p>50 quantities of Buku Absen was added to stock</p>\n            Reason: <p>Penambahan Barang Baru Transit</p>', 'items', 3, '2018-10-20 02:26:56');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `kategori` varchar(90) NOT NULL,
  `barcode` varchar(90) NOT NULL,
  `code` varchar(10) NOT NULL,
  `satuan` varchar(30) NOT NULL,
  `lokasi` varchar(50) NOT NULL,
  `transit` varchar(20) NOT NULL,
  `aktivasi` varchar(10) NOT NULL,
  `description` text,
  `unitPrice` decimal(10,2) NOT NULL,
  `quantity` int(6) NOT NULL,
  `minimal` int(10) NOT NULL,
  `dateAdded` datetime NOT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pengadaan` int(2) NOT NULL,
  `banyak` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `kategori`, `barcode`, `code`, `satuan`, `lokasi`, `transit`, `aktivasi`, `description`, `unitPrice`, `quantity`, `minimal`, `dateAdded`, `lastUpdated`, `pengadaan`, `banyak`) VALUES
(1, 'Ballpoint', 'ALAT TULIS', '11144451', '92141074', 'buah', '', '0', '1', '', '59334.00', 0, 18, '2018-08-04 13:47:13', '2018-10-20 02:27:54', 1, 0),
(2, 'Sign pen M.B.H', 'ALAT TULIS', '11104773', '19793465', 'buah', '', '0', '1', '', '38631.00', 40, 20, '2018-08-04 13:47:15', '2018-09-18 00:28:59', 1, 0),
(3, 'Spidol WB 70 (permanen)', 'ALAT TULIS', '11177794', '11308851', 'buah', '', '0', '1', '', '13455.00', 35, 21, '2018-08-04 13:47:16', '2018-10-20 02:31:48', 1, 0),
(4, 'Spidol WB 500', 'ALAT TULIS', '11169563', '17212765', 'buah', '', '0', '1', '', '12245.00', 21, 23, '2018-08-04 13:47:17', '2018-10-20 02:32:03', 1, 0),
(5, 'Pensil', 'ALAT TULIS', '11130625', '37601925', 'buah', '', '0', '1', '', '54888.00', 38, 6, '2018-08-04 13:47:18', '2018-08-04 06:47:15', 1, 0),
(6, 'Satabilo boss (M, B, H, Hj)', 'ALAT TULIS', '11180568', '89057093', 'buah', '', '0', '1', '', '71976.00', 41, 7, '2018-08-04 13:47:19', '2018-08-04 06:47:16', 1, 0),
(7, 'Bolpoint Standart', 'ALAT TULIS', '11143065', '85690036', 'buah', '', '0', '1', '', '30025.00', 29, 27, '2018-08-04 13:47:20', '2018-08-04 06:47:17', 1, 0),
(8, 'Bolpoint Pilot G1', 'ALAT TULIS', '11115442', '23583961', 'buah', '', '0', '1', '', '38379.00', 24, 11, '2018-08-04 13:47:21', '2018-08-04 06:47:18', 1, 0),
(9, 'Bolpoint Pilot G2', 'ALAT TULIS', '11170796', '89049205', 'buah', '', '0', '1', '', '35551.00', 59, 11, '2018-08-04 13:47:22', '2018-08-04 06:47:19', 1, 0),
(10, 'Bolpoint Paster C6/606', 'ALAT TULIS', '11148508', '81795955', 'buah', '', '0', '1', '', '17688.00', 35, 15, '2018-08-04 13:47:23', '2018-08-04 06:47:20', 1, 0),
(11, 'Bolpoint Combo', 'ALAT TULIS', '11153822', '27973638', 'buah', '', '0', '1', '', '42423.00', 7, 9, '2018-08-04 13:47:24', '2018-08-04 06:47:21', 1, 0),
(12, 'Drawing Pen A1/7A5', 'ALAT TULIS', '11143124', '75910725', 'buah', '', '0', '1', '', '32612.00', 34, 15, '2018-08-04 13:47:25', '2018-08-04 06:47:22', 1, 0),
(13, 'Isi bolpoint mekanik', 'ALAT TULIS', '11102649', '64303387', 'buah', '', '0', '1', '', '60581.00', 46, 12, '2018-08-04 13:47:26', '2018-08-04 06:47:23', 1, 0),
(14, 'Bolpoint tempel meja', 'ALAT TULIS', '11106996', '57472108', 'buah', '', '0', '1', '', '61840.00', 52, 5, '2018-08-04 13:47:27', '2018-08-04 06:47:24', 1, 0),
(15, 'Pensil mekanik joyko', 'ALAT TULIS', '11116173', '61060414', 'buah', '', '0', '1', '', '33398.00', 58, 6, '2018-08-04 13:47:28', '2018-08-04 06:47:25', 1, 0),
(16, 'Pensil mekanik stleadler', 'ALAT TULIS', '11153521', '92434920', 'buah', '', '0', '1', '', '22545.00', 53, 21, '2018-08-04 13:47:29', '2018-08-04 06:47:26', 1, 0),
(17, 'Supidol  OHP pine/medium 4 set', 'ALAT TULIS', '11170002', '38578820', 'buah', '', '0', '1', '', '49766.00', 20, 26, '2018-08-04 13:47:30', '2018-08-04 06:47:27', 1, 0),
(18, 'Supidol  OHP pine/medium 6 set', 'ALAT TULIS', '11199747', '93985060', 'buah', '', '0', '1', '', '82095.00', 21, 11, '2018-08-04 13:47:31', '2018-08-04 06:47:28', 1, 0),
(19, 'Bolpoint Parker Jotter', 'ALAT TULIS', '11170147', '96556157', 'buah', '', '0', '1', '', '22472.00', 22, 30, '2018-08-04 13:47:32', '2018-10-20 02:32:22', 1, 0),
(20, 'Supidol Panabord', 'ALAT TULIS', '11173251', '84305762', 'buah', '', '0', '1', '', '97786.00', 14, 26, '2018-08-04 13:47:33', '2018-08-04 06:47:30', 1, 0),
(21, 'Tinta tulis parker', 'TINTA TULIS, TINTA STEMPEL', '22282650', '19282898', 'botol', '', '0', '1', '', '23057.00', 27, 6, '2018-08-04 13:47:34', '2018-08-04 06:47:31', 1, 0),
(22, 'Tinta Spidol hitam', 'TINTA TULIS, TINTA STEMPEL', '22273703', '42435673', 'botol', '', '0', '1', '', '27794.00', 58, 6, '2018-08-04 13:47:35', '2018-08-04 06:47:32', 1, 0),
(23, 'tinta stempel artline', 'TINTA TULIS, TINTA STEMPEL', '22294717', '73536409', 'botol', '', '0', '1', '', '47535.00', 31, 12, '2018-08-04 13:47:36', '2018-08-04 06:47:33', 1, 0),
(24, 'Tinta Stempel Otomatis', 'TINTA TULIS, TINTA STEMPEL', '22245817', '12041913', 'botol', '', '0', '1', '', '78818.00', 34, 20, '2018-08-04 13:47:37', '2018-08-04 06:47:34', 1, 0),
(25, 'Tinta Spidol Warna', 'TINTA TULIS, TINTA STEMPEL', '22252149', '26867190', 'botol', '', '0', '1', '', '46375.00', 35, 16, '2018-08-04 13:47:38', '2018-08-04 06:47:35', 1, 0),
(26, 'Tinta Numerator', 'TINTA TULIS, TINTA STEMPEL', '22216657', '81521171', 'botol', '', '0', '1', '', '19633.00', 60, 17, '2018-08-04 13:47:39', '2018-08-04 06:47:36', 1, 0),
(27, 'Acco besi/plastik', 'PENJEPIT KERTAS', '33398215', '49339754', 'pak', '', '0', '1', '', '92713.00', 45, 15, '2018-08-04 13:47:40', '2018-08-04 06:47:37', 1, 0),
(28, 'Binder clip No.155', 'PENJEPIT KERTAS', '33348580', '25114953', 'pak', '', '0', '1', '', '14595.00', 18, 24, '2018-08-04 13:47:41', '2018-08-04 06:47:38', 1, 0),
(29, 'Paper clip warna', 'PENJEPIT KERTAS', '33376746', '86334766', 'pak', '', '0', '1', '', '79269.00', 39, 27, '2018-08-04 13:47:42', '2018-08-04 06:47:39', 1, 0),
(30, 'Paper clip kecil', 'PENJEPIT KERTAS', '33385289', '29758479', 'pak', '', '0', '1', '', '50986.00', 13, 23, '2018-08-04 13:47:43', '2018-08-04 06:47:40', 1, 0),
(31, 'Binder Clip No. 111', 'PENJEPIT KERTAS', '33391429', '84628925', 'pak', '', '0', '1', '', '79815.00', 6, 23, '2018-08-04 13:47:44', '2018-10-20 02:27:55', 1, 0),
(32, 'Binder Clip No. 107', 'PENJEPIT KERTAS', '33362443', '81095405', 'pak', '', '0', '1', '', '38413.00', 48, 11, '2018-08-04 13:47:45', '2018-08-04 06:47:42', 1, 0),
(33, 'Paper Cilp besar', 'PENJEPIT KERTAS', '33394444', '10075063', 'pak', '', '0', '1', '', '70625.00', 5, 24, '2018-08-04 13:47:46', '2018-08-04 06:47:13', 1, 0),
(34, 'Binding Kawat', 'PENJEPIT KERTAS', '33392996', '44190861', 'pak', '', '0', '1', '', '87629.00', 33, 17, '2018-08-04 13:47:47', '2018-08-04 06:47:14', 1, 0),
(35, 'Binding Kawat A4 ukuran 3/8\"', 'PENJEPIT KERTAS', '33333240', '92960710', 'pak', '', '0', '1', '', '84744.00', 56, 27, '2018-08-04 13:47:48', '2018-08-04 06:47:15', 1, 0),
(36, 'Binding Kawat A4 ukuran 3/16\"', 'PENJEPIT KERTAS', '33337166', '42133129', 'pak', '', '0', '1', '', '63285.00', 22, 27, '2018-08-04 13:47:49', '2018-08-04 06:47:16', 1, 0),
(37, 'Binding Kawat A4 ukuran 7/16\"', 'PENJEPIT KERTAS', '33321273', '81189135', 'pak', '', '0', '1', '', '46632.00', 59, 16, '2018-08-04 13:47:50', '2018-08-04 06:47:17', 1, 0),
(38, 'Binding Kawat A4 ukuran 1/2\"', 'PENJEPIT KERTAS', '33391265', '96227995', 'pak', '', '0', '1', '', '77381.00', 13, 6, '2018-08-04 13:47:51', '2018-08-04 06:47:18', 1, 0),
(39, 'Binding Kawat A4 ukuran 5/8\"', 'PENJEPIT KERTAS', '33363820', '76090147', 'pak', '', '0', '1', '', '93354.00', 12, 13, '2018-08-04 13:47:52', '2018-08-04 06:47:19', 1, 0),
(40, 'Binding Kawat A4 ukuran 3/4\"', 'PENJEPIT KERTAS', '33349631', '76109408', 'pak', '', '0', '1', '', '95678.00', 52, 6, '2018-08-04 13:47:53', '2018-08-04 06:47:20', 1, 0),
(41, 'Binding Kawat A4 ukuran 7/8\"', 'PENJEPIT KERTAS', '33343548', '59402451', 'pak', '', '0', '1', '', '44485.00', 44, 15, '2018-08-04 13:47:54', '2018-08-04 06:47:21', 1, 0),
(42, 'Binding Kawat A4 ukuran 1\"', 'PENJEPIT KERTAS', '33333820', '79825996', 'pak', '', '0', '1', '', '39887.00', 13, 30, '2018-08-04 13:47:55', '2018-08-04 06:47:22', 1, 0),
(43, 'Binding Kawat A4 ukuran 1 1/4\"', 'PENJEPIT KERTAS', '33305363', '91453952', 'pak', '', '0', '1', '', '46110.00', 33, 15, '2018-08-04 13:47:56', '2018-08-04 06:47:23', 1, 0),
(44, 'Binder Clip Nno. 200', 'PENJEPIT KERTAS', '33313303', '90155286', 'pak', '', '0', '1', '', '89707.00', 45, 7, '2018-08-04 13:47:57', '2018-08-04 06:47:24', 1, 0),
(45, 'Penghapus WB', 'PENGHAPUS/KOREKTOR', '44420591', '44468455', 'buah', '', '0', '1', '', '61917.00', 5, 19, '2018-08-04 13:47:58', '2018-08-04 06:47:25', 1, 0),
(46, 'Steadler HD', 'PENGHAPUS/KOREKTOR', '44468410', '41335518', 'buah', '', '0', '1', '', '36941.00', 5, 10, '2018-08-04 13:47:59', '2018-08-04 06:47:26', 1, 0),
(47, 'Tipe-X', 'PENGHAPUS/KOREKTOR', '44400596', '18032601', 'buah', '', '0', '1', '', '53951.00', 8, 29, '2018-08-04 13:47:13', '2018-08-04 06:47:27', 1, 0),
(48, 'Tipe Ex Re No. 9010', 'PENGHAPUS/KOREKTOR', '44403825', '50266922', 'buah', '', '0', '1', '', '36954.00', 56, 21, '2018-08-04 13:47:14', '2018-08-04 06:47:28', 1, 0),
(49, 'Buku Tulis', 'BUKU TULIS', '55533157', '79204955', 'buah', '', '0', '1', '', '63489.00', 11, 16, '2018-08-04 13:47:15', '2018-08-04 06:47:29', 1, 0),
(50, 'Buku Kasbon', 'BUKU TULIS', '55551336', '33097751', 'buah', '', '0', '1', '', '83691.00', 46, 13, '2018-08-04 13:47:16', '2018-08-04 06:47:30', 1, 0),
(51, 'Buku Kas Folio', 'BUKU TULIS', '55514810', '33774893', 'buah', '', '0', '1', '', '92529.00', 5, 13, '2018-08-04 13:47:17', '2018-08-04 06:47:31', 1, 0),
(52, 'Buku Exspedisi', 'BUKU TULIS', '55575662', '70106495', 'buah', '', '0', '1', '', '13363.00', 58, 13, '2018-08-04 13:47:18', '2018-08-04 06:47:32', 1, 0),
(53, 'Buku Folio / Agenda', 'BUKU TULIS', '55572548', '95725663', 'buah', '', '0', '1', '', '54054.00', 58, 6, '2018-08-04 13:47:19', '2018-08-04 06:47:33', 1, 0),
(54, 'Buku Kwitansi Besar', 'BUKU TULIS', '55575158', '12176573', 'buah', '', '0', '1', '', '97153.00', 33, 20, '2018-08-04 13:47:20', '2018-08-04 06:47:34', 1, 0),
(55, 'Buku Tulis Tebal 1/2 Folio', 'BUKU TULIS', '55532642', '68731106', 'buah', '', '0', '1', '', '72258.00', 60, 30, '2018-08-04 13:47:21', '2018-08-04 06:47:35', 1, 0),
(56, 'Block note', 'BUKU TULIS', '55553250', '39759867', 'buah', '', '0', '1', '', '86797.00', 5, 9, '2018-08-04 13:47:22', '2018-08-04 06:47:36', 1, 0),
(57, 'Buku Tamu', 'BUKU TULIS', '55514193', '70393015', 'buah', '', '0', '1', '', '14459.00', 16, 16, '2018-08-04 13:47:23', '2018-08-04 06:47:37', 1, 0),
(58, 'Block Note Kokuya', 'BUKU TULIS', '55584019', '88016743', 'buah', '', '0', '1', '', '79373.00', 57, 24, '2018-08-04 13:47:24', '2018-08-04 06:47:38', 1, 0),
(59, 'Buku Kwitansi Kecil', 'BUKU TULIS', '55514867', '25569842', 'buah', '', '0', '1', '', '53746.00', 50, 28, '2018-08-04 13:47:25', '2018-08-04 06:47:39', 1, 0),
(60, 'Buku Absen', 'ALAT TULIS', '9970987052', '11142322', 'Buah', '', '0', '1', '', '20000.00', 60, 10, '2018-10-20 09:25:47', '2018-10-20 02:31:12', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `kartu_stok`
--

CREATE TABLE `kartu_stok` (
  `no` int(11) NOT NULL,
  `id_barang` varchar(50) NOT NULL,
  `nama_barang` varchar(50) NOT NULL,
  `transit` int(3) NOT NULL,
  `jenis` varchar(30) NOT NULL,
  `satuan` varchar(20) NOT NULL,
  `tanggal` datetime NOT NULL,
  `aksi` varchar(50) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `masuk` int(20) NOT NULL,
  `keluar` int(20) NOT NULL,
  `saldo` int(20) NOT NULL,
  `admin` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kartu_stok`
--

INSERT INTO `kartu_stok` (`no`, `id_barang`, `nama_barang`, `transit`, `jenis`, `satuan`, `tanggal`, `aksi`, `unit`, `masuk`, `keluar`, `saldo`, `admin`) VALUES
(1, '11142322', 'Buku Absen', 0, 'ALAT TULIS', '', '2018-10-20 09:25:47', 'Stok Baru', '', 0, 0, 30, 'Admin Bmn'),
(2, '11142322', 'Buku Absen', 0, 'ALAT TULIS', '', '2018-10-20 09:26:40', 'Penambahan', '', 10, 0, 40, 'Admin Bmn'),
(3, '11142322', 'Buku Absen', 2, 'ALAT TULIS', '', '2018-10-20 09:26:56', 'Penambahan', '', 50, 0, 40, 'Admin Bmn'),
(4, '11142322', 'Buku Absen', 0, 'ALAT TULIS', '', '2018-10-20 09:27:13', 'Penambahan', '', 50, 0, 90, 'Admin Bmn'),
(5, '11142322', 'Buku Absen', 0, 'ALAT TULIS', '', '2018-10-20 09:31:12', 'Pemakaian', 'Subbag. Persuratan', 0, 30, 60, 'Admin Bmn'),
(6, '11308851', 'Spidol WB 70 (permanen)', 0, 'ALAT TULIS', '', '2018-10-20 09:31:48', 'Pemakaian', 'Subbag. Persuratan', 0, 20, 35, 'Admin Bmn'),
(7, '17212765', 'Spidol WB 500', 0, 'ALAT TULIS', '', '2018-10-20 09:32:03', 'Pemakaian', 'Subbag. Persuratan', 0, 30, 21, 'Admin Bmn'),
(8, '96556157', 'Bolpoint Parker Jotter', 0, 'ALAT TULIS', '', '2018-10-20 09:32:22', 'Pemakaian', 'Subbag. Persuratan', 0, 5, 22, 'Admin Bmn');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(20) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `admin` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `kategori`, `tanggal`, `admin`) VALUES
(13, 'ALAT TULIS', '2018-10-19 15:20:45', 'Admin Bmn'),
(14, 'TINTA TULIS, TINTA STEMPEL', '2018-10-19 15:20:54', 'Admin Bmn'),
(15, 'PENJEPIT KERTAS', '2018-10-19 15:21:05', 'Admin Bmn'),
(16, 'PENGHAPUS/KOREKTOR', '2018-10-19 15:21:14', 'Admin Bmn'),
(17, 'BUKU TULIS', '2018-10-19 15:21:23', 'Admin Bmn');

-- --------------------------------------------------------

--
-- Table structure for table `laporan_pengadaan`
--

CREATE TABLE `laporan_pengadaan` (
  `id` int(10) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `administrator` varchar(50) NOT NULL,
  `direktori` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `laporan_pengadaan`
--

INSERT INTO `laporan_pengadaan` (`id`, `tanggal`, `administrator`, `direktori`) VALUES
(1, '2018-10-20 02:27:44', 'Admin Bmn', '/opt/lampp/htdocs/e-inventory/dokumen/pengadaan/20-10-2018 04:27:44 am.pdf'),
(2, '2018-10-20 02:27:59', 'Admin Bmn', '/opt/lampp/htdocs/e-inventory/dokumen/pengadaan/20-10-2018 04:27:59 am.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `lk_sess`
--

CREATE TABLE `lk_sess` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `satuan_unit`
--

CREATE TABLE `satuan_unit` (
  `id` int(4) NOT NULL,
  `satuan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `satuan_unit`
--

INSERT INTO `satuan_unit` (`id`, `satuan`) VALUES
(1, 'Subbag. Tata Usaha'),
(2, 'Subbag. Administrasi Umum'),
(3, 'Subbag. Persuratan');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transId` bigint(20) UNSIGNED NOT NULL,
  `ref` varchar(10) NOT NULL,
  `transit` varchar(30) NOT NULL,
  `itemName` varchar(50) NOT NULL,
  `itemCode` varchar(50) NOT NULL,
  `description` text,
  `ket` varchar(20) NOT NULL,
  `quantity` int(6) NOT NULL,
  `pengguna` varchar(30) NOT NULL,
  `unitPrice` decimal(10,2) NOT NULL,
  `totalPrice` decimal(10,2) NOT NULL,
  `totalMoneySpent` decimal(10,2) NOT NULL,
  `amountTendered` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `discount_percentage` decimal(10,2) NOT NULL,
  `vatPercentage` decimal(10,2) NOT NULL,
  `vatAmount` decimal(10,2) NOT NULL,
  `changeDue` decimal(10,2) NOT NULL,
  `modeOfPayment` varchar(20) NOT NULL,
  `cust_name` varchar(20) DEFAULT NULL,
  `cust_phone` varchar(15) DEFAULT NULL,
  `cust_email` varchar(50) DEFAULT NULL,
  `transType` char(1) NOT NULL,
  `staffId` bigint(20) UNSIGNED NOT NULL,
  `transDate` datetime NOT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cancelled` char(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transId`, `ref`, `transit`, `itemName`, `itemCode`, `description`, `ket`, `quantity`, `pengguna`, `unitPrice`, `totalPrice`, `totalMoneySpent`, `amountTendered`, `discount_amount`, `discount_percentage`, `vatPercentage`, `vatAmount`, `changeDue`, `modeOfPayment`, `cust_name`, `cust_phone`, `cust_email`, `transType`, `staffId`, `transDate`, `lastUpdated`, `cancelled`) VALUES
(1, '1559281', '', 'Buku Absen', '11142322', '', '', 30, 'Admin Bmn', '20000.00', '600000.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '', 'Subbag. Persuratan', NULL, NULL, '', 0, '2018-10-20 09:31:12', '2018-10-20 02:31:12', '0'),
(2, '4350153', '', 'Spidol WB 70 (permanen)', '11308851', '', '', 20, 'Admin Bmn', '13455.00', '269100.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '', 'Subbag. Persuratan', NULL, NULL, '', 0, '2018-10-20 09:31:48', '2018-10-20 02:31:48', '0'),
(3, '704986420', '', 'Spidol WB 500', '17212765', '', '', 30, 'Admin Bmn', '12245.00', '367350.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '', 'Subbag. Persuratan', NULL, NULL, '', 0, '2018-10-20 09:32:03', '2018-10-20 02:32:03', '0'),
(4, '347635', '', 'Bolpoint Parker Jotter', '96556157', '', '', 5, 'Admin Bmn', '22472.00', '112360.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '', 'Subbag. Persuratan', NULL, NULL, '', 0, '2018-10-20 09:32:22', '2018-10-20 02:32:22', '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `mobile1` (`mobile1`);

--
-- Indexes for table `eventlog`
--
ALTER TABLE `eventlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `kartu_stok`
--
ALTER TABLE `kartu_stok`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporan_pengadaan`
--
ALTER TABLE `laporan_pengadaan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `satuan_unit`
--
ALTER TABLE `satuan_unit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `eventlog`
--
ALTER TABLE `eventlog`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `kartu_stok`
--
ALTER TABLE `kartu_stok`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `laporan_pengadaan`
--
ALTER TABLE `laporan_pengadaan`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `satuan_unit`
--
ALTER TABLE `satuan_unit`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
