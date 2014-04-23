<?php
// check if this is the homepage
$home = isset($home) && $home;
$title = 'Test Title';
?>

<!DOCTYPE html>
<html>
<?php echo $this->element('head'); ?>
<body class="<?php echo ($home) ? 'index' : 'no-sidebar'; ?> loading">
	<?php
	echo $this->element(
		'menu',
		array(
			'home' => $home,
			'title' => $title
		)
	);
	?>

	<?php if ($home): ?>
		<section id="banner">
			<div class="inner">
				<header>
					<h2><?php echo h($title); ?></h2>
				</header>
				<p>
					Powered by the <br />
					<strong>
						<?php echo $this->Html->link('Robot Management System', 'http://wiki.ros.org/rms'); ?>
					</strong>
				</p>
				<footer>
					<ul class="buttons vertical">
						<li><a href="#main" class="button fit scrolly">Learn More</a></li>
						<?php if(!AuthComponent::user('id')): ?>
							<li>
								<?php
								echo $this->Html->link(
									'Sign In',
									array(
										'controller' => 'users',
										'action' => 'login'
									),
									array(
										'class' => 'button fit special'
									)
								);
								?>
							</li>
						<?php endif; ?>
					</ul>
				</footer>
			</div>
		</section>
	<?php endif; ?>

	<article id="main">
		<?php echo $this->Session->flash(); ?>
		<?php echo $this->fetch('content'); ?>
	</article>

	<?php echo $this->element('footer'); ?>
</body>
</html>
