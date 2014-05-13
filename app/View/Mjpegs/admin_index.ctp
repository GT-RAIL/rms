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
 * @version		2.0.0
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
			<?php echo $this->Html->link('Create New Entry', array('action' => 'add')); ?>
			<br /><br />
			<table>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Name</th>
					<th>URI</th>
					<th>Status</th>
					<th>Created</th>
					<th>Modified</th>
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
						<td>
							<?php echo h($mjpeg['Mjpeg']['id']); ?>
						</td>
						<td>
							<?php echo h($mjpeg['Mjpeg']['name']); ?>
						</td>
						<td>
							<?php
							echo __('http://%s:%s', h($mjpeg['Mjpeg']['host']), h($mjpeg['Mjpeg']['port']));
							?>
						</td>
						<td>
							<div id="<?php echo __('mjpeg-%s', h($mjpeg['Mjpeg']['id'])); ?>">
								<span class="icon orange fa-spinner"></span>
							</div>
							<script type="text/javascript">
								RMS.verifyMjpegServer(
									'<?php echo (h($mjpeg['Mjpeg']['host'])); ?>',
									<?php echo (h($mjpeg['Mjpeg']['port'])); ?>,
									'<?php echo __('mjpeg-%s', h($mjpeg['Mjpeg']['id'])); ?>'
								);
							</script>
						</td>
						<td>
							<?php echo h($mjpeg['Mjpeg']['created']); ?>
						</td>
						<td>
							<?php echo h($mjpeg['Mjpeg']['modified']); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>
