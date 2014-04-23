<header class="special container">
	<span class="icon fa-users"></span>
	<h2>Sign Up</h2>
</header>

<section class="wrapper style4 container">
	<div class="content">
		<section>
			<header>
				<h3>Please Fill Out the Following Form</h3>
			</header>
			<?php
			echo $this->Form->create('User');
			echo $this->Form->input('username');
			echo $this->Form->input('password');
			echo $this->Form->input('email');
			echo $this->Form->input('fname', array('label' => 'First Name'));
			echo $this->Form->input('lname', array('label' => 'Last Name'));
			echo $this->Form->end('Sign Up');
			?>
		</section>
	</div>
</section>