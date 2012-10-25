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
 *  Version: September 4, 2012
 *
 *********************************************************************/
?>

<?php
// start the session
session_start();

// check if the user is logged in
if (!isset($_SESSION['userid'])) {
	// send them to the home page
	header('Location: ../../index.php');
} else {
	// load the include files
	include($_SERVER['DOCUMENT_ROOT'].'/inc/config.inc.php');
	include($_SERVER['DOCUMENT_ROOT'].'/inc/log.inc.php');

	// grab the user info from the database
	$query = mysqli_query($db, "SELECT * FROM user_accounts WHERE userid = '".$_SESSION['userid']."'");
	$usr = mysqli_fetch_array($query);
	// now make sure this is an admin
	if(strcmp($usr['type'], "admin") != 0) {
		// report this
		write_to_log($usr['username']." attempted to perform a deletion.");
		// send the user back to their main menu
		header('Location: ../../main_menu.php');
	}

	// cleanup the fields
	$id = $db->real_escape_string($_POST['id']);
	$type = $db->real_escape_string($_POST['type']);
	// create the correct type of SQL statement
	if(strcmp($type, "pages") == 0) {
		$sql = "DELETE FROM content_pages WHERE pageid = ".$id;
		// also remove all articles from this page
		remove_articles($id);
	} else if(strcmp($type, "articles") == 0) {
		$sql = "DELETE FROM articles WHERE artid = ".$id;
	} else if(strcmp($type, "interfaces") == 0) {
		$sql = "DELETE FROM interfaces WHERE intid = ".$id;
	} else if(strcmp($type, "environment-interface") == 0) {
		$sql = "DELETE FROM environment_interfaces WHERE pairid = ".$id;
	} else if(strcmp($type, "users") == 0) {
		$sql = "DELETE FROM user_accounts WHERE userid = ".$id;
	} else if(strcmp($type, "slideshow") == 0) {
		$sql = delete_slide($id);
	} else if (strcmp($type, "environments") == 0) {
		$sql = "DELETE FROM environments WHERE envid = ".$id;
		// also remove all widgets from this environment
		remove_widgets($id);
	} else if (strcmp($type, "widgets") == 0) {
		$sql = "DELETE FROM widgets WHERE widgetid = ".$id;
	} else if (strpos($type, "widget-") === 0) {
		$sql = delete_widget_type(substr($type, 7), $id);
	}

	// delete the item
	if($sql) {
		mysqli_query($db, $sql);
		// log this
		write_to_log($usr['username']." deleted from \"".$type."\" with ID ".$id.".");
	} else {
		// log this
		write_to_log($usr['username']." tried to delete an unknown type: \"".$type."\".");
	}
}

/**
 * A function to build and return the SQL needed to delete the given widget type by id.
 */
function delete_widget_type($widgetid, $id) {
	global $db;

	// see if a widget exists with the given id
	$widgid = $db->real_escape_string($widgetid);
	$id_of_widget = $db->real_escape_string($id);
	$sql = "SELECT `table` FROM widgets WHERE widgetid = ".$widgid;
	$query = mysqli_query($db, $sql);
	$widget = mysqli_fetch_array($query);
	$sql = "DELETE FROM ".$widget['table']." WHERE id = ".$id;

	return $sql;
}

/**
 * A function to delete all of the widgets associated with the given environment ID.
 */
function remove_widgets($envid) {
	global $db;

	// grab all widgets that belong to this environment
	$envid = $db->real_escape_string($envid);
	$sql = "SELECT * FROM widgets";
	$query = mysqli_query($db, $sql);
	while($cur = mysqli_fetch_array($query)) {
		// check for matching widgets
		$sql = "SELECT `id` FROM ".$cur['table']." WHERE `envid` = ".$envid;
		$widget_query = mysqli_query($db, $sql);
		while($widget = mysqli_fetch_array($widget_query)) {
			// grab the SQL to delete the current widget
			$sql = delete_widget_type($cur['widgetid'], $widget['id']);
			// delete it
			mysqli_query($db, $sql);
		}
	}
}

/**
 * A function to delete all of the articles associated with the given page ID.
 */
function remove_articles($pageid) {
	global $db;

	// grab all articles that belong to this page
	$pageid = $db->real_escape_string($pageid);
	$sql = "DELETE FROM articles WHERE pageid = ".$pageid;
	mysqli_query($db, $sql);
}

/**
 * A function to delete the image on the server and return the SQL for deleting the entry for the given slide.
 */
function delete_slide($slideid) {
	global $db;

	// grab the image
	$sql = "SELECT img FROM slideshow WHERE slideid = ".$slideid;
	$query = mysqli_query($db, $sql);
	$cur = mysqli_fetch_array($query);
	unlink("../../img/slides/".$cur['img']);

	$sql = "DELETE FROM slideshow WHERE slideid = ".$slideid;
	return $sql;
}
?>

