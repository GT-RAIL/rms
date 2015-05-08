<?php
/**
 * Admin ROS View
 *
 * The ROS index view displays menu information for the relevant controllers.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Ros
 */
?>

<header class="special container">
	<span class="icon fa-cloud"></span>
	<h2>ROS Settings</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<p>Choose from the following options to edit ROS settings.</p>
			</header>
			<div class="row">
				<section class="4u">
					<?php
					echo $this->Html->link(
						'Environments',
						array('controller' => 'environments', 'action' => 'index'),
						array('class' => 'button special')
					);
					?>
				</section>
				<section class="4u">
					<?php
					echo $this->Html->link(
						'rosbridge Servers',
						array('controller' => 'rosbridges', 'action' => 'index'),
						array('class' => 'button special')
					);
					?>
				</section>
				<section class="4u">
					<?php
					echo $this->Html->link(
						'MJPEG Servers',
						array('controller' => 'mjpegs', 'action' => 'index'),
						array('class' => 'button special')
					);
					?>
				</section>
			</div>
			<div class="row">
				<section class="6u">
					<?php
					echo $this->Html->link(
						'Topics & Widgets',
						array('controller' => 'widget', 'action' => 'index'),
						array('class' => 'button special')
					);
					?>
				</section>
				<section class="6u">
					<?php
					echo $this->Html->link(
						'Interfaces',
						array('controller' => 'ifaces', 'action' => 'index'),
						array('class' => 'button special')
					);
					?>
				</section>
			</div>
		</section>
	</div>
</section>
