2013-04-16 **0.3.0**
 * Source moved from www to src [(rctoris)](https://github.com/rctoris/)
 * ROS XML and build scripts removed [(rctoris)](https://github.com/rctoris/)
 * Main devel branch started [(rctoris)](https://github.com/rctoris/)
 * Authors file started [(rctoris)](https://github.com/rctoris/)
 * Phing build script added [(rctoris)](https://github.com/rctoris/)
 * Major code cleanup for Zend coding standard [(rctoris)](https://github.com/rctoris/)
 * JavaScript file management removed from RMS [(rctoris)](https://github.com/rctoris/)
 * Widget PHP script management removed from RMS [(rctoris)](https://github.com/rctoris/)
 * rmsdisplay.js removed [(rctoris)](https://github.com/rctoris/)
 * Local JQuery copy removed [(rctoris)](https://github.com/rctoris/)
 * Type and description removed from environment [(rctoris)](https://github.com/rctoris/)
 * MJPEG Server host and port added to environment [(rctoris)](https://github.com/rctoris/)

2013-03-08 **0.2.12**
 * Admin panel now correctly URI encodes the URL before making an AJAX call [(rctoris)](https://github.com/rctoris/)
 * "Powered by the Robot Management System" added to footer [(rctoris)](https://github.com/rctoris/)
 * topic_logger widget integrated [(rctoris)](https://github.com/rctoris/)

2013-02-26 **0.2.11**
 * Bug patched to correctly take into account a map's "continuous" flag [(rctoris)](https://github.com/rctoris/)
 * Bug patched to only include the update script if a config file already exists in initial setup [(rctoris)](https://github.com/rctoris/)
 * Three.js now included with RMS [(rctoris)](https://github.com/rctoris/)
 * Package names fixed in redirect index.php scripts [(rctoris)](https://github.com/rctoris/)
 * Interactivemarkersjs added to javascript_files [(rctoris)](https://github.com/rctoris/)
 * interactive_markers added as a widget type [(rctoris)](https://github.com/rctoris/)
 * Example interactive marker interface added [(rctoris)](https://github.com/rctoris/)
 * PR2 model added [(rctoris)](https://github.com/rctoris/)

2012-12-05 **0.2.1**
 * Bug patched to allow for apostrophes in config settings [(rctoris)](https://github.com/rctoris/)
 * Add to study_logs now in RMS API [(rctoris)](https://github.com/rctoris/)
 * Copyright notices updated [(rctoris)](https://github.com/rctoris/)

2012-12-05 **0.2.0**
 * CHANGES IN v0.2.0 ARE NOT BACKWARDS COMPATIBLE WITH RMS v0.1.1 OR EARLIER [(rctoris)](https://github.com/rctoris/)
 * Ordering fixed on articles [(rctoris)](https://github.com/rctoris/)
 * CSS issues fixed [(rctoris)](https://github.com/rctoris/)
 * Admin panel changed to use new API [(rctoris)](https://github.com/rctoris/)
 * Log RMS API script added [(rctoris)](https://github.com/rctoris/)
 * Password encryption changed to SHA1 (NOT BACKWARDS COMPATIBLE WITH RMS v0.1.1 OR EARLIER) [(rctoris)](https://github.com/rctoris/)
 * Database structure cleaned (NOT BACKWARDS COMPATIBLE WITH RMS v0.1.1 OR EARLIER) [(rctoris)](https://github.com/rctoris/)
 * Head include files now allow for relative paths [(rctoris)](https://github.com/rctoris/)
 * Pages moved to folders with their own index.php [(rctoris)](https://github.com/rctoris/)
 * Port and protocol added to environments [(rctoris)](https://github.com/rctoris/)
 * All interfaces ported to new API [(rctoris)](https://github.com/rctoris/)
 * SQL cleanse function added [(rctoris)](https://github.com/rctoris/)

2012-11-14 **0.1.1**
 * Constraints added to MySQL tables [(rctoris)](https://github.com/rctoris/)

2012-11-12 **0.1.0**
 * RMS REST API started [(rctoris)](https://github.com/rctoris/)
 * Login changed to use RMS API [(rctoris)](https://github.com/rctoris/)
 * Index pages (content pages) changed to use RMS API [(rctoris)](https://github.com/rctoris/)

2012-10-26 **0.0.61**
 * updated to use new Git repositories [(rctoris)](https://github.com/rctoris/)

2012-10-25 **0.0.6**
 * initial stages of experiment logging added [(rctoris)](https://github.com/rctoris/)
 * study table added [(rctoris)](https://github.com/rctoris/)
 * condition table added [(rctoris)](https://github.com/rctoris/)
 * experiments table added [(rctoris)](https://github.com/rctoris/)
 * study_log table added [(rctoris)](https://github.com/rctoris/)
 * form/study_log.php script added [(rctoris)](https://github.com/rctoris/)
 * Study Panel added for admins [(rctoris)](https://github.com/rctoris/)
 * width and height removed from nav2d.php API [(rctoris)](https://github.com/rctoris/)
 * 2D Navigation interface updated to use new version of nav2d.php API [(rctoris)](https://github.com/rctoris/)
 * User main menu updated to only allow for valid studies [(rctoris)](https://github.com/rctoris/)
 * Callback added to mjpeg_canvas.php API [(rctoris)](https://github.com/rctoris/)
 * removed some $_SERVER['DOCUMENT_ROOT'] [(rctoris)](https://github.com/rctoris/)
 * login now redirects to the page that required you to login [(rctoris)](https://github.com/rctoris/)
 * code cleanup [(rctoris)](https://github.com/rctoris/)
 * URL icon updated [(rctoris)](https://github.com/rctoris/)

2012-10-20 **0.0.54**
 * Change of rosjs GitHub raw file location to use new bundled version [(rctoris)](https://github.com/rctoris/)

2012-10-14 **0.0.53**
 * Minor code cleanup in interface/widget/mjpeg_canvas.php [(rctoris)](https://github.com/rctoris/)
 * Change of rosjs GitHub raw file location [(rctoris)](https://github.com/rctoris/)
 * Updated interface/widget/keyboard_teleop.php to use new version of keyboardteleop.js [(rctoris)](https://github.com/rctoris/)
 * Updated interfaces to use new version of interface/widget/keyboard_teleop.php [(rctoris)](https://github.com/rctoris/)
 * Update script now also updates the Javascript files [(rctoris)](https://github.com/rctoris/)

2012-10-12 **0.0.52**
 * nav2d.php now accepts a callback to return the Nav2D object [(rctoris)](https://github.com/rctoris/)

2012-10-03 **0.0.51**
 * SQL injection vulnerability patched [(rctoris)](https://github.com/rctoris/)

2012-09-26 **0.0.5**
 * mjpeg_canvas.php now makes use of multi-stream canvas feature [(rctoris)](https://github.com/rctoris/)
 * basic and simple_nav2d interfaces now use multi-stream MJPEG canvas feature [(rctoris)](https://github.com/rctoris/)

2012-09-20 **0.0.4**
 * Added map2d.js, actionclient.js, and nav2d.js to the list of downloaded JS files [(rctoris)](https://github.com/rctoris/)
 * Added map2d and nav2d widget types [(rctoris)](https://github.com/rctoris/)
 * Fixed incorrect variable name in create_error_page (interface/common.inc.php) [(rctoris)](https://github.com/rctoris/)
 * Fixed whitespace errors in admin panel [(rctoris)](https://github.com/rctoris/)

2012-09-16 **0.0.3**
 * Downloaded Javascript files now stored in a SQL table [(rctoris)](https://github.com/rctoris/)

2012-09-15 **0.0.22**
 * Downloaded Javascript file links changed to new RobotWebTools GitHub [(rctoris)](https://github.com/rctoris/)

2012-09-13 **0.0.21**
 * Fixed bugs in update script [(rctoris)](https://github.com/rctoris/)

2012-09-12 **0.0.2**
 * Site Maintenance tab created [(rctoris)](https://github.com/rctoris/)

2012-09-05 **0.0.1**
 * Initial release [(rctoris)](https://github.com/rctoris/)
