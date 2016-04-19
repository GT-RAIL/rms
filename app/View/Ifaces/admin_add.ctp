<?php
/**
 * Admin Add Interface View
 *
 * The add interface view allows an admin to add a new interface to the database. Ifaces is used to prevent using the
 * reserved PHP keyword interface.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Ifaces
 */
?>

<header class="special container">
	<span class="icon fa-pencil"></span>
	<h2>Add Interface</h2>
</header>

<?php echo $this->element('iface_form'); ?>
