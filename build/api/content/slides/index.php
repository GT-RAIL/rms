<?php
/**
 * Slideshow script for the RMS API.
 *
 * Allows read and write access to slideshow slides via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 11 2013
 * @package    api.content.slides
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).'/../../config/logs/logs.inc.php');
include_once(dirname(__FILE__).
        '/../../users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/slides.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check for authorization
if ($auth = user_accounts::authenticate()) {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            // check if we are creating a new entry
            if (slides::valid_slide_fields($_POST)) {
                if ($auth['type'] === 'admin') {
                    // check if a file was uploaded
                    if ($_FILES['img']['error'] !== 0 
                            && $_FILES['img']['error'] !== 4) {
                        $result = api::create_404_state();
                        $error = 'PHP file upload returned with error code '.
                                $_FILES['img']['error'].'.';
                    } else {
                        if ($error = slides::create_slide(
                            $_POST['caption'], $_POST['index'], 
                            $_FILES['img']['name'], 
                            $_FILES['img']['tmp_name']
                        )
                        ) {
                            $result = api::create_404_state($error);
                        } else {
                            $msg = 'EDIT: '.$auth['username'].' created slide '.
                                $_FILES['img']['name'].'.';
                            logs::write_to_log($msg);
                            $name = $_FILES['img']['name'];
                            $s = slides::get_slide_by_img($name);
                            $result = api::create_200_state($s);
                        }
                    }
                } else {
                    $msg = 'SECURITY: '.$auth['username'].
                        ' attempted to create a slide.';
                    logs::write_to_log($msg);
                    $result = api::create_401_state();
                }
            } else if (isset($_FILES['img']) && count($_POST) === 0) {
                if ($auth['type'] === 'admin') {
                    // check if a file was uploaded
                    if ($_FILES['img']['error'] !== 0 
                            && $_FILES['img']['error'] !== 4) {
                        $result = api::create_404_state();
                        $error = 'PHP file upload returned with error code '.
                            $_FILES['img']['error'].'.';
                    } else {
                        if ($error = slides::upload_img(
                            $_FILES['img']['name'], $_FILES['img']['tmp_name']
                        )
                        ) {
                            $result = api::create_404_state($error);
                        } else {
                            $msg = 'EDIT: '.$auth['username'].' created slide '.
                                $_FILES['img']['name'].'.';
                            logs::write_to_log($msg);
                            $file = $_FILES['img']['name'];
                            $s = slides::get_slide_by_img($file);
                            $result = api::create_200_state($s);
                        }
                    }
                } else {
                    $msg = 'SECURITY: '.$auth['username'].
                        ' attempted to upload a slide.';
                    logs::write_to_log($msg);
                    $result = api::create_401_state();
                }
            } else {
                $result = api::create_404_state('Unknown request.');
            }
            break;
        case 'GET':
            // check if this is a default request
            if (count($_GET) === 0) {
                // check for slides
                if ($slides = slides::get_slides()) {
                    $result = api::create_200_state($slides);
                } else {
                    $msg = 'No slideshow entires found.';
                    $result = api::create_404_state($msg);
                }
            } else if (isset($_GET['request'])) {
                switch ($_GET['request']) {
                    case 'editor':
                        if ($auth['type'] === 'admin') {
                            if (count($_GET) === 1) {
                                $edit = slides::get_slide_editor();
                                $result = api::create_200_state($edit);
                            } else if (count($_GET) === 2 
                                    && isset($_GET['id'])) {
                                $id = $_GET['id'];
                                $edit = slides::get_slide_editor($id);
                                $result = api::create_200_state($edit);
                            } else {
                                $msg = 'Too many fields provided.';
                                $result = api::create_404_state($msg);
                            }
                        } else {
                            $msg = 'SECURITY: '.$auth['username'].
                                ' attempted to get a slide editor.';
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
                    if ($error = slides::delete_slide_by_id(
                        $deleteArray['id']
                    )
                    ) {
                        $result = api::create_404_state($error);
                    } else {
                        $msg = 'EDIT: '.$auth['username'].' deleted slide ID '.
                            $deleteArray['id'].'.';
                        logs::write_to_log($msg);
                        $t = api::get_current_timestamp();
                        $result = api::create_200_state($t);
                    }
                } else {
                    $msg = 'SECURITY: '.$auth['username'].
                        ' attempted to delete slide ID '.$deleteArray['id'].'.';
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
                    if ($error = slides::update_slide($putArray)) {
                        $result = api::create_404_state($error);
                    } else {
                        $msg = 'EDIT: '.$auth['username'].
                            ' modified slide ID '.$putArray['id'].'.';
                        logs::write_to_log($msg);
                        $s = slides::get_slide_by_id($putArray['id']);
                        $result = api::create_200_state($s);
                    }
                } else {
                    $msg = 'SECURITY: '.$auth['username'].
                        ' attempted to edit slide ID '.$putArray['id'].'.';
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
