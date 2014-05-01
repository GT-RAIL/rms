<?php
/**
 * Admin Settings Index View
 *
 * The settings index page displays a list of all site settings. An admin can edit these settings.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.View.Settings
 */
?>

<header class="special container">
	<span class="icon fa-gear"></span>
	<h2>Site Settings</h2>
	<p>RMS Version <?php echo h($setting['Setting']['version']); ?></p>
</header>

<section class="wrapper style4 container">
	<div class="content">
		<section>
			<div class="row">
				<section class="4u center">
					<strong><u>Site Title</u></strong>
					<br />
					<?php echo h($setting['Setting']['title']); ?>
					<?php echo $this->Html->link('', array('action' => 'edit'), array('class' => 'icon fa-edit')); ?>
				</section>
				<section class="4u center">
					<strong><u>Copyright Message</u></strong>
					<br />
					<?php echo h($setting['Setting']['copyright']); ?>
					<?php echo $this->Html->link('', array('action' => 'edit'), array('class' => 'icon fa-edit')); ?>
				</section>
				<section class="4u center">
					<strong>
						<?php echo $this->Html->link('Google Analytics', 'http://www.google.com/analytics/'); ?>
					</strong>
					<br />
					<?php echo ($setting['Setting']['analytics']) ? h($setting['Setting']['analytics']) : 'N/A'; ?>
					<?php echo $this->Html->link('', array('action' => 'edit'), array('class' => 'icon fa-edit')); ?>
				</section>
			</div>
			<div class="row">
				<section class="12u center" id="logo-preview">
					<strong><u>Site Logo</u></strong>
					<br />
					<?php
					echo $this->Html->image(
						array('admin' => false, 'action' => 'logo'),
						array('alt' => $setting['Setting']['title'])
					);
					?>
					<br />
					<?php
					echo $this->Html->link(
						'Upload ',
						array('action' => 'uploadLogo'),
						array('class' => 'icon fa-upload')
					);
					?>
				</section>
			</div>
		</section>
	</div>
</section>
