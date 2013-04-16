<?php
/**
 * Include functions for the HTML HEAD section of the RMS.
 *
 * Provides several common functions to generate the HTML for the HEAD section
 * of the RMS HTML.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 13 2013
 * @package    inc
 * @link       http://ros.org/wiki/rms
 */

if (file_exists(dirname(__FILE__).'/config.inc.php')) {
    include_once(dirname(__FILE__).'/config.inc.php');
}

/**
 * A static class to contain the head.inc.php functions.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 13 2013
 * @package    inc
 */
class head
{
    
    /**
     * A function to echo the HTML for the HEAD section of the HTML document.
     * This includes metadata, common CSS and Javascript files, and the Google
     * Analytics code if it exists.
     *
     * @param string $path the relative path to the base RMS directory
     */
    static function import_head($path)
    {
        head::import_meta();
        head::import_common_css($path);
        head::import_common_js($path);
        head::import_analytics();
    }
    
    /**
     * A function to echo the HTML to set the meta information.
     */
    static function import_meta()
    {
        echo '
        <meta    http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        ';
    }
    
    /**
     * A function to echo the HTML to import common CSS files.
     *
     * @param string $path the relative path to the base RMS directory
     */
    static function import_common_css($path)
    {
        echo '
        <link rel="stylesheet" type="text/css" href="'.$path.'css/common.css" />
        <link rel="stylesheet" type="text/css" href="'.$path.
            'css/jquery-ui-1.8.22.custom.css" />
        ';
    }
    
    /**
     * A function to echo the HTML to import common JS files.
     *
     * @param string $path the relative path to the base RMS directory
     */
    static function import_common_js($path)
    {
        echo '
        <script type="text/javascript" src="'.$path.'js/rms/common.js"></script>
        <script type="text/javascript" src="http://code.jquery.com/'.
            'jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="http://code.jquery.com/ui/'.
            '1.8.22/jquery-ui.min.js"></script>
        ';
    }
    
    /**
     * A function to echo the HTML to import Google Analytics settings.
     */
    static function import_analytics()
    {
        global $googleTrackingID;
        
        // check if tracking is being used
        if ($googleTrackingID) {
            echo '
            <script type="text/javascript">
            var _gaq = _gaq || [];
            _gaq.push(["_setAccount", "'.$googleTrackingID.'"]);
            _gaq.push(["_trackPageview"]);
            (function() {
            var ga = document.createElement("script"); '.
                'ga.type = "text/javascript"; ga.async = true;
            ga.src = ("https:" == document.location.protocol ? '.
                '"https://ssl" : "http://www") + ".google-analytics.com/ga.js";
            var s = document.getElementsByTagName("script")[0]; '.
                's.parentNode.insertBefore(ga, s);
            })();
            </script>
            ';
        }
    }
}
