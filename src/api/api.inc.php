<?php
/**
 * Common include static functions for the RMS API.
 *
 * A set of useful static functions used within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 11 2013
 * @package    api
 * @link       http://ros.org/wiki/rms
 */

/**
 * A static class to contain the api.inc.php functions.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 11 2013
 * @package    api
 */
class api
{
    /**
     * The default password text holder.
     * @var string
     */
    static $passwordHolder = '***********';
    
    /**
     * Cleanse the given input string for use in an SQL query. This will perform
     * things such as escape character checks and HTML scrubbing (if enabled).
     * By default, the string is formated for use in an sprintf static function
     * (i.e., percetange signs are esacped by '%%').
     *
     * @param string $input The input string to cleanse
     * @param boolean $html If HTML scrubbing (conversion to HTML entities) 
     *     should be performed (default = true)
     * @param boolean $sprintf If percentage signs should be esacped for use in
     *     a sprintf static function (default = true)
     * @return string The cleansed string
     */
    static function cleanse($input, $html = true, $sprintf = true)
    {
        global $db;
    
        if ($html) {
            $cleansed = htmlentities($input, ENT_QUOTES);
        } else {
            $cleansed = $input;
        }
    
        // trim the string
        $cleansed = trim($cleansed);
        // run a escape check
        $cleansed = $db->real_escape_string($cleansed);
    
        if ($sprintf) {
            // escape percentage signs
            $cleansed = str_replace('%', '%%', $cleansed);
        }
    
        return $cleansed;
    }
    
    /**
     * Creates a default 404 state in an array. This includes a 'false' in the
     * 'ok' element, the given error message in the 'msg' element, and null in
     * the 'data' element. The header is also changed to the 404 state.
     *
     * @param string $msg The message to put in the array
     * @return array The filled array
     */
    static function create_404_state($msg)
    {
        $resultArray = array();
    
        $resultArray['ok'] = false;
        $resultArray['msg'] = $msg;
        $resultArray['data'] = null;
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
    
        return $resultArray;
    }
    
    /**
     * Creates a default 401 state in an array. This includes a 'false' in the
     * 'ok' element, 'Insufficient authorization.' in the 'msg' element, and
     * null in the 'data' element. The header is also changed to the 401 state.
     *
     * @return array The filled array
     */
    static function create_401_state()
    {
        $resultArray = array();
    
        $resultArray['ok'] = false;
        $resultArray['msg'] = 'Insufficient authorization.';
        $resultArray['data'] = null;
        header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized', true, 401);
    
        return $resultArray;
    }
    
    /**
     * Creates a default 200 state in an array. This includes a 'true' in the
     * 'ok' element, 'Success.' in the 'msg' element, and $data in the 'data'
     * element. The header is also changed to the 200 state.
     *
     * @param object $data The data to place in the 'data' element of the array
     * @return array The filled array
     */
    static function create_200_state($data)
    {
        $resultArray = array();
    
        $resultArray['ok'] = true;
        $resultArray['msg'] = 'Success.';
        $resultArray['data'] = $data;
    
        header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK', true, 200);
        return $resultArray;
    }
    
    /**
     * Get the enum types for the given column in the given table in an array.
     *
     * @param string $table the name of the MySQL table
     * @param string $column the column name inside of the given table
     * @return array An array containing the enum types for the given column in
     *     the given table
     */
    static function get_enum_types($table, $column)
    {
        global $db;
    
        $str = "SELECT `column_type` FROM `information_schema`.
                `columns` WHERE `table_name`='%s' AND column_name='%s'";
        $sql = sprintf($str, api::cleanse($table), api::cleanse($column));
    
        $enums = mysqli_fetch_row(mysqli_query($db, $sql));
        $search = array("enum('", "')", "''");
        $replace = array('', '', "'");
        return explode("','", str_replace($search, $replace, $enums[0]));
    }
    
    /**
     * Get the current timestamp from the MySQL server.
     *
     * @return string The current timestamp from the MySQL server
     */
    static function get_current_timestamp()
    {
        global $db;
    
        $sql = "SELECT CURRENT_TIMESTAMP()";
        $time = mysqli_fetch_array(mysqli_query($db, $sql));
        return $time['CURRENT_TIMESTAMP()'];
    }
}

/**
 * Create the delete array for the REST API.
 * @var array
 */
$deleteArray = array();
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents('php://input'), $deleteArray);
}

/**
 * Create the _PUT array for the REST API.
 * @var array
 */
$putArray = array();
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents('php://input'), $putArray);
}
