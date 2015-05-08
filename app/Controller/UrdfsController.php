<?php
/**
 * URDF Settings Controller
 *
 * An URDF contains information about the ROS parameter and Collada resources.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */
class UrdfsController extends AppController {

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
		return $this->redirect(array('controller' => 'widget', 'action' => 'index', '#' => 'urdfs'));
	}

/**
 * The admin add action. This will allow the admin to create a new entry.
 *
 * @return null
 */
	public function admin_add() {
		$this->__setCustomLists();

		// load the environments list
		$environments = $this->Urdf->Environment->find('list');
		$this->set('environments', $environments);

		// only work for POST requests
		if ($this->request->is('post')) {
			// create a new entry
			$this->Urdf->create();
			// check for empty values
			if ($this->request->data['Urdf']['collada_id'] === '-1') {
				$this->request->data['Urdf']['collada_id'] = null;
			}
			if ($this->request->data['Urdf']['resource_id'] === '-1') {
				$this->request->data['Urdf']['resource_id'] = null;
			}
			// set the current timestamp for creation and modification
			$this->Urdf->data['Urdf']['created'] = date('Y-m-d H:i:s');
			$this->Urdf->data['Urdf']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Urdf->save($this->request->data)) {
				$this->Session->setFlash('The URDF has been saved.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to add the URDF.');
		}

		$this->set('title_for_layout', 'Add URDF');
	}

/**
 * The admin edit action. This allows the admin to edit an existing entry.
 *
 * @param int $id The ID of the entry to edit.
 * @throws NotFoundException Thrown if an entry with the given ID is not found.
 * @return null
 */
	public function admin_edit($id = null) {
		$this->__setCustomLists();

		// load the environments list
		$environments = $this->Urdf->Environment->find('list');
		$this->set('environments', $environments);

		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid Urdf.');
		}

		$urdf = $this->Urdf->findById($id);
		if (!$urdf) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid Urdf.');
		}

		// only work for PUT requests
		if ($this->request->is(array('urdf', 'put'))) {
			// set the ID
			$this->Urdf->id = $id;
			// check for empty values
			if ($this->request->data['Urdf']['collada_id'] === '-1') {
				$this->request->data['Urdf']['collada_id'] = null;
			}
			if ($this->request->data['Urdf']['resource_id'] === '-1') {
				$this->request->data['Urdf']['resource_id'] = null;
			}
			// set the current timestamp for modification
			$this->Urdf->data['Urdf']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Urdf->save($this->request->data)) {
				$this->Session->setFlash('The URDF has been updated.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to update the URDF.');
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $urdf;
		}

		$this->set('title_for_layout', __('Edit URDF - %s', $urdf['Urdf']['param']));
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
		if ($this->Urdf->delete($id)) {
			$this->Session->setFlash('The iURDF has been deleted.');
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
			throw new NotFoundException('Invalid interactive marker.');
		}

		$this->Urdf->recursive = 3;
		$urdf = $this->Urdf->findById($id);
		if (!$urdf) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid interactive marker.');
		}

		// store the entry
		$this->set('urdf', $urdf);
		$this->set('title_for_layout', $urdf['Urdf']['param']);
		// we will need some RWT libraries
		$this->set('rwt', array('roslibjs' => 'current', 'ros3djs' => 'current'));
	}

/**
 * Set the custom Collada lists fields. This will be the name as well as a 'None' option.
 *
 * @return null
 */
	private function __setCustomLists() {
		// load the lists
		$colladas = $this->Urdf->Collada->find('all');
		$colladasList = array(-1 => 'None');
		foreach ($colladas as $collada) {
			$colladasList[$collada['Collada']['id']] = h($collada['Collada']['name']);
		}
		$this->set('colladas', $colladasList);
		$resources = $this->Urdf->Resource->find('all');
		$resourcesList = array(-1 => 'None');
		foreach ($resources as $resource) {
			$resourcesList[$resource['Resource']['id']] = h($resource['Resource']['url']);
		}
		$this->set('resources', $resourcesList);
	}
}
