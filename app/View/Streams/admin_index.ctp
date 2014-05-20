<?php
/**
 * Admin MJPEG Stream Index View
 *
 * The MJPEG stream index page displays a list of all MJPEG streams in the database. An admin may edit, add, or delete
 * from this list.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.View.Streams
 */
?>

<header class="special container">
	<span class="icon fa-gear"></span>
	<h2>MJPEG Server Streams</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<p>
					MJPEG streams correspond to ROS image topics streamed via the MJPEG Server node.
				</p>
			</header>
			<?php echo $this->Html->link('Create New Entry', array('action' => 'add'), array('class' => 'button')); ?>
			<br /><br />
			<table>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Name</th>
					<th>Topic</th>
					<th>Size</th>
					<th>Quality</th>
					<th>Invert</th>
					<th><?php echo $this->Html->link('Environment', array('controller' => 'environments')); ?></th>
				</tr>
				<?php foreach ($streams as $stream): ?>
					<tr>
						<td>
							<?php
							echo $this->Form->postLink(
								'',
								array('action' => 'delete', $stream['Stream']['id']),
								array('class' => 'icon fa-trash-o', 'confirm' => 'Are you sure?')
							);
							?>
							<?php
							echo $this->Html->link(
								'',
								array('action' => 'edit', $stream['Stream']['id']),
								array('class' => 'icon fa-edit')
							);
							?>
						</td>
						<td data-title="ID">
							<?php echo h($stream['Stream']['id']); ?>
						</td>
						<td data-title="Name">
							<?php echo h($stream['Stream']['name']); ?>
						</td>
						<td data-title="Topic">
							<?php
							echo $this->Html->link(
								h($stream['Stream']['topic']),
								array('action' => 'view', $stream['Stream']['id'])
							);
							?>
						</td>
						<td data-title="Size">
							<?php echo ($stream['Stream']['width']) ? h($stream['Stream']['width']) : 'original'; ?>
							x
							<?php echo ($stream['Stream']['height']) ? h($stream['Stream']['height']) : 'original'; ?>
						</td>
						<td data-title="Quality">
							<?php echo ($stream['Stream']['quality']) ? h($stream['Stream']['quality']) : 'N/A'; ?>
						</td>
						<td data-title="Invert">
							<?php echo ($stream['Stream']['invert']) ? 'Yes' : 'No'; ?>
						</td>
						<td data-title="Environment">
							<?php echo h($stream['Environment']['name']); ?>
							<span id="<?php echo __('mjpeg-%s', h($stream['Environment']['Mjpeg']['id'])); ?>">
								<span class="icon orange fa-spinner"></span>
							</span>
							<br />
							<?php
							echo $this->Html->link(
								__(
									'http://%s:%s',
									h($stream['Environment']['Mjpeg']['host']),
									h($stream['Environment']['Mjpeg']['port'])
								),
								array(
									'controller' => 'mjpegs',
									'action' => 'view',
									$stream['Environment']['Mjpeg']['id']
								)
							);
							?>
							<script type="text/javascript">
								RMS.verifyMjpegServer(
									'<?php echo (h($stream['Environment']['Mjpeg']['host'])); ?>',
									<?php echo (h($stream['Environment']['Mjpeg']['port'])); ?>,
									'<?php echo __('mjpeg-%s', h($stream['Environment']['Mjpeg']['id'])); ?>'
								);
							</script>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>
