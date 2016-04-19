<?php
/**
 * Protocols Model
 *
 * Protocols define rosbridge transport protocols. Currently, these models cannot be modified via the admin interface as
 * they should remain constant for proper functionality.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Model
 */
class Protocol extends AppModel {

/**
 * Protocols can have many rosbridge servers.
 *
 * @var array
 */
	public $hasMany = array('Rosbridge' => array('className' => 'Rosbridge', 'dependent' => true));
}
