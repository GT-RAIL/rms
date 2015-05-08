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
 * @version		2.0.9
 * @package		app.View.BasicInterface
 */
?>

<?php
// setup the main ROS connection and any study information
if($environment['Rosbridge']['host']) {
	echo $this->Rms->ros($environment['Rosbridge']['uri'], $environment['Rosbridge']['rosauth']);
}
echo $this->Rms->initStudy();
?>

<script>
	RMS.logString('start', 'User has connected.');
</script>

<header class="special container">
	<span class="icon fa-cloud"></span>
	<h2>Basic Interface</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<p>Use the <strong>W, A, S, D, Q, E</strong> keys to drive your robot.</p>
			</header>
			<div class="row">
				<section class="6u stream">
					<?php if($environment['Mjpeg']['host']): ?>
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
					<?php else: ?>
						<h2>No Associated MJPEG Server Found</h2>
					<?php endif; ?>
				</section>
				<section class="6u">
					<?php if($environment['Rosbridge']['host']): ?>
						<?php if(count($environment['Teleop']) > 0): ?>
							<?php echo $this->Rms->keyboardTeleop($environment['Teleop'][0]['topic']); ?>
							<pre class="rostopic"><code id="speed">Awaiting data...</code></pre>
							<script>
								var topic = new ROSLIB.Topic({
									ros : _ROS,
									name : '<?php echo h($environment['Teleop'][0]['topic']);?>'
								});
								topic.subscribe(function(message) {
									$('#speed').html(RMS.prettyJson(message));
								});
							</script>
						<?php else: ?>
							<h2>No Associated Telop Settings Found</h2>
						<?php endif; ?>
					<?php else: ?>
						<h2>No Associated rosbridge Server Found</h2>
					<?php endif; ?>
				</section>
			</div>
		</section>
	</div>
</section>
