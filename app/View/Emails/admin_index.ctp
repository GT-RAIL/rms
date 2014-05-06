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
 * @version		2.0.0
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
			<?php if($setting['Setting']['email']): ?>
			<div class="row">
				<section class="6u">
					<strong><u>SMTP Host</u>:</strong>
					<?php echo ($email['Email']['host']) ? h($email['Email']['host']) : 'N/A'; ?>
					<br />
					<strong><u>SMTP Port</u>:</strong>
					<?php echo ($email['Email']['port']) ? h($email['Email']['port']) : 'N/A'; ?>
					<br />
					<strong><u>SMTP Username</u>:</strong>
					<?php echo ($email['Email']['username']) ? h($email['Email']['username']) : 'N/A'; ?>
					<br />
					<strong><u>SMTP Password</u>:</strong>
					<?php echo ($email['Email']['password']) ? '****************' : 'N/A'; ?>
				</section>
				<section class="6u">
					<strong><u>Sender</u>:</strong>
					<?php echo ($email['Email']['from']) ? h($email['Email']['from']) : 'N/A'; ?>
					<br />
					<strong><u>Sender Alias</u>:</strong>
					<?php echo ($email['Email']['alias']) ? h($email['Email']['alias']) : 'N/A'; ?>
					<br />
					<strong><u>TLS</u>:</strong>
					<?php echo ($email['Email']['tls']) ? 'Enabled' : 'Disabled'; ?>
					<br />
					<?php
					echo $this->Html->link(' Edit All', array('action' => 'edit'), array('class' => 'icon fa-edit'));
					?>
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
