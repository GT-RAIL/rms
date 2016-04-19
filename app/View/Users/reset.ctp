<?php
/**
 * Password Reset View
 *
 * The password reset view will allow a user to reset their password if they forgot.
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
	<span class="icon fa-unlock"></span>
	<h2>Request New Password</h2>
</header>

<section class="wrapper style4 container">
	<?php if($setting['Setting']['email']): ?>
		<div class="content">
			<section>
				<header>
					<h3>Please Enter Your Username</h3>
				</header>
				<?php echo $this->Form->create('User'); ?>
				<div class="row">
					<section class="12u">
						<?php echo $this->Form->input('username'); ?>
					</section>
				</div>
				<div class="row">
					<section class="12u">
						<?php echo $this->Form->end(array('label' => 'Send Password', 'class' => 'button special')); ?>
					</section>
				</div>
			</section>
		</div>
	<?php else: ?>
		<div class="content center">
			<p>
				Unfortunately, this instance of the <strong>Robot Management System</strong> does not have email
				settings enabled. Email settings must be enabled in order to send automated emails for messages such as
				username and password reminders. Please contact a site administrator directly for further assistance.
			</p>
		</div>
	<?php endif; ?>
</section>
