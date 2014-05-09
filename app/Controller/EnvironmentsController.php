<?php
/**
 * Robot Environments Controller
 *
 * A robot environment consists of a rosbridge server and MJPEG server.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.Controller
 */
class EnvironmentsController extends AppController {

	/**
	 * The used helpers for the controller.
	 *
	 * @var array
	 */
	public $helpers = array('Html', 'Form');

	/**
	 * The used components for the controller.
	 *
	 * @var array
	 */
	public $components = array('Session', 'Auth' => array('authorize' => 'Controller'));

	/**
	 * The admin index action lists information about all environments. This allows the admin to add, edit, or delete
	 * entries.
	 */
	public function admin_index() {
		// grab all the entries
		$this->set('environments', $this->Environment->find('all'));
	}
}
