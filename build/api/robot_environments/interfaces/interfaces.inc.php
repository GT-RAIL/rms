<?php
/**
 * Interface include static functions for the RMS API.
 *
 * Allows read and write access to interfaces. Used throughout RMS and within
 * the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 12 2013
 * @package    api.robot_environments.interfaces
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');

/**
 * A static class to contain the interfaces.inc.php static functions.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 12 2013
 * @package    api.robot_environments.interfaces
*/
class interfaces
{
    /**
     * Check if the given array has all of the necessary fields to create an
     * interface.
     *
     * @param array $array The array to check
     * @return boolean If the given array has all of the necessary fields to
     *     create a new interface
     */
    static function valid_interface_fields($array)
    {
        return isset($array['name']) && isset($array['location']) 
            && (count($array) === 2);
    }

    /**
     * Get an array of all the interface entries, or null if none exist.
     *
     * @return array|null An array of all the interface entries, or null if
     *     none exist
     */
    static function get_interfaces()
    {
        global $db;

        // grab the entries and push them into an array
        $result = array();
        $query = mysqli_query($db, "SELECT * FROM `interfaces`");
        while ($cur = mysqli_fetch_assoc($query)) {
            $result[] = $cur;
        }

        return (count($result) === 0) ? null : $result;
    }

    /**
     * Get the interface array for the interface with the given ID, or null if
     * none exist.
     *
     * @param integer $id The interface ID number
     * @return array|null An array of the interface's SQL entry or null if none
     *     exist
     */
    static function get_interface_by_id($id)
    {
        global $db;

        $str = "SELECT * FROM `interfaces` WHERE `intid`='%d'";
        $sql = sprintf($str, api::cleanse($id));
        return mysqli_fetch_assoc(mysqli_query($db, $sql));
    }

    /**
     * Get the interface array for the interface with the given location, or
     * null if none exist.
     *
     * @param string $location The interface location inside of the
     *     api/robot_environments/interfaces directory
     * @return array|null An array of the interface's SQL entry or null if none
     *     exist
     */
    static function get_interface_by_location($location)
    {
        global $db;

        $str = "SELECT * FROM `interfaces` WHERE `location`='%s'";
        $sql = sprintf($str, api::cleanse($location));
        return mysqli_fetch_assoc(mysqli_query($db, $sql));
    }

    /**
     * Create an interface with the given information. Any errors are returned.
     *
     * @param string $name The name of the interface
     * @param string $location The location inside of
     *     api/robot_environments/interfaces
     * @return string|null An error message or null if the create was sucessful
     */
    static function create_interface($name, $location)
    {
        global $db;

        // make sure it does not already exist
        if (interfaces::get_interface_by_location($location)) {
            return 'ERROR: Interface with location '.$location.
                ' already exists';
        }
        // now check if that location is valid
        $locations = interfaces::get_unused_interface_locations();
        foreach ($locations as $l) {
            if ($location === $l) {
                // insert into the database
                $str = "INSERT INTO `interfaces` (`name`, `location`) 
                        VALUES ('%s', '%s')";
                $sql = sprintf(
                    $str, api::cleanse($name), api::cleanse($location)
                );
                mysqli_query($db, $sql);

                // no error
                return null;
            }
        }

        // location not valid
        return 'ERROR: Interface location '.$location.' is not valid';
    }

    /**
     * Update an interface with the given information inside of the array. The
     * array should be indexed by the SQL column names. The ID field must be
     * contained inside of the array with the index 'id'. Any errors are
     * returned.
     *
     * @param array $fields the fields to update including the interface ID
     *     number
     * @return string|null an error message or null if the update was sucessful
     */
    static function update_interface($fields)
    {
        global $db;

        if (!isset($fields['id'])) {
            return 'ERROR: ID field missing in update';
        }

        // build the SQL string
        $sql = "";
        $numFields = 0;
        // check for the interface
        if (!($interface = interfaces::get_interface_by_id($fields['id']))) {
            return 'ERROR: Interface ID '.$fields['id'].' does not exist';
        }

        // check if we are changing the id
        $idToSet = $interface['intid'];
        if (isset($fields['intid'])) {
            $numFields++;
            if ($fields['intid'] !== $interface['intid'] 
                    && interfaces::get_interface_by_id($fields['intid'])) {
                return 'ERROR: Interface ID '.$fields['intid'].
                    ' already exists';
            } else {
                $idToSet = $fields['intid'];
            }
        }
        $sql .= sprintf(" `intid`='%d'", api::cleanse($idToSet));

        // check for each update
        if (isset($fields['name'])) {
            $numFields++;
            $sql .= sprintf(", `name`='%s'", api::cleanse($fields['name']));
        }
        if (isset($fields['location'])) {
            $numFields++;
            if ($fields['location'] !== $interface['location'] 
                    && interfaces::get_interface_by_location(
                        $fields['location']
                    )
            ) {
                return 'ERROR: Interface location "'.$fields['location'].
                    '" is already used';
            }
            $sql .= sprintf(
                ", `location`='%s'", api::cleanse($fields['location'])
            );
        }

        // check to see if there were too many fields
        if ($numFields !== (count($fields) - 1)) {
            return 'ERROR: Too many fields given.';
        } else if ($numFields === 0) {
            // nothing to update
            return null;
        }

        // we can now run the update
        $str = "UPDATE `interfaces` SET ".$sql." WHERE `intid`='%d'";
        $sql = sprintf($str, api::cleanse($fields['id']));
        mysqli_query($db, $sql);

        // no error
        return null;
    }

