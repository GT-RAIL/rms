<?php
/**
 * System log include functions for the RMS API.
 *
 * Allows read and write access to the system log. Used throughout RMS and
 * within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 11 2013
 * @package    api.config.logs
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');

/**
 * A static class to contain the logs.inc.php static functions.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 11 2013
 * @package    api.config.logs
 */
class logs
{
    /**
     * Get an array of all the system log entries, or null if none exist.
     *
     * @return array|null An array of all the system log entries, or null if
     *     none exist
     */
    static function get_logs()
    {
      global $db;
    
      // grab the entries and push them into an array
      $result = array();
      $query = mysqli_query($db, "SELECT * FROM `logs` ORDER BY `logid` DESC");
      while ($cur = mysqli_fetch_assoc($query)) {
        $result[] = $cur;
      }
    
      return (count($result) === 0) ? null : $result;
    }
    
    /**
     * A function that can be used to write a log message to the database.
     *
     * @param string $message the log entry message to put into the database
     */
    static function write_to_log($message)
    {
      global $db;
    
      // don't log an empty message
      if ($message !== '') {
        // insert the message into the database
        $str = "INSERT INTO `logs` (`uri`, `addr`, `entry`) 
                VALUES ('%s','%s','%s')";
        $sql = sprintf(
            $str, api::cleanse($_SERVER['REQUEST_URI']), 
            api::cleanse($_SERVER['REMOTE_ADDR']), api::cleanse($message)
        );
        mysqli_query($db, $sql);
      }
    }
}
