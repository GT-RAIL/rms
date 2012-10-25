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
// start the session
session_start();

// check if a user is logged in
if (!isset($_SESSION['userid'])) {
	header('Location: login.php?goto='.substr($_SERVER["SCRIPT_NAME"], 1));
	return;
}

// load the include files
include('inc/head.inc.php');
include('inc/content.inc.php');

$pagename = "Account Settings";

// grab the user info from the database
$user = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM user_accounts WHERE userid='".$_SESSION['userid']."'"));
?>

<!DOCTYPE html>
<html>
<head>
<?php import_head()// grab the header information ?>
<title><?php echo $title." :: ".$pagename?></title>

<script type="text/javascript">
	create_menu_buttons();
</script>
</head>

<body>
<?php create_header($user, $pagename)?>
	<section id="page">
		<section>
			<div class="line"></div>
			<article>
				<div class="line"></div>
				<div class="center">
					<h2>Coming Soon</h2>
				</div>
				<div class="line"></div>
			</article>
		</section>
		<?php create_footer()?>
	</section>
</body>
</html>

