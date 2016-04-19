<?php
/**
 * Admin Edit TF View
 *
 * The edit Tf view allows an admin to edit an existing TF setting in the database.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Tfs
 */
?>

<header class="special container">
	<span class="icon fa-pencil"></span>
	<h2>Edit TF Client Settings</h2>
</header>

<?php echo $this->element('tf_form', array('edit' => true)); ?>
