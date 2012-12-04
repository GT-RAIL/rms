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

// check if there is a user logged in
if (!isset($_SESSION['userid'])) {
  echo "INVALID SESSION";
  return;
}
// load the include files
include_once('../inc/log.inc.php');
include_once('../api/users/user_accounts/user_accounts.inc.php');
include_once('../api/robot_environments/environments/environments.inc.php');

// grab the user info from the database
$user = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM user_accounts WHERE userid = '".$_SESSION['userid']."'"));
// now make sure this is an admin
if($user['type'] !== "admin") {
  // report this
  write_to_log($user['username']." attempted to create an admin popup.");
  // send the user back to their main menu
  echo "INVALID ACCESS";
  return;
}

// check the type
if ($_GET['type'] === "pages") {
  create_page_editor(isset($_GET['id']) ? $_GET['id'] : null);
} else if ($_GET['type'] === "environments") {
  create_environment_editor(isset($_GET['id']) ? $_GET['id'] : null);
} else if ($_GET['type'] === "articles") {
  create_article_editor(isset($_GET['id']) ? $_GET['id'] : null);
} else if ($_GET['type'] === "widgets") {
  create_widget_editor(isset($_GET['id']) ? $_GET['id'] : null);
} else if ($_GET['type'] === "environment-interface") {
  create_environment_interface_editor(isset($_GET['id']) ? $_GET['id'] : null);
} else if ($_GET['type'] === "interfaces") {
  create_interface_editor(isset($_GET['id']) ? $_GET['id'] : null);
} else if (strpos($_GET['type'], "widget-") === 0) {
  create_widget_id_editor(substr($_GET['type'], 7), isset($_GET['id']) ? $_GET['id'] : null);
} else if ($_GET['type'] === "slideshow") {
  create_slide_editor(isset($_GET['id']) ? $_GET['id'] : null);
} else if ($_GET['type'] === "site") {
  create_site_editor();
} else if ($_GET['type'] === "site-status") {
  create_db_update();
} else if ($_GET['type'] === "javascript") {
  create_js_update();
} else {
  // report this
  write_to_log($user['username']." attempted to create an invalid popup type: \"".$_GET['type']."\"");
  echo "INVALID TYPE: ".$_GET['type'];
}


/**
 * A function to echo the HTML for the popup dialog used to create an environment.
 *
 * @param int $id the envrironment ID number to create the popup for, or null if it is a new entry
 */
function create_environment_editor($id) {
  echo get_environment_editor_html($id);
}

/**
 * A function to echo the HTML for the popup dialog used to create a new interface.
 *
 * @param int $id the interface ID number to create the popup for, or null if it is a new entry
 */
function create_interface_editor($id) {
  global $db;

  // see if a widget exists with the given id
  $query = mysqli_query($db, sprintf("SELECT * FROM interfaces WHERE intid=%d", $db->real_escape_string($id)));
  if($query) {
    $cur = mysqli_fetch_array($query);
  } else {
    $cur = null;
	}?>

<p>Complete the following form to create a new interface.</p>
<form action="form/admin/create_interface.php" method="POST">
  <fieldset>
    <ol>
      <?php if($cur) {// only show the ID for edits?>
      <li><label for="intid">Interface ID</label> <input type="text"
        name="intid" id="intid" value="<?php echo $cur['intid']?>"
        readonly="readonly" />
      </li>
      <?php
		}?>
      <li><label for="name">Name</label> <input type="text" name="name"
        value="<?php echo $cur['name']?>" id="name"
        placeholder="e.g., My Cool Interface" required />
      </li>
      <li><label for="location">Location</label> <?php
      // check for unused interface folders
      $dir  = opendir($_SERVER['DOCUMENT_ROOT'].'/interface');
      $files = array();
      while ($f = readdir($dir))
        if(is_dir($_SERVER['DOCUMENT_ROOT'].'/interface/'.$f) && substr($f, 0, 1) != "." && strcmp($f, "widget") != 0) {
        // check if the folder has been used
      $query = mysqli_query($db, "SELECT * FROM interfaces WHERE location='".$f."'");
      if(mysqli_num_rows($query) === 0) {
        $files[] = $f;
      }
      }
      if(count($files) > 0) {?> <select name="location" id="location">
          <?php
          // put in each option
          foreach($files as $f) {
            $selected = "";
            // check if this file is the same
            if(strcmp($cur['location'], $f) === 0) {
              $selected = "selected=\"selected\"";
				}?>
          <option value="<?php echo $f?>" <?php echo $selected?>>
            <?php echo $f?>
          </option>
          <?php
			}?>
      </select>
      </li>
    </ol>
    <input type="submit" value="Submit" />
    <?php
			} else {// put dummy dropdown in?>
    <select name="location" id="location" disabled="true"><option
        value="void">No unused interface locations found in 'interface/'
        folder.</option>
    </select>
    </li>
    </ol>
    <input type="submit" value="Submit" disabled="true" />
    <?php
			}?>
  </fieldset>
</form>
<?php
}

