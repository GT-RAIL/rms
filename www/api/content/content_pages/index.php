<?php
/**
 * Content pages script for the RMS API.
 *
 * Allows read and write access to content pages via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 8 2012
 * @package    api.content.content_pages
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).'/content_pages.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// default to the error state
$result = create_404_state(array());

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    // check if this is a default request
    if(count($_GET) === 0) {
      // check for articles
      $pages = get_content_pages();
      if($pages) {
        $result = create_200_state($result, $pages);
      } else {
        $result['msg'] = 'No content pages found.';
      }
    } else if(count($_GET) === 1 && isset($_GET['id'])) {
      $page = get_content_page_by_id($_GET['id']);
      // now check if the page was found
      if($page) {
        $result = create_200_state($result, $page);
      } else {
        $result['msg'] = 'Content Page ID "'.$_GET['id'].'" is invalid.';
      }
    }
    break;
  default:
    $result['msg'] = $_SERVER['REQUEST_METHOD'].' method is unavailable.';
    break;
}

// return the JSON encoding of the result
echo json_encode($result);
?>
