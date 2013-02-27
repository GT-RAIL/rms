<?php
/**
 * A PHP object to store information useful when generating an interface.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 12 2012
 * @package    api.robot_environments
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../api.inc.php');
include_once(dirname(__FILE__).'/../config/javascript_files/javascript_files.inc.php');
include_once(dirname(__FILE__).'/../users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/../user_studies/experiments/experiments.inc.php');
include_once(dirname(__FILE__).'/environments/environments.inc.php');
include_once(dirname(__FILE__).'/interfaces/interfaces.inc.php');
include_once(dirname(__FILE__).'/../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../../inc/content.inc.php');
include_once(dirname(__FILE__).'/../../inc/head.inc.php');

/**
 * The robot_environment class contains useful functions for gaining access to useful information
 * such as widget lists, ROS.JS connections, etc.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    February, 26 2013
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
   * The array of arrays of SQL widget entries. The first array is indexed by widget name. The
   * inner array is indexed only by numbers.
   * @var array
   */
  private $widgets;

  /**
   * The experiment SQL entry for any valid experiments found for the given user at this time. This
   * will be set to null if such an experiment does not exist.
   * @var array|null
   */
  private $experiment;

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

    // check for an experiment
    $this->experiment = get_valid_experiment_by_intid_userid_and_envid($intid, $userid, $envid);

    // generate the widget list
    $this->widgets = array();
    $all = get_widgets();
    foreach ($all as $cur) {
      if($cur_all = get_widget_instances_by_widgetid_and_envid($cur['widgetid'], $envid)) {
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
   * Get the experiment SQL entry found for the user, or null if none exist.
   *
   * @return array the experiment SQL entry found for the user, or null if none exist
   */
  function get_experiment() {
    return $this->experiment;
  }

  /**
   * Checks if the current user is authorized for this interface/environment. An authorized user
   * is either an admin or a regular user how has a valid experiment session now.
   *
   * @return boolean If the current user is authorized for this interface/environment
   */
  function authorized() {
    return $this->user_account['type'] === 'admin' || $this->experiment;
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
    
    // include three.js
    echo '
    <script type="text/javascript" src="'.$path.'js/threejs/three.js"></script>
    <script type="text/javascript" src="'.$path.'js/threejs/helvetiker_regular.typeface.js"></script>
    <script type="text/javascript" src="'.$path.'js/threejs/ColladaLoader.js"></script>
    ';

    // include the Javascript files
    $js = get_javascript_files();
    foreach ($js as $file) {
      echo '<script type="text/javascript" src="'.$path.$file['path'].'"></script>
      ';
    }
    // get the study and display scripts
    echo '
    <script type="text/javascript" src="'.$path.'js/rms/study.js"></script>
    <script type="text/javascript" src="'.$path.'js/rms/rmsdisplay.js"></script>
    ';
  }

  /**
   * Create common parts of the HTML head section for a study session. If the current user has a
   * valid experiment at this time, this function will create three JavaScript global variables.
   * The first, _EXPID, contains the experiment ID number for this user. The second, _TIME, is
   * the time remaining in microseconds remaining in their session at the time the page was loaded.
   * The final, _END, is the interval ID for the JavaScript time user to indicate the session is over.
   * This will go off in _TIME microseconds and will call a function called 'endStudy' if one exists.
   * All HTML will be echoed and should be placed in the HEAD section. If no valid experiment is found
   * for this user, nothing will be echoed.
   */
  function create_study_head() {
    global $db;

    // check for an experiment
    if($this->experiment) {
      // get the time difference
      $sql = sprintf("SELECT UNIX_TIMESTAMP('%s') - UNIX_TIMESTAMP('%s') AS time"
      , cleanse($this->experiment['end']), cleanse(get_current_timestamp()));
      $diff = mysqli_fetch_array(mysqli_query($db, $sql));

      echo '
      <script type="text/javascript">
        _EXPID = '.$this->experiment['expid'].';
        _TIME = '.($diff['time']*1000).';
        _END = setInterval(function() {
          if(typeof endStudy == \'function\') {
            endStudy();
          }
         }, _TIME);
      </script>
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
