<?php
/**
 * Content pages script for the RMS API.
 *
 * Allows read and write access to content pages via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 13 2012
 * @package    api.content.content_pages
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).'/../../users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/content_pages.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check for authorization
if($auth = authenticate()) {
  switch ($_SERVER['REQUEST_METHOD']) {
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
