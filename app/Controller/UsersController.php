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
class UsersController extends AppController {

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
	public $uses = array('User', 'Role', 'Iface', 'Study', 'Appointment');

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
	public $paginate = array('limit' => 30, 'order' => array('User.role_id' => 'ASC', 'User.created' => 'ASC'));

/**
 * Define the actions which can be used by any user, authorized or not.
 *
 * @return null
 */
	public function beforeFilter() {
		// only allow unauthenticated account creation
		parent::beforeFilter();
		$this->Auth->allow('signup', 'login', 'username', 'reset');
	}

/**
 * The admin index action lists information about all users. This allows the admin to add, edit, or delete entries.
 *
 * @return null
 */
	public function admin_index() {
		$this->Paginator->settings = $this->paginate;
		// grab all the fetched entries
		$this->set('users', $this->Paginator->paginate('User'));
	}

/**
 * The admin add action. This will allow the admin to create a new entry.
 *
 * @return null
 */
	public function admin_add() {
		// load the roles list
		$roles = $this->User->Role->find('list');
		$this->set('roles', $roles);

		// only work for POST requests
		if ($this->request->is('post')) {
			// store the original password and username
			$username = $this->request->data['User']['username'];
			$password = $this->request->data['User']['password'];

			// create a new entry
			$this->User->create();
			// set the current timestamp for creation and modification
			$this->User->data['User']['created'] = date('Y-m-d H:i:s');
			$this->User->data['User']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->User->save($this->request->data)) {
				// send the welcome email
				$this->__sendCreationEmail($this->User->id, $username, $password);
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
 * @return null
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
			$this->Session->setFlash('Unable to update the user.');
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $user;
		}

		$this->set('title_for_layout', __('Edit User - %s', $user['User']['username']));
	}

/**
 * The admin message action. This allows the admin to send an email message to a given user.
 *
 * @param int $id The ID of the entry to message.
 * @throws NotFoundException Thrown if an entry with the given ID is not found.
 * @return null
 */
	public function admin_message($id = null) {
		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid user.');
		}

		$user = $this->User->findById($id);
		if (!$user) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid user.');
		}

		// only work for POST requests
		if ($this->request->is('post')) {
			$this->sendEmail($id, 'New Private Message', $this->request->data['User']['message']);
			$this->Session->setFlash('The message has been sent.');
			return $this->redirect(array('action' => 'index'));
		}

		$this->set('user', $user);
		$this->set('title_for_layout', __('Message User - %s', $user['User']['username']));
	}

/**
 * Revoke admin privileges from the given user. An admin may not revoke themselves.
 *
 * @param int $id The entry ID to revoke admin privileges from.
 * @throws NotFoundException Thrown if an entry with the given ID is not found.
 * @throws MethodNotAllowedException Thrown if a GET request is made.
 * @return null
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
		if ($this->Auth->user('id') !== $user['User']['id']) {
			// grab the basic ID
			$role = $this->Role->find('first', array('conditions' => array('Role.name' => 'basic')));
			if ($user['User']['role_id'] !== $role['Role']['id']) {
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
 * @return null
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

		// make sure we can grant
		$role = $this->Role->find('first', array('conditions' => array('Role.name' => 'admin')));
		if ($user['User']['role_id'] !== $role['Role']['id']) {
			// update the role
			$this->User->read(null, $user['User']['id']);
			$this->User->saveField('role_id', $role['Role']['id']);
			$this->User->saveField('modified', date('Y-m-d H:i:s'));

			// notify the user
			$this->__sendAdminGrantEmail($id);
		}

		// return to the index
		return $this->redirect(array('action' => 'index'));
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
		} elseif ($this->User->delete($id)) {
			$this->Session->setFlash('The user has been deleted.');
			return $this->redirect(array('action' => 'index'));
		}
	}

/**
 * A redirect to the normal login. Admins do not need a separate login.
 *
 * @return null
 */
	public function admin_login() {
		// no different login system for admins
		unset($this->request->params['admin']);
		$this->redirect(array('action' => 'login'));
	}

