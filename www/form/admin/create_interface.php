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
if (!isset($_SESSION['userid']))
// send them to the home page
header('Location: ../../index.php');
else {
	// load the include files
	include($_SERVER['DOCUMENT_ROOT'].'/inc/config.inc.php');
	include($_SERVER['DOCUMENT_ROOT'].'/inc/log.inc.php');

	// grab the user info from the database
	$query = mysqli_query($db, "SELECT * FROM user_accounts WHERE userid = '".$_SESSION['userid']."'");
	$usr = mysqli_fetch_array($query);
	// now make sure this is an admin
	if(strcmp($usr['type'], "admin") != 0) {
		// report this
		write_to_log($usr['username']." attempted to create an interface.");
		// send the user back to their main menu
		header('Location: ../../main_menu.php');
	}

	// cleanup the fields
	$intid = $db->real_escape_string($_POST['intid']);
	$name = $db->real_escape_string($_POST['name']);
	$location = $db->real_escape_string($_POST['location']);

	// see if this is an update or a new widget
	if($intid) {
		// update the database
		$sql = "UPDATE interfaces SET `name` = '".$name."', `location` = '".$location."' WHERE intid = ".$intid;
		// log this
		write_to_log($usr['username']." updated interface \"".$name."\".");
	} else {
		// insert into the database
		$sql = "INSERT INTO interfaces (`name`, `location`) VALUES ('".$name."', '".$location."')";
		// log this
		write_to_log($usr['username']." created a new interface: \"".$name."\".");
	}

	mysqli_query($db, $sql);

	// send them back to the admin panel
	header('Location: ../../admin.php#environments-tab');
}
?>

