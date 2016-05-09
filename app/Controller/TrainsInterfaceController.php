<?php
App::uses('InterfaceController', 'Controller');

/**
 * Crowd Manipulation Interface Controller
 *
 * The Crowd Manipulation Interface controller. This interface will allow for navigation and manipulation controls.
 *
 * @author		Russell Toris - rctoris@wpi.edu, Peter Mitrano - robotwizard@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/CrowdManipulationInterface
 * @since		CrowdManipulationInterface v 0.0.1
 * @version		0.0.1
 * @package		app.Controller
 */
class TrainsInterfaceController extends InterfaceController {


/**
 * The basic view action. All necessary variables are set in the main interface controller.
 *
 * @return null
 */
	public function view() {
		
		// set the title of the HTML page
		$this->set('title_for_layout', 'TRAINS Study');

		// we will need some RWT libraries
		$this->set('rwt',
			array(
				'roslibjs' => 'current',
				'ros2djs' => 'current',
				'nav2djs' => 'current',
				'ros3djs' => 'current',
				'keyboardteleopjs' => 'current',
				'mjpegcanvasjs' => 'current',
				'rosqueuejs' => 'current'
			)
		);

		$this->set('userId', $this->Auth->user('id'));
	}

	public function admin(){
		// set the title of the HTML page
		$this->set('title_for_layout', 'TRAINS Study');

		// we will need some RWT libraries
		$this->set('rwt',
			array(
				'roslibjs' => 'current',
				'ros2djs' => 'current',
				'nav2djs' => 'current',
				'ros3djs' => 'current',
				'keyboardteleopjs' => 'current',
				'mjpegcanvasjs' => 'current',
				'rosqueuejs' => 'current'
			)
		);

		$this->set('userId', $this->Auth->user('id'));
	}
}
