<?php
/**
 * Admin Upload Logo View
 *
 * The upload logo page allows an admin to upload a new site logo.
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
	<span class="icon fa-upload"></span>
	<h2>Upload Logo</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<section>
			<header>
				<h3>Upload a New Logo Image Below</h3>
			</header>
			<?php
			echo $this->Form->create('Setting', array('type' => 'file'));
			echo $this->Form->input('id', array('type' => 'hidden'));
			echo $this->Form->file('Setting.logo');
			echo $this->Form->end(array('label' => 'Upload', 'class' => 'button special'));
			?>
		</section>
	</div>
</section>
