<?php
// check if this is the homepage
$home = isset($home) && $home;

// check if a user is logged in
$loggedIn = AuthComponent::user('id') !== null;
if($loggedIn) {
	$title = __(AuthComponent::user('fname').' '.AuthComponent::user('lname'));
}
?>

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
			<?php if ($loggedIn): ?>
				<li>
					<?php
					echo $this->Html->link(
						'Account',
						array(
							'controller' => 'users',
							'action' => 'view',
							AuthComponent::user('id')
						)
					);
					?>
				</li>
				<li>
					<?php
					echo $this->Html->link(
						'Log Out',
						array(
							'controller' => 'users',
							'action' => 'logout'
						),
						array(
							'class' => 'button special'
						)
					);
					?>
				</li>
			<?php else: ?>
				<li>
					<?php
					echo $this->Html->link(
						'Sign Up',
						array(
							'controller' => 'users',
							'action' => 'signup'
						),
						array(
							'class' => 'button special'
						)
					);
					?>
				</li>
			<?php endif; ?>
		</ul>
	</nav>
</header>