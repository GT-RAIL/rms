<?php
/**
 * Admin Study Logs Index View
 *
 * The logs index page displays a list of all logs in the database. Further view options can be found from this view.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Logs
 */
?>

<header class="special container">
	<span class="icon fa-list"></span>
	<h2>Logs</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<?php echo $this->Form->create('Study', array('action' => 'export')); ?>
			<?php echo $this->Form->input('study_id', array('label' => 'Study: ')); ?>
			<?php echo $this->Form->end(array('label' => 'Export Logs', 'class' => 'button small')); ?>
			<br />
			<header>
				<p>Study log data is linked to a single appointment.</p>
			</header>
			<?php if ($this->Paginator->hasPrev() || $this->Paginator->hasNext()): ?>
				<br /><br />
			<?php endif; ?>
			<?php echo $this->Paginator->numbers(); ?>
			<table>
				<tr>
					<th>ID</th>
					<th><?php echo $this->Html->link('Study', array('controller' => 'studies')); ?></th>
					<th>Type</th>
					<th>Entry</th>
					<th>Created</th>
				</tr>
				<?php foreach ($logs as $log): ?>
					<tr>
						<td data-title="ID">
							<?php echo h($log['Log']['id']); ?>
						</td>
						<td data-title="Study">
							<?php echo h($log['Study']['name']); ?>
						</td>
						<td data-title="Type">
							<?php echo h($log['Type']['name']); ?>
						</td>
						<td data-title="Entry">
							<?php echo substr(h($log['Log']['entry']), 0, 64); ?>
						</td>
						<td data-title="Created">
							<?php echo $this->Time->format('m/d/y h:i A T', $log['Log']['modified']); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>
