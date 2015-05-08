<?php
/**
 * Study Conditions Controller
 *
 * A user study condition contains information about the name of the condition and associated interface.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */
class ConditionsController extends AppController {

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
 * The admin index action lists information about all conditions. This allows the admin to add, edit, or delete
 * entries.
 *
 * @return null
 */
	public function admin_index() {
		// grab all the entries
		$this->set('conditions', $this->Condition->find('all', array('recursive' => 2)));
		$this->set('title_for_layout', 'Study Conditions');
	}

/**
 * The admin add action. This will allow the admin to create a new entry.
 *
 * @return null
 */
	public function admin_add() {
		// load the experiments and interface list
		$this->set('studies', $this->Condition->Study->find('list'));
		$this->set('ifaces', $this->Condition->Iface->find('list'));
		$this->set('environments', $this->Condition->Environment->find('list'));

		// only work for POST requests
		if ($this->request->is('post')) {
			// create a new entry
			$this->Condition->create();
			// set the current timestamp for creation and modification
			$this->Condition->data['Condition']['created'] = date('Y-m-d H:i:s');
			$this->Condition->data['Condition']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Condition->save($this->request->data)) {
				$this->Session->setFlash('The condition has been saved.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to add the condition.');
		}

		$this->set('title_for_layout', 'Add Condition');
	}

/**
 * The admin edit action. This allows the admin to edit an existing entry.
 *
 * @param int $id The ID of the entry to edit.
 * @throws NotFoundException Thrown if an entry with the given ID is not found.
 * @return null
 */
	public function admin_edit($id = null) {
		// load the experiments and interface list
		$this->set('studies', $this->Condition->Study->find('list'));
		$this->set('ifaces', $this->Condition->Iface->find('list'));
		$this->set('environments', $this->Condition->Environment->find('list'));

		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid condition.');
		}

		$condition = $this->Condition->findById($id);
		if (!$condition) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid condition.');
		}

		// only work for PUT requests
		if ($this->request->is(array('condition', 'put'))) {
			// set the ID
			$this->Condition->id = $id;
			// set the current timestamp for modification
			$this->Condition->data['Condition']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Condition->save($this->request->data)) {
				$this->Session->setFlash('The condition has been updated.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to update the condition.');
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $condition;
		}

		$this->set('title_for_layout', __('Edit Condition - %s', $condition['Condition']['name']));
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
		if ($this->Condition->delete($id)) {
			$this->Session->setFlash('The condition has been deleted.');
			return $this->redirect(array('action' => 'index'));
		}
	}
}
