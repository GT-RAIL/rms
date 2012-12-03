<?php
/**
 * Main user menu page for RMS.
 *
 * If the logged in user is an admin, then all interface and environment options are available. If
 * this is just a regular user, then only their scheduled study environments will be displayed.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 3 2012
 * @link       http://ros.org/wiki/rms
 */

// start the session
session_start();

// check if a user is logged in
if (!isset($_SESSION['userid'])) {
  header('Location: login.php');
  return;
}

// load the include files
include_once(dirname(__FILE__).'/api/users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/inc/head.inc.php');
include_once(dirname(__FILE__).'/inc/content.inc.php');

$pagename = 'Main Menu';

// grab the user info from the database
$session_user = get_user_account_by_id($_SESSION['userid']);
?>

<!DOCTYPE html>
<html>
<head>
<?php import_head() ?>
<title><?php echo $title." :: ".$pagename?></title>

<script type="text/javascript" src="js/ros/ros_bundle.min.js"></script>
<script type="text/javascript">
  createMenuButtons();

  /**
   * A function to direct the page to the study environment session with the given settings.
   *
   * @param {int}
   *          expid the experiment's ID number
   * @param {int}
   *          intid the experiment's interface ID number
   * @param {int}
   *          envid the experiment's environment ID number
   */
	function beginStudy(expid, intid, envid) {
		window.location = 'environment.php?expid='+expid+'&intid='+intid+'&envid='+envid;
	}
</script>
</head>
<body>
<?php create_header($session_user, $pagename)?>
  <section id="page">
    <section id="articles">
      <div class="line"></div>
      <?php if($session_user['type'] === "admin") { //admin menu?>
      <article>
        <div class="center">
          <h2>Admin Interface Menu</h2>
        </div>
      </article>
      <?php
      $query = mysqli_query($db, "SELECT * FROM environments");
      while($cur = mysqli_fetch_array($query)) {?>
      <article>
        <div class="center">
          <h3>
          <?php echo $cur['envid'].": ".$cur['envaddr']." -- ".$cur['type']?>
          </h3>
          <script type="text/javascript">
						rosonline('<?php echo $cur['envaddr']?>', 9090, function(online) {
							if(online) {
							  $('#envstatus-<?php echo $cur['envid']?>').html('<b>Available</b>');
							} else {
						    $('#envstatus-<?php echo $cur['envid']?>').html('<b>Offline</b>');
              }
					  });
					</script>
          <div id="envstatus-<?php echo $cur['envid']?>"
            class="environment-status">Acquiring connection...</div>
            <?php if(strlen($cur['notes']) > 0) {
              echo $cur['notes'];
            }?>
          <div class="line"></div>

          <?php
          // check if the environment is enabled
          if($cur['enabled']) {?>
          <ul>
          <?php
          // go through each interface for this environment
          $interfaces = mysqli_query($db, "SELECT * FROM environment_interfaces WHERE envid=".$cur['envid']);
          while($pair = mysqli_fetch_array($interfaces)) {
            $int = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM interfaces WHERE intid=".$pair['intid']));
            ?>
            <li><a
              href="environment.php?envid=<?php echo $cur['envid']?>&intid=<?php echo $int['intid']?>">
              <?php echo $int['name']?> </a></li>
              <?php
          }?>
          </ul>
          <?php
          } else {?>
          <h3>Environment Disabled</h3>
          <?php
          }?>

        </div>
      </article>
      <?php
      }?>
      <?php
      } else {?>
      <div class="center">
        <h1>
          Welcome
          <?php echo $session_user['firstname']." ".$session_user['lastname']?>
          !
        </h1>
      </div>
      <article>
        <div class="center">
          <h2>My Studies</h2>
          <div class="line"></div>
          <p>
            <b>The following is a list of the available studies you are
              signed up for. If you are currently scheduled and you are
              ready to begin, simply select the 'Start!' button next to
              the appropriate study.</b>
          </p>
          <?php
          // populate the selection box
          $query = mysqli_query($db, "SELECT * FROM experiments WHERE userid=".$session_user['userid']);
          while($cur = mysqli_fetch_array($query)) {
            $cond = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM conditions WHERE condid=".$cur['condid']));
            $study = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM study WHERE studyid=".$cond['studyid']));
            echo "<h3>".$study['name']." (".$cur['start']." - ".$cur['end'].")</h3>";

            // grab the current timestamp from the SQL server
            $time = mysqli_fetch_array(mysqli_query($db, "SELECT CURRENT_TIMESTAMP()"));
            if($time['CURRENT_TIMESTAMP()'] >= $cur['start'] && $time['CURRENT_TIMESTAMP()'] <= $cur['end']) {
              echo "<button onclick=\"javascript:beginStudy(".$cur['expid'].", ".$cond['intid'].", ".$cur['envid'].");\">Start!</button>";
            } else {
              echo "<button disabled=\"disabled\">Start!</button>";
            }
					}?>
        </div>
      </article>
      <?php
			}?>
    </section>
    <?php create_footer()?>
  </section>
</body>
</html>

<?php
/**
 * A function to echo the HTML for the admin main menu.
 */
function create_admin_menu() {

}
?>
