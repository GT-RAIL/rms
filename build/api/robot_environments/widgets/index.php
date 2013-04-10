<?php
/**
 * Widget API script. Currently, this level of the API contains no useful functions.
 *
 * Allows read and write access to widgets via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 7 2012
 * @package    api.robot_environments.widgets
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).'/../../config/logs/logs.inc.php');
include_once(dirname(__FILE__).'/../../users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/widgets.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check for authorization
if($auth = authenticate()) {
  switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
      // check if we are creating a new entry
      if(valid_widget_fields($_POST)) {
        if($auth['type'] === 'admin') {
          if($error = create_widget($_POST['name'], $_POST['table'], $_POST['script'])) {
            $result = create_404_state($error);
          } else {
            write_to_log('EDIT: '.$auth['username'].' created widget '.$_POST['name'].'.');
            $result = create_200_state(get_widget_by_script($_POST['script']));
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to create a widget.');
          $result = create_401_state();
        }
      } else if(valid_widget_instance_fields($_POST)) {
        if($auth['type'] === 'admin') {
          if($error = create_widget_instance($_POST)) {
            $result = create_404_state($error);
          } else {
            // the most recent entry is the one we just created (auto increment)
            $all = get_widget_instances_by_widgetid($_POST['widgetid']);
            $max_id = -1;
            $data = null;
            foreach ($all as $cur) {
              if($cur['id'] > $max_id) {
                $max_id = $cur['id'];
                $data = $cur;
              }
            }
            write_to_log('EDIT: '.$auth['username'].' created widget instance '.$_POST['label'].' in widget ID '.$_POST['widgetid']);
            $result = create_200_state($data);
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to create a widget instance in widget ID '.$_POST['widgetid']);
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
            // check the editor type
            if($auth['type'] === 'admin') {
              if(count($_GET) === 1) {
                $result = create_200_state(get_widget_editor_html(null));
              } else if(count($_GET) === 2 && isset($_GET['id'])) {
                $result = create_200_state(get_widget_editor_html($_GET['id']));
              } else if(count($_GET) === 2 && isset($_GET['widgetid'])) {
                $result = create_200_state(get_widget_instance_editor_html_by_widgetid($_GET['widgetid'], null));
              } else if(count($_GET) === 3 && isset($_GET['widgetid']) && isset($_GET['id'])) {
                $result = create_200_state(get_widget_instance_editor_html_by_widgetid($_GET['widgetid'], $_GET['id']));
              } else {
                $result = create_404_state('Too many fields provided.');
              }
            } else {
              write_to_log('SECURITY: '.$auth['username'].' attempted to get a widget editor.');
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
          if($error = delete_widget_by_id($_DELETE['id'])) {
            $result = create_404_state($error);
          } else {
            write_to_log('EDIT: '.$auth['username'].' deleted widget ID '.$_DELETE['id'].'.');
            $result = create_200_state(get_current_timestamp());
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to delete widget ID '.$_DELETE['id'].'.');
          $result = create_401_state();
        }
      } else if(count($_DELETE) === 2 && isset($_DELETE['widgetid']) && isset($_DELETE['id'])) {
        if($auth['type'] === 'admin') {
          if($error = delete_widget_instance_by_widgetid_and_id($_DELETE['widgetid'], $_DELETE['id'])) {
            $result = create_404_state($error);
          } else {
            write_to_log('EDIT: '.$auth['username'].' deleted widget instance ID '.$_DELETE['id'].' from widget ID '.$_DELETE['widgetid']);
            $result = create_200_state(get_current_timestamp());
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to delete widget instance ID '.$_DELETE['id'].' from widget ID '.$_DELETE['widgetid']);
          $result = create_401_state();
        }
      } else {
        $result = create_404_state('Unknown request.');
      }
      break;
    case 'PUT':
      if(isset($_PUT['id'])) {
        if($auth['type'] === 'admin') {
          // check if this is a widget update or an instance update
          if(isset($_PUT['widgetid'])) {
            if($error = update_widget_instance($_PUT)) {
              $result = create_404_state($error);
            } else {
              write_to_log('EDIT: '.$auth['username'].' modified widget instance ID '.$_PUT['id'].' from widget ID '.$_PUT['widgetid']);
              $result = create_200_state(get_widget_by_id($_PUT['id']));
            }
          } else {
            if($error = update_widget($_PUT)) {
              $result = create_404_state($error);
            } else {
              write_to_log('EDIT: '.$auth['username'].' modified widget ID '.$_PUT['id'].'.');
              $result = create_200_state(get_widget_by_id($_PUT['id']));
            }
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to edit widget ID '.$_PUT['id'].'.');
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
