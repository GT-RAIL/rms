<?php
class Role extends AppModel {
	public $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Please enter a valid name.'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 16),
				'message' => 'Names cannot be longer than 16 characters.'
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This role name already exists.'
			)
		),
		'description' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Please enter a valid description.'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 255),
				'message' => 'Descriptions cannot be longer than 255 characters.'
			)
		)
	);

	public $hasMany = array('User' => array('className' => 'User', 'dependent' => true));
}