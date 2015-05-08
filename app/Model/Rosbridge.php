<?php
/**
 * Rosbridges Model
 *
 * Rosbridges represent a rosbridge servers. It contains information about the port, host, and protocol.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Model
 */
class Rosbridge extends AppModel {

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
				'message' => 'This name already exists.',
				'required' => true
			)
		),
		'protocol_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid role.',
				'required' => true
			),
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'Role IDs must be greater than 0.',
				'required' => true
			)
		),
		'host' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid host or IP.',
				'required' => true
			),
			'maxLength' => array(
				'rule' => array('maxLength', 255),
				'message' => 'Hosts cannot be longer than 255 characters.',
				'required' => true
			)
		),
		'port' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid port.',
				'required' => true
			),
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'Ports must be greater than 0.',
				'required' => true
			),
			'leq' => array(
				'rule' => array('comparison', '<=', 65535),
				'message' => 'Ports cannot be larger than 65535.',
				'required' => true
			)
		),
		'rosauth' => array(
			'maxLength' => array(
				'rule' => array('maxLength', 16),
				'message' => 'rosauth keys must be 16 characters.',
				'allowEmpty' => true
			),
			'minLength' => array(
				'rule' => array('minLength', 16),
				'message' => 'rosauth keys must be 16 characters.',
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
 * All rosbridges have a single protocol.
 *
 * @var string
 */
	public $belongsTo = 'Protocol';

/**
 * rosbridge servers may have many environments.
 *
 * @var array
 */
	public $hasMany = array('Environment' => array('className' => 'Environment'));

/**
 * Check if a new rosauth key was provided. If so, hash the encrypt and store it.
 *
 * @param array $options Unused in this implementation.
 * @return bool If the save was successful.
 */
	public function beforeSave($options = array()) {
		if (isset($this->data['Rosbridge']['rosauth'])) {
			// grab the only setting
			$settingModel = ClassRegistry::init('Setting');
			$setting = $settingModel->findById(Setting::$default);
			// encrypt the key
			$rosauth = Security::encrypt($this->data['Rosbridge']['rosauth'], $setting['Setting']['encrypt']);
			$this->data['Rosbridge']['rosauth'] = $rosauth;
		}
		return true;
	}

/**
 * Decrypt the rosauth key in any results.
 *
 * @param array $results The results to decrypt the rosauth key.
 * @param bool $primary Unused in this implementation.
 * @return array The results withe a decrypted rosauth key.
 */
	public function afterFind($results = array(), $primary = false) {
		// grab the only settings entry
		$settingModel = ClassRegistry::init('Setting');
		$setting = $settingModel->findById(Setting::$default);
		foreach ($results as $key => $val) {
			if (isset($val['Rosbridge']['rosauth'])) {
				// decrypt the password
				$rosauth = Security::decrypt($val['Rosbridge']['rosauth'], $setting['Setting']['encrypt']);
				$results[$key]['Rosbridge']['rosauth'] = $rosauth;
			}
		}
		return $results;
	}
}