    /**
     * Delete the interface array for the interface with the given ID. Any
     * errors are returned.
     *
     * @param integer $id The interface ID number
     * @return string|null an error message or null if the delete was sucessful
     */
    static function delete_interface_by_id($id)
    {
        global $db;

        // see if the interface exists
        if (interfaces::get_interface_by_id($id)) {
            // delete it
            $str = "DELETE FROM `interfaces` WHERE `intid`='%d'";
            $sql = sprintf($str, api::cleanse($id));
            mysqli_query($db, $sql);
            // no error
            return null;
        } else {
            return 'ERROR: Interface ID '.$id.' does not exist';
        }
    }

    /**
     * Get an array of all the unused interface directories inside of the
     * api/robot_environments/interfaces directory or null if none exist.
     *
     * @return array|null An array of the unused interface directories or null
     *     if none exist
     */
    static function get_unused_interface_locations()
    {
        // check for unused interface folders
        $dir  = opendir(dirname(__FILE__));
        $files = array();
        while ($f = readdir($dir)) {
            // check if it is a file or a directory and is already not used
            if (is_dir(dirname(__FILE__).'/'.$f) && $f[0] !== '.' 
                    && !interfaces::get_interface_by_location($f)) {
                $files[] = $f;
            }
        }

        return (count($files) === 0) ? null : $files;
    }

    /**
     * Get the HTML for an editor used to create or edit the given interface
     * entry. If this is not an
     * edit, null can be given as the ID.
     *
     * @param integer|null $id the ID of the interface to edit, or null if a new
     *     entry is being made
     * @return string A string containing the HTML of the editor
     */
    static function get_interface_editor($id = null)
    {
        // see if an interface exists with the given id
        $cur = interfaces::get_interface_by_id($id);

        if ($cur) {
            $name = $cur['name'];
            $location = $cur['location'];
        } else {
            $name = '';
            $location = '';
        }

        $result = '
            <p>Complete the following form to create or edit an interface.</p>
            <form action="javascript:submit();">
            <fieldset>
            <ol>';

        // only show the ID for edits
        $result .=  ($cur) ? '
                        <li><label for="id">Interface ID</label>
                        <input type="text" name="id" id="id" value="'.
                         $cur['intid'].'" readonly="readonly" /></li>' 
                        : '';

        $result .= '
                <li>
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" value="'.$name.'"
                     placeholder="e.g., My Cool Interface" required />
                    </li>
                <li>';

        // check for unused locations
        $locations = interfaces::get_unused_interface_locations();
        if (strlen($location) > 0) {
            $locations = ($locations) ? $locations : array();
            $locations[] = $location;
        }
        if ($locations) {
            $result .= '<label for="location">Location</label>
                    <select name="location" id="location" required>';
            // put in each option
            foreach ($locations as $l) {
                if ($location === $l) {
                    $result .= '<option value="'.$l.'" selected="selected">'.
                        $l.'</option>';
                } else {
                    $result .= '<option value="'.$l.'">'.$l.'</option>';
                }
            }
            $result .= '    </select>
                    </li>
                    </ol>
                    <input type="submit" value="Submit" />';
        } else {
            // put dummy dropdown in
            $result .= '
            <label for="location-dummy">Location</label>
            <select name="location-dummy" id="location-dummy" 
                    disabled="disabled">
                <option value="void">No unused locations found in 
                      "api/robot_environments/interfaces"</option>
            </select>
            </li>
        </ol>
        <input type="submit" value="Submit" disabled="true" />';
        }

        $result .= '</fieldset>
                </form>';

        return $result;
    }
}
