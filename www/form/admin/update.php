<?php
/*********************************************************************
 *
 * Software License Agreement (BSD License)
 *
 *  Copyright (c) 2012, Worcester Polytechnic Institute
 *  All rights reserved.
 *
 *  Redistribution and use in source and binary forms, with or without
 *  modification, are permitted provided that the following conditions
 *  are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *   * Redistributions in binary form must reproduce the above
 *     copyright notice, this list of conditions and the following
 *     disclaimer in the documentation and/or other materials provided
 *     with the distribution.
 *   * Neither the name of the Worcester Polytechnic Institute nor the
 *     names of its contributors may be used to endorse or promote
 *     products derived from this software without specific prior
 *     written permission.
 *
 *  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 *  "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 *  LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 *  FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 *  INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 *  BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 *  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 *  CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 *  LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 *  ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 *  POSSIBILITY OF SUCH DAMAGE.
 *
 *   Author: Russell Toris
 *  Version: October 5, 2012
 *
 *********************************************************************/
?>

<?php
// start the session
session_start();

// load the include files
include('../../inc/config.inc.php');
include('../../inc/log.inc.php');

// grab the user info from the database
$sql ="SELECT * FROM user_accounts WHERE userid = '".$_SESSION['userid']."'";
$query = mysqli_query($db, $sql);
$usr = mysqli_fetch_array($query);
// now make sure this is an admin
if(strcmp($usr['type'], "admin") != 0) {
  // report this
  write_to_log($usr['username']." attempted to use the update script.");
  // send the user back to their main menu
  header('Location: ../../main_menu.php');
}

// it is an admin, get the start version
$start = $_POST['version'];
$start = str_replace(".", "_", $start);
// call the update function
$update_func = "update_".$start;
$update_func();

// recheck the version number for the log
$sql = "SELECT * FROM version";
$query = mysqli_query($db, $sql);
$v = mysqli_fetch_array($query);

// log this and send them back to the admin panel
write_to_log($usr['username']." updated the database from version ".$_POST['version']." to ".$v['version']);

// now run the Javascript updater
$url = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https://" : "http://";
$url .= ($_SERVER["SERVER_PORT"] != "80") ?  $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"] : $_SERVER["SERVER_NAME"];
$url .= "/form/admin/setup.php";
// make a POST request
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, "req=update");
curl_exec($curl);
curl_close($curl);

header('Location: ../../admin.php#maintenance-tab');

/**
 * A function to update the database schema from 0.0.1 to 0.0.2.
 */
function update_0_0_1() {
  global $db;

  // no major updates, just change the version number
  $sql = "UPDATE version SET version = '0.0.2' WHERE version = '0.0.1'";
  mysqli_query($db, $sql);
  update_0_0_2();
}

/**
 * A function to update the database schema from 0.0.2 to 0.0.21.
 */
function update_0_0_2() {
  global $db;

  // no major updates, just change the version number
  $sql = "UPDATE version SET version = '0.0.21' WHERE version = '0.0.2'";
  mysqli_query($db, $sql);
  update_0_0_21();
}

/**
 * A function to update the database schema from 0.0.21 to 0.0.22.
 */
function update_0_0_21() {
  global $db;

  // no major updates, just change the version number
  $sql = "UPDATE version SET version = '0.0.22' WHERE version = '0.0.21'";
  mysqli_query($db, $sql);
  update_0_0_22();
}

/**
 * A function to update the database schema from 0.0.22 to 0.0.3.
 */
