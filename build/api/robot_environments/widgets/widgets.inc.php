<?php
/**
 * Widget include static functions for the RMS API.
 *
 * Allows read and write access to widgets. Used throughout RMS and within the
 * RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 12 2013
 * @package    api.robot_environments.widgets
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../environments/environments.inc.php');

/**
 * A static class to contain the widgets.inc.php static functions.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 12 2013
 * @package    api.robot_environments.widgets
 */
class widgets
{
    /**
     * Check if the given array has all of the necessary fields to create a
     * widget.
     *
     * @param array $array The array to check
     * @return boolean If the given array has all of the necessary fields to
     *     create a new widget
     */
    static function valid_widget_fields($array)
    {
        return isset($array['name']) && isset($array['table']) 
            && (count($array) === 2);
    }

    /**
     * Check if the given array has all of the necessary fields to create a
     * sepecific widget instance. This includes the widget ID number inside the
     * entry 'widgetid'.
     *
     * @param array $array The array to check
     * @return boolean If the given array has all of the necessary fields to
     *     create a new widget instance
     */
    static function valid_widget_instance_fields($array)
    {
        // check if the widget exists
        if (isset($array['widgetid']) 
                && $widget = widgets::get_widget_by_id($array['widgetid'])) {
            // go through each field
            $fields = widgets::get_widget_table_columns_by_id(
                $array['widgetid']
            );
            // check if the numbers match (ignore ID)
            if ($valid = ((count($fields)) === (count($array)))) {
                foreach ($fields as $f) {
                    if ($f !== 'id') {
                        $valid &= isset($array[$f]);
                    }
                }
            }
            return $valid;
        } else {
            return false;
        }
    }

    /**
     * Get an array of all the widget entries, or null if none exist.
     *
     * @return array|null An array of all the widget entries, or null if none
     *     exist
     */
    static function get_widgets()
    {
        global $db;

        // grab the entries and push them into an array
        $result = array();
        $query = mysqli_query($db, "SELECT * FROM `widgets`");
        while ($cur = mysqli_fetch_assoc($query)) {
            $result[] = $cur;
        }

        return (count($result) === 0) ? null : $result;
    }

    /**
     * Get the widget array for the widget with the given ID, or null if none
     * exist.
     *
     * @param integer $id The widget ID number
     * @return array|null An array of the widget's SQL entry or null if none
     *     exist
     */
    static function get_widget_by_id($id)
    {
        global $db;

        $str = "SELECT * FROM `widgets` WHERE `widgetid`='%d'";
        $sql = sprintf($str, api::cleanse($id));
        return mysqli_fetch_assoc(mysqli_query($db, $sql));
    }

