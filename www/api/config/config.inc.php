<?php
/**
 * Configuration include functions for the RMS API.
 *
 * Allows read and write access to system settings and configuration files. Used throughout RMS and
 * within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 6 2012
 * @package    api.config
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../api.inc.php');

/**
 * The complete file name/path for the init SQL file.
 * @var string
 */
$INIT_SQL_FILE = dirname(__FILE__).'/init.sql';

/**
 * Gets the version number of the RMS database.
 *
 * @return string the version number of the RMS database
 */
function get_db_version() {
  global $db;

  $query = mysqli_query($db, "SELECT `version` FROM `version`");
  $version = mysqli_fetch_array($query);

  return $version['version'];
}

/**
 * Parse the init.sql file at the given URL and return the version number.
 *
 * @param string $url the URL to the init.sql file
 * @return string the version number in the init.sql file
 */
function get_init_sql_version($url) {
  // setup CURL to grab the file
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $data = curl_exec($curl);
  curl_close($curl);

  // read the file line by line until we find the version
  $lines = array();
  $lines = explode("\n", $data);
  $len = count($lines);
  for ($i = 0; $i < $len; $i++) {
    if($lines[$i] === "INSERT INTO `version` (`version`) VALUES") {
      // break out the version number
      $v = substr($lines[$i+1], strpos($lines[$i+1], "'")+1);
      $v = substr($v, 0, strpos($v, "'"));
      return $v;
    }
  }
}

/**
 * Check if the given array has all of the necessary fields to create a config file.
 *
 * @param array $array The array to check
 * @return boolean If the given array has all of the necessary fields to create a config file
 */
function valid_config_fields($array) {
  return isset($array['host']) && isset($array['db']) && isset($array['dbuser'])
  && isset($array['dbpass']) && isset($array['site-name']) && isset($array['copyright'])
  && isset($array['google']) && (count($array) === 7);
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
  * @author     Auto Generated via Setup Script
  * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
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
$copyright = \'&copy '.addslashes($copyright).'\';
$title = \''.addslashes($title).'\';
// original site design information
$designed_by = \'Site design by <a href="http://users.wpi.edu/~rctoris/">Russell Toris</a>\';
?>
');

  // close the file
  fclose($f);

  // everything went fine, no errors
  return false;
}

/**
 * Update the RMS database to the latest code version. Any errors are returned.
 *
 * @return string|null an error message or null if the update was sucessful
 */
function run_database_update() {
  // get the code version
  $prot = (isset($_SERVER['HTTPS'])) ? 'https://' : 'http://';
  $code_version = get_init_sql_version($prot.$_SERVER['HTTP_HOST'].'/api/config/init.sql');


  if(get_db_version() < '0.2.0') {
    return 'ERROR: Version '.$code_version.' is not backwards compatible with version '.get_db_version().'.';
  }

  // loop through until we are up to date
  while(get_db_version() < $code_version) {
    // build the function name
    $function  = 'update_'.get_db_version();
    $function= str_replace('.', '_', $function);
    if($error = $function()) {
      return $error;
    }
  }

  // no errors
  return null;
}

/**
 * Update the site settings with the given information inside of the array. Any errors are returned.
 *
 * @param array $fields The fields to update
 * @return string|null an error message or null if the update was sucessful
 */
function update_site_settings($fields) {
  global $dbhost, $dbuser, $dbpass, $dbname, $title, $google_tracking_id, $copyright;

  // check the fields
  $num_fields = 0;
  if(isset($fields['host'])) {
    $num_fields++;
    $new_dbhost = $fields['host'];
  } else {
    $new_dbhost = $dbhost;
  }
  if(isset($fields['db'])) {
    $num_fields++;
    $new_dbname = $fields['db'];
  } else {
    $new_dbname = $dbname;
  }
  if(isset($fields['dbuser'])) {
    $num_fields++;
    $new_dbuser = $fields['dbuser'];
  } else {
    $new_dbuser = $dbuser;
  }
  if(isset($fields['password'])) {
    $num_fields++;
    $new_dbpass = $fields['password'];
  } else {
    $new_dbpass = $dbpass;
  }
  if(isset($fields['site-name'])) {
    $num_fields++;
    $new_title = $fields['site-name'];
  } else {
    $new_title = $title;
  }
  if(isset($fields['google'])) {
    $num_fields++;
    $new_google = $fields['google'];
  } else {
    $new_google = $google_tracking_id;
  }
  if(isset($fields['copyright'])) {
    $num_fields++;
    $new_copyright= $fields['copyright'];
  } else {
    $new_copyright = $copyright;
  }

  // check to see if there were too many fields or if we do not need to update
  if($num_fields !== count($fields)) {
    return 'ERROR: Too many fields given.';
  } else if ($num_fields === 0) {
    // nothing to update
    return null;
  }

  // cleanup the old file
  if(file_exists(dirname(__FILE__).'/../../inc/config.inc.php')) {
    unlink(dirname(__FILE__).'/../../inc/config.inc.php');
  }
  // we can now run the update
  if($error =  create_config_inc($new_dbhost, $new_dbuser, $new_dbpass, $new_dbname, $new_title, $new_google, $new_copyright)) {
    return $error;
  } else {
    // no error
    return null;
  }
}

/**
 * Get the HTML for an editor used to edit site wide settings.
 *
 * @return string A string containing the HTML of the editor
 */
function get_site_settings_editor_html() {
  global $dbhost, $dbuser, $dbpass, $dbname, $title, $google_tracking_id, $copyright, $PASSWORD_HOLDER;

  $result = '<p>Complete the following form to edit the site settings.</p>
             <form action="javascript:submit();"><fieldset>
               <h3>MySQL Database Information</h3>
               <ol>
                 <li>
                  <label for="host">Database Host</label>
                  <input type="text" name="host" id="host" value="'.$dbhost.'"
                   placeholder="e.g., localhost" required />
                </li>
                <li>
                  <label for="db">Database Name</label>
                  <input type="text" name="db" id="db" value="'.$dbname.'"
                   placeholder="e.g., my_remote_lab" required />
                </li>
                <li>
                  <label for="dbuser">Database Username</label>
                  <input type="text" name="dbuser" id="dbuser" value="'.$dbuser.'"
                   placeholder="username" required />
              </li>
              <li>
                <label for="password">Database Password</label>
                <input type="password" name="password" id="password" value="'.$PASSWORD_HOLDER.'"
                 placeholder="Password" required />
                <label for="password-confirm">Confirm Database Password</label>
                <input type="password" name="password-confirm" id="password-confirm"
                 value="'.$PASSWORD_HOLDER.'" placeholder="Confirm Password" required />
              </li>
            </ol>
          </fieldset>
          <fieldset>
            <br />
            <h3>Site Information</h3>
            <ol>
              <li>
                <label for="site-name">Site Name</label>
                <input type="text" name="site-name" id="site-name" value="'.$title.'"
                 placeholder="e.g., My Awesome Title" required />
              </li>
              <li>
                <label for="google">Google Analytics (optional)</label>
                <input type="text" name="google" id="google" value="'.$google_tracking_id.'"
                 placeholder="(optional)" />
              </li>
              <li><label for="copyright">Copyright Message</label>
              <input type="text" name="copyright" id="copyright" value="'.substr($copyright, 6).'"
               placeholder="e.g., 2012 My Robot College" required />
              </li>
            </ol>
          </fieldset>
        <input type="submit" value="Submit" />
      </form>';

  return $result;
}
?>
