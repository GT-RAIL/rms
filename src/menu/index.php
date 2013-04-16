<?php
/**
 * Main user menu page for RMS.
 *
 * If the logged in user is an admin, then all interface and environment options
 * are available. If this is just a regular user, then only their scheduled
 * study environments will be displayed.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 15 2013
 * @package    menu
 * @link       http://ros.org/wiki/rms
 */

// start the session
session_start();

// check if a user is logged in
if (!isset($_SESSION['userid'])) {
    header('Location: ../login?goto=account/');
    return;
}

// load the include files
include_once(dirname(__FILE__).
        '/../api/users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).
        '/../api/robot_environments/robot_environments.inc.php');
include_once(dirname(__FILE__).
        '/../api/robot_environments/environments/environments.inc.php');
include_once(dirname(__FILE__).
        '/../api/robot_environments/interfaces/interfaces.inc.php');
include_once(dirname(__FILE__).
        '/../api/user_studies/conditions/conditions.inc.php');
include_once(dirname(__FILE__).'/../api/user_studies/studies/studies.inc.php');
include_once(dirname(__FILE__).
        '/../api/user_studies/experiments/experiments.inc.php');
include_once(dirname(__FILE__).'/../inc/head.inc.php');
include_once(dirname(__FILE__).'/../inc/content.inc.php');

$pagename = 'Main Menu';

// grab the user info from the database
$sessionUser = user_accounts::get_user_account_by_id($_SESSION['userid']);
?>
<!DOCTYPE html>
<html>
<head>
<?php head::import_head('../') ?>
<title><?php echo $title.' :: '.$pagename?></title>
<script type="text/javascript"
    src="http://cdn.robotwebtools.org/EventEmitter2/0.4.11/eventemitter2.js">
</script>
<script type="text/javascript"
    src="http://cdn.robotwebtools.org/roslibjs/r5/roslib.min.js"></script>
<script type="text/javascript">
  createMenuButtons();

  /**
   * A function to direct the page to the study environment session with the
   * given settings.
   *
   * @param {int}
   *          expid the experiment's ID number
   * @param {int}
   *          intid the experiment's interface ID number
   * @param {int}
   *          envid the experiment's environment ID number
   */
    function beginStudy(expid, intid, envid) {
        window.location = '../connection/?expid=' + expid + '&intid=' + intid 
                              + '&envid=' + envid;
    }
</script>
</head>
<body>
    <?php content::create_header($sessionUser, $pagename, '../')?>
    <section class="page">
        <div class="line"></div>
        <?php
//admin menu
if ($sessionUser['type'] === 'admin') {
?>
            <article>
                <div class="center">
                    <h2>Admin Interface Menu</h2>
                </div>
            </article>
    <?php
    $environments = environments::get_environments();
    foreach ($environments as $cur) {?>
            <article>
                <div class="center">
                    <h3>
                        <?php echo $cur['envid'].': ',$cur['envaddr']?>
                    </h3>
                    <script type="text/javascript">
                        rosonline('<?php echo $cur['protocol']?>', 
                                  '<?php echo $cur['envaddr']?>', 
                                  <?php echo $cur['port']?>, function(online) {
                            var id = '#envstatus-<?php echo $cur['envid']?>';
                            if (online) {
                              $(id).html('<b>Available</b>');
                            } else {
                              $(id).html('<b>Offline</b>');
                            }
                        });
                    </script>
                    <div id="envstatus-<?php echo $cur['envid']?>"
                        class="environment-status">Acquiring connection...</div>
                    <div class="line"></div>
        <?php
        // check if the environment is enabled
        if ($cur['enabled']) {?>
            <ul>
            <?php
            // go through each interface for this environment
            if ($p = robot_environments::
                    get_environment_interface_pairs_by_envid($cur['envid'])) {
                foreach ($p as $pair) {
                  $int = interfaces::get_interface_by_id($pair['intid']);
                  $url = '../connection/?envid='.$cur['envid'].'&intid='.
                             $int['intid']?>
                    <li><a href="<?php echo $url?>"><?php echo $int['name']?>
                    </a></li>
            <?php
                }
            }
            ?>
            </ul>
            <?php
        } else {?>
            <h3>Environment Disabled</h3>
            <?php
        }
        ?>
            </div>
        </article>
    <?php
    }
} else {
?>
        <div class="center">
            <h1>
                <?php echo 'Welcome '.$sessionUser['firstname'].' '
                     .$sessionUser['lastname'].'!'?>
            </h1>
        </div>
        <article>
            <div class="center">
                <h2>My Studies</h2>
                <div class="line"></div>
                <p>
                    <b>The following is a list of the available studies you
                        are signed up for. If you are currently scheduled
                        and you are ready to begin, simply select the
                        'Start!' button next to the appropriate study.</b>
                </p>
    <?php
    // populate the selection box
    if ($experiments = experiments::get_experiments_by_userid(
        $sessionUser['userid']
    )
    ) {
        foreach ($experiments as $cur) {
            $cond = conditions::get_condition_by_id($cur['condid']);
            $study = studies::get_study_by_id($cond['studyid']);
            echo '<h3>'.$study['name'].' ('.$cur['start'].' - '.
                $cur['end'].')</h3>';

            // grab the current timestamp from the SQL server
            $time = api::get_current_timestamp();
            if ($time >= $cur['start'] && $time <= $cur['end']) {
                echo '<button onclick="javascript:beginStudy('.$cur['expid'].
                    ', '.$cond['intid'].', '.
                    $cur['envid'].');">Start!</button>';
            } else {
                echo '<button disabled="disabled">Start!</button>';
            }
        }
    }
    ?>
            </div>
        </article>
        <?php
}
?>
    <?php content::create_footer()?>
    </section>
</body>
</html>
