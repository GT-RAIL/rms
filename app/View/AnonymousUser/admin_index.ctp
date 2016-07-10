<?php
/**
 * Admin User Index View
 *
 * The add page will allow an admin to create a new user manually.
 *
 * @author		Carl Saldanha - csaldanha3@gatech.edu
 * @copyright	2016 Georgia Institute Of Technology
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.AnonymousUsers
 */
?>

<header class="special container">
	<span class="icon fa-gear"></span>
	<h2>Anonymous Users</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<p>Tokens are a way of having anonymous users use the system. We add a collection of tokens in and anonymous users can come in and use them while we record which have been used</p>
			</header>
			<?php echo $this->Html->link('Create New Entry', array('action' => 'add'), array('class' => 'button')); ?>
			<?php if ($this->Paginator->hasPrev() || $this->Paginator->hasNext()): ?>
				<br /><br />
			<?php endif; ?>
			<?php echo $this->Paginator->numbers(); ?>
			<br /><br />
			<table>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Token</th>
					<th>Used?</th>
					<th>Modified</th>
				</tr>
				<?php foreach ($anonymoususers as $user): ?>
					<tr>
						<td>
							<?php

								echo $this->Form->postLink(
									'',
									array('action' => 'delete', $user['AnonymousUser']['id']),
									array('class' => 'icon fa-trash-o', 'confirm' => 'Are you sure?')
								);
							
							?>
							<?php
							echo $this->Html->link(
								'',
								array('action' => 'edit', $user['AnonymousUser']['id']),
								array('class' => 'icon fa-edit')
							);
							?>
							<?php
							echo $this->Html->link(
								'',
								array('action' => 'message', $user['AnonymousUser']['id']),
								array('class' => 'icon fa-envelope-o')
							);
							?>
						</td>
						<td data-title="ID">
							<?php echo h($user['AnonymousUser']['id']); ?>
						</td>
						<td data-title="Token">
							<?php echo h($user['AnonymousUser']['token']); ?>
						</td>
						<td data-title="Used">
							<?php echo h($user['AnonymousUser']['used']); ?>
						</td>
						<td data-title="Modified">
							<?php echo h($user['AnonymousUser']['modified']); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
			<?php echo $this->Paginator->numbers(); ?><br />
		</section>
	</div>
</section>
