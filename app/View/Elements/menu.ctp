<?php
/**
 * Default Menu
 *
 * The main menu contains links to all pages and a sign in button. If a user is logged in, a user menu and logout button
 * is displayed as well.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Elements
 */
?>


<?php $home = isset($home) && $home; ?>

<header id="header" class="<?php echo ($home) ? 'alt' : ''; ?>">
	<h1 id="logo">
		<?php
		echo $this->Html->image(
			array('admin' => false, 'controller' =>'settings', 'action' => 'logo'),
			array('alt' => 'rms', 'url' => '/')
		);
		?>
	</h1>
	<nav id="nav">
		<ul>
			<li class="current"><?php echo $this->Html->link('Home', '/'); ?></li>
			<li class="submenu">
				<a href="">Menu</a>
				<ul>
					<?php foreach($menu as $m): ?>
						<li>
							<?php echo $this->Html->link($m['title'], $m['url']);?>
						</li>
					<?php endforeach; ?>
				</ul>
			</li>
			<?php if ($loggedIn): ?>
				<?php if ($admin): ?>
					<li class="submenu">
						<a href="">Admin</a>
						<ul>
							<?php foreach($adminMenu as $am): ?>
								<?php if (isset($am['menu'])): ?>
									<li class="submenu">
										<?php echo $this->Html->link($am['title'], $am['url']); ?>
										<ul>
											<?php foreach($am['menu'] as $m): ?>
												<li>
													<?php echo $this->Html->link($m['title'], $m['url']);?>
												</li>
											<?php endforeach; ?>
										</ul>
									</li>
								<?php else: ?>
									<li>
										<?php echo $this->Html->link($am['title'], $am['url']); ?>
									</li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</li>
				<?php endif; ?>
				<li>
					<?php
					echo $this->Html->link(
						'My Account',
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
						'Sign In',
						array('controller' => 'users', 'action' => 'login'),
						array('class' => 'button special')
					);
					?>
				</li>
			<?php endif; ?>
		</ul>
	</nav>
</header>
