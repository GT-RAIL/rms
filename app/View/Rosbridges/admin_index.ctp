<?php
/**
 * Admin rosbridge Index View
 *
 * The rosbridge index page displays a list of all rosbridge servers in the database. An admin may edit, add, or delete
 * from this list.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.View.Rosbridges
 */
?>

<header class="special container">
	<span class="icon fa-gear"></span>
	<h2>rosbridge Servers</h2>
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
					<th>rosauth Key</th>
					<th>Modified</th>
				</tr>
				<?php foreach ($rosbridges as $rosbridge): ?>
					<tr>
						<td>
							<?php
							echo $this->Form->postLink(
								'',
								array('action' => 'delete', $rosbridge['Rosbridge']['id']),
								array('class' => 'icon fa-trash-o', 'confirm' => 'Are you sure?')
							);
							?>
							<?php
							echo $this->Html->link(
								'',
								array('action' => 'edit', $rosbridge['Rosbridge']['id']),
								array('class' => 'icon fa-edit')
							);
							?>
						</td>
						<td>
							<?php echo h($rosbridge['Rosbridge']['id']); ?>
						</td>
						<td>
							<?php echo h($rosbridge['Rosbridge']['name']); ?>
						</td>
						<td>
							<?php
							echo __(
								'%s://%s:%s',
								h($rosbridge['Protocol']['name']),
								h($rosbridge['Rosbridge']['host']),
								h($rosbridge['Rosbridge']['port']));
							?>
						</td>
						<td>
							<?php
							echo ($rosbridge['Rosbridge']['status']) ?
								'<span class="icon green fa-thumbs-o-up"></span>' :
									'<span class="icon red fa-thumbs-o-down"></span>';
							?>
						</td>
						<td>
							<?php
							echo ($rosbridge['Rosbridge']['rosauth']) ? h($rosbridge['Rosbridge']['rosauth']) : 'N/A';
							?>
						</td>
						<td>
							<?php echo h($rosbridge['Rosbridge']['modified']); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>
