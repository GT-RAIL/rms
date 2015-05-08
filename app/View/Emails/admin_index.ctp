<?php
/**
 * Admin SMTP Email Settings Index View
 *
 * The SMTP email settings index page displays a list of all SMTP settings. An admin can edit these settings.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Emails
 */
?>

<header class="special container">
	<span class="icon fa-gear"></span>
	<h2>SMTP Email Settings</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<p>
					SMTP email settings can be set to allow the RMS to send automated emails such as password resets,
					newsletters, or study announcements.
				</p>
			</header>
			<?php if($setting['Setting']['email']): ?>
				<?php echo $this->Html->link('Edit Settings', array('action' => 'edit'), array('class' => 'button')); ?>
				<br /><br />
				<div class="row">
					<section class="6u">
						<strong><u>SMTP Host</u></strong>
						<br />
						<?php echo ($email['Email']['host']) ? h($email['Email']['host']) : 'N/A'; ?>
					</section>
					<section class="3u">
						<strong><u>SMTP Port</u></strong>
						<br />
						<?php echo ($email['Email']['port']) ? h($email['Email']['port']) : 'N/A'; ?>
					</section>
					<section class="3u">
						<strong><u>TLS</u></strong>
						<br />
						<?php echo ($email['Email']['tls']) ? 'Enabled' : 'Disabled'; ?>
					</section>
				</div>
				<div class="row">
					<section class="6u">
						<strong><u>SMTP Username</u></strong>
						<br />
						<?php echo ($email['Email']['username']) ? h($email['Email']['username']) : 'N/A'; ?>
					</section>
					<section class="6u">
						<strong><u>SMTP Password</u></strong>
						<br />
						<?php echo ($email['Email']['password']) ? h($email['Email']['password']) : 'N/A'; ?>
					</section>
				</div>
				<div class="row">
					<section class="6u">
						<strong><u>Sender's Email</u></strong>
						<br />
						<?php echo ($email['Email']['from']) ? h($email['Email']['from']) : 'N/A'; ?>
					</section>
					<section class="6u">
						<strong><u>Sender Alias</u></strong>
						<br />
						<?php echo ($email['Email']['alias']) ? h($email['Email']['alias']) : 'N/A'; ?>
					</section>
				</div>
			<?php else: ?>
				<p>
					Email settings have been <strong>disabled</strong>. Please enable them in the
					<?php
					echo $this->Html->link(
						'Site Settings',
						array('controller' => 'settings', 'action' => 'edit')
					);
					?>
				</p>
			<?php endif; ?>
		</section>
	</div>
</section>
