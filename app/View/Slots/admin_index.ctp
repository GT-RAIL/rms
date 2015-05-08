<?php
/**
 * Admin Study Session Slot Index View
 *
 * The study session slot index page displays a list of all slots in the database. An admin may edit, add, or delete
 * from this list.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Slots
 */
?>

<header class="special container">
	<span class="icon fa-gear"></span>
	<h2>Study Session Slots</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<p>Study session slots are blocks of time that a user can book for a study appointment.</p>
			</header>
			<?php echo $this->Html->link('Create New Entry', array('action' => 'add'), array('class' => 'button')); ?>
			<?php if ($this->Paginator->hasPrev() || $this->Paginator->hasNext()): ?>
				<br /><br />
			<?php endif; ?>
			<?php echo $this->Paginator->numbers(); ?>
			<br /><br />
			<table>
				<tr>
					<th></th>
					<th>ID</th>
					<th><?php echo $this->Html->link('Study', array('controller' => 'studies')); ?></th>
					<th><?php echo $this->Html->link('Condition', array('controller' => 'conditions')); ?></th>
					<th>Start Time</th>
					<th>End Time</th>
					<th>Booked</th>
				</tr>
				<?php foreach ($slots as $slot): ?>
					<tr>
						<td>
							<?php
							echo $this->Form->postLink(
								'',
								array('action' => 'delete', $slot['Slot']['id']),
								array('class' => 'icon fa-trash-o', 'confirm' => 'Are you sure?')
							);
							?>
							<?php
							echo $this->Html->link(
								'',
								array('action' => 'edit', $slot['Slot']['id']),
								array('class' => 'icon fa-edit')
							);
							?>
						</td>
						<td data-title="ID">
							<?php echo h($slot['Slot']['id']); ?>
						</td>
						<td data-title="Study / Condition">
							<?php echo h($slot['Condition']['Study']['name']); ?>
						</td>
						<td data-title="Condition">
							<?php echo h($slot['Condition']['name']); ?>
						</td>
						<td data-title="Time">
							<?php echo $this->Time->format('m/d/y h:i A T', $slot['Slot']['start']); ?>
						</td>
						<td data-title="Time">
							<?php echo $this->Time->format('m/d/y h:i A T', $slot['Slot']['end']); ?>
						</td>
						<td data-title="Booked">
							<?php echo isset($slot['Appointment']['id']) ? 'Yes' : 'No'; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>
