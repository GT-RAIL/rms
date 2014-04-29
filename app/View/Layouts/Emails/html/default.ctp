<?php
/**
 * Default HTML Email Layout
 *
 * The default HTML email layout will display a simple HTML page that shows the content and a shout-out to the RMS.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.View.Layouts.Emails.html
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
	<title><?php echo $title_for_layout; ?></title>
</head>
<body>
	<?php echo $this->fetch('content'); ?>

	<p>This email was sent using the <a href="http://wiki.ros.org/rms">Robot Management System</a>.</p>
</body>
</html>
