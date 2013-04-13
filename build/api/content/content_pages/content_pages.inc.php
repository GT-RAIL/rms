<?php
/**
 * Content page include static functions for the RMS API.
 *
 * Allows read and write access to content pages via PHP static function calls.
 * Used throughout RMS and within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 12 2013
 * @package    api.content.content_pages
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');

/**
 * A static class to contain the content_pages.inc.php static functions.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 12 2013
 * @package    api.content.content_pages
 */
class content_pages
{
    /**
     * Check if the given array has all of the necessary fields to create a
     * content page.
     *
     * @param array $array The array to check
     * @return boolean If the given array has all of the necessary fields to
     *     create a new content page
     */
    static function valid_content_page_fields($array)
    {
        return isset($array['title']) && isset($array['menu']) 
            && isset($array['index']) && ((isset($array['js']) 
            && (count($array) === 4)) || (count($array) === 3));
    }

    /**
     * Get all content page entries in the database or null if none exist.
     *
     * @return array|null All content pages in the database or null if none
     *     exist
     */
    static function get_content_pages()
    {
        global $db;

        // grab the pages and push them into an array
        $result = array();
        $query = mysqli_query(
            $db, "SELECT * FROM `content_pages` ORDER BY `index`"
        );
        while ($cur = mysqli_fetch_assoc($query)) {
            $result[] = $cur;
        }

        return (count($result) === 0) ? null : $result;
    }

    /**
     * Get the content page array for the page with the given ID, or null if
     * none exist.
     *
     * @param integer $id The page ID number
     * @return array|null An array of the page's SQL entry or null if none exist
     */
    static function get_content_page_by_id($id)
    {
        global $db;

        // grab the page
        $sql = sprintf(
            "SELECT * FROM `content_pages` WHERE `pageid`='%d'", 
            api::cleanse($id)
        );
        return mysqli_fetch_assoc(mysqli_query($db, $sql));
    }

    /**
     * Get the content page array for the page with the given title, or null if
     * none exist.
     *
     * @param string $title The page title
     * @return array|null An array of the page's SQL entry or null if none exist
     */
    static function get_content_page_by_title($title)
    {
        global $db;

        // grab the page
        $sql = sprintf(
            "SELECT * FROM `content_pages` WHERE `title`='%s'",
            api::cleanse($title)
        );
        return mysqli_fetch_assoc(mysqli_query($db, $sql));
    }

    /**
     * Create a content page with the given information. Any errors are
     * returned.
     *
     * @param string $title The title of the page
     * @param string $menu The name of the menu link for the page
     * @param integer $index The index in the menu for this page
     * @param string|null $js The Javascript file in the js/content folder
     *     associated with this page or null if there is none
     * @return string|null An error message or null if the create was sucessful
     */
    static function create_content_page($title, $menu, $index, $js)
    {
        global $db;

        // make sure it does not already exist
        if (content_pages::get_content_page_by_title($title)) {
            return 'ERROR: Content page with title '.$title.' already exists';
        }

        // insert into the database
        if (!$js || $js === 'NULL') {
            $sql = sprintf(
                "INSERT INTO `content_pages` (`title`, `menu`, `index`) 
                 VALUES ('%s', '%s', '%d')", 
                api::cleanse($title), api::cleanse($menu), api::cleanse($index)
            );
        } else {
            $sql = sprintf(
                "INSERT INTO `content_pages` (`title`, `menu`, `index`, `js`) 
                 VALUES ('%s', '%s', '%d', '%s')",
                api::cleanse($title), api::cleanse($menu), api::cleanse($index),
                api::cleanse($js)
            );
        }
        mysqli_query($db, $sql);

        // no error
        return null;
    }

