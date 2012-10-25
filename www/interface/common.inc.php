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

/**
 * A WidgetList contains an array of WidgetInfo objects which contain
 * information about given widgets for different environments. Useful
 * methods for searching through the widgets are also provided.
 */
class WidgetList {
	public $list = array();

	/**
	 * Get an array of WidgetInfos for the given type string. This type
	 * string should match the one inside the 'type' field in the 'widgets'
	 * SQL table.
	 */
	function get_infos_by_type($type) {
		$infos = array();
		// go through each and search for a match
		foreach ($this->list as $info) {
			if(strcmp($info->name, $type) == 0) {
				$infos[] = $info;
			}
		}
		return $infos;
	}
}

/**
 * A WidgetInfo contains differnt types of information about a widget.
 * The SQL array entry for the given widget, name of the widget (or type),
 * and PHP script used to create the widget (inside the 'interface/widget/
 * folder) are stored inside each object.
 */
class WidgetInfo {
	public $sql = null;
	public $name = null;
	public $script = null;

	/**
	 * Creates a WidgetInfo with the given information. Note that the script
	 * should match the SQL entry in the 'widgets' table. That is, it is the base
	 * name of the script file inside the 'interface/widget/ folder without '.php'.
	 */
	function __construct($sql_, $name_, $script_) {
		$this->sql = $sql_;
		$this->name = $name_;
		$this->script = $script_;
	}
}

/**
 * A function to create a page which displays an error. This is useful if you
 * are unable to build a given interface with the types of widgets provided.
 */
function create_error_page($error, $usr) {
	global $title;

	include_once('inc/head.inc.php');
	include_once('inc/content.inc.php');?>
<!DOCTYPE html>
<html>
<head>
	<?php import_head()// grab the header information ?>
<script type="text/javascript">
				// create the user menu 
				create_menu_buttons();
			</script>
<title><?php echo $title." :: Invalid Environment"?></title>
</head>
<body>
<?php create_header($usr, "Environment") // create the header?>
	<section id="page">
		<article>
			<div class="center">
				<br /> <br /> <br /> <br /> <br />
				<h2>
					ERROR:
					<?php echo $error?>
				</h2>
				<br /> <br /> <br /> <br /> <br />
			</div>
		</article>
		<?php
		create_footer();
		?>
	</section>
</body>
</html>
		<?php
}

function init_study_head() {
	global $db;
	
	// check for a valid study
	if(!isset($_GET['expid'])) {
		return "No experiment ID provided.";
	}
	$user = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM user_accounts WHERE userid='".$_SESSION['userid']."'"));
	$exp = mysqli_fetch_array(mysqli_query($db, sprintf("SELECT * FROM experiments WHERE expid=%d", $db->real_escape_string($_GET['expid']))));
	$cond = mysqli_fetch_array(mysqli_query($db, sprintf("SELECT * FROM conditions WHERE condid=".$exp['condid'])));
	$time = mysqli_fetch_array(mysqli_query($db, "SELECT CURRENT_TIMESTAMP()"));
	if($exp['userid'] !== $_SESSION['userid']) {
		return "Invalid experiment ID for the current user.";
	} else if($_GET['intid'] !== $cond['intid']) {
		return "Invalid interface for the current study.";
	} else if($time['CURRENT_TIMESTAMP()'] < $exp['start'] || $time['CURRENT_TIMESTAMP()'] > $exp['end']) {
		return "It is not the time for the given study.";
	}
	
	$diff = mysqli_fetch_array(mysqli_query($db, "SELECT UNIX_TIMESTAMP('".$exp['end']."') - UNIX_TIMESTAMP('".$time['CURRENT_TIMESTAMP()']."') AS time"));
	?>
<script type="text/javascript">
	_EXPID = <?php echo $_GET['expid']?>;
	_TIME = <?php echo $diff['time']*1000?>;
	_END = setInterval(function() {endStudy();}, _TIME);
</script>
<?php
return null;
}

?>
