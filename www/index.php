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
// first check if there is a config file
if(!file_exists('inc/config.inc.php')) {
	header('Location: setup.php');
	return;
}

// start the session
session_start();

// load the include files
include_once('inc/head.inc.php');
include_once('inc/content.inc.php');
include_once('inc/article.inc.php');
include_once('inc/config.inc.php');

// check if there is a user logged in
if (isset($_SESSION['userid'])) {
	// grab the user info from the database
	$user = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM user_accounts WHERE userid='".$_SESSION['userid']."'"));
} else {
	$user = null;
}

// grab the page information
if(!isset($_GET['pageid'])) {
	// simply take the first page
	$page = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM content_pages ORDER BY pageid"));
} else {
	// check if that page exists
	$page = mysqli_fetch_array(mysqli_query($db, sprintf("SELECT * FROM content_pages WHERE pageid=%d", $db->real_escape_string($_GET['pageid']))));
}

// check if we found a valid content page
if(!$page) {
	header('Location: index.php');
	return;
}
// grab the Javascript file that goes with this page
$js = $page['js'];

// check if this is the homepage
$homepage = mysqli_fetch_array(mysqli_query($db, "SELECT pageid FROM content_pages ORDER BY pageid"));
if($page['pageid'] === $homepage['pageid']) {
	$ishome = True;
} else {
	$ishome = False;
}
?>

<!DOCTYPE html>
<html>
<head>
<?php import_head()// grab the header information ?>
<title><?php echo $title." :: ".$page['title']?></title>

<?php if($user) {?>
<script type="text/javascript">
	create_menu_buttons();
</script>
<?php }

// check for extra JS files
if($js) {?>
<script type="text/javascript" src="js/content/<?php echo $js?>"></script>
<?php }

// check for the slideshow
if($ishome) {?>
<script type="text/javascript" src="js/jquery/slides.min.jquery.js"></script>
<script type="text/javascript" src="js/rms/slideshow.js"></script>
<?php }?>
</head>
<body>
<?php create_header($user, $page['title']) // create the header?>
	<section id="page">
	<?php
	// check if this is the homepage
	if($ishome) {
		create_slideshow();
	}
	// put in the content
	create_article_section($page);
	create_footer();
	?>
	</section>
</body>
</html>

