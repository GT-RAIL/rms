<?php
/**
 * Admin Edit MJPEG Server Stream View
 *
 * The edit MJPEG server stream view allows an admin to edit an existing MJPEG server stream in the database.
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
	<h2>Edit MJPEG Server Stream</h2>
</header>

<?php echo $this->element('stream_form', array('edit' => true)); ?>
