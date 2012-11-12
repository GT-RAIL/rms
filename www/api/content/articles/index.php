<?php
/**
 * Article script for the RMS API.
 *
 * Allows read and write access to content articles via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 8 2012
 * @package    api.content.articles
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).'/articles.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// default to the error state
$result = create_404_state(array());

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    // check if this is a default request
    if(count($_GET) === 0) {
      // check for articles
      $articles = get_articles();
      if($articles) {
        $result = create_200_state($result, $articles);
      } else {
        $result['msg'] = 'No content articles found.';
      }
    } else if(count($_GET) === 1 && isset($_GET['id'])) {
      $article = get_article_by_id($_GET['id']);
      // now check if the article was found
      if($article) {
        $result = create_200_state($result, $article);
      } else {
        $result['msg'] = 'Article ID "'.$_GET['id'].'" is invalid.';
      }
    } else if(count($_GET) === 1 && isset($_GET['pageid'])) {
      $articles = get_articles_by_pageid($_GET['pageid']);
      // now check if the articles were found
      if($articles) {
        $result = create_200_state($result, $articles);
      } else {
        $result['msg'] = 'No articles with content page ID "'.$_GET['pageid'].'" found.';
      }
    }
    break;
  default:
    $result['msg'] = $_SERVER['REQUEST_METHOD'].' method is unavailable.';
    break;
}

// return the JSON encoding of the result
echo json_encode($result);
?>
