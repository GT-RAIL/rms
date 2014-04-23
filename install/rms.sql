-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 23, 2014 at 10:49 AM
-- Server version: 5.5.35-1ubuntu1
-- PHP Version: 5.5.9-1ubuntu4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rms`
--

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'The unique identifier for the entry.',
  `title` varchar(16) NOT NULL COMMENT 'The title of the page.',
  `menu` varchar(16) NOT NULL COMMENT 'The menu entry for the page.',
  `index` int(10) unsigned NOT NULL COMMENT 'The index of the page.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  UNIQUE KEY `menu` (`menu`),
  UNIQUE KEY `index` (`index`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='The content pages of the RMS.' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
