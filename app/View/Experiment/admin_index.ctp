<?php
/**
 * Admin Experiment View
 *
 * The experiment index view displays menu information for the relevant controllers.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Experiment
 */
?>

<header class="special container">
	<span class="icon fa-users"></span>
	<h2>Experiment Settings</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<p>Choose from the following options to edit experiment settings.</p>
			</header>
			<div class="row">
				<section class="6u">
					<?php
					echo $this->Html->link(
						'Studies',
						array('controller' => 'studies', 'action' => 'index'),
						array('class' => 'button special')
					);
					?>
				</section>
				<section class="6u">
					<?php
					echo $this->Html->link(
						'Conditions',
						array('controller' => 'conditions', 'action' => 'index'),
						array('class' => 'button special')
					);
					?>
				</section>
			</div>
			<div class="row">
				<section class="4u">
					<?php
					echo $this->Html->link(
						'Slots',
						array('controller' => 'slots', 'action' => 'index'),
						array('class' => 'button special')
					);
					?>
				</section>
				<section class="4u">
					<?php
					echo $this->Html->link(
						'Study Logs',
						array('controller' => 'logs', 'action' => 'index'),
						array('class' => 'button special')
					);
					?>
				</section>
				<section class="4u">
					<?php
					echo $this->Html->link(
						'Send Announcement',
						array('controller' => 'subscriptions', 'action' => 'announcement'),
						array('class' => 'button special')
					);
					?>
				</section>
			</div>
		</section>
	</div>
</section>
