<?php
/**
 * Empty Layout
 *
 * The empty layout only loads the core RMS libraries. No default styles are added.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.1
 * @version		2.0.9
 * @package		app.View.Layouts
 */
?>

<!DOCTYPE html>
<html>
<?php echo $this->element('head', array('style' => false)); ?>

<body>
	<?php echo $this->Session->flash(); ?>
	<?php echo $this->fetch('content'); ?>
</body>
</html>
