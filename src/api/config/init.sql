-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 06, 2012
-- Server version: 5.5.28
-- PHP Version: 5.3.10-1ubuntu3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
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
  `artid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the article.',
  `title` varchar(255) NOT NULL COMMENT 'Title of the article.',
  `content` text NOT NULL COMMENT 'HTML content of the article.',
  `pageid` int(11) NOT NULL COMMENT 'The ID of the page for this article to be displayed on.',
  `index` int(11) NOT NULL COMMENT 'The order of this article on its given page.',
  PRIMARY KEY (`artid`),
  UNIQUE KEY `title` (`title`,`pageid`),
  KEY `pageid` (`pageid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='HTML content of the site''s articles.' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`artid`, `title`, `content`, `pageid`, `index`) VALUES
(1, 'Lorem Ipsum', '<figure><img src="img/real.png" width="300" height="200" /></figure>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>', 1, 0),
(2, 'Lorem Ipsum Dolor', '<figure><img src="img/sim.png" width="300" height="200" /></figure>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>', 1, 1),
(3, 'Lorem Ipsum', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>', 2, 0),
(4, 'Lorem Ipsum Dolor', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p> \r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `conditions`
--

CREATE TABLE IF NOT EXISTS `conditions` (
  `condid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the condition.',
  `studyid` int(11) NOT NULL COMMENT 'Unique identifier for the study.',
  `name` varchar(255) NOT NULL COMMENT 'Name of the condition.',
  `intid` int(11) NOT NULL COMMENT 'Unique identifier for the interface used in this condition.',
  PRIMARY KEY (`condid`),
  UNIQUE KEY `studyid_name_intid` (`studyid`,`name`,`intid`),
  KEY `studyid` (`studyid`),
  KEY `intid` (`intid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='A list of conditions for a given study.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `content_pages`
--

CREATE TABLE IF NOT EXISTS `content_pages` (
  `pageid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the page.',
  `title` varchar(255) NOT NULL COMMENT 'The title of the page.',
  `menu` varchar(255) NOT NULL COMMENT 'The name of the menu item for this page.',
  `index` int(11) NOT NULL COMMENT 'The index in the main menu.',
  `js` varchar(255) DEFAULT NULL COMMENT 'JS file to be included with this contect page.',
  PRIMARY KEY (`pageid`),
  UNIQUE KEY `title` (`title`),
  UNIQUE KEY `menu` (`menu`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `content_pages`
--

INSERT INTO `content_pages` (`pageid`, `title`, `menu`, `index`, `js`) VALUES
(1, 'About', 'About', 0, NULL),
(2, 'Our Research', 'Research', 3, NULL),
(3, 'Contact Information', 'Contact', 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `environments`
--

CREATE TABLE IF NOT EXISTS `environments` (
  `envid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the environment.',
  `protocol` enum('ws://','wss://') NOT NULL COMMENT 'WebSocket server protocol.',
  `envaddr` varchar(255) NOT NULL COMMENT 'Address of the robot environment''s ROS server.',
  `port` int(11) NOT NULL COMMENT 'Port of the rosbridge server.',
  `mjpeg` varchar(255) NOT NULL COMMENT 'The MJPEG server host address.',
  `mjpegport` int(11) NOT NULL COMMENT 'The MJPEG server port.',
  `enabled` tinyint(1) NOT NULL COMMENT 'If this environment is currently enabled.',
  PRIMARY KEY (`envid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Configuration information for robot environments.' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `environments`
--

INSERT INTO `environments` (`envid`, `protocol`, `envaddr`, `port`, `mjpeg`, `mjpegport`, `enabled`) VALUES
(1, 'ws://', 'localhost', 9090, 'localhost', '8080', 1),
(2, 'ws://', 'localhost', 9090, 'localhost', '8080', 1);

-- --------------------------------------------------------

--
-- Table structure for table `environment_interface_pairs`
--

CREATE TABLE IF NOT EXISTS `environment_interface_pairs` (
  `pairid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the pairing.',
  `envid` int(11) NOT NULL COMMENT 'Unique identifier for the envionmnet.',
  `intid` int(11) NOT NULL COMMENT 'Unique identifier for the interface.',
  PRIMARY KEY (`pairid`),
  UNIQUE KEY `envid_intid` (`envid`,`intid`),
  KEY `envid` (`envid`),
  KEY `intid` (`intid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Matching of interfaces to environments.' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `environment_interface_pairs`
--

INSERT INTO `environment_interface_pairs` (`pairid`, `envid`, `intid`) VALUES
(1, 1, 1),
(2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `experiments`
--

CREATE TABLE IF NOT EXISTS `experiments` (
  `expid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the experiment.',
  `userid` int(11) NOT NULL COMMENT 'Unique identifier for the user.',
  `condid` int(11) NOT NULL COMMENT 'Unique identifier for the condition.',
  `envid` int(11) NOT NULL COMMENT 'Unique identifier for the envionmnet.',
  `start` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Start time for the experiment.',
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'End time for the experiment.',
  PRIMARY KEY (`expid`),
  UNIQUE KEY `userid_condid` (`userid`,`condid`),
  UNIQUE KEY `envid_start_end` (`envid`,`start`,`end`),
  KEY `envid` (`envid`),
  KEY `userid` (`userid`),
  KEY `condid` (`condid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Pairs of users and conditons that make up an experimental trial.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `interfaces`
--

CREATE TABLE IF NOT EXISTS `interfaces` (
  `intid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the interface.',
  `name` varchar(255) NOT NULL COMMENT 'Name of the interface (for use in menus).',
  `location` varchar(255) NOT NULL COMMENT 'Directory name within the interface API folder.',
  PRIMARY KEY (`intid`),
  UNIQUE KEY `location` (`location`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Information about the different interface types.' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `interfaces`
--

INSERT INTO `interfaces` (`intid`, `name`, `location`) VALUES
(1, 'Basic Teleop', 'basic'),
(2, 'Nav2D Interface', 'simple_nav2d'),
(3, 'Interactive Markers', 'markers');

-- --------------------------------------------------------

--
-- Table structure for table `keyboard_teleoperations`
--

CREATE TABLE IF NOT EXISTS `keyboard_teleoperations` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the teleop.',
  `envid` int(11) NOT NULL,
  `label` varchar(255) NOT NULL COMMENT 'Label for the widget.',
  `throttle` float NOT NULL COMMENT 'Throttle constant for the drive command.',
  `twist` varchar(255) NOT NULL COMMENT 'Twist topic for the drive command.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `envid_label_throttle_twist` (`envid`,`label`,`throttle`,`twist`),
  KEY `envid` (`envid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Keyboard teleoperation information.' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `keyboard_teleoperations`
--

INSERT INTO `keyboard_teleoperations` (`id`, `envid`, `label`, `throttle`, `twist`) VALUES
(1, 1, 'Keyboard Teleop', 0.75, '/base_controller/command'),
(2, 2, 'Keyboard Teleop', 0.75, '/base_controller/command');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `logid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the log entry.',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp of the log entry.',
  `uri` varchar(255) NOT NULL COMMENT 'Requested page for the log entry.',
  `addr` varchar(255) NOT NULL COMMENT 'Remote address of the requester.',
  `entry` varchar(255) NOT NULL COMMENT 'The log entry.',
  PRIMARY KEY (`logid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='System log.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `maps`
--

CREATE TABLE IF NOT EXISTS `maps` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the map.',
  `envid` int(11) NOT NULL COMMENT 'Unique identifier for the environment.',
  `label` varchar(255) NOT NULL COMMENT 'Label for the widget.',
  `topic` varchar(255) NOT NULL COMMENT 'Topic of the map (nav_msgs/OccupancyGrid)',
  `continuous` tinyint(1) NOT NULL COMMENT 'If the map should be continuously loaded or loaded and saved once.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `envid_label_topic_continuous` (`envid`,`label`,`topic`,`continuous`),
  KEY `envid` (`envid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Map 2D widget info.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mjpeg_streams`
--

CREATE TABLE IF NOT EXISTS `mjpeg_streams` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the stream.',
  `envid` int(11) NOT NULL COMMENT 'Unique identifier for the environment.',
  `label` varchar(255) NOT NULL,
  `topic` varchar(255) NOT NULL COMMENT 'ROS topic name of the video stream.',
  PRIMARY KEY (`id`),
  KEY `envid` (`envid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Connection information for ROS topics published from a MJPEG server.' AUTO_INCREMENT=7 ;

--
-- Dumping data for table `mjpeg_streams`
--

INSERT INTO `mjpeg_streams` (`id`, `envid`, `label`, `topic`) VALUES
(1, 1, 'Overhead East View', '/logitech_9000_camera1/image_raw'),
(2, 1, 'Overhead West View', '/logitech_9000_camera2/image_raw'),
(3, 1, 'Robot View', '/youbot_base_kinect/youbot_base_kinect/rgb/image_raw'),
(4, 2, 'Overhead East View', '/logitech_9000_camera1/image_raw'),
(5, 2, 'Overhead West View', '/logitech_9000_camera2/image_raw'),
(6, 2, 'Robot View', '/prosilica/image_raw');

-- --------------------------------------------------------

--
-- Table structure for table `interactive_markers`
--

CREATE TABLE IF NOT EXISTS `interactive_markers` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the interactive marker.',
  `envid` int(11) NOT NULL COMMENT 'The environment this widget belongs to.',
  `label` varchar(255) NOT NULL COMMENT 'A label for this widget.',
  `topic` varchar(255) NOT NULL COMMENT 'The interactive marker topic to listen to.',
  `fixed_frame` varchar(255) NOT NULL COMMENT 'The fixed frame for the TF tree.',
  PRIMARY KEY (`id`),
  KEY `envid` (`envid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='The interactive marker widget.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `navigations`
--

CREATE TABLE IF NOT EXISTS `navigations` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the navigation widget.',
  `envid` int(11) NOT NULL COMMENT 'Unique identifier for the environment.',
  `label` varchar(255) NOT NULL COMMENT 'Label for the widget.',
  `mapid` int(11) NOT NULL COMMENT 'ID number for the map widget to use with this nav (in the ''maps'' table).',
  `actionserver` varchar(255) NOT NULL COMMENT 'Name of the action server (e.g., /move_base)',
  `action` varchar(255) NOT NULL COMMENT 'Basename of the action (e.g., move_base_msgs/MoveBaseAction)',
  PRIMARY KEY (`id`),
  KEY `envid` (`envid`),
  KEY `mapid` (`mapid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Nav 2D widget info.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

CREATE TABLE IF NOT EXISTS `slides` (
  `slideid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the slide.',
  `img` varchar(255) NOT NULL COMMENT 'Name of the file in the img/slides folder.',
  `caption` varchar(255) NOT NULL COMMENT 'Caption text for the slide.',
  `index` int(11) NOT NULL COMMENT 'Slide index in the slideshow.',
  PRIMARY KEY (`slideid`),
  UNIQUE KEY `img` (`img`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Slides used in the slideshow.' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `slides`
--

INSERT INTO `slides` (`slideid`, `img`, `caption`, `index`) VALUES
(1, 'youbot.jpg', 'Lorem ipsum dolor sit amet.', 5),
(2, 'pr2.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. ', 3),
(3, 'youbot_sim.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum diam.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `studies`
--

CREATE TABLE IF NOT EXISTS `studies` (
  `studyid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the study.',
  `name` varchar(255) NOT NULL COMMENT 'Name of the study.',
  `description` varchar(255) NOT NULL COMMENT 'Description of the study.',
  `start` date NOT NULL COMMENT 'Start date of the study.',
  `end` date NOT NULL COMMENT 'End date of the study.',
  PRIMARY KEY (`studyid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='A table to hold information about different studies.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `study_logs`
--

CREATE TABLE IF NOT EXISTS `study_logs` (
  `logid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the log entry.',
  `expid` int(11) NOT NULL COMMENT 'Unique identifier for the experiment.',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp for the entry.',
  `entry` varchar(255) NOT NULL COMMENT 'The log entry.',
  PRIMARY KEY (`logid`),
  KEY `expid` (`expid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='A table to hold log information during studies.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

CREATE TABLE IF NOT EXISTS `user_accounts` (
  `userid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the user.',
  `username` varchar(255) NOT NULL COMMENT 'Username for the user.',
  `password` varchar(255) NOT NULL COMMENT 'Encrypted password for the user.',
  `salt` varchar(255) NOT NULL COMMENT 'Random string used to salt the passwords.',
  `firstname` varchar(255) NOT NULL COMMENT 'User''s first name.',
  `lastname` varchar(255) NOT NULL COMMENT 'User''s last name.',
  `email` varchar(255) NOT NULL COMMENT 'User''s email address.',
  `type` enum('admin','user') NOT NULL COMMENT 'Type of user.',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Main user accounts table.' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user_accounts`
--

INSERT INTO `user_accounts` (`userid`, `username`, `password`, `salt`, `firstname`, `lastname`, `email`, `type`) VALUES
(1, 'admin', 'e2073df5dd9c723211aa7d00147f63491f0168ef', 'Hk8aqtLbAAK5IGaD', 'Admin', 'Account', 'admin@my.rms', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `version`
--

CREATE TABLE IF NOT EXISTS `version` (
  `version` varchar(255) NOT NULL COMMENT 'Version number of this remote lab database system.',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='A table to hold the version number of this database system.';

--
-- Dumping data for table `version`
--

INSERT INTO `version` (`version`) VALUES
('0.3.0');

-- --------------------------------------------------------

--
-- Table structure for table `widgets`
--

CREATE TABLE IF NOT EXISTS `widgets` (
  `widgetid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the widget.',
  `name` varchar(255) NOT NULL COMMENT 'Name of the widget.',
  `table` varchar(255) NOT NULL COMMENT 'The SQL table containing information for an instance of this widget. ',
  PRIMARY KEY (`widgetid`),
  UNIQUE KEY `table` (`table`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='RMS Widgets.' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `widgets`
--

INSERT INTO `widgets` (`widgetid`, `name`, `table`) VALUES
(1, 'MJPEG Stream', 'mjpeg_streams'),
(2, 'Keyboard Teleop', 'keyboard_teleoperations'),
(3, 'Map 2D', 'maps'),
(4, '2D Navigation', 'navigations'),
(5, 'Interactive Markers', 'interactive_markers');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`pageid`) REFERENCES `content_pages` (`pageid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `conditions`
--
ALTER TABLE `conditions`
  ADD CONSTRAINT `conditions_ibfk_1` FOREIGN KEY (`studyid`) REFERENCES `studies` (`studyid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `conditions_ibfk_2` FOREIGN KEY (`intid`) REFERENCES `interfaces` (`intid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `environment_interface_pairs`
--
ALTER TABLE `environment_interface_pairs`
  ADD CONSTRAINT `environment_interface_pairs_ibfk_1` FOREIGN KEY (`envid`) REFERENCES `environments` (`envid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `environment_interface_pairs_ibfk_2` FOREIGN KEY (`intid`) REFERENCES `interfaces` (`intid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `experiments`
--
ALTER TABLE `experiments`
  ADD CONSTRAINT `experiments_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_accounts` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `experiments_ibfk_2` FOREIGN KEY (`condid`) REFERENCES `conditions` (`condid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `experiments_ibfk_3` FOREIGN KEY (`envid`) REFERENCES `environments` (`envid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `keyboard_teleoperations`
--
ALTER TABLE `keyboard_teleoperations`
  ADD CONSTRAINT `keyboard_teleoperations_ibfk_1` FOREIGN KEY (`envid`) REFERENCES `environments` (`envid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `maps`
--
ALTER TABLE `maps`
  ADD CONSTRAINT `maps_ibfk_1` FOREIGN KEY (`envid`) REFERENCES `environments` (`envid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mjpeg_streams`
--
ALTER TABLE `mjpeg_streams`
  ADD CONSTRAINT `mjpeg_streams_ibfk_1` FOREIGN KEY (`envid`) REFERENCES `environments` (`envid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `interactive_markers`
--
ALTER TABLE `interactive_markers`
  ADD CONSTRAINT `interactive_markers_ibfk_1` FOREIGN KEY (`envid`) REFERENCES `environments` (`envid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `navigations`
--
ALTER TABLE `navigations`
  ADD CONSTRAINT `navigations_ibfk_1` FOREIGN KEY (`envid`) REFERENCES `environments` (`envid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `navigations_ibfk_2` FOREIGN KEY (`mapid`) REFERENCES `maps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `study_logs`
--
ALTER TABLE `study_logs`
  ADD CONSTRAINT `study_logs_ibfk_1` FOREIGN KEY (`expid`) REFERENCES `experiments` (`expid`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
