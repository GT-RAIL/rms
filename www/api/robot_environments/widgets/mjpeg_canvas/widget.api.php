<?php
/**
 * Keyboard Teleop API script. This allows the creation of Keyboard Teleop widgets using RMS SQL
 * entires. This is used throughout interface creation.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 13 2012
 * @package    api.robot_environments.widgets.mjpeg_canvas
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../../environments/environments.inc.php');

/**
 * Get an array of all MJPEG stream SQL entires for the given environment or null if none exist.
 * Enter description here ...
 * @param integer $envid The environment ID
 * @return array|null All MJPEG stream SQL entires for the given environment or null if none exist
 */
function get_mjpeg_streams_by_envid($envid) {
  global $db;

  // grab the entries and push them into an array
  $result = array();
  $sql = sprintf( "SELECT * FROM `mjpeg_streams` WHERE `envid`='%d'"
  , $db->real_escape_string($envid));
  $query = mysqli_query($db, $sql);
  while($cur = mysqli_fetch_assoc($query)) {
    $result[] = $cur;
  }

  return (count($result) === 0) ? null : $result;
}

/**
 * Create the HTML and Javascript needed to create a multi-stream MJPEG canvas with the given information.
 * This will create an HTML5 canvas element to render on and thus should be placed in the BODY section
 * of the HTML. Note that this returns the HTML, you will need to echo it in the appropriate spot.
 *
 * @param integer $envid The environment to get the list of MJPEG streams from
 * @param integer $width The width of the canvas/stream
 * @param integer $height The height of the canvas/stream
 * @param integer $default The index of the default stream (0 by default)
 * @param string|null $cb The name of a JavaScript function that will be called once the widget is created or null if no callback is being used (default is null)
 * @return string The HTML to create the widget and the slider
 */
function create_multi_mjpeg_canvas_by_envid($envid, $width, $height, $default = 0, $cb = null) {
  // check the information
  if(!$environment = get_environment_by_id($envid)) {
    return '<h2>Invalid Environment Provided</h2>';
  } else if(!$streams = get_mjpeg_streams_by_envid($envid)) {
    return '<h2>No MJPEG Streams Found</h2>';
  }

  // check the default
  $num_streams = count($streams);
  $default = min($default, $num_streams-1);

  // create the widget with a unique ID
  $id = time() + rand(0, time());

  $result = '
<canvas class="mjpeg-stream" id="mjpeg-canvas-'.$id.'" width="'.$width.'" height="'.$height.'"></canvas>

<script type="text/javascript">
';

  // create the topic/label lists
  $topic = 'var topic = [';
  $label = 'var label = [';
  for ($i = 0; $i < $num_streams; $i++) {
    $topic .= '\''.$streams[$i]['topic'].'\'';
    $label .= '\''.$streams[$i]['label'].'\'';
    if($i != $num_streams-1) {
      $topic .= ', ';
      $label .= ', ';
    }
  }
  $topic .= '];
  ';
  $label .= '];
  ';

  $result .= $topic.$label;

  // create the stream
  $result .= '
    var mjpeg = new MjpegCanvas({
      host : \''.$environment['envaddr'].'\',
      topic : topic,
      label : label,
      canvasID : \'mjpeg-canvas-'.$id.'\',
      width : '.$width.',
      height : '.$height.',
      defaultStream : '.$default.'
    });

    mjpeg.on(\'error\', function(){});
  ';

  // check the callback
  if($cb) {
      $result .= $cb.'(mjpeg);
      ';
  }

  $result .= '</script>
  ';

  return $result;
}
?>
