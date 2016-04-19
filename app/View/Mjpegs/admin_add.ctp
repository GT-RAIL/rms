<?php
/**
 * Admin Add MJPEG Server View
 *
 * The add MJPEG server view allows an admin to add a new MJPEG server to the database.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Mjpegs
 */
?>

<header class="special container">
	<span class="icon fa-pencil"></span>
	<h2>Add MJPEG Server</h2>
</header>

<?php echo $this->element('mjpeg_form'); ?>
