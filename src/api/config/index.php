<?php
/**
 * Configration script for the RMS API.
 *
 * Allows read and write access to site settings and configuration. Proper
 * authentication is required. Alternatively, if the 'inc/confic.inc.php' file
 * is missing, no authentication is required and only the default POST request
 * will be accepted. This allows for initial site setup.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 11 2013
 * @package    api.config
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../api.inc.php');
include_once(dirname(__FILE__).'/config.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check if this is initial site setup
if (!file_exists(dirname(__FILE__).'/../../inc/config.inc.php')) {
    // we only take POST requests at this point
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // check the config fields
        if (config::valid_config_fields($_POST)) {
            // check if a file was uploaded
            $error = false;
            if (isset($_FILES['sqlfile'])) {
                // check for an error
                if ($_FILES['sqlfile']['error'] !== 0
                        && $_FILES['sqlfile']['error'] !== 4) {
                    $error = 'PHP file upload returned with error code '.
                            $_FILES['sqlfile']['error'].'.';
                } else {
                    // if a blank file name was given, we just use the init file
                    $sqlfile = ($_FILES['sqlfile']['tmp_name'] === '')
                    ? $initSqlFile : $_FILES['sqlfile']['tmp_name'];
                }
            } else {
                $sqlfile = $initSqlFile;
            }

            // try to upload the database
            if ($error || $error = config::upload_database(
                $_POST['host'], $_POST['dbuser'], $_POST['dbpass'], 
                $_POST['db'], $sqlfile
            )
            ) {
                $result = api::create_404_state($error);
            } else {
                // now create the config file
                if ($error = config::create_config_inc(
                    $_POST['host'], $_POST['dbuser'], $_POST['dbpass'],
                    $_POST['db'], $_POST['site-name'], $_POST['google'],
                    $_POST['copyright']
                )
                ) {
                    $result = api::create_404_state($error);
                } else {
                    include_once(dirname(__FILE__).'/logs/logs.inc.php');
                    logs::write_to_log('SYSTEM: Site created.');
                    // return the timestamp
                    $t = api::get_current_timestamp();
                    $result = api::create_200_state($t);
                }
            }
        } else {
            $msg = 'Incomplete list of required fields.';
            $result = api::create_404_state($msg);
        }
    } else {
        $msg = $_SERVER['REQUEST_METHOD'].' method is unavailable.';
        $result = api::create_404_state($msg);
    }
} else {
    // load the normal include files
    include_once(dirname(__FILE__).'/../../inc/config.inc.php');
    include_once(dirname(__FILE__).'/logs/logs.inc.php');
    include_once(dirname(__FILE__).
            '/../users/user_accounts/user_accounts.inc.php');

    if ($auth = user_accounts::authenticate()) {
        // only admins can use this script
        if ($auth['type'] === 'admin') {
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    if (isset($_POST['request'])) {
                        switch ($_POST['request']) {
                            // check for the editor request
                            case 'update':
                                if (count($_POST) === 1) {
                                    // try and do the update
                                    $error = config::run_database_update();
                                    if ($error) {
                                        $result = api::create_404_state($error);
                                    } else {
                                        $log = 'SYSTEM: '.$auth['username'].
                                            ' updated the database.';
                                        logs::write_to_log($log);
                                        $v = config::get_db_version();
                                        $result = api::create_200_state($v);
                                    }
                                } else {
                                    $msg = 'Too many fields provided.';
                                    $result = api::create_404_state($msg);
                                }
                                break;
                            default:
                                $msg = $_GET['request'].
                                    ' request type is invalid.';
                                $result = api::create_404_state($msg);
                                break;
                        }
                    } else {
                        $result = api::create_404_state('Unknown request.');
                    }
                    break;
                case 'GET':
                    if (isset($_GET['request'])) {
                        switch ($_GET['request']) {
                            // check for the editor request
                            case 'editor':
                                $msg = config::get_site_settings_editor();
                                if (count($_GET) === 1) {
                                    $result = api::create_200_state($msg);
                                } else {
                                    $msg = 'Too many fields provided.';
                                    $result = api::create_404_state($msg);
                                }
                                break;
                            default:
                                $msg = $_GET['request'].
                                    ' request type is invalid.';
                                $result = api::create_404_state($msg);
                                break;
                        }
                    } else {
                        $result = api::create_404_state('Unknown request.');
                    }
                    break;
                case 'PUT':
                    if (count($putArray) > 0) {
                        if ($error = config::update_site_settings($putArray)) {
                            $result = api::create_404_state($error);
                        } else {
                            $log = 'SYSTEM: '.$auth['username'].
                                ' modified the site settings.';
                            logs::write_to_log($log);
                            $t = api::get_current_timestamp();
                            $result = api::create_200_state($t);
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
            $log = 'SECURITY: '.$auth['username'].
                ' attempted to use the config script.';
            logs::write_to_log($log);
            $result = api::create_401_state();
        }
    } else {
        $result = api::create_401_state();
    }
}

// return the JSON encoding of the result
echo json_encode($result);
