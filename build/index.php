<?php
/**
 * Main index page for RMS.
 *
 * The index page will load either the default hompage, or the content page
 * specified in the URL.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 13 2013
 * @link       http://ros.org/wiki/rms
 */

// first check if there is a config file
if (!file_exists(dirname(__FILE__).'/inc/config.inc.php')) {
    header('Location: setup/');
    return;
}

// start the session
session_start();

// load the include files
include_once(dirname(__FILE__).'/api/content/articles/articles.inc.php');
include_once(dirname(__FILE__).
        '/api/content/content_pages/content_pages.inc.php');
include_once(dirname(__FILE__).'/api/content/slides/slides.inc.php');
include_once(dirname(__FILE__).
        '/api/users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/inc/config.inc.php');
include_once(dirname(__FILE__).'/inc/content.inc.php');
include_once(dirname(__FILE__).'/inc/head.inc.php');

// grab the user with this session
$sessionUser = isset($_SESSION['userid']) ?
user_accounts::get_user_account_by_id($_SESSION['userid']) : null;

// grab the page information
if (!isset($_GET['pageid'])) {
    // simply take the first page
    $contentPages = content_pages::get_content_pages();
    $page = $contentPages ? $contentPages[0] : null;
} else {
    // check if that page exists
    $page = content_pages::get_content_page_by_id($_GET['pageid']);
}

// check if we found a valid content page
if (!$page) {
    header('Location: index.php');
    return;
}

// check if this is the homepage
if (!isset($contentPages)) {
    $contentPages = content_pages::get_content_pages();
}
$homepage = $contentPages[0];
$ishome = $page['pageid'] === $homepage['pageid'];
?>

<!DOCTYPE html>
<html>
<head>
<?php head::import_head('')?>
<title><?php echo $title." :: ".$page['title']?></title>

<?php if ($sessionUser) {?>
<script type="text/javascript">
  createMenuButtons();
</script>
<?php 
}

// check for extra JS files
if ($page['js']) {?>
<script type="text/javascript" src="js/content/<?php echo $page['js']?>">
</script>
<?php 
}

// check for the slideshow
if ($ishome) {?>
<script type="text/javascript"
    src="http://cdn.robotwebtools.org/Slides/1.1.8/slides.min.js"></script>
<script type="text/javascript">
  createSlideshow();
</script>
<?php 
}
?>
</head>

<body>
    <?php content::create_header($sessionUser, $page['title'], '')?>
    <section class="page">
        <?php
        // check if this is the homepage
        if ($ishome) {
            echo '<center>'.slides::create_slideshow_html().'</center><br />';
        }
        // put in the content
        echo articles::create_page_articles_html($page);
        content::create_footer();
        ?>
    </section>
</body>
</html>
