<?php
/**
 * Log Model
 *
 * Logs are used to store study log data.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Model
 */
class Log extends AppModel {

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
				'message' => 'This ID already exists.',
				'required' => 'update'
			)
		),
		'appointment_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid appointment.',
				'required' => true
			),
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'Appointment IDs must be greater than 0.',
				'required' => true
			)
		),
		'type_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid type.',
				'required' => true
			),
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'Type IDs must be greater than 0.',
				'required' => true
			)
		),
		'label' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid label.',
				'required' => true
			),
			'maxLength' => array(
				'rule' => array('maxLength', 255),
				'message' => 'Labels cannot be longer than 255 characters.',
				'required' => true
			)
		),
		'entry' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter the log entry data.',
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
 * All logs belong to a single appointment and type.
 *
 * @var array
 */
	public $belongsTo = array('Appointment', 'Type');
}
