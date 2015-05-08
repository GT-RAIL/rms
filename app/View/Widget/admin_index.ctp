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
 * @version		2.0.9
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
					<a href="#teleops" class="button special scrolly">Teleoperations</a>
				</section>
			</div>
			<div class="row">
				<section class="4u">
					<a href="#tfs" class="button special scrolly">TF Clients</a>
				</section>
				<section class="4u">
					<a href="#markers" class="button special scrolly">Markers</a>
				</section>
				<section class="4u">
					<a href="#ims" class="button special scrolly">Interactive Markers</a>
				</section>
			</div>
			<div class="row">
				<section class="6u">
					<a href="#urdfs" class="button special scrolly">URDFs</a>
				</section>
				<section class="6u">
					<a href="#resources" class="button special scrolly">Resource Servers</a>
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

<section id="tfs" class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<h2>TF Client Settings</h2>
				<p>
					TF client settings store information about a the fixed frame for an environment.
				</p>
			</header>
			<?php
			echo $this->Html->link(
				'Create New Entry',
				array('controller' => 'tfs', 'action' => 'add'),
				array('class' => 'button')
			);
			?>
			<br /><br />
			<table>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Frame</th>
					<th>Angular Thresh.</th>
					<th>Translational Thresh.</th>
					<th>Rate</th>
					<th><?php echo $this->Html->link('Environment', array('controller' => 'environments')); ?></th>
				</tr>
				<?php foreach ($tfs as $tf): ?>
					<tr>
						<td>
							<?php
							echo $this->Form->postLink(
								'',
								array('controller' => 'tfs', 'action' => 'delete', $tf['Tf']['id']),
								array('class' => 'icon fa-trash-o', 'confirm' => 'Are you sure?')
							);
							?>
							<?php
							echo $this->Html->link(
								'',
								array('controller' => 'tfs', 'action' => 'edit', $tf['Tf']['id']),
								array('class' => 'icon fa-edit')
							);
							?>
						</td>
						<td data-title="ID">
							<?php echo h($tf['Tf']['id']); ?>
						</td>
						<td data-title="Frame">
							<?php echo h($tf['Tf']['frame']); ?>
						</td>
						<td data-title="Angular">
							<?php echo h($tf['Tf']['angular']); ?>
						</td>
						<td data-title="Translational">
							<?php echo h($tf['Tf']['translational']); ?>
						</td>
						<td data-title="Rate">
							<?php echo h($tf['Tf']['rate']); ?>
						</td>
						<td data-title="Environment">
							<?php echo h($tf['Environment']['name']); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>

<section id="markers" class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<h2>Marker Settings</h2>
				<p>Marker settings store information about ROS 3D marker topics.</p>
			</header>
			<?php
			echo $this->Html->link(
				'Create New Entry',
				array('controller' => 'markers', 'action' => 'add'),
				array('class' => 'button')
			);
			?>
			<br /><br />
			<table>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Topic</th>
					<th><?php echo $this->Html->link('Environment', array('controller' => 'environments')); ?></th>
				</tr>
				<?php foreach ($markers as $marker): ?>
					<tr>
						<td>
							<?php
							echo $this->Form->postLink(
								'',
								array('controller' => 'markers', 'action' => 'delete', $marker['Marker']['id']),
								array('class' => 'icon fa-trash-o', 'confirm' => 'Are you sure?')
							);
							?>
							<?php
							echo $this->Html->link(
								'',
								array('controller' => 'markers', 'action' => 'edit', $marker['Marker']['id']),
								array('class' => 'icon fa-edit')
							);
							?>
						</td>
						<td data-title="ID">
							<?php echo h($marker['Marker']['id']); ?>
						</td>
						<td data-title="Topic">
							<?php
							echo $this->Html->link(
								$marker['Marker']['topic'],
								array('controller' => 'markers', 'action' => 'view', $marker['Marker']['id'])
							);
							?>
						</td>
						<td data-title="Environment">
							<?php echo h($marker['Environment']['name']); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>

