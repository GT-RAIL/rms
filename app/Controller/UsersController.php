<?php
class UsersController extends AppController {
	public $uses = array('User', 'Role');

	public $components = array(
		'Session',
		'Auth' => array(
			'loginRedirect' => array(
				'controller' => 'users',
				'action' => 'view'
			),
			'logoutRedirect' => array(
				'controller' => 'pages',
				'action' => 'view'
			),
			'authenticate' => array(
				'Form' => array(
					'passwordHasher' => array(
						'className' => 'Simple',
						'hashType' => 'sha256'
					)
				)
			)
		)
	);

	public function beforeFilter() {
		// only allow unauthenticated account creation
		parent::beforeFilter();
		$this->Auth->allow('signup', 'login');
	}

	public function view($id = null) {
		// check if an ID was given -- if not, use the ID
		$id = ($id) ? $id : $this->Auth->user('id');
		// grab the entry
		$user = $this->User->findById($id);
		// store the entry
		$this->set('user', $user);
	}

	public function admin_login() {
		// no different login system for admins
		unset($this->request->params['admin']);
		$this->redirect(array('action' => 'login'));
	}

	public function login() {
		// only work for POST requests
		if ($this->request->is('post')) {
			// check if we have valid login credentials
			if ($this->Auth->login()) {
				return $this->redirect($this->Auth->redirectUrl());
			}
			$this->Session->setFlash(__('Invalid username or password, try again'));
		}
	}

	public function logout() {
		// simply log the user out
		return $this->redirect($this->Auth->logout());
	}

	public function signUp() {
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
				return $this->redirect(array('controller' => 'pages', 'action' => 'view'));
			}
			$this->Session->setFlash(__('The user could not be created. Please, try again.'));
		}
	}
}