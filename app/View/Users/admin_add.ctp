<?php
/**
 * Admin Add View
 *
 * The add page will allow an admin to create a new user manually.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Users
 */
?>

<header class="special container">
	<span class="icon fa-pencil"></span>
	<h2>Add User</h2>
</header>

<?php echo $this->element('user_form'); ?>
