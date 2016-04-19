<?php
/**
 * Admin Environment Index View
 *
 * The environment index page displays a list of all environments in the database. An admin may edit, add, or delete
 * from this list.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Environments
 */
?>

<header class="special container">
	<span class="icon fa-gear"></span>
	<h2>Environments</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<p>
					Environments are the central component of the RMS. A robot environment can consist of a rosbridge
					and MJPEG server, as well as associated topics and image streams. An interface can be linked
					together with an environment to gain access to this information.
				</p>
			</header>
			<?php echo $this->Html->link('Create New Entry', array('action' => 'add'), array('class' => 'button')); ?>
			<br /><br />
			<table>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Name</th>
					<th><?php echo $this->Html->link('rosbridge Server', array('controller' => 'rosbridges')); ?></th>
					<th><?php echo $this->Html->link('MJPEG Server', array('controller' => 'mjpegs')); ?></th>
					<th><?php echo $this->Html->link('Interfaces', array('controller' => 'ifaces')); ?></th>
				</tr>
				<?php foreach ($environments as $env): ?>
					<tr>
						<td>
							<?php
							echo $this->Form->postLink(
								'',
								array('action' => 'delete', $env['Environment']['id']),
								array('class' => 'icon fa-trash-o', 'confirm' => 'Are you sure?')
							);
							?>
							<?php
							echo $this->Html->link(
								'',
								array('action' => 'edit', $env['Environment']['id']),
								array('class' => 'icon fa-edit')
							);
							?>
						</td>
						<td data-title="ID">
							<?php echo h($env['Environment']['id']); ?>
						</td>
						<td data-title="Name">
							<?php
							echo $this->Html->link(
								$env['Environment']['name'],
								array('action' => 'view', $env['Environment']['id'])
							);
							?>
						</td>
						<td data-title="rosbridge Server">
							<?php if (!$env['Rosbridge']['id']): ?>
								N/A
							<?php else: ?>
								<?php echo h($env['Rosbridge']['name']); ?>
								<?php
								echo $this->Rms->rosbridgeStatus(
									$env['Rosbridge']['Protocol']['name'],
									$env['Rosbridge']['host'],
									$env['Rosbridge']['port']
								);
								?>
								<br />
								<?php
								echo $this->Html->link(
									__(
										'%s://%s:%s',
										h($env['Rosbridge']['Protocol']['name']),
										h($env['Rosbridge']['host']),
										h($env['Rosbridge']['port'])
									),
									array('controller' => 'rosbridges', 'action' => 'view', $env['Rosbridge']['id'])
								);
								?>
							<?php endif; ?>
						</td>
						<td data-title="MJPEG Server">
							<?php if (!$env['Mjpeg']['id']): ?>
								N/A
							<?php else: ?>
								<?php echo h($env['Mjpeg']['name']); ?>
								<?php
								echo $this->Rms->mjpegServerStatus($env['Mjpeg']['host'], $env['Mjpeg']['port']);
								?>
								<br />
								<?php
								echo $this->Html->link(
									__('http://%s:%s', h($env['Mjpeg']['host']), h($env['Mjpeg']['port'])),
									array('controller' => 'mjpegs', 'action' => 'view', $env['Mjpeg']['id'])
								);
								?>
							<?php endif; ?>
						</td>
						<td data-title="Interfaces">
							<?php echo count($env['Iface']); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>
