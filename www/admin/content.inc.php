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
 *  Version: October 10, 2012
 *
 *********************************************************************/
?>

<?php
/**
 * Create the HTML for the users tab of the admin panel.
 */
function create_users() {
	global $db;?>
<div id="users-tab">
	<div id="users">
		<div class="center">
			<h3>Users</h3>
		</div>
		<div class="line"></div>
		<table class="tablesorter">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th>ID</th>
					<th>Username</th>
					<th>First Name</th>
					<th>Last Name</th>
					<th>E-mail</th>
					<th>Role</th>
				</tr>
				<tr>
					<td colspan="8"><hr /></td>
				</tr>
			</thead>
			<tbody>
			<?php
			// populate the table
			$query = mysqli_query($db, "SELECT * FROM user_accounts");
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
					<td class="delete-cell"><div id="<?php echo $cur['userid']?>"
							class="delete">
							<button>Delete</button>
						</div></td>
					<td class="edit-cell"><div id="<?php echo $cur['userid']?>"
							class="edit">
							<button>Edit</button>
						</div></td>
					<td class="content-cell"><?php echo $cur['userid']?></td>
					<td class="content-cell"><?php echo $cur['username']?></td>
					<td class="content-cell"><?php echo $cur['firstname']?></td>
					<td class="content-cell"><?php echo $cur['lastname']?></td>
					<td class="content-cell"><?php echo $cur['email']?></td>
					<td class="content-cell"><?php echo $cur['type']?></td>
				</tr>
				<?php
			}?>
			</tbody>
			<tr>
				<td colspan="8"><hr /></td>
			</tr>
			<tr>
				<td colspan="7"></td>
				<td class="add-cell">
					<div class="add">
						<button class="editor" id="add-user">Add User</button>
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>
			<?php
}

/**
 * Create the HTML for the log tab of the admin panel.
 */
function create_log() {?>
<div id="site-log-tab">
	<div class="center">
		<h3>Site Log</h3>
	</div>
	<div class="line"></div>
	<div id="log-container">
		<table class="table-log">
			<thead>
				<tr>
					<th>ID</th>
					<th>Timestamp</th>
					<th>URI</th>
					<th>Address</th>
					<th>Enrty</th>
				</tr>
				<tr>
					<td colspan="5"><hr /></td>
				</tr>
			</thead>
			<tbody>
			<?php
			// grab the log from the database
			$log = get_log();
			// alternate colors
			$even = True;
			while($cur = mysqli_fetch_array($log)) {
				if($even) {
					$class = "even";
				} else {
					$class = "odd";
				}
				$even = !$even;?>
				<tr class="<?php echo $class?>">
					<td><?php echo $cur['logid']?></td>
					<td class="content-cell"><?php echo $cur['timestamp']?></td>
					<td class="content-cell"><?php echo $cur['request_uri']?></td>
					<td class="content-cell"><?php echo $cur['remote_addr']?></td>
					<td class="content-cell"><?php echo $cur['entry']?></td>
				</tr>
				<?php
			}?>
			</tbody>
		</table>
	</div>
</div>
			<?php
}

/**
 * Create the HTML for the environments tab of the admin panel.
 */
