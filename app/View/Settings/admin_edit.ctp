<?php
/**
 * Admin Settings Edit View
 *
 * The settings edit page allows an admin to edit site settings.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Settings
 */
?>

<header class="special container">
	<span class="icon fa-edit"></span>
	<h2>Edit Site Settings</h2>
</header>

<section class="wrapper style4 container">
	<div class="content">
		<section>
			<header>
				<h3>Enter New Site Settings Below</h3>
			</header>
			<?php
			echo $this->Form->create('Setting');
			echo $this->Form->input('id', array('type' => 'hidden'));
			?>
			<div class="row">
				<section class="6u">
					<?php echo $this->Form->input('title'); ?>
					<?php echo $this->Form->input('analytics', array('label' => 'Google Analytics ID (optional)')); ?>
				</section>
				<section class="6u">
					<?php echo $this->Form->input('copyright'); ?>
					<br />
					<?php echo $this->Form->input('email', array('label' => 'Emails Enabled', 'type' => 'checkbox')); ?>
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
