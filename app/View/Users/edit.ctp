<header class="special container">
	<span class="icon fa-pencil"></span>
	<h2>Edit User</h2>
</header>

<section class="wrapper style4 container">
	<div class="content">
		<section>
			<?php
			echo $this->Form->create('User');
			echo $this->Form->input('id', array('type' => 'hidden'));
			echo $this->Form->input('fname');
			echo $this->Form->input('lname');
			echo $this->Form->input('email');
			echo $this->Form->end(array('label' => 'Save', 'class' => 'button special')); ?>
		</section>
	</div>
</section>
