<?php
/**
 * Experiments script for the RMS API.
 *
 * Allows read and write access to experiment entries via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 12 2013
 * @package    api.user_studies.experiments
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).
        '/../../users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/experiments.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check for authorization
if ($auth = user_accounts::authenticate()) {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if (count($_GET) === 0) {
                // check the user level
                if ($auth['type'] === 'admin') {
                    if ($experiments = experiments::get_experiments()) {
                        $result = api::create_200_state($experiments);
                    } else {
                        $msg = 'No experiment entries found.';
                        $result = api::create_404_state($msg);
                    }
                } else {
                    $result = api::create_401_state();
                }
            } else if (count($_GET) === 1 && isset($_GET['id'])) {
                // check the user level
                if ($auth['type'] === 'admin') {
                    // now check if the entry was found
                    if ($experiment = experiments::get_experiment_by_id(
                        $_GET['id']
                    )
                    ) {
                        $result = api::create_200_state($experiment);
                    } else {
                        $msg = 'Experiment ID "'.$_GET['id'].'" is invalid.';
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
