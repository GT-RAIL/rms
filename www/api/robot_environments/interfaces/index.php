<?php
/**
 * Interface API script.
 *
 * Allows read and write access to interface via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 7 2012
 * @package    api.robot_environments.interfaces
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).'/../../config/logs/logs.inc.php');
include_once(dirname(__FILE__).'/../../users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/interfaces.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check for authorization
if($auth = authenticate()) {
  switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
      // check if we are creating a new entry
      if(valid_interface_fields($_POST)) {
        if($auth['type'] === 'admin') {
          if($error = create_interface($_POST['name'], $_POST['location'])) {
            $result = create_404_state($error);
          } else {
            write_to_log('EDIT: '.$auth['username'].' created interface '.$_POST['name'].'.');
            $result = create_200_state(get_interface_by_location($_POST['location']));
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to create an interface.');
          $result = create_401_state();
        }
      } else {
        $result = create_404_state('Unknown request.');
      }
      break;
    case 'GET':
      if(isset($_GET['request'])) {
        // create an editor
        switch ($_GET['request']) {
          case 'editor':
            if($auth['type'] === 'admin') {
              if(count($_GET) === 1) {
                $result = create_200_state(get_interface_editor_html(null));
              } else if(count($_GET) === 2 && isset($_GET['id'])) {
                $result = create_200_state(get_interface_editor_html($_GET['id']));
              } else {
                $result = create_404_state('Too many fields provided.');
              }
            } else {
              write_to_log('SECURITY: '.$auth['username'].' attempted to get an interface editor.');
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
          if($error = delete_interface_by_id($_DELETE['id'])) {
            $result = create_404_state($error);
          } else {
            write_to_log('EDIT: '.$auth['username'].' deleted interface ID '.$_DELETE['id'].'.');
            $result = create_200_state(get_current_timestamp());
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to delete interface ID '.$_DELETE['id'].'.');
          $result = create_401_state();
        }
      } else {
        $result = create_404_state('Unknown request.');
      }
      break;
    case 'PUT':
      if(isset($_PUT['id'])) {
        if($auth['type'] === 'admin') {
          if($error = update_interface($_PUT)) {
            $result = create_404_state($error);
          } else {
            write_to_log('EDIT: '.$auth['username'].' modified interface ID '.$_PUT['id'].'.');
            $result = create_200_state(get_interface_by_id($_PUT['id']));
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to edit interface ID '.$_PUT['id'].'.');
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
  // default to the 401 state if no auth was given
  $result = create_401_state();
}

// return the JSON encoding of the result
echo json_encode($result);
?>
