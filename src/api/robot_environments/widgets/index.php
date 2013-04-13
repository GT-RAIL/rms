<?php
/**
 * Widget API script. Currently, this level of the API contains no useful
 * functions.
 *
 * Allows read and write access to widgets via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 12 2013
 * @package    api.robot_environments.widgets
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).'/../../config/logs/logs.inc.php');
include_once(dirname(__FILE__).
        '/../../users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/widgets.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check for authorization
if ($auth = user_accounts::authenticate()) {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            // check if we are creating a new entry
            if (widgets::valid_widget_fields($_POST)) {
                if ($auth['type'] === 'admin') {
                    if ($error = widgets::create_widget(
                        $_POST['name'], $_POST['table']
                    )) {
                        $result = api::create_404_state($error);
                    } else {
                        $msg = 'EDIT: '.$auth['username'].
                            ' created widget '.$_POST['name'].'.';
                        logs::write_to_log($msg);
                        $w = widgets::get_widget_by_table($_POST['table']);
                        $result = api::create_200_state($w);
                    }
                } else {
                    $msg = 'SECURITY: '.$auth['username'].
                        ' attempted to create a widget.';
                    logs::write_to_log($msg);
                    $result = api::create_401_state();
                }
            } else if (widgets::valid_widget_instance_fields($_POST)) {
                if ($auth['type'] === 'admin') {
                    if ($error = widgets::create_widget_instance($_POST)) {
                        $result = api::create_404_state($error);
                    } else {
                        // the most recent entry is the one we just created
                        $all = widgets::get_widget_instances_by_widgetid(
                            $_POST['widgetid']
                        );
                        $maxID = -1;
                        $data = null;
                        foreach ($all as $cur) {
                            if ($cur['id'] > $maxID) {
                                $maxID = $cur['id'];
                                $data = $cur;
                            }
                        }
                        $msg = 'EDIT: '.$auth['username'].
                            ' created widget instance '.$_POST['label'].
                            ' in widget ID '.$_POST['widgetid'];
                        logs::write_to_log($msg);
                        $result = api::create_200_state($data);
                    }
                } else {
                    $msg = 'SECURITY: '.$auth['username'].
                        ' attempted to create a widget instance in widget ID '.
                        $_POST['widgetid'];
                    logs::write_to_log($msg);
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
                        // check the editor type
                        if ($auth['type'] === 'admin') {
                            if (count($_GET) === 1) {
                                $edit = widgets::get_widget_editor();
                                $result = api::create_200_state($edit);
                            } else if (count($_GET) === 2 
                                    && isset($_GET['id'])) {
                                $edit = widgets::get_widget_editor($_GET['id']);
                                $result = api::create_200_state($edit);
                            } else if (count($_GET) === 2 
                                    && isset($_GET['widgetid'])) {
                                $edit = widgets::
                                    get_widget_instance_editor_by_widgetid(
                                        $_GET['widgetid']
                                    );
                                $result = api::create_200_state($edit);
                            } else if (count($_GET) === 3 
                                    && isset($_GET['widgetid']) 
                                    && isset($_GET['id'])) {
                                $edit = widgets::
                                    get_widget_instance_editor_by_widgetid(
                                        $_GET['widgetid'], $_GET['id']
                                    );
                                $result = api::create_200_state($edit);
                            } else {
                                $msg = 'Too many fields provided.';
                                $result = api::create_404_state($msg);
                            }
                        } else {
                            $msg = 'SECURITY: '.$auth['username'].
                                ' attempted to get a widget editor.';
                            logs::write_to_log($msg);
                            $result = api::create_401_state();
                        }
                        break;
                    default:
                        $msg = $_GET['request'].' request type is invalid.';
                        $result = api::create_404_state($msg);
                        break;
                }
            } else {
                $result = api::create_404_state('Unknown request.');
            }
            break;
        case 'DELETE':
            if (count($deleteArray) === 1 && isset($deleteArray['id'])) {
                if ($auth['type'] === 'admin') {
                    if ($error = widgets::delete_widget_by_id(
                        $deleteArray['id']
                    )) {
                        $result = api::create_404_state($error);
                    } else {
                        $msg = 'EDIT: '.$auth['username'].
                            ' deleted widget ID '.$deleteArray['id'].'.';
                        logs::write_to_log($msg);
                        $t = api::get_current_timestamp();
                        $result = api::create_200_state($t);
                    }
                } else {
                    $msg = 'SECURITY: '.$auth['username'].
                        ' attempted to delete widget ID '.
                        $deleteArray['id'].'.';
                    logs::write_to_log($msg);
                    $result = api::create_401_state();
                }
            } else if (count($deleteArray) === 2 
                    && isset($deleteArray['widgetid'])
                    && isset($deleteArray['id'])) {
                if ($auth['type'] === 'admin') {
                    if ($error = widgets::
                            delete_widget_instance_by_widgetid_and_id(
                                $deleteArray['widgetid'], $deleteArray['id']
                            )
                    ) {
                        $result = api::create_404_state($error);
                    } else {
                        $msg = 'EDIT: '.$auth['username'].
                            ' deleted widget instance ID '.$deleteArray['id'].
                            ' from widget ID '.$deleteArray['widgetid'];
                        logs::write_to_log($msg);
                        $t = api::get_current_timestamp();
                        $result = api::create_200_state($t);
                    }
                } else {
                    $msg = 'SECURITY: '.$auth['username'].
                        ' attempted to delete widget instance ID '.
                        $deleteArray['id'].' from widget ID '.
                        $deleteArray['widgetid'];
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
                    // check if this is a widget update or an instance update
                    if (isset($putArray['widgetid'])) {
                        if ($error = widgets::update_widget_instance(
                            $putArray
                        )) {
                            $result = api::create_404_state($error);
                        } else {
                            $msg = 'EDIT: '.$auth['username'].
                                ' modified widget instance ID '.
                                $putArray['id'].' from widget ID '.
                                $putArray['widgetid'];
                            logs::write_to_log($msg);
                            $w = widgets::get_widget_by_id($putArray['id']);
                            $result = api::create_200_state($w);
                        }
                    } else {
                        if ($error = widgets::update_widget($putArray)) {
                            $result = api::create_404_state($error);
                        } else {
                            $msg = 'EDIT: '.$auth['username'].
                                ' modified widget ID '.$putArray['id'].'.';
                            logs::write_to_log($msg);
                            $w = widgets::get_widget_by_id($putArray['id']);
                            $result = api::create_200_state($w);
                        }
                    }
                } else {
                    $msg = 'SECURITY: '.$auth['username'].
                        ' attempted to edit widget ID '.$putArray['id'].'.';
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
    // default to the 401 state if no auth was given
    $result = api::create_401_state();
}

// return the JSON encoding of the result
echo json_encode($result);
