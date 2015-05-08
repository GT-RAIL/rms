<?php
App::uses('Security', 'Utility');

/**
 * SMTP Email Settings Model
 *
 * SMTP email settings contain information about email server settings.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Model
 */
class Email extends AppModel {

/**
 * The default ID for the single SMTP email settings entry.
 *
 * @var int
 */
	public static $default = 1;

/**
 * The validation criteria for the model.
 *
 * @var array
 */
	public $validate = array(
		'id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid ID.',
				'required' => true
			),
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'IDs must be greater than 0.',
				'required' => true
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This user ID already exists.',
				'required' => 'update'
			)
		),
		'from' => array(
			'maxLength' => array(
				'rule' => array('maxLength', 255),
				'message' => 'From emails cannot be longer than 255 characters.',
				'allowEmpty' => true
			),
			'email' => array(
				'rule' => array('email', true),
				'message' => 'Please supply a valid email address.',
				'allowEmpty' => true
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This email already exists.',
				'allowEmpty' => true
			)
		),
		'alias' => array(
			'maxLength' => array(
				'rule' => array('maxLength', 32),
				'message' => 'The from alias cannot be longer than 32 characters.',
				'allowEmpty' => true
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This alias already exists.',
				'allowEmpty' => true
			)
		),
		'host' => array(
			'maxLength' => array(
				'rule' => array('maxLength', 255),
				'message' => 'SMTP hosts cannot be longer than 255 characters.',
				'allowEmpty' => true
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This host already exists.',
				'allowEmpty' => true
			)
		),
		'port' => array(
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'Ports must be greater than 0.',
				'allowEmpty' => true
			),
			'leq' => array(
				'rule' => array('comparison', '<=', 65535),
				'message' => 'Ports cannot be larger than 65535.',
				'allowEmpty' => true
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This port already exists.',
				'allowEmpty' => true
			)
		),
		'username' => array(
			'maxLength' => array(
				'rule' => array('maxLength', 255),
				'message' => 'Usernames cannot be longer than 255 characters.',
				'allowEmpty' => true
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This username already exists.',
				'allowEmpty' => true
			)
		),
		'tls' => array(
			'geq' => array(
				'rule' => array('comparison', '>=', 0),
				'message' => 'TLS settings must be boolean.',
				'allowEmpty' => true
			),
			'leq' => array(
				'rule' => array('comparison', '<=', 1),
				'message' => 'TLS settings must be boolean.',
				'allowEmpty' => true
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'TLS settings must be unique.',
				'allowEmpty' => true
			)
		),
		'modified' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid modification time.',
				'required' => true
			)
		)
	);

/**
 * Check if a new password was provided. If so, hash the encrypt and store it.
 *
 * @param array $options Unused in this implementation.
 * @return bool If the save was successful.
 */
	public function beforeSave($options = array()) {
		if (isset($this->data['Email']['password'])) {
			// grab the only setting
			$settingModel = ClassRegistry::init('Setting');
			$setting = $settingModel->findById(Setting::$default);
			// encrypt the password
			$pw = Security::encrypt($this->data['Email']['password'], $setting['Setting']['encrypt']);
			$this->data['Email']['password'] = $pw;
		}
		return true;
	}

/**
 * Decrypt the password in any results.
 *
 * @param array $results The results to decrypt the password.
 * @param bool $primary Unused in this implementation.
 * @return array The results withe a decrypted password.
 */
	public function afterFind($results = array(), $primary = false) {
		// grab the only settings entry
		$settingModel = ClassRegistry::init('Setting');
		$setting = $settingModel->findById(Setting::$default);
		foreach ($results as $key => $val) {
			if (isset($val['Email']['password'])) {
				// decrypt the password
				$pw = Security::decrypt($val['Email']['password'], $setting['Setting']['encrypt']);
				$results[$key]['Email']['password'] = $pw;
			}
		}
		return $results;
	}

/**
 * Create the email settings array for a CakeEmail object.
 *
 * @return array The email settings array for CakeEmail.
 */
	public function getConfig() {
		// grab the only email settings entry
		$email = $this->findById(Email::$default);

		// check what fields we have
		$smtp = array();
		$smtp['transport'] = 'Smtp';
		$smtp['timeout'] = 30;
		$smtp['log'] = false;
		$smtp['client'] = null;
		$smtp['emailFormat'] = 'html';
		$smtp['template'] = 'default';
		$smtp['layout'] = 'default';
		if (strlen($email['Email']['from']) > 0) {
			if (strlen($email['Email']['alias']) > 0) {
				$smtp['from'] = array($email['Email']['from'] => $email['Email']['alias']);
			} else {
				$smtp['from'] = array($email['Email']['from'] => $email['Email']['from']);
			}
		}
		if (strlen($email['Email']['host']) > 0) {
			$smtp['host'] = $email['Email']['host'];
		}
		if (strlen($email['Email']['port']) > 0) {
			$smtp['port'] = $email['Email']['port'];
		}
		if (strlen($email['Email']['username']) > 0) {
			$smtp['username'] = $email['Email']['username'];
		}
		if (strlen($email['Email']['password']) > 0) {
			$smtp['password'] = $email['Email']['password'];
		}
		if (strlen($email['Email']['tls']) > 0 && $email['Email']['tls']) {
			$smtp['tls'] = true;
		}

		return $smtp;
	}
}
