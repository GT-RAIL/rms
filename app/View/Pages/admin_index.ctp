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
					<th>ID</th>
					<th>Title</th>
					<th>Menu</th>
					<th>Articles</th>
					<th>Actions</th>
					<th>Index</th>
					<th>Created</th>
					<th>Modified</th>
				</tr>
				<?php foreach ($pages as $page): ?>
					<tr>
						<td><?php echo $page['Page']['id']; ?></td>
						<td>
							<?php
							echo $this->Html->link(
								$page['Page']['title'],
								array('admin' => false, 'action' => 'view', $page['Page']['id'])
							);
							?>
						</td>
						<td>
							<?php echo $page['Page']['menu']; ?>
						</td>
						<td>
							<?php echo count($page['Article']); ?>
						</td>
						<td>
							<?php
							echo $this->Form->postLink(
								'Delete',
								array('action' => 'delete', $page['Page']['id']),
								array('confirm' => 'Are you sure?')
							);
							?>
							<?php
							echo $this->Html->link(
								'Edit', array('action' => 'edit', $page['Page']['id'])
							);
							?>
						</td>
						<td>
							<?php echo $page['Page']['index']; ?>
							<?php
							echo $this->Form->postLink(
								'',
								array('action' => 'decrementIndex', $page['Page']['id']),
								array('class' => 'icon fa-arrow-circle-up')
							);
							?>
							<?php
							echo $this->Form->postLink(
								'',
								array('action' => 'incrementIndex', $page['Page']['id']),
								array('class' => 'icon fa-arrow-circle-down')
							);
							?>
						</td>
						<td>
							<?php echo $page['Page']['created']; ?>
						</td>
						<td>
							<?php echo $page['Page']['modified']; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</section>