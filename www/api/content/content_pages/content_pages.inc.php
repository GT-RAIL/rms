<?php
/**
 * Content page include functions for the RMS API.
 *
 * Allows read and write access to content pages via PHP function calls. Used throughout RMS and
 * within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 8 2012
 * @package    api.content.content_pages
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');

/**
 * Get all content page entries in the database or null if none exist.
 *
 * @return array|null All content pages in the database or null if none exist
 */
function get_content_pages() {
  global $db;

  // grab the pages and push them into an array
  $result = array();
  $query = mysqli_query($db, "SELECT * FROM `content_pages` ORDER BY `menu_index`");
  while($cur = mysqli_fetch_assoc($query)) {
    $result[] = $cur;
  }

  return (count($result) === 0) ? null : $result;
}

/**
 * Get the content page array for the page with the given ID, or null if none exist.
 *
 * @param integer $id The page ID number
 * @return array|null An array of the page's SQL entry or null if none exist
 */
function get_content_page_by_id($id) {
  global $db;

  // grab the page
  $sql = sprintf("SELECT * FROM `content_pages` WHERE pageid='%d'", $db->real_escape_string($id));
  return mysqli_fetch_assoc(mysqli_query($db, $sql));
}
?>
