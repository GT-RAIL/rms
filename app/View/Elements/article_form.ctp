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
 * @version		2.0.0
 * @package		app.View.Elements
 */
?>

<section class="wrapper style4 container">
	<div class="content">
		<section>
			<?php
			echo $this->Form->create('Article');
			if (isset($edit) && $edit) {
				echo $this->Form->input('id', array('type' => 'hidden'));
			}
			echo $this->Form->input('title');
			echo $this->Form->input('content');
			echo $this->Form->input('page_id');
			echo $this->Form->end(array('label' => 'Save', 'class' => 'button special')); ?>
		</section>
	</div>
</section>
