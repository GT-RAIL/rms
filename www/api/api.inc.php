<?php
/**
 * Common include functions for the RMS API.
 *
 * A set of useful functions used within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 8 2012
 * @package    api
 * @link       http://ros.org/wiki/rms
 */

/**
 * Creates a default 404 state in the given array. This includes a 'false' in the 'ok' element,
 * 'Unknown request.' in the 'msg' element, and null in the 'data' element. The header is also
 * changed to the 404 state.
 *
 * @param array $result_array The result array to populate with default 404 information
 * @return array The filled $result_array
 */
function create_404_state($result_array) {
  $result_array['ok'] = false;
  $result_array['msg'] = 'Unknown request.';
  $result_array['data'] = null;
  header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);

  return $result_array;
}

/**
 * Creates a default 401 state in the given array. This includes a 'false' in the 'ok' element,
 * 'Insufficient authorization.' in the 'msg' element, and null in the 'data' element. The header is
 * also changed to the 401 state.
 *
 * @param array $result_array The result array to populate with default 401 information
 * @return array The filled $result_array
 */
function create_401_state($result_array) {
  $result_array['ok'] = false;
  $result_array['msg'] = 'Insufficient authorization.';
  $result_array['data'] = null;
  header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized', true, 401);

  return $result_array;
}

/**
 * Creates a default 200 state in the given array. This includes a 'true' in the 'ok' element,
 * 'Success.' in the 'msg' element, and $data in the 'data' element. The header is also changed to
 * the 200 state.
 *
 * @param array $result_array The result array to populate with default 200 information
 * @param object $data The data to place in the 'data' element of the array
 * @return array The filled $result_array
 */
function create_200_state($result_array, $data) {
  header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK', true, 200);
  $result_array['ok'] = true;
  $result_array['msg'] = 'Success.';
  $result_array['data'] = $data;

  return $result_array;
}
?>
