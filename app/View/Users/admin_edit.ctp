<?php
/**
 * Admin Edit View
 *
 * The edit page will allow an admin to edit an existing user.
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
	<h2>Edit User</h2>
</header>

<?php echo $this->element('user_form', array('edit' => true)); ?>
