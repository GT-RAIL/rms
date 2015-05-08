<?php
/**
 * Admin MJPEG Stream View
 *
 * The MJPEG stream view allows an admin to see see the given camera stream if it is up.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Streams
 */
?>

<header class="special container">
	<span class="icon fa-video-camera"></span>
	<h2><?php echo h($stream['Stream']['name']); ?></h2>
	<p><?php echo h($stream['Stream']['topic']); ?></p>
</header>

<section class="wrapper style4 container">
	<div class="content">
		<?php if(isset($stream['Environment']['Mjpeg']['host'])): ?>
			<?php
			echo $this->Rms->mjpegStream(
				$stream['Environment']['Mjpeg']['host'],
				$stream['Environment']['Mjpeg']['port'],
				$stream['Stream']['topic'],
				$stream['Stream']
			);
			?>
		<?php else: ?>
			<h2 class="center">Error: No MJPEG Server Defined for this Environment</h2>
		<?php endif; ?>
	</div>
</section>
