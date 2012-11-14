-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 14, 2012 at 01:35 PM
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
  `pageindex` int(11) NOT NULL COMMENT 'The order of this article on its given page.',
  PRIMARY KEY (`artid`),
  UNIQUE KEY `title` (`title`,`pageid`),
  KEY `pageid` (`pageid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='HTML content of the site''s articles.' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`artid`, `title`, `content`, `pageid`, `pageindex`) VALUES
(1, 'Lorem Ipsum', '<figure><img src="img/real.png" width="300" height="200" /></figure>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>', 1, 0),
(2, 'Lorem Ipsum Dolor', '<figure><img src="img/sim.png" width="300" height="200" /></figure>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>', 1, 1),
(3, 'Lorem Ipsum', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>', 2, 0),
(4, 'Lorem Ipsum Dolor', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p> \r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `content_pages`
--

CREATE TABLE IF NOT EXISTS `content_pages` (
  `pageid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the page.',
  `title` varchar(255) NOT NULL COMMENT 'The title of the page.',
  `menu_name` varchar(32) NOT NULL COMMENT 'The name of the menu item for this page.',
  `menu_index` int(11) NOT NULL COMMENT 'The index in the main menu.',
  `js` varchar(255) DEFAULT NULL COMMENT 'JS file to be included with this contect page.',
  PRIMARY KEY (`pageid`),
  UNIQUE KEY `title` (`title`),
  UNIQUE KEY `menu_name` (`menu_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `content_pages`
--

INSERT INTO `content_pages` (`pageid`, `title`, `menu_name`, `menu_index`, `js`) VALUES
(1, 'About', 'About', 0, NULL),
(2, 'Our Research', 'Research', 3, NULL),
(3, 'Contact Information', 'Contact', 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `environments`
--

CREATE TABLE IF NOT EXISTS `environments` (
  `envid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the environment.',
  `envaddr` varchar(255) NOT NULL COMMENT 'Address of the robot environment''s ROS server.',
  `type` enum('simulation','physical') NOT NULL COMMENT 'The type of robot environment.',
  `notes` text COMMENT 'Notes about the enironment.',
  `enabled` tinyint(1) NOT NULL COMMENT 'If this environment is currently enabled.',
  PRIMARY KEY (`envid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Configuration information for robot environments.' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `environments`
--

INSERT INTO `environments` (`envid`, `envaddr`, `type`, `notes`, `enabled`) VALUES
(1, 'localhost', 'simulation', 'YouBot simulator.', 1),
(2, 'localhost', 'simulation', 'PR2 Simulator.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `environment_interfaces`
--

CREATE TABLE IF NOT EXISTS `environment_interfaces` (
  `pairid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the pairing.',
  `envid` int(11) NOT NULL COMMENT 'Unique identifier for the envionmnet.',
  `intid` int(11) NOT NULL COMMENT 'Unique identifier for the interface.',
  PRIMARY KEY (`pairid`),
  UNIQUE KEY `envid` (`envid`,`intid`),
  KEY `envid_2` (`envid`),
  KEY `intid` (`intid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Matching of interfaces to environments.' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `environment_interfaces`
--

INSERT INTO `environment_interfaces` (`pairid`, `envid`, `intid`) VALUES
(1, 1, 1),
(2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `interfaces`
--

CREATE TABLE IF NOT EXISTS `interfaces` (
  `intid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the interface.',
  `name` varchar(255) NOT NULL COMMENT 'Name of the interface (for use in menus).',
  `location` varchar(255) NOT NULL COMMENT 'Directory name within the interface folder.',
  PRIMARY KEY (`intid`),
  UNIQUE KEY `location` (`location`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Information about the different interface types.' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `interfaces`
--

INSERT INTO `interfaces` (`intid`, `name`, `location`) VALUES
(1, 'Basic Teleop', 'basic'),
(2, 'Nav2D Interface', 'simple_nav2d');

-- --------------------------------------------------------

--
-- Table structure for table `javascript_files`
--

CREATE TABLE IF NOT EXISTS `javascript_files` (
  `fileid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the file.',
  `url` varchar(511) NOT NULL COMMENT 'URL to the file that will be downloaded.',
  `path` varchar(511) NOT NULL COMMENT 'Local file path (including the file name) relative to the server root for the file.',
  PRIMARY KEY (`fileid`),
  UNIQUE KEY `url` (`url`),
  UNIQUE KEY `path` (`path`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='A list of Javascript files that are to be downloaded and maintained.' AUTO_INCREMENT=7 ;

--
-- Dumping data for table `javascript_files`
--

INSERT INTO `javascript_files` (`fileid`, `url`, `path`) VALUES
(1, 'https://raw.github.com/RobotWebTools/rosjs/fuerte-devel/dist/ros_bundle.min.js', 'js/ros/ros_bundle.min.js'),
(2, 'https://raw.github.com/RobotWebTools/keyboardteleopjs/fuerte-devel/keyboardteleop.js', 'js/ros/widgets/keyboardteleop.js'),
(3, 'https://raw.github.com/RobotWebTools/mjpegcanvasjs/fuerte-devel/mjpegcanvas.js', 'js/ros/widgets/mjpegcanvas.js'),
(4, 'https://raw.github.com/RobotWebTools/map2djs/fuerte-devel/map.js', 'js/ros/widgets/map.js'),
(5, 'https://raw.github.com/RobotWebTools/actionlibjs/fuerte-devel/actionclient.js', 'js/ros/actionclient.js'),
(6, 'https://raw.github.com/RobotWebTools/nav2djs/fuerte-devel/nav2d.js', 'js/ros/widgets/nav2d.js');

-- --------------------------------------------------------

--
-- Table structure for table `keyboard_teleop`
--

CREATE TABLE IF NOT EXISTS `keyboard_teleop` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the teleop.',
  `envid` int(11) NOT NULL,
  `label` varchar(255) NOT NULL COMMENT 'Label for the widget.',
  `throttle` double NOT NULL COMMENT 'Throttle constant for the drive command.',
  `twist` varchar(255) NOT NULL COMMENT 'Twist topic for the drive command.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `envid_2` (`envid`,`label`,`throttle`,`twist`),
  KEY `envid` (`envid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Keyboard teleoperation information.' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `keyboard_teleop`
--

INSERT INTO `keyboard_teleop` (`id`, `envid`, `label`, `throttle`, `twist`) VALUES
(1, '1', 'Keyboard Teleop', 0.75, '/base_controller/command'),
(2, '2', 'Keyboard Teleop', 0.75, '/base_controller/command');

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `logid` int(32) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the log entry.',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp of the log entry.',
  `request_uri` varchar(255) NOT NULL COMMENT 'Requested page for the log entry.',
  `remote_addr` varchar(255) NOT NULL COMMENT 'Remote address of the requester.',
  `entry` text NOT NULL COMMENT 'The log entry.',
  PRIMARY KEY (`logid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Site log.' AUTO_INCREMENT=1 ;

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
  UNIQUE KEY `envid` (`envid`,`label`,`topic`,`continuous`),
  KEY `envid_2` (`envid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Map 2D widget info.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `navigations`
--

CREATE TABLE IF NOT EXISTS `navigations` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the navigation widget.',
  `envid` int(11) NOT NULL COMMENT 'Unique identifier for the environment.',
  `label` varchar(255) NOT NULL COMMENT 'Label for the widget.',
  `mapid` int(11) NOT NULL COMMENT 'ID number for the map widget to use with this nav (in the ''maps'' table).',
  `server_name` varchar(255) NOT NULL COMMENT 'Name of the action server (e.g., /move_base)',
  `action_name` varchar(255) NOT NULL COMMENT 'Basename of the action (e.g., move_base_msgs/MoveBaseAction)',
  PRIMARY KEY (`id`),
  KEY `envid` (`envid`),
  KEY `mapid` (`mapid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Nav 2D widget info.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mjpeg_server_streams`
--

CREATE TABLE IF NOT EXISTS `mjpeg_server_streams` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the stream.',
  `envid` int(11) NOT NULL COMMENT 'Unique identifier for the environment.',
  `label` varchar(255) NOT NULL,
  `topic` varchar(255) NOT NULL COMMENT 'ROS topic name of the video stream.',
  PRIMARY KEY (`id`),
  KEY `envid` (`envid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Connection information for ROS topics published from a MJPEG server.' AUTO_INCREMENT=7 ;

--
-- Dumping data for table `mjpeg_server_streams`
--

INSERT INTO `mjpeg_server_streams` (`id`, `envid`, `label`, `topic`) VALUES
(1, '1', 'Overhead South View', '/overhead_south/image_raw'),
(2, '1', 'Overhead North View', '/overhead_north/image_raw'),
(3, '1', 'Robot View', '/youbot_base_kinect/youbot_base_kinect/rgb/image_raw'),
(4, '2', 'Overhead South View', '/overhead_south/image_raw'),
(5, '2', 'Overhead North View', '/overhead_north/image_raw'),
(6, '2', 'Robot View', '/prosilica/image_raw');

-- --------------------------------------------------------

--
-- Table structure for table `slideshow`
--

CREATE TABLE IF NOT EXISTS `slideshow` (
  `slideid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the slide.',
  `img` varchar(255) NOT NULL COMMENT 'Name of the file in the img/slides folder.',
  `caption` text NOT NULL COMMENT 'Caption text for the slide.',
  `index` int(11) NOT NULL COMMENT 'Slide index in the slideshow.',
  PRIMARY KEY (`slideid`),
  UNIQUE KEY `img` (`img`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Slides used in the slideshow.' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `slideshow`
--

INSERT INTO `slideshow` (`slideid`, `img`, `caption`, `index`) VALUES
(1, 'youbot_sim.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum diam.', 0),
(2, 'pr2.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. ', 3),
(3, 'youbot.jpg', 'Lorem ipsum dolor sit amet.', 5);

-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

CREATE TABLE IF NOT EXISTS `user_accounts` (
  `userid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the user.',
  `username` varchar(32) NOT NULL COMMENT 'Username for the user.',
  `password` varchar(255) NOT NULL COMMENT 'Encrypted password for the user.',
  `firstname` varchar(32) NOT NULL COMMENT 'User''s first name.',
  `lastname` varchar(32) NOT NULL COMMENT 'User''s last name.',
  `email` varchar(64) NOT NULL COMMENT 'User''s email address.',
  `type` enum('admin','user') NOT NULL COMMENT 'Type of user.',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Main user accounts table.' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user_accounts`
--

INSERT INTO `user_accounts` (`userid`, `username`, `password`, `firstname`, `lastname`, `email`, `type`) VALUES
(1, 'admin', 'caac26c8e0d7fb268933f11c88e97d13', 'Admin', 'Account', 'admin@my.rms', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `version`
--

CREATE TABLE IF NOT EXISTS `version` (
  `version` varchar(64) NOT NULL COMMENT 'Version number of this remote lab database system.',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='A table to hold the version number of this database system.';

--
-- Dumping data for table `version`
--

INSERT INTO `version` (`version`) VALUES
('0.1.1');

-- --------------------------------------------------------

--
-- Table structure for table `widgets`
--

CREATE TABLE IF NOT EXISTS `widgets` (
  `widgetid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the widget.',
  `script` varchar(255) NOT NULL COMMENT 'PHP script to create the widget HTML. The ''init'' function will be called with a valid table entry.',
  `table` varchar(255) NOT NULL COMMENT 'The SQL table containing information for an instance of this widget. ',
  `name` varchar(255) NOT NULL COMMENT 'Name of the widget.',
  PRIMARY KEY (`widgetid`),
  UNIQUE KEY `script` (`script`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='RRL Widgets.' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `widgets`
--

INSERT INTO `widgets` (`widgetid`, `script`, `table`, `name`) VALUES
(1, 'mjpeg_canvas', 'mjpeg_server_streams', 'MJPEG Widget'),
(2, 'keyboard_teleop', 'keyboard_teleop', 'Keyboard Teleop'),
(3, 'map2d', 'maps', 'Map 2D'),
(4, 'nav2d', 'navigations', '2D Navigation');

-- --------------------------------------------------------

--
-- Table structure for table `study`
--

CREATE TABLE IF NOT EXISTS `study` (
  `studyid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the study.',
  `name` varchar(255) NOT NULL COMMENT 'Name of the study.',
  `description` text NOT NULL COMMENT 'Description of the study.',
  `start` date NOT NULL COMMENT 'Start date of the study.',
  `end` date NOT NULL COMMENT 'End date of the study.',
  PRIMARY KEY (`studyid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='A table to hold information about different studies.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `study_log`
--

CREATE TABLE IF NOT EXISTS `study_log` (
  `logid` int(32) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the log entry.',
  `expid` int(11) NOT NULL COMMENT 'Unique identifier for the experiment.',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp for the entry.',
  `entry` text NOT NULL COMMENT 'The log entry.',
  PRIMARY KEY (`logid`),
  KEY `expid` (`expid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='A table to hold log information during studies.' AUTO_INCREMENT=1 ;

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
  UNIQUE KEY `studyid` (`studyid`,`name`,`intid`),
  KEY `studyid_2` (`studyid`),
  KEY `intid` (`intid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='A list of conditions for a given study.' AUTO_INCREMENT=1 ;

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
  UNIQUE KEY `userid` (`userid`,`condid`),
  UNIQUE KEY `envid` (`envid`,`start`,`end`),
  KEY `envid_2` (`envid`),
  KEY `userid_2` (`userid`),
  KEY `condid` (`condid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Pairs of users and conditons that make up an experimental trial.' AUTO_INCREMENT=1 ;

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
  ADD CONSTRAINT `conditions_ibfk_2` FOREIGN KEY (`intid`) REFERENCES `interfaces` (`intid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `conditions_ibfk_1` FOREIGN KEY (`studyid`) REFERENCES `study` (`studyid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `environment_interfaces`
--
ALTER TABLE `environment_interfaces`
  ADD CONSTRAINT `environment_interfaces_ibfk_2` FOREIGN KEY (`intid`) REFERENCES `interfaces` (`intid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `environment_interfaces_ibfk_1` FOREIGN KEY (`envid`) REFERENCES `environments` (`envid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `experiments`
--
ALTER TABLE `experiments`
  ADD CONSTRAINT `experiments_ibfk_3` FOREIGN KEY (`envid`) REFERENCES `environments` (`envid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `experiments_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_accounts` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `experiments_ibfk_2` FOREIGN KEY (`condid`) REFERENCES `conditions` (`condid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `keyboard_teleop`
--
ALTER TABLE `keyboard_teleop`
  ADD CONSTRAINT `keyboard_teleop_ibfk_1` FOREIGN KEY (`envid`) REFERENCES `environments` (`envid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `maps`
--
ALTER TABLE `maps`
  ADD CONSTRAINT `maps_ibfk_1` FOREIGN KEY (`envid`) REFERENCES `environments` (`envid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mjpeg_server_streams`
--
ALTER TABLE `mjpeg_server_streams`
  ADD CONSTRAINT `mjpeg_server_streams_ibfk_1` FOREIGN KEY (`envid`) REFERENCES `environments` (`envid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `navigations`
--
ALTER TABLE `navigations`
  ADD CONSTRAINT `navigations_ibfk_2` FOREIGN KEY (`mapid`) REFERENCES `maps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `navigations_ibfk_1` FOREIGN KEY (`envid`) REFERENCES `environments` (`envid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `study_log`
--
ALTER TABLE `study_log`
  ADD CONSTRAINT `study_log_ibfk_1` FOREIGN KEY (`expid`) REFERENCES `experiments` (`expid`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
