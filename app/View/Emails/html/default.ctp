<?php
/**
 * Default HTML Email Template
 *
 * The default HTML email template will separate each new line as a new <p> element.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.View.Emails.html
 */
?>

<?php
// create a new paragraph for each new line
$content = explode('\n', $content);
foreach ($content as $line) {
	echo '<p> ' . $line . '</p>\n';
}
?>
