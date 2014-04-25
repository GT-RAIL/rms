<?php
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
 * @version		2.0.0
 * @package		app.Controller
 */
class UsersController extends AppController {

	/**
	 * The used helpers for the controller.
	 *
	 * @var array
	 */
	public $helpers = array('Html', 'Form');

	/**
	 * The used models for the controller.
	 *
	 * @var array
	 */
	public $uses = array('User', 'Role');

	/**
	 * The used components for the controller.
	 *
	 * @var array
	 */
	public $components = array(
		'Session',
		'Auth' => array(
			'loginRedirect' => array('controller' => 'users', 'action' => 'view'),
			'logoutRedirect' => array('controller' => 'pages', 'action' => 'view'),
			'authenticate' => array(
				'Form' => array('passwordHasher' => array('className' => 'Simple', 'hashType' => 'sha256'))
			)
		)
	);

	/**
	 * Define the actions which can be used by any user, authorized or not.
	 */
	public function beforeFilter() {
		// only allow unauthenticated account creation
		parent::beforeFilter();
		$this->Auth->allow('signup', 'login');
	}

	public function admin_login() {
		// no different login system for admins
		unset($this->request->params['admin']);
		$this->redirect(array('action' => 'login'));
	}

	public function login() {
		// check if we are already logged in
		if ($this->Auth->user('id')) {
			return $this->redirect($this->Auth->redirectUrl());
		}

		// only work for POST requests
		if ($this->request->is('post')) {
			// check if we have valid login credentials
			if ($this->Auth->login()) {
				return $this->redirect($this->Auth->redirectUrl());
			}
			$this->Session->setFlash(__('Invalid username or password, try again'));
		}
	}

	public function view($id = null) {
		// check if an ID was given -- if not, use the ID
		$id = ($id) ? $id : $this->Auth->user('id');
		// grab the entry
		$user = $this->User->findById($id);
		// store the entry
		$this->set('user', $user);
	}

	public function logout() {
		// simply log the user out
		return $this->redirect($this->Auth->logout());
	}

	public function signup() {
		// check if we are already logged in
		if ($this->Auth->user('id')) {
			return $this->redirect($this->Auth->redirectUrl());
		}

		// only work for POST requests
		if ($this->request->is('post')) {
			// create a new entry
			$this->User->create();
			// set the current timestamp
			$this->request->data['User']['created'] = DboSource::expression('NOW()');
			// default to the basic user type
			$role = $this->Role->find('first', array('conditions' => array('Role.name' => 'basic')));
			$this->request->data['User']['role_id'] = $role['Role']['id'];
			// attempt to save the entry
			if ($this->User->save($this->request->data)) {
				// log the user in
				$id = $this->User->id;
				$this->request->data['User'] = array_merge($this->request->data['User'], array('id' => $id));
				$this->Auth->login($this->request->data['User']);
				return $this->redirect($this->Auth->redirectUrl());
			}
			$this->Session->setFlash(__('The user could not be created. Please, try again.'));
		}
	}
}