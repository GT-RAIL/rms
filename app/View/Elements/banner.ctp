<?php
/**
 * Default Homepage Banner
 *
 * The banner contains the site title and sign up links.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Elements
 */
?>

<section id="banner">
	<div class="inner">
		<header>
			<h2><?php echo h($setting['Setting']['title']); ?></h2>
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
							'Sign Up',
							array('controller' => 'users', 'action' => 'signup'),
							array('class' => 'button fit special')
						);
						?>
					</li>
				<?php endif; ?>
			</ul>
		</footer>
	</div>
</section>
