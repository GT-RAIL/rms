<?php
/**
 * User Studies Controller
 *
 * A user study contains information about the name of the study, access controls, and start/end dates.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.Controller
 */
class StudiesController extends AppController {

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
	 * The admin index action lists information about all studies. This allows the admin to add, edit, or delete
	 * entries.
	 */
	public function admin_index() {
		// grab all the entries
		$this->set('studies', $this->Study->find('all'));
		$this->set('title_for_layout', 'User Studies');
	}

	/**
	 * The admin add action. This will allow the admin to create a new entry.
	 */
	public function admin_add() {
		// only work for POST requests
		if ($this->request->is('post')) {
			// create a new entry
			$this->Study->create();
			// set the current timestamp for creation and modification
			$this->Study->data['Study']['created'] = date('Y-m-d H:i:s');
			$this->Study->data['Study']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Study->save($this->request->data)) {
				$this->Session->setFlash('The study has been saved.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to add the study.');
		}

		$this->set('title_for_layout', 'Add Study');
	}

	/**
	 * The admin edit action. This allows the admin to edit an existing entry.
	 *
	 * @param int $id The ID of the entry to edit.
	 * @throws NotFoundException Thrown if an entry with the given ID is not found.
	 */
	public function admin_edit($id = null) {
		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid study.');
		}

		$study = $this->Study->findById($id);
		if (!$study) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid study.');
		}

		// only work for PUT requests
		if ($this->request->is(array('study', 'put'))) {
			// set the ID
			$this->Study->id = $id;
			// set the current timestamp for modification
			$this->Study->data['Study']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Study->save($this->request->data)) {
				$this->Session->setFlash('The study has been updated.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to update the study.');
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $study;
		}

		$this->set('title_for_layout', __('Edit Study - %s', $study['Study']['name']));
	}

	/**
	 * The admin delete action. This allows the admin to delete an existing entry.
	 *
	 * @param int $id The ID of the entry to delete.
	 * @throws MethodNotAllowedException Thrown if a GET request is made.
	 */
	public function admin_delete($id = null) {
		// do not allow GET requests
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		// attempt to delete the entry
		if ($this->Study->delete($id)) {
			$this->Session->setFlash('The study has been deleted.');
			return $this->redirect(array('action' => 'index'));
		}
	}
}
