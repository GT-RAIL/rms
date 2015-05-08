<?php
/**
 * Article Add/Edit Form
 *
 * The main add/edit article form. Passing in 'true' as a parameter called 'edit' will result in a hidden ID field
 * allowing the user to edit an existing article.
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
				<h3>Enter the Article Information Below</h3>
			</header>
			<?php
			echo $this->Form->create('Article');
			if (isset($edit) && $edit) {
				echo $this->Form->input('id', array('type' => 'hidden'));
			}
			?>
			<div class="row">
				<section class="8u">
					<?php echo $this->Form->input('title'); ?>
				</section>
				<section class="4u">
					<?php echo $this->Form->input('page_id', array('label' => 'Page<br />', 'class' => 'button')); ?>
				</section>
			</div>
			<div class="row">
				<section class="12u">
					<?php echo $this->Form->input('content', array('label' => 'HTML Content')); ?>
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
