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
*  Version: September 4, 2012
*
*********************************************************************/
?>

<?php
// start the session
session_start();

// check if the user is logged in
if (!isset($_SESSION['userid']))
// send them to the home page
  header('Location: ../../index.php');
else {
  // load the include files
  include($_SERVER['DOCUMENT_ROOT'].'/inc/config.inc.php');
  include($_SERVER['DOCUMENT_ROOT'].'/inc/log.inc.php');

  // grab the user info from the database
  $query = mysqli_query($db, "SELECT * FROM user_accounts WHERE userid = '".$_SESSION['userid']."'");
  $usr = mysqli_fetch_array($query);
  // now make sure this is an admin
  if(strcmp($usr['type'], "admin") != 0) {
    // report this
    write_to_log($usr['username']." attempted to create a widget-by-id.");
    // send the user back to their main menu
    header('Location: ../../main_menu.php');
  }

  // cleanup the fields
  $widgetid = $db->real_escape_string($_POST['widgetid']);
  $id = $db->real_escape_string($_POST['id']);
  // grab the widget information
  $sql = "SELECT * FROM widgets WHERE widgetid = ".$widgetid;
  echo $sql;
  $query = mysqli_query($db, $sql);
  $widget = mysqli_fetch_array($query);
  // store the values in an array for building the SQL
  $attributes = array();
  $values = array();
  $sql = "SHOW COLUMNS FROM ".$widget['table'];
  $query = mysqli_query($db, $sql);
  while($cur = mysqli_fetch_array($query)) {
    // do not add ID here
    if (strcmp($cur['Field'], "id") != 0) {
      $attributes[] = $cur['Field'];
      $values[] = $db->real_escape_string($_POST[$cur['Field']]);
    }
  }

  // see if this is an update or a new widget
  if($id) {
    // update the database
    $sql = "UPDATE ".$widget['table']." SET ";
    $num_attr = count($attributes);
    for ($i = 0; $i < $num_attr; $i++) {
      $sql = $sql."`".$attributes[$i]."` = '".$values[$i]."'";
      if ($i < ($num_attr - 1))
        $sql = $sql.", ";
    }
    $sql = $sql." WHERE id = ".$id;
    // log this
    write_to_log($usr['username']." updated ".$widget['name']." \"".$id."\".");
  } else {
    // insert into the database
    $sql = "INSERT INTO ".$widget['table']." (";
    $num_attr = count($attributes);
    for ($i = 0; $i < $num_attr; $i++) {
      $sql = $sql."`".$attributes[$i]."`";
      if ($i < ($num_attr - 1))
        $sql = $sql.", ";
    }
    $sql = $sql.") VALUES (";
    for ($i = 0; $i < $num_attr; $i++) {
      $sql = $sql."'".$values[$i]."'";
      if ($i < ($num_attr - 1))
        $sql = $sql.", ";
    }
    $sql = $sql.")";

    // log this
    write_to_log($usr['username']." created a new ".$widget['name'].": \"".$id."\".");
  }

  mysqli_query($db, $sql);

  // send them back to the admin panel
  header('Location: ../../admin.php#environments-tab');
}
?>

