<header class="special container">
	<span class="icon fa-user"></span>
	<h2><?php echo h($user['User']['fname'].' '.$user['User']['lname']); ?></h2>
	<p><?php echo h($user['User']['email']); ?></p>
	<p>
		<?php echo $this->Html->link('Edit Information', array('action' => 'edit')); ?> |
		<?php echo $this->Html->link('Change Password', array('action' => 'password')); ?>
	</p>
</header>