function create_environments() {
	global $db;?>
<div id="environments-tab">
	<div id="environments">
		<div class="center">
			<h3>Environments</h3>
		</div>
		<div class="line"></div>
		<table class="tablesorter">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th>ID</th>
					<th>Address</th>
					<th>Type</th>
					<th>Notes</th>
					<th>Status</th>
				</tr>
				<tr>
					<td colspan="7"><hr /></td>
				</tr>
			</thead>
			<tbody>
			<?php
			// populate the table
			$query = mysqli_query($db, "SELECT * FROM environments");
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
					<td class="delete-cell"><div id="<?php echo $cur['envid']?>"
							class="delete">
							<button>Delete</button>
						</div></td>
					<td class="edit-cell"><div id="<?php echo $cur['envid']?>"
							class="edit">
							<button>Edit</button>
						</div></td>
					<td class="content-cell"><?php echo $cur['envid']?></td>
					<td class="content-cell"><?php echo $cur['envaddr']?></td>
					<td class="content-cell"><?php echo $cur['type']?></td>
					<td class="content-cell"><?php echo $cur['notes']?></td>
					<?php
					if($cur['enabled']) {// check if the environment is enabled?
						echo "<script type=\"text/javascript\">rosonline('".$cur['envaddr']."', ".$cur['envid'].");</script>";?>
					<td class="content-cell"><div
							id="envstatus-<?php echo $cur['envid']?>">Acquiring connection...</div>
					</td>
					<?php
					} else {?>
					<td class="content-cell"><div
							id="envstatus-<?php echo $cur['envid']?>">DISABLED</div>
					</td>
					<?php
					}?>
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
						<button class="editor" id="add-environment">Add Environment</button>
					</div>
				</td>
			</tr>
		</table>
	</div>

	<div id="interfaces">
		<br /> <br />
		<div class="center">
			<h3>Interface</h3>
		</div>
		<div class="line"></div>
		<table class="tablesorter">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th>ID</th>
					<th>Name</th>
					<th>Directory</th>
				</tr>
				<tr>
					<td colspan="5"><hr /></td>
				</tr>
			</thead>
			<tbody>
			<?php
			// populate the table
			$query = mysqli_query($db, "SELECT * FROM interfaces");
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
					<td class="delete-cell"><div id="<?php echo $cur['intid']?>"
							class="delete">
							<button>Delete</button>
						</div></td>
					<td class="edit-cell"><div id="<?php echo $cur['intid']?>"
							class="edit">
							<button>Edit</button>
						</div></td>
					<td class="content-cell"><?php echo $cur['intid']?></td>
					<td class="content-cell"><?php echo $cur['name']?></td>
					<td class="content-cell"><?php echo $cur['location']?>
					</td>
				</tr>
				<?php
			}?>
			
			
			<tbody>
				<tr>
					<td colspan="5"><hr /></td>
				</tr>
				<tr>
					<td colspan="4"></td>
					<td class="add-cell">
						<div class="add">
							<button class="editor" id="add-interface">Add Interface</button>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div id="environment-interface">
		<br /> <br />
		<div class="center">
			<h3>Environment-Interface Pairings</h3>
		</div>
		<div class="line"></div>
		<table class="tablesorter">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th>ID</th>
					<th>Environment ID</th>
					<th>Interface ID</th>
				</tr>
				<tr>
					<td colspan="5"><hr /></td>
				</tr>
			</thead>
			<tbody>
			<?php
			// populate the table
			$query = mysqli_query($db, "SELECT * FROM environment_interfaces");
			// alternate colors
			$even = True;
			while($cur = mysqli_fetch_array($query)) {
				// grab the interface and environment variables
				$env = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM environments WHERE envid=".$cur['envid']));
				$int = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM interfaces WHERE intid=".$cur['intid']));

				if($even) {
					$class = "even";
				} else {
					$class = "odd";
				}
				$even = !$even;?>
				<tr class="<?php echo $class?>">
					<td class="delete-cell"><div id="<?php echo $cur['pairid']?>"
							class="delete">
							<button>Delete</button>
						</div></td>
					<td class="edit-cell"><div id="<?php echo $cur['pairid']?>"
							class="edit">
							<button>Edit</button>
						</div></td>
					<td class="content-cell"><?php echo $cur['pairid']?></td>
					<td class="content-cell"><?php echo $env['envid'].": ".$env['envaddr']." -- ".$env['type']." :: ".$env['notes']?>
					</td>
					<td class="content-cell"><?php echo $int['intid'].": ".$int['name']?>
					</td>
				</tr>
				<?php
			}?>
			
			
			<tbody>
				<tr>
					<td colspan="5"><hr /></td>
				</tr>
				<tr>
					<td colspan="4"></td>
					<td class="add-cell">
						<div class="add">
							<button class="editor" id="add-environment-interface">Add Pairing</button>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div id="widgets">
		<br /> <br />
		<div class="center">
			<h3>Widgets</h3>
		</div>
		<div class="line"></div>
		<table class="tablesorter">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th>ID</th>
					<th>Name</th>
					<th>SQL Table</th>
					<th>PHP Script</th>
				</tr>
				<tr>
					<td colspan="6"><hr /></td>
				</tr>
			</thead>
			<tbody>
			<?php
			// populate the table
			$query = mysqli_query($db, "SELECT * FROM widgets");
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
					<td class="delete-cell"><div id="<?php echo $cur['widgetid']?>"
							class="delete">
							<button>Delete</button>
						</div></td>
					<td class="edit-cell"><div id="<?php echo $cur['widgetid']?>"
							class="edit">
							<button>Edit</button>
						</div></td>
					<td class="content-cell"><?php echo $cur['widgetid']?></td>
					<td class="content-cell"><a
						href="#widget<?php echo $cur['widgetid']?>"><?php echo $cur['name']?>
					</a></td>
					<td class="content-cell"><?php echo $cur['table']?></td>
					<td class="content-cell"><?php echo $cur['script']?></td>
				</tr>
				<?php
			}?>
			
			
			<tbody>
				<tr>
					<td colspan="6"><hr /></td>
				</tr>
				<tr>
					<td colspan="5"></td>
					<td class="add-cell">
						<div class="add">
							<button class="editor" id="add-widget">Add Widget</button>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<?php
	// individual widget tables
	$widget_query = mysqli_query($db,"SELECT * FROM widgets");
	while($widget = mysqli_fetch_array($widget_query)) {?>
	<div id="widget-<?php echo $widget['widgetid']?>">
		<br /> <br />
		<div class="center">
			<h3>
				<a name="widget<?php echo $widget['widgetid']?>"> <?php echo $widget['name']?>
				</a>
			</h3>
		</div>
		<div class="line"></div>
		<table class="tablesorter">
			<thead>
			<?php
			// build an array of the column names
			$attributes = array();
			$query = mysqli_query($db, "SHOW COLUMNS FROM ".$widget['table']);
			while($cur = mysqli_fetch_array($query)) {
				$attributes[] = $cur['Field'];
			}
			$num_att = count($attributes);?>

				<tr>
					<th></th>
					<th></th>
					<?php
					foreach ($attributes as $label) {
						echo "<th>".$label."</th>";
					}?>
				</tr>
				<tr>
					<td colspan="<?php echo ($num_att+2)?>"><hr /></td>
				</tr>
			</thead>
			<tbody>
			<?php
			// populate the table
			$query = mysqli_query($db, "SELECT * FROM ".$widget['table']);
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
					<td class="delete-cell"><div id="<?php echo $cur['id']?>"
							class="delete">
							<button>Delete</button>
						</div></td>
					<td class="edit-cell"><div id="<?php echo $cur['id']?>"
							class="edit">
							<button>Edit</button>
						</div></td>
						<?php foreach ($attributes as $label) {?>
					<td class="content-cell"><?php echo $cur[$label]?></td>
					<?php
						}?>
				</tr>
				<?php
			}?>
			</tbody>
			<tr>
				<td colspan="<?php echo ($num_att+2)?>"><hr /></td>
			</tr>
			<tr>
				<td colspan="<?php echo ($num_att+1)?>"></td>
				<td class="add-cell">
					<div class="add">
						<button class="editor" id="add-widget">
							Add
							<?php echo $widget['name']?>
						</button>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<?php
	}?>