/**
 * Log the user in if they are not already logged in. This will check the credentials provided in a POST request.
 *
 * @return null
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
				// check for a cookie
				if ($this->request->data['User']['remember']) {
					$cookieTime = '12 months';

					// remove "remember me"
					unset($this->request->data['User']['remember']);

					// hash the user's password
					$passwordHasher = new SimplePasswordHasher(array('hashType' => 'sha256'));
					$this->request->data['User']['password'] = $passwordHasher->hash(
						$this->request->data['User']['password']
					);

					// write the cookie
					$this->Cookie->write('remember', $this->request->data['User'], true, $cookieTime);
				}

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
 *
 * @return null
 */
	public function logout() {
		// simply log the user out and remove the cookie
		$this->Cookie->delete('remember');
		return $this->redirect($this->Auth->logout());
	}

/**
 * The main sign up page. This will allow any user to register to the site with a basic user account.
 *
 * @return null
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
 * Send a username reminder to a given user.
 *
 * @return null
 */
	public function username() {
		// check if we are already logged in
		if ($this->Auth->user('id')) {
			return $this->redirect($this->Auth->redirectUrl());
		}

		// only work for POST requests
		if ($this->request->is('post')) {
			// check for the email
			$user = $this->User->find(
				'first',
				array('conditions' => array('User.email' => $this->request->data['User']['email']))
			);
			if ($user) {
				// send the email
				$this->__sendUsernameReminderEmail($user['User']['id'], $user['User']['username']);
				$this->Session->setFlash(__('Username reminder sent to %s.', h($this->request->data['User']['email'])));
				return $this->redirect(array('action' => 'login'));
			}
			$this->Session->setFlash('Email does not exist. Please try again.');
		}

		$this->set('title_for_layout', 'Username Reminder');
	}

/**
 * Send a password reset link to a given user.
 *
 * @return null
 */
	public function reset() {
		// check if we are already logged in
		if ($this->Auth->user('id')) {
			return $this->redirect($this->Auth->redirectUrl());
		}

		// only work for POST requests
		if ($this->request->is('post')) {
			// check for the email
			$user = $this->User->find(
				'first',
				array('conditions' => array('User.username' => $this->request->data['User']['username']))
			);
			if ($user) {
				$username = $user['User']['username'];
				// create a new password
				$pw = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);

				// update the user
				$this->User->read(null, $user['User']['id']);
				// set the current timestamp for modification
				$this->User->data['User']['modified'] = date('Y-m-d H:i:s');
				$this->User->data['User']['password'] = $pw;
				// attempt to save the entry
				if ($this->User->save($this->User->data)) {
					// send the email
					$this->__sendPasswordResetEmail($user['User']['id'], $username, $pw);
					$this->Session->setFlash(__('New password sent to %s.', h($user['User']['email'])));
					return $this->redirect(array('action' => 'login'));
				}
				$this->Session->setFlash('Unable to reset your password.');
			}
			$this->Session->setFlash('Username does not exist. Please try again.');
		}

		$this->set('title_for_layout', 'Password Reset');
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

/**
 * The default edit action. This allows the user to edit their entry.
 *
 * @throws NotFoundException Thrown if an entry with the logged in user ID is not found.
 * @return null
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
 * @return null
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

/**
 * The user delete action. This allows the user to delete their own account.
 *
 * @throws MethodNotAllowedException Thrown if a GET request is made.
 * @throws NotFoundException Thrown if an entry with the logged in user ID is not found.
 * @return null
 */
	public function delete() {
		// do not allow GET requests
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		// find the ID
		$id = $this->Auth->user('id');
		// grab the entry
		$user = $this->User->findById($id);

		if (!$user) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid user.');
		}

		// delete the user
		if ($this->User->delete($id)) {
			// log the user out
			return $this->redirect($this->Auth->logout());
		}
	}

