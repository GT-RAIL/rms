<?php
/**
 * Routes Configuration
 *
 * This file configures static routes for URLs. The most common example is the homepage to default to a page view.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */

// default view for pages is the homepage
Router::connect('/', array('controller' => 'pages', 'action' => 'view'));

// load any plugin routes as well
CakePlugin::routes();

// default routes from CakePHP
require CAKE . 'Config' . DS . 'routes.php';