/**
 * A function to echo the HTML for the popup dialog used to create a new environment-interface pairing.
 *
 * @param int $id the environment-interface pair ID number to create the popup for, or null if it is a new entry
 */
function create_environment_interface_editor($id) {
  global $db;

  // see if a pair exists with the given id
  $query = mysqli_query($db, sprintf("SELECT * FROM environment_interfaces WHERE pairid=%d", $db->real_escape_string($id)));
  if($query) {
    $cur = mysqli_fetch_array($query);
  } else {
    $cur = null;
	}?>

<p>Complete the following form to create a new environment-interface
  pairings.</p>
<form action="form/admin/create_environment_interface_pair.php"
  method="POST">
  <fieldset>
    <ol>
      <?php if($cur) {// only show the ID for edits?>
      <li><label for="intid">Pair ID</label> <input type="text"
        name="pairid" id="pairid" value="<?php echo $cur['pairid']?>"
        readonly="readonly" />
      </li>
      <?php
		}?>
      <li><label for="envid">Environment</label><select name="envid"
        id="envid" required>
          <?php
          // grab all environments
          $env_query = mysqli_query($db, "SELECT * FROM environments");
          while($cur_env = mysqli_fetch_array($env_query)) {
            $selected = "";
            // check if this envid is the same
            if($cur['envid'] === $cur_env['envid']){
              $selected = "selected=\"selected\"";
					}?>
          <option value="<?php echo $cur_env['envid']?>"
          <?php echo $selected?>>
            <?php echo $cur_env['envid'].": ".$cur_env['envaddr']." -- ".$cur_env['type']." :: ".$cur_env['notes']?>
          </option>
          <?php
          }
          ?>
      </select>
      </li>
      <li><label for="intid">Interface</label><select name="intid"
        id="intid" required>
          <?php
          // grab all environments
          $int_query = mysqli_query($db, "SELECT * FROM interfaces");
          while($cur_int = mysqli_fetch_array($int_query)) {
            $selected = "";
            // check if this envid is the same
            if($cur['intid'] === $cur_int['intid']){
              $selected = "selected=\"selected\"";
					}?>
          <option value="<?php echo $cur_int['intid']?>"
          <?php echo $selected?>>
            <?php echo $cur_int['intid'].": ".$cur_int['name']?>
          </option>
          <?php
          }
          ?>
      </select>
      </li>
    </ol>
    <input type="submit" value="Submit" />
  </fieldset>
</form>
<?php
}

/**
 * A function to echo the HTML for the popup dialog used to create a new widget.
 *
 * @param int $id the widget ID number to create the popup for, or null if it is a new entry
 */
function create_widget_editor($id) {
  global $db;

  // see if a widget exists with the given id
  $query = mysqli_query($db, sprintf("SELECT * FROM widgets WHERE widgetid=%d", $db->real_escape_string($id)));
  if($query) {
    $cur = mysqli_fetch_array($query);
  } else {
    $cur = null;
	}?>

<p>Complete the following form to create a new widget.</p>
<form action="form/admin/create_widget.php" method="POST">
  <fieldset>
    <ol>
      <?php if($cur) {// only show the ID for edits?>
      <li><label for="widgetid">Widget ID</label> <input type="text"
        name="widgetid" id="widgetid"
        value="<?php echo $cur['widgetid']?>" readonly="readonly" />
      </li>
      <?php
		}?>
      <li><label for="name">Name</label> <input type="text" name="name"
        placeholder="e.g., My Cool Widget"
        value="<?php echo $cur['name']?>" id="name" required />
      </li>
      <li><label for="table">MySQL Table</label> <input type="text"
        placeholder="e.g., my_widget_info" name="table"
        value="<?php echo $cur['table']?>" id="table" />
      </li>
      <li><label for="script">PHP Script</label> <input type="text"
        placeholder="e.g., create_my_widget" name="script"
        value="<?php echo $cur['script']?>" id="script" />
      </li>
    </ol>
    <input type="submit" value="Submit" />
  </fieldset>
</form>
<?php
}

