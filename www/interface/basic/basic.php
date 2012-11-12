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
*  Version: September 25, 2012
*
*********************************************************************/
?>

<?php
// include path relative to home directory
include_once('interface/iface.iface.php');
include_once('inc/head.inc.php');
include_once('inc/config.inc.php');
include_once('inc/content.inc.php');

/**
 * The basic interface is an example interface implementation that allows for two basic
 * widget types: MJPEG canvas widgets and a keyboard teleop widget. Three camera feeds
 * and one keyboard widget are required for this interface to load.
 */
class basic implements iface {

  /**
   * Generates a basic interface for the given environment with 3 main
   * camera displays and basic keyboard teleoperation.
   */
  function generate_interface($env, $widgets, $usr) {
    global $title;
    // check if we have enough valid widgets
    if(count($widgets->get_infos_by_type("MJPEG Widget")) < 3 || count($widgets->get_infos_by_type("Keyboard Teleop")) == 0) {
      create_error_page("Not Enough Widgets for this Environment", $usr);
    } else {
      $teleop_list = $widgets->get_infos_by_type("Keyboard Teleop");
      $teleop = $teleop_list[0];

      $widget_list = $widgets->get_infos_by_type("MJPEG Widget");
      // load the first three widgets
			$widget = $widget_list[0]?>
<!DOCTYPE html>
<html>
<head>
<?php
// grab the header information
import_head();
import_common_js();
?>
<title><?php echo $title?> :: Basic Interface</title>

<link rel="stylesheet" type="text/css"
  href="css/interfaces/basic/basic.css" />
<script type="text/javascript" src="js/ros/ros_bundle.min.js"></script>
<script type="text/javascript" src="js/ros/widgets/mjpegcanvas.js"></script>
<script type="text/javascript" src="js/ros/widgets/keyboardteleop.js"></script>

<script type="text/javascript">
	function createSpeedSlider(teleop) {
		$("#speed-slider").slider({
			range: "min",
			min: 0,
			max: 100,
			value: 90,
			slide: function(event, ui) {
				$("#speed-label").html("<h3>Speed: "+ui.value+"%</h3>");
				teleop.scale = (ui.value/100.0);
			}
		});
		$("#speed-label").html("<h3>Speed: "+($("#speed-slider").slider("value"))+"%</h3>");
	}

	function start() {
		// conntect to ROS
		ros = new ROS('ws://<?php echo $env['envaddr']?>:9090');

		ros.on('error', function() {
			alert('Lost communication with ROS.');
		});

		// initialize the teleop
		$('#teleop').load("interface/widget/<?php echo $teleop->script?>.php?envid=<?php echo $env['envid']?>&id=<?php echo $teleop->sql['id']?>&callback=createSpeedSlider");
		$('#main-widget').load("interface/widget/<?php echo $widget->script?>.php?width=800&height=600&envid=<?php echo $env['envid']?>&id=*&default=0");
		$('#south-east-widget').load("interface/widget/<?php echo $widget->script?>.php?width=266&height=200&envid=<?php echo $env['envid']?>&id=*&default=1");
		$('#south-west-widget').load("interface/widget/<?php echo $widget->script?>.php?width=266&height=200&envid=<?php echo $env['envid']?>&id=*&default=2");
	}
</script>

</head>
<body onload="start()">
  <section id="page">
    <section id="basic-interface">
      <table class="center">
        <tr>
          <td colspan="3"><div id="main-widget" class="widget"></div></td>
        </tr>
        <tr>
          <td width="33%"><div id="south-west-widget" class="widget"></div>
          </td>
          <td width="33%">
            <div id="control-widget" class="widget">
              <div id="teleop"></div>
              <div id="speed-container">
                <div id="speed-label"></div>
                <div id="speed-slider"></div>
              </div>
            </div>
          </td>
          <td width="33%"><div id="south-east-widget" class="widget"></div>
          </td>
        </tr>
      </table>
    </section>
  </section>
</body>
</html>
<?php
    }
  }
}
?>

