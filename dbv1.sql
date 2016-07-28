-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.11-log - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for miogift
DROP DATABASE IF EXISTS `miogift`;
CREATE DATABASE IF NOT EXISTS `miogift` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `miogift`;


-- Dumping structure for table miogift.category
DROP TABLE IF EXISTS `category`;
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
  UNIQUE KEY `slug` (`slug`),
  KEY `FK_category_category` (`parent_id`),
  CONSTRAINT `FK_category_category` FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Dumping data for table miogift.category: ~6 rows (approximately)
DELETE FROM `category`;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` (`id`, `title`, `description`, `image`, `slug`, `parent_id`, `is_active`, `on_navigation`, `order`, `seo_title`, `seo_description`, `seo_tags`, `updated`) VALUES
	(1, 'HỘP BÀI TAROT', '&lt;h1&gt;&lt;strong&gt;HỘP B&amp;Agrave;I TAROT GỖ ĐẸP, HỘP B&amp;Agrave;I TAROT CAO CẤP TPHCM, &lt;/strong&gt;&lt;/h1&gt;\r\n\r\n&lt;h1&gt;&lt;strong&gt;MUA HỘP B&amp;Agrave;I TAROT Ở Đ&amp;Acirc;U?&lt;/strong&gt;&lt;/h1&gt;\r\n', 'Thumbnail-hop-bai-tarot.jpg', 'hop-bai-tarot', NULL, b'1', b'1', 4, 'HỘP BÀI TAROT GỖ ĐẸP, HỘP BÀI TAROT CAO CẤP TPHCM, MUA HỘP BÀI TAROT', 'NƠI CUNG CẤP HỘP BÀI TAROT ĐẸP, CAO CẤP. Chất liệu: gỗ thông, được gia công sắc nét, 100% khách hàng hài lòng, địa chỉ TPHCM......', 'Hộp bài tarot, hộp bài tarot gỗ, mua hộp bài tarot, hộp bài tarot tphcm, hộp bài tarot đẹp', '2016-07-28 09:05:56'),
	(4, 'DANBO GỖ', '&lt;p&gt;&lt;strong&gt;Danbo gỗ&amp;nbsp;&lt;/strong&gt;được tạo bởi một nghệ sĩ người Nhật c&amp;oacute; t&amp;ecirc;n l&amp;agrave; Kiyohiko Azuma trong bộ manga của &amp;ocirc;ng l&amp;agrave; Yotsuba&amp;amp;!. Danbo ban đầu l&amp;agrave; một robot giả v&amp;agrave; trong rất buồn cười được kết hợp bởi c&amp;aacute;c tấm giấy c&amp;aacute;t t&amp;ocirc;ng. V&amp;agrave; Amazon chi nh&amp;aacute;nh tại Nhật Bản đ&amp;atilde; lấy&amp;nbsp;&amp;yacute; tưởng n&amp;agrave;y để tiếp thị v&amp;agrave; n&amp;oacute; đ&amp;atilde; mang lại th&amp;agrave;nh c&amp;ocirc;ng vượt bậc cho c&amp;ocirc;ng ty&lt;/p&gt;\r\n\r\n&lt;p&gt;Trở&amp;nbsp;về cuộc sống với bộn bề lo toan v&amp;agrave; suy nghĩ, ai trong ch&amp;uacute;ng ta đ&amp;ocirc;i l&amp;uacute;c cũng c&amp;oacute; những ph&amp;uacute;t gi&amp;acirc;y cảm x&amp;uacute;c, những chất liệu nghệ thuật t&amp;acirc;m hồn m&amp;agrave; v&amp;ocirc; t&amp;igrave;nh hay hữu &amp;yacute; bị giữ lại ở một g&amp;oacute;c khuất n&amp;agrave;o đ&amp;oacute;&amp;hellip;&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;hearts; Cuộc sống l&amp;agrave; sự sẻ chia, h&amp;atilde;y để&amp;nbsp;&lt;strong&gt;Danbo&lt;/strong&gt;&amp;nbsp;l&amp;agrave; người bạn đồng h&amp;agrave;nh v&amp;agrave; chia sẻ với c&amp;aacute;c bạn những gi&amp;acirc;y ph&amp;uacute;t ấy&amp;hellip; Hay đơn giản chỉ l&amp;agrave; mang đến cho c&amp;aacute;c bạn những niềm vui nho nhỏ th&amp;ocirc;ng qua những h&amp;igrave;nh ảnh dễ thương, đ&amp;aacute;ng y&amp;ecirc;u v&amp;agrave; ngộ nghĩnh của Danbo với những hoạt động trong đời sống thường nhật.&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;hearts; C&amp;ugrave;ng với&amp;nbsp;&lt;strong&gt;Danbo&lt;/strong&gt;&amp;nbsp;h&amp;atilde;y truyền tải cảm x&amp;uacute;c v&amp;agrave; những th&amp;ocirc;ng điệp y&amp;ecirc;u thương đến với bạn b&amp;egrave; v&amp;agrave; những người th&amp;acirc;n y&amp;ecirc;u của m&amp;igrave;nh c&amp;aacute;c bạn nh&amp;eacute;! :)&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n', 'Thumbnail-danbo-go.jpg', 'danbo-go', NULL, b'1', b'1', 1, 'DANBO GỖ THÔNG CHẤT LƯỢNG CAO, HỘP GỖ, DANBO GỖ TPHCM, MUA NGƯỜI GỖ DANBO', 'NƠI MUA DANBO GỖ CHẤT LƯỢNG TPHCM - HÀ NỘI. HỘP ĐỰNG GỖ. TẶNG 7 MẶT CẢM XÚC. Giao hàng toàn quốc.  Cùng với Danbo gô hãy truyền tải cảm xúc và tình yêu nào các bạn', 'Danbo gỗ, mua danbo, danbo gỗ tphcm, búp bê danbo, người gỗ danbo, shop bán danbo', '2016-07-28 08:57:09'),
	(5, 'NHÀ TĂM TRE', '&lt;h1&gt;&lt;a href=&quot;http://sgreen.vn/nha-tam-tre-lon-8591398.html&quot; target=&quot;_blank&quot;&gt;NH&amp;Agrave; TĂM TRE ĐẸP&amp;nbsp;| M&amp;Ocirc; H&amp;Igrave;NH NH&amp;Agrave; L&amp;Agrave;M BẰNG TĂM TPHCM &amp;nbsp;SẢN PHẨM HANDMADE ĐẶC SẶC L&amp;Agrave;M QU&amp;Agrave; LƯU NIỆM&lt;/a&gt;&lt;/h1&gt;\r\n\r\n&lt;p&gt;&lt;a href=&quot;http://sgreen.vn/nha-tam-tre-lon-8591398.html&quot; target=&quot;_blank&quot;&gt;01. &lt;strong&gt;&lt;em&gt;Nh&amp;agrave; tăm tre&lt;/em&gt;&lt;/strong&gt; - giới thiệu đ&amp;ocirc;i n&amp;eacute;t v&amp;agrave; th&amp;ocirc;ng tin sản phẩm&lt;/a&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp;Sản&amp;nbsp;phẩm&amp;nbsp;handmade được đan kết từ h&amp;agrave;ng ng&amp;agrave;n c&amp;acirc;y tăm, c&amp;aacute;c nghệ nh&amp;acirc;n tăm tre đ&amp;atilde; d&amp;agrave;nh ra cả một qu&amp;aacute; tr&amp;igrave;nh ki&amp;ecirc;n nhẫn v&amp;agrave; đam m&amp;ecirc; của m&amp;igrave;nh, thể hiện được sự s&amp;aacute;ng tạo bất tận khi thiết kế những m&amp;ocirc; h&amp;igrave;nh tăm tre độc đ&amp;aacute;o. Do vậy nh&amp;agrave; tăm kh&amp;ocirc;ng chỉ l&amp;agrave; m&amp;ocirc; h&amp;igrave;nh m&amp;agrave; c&amp;ograve;n mang nhiều &amp;yacute; nghĩa b&amp;ecirc;n trong, nh&amp;igrave;n mộc mạc th&amp;acirc;n quen đồng thời cũng đong đầy cảm x&amp;uacute;c &amp;amp; t&amp;igrave;nh cảm.&amp;nbsp;Nh&amp;agrave; tăm tre với những chi tiết tỉ mỷ v&amp;agrave; sắc sảo, n&amp;oacute; lu&amp;ocirc;n mang đến cho người xem cảm gi&amp;aacute;c độc, mới, lạ v&amp;agrave; được nhiều người quan t&amp;acirc;m đến. Chỉ bằng những chiếc tăm đơn giản được kết hợp với mu&amp;ocirc;n v&amp;agrave;n &amp;yacute; tưởng bản vẽ thiết kế s&amp;aacute;ng tạo,&amp;nbsp;&amp;nbsp;sẽ h&amp;igrave;nh th&amp;agrave;nh n&amp;ecirc;n sự độc đ&amp;aacute;o kh&amp;aacute;c lạ, thổi hồn v&amp;agrave;o mỗi t&amp;aacute;c phẩm nghệ thuật&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&amp;nbsp;&lt;a href=&quot;http://sgreen.vn/cach-lam-nha-tam-tre-huong-dan-chi-tiet-9298782.html&quot; target=&quot;_blank&quot;&gt;02. &lt;strong&gt;&lt;em&gt;Hướng dẫn c&amp;aacute;ch l&amp;agrave;m m&amp;ocirc; h&amp;igrave;nh&amp;nbsp;Nh&amp;agrave; tăm tre&lt;/em&gt;&lt;/strong&gt;&amp;nbsp;- chi tiết từng bước một&lt;/a&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;Trong qu&amp;aacute; tr&amp;igrave;nh sản xuất nh&amp;agrave; tăm tre, Sgreen nhận được rất nhiều c&amp;acirc;u hỏi từ kh&amp;aacute;ch h&amp;agrave;ng về việc tự lắp r&amp;aacute;p m&amp;ocirc; h&amp;igrave;nh nh&amp;agrave; bằng tăm. N&amp;ecirc;n sgreen đ&amp;atilde; bi&amp;ecirc;n soạn ra b&amp;agrave;i hướng dẫn phương ph&amp;aacute;p tự lắp r&amp;aacute;p nh&amp;agrave; tăm tre đơn giản, chi tiết từng bước một, gi&amp;uacute;p những bạn đam m&amp;ecirc; l&amp;agrave;m m&amp;ocirc; h&amp;igrave;nh ho&amp;agrave;n to&amp;agrave;n c&amp;oacute; thể tự lắp một nh&amp;agrave; tăm tre cho ri&amp;ecirc;ng m&amp;igrave;nh.&amp;nbsp;C&amp;aacute;c bạn &lt;em&gt;&lt;strong&gt;&lt;a href=&quot;http://sgreen.vn/cach-lam-nha-tam-tre-huong-dan-chi-tiet-9298782.html&quot; target=&quot;_blank&quot;&gt;CLICK V&amp;Agrave;O Đ&amp;Acirc;Y&lt;/a&gt;&amp;nbsp;&amp;nbsp;&lt;/strong&gt;&lt;/em&gt;để&amp;nbsp;xem b&amp;agrave;i hướng dẫn nh&amp;eacute;&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n', 'Thumbnail-nha-tam-tre.jpg', 'nha-tam-tre', NULL, b'1', b'1', 2, 'NHÀ TĂM TRE | NHÀ TĂM TRE TPHCM | NHÀ TĂM TRE ĐẸP | NHÀ TĂM GIÁ RẺ', 'NƠI CUNG CẤP NHÀ TĂM TRE TPHCM - HÀ NỘI: nhà tăm tre cực đẹp với thiết kế bắt mắt, gồm đầy đủ sân vườn bể bơi ...được bố trí hài hòa về bố cục, màu sắc', 'Nhà tăm tre, nhà tăm tre tphcm, nhà tăm tphcm, mua nhà tăm tre, cách làm nhà tăm tre, mua nhà tăm ở đâu, shop bán nhà tăm, nhà bằng tăm tre, nhà tăm tre đẹp, nhà tăm tre giá rẻ, shop bán nhà tăm', '2016-07-28 09:00:23'),
	(6, 'THIỆP HANDMADE', '&lt;p&gt;thiệp sinh nhật đẹp,thiệp handmade, thiệp sinh nhật handmade, thiệp sinh nhật độc đ&amp;aacute;o, mua thiệp sinh nhật, mua b&amp;aacute;n thiệp sinh nhật, thiệp valentine handmade, thiệp noel, thiệp gi&amp;aacute;ng sinh handmade, thiệp 8/3 handmade, thiệp 20/10 handmade, thiệp handmade, thiệp handmade độc đ&amp;aacute;o, thiệp 20/11, thiệp tết handmade, thiệp tết độc đ&amp;aacute;o, shop b&amp;aacute;n thiệp&amp;nbsp;&lt;/p&gt;\r\n', 'Thumbnail-thiep-handmade.jpg', 'thiep-handmade', NULL, b'1', b'1', 3, 'MUA THIỆP SINH NHẬT ĐẸP TPHCM| THIỆP NOEL GIÁNG SINH|THIỆP VALENTINE', 'NƠI BÁN THIỆP SINH NHẬT GỖ THÔNG ĐEP TPHCM HÀ NỘI - thiết kế thiệp từ khoa mỹ thuật công nghiệp TPHCM, thiệp sinh nhật đẹp độc đáo, mua thiệp sinh nhật', 'Thiệp sinh nhật đẹp, thiệp sinh nhật tphcm, mua thiệp sinh nhật, thiệp sinh nhật handmade,thiệp valentine đẹp, thiệp valentine tphcm, thiệp noel-giáng sinh, thiệp 20/10, thiệp 8/3, thiệp tết đẹp, ', '2016-07-28 09:03:11'),
	(7, 'KHUNG ẢNH', '&lt;p&gt;NƠI B&amp;Aacute;N KHUNG ẢNH ĐỂ B&amp;Agrave;N VINTAGE CỰC ĐẸP TPHCM - H&amp;Agrave; NỘI : khung ảnh để b&amp;agrave;n, mua khung ảnh tphcm đẹp gi&amp;aacute; rẻ, c&amp;oacute; nhiều mẫu m&amp;atilde; cho c&amp;aacute;c bạn lựa chọn&lt;/p&gt;\r\n', 'Thumbnail-khung-anh.jpg', 'khung-anh', NULL, b'1', b'1', 5, 'KHUNG ẢNH HANDMADE |KHUNG ẢNH ĐẸP ĐỂ BÀN| MUA KHUNG ẢNH ĐẸP TPHCM', '"NƠI BÁN KHUNG ẢNH ĐỂ BÀN VINTAGE CỰC ĐẸP TPHCM - HÀ NỘI : khung ảnh để bàn, mua khung ảnh tphcm đẹp giá rẻ, có nhiều mẫu mã cho các bạn lựa chọn', 'khung ảnh để bàn đẹp, khung ảnh đẹp tphcm, mua khung ảnh đẹp, khung ảnh handmade, khung ảnh để bàn, mua khung ảnh ở đâu, shop bán khung ảnh, khung ảnh ở tphcm, khung ảnh vintage', '2016-07-28 09:07:53'),
	(9, 'ALBUM ẢNH, SCRAPBOOK', '&lt;h1&gt;MIOGIFTSHOP - ALBUM ẢNH ĐẸP | ALBUM ẢNH TPHCM | MUA ALBUM ẢNH |&amp;nbsp;SCRAPBOOK ĐẸP | SCRAPBOOK TPHCM | MUA SCRAPBOOK&amp;nbsp;&lt;/h1&gt;\r\n\r\n&lt;p&gt;&amp;nbsp; &amp;nbsp;Để c&amp;oacute; được 1 cuốn album ảnh handmade đẹp, bạn c&amp;oacute; thể thiết kế theo phong c&amp;aacute;ch sổ lưu niệm kết hợp h&amp;igrave;nh ảnh, trang tr&amp;iacute; thủ c&amp;ocirc;ng v&amp;agrave; viết nhật k&amp;yacute;, đ&amp;acirc;y sẽ l&amp;agrave; sản phẩm chứa đựng t&amp;igrave;nh cảm của ch&amp;iacute;nh bạn nhằm lưu giữ lịch sử c&amp;aacute; nh&amp;acirc;n v&amp;agrave; gia đ&amp;igrave;nh.&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp; Một cuốn album ảnh handmade c&amp;ograve;n gọi l&amp;agrave; &amp;nbsp;scrapbook-scrapbooking bắt đầu từ thế kỷ 15, tại Anh, sau đ&amp;oacute; rất sớm l&amp;agrave; ở Mỹ v&amp;agrave; hiện tại, l&amp;agrave;m nhật k&amp;yacute; ảnh-album h&amp;igrave;nh thủ c&amp;ocirc;ng (handmade) đ&amp;atilde; trở th&amp;agrave;nh một th&amp;uacute; ti&amp;ecirc;u khiển tương đối phổ biến tr&amp;ecirc;n thế giới v&amp;agrave; Việt Nam. Thậm ch&amp;iacute;, scrapbook trở th&amp;agrave;nh một ng&amp;agrave;nh c&amp;ocirc;ng nghiệp th&amp;uacute; vị tại Hoa Kỳ với h&amp;agrave;ng triệu người tham gia c&amp;ugrave;ng doanh số h&amp;agrave;ng triệu đ&amp;ocirc; la. Quả thật th&amp;uacute; vị!&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n', 'Thumbnail-album-anh-scrapbook.jpg', 'album-anh-scrapbook', NULL, b'1', b'1', 6, 'ALBUM ẢNH ĐẸP TPHCM| ALBUM HANDMADE|SCRAPBOOK ĐẸP |MUA SCRAPBOOK TPHCM', '"NƠI BÁN ALBUM ẢNH ĐẸP, SCRAPBOOK ĐẸP TPHCM HÀ NỘI 100% KHÁCH HÀNG HÀI LÒNG. Album ảnh đẹp tphcm, scrapbook đẹp, scrapbook tphcm, mua scrapbook, shop bán album ảnh', 'Album ảnh đẹp, Album ảnh tphcm, scrapbook đẹp, scrapbook tphcm, mua scrapbook, mua album ảnh, shop bán album ảnh, shop bán scrapbook', '2016-07-28 09:11:32');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;


-- Dumping structure for table miogift.page
DROP TABLE IF EXISTS `page`;
CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(1000) NOT NULL,
  `description` longtext NOT NULL,
  `slug` varchar(1000) NOT NULL,
  `seo_title` varchar(1000) DEFAULT NULL,
  `seo_desciption` varchar(1000) DEFAULT NULL,
  `seo_tags` varchar(1000) DEFAULT NULL,
  `is_active` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='static page';

-- Dumping data for table miogift.page: ~0 rows (approximately)
DELETE FROM `page`;
/*!40000 ALTER TABLE `page` DISABLE KEYS */;
/*!40000 ALTER TABLE `page` ENABLE KEYS */;


-- Dumping structure for table miogift.price
DROP TABLE IF EXISTS `price`;
CREATE TABLE IF NOT EXISTS `price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` int(11) NOT NULL DEFAULT '0',
  `sale_percent` int(3) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `from_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `to_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_price_product` (`product_id`),
  CONSTRAINT `FK_price_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='price of product by season';

-- Dumping data for table miogift.price: ~0 rows (approximately)
DELETE FROM `price`;
/*!40000 ALTER TABLE `price` DISABLE KEYS */;
INSERT INTO `price` (`id`, `price`, `sale_percent`, `comment`, `from_date`, `to_date`, `updated`, `product_id`) VALUES
	(1, 950000000, 5, 'test', '2016-07-06 00:00:00', '2016-07-31 00:00:00', '2016-07-24 17:57:30', 1);
/*!40000 ALTER TABLE `price` ENABLE KEYS */;


-- Dumping structure for table miogift.product
DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `description` longtext,
  `sort_description` varchar(1000) DEFAULT NULL,
  `image` varchar(1000) DEFAULT NULL,
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
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `code` (`code`),
  KEY `FK_product_category` (`category_id`),
  CONSTRAINT `FK_product_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table miogift.product: ~0 rows (approximately)
