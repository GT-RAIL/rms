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
  include_once($_SERVER['DOCUMENT_ROOT'].'/inc/config.inc.php');
  include_once($_SERVER['DOCUMENT_ROOT'].'/inc/log.inc.php');

  // grab the user info from the database
  $query = mysqli_query($db, "SELECT * FROM user_accounts WHERE userid = '".$_SESSION['userid']."'");
  $usr = mysqli_fetch_array($query);
  // now make sure this is an admin
  if(strcmp($usr['type'], "admin") != 0) {
    // report this
    write_to_log($usr['username']." attempted to create an article.");
    // send the user back to their main menu
    header('Location: ../../main_menu.php');
  }

  // cleanup the fields
  $artid = $db->real_escape_string($_POST['artid']);
  $title = $db->real_escape_string($_POST['title']);
  $content = $db->real_escape_string($_POST['content']);
  $pageid = $db->real_escape_string($_POST['pageid']);
  $pageindex = $db->real_escape_string($_POST['pageindex']);

  // see if this is an update or a new article
  if($artid) {
    $sql = "UPDATE articles SET title = '".$title."', content = '".$content."', pageid = ".$pageid.", pageindex = ".$pageindex." WHERE artid = ".$artid;
    // log this
    write_to_log($usr['username']." updated article \"".$title."\".");
  } else {
    $sql = "INSERT INTO articles (title, content, pageid, pageindex) VALUES ('".$title."', '".$content."', ".$pageid.", ".$pageindex.")";
    // log this
    write_to_log($usr['username']." created a new article: \"".$title."\".");
  }

  // insert into the database
  mysqli_query($db, $sql);

  // send them back to the admin panel
  header('Location: ../../admin.php#pages-tab');
}
?>

