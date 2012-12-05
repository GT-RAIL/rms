<?php
/**
 * Robot environment include functions for the RMS API.
 *
 * Allows read and write access to robot environments. Used throughout RMS and within the RMS API.
 * Useful functions include adding pairings between interfaces and environments.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 30 2012
 * @package    api.robot_environments
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../inc/config.inc.php');

/**
 * Get an array of all the environment-interface pair entries, or null if none exist.
 *
 * @return array|null An array of all the environment-interface pair entries, or null if none exist
 */
function get_environment_interface_pairs() {
  global $db;

  // grab the entries and push them into an array
  $result = array();
  $query = mysqli_query($db, "SELECT * FROM `environment_interface_pairs`");
  while($cur = mysqli_fetch_assoc($query)) {
    $result[] = $cur;
  }

  return (count($result) === 0) ? null : $result;
}

/**
 * Get an array of all the environment-interface pair entries for a given environment, or null if none exist.
 *
 * @param integer $envid The environment ID number
 * @return array|null An array of all the environment-interface pair entries, or null if none exist
 */
function get_environment_interface_pairs_by_envid($envid) {
  global $db;

  // grab the entries and push them into an array
  $result = array();
  $sql = sprintf( "SELECT * FROM `environment_interface_pairs` WHERE `envid`='%d'"
  , $db->real_escape_string($envid));
  $query = mysqli_query($db, $sql);
  while($cur = mysqli_fetch_assoc($query)) {
    $result[] = $cur;
  }

  return (count($result) === 0) ? null : $result;
}
?>
