<?php
/**
 * Admin Article Index View
 *
 * The article index page displays a list of all articles in the database. An admin may edit, add, or delete from this
 * list as well as re-index the articles.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.View.Articles
 */
?>

<header class="special container">
	<span class="icon fa-gear"></span>
	<h2>Articles</h2>
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
					<th><?php echo $this->Html->link('Page', array('controller' => 'pages')); ?></th>
					<th>Created</th>
					<th>Modified</th>
				</tr>
				<?php foreach ($articles as $article): ?>
					<tr>
						<td class="center">
							<?php
							echo $this->Form->postLink(
								'',
								array('action' => 'delete', $article['Article']['id']),
								array('class' => 'icon fa-trash-o', 'confirm' => 'Are you sure?')
							);
							?>
							<?php
							echo $this->Html->link(
								'',
								array('action' => 'edit', $article['Article']['id']),
								array('class' => 'icon fa-edit')
							);
							?>
							<?php
							if($article['Article']['index'] > 0) {
								echo $this->Form->postLink(
									'',
									array('action' => 'decrementIndex', $article['Article']['id']),
									array('class' => 'icon fa-arrow-circle-up')
								);
							} else {
								echo '<span class="icon fa-circle-o"></span>';
							}
							?>
							<?php
							// determine the number of articles for the page
							$count = 0;
							foreach ($pages as $page) {
								if ($page['Page']['id'] === $article['Article']['page_id']) {
									$count = count($page['Article']);
									break;
								}
							}
							if($article['Article']['index'] < $count - 1) {
								echo $this->Form->postLink(
									'',
									array('action' => 'incrementIndex', $article['Article']['id']),
									array('class' => 'icon fa-arrow-circle-down')
								);
							} else {
								echo '<span class="icon fa-circle-o"></span>';
							}
							?>
						</td>
						<td><?php echo h($article['Article']['id']); ?></td>
						<td>
							<?php echo h($article['Article']['title']); ?>
						</td>
						<td>
							<?php
							echo $this->Html->link(
								h($article['Page']['title']),
								array(
									'admin' => false,
									'controller' => 'pages',
									'action' => 'view',
									$article['Article']['page_id']
								)
							);
							?>
						</td>
						<td>
							<?php echo h($article['Article']['created']); ?>
						</td>
						<td>
							<?php echo h($article['Article']['modified']); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>
