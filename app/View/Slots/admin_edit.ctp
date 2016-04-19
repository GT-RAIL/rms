<?php
/**
 * Admin Edit Study Session Slot View
 *
 * The edit study session slot view allows an admin to edit an existing study session slot in the database.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Slots
 */
?>

<header class="special container">
	<span class="icon fa-pencil"></span>
	<h2>Edit Study Session Slot</h2>
</header>

<?php echo $this->element('slot_form', array('edit' => true)); ?>
