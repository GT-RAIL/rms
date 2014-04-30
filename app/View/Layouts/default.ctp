<?php
/**
 * Default Layout
 *
 * The main, default layout uses the RMS template to create a new page with a menu, content section, and footer.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.View.Layouts
 */
?>

<?php
// check if this is the homepage
$home = isset($home) && $home;
?>

<!DOCTYPE html>
<html>
<?php echo $this->element('head'); ?>
<body class="<?php echo ($home) ? 'index' : 'no-sidebar'; ?> loading">
	<?php echo $this->element('menu', array('home' => $home)); ?>

	<?php if ($home): ?>
		<section id="banner">
			<div class="inner">
				<header>
					<h2><?php echo h($setting['title']); ?></h2>
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

		<?php  if($this->Session->check('Message.flash')): ?>
			<section class="flash">
				<p><?php echo $this->Session->flash(); ?></p>
			</section>
		<?php endif; ?>

		<?php echo $this->fetch('content'); ?>
	</article>

	<?php echo $this->element('footer'); ?>
</body>
</html>
