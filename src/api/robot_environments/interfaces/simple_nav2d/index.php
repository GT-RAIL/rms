<?php
/**
 * The simple_nav2d interface is an interface implementation that allows for three widget types:
 * a MJPEG canvas widget, a nav2d widget, and a keyboard teleop widget. One camera feed, one nav2d
 * widget, and one keyboard widget are required for this interface to load.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 13 2012
 * @package    api.robot_environments.interfaces.simple_nav2d
 * @link       http://ros.org/wiki/rms
 */

/**
 * Generate the HTML for the interface. All HTML is echoed.
 * @param robot_environment $re The associated robot_environment object for this interface
 */
function generate($re) {
  // check if we have enough valid widgets
  if(!$re->get_widgets_by_name('MJPEG Stream')) {
    create_error_page('No MJPEG streams found.', $re->get_user_account());
  } else if(!$teleop = $re->get_widgets_by_name('Keyboard Teleop')) {
    create_error_page('No Keyboard Teloperation settings found.', $re->get_user_account());
  } else if(!$nav2d = $re->get_widgets_by_name('2D Navigation')) {
    create_error_page('No 2D Navaigation settings found.', $re->get_user_account());
  } else if(!$re->authorized()) {
    create_error_page('Invalid experiment for the current user.', $re->get_user_account());
  } else { // here we can spit out the HTML for our interface ?>
<!DOCTYPE html>
<html>
<head>
  <?php $re->create_head() // grab the header information ?>
<title>Simple Navigation Interface</title>

  <?php $re->make_ros() // connect to ROS ?>

<script type="text/javascript">
  ros.on('error', function() {
    alert('Lost communication with ROS.');
  });
</script>
</head>
<body>
  <section id="page">
    <section id="nav-interface">
      <table class="center">
        <tr>
          <td colspan="2"><h1>2D Navigation</h1></td>
        </tr>
        <tr>
          <td colspan="2"><div id="speed-container">
          <?php echo create_keyboard_teleop_with_slider($teleop[0])?>
            </div></td>
        </tr>
        <tr>
          <td><div id="mjpeg-widget">
          <?php echo create_multi_mjpeg_canvas_by_envid($re->get_envid(), 480, 360, 2)?>
            </div></td>
          <td><div id="nav-widget">
          <?php echo create_nav2d($nav2d[0], 480, 360)?>
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
?>

