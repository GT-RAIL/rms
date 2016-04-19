<?php
/**
 * Default Footer
 *
 * The footer contains a copyright note and acknowledgement to the RMS.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Elements
 */
?>

<footer id="footer">
	<span class="copyright">
		&copy; <?php echo __('%s %s', date('Y'), h($setting['Setting']['copyright'])); ?>
		<br />
		Powered by the
		<strong><?php echo $this->Html->link('Robot Management System', 'http://wiki.ros.org/rms'); ?></strong>
	</span>
</footer>
