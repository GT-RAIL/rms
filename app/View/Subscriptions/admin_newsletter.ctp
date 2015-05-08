<?php
/**
 * Admin Newsletter View
 *
 * The newsletter page will allow an admin to send a newsletter.
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
	<h2>Send Newsletter</h2>
</header>

<section class="wrapper style4 container">
	<?php if($setting['Setting']['email']): ?>
		<div class="content">
			<header>
				<p>
					Newsletters will be sent to all active subscribers. By default, new users are subscribed to
					newsletters. This form accepts HTML as input.
				</p>
			</header>
			<section>
				<?php echo $this->Form->create('Newsletter'); ?>
				<div class="row">
					<section class="12u">
						<?php
						echo $this->Form->input(
							'message',
							array('label' => '', 'type' => 'textarea', 'required' => true)
						);
						?>
						<p><?php echo __('--The %s Team', $setting['Setting']['title']);?></p>
						<?php echo $this->Form->end(array('label' => 'Send', 'class' => 'button special')); ?>
					</section>
				</div>
			</section>
		</div>
	<?php else: ?>
		<div class="content center">
			<p>
				Email settings have been <strong>disabled</strong>. Please enable them in the
				<?php
				echo $this->Html->link(
					'Site Settings',
					array('controller' => 'settings', 'action' => 'edit')
				);
				?>
			</p>
		</div>
	<?php endif; ?>
</section>
