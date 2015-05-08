<?php
/**
 * Admin Interactive Maker View
 *
 * The URDF view allows an admin to see the URDF in a viewer.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Urdfs
 */
?>

<?php
// setup the ROS connection
echo $this->Rms->ros(
	__(
		'%s://%s:%d',
		h($urdf['Environment']['Rosbridge']['Protocol']['name']),
		h($urdf['Environment']['Rosbridge']['host']),
		h($urdf['Environment']['Rosbridge']['port'])
	),
	h($urdf['Environment']['Rosbridge']['rosauth'])
);
// setup the TF client
echo $this->Rms->tf(
	$urdf['Environment']['Tf']['frame'],
	$urdf['Environment']['Tf']['angular'],
	$urdf['Environment']['Tf']['translational'],
	$urdf['Environment']['Tf']['rate']
);
?>

<header class="special container">
	<span class="icon fa-cloud"></span>
	<h2><?php echo h($urdf['Urdf']['param']); ?></h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<strong>
			<?php echo h($urdf['Environment']['Rosbridge']['name']); ?>
			<?php
				echo $this->Rms->rosbridgeStatus(
					$urdf['Environment']['Rosbridge']['Protocol']['name'],
					$urdf['Environment']['Rosbridge']['host'],
					$urdf['Environment']['Rosbridge']['port']
				);
			?>
		</strong>
		<?php echo $this->Rms->ros3d(); ?>
		<script>_VIEWER.addObject(new ROS3D.Grid());</script>
		<?php echo $this->Rms->urdf($urdf['Urdf']['param'], $urdf['Collada']['id'], $urdf['Resource']['url']); ?>
	</div>
</section>
