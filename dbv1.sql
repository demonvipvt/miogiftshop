-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.9 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for miogift
CREATE DATABASE IF NOT EXISTS `miogift` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `miogift`;


-- Dumping structure for table miogift.category
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(1000) NOT NULL,
  `description` longtext,
  `image` varchar(255) DEFAULT NULL,
  `slug` varchar(1000) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `is_active` bit(1) NOT NULL DEFAULT b'1',
  `on_navigation` bit(1) NOT NULL DEFAULT b'1',
  `order` int(3) NOT NULL DEFAULT '1',
  `seo_title` varchar(1000) DEFAULT NULL,
  `seo_description` varchar(1000) DEFAULT NULL,
  `seo_tags` varchar(1000) DEFAULT NULL,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_category_category` (`parent_id`),
  CONSTRAINT `FK_category_category` FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table miogift.category: ~0 rows (approximately)
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
/*!40000 ALTER TABLE `category` ENABLE KEYS */;


-- Dumping structure for table miogift.page
CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(1000) NOT NULL,
  `description` longtext NOT NULL,
  `slug` varchar(1000) NOT NULL,
  `seo_title` varchar(1000) DEFAULT NULL,
  `seo_desciption` varchar(1000) DEFAULT NULL,
  `seo_tags` varchar(1000) DEFAULT NULL,
  `is_active` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='static page';

-- Dumping data for table miogift.page: ~0 rows (approximately)
/*!40000 ALTER TABLE `page` DISABLE KEYS */;
/*!40000 ALTER TABLE `page` ENABLE KEYS */;


-- Dumping structure for table miogift.price
CREATE TABLE IF NOT EXISTS `price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` int(11) NOT NULL DEFAULT '0',
  `sale_percent` int(3) NOT NULL DEFAULT '0',
  `from_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `to_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_price_product` (`product_id`),
  CONSTRAINT `FK_price_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='price of product by season';

-- Dumping data for table miogift.price: ~0 rows (approximately)
/*!40000 ALTER TABLE `price` DISABLE KEYS */;
/*!40000 ALTER TABLE `price` ENABLE KEYS */;


-- Dumping structure for table miogift.product
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `description` longtext,
  `sort_description` varchar(1000) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  `quantity_sold` int(11) NOT NULL DEFAULT '0',
  `price` int(11) NOT NULL DEFAULT '0',
  `slug` varchar(1000) NOT NULL,
  `is_active` bit(1) NOT NULL DEFAULT b'1',
  `seo_title` varchar(1000) DEFAULT NULL,
  `seo_description` varchar(1000) DEFAULT NULL,
  `seo_tags` varchar(1000) DEFAULT NULL,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `FK_product_category` (`category_id`),
  CONSTRAINT `FK_product_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table miogift.product: ~0 rows (approximately)
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
/*!40000 ALTER TABLE `product` ENABLE KEYS */;


-- Dumping structure for table miogift.related_product
CREATE TABLE IF NOT EXISTS `related_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product1` int(11) NOT NULL DEFAULT '0',
  `product2` int(11) NOT NULL DEFAULT '0',
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_related_product_product` (`product1`),
  KEY `FK_related_product_product_2` (`product2`),
  CONSTRAINT `FK_related_product_product` FOREIGN KEY (`product1`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_related_product_product_2` FOREIGN KEY (`product2`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table miogift.related_product: ~0 rows (approximately)
/*!40000 ALTER TABLE `related_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `related_product` ENABLE KEYS */;


-- Dumping structure for table miogift.slug
CREATE TABLE IF NOT EXISTS `slug` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(1000) NOT NULL,
  `object` varchar(10) NOT NULL,
  `object_id` int(11) NOT NULL,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table miogift.slug: ~0 rows (approximately)
/*!40000 ALTER TABLE `slug` DISABLE KEYS */;
INSERT IGNORE INTO `slug` (`id`, `slug`, `object`, `object_id`, `updated`) VALUES
	(1, 'home', 'home', 1, '2016-07-07 12:39:52');
/*!40000 ALTER TABLE `slug` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
