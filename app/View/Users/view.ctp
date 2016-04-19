<?php
/**
 * User View
 *
 * The user view displays account information for the given user. This will allow the user to edit their information.
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
	<span class="icon fa-user"></span>
	<h2><?php echo __('%s %s', h($user['User']['fname']), h($user['User']['lname'])); ?></h2>
	<p><?php echo h($user['User']['email']); ?></p>
</header>

<?php if (count($appointments) > 0): ?>
	<section class="wrapper style4 container">
		<div class="content center">
			<section>
				<?php if (strtotime($appointments[0]['Slot']['start']) <=  strtotime('now') &&
					strtotime($appointments[0]['Slot']['end']) >  strtotime('now')): ?>
					<?php
					echo $this->Html->link(
						'Begin my Study Now',
						array(
							'controller' => 'appointments',
							'action' => 'begin',
							$appointments[0]['Appointment']['id']
						),
						array('class' => 'button')
					);
					?>
				<?php else: ?>
					<strong>Next Scheduled Study</strong>
					<br />
					<?php echo $this->Time->format('F jS, Y g:i A T', $appointments[0]['Slot']['start']); ?>
				<?php endif; ?>
			</section>
		</div>
	</section>
<?php endif; ?>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<div class="row">
				<?php $u = (count($ifaces) > 0) ? '4u' : '6u'; ?>
				<section class="<?php echo $u; ?>">
					<a href="#available" class="button special scrolly">Available Studies</a>
				</section>
				<section class="<?php echo $u; ?>">
					<a href="#scheduled" class="button special scrolly">Scheduled Studies</a>
				</section>
				<?php if(count($ifaces) > 0): ?>
					<section class="<?php echo $u; ?>">
						<a href="#interfaces" class="button special scrolly">Interfaces</a>
					</section>
				<?php endif; ?>
			</div>
		</section>
	</div>
</section>


<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				Use the following menu items to view and edit your account settings.
			</header>
			<br />
			<div class="row center">
				<section class="3u">
					<?php
					echo $this->Html->link(
						' Edit Information',
						array('action' => 'edit'),
						array('class' => 'icon fa-pencil')
					);
					?>
				</section>
				<section class="3u">
					<?php
					echo $this->Html->link( ' Email Subscriptions',
						array('controller' => 'subscriptions', 'action' => 'view'),
						array('class' => 'icon fa-envelope-o')
					);
					?>
				</section>
				<section class="3u">
					<?php
					echo $this->Html->link(
						' Change Password',
						array('action' => 'password'),
						array('class' => 'icon fa-lock')
					);
					?>
				</section>
				<section class="3u">
					<?php
					echo $this->Form->postLink(
						' Deactivate My Account',
						array('action' => 'delete'),
						array(
							'class' => 'icon fa-trash-o',
							'confirm' => 'Warning: You cannot undo this action. Continue?'
						)
					);
					?>
				</section>
			</div>
		</section>
	</div>
</section>

<hr />
<section id="available" class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<h2>Available User Studies</h2>
				<p>Click on a study below to sign up for a slot.</p>
			</header>
			<?php
			if(count($studies) > 0) {
				foreach ($studies as $study) {

					echo '<hr />';
					echo '<div class="row">';
					echo '<section class="5u">';
					if ($study['Study']['length'] > 0) {
						echo __(
							'<strong>%s</strong> (%d min.)', h($study['Study']['name']), h($study['Study']['length'])
						);
					} else {
						echo __('<strong>%s</strong>', h($study['Study']['name']));
					}
					echo '</section>';

					// check if we have an appointment
					$next = null;
					foreach ($allAppointments as $appointment) {
						if ($appointment['Slot']['Condition']['Study']['id'] === $study['Study']['id']) {
							// pick the latest
							if(!$next || strtotime($appointment['Slot']['start']) > strtotime($next['Slot']['start'])) {
								$next = $appointment;
							}
						}
					}

					// find slots with no appointment
					$free = array();
					foreach ($study['Condition'] as $condition) {
						foreach ($condition['Slot'] as $slot) {
							if (!isset($slot['Appointment']['id'])
								&& strtotime($slot['start']) >  strtotime('now')) {
								$free[$slot['id']] = $this->Time->format('F jS, Y g:i A T', $slot['start']);
							}
						}
					}

					if ($next && strtotime($next['Slot']['end']) > strtotime('now')) {
						echo '<section class="6u">';
						echo '<strong>Next Appointment:</strong> ';
						echo $this->Time->format('F jS, Y g:i A T', $next['Slot']['start']);
						echo '</section>';
					} else if ($next && !$study['Study']['repeatable']) {
						echo '<section class="6u">';
						echo '<strong>You Have Completed This Study</strong> ';
						echo '</section>';
					} else if (count($free) > 0) {
						echo '<section class="5u">';
						echo $this->Form->create('Appointment', array('action' => 'book'));
						echo $this->Form->input(
							'slot_id', array('label' => 'Time Slot: ', 'options' => $free)
						);
						echo '</section>';
						echo '<section class="2u">';
						echo $this->Form->end(array('label' => 'Book', 'class' => 'button small'));
						echo '</section>';
					} else if (!$study['Study']['otf']) {
						echo '<section class="6u">';
						echo '<strong>No Free Reservation Slots Available</strong>';
						echo '</section>';
					}
					echo '</div>';

					// check for on-the-fly creation
					if ($study['Study']['otf']) {
						// check for if the robot is free
						$available = true;
						// check for parallel sessions
						if (!$study['Study']['parallel']) {
							foreach ($study['Condition'] as $condition) {
								foreach ($condition['Slot'] as $slot) {
									if (strtotime($slot['start']) >  strtotime('now')) {
										$free[$slot['id']] = $slot['start'];
									}
								}
							}
						}
						if ($available) {
							echo '<div class="row">';
							echo '<section class="12u">';
								echo $this->Html->link(
									'Begin this Study Now',
									array('controller' => 'studies', 'action' => 'otf', $study['Study']['id']),
									array('class' => 'button')
								);
							echo '</section>';
							echo '</div>';
						}
					}
					echo '<hr />';
				}
			} else {
				echo '<h2>No Studies are Available at this Time</h2>';
			}
			?>
		</section>
	</div>
</section>
<hr />
<section id="scheduled" class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<h2>Scheduled User Studies</h2>
				<p>The list below indicates the studies you are scheduled for.</p>
			</header>
			<?php if (count($appointments) > 0): ?>
				<?php foreach($appointments as $appointment): ?>
					<div class="row">
						<section class="6u">
							<strong><?php echo $appointment['Slot']['Condition']['Study']['name']; ?></strong>
							<?php if ($appointment['Slot']['Condition']['Study']['length'] > 0): ?>
								(<?php echo $appointment['Slot']['Condition']['Study']['length']; ?> Minute Study)
							<?php endif; ?>
						</section>
						<?php if (strtotime($appointment['Slot']['start']) <=  strtotime('now') &&
							strtotime($appointment['Slot']['end']) >  strtotime('now')): ?>
							<section class="6u">
								<?php
								echo $this->Html->link(
									'Begin this Study Now',
									array(
										'controller' => 'appointments',
										'action' => 'begin',
										$appointment['Appointment']['id']
									),
									array('class' => 'button special')
								);
								?>
							</section>
						<?php else: ?>
							<section class="6u">
								<?php echo $this->Time->format('F jS, Y g:i A T', $appointment['Slot']['start']); ?>
								<?php
								echo $this->Form->postLink(
									'Cancel',
									array(
										'controller' => 'appointments',
										'action' => 'delete',
										$appointment['Appointment']['id']
									),
									array('class' => 'icon fa-trash-o', 'confirm' => 'Are you sure?')
								);
								?>
							</section>
						<?php endif; ?>
					</div>
					<hr />
				<?php endforeach; ?>
			<?php else: ?>
				<h2>You Currently Have No Pending Studies</h2>
			<?php endif; ?>
		</section>
	</div>
</section>

<?php if(count($ifaces) > 0): ?>
	<hr />
	<section id="interfaces" class="wrapper style4 container">
		<div class="content center">
			<section>
				<header>
					<h2>Interface Menu</h2>
					<p>Click an environment name to view the interface.</p>
				</header>
				<br /><hr />
				<?php foreach ($ifaces as $iface): ?>
					<div class="row center">
						<section class="12u">
							<strong><u><?php echo h($iface['Iface']['name']); ?></u></strong>
						</section>
						<?php foreach ($iface['Environment'] as $environment): ?>
							<section class="4u">
								<?php
								echo $this->Html->link(
										$environment['name'],
										array(
											'controller' => 'ifaces',
											'action' => 'view',
											$iface['Iface']['id'],
											$environment['id']
										)
									);
								?>
							</section>
							<section class="4u">
								<strong>rosbridge Status:</strong>
								<?php if (!isset($environment['Rosbridge']['id'])): ?>
									N/A
								<?php else: ?>
									<?php
									echo $this->Rms->rosbridgeStatus(
										$environment['Rosbridge']['Protocol']['name'],
										$environment['Rosbridge']['host'],
										$environment['Rosbridge']['port']
									);
									?>
								<?php endif; ?>
							</section>
							<section class="4u">
								<strong>MJPEG Status:</strong>
								<?php if (!isset($environment['Mjpeg']['id'])): ?>
									N/A
								<?php else: ?>
									<?php
									echo $this->Rms->mjpegServerStatus(
										$environment['Mjpeg']['host'],
										$environment['Mjpeg']['port']
									);
									?>
									<br />
								<?php endif; ?>
							</section>
						<?php endforeach; ?>
					</div>
					<hr />
				<?php endforeach; ?>
			</section>
		</div>
	</section>
<?php endif; ?>
