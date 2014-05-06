<?php
/**
 * Login View
 *
 * The login page will allow an existing user to login.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.View.Users
 */
?>

<?php echo $this->Session->flash('auth'); ?>

<header class="special container">
	<span class="icon fa-lock"></span>
	<h2>Sign In</h2>
</header>

<section class="wrapper style4 container">
	<div class="content">
		<section>
			<header>
				<h3>Please Enter Your Username and Password</h3>
			</header>
			<?php echo $this->Form->create('User'); ?>
			<div class="row">
				<section class="6u">
					<?php echo $this->Form->input('username'); ?>
				</section>
				<section class="6u">
					<?php echo $this->Form->input('password'); ?>
				</section>
			</div>
			<div class="row">
				<section class="12u">
					<?php echo $this->Form->end(array('label' => 'Login', 'class' => 'button special')); ?>
				</section>
			</div>
			<br />
			<p>
				Don't have an account? Click
				<?php echo $this->Html->link('here', array('action' => 'signup')); ?> to get started.
				<?php if ($setting['Setting']['email']): ?>
					<br />
					<?php echo $this->Html->link('Forget Your Username?', array('action' => 'username')); ?> |
					<?php echo $this->Html->link('Forget Your Password?', array('action' => 'reset')); ?>
				<?php endif; ?>
			</p>
		</section>
	</div>
</section>