    /**
     * Get the column names in the MySQL table for the widget with the given ID,
     * or null if none exist.
     *
     * @param integer $id The widget ID number
     * @return array|null An array of the widget's MySQL table column names or
     *     null if none exist
     */
    static function get_widget_table_columns_by_id($id)
    {
        global $db;

        // check the widget
        if ($w = widgets::get_widget_by_id($id)) {
            $columns = array();
            $sql = sprintf("SHOW COLUMNS FROM `%s`", api::cleanse($w['table']));
            $query = mysqli_query($db, $sql);
            // fill the array
            while ($cur = mysqli_fetch_array($query)) {
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
     * @param integer $widgetid The widget ID number
     * @return array|null An array of the widget's instances or null if none
     *     exist
     */
    static function get_widget_instances_by_widgetid($widgetid)
    {
        global $db;

        // check the widget
        if ($w = widgets::get_widget_by_id($widgetid)) {
            // grab the entries and push them into an array
            $result = array();
            $sql = sprintf("SELECT * FROM `%s`", api::cleanse($w['table']));
            $query = mysqli_query($db, $sql);
            while ($cur = mysqli_fetch_assoc($query)) {
                $result[] = $cur;
            }
            return (count($result) === 0) ? null : $result;
        }

        // none found
        return null;
    }

    /**
     * Get an array of the instances of the given widget for the given
     * environment, or null if none exist.
     *
     * @param integer $widgetid The widget ID number
     * @param integer $envid The environment ID number
     * @return array|null An array of the widget's instances or null if none
     *     exist
     */
    static function get_widget_instances_by_widgetid_and_envid($widgetid,
            $envid)
    {
        global $db;

        // check the widget
        if ($w = widgets::get_widget_by_id($widgetid)) {
            // grab the entries and push them into an array
            $result = array();
            $str = "SELECT * FROM `%s` WHERE `envid`='%d'";
            $sql = sprintf(
                $str, api::cleanse($w['table']), api::cleanse($envid)
            );
            $query = mysqli_query($db, $sql);
            while ($cur = mysqli_fetch_assoc($query)) {
                $result[] = $cur;
            }
            return (count($result) === 0) ? null : $result;
        }

        // none found
        return null;
    }

    /**
     * Get the widget instance array for the widget with the given IDs, or null
     * if none exist.
     *
     * @param integer $widgetid The widget ID number to get the instance from
     * @param integer $id The widget instance ID number
     * @return array|null An array of the widget's SQL entry or null if none
     *     exist
     */
    static function get_widget_instance_by_widgetid_and_id($widgetid, $id)
    {
        global $db;

        // check the widget
        if ($w = widgets::get_widget_by_id($widgetid)) {
            // return what we find
            $result = array();
            $str = "SELECT * FROM `%s` WHERE `id`='%d'";
            $sql = sprintf($str, api::cleanse($w['table']), api::cleanse($id));
            return  mysqli_fetch_assoc(mysqli_query($db, $sql));
        }

        // none found
        return null;
    }

    /**
     * Get the widget array for the widget with the given SQL table, or null if
     * none exist.
     *
     * @param string $table The widget SQL table
     * @return array|null An array of the widgets's SQL entry or null if none
     *     exist
     */
    static function get_widget_by_table($table)
    {
        global $db;

        $str = "SELECT * FROM `widgets` WHERE `table`='%s'";
        $sql = sprintf($str, api::cleanse($table));
        return mysqli_fetch_assoc(mysqli_query($db, $sql));
    }

    /**
     * Create a widget with the given information. Any errors are returned.
     *
     * @param string $name The name of the widget
     * @param string $table The SQL table for the widget
     * @return string|null An error message or null if the create was sucessful
     */
    static function create_widget($name, $table)
    {
        global $db;

        // make sure it does not already exist
        if (widgets::get_widget_by_table($table)) {
            return 'ERROR: Widget with SQL table '.$table.' already exists';
        }

        // now check if those fields are valid
        $tables = widgets::get_unused_widget_tables();
        foreach ($tables as $t) {
            if ($table === $t) {
                // insert into the database
                $str = "INSERT INTO `widgets` 
                        (`name`, `table`) VALUES ('%s', '%s')";
                $sql = sprintf(
                    $str, api::cleanse($name), api::cleanse($table)
                );
                mysqli_query($db, $sql);
                // no error
                return null;
            }
        }
        // table not valid
        return 'ERROR: Widget SQL table '.$table.' is not valid';
    }

    /**
     * Create a widget instance with the given information. The widget ID number
     * should be included in the array. Any errors are returned.
     *
     * @param array $entries The values for the widget creation
     * @return string|null An error message or null if the create was sucessful
     */
    static function create_widget_instance($entries)
    {
        global $db;

        // check the widget
        if ($widget = widgets::get_widget_by_id($entries['widgetid'])) {
            // create the insertion string
            $sql = sprintf(
                "INSERT INTO `%s` (", api::cleanse($widget['table'])
            );
            $insert = "";
            $values = "";
            $fields = widgets::get_widget_table_columns_by_id(
                $entries['widgetid']
            );
            foreach ($fields as $f) {
                // check the environment
                if ($f === 'envid' && !environments::get_environment_by_id(
                    $entries[$f]
                )) {
                    return 'ERROR: Environment ID '.$entries[$f].
                        ' does not exist';
                }
                if ($f !== 'id') {
                    $insert .= sprintf("`%s`, ", api::cleanse($f));
                    $values .= sprintf("'%s', ", api::cleanse($entries[$f]));
                }
            }
            // ignore the last ', '
            $insert = substr($insert, 0, strlen($insert) - 2);
            $values = substr($values, 0, strlen($values) - 2);

            // do the insertion
            mysqli_query($db, $sql.$insert.') VALUES ('.$values.')');

            // no error
            return null;
        }
    }

    /**
     * Update a widget with the given information inside of the array. The array
     * should be indexed by the SQL column names. The ID field must be contained
     * inside of the array with the index 'id'. Any errors are returned.
     *
     * @param array $fields the fields to update including the widget ID number
     * @return string|null an error message or null if the update was sucessful
     */
    static function update_widget($fields)
    {
        global $db;

        if (!isset($fields['id'])) {
            return 'ERROR: ID field missing in update';
        }

        // build the SQL string
        $sql = "";
        $numFields = 0;
        // check for the widget
        if (!($widget = widgets::get_widget_by_id($fields['id']))) {
            return 'ERROR: Widget ID '.$fields['id'].' does not exist';
        }

        // check if we are changing the id
        $idToSet = $widget['widgetid'];
        if (isset($fields['widgetid'])) {
            $numFields++;
            if ($fields['widgetid'] !== $widget['widgetid'] 
                    && widgets::get_widgetid_by_id($fields['widgetid'])) {
                return 'ERROR: Widget ID '.$fields['widgetid'].
                    ' already exists';
            } else {
                $idToSet = $fields['widgetid'];
            }
        }
        $sql .= sprintf(" `widgetid`='%d'", api::cleanse($idToSet));

        // check for each update
        if (isset($fields['name'])) {
            $numFields++;
            $sql .= sprintf(", `name`='%s'", api::cleanse($fields['name']));
        }
        if (isset($fields['table'])) {
            $numFields++;
            if ($fields['table'] !== $widget['table']) {
                if (widgets::get_widget_by_table($fields['table'])) {
                    return 'ERROR: Widget SQL table "'.$fields['table'].
                        '" is already used';
                } else {
                    // now check if those fields are valid
                    $tables = widgets::get_unused_widget_tables();
                    $valid = false;
                    foreach ($tables as $t) {
                        $valid |= ($fields['table'] === $t);
                    }
                    if (!$valid) {
                        // table not valid
                        return 'ERROR: Widget SQL table '.$fields['table'].
                            ' is not valid';
                    }
                }
            }
            $sql .= sprintf(", `table`='%s'", api::cleanse($fields['table']));
        }

        // check to see if there were too many fields
        if ($numFields !== (count($fields) - 1)) {
            return 'ERROR: Too many fields given.';
        } else if ($numFields === 0) {
            // nothing to update
            return null;
        }

        // we can now run the update
        $str = "UPDATE `widgets` SET ".$sql." WHERE `widgetid`='%d'";
        $sql = sprintf($str, api::cleanse($fields['id']));
        mysqli_query($db, $sql);

        // no error
        return null;
    }

    /**
     * Update a widget instance with the given information inside of the array.
     * The array should be indexed by the SQL column names. The ID field must be
     * contained inside of the array with the index 'id' for the instance ID and
     * index 'widgetid` for the widget's ID. Any errors are returned.
     *
     * @param array $fields the fields to update including the widget ID numbers
     * @return string|null an error message or null if the update was sucessful
     */
    static function update_widget_instance($fields)
    {
        global $db;

        if (!isset($fields['id'])) {
            return 'ERROR: ID field missing in update';
        } else if (!isset($fields['widgetid'])) {
            return 'ERROR: Widget ID field missing in update';
        }

        // build the SQL string
        $sql = "";
        $numFields = 0;
        // check for the widget and instance
        if (!($widget = widgets::get_widget_by_id($fields['widgetid']))) {
            return 'ERROR: Widget ID '.$fields['widgetid'].' does not exist';
        } else if (!($instance = widgets::
                get_widget_instance_by_widgetid_and_id(
                    $fields['widgetid'], $fields['id']
                )
        )) {
            return 'ERROR: Widget instance ID '.$fields['id'].
                ' does not exist in Widget ID '.$fields['widgetid'];
        }

        // check if we are changing the id
        $idToSet = $instance['id'];
        if (isset($fields['id'])) {
            $numFields++;
            if ($fields['id'] !== $instance['id'] 
                    && widgets::get_widgetid_by_id($fields['id'])) {
                return 'ERROR: Widget instance ID '.$fields['id'].
                    ' already exists in Widget ID '.$fields['widgetid'];
            } else {
                $idToSet = $fields['id'];
            }
        }
        $sql .= sprintf(" `id`='%d'", api::cleanse($idToSet));

        // check for each update
        $columns = widgets::get_widget_table_columns_by_id($fields['widgetid']);
        foreach ($columns as $c) {
            if ($c !== 'id' && isset($fields[$c])) {
                // check for a valid environment
                if ($c === 'envid' && !environments::get_environment_by_id(
                    $fields[$c]
                )) {
                    return 'ERROR: Environment ID '.$fields[$c].
                        ' does not exist';
                }
                $numFields++;
                $sql .= sprintf(
                    ", `%s`='%s'", api::cleanse($c), api::cleanse($fields[$c])
                );
            }
        }

        // check to see if there were too many fields
        if ($numFields !== (count($fields) - 1)) {
            return 'ERROR: Too many fields given.'.$numFields;
        } else if ($numFields === 0) {
            // nothing to update
            return null;
        }

        // we can now run the update
        $str = "UPDATE `%s` SET ".$sql." WHERE `id`='%d'";
        $sql = sprintf(
            $str, api::cleanse($widget['table']), api::cleanse($fields['id'])
        );
        mysqli_query($db, $sql);

        // no error
        return null;
    }

    /**
     * Delete the widget array for the widget with the given ID. Any errors are
     * returned.
     *
     * @param integer $id The widget ID number
     * @return string|null an error message or null if the delete was sucessful
     */
    static function delete_widget_by_id($id)
    {
        global $db;

        // see if the widget exists
        if (widgets::get_widget_by_id($id)) {
            // delete it
            $str = "DELETE FROM `widgets` WHERE `widgetid`='%d'";
            $sql = sprintf($str, api::cleanse($id));
            mysqli_query($db, $sql);
            // no error
            return null;
        } else {
            return 'ERROR: Widget ID '.$id.' does not exist';
        }
    }

    /**
     * Delete the widget instance array for the widget with the given IDs. Any
     * errors are returned.
     *
     * @param integer $widgetid The widget ID number to delete from
     * @param integer $id The widget instance ID number
     * @return string|null an error message or null if the delete was sucessful
     */
    static function delete_widget_instance_by_widgetid_and_id($widgetid, $id)
    {
        global $db;

        // see if the widget exists
        if ($widget = widgets::get_widget_by_id($widgetid)) {
            // now check the instance
            $table = $widget['table'];
            if (widgets::get_widget_instance_by_widgetid_and_id(
                $widgetid, $id
            )) {
                // delete it
                $sql = sprintf(
                    "DELETE FROM `%s` WHERE `id`='%d'", 
                    api::cleanse($widget['table']), api::cleanse($id)
                );
                mysqli_query($db, $sql);
                // no error
                return null;
            } else {
                return 'ERROR: Widget instance ID '.$id.
                    ' does not exist for widget ID '.$widgetid;
            }
        } else {
            return 'ERROR: Widget ID '.$widgetid.' does not exist';
        }
    }

    /**
     * Get an array of all the unused widget SQL tables or null if none exist.
     * A valid table is any table with an 'envid' field, and a 'label' field.
     *
     * @return array|null An array of the unused widget SQL tables or null if
     *     none exist
     */
    static function get_unused_widget_tables()
    {
        global $db;

        // go through each table
        $valid = array();
        $query = mysqli_query($db, "SHOW TABLES");
        while ($table = mysqli_fetch_array($query)) {
            $sql = sprintf("SHOW COLUMNS FROM `%s`", api::cleanse($table[0]));
            $fieldQuery = mysqli_query($db, $sql);
            $foundEnvid = false;
            $foundLabel = false;
            while ($field = mysqli_fetch_array($fieldQuery)) {
                $foundEnvid |= ($field['Field'] === 'envid');
                $foundLabel |= ($field['Field'] === 'label');
            }
            // check for an unused match
            if ($foundEnvid && $foundLabel 
                    && !widgets::get_widget_by_table($table[0])) {
                $valid[] = $table[0];
            }
        }

        return (count($valid) === 0) ? null : $valid;
    }

    /**
     * Get the HTML for an editor used to create or edit the given widget entry.
     * If this is not an edit, null can be given as the ID.
     *
     * @param integer|null $id the ID of the widget to edit, or null if a new
     *     entry is being made
     * @return string A string containing the HTML of the editor
     */
    static function get_widget_editor($id = null)
    {
        // see if an widget exists with the given id
        $cur = widgets::get_widget_by_id($id);

        if ($cur) {
            $table = $cur['table'];
            $name = $cur['name'];
        } else {
            $table = '';
            $name = '';
        }

        $result = '
            <p>Complete the following form to create or edit a widget.</p>
            <form action="javascript:submit();">
            <fieldset>
            <ol>';

        // only show the ID for edits
        $result .=  ($cur) ? 
                    '<li><label for="id">Widget ID</label>
                         <input type="text" name="id" id="id" value="'.
                         $cur['widgetid'].'" readonly="readonly" /></li>' 
                     : '';


        $result .= '<li>
                <label for="name">Name</label>
                <input type="text" name="name" id="name" value="'.$name.'"
                        placeholder="e.g., My Cool Interface" required />
                        </li>
                        <li>';

        $disabled = '';
        // check for unused tables
        $tables = widgets::get_unused_widget_tables();
        if (strlen($table) > 0) {
            $tables = ($tables) ? $tables : array();
            $tables[] = $table;
        }
        if ($tables) {
            $result .= '<label for="table">SQL Table</label>
                    <select name="table" id="table" required>';
            // put in each option
            foreach ($tables as $t) {
                if ($table === $t) {
                    $result .= '<option value="'.$t.'" selected="selected">'.
                        $t.'</option>';
                } else {
                    $result .= '<option value="'.$t.'">'.$t.'</option>';
                }
            }
        } else {
            // put dummy dropdown in
            $result .= '<label for="table-dummy">SQL Table</label>
                    <select name="table-dummy" id="table-dummy" disabled="true">
                    <option value="void">No unused valid SQL tables found
                    </option>';
            $disabled = 'disabled="disabled" ';
        }
        $result .= '  </select>
                </li>
                </ol>
                <input type="submit" value="Submit" '.$disabled.'/>
                        </fieldset>
                        </form>';

        return $result;
    }

    /**
     * Get the HTML for an editor used to create or edit the given widget
     * instance entry. If this is not an edit, null can be given as the ID.
     *
     * @param integer|null $widgetid the ID of the widget to get the instance
     *     editor for
     * @param integer|null $id the ID of the widget instance to edit, or null if
     *     a new entry is being made
     * @return string A string containing the HTML of the editor
     */
    static function get_widget_instance_editor_by_widgetid($widgetid, 
            $id = null)
    {
        // check if widget ID
        if (!($widget = widgets::get_widget_by_id($widgetid))) {
            return '<h2>Invalid Widget ID '.$widgetid.'</h2>';
        }

        $result = '
                <p>Complete the following form to create or edit a(n) '.
                    $widget['name'].'.</p>
                <form action="javascript:submit();">
                <fieldset>
                <ol>
                <li><label for="widgetid">Widget ID</label>
                    <input type="text" name="widgetid" id="widgetid" value="'.
                        $widgetid.'" readonly="readonly" />';

        $fields = widgets::get_widget_table_columns_by_id($widgetid);
        // check if there is a widget
        if (!($w = widgets::get_widget_instance_by_widgetid_and_id(
            $widgetid, $id
        ))) {
            // put in empty fields
            $w = array();
            foreach ($fields as $f) {
                $w[$f] = '';
            }
            $result .= '</li>';
        } else {
            // only show the ID for edits
            $result .=  '<label for="id">Instance ID</label>
                        <input type="text" name="id" id="id"
                         value="'.$w['id'].'" readonly="readonly" /></li>';
        }

        // put in each field
        foreach ($w as $field => $value) {
            // check if this is the environment ID
            if ($field === 'envid') {
                $result .= '<li>
                        <label for="envid">Environment</label>
                        <select name="envid" id="envid" required>';
                // grab the environments
                $environments = environments::get_environments();
                foreach ($environments as $cur) {
                    // check if this environment is the same
                    if ($value === $cur['envid']) {
                        $result .= '<option value="'.$cur['envid'].
                            '" selected="selected">'.$cur['envid'].": ".
                            $cur['envaddr'].'</option>';
                    } else {
                        $result .= '<option value="'.$cur['envid'].'">'.
                            $cur['envid'].": ".$cur['envaddr'].'</option>';
                    }
                }
                $result .= '  </select>
                        </li>';
            } else if ($field !== 'id') {
                // later we can add cases for booleans and such
                $result .= '
                        <li>
                            <label for="'.$field.'">'.$field.'</label>
                            <input type="text" name="'.$field.'" value="'.
                                $value.'" id="'.$field.'" required />
                        </li>';
            }
        }

        $result .= '</select>
                </li>
                </ol>
                <input type="submit" value="Submit" />
                </fieldset>
                </form>';

        return $result;
    }
}
