<?php
/**
 * Admin Content Index View
 *
 * The content index view displays menu information for the relevant controllers.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Content
 */
?>

<header class="special container">
	<span class="icon fa-pencil"></span>
	<h2>Site Content</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<p>Choose from the following options to edit site content.</p>
			</header>
			<div class="row">
				<section class="4u">
					<?php
					echo $this->Html->link(
						'Pages',
						array('controller' => 'pages', 'action' => 'index'),
						array('class' => 'button special')
					);
					?>
				</section>
				<section class="4u">
					<?php
					echo $this->Html->link(
						'Articles',
						array('controller' => 'articles', 'action' => 'index'),
						array('class' => 'button special')
					);
					?>
				</section>
				<section class="4u">
					<?php
					echo $this->Html->link(
						'Send Newsletter',
						array('controller' => 'subscriptions', 'action' => 'newsletter'),
						array('class' => 'button special')
					);
					?>
				</section>
			</div>
		</section>
	</div>
</section>
