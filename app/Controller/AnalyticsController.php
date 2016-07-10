<?php

/**
 * Analytics Controller
 *
 * The analytics gets data about the user when they are performing an experiment.
 * Currently the only experiment that uses it is Trains view.ctp
 *
 * @author		Carl Saldanha - csaldanh3@gatech.edu
 * @copyright	2016 Georgia Institute Of Technology
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */
class AnalyticsController extends AppController {

/**
 * The used helpers for the controller.
 *
 * @var array
 */
	public $helpers = array('Html', 'Form', 'Time', 'Rms');

/**
 * The used models for the controller.
 *
 * @var array
 */
	public $uses = array('User', 'Analytics');

/**
 * The admin index action lists information about all users. This allows the admin to add, edit, or delete entries.
 *
 * @return null
 */
	public function admin_index() {
		$this->Paginator->settings = $this->paginate;
		// grab all the fetched entries
		$this->set('users', $this->Paginator->paginate('Analytics'));
	}

/**
 * The admin edit action. This allows the admin to edit an existing entry.
 *
 * @param int $id The ID of the entry to edit.
 * @throws NotFoundException Thrown if an entry with the given ID is not found.
 * @return null
 */
	public function admin_edit($id = null) {
		// load the roles list
		$roles = $this->User->Role->find('list');
		$this->set('roles', $roles);

		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid user.');
		}

		$user = $this->User->findById($id);
		if (!$user) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid user.');
		}

		// only work for PUT requests
		if ($this->request->is(array('user', 'put'))) {
			// set the ID
			$this->User->id = $id;
			// set the current timestamp for modification
			$this->User->data['User']['modified'] = date('Y-m-d H:i:s');

			// attempt to save the entry
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash('The user has been updated.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to update the user.');
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $user;
		}

		$this->set('title_for_layout', __('Edit User - %s', $user['User']['username']));
	}

/**
 * The admin delete action. This allows the admin to delete an existing entry. An admin may not delete themselves.
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

		// check the ID
		if ($this->Auth->user('id') === $id) {
			$this->Session->setFlash('You many not delete yourself.');
			return $this->redirect(array('action' => 'index'));
		} elseif ($this->User->delete($id)) {
			$this->Session->setFlash('The user has been deleted.');
			return $this->redirect(array('action' => 'index'));
		}
	}

/**
 * Post call to allow usesrs to add Analytics to the system
 *
 * @return success false or true
 */
	public function add() {
		if ($this->request->is('post')) {
			// create a new entry
			$this->Analytics->create();
			// set the current timestamp for creation and modification
			$this->Analytics->data['Analytics']['created'] = date('Y-m-d H:i:s');
			$this->Analytics->data['Analytics']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Analytics->save($this->request->data)) {
				return '[success=>"true"]';
			}
		}
		return '[success=>"false"]';
	}
}
