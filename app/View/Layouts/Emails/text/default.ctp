<?php
/**
 * Default Text Email Layout
 *
 * The default text email layout will display the content and a shout-out to the RMS.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.View.Layouts.Emails.text
 */
?>

<?php echo $this->fetch('content'); ?>

This email was sent using the Robot Management System, http://wiki.ros.org/rms.
