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
 * @version		2.0.0
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
							<?php echo h($env['Environment']['name']); ?>
						</td>
						<td data-title="rosbridge Server">
							<?php if (!$env['Rosbridge']['id']): ?>
								N/A
							<?php else: ?>
								<?php echo h($env['Rosbridge']['name']); ?>
								<span id="<?php echo __('rosbridge-%s', h($env['Rosbridge']['id'])); ?>">
									<span class="icon orange fa-spinner"></span>
								</span>
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
								<script type="text/javascript">
									RMS.verifyRosbridge(
										'<?php echo (h($env['Rosbridge']['Protocol']['name'])); ?>',
										'<?php echo (h($env['Rosbridge']['host'])); ?>',
										<?php echo (h($env['Rosbridge']['port'])); ?>,
										'<?php echo __('rosbridge-%s', h($env['Rosbridge']['id'])); ?>'
									);
								</script>
							<?php endif; ?>
						</td>
						<td data-title="MJPEG Server">
							<?php if (!$env['Mjpeg']['id']): ?>
								N/A
							<?php else: ?>
								<?php echo h($env['Mjpeg']['name']); ?>
								<span id="<?php echo __('mjpeg-%s', h($env['Mjpeg']['id'])); ?>">
									<span class="icon orange fa-spinner"></span>
								</span>
								<br />
								<?php
								echo $this->Html->link(
									__('http://%s:%s', h($env['Mjpeg']['host']), h($env['Mjpeg']['port'])),
									array('controller' => 'mjpegs', 'action' => 'view', $env['Mjpeg']['id'])
								);
								?>
								<script type="text/javascript">
									RMS.verifyMjpegServer(
										'<?php echo (h($env['Mjpeg']['host'])); ?>',
										<?php echo (h($env['Mjpeg']['port'])); ?>,
										'<?php echo __('mjpeg-%s', h($env['Mjpeg']['id'])); ?>'
									);
								</script>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>
