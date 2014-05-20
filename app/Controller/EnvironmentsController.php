<?php
/**
 * Robot Environments Controller
 *
 * A robot environment consists of a rosbridge server and MJPEG server.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.Controller
 */
class EnvironmentsController extends AppController {

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
	 * The admin index action lists information about all environments. This allows the admin to add, edit, or delete
	 * entries.
	 */
	public function admin_index() {
		// grab all the entries
		$this->set('environments', $this->Environment->find('all', array('recursive' => 2)));
		// we will need some RWT libraries
		$this->set('rwt', array('roslibjs' => true));
	}

	/**
	 * The admin add action. This will allow the admin to create a new entry.
	 */
	public function admin_add() {
		// load the rosbridge and MJPEG server lists
		$this->setServerLists();

		// only work for POST requests
		if ($this->request->is('post')) {
			// create a new entry
			$this->Environment->create();
			// set the current timestamp for creation and modification
			$this->Environment->data['Environment']['created'] = date('Y-m-d H:i:s');
			$this->Environment->data['Environment']['modified'] = date('Y-m-d H:i:s');

			// check for NULL values
			if ($this->request->data['Environment']['rosbridge_id'] === '-1') {
				$this->request->data['Environment']['rosbridge_id'] = NULL;
			}
			if ($this->request->data['Environment']['mjpeg_id'] === '-1') {
				$this->request->data['Environment']['mjpeg_id'] = NULL;
			}

			// attempt to save the entry
			if ($this->Environment->save($this->request->data)) {
				$this->Session->setFlash('The environment has been saved.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to add the environment.');
		}

		$this->set('title_for_layout', 'Add Environment');
	}

	/**
	 * The admin edit action. This allows the admin to edit an existing entry.
	 *
	 * @param int $id The ID of the entry to edit.
	 * @throws NotFoundException Thrown if an entry with the given ID is not found.
	 */
	public function admin_edit($id = null) {
		// load the rosbridge and MJPEG server lists
		$this->setServerLists();

		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid environment.');
		}

		$environment = $this->Environment->findById($id);
		if (!$environment) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid environment.');
		}

		// only work for PUT requests
		if ($this->request->is(array('environment', 'put'))) {
			// set the ID
			$this->Environment->id = $id;

			// check for NULL values
			if ($this->request->data['Environment']['rosbridge_id'] === '-1') {
				$this->request->data['Environment']['rosbridge_id'] = NULL;
			}
			if ($this->request->data['Environment']['mjpeg_id'] === '-1') {
				$this->request->data['Environment']['mjpeg_id'] = NULL;
			}

			// set the current timestamp for modification
			$this->Environment->data['Environment']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Environment->save($this->request->data)) {
				$this->Session->setFlash('The environment has been updated.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to update the environment.');
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $environment;
		}

		$this->set('title_for_layout', __('Edit Environment - %s', $environment['Environment']['name']));
	}

	/**
	 * Set the custom server lists fields. This will be the name and the URI combined as well as a 'None' option.
	 */
	private function setServerLists() {
		// load the rosbridge and MJPEG server lists
		$rosbridges = $this->Environment->Rosbridge->find('all');
		$rosbridgesList = array(-1 => 'None');
		foreach ($rosbridges as $rosbridge) {
			$rosbridgesList[$rosbridge['Rosbridge']['id']] = __(
				'%s - %s://%s:%s',
				h($rosbridge['Rosbridge']['name']),
				h($rosbridge['Protocol']['name']),
				h($rosbridge['Rosbridge']['host']),
				h($rosbridge['Rosbridge']['port'])
			);
		}
		$this->set('rosbridges', $rosbridgesList);

		$mjpegs = $this->Environment->Mjpeg->find('all');
		$mjpegsList = array(-1 => 'None');
		foreach ($mjpegs as $mjpeg) {
			$mjpegsList[$mjpeg['Mjpeg']['id']] = __(
				'%s - http://%s:%s',
				h($mjpeg['Mjpeg']['name']),
				h($mjpeg['Mjpeg']['host']),
				h($mjpeg['Mjpeg']['port'])
			);
		}
		$this->set('mjpegs', $mjpegsList);
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
		if ($this->Environment->delete($id)) {
			$this->Session->setFlash('The environment has been deleted.');
			return $this->redirect(array('action' => 'index'));
		}
	}
}