/**
 * A function to echo the HTML for the popup dialog used to create a widget of the given ID type.
 *
 * @param int $widgetid the widget ID number to create the popup for
 * @param int $id the ID number within the given widget to create the popup for, or null if it is a new entry
 */
function create_widget_id_editor($widgetid, $id) {
  global $db;

  // see if a widget exists with the given id
  $widget = mysqli_fetch_array(mysqli_query($db, sprintf("SELECT * FROM widgets WHERE widgetid=%d", $db->real_escape_string($widgetid))));
  if($id) {
    $cur = mysqli_fetch_array(mysqli_query($db, sprintf("SELECT * FROM ".$widget['table']." WHERE id=%d", $db->real_escape_string($id))));
  } else {
    $cur = null;
	}?>

<p>
  Complete the following form to create a new
  <?php echo $widget['name']?>
  .
</p>
<form action="form/admin/create_widget_by_id.php" method="POST">
  <fieldset>
    <ol>
      <input type="hidden" name="widgetid"
        value="<?php echo $widgetid?>" id="widgetid" readonly="readonly" />
      <?php if($cur) {// only show the ID for edits?>
      <li><label for="id">ID</label> <input type="text" name="id"
        id="id" value="<?php echo $cur['id']?>" readonly="readonly" />
      </li>
      <?php
	}
	// get each attribute
	$query = mysqli_query($db, "SHOW COLUMNS FROM ".$widget['table']);
	while($labels = mysqli_fetch_array($query)) {
	  $label = $labels['Field'];
	  // do not add the ID filed
					if (strcmp($label, "envid") === 0) {?>
      <li><label for="envid">Environment</label><select name="envid"
        id="envid" required>
          <?php
          // grab all environments
          $env_query = mysqli_query($db, "SELECT * FROM environments");
          while($cur_env = mysqli_fetch_array($env_query)) {
            $selected = "";
            // check if this envid is the same
            if($cur['envid'] === $cur_env['envid']){
              $selected = "selected=\"selected\"";
					}?>
          <option value="<?php echo $cur_env['envid']?>"
          <?php echo $selected?>>
            <?php echo $cur_env['envid'].": ".$cur_env['envaddr']." -- ".$cur_env['type']." :: ".$cur_env['notes']?>
          </option>
          <?php
          }
          ?>
      </select>
      </li>
      <?php
					} else if (strcmp($label, "id") != 0) {?>
      <li><label for="<?php echo $label?>"> <?php echo $label?>
      </label> <input type="text" name="<?php echo $label?>"
        value="<?php echo $cur[$label]?>" id="<?php echo $label?>"
        required />
      </li>
      <?php
					}
				}?>
    </ol>
    <input type="submit" value="Submit" />
  </fieldset>
</form>
<?php
}

/**
 * A function to echo the HTML for the popup dialog used to create a new content page.
 *
 * @param int $id the page ID number to create the popup for, or null if it is a new entry
 */
function create_page_editor($id) {
  global $db;

  // see if a page exists with the given id
  $query = mysqli_query($db, sprintf("SELECT * FROM content_pages WHERE pageid=%d".$pageid, $db->real_escape_string($id)));
  if($query) {
    $cur = mysqli_fetch_array($query);
  } else {
    $cur = null;
	}?>

<p>Complete the following form to create a new content page.</p>
<form action="form/admin/create_page.php" method="POST">
  <fieldset>
    <ol>
      <?php if($cur) {// only show the ID for edits?>
      <li><label for="pageid">Page ID</label> <input type="text"
        name="pageid" id="pageid" value="<?php echo $cur['pageid']?>"
        readonly="readonly" />
      </li>
      <?php
		}?>
      <li><label for="title">Title</label> <input type="text"
        name="title" value="<?php echo $cur['title']?>" " id="title"
        placeholder="e.g., My New Page" required />
      </li>
      <li><label for="menu_name">Menu Name</label> <input type="text"
        name="menu_name" value="<?php echo $cur['menu_name']?>"
        id="menu_name" placeholder="e.g., The Page" required />
      </li>
      <li><label for="menu_index">Menu Index</label> <select
        name="menu_index" id="menu_index" required>
          <?php
          for($i = 0; $i < 20; $i++) {
            $selected = "";
            // check if this index is the same
            if($cur['menu_index'] === $i){
              $selected = "selected=\"selected\"";
					}?>
          <option value="<?php echo $i?>"<?php echo $selected?>\>
            <?php echo $i?>
          </option>
          <?php
				}?>
      </select>
      </li>
      <li><?php
      // check for JS files
      $dir  = opendir($_SERVER['DOCUMENT_ROOT'].'/js/content');
      $files = array();
      while ($f = readdir($dir))
        if(substr($f, strlen($f)-3) === ".js")
        $files[] = $f;
      if(count($files) > 0) {?> <label for="js">Javascript File
          (optional)</label> <select name="js" id="js">
          <option value="none">none</option>
          <?php
          // put in each option
          foreach($files as $f) {
            $selected = "";
            // check if this file is the same
            if(strcmp($cur['js'], $f) === 0) {
              $selected = "selected=\"selected\"";
						}?>
          <option value="<?php echo $f?>" <?php echo $selected?>>
            <?php echo $f?>
          </option>
          <?php
					}?>
      </select> <?php
      } else {// put dummy dropdown in?> <label for="holder">Javascript
          File (optional)</label> <select name="holder" id="holder"
        disabled="true"><optionvalue"void">No .js files found in
          js/content/

          </option>

      </select> <?php
      }?></li>
    </ol>
    <input type="submit" value="Submit" />
  </fieldset>
</form>
<?php
}

