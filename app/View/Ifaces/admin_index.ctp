<?php
/**
 * Admin Interface Index View
 *
 * The interface index page displays a list of all interfaces in the database. An admin may edit, add, or delete from
 * this list as well as re-index the pages. Ifaces is used to prevent using the reserved PHP keyword interface.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Ifaces
 */
?>

<header class="special container">
	<span class="icon fa-gear"></span>
	<h2>Interfaces</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<p>
					Interfaces can be linked to a set of environments. An interface can have <strong>anonymous</strong>
					access (i.e., no login required) and/or <strong>unrestricted</strong> access (i.e., no scheduled
					session for basic users required).
				</p>
			</header>
			<?php echo $this->Html->link('Create New Entry', array('action' => 'add'), array('class' => 'button')); ?>
			<br /><br />
			<table>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Name</th>
					<th>Controller</th>
					<th>Anonymous</th>
					<th>Unrestricted</th>
					<th><?php echo $this->Html->link('Environments', array('controller' => 'environments')); ?></th>
				</tr>
				<?php foreach ($ifaces as $iface): ?>
					<tr>
						<td>
							<?php
							echo $this->Form->postLink(
								'',
								array('action' => 'delete', $iface['Iface']['id']),
								array('class' => 'icon fa-trash-o', 'confirm' => 'Are you sure?')
							);
							?>
							<?php
							echo $this->Html->link(
								'',
								array('action' => 'edit', $iface['Iface']['id']),
								array('class' => 'icon fa-edit')
							);
							?>
						</td>
						<td data-title="ID">
							<?php echo h($iface['Iface']['id']); ?>
						</td>
						<td data-title="Name">
							<?php echo h($iface['Iface']['name']); ?>
						</td>
						<td data-title="Controller">
							<?php
							echo __(
								'%sInterfaceController.php', str_replace(' ', '', ucwords(h($iface['Iface']['name'])))
							);
							?>
						</td>
						<td data-title="Anonymous">
							<?php echo ($iface['Iface']['anonymous']) ? 'Yes' : 'No'; ?>
						</td>
						<td data-title="Unrestricted">
							<?php echo ($iface['Iface']['unrestricted']) ? 'Yes' : 'No'; ?>
						</td>
						<td data-title="Environments">
							<?php echo count($iface['Environment']); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>
