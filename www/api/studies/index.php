<?php
/**
 * Studies API script. Currently, this level of the API contains no useful functions.
 *
 * Returns a bad request response.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 8 2012
 * @package    api.studies
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../api.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

$result = create_404_state(array());
$result['msg'] = $_SERVER['REQUEST_METHOD'].' method is unavailable.';

echo json_encode($result);
?>
