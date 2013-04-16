<?php
/**
 * Environment include static functions for the RMS API.
 *
 * Allows read and write access to environments. Used throughout RMS and within
 * the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 15 2013
 * @package    api.robot_environments.environments
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../../api.inc.php');

/**
 * A static class to contain the interfaces.inc.php static functions.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 15 2013
 * @package    api.robot_environments.environments
 */
class environments
{
    /**
     * Check if the given array has all of the necessary fields to create an
     * environment.
     *
     * @param array $array The array to check
     * @return boolean If the given array has all of the necessary fields to
     *     create a new environment
     */
    static function valid_environment_fields($array)
    {
        return isset($array['protocol']) && isset($array['envaddr']) 
            && isset($array['mjpeg']) && isset($array['mjpegport']) 
            && isset($array['enabled']) && isset($array['port'])
            && (count($array) === 6);
    }

    /**
     * Get an array of all the environment entries, or null if none exist.
     *
     * @return array|null An array of all the environment entries, or null if
     *     none exist
     */
    static function get_environments()
    {
        global $db;

        // grab the entries and push them into an array
        $result = array();
        $query = mysqli_query($db, "SELECT * FROM `environments`");
        while ($cur = mysqli_fetch_assoc($query)) {
            $result[] = $cur;
        }

        return (count($result) === 0) ? null : $result;
    }

    /**
     * Get the environment array for the environment with the given ID, or null
     *     if none exist.
     *
     * @param integer $id The environment ID number
     * @return array|null An array of the environment's SQL entry or null if
     *     none exist
     */
    static function get_environment_by_id($id)
    {
        global $db;

        $sql = sprintf(
            "SELECT * FROM `environments` WHERE `envid`='%d'", 
            api::cleanse($id)
        );
        return mysqli_fetch_assoc(mysqli_query($db, $sql));
    }

    /**
     * Create an environment with the given information. Any errors are
     * returned.
     *
     * @param string $protocol The protocol to use
     * @param string $envaddr The environment's address
     * @param integer $port The environment's port
     * @param string $mjpeg The MJPEG server host
     * @param string $mjpegport The MJPEG server port
     * @param integer $enabled If the environment is currently enabled
     * @return string|null An error message or null if the create was sucessful
     */
    static function create_environment($protocol, $envaddr, $port, $mjpeg,
            $mjpegport, $enabled)
    {
        global $db;

        // insert into the database
        $sql = sprintf(
            "INSERT INTO `environments`
              (`protocol`, `envaddr`, `port`, `mjpeg`, `mjpegport`, `enabled`)
             VALUES
              ('%s', '%s', '%d', '%s', '%s', '%d')", 
            api::cleanse($protocol), api::cleanse($envaddr), 
            api::cleanse($port), api::cleanse($mjpeg), 
            api::cleanse($mjpegport), api::cleanse($enabled)
        );
        mysqli_query($db, $sql);

        // no error
        return null;
    }

