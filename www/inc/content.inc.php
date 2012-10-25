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
 *  Version: October 8, 2012
 *
 *********************************************************************/
?>

<?php
include_once('config.inc.php');

/**
 * A function to echo the HTML for the main header based on the given user
 * and page name. If the user is an admin, the admin menus will be shown.
 */
function create_header($user_row, $pagename) {
	global $title, $db;?>

<header class="clear"> <figure> <img src="img/logo.png" /></figure> <hgroup>
<h1>
<?php echo $title?>
</h1>
<h2>
<?php echo $pagename?>
</h2>
</hgroup> </header>
<div id="nav">
	<center>
		<nav>
		<ul>
		<?php
		// list all of the content pages
		$query = mysqli_query($db, "SELECT * FROM content_pages ORDER BY menu_index");
		while($cur = mysqli_fetch_array($query)) {?>
			<li><a href="index.php?pageid=<?php echo $cur['pageid']?>"><?php echo $cur['menu_name']?>
			</a></li>
			<?php
		}
		// add the login page if the user is not logged in
		if(!$user_row) {?>
			<li><a href="login.php">Login</a></li>
			<?php
		}?>
		</ul>
		</nav>
	</center>
</div>

		<?php if($user_row) { // the user menu?>
<header class="clear">
<table>
	<tr>
		<td align="left"><div id="page_name">
				<h3>
				<?php echo $user_row['firstname']." ".$user_row['lastname']?>
				</h3>
			</div></td>
		<td align="right">
			<div id="user_menu">
				<span class="menu_main_menu"><a href="main_menu.php">Main Menu</a> </span>&nbsp;
				<span class="menu_account"><a href="account.php">Account</a> </span>&nbsp;

				<?php if(strcmp($user_row['type'], "admin") == 0) { // check if this is an admin?>
				<span class="menu_admin_panel"><a href="admin.php">Admin Panel</a> </span>&nbsp;
				<span class="menu_study_panel"><a href="study.php">Study Panel</a> </span>&nbsp;
				<?php
				}?>
				<span class="menu_logout"><a href="form/logout.php">Logout</a> </span>&nbsp;
			</div>
		</td>
	</tr>
</table>
</header>
				<?php
		}
}

/**
 * A function to echo the HTML for the page footer.
 */
function create_footer() {
	global $designed_by, $copyright;?>

<footer>
<div class="line"></div>
<table>
	<tr>
		<td align="left"><?php echo $designed_by?></td>
		<td align="right"><?php echo $copyright?></td>
	</tr>
</table>
</footer>
	<?php
}
?>

