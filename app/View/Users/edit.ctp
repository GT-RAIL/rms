<?php
/**
 * User Edit View
 *
 * The user edit view allows a user to change their own name and email.
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
	<span class="icon fa-pencil"></span>
	<h2>Edit User</h2>
</header>

<section class="wrapper style4 container">
	<div class="content">
		<section>
			<header>
				<h3>Enter Your Updated Information Below</h3>
			</header>
			<?php
			echo $this->Form->create('User');
			echo $this->Form->input('id', array('type' => 'hidden'));
			?>
			<div class="row">
				<section class="6u">
					<?php echo $this->Form->input('fname', array('label' => 'First Name')); ?>
				</section>
				<section class="6u">
					<?php echo $this->Form->input('lname', array('label' => 'Last Name')); ?>
				</section>
			</div>
			<div class="row">
				<section class="12u">
					<?php echo $this->Form->input('email'); ?>
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
