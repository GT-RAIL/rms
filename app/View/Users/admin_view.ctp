<?php
/**
 * Admin User View
 *
 * The admin user view allows the admin to look at any user's extended information.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.View.Users
 */
?>


<header class="special container">
	<span class="icon fa-user"></span>
	<h2><?php echo __('%s %s', h($user['User']['fname']), h($user['User']['lname'])); ?></h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<div class="row">
				<section class="4u">
					<strong>Name:</strong> <?php echo h($user['User']['fname'].' '.$user['User']['lname']); ?>
					<br />
					<strong>Username:</strong> <?php echo h($user['User']['username']); ?>
				</section>
				<section class="4u">
					<strong>Email:</strong> <?php echo h($user['User']['email']); ?>
					<br />
					<strong>Role:</strong> <?php echo ucfirst(h($user['Role']['name'])); ?>
				</section>
				<section class="4u">
					<strong>Login Count:</strong> <?php echo h($user['User']['logins']); ?>
					<br />
					<strong>Last Login:</strong>
					<?php echo ($user['User']['visit']) ? h($user['User']['visit']) : 'N/A'; ?>
				</section>
			</div>
		</section>
	</div>
</section>
