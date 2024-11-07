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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

-- Dumping structure for table depotbaksoasli.kategori
CREATE TABLE IF NOT EXISTS `kategori` (
  `id_kategori` int NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) NOT NULL,
  PRIMARY KEY (`id_kategori`),
  UNIQUE KEY `nama_kategori` (`nama_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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
  `status_transaksi`  int DEFAULT 0,
  `admin_id_admin` int NOT NULL,
  PRIMARY KEY (`id_transaksi`),
  KEY `transaksi_admin_fk` (`admin_id_admin`),
  CONSTRAINT `transaksi_admin_fk` FOREIGN KEY (`admin_id_admin`) REFERENCES `admin_depot` (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
