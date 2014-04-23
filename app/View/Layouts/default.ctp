<?php
$home = isset($home) && $home;
$title = 'Test Title';
?>

<!DOCTYPE html>
<html>
<?php echo $this->element('head'); ?>
<body class="<?php echo ($home) ? 'index' : 'no-sidebar'; ?> loading">
	<?php echo $this->Session->flash(); ?>

	<header id="header" class="<?php echo ($home) ? 'alt' : ''; ?>">
		<h1 id="logo"><?php echo $this->Html->link($title, '/'); ?></h1>
		<nav id="nav">
			<ul>
				<li class="current"><?php echo $this->Html->link('Home', '/'); ?></li>
				<li class="submenu">
					<a href="">Menu</a>
					<ul>
						<?php foreach($pages as $page): ?>
							<li>
								<?php
								echo $this->Html->link(
									$page['Page']['menu'],
									array(
										'controller' => 'pages',
										'action' => 'view',
										$page['Page']['id']
									)
								);
								?>
							</li>
						<?php endforeach; ?>
					</ul>
				</li>
				<li><a href="#" class="button special">Sign Up</a></li>
			</ul>
		</nav>
	</header>

	<?php if ($home):?>
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
					</ul>
				</footer>
			</div>
		</section>
	<?php endif; ?>

	<article id="main">
		<?php echo $this->fetch('content'); ?>
	</article>

	<?php echo $this->element('footer'); ?>
</body>
</html>
