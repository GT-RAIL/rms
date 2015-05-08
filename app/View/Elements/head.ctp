<?php
/**
 * Default Head Section
 *
 * The default head section will load all necessary JavaScript and CSS styles for the main RMS system.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Elements
 */
?>

<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo __('%s: %s', h($setting['Setting']['title']), h($title_for_layout)); ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->meta('description', '');
		echo $this->Html->meta('keywords', '');
	?>

	<?php if(!isset($style) || $style): ?>
		<!--[if lte IE 8]><?php echo $this->Html->script('../css/ie/html5shiv.min'); ?><![endif]-->
	<?php endif; ?>

	<?php
	echo $this->Html->script(array(
		'//code.jquery.com/jquery-1.11.0.js',
		'rms'
	));

	if(!isset($style) || $style) {
		echo $this->Html->script(array(
			'jquery.dropotron.min',
			'skel.min',
			'skel-layers.min',
			'init.min',
			'rms'
		));
	}

	// check for RWT libraries
	if (isset($rwt)) {
		if (isset($rwt['roslibjs'])) {
			echo $this->Html->script(array(
				'//s3.amazonaws.com/cdn.robotwebtools.org/EventEmitter2/current/eventemitter2.min.js',
				'//s3.amazonaws.com/cdn.robotwebtools.org/roslibjs/' . h($rwt['roslibjs']) . '/roslib.min.js',
			));
		}
		if (isset($rwt['keyboardteleopjs'])) {
			echo $this->Html->script(
				array(
					'//s3.amazonaws.com/cdn.robotwebtools.org/keyboardteleopjs/' . h($rwt['keyboardteleopjs']) . '/keyboardteleop.min.js'
				)
			);
		}
		if (isset($rwt['ros2djs'])) {
			echo $this->Html->script(array(
				'//s3.amazonaws.com/cdn.robotwebtools.org/EaselJS/current/easeljs.min.js',
				'//s3.amazonaws.com/cdn.robotwebtools.org/ros2djs/' . h($rwt['ros2djs']) . '/ros2d.min.js',
			));
		}
		if (isset($rwt['nav2djs'])) {
			echo $this->Html->script(array(
				'//s3.amazonaws.com/cdn.robotwebtools.org/nav2djs/' . h($rwt['nav2djs']) . '/nav2d.min.js',
			));
		}
		if (isset($rwt['ros3djs'])) {
			echo $this->Html->script(array(
				'//s3.amazonaws.com/cdn.robotwebtools.org/threejs/current/three.min.js',
				'//s3.amazonaws.com/cdn.robotwebtools.org/threejs/current/ColladaLoader.min.js',
				'//s3.amazonaws.com/cdn.robotwebtools.org/ColladaAnimationCompress/current/ColladaLoader2.min.js',
				'//s3.amazonaws.com/cdn.robotwebtools.org/ros3djs/' . h($rwt['ros3djs']) . '/ros3d.min.js',
			));
		}
		if (isset($rwt['mjpegcanvasjs'])) {
			echo $this->Html->script(array(
				'//s3.amazonaws.com/cdn.robotwebtools.org/mjpegcanvasjs/' . h($rwt['mjpegcanvasjs']) . '/mjpegcanvas.min.js'
			));
		}
		if (isset($rwt['rosqueuejs'])) {
			echo $this->Html->script(array(
				'//s3.amazonaws.com/cdn.robotwebtools.org/rosqueuejs/' . h($rwt['rosqueuejs']) . '/rosqueue.min.js'
			));
		}
	}
	?>

	<?php if(!isset($style) || $style): ?>
		<noscript>
			<?php echo $this->Html->css(array('skel', 'style', 'style-noscript')); ?>
		</noscript>

		<!--[if lte IE 8]><?php echo $this->Html->css('ie/v8'); ?><![endif]-->
		<!--[if lte IE 9]><?php echo $this->Html->css('ie/v9'); ?><![endif]-->
	<?php endif; ?>

	<?php
	// custom CSS
	if (isset($css)) {
		echo $this->Html->css($css);
	}
	?>

	<?php if ($setting['Setting']['analytics']): ?>
		<script>
			var _gaq = _gaq || [];
			_gaq.push(["_setAccount", "<?php echo h($setting['Setting']['analytics']); ?>"]);
			_gaq.push(["_trackPageview"]);
			(function() {
			var ga = document.createElement("script"); ga.type = "text/javascript"; ga.async = true;
			ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
			var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
	<?php endif; ?>

	<?php
	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
	?>
</head>
