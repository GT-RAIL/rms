<?php
/**
 * Article include static functions for the RMS API.
 *
 * Allows read and write access to content articles via PHP static function
 * calls. Used throughout RMS and within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 12 2013
 * @package    api.content.articles
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../content_pages/content_pages.inc.php');
include_once(dirname(__FILE__).'/../../../inc/config.inc.php');

/**
 * A static class to contain the articles.inc.php static functions.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 12 2013
 * @package    api.content.articles
*/
class articles
{
    /**
     * Check if the given array has all of the necessary fields to create an
     * article.
     *
     * @param array $array The array to check
     * @return boolean If the given array has all of the necessary fields to
     *     create a new article
     */
    static function valid_article_fields($array)
    {
        return isset($array['title']) && isset($array['content']) 
            && isset($array['pageid']) && isset($array['index']) 
            && (count($array) === 4);
    }


    /**
     * Get all content article entries in the database or null if none exist.
     *
     * @return array|null All content articles in the database or null if none
     *     exist
     */
    static function get_articles()
    {
        global $db;

        // grab the articles and push them into an array
        $result = array();
        $query = mysqli_query($db, "SELECT * FROM `articles`");
        while ($cur = mysqli_fetch_assoc($query)) {
            $result[] = $cur;
        }

        return (count($result) === 0) ? null : $result;
    }

    /**
     * Get the content article array for the article with the given ID, or null
     * if none exist.
     *
     * @param integer $id The article ID number
     * @return array|null An array of the article's SQL entry or null if none
     *     exist
     */
    static function get_article_by_id($id)
    {
        global $db;

        // grab the article
        $sql = sprintf(
            "SELECT * FROM `articles` WHERE `artid`='%d'", api::cleanse($id)
        );
        return mysqli_fetch_assoc(mysqli_query($db, $sql));
    }

    /**
     * Get the content article array for the article with the given title on the
     * given page, or null if none exist.
     *
     * @param string $title The article title
     * @param integer $pageid The page ID number
     * @return array|null An array of the article's SQL entry or null if none
     *     exist
     */
    static function get_article_by_title_and_pageid($title, $pageid)
    {
        global $db;

        // get all the articles on that page
        if ($articles = articles::get_articles_by_pageid($pageid)) {
            // now check all of them
            foreach ($articles as $a) {
                if ($a['title'] === $title) {
                    return $a;
                }
            }
        }

        // none found
        return null;
    }

    /**
     * Get all content articles for the page with the given ID, or null if none
     * exist.
     *
     * @param integer $pageid The page ID number
     * @return array|null An array of the page's articles or null if none exist
     */
    static function get_articles_by_pageid($pageid)
    {
        global $db;

        // grab the articles and push them into an array
        $result = array();
        $sql = sprintf(
            "SELECT * FROM `articles` WHERE `pageid`='%d' ORDER BY `index`",
            api::cleanse($pageid)
        );
        $query = mysqli_query($db, $sql);
        while ($cur = mysqli_fetch_assoc($query)) {
            $result[] = $cur;
        }

        return (count($result) === 0) ? null : $result;
    }

    /**
     * Create an article with the given information. Any errors are returned.
     *
     * @param string $title The title of the article
     * @param string $content The HTML of the article's content
     * @param integer $pageid The ID number of the page this article belongs to
     * @param integer $index The index on the page for this article
     * @return string|null An error message or null if the create was sucessful
     */
    static function create_article($title, $content, $pageid, $index)
    {
        global $db;

        // check if the page ID is valid
        if (!content_pages::get_content_page_by_id($pageid)) {
            return 'ERROR: Content page with ID '.$pageid.' does not exist.';
        } else if (articles::get_article_by_title_and_pageid($title, $pageid)) {
            // make sure it does not already exist
            return 'ERROR: Article page with title '.$title.
                ' already exists on page ID '.$pageid.'.';
        }

        // insert into the database
        $sql = sprintf(
            "INSERT INTO `articles` (`title`, `content`, `pageid`, `index`) 
             VALUES ('%s', '%s', '%d', '%d')", 
            api::cleanse($title), api::cleanse($content, false), 
            api::cleanse($pageid), api::cleanse($index)
        );
        mysqli_query($db, $sql);

        // no error
        return null;
    }

