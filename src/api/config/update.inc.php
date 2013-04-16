<?php
/**
 * Update static functions for the RMS API.
 *
 * The update include script has static functions for updating the RMS database.
 * These should only be used through the config::run_database_update static
 * function inside of config.inc.php.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 15 2013
 * @package    api.config
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../inc/config.inc.php');
include_once(dirname(__FILE__).
        '/../robot_environments/environments/environments.inc.php');

/**
 * A static class to contain the update.inc.php static functions.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 15 2013
 * @package    api.config
 */
class update
{
    /**
     * Update the RMS database from version 0.2.12 to version 0.3.0.
     *
     * @return string|null an error message or null if the update was sucessful
     */
    static function update_0_2_12()
    {
        global $db;
    
        // drop the JavaScript table
        $sql = "DROP TABLE `javascript_files`";
        // try the update
        if (!mysqli_query($db, $sql)) {
            return mysqli_error($db);
        }
        
        // drop the scripts column
        $sql = "ALTER TABLE `widgets` DROP COLUMN `script`";
        // try the update
        if (!mysqli_query($db, $sql)) {
            return mysqli_error($db);
        }
        
        // remove type and notes from environment
        $sql = "ALTER TABLE `environments` DROP `type`, DROP `notes`";
        // try the update
        if (!mysqli_query($db, $sql)) {
            return mysqli_error($db);
        }
        
        // add MJPEG server info
        $sql = "ALTER TABLE `environments` 
                ADD `mjpeg` VARCHAR( 255 ) NOT NULL COMMENT
                    'The MJPEG server host address.' AFTER `port`,
                ADD `mjpegport` INT( 11 ) NOT NULL COMMENT 
                    'The MJPEG server port.' AFTER `mjpeg`";
        // try the update
        if (!mysqli_query($db, $sql)) {
            return mysqli_error($db);
        }
        
        // add default information
        $environments = environments::get_environments();
        foreach ($environments as $env) {
            $sql = sprintf(
                "UPDATE `environments` SET `mjpeg`='%s', mjpegport='8080'
                 WHERE `envid`='%d'",
                api::cleanse($env['envaddr']), api::cleanse($env['envid'])
            );
            // try the update
            if (!mysqli_query($db, $sql)) {
                return mysqli_error($db);
            }
        }

        // update the database version
        $sql = "UPDATE `version` SET `version`='0.3.0' 
                WHERE `version`='0.2.12'";
        if (!mysqli_query($db, $sql)) {
            return mysqli_error($db);
        } else {
            return null;
        }
    }
    
    /**
     * Update the RMS database from version 0.2.11 to version 0.2.12.
     *
     * @return string|null an error message or null if the update was sucessful
     */
    static function update_0_2_11()
    {
        global $db;
    
        // add interactivemarkersjs
        $sql = "
                INSERT INTO `javascript_files` (`url`, `path`) VALUES
                ('https://raw.github.com/RobotWebTools/'.
                'rosbagjs/groovy-devel/topiclogger.js',
                'js/ros/widgets/topiclogger.js')
                ";
        // try the update
        if (!mysqli_query($db, $sql)) {
            return mysqli_error($db);
        }
    
        // update the database version
        $sql = "UPDATE `version` SET `version`='0.2.12' 
                WHERE `version`='0.2.11'";
        if (!mysqli_query($db, $sql)) {
            return mysqli_error($db);
        } else {
            return null;
        }
    }
    
