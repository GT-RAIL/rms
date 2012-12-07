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
 * Check if the given array has all of the necessary fields to create an environment.
 *
 * @param array $array The array to check
 * @return boolean If the given array has all of the necessary fields to create a new environment
 */
function valid_environment_fields($array) {
  return isset($array['protocol']) && isset($array['envaddr']) && isset($array['type'])
  && isset($array['notes']) && isset($array['enabled']) && isset($array['port'])
  && (count($array) === 6);
}

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
 * Create an environment with the given information. Any errors are returned.
 *
 * @param string $protocol The protocol to use
 * @param string $envaddr The environment's address
 * @param integer $port The environment's port
 * @param string $type The the environment type
 * @param string $notes Any notes about the environment
 * @param integer $enabled If the environment is currently enabled
 * @return string|null An error message or null if the create was sucessful
 */
function create_environment($protocol, $envaddr, $port, $type, $notes, $enabled) {
  global $db;

  // insert into the database
  $sql = sprintf("INSERT INTO `environments`
                 (`protocol`, `envaddr`, `port`, `type`, `notes`, `enabled`)
                 VALUES
                 ('%s', '%s', '%d', '%s', '%s', '%d')", $db->real_escape_string($protocol),
  $db->real_escape_string($envaddr), $db->real_escape_string($port), $db->real_escape_string($type),
  $db->real_escape_string($notes), $db->real_escape_string($enabled));
  mysqli_query($db, $sql);

  // no error
  return null;
}

/**
 * Update an environment with the given information inside of the array. The array should be indexed
 * by the SQL column names. The ID field must be contained inside of the array with the index 'id'.
 * Any errors are returned.
 *
 * @param array $fields the fields to update including the environment ID number
 * @return string|null an error message or null if the update was sucessful
 */
function update_environment($fields) {
  global $db;

  if(!isset($fields['id'])) {
    return 'ERROR: ID filed missing in update';
  }

  // build the SQL string
  $sql = "";
  $num_fields = 0;
  // check for the user
  if(!($environment = get_environment_by_id($fields['id']))) {
    return 'ERROR: Environment ID '.$id.' does not exist';
  }

  // check if we are changing the id
  $id_to_set = $environment['envid'];
  if(isset($fields['envid'])) {
    $num_fields++;
    if($fields['envid'] !== $environment['envid'] && get_environment_by_id($fields['envid'])) {
      return 'ERROR: Environment ID '.$fields['envid'].' already exists';
    } else {
      $id_to_set = $fields['envid'];
    }
  }
  $sql .= sprintf(" `envid`='%d'", $db->real_escape_string($id_to_set));

  // check for each update
  if(isset($fields['protocol'])) {
    $num_fields++;
    $sql .= sprintf(", `protocol`='%s'", $db->real_escape_string($fields['protocol']));
  }
  if(isset($fields['envaddr'])) {
    $num_fields++;
    $sql .= sprintf(", `envaddr`='%s'", $db->real_escape_string($fields['envaddr']));
  }
  if(isset($fields['port'])) {
    $num_fields++;
    $sql .= sprintf(", `port`='%d'", $db->real_escape_string($fields['port']));
  }
  if(isset($fields['type'])) {
    $num_fields++;
    $sql .= sprintf(", `type`='%s'", $db->real_escape_string($fields['type']));
  }
  if(isset($fields['notes'])) {
    $num_fields++;
    $sql .= sprintf(", `notes`='%s'", $db->real_escape_string($fields['notes']));
  }
  if(isset($fields['enabled'])) {
    $num_fields++;
    $sql .= sprintf(", `enabled`='%d'", $db->real_escape_string($fields['enabled']));
  }

  // check to see if there were too many fields or if we do not need to update
  if($num_fields !== (count($fields) - 1)) {
    return 'ERROR: Too many fields given.';
  } else if ($num_fields === 0) {
    // nothing to update
    return null;
  }

  // we can now run the update
  $sql = sprintf("UPDATE `environments` SET ".$sql." WHERE `envid`='%d'"
  , $db->real_escape_string($fields['id']));
  mysqli_query($db, $sql);

  // no error
  return null;
}

/**
 * Delete the environment array for the environment with the given ID. Any errors are returned.
 *
 * @param integer $id The environment ID number
 * @return string|null an error message or null if the delete was sucessful
 */
function delete_environment_by_id($id) {
  global $db;

  // see if the environment exists
  if(get_environment_by_id($id)) {
    // delete it
    $sql = sprintf("DELETE FROM `environments` WHERE `envid`='%d'", $db->real_escape_string($id));
    mysqli_query($db, $sql);
    // no error
    return null;
  } else {
    return 'ERROR: Environment ID '.$id.' does not exist';
  }
}

/**
 * Get the enum types for protocols in an array.
 *
 * @return array An array containing the enum types for protocols
 */
function get_protocol_types() {
  return get_enum_types('environments', 'protocol');
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
    $protocol = $cur['protocol'];
    $envaddr = $cur['envaddr'];
    $port = $cur['port'];
    $type = $cur['type'];
    $notes = $cur['notes'];
    $enabled = ($cur['enabled'] === '0') ? '' : 'checked';
  } else {
    $protocol = '';
    $envaddr = '';
    $port = '';
    $type = '';
    $notes = '';
    $enabled = 'checked';
  }

  $result = '<p>Complete the following form to create or edit an environment.</p>
             <form action="javascript:submit();">
               <fieldset>
                 <ol>';

  // only show the ID for edits
  $result .=  ($cur) ? '<li><label for="id">Environment ID</label><input type="text" name="id"
                             id="id" value="'.$cur['envid'].'" readonly="readonly" /></li>' : '';


  // grab the protocols
  $result .= '<li>
                <label for="protocol">Protocol</label>
                <select name="protocol" id="protocol" required>';
  $protocols = get_protocol_types();
  foreach ($protocols as $curprot) {
    // check if this type is the same
    if($type === $curprot) {
      $result .= '<option value="'.$curprot.'" selected="selected">'.$curprot.'</option>';
    } else {
      $result .= '<option value="'.$curprot.'">'.$curprot.'</option>';
    }
  }

  $result .= '  </select>
              </li>
              <li>
              <label for="envaddr">Address</label>
              <input type="text" name="envaddr" id="envaddr" value="'.$envaddr.'"
               placeholder="e.g., myrobot.robot-college.edu" required />
            </li>
            <li>
              <label for="port">Port</label>
              <input type="text" name="port" id="port" value="'.$port.'"
              placeholder="e.g., 9090" required />
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
                  </li>
                  <li>
                    <label for="notes">Notes</label>
                    <input type="text" name="notes" id="notes" value="'.$notes.'"
                     placeholder="Environment notes" required />
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
