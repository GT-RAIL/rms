<?php
/**
 * Default Layout
 *
 * The main, default layout uses the RMS template to create a new page with a menu, content section, and footer.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Layouts
 */
?>

<?php $home = isset($home) && $home; ?>

<!DOCTYPE html>
<html>
<?php echo $this->element('head'); ?>

<body class="<?php echo ($home) ? 'index' : 'no-sidebar'; ?> loading">
	<?php echo $this->element('menu', array('home' => $home)); ?>

	<?php
	if ($home) {
		echo $this->element('banner');
	}
	?>

	<article id="main">
		<?php if($this->Session->check('Message.flash')): ?>
			<section class="flash">
				<p><?php echo $this->Session->flash(); ?></p>
			</section>
		<?php endif; ?>
		<?php echo $this->fetch('content'); ?>
	</article>

	<?php echo $this->element('footer'); ?>
</body>
</html>
