<?php
/**
 * Basic Interface View
 *
 * The basic interface displays a camera feed and keyboard teleop.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.View.Basic
 */
?>

<header class="special container">
	<span class="icon fa-cloud"></span>
	<h2>Basic Interface</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<div class="row">
				<section class="8u stream">
					<?php if(count($environment['Stream']) > 0): ?>
						<?php
						echo $this->Rms->mjpegStream(
							$environment['Mjpeg']['host'],
							$environment['Mjpeg']['port'],
							$environment['Stream'][0]['topic'],
							$environment['Stream'][0]
						);
						?>
					<?php else: ?>
						<h2>No Associated MJPEG Streams Found</h2>
					<?php endif; ?>
				</section>
				<section class="4u">
					<?php if(count($environment['Teleop']) > 0): ?>
						TODO
					<?php else: ?>
						<h2>No Associated Telop Settings Found</h2>
					<?php endif; ?>
				</section>
			</div>
		</section>
	</div>
</section>
