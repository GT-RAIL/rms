<?php
/**
 * rosbridge Servers Controller
 *
 * A rosbridge server contains information about the protocol, host, port, and optional rosauth key.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */
class RosbridgesController extends AppController {

/**
 * The used helpers for the controller.
 *
 * @var array
 */
	public $helpers = array('Html', 'Form', 'Rms');

/**
 * The used components for the controller.
 *
 * @var array
 */
	public $components = array('Session', 'Auth' => array('authorize' => 'Controller'));

/**
 * The admin index action lists information about all environments. This allows the admin to add, edit, or delete
 * entries.
 *
 * @return null
 */
	public function admin_index() {
		// grab all the entries
		$this->set('rosbridges', $this->Rosbridge->find('all'));
		// we will need some RWT libraries
		$this->set('rwt', array('roslibjs' => 'current'));
		$this->set('title_for_layout', 'rosbridge Servers');
	}

/**
 * The admin add action. This will allow the admin to create a new entry.
 *
 * @return null
 */
	public function admin_add() {
		// load the protocols list
		$protocols = $this->Rosbridge->Protocol->find('list');
		$this->set('protocols', $protocols);

		// only work for POST requests
		if ($this->request->is('post')) {
			// create a new entry
			$this->Rosbridge->create();
			// check for empty key
			if (strlen($this->request->data['Rosbridge']['rosauth']) === 0) {
				$this->request->data['Rosbridge']['rosauth'] = null;
			}
			// set the current timestamp for creation and modification
			$this->Rosbridge->data['Rosbridge']['created'] = date('Y-m-d H:i:s');
			$this->Rosbridge->data['Rosbridge']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Rosbridge->save($this->request->data)) {
				$this->Session->setFlash('The rosbridge server has been saved.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to add the rosbridge server.');
		}

		$this->set('title_for_layout', 'Add rosbridge Server');
	}

/**
 * The admin edit action. This allows the admin to edit an existing entry.
 *
 * @param int $id The ID of the entry to edit.
 * @throws NotFoundException Thrown if an entry with the given ID is not found.
 * @return null
 */
	public function admin_edit($id = null) {
		// load the protocols list
		$protocols = $this->Rosbridge->Protocol->find('list');
		$this->set('protocols', $protocols);

		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid rosbridge.');
		}

		$rosbridge = $this->Rosbridge->findById($id);
		if (!$rosbridge) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid rosbridge.');
		}

		// only work for PUT requests
		if ($this->request->is(array('rosbridge', 'put'))) {
			// set the ID
			$this->Rosbridge->id = $id;
			// check for empty key
			if (strlen($this->request->data['Rosbridge']['rosauth']) === 0) {
				$this->request->data['Rosbridge']['rosauth'] = null;
			}
			// set the current timestamp for modification
			$this->Rosbridge->data['Rosbridge']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Rosbridge->save($this->request->data)) {
				$this->Session->setFlash('The rosbridge server has been updated.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to update the rosbridge server.');
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $rosbridge;
		}

		$this->set('title_for_layout', __('Edit rosbridge Server - %s', $rosbridge['Rosbridge']['name']));
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
		if ($this->Rosbridge->delete($id)) {
			$this->Session->setFlash('The rosbridge server has been deleted.');
			return $this->redirect(array('action' => 'index'));
		}
	}

/**
 * View the given entry.
 *
 * @param int $id The ID of the entry to view.
 * @throws NotFoundException Thrown if an entry with the given ID is not found.
 * @return null
 */
	public function admin_view($id = null) {
		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid rosbridge.');
		}

		$rosbridge = $this->Rosbridge->findById($id);
		if (!$rosbridge) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid rosbridge.');
		}

		// store the entry
		$this->set('rosbridge', $rosbridge);
		$this->set('title_for_layout', $rosbridge['Rosbridge']['name']);
		// we will need some RWT libraries
		$this->set('rwt', array('roslibjs' => 'current'));
	}
}
