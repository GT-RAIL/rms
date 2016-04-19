<?php
/**
 * User Change Password View
 *
 * The user change password view allows a user to change their own password.
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
	<span class="icon fa-lock"></span>
	<h2>Change Password</h2>
</header>

<section class="wrapper style4 container">
	<div class="content">
		<section>
			<header>
				<h3>Enter Your New Password Below</h3>
			</header>
			<?php
			echo $this->Form->create('User');
			echo $this->Form->input('id', array('type' => 'hidden'));
			?>
			<div class="row">
				<section class="6u">
					<?php echo $this->Form->input('password', array('value' => '')); ?>
				</section>
				<section class="6u">
					<?php
					echo $this->Form->input(
						'repass',
						array('label' => 'Password Confirmation', 'type' => 'password')
					);
					?>
				</section>
			</div>
			<div class="row">
				<section class="12u">
					<?php echo $this->Form->end(array('label' => 'Save', 'class' => 'button special')); ?>
				</section>
			</div>
		</section>
	</div>
</section>
