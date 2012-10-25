<?php
/*********************************************************************
 *
 * Software License Agreement (BSD License)
 *
 *  Copyright (c) 2012, Worcester Polytechnic Institute
 *  All rights reserved.
 *
 *  Redistribution and use in source and binary forms, with or without
 *  modification, are permitted provided that the following conditions
 *  are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *   * Redistributions in binary form must reproduce the above
 *     copyright notice, this list of conditions and the following
 *     disclaimer in the documentation and/or other materials provided
 *     with the distribution.
 *   * Neither the name of the Worcester Polytechnic Institute nor the
 *     names of its contributors may be used to endorse or promote
 *     products derived from this software without specific prior
 *     written permission.
 *
 *  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 *  "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 *  LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 *  FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 *  INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 *  BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 *  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 *  CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 *  LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 *  ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 *  POSSIBILITY OF SUCH DAMAGE.
 *
 *   Author: Russell Toris
 *  Version: September 12, 2012
 *
 *********************************************************************/
?>

<?php
// check if this is an admin panel request
if(isset($_POST['req'])) {
	// start the session
	session_start();

	// load the include files
	include_once($_SERVER['DOCUMENT_ROOT'].'/inc/config.inc.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/inc/log.inc.php');

	// grab the user info from the database
	$sql ="SELECT * FROM user_accounts WHERE userid = '".$_SESSION['userid']."'";
	$query = mysqli_query($db, $sql);
	$usr = mysqli_fetch_array($query);
	// now make sure this is an admin
	if(strcmp($usr['type'], "admin") != 0) {
		// report this
		write_to_log($usr['username']." attempted to use the setup script.");
		// send the user back to their main menu
		header('Location: ../../main_menu.php');
	}

	// it is an admin, check the request type
	if(strcmp($_POST['req'], "reconfig") == 0) {
		// check if the passwords match
		if(strcmp($_POST['dbpass'], $_POST['dbpass-confirm']) != 0) {
			// set an error message and return to the admin panel
			$_SESSION['error'] = "Passwords did not match. Site modification failed.";
			header('Location: ../../admin.php#site-tab');
		} else {
			// cleanup the request
			$host = $db->real_escape_string($_POST['host']);
			$dbuser = $db->real_escape_string($_POST['dbuser']);
			// check for a new password
			if(strcmp($_POST['dbpass'], "**********") != 0)
			$dbpass = $db->real_escape_string($_POST['dbpass']);
			$dbname = $db->real_escape_string($_POST['db']);
			$site_name = $db->real_escape_string($_POST['site-name']);
			$google = $db->real_escape_string($_POST['google']);
			$copyright = $db->real_escape_string($_POST['copyright']);

			// remove the old file
			unlink('../../inc/config.inc.php');
			// create the new file
			create_config($host, $dbuser, $dbpass, $dbname, $site_name, $google, $copyright);

			// log this
			write_to_log($usr['username']." modified the configuration file.");
			// send them back to the admin panel
			header('Location: ../../admin.php#site-tab');
		}
	} else if(strcmp($_POST['req'], "update") == 0) {
		// cleanup the old files
		cleanup($db);
		// get the new files
		download_all($db);
		
		// log this
		write_to_log($usr['username']." updated the Javascript files.");

		// send them back to the admin panel
		header('Location: ../../admin.php#maintenance-tab');
	}
}
else if(file_exists('../../inc/config.inc.php')) {
	// only work if the config file is indeed missing
	header('Location: ../index.php');
} else {
	// upload the database
	$db = upload_db($_POST['host'], $_POST['dbuser'], $_POST['dbpass'], $_POST['db'], $_FILES['sqlfile']['tmp_name']);

	// write the configuration file
	create_config($_POST['host'], $_POST['dbuser'], $_POST['dbpass'], $_POST['db'], $_POST['site-name'], $_POST['google'], $_POST['copyright']);

	//cleanup anything from a bad install
	cleanup($db);
	// grab the JS files
	download_all($db);

	// give the database some time to update and then return home
	sleep(2);
	header('Location: ../../index.php');
}

/*
 * Remove any files that were downloaded from the installation.
 */
function cleanup($db) {
	// grab the Javascript file list
	$sql = "SELECT * FROM javascript_files";
	$query = mysqli_query($db, $sql);
	while ($file = mysqli_fetch_array($query)) {
		if(file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$file['path'])) {
			unlink($_SERVER['DOCUMENT_ROOT'].'/'.$file['path']);
		}
	}
}

/*
 * Download the necessary files for the RMS.
 */
function download_all($db) {
	// grab the Javascript file list
	$sql = "SELECT * FROM javascript_files";
	$query = mysqli_query($db, $sql);
	while ($file = mysqli_fetch_array($query)) {
		download_file($file['url'], $_SERVER['DOCUMENT_ROOT'].'/'.$file['path']);
	}
}

/*
 * Download a file from the given source URL to the given destination.
 */
function download_file($src, $dst) {
	// download the file using curl
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $src);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec ($curl);
	curl_close ($curl);

	// save the file locally
	$file = fopen($dst, "w+") or DIE("Error: could not write file '".$dst."'. Check folder permisions and try again.");
	fputs($file, $data);
	fclose($file);
}

