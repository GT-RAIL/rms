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
 * @version		2.0.0
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
		<section id="stream" class="stream center">
			<h2>Acquiring connection... <span class="icon orange fa-spinner"></span></h2>
		</section>
	</div>
</section>

<script>
	// attempt to get the stream
	RMS.generateStream(
		'<?php echo h($stream['Environment']['Mjpeg']['host']); ?>',
		<?php echo h($stream['Environment']['Mjpeg']['port']); ?>,
		'<?php echo h($stream['Stream']['topic']); ?>',
		'stream',
		{
			width : <?php echo ($stream['Stream']['width']) ? h($stream['Stream']['width']) : 'null'; ?>,
			height : <?php echo ($stream['Stream']['height']) ? h($stream['Stream']['height']) : 'null'; ?>,
			quality : <?php echo ($stream['Stream']['quality']) ? h($stream['Stream']['quality']) : 'null'; ?>,
			invert : <?php echo ($stream['Stream']['invert']) ? 'true' : 'false'; ?>
		}
	);
</script>