function update_0_0_22() {
  global $db;

  // add the Javascript file table
  $sql = "CREATE TABLE IF NOT EXISTS `javascript_files` (
  `fileid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the file.',
  `url` varchar(511) NOT NULL COMMENT 'URL to the file that will be downloaded.',
  `path` varchar(511) NOT NULL COMMENT 'Local file path (including the file name) relative to the server root for the file.',
  PRIMARY KEY (`fileid`),
  UNIQUE KEY `url` (`url`,`path`)
  ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='A list of Javascript files that are to be downloaded and maintained.' AUTO_INCREMENT=5 ;";
  mysqli_query($db, $sql);

  // add the initial Javascript files
  $sql = "INSERT INTO `javascript_files` (`fileid`, `url`, `path`) VALUES
  (3, 'https://raw.github.com/robotwebtools/keyboardteleopjs/master/keyboardteleop.js', 'js/ros/widgets/keyboardteleop.js'),
  (4, 'https://raw.github.com/robotwebtools/mjpegcanvasjs/master/mjpegcanvas.js', 'js/ros/widgets/mjpegcanvas.js'),
  (1, 'https://raw.github.com/robotwebtools/rosjs/master/example/eventemitter2.js', 'js/ros/eventemitter2.js'),
  (2, 'https://raw.github.com/robotwebtools/rosjs/master/ros.js', 'js/ros/ros.js');";
  mysqli_query($db, $sql);

  // change the version number
  $sql = "UPDATE version SET version = '0.0.3' WHERE version = '0.0.22'";
  mysqli_query($db, $sql);
  update_0_0_3();
}

/**
 * A function to update the database schema from 0.0.3 to 0.0.4.
 */
function update_0_0_3() {
  global $db;


  // add the new Javascript files
  $sql = "INSERT INTO `javascript_files` (`fileid`, `url`, `path`) VALUES
  (NULL, 'https://raw.github.com/RobotWebTools/map2djs/master/map.js', 'js/ros/widgets/map.js'),
  (NULL, 'https://raw.github.com/RobotWebTools/actionlibjs/master/actionclient.js', 'js/ros/actionclient.js'),
  (NULL, 'https://raw.github.com/RobotWebTools/nav2djs/master/nav2d.js', 'js/ros/widgets/nav2d.js');";
  mysqli_query($db, $sql);

  // new widgets
  $sql = "INSERT INTO `widgets`  (`widgetid`, `script`, `table`, `name`) VALUES
  (NULL , 'map2d', 'maps', 'Map 2D'),
  (NULL , 'nav2d', 'navigations', '2D Navigation');";
  mysqli_query($db, $sql);

  // new widget tables
  $sql = "CREATE TABLE IF NOT EXISTS `maps` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the map.',
  `envid` int(11) NOT NULL COMMENT 'Unique identifier for the environment.',
  `label` varchar(255) NOT NULL COMMENT 'Label for the widget.',
  `topic` varchar(255) NOT NULL COMMENT 'Topic of the map (nav_msgs/OccupancyGrid)',
  `continuous` tinyint(1) NOT NULL COMMENT 'If the map should be continuously loaded or loaded and saved once.',
  PRIMARY KEY (`id`)
  ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Map 2D widget info.' AUTO_INCREMENT=1 ;";
  mysqli_query($db, $sql);
  $sql = "CREATE TABLE IF NOT EXISTS `navigations` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the navigation widget.',
  `envid` int(11) NOT NULL COMMENT 'Unique identifier for the environment.',
  `label` varchar(255) NOT NULL COMMENT 'Label for the widget.',
  `mapid` int(11) NOT NULL COMMENT 'ID number for the map widget to use with this nav (in the ''maps'' table).',
  `server_name` varchar(255) NOT NULL COMMENT 'Name of the action server (e.g., /move_base)',
  `action_name` varchar(255) NOT NULL COMMENT 'Basename of the action (e.g., move_base_msgs/MoveBaseAction)',
  PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Nav 2D widget info.' AUTO_INCREMENT=1 ;";
  mysqli_query($db, $sql);

  // new interface
  $sql = "INSERT INTO `interfaces` (`intid`, `name`, `location`) VALUES (NULL, 'Nav2D Interface', 'simple_nav2d');";
  mysqli_query($db, $sql);

  // change the version number
  $sql = "UPDATE version SET version = '0.0.4' WHERE version = '0.0.3'";
  mysqli_query($db, $sql);
  update_0_0_4();
}

/**
 * A function to update the database schema from 0.0.4 to 0.0.5.
 */
function update_0_0_4() {
  global $db;

  // no major updates, just change the version number
  $sql = "UPDATE version SET version = '0.0.5' WHERE version = '0.0.4'";
  mysqli_query($db, $sql);
  update_0_0_5();
}

/**
 * A function to update the database schema from 0.0.5 to 0.0.51.
 */
function update_0_0_5() {
  global $db;

  // no major updates, just change the version number
  $sql = "UPDATE version SET version = '0.0.51' WHERE version = '0.0.5'";
  mysqli_query($db, $sql);
  update_0_0_51();
}

/**
 * A function to update the database schema from 0.0.51 to 0.0.52.
 */
function update_0_0_51() {
  global $db;

  // no major updates, just change the version number
  $sql = "UPDATE version SET version = '0.0.52' WHERE version = '0.0.51'";
  mysqli_query($db, $sql);
  update_0_0_52();
}

/**
 * A function to update the database schema from 0.0.52 to 0.0.53.
 */
function update_0_0_52() {
  global $db;

  // change the location of ros.js
  $sql = "UPDATE javascript_files SET url = 'https://raw.github.com/robotwebtools/rosjs/fuerte-devel/ros.js' WHERE url = 'https://raw.github.com/robotwebtools/rosjs/master/ros.js'";
  mysqli_query($db, $sql);
  $sql = "UPDATE javascript_files SET url = 'https://raw.github.com/robotwebtools/rosjs/fuerte-devel/example/eventemitter2.js' WHERE url = 'https://raw.github.com/robotwebtools/rosjs/master/example/eventemitter2.js'";
  mysqli_query($db, $sql);

  // change the version number
  $sql = "UPDATE version SET version = '0.0.53' WHERE version = '0.0.52'";
  mysqli_query($db, $sql);
  update_0_0_53();
}

/**
 * A function to update the database schema from 0.0.53 to 0.0.54.
 */
function update_0_0_53() {
  global $db;

  // change the location of ros.js to use the new bundled version
  $sql = "UPDATE javascript_files SET path = 'js/ros/ros_bundle.min.js', url = 'https://raw.github.com/RobotWebTools/rosjs/fuerte-devel/dist/ros_bundle.min.js' WHERE url = 'https://raw.github.com/robotwebtools/rosjs/fuerte-devel/ros.js'";
  mysqli_query($db, $sql);
  $sql = "DELETE FROM javascript_files WHERE url = 'https://raw.github.com/robotwebtools/rosjs/fuerte-devel/example/eventemitter2.js'";
  mysqli_query($db, $sql);

  // remove the old files
  unlink('../../js/ros/ros.js');
  unlink('../../js/ros/eventemitter2.js');

  // change the version number
  $sql = "UPDATE version SET version = '0.0.54' WHERE version = '0.0.53'";
  mysqli_query($db, $sql);
  update_0_0_54();
}

/**
 * A function to update the database schema from 0.0.54 to 0.0.6.
 */
function update_0_0_54() {
  global $db;

  // add the new tables for studies
  $sql = "CREATE TABLE IF NOT EXISTS `study` (
  `studyid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the study.',
  `name` varchar(255) NOT NULL COMMENT 'Name of the study.',
  `description` text NOT NULL COMMENT 'Description of the study.',
  `start` date NOT NULL COMMENT 'Start date of the study.',
  `end` date NOT NULL COMMENT 'End date of the study.',
  PRIMARY KEY (`studyid`)
  ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='A table to hold information about different studies.' AUTO_INCREMENT=1 ;";
  mysqli_query($db, $sql);
  $sql = "CREATE TABLE IF NOT EXISTS `study_log` (
  `logid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the log entry.',
  `expid` int(11) NOT NULL COMMENT 'Unique identifier for the experiment.',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp for the entry.',
  `entry` text NOT NULL COMMENT 'The log entry.',
  PRIMARY KEY (`logid`)
  ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='A table to hold log information during studies.' AUTO_INCREMENT=1 ;";
  mysqli_query($db, $sql);
  $sql = "CREATE TABLE IF NOT EXISTS `conditions` (
  `condid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the condition.',
  `studyid` int(11) NOT NULL COMMENT 'Unique identifier for the study.',
  `name` varchar(255) NOT NULL COMMENT 'Name of the condition.',
  `intid` int(11) NOT NULL COMMENT 'Unique identifier for the interface used in this condition.',
  PRIMARY KEY (`condid`)
  ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='A list of conditions for a given study.' AUTO_INCREMENT=1 ;";
  mysqli_query($db, $sql);
  $sql = "CREATE TABLE IF NOT EXISTS `experiments` (
  `expid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the experiment.',
  `userid` int(11) NOT NULL COMMENT 'Unique identifier for the user.',
  `condid` int(11) NOT NULL COMMENT 'Unique identifier for the condition.',
  `envid` int(11) NOT NULL COMMENT 'Unique identifier for the envionmnet.',
  `start` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Start time for the experiment.',
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'End time for the experiment.',
  PRIMARY KEY (`expid`),
  UNIQUE KEY `userid` (`userid`,`condid`)
  ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Pairs of users and conditons that make up an experimental trial.' AUTO_INCREMENT=1 ;";
  mysqli_query($db, $sql);

  // change the version number
  $sql = "UPDATE version SET version = '0.0.6' WHERE version = '0.0.54'";
  mysqli_query($db, $sql);
  update_0_0_6();
}

/**
 * A function to update the database schema from 0.0.6 to 0.0.61.
 */
function update_0_0_6() {
  global $db;

  // change the location of ros.js
  $sql = "UPDATE javascript_files SET url = 'https://raw.github.com/RobotWebTools/keyboardteleopjs/fuerte-devel/keyboardteleop.js' WHERE url = 'https://raw.github.com/RobotWebTools/keyboardteleopjs/master/keyboardteleop.js'";
  mysqli_query($db, $sql);
  $sql = "UPDATE javascript_files SET url = 'https://raw.github.com/RobotWebTools/mjpegcanvasjs/fuerte-devel/mjpegcanvas.js' WHERE url = 'https://raw.github.com/RobotWebTools/mjpegcanvasjs/master/mjpegcanvas.js'";
  mysqli_query($db, $sql);
  $sql = "UPDATE javascript_files SET url = 'https://raw.github.com/RobotWebTools/map2djs/fuerte-devel/map.js' WHERE url = 'https://raw.github.com/RobotWebTools/map2djs/master/map.js'";
  mysqli_query($db, $sql);
  $sql = "UPDATE javascript_files SET url = 'https://raw.github.com/RobotWebTools/actionlibjs/fuerte-devel/actionclient.js' WHERE url = 'https://raw.github.com/RobotWebTools/actionlibjs/master/actionclient.js'";
  mysqli_query($db, $sql);
  $sql = "UPDATE javascript_files SET url = 'https://raw.github.com/RobotWebTools/nav2djs/fuerte-devel/nav2d.js' WHERE url = 'https://raw.github.com/RobotWebTools/nav2djs/master/nav2d.js'";
  mysqli_query($db, $sql);

  // change the version number
  $sql = "UPDATE version SET version = '0.0.61' WHERE version = '0.0.6'";
  mysqli_query($db, $sql);
}

/**
 * A function to update the database schema from 0.0.61 to 0.1.0.
 */
function update_0_0_61() {
  global $db;

  // change the version number
  $sql = "UPDATE version SET version = '0.1.0' WHERE version = '0.0.61'";
  mysqli_query($db, $sql);

  update_0_1_0();
}

/**
 * A function to update the database schema from 0.1.0 to 0.1.01.
 */
function update_0_1_0() {
  global $db;

  // update articles table
  $sql = "ALTER TABLE `articles` CHANGE `artid` `artid` INT( 11 ) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the article.',
          CHANGE `pageid` `pageid` INT( 11 ) NOT NULL COMMENT 'The ID of the page for this article to be displayed on.',
          CHANGE `pageindex` `pageindex` INT( 11 ) NOT NULL COMMENT 'The order of this article on its given page.';";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `articles` ADD UNIQUE (`title`, `pageid`);";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `articles` ADD INDEX ( `pageid` ) ;";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `articles` ADD FOREIGN KEY ( `pageid` ) REFERENCES `content_pages` (`pageid`) ON DELETE CASCADE ON UPDATE CASCADE ;";
  mysqli_query($db, $sql);

  // update conditions table
  $sql = "ALTER TABLE `conditions` ADD UNIQUE (`studyid` ,`name` ,`intid`);";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `conditions` ADD INDEX ( `studyid` ) ;";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `conditions` ADD INDEX ( `intid` ) ;";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `conditions` ADD FOREIGN KEY ( `studyid` ) REFERENCES `study` (`studyid`) ON DELETE CASCADE ON UPDATE CASCADE ;";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `conditions` ADD FOREIGN KEY ( `intid` ) REFERENCES `interfaces` (`intid`) ON DELETE CASCADE ON UPDATE CASCADE ;";
  mysqli_query($db, $sql);

  // update content pages table
  $sql = "ALTER TABLE `content_pages` CHANGE `pageid` `pageid` INT( 11 ) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the page.',
          CHANGE `menu_index` `menu_index` INT( 11 ) NOT NULL COMMENT 'The index in the main menu.';";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `content_pages` ADD UNIQUE (`title`);";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `content_pages` ADD UNIQUE (`menu_name`);";
  mysqli_query($db, $sql);

  // update environments pages table
  $sql = "ALTER TABLE `environments` CHANGE `envid` `envid` INT( 11 ) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the environment.',
          CHANGE `enabled` `enabled` BOOLEAN NOT NULL COMMENT 'If this environment is currently enabled.';";
  mysqli_query($db, $sql);

  // update environment interfaces table
  $sql = "ALTER TABLE `environment_interfaces` ADD INDEX ( `envid` ) ;";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `environment_interfaces` ADD INDEX ( `intid` ) ;";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `environment_interfaces` ADD FOREIGN KEY ( `envid` ) REFERENCES `environments` (`envid`) ON DELETE CASCADE ON UPDATE CASCADE ;";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `environment_interfaces` ADD FOREIGN KEY ( `intid` ) REFERENCES `interfaces` (`intid`) ON DELETE CASCADE ON UPDATE CASCADE ;";
  mysqli_query($db, $sql);

  // update experiments table
  $sql = "ALTER TABLE `experiments` ADD UNIQUE (`envid` ,`start` ,`end`);";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `experiments` ADD INDEX ( `envid` ) ;";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `experiments` ADD INDEX ( `userid` ) ;";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `experiments` ADD INDEX ( `condid` ) ;";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `experiments` ADD FOREIGN KEY (`userid`) REFERENCES `user_accounts`(`userid`) ON DELETE CASCADE ON UPDATE CASCADE;";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `experiments` ADD FOREIGN KEY (`condid`) REFERENCES `conditions`(`condid`) ON DELETE CASCADE ON UPDATE CASCADE;";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `experiments` ADD FOREIGN KEY (`envid`) REFERENCES `environments`(`envid`) ON DELETE CASCADE ON UPDATE CASCADE;";
  mysqli_query($db, $sql);

  //update javascript files table
  $sql = "ALTER TABLE javascript_files DROP INDEX url;";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `javascript_files` ADD UNIQUE( `url`);";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `javascript_files` ADD UNIQUE( `path`);";
  mysqli_query($db, $sql);

  //update keyboard teleop files table
  $sql = "ALTER TABLE `keyboard_teleop` CHANGE `envid` `envid` INT(11) NULL DEFAULT NULL";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `keyboard_teleop` CHANGE `envid` `envid` INT(11) NOT NULL";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `keyboard_teleop` ADD INDEX( `envid`);";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `keyboard_teleop` ADD UNIQUE( `envid`, `label`, `throttle`, `twist`);";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `keyboard_teleop` ADD FOREIGN KEY (`envid`) REFERENCES `environments`(`envid`) ON DELETE CASCADE ON UPDATE CASCADE;";
  mysqli_query($db, $sql);

  // update maps table
  $sql = "ALTER TABLE `maps` ADD UNIQUE( `envid`, `label`, `topic`, `continuous`);";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `maps` ADD INDEX( `envid`);";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `maps` ADD FOREIGN KEY (`envid`) REFERENCES `environments`(`envid`) ON DELETE CASCADE ON UPDATE CASCADE;";
  mysqli_query($db, $sql);

  // update mjpeg server streams
  $sql = "ALTER TABLE `mjpeg_server_streams` CHANGE `envid` `envid` TINYTEXT NOT NULL, CHANGE `label` `label` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `mjpeg_server_streams` CHANGE `envid` `envid` INT(11) NOT NULL COMMENT 'Unique identifier for the environment.'";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `mjpeg_server_streams` ADD FOREIGN KEY (`envid`) REFERENCES `environments`(`envid`) ON DELETE CASCADE ON UPDATE CASCADE;";
  mysqli_query($db, $sql);

  // update navigations table
  $sql = "ALTER TABLE `navigations` ADD INDEX( `envid`);";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `navigations` ADD INDEX( `mapid`);";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `navigations` ADD FOREIGN KEY (`envid`) REFERENCES `environments`(`envid`) ON DELETE CASCADE ON UPDATE CASCADE;";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `navigations` ADD FOREIGN KEY (`mapid`) REFERENCES `maps`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
  mysqli_query($db, $sql);

  // update slideshow table
  $sql = "ALTER TABLE `slideshow` ADD UNIQUE( `img`);";
  mysqli_query($db, $sql);

  // update study log table
  $sql = "ALTER TABLE `study_log` CHANGE `logid` `logid` INT(32) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the log entry.';";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `study_log` ADD INDEX( `expid`);";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `study_log` ADD FOREIGN KEY (`expid`) REFERENCES `experiments`(`expid`) ON DELETE CASCADE ON UPDATE CASCADE;";
  mysqli_query($db, $sql);

  // update user accounts table
  $sql = "ALTER TABLE `user_accounts` CHANGE `userid` `userid` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the user.'";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `user_accounts` ADD UNIQUE( `username`);";
  mysqli_query($db, $sql);
  $sql = "ALTER TABLE `user_accounts` ADD UNIQUE( `email`);";
  mysqli_query($db, $sql);

  // change the version number
  $sql = "UPDATE version SET version = '0.1.1' WHERE version = '0.1.0'";
  //mysqli_query($db, $sql);


}
?>

