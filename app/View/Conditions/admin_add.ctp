<?php
/**
 * Admin Add Study Condition View
 *
 * The add study condition view allows an admin to add a new study condition to the database.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Conditions
 */
?>

<header class="special container">
	<span class="icon fa-pencil"></span>
	<h2>Add Study Condition</h2>
</header>

<?php echo $this->element('condition_form'); ?>
