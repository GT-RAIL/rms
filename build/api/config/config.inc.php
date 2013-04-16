<?php
/**
 * Configuration include static functions for the RMS API.
 *
 * Allows read and write access to system settings and configuration files. Used
 * throughout RMS and within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 11 2013
 * @package    api.config
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../api.inc.php');
// only include update if we have a config file already
if (file_exists(dirname(__FILE__).'/../../inc/config.inc.php')) {
    include_once(dirname(__FILE__).'/update.inc.php');
}

/**
 * The complete file name/path for the init SQL file.
 * @var string
 */
$initSqlFile = dirname(__FILE__).'/init.sql';

/**
 * A static class to contain the config.inc.php static functions.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 11 2013
 * @package    api.config
 */
class config
{
    /**
     * Gets the version number of the RMS database.
     *
     * @return string the version number of the RMS database
     */
    static function get_db_version()
    {
        global $db;
    
        $query = mysqli_query($db, "SELECT `version` FROM `version`");
        $version = mysqli_fetch_array($query);
    
        return $version['version'];
    }
    
    /**
     * Parse the init.sql file at the given URL and return the version number.
     *
     * @param string $url the URL to the init.sql file
     * @param boolean $local if this is a local file (default = false)
     * @return string the version number in the init.sql file
     */
    static function get_init_sql_version($url, $local = false)
    {
        if ($local) {
            // get the local file
            $f = fopen($url, "r");
            $data = fread($f, filesize($url));
            fclose($f);
        } else {
            // setup CURL to grab the file
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
            $data = curl_exec($curl);
            curl_close($curl);
        }
    
        // read the file line by line until we find the version
        $lines = array();
        $lines = explode("\n", $data);
        $len = count($lines);
        for ($i = 0; $i < $len; $i++) {
            if ($lines[$i] === "INSERT INTO `version` (`version`) VALUES") {
                // break out the version number
                $v = substr($lines[$i+1], strpos($lines[$i+1], "'")+1);
                $v = substr($v, 0, strpos($v, "'"));
                return $v;
            }
        }
        
        return 'unknown';
    }
    
