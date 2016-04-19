<?php
/**
 * Admin Environment View
 *
 * The environment view allows an admin to see diagnostic information about the associated rosbridge and MJPEG server.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Environments
 */
?>

<header class="special container">
	<span class="icon fa-cloud"></span>
	<h2><?php echo h($environment['Environment']['name']); ?></h2>
	<p>
		<?php
		if ($environment['Rosbridge']['host']) {
			echo __(
				'%s://%s:%s',
				h($environment['Rosbridge']['Protocol']['name']),
				h($environment['Rosbridge']['host']),
				h($environment['Rosbridge']['port'])
			);
		}
		?>
		<br />
		<?php
		if ($environment['Mjpeg']['host']) {
			echo __('http://%s:%s', h($environment['Mjpeg']['host']), h($environment['Mjpeg']['port']));
		}
		?>
	</p>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<ul class="buttons">
				<li><a href="#rosbridge" class="button special scrolly">rosbridge Server</a></li>
				<li><a href="#mjpeg" class="button special scrolly">MJPEG Server</a></li>
			</ul>
		</section>
	</div>
</section>

<section id="rosbridge" class="wrapper style4 container">
	<div class="content">
		<?php
		if ($environment['Rosbridge']['host']) {
			echo $this->Rms->rosbridgePanel(
				$environment['Rosbridge']['Protocol']['name'],
				$environment['Rosbridge']['host'],
				$environment['Rosbridge']['port']
			);
		}
		?>
	</div>
</section>

<section id="mjpeg" class="wrapper style4 container">
	<div class="content">
		<?php
		$topics = array();
		foreach ($environment['Stream'] as $stream) {
			$topics[] = $stream['topic'];
		}
		echo $this->Rms->mjpegPanel($environment['Mjpeg']['host'], $environment['Mjpeg']['port'], $topics);
		?>
	</div>
</section>
