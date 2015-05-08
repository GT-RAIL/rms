<?php
/**
 * Admin MJPEG Index View
 *
 * The MJPEG index page displays a list of all MJPEG servers in the database. An admin may edit, add, or delete from
 * this list.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Mjpegs
 */
?>

<header class="special container">
	<span class="icon fa-gear"></span>
	<h2>MJPEG Servers</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<p>
					MJPEG server is a streaming server that subscribes to requested image topics in ROS and publishes
					those topics as MJPEG streams via HTTP.
				</p>
			</header>
			<?php echo $this->Html->link('Create New Entry', array('action' => 'add'), array('class' => 'button')); ?>
			<br /><br />
			<table>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Name</th>
					<th>URI</th>
					<th>Status</th>
					<th><?php echo $this->Html->link('Environments', array('controller' => 'environments')); ?></th>
					<th><?php echo $this->Html->link('Streams', array('controller' => 'streams')); ?></th>
				</tr>
				<?php foreach ($mjpegs as $mjpeg): ?>
					<tr>
						<td>
							<?php
							echo $this->Form->postLink(
								'',
								array('action' => 'delete', $mjpeg['Mjpeg']['id']),
								array('class' => 'icon fa-trash-o', 'confirm' => 'Are you sure?')
							);
							?>
							<?php
							echo $this->Html->link(
								'',
								array('action' => 'edit', $mjpeg['Mjpeg']['id']),
								array('class' => 'icon fa-edit')
							);
							?>
						</td>
						<td data-title="ID">
							<?php echo h($mjpeg['Mjpeg']['id']); ?>
						</td>
						<td data-title="Name">
							<?php echo h($mjpeg['Mjpeg']['name']); ?>
						</td>
						<td data-title="URI">
							<?php
							echo $this->Html->link(
								__('http://%s:%s', h($mjpeg['Mjpeg']['host']), h($mjpeg['Mjpeg']['port'])),
								array('action' => 'view', $mjpeg['Mjpeg']['id'])
							);
							?>
						</td>
						<td data-title="Status">
							<?php
							echo $this->Rms->mjpegServerStatus($mjpeg['Mjpeg']['host'], $mjpeg['Mjpeg']['port']);
							?>
						</td>
						<td data-title="Environments">
							<?php echo count($mjpeg['Environment']); ?>
						</td>
						<td data-title="Streams">
							<?php
							$streams = 0;
							foreach ($mjpeg['Environment'] as $env) {
								$streams += count($env['Stream']);
							}
							echo h($streams);
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>
