<?php
/**
 * Keyboard Teleop API script. This allows the creation of Keyboard Teleop widgets using RMS SQL
 * entires. This is used throughout interface creation.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 13 2012
 * @package    api.robot_environments.widgets.keyboard_teleoperation
 * @link       http://ros.org/wiki/rms
 */

/**
 * Create the HTML and Javascript needed for a keyboard telop widget with an associated speed slider.
 * This creates two divs with IDs of speed-label and speed-slider. Custom CSS can be used to change
 * the appearance of these. Javascript is also created to bind the keyboard and link the slider.
 * This should be placed somewhere in your HTML BODY section. Note that this returns the HTML, you
 * will need to echo it in the appropriate spot.
 *
 * @param array $teleop The SQL entry for the teleop we are creating (this can be easily found in the interface's robot_environment object)
 * @param string|null $cb The name of a JavaScript function that will be called once the widget is created or null if no callback is being used (default is null)
 * @return string The HTML to create the widget and the slider
 */
function create_keyboard_teleop_with_slider($teleop, $cb = null) {
  $result = '
<div id="speed-label"></div>
<div id="speed-slider"></div>

<script type="text/javascript">
  var teleop = new KeyboardTeleop({
    ros : ros,
    topic : \''.$teleop['twist'].'\',
		throttle: '.$teleop['throttle'].'
	});

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
		';

  // check the callback
  if($cb) {
      $result .= $cb.'(teleop);
      ';
  }

  $result .= '</script>
  ';

  return $result;
}
?>
