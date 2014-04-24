<header class="special container">
	<span class="icon fa-user"></span>
	<h2><?php echo h($user['User']['fname'].' '.$user['User']['lname']); ?></h2>
</header>

<section class="wrapper style4 container">
	<div class="content">
		<section>
			Email: <?php echo h($user['User']['email']); ?>
		</section>
	</div>
</section>