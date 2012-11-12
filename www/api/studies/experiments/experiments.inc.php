<?php
/**
 * Experiments include functions for the RMS API.
 *
 * Allows read and write access to experiment entries via PHP function calls. Used throughout RMS
 * and within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 12 2012
 * @package    api.studies.experiments
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');

/**
 * Get an array of all experiment entires in the database or null if none exist.
 *
 * @return array|null The array of experiment entries or null if none exist.
 */
function get_experiments() {
  global $db;

  // grab the javascript entries and push them into an array
  $result = array();
  $query = mysqli_query($db, "SELECT * FROM `experiments`");
  while($cur = mysqli_fetch_assoc($query)) {
    $result[] = $cur;
  }

  return (count($result) === 0) ? null : $result;
}

/**
 * Get the experiment array for the entry with the given ID, or null if none exist.
 *
 * @param integer $id The experiment ID number
 * @return array|null An array of the experiment's SQL entry or null if none exist
 */
function get_experiment_by_id($id) {
  global $db;

  // grab the article
  $sql = sprintf("SELECT * FROM `experiments` WHERE expid='%d'", $db->real_escape_string($id));
  return mysqli_fetch_assoc(mysqli_query($db, $sql));
}
?>
