<header class="special container">
	<span class="icon fa-lock"></span>
	<h2>Sign In</h2>
</header>

<section class="wrapper style4 container">
	<div class="content">
		<section>
			<header>
				<h3>Please Enter Your Username and Password</h3>
			</header>
			<?php echo $this->Session->flash('auth'); ?>
			<?php
			echo $this->Form->create('User');
			echo $this->Form->input('username');
			echo $this->Form->input('password');
			echo $this->Form->end('Login');
			?>
			<br />
			<p>Don't have an account? Click
				<?php echo $this->Html->link('here', array('action' => 'signup')); ?> to get started.</p>
		</section>
	</div>
</section>