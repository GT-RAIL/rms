<?php
/**
 * Admin rosbridge Server View
 *
 * The rosbridge server view allows an admin to see diagnostic information about the rosbridge server.
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
	<span class="icon fa-cloud"></span>
	<h2><?php echo h($rosbridge['Rosbridge']['name']); ?></h2>
	<p>
		<?php
		echo __(
			'%s://%s:%s',
			h($rosbridge['Protocol']['name']),
			h($rosbridge['Rosbridge']['host']),
			h($rosbridge['Rosbridge']['port'])
		);
		?>
	</p>
</header>

<section class="wrapper style4 container">
	<div class="content">
		<?php
		echo $this->Rms->rosbridgePanel(
			$rosbridge['Protocol']['name'],
			$rosbridge['Rosbridge']['host'],
			$rosbridge['Rosbridge']['port']
		);
		?>
	</div>
</section>
