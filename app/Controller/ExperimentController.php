<?php
/**
 * Experiment Controller
 *
 * The experiment controller has a single index action for the user study items.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */
class ExperimentController extends AppController {

/**
 * The used helpers for the controller.
 *
 * @var array
 */
	public $helpers = array('Html');

/**
 * The used components for the controller.
 *
 * @var array
 */
	public $components = array('Session', 'Auth' => array('authorize' => 'Controller'));

/**
 * The admin index displays the relevant menu items.
 *
 * @return null
 */
	public function admin_index() {
		$this->set('title_for_layout', 'Experiment Settings');
	}
}
