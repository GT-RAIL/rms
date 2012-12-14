<?php
/**
 * Configration script for the RMS API.
 *
 * Allows read and write access to site settings and configuration. Proper authentication is
 * required. Alternatively, if the 'inc/confic.inc.php' file is missing, no authentication is
 * required and only the default POST request will be accepted. This allows for initial site
 * setup.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 6 2012
 * @package    api.config
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../api.inc.php');
include_once(dirname(__FILE__).'/config.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check if this is initial site setup
if(!file_exists(dirname(__FILE__).'/../../inc/config.inc.php')) {
  // we only take POST requests at this point
  if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // check the config fields
    if(valid_config_fields($_POST)) {
      // check if a file was uploaded
      $error = false;
      if(isset($_FILES['sqlfile'])) {
        // check for an error
        if($_FILES['sqlfile']['error'] !== 0 && $_FILES['sqlfile']['error'] !== 4) {
          $error = 'PHP file upload returned with error code '.$_FILES['sqlfile']['error'].'.';
        } else {
          // if a blank file name was given, we just use the init SQL file
          $sqlfile = ($_FILES['sqlfile']['tmp_name'] === '') ? $INIT_SQL_FILE
          : $_FILES['sqlfile']['tmp_name'];
        }
      } else {
        $sqlfile = $INIT_SQL_FILE;
      }

      // try to upload the database
      if($error || $error = upload_database($_POST['host'], $_POST['dbuser'], $_POST['dbpass']
      , $_POST['db'], $sqlfile)) {
        $result = create_404_state($error);
      } else {
        // now create the config file
        if($error = create_config_inc($_POST['host'], $_POST['dbuser'], $_POST['dbpass']
        , $_POST['db'], $_POST['site-name'], $_POST['google'], $_POST['copyright'])) {
          $result = create_404_state($error);
        } else {
          // now delete any old Javascript files and download the new ones
          include_once(dirname(__FILE__).'/javascript_files/javascript_files.inc.php');
          if($error = delete_local_javascript_files() || $error = download_javascript_files()) {
            $result = create_404_state($error);
          } else {
            include_once(dirname(__FILE__).'/logs/logs.inc.php');
            write_to_log('SYSTEM: Site created.');
            // return the timestamp
            $result = create_200_state(get_current_timestamp());
          }
        }
      }
    } else {
      $result = create_404_state('Incomplete list of required fields.');
    }
  } else {
    $result = create_404_state($_SERVER['REQUEST_METHOD'].' method is unavailable.');
  }
} else {
  // load the normal include files
  include_once(dirname(__FILE__).'/../../inc/config.inc.php');
  include_once(dirname(__FILE__).'/../users/user_accounts/user_accounts.inc.php');

  if($auth = authenticate()) {
    // only admins can use this script
    if($auth['type'] === 'admin') {
      $result = create_404_state($_SERVER['REQUEST_METHOD'].' method is unavailable.');
    } else {
      $result = create_401_state();
    }
  }else {
    $result = create_401_state();
  }
}

// return the JSON encoding of the result
echo json_encode($result);
?>
