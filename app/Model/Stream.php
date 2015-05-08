<?php
/**
 * Streams Model
 *
 * Streams represent a MJPEG server streams. It contains information about the ROS image topic and streaming parameters.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Model
 */
class Stream extends AppModel {

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
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid name.',
				'required' => true
			),
			'maxLength' => array(
				'rule' => array('maxLength', 32),
				'message' => 'Names cannot be longer than 32 characters.',
				'required' => true
			)
		),
		'topic' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid ROS image topic.',
				'required' => true
			),
			'maxLength' => array(
				'rule' => array('maxLength', 255),
				'message' => 'Topics cannot be longer than 255 characters.',
				'required' => true
			)
		),
		'width' => array(
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'Width must be greater than 0.',
				'allowEmpty' => true
			)
		),
		'height' => array(
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'Height must be greater than 0.',
				'allowEmpty' => true
			)
		),
		'quality' => array(
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'Quality must be greater than 0.',
				'allowEmpty' => true
			),
			'leq' => array(
				'rule' => array('comparison', '<=', 100),
				'message' => 'Quality must be no more than 100.',
				'allowEmpty' => true
			)
		),
		'invert' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Inversion setting cannot be blank.',
				'required' => true
			),
			'geq' => array(
				'rule' => array('comparison', '>=', 0),
				'message' => 'Inversion setting must be boolean.',
				'required' => true
			),
			'leq' => array(
				'rule' => array('comparison', '<=', 1),
				'message' => 'Inversion setting must be boolean.',
				'required' => true
			)
		),
		'environment_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid environment.',
				'required' => true
			),
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'Environment IDs must be greater than 0.',
				'required' => true
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
 * All streams belong to a single environment.
 *
 * @var string
 */
	public $belongsTo = 'Environment';
}
