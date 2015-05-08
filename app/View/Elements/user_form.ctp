<?php
/**
 * User Add/Edit Form
 *
 * The main add/edit user form. Passing in 'true' as a parameter called 'edit' will result in a hidden ID field
 * allowing the user to edit an existing user and will remove the password field.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Elements
 */
?>

<section class="wrapper style4 container">
	<div class="content">
		<section>
			<header>
				<h3>Enter the User Information Below</h3>
			</header>
			<?php
			echo $this->Form->create('User');
			if (isset($edit) && $edit) {
				echo $this->Form->input('id', array('type' => 'hidden'));
			}
			?>
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
					if (!isset($edit) || !$edit) {
						echo $this->Form->input('password');
					}
					echo $this->Form->input('role_id', array('label' => 'Role<br />', 'class' => 'button'));
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
