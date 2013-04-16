<?php
/**
 * User account script for the RMS API.
 *
 * Allows read and write access to user accounts via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 12 2013
 * @package    api.users.user_accounts
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).'/../../config/logs/logs.inc.php');
include_once(dirname(__FILE__).'/user_accounts.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check for authorization
if ($auth = user_accounts::authenticate()) {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            // check if this is a request
            if (isset($_POST['request'])) {
                switch ($_POST['request']) {
                    case 'server_session':
                        if (count($_POST) === 1) {
                            // create the session
                            session_start();
                            $_SESSION['userid'] = $auth['userid'];
                            $msg = 'SESSION: '.$auth['username'].
                                ' created a new session.';
                            logs::write_to_log($msg);
                            $t = api::get_current_timestamp();
                            $result = api::create_200_state($t);
                        } else {
                            $msg = 'Too many fields provided.';
                            $result = api::create_404_state($msg);
                        }
                        break;
                    case 'destroy_session':
                        if (count($_POST) === 1) {
                            if (isset($_SESSION['userid'])) {
                                // destroy the session
                                unset($_SESSION['userid']);
                                session_destroy();
                                $msg = 'Too many fields provided.';
                                logs::write_to_log($msg);
                                $t = api::get_current_timestamp();
                                $result = api::create_200_state($t);
                            } else {
                                $msg = 'No session to destroy.';
                                $result = api::create_404_state($msg);
                            }
                        } else {
                            $msg = 'No session to destroy.';
                            $result = api::create_404_state($msg);
                        }
                        break;
                    default:
                        $msg = 'No session to destroy.';
                        $result = api::create_404_state($msg);
                        break;
                }
            } else if (user_accounts::valid_user_account_fields($_POST)) {
                if ($auth['type'] === 'admin') {
                    if ($error = user_accounts::create_user_account(
                        $_POST['username'], $_POST['password'],
                        $_POST['firstname'], $_POST['lastname'],
                        $_POST['email'], $_POST['type']
                    )
                    ) {
                        $result = api::create_404_state($error);
                    } else {
                        $msg = 'EDIT: '.$auth['username'].' created user '.
                            $_POST['username'].'.';
                        logs::write_to_log($msg);
                        $username = $_POST['username'];
                        $account = user_accounts::
                            get_user_account_by_username($username);
                        // remove the password info
                        unset($account['password']);
                        unset($account['salt']);
                        $result = api::create_200_state($account);
                    }
                } else {
                    $msg = 'SECURITY: '.$auth['username'].
                        ' attempted to create a user.';
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
                    // we authenticated so we know at least one user exists
                    $accounts = user_accounts::get_user_accounts();
                    // remove the password info
                    foreach ($accounts as $account) {
                        unset($account['password']);
                        unset($account['salt']);
                    }
                    $accounts = user_accounts::get_user_accounts();
                    $result = api::create_200_state($accounts);
                } else {
                    $msg = 'SECURITY: '.$auth['username'].
                        ' attempted to get all users.';
                    logs::write_to_log($msg);
                    $result = api::create_401_state();
                }
            } else if (count($_GET) === 1 && isset($_GET['id'])) {
                // check the user level
                if ($auth['type'] === 'admin' 
                        || $auth['userid'] === $_GET['id']) {
                    // check if it exists
                    if ($user = user_accounts::get_user_account_by_id(
                        $_GET['id']
                    )
                    ) {
                        // remove the password info
                        unset($user['password']);
                        unset($user['salt']);
                        $result = api::create_200_state($user);
                    } else {
                        $msg = 'User with ID '.$_GET['id'].' does not exist.';
                        $result = api::create_404_state($msg);
                    }
                } else {
                    $msg = 'SECURITY: '.$auth['username'].
                        ' attempted to get user ID '.$_GET['id'].'.';
                    logs::write_to_log($msg);
                    $result = api::create_401_state();
                }
            } else if (isset($_GET['request'])) {
                switch ($_GET['request']) {
                    case 'editor':
                        if ($auth['type'] === 'admin') {
                            // get the editors
                            $id = isset($_GET['id']) ? $_GET['id'] : null;
                            $e = user_accounts::get_user_account_editor(
                                $id
                            );
                            if (count($_GET) <= 2) {
                                $result = api::create_200_state($e);
                            } else {
                                $msg = 'Unknown request.';
                                $result = api::create_404_state($msg);
                            }
                        } else {
                            $msg = 'SECURITY: '.$auth['username'].
                                ' attempted to get a user editor.';
                            logs::write_to_log();
                            $result = api::create_401_state();
                        }
                        break;
                    default:
                        $result = api::create_404_state('Unknown request.');
                        break;
                }
            } else {
                $result = api::create_404_state('Unknown request.');
            }
            break;
        case 'DELETE':
            if (count($deleteArray) === 1 && isset($deleteArray['id'])) {
                if ($auth['type'] === 'admin') {
                    if ($error = user_accounts::delete_user_account_by_id(
                        $deleteArray['id']
                    )
                    ) {
                        $result = api::create_404_state($error);
                    } else {
                        $msg = 'EDIT: '.$auth['username'].' deleted user ID '.
                            $deleteArray['id'].'.';
                        logs::write_to_log($msg);
                        $t = api::get_current_timestamp();
                        $result = api::create_200_state($t);
                    }
                } else {
                    $msg = 'SECURITY: '.$auth['username'].
                        ' attempted to delete user ID '.$deleteArray['id'].'.';
                    logs::write_to_log($msg);
                    $result = api::create_401_state();
                }
            } else {
                $result = api::create_404_state('Unknown request.');
            }
            break;
        case 'PUT':
            if (isset($putArray['id'])) {
                if ($auth['type'] === 'admin') {
                    if ($error = user_accounts::update_user_account(
                        $putArray
                    )
                    ) {
                        $result = api::create_404_state($error);
                    } else {
                        $msg = 'EDIT: '.$auth['username'].
                            ' modified user ID '.$putArray['id'].'.';
                        logs::write_to_log($msg);
                        $id = $putArray['id'];
                        $account = user_accounts::get_user_account_by_id($id);
                        // remove the password info
                        unset($account['password']);
                        unset($account['salt']);
                        $result = api::create_200_state($account);
                    }
                } else {
                    $msg = 'SECURITY: '.$auth['username'].
                        ' attempted to edit user ID '.$putArray['id'].'.';
                    logs::write_to_log($msg);
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
