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
// check if the setup has already finished
if(file_exists('inc/config.inc.php')) {
	header('Location: index.php');
	return;
}

include('inc/head.inc.php');
?>

<!DOCTYPE html>
<html>
<head>
<?php import_common_css()// grab the css information ?>
<title>Robot Management System Setup</title>
</head>
<body>
	<header class="clear">
		<div class="center">
			<h1>Robot Management System Setup</h1>
		</div>
	</header>
	<section id="page">
		<div class="line"></div>
		<article>
			<div class="center">
				<form action="../form/admin/setup.php" method="POST"
					enctype="multipart/form-data">
					<fieldset>
						<br />
						<h3>MySQL Database Information</h3>
						<ol>
							<li><label for="host">Database Host</label> <input type="text"
								name="host" id="host" placeholder="e.g., localhost" required />
							</li>
							<li><label for="db">Database Name</label> <input type="text"
								name="db" id="db" placeholder="e.g., my_remote_lab" required />
							</li>
							<li><label for="dbuser">Database Username</label> <input
								type="text" name="dbuser" id="dbuser" placeholder="username"
								required />
							</li>
							<li><label for="dbpass">Database Password</label> <input
								type="password" name="dbpass" id="dbpass" placeholder="password"
								required />
							</li>
						</ol>
					</fieldset>
					<fieldset>
						<br />
						<h3>Site Information</h3>
						<ol>
							<li><label for="site-name">Site Name</label> <input type="text"
								name="site-name" id="site-name"
								placeholder="e.g., My Awesome Title" required />
							</li>
							<li><label for="google">Google Analytics Tracking ID (optional)</label>
								<input type="text" name="google" id="google"
								placeholder="(optional)" />
							</li>
							<li><label for="copyright">Copyright Message</label> <input
								type="text" name="copyright" id="copyright"
								placeholder="e.g., 2012 My Robot College" required />
							</li>
						</ol>
					</fieldset>
					<fieldset>
						<br />
						<h3>SQL Database Restoration (optional)</h3>
						<ol>
							<li><label for="sqlfile">SQL Backup File (optional)</label> <input
								type="file" name="sqlfile" id="sqlfile" />
							</li>
						</ol>
					</fieldset>
					<input type="submit" value="Create" />
				</form>
			</div>
		</article>
	</section>
</body>
</html>

