<?php
/**
 * Environments API script.
 *
 * Allows read and write access to environments via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 30 2012
 * @package    api.robot_environments.environments
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).'/../../config/log/log.inc.php');
include_once(dirname(__FILE__).'/../../users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/environments.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check for authorization
if($auth = authenticate()) {
  // default to the 404 state
  $result = create_404_state(array());

  switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
      if(isset($_GET['request'])) {
        switch ($_GET['request']) {
          case 'editor':
            if($auth['type'] !== 'admin') {
              write_to_log('SECURITY: '.$auth['username'].' attempted to get an environment editor.');
              $result = create_401_state($result);
            } else {
              if(count($_GET) === 1) {
                $html = array();
                $html['html'] = get_environment_editor_html(null);
                $result = create_200_state($result, $html);
              } else if(count($_GET) === 2 && isset($_GET['id'])) {
                $html = array();
                $html['html'] = get_environment_editor_html($_GET['id']);
                $result = create_200_state($result, $html);
              }
            }
            break;
          default:
            $result['msg'] = $_GET['request'].' request type is invalid.';
            break;
        }
      }
      break;
    default:
      write_to_log('SECURITY: '.$auth['username'].' attempted to make an environment '.$_SERVER['REQUEST_METHOD'].' request.');
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
