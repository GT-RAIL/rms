<?php
/**
 * Developer API index page.
 *
 * This page provides links to the REST, PHP, and JavaScript APIs.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 20 2012
 * @package    developers
 * @link       http://ros.org/wiki/rms
 */

// start the session
session_start();

// load the include files
include_once(dirname(__FILE__).'/../api/users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/../inc/head.inc.php');
include_once(dirname(__FILE__).'/../inc/content.inc.php');

$pagename = 'Developer APIs';

// grab the user info from the database
if(isset($_SESSION['userid'])) {
  $session_user = get_user_account_by_id($_SESSION['userid']);
} else {
  $session_user = null;
}
?>

<!DOCTYPE html>
<html>
<head>
<?php import_head('../') ?>
<title><?php echo $title.' :: '.$pagename?></title>

<script type="text/javascript">
  createMenuButtons();
</script>
</head>
<body>
<?php create_header($session_user, $pagename, '../')?>
  <section id="page">
    <section id="articles">
      <div class="line"></div>
      <article>
        <div class="center">
          <h2>Select the desired API Documentation</h2>
          <br /><br />
          <h3>
            <a href="http://www.ros.org/wiki/rms/rest">RMS REST API</a>
          </h3>
          <br /><br />
          <h3>
            <a href="php/">RMS PHP API</a>
          </h3>
          <br /><br />
          <h3>
            <a href="js/">RMS JavaScript API</a>
          </h3>
          <br />
        </div>
      </article>
    </section>
    <?php create_footer()?>
  </section>
</body>
</html>
