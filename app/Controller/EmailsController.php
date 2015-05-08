<?php
/**
 * Email Settings Controller
 *
 * Email settings can be used to send automated emails from the RMS. This includes features like password resets and
 * account creation emails.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */
class EmailsController extends AppController {

/**
 * The used helpers for the controller.
 *
 * @var array
 */
	public $helpers = array('Html', 'Form');

/**
 * The used components for the controller.
 *
 * @var array
 */
	public $components = array('Session', 'Auth' => array('authorize' => 'Controller'));

/**
 * The used models for the controller.
 *
 * @var array
 */
	public $uses = array('Email', 'Setting');

/**
 * The admin index action lists all SMTP settings. This allows the admin to edit the SMTP settings.
 *
 * @return null
 */
	public function admin_index() {
		// grab the only settings entry
		$setting = $this->Setting->findById(Setting::$default);
		$this->set('setting', $setting);

		// grab the only email settings entry
		$email = $this->Email->findById(Email::$default);
		$this->set('email', $email);
	}

/**
 * The admin edit action. This allows the admin to edit the SMTP settings.
 *
 * @return null
 */
	public function admin_edit() {
		// only work for PUT requests
		if ($this->request->is(array('email', 'put'))) {
			// set the ID
			$this->loadModel('Email');
			$this->Email->id = Email::$default;
			// set the current timestamp for modification
			$this->Email->data['Email']['modified'] = date('Y-m-d H:i:s');

			// check for empty fields
			if (strlen($this->request->data['Email']['from']) === 0) {
				$this->request->data['Email']['from'] = null;
			}
			if (strlen($this->request->data['Email']['alias']) === 0) {
				$this->request->data['Email']['alias'] = null;
			}
			if (strlen($this->request->data['Email']['host']) === 0) {
				$this->request->data['Email']['host'] = null;
			}
			if (strlen($this->request->data['Email']['port']) === 0) {
				$this->request->data['Email']['port'] = null;
			}
			if (strlen($this->request->data['Email']['username']) === 0) {
				$this->request->data['Email']['username'] = null;
			}
			if (strlen($this->request->data['Email']['tls']) === 0 || !$this->request->data['Email']['tls']) {
				$this->request->data['Email']['tls'] = null;
			}
			if (strlen($this->request->data['Email']['password']) === 0) {
				$this->request->data['Email']['password'] = null;
			}

			// attempt to save the entry
			if ($this->Email->save($this->request->data)) {
				$this->Session->setFlash('The email settings have been updated.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to update the email settings.');
		} else {
			// grab the only email settings entry
			$email = $this->Email->findById(Email::$default);
			$this->set('email', $email);
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			// grab the only entry
			$email = $this->Email->findById(Email::$default);
			$this->request->data = $email;
		}

		$this->set('title_for_layout', 'Edit Email Settings');
	}
}
