<?php
/**
 * Common include functions for the RMS API.
 *
 * A set of useful functions used within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 6 2012
 * @package    api
 * @link       http://ros.org/wiki/rms
 */

/**
 * The default password text holder.
 * @var string
 */
$PASSWORD_HOLDER = '***********';

/**
 * Create the _DELETE array for the API.
 * @var array
 */
$_DELETE = array();
if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
  parse_str(file_get_contents('php://input'), $_DELETE);
}
/**
 * Create the _PUT array for the API.
 * @var array
 */
$_PUT = array();
if($_SERVER['REQUEST_METHOD'] === 'PUT') {
  parse_str(file_get_contents('php://input'), $_PUT);
}

/**
 * Cleanse the given input string for use in an SQL query. This will perform things such as escape
 * character checks and HTML scrubbing (if enabled).
 *
 * @param string $input The input string to cleanse
 * @param boolean $html If HTML scrubbing (conversion to HTML entities) should be performed (default = true)
 * @return string The cleansed string
 */
function cleanse($input, $html = true) {
  global $db;

  if($html) {
    $cleansed = htmlentities($input, ENT_QUOTES);
  } else {
    $cleansed = $input;
  }

  // trim the string
  $cleansed = trim($cleansed);
  // run a escape check
  $cleansed = $db->real_escape_string($cleansed);
  // escape percentage signs
  $cleansed = addcslashes($cleansed, '%');

  return $cleansed;
}

/**
 * Creates a default 404 state in an array. This includes a 'false' in the 'ok' element, the given
 * error message in the 'msg' element, and null in the 'data' element. The header is also
 * changed to the 404 state.
 *
 * @param string $msg The message to put in the array
 * @return array The filled array
 */
function create_404_state($msg) {
  $result_array = array();

  $result_array['ok'] = false;
  $result_array['msg'] = $msg;
  $result_array['data'] = null;
  header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);

  return $result_array;
}

/**
 * Creates a default 401 state in an array. This includes a 'false' in the 'ok' element,
 * 'Insufficient authorization.' in the 'msg' element, and null in the 'data' element. The header is
 * also changed to the 401 state.
 *
 * @return array The filled array
 */
function create_401_state() {
  $result_array = array();

  $result_array['ok'] = false;
  $result_array['msg'] = 'Insufficient authorization.';
  $result_array['data'] = null;
  header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized', true, 401);

  return $result_array;
}

/**
 * Creates a default 200 state in an array. This includes a 'true' in the 'ok' element,
 * 'Success.' in the 'msg' element, and $data in the 'data' element. The header is also changed to
 * the 200 state.
 *
 * @param object $data The data to place in the 'data' element of the array
 * @return array The filled array
 */
function create_200_state($data) {
  $result_array = array();

  $result_array['ok'] = true;
  $result_array['msg'] = 'Success.';
  $result_array['data'] = $data;

  header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK', true, 200);
  return $result_array;
}

/**
 * Get the enum types for the given column in the given table in an array.
 *
 * @param string $table the name of the MySQL table
 * @param string $column the column name inside of the given table
 * @return array An array containing the enum types for the given column in the given table
 */
function get_enum_types($table, $column) {
  global $db;

  $sql = sprintf("SELECT `column_type` FROM `information_schema`.`columns` WHERE `table_name`='%s' AND column_name='%s'"
  , cleanse($table), cleanse($column));

  $enums = mysqli_fetch_row(mysqli_query($db, $sql));
  return explode("','", str_replace(array("enum('", "')", "''"), array('', '', "'"), $enums[0]));
}

/**
 * Get the current timestamp from the MySQL server.
 *
 * @return string The current timestamp from the MySQL server
 */
function get_current_timestamp() {
  global $db;

  $time = mysqli_fetch_array(mysqli_query($db, "SELECT CURRENT_TIMESTAMP()"));
  return $time['CURRENT_TIMESTAMP()'];
}
?>
