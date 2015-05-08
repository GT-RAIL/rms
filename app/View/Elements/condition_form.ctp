<?php
/**
 * Condition Add/Edit Form
 *
 * The main add/edit condition form. Passing in 'true' as a parameter called 'edit' will result in a hidden ID field
 * allowing the user to edit an existing condition.
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
				<h3>Enter the Study Condition Information Below</h3>
			</header>
			<?php
			echo $this->Form->create('Condition');
			if (isset($edit) && $edit) {
				echo $this->Form->input('id', array('type' => 'hidden'));
			}
			?>
			<div class="row">
				<section class="6u">
					<?php echo $this->Form->input('name'); ?>
				</section>
				<section class="6u">
					<?php echo $this->Form->input('study_id', array('label' => 'Study<br />')); ?>
				</section>
			</div>
			<div class="row">
				<section class="4u">
					<?php echo $this->Form->input('iface_id', array('label' => 'Interface<br />')); ?>
				</section>
				<section class="6u">
					<?php echo $this->Form->input('environment_id', array('label' => 'Environment<br />')); ?>
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
