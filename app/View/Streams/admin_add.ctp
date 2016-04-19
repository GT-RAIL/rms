<?php
/**
 * Admin Add MJPEG Server Stream View
 *
 * The add MJPEG server stream view allows an admin to add a new MJPEG server stream to the database.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Streams
 */
?>

<header class="special container">
	<span class="icon fa-pencil"></span>
	<h2>Add MJPEG Server Stream</h2>
</header>

<?php echo $this->element('stream_form'); ?>