    /**
     * Update the RMS database from version 0.2.1 to version 0.2.11.
     *
     * @return string|null an error message or null if the update was sucessful
     */
    static function update_0_2_1()
    {
        global $db;
    
        // add interactivemarkersjs
        $base = 'https://raw.github.com/RobotWebTools/interactivemarkersjs/';
        $sql = "
                INSERT INTO `javascript_files` (`url`, `path`) VALUES
                ('".$base."/groovy-devel/tfclient.js',
                'js/ros/widgets/tfclient.js'),
                ('".$base."groovy-devel/markersthree.js',
                'js/ros/widgets/markersthree.js'),
                ('".$base."groovy-devel/imthree.js',
                'js/ros/widgets/imthree.js'),
                ('".$base."groovy-devel/improxy.js',
                'js/ros/widgets/improxy.js'),
                ('".$base."groovy-devel/threeinteraction.js',
                'js/ros/widgets/threeinteraction.js'),
                ('".$base."groovy-devel/examples/include/helpers/".
                "RosAxisHelper.js',
                'js/ros/RosAxisHelper.js'),
                ('".$base."groovy-devel/examples/include/helpers/".
                "RosOrbitControls.js',
                'js/ros/RosOrbitControls.js')
                ";
        // try the update
        if (!mysqli_query($db, $sql)) {
            return mysqli_error($db);
        }
    
        // create the IM table
        $sql = "
                CREATE TABLE IF NOT EXISTS `interactive_markers` (
                `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 
                    'Unique identifier for the interactive marker.',
                `envid` int(11) NOT NULL COMMENT 
                    'The environment this widget belongs to.',
                `label` varchar(255) NOT NULL COMMENT 
                    'A label for this widget.',
                `topic` varchar(255) NOT NULL COMMENT 
                    'The interactive marker topic to listen to.',
                `fixed_frame` varchar(255) NOT NULL COMMENT 
                    'The fixed frame for the TF tree.',
                PRIMARY KEY (`id`),
                KEY `envid` (`envid`)
                )
                ENGINE=InnoDB DEFAULT CHARSET=latin1 
                COMMENT='The interactive marker widget.' AUTO_INCREMENT=1;
                ";
        // try the update
        if (!mysqli_query($db, $sql)) {
            return mysqli_error($db);
        }
    
        // add constraints
        $sql = "
                ALTER TABLE `interactive_markers` ADD CONSTRAINT 
                    `interactive_markers_ibfk_1`
                FOREIGN KEY (`envid`) REFERENCES `environments` (`envid`) 
                    ON DELETE CASCADE ON UPDATE CASCADE;
                ";
        // try the update
        if (!mysqli_query($db, $sql)) {
            return mysqli_error($db);
        }
    
        // "install" the table
        $sql = "
                INSERT INTO `widgets` (`name`, `table`, `script`)
                VALUES
                ('Interactive Markers', 
                 'interactive_markers', 
                 'interactive_markers')
                ";
        // try the update
        if (!mysqli_query($db, $sql)) {
            return mysqli_error($db);
        }
    
        // "install" the example interface
        $sql = "INSERT INTO `interfaces` (`name`, `location`) VALUES 
                ('Interactive Markers', 'markers')";
        // try the update
        if (!mysqli_query($db, $sql)) {
            return mysqli_error($db);
        }
    
        // update the database version
        $sql = "UPDATE `version` SET `version`='0.2.11' 
                WHERE `version`='0.2.1'";
        if (!mysqli_query($db, $sql)) {
            return mysqli_error($db);
        } else {
            return null;
        }
    }
    
    /**
     * Update the RMS database from version 0.2.0 to version 0.2.1.
     *
     * @return string|null an error message or null if the update was sucessful
     */
    static function update_0_2_0()
    {
        global $db;
    
        // upgrade the JS files to groovy-devel
        $sql = "SELECT * FROM `javascript_files` ".
               "WHERE `url` like '%fuerte-devel%'";
        $query = mysqli_query($db, $sql);
        while ($cur = mysqli_fetch_assoc($query)) {
            $newUrl = str_replace('fuerte-devel', 'groovy-devel', $cur['url']);
            $s = "UPDATE `javascript_files` SET `url`='%s' WHERE `fileid`='%d'";
            $id = $cur['fileid'];
            $sql = sprintf($s, api::cleanse($newUrl), api::cleanse($id));
            // try and do the update
            if (!mysqli_query($db, $sql)) {
                return mysqli_error($db);
            }
        }
    
        // update the database version
        $sql = "UPDATE `version` SET `version`='0.2.1' WHERE `version`='0.2.0'";
        if (!mysqli_query($db, $sql)) {
            return mysqli_error($db);
        } else {
            return null;
        }
    }
}
