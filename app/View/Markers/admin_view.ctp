<?php
/**
 * Admin Maker View
 *
 * The marker view allows an admin to see the marker topic in a viewer.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Markers
 */
?>

<?php
// setup the ROS connection
echo $this->Rms->ros(
	__(
		'%s://%s:%d',
		h($marker['Environment']['Rosbridge']['Protocol']['name']),
		h($marker['Environment']['Rosbridge']['host']),
		h($marker['Environment']['Rosbridge']['port'])
	),
	h($marker['Environment']['Rosbridge']['rosauth'])
);
// setup the TF client
echo $this->Rms->tf(
	$marker['Environment']['Tf']['frame'],
	$marker['Environment']['Tf']['angular'],
	$marker['Environment']['Tf']['translational'],
	$marker['Environment']['Tf']['rate']
);
?>

<header class="special container">
	<span class="icon fa-cloud"></span>
	<h2><?php echo h($marker['Marker']['topic']); ?></h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<strong>
			<?php echo h($marker['Environment']['Rosbridge']['name']); ?>
			<?php
				echo $this->Rms->rosbridgeStatus(
					$marker['Environment']['Rosbridge']['Protocol']['name'],
					$marker['Environment']['Rosbridge']['host'],
					$marker['Environment']['Rosbridge']['port']
				);
			?>
		</strong>
		<?php echo $this->Rms->ros3d(); ?>
		<?php echo $this->Rms->marker($marker['Marker']['topic']); ?>
	</div>
</section>
