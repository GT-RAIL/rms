<?php
/**
 * Core Configuration File
 *
 * The main configuration file for the system.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */

// debug output level -- 0 = off, 1 = warnings, 2 = full debug
Configure::write('debug', 1);

// error handler
Configure::write(
	'Error',
	array('handler' => 'ErrorHandler::handleError', 'level' => E_ALL & ~E_DEPRECATED, 'trace' => true)
);

// exception handler
Configure::write(
	'Exception',
	array('handler' => 'ErrorHandler::handleException', 'renderer' => 'ExceptionRenderer', 'log' => true)
);

// default charset encoding
Configure::write('App.encoding', 'UTF-8');

// admin prefix for all admin actions
Configure::write('Routing.prefixes', array('admin'));

// session configuration
Configure::write('Session', array('defaults' => 'php'));

// used for generating unique password salts
Configure::write('Security.salt', '43935b8e6fbef5a4937ee6fa6ee4157c3d5bb429');

// used for encryption/decryption of strings
Configure::write('Security.cipherSeed', '313362373435646630643062303738');

// default system for caches
$engine = 'File';
// cache duration
$duration = '+240 seconds';
// cache prefix
$prefix = 'rms_';

// set the configuration settings
Cache::config('_cake_core_', array(
	'engine' => $engine,
	'prefix' => $prefix . 'cake_core_',
	'path' => CACHE . 'persistent' . DS,
	'serialize' => ($engine === 'File'),
	'duration' => $duration
));
Cache::config('_cake_model_', array(
	'engine' => $engine,
	'prefix' => $prefix . 'cake_model_',
	'path' => CACHE . 'models' . DS,
	'serialize' => ($engine === 'File'),
	'duration' => $duration
));
