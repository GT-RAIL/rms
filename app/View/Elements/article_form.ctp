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