    /**
     * Update an article with the given information inside of the array. The
     * array should be indexed by the SQL column names. The ID field must be
     * contained inside of the array with the index 'id'. Any errors are
     * returned.
     *
     * @param array $fields the fields to update including the article ID number
     * @return string|null an error message or null if the update was sucessful
     */
    static function update_article($fields)
    {
        global $db;

        if (!isset($fields['id'])) {
            return 'ERROR: ID field missing in update.';
        }

        // build the SQL string
        $sql = "";
        $numFields = 0;
        // check for the user
        if (!($article = articles::get_article_by_id($fields['id']))) {
            return 'ERROR: Article page ID '.$fields['id'].' does not exist.';
        }

        // check if we are changing the id
        $idToSet = $article['artid'];
        if (isset($fields['artid'])) {
            $numFields++;
            if ($fields['artid'] !== $article['artid'] 
                    && articles::get_article_by_id($fields['artid'])) {
                return 'ERROR: Article ID '.$fields['artid'].' already exists';
            } else {
                $idToSet = $fields['artid'];
            }
        }
        $sql .= sprintf(" `artid`='%d'", api::cleanse($idToSet));

        // check for each update
        if (isset($fields['pageid'])) {
            $numFields++;
            if (!content_pages::get_content_page_by_id($fields['pageid'])) {
                return 'ERROR: Content page ID "'.$fields['pageid'].
                    '" does not exist.';
            }
            $sql .= sprintf(", `pageid`='%d'", api::cleanse($fields['pageid']));
        }
        if (isset($fields['title'])) {
            $numFields++;
            // check if we changed pages or if the article name exists
            if ((isset($fields['pageid']) 
                    && $fields['pageid'] !== $article['pageid'] 
                    && articles::get_article_by_title_and_pageid(
                        $fields['title'], $fields['pageid']
                    )
                ) || ($fields['title'] !== $article['title'] 
                        && articles::get_article_by_title_and_pageid(
                            $fields['title'], $article['pageid']
                        )
                )
            ) {
                return 'ERROR: Article page with title '.$fields['title'].
                    ' already exists.';
            }
            $sql .= sprintf(", `title`='%s'", api::cleanse($fields['title']));
        }
        if (isset($fields['content'])) {
            $numFields++;
            $sql .= sprintf(
                ", `content`='%s'", api::cleanse($fields['content'], false)
            );
        }
        if (isset($fields['index'])) {
            $numFields++;
            $sql .= sprintf(", `index`='%d'", api::cleanse($fields['index']));
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
            "UPDATE `articles` SET ".$sql." WHERE `artid`='%d'",
            api::cleanse($fields['id'])
        );
        mysqli_query($db, $sql);

        // no error
        return null;
    }

    /**
     * Delete the article array for the article with the given ID. Any errors
     * are returned.
     *
     * @param integer $id The article ID number
     * @return string|null an error message or null if the delete was sucessful
     */
    static function delete_article_by_id($id)
    {
        global $db;

        // see if the article exists
        if (articles::get_article_by_id($id)) {
            // delete it
            $sql = sprintf(
                "DELETE FROM `articles` WHERE `artid`='%d'", api::cleanse($id)
            );
            mysqli_query($db, $sql);
            // no error
            return null;
        } else {
            return 'ERROR: Article ID '.$id.' does not exist';
        }
    }

    /**
     * Get the HTML for creating the given article.
     *
     * @param array $article The article SQL entry to generate HTML for
     * @return string The HTML for the article
     */
    static function create_article_html($article)
    {
        $html = '<article>
                <h2>'.$article['title'].'</h2>
                        <div class="line"></div>
                        <div class="clear">'.$article['content'].'</div>
                                </article>';
        return $html;
    }

    /**
     * Get the HTML for creating the article section for the given page.
     *
     * @param array $page The content page SQL entry to generate HTML for
     * @return string The HTML for the article section for the given page
     */
    static function create_page_articles_html($page)
    {
        $html = '<section id="articles">';
        // load this page's articles
        $articles = articles::get_articles_by_pageid($page['pageid']);
        foreach ($articles as $cur) {
            $html .= articles::create_article_html($cur);
        }
        $html .= '</section>';

        return $html;
    }

    /**
     * Get the HTML for an editor used to create or edit the given article
     * entry. If this is not an edit, null can be given as the ID. An invalid ID
     * is the same as giving a null ID.
     *
     * @param integer|null $id the ID of the article to edit, or null if a new
     *     entry is being made
     * @return string A string containing the HTML of the editor
     */
    static function get_article_editor($id = null)
    {
        // see if an article exists with the given id
        $cur = articles::get_article_by_id($id);

        if ($cur) {
            $title = $cur['title'];
            $content = $cur['content'];
            $pageid = $cur['pageid'];
            $index = $cur['index'];
        } else {
            $title = '';
            $content = '';
            $pageid = '';
            $index = '';
        }

        $result = '
            <p>Complete the following form to create or edit an article.</p>
                <form action="javascript:submit();"><fieldset>
                <ol>';

        // only show the ID for edits
        $result .=  ($cur) ? '
                    <li><label for="id">Article ID</label>
                        <input type="text" name="id" id="id" value="'.
                         $cur['artid'].'" readonly="readonly" /></li>' 
                    : '';

        $result .= '<li>
<label for="title">Title</label>
<input type="text" name="title" id="title" value="'.$title.'"
    placeholder="e.g., My Content" required />
    </li>
    <li>
    <label for="content">Content</label>
    <textarea name="content" id="content" rows="10"
     placeholder="<p>Put your HTML code here.</p>">'.
     htmlentities($content).'</textarea>
    <div class="content-preview">
    <a href="javascript:preview();">Preview</a>
    </div>
</li>
<li>
    <label for="pageid">Page</label>
    <select name="pageid" id="pageid" required>';

        // grab all of the pages
        $pages = content_pages::get_content_pages();
        foreach ($pages as $curpage) {
            // check if this type is the same
            if ($pageid === $curpage['pageid']) {
                $result .= '<option value="'.$curpage['pageid'].
                    '" selected="selected">'.$curpage['pageid'].': '.
                    $curpage['title'].'</option>';
            } else {
                $result .= '<option value="'.$curpage['pageid'].'">'.
                    $curpage['pageid'].': '.$curpage['title'].'</option>';
            }
        }
        $result .= '  </select>
                </li>
                <li>
                <label for="index">Index</label>
                <select name="index" id="index" required>';
        // create enough to index 15 articles
        for ($i = 0; $i < 25; $i++) {
            $selected = ($index === strval($i)) ? 'selected="selected" ' : '';
            $result .=  '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
        }
        $result .= '</select></li>';



        $result .= '      </select>
                </li></ol>
                <input type="submit" value="Submit" />
                </fieldset>
                </form>';

        return $result;
    }
}
