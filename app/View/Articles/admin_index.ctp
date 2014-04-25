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
					<th>ID</th>
					<th>Title</th>
					<th>Page</th>
					<th>Content</th>
					<th>Actions</th>
					<th>Index</th>
					<th>Created</th>
					<th>Modified</th>
				</tr>
				<?php foreach ($articles as $article): ?>
					<tr>
						<td><?php echo h($article['Article']['id']); ?></td>
						<td>
							<?php echo h($article['Article']['title']); ?>
						</td>
						<td>
							<?php echo h($article['Page']['title']); ?>
						</td>
						<td>
							<?php echo __('%s...', substr(h($article['Article']['content']), 0, 33)); ?>
						</td>
						<td>
							<?php
							echo $this->Form->postLink(
								'Delete',
								array('action' => 'delete', $article['Article']['id']),
								array('confirm' => 'Are you sure?')
							);
							?>
							<?php
							echo $this->Html->link(
								'Edit', array('action' => 'edit', $article['Article']['id'])
							);
							?>
						</td>
						<td>
							<?php echo $article['Article']['index']; ?>
							<?php
							echo $this->Form->postLink(
								'',
								array('action' => 'decrementIndex', $article['Article']['id']),
								array('class' => 'icon fa-arrow-circle-up')
							);
							?>
							<?php
							echo $this->Form->postLink(
								'',
								array('action' => 'incrementIndex', $article['Article']['id']),
								array('class' => 'icon fa-arrow-circle-down')
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
