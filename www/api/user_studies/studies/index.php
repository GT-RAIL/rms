<?php
/**
 * Study script for the RMS API.
 *
 * Allows read and write access to study entries via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 30 2012
 * @package    api.user_studies.studies
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).'/../../users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/studies.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check for authorization
if($auth = authenticate())  {
  switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
      if(count($_GET) === 0) {
        // check the user level
        if($auth['type'] === 'admin') {
          if($studies = get_studies()) {
            $result = create_200_state($studies);
          } else {
            $result = create_404_state('No study entries found.');
          }
        } else {
          $result = create_401_state(array());
        }
      } else if(count($_GET) === 1 && isset($_GET['id'])) {
        // check the user level
        if($auth['type'] === 'admin') {
          // now check if the entry was found
          if($study = get_study_by_id($_GET['id'])) {
            $result = create_200_state($study);
          } else {
            $result = create_404_state('Study ID "'.$_GET['id'].'" is invalid.');
          }
        } else {
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
