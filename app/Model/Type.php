<?php
/**
 * Types Model
 *
 * Types define user study log data types. Currently, these models cannot be modified via the admin interface as they
 * should remain constant for proper functionality.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Model
 */
class Type extends AppModel {

/**
 * Types can have many log entries.
 *
 * @var array
 */
	public $hasMany = array('Log' => array('className' => 'Log', 'dependent' => true));
}