</div>
	<?php
}

/**
 * Create the HTML for the pages tab of the admin panel.
 */
function create_pages() {
	global $db;?>
<div id="pages-tab">
	<div id="slideshow">
		<div class="center">
			<h3>Homepage Slideshow</h3>
		</div>
		<div class="line"></div>
		<table class="tablesorter">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th>ID</th>
					<th>Image</th>
					<th>Caption</th>
					<th>Index</th>
				</tr>
				<tr>
					<td colspan="6"><hr /></td>
				</tr>
			</thead>
			<tbody>
			<?php
			$query = mysqli_query($db, "SELECT * FROM slideshow ORDER BY 'index'");
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
					<td class="delete-cell"><div id="<?php echo $cur['slideid']?>"
							class="delete">
							<button>Delete</button>
						</div></td>
					<td class="edit-cell"><div id="<?php echo $cur['slideid']?>"
							class="edit">
							<button>Edit</button>
						</div></td>
					<td class="content-cell"><?php echo $cur['slideid']?></td>
					<td class="content-cell"><?php echo $cur['img']?></td>
					<td class="content-cell"><?php echo $cur['caption']?></td>
					<td class="content-cell"><?php echo $cur['index']?></td>
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
						<button class="editor" id="add-article">Add Slide</button>
					</div>
				</td>
			</tr>
		</table>
	</div>

	<div id="pages">
		<br /> <br />
		<div class="center">
			<h3>Content Pages</h3>
		</div>
		<div class="line"></div>
		<table class="tablesorter">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th>ID</th>
					<th>Title</th>
					<th>Menu Name</th>
					<th>Menu Index</th>
					<th>Javascript File</th>
				</tr>
				<tr>
					<td colspan="7"><hr /></td>
				</tr>
			</thead>
			<tbody>
			<?php
			// populate the table
			$query = mysqli_query($db, "SELECT * FROM content_pages ORDER BY menu_index");
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
					<td class="delete-cell"><div id="<?php echo $cur['pageid']?>"
							class="delete">
							<button>Delete</button>
						</div></td>
					<td class="edit-cell"><div id="<?php echo $cur['pageid']?>"
							class="edit">
							<button>Edit</button>
						</div></td>
					<td class="content-cell"><?php echo $cur['pageid']?></td>
					<td class="content-cell"><?php echo $cur['title']?></td>
					<td class="content-cell"><?php echo $cur['menu_name']?></td>
					<td class="content-cell"><?php echo $cur['menu_index']?></td>
					<?php
					if($cur['js']) {
						echo "<td class=\"content-cell\">".$cur['js']."</td>";
					} else {
						echo "<td class=\"content-cell\">---</td>";
					}?>
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
						<button class="editor" id="add-page">Add Page</button>
					</div>
				</td>
			</tr>
		</table>
	</div>

	<div id="articles">
		<br /> <br />
		<div class="center">
			<h3>Content Page Articles</h3>
		</div>
		<div class="line"></div>
		<table class="tablesorter">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th>ID</th>
					<th>Title</th>
					<th>Content</th>
					<th>Page</th>
					<th>Page-Index</th>
				</tr>
				<tr>
					<td colspan="7"><hr /></td>
				</tr>
			</thead>
			<tbody>
			<?php
			// populate the table
			$query = mysqli_query($db, "SELECT * FROM articles ORDER BY pageid, pageindex");
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
					<td class="delete-cell"><div id="<?php echo $cur['artid']?>"
							class="delete">
							<button>Delete</button>
						</div></td>
					<td class="edit-cell"><div id="<?php echo $cur['artid']?>"
							class="edit">
							<button>Edit</button>
						</div></td>
					<td class="content-cell"><?php echo $cur['artid']?></td>
					<td class="content-cell"><?php echo $cur['title']?></td>
					<?php
					// check if we should trim the string to fit in the table
					if(strlen(htmlentities($cur['content'])) > 30) {
						echo "<td class=\"content-cell\">".substr(htmlentities($cur['content']), 0, 30)."...</td>";
					} else {
						echo "<td class=\"content-cell\">".htmlentities($cur['content'])."</td>";
					}
					// grab the page name from the database
					$res = mysqli_fetch_array(mysqli_query($db, "SELECT title FROM content_pages WHERE pageid =".$cur['pageid']));?>
					<td class="content-cell"><?php echo $res['title']?></td>
					<td class="content-cell"><?php echo $cur['pageindex']?></td>
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
						<button class="editor" id="add-article">Add Article</button>
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>
			<?php
}

