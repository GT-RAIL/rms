<?php
/**
 * Default Fatal Error Page
 *
 * The fatal error page will display a simple error message stating the name of the error.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.View.Errors
 */
?>

<header class="special container">
	<span class="icon fa-warning"></span>
	<h2><?php echo $name; ?></h2>
</header>

<section class="wrapper style4 container">
	<div class="content">
		<section>
			<p>
				<strong>Error:</strong> An Internal Error Has Occurred.
			</p>
		</section>
	</div>
</section>
