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
			<?php echo $this->Html->link('Create New Entry', array('action' => 'add')); ?>
			<br /><br />
			<table>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Name</th>
					<th><?php echo $this->Html->link('MJPEG Server', array('controller' => 'mjpegs')); ?></th>
					<th><?php echo $this->Html->link('rosbridge Server', array('controller' => 'rosbridges')); ?></th>
					<th>Created</th>
					<th>Modified</th>
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
						<td>
							<?php echo h($env['Environment']['id']); ?>
						</td>
						<td>
							<?php echo h($env['Environment']['name']); ?>
						</td>
						<td>
							<?php echo h($env['Rosbridge']['name']); ?></td>
						<td>
							<?php echo h($env['Mjpeg']['name']); ?>
						</td>
						<td>
							<?php echo h($env['Environment']['created']); ?>
						</td>
						<td>
							<?php echo h($env['Environment']['modified']); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>
