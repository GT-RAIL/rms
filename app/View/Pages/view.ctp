<?php
/**
 * Page View
 *
 * The page view will display a page will all associated articles on it. If an admin is logged in, edit buttons will
 * appear for each page and each article for quick edits.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Pages
 */
?>

<header class="special container">
	<span class="icon fa-spinner"></span>
	<h2><?php echo h($page['Page']['title']); ?></h2>
</header>

<?php foreach ($page['Article'] as $article): ?>
	<section class="wrapper style4 container">
		<div class="content">
			<section>
				<header>
					<h3><?php echo h($article['title']); ?></h3>
				</header>
				<?php echo __($article['content']); ?>
			</section>
		</div>
		<?php
		if($admin) {
			echo $this->Html->link(
				'Edit',
				array('admin' => true, 'controller' => 'articles', 'action' => 'edit', $article['id']),
				array('class' => 'button small')
			);
		}
		?>
	</section>
<?php endforeach; ?>
