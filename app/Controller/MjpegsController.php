<?php
/**
 * MJPEG Servers Controller
 *
 * A MJPEG server contains information about the host and port.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */
class MjpegsController extends AppController {

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
 * The admin index action lists information about all MJPEG servers. This allows the admin to add, edit, or delete
 * entries.
 *
 * @return null
 */
	public function admin_index() {
		// grab all the entries
		$this->set('mjpegs', $this->Mjpeg->find('all', array('recursive' => 2)));
		$this->set('title_for_layout', 'MJPEG Servers');
	}

/**
 * The admin add action. This will allow the admin to create a new entry.
 *
 * @return null
 */
	public function admin_add() {
		// only work for POST requests
		if ($this->request->is('post')) {
			// create a new entry
			$this->Mjpeg->create();
			// set the current timestamp for creation and modification
			$this->Mjpeg->data['Mjpeg']['created'] = date('Y-m-d H:i:s');
			$this->Mjpeg->data['Mjpeg']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Mjpeg->save($this->request->data)) {
				$this->Session->setFlash('The MJPEG server has been saved.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to add the MJPEG server.');
		}

		$this->set('title_for_layout', 'Add MJPEG Server');
	}

/**
 * The admin edit action. This allows the admin to edit an existing entry.
 *
 * @param int $id The ID of the entry to edit.
 * @throws NotFoundException Thrown if an entry with the given ID is not found.
 * @return null
 */
	public function admin_edit($id = null) {
		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid mjpeg.');
		}

		$mjpeg = $this->Mjpeg->findById($id);
		if (!$mjpeg) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid mjpeg.');
		}

		// only work for PUT requests
		if ($this->request->is(array('mjpeg', 'put'))) {
			// set the ID
			$this->Mjpeg->id = $id;
			// set the current timestamp for modification
			$this->Mjpeg->data['Mjpeg']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Mjpeg->save($this->request->data)) {
				$this->Session->setFlash('The MJPEG server has been updated.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to update the MJPEG server.');
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $mjpeg;
		}

		$this->set('title_for_layout', __('Edit MJPEG Server - %s', $mjpeg['Mjpeg']['name']));
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
		if ($this->Mjpeg->delete($id)) {
			$this->Session->setFlash('The MJPEG server has been deleted.');
			return $this->redirect(array('action' => 'index'));
		}
	}

/**
 * View the given entry.
 *
 * @param intl $id The ID of the entry to view.
 * @throws NotFoundException Thrown if an entry with the given ID is not found.
 * @return null
 */
	public function admin_view($id = null) {
		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid mjpeg.');
		}

		$this->Mjpeg->recursive = 2;
		$mjpeg = $this->Mjpeg->findById($id);
		if (!$mjpeg) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid mjpeg.');
		}

		// store the entry
		$this->set('mjpeg', $mjpeg);
		$this->set('title_for_layout', $mjpeg['Mjpeg']['name']);
	}
}
