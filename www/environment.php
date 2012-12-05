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
*  Version: October 9, 2012
*
*********************************************************************/
?>

<?php
// start the session
session_start();
include($_SERVER['DOCUMENT_ROOT'].'/interface/common.inc.php');

// check if there is a user logged in
if (!isset($_SESSION['userid'])) {
  header('Location: login/');
} else {
  // load the include files
  include($_SERVER['DOCUMENT_ROOT'].'/inc/config.inc.php');
  $query = mysqli_query($db, "SELECT * FROM user_accounts WHERE userid = '".$_SESSION['userid']."'");
  $usr = mysqli_fetch_array($query);
}

// check for valid information
if(isset($_GET['envid'])) {
  $envid = $db->real_escape_string($_GET['envid']);
  $sql = sprintf("SELECT * FROM environments WHERE envid = %d", $envid);
  $query = mysqli_query($db, $sql);
  $environment = mysqli_fetch_array($query);
} else {
  $environment = null;
}
if(isset($_GET['intid'])) {
  $intid = $db->real_escape_string($_GET['intid']);
  $sql = sprintf("SELECT * FROM interfaces WHERE intid = %d", $intid);
  $query = mysqli_query($db, $sql);
  $interface = mysqli_fetch_array($query);
} else {
  $interface = null;
}

// make sure the interface code exists
$error = null;
if(!$environment) {
  $error = "Valid environment not provided.";
} else if(!$interface) {
  $error = "Valid interface not provided.";
} else {
  // check the interface
  include($_SERVER['DOCUMENT_ROOT'].'/interface/'.$interface['location'].'/'.$interface['location'].'.php');
  // check if the class actually exists
  if(!class_exists($interface['location'])) {
    $error = "A class of type '".$interface['location']."' does not exist inside 'interface/".$interface['location']."/".$interface['location'].".php'.";
  } else {
    // create an interface object
    $iface = new $interface['location']();
    if(!($iface instanceof iface)) {
      $error = "Class '".$interface['location']."' does not implement 'iface'.";
    }
  }
}

// check for errors
if($error) {
  create_error_page($error, $usr);
} else {
  // grab all the widgets
  $sql = "SELECT * FROM widgets";
  $query = mysqli_query($db, $sql);

  // create arrays to hold the widgets
  $widgets = new WidgetList();

  // go through each widget type
  while($cur = mysqli_fetch_array($query)) {
    // grab the valid instances of this widget
    $sql = "SELECT * FROM ".$db->real_escape_string($cur['table'])." WHERE envid = ".$envid;
    $widget_query = mysqli_query($db, $sql);
    // only continue if we have at least one instance of this widget type
    if($widget_query && mysqli_num_rows($widget_query) > 0) {
      // grab the valid instances of this widget
      while($cur_widget = mysqli_fetch_array($widget_query)) {
        // store the widget to make calls later
        $widgets->list[] = new WidgetInfo($cur_widget, $cur['name'], $cur['script']);
      }
    }
  }

  // generate the interface
  $iface->generate_interface($environment, $widgets, $usr);
}
?>