    /**
     * Check if the given array has all of the necessary fields to create a
     * config file.
     *
     * @param array $array The array to check
     * @return boolean If the given array has all of the necessary fields to
     *     create a config file
     */
    static function valid_config_fields($array)
    {
        return isset($array['host']) && isset($array['db'])
        && isset($array['dbuser']) && isset($array['dbpass'])
        && isset($array['site-name']) && isset($array['copyright'])
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
     * @return string|boolean A string containing an error message or false if
     *     there were no errors
     */
    static function upload_database($host, $user, $pass, $name, $fname)
    {
        // grab the file
        if (!file_exists($fname) || !($sql = file_get_contents($fname))) {
            return 'Could not load SQL file "'.$fname.'".';
        }
        // connect to the database
        if (!($db = @mysqli_connect($host, $user, $pass, $name))) {
            return 'Could not create a connection to the MySQL server.';
        }
    
        // make the query
        mysqli_multi_query($db, $sql);
        // wait for everything to finish
        do {
            if ($r = mysqli_store_result($db)) {
                mysqli_free_result($r);
            }
        } while (mysqli_next_result($db));
    
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
     * @param string $google The Google Analytics ID number or the empty string
     *     if there is none
     * @param unknown_type $copyright The copyright message to use
     * @return string|boolean A string containing an error message or false if
     *     there were no errors
     */
    static function create_config_inc($dbhost, $dbuser, $dbpass, $dbname, 
            $title, $google, $copyright)
    {
        // create the file
        if (!$f = @fopen(dirname(__FILE__).'/../../inc/config.inc.php', 'w')) {
            return 'Could not create config inside of folder "inc". '.
                'Check folder permissions and try again.';
        }
    
        // check the google tracking ID
        $googleTrackingID = '$googleTrackingID = ';
        if ($google === '') {
            $googleTrackingID .= 'null;';
        } else {
            $googleTrackingID .= '\''.$google.'\';';
        }
    
        // put in the header
        $today = getdate();
        $toWrite = '
<?php
/**
 * RMS Site Settings and Configuration
 *
 * Contains the site settings and configurations for the RMS. This file is
 * auto-generated and should not be edited by hand.
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
'.$googleTrackingID.'

// site copyright and design information
$copyright = \'&copy '.addslashes($copyright).'\';
$title = \''.addslashes($title).'\';
// original site design information
$designedBy = \'Site design by
    <a href="http://users.wpi.edu/~rctoris/">Russell Toris</a>\';
';
        fwrite($f, $toWrite);
    
        // close the file
        fclose($f);
    
        // everything went fine, no errors
        return false;
    }
    
    /**
     * Update the RMS database to the latest code version. Any errors are
     * returned.
     *
     * @return string|null an error message or null if the update was sucessful
     */
    static function run_database_update()
    {
        // get the code version
        $prot = (isset($_SERVER['HTTPS'])) ? 'https://' : 'http://';
        $url = $prot.$_SERVER['HTTP_HOST'].'/api/config/init.sql';
        $codeVersion = config::get_init_sql_version($url);

        if (config::get_db_version() < '0.2.0') {
            return 'ERROR: Version '.$codeVersion.
                ' is not backwards compatible with version '.
                config::get_db_version().'.';
        }
    
        // loop through until we are up to date
        while (config::get_db_version() < $codeVersion) {
            // build the static function name
            $function = 'update::update_'.config::get_db_version();
            $function = str_replace('.', '_', $function);
            if ($error = call_user_func($function)) {
                return $error;
            }
        }
    
        // no errors
        return null;
    }
    
    /**
     * Update the site settings with the given information inside of the array.
     * Any errors are returned.
     *
     * @param array $fields The fields to update
     * @return string|null an error message or null if the update was sucessful
     */
    static function update_site_settings($fields)
    {
        global $dbhost, $dbuser, $dbpass, $dbname, $title, $googleTrackingID, 
            $copyright;
                 
    
        // check the fields
        $numFields = 0;
        if (isset($fields['host'])) {
            $numFields++;
            $newDBHost = $fields['host'];
        } else {
            $newDBHost = $dbhost;
        }
        if (isset($fields['db'])) {
            $numFields++;
            $newDBName = $fields['db'];
        } else {
            $newDBName = $dbname;
        }
        if (isset($fields['dbuser'])) {
            $numFields++;
            $newDBUser = $fields['dbuser'];
        } else {
            $newDBUser = $dbuser;
        }
        if (isset($fields['password'])) {
            $numFields++;
            $newDBPass = $fields['password'];
        } else {
            $newDBPass = $dbpass;
        }
        if (isset($fields['site-name'])) {
            $numFields++;
            $newTitle = $fields['site-name'];
        } else {
            $newTitle = $title;
        }
        if (isset($fields['google'])) {
            $numFields++;
            $newGoogle = $fields['google'];
        } else {
            $newGoogle = $googleTrackingID;
        }
        if (isset($fields['copyright'])) {
            $numFields++;
            $newCopyright= $fields['copyright'];
        } else {
            $newCopyright = $copyright;
        }
    
        // check to see if there were too many fields
        if ($numFields !== count($fields)) {
            return 'ERROR: Too many fields given.';
        } else if ($numFields === 0) {
            // nothing to update
            return null;
        }
    
        // cleanup the old file
        if (file_exists(dirname(__FILE__).'/../../inc/config.inc.php')) {
            unlink(dirname(__FILE__).'/../../inc/config.inc.php');
        }
        // we can now run the update
        if ($error = config::create_config_inc(
            $newDBHost, $newDBUser, $newDBPass, $newDBName, $newTitle, 
            $newGoogle, $newCopyright
        )) {
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
    static function get_site_settings_editor()
    {
        global $dbhost, $dbuser, $dbpass, $dbname, $title, $googleTrackingID,
            $copyright;
    
        $result = '
<p>Complete the following form to edit the site settings.</p>
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
                <input type="password" name="password" id="password" value="'.
                    api::$passwordHolder.'"
                 placeholder="Password" required />
                <label for="password-confirm">Confirm Database Password</label>
                <input type="password" name="password-confirm" 
                 id="password-confirm" value="'.api::$passwordHolder.
                 '" placeholder="Confirm Password" required />
            </li>
        </ol>
        </fieldset>
        <fieldset>
            <br />
            <h3>Site Information</h3>
            <ol>
                <li>
                    <label for="site-name">Site Name</label>
                    <input type="text" name="site-name" id="site-name" 
                     value="'.$title.'" placeholder="e.g., My Awesome Title"
                     required />
                </li>
                <li>
                    <label for="google">Google Analytics (optional)</label>
                    <input type="text" name="google" id="google" value="'.
                     $googleTrackingID.'" placeholder="(optional)" />
                </li>
                <li><label for="copyright">Copyright Message</label>
                <input type="text" name="copyright" id="copyright" value="'.
                 substr($copyright, 6).'" 
                 placeholder="e.g., 2012 My Robot College" required />
                </li>
            </ol>
        </fieldset>
    <input type="submit" value="Submit" />
</form>';
    
        return $result;
    }
}
