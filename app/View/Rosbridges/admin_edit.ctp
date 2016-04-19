<?php
/**
 * Admin Edit rosbridge Server View
 *
 * The edit pages view allows an admin to edit an existing rosbridge server in the database.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Rosbridges
 */
?>

<header class="special container">
	<span class="icon fa-pencil"></span>
	<h2>Edit rosbridge Server</h2>
</header>

<?php echo $this->element('rosbridge_form', array('edit' => true)); ?>