<section id="ims" class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<h2>Interactive Marker Settings</h2>
				<p>Interactive marker settings store information about ROS interactive marker topics.</p>
			</header>
			<?php
			echo $this->Html->link(
				'Create New Entry',
				array('controller' => 'ims', 'action' => 'add'),
				array('class' => 'button')
			);
			?>
			<br /><br />
			<table>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Topic</th>
					<th>Collada</th>
					<th>Resources</th>
					<th><?php echo $this->Html->link('Environment', array('controller' => 'environments')); ?></th>
				</tr>
				<?php foreach ($ims as $im): ?>
					<tr>
						<td>
							<?php
							echo $this->Form->postLink(
								'',
								array('controller' => 'ims', 'action' => 'delete', $im['Im']['id']),
								array('class' => 'icon fa-trash-o', 'confirm' => 'Are you sure?')
							);
							?>
							<?php
							echo $this->Html->link(
								'',
								array('controller' => 'ims', 'action' => 'edit', $im['Im']['id']),
								array('class' => 'icon fa-edit')
							);
							?>
						</td>
						<td data-title="ID">
							<?php echo h($im['Im']['id']); ?>
						</td>
						<td data-title="Topic">
							<?php
							echo $this->Html->link(
								$im['Im']['topic'],
								array('controller' => 'ims', 'action' => 'view', $im['Im']['id'])
							);
							?>
						</td>
						<td data-title="Collada">
							<?php echo ($im['Collada']['name']) ? h($im['Collada']['name']) : 'None'; ?>
						</td>
						<td data-title="Resources">
							<?php echo ($im['Resource']['name']) ? h($im['Resource']['name']) : 'None'; ?>
						</td>
						<td data-title="Environment">
							<?php echo h($im['Environment']['name']); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>

<section id="urdfs" class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<h2>URDF Settings</h2>
				<p>URDF settings store information about ROS parameters and Collada resources.</p>
			</header>
			<?php
			echo $this->Html->link(
				'Create New Entry',
				array('controller' => 'urdfs', 'action' => 'add'),
				array('class' => 'button')
			);
			?>
			<br /><br />
			<table>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Param</th>
					<th>Collada</th>
					<th>Resources</th>
					<th><?php echo $this->Html->link('Environment', array('controller' => 'environments')); ?></th>
				</tr>
				<?php foreach ($urdfs as $urdf): ?>
					<tr>
						<td>
							<?php
							echo $this->Form->postLink(
								'',
								array('controller' => 'urdfs', 'action' => 'delete', $urdf['Urdf']['id']),
								array('class' => 'icon fa-trash-o', 'confirm' => 'Are you sure?')
							);
							?>
							<?php
							echo $this->Html->link(
								'',
								array('controller' => 'urdfs', 'action' => 'edit', $urdf['Urdf']['id']),
								array('class' => 'icon fa-edit')
							);
							?>
						</td>
						<td data-title="ID">
							<?php echo h($urdf['Urdf']['id']); ?>
						</td>
						<td data-title="Param">
							<?php
							echo $this->Html->link(
								$urdf['Urdf']['param'],
								array('controller' => 'urdfs', 'action' => 'view', $urdf['Urdf']['id'])
							);
							?>
						</td>
						<td data-title="Collada">
							<?php echo ($urdf['Collada']['name']) ? h($urdf['Collada']['name']) : 'None'; ?>
						</td>
						<td data-title="Resources">
							<?php echo ($urdf['Resource']['name']) ? h($urdf['Resource']['name']) : 'None'; ?>
						</td>
						<td data-title="Environment">
							<?php echo h($urdf['Environment']['name']); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>
<section id="resources" class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<h2>Resource Server Settings</h2>
				<p>Resource servers store information about Collada resource servers for ros3djs.</p>
			</header>
			<?php
			echo $this->Html->link(
				'Create New Entry',
				array('controller' => 'resources', 'action' => 'add'),
				array('class' => 'button')
			);
			?>
			<br /><br />
			<table>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Name</th>
					<th>Base URL</th>
				</tr>
				<?php foreach ($resources as $resource): ?>
					<tr>
						<td>
							<?php
							echo $this->Form->postLink(
								'',
								array('controller' => 'resources', 'action' => 'delete', $resource['Resource']['id']),
								array('class' => 'icon fa-trash-o', 'confirm' => 'Are you sure?')
							);
							?>
							<?php
							echo $this->Html->link(
								'',
								array('controller' => 'resources', 'action' => 'edit', $resource['Resource']['id']),
								array('class' => 'icon fa-edit')
							);
							?>
						</td>
						<td data-title="ID">
							<?php echo h($resource['Resource']['id']); ?>
						</td>
						<td data-title="Name">
							<?php echo h($resource['Resource']['name']); ?>
						</td>
						<td data-title="Base URL">
							<?php echo h($resource['Resource']['url']); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>
