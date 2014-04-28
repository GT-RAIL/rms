<?php
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {
	public $validate = array(
//		'username' => array(
//			'notEmpty' => array(
//				'rule' => 'notEmpty',
//				'required' => true,
//				'message' => 'Please enter a valid username.'
//			),
//			'maxLength' => array(
//				'rule' => array('maxLength', 16),
//				'message' => 'Usernames cannot be longer than 16 characters.'
//			),
//			'isUnique' => array(
//				'rule' => 'isUnique',
//				'message' => 'This username already exists.'
//			)
//		),
//		'password' => array(
//			'notEmpty' => array(
//				'rule' => 'notEmpty',
//				'required' => true,
//				'message' => 'Please enter a valid password.'
//			),
//			'maxLength' => array(
//				'rule' => array('maxLength', 64),
//				'message' => 'Password cannot be longer than 64 characters.'
//			)
//		),
		'email' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Please enter a valid email.'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 255),
				'message' => 'Emails cannot be longer than 255 characters.'
			),
			'maxLength' => array(
				'rule' => array('email', true),
				'message' => 'Please supply a valid email address.'
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This email already exists.'
			)
		),
		'fname' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Please enter a valid first name.'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 32),
				'message' => 'First names cannot be longer than 32 characters.'
			)
		),
		'lname' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Please enter a valid last name.'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 32),
				'message' => 'Last names cannot be longer than 32 characters.'
			)
		),
		'repass' => array(
			'equalToField' => array(
				'rule' => array('equalToField', 'password'),
				'message' => 'Password confirmation must match.',
				'on' => 'signup'
			)
		)
	);

	public $belongsTo = 'Role';

	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$passwordHasher = new SimplePasswordHasher(array('hashType' => 'sha256'));
			$this->data[$this->alias]['password'] = $passwordHasher->hash(
				$this->data[$this->alias]['password']
			);
		}
		return true;
	}
}