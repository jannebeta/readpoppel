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



-- Dumping structure for taulu poppeli.papers
CREATE TABLE IF NOT EXISTS `papers` (
  `paperBrand` varchar(255) NOT NULL,
  `paperID` int(11) NOT NULL,
  `paperPublished` int(12) NOT NULL,
  `paperTitle` varchar(255) NOT NULL,
  `pageCount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`paperBrand`,`paperID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- Tietojen vientiä ei oltu valittu.

-- Dumping structure for taulu poppeli.papers_metadatas
CREATE TABLE IF NOT EXISTS `papers_metadatas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paperBrand` varchar(255) NOT NULL,
  `paperId` int(15) NOT NULL,
  `additionalImageURL` varchar(255) DEFAULT NULL,
  `page_number` int(15) DEFAULT NULL,
  `phrase_title` text NOT NULL,
  `phrase_text` text NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=403440 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- Tietojen vientiä ei oltu valittu.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
