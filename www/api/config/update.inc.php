<?php
/**
 * Update functions for the RMS API.
 *
 * The update include script has functions for updating the RMS database. These should only be used
 * through the run_database_update function inside of config.inc.php.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    February, 26 2013
 * @package    api.config
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../inc/config.inc.php');

/**
 * Update the RMS database from version 2.0.1 to version 2.1.11.
 *
 * @return string|null an error message or null if the update was sucessful
 */
function update_0_2_1() {
  global $db;
  
  // add interactivemarkersjs
  $sql = "
          INSERT INTO `javascript_files` (`url`, `path`) VALUES
            ('https://raw.github.com/RobotWebTools/interactivemarkersjs/groovy-devel/improxy.js', 
             'js/ros/widgets/improxy.js'),
            ('https://raw.github.com/RobotWebTools/interactivemarkersjs/groovy-devel/imthree.js', 
             'js/ros/widgets/imthree.js'),
            ('https://raw.github.com/RobotWebTools/interactivemarkersjs/groovy-devel/markersthree.js', 
             'js/ros/widgets/markersthree.js'),
            ('https://raw.github.com/RobotWebTools/interactivemarkersjs/groovy-devel/tfclient.js', 
             'js/ros/widgets/tfclient.js'),
            ('https://raw.github.com/RobotWebTools/interactivemarkersjs/groovy-devel/threeinteraction.js', 
             'js/ros/widgets/threeinteraction.js')
         ";
  // try the update
  if(!mysqli_query($db, $sql)) {
    return mysqli_error($db);
  }
  
  // create the IM table
  $sql = "
          CREATE TABLE IF NOT EXISTS `interactive_markers` (
            `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the interactive marker.',
            `envid` int(11) NOT NULL COMMENT 'The environment this widget belongs to.',
            `label` varchar(255) NOT NULL COMMENT 'A label for this widget.',
            `topic` varchar(255) NOT NULL COMMENT 'The interactive marker topic to listen to.',
            `fixed_frame` varchar(255) NOT NULL COMMENT 'The fixed frame for the TF tree.',
            PRIMARY KEY (`id`),
            KEY `envid` (`envid`)
          )
          ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='The interactive marker widget.' AUTO_INCREMENT=1;
         ";
  // try the update
  if(!mysqli_query($db, $sql)) {
    return mysqli_error($db);
  }
  
  // add constraints
  $sql = "
          ALTER TABLE `interactive_markers` ADD CONSTRAINT `interactive_markers_ibfk_1` 
          FOREIGN KEY (`envid`) REFERENCES `environments` (`envid`) ON DELETE CASCADE ON UPDATE CASCADE;
         ";
  // try the update
  if(!mysqli_query($db, $sql)) {
    return mysqli_error($db);
  }
  
  // "install" the table
  $sql = "
          INSERT INTO `widgets` (`name`, `table`, `script`)
          VALUES
          ('Interactive Markers', 'interactive_markers', 'interactive_markers')
         ";
  // try the update
  if(!mysqli_query($db, $sql)) {
    return mysqli_error($db);
  }

  // update the database version
  if(!mysqli_query($db, "UPDATE `version` SET `version`='0.2.11' WHERE `version`='0.2.1'")) {
    return mysqli_error($db);
  } else {
    return null;
  }
}

/**
 * Update the RMS database from version 2.0.0 to version 2.1.0.
 *
 * @return string|null an error message or null if the update was sucessful
 */
function update_0_2_0() {
  global $db;

  // upgrade the JS files to groovy-devel
  $query = mysqli_query($db, "SELECT * FROM `javascript_files` WHERE `url` like '%fuerte-devel%'");
  while($cur = mysqli_fetch_assoc($query)) {
    $new_url = str_replace('fuerte-devel', 'groovy-devel', $cur['url']);
    $sql = sprintf("UPDATE `javascript_files` SET `url`='%s' WHERE `fileid`='%d'"
    , cleanse($new_url), cleanse($cur['fileid']));
    // try and do the update
    if(!mysqli_query($db, $sql)) {
      return mysqli_error($db);
    }
  }

  // update the database version
  if(!mysqli_query($db, "UPDATE `version` SET `version`='0.2.1' WHERE `version`='0.2.0'")) {
    return mysqli_error($db);
  } else {
    return null;
  }
}
?>
