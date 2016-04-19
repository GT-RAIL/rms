<?php
/**
 * Environments Model
 *
 * Environments are linked to a rosbridge and MJPEG server. Each has a unique name.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Model
 */
class Environment extends AppModel {

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
				'rule' => array('maxLength', 255),
				'message' => 'Names cannot be longer than 255 characters.',
				'required' => true
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This namee already exists.',
				'required' => true
			)
		),
		'rosbridge_id' => array(
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'rosbridge IDs must be greater than 0.',
				'allowEmpty' => true
			)
		),
		'mjpeg_id' => array(
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'MJPEG IDs must be greater than 0.',
				'allowEmpty' => true
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
 * All environments have a rosbridge and MJPEG server.
 *
 * @var array
 */
	public $belongsTo = array(
		'Rosbridge' => array('className' => 'Rosbridge'),
		'Mjpeg' => array('className' => 'Mjpeg')
	);

/**
 * Environments can have associated streams and topics as well as study conditions.
 *
 * @var array
 */
	public $hasMany = array(
		'Stream' => array('className' => 'Stream', 'dependent' => true),
		'Teleop' => array('className' => 'Teleop', 'dependent' => true),
		'Marker' => array('className' => 'Marker', 'dependent' => true),
		'Im' => array('className' => 'Im', 'dependent' => true),
		'Urdf' => array('className' => 'Urdf', 'dependent' => true),
		'Condition' => array('className' => 'Condition', 'dependent' => false)
	);

/**
 * Environments can have a single associated TF.
 *
 * @var array
 */
	public $hasOne = array('Tf' => array('className' => 'Tf', 'dependent' => true));

/**
 * Environments can have associated interfaces.
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Iface' =>
			array(
				'className' => 'Iface',
				'joinTable' => 'ifaces_environments',
				'foreignKey' => 'environment_id',
				'associationForeignKey' => 'iface_id',
				'unique' => true
			)
	);
}
