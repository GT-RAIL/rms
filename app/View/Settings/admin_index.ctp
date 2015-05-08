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
 * @version		2.0.9
 * @package		app.View.Settings
 */
?>

<header class="special container">
	<span class="icon fa-gear"></span>
	<h2>Site Settings</h2>
	<p>RMS Version <?php echo h($setting['Setting']['version']); ?></p>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<p>
					Site settings can be edited to change things such as the title or logo for the site. Email settings
					can also be enabled from this page.
				</p>
			</header>
			<?php echo $this->Html->link('Edit Settings', array('action' => 'edit'), array('class' => 'button')); ?>
			<br /><br />
			<div class="row">
				<section class="3u">
					<strong><u>Site Title</u></strong>
					<br />
					<?php echo h($setting['Setting']['title']); ?>
				</section>
				<section class="4u">
					<strong><u>Copyright Message</u></strong>
					<br />
					<?php echo h($setting['Setting']['copyright']); ?>
				</section>
				<section class="3u">
					<strong>
						<?php echo $this->Html->link('Google Analytics', 'http://www.google.com/analytics/'); ?>
					</strong>
					<br />
					<?php echo ($setting['Setting']['analytics']) ? h($setting['Setting']['analytics']) : 'N/A'; ?>
				</section>
				<section class="2u">
					<strong>
						<?php
						echo $this->Html->link('Email Settings', array('controller' => 'emails', 'action' => 'index'));
						?>
					</strong>
					<br />
					<?php echo ($setting['Setting']['email']) ? 'Enabled' : 'Disabled'; ?>
				</section>
			</div>
			<div class="row">
				<section class="12u" id="logo-preview">
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
						' Upload',
						array('action' => 'uploadLogo'),
						array('class' => 'icon fa-upload button small')
					);
					?>
				</section>
			</div>
		</section>
	</div>
</section>
