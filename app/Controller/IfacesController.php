<?php
/**
 * Interfaces Controller
 *
 * An interface contains information about the name of the interface and class definition. Ifaces is used to prevent
 * using the reserved PHP keyword interface.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */
class IfacesController extends AppController {

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
 * Define the actions which can be used by any user, authorized or not.
 *
 * @return null
 */
	public function beforeFilter() {
		// allow anyone to view an interface (interface authorization will check this better)
		parent::beforeFilter();
		$this->Auth->allow('view');
	}

/**
 * The admin index action lists information about all interfaces. This allows the admin to add, edit, or delete
 * entries.
 *
 * @return null
 */
	public function admin_index() {
		// grab all the entries
		$this->set('ifaces', $this->Iface->find('all'));
		$this->set('title_for_layout', 'Interfaces');
	}

/**
 * The admin add action. This will allow the admin to create a new entry.
 *
 * @return null
 */
	public function admin_add() {
		// grab the list of environments
		$this->set('environments', $this->Iface->Environment->find('list'));

		// only work for POST requests
		if ($this->request->is('post')) {
			// create a new entry
			$this->Iface->create();
			// set the current timestamp for creation and modification
			$this->Iface->data['Iface']['created'] = date('Y-m-d H:i:s');
			$this->Iface->data['Iface']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Iface->save($this->request->data)) {
				$this->Session->setFlash('The interface has been saved.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to add the interface.');
		}

		$this->set('title_for_layout', 'Add Interface');
	}

/**
 * The admin edit action. This allows the admin to edit an existing entry.
 *
 * @param int $id The ID of the entry to edit.
 * @throws NotFoundException Thrown if an entry with the given ID is not found.
 * @return null
 */
	public function admin_edit($id = null) {
		// grab the list of environments
		$this->set('environments', $this->Iface->Environment->find('list'));

		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid interface.');
		}

		$iface = $this->Iface->findById($id);
		if (!$iface) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid interface.');
		}

		// only work for PUT requests
		if ($this->request->is(array('iface', 'put'))) {
			// set the ID
			$this->Iface->id = $id;
			// set the current timestamp for modification
			$this->Iface->data['Iface']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Iface->save($this->request->data)) {
				$this->Session->setFlash('The interface has been updated.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to update the interface.');
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $iface;
		}

		$this->set('title_for_layout', __('Edit Interface - %s', $iface['Iface']['name']));
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
		if ($this->Iface->delete($id)) {
			$this->Session->setFlash('The interface has been deleted.');
			return $this->redirect(array('action' => 'index'));
		}
	}

/**
 * Request to view the given interface with the given environment. This will make the correct redirect.
 *
 * @param int $id The ID of the interface to view.
 * @param int $environmentID The environment ID to use.
 * @throws NotFoundException Thrown if an entry with the given IDs is not found.
 * @return null
 */
	public function view($id = null, $environmentID = null) {
		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid interface.');
		}
		if (!$environmentID) {
			// no environment ID provided
			throw new NotFoundException('Invalid environment.');
		}

		$this->Iface->recursive = 2;
		$iface = $this->Iface->findById($id);
		if (!$iface) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid interface.');
		}
		$environment = null;
		foreach ($iface['Environment'] as $env) {
			if ($env['id'] === $environmentID) {
				$environment = $env;
			}
		}
		if (!$environment) {
			// no valid entry found for the given environment ID
			throw new NotFoundException('Invalid environment.');
		}

		// call the correct controller
		$controller = __('%sInterface', str_replace(' ', '', ucwords(h($iface['Iface']['name']))));
		return $this->redirect(array('controller' => $controller, 'action' => 'view', $environmentID));
	}
}
