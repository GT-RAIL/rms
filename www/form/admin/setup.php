<?php
/*********************************************************************
 *
* Software License Agreement (BSD License)
*
*  Copyright (c) 2012, Worcester Polytechnic Institute
*  All rights reserved.
*
*  Redistribution and use in source and binary forms, with or without
*  modification, are permitted provided that the following conditions
*  are met:
*
*   * Redistributions of source code must retain the above copyright
*     notice, this list of conditions and the following disclaimer.
*   * Redistributions in binary form must reproduce the above
*     copyright notice, this list of conditions and the following
*     disclaimer in the documentation and/or other materials provided
*     with the distribution.
*   * Neither the name of the Worcester Polytechnic Institute nor the
*     names of its contributors may be used to endorse or promote
*     products derived from this software without specific prior
*     written permission.
*
*  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
*  "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
*  LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
*  FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
*  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
*  INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
        *  BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
        *  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
*  CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
*  LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
*  ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
*  POSSIBILITY OF SUCH DAMAGE.
*
*   Author: Russell Toris
*  Version: September 12, 2012
*
*********************************************************************/
?>

<?php
// check if this is an admin panel request
if(isset($_POST['req'])) {
  // start the session
  session_start();

  // load the include files
  include_once($_SERVER['DOCUMENT_ROOT'].'/inc/config.inc.php');
  include_once($_SERVER['DOCUMENT_ROOT'].'/inc/log.inc.php');

  // grab the user info from the database
  $sql ="SELECT * FROM user_accounts WHERE userid = '".$_SESSION['userid']."'";
  $query = mysqli_query($db, $sql);
  $usr = mysqli_fetch_array($query);
  // now make sure this is an admin
  if(strcmp($usr['type'], "admin") != 0) {
    // report this
    write_to_log($usr['username']." attempted to use the setup script.");
    // send the user back to their main menu
    header('Location: ../../main_menu.php');
  }

  // it is an admin, check the request type
  if(strcmp($_POST['req'], "reconfig") == 0) {
    // check if the passwords match
    if(strcmp($_POST['dbpass'], $_POST['dbpass-confirm']) != 0) {
      // set an error message and return to the admin panel
      $_SESSION['error'] = "Passwords did not match. Site modification failed.";
      header('Location: ../../admin.php#site-tab');
    } else {
      // cleanup the request
      $host = $db->real_escape_string($_POST['host']);
      $dbuser = $db->real_escape_string($_POST['dbuser']);
      // check for a new password
      if(strcmp($_POST['dbpass'], "**********") != 0)
        $dbpass = $db->real_escape_string($_POST['dbpass']);
      $dbname = $db->real_escape_string($_POST['db']);
      $site_name = $db->real_escape_string($_POST['site-name']);
      $google = $db->real_escape_string($_POST['google']);
      $copyright = $db->real_escape_string($_POST['copyright']);

      // remove the old file
      unlink('../../inc/config.inc.php');
      // create the new file
      create_config($host, $dbuser, $dbpass, $dbname, $site_name, $google, $copyright);

      // log this
      write_to_log($usr['username']." modified the configuration file.");
      // send them back to the admin panel
      header('Location: ../../admin.php#site-tab');
    }
  } else if(strcmp($_POST['req'], "update") == 0) {
    // cleanup the old files
    cleanup($db);
    // get the new files
    download_all($db);

    // log this
    write_to_log($usr['username']." updated the Javascript files.");

    // send them back to the admin panel
    header('Location: ../../admin.php#maintenance-tab');
  }
}

?>

