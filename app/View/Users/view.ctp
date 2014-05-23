<?php
/**
 * User View
 *
 * The user view displays account information for the given user. This will allow the user to edit their information.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.View.Users
 */
?>

<header class="special container">
	<span class="icon fa-user"></span>
	<h2><?php echo __('%s %s', h($user['User']['fname']), h($user['User']['lname'])); ?></h2>
	<p><?php echo h($user['User']['email']); ?></p>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				Use the following menu items to view and edit your account settings.
			</header>
			<br />
			<div class="row center">
				<section class="3u">
					<?php
					echo $this->Html->link(
						' Edit Information',
						array('action' => 'edit'),
						array('class' => 'icon fa-pencil')
					);
					?>
				</section>
				<section class="3u">
					<?php
					echo $this->Html->link( ' Email Subscriptions',
						array('controller' => 'subscriptions', 'action' => 'view'),
						array('class' => 'icon fa-envelope-o')
					);
					?>
				</section>
				<section class="3u">
					<?php
					echo $this->Html->link(
						' Change Password',
						array('action' => 'password'),
						array('class' => 'icon fa-lock')
					);
					?>
				</section>
				<section class="3u">
					<?php
					echo $this->Form->postLink(
						' Deactivate My Account',
						array('action' => 'delete'),
						array(
							'class' => 'icon fa-trash-o',
							'confirm' => 'Warning: You cannot undo this action. Continue?'
						)
					);
					?>
				</section>
			</div>
		</section>
	</div>
</section>

<?php if($admin): ?>
	<section class="wrapper style4 container">
		<div class="content center">
			<section>
				<header>
					<h2>Admin Interface Menu</h2>
				</header>
				<br /><hr />
				<?php foreach ($environments as $env): ?>
					<div class="row center">
						<section class="4u">
							<?php echo h($env['Environment']['name']); ?>
						</section>
						<section class="4u">
							<?php if (!$env['Rosbridge']['id']): ?>
								N/A
							<?php else: ?>
								<?php
								echo __(
										'%s://%s:%s',
										h($env['Rosbridge']['Protocol']['name']),
										h($env['Rosbridge']['host']),
										h($env['Rosbridge']['port'])
									);
								?>
								<?php
								echo $this->Rms->rosbridgeStatus(
									$env['Rosbridge']['Protocol']['name'],
									$env['Rosbridge']['host'],
									$env['Rosbridge']['port']
								);
								?>
							<?php endif; ?>
						</section>
						<section class="4u">
							<?php if (!$env['Mjpeg']['id']): ?>
								N/A
							<?php else: ?>
								<?php echo __('http://%s:%s', h($env['Mjpeg']['host']), h($env['Mjpeg']['port'])); ?>
								<?php
								echo $this->Rms->mjpegServerStatus($env['Mjpeg']['host'], $env['Mjpeg']['port']);
								?>
								<br />
							<?php endif; ?>
						</section>
					</div>
					<div class="row center">
						<section class="12u">
							<?php foreach ($env['Iface'] as $iface): ?>
								<?php
								echo $this->Html->link(
									$iface['name'],
									array(
										'controller' => 'ifaces',
										'action' => 'view',
										$iface['id'],
										$env['Environment']['id']
									)
								);
								?>
								<br />
							<?php endforeach; ?>
						</section>
					</div>
					<br /><hr />
				<?php endforeach; ?>
			</section>
		</div>
	</section>
<?php endif; ?>
