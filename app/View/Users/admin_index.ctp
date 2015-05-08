<?php
/**
 * Admin User Index View
 *
 * The user index page displays a list of all users in the database. An admin may edit, add, or delete from this list
 * as well as grant/revoke privileges.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Users
 */
?>

<header class="special container">
	<span class="icon fa-gear"></span>
	<h2>Users</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<p>Users come in two forms: admin and basic. Users who sign up on their own are basic users.</p>
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
					<th>Username</th>
					<th>Role</th>
					<th>Email</th>
					<th>Name</th>
					<th>Logins</th>
					<th>Modified</th>
				</tr>
				<?php foreach ($users as $user): ?>
					<tr>
						<td>
							<?php
							if($user['User']['id'] !== AuthComponent::user('id')) {
								echo $this->Form->postLink(
									'',
									array('action' => 'delete', $user['User']['id']),
									array('class' => 'icon fa-trash-o', 'confirm' => 'Are you sure?')
								);
							} else {
								echo '<span class="icon fa-circle-o"></span>';
							}
							?>
							<?php
							echo $this->Html->link(
								'',
								array('action' => 'edit', $user['User']['id']),
								array('class' => 'icon fa-edit')
							);
							?>
							<?php
							echo $this->Html->link(
								'',
								array('action' => 'message', $user['User']['id']),
								array('class' => 'icon fa-envelope-o')
							);
							?>
						</td>
						<td data-title="ID">
							<?php echo h($user['User']['id']); ?>
						</td>
						<td data-title="Username">
							<?php echo h($user['User']['username']); ?>
						</td>
						<td data-title="Role">
						<?php
							if($user['Role']['name'] === 'admin' && $user['User']['id'] !== AuthComponent::user('id')) {
								echo $this->Form->postLink(
									'',
									array('action' => 'revoke', $user['User']['id']),
									array('class' => 'icon fa-arrow-circle-down')
								);
							} else if($user['Role']['name'] === 'basic') {
								echo $this->Form->postLink(
									'',
									array('action' => 'grant', $user['User']['id']),
									array('class' => 'icon fa-arrow-circle-up')
								);
							} else {
								echo '<span class="icon fa-circle-o"></span>';
							}
							echo __(' %s', ucfirst(h($user['Role']['name'])));
							?>
						</td>
						<td data-title="Email">
							<?php echo h($user['User']['email']); ?>
						</td>
						<td data-title="Name">
							<?php echo __('%s %s', h($user['User']['fname']), h($user['User']['lname'])); ?>
						</td>
						<td data-title="Logins">
							<?php echo h($user['User']['logins']); ?>
						</td>
						<td data-title="Modified">
							<?php echo h($user['User']['modified']); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
			<?php echo $this->Paginator->numbers(); ?><br />
		</section>
	</div>
</section>
