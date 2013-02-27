<?php
/**
 * Interactive Markers API script. This allows the creation of Interactive Markers widgets using RMS
 * SQL entires. This is used throughout interface creation.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    February, 26 2013
 * @package    api.robot_environments.widgets.interactive_markers
 * @link       http://ros.org/wiki/rms
 */

/**
 * Create the Javascript needed for a given Interactive Markers widget. The Javascript will *NOT* be
 * wrapped in its own <script> tag. This should be placed somewhere in an existing JavaScript 
 * section after you have created a RMSDisplay. Note that this returns the JavaScript, you will need
 * to echo it in the appropriate spot.
 *
 * @param array $im The SQL entry for the interactive marker we are creating (this can be easily found in the interface's robot_environment object)
 * @return string The HTML to create the map object
 */
function add_interactive_markers_to_global_scene($im) {
  return '
  var tfClient = new TfClient({
    ros : ros,
    fixedFrame : \''.$im['fixed_frame'].'\',
    angularThres : 0.5,
    transThres : 0.001,
    rate : 29.0
  });
  var imClient = new ImProxy.Client(window._RMSDISPLAY.ros, tfClient);
  var meshBaseUrl = \'http://\' + location.hostname + \'/resources/\';
  var imViewer = new ImThree.Viewer(window._RMSDISPLAY.selectableObjs, window._RMSDISPLAY.camera, imClient,
      meshBaseUrl);
  imClient.subscribe(\''.$im['topic'].'\');
  ';
}
?>
