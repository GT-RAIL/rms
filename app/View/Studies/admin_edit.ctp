<?php
/**
 * Admin Edit Study View
 *
 * The edit study view allows an admin to edit an existing study in the database.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Studies
 */
?>

<header class="special container">
	<span class="icon fa-pencil"></span>
	<h2>Edit User Study</h2>
</header>

<?php echo $this->element('study_form', array('edit' => true)); ?>