/**
 * A function to echo the HTML for the popup dialog used to create a content article.
 *
 * @param int $id the article ID number to create the popup for, or null if it is a new entry
 */
function create_article_editor($id) {
  global $db;

  // see if a article exists with the given id
  $query = mysqli_query($db, sprintf("SELECT * FROM articles WHERE artid=%d", $db->real_escape_string($id)));
  if($query) {
    $cur = mysqli_fetch_array($query);
  } else {
    $cur = null;
	}?>

<p>Complete the following form to create a new content article.</p>
<form action="form/admin/create_article.php" method="POST">
  <fieldset>
    <ol>
      <?php if($cur) {// only show the ID for edits?>
      <li><label for="artid">Page ID</label> <input type="text"
        name="artid" id="artid" value="<?php echo $cur['artid']?>"
        readonly="readonly" />
      </li>
      <?php
		}?>
      <li><label for="title">Title</label> <input type="text"
        name="title" value="<?php echo $cur['title']?>" id="title"
        placeholder="e.g., My Content" required />
      </li>
      <li><label for="content">Content</label> <?php echo "<textarea name=\"content\"
      id=\"content\" rows=\"10\"
					placeholder=\"<p>Put your HTML code here.</p>\">".htmlentities($cur['content'])."</textarea>"?>
        <div class="content-preview">
          <a
            href="javascript:preview($('input#title').val(), $('textarea#content').val());">Preview</a>
        </div>
      </li>
      <li><label for="pageid">Page</label> <select name="pageid"
        id="pageid" required>
          <?php
          // grab all of the page names
          $query = mysqli_query($db, "SELECT pageid, title FROM content_pages ORDER BY pageid");
          while($curpage = mysqli_fetch_array($query)) {
            $selected = "";
            // check if this page is the same
            if($curpage['pageid'] === $cur['pageid']) {
              $selected = "selected=\"selected\"";
					}?>
          <option value="<?php echo $curpage['pageid']?>"
          <?php echo $selected?>>
            <?php echo $curpage['title']?>
          </option>
          <?php
				}?>
      </select>
      </li>
      <li><label for="pageindex">Page-Index</label> <select
        name="pageindex" id="pageindex" required>
          <?php
          for($i = 0; $i < 20; $i++) {
            $selected = "";
            // check if this index is the same
            if($cur['pageindex'] === $i) {
              $selected = "selected=\"selected\"";
					}?>
          <option value="<?php echo $i?>" <?php echo $selected?>>
            <?php echo $i?>
          </option>
          <?php
				}?>
      </select>
      </li>
    </ol>
    <input type="submit" value="Submit" />
  </fieldset>
</form>
<?php
}

/**
 * A function to echo the HTML for the popup dialog used to create a slide for the homepage slideshow.
 *
 * @param int $id the slide ID number to create the popup for, or null if it is a new entry
 */
