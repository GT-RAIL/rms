<?php
/**
 * Admin Widget Index View
 *
 * The widget index page displays a list of all topics and widgets streams in the database. An admin may edit, add, or
 * delete from this list.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.View.Widget
 */
?>

<header class="special container">
	<span class="icon fa-gear"></span>
	<h2>ROS Topics and Widgets</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<p>
					ROS topics and widgets can be assigned to a particular environment.
				</p>
			</header>
			<div class="row">
				<section class="6u">
					<a href="#streams" class="button special scrolly">MJPEG Streams</a>
				</section>
				<section class="6u">
					<a href="#teleops" class="button special scrolly">Teleop Settings</a>
				</section>
			</div>
		</section>
	</div>
</section>

<section id="streams" class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<h2>MJPEG Server Streams</h2>
				<p>
					MJPEG streams correspond to ROS image topics streamed via the MJPEG Server node.
				</p>
			</header>
			<?php
			echo $this->Html->link(
				'Create New Entry',
				array('controller' => 'streams', 'action' => 'add'),
				array('class' => 'button')
			);
			?>
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
								array('controller' => 'streams', 'action' => 'delete', $stream['Stream']['id']),
								array('class' => 'icon fa-trash-o', 'confirm' => 'Are you sure?')
							);
							?>
							<?php
							echo $this->Html->link(
								'',
								array('controller' => 'streams', 'action' => 'edit', $stream['Stream']['id']),
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
								array('controller' => 'streams', 'action' => 'view', $stream['Stream']['id'])
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
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>

<section id="teleops" class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<h2>Teleoperation Settings</h2>
				<p>
					Teleoperation settings store information about a geometry_msgs/Twist topic with an optional throttle
					rate.
				</p>
			</header>
			<?php
			echo $this->Html->link(
				'Create New Entry',
				array('controller' => 'teleops', 'action' => 'add'),
				array('class' => 'button')
			);
			?>
			<br /><br />
			<table>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Topic</th>
					<th>Throttle</th>
					<th><?php echo $this->Html->link('Environment', array('controller' => 'environments')); ?></th>
				</tr>
				<?php foreach ($teleops as $teleop): ?>
					<tr>
						<td>
							<?php
							echo $this->Form->postLink(
								'',
								array('controller' => 'teleops', 'action' => 'delete', $teleop['Teleop']['id']),
								array('class' => 'icon fa-trash-o', 'confirm' => 'Are you sure?')
							);
							?>
							<?php
							echo $this->Html->link(
								'',
								array('controller' => 'teleops', 'action' => 'edit', $teleop['Teleop']['id']),
								array('class' => 'icon fa-edit')
							);
							?>
						</td>
						<td data-title="ID">
							<?php echo h($teleop['Teleop']['id']); ?>
						</td>
						<td data-title="Topic">
							<?php echo h($teleop['Teleop']['topic']); ?>
						</td>
						<td data-title="Throttle">
							<?php echo ($teleop['Teleop']['throttle']) ? h($teleop['Teleop']['throttle']) : 'N/A'; ?>
						</td>
						<td data-title="Environment">
							<?php echo h($teleop['Environment']['name']); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>