/**
 * Send a welcome email if email is enabled in the site settings.
 *
 * @param int $id The user ID to send the welcome email to.
 * @return null
 */
	private function __sendWelcomeEmail($id = null) {
		// create the subject
		$this->loadModel('Setting');
		$setting = $this->Setting->findById(Setting::$default);
		$subject = __('Welcome to %s', h($setting['Setting']['title']));

		// generate the message
		$message = __('Welcome to %s! This email is to confirm your account. ', h($setting['Setting']['title']));
		$message .= 'No additional action is required at this time. Welcome and have fun!';

		// send the message
		$this->sendEmail($id, $subject, $message);
	}

/**
 * Send a welcome email if email is enabled in the site settings for an admin created user.
 *
 * @param int $id The user ID to send the welcome email to.
 * @param string $username The username send to the new user.
 * @param string $password The un-hashed password to send to the new user.
 * @return null
 */
	private function __sendCreationEmail($id = null, $username = '', $password = '') {
		// create the subject
		$this->loadModel('Setting');
		$setting = $this->Setting->findById(Setting::$default);
		$subject = __('Account Created for %s', h($setting['Setting']['title']));

		// generate the message
		$message = __('An admin has created you an account for use with %s! ', h($setting['Setting']['title']));
		$message .= 'This email is to confirm your account. Below are your login credentials. ';
		$message .= 'No additional action is required at this time. Welcome and have fun!\n\n';
		$message .= __(
			'<center><strong>Username:</strong> %s<br /><strong>Password:</strong> %s</center>',
			h($username),
			h($password)
		);

		// send the message
		$this->sendEmail($id, $subject, $message);
	}

/**
 * Send an email notifying a user they are now an admin.
 *
 * @param int $id The user ID to send the access email to.
 * @return null
 */
	private function __sendAdminGrantEmail($id = null) {
		// create the subject
		$this->loadModel('Setting');
		$setting = $this->Setting->findById(Setting::$default);
		$subject = __('Admin Status for %s', h($setting['Setting']['title']));

		// generate the message
		$message = __('An admin has granted you admin privileges on %s! ', h($setting['Setting']['title']));
		$message .= 'No additional action is required at this time. ';
		$message .= 'Remember, with great power comes great responsibility!\n\n';

		// send the message
		$this->sendEmail($id, $subject, $message);
	}

/**
 * Send an email with a username reminder.
 *
 * @param int $id The user ID to send the reminder email to.
 * @param string $username The username send to the user.
 * @return null
 */
	private function __sendUsernameReminderEmail($id = null, $username = '') {
		// create the subject
		$this->loadModel('Setting');
		$setting = $this->Setting->findById(Setting::$default);
		$subject = __('Username for %s', h($setting['Setting']['title']));

		// generate the message
		$message = 'Below is your requested username reminder. No additional action is required at this time.\n\n';
		$message .= __('<center><strong>Username:</strong> %s<br /></center>', h($username));

		// send the message
		$this->sendEmail($id, $subject, $message);
	}

/**
 * Send an email with a new password.
 *
 * @param int $id The user ID to send the reminder email to.
 * @param string $username The username send to the user.
 * @param string $password The un-hashed password to send to the user.
 * @return null
 */
	private function __sendPasswordResetEmail($id = null, $username = '', $password = '') {
		// create the subject
		$this->loadModel('Setting');
		$setting = $this->Setting->findById(Setting::$default);
		$subject = __('New Password for %s', h($setting['Setting']['title']));

		// generate the message
		$message = 'Below is your requested new password.';
		$message .= ' It is recommended that you change your password after logging in.\n\n';
		$message .= __(
			'<center><strong>Username:</strong> %s<br /><strong>Password:</strong> %s</center>',
			h($username),
			h($password)
		);

		// send the message
		$this->sendEmail($id, $subject, $message);
	}
}
