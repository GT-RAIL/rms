<?php
App::uses('InterfaceController', 'Controller');

/**
 * Queueing study Interface Controller
 *
 * The Queueing Study interface controller. This interface will allow for navigation and manipulation controls.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/petermitrano/QueueingStudyInterface
 * @since		QueueingStudyInterface v 0.0.1
 * @version		0.0.6
 * @package		app.Controller
 */
class QueueingStudyInterfaceController extends InterfaceController {

/**
 * The basic view action. All necessary variables are set in the main interface controller.
 *
 * @return null
 */
	public $components = array('Cookie');

	public function beforeFilter() {
		parent::beforeFilter();
		if ($this->Cookie->check('has_visited')){
			$this->set('has_visited','true');
		}
		else {
			$this->set('has_visited','false');
			$this->Cookie->write('has_visited', '1');
		}
	}

	public function view() {
		// set the title of the HTML page
		$this->set('title_for_layout', 'CARL (Crowdsourcing for Autonomous Robot Learning)');
		// we will need some RWT libraries
		$this->set('rwt',
			array(
				'roslibjs' => 'current',
				'ros2djs' => 'current',
				'nav2djs' => 'current',
				'ros3djs' => 'current',
				'keyboardteleopjs' => 'current',
				'mjpeg' => 'current',
				'rosqueuejs' => 'current'
			)
		);
	}
}
