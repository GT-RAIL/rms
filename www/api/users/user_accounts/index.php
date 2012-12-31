<?php
/**
 * User account script for the RMS API.
 *
 * Allows read and write access to user accounts via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 30 2012
 * @package    api.users.user_accounts
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).'/../../config/logs/logs.inc.php');
include_once(dirname(__FILE__).'/user_accounts.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check for authorization
if($auth = authenticate()) {
  switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
      // check if this is a request
      if(isset($_POST['request'])) {
        switch ($_POST['request']) {
          case 'server_session':
            if(count($_POST) === 1) {
              // create the session
              session_start();
              $_SESSION['userid'] = $auth['userid'];
              write_to_log('SESSION: '.$auth['username'].' created a new session.');
              $result = create_200_state(get_current_timestamp());
            } else {
              $result = create_404_state('Too many fields provided.');
            }
            break;
          case 'destroy_session':
            if(count($_POST) === 1) {
              if(isset($_SESSION['userid'])) {
                // destroy the session
                unset($_SESSION['userid']);
                session_destroy();
                write_to_log('SESSION: '.$auth['username'].' destroyed their session.');
                $result = create_200_state(get_current_timestamp());
              } else {
                $result = create_404_state('No session to destroy.');
              }
            } else {
              $result = create_404_state('Too many fields provided.');
            }
            break;
          default:
            $result = create_404_state($_POST['request'].' request type is invalid.');
            break;
        }
      } else if(valid_user_account_fields($_POST)) {
        if($auth['type'] === 'admin') {
          if($error = create_user_account($_POST['username'], $_POST['password'], $_POST['firstname']
          , $_POST['lastname'], $_POST['email'], $_POST['type'])) {
            $result = create_404_state($error);
          } else {
            write_to_log('EDIT: '.$auth['username'].' created user '.$_POST['username'].'.');
            $account = get_user_account_by_username($_POST['username']);
            // remove the password info
            unset($account['password']);
            unset($account['salt']);
            $result = create_200_state($account);
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to create a user.');
          $result = create_401_state();
        }
      } else {
        $result = create_404_state('Unknown request.');
      }
      break;
    case 'GET':
      if(count($_GET) === 0) {
        // check the user level
        if($auth['type'] === 'admin') {
          // we authenticated so we know at least one user exists
          $accounts = get_user_accounts();
          // remove the password info
          foreach ($accounts as $account) {
            unset($account['password']);
            unset($account['salt']);
          }
          $result = create_200_state(get_user_accounts());
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to get all users.');
          $result = create_401_state();
        }
      } else if(count($_GET) === 1 && isset($_GET['id'])) {
        // check the user level
        if($auth['type'] === 'admin' || $auth['userid'] === $_GET['id']) {
          // check if it exists
          if($user = get_user_account_by_id($_GET['id'])) {
            // remove the password info
            unset($user['password']);
            unset($user['salt']);
            $result = create_200_state($user);
          } else {
            $result = create_404_state('User with ID '.$_GET['id'].' does not exist.');
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to get user ID '.$_GET['id'].'.');
          $result = create_401_state();
        }
      } else if(isset($_GET['request'])) {
        switch ($_GET['request']) {
          case 'editor':
            if($auth['type'] === 'admin') {
              if(count($_GET) === 1) {
                $result = create_200_state(get_user_account_editor_html(null));
              } else if(count($_GET) === 2 && isset($_GET['id'])) {
                $result = create_200_state(get_user_account_editor_html($_GET['id']));
              } else {
                $result = create_404_state('Too many fields provided.');
              }
            } else {
              write_to_log('SECURITY: '.$auth['username'].' attempted to get a user editor.');
              $result = create_401_state();
            }
            break;
          default:
            $result = create_404_state($_GET['request'].' request type is invalid.');
            break;
        }
      } else {
        $result = create_404_state('Unknown request.');
      }
      break;
    case 'DELETE':
      if(count($_DELETE) === 1 && isset($_DELETE['id'])) {
        if($auth['type'] === 'admin') {
          if($error = delete_user_account_by_id($_DELETE['id'])) {
            $result = create_404_state($error);
          } else {
            write_to_log('EDIT: '.$auth['username'].' deleted user ID '.$_DELETE['id'].'.');
            $result = create_200_state(get_current_timestamp());
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to delete user ID '.$_DELETE['id'].'.');
          $result = create_401_state();
        }
      } else {
        $result = create_404_state('Unknown request.');
      }
      break;
    case 'PUT':
      if(isset($_PUT['id'])) {
        if($auth['type'] === 'admin') {
          if($error = update_user_account($_PUT)) {
            $result = create_404_state($error);
          } else {
            write_to_log('EDIT: '.$auth['username'].' modified user ID '.$_PUT['id'].'.');
            $account = get_user_account_by_id($_PUT['id']);
            // remove the password info
            unset($account['password']);
            unset($account['salt']);
            $result = create_200_state($account);
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to edit user ID '.$_PUT['id'].'.');
          $result = create_401_state();
        }
      } else {
        $result = create_404_state('Unknown request.');
      }
      break;
    default:
      $result = create_404_state($_SERVER['REQUEST_METHOD'].' method is unavailable.');
      break;
  }
} else {
  $result = create_401_state();
}

// return the JSON encoding of the result
echo json_encode($result);
?>
