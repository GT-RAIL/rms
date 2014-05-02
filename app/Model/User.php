<?php
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

/**
 * Users Model
 *
 * Users can either be of type admin or basic. Information about the user's name and email are stored inside their
 * account as well as login information.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.Model
 */
class User extends AppModel {

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
				'required' => 'update'
			),
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'IDs must be greater than 0.',
				'required' => 'update'
			)
		),
		'username' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid username.',
				'required' => 'create'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 16),
				'message' => 'Usernames cannot be longer than 16 characters.',
				'required' => 'create'
			),
			'minLength' => array(
				'rule' => array('minLength', 3),
				'message' => 'Usernames cannot be shorter than 3 characters.',
				'required' => 'create'
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This username already exists.',
				'required' => 'create'
			)
		),
		'password' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid password.',
				'on' => array('signup', 'password')
			),
			'maxLength' => array(
				'rule' => array('maxLength', 32),
				'message' => 'Password cannot be longer than 32 characters.',
				'on' => array('signup', 'password')
			),
			'minLength' => array(
				'rule' => array('minLength', 6),
				'message' => 'Password cannot be shorter than 6 characters.',
				'on' => array('signup', 'password')
			),
		),
		'email' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid email.',
				'on' => array('signup')
			),
			'maxLength' => array(
				'rule' => array('maxLength', 255),
				'message' => 'Emails cannot be longer than 255 characters.',
				'on' => array('signup')
			),
			'email' => array(
				'rule' => array('email', true),
				'message' => 'Please supply a valid email address.',
				'on' => array('signup')
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This email already exists.',
				'on' => array('signup', 'edit')
			)
		),
		'fname' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid first name.',
				'on' => array('signup', 'edit')
			),
			'maxLength' => array(
				'rule' => array('maxLength', 32),
				'message' => 'First names cannot be longer than 32 characters.',
				'on' => array('signup', 'edit')
			)
		),
		'lname' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid last name.',
				'on' => array('signup', 'edit')
			),
			'maxLength' => array(
				'rule' => array('maxLength', 32),
				'message' => 'Last names cannot be longer than 32 characters.',
				'on' => array('signup', 'edit')
			)
		),
		'repass' => array(
			'equalToField' => array(
				'rule' => array('equalToField', 'password'),
				'message' => 'Password confirmation must match.',
				'on' => array('signup', 'password')
			)
		),
		'created' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid creation time.',
				'required' => 'create'
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
	 * All roles belong to a single role.
	 *
	 * @var string
	 */
	public $belongsTo = 'Role';

	/**
	 * Check if a new password was provided. If so, hash the password and store it.
	 *
	 * @param array $options Unused in this implementation.
	 * @return bool If the save was successful.
	 */
	public function beforeSave($options = array()) {
		if (isset($this->data['User']['password'])) {
			$passwordHasher = new SimplePasswordHasher(array('hashType' => 'sha256'));
			$this->data['User']['password'] = $passwordHasher->hash(
				$this->data['User']['password']
			);
		}
		return true;
	}
}