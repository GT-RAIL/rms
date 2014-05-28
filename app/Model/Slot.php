<?php
/**
 * Slot Model
 *
 * Slots represent a user study session slot. It contains information about the associated interface, environment, and
 * start/end time.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.Model
 */
class Slot extends AppModel {

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
		'condition_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid condition ID.',
				'required' => true
			),
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'Condition IDs must be greater than 0.',
				'required' => true
			)
		),
		'environment_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid environment ID.',
				'required' => true
			),
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'Environment IDs must be greater than 0.',
				'required' => true
			)
		),
		'start' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid start time.',
				'required' => true
			)
		),
		'end' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid end time.',
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
	 * All slots belong to a single condition and environment.
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Condition' => array('className' => 'Condition'), 'Environment' => array('className' => 'Environment')
	);

	/**
	 * Conditions can have many appointments.
	 *
	 * @var array
	 */
	public $hasMany = array('Appointment' => array('className' => 'Appointment', 'dependent' => true));
}
