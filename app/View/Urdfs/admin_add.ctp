<?php
/**
 * Admin Add URDF View
 *
 * The add URDF view allows an admin to add a new URDF setting to the database.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Urdfs
 */
?>

<header class="special container">
	<span class="icon fa-pencil"></span>
	<h2>Add URDF Setting</h2>
</header>

<?php echo $this->element('urdf_form'); ?>
