<?php
App::uses('CakeEmail', 'Network/Email');

/**
 * Main Application Controller
 *
 * Add your application-wide methods in the class below, your controllers will inherit them. This is useful for setting
 * global flags and menu variables for views. A global authorization function is also defined for all admin rights in
 * RMS controllers.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.Controller
 */
abstract class AppController extends Controller {

	/**
	 * Set global flags and variables for views. This includes the 'pages' variable for the menu generation and the
	 * `admin` flag for admin checking.
	 */
	public function beforeFilter() {
		parent::beforeFilter();

		// grab site settings
		$this->loadModel('Setting');
		$setting = $this->Setting->findById(Setting::$DEFAULT_ID);
		$settingSubset = array(
			'Setting' => array(
				'title' => $setting['Setting']['title'],
				'copyright' => $setting['Setting']['copyright'],
				'analytics' => $setting['Setting']['analytics'],
				'email' => $setting['Setting']['email']
			)
		);
		$this->set('setting', $settingSubset);

		// set the main menu for the pages
		$this->loadModel('Page');
		$pages =  $this->Page->find('all', array('order' => array('Page.index' => 'ASC')));
		$menu = array();
		foreach ($pages as $page) {
			$menu[] = array(
				'title' => $page['Page']['menu'],
				'url' => array(
					'admin' => false,
					'controller' => 'pages',
					'action' => 'view',
					$page['Page']['id']
				)
			);
		}
		$this->set('menu', $menu);

		// check for a logged in user
		$loggedIn = AuthComponent::user('id') !== null;
		$this->set('loggedIn', $loggedIn);

		// set default admin flag and admin menu
		$this->set('admin', false);
		$this->set('adminMenu', NULL);

		if($loggedIn) {
			// now check the admin flag
			$this->loadModel('Role');
			$role = $this->Role->find('first', array('conditions' => array('Role.name' => 'admin')));
			$admin = AuthComponent::user('role_id') === $role['Role']['id'];
			$this->set('admin', $admin);

			// check if we should create the admin menu
			if ($admin) {
				$adminMenu = array(
					array(
						'title' => 'Content',
						'menu' => array(
							array(
								'title' => 'Pages',
								'url' => array('admin' => true, 'controller' => 'pages', 'action' => 'index')
							),
							array(
								'title' => 'Articles',
								'url' => array('admin' => true, 'controller' => 'articles', 'action' => 'index')
							),
							array(
								'title' => 'Send Newsletter',
								'url' => array(
									'admin' => true,
									'controller' => 'subscriptions',
									'action' => 'newsletter'
								)
							)
						),
						'url' => array('admin' => true, 'controller' => 'content', 'action' => 'index')
					),
					array(
						'title' => 'ROS Settings',
						'menu' => array(
							array(
								'title' => 'Environments',
								'url' => array('admin' => true, 'controller' => 'environments', 'action' => 'index')
							),
							array(
								'title' => 'rosbridge',
								'url' => array('admin' => true, 'controller' => 'rosbridges', 'action' => 'index')
							),
							array(
								'title' => 'MJPEG Server',
								'url' => array('admin' => true, 'controller' => 'mjpegs', 'action' => 'index')
							),
							array(
								'title' => 'Topics & Widgets',
								'url' => array('admin' => true, 'controller' => 'widget', 'action' => 'index')
							),
							array(
								'title' => 'Interfaces',
								'url' => array('admin' => true, 'controller' => 'ifaces', 'action' => 'index')
							)
						),
						'url' => array('admin' => true, 'controller' => 'ros', 'action' => 'index')
					),
					array(
						'title' => 'Experiments',
						'menu' => array(
							array(
								'title' => 'Studies',
								'url' => array('admin' => true, 'controller' => 'studies', 'action' => 'index')
							),
							array(
								'title' => 'Conditions',
								'url' => array('admin' => true, 'controller' => 'conditions', 'action' => 'index')
							),
							array(
								'title' => 'Slots',
								'url' => array('admin' => true, 'controller' => 'slots', 'action' => 'index')
							)
						),
						'url' => array('admin' => true, 'controller' => 'experiment', 'action' => 'index')
					),
					array(
						'title' => 'Accounts',
						'url' => array('admin' => true, 'controller' => 'users', 'action' => 'index')
					),
					array(
						'title' => 'Settings',
						'menu' => array(
							array(
								'title' => 'Site Settings',
								'url' => array('admin' => true, 'controller' => 'settings', 'action' => 'index')
							),
							array(
								'title' => 'Email Settings',
								'url' => array('admin' => true, 'controller' => 'emails', 'action' => 'index')
							)
						),
						'url' => array('admin' => true, 'controller' => 'global', 'action' => 'index')
					),

				);
				$this->set('adminMenu', $adminMenu);
			}
		}
	}

	/**
	 * The global authorization method. This will be automatically called and used if the authorize controller is an
	 * an included component in the given controller.
	 *
	 * @return bool Returns if the user is authorized.
	 */
	public function isAuthorized() {
		// any registered user can access public functions
		if (empty($this->request->params['admin'])) {
			return true;
		}

		// only admins can access admin functions
		if (isset($this->request->params['admin'])) {
			return $this->viewVars['admin'];
		}

		// default deny
		return false;
	}

	/**
	 * Send an email message to a user. No effect is made if email is disabled.
	 *
	 * @param int $id The user ID to send the message email to.
	 * @param string $subject The message subject.
	 * @param string $message The message text.
	 * @throws NotFoundException Thrown if an invalid user ID is given.
	 */
	public function sendEmail($id = null, $subject = '', $message = '') {
		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid user.');
		}

		$this->loadModel('User');
		$user = $this->User->findById($id);
		if (!$user) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid user.');
		}

		// check if we are sending a welcome email
		$this->loadModel('Setting');
		$setting = $this->Setting->findById(Setting::$DEFAULT_ID);
		if ($setting['Setting']['email']) {
			$email = new CakeEmail('dynamic');
			$email->to($user['User']['email']);
			$email->subject(h($subject));

			// generate the content
			$content = __('Dear %s,\n\n', h($user['User']['fname']));
			$content .= $message . '\n\n';
			$content .= __('--The %s Team', h($setting['Setting']['title']));
			$email->send($content);
		}
	}

	/**
	 * Send an email to a group of given users. No effect is made if email is disabled.
	 *
	 * @param array $bcc The email addresses to send to.
	 * @param string $subject The message subject.
	 * @param string $message The message text.
	 */
	public function sendBatchEmail($bcc = array(), $subject = '', $message = '') {
		// check if we are sending a welcome email
		$this->loadModel('Setting');
		$setting = $this->Setting->findById(Setting::$DEFAULT_ID);
		if ($setting['Setting']['email']) {
			$email = new CakeEmail('dynamic');
			$email->bcc($bcc);
			$email->subject(h($subject));

			// generate the content
			$content = $message . '\n\n';
			$content .= __('--The %s Team', h($setting['Setting']['title']));
			$email->send($content);
		}
	}
}
