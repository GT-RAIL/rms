<?php
/**
 * Sign Up View
 *
 * The sign up page will allow a new user to create an account.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Users
 */
?>

<header class="special container">
	<span class="icon fa-users"></span>
	<h2>Sign Up</h2>
</header>

<section class="wrapper style4 container">
	<div class="content">
		<section>
			<header>
				<h3>Please Fill Out the Following Form</h3>
			</header>
			<?php echo $this->Form->create('User'); ?>
			<div class="row">
				<section class="6u">
					<?php
					echo $this->Form->input('fname', array('label' => 'First Name'));
					echo $this->Form->input('lname', array('label' => 'Last Name'));
					echo $this->Form->input('email');
					?>
				</section>
				<section class="6u">
					<?php
					echo $this->Form->input('username');
					echo $this->Form->input('password');
					echo $this->Form->input(
						'repass',
						array('label' => 'Password Confirmation', 'type' => 'password')
					);
					?>
				</section>
			</div>
			<div class="row">
				<section class="12u">
					<?php echo $this->Form->end(array('label' => 'Sign Up', 'class' => 'button special')); ?>
				</section>
			</div>
			<br />
			<div class="row center">
				<section class="3u">
					Have an account?
					<br />
					Click <?php echo $this->Html->link('here', array('action' => 'login')); ?> to login.
				</section>
			</div>
		</section>
	</div>
</section>