/**
 * Create the HTML for the site tab of the admin panel.
 */
function create_site() {
	global $dbhost, $dbuser, $dbname, $dbpass, $title, $google_tracking_id, $copyright;?>
<div id="site-tab">
	<div id="site">
		<div class="center">
			<h3>Site Settings</h3>
		</div>
		<div class=line></div>
		<table>
			<tbody>
				<tr>
					<td width="33%" rowspan="7"></td>
					<td class="setting-label">Database Host:</td>
					<td><?php echo $dbhost?></td>
					<td width="33%" rowspan="7"></td>
				</tr>
				<tr>
					<td class="setting-label">Database Name:</td>
					<td><?php echo $dbname?></td>
				</tr>
				<tr>
					<td class="setting-label">Database Username:</td>
					<td><?php echo $dbuser?></td>
				</tr>
				<tr>
					<td class=setting-label>Database Password:</td>
					<td>**********</td>
				</tr>
				<tr>
					<td class="setting-label">Site Name:</td>
					<td><?php echo $title?></td>
				</tr>
				<tr>
					<td class="setting-label">Google Analytics Tracking ID:</td>
					<td><?php echo $google_tracking_id?></td>
				</tr>
				<tr>
					<td class="setting-label">Copyright Message:</td>
					<td><?php echo substr($copyright, 6)?></td>
				</tr>
			</tbody>
			<tr>
				<td colspan="4"><hr /></td>
			</tr>
			<tr>
				<td colspan="3"></td>
				<td class="add-cell">
					<div class="add">
						<button class="editor" id="add-site">Edit Settings</button>
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>
	<?php
}

/**
 * Create the HTML for the maintenance tab of the admin panel.
 */
