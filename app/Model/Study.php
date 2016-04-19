<?php
/**
 * Study Model
 *
 * Studies represent a user study. It contains information about the name, access settings, and start/end dates.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Model
 */
class Study extends AppModel {

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
				'rule' => array('maxLength', 64),
				'message' => 'Names cannot be longer than 64 characters.',
				'required' => true
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This name already exists.',
				'required' => true
			)
		),
		'length' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid length.',
				'required' => 'update'
			),
			'gt' => array(
				'rule' => array('comparison', '>=', 0),
				'message' => 'Lengths must be greater than 0 (or 0 for no limit).',
				'required' => 'update'
			)
		),
		'start' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid start date.',
				'required' => true
			),
			'date' => array(
				'rule' => 'date',
				'message' => 'Please enter a valid start date.',
				'required' => true
			)
		),
		'end' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid end date.',
				'required' => true
			),
			'date' => array(
				'rule' => 'date',
				'message' => 'Please enter a valid end date.',
				'required' => true
			)
		),
		'anonymous' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Anonymous settings cannot be blank.',
				'required' => true
			),
			'geq' => array(
				'rule' => array('comparison', '>=', 0),
				'message' => 'Anonymous settings must be boolean.',
				'required' => true
			),
			'leq' => array(
				'rule' => array('comparison', '<=', 1),
				'message' => 'Anonymous settings must be boolean.',
				'required' => true
			)
		),
		'otf' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'On-the-fly settings cannot be blank.',
				'required' => true
			),
			'geq' => array(
				'rule' => array('comparison', '>=', 0),
				'message' => 'On-the-fly settings must be boolean.',
				'required' => true
			),
			'leq' => array(
				'rule' => array('comparison', '<=', 1),
				'message' => 'On-the-fly settings must be boolean.',
				'required' => true
			)
		),
		'parallel' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Parallel settings cannot be blank.',
				'required' => true
			),
			'geq' => array(
				'rule' => array('comparison', '>=', 0),
				'message' => 'Parallel settings must be boolean.',
				'required' => true
			),
			'leq' => array(
				'rule' => array('comparison', '<=', 1),
				'message' => 'Parallel settings must be boolean.',
				'required' => true
			)
		),
		'repeatable' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Repeatable settings cannot be blank.',
				'required' => true
			),
			'geq' => array(
				'rule' => array('comparison', '>=', 0),
				'message' => 'Repeatable settings must be boolean.',
				'required' => true
			),
			'leq' => array(
				'rule' => array('comparison', '<=', 1),
				'message' => 'Repeatable settings must be boolean.',
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
 * Studies can have many conditions.
 *
 * @var array
 */
	public $hasMany = array('Condition' => array('className' => 'Condition', 'dependent' => true));
}
