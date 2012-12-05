<?php
/**
 * Javascript script for the RMS API.
 *
 * Allows read and write access to Javascript URLs and files via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 8 2012
 * @package    api.config.javascript_files
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).'/../logs/logs.inc.php');
include_once(dirname(__FILE__).'/javascript_files.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check for authorization
if($auth = authenticate()) {
  // default to the error state
  $result = create_404_state(array());

  switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
      // check if this is a default request
      if(count($_GET) === 0) {
        // check for Javascript files
        $js = get_javascript_files();
        if($js) {
          $result = create_200_state($result, $js);
        } else {
          $result['msg'] = 'No Javascript entries found.';
        }
      } else if(count($_GET) === 1 && isset($_GET['id'])) {
        $js = get_javascript_file_by_id($_GET['id']);
        // now check if the entry was found
        if($js) {
          $result = create_200_state($result, $js);
        } else {
          $result['msg'] = 'Javascript file ID "'.$_GET['id'].'" is invalid.';
        }
      }
      break;
    case 'POST':
      if(isset($_POST['request'])) {
        switch ($_POST['request']) {
          case 'update':
            if($auth['type'] !== 'admin') {
              write_to_log('SECURITY: '.$auth['username'].' attempted to update the Javascript files.');
              $result = create_401_state($result);
            } else {
              // try and do the update
              if($error = delete_local_javascript_files() || $error = download_javascript_files()) {
                $result['msg'] = $error;
              } else {
                write_to_log('SYSTEM: Javascript files updated.');
                $result = create_200_state($result, null);
              }
            }
            break;
          default:
            $result['msg'] = $_POST['request'].' request type is invalid.';
            break;
        }
      }
      break;
    default:
      $result['msg'] = $_SERVER['REQUEST_METHOD'].' method is unavailable.';
      break;
  }
} else {
  // default to the 401 state if no auth was given
  $result = create_401_state(array());
}

// return the JSON encoding of the result
echo json_encode($result);
?>
