<section class="wrapper style4 container">
	<div class="content">
		<section>
			<?php
			echo $this->Form->create('Page');
			if (isset($edit) && $edit) {
				echo $this->Form->input('id', array('type' => 'hidden'));
			}
			echo $this->Form->input('title');
			echo $this->Form->input('menu');
			echo $this->Form->end('Save');?>
		</section>
	</div>
</section>