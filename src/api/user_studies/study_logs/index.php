<?php
/**
 * Study log script for the RMS API.
 *
 * Allows read and write access to study log entries via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 12 2013
 * @package    api.user_studies.study_logs
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).
        '/../../users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/../experiments/experiments.inc.php');
include_once(dirname(__FILE__).'/study_logs.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check for authorization
if ($auth = user_accounts::authenticate()) {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            // check the fields
            if (count($_POST) === 2 && isset($_POST['expid']) 
                    && isset($_POST['entry'])) {
                // check if we are authorized to make this entry
                $exp = experiments::get_experiment_by_id($_POST['expid']);
                if ($auth['type'] === 'admin' 
                        || $auth['userid'] === $exp['userid']) {
                    // insert into the log
                    study_logs::create_study_log(
                        $_POST['expid'], $_POST['entry']
                    );
                    $result = api::create_200_state(
                        api::get_current_timestamp()
                    );
                } else {
                    $msg = 'SECURITY: '.$auth['username'].
                        ' attempted to insert into the study log.';
                    logs::write_to_log($msg);
                    $result = api::create_401_state();
                }
            } else {
                $result = api::create_404_state('Unknown request.');
            }
            break;
        case 'GET':
            if (count($_GET) === 0) {
                // check the user level
                if ($auth['type'] === 'admin') {
                    if ($logs = study_logs::get_study_logs()) {
                        $result = api::create_200_state($logs);
                    } else {
                        $msg = 'No study log entries found.';
                        $result = api::create_404_state($msg);
                    }
                } else {
                    $result = api::create_401_state();
                }
            } else if (count($_GET) === 1 && isset($_GET['id'])) {
                // check the user level
                if ($auth['type'] === 'admin') {
                    // now check if the entry was found
                    if ($log = study_logs::get_study_log_by_id($_GET['id'])) {
                        $result = api::create_200_state($log);
                    } else {
                        $msg = 'Study log ID "'.$_GET['id'].'" is invalid.';
                        $result = api::create_404_state($msg);
                    }
                } else {
                    $result = api::create_401_state();
                }
            } else if (count($_GET) === 1 && isset($_GET['expid'])) {
                // check the user level
                if ($auth['type'] === 'admin') {
                    // now check if the entry was found
                    if ($logs = study_logs::get_study_logs_by_expid(
                        $_GET['expid']
                    )
                    ) {
                        $result = api::create_200_state($logs);
                    } else {
                        $msg = 'No study log entries found with '.
                            'experiment ID "'.$_GET['expid'].'".';
                        $result = api::create_404_state($msg);
                    }
                } else {
                    $result = api::create_401_state();
                }
            } else {
                $result = api::create_404_state('Unknown request.');
            }
            break;
        default:
            $msg = $_SERVER['REQUEST_METHOD'].' method is unavailable.';
            $result = api::create_404_state($msg);
            break;
    }
} else {
    $result = api::create_401_state();
}

// return the JSON encoding of the result
echo json_encode($result);
