<?php
/**
 * Roles Model
 *
 * Roles define different user types for the RMS. Currently, these models cannot be modified via the admin interface as
 * they should remain constant for proper functionality.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Model
 */
class Role extends AppModel {

/**
 * Roles can have many users.
 *
 * @var array
 */
	public $hasMany = array('User' => array('className' => 'User', 'dependent' => true));
}
