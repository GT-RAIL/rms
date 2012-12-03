<?php
/**
 * Widget include functions for the RMS API.
 *
 * Allows read and write access to widgets. Used throughout RMS and within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 30 2012
 * @package    api.robot_environments.widgets
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');

/**
 * Get an array of all the widget entries, or null if none exist.
 *
 * @return array|null An array of all the widget entries, or null if none exist
 */
function get_widgets() {
  global $db;

  // grab the entries and push them into an array
  $result = array();
  $query = mysqli_query($db, "SELECT * FROM `widgets`");
  while($cur = mysqli_fetch_assoc($query)) {
    $result[] = $cur;
  }

  return (count($result) === 0) ? null : $result;
}

/**
 * Get the widget array for the widget with the given ID, or null if none exist.
 *
 * @param integer $id The widget ID number
 * @return array|null An array of the widget's SQL entry or null if none exist
 */
function get_widget_by_id($id) {
  global $db;

  $sql = sprintf("SELECT * FROM `widgets` WHERE `widgetid`='%d'", $db->real_escape_string($id));
  return mysqli_fetch_assoc(mysqli_query($db, $sql));
}

/**
 * Get the column names in the MySQL table for the widget with the given ID, or null if none exist.
 *
 * @param integer $id The widget ID number
 * @return array|null An array of the widget's MySQL table column names or null if none exist
 */
function get_widget_table_columns_by_id($id) {
  global $db;

  // check the widget
  if($w = get_widget_by_id($id)) {
    $columns = array();
    $sql = sprintf("SHOW COLUMNS FROM `%s`", $db->real_escape_string($w['table']));
    $query = mysqli_query($db, $sql);
    // fill the array
    while($cur = mysqli_fetch_array($query)) {
      $columns[] = $cur['Field'];
    }
    return (count($columns) === 0) ? null : $columns;
  }

  // none found
  return null;
}

/**
 * Get an array of the instances of the given widget, or null if none exist.
 *
 * @param integer $id The widget ID number
 * @return array|null An array of the widget's instances or null if none exist
 */
function get_widget_instances_by_id($id) {
  global $db;

  // check the widget
  if($w = get_widget_by_id($id)) {
    // grab the entries and push them into an array
    $result = array();
    $sql = sprintf("SELECT * FROM `%s`", $db->real_escape_string($w['table']));
    $query = mysqli_query($db, $sql);
    while($cur = mysqli_fetch_assoc($query)) {
      $result[] = $cur;
    }
    return (count($result) === 0) ? null : $result;
  }

  // none found
  return null;
}
?>
