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
 *  Version: October 9, 2012
 *
 *********************************************************************/
?>

<?php
session_start();
include_once('../inc/log.inc.php');

$ERROR_INVALID_CREDNTIALS = 1;

// cleanup the username and password
$query = mysqli_query($db, sprintf("SELECT * FROM user_accounts WHERE (username='%s') AND (password='%s')", $db->real_escape_string($_POST['username']), md5($db->real_escape_string($_POST['password']))));
if (mysqli_num_rows($query) == 1) {
	$user = mysqli_fetch_array($query);
	// log the successful login
	write_to_log($user['username']." has logged in.", $db);

	// create the session variable
	$_SESSION['userid'] = $user['userid'];

	// check if a header location was specified
	if(strlen($_POST['goto']) > 0) {
		// head to the location
		header('Location: ../'.$_POST['goto']);
	} else {
		header('Location: ../main_menu.php');
	}
} else {
	// log the invalid login
	write_to_log("Invalid credentials using '".$_POST['username']."'", $db);
	header('Location: ../login.php?error='.$ERROR_INVALID_CREDNTIALS);
}
?>
