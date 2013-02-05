<?php
/**
 * Update functions for the RMS API.
 *
 * The update include script has functions for updating the RMS database. These should only be used
 * through the run_database_update function inside of config.inc.php.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    February, 5 2013
 * @package    api.config
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../inc/config.inc.php');

/**
 * Update the RMS database from version 2.0.0 to version 2.1.0.
 *
 * @return string|null an error message or null if the update was sucessful
 */
function update_0_2_0() {
	global $db;

	// upgrade the JS files to groovy-devel
	$query = mysqli_query($db, "SELECT * FROM `javascript_files` WHERE `url` like '%fuerte-devel%'");
	while($cur = mysqli_fetch_assoc($query)) {
		$new_url = str_replace('fuerte-devel', 'groovy-devel', $cur['url']);
		$sql = sprintf("UPDATE `javascript_files` SET `url`='%s' WHERE `fileid`='%d'"
		, cleanse($new_url), cleanse($cur['fileid']));
		// try and do the update
		if(!mysqli_query($db, $sql)) {
			return mysqli_error($db);
		}
	}

	// update the database version
	if(!mysqli_query($db, "UPDATE `version` SET `version`='0.2.1' WHERE `version`='0.2.0'")) {
		return mysqli_error($db);
	} else {
		return null;
	}
}
?>
