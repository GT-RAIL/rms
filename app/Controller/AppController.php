<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public function isAuthorized($user = null) {
		// any registered user can access public functions
		if ($user && empty($this->request->params['admin'])) {
			return true;
		}

		// only admins can access admin functions
		if (isset($this->request->params['admin'])) {
			return $this->viewVars['admin'];
		}

		// default deny
		return false;
	}

	public function beforeFilter()  {
		parent::beforeFilter();
		// set the menu items
		$this->setMenuItems();
		// set the admin flag
		$this->setAdminFlag();
	}

	private function setMenuItems() {
		// set the main menu for the pages
		$this->loadModel('Page');
		$this->set('pages', $this->Page->find('all'), array('order' => array('Page.index' => 'ASC')));
	}

	private function setAdminFlag() {
		// check if the logged in user is an admin
		$this->loadModel('Role');
		$role = $this->Role->find('first', array('conditions' => array('Role.name' => 'admin')));
		$admin = AuthComponent::user('role_id') === $role['Role']['id'];
		$this->set('admin', $admin);
	}
}
