<?php
/**
 * Subscriptions Model
 *
 * Subscription settings allow users to enable/disable automated emails.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Model
 */
class Subscription extends AppModel {

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
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid username.',
				'required' => 'create'
			),
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'User IDs must be greater than 0.',
				'required' => 'create'
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This user ID already exists.',
				'required' => 'create'
			)
		),
		'newsletter' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Newsletter settings cannot be blank.',
				'required' => true
			),
			'geq' => array(
				'rule' => array('comparison', '>=', 0),
				'message' => 'Newsletter settings must be boolean.',
				'required' => true
			),
			'leq' => array(
				'rule' => array('comparison', '<=', 1),
				'message' => 'Newsletter settings must be boolean.',
				'required' => true
			)
		),
		'studies' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Study settings cannot be blank.',
				'required' => true
			),
			'geq' => array(
				'rule' => array('comparison', '>=', 0),
				'message' => 'Study settings must be boolean.',
				'required' => true
			),
			'leq' => array(
				'rule' => array('comparison', '<=', 1),
				'message' => 'Study settings must be boolean.',
				'required' => true
			)
		),
		'reminders' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Study reminder settings cannot be blank.',
				'required' => true
			),
			'geq' => array(
				'rule' => array('comparison', '>=', 0),
				'message' => 'Study reminder settings must be boolean.',
				'required' => true
			),
			'leq' => array(
				'rule' => array('comparison', '<=', 1),
				'message' => 'Study reminder settings must be boolean.',
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
 * All subscriptions have a user.
 *
 * @var string
 */
	public $belongsTo = 'User';
}
