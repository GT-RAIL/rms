-- Host: localhost
-- Server version: 5.5.37-0ubuntu0.14.04.1
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
  UNIQUE KEY `page_id_index` (`page_id`,`index`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='HTML content of the site''s articles.' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `content`, `page_id`, `index`, `created`, `modified`) VALUES
(1, 'Lorem Ipsum', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>', 1, 0, NOW(), NOW()),
(2, 'Lorem Ipsum Dolor', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>', 1, 1, NOW(), NOW()),
(3, 'Lorem Ipsum', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>', 2, 0, NOW(), NOW()),
(4, 'Lorem Ipsum Dolor', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p> \r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer luctus quam quis nibh fringilla sit amet consectetur lectus malesuada. Sed nec libero erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc mi nisi, rhoncus ut vestibulum ac, sollicitudin quis lorem. Duis felis dui, vulputate nec adipiscing nec, interdum vel tortor. Sed gravida, erat nec rutrum tincidunt, metus mauris imperdiet nunc, et elementum tortor nunc at eros. Donec malesuada congue molestie. Suspendisse potenti. Vestibulum cursus congue sem et feugiat. Morbi quis elit odio.</p>', 3, 0, NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'The unique identifier for the entry.',
  `title` varchar(32) NOT NULL COMMENT 'The title of the page.',
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
(1, 'About Us', 'About', 0, NOW(), NOW()),
(2, 'Our Research', 'Research', 1, NOW(), NOW()),
(3, 'Contact Information', 'Contact', 2, NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `name` varchar(16) NOT NULL COMMENT 'Name of the role.',
  `description` varchar(255) NOT NULL COMMENT 'Short description of the role.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `description` (`description`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Roles for various users.' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created`, `modified`) VALUES
(1, 'admin', 'Administrator of the site.', NOW(), NOW()),
(2, 'basic', 'Basic user level.', NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `protocols`
--

CREATE TABLE IF NOT EXISTS `protocols` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `name` varchar(16) NOT NULL COMMENT 'Name of the rosbridge transport protocol.',
  `description` varchar(255) NOT NULL COMMENT 'Short description of the rosbridge transport protocol.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `description` (`description`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Transport protocols for rosbridge.' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `protocols`
--

INSERT INTO `protocols` (`id`, `name`, `description`, `created`, `modified`) VALUES
  (1, 'ws', 'WebSocket', NOW(), NOW()),
  (2, 'wss', 'WebSocket Secure', NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `rosbridges`
--

CREATE TABLE IF NOT EXISTS `rosbridges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `name` varchar(255) NOT NULL COMMENT 'Name of the rosbridge server.',
  `protocol_id` int(10) unsigned NOT NULL COMMENT 'rosbridge transport protocol used.',
  `host` varchar(255) NOT NULL COMMENT 'IP address or hostname of the rosbridge server.',
  `port` int(11) unsigned NOT NULL COMMENT 'The rosbridge server port.',
  `rosauth` tinyblob DEFAULT NULL COMMENT 'The encrypted rosauth secret key.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `protocol_id` (`protocol_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='rosbridge servers.' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `rosbridges`
--

INSERT INTO `rosbridges` (`id`, `name`, `protocol_id`, `host`, `port`, `rosauth`, `created`, `modified`) VALUES
  (1, 'Local rosbridge Server', 1, 'localhost', 9090, NULL, NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `mjpegs`
--

CREATE TABLE IF NOT EXISTS `mjpegs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `name` varchar(255) NOT NULL COMMENT 'Name of the MJPEG server.',
  `host` varchar(255) NOT NULL COMMENT 'IP address or hostname of the MJPEG server.',
  `port` int(11) unsigned NOT NULL COMMENT 'The MJPEG server port.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='MJPEG servers.' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `mjpegs`
--

INSERT INTO `mjpegs` (`id`, `name`, `host`, `port`, `created`, `modified`) VALUES
  (1, 'Local MJPEG Server', 'localhost', 8080, NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `streams`
--

CREATE TABLE IF NOT EXISTS `streams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `name` varchar(32) NOT NULL COMMENT 'Name of the MJPEG server stream.',
  `topic` varchar(255) NOT NULL COMMENT 'ROS image topic for the stream.',
  `width` int(10) unsigned DEFAULT NULL COMMENT 'The width of the streaming image.',
  `height` int(10) unsigned DEFAULT NULL COMMENT 'The height of the streaming image.',
  `quality` int(10) unsigned DEFAULT NULL COMMENT 'The quality of the streaming image.',
  `invert` boolean DEFAULT FALSE COMMENT 'If the stream should be inverted.',
  `environment_id` int(10) unsigned NOT NULL COMMENT 'The environment this stream belongs to.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  KEY `environment_id` (`environment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='MJPEG server streams.' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `streams`
--

INSERT INTO `streams` (`id`, `name`, `topic`, `width`, `height`, `quality`, `invert`, `environment_id`, `created`, `modified`) VALUES
  (1, 'RGB Stream', '/rgb/image', NULL, NULL, NULL, FALSE, 1, NOW(), NOW());


-- --------------------------------------------------------

--
-- Table structure for table `teleops`
--

CREATE TABLE IF NOT EXISTS `teleops` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `topic` varchar(255) NOT NULL COMMENT 'ROS geometry_msgs/Twist topic for the teleop.',
  `throttle` float unsigned DEFAULT NULL COMMENT 'The throttle rate.',
  `environment_id` int(10) unsigned NOT NULL COMMENT 'The environment this stream belongs to.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  KEY `environment_id` (`environment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Keyboard teleop settings.' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `teleops`
--

INSERT INTO `teleops` (`id`, `topic`, `throttle`, `environment_id`, `created`, `modified`) VALUES
  (1, '/cmd_vel', NULL, 1, NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `markers`
--

CREATE TABLE IF NOT EXISTS `markers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `topic` varchar(255) NOT NULL COMMENT 'ROS topic for the markers.',
  `environment_id` int(10) unsigned NOT NULL COMMENT 'The environment this stream belongs to.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  KEY `environment_id` (`environment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='ROS 3D marker settings.' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `markers`
--

INSERT INTO `markers` (`id`, `topic`, `environment_id`, `created`, `modified`) VALUES
  (1, '/visualization_marker', 1, NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `ims`
--

CREATE TABLE IF NOT EXISTS `ims` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `topic` varchar(255) NOT NULL COMMENT 'ROS topic for the interactive markers.',
  `collada_id` int(10) unsigned DEFAULT NULL COMMENT 'The Collada loader for this interactive marker.',
  `resource_id` int(10) unsigned DEFAULT NULL COMMENT 'The Collada resource server for this interactive marker.',
  `environment_id` int(10) unsigned NOT NULL COMMENT 'The environment this stream belongs to.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  KEY `collada_id` (`collada_id`),
  KEY `resource_id` (`resource_id`),
  KEY `environment_id` (`environment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='ROS interactive marker settings.' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ims`
--

INSERT INTO `ims` (`id`, `topic`, `environment_id`, `collada_id`, `resource_id`, `created`, `modified`) VALUES
  (1, '/basic_controls', 1, NULL, NULL, NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `urdfs`
--

CREATE TABLE IF NOT EXISTS `urdfs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `param` varchar(255) NOT NULL COMMENT 'ROS robot description parameter.',
  `collada_id` int(10) unsigned DEFAULT NULL COMMENT 'The Collada loader for this URDF.',
  `resource_id` int(10) unsigned DEFAULT NULL COMMENT 'The Collada resource server for this URDF.',
  `environment_id` int(10) unsigned NOT NULL COMMENT 'The environment this stream belongs to.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  KEY `collada_id` (`collada_id`),
  KEY `resource_id` (`resource_id`),
  KEY `environment_id` (`environment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='ROS URDF settings.' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `urdfs`
--

INSERT INTO `urdfs` (`id`, `param`, `environment_id`, `collada_id`, `resource_id`, `created`, `modified`) VALUES
  (1, 'robot_description', 1, 1, 1, NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `tfs`
--

CREATE TABLE IF NOT EXISTS `tfs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `frame` varchar(255) NOT NULL COMMENT 'Name of the fixed frame.',
  `angular` float unsigned DEFAULT NULL COMMENT 'Angular threshold.',
  `translational` float unsigned NOT NULL COMMENT 'Translational threshold.',
  `rate` float unsigned NOT NULL COMMENT 'The rate to send the TFs.',
  `environment_id` int(10) unsigned NOT NULL COMMENT 'The environment this TF belongs to.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `environment_id` (`environment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='TF client settings.' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tfs`
--

INSERT INTO `tfs` (`id`, `frame`, `angular`, `translational`, `rate`, `environment_id`, `created`, `modified`) VALUES
  (1, '/base_link', 0.01, 0.01, 10.0, 1, NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE IF NOT EXISTS `resources` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `name` varchar(64) NOT NULL COMMENT 'Name of the resource server.',
  `url`  varchar(255) NOT NULL COMMENT 'Base URL of the resource server.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Collada resource servers.' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `name`, `url`, `created`, `modified`) VALUES
  (1, 'Robot Web Tools', 'http://resources.robotwebtools.org/', NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `environments`
--

CREATE TABLE IF NOT EXISTS `environments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `name` varchar(255) NOT NULL COMMENT 'Name of the robot environment.',
  `rosbridge_id` int(10) unsigned DEFAULT NULL COMMENT 'ID of the associated rosbridge server.',
  `mjpeg_id` int(10) unsigned DEFAULT NULL COMMENT 'ID of the associated MJPEG server.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `rosbridge_id` (`rosbridge_id`),
  KEY `mjpeg_id` (`mjpeg_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Robot environments.' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `environments`
--

INSERT INTO `environments` (`id`, `name`, `rosbridge_id`, `mjpeg_id`, `created`, `modified`) VALUES
  (1, 'Demo Environment', 1, 1, NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `ifaces`
--

CREATE TABLE IF NOT EXISTS `ifaces` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `name` varchar(64) NOT NULL COMMENT 'Name of the interface.',
  `anonymous` boolean DEFAULT FALSE COMMENT 'If anonymous access is allowed.',
  `unrestricted` boolean DEFAULT FALSE COMMENT 'If unrestricted access is allowed.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='RMS interfaces.' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ifaces`
--

INSERT INTO `ifaces` (`id`, `name`, `anonymous`, `unrestricted`, `created`, `modified`) VALUES
  (1, 'Basic', TRUE, TRUE, NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `ifaces_environments`
--

CREATE TABLE IF NOT EXISTS `ifaces_environments` (
  `iface_id` int(10) unsigned NOT NULL COMMENT 'ID of the associated interface.',
  `environment_id` int(10) unsigned NOT NULL COMMENT 'ID of the associated interface.',
  KEY `iface_id` (`iface_id`),
  KEY `environment_id` (`environment_id`),
  UNIQUE `iface_id_environment_id` (`iface_id`, `environment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Intreface-environment pairings.' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ifaces_environments`
--

INSERT INTO `ifaces_environments` (`iface_id`, `environment_id`) VALUES
  (1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `studies`
--

CREATE TABLE IF NOT EXISTS `studies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `name` varchar(64) NOT NULL COMMENT 'Name of the robot study.',
  `start` date NOT NULL COMMENT 'Start date of the study.',
  `end` date NOT NULL COMMENT 'End date of the study.',
  `length` int(10) unsigned NOT NULL COMMENT 'Length, in minutes, of a study session.',
  `anonymous` boolean DEFAULT FALSE COMMENT 'If anonymous sessions are allowed.',
  `otf` boolean DEFAULT FALSE COMMENT 'If on-the-fly sessions are allowed.',
  `parallel` boolean DEFAULT FALSE COMMENT 'If parallel sessions are allowed.',
  `repeatable` boolean DEFAULT FALSE COMMENT 'If repeatable studies are allowed by the same user.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='User studies.' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `studies`
--

INSERT INTO `studies` (`id`, `name`, `start`, `end`, `length`, `anonymous`, `otf`, `parallel`, `repeatable`, `created`, `modified`) VALUES
  (1, 'Demo Study', CURDATE(), ADDDATE(CURDATE(), INTERVAL 1 YEAR), 10, FALSE, TRUE, FALSE, TRUE, NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `conditions`
--

CREATE TABLE IF NOT EXISTS `conditions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `name` varchar(64) NOT NULL COMMENT 'Name of the condition.',
  `study_id`int(10) unsigned NOT NULL COMMENT 'ID of the associated study.',
  `iface_id`int(10) unsigned DEFAULT NULL COMMENT 'ID of the associated interface.',
  `environment_id`int(10) unsigned DEFAULT NULL COMMENT 'ID of the associated environment.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `study_id` (`study_id`),
  KEY `iface_id` (`iface_id`),
  KEY `environment_id` (`environment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Study conditions.' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `conditions`
--

INSERT INTO `conditions` (`id`, `name`, `study_id`, `iface_id`, `environment_id`, `created`, `modified`) VALUES
  (1, 'Default Demo Condition', 1, 1, 1, NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `slots`
--

CREATE TABLE IF NOT EXISTS `slots` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `condition_id`int(10) unsigned NOT NULL COMMENT 'ID of the environment associated study slot.',
  `start` timestamp NULL DEFAULT NULL COMMENT 'Start time of the study appointment.',
  `end` timestamp NULL DEFAULT NULL COMMENT 'End time of the study appointment.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  KEY `condition_id` (`condition_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Study session slots.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE IF NOT EXISTS `appointments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `user_id` int(10) unsigned DEFAULT NULL COMMENT 'ID of the user associated with this study appointment.',
  `slot_id`int(10) unsigned NOT NULL COMMENT 'ID of the slot associated study appointment.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `slot_id` (`slot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Study session appointments.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `appointment_id` bigint unsigned NOT NULL COMMENT 'ID of the appointment associated with this log entry.',
  `type_id`int(10) unsigned NOT NULL COMMENT 'ID of the log type.',
  `label`varchar(255) NOT NULL COMMENT 'Log entry label.',
  `entry` text NOT NULL COMMENT 'Value of the log entry.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  KEY `appointment_id` (`appointment_id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Study session log data.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE IF NOT EXISTS `types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `name` varchar(16) NOT NULL COMMENT 'Name of the type.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Log data types.' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id`, `name`, `created`, `modified`) VALUES
  (1, 'string', NOW(), NOW()),
  (2, 'numeric', NOW(), NOW()),
  (3, 'json', NOW(), NOW()),
  (4, 'score', NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `colladas`
--

CREATE TABLE IF NOT EXISTS `colladas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `name` varchar(16) NOT NULL COMMENT 'Name of the Collada loader.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Collada loader types.' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `types`
--

INSERT INTO `colladas` (`id`, `name`, `created`, `modified`) VALUES
  (1, 'ColladaLoader', NOW(), NOW()),
  (2, 'ColladaLoader2', NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` tinyint(1) unsigned NOT NULL COMMENT 'ID of the settings (should remain 1).',
  `title` varchar(16) NOT NULL COMMENT 'Site name.',
  `copyright` varchar(32) NOT NULL COMMENT 'Copyright message.',
  `analytics` varchar(16) DEFAULT NULL COMMENT 'Optional Google Analytics ID.',
  `email` boolean NOT NULL COMMENT 'If email sending is enabled.',
  `encrypt` varchar(256) NOT NULL COMMENT 'Random encryption key.',
  `logo` longblob NOT NULL COMMENT 'The actual logo image data (prevents Git conflicts).',
  `version` varchar(16) NOT NULL COMMENT 'RMS version number.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  UNIQUE KEY `copyright` (`copyright`),
  UNIQUE KEY `analytics` (`analytics`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `encrypt` (`encrypt`),
  UNIQUE KEY `version` (`version`),
  UNIQUE KEY `created` (`created`),
  UNIQUE KEY `modified` (`modified`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Site settings for the RMS.';

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `title`, `copyright`, `analytics`, `email`, `encrypt`, `logo`, `version`, `created`, `modified`) VALUES
  (1, 'My Robot Site', 'Worcester Polytechnic Institute', NULL, FALSE, CONCAT(MD5(RAND()), CONCAT(MD5(RAND()), CONCAT(MD5(RAND()), CONCAT(MD5(RAND()), CONCAT(MD5(RAND()), CONCAT(MD5(RAND()), CONCAT(MD5(RAND()), MD5(RAND())))))))), 0x89504e470d0a1a0a0000000d494844520000026a000000c808060000005899cdb300000006624b474400ff00ff00ffa0bda793000000097048597300005c4600005c4601149443410000000774494d4507de041d11342bb9613d8d000020004944415478daec9d777c1465fec7df53b666534842127a970e51912211b080a282ed3c95e3147b170be729e879f68262fb890df56c143d2cb144cd89a8915e420bbd262484c0a66fb6cdccef8fd96c76930d35a4e07c5eaf7d417667e69979e69999cf7c9eeff7f3153070d448cfc864fcd8d191be370166c004c88003e80274023a02ed8064201168054403b18175acb5365709f88152a01c7002454021b017d803ec0a7caa005ff567fcd8d1be23dd6703060c183060c040f3856074c1b193b3f48ccce400096b1f20623d806e40d7c0f7e646da3d17b01bd8096c07b600b981cf9ef163471f30489b0103060c18306010b5939dac5981f38011c060a0359000c4a3ab68cd091e7415ee20b00f58062c04168c1f3b5a31489b0103060c18306010b59648c6c2884b7a46665b6002f057e00c406ba17d57bddf2ab014f81498337eece86283b4193060c080010306516b2944ad0dfab4e558e04aa0f7d1f5a810f804feaf69e0f521f8fc083e3ff815f0fb1114151415d4c027122451df8624a14912c8129a2c814946339bc024d7b4a111f8573bda43ce06e6023f03b9e3c78e2e34889b0103060c18306010b5a62463004112929e9169072e044603438081474cca24512751928850e541dcef443ce0443a5082e82c45282d47aca844a8a842705521b8bd081e0f78bc085e3f82a280cf1f71f39a49064902b309cd6246b39ad1ac16b4281b6a941dcd61478b8b46898f454b6885d2ba156a72029ac904aaaa6f5b518f94bca9c04a603190097c3f7eec6835527f193060c0800103060ca2d61884ad2bf00070097a9c99edb02b89429098891555c81b7760dab41d79cb2ec4c2831054cefc087e051445ef66a11ea277248844b4aabf92c41a954d96c124a3b44bc6dfa313fe5e5df1f5e98666b580a2e8fb7364a4ad123dbe6d1e3063fcd8d105d584cd206b060c183060c08041d44e14311302646c20f00c30eaf03d24e8ea945f019f0f39b710d38a7598566f42debd174d10401441148e9c789d68681aa89abedf9a86bf5b477ca7f6c67b467f9436ad0353a832882184efd0f81af817b019f08c1f3bda50d90c183060c0800183a81d17290b2312e91999dd8031c00dc0a043123349421304a4fd0790f28b9076e661cad98ebc713b62715940bd927482d612a0a8c1295635b115be3eddf0f7e98ebf531bd476c9a809ad405375f5efd0c4ed77e003e0e7f16347ef89d4cf060c183060c0800183a81d96a4d52268ff0890b42ef5ae248a68661382cb8d79e91acc2bd623e515221639112b5c686240393b5ed54cd3d0545557bc340d2d340140d3104c260459aa595c51f4654501a181da27d0be1613859a9480d2b12dde41fdf00ce90f261382d7abb7593f36033f002f8c1f3b3abf769f1b3060c0800103060ca2764892969e911907bc8a6eab61a9f758053de64cde9d8ff5bb85981767ebd39cf565621e0d210a21559a5f4153144cf1b198539230b56e85dc2a0ed961478c762059cd081633c5fffb838aec1c04490241c0deb30ba6d68978f7edc75bb01f7f715970bf116818f2164254bd23cfa0eaa291a849f17a1fd4cfd73474a3ddb78087c68f1ded37d43503060c183060c0206af592b3c0ff5380eb8027a8af328028825f412c2d43deba1bebb70b31e56c0f5a601c3111ab264cd59d294b98db2421c7c562edd496f295ebf116ec478e8da1f7272f1373e66987ddeceea7ff8f3d4fcf44b459104c32dd663c42f2844bc296f1153971efde8b7bcf5e8ae67e4bf18245c762cb11193e3f82aae23bad0f55179c85d2ad236aac438fc1ab5f65ab021e04e656574130143603060c183060e0f821b7748206542b68b1c0edc0ad40e7c8472b8300a6b59b312d5f8f79550ed29e7cb480fdc59140f57ad1dc5ee456b18816334a852b406254ec3d7bd0eba319583bb7c75bb09f7517dd10a4c35294edc84e486c74907409a2881c135d671953eb784cade3891ed41ff78e3d14ffb2b89ecc500dcdef4753d420a9146419413a446c9d494603e4359b89599583bf733b7ca7f7c53ba81ffe7e3df43836bf527b2d1bf03a707b7a46e65bc0bbe3c78e761b84cd800103060c18f89312b55a2adab5e80a5a07f45cc670882298644ccbd661fbe227a4fcfd0895553a97b1598fb84dd162a6f5e5179078d918ec7d7a50fadb32b6ddff5430d6ccd6a30be694d6005464e750b56d37824946f3f9a958bb115f6939debd85200ab4be622c822ce12f2963d3b50fa0ba3d200878f715215803a451923025b60ab65ff4dfef51dd1eac1ddb61edd4164ba7f6f88a9c11499ae6f3812010376a18517dba23c8129e8222ca16adc4b5751792c3ae4fafd63b3274735d297f3f52fe7e2c0b97e1efd496aaabc6e2ebdd4db720a9db6e1f600670477a46e683e3c78efea63a43d4206b060c183060c0c0494ed46ac54009e919991d80f78173eb5d49519177eec5fefe7ce4adbb75b77f38a6b82ed5edc5daa53df16347812060e9d006e74fbfe3cc58081a583bb543b45ad01495e2058bd0541501502a5d6cbdebdfe8615d028e81bd49183b0a29c681e6f353be721daacbad2b7304e2ce02ff9a12e282ede7cd788f8a351b7582a5697a8c9aa96e8951419648bc740cdd673c82145b5791ab5cb7899dd35ea26cf12a3db1e15008f49350e1c2b47e2be6d59bf09eda0bd7f597a3a42446ea4719e805a4a76764ce06ee044a01cd206c060c183060c0c0d1416c493b3b7eece8ea69ce04e00e607d44922689a0a898366c23fa950f897de039e4adbb6a485a4416a6a17abc88560b9223aa9e207a8ddce9ef50343f43272f9244d7e7ff89393911d162c2daa543605b2ac53ffc8a20cb61e4499065045942535554af3770064424873d324792444c09358a9a52e1d2d7f72b01822584ef6780bc75fef7bdf47ceff93092a654ba82ff8feadf8b7ee9ef62eddab1ae2a76a892568280669631add94cdc3d4fe1983907d3e69d357d5e1713801dc0b5e919998e50ff3503060c183060c0c0e1d12214b55ab16867034f02c3231209bb15d3aa1c6cdffc82bc7e2b82db73d8e94d4d55b1b44d26f1f2f349bce85c0e7cb780fcb73ead4b5804014dd3d876f7e39812e3891b35144bbb144e79eb6972aeba0b6be7f600b8366dc3b5790752b423727b7e3faa47276a822820d96d78d1106ae7760802727c8da276ca9b4fe12fafc05f5c8a277f3f95eb36e1fcf13734af4fe7582e379d1e9f4cdbdb2706d729cecca2f0e32ff1394b90a26c449f319036375d45f9b2357872f3eb2862e694d6a81e2f9efcfd48366b50e50b1f35121a12965f97635abd115fff1eb8c79d83af6f3704b7b736f96b05fc0798989e9179fff8b1a3d719d9a1060c183060c0c04942d46ac5a23d0e4c8db8dfa28850e9c2f1dac798576c007fa07ea674f82c4ecde72761ec283a3f76afae7c9964f67ff225feb28aba5c5014513d1e36dff04f06647e84ad5b2762470ca6cbd3533025c603b07f767ac429c9d0f6344f88a216658fa8e089b50866ccf0d34336a2519ab58292058b51bc3e5035ecfd7a90fcf7cb838bec9ffd353bfef97c989a56b26031fbde9b07a218248ba1dbecf6d223c49d3d94c28fe6933be33dbcfb0f06a762eb1c874946a872635eb60ed39acd78469e816be2783d73b66efcda79c0aaf48ccc87c78f1dfd62ed736bc0800103060c1888c0059a334183a08ad6393d2373097a1923b90e41737b312f5943dce4673067adaa21694708c12473e0ab9fa8664b8ed3fb616e9354bfe58520e02f2965cb6d8fe07796009072c35fb1f5e80c40e1dc6f10cde64312b51a454d448cb25187a9a92a96b649357fbadcb877ecc1b3771fdefd07f19796e32f2d4775bb031c4b256ee450ccc9890078f715b177e6273a491384e0475355bc8507f016ec0fdf2745c5d6ad23f1178e42b4596973ebdfb075eba417753f923ef478b17dbb9056939fc6b46eb39e6c10397e6d7a7a46e677e91999ed4308b871251a3060c08001032d85a8d552d1ae07960143ea2c284bc81bb7e3f8bf4f887ee15d847257bd3e689aaaea5615110a930ba248d5ae3c4a162e0d7e97fcf7cbd07c87207c8240f992d5ec7c7446cd57b244f98ab5f80f14479e32ac265d3e9f9ee509200aba75472d9ea6691a96b629c1bf4b162e6149b7b35835f432d69e7f2d3957de49de8c59faf100a2c9a46777061444d7c66db8366e8b9c3421d4ad4baabaaae8f6e2b4e0dfa50b9752b2601182490e5f0f8253ad75fad86c42385842cce333897a6bae5e0bd51451b4bd1058909e917945081937ae4603060c183060a0b913b55a246d36f036d0ba0ed19025ec1fa7133dfd7d4c2b37a0592c11c991a62828952e4cf171449fde9fa8bea7a0f97ca81e4fd87252949ddc97de0dfe9d72fd9587dd57c16ca2f0e32fc97bb1663dd5ed3d6c0dd03a8a9add4e5d454dc3d2be86a879f6ee43b4da502babf0eccaa36cf95aca576fa8c9cab4988353af681a553b7251abdc47d4e79adf4fdc88c1c48e181cfc6ed713af22dac3bddf62cf3c8dd4057368353aad46a9ab33a2f40a07e645ab897efa6decf332d0ace648cbf6003e4acfc87cb39aac193060c0800103069a2951ab35d5d93e3d237323700d60aa4dd2c4222771f73c8df5eb9f11aadcf512234d5189199aca698bbf60c8f65f19b86036a72efa2fc30a96d3ee8e6bc3e2c80459a27c6976704a50b45a48bc6c4c4d76667d644d12c99f3537f877cc908187ad12104ad41004247bdd64074d5531b50999faacaa428e76e899a32613a249aee383a685b47b4853db3a444da1ddbd3704c954c98245546ddb154eae5495f60fdc4cd4c05ef4fde22dba3ef38fb0d8b748844da87061fbfc47e21e7c11d1591289acd981dbd233327f0c64f2868d0503060c183060c0206acd84a485a86867038bd0bdb8c2089ae057b0fcbc98b8fb9f432c283a64a28068b5d0e5a9fb19f0c347384eed1bf69be4b0d3f58587e8fde18bc8ad62f5c2e781360a3ffe32b85c9b5b27a0790f13ef268a78720bf09795eb9b3099b09dd2a5669b1189514d32812049919309340d6b8736c1ffb7bbe77acedcbf82a17bfee0d445f3e9f3f91b749872b3be2ea0ba3d78f30b83c711d5a707725ccc11b0348d98330610337860f0abb2a56b505ceee0d4afe657700ce84ddcc89ad9e7c2d9e9884752cd41149036ef22f6e197b12c59a393d8bacae718e097f48ccc5343c8ba71751a3060c0800183a8353392f600f0257a85811a2e613621969613f57f9f10f5de7cbd84d161a617db4dbe9e76775d078077ff41f25e7e8fdc9766e94a5180a0b43a7f04bdde7f01d16a0daa60ce1f7f0f1214476a5fa27a770b3785d5d0d5b050f54a9628fb6d79f0efb873861d32be4df3f91002b174822804a618eb4e7d9aab9309429428d16ac1d6bd1371a386127ffe88a072a6f9fd9466ad08c6be394eed4b9b5b27a0baaaf47da947e5132489f80bcfc194945043506fba8a5e1f4c2761dc39685e1ffeb272babe3835f87bd1bcef706ddc5657d153d5c806bab284505a4ed46b1f13f5ce6708659568729dd8b5fec04fe9199993aac99a0103060c1830f06747b329ca9e9e913913b8995a599d9ad98479ed161caf7e8410c12ea32e0bd2b0b44b61d0ba1f7492567880d5432e4509948c121d76ba3efd0f92268c0f928b3d4fbc46ee8c59082613a6f8387a7ff232d14352d1fc0abb1e7f95bc97df43349b74c22349244f184fd1fc1f82659a3455a5dd1d7fa7cbd3530028fef137d68dbb09293aaadedd34c5c78128a0292a6a95bb26b920e438e4d8180493841865c79cd21a4b9b242c9dda61ebda015bcf6e78f30bd972eb3434bf3f48d6face7f8b56e70d0f1e5be96f4bc99d3e8bd23f96a3ba3d016fb41a922bc746736ad67f6b48612857747ba85cbf05e7f70be938edce20295c3dec725c5b76d6edf7f66d502a5df80e1407896824286d93a8bcf73afc1d9223157af7000f8f1f3bfae5966cdf91159f4a9a33dbb8c3187d61c08071fd19f78ce34293f9a8553f84d333321dc00be805d56b310515eb82a544bdfb59a442e011a17abca4dc7455f0efedf73f85afb83458254029ab60e7b4e958bbb42766d86908a248c2a563289cfb2dbea283780b0f50b6349be821a908b244ab5143299a938e29b115c9d75d41cab5572046d910cc260ade9d8b68b5208822a58b56a2545452be6c0d25bf2e0dcf968c005fc0d6a37e0a2d04a7533950a22710681aa82a9aaaff8b20205a2d35ab48121bafbe9b7edfbe4fcce00108a248dca861c48d1a165c267be455c132549a5f21e192d14192a6ba3d6c9c781f1deebf89a801bd901c76a207f5277a50ffe0fa07becac49d5b50677745ab85ceff9e4ce2e517b0fdbe27299cf38d4e642340cadb47f4a3af5031e5467cbdbbea53a1357ccd02cc48cfc8b4002f02fe9648d8d29cd964c5a7b60736a1c7e2fd595f04fba739b3d737f78742f5393b191f2659f1a929c042a027f5d45c31d0a2aea93240055c4079e0ef12e020b01f280472813d814f55567caa0ff0567fd29cd95a731ecb81fbe743c0b37ff2312b00f94d42d442485a2b60267075f8935cf746b3cdff09eb573feb0ff223accda9f9fdc40e1f14fcbb72c3963a5374de034ef6bef1318ed3fa215acc3852fb60edd21e5fd14110059c9959a4dcf05724871dc719fde9f7ed7bd87b750bdb46f2844bf4ea05d5edacdbcc924e692865150866936e56ab690d77aa0451973f25a97e1934a0eeadbde05adadef63712c78f262ab5375248f6a677ff410441004d4330c9747ee4eee06f792fbdcb812f7fc499b19056e70e27e99a71c49d7366d09b4d757b38f0d54f7a29ab906405cdeba3f5c44b49bcfc0200babdfc28d183fab3edfea782f621e1ac4e44a8f210fdf81b544db898aa8b468259aeadae3d0b744dcfc87c60fcd8d1e52d585d136946cab581433e18261de1cbab0cfc37cd997da0858d438cb17852a03af838ee0897f7017b03e46d27b03d2b3e7517b01bd89de6ccde154ad28ef5c5e504911463cc82d8e8442d84a425015f01c3ea90b4e232a2ff6f36f2bacd87aecf1991a98118924569ebd201f7aebd0852489c97d9ccc1af33f13efd00d64e7ad9277bcf6e942f5b83204994feb614effe03d81c1d9163a291636a6a663abfff85a22f7ec4b5715b180142d340d390621c357f37c9d016102489fcb73ea5e8f3efb1b44bc1d2be0db6ae1d901c767c079c208a28ae2a3a3c703372a0e8bbbfb49cbd6f7e8a1c1b0d8240c9af4b295bba9aaecf3f4ccaa42b0070efccc5f9c3c2b04a059acf8723b50fdd5e9a16b61b964eed234d6b86913544b07d968194b78f8a5bfe0a7555c89b81c4f48cccbf8f1f3bbab2855e648682d132485a67e083a358ad35f0640b9aa231c6e19f1726a073e07356c8f7c580332b3eb508580964023fa639b3dda1a4ad09c7b7316603fdd0e8442d84a4a513c1c4563c5042f40bef21edde7bd88481889044dcdb7713d5a7070049132fc3f9e3ef08364bd862aad747d9e2d541a226c73a42c8879fa279dfd1f1617d36b66aeb2ef2dffa94fd73d2f558b26a754fa85b9bb3b9409024fc2565f84bcaa8dcb0a5d669d790a31db4bfe7fae0577b5f795ff75dabf66593f56cd4848bcf0e2e53306b1e4a7965cd74aba62146d9e9f3f91b6105e8ab899f52e9aa53062b12ccbfaf20c65942f9c3b7a2d58d6dbb0c989d9e917919a01a4906061a12210fa26b8e72d5db80278d381a032d18ad029f6ec050e04ec09f159ffa2bf006f0a531be9b071a2debb3da6e2140d23ead43d2640929bf90e827df44debe2732e951552ced53e830e5164e5bfa254377ff41bf6f66d1eaec6141054b34c91c48ffb9e6b5f78a0b881ad8abae9bbea685d5baf4394b6b488ec54ce1c75f70f09b9f593be6efac187001f96fcfd6973f5232a66a7af925bf12cc52d56c56d4d868d4c456286d5aa3b44f41e9d416a54b7bfc5ddae3efde117fd70e285ddaebdf77484169d31ab5753c6a5c349addaa5b9228816d2aaadeced1aa778163d872fb540e7ebb80cab59b3890febf3a4436e99af141135ddfc1620ade99136ec9218af478e55f98535a0709ad52e9024dc357183e2b1433ec54ecbdbb47ce8695244c6bb710f3f00cc403c59108fa78e00b202a742c193070bc0879108d3fca55e3b3e253cfab267b060c9c2490817303f75b6f567cea9b59f1a96765c5a7261a5dd3b427a551485a40496b4d84e94ecd64c2bc723d8eb7e7211c2889587648a9ac2265e2a574fad73d583ab60d7e1f7be6e9c49e793a3b1f7e81bc573f40b45971fef02bbea283985aeb96133ddf79960d57dc86a7603fa2d98ca628c8b10e62cf3a4327256e0fae9cad353c4614f1141491f3d73b7573d95a0efd9189998a504dca2c66fced9251db24a22427a226c6a1c546a34647a1d96d68762b9ad582663181c954a322994cc13aa582cf0f7e3f82d787e0f622b8aa102aab102a5c88e59588078a11f73b910a8a90f616229457822ce9db3a9242f48a4271e61f387ff80d4bdbe49ac485008995a2ec749c7a47f0ab9d535f44d3343dbe0d4083c4cbcf0fc6a5f99dc514cdcfa0dd1d7f47d3343cf9858124010d5bf74ef4fee415d42a377b9e7f8b7def7f56a768bd6636e94906cfbe43e5dd13f177eda0274cd4e012606e601ab4c4b8740d34a0aad60fe87894ab59d0d5deff19aa838193142674e5f836e08facf8d46f80f7d39cd945d52f28c6d83f89885a48e2c0d7b5491a828079f5061c33e720945546acd5a99457d2f5f97fd2ee9e4935b52c73b662e9dc21e8eadfeede1b28f97d39ae0d5b50abaad8fdcc4cbabffc280051fd7bd2e7bf33d936f949cafe5881b96d123d5eff37b62eba5d5bc92f8bf5ba98a1bb250a088723689a1620677efc9ddae13fad0fbefea7e0efd456279b2659f70b93253d1c324cf8d2eacec08714930fae6fb71231965251f4b67d7e049f1fa9f020a6759b91b33761dab843b7fe90a5434e1f0b2619c124e33b581ca6142a556eba3e7a4fd04cd7bd338f83e999356a9a06a6c438babf343598ddbae799377573db18079aa2e02d2c024144f5fae8f5e14bbaf96e5c0cad460d25ffcd4f916a11b56a854e2a2822fa85f728fdf75da82909b5e3dc2e06de4acfc8bc16f01ad3a0061a0867006d8e721d01189a159fda26cd995d6074a181931cc303cfeefbb2e2535f4f73663f1d88ed0430085b4b266ab52c38de8c44d2a45d7938667c88101afb556b991eaf3f4e9b5bf510124fde3e364e984cf9f2b5d84ee94cdff96f61ebde0973eb0462470ca672dd260451a4e8b3ef6875ce99248c3b1700c780dea4fe325b7ff08738e37b720bd839ed451497fbc8ca2e69804942339b51da25e14d3b0dcfb054d4f8380425301da9d5a9b07eec61911a445c5914d1cc22984d6880da2a065f9f6e68132e46a8726359b60ef3ef2bf569648f5757e834adfe22ed612455c4b3271f6fd141ccad13d8fbc647a8d553969a86a6aaf49df7065220c9a278c122f25efd80eeaf3da62fa36a78f7ee47f37ae9f9def3d87b77d7fb3a7f3f9b6e7a08c97108b70a4140282e23eec117297df63e9476c9b595b5ab807ce0fed03166c0c0312869d5cad8591c5b66592a7a051583a819f83340049281a7b2e253ef0226020bd39cd9ca09226c46867248c79f509216c03381076c9862645abf8598c76722b8dc910984a6913076246d6eb926f877fe5b9f52919d8368b7e2dabc0367c6c2605922393a2ac86994ca2ab6def928fb3ffd2abc387980a4695e2fce8c85acbbf8065c5b761e9aa405942b353911ef99a9545e7f05a5d3ff41e9f47f5075e14834471482c7ab2fd754d99e9aa6ab6c5e1fc832ee1183287bfc2e4a5e9d4ae59d13f09c3d18a5635b50b51ad256dfa0b059c97f67362b075cc88e079fa3f8c7df82cb6b7e85aecf3e48d4a97d026a5b2e9b27fd03535202e694a4e0beb8b6ee2079e265244fbc4ce76e2e179b6ff8476dd255cf0e08e0f112f3d8eb3ad10c570535e03ee06968f6e5a654e000bad7d19f156a33dfbf68e0bce3b87f5e94159f2a36f338350da8321e77061a1029c0ff80b7b2e253bb5713b406be0ebc4637eb1ac70961acb5ca42fd1f7a36490d6409d3aa1c1c6fccd663ab0e11a02fb78aa5cfec57890ed4a2f41d2c61fdf81b71e56c47f5f9e8f9eeb3245d331ed5ed61eb5d8f51f4dfef83d3a39aaa22c8323183fa137bf6301cfd7b011a95ebb750f2eb52ca57ac45757beb256982cf0f8280f7f43e78ce198ad2a10d4a72823e951949396b8e10049de8682ae28112e4bd8598fe588d256b2582c78b66361d92fc697ebf9ed129e855145a5f71013d663e896831a354bad874dd144a162c426e154baf8f66103bfc74145715ab068da7ff771f60edd21e348d3d2fbc4dee0b6fd76d4251d07c7e04b329ccf6a3ba7db5753ce5ffbc4957d6b4da73c73c3c7eece8e79bb162630a282ed6c0271ee8070c06ce0e908493053b8065c07a600b5014b8d1ae4e73665735e3733412dd0cf658e144b7ea509bebf44f601cf646f7dd8a4337be4d05ce44b76c68ced807ac088ca97de8e6ae26742534053d637140e05f034d83edc0b43467f6bc061eb76d0263d50a2401ed029f0e4057a047601c9c0cd813b887ee42f7bcdb1b18ef2540d909951603b53b9f0d5c5841487b0b897ee66dc492c30b0d9aaa624e69cda015df2046e93163dec203ac18700171a386d2eb83e988761beeedbb599576a55eecbc9ee93d21e080af6987c994d4340455c53d6a3055978f416d1d7f7289b09a86585985e5bb5fb17dbb3090957af80394ec364e79eb29e22f3a475737df99c38e079f439025ccc989f4fbfa5d6c3d3a53be740da54b56d17eb26eff51b17a036bce9b5877ea5555891e3c90ce8fdfc7a6eba6e81e6f75f4180d2d318e9217a6e8f17ae1a7cd035c0afc50adae35a387639d6980c0dba610506234f45080c9c05f6899236c07f01a7a167771e0983440ab6da0d98c49cc2ce0c6e3dcccb83467f6b72de184858cc1ea4f1b74bfc2bb022f12cd0145c0dbc0fbe8a6ac42c8d80abbab877ca281cb03d753ff16740d2d0f28532b81ad81632f4537a98d051c2184b46fe0252fad1912142f300d78a9f6f5df10f7cf08e3b67a4c2405fae382c0b320be059c7315f8153db1f297c04b88127affac7e5257f741833f1c42e2d2ce462fb01e1bfc5114119da5443f391329af3062e240c4a3f2788939f334067cff41d0afcbbd630f964eed1124117f5939397fb983b2c5ab110ea5101d86bca0819a18876f602f5c7fbd003529419fd2d44e42df3d414033c9082e37b62fff8765f16ac4c283fad4647d09089a86a66a749a760771e70c67dd859374bb0d41c0dab12d0317ce458e8da6f8a7df693546f75554ca2b5935f4523c798561f53f35bf9f98e1a733e0fbff0050be622d397fbd0b7f49595da2ed57503aa650fef83da80e7bede9d31260d0f8b1a3b7b79478b5daf11c59f1a909c013e81611ed9bf9eebb03ca8ba06e0000200049444154cad9e369ceec052d818c1de65c5405ded88f07bfa539b347b6c063affd30fc2bf00030b00988801fd80cbc94e6ccfee068c65584e3e80f3c028c46f7096b6ed809bc0bbc9ae6cc761de98b5eaddffb04ee17e302ea524a3339b6a781a7d39cd95527fa9e50cfcbf0d9c08301f2e66866e46c3b7aacfedbb5cffbe1faaa41895a08496b0ffc4ea8ac2e890825e5c43cfb2ef2b65d68a6a323549ad747f27557d0e3f57f873dc82bd76e62c7d4e9942c587444e6aa113bc1e34569d31acfa8c178879f8abf533b7dda536deee1350d44d8cc26a47d07302f5983e5d7e5c8db76eb53a2f5c40d2a556e4c89ad50ca6b4a49d94ee9c2698bbfd0975194a04548ce5fefe4e0f70bc3fcd734bf9ff8f347d273d6b3c18404df8162d65d787dfdf1828a8a7fc02994df7b2d5a94bd3679de0ca48d1f3bfa400b2fe47e4a40d9b88d5a2a74334101f0409a337b4e4b2768817dbf00c868a0cd75487366e7b5d07e087d6910d1e3891f01fa04deee4fb4dabb173d8ef9fd6a57fc631957115e80060714b609cd4c41bb32cd99bdfb588eb31e7232101813386fa73783639c0bdc96e6cc2e6dacfb4304b27e21f07f40976642d25e01fe9de6cc2e8f34560ffb986e689216f8ff3af4589c602b82d74ff48bef23afdb72cc0efe8228d2e5a90768736bcd75b7e3a1e729787bf6b155315035d054dc979c8b7bcc70d484387ddfb43f61e58aea8a0465155816adc6fe493af894fa4b78854e636a1a712387d0f7cbf018b4dc17df65cf736f86f7a7a6e138ad1f7de7fd5fb07c95ef60311baf994cf98ab5871e1b9a86e7bc61545e7759a4fdfa1918337eece816c7ae432fdac0837234bae164732ae65e069c9ae6ccde711210b4eabefe16b8a88136fb4c9a337bdac9e22d95159fda0a98014c3ac14d650397a739b37736d4b8aa453cade81e8c1f01e666d0b56dd39cd9050d912519819cc4a227c6bc891e37d994f816b826cd995dd1842f1ddd80b5cde03efa6a9a33fbdee319df0d96f51942d23e0d23690135c436ff27e4b59b8fabcc92a628ecfcd70c2a56e704bfebfaec83d84ee98a760cea97d2a51da52f4fa5f2da4b7492564d40fe8c08c4ed69d151b82f1c49f13b4fe21bd4efb0c44e5f3750db3304e52bd7e945eb43fb5355b176ed40ffafdfada9315a56ce96dba651b664f5e1c7862060fdee572cbfaf88b4ec39c0d4ea97869684346776e8c5aba639b37f440fa26d4e45bf87a539b3779c0cbe49019266074634e0662fcb8a4f954e223fa962f4d8bdd74f601b0bd29cd9a7a639b37736e4b8aab50d7720c87d3c7ae24753e2936a9256eb9a6f88e304284d7366cf47b7d0781b7d3ab9a97031f052567caa39f465b431aeed1042b49da65753f75493b4e319df0d42d442ca434d02ae0c7bfe9b642c8bd760fdeae76353bd6a3da855b7878dd7dc8d67efbee077fdd3dfc5d23619cdaf1c7e1b7e3f6aeb785cd75d4ac9f47fa0b44bd2a7390d84305805cd6ea56cdaad54dc37097f97f6087e7ffd5e70a24069d6724aff58a9af5e59c5ce692feaa6b7d5e3c0eb236a606f06fe32379814e22fab60dbe42738f8f5ff82c6b987e593560b8e997330afcaa99db12a000fa767649ed5cc2d3b8e84441098461b046c6b06bbf54a9a333be764508b421e18e31a586169038c3c596e010122a1020fa117eb6e687c17507f4ea83a1b723dfd085c07b89ab05bd34fe44b4ec8b16a69ceecdb80bfa127fc34156e01fe15ba6f4d701ffd1a3da6b6a9706b4310d5e3266a2171699d80e70889add14c26cc39db897ae7b3062b582ec8329efcfd6c9c3019a54c57554dc989f4fef415a4e8a8fa15314d43707bf09c9f46d9d45b715f38e2cf1387762cd034048f17efd00154fce3465c13c72368aa6e4b120155db76b371e2bd14ce4967e743cf53fadb3244b3fe1cd414859861a7d1fbd35774af3b40a9a864f3a429147dfebd7ede8e66d76489a8b7e662dabcb376428a1dbd80bbe324216bbb03aa46532a010af0df13f98069ecbe0d600c0d1b301f079c7d32d5fd0c8c41177a566843becde60037a739b3b5468e61fa16b8b609bb746363dd3b02ffff0c188b9e59d8549896159ffacf261ecad39ba8dd02e0e78618e30d69783b1b5d720d302a01c9598263c67ff4d24862c3c5a30a2699caf55bd876df536801e2b0efbd79f84bcbeb0d80d7ec56ca1ebd9dca4997a126c5eb9614060e0fbf821aeba0eae251943e3f0535253122b9152411a5b482edf73e49e19cf4b0c40e4bfb36f47cff052cedf4c424c555c5965ba751f2ebd2f022ef00aaaa7bab2987300f1604840a17f68fbe46a86bf1d21ef80668d1150baa2fec3467f66fe816184d857c9ad7146c433cb453d07dc41a1a63d08bb59f14fd14320677a34fa53514f1bfbfbaec566390b45a6d7c0dbcd504dde901dc8d3136aac95a80206c419fe56acab7d687b2e253cf6dec17bd90f6d600154d70dc3fd14086dfc74cd4aad58a8072f108ba7962cdb3d4efc73eebbf086527ae7f8abecc60df079fb3feb25b2998350fb1b635477595826e1d287bea5ebc83fa1ffff4eb9f158280bf4b3b4a9fbd0fdfe97d0326bab58894a0677406bf5755cc6d9319f8bf4fb0b4d7499aeaf6b0f3e11738f0e58f35a74955112d66ac9ddb933cf132ba3e35852e4fde4fe2656330b7490a1677af0d79fd56ac19bf45fa6d547a46e63d274bd7a739b31f474fed6e0a78d03d9d4e267447cf686c680c063a9ea4750fdf6ca0edcc4d7366ffd8d864368474fa8199e87e658d894ac0df58632334062ecd997d103d566b75138d9d38e0c9800d5153a004d8d404edaea0818cb01b62ea7324f0efda0f75f36f2b30af587f82b983c8f6fb9ea4e4e73f106b175057551005dc178da4f45f77a2b44fd6e3ac0c1cc7bbb08a1665a7fca19b714db8182dcaa65b714442803cf59df73ae6143d0149f3fad8fec0d3e4bf3d3ba8b8698a8abd7b67babd348dd3967c41f7d7ff4ddbbbaea5dddd93e8f9de0ba4fe3a8f8e0fdd8e6031d74d183199b0cdcfc49cbd2992ddcb3fd333327b9d04ea4ff57faf6ba25d684df3f2233adefe14d00b4c5b4f5013d7d73a6f27439f91e6ccded0009b2a45b79e696aace7f8aa511c0b548ebde273439cbf03c065e806c24d8161c0dd4d746d54a23bff3736b6a539b31be49c1f33510b2869f1c05380144ad2c4a262a2fef355a364500a661342044f36cd6aa1f2ae09b8aeb908cc2663aab3c16e372af815dc178ea07cca0da889ad229f6741004164db7d4f057fdf7ceb540a677f8d14650f6ecbdea333fdbe7a9ba46bc6473c8fa684383a3c7033fdbf998528cbe16d09802810f5c6a74845076baba56d81c7d23332c5961aab56fd661cb8d1fe012c6a825d88054e39198847e018446a253c35306e0b55700c84e1e1346776455326a55407dba3fbe7f9fe0c9d5e2be6f562f4e9e7a6c06359f1a9fd1b3bb100bdce6d41231fab1bddd2a841704c442d3d2393f48c4c01b81add01b806b244cc73ef80d7db600904470bcd6aa17ceaad78d24efff3faa29df04ed6f0f7ee46d99393f5125b11c91a942f5f43f688abd87edf931cfc2ab3a69ea7a621da6da4fe321b7320760d4dc3b7ff20b9d3df61d3a429ec7df53f7aa502207ad0007acf7eb56e7c9c2020547970bcfe49a4bdbc1add5b8d964ed60268aa58b58b01a1a593b5403fb6e6c49a829ab3e253af6922e5e0443ee83b1ce766b6035f3775e67048db597f16a2164ad6800dc0bd4db82b736aed4f631cbb46e3276495d38019c6474dd4428c6ddba1bb4987903419fbc7e988b9fb22c682698a82eaf1a27abc7ab07843435150daa750fadcfdf8fa766f52154dd3b43a9f96b0eda382aaa226c651fae283f807f48c9c412b0854acdd44febb73c3124ad42a37a7bcf534a2a326e3b3f0e32f59deef7c763efa1245f37f60fb43cf913de2aaa06f5edca8a124fdedd2c8f16a5b7763cdfc23d2cbc1bcf48ccc98964ed60237b595344d06e838406cc92a51c843e1e64668eede5aa4e064c099c7b9fe7ca0a0b9f4499a337b2b4deb33d624642d405a3e05fe68a2dde893159fda14611ca534eed4b30b5d556b1aa2168277a955c753ced986e57f8b23d6f054dd1e1ca97de834ed2e3a3f7a378ed43ea8de067ca15154fc037a52fed0cda8c98908dec67d5952350dbfa2e0f3f99124899898685a27c693dc3a91c48478ec361b7e4541558f7daca8aa8aaaaa38a2ecfab693f46d473b1c88a288cfef435194c6256e8a8a6636517edf7578460dd6ab3dd4e66ab2145e424a55b1f5e842dcd94383dfed7ded3f6cbdfbdf683e3f52941dd16c42b2dba8da99cbe69b1e44ad72239864dadc741572abd8bafbe153b07efd33627169ed5f620864ac9d0459a0fbd103541b1bf1c0692dfd2115c0ad8dd05c6a567c6acf93ec397f3c1e71c5c0570d15afd390efd3fcc91050348b811769a08cc4a38400dc98159f1addc8a4bd92c69df2f5a017aa6f10c847b3708867da04f46af535bd5fe5c6f6cd2f08955511ad38dade3191ce8f4d46b4d94080f6536e61f7e3af903be3bdba160d472f31a1f4ec42f9fd93d06c9646f54653038a56627c2bdab64921b9750266b3195110002164e655c355e5665dce268a0e1c443ccaec534dd368939244bf5e3db158cc61dbd6d0f7a1aacacdfea203e4efdb8fb3b8185114111a63fa59d3d02c662a6fbd0ac1e7c79cb5aafed2533ae3c4deb70782ac0f3ff7ae3cf67d383fac687b90ff9b4db8366e27ef950fe8f8f0ed546ddfad6796d6595040da7710dbdc0c2aef9a505b4dbd283d23f382f16347ffd0c2c94659567cea72741b88c6c6f5c0f2165e3a2a157d26e0444346cfb27baca59b0487ecff39c7b1992d69ceecc5cdf098feb44873667f95159fba183db1a6b13134f069cc298eaa0051931ba9bd06cd963faa9d0e903407f07438471630addf8a7971365a2dd2a5292af1178ea2dbf4a975b6d7f989fb29fd6325e52bd6061fdac72033a174ef44e9137707e9bad56a413bc4cb9228e824a2d25589abd285cd6e3f6ae224cb32769b950eeddad2a9633b24513aec3ad18e28ce1c7c3a4b96afa6b0e800e2117acba9aa4a4a7212834f3bf49cbe29da414cb483ee5d3be3f5fad89d9b47eede023c1e0ffea39c6ad6348db2d252a263a271443902a4543de4bba9c7eba57cca0d44ab2ae6c56b0ee99d27869c6ff7ae3c7c078beb5fd66a21ffedd91cf8f2072ad66e42b2db22c63f6a2619eb8f5978479d81af5f8f50b2160ddc959e91f91be06ac9ca1af03b7afc437423b77b0d70470b7fc04d6ca47644e09cacf8d417d29cd9952dfc814e567caa05bda4d9b162667322487f669216925800fa14fdf226d80d13704d567cea2f349e65899bc65510d5866cef58d8d11d40c750922654b9897af773b45a658034bf1f5bf7cef47a5f3706aedab293d245ab88eadd8de8213ae948b9fe2f142fc842b2d91164f9884b0901a028f806f6a662caa42069136599e1430761b35ac3846d0d2da82eeddab98b1f7ffa91050b16b076dd3aaebfe956faf51f80720832a3691aaaa6111d15457252226d929348886f754c9dde3625897dfbf7139a2c7b68a2a6d1a15d9ba36ac36c36d1a35b177a74ebc2fe030729d8b79fc2a2225cae2a44513a649e872ccbacc95ecdf4679f62c488119c7beeb98c193386534e392548ca6aa3a4ac8c45cb56a2b93d54dc7b1d51f6cfb02c581239a1441070efc90f2a9f92dd866832d5af4b0b024a5939aee2d29a8cd1face93cd42d4cc3994bef8a0fed250330d7c11306afcd8d1df87c459b654a256d604442d2e2b3e75509a337b454becb440b1fbf31bb1c97e405f9ab67c4d436164e0aa3f1679be32cd99fdd19f9d203553b2b6222b3e358bda09818df7e23725cd99dd5831b71a8d3bd5eda301a75a8f58460a647a26a3d7ef0aaea7d9ad44bd3f1fc1595a378140d5e839eb3944ab19f7ae3cb6dc3295edf73dc1e69b1fa27cd91a00ac9ddad171ca6d747ae46ee2ce1e865ae54671551dbec8baa6e1efde99ca3baed66b3e6a9a6ecaeaf793bd3660f923d47c0441c0e3f13079f264468c1cc1edb7dfce679f7dc6a68d1bf9edd75f500f11d7e55714cc2633a9fd7a73e690d3e9d7bbe7319334afd7cb1b33dfc06c39f2ea35369b8d679e79e6984f7252620203faf6226de819f4ebdd1359960e494acd66339f7cf8014ea793afbefa8abbefbe9bd1a34773c71d7750565616d6afd59f0d1b37e3f3f98284d975dda578870c8818f82f4812152bd7e129d03d271da7f6c5d2b1eda10f4210224e8d465a4e3c5882ed8b4cb4ba2aedcb27c14dd6859eb1d614b8a25a19698118825e8fb3d1882d302a40105b2442cef3d9c748d200de69c163e6cf80279ba85d2bf097466ccfd3c88a9ad690ed1dd14d244481f81bd02df8832462cade8879e93aa8ada6797db4b9f96aa2faf54453550a3f9c4ff9eaf5208ab8f71450b5331780d8b306d375fac3749c7a07fdbe7e8761fb96d1fde547b19fd2f590b61a5a7414155326a1b68a09e3c9822070b0b884bd05fbc26588df7fa753a74ebcf6da6be4e6e68605dc67af5a456141649b154914e9d3b307179c3792ce1d3be84add31a0b8b898975e7a89f8f878962f5b8ee928a67a4d26993fb2b2484c48e0dd77dfa5aaaaea9009039aa6b16fdfbe5afc45c06eb3d1bd6b67c69c7d165d3b778c18bf268a22ebd6ae61d7cef05abe7bf6ece1cd37dfa467cf9e2c5b162e12e4eecda7e8a0336cfa58339ba8b8e7ef281dda443c8f9a00bb1ed379936092e9f6d2b406ad1a615e9c8dbc2baff6d7a7a46764dedec2a73e013e69a27647b760656428d0d8cee8571352fbb8252a2f011c4fc6e72b869ad67c5535602db0b38976e3ce466cab451ba91ef6c9189240e0a0b61d87cf8fedeb5ff45a9eb5c9457222c9d75e8e6092114491844b461377f69920803939017baf6e11db9363a2697bdbdf386de997384eed5b37705c55d16c56ca1eb90da5757cc42c434dd3d8b663378aa2e2f7fb79fdf5d71931620485858511dbdc9b97cbe64d1b83eb6a9a86cd6aa543fbb69c3d62383dbb77adb34e494909797979f8eba976b06fdf3e162f5ecc73cf3dc759679d457c7c3c53a64ca1b2b2f2d802fc0581834e27b7dc720b76bb9df3cf3f9fd75e7b8da54b975254145e0dc5ebf53268d02032333359bc78315bb76e0def63596640dfde8c4a1b4adb362958cce6309b8fdf16fe42656565bdc73564c8105e79e5157c3e1f7e45617dce662449aa7d124092287be26e9436adeb58a5886633459f7fcffecfbed355b5d43ef49b3f532f19d500c920524111e645ab2391c4e7d23332add563bb85de64bfa569ac0552b2e253fbb53455282b3e350a18d104cd9f0a746dc90f88807fdab12a914b8102434d6bd644dc49d3156d1f90159fdada38130da4a805f03c10365f67dab80379c3d6c8bc4296f0e6d71023476a1ffacc7b9deeaffc8b76775e8b63606f5d8d79e16db6dfff14fbe77d8bcf59125c7edbbd4f50b6343b3cc920905d5871cf44942eed117cf53fab2a2a2bd9b4652bb7dc720bf7dc73f8b28ff3e67c8acfe723dae1a04fcf1e0c19742aa9fdfa60b3864f51969797f3ca2baf3066cc18ce3bef3c56ae5c19f6bbcbe5e291471ee1a28b2ee2cc33cfe4e1871f262babe167aa323333f569dc1123b8e4924b78e1851770bb6b6c5b2c160bebd6ad63e4c891bcf2ca2ba4a7a7b36143781518475414a70fecc79041a7d2b34737ec763b7bf3f258b2f8f006f8f7dd771f93274f66c5caecfa131502a4bae2fe49a84909750898e4b0b363ca3314fff41b00b12386107ffe0854dff173104d96b07ef72b627159ed38b918606a0b7e7056fff7bf4dd07c3230b005765b1cc7672f713c78a0d6796b693805483ac675e7d288f52d0d1c1359f3024b681aab0e38b155424e1a1c76fe2da0a6752150c32e0845c5fabf45082e7744df345f91934d373c48d25f2fa2fb6bffd659a1c54cf2c4cb822ad9ce7f3e4ffe3b7300d83ff71bc4283bd1a7f6c5daa91d459f7f5fd7b64314715f3106df809e8735b375bbdddc7efb6dfcfeebc223ea882e9d3b31f8b481b44949aeab0e0508d8dcb973494c4ce481071e60f9f2e55c7cf1c5612a9da2285c7cf1c52c5cb8b0d1bcccbc5e2f8b172f66c99225ecd9b3871933660455b3193366101515c5d34f3fcd4d37ddc4e0c183916599dcdc5cce3befbce0366263a2898976d0b5530792e2636993924c41fedec3b6fde69b6f5258749049371ec243545551daa7e09a388ea8b7e6d6f1b7532a5d6cbee9614e79f7593cb9f914bcf7d9f1dbb5801eabe672639ffd2de5536e40707b427fbd223d23f39df16347e7b5d0b7608059e8536b8d7dbf1892159f3a2f50dcba45f457567cea60a05513edc20d59f1a937b560b2d29350bfcc234709b0a4197aa719a88b35c081e320e4c7838b809986654ac3286a53082d622c08c80545987f5b1991a405550d9f9f7d1fce6779eff3285bb23a982050ad92d97a77478e8b065140f578f13b4b7066fe4efe3b7350c31fac7a79a17e3da81a77f66177565555befce2bf11499a200858ad56626363e9d9b3274f3cf1040505052c5cb890f6eddad62169f9f9f9f8fd7ea64f9fce934f3ea9677faa2a0e8783e4e464faf6ed1b5c76d9b265ac59b3a6492a05689ac6f7df7f4f5151118220e076bbd9bb772f0f3cf000b1b1b1288a82a228e4e5e571f5d557b36ad52a76edda15dc57411030994c0ce8df8f952b57b26ddb36fef9cf7fd2bd7b77626262b0582c11a76cbff8ef67fc9cf96344721b7aee3c234ec73be28c8879374aa58b0d57dcceb6fb9e42901a2e4e4d33c9587e5d8ebc634f6d5fb73e04bcc85ae2f467409d594203d6923b0a0c07a25b824214b28fd736e16e08c08d2d51550bd8720c3ac6d537009b8c476c8b400eba297153a06f567caa6890b4e3246a0135ed0242b37ecc26ecefcf473b822c3c4196f114ec67ddc537b2ebd1197876d728352993aee0d4c55f9074f53804594653550451ac9bdda7a8a889ada8b8eb6f471470eef379292ad8cb902143183468106969698c1d3b960913263075ea543efcf04356af5ecda64d9b78f4d147494949095b7ffffefd7cf1c517ecdcb993cb2fbf9c458b1661b7db31058a86dbed762eb9e4124451243abac625a17ffffe5c7ae9a5c8b2dce827d266b3f1f7bfff9d848404344d439665ce39e71c3efae8a3308225080276bb9ddcdc5cba74e9c292254bc8cbcb63e7cef078d26eddbaf1dc73cfb175eb56962d5bc6ac59b378f0c107b9e69a6b38fffcf3193e7c3883060d62d8b0616cdab0168fc773c8d83bc1eba7f2c62bf0f7e98ae0abeb03285acc88e6868fbbd64c32f64fbf45ab3b6e6ead8e556ba1aa9a0fbdb07463e334a0754bb8b106d43413706913efcaf521e7ad25bd0c58d1b3658f162a9095e6cc2e31e2d35ac4fda40268aad9051bd0cb380b87867c1892464079a8098895254cab7290376caba3a6693e3f9adf8f68b584c505099204aacaded7ff43f14fbfd3f6ee6b49b9f60a9df3a5b4a6fbab8f9174d5456cbbff69aab6efae29dc1d506310052aee9f84161b0d4760dc6ab55a79f5f537689dd00a9fcf87c964c266b361b7db0fbbeecc9933993e7d3a0909097cf4d147b8dd6eb66edd4a46464690a8b95c2ede7aeb2d468d1ac5ba75eb38f7dc730170381cbcf1c61bdc70c30dcc9b378fcf3fffbc4ef66543a34b972e5c75d555fce52f7f61c08001984c263c1e0f5eaf976baeb986db6ebb8d5f7ef9a58e2226491276bb9d193366306edc3856ac58c1abafbecaecd9b3e9d0a1032346d4c45ef7ecd9939e3d6bfc2e2b2a2a70bbddf8fd7e2c160b7b0b0ad9be6bcfa195444d03012aee9e48ccd4971123d9b99c103d4340deb20bd3c6edf84fe9121a27371818367eece85f5ae8b5eb4577f6beaa09dabe00d8d242fae9ea66b00f3db3e25307a439b3d7b6a49781acf8d43874f5f968e1033e371eaf2d8390075e209603e736c12ed803fc22c7381bc7a0a805489a1d9814f6bc95246c9fff5097a4a92aad46a7d1e5f1fb90a21d7533f70401c164a26ac71eb6dffb141bff7e1f4a6555407593881d31047f71691d5546f0fb714d1c87bf53db232269d59c606f412116ab95949414121212ea25690b162c60f8f0e1e4e5e92f142b57ae64e4c8912c5ab4888e1d3b228a2237dd7413ebd7af0f9bde1b3870201d3b76ac63f561b55a193e7c38afbefa2a050505ecd8b18359b3663169d224faf7ef8f2ccb28aaaa977ed28eec58344055546459e6b4d34ee3e69b6fe6934f3e212f2f8feddbb7f3cc33cf70fae9a70789a4c56261c58a154c9c3891b4b434de78e30dba75eb4679797958ffda6c36727272d8b66d1b8aa270df7df73171e244468e1cc9b7df7ecba851a378e49147484f4f273f3f3fb89ec3e12031315157224591dd79f94756d941d55063a3a9bcf12f8d43d2aac75065159645ab2365933e1532d65ba2aab60e286a82e62784a82ecd591102b8ad19ec4e024d53f6eb7871ac65a372d39cd92b43c6a981e67d1f013d4ead296025d440dfc0d1296a8124823ee8fe43015a27625eb319696f5d9b0bd16221e9aa8b69fdd78b68ff8f5b587bc175942d5e1d2c2314a6920970e08b1f29fb7d05dddf7882f8f3cf62f793afe32f2eade364efebd31def5983e0282d2d2a2a2bc9d9bc8dd307f63b64fc546161217bf7ee0d1218499258b97225e3c68d63d6ac59f87c3ebef8e20b76ecd8c1cc9933916599eeddbb63b7dbc9c9c9212a2aaa1e2147082a5e37de782337de7863f037bfdf8fd7ebd33f7e2f8a5f2fb6ee5715044d4092444451449225cc261366b389552b971f3a0eac169293930158b870213ffdf41366b399c9932793939383dd6ec76ab5525959c9b871e3f8e1871f18316204a5a5a50c183080458b1661369b993b772eafbffe3ae5e5e50c1d3a94458bc2b3413d1e2f6bd76d443d4a3b0dff809e7887a5625edc783771d3ca1cc40b47a22686c5949f999e91d96ffcd8d1eb5b68b5824dc00ea0b153dc8764c5a74635e7f24801452899638fb16ad07705f492526f03e5cd9dbc84a82cc73a65fc46aded1868fed8d8841c24c5e8fe6350d4421486a7c27e50144ccbd7e985d76bc1d6b5030997ea2f8d55db7753be740d681a8efebdb075ef8452e90a939044ab055f69191b27dcc3c66beea1f0d3afeb923151a4eaf2d1baa9edd11e9828925fb08fcddbb61f76395114c3543197cbc595575e495c5c1c9aa6919c9c8ccbe562c080010c1d3a94254b9660369beb9034bfdf7f448904b22c63b7db888b8b21293191362949b46b9b42a7f6ede8d8a12deddaa6d0262589a4c404e26263b0db6c4744d2ea6b7bcc98318c1a358ae9d3a7e37038b8fbeebb494a4ac2e7f371e9a597b27cf972dc6e3753a74ea5acac8c7ffdeb5fc8b2cc279f7cc2c89123b9f3ce3b99376f5edd2b7bcb564acbcb8ffadc68161355575e8066b735d2635240da5b8869fdd648feea4fb6d48b37cd3c4a17b90000200049444154995d06aca2714ba354e36f2d404dbb8ca3b3203a911805b46929b17d018c3dc64dcc32d4b416871d4dd876a211cb780c442da0a675a0566d3cb1a41cf3eabac45bf3f96973f335c160f05dd35e44f5fbd17c3e7abcf10403323ea4c7ab8fd5d469d43434a53a7140a6f87f7fe07796d6925d143c69a7e21dd4efb0561cf5419224b6efdcc3ae3d471e27a9280ae79d771e975e7a29f9f9f9288a42454505f7dc730ffff9cf7f4848482021a1c6e0dcebf5b27de72e7efb63296b376c4251d4a326520d05bfdfcf1f4b57b06acd7a8a0e382312b6f7de7b8f3befbc13b3d98ca228f4e8d1830e1d3ae076bbe9d2a50bf3e7cf67ce9c397cfcf1c7baa1addf8fcd66a343870e61dbdab93b97ddb97b8fcdbc5751f1b74fa1eacaf3113cde4619e89a49c6f6f50290ea88c869e919999d5b9a9a167263fb86a671ddbeaed67e3447a2318663ab677c2260032e6c41e36b28c75655213dcd995d613c785bdc4b5f7913361f03988db37094442d80fbeb2841db739176ed0d57be540d537c2c29d7eb65bbdcdb7673e0bb5f104481c4cb2fc0d6b30ba6c4560816334a956ec86a4a4a24f6ac33901c767d6a5414c3950e4d43b35b71dd7cd5214d6d8f141bb76ca3a4ac7e2703555571bbddb8dd6e4c2613efbfff3ead5bb766c3860d7cf7dd778c193386d8d85862636383cb179794b274c56a7ef8f957366cda4a4959193e9fff90e28620080882c0c48913292a2a62f8f0e1bcfffefb2c5fbe9c8b2eba28380dfb97bffc85e2e262befffe7b060c1840d7ae5d193d7a343939878eb7d43470bb3de4e517f0c7b21564fc6f21399bb75255e50e4e4f9acdfaf5d0ab572f0a0b0b71381c5c72c92558ad56e6cc994342420223478ee4b3cf3e0bcb5e0d2599c525a5acd9b0f1c8e2d2eaeb0b9f8faa7167e3efd9f988630f0fcfc6340449448ab2e9092da1a742149176ef455ebb19c2d5c9389a3e2bf098c9489a333b03bd8e5d636340567c6aabe6aa9a64c5a776017a37b3ddbaadb98fab10827585a1a6fd391072ce9d4db40bd1b4e0526b4d46d4d233322dd42e981a707ad7cce12fa84aa58b8e8fde1dfc3bf7e5f7405110cc2692ff7609a2d98c5251c9fed95f078d6e13c79f4bff6f6671ea6f9f63699384564b8512fc7e2a6fbe12cd241d59c4fd61e0f3f9589fb3196f045b085555d9bd7b3713274e64dab4690c1b368c071f7c909c9c1c2ebffc72ba76ad4978755555b12b378fdf972ce797acc5ecdb5f14245f470393c984dfef67c58a156cdab489dcdc5cbefffe7beebdf75e144561fefcf9b8dd6e727272d8b16307d3a64d63c3860d8c1b378ef223986a1404014914f1f9fc6cd9b6831f7ff98de5abd7925f5088c753f34cff7ff6ce3b3c8eeaece2bf3bb34dabbe6a96abdcbbbd601bdb20d30d988049680925014208a9a413be24a4939e909e90423a8404124a0204d39101838d17f75e70932d69d5b7ceccfdfe9891b44db6566557327b9ec7486877e6de99b933ef99b79dcaca4a9c4e275ffffad7f9d8c73ec6f6eddb59b66c192fbef8222b56ac40d775344d8bcb410b0483ac5dbf0175108a0144244ae707af069badffc13b4362842308bb8dc2c55ec67dfec378573fc8f83b3e841189f7d6498703f7bf9e4a5cc336e0fc479f58e51ec10fd8c7b230bcddf2580dd7a28299985df58713a6d779bcc35ad9218660bda31f9b1f055ecb79d346e64b5f16899a93e1e3f91e96e8ede4ac003c71de884347b16fdc8174da63590ece71d5545cd5e3d11f7debb5d82bcbe8dcb483a2d31700d0feda065a57afb3da76c0b8cf7dd0728018840f1f43283144473788ce9d4e74c16cd00747d542084193bf992ddb77e29d135f6d3e73e64ceebefb6e66cf9ecdd4a953a9a9a949da3e128db273f73e8e1e6ba0c3d2c0b40fb0579a10029bcd86aaaa0821282929e1ef7fff3b575d75152e4bf85d55550a0b0b59b97225070f1ee4073ff8015bb66c61f1e2c57d1c83eedcb6faa3c738d6d048516101a347553179e2846e8f58696929a5a5a57cf5ab5fe5e69b6fa6b3b39369d3a6a1691af3e7cfef2e4cd0759d4d5bb7130a0f9203474ab4b1d584cf5e846bd52b7deacb174bd0f4ce008e51e5545f7b19e5972dc73d7b2ab622b3af5df1998b715657c6c992a12ad8b6ef43dd7708235e53f40c60fca34facda369242a0310fd87bc87c1b0a276655e003c3cd7b52e7f1aa983961ca30bc6c5f02ae1ace89f6751eef7860743f365d05b4e6bc6923162d591ad7314cefd5614fd49663b6e6b03c11365c4fbc8454e23d4786a6517ef945ddc611207fde0cdc73a6816e20ece6eef77ff3e70855c50847187deb75d82bcc1cafdd9fb90ba9ebf115a14e3ba1f397225dce41f1a6f5704d857d6f1da4a4a8889af163bbff3e7ffe7ce6cf9f9fd22b6618063bf7ec65effe83442d6f5cbf72b24ec85724a5a5a52c5ebc98af7ded6b1415c5174f442211dc6e779c707a7f8e1fa0adbd83b6f60ef6ec3fc0f42913a9191f9f7b169b8be67038f8f6b7bfdded35dcf7d6410ed71f1b146f5acf0a54099fb908c7dacd88f63e14114a90ba86bdbc8cc9dfbf838aab2f41c97727291a384755e2a8ae24dad492145677bdb096ceeb2f4544ba895a0970c6ca15cb476427f55abfeff93a8f3744ac7a486630a7cee3f5d4fa7dfee1722e2c6fce706872db1b2ea9f3780bac26a3c37649f5632d19c08bb57e5f286756472c22591ad796236a691035abdab38ad86ed44220c2511c2fae057b3caf531c0e0effeaaf0477ee65ec27df4f817716b692229378c518f3b24bcf45efe8247ce030355698b463c336da5e7b339ea401dab86aa2a7ce1a5492d6e35051d8b0792b458505784a4b928897611844a31a9d81006f1d3ac25b070e6218127510658d5455251008d0d4d484aeebdd95a30d0d0d3cf2c823cc9b372f6e5e4208745de7d9679fa5a2a2222e143ba03b3212c1b7710b9bb7ed60e2f8f18c195d85cbe5c261b7c79d93ae3cb5c6a666366cd9862d8d16217d64a968b3a6109d3109c7eb1b7bfd8e94127b7929ee1993a9bee51aca579e9ff435bdad83d0be831cf9c33f39fae77f2135ad5bae2c765ff64d3b503a83c8f8f57c03f0fb11daa6034c01ec1b333ce674a086ec854c529156ea3cde1a60ea30bd4e76e07ae0d7c3d8abb614d3639a0eea81d539939a437fac00d9a95c1f9944cdaaf69c002c886137385e5e9f52f6074ce99f96675fa679551dc54b4fa5ec9273f15c7a1e799327747f67eca76ea6fa966be8dcb015b5c4f4be1dfbdbc3188160a20b8bf085b51885f949e2dd83052104be8d5b58b2e8149c0e07a15098ce60908e8e4e5adbda69696da3cdca03531405551569ecfbc41eb7cf7ef6b3fcf8c73f66d7ae5d84c36156ae5cc9c68d1be9e8e860eedcb95c71c5153cf4d0434829d1348d23478e70f3cd37f3da6baf71f7dd7753595979dcf1d3258d8621d9b17b0f3bf7eea5a4a88892e2228a0a0bc877e7e376e7e1ce73d1d1d1c9daf56f0e3e498b214fc12b2ec0f1da86a4839086816bfc682adfb312cf85cb2838754ed2e6c19dfbf03ff512cdff7b919617d698ea184e473249eb5ab3c7fca8fb0fa14dad897d2158f6e813abca57ae58de3842efe57f6481a895616af5bd311c08470cf119ce49fb2a70599dc7fbebe148d2ea3cde22faa746b0abd6efcb75971ff984291b08939dcaf59149d42cc4b913a4a2e0786d2318c7a966b4db1176685bbb91f6f59b39fcdbbf53b4f454267ce963b86acc30a35ae0eece590b1f384ccb73af5ab24296613624466519a173170f1949eb426720c0ea57d7a2a80a866ea05949f3665851f4ab9a510841734b2b814090c2c2825ebff7d5af7e9577bce31d6cddba95458b16316fde3c229108b7df7e3b0077dd7517252525e4e7e7535b5bcb9d77dec98c1933b8e79e7b983871e271e7e06f69251249df7bdd95c7d6dad64e6b5b7b5cfe9c4d55886a3a514d1bba0ba2eb6853c6135d3417fbab6fc6ab5e58cb6efcff7d3869b3f6b51b38f8a37be9f06d2672acc9f2a0a9c95ab189d7aab90ddbee03685326247e741df0939176135be1be2d965723d3cd232f02ee1b0e0fda18e273f330bf64b38139c0a66138b7d1989ed274f1cf04b29cc3c843618ea88d1ca276559c116ff0a31e3cda37b262850823f50d343ef4240d7f7f8cb295cba9f9ca6de44dade90e87b6afdb44e7a6ed28793d691022122578ed25089919529f2a217ea0f967916894e756bfcab4c913995433bebbe02076bf2e978b65cb96b16cd9b2eebf2d5cb890850bcd06ead3a74fe777bffb1d004b972e65e9d2a5c77ffd91124dd3d9b5773fbbf7ee1b50afb6d8796a9a86a66919ebfb20a25102d75f4a49ddbab8a202a12a746ed949dbea75149db100198de27ff205defaceafe9f06d41713abac9bee8abc74f807dc30ec2e72c46c60bc1bf7f2412352bdcd708bc4ae673b32eb69e23597dd07611843a8ff76ca060985fb271988a09c38aa859847f1c30a61f9bff39812ce730f29097a5718339a29606517bf48955e5c0fc18cb8d72e8184a833f8921a8450594bff3023a376d23b4e7005a4b5b7cd84a1108a783a6c79ea6e1df4f527ec9b954dd7025850be650fffb7fc487a50c037d7405e1c5f3fbdddc76b84000db76ec62c7ae3d949595525e5a4a7171114e8703bbdd8edda6a2da6ca88a921631d475ddf4fc4535a29a463812a1adbd9dc6a6668e3536220d9996c4d4b08321d1abca882cf5627f7da3d9b2a3eb65c19dc7fe6ffd82b2779ccba15ffe85e0ae7d282ea75945dc1b89d575a4d59f4d713a927aaad937ef42842389446ddea34facaa58b96279c3483b7db57e5f679dc7fb5a16885a09708a451287036e4af3fb123389da99e1795e51e7f1de0f848711b911c039fdd8ee995abfaf2de74d1b9988b96e9e2c4da18dec15328c3ca2466289bf6160db7710a5a31319dbfc548b527ed972a6fcf84e6424c2d61b3f87ffbfcfa50c3909871dd561c7ffe48b343ff332f973a713d8baabbb22d4f4a66884de7136030e910b31244508e9a28b30353535d3d0e847000e871d87dd81dd6ec366b36153551455b1c28b2a8a5012ac8744d70d345d43d78dee9e66d1a846241a211c89220d89a20873dbe1c0d1067afe0d4968f91938d66e8a5b09c266a3f5e575b43cffaab99ef253b43c931223124546a2089b4ac129b3295a7a2a454b4ea1f9c91738faf7c74cc266cd53b4b663dbb18fc8a2b98962edef047e3b428b0a5ec62cb12fc9f0b83703af66d3505bde3437b0ac1fe72c049c97e1295f0294d6fa7df5c3c5585bb8a81f9bffa8eb1ae430f21073ddcab23485965abf2fe7513b11518b314a71444d683af6ad7b90099e1f231ca5eabdefb2889883f6d7de3c715e90e5b9e8dcb823496f512f2f253a7bca003d320642374c0f891c1e0524420854ebdc699a8ea6054d27ef204011025431ac16938846918a6aaa4df413faf86ab489e350f7c72b60084541247ad0a4b43c6706f6d262ca2f3f93b24bcea5e4acc5282e17c2ae226c369c63aba8ffd343e08c5129b1db70ac7e83f0526f6c9b8e2e43f5db115af9f932d09c05a2762d70cb3030d4cbfa616cbe0fccc8025103781ff0bde162aceb3cde424cef683ae8009ece99d2918d3a8fd799a5a125702c77054e60efa1bbda53054e8ffb34aa61dbb627aed50686c45e564ae1c2b900b4bdbc0ebdbdb3efe448247b42b4a91330aa0646e695d60e0aeefe537748cb94a512e99742e6d0f7eb282cf92fdd403dd644c1777f876defc101edd6282b419bd97bd184b0d9b01517e21c338ae265a731f1dbb7b3d0f7384b0ebcccf4df7d87f2775e80adb41825cfd91d5e774d1c8f6bd2f8b8352a6d2af6759b530d31f5d12756158dc0072db57e5f1478210bc3bb2d6dc8acf30d4cddc0be225aebf73d8219b66dcec27c6f1b66cba83f61f37f011a398c748ccfd2b861e048eef41f1fb1a1cff8374a21508f34a0343423f37b720c8d68948a77f7a88b681d010a17cea573f30e22471accaa3bbbadef89dd36156df6540c771e622095855262db7380c2affd9ce869f3887a67a04d188351518a51980f0e7b0a9638d434582055d57c6990120c89300c33d466a4e1f5b308915414d35bd54540a544643aa74f009a8ee80ca2f85b510f1dc5b67537ce577da8078f12bee8cc8185400544bc33713cff7a9260bbb0db1975c315945f7e21f973a7a3ba53e7be766ede816bc258534b16509c4eec951e22c71a7bf2028540e90c62dfb6176dea8458bdd14accaab7d747d28d1ce3cdfa33996fd301f06eb214feb4c27625492f9a27c69fac9faf00fb80d20c9fb331751eefb25abfefa56c868d63c6bea61f9b3f82d9ec3687918d29591a3704eccf9dfe13d0a498b0e75971bcc7a6e258bb29a9c9ad8c44285b7176f7ff979e773a850be7a235b510dcb58fe6a757d3fc741d9d9b77a0381c0887fdb8a44d3a1d4416cc421803241cba0e9108a82af6b59bb0bfb105595480cc739ae1d02e0f5ba66076fa40aa2ab2d08d51528851558e3e6e14dad41af44a0f44b5e38bceab2ad261470482d8b7ef45dd7f18b5be01d1d88cd21e80480461649e7f6218e6dc4311948e4e08454c52ea7422dada07781d0da2f3a6833b0f12889a0c87714d1a47d1e2642dc1b697d7e1ffdf4bb43cb39a095ffb14f9b37afa9deebcf50b04b6ec4a6aae2c6d2af63736139d3909d143d42a80c9238da8c510b6e7ea3cde2099afe03a378130661ae5fd206abfb2e61ca9f3781f27fdb0df60e0b3c04bc384e49f9fe6a6fb814db9dcb4930233b3346e10d89d3bfd27206a31b9388be34cbea2605fbfc5f208f578add4a242f2bd3dfd1085aa62f79460f7949037b5068f45e222478ed1f8f02a1afffd24edebb798cc25d18b2425faa872f49a3188ce81256f09dd400945cc7cbaae760d6d1d88b661a4d4d2e5659212637425e1b317135a6ec965a580e3f54d389f7c11fb96dd3109ef22f3c4ec44e8526e1020da03e6fc069026285d0ec2a7cdc1f5f84b3dfb06a421697b791d55d7bf13bdbd93a6ff3e87ffbfcfd2fae2eb185a14b5209f59fff839c556bf3e198db2f1e29b687b7d43ea970545c1fee63678df65717f05663ffac42a65e58ae523ca5310e319b91fb3d548265155e7f1ceaff5fbdecc06d1a8f378cf223dd9a3bdc08e9873f61be08b59b86c8beb3cde51d92a2a88696bb29cf485b1dfb4ce630e231ff3b3346e5baddf772877fa8f0f05badb7254f5b01e81120a63db7d20de504a49feec69dd21a568530bedaf6f207ca81e99108273545732fac3d731efa9bf70fa9135d4dc795b723b054d2772d669101d84140729933c30c30e5d245251500e1fc3fda77f53fafe2fe2beefbf284d2da02888a886f3a575947cfc9b147ee397d8376cef690c2c8621494b383e251864a09314519dc8998b92c2bac2a6d2f2ec2bac5b7029af8c59c2ce8f7e99e6a7576368515c13c6323b86a4451b9ad87cc5477a2769d67cd5c616147f6ba2b7752e99d7cd1c4ccfc8bd5918be0c58940da261e18634377d14085924855abfef2d604d16ce5b09662fba6ce39234bfaf0375b57e5f34e61ae4307231274be3ae49b88f73e88da8011388ad9652156c5bf724870a0d897be664d402539fd2ffc4f36cb8e806b6bcfbe36cbff9f31cf8fe6f687de9758cc4dc229b0d5b711146309c44aec2679c7afcf05f5f118924b65918e6675e415a558879ff5e45d1777e8b6df75be4ffee9fe4fff23e94c666a4db0523a9379a001118044d665d273aad06a3bc24c90bab7706881c6dc45652849a9f87d475dcd32731ebbe1f772b5f441b9bd9fade4f9bed3c1405231245efe844a692418b6aa807ea13d7fa6cc06569df8e28580fbc7540a6a5b06cc0a23a8fd79e69725ae7f196925e5b0e0d78aed6ef4b7cf07c230b97cc099c57e7f13ab24ceecf4873d310f058c23e721881a8f378c790f9fccc2efc37b786faf67005184b4cb33ba9aad8b6ed4526b65910e09e3119c5e9406a3a1debb760842304b6ec24b06527fec79f43b89ca82e27eed95329bfec02ca2f5b8ecd534c876fb329f1d395f3a6ebe8d3276214170c8a6494a20d2c59eb78d13a055010dd7bd791ddd9b30aa2bb8599b43e93e98c2104d26147397494c26ffebaa76235cd790a6b2e5d5b1ad65cba3e5363e61ffb59aafd407f239762f01a164b4964f13c5c8fbf18df945688ee8200198952b8683e731efc25b672f33913da7b800d17df44f8c011d07584d341f9856752f6ae0b68f8c7e3b43cfb4adc3211d128eaa1a36833e2c4eea790f906a88309cd32a2376578dca540699dc77b2cc30fde748f730fb0210559790a6822f3fda4cec42c2cd89b0d8355e7f1ce267de9b1bdb57edfb69c093d2930238b44eddfb9d3df77a2362ece30290ab69dfb92088362b713dafd16811d7bb11515d0e1db6c8695ba0ca7612003418c40909617d6d0fcf46a76dcfa05f2a6d620a3d1b85e6b42d3899c3617a10f82174c00a170bf795a0483ed5a9009aa8b3ca1604398edcaa541a734d8aa77f2a6d641a3ae51acd858662f66862d0f0358176d67add64ed030a8521d2cb21532d1e6220f05bb5010401449aba1e137a24cb5b9517b216c22143eee21d61b513aa4c618c589430804020d49481a34ca286b226decd1434809d3ed6eceb017512a6c1c95519e0db770500fe1120a33ec6e4eb5155222549c4241456020094b8363324a1e2aa3147bda644dc2e0784701a19bebc3f5d873803d25912b5a7a0a33fff66393a41992b657df60c787be84a3a28c51375e49d9c5e7903fb747b6506b6aa1e59997e3bd67510df5f0b1546b67faca15cb475cd9b865e8b53a8ff77f59206a7381f25abf2fd37d91d2d5f6dc5aebf7eded85e0fe19f85486e73f1638a5973965eaba55a4b9cdef73e6f3a4c11c32df7b116242e7398fda0988daa34facb20353e3fe6a791912439fd2303872ef3f387ccfdfc89b3689686373af8d6e85a2209c0e703a081f3a6aee2aa11f5bd43b6390c295c26c7bd10f744a9d9f040ef178d84f1536163a8ba8541c4490ecd582bc166deb691264b9b31e0c353045cdc390067b8c7872f5678e528060a1a398f1aa1305c1413dc49a482bed486ecc1bc57bf32ac943e9734dbb5d089e89b4f0dd8e03b44a8d456a3e531d05b810341851de8c76f056c23c9e88f8f925b0c85ec4ea685bdc678f849b0098a9e631db9e4f91b0d12e75b6463bd8a40799a6baf97cc138e6a86ea269d235118d0e4e1e9d21d16ac6220bf241d3e2d6a2d4754acf3b83a9bffa26f60ad3f91139da48e4480373fff37b9ce347a7dca57bfa642432c6b7685e4fa5b1d9ac5c8d5fcb8b81e747f0bdbd15b33f517586c75d8129109f296fd054cb23d0e79585d952229580b8041e063e49e6b3416f05fe9569a355e7f12ac0a940baa1d73fe4cce7c8479dc79b0764ab07e22f625e2e73388147cd4e6c0f1521508ff97b4df0575c4e7039891caa3f6e882ece78ab4a9237c4a8f0a09795645545c029146e6edbc1762dc0b4e9d369efece4bf07e31bb64e9f319333969dc9d469d371389ce87a94f5ebd671fffd7fa5b0b0889bafbe89e93367a2280a6d6dad6c7cf34d5e7ae9059e3f16ef54983a7d0691fdfbf863b09e437a983bf2c7e114273e7f0e21b83fd4c0af0287092199326d3a5b8fd5f37acbd1eeefa8761bb5b567b378c952aa468d4251141a1b1af8e703f7b37ad74e162d5ec2a52bdf497e4101866170e4c8615e7bf51556afae636ba8a7dad6959f8f77fa027c6face3d36dbbf941e124e6daf2d1d2206b525507ac04d6b33a55b4c9e3b16ddb1d4fd4c251aa3f782d8eca9e0895bdaa9cf22b92d56f82bbf6d1f2fc1a5a57bf4e60db1e44e23917a034b721822164617eec27a78ef07b7b27b0230b44ed1ae087434d3862f67f559a9beac003a90c8495efb60378035890e1f376419dc75b51ebf7655a67b6d07a2949072fd4fa7dad394fc88827696036883e2b0bc377024fe6ae427a44ad2696a8290dfe13379fed2349ebdd5b323a4e787b4090d26c719126415081ed5ac0b4ca0b4fe39aebde4b2814e4687d3d4208c68c198bcd9e1c763be5d485bcf0fcb38c193b8ecbafba1abda707170b172de6c69b6fa1b3b39386634771e5e5515d3d9addbb76f2b52f7f917028c46e3d48000327273e870a82ed5a809034104270e1852bb8f8d29534fbfd343434505c5c4c59797992c0fbec3973696ef6b37bd74e3efcb1dba8acacc2b0bc8ed367cce4ec73ccfec6c78e1ea5bdbd0d4f59192525a5ecd8be0ddf1beb68951afb8c10f345419fc9b4804491f3815d569b8a56331adb965dc49d2a55a1c3b719cf853df9e3c2caa73482219a9f5e4dd37f9ea1f9e9d568adedf1f34fe12731895a3891a8cd1fc93776addf17b444da97014a06875e50e7f116d6fa7ded437c7c5d86e6fc348fefc95abf2f709cfdd6d779bcaf6481a8017c1cf872a6089075fefa43d47e93339d231fd63db4300b2f7300ff003a7364bfdf440dd4c61688a64e0a97ba0e92e490a79448dd006926f50bf5389a8f52628cab8e137a1f30d481d922531149e076e73369b2e960347a0da70a8e67f98510141616525454641daeec8d23f46ba2524a8a4b4a28f178ccbcc0017825475557336af4e801ef6750087cd235b5a18f1d452203173695f657d763442284f61e247ce030ad2fbc46cbf3afd0be7613d230501c76b370e5444d8e8530fbed25e7074e3f09eeefc7814f907e586ba0781ff08b0c3c84676336274e073f3b1e71b1e6fb18661f3a7786cfdb87802f67ca7059867a01e915ce8480677206766423e6fa7d260bc3ebc08396e45d0e7d31ab98fda2dcb1864b696e4de951738cae62d2773e4fe979a7a3381c710d5c1182f2775dc0d84fde4cd50d97e3185d91d4a623966c68d5158939410382314862ec524a0cc3380e49cbec7e8ebb7f5d1f30b9320c6350f6d34dd69d83c80904668b8e04a928a1aab4afddc0962b3ec2c68b6e60e3253773e047bfa363c37614b70bb5c08d70d8fbac44a1b4772282c9851c8f3eb1aa7424dfdcb57edff3405b1686be2143e3cc253d8dc2d65abf6fd5f1888bf5f329b223145d51e7f1aee832a4436da82dbc3bcd4d9fc61462cf61849334abdaf79c2c4cc18729db96431a446d5cbcb105d1dc0689d598525271f9458cfed075ccfed7af714d1883ec22215232f7bff732fd37df66c2576e63f20fbfc8bc27ff44e939bde428e6b9909ee2c13d125505872d7745b38d3c078397a406b2a408233f590d490f8468ad5b8bd6da8e9aef4671397b2d6ce9035b45f1b7a4fa64da487e185b78240bc3cfb6f2ad86f2f8ecc0f23437bbff442428e6b3bf66e9d2dd184b1a8790c477fd7a459a9b3e53ebf775e6bc6927057e92853175e0815abfaf3977fad3236af1a18368d4d46f4c34988681e7c23301e8dcb28b8e8ddb10aa8a110c31eb9fbfa468c929ddba9e8ac38173dc68a6fcec6ba8f1793fa65d74bb308a0b07f94894c1f5e6e4d00f562531f24a5f67360000200049444154f3078fa74989515480ec457cbd3bb4de9b37509acd9685fd04de3545416df0a7fa64ca48bd143186341b6d141cc045272245037d25e81a230ddc77221214f3d94fb274e9bc751eeff84c0c54e7f12e21bdb07813b036f7a01bb988f1a6d5925e93e8c14233f0a3dc95489fa88d8dfd83886aa6eea648369aee59a6dd6a7ef27984dd8611895071c50a4acf379b5acb4894d6d56b09eedc0780ab662cc5b58bccbcb6d85db99c1845f983eb795115a4d399d52ad21c5193c8c2c14deb318af29179a9d59c6c2545149dbe00f7cc29d88a0be3aebdb0dbf0bce36c267deb734cf9e117a97effd5d84a8b53af0f214c19a9644c18e997a4d6ef7b85cc873f6dc07927224503c43c60741adfdf01ecee0b71b48c5923f06c162ed944e0b4a136d616ae4d73d38398aa17398ce01738cb1b7d3b99cf5d05f874addfa7e724a3d2276af10fbba8965a064882ada80000adb9cdfcffc202aa3fd093e270f46f0fb3f5da4fb0e5ba4fa07798855525672f4ed201952e27b2c03d983c0d54c5945cca21ab44cd282a185c02ee7621f35d29c72a5ae265f6033f67f63f7f49c9394b3162142e46df7a2d33fff44346dd7c3595d75dc6a4efddc1ec7ffec2cc5d4b206b520833dc9f8cd123f972c43c0cff9685e1e7d479bce543784c1f4a73d3d5409f141362bef3bd2c9c373b66ab8e2133a231c77759baa7bfd6ef0be61e7423fe79701166bfc34ce3d95abfef2f43fc0277d212b5b8d25c11d55226562304917ab3c5cfa89bae42cd7351f5de77517c9659d91d6d68e2f0affe86ded64178ff2102db7703e0a8aa486e6aeb7420f307b9a04a557b0f91e59021a2064649d1e0127004d253929298bba74d46c9cfc331ba92f6d73798396a52e21c3b8a9aaf7d2aa902b5e094d94cfeee1de88160e21028ad29bb498c19e96fcf59246a931882d071cc315d97c66661e0c514da9e27c2eb985ea44ce39d40fe507a1dea3cdeb9a42f9575c21cbf1c86f7f3c06a70fb0b7a5489328508705b6efdf49fa8c5bff5ea3a229c82a82982e6a75e02c035691c4beb5f63d277efe8feb8f1e15574f8b658c651e0acae3209dcb1c6248329f3f390f6c15d27d2a69a61b75ce8337b3c2d3f6ff00b3aa4c4282d4aca31138a82a3aac7ce04b6eeeace991cf7f91e674b60db6e0efce0b7448e98457c55ef7d5752981421101d295b6b558cf46b623d147767817094627ad506dd2350e7f15e9ee6a67efaa732d10e3c9a85cb56012c8be91537145e957348af2d87bfd6ef5b9df3868cd8674017fe4262016166f07d606baead4bff895a9cbb42683aa41049178ac2e1dfdc8fd69c9ccbd3b6663d7bbffc23d402371806ae9a313846579a4fba3736272913188986723060b3611416e4ae68d6589a4416e60f6e6fbc2ea25694faba76ad20a9eb3dfdfd1c762aafe8f1ea6fbdf636f67ef1071cfce91fbbb7295c382f216f52a074067a231b23fe2d1a68015ecac2f0179252a875c04857db734baddfb7af1fe72e8ad98e2292857377c710ae078033d2f4aafc21e70d19d1cf00ea3cdeef6256f966da9bf126f0a35abfcfc891b4fe13354f3c8b322c61ede42ab9c0f63d6c79cf6d74bcb1b9fb6f47fff410dbaeff3432140121d08321267ea3a7875ecb73af20128cf75078bea4cd862c2ec8fc12cc21868017c0207b4a8504a320b97258ea46b7974ca82af9b3a6a235b75275cd4a5363166879f615c2471a50f25c448f3661844c7b6baff08011af562042296db107e0d127568df4077588ecf42d5a01a88365dc2d0f5305704a9a9bfe790024630db02f0be76e699dc73b7e280c5b9dc73b86f41b3aff3281e8e5300210e385fe0cf0e99e275e467175addfe7cf91fcfec386d9f0b607ba8188f42eacddfefa9bac3febdd2836d5e44486611231ab55829ae7a275f55adc3327d3be7e0b5a4b4292b600236f0872c9ecaa991f95091c5f9860d8a14b7561482125467121d23ed80e1499ba48c430086cdf83340c84a230eb1f3fe7e85fffcdb8cff5843d9b1e7f161935bdc38ad3deedd995e148c27513100ca51a5c0558b962f9c970afbf063490d9706e3eb0b8d6ef7b6110f77936095180be12b5fe908c5abfef709dc7bb96ecf4d4bb0df8ec10ec7702e9e50feea8f5fbf6e4c256238ba059d74ad479bcd7013fc8d254de51ebf7edc8ad9dc1266a522627ffc7d9348162e9390a48960c52140e7cffb71cfcd1ef51f2dddd8632ce28ba86a08d8604a3a4101c76d0f5f4c94c029151140555559152d2dad2426b6b0bc16010c330f078ca300c834824c281b7f6d3d9de8ecde1c0ed765352524a515111baaea3c774fc37f73f70b22484123757555551559548384293bf89ce8e762291084ea78bf6769324373636d2d6d686aeeb389c4e0a0b0af1949561b3dbd1351dc3d0e38ebbbf304a8b0755ebb3ebbae2725842a2f1ebb0d3b795c0a61de4cf9b817bd654267eebf6ee8fc3878fd2fad25a30244255708c1d654a4a610ab5c7ad5b81e5453ea9b10ea827f3797737022f0ce283fa6ccc1e6a7dc53fbb72bdd21d3f669b7b49bf95c560e03d751eefedb57edfa0c99bd479bc0253c7363f8dcdeecf19da91056bcde7039f02be91a5697cb1d6ef7b3cb776868aa8e9037b2e2879a640ba8c4452371a1d12050189e129463aed8840fa44adb9b91987c389c36186cd0e1d3ac8eb6b5ee5cdf56f505f7f84b6d65682a120866e50505080bfa989d69656bef2c53b080602d8ec76dc6e37c5c5258c1b3f81c54b96e23d7501c5c5a602436747279a36303220a5a4adb50587d389aaa8442261b66dddc29a575e61fbb62df8fd7e3a3b3a8844c2d86c76748b80ddfdfdef12080490d230895a61211e4f193367cd66f1d23398367d3a369b1d90b43437f7f7f49b6a130e5b7c5871303c6abd280e848f1c65ef977ec0ccfb7f8a9a5045fcd6b77e4160c71e84a2801034fefb29025b76e29a3481e0aeb74c2dda586829d78c78f4895562e58ae5233aa06e3d28b53a8ff7454cd9a54ce26ae0a6813ea8adb04925b028cd4d7f3c006f5ad7cf67ea3cde23645ebcda03bc03786c308c9d750e55d26bcd2031f3f4721801f7790c499b8459dd795196a6730f567b9b1c491b1ca236e8fd7aa4ae77f74e5352a9050c45184e82515682743a52f7813b019e7af2715e78ee19ca2b2ae8ece8a4a3a31d4dd3d05378e7dadaba0a2a2204833d49e8fea6260e1e38c0e64d1b79faa92751559592d252344da3adb5956874e01ab47ffbcb9ff8d783ffa0a8a888969656a2d108baae1f5753f4f0e14371ffdf70ec187b76efe68d756bf9fbfd7fc3e572515aeaa1bda39df6d67ef646b5dbd0cb4bac6b3bc8bcc66127d9a566aa0eb4ae5ec7dab917517dcb7bc89f3b9df0c17aeaeffd27c1ddfb4d9266bd7c84f61e20b4e72dd393a6f479fd09cbb09d2ceeb6fb808f66784c779dc75b5bebf7d50d82876034b0308dcd8e62ea0a0e94e48299a39569cf445e17511b4463a7a449d43602fb72c67644bc8c75fdfe01eb05253f4bd379a8d6effb50e2bc72181851eb8b2b07c59d87ada40861b711397c0ca96908bb0dbba704e1b0a3b777a235b76244a28cfbec07a9f9ea2790bac19a2967a1b76540c3574a8cf2d27ecb48e9ba4e2010e0adfdfbbbffe6120a458a0d070a7621104093112520e3495189b051a4a8e8802625216910d40dc29ac6d1fafaa4b1d2f557ca8479767474d0d1619e531541be50702976ec08542190c0413d9c70a10595aa0305d0a5248239cf4024427b24427b5bdb71c73de11c9d0e8cf2d22129e638d12eb5d676f6dff50b33e4ad28280e476a3226c4dbb27d4b8c67e8e53a8fb70d28caf014de03d40dc243fb02d2cb1f789801566cc6ccf767642784b4b4cee31d53ebf71d1a8c7550e7f19e457ad59eebc94e2fb9930d0253e732c9fb35506266a1b0cee3f55a04edd42c1ee7df6bfdbe6b067a7c392413b53027e8a76344a2545c753113bf753b764f31eb4e7b27a13d6f9137693cd37e7d17ae291338f4d33fb2ffeb3f353d16d6a354ef0cf422d9333407631415204b8aa0beb14fdfd780d3ed456cd303f80dcd6c2aa73898a8ba9861733355cd63bceaa44ab153acd870207824dcc423e126da0d1d01e40b958fe58f6691ad9080d469913af57a84fd46881d5a90ed7a807d7a08bf613a65aa1407a7d90bc9a36fb960ba949c622f609716649f11222a25f94265ace2649a2d8fe9b63c26aa798c511c78143b6ea11095923b3af652af478862e0120a0bed85dc92578d430802d2a0c18872480fb35b0fb14d0bb0530f72c408139112b750a851f398a8ba30fa486ca4cb815159362444c8cc1f3bfe7e95131174c3404a108a48c7a32b57ae587e5278d3621eec7f053e92e1e1cf1f24a3f4de34377db21f4d6e7b1bbfb5cee3fdafe5e1ca24e60133ea3cde4303317a31e7f08634368b60360ace7946068e02e0666b0dadabf5fbe4098857d2f54b78e9eafafb1ccc9e789761c9b66511df073e9fe225278741276a4280aa42423e9570d8b19795f4184509280af6f25254771e6abe1b2925428061350f559d8ed4d586a1e8d01c8d946893c763dbbabb4fc638220dbe5150835f6a84a481b0bc6885c24691502d6f8e445a9e320dc9798e5296d88b884889001c425020543aa4f9b2542c544a6c79ccc4cd050e499bd4e9907af7fef38442a9b0e1144a9f9c4f512417393c2cb115d22e750cc02e04f9a814292a0eccfd486b6f21cbdbf7e5fcf104a481814415827ca12280a89438108c511c8c559c2cb11711c2a0d5d00848030d891d41b162a358a8687d74914987037d54c510103591b416fb037b6539425588d4376044a2dd05313d7782da1f67de88f3aa01bfce02512baff37817d6fa7d6bfb3bf73a8fb7069893c666bb80cd837cee7e9b05a2067025f0cc40c852cc7657a6b15927b02a677407054ee0abd6bd77d4aa247e165853ebf7ed3cd1f98d2166025882d9a3f022cc0ade51c3e0f86e057e57ebf7c91ca91f1aa2162236142244eab051b8875ca96e97490da21a86552da7e4bbcc86a342a075749abb723a52102609d1e890b8d5846ea0cd98088f3ddb67af895328548b646f8cde8b8d568042a1f63a7dd96dddcdff160ad5fcfe0018800278143b9e14bd437b9ba753283885c2f1d84717b9b3232857ecfd9fa3941863ab90798ed4f26303e46922141d18659292ea0f5ec3b8cf7c0080add77f8aa6475699ba9fd6e7835ead3a4cbd6ac056e0109995c72ab18ccbdafeccd97ae8df92e6a65b6bfdbe9d837cee3602fb2de39849dc047cbcbfdec1ae7358e7f1d6929e1ac1b65abfef40cef00e2a2aad7f73adebdab5b60e013b81c3986d740298411f15b3f1f604607616d6de89d00e9c5febf7bd960b770e1d146b41f440554ca315db0f54114463faa1d94a8a90ba811189202da2662b2c40462218110dcd1fa35e90489824289dc1a1097fea3ad11913cd3173ff32f70f88ce9a3ce06ae15eb95aea1e6748c3c0084790912832aa21351da91b667b9918cf9e5055d43cd33e49ddc0e80c26afcbd4a1d3288cfc86b7096fe506f04886875681d3ea3c5e7b3fe70c669b8fbe42039e8c31828375ee0e007559b8744ee0aa41389eabd3fcfe1f722632631883d97ae65ae013c0ff0177025f003e0c5c3c0c49daff80d95d242d47d086d6a3d6124fdd14a4dd8699922ebac996d6d6235a6dafaac031ba12d7985166ce0fe09e3e99aa1bafc45e564ac19c9ede90b6a20253762ac6308a8ec010557e4a6461011d9fbe317765330929d1a6d50c492f32d99b0ea784fc39d329bf6c399123c78836b5106d6a466b6ab67eb674bf5c284e07c2e1e8de506fef887f5190205dae54c3b7c049d3f0b60b864562321dfe5c8019023dd28f7e668b80d1691ee38383fd765febf745eb3cdea731c387ce0c9fbf2f6089a20f80ec9e9de6a67fcb794872488136e076e0afb57e5f676e8d6486a8c535ce9236151c09de05218836f5f0b9c9dfbbc36cbfa128dd9e8afc39d399f6ebbbac646d2586d49513dc77302e574db4b50f0d51b3bc6a91d3e682a292d393ca1c51433786a6a25208949897849ebf43c1fc998cfbec2da637cd303d693d3f2532122552df40a4be81bc29353dee96b68e84f527310a52f650f59f6c97ca0a81ed24f321bc59c0a85abfef481a04adcb00bc27cdb15eaef5fb8e0dd171fcd73254996e1c3ca7cee39d53ebf76deacfc6564560551a9b3c5debf705720fb71c12f018663e5a7d573e5a8ea86586a8c59748aa2ad2154fd484a2106d68eafe7f252f85f74111c909da806b5c356dafac27b6c851690f20faa11ed067c3ded482eb3f2f0e5e635d69e6730db90c534648d5201f8b6ea04d1e47e4f453faa508d1a7ebd9dc964c0285c0515966fe6ab7f51a49b75778c89f3b3deef8357f4b3c51b304e553a0e124bdeff7015bc87c28650566bb877448a51d382bcd717e311493b78863439dc7fb12707916aedbfbe9d16b4c170b49d4753e3e7e9f338f3974bddb0247803b6afdbefbbaee851c41cb2c518b6bf425ed36649e33c92876e59d49ddc00804318221f460082310b27e37ff6604c318c1207a20848c6a840e1c49f69e852388cee0d024704b8974e791f7d8b366aed2000989941224b8c68f26b4ff607247fb2184110e23ec8eeef0f2609c1bc5e5442970133dda686ab40e94470542b4dffefe21d53c5562731ebb0786e0cebd34feeb4914771e6a613eb6c202d4c202d4a27cd4c282942f0e08883434c55f472991c505a9863e7cb2ddf016d908d579bc6b302bc7940c0e7f35f0ad34f3594e01c6a5314614786828261f33e71f6689a82daff378f3bbc24d695c731b701a7def9f1621a746908389373043e0bfaaf5fb8231cf90dc99c930518b3746761bd29d1c0612369537cfbb0ea91bc86814a96966c829aa6144bb92b963ffa621751d6153bbc5b0bbf7150a233a02a6e4d050388dec36220b66615fbfd56c3532907d693a355ffe3865972d67dda9979a6d1c32e059d30341467fe87a1afef52446203828631ac110e33e772b45679ccaf69b6e27dad43c7022ab08220be7f426c1347022180ca5ce5133244dff7d0eff932f225c4e14871dc5e930f3d1ba7e3aecd84b4b704d1ccbb8cf7e107b8507bd2380de11c0561253e82cc12849d903f6646ef4f914669e893b8363ceaff3782b6afdbe743c950b30abe4fa8afb86ba4580d538f82d607c86afd904e00cebdaf59998637ad216a731ce2aa0336790dff604ed5bc00bb57e5f632c41cbad89cc4301de4a2439d29d979cde25041dbe2d746edc4660db6e82bbf613da7f88f0e1a3441bfc682d6de81d01b30acf3010aa82e2b0f7c8f824103525294f6830adbb203a7f2662a0558852527afe19547ff87af2a6d4507ef98518d1e8d05d0dab8a526a1a9ef3ce60ca4fbfc2c4bb3ed32dec3ed06311361be33efb018a6b1751fda16b11b6017a0775036dd664a473e8f2aa95f640ef2d3f84302b3f0341b4963622471b09bd7598e0ce7d746eda41c71b9b697e66354dff79b6fb1c86f71f4a6e3f2325bad5233001fb4eb61b3ea661e62b406b16a6706d0c81382ec1a8f378f348bf89e79f638f7308f1b32c9cbb42e0ec3a8f57e96bf5a7751eca301be7f699a8d5fa7dc19c417edb4102af5a2f030b6afdbe8788498dcaad87ec12b5bd717fb1db310a072e11265495bc49e3285c30a7bb8547f7678110622865a584409b320e993f3067817038a8b8f26254cbc35872d6126474009e23439aed23baee0a4d43713a2998378349dffd3cb31ff819a71f5ec3c2f5ff65ce7fcc1491923397e0aa193bf03b50d3295aecedf63056df74356a51e1c0ce8fae13f5ce025519b2eb285adb1181e0c016b9d3d19dcf16dcb90fa1241054c3c0a84899beb3e764bce9638cfc435918fe7d7d79e85b9f1702e994dc1e00b60c564b8ebe10c22ce012d297004ba7496fb3e54dc9e1ed013fa61eee0f8159b57edfd25abfefe54405841cb20b9bf5708b318e6094149ac6b7bf9e1c21a8bce652267ce1a3d83c25bc36ed5ca22dadddde351108a5ce3b1a4418151ef43195a87b0ff53b7f4a713a285ad2f3d0b7577840f6c34b6748f4ce00ae0963708ca9a263fd66302465efbc80aaf7be0bcf05cbe2beee9a349e96e75fc5fff873088703a373e0c557524aec165901b0798a718e1b9d9c589fce3edd2eb46913863414acb4b6233a830339f0b8e30eecda97148a4751304a5386e1b79f8c377d42a7fd8f6578f87969843f4f4f93943c07340fb571b188602b6605dca5193e7f738119b57edfab7d99a7752eae4993ecbe99338d273d397b0978de22e5ebbaf21e734502c397a885800e4c2d3233b1da5362f6528b44938c9e110a23a544b1db10767bafc6317ca4c134904250fd8177b3ffae9f23baaa45a5815adfd82da23d2444adac04ad660ceabefe6b190b45c1eee90989458f35c5b51ee9db440c14b79b9aaf7f8a8aabdf81d434d64c3d073dd841d535972691b46e57cee7bf4370c7be8111e6b86311e6fc6309e1984a3ad66fea7781843ebaca948d1a42284d2d2651eb6f9856083adfdccaeb732ec45e5e4ab4b1394917d428cc4f2ea00156ae58de7a32dffcb57edf863a8fb79ecc4ad02816b9b9b7b71ca898bfdf9ac67e354c5dca7006ce1b40b8cee3fd7716885ad77979f544396456d56c29e98974bf5eebf7b5e54ce34987d731fb27fe07535e2d94aafd4a8ea00d5fa216c5eca934db2451a0979798863181a839aaca99faab6fe21c338aa37f7b8403dffb75ea561d4060eb4e3ab7ec247ff634cadeb99c7d5ffb492c6b403d508fd074a46388c26612a2de9938ebdee8b756a4340c224dcde4159b2142ffff5e40b1f7bd5252ea06ceb1d52cdaf4641c899dfceddbc99f3f8b92b37bcfef2d5c349fc0b63d8356f129541b6dafbc81de11402d3043c2e1c3c710a2ffe75f9f508d51513a648504683aeaa1a324254c4a89adb40484347322c366314bb7e734c1c36744a2448e1c2372f8a8451594b87dc9e242702511b56d6f9367c07df4bfe5437f89da45c0bdbd198598b61c17a5b1df63c0cb193e776b310b4ec66678dc1beb3cdef7270a7bf74276af4fefa9c9bf736671c8bc589fc2ac605e08ccc7f416db30953b54ebde508879921de73a75fd3300ddfaa959bf7779455f075ea9f5fbd6f7b236721861446d6f0f519318151ea42db93795703a704f9f84a3ba12c7a80aa4d1bba727dae02772f818f9b3a761af28c331a60abdb5bd5b4b54dd77c8245043a5b16818444f9989cc7322dafb4ed41487c334fed294c86a5ff3267993c6d3f2c21a9afef32c8aabef89f3425508ed7d8bd6975ea778d9a26e1231e61337210d83b657d753b4e49494dbd67cfd5374beb995f6751b7b25c3e93135f3aedef3f9ef30f5175f476b6923b8f7a099901f89622b2d464f27c46a53892c9c3ba48b53681ab67d8793bc987a20c8bc557fc53dad86f6759be8dcb483ce2d3b091f3842f8c061c207ebd13b3a11369b5575aca62470dd4ba5a4309547ededf224bb3fc3440d60469dc75b99aa296d8c11b931cd7deeaef5fbb666f83836029bb240d4003e08dcd39bd18df9db0d69ecb313b3a16f0e436091309b081f8e59eb0e4ce9a86acc828f22ccbc4ca745d2f2939fe2842d4216c5947f6cc7cc2b6c040ef7d6e839769de448dac8246a116077ac87c1a8f4400acf918c44d19a5b715457e21c5b75fc06a78681b43e17aa82a3bc94404b9bd9685508d4fa0694d60ef4fcbca111109012a3d04d64f15c9cff5bdde7361de5575c44d925e7b2ebe35f25d2d044cb732fe39e31891db77e01e1b063842326a14bad0d994cfc9c0ef67fe3a7cc7bea2f717fdfff8d9f51ffc707a9ba662515575d8c5a904fded49aeecfed9e12663ef033f67ee1fb343cf464ea9e606978f68c7018d5e5e4d803ffa160fe0cd4c2028c600869e84cb8f363e44d9bc4f69b3fdff77dba9c4416ce1e327d4f939119a8bbdf8aafd294a0d86d142e980340c9d94b28397b89e9806b6d27dae827dad84c70e75edad7bc49fbda0db4afdf0cba8192e74224ae6b09466911d29d4486d79fec37bf958fb2c7fa372983434f0066585eb0def08134f7f94026bd053142e78f001790d97e74001f07ee39deb1d679bca3ba5fc0fb86c7ac63ca19f3a1813d76edd4fa7d11cb49b27728d6662fc43d87110865e58ae51ab033ce76d96ce8634725e546e99d01c207cdfeb8c5cb16a1e4e7f59a3fe5a82cc739aeba873445b4040f9dc0fee6764bea69883c32e128c195e721227df7a8b9c68ec273d1592c78e33f38aa2b9186247ce0085a530baacbc9980f5fcf988f5cdf4d424f3c094160fb1e5a9e5e1df7e7fcd9d390e108877f733f1b2e781f6f2c7917eb165c624a7359708eae62fa6fbf4de555170f88b0ba674c62ea4fbf8abdaa02a4a4edb50d16e90c33ebfe9f31eef60f51307f66dff7a96984cf3e0de9740c8d6c9475ded4230da62a418c274c1a3aee6993cd66c6896f1dc585e44d9e40d1622f55d7bf8b293ffd0aded50f52eb7f9379cffc8dd27397a6503800a3a234558b913527fbcd6f3dbc3b31938a338922cc9e6a29e754e7f1d60053d3dce71f32699062c6f98be5e1c8346aea3cde85c721e0600a79a7d3d5fa9e9c51cfe8da1991fbcf210b44cdfa7900d3a56a3132ddace64b30865a5b071d1bcce8825a90cf988fbecf242c86a5f3282552339bdc965f7111f9b34d717623182272b83edee0da55ecaf6d30b545870a86815e5d41d43ba3cf7954a183a614a15a98cffc27ff84b3ba1235df8d340ce63f7b1f93beff7f949c791a187d27285a6b07c71e7a228e24d84a8bcc5c29294d7d4a20b8e700fbeefc613c8fb0db997eeff728bbe4dcbe93c304384755507dcb7b386ddbd314cc9b69e678151731f97bff876785a9ce133ed06709460482d0c5672186b0a79c54551c6bde4c2a2210aa4a60cf7e5e74cd60ddc295ecbaed6b1cbbef113a376e277ce0305a6b7b1cd9138a82e2765172e66971d59f3defb836b3202299706e7ff489556f07a31106eab230f479802b96acc5fc7e01c9619fe36155addf17c8505b8e44cf4527f04816ce5f1ebda823c418eae56910b5ce5abfef854c9fc31c72c8a1ef44ed203102d4c23088ce988c4841469a1e7d06bdbd03a1aa8cbbfd56c67eea669c13c6206c36ec9e124acf3b9d2977df49cd573fd9b3cd132fa0b575c6e708a92af62dbb109d81216def20a21aa195e722fa5850d0b9713b46c8e4accef1a319fdb1f7222ccf917be6148b501d4c6bce4211343ff51247fffa3052d388d437507fef8318c150c2f7149a1e7f8ef08164e5a2197ffa2155d75dd63db7743c5391c61e6d71d7c4b1a8ee3c0a4f9d4df52d3d5ad7feffbdd8e7f319593c0fbda2342db29a366c2a8e577c2989bc5014d4c27c82bbf671e4de7fb0edfdb7b3bef64a36aebc85ed377e8edd9fbd8bc3f7dc47eb0b6b881eeb91b2eddcbc33e9ba49bb3d95f77867dc8bcbc98ff5985a7e99c4f9802385374dc1d407090ee30000200049444154f674a4b1afbbb3e1498819efdb597a769f695575a62291d5c0ac34f6777fce1ce690c3f044d7dbd67ea00933a9d1ec383f6362929741280aed6f6ca2fe8f0f31e6e337602b2e64c2173fc6988fbe17231c45d854d47c376a4cc35cada999fd5ff9316a7e0a592a43e25cb381d0d9a721a243143d9092e8e4716893c6a11e3c9adc953e01c11d7b697eea25ca569e6f0a7f5755106d327b8d451b9bb19797d2f8e8aae45e5c27204b5a732b7bfeef7b1cf8c16f312211b4a6e6945f0dbd7598c67f3fc598db6e4cfaace6eb9f21b4ef10adabd7a5357ee4681381adbb70cf9c42e46823858be6618b693b123e70a4cf440dc320b8f2dc21f5a6a12ad8f61e44ad6f3cae1eac50ad42012b5f30bcff10a17d07e1b9574c4929b70bd59d87adb498bc69130977557dc611351bfab8eac4b5be0908af5cb1fc6df110a8f5fb7c751eefe1eefb3f33c80796d4fa7d8972486331f53dfb8a56321fba4d3c7febeb3cde1dc0b40c0f7d2a3005b3ba2f115381c969eceb9fd920bb39e49043dfdeca58b9627913b1e2ec52225d0eb4a9139292c515a7833d9fff0e4dff79d63496761bf68a329c6347e118551147d222471af09d773d46389cd203256d2ace17d7f6bf47565fb95a613ed125f3fb944f6584c2ecb9e3bb84f6f4286b39aaca4da276ac91c67ffd8fc0d65de97b0185c00886081f3c42f458539c4241fcd704077ef47b427bdf4afacc5e56c2842f7c047b9a1aa95a533347eeb9cfba26477154f74827ea1d9decffc64f09eee8433eab61109d3315bdba62680a40bad7850de78b6b91e92a1e58a14ea12a485d476fef2472b491c0b6dd343efc147aa21a8694c88a528cb2e2c4b5b101b3bfe0498f9850d7335918fe7db173b07e4e04d24898e411cc82a86ce39e2c8c99075c58e7f18a8410b2c0d448ed6bf8f820b02b17f6cc2187614ad462f2705e25c6fc0acd20ea9d993264a8e4b9d87acd6decfbcadd746edc8e1e239aadb775d0b97907477efb777ce7bcc7f47008611ac5c406ba42a0ee396036bf1d4aa17321889c3a0b59d207c92445217ce0081b2ebe09ff13cf232351ec65a508bb8d833fbe97dd9fffb629923e84738d1e6d64d33b6f4d499e8ace5848d9cae5dd796d7ddde7913f3cc8de2ffc80f0c17af2268e03a073eb2e767ee84bd4fff95fc9d590bd9c9bc8e2f9c882a1d5f1169a86fdb50dc995ba52a695a7177b8e5269ce621844e627e52feac0d6952b961b6f8707408c07e5ef5918fef2843908cc04f874f0a875cdb24d76ff4b76c2e5ef23a6e2d49a8b9df464a3d602f5396f5a0e390c53a21613de7921ee135d37db2ff492842f1c760efef85eb65cfd51365ff911b65cfd51b65cf511f3f7777f9cdd9fb98bc8d146d3bb611828ee3caa3ff09ea402051189625fb7a5cfed33fae7a291685327a04d19dfa7ef1ad128a1bd07d976e3e7386c79a2f2268de7e87d8fa235b598de9a21caad939a4ed1122fe33ef7c15ef75ff3e5db907d0c3dca4814231842a80a077ff6479090376d129d1bb6b1edba4f72ecc1c74def5e1f889f515a4c64d19c215e910ab62dbb515ada933f72e7e19e31193d1034f3fb8e9323276c2ace5115e8ed9ddd2d559209a14e74c16c44fc1a6fc0ecdcfdb682d51433d3796a79751eef595d04c36ae07a6d1adbef07360d13827198ec8460a7020b137a64e5d377317b035893aa4b7d0e39e4304c885accefcf101bd092127d5485d979be9790a1b0d9881c6ba2fdf537697ee6659a9f7d85f6751b891c3986b0db4c2f8694d88a0b99f5c0cf987cf797cce6adb1fb8b6ad837ed80f010472f2404df79fe090949c1a973587ae855267ce96318a1109d1b4db9c7a285f3ccf065248a67c5d94cfbd537e33c890386d577ae60de74663df42baaae7f575c5fb5581cfae59f11b6137bc064244ad9a5e733e1cbb7216caaa9aa2004ae9a310476ec25f4d6613ce7d7b268d3ff9870e7c74f78fe22a7cc401f5335742d39c0cc0b7c630b2245d184ab662ca7bcf44f4edbf11ce33e772b6a4991495853ccc7316614b31ffe0d8b363f45f5cdef4ef6404a89cc77139d5e93d80ff018b0e3edf410880979fd350bc35fd74530ea3cde5348af79ecc6e140aaad9e58edc0b30c695240aff86cc2754c47d6aa0d78911c72c8617813b5479f58c5ca15cb2589122c761bdaccc97df2b6f486c2857359f0fa63149f61b6fc1975c395091e0e816de77ed4041dca4187ae139d3f1d6dd694e336ea35426114979309777e9c05eb1ec3565208525274faa960184cf8c24799f5c0cfa9bc66e5c0e59da444a82aeee993a8fec0bb99f49d3b98faf36fc4e98b26a2e9f1e738f4f33ff7499fd38846293dff74c6dff161663ff42b6cc585e4cf9d6ef2e3634d4cf9c11798f39fdfe39e3e89c891136864db1442575c3874451f5daba1b915dbd6ddc9e44b4aca2e3a0b61b7e11a574dcdd73ec992bd2f32fbc15f517ac1321cd5950887bdbb5d4cdec471e44d9e40ded41a2aaf5d899120ec2e34ddf40e269bd59d2b572c6f7f3bb4e688251a16fe9085e12f8cf93d5d6dcfe76afd3e7d18e556ad22a67a3e83b8b2cee3cd8bb98ee9340b6eaaf5fb5e26871c7218de442d26fc795f9c6db4d988ce9a8ce84b127e308c8c44919a86de11406a1a95efb984b94ffc117b454c05796282b800b5be11dbb6bd437eb02218a6f303571cf79d3752df4060b3f9929e37a5860977de8611895272ce526ca5c5945f6ed915439a5eadfe7a97a4044561e25d9fc5fbc2034cfafe1718fda16bc99f3fe3b89b75f8b698ba967d3d66bb593559bcec349ce346537adee900945f7e2155375e694e2512a5fd8d4dbdef23142674e132f4eaf20191f6bec076f028b6dd0752867dd5d2a2a4fcc0d20b9631ebef3f63de537f61ca8fbf4cd57bdf856bd2788a969e8ab08a548efef95f08478207321a2572c6a989614f80c7df8e0f022bf4b815339c984914d779bc5da2b7e9843da3c0c3094433db84773d09cdc333880f59d7b1105896c676ffebbafe39e490c330266a31884f285604fa84d1188505c7775605838cfbcc073875cdc32c5cff38d3effd1e850be732e6b69bba3d3f4667806d377e8623bfb93f492f53aa2a794fbc182f963d14300cf4ea4ac2e72f414452e778451bfd343efcbfee50995a988fe27460af2cc351594ee72633141adcb50f3d18ea779e9ade1160cadd77527dcb7bfa96c86fcdade9d1a7fbdcbf4ca82a6d6b3702103e544ff8503d9e15e700e01855d1fdbde667569b95acbd104abdac84e0e5cb11c121ce951602e70bafa5f6da09c181effc9a37ceb8925d9ff83a1deb36c67dec1c5d49e5bb2f61ca8fbec4dcfffc9ed1b75ed3fd59c3838fa3381c71c7649414119d323e15f17c24e1e5e56d45d6b0da34641085c0e23a8ff71cebf7be6253addfb767989d3b803f66690ad724fcec2bfe389cc86e0e39e4701ca266853f9b89d53894127d749599a7d61bf7098698f3d0afa9f9c6a771cf9a826bd238aaaebb8c794ffdb5bbba30b4ff10be73aea1f1e155a9abf6540575e77eec1b770c6d518185f005b5184505bd929b433fff132dcfbd9afcea7fc602dad7980fb4833ff9439ff53e93b88f6e5078da7ccadf75611a1b490efef48f74fe3f7be71d1e45b9b6f1dfcc6c4f4fe849e8457a136bb020516389edf3e81154ecc78abd61efbd8bd83b1614115154048e07041584d07b0b2590904ddf3a33eff7c76c369b64430969c0dcd7952b6d76e6dd7766e7bde77e9ee77e96aedeab175cf8e4da6de47f3e05a16a542c313a4ac40eaeee8119c82b60d3032f1aa433da7c04557ce76722f6a3197d7d499a545a817df65f883a88abe6f1e2cfddc1ae4f26933362140b0764b1e3adcfd14acbc2adb724ab155bfb3628a1870bf72fff336c392209b5aea3f6eb11f65f8bc092ecacccc2c329ec19a10655fef86333dc8306b1ffbd3ddf6da1f3f74e330da1f3dce441bdf793a89564b8731698cba009130709518b5010beaeb638b64a42eb18dd0753045552ce3a85e4ac93aa549f02376a510992222385cc4a97665e4ac5caf548928c1e0822db6db55535870dd7c41ff6df3bab1e50d3db11386a40dd9cc16261f9b9d7b2fdcd4fc3bd4d0152ce3e85923ffe61db8bef91ffc55483a8ed25f4a9fbfce8deea5f5a790589271d83ecdc37f2e3dfba838df73ec796c75f09f9b1f9d17dfe3d5b55080148085563e54537e1fee57fe1e6e5006a71294533e7b1346b0c9e351ba3db570881d6b983315752e39e1361b7e1fa621afb74205942b228f8b6ee60fd6d8f313fed38565f763bf95f4d3394c108c531efbd2f8dce123510ecdf2b9a99eebb353e0b872359db42d327e88f00b2f7f3359fb4b4f98ba85c6d8eb1b5026ec46876bfaff8b072dc264c9868b988265dccc428d9960124a113386a00b6798b6b85dc84ae133bb03742d78df6473fcc64dbab1f22592d741a771309194722822a9e759b90ad162c6d5bd1e65f679272d629ecfa6c0af95f4f8b587c65940ddbb0fdb594c051fd1b3769dd62c177d689d8ffcc816821504942b659d978efb3ecfae81b12860fa3db8be370f5e98e56e165f3a3af223b1de88120968438a3ad5314c22684a0fb2b0fd626a59a4efc3183f7a92040683ac1dd45b87a76a6f727af44304041fe573f52f2c7c2702e56352e63b3a2fb03488a4cd1cc79884080bedf4e0060f7b73fb3fdcd4f285fb606110cd6ad0c2a32fe138f321455ad1173d36419cbd69dd8e7fe53a79a169550cb324a8c0b84a070da4c764ffd0d57f74e0ccd31d2cc02bb7653fae7e25af32c12e351bba685fdfd2230d1bc25b00dc3f0b77b131eb3d37e6e3f25c39de30f11a3964674015e2064e6db8490803180637f899a19f63461e220226aa190cf568cf0e75000349dc0b0fe60b3822f1055314208d4d272f23e9844d9c2a548b24cee936fd267d21b46f3f6eb471337b43fad2e381d2536c62016560b853fce448bacc653649c5367111cdcbb71dfb5aea37649c377ea7138a6cc8ada1941a82ae83a15abd6539eb392f4bbafc3d6a61571c306e0dbb20d80f6632ea0c3cd635872d2c551bdba84cf8f5a544afaddd7d57ba89222133bb82fb183fb56fbbb5a54c2c67b9f8dda4a4a92653a3d7c2bf6b4b6acbef21e24217074ef8cab4f77d075f2defd82d2f98b8d10aa24d54918f5e404bc679dd4b83d3d31da383927fd5c7f32284948562bc2e727e184a3c27fdefddd2f087f6d22aeb54941ebdca126499b9d9d9559144a01386c6f0819ee9ce0dce441f3420a97a5850e73420b2718eb42647740131f777f1ad96f023662c28489838ba88516a89d53a7cf9817266a4220ec367c270ec339edf76a8a87a4c8942f5d0dba8e6cb5604d8a3792b315057f5e3e9ed51b893bb23f3dc63f5eebc07143fb634fef50ab1d93929b8775d14a8243fb36aa5f971408e2197d0ef6b98b91dcc5d50a1974af8f8e77ff87f43bafa174c112b6bdf41ef913bf27edf6ab493cf1680abf9f41ecd0fe747ffd5163ccf171e8f9b5bb2bc84e075b9e7c93a4cc0c64ab05d140ef47b659c97de13dd4d2f25a6a5d25e18d3f7a20b143fad1edf9fb5877c383c41f37047b6a5b02f985f8b6efa4c79b8f9174fa0968c5a52c1c7a368aab462f564da3e29a0bc16201b511d54d49c2ba6e0b96157b88b6096154ea4aecb17843b228e146f32218a4e8d73906e18e7c8d24111cd0133dc689549dc47d1ef11938dc310bf000f12d706cbb81c52d4d4dab8100f0553310b5fdc1cf344f270513264c1c08518bc00ce02ac065909a00be334ec4f9c3ec1a6a8f42f19cbf09ba4bb0b56d4572d68904f20b49c81846ca592713d3a7768fe28ae56b29feef9f144e9b8577dde65a0baf54e1c131eb4fd4fe3df72b0c563f2947507ec3bf897be2adea63b059299c36934e8f8c25f1e463493cf9588a661a5643c95927b2f9d15749cecc086f6f4d4e20b0ab00290a89902c0aff0c3ba7c1872e3bec468e9b884e7e9458e3e1baf579a7b17eec6324650e47b25808e6173278ee3758128c02bbeddf4cafb50fc917c07feaf10486f647f237f2bd5cd3b0cd59885c585ccbba45a81a9245c1d9ab1b8ad34eb0c08d77d35624590adb8e449e4b7b87b67837e4e2e89c867fdb2eca97ac8a4aec02270e430a56cbef7303730f7735ad12a126e33b5b28519b0194b6e4705d863b479f9b3ce8bf4019fb57c9da94989de1ce0962c284898396a8fd145abc8ca68eba406f9b4270606f2ccbd656850a2509dde365e7fb5fd1f1fe1b49397b244923876349aa7d7f2ffa750e79ef4ca462e506023bf38dbcb668213745c1ba601996d59b080ee8d9b82ef8ba4eb07757fc238ec6f1db7c44c8ed5f52142a566f60e747dfd02ee4359674ca7120c0da2a99d6e79dc68e77bf20edf6ab8c3ea279f9d193f12b2739a1fef76a893dd8bed5f50f5d10c8cbc7d9b30b9b1f7b0d7b5a3b5a658f0420664055aeb156564eeeb36fa1b81cd55eaba5b7c5f3afd31b9fa4014a61318e5fffa855c92a5415d711dde8feda23383a76405214749f1fcfbacd6c7fed230a7f9a8d252ea68a88491281fc42d6dffa18f6f66d909d0e547749f5fd6a3ac1013d51d3dbd7ec7c300fc8356f07e1847880ef807b5ad8f004f0df0c778eef2098ca65c072e0d81638b65c609579b59b307170a016bb08a90a1a86745f6d91f39d71422d8350c5e960dbcb1f80a6213becd5489aee0fb0eba36f58d8f754565c780345b3ff24b0ab608f7951c6a86462267c8990a5c69f0145c177fa09686d5b551f82ddc6a6075ea4ecef25d55913d069dc4d040bdce47ffe3da57f2e46abf417db074f351dd011680874047a1d7c4b0734046542c3277474c4de7bd3848e2f348dc2e9ff452d29277fe2f7a4de74592daf36110cb2fcec6bd03dfeeae35664bce79c829e9cd0f8736fb5e0fa780af8aa8f41681aae9e5d19327f32f1470fc2d6be0dd63629d83b1a86bdfdbe7f87013f7f8cad7d9b5a445e2babc0b37693a1a6d5b87ea46010df7999350b5554e0d7ecac4cafa9a655cbfbfab8050e2f0ff8eb2021bb6518fd93f51638c48dc06a73f93361e220256a118bd5cb35ffa7764d43ad99842d49e8c1205b9e36c2876a491965ff2c63f3432ff157f7935873fd03f876ecaad69b52683a42558daf6809e4b28c925f48cce7d3a2592834f033ba40edde117fe671b592e6758f9795978ca5e0eb69f877ec42842a4495c438526fb8946daf7dc8a6fb5f400402383a76a0eb537761ab41f82a27d9adab4cf517724be97a4e712fe544f7124e702f614cc96a3ef6ee62b3e6c3824485d0f82550c4d8b2f59ce85ec2e945cb1859b494338b97f36cc55696a915d1399ad542fa1d57937cfa89886090c269b3587dd96d589212e870fde8d01bd2518b4a289dbf9865675f4dd99295d5c98c1004fb76c73f7c68e32a9980b059b1fdb1d8a8268eec5b2a04b2c3419f2f5f035946e83abecddbf0aedf8216d19520e994e33872f14fa4df71358e4ea9c6b9d953d183a6a3754e2530a067cd166245841cee4d54231aab301a8db7246cce70e72c3988c8eec4d083404b820efc91e1ce514d5b0e13260e0e440d7d8654b5ed53a7cf98069c15fe8427c4131cd207e5c7dfab93044561e7c7df20c912a57f2fa1ecef25a8c5a5c8767bb524753d10440482c40eea83b36b3a9222e3dbb29db27f962359add56c2684cd8afd97b90486f645eddd1554add12641f207f09e730a96551bb02d5c8ea8cc7f922482bb8b5873edfdc4f43f02477a7b6206f6266dec15a4de7a25bb267e8f67d5062445a6e73b4f11376c20a5f3fe61f7d4dfc20a9115897fd4725ef06c65a36a446cac361b9d53d3f0fabc6ccccb63a3378f29feddfc9fa335ff04cb58102c03c0191343db366da9a828a7203f9f69fe427e0b1471aebd15636352d122c894ad4d0a1dfe330a252e869c11a3a8c85941c58ab5f49ff60100853ffc46c1373f13dceda67cc92ab492b2b0cf9d31e180cd8ae7ca0bc06edf633fd4037f3c9051f2ddb83e9982703a6a287d2aedafba087be73484a6b1eb93c96c7fed23842e70f5ee46db7f6793728ef13021d92c741c7713ad2f3a9b82afa7b163c244d4e2d2a89d1ea4a08af7dc53906a1746fc373b2b73ab792b888af781075bd0787e8820922d7ef232dc39cbe6260f5a8961e8db92885a8b6abd65c284897a10b5083c1449d4506402c3fa63ff7d01528d26d7aabb84ad2fbd6f547d4a12728d0558f7f94939f3643a3f7a1bf6f4f6a1d0a78450553cab37b0f68607f0adcfada6f0488120ce6f7fa5fcd6cb1adf195f08ca6fb994c45b9e442af384c721293222a852be7019650b9752f0ed7412471c47fcb00174bcfb3facbbf961da5d792171c306029070d231144c9981a448589058ae79b8b9743d1a029bddce8d37dfcad1c71c0b9284108292e2623e7cff6dfe9a3f9f773d796808929292f8cf4db7d07fc0206459060485bb0b79efedf12c5ef40f5ffaf229d0833c1fd7158f300895ad5d1b2c4946b8b2fb8be3f8e7a86c5a9d7b1a49a71c875a5cca96c7df088703254541aea1544a5e1f65b75f8796daa6514971a5b2e7fcf657e4a2d2da1c2ec6457cc6302445098dfb758245254892847feb0e8a67ff49dcdb13e931fe311c9d8dce17ceee9de878eff5b4bdec7cd65e7b3fa57f2eae1ece1502ad637b82bdbb458b333f14f17062de11aa2fe09fb730a276d0f87e4590c95768beb652d1e0ce70e72c34af7213260e1e44cd80cfcecaacf4545b0dfc11b9c006fbf7444b6f173d342644ed3c2d21b0c4c7d173c293f4f9fa4d5cbdbba3c4c6203b1dc84e3b4a5c0c71c30630f4efef893b7a50b8155058295abc0afb5f4b1b7f26844038ec94df34ba1a59148120a9378c66f01f9318f2c737747be941767e60346f48396724b1837ae3ea5de50d6a6b9d0c0824a04268dc51ba110d41db76ed19fff67b8c3cf5345c3131b85c2e626262484d4be391c79fe69cf32e404390969ece0bafbc41c6f013898d8dc5e572e172c5d0b153271e7df219ce3cdb30709f1528e22b5f3e160cd3564b6255c182a353076c29c9a48fbd02d961a7e47f7f117fcc2006cefc8c23174ca5c7ab0f63498c28f850357c679e687420686c92065857aec73e27fa5aa1c4ba42731832fb2d701bd5b4a12fa1aa94ce5fc4820167b0f9e1970916b8c32a9dad5d1bd4e2d2e8959e437aa3b7aad50a6d6e7656e66a93a445271a1839612d25e97c41863b67d7c112aeab249319ee9c8f6959796a9f459c5f13264c1cac44ad92ac01de9a4f8392a6e1bd300b695f42634220bb9cf47cf729da5e7a5eb57f79d66ca468e63cca162c45040dafab7e53de26a64f77e3f7ca5dc832ce4fbe47c92ba865dfd01808f6eb8eefd4e3ab8e0f040b8b891dd28fd821fd48bdf1527abcf1282210c0d6ae351deff90fb94f8dc7976ba4f3f8f30a00434dfbcc974f890862b7dbb9fea65be8909a865663de8410a8aaca0d378f65e8b0618cbded4e52d3a26f274912575d7b3d3d7b1d8100a6f80a2914419024d4a292f0b66bae1d47cab999249c78b4a1f29d7034dd5f7b84848c61b8faf5c4da2ac92034956a53d7343c179d1ead4179c3429290bc7e623e9a027534781741153d6098072b712eda5d7d91713d443e18c832b2d542eeb36fb364e46876bc3d9160819b826f7ec2bbb1761453b89c048e1f52cd2b2f84fb23ae7513b5898607f8ad850ce9a583454dab417601de6b41c37aff609b4713264ca2b6078416b01944f6fe533502037a12ecd773afea8b56eea1fb6b0f933432231c72f2e5ee60d5a85b59f17f37b0e68abb58356a2c1b6e7f02addc83ecb0d3e5e97baae718c912528587d8973e0255dfa7caca0382a2e0bde88cb06a28dbace47f358d9def57b540951405c966b45d6a754116adcf3f8db5d7dc07807bfa7f91648922a1f24fb00c01f4ec7504c71c7b1c6a1dc6b14208028120b7df752f47f4ed472010a8733ba7cbc525975e0ec07acdcb06cdc87bf3efc827585844de3b5f509eb3826ecf8f0bcf55a47a16d855c0863b9e445426de5b2d782e3f1791d0f89659c2612776fc4494cddba3768300504b4a8dc6f3806cb3d1e5f13be8f4f058a300a546bb2f25c6892f773b9bee7996e5d9d790fbf45b889a7327046aef2e047b75ad4944ff00161e8e0dd8f783aca9a179d29a79282af0fd414a76c3e4a805605b863b67a579659b30710811b51059db82e1621d9634a4a04ac595e7474bccae5a1f358dc4138f22e5f493422f02f72fff63d191d9b87ffe9dc08e5d68151e8285c5e47df035c5b30c43d998be3d881dd2af7a059fa2a06ccf27f6cdcfeb5ce01b8e4d0884cb4ed9fdd781a2801048560b1bee7a8a1d133e8ffa921e6f3d815652c6aad1b7519eb31224897c3dc80edd200dc34f38691f0eab1317178fd88baa150c04383e6338b2a2a02158af7a11187d2d37dcfe04b94f8da7efb713a27ad97937e4929371214177b1d17a4ad5f0fefb4c02037a35ba9a266c565c5ffc88f59f157b3432966499edaf7d4460678171ea635da4df790d476ffc9de43347189d066a6c8f22e359bb11ffb6bcda06caaa8667f439d114e077b3b332bde62d60af5886d1ffb339f12de03b88c3759b30daf23537be0233ec69c2c42145d422d486e730c2a06132a3b56f8dffc46175aa6a42d5481c713c92c3509eca7356b2ee3fe31075844c4b172c010196a4049c5dd36bbb864960fb7b19f65fff687c554d17e8490994dd776d98ac21496cbcfb69565e782345b3e6e159b501cfaaf5f8361a3ea9bd3e7c96e259f308161603467e5a7928d1bf6397cee80d4c843a75ea0c409e1e30e64a080a26fd44ead831c40ded47d05d4cc5f23578d66ca46ce152b63cf22a8b8f3ed7189f2421a91afe1147e3392f1329d0c806e592842d67358e69b3f77eee248940fe6e969d7105def59bc37fb6a624d1e7cbd7e83b693cb183fb2259945af98cb5761508e21f71346aa70e3589e80a0ca5d80c7bee5d155a096c6e01444d1cc4e1baddb48c10f2e4d039352f6c13260e15a256595410b22ff8b0fa2b65fc99c723629c752a53f60e6d906419a1aae47f31354c626a6daaebb87a7605c9a8b294ebf24e0baab8befc09cbc6add1f28d1a169a46b06f773cff3e337c2cc96ac5fdf3ef2c3bf34a969e76194b4fbb9c259997e2ddb00557ef1e747de69ef0d80586b12d10dd2bee00894fa5f2160c515aa1ebb4b9f86c526fbc0c8075d78d63c988512ccb1ac392534693fbdc04e335926484af8fec4bc5951720791bd9e45d92908b4a717d38b956a5709d2fb15af16ed8c2d2ac316c7de11db4b22aefb8a45387d3ff87f7e9fecac3c40ee95b2b1c1a79fde97131782eca42f2d70a257f939d95b9c3fcf8ef1911cacb2c4034d330f280a50733b9c870e788d01c9635e33036029b4d35cd8489438ca8d5c01d44aa6a40b07717d47e3dea7c815aee31164c7f00df96edd1d5145d276e705f92461a09fcbacf4f302231befa6825a4d272e29e7d0fa9dc53cbcde17029000020004944415479bec12104be334e2070dce07032bb64b3a2b89c68e51568e515047717b1f2c21b00687bd9f9b4bbf242442088439271618469376dda802c375cc856d334366fde04406bd98a24c0d5ab2bdd5e7a00c96665f3832fe1fef97784aa1abe6216c5b04b09cdbf9eda868a2bce87c6eea50aa0c8c4bdf001cad69db5c9b510759ad44a160b6a6131b9cf4e60f1b1e7b3eb8baa1425d969a7cdbfcfa6ff0fef937ed7b551db77494115df9927a027c6d7ac502e019e846a8ab189e804234c6c9b71188b802d87c074ce027636e3f1ff040a4d35cd84c9750ec1c147a86a7e425572558bb082f79c1151bb07488a42f9e21588a08ae27410d3ff88da8b34604b6d478ff18f1bed803092e2cb97ac8adae0dc18b18c545a4ec2b857907c81c60f834a507ef328b46e1dab87cf4276119245c1bb691bab2e190b40d767eea1d53923490c0a5ac9c6bcfcf1bf39c80d54b16ab15af97bfebcf0efdd653bb6b858fafff80196c478767fff2b5b5f7edf30b3adb4b588987361b7517ef368f476ad1a7dde5014e29e7a0765dd96da0dd7351d7ba7345c7dbad7dd0541924017f8f3f25977ed38724eba988a65abc3214fc5e5c4b37a63d4bc3e2db52d81e38644bb3eeecaceca0c9a961cfb45d856d23c5d0a34606e863be760ce4fabf4540b003f36d31074e0cf0c778edfbc9a9b0d12e12684269a018e66206b0d76bef769e011be6a1381b555b751c3572d70fc905a794e9245a170da2cfc3bf24196697fc5ffd1eadc4c2c490928312e5cbdbad2ee8a0b19f4df2f881dd4271c58d9f5f914c3f8764f044c929077ed26f6e58f913c8d1cba13802e287df07ad41e9d2158bb80429265dc33e6b0ed15a30b40e7a7eea6954fa39bc5080b6fdeb289d52b5784cc6b0ff0844912df7ff72d00ed641b1d82126d469d83352589f27f96b1e1d6c791edb628b76a1de1b05331f6328247746d7cbf344dc7f5c1b75817afac550022741d6b4a227d26bec2c0df3ea3d579a7edb5659564b352be680539275ccc86bb9fa67cd172767fff2bc5b3e747dd3e903104ad63fb9a7f5e9e9d95f9ae49d2f68f6484d01c958b1ee0a710593c98896ee58f6f36d3108a30143513cd07fbc1aeea984479bf6065ef0d051a96a84510b67ce01d22caf525af1fcf65e7a0b74eaea538691515acbbd13036b775684b8f371fa7efe4b7e8fbdd04fa4c7a936e2fdc8fb55572781af3def982edaf7e88ecacdd85a0966a22495897ac26e6bd498d9faf56a944dd321aad7bc7e815929aced6672750f4db5c025bf350149911b644a37f677939b366fe76c005058aa2b074490eab561915f6bd2d2e3a5b5df8b6ec40f7785977d3c355fe6835c68fdd46c50d971018d2275ace56c3c2a2e09c3213c78c3fa29e1bc561a7eb0bf7e1eadd1dd9e9a0c7f8c7e9fce86de85edf1e099b64b584af939517dfcca6075f42ab996327047a72229ef3464623a3b79bf7ab7a938ce6f002db9ce1ce597a08cde57ae09f66387441863b67817935379ee2b10fb09944ad59d1d4f3af34e4f1f67947110ac4782273464224a6e2da0b916aa84db2cd46e9bc7f587dc55dc6c8635dc40ee84ddcd0fed8d3aaab1d5b1e7d954d0fbc102661d5d6dea08abd5debda49f992846dee22625ff918616ffc16537aeb64ca6fbc049118c5734c92d0fd01565e32968ae5abd1149991b644d214635c3367fcc28eeddb0f68083e9f8f1f7ff81e4f45055624ceb6a7a020215b1556fceb262a56ae8f4e5a2d0a9e51671318d6af717b7862d8703827cfc0f5e54f75a69f6b5e1f3bdf9f84e6f118d789dd46eacd973370c667466bb1baba5e84e659b6db08161613c8cb376c46224f833f40f9cda3c066abb99fef8179a69a566f92b195a6ef52f0091c1a761211efe1b96638fcafe6155ceb7c384ca27658c119224f4d85e653d4428b9c17b8b7e6221aecd3dd707faf991c2ecbec9efc0b4bcfb882b2bf9710d859805a5c8aea2ec1bf6d27c5b3e7b374e468729f7fa73a111386e584b35b277abdf70cc356fd46ca9927d5b6f7b028d8e72e22eef9f70da2d898396b42a0764ea5f4be6b117131108538a2e96cb8eb19649793a010dc1d63f4a32c2b2de5938fdeafb7aa26cb32ff2cf88bdf67cf026090358e936d89e8360bbba7cca064ce826a4dedc373284978cf3d056ff6c97b0d2f1ed8b3a931efaeaf7fc6f5f1943d7ba5290ac5fffd93c5479d47c5aa2a2fe5f8e3873268ee245cbdba5629a842802cd1e186d1589212f658412b05557ca70f27d8b77b4d425a06bc9e9d95596192b403c2d74d7cbcf74224f15020ba95646d0a50d1c487ffccbc746b219ea6cf19b398d3de6c703531517384c879d313b588c28249c077d53881c38e377b4454bb0ec9a2503a67014b3247b3e2bceb587dd9edac1a7d2bcbceb882a5675c41e9df4baae55569155e6ced5ad3e9819be8ffe307b4fed79900f49cf01429678ea8adb8592dd8e6e71033e14b249fbf51c99a145451bba55376ef35e8ed5b47cdf5aaecaca02238ca1ac7f90e23717fdedc394cfcf4631c0ec7fe3d8ad96cac5bbb96575e3214c77849e1a1988e78433e6d28729d24cd33ea2c3c179f89e46de43c6221707df913ce493f231c7b573765bb0ddfd63c969d7e39bb3e9b52f569ead5953edfbc45ebf34e432baf40a82a69378fa1cb1377d277d278e286f68b4e38751d2db50ddeff3bad96b20b7c979d9539d3bc571d307e69c263fd9ee1ce293e04ed2454e0d3263c9edb0c7b46455233285c49e6b41f36c4dc899197d8f444ad06aec748520d2f94ea115d8c3e9951540fc96645b258f0acde48f19c0594cc5f847f5b1e8acb59d5324a08445025f5fad10cf8f963d26ebf1a6ba84137801217132a3c88b250cb12b6f939c43dfd8e419e1a316f4d0aaaa85dd228bbed72a37a720f4a954fe85ce16c472f8b0b806f277dc5575f7c8e7d1f43b5168b852d9b37f3d843e3f054180fe28fc6762645b6eeb9d3b3a25071cd85f8b24e687c435bbb8dd8f15fe0f861769d242e9a0d876451d0ca2ad870dbe36cbceff9aadda5b6a5c75b8fd3eda50749187e1469b75e6990b83eddb175688b8836df362bbeec11e8c90935ff530a5c07a61dc7812044987281a66a41f47aa51275a820c39d43863b47a769db614d8c387f26aad0ae19885a2773da9b0dc94d7cbc180c15af79885a4405683e3543a04115efa5d968b59de0ab112a49960defab48e54b92b0774a65c0af9fd0ede507b077ec80d0740279f96821a354effa2de4bdf3c51eed1c2c6b369178eb53c82107fec65490b42ea994dd7b0d2221768f642d45b27093b303f192054dd3f8ece30fc321ccbd61d7ce9d3cf1e8831415b901b8c2d98e23ad716133dd3ad81d9e5167e11f795ce35e8a9271cee3c7bd82edf7ba1fda1de91d70744e8d3e4721f3deedaf7fc4f2b3af46f719ca9f6cb7937ae3a5f4fff10394f858000abf9fc1eec93fd7f64d1302b547177c238f8d768cffcbcecaf499b969074e32805d344d327c39f0df43985cac009637d1b13e38d4086f033c700074a4694361003dcd33d02ce7dc01b46de2c3c686be9a87a85592b5ecac4c81d13beef76aff0c0429bbeb2a2304bab77c2c2110c1208ece69747ef8168e5cfc23f1c70c3676935fc8f6d73ea23c67254a289cba69dcf3f877ec325ea769d853dbd65e986519797731f10fbe8675d95aa305546341d3513bb6a7e489db50bba421d56179a103c7dae2b9d1d5010b127ebf9fe79f799239fffb1d8bc512d533ce62b5b26be74e9e7fe629b6e61a6daa4eb32731c6d9b6ee93a6690897938a2bcfc77b5e26eca117eb01439151727792f0e81b58566e88da8355f707481a711c83ff9e42f7d71fc1929c5827a195ed368a7fff8b9c93ff6df44badb15d79ce4ad6ddf0606deb115d47c4c5507ed325d18a173e057e33495a83913515980f34b244cb2f80e7502517a1c28ca60847ee04969b6a5a9da4a9a989dad01a64d144d3a95be9cd70dcf4b9c9831a442daab7f41b5afc4a80bb425c244cbef4d649782e3b778fe147a1aa20cb74bcef46fa4d7987d49bc784ff573c7b3ecbcfba121108909c7512005b9f7b9bc21f668210589213e9f1faa3f499349ec4938fadd5a81b5942de5d44ecab9fe09cfcab5111da48ea9a1454d15b27517ef755048eea5f6755a54fe89ce748e1a15843fd0e0683bcf4dcd37cf0eedb489254cd63cde572b164f1621e7d681c2b572c03e00c7b32f7b83a62458eaea5693a7a9b14ca6fbb0cdfc8631bb535947039b1cd5d44dcb3efa1accfad65660b200241da5d7e3ebd3e7c1ed96e232163183dc73f5e67af573072fb3c2bd7b3e2c21b08e4e587ffeedf96c7ea4b6f47576b178b48baa0fc964bd15a27d57c30d80a3c919d95294c92d6a0f89dc66f85f45b863bc77b284e5ec4223d05686c03da1f38b87ba4360649aefcb1374d1ffa1c619e8166f9bcc5d23c6ae6501a282fee802fd4ecaccc05c0b8eaabb4c09f3184c0d103a22ff4ba8eb37b67862e9a46fa3dd7614fafb2ead8fcc82b2c3ff73a1c9dd248bfe73f00942d58cab697df47b25a697dc1e90cf973326d479f6b249e7ff50649a79e50bb41b72223797c3827fd42c2636f18de618d150ad575f48438ca6fbdcc68375547656240084eb625f2745c17ac9284a6697c3be92b9e7cf4613c21ab0a4551f861ea141e7f781cdbb61a4a5a963d99bb62d2b14952f490a710e86d9229bbef5a827d7b444ba66f20566a743a88797322b1e32722bb4ba22b9642d066f4b9747ff92194d8aa307dd2a9c3e9fbed5b689e3dadc18236179e89b5758a31b55e1feb6e7808ff8e5d86754735921cc473f11904fb74abf99e75e099ecacccb5e6adaac117bac6ee52b0135878a813850c77ced42620bcbf86545013d517ef34a047331cda353779d07126716ef2cf5b37a075331cfe14406e0805b5de44ad52a508296bcf5033046a51f08c390fbd5552ad309624cb7856ada778e63c23df48082a96ad61d131e793fbf45bd8daa6d0fdcd470da1a8ac9c4df73e6ba8686f3eca119fbe6c2ce221d2255914d26fbd12dd5fc7c3a9105816af22fefe97b16cc80d5743368ad2a42894dd7b0dbeec1106818912e6938011d6441e8ded4cb26c4508c1fc7973b963ec4d6cd9bc918f3e788f575f7c1e8fc7838244b63d857b62d2b14623e6a1ddabdd3a52f2dc9d6869ed1acf82439250f20a887f72028e5fe618c501526dc22a5914da5df92f7abef50492cd8ae6f1523a6f51785c492333e8f9d613753e6724650ea7d3833723592d084d63ebf36f53fc7b7453f560ff5ef84e3d2edaf99c919d9539debc4d359a1a34a9110fb309c8394ce6f193463ccc0e22bbc898889cf7bea1afe6c0bd51c663a271cff775cd348423809e11d63c4d4fd4a2e0728c705368d11668294994df72a9d10bb446d59fec74b0ee9647c87bef4bb63eff0e4b4ebd14cfaaf55812e3e9f4f0586c6d5a812ec87be74b6287f463e0cccf693bfa3c747f80b2bfaaeee36a61316baebe07c5e5ac7b648a82b27527f18f8dc7f9edafc8a5e551fb9336089ff1f8a818732ee5d75f6c18e346c9d30b2238cd96c473b15d196431f20db7e66ee1e6ebaf63d29713018891146e7475e0de98746c51499ae12fe63b7d38a58fde8470391bc7ccd6a280aae1f8f50fe21e7f0bebe25575aa68b6d4b6747be17ebabd60b4840deedacdbaff8c63e919632898f45378d376975f40daed5747f5444bcacc301ac8033bdfff9aad2fbe5f4b49430844ac8b8a31e72212e26aee621d7061e5438489865783808f1be910029899e1ce510fe5452c621e5f6ec4c3ac03d69b576d54dc42d3e7a755e2ccb9c9834e304f41e393b410411a04fcab198712ae5e3f907b5a83484b95c9da53a7cf188d6152698f2449b6bf9612fbc2fbb51778210c6b0e5d847397924f1d4eef2f5f07a062d91ad4e252e28f1b82a428a8ee1236dcfd3469b75e494c3f23e4bcfed6c7d9f5e9e47d5c060cb2a8a5b7379cfa87f669d430a1b26317b12f7f8c65d3f6a8c45041a248a87ce6ddc5445f554e56778b93fb633a7284e242206a073b85409224ca6e1c45e0d8818d17d2b55a50366d23e683c958d66d3642ba751c4be83a7183fbd27fdafbc82ea7915736e64ecafe596ee4e0391d0cf8e593f07903d870e7936c7fe3d370b108188dd63b3e701392c5c2a6fb9e3542da35f3d2fc414a9f184bb05ff79a3e761ee0e4ecacccbfcd028246bf11aea171f23e7a65b873d61e06f35779f39e036434f0ee75e0a50c77ce5de6955aa5ac84e6fb2e9aa73b44247280ac0c77cececaebc044a37cb65cc0528cd06773e2f60c77cecb0772ae1b7c859f3a7dc627c0a5d56f1b02d7373fe39832b36e522104728c8b61cb7fc1125249842e906463fbe259f35975d96da4dd7a15e9775e0340e9fc45acbcf816b4f2fd37fa96822afecc63a9b8fc7c84ad110da3659998f113b1ff7741d4a47b00ab24332b50c4e3e55bc8b026704f6c3a8eba8a0684406f9544e94337a0b74da9331fae21e09c320be7a4e9fb1c2ed67d7eda8e3a87ce8fdcca9291a3f1ef08b57892253a5cfd6f3a3f7e1b92a5fa5cafb9e22e0abe998e1449642b55c828c5285240a5fcc67fe31f714c4db55207c66567653e6392b426b919de073cd5c0bb5d93e1ce39e2705abce6260ffa3f1a3e94ec07ceca70e7fc76b82fd635fe7627f07c0b19e252200bc8cb70e7883d8ddbc4fe9def10316f034c068e6f01c3f30057025fd7f75c3718518b5c1ca74e9ff1277074a4ba240502c4befa29d6852ba21216dde3a5dbcb0fd2e13fa3aafdddbb610bdb5e7c8fbcf7bf2269c471f49d3c01d9e9400f045879d1cd14fdf607723dc3985220889e1887f7a233080ceb879e9264840f1b3acfcba2e0f8e50f9c937f452a2c8e1a3ab4493225422549b2e017514a067401560bfee387e0b9f27c84ddb677fb937a8c532af7605dba16d7173fa2e4eed8a72e03915793eef5233b6c4658539250ec763adc78299d1ebc1900adac82c0ce029c3d3a1bbc53d558356a2c45bfced9bb49b110f84e3d3e54515cebd29d069c939d95a99bb7ad26b93176013636f06e6fcc70e78c3f8ce6108c24e79540ab06dcb51b48399c16fcba16bdb9c9839231f2d16e05ce6f61c3d680c7310c903765b8734af6f57d99f79fa8441ca02b701cf02486575e4bc21b18be866b33dc39fba52e35a8a2161102ed00cc263234a2c848a515c43ff32e96d51b6b850285a6e3ecd1995eef3d4decc03e006c7be543767ef035de8db92871310c9efb0dce6ec6dcef787b221b6e7d0c794fb969fb82906bbed6250d7fc6107c238f45c4c71a55a20d4c82948ddb707efb2bf6390b0d025433a447f43ee6923f80d6be0d9ecbb2091cd9cf20340d482685d582a40b6cbfff8d63d65f58d66c3248607dbb3b0801022cad12e9fee203a4648f0420b06b37ebae7f00ff8e5df4f9fc151cdd425625056e565f7e07a57fede186a4e904071e41f96d97239cf69aef7f0590919d95596caa694d46309c189e6a031b70d7f119ee9cb2c36c1eedc004604c03eefa8b0c77ce2587da22bf2fef676ef2201b4612f7c0905830103812a3f7624b4531b02444d817639821afcb70e7ecdec76be8902473fb78bedb8488f85060183098e6a9e8dd57946078282e02fe029665b873d6352951ab41d68e0fa91c89d594b50a2ff10fbe8ab27d57d49c3567b78ea4df7d1ddb5ffb18cf9a8d0855452baba0eff7ef9072c6c900f8b76c67e1d0b30d32d150f9594280a2180adbd923f09f1e4a1b6948d52aa42cdae62e26e6a3c9b00f4de4a5a08aefe4a3f15c7e8e5130d090902590656c0b96e19a380d39bf0882c18699d310911a38fb0b6207f6c6b7219795978cc5bb6e1322a8d276f4b9747bf5e1b01aeafe7136ab2ebbbd4e92a6a5b5a1f4993b8d66efd5495a11d02f3b2b738749d29af426aa004f50b33b49fdf153863be7ccc35141989b3c6814f021d050154e2333dc39330fe1f98ac368c7d431a4a074017a85085a72681e6d346053ec264400c3505a0dfdbc0dd810fada1afa7d2b46c834ef7050cce6260fea103adf9d42e7bb7be85c770d11f0caf37d3035bd1718290ac1d0571eb006a3527b23b019a3655f6e863bc7dba84d4aa74e9f710b464e40b50f8cb2239fb8a7df412e2ca94d158540f7070c077a490a2fea3dc63f1ede64e9e96328fb3ba7f14c6cfd01f49444bc179e46e0e881e82ea751fdd8402a96b05ab06cd941cc9b13b16cdd697410887c2fc2a0d07a623cde8bb2f08f383a6af3f7fa9245741dc9ebc7ba621dce49bf60599f8b70d81a7e3e850059a6cfc4d7d870d793f8b66c4792656287f6a7f7c72f60eb6074f5285bb09495a36e452d2caabd0f5d476f9b42c9b3771a2a64f573e0c108779add079ae7667a2646d8a6212ae84665b873261ea6f3d8199815221c070a35c39d633d44e7a92f460b33bbf9e90b633c705364eed32174beefc708611eee286c1467e64a5b84ecacccd780176a09241ddb5371c3bf11f131b59b754b127265585080ab77773ade7f43f8dfbb3e9d4cf9e2158ddac753d86d48a515c44cf88a843b9fc3f5e9f75817ad40aaf01ab96107d8964a0aaa68e9ed297dfa763ca3ce466fdfa6aafa5455114e3bbe538ea5f4f19b8d26f7074ad264d9784f8160989c258c7b85b867de43c9cd3342898d319f2152b82cfb2a7c5bb62302415a6567d2f7abd7c324ad68e61facbefc0e82bb0aa2123dad7d6bcaeeb9261a4903b8373b2bf3b7d0b5667e9c9b1eab317ccf0e146e60c1e1ea2b95e1ced94cc379c77d55a9481ca2f09a1fbbeacbe921fcde84797a0108368a5458d9b83db4783e80e1d7561522513582fd7a5276d755c43ff286a12845cb87522452ce1a813dcde85c102c70b3edd58f108160b464f286852c19e4a6ac02c7f439d8ffb710bd75326acfce04860f2530b017e8c22058f5098f86ec487c59c3090ceb87e3b77938bef91575706f3cffca42ed966e844a7df5ec3223494698d066c5b2760bf6ff2dc0ba6c2d72811ba9a4dc206f8e26880c48124a8c0b6489ce0f8da5c3f5a3c23e69791f7ccd96475f432d2ead550d5ae995567edb18b4d4b691731cd21b79303b2bf3f5ca070393a8350bc1d8303779d07a8c50c481e06f8c50ce61378711219e0f80f31a60979f87ce8d79819a3089da21320f8d16d38d583805f0284653d451914445edde919227c692f0f01b1088d2e24917ec78eb736207f725e5ac116c7ffd233cabd61b8a5b5341928c6a487f0065db4e946d3bb1cf9c8f888dc17fe291044e1c869adab6fe8a94aea32727e0b9e80cbc679d6c34b3af548e0e20d42a1716639fb310fbacbf910b0aab8fcfd2f45e8fba2f80b54d7298a46d7be97d729f7d0ba16a4896dab98ad86d943c751b7a9b949a4458025ecaceca7ca292a09924ad5909c66fc0a9d4df3c5b007f64b873ca0f53b25bf97ddadce4416540dc01ecce0d2c332b054d9838b4d0e8c977a145d43775fa8c9b80f64436a61502ad4b2a65775d49cc9b13918b4b6b111edde763e5c537d3f9e1b16c7be9fda625697b206f527905ceef67e29cf40b7adb560407f444eddb1d35bd1d2236063dc669902eab3544b88c4ac83a499810d5495a4d6a8214f13df4b3a6235578912abcc8e51e94bc022cab36605dba06257707582c86aa2649cd3e65b2cdca9aabee417638f06dd8c29627df34cc8ea311d7362994dd7b4d5d3e719f02779b2a5acb201818396acf1c00512b050eeb161211c4ea558c08447d311f2836499a091387169a64058fa8046d054c04aaafb0160b9695eb897be513c3672c8ae2a37b7d6135a6be109a861eb2dd90ed76c38cb521a00bd035245547386de8ad928cafa404b4564988564968ad12118909e80931e871b1885867759b0d11e5cc4892d185a0928c159722179721bb8b910b8a50761721b94b500a8ddfa5f20a50148422d7df5a23ca7bd3fd7e842e906dd6e8e46a9f4f8011b514ba6e1819d7525075f4d4b694dd7a395ac7f6d1c29ddf025764676596991fdd164534566354ddd507ab33dc39bdcd5984b9c983da73600def1fc870e73c7908cf4f5f602e914e02265e07c61ea2c504f7024f9ba798bc2629678dc859db3d75fa8c0b802f8133c21ba82aea115d2879622cf18f8d47ce2ba845d60e98a4058338ba74a4dd55ff025d27efbd2f09ecc86f18b5499640b61881640172be1b39df6dd00bd9089d0a45318a101419211b6efdc2e540c4b8c06635fe1f4158a440d020685eafa19ce9baa12e693a92aa19396e9a5e45b52569ffcc69f7915829f1b1a45e31067b8736147cfd13257f2e362a72ebf558600c362a415635b4f4b6943d7c237a7c6cb470e764e05fd95999baa9a6b53825e8a303b8a17e5d635f87ed5c023b3142c923ebb18b4a7f2613264c1c62689698d8d4e9339280ef80136b0da8a49cf8e7df47d9b8b5413dd26206f6a6dfe4095892120c5e505c4acef07fe1dfbeab9966a16a6cc6f76867456ad6b129b131f49b3c81d8a1fd8c21aa2a6baeba87c2a9331bb6984308d423ba52fad00d75b9fe4ecececabc20a29ad8fce4b62c92d1364432ea838e19ee9cade62c86c9da688cf0fefe623d704c863ba7f0109e1f5351ab8d4359516b8c36750723f2e4a63e62480d29022e027eadb56627c75376f7550486f5370c611b8471c8b4bee0f4304903b024c6d3eeaa0b11c1603353e55008508ef89222f2d00e84ff687abd0d7b85a6137fd4c0304903902c16da5c9c8d1ceb6ab8f7af0bfc271f4de97dd7d626ac062612d13bd624692d8f5c64b8737661f42edc5f2cc970e76c3d5c6d396a22a4282ec030bfdc5fac3894491a5577071f8651a8cffc020cb3d443159504e0703ec701c0dfe44ebe1161d05d53a7cfb8107809b82abc81a6a327c65371e32568ddd2717df603c2a21c90ba26c9324a94d0a925390921c401f121a1eb88a08ad034648bc5c8e16aaee47d5da0ab414450055de03aa22bba3f40b0c0bdff6312024b4aed075725c689a43440d5a830ac4d2aaeb910df8863a076c70180d7807bb2b3327d66b8b345930b306c2106ece7cb5f3567b0163662d8959cb39faffb2682381faa73b309388b83cb81be312163e4341eaa36169f6328a8877bffe640b305d6220a0cecc073c02dd11673fb9f4b70bdf33552a0fe0f0e42d7697dde69f49cf00492ad2abf6ac9c8d194ffb3bcde613ca169383aa69270e251c4f6ef45c9fc4514cf9c8756d1c49e8c4220741d5b9b14124e3a96a411c79278d2d1d8dab561ebf3efb0f9d157eb9557666d95cc918b7f44765591dc2d8fbdcab6573e3c30322a04c261a7ecbe6b517b76aecb86e4deecaccc6723af15132d5a59ebbf9faa9a005a65b873dce6ecd59acb1b8197d9bf96528e0c778edf9c3d1387c867c0b4988980d452063275fa8c17819ba8d99fcd6ec59ab31ad77bdf86fa83d62f5a2b822a1daefd376dc75c80d074768cff94826fa6d76fb0bac0d9a31383e77f572b31beecef252cc9bcb4ba3798ae23d92b53863c00000e89494441546dd85aa7203bed0855432d2d27b8bb287af563e4b8550dd966c5929c80121b8b0804f0ef2c088734759f9fd49b2e27fdceabb1b64ea9f5fad23f73c839f962947af6098d3f66309d1eba054b5c0cee5fe790fbf4784450abff95a30bd4ee1da9f8cfc568e9eda285663d2192f6ba49d00e9e9b2a9082d185a4f33e3c015b8085c0fd80dfbc21d79acf74e04d20661f3657803519ee9cebccc5cd840993a83516410b2fc653a7cfb832f424195f8dacd8ac28bb0a714efa19fbecbfaa72b8f69723f8fc068192248300d5b37a51683a09c70fa1ff8f1f2254151150c3aad3e6475e61fbab1f86ed3184a6e3ead199f4bbae2576483facad92d07d7e7c9bb7513c6b1edb5ef900a16a51df8fee0f907cea09a49c3392d87ebdb0774aa56ce152d65e370eadbcc2d8c6e3a5cbd3779376eb95e1d7f9b76ca762d57a3cab37e059b59efc2f7ea8b7ad860806418064b5a0797d4608b93e6a9aae2309f09e7d12de734e4124c61b1d29aaa310b8263b2bf33bf3a369c25412f6eb75d2a198506ec2848916a2a8d5206bc330f22d3a561fa904aa8a7d7e0e31ef4caaddc8bc292104b6b476c41f3980e2d97f3278eed7d852db01b0347334658b5684b753625d0c5dfc23d694a4a8bb2afce9bfacfcbfeb6bd98f681e1fbdde7b86d6179c5ecde4d7b3622dcbceb916b5a8244ce6da5f7d11dd5f7908b5a88455a36fc3bb2117dde345abf02034bde1fce2ea0b4d4724c6517ec32504fb75376c4a6a873b9703d9d959999b6a5e13264c983061c2c4e10ab9250c2282a4919d95b900381623df454492231405ff494751fcf2bde8a96d9b91de4a04b6eda4e0db9f11082cad9201a31769a0a028bc99eef5913af6ca3049cbff622a7362fbb2f8b80bf06f330abb124f388ac4938e416855bd75757f803e5fbc4adb51e7203bec88a08afbc7d9acbbe141d65c731f5a6995dfab244bf8738d7d49362b6a5109bac70bb2d1635376da9b2fd534e423a7f5ea42c933b7131c74447593dfaaada603c76767656e322d384c983061c284892ab4a8ea99888ad01d53a7cf1804bc058c01aa242555436f9d4cf18bf7e09cf4338e19f3908a4b0d95a689c91a42103f6c005228cce959bb09adacaa65a1d0745cbdba867fdff9c964248b8267f506ca162ec39ed61ec9a2606dd73a4c5e84aad2e6e2b34939fb14639f2bd6b2facabb29f97b11b26c4576d891edf62a2d5496c3a44f713ae9f9f65338baa4a1c4c6a079bc14cf9ecf8e373fa564eec203eb2ab0bfd3a36a686d92f19f9a81f7fc4cc3a0b7763e5a05f006705f7656a630099a0913264c9830511d724b1b50045913d95999ffc168e45e506d235d07a1e3bdf034caeebf96e03103912abc07d4c4bc3e109a46dc9103c2a145efda8da8916a97d542f1ecf9e1df3b3f722b8e4ea9b8fa7427aed29f4c08ca172d0fe7b4297131b43eff74244541f778d9f6da47a49c39827e5fbf45af0f9e23f9b413105a556e9724cbf8b786bacec81231fd7ba1c41a39c88acb49ca9923e8f5e1f3c41d39a09a6ad778932290fc017c238ea2ecfe6bf19e770a0483d148da1a8c50e7bdd95999a252493361c2840913264c44081f2d756035f2d65a033f014746dd58d7b12d594dcc84af904acb1baecfe5de3889aad1f7eb37483aed04d07536deff3c3bdefabc5ac5a7d034fa7efb1649a71c6f0cd51f0009e4904dc8baebc6b173e2f7c836a312dfd1398d7edfbd8dbd630784a611dcb91b5b873661050f4922f7c937c97d76425821d303018697ada064ee424afef7376a5109b143fad1faffb2c2dbe4bdfb251bef79a671f3fa340d919c40d92d97a21ed1754f5bfe085c9e9d955958f35c9b3061c284091326aad0628d036be4ad1500c3a64e9ff130700710576d63592630ac3fc15e5d89f9f83bac39ab9b84b0d9dab7c6d6be0d006a59059ed51baa1121a169d853dbe159bd314cd4222b4d739f1a4ffea41f912342924a6c0cb6b6ad0c16ad282809b194e7acc2921887a34b3a001dc7dd48f1fffea274fe6224ab05d966637ebb6104dd254836ab51d5ea0f10ccdf4deacd6340967076eb84121f572d34db306c558010e889f1048e1f8c67d4d946183a7a478422e0c9ecaccc1723099a49d24c983061c28489e8905bfa002b43a1a19f1f05b2805f6a6da86a88582765b78da1ec8e2b089c300c140549551b676002eca96dc3444daff0e059bdc1f045c330d9b5a7b6a3ffd4f748bdf1521050f8fd0cb6bffa01c1dd86c767c7fb6fa0eb0bf71bf61c214816052944e6749f9f75373dcca2a3cf61d1d1e75134634e78bbb4b157a2fbaafc2df54010252e06d96e43b65991ac162a56adaf0a770abdc143c3522088703af09f7a3c65f75e43c555ff6790e3e8246d32303c3b2bf3c5a9d367982a9a0913264c9830b10f38285a71442ee8d959997f845a4f9d03bc035439b9ea02c9e7473da20be5ddd2b1640dc7317906f6390b110e7b032b6c025b87b6585b1b159f81fc427c9bb7a3c418c311419576979d8fa3aba18215cdfa83f5b73d8e5a5c4ac9bc45f4faf07914979336179ec9ae4f26539eb30a499111c120bac787ec72a07bfd144e9b85121b835055f2defb9aa4cce100c4f4eb512de74c0881f0070c454fd7b12427d276d4b9e1d067c5ea0da8a565e1c2870382ae230555fca71c83f7ec93d13ab4018b82e40f44dbba0ca320e427b315940913264c9830b17f900fd271976567657e0674016652d5bc354cd85014d4cea994df751525af8e43edd1a9deada2a241521462fb1f11febd64de3fd533fe741d7be7b47028d4b7653b6a51294812152bd6e1dbb835bc1f7b87b661b54b2d29c7bf7da7f13fab0557afae884a852a62ff815d85d5de8fb35b273a3f3296b4b157d0e3eda718b6e25712860f33b6cdcb27efdd2f1b2621519250fbf6a0f8d57194dd72295a5abb908a564badd3812f80d6d959999309351136499a0913264c9830b1ef38289bdb46367607464e9d3e631446eedae05a1b6b1a6a97544a9fb903eb82653866ccc3b2612bb2bb18212bf5276f8a8cad435b84a621290a25bfff856c89984e593672d64248386e28f1c70ec6b7692b09c70fc5d1250d00a16b7837e686c711d8994fd982a5387b744689719276eb956c79e20d248b4287eb2e09efafe0eb1fc305090811ea7e701d35096bf992556cb8f309bceb365733cedd67e83a92a6a3b54946edd919dfe9c309f6ef891454918251c3ca0298073c959d95f91398c502264c983061c244bdf5918379f0352a4313810b8107a8d9d5204c4b15d0742c1b72b1e6acc631fb2fe46d3b1176fbfef71095241c1d3be0e8928eab4717767d3915adb4bcaa98400894b858fa4d7987d8c17d0c1296bf9b405e8191d81feb0220efbd2fd970db134611004601424c9f1ef49ffe219684781002ef865c24590e87514be72f62f9b9d722826ab81a346e687f7abcf504084170b71bcfca7514cf5d48f1ec3f51ddc5fbefa1a66a48aa8ada3915ff886308f6eb81d625a410d66df3b10c7806989a9d95596e923413264c983061e230266a3509dbd4e933240c73dc9b428441a9f35d6b024955b12e5b87f3db5f50d6e71a646d7f73b842961975fd4f763ae878eff5464141c4be85aab1e59157d83ee173236c18b10ba1ebb8ba77a6cfa43770744eafb6cbc269b38c5c377771f5b724cb480e3b52e8f542d510f529a4d07524552338a017def34e21784457a38a73cfb61e01e016e0e3ecac4c9f49d04c983061c2840993a845256c100e8dc6032f02d9409b3a5fa4c808ab15ebba2dd87f9e63587b787d489515950de03ba6fb0348560b31fd7ae2e8d08e40819bf29c1588a01a56d2a2913cdd17203e6328b17d7b22749dd2f98ba858bed6086136941f5a654704a703e1721038b21fbe334f44ebd00649d5eaaae00423c4b90bf898506701b3fd930913264c98306112b5fd256f4700a381f381de7b236c726939d6a56bb0acdc8065432e96cddb912abc08453114b7fa122421109a86d005922c21ed5da5325e1654c3d59d92c552cd4cb7dec44cd390540d3d210ead4b2a6ab78e04fb7447eddf133dd689e40fee89a001fc037c077c929d95b9b592249b04cd840913264c983089dabe1234a09a716e3be024e06ea2151d8467443212fb75815c5286545c8675fd16ac0b97635dba06a9c287b05a8c7c37e920993e5d20691a0455449c8be0e03e048eec87da25153d311e1117137acffade1ab8ff013c07fc152ae4c054d14c983061c2840993a83508718b206d4703cf03c7615894ec7d1e24c9284458b311fbdfcbb1e6ac44ce7787c89ad81bc169a6336bbc2dad5d0ac1a1fd080ceb87dab34bb89bc03e4060d86c4c07eecdceca5c51732e4d983061c284091326516b2cf2d60bb8161809740562f7fa22454158145014e4dd4558d76e46d9988b65f30ea4a212248f0fd9e343f2fac01730542a49aa224e12d595b8bda97295844a1041088def921008454638ece11c33e172a02727a0754d47ed9a8edab3137a5222a8aad1a541d3f7656a8a818d18fd55df31c39b264c983061c28449d49a92a001d5c2a2f1c051c070e06cf6141aad367b9291db261bb96b728507a9a80cb9b40cb9a41ca9ac02b9b80cb9b814a9a41cb9ac02a9c28354e145f2f98daf6010541d82c12ad35845068bc5208576ab41c41c768388c5c5a0c7c5a0c7c72292e2d113e3d0e3621071b1c6cf89f1881867d8ffcc0867eeb3dc370f433d9b03fc9d9d95e98d9c3393a4993061c284091326516b4e022703314007e05fc06540f7fd9fd18869d50db224e9ba41c42abf87c28f921035d432aaa96f4292428a5c28774e362c44febfbdbb6569288cc3387c6dc5288aa2a0616a528b60f0132cac2c59fc725641b0580c7e03a3882f589c45c30682b0a6333c4798631a0ce2f4bee0a4539e73d28fe7bcfc07f5fa988f1bbef508f612fb384217fd76abf99a388b888848a8fdc658fb1027c727a7cbd8c36e156d53ca7cd1491bbff5a28c71eae306873868b79addcfae3d222222126a93147133d8c13636d150a6202cfdd225dfa3833b5c28bfd5386bb79acf89b388888884da5f8ab4d19d3698c76c75ac620b1bd5d1f8e125dee2ba0ab273e5638027f4daad666ff45ac82f35222222126aff20da86e2a736745f6bca448475ac6145d97d5bc402e630ad3c46fd4aff3db8f088076597ac53c5d955756ef8edb4c1b8f525cc22222226cb1b87cb41714c87e1770000000049454e44ae426082, '2.0.9', NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE IF NOT EXISTS `emails` (
  `id` tinyint(1) unsigned NOT NULL COMMENT 'ID of the email settings (should remain 1).',
  `from` varchar(255) DEFAULT NULL COMMENT 'Sender''s email address.',
  `alias` varchar(32) DEFAULT NULL COMMENT 'Alias for the sender.',
  `host` varchar(255) DEFAULT NULL COMMENT 'The SMTP host address.',
  `port` int(11) unsigned DEFAULT NULL COMMENT 'The SMTP server port.',
  `username` varchar(255) DEFAULT NULL COMMENT 'The username for the SMTP server.',
  `password` tinyblob DEFAULT NULL COMMENT 'The encrypted password for the SMTP user.',
  `tls` boolean DEFAULT NULL COMMENT 'If TLS should be used.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `from` (`from`),
  UNIQUE KEY `alias` (`alias`),
  UNIQUE KEY `host` (`host`),
  UNIQUE KEY `port` (`port`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `tls` (`tls`),
  UNIQUE KEY `created` (`created`),
  UNIQUE KEY `modified` (`modified`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='SMTP email settings.';

--
-- Dumping data for table `emails`
--

INSERT INTO `emails` (`id`, `from`, `alias`, `host`, `port`, `username`, `password`, `tls`, `created`, `modified`) VALUES
  ('1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `user_id` int(10) unsigned NOT NULL COMMENT 'The user ID for the subscription settings.',
  `newsletter` boolean NOT NULL COMMENT 'If the user is subscribed to the newsletter.',
  `studies` boolean NOT NULL COMMENT 'If the user is subscribed to new study announcements.',
  `reminders` boolean NOT NULL COMMENT 'If the user is subscribed to study reminders.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Email subscription settings for a user.' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `user_id`, `newsletter`, `studies`, `reminders`, `created`, `modified`) VALUES
  (1, true, true, true, true, NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the entry.',
  `username` varchar(16) NOT NULL COMMENT 'Username for the user.',
  `password` varchar(64) NOT NULL COMMENT 'Hashed password for the user.',
  `email` varchar(255) NOT NULL COMMENT 'Email of the user.',
  `fname` varchar(32) NOT NULL COMMENT 'First name of the user.',
  `lname` varchar(32) NOT NULL COMMENT 'Last name of the user.',
  `role_id` int(10) unsigned NOT NULL COMMENT 'Role assignment for the user.',
  `logins` int(11) NOT NULL DEFAULT '0' COMMENT 'The login counter for the user.',
  `visit` timestamp NULL DEFAULT NULL COMMENT 'The last time the user logged in.',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of entry creation.',
  `modified` timestamp NULL DEFAULT NULL COMMENT 'The last edited time.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Roles for various users.' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `fname`, `lname`, `role_id`, `logins`, `visit`, `created`, `modified`) VALUES
  (1, 'admin', '56d22830d7f723212a140d1d2061c6c75e7ac2b3008d2cc673be6831eb84d8b6', 'admin@mysite.com', 'System', 'Admin', 1, 0, NULL,  NOW(), NOW());

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`);

--
-- Constraints for table `rosbridges`
--
ALTER TABLE `rosbridges`
  ADD CONSTRAINT `rosbridges_ibfk_1` FOREIGN KEY (`protocol_id`) REFERENCES `protocols` (`id`);

--
-- Constraints for table `streams`
--
ALTER TABLE `streams`
ADD CONSTRAINT `streams_ibfk_1` FOREIGN KEY (`environment_id`) REFERENCES `environments` (`id`);

--
-- Constraints for table `teleops`
--
ALTER TABLE `teleops`
  ADD CONSTRAINT `teleops_ibfk_1` FOREIGN KEY (`environment_id`) REFERENCES `environments` (`id`);

--
-- Constraints for table `markers`
--
ALTER TABLE `markers`
  ADD CONSTRAINT `markers_ibfk_1` FOREIGN KEY (`environment_id`) REFERENCES `environments` (`id`);

--
-- Constraints for table `ims`
--
ALTER TABLE `ims`
  ADD CONSTRAINT `ims_ibfk_1` FOREIGN KEY (`environment_id`) REFERENCES `environments` (`id`),
  ADD CONSTRAINT `ims_ibfk_2` FOREIGN KEY (`collada_id`) REFERENCES `colladas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `ims_ibfk_3` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `urdfs`
--
ALTER TABLE `urdfs`
  ADD CONSTRAINT `urdfs_ibfk_1` FOREIGN KEY (`environment_id`) REFERENCES `environments` (`id`),
  ADD CONSTRAINT `urdfs_ibfk_2` FOREIGN KEY (`collada_id`) REFERENCES `colladas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `urdfs_ibfk_3` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tfs`
--
ALTER TABLE `tfs`
  ADD CONSTRAINT `tfs_ibfk_1` FOREIGN KEY (`environment_id`) REFERENCES `environments` (`id`);

--
-- Constraints for table `environments`
--
ALTER TABLE `environments`
  ADD CONSTRAINT `environments_ibfk_2` FOREIGN KEY (`mjpeg_id`) REFERENCES `mjpegs` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `environments_ibfk_1` FOREIGN KEY (`rosbridge_id`) REFERENCES `rosbridges` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `ifaces_environments`
--
ALTER TABLE `ifaces_environments`
  ADD CONSTRAINT `ifaces_environments_ibfk_1` FOREIGN KEY (`iface_id`) REFERENCES `ifaces` (`id`),
  ADD CONSTRAINT `ifaces_environments_ibfk_2` FOREIGN KEY (`environment_id`) REFERENCES `environments` (`id`);

--
-- Constraints for table `conditions`
--
ALTER TABLE `conditions`
  ADD CONSTRAINT `conditions_ibfk_1` FOREIGN KEY (`study_id`) REFERENCES `studies` (`id`),
  ADD CONSTRAINT `conditions_ibfk_2` FOREIGN KEY (`iface_id`) REFERENCES `ifaces` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `conditions_ibfk_3` FOREIGN KEY (`environment_id`) REFERENCES `environments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `slots`
--
ALTER TABLE `slots`
  ADD CONSTRAINT `slots_ibfk_2` FOREIGN KEY (`condition_id`) REFERENCES `conditions` (`id`);

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`slot_id`) REFERENCES `slots` (`id`);
--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`),
  ADD CONSTRAINT `logs_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`);

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
