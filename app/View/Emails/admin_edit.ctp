<?php
/**
 * Admin SMTP email Settings Edit View
 *
 * The SMTP email settings edit page allows an admin to edit SMTP email settings.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Emails
 */
?>

<header class="special container">
	<span class="icon fa-edit"></span>
	<h2>Edit SMTP Email Settings</h2>
</header>

<section class="wrapper style4 container">
	<div class="content">
		<section>
			<header>
				<h3>Enter New SMTP Settings Below</h3>
				<p><strong>All Fields Are Optional</strong></p>
			</header>
			<?php
			echo $this->Form->create('Email');
			echo $this->Form->input('id', array('type' => 'hidden'));
			?>
			<div class="row">
				<section class="6u">
					<?php echo $this->Form->input('host'); ?>
					<?php echo $this->Form->input('port', array('label' => 'Port<br />')); ?>
					<?php echo $this->Form->input('username'); ?>
					<?php echo $this->Form->input('password', array('value' => $email['Email']['password'])); ?>
				</section>
				<section class="6u">
					<?php echo $this->Form->input('from'); ?>
					<?php echo $this->Form->input('alias'); ?>
					<br />
					<?php echo $this->Form->input('tls', array('label' => 'TLS Enabled', 'type' => 'checkbox')); ?>
					<br />
					<?php echo $this->Form->end(array('label' => 'Save', 'class' => 'button special')); ?>
				</section>
			</div>
		</section>
	</div>
</section>
