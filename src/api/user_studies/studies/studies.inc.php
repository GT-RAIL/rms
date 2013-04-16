<?php
/**
 * Study include static functions for the RMS API.
 *
 * Allows read and write access to study entries via PHP static function calls.
 * Used throughout RMS and within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 12 2013
 * @package    api.user_studies.studies
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');

/**
 * A static class to contain the studies.inc.php static functions.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 12 2013
 * @package    api.user_studies.studies
 */
class studies
{
    /**
     * Get an array of all study entires in the database or null if none exist.
     *
     * @return array|null The array of study entries or null if none exist.
    */
    static function get_studies()
    {
        global $db;
    
        // grab the javascript entries and push them into an array
        $result = array();
        $query = mysqli_query($db, "SELECT * FROM `studies`");
        while ($cur = mysqli_fetch_assoc($query)) {
            $result[] = $cur;
        }
    
        return (count($result) === 0) ? null : $result;
    }
    
    /**
     * Get the study array for the entry with the given ID, or null if none
     * exist.
     *
     * @param integer $id The study ID number
     * @return array|null An array of the study's SQL entry or null if none
     *     exist
     */
    static function get_study_by_id($id)
    {
        global $db;
    
        // grab the article
        $str = "SELECT * FROM `studies` WHERE `studyid`='%d'";
        $sql = sprintf($str, api::cleanse($id));
        return mysqli_fetch_assoc(mysqli_query($db, $sql));
    }
}
