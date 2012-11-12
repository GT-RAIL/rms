<?php
/**
 * Include functions for the HTML HEAD section of the RMS.
 *
 * Provides several common functions to generate the HTML for the HEAD section of the RMS HTML.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 12 2012
 * @package    inc
 * @link       http://ros.org/wiki/rms
 */

if(file_exists(dirname(__FILE__).'/inc/config.inc.php')) {
  include_once(dirname(__FILE__).'/config.inc.php');
}

/**
 * A function to echo the HTML for the HEAD section of the HTML document. This includes metadata,
 * common CSS and Javascript files, and the Google Analytics code if it exists.
 */
function import_head() {
  import_meta();
  import_common_css();
  import_common_js();
  import_analytics();
}

/**
 * A function to echo the HTML to set the meta information.
 */
function import_meta() {
  echo '
  <meta	http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  ';
}

/**
 * A function to echo the HTML to import common CSS files.
 */
function import_common_css() {
  echo '
  <link rel="stylesheet" type="text/css" href="css/common.css" />
  <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.22.custom.css" />
  ';
}

/**
 * A function to echo the HTML to import common JS files.
 */
function import_common_js() {
  echo '
  <script type="text/javascript" src="js/rms/common.js"></script>
  <script type="text/javascript" src="js/jquery/jquery-1.7.2.min.js"></script>
  <script type="text/javascript" src="js/jquery/jquery-ui-1.8.22.custom.min.js"></script>
  ';
}

/**
 * A function to echo the HTML to import Google Analytics settings.
 */
function import_analytics() {
  global $google_tracking_id;

  // check if tracking is being used
  if($google_tracking_id) {
    echo '
    <script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(["_setAccount", "'.$google_tracking_id.'"]);
    _gaq.push(["_trackPageview"]);
    (function() {
    var ga = document.createElement("script"); ga.type = "text/javascript"; ga.async = true;
    ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ga, s);
  })();
  </script>
  ';
  }
}
?>
