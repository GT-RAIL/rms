<?php
/**
 * A PHP object to store information useful when generating an interface.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 12 2012
 * @package    api.robot_environments
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../config/javascript_files/javascript_files.inc.php');
include_once(dirname(__FILE__).'/../users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/environments/environments.inc.php');
include_once(dirname(__FILE__).'/interfaces/interfaces.inc.php');
include_once(dirname(__FILE__).'/../../inc/content.inc.php');
include_once(dirname(__FILE__).'/../../inc/head.inc.php');

/**
 * The robot_environment class contains useful functions for gaining access to useful information
 * such as widget lists, ROS.JS connections, etc.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 12 2012
 * @package    api.robot_environments
 */
class robot_environment {
  /**
   * The user account SQL array for the current user.
   * @var array
   */
  private $user_account;

  /**
   * The environment SQL array for the current environment.
   * @var array
   */
  private $environment;

  /**
   * The interface SQL array for the current interface.
   * @var array
   */
  private $interface;

  /**
   * The an array of arrays of SQL widget entries. The first array is indexed by widget name. The
   * inner array is indexed only by numbers.
   * @var array
   */
  private $widgets;

  /**
   * Creates a robot_environment using the given information.
   *
   * @param integer $userid The ID of the currently logged in user
   * @param integer $envid The ID of the environment to use
   * @param integer $intid The ID of the interface to use
   */
  function __construct($userid, $envid, $intid) {
    $this->user_account = get_user_account_by_id($userid);
    $this->environment = get_environment_by_id($envid);
    $this->interface = get_interface_by_id($intid);

    // generate the widget list
    $this->widgets = array();
    $all = get_widgets();
    foreach ($all as $cur) {
      if($cur_all = get_widget_instances_by_widgetid($cur['widgetid'])) {
        $this->widgets[$cur['name']] = $cur_all;

        // include all of the widget scripts
        $file = dirname(__FILE__).'/widgets/'.$cur['script'].'/widget.api.php';
        if(file_exists($file)) {
          include_once($file);
        }
      }
    }
  }

  /**
   * Get an array of all widgets for this robot environment/interface with the given widget name.
   *
   * @param string $name The name of the widget
   * @return array The array of widgets for this environment/interface or null if none exist
   */
  function get_widgets_by_name($name) {
    return isset($this->widgets[$name]) ? $this->widgets[$name] : null;
  }

  /**
   * Get the user account SQL array for the user that is currently logged in.
   *
   * @return array the user account SQL array
   */
  function get_user_account() {
    return $this->user_account;
  }

  /**
   * Get the environment ID for the associated environment.
   *
   * @return integer The environment ID
   */
  function get_envid() {
    return $this->environment['envid'];
  }

  /**
   * Get the interface ID for the associated interface.
   *
   * @return integer The interface ID
   */
  function get_intid() {
    return $this->interface['intid'];
  }

  /**
   * Create common parts of the HTML head section. This includes metadata, common RMS CSS files,
   * all widget and RMS Javascript files, and a custom style.css class if it exists in the interface.
   * All HTML will be echoed.
   */
  function create_head() {
    $prot = (isset($_SERVER['HTTPS'])) ? 'https://' : 'http://';
    $path = $prot.$_SERVER['HTTP_HOST'].'/';

    // grab the common information
    import_head($path);

    // check for a style sheet
    if(file_exists(dirname(__FILE__).'/interfaces/'.$this->interface['location'].'/style.css')) {
      $css = $path.'api/robot_environments/interfaces/'.$this->interface['location'].'/style.css';
      echo '<link rel="stylesheet" type="text/css" href="'.$css.'" />
      ';
    }

    // include the Javascript files
    $js = get_javascript_files();
    foreach ($js as $file) {
      echo '<script type="text/javascript" src="'.$path.$file['path'].'"></script>
      ';
    }
  }

  /**
   * Create the HTML to create a connection to ROS using ros.js. This will be wrapped in its own
   * <script> tag and will declare a global variable called 'ros'. It can be placed anywhere in the
   * HEAD section. All HTML will be echoed.
   */
  function make_ros() {
    echo '
<script type="text/javascript">
  ros = new ROS(\''.$this->environment['protocol'].$this->environment['envaddr'].':'.$this->environment['port'].'\');
</script>
    ';
  }
}
?>
