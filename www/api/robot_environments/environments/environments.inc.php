<?php
/**
 * Environment include functions for the RMS API.
 *
 * Allows read and write access to environments. Used throughout RMS and within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 30 2012
 * @package    api.robot_environments.environments
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../../api.inc.php');

/**
 * Get an array of all the environment entries, or null if none exist.
 *
 * @return array|null An array of all the environment entries, or null if none exist
 */
function get_environments() {
  global $db;

  // grab the entries and push them into an array
  $result = array();
  $query = mysqli_query($db, "SELECT * FROM `environments`");
  while($cur = mysqli_fetch_assoc($query)) {
    $result[] = $cur;
  }

  return (count($result) === 0) ? null : $result;
}

/**
 * Get the environment array for the environment with the given ID, or null if none exist.
 *
 * @param integer $id The environment ID number
 * @return array|null An array of the environment's SQL entry or null if none exist
 */
function get_environment_by_id($id) {
  global $db;

  $sql = sprintf("SELECT * FROM `environments` WHERE `envid`='%d'", $db->real_escape_string($id));
  return mysqli_fetch_assoc(mysqli_query($db, $sql));
}

/**
 * Get the enum types for environments in an array.
 *
 * @return array An array containing the enum types for environments
 */
function get_environment_types() {
  return get_enum_types('environments', 'type');
}

/**
 * Get the HTML for an editor used to create or edit the given environment entry. If this is not an
 * edit, null can be given as the ID.
 *
 * @param integer|null $id the ID of the environment to edit, or null if a new entry is being made
 * @return string A string containing the HTML of the editor
 */
function get_environment_editor_html($id) {
  // see if an environment exists with the given id
  $cur = get_environment_by_id($id);

  if($cur) {
    $envaddr = $cur['envaddr'];
    $type = $cur['type'];
    $notes = $cur['notes'];
    $enabled = ($cur['enabled'] === '0') ? '' : 'checked';
  } else {
    $envaddr = '';
    $type = '';
    $notes = '';
    $enabled = 'checked';
  }

  $result = '<p>Complete the following form to create or edit an environment.</p>
             <form action="form/admin/create_environment.php" method="POST">
               <fieldset>
                 <ol>';

  // only show the ID for edits
  $result .=  ($cur) ? '<li><label for="envid">Environment ID</label><input type="text" name="envid"
                             id="envid" value="'.$cur['envid'].'" readonly="readonly" /></li>' : '';

  $result .= '<li>
              <label for="envaddr">Username</label>
              <input type="text" name="envaddr" id="envaddr" value="'.$envaddr.'"
               placeholder="e.g., myrobot.robot-college.edu" required />
            </li>
            <li>
              <label for="type">Type</label>
              <select name="type" id="type" required>';

  // grab the types of environments
  $types = get_environment_types();
  foreach ($types as $curtype) {
    // check if this type is the same
    if($type === $curtype) {
      $result .= '<option value="'.$curtype.'" selected="selected">'.$curtype.'</option>';
    } else {
      $result .= '<option value="'.$curtype.'">'.$curtype.'</option>';
    }
  }

  $result .= '      </select>
                  <li>
                    <label for="notes">Notes (optional)</label>
                    <input type="text" name="notes" id="notes" value="'.$notes.'"
                     placeholder="Environment notes" />
                  </li>
                  <li>
                    <label for="enabled">Enabled</label>
                    <input type="checkbox" name="enabled" id="enabled" value="enabled" '.$enabled.' />
                  </li></ol>
                  <input type="submit" value="Submit" />
                </fieldset>
              </form>';

  return $result;
}
?>
