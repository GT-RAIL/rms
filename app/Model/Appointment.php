<?php
/**
 * Appointment Model
 *
 * Appointments represent a reserved user study session slot. It contains information about the associated user (if
 * any) and slot.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Model
 */
class Appointment extends AppModel {

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
		'user_id' => array(
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'User IDs must be greater than 0.',
				'allowEmpty' => true
			)
		),
		'slot_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid slot ID.',
				'required' => true
			),
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'Slot IDs must be greater than 0.',
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
 * All appointments have a slot and user.
 *
 * @var string
 */
	public $belongsTo = array(
		'User' => array('className' => 'User'), 'Slot' => array('className' => 'Slot')
	);

/**
 * Appointments can have many logs.
 *
 * @var array
 */
	public $hasMany = array('Log' => array('className' => 'Log', 'dependent' => true));
}
