<?php
/**
 * Admin Add TF View
 *
 * The add TF view allows an admin to add a new TF setting to the database.
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
	<h2>Add TF Client Settings</h2>
</header>

<?php echo $this->element('tf_form'); ?>
