<?php
/**
 * Teleoperation Settings Controller
 *
 * A teleoperation contains information about the geometry_msgs/Twist topic and optional throttle rate.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */
class TeleopsController extends AppController {

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
 * The admin index action redirects to the main widget index.
 *
 * @return null
 */
	public function admin_index() {
		return $this->redirect(array('controller' => 'widget', 'action' => 'index', '#' => 'teleops'));
	}

/**
 * The admin add action. This will allow the admin to create a new entry.
 *
 * @return null
 */
	public function admin_add() {
		// load the environments list
		$environments = $this->Teleop->Environment->find('list');
		$this->set('environments', $environments);

		// only work for POST requests
		if ($this->request->is('post')) {
			// create a new entry
			$this->Teleop->create();
			// check for empty values
			if (strlen($this->request->data['Teleop']['throttle']) === 0) {
				$this->request->data['Teleop']['throttle'] = null;
			}
			// set the current timestamp for creation and modification
			$this->Teleop->data['Teleop']['created'] = date('Y-m-d H:i:s');
			$this->Teleop->data['Teleop']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Teleop->save($this->request->data)) {
				$this->Session->setFlash('The teleop has been saved.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to add the teleop.');
		}

		$this->set('title_for_layout', 'Add Teleop');
	}

/**
 * The admin edit action. This allows the admin to edit an existing entry.
 *
 * @param int $id The ID of the entry to edit.
 * @throws NotFoundException Thrown if an entry with the given ID is not found.
 * @return null
 */
	public function admin_edit($id = null) {
		// load the environments list
		$environments = $this->Teleop->Environment->find('list');
		$this->set('environments', $environments);

		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid teleop.');
		}

		$teleop = $this->Teleop->findById($id);
		if (!$teleop) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid teleop.');
		}

		// only work for PUT requests
		if ($this->request->is(array('teleop', 'put'))) {
			// set the ID
			$this->Teleop->id = $id;
			// check for empty values
			if (strlen($this->request->data['Teleop']['throttle']) === 0) {
				$this->request->data['Teleop']['throttle'] = null;
			}
			// set the current timestamp for modification
			$this->Teleop->data['Teleop']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Teleop->save($this->request->data)) {
				$this->Session->setFlash('The teleop has been updated.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to update the teleop.');
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $teleop;
		}

		$this->set('title_for_layout', __('Edit Teleop - %s', $teleop['Teleop']['topic']));
	}

/**
 * The admin delete action. This allows the admin to delete an existing entry.
 *
 * @param int $id The ID of the entry to delete.
 * @throws MethodNotAllowedException Thrown if a GET request is made.
 * @return null
 */
	public function admin_delete($id = null) {
		// do not allow GET requests
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		// attempt to delete the entry
		if ($this->Teleop->delete($id)) {
			$this->Session->setFlash('The teleop has been deleted.');
			return $this->redirect(array('action' => 'index'));
		}
	}
}
