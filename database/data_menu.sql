-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for depotbaksoasli
CREATE DATABASE IF NOT EXISTS `depotbaksoasli` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `depotbaksoasli`;

-- Dumping structure for table depotbaksoasli.admin_depot
CREATE TABLE IF NOT EXISTS `admin_depot` (
  `id_admin` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email_admin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `whatsapp_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `store_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `photo` text,
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `email_admin` (`email_admin`),
  UNIQUE KEY `username` (`username`,`email_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table depotbaksoasli.admin_depot: ~1 rows (approximately)
DELETE FROM `admin_depot`;
INSERT INTO `admin_depot` (`id_admin`, `username`, `email_admin`, `password`, `whatsapp_number`, `store_address`, `photo`) VALUES
	(3, 'BaksoAsli2', 'depotbaksoasli2@gmail.com', 'bdi2006', '0878-1218-2840', 'JL. MT. HARYONO RT. 12 No.77 Balikpapan ', NULL);

-- Dumping structure for table depotbaksoasli.detail_transaksi
CREATE TABLE IF NOT EXISTS `detail_transaksi` (
  `id_detail_transaksi` int NOT NULL AUTO_INCREMENT,
  `jumlah` int NOT NULL,
  `total_harga` int NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `transaksi_id_transaksi` int NOT NULL,
  `menu_id_menu` int NOT NULL,
  PRIMARY KEY (`id_detail_transaksi`),
  KEY `detail_transaksi_menu_fk` (`menu_id_menu`),
  KEY `detail_transaksi_transaksi_fk` (`transaksi_id_transaksi`),
  CONSTRAINT `detail_transaksi_menu_fk` FOREIGN KEY (`menu_id_menu`) REFERENCES `menu` (`id_menu`),
  CONSTRAINT `detail_transaksi_transaksi_fk` FOREIGN KEY (`transaksi_id_transaksi`) REFERENCES `transaksi` (`id_transaksi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table depotbaksoasli.detail_transaksi: ~0 rows (approximately)
DELETE FROM `detail_transaksi`;

-- Dumping structure for table depotbaksoasli.kategori
CREATE TABLE IF NOT EXISTS `kategori` (
  `id_kategori` int NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) NOT NULL,
  PRIMARY KEY (`id_kategori`),
  UNIQUE KEY `nama_kategori` (`nama_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table depotbaksoasli.kategori: ~9 rows (approximately)
DELETE FROM `kategori`;
INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
	(3, 'Bakso'),
	(1, 'Mie'),
	(2, 'Mie Telur'),
	(6, 'Minuman'),
	(9, 'Nasi'),
	(8, 'Pangsit'),
	(5, 'Sarapan Pagi'),
	(7, 'Snack'),
	(4, 'Sop');

-- Dumping structure for table depotbaksoasli.menu
CREATE TABLE IF NOT EXISTS `menu` (
  `id_menu` int NOT NULL AUTO_INCREMENT,
  `nama_menu` varchar(255) NOT NULL,
  `harga_menu` int NOT NULL,
  `harga_setengah` int DEFAULT NULL,
  `kategori_id_kategori` int NOT NULL,
  PRIMARY KEY (`id_menu`),
  UNIQUE KEY `nama_menu` (`nama_menu`),
  KEY `menu_kategori_fk` (`kategori_id_kategori`),
  CONSTRAINT `menu_kategori_fk` FOREIGN KEY (`kategori_id_kategori`) REFERENCES `kategori` (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table depotbaksoasli.menu: ~111 rows (approximately)
DELETE FROM `menu`;

INSERT INTO `menu` (`id_menu`, `nama_menu`, `harga_menu`, `harga_setengah`, `kategori_id_kategori`) VALUES
	(1, 'Bakso Beku', 4500, NULL, 3),
	(2, 'Bakso / biji', 5000, NULL, 3),
	(3, 'Sawi Kuah', 10000, NULL, 3),
	(4, 'Kuah Bakso', 6000, NULL, 3),
	(5, 'Kuah Buntut', 10000, NULL, 3),
	(6, 'Bakso Kuah', 33000, 20000, 3),
	(7, 'Tahu Kuah', 33000, 20000, 3),
	(8, 'Bakso Tahu Kuah', 33000, 20000, 3),
	(9, 'Bakso Pangsit', 33000, 20000, 3),
	(10, 'Bakso Campur', 35000, 20000, 3),
	(11, 'Bakso Pangsit Campur', 37000, NULL, 3),
	(12, 'Mie', 20000, 12000, 1),
	(13, 'Mie Bakso', 35000, 31000, 1),
	(14, 'Mie Bakso Tahu', 35000, 31000, 1),
	(15, 'Mie Baksi Kikil', 37000, 33000, 1),
	(16, 'Mie Bakso Babat', 37000, 33000, 1),
	(17, 'Mie Bakso Tetelan', 37000, 33000, 1),
	(18, 'Mie Bakso Campur', 37000, 33000, 1),
	(19, 'Mie Ayam', 28000, 24000, 1),
	(20, 'Mie Ayam Bakso', 37000, 33000, 1),
	(21, 'Mie Ayam Bakso Campur', 43000, 39000, 1),
	(22, 'Mie Pangsit', 35000, 31000, 1),
	(23, 'Mie Pangsit Bakso', 40000, 36000, 1),
	(24, 'Mie Pangsit Bakso Campur', 48000, 44000, 1),
	(25, 'Pangsit / biji', 5000, NULL, 8),
	(26, 'Pangsit Goreng', 25000, 15000, 8),
	(27, 'Pangsit Rebus', 25000, 15000, 8),
	(28, 'Sop Buntut', 65000, NULL, 4),
	(29, 'Sop Daging', 55000, NULL, 4),
	(30, 'Sop Kikil', 50000, 30000, 4),
	(31, 'Sop Babat', 50000, 30000, 4),
	(32, 'Sop Tetelan', 50000, 30000, 4),
	(33, 'Sop Campur', 50000, 30000, 4),
	(34, 'Sop Sumsum', 50000, 30000, 4),
	(35, 'Nasi Putih', 8000, 5000, 9),
	(36, 'Telor Puyuh 1 biji', 1500, NULL, 2),
	(37, 'Telor Puyuh (4 biji)', 6000, NULL, 2),
	(38, 'Yammie Telor', 26000, 22000, 2),
	(39, 'Yammie Bakso Telor', 37000, 33000, 2),
	(40, 'Yammie Bakso Telor Campur', 43000, 39000, 2),
	(41, 'Yammie Ayam Telor', 37000, 33000, 2),
	(42, 'Yammie Ayam Bakso Telor', 40000, 36000, 2),
	(43, 'Yammie Ayam Bakso Telor Campur', 48000, 44000, 2),
	(44, 'Yammie Pangsit Telor', 40000, 36000, 2),
	(45, 'Yammie Pangsit Bakso Telor', 48000, 44000, 2),
	(46, 'Yammie Pangsit Bakso Telor Campur', 52000, 48000, 2),
	(47, 'Teh Tawar', 5000, NULL, 6),
	(48, 'Teh Manis', 7000, NULL, 6),
	(49, 'Es Sirup', 7000, NULL, 6),
	(50, 'Jeruk Manis', 18000, NULL, 6),
	(51, 'Jeruk Manis Murni', 22000, NULL, 6),
	(52, 'Jeruk Nipis', 12000, NULL, 6),
	(53, 'Jus Alpukat', 22000, NULL, 6),
	(54, 'Jus Mangga', 22000, NULL, 6),
	(55, 'Jus Sirsak', 22000, NULL, 6),
	(56, 'Jus Melon', 22000, NULL, 6),
	(57, 'Es Campur', 25000, NULL, 6),
	(58, 'Es Teler', 25000, NULL, 6),
	(59, 'Es Shanghai', 25000, NULL, 6),
	(60, 'Es Kacang Merah', 25000, NULL, 6),
	(61, 'Es Sari Kacang Ijo', 18000, NULL, 6),
	(62, 'Es Kelapa', 18000, NULL, 6),
	(63, 'Air Kelapa Murni', 5000, NULL, 6),
	(64, 'Es Serut Sirup Susu', 10000, NULL, 6),
	(65, 'Es Cincau', 12000, NULL, 6),
	(66, 'Es Selasih', 12000, NULL, 6),
	(67, 'Larutan Kaleng', 10000, NULL, 6),
	(68, 'Pulpy', 10000, NULL, 6),
	(69, 'Coca Cola', 5000, NULL, 6),
	(70, 'Fanta', 5000, NULL, 6),
	(71, 'Sprite', 5000, NULL, 6),
	(72, 'Milo', 12000, NULL, 6),
	(73, 'Susu Beruang', 12000, NULL, 6),
	(74, 'Susu Soda', 22000, NULL, 6),
	(75, 'Squades Kecil', 4000, NULL, 6),
	(76, 'Squades Tanggung', 6000, NULL, 6),
	(77, 'Squades Besar', 10000, NULL, 6),
	(78, 'Teh Sosro', 6000, NULL, 6),
	(79, 'Teh Kotak', 6000, NULL, 6),
	(80, 'Fruittea', 10000, NULL, 6),
	(81, 'Buavita', 10000, NULL, 6),
	(82, 'Susu Ultra', 10000, NULL, 6),
	(83, 'Kunyit Asam', 10000, NULL, 6),
	(84, 'Kopi', 12000, NULL, 6),
	(85, 'Es Kopi', 15000, NULL, 6),
	(86, 'Kopi Susu', 15000, NULL, 6),
	(87, 'Es Kopi Susu', 15000, NULL, 6),
	(88, 'Teh Susu', 15000, NULL, 6),
	(89, 'Teh Manis Cangkir', 4000, NULL, 6),
	(90, 'Teh Tawar Cangkir', 3000, NULL, 6),
	(91, 'Susu Kedelai', 11000, NULL, 6),
	(92, 'Bubur Polos', 15000, NULL, 5),
	(93, 'Bubur Ayam', 25000, NULL, 5),
	(94, 'Telur 1/2 Matang (1 biji)', 6000, NULL, 5),
	(95, 'Telur 1/2 Matang (2 biji)', 12000, NULL, 5),
	(96, 'Soto Bandung', 28000, NULL, 5),
	(97, 'Soto Bandung + Nasi', 33000, NULL, 5),
	(98, 'Soto Banjar', 28000, 26000, 5),
	(99, 'Sop Banjar', 23000, NULL, 5),
	(100, 'Telur', 7000, NULL, 5),
	(101, 'Perkedel', 4000, NULL, 5),
	(102, 'Nasi Uduk', 30000, NULL, 5),
	(103, 'Nasi Lalapan', 30000, NULL, 5),
	(104, 'Nasi Uduk Telur', 18000, NULL, 5),
	(105, 'Ayam Goreng', 18000, NULL, 5),
	(106, 'Nasi Uduk (Nasi Putih)', 28000, NULL, 5),
	(107, 'Nasi Uduk (kosongan)', 10000, NULL, 5),
	(108, 'Tahu Balik Crispy / Biji', 5000, NULL, 7),
	(109, 'Tahu Balik Crispy 3 Biji', 15000, NULL, 7),
	(110, 'Tahu Balik Crispy 10 Biji', 50000, NULL, 7),
	(111, 'Salome', 12000, NULL, 7);

-- Dumping structure for table depotbaksoasli.transaksi
CREATE TABLE IF NOT EXISTS `transaksi` (
  `id_transaksi` int NOT NULL AUTO_INCREMENT,
  `tanggal_transaksi` date NOT NULL,
  `tipe_order` varchar(255) NOT NULL,
  `no_meja` int DEFAULT NULL,
  `jumlah` int NOT NULL,
  `subtotal_harga` int NOT NULL,
  `pajak` int DEFAULT NULL,
  `total_harga` int NOT NULL,
  `status_transaksi` int DEFAULT 0,
  `admin_id_admin` int NOT NULL,
  PRIMARY KEY (`id_transaksi`),
  KEY `transaksi_admin_fk` (`admin_id_admin`),
  CONSTRAINT `transaksi_admin_fk` FOREIGN KEY (`admin_id_admin`) REFERENCES `admin_depot` (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table depotbaksoasli.transaksi: ~0 rows (approximately)
DELETE FROM `transaksi`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
