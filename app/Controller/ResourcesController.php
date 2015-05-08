<?php
/**
 * Resource Servers Controller
 *
 * Resource servers contain information about a Collada resource server for ros3djs.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */
class ResourcesController extends AppController {

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
		return $this->redirect(array('controller' => 'widget', 'action' => 'index', '#' => 'resources'));
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
			$this->Resource->create();
			// set the current timestamp for creation and modification
			$this->Resource->data['Resource']['created'] = date('Y-m-d H:i:s');
			$this->Resource->data['Resource']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Resource->save($this->request->data)) {
				$this->Session->setFlash('The resource server has been saved.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to add the resource server.');
		}

		$this->set('title_for_layout', 'Add Resource Server');
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
			throw new NotFoundException('Invalid resource server.');
		}

		$resource = $this->Resource->findById($id);
		if (!$resource) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid resource server.');
		}

		// only work for PUT requests
		if ($this->request->is(array('resource', 'put'))) {
			// set the ID
			$this->Resource->id = $id;
			// set the current timestamp for modification
			$this->Resource->data['Resource']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Resource->save($this->request->data)) {
				$this->Session->setFlash('The resource server been updated.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to update the resource server.');
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $resource;
		}

		$this->set('title_for_layout', __('Edit Resource Server - %s', $resource['Resource']['title']));
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
		if ($this->Resource->delete($id)) {
			$this->Session->setFlash('The resource server has been deleted.');
			return $this->redirect(array('action' => 'index'));
		}
	}
}
