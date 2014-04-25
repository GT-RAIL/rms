<?php
class Article extends AppModel {
	public $validate = array(
		'title' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Please enter a valid title.'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 64),
				'message' => 'Titles cannot be longer than 64 characters.'
			)
		),
		'content' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Please enter some article content.'
			)
		)
	);

	public $belongsTo = 'Page';
}
