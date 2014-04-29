<?php
/**
 * Default Menu
 *
 * The main menu contains links to all pages and a sign up button. If a user is logged in, a user menu and logout button
 * is displayed as well.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.View.Elements
 */
?>


<?php
// check if this is the homepage
$home = isset($home) && $home;

// check if a user is logged in
$loggedIn = AuthComponent::user('id') !== null;
?>

<header id="header" class="<?php echo ($home) ? 'alt' : ''; ?>">
	<h1 id="logo"><?php echo $this->Html->image('/img/logo.png', array('alt' => 'rms')); ?></h1>
	<nav id="nav">
		<ul>
			<li class="current"><?php echo $this->Html->link('Home', '/'); ?></li>
			<li class="submenu">
				<a href="">Menu</a>
				<ul>
					<?php foreach($menu as $page): ?>
						<li>
							<?php
							echo $this->Html->link(
								$page['Page']['menu'],
								array(
									'admin' => false,
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
				<?php if ($admin): ?>
					<li class="submenu">
						<a href="">Admin</a>
						<ul>
							<li class="submenu">
								<a href="">Content</a>
								<ul>
									<li>
										<?php
										echo $this->Html->link(
											'Pages',
											array('admin' => true, 'controller' => 'pages', 'action' => 'index')
										);
										?>
									</li>
									<li>
										<?php
										echo $this->Html->link(
											'Articles',
											array('admin' => true, 'controller' => 'articles', 'action' => 'index')
										);
										?>
									</li>
								</ul>
							</li>
						</ul>
					</li>
				<?php endif; ?>
				<li>
					<?php
					echo $this->Html->link(
						'Account',
						array('admin' => false, 'controller' => 'users', 'action' => 'view')
					);
					?>
				</li>
				<li>
					<?php
					echo $this->Html->link(
						'Log Out',
						array('admin' => false, 'controller' => 'users', 'action' => 'logout'),
						array('class' => 'button special')
					);
					?>
				</li>
			<?php else: ?>
				<li>
					<?php
					echo $this->Html->link(
						'Sign Up',
						array('controller' => 'users', 'action' => 'signup'),
						array('class' => 'button special')
					);
					?>
				</li>
			<?php endif; ?>
		</ul>
	</nav>
</header>
