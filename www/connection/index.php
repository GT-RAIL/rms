<?php
/**
 * Connection script for connecting to a robot environment with a given interface.
 *
 * If the logged in user is an admin, then all interface and environment options are available. If
 * this is just a regular user, then only their scheduled study environments will be displayed.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 12 2012
 * @package    connection
 * @link       http://ros.org/wiki/rms
 */

// start the session
session_start();

include_once(dirname(__FILE__).'/../api/users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/../api/config/logs/logs.inc.php');

// check if a user is logged in
if (!isset($_SESSION['userid'])) {
  header('Location: ../login');
  return;
}

$session_user = get_user_account_by_id($_SESSION['userid']);

// check the information that was given
if(!isset($_GET['envid']) || !isset($_GET['intid'])) {
  // invalid request, log this and return them back to the menu
  write_to_log($session_user['username'].' attempted to create an invalid connection.');
  header('Location: ../login');
  return;
}

// close the session so cURL can use it
session_write_close();

// forward the request to cURL which will access the RMS API (allows non-native clients to request interfces)
$curl = curl_init();
$prot = (isset($_SERVER['HTTPS'])) ? 'https://' : 'http://';
$get = '?request=generate&envid='.$_GET['envid'].'&intid='.$_GET['intid'];
curl_setopt($curl, CURLOPT_URL, $prot.$_SERVER['HTTP_HOST'].'/api/robot_environments/'.$get);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('RMS-Use-Session: true'));
curl_setopt($curl, CURLOPT_COOKIESESSION, true);
curl_setopt($curl, CURLOPT_COOKIE, session_name().'='.session_id());

// displays the resulting HTML
curl_exec($curl);
?>

