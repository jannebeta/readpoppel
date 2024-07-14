-- --------------------------------------------------------
-- Verkkotietokone:              127.0.0.1
-- Palvelinversio:               10.1.25-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Versio:              10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping structure for taulu poppeli.banned_usernames
CREATE TABLE IF NOT EXISTS `banned_usernames` (
  `username` varchar(255) NOT NULL,
  PRIMARY KEY (`username`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Dumping data for table poppeli.banned_usernames: ~0 rows (suunnilleen)
/*!40000 ALTER TABLE `banned_usernames` DISABLE KEYS */;
INSERT INTO `banned_usernames` (`username`) VALUES
	('paska');
/*!40000 ALTER TABLE `banned_usernames` ENABLE KEYS */;

-- Dumping structure for taulu poppeli.invites
CREATE TABLE IF NOT EXISTS `invites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp_created` int(15) NOT NULL,
  `invite_code` varchar(255) NOT NULL,
  `is_used` enum('0','1') NOT NULL DEFAULT '0',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `nu_mail` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- Dumping data for table poppeli.invites: 3 rows
/*!40000 ALTER TABLE `invites` DISABLE KEYS */;
INSERT INTO `invites` (`id`, `timestamp_created`, `invite_code`, `is_used`, `creator_id`, `nu_mail`) VALUES
	(6, 1454258641, 'VQT49O-C94I5Y-ILDAQW-SLG7VT', '0', 0, ''),
	(8, 1454258669, 'OYKHXM-71VRCL-Y8G67T-32VUG6', '0', 0, '0'),
	(30, 1459008459, 'XP8IOU-DX7SJG-D6XG9V-FASP12', '0', 0, '');
/*!40000 ALTER TABLE `invites` ENABLE KEYS */;

-- Dumping structure for taulu poppeli.paper_brands
CREATE TABLE IF NOT EXISTS `paper_brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brandCode` varchar(255) NOT NULL,
  `brandName` varchar(255) NOT NULL,
  `mainColor` varchar(7) NOT NULL,
  `secondColor` varchar(7) NOT NULL,
  `uAlternativeMethod` enum('0','1') NOT NULL DEFAULT '1',
  `logoIMG` varchar(255) NOT NULL,
  `enabledInMobile` enum('0','1') NOT NULL DEFAULT '0',
  `enabledInDesktop` int(11) NOT NULL DEFAULT '1',
  `platform` enum('readpoppel_envsafe','visiolink_epages') NOT NULL DEFAULT 'visiolink_epages',
  `archived` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- Dumping data for table poppeli.paper_brands: 37 rows
/*!40000 ALTER TABLE `paper_brands` DISABLE KEYS */;
INSERT INTO `paper_brands` (`id`, `brandCode`, `brandName`, `mainColor`, `secondColor`, `uAlternativeMethod`, `logoIMG`, `enabledInMobile`, `enabledInDesktop`, `platform`, `archived`) VALUES
	(1, 'pohjalainen', 'Pohjalainen', '#FF9900', '#ef7600', '1', 'pohjalainen_logo.png', '1', 1, 'visiolink_epages', '0'),
	(2, 'ilkka', 'Ilkka', '#81d8f4', '#00659b', '0', 'ilkka_logo.png', '1', 0, 'visiolink_epages', '0'),
	(3, 'iltalehti', 'Iltalehti', '#e5e5e5', '#c41c32', '1', 'iltalehti_logo.png', '1', 1, 'visiolink_epages', '0'),
	(4, 'iltasanomat', 'Ilta-sanomat', '#e5e5e5', '#ba242f', '1', 'is_logo.png', '1', 1, 'visiolink_epages', '1'),
	(5, 'kaleva', 'Kaleva', '#e5e5e5', '#ffffff', '1', 'kaleva_logo.png', '0', 1, 'visiolink_epages', '1'),
	(6, 'kauppalehti', 'Kauppalehti', '#e5e5e5', 'gray', '1', 'kl_logo.png', '1', 1, 'visiolink_epages', '1'),
	(7, 'keskisuomalainen', 'Keskisuomalainen', '#e5e5e5', '#1371b9', '1', 'ks_logo.png', '0', 1, 'visiolink_epages', '0'),
	(8, 'kainuunsanomat', 'Kainuun Sanomat', '#000000', '#16875b', '1', 'kain_logo.png', '0', 1, 'visiolink_epages', '1'),
	(9, 'pohjolansanomat', 'Pohjolan Sanomat', '#e5e5e5', '#000000', '1', 'pohjs_logo.png', '0', 1, 'visiolink_epages', '1'),
	(10, 'ylakainuu', 'Ylä-Kainuu', '#e5e5e5', '#1e70b8', '1', 'ylakainuu_logo.png', '0', 1, 'visiolink_epages', '1'),
	(11, 'hameensanomat', 'Hämeen Sanomat', '#ffffff', '#000000', '1', 'hms_logo.png', '0', 1, 'visiolink_epages', '1'),
	(12, 'lapinkansa', 'Lapin Kansa', '#e5e5e5', '#0083d6', '1', 'lapk_logo.gif', '1', 1, 'visiolink_epages', '1'),
	(13, 'karjalainen', 'Karjalainen', '#e5e5e5', '#e2001a', '1', 'krl_logo.png', '0', 1, 'visiolink_epages', '1'),
	(14, 'kuhmolainen', 'Kuhmolainen', '#e5e5e5', '#1e70b8', '1', 'kuhmolainen_logo.png', '0', 1, 'visiolink_epages', '1'),
	(15, 'turunsanomat', 'Turun Sanomat', '#6c7278', '#000000', '1', 'ts_logo.png', '0', 1, 'visiolink_epages', '0'),
	(16, 'savonsanomat', 'Savon Sanomat', '#e7e4df', '#f4b832', '1', 'ss_logo.png', '0', 1, 'visiolink_epages', '1'),
	(17, 'satakunnankansa', 'Satakunnan Kansa', '#e5e5e5', '#2059aa', '1', 'sk_logo.png', '1', 1, 'visiolink_epages', '1'),
	(18, 'viiskunta', 'Viiskunta', '#e5e5e5', '#08549a', '1', 'vk_logo.png', '0', 1, 'visiolink_epages', '0'),
	(19, 'lisalmen', 'Iisalmen Sanomat', '#6c7278', '#000000', '0', 'iisalmensanomat_logo.png', '0', 1, 'visiolink_epages', '0'),
	(20, 'komiatlehti', 'Komiat', '#ffffff', '#000000', '1', 'komiat_logo.png', '0', 0, 'visiolink_epages', '0'),
	(21, 'nastola', 'Nastola-lehti', '#0095da', '#0095da', '1', '', '0', 1, 'visiolink_epages', '0'),
	(22, 'vasabladet', 'Vasabladet', '#419d36', '#419d36', '1', 'vbl.gif', '0', 1, 'visiolink_epages', '0'),
	(23, 'jarviseutulehti', 'Järviseutu', '#01a1d9', '#01a1d9', '1', '', '0', 1, 'visiolink_epages', '0'),
	(35, 'uusilahti', 'Uusi Lahti', '#f7a821', '#ffffff', '1', '', '0', 1, 'visiolink_epages', '0'),
	(26, 'sotkamo', 'Sotkamo-lehti', '#396cbb', '#ffffff', '1', 'logo-sotkamolehti.png', '0', 1, 'visiolink_epages', '1'),
	(27, 'jurvansanomat', 'Jurvan Sanomat', '#007ec2', '#ffffff', '1', '', '0', 1, 'visiolink_epages', '0'),
	(28, 'epari', 'Epari', '#fab80a', '#ffffff', '1', '', '0', 1, 'visiolink_epages', '0'),
	(29, 'sisa', 'Sisä-Suomen Lehti', '#23b1f0', '#ffffff', '1', 'sisa-suomen-lehti.png', '0', 1, 'visiolink_epages', '0'),
	(30, 'hankasalmen', 'Hankasalmen Sanomat', '#4c8ac9', '#ffffff', '1', 'hankasalmen-sanomat', '0', 1, 'visiolink_epages', '0'),
	(31, 'viitasaaren', 'Viitasaaren seutu', '#e32643', '#ffffff', '1', 'viitasaaren-seutu.png', '0', 1, 'visiolink_epages', '0'),
	(32, 'viispiikkinen', 'Viispiikkinen', '#cce40f', '#221f20', '1', 'viispiikkinen.png', '0', 1, 'visiolink_epages', '0'),
	(33, 'sampo', 'Sampo', '#221f20', '#ffffff', '1', 'sampo.png', '0', 1, 'visiolink_epages', '0'),
	(34, 'laukaa', 'Laukaa-Konnevesi', '#7193a8', '#c9252b', '1', 'laukaa-konnevesi.png', '0', 1, 'visiolink_epages', '0'),
	(36, 'hollolan', 'Hollolan sanomat', '#ffffff', '#ffffff', '1', '', '0', 1, 'visiolink_epages', '0'),
	(37, 'warkauden', 'Warkauden sanomat', '#ffffff', '#ffffff', '1', '', '0', 1, 'visiolink_epages', '0'),
	(39, 'orimattilan', 'Orimattilan Aluelehti', '#ffffff', '#ffffff', '1', '', '0', 1, 'visiolink_epages', '0'),
	(41, 'suupohjansanomat', 'Suupohjan Sanomat', '#ffffff', '#ffffff', '1', '', '0', 1, 'visiolink_epages', '0');
/*!40000 ALTER TABLE `paper_brands` ENABLE KEYS */;

-- Dumping structure for taulu poppeli.user_accounts
CREATE TABLE IF NOT EXISTS `user_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `registered_ip` varchar(255) NOT NULL,
  `permission` enum('ADMINISTRATOR','MODERATOR','READER') NOT NULL DEFAULT 'READER',
  `email` varchar(255) NOT NULL,
  `timestamp_registered` int(20) NOT NULL,
  `accessCount` int(11) NOT NULL DEFAULT '0',
  `firstName` varchar(255) NOT NULL,
  `secondName` varchar(255) NOT NULL,
  `accountDisabled` enum('0','1') NOT NULL DEFAULT '0',
  `welcomeHelp` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


-- Dumping structure for taulu poppeli.user_readhistory
CREATE TABLE IF NOT EXISTS `user_readhistory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `paperBrand` varchar(255) NOT NULL,
  `paperId` int(11) NOT NULL,
  `pagesReaded` int(11) NOT NULL,
  `timestamp_start` int(13) NOT NULL,
  `timestamp_end` int(13) NOT NULL,
  `open_count` int(11) NOT NULL,
  `currentPage` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;



/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
