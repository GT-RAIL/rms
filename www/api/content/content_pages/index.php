<?php
/**
 * Content pages script for the RMS API.
 *
 * Allows read and write access to content pages via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 20 2012
 * @package    api.content.content_pages
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).'/../../config/logs/logs.inc.php');
include_once(dirname(__FILE__).'/../../users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/content_pages.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check for authorization
if($auth = authenticate()) {
  switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
      // check if we are creating a new entry
      if(valid_content_page_fields($_POST)) {
        if($auth['type'] === 'admin') {
          if($error = create_content_page($_POST['title'], $_POST['menu'], $_POST['index'],
          isset($_POST['js']) ? $_POST['js'] : null)) {
            $result = create_404_state($error);
          } else {
            write_to_log('EDIT: '.$auth['username'].' created content page '.$_POST['title'].'.');
            $result = create_200_state(get_content_page_by_title($_POST['title']));
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to create a content page.');
          $result = create_401_state();
        }
      } else {
        $result = create_404_state('Unknown request.');
      }
      break;
    case 'GET':
      // check if this is a default request
      if(count($_GET) === 0) {
        // check for pages
        if($pages = get_content_pages()) {
          $result = create_200_state($pages);
        } else {
          $result = create_404_state('No content pages found.');
        }
      } else if(count($_GET) === 1 && isset($_GET['id'])) {
        // now check if the page was found
        if($page = get_content_page_by_id($_GET['id'])) {
          $result = create_200_state($page);
        } else {
          $result = create_404_state('Content Page ID "'.$_GET['id'].'" is invalid.');
        }
      } else if(isset($_GET['request'])) {
        switch ($_GET['request']) {
          case 'editor':
            if($auth['type'] === 'admin') {
              if(count($_GET) === 1) {
                $result = create_200_state(get_content_page_editor_html(null));
              } else if(count($_GET) === 2 && isset($_GET['id'])) {
                $result = create_200_state(get_content_page_editor_html($_GET['id']));
              } else {
                $result = create_404_state('Too many fields provided.');
              }
            } else {
              write_to_log('SECURITY: '.$auth['username'].' attempted to get a content page editor.');
              $result = create_401_state();
            }
            break;
          default:
            $result = create_404_state($_GET['request'].' request type is invalid.');
            break;
        }
      } else {
        $result = create_404_state('Unknown request.');
      }
      break;
    case 'DELETE':
      if(count($_DELETE) === 1 && isset($_DELETE['id'])) {
        if($auth['type'] === 'admin') {
          if($error = delete_content_page_by_id($_DELETE['id'])) {
            $result = create_404_state($error);
          } else {
            write_to_log('EDIT: '.$auth['username'].' deleted content page ID '.$_DELETE['id'].'.');
            $result = create_200_state(get_current_timestamp());
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to delete content page ID '.$_DELETE['id'].'.');
          $result = create_401_state();
        }
      } else {
        $result = create_404_state('Unknown request.');
      }
      break;
    case 'PUT':
      if(isset($_PUT['id'])) {
        if($auth['type'] === 'admin') {
          if($error = update_content_page($_PUT)) {
            $result = create_404_state($error);
          } else {
            write_to_log('EDIT: '.$auth['username'].' modified content page ID '.$_PUT['id'].'.');
            $result = create_200_state(get_content_page_by_id($_PUT['id']));
          }
        } else {
          write_to_log('SECURITY: '.$auth['username'].' attempted to edit content page ID '.$_PUT['id'].'.');
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
