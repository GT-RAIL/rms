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
if (!isset($_SESSION['userid'])) {
	header('Location: login.php?goto='.substr($_SERVER["SCRIPT_NAME"], 1));
	return;
}

// the name of the admin page
$pagename = "Admin Panel";

// load the include files
include_once('inc/head.inc.php');
include_once('inc/content.inc.php');
include_once('inc/log.inc.php');

// grab the user info from the database
$user = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM user_accounts WHERE userid='".$_SESSION['userid']."'"));
// now make sure this is an admin
if($user['type'] !== "admin") {
	// report this
	write_to_log($user['username']." attempted to use the admin panel.");
	// send the user back to their main menu
	header('Location: main_menu.php');
	return;
}

include_once('admin/content.inc.php');
?>

<!DOCTYPE html>
<html>
<head>
<?php
// grab the header information
import_head();
import_ros_js();
?>
<title><?php echo $title." :: ".$pagename?></title>
<script type="text/javascript" src="js/jquery/jquery.tablesorter.js"></script>

<script type="text/javascript">
	/**
	 * A function to check and set the given ID's HTML if the given ROS host is available.
	 *
	 * @param host {string} the hostname of the rosbridge server
	 * @param id {string} the ID of the 'envstatus' div to set the HTML of
	 */
	function rosonline(host, id) {
		var ros = new ROS("ws://"+host+":9090");
		ros.on('connection', function() {
			$("#envstatus-"+id).html("ONLINE");
			ros.close();
		});
		ros.on('error', function() {
			$("#envstatus-"+id).html("OFFLINE");
		});
	}

	/**
	 * The start function creates all of the JQuery UI elements and button callbacks.
	 */
	function start() {
		// create the user menu 
		create_menu_buttons();

		// create the tabs for the admin page 
		$("#admin-tabs").tabs();

		// create the edit icon buttons 
		$(".edit").button({
            icons: {primary: "ui-icon-pencil"},
            text: false
		});

		// edit button callback 
		$("div.edit").click(function(e) {
			// find the type and ID
			type = $(e.target).parents("div").parents("div").attr('id');
			id = $(e.target).parents("div").attr('id');
			$("#editor-popup").load("admin/popups.php?type=" + type + "&id=" + id);
			$("#editor-popup").dialog("open");
		});

		// create the delete icon buttons 
		$(".delete").button({
		    icons: {primary: "ui-icon-circle-close"},
		    text: false
		});

		// delete button callback 
		$("div.delete").click(function(e) {
			// find the type and ID
			type = $(e.target).parents("div").parents("div").attr('id');
			id = $(e.target).parents("div").attr('id');
			// create a confirm dialog
			confirm = $("#confirm-delete-popup").dialog({
				position: ['center',100],
				draggable: false,
				resizable: false,
				modal: true,
				show: "blind",
				width: 300,
				buttons: {
					No: function() {
						$(this).dialog( "close" );
					},
					Yes: function() {
						$.post("form/admin/delete.php", {type: type, id: id}, 
						function() {
							location.reload();
						});
					}
				},
				autoOpen: false
			});
			confirm.html("<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left; margin:0 7px 50px 0;\"></span>Are you sure you want to delete the selected item? This <b>cannot</b> be reversed.</p>");
			// load the popup
			$("#confirm-delete-popup").dialog("open");
		});

		// create the add icon buttons
		$("button", ".add").button({icons: {primary:'ui-icon-plus'}});

		// creates a popup used to add/edit an entry
		$("#editor-popup").dialog({
			position: ['center',100],
			autoOpen: false,
			draggable: false,
			resizable: false,
			modal: true,
			show: "blind",
			width: 700,
			buttons: {
				Cancel: function() {
					$(this).dialog("close");
				}
			}
		});

		// editor (add new ...) button callback 
		$(".editor").click(function(e) {
			type = $(e.target).parents("div").parents("div").attr('id');
			$("#editor-popup").load("admin/popups.php?type=" + type);
			$("#editor-popup").dialog("open");
		});

		// make the tables sortable 
		$(".tablesorter").tablesorter({ 
			widgets: ['zebra'],
			headers: { 
				// disable the first two columns (delete/edit)
				0:{sorter: false}, 1:{sorter: false} 
			}
    	}); 

		// creates the preview popup
		$("#preview-popup").dialog({
			position: ['center',100],
			autoOpen: false,
			draggable: false,
			resizable: false,
			modal: true,
			show: "blind",
			width: 1050,
			buttons: {
				Close: function() {
					$(this).dialog("close");
				}
			}
		});
	}

	/**
	 * A function to set the HTML of the 'preview-popup' div with the given article content and title.
	 *
	 * @param title {string} the title of the article to preview
	 * @param content {string} the HTML article content to preview
	 */
	function preview(title, content) {
		// create the HTML
		$("#preview-popup").html('<section id="page"><article><h2>'+title+'</h2><div class="line"></div><div class="clear">'+content+'</div></article></section>');
		// open the dialog				
		$("#preview-popup").dialog("open");
	}
</script>

<?php if(isset($_GET['error'])) { // check if an error was made in one of the forms ?>
<script type="text/javascript">
	// error dialog box 
	$(function() {
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

<body onload="start()">
<?php create_header($user, $pagename)?>
	<section id="page">
		<section>
			<div class="line"></div>
			<article>
				<div class="admin-tabs-container">
					<div id="admin-tabs">
						<ul>
							<li><a href="#users-tab">Manage Users</a></li>
							<li><a href="#site-log-tab">Site Log</a></li>
							<li><a href="#environments-tab">Manage Environments</a></li>
							<li><a href="#pages-tab">Manage Pages</a></li>
							<li><a href="#site-tab">Site Settings</a></li>
							<li><a href="#maintenance-tab">Site Maintenance</a></li>
						</ul>
						<?php
						// create each of the divs for the tabs
						create_users();
						create_log();
						create_environments();
						create_pages();
						create_site();
						create_maintenance();
						?>
					</div>
				</div>
			</article>
		</section>
		<?php create_footer()?>
	</section>

	<div id="confirm-delete-popup" title="Delete?"></div>
	<div id="editor-popup" title="Entry Editor"></div>
	<div id="preview-popup" title="Content Preview"></div>

	<?php
	if(isset($_GET['error'])) { ?>
	<div id="error-message" title="Error">
		<p>
			<span class="ui-icon ui-icon-alert"
				style="float: left; margin: 0 7px 50px 0;"></span> Error
			creating/modifying content. Check fields and try again.
		</p>
	</div>
	<?php } ?>
</body>
</html>
