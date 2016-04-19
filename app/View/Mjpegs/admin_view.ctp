<?php
/**
 * Admin MJPEG Server View
 *
 * The MJPEG server view allows an admin to see diagnostic information about the MJPEG server.
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
	<span class="icon fa-video-camera"></span>
	<h2><?php echo h($mjpeg['Mjpeg']['name']); ?></h2>
	<p>
		<?php echo __('http://%s:%s', h($mjpeg['Mjpeg']['host']), h($mjpeg['Mjpeg']['port'])); ?>
	</p>
</header>

<section class="wrapper style4 container">
	<div class="content">
		<?php
		$topics = array();
		foreach ($mjpeg['Environment'] as $env) {
			foreach ($env['Stream'] as $stream) {
				$topics[] = $stream['topic'];
			}
		}
		echo $this->Rms->mjpegPanel($mjpeg['Mjpeg']['host'], $mjpeg['Mjpeg']['port'], $topics);
		?>
	</div>
</section>
