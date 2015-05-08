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
 * @version		2.0.9
 * @package		app.View.Pages
 */
?>

<header class="special container">
	<span class="icon fa-gear"></span>
	<h2>Pages</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<p>Content pages are publicly visible. Each page will automatically be added to the main menu. The page at
				the top of the list will become the homepage.</p>
			</header>
			<?php echo $this->Html->link('Create New Entry', array('action' => 'add'), array('class' => 'button')); ?>
			<br /><br />
			<table>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Title</th>
					<th>Menu</th>
					<th><?php echo $this->Html->link('Articles', array('controller' => 'articles')); ?></th>
					<th>Modified</th>
				</tr>
				<?php foreach ($pages as $page): ?>
					<tr>
						<td>
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
						<td data-title="ID">
							<?php echo h($page['Page']['id']); ?>
						</td>
						<td data-title="Title">
							<?php
							echo $this->Html->link(
								h($page['Page']['title']),
								array('admin' => false, 'action' => 'view', $page['Page']['id'])
							);
							?>
						</td>
						<td data-title="Menu">
							<?php echo h($page['Page']['menu']); ?>
						</td>
						<td data-title="Articles">
							<?php echo __('%d', count($page['Article'])); ?>
						</td>
						<td data-title="Modified">
							<?php echo $this->Time->format('m/d/y h:i A T', $page['Page']['modified']); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>
