<?php
App::uses('InterfaceController', 'Controller');

/**
 * Crowd Manipulation Interface Controller
 *
 * The Grasp Demonstration Interface controller. This interface will allow for navigation and manipulation controls.
 *
 * @author		Russell Toris - rctoris@wpi.edu, Peter Mitrano - robotwizard@wpi.edu, David Kent - davidkent@wpi.edu
 * @copyright	2015 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/GraspDemonstrationInterface
 * @since		GraspDemonstrationInterface v 0.0.1
 * @version		0.0.1
 * @package		app.Controller
 */
class GraspDemonstrationInterfaceController extends InterfaceController {

/**
 * The basic view action. All necessary variables are set in the main interface controller.
 *
 * @return null
 */
	public function view() {
		// set the title of the HTML page
		$this->set('title_for_layout', 'Online Grasp Demonstration');
		// we will need some RWT libraries
		$this->set('rwt',
			array(
				'roslibjs' => 'current',
				'ros2djs' => 'current',
				'nav2djs' => 'current',
				'ros3djs' => 'current',
				'keyboardteleopjs' => 'current',
				'mjpegcanvasjs' => 'current'
			)
		);
	}
}
