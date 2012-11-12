<?php
/**
 * Conditions script for the RMS API.
 *
 * Allows read and write access to condition entries via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 12 2012
 * @package    api.studies.conditions
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).'/../../users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/conditions.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check for authorization
if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])
&& ($auth = authenticate($_SERVER['PHP_AUTH_USER'], md5($_SERVER['PHP_AUTH_PW']))))  {
  // default to the 404 state
  $result = create_404_state(array());

  switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
      if(count($_GET) === 0) {
        // check the user level
        if($auth['type'] === 'admin') {
          $conditions = get_conditions();
          if($conditions) {
            $result = create_200_state($result, $conditions);
          } else {
            $result['msg'] = 'No condition entries found.';
          }
        } else {
          $result = create_401_state(array());
        }
      } else if(count($_GET) === 1 && isset($_GET['id'])) {
        // check the user level
        if($auth['type'] === 'admin') {
          $condition = get_condition_by_id($_GET['id']);
          // now check if the entry was found
          if($condition) {
            $result = create_200_state($result, $condition);
          } else {
            $result['msg'] = 'Condition ID "'.$_GET['id'].'" is invalid.';
          }
        } else {
          $result = create_401_state(array());
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
