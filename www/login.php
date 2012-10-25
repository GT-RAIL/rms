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

// check if there is a user logged in
if (isset($_SESSION['userid'])) {
	header('Location: main_menu.php');
	return;
}

// load the include files
include_once('inc/head.inc.php');
include_once('inc/content.inc.php');
?>

<!DOCTYPE html>
<html>
<head>
<?php import_head()// grab the header information ?>
<title><?php echo $title." :: User Login" ?></title>

<?php if(isset($_GET['error'])) { // check if an invalid user tried to login ?>
<script type="text/javascript">
	$(function() {
		// Authentication Failure dialog box 
		$("#error-message").dialog({
			position: ['center',100],
			draggable: false,
			resizable: false,
			modal: true,
			show: "blind",
			hide: "puff",
			autoOpen: true,
			buttons: {
				// close button
				Close: function() {
					$(this).dialog("close");
				}
			}
		});
	});
</script>
<?php }?>

</head>
<body>
<?php create_header(NULL, "Login") ?>
	<section id="page">
		<article>
			<h2>User Login</h2>
			<div class="line"></div>
			<table class="center">
				<tr>
					<td>
						<form action="form/login.php" method="POST">
							<fieldset>
								<input type="hidden" name="goto" id="goto"
									value="<?php echo (isset($_GET['goto']) ? $_GET['goto'] : "")?>"
									readonly="readonly" />
								<ol>
									<li><label for="username">Username</label> <input type="text"
										name="username" id="username" required autofocus />
									</li>
									<li><label for="password">Password</label> <input
										type="password" name="password" id="password" required />
									</li>
								</ol>
								<input type="submit" value="Login" />
							</fieldset>
						</form>
					</td>
				</tr>
			</table>
		</article>
		<?php create_footer() ?>
	</section>
	<?php
	if(isset($_GET['error'])) {?>
	<div id="error-message" title="Authentication Failure">
		<p>
			<span class="ui-icon ui-icon-alert"
				style="float: left; margin: 0 7px 50px 0;"></span> Invalid username
			and/or password!
		</p>
	</div>
	<?php
	}?>
</body>
</html>

