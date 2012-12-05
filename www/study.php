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
  // send them to the login page
  header('Location: /login?goto='.substr($_SERVER["SCRIPT_NAME"], 1));
  return;
}

// the name of the admin page
$pagename = "Study Panel";

// load the include files
include_once('inc/head.inc.php');
include_once('inc/content.inc.php');
include_once('inc/log.inc.php');

// grab the user info from the database
$user = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM user_accounts WHERE userid='".$_SESSION['userid']."'"));
// now make sure this is an admin
if($user['type'] !== "admin") {
  // report this
  write_to_log($user['username']." attempted to use the study panel.");
  // send the user back to their main menu
  header('Location: menu/');
  return;
}

include_once('study/content.inc.php');
?>

<!DOCTYPE html>
<html>
<head>
<?php
// grab the header information
import_head();
import_common_js();
?>
<title><?php echo $title." :: ".$pagename?></title>
<script type="text/javascript" src="js/jquery/jquery.tablesorter.js"></script>

<script type="text/javascript">

	/**
	 * The start function creates all of the JQuery UI elements and button callbacks.
	 */
	function start() {
		// create the user menu
		createMenuButtons();

		// create the tabs for the study page
		$("#study-tabs").tabs();

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
			$("#editor-popup").html("<center><h2>Coming Soon</h2></center>");
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
						$(this).dialog( "close" );
					}
				},
				autoOpen: false
			});
			confirm.html("<center><h2>Coming Soon</h2></center>");
			// load the popup
			$("#confirm-delete-popup").dialog("open");
		});

		// create the add icon buttons
		$("button", ".add").button({icons: {primary:'ui-icon-plus'}});

		// create a popup used to add/edit an entry
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
			$("#editor-popup").html("<center><h2>Coming Soon</h2></center>");
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

		// a function to create the preview popup
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
	 * A function to update the 'log-container' with the study log with the given selected values.
	 */
	function updateLog() {
		var userid = $('#user-selector').val();
		var condid = $('#condition-selector').val();
		// fill the table via AJAX
		$('#log-container').load('form/admin/study/log.php?userid='+userid+'&condid='+condid, function() {
			// make the tables sortable
			$(".tablesorter").tablesorter({
				widgets: ['zebra'],
				headers: {
					// disable the first two columns (delete/edit)
					0:{sorter: false}, 1:{sorter: false}
				}
	    	});
		});
	}
</script>
</head>

<body onload="start()">
  <?php create_header($user, $pagename)?>
  <section id="page">
    <section>
      <div class="line"></div>
      <article>
        <div class="study-tabs-container">
          <div id="study-tabs">
            <ul>
              <li><a href="#studies-tab">Manage Studies</a></li>
              <li><a href="#experiments-tab">Manage Experiments</a></li>
              <li><a href="#browse-logs-tab">Browse Logs</a></li>
            </ul>
            <?php
            // create each of the divs for the tabs
            create_studies();
            create_experiments();
            create_logs();
            ?>
          </div>
        </div>
      </article>
    </section>
    <?php create_footer()?>
  </section>

  <div id="confirm-delete-popup" title="Delete?"></div>
  <div id="editor-popup" title="Entry Editor"></div>
</body>
</html>
