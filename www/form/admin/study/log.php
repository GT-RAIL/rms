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
// include path relative to home directory
include_once('../../../inc/config.inc.php');

session_start();
// check if there is a user logged in
if (!isset($_SESSION['userid'])) {
	echo "INVALID USER SESSION";
} else if(isset($_GET['userid']) && isset($_GET['condid'])) {
	include_once('../../../inc/log.inc.php');

	// grab the user info from the database
	$query = mysqli_query($db, "SELECT * FROM user_accounts WHERE userid = '".$_SESSION['userid']."'");
	$usr = mysqli_fetch_array($query);
	// now make sure this is an admin
	if(strcmp($usr['type'], "admin") != 0) {
		// report this
		write_to_log($usr['username']." attempted to use the read from the study log.");
		echo "INVALID USER";
	} else {
		// grab the log entries
		$sql = sprintf("SELECT * FROM experiments WHERE userid = %d AND condid = %d", $db->real_escape_string($_GET['userid']), $db->real_escape_string($_GET['condid']));
		$query = mysqli_query($db, $sql);
		$condition = mysqli_fetch_array($query);
		$sql = "SELECT * FROM study_log WHERE expid = ".$condition['expid'];
		$query = mysqli_query($db, $sql);?>

<table class="table-log tablesorter">
	<thead>
		<tr>
			<th>ID</th>
			<th>Exp.</th>
			<th>Time</th>
			<th>Enrty</th>
		</tr>
		<tr>
			<td colspan="4"><hr /></td>
		</tr>
	</thead>
	<tbody>
	<?php
	// alternate colors
	$even = True;
	while($query && $cur = mysqli_fetch_array($query)) {
		if($even) {
			$class = "even";
		} else {
			$class = "odd";
		}
		$even = !$even;?>
		<tr class="<?php echo $class?>">
			<td><?php echo $cur['logid']?></td>
			<td class="content-cell"><?php echo $cur['expid']?></td>
			<td class="content-cell"><?php echo $cur['timestamp']?></td>
			<td class="content-cell"><?php echo $cur['entry']?></td>
		</tr>
		<?php
	}?>
	</tbody>
</table>
	<?php
	}
} else {
	echo "INVALID LOG PARAMETERS";
}
?>

