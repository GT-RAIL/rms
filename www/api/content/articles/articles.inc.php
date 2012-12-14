<?php
/**
 * Article include functions for the RMS API.
 *
 * Allows read and write access to content articles via PHP function calls. Used throughout RMS and
 * within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 20 2012
 * @package    api.content.articles
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../content_pages/content_pages.inc.php');
include_once(dirname(__FILE__).'/../../../inc/config.inc.php');

/**
 * Get all content article entries in the database or null if none exist.
 *
 * @return array|null All content articles in the database or null if none exist
 */
function get_articles() {
  global $db;

  // grab the articles and push them into an array
  $result = array();
  $query = mysqli_query($db, "SELECT * FROM `articles`");
  while($cur = mysqli_fetch_assoc($query)) {
    $result[] = $cur;
  }

  return (count($result) === 0) ? null : $result;
}

/**
 * Get the content article array for the article with the given ID, or null if none exist.
 *
 * @param integer $id The article ID number
 * @return array|null An array of the article's SQL entry or null if none exist
 */
function get_article_by_id($id) {
  global $db;

  // grab the article
  $sql = sprintf("SELECT * FROM `articles` WHERE `artid`='%d'", $db->real_escape_string($id));
  return mysqli_fetch_assoc(mysqli_query($db, $sql));
}

/**
 * Get all content articles for the page with the given ID, or null if none exist.
 *
 * @param integer $pageid The page ID number
 * @return array|null An array of the page's articles or null if none exist
 */
function get_articles_by_pageid($pageid) {
  global $db;

  // grab the articles and push them into an array
  $result = array();
  $sql = sprintf("SELECT * FROM `articles` WHERE `pageid`='%d' ORDER BY `index`"
                 , $db->real_escape_string($pageid));
  $query = mysqli_query($db, $sql);
  while($cur = mysqli_fetch_assoc($query)) {
    $result[] = $cur;
  }

  return (count($result) === 0) ? null : $result;
}

/**
 * Get the HTML for creating the given article.
 *
 * @param array $article The article SQL entry to generate HTML for
 * @return string The HTML for the article
 */
function create_article_html($article) {
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
function create_page_articles_html($page) {
  $html = '<section id="articles">';
  // load this page's articles
  $articles = get_articles_by_pageid($page['pageid']);
  foreach ($articles as $cur) {
    $html .= create_article_html($cur);
  }
  $html .= '</section>';

  return $html;
}

/**
 * Get the HTML for an editor used to create or edit the given article entry. If this is not an
 * edit, null can be given as the ID. An invalid ID is the same as giving a null ID.
 *
 * @param integer|null $id the ID of the article to edit, or null if a new entry is being made
 * @return string A string containing the HTML of the editor
 */
function get_article_editor_html($id) {
  // see if an article exists with the given id
  $cur = get_article_by_id($id);

  if($cur) {
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

  $result = '<p>Complete the following form to create or edit an article.</p>
             <form action="javascript:submit();"><fieldset>
               <ol>';

  // only show the ID for edits
  $result .=  ($cur) ? '<li><label for="id">Article ID</label><input type="text" name="id"
                             id="id" value="'.$cur['artid'].'" readonly="readonly" /></li>' : '';

  $result .= '<li>
                <label for="title">Title</label>
                <input type="text" name="title" id="title" value="'.$title.'"
                 placeholder="e.g., My Content" required />
              </li>
              <li>
                <label for="content">Content</label>
                <textarea name="content" id="content" rows="10"
                 placeholder="<p>Put your HTML code here.</p>">'.htmlentities($content).'</textarea>
                <div class="content-preview">
                  <a href="javascript:preview();">Preview</a>
                </div>
              </li>
              <li>
                <label for="pageid">Page</label>
                <select name="pageid" id="pageid" required>';

  // grab all of the pages
  $pages = get_content_pages();
  foreach ($pages as $curpage) {
    // check if this type is the same
    if($pageid === $curpage['pageid']) {
      $result .= '<option value="'.$curpage['pageid'].'" selected="selected">'.$curpage['pageid'].': '.$curpage['title'].'</option>';
    } else {
      $result .= '<option value="'.$curpage['pageid'].'">'.$curpage['pageid'].': '.$curpage['title'].'</option>';
    }
  }
 $result .= '  </select>
             </li>
             <li>
               <label for="index">Index</label>
               <select name="index" id="index" required>';
  // create enough to index 15 articles
  for($i = 0; $i < 25; $i++) {
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
?>
