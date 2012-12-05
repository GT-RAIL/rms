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
 * @version    December, 5 2012
 * @package    api.config
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../api.inc.php');
include_once(dirname(__FILE__).'/config.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// default to the error state
$result = create_404_state(array());

switch ($_SERVER['REQUEST_METHOD']) {
  case 'POST':
    // check if this is the initial setup
    if(file_exists(dirname(__FILE__).'/../../inc/config.inc.php')) {
      $result = create_401_state(array());
    } else if(valid_config_fields($_POST)) {
      $error = false;
      // check if a file was uploaded
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
      if($error || ($error = upload_database($_POST['host'], $_POST['dbuser'], $_POST['dbpass']
      , $_POST['db'], $sqlfile))) {
        $result['msg'] = $error;
      } else {
        // now create the config file
        if($error = create_config_inc($_POST['host'], $_POST['dbuser'], $_POST['dbpass']
        , $_POST['db'], $_POST['site-name'], $_POST['google'], $_POST['copyright'])) {
          $result['msg'] = $error;
        } else {
          // now delete any old Javascript files
          include_once(dirname(__FILE__).'/javascript_files/javascript_files.inc.php');
          if($error = delete_local_javascript_files() || $error = download_javascript_files()) {
            $result['msg'] = $error;
          } else {
            include_once(dirname(__FILE__).'/logs/logs.inc.php');
            write_to_log('SYSTEM: Site created.');
            $result = create_200_state($result, null);
          }
        }
      }
    } else {
      $result['msg'] = 'Incomplete list of required fields.';
    }
    break;
  default:
    $result['msg'] = $_SERVER['REQUEST_METHOD'].' method is unavailable.';
    break;
}

// return the JSON encoding of the result
echo json_encode($result);
?>
