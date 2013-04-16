<?php
/**
 * Article script for the RMS API.
 *
 * Allows read and write access to content articles via the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 12 2013
 * @package    api.content.articles
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../../api.inc.php');
include_once(dirname(__FILE__).'/../../config/logs/logs.inc.php');
include_once(dirname(__FILE__).
        '/../../users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/articles.inc.php');

// JSON response
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// check for authorization
if ($auth = user_accounts::authenticate()) {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            // check if we are creating a new entry
            if (articles::valid_article_fields($_POST)) {
                if ($auth['type'] === 'admin') {
                    if ($error = articles::create_article(
                        $_POST['title'], $_POST['content'], $_POST['pageid'], 
                        $_POST['index']
                    )) {
                        $result = api::create_404_state($error);
                    } else {
                        logs::write_to_log(
                            'EDIT: '.$auth['username'].' created article '.
                            $_POST['title'].'.'
                        );
                        $result = api::create_200_state(
                            articles::get_article_by_title_and_pageid(
                                $_POST['title'], $_POST['pageid']
                            )
                        );
                    }
                } else {
                    logs::write_to_log(
                        'SECURITY: '.$auth['username'].
                        ' attempted to create an article.'
                    );
                    $result = api::create_401_state();
                }
            } else {
                $result = api::create_404_state('Unknown request.');
            }
            break;
        case 'GET':
            // check if this is a default request
            if (count($_GET) === 0) {
                // check for articles
                if ($articles = articles::get_articles()) {
                    $result = api::create_200_state($articles);
                } else {
                    $result = api::create_404_state(
                        'No content articles found.'
                    );
                }
            } else if (count($_GET) === 1 && isset($_GET['id'])) {
                // now check if the article was found
                if ($article = articles::get_article_by_id($_GET['id'])) {
                    $result = api::create_200_state($article);
                } else {
                    $result = api::create_404_state(
                        'Article ID "'.$_GET['id'].'" is invalid.'
                    );
                }
            } else if (count($_GET) === 1 && isset($_GET['pageid'])) {
                // now check if the articles were found
                if ($articles = articles::get_articles_by_pageid(
                    $_GET['pageid']
                )) {
                    $result = api::create_200_state($articles);
                } else {
                    $result = api::create_404_state(
                        'No articles with content page ID "'.
                        $_GET['pageid'].'" found.'
                    );
                }
            } else if (isset($_GET['request'])) {
                switch ($_GET['request']) {
                    case 'editor':
                        if ($auth['type'] === 'admin') {
                            if (count($_GET) === 1) {
                                $result = api::create_200_state(
                                    articles::get_article_editor()
                                );
                            } else if (count($_GET) === 2 
                                    && isset($_GET['id'])) {
                                $result = api::create_200_state(
                                    articles::get_article_editor($_GET['id'])
                                );
                            } else {
                                $result = api::create_404_state(
                                    'Too many fields provided.'
                                );
                            }
                        } else {
                            logs::write_to_log(
                                'SECURITY: '.$auth['username'].
                                ' attempted to get an article editor.'
                            );
                            $result = api::create_401_state();
                        }
                        break;
                    default:
                        $result = api::create_404_state(
                            $_GET['request'].' request type is invalid.'
                        );
                        break;
                }
            } else {
                $result = api::create_404_state('Unknown request.');
            }
            break;
        case 'DELETE':
            if (count($deleteArray) === 1 && isset($deleteArray['id'])) {
                if ($auth['type'] === 'admin') {
                    if ($error = articles::delete_article_by_id(
                        $deleteArray['id']
                    )) {
                        $result = api::create_404_state($error);
                    } else {
                        logs::write_to_log(
                            'EDIT: '.$auth['username'].' deleted article ID '.
                            $deleteArray['id'].'.'
                        );
                        $result = api::create_200_state(
                            api::get_current_timestamp()
                        );
                    }
                } else {
                    logs::write_to_log(
                        'SECURITY: '.$auth['username'].
                        ' attempted to delete article ID '.
                        $deleteArray['id'].'.'
                    );
                    $result = api::create_401_state();
                }
            } else {
                $result = api::create_404_state('Unknown request.');
            }
            break;
        case 'PUT':
            if (isset($putArray['id'])) {
                if ($auth['type'] === 'admin') {
                    if ($error = articles::update_article($putArray)) {
                        $result = api::create_404_state($error);
                    } else {
                        logs::write_to_log(
                            'EDIT: '.$auth['username'].' modified article ID '.
                            $putArray['id'].'.'
                        );
                        $result = api::create_200_state(
                            articles::get_article_by_id($putArray['id'])
                        );
                    }
                } else {
                    logs::write_to_log(
                        'SECURITY: '.$auth['username'].
                        ' attempted to edit article ID '.$putArray['id'].'.'
                    );
                    $result = api::create_401_state();
                }
            } else {
                $result = api::create_404_state('Unknown request.');
            }
            break;
        default:
            $result = api::create_404_state(
                $_SERVER['REQUEST_METHOD'].' method is unavailable.'
            );
            break;
    }
} else {
    $result = api::create_401_state();
}

// return the JSON encoding of the result
echo json_encode($result);
