<?php
/**
 * Pages Model
 *
 * Pages display a series of articles as content on the RMS. Each page has many articles. A page has a title and menu
 * entry name.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Model
 */
class Page extends AppModel {

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
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This user ID already exists.',
				'required' => 'update'
			)
		),
		'title' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid title.',
				'required' => true
			),
			'maxLength' => array(
				'rule' => array('maxLength', 32),
				'message' => 'Titles cannot be longer than 32 characters.',
				'required' => true
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This page title already exists.',
				'required' => true
			)
		),
		'menu' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid menu name.',
				'required' => true
			),
			'maxLength' => array(
				'rule' => array('maxLength', 16),
				'message' => 'Menu names cannot be longer than 16 characters.',
				'required' => true
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This menu name already exists.',
				'required' => true
			)
		),
		'index' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid index.',
				'required' => 'create'
			),
			'geq' => array(
				'rule' => array('comparison', '>=', 0),
				'message' => 'The index must not be less than 0.',
				'required' => 'create'
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'Each index must be unique.',
				'required' => 'create'
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
 * Pages can have many articles.
 *
 * @var array
 */
	public $hasMany = array(
		'Article' => array('className' => 'Article', 'dependent' => true, 'order' => 'Article.index')
	);
}
