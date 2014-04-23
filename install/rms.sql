-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 23, 2014 at 12:42 PM
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
-- Table structure for table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'The unique identifier for the entry.',
  `title` varchar(255) NOT NULL COMMENT 'Title of the article.',
  `content` text NOT NULL COMMENT 'HTML content of the article.',
  `page_id` int(10) unsigned NOT NULL COMMENT 'The ID of the page for this article to be displayed on.',
  `index` int(10) unsigned NOT NULL COMMENT 'The order of this article on its given page.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title_page_id` (`title`,`page_id`),
  UNIQUE KEY `page_id_index` (`page_id`, `index`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='HTML content of the site''s articles.' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `content`, `page_id`, `index`, `created`, `modified`) VALUES
  (1, 'Lorem Ipsum', '<figure><img src="img/real.png" width="300" height="200" /></figure>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>', 1, 0, '2014-04-23 19:21:06', '2014-04-23 19:21:06'),
  (2, 'Lorem Ipsum Dolor', '<figure><img src="img/sim.png" width="300" height="200" /></figure>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>', 1, 1, '2014-04-23 19:21:06', '2014-04-23 19:21:06'),
  (3, 'Lorem Ipsum', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>', 2, 0, '2014-04-23 19:21:06', '2014-04-23 19:21:06'),
  (4, 'Lorem Ipsum Dolor', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p> \r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>', 3, 0, '2014-04-23 19:21:06', '2014-04-23 19:21:06');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='The content pages of the RMS.' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `menu`, `index`, `created`, `modified`) VALUES
  (1, 'About Us', 'About', 0, '2014-04-23 15:21:06', '2014-04-23 15:21:06'),
  (2, 'Our Research', 'Research', 3, '2014-04-23 15:21:06', '2014-04-23 15:21:06'),
  (3, 'Contact Information', 'Contact', 5, '2014-04-23 15:21:06', '2014-04-23 15:21:06');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;