    /**
     * Update an environment with the given information inside of the array. The
     * array should be indexed by the SQL column names. The ID field must be 
     * contained inside of the array with the index 'id'. Any errors are
     * returned.
     *
     * @param array $fields the fields to update including the environment ID
     *     number
     * @return string|null an error message or null if the update was sucessful
     */
    static function update_environment($fields)
    {
        global $db;

        if (!isset($fields['id'])) {
            return 'ERROR: ID field missing in update';
        }

        // build the SQL string
        $sql = "";
        $numFields = 0;
        // check for the user
        if (!($environment = environments::get_environment_by_id(
            $fields['id']
        ))) {
            return 'ERROR: Environment ID '.$fields['id'].' does not exist';
        }

        // check if we are changing the id
        $idToSet = $environment['envid'];
        if (isset($fields['envid'])) {
            $numFields++;
            if ($fields['envid'] !== $environment['envid'] 
                    && environments::get_environment_by_id($fields['envid'])) {
                return 'ERROR: Environment ID '.$fields['envid'].
                    ' already exists';
            } else {
                $idToSet = $fields['envid'];
            }
        }
        $sql .= sprintf(" `envid`='%d'", api::cleanse($idToSet));

        // check for each update
        if (isset($fields['protocol'])) {
            $numFields++;
            $sql .= sprintf(
                ", `protocol`='%s'", api::cleanse($fields['protocol'])
            );
        }
        if (isset($fields['envaddr'])) {
            $numFields++;
            $sql .= sprintf(
                ", `envaddr`='%s'", api::cleanse($fields['envaddr'])
            );
        }
        if (isset($fields['port'])) {
            $numFields++;
            $sql .= sprintf(
                ", `port`='%d'", api::cleanse($fields['port'])
            );
        }
        if (isset($fields['mjpeg'])) {
            $numFields++;
            $sql .= sprintf(
                ", `mjpeg`='%s'", api::cleanse($fields['mjpeg'])
            );
        }
        if (isset($fields['mjpegport'])) {
            $numFields++;
            $sql .= sprintf(
                ", `mjpegport`='%s'", api::cleanse($fields['mjpegport'])
            );
        }
        if (isset($fields['enabled'])) {
            $numFields++;
            $sql .= sprintf(
                ", `enabled`='%d'", api::cleanse($fields['enabled'])
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
        $sql = sprintf(
            "UPDATE `environments` SET ".$sql." WHERE `envid`='%d'",
            api::cleanse($fields['id'])
        );
        mysqli_query($db, $sql);

        // no error
        return null;
    }

    /**
     * Delete the environment array for the environment with the given ID. Any
     * errors are returned.
     *
     * @param integer $id The environment ID number
     * @return string|null an error message or null if the delete was sucessful
     */
    static function delete_environment_by_id($id)
    {
        global $db;

        // see if the environment exists
        if (environments::get_environment_by_id($id)) {
            // delete it
            $sql = sprintf(
                "DELETE FROM `environments` WHERE `envid`='%d'",
                api::cleanse($id)
            );
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
    static function get_protocol_types()
    {
        return api::get_enum_types('environments', 'protocol');
    }

    /**
     * Get the HTML for an editor used to create or edit the given environment
     * entry. If this is not an edit, null can be given as the ID.
     *
     * @param integer|null $id the ID of the environment to edit, or null if a
     *     new entry is being made
     * @return string A string containing the HTML of the editor
     */
    static function get_environment_editor($id = null)
    {
        // see if an environment exists with the given id
        $cur = environments::get_environment_by_id($id);

        if ($cur) {
            $protocol = $cur['protocol'];
            $envaddr = $cur['envaddr'];
            $port = $cur['port'];
            $mjpeg = $cur['mjpeg'];
            $mjpegport = $cur['mjpegport'];
            $enabled = ($cur['enabled'] === '0') ? '' : 'checked';
        } else {
            $protocol = '';
            $envaddr = '';
            $port = '';
            $mjpeg = '';
            $mjpegport = '';
            $enabled = 'checked';
        }

        $result = '
            <p>Complete the following form to create or edit an environment.</p>
            <form action="javascript:submit();">
                <fieldset>
                    <ol>';

        // only show the ID for edits
        $result .=  ($cur) ? '
                        <li><label for="id">Environment ID</label>
                            <input type="text" name="id" d="id" value="'.
                             $cur['envid'].'" readonly="readonly" /></li>' 
                         : '';


        // grab the protocols
        $result .= '<li>
                <label for="protocol">Protocol</label>
                <select name="protocol" id="protocol" required>';
        $protocols = environments::get_protocol_types();
        foreach ($protocols as $curprot) {
            // check if this type is the same
            if ($protocol === $curprot) {
                $result .= '<option value="'.$curprot.'" selected="selected">'.
                    $curprot.'</option>';
            } else {
                $result .= '<option value="'.$curprot.'">'.$curprot.'</option>';
            }
        }

        $result .= ' 
                </select>
                </li>
                <li>
                <label for="envaddr">Address</label>
                <input type="text" name="envaddr" id="envaddr" value="'.
                    $envaddr.'" placeholder="e.g., myrobot.robot-college.edu" 
                required />
                </li>
                <li>
                <label for="port">Port</label>
                <input type="text" name="port" id="port" value="'.$port.'"
                    placeholder="e.g., 9090" required />
                </li>
                <li>
                <label for="mjpeg">MJPEG Server</label>
                <input type="text" name="mjpeg" id="mjpeg" value="'.$mjpeg.'"
                    placeholder="e.g., streams.robot-college.edu" required />
                </li>
                <li>
                <label for="mjpegport">MJPEG Port</label>
                <input type="text" name="mjpegport" id="mjpegport" value="'.
                    $mjpegport.'" placeholder="e.g., 8080" required />
                </li>
                <li>
                <label for="enabled">Enabled</label>
                <input type="checkbox" name="enabled" id="enabled" 
                    value="enabled" '.$enabled.' />
                </li></ol>
            <input type="submit" value="Submit" />
            </fieldset>
            </form>';

        return $result;
    }
}
