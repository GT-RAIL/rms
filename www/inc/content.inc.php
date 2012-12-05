<?php
/**
 * Include functions for the HTML BODY section of the RMS.
 *
 * Provides several common functions to generate the HTML for the BODY section of the RMS HTML.
 * Examples include the header, menu, and footer.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 2 2012
 * @package    inc
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/config.inc.php');
include_once(dirname(__FILE__).'/../api/content/content_pages/content_pages.inc.php');

/**
 * A function to echo the HTML for the main header based on the given user and page name. If the
 * user is an admin, the admin menus will be shown.
 *
 * @param array|null $user the user SQL array for the current user (or null if none)
 * @param string $pagename the name of the page
 * @param string $path the relative path to the base RMS directory
 */
function create_header($user, $pagename, $path) {
  global $title, $db;
  echo '
  <header class="clear">
  <figure><img src="'.$path.'img/logo.png" /></figure>
  <hgroup><h1>'.$title.'</h1><h2>'.$pagename.'</h2></hgroup>
  </header>
  <div id="nav"><center><nav><ul>';

  // list all of the content pages
  $pages = get_content_pages();
  foreach ($pages as $cur) {
    echo '<li><a href="'.$path.'?pageid='.$cur['pageid'].'">'.$cur['menu'].'</a></li>';
  }

  // add the login page if the user is not logged in
  if(!$user) {
    echo '<li><a href="login.php">Login</a></li>';
  }
  echo '
  </ul></nav></center></div>';

  // the user menu
  if($user) {
    echo '
    <header class="clear"><table><tr>
    <td align="left"><h3>'.$user['firstname'].' '.$user['lastname'].'</h3></td>
    <td align="right">
    <span class="menu-main-menu"><a href="main_menu.php">Main Menu</a></span>&nbsp;
    <span class="menu-account"><a href="account.php">Account</a></span>&nbsp;';

    // check if this is an admin
    if($user['type'] === 'admin') {
      echo '
      <span class="menu-admin-panel"><a href="admin.php">Admin Panel</a></span>&nbsp;
      <span class="menu-study-panel"><a href="study.php">Study Panel</a> </span>&nbsp;';
    }
    echo '
    <span class="menu-logout"><a href="form/logout.php">Logout</a></span>&nbsp;
    </td></tr></table></header>
    ';
  }
}

/**
 * A function to echo the HTML for the page footer.
 */
function create_footer() {
  global $designed_by, $copyright;

  echo '
  <footer>
  <div class="line"></div>
  <table>
  <tr><td align="left">'.$designed_by.'</td><td align="right">'.$copyright.'</td></tr>
  </table>
  </footer>
  ';
}
?>

