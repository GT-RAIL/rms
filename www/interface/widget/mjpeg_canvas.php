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
// include path relative to home directory
include_once('../../inc/config.inc.php');

session_start();
// check if there is a user logged in
if (!isset($_SESSION['userid'])) {
  echo "INVALID USER SESSION";
} else if(isset($_GET['envid']) && isset($_GET['id']) && isset($_GET['width']) && isset($_GET['height'])) {
  // grab the environment and widget
  $sql = sprintf("SELECT * FROM environments WHERE envid = %d", $db->real_escape_string($_GET['envid']));
  $query = mysqli_query($db, $sql);
  $environment = mysqli_fetch_array($query);
  // check if this is a single stream or a collection of mjpeg streams
  if(strcmp($_GET['id'], "*") == 0) {
    $sql = sprintf("SELECT * FROM mjpeg_server_streams WHERE envid = %d", $db->real_escape_string($_GET['envid']));
  } else {
    $sql = sprintf("SELECT * FROM mjpeg_server_streams WHERE id = %d", $db->real_escape_string($_GET['id']));
  }
  $query = mysqli_query($db, $sql);
  $rows = mysqli_num_rows($query);

  if(!$environment) {
    echo "INVALID ENVIRONMENT";
  } else if($rows == 0) {
    echo "INVALID MJPEG ID";
  } else {
    // create a unique ID
		$id = time() + rand(0, time());?>

<canvas
  id="mjpeg-canvas-<?php echo $id?>" width="<?php echo $_GET['width']?>"
  height="<?php echo $_GET['height']?>"></canvas>

<script type="text/javascript">
	// create the topic/label lists 
	<?php 
		if($rows == 1) {
			$mjpeg = mysqli_fetch_array($query);?>
			var topic = '<?php echo $mjpeg['topic']?>';
			var label = '<?php echo $mjpeg['label']?>';
		<?php 
		} else {
			$topic = "var topic = [";
			$label = "var label = [";
			for ($i = 0; $i < $rows; $i++) {
				$mjpeg = mysqli_fetch_array($query);
				$topic = $topic."'".$mjpeg['topic']."'";
				$label = $label."'".$mjpeg['label']."'";
				if($i != $rows-1) {
					$topic = $topic.", ";
					$label = $label.", ";
				}
			}
			$topic = $topic."];\n";
			$label = $label."];\n";
			echo $topic;
			echo $label;
		}
	?>

	// create the canvas	
	var mjpeg = new MjpegCanvas({
		host : '<?php echo $environment['envaddr']?>',
		topic : topic,
		label : label,
		canvasID : 'mjpeg-canvas-<?php echo $id?>',
		width : <?php echo $_GET['width']?>,
		height : <?php echo $_GET['height']?>,
		defaultStream : <?php echo isset($_GET['default']) ? $_GET['default'] : "0";?>	
	});

	<?php 
		if (isset($_GET['cb'])) {
			echo $_GET['cb']."(mjpeg);";
		}?>
</script>

<?php
  }
} else {
  echo "INVALID MJPEG CANVAS PARAMETERS";
}
?>

