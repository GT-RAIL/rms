<?php
/**
 * Interactive Marker Settings Controller
 *
 * An interactive marker contains information about the ROS interactive marker topic.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */
class ImsController extends AppController {

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
		return $this->redirect(array('controller' => 'widget', 'action' => 'index', '#' => 'ims'));
	}

/**
 * The admin add action. This will allow the admin to create a new entry.
 *
 * @return null
 */
	public function admin_add() {
		$this->__setCustomLists();

		// load the environments list
		$environments = $this->Im->Environment->find('list');
		$this->set('environments', $environments);

		// only work for POST requests
		if ($this->request->is('post')) {
			// create a new entry
			$this->Im->create();
			// check for empty values
			if ($this->request->data['Im']['collada_id'] === '-1') {
				$this->request->data['Im']['collada_id'] = null;
			}
			if ($this->request->data['Im']['resource_id'] === '-1') {
				$this->request->data['Im']['resource_id'] = null;
			}
			// set the current timestamp for creation and modification
			$this->Im->data['Im']['created'] = date('Y-m-d H:i:s');
			$this->Im->data['Im']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Im->save($this->request->data)) {
				$this->Session->setFlash('The interactive marker has been saved.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to add the interactive marker.');
		}

		$this->set('title_for_layout', 'Add Interactive Marker');
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
		$environments = $this->Im->Environment->find('list');
		$this->set('environments', $environments);

		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid interactive marker.');
		}

		$im = $this->Im->findById($id);
		if (!$im) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid interactive marker.');
		}

		// only work for PUT requests
		if ($this->request->is(array('im', 'put'))) {
			// set the ID
			$this->Im->id = $id;
			// check for empty values
			if ($this->request->data['Im']['collada_id'] === '-1') {
				$this->request->data['Im']['collada_id'] = null;
			}
			if ($this->request->data['Im']['resource_id'] === '-1') {
				$this->request->data['Im']['resource_id'] = null;
			}
			// set the current timestamp for modification
			$this->Im->data['Im']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Im->save($this->request->data)) {
				$this->Session->setFlash('The interactive marker has been updated.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to update the interactive marker.');
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $im;
		}

		$this->set('title_for_layout', __('Edit Interactive Marker - %s', $im['Im']['topic']));
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
		if ($this->Im->delete($id)) {
			$this->Session->setFlash('The interactive marker has been deleted.');
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

		$this->Im->recursive = 3;
		$im = $this->Im->findById($id);
		if (!$im) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid interactive marker.');
		}

		// store the entry
		$this->set('im', $im);
		$this->set('title_for_layout', $im['Im']['topic']);
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
		$colladas = $this->Im->Collada->find('all');
		$colladasList = array(-1 => 'None');
		foreach ($colladas as $collada) {
			$colladasList[$collada['Collada']['id']] = h($collada['Collada']['name']);
		}
		$this->set('colladas', $colladasList);
		$resources = $this->Im->Resource->find('all');
		$resourcesList = array(-1 => 'None');
		foreach ($resources as $resource) {
			$resourcesList[$resource['Resource']['id']] = h($resource['Resource']['url']);
		}
		$this->set('resources', $resourcesList);
	}
}
