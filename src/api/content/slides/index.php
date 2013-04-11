<?php
/**
 * Slideshow script for the RMS API.
 *
 * Allows read and write access to slideshow slides via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 20 2012
 * @package    api.content.slides
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).'/../../config/logs/logs.inc.php');
include_once(dirname(__FILE__).'/../../users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/slides.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check for authorization
if ($auth = authenticate()) {
  switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
      // check if we are creating a new entry
      if (valid_slide_fields($_POST)) {
        if ($auth['type'] === 'admin') {
          // check if a file was uploaded
          if ($_FILES['img']['error'] !== 0 && $_FILES['img']['error'] !== 4) {
            $result = api::create_404_state();
            $error = 'PHP file upload returned with error code '.$_FILES['img']['error'].'.';
          } else {
            if ($error = create_slide($_POST['caption'], $_POST['index'], $_FILES['img']['name'], $_FILES['img']['tmp_name'])) {
              $result = api::create_404_state($error);
            } else {
              write_to_log('EDIT: '.$auth['username'].' created slide '.$_FILES['img']['name'].'.');
              $result = api::create_200_state(get_slide_by_img($_FILES['img']['name']));
            }
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to create a slide.');
          $result = api::create_401_state();
        }
      } else if (isset($_FILES['img']) && count($_POST) === 0) {
        if ($auth['type'] === 'admin') {
          // check if a file was uploaded
          if ($_FILES['img']['error'] !== 0 && $_FILES['img']['error'] !== 4) {
            $result = api::create_404_state();
            $error = 'PHP file upload returned with error code '.$_FILES['img']['error'].'.';
          } else {
            if ($error = upload_img($_FILES['img']['name'], $_FILES['img']['tmp_name'])) {
              $result = api::create_404_state($error);
            } else {
              write_to_log('EDIT: '.$auth['username'].' created slide '.$_FILES['img']['name'].'.');
              $result = api::create_200_state(get_slide_by_img($_FILES['img']['name']));
            }
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to upload a slide.');
          $result = api::create_401_state();
        }
      }else {
        $result = api::create_404_state('Unknown request.');
      }
      break;
    case 'GET':
      // check if this is a default request
      if (count($_GET) === 0) {
        // check for slides
        if ($slides = get_slides()) {
          $result = api::create_200_state($slides);
        } else {
          $result = api::create_404_state('No slideshow entires found.');
        }
      } else if (isset($_GET['request'])) {
        switch ($_GET['request']) {
          case 'editor':
            if ($auth['type'] === 'admin') {
              if (count($_GET) === 1) {
                $result = api::create_200_state(get_slide_editor_html(null));
              } else if (count($_GET) === 2 && isset($_GET['id'])) {
                $result = api::create_200_state(get_slide_editor_html($_GET['id']));
              } else {
                $result = api::create_404_state('Too many fields provided.');
              }
            } else {
              write_to_log('SECURITY: '.$auth['username'].' attempted to get a slide editor.');
              $result = api::create_401_state();
            }
            break;
          default:
            $result = api::create_404_state($_GET['request'].' request type is invalid.');
            break;
        }
      } else {
        $result = api::create_404_state('Unknown request.');
      }
      break;
    case 'DELETE':
      if (count($deleteArray) === 1 && isset($deleteArray['id'])) {
        if ($auth['type'] === 'admin') {
          if ($error = delete_slide_by_id($deleteArray['id'])) {
            $result = api::create_404_state($error);
          } else {
            write_to_log('EDIT: '.$auth['username'].' deleted slide ID '.$deleteArray['id'].'.');
            $result = api::create_200_state(get_current_timestamp());
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to delete slide ID '.$deleteArray['id'].'.');
          $result = api::create_401_state();
        }
      } else {
        $result = api::create_404_state('Unknown request.');
      }
      break;
    case 'PUT':
      if (isset($putArray['id'])) {
        if ($auth['type'] === 'admin') {
          if ($error = update_slide($putArray)) {
            $result = api::create_404_state($error);
          } else {
            write_to_log('EDIT: '.$auth['username'].' modified slide ID '.$putArray['id'].'.');
            $result = api::create_200_state(get_slide_by_id($putArray['id']));
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to edit slide ID '.$putArray['id'].'.');
          $result = api::create_401_state();
        }
      } else {
        $result = api::create_404_state('Unknown request.');
      }
      break;
    default:
      $result = api::create_404_state($_SERVER['REQUEST_METHOD'].' method is unavailable.');
      break;
  }
} else {
  $result = api::create_401_state();
}

// return the JSON encoding of the result
echo json_encode($result);
?>
