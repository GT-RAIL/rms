<?php
/**
 * Environments API script.
 *
 * Allows read and write access to environments via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 6 2012
 * @package    api.robot_environments.environments
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).'/../../config/logs/logs.inc.php');
include_once(dirname(__FILE__).'/../../users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/environments.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check for authorization
if ($auth = authenticate()) {
  switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
      // check if we are creating a new entry
      if (valid_environment_fields($_POST)) {
        if ($auth['type'] === 'admin') {
          if ($error = create_environment($_POST['protocol'], $_POST['envaddr'], $_POST['port']
          , $_POST['type'], $_POST['notes'], $_POST['enabled'])) {
            $result = api::create_404_state($error);
          } else {
            // the most recent entry is the one we just created (auto increment)
            $all = get_environments();
            $max_id = -1;
            $data = null;
            foreach ($all as $cur) {
              if ($cur['envid'] > $max_id) {
                $max_id = $cur['envid'];
                $data = $cur;
              }
            }
            write_to_log('EDIT: '.$auth['username'].' created environment '.$_POST['envaddr'].'.');
            $result = api::create_200_state($data);
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to create an environment.');
          $result = api::create_401_state();
        }
      } else {
        $result = api::create_404_state('Unknown request.');
      }
      break;
    case 'GET':
      if (isset($_GET['request'])) {
        // create an editor
        switch ($_GET['request']) {
          case 'editor':
            if ($auth['type'] === 'admin') {
              if (count($_GET) === 1) {
                $result = api::create_200_state(get_environment_editor_html(null));
              } else if (count($_GET) === 2 && isset($_GET['id'])) {
                $result = api::create_200_state(get_environment_editor_html($_GET['id']));
              } else {
                $result = api::create_404_state('Too many fields provided.');
              }
            } else {
              write_to_log('SECURITY: '.$auth['username'].' attempted to get an environment editor.');
              $result = api::create_401_state();
            }
            break;
          default:
            $result = api::create_404_state($_GET['request'].' request type is invalid.');
            break;
        }
      } else {
        $result = api::create_404_state('Unknown request.');
      }
      break;
    case 'DELETE':
      if (count($deleteArray) === 1 && isset($deleteArray['id'])) {
        if ($auth['type'] === 'admin') {
          if ($error = delete_environment_by_id($deleteArray['id'])) {
            $result = api::create_404_state($error);
          } else {
            write_to_log('EDIT: '.$auth['username'].' deleted environment ID '.$deleteArray['id'].'.');
            $result = api::create_200_state(get_current_timestamp());
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to delete environment ID '.$deleteArray['id'].'.');
          $result = api::create_401_state();
        }
      } else {
        $result = api::create_404_state('Unknown request.');
      }
      break;
    case 'PUT':
      if (isset($putArray['id'])) {
        if ($auth['type'] === 'admin') {
          if ($error = update_environment($putArray)) {
            $result = api::create_404_state($error);
          } else {
            write_to_log('EDIT: '.$auth['username'].' modified environment ID '.$putArray['id'].'.');
            $result = api::create_200_state(get_environment_by_id($putArray['id']));
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to edit environment ID '.$putArray['id'].'.');
          $result = api::create_401_state();
        }
      } else {
        $result = api::create_404_state('Unknown request.');
      }
      break;
    default:
      $result = api::create_404_state($_SERVER['REQUEST_METHOD'].' method is unavailable.');
      break;
  }
} else {
  // default to the 401 state if no auth was given
  $result = api::create_401_state();
}

// return the JSON encoding of the result
echo json_encode($result);
?>
