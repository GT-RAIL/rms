<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->meta('description', '');
		echo $this->Html->meta('keywords', '');
	?>
	<!--[if lte IE 8]><?php echo $this->Html->script('../css/ie/html5shiv'); ?><![endif]-->
	<?php
	echo $this->Html->script(array(
		'//code.jquery.com/jquery-1.11.0.min.js',
		'jquery.dropotron.min',
		'skel.min',
		'skel-layers.min',
		'init'
	));
	?>

	<noscript>
		<?php echo $this->Html->css(array('skel', 'style', 'style-noscript')); ?>
	</noscript>

	<!--[if lte IE 8]><?php echo $this->Html->css('ie/v8'); ?><![endif]-->
	<!--[if lte IE 9]><?php echo $this->Html->css('ie/v9'); ?><![endif]-->

	<?php
	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
	?>
</head>