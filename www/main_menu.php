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
	header('Location: login.php');
	return;
}
// load the include files
include('inc/head.inc.php');
include('inc/content.inc.php');

$pagename = "Main Menu";

// grab the user info from the database
$user = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM user_accounts WHERE userid='".$_SESSION['userid']."'"));
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

<script type="text/javascript">
	// chech if the given rosbridge host is reachable 
	function rosonline(host, id) {
		var ros = new ROS("ws://"+host+":9090");
		ros.on('connection', function() {
			$("#envstatus-"+id).html('Available');
		});
		ros.on('error', function() {
			$("#envstatus-"+id).html("OFFLINE");
		});
	}

	function start() {
		create_menu_buttons();
	}

	function beginStudy(expid, intid, envid) {
		window.location = 'environment.php?expid='+expid+'&intid='+intid+'&envid='+envid;
	}
</script>
</head>
<body onload="start()">
<?php create_header($user, $pagename)?>

	<section id="page">
		<section id="articles">
			<div class="line"></div>
			<?php if($user['type'] === "admin") { //admin menu?>
			<article>
				<div class="center">
					<h2>Admin Interface Menu</h2>
				</div>
			</article>
			<?php
			$query = mysqli_query($db, "SELECT * FROM environments");
			while($cur = mysqli_fetch_array($query)) {?>
			<article>
				<div class="center">
					<h3>
					<?php echo $cur['envid'].": ".$cur['envaddr']." -- ".$cur['type']?>
					</h3>
					<script type="text/javascript">
						rosonline('<?php echo $cur['envaddr']?>', '<?php echo $cur['envid']?>');
					</script>
					<div id="envstatus-<?php echo $cur['envid']?>"
						class="environment-status">Acquiring connection...</div>
						<?php if(strlen($cur['notes']) > 0) {
							echo $cur['notes'];
						}?>
					<div class="line"></div>

					<?php
					// check if the environment is enabled
					if($cur['enabled']) {?>
					<ul>
					<?php
					// go through each interface for this environment
					$interfaces = mysqli_query($db, "SELECT * FROM environment_interfaces WHERE envid=".$cur['envid']);
					while($pair = mysqli_fetch_array($interfaces)) {
						$int = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM interfaces WHERE intid=".$pair['intid']));
						?>
						<li><a
							href="environment.php?envid=<?php echo $cur['envid']?>&intid=<?php echo $int['intid']?>">
							<?php echo $int['name']?> </a></li>
							<?php
					}?>
					</ul>
					<?php
					} else {?>
					<h3>Environment Disabled</h3>
					<?php
					}?>

				</div>
			</article>
			<?php
			}?>
			<?php
			} else {?>
			<div class="center">
				<h1>
					Welcome
					<?php echo $user['firstname']." ".$user['lastname']?>
					!
				</h1>
			</div>
			<article>
				<div class="center">
					<h2>My Studies</h2>
					<div class="line"></div>
					<p>
						<b>The following is a list of the available studies you are signed
							up for. If you are currently scheduled and you are ready to
							begin, simply select the 'Start!' button next to the appropriate
							study.</b>
					</p>
					<?php
					// populate the selection box
					$query = mysqli_query($db, "SELECT * FROM experiments WHERE userid=".$user['userid']);
					while($cur = mysqli_fetch_array($query)) {
						$cond = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM conditions WHERE condid=".$cur['condid']));
						$study = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM study WHERE studyid=".$cond['studyid']));
						echo "<h3>".$study['name']." (".$cur['start']." - ".$cur['end'].")</h3>";

						// grab the current timestamp from the SQL server
						$time = mysqli_fetch_array(mysqli_query($db, "SELECT CURRENT_TIMESTAMP()"));
						if($time['CURRENT_TIMESTAMP()'] >= $cur['start'] && $time['CURRENT_TIMESTAMP()'] <= $cur['end']) {
							echo "<button onclick=\"javascript:beginStudy(".$cur['expid'].", ".$cond['intid'].", ".$cur['envid'].");\">Start!</button>";
						} else {
							echo "<button disabled=\"disabled\">Start!</button>";
						}
					}?>
				</div>
			</article>
			<?php
			}?>
		</section>
		<?php create_footer()?>
	</section>
</body>
</html>
