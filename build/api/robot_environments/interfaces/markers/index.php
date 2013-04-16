<?php
/**
 * A basic interface to display a set of interactive markers with camera feeds
 * and keybaord teleoperation.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 15 2013
 * @package    api.robot_environments.interfaces.markers
 * @link       http://ros.org/wiki/rms
 */

/**
 * A static class to contain the interface generate function.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 15 2013
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
<title>Interactive Markers</title>
<script type="text/javascript"
    src="http://cdn.robotwebtools.org/threejs/r56/three.min.js">
</script>
<?php $collada = 'ColladaAnimationCompress/0.0.1/ColladaLoader2.min.js'?>
<script type="text/javascript"
    src="http://cdn.robotwebtools.org/<?php echo $collada?>">
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
  src="http://cdn.robotwebtools.org/ros3djs/r4/ros3d.min.js">
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

    // create the main viewer
    var viewer = new ROS3D.Viewer({
      divID : 'markers',
      width : 480,
      height : 360,
      antialias : true
    });
    viewer.addObject(new ROS3D.Grid());

    // setup a client to listen to TFs
    var tfClient = new ROSLIB.TFClient({
      ros : ros,
      angularThres : 0.01,
      transThres : 0.01,
      rate : 10.0,
      fixedFrame : '<?php echo $im[0]['fixed_frame'] ?>'
    });

    // xetup the marker client
    var imClient = new ROS3D.InteractiveMarkerClient({
      ros : ros,
      tfClient : tfClient,
      topic : '<?php echo $im[0]['topic'] ?>',
      camera : viewer.camera,
      rootObject : viewer.selectableObjects,
      path : 'http://resources.robotwebtools.org/'
    });
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
                    <div id='markers' class="scene"></div>
                </td>
            </tr>
        </table>
    </section>
</body>
</html>
<?php
        }
    }
}