DELETE FROM `product`;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` (`id`, `title`, `description`, `sort_description`, `image`, `code`, `quantity_sold`, `price`, `slug`, `is_active`, `seo_title`, `seo_description`, `seo_tags`, `updated`, `category_id`) VALUES
	(1, 'product 1', '&lt;p&gt;sad sad sad&lt;/p&gt;\r\n', '       product one, one one one', 'Thumbnail-product-1.jpg', 'P1', 0, 1000000000, 'product-1', b'1', 'product 1', 'sadsad', 'dsadasd', '2016-07-24 18:27:32', 4);
/*!40000 ALTER TABLE `product` ENABLE KEYS */;


-- Dumping structure for table miogift.related_product
DROP TABLE IF EXISTS `related_product`;
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
DELETE FROM `related_product`;
/*!40000 ALTER TABLE `related_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `related_product` ENABLE KEYS */;


-- Dumping structure for table miogift.slug
DROP TABLE IF EXISTS `slug`;
CREATE TABLE IF NOT EXISTS `slug` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(1000) NOT NULL,
  `object` varchar(10) NOT NULL,
  `object_id` int(11) NOT NULL,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- Dumping data for table miogift.slug: ~9 rows (approximately)
DELETE FROM `slug`;
/*!40000 ALTER TABLE `slug` DISABLE KEYS */;
INSERT INTO `slug` (`id`, `slug`, `object`, `object_id`, `updated`) VALUES
	(1, 'home', 'home', 1, '2016-07-07 12:39:52'),
	(2, 'danbo-go', 'category', 4, '2016-07-28 08:57:09'),
	(3, 'nha-tam-tre', 'category', 5, '2016-07-28 09:00:23'),
	(4, 'thiep-handmade', 'category', 6, '2016-07-28 09:03:11'),
	(5, 'category-5', 'category', 8, '2016-07-20 13:30:39'),
	(6, 'album-anh-scrapbook', 'category', 9, '2016-07-28 09:11:32'),
	(10, 'khung-anh', 'category', 7, '2016-07-28 09:07:53'),
	(11, 'product-1', 'product', 1, '2016-07-23 15:57:06'),
	(12, 'hop-bai-tarot', 'category', 1, '2016-07-28 09:05:56');
/*!40000 ALTER TABLE `slug` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