function create_slide_editor($id) {
  global $db;

  // see if a article exists with the given id
  $query = mysqli_query($db, sprintf("SELECT * FROM slideshow WHERE slideid=%d", $db->real_escape_string($id)));
  if($query) {
    $cur = mysqli_fetch_array($query);
  } else {
    $cur = null;
	}?>

<p>Complete the following form to create a new slide.</p>
<form action="form/admin/create_slide.php" method="POST"
  enctype="multipart/form-data">
  <fieldset>
    <ol>

      <?php if($cur) {// only show the ID for edits?>
      <li><label for="slideid">Slide ID</label> <input type="text"
        name="slideid" id="slideid" value="<?php echo $cur['slideid']?>"
        readonly="readonly" />
      </li>
      <?php
		}?>
      <li><?php if($cur) {// check if we can display a preview?> <label
        for="imgdisplay">Current Image</label> <input type="text"
        name="imgdisplay" value="<?php echo $cur['img']?>"
        id="imgdisplay" readonly="readonly" />
        <center>
          <img src="img/slides/<?php echo $cur['img']?>" width="400"
            height="175" />
        </center>
        <div class="line"></div> <label for="img">New Image (optional)</label>
        <input type="file" name="img" id="img" /> <?php
		} else {?> <label for="img">Upload Image</label> <input type="file"
        name="img" id="img" required /> <?php
		}?></li>
      <li><label for="caption">Caption</label> <input type="text"
        name="caption" value="<?php echo $cur['caption']?>" id="caption"
        placeholder="e.g., A Robot being Awesome" required />
      </li>
      <li><label for="index">Slide-Index</label> <select name="index"
        id="index" required>
          <?php
          for($i = 0; $i < 10; $i++) {
            $selected = "";
            // check if this index is the same
            if($cur['index'] === $i) {
              $selected = "selected=\"selected\"";
					}?>
          <option value="<?php echo $i?>" <?php echo $selected?>>
            <?php echo $i?>
          </option>
          <?php
				}?>
      </select>
      </li>
    </ol>
    <input type="submit" value="Submit" />
  </fieldset>
</form>
<?php
}

/**
 * A function to echo the HTML for the popup dialog used to edit the site settings.
 */
function create_site_editor() {
	global $dbhost, $dbuser, $dbpass, $dbname, $title, $google_tracking_id, $copyright;?>

<p>Complete the following form to edit the site settings.</p>
<form action="../form/admin/setup.php" method="POST">
  <fieldset>
    <input type="hidden" name="req" id="req" value="reconfig">
    <h3>MySQL Database Information</h3>
    <ol>
      <li><label for="host">Database Host</label> <input type="text"
        name="host" id="host" value="<?php echo $dbhost?>"
        placeholder="e.g., localhost" required />
      </li>
      <li><label for="db">Database Name</label> <input type="text"
        name="db" id="db" value="<?php echo $dbname?>"
        placeholder="e.g., my_remote_lab" required />
      </li>
      <li><label for="dbuser">Database Username</label> <input
        type="text" name="dbuser" value="<?php echo $dbuser?>"
        id="dbuser" placeholder="Username" required />
      </li>
      <li><label for="dbpass">Database Password</label> <input
        type="password" name="dbpass" value="**********" id="dbpass"
        placeholder="Password" required /> <label for="dbpass-confirm">Confrim
          Password</label> <input type="password" name="dbpass-confirm"
        id="dbpass-confirm" value="**********"
        placeholder="Confirm password" required />
      </li>
    </ol>
  </fieldset>
  <fieldset>
    <br />
    <h3>Site Information</h3>
    <ol>
      <li><label for="site-name">Site Name</label> <input type="text"
        name="site-name" id="site-name" value="<?php echo $title?>"
        placeholder="e.g., My Awesome Title" required />
      </li>
      <li><label for="google">Google Analytics ID</label> <input
        type="text" name="google" id="google"
        value="<?php echo $google_tracking_id?>"
        placeholder="(optional)" />
      </li>
      <li><label for="copyright">Copyright Message</label> <input
        type="text" name="copyright" id="copyright"
        value="<?php echo substr($copyright, 6)?>"
        placeholder="e.g., 2012 My Robot College" required /></li>
    </ol>
  </fieldset>
  <input type="submit" value="Submit" />
</form>
<?php
}

/**
 * A function to echo the HTML for the popup dialog used to update the database.
 */
function create_db_update() {
	global $db;?>

<p>By using this form, you will update your RMS database to the current
  schema version.</p>
<form action="../form/admin/update.php" method="POST">
  <fieldset>
    <?php
    $query = mysqli_query($db, "SELECT * FROM version");
	$v = mysqli_fetch_array($query);?>
    <input type="hidden" name="version" id="version"
      value="<?php echo $v['version']?>">
  </fieldset>
  <input type="submit" value="Update" />
</form>
<?php
}

/**
 * A function to echo the HTML for the popup dialog used to update Javascript files.
 */
function create_js_update() {?>
<p>By using this form, you will delete all local ROS Javascript files
  and download the latest versions.</p>
<form action="../form/admin/setup.php" method="POST">
  <fieldset>
    <input type="hidden" name="req" id="req" value="update">
  </fieldset>
  <input type="submit" value="Update" />
</form>
<?php
}
?>
