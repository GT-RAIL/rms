<?php
/**
 * Study log script for the RMS API.
 *
 * Allows read and write access to study log entries via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 30 2012
 * @package    api.user_studies.study_logs
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).'/../../users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/../experiments/experiments.inc.php');
include_once(dirname(__FILE__).'/study_logs.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check for authorization
if($auth = authenticate())  {
	switch ($_SERVER['REQUEST_METHOD']) {
		case 'POST':
			// check the fields
			if(count($_POST) === 2 && isset($_POST['expid']) && isset($_POST['entry'])) {
				// check if we are authorized to make this entry
				$exp = get_experiment_by_id($_POST['expid']);
				if($auth['type'] === 'admin' || $auth['userid'] === $exp['userid']) {
					// insert into the log
					create_study_log($_POST['expid'], $_POST['entry']);
					$result = create_200_state(get_current_timestamp());
				} else {
					write_to_log('SECURITY: '.$auth['username'].' attempted to insert into the study log.');
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
					if($logs = get_study_logs()) {
						$result = create_200_state($logs);
					} else {
						$result = create_404_state('No study log entries found.');
					}
				} else {
					$result = create_401_state();
				}
			} else if(count($_GET) === 1 && isset($_GET['id'])) {
				// check the user level
				if($auth['type'] === 'admin') {
					// now check if the entry was found
					if($log = get_study_log_by_id($_GET['id'])) {
						$result = create_200_state($log);
					} else {
						$result = create_404_state('Study log ID "'.$_GET['id'].'" is invalid.');
					}
				} else {
					$result = create_401_state();
				}
			}else if(count($_GET) === 1 && isset($_GET['expid'])) {
				// check the user level
				if($auth['type'] === 'admin') {
					// now check if the entry was found
					if($logs = get_study_logs_by_expid($_GET['expid'])) {
						$result = create_200_state($logs);
					} else {
						$result = create_404_state('No study log entries found with experiment ID "'.$_GET['expid'].'".');
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
