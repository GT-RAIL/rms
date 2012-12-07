<?php
/**
 * Widget include functions for the RMS API.
 *
 * Allows read and write access to widgets. Used throughout RMS and within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 7 2012
 * @package    api.robot_environments.widgets
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');

/**
 * Check if the given array has all of the necessary fields to create a widget.
 *
 * @param array $array The array to check
 * @return boolean If the given array has all of the necessary fields to create a new widget
 */
function valid_widget_fields($array) {
  return isset($array['name']) && isset($array['table']) && isset($array['script']) && (count($array) === 3);
}

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

/**
 * Get the widget array for the widget with the given script location, or null if none exist.
 *
 * @param string $script The widget script location inside of the api/robot_environments/widgets directory
 * @return array|null An array of the widgets's SQL entry or null if none exist
 */
function get_widget_by_script($script) {
  global $db;

  $sql = sprintf("SELECT * FROM `widgets` WHERE `script`='%s'", $db->real_escape_string($script));
  return mysqli_fetch_assoc(mysqli_query($db, $sql));
}

/**
 * Get the widget array for the widget with the given SQL table, or null if none exist.
 *
 * @param string $table The widget SQL table
 * @return array|null An array of the widgets's SQL entry or null if none exist
 */
function get_widget_by_table($table) {
  global $db;

  $sql = sprintf("SELECT * FROM `widgets` WHERE `table`='%s'", $db->real_escape_string($table));
  return mysqli_fetch_assoc(mysqli_query($db, $sql));
}

/**
 * Create an environment with the given information. Any errors are returned.
 *
 * @param string $name The name of the widget
 * @param string $table The SQL table for the widget
 * @param integer $script The PHP script location
 * @return string|null An error message or null if the create was sucessful
 */
function create_widget($name, $table, $script) {
  global $db;

  // make sure it does not already exist
  if(get_widget_by_table($table)) {
    return 'ERROR: Widget with SQL table '.$table.' already exists';
  } else if(get_widget_by_script($script)) {
    return 'ERROR: Widget with script location '.$script.' already exists';
  }

  // now check if those fields are valid
  $tables = get_unused_widget_tables();
  foreach ($tables as $t) {
    if($table === $t) {
      $scripts = get_unused_widget_scripts();
      foreach ($scripts as $s) {
        if($script === $s) {
          // insert into the database
          $sql = sprintf("INSERT INTO `widgets` (`name`, `table`, `script`) VALUES ('%s', '%s', '%s')",
          $db->real_escape_string($name), $db->real_escape_string($table), $db->real_escape_string($script));
          mysqli_query($db, $sql);

          // no error
          return null;
        }
      }
      // script not valid
      return 'ERROR: Widget script location '.$script.' is not valid';
    }
  }
  // table not valid
  return 'ERROR: Widget SQL table '.$table.' is not valid';
}

/**
 * Update a widget with the given information inside of the array. The array should be indexed
 * by the SQL column names. The ID field must be contained inside of the array with the index 'id'.
 * Any errors are returned.
 *
 * @param array $fields the fields to update including the widget ID number
 * @return string|null an error message or null if the update was sucessful
 */
function update_widget($fields) {
  global $db;

  if(!isset($fields['id'])) {
    return 'ERROR: ID filed missing in update';
  }

  // build the SQL string
  $sql = "";
  $num_fields = 0;
  // check for the interface
  if(!($widget = get_widget_by_id($fields['id']))) {
    return 'ERROR: Widget ID '.$id.' does not exist';
  }

  // check if we are changing the id
  $id_to_set = $widget['widgetid'];
  if(isset($fields['widgetid'])) {
    $num_fields++;
    if($fields['widgetid'] !== $widget['widgetid'] && get_widgetid_by_id($fields['widgetid'])) {
      return 'ERROR: Widget ID '.$fields['widgetid'].' already exists';
    } else {
      $id_to_set = $fields['widgetid'];
    }
  }
  $sql .= sprintf(" `widgetid`='%d'", $db->real_escape_string($id_to_set));

  // check for each update
  if(isset($fields['name'])) {
    $num_fields++;
    $sql .= sprintf(", `name`='%s'", $db->real_escape_string($fields['name']));
  }
  if(isset($fields['table'])) {
    $num_fields++;
    if($fields['table'] !== $widget['table']) {
      if(get_widget_by_table($fields['table'])) {
        return 'ERROR: Widget SQL table "'.$fields['table'].'" is already used';
      } else {
        // now check if those fields are valid
        $tables = get_unused_widget_tables();
        $valid = false;
        foreach ($tables as $t) {
          $valid |= ($fields['table'] === $t);
        }
        if(!$valid) {
          // script not valid
          return 'ERROR: Widget SQL table '.$fields['table'].' is not valid';
        }
      }
    }
    $sql .= sprintf(", `table`='%s'", $db->real_escape_string($fields['table']));
  }
  if(isset($fields['script'])) {
    $num_fields++;
    if($fields['script'] !== $widget['script']) {
      if(get_widget_by_script($fields['script'])) {
        return 'ERROR: Widget script location "'.$fields['script'].'" is already used';
      } else {
        // now check if those fields are valid
        $scripts = get_unused_widget_scripts();
        $valid = false;
        foreach ($scripts as $s) {
          $valid |= ($fields['script'] === $s);
        }
        if(!$valid) {
          // script not valid
          return 'ERROR: Widget script location '.$fields['script'].' is not valid';
        }
      }
    }
    $sql .= sprintf(", `script`='%s'", $db->real_escape_string($fields['script']));
  }

  // check to see if there were too many fields or if we do not need to update
  if($num_fields !== (count($fields) - 1)) {
    return 'ERROR: Too many fields given.';
  } else if ($num_fields === 0) {
    // nothing to update
    return null;
  }

  // we can now run the update
  $sql = sprintf("UPDATE `widgets` SET ".$sql." WHERE `widgetid`='%d'"
  , $db->real_escape_string($fields['id']));
  mysqli_query($db, $sql);

  // no error
  return null;
}

