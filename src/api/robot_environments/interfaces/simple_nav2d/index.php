<?php
/**
 * The simple_nav2d interface is an interface implementation that allows for
 * three widget types: a MJPEG canvas widget, a nav2d widget, and a keyboard
 * teleop widget. One camera feed, one nav2d widget, and one keyboard widget are
 * required for this interface to load.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 15 2013
 * @package    api.robot_environments.interfaces.simple_nav2d
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../widgets/widgets.inc.php');

/**
 * A static class to contain the interface generate function.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 15 2013
 * @package    api.robot_environments.interfaces.simple_nav2d
 */
class simple_nav2d
{
    /**
     * Generate the HTML for the interface. All HTML is echoed.
     * @param robot_environment $re The associated robot_environment object for
     *     this interface
     */
    static function generate($re)
    {
        // check if we have enough valid widgets
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
        } else if (!$nav = $re->get_widgets_by_name('2D Navigation')) {
            robot_environments::create_error_page(
                'No 2D Navaigation settings found.',
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
            
            // we will also need the map
            $widget = widgets::get_widget_by_table('maps');
            $map = widgets::get_widget_instance_by_widgetid_and_id(
                $widget['widgetid'], $nav[0]['mapid']
            );

            // here we can spit out the HTML for our interface ?>
<!DOCTYPE html>
<html>
<head>
<?php $re->create_head() // grab the header information ?>
<title>Simple Navigation Interface</title>
<script type="text/javascript"
    src="http://cdn.robotwebtools.org/EaselJS/0.6.0/easeljs.min.js">
</script>
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
  <script type="text/javascript"
  src="http://cdn.robotwebtools.org/ros2djs/r1/ros2d.min.js">
</script>
  <script type="text/javascript"
  src="http://cdn.robotwebtools.org/nav2djs/r1/nav2d.min.js">
</script>

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
    var mjpeg = new MJPEGCANVAS.MultiStreamViewer({
      divID : 'mjpeg',
      host : '<?php echo $re->get_mjpeg()?>',
      port : '<?php echo $re->get_mjpegport()?>',
      width : 480,
      height : 360,
      topics : <?php echo $topics?>,
      labels : <?php echo $labels?>
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

    // Create the 2d viewer
    var viewer = new ROS2D.Viewer({
      divID : 'nav',
      width : 480,
      height : 360
    });

    // setup the nav client
    var nav = NAV2D.OccupancyGridClientNav({
      ros : ros,
      rootObject : viewer.scene,
      viewer : viewer,
      serverName : '<?php echo $nav[0]['actionserver']?>',
      actionName : '<?php echo $nav[0]['action']?>',
      topic : '<?php echo $map['topic']?>',
      continuous : <?php echo ($map['continuous'] === 0) ? 'true' : 'false'?>
    });
  }
</script>
</head>
<body onload="start();">
    <section class="page">
        <section class="nav-interface">
            <table class="center">
                <tr>
                    <td colspan="2"><h1>2D Navigation</h1></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="speed-container">
                            <div id="speed-label"></div>
                            <div id="speed-slider"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id="mjpeg" class="mjpeg-widget"></div>
                    </td>
                    <td>
                        <div id='nav' class="nav-widget"></div>
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
