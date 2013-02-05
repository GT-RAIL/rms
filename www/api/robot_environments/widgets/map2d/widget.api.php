<?php
/**
 * Map2D API script. This allows the creation of Map2D widgets using RMS SQL entires. This is used
 * throughout interface creation.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 13 2012
 * @package    api.robot_environments.widgets.map2d
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../../inc/config.inc.php');

/**
 * Get the map array for the map with the given ID, or null if none exist.
 *
 * @param integer $id The map ID number
 * @return array|null An array of the map's SQL entry or null if none exist
 */
function get_map_by_id($id) {
  global $db;

  $sql = sprintf("SELECT * FROM `maps` WHERE `id`='%d'", cleanse($id));
  return mysqli_fetch_assoc(mysqli_query($db, $sql));
}

/**
 * Create the Javascript needed for a given Map2D widget. This will be wrapped in its own <script>
 * tah and should be placed somewhere in your HTML BODY or HEAD section. Note that this returns
 * the HTML, you will need to echo it in the appropriate spot.
 *
 * @param array $map2d The SQL entry for the map we are creating (this can be easily found in the interface's robot_environment object)
 * @return string The HTML to create the map object
 */
function create_map2d($map2d) {
  $continuous = ($map2d['continuous']) ? ', continuous : true' : '';

  $result = '
<script type="text/javascript">
	var map = new Map({
		ros : ros,
		mapTopic : \''.$map2d['topic'].'\'
		'.$continuous.'
	});
</script>
';

  return $result;
}
?>
