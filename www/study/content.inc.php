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
 * Create the HTML for the studies tab of the study panel.
 */
function create_studies() {
	global $db;?>
<div id="studies-tab">
	<div id="studies">
		<div class="center">
			<h3>Studies</h3>
		</div>
		<div class="line"></div>
		<table class="tablesorter">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th>ID</th>
					<th>Name</th>
					<th>Sescription</th>
					<th>Start</th>
					<th>End</th>
				</tr>
				<tr>
					<td colspan="7"><hr /></td>
				</tr>
			</thead>
			<tbody>
			<?php
			// populate the table
			$query = mysqli_query($db, "SELECT * FROM study");
			// alternate colors
			$even = True;
			while($cur = mysqli_fetch_array($query)) {
				if($even) {
					$class = "even";
				} else {
					$class = "odd";
				}
				$even = !$even;?>
				<tr class="<?php echo $class?>">
					<td class="delete-cell"><div id="<?php echo $cur['studyid']?>"
							class="delete">
							<button>Delete</button>
						</div></td>
					<td class="edit-cell"><div id="<?php echo $cur['studyid']?>"
							class="edit">
							<button>Edit</button>
						</div></td>
					<td class="content-cell"><?php echo $cur['studyid']?></td>
					<td class="content-cell"><?php echo $cur['name']?></td>
					<td class="content-cell"><?php echo $cur['description']?></td>
					<td class="content-cell"><?php echo $cur['start']?></td>
					<td class="content-cell"><?php echo $cur['end']?></td>
				</tr>
				<?php
			}?>
			</tbody>
			<tr>
				<td colspan="7"><hr /></td>
			</tr>
			<tr>
				<td colspan="6"></td>
				<td class="add-cell">
					<div class="add">
						<button class="editor" id="add-study">Add Study</button>
					</div>
				</td>
			</tr>
		</table>
	</div>

	<div id="conditions">
		<div class="center">
			<h3>Conditions</h3>
		</div>
		<div class="line"></div>
		<table class="tablesorter">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th>ID</th>
					<th>Study</th>
					<th>Name</th>
					<th>Interface</th>
				</tr>
				<tr>
					<td colspan="6"><hr /></td>
				</tr>
			</thead>
			<tbody>
			<?php
			// populate the table
			$query = mysqli_query($db, "SELECT * FROM conditions");
			// alternate colors
			$even = True;
			while($cur = mysqli_fetch_array($query)) {
				// grab the study and environment variables
				$study = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM study WHERE studyid=".$cur['studyid']));
				$int = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM interfaces WHERE intid=".$cur['intid']));

				if($even) {
					$class = "even";
				} else {
					$class = "odd";
				}
				$even = !$even;?>
				<tr class="<?php echo $class?>">
					<td class="delete-cell"><div id="<?php echo $cur['condid']?>"
							class="delete">
							<button>Delete</button>
						</div></td>
					<td class="edit-cell"><div id="<?php echo $cur['condid']?>"
							class="edit">
							<button>Edit</button>
						</div></td>
					<td class="content-cell"><?php echo $cur['condid']?></td>
					<td class="content-cell"><?php echo $study['studyid'].": ".$study['name']?>
					
					<td class="content-cell"><?php echo $cur['name']?></td>
					<td class="content-cell"><?php echo $int['intid'].": ".$int['name']?>
				
				</tr>
				<?php
			}?>
			</tbody>
			<tr>
				<td colspan="6"><hr /></td>
			</tr>
			<tr>
				<td colspan="5"></td>
				<td class="add-cell">
					<div class="add">
						<button class="editor" id="add-conditon">Add Condition</button>
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>
			<?php
}

/**
 * Create the HTML for the experiments tab of the study panel.
 */
function create_experiments() {
	global $db;?>

<div id="experiments-tab">
	<div id="experiments">
		<div class="center">
			<h3>Experiments</h3>
		</div>
		<div class="line"></div>
		<table class="tablesorter">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th>ID</th>
					<th>User</th>
					<th>Study</th>
					<th>Condition</th>
					<th>Environment</th>
					<th>Start</th>
					<th>End</th>
				</tr>
				<tr>
					<td colspan="9"><hr /></td>
				</tr>
			</thead>
			<tbody>
			<?php
			// populate the table
			$query = mysqli_query($db, "SELECT * FROM experiments");
			// alternate colors
			$even = True;
			while($cur = mysqli_fetch_array($query)) {
				// grab the user, condition, and study variables
				$user = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM user_accounts WHERE userid=".$cur['userid']));
				$condition = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM conditions WHERE condid=".$cur['condid']));
				$study = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM study WHERE studyid=".$condition['studyid']));
				$env = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM environments WHERE envid=".$cur['envid']));

				if($even) {
					$class = "even";
				} else {
					$class = "odd";
				}
				$even = !$even;?>
				<tr class="<?php echo $class?>">
					<td class="delete-cell"><div id="<?php echo $cur['expid']?>"
							class="delete">
							<button>Delete</button>
						</div></td>
					<td class="edit-cell"><div id="<?php echo $cur['expid']?>"
							class="edit">
							<button>Edit</button>
						</div></td>
					<td class="content-cell"><?php echo $cur['expid']?></td>
					<td class="content-cell"><?php echo $user['userid'].": ".$user['firstname']." ".$user['lastname']." (".$user['username'].")"?>
					</td>
					<td class="content-cell"><?php echo $study['studyid'].": ".$study['name']?>
					</td>
					<td class="content-cell"><?php echo $condition['condid'].": ".$condition['name']?>
					</td>
					<td class="content-cell"><?php echo $cur['envid'].": ".$env['envaddr']?>
					
					<td class="content-cell"><?php echo $cur['start']?></td>
					<td class="content-cell"><?php echo $cur['end']?></td>
				</tr>
				<?php
			}?>
			</tbody>
			<tr>
				<td colspan="9"><hr /></td>
			</tr>
			<tr>
				<td colspan="8"></td>
				<td class="add-cell">
					<div class="add">
						<button class="editor" id="add-experiment">Add Experiment</button>
					</div>
				</td>
			</tr>
		</table>
	</div>

</div>
			<?php
}


/**
 * Create the HTML for the log browser tab of the study panel.
 */
function create_logs() {
	global $db;?>

<div id="browse-logs-tab">
	<div id="logs">
		<div class="center">
			<h3>Experiment Logs</h3>
		</div>
		<div class="line"></div>
		Username: <select name="user-selector" id="user-selector">
		<?php
		// populate the selection box
		$query = mysqli_query($db, "SELECT * FROM user_accounts ORDER BY `username`");
		while($cur = mysqli_fetch_array($query)) {
			echo "<option value=".$cur['userid'].">".$cur['username']."</option>";
		}
		?>
		</select> Study/Condition: <select name="condition-selector"
			id="condition-selector">
			<?php
			// populate the selection box
			$query = mysqli_query($db, "SELECT * FROM study");
			while($cur = mysqli_fetch_array($query)) {
				$query2 = mysqli_query($db, "SELECT * FROM conditions WHERE studyid=".$cur['studyid']);
				while($cond = mysqli_fetch_array($query2)) {
					echo "<option value=".$cond['condid'].">".$cur['name']."/".$cond['name']."</option>";
				}
			}
			?>
		</select>
		<button onclick="javascript:updateLog();">View</button>
		<div class="line"></div>
		<div id="log-container"></div>
	</div>
</div>
			<?php
}
?>