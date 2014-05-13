<?php
/**
 * Admin rosbridge Server View
 *
 * The rosbridge server view allows an admin to see diagnostic information about the rosbridge server.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.View.Rosbridges
 */
?>

<header class="special container">
	<span class="icon fa-cloud"></span>
	<h2><?php echo h($rosbridge['Rosbridge']['name']); ?></h2>
	<p>
		<?php
		echo __(
			'%s://%s:%s',
			h($rosbridge['Protocol']['name']),
			h($rosbridge['Rosbridge']['host']),
			h($rosbridge['Rosbridge']['port'])
		);
		?>
	<p>
</header>

<section class="wrapper style4 container">
	<div class="content">
		<section id="panel" class="center">
			<h2>Acquiring connection... <span class="icon orange fa-spinner"></span></h2>
		</section>
	</div>
</section>

<script>
	// attempt to get the connection
	RMS.generateRosbridgeDiagnosticPanel(
		'<?php echo (h($rosbridge['Protocol']['name'])); ?>',
		'<?php echo (h($rosbridge['Rosbridge']['host'])); ?>',
		<?php echo (h($rosbridge['Rosbridge']['port'])); ?>,
		'panel'
	);
</script>