function create_maintenance() {
	global $db;?>
<div id="maintenance-tab">
	<div id="site-status">
		<div class="center">
			<h3>Site Status</h3>
		</div>
		<div class=line></div>
		<?php
		// grab the database version
		$v = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM version"));

		// grab the code version
		$code = parse_init_sql("http://".$_SERVER['HTTP_HOST']."/admin/init.sql");

		// check if an update is needed
		if($v['version'] < $code) {
			$disable = "";
		} else {
			$disable = "disabled=\"disabled\"";
		}

		// find our the live version
		$live = parse_init_sql("https://raw.github.com/WPI-RAIL/rms/fuerte-devel/www/admin/init.sql");

		?>
		<table>
			<tbody>
				<tr>
					<td width="33%" rowspan="2"></td>
					<td class="setting-label">Database Version:</td>
					<td><?php echo $v['version']?></td>
					<td width="33%" rowspan="2"></td>
				</tr>
				<tr>
					<td class="setting-label">Code Version:</td>
					<td><?php echo $code?></td>
				</tr>
				<tr>
					<td colspan="4"><hr /></td>
				</tr>
				<tr>
					<td width="33%" rowspan="1"></td>
					<td class="setting-label">Released Version:</td>
					<td><?php echo $live?></td>
					<td width="33%" rowspan="1"></td>
				</tr>
			</tbody>
			<tr>
				<td colspan="4"><hr /></td>
			</tr>
			<tr>
				<td colspan="3"></td>
				<td class="add-cell">
					<div class="add">
						<button class="editor" id="update-db" <?php echo $disable?>>Run
							Database Update</button>
					</div>
				</td>
			</tr>
		</table>
	</div>

	<div id="javascript">
		<div class="center">
			<h3>ROS Javascript</h3>
		</div>
		<div class=line></div>
		<?php
		// grab the Javascript file list
		$query = mysqli_query($db, "SELECT * FROM javascript_files ORDER BY path");
		?>
		<table>
			<tbody>
			<?php
			while ($file = mysqli_fetch_array($query)) {?>
				<tr>
					<td width="33%"></td>
					<td class=setting-label><?php echo $file['path']?>:</td>
					<td><?php echo file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$file['path']) ? "Exists" : "MISSING"?>
					</td>
					<td width="33%"></td>
				</tr>
				<?php
			}?>
			</tbody>
			<tr>
				<td colspan="4"><hr /></td>
			</tr>
			<tr>
				<td colspan="3"></td>
				<td class="add-cell">
					<div class="add">
						<button class="editor" id="update-js">Update ROS Javascript</button>
					</div>
				</td>
			</tr>
		</table>
	</div>

	<div id="privileges">
		<div class="center">
			<h3>Directory Privileges</h3>
		</div>
		<div class=line></div>
		<table>
			<tbody>
				<tr>
					<td width="33%" rowspan="4"></td>
					<td class=setting-label>js/ros:</td>
					<td><?php echo is_writable($_SERVER['DOCUMENT_ROOT'].'/js/ros') ? "Writable" : "UN-WRITABLE"?>
					</td>
					<td width="33%" rowspan="4"></td>
				</tr>
				<tr>
					<td class=setting-label>js/ros/widgets:</td>
					<td><?php echo is_writable($_SERVER['DOCUMENT_ROOT'].'/js/ros/widgets') ? "Writable" : "UN-WRITABLE"?>
					</td>
				</tr>
				<tr>
					<td class=setting-label>inc:</td>
					<td><?php echo is_writable($_SERVER['DOCUMENT_ROOT'].'/inc') ? "Writable" : "UN-WRITABLE"?>
					</td>
				</tr>
				<tr>
					<td class=setting-label>img/slides:</td>
					<td><?php echo is_writable($_SERVER['DOCUMENT_ROOT'].'/img/slides') ? "Writable" : "UN-WRITABLE"?>
					</td>
				</tr>
			</tbody>
			<tr>
				<td colspan="4"><hr /></td>
			</tr>
		</table>
	</div>
</div>
			<?php
}

/**
 * Parse the init.sql file at the given URL and return the version number.
 *
 * @param string $url the URL to the init.sql file
 */
function parse_init_sql($url) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec ($curl);
	curl_close ($curl);
	$lines = array();
	$lines = explode("\n", $data);
	$len = count($lines);
	for ($i = 0; $i < $len; $i++) {
		if($lines[$i] === "INSERT INTO `version` (`version`) VALUES") {
			// break out the version number
			$v = substr($lines[$i+1], strpos($lines[$i+1], "'")+1);
			$v = substr($v, 0, strpos($v, "'"));
			return $v;
		}
	}
}
?>
