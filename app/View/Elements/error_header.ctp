<?php
/**
 * Default Error Page Header
 *
 * The error page header will display the name of the error.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Elements
 */
?>

<header class="special container">
	<span class="icon fa-warning"></span>
	<h2><?php echo h($name); ?></h2>
</header>
