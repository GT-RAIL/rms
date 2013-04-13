<?php
/**
 * A basic interface to display 1 or more MJPEG streams and basic keyboard
 * teleoperation control.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 13 2013
 * @package    api.robot_environments.interfaces.basic
 * @link       http://ros.org/wiki/rms
 */

/**
 * A static class to contain the interface generate function.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 13 2013
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
<script type="text/javascript"
    src="http://cdn.robotwebtools.org/EventEmitter2/0.4.11/eventemitter2.js">
</script>
<script type="text/javascript"
    src="http://cdn.robotwebtools.org/roslibjs/r5/roslib.min.js"></script>
<title>Basic Teleop Interface</title>
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
        <section class="basic-interface">
            <table class="center">
                <tr>
                    <td colspan="3"><div class="main-widget">
<?php //echo create_multi_mjpeg_canvas_by_envid($re->get_envid(), 800, 600, 2)?>
                        </div></td>
                </tr>
                <tr>
                    <td width="33%"><div class="south-west-widget">
<?php //echo create_multi_mjpeg_canvas_by_envid($re->get_envid(), 266, 200, 1)?>
                        </div>
                    </td>
                    <td width="33%">
                        <div class="control-widget">
                            <div class="speed-container">
<?php //echo create_keyboard_teleop_with_slider($teleop[0])?>
                            </div>
                        </div>
                    </td>
                    <td width="33%"><div class="south-east-widget">
<?php //echo create_multi_mjpeg_canvas_by_envid($re->get_envid(), 266, 200)?>
                        </div>
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
