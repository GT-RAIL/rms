<?php
/**
 * Loader for Application Wide Configurations
 *
 * This file is automatically loaded and should be used to load configuration settings for things like logging.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */

// setup a 'default' cache configuration for use in the application
Cache::config('default', array('engine' => 'File'));

// event listeners
Configure::write('Dispatcher.filters', array('AssetDispatcher', 'CacheDispatcher'));

// configures default file logging options
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array('engine' => 'File', 'types' => array('notice', 'info', 'debug'), 'file' => 'debug'));
CakeLog::config(
	'error',
	array('engine' => 'File', 'types' => array('warning', 'error', 'critical', 'alert', 'emergency'), 'file' => 'error')
);

// configures authentication globally
App::uses('AuthComponent', 'Controller/Component');
