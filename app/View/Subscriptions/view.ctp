<?php
/**
 * Subscription Settings View
 *
 * The subscription settings page allows a user to see their email subscriptions.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Subscriptions
 */
?>

<header class="special container">
	<span class="icon fa-envelope"></span>
	<h2>Email Subscriptions</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<h3>Use the Checkboxes Below to Toggle Email Subscription Settings</h3>
			</header>
			<div class="row">
				<section class="4u">
					<strong><u>Newsletter</u></strong>
					<br />
					<?php if ($subscription['Subscription']['newsletter']): ?>
						<?php
						echo $this->Form->postLink(
							'', array('action' => 'newsletters'),
							array('class' => 'icon fa-check-square-o')
						);
						?>
						Enabled
					<?php else: ?>
						<?php
						echo $this->Form->postLink(
							'', array('action' => 'newsletters'),
							array('class' => 'icon fa-square-o')
						);
						?>
						Disabled
					<?php endif; ?>
				</section>
				<section class="4u">
					<strong><u>New Study Announcements</u></strong>
					<br />
					<?php if ($subscription['Subscription']['studies']): ?>
						<?php
						echo $this->Form->postLink(
							'', array('action' => 'studies'),
							array('class' => 'icon fa-check-square-o')
						);
						?>
						Enabled
					<?php else: ?>
						<?php
						echo $this->Form->postLink(
							'', array('action' => 'studies'),
							array('class' => 'icon fa-square-o')
						);
						?>
						Disabled
					<?php endif; ?>
				</section>
				<section class="4u">
					<strong><u>Study Reminders</u></strong>
					<br />
					<?php if ($subscription['Subscription']['reminders']): ?>
						<?php
						echo $this->Form->postLink(
							'', array('action' => 'reminders'),
							array('class' => 'icon fa-check-square-o')
						);
						?>
						Enabled
					<?php else: ?>
						<?php
						echo $this->Form->postLink(
							'', array('action' => 'reminders'),
							array('class' => 'icon fa-square-o')
						);
						?>
						Disabled
					<?php endif; ?>
				</section>
			</div>
		</section>
	</div>
</section>
