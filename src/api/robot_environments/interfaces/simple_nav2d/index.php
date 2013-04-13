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
 * @version    April, 13 2013
 * @package    api.robot_environments.interfaces.simple_nav2d
 * @link       http://ros.org/wiki/rms
 */

/**
 * A static class to contain the interface generate function.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 13 2013
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
        } else { // here we can spit out the HTML for our interface ?>
<!DOCTYPE html>
<html>
<head>
<?php $re->create_head() // grab the header information ?>
<title>Simple Navigation Interface</title>
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
</script>
</head>
<body>
    <section class="page">
        <section class="nav-interface">
            <table class="center">
                <tr>
                    <td colspan="2"><h1>2D Navigation</h1></td>
                </tr>
                <tr>
                    <td colspan="2"><div class="speed-container">
<?php //echo create_keyboard_teleop_with_slider($teleop[0])?>
                        </div></td>
                </tr>
                <tr>
                    <td><div class="mjpeg-widget">
<?php //echo create_multi_mjpeg_canvas_by_envid($re->get_envid(), 480, 360, 2)?>
                        </div></td>
                    <td><div class="nav-widget">
<?php //echo create_nav2d($nav[0], 480, 360)?>
                        </div></td>
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
