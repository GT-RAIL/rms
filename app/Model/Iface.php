<?php
/**
 * RMS Interfaces Model
 *
 * Interfaces represent an RMS interface. It contains information about the name and class information. Ifaces is used
 * to prevent using the reserved PHP keyword interface.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Model
 */
class Iface extends AppModel {

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
		'unrestricted' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Unrestricted settings cannot be blank.',
				'required' => true
			),
			'geq' => array(
				'rule' => array('comparison', '>=', 0),
				'message' => 'Unrestricted settings must be boolean.',
				'required' => true
			),
			'leq' => array(
				'rule' => array('comparison', '<=', 1),
				'message' => 'Unrestricted settings must be boolean.',
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
 * Interfaces can have associated environments.
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Environment' =>
			array(
				'className' => 'Environment',
				'joinTable' => 'ifaces_environments',
				'foreignKey' => 'iface_id',
				'associationForeignKey' => 'environment_id',
				'unique' => true
			)
	);

/**
 * Interfaces can have many conditions.
 *
 * @var array
 */
	public $hasMany = array('Condition' => array('className' => 'Condition', 'dependent' => false));
}
