<?php
/**
 * Admin Study Condition Index View
 *
 * The study index page displays a list of all conditions in the database. An admin may edit, add, or delete from this
 * list.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.View.Conditions
 */
?>

<header class="special container">
	<span class="icon fa-gear"></span>
	<h2>Study Conditions</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<p>Study conditions contain information about the associated interface and user study.</p>
			</header>
			<?php echo $this->Html->link('Create New Entry', array('action' => 'add'), array('class' => 'button')); ?>
			<br /><br />
			<table>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Name</th>
					<th><?php echo $this->Html->link('Study', array('controller' => 'studies')); ?></th>
					<th><?php echo $this->Html->link('Interface', array('controller' => 'ifaces')); ?></th>
					<th><?php echo $this->Html->link('Slots', array('controller' => 'slots')); ?></th>
				</tr>
				<?php foreach ($conditions as $condition): ?>
					<tr>
						<td>
							<?php
							echo $this->Form->postLink(
								'',
								array('action' => 'delete', $condition['Condition']['id']),
								array('class' => 'icon fa-trash-o', 'confirm' => 'Are you sure?')
							);
							?>
							<?php
							echo $this->Html->link(
								'',
								array('action' => 'edit', $condition['Condition']['id']),
								array('class' => 'icon fa-edit')
							);
							?>
						</td>
						<td data-title="ID">
							<?php echo h($condition['Condition']['id']); ?>
						</td>
						<td data-title="Name">
							<?php echo h($condition['Condition']['name']); ?>
						</td>
						<td data-title="Study">
							<?php echo h($condition['Study']['name']); ?>
						</td>
						<td data-title="Interface">
							<?php echo h($condition['Iface']['name']); ?>
						</td>
						<td data-title="Slots">
							<?php echo count($condition['Slot']); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>
