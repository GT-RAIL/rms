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
	public $helpers = array('Html', 'Form', 'Paginator');

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
	 */
	public $paginate = array(
		'limit' => 25,
		'order' => array(
			'User.role_id' => 'ASC', 'User.created' => 'ASC'
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

	/**
	 * The admin index action lists information about all users. This allows the admin to add, edit, or delete entries.
	 */
	public function admin_index() {
		$this->Paginator->settings = $this->paginate;
		// grab all the fetched entries
		$this->set('users', $this->Paginator->paginate('User'));
	}

	/**
	 * The admin view allows and admin to view any user's extended information.
	 *
	 * @param int $id The ID of the entry to view.
	 * @throws NotFoundException Thrown if an entry with the given ID is not found.
	 */
	public function admin_view($id = null) {
		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid user.');
		}

		$user = $this->User->findById($id);
		if (!$user) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid user.');
		}

		// store the entry
		$this->set('user', $user);
		$this->set('title_for_layout', $user['User']['username']);
	}

	/**
	 * The admin add action. This will allow the admin to create a new entry.
	 */
	public function admin_add() {
		// load the roles list
		$roles = $this->User->Role->find('list');
		$this->set('roles', $roles);

		// only work for POST requests
		if ($this->request->is('post')) {
			// create a new entry
			$this->User->create();
			// set the current timestamp for creation and modification
			$this->User->data['User']['created'] = date('Y-m-d H:i:s');
			$this->User->data['User']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash('The user has been saved.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to add the user.');
		}

		$this->set('title_for_layout', 'Add User');
	}

	/**
	 * The admin edit action. This allows the admin to edit an existing entry.
	 *
	 * @param int $id The ID of the entry to edit.
	 * @throws NotFoundException Thrown if an entry with the given ID is not found.
	 */
	public function admin_edit($id = null) {
		// load the roles list
		$roles = $this->User->Role->find('list');
		$this->set('roles', $roles);

		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid user.');
		}

		$user = $this->User->findById($id);
		if (!$user) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid user.');
		}

		// only work for PUT requests
		if ($this->request->is(array('user', 'put'))) {
			// set the ID
			$this->User->id = $id;
			// set the current timestamp for modification
			$this->User->data['User']['modified'] = date('Y-m-d H:i:s');

			// attempt to save the entry
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash('The user has been updated.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to update the article.');
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $user;
		}

		$this->set('title_for_layout', __('Edit User - %s', $user['User']['username']));
	}

	/**
	 * Revoke admin privileges from the given user. An admin may not revoke themselves.
	 *
	 * @param int $id The entry ID to revoke admin privileges from.
	 * @throws NotFoundException Thrown if an entry with the given ID is not found.
	 * @throws MethodNotAllowedException Thrown if a GET request is made.
	 */
	public function admin_revoke($id = null) {
		// do not allow GET requests
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid user.');
		}

		$user = $this->User->findById($id);
		if (!$user) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid user.');
		}

		// make sure we can revoke
		if($this->Auth->user('id') !== $user['User']['id']) {
			// grab the basic ID
			$role = $this->Role->find('first', array('conditions' => array('Role.name' => 'basic')));
			if($user['User']['role_id'] !==  $role['Role']['id']) {
				// update the role
				$this->User->read(null, $user['User']['id']);
				$this->User->saveField('role_id', $role['Role']['id']);
				$this->User->saveField('modified', date('Y-m-d H:i:s'));
			}
		}

		// return to the index
		return $this->redirect(array('action' => 'index'));
	}

	/**
	 * Grant admin privileges to the given user.
	 *
	 * @param int $id The entry ID to grant admin privileges to.
	 * @throws NotFoundException Thrown if an entry with the given ID is not found.
	 * @throws MethodNotAllowedException Thrown if a GET request is made.
	 */
	public function admin_grant($id = null) {
		// do not allow GET requests
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid user.');
		}

		$user = $this->User->findById($id);
		if (!$user) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid user.');
		}

		// make sure we can revoke
		$role = $this->Role->find('first', array('conditions' => array('Role.name' => 'admin')));
		if($user['User']['role_id'] !==  $role['Role']['id']) {
			// update the role
			$this->User->read(null, $user['User']['id']);
			$this->User->saveField('role_id', $role['Role']['id']);
			$this->User->saveField('modified', date('Y-m-d H:i:s'));
		}

		// return to the index
		return $this->redirect(array('action' => 'index'));
	}

	/**
	 * The admin delete action. This allows the admin to delete an existing entry. An admin may not delete themselves.
	 *
	 * @param int $id The ID of the entry to delete.
	 * @throws MethodNotAllowedException Thrown if a GET request is made.
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
		} else if ($this->User->delete($id)) {
			$this->Session->setFlash('The user has been deleted.');
			return $this->redirect(array('action' => 'index'));
		}
	}

	/**
	 * A redirect to the normal login. Admins do not need a separate login.
	 */
	public function admin_login() {
		// no different login system for admins
		unset($this->request->params['admin']);
		$this->redirect(array('action' => 'login'));
	}

	/**
	 * Log the user in if they are not already logged in. This will check the credentials provided in a POST request.
	 */
	public function login() {
		// check if we are already logged in
		if ($this->Auth->user('id')) {
			return $this->redirect($this->Auth->redirectUrl());
		}

		// only work for POST requests
		if ($this->request->is('post')) {
			// check if we have valid login credentials
			if ($this->Auth->login()) {
				// update the user's counter
				$id = $this->Auth->user('id');
				$this->User->read(null, $id);
				$this->User->saveField('logins', $this->Auth->user('logins') + 1);
				$this->User->saveField('visit', date('Y-m-d H:i:s'));

				return $this->redirect($this->Auth->redirectUrl());
			}
			$this->Session->setFlash('Invalid username or password, try again');
		}

		$this->set('title_for_layout', 'Sign In');
	}

	/**
	 * Log the logged in user out.
	 */
	public function logout() {
		// simply log the user out
		return $this->redirect($this->Auth->logout());
	}

	/**
	 * The main sign up page. This will allow any user to register to the site with a basic user account.
	 */
	public function signup() {
		// check if we are already logged in
		if ($this->Auth->user('id')) {
			return $this->redirect($this->Auth->redirectUrl());
		}

		// only work for POST requests
		if ($this->request->is('post')) {
			// create a new entry
			$this->User->create();
			// set the current timestamp for creation and modification
			$this->User->data['User']['created'] = date('Y-m-d H:i:s');
			$this->User->data['User']['modified'] = date('Y-m-d H:i:s');
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

		$this->set('title_for_layout', 'Sign Up');
	}

	/**
	 * The default index simply redirects to the view action.
	 */
	public function index() {
		return $this->redirect(array('action' => 'view'));
	}

	/**
	 * View the logged in user. A user may only view their own page.
	 *
	 * @throws NotFoundException Thrown if an entry with the logged in user ID is not found.
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

		// store the entry
		$this->set('user', $user);
		$this->set('title_for_layout', 'Account');
	}

	/**
	 * The default edit action. This allows the user to edit their entry.
	 *
	 * @throws NotFoundException Thrown if an entry with the logged in user ID is not found.
	 */
	public function edit() {
		// find the ID
		$id = $this->Auth->user('id');
		// grab the entry
		$user = $this->User->findById($id);

		if (!$user) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid user.');
		}

		// only work for PUT requests
		if ($this->request->is(array('user', 'put'))) {
			// set the ID
			$this->User->id = $id;
			// set the current timestamp for modification
			$this->User->data['User']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash('Your information has been updated.');
				// update the user's session
				$this->Session->write('Auth', $this->User->read(null, $this->Auth->User('id')));
				return $this->redirect(array('action' => 'view'));
			}
			$this->Session->setFlash('Unable to update your information.');
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $user;
		}

		$this->set('title_for_layout', __('Edit User - %s', $user['User']['username']));
	}

	/**
	 * The password change action. This allows the user to edit their password entry.
	 *
	 * @throws NotFoundException Thrown if an entry with the logged in user ID is not found.
	 */
	public function password() {
		// find the ID
		$id = $this->Auth->user('id');
		// grab the entry
		$user = $this->User->findById($id);

		if (!$user) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid user.');
		}

		// only work for PUT requests
		if ($this->request->is(array('user', 'put'))) {
			// set the ID
			$this->User->id = $id;
			// set the current timestamp for modification
			$this->User->data['User']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash('Your information has been updated.');
				// update the user's session
				$this->Session->write('Auth', $this->User->read(null, $this->Auth->User('id')));
				return $this->redirect(array('action' => 'view'));
			}
			$this->Session->setFlash('Unable to update your information.');
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $user;
		}

		$this->set('title_for_layout', __('Change Password - %s', $user['User']['username']));
	}
}
