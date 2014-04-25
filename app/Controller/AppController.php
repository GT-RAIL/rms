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
class AppController extends Controller {

	/**
	 * Set global flags and variables for views. This includes the 'pages' variable for the menu generation and the
	 * `admin` flag for admin checking.
	 */
	public function beforeFilter() {
		parent::beforeFilter();

		// set the main menu for the pages
		$this->loadModel('Page');
		$this->set('pages', $this->Page->find('all'), array('order' => array('Page.index' => 'ASC')));

		// set the admin flag
		$this->loadModel('Role');
		$role = $this->Role->find('first', array('conditions' => array('Role.name' => 'admin')));
		$admin = AuthComponent::user('role_id') === $role['Role']['id'];
		$this->set('admin', $admin);
	}

	/**
	 * The global authorization method. This will be automatically called and used if the authorize controller is an
	 * an included component in the given controller.
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
