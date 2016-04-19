<?php
/**
 * Widget Controller
 *
 * The widget controller is the main page for editing ROS topics and widgets. This is made to keep the admin panel
 * cleaner instead of creating new pages for each.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */
class WidgetController extends AppController {

/**
 * The used helpers for the controller.
 *
 * @var array
 */
	public $helpers = array('Html');

/**
 * The used models for the controller.
 *
 * @var array
 */
	public $uses = array('Stream', 'Teleop', 'Tf', 'Marker', 'Im', 'Resource', 'Urdf');

/**
 * The used components for the controller.
 *
 * @var array
 */
	public $components = array('Session', 'Auth' => array('authorize' => 'Controller'));

/**
 * The admin index action lists information about ROS topics and widgets. This allows the admin to add, edit, or
 * delete entries.
 *
 * @return null
 */
	public function admin_index() {
		// grab all the entries we need.
		$this->set('streams', $this->Stream->find('all', array('recursive' => 2, 'order' => 'Stream.environment_id')));
		$this->set('teleops', $this->Teleop->find('all', array('recursive' => 2, 'order' => 'Teleop.environment_id')));
		$this->set('markers', $this->Marker->find('all', array('recursive' => 2, 'order' => 'Marker.environment_id')));
		$this->set('ims', $this->Im->find('all', array('recursive' => 2, 'order' => 'Im.environment_id')));
		$this->set('urdfs', $this->Urdf->find('all', array('recursive' => 2, 'order' => 'Urdf.environment_id')));
		$this->set('resources', $this->Resource->find('all'));
		$this->set('tfs', $this->Tf->find('all', array('recursive' => 2)));
		$this->set('title_for_layout', 'ROS Topics and Widgets');
	}
}
