<?php
/**
 * Admin Interactive Maker View
 *
 * The interactive marker view allows an admin to see the interactive marker topic in a viewer.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Ims
 */
?>

<?php
// setup the ROS connection
echo $this->Rms->ros(
	__(
		'%s://%s:%d',
		h($im['Environment']['Rosbridge']['Protocol']['name']),
		h($im['Environment']['Rosbridge']['host']),
		h($im['Environment']['Rosbridge']['port'])
	),
	h($im['Environment']['Rosbridge']['rosauth'])
);
// setup the TF client
echo $this->Rms->tf(
	$im['Environment']['Tf']['frame'],
	$im['Environment']['Tf']['angular'],
	$im['Environment']['Tf']['translational'],
	$im['Environment']['Tf']['rate']
);
?>

<header class="special container">
	<span class="icon fa-cloud"></span>
	<h2><?php echo h($im['Im']['topic']); ?></h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<strong>
			<?php echo h($im['Environment']['Rosbridge']['name']); ?>
			<?php
				echo $this->Rms->rosbridgeStatus(
					$im['Environment']['Rosbridge']['Protocol']['name'],
					$im['Environment']['Rosbridge']['host'],
					$im['Environment']['Rosbridge']['port']
				);
			?>
		</strong>
		<?php echo $this->Rms->ros3d(); ?>
		<?php echo $this->Rms->interactiveMarker($im['Im']['topic'], $im['Collada']['id'], $im['Resource']['url']); ?>
	</div>
</section>
