<?php
/**
 * Settings Model
 *
 * RMS settings contain information about parameters such as the site name and copyright message.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Model
 */
class Setting extends AppModel {

/**
 * The default ID for the single settings entry.
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
		'title' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid title.',
				'required' => true
			),
			'maxLength' => array(
				'rule' => array('maxLength', 16),
				'message' => 'Titles cannot be longer than 16 characters.',
				'required' => true
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This title already exists.',
				'required' => true
			)
		),
		'copyright' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid copyright message.',
				'required' => true
			),
			'maxLength' => array(
				'rule' => array('maxLength', 32),
				'message' => 'Copyright messages cannot be longer than 32 characters.',
				'required' => true
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This copyright message already exists.',
				'required' => true
			)
		),
		'analytics' => array(
			'maxLength' => array(
				'rule' => array('maxLength', 16),
				'message' => 'Google Analytics IDs cannot be longer than 16 characters.',
				'allowEmpty' => true
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This Google Analytics ID already exists.',
				'allowEmpty' => true
			)
		),
		'email' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Email settings cannot be blank.',
				'required' => true
			),
			'geq' => array(
				'rule' => array('comparison', '>=', 0),
				'message' => 'Email settings must be boolean.',
				'required' => true
			),
			'leq' => array(
				'rule' => array('comparison', '<=', 1),
				'message' => 'Email settings must be boolean.',
				'required' => true
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'Email settings must be unique.',
				'required' => true
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
}
