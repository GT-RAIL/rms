<?php
/**
 * Interface include functions for the RMS API.
 *
 * Allows read and write access to interfaces. Used throughout RMS and within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 30 2012
 * @package    api.robot_environments.interfaces
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');

/**
 * Get an array of all the interface entries, or null if none exist.
 *
 * @return array|null An array of all the interface entries, or null if none exist
 */
function get_interfaces() {
  global $db;

  // grab the entries and push them into an array
  $result = array();
  $query = mysqli_query($db, "SELECT * FROM `interfaces`");
  while($cur = mysqli_fetch_assoc($query)) {
    $result[] = $cur;
  }

  return (count($result) === 0) ? null : $result;
}

/**
 * Get the interface array for the interface with the given ID, or null if none exist.
 *
 * @param integer $id The interface ID number
 * @return array|null An array of the interface's SQL entry or null if none exist
 */
function get_interface_by_id($id) {
  global $db;

  $sql = sprintf("SELECT * FROM `interfaces` WHERE `intid`='%d'", $db->real_escape_string($id));
  return mysqli_fetch_assoc(mysqli_query($db, $sql));
}
?>