/*
 * Create the config.inc.php file based on the form data provided.
 */
function create_config($dbhost, $dbuser, $dbpass, $dbname, $title, $google_tracking_id, $copyright) {
	// create the file
	$f = fopen($_SERVER['DOCUMENT_ROOT']."/inc/config.inc.php", 'w') or DIE("Error: could not create file inside of folder 'inc'. Check folder permisions and try again.");

	// put in the header
	$today = getdate();
	fwrite($f, "<?php\n");
	fwrite($f, "/*********************************************************************\n");
	fwrite($f, " *\n");
	fwrite($f, " * Software License Agreement (BSD License)\n");
	fwrite($f, " *\n");
	fwrite($f, " *  Copyright (c) 2012, Worcester Polytechnic Institute\n");
	fwrite($f, " *  All rights reserved.\n");
	fwrite($f, " *\n");
	fwrite($f, " *  Redistribution and use in source and binary forms, with or without\n");
	fwrite($f, " *  modification, are permitted provided that the following conditions\n");
	fwrite($f, " *  are met:\n");
	fwrite($f, " *\n");
	fwrite($f, " *   * Redistributions of source code must retain the above copyright\n");
	fwrite($f, " *     notice, this list of conditions and the following disclaimer.\n");
	fwrite($f, " *   * Redistributions in binary form must reproduce the above\n");
	fwrite($f, " *     copyright notice, this list of conditions and the following\n");
	fwrite($f, " *     disclaimer in the documentation and/or other materials provided\n");
	fwrite($f, " *     with the distribution.\n");
	fwrite($f, " *   * Neither the name of the Worcester Polytechnic Institute nor the \n");
	fwrite($f, " *     names of its contributors may be used to endorse or promote\n");
	fwrite($f, " *     products derived from this software without specific prior\n");
	fwrite($f, " *     written permission.\n");
	fwrite($f, " *\n");
	fwrite($f, " *  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS\n");
	fwrite($f, " *  \"AS IS\" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT\n");
	fwrite($f, " *  LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS\n");
	fwrite($f, " *  FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE\n");
	fwrite($f, " *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,\n");
	fwrite($f, " *  INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,\n");
	fwrite($f, " *  BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;\n");
	fwrite($f, " *  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER\n");
	fwrite($f, " *  CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT\n");
	fwrite($f, " *  LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN\n");
	fwrite($f, " *  ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE\n");
	fwrite($f, " *  POSSIBILITY OF SUCH DAMAGE.\n");
	fwrite($f, " *\n");
	fwrite($f, " *   Author: Auto Generated via Setup Script\n");
	fwrite($f, " *  Version: ".$today['month']." ".$today['mday'].", ".$today['year']."\n");
	fwrite($f, " *\n");
	fwrite($f, " *********************************************************************/\n");
	fwrite($f, "?>\n\n");

	fwrite($f, "<?php\n");
	// database info
	fwrite($f, "\t// database information\n");
	fwrite($f, "\t\$dbhost = \"".$dbhost."\";\n");
	fwrite($f, "\t\$dbuser = \"".$dbuser."\";\n");
	fwrite($f, "\t\$dbpass = \"".$dbpass."\";\n");
	fwrite($f, "\t\$dbname = \"".$dbname."\";\n");
	fwrite($f, "\t\$db = mysqli_connect(\$dbhost, \$dbuser, \$dbpass, \$dbname) or DIE(\"Connection has failed. Please try again later.\");\n\n");

	// google tracking ID
	fwrite($f, "\t// Google Analytics tracking ID -- unset if no tracking is being used.\n");
	if(strlen($google_tracking_id) > 0) {
		fwrite($f, "\t\$google_tracking_id = \"".$google_tracking_id."\";\n\n");
	} else {
		fwrite($f, "\t\$google_tracking_id;\n\n");
	}

	// copyright and design
	fwrite($f, "\t// site copyright and design information\n");
	fwrite($f, "\t\$copyright = \"&copy ".$copyright."\";\n");
	fwrite($f, "\t\$title = \"".$title."\";\n");
	fwrite($f, "\t// original site design information\n");
	fwrite($f, "\t\$designed_by = \"Site design by <a href=\\\"http://users.wpi.edu/~rctoris/\\\">Russell Toris</a>\";\n");
	fwrite($f, "?>\n");

	// close the file
	fclose($f);
}

/*
 * Uploads either the initial, example database file or a backup SQL file to the database and return the database object.
 */
function upload_db($dbhost, $dbuser, $dbpass, $dbname, $fileupload) {
	// connect to the database
	$db = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or DIE("Error: MySQL connection has failed.");
	// load in the SQL file
	if($fileupload) {
		$sql = file_get_contents($fileupload) or DIE("Error: Could not load uploaded SQL file.");
	} else {
		$sql = file_get_contents($_SERVER['DOCUMENT_ROOT']."/admin/init.sql") or DIE("Error: SQL file missing.");
		mysqli_multi_query($db, $sql);
		echo mysqli_error($db);
	}

	return $db;
}
?>

