<?php
/**
 * Admin Edit View
 *
 * The add page will allow an admin to create a new user manually.
 *
 * @author		Carl Saldanha - csaldanha3@gatech.edu
 * @copyright	2016 Georgia Institute Of Technology
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.AnonymousUsers
 */
?>

<header class="special container">
	<span class="icon fa-pencil"></span>
	<h2>Edit User</h2>
</header>

<?php echo $this->element('user_form', array('edit' => true)); ?>
