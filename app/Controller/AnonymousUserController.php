<?php
App::uses('CakeEmail', 'Network/Email');

/**
 * Users Controller
 *
 * The users controller manages the account creation, modification, and removal of user and admin accounts. This
 * controller also manages basic authentication mechanisms used throughout the RMS.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */
class AnonymousUserController extends AppController {

/**
 * The used helpers for the controller.
 *
 * @var array
 */
	public $helpers = array('Html', 'Form', 'Paginator', 'Time', 'Rms');

/**
 * The used models for the controller.
 *
 * @var array
 */
	public $uses = array('User', 'Role', 'Iface', 'Study', 'Appointment', 'AnonymousUser');

/**
 * The used components for the controller.
 *
 * @var array
 */
	public $components = array(
		'Paginator',
		'Session',
		'Auth' => array(
			'authorize' => 'Controller',
			'loginRedirect' => array('controller' => 'users', 'action' => 'view'),
			'logoutRedirect' => array('controller' => 'pages', 'action' => 'view'),
			'authenticate' => array(
				'Form' => array('passwordHasher' => array('className' => 'Simple', 'hashType' => 'sha256'))
			)
		)
	);

/**
 * Define pagination criteria.
 *
 * @var array
 * @return null
 */
	public $paginate = array('limit' => 30);

/**
 * Define the actions which can be used by any user, authorized or not.
 *
 * @return null
 */
	public function beforeFilter() {
		// only allow unauthenticated account creation
		parent::beforeFilter();
		$this->Auth->allow('usetoken');
	}

/**
 * The admin index action lists information about all users. This allows the admin to add, edit, or delete entries.
 *
 * @return null
 */
	public function admin_index() {
		$this->Paginator->settings = $this->paginate;
		// grab all the fetched entries
		$this->set('anonymoususers', $this->Paginator->paginate('AnonymousUser'));
	}

/**
 * The admin add action. This will allow the admin to create a new entry.
 *
 * @return null
 */
	public function admin_add() {
		// only work for POST requests
		if ($this->request->is('post')) {
			// store the original password and username
			$this->request->data['AnonymousUser']['used'] = false;
			// create a new entry
			$this->AnonymousUser->create();
			// set the current timestamp for creation and modification
			$this->AnonymousUser->data['AnonymousUser']['created'] = date('Y-m-d H:i:s');
			$this->AnonymousUser->data['AnonymousUser']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->AnonymousUser->save($this->request->data)) {
				// send the welcome email
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to add the user.');
		}

		$this->set('title_for_layout', 'Add Toekn');
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
		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid user.');
		}
		$user = $this->AnonymousUser->findById($id);
		if (!$user) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid user.');
		}

		// only work for PUT requests
		if ($this->request->is(array('user', 'put'))) {
			// set the ID
			$this->AnonymousUser->id = $id;
			// set the current timestamp for modification
			$this->AnonymousUser->data['AnonymousUser']['modified'] = date('Y-m-d H:i:s');

			// attempt to save the entry
			if ($this->AnonymousUser->save($this->request->data)) {
				$this->Session->setFlash('The user has been updated.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to update the user.');
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $user;
		}

		$this->set('title_for_layout', __('Edit User - %s', $AnonymousUser['User']['username']));
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
		} elseif ($this->AnonymousUser->delete($id)) {
			$this->Session->setFlash('The user has been deleted.');
			return $this->redirect(array('action' => 'index'));
		}
	}

/**
 * This allows the anonymous user to use the token that has been given to him
 *
 * @return null
 */
	public function usetoken() {
		// only work for POST requests
		if ($this->request->is('post')) {
			// default to the basic user type
			$user = $this->AnonymousUser->find('first', array('conditions' => array('AnonymousUser.token' => $this->request->data['AnonymousUser']['token'] )));
			$this->request->data['AnonymousUser']['used'] = true;
			// attempt to save the entry
			if ($this->AnonymousUser->save($this->request->data)) {
				$id = $this->User->id;
				// try to send the welcome email
				$this->__sendWelcomeEmail($id);

				// log the user in
				$this->request->data['User'] = array_merge($this->request->data['User'], array('id' => $id));
				$this->Auth->login($this->request->data['User']);
				return $this->redirect($this->Auth->redirectUrl());
			}
			$this->Session->setFlash(__('The user could not be created. Please, try again.'));
		}

		$this->set('title_for_layout', 'Sign Up');
	}

/**
 * The default index simply redirects to the view action.
 *
 * @return null
 */
	public function index() {
		return $this->redirect(array('action' => 'view'));
	}

/**
 * View the logged in user. A user may only view their own page.
 *
 * @throws NotFoundException Thrown if an entry with the logged in user ID is not found.
 * @return null
 */
	public function view() {
		// find the ID
		$id = $this->Auth->user('id');
		// grab the entry
		$user = $this->User->findById($id);

		if (!$user) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid user.');
		}

		// search for interfaces
		if ($this->viewVars['admin']) {
			$this->set('ifaces', $this->Iface->find('all', array('recursive' => 3)));
		} else {
			// only show the unrestricted interfaces
			$ifaces = $this->Iface->find(
				'all',
				array('conditions' => array('Iface.unrestricted' => 1), 'recursive' => 3)
			);
			$this->set('ifaces', $ifaces);
		}

		// search for studies
		$studies = $this->Study->find(
			'all',
			array(
				'recursive' => 3,
				'conditions' => array('Study.start <= CURDATE()', 'Study.end >= CURDATE()')
			)
		);
		$this->set('studies', $studies);
		// do NOT attempt to load all of the logs
		$this->Appointment->hasMany = array();
		$appointments = $this->Appointment->find(
			'all',
			array(
				'recursive' => 3,
				'conditions' => array(
					'Appointment.user_id' => $id,
					'Slot.end >= NOW()',
					'Slot.end < "2038-01-18 22:14:07"'
				),
				'order' => array('Slot.start'),
			)
		);
		$this->set('appointments', $appointments);
		$allAppointments = $this->Appointment->find(
			'all',
			array(
				'recursive' => 3,
				'conditions' => array('Appointment.user_id' => $id, 'Slot.end < "2038-01-18 22:14:07"'),
				'order' => array('Slot.start'),
			)
		);
		$this->set('allAppointments', $allAppointments);

		// store the entry
		$this->set('user', $user);
		$this->set('title_for_layout', 'Account');

		// we will need some RWT libraries
		$this->set('rwt', array('roslibjs' => 'current'));
	}

}
