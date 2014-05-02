<?php
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
				'copyright' => $setting['Setting']['copyright']
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
								'url' => array('admin' => true, 'controller' => 'Articles', 'action' => 'index')
							)
						)
					),
					array(
						'title' => 'Settings',
						'url' => array('admin' => true, 'controller' => 'Settings')
					)
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
}
