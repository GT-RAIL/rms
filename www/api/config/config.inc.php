<?php
/**
 * Configuration include functions for the RMS API.
 *
 * Allows read and write access to system settings and configuration files. Used throughout RMS and
 * within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 14 2012
 * @package    api.config
 * @link       http://ros.org/wiki/rms
 */

$INIT_SQL_FILE = dirname(__FILE__).'/init.sql';

/**
 * Check if the given array has all of the necessary fields to create a config file.
 *
 * @param array $array The array to check
 * @return boolean If the given array has all of the necessary fields to create a config file
 */
function valid_config_fields($array) {
  return isset($array['host']) && isset($array['db']) && isset($array['dbuser'])
  && isset($array['dbpass']) && isset($array['site-name']) && isset($array['copyright'])
  && isset($_POST['google']);
}

/**
 * Upload the multi-statement SQL file to the given database.
 *
 * @param string $host The hostname of the MySQL server
 * @param string $user The username for the MySQL server
 * @param string $pass The password for the MySQL server
 * @param string $name The database name to use
 * @param string $fname The file name to upload
 * @return string|boolean A string containing an error message or false if there were no errors
 */
function upload_database($host, $user, $pass, $name, $fname) {
  // grab the file
  if(!file_exists($fname) || !($sql = file_get_contents($fname))) {
    return 'Could not load SQL file "'.$fname.'".';
  }
  // connect to the database
  if(!($db = @mysqli_connect($host, $user, $pass, $name))) {
    return 'Could not create a connection to the MySQL server.';
  }

  // make the query
  mysqli_multi_query($db, $sql);
  // wait for everything to finish
  do {
    if($r = mysqli_store_result($db)){
      mysqli_free_result($r);
    }
  } while(mysqli_next_result($db));

  // no error
  return false;
}

/**
 * Write the configuration file with the given information into 'inc/'.
 *
 * @param unknown_type $dbhost The hostname of the MySQL server
 * @param unknown_type $dbuser The username for the MySQL server
 * @param unknown_type $dbpass The password for the MySQL server
 * @param unknown_type $dbname The database name to use
 * @param unknown_type $title The title of the website
 * @param string $google The Google Analytics ID number or the empty string if there is none
 * @param unknown_type $copyright The copyright message to use
 * @return string|boolean A string containing an error message or false if there were no errors
 */
function create_config_inc($dbhost, $dbuser, $dbpass, $dbname, $title, $google, $copyright) {
  // create the file
  if(!$f = @fopen(dirname(__FILE__).'/../../inc/config.inc.php', 'w')) {
    return 'Could not create config inside of folder "inc". Check folder permissions and try again.';
  }

  // check the google tracking ID
  $google_tracking_id = '$google_tracking_id = ';
  if($google === '') {
    $google_tracking_id .= 'null;';
  } else {
    $google_tracking_id .= '\''.$google.'\';';
  }

  // put in the header
  $today = getdate();
  fwrite($f, '
<?php
/**
  * RMS Site Settings and Configuration
  *
  * Contains the site settings and configurations for the RMS. This file is auto-generated and
  * should not be edited by hand.
  *
  * @author     Auto Generated via Setup Sipt
  * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
  * @license    BSD -- see LICENSE file
  * @version    '.$today['month'].' '.$today['mday'].', '.$today['year'].'
  * @package    inc
  * @link       http://ros.org/wiki/rms
  */

// database information
$dbhost = \''.$dbhost.'\';
$dbuser = \''.$dbuser.'\';
$dbpass = \''.$dbpass.'\';
$dbname = \''.$dbname.'\';
$db = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
or DIE(\'Connection has failed. Please try again later.\');

// Google Analytics tracking ID -- unset if no tracking is being used.
'.$google_tracking_id.'

// site copyright and design information
$copyright = \'&copy '.$copyright.'\';
$title = \''.$title.'\';
// original site design information
$designed_by = \'Site design by <a href="http://users.wpi.edu/~rctoris/">Russell Toris</a>\';
?>
');

  // close the file
  fclose($f);

  // everything went fine, no errors
  return false;
}
?>