/**
 * Delete the widget array for the widget with the given ID. Any errors are returned.
 *
 * @param integer $id The widget ID number
 * @return string|null an error message or null if the delete was sucessful
 */
function delete_widget_by_id($id) {
  global $db;

  // see if the interface exists
  if($widget = get_widget_by_id($id)) {
    // delete it
    $sql = sprintf("DELETE FROM `widgets` WHERE `widgetid`='%d'", $db->real_escape_string($id));
    mysqli_query($db, $sql);
    // no error
    return null;
  } else {
    return 'ERROR: Widget ID '.$id.' does not exist';
  }
}

/**
 * Get an array of all the unused widget scripts inside of the api/robot_environments/widgets
 * directory or null if none exist.
 *
 * @return array|null An array of the unused widget directory scripts or null if none exist
 */
function get_unused_widget_scripts() {
  // check for unused interface folders
  $dir  = opendir(dirname(__FILE__));
  $files = array();
  while ($f = readdir($dir)) {
    // check if it is a file or a directory and is already not used
    if(is_dir(dirname(__FILE__).'/'.$f) && $f[0] !== '.' && !get_widget_by_script($f)) {
      $files[] = $f;
    }
  }

  return (count($files) === 0) ? null : $files;
}

/**
 * Get an array of all the unused widget SQL tables or null if none exist. A valid table is any
 * table with an 'envid' field, and a 'label' field.
 *
 * @return array|null An array of the unused widget SQL tables or null if none exist
 */
function get_unused_widget_tables() {
  global $db;

  // go through each table
  $valid = array();
  $query = mysqli_query($db, "SHOW TABLES");
  while($table = mysqli_fetch_array($query)) {
    $sql = sprintf("SHOW COLUMNS FROM `%s`", $db->real_escape_string($table[0]));
    $field_query = mysqli_query($db, $sql);
    $found_envid = false;
    $found_label = false;
    while($field = mysqli_fetch_array($field_query)) {
      $found_envid |= ($field['Field'] === 'envid');
      $found_label |= ($field['Field'] === 'label');
    }
    // check for an unused match
    if($found_envid && $found_label && !get_widget_by_table($table[0])) {
      $valid[] = $table[0];
    }
  }

  return (count($valid) === 0) ? null : $valid;
}

/**
 * Get the HTML for an editor used to create or edit the given widget entry. If this is not an
 * edit, null can be given as the ID.
 *
 * @param integer|null $id the ID of the widget to edit, or null if a new entry is being made
 * @return string A string containing the HTML of the editor
 */
function get_widget_editor_html($id) {
  // see if an widget exists with the given id
  $cur = get_widget_by_id($id);

  if($cur) {
    $script = $cur['script'];
    $table = $cur['table'];
    $name = $cur['name'];
  } else {
    $script = '';
    $table = '';
    $name = '';
  }

  $result = '<p>Complete the following form to create or edit a widget.</p>
             <form action="javascript:submit();">
               <fieldset>
                 <ol>';

  // only show the ID for edits
  $result .=  ($cur) ? '<li><label for="id">Widget ID</label><input type="text" name="id"
                             id="id" value="'.$cur['widgetid'].'" readonly="readonly" /></li>' : '';


  $result .= '<li>
              <label for="name">Name</label>
              <input type="text" name="name" id="name" value="'.$name.'"
               placeholder="e.g., My Cool Interface" required />
            </li>
            <li>';

  $disabled = '';
  // check for unused tables
  $tables = get_unused_widget_tables();
  if(strlen($table) > 0) {
    $tables = ($tables) ? $tables : array();
    $tables[] = $table;
  }
  if($tables) {
    $result .= '<label for="table">SQL Table</label>
                <select name="table" id="table" required>';
    // put in each option
    foreach($tables as $t) {
      if($table === $t) {
        $result .= '<option value="'.$t.'" selected="selected">'.$t.'</option>';
      } else {
        $result .= '<option value="'.$t.'">'.$t.'</option>';
      }
    }
  } else {
    // put dummy dropdown in
    $result .= '<label for="table-dummy">SQL Table</label>
                <select name="table-dummy" id="table-dummy" disabled="true">
                  <option value="void">No unused valid SQL tables found</option>';
    $disabled = 'disabled="disabled" ';
  }
  $result .= '  </select>
              </li>
              <li>';

  // check for unused script locations
  $scripts = get_unused_widget_scripts();
  if(strlen($script) > 0) {
    $scripts = ($scripts) ? $scripts : array();
    $scripts[] = $script;
  }
  if($scripts) {
    $result .= '<label for="script">PHP Script Location</label>
                <select name="script" id="script" required>';
    // put in each option
    foreach($scripts as $s) {
      if($script === $s) {
        $result .= '<option value="'.$s.'" selected="selected">'.$s.'</option>';
      } else {
        $result .= '<option value="'.$s.'">'.$s.'</option>';
      }
    }
  } else {
    // put dummy dropdown in
    $result .= '<label for="script-dummy">PHP Script Location</label>
                <select name="script-dummy" id="script-dummy" disabled="true">
                  <option value="void">No unused script locations found in "api/robot_environments/widgets"</option>';
    $disabled = 'disabled="disabled" ';
  }

  $result .= '        </select>
                    </li>
                  </ol>
                <input type="submit" value="Submit" '.$disabled.'/>
                </fieldset>
              </form>';

  return $result;
}
?>
