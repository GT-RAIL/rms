<?php
/**
 * Slideshow script for the RMS API.
 *
 * Allows read and write access to slideshow slides via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 1 2012
 * @package    api.content.slideshow
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).'/slideshow.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// default to the error state
$result = create_404_state(array());

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    // check if this is a default request
    if(count($_GET) === 0) {
      // check for slideshows
      $slides = get_slides();
      if($slides) {
        $result = create_200_state($result, $slides);
      } else {
        $result['msg'] = 'No content articles found.';
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
