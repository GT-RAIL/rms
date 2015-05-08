<?php
/**
 * Admin Global Index View
 *
 * The global settings index view displays menu information for the relevant controllers.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Global
 */
?>

<header class="special container">
	<span class="icon fa-gear"></span>
	<h2>Site Content</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<p>Choose from the following options to edit global site settings.</p>
			</header>
			<ul class="buttons">
			<div class="row">
				<section class="6u">
					<?php
					echo $this->Html->link(
						'Site Settings',
						array('controller' => 'settings', 'action' => 'index'),
						array('class' => 'button special')
					);
					?>
				</section>
				<section class="6u">
					<?php
					echo $this->Html->link(
						'Email Settings',
						array('controller' => 'emails', 'action' => 'index'),
						array('class' => 'button special')
					);
					?>
				</section>
			</div>
		</section>
	</div>
</section>
