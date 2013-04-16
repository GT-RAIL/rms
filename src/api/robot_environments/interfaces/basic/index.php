<?php
/**
 * A basic interface to display 1 or more MJPEG streams and basic keyboard
 * teleoperation control.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 15 2013
 * @package    api.robot_environments.interfaces.basic
 * @link       http://ros.org/wiki/rms
 */

/**
 * A static class to contain the interface generate function.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 15 2013
 * @package    api.robot_environments.interfaces.basic
 */
class basic
{
    /**
     * Generate the HTML for the interface. All HTML is echoed.
     * @param robot_environment $re The associated robot_environment object for
     *     this interface
     */
    static function generate($re)
    {
        // lets begin by checking if we have an MJPEG keyboard at the very least
        if (!$streams = $re->get_widgets_by_name('MJPEG Stream')) {
            robot_environments::create_error_page(
                'No MJPEG streams found.', 
                $re->get_user_account()
            );
        } else if (!$teleop = $re->get_widgets_by_name('Keyboard Teleop')) {
            robot_environments::create_error_page(
                'No Keyboard Teloperation settings found.', 
                $re->get_user_account()
            );
        } else if (!$re->authorized()) {
            robot_environments::create_error_page(
                'Invalid experiment for the current user.', 
                $re->get_user_account()
            );
        } else { 
            // lets create a string array of MJPEG streams
            $topics = '[';
            $labels = '[';
            foreach ($streams as $s) {
                $topics .= "'".$s['topic']."', ";
                $labels .= "'".$s['label']."', ";
            }
            $topics = substr($topics, 0, strlen($topics) - 2).']';
            $labels = substr($labels, 0, strlen($topics) - 2).']';

            // here we can spit out the HTML for our interface ?>
<!DOCTYPE html>
<html>
<head>
<?php $re->create_head() // grab the header information ?>
<script type="text/javascript"
    src="http://cdn.robotwebtools.org/EventEmitter2/0.4.11/eventemitter2.js">
</script>
<script type="text/javascript"
    src="http://cdn.robotwebtools.org/roslibjs/r5/roslib.min.js"></script>
<script type="text/javascript"
    src="http://cdn.robotwebtools.org/mjpegcanvasjs/r1/mjpegcanvas.min.js">
</script>
<script type="text/javascript"
  src="http://cdn.robotwebtools.org/keyboardteleopjs/r1/keyboardteleop.min.js">
</script>
<title>Basic Teleop Interface</title>
<script type="text/javascript">
  //connect to ROS
  var ros = new ROSLIB.Ros({
      url : '<?php echo $re->rosbridge_url()?>'
  });
  
  ros.on('error', function() {
        alert('Lost communication with ROS.');
    });

  /**
   * Load everything on start.
   */
  function start() {
    // create MJPEG streams
    var main = new MJPEGCANVAS.MultiStreamViewer({
      divID : 'main',
      host : '<?php echo $re->get_mjpeg()?>',
      port : '<?php echo $re->get_mjpegport()?>',
      width : 800,
      height : 600,
      topics : <?php echo $topics?>,
      labels : <?php echo $labels?>
    });

    var southWest = new MJPEGCANVAS.MultiStreamViewer({
      divID : 'south-west',
      host : '<?php echo $re->get_mjpeg()?>',
      port : '<?php echo $re->get_mjpegport()?>',
      width : 266,
      height : 200,
      topics : <?php echo $topics?>,
      labels : <?php echo $labels?>,
      defaultStream : <?php echo min(count($streams), 1) ?>
    });

    var soutEast = new MJPEGCANVAS.MultiStreamViewer({
      divID : 'south-east',
      host : '<?php echo $re->get_mjpeg()?>',
      port : '<?php echo $re->get_mjpegport()?>',
      width : 266,
      height : 200,
      topics : <?php echo $topics?>,
      labels : <?php echo $labels?>,
      defaultStream : <?php echo min(count($streams), 2) ?>
    });

    // initialize the teleop
    var teleop = new KEYBOARDTELEOP.Teleop({
      ros : ros,
      topic : '<?php echo $teleop[0]['twist']?>',
      throttle : '<?php echo $teleop[0]['throttle']?>'
    });

    // create a UI slider using JQuery UI
    $('#speed-slider').slider({
      range : 'min',
      min : 0,
      max : 100,
      value : 90,
      slide : function(event, ui) {
        // Change the speed label.
        $('#speed-label').html('Speed: ' + ui.value + '%');
        // Scale the speed.
        teleop.scale = (ui.value / 100.0);
      }
    });

    // set the initial speed
    $('#speed-label').html('Speed: '+($('#speed-slider').slider('value'))+'%');
    teleop.scale = ($('#speed-slider').slider('value') / 100.0);
  }
</script>
</head>
<body onload="start();">
    <section class="page">
        <section class="basic-interface">
            <table class="center">
                <tr>
                    <td colspan="3">
                        <div id="main" class="main-widget"></div>
                    </td>
                </tr>
                <tr>
                    <td width="33%">
                        <div id="south-west" class="south-west-widget"></div>
                    </td>
                    <td width="33%">
                        <div class="control-widget">
                            <div class="speed-container">
                                <div id="speed-label"></div>
                                <div id="speed-slider"></div>
                            </div>
                        </div>
                    </td>
                    <td width="33%">
                        <div id="south-east" class="south-east-widget"></div>
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
