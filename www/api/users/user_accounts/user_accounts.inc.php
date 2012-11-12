<?php
/**
 * User accounts include functions for the RMS API.
 *
 * Allows read and write access to user accounts via PHP function calls. Used throughout RMS and
 * within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 8 2012
 * @package    api.users.user_accounts
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');

/**
 * Get an array of all the user accounts in the system, or null if none exist.
 *
 * @return array|null An array of all the user accounts in the system, or null if none exist
 */
function get_user_accounts() {
  global $db;

  // grab the users and push them into an array
  $result = array();
  $query = mysqli_query($db, "SELECT * FROM `user_accounts`");
  while($cur = mysqli_fetch_assoc($query)) {
    $result[] = $cur;
  }

  return (count($result) === 0) ? null : $result;
}

/**
 * Get the user array for the user with the given ID, or null if none exist.
 *
 * @param integer $id The user ID number
 * @return array|null An array of the user's SQL entry or null if none exist
 */
function get_user_account_by_id($id) {
  global $db;

  // grab the article
  $sql = sprintf("SELECT * FROM `user_accounts` WHERE userid='%d'", $db->real_escape_string($id));
  return mysqli_fetch_assoc(mysqli_query($db, $sql));
}

/**
 * Get the user with the given username and MD5 encrypted password or null if none exists.
 *
 * @param string $username The username
 * @param string $password The MD5 encrypted password
 * @return array|null The user with the given username and encrypted password or null if none exists
 */
function authenticate($username, $password) {
  global $db;

  $sql = sprintf("SELECT * FROM `user_accounts` WHERE (username='%s') AND (password='%s')"
          , $username, $password);

  // check if a result was found
  $query = mysqli_query($db,$sql);
  if (mysqli_num_rows($query) == 1) {
    return mysqli_fetch_array($query);
  } else {
    return null;
  }
}
?>
