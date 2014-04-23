<?php
class UsersController extends AppController {
	public $uses = array('User', 'Role');

	public $components = array(
		'Session',
		'Auth' => array(
			'loginRedirect' => array(
				'controller' => 'pages',
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
		$this->Auth->allow('signup');
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

	public function signup() {
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
				$this->Session->setFlash(__('The user has been saved'));
				return $this->redirect(array('controller' => 'pages', 'action' => 'view'));
			}
			$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
		}
	}
}