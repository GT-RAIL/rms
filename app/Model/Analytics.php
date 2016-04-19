<?php
/**
 * Analytics Model
 *
 * Analytics are connected to the study and connect some basic data baout what the users are
 * accessing this on. 
 *
 * @author		Carl Saldanha - csaldanha3@gatech.edu
 * @copyright	2016 Georgia Institute Of Technology
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Model
 */
class Analytics extends AppModel {

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
		'screen_size' => array(
		),
		'os' => array(
		),
		'browser' => array(

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
 * All Analytics have a user.
 *
 * @var string
 */
	public $belongsTo = array(
		'User' => array('className' => 'User')
	);
}
