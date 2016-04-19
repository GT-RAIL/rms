<?php
/**
 * Admin Edit Article View
 *
 * The edit articles view allows an admin to edit and existing article in the database.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Articles
 */
?>

<header class="special container">
	<span class="icon fa-pencil"></span>
	<h2>Edit Article</h2>
</header>

<?php echo $this->element('article_form', array('edit' => true)); ?>
