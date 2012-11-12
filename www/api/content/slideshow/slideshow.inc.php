<?php
/**
 * Slideshow include functions for the RMS API.
 *
 * Allows read and write access to slideshow slides via PHP function calls. Used throughout RMS and
 * within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 8 2012
 * @package    api.content.slideshow
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');

/**
 * Get an array of all slide entires in the database or null if none exist.
 *
 * @return array|null The array of slide entries or null if none exist.
 */
function get_slides() {
  global $db;

  // grab the articles and push them into an array
  $result = array();
  $query = mysqli_query($db, "SELECT * FROM `slideshow` ORDER BY `index`");
  while($cur = mysqli_fetch_assoc($query)) {
    $result[] = $cur;
  }

  return (count($result) === 0) ? null : $result;
}

/**
 * Get the HTML for creating the given slide inside of the slideshow.
 *
 * @param array $slide The slide SQL entry to generate HTML for
 * @return string The HTML for the slide
 */
function create_slide_html($slide) {
  return '<div class="slide">
            <img src="img/slides/'.$slide['img'].'" width="800" height="350" />
            <div class="caption"><p>'.$slide['caption'].'</p></div>
          </div>';
}

/**
 * Get the HTML for creating the slideshow.
 *
 * @return string The HTML for the slideshow
 */
function create_slideshow_html() {
  $slides = get_slides();
  $html  = '<div id="slides">
              <div class="slides_container">';
  foreach ($slides as $cur) {
    $html .= create_slide_html($cur);
  }
  $html .= '  </div>
            </div>';

  return $html;
}
?>
