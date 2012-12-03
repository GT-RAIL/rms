<?php
/**
 * System log include functions for the RMS API.
 *
 * Allows read and write access to the system log. Used throughout RMS and within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 30 2012
 * @package    api.config
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');

/**
 * Get an array of all the system log entries, or null if none exist.
 *
 * @return array|null An array of all the system log entries, or null if none exist
 */
function get_logs() {
  global $db;

  // grab the entries and push them into an array
  $result = array();
  $query = mysqli_query($db, "SELECT * FROM `log` ORDER BY `logid` DESC");
  while($cur = mysqli_fetch_assoc($query)) {
    $result[] = $cur;
  }

  return (count($result) === 0) ? null : $result;
}

/**
 * A function that can be used to write a log message to the database.
 *
 * @param string $message the log entry message to put into the database
 */
function write_to_log($message) {
  global $db;

  // don't log an empty message
  if($message !== '') {
    // cleanup the strings
    $message = $db->real_escape_string($message);
    $request_uri = $db->real_escape_string($_SERVER['REQUEST_URI']);
    $remote_addr = $db->real_escape_string($_SERVER['REMOTE_ADDR']);
    // insert the message into the database
    $sql = "INSERT INTO log (request_uri, remote_addr, entry) VALUES ('$request_uri','$remote_addr','$message')";
    mysqli_query($db, $sql);
  }
}
?>
