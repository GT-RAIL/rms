<?php
/**
 * Study Add/Edit Form
 *
 * The main add/edit study form. Passing in 'true' as a parameter called 'edit' will result in a hidden ID field
 * allowing the user to edit an existing study.
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
				<h3>Enter the User Study Information Below</h3>
			</header>
			<?php
			echo $this->Form->create('Study');
			if (isset($edit) && $edit) {
				echo $this->Form->input('id', array('type' => 'hidden'));
			}
			?>
			<div class="row">
				<section class="8u">
					<?php echo $this->Form->input('name'); ?>
				</section>
				<section class="4u">
					<?php echo $this->Form->input('start', array('label' => 'Start Date<br />')); ?>
				</section>
			</div>
			<div class="row">
				<section class="4u">
					<?php echo $this->Form->input('length', array('label' => 'Session Length (minutes) 0=&infin;')); ?>
				</section>
				<section class="4u">
					<?php echo $this->Form->input('anonymous', array('label' => 'Anonymous Access')); ?>
					<?php echo $this->Form->input('otf', array('label' => 'On-the-Fly Sessions')); ?>
					<?php echo $this->Form->input('parallel', array('label' => 'Parallel Sessions Allowed')); ?>
					<?php echo $this->Form->input('repeatable', array('label' => 'Repeatable')); ?>
				</section>
				<section class="4u">
					<?php echo $this->Form->input('end', array('label' => 'End Date<br />')); ?>
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
