<?php
/**
 * Content page include functions for the RMS API.
 *
 * Allows read and write access to content pages via PHP function calls. Used throughout RMS and
 * within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 5 2012
 * @package    api.content.content_pages
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');

/**
 * Get all content page entries in the database or null if none exist.
 *
 * @return array|null All content pages in the database or null if none exist
 */
function get_content_pages() {
  global $db;

  // grab the pages and push them into an array
  $result = array();
  $query = mysqli_query($db, "SELECT * FROM `content_pages` ORDER BY `index`");
  while($cur = mysqli_fetch_assoc($query)) {
    $result[] = $cur;
  }

  return (count($result) === 0) ? null : $result;
}

/**
 * Get the content page array for the page with the given ID, or null if none exist.
 *
 * @param integer $id The page ID number
 * @return array|null An array of the page's SQL entry or null if none exist
 */
function get_content_page_by_id($id) {
  global $db;

  // grab the page
  $sql = sprintf("SELECT * FROM `content_pages` WHERE `pageid`='%d'", $db->real_escape_string($id));
  return mysqli_fetch_assoc(mysqli_query($db, $sql));
}

/**
 * Get the HTML for an editor used to create or edit the given content page entry. If this is not an
 * edit, null can be given as the ID. An invalid ID is the same as giving a null ID.
 *
 * @param integer|null $id the ID of the content page to edit, or null if a new entry is being made
 * @return string A string containing the HTML of the editor
 */
function get_content_page_editor_html($id) {
  global $PASSWORD_HOLDER;

  // see if a content page exists with the given id
  $cur = get_content_page_by_id($id);

  if($cur) {
    $title = $cur['title'];
    $menu = $cur['menu'];
    $index = $cur['index'];
    $js = $cur['js'];
  } else {
    $title = '';
    $menu = '';
    $index = '';
    $js = '';
  }

  $result = '<p>Complete the following form to create or edit a content page.</p>
             <form action="javascript:submit();"><fieldset>
               <ol>';

  // only show the ID for edits
  $result .=  ($cur) ? '<li><label for="id">Page ID</label><input type="text" name="id"
                             id="id" value="'.$cur['pageid'].'" readonly="readonly" /></li>' : '';

  $result .= '<li>
                <label for="title">Title</label>
                <input type="text" name="title" id="title" value="'.$title.'"
                 placeholder="e.g., My New Page" required />
            </li>
            <li>
              <label for="menu">Menu Name</label>
              <input type="text" name="menu" id="menu" value="'.$menu.'"
               placeholder="e.g., The Page" required />
            </li>
            <li>
               <label for="index">Index</label>
               <select name="index" id="index" required>';
  // create enough to index 10 pages
  for($i = 0; $i < 10; $i++) {
    $selected = ($index === strval($i)) ? 'selected="selected" ' : '';
    $result .=  '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
  }

  $result .= '  </select>
              </li>';

  // check for content Javascript files
  $dir  = opendir(dirname(__FILE__).'/../../../js/content/');
  $files = array();
  while ($f = readdir($dir)) {
    // check if it is a Javascript file
    $parts = pathinfo($f);
    if($parts['extension'] === 'js') {
      $files[] = $f;
    }
  }
  if(count($files) > 0) {
    $result .= '<li>
                  <label for="js">Javascript File (optional)</label>
                  <select name="js" id="js" required>';
    // put in each option
    foreach($files as $f) {
      if($js === $f) {
        $result .= '<option value="'.$f.'" selected="selected">'.$f.'</option>';
      } else {
        $result .= '<option value="'.$f.'">'.$f.'</option>';
      }
    }
    $result .= '<option value="NULL">None</option>';
    $result .= '    </select>
                  </li>';
  } else {
    // put dummy dropdown in
    $result .= '<li>
                  <label for="js-dummy">Javascript File (optional)</label>
                  <select name="js-dummy" id="js-dummy" disabled="disabled">
                    <option value="void">No .js files found in "js/content/"</option>
                  </select>
                </li>';
  }

  $result .= '    </ol>
                <input type="submit" value="Submit" />
                </fieldset>
              </form>';

  return $result;
}
?>
