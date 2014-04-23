<?php
class Page extends AppModel {
	public $validate = array(
		'title' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Please enter a valid title.'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 32),
				'message' => 'Titles cannot be longer than 32 characters.'
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This page title already exists.'
			)
		),
		'menu' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Please enter a valid menu name.'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 16),
				'message' => 'Menu names cannot be longer than 16 characters.'
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This menu name already exists.'
			)
		),
		'index' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Please enter a valid index.'
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This index already exists.'
			),
			'numeric' => array(
				'rule' => 'numeric',
				'message' => 'The index must be numeric.'
			)
		)
	);

	public $hasMany = array(
		'Article' => array(
			'className' => 'Article',
			'dependent' => true
		)
	);
}
