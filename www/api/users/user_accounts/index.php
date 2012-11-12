<?php
/**
 * User account script for the RMS API.
 *
 * Allows read and write access to user accounts via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 8 2012
 * @package    api.users.user_accounts
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).'/user_accounts.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check for authorization
if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])
&& ($auth = authenticate($_SERVER['PHP_AUTH_USER'], md5($_SERVER['PHP_AUTH_PW']))))  {
  // default to the 404 state
  $result = create_404_state(array());

  switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
      // check if this is a request
      if(isset($_POST['request'])) {
        switch ($_POST['request']) {
          case 'server_session':
            // check if the username and password are given
            if(count($_POST) === 1) {
              // create the session
              session_start();
              $_SESSION['userid'] = $auth['userid'];
              $_SESSION['username'] = $auth['username'];
              $_SESSION['password'] = $auth['password'];
              $result = create_200_state($result, $auth);
            }
            break;
          default:
            $result['msg'] = $_POST['request'].' request type is invalid.';
            break;
        }
      }
      break;
    case 'GET':
      if(count($_GET) === 0) {
        // check the user level
        if($auth['type'] === 'admin') {
          // we authenticated so we know at least one user exists
          $result = create_200_state($result, get_user_accounts());
        } else {
          $result = create_401_state(array());
        }
      } else if(count($_GET) === 1 && isset($_GET['id'])) {
        // check the user level
        if($auth['type'] === 'admin' || $auth['userid'] === $_GET['id']) {
          $user = get_user_account_by_id($_GET['id']);
          // check if it exists
          if($user) {
            $result = create_200_state($result, $user);
          } else {
            $result['msg'] = 'User with ID '.$_GET['id'].' does not exist.';
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
