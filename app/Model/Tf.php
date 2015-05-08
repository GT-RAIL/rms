<?php
/**
 * Tfs Model
 *
 * TFs represent TF client settings. It contains information about the fixed frame and throttle rates.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Model
 */
class Tf extends AppModel {

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
		'frame' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid fixed frame.',
				'required' => true
			),
			'maxLength' => array(
				'rule' => array('maxLength', 255),
				'message' => 'Fixed frames cannot be longer than 255 characters.',
				'required' => true
			)
		),
		'angular' => array(
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'Angular thresholds must be greater than 0.',
				'allowEmpty' => true
			)
		),
		'translational' => array(
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'Translational thresholds must be greater than 0.',
				'allowEmpty' => true
			)
		),
		'rate' => array(
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'Rates must be greater than 0.',
				'allowEmpty' => true
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
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'Each environment can only have a single TF setting.',
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
 * All TFs belong to a single environment.
 *
 * @var string
 */
	public $belongsTo = 'Environment';
}
