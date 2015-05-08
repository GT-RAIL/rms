<?php
/**
 * Admin Edit URDF View
 *
 * The edit URDF view allows an admin to edit an existing URDF setting in the database.
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
	<h2>Edit URDF Setting</h2>
</header>

<?php echo $this->element('urdf_form', array('edit' => true)); ?>
