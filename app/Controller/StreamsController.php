<?php
/**
 * MJPEG Server Streams Controller
 *
 * A MJPEG server stream contains information about the ROS image topic and streaming parameters.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */
class StreamsController extends AppController {

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
 * The admin index action redirects to the main widget index.
 *
 * @return null
 */
	public function admin_index() {
		return $this->redirect(array('controller' => 'widget', 'action' => 'index', '#' => 'streams'));
	}

/**
 * The admin add action. This will allow the admin to create a new entry.
 *
 * @return null
 */
	public function admin_add() {
		// load the environments list
		$environments = $this->Stream->Environment->find('list');
		$this->set('environments', $environments);

		// only work for POST requests
		if ($this->request->is('post')) {
			// create a new entry
			$this->Stream->create();
			// check for empty values
			if (strlen($this->request->data['Stream']['width']) === 0) {
				$this->request->data['Stream']['width'] = null;
			}
			if (strlen($this->request->data['Stream']['height']) === 0) {
				$this->request->data['Stream']['height'] = null;
			}
			if (strlen($this->request->data['Stream']['quality']) === 0) {
				$this->request->data['Stream']['quality'] = null;
			}
			// set the current timestamp for creation and modification
			$this->Stream->data['Stream']['created'] = date('Y-m-d H:i:s');
			$this->Stream->data['Stream']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Stream->save($this->request->data)) {
				$this->Session->setFlash('The MJPEG stream has been saved.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to add the MJPEG stream.');
		}

		$this->set('title_for_layout', 'Add MJPEG Stream');
	}

/**
 * The admin edit action. This allows the admin to edit an existing entry.
 *
 * @param int $id The ID of the entry to edit.
 * @throws NotFoundException Thrown if an entry with the given ID is not found.
 * @return null
 */
	public function admin_edit($id = null) {
		// load the environments list
		$environments = $this->Stream->Environment->find('list');
		$this->set('environments', $environments);

		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid stream.');
		}

		$stream = $this->Stream->findById($id);
		if (!$stream) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid stream.');
		}

		// only work for PUT requests
		if ($this->request->is(array('stream', 'put'))) {
			// set the ID
			$this->Stream->id = $id;
			// check for empty values
			if (strlen($this->request->data['Stream']['width']) === 0) {
				$this->request->data['Stream']['width'] = null;
			}
			if (strlen($this->request->data['Stream']['height']) === 0) {
				$this->request->data['Stream']['height'] = null;
			}
			if (strlen($this->request->data['Stream']['quality']) === 0) {
				$this->request->data['Stream']['quality'] = null;
			}
			// set the current timestamp for modification
			$this->Stream->data['Stream']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Stream->save($this->request->data)) {
				$this->Session->setFlash('The MJPEG stream has been updated.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to update the MJPEG stream.');
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $stream;
		}

		$this->set('title_for_layout', __('Edit MJPEG Stream - %s', $stream['Stream']['name']));
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
		if ($this->Stream->delete($id)) {
			$this->Session->setFlash('The MJPEG stream has been deleted.');
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
			throw new NotFoundException('Invalid stream.');
		}

		$this->Stream->recursive = 2;
		$stream = $this->Stream->findById($id);
		if (!$stream) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid stream.');
		}

		// store the entry
		$this->set('stream', $stream);
		$this->set('title_for_layout', $stream['Stream']['topic']);
	}
}