    /**
     * Update a content page with the given information inside of the array. The
     * array should be indexed by the SQL column names. The ID field must be
     * contained inside of the array with the index 'id'. Any errors are
     * returned.
     *
     * @param array $fields the fields to update including the content page ID
     *     number
     * @return string|null an error message or null if the update was sucessful
     */
    static function update_content_page($fields)
    {
        global $db;

        if (!isset($fields['id'])) {
            return 'ERROR: ID field missing in update.';
        }

        // build the SQL string
        $sql = "";
        $numFields = 0;
        // check for the user
        if (!($page = content_pages::get_content_page_by_id($fields['id']))) {
            return 'ERROR: Content page ID '.$fields['id'].' does not exist.';
        }

        // check if we are changing the id
        $idToSet = $page['pageid'];
        if (isset($fields['pageid'])) {
            $numFields++;
            if ($fields['pageid'] !== $page['pageid'] 
                    && content_pages::get_content_page_by_id(
                        $fields['pageid']
                    )
            ) {
                return 'ERROR: Content page ID '.$fields['pageid'].
                    ' already exists';
            } else {
                $idToSet = $fields['pageid'];
            }
        }
        $sql .= sprintf(" `pageid`='%d'", api::cleanse($idToSet));

        // check for each update
        if (isset($fields['title'])) {
            $numFields++;
            if ($fields['title'] !== $page['title'] && 
                    content_pages::get_content_page_by_title(
                        $fields['title']
                    )
            ) {
                return 'ERROR: Content page title "'.$fields['title'].
                    '" already exists.';
            }
            $sql .= sprintf(", `title`='%s'", api::cleanse($fields['title']));
        }
        if (isset($fields['menu'])) {
            $numFields++;
            $sql .= sprintf(", `menu`='%s'", api::cleanse($fields['menu']));
        }
        if (isset($fields['index'])) {
            $numFields++;
            $sql .= sprintf(", `index`='%d'", api::cleanse($fields['index']));
        }
        if (isset($fields['js'])) {
            $numFields++;
            // check if we are removing the JS file
            if ($fields['js'] === 'NULL') {
                $sql .= sprintf(", `js`=NULL");
            } else {
                $sql .= sprintf(", `js`='%s'", api::cleanse($fields['js']));
            }
        }

        // check to see if there were too many fields
        if ($numFields !== (count($fields) - 1)) {
            return 'ERROR: Too many fields given.';
        } else if ($numFields === 0) {
            // nothing to update
            return null;
        }

        // we can now run the update
        $sql = sprintf(
            "UPDATE `content_pages` SET ".$sql." WHERE `pageid`='%d'", 
            api::cleanse($fields['id'])
        );
        mysqli_query($db, $sql);

        // no error
        return null;
    }

    /**
     * Delete the content page array for the content page with the given ID. Any
     * errors are returned.
     *
     * @param integer $id The content page ID number
     * @return string|null an error message or null if the delete was sucessful
     */
    static function delete_content_page_by_id($id)
    {
        global $db;

        // see if the content page exists
        if (content_pages::get_content_page_by_id($id)) {
            // delete it
            $sql = sprintf(
                "DELETE FROM `content_pages` WHERE `pageid`='%d'",
                api::cleanse($id)
            );
            mysqli_query($db, $sql);
            // no error
            return null;
        } else {
            return 'ERROR: Content page ID '.$id.' does not exist';
        }
    }

    /**
     * Get the HTML for an editor used to create or edit the given content page
     * entry. If this is not an edit, null can be given as the ID. An invalid ID
     * is the same as giving a null ID.
     *
     * @param integer|null $id the ID of the content page to edit, or null if a
     *     new entry is being made
     * @return string A string containing the HTML of the editor
     */
    static function get_content_page_editor($id = null)
    {
        // see if a content page exists with the given id
        $cur = content_pages::get_content_page_by_id($id);

        if ($cur) {
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

        $result = '
            <p>Complete the following form to create or edit a content page.</p>
            <form action="javascript:submit();"><fieldset>
            <ol>';

        // only show the ID for edits
        $result .=  ($cur) ? '
                    <li><label for="id">Page ID</label>
                        <input type="text" name="id" id="id" value="'.
                        $cur['pageid'].'" readonly="readonly" /></li>' 
                    : '';

        $result .= '
            <li>
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
        for ($i = 0; $i < 10; $i++) {
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
            if ($parts['extension'] === 'js') {
                $files[] = $f;
            }
        }
        if (count($files) > 0) {
            $result .= '<li>
                    <label for="js">Javascript File (optional)</label>
                    <select name="js" id="js" required>
                    <option value="NULL">None</option>';
            // put in each option
            foreach ($files as $f) {
                if ($js === $f) {
                    $result .= '<option value="'.$f.'" selected="selected">'.
                        $f.'</option>';
                } else {
                    $result .= '<option value="'.$f.'">'.$f.'</option>';
                }
            }
            $result .= '    </select>
                    </li>';
        } else {
            // put dummy dropdown in
            $result .= '
        <li>
            <label for="js-dummy">Javascript File (optional)</label>
            <select name="js-dummy" id="js-dummy" disabled="disabled">
            <option value="void">No .js files found in "js/content/"</option>
            </select>
        </li>';
        }

        $result .= '</ol>
                <input type="submit" value="Submit" />
                </fieldset>
                </form>';

        return $result;
    }
}
