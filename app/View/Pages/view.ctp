<header class="special container">
	<span class="icon fa-spinner"></span>
	<h2><?php echo h($page['Page']['title']); ?></h2>
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
	</section>
<?php endforeach; ?>