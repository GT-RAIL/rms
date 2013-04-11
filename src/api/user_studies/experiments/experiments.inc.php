<?php
/**
 * Experiments include functions for the RMS API.
 *
 * Allows read and write access to experiment entries via PHP function calls. Used throughout RMS
 * and within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 5 2012
 * @package    api.user_studies.experiments
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../conditions/conditions.inc.php');

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
  $sql = sprintf("SELECT * FROM `experiments` WHERE `expid`='%d'", api::cleanse($id));
  return mysqli_fetch_assoc(mysqli_query($db, $sql));
}

/**
 * Get an array of all experiment entires in the database for a given user or null if none exist.
 *
 * @param integer $userid The user ID number
 * @return array|null The array of experiment entries or null if none exist.
 */
function get_experiments_by_userid($userid) {
  global $db;

  // grab the javascript entries and push them into an array
  $result = array();
  $sql = sprintf("SELECT * FROM `experiments` WHERE `userid`='%d'", api::cleanse($userid));
  $query = mysqli_query($db, $sql);
  while($cur = mysqli_fetch_assoc($query)) {
    $result[] = $cur;
  }

  return (count($result) === 0) ? null : $result;
}

/**
 * Get the experiment for the given user with the given interface and environment. A valid experiment
 * means that this call is made during their allocated timeslot.
 *
 * @param integer $intid The interface ID number
 * @param integer $userid The user ID number
 * @param integer $envid The environment ID number
 * @return array|null The valid experiment found or null if none exist
 */
function get_valid_experiment_by_intid_userid_and_envid($intid, $userid, $envid) {
  global $db;

  // grab the experiments within the current time
  $timestamp = api::cleanse(get_current_timestamp());
  $sql = sprintf("SELECT * FROM `experiments` WHERE (`userid`='%d' AND `end`>'%s' AND `start`<'%s')"
  , api::cleanse($userid), $timestamp, $timestamp);
  $query = mysqli_query($db, $sql);
  while($cur = mysqli_fetch_assoc($query)) {
    // check the fields
    if ($cur['envid'] === $envid
    && ($condition = get_condition_by_id($cur['condid']))
    && $condition['intid'] === $intid) {
      // found a match
      return $cur;
    }
  }

  // none found
  return null;
}
?>
