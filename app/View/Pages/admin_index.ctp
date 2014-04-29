<?php
/**
 * Admin Page Index View
 *
 * The page index page displays a list of all pages in the database. An admin may edit, add, or delete from this list
 * as well as re-index the pages.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.View.Pages
 */
?>

<header class="special container">
	<span class="icon fa-gear"></span>
	<h2>Pages</h2>
</header>

<section class="wrapper style4 container">
	<div class="content">
		<section>
			<?php echo $this->Html->link('Create New Entry', array('action' => 'add')); ?>
			<br /><br />
			<table>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Title</th>
					<th>Menu</th>
					<th><?php echo $this->Html->link('Articles', array('controller' => 'articles')); ?></th>
					<th>Created</th>
					<th>Modified</th>
				</tr>
				<?php foreach ($pages as $page): ?>
					<tr>
						<td class="center">
							<?php
							echo $this->Form->postLink(
								'',
								array('action' => 'delete', $page['Page']['id']),
								array('class' => 'icon fa-trash-o', 'confirm' => 'Are you sure?')
							);
							?>
							<?php
							echo $this->Html->link(
								'',
								array('action' => 'edit', $page['Page']['id']),
								array('class' => 'icon fa-edit')
							);
							?>
							<?php
							if($page['Page']['index'] > 0) {
								echo $this->Form->postLink(
									'',
									array('action' => 'decrementIndex', $page['Page']['id']),
									array('class' => 'icon fa-arrow-circle-up')
								);
							} else {
								echo '<span class="icon fa-circle-o"></span>';
							}
							?>
							<?php
							if($page['Page']['index'] < count($pages) - 1) {
								echo $this->Form->postLink(
									'',
									array('action' => 'incrementIndex', $page['Page']['id']),
									array('class' => 'icon fa-arrow-circle-down')
								);
							} else {
								echo '<span class="icon fa-circle-o"></span>';
							}
							?>
						</td>
						<td><?php echo h($page['Page']['id']); ?></td>
						<td>
							<?php
							echo $this->Html->link(
								h($page['Page']['title']),
								array('admin' => false, 'action' => 'view', $page['Page']['id'])
							);
							?>
						</td>
						<td>
							<?php echo h($page['Page']['menu']); ?>
						</td>
						<td>
							<?php echo __('%d', count($page['Article'])); ?>
						</td>
						<td>
							<?php echo h($page['Page']['created']); ?>
						</td>
						<td>
							<?php echo h($page['Page']['modified']); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>
