<?php
/**
 * Robot environments API script.
 *
 * Allows read and write access to robot environments. This includes things like pairings between
 * interfaces and environments. Used throughout RMS and within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 12 2012
 * @package    api.robot_environments
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../api.inc.php');
include_once(dirname(__FILE__).'/../config/logs/logs.inc.php');
include_once(dirname(__FILE__).'/../users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/robot_environments.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check for authorization
if ($auth = authenticate()) {
  switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
      // check if we are creating a new entry
      if (valid_environment_interface_pair_fields($_POST)) {
        if ($auth['type'] === 'admin') {
          if ($error = create_environment_interface_pair($_POST['envid'], $_POST['intid'])) {
            $result = create_404_state($error);
          } else {
            write_to_log('EDIT: '.$auth['username'].' created environment-interface pair '.$_POST['envid'].'-'.$_POST['intid'].'.');
            $result = create_200_state(get_environment_interface_pair_by_envid_and_intid($_POST['envid'], $_POST['intid']));
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to create an environment-interface pair.');
          $result = create_401_state();
        }
      } else {
        $result = create_404_state('Unknown request.');
      }
      break;
    case 'GET':
      if (isset($_GET['request'])) {
        // create an editor
        switch ($_GET['request']) {
          case 'editor':
            if ($auth['type'] === 'admin') {
              if (count($_GET) === 1) {
                $result = create_200_state(get_environment_interface_pair_editor_html(null));
              } else if (count($_GET) === 2 && isset($_GET['id'])) {
                $result = create_200_state(get_environment_interface_pair_editor_html($_GET['id']));
              } else {
                $result = create_404_state('Too many fields provided.');
              }
            } else {
              write_to_log('SECURITY: '.$auth['username'].' attempted to get an environment-interface pair editor.');
              $result = create_401_state();
            }
            break;
          case'generate':
            if (count($_GET) === 3 && isset($_GET['envid']) && isset($_GET['intid'])) {
              generate_environment_interface($auth['userid'], $_GET['envid'], $_GET['intid']);
              return;
            } else {
              $result = create_404_state('Incompatible fields provided.');
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
      if (count($_DELETE) === 1 && isset($_DELETE['id'])) {
        if ($auth['type'] === 'admin') {
          if ($error = delete_environment_interface_pair_by_id($_DELETE['id'])) {
            $result = create_404_state($error);
          } else {
            write_to_log('EDIT: '.$auth['username'].' deleted environment-interface pair ID '.$_DELETE['id'].'.');
            $result = create_200_state(get_current_timestamp());
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to delete environment-interface pair ID '.$_DELETE['id'].'.');
          $result = create_401_state();
        }
      } else {
        $result = create_404_state('Unknown request.');
      }
      break;
    case 'PUT':
      if (isset($_PUT['id'])) {
        if ($auth['type'] === 'admin') {
          if ($error = update_environment_interface_pair($_PUT)) {
            $result = create_404_state($error);
          } else {
            write_to_log('EDIT: '.$auth['username'].' modified environment-interface pair ID '.$_PUT['id'].'.');
            $result = create_200_state(get_environment_interface_pair_by_id($_PUT['id']));
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to edit environment-interface pair ID '.$_PUT['id'].'.');
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
