<?php
/**
 * A basic interface to display a set of interactive markers with camera feeds
 * and keybaord teleoperation.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 13 2013
 * @package    api.robot_environments.interfaces.markers
 * @link       http://ros.org/wiki/rms
 */

/**
 * A static class to contain the interface generate function.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 13 2013
 * @package    api.robot_environments.interfaces.markers
 */
class markers
{
    /**
     * Generate the HTML for the interface. All HTML is echoed.
     * @param robot_environment $re The associated robot_environment object for
     *     this interface
     */
    static function generate($re)
    {
        // lets begin by checking if we have the correct widgets
        if (!$re->get_widgets_by_name('MJPEG Stream')) {
            robot_environments::create_error_page(
                'No MJPEG streams found.',
                $re->get_user_account()
            );
        } else if (!$teleop = $re->get_widgets_by_name('Keyboard Teleop')) {
            robot_environments::create_error_page(
                'No Keyboard Teloperation settings found.', 
                $re->get_user_account()
            );
        } else if (!$im = $re->get_widgets_by_name('Interactive Markers')) {
            robot_environments::create_error_page(
                'No Interactive Markers settings found.',
                $re->get_user_account()
            );
        } else if (!$re->authorized()) {
            robot_environments::create_error_page(
                'Invalid experiment for the current user.',
                $re->get_user_account()
            );
        } else { // here we can spit out the HTML for our interface ?>
<!DOCTYPE html>
<html>
<head>
<?php $re->create_head() // grab the header information ?>
<title>Interactive Markers</title>
<script type="text/javascript"
    src="http://cdn.robotwebtools.org/EventEmitter2/0.4.11/eventemitter2.js">
</script>
<script type="text/javascript"
    src="http://cdn.robotwebtools.org/roslibjs/r5/roslib.min.js"></script>
<script type="text/javascript">
  //connect to ROS
  var ros = new ROSLIB.Ros('<?php echo $re->rosbridge_url()?>');

  ros.on('error', function() {
      alert('Lost communication with ROS.');
  });

  function start() {
    // create the global display

    // add the markers
    <?php //echo add_interactive_markers_to_global_scene($im[0])?>
  }
</script>
</head>
<body class="center" onload="start();">
    <section class="page">
        <table class="center">
            <tr>
                <td colspan="2"><h1>Interactive Markers</h1></td>
            </tr>
            <tr>
                <td colspan="2"><div class="speed-container">
<?php //echo create_keyboard_teleop_with_slider($teleop[0])?>
                    </div></td>
            </tr>
            <tr>
                <td>
<?php //echo create_multi_mjpeg_canvas_by_envid($re->get_envid(), 480, 360)?>
                </td>
                <td><div class="scene"></div></td>
            </tr>
        </table>
    </section>
</body>
</html>
<?php
        }
    }
}

