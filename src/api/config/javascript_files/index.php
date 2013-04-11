<?php
/**
 * Javascript script for the RMS API.
 *
 * Allows read and write access to Javascript URLs and files via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 20 2012
 * @package    api.config.javascript_files
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).'/../logs/logs.inc.php');
include_once(dirname(__FILE__).'/javascript_files.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check for authorization
if ($auth = authenticate()) {
  switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
      // check if this is a default request
      if (count($_GET) === 0) {
        // check for Javascript files
        if ($js = get_javascript_files()) {
          $result = api::create_200_state($js);
        } else {
          $result = api::create_404_state('No Javascript entries found.');
        }
      } else if (count($_GET) === 1 && isset($_GET['id'])) {
        // now check if the entry was found
        if ($js = get_javascript_file_by_id($_GET['id'])) {
          $result = api::create_200_state($js);
        } else {
          $result = api::create_404_state('Javascript file ID '.$_GET['id'].' is invalid.');
        }
      } else {
        $result = api::create_404_state('Unknown request.');
      }
      break;
    case 'POST':
      if (isset($_POST['request'])) {
        switch ($_POST['request']) {
          case 'update':
            if ($auth['type'] === 'admin') {
              if (count($_POST === 1)) {
                // try and do the update
                if ($error = delete_local_javascript_files() || $error = download_javascript_files()) {
                  $result = api::create_404_state($error);
                } else {
                  write_to_log('SYSTEM: '.$auth['username'].' upedated the Javascript files.');
                  $result = api::create_200_state(get_current_timestamp());
                }
              } else {
                $result = api::create_404_state('Too many fields provided.');
              }
            } else {
              write_to_log('SECURITY: '.$auth['username'].' attempted to update the Javascript files.');
              $result = api::create_401_state();
            }
            break;
          default:
            $result = api::create_404_state($_POST['request'].' request type is invalid.');
            break;
        }
      }
      break;
    default:
      api::create_404_state($_SERVER['REQUEST_METHOD'].' method is unavailable.');
      break;
  }
} else {
  $result = api::create_401_state();
}

// return the JSON encoding of the result
echo json_encode($result);
?>
