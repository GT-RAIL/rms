<?php
/**
 * Admin Study Index View
 *
 * The study index page displays a list of all studies in the database. An admin may edit, add, or delete from this
 * list.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Studies
 */
?>

<header class="special container">
	<span class="icon fa-gear"></span>
	<h2>User Studies</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<p>
					User studies allow controlled access to robot interfaces and environments for users. Studies can
					have multiple conditions and can log data from the users interaction with the interface.
				</p>
			</header>
			<?php echo $this->Html->link('Create New Entry', array('action' => 'add'), array('class' => 'button')); ?>
			<br /><br />
			<table>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Name</th>
					<th>Dates</th>
					<th>Length</th>
					<th>Anonymous</th>
					<th>On-the-Fly</th>
					<th>Parallel</th>
					<th>Repeatable</th>
				</tr>
				<?php foreach ($studies as $study): ?>
					<tr>
						<td>
							<?php
							echo $this->Form->postLink(
								'',
								array('action' => 'delete', $study['Study']['id']),
								array('class' => 'icon fa-trash-o', 'confirm' => 'Are you sure?')
							);
							?>
							<?php
							echo $this->Html->link(
								'',
								array('action' => 'edit', $study['Study']['id']),
								array('class' => 'icon fa-edit')
							);
							?>
						</td>
						<td data-title="ID">
							<?php echo h($study['Study']['id']); ?>
						</td>
						<td data-title="Name">
							<?php echo h($study['Study']['name']); ?>
						</td>
						<td data-title="Dates">
							<?php echo __('%s - %s', h($study['Study']['start']), h($study['Study']['end'])); ?>
						</td>
						<td data-title="Length">
							<?php
								if ($study['Study']['length'] > 0) {
									echo __('%d min.', h($study['Study']['length']));
								} else {
									echo '&infin;';
								}
							?>
						</td>
						<td data-title="Anonymous">
							<?php echo ($study['Study']['anonymous']) ? 'Yes' : 'No'; ?>
						</td>
						<td data-title="On-the-Fly">
							<?php echo ($study['Study']['otf']) ? 'Yes' : 'No'; ?>
						</td>
						<td data-title="Parallel">
							<?php echo ($study['Study']['parallel']) ? 'Yes' : 'No'; ?>
						</td>
						<td data-title="Repeatable">
							<?php echo ($study['Study']['repeatable']) ? 'Yes' : 'No'; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>
