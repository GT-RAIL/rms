<?php
/**
 * Default Page Not Found
 *
 * The 400 page will display a simple error message stating that the given page does not exist.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Errors
 */
?>

<?php echo $this->element('error_header'); ?>

<section class="wrapper style4 container">
	<div class="content">
		<section>
			<p>
				<strong>Error:</strong>
				The requested address <strong><?php echo h($url); ?></strong> was not found on this server.
			</p>
		</section>
	</div>
</section>
