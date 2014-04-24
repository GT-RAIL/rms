<header class="special container">
	<span class="icon fa-spinner"></span>
	<h2><?php echo h($page['Page']['title']); ?></h2>
	<?php
	if($admin) {
		echo $this->Html->link('Edit', array('admin' => true, 'action' => 'edit', $page['Page']['id']));
	}
	?>
</header>

<?php foreach ($page['Article'] as $article): ?>
	<section class="wrapper style4 container">
		<div class="content">
			<section>
				<header>
					<h3><?php echo h($article['title']); ?></h3>
				</header>
				<?php echo __($article['content']); ?>
			</section>
		</div>
		<?php
		if($admin) {
			echo $this->Html->link(
				'Edit',
				array(
					'admin' => true,
					'controller' => 'articles',
					'action' => 'edit',
					$article['id']
				)
			);
		}
		?>
	</section>
<?php endforeach; ?>