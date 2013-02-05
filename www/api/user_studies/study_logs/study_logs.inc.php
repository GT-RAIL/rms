<?php
/**
 * Study log include functions for the RMS API.
 *
 * Allows read and write access to study log entries via PHP function calls. Used throughout RMS and
 * within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    February, 4 2013
 * @package    api.user_studies.study_logs
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');

/**
 * Get an array of all study log entires in the database or null if none exist.
 *
 * @return array|null The array of study log entries or null if none exist.
 */
function get_study_logs() {
  global $db;

  // grab the javascript entries and push them into an array
  $result = array();
  $query = mysqli_query($db, "SELECT * FROM `study_logs`");
  while($cur = mysqli_fetch_assoc($query)) {
    $result[] = $cur;
  }

  return (count($result) === 0) ? null : $result;
}

/**
 * Get the study log entry array for the entry with the given ID, or null if none exist.
 *
 * @param integer $id The study log entry ID number
 * @return array|null An array of the study log entry's SQL entry or null if none exist
 */
function get_study_log_by_id($id) {
  global $db;

  // grab the article
  $sql = sprintf("SELECT * FROM `study_logs` WHERE `logid`='%d'", cleanse($id));
  return mysqli_fetch_assoc(mysqli_query($db, $sql));
}

/**
 * Get an array of all study log entires in the database with the given experiment ID number or null
 * if none exist.
 *
 * @param integer $expid The experiment ID number
 * @return array|null The array of study log entries or null if none exist.
 */
function get_study_logs_by_expid($expid) {
  global $db;

  // grab the javascript entries and push them into an array
  $result = array();
  $sql = sprintf("SELECT * FROM `study_logs` WHERE `expid`='%d'", cleanse($expid));
  $query = mysqli_query($db, $sql);
  while($cur = mysqli_fetch_assoc($query)) {
    $result[] = $cur;
  }

  return (count($result) === 0) ? null : $result;
}

/**
 * Create an entry in the study log for the given experiment.
 *
 * @param integer $expid The experiment ID number
 * @param string $entry The log entry
 */
function create_study_log($expid, $entry) {
global $db;

  // don't log an empty message
  if($message !== '') {
    // insert the message into the database
    $sql = sprintf("INSERT INTO `study_logs` (`expid`, `entry`) VALUES ('%d','%s')"
    , cleanse($expid), cleanse($entry));
    mysqli_query($db, $sql);
  }
}
?>
