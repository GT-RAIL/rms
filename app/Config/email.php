<?php
/**
 * Email Settings Configuration
 *
 * The email settings configuration will dynamically load settings from the RMS database.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Config
 */
class EmailConfig {

/**
 * The dynamic config variable (defined on creation).
 *
 * @var array
 */
	public $dynamic;

/**
 * Create a new email config by loading settings from the RMS database.
 */
	public function __construct() {
		$this->dynamic = ClassRegistry::init('Email')->getConfig();
	